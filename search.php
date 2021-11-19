<?php require_once("files/main.php") ?>
<?php
    if(!isset($_GET["page"])){
        //$keywords = $_GET["term"];
        header("location:search.php?page=Search&keywords=".$_GET["keywords"]);
    }
    $mediaTitle = "Search results for '".$_GET["keywords"]."'"
?>
<?php require_once("files/MediaOrder.php") ?>
