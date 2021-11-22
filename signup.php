<?php 
require_once("files/connection.php"); 
require_once("files/Classes/UserAccount.php");
require_once("files/Classes/StatusMessage.php");

$userAccount = new UserAccount($con);
if(isset($_POST["signupButton"])){

    $email = trim($_POST["email"]);
    $userName = trim($_POST["username"]);
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmpassword"];

    $resultKey=$userAccount->register($email,$password,$confirmPassword,$userName);

    if($resultKey){
        echo "Success";
        header("location:index.php");
    }
    else{
        echo "failure";
    }
    echo $email;
    echo $password;
    echo $confirmPassword;
}

?>
<!DOCTYPE html>

<html lang="en" dir="ltr">
  <head>
    <meta charset="UTF-8">
    
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>

	<body>     
	  <div class="container">
	    <div class="title">Join MeTube</div>
	    <h4>or <a href="login.php">sign in!</a></h4>  
	    <div class="content">
	    
	    <form action="signup.php" method="POST">
	        <div class="user-details">
	            <div class="input-box">
	                <input type="text" name="username" placeholder="Enter username" required>
	                <?php echo $userAccount->displayError(StatusMessage::$userNameUniqueError); ?>
	            </div>
	            <div class="input-box">
	                <input type="text" name = "email" placeholder="Enter email" required>
	                <?php  echo $userAccount->displayError(StatusMessage::$emailInvalidError); ?>
	                <?php echo $userAccount->displayError(StatusMessage::$emailUniqueError); ?>
	            </div>
	            <div class="input-box">
	                <input type="password" name ="password" placeholder="Enter password" required>
	            </div>
	            <div class="input-box">
	                <input type="password" name = "confirmpassword" placeholder="Confirm password" required>

	                <?php echo $userAccount->displayError(StatusMessage::$passwordMatchError); ?>
	                <?php echo $userAccount->displayError(StatusMessage::$passwordLengthError); ?>
	            </div>
	        </div>

	        <div class="button">
	          <button type="submit" name="signupButton">Register</button>
	        </div>
	    </form>
	    </div>
	  </div>
	</body>
</html>
