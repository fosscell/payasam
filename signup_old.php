<?php

require_once("initdb.php");

$msg = "";

if (isset($_SESSION['type'])) {

  switch ($_SESSION['type']) {

    case 'MN':

      header("Location: $mn_page");

      _exit();

    case 'PR':

      header("Location: $pr_page");

      _exit();

    case 'AD':

      header("Location: $ad_page");

      _exit();

    case 'ML':

      header("Location: $ml_page");

      _exit();

  }

}

if (isset($_POST["signup"])) {

    $s = 0;

    if ($_POST["type"] == "mn") {

	if (TRUE === $mysqli->query("INSERT INTO `managers` VALUES ('$_POST[ecode]', '$_POST[uname]', '$_POST[pass]', 0)")) {

	    if (TRUE === $mysqli->query("INSERT INTO `events`(`code`, `name`, `cat_id`) VALUES ('$_POST[ecode]', '".str_replace("'","&#39;",$_POST['ename'])."', '$_POST[category]')")) {

		$msg = "<span class='color'>Manager signup was successful!</span> ";

		$s = 1;

	    } else

		$mysqli->query("DELETE FROM managers WHERE username='$_POST[uname]'");

	}

    } else if (TRUE === $mysqli->query("insert into managers values ('-pr', '$_POST[uname]', '$_POST[pass]', 0)")) {

	$msg = "<span class='color'>Proofreader signup was successful!</span> ";

	$s = 1;

    }

    if ($s == 1)

	$msg .= "<span class='color'>Please wait till an administrator validates your account.</span>";

    else

	$msg = "<span class='color'>Signup failed</span>";

    

} else if (isset($_POST["signin"])) {

    $user = $mysqli->real_escape_string($_POST['username']);

    $pass = $mysqli->real_escape_string($_POST['password']);

    if ($user == "admin" && $pass == "12tathva12") {

	$_SESSION['type'] = 'AD';

	header("Location: $ad_page");

	_exit();

    } else if ($user == "mailer" && $pass == "tmailer") {

	$_SESSION['type'] = 'ML';

	header("Location: $ml_page");

	_exit();

    }

    $res = $mysqli->query("select eventcode, validate from managers where username='$user' and password='$pass'");

    if ($res->num_rows == 0)

	$msg = "<span class='color'>Invalid Username or Password!</span>";

    else {

	$row = $res->fetch_assoc();

	if ($row['validate'] == 0)

	    $msg = "<span class='color'>Your account needs to be validated!.</span>";

	else {

	    $_SESSION['uname'] = $user;

	    if ($row['eventcode'] == '-pr') {

		$_SESSION['type'] = 'PR';

		header("Location: $pr_page");

	    } else {

		$_SESSION['type'] = 'MN';

		$_SESSION['ecode'] = $row['eventcode'];

		header("Location: $mn_page");

	    }

	    $res->free();

	    _exit();

	}

    }

}

?>

<!DOCTYPE html>

<html>

<head>

  <meta content="text/html; charset=iso-8859-1" http-equiv="Content-Type">
  <meta name="description" content="System designed for event managers to add and manage event details facilitated with an html editor to enter content into the Tathva'12 website">
  <meta name="keywords" content="tathva content management system,cms">



  <title>Tathva 12 CMS: Start here!</title>

  <link rel="shortcut icon" href="taticon.png" type="image/png">

  <style type="text/css">

  @font-face

  {

  	font-family:Tathva_Cafe;

    src:url("CafeNeroM54.ttf");

	

  }

  body {

    background-image:url("cms.jpg");

    background-size:100%;

  }

  #wrapper {

    display: none;

    width: 100%;

    min-width: 1024px;

    margin: auto;

    text-align: center;

  }

  #sinwrap, #supwrap {

    padding: 10px;

    border: 1px solid gray;

    border-radius: 3px;

    background-color: rgba(0,0,0,0.5);

    color:white;

    margin: 10px;

  }

  #supwrap {

    position:absolute;

    top:250px;

    right:100px;

  }

  #sinwrap {

    position:absolute;

    top:100px;

    right:100px;

  }

  #sinwrap h3, #supwrap h3 {

    margin: 0 0 5px;

  }

  input[type=password], input[type=text] {

    width: 180px;

  }

  .color

  {

	color:white;

	font-weight:bold;

  }

  </style>

  <script type="text/javascript" src="jquery.min.js"></script>

  <script type="text/javascript">

function validatesup() {

  var un=this.uname.value;

  var p=this.pass.value;

  var rp=this.repass.value;

  var t=this.type.value;

  var c=this.category.value;

  var en=this.ename.value;

  var ec=this.ecode.value;

  var unex=/^[a-z]+$/;

  var ecex=/^[A-Z]+$/;



  if (!un || !p || !rp || (t == "mn" && (!c || !en || !ec))) {

    alert("Please fill in all the fields!");

    return false;

  }

  if (!un.match(unex)) {

    alert("Username should contain only lowercase alphabets.");

    return false;

  }

  if (p != rp) {

    alert("Passwords don't match");

    return false; 

  }

  if(t == "mn" && (ec.length!=3 || !ec.match(ecex))) {

    alert("Event code must be 3 alphabets.");

    return false;

  }

}



function validatesin() {

   var u = this.username.value;

   var p = this.password.value;



   if(!u || !p) {

     alert("Please fill in all the fields");

     return false;

   }

}



$(document).ready(function() {

  $("#acctype").change(function() {

    var c=$(this).val();

    if(c=="mn")

      $("#mn_opts").show();

    else

      $("#mn_opts").hide();

  });

  $("#supform").submit(validatesup);

  $("#sinform").submit(validatesin);

  $("#wrapper").show();

});



  </script>

</head>



<body>

    <noscript>

    <div style="background-color: #FF7777; padding: 20px; font-size: 20px">Please enable Javascript</div>

    </noscript>

    <div id="wrapper">

    <h1 style="color:black; position:relative; top:160px; font-family:Tathva_Cafe; ">CONTENT MANAGEMENT SYSTEM</h1>

    <?php echo $msg; ?>

    <div id="supwrap">

    <form action="signup.php" method="post" id="supform">

	<h3>Not yet a member?</h3>

	<input type="text" name="uname" placeholder="Username"><br/>

	<input type="password" name="pass" placeholder="Password"><br/>

	<input type="password" name="repass" placeholder="Retype password"><br/>

	<select id="acctype" name="type">

	    <option value="mn">Event Manager</option>

	    <option value="pr">Proofreader</option>

	</select><br/>

	<div id="mn_opts">

	    <select name="category">

		<option value="">--event category--</option>

		<?php

$res1 = $mysqli->query("select cat_id, name from event_cats where par_cat=-1");

while($row=$res1->fetch_assoc()) {

    $res2 = $mysqli->query("select cat_id, name from event_cats where par_cat=$row[cat_id]");

    if ($res2->num_rows == 0)

	echo "<option value='$row[cat_id]'>$row[name]</option>";

    else {

	echo "<optgroup label='$row[name]'>";

	while ($erow=$res2->fetch_assoc())

	    echo "<option value='$erow[cat_id]'>$erow[name]</option>";

	echo "</optgroup>";

    }

    $res2->free();

}

$res1->free();

		?>

	    </select><br/>

	    <input type="text" placeholder="Event Name" name="ename"><br/>

	    <input type="text" placeholder="Event Code (3 letters)" name="ecode" onchange="javascript:this.value=this.value.toUpperCase();"><br/>

	</div>

	<input type="submit" name="signup" value="Sign Up">

    </form>

    </div>



    <div id="sinwrap">

    <form action="signup.php" method="post" id="sinform">

	<h3>Login</h3>

	<input type="text" placeholder="Username" name="username"><br/>

	<input type="password" placeholder="Password" name="password"><br/>

	<input type="submit" name="signin" value="Sign In">

    </form>

    </div>

    </div>

</body>

</html>

<?php _exit(); ?>