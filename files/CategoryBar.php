<?php
require_once("connection.php");
require_once("Classes/UserDetails.php");
require_once("Classes/Media.php");
require_once("Classes/MediaGrid.php");
require_once("Classes/MediaItem.php");
?>

<ul class="nav nav-pills mb-3" id="pills-tab-category" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="pills-All-tab" data-bs-toggle="pill" data-bs-target="#pills-All" type="button" role="tab" aria-controls="pills-All" aria-selected="true">All</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-Human-tab" data-bs-toggle="pill" data-bs-target="#pills-Human" type="button" role="tab" aria-controls="pills-Human" aria-selected="false">Human</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-Animal-tab" data-bs-toggle="pill" data-bs-target="#pills-Animal" type="button" role="tab" aria-controls="pills-Animal" aria-selected="false">Animal</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-Sports-tab" data-bs-toggle="pill" data-bs-target="#pills-Sports" type="button" role="tab" aria-controls="pills-Sports" aria-selected="false">Sports</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pills-Other-tab" data-bs-toggle="pill" data-bs-target="#pills-Other" type="button" role="tab" aria-controls="pills-Other" aria-selected="false">Other</button>
    </li>
</ul>
<?php
//require("MediaOrder.php");
//?>
<div class="tab-content" id="pills-tabContent-category">
    <div class="tab-pane fade show active" id="pills-All" role="tabpanel" aria-labelledby="pills-All-tab">
        <?php
            $category = "";
            require("MediaOrder.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Human" role="tabpanel" aria-labelledby="pills-Human-tab">
        <?php
            $category = "Human";
            require("MediaOrder.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Animal" role="tabpanel" aria-labelledby="pills-Animal-tab">
        <?php
            $category = "Animal";
            require("MediaOrder.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Sports" role="tabpanel" aria-labelledby="pills-Sports-tab">
        <?php
            $category = "Sports";
           require("MediaOrder.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Other" role="tabpanel" aria-labelledby="pills-Other-tab">
        <?php
            $category = "Other";
        require("MediaOrder.php");
        ?>
    </div>
</div>

<!--<ul class="nav nav-tabs" id="pills-tab-order" role="tablist">-->
<!--    <li class="nav-item" role="presentation">-->
<!--        <button class="nav-link active" id="pills-Title-tab" data-bs-toggle="pill" data-bs-target="#pills-Title" type="button" role="tab" aria-controls="pills-Title" aria-selected="true">Title</button>-->
<!--    </li>-->
<!--    <li class="nav-item" role="presentation">-->
<!--        <button class="nav-link" id="pills-Views-tab" data-bs-toggle="pill" data-bs-target="#pills-Views" type="button" role="tab" aria-controls="pills-Views" aria-selected="false">Most Views</button>-->
<!--    </li>-->
<!--    <li class="nav-item" role="presentation">-->
<!--        <button class="nav-link" id="pills-Upload-tab" data-bs-toggle="pill" data-bs-target="#pills-Upload" type="button" role="tab" aria-controls="pills-Upload" aria-selected="false">Recently Uploads</button>-->
<!--    </li>-->
<!--    <li class="nav-item" role="presentation">-->
<!--        <button class="nav-link" id="pills-Size-tab" data-bs-toggle="pill" data-bs-target="#pills-Size" type="button" role="tab" aria-controls="pills-Size" aria-selected="false">Size</button>-->
<!--    </li>-->
<!--</ul>-->
<!--<div class="tab-content" id="pills-tabContent-order">-->
<!--    <div class="tab-pane fade show active" id="pills-Title" role="tabpanel" aria-labelledby="pills-Title-tab">-->
<!--        --><?php
//        $sortby = "title";
//        require("MediaContent.php");
//        ?>
<!--    </div>-->
<!--    <div class="tab-pane fade" id="pills-Views" role="tabpanel" aria-labelledby="pills-Views-tab">-->
<!--        --><?php
//        $sortby = "views";
//        require("MediaContent.php");
//        ?>
<!--    </div>-->
<!--    <div class="tab-pane fade" id="pills-Upload" role="tabpanel" aria-labelledby="pills-Upload-tab">-->
<!--        --><?php
//        $sortby = "uploadDate";
//        require("MediaContent.php");
//        ?>
<!--    </div>-->
<!--    <div class="tab-pane fade" id="pills-Size" role="tabpanel" aria-labelledby="pills-Size-tab">-->
<!--        --><?php
//        $sortby = "mediaSize";
//        require("MediaContent.php");
//        ?>
<!--    </div>-->
<!--</div>-->