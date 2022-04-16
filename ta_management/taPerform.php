
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
    if (is_null($TA_name) && is_null($prof_name) && is_null($course))
    {
        $TA_name = $_POST['TA_name'];
        $course = $_POST['course'];
        $prof_name = $_POST['prof_name'];
    }
    
   
    //run sql
    $db_perform = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement_perform = $db_perform->prepare("SELECT * FROM TAPerformance");
    $result_perform = $statement_perform->execute();
    echo "<div class = 'comment'>";
    while ($ta_perform = $result_perform->fetchArray(SQLITE3_ASSOC))
    {
        if ($ta_perform['TA_name'] == $TA_name && $ta_perform['course_num'] == $course && $ta_perform['prof_name'] == $prof_name)
        {
            echo "<div class = 'commentInfo'>". $ta_perform['term_month_year'] . "</div>";
            echo "<div class = 'comment'>". $ta_perform['comment'] . "</div>";
        }
    }
    echo "</div>";
    $db_perform->close();
    
?>