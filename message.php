<?php 

require_once("files/main.php"); 
require_once("files/Classes/messagingClass.php");

?>
<div class='container' style='padding-top:10px;'>
<div class='jumbotron'>
    <h3 class="text-dark display-3 text-center">Contacts</h3>

    <?php
    
    $messagingClass = new MessagingClass($con);
    $result= $messagingClass->getAllUserstoMessage($loggedInUserName);
    if($result!="")
    {
        echo $result;
    }
    else{
        echo "<h5 class='text-primary display-5 text-center'>";
        echo StatusMessage::$NoContacts;
        "</h5>";
    }
?>
<?php

	if(isset($_POST["messageButton"])){
        $recipient = $_POST['recipient'];
		$resultKey = $messagingClass->sendMessage($loggedInUserName, $recipient, $_POST["msg"]);
	}
    else if(isset($_POST["inbox"])){
        $var = 'Sent By';
        $resultKey = $messagingClass->viewMessage($loggedInUserName, $var);
    }
    else if(isset($_POST["sent"])){
        $var = 'Sent To';
        $resultKey = $messagingClass->viewMessage($loggedInUserName, $var);
    }

?>
	