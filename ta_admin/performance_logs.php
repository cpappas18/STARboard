<?php
$db_performance = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
//find all prof comments about this ta
$stmt_performance = $db_performance->prepare("SELECT * FROM TAPerformance WHERE student_ID= ".$TA_id."");
$logs = $stmt_performance->execute();

echo "<h3 class='red-detail'>Performance Logs</h3></br>";

while ($perf = $logs->fetchArray(SQLITE3_ASSOC)){
    echo "<hr/>";
    echo "<p class='field-head'>".$perf['course_num']." ".$perf['term_month_year']."</p>";
    echo "<p>".$perf['comment']."</p>";
}
