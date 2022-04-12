
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
    //Gets the TA performance information from the database and displays it

    //Define feilds from post
    $TA_name = $_POST['TA_name'];
    $course = $_POST['course'];
    $prof_name = $_POST['prof_name'];
   
    //run sql
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare("SELECT * FROM TAPerformance");
    $result = $statement->execute();
    echo "<div class = 'comment'>";
    while ($ta = $result->fetchArray(SQLITE3_ASSOC))
    {
        if ($ta['TA_name'] == $TA_name && $ta['course_num'] == $course && $ta['prof_name'] == $prof_name)
        {
            echo "<div class = 'commentInfo'>". $ta['term_month_year'] . "</div>";
            echo "<div class = 'comment'>". $ta['comment'] . "</div>";
        }
    }
    echo "</div>";
           
        
?>