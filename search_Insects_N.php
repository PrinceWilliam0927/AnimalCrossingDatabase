<?php
        require('connect-db.php');

        global $db;
        $query = "select * from Insects_N where Name like :search";
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
                    <th>Location</th>
                    <th>Weather</th>
                    <th>Jan</th>
                    <th>Feb</th>
                    <th>March</th>
                    <th>Apr</th>
                    <th>May</th>
                    <th>Jun</th>
                    <th>Jul</th>
                    <th>Aug</th>
                    <th>Sep</th>
                    <th>Oct</th>
                    <th>Nov</th>
                    <th>Dec</th>
                    <th>Select</th>
                    </tr> 
                </thead>";
        foreach($output as $r) {
            echo '<tr><td>' . $r['Name'] . '</td><td><img src="' . $r['Icon Image'] . '" height="50" width="50"></td><td>' . $r['Sell'] . ' Bells</td><td>' . $r['Location'] . '</td><td>' . $r['Weather'] . '</td><td>' . $r['NH_Jan'] . '</td><td>' . $r['NH_Feb'] . '</td><td>' . $r['NH_Mar'] . '</td><td>' . $r['NH_Apr'] . '</td><td>' . $r['NH_May'] . '</td><td>' . $r['NH_Jun'] . '</td><td>' . $r['NH_Jul'] . '</td><td>' . $r['NH_Aug'] . '</td><td>' . $r['NH_Sep'] . '</td><td>' . $r['NH_Oct'] . '</td><td>' . $r['NH_Nov'] . '</td><td>' . $r['NH_Dec'] . ' </td><td><input type="checkbox" name="collected[]" value="' . $r['Name'] . '"></td></tr>';
        }
        echo "</table>";

        echo "<input style='display: none;' name='item_type' value=“Insects_N” />
              <input style='display: none;' name='return_addr' value='Archives_Insects_N.php' />";
        echo "</form>";
        $statement->closeCursor();


?>