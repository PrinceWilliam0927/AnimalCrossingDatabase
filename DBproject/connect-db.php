<?php
$hostname = '128.143.69.130';
//$hostname = 'localhost:3306';
//$hostname = '128.143.69.130';

$dbname = 'bnp3nj';
//$dbname = 'BenP';

if (isset($_SESSION['user'])){ //  if the user has been declared before
   $username = 'bnp3nj_b'; // general user
}else{
   $username = 'bnp3nj_c'; // new users are login users with less privileges
}

//$username = 'bnp3nj';
//$password = '@Sarah524';
$password = 'Spr1ng2021!!';

$dsn = "mysql:host=128.143.69.130;dbname=$dbname";

try 
{

   $db = new PDO($dsn, $username, $password);
}
catch (PDOException $e) 
{

   $error_message = $e->getMessage();        
   echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
catch (Exception $e) 
{
   $error_message = $e->getMessage();
   echo "<p>Error message: $error_message </p>";
}

//Add user roles functionality:

?>