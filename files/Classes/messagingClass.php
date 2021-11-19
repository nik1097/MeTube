<?php  
class MessagingClass{
    private $con;
    public function __construct($con){
        $this->con=$con;
    }
    public function getAllUserstoMessage($userName){
        try{
            $query = $this->con->prepare("SELECT * from users where userName != '$userName'");
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
                        <th>Message</th>
                        <th>Send Message</th>
                        </tr>
                    
                        </thead>
                        <tbody><tr><td>";

                $html.= "<div style='padding-bottom:10px;'>
                
                <form action='message.php' method='POST'>
                <select name='recipient'>";
                while($row= $query->fetch(PDO::FETCH_ASSOC)){
                    $html.= "<option value='" . $row['userName'] . "'>" . $row['userName'] . "</option>";
                }
                $html.= "</select></td>
                <td><textarea rows = '7' cols = '90' name = 'msg' placeholder='enter your message'></textarea></td>
                <td><button type='submit' class='btn btn-primary' name='messageButton' value='$userName'>Message</button></td>
                </form>
                </div></td></tr>";

                $html.="</tbody>
                </table></div>
                <form action='message.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='inbox' value='$userName'>Inbox</button>
                    <button type='submit' class='btn btn-primary' name='sent' value='$userName'>Sent</button>
                    </form>
                
                </div>";
                 return $html;
            }
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }
    public function sendMessage($loggedInUserName, $recipient, $msg){
        echo "Message has been delivered";
        $query = $this->con->prepare("INSERT INTO messages(sentBy, sentTo, message) VALUES('$loggedInUserName', '$recipient', '$msg')");
        $query->execute();
    }

    public function viewMessage($loggedInUserName, $var){
        if ($var == 'Sent By'){
            $query = $this->con->prepare("select * from messages where sentTo = '$loggedInUserName'");
        }
        else if($var == 'Sent To'){
            $query = $this->con->prepare("select * from messages where sentBy = '$loggedInUserName'");
        }
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

