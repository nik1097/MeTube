<?php

class MediaPlayer{
    private $media;
    public function __construct($media){
        $this->media = $media;
    }

    public function create(){
        $filePath= $this->media->getFilepath();
        if (strpos($filePath, '.mp4') !== false) {
            return "
            <video class='videoPlayer' controls  >
                <source src='$filePath' type='video/mp4'>
                Browser does not support video
            </video>";
        }
        else{
            return "
            <img src='$filePath' alt='Unable to display media'>
            ";
        }

    }
}



?>