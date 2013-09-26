<?php
require_once("config.php");
$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);
$result = $mysqli->query("SELECT DISTINCT tags FROM group_mail");
$tags = array();
while($row = $result->fetch_assoc()) {
  $cur_tags = explode("::", substr($row['tags'],1,-1));
  foreach ($cur_tags as $tag)
    $tags[$tag] = 1;
}
$tags = array_keys($tags);
print json_encode($tags);
?>