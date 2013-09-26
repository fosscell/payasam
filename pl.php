<?php
require_once("initdb.php");
$erlist = '';
if (!isset($_SESSION['type'])) {
  header("Location: $start_page");
  _exit();
} else if ($_SESSION['type'] != "PL")
  _exit("You do not have access to this page");

echo "<h3 style='margin:10px 0'>$erlname Registration List</h3>";
$res = $mysqli->query("SELECT s.id, s.name as name, s.phone, s.email, c.name as clg FROM student_reg s INNER JOIN colleges c ON s.clg_id=c.id WHERE s.clg_id!='1'");
?>
<table>
  <tr><th>Tathva ID</th><th>Name</th><th>Phone no.</th><th>eMail</th><th>College</th></tr>
  <?php
while($row=$res->fetch_assoc())
  echo "<tr><td>$row[id]</td><td>$row[name]</td><td>$row[phone]</td><td>$row[email]</td><td>$row[clg]</td></tr>";
  ?>
</table>
<?php
echo "Total: ".$res->num_rows;
$res->free();
$mysqli->close();
?>