<?php
require_once("files/connection.php");
require_once("files/main.php");

$query = $con->prepare("SELECT * FROM wordcount");
$query->execute();

echo "<div style = 'font-size: 30px; text-align: center;'> 
           Search Cloud:
      </div>";
echo "<div>";
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $keyword = $row['word'];
    $count = $row['search_count'] + 10;
    if ($count > 100) {
        $count = 100;
    }
    echo "<div style = 'font-size:".$count."px'>".$keyword."&nbsp;&nbsp;&nbsp;&nbsp;</div>";
}
echo "</div>";
?>