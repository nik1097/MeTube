<?php
require_once("files/connection.php");
require_once("files/main.php");

if ($_POST['cancel']) {
    header("Location: index.php");
    exit;
}

$filename = $_FILES["mediaFile"]["name"];
$title = $_POST["title"];
$description = $_POST['description'];
$category = $_POST['category'];
$visibility = $_POST['visibility'];
$keywords = $_POST['keywords'];
$keyword_arr = explode(',', $keywords);

$videoExts = array("video/mp4");
$imageExts = array("image/pjpeg", "image/gif", "image/jpeg");
$audioExts = array("audio/mp3", "audio/wma");

$extension = pathinfo($_FILES['mediaFile']['name'], PATHINFO_EXTENSION);
$username = $_SESSION["loggedinUser"];
$file_path = 'uploads/'.$username.'/';

//if user upload folder does not exist, create the folder
if (!file_exists($file_path)) {
    mkdir($file_path);
    chmod($file_path, 0755);
}

if($_FILES["mediaFile"]["error"] > 0 ) {
    echo "<h1> error:".$_FILES["mediaFile"]["error"] ."</h1>";
    //header("Location: index.php");
    exit;
}

$upload_file = $file_path.urlencode($filename);

if(file_exists($upload_file))
{
    echo "File already exists";
    header("Location: upload.php");
    exit;
}

try{
    move_uploaded_file($_FILES["mediaFile"]["tmp_name"],
        $upload_file);
    echo "Stored in: " . $file_path;
    chmod($upload_file, 0644);
    $query = $con->prepare("INSERT INTO videos(description,uploadedBy,title, category, privacy, keywords, filepath, duration, views) 
                    VALUES('$description','$username','$title', '$category', '$visibility', '$keyword_arr[0]', '$upload_file', '00:00',0)");
    $query->execute();
//    if($query->rowCount() ==1){
//        return true;
//    }
//    else{
//        array_push($this->errorMessages,StatusMessage::$loginFailed);
//        return false;
//    }
}
catch(Exception $e){
    echo"Some Error Occured: ".$e->getMessage();
}

//if (in_array($_FILES["mediaFile"]["type"], $videoExts))
//{
//    echo "Upload: " . $_FILES["mediaFile"]["name"] . "<br />";
//    echo "Type: " . $_FILES["mediaFile"]["type"] . "<br />";
//    echo "Size: " . ($_FILES["mediaFile"]["size"] / 1024) . " Kb<br />";
//    echo "Temp file: " . $_FILES["mediaFile"]["tmp_name"] . "<br />";
//
//
//
//
//}
//else
//{
//    echo "Invalid file type";
//    header("Location: upload.php");
//    exit;
//}
header("Location: upload.php");
?>