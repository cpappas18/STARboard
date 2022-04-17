<?php

$db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

function submitFeedback($term, $course, $TA, $rating, $comment) {
    global $db;

    $date = new DateTime(); // date and time of rating
    $date = $date->format('Y-m-d H:i:s'); // convert to SQL date format

    // get the TAs student ID
    $statement = 'SELECT * FROM "TAassignment" WHERE "TA_name" = :TA_name';
    $query = $db->prepare($statement);
    $query->bindValue(':TA_name', $TA);
    $result = $query->execute();
    $user = $result->fetchArray(SQLITE3_ASSOC); 

    if (!$user) {
        die ("There is no TA with this name.");
    }

    $statement = 'INSERT INTO "ratings" VALUES (:course, :term, :TA_name, :rating, :comment, :date, :student_ID)';
    $query = $db->prepare($statement);
    $query->bindValue(':course', $course);
    $query->bindValue(':term', $term);
    $query->bindValue(':TA_name', $TA);
    $query->bindValue(':rating', $rating);
    $query->bindValue(':comment', $comment);
    $query->bindValue(':date', $date);
    $query->bindValue(':student_ID', $user['student_ID']);

    $query->execute();
    $db->close();
}


if (isset($_POST)) {
    if (strcmp($_POST["action"], "submit")==0) {
        $term = $_POST["term"];
        $course = $_POST["course"];
        $TA = $_POST["TA"];
        $rating = $_POST["rating"];
        $comment = $_POST["comment"];

        submitFeedback($term, $course, $TA, $rating, $comment);

        echo "<div style=\"text-align:center; font-size:20px;\">
        <p>Your rating has been submitted successfully!</p><br>
        <button onclick=\"location.reload()\" class=\"style-button\">Rate another TA <i class='bi bi-arrow-return-left'></i></button>
        </div>";

    }
} else {
    die("POST is not set.");
}

?>