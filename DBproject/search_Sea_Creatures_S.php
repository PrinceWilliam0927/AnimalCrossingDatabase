<?php
        require('connect-db.php');

        global $db;
        $query = "select * from Sea_Creatures_S where Name like :search";
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
                    <th>Shadow</th>
                    <th>Speed</th>
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
            echo '<tr><td>' . $r['Name'] . '</td><td><img src="' . $r['Icon Image'] . '" height="50" width="50"></td><td>' . $r['Price'] . ' Bells</td><td>' . $r['Shadow'] . '</td><td>' . $r['Movement Speed'] . '</td><td>' . $r['SH_Jan'] . '</td><td>' . $r['SH_Feb'] . '</td><td>' . $r['SH_Mar'] . '</td><td>' . $r['SH_Apr'] . '</td><td>' . $r['SH_May'] . '</td><td>' . $r['SH_Jun'] . '</td><td>' . $r['SH_Jul'] . '</td><td>' . $r['SH_Aug'] . '</td><td>' . $r['SH_Sep'] . '</td><td>' . $r['SH_Oct'] . '</td><td>' . $r['SH_Nov'] . '</td><td>' . $r['SH_Dec'] . ' </td><td><input type="checkbox" name="collected[]" value="' . $r['Name'] . '"></td></tr>';
        }
        echo "</table>";

        echo "<input style='display: none;' name='item_type' value=“Sea_Creatures_S” />
              <input style='display: none;' name='return_addr' value='Archives_Sea_Creatures_S.php' />";
        echo "</form>";
        $statement->closeCursor();


?>