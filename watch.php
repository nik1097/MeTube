<?php
    require_once("files/connection.php");
    require_once("files/main.php");
    require_once("files/Classes/MediaPlayer.php");
    require_once("files/Classes/MediaInfoSection.php");
    require_once("files/Classes/CommentsClass.php");

    $commentsClass= new CommentsClass($con);
    if(!isset($_GET["Id"]) && !isset($_POST["postComment"])){
        echo "No URL passed on to page";
        exit();
    }

    $mediaId="";
    if(isset($_GET["Id"])){
        $mediaId = $_GET["Id"];
    }
    if(isset($_POST["postComment"])){
        $mediaId = $_POST["postComment"];
    }

    $media= new Media($con,$mediaId);
    $media->incrementViews();

    $query = $con->prepare("SELECT uploadedBy FROM media where id = '$mediaId'");
    $query->execute();
    $row = $query->fetch(PDO::FETCH_ASSOC);
    $mediaOwner = $row['uploadedBy'];

?>

<div class="PageDiv"> 
    <div class="watchLeftColumn embed-responsive">
    <?php
        $mediaPlayer= new MediaPlayer($media);
        echo $mediaPlayer->create();
    ?>
    </div>
    <div class= "suggestions">
        <div>
            Recommended media for you:
        </div>
        <?php
            $mediaGrid= new MediaGrid($con);
            echo $mediaGrid->create('Recommendation', "", "","", $loggedInUserName, "", $mediaId);
        ?>

    </div>
</div>

<div>

<?php
    $mediaPlayer= new MediaInfoSection($con,$media,$loggedInUser);
    echo $mediaPlayer->create();

    echo "<form action='download.php' method='POST' >
    <button type='submit' value='$mediaId' name='downloadButton'>Download</button>
    </form>";

    $checkquery = $con->prepare("SELECT * from rating where mediaId = '$mediaId'");
    $checkquery -> execute();

    if ($checkquery->rowCount() == 0) {
        echo "Not enough ratings available";
    }
    else{
        $ratingquery = $con->prepare("SELECT ROUND(AVG(ratedIndex),1) as avg FROM rating where mediaId='$mediaId'");
        $ratingquery->execute();
        while($row = $ratingquery->fetch(PDO::FETCH_ASSOC)){
            echo "Overall Rating: ". $row['avg'];
        }
    }

    if($loggedInUserName!=""){

        echo "<form action='updateRating.php' method='POST' >
        <td><select name='rate'>
        <option value=1>1</option>
        <option value=2>2</option>
        <option value=3>3</option>
        <option value=4>4</option>
        <option value=5>5</option>
      	</select>
      	<button type='submit' value='$mediaId' name='ratingButton'>Update Rating</button>
        </form>";

        echo "<form action='addtoplaylist.php' method='POST' >";
        $query = $con->prepare("SELECT * FROM playlist where userName = '$loggedInUserName'");
        $query->execute();

        echo "<select name='playlistname'>";
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
        }
        echo "</select>";

        echo "<button type='submit' value='$mediaId' name='playlistButton'>Add to Playlist</button>
        </form>";

        $checkquery = $con->prepare("SELECT * from favorites where userName='$loggedInUserName' and videoId = '$mediaId'");
        $checkquery->execute();
        if ($checkquery->rowCount() == 0) {
            echo "<form action='addtofavorites.php' method='GET' >
                    <button type='submit' value='$mediaId' name='Id'>Add to Favorite</button>
                  </form>";
        }
        else {
            echo "<form action='removeFromFavorite.php' method='GET' >
                    <button type='submit' value='$mediaId' name='Id'>Remove from Favorite</button>
                  </form>";

        }

        if (isset($_GET["add"]) && $_GET["add"] == 'success') {
            echo "<div class='badge'>
                     <p style = 'color:red'>Successfully added to Favorite!</p>
                  </div>";
        }

        if (isset($_GET["delete"]) && $_GET["delete"] == 'success') {
            echo "<div class='badge'>
                    <p style = 'color:red'>Successfully removed From Favorite!</p>
                  </div>";
        }

        if ($mediaOwner == $loggedInUserName) {
            echo "<form action='updateVideoInfo.php' method='GET' >
                    <button type='submit' value='$mediaId' name='Id'>Edit Video Info</button>
              </form>";
        }
    }
?> 
</div>


<?php
    echo "<div style='padding-top:20px;' ><h3>Comment Section</h3></div>";
    if(isset($_GET["Id"])){
        $result=$commentsClass->getAllCommentsOfMedia($mediaId);
        if($result==""){
            echo "No Comments";
        }
        else{
            echo $result;
        }

    }

    if(isset($_POST["postComment"])){
        $commentsClass->postComment($loggedInUserName,$mediaId,$_POST['comment']);
        $result=$commentsClass->getAllCommentsOfMedia($mediaId);
        echo $result;
        header("location:watch.php?Id=$mediaId");
    }


?>
<?php    

if($loggedInUserName!=""){
    echo "<div class='commentSection' style='margin-right:425px;'>
               <form action='watch.php' method='POST' style='padding-top:20px' >
                <div class='input-group'>
                    <input type='text' id='comment' name='comment' required placeholder='your comment' class='form-control' >
                    <div class='input-group-append'>
                        <button class='btn btn-primary' type='submit' value='$mediaId' name='postComment'>Post</button>
                    </div>
                </div>
            </form>
        </div>";
}

?>
    
