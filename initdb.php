<?php
// This page is included throughout the CMS as shorthand
// for establishing database connection and starting a session
require_once("config.php");
$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
    die("Connect failed: ".$mysqli->connect_error);

// This function is used as a shorthand for closing the database and exiting
function _exit($s="") {
    global $mysqli;
    $mysqli->close();
    exit($s);
}
session_start();
?>
