<?php
require_once("config.php");
require_once("utils.php");
$user = ""; $status = 0;
session_start();
if (isset($_SESSION['user']) && isset($_SESSION['tat_id'])) {
    echo "You are logged in as $_SESSION[user] (TAT".num2str($_SESSION[tat_id],4)."). <a href='tat_logout.php'>Log out?</a>";
    exit();
}
if ($_POST["submit"] == "Log in") {
    $con=mysql_connect($host,$db_user,$db_password);
    if (!$con)
	die("Could not connect:".mysql_error());
    else if (!mysql_select_db($db_name, $con))
	die("Database error:".mysql_error());
    $user = strtolower(trim($_POST["user"]));
    $pass = $_POST["pass"];
    $result=mysql_query("select id, name from student_reg where user='$user' and pass='$pass'");
    if (mysql_num_rows($result)==0)
	$status = -1;
    else if ($row = mysql_fetch_array($result)) {
	$_SESSION['user'] = $user;
	$_SESSION['tat_id'] = $row['id'];
	$_SESSION['name'] = $row['name'];
	$status = 1;
    }
}
if ($status == 1) 
    echo "Success!";
else {
    echo "<h2>Log In</h2>";
    if ($status == -1)
	echo "Invalid Username/Password!";
    form_ac("tat_login.php",array(
	array("Username", input_ntv("user", "text", $user)),
	array("Password", input_ntv("pass", "password", "")),
	array(input_ntv("submit", "submit", "Log in"))
    ));
}
?>