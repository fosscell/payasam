<?php
require_once("config.php");
session_start();
if (isset($_SESSION["type"])) {
    if ($_SESSION["type"] != 'ML') {
	exit("Please go back and try again!");
    }
} else {
    header("Location: $start_page");
    exit();
}
if (!isset($_POST['mail'])) ;
else if (isset($_POST['eids']) && $_POST['replytoname'] && $_POST['replytoemail'] && $_POST['subject'] && $_POST['mailbody']) {
  $mysqli = new mysqli($host,$db_user,$db_password,$db_name);
  if ($mysqli->connect_errno)
    die("Connect failed: ".$mysqli->connect_error);
  $replyto = "$_POST[replytoname] <$_POST[replytoemail]>";
  $headers = "Reply-To: $replyto\r\n";
  $headers .= "From: Tathva 12 Mailer <nitcfest@gator860.hostgator.com>\r\n";
  $headers .= "Organization: National Institute of Technology, Calicut\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-type: text/plain; charset=ISO-8859-1\r\n";
  $headers .= "X-Priority: 3\r\n";
  $headers .= "X-Mailer: PHP". phpversion() ."\r\n";
  $ids = implode(', ', $_POST['eids']);
  $emails = array();
  $result = $mysqli->query("SELECT * FROM group_mail WHERE id IN ($ids)");
  while ($row = $result->fetch_assoc()) {
    $emails[] = $row['email'];
    mail($row['email'], $_POST['subject'], $_POST['mailbody'], $headers);
  }
  $ems = implode(", ", $emails);
  $result->free();
  $body = $mysqli->real_escape_string($_POST['mailbody']);
  $subject = $mysqli->real_escape_string($_POST['subject']);
  $mysqli->query("INSERT INTO mail_history(replyto, wtag, stag, to_ids, subject, body) VALUES ('$replyto','$_POST[wtag]','$_POST[stag]','$ids','$subject','$body\n\n---\nSent to:\n$ems')");
  $mysqli->query("UPDATE group_mail SET tags=CONCAT(tags,':$_POST[stag]:') WHERE id IN ($ids)");
  echo "Hopefully sent to $ems";
  $mysqli->close();
} else
  echo "Verify input!";
$rtnm = ""; $rtem = ""; $wtag = ""; $stag = ""; $subj = ""; $body = "";
if (isset($_POST['mail'])) {
  $rtnm = $_POST['replytoname'];
  $rtem = $_POST['replytoemail'];
  $wtag = $_POST['wtag'];
  $stag = $_POST['stag'];
  $subj = $_POST['subject'];
  $body = $_POST['mailbody'];
}
?>
<!DOCTYPE html>
<html>

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Tathva 12 Mailer</title>
<script type="text/javascript" src="jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
  $("#search").click(function () {
    $.ajax("mails_get.php", {
      data: {"wtag":$("input[name=wtag]").val(),"stag":$("input[name=stag]").val()}, 
      dataType: 'json',
      success: function (d) {
	var hs = "<table id='lister'><tr><th><input type='checkbox' id='select_all' />id</th><th>email</th><th>tags</th></tr>", cb;
	for (i=0; i<d.length; i++) {
	  cb = "<input type='checkbox' name='eids[]' value='"+d[i]["id"]+"' id='ecb"+i+"' /><label for='ecb"+i+"'>"+d[i]["id"]+"</label>";
	  hs += "<tr><td>"+cb+"</td><td>"+d[i]["email"]+"</td><td>"+d[i]["tags"]+"</td></tr>";
	}
	hs += "</table>";
	$("#selection").html(hs);
      },
      error: function(xhr,t) {
	$("#selection").html(t);
      }});
  });
  $("#seetags").click(function () {
    $.ajax("mail_tags.php", {
      data: {}, 
      dataType: 'json',
      success: function (d) {
	var hs = "<table id='lister'><tr><th>tags</th></tr>", cb;
	for (i=0; i<d.length; i++) {
	  hs += "<tr><td>"+d[i]+"</td></tr>";
	}
	hs += "</table>";
	$("#selection").html(hs);
      },
      error: function(xhr,t) {
	$("#selection").html(t);
      }});
  });
  $("#selection").on("change", "input:checkbox", function () {
    var $this = $(this);
    if ($this.is("#select_all")) {
      var state = $this.is(':checked');
      $("input[name^=eids]").each(function () {
	$(this).attr('checked', state);
      });
    } else {
      var $sa = $("#select_all");
      if ($sa.is(':checked'))
	$sa.attr('checked', false);
      else
	$sa.attr('checked', $("#selection").find("input:checkbox:not(:checked)").length == 1);
    }
  });
});
</script>
<style>
#frap {
  text-align:right;
  border-spacing:0;
}
#frap td {
  padding: 5px;
  border-bottom: 1px solid #EEEEEE;
}
#lister {
  background-color: #F8F8FD;
}
#lister th {
  background-color: #F0F0F8;
}
</style>
</head>
<body>
  <h1>Tathva 12 mailer</h1>
  Group mail
  <a href="mails_add.php">Add new mails</a>
  <a href="mail_history.php">Mail history</a>
  <a href="logout.php">Log out</a><br/><br/>
  <form action="mail.php" method="post">
    <table id="frap">
    <tr>
      <td>Reply-to</td>
      <td>
	<input name="replytoname" type="text" placeholder="Name" value="<?php echo $rtnm; ?>" /><br/>
	<input name="replytoemail" type="text" placeholder="eMail" value="<?php echo $rtem; ?>" />
      </td>
    </tr><tr>
      <td colspan=2>
	main tag: <input name="wtag" type="text" placeholder="select those with tag" value="<?php echo $wtag; ?>" /><br/>
	"sent" tag: <input name="stag" type="text" placeholder="'sent' marking tag" value="<?php echo $stag; ?>" /><br/>
	<a href="javascript:void(0);" id="seetags">See all tags</a>
	<a href="javascript:void(0);" id="search">Search</a>
	<div id="selection"></div>
      </td>
    </tr><tr>
      <td>Subject</td>
      <td>
	<input name="subject" type="text" placeholder="Subject" value="<?php echo $subj; ?>" />
      </td>
    </tr><tr>
      <td colspan=2>
	<textarea name="mailbody" placeholder="Body" style="width:100%"><?php echo $body; ?></textarea>
      </td>
    </tr><tr>
      <td colspan=2 style="text-align:center">
	<input name="mail" type="submit" value="Send" />
      </td>
    </tr>
    </table>
  </form>
</body>
</html>