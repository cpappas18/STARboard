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
$reg_courses = $_POST['courses'];

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
$account_types = json_decode($account_types, true); // convert JSON to array of account types
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

echo "
<p>Account created successfully. Please login to continue.</p>
<button type=\"button\" onclick=\"toggleLogin()\">Back to Login</button> 
";
?>
