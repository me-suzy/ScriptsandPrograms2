<?php
/****************************************************************/
/*                       phphg Guestbook                        */
/*                       common.php file                        */
/*                      (c)copyright 2003                       */
/*                       By hinton design                       */
/*                 http://www.hintondesign.org                  */
/*                  support@hintondesign.org                    */
/*                                                              */
/* This program is free software. You can redistrabute it and/or*/
/* modify it under the terms of the GNU General Public Licence  */
/* as published by the Free Software Foundation; either version */
/* 2 of the license.                                            */
/*                                                              */
/****************************************************************/
if(eregi("common.php", $HTTP_SERVER_VARS['PHP_SELF'])) {
   header("Location: index.php");
   exit();
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);

include($phphg_real_path . 'config.php');

include($phphg_real_path . 'includes/db.php');
include($phphg_real_path . 'includes/Template.php');
include($phphg_real_path . 'includes/functions.php');

$db = new db("$dbhost", "$dbuser", "$dbpass", "$dbname");

$sql = "SELECT * FROM ".$prefix."_config";
$result = $db->query($sql);


$row = $db->fetch($result);

$domain = $row['domain'];
$script_path = $row['script_path'];
$default_lang = $row['default_lang'];
$default_theme = $row['default_theme'];
$limit = $row['board_limit'];
if((!$default_lang) || (!$default_theme)) {
    $default_lang = "english";
    $default_theme = "default";
} else {
   $default_theme = $default_theme;
   $default_lang = $default_lang;
}
$smilie_dir = $phphg_real_path . "images/smilies/";

include($phphg_real_path . 'language/lang_' . $default_lang . '.php');

$template = new Template($phphg_real_path . 'templates/' . $default_theme);
?>
