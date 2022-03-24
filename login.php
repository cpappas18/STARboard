<html>
    <head>
    </head>

    <body>
        <!-- TODO: remove all this code, implement login verification -->

        Welcome <?php echo $_POST["username"];?><br>
        Your encrypted password is <?php echo $_POST["password"];?><br>

        <?php 
            $login_success = true;

            // TODO: redirection is not working for some reason
            if ($login_success) {
                echo '<script> window.location.replace("dashboard.html"); </script>';
            }
        ?>
    </body>
</html>