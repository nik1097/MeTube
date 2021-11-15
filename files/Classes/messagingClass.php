<?php  
class MessagingClass{
	private $con;
    public function __construct($con){
        $this->con=$con;
    }
    public function getAllUserstoMessage($userName){
        try{
            $query = $this->con->prepare("select * from users where userName != '$userName'");
            $query->execute();
            if($query->rowCount()== 0){
                return "";
            }
            else{
                $html = "
                <div><div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
                        <thead class='thead-dark'>
                        <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Messaging</th>
                        </tr>
                    
                        </thead>
                        <tbody>";

                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    $userName= $row["userName"];
                    $email= $row["emailId"];
                    $html.=  "<tr><td>$userName</td>";
                    $html.=  "<td>$email</td>";
                    
                    $html.=  "<td> <div style='padding-bottom:10px;'>
                    <form action='message.php' method='POST'>
                    <input type='text' name='msg' placeholder='enter your message'>
                    <button type='submit' class='btn btn-primary' name='messageButton' value='$userName'>Message</button>
                    </form>
                    </div></td></tr>";
                }

                $html.="<form action='message.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='viewMessage' value='$userName'>View Message</button>
                    </form>
                </tbody>
                </table></div>
                </div>";
                 return $html;
            }
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }
    public function sendMessage($loggedInUserName, $recipient, $msg){
        echo "You can view your messages by clicking the view button";
        $query = $this->con->prepare("INSERT INTO messages(sentBy, sentTo, message) VALUES('$loggedInUserName', '$recipient', '$msg')");
        $query->execute();
    }

    public function viewMessage($loggedInUserName){
        $query = $this->con->prepare("select * from messages where sentBy = '$loggedInUserName' or sentTo = '$loggedInUserName'");
        $query->execute();
        if($query->rowCount()== 0){
                return "";
        }
        else{
            $html = "
            <div><div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
                    <thead class='thead-dark'>
                    <tr>
                    <th>Sent By</th>
                    <th>Sent To</th>
                    <th>Messages</th>
                    <th>Sent At</th>
                    </tr>
                    </thead>
                    <tbody>";
            while($row=$query->fetch(PDO::FETCH_ASSOC)){
                $sentBy= $row["sentBy"];
                $sentTo= $row["sentTo"];
                $message= $row["message"];
                $sentAt= $row["sentAt"];

                $html.=  "<tr><td>$sentBy</td>";
                $html.=  "<td>$sentTo</td>";
                $html.=  "<td>$message</td>";  
                $html.=  "<td>$sentAt</td></tr>";   
            }
            echo $html;
        }
    }
}
