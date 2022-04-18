<style>
.term{
    font-size: 12px;
    color: #8d99ae;

}
</style>

<?php //Shows all the courses which the user can enter the "TA management" section with.
        // This script is used to get the courses in the top nav "corses" drop down and for 
        //the dashboard courses section

    ini_set("display_errors", 1);
    error_reporting(E_ALL);
    $db_dropdown = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $nameOfUser = obtainNameFromTicket($ticket);

    //sysop: show all courses in the assignedProfs db (even past terms)
    if (in_array("admin", $permissions) || in_array("sys-operator", $permissions)) 
    {
        $query_dropdown = $db_dropdown->prepare('SELECT * FROM "assignedProfs"');
        $exec_dropdown = $query_dropdown->execute();
        //display each course as an option
        while ($row_dropdown = $exec_dropdown->fetchArray(SQLITE3_ASSOC)) {
            echo '<a class="option" href="../ta_management/taManagement.html?Course=' 
                .$row_dropdown['course_num'] 
                . '"><i class="fa fa-bookmark" aria-hidden="true"></i>' 
                . $row_dropdown['course_num'] 
                .'<div class="term">'
                . $row_dropdown['term_month_year']
                .'</div></a>';
        }
    }

    //TA: get a list of their courses through the DB table "TAmanagement"
    if (in_array("TA", $permissions))
    {
        $TA_name = $nameOfUser;
        
        $query_dropdown = $db_dropdown->prepare('SELECT * FROM "TAmanagement" WHERE "taName" = :TA_name');
        $query_dropdown->bindValue(':TA_name', $TA_name);
        $exec_dropdown = $query_dropdown->execute();
        //display each course as a drop down option
        while ($row_dropdown = $exec_dropdown->fetchArray(SQLITE3_ASSOC)) {
            echo '<a class="option" href="../ta_management/taManagement.html?Course=' .$row_dropdown['course'] . '"><i class="fa fa-bookmark" aria-hidden="true"></i>' . $row_dropdown['course'] .'</a>';
        }
    }


    //Professor: get a list of their courses through the "assigned_profs" table
    if (in_array("professor", $permissions))
    {
        $prof_name = $nameOfUser;
        $q = "SELECT * FROM assignedProfs WHERE instructor_assigned_name = '".$prof_name."';";
        
        $query_dropdown = $db_dropdown->prepare($q);
        $exec_dropdown = $query_dropdown->execute();
       
        //display each course as a drop down option
        while ($row_dropdown = $exec_dropdown->fetchArray(SQLITE3_ASSOC)) {
            $coursenum = $row_dropdown['course_num'];
            
            echo '<a class="option" href="../ta_management/taManagement.html?Course=' .$row_dropdown['course_num'] . '"><i class="fa fa-bookmark" aria-hidden="true"></i>' . $row_dropdown['course_num'] .'</a>';
            
        }
    }

    

    /**
     * Returns the name associated with $ticket in the form "firstname lastname"
     */
    function obtainNameFromTicket($ticket) {

        $db_name = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    
        $query_name = $db_name->prepare('SELECT * FROM "accounts" WHERE "ticket" = :ticket');
        $query_name->bindValue(':ticket', $ticket);
        $exec_name = $query_name->execute();
    
        //get the name associated with the ticket
        $name;
        $first;
        $last;
        while ($row_name = $exec_name->fetchArray(SQLITE3_ASSOC)) {
            $first = $row_name['first_name'];
            $last = $row_name['last_name'];
            $first = trim($first);
            $last = trim($last);
            $name = $first . " " . $last;
            break;
        }

        $db_name->close();
        return $name;
    }
    
    $db_dropdown->close();
?>