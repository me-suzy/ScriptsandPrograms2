<?
/*
Copyright Information
Script File :  admin.php
Creator:  Jose Blanco (snyper)
Version:  1.0
Date Created: Feb. 20 / 2005
Released :  Feb. 27 / 2005
website: http://x-php.com , Shadowphp.net
e-mail: joseblanco.jr@g-mail.com
Aim: xphp snyper , Junior Snyper
please keep this copyright in place. :)
*/
header("Cache-control: private");
require_once("./inc/options.db.php"); // include configuration
require_once("./inc/functions.php");// include the fucntions
$id = (isset($HTTP_GET_VARS['id'])) ? $HTTP_GET_VARS['id'] : '';
if(file_exists("./skins/$skin/index.php")){
  $cs1 = implode(" ", file("./skins/$skin/index.php"));
}elseif(file_exists("./skins/$skin/index.htm")){
  $cs1 = implode(" ", file("./skins/$skin/index.htm"));
}elseif(file_exists("./skins/$skin/index.txt")){
  $cs1 = implode(" ", file("./skins/$skin/index.txt"));
}elseif(file_exists("./skins/$skin/skin.php")){
  $cs1 = implode(" ", file("./skins/$skin/skin.php"));
}elseif(file_exists("./skins/$skin/skin.html")){
  $cs1 = implode(" ", file("./skins/$skin/skin.html"));
}elseif(file_exists("./skins/$skin/skin.htm")){
  $cs1 = implode(" ", file("./skins/$skin/skin.htm"));
}elseif(file_exists("./skins/$skin/skin.txt")){
  $cs1 = implode(" ", file("./skins/$skin/skin.txt"));
}else{
  $cs1 = implode(" ", file("./skins/$skin/index.html"));
  }
  if($_GET["admin"]=="del") { unlink("./install.php"); }
global $scriptn ,$version,$loginid,$links,$linksbr,$title;
$sec_inc_code="081604"; // Do not edit this number or the script will not work
$scriptn="Fusion Contact"; // this is the current script
$version="1.0 ";// this is the current version of the script
// let include the brain of the script
include("./sources/admin.php");
// done that.
$cs = str_replace("{content}", $cont, $cs1);
$cs = str_replace("{title}", $title, $cs);
$cs = str_replace("{linksbr}", $linksbr, $cs);
$cs = str_replace("{links}", $links, $cs);
$cs = str_replace("{login}", $loginid, $cs);

echo $cs;
exit;
?>
