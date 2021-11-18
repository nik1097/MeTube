<?php
require_once("files/main.php");

$query = $con->prepare("SELECT * from playlist where userName = '$loggedInUserName'");
$query->execute();

if($query->rowCount()== 0){
    echo "";
}
else{
    $html = "
    <div><div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
            <thead class='thead-dark'>
            <tr>
            <th>Playlist Name</th>
            <th>View</th>
            </tr>
        
            </thead>
            <tbody>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)){
        
        $playlistName= $row["name"];
        $html.=  "<tr><td>$playlistName</td>";
        
        $html.=  "<td> <div style='padding-bottom:5px;'>
        <form action='playlist.php' method='POST'>
        <button type='submit' class='btn btn-primary' name='viewplaylistButton' value='$playlistName'>Click to View</button>
        </form>
        </div></td>";

        //$html.=  "<td> <div style='padding-bottom:5px;'>
        //<form action='playlist.php' method='POST'>
        //<button type='submit' class='btn btn-primary' name='deleteplaylistButton' value='$playlistName'>Delete</button>
        //</form>
        //</div></td></tr>";
    }
}
    $html.="</tbody>
    </table></div>
    <p1>Create A New Playlist</p1>
    <form action='playlist.php' method='POST'>
	        <input type='text' name ='playlistNamein' placeholder='Enter Name' required>
            <button type='submit' class='btn btn-primary' name='createPlaylist' value='$loggedInUserName'>Create Playlist</button>
        </form>
    
    </div>";
    echo $html;

if(isset($_POST["createPlaylist"])){

    $playlistnamein = $_POST['playlistNamein'];
    echo $playlistnamein;
    $query = $con->prepare("INSERT INTO playlist (name, userName) VALUES ('$playlistnamein', '$loggedInUserName')");
    $query->execute();
}

//if(isset($_POST["deleteplaylistButton"])){

    //$playlistname = $_POST['playlistName'];
    //$query = $con->prepare("DELETE FROM playlist_media WHERE playlistName = '$playlistName'");
    //$query->execute();
    //$query = $con->prepare("DELETE FROM playlist WHERE name = '$playlistName'");
    //$query->execute();

//}


//echo $vidId;

//if(isset($_POST["addtoplaylistButton"])){
//echo $vidId;
//$checkquery = $con -> prepare("SELECT * FROM playlist inner join playlist_media 
//on playlist.name = playlist_media.playlistName
//where userName = '$loggedInUserName' and videoId = '$vidId' and playlistName = '$playlistName'");

//$checkquery -> execute();
//if($checkquery->rowCount()==0){
    
  //  $query = $con -> prepare("INSERT INTO playlist_media ('playlistName', 'videoId') VALUES ('$playlistName','$vidId',)");
    //$query->execute();
    //echo "Video has been added to playlist";
//}
//else{
  //  echo"Video is already in playlist";
//}
//}

?>