<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

if ($nazev=="") {
Header("Location: categorycreate.php");
} else {

function get_new_id ($tblname_head) {
  $id = 0;
  $tmp = mysql_Query("SELECT MAX(head_id) AS maxim FROM $tblname_head");
  $pocet = mysql_num_rows($tmp);
  if (!$pocet) {
    $id = 1;
    } else {
      $id = mysql_result($tmp, 0, "maxim");
      $id++;
  }
  return $id;
}

function get_new_order ($tblname_head) {
  $order = 0;
  $tmp = mysql_Query("SELECT MAX(head_order) AS maxim FROM $tblname_head");
  $pocet = mysql_num_rows($tmp);
  if (!$pocet) {
    $order = 1;
    } else {
      $order = mysql_result($tmp, 0, "maxim");
      $order++;
  }
  return $order;
}

$id = get_new_id($tblname_head);
$order = get_new_order($tblname_head);
$vysledek = MySQL_Query("INSERT INTO $tblname_head VALUES ('$id','$nazev','$order',0,'$popis')");
Header("Location: category.php");
}
?>