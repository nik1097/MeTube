<?php 

    require_once("files/main.php");
    require_once("files/Classes/MediaPlayer.php");
    require_once("files/Classes/MediaInfoSection.php");
    require_once("files/Classes/CommentsClass.php");

    $commentsClass= new CommentsClass($con);
    if(!isset($_GET["Id"]) && !isset($_POST["postComment"])){
        echo "No URL passed on to page";
        exit();
    }

    $vidId="";
    if(isset($_GET["Id"])){
        $vidId = $_GET["Id"];
    }
    if(isset($_POST["postComment"])){
        $vidId = $_POST["postComment"];
    }

    $media= new Media($con,$vidId);
    $media->incrementViews();
?>

<div class="PageDiv"> 
    <div class="watchLeftColumn embed-responsive">
    <?php
        $mediaPlayer= new MediaPlayer($media);
        echo $mediaPlayer->create();
    ?>
    </div>
    <div class= "suggestions">
        <?php
            $mediaGrid= new MediaGrid($con);
            echo $mediaGrid->create('Recommendation', "", "","", $loggedInUserName, $vidId);
        ?>

    </div>
</div>

<div>

<?php
    $mediaPlayer= new MediaInfoSection($con,$media,$loggedInUser);
    echo $mediaPlayer->create();

    echo "<form action='download.php' method='POST' >
    <button type='submit' value='$vidId' name='downloadButton'>Download</button>
    </form>";

    echo "<form action='addtoplaylist.php' method='POST' >";
    $query = $con->prepare("SELECT * FROM playlist where userName = '$loggedInUserName'");
    $query->execute();

    echo "<select name='playlistname'>";
    while($row= $query->fetch(PDO::FETCH_ASSOC)){
        echo "<option value='" . $row['name'] . "'>" . $row['name'] . "</option>";
    }
    echo "</select>";

    echo "<button type='submit' value='$vidId' name='playlistButton'>Add to Playlist</button>
    </form>";

    $checkquery = $con -> prepare("SELECT * from favorites where userName='$loggedInUserName' and videoId = '$vidId'");
    $checkquery -> execute();
    if($checkquery->rowCount()==0){
         echo "<form action='addtofavorites.php' method='GET' >
                <button type='submit' value='$vidId' name='Id'>Add to Favorite</button>
              </form>";

    }
    else{
        echo "<form action='removeFromFavorite.php' method='GET' >
                <button type='submit' value='$vidId' name='Id'>Remove from Favorite</button>
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
?> 
</div>


<?php
    echo "<div style='padding-top:20px;' ><h3>Comment Section</h3></div>";
    if(isset($_GET["Id"])){
        $result=$commentsClass->getAllCommentsOfMedia($vidId);
        if($result==""){
            echo "No Comments";
        }
        else{
            echo $result;
        }

    }

    if(isset($_POST["postComment"])){
        $commentsClass->postComment($loggedInUserName,$vidId,$_POST['comment']); 
        $result=$commentsClass->getAllCommentsOfMedia($vidId);
        echo $result;
        header("location:watch.php?Id=$vidId");
    }


?>
<?php    

if($loggedInUserName!=""){
    echo "<div class='commentSection' style='margin-right:425px;'>
    <form action='watch.php' method='POST' style='padding-top:20px' >
        <div class='input-group'>
        <input type='text' id='comment' name='comment' required placeholder='your comment' class='form-control' >
        <div class='input-group-append'>
        <button class='btn btn-primary' type='submit' value='$vidId' name='postComment'>Post</button>
        </div>
        </div>
        </form>
    </div>";
}

?>
    
