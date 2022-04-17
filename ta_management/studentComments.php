
<style>
    .commentInfo
    {
        margin-top: 10px;  
        font-size: 11px;
        color: #585d66;
    }
    .commentText{
    }

    .comment{
        padding: 10px;
    }

    

</style>   
<?php
    //Gets the comments from students about the TA with $TA_name and displays it

    //Define feilds from post unless they already locally defined
    if (is_null($TA_name) && is_null($course))
    {
        $TA_name = $_POST['TA_name'];
        $course = $_POST['course'];
    }
    
   
    //run sql
    $db_studentComments = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement_studentComments = $db_studentComments->prepare("SELECT * FROM ratings");
    $result_studentComments = $statement_studentComments->execute();
    echo "<div class = 'comment'>";
    while ($ta_studentComments = $result_studentComments->fetchArray(SQLITE3_ASSOC))
    {
        if ($ta_studentComments['TA_name'] == $TA_name && $ta_studentComments['course'] == $course)
        {
            echo "<div class = 'commentInfo'>". $ta_studentComments['term'] ." ". $ta_studentComments['date'] . "</div>";
            echo "<div class = 'comment'>". $ta_studentComments['comment'] . "</div>";
        }
    }
    echo "</div>";
    $db_studentComments->close();
    
           
        
?>