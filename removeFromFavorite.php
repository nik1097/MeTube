<?php
    require_once("files/main.php");
    $vidId = (int)$_GET['Id'];
    $checkquery = $con->prepare("SELECT * from favorites where userName='$loggedInUserName' and videoId = '$vidId'");
    $checkquery->execute();
    if ($checkquery->rowCount() > 0) {
        $query = $con->prepare("DELETE FROM favorites WHERE userName='$loggedInUserName' and videoId = '$vidId'");
        $query->execute();
        header("location:watch.php?Id=$vidId&delete=success");
    } else {
        echo "Video is already not in favorites";
    }
?>