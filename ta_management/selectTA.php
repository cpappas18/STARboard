<style>
    .taOption{
        padding: 10px;
        text-align: center;
        color: #2b2d42;
    }
</style>
<?php 
    //define the PDO object and tell it about the db
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $course = $_POST['course'];
    //write sql
    $query = $db->prepare('SELECT * FROM TAmanagement WHERE "course" = :course');
    $query->bindValue(':course', $course);
    $result = $query->execute();
    echo $course;
    echo "<option value='none'>-Select a TA-</option>";
    while ($ta = $result->fetchArray(SQLITE3_ASSOC))
    {
        echo "<option class='taOption' value='". $ta['taName'] ."'>". $ta['taName']."</option>";
    }
    $db->close();
    

?>