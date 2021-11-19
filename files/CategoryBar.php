<?php
require_once("connection.php");
require_once("Classes/UserDetails.php");
require_once("Classes/Media.php");
require_once("Classes/MediaGrid.php");
require_once("Classes/MediaItem.php");
?>


<ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="index.php">
            <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#All" type="button" role="tab" aria-controls="All" aria-selected="true">All</button>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="watch.php?Id=2">
            <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#Human" type="button" role="tab" aria-controls="Human" aria-selected="false" >Human</button>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Animal" type="button" role="tab" aria-controls="Animal" aria-selected="false">Animal</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Sports" type="button" role="tab" aria-controls="Sports" aria-selected="false">Sports</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#Other" type="button" role="tab" aria-controls="Other" aria-selected="false">Other</button>
    </li>
</ul>
