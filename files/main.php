<?php 
require_once("files/connection.php"); 
require_once("files/Classes/UserDetails.php");
require_once("files/Classes/Video.php");
require_once("files/Classes/VideoGrid.php"); 
require_once("files/Classes/VideoItem.php");  

$loggedInUserName="";
if(isset($_SESSION["loggedinUser"])){
  $loggedInUserName=$_SESSION["loggedinUser"];
}
$loggedInUser = new UserDetails($con,$loggedInUserName);
?>

<!DOCTYPE html>
<html>
  <head>
    <title>MeTube</title>

      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css"> 
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
      <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" href="files/css/style.css">


      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
      <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
      <script src="files/js/jsfile.js"></script>

  </head>
<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
  <button class="btn hamburgermenu" >
    <span class="navbar-toggler-icon"></span>
  </button>
  <a class="navbar-brand" href="index.php"><i class="fab fa-youtube fa-lg" style="color:red;"></i> Metube</a>
  <button class="navbar-toggler btn " type="button" data-toggle="collapse" data-target="#navbar-collapse-content" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        Menu <i class="fas fa-caret-square-down" ></i>
  </button>

  <div class="collapse navbar-collapse" id="navbar-collapse-content">
    
    <form class="form-inline my-2 my-lg-0 mr-auto search-bar" action="search.php" method="GET">
      <input class="form-control mr-sm-2 search" list="datalist" onkeyup="ac(this.value)" type="search" placeholder="Search" aria-label="Search" name="term">
      <button class="btn btn-dark my-2 my-sm-0" type="submit">Search <i class="fab fa-searchengin"></i></button>
    </form>

  <?php
   if($loggedInUserName!="")
   {
      echo "<ul class='navbar-nav'>
      <li class='nav-item'>
      <a class='text-danger nav-link' href='index.php'>".$loggedInUser->getuserName()." </a>
      </li>
      <li class='nav-item'>
         <a class='nav-link' href='upload.php'>Upload <i class='fas fa-upload'></i></a>
      </li>
   
      <li class='nav-item'>
      <a class='nav-link' href='logout.php'>Log Out <i class='fas fa-sign-out-alt'></i></a>
      </li>
      </ul>";     
     }
    else{
      echo "<ul class='navbar-nav'>
        <li class='nav-item'>
          <a class='nav-link' href='signup.php'>Sign up <i class='fas fa-user-plus'></i> </a>
        </li>
        <li class='nav-item'>
          <a class='nav-link' href='login.php'>Log In <i class='fas fa-user'></i></a>
        </li>
      </ul>";
    } 
   ?>   
    </div>
  </nav>


<div id="side-nav" style="display:none;">
<div class="sidebar-menu">
<ul style="list-style-type:none;">
      <li class='nav-item'>
        <a href='index.php'>Home</a>
      </li>
      <li class='nav-item'>
      
    <a href='CategorySearch.php'>Search by Category</a>
  </li>
      <?php
      if($loggedInUserName!="")
      {
      echo"<li class='nav-item'>
      <a href='updateProfile.php'>Update Profile</a>
        </li>
        <li class='nav-item'>
        <a href='friend.php'>Contacts</a>
      </li> 
      <li class='nav-item'>
        <a href='message.php'>Messages</a>
      </li>
      
      <li class='nav-item'>
      <a href='channels.php'>Your Channel</a>
    </li>
    <li class='nav-item'>
      <a href='playlist.php?id='>Your Playlist</a>
    </li>

    <li class='nav-item'>
      <a href='favoritelist.php?id='>Favorite List</a>
    </li>";
      }
      ?>
  </ul>
</div>

</div> 

<div id="main-section">
    <div id="content" class="container-fluid">