<?php

function generate_new_ticket() {

    $db = new SQLite3("STARboard.db", SQLITE3_OPEN_READWRITE);

    do {
        $ticket_num = rand(); // generate a random ticket number

        $query = "SELECT COUNT(*) FROM 'tickets' WHERE ticket = {$ticket_num}";
        $count = $db->querySingle($query);

    } while ($count > 0); // continue generating until we find an unused ticket number

    return $ticket_num;
}

function store_ticket_for_user($username, $ticket) {
 
    $db = new SQLite3("STARboard.db", SQLITE3_OPEN_READWRITE);

    // retrieve the old ticket number
    $query = $db->prepare('SELECT * FROM "accounts" WHERE "username" = :username');
    $query->bindValue(':username', $username);
    $result = $query->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC);

    if (!$user) {
        die("Username does not exist in the database.");
    }
    
    $expired_ticket = $user['ticket'];

    // update 'accounts' database with the new ticket
    $query = $db->prepare('UPDATE "accounts" SET "ticket" = :ticket WHERE "username" = :username');
    $query->bindValue(':ticket', $ticket);
    $query->bindValue(':username', $username);
    $query->execute();

    // update 'tickets' database with the new ticket
    $ticket_expiry = new DateTime("+30 minutes"); // ticket expires in 30 minutes
    $ticket_expiry = $ticket_expiry->format('Y-m-d H:i:s'); // convert to SQL date format
    $query = $db->prepare('UPDATE "tickets" SET "ticket" = :ticket, "timeout" = :ticket_expiry WHERE "ticket" = :expired_ticket');
    $query->bindValue(':ticket', $ticket);
    $query->bindValue(':ticket_expiry', $ticket_expiry);
    $query->bindValue(':expired_ticket', $expired_ticket);
    $query->execute();

    $db->close();
}

function verify_ticket($ticket) {
    $db = new SQLite3("STARboard.db", SQLITE3_OPEN_READWRITE);
 
    $query = $db->prepare('SELECT * FROM "tickets" WHERE "ticket" = :ticket');
    $query->bindValue(':ticket', $ticket);
    $exec = $query->execute();
    $result = $exec->fetchArray(SQLITE3_ASSOC);

    // ticket does not exist in database
    if (!$result) {
        return 0; // calling program will send the user back to the login page
    }

    $timeout = $result['timeout'];
    $now = new DateTime(); // get current time
    $now = $now->format('Y-m-d H:i:s'); // convert to SQL date format

    if ($now > $timeout) { // ticket is expired
        return 0; // calling program will send the user back to the login page
    }

    $db->close();

    return 1; // ticket is valid
}

function get_ticket_permissions($ticket) {

    $db = new SQLite3("STARboard.db", SQLITE3_OPEN_READWRITE);

    $query = $db->prepare('SELECT * FROM "tickets" WHERE "ticket" = :ticket');
    $query->bindValue(':ticket', $ticket);
    $exec = $query->execute();

    // populate permissions array with account types for this ticket
    $permissions = [];
    while ($row = $exec->fetchArray(SQLITE3_ASSOC)) {
        $permissions[] = $row['account_type'];
    }

    $db->close();

    return $permissions;
}
?>