<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

$result1 = MySQL_Query("SELECT topic_head FROM $tblname_topic WHERE topic_id='$id'");
  $topichead = mysql_result($result1,0,"topic_head");
$result2 = MySQL_Query("SELECT head_number FROM $tblname_head WHERE head_id='$topichead'");
  $headnumber = mysql_result($result2,0,"head_number");
  $headnumber = $headnumber-1;

$vysledek1 = MySQL_Query("DELETE FROM $tblname_topic WHERE topic_id='$id'");
$vysledek2 = MySQL_Query("UPDATE $tblname_head SET head_number='$headnumber' WHERE head_id='$topichead'");

Header("Location: category.php");
?>