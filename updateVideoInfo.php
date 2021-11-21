<?php
require_once("files/connection.php");
require_once("files/main.php");

$vedioId = (int)$_GET['Id'];
$query = $con->prepare("SELECT * FROM media where id = '$vedioId'");
$query->execute();
$row = $query->fetch(PDO::FETCH_ASSOC);
$title = $row["title"];

$description = $row['description'];
$category = $row['category'];
$visibility = $row['privacy'];
$categoryMap = array("Animal" => 0, "Human" => 1, "Sports" => 2, "Other" => 3);
$categoryIndex = $categoryMap[$category];
$categorySelect = array(false, false, false, false);
$categorySelect[$categoryIndex] = true;

$visibilityMap = array("Public" => 0, "Friend" => 1, "Family" => 2, "Fav" => 3);
$visibilityIndex = $visibilityMap[$visibility];
$visibilitySelect = array(false, false, false, false);
$visibilitySelect[$visibilityIndex] = true;

$query = $con->prepare("SELECT * FROM keywords where media_id = '$vedioId'");
$query->execute();

$keywords = "";
while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $keywords.=$row["keyword"];
    $keywords.=";";
}
$keywords = rtrim($keywords, ';');

$actionString = "updateVideoProcess.php?Id=".$vedioId;
//echo "$actionString";
?>

<form action=<?php echo "$actionString" ?> method="POST" enctype="multipart/form-data">

    <div style="color: blue">Please edit video info:</div>

    <div class="form-floating">
        <label for="title">Media Title:</label>
        <textarea class="form-control" name="title" rows = "1"  maxlength="50"
                  placeholder="Enter your title(required)"  id="title" required><?php echo $title ?></textarea>
    </div>
    <br>
    <div class="form-floating">
        <label for="keywords">Keyword List (separted by ',')</label>
        <textarea class="form-control" name="keywords" placeholder="Enter keywords list, each keyword separated by ','" id="keywords" ><?php echo $keywords ?></textarea>
    </div>
    <br>
    <!--    <p> Select a category</p>-->
    <label class="form-label" for="category">
        Select a category:
    </label>
    <div id = 'category'>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Animal" value="Animal" <?php if ($categorySelect[0]) echo "checked"?>>
            <label class="form-check-label" for="Animal">
                Animal
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Human" value="Human" <?php if ($categorySelect[1]) echo "checked"?>>
            <label class="form-check-label" for="Human">
                Human
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Sports" value="Sports" <?php if ($categorySelect[2]) echo "checked"?>>
            <label class="form-check-label" for="Sports">
                Sports
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="category" id="Other" value="Other" <?php if ($categorySelect[3]) echo "checked"?>>
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
            <input type="radio" class="form-check-input" name="visibility" id="Public" value="Public" <?php if ($visibilitySelect[0]) echo "checked"?>>
            <label class="form-check-label" for="Public">
                Public
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Friend" value="Friend" <?php if ($visibilitySelect[1]) echo "checked"?>>
            <label class="form-check-label" for="Friend">
                Friend
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Family" value="Family" <?php if ($visibilitySelect[2]) echo "checked"?>>
            <label class="form-check-label" for="Family">
                Family
            </label>
        </div>
        <div class="form-check">
            <input type="radio" class="form-check-input" name="visibility" id="Fav" value="Fav" <?php if ($visibilitySelect[3]) echo "checked"?>>
            <label class="form-check-label" for="Fav">
                Fav
            </label>
        </div>
    </div>
    <br>
    <div class="form-floating">
        <label for="floatingTextarea">Description</label>
        <textarea class="form-control" name = 'description' placeholder="Enter media description 200 characters maximum" id="floatingTextarea"  maxlength="200"><?php echo $description ?></textarea>
    </div>
    <br>
    <button class="btn btn btn-primary" value="upload" name="upload" type="submit" />Upload</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="btn btn btn-secondary" value="cancel" name="cancel" type="submit" />Cancel</button>

</form>
