
<style>
    .stat{
        padding-left: 15px;
        padding-right: 15px;
        text-align: center;
        color: #2b2d42;
        
    }
</style>
<?php
    //Gets the total number of ratings in the database
    //run sql
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare("SELECT * FROM ratings");
    $result = $statement -> execute();
    $num = 0;
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
        $num = $num + 1;
    }
    
    echo "<div class='stat'><b>" . $num . " users have already submitted TA ratings. </b></div>";
    
    
?>