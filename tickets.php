<?php

function generate_new_ticket() {

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "STARboard";
    
    $con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if ($con->connect_error) {
        die("Failed to connect to the database.");
    }

    do {
        $ticket_num = rand(); // generate a random ticket number

        $query = "SELECT * FROM tickets WHERE ticket = '$ticket_num'";
        $result = $con->query($query);

    } while (mysqli_num_rows($result) > 0); // continue generating until we find an unused ticket number

    return $ticket_num;
}

function store_ticket_for_user($username, $ticket) {

    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "STARboard";
    
    $con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if ($con->connect_error) {
        die("Failed to connect to the database.");
    }

    // retrieve the old ticket number
    $query0 = "SELECT * FROM accounts WHERE username='$username'";
    $result0 = $con->query($query0);

    if (mysqli_num_rows($result0) == 0) {
        die("Username does not exist in the database.");
    }

    $expired_ticket = $result0->fetch_assoc()['ticket'];

    // update 'accounts' database with the new ticket
    $query1 = "UPDATE accounts SET ticket='$ticket' WHERE username='$username'";
    $result1 = $con->query($query1);

    // update 'tickets' database with the new ticket
    $ticket_expiry = new DateTime("+30 minutes"); // ticket expires in 30 minutes
    $ticket_expiry = $ticket_expiry->format('Y-m-d H:i:s'); // convert to SQL date format
    $query2 = "UPDATE tickets SET ticket='$ticket', timeout='$ticket_expiry' WHERE ticket='$expired_ticket'";
    $result2 = $con->query($query2);

    // TODO: check for success and handle failures
}

function verify_ticket($ticket) {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "STARboard";
    
    $con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
    
    if ($con->connect_error) {
        die("Failed to connect to the database.");
    }

    $query = "SELECT * FROM tickets WHERE ticket='$ticket'";
    $result = $con->query($query);

    // ticket does not exist in database
    if (mysqli_num_rows($result) == 0) {
        return 0;  // calling program will send the user back to the login page
    }

    $timeout = $result->fetch_assoc()['timeout'];
    $now = new DateTime(); // get current time
    $now = $now->format('Y-m-d H:i:s'); // convert to SQL date format

    if ($now > $timeout) { // ticket is expired
        return 0; // calling program will send the user back to the login page
    }

    return 1; // ticket is valid
}

function get_ticket_permissions($ticket) {
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "";
    $dbname = "STARboard";

    $con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

    if ($con->connect_error) {
        die("Failed to connect to the database.");
    }

    $query = "SELECT * FROM tickets WHERE ticket='$ticket'";
    $result = $con->query($query);

    if (mysqli_num_rows($result) == 0) {
        die("Ticket does not exist in database");
    }

    // populate permissions array with account types for this ticket
    $permissions = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $permissions[] = $row['account_type'];
    }

    return $permissions;
}

function redirect_to_login() {
    echo 
    "<script>
    function redirect() { 
        window.location.replace('login.html'); 
    } 
    </script>";
}

?>