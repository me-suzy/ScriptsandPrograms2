<?php
/****************************************************************/
/*                       phpht Topsites                         */
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

include($phpht_real_path . 'config.php');
include($phpht_real_path . 'includes/db.php');
include($phpht_real_path . 'includes/Template.php');
include($phpht_real_path . 'includes/functions.php');

$db = new db("$dbhost", "$dbuser", "$dbpass", "$dbname");

$sql = "SELECT * FROM ".$prefix."_config";
$result = $db->query($sql);

$row = $db->fetch($result);

$theme2 = $row['theme'];
$default_lang2 = $row['lang'];
$domain = $row['domain'];
$limit = $row['link_limit'];
$site_title = $row['title'];
$uemail = $row['email'];
$activate = $row['activate'];
$site_url = $row['site_url'];
$dir = $row['script_path'];
$mail = $row['mail'];

if((!$default_lang2) || (!$theme2)) {
    $default_lang = "english";
    $theme = "default";
} else {
    $default_lang = $default_lang2;
    $theme = $theme2;
}

include($phpht_real_path . 'language/lang_' . $default_lang . '.php');

$template = new Template($phpht_real_path . 'templates/'.$theme.'/');
?>