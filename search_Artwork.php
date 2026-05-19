<?php
        require('connect-db.php');

        global $db;
        $query = "select * from Artwork where Real_Art_Title like :search";
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
                    <th>Real Art Title</th>
                    <th>Type</th>
                    <th>Genuine</th>
                    <th>Sell Price</th>
                    <th>Name</th>
                    <th>Artist</th>
                    <th>Select</th>
                    </tr> 
                </thead>";
        foreach($output as $r) {
            echo '<tr><td>' . $r['Real_Art_Title'] . '</td><td>' . $r['Type'] . '</td><td>' . $r['Genuine'] . '</td><td>' . $r['Sell_Price'] . ' Bells</td><td>' . $r['Name'] . '</td><td>' . $r['Artist'] . ' </td><td><input type="checkbox" name="collected[]" value="' . $r['Name'] . '"></td></tr>';
        }
        echo "</table>";

        echo "<input style='display: none;' name='item_type' value=“Artwork” />
              <input style='display: none;' name='return_addr' value='Archives_Artwork.php' />";
        echo "</form>";
        $statement->closeCursor();


?>