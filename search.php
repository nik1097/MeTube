<?php require_once("files/main.php") ?>
<?php
    if(!isset($_GET["page"])){
        //$keywords = $_GET["term"];
        header("location:search.php?page=Search&keywords=".$_GET["keywords"]);
    }
    $mediaTitle = "Search results for '".$_GET["keywords"]."'";
    if(isset($_GET["size"])){
        $size = $_GET["size"];
    } else {
        $size = "";
    }
?>


<div>
    <div style="display:flex; font-size: 20px; justify-content: center">
        <span style="display:flex; text-align: center">Refined Search by size <?php echo $_GET["size"].":" ?> &nbsp; &nbsp; &nbsp;</span>
        <div>
            <a class="btn btn-primary" href='search.php?page=Search&keywords=<?php echo $_GET["keywords"]?>&size=0-100K'>0-100K</a>
            <a class="btn btn-primary" href='search.php?page=Search&keywords=<?php echo $_GET["keywords"]?>&size=100K-1000K'>100K-1000K</a>
            <a class="btn btn-primary" href='search.php?page=Search&keywords=<?php echo $_GET["keywords"]?>&size=>1000K'>>1000K </a>
        </div>
    </div>
</div>


<?php require_once("files/MediaOrder.php") ?>
