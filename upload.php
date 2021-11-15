<?php
require_once("files/connection.php");
require_once("files/main.php");

?>

<!DOCTYPE html>

<head>
    <title></title>
</head>

<body>

<form action="upload_process.php" method="POST" enctype="multipart/form-data">

    <div class="mb-3">
        <label for="formFile" class="form-label">Select a media file to upload</label>
        <input class="form-control" type="file" id="formFile" name = 'mediaFile' required>
    </div>

    <div class="form-floating">
        <label for="title">Media Title:</label>
        <textarea class="form-control" name="title" rows = "1"  maxlength="50"
                  placeholder="Enter your title(required)" id="title" required></textarea>
    </div>
    <br>
    <div class="form-floating">
        <label for="keywords">Keyword List (separted by ',')</label>
        <textarea class="form-control" name="keywords" placeholder="Enter keywords list, each keyword separated by ','" id="keywords"></textarea>
    </div>
    <br>
<!--    <p> Select a category</p>-->
    <label class="form-label" for="category">
        Select a category:
    </label>
    <div id = 'category'>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Animal" value="Animal">
            <label class="form-check-label" for="Animal">
                Animal
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Human" value="Human">
            <label class="form-check-label" for="Human">
                Human
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Sports" value="Sports">
            <label class="form-check-label" for="Sports">
                Sports
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Other" value="Other" checked>
            <label class="form-check-label" for="Other">
                Other
            </label>
        </div>
    </div>
    <br>
    <label class="form-label" for="visibility">
        Visibility:
    </label>
    <div id = 'visibility'>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Public" value="Public" checked>
            <label class="form-check-label" for="Public">
                Public
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Friend" value="Friend">
            <label class="form-check-label" for="Friend">
                Friend
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Family" value="Family">
            <label class="form-check-label" for="Family">
                Family
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Fav" value="Fav">
            <label class="form-check-label" for="Fav">
                Fav
            </label>
        </div>
    </div>
    <br>
    <div class="form-floating">
        <label for="floatingTextarea">Description</label>
        <textarea class="form-control" name = 'description' placeholder="Enter media description 200 characters maximum" id="floatingTextarea" maxlength="200"></textarea>
    </div>
    <br>
    <button class="btn btn btn-primary" value="upload" name="upload" type="submit" />Upload</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="btn btn btn-secondary" onclick="location.href='index.php';" type="button" >Cancel</button>

</form>

</body>
</html>