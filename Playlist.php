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
            </tr>
        
            </thead>
            <tbody><tr><td>
            <form action='playlist.php' method='POST'>";
        
    $html.= "<select name='playlistname'>";
    while($row= $query->fetch(PDO::FETCH_ASSOC)){
        $html.= "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    }
    $html.="</select></td>
    <td><button type='submit' class='btn btn-primary' name='viewplaylistButton' value='name'>View Playlist</button></td>
    <td><button type='submit' class='btn btn-primary' name='deleteplaylistButton' value='name'>Delete Playlist</button></td>
    </form>
    </div></tr>";

    $html.="</tbody>
    </table></div>
    <p1>Create A New Playlist</p1>
    <form action='playlist.php' method='POST'>
	        <input type='text' name ='playlistNamein' placeholder='Enter Name' required>
            <button type='submit' class='btn btn-primary' name='createPlaylist' value='$loggedInUserName'>Create Playlist</button>
        </form>
    
    </div>";
    echo $html;
}
if(isset($_POST["createPlaylist"])){

    $playlistnamein = $_POST['playlistNamein'];
    $query = $con->prepare("INSERT INTO playlist (name, userName) VALUES ('$playlistnamein', '$loggedInUserName')");
    $query->execute();
}

if(isset($_POST["viewplaylistButton"])){
    $query = $con->prepare("SELECT *
    FROM media
    JOIN playlist_media ON media.id = playlist_media.videoId
    JOIN playlist ON playlist.name = playlist_media.playlistName
    WHERE playlistName = 'viewplaylistButton' AND userName='$loggedInUserName';");
    $query->execute();
    echo "
    <div><div class='table-responsive'><table class='table table-bordered table-striped table-hover'>
        <thead class='thead-dark'>
        <tr>
        <th>Media</th>
        <th>Remove from Playlist</th>
        </tr>
    
        </thead>
        <tbody>";

    while($row=$query->fetch(PDO::FETCH_ASSOC)){
        $mediaName= $row["title"];  
        echo $mediaName;  
        echo "<tr>
        <td>button type='submit' class='btn btn-primary' name='deletefromplaylist' value='$videoId'>Delete</button>
        </td></tr>";
        
    echo "</tbody></table>"; 
    }
}
?>