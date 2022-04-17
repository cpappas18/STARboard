<?php
$db_rating = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);

$stmt_rating = $db_rating->prepare("SELECT rating FROM ratings WHERE student_ID= ".$TA_id."");
$ratings = $stmt_rating->execute();

//find the average rating for the ta from within the ratings table
echo "<h3 class='red-detail'>Average Student Rating</h3>";
    
$sum_rate =0;
$num_rate =0;
while ($rating = $ratings->fetchArray(SQLITE3_ASSOC)){
    $num_rate ++;
    $sum_rate = $sum_rate + $rating['rating'];
}
if($num_rate ==0){
    echo "<p class='ratings'>No ratings yet</p>";
}
else{
    $avg_score = $sum_rate / $num_rate;
    $avg_score_rounded = number_format((float)$avg_score, 1, '.', '');
    echo "<p class='ratings'>".$avg_score_rounded." / 5.0</p>";
}

