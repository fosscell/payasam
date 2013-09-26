<?php
require_once("config.php");
require_once("utils.php");
echo "<h2>Sign Up</h2>";
$con=mysql_connect($host,$db_user,$db_password);
if(!$con)
    die("could not connect :".mysql_error());
else
{
    $db=mysql_select_db($db_name,$con);
    if(!$db)
	die("database not found :".mysql_error());
}
$user = ""; $pass = "";
$name = ""; $phone = "";
$clg = ""; $cont2 = "";
if ($_POST["submit"] == "Submit") {
    /*Check for single quotes in password b4 SQL, everywhere b4 form; Add expresssions for con2; Split $error*/
    $user = strtolower(trim($_POST["user"]));
    $pass = $_POST["pass"];
    $name = trim($_POST["name"]);
    $phone = trim($_POST["phone"]);
    $clg = $_POST["clg"];
    $cont2 = trim($_POST["cont2"]);
    $error = "";
    $upat = '/^\w[a-zA-Z0-9._]+$/';
    $npat = '/^[a-zA-Z. ]+$/';
    $ppat = '/^\+?[0-9]{2,7}[- ]?[0-9]{6,8}$/';
    if ($user == "" || !preg_match($upat, $user) || $pass == "" || $name == "" || !preg_match($npat, $name) || $phone == "" || !preg_match($ppat, $phone) || $clg == "")
	$error = "Invalid inputs!";

    if ($error != "") {
	echo "Error: $error";
    } else if (mysql_query("INSERT INTO `student_reg`(`user`, `pass`, `name`, `phone`, `clg_id`, `contact2`) VALUES ('$user', '$pass', '$name', '$phone', '$clg', '$cont2')")) {
	echo "Success!";
	$user = ""; $pass = "";
	$name = ""; $phone = "";
	$clg = ""; $cont2 = "";
    } else {
	echo "SQL Error!";
    }
}
form_ac("tat_signup.php",array(
    array("Username *", input_ntv("user", "text", $user)),
    array("Password *", input_ntv("pass", "password", "")),
    array("Name *", input_ntv("name", "text", $name)),
    array("Phone *", input_ntv("phone", "text", $phone)),
    array("College *", select_nqtv("clg", "select id,name from colleges", "name", "id")),
    array("Email/Roll no", input_ntv("cont2", "text", $cont2)),
    array(input_ntv("submit", "submit", "Submit"))
));
?>