<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName==""){
        $videoGrid= new VideoGrid($con);
        echo $videoGrid->create(null, "All Videos", false, $loggedInUserName);
    }
    else{
        $videoGrid= new VideoGrid($con);
        echo $videoGrid->create(null, "Recommended", false, $loggedInUserName);
        echo $videoGrid->create(null, "Shared Videos", false, $loggedInUserName);
    }
    ?>
</div>
    