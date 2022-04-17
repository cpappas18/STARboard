

<?php 
    /**
     * Displays a report on the screen for all the TA's in the course:
     * TA | Responsibilities | Avg student rating | performane logs | stdent rating comments
     */
    
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    
    //write sql
    $query = $db->prepare('SELECT * FROM assignedProfs');
    
    $result = $query->execute();
    echo "<div class = 'report' >";
    echo "<table id='allTAReportTable'>";
    echo "<tr>";
    echo "<th>Term Month Year</th>";
    echo "<th>Course number</th>";
    echo "<th>Course title</th>";
    echo "<th>Assigned professor</th>";
    echo "</tr>";
    while ($row = $result->fetchArray(SQLITE3_ASSOC))
    {
        
        echo "<tr>";
        //term month year
        echo "<td>". $row['term_month_year'] . "</td>";
        
        //course num
        echo "<td>". $row['course_num'] . "</td>";

        //Course title
        echo "<td>". $row['course_name'] . "</td>";

        //Course title
        echo "<td>". $row['instructor_assigned_name'] . "</td>";

        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    $db->close();
    

?>