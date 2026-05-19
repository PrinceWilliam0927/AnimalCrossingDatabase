<?php
        require('connect-db.php');

        global $db;
        $query = "select * from Fossils where Name like :search";
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
                    <th>Image</th>
                    <th>Price</th>
                    <th>Select</th>
                    </tr> 
                </thead>";
        foreach($output as $r) {
            echo '<tr><td>' . $r['Name'] . '</td><td><img src="' . $r['Image'] . '" height="50" width="50"></td><td>' . $r['Price'] . ' Bells</td><td><input type="checkbox" name="collected[]" value="' . $r['Name'] . '"></td></tr>';
        }
        echo "</table>";

        echo "<input style='display: none;' name='item_type' value=“Fossil” />
              <input style='display: none;' name='return_addr' value='Archives_Fossils.php' />";
        echo "</form>";
        $statement->closeCursor();


?>