<?php
$db_assigned = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
$stmt_assign = $db_assigned->prepare("SELECT course_num FROM TAassignment WHERE term_month_year = '".$TA_term."' AND student_id= ".$TA_id."");
$assigned = $stmt_assign->execute();


    echo "<h3 class='red-detail'>Courses Assigned</h3>";
    echo "<ul>";
$rows =0;
while ($course = $assigned->fetchArray(SQLITE3_ASSOC)){
    
    echo "<li>".$course['course_num']."</li>";
    $rows++;
}
echo "</ul>";
if ($rows == 0){
    echo "<p class='field-head'>Has not been assigned </p>";
}

