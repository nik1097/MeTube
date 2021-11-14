<?php

class VideoPlayer{
    private $video;
    public function __construct($video){
        $this->video = $video;
    }

    public function create(){
        $filePath= $this->video->getFilepath();
        return "
        <video class='videoPlayer' controls  >
            <source src='$filePath' type='video/mp4'>
            Browser does not support video
        </video>";

    }
}



?>