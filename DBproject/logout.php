<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="Database Project">
  <meta name="description" content="DB project">  
  
  <title>Logout</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

</head>


<body>
  
<?php session_start(); ?>

<?php
//Checks if there are any objects in the session
if (count($_SESSION) > 0)
{
  foreach ($_SESSION as $k => $v)
  {
    unset($_SESSION[$k]);    // remove key-value pair from session object (only server-side)
  }
  session_destroy();    // completely remove the instance (server)

  setcookie("PHPSESSID", "", time()-3600, "/");  //Delete PHPSESSID cookie
}

header("Location: login.php"); //Redirect back to login page
?>

</body>
</html>