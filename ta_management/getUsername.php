<?php
$db_name = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
$ticket = $_POST['ticket'];
$query_name = $db_name->prepare('SELECT * FROM "accounts" WHERE "ticket" = :ticket');
$query_name->bindValue(':ticket', $ticket);
$exec_name = $query_name->execute();

//get the name associated with the ticket
$name;
$first;
$last;
while ($row_name = $exec_name->fetchArray(SQLITE3_ASSOC)) {
    $first = $row_name['first_name'];
    $last = $row_name['last_name'];
    $first = trim($first);
    $last = trim($last);
    $name = $first . " " . $last;
    break;
}

$db_name->close();
echo $name;
?>