
<?php
    //Gets the TA info information from the database and displays it
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    //Define feilds from post
    $taName = $_POST['username'];
    $course = $_POST['course'];

    $query = $db->prepare('SELECT * FROM TAmanagement WHERE "taName" = :taName AND "course" = :course');
    $query->bindValue(':course', $course);
    $query->bindValue(':taName', $taName);
    
    $result = $query->execute();
    
    
    $db_day = "day- unspecified, ";
    $db_times = " times- unspecified";
    $db_location = "Unspecified ";
    $db_resp = "Unspecified";
    while ($ta = $result->fetchArray(SQLITE3_ASSOC))
    {
        if (strlen($ta['ohDay']) > 0) 
        { 
            $db_day = $ta['ohDay'] . "s"; 
        }
        
        if ((strlen($ta['ohStart']) > 0) and (strlen($ta['ohEnd']) > 0)) 
        { 
            $db_times = " from ". $ta['ohStart'] . " to " . $ta['ohEnd'];
        }
        
        if (strlen($ta['ohLocation']) > 0) 
        { 
            $db_location = $ta['ohLocation']; 
        }
        if (strlen($ta['responsibilities']) > 0) 
        { 
            $db_resp = $ta['responsibilities']; 
        }
        
    
        echo "<p> " . $taName . "'s office hours: " . $db_day . $db_times . "</p>";
        echo "<p> Location: " . $db_location. "</p>";
        echo "<p> " . $taName . "'s TA responsibilities: " . $db_resp . "</p>";
        break;
    }
   
    
    
           
        
?>