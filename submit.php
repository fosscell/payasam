<?php
require_once("initdb.php");
$eventcode = NULL; $redirect = TRUE;
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

if ($eventcode && isset($_POST['update'])) {
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

$query="SELECT 1 FROM events WHERE code='$eventcode'";
$result=$mysqli->query($query);
if($result->num_rows == 0)
{
    $query="INSERT INTO events VALUES ('$eventcode', '$eventname', NULL, '$shortdesc', '$longdesc', '$tags', '$contacts', '$prizes', '$prtpnt', 0)";
    if ($mysqli->query($query))
	echo "<p class='col'>Successfully Inserted!</p>";
    else
	echo "<p class='col'>Update failed:</p>".$mysqli->error;
} else {
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
<style type="text/css">

.col
{
	color:white;
	
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

#heading p
{
    position:absolute;
    top:0px;
    left:0px;
    height:65px;
    width:566px;
    text-align:center;
    margin:0px;
    font-size:32px;
    padding-top:12px;
    color:white;
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

<link href="submit_style.css" rel="stylesheet" type="text/css"/>

<!-- styles needed by jScrollPane -->
<link type="text/css" href="jquery.jscrollpane.css" rel="stylesheet" media="all" />

<script type="text/javascript" src="jquery.min.js"></script>
<!-- the mousewheel plugin - optional to provide mousewheel support -->
<script type="text/javascript" src="jquery.mousewheel.js"></script>

<!-- the jScrollPane script -->
<script type="text/javascript" src="jquery.jscrollpane.min.js"></script>

<script type="text/javascript">

$(document).ready(
function()
{

var desc=$("#data").get(0).value.split("||sec||");
var i,sec;
for(j=0;j<=(desc.length+2);j++)
{

 $("<div/>",{"id":"lnk"+j}).appendTo("#link");
 $("<div/>",{"id":"cnt"+j}).appendTo("#content");
}

$("#link>div").click(function(){
	
		 var i=$(this).attr("id").split("lnk");
		 var t;
		 if(!($(this).hasClass("link_select")))
		 {
		 $("#link>div").removeClass("link_select");
		 $(this).addClass("link_select");
		 $("#link>div").css({"background-color":"black","color":"silver","position":"relative","left":0,"width":164});
		 $(this).css({"background-color":"white","color":"black","position":"relative","left":-10,"width":174});
		 for(t=0;t<=(desc.length+2);t++)
		 {
			 if(t!=i[1])
			 {
			   	$("#cnt"+t).hide();
			 }
		 }
		 $("#cnt"+i[1]).show();
		 $("#text").jScrollPane({showArrows: true});
		 }
		   	
	});

    $("#link>div").mouseover(function(){
		if(!($(this).hasClass("link_select")))
		{	
		    $(this).css({"color":"white","cursor":"pointer"});
		}
		});
		
	$("#link>div").mouseout(function(){
		if(!($(this).hasClass("link_select")))
		{	
	    	$(this).css({"color":"silver"});
		}
		
		});
		
$("#lnk0").append("<p><b>INTRODUCTION</b></p>");
$("#cnt0").append("<p>"+desc[0]+"</p>");
$("#text").jScrollPane({showArrows: true});
for(i=1;i<desc.length;i++)
{
 sec=desc[i].split("||ttl||");
 $("#lnk"+i).append("<p><b>"+sec[0].toUpperCase()+"</b></p>");
 $("#cnt"+i).append("<p>"+sec[1]+"</p>").hide();
}
 var k=desc.length;
 
 var prize=$("#prize").get(0).value;
	if(prize.length>0)
	{
	$("#cnt"+k).append("<br/>"+prize);
   	}
    $("#cnt"+k).hide();
 
 var con=$("#contact").get(0).value.split("||0||");
   if(con.length>0)
	{
	var cnt,j,k;
	for(j=0;j<3;j++)
	{
	cnt=con[j].split("||@||");
	if(cnt[0])
	{
	$("#cnt"+(k+1)).append(cnt[0]+"<br/>");
	$("#cnt"+(k+1)).append("Event Manager<br/>");
	$("#cnt"+(k+1)).append("Phone:+91"+cnt[1]+"<br/>");
	$("#cnt"+(k+1)).append("E-mail:"+cnt[2]+"@tathva.org<br/><br/>");
	}
	}
	}
	$("#cnt"+(k+1)).hide();
	
 var parpnt=$("#prtpnt").get(0).value.split("||@||");
	if(parpnt.length>0)
	{
	$("#cnt"+(k+2)).append("<br/><p>Min:"+parpnt[0]+"</p>");
	$("#cnt"+(k+2)).append("<p>Max:"+parpnt[1]+"</p>");
   	}
    $("#cnt"+(k+2)).hide();
	
	
	 $("#wrapper").on("click","#prize",
           function()
           {
           var t;
               $("#link>div").removeClass("link_select");
        	   $("#link>div").css({"background-color":"black","color":"silver","position":"relative","left":0,"width":164});
           for(t=0;t<=(desc.length+2);t++)
            {
             $("#cnt"+t).hide();
            }
            $("#text").jScrollPane({showArrows: false});
            $("#cnt"+k).show();

           });
           
     $("#wrapper").on("click","#contact",
           function()
           {
           var t;
               $("#link>div").removeClass("link_select");
        	   $("#link>div").css({"background-color":"black","color":"silver","position":"relative","left":0,"width":164});
           for(t=0;t<=(desc.length+2);t++)
            {
             $("#cnt"+t).hide();
            }
            $("#text").jScrollPane({showArrows: false});
            $("#cnt"+(k+1)).show();

           });

     $("#wrapper").on("click","#participant",
           function()
           {
           var t;
               $("#link>div").removeClass("link_select");
        	   $("#link>div").css({"background-color":"black","color":"silver","position":"relative","left":0,"width":164});
           for(t=0;t<=(desc.length+2);t++)
            {
             $("#cnt"+t).hide();
            }
            $("#text").jScrollPane({showArrows: false});
            $("#cnt"+(k+2)).show();

           });
     
	
});
</script>


</head>
<body>
<p><a class="col" href="manager.php">Return</a></p>
<p><a class="col" href="logout.php">Log out</a></p>

<input type="hidden" id="data" value="<?php echo str_replace(array('"',"&#39;"), array('&quot;','&amp;#39;'), $longdesc);?>"/>
<input type="hidden" id="contact" value="<?php echo $contacts;?>"/>
<input type="hidden" id="prize" value="<?php echo $prizes;?>"/>
<input type="hidden" id="prtpnt" value="<?php echo $prtpnt;?>"/>
<div id="wrapper">
<div id="heading">
<p>
<?php echo $eventname;?>
</p>
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