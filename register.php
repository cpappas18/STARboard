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

/*
if (mysqli_num_rows($result) > 0) {
    // retrieve password from database
    $hashed_pass = $result->fetch_assoc()['password'];
    echo 'hashed_pass';
    echo $hashed_pass;
    $password = $_POST['password'];
    echo 'password';
    echo $password;
    // check against the user input password
    //$login_success = password_verify($_POST['password'], $hashed_pass);
    $login_success = password_verify("pappas", $hashed_pass);

    $hash = password_verify($password, $hashed_pass);

    echo 'pappas_hashed';

    $pappas_hashed = password_hash("pappas", PASSWORD_DEFAULT);
    echo $pappas_hashed.'<br>';

    echo $hash;
    echo (int) $hash;

    if ($hash == 0) {

        echo "hashwrong";
    }

} else {
    echo '<text>Username does not exist.</text>';
}
echo $login_success;
if ($login_success) {
    echo 
   '<script> 
        function redirect() { 
            console.log("redirecting");
            window.location.replace("dashboard.html"); 
        } 
    </script>';
} else {
    echo '<text>Password is incorrect. Try again.</text>';
}*/
?>
