<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require("tickets.php");
$ticket = $_POST['ticket'];
$permissions = get_ticket_permissions($ticket);
$nameOfUser = getNameFromTicket($ticket);
// display sections based on permissions
echo '<div class="welcomeMessage">
                Welcome, '. $nameOfUser .
            '</div>';
// visible to everyone: ratings
echo '<!--- Rate a TA --->
<div class = "section">
    <div class="title">
        <i class="fa fa-thumbs-up" aria-hidden="true" style="color: rgb(167, 37, 48)"></i>
        Ratings
    </div>
    <div id="ratingStats">
        
    </div>
    <ul>
        <li>
            Submit anonymous ratings and feedback to TAs
        </li>
        <li>
            Help influence future TA hiring decisions
        </li>
    </ul>
    <a class="option" onclick="menuItemSelected(\'ratings\')" href="../ratings/ratings.html">
        Rate a TA
    </a>
</div>';

//courses section: TA and profs
if (in_array("professor", $permissions) || in_array("sys-operator", $permissions) 
    || in_array("admin", $permissions) || in_array("TA", $permissions)) {
    echo '<div class = "section">
    <div class="title">
        <i class="fa fa-book" aria-hidden="true" style="color: rgb(167, 37, 48)"></i>
        Manage courses
    </div>
    <ul>
        <li>
            Use your course message channel
        </li>
        <li>
            TA office hours and responsibilities
        </li>
        <li>
            Profs: manage TA wishlist, peformance, and reports
        </li>
    </ul>';
    $styleDivs = true;
    require("../dashboard/courseDropDown.php");
    
echo '</div>';
}

//Only admin and sysop- admin section TODO: add links
if (in_array("admin", $permissions) || in_array("sys-operator", $permissions)) {
    echo '<div class = "section">
    <div class="title">
        <i class="fa fa-sliders" aria-hidden="true" style="color: rgb(167, 37, 48)"></i>
        Administration
    </div>
    <ul>
        <li>
            Search TAs by course or name
        </li>
        <li>
            Add/remove TAs from courses
        </li>
        <li>
            View TA history
        </li>
    </ul>
    <a class="option" onclick="menuItemSelected(\'admin\')" href="#">
        Admin
    </a>
</div>';
}

//sys op section
if (in_array("sys-operator", $permissions)) {
    echo '<div class = "section">
    <div class="title">
        <i class="fa fa-cog" aria-hidden="true" style="color: rgb(167, 37, 48)"></i>
        System operator
    </div>
    <ul>
        <li>
            Manage user accounts
        </li>
        <li>
            Add or remove professors or courses
        </li>
        <li>
            Manage system manually or using a CSV file
        </li>
    </ul>
    
    <a class="option" onclick="menuItemSelected(\'system\')">
        Manage users
    </a>
    <a class="option" onclick="menuItemSelected(\'system\')" href="../sysop_tasks/importProf.html">
        Quick import prof/course
    </a>
</div>';
}


/**
     * Returns the name associated with $ticket in the form "firstname lastname"
     */
    function getNameFromTicket($ticket) {

        $db_name = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    
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
        return $name;
    }

?>
