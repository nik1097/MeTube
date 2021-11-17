<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName==""){
        $mediaGrid= new MediaGrid($con);
        echo $mediaGrid->create(null, "All Media", $loggedInUserName);
    }
    else{
        $mediaGrid= new MediaGrid($con);
        echo $mediaGrid->create(null, "Recommended", $loggedInUserName);
        echo $mediaGrid->create(null, "Shared Media", $loggedInUserName);
    }
    ?>
</div>
    