<?php 
	require_once("files/main.php");
	$vidId=(int)$_GET['Id'];
	$checkquery = $con -> prepare("SELECT * from favorites where userName='$loggedInUserName' and videoId = '$vidId'");
	$checkquery -> execute();
	if($checkquery->rowCount()==0){
    	$query = $con -> prepare("INSERT into favorites (videoId, userName) VALUES ('$vidId', '$loggedInUserName')");
        $query->execute();
        header("location:watch.php?Id=$vidId&add=success");
    }
    else{
    	echo"Video is already in favorites";
    }
?>
