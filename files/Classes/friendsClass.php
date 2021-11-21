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
                    <th>Relationship</th>
                    <th>Block</th>

                    </tr></thead>
                    <tbody><tr><td>";

                $html.= "<div style='padding-bottom:10px;'>
                
                <form action='friend.php' method='POST'>
                <select name='person'>";
                while($row= $query->fetch(PDO::FETCH_ASSOC)){
                    $html.= "<option value='" . $row['userName'] . "'>" . $row['userName'] . "</option>";
                }

                $html.= "</select></td>
                <td><select name='relation'>
                    <option value='Friend'>Friend</option>
                    <option value='Family'>Family</option>
                    <option value='Fav'>Favourite</option>
                </select>
                <button type='submit' class='btn btn-primary' name='friendsButton' value='$userName'>Confirm</button>
                </div></td>
                <td><select name='block'>
                    <option value='Blocked'>Block</option>
                    <option value='Not Blocked'>Unblock</option>
                </select>
                <button type='submit' class='btn btn-primary' name='blockButton' value='$userName'>Confirm</button>
                </div></td>


                </tr></form>
                </tbody></table></div>
                </div>
                <div><div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
                    <thead class='thead-dark'>
                    <tr>
                    <th>Username</th>
                    <th>Current Relationship</th>
                    <th>Block Status</th>
                    </tr>
                    </thead>
                    <tbody>";

                $contactquery = $this->con->prepare("select * from contact where userName = '$userName'");
                $contactquery->execute();

                while($row = $contactquery->fetch(PDO::FETCH_ASSOC)){
                    $contactUserName = $row['contactUserName'];
                    $contactType = $row['contactType'];
                    $status = $row['status'];

                    $html.="
                    <tr><td>$contactUserName</td>
                        <td>$contactType</td>
                        <td>$status</td></tr>";
                }
                $html.= "</tbody></table></div></div>";
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
        header("location:friend.php");
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
        header("location:friend.php");
    }

}