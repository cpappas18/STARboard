<?php

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "STARboard";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($con->connect_error) {
    die("Failed to connect to the database.");
}

$username = $_POST['username'];
$query = "SELECT * FROM accounts WHERE username = '$username'";
$result = $con->query($query);

if (mysqli_num_rows($result) > 0) {
    // retrieve password from database
    $hashed_pass = $result->fetch_assoc()['password'];

    // check against the user input password
    $login_success = password_verify($_POST['password'], $hashed_pass);

} else {
    echo '<text>Username does not exist.</text>';
    return;
}

if ($login_success) {

    require_once("tickets.php");
    $ticket = generate_new_ticket();
    store_ticket_for_user($username, $ticket);

    echo 
   "<script> 
        function save_ticket_in_cookie() {
            document.cookie = \"ticket=$ticket\";
        }

        function redirect() { 
            window.location.replace('dashboard.html'); 
        } 
    </script>";
} else {
    echo '<text>Password is incorrect. Try again.</text>';
}
?>
