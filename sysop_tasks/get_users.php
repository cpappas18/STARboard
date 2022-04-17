<?php

function convertAccountType1($type) {
    switch ($type) {
        case "student":
            return "Student";
        case "professor":
            return "Professor";
        case "admin":
            return "TA Administrator";
        case "TA":
            return "TA";
        case "sys-operator":
            return "System Operator";
    }
}

function convertAccountType2($type) {
    $type = strtolower($type);

    switch ($type) {
        case "student": 
            return "student";
        case "professor": 
            return "professor";
        case "admin": case "administrator": case "ta admin": case "ta administrator":
            return "admin";
        case "ta": case "teaching assistant": 
            return "TA";
        case "system operator": case "sysop": case "sys op":
            return "sys-operator";
    } 
}

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

// get a single user
if (isset($_GET['username'])) {
    $query = $db->prepare('SELECT * FROM "accounts" WHERE "username" = :username');
    $query->bindValue(':username', $_GET['username']);
    $users = $query->execute();
} 

// get all users
else {
    $db->exec('BEGIN');

    if (isset($_GET['filterOn']) && isset($_GET['filterBy'])) { // filter 
        switch ($_GET['filterOn']) {
            case 'username': 
                $users = $db->query('SELECT * FROM "accounts" WHERE "username" = "'.$_GET['filterBy'].'"'); 
                break; 
            case 'first-name': 
                $users = $db->query('SELECT * FROM "accounts" WHERE "first_name" = "'.$_GET['filterBy'].'"'); 
                break; 
            case 'last-name': 
                $users = $db->query('SELECT * FROM "accounts" WHERE "last_name" = "'.$_GET['filterBy'].'"');  
                break;
            case 'email': 
                $users = $db->query('SELECT * FROM "accounts" WHERE "email" = "'.$_GET['filterBy'].'"');  
                break;
            case 'student-id': 
                $users = $db->query('SELECT * FROM "accounts" WHERE "student_ID" = "'.$_GET['filterBy'].'"'); 
                break;
            case 'account-type': 
                // first get tickets with this account type
                $filterBy = convertAccountType2($_GET['filterBy']);
                $tickets = $db->query('SELECT * FROM "tickets" WHERE "account_type" = "'.$filterBy.'"');
                $query_list = "";
                $ticket = $tickets->fetchArray(SQLITE3_ASSOC);

                // create a list of all ticket numbers 
                if ($ticket) {
                    $query_list = "".$ticket['ticket'];

                    while ($ticket = $tickets->fetchArray(SQLITE3_ASSOC)) {
                        $query_list = $query_list.",".$ticket['ticket'];
                    }
                }
                
                // now get all accounts with these tickets
                $users = $db->query('SELECT * FROM "accounts" WHERE "ticket" IN ('.$query_list.')');
                break;
            case 'course':
                // first get student IDs that are taking this course
                $students = $db->query('SELECT * FROM "registered_courses" WHERE "course" = "'.$_GET['filterBy'].'"');
                $query_list = "";
                $student = $students->fetchArray(SQLITE3_ASSOC);

                // create a list of all student IDs
                if ($student) {
                    $query_list = "".$student['student_ID'];

                    while ($student = $students->fetchArray(SQLITE3_ASSOC)) {
                        $query_list = $query_list.",".$student['student_ID'];
                    }
                }
                
                // now get all accounts with these student IDs
                $users = $db->query('SELECT * FROM "accounts" WHERE "student_ID" IN ('.$query_list.')');
                break;
        }
 
    } else { // no filtering
        $users = $db->query('SELECT * FROM "accounts"');  
    }

    $db->exec('COMMIT');
}


echo '<table>';
echo'<tr>
    <th class="red-label">Username</th>
    <th class="red-label">First Name</th>
    <th class="red-label">Last Name</th>
    <th class="red-label">Email</th>
    <th class="red-label">Account Types</th>
    <th class="red-label">Student ID</th>
    <th class="red-label">Registered Courses</th>
    <th style="width:120px;" class="red-label">Actions</th>
    </tr>';


while ($user = $users->fetchArray(SQLITE3_ASSOC)) {

    // create comma-separated list of account types
    $query = $db->prepare('SELECT * FROM "tickets" WHERE "ticket" = :ticket');
    $query->bindValue(':ticket', $user['ticket']);
    $result = $query->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    $acct_types = "".convertAccountType1($row['account_type']);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $acct_types = $acct_types.", ".convertAccountType1($row['account_type']);
    }

    // create comma-separated list of courses
    $courses = "";
    if (strlen(strval($user['student_ID'])) > 0) {
        $query = $db->prepare('SELECT * FROM "registered_courses" WHERE "student_ID" = :student_ID');
        $query->bindValue(':student_ID', $user['student_ID']);
        $result = $query->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);
        
        if ($row) {
            $courses = "".$row['course'];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $courses = $courses.", ".$row['course'];
            }
        }
    }

    echo 
    '<tr>
        <td>'.$user['username'].'</td>
        <td>'.$user['first_name'].'</td>
        <td>'.$user['last_name'].'</td>
        <td>'.$user['email'].'</td>
        <td>'.$acct_types.'</td>
        <td>'.$user['student_ID'].'</td>
        <td>'.$courses.'</td>
        <td>
            <button type="button" class="btn btn-default btn-lg" onclick=\'showAccount("'.$user['username'].'")\'>
                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
            </button>
            <button type="button" class="btn btn-default btn-lg" onclick=\'deleteAccount("'.$user['username'].'", "'.$user['student_ID'].'", "'.$user['ticket'].'")\'>
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        </td>
    </tr>';
}

echo '</table>';

$db->close();

?>