<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getTerms() {
    $db = new SQLite3("../STARboard_G.db", SQLITE3_OPEN_READWRITE);
    $db->exec('BEGIN');
    $result = $db->query('SELECT DISTINCT "term_month_year" FROM "assign"');
    $db->exec('COMMIT');

    $terms = [];

    while ($term = $result->fetchArray(SQLITE3_ASSOC)) {
        array_push($terms, reset($term));
    }

    echo json_encode($terms);

    $db->close();
}

function getCoursesInTerm($term) {

}

if (strcmp($_POST["action"], "getTerms")==0) {
    getTerms();
} else if (strcmp($_POST["action"], "getCourses")==0) {
    $term = $_POST["term"];
    getCoursesInTerm($term);
}


?>