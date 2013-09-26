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
$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);

?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 College List</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
$(function () {
  $("#ctable").on("click", "a.migrate", function () {
	$(document.migform.id).val($(this).attr("href").substr(1));
	var r = this.getBoundingClientRect();
	$(document.migform.dest).css({
	  left: r.left + this.offsetWidth,
	  top: r.top - 3 + $(document).scrollTop()
	}).show().focus();
	return false;
  });
  $(document.migform.dest).blur(function () {$(this).hide();});
});
</script>
</head>
<body>
  <h1>Tathva 12 College List</h1>
  <a href="logout.php">Log out</a>
  <table id="ctable">
	<tr><th>id</th><th>name</th><th>editing</th><th>validation</th><th>ignore</th><th>student name</th><th>his/her phone</th><th>migrate</th></tr>
<?php
$result = $mysqli->query("SELECT id, name, (SELECT id FROM student_reg WHERE clg_id=colleges.id limit 1) as st_id FROM colleges WHERE validated=0");
$tid = 0;
$stmt = $mysqli->prepare("SELECT name, phone FROM student_reg WHERE id=?");
$stmt->bind_param("i", $tid);

$name = ""; $phone = "";
while ($row = $result->fetch_assoc()) {
  $tid = $row['st_id'];
  $stmt->execute();
  $stmt->bind_result($name, $phone);
  echo "<tr><td>$row[id]</td><td>$row[name]</td><td><a href=\"cl_edit.php?id=$row[id]\">Edit</a></td><td><a href=\"cl_exec.php?id=$row[id]&do=val\">Validate</a></td><td><a href=\"cl_exec.php?id=$row[id]&do=ign\">Ignore</a></td>";
  if ($stmt->fetch())
	echo "<td>$name</td><td>$phone</td>";
  else
	echo "<td></td><td></td>";
  echo "<td><a class=\"migrate\" href=\"#$row[id]\">Migrate</a></td></tr>";
}
$stmt->close();
$mysqli->close();
?>
  </table>
  <form method="GET" name="migform" action="cl_exec.php">
	<input type="hidden" name="id" />
	<input type="hidden" name="do" value="mig" />
	<input type="text" name="dest" style="position: absolute; display: none" placeholder='to: college id? Press Enter!' />
  </form>
</body>
</html>
