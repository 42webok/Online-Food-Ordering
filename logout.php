<?php 
include("admin/database.inc.php");
session_start();

session_unset();
setcookie("remember_token", "", time() - 3600, "/");
session_destroy();
header("location:index.php");
?>