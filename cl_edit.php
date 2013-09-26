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

if (isset($_GET['id']))
  $id=$_GET['id'];
else if (isset($_POST['id']) && isset($_POST['name'])) {
  $id=$_POST['id'];
  $name=$_POST['name'];
} else
  exit("Invalid request!");

if (!preg_match('/^[0-9]+$/',$id))
  exit("Invalid request!!!");

$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);

$u_stat = 0;
if ($name) {
  $name = $mysqli->real_escape_string($name);
  if ($mysqli->query("UPDATE colleges SET name='$name' WHERE id='$id'"))
	$u_stat = 1;
}

$result = $mysqli->query("SELECT * FROM colleges WHERE id='$id'");
$row = $result->fetch_assoc();
$result->free();
$mysqli->close();
if ($row)
  $name = $row['name'];
else
  exit("Invalid college id!");
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 College List</title>
<script type="text/javascript" src="jquery.min.js"></script>

</head>
<body>
  <h1>Tathva 12 College List</h1>
  <a href="cl.php">Back to list</a>
  <a href="logout.php">Log out</a>
  <form action="cl_edit.php" method="POST">
	<?php if ($u_stat == 1) echo "Successfully updated!<br/>"; ?>
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<input type="text" name="name" value="<?php echo $name; ?>" style="width:320px" />
	<input type="submit" value="Update" />
  </form>
</body>
</html>