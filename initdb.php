<?php
require_once("config.php");
$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
    die("Connect failed: ".$mysqli->connect_error);
function _exit($s="") {
    global $mysqli;
    $mysqli->close();
    exit($s);
}
session_start();
?>