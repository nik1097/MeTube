<?php
require_once("connection.php");
require_once("Classes/UserDetails.php");
require_once("Classes/Media.php");
require_once("Classes/MediaGrid.php");
require_once("Classes/MediaItem.php");
?>
<div >
    <div>
        <p  style="text-align: center; font-size: 20px;"><?php echo $mediaTitle ?> </p>
    </div>

    <div style="display:flex; align-items: center; justify-content: flex-end">
        <div style="font-size: 18px"> Media Order by: &nbsp;&nbsp;</div>
        <ul aria-label="Sorted By:" class="nav nav-tabs justify-content-end" id="pills-tab-order" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-Title-tab" data-bs-toggle="tab" data-bs-target="#pills-Title" type="button" role="tab" aria-controls="pills-Title" aria-selected="true">Title</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-Views-tab" data-bs-toggle="tab" data-bs-target="#pills-Views" type="button" role="tab" aria-controls="pills-Views" aria-selected="false">Most Views</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-Upload-tab" data-bs-toggle="tab" data-bs-target="#pills-Upload" type="button" role="tab" aria-controls="pills-Upload" aria-selected="false">Recently Uploads</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-Size-tab" data-bs-toggle="tab" data-bs-target="#pills-Size" type="button" role="tab" aria-controls="pills-Size" aria-selected="false">Size</button>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content" id="pills-tabContent-order">
    <div class="tab-pane fade show active" id="pills-Title" role="tabpanel" aria-labelledby="pills-Title-tab">
        <?php
        $sortby = "title";
        require("MediaContent.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Views" role="tabpanel" aria-labelledby="pills-Views-tab">
        <?php
        $sortby = "views";
        require("MediaContent.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Upload" role="tabpanel" aria-labelledby="pills-Upload-tab">
        <?php
        $sortby = "uploadDate";
        require("MediaContent.php");
        ?>
    </div>
    <div class="tab-pane fade" id="pills-Size" role="tabpanel" aria-labelledby="pills-Size-tab">
        <?php
        $sortby = "mediaSize";
        require("MediaContent.php");
        ?>
    </div>
</div>