<?php
require_once("initdb.php");
// redirect for unauthorised access
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] != 'AD')
	_exit("Go away!");
} else
    _exit("Who are you?");
// setting username
if (isset($_GET['u']))
    $u=$_GET['u'];
// setting eventcode
else if (isset($_GET['e']))
    $e=$_GET['e'];
// setting type of operation (validate/invalidate/delete)
if (isset($_GET['a']))
    $a = $_GET['a'];
if ($u && $a) {
    if ($a == 'val')
	$query="UPDATE managers SET validate=1 WHERE username='$u'";
    else if ($a == 'inv')
	$query="UPDATE managers SET validate=0 WHERE username='$u'";
    else if ($a == 'del')
	$query="DELETE FROM managers WHERE username='$u'";
} else if ($e && $a) {
    if ($a == 'val')
	$query="UPDATE events SET validate=1 WHERE code='$e'";
    else if ($a == 'inv')
	$query="UPDATE events SET validate=0 WHERE code='$e'";
    else if ($a == 'del')
	$query="DELETE FROM events WHERE code='$e'";
}
if ($query) {
    if ($mysqli->query($query))
	echo "Success!";
    else
	echo "Failed! Please contact an administrator!";
} else
    echo "Invalid request!";
$mysqli->close();
echo " <a href='terminal.php'>Go back!</a>";
?>