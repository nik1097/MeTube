<?php
class VideoItem{

    private $video, $largeMode;

    public function __construct($video, $largeMode){
        $this->video= $video;
        $this->largeMode=$largeMode;
    }

    public function create(){
        $thumbnail="<div class='thumbnail'>
                     <img src='http://unsplash.it/250/150?gravity=center'></div>";
        $details= $this->createDetails();
        $url= "watch.php?Id=" . $this->video->getId();
        
        return "<a href='$url'>
                    <div class='videoGridItem'>
                   $thumbnail 
                    $details
                    </div>
                </a>";
    }
    private function createDetails(){
        $title = $this->video->getTitle();
        $userName = $this->video->getUploadedBy();
        $views = $this->video->getViews();
        #$description = $this->getDescription();
        $uploaddate = $this->video->getUploadDate();
        
        return "<div class='details'>
                <h3 class='title'>$title</h3>
                <span class='username'>$userName</span>
                <div class='stats'>
                    <span class='viewCount'>$views Views </span>
                    <span class='uploaddate'>$uploaddate</span>
                </div>
                </div>";
    }
} 
?>