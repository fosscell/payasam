<?php
require_once("config.php");
session_start();
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] != 'ML') {
	exit("Please go back and try again!");
    }
} else {
    header("Location: $start_page");
    exit();
}
if (isset($_POST['add'])) {
  $emails = explode("\n",trim($_POST['emails']));
  $tag = trim($_POST['tag']);
  if (strpos($tag,",") === FALSE && strpos($tag,":") === FALSE) {
	$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
	if ($mysqli->connect_errno)
	  die("Connect failed: ".$mysqli->connect_error);

	if (!($stmt = $mysqli->prepare("INSERT INTO group_mail(email,tags) VALUES (?,':$tag:')")))
	  die("Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error);

	$i = 0;
	$email = $emails[$i];
	if (!$stmt->bind_param("s", $email))
	  die("Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error);

	if (!$stmt->execute())
	  echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;

	/* Prepared statement: repeated execution, only data transferred from client to server */
	for ($i = 1; $i < count($emails); $i++) {
	  $email = $emails[$i];
	  if (!$stmt->execute())
		echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
	}

	/* explicit close recommended */
	$stmt->close();
	$mysqli->close();
  } else
    echo "Invalid tag!";
}
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 Mailer</title>
</head>
<body>
  <h1>Tathva 12 mailer</h1>
  <a href="mail.php">Group mail</a>
  Add new mails
  <a href="mail_history.php">Mail history</a>
  <a href="logout.php">Log out</a><br/><br/>
  <form action="mails_add.php" method="post">
    <input name="tag" placeholder="Main tag" type="text" /><br/>
    <textarea name="emails" placeholder="Line-separated list of eMails" style="height:300px;width:280px"></textarea><br/>
    <input name="add" type="submit" value="Add" />
  </form>
</body>
</html>