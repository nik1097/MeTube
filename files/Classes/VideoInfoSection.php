<?php

class VideoInfoSection{
    private $con,$video,$userLoggedInObj;
    public function __construct($con,$video,$userLoggedInObj){
        $this->video= $video;
        $this->con= $con;
        $this->userLoggedInObj= $userLoggedInObj;
    }

    public function create(){
        return $this->createInfo();
    }
    private function createInfo(){
        $title= $this->video->getTitle();
        $views= $this->video->getViews(); 
        
        return "<div class='videoInfo'>
            <h1>$title</h1>
            <div class='BottomSection'>
                <span class='viewCount'>$views Views</span>
            </div>
        
        </div>";
    }
}
?>
