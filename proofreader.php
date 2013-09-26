<?php
require_once("initdb.php");
$erlist = '';
if (!isset($_SESSION['type']) || $_SESSION['type'] != "PR")
  _exit("You do not have access to this page");
if (isset($_POST['prsubmit']) && $_POST['event']) {
  $_SESSION['ecode'] = $_POST['event'];
  header("Location: manager.php");
  _exit();
} else if (isset($_POST['ersubmit']) && $_POST['event']) {
  $erlist = $_POST['event'];
}
if (isset($_SESSION['ecode'])) {
  header("Location: manager.php");
  _exit();
}
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 CMS: Proofreaders Corner</title>
<link rel="shortcut icon" href="taticon.png" type="image/png"/>
</head>

<body>
  <div style="text-align:right"><a href="logout.php">Log out</a></div>
  <form action="proofreader.php" method="post">
	<select name="event">
	  <option value="">--events--</option>
	  <?php
$res = $mysqli->query("SELECT code, name FROM events");
while($row=$res->fetch_assoc()) {
  if ($erlist == $row['code']) $erlname = $row['name'];
  echo "<option value='$row[code]'>$row[name]</option>";
}
$res->free();
	  ?>
	</select>
	<input name="prsubmit" type="submit" value="Proofread">
	<input name="ersubmit" type="submit" value="Get Event Reg List">
  </form>
  <?php
if ($erlist) {
  echo "<h3 style='margin:10px 0'>$erlname Registration List</h3>";
  $res = $mysqli->query("SELECT e.team_id, e.tat_id, s.name as name, s.phone, s.email, c.name as clg FROM event_reg e INNER JOIN student_reg s ON e.tat_id=s.id INNER JOIN colleges c ON s.clg_id=c.id WHERE e.code='$erlist'");
  ?>
  <table>
	<tr><th>Team ID</th><th>Tathva ID</th><th>Name</th><th>Phone no.</th><th>eMail</th><th>College</th></tr>
	<?php
  while($row=$res->fetch_assoc())
	echo "<tr><td>$row[team_id]</td><td>$row[tat_id]</td><td>$row[name]</td><td>$row[phone]</td><td>$row[email]</td><td>$row[clg]</td></tr>";
	?>
  </table>
  <?php
  $res->free();
}
$mysqli->close();
  ?>
</body>
</html>