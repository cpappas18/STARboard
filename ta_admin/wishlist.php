<?php
$db_wishlist = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$stmt_wish = $db_wishlist->prepare("SELECT * FROM WishList WHERE term_month_year like '%".$TA_term."%' AND student_id= ".$TA_id."");
$wished = $stmt_wish->execute();



// find if ta is on any wishlist for this term


echo "<h3 class='red-detail'>Wishlist Status</h3>";
    echo "<ul>";
while ($wish = $wished->fetchArray(SQLITE3_ASSOC)){

    
    echo "<li>Professor "  .$wish['prof_name']." requested for ".$wish['course_num']."</li>";

}
echo "</ul>";

