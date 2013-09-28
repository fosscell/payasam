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
<title>Update</title>

<link rel="shortcut icon" href="taticon.png" type="image/png"/>
<!-- CSS STYLES -->
<style type="text/css">

.col
{
	color:#777;
}
#mid
{
    width:600px;
	height:315px;
	margin:auto;
	color:black;
	font-size:x-large;
	text-align:center;
}

#text
{
	height:342px;
	top:20px;
}
img
{
	opacity:0.5;
}
</style>
<link rel="shortcut icon" href="taticon.png" type="image/png"/>

<link href="style/submit_style.css" rel="stylesheet" type="text/css"/>

<!-- styles needed by jScrollPane -->
<link href="style/jquery.jscrollpane.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="scripts/jquery.min.js"></script>

<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="scripts/jquery.mousewheel.js"></script>

<!-- the jScrollPane script -->
<script type="text/javascript" src="scripts/jquery.jscrollpane.min.js"></script>

<script type="text/javascript">
$(document).ready(function () {
  // the longdesc data is split using the section separator ||sec|| into an array
  var desc = $("#data").get(0).value.split("||sec||");
  var i, sec;
  // dynamically creating divs for populating the title and content for each section
  // the number of divs created is equal to the number of splits done using the ||sec|| separator
  for (j = 0; j <= (desc.length + 2); j++) {
    $("<div/>", {
      "id": "lnk" + j
    }).appendTo("#link");
    $("<div/>", {
      "id": "cnt" + j
    }).appendTo("#content");
  }

  // function to handle css effects on clicking a title/link
  // the appropriate content for the title is also displayed hiding the rest
  $("#link>div").click(function () {
    // the title lnk<number> has content in cnt<number>
    // the <number> of the the lnk<number> is obtained by splitting with separator lnk
    // the <number> would be stored in i[1]   
    var i = $(this).attr("id").split("lnk");
    var t;
    if (!($(this).hasClass("link_select"))) {
      $("#link>div").removeClass("link_select");
      $(this).addClass("link_select");
      $("#link>div").css({
        "color": "#777",
      });
      $(this).css({
        "color": "#555",
      });
      // all the contents except the one clicked are hidden
      for (t = 0; t <= (desc.length + 2); t++) {
        if (t != i[1]) {
          $("#cnt" + t).hide();
        }
      }
      // content for the corresponding <number> is shown
      $("#cnt" + i[1]).show();
      $("#text").jScrollPane({
        showArrows: true
      });
    }

  });

  // functions to handle mouseover and mouseout on the link

  $("#link>div").mouseover(function () {
    if (!($(this).hasClass("link_select"))) {
      $(this).css({
        "color": "#555",
        "cursor": "pointer"
      });
    }
  });

  $("#link>div").mouseout(function () {
    if (!($(this).hasClass("link_select"))) {
      $(this).css({
        "color": "#777"
      });
    }
  });

  // default content is populated
  $("#lnk0").append("<p><b>INTRODUCTION</b></p>");
  $("#cnt0").append("<p>" + desc[0] + "</p>");
  $("#text").jScrollPane({
    showArrows: true
  });

  // note that all the sections were split using the separator ||sec|| and stored into an array desc
  // desc is further split using ||ttl|| into title(sec[0]) and content(sec[1]) for that section
  for (i = 1; i < desc.length; i++) {
    sec = desc[i].split("||ttl||");
    $("#lnk" + i).append("<p><b>" + sec[0].toUpperCase() + "</b></p>");
    $("#cnt" + i).append("<p>" + sec[1] + "</p>").hide();
  }
  var k = desc.length;

  // a div is dynamically created for storing the content for prizes and hidden
  var prize = $("#prize").get(0).value;
  if (prize.length > 0) {
    $("#cnt" + k).append("<br/>" + prize);
  }
  $("#cnt" + k).hide();

  // a div is dynamically created for storing the content for contacts and hidden
  // the content is split using ||0|| into various contacts
  // each contact is split using ||@|| into name, contact and email respectively
  var con = $("#contact").get(0).value.split("||0||");
  if (con.length > 0) {
    var cnt, j, k;
    for (j = 0; j < 3; j++) {
      cnt = con[j].split("||@||");
      if (cnt[0]) {
        $("#cnt" + (k + 1)).append(cnt[0] + "<br/>");
        $("#cnt" + (k + 1)).append("Event Manager<br/>");
        $("#cnt" + (k + 1)).append("Phone:+91" + cnt[1] + "<br/>");
        $("#cnt" + (k + 1)).append("E-mail:" + cnt[2] + "@website.com<br/><br/>");
      }
    }
  }
  $("#cnt" + (k + 1)).hide();

  // a div is dynamically created for storing the min/max participation and hidden
  var parpnt = $("#prtpnt").get(0).value.split("||@||");
  if (parpnt.length > 0) {
    $("#cnt" + (k + 2)).append("<br/><p>Min:" + parpnt[0] + "</p>");
    $("#cnt" + (k + 2)).append("<p>Max:" + parpnt[1] + "</p>");
  }
  $("#cnt" + (k + 2)).hide();

  // on click of prize/contact/participation the other content divs are hidden
  // the respective content is shown and some CSS styles are also changed
  
  $("#wrapper").on("click", "#prize", function () {
    var t;
    $("#link>div").removeClass("link_select");
    $("#link>div").css({
      "background-color": "black",
      "color": "silver",
      "position": "relative",
      "left": 0,
      "width": 164
    });
    for (t = 0; t <= (desc.length + 2); t++) {
      $("#cnt" + t).hide();
    }
    $("#text").jScrollPane({
      showArrows: false
    });
    $("#cnt" + k).show();
  });

  $("#wrapper").on("click", "#contact", function () {
    var t;
    $("#link>div").removeClass("link_select");
    $("#link>div").css({
      "background-color": "black",
      "color": "silver",
      "position": "relative",
      "left": 0,
      "width": 164
    });
    for (t = 0; t <= (desc.length + 2); t++) {
      $("#cnt" + t).hide();
    }
    $("#text").jScrollPane({
      showArrows: false
    });
    $("#cnt" + (k + 1)).show();

  });

  $("#wrapper").on("click", "#participant", function () {
    var t;
    $("#link>div").removeClass("link_select");
    $("#link>div").css({
      "background-color": "black",
      "color": "silver",
      "position": "relative",
      "left": 0,
      "width": 164
    });
    for (t = 0; t <= (desc.length + 2); t++) {
      $("#cnt" + t).hide();
    }
    $("#text").jScrollPane({
      showArrows: false
    });
    $("#cnt" + (k + 2)).show();
  });
});
</script>

</head>
<body>
    <p><a class="col" href="manager.php">Return</a></p>
    <p><a class="col" href="logout.php">Log out</a></p>

    <!-- hidden input fields for populating data from the form data sent from manager.php -->
    <!-- the data is split and populated into dynamically created divs from these hidden input fields -->
    <input type="hidden" id="data" value="<?php echo str_replace(array('"',"&#39;"), array('&quot;','&amp;#39;'), $longdesc);?>"/>
    <input type="hidden" id="contact" value="<?php echo $contacts;?>"/>
    <input type="hidden" id="prize" value="<?php echo $prizes;?>"/>
    <input type="hidden" id="prtpnt" value="<?php echo $prtpnt;?>"/>
    <div id="wrapper">
        <div id="heading">
            <p><?php echo $eventname;?></p>
        </div>
        <div id="hd"></div>

        <div id="link">
        </div>

        <div id="midframe">
            <div id="text">
                <div id="content">
                </div>
            </div>
            <div id="register">REGISTER</div>
            <div id="prize">prizes</div>
            <div id="contact">contact</div>
            <div id="participant">participation</div>
        </div>
    </div>
</body>

</html>
