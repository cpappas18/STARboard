<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "users";

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
$registered_courses = $_POST['courses'];

$query = "INSERT INTO accounts values ('$username', '$hashed_pass')";
$result = $con->query($query);

if ($result) {
    echo "Account created";
    echo '<script> 
            function redirect() { 
                console.log("redirecting");
                window.location.replace("login.html"); 
            } 
        </script>'; 
} else {
    echo "Account could not be created";
}
?>
