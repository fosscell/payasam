<?php
require_once("config.php");
$sq = $_GET['q'];

$mysqli = new mysqli($host,$db_user,$db_password,$db_name);
if ($mysqli->connect_errno)
  die("Connect failed: ".$mysqli->connect_error);

$results = array();

function get_results($cid, $cname, $where, $limit, $exclude, $cat) {
  global $sq, $results, $mysqli;
  if (gettype($cid)=='array') {
    $catq = "(cat_id='$cid[0]'";
    foreach ($cid as $cid_i => $cid_v)
      if ($cid_i)
	$catq .= " OR cat_id='$cid_v'";
    $catq .= ")";
  } else
    $catq = "cat_id='$cid'";
  if ($exclude)
    $addq = "AND code NOT IN ('$exclude')";
  $res = $mysqli->query("SELECT code, name, shortdesc FROM events WHERE $catq $addq AND ($where LIKE '%".str_replace(' ', "%' AND $where LIKE '%", $sq)."%') LIMIT $limit");
  $ret = array();
  while($row = $res->fetch_assoc()) {
    $results[$cname][] = array('code' => $row['code'], 'name' => $row['name'], 'shortdesc' => $row['shortdesc'], 'category' => $cat);
    $ret[] = $row['code'];
  }
  $res->free();
  return $ret;
}

function fill_results($cid, $cname, $pcat=NULL) {
  global $results;
  $results[$cname] = array();
  if ($pcat===NULL) $pcat = $cid;
  $n1 = get_results($cid, $cname, 'name', 8, NULL, $pcat);
  if (count($n1) < 8) {
    $exclude = implode("','",$n1);
    $n2 = get_results($cid, $cname, 'tags', 8-count($n1), $exclude, $pcat);
    if (count($n1) + count($n2) < 8) {
      $exclude = implode("','",array_merge($n1,$n2));
      get_results($cid, $cname, 'shortdesc', 8-count($n1)-count($n2), $exclude, $pcat);
    }
  }
}

$cat_res = $mysqli->query("SELECT cat_id, name FROM event_cats WHERE par_cat=1");
while($cat = $cat_res->fetch_assoc()) {
  fill_results($cat['cat_id'], $cat['name'], 1);
}
fill_results(array(17,18), 'Workshops', 2);
fill_results(3, 'Exhibition');
fill_results(4, 'Highlights');
fill_results(6, 'Online');
fill_results(19, 'Lectures');
fill_results(20, 'Nites');

print json_encode($results);
?>