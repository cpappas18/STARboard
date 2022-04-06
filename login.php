<?php

$db = new SQLite3("STARboard.db", SQLITE3_OPEN_READWRITE);

$username = $_POST['username'];
$query = $db->prepare('SELECT * FROM "accounts" WHERE "username" = :username');
$query->bindValue(':username', $username);
$result = $query->execute();
$user = $result->fetchArray(SQLITE3_ASSOC);

$db->close();

if ($user) {
    // retrieve password from database
    $hashed_pass = $user['password'];

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
