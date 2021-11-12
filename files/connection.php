<?php
ob_start(); 


session_start();

date_default_timezone_set("America/New_York");

try {
    $con = new PDO("mysql:dbname=MeTube_z17a;host=mysql1.cs.clemson.edu;", "MeTube_w0sh", "thestruggleisreal6620");
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


