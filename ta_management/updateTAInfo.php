<?php
    error_reporting(E_ALL); 
    ini_set('display_errors', 'On');
    //connect to database
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    
    //Define feilds from post
    $username = $_POST['username'];
    $course = $_POST['course'];
    $ohDay = $_POST['ohDay'];
    $ohStart = $_POST['ohStart'];
    $ohEnd = $_POST['ohEnd'];
    $ohLocation = $_POST['ohLocation'];
    $responsibilities = $_POST['responsibilities'];

    // store message in 'messages' table
    $statement = "UPDATE TAmanagement SET ohDay = :ohDay, 
                                    ohStart = :ohStart, 
                                    ohEnd = :ohEnd, 
                                    ohLocation = :ohLocation, 
                                    responsibilities = :responsibilities 
                                WHERE taName = :username 
                                AND course = :course";
    $query = $db->prepare($statement);
    
    $query->bindValue(':username', $username);
    $query->bindValue(':course', $course);
    $query->bindValue(':ohDay', $ohDay);
    $query->bindValue(':ohStart', $ohStart);
    $query->bindValue(':ohEnd', $ohEnd);
    $query->bindValue(':ohLocation', $ohLocation);
    $query->bindValue(':responsibilities', $responsibilities);


    $query->execute();
    $db->close();

           
        
?>