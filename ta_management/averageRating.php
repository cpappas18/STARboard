<?php
    //Gets the average rating for a TA with TA_name and displays it

    //Define feilds from post
    if (is_null($TA_name) && is_null($course))
    {
        $TA_name = $_POST['TA_name'];
        $course = $_POST['course'];
    }
    
   
    //run sql
    $db_ratings = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement_ratings = $db_ratings->prepare("SELECT * FROM ratings");
    $result_ratings = $statement_ratings->execute();
    $num_ratings = 0;
    $sum = 0;
    while ($rating = $result_ratings->fetchArray(SQLITE3_ASSOC))
    {
        if ($rating['TA_name'] == $TA_name && $rating['course'] == $course)
        {
            $num_ratings ++;
            $sum = $sum + $rating['rating'];
        }
    }
    if ($num_ratings == 0)
    {
        echo "No ratings yet";
    }
    else
    {
        $average_score = $sum / $num_ratings;
        $average_score_rounded = number_format((float)$average_score, 1, '.', '');
        echo $average_score_rounded . " / 5.0";
    }
    $db_ratings->close();
    
           
        
?>