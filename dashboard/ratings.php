<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getTerms() {
    // get all unique terms (month and year) from the database
    $db = new SQLite3("../STARboard_G.db", SQLITE3_OPEN_READWRITE);
    $db->exec('BEGIN');
    $result = $db->query('SELECT DISTINCT "term_month_year" FROM "assign"');
    $db->exec('COMMIT');

    $terms = [];

    while ($term = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($terms, reset($term));
    }

    // send response back to front end
    echo json_encode($terms);

    $db->close();
}


function getCoursesInTerm($term) {
    // get all unique courses from the database
    $db = new SQLite3("../STARboard_G.db", SQLITE3_OPEN_READWRITE);
    $query = $db->prepare('SELECT DISTINCT "course_num" FROM "assign" WHERE "term_month_year" = :term');
    $query->bindValue(':term', $term);
    $result = $query->execute();

    $courses = [];

    while ($course = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($courses, reset($course));
    }

    // send response back to front end
    echo json_encode($courses);

    $db->close();
}


function getTAs($term, $course) {
    // get all unique TAs from the database
    $db = new SQLite3("../STARboard_G.db", SQLITE3_OPEN_READWRITE);
    $query = $db->prepare('SELECT DISTINCT "TA_name" FROM "assign" WHERE "term_month_year" = :term and "course_num" = :course');
    $query->bindValue(':term', $term);
    $query->bindValue(':course', $course);
    $result = $query->execute();

    $TAs = [];

    while ($TA = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($TAs, reset($TA));
    }

    // send response back to front end
    echo json_encode($TAs);

    $db->close();
}

// called function depends on the AJAX request parameter
if (strcmp($_GET["action"], "getTerms")==0) {
    getTerms();
} else if (strcmp($_GET["action"], "getCourses")==0) {
    $term = $_GET["term"];
    getCoursesInTerm($term);
} else if (strcmp($_GET["action"], "getTAs")==0) {
    $term = $_GET["term"];
    $course = $_GET["course"];
    getTAs($term, $course);
}


?>