
<?php
    //Gets the TA iwih list from the database and displays it

    //Define feilds from post
    $prof_name = $_POST['prof_name'];
    $course_num = $_POST['course_num'];

    //run sql
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $statement = $db->prepare("SELECT * FROM WishList");
    $result = $statement -> execute();
    echo "<ul>";
    while ($ta = $result->fetchArray(SQLITE3_ASSOC))
    {
        if ($ta['prof_name'] == $prof_name && $ta['course_num'] == $course_num)
        {
            echo "<li>" . $ta['TA_name'] . "</li>";
        }
    }
    echo "</ul>";
    
?>