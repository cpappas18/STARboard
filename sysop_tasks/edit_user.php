<?php

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

// get all fields from the POST request
$old_username = $_POST['old_username'];
$new_username = $_POST['new_username'];
$email = $_POST['email'];
$student_id = $_POST['studentid'];
$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$account_types = $_POST['accounttypes'];
$reg_courses = $_POST['courses'];

// get ticket and original student ID before updating it so we can update the other tables
$query = $db->prepare('SELECT * FROM "accounts" WHERE "username" = :username');
$query->bindValue(':username', $old_username);
$result = $query->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

if (!$user) {
    echo "<div class='error'>Username could not be found in the database.</div>";
    die();
}

$old_student_id = $user['student_ID'];
$ticket = $user['ticket'];

$account_types = json_decode($account_types, true); // convert JSON to array of account types

// if the user is being set as a TA, their information must be in the TACohorts table already and the name must match
if (in_array("TA", $account_types)) {
    $db2 = new SQLite3("../STARboard_apr14.db", SQLITE3_OPEN_READWRITE);
    $statement = 'SELECT * FROM "TACohort" WHERE "student_ID" = :student_id';
    $query = $db2->prepare($statement);
    $query->bindValue(':student_id', $student_id);
    $result = $query->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC); 

    if (!$user) {
        echo "<div class='error'>This account cannot have TA privileges because this user's student ID is not associated with any TA on file. 
        If you think this is a mistake, please contact the TA administrator for assistance.</div>";
        die();        
    } else {
        $first_name = trim($first_name);
        $last_name = trim($last_name);
        $name = $first_name." ".$last_name;

        if (strcmp($name, $user['legal_name']) != 0) {
            echo "<div class='error'>Error: The name provided does not match the name on file for this student ID.</div>";
            die();
        }
    }

    $db2->close();
}

// if the user is being set as a professor, their information must be in the assignedProfs table already 
if (in_array("professor", $account_types)) {
    $first_name = trim($first_name);
    $last_name = trim($last_name);
    $name = $first_name." ".$last_name;

    $statement = 'SELECT * FROM "assignedProfs" WHERE "instructor_assigned_name" = :name';
    $query = $db->prepare($statement);
    $query->bindValue(':name', $name);
    $result = $query->execute();
    $user_exists = $result->fetchArray(SQLITE3_ASSOC);

    if (!$user_exists) {
        echo "<div class='error'>This account cannot have professor privileges because this user's name is not associated with any professor on file.
        Please add/import the professor to the system before adding professor privileges to this account.</div>";
        die();
    }
}

// update 'accounts' table 
$query = $db->prepare('UPDATE "accounts" SET "username"=:new_username, "email"=:email, "student_ID"=:student_ID, "first_name"=:first_name,
            "last_name"=:last_name, "email"=:email WHERE "username"=:old_username');
$query->bindValue(':new_username', $new_username);
$query->bindValue(':email', $email);

// only insert the student ID if the user is a student or TA
if (in_array("student", $account_types) || in_array("TA", $account_types)) {
    $query->bindValue(':student_ID', $student_id);
} else {
    $query->bindValue(':student_ID', "");
}

$query->bindValue(':first_name', $first_name);
$query->bindValue(':last_name', $last_name);
$query->bindValue(':email', $email);
$query->bindValue(':old_username', $old_username);
$query->execute();

// delete all previously registered courses
if (strlen($old_student_id) > 0) {
    $query = $db->prepare('DELETE FROM "registered_courses" WHERE "student_ID" = :student_id');
    $query->bindValue(':student_id', $old_student_id);
    $query->execute();
}

// store new courses in 'registered_courses' table
if (in_array("student", $account_types)) {
    $reg_courses = json_decode($reg_courses, true); // convert JSON to array of courses
    $statement = 'INSERT INTO "registered_courses" VALUES (:student_id, :course)';

    foreach ($reg_courses as &$course) {
        $query = $db->prepare($statement);
        $query->bindValue(':student_id', $student_id);
        $query->bindValue(':course', $course);
        $query->execute();
    }
}

// get ticket expiry before deleting its entries
$query = $db->prepare('SELECT * FROM "tickets" WHERE "ticket" = :ticket');
$query->bindValue(':ticket', $ticket);
$result = $query->execute();
$row = $result->fetchArray(SQLITE3_ASSOC);

if (!$row) {
    echo "<div class='error'>Ticket for this user does not exist.</div>";
    die();
}

$ticket_expiry = $row['timeout'];

// delete all previous permissions for this ticket
$query = $db->prepare('DELETE FROM "tickets" WHERE "ticket" = :ticket');
$query->bindValue(':ticket', $ticket);
$query->execute();

// store new account types in 'tickets' table
$statement = 'INSERT INTO "tickets" VALUES (:ticket, :ticket_expiry, :type)';

foreach ($account_types as &$type) {
    $query = $db->prepare($statement);
    $query->bindValue(':ticket', $ticket);
    $query->bindValue(':ticket_expiry', $ticket_expiry);
    $query->bindValue(':type', $type);
    $query->execute();
}

$db->close();

echo "
<p>Your changes have been saved.</p>
<button type=\"button\" onclick=\"window.location.replace('./manage_users.html');\" class=\"style-button\">Back to Accounts <i class='bi bi-arrow-return-left'></i></button> 
";

?>