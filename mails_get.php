<?php
require_once("config.php");
session_start();
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] != 'ML') {
	exit("Wrong page!");
    }
} else {
    header("Location: $start_page");
    exit();
}
if (isset($_GET['wtag']) && isset($_GET['stag'])) {
  $mysqli = new mysqli($host,$db_user,$db_password,$db_name);
  if ($mysqli->connect_errno)
    die("Connect failed: ".$mysqli->connect_error);
  $result = $mysqli->query("SELECT * FROM group_mail WHERE tags LIKE '%:$_GET[wtag]:%' AND tags NOT LIKE '%:$_GET[stag]:%' LIMIT 50");
  $rows = array();
  while($r = $result->fetch_assoc()) {
    $rows[] = $r;
  }
  print json_encode($rows);
}
?>