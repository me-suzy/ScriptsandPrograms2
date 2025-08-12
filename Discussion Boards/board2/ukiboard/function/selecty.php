<?php
$result1 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_lang'");
  $language = mysql_result($result1,0,"config_value");
  $language = $language.".php";
$result2 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_themes'");
  $themes = mysql_result($result2,0,"config_value");
  $style = $themes."style.php";
  $themes = $themes.".css";
$result3 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_title'");
  $board_title = mysql_result($result3,0,"config_value");
$result4 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_char'");
  $board_char = mysql_result($result4,0,"config_value");
$result5 = MySQL_Query("SELECT config_value FROM $tblname_config WHERE config_name='board_email'");
  $board_email = mysql_result($result5,0,"config_value");
?>