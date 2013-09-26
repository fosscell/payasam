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

if (isset($_POST['colleges'])) {
  $clgs = explode("\n",$_POST['colleges']);

  $mysqli = new mysqli($host,$db_user,$db_password,$db_name);
  if ($mysqli->connect_errno)
	die("Connect failed: ".$mysqli->connect_error);

  if (!($stmt = $mysqli->prepare("INSERT INTO colleges(name, validated) VALUES (?,1)")))
	die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);

  $i = 0;
  $college = $clgs[$i];
  if (!$stmt->bind_param("s", $college))
	die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);

  if (!$stmt->execute())
	echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

  /* Prepared statement: repeated execution, only data transferred from client to server */
  for ($i = 1; $i < count($clgs); $i++) {
	$college = $clgs[$i];
	if (!$stmt->execute())
	  echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
  }

  /* explicit close recommended */
  $stmt->close();
  $mysqli->close();
}
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 College List</title>
<script type="text/javascript" src="jquery.min.js"></script>

</head>
<body>
  <h1>Tathva 12 College List -FORBIDDEN PAGE-</h1>
  <a href="logout.php">Log out</a>
  <form action="cl_add.php" method="post">
    <textarea name="colleges" style="height:600px;width:480px"></textarea><br/>
    <input type="submit" value="Add" />
  </form>
</body>
</html>