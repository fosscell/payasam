<?php
require_once("config.php");
session_start();
if (isset($_SESSION['user']) && isset($_SESSION['tat_id'])) {
    $event_code = $_POST["evcode"];
    $team = $_POST["evteam"];
    /*Register*/
    echo "Hohohoho!!!";
} else {
    echo "Please <a href='tat_login.php'>Log in</a>";
}
?>