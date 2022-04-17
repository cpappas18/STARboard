<?php

$db_past = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$stmt_history = $db_past->prepare("SELECT * FROM TAassignment WHERE NOT term_month_year = '".$TA_term."' AND student_id= ".$TA_id."");
$past_courses = $stmt_history->execute();

//echo all courses the ta has been assigned in the past

echo "<h3 class='red-detail'>TA History</h3>";
echo "<ul>";
    

while ($past_course = $past_courses->fetchArray(SQLITE3_ASSOC)){
    
    
    echo "<li>".     $past_course['course_num']    ." in ".$past_course['term_month_year']."</li>";

}
echo "</ul>";
