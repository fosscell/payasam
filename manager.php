<?php
require_once("initdb.php");
$eventcode = "";
if (isset($_SESSION["type"])) {
  if ($_SESSION["type"] == 'MN' || $_SESSION["type"] == 'PR') {
    if (isset($_SESSION["ecode"]))
      $eventcode = $_SESSION["ecode"];
    else
      _exit("Please go back and try again!");
  } else
    _exit("You don't have permission!");
} else {
  header("Location: $start_page");
  _exit();
}

if (isset($_POST["prback"])) {
  unset($_SESSION["ecode"]);
  header("Location: $pr_page");
  _exit();
}

$query="SELECT name,shortdesc,longdesc,tags,contacts,prize,prtpnt FROM events WHERE code='$eventcode'";
$result=$mysqli->query($query);
$row=$result->fetch_assoc();
$eventname=NULL; $shortdesc=NULL; $longdesc=NULL;
$tags=NULL; $contacts=NULL; $prize=NULL;
if($row)
{
    $eventname=$row['name'];
    $shortdesc=$row['shortdesc'];
    $longdesc=$row['longdesc'];
    $tags=$row['tags'];
    $contacts=$row['contacts'];
    $prize=$row['prize'];
    $prtpnt=$row['prtpnt'];
    $result->free();
}
$mysqli->close();
?>
<!DOCTYPE html>

<html>
<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

  <title>Tathva 12 CMS: Event Management</title>
  <link rel="shortcut icon" href="taticon.png" type="image/png" />
  <link rel="stylesheet" href="style/manager.css" type="text/css" media="all" />
  <script type="text/javascript" src="jquery.min.js"></script>
  <script type="text/javascript" src="ajaxupload.js"></script>
  <script type="text/javascript" src="kaja-input.js"></script>
  <script type="text/javascript"><!--
function remove_sec() {
    $(this).remove();
}

function move_sec_up() {
    var next = $(this).next('.desc-sec');
    var mover = $(this).prev('.desc-sec');
    if (next.length == 0 || mover.offset().top + $(this).height() > $(document).scrollTop() + $(window).height() - 200) $('body,html').animate({
        scrollTop: '+=' + $(this).height()
    }, 400);
    $(this).insertBefore(mover);
    $(this).animate({
        height: 'show',
        opacity: 'show'
    }, 400);
}

function move_sec_down() {
    $(this).insertAfter($(this).next('.desc-sec'));
    $(this).animate({
        height: 'show',
        opacity: 'show'
    }, 400);
}

function get_par_sec(e) {
    return $(e).closest(".desc-sec");
}

function new_desc_sec(title, content) {
    var link = $("#new_sec");
    var new_section = $("<div/>", {
        class: "desc-sec"
    });
    var desc_head = $("<span/>", {
        html: "Section Title: ",
        class: "desc-head"
    });
    var sec_ttl = $("<input type='text' />").appendTo(desc_head); //$(..).attr({name: 'xyz'})
    $("<span/>", {
        html: "Remove",
        class: "desc-but"
    }).click(function () {
        get_par_sec(this).hide(400, remove_sec);
    }).appendTo(desc_head);
    $("<span/>", {
        html: "Down",
        class: "desc-but"
    }).appendTo(desc_head).click(function () {
        var par_sec = get_par_sec(this);
        par_sec.next('.desc-sec').animate({
            height: 'hide',
            opacity: 'hide'
        }, 400, move_sec_up);
    });
    $("<span/>", {
        html: "Up",
        class: "desc-but"
    }).appendTo(desc_head).click(function () {
        var par_sec = get_par_sec(this);
        par_sec.prev('.desc-sec').animate({
            height: 'hide',
            opacity: 'hide'
        }, 400, move_sec_down);
    });
    var desc_src = document.createElement("textarea");
    new_section.hide();
    new_section.insertBefore(link);
    desc_head.appendTo(new_section);
    $(desc_src).appendTo(new_section);
    new_kaja_input(desc_src);
    $('body,html').animate({
        scrollTop: '+=' + new_section.height()
    }, 400);
    new_section.show(400);
    if (title) {
        sec_ttl.val(title);
        if (content) {
            desc_src.value = content;
            update_preview(desc_src);
        }
    } else sec_ttl.focus();
}
$(document).ready(function () {
    new_kaja_input($("#intro"));
    $("#new_sec").click(function () {
        new_desc_sec();
    });
    
    $("#event_form").submit(function () {
	$("#con_disp").get(0).value=null;
	$("#pr_disp").get(0).value=null;
	
	$(".con").each(function(index) {
	    $("#con_disp").get(0).value += $(this).find(".na").val()+"||@||"+$(this).find(".co").val()+"||@||"+$(".em").val()+"||0||";
	});

	$("#pr_disp").get(0).value = $(this).find("#pr").val();
    
	$("#prtpnt").get(0).value=$(this).find("#par_min").val()+"||@||"+$(this).find("#par_max").val(); 
	
	var desc_hid = $("#desc").get(0);
	desc_hid.value = $("#intro").val();
	$(".desc-sec").each(function(index) {
	    $("#desc").get(0).value += "||sec||" + $(this).find("input").val() + "||ttl||" + $(this).find("textarea").val();
	});
	desc_hid.value = desc_hid.value.replace(/'/g, "&#39;").replace(/\u2013/g, "&#8211;");
	return true;
    });
    //Filling descriptions
    var descs = $("#desc").get(0).value.split("||sec||");
    if (descs.length > 0) {
	update_preview($("#intro").val(descs[0]).get(0));
	if (descs.length > 1) {
	    var sec_data, i;
	    for (i = 1; i<descs.length; i++) {
		sec_data = descs[i].split("||ttl||");
		new_desc_sec(sec_data[0], sec_data[1]);
	    }
	}
    }
    // Filling prizes and contacts
    var prv = $("#pr_disp").get(0).value;
 
	$("#pr").get(0).value=prv;

	var conv=$("#con_disp").get(0).value;
    var cons=conv.split("||0||");
    if(conv && cons.length>0)
    {
	var cnt,j,k;
	for(j=0;j<3;j++)
	{
	    cnt=cons[j].split("||@||");
	    k=j+1;
	    $("#na"+k).get(0).value=cnt[0];
	    $("#co"+k).get(0).value=cnt[1];
	}
    	$(".em").get(0).value=cnt[2];
    }
    
    var prtpnt=$("#prtpnt").get(0).value;
    if(prtpnt!=1)
    {
	pnt=prtpnt.split("||@||");
	$("#par_min").get(0).value=pnt[0];
	$("#par_max").get(0).value=pnt[1];
    }
    else if(prtpnt==1)
    {
        $("#par_min").get(0).value=1;
	    $("#par_max").get(0).value=1;
    }
});
  //-->
  </script>
</head>

<body>

  <div class="left">
	<?php if ($_SESSION["type"] == 'PR') { ?>
	<form method="post" action="manager.php" name="back_form">
	  <input type="submit" style="float:right" name="prback" value="Go Back" >
	</form>
	<?php } ?>
	<h4>Event Code: <?php echo $eventcode; ?></h4>
	<a style="float:right" href="logout.php">Log out</a>
    <form method="post" action="submit.php" id="event_form" name="event_form">
      <h4>Event Name:</h4>
      <input type="text" name="ename" value="<?php echo $eventname; ?>" >
      <h4>Short Description</h4>
      <textarea name="shortdesc"><?php echo $shortdesc; ?></textarea>

      <h4>Tags</h4>
      <textarea name="tags"><?php echo $tags;?></textarea>

      <div class="wrapper">
        <h4>Contacts:</h4>
        <h5>Event email-id</h5>
        <div class="email">
          <div style="float:right; padding: 3px">@tathva.org</div>
          <div style="margin-right: 96px"><input type="text" class="em" id="em3" placeholder="eMail" /></div>
        </div>

        <div class="con">
          <h5>Manager 1 <small>(preferred contact for queries)</small></h5>
          <input type="text" class="na" id="na1" placeholder="Name" />
          <div style="float:left; padding: 3px">+91</div>
          <div style="margin-left: 36px"><input type="text" class="co" id="co1" placeholder="Contact Number" /></div>

        </div>

        <div class="con">
          <h5>Manager 2</h5>
          <input type="text" class="na" id="na2" placeholder="Name" />
          <div style="float:left; padding: 3px">+91</div>
          <div style="margin-left: 36px"><input type="text" class="co" id="co2" placeholder="Contact Number" /></div>

        </div>

        <div class="con">
          <h5>Manager 3</h5>
          <input type="text" class="na" id="na3" placeholder="Name" />
          <div style="float:left; padding: 3px">+91</div>
          <div style="margin-left: 36px"><input type="text" class="co" id="co3" placeholder="Contact Number" /></div>

        </div>

        <input type="hidden" name="contacts" id="con_disp" value="<?php echo $contacts;?>" />
      </div>

      <div>
        <h4>Prizes:</h4>

        <table>
          <tr>
            <td><textarea id="pr" style="width:150px"></textarea></td>
          </tr>

        </table>
        <input type="hidden" name="prizes" id="pr_disp" value="<?php echo $prize;?>" />
      </div>

      <div id="num_par">
       <h4>No of participants</h4>
       <table>
          <tr>
            <td style="text-align: right">Min: </td>
            <td> <input id="par_min" type="text" style="width:150px"/></td>
          </tr>
          <tr>
            <td style="text-align: right">Max: </td>
            <td> <input id="par_max" type="text" style="width:150px"/></td>
          </tr>
          <input type="hidden" name="prtpnt" id="prtpnt" value="<?php echo $prtpnt;?>" />

        </table> 

      </div>

      <input type="hidden" id="desc" name="longdesc" value="<?php echo str_replace('"', '&quot;', $longdesc);?>" />

      <center>
        <input name="update" type="submit" value="Update" />
      </center>
    </form>

  </div>

  <div class="main">
    <h2>Introduction</h2>
    <textarea id="intro" name="intro"></textarea>
    <a href="javascript:void(0)" id="new_sec">+section</a>
  </div>
</body>
</html>
