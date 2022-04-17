<?php
$db_comments = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$stmt_comments = $db_comments->prepare("SELECT * FROM ratings WHERE student_ID= ".$TA_id."");
$comments = $stmt_comments->execute();


//$comments = $statement_comments->fetchAll(PDO::FETCH_ASSOC);

echo "<h3 class='red-detail'>Student Comments</h3></br>";

while ($comment = $comments->fetchArray(SQLITE3_ASSOC)){

    echo "<hr/>";
    echo "<p class='field-head'>".$comment['course']." ".$comment['term']."</p>";
    echo "<p>".$comment['comment']."</p>";

}
