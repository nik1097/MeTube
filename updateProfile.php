<?php 
    require_once("files/main.php"); 
    require_once("files/connection.php");
    require_once("files/Classes/StatusMessage.php");
    require_once("files/Classes/UserAccount.php");

    $emailResult = false;
    $userAccount = new UserAccount($con);

    if(isset($_POST["UpdateEmail"])){
        $email=trim($_POST["email"]);
        $emailResult=$userAccount->updateEmail($email, $loggedInUserName);
    }
    else if(isset($_POST["UpdatePassword"])){
        $currentPassword=$_POST["currentpassword"];
        $newPassword=$_POST["newpassword"];
        $confirmNewPassword=$_POST["confirmnewpassword"];
        $result=$userAccount->updatePassword($currentPassword,$newPassword,$confirmNewPassword,$loggedInUserName);
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
            <div class="title">Update your profile</div>
            <div class="content">
        
                <form action="updateProfile.php" method="POST">
                    <div class="user-details">
                        <div class="input-box">
                            <input type="text" name="email" placeholder="Enter your new email">
                            <?php  echo $userAccount->displayError(StatusMessage::$emailInvalidError); ?>
                            <?php echo $userAccount->displayError(StatusMessage::$emailUniqueError); ?>
                        </div>
                        <div class="button">
                            <button type="submit" name="UpdateEmail">Update Email</button>
                        </div>
                    </div>
                    
                    <div class="user-details">
                        <div class="input-box">
                            <input type="password" name ="currentpassword" placeholder="Enter old password">
                        </div>
                        <div class="input-box">
                            <input type="password" name ="newpassword" placeholder="Enter new password">
                        </div>

                        <div class="input-box">
                            <input type="password" name = "confirmnewpassword" placeholder="Confirm new password">

                            <?php echo $userAccount->displayError(StatusMessage::$passwordMatchError); ?>
                            <?php echo $userAccount->displayError(StatusMessage::$passwordLengthError); ?>
                        </div>
                        <div class="button">
                            <button type="submit" name="UpdatePassword">Update Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
