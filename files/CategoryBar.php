<?php
require_once("connection.php");
require_once("Classes/UserDetails.php");
require_once("Classes/Media.php");
require_once("Classes/MediaGrid.php");
require_once("Classes/MediaItem.php");
if(isset($_GET["category"])){
    $category = $_GET["category"];
} else {
    $category = "All";
}
?>

<div>
    <div style="display:flex; font-size: 20px; justify-content: flex-start">
        <span style="display:flex">Showing media for <?php echo $category?> Category: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
        <div>
            <a class="btn btn-primary" href='index.php?page=Home'>All</a>
            <a class="btn btn-primary" href='index.php?page=Home&category=Human'>Human</a>
            <a class="btn btn-primary" href='index.php?page=Home&category=Animal'>Animal</a>
            <a class="btn btn-primary" href='index.php?page=Home&category=Sports'>Sports</a>
            <a class="btn btn-primary" href='index.php?page=Home&category=Other'>Other</a>
        </div>
    </div>
</div>

<?php
require("MediaOrder.php");
?>
