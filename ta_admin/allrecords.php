<style>
<?php include 'admin.css';
?></style>

<?php

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);


$statement = $db->prepare("SELECT * FROM TACohort");
$result = $statement->execute();



//print_r($tas);
$divid = 0;

while ($tas = $result->fetchArray(SQLITE3_ASSOC)){
    $TA_id = $tas['student_ID'];
    $TA_term = $tas['term_month_year'];
    echo "<div class='record'>";
    require "populate_report.php";
    echo "<button class='editButton' onclick='myFunction(this,".$divid.")'>show more
    <i class='fa fa-chevron-circle-down' aria-hidden='true'></i></button>";
    echo "</div>";
    $divid +=1;
}



