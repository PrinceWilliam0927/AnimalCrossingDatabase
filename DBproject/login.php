<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Databases Project">
  <meta name="description" content="DB project">  
  
  <title>Login</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="./styles/loginstylesheet.css" />
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<?php session_start();?>
<?php require('connect-db.php'); ?>

<body>
  <div class="login-chunk">
    <h1>Welcome Back to the Blathers' Archives!</h1><br/>
    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
      Name: <input type="text" name="username" class="form-control" autofocus required /> <br/>
      Password: <input type="password" name="pwd" class="form-control" required /> <br/>
      <input type="submit" value="Sign in" class="btn btn-light" />   
    </form><br/><hr><br/>
    <p style="text-align:center; color:grey">Don't have an Account? <br/>  Please Sign In with your desired name and password</p>
  </div>



<?php   

  //Function to validate user and log them in
  $password_warning = NULL;
  function login_and_authenticate(){
    if($_SERVER['REQUEST_METHOD'] == 'POST' && strlen($_POST['username']) > 0) {
      //Check valid username 
      $regex = preg_match('/[^a-zA-Z0-9\d]/', $_POST['username']);
      if ($regex == 0){
      $pwd_clean = htmlspecialchars($_POST['pwd']);
      global $db;
      $query = "SELECT Name, Password, User_Type FROM User WHERE Name=:name";
      $statement = $db->prepare($query); //Compile string query into executable version
      $name = htmlspecialchars($_POST['username']);
      $statement->bindParam(':name', $name);
      $statement->execute();
      $output = $statement->fetchAll();  //Returns an array of all row from execution

      if(count($output) > 0){
        foreach($output as $r){
          if($r['Password'] == md5($pwd_clean)) {
            $_SESSION['user'] = $_POST['username'];
            //updates the User_Type in User table once a user is authenticated
            $query = "UPDATE User SET User_Type = 'user' WHERE Name=:name";
            $statement = $db->prepare($query); 
            $name = htmlspecialchars($_POST['username']);
            $statement->bindParam(':name', $name);
            $statement->execute();
            echo "Correct password!";
            header("Location: profile.php");
          }
          else {
            echo '<div class="alert alert-warning alert-dismissable fade show">
            <strong>Warning!</strong> We could not find a match with your username and passwords, please try Again!</a>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>';
          }
        }
      }
      else{
        //Insert into User Table:
        $query = "INSERT INTO User (Name, Password) VALUES (:name, :password);";
        $statement = $db->prepare($query); //Compile string query into executable version
        $pwd_eng = htmlspecialchars($_POST['pwd']);
        $pwd_hash = md5($pwd_eng);
        $statement->bindParam(':password', $pwd_hash);
        $name = htmlspecialchars($_POST['username']);
        $statement->bindParam(':name', $name);
        $statement->execute();
        
        //Create row in User_Favorite table:
        $query2 = "INSERT INTO User_Favorite (Name) VALUES (:name);";
        //$query2 = "SELECT * FROM User_Favorite;";
        $statement2 = $db->prepare($query2); //Compile string query into executable version
        $name = htmlspecialchars($_POST['username']);
        $statement2->bindParam(':name', $name);
        //print_r($statement2);
        $statement2->execute();
        $output4 = $statement2->fetchAll();
        //print_r($output4);

        //Log user in using session object
        $_SESSION['user'] = $_POST['username'];
        header('Location: profile.php');
      }
    }
    else{
      echo '<div class="alert alert-warning alert-dismissable fade show">
      <strong>Warning!</strong> Username can not contain special character(s)!</a>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>';
    }
    }    
  }  
  
  
login_and_authenticate();
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