
<?php
//large php that will call from all the other phps into large report
$db_pop = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
$stmt = $db_pop->prepare("SELECT * FROM TACohort WHERE term_month_year = '".$TA_term."' AND student_id= ".$TA_id."");
$res = $stmt->execute();


while ($ta = $res->fetchArray(SQLITE3_ASSOC)){
    echo "<h2>"  .     $ta['legal_name']    ."</h2>";
    echo "<div class='col-7'>";
    echo "<div class='report-section'>";
    echo "<p></p>";
    echo "<h3 class='red-detail'>Basic Information</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<td width=50%><p class='field-head'>TA Student ID</p><p>"  .     $ta['student_ID']  ."</p></td>";
    echo "<td><p class='field-head'>TA Email</p><p>"  .     $ta['email']  ."</p></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2'><p class='field-head'>Courses Applied For</p><p>"  .     $ta['courses_applied_for']  ."</p></td>";
    echo "</tr>";
    echo "</table>";
    

   
    echo "</div>";
    echo "</div>";
    echo "<div class='col-5'>";
    echo "<div class='report-section'>";
    require "courses_assigned.php";
    echo "</div>";
    echo "</div>";

    


    echo "<div id='".$divid."' style='display:none'>";
    echo "<hr/>";
    echo "<h2>TA Report</h2>";
    echo "<div class='col-6'>";
    echo "<div class='report-section'>";
    echo "<h3 class='red-detail'>Ta Details</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<td width=50%><p class='field-head'>Degree Level</p><p>"  .     $ta['grad_ugrad']  ."</p></td>";
    echo "<td><p class='field-head'>Supervisor Name</p><p>"  .     $ta['supervisor_name']  ."</p></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td width=50%><p class='field-head'>Location</p><p>"  .     $ta['location']  ."</p></td>";
    echo "<td><p class='field-head'>Phone</p><p>"  .     $ta['phone']  ."</p></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2'><p class='field-head'>Degree</p><p>"  .     $ta['degree']  ."</p></td>";
    echo "</tr>";
    echo "</table>";

    echo "</div>";
    echo "<div class='report-section'>";
    require "wishlist.php";
    echo "</div>";
    echo "<div class='report-section'>";
    require "average_rating.php";
    echo "</div>";
    echo "<div class='report-section' style='text-align:center'>";
    require "student_comments.php";
    echo "</div>";
    echo "</div>";
    

    echo "<div class='col-6'>";
    echo "<div class='report-section'>";
    echo "<h3 class='red-detail'>Application Details</h3>";
    echo "<table>";
    echo "<tr>";
    echo "<td width=50%><p class='field-head'>Hours Applied For</p><p>"  .     $ta['hours']  ."</p></td>";
    echo "<td><p class='field-head'>Date Applied</p><p>"  .     $ta['date_applied']  ."</p></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td width=50%><p class='field-head'>Priority</p><p>"  .     $ta['priority']  ."</p></td>";
    echo "<td><p class='field-head'>Open to Other Courses</p><p>"  .     $ta['open_to_other_courses']  ."</p></td>";
    echo "</tr>";
    echo "<tr>";
    echo "<td colspan='2'><p class='field-head'>Notes</p><p>"  .     $ta['notes']  ."</p></td>";
    echo "</tr>";
    echo "</table>";
    echo "</div>";
    echo "<div class='report-section'>";
    require "course_history.php";
    echo "</div>";
    echo "<div class='report-section' style='text-align:center'>";
    require "performance_logs.php";
    echo "</div>";
    echo "</div>";

   echo "</div>";

    

}