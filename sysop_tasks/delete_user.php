<?php

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

// delete account from 'accounts' table
$query = $db->prepare('DELETE FROM "accounts" WHERE "username" = :username');
$query->bindValue(':username', $_POST['username']);
$query->execute();

// delete ticket info from 'tickets' table
$query = $db->prepare('DELETE FROM "tickets" WHERE "ticket" = :ticket');
$query->bindValue(':ticket', $_POST['ticket']);
$query->execute();

// delete courses from 'registered_courses' table
if (strlen($_POST['student_id']) > 0) {
    $query = $db->prepare('DELETE FROM "registered_courses" WHERE "student_ID" = :student_ID');
    $query->bindValue(':student_ID', $_POST['student_id']);
    $query->execute();
}

$db->close();

?>