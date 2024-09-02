<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Ben Phillips">
  <meta name="description" content="Screen Displaying Artwork table from Database">  
  
  <title>Insects N</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"> 
  <link rel="stylesheet" type="text/css" href="./styles/Base.css" />
</head>

<body>
<?php session_start(); ?>
<?php require('connect-db.php'); ?>
<?php include('header.php'); ?>
<?php include('Archive_Header.php') ?>

<br/><br/><br/><br/><br/>

<script src="js/jquery-1.6.2.min.js" type="text/javascript"></script> 
<script src="js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<script>
	$(document).ready(function() {
		$( "#Nameinput" ).change(function() {
		
			$.ajax({
				url: 'search_Insects_N.php', 
				data: {searchName: $( "#Nameinput" ).val()},
				success: function(data){
					$('#Nameresult').html(data);	
				
				}
			});
		});
		exit();
	});
	</script>

<h3>Search Insect by Name:</h3>	
<input class="xlarge" style="margin-left:50px;" id="Nameinput" type="search" placeholder="Enter Insect Name"/>

<div id="Nameresult">
<form method='post' action='download.php'>
<input type='submit' value='Add to Collection' name='Collect'>
<input type='submit' value='Download Table' name='Download'>

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
        <?php query_database();?>
    </tbody>
</table>
</div>

<?php 
  //Function that Queries the database and returns 5 most recent meals
  function query_database(){

    global $db;
    $query = "SELECT * FROM Insects_N";
    $statement = $db->prepare($query); //Compile string query into executable version
    $statement->execute();
    $output = $statement->fetchAll();  //Returns an array of all row from execution

    //All download code heavily influenced by: https://makitweb.com/how-to-export-mysql-table-data-as-csv-file-in-php/
    $row_array = array();

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
        <td><input type="checkbox" name="collected[]" value="<?php echo $row['Name']; ?>"></td>
      </tr>
      <?php
      //Create array for download feature
      $row_array[] = array($row['Name'], $row['Icon Image'], $row['Sell'], $row['Location'], $row['Weather'], $row['NH_Jan'], $row['NH_Feb'], $row['NH_Mar'], $row['NH_Apr'], $row['NH_May'], $row['NH_Jun'], $row['NH_Jul'], $row['NH_Aug'], $row['NH_Sep'], $row['NH_Oct'], $row['NH_Nov'], $row['NH_Dec']);
    }
    //Serialize array for download feature
    $serialize_row_array =  serialize($row_array);
    ?>
    <!-- Pass serialized array via textarea -->
    <textarea name='download_data' style='display: none;'><?php echo $serialize_row_array; ?></textarea>
    <!-- Pass desired name of file -->
    <input style='display: none;' name='file_name' value='insects_N.csv' />
    <!-- Pass type of item for adding to User_Collection database -->
    <input style='display: none;' name='item_type' value='Insects_N' />
    <!-- Pass return address after adding to collection -->
    <input style='display: none;' name='return_addr' value='Archives_Insects_N.php' />
    <?php
    $statement->closeCursor();
  }
?>

</form>
</body>