<style>
    a:link { text-decoration: none; }
    a:visited { text-decoration: none; }
    a:hover { text-decoration: none; }
    a:active { text-decoration: none; }
</style>

<?php

require_once("tickets.php");

$ticket = $_POST['ticket'];
$verified = verify_ticket($ticket);

if (!$verified) { // ticket does not exist in database or it is expired
    // send the user back to the login page (kick them out)
    echo 
    "<script>
    function redirect() { 
        window.location.replace('../login/login.html'); 
    } 
    </script>";

    return;
}


// display headers based on permissions
$permissions = get_ticket_permissions($ticket);


/**
 * THE FOLLOWING SECTIONS ARE VISIBLE TO EVERYONE 
 */ 

echo '
<!--- TOPNAV: Logo and website name --->
<img class = "topNavLogo" src="../media/logo_light.png">
<a href="../dashboard/dashboard.html" class="logoText">STAR<span style = "color: white">board</span></a>';

echo '
<!--- SIGNOUT BUTTON WITH DROP-DOWN--->
<div class = "topNavOption" onclick="menuItemSelected(\'signout\')" id="signout">
    <div class="dropDownMenu">
        <!--- SIGN OUT BUTTON--->
        <a class="dropDownButton" onclick="toggleDropDown(\'signout_dropDown\')">
            <i class="fa fa-user-circle-o" aria-hidden="true"></i>
            SIGN-OUT
            <i class="fa fa-angle-down" aria-hidden="true"></i>
        </a>
        <!--- DROP-DOWN--->
        <div class="dropDown_contents" style="display: none;"  id="signout_dropDown">
            <a href="../login/login.html">
                <i class="fa fa-sign-out" aria-hidden="true"></i>
                Confirm sign-out
            </a>
        </div>
    </div>
</div>';

echo '
<!--- RATINGS BUTTON--->
<div class="topNavOption" onclick="menuItemSelected(\'ratings\')" id="ratings">
    <i class="fa fa-thumbs-up" aria-hidden="true"></i><a class="buttonLabel" href="../ratings/ratings.html">RATINGS</a>
</div>';



/**
 * VISIBLE TO ADMIN AND SYSOP
 */
if (in_array("admin", $permissions) || in_array("sys-operator", $permissions)) {
    echo '
    <!--- ADMIN BUTTON--- (FOR ADMIN AND SYSOP ONLY)-->
    <div class="topNavOption" onclick="menuItemSelected(\'admin\')" id="admin">
        <i class="fa fa-sliders" aria-hidden="true"></i><a class = "buttonLabel" id="adminButton" href="../ta_admin/admin.html"> ADMIN</a>
    </div>';
}

/**
 * TA MANAGEMENT: VISIBLE TO ALL EXCEPT STUDENTS
 */
if (in_array("professor", $permissions) || in_array("sys-operator", $permissions) 
    || in_array("admin", $permissions) || in_array("TA", $permissions)) {
    echo '
    <!--- COURSES BUTTON WITH DROP-DOWN--->
    <div class = "topNavOption" onclick="menuItemSelected(\'courses\')" id="courses">
        <div class="dropDownMenu">

            <!--- COURSES BUTTON--->
            <a class="dropDownButton buttonLabel" onclick="toggleDropDown(\'courses_dropdown\')">
                <i class="fa fa-book" aria-hidden="true"></i>
                COURSES
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
    
            <!--- DROP-DOWN--->
            <div class="dropDown_contents" style="display: none;" id="courses_dropdown">';
            require "courseDropDown.php";
            echo'
            </div>
        </div>
    </div>';
}

/**
 * VISIBLE TO ADMIN AND SYSOP
 */
if (in_array("admin", $permissions) || in_array("sys-operator", $permissions)) {
    echo '
    <!--- ADMIN BUTTON--- (FOR ADMIN AND SYSOP ONLY)-->
    <div class="topNavOption" onclick="menuItemSelected(\'admin\')" id="admin">
        <i class="fa fa-sliders" aria-hidden="true"></i><a class = "buttonLabel" id="adminButton"> ADMIN</a>
    </div>';
}
/**
 * VISIBLE TO SYSOP
 */
if (in_array("sys-operator", $permissions)) {
    echo 
    '<!--- SYSTEM BUTTON WITH DROP-DOWN--- (FOR SYSOP ONLY)-->
    <div class = "topNavOption" onclick="menuItemSelected(\'system\')" id="system">
        <div class="dropDownMenu">
            <!--- SYSTEM BUTTON--->
            <a class="dropDownButton buttonLabel" onclick="toggleDropDown(\'system_dropdown\')">
                <i class="fa fa-cog" aria-hidden="true"></i>
                SYSTEM
                <i class="fa fa-angle-down" aria-hidden="true"></i>
            </a>
            <!--- DROP-DOWN--->
            <div class="dropDown_contents" style="display: none;"  id="system_dropdown">
                <a href="#"> <i class="fa fa-users" aria-hidden="true"></i> Manage users</a>
                <a href="../sysop_tasks/importProf.html"><i class="fa fa-user-plus" aria-hidden="true"></i> Import profs/courses</a>
               
                <a href="../sysop_tasks/manage_users.html"><i class="fa fa-users" aria-hidden="true"></i> Manage users</a>
                <a href="#"><i class="fa fa-user-plus" aria-hidden="true"></i> Import professor</a>
                <a href="#"><i class="fa fa-upload" aria-hidden="true"></i> Import course</a>
            </div>
        </div>
    </div>';
}


// also visible to everyone
echo
'<!--- HAMBURGER BUTTON to toggle showing menu options--- (PHONE MODE ONLY)-->
<a  class="hamburger" onclick="showTopNavOptions()" >
    <i class="fa fa-bars" style = "color: white;"></i>
</a>';
?>
