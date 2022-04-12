<?php
    error_reporting(E_ALL); 
    ini_set('display_errors', 'On');
       
    //connect to database
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    
    //Define feilds from post
    $username = $_POST['username'];
    $course = $_POST['course'];
    $dateAndTime = $_POST['dateAndTime'];
    $message = $_POST['message'];

    // store message in 'messages' table
    $statement = 'INSERT INTO "messages" ("username", "course", "dateAndTime", "message")
    VALUES (:username, :course, :dateAndTime, :message)';
    $query = $db->prepare($statement);
    

    $query->bindValue(':username', $username);
    $query->bindValue(':course', $course);
    $query->bindValue(':dateAndTime', $dateAndTime);
    $query->bindValue(':message', $message);

    $query->execute();
    $db->close();

           
        
?>