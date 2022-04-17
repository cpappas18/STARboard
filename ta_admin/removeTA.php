<?php
$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$term = filter_var($_POST['term'], FILTER_SANITIZE_SPECIAL_CHARS);
$course_num = filter_var($_POST['course_num'], FILTER_SANITIZE_SPECIAL_CHARS);
$TA_name = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
$id = filter_var($_POST['id'], FILTER_SANITIZE_SPECIAL_CHARS);

if($course_num == '' || $term=='' || $TA_name=='' ||$id==''){
    echo "Invalid input, must provide all fields";
}
else{

$query = 'DELETE FROM TAassignment '
    .'WHERE student_ID = :student_ID AND course_num = :course_num';
$stmt = $db->prepare($query);
$stmt->bindValue(':course_num', $course_num);
$stmt->bindValue(':student_ID', $id);
$stmt->execute();




echo "TA removed successfully!";
}
?>