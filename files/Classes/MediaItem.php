<?php
class MediaItem{
    private $media;
    public function __construct($media){
        $this->media= $media;
    }

    public function create(){
        $thumbnail_path = $this->media->getThumbnailpath();
        $thumbnail="<div class='thumbnail'>
                    <img src='$thumbnail_path'></div>";
        $details= $this->createDetails();
        $url= "watch.php?Id=" . $this->media->getId();
        
        return "<a href='$url'>
                    <div class='videoGridItem'>
                   $thumbnail 
                    $details
                    </div>
                </a>";
    }
    private function createDetails(){
        $title = $this->media->getTitle();
        $userName = $this->media->getUploadedBy();
        $views = $this->media->getViews();
        //$description = $this->media->getDescription();
        $keywords = $this->media->getKeywords();
        $uploaddate = $this->media->getUploadDate();
        $size = $this->media->getMediasize();
        return "<div class='details'>
                    <h3 class='title'>$title</h3>
                    <span class='username'>$userName</span>
                    <span class='keywords'>$keywords</span>
                    <div class='stats'>
                        <span class='viewCount'>$views Views </span>
                        <span class='uploaddate'>$uploaddate</span>
                    </div>
                    <span class='description'>$size</span>
                </div>";
    }
} 
?>