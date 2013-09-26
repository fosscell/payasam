<?php
function input_ntv($name, $type, $value) {
    return "<input type='$type' value='".htmlspecialchars($value, ENT_QUOTES)."' name='$name' />";
}
function select_nqtv($name, $query, $text_col, $value_col) {
    $ret = "<select name='$name'>";
    $ret .= "<option value=''>--select--</option>";
    $q=mysql_query($query);
    while($row=mysql_fetch_array($q))
	$ret .= "<option value='".htmlspecialchars($row[$value_col], ENT_QUOTES)."'>".$row[$text_col]."</option>";
    return $ret."</select>";
}
function select_no($name, $options) {
    $ret = "<select name='$name'>";
    $q=mysql_query($query);
    foreach ($options as $value=>$text)
	$ret .= "<option value='".htmlspecialchars($value, ENT_QUOTES)."'>$text</option>";
    return $ret."</select>";
}
function radios_nioc($name, $id_prefix, $options, $checked_index = -1) {
    $i = 0;
    $length = count($options);
    $ret = "";
    foreach ($options as $value=>$label) {
	$ret .= "<input type='radio' value='$value' name='$name' id='$id_prefix"."$i' ".($i==$checked_index ? "checked='checked' " : "")."/>";
	$ret .= "<label for='$id_prefix"."$i'>$label</label>";
	if ($length > $i+1)
	    $ret .= "<br/>";
    }
    return $ret;
}
function form_ac($action, $rows) {
    echo "<form action='$action' method='post'>";
    echo "<table>";
    foreach ($rows as $row) {
	$cols = count($row);
	if ($cols==2)
	    echo "<tr><td>".$row[0]."</td><td>".$row[1]."</td></tr>";
	else if ($cols==1)
	    echo "<tr><td colspan='2' style='text-align:center'>".$row[0]."</td></tr>";
	else
	    echo "<tr><td colspan='2'>--Nil--</td></tr>";
    }
    echo "</table></form>";
}
function num2str($num, $width) {
    $temp = $num;
    $digits = 1;
    while ($temp >= 10) {
	$digits++;
	$temp /= 10;
    }
    $str = "";
    while ($digits++ < $width)
	$str .= "0";
    return $str . $num;
}
?>