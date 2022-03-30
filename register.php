<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "STARboard";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($con->connect_error) {
    die("Failed to connect to the database.");
}

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

require_once("tickets.php");
$ticket = generate_new_ticket();

// store account in 'accounts' table
if (strlen($student_id) > 0) {
    $query = "INSERT INTO accounts VALUES ('$username', '$hashed_pass', '$first_name', '$last_name', '$email', '$student_id', '$ticket')";
} else {
    $query = "INSERT INTO accounts (username, password, first_name, last_name, email, ticket)
    VALUES ('$username', '$hashed_pass', '$first_name', '$last_name', '$email', '$ticket')";
}
$result1 = $con->query($query);

// store courses in 'registered_courses' table if the user is a student
$result2 = true; 
if (strlen($student_id) > 0) {
    $reg_courses = json_decode($reg_courses, true); // convert JSON to array of courses

    foreach ($reg_courses as &$course) {
        $query = "INSERT INTO registered_courses values ('$student_id', '$course')";
        $result2 = $con->query($query);
    }
}

// store ticket and permissions (account types) in 'tickets' table
$account_types = json_decode($account_types, true); // convert JSON to array of account types
$ticket_expiry = new DateTime("+30 minutes"); // ticket expires in 30 minutes
$ticket_expiry = $ticket_expiry->format('Y-m-d H:i:s'); // convert to SQL date format

$result3 = true;
foreach ($account_types as &$type) {
    $query = "INSERT INTO tickets values ('$ticket', '$ticket_expiry', '$type')";
    $result3 = $con->query($query); 
}

if ($result1 && $result2 && $result3) {
    echo 
    "<p>Account created successfully.</p>
    <p>Click <a href='login.html'>here</a> to login.</p>";
} else {
    echo "Account could not be created";
    // TODO may have to delete some database entries if other ones could not be added
}
?>
