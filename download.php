<?php
    require_once("files/main.php");

    $vidId=$_POST['downloadButton'];
    //$query=$con->prepare("select filepath from videos where id='$vidId'");
    $query=$con->prepare("select file_path from media where media_id='$vidId'");
    $query->execute();

    $filePath=$query->fetchColumn();

    echo "<div class='text-center'><a href='$filePath' download><button class='btn btn-xl btn-primary'>Confirm Download</button></a></div>";

?>