<?php
include("../connect.php");

if ($inputposter=="" || $inputbody=="") {
Header("Location: show.php?headid=$headid");
} else {

function get_new_id ($tblname_topic) {
	$id = 0;
	$tmp = mysql_Query("SELECT MAX(topic_id) AS maxim FROM $tblname_topic");
	$pocet = mysql_num_rows($tmp);
	if (!$pocet) {
		$id = 1;
	}
	else {
		$id = mysql_result($tmp, 0, "maxim");
		$id++;
	}
	return $id;
}

$id = get_new_id($tblname_topic);
$inputbody = nl2br($inputbody);

$result = MySQL_Query("SELECT head_number FROM $tblname_head WHERE head_id='$headid'");
  $headnumber = mysql_result($result,0,"head_number");
  $headnumber++;
$vysledek1 = MySQL_Query("INSERT INTO $tblname_topic VALUES ('$id','$headid','$inputposter','$inputemail',now(),'$inputtitle','$inputbody')");
$vysledek2 = MySQL_Query("UPDATE $tblname_head SET head_number='$headnumber' WHERE head_id='$headid'");

Header("Location: show.php?headid=$headid");

}
?>