<?php
$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$term = filter_var($_POST['term'], FILTER_SANITIZE_SPECIAL_CHARS);
$course_num = filter_var($_POST['course_num'], FILTER_SANITIZE_SPECIAL_CHARS);
$title = filter_var($_POST['title'], FILTER_SANITIZE_SPECIAL_CHARS);
$prof = filter_var($_POST['prof'], FILTER_SANITIZE_SPECIAL_CHARS);
if($prof == '' || $term=='' || $course_num=='' ||$title==''){
    echo "Invalid input, must provide all fields";
}
else{
$statement='INSERT or REPLACE INTO "assignedProfs" ("term_month_year", "course_num", "course_name", "instructor_assigned_name")
VALUES(:term_month_year, :course_num, :title, :prof)';
$query = $db->prepare($statement);

$query->bindValue(':term_month_year', $term);
$query->bindValue(':course_num', $course_num);
$query->bindValue(':title', $title);
$query->bindValue(':prof', $prof);
$query->execute();


echo "Professor added successfully!";
}

?>