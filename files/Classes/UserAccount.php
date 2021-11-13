<?php
class UserAccount{
    private $con;
    private $errorMessages = array();
    private $successMessages = array();

    public function __construct($con){
        $this->con = $con;
    }

    public function register($email,$password,$confirmPassword,$userName){
        try{
            $this->validateUserName($userName);
            $this->validateEmail($email);
            $this->validatePassword($password,$confirmPassword);
            if(empty($this->errorMessages)){
                $query = $this->con->prepare("INSERT INTO users(emailId,password,userName) VALUES('$email','$password','$userName')");
                return $query->execute();
            }
            else{
                return false;
            }
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }

    public function login($userName, $password){
        try{
            $query = $this->con->prepare("SELECT * from users where userName='$userName' and password='$password'");
            $query->execute();
            if($query->rowCount() ==1){
                return true;
            }
            else{
                array_push($this->errorMessages,StatusMessage::$loginFailed);
                return false;
            }
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
        
    }

    private function validateEmail($email){
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            array_push($this->errorMessages,StatusMessage::$emailInvalidError);
            return;
        }
        $query = $this->con->prepare("SELECT emailId from users where emailId='$email'");
        $query->execute();
        if($query->rowCount()!= 0){
            array_push($this->errorMessages,StatusMessage::$emailUniqueError);
        }
    }

    private function validateUserName($userName){   
        $query = $this->con->prepare("SELECT userName from users where userName='$userName'");
        $query->execute();
        if($query->rowCount()!= 0){
            array_push($this->errorMessages,StatusMessage::$userNameUniqueError);
        }
    }

    private function validatePassword($password,$confirmPassword){
        if($password != $confirmPassword){
            array_push($this->errorMessages,StatusMessage::$passwordMatchError);
            return;
        }
        if(strlen($password)<6){
            array_push($this->errorMessages,StatusMessage::$passwordLengthError);
        }   
    }
    public function updateEmail($newEmail,$userName){ 
        try{
            $this->validateEmail($newEmail);
            if(empty($this->errorMessages)){
                $query = $this->con->prepare("UPDATE users SET emailId ='$newEmail' where userName='$userName'");
                $result= $query->execute();
                if($result){
                    array_push($this->successMessages,StatusMessage::$SuccessfulUpdateEmail);
                    return $result;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
                }
            }
        catch(Exception $e){
            echo"Error: ".$e->getMessage();
        }
    }

    public function updatePassword($currentPassword,$newPassword,$confirmNewPassword,$userName){
        try{
            $query = $this->con->prepare("SELECT password from users where userName='$userName'");
            $query->execute();
            $passwordData = $query->fetch(PDO::FETCH_ASSOC);
            $originalPassword=$passwordData["password"];
            if($currentPassword!=$originalPassword){
                array_push($this->errorMessages,StatusMessage::$passwordMismatchError);
                return false;
            }
            else{
                $this->validatePassword($newPassword,$confirmNewPassword);
            }

            if(empty($this->errorMessages)){
                $query1 = $this->con->prepare("UPDATE users SET password = '$newPassword' where userName='$userName'");
                $result= $query1->execute();   

                if($result){
                    array_push($this->successMessages,StatusMessage::$SuccessfulUpdatePassword);
                    return $result;
                }
                else{
                    return false;
                }
            }
            else{
                return false;
            }
            
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }
    public function displayError($error){
        if(in_array($error,$this->errorMessages)){
            return "<span>$error</span>";
        }
    }

    public function displaySuccess($success){
        if(in_array($success,$this->successMessages)){
            return "<span>$success</span>";
        }
    }

    public function emptySuccessMessages(){
        $this->successMessages =array();
    }
}
?>