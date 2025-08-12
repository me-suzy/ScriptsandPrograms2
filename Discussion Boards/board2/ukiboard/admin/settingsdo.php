<?php
include("../connect.php");
include("one.php");
logincontrol($tblname_admin);
sessioncontrol();

if ($nazev=="" || $popis=="" || $email=="" || $stranka=="" || $stranka<1) {
Header("Location: settings.php");
} else {

$vysledek1 = MySQL_Query("UPDATE $tblname_config SET config_value='$nazev' WHERE config_name='board_title'");
$vysledek2 = MySQL_Query("UPDATE $tblname_config SET config_value='$popis' WHERE config_name='board_char'");
$vysledek3 = MySQL_Query("UPDATE $tblname_config SET config_value='$email' WHERE config_name='board_email'");
$vysledek4 = MySQL_Query("UPDATE $tblname_config SET config_value='$jazyk' WHERE config_name='board_lang'");
$vysledek5 = MySQL_Query("UPDATE $tblname_config SET config_value='$vzhled' WHERE config_name='board_themes'");
$vysledek6 = MySQL_Query("UPDATE $tblname_config SET config_value='$stranka' WHERE config_name='board_page'");

if ($adminzobraz=="yes") {
  $vysledek7 = MySQL_Query("UPDATE $tblname_config SET config_value='yes' WHERE config_name='board_admin'");
  } else {
    $vysledek7 = MySQL_Query("UPDATE $tblname_config SET config_value='no' WHERE config_name='board_admin'");
}

Header("Location: settings.php");
}
?>