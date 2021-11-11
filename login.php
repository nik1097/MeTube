<?php 
require_once("commonFiles/config.php");
require_once("files/Classes/UserAccount.php");
require_once("files/Classes/StatusMessage.php");

$userAccount =new UserAccount($con);
if(isset($_POST["loginButton"])){
    $userName=trim($_POST["username"]);
    $password=$_POST["password"];
    $resultKey=$userAccount->login($userName,$password);
    if($resultKey){
        echo "Success";
        $_SESSION["loggedinUser"] = $userName;
        header("location:index.php");
    }
    else{
        echo "failure";
    }
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
        <div id="logo"> 
            <img src='Logo.png'> 
        </div>     
        <div class="container">
            <div class="title">Login to MeTube</div>
            <h4>or <a href="signup.php">sign up!</a></h4>  
            <div class="content">
                <form action="login.php" method="POST">
                    <div class="user-details">
                        <div class="input-box">
                            <input type="text" name="username" placeholder="Enter username" required>
                        </div>
                        <div class="input-box">
                            <input type="password" name="password" placeholder="Enter password" required>
                            <?php echo $userAccount->displayError(StatusMessage::$loginFailed); ?>
                        </div>
        
                    <div class="button">
                        <button type="submit" name="loginButton">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>