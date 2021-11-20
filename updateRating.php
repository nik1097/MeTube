<?php
require_once("files/main.php");
    if (isset($_POST["ratingButton"])){
    	$mediaId = $_POST["ratingButton"];
    	echo $mediaId;
		$ratecheck =  $con->prepare("SELECT * from rating where userName='$loggedInUserName' and mediaId = '$mediaId'");
		$ratecheck->execute();
		$rateme = $_POST['rate'];
   		if ($ratecheck->rowCount() != 0){
   			$updaterating = $con->prepare("UPDATE rating set ratedIndex='$rateme' where userName = '$loggedInUserName' and mediaId = '$mediaId'");
   			$updaterating->execute();
   		}
   		else{
   			$updaterating = $con->prepare("INSERT INTO rating(userName, mediaId, ratedIndex) values ('$loggedInUserName', '$mediaId','$rateme')");
   			$updaterating->execute();
   		}
    }
    header('Location: ' . $_SERVER['HTTP_REFERER']);
?>
