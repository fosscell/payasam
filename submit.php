<?php
// this page displays a view of the content entered for the event
require_once("initdb.php");
$eventcode = NULL; $redirect = TRUE;
// redirect to start page if session for proofreader/manager is not set
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] == 'MN' || $_SESSION["type"] == 'PR') {
        if (isset($_SESSION["ecode"])) {
            $eventcode = $_SESSION["ecode"];
            $redirect = FALSE;
        } else
            header("Location: logout.php");
    } else
	header("Location: logout.php");
} else
    header("Location: $start_page");

if ($redirect)
    _exit();

// All from data sent from manager.php is received and assigned to variables 
if ($eventcode && isset($_POST['update'])) {
    //single quotes replaced.
    $eventname=str_replace("'","&#39;",$_POST['ename']);
    $shortdesc=str_replace("'","&#39;",$_POST['shortdesc']);
    $tags=$_POST['tags'];
    $contacts=$_POST['contacts'];
    $prizes=$_POST['prizes'];
    $longdesc=$_POST['longdesc']; //single quotes - replaced with javascript
    $prtpnt=$_POST['prtpnt'];
}
else
    _exit("Please go back and try again!");

//inserting/updating into the database and thereby reflecting the changes made to the content
$query="SELECT 1 FROM events WHERE code='$eventcode'";
$result=$mysqli->query($query);
//if the event is populated for the first time then INSERT SQL command is used.
if($result->num_rows == 0)
{
    $query="INSERT INTO events VALUES ('$eventcode', '$eventname', NULL, '$shortdesc', '$longdesc', '$tags', '$contacts', '$prizes', '$prtpnt', 0)";
    if ($mysqli->query($query))
        echo "<p class='col'>Successfully Inserted!</p>";
    else
        echo "<p class='col'>Update failed:</p>".$mysqli->error;
}
// UPDATE SQL command is used otherwise
 else {
    $query="UPDATE events SET name='$eventname',shortdesc='$shortdesc',longdesc='$longdesc',tags='$tags',contacts='$contacts',prize='$prizes',prtpnt='$prtpnt' WHERE code='$eventcode'";
    if ($mysqli->query($query))
        echo "<p class='col'>Successfully updated!</p>";
    else
        echo "<p class='col'>Update failed:</p>".$mysqli->error;
}
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Updated!</title>

<link rel="shortcut icon" href="taticon.png" type="image/png"/>
<link href="style/submit_style.css" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="scripts/jquery.min.js"></script>
<script type="text/javascript">
var desc;
$(document).ready(function () {
  // the longdesc data is split using the section separator ||sec|| into an array
  desc = $("#data").get(0).value.split("||sec||");
  desc[0] = ["Introduction", desc[0]];
  var i;
  for (i = 1; i < desc.length; i++)
    desc[i] = desc[i].split("||ttl||");
  desc[i] = ["Contacts"];
  // the content is split using ||0|| into various contacts
  // each contact is split using ||@|| into name, contact and email respectively
  var con = $("#contact").get(0).value.split("||0||");
  if (con.length > 0) {
    var cnt, j;
    desc[i][1] = "";
    for (j = 0; j < 3; j++) {
      cnt = con[j].split("||@||");
      if (cnt[0]) {
        desc[i][1] += cnt[0] + "<br/>";
        desc[i][1] += "Event Manager<br/>";
        desc[i][1] += "Phone:+91" + cnt[1] + "<br/>";
        desc[i][1] += "E-mail:" + cnt[2] + "@website.com<br/><br/>";
      }
    }
  }
  i++;
  desc[i] = ["Prizes", $("#prize").get(0).value];
  i++;
  desc[i] = ["Participation Limits"];
  var parpnt = $("#prtpnt").get(0).value.split("||@||");
  if (parpnt.length > 0) {
    desc[i][1] = "<br/><p>Min:" + parpnt[0] + "</p>";
    desc[i][1] += "<p>Max:" + parpnt[1] + "</p>";
  }
  i++;

  for (i = desc.length-1; i >= 0; i--)
    $("#menu").prepend("<li><a class='section' rel='sec-" + i + "' href='javascript:;'>" + desc[i][0] + "</a></li>");

  $("#menu").on("click", "a.section", function() {
    var i = $(this).attr("rel").substr(4);
    $("#subhead").html(desc[i][0]);
    $("#content").html(desc[i][1]);
    return false;
  });
});
</script>

</head>
<body>
    <h1><?php echo $eventname;?></h1>
    <ul id="menu">
        <li><a href="manager.php">Return</a></li>
        <li><a href="logout.php">Log out</a></li>
    </ul>
    <div id="wrapper">
        <h2 id="subhead"></h2>
        <div id="content"></div>
    </div>
    <!-- hidden input fields for populating data from the form data sent from manager.php -->
    <!-- the data is split and populated into dynamically created divs from these hidden input fields -->
    <input type="hidden" id="data" value="<?php echo str_replace(array('"',"&#39;"), array('&quot;','&amp;#39;'), $longdesc);?>"/>
    <input type="hidden" id="contact" value="<?php echo $contacts;?>"/>
    <input type="hidden" id="prize" value="<?php echo $prizes;?>"/>
    <input type="hidden" id="prtpnt" value="<?php echo $prtpnt;?>"/>
</body>

</html>
