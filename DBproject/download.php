<?php
session_start();
require('connect-db.php');

if( isset($_POST['Download']) ){ //If Download button is pressed
    //Heavily influenced by: https://makitweb.com/how-to-export-mysql-table-data-as-csv-file-in-php/

    $filename = $_POST['file_name'];
    //$download_data = unserialize($_POST['download_data']);

    // file creation
    //$file = fopen($filename,"w");

    //foreach ($download_data as $line){
        //fputcsv($file,$line);
    //}

    //fclose($file);

    // download
    header('Content-Description: File Transfer');
    header('Content-Disposition: attachment; filename=' . $_POST['file_name']);
    header('Content-Type: application/csv; '); 

    readfile('./csv_downloads/' . $_POST['file_name']);

    // deleting file
    unlink($filename);
    exit();
}

else {  //If Collect button is pressed
    if(isset($_POST['collected']) && !empty($_POST['collected'])) {
        foreach($_POST['collected'] as $collect){
            $query = "INSERT INTO User_Collection (User_Name, Item_Name, Item_Type) VALUES (:user_name, :item_name, :item_type)";
            $statement = $db->prepare($query); //Compile string query into executable version
            // Insert user into query
            $user_name = $_SESSION['user'];
            $statement->bindParam(':user_name', $user_name);
            // Insert selected item into query
            $item_name = $collect;
            $statement->bindParam(':item_name', $collect);
            // Insert type of item into query
            $item_type = $_POST['item_type'];
            $statement->bindParam(':item_type', $item_type);
            $statement->execute();
            echo $collect . ' inserted <br/>';
        }
        $statement->closeCursor();
        header('Location: ' . $_POST['return_addr']);
    }
}

