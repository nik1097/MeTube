<?php 
    require_once("files/main.php");

    $playlistname =  $_POST['playlistname'];
    $vidId = $_POST['playlistButton'];
    header("Refresh: 1;URL=watch.php?Id=$vidId");
    $checkquery = $con->prepare("SELECT * from playlist inner join playlist_media on name = playlistName where userName = '$loggedInUserName' and videoId = '$vidId'");
    $checkquery->execute();

    if($checkquery->rowCount() == 0){
    	$query = $con->prepare("INSERT INTO playlist_media(playlistName, videoId) values ('$playlistname', '$vidId')");
    	$query->execute();
    	echo "Video added to playlist ". $playlistname;
    }
    else{
    	echo "Video already exists in playlist";
    }

?>
