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
    <div class="watchLeftColumn embed-responsive embed-responsive-16by9">
    <?php
        $mediaPlayer= new MediaPlayer($media);
        echo $mediaPlayer->create();
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

    echo "<form action='playlist.php' method='POST' >
    <button type='submit' value='$vidId' name='playlistButton'>Playlist</button>
    </form>";

    echo "<form action='addtofavorites.php' method='POST' >
    <button type='submit' value='$vidId' name='favoriteButton'>Favorite</button>
    </form>";

?> 
</div>

<div align="center" style="background: #000; padding: 50px;color:white;">
        <i class="fa fa-star fa-2x" data-index="0"></i>
        <i class="fa fa-star fa-2x" data-index="1"></i>
        <i class="fa fa-star fa-2x" data-index="2"></i>
        <i class="fa fa-star fa-2x" data-index="3"></i>
        <i class="fa fa-star fa-2x" data-index="4"></i>
        <br><br>
        <?php echo round($avg,2) ?>
    </div>

<script src="http://code.jquery.com/jquery-3.4.0.min.js" integrity="sha256-BJeo0qm959uMBGb65z40ejJYGSgR7REI4+CW1fNKwOg=" crossorigin="anonymous">
</script>
    <script>
        var ratedIndex = -1, uID = 0;

        $(document).ready(function () {
            resetStarColors();

            if (localStorage.getItem('ratedIndex') != null) {
                setStars(parseInt(localStorage.getItem('ratedIndex')));
                uID = localStorage.getItem('uID');
            }

            $('.fa-star').on('click', function () {
               ratedIndex = parseInt($(this).data('index'));
               localStorage.setItem('ratedIndex', ratedIndex);
               saveToTheDB();
            });

            $('.fa-star').mouseover(function () {
                resetStarColors();
                var currentIndex = parseInt($(this).data('index'));
                setStars(currentIndex);
            });

            $('.fa-star').mouseleave(function () {
                resetStarColors();

                if (ratedIndex != -1)
                    setStars(ratedIndex);
            });
        });

        function saveToTheDB() {
            $.ajax({
               url: "rating.php",
               method: "POST",
               dataType: 'json',
               data: {
                   save: 1,
                   uID: $loggedInUserName,
                   ratedIndex: ratedIndex
               }, success: function (r) {
                    uID = r.id;
                
               }
            });
        }

        function setStars(max) {
            for (var i=0; i <= max; i++)
                $('.fa-star:eq('+i+')').css('color', 'green');
        }

        function resetStarColors() {
            $('.fa-star').css('color', 'white');
        }
    </script>

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
    
