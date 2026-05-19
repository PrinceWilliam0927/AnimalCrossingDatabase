<?php

class LocalStatement
{
    private $db;
    private $query;
    private $params = array();
    private $rows = array();

    public function __construct($db, $query)
    {
        $this->db = $db;
        $this->query = $query;
    }

    public function bindParam($name, &$value)
    {
        $this->params[$name] = &$value;
    }

    public function execute()
    {
        $this->rows = $this->db->run($this->query, $this->params);
        return true;
    }

    public function fetchAll()
    {
        return $this->rows;
    }

    public function closeCursor()
    {
        $this->rows = array();
    }
}

class LocalDb
{
    private $baseDir;
    private $storePath;
    private $tables = array(
        'Artwork' => 'artwork.csv',
        'Fish_N' => 'fish_N.csv',
        'Fish_S' => 'fish_S.csv',
        'Fossils' => 'fossils.csv',
        'Insects_N' => 'insects_N.csv',
        'Insects_S' => 'insects_S.csv',
        'Music' => 'music.csv',
        'Sea_Creatures_N' => 'sea_creatures_N.csv',
        'Sea_Creatures_S' => 'sea_creatures_S.csv',
    );
    private $cache = array();

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
        $this->storePath = $baseDir . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'local-store.json';
        $this->ensureStore();
    }

    public function prepare($query)
    {
        return new LocalStatement($this, $query);
    }

    public function run($query, $params)
    {
        $normalized = preg_replace('/\s+/', ' ', trim($query));
        $lower = strtolower($normalized);

        if (preg_match('/^select \* from ([a-z_]+)$/i', $normalized, $m)) {
            return $this->tableRows($this->canonicalTable($m[1]));
        }

        if (preg_match('/^select \* from ([a-z_]+) where name like :search$/i', $normalized, $m)) {
            $rows = $this->tableRows($this->canonicalTable($m[1]));
            $needle = strtolower(rtrim($this->param($params, ':search'), '%'));
            return array_values(array_filter($rows, function ($row) use ($needle) {
                return strpos(strtolower($row['Name']), $needle) === 0;
            }));
        }

        if (preg_match('/^select \* from artwork where real_art_title like :search$/i', $normalized)) {
            $needle = strtolower(rtrim($this->param($params, ':search'), '%'));
            return array_values(array_filter($this->tableRows('Artwork'), function ($row) use ($needle) {
                return strpos(strtolower($row['Real_Art_Title']), $needle) === 0;
            }));
        }

        if (preg_match('/^select \* from ([a-z_]+) where name in/i', $normalized, $m)) {
            $user = $this->param($params, ':user_name');
            $names = $this->collectionNames($user);
            return array_values(array_filter($this->tableRows($this->canonicalTable($m[1])), function ($row) use ($names) {
                return in_array($row['Name'], $names, true);
            }));
        }

        if ($lower === 'select name, password, user_type from user where name=:name' ||
            $lower === 'select user_type from user where name=:name') {
            $user = $this->findUser($this->param($params, ':name'));
            return $user ? array($user) : array();
        }

        if ($lower === 'select * from user_favorite where name=:name' ||
            $lower === 'select fav_char from user_favorite where name=:name') {
            return array($this->favoriteRow($this->param($params, ':name')));
        }

        if ($lower === 'select name from special_npcs') {
            return $this->specialNpcs();
        }

        if ($lower === 'select picture from special_npcs where name=:favchar') {
            $name = $this->param($params, ':favchar');
            return array_values(array_filter($this->specialNpcs(), function ($row) use ($name) {
                return $row['Name'] === $name;
            }));
        }

        if (strpos($lower, 'select * from user_collection where user_name=:user_name') === 0) {
            $store = $this->loadStore();
            $user = $this->param($params, ':user_name');
            return array_values(array_filter($store['collections'], function ($row) use ($user) {
                return $row['User_Name'] === $user;
            }));
        }

        if ($lower === 'insert into user (name, password) values (:name, :password);' ||
            $lower === 'insert into user (name, password) values (:name, :password)') {
            $this->upsertUser($this->param($params, ':name'), $this->param($params, ':password'), 'user');
            return array();
        }

        if ($lower === 'insert into user_favorite (name) values (:name);' ||
            $lower === 'insert into user_favorite (name) values (:name)') {
            $this->ensureFavorite($this->param($params, ':name'));
            return array();
        }

        if ($lower === "update user set user_type = 'user' where name=:name") {
            $this->setUserType($this->param($params, ':name'), 'user');
            return array();
        }

        if ($lower === 'update user_favorite set fav_char=null where name=:name') {
            $this->setFavorite($this->param($params, ':name'), null);
            return array();
        }

        if ($lower === 'update user_favorite set fav_char=:characters where name=:name') {
            $this->setFavorite($this->param($params, ':name'), $this->param($params, ':characters'));
            return array();
        }

        if (strpos($lower, 'insert into user_collection') === 0) {
            $this->addCollection($this->param($params, ':user_name'), $this->param($params, ':item_name'), $this->param($params, ':item_type'));
            return array();
        }

        if (strpos($lower, 'delete from user_collection') === 0) {
            $this->deleteCollection($this->param($params, ':user_name'), $this->param($params, ':item_name'));
            return array();
        }

        return array();
    }

    private function canonicalTable($table)
    {
        foreach (array_keys($this->tables) as $name) {
            if (strtolower($name) === strtolower($table)) {
                return $name;
            }
        }
        return $table;
    }

    private function tableRows($table)
    {
        if (!isset($this->tables[$table])) {
            return array();
        }
        if (isset($this->cache[$table])) {
            return $this->cache[$table];
        }

        $path = $this->baseDir . DIRECTORY_SEPARATOR . 'csv_downloads' . DIRECTORY_SEPARATOR . $this->tables[$table];
        $handle = fopen($path, 'r');
        if (!$handle) {
            return array();
        }

        $headers = fgetcsv($handle, 0, ',', '"', '\\');
        $rows = array();
        while (($values = fgetcsv($handle, 0, ',', '"', '\\')) !== false) {
            $row = array();
            foreach ($headers as $index => $header) {
                $row[$header] = isset($values[$index]) ? $values[$index] : '';
            }
            if (!isset($row['Name']) || $row['Name'] === '') {
                continue;
            }
            $rows[] = $this->withAliases($row);
        }
        fclose($handle);

        $this->cache[$table] = $rows;
        return $rows;
    }

    private function withAliases($row)
    {
        foreach ($row as $key => $value) {
            $row[str_replace(' ', '_', $key)] = $value;
        }

        if (isset($row['Icon Image'])) {
            $row['Image'] = $row['Icon Image'];
            $row['Icon'] = $row['Icon Image'];
        }
        if (isset($row['Shadow_size'])) {
            $row['Shadow_Size'] = $row['Shadow_size'];
        }

        foreach (array('NH', 'SH') as $prefix) {
            foreach (array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec') as $month) {
                $spaceKey = $prefix . ' ' . $month;
                $underscoreKey = $prefix . '_' . $month;
                if (isset($row[$spaceKey])) {
                    $row[$underscoreKey] = $row[$spaceKey];
                }
                if (isset($row[$underscoreKey])) {
                    $row[$spaceKey] = $row[$underscoreKey];
                }
            }
        }

        return $row;
    }

    private function param($params, $name)
    {
        return isset($params[$name]) ? $params[$name] : null;
    }

    private function ensureStore()
    {
        $dir = dirname($this->storePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (!file_exists($this->storePath)) {
            file_put_contents($this->storePath, json_encode(array(
                'users' => array(),
                'favorites' => array(),
                'collections' => array(),
            ), JSON_PRETTY_PRINT));
        }
    }

    private function loadStore()
    {
        $store = json_decode(file_get_contents($this->storePath), true);
        if (!is_array($store)) {
            $store = array();
        }
        foreach (array('users', 'favorites', 'collections') as $key) {
            if (!isset($store[$key]) || !is_array($store[$key])) {
                $store[$key] = array();
            }
        }
        return $store;
    }

    private function saveStore($store)
    {
        file_put_contents($this->storePath, json_encode($store, JSON_PRETTY_PRINT));
    }

    private function findUser($name)
    {
        $store = $this->loadStore();
        foreach ($store['users'] as $user) {
            if ($user['Name'] === $name) {
                return $user;
            }
        }
        return null;
    }

    private function upsertUser($name, $password, $type)
    {
        $store = $this->loadStore();
        foreach ($store['users'] as &$user) {
            if ($user['Name'] === $name) {
                $user['Password'] = $password;
                $user['User_Type'] = $type;
                $this->saveStore($store);
                $this->ensureFavorite($name);
                return;
            }
        }
        $store['users'][] = array('Name' => $name, 'Password' => $password, 'User_Type' => $type);
        $this->saveStore($store);
        $this->ensureFavorite($name);
    }

    private function setUserType($name, $type)
    {
        $store = $this->loadStore();
        foreach ($store['users'] as &$user) {
            if ($user['Name'] === $name) {
                $user['User_Type'] = $type;
                $this->saveStore($store);
                return;
            }
        }
    }

    private function ensureFavorite($name)
    {
        $store = $this->loadStore();
        foreach ($store['favorites'] as $row) {
            if ($row['Name'] === $name) {
                return;
            }
        }
        $store['favorites'][] = array('Name' => $name, 'Fav_Char' => null);
        $this->saveStore($store);
    }

    private function favoriteRow($name)
    {
        $this->ensureFavorite($name);
        $store = $this->loadStore();
        foreach ($store['favorites'] as $row) {
            if ($row['Name'] === $name) {
                return $row;
            }
        }
        return array('Name' => $name, 'Fav_Char' => null);
    }

    private function setFavorite($name, $character)
    {
        $store = $this->loadStore();
        $found = false;
        foreach ($store['favorites'] as &$row) {
            if ($row['Name'] === $name) {
                $row['Fav_Char'] = $character;
                $found = true;
            }
        }
        if (!$found) {
            $store['favorites'][] = array('Name' => $name, 'Fav_Char' => $character);
        }
        $this->saveStore($store);
    }

    private function collectionNames($user)
    {
        $store = $this->loadStore();
        $names = array();
        foreach ($store['collections'] as $row) {
            if ($row['User_Name'] === $user) {
                $names[] = $row['Item_Name'];
            }
        }
        return $names;
    }

    private function addCollection($user, $item, $type)
    {
        $store = $this->loadStore();
        foreach ($store['collections'] as $row) {
            if ($row['User_Name'] === $user && $row['Item_Name'] === $item) {
                return;
            }
        }
        $store['collections'][] = array(
            'User_Name' => $user,
            'Item_Name' => $item,
            'Item_Type' => trim($type),
        );
        $this->saveStore($store);
    }

    private function deleteCollection($user, $item)
    {
        $store = $this->loadStore();
        $store['collections'] = array_values(array_filter($store['collections'], function ($row) use ($user, $item) {
            return !($row['User_Name'] === $user && $row['Item_Name'] === $item);
        }));
        $this->saveStore($store);
    }

    private function specialNpcs()
    {
        return array(
            array('Name' => 'Blathers', 'Picture' => 'https://dodo.ac/np/images/9/9e/Blathers_NH.png'),
            array('Name' => 'Celeste', 'Picture' => 'https://dodo.ac/np/images/c/cd/Celeste_NH.png'),
            array('Name' => 'Isabelle', 'Picture' => 'https://dodo.ac/np/images/9/95/Isabelle_NH.png'),
            array('Name' => 'K.K. Slider', 'Picture' => 'https://dodo.ac/np/images/3/3d/K.K._Slider_NH.png'),
            array('Name' => 'Tom Nook', 'Picture' => 'https://dodo.ac/np/images/7/70/Tom_Nook_NH.png'),
        );
    }
}

?>
