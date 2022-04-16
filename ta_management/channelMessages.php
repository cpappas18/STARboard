<style>
.messageBubble
{
  background-color: #D2DDEBAB;
  color: black;
  border-radius: 15px;
  padding: 10px;
  margin: 10px;
  font-size: 15px;
  margin-right: 25px;

}

.messageInfo
{
  margin-top: 10px;  
  padding-left: 20px;
  font-size: 11px;
  color: #585d66;
}

.selfMessageBubble
{
  background-color: lightgrey;
  color: black;
  border-radius: 15px;
  padding: 10px;
  margin: 10px;
  margin-left: 25px;
  font-size: 15px;

}

.selfMessageInfo
{
  margin-top: 10px;  
  text-align: right;
  padding-right: 30px;
  font-size: 11px;
  color: #585d66;
  
}


</style>

<?php 
    //define the PDO object and tell it about the db
    $db = new SQLite3("../STARboard.db", SQLITE3_OPEN_READWRITE);
    $course = $_POST['course'];
    $username = $_POST['username'];
    //write sql
    $query = $db->prepare('SELECT * FROM "messages" WHERE "course" = :course');
    $query->bindValue(':course', $course);
    $result = $query->execute();
    //run sql
    
    while ($message = $result->fetchArray(SQLITE3_ASSOC))
    {
      //then display self message
      if ($message['username'] != $username)
      {
        echo "<div class='messageInfo'>" . $message['username'] . "  " . $message['dateAndTime'] . " : </div>";
        echo "<div class='messageBubble'> " . $message['message'] . "</div>";
      }
      else
      {
        echo "<div class='selfMessageInfo'> Me: " . $message['dateAndTime'] . " : </div>";
        echo "<div class='selfMessageBubble'> " . $message['message'] . "</div>";
      }
      
      
    }
    $db->close();


?>