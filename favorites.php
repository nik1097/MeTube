<?php require_once("files/main.php") ?>

<div class='videoSection'>
    <?php
    if($loggedInUserName!=""){
        $mediaGrid= new MediaGrid($con);
       echo $mediaGrid->create('Favorite', "", "","", $loggedInUserName);
    }
    ?>
</div>