<?php //returns whether $user can view $course as a ADMIN_SYSOP, TA, PROF, or they don't have permission
    
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    require_once("../dashboard/tickets.php");
    $course = $_POST['course'];
    $ticket = $_POST['ticket'];
    $user = $_POST['user'];
    $permissions = get_ticket_permissions($ticket);
    if (in_array("admin", $permissions) || in_array("sys-operator", $permissions)) {
        //check if the course is valid
        $query = $db->prepare('SELECT * FROM "assignedProfs" WHERE "course_num" = :course');
        $query->bindValue(':course', $course);
        $exec = $query->execute();
        while ($row = $exec->fetchArray(SQLITE3_ASSOC)) {
            if ($row['course_num'] == $course)
            {
                echo "ADMIN_SYSOP";
                break;
            }
        }
       
    }

    //Check if the user is a TA for course
    else if (in_array("TA", $permissions))
    {
        $TA_name = $user;
    
        $query = $db->prepare('SELECT * FROM "TAmanagement" WHERE "taName" = :TA_name');
        $query->bindValue(':TA_name', $TA_name);
        $exec = $query->execute();
        while ($row = $exec->fetchArray(SQLITE3_ASSOC)) {
            if ($row['course'] == $course)
            {
                echo "TA";
                break;
            }
        }
    }
    else if (in_array("professor", $permissions)){
        $prof_name = $user;
        $q = "SELECT * FROM assignedProfs WHERE instructor_assigned_name = '".$prof_name."';";
        
        $query = $db->prepare($q);
        $exec = $query->execute();
       
        while ($row = $exec->fetchArray(SQLITE3_ASSOC)) {
            $coursenum = $row['course_num'];
            if ($row['course_num'] == $course)
            {
                echo "PROF";
                break;
            }
            
        }
    }
    
    $db -> close();

?>
    