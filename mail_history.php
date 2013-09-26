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
$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 Mailer</title>
<style type="text/css">
body
{
    background-color:#F8F8FC;
}
.overflow
{
    height:80px;
    width:200px;
    overflow:auto;
    resize: both;
    background-color: #F8F8FC;
    margin: 3px;
    border: 1px solid #88B;
    border-radius: 3px;
}
table {
    border-spacing: 0;
    border: 0;
    background-color:#F4F4FF;
}
th {
    padding: 5px 15px 5px 0px;
    text-align: left;
    border: 0;
    border-bottom: 1px solid #889;
    background-color: #E9E9FF;
}
td {
    padding-right: 15px;
    border: 0;
    border-bottom: 1px solid #99A;
}
td:hover {
    background-color: #EFEFFF;
}
</style>
</head>
<body>
  <h1>Tathva 12 mailer</h1>
  <a href="mail.php">Group mail</a>
  <a href="mails_add.php">Add new mails</a>
  Mail history
  <a href="logout.php">Log out</a><br/><br/>
  <table>
    <tr>
      <th>id</th><th>replyto</th><th>wtag</th><th>stag</th>
      <th>to_ids</th><th>subject</th><th>body</th><th>timestamp</th>
      <?php
$result = $mysqli->query("SELECT id,replyto,wtag,stag,to_ids,subject,body,timestamp FROM mail_history");
while($r = $result->fetch_assoc()) {
  $body = str_replace("\n",'<br/>',$r['body']);
  echo "<tr><td>$r[id]</td><td>$r[replyto]</td><td>$r[wtag]</td><td>$r[stag]</td><td>$r[to_ids]</td>".
       "<td>$r[subject]</td><td>$body</td><td>$r[timestamp]</td></tr>";
}
$result->free();
$mysqli->close();
      ?>
    </tr>
  </table>
</body>
</html>