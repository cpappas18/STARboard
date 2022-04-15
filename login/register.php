<?php

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

// define all fields to add to the database
$username = $_POST['username'];
$password = $_POST['password'];
$hashed_pass = password_hash($password, PASSWORD_DEFAULT);
$email = $_POST['email'];
$student_id = $_POST['studentid'];
$first_name = $_POST['firstname'];
$last_name = $_POST['lastname'];
$account_types = $_POST['accounttypes'];
$account_types = json_decode($account_types, true); // convert JSON to array of account types
$reg_courses = $_POST['courses'];

// check if the username already exists
$statement = 'SELECT * FROM "accounts" WHERE "username" = :username';
$query = $db->prepare($statement);
$query->bindValue(':username', $username);
$result = $query->execute();
$user_exists = $result->fetchArray(SQLITE3_ASSOC);

if ($user_exists) {
    echo "<div class='error'>The username already exists.</div>";
    die();
}

// check if the student ID already exists
if (strlen($student_id) > 0) {
    $statement = 'SELECT * FROM "accounts" WHERE "student_ID" = :student_id';
    $query = $db->prepare($statement);
    $query->bindValue(':student_id', $student_id);
    $result = $query->execute();
    $user_exists = $result->fetchArray(SQLITE3_ASSOC);

    if ($user_exists) {
        echo "<div class='error'>This student ID belongs to another user.</div>";
        die();
    }
}

// if the user is registering as a TA, their information must be in the TACohorts table already and the name must match
if (in_array("TA", $account_types)) {
    $db2 = new SQLite3("../STARboard_apr14.db", SQLITE3_OPEN_READWRITE);
    $statement = 'SELECT * FROM "TACohort" WHERE "student_ID" = :student_id';
    $query = $db2->prepare($statement);
    $query->bindValue(':student_id', $student_id);
    $result = $query->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC); 

    if (!$user) {
        if (strcmp($_POST['sender'], "user")==0) {
            echo "<div class='error'>You cannot register as a TA because your student ID is not associated with a TA account. 
            If you think this is a mistake, please contact your TA administrator for assistance.</div>";
            die();
        }

        else if (strcmp($_POST['sender'], "sysop")==0) {
            echo "<div class='error'>This account cannot have TA privileges because this user's student ID is not associated with any TA on file. 
            If you think this is a mistake, please contact the TA administrator for assistance.</div>";
            die();      
        }
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

// if the user is registering as a professor, their information must be in the assignedProfs table already 
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
        if (strcmp($_POST['sender'], "user")==0) {
            echo "<div class='error'>You cannot register as a professor because your name is not associated with a professor account. 
            If you think this is a mistake, please contact the system operator for assistance.</div>";
            die();
        } else if (strcmp($_POST['sender'], "sysop")==0) {
            echo "<div class='error'>This account cannot have professor privileges because this user's name is not associated with any professor on file.
            Please add/import the professor to the system before adding professor privileges to this account.</div>";
            die();
        }
    }
}

require_once("../dashboard/tickets.php");
$ticket = generate_new_ticket();

// store account in 'accounts' table
if (strlen($student_id) > 0) {
    $statement = 'INSERT INTO "accounts" VALUES (:username, :hashed_pass, :first_name, :last_name, :email, :id, :ticket)';
    $query = $db->prepare($statement);
    $query->bindValue(':id', $student_id);
} else {
    $statement = 'INSERT INTO "accounts" ("username", "password", "first_name", "last_name", "email", "ticket")
    VALUES (:username, :hashed_pass, :first_name, :last_name, :email, :ticket)';
    $query = $db->prepare($statement);
}

$query->bindValue(':username', $username);
$query->bindValue(':hashed_pass', $hashed_pass);
$query->bindValue(':first_name', $first_name);
$query->bindValue(':last_name', $last_name);
$query->bindValue(':email', $email);
$query->bindValue(':ticket', $ticket);
$query->execute();

// store courses in 'registered_courses' table if the user is a student
if (strlen($student_id) > 0) {
    $reg_courses = json_decode($reg_courses, true); // convert JSON to array of courses
    $statement = 'INSERT INTO "registered_courses" VALUES (:student_id, :course)';

    foreach ($reg_courses as &$course) {
        $query = $db->prepare($statement);
        $query->bindValue(':student_id', $student_id);
        $query->bindValue(':course', $course);
        $query->execute();
    }
}

// store ticket and permissions (account types) in 'tickets' table
$ticket_expiry = new DateTime("+30 minutes"); // ticket expires in 30 minutes
$ticket_expiry = $ticket_expiry->format('Y-m-d H:i:s'); // convert to SQL date format
$statement = 'INSERT INTO "tickets" VALUES (:ticket, :ticket_expiry, :type)';

foreach ($account_types as &$type) {
    $query = $db->prepare($statement);
    $query->bindValue(':ticket', $ticket);
    $query->bindValue(':ticket_expiry', $ticket_expiry);
    $query->bindValue(':type', $type);
    $query->execute();
}

$db->close();

if (strcmp($_POST['sender'], "user")==0) {
    echo "
    <p>Account created successfully. Please login to continue.</p>
    <button type=\"button\" onclick=\"toggleLogin()\">Back to Login</button>";
} else if (strcmp($_POST['sender'], "sysop")==0) {
    echo "
    <p>Account created successfully.</p>
    <button type=\"button\" onclick=\"window.location.replace('./manage_users.html');\">Back to Accounts <i class='bi bi-arrow-return-left'></i></button>";
}
?>
