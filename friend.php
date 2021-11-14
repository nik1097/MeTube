<?php 
require_once("files/main.php"); 
require_once("files/Classes/friendsClass.php");
require_once("files/Classes/StatusMessage.php");
?>

<div class='container' style='padding-top:10px;'>
<div class='jumbotron'>
    <h3 class="text-dark display-3 text-center">Contacts</h3>

    <?php
    //$contactBean = new ContactBean();
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
    		$resultKey = $friendsClass->makefriends($loggedInUserName, $_POST["friendsButton"],'Friend');
    	}
    	else if(isset($_POST["familyButton"])){
    		$resultKey = $friendsClass->makefriends($loggedInUserName, $_POST["familyButton"],'Family');
    	}
    	else if(isset($_POST["favoriteButton"])){
    		$resultKey = $friendsClass->makefriends($loggedInUserName, $_POST["favoriteButton"],'Fav');
    	}
   	?>

</div>
</div>