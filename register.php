<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "users";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($con->connect_error) {
    die("Failed to connect to the database.");
}

$username = $_POST['username'];
$password = $_POST['password'];

$hashed_pass = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO accounts values ('$username', '$hashed_pass')";
$result = $con->query($query);

if ($result) {
    echo "Account created";
} else {
    echo "Account could not be created";
}
?>
