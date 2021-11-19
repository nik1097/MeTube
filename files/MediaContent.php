<?php
    $page = "";
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }

    $category = "";
    if(isset($_GET["category"])){
        $category = $_GET["category"];
    }

    $sortby = "";
    if(isset($_GET["sortby"])){
        $sortby = $_GET["sortby"];
    }

    $keywords = "";
    if(isset($_GET["keywords"])){
        $keywords = $_GET["keywords"];
    }

?>

<div class='videoSection'>

    <?php
//    if($loggedInUserName==""){
//        $mediaGrid= new MediaGrid($con);
//        echo $mediaGrid->create(null, "All Media", $loggedInUserName);
//    }
//    else{
        $mediaGrid= new MediaGrid($con);
        echo $mediaGrid->create($page, $category, $keywords, $sortby, $loggedInUserName);
    //}
    ?>
</div>