<?php
// This page is used to manage college names, while the main site is up and running.
// When students signs up from the main site, they should be allowed to provide any
// college name in case he/she cannot find his/her college in our list. In that case,
// that college should be added to our list as "not validated".
// This page lists colleges that are "not validated"
// Publicly shown college-list should contain only "validated" colleges. Here cases may arise:
// 1. Multiple users register from an unlisted college (before it gets "validated").
//    Since that college is not publicly listed, it will be added multiple times.
//    In that case, we will validate one of it, and "migrate" all other registrations to it.
// 2. Prank user registers with a bad college name. "Ignore" them. Ignored items should
//    not show up here thereafter.
// 
// TODO: When "Migrate"-ing, we should input college id into the field that appears. Autocomplete can help.
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
<title>College List</title>
<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">
$(function () {
  $("#ctable").on("click", "a.migrate", function () {
	$(document.migform.id).val($(this).attr("href").substr(1)); // href of migrate is be like: "#<college id>"
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
  <h1>College List</h1>
  <a href="logout.php">Log out</a>
  <table id="ctable">
	<tr><th>Id</th><th>Name</th><th>Editing</th><th>Validation</th><th>Ignore</th><th>Students Name and Phone</th><th>Migrate</th></tr>
<?php
$result = $mysqli->query("SELECT id, name, (SELECT group_concat(concat(name, ' (', phone, ')') SEPARATOR '<br/>') FROM student_reg WHERE clg_id=colleges.id) as students FROM colleges WHERE validated=0");

while ($row = $result->fetch_assoc()) {
  echo "<tr><td>$row[id]</td><td>$row[name]</td>";
  echo "<td><a href=\"cl_edit.php?id=$row[id]\">Edit</a></td>";
  echo "<td><a href=\"cl_exec.php?id=$row[id]&do=val\">Validate</a></td>";
  echo "<td><a href=\"cl_exec.php?id=$row[id]&do=ign\">Ignore</a></td>";
  echo "<td>$row[students]</td>";
  echo "<td><a class=\"migrate\" href=\"#$row[id]\">Migrate</a></td></tr>"; // when clicked, 'migform' will be shown beside it.
}
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
