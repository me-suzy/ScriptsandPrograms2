<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

if ($autor=="" || $datum=="") {
Header("Location: categoryshowedit.php?id=$id");
} else {

$prispevek = strip_tags($prispevek);
$prispevek = nl2br($prispevek);

if ($starahlava==$hlava) {
$vysledek = MySQL_Query("UPDATE $tblname_topic SET topic_user='$autor', topic_email='$email', topic_time='$datum', topic_title='$predmet', topic_text='$prispevek' WHERE topic_id='$id'");
} else {

  $result1 = MySQL_Query("SELECT head_number FROM $tblname_head WHERE head_id='$starahlava'");
    $headnumber1 = mysql_result($result1,0,"head_number");
    $headnumber1 = $headnumber1-1;
  $result2 = MySQL_Query("SELECT head_number FROM $tblname_head WHERE head_id='$hlava'");
    $headnumber2 = mysql_result($result2,0,"head_number");
    $headnumber2 = $headnumber2+1;

$vysledek1 = MySQL_Query("UPDATE $tblname_topic SET topic_head='$hlava', topic_user='$autor', topic_email='$email', topic_time='$datum', topic_title='$predmet', topic_text='$prispevek' WHERE topic_id='$id'");
$vysledek2 = MySQL_Query("UPDATE $tblname_head SET head_number='$headnumber1' WHERE head_id='$starahlava'");
$vysledek3 = MySQL_Query("UPDATE $tblname_head SET head_number='$headnumber2' WHERE head_id='$hlava'");
}

Header("Location: categoryshowedit.php?id=$id");

}
?>