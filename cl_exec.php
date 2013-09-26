<?php
require_once("config.php");
session_start();
if (isset($_SESSION["type"])) {
  if ($_SESSION["type"] != 'CL') {
	exit("Please go back and try again!");
  }
} else {
  header("Location: $start_page");
  exit();
}

if (isset($_GET['id']) && isset($_GET['do'])) {
  $id = $_GET['id'];
  $do = $_GET['do'];
  if (isset($_GET['dest']))
	$dest = $_GET['dest'];
} else
  exit("Invalid request!");

$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);

if ($do == 'ign')
  $query="UPDATE colleges SET validated=-1 WHERE id='$id'";
else if ($do == 'val')
  $query="UPDATE colleges SET validated=1 WHERE id='$id'";
else if ($do == 'mig' && $dest)
  $query="UPDATE student_reg SET clg_id='$dest' WHERE clg_id='$id'";
else
  echo "Invalid request!!!";
if ($query) {
  if ($mysqli->query($query))
	echo "Success! ".$mysqli->affected_rows." rows affected!";
  else
	echo "Failed! ".$mysqli->error;
}
echo " <a href='cl.php'>Go back!</a>";
$mysqli->close();
?>