<?php
$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
//assign a ta to a course in the ta assignment table using user inputs
$term = filter_var($_POST['term'], FILTER_SANITIZE_SPECIAL_CHARS);
$course_num = filter_var($_POST['course_num'], FILTER_SANITIZE_SPECIAL_CHARS);
$TA_name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
$id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);
$assign_hours = $_POST['hours'];

if($course_num == '' || $term=='' || $TA_name=='' ||$id==''){
    echo "Invalid input, must provide all fields";
}
else{

$statement='INSERT or REPLACE INTO "TAassignment" ("term_month_year", "course_num", "TA_name", "student_ID", "assigned_hours")
VALUES(:term_month_year, :course_num, :TA_name, :student_ID, :assigned_hours)';
$query = $db->prepare($statement);

$query->bindValue(':term_month_year', $term);
$query->bindValue(':course_num', $course_num);
$query->bindValue(':TA_name', $TA_name);
$query->bindValue(':student_ID', $id);
$query->bindValue(':assigned_hours', $assign_hours);
$query->execute();


echo "TA added successfully!";
}

?>