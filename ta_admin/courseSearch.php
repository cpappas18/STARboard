
<style>
<?php include 'admin.css';
?></style>
<?php
$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
//from user input field search for all tas currently assigned to this course
$query = $_POST['query'];
$category = $_POST['category'];
$term = $_POST['term'];


$stmt = $db->prepare("SELECT distinct student_ID, term_month_year FROM TAassignment WHERE UPPER(".$category.") like UPPER('%".$query."%') AND term_month_year = '".$term."'");
$tas = $stmt->execute();


// //print_r($tas);
$divid = 0;
while ($ta = $tas->fetchArray(SQLITE3_ASSOC)){
    $TA_id = $ta['student_ID'];
    $TA_term = $ta['term_month_year'];
    echo "<div class='record'>";
    require "populate_report.php";
    echo "<button class='editButton' onclick='myFunction(this, ".$divid.")'>show more
    <i class='fa fa-chevron-circle-down' aria-hidden='true'></i></button>";
    echo "</div>";
    $divid +=1;
}


