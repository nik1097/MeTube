<?php
class FriendsClass{
	private $con;
    public function __construct($con){
        $this->con=$con;
    }

    public function getAllUserstoFriend($userName){
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
                        <th>Relationship</th>
                        <th>Block</th>
                        </tr>
                    
                        </thead>
                        <tbody>";

                while($row=$query->fetch(PDO::FETCH_ASSOC)){
                    $userName=$row["userName"];
                    $email=$row["emailId"];
                    $html.=  "<tr><td>$userName</td>";
                    $html.=  "<td>$email</td>";
                    
                    $html.=  "<td> <div style='padding-bottom:10px;'>
                    <form action='friend.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='friendsButton' value='$userName'>Friends</button>
                    </form>
                    <form action='friend.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='familyButton' value='$userName'>Family</button>
                    </form>
                    <form action='friend.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='favoriteButton' value='$userName'>Favourite</button>
                    </form>
                    </div></td>

                    <td><div style='padding-bottom:10px;'>
                    <form action='friend.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='blockButton' value='$userName'>Block</button>
                    </form>
                    <form action='friend.php' method='POST'>
                    <button type='submit' class='btn btn-primary' name='unblockButton' value='$userName'>Unblock</button>
                    </form>
                    </div></td></tr>
                    ";
                 }
                 $html.="</tbody>
                 </table></div>
                </div>";
                 return $html;
            }
        }
        catch(Exception $e){
            echo"Some Error Occured: ".$e->getMessage();
        }
    }
    public function makefriends($userName, $friendName, $relationType){
    	echo "Relationship status with " . $friendName . " changed to ". $relationType;

    	$checkquery = $this->con->prepare("SELECT * from contact where userName='$userName' and contactUserName = '$friendName'");
    	$checkquery->execute();

    	if($checkquery->rowCount()==0){
	    	$query = $this->con->prepare("INSERT INTO contact(userName, contactUserName, contactType) VALUES('$userName', '$friendName', '$relationType')");
	        $query->execute();
	    }
	    else{
	    	$query = $this->con->prepare("UPDATE contact SET contactType = '$relationType' where userName='$userName' and contactUserName = '$friendName'");
	        $query->execute();
    	}
    }
    public function blockfriends($userName, $friendName, $relationType){
        echo "You have " .$relationType. " user " . $friendName;

        $checkquery = $this->con->prepare("SELECT * from contact where userName='$userName' and contactUserName = '$friendName'");
        $checkquery->execute();

        if($checkquery->rowCount()!=0){
            $query = $this->con->prepare("UPDATE contact SET status = '$relationType' where userName='$userName' and contactUserName = '$friendName'");
            $query->execute();
        }
        else{
            $query = $this->con->prepare("INSERT INTO contact(userName, contactUserName, status) VALUES('$userName', '$friendName', '$relationType')");
            $query->execute();
        }
    }

}