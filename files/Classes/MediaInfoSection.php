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
        $views= $this->media->getViews(); 
        
        return "<div class='videoInfo'>
            <h1>$title</h1>
            <div class='BottomSection'>
                <span class='viewCount'>$views Views</span>
            </div>
        
        </div>";
    }
}
?>
