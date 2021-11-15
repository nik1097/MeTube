<?php 
	require_once("files/main.php");
	$vidId=$_POST['favoriteButton'];
	$checkquery = $con -> prepare("SELECT * from favorites where userName='$loggedInUserName' and videoId = '$vidId'");
	$checkquery -> execute();
	if($checkquery->rowCount()==0){
    	$query = $con -> prepare("INSERT into favorites (videoId, userName) VALUES ('$vidId', '$loggedInUserName')");
        $query->execute();
        echo "Video has been added to favorites";
    }
    else{
    	echo"Video is already in favorites";
    }
?>
