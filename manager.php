<?php
// this is where content is entered for the appropriate event using an HTML editor.
// content from the database will be filled in hidden text boxes and then populated to sections and other
require_once("initdb.php");
$eventcode = "";
if (isset($_SESSION["type"])) {
  // only accessible to managers and proofreaders
  if ($_SESSION["type"] == 'MN' || $_SESSION["type"] == 'PR') {
    if (isset($_SESSION["ecode"]))
      $eventcode = $_SESSION["ecode"];
    else // event code not set!
      _exit("Please go back and try again!");
  } else
    _exit("You don't have permission!");
} else {
  header("Location: $start_page");
  _exit();
}

// back button clicked (visible to only proofreaders)
// so that they can go back and select another event for proofreading.
if (isset($_POST["prback"])) { 
  unset($_SESSION["ecode"]);
  header("Location: $pr_page");
  _exit();
}

// get the content from the database
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
  <script type="text/javascript" src="scripts/jquery.min.js"></script>
  <script type="text/javascript" src="scripts/ajaxupload.js"></script>
  <script type="text/javascript" src="scripts/kaja-input.js"></script>
  <script type="text/javascript"><!--

// remove a section
function remove_sec() {
    $(this).remove();
}

// move a section up
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

// move a section down
function move_sec_down() {
    $(this).insertAfter($(this).next('.desc-sec'));
    $(this).animate({
        height: 'show',
        opacity: 'show'
    }, 400);
}

// get (parent) section in which the DOM object 'e' is in
function get_par_sec(e) {
    return $(e).closest(".desc-sec");
}

// create and add a new section at the bottom (right before the element with id 'new_sec')
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

    // dynamically creating buttons: Remove, Down, Up
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

    //dynamically creating textarea
    var desc_src = document.createElement("textarea");
    new_section.hide();
    new_section.insertBefore(link);
    desc_head.appendTo(new_section);
    $(desc_src).appendTo(new_section);

    //calling new kaja input to make a new section
    new_kaja_input(desc_src);

    // scrolling down to shift view to the newly added section
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

    // creating the intro section which is the default section  
    new_kaja_input($("#intro"));
    $("#new_sec").click(function () {
        new_desc_sec();
    });
    
    $("#event_form").submit(function () {
        $("#con_disp").get(0).value=null;
        $("#pr_disp").get(0).value=null;
        
        // collecting and joining content from different contacts fields in the following format into the hidden input area for contact
        // <name>||@||<contact>||@||<email>||0||<name>||@||<contact>||@||<email>||0|| ..        
        $(".con").each(function(index) {
            $("#con_disp").get(0).value += $(this).find(".na").val()+"||@||"+$(this).find(".co").val()+"||@||"+$(".em").val()+"||0||";
        });

        // populating prize html content into the hidden input area for prize
        $("#pr_disp").get(0).value = $(this).find("#pr").val();

        // collecting and joining content from min and max participation in the following format into the hidden input area for participation
        // <name>||@||<contact>||@||<email>||0||<name>||@||<contact>||@||<email>||0|| ..        
        $("#prtpnt").get(0).value=$(this).find("#par_min").val()+"||@||"+$(this).find("#par_max").val(); 
        
        // 
        var desc_hid = $("#desc").get(0);
        desc_hid.value = $("#intro").val();
        $(".desc-sec").each(function(index) {
            $("#desc").get(0).value += "||sec||" + $(this).find("input").val() + "||ttl||" + $(this).find("textarea").val();
        });
        desc_hid.value = desc_hid.value.replace(/'/g, "&#39;").replace(/\u2013/g, "&#8211;");
        return true;
    });

    // format: <title>||ttl||<body>||sec||<title>||ttl||<body>||sec||
    // #desc contains complete event description, divided into sections using separator ||sec||
    var descs = $("#desc").get(0).value.split("||sec||");
    if (descs.length > 0) {
        update_preview($("#intro").val(descs[0]).get(0));
        if (descs.length > 1) {
            var sec_data, i;
            for (i = 1; i<descs.length; i++) {
            sec_data = descs[i].split("||ttl||"); // section: "<title>||ttl||<body>"
            new_desc_sec(sec_data[0], sec_data[1]);
            }
        }
    }
    
    // Fill prizes and contacts text boxes from #pr_disp and #con_disp
    var prv = $("#pr_disp").get(0).value;
	$("#pr").get(0).value=prv;
	var conv=$("#con_disp").get(0).value;
    var cons=conv.split("||0||");
    if (conv && cons.length>0) {
        var cnt,j,k;
        for(j=0;j<3;j++) {
            cnt=cons[j].split("||@||");
            k=j+1;
            $("#na"+k).get(0).value=cnt[0];
            $("#co"+k).get(0).value=cnt[1];
        }
        $(".em").get(0).value=cnt[2];
    }
    
    // Fill participation limits (min & max) from #prtpnt
    var prtpnt=$("#prtpnt").get(0).value;
    if (prtpnt!=1) {
        pnt=prtpnt.split("||@||");
        $("#par_min").get(0).value=pnt[0];
        $("#par_max").get(0).value=pnt[1];
    } else if(prtpnt==1) {
        $("#par_min").get(0).value=1;
        $("#par_max").get(0).value=1;
    }
});
  //-->
  </script>
</head>

<body>

  <div class="left">
   <!-- If proofreader then 'Go back' option is present so as to allow the proofreader
        to go back and switch the event content to proofread--> 
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
        <!-- Hidden input where contact information from the database is populated -->
        <input type="hidden" name="contacts" id="con_disp" value="<?php echo $contacts;?>" />
      </div>

      <div>
        <h4>Prizes:</h4>

        <table>
          <tr>
            <td><textarea id="pr" style="width:150px"></textarea></td>
          </tr>

        </table>
        <!-- Hidden input where the prizes information from the database is populated -->
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
          <!-- Hidden input where the participant information from the database is populated -->
          <input type="hidden" name="prtpnt" id="prtpnt" value="<?php echo $prtpnt;?>" />

        </table> 

      </div>

      <!-- This hidden text is where the longdesc from the database is populated.-->
      <!-- This content is then split and populated into various sections by creating them dynamically-->  
      <!-- All of which is done using Jquery and Javascript-->    
      <input type="hidden" id="desc" name="longdesc" value="<?php echo str_replace('"', '&quot;', $longdesc);?>" />

      <center>
        <!-- update button -->
        <input name="update" type="submit" value="Update" />
      </center>
    </form>

  </div>

  <div class="main">
    <h2>Introduction</h2>
    <textarea id="intro" name="intro"></textarea>
    <!-- button for adding new sections -->  
    <a href="javascript:void(0)" id="new_sec">+section</a>
  </div>
</body>
</html>
