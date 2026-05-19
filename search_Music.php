<?php
        require('connect-db.php');

        global $db;
        $query = "select * from Music where Name like :search";
        $statement = $db->prepare($query); //Compile string query into executable version
        $searchString = $_GET['searchName'] . '%';
        $statement->bindParam(':search', $searchString);
        $statement->execute();
        $output = $statement->fetchAll(); 
        echo"<form method='post' action='download.php'> 
            <input type='submit' value='Add to Collection' name='Collect'>
             <input type='submit' value='Download Table' name='Download'>
                <table class='table table-striped table-hover'>
                <thead class= 'thead-dark' >
                    <tr>
                    <th>Name</th>
                    <th>Album Cover</th>
                    <th>For Sale</th>
                    <th>Select</th>
                    </tr> 
                </thead>";
        foreach($output as $r) {
            echo '<tr><td>' . $r['Name'] . '</td><td><img src="' . $r['Album_Image'] . '" height="50" width="50"></td><td>' . $r['For Sale'] . ' </td><td><input type="checkbox" name="collected[]" value="' . $r['Name'] . '"></td></tr>';
        }
        echo "</table>";

        echo "<input style='display: none;' name='item_type' value=“Music” />
              <input style='display: none;' name='return_addr' value='Archives_Music.php' />";
        echo "</form>";
        $statement->closeCursor();


?>