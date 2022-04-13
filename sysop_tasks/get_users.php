<?php

function convertAccountType($type) {
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
    $users = $db->query('SELECT * FROM "accounts"');
    $db->exec('COMMIT');
}


echo '<table>';
echo'<tr>
    <th>Username</th>
    <th>First Name</th>
    <th>Last Name</th>
    <th>Email</th>
    <th>Account Types</th>
    <th>Student ID</th>
    <th>Registered Courses</th>
    <th style="width:120px;">Actions</th>
    </tr>';


while ($user = $users->fetchArray(SQLITE3_ASSOC)) {

    // create comma-separated list of account types
    $query = $db->prepare('SELECT * FROM "tickets" WHERE "ticket" = :ticket');
    $query->bindValue(':ticket', $user['ticket']);
    $result = $query->execute();
    $row = $result->fetchArray(SQLITE3_ASSOC);
    
    $acct_types = "".convertAccountType($row['account_type']);

    while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
        $acct_types = $acct_types.", ".convertAccountType($row['account_type']);
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