<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName==""){
        $videoGrid= new MediaGrid($con);
        echo $videoGrid->create(null, "Other", true, $loggedInUserName);
    }
    else{
        $videoGrid= new MediaGrid($con);
        echo $videoGrid->create(null, "Recommended", true, $loggedInUserName);
    }
    ?>
</div>