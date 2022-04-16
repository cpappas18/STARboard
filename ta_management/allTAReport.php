<style>
 
    .report{
        overflow-x: auto;
    }

    #allTAReportTable td, #allTAReportTable th {
        padding: 8px;
        text-align: center;
        color: #2b2d42;
        
    }
    #allTAReportTable tr:nth-child(even){background-color: #f2f2f2;}

    #allTAReportTable tr:hover {background-color: #ddd;}

    #allTAReportTable th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #8d99ae;
        color: white;
        text-align: center;
        font-weight: 400;
    }
   

    

</style> 

<?php 
    /**
     * Displays a report on the screen for all the TA's in the course:
     * TA | Responsibilities | Avg student rating | performane logs | stdent rating comments
     */
    
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $course = $_POST['course_num'];
    $prof_name = $_POST['prof_name'];
    //write sql
    $query = $db->prepare('SELECT * FROM TAmanagement WHERE "course" = :course');
    $query->bindValue(':course', $course);
    $query->bindValue(':prof_name', $prof_name);
    $result = $query->execute();
    echo "<div class = 'report' >";
    echo "<table id='allTAReportTable'>";
    echo "<tr>";
    echo "<th>TA name</th>";
    echo "<th>Responsibilities</th>";
    echo "<th>Average student rating</th>";
    echo "<th>Performance comments</th>";
    echo "<th>Student comments</th>";
    echo "</tr>";
    while ($ta = $result->fetchArray(SQLITE3_ASSOC))
    {
        $TA_name = $ta['taName'];
        echo "<tr>";
        //TA name
        echo "<td>". $TA_name . "</td>";
        
        //Responsibilites
        echo "<td>". $ta['responsibilities'] . "</td>";

        //Rating
        //Use a php file to get the TA's average rating
        echo "<td>";
        require "averageRating.php";
        echo "</td>";

        //Fetch perform. comments from performance DB
        //Use existing php file to reduce code duplication:
        echo "<td>";
        require "taPerform.php";
        echo "</td>";

        //Fetch student rating comments from ratings DB
        echo "<td>";
        require "studentComments.php";
        echo "</td>";


        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    $db->close();
    

?>