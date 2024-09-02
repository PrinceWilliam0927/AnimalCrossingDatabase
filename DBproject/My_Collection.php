<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Ben Phillips">
  <meta name="description" content="Screen Displaying a user's collection">  
  
  <title>My Collection</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"> 
  <link rel="stylesheet" type="text/css" href="./styles/Base.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
<?php session_start(); ?>
<?php require('connect-db.php'); ?>
<?php include('header.php'); ?>
<br/><br/><br/>
<h2>Your Collection:</h2>

<form method='post' action='My_Collection.php'>
<input type='submit' value='Delete from My Collection' style="" name='Delete'> 

<br/>

<h3>Artworks:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Genuine</th>
        <th>Sell Price</th>
        <th>Real Art Title</th>
        <th>Artist</th>
        <th>Select</th>
        </tr> 
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Artwork WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><?php echo $row['Type']; ?></td>
        <td><?php echo $row['Genuine']; ?></td>
        <td><?php echo $row['Sell_Price']; ?> Bells</td>
        <td><?php echo $row['Real_Art_Title']; ?></td>
        <td><?php echo $row['Artist']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php 
}
?>
    </tbody>
</table>

<br/>

<h3>Fossils:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
        <th>Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Select</th>
        </tr> 
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Fossils WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Price']; ?> Bells</td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
        }
      ?>
    </tbody>
</table>

<br/>

<h3>Fish from the Northern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
        <th>Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Location</th>
        <th>Shadow Size</th>
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Fish_N WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Price']; ?> Bells</td>
        <td><?php echo $row['Location']; ?></td>
        <td><?php echo $row['Shadow_Size']; ?></td>
        <td><?php echo $row['NH_Jan']; ?></td>
        <td><?php echo $row['NH_Feb']; ?></td>
        <td><?php echo $row['NH_Mar']; ?></td>
        <td><?php echo $row['NH_Apr']; ?></td>
        <td><?php echo $row['NH_May']; ?></td>
        <td><?php echo $row['NH_Jun']; ?></td>
        <td><?php echo $row['NH_Jul']; ?></td>
        <td><?php echo $row['NH_Aug']; ?></td>
        <td><?php echo $row['NH Sep']; ?></td>
        <td><?php echo $row['NH Oct']; ?></td>
        <td><?php echo $row['NH Nov']; ?></td>
        <td><?php echo $row['NH Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
    }
    ?>

    </tbody>
</table>

<br/>

<h3>Fish from the Southern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
        <th>Name</th>
        <th>Image</th>
        <th>Price</th>
        <th>Location</th>
        <th>Shadow Size</th>
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Fish_S WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Icon']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Price']; ?> Bells</td>
        <td><?php echo $row['Location']; ?></td>
        <td><?php echo $row['Shadow_Size']; ?></td>
        <td><?php echo $row['SH_Jan']; ?></td>
        <td><?php echo $row['SH_Feb']; ?></td>
        <td><?php echo $row['SH_Mar']; ?></td>
        <td><?php echo $row['SH_Apr']; ?></td>
        <td><?php echo $row['SH_May']; ?></td>
        <td><?php echo $row['SH_Jun']; ?></td>
        <td><?php echo $row['SH_Jul']; ?></td>
        <td><?php echo $row['SH_Aug']; ?></td>
        <td><?php echo $row['SH_Sep']; ?></td>
        <td><?php echo $row['SH_Oct']; ?></td>
        <td><?php echo $row['SH_Nov']; ?></td>
        <td><?php echo $row['SH_Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<br/>

<h3>Insects from the Northern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Insects_N WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Icon Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Sell']; ?> Bells</td>
        <td><?php echo $row['Location']; ?></td>
        <td><?php echo $row['Weather']; ?></td>
        <td><?php echo $row['NH_Jan']; ?></td>
        <td><?php echo $row['NH_Feb']; ?></td>
        <td><?php echo $row['NH_Mar']; ?></td>
        <td><?php echo $row['NH_Apr']; ?></td>
        <td><?php echo $row['NH_May']; ?></td>
        <td><?php echo $row['NH_Jun']; ?></td>
        <td><?php echo $row['NH_Jul']; ?></td>
        <td><?php echo $row['NH_Aug']; ?></td>
        <td><?php echo $row['NH_Sep']; ?></td>
        <td><?php echo $row['NH_Oct']; ?></td>
        <td><?php echo $row['NH_Nov']; ?></td>
        <td><?php echo $row['NH_Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<br/>

<h3>Insects from the Southern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Insects_S WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Icon Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Sell']; ?> Bells</td>
        <td><?php echo $row['Location']; ?></td>
        <td><?php echo $row['Weather']; ?></td>
        <td><?php echo $row['SH_Jan']; ?></td>
        <td><?php echo $row['SH_Feb']; ?></td>
        <td><?php echo $row['SH_Mar']; ?></td>
        <td><?php echo $row['SH_Apr']; ?></td>
        <td><?php echo $row['SH_May']; ?></td>
        <td><?php echo $row['SH_Jun']; ?></td>
        <td><?php echo $row['SH_Jul']; ?></td>
        <td><?php echo $row['SH_Aug']; ?></td>
        <td><?php echo $row['SH_Sep']; ?></td>
        <td><?php echo $row['SH_Oct']; ?></td>
        <td><?php echo $row['SH_Nov']; ?></td>
        <td><?php echo $row['SH_Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
    }
    ?>
    </tbody>
</table>

<br/>

<h3>Music:</h3>
<table id="eat-table" class="table table-striped table-hover">
    <thead class="thead-dark">
        <tr>
        <th>Name</th>
        <th>Album Cover</th>
        <th>For Sale</th>
        <th>Select</th>
        </tr> 
    </thead>
    <tbody>
        
    <?php
        global $db;
        $query = "SELECT * FROM Music WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Album_Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['For Sale']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
    }
    ?>

    </tbody>
</table>

<br/>

<h3>Sea Creatures from the Northern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Sea_Creatures_N WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Icon Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Price']; ?> Bells</td>
        <td><?php echo $row['Shadow']; ?></td>
        <td><?php echo $row['Movement Speed']; ?></td>
        <td><?php echo $row['NH_Jan']; ?></td>
        <td><?php echo $row['NH_Feb']; ?></td>
        <td><?php echo $row['NH_Mar']; ?></td>
        <td><?php echo $row['NH_Apr']; ?></td>
        <td><?php echo $row['NH_May']; ?></td>
        <td><?php echo $row['NH_Jun']; ?></td>
        <td><?php echo $row['NH_Jul']; ?></td>
        <td><?php echo $row['NH_Aug']; ?></td>
        <td><?php echo $row['NH_Sep']; ?></td>
        <td><?php echo $row['NH_Oct']; ?></td>
        <td><?php echo $row['NH_Nov']; ?></td>
        <td><?php echo $row['NH_Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
        }
?>
    </tbody>
</table>

<br/>

<h3>Sea Creatures from the Southern Hemisphere:</h3>
<table class="table table-striped table-hover">
    <thead class="thead-dark">
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
    </thead>
    <tbody>
    <?php
        global $db;
        $query = "SELECT * FROM Sea_Creatures_S WHERE Name IN ( SELECT Item_Name FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        
        foreach ($output as $row){
        //Puts each entry into the table format for display
    ?><tr>
        <td><?php echo $row['Name']; ?></td>
        <td><img src="<?php echo $row['Icon Image']; ?>" height="50" width="50"></td>
        <td><?php echo $row['Price']; ?> Bells</td>
        <td><?php echo $row['Shadow']; ?></td>
        <td><?php echo $row['Movement Speed']; ?></td>
        <td><?php echo $row['SH_Jan']; ?></td>
        <td><?php echo $row['SH_Feb']; ?></td>
        <td><?php echo $row['SH_Mar']; ?></td>
        <td><?php echo $row['SH_Apr']; ?></td>
        <td><?php echo $row['SH_May']; ?></td>
        <td><?php echo $row['SH_Jun']; ?></td>
        <td><?php echo $row['SH_Jul']; ?></td>
        <td><?php echo $row['SH_Aug']; ?></td>
        <td><?php echo $row['SH_Sep']; ?></td>
        <td><?php echo $row['SH_Oct']; ?></td>
        <td><?php echo $row['SH_Nov']; ?></td>
        <td><?php echo $row['SH_Dec']; ?></td>
        <td><input type="checkbox" name="deleted[]" value="<?php echo $row['Name']; ?>"></td>
    </tr>
    <?php
        }
?>
    </tbody>
</table>

<?php // create an array all data in the User_Collection under a user's name
        global $db;
        $query = "SELECT * FROM User_Collection WHERE User_Name=:user_name) ";
        $statement = $db->prepare($query); //Compile string query into executable version
        $user_name = $_SESSION['user'];
        $statement->bindParam(':user_name', $user_name);
        $statement->execute();
        $output = $statement->fetchAll();  //Returns an array of all row from execution
        $row_array = array();
        foreach ($output as $row){
            //Puts each entry into the table format but not for display
            ?><tr style='display: none;'>
                <td><?php echo $row['User_Name']; ?></td>
                <td><?php echo $row['Item_Name']; ?></td>
                <td><?php echo $row['Item_Type']; ?></td>
              </tr>
              <?php
              //Create array for download feature
              $row_array[] = array($row['User_Name'], $row['Item_Name'], $row['Item_Type']);  
          }
            //Serialize array for download feature
            $serialize_row_array =  serialize($row_array);
            ?>
    <!-- Pass serialized array via textarea -->
    //<textarea name='download_data' style='display: none;'><?php echo $serialize_row_array; ?></textarea>
    <!-- Pass return address after deleting from collection -->
    <input style='display: none;' name='return_addr00' value='My_Collection.php' />
    <?php
    $statement->closeCursor();
?> 

<div class="fixed-bottom pt-1 pb-1 bg-secondary">
    <div class="container">
        <div class="row">
            <div class="col text-left" style="color:white;">
                Created for CS4750: Database Systems
            </div>

            <div class="col text-right" style="color:white;">
            Blathers’ Museum Curators, Copyright 2021
            </div>
        </div>
    </div>
</div>
</body>
</form>


<?php
// When the delete button is pressed 
if(isset($_POST['deleted']) && !empty($_POST['deleted'])) {
    // user privileges checking
    $query = "SELECT User_Type FROM User WHERE Name=:name";
    $statement = $db->prepare($query); //Compile string query into executable version
    $name = $_SESSION['user'];
    $statement->bindParam(':name', $name);
    $statement->execute();
    $output5 = $statement->fetchAll();
        foreach ($output5 as $row){
            if ($row['User_Type'] == 'user'){ //only general users are allow to modify their collection
                foreach($_POST['deleted'] as $delete){
                    $query = "DELETE FROM User_Collection WHERE User_Name =:user_name and Item_Name= :item_name";
                    $statement = $db->prepare($query); //Compile string query into executable version
                    // Insert user into query
                    $user_name = $_SESSION['user'];
                    $statement->bindParam(':user_name', $user_name);
                    // Insert selected item into query
                    $item_name = $delete;
                    $statement->bindParam(':item_name', $delete);
                    $statement->execute();
                    echo $delete . ' inserted <br/>';
                }
                $statement->closeCursor();
                header('Location: ' . $_POST['return_addr00']); // returns to My_Collection.php
            }else{
                echo '<div class="fixed-top alert alert-warning alert-dismissible fade show" >
                <strong>Sorry, </strong>you have to become a user of this site to edit your own collection.</a>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>';
            }
    }
}
?>
