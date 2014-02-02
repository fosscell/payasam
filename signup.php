<?php
// This is from where everyone signs in, and event managers or proofreaders signs up
// TODO List
// 1. When a user with multiple privileges signs in, he should be redirected to a "menu" page.
//    For example, an event manager should also be given a choice to view his/her event's registration list
//                 this "menu" page may replace proofreader.php
// 2. Admin should hava all privilages.
require_once("initdb.php");
$msg = "";
// if a session exists then redirect to the respective page
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
  case 'CL':
    header("Location: cl.php");
    _exit();
  }
}
$erlist = "";
// Sign up button clicked
if (isset($_POST["signup"])) { 
  $s = 0;
  // if type is manager  
  if ($_POST["type"] == "mn") {
    // if an event manager signs up then insert into the MANAGERS table and if it is successful then insert into the EVENTS table     
    if (TRUE === $mysqli->query("INSERT INTO `managers` VALUES ('$_POST[ecode]', '$_POST[uname]', '$_POST[pass]', 0)")) {
      // converting single quote into the corresponding ascii code      
      if (TRUE === $mysqli->query("INSERT INTO `events`(`code`, `name`, `cat_id`) VALUES ('$_POST[ecode]', '".str_replace("'","&#39;",$_POST['ename'])."', '$_POST[category]')")) {
        $msg = "<span class='color'>Manager signup was successful!</span> ";
        $s = 1;
      } else
        // if populating the EVENTS table failed then the corresponding entry in the MANAGERS table is deleted.      
        $mysqli->query("DELETE FROM managers WHERE username='$_POST[uname]'");
    }
  // if type is proofreader
  } else if (TRUE === $mysqli->query("insert into managers values ('-$_POST[type]', '$_POST[uname]', '$_POST[pass]', 0)")) {
    $msg = "<span class='color'>Proofreader signup was successful!</span> ";
    $s = 1;
  }
  if ($s == 1) // if signup is succesful
    $msg .= "<span class='color'>Please wait till an administrator validates your account.</span>";
  else    
    $msg = "<span class='color'>Signup failed</span>";
// Sign in button clicked 
} else if (isset($_POST["signin"])) { 
  // real escaping strings to avoid SQL injection
  $user = $mysqli->real_escape_string($_POST['username']);
  $pass = $mysqli->real_escape_string($_POST['password']);
  // different login sessions
  if ($user == $admin && $pass == $password) {
    $_SESSION['type'] = 'AD';
    header("Location: $ad_page");
    _exit();
  }
  // execution reached here means it could be an event manager/proofreader trying to log in
  $res = $mysqli->query("select eventcode, validate from managers where username='$user' and password='$pass'");
  if ($res->num_rows == 0)
    $msg = "<span class='color'>Invalid Username or Password!</span>";
  else {
    $row = $res->fetch_assoc();
    if ($row['validate'] == 0) {
      $msg = "<span class='color'>Your account needs to be validated!.</span>";
      $erlist = $row['eventcode'];
      if ($erlist == '-pr') $erlist = '';
    } else { // only a validated account can be used for editing event details
      $_SESSION['uname'] = $user;
      if ($row['eventcode'] == '-pr') {
        // redirect to the proofreader page
        $_SESSION['type'] = 'PR';
        header("Location: $pr_page");
      }
      else if($row['eventcode'] == '-cl') {
        //redirect to the colleges page
        $_SESSION['type'] = 'CL';
        header("Location: cl.php");
      }
      else if($row['eventcode'] == '-ml') {
        //redirect to the mailer page
        $_SESSION['type'] = 'ML';
        header("Location: $ml_page");
      }
      else {
        // redirect to the manager page with the corresponding event code set
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

  <title>Payasam CMS: Start here!</title>
  <link rel="shortcut icon" href="taticon.png" type="image/png">
  <link href='style/signup.css' type='text/css' rel='stylesheet'>
  <script type="text/javascript" src="scripts/jquery.min.js"></script>
  <script type="text/javascript">
// form validation for signup
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
// form validation for signin
function validatesin() {
  var u = this.username.value;
  var p = this.password.value;

  if(!u || !p) {
    alert("Please fill in all the fields");
    return false;
  }
}

$(document).ready(function() {
  // hide/show fields according to the type of account that is created (event manager/proofreader/colleges/mailer)  
  $("#acctype").change(function() {
  var c=$(this).val();
  if(c=="mn")
    $("#mn_opts").show();
  else
    $("#mn_opts").hide();
  });
  // call the corresponding functions upon submission of the respective signup and signin forms  
  $("#supform").submit(validatesup);
  $("#sinform").submit(validatesin);
  $("#wrapper").show();
});

  </script>
</head>

<body>
  <?php
// When in 'proofreading' phase, manager accounts are invalidated.
// After that, he/she, when tries to sign in, will be provided with
// the event registration list of his/her event.
if ($erlist) {
  $res = $mysqli->query("SELECT name FROM events WHERE code='$erlist'");
  if ($row=$res->fetch_assoc()) {
    $erlname = $row['name'];
    $res->free();
    echo "<div id='erlist'><h3 style='margin:10px 0'>$erlname Registration List</h3>";
  ?>
  <table>
    <tr><th>Team ID</th><th>ID</th><th>Name</th><th>Phone no.</th><th>eMail</th><th>College</th></tr>
    <?php
    $res = $mysqli->query("SELECT e.team_id, e.tat_id, s.name as name, s.phone, s.email, c.name as clg FROM event_reg e INNER JOIN student_reg s ON e.tat_id=s.id INNER JOIN colleges c ON s.clg_id=c.id WHERE e.code='$erlist'");
    while($row=$res->fetch_assoc())
      echo "<tr><td>$row[team_id]</td><td>$row[tat_id]</td><td>$row[name]</td><td>$row[phone]</td><td>$row[email]</td><td>$row[clg]</td></tr>";
    ?>
  </table></div>
  <?php
    $res->free();
  } else $res->free();
}
  ?>
  <noscript>
  <div style="background-color: #FF7777; padding: 20px; font-size: 20px">Please enable Javascript</div>
  </noscript>
  <div id="wrapper">
  <?php echo $msg; ?>
  <div id="title"><span>Payasam</span><br/>Content Management System</div>
  <div id="sinwrap">
  <form action="signup.php" method="post" id="sinform">
    <h3>Login</h3>
    <input type="text" placeholder="Username" name="username"><br/>
    <input type="password" placeholder="Password" name="password"><br/>
    <input type="submit" name="signin" value="Sign In">
  </form>
  </div>  
  <div id="supwrap">
  <form action="signup.php" method="post" id="supform">
    <h3>Not yet a member?</h3>
    <input type="text" name="uname" placeholder="Username"><br/>
    <input type="password" name="pass" placeholder="Password"><br/>
    <input type="password" name="repass" placeholder="Retype password"><br/>
    <select id="acctype" name="type">
      <option value="mn">Event Manager</option>
      <option value="pr">Proofreader</option>
      <option value="cl">Colleges</option>
      <option value="ml">Mailer</option>      
    </select><br/>
    <div id="mn_opts">
      <select name="category">
        <option value="">--event category--</option>
        <?php
// populate with event categories from database
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
  </div>
  <div id='footer'>Code opensourced on <a href='https://github.com/fosscell/payasam'>github</a> | Credits: John C F & John P Joseph</div>
</body>
</html>
<?php _exit(); ?>