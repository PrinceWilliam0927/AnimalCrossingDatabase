<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Ben Phillips">
  <meta name="description" content="Navigational Header for DB project">  
  
  <title>Profile</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous"> 
  <link rel="stylesheet" type="text/css" href="./styles/profilestylesheet.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<?php session_start();?>
<?php require('connect-db.php'); ?>
<?php include('header.php'); ?>
<br/><br/><br/>

<div class="card" style="margin:20px">
  <h5 class="card-header">Hello <?php echo $_SESSION['user']?>, take a look at your Profile!</h5>
  <div class="card-body">
  <?php 
  global $db;
  $query = "SELECT * FROM User_Favorite WHERE Name=:name";
  $statement = $db->prepare($query); //Compile string query into executable version
  $name = $_SESSION['user'];
  $statement->bindParam(':name', $name);
  $statement->execute();
  $output = $statement->fetchAll();
  foreach($output as $r){
    if(is_null($r['Fav_Char'])){
      $update_character = false;
      ?>
      <form method="post">
      <p>Select your favorite Animal Crossing character:</p>
      <select name="characters">
        <?php 
          $query = "SELECT Name FROM Special_Npcs";
          $statement = $db->prepare($query); //Compile string query into executable version
          $statement->execute();
          $output2 = $statement->fetchAll();
          foreach($output2 as $r2){
            ?>
            <option value="<?php echo $r2['Name'] ?>"><?php echo $r2['Name'] ?></option>
            <?php
          }
          ?>
        <input type="submit" name="char-submit" class="btn btn-outline-secondary"></button>
        </form> 
        <?php
        if(isset($_POST['char-submit']))
        {
          echo "<meta http-equiv='refresh' content='0'>"; 
          //after a user selects his/her favorite character, refresh the page to see the image of that character
        }
        ?> 

     <?php
    }
    
      $query = "SELECT Fav_Char FROM User_Favorite WHERE Name=:name";
      $statement = $db->prepare($query); //Compile string query into executable version
      $name = $_SESSION['user'];
      $statement->bindParam(':name', $name);
      $statement->execute();
      $output3 = $statement->fetchAll();
      foreach($output3 as $r3){
        $query = "SELECT Picture FROM Special_Npcs WHERE Name=:favchar";
        $statement = $db->prepare($query); //Compile string query into executable version
        $Fav_Char = $r3['Fav_Char'];
        $statement->bindParam(':favchar', $Fav_Char);
        $statement->execute();
        $output4 = $statement->fetchAll();
        foreach($output4 as $r4){
          echo '<img style="border-radius: 50%;" src="' . $r4['Picture'] . '">';
          echo '<p>Your favorite Animal Crossing Character is: ' . $Fav_Char . '<br/>';
          echo '<form method="post" action="profile.php">';
          echo '<input type="submit" name="update-char" class="btn btn-outline-secondary" value="Change my favorite character">';
          echo '</form>';
          //When users want to change their favorite character
          if(isset($_POST['update-char']))
            {
              //user privileges checking
              $query = "SELECT User_Type FROM User WHERE Name=:name";
              $statement = $db->prepare($query); //Compile string query into executable version
              $name = $_SESSION['user'];
              $statement->bindParam(':name', $name);
              $statement->execute();
              $output5 = $statement->fetchAll();
              foreach ($output5 as $row){
                  if ($row['User_Type'] == 'user'){ //only general users are allow to update their favorite character
                  $query = "UPDATE User_Favorite SET Fav_Char=NULL WHERE Name=:name";
                  $statement = $db->prepare($query); //Compile string query into executable version
                  $name = $_SESSION['user'];
                  $statement->bindParam(':name', $name);
                  $statement->execute();
                  $output6 = $statement->fetchAll();
                  echo "<meta http-equiv='refresh' content='0'>"; 
                }else{
                  echo '<div class="alert alert-warning alert-dismissable fade show">
                  <strong>Sorry, </strong>you can not update your profile until you become a user.</a>
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  </div>';
                }
              }
              
            }
        }
      }
  }
?>

<?php //Function for when user picks their favorite character   
  if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['char-submit'])){
    $query = "UPDATE User_Favorite SET Fav_Char=:characters WHERE Name=:name";
    $statement = $db->prepare($query); //Compile string query into executable version
    $name = $_SESSION['user'];
    $statement->bindParam(':name', $name);
    $favChar = $_POST['characters'];
    $statement->bindParam(':characters', $favChar);
    $statement->execute();
    $statement->closeCursor();
  }
?>


  </div>
</div>
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