<?php
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    if(!$db){
        echo $db->lastErrorMsg();
    }

//import cohort info from file
$cohort = fopen("./import_csv/TACohort.csv", "r");
//remove headerline
fgetcsv($cohort);
while(($line = fgetcsv($cohort)) != FALSE){
    $db->exec("Insert or replace into TACohort(term_month_year,TA_name,student_ID,legal_name,email,grad_ugrad,supervisor_name,priority,hours,date_applied,location,phone,degree,courses_applied_for,open_to_other_courses,notes)
    VALUES('".$line[0]."', '".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."','".$line[7]."','".$line[8]."','".$line[9]."','".$line[10]."','".$line[11]."','".$line[12]."','".$line[13]."','".$line[14]."','".$line[15]."')");

}
fclose($cohort);

$quota = fopen("./import_csv/CourseQuota.csv", "r");
//remove headerline
fgetcsv($quota);
while(($line = fgetcsv($quota)) != FALSE){
    $db->exec("Insert or replace into CourseQuota(term_month_year,course_num,course_type,course_name,instructor_name,course_enrollment_num,TA_quota)
    VALUES('".$line[0]."', '".$line[1]."','".$line[2]."','".$line[3]."','".$line[4]."','".$line[5]."','".$line[6]."')");

}
fclose($quota);
$db->close();
?>