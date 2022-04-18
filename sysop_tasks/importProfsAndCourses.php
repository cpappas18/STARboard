<?php
    $db_import = new SQLite3('../STARboard.db');
    if(!$db_import){
        echo $db_import->lastErrorMsg();
    }

    //import courseaAndProfs info from file
    $coursesAndProfs = fopen("./import_csv/coursesAndProfs.csv", "r");

    //remove headerline
    fgetcsv($coursesAndProfs);


    while(($line = fgetcsv($coursesAndProfs)) != FALSE){
       
        $db_import->exec("Insert or replace into assignedProfs(term_month_year,course_num,course_name,instructor_assigned_name)
        VALUES('".$line[0]."', '".$line[1]."','".$line[2]."','".$line[3]."')");
    }
    fclose($coursesAndProfs);

    $db_import->close();
?>