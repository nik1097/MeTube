<?php

class MediaInfoSection{
    private $con,$media,$userLoggedInObj;
    public function __construct($con,$media,$userLoggedInObj){
        $this->media= $media;
        $this->con= $con;
        $this->userLoggedInObj= $userLoggedInObj;
    }

    public function create(){
        return $this->createInfo();
    }
    private function createInfo(){
        $title= $this->media->getTitle();
        $uploadTime = $this->media->getUploadDate();
        $keywords = $this->media->getKeywords();
        $views= $this->media->getViews(); 
        
        return "<div class='videoInfo'>
            <h1>$title</h1>
            <span class='keywords'>$keywords</span>
            <div class='BottomSection'>
                <span class='viewCount'>$views Views</span>
            </div>
            <span class='uploadTime'>Upload time: $uploadTime </span>
        </div>";
    }
}
?>
