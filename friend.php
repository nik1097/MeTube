<?php 
require_once("files/main.php"); 
require_once("files/Classes/friendsClass.php");
require_once("files/Classes/StatusMessage.php");
?>

<div class='container' style='padding-top:10px;'>
<div class='jumbotron'>
    <h3 class="text-dark display-3 text-center">Contacts</h3>

    <?php
    
    $friendsClass = new FriendsClass($con);
    $result= $friendsClass->getAllUserstoFriend($loggedInUserName);
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
		if(isset($_POST["friendsButton"])){
			$relation = $_POST['relation'];
			$person = $_POST['person'];
    		$resultKey = $friendsClass->makefriends($loggedInUserName,$person, $relation);
    	}
        else if(isset($_POST["blockButton"])){
        	$relation = $_POST['block'];
			$person = $_POST['person'];
            $resultKey = $friendsClass->blockfriends($loggedInUserName, $person, $relation);
        }
   	?>

</div>
</div>