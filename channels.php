<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName!=""){
        $videoGrid= new VideoGrid($con);
        echo $videoGrid->create(null, "My Channel", false, $loggedInUserName);
    }
    ?>
</div>