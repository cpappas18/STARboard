<?php

require_once("tickets.php");

$ticket = $_POST['ticket'];
$verified = verify_ticket($ticket);

if (!$verified) { // ticket does not exist in database or it is expired
    echo redirect_to_login(); // send the user back to the login page (kick them out)
    return;
}

$dbhost = "localhost";
$dbuser = "root";
$dbpass = "";
$dbname = "STARboard";

$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if ($con->connect_error) {
    die("Failed to connect to the database.");
}

$permissions = get_ticket_permissions($ticket);

// display headers based on permissions
echo "<h1><a href='#'>Rate a TA</a></h1>"; // visible to everyone

if (in_array("admin", $permissions) || in_array("sys_operator", $permissions)) {
    echo "<h1><a href='#'>TA Administration</a></h1>";
}

if (in_array("professor", $permissions) || in_array("sys_operator", $permissions) 
    || in_array("admin", $permissions) || in_array("TA", $permissions)) {
    echo "<h1><a href='#'>TA Management</a></h1>";
}

if (in_array("sys_operator", $permissions)) {
    echo "<h1><a href='#'>Sysop Tasks</a></h1>";
}

?>