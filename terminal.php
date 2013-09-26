<?php
require_once("initdb.php");
if (!isset($_SESSION['type']) || $_SESSION['type'] != "AD")
    _exit("You do not have access to this page");
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 CMS: Administrator Terminal</title>
<link rel="shortcut icon" href="taticon.png" type="image/png"/>
<style type="text/css">
body
{
    background-color:#F4F4FF;
}

.overflow
{
    height:120px;
    width:320px;
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
td:nth-child(6) {
    max-width: 240px;
    overflow: auto;
    word-wrap: break-word;
}
tr:hover {
    background-color: #EFEFFF;
}

</style>

</head>

<body>
    <div style="text-align:right"><a href="logout.php">Log out</a></div>
    <?php
$query="SELECT username, password, eventcode, validate FROM managers";
$result=$mysqli->query($query);
$row=$result->fetch_assoc();

if (!$row)
    echo "No managers found!";
else {
    ?>
    <table>
      <thead>
	<tr><th>Username</th> <th>Password</th> <th>Eventcode</th> <th>Validation</th> <th>-del-</th></tr>
      </thead>
    <?php
    do {
	$u = $row['username'];
	$x = "exec.php?u=$u";
	$v = "<a href='$x&a=";
	$v .= ($row['validate'] == 0) ? "val'>Validate" : "inv'>Invalidate";
	$v .= "</a>";
	echo "<tr> <td>$u</td> <td>$row[password]</td> <td>$row[eventcode]</td> <td>$v</td> <td><a href='javascript:alert(\"$x&a=del\");'>Delete</a></td></tr>";
    } while($row=$result->fetch_array());
    ?></table><br/>
    <?php
}

$query="SELECT code, name, (SELECT name FROM event_cats WHERE event_cats.cat_id=events.cat_id) AS cat, shortdesc, longdesc, tags, contacts, prize, validate FROM events";
$result=$mysqli->query($query);
$row=$result->fetch_assoc();
if (!$row)
    echo "Sorry events table is empty.";
else {
    ?>
    <table>
      <thead>
	<tr> <th>Code</th> <th>Event Name</th> <th>Category</th> <th>Short Desc</th>
	     <th>Long Desc</th> <th>Tags</th> <th>Contacts</th> 
	     <th>Prize</th> <th>Validation</th> <th>-del-</th></tr>
      </thead>
    <?php
    do {
	$e = $row['code'];
	$x = "exec.php?e=$e";
	$v = "<a href='$x&a=";
	$v .= ($row['validate'] == 0) ? "val'>Validate" : "inv'>Invalidate";
	$v .= "</a>";
	echo "<tr><td>$e</td> <td>$row[name]</td> <td>$row[cat]</td> <td>$row[shortdesc]</td>
		  <td><div class='overflow'>".str_replace(array('||sec||','||ttl||'),array('<h4>','</h4>'),$row['longdesc'])."</div></td> <td>$row[tags]</td> <td>".str_replace(array("||0||","||@||"),array("<br/>"," "),$row['contacts'])."</td>
		  <td>".str_replace("||@||","<br/>",$row['prize'])."</td> <td>$v</td>  <td><a href='javascript:alert(\"$x&a=del\");'>Delete</a></td></tr>";
    } while($row=$result->fetch_array());
    ?></table>
    <?php
}
?>

</body>

</html>
