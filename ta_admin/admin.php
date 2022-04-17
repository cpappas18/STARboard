<?php
    $db = new SQLite3("../STARboard.db");
    if(!$db){
        echo $db->lastErrorMsg();
    }
    
    else{
        //drop and create new cohort table
    $db->exec('DROP TABLE IF EXISTS TACohort');
    $sql = 'CREATE TABLE IF NOT EXISTS "TACohort"(
        "term_month_year" TEXT,
        "TA_name" TEXT,
        "student_ID" INTEGER,
        "legal_name" TEXT,
        "email" TEXT,
        "grad_ugrad" TEXT,
        "supervisor_name" TEXT,
        "priority" TEXT,
        "hours" INTEGER,
        "date_applied" TEXT,
        "location" TEXT,
        "phone" INTEGER,
        "degree" TEXT,
        "courses_applied_for" TEXT,
        "open_to_other_courses" TEXT,
        "notes" TEXT,
        UNIQUE("term_month_year","student_ID"))';
    
    $db->exec($sql) or die('Table creation failed');
    
    $db->exec('DROP TABLE IF EXISTS CourseQuota');
    $course_info = 'CREATE TABLE IF NOT EXISTS "CourseQuota"(
        "term_month_year" TEXT,
        "course_num" INT,
        "course_type" TEXT,
        "course_name" TEXT,
        "instructor_name" TEXT,
        "course_enrollment_num" INTEGER,
        "TA_quota" INTEGER,
        UNIQUE("term_month_year","course_num"))';
    
    $db->exec($course_info) or die('Table creation failed');}

    $assign = 'CREATE TABLE IF NOT EXISTS "TAassignment"(
        "term_month_year" TEXT,
        "course_num" TEXT,
        "TA_name" TEXT,
        "student_ID" INTEGER,
        "assigned_hours" INTEGER,
        UNIQUE("term_month_year","course_num","student_ID"))';
    
    $db->exec($assign) or die('Table creation failed');

    
?>


