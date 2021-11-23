<?php
require_once("files/connection.php");
require_once("files/main.php");
$mediaId = (int)$_GET['Id'];
if (isset($_POST["cancel"])) {
    header("location:watch.php?Id=$mediaId");
}

$title = $_POST["title"];
$description = $_POST['description'];
$category = $_POST['category'];
$visibility = $_POST['visibility'];
$keywords = $_POST['keywords'];
$keywords = rtrim($keywords, ',');
$keyword_arr = explode(',', $keywords);

try{

    $query = $con->prepare("UPDATE media
                                 SET title = '$title', description = '$description', category = '$category', privacy = '$visibility', keywords = '$keywords'
                                 WHERE id = '$mediaId'");
    $query->execute();
    //get the media id just added to database
    $query = $con->prepare("DELETE FROM keywords WHERE media_id = '$mediaId'");
    $query->execute();

    if ($keywords != "") {
        for ($i = 0; $i < count($keyword_arr); $i++) {
            $key = $keyword_arr[$i];
            //echo "$key"."<br>";
            $query = $con->prepare("INSERT INTO keywords(keyword, media_id) VALUES('$key', '$mediaId')");
            $query->execute();
        }
    }
}
catch(Exception $e){
    echo"Some Error Occured: ".$e->getMessage();
    header("location:watch.php?Id=$mediaId");
}
echo "Update Success!";
header("Refresh: 2;URL=watch.php?Id=$mediaId");
?>