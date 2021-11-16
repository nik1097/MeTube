<?php
class VideoItem{

    private $video;

    public function __construct($video){
        $this->video= $video;
    }

    public function create(){
        $thumbnail_path = '/MeTube/'.$this->video->getThumbnailpath();

        $thumbnail="<div class='thumbnail'>
                    <img src=$thumbnail_path></div>";
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
        $description = $this->video->getDescription();
        $uploaddate = $this->video->getUploadDate();
        
        return "<div class='details'>
                <h3 class='title'>$title</h3>
                <span class='username'>$userName</span>
                <span class='description'>$description</span>
                <div class='stats'>
                    <span class='viewCount'>$views Views </span>
                    <span class='uploaddate'>$uploaddate</span>
                </div>
                </div>";
    }
} 
?>