<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName!=""){
        $videoGrid= new VideoGrid($con);
        echo $videoGrid->create(null, "My Favorites", false, $loggedInUserName);
    }
    ?>
</div>