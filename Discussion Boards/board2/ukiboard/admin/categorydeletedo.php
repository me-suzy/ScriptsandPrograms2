<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

$vysledek1 = MySQL_Query("DELETE FROM $tblname_head WHERE head_id='$id'");
$vysledek2 = MySQL_Query("DELETE FROM $tblname_topic WHERE topic_head='$id'");
Header("Location: category.php");
?>