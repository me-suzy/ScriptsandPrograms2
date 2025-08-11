<?PHP
/* 24/04/2004 - Hilay Selivansky - BiDi support*/
include ("../config.inc.php");
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
if(! include($root_path_admin.'../lang/'.$default_language.'.inc.php') ) die("Can't include ".$root_path.'lang/'.$default_language.'.inc.php');
if (!$language_dir) $language_dir="LTR";

$style = "P, BODY, TD, INPUT.text, .text {  font-family: Tahoma, Arial, sans-serif; font-size: 12px; color: #707071;  text-decoration : none; direction:".$language_dir." }";
print $style;
?>
BODY {	padding : 0px 0px 0px 0px; margin : 0px 0px 0px 0px; background: #F6F7F7 }

TD.adminarea, A.adminarea, A.adminarea:visited, A.adminarea:active, A.adminarea:link {  font-family: Tahoma, Arial,  sans-serif; font-size: 12px; color: #585859;  text-decoration : none; font-weight:bold; }
A.adminarea:hover { text-decoration : none; color: #000000; }
INPUT.adminarea, SELECT.adminarea {  font-family: Tahoma, Arial,  sans-serif; font-size: 12px; color: #585859;  text-decoration : none; font-weight: normal; }
A.admin_com, A.admin_com:visited, A.admin_com:active, A.admin_com:link {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #707071;  text-decoration : none; font-weight: normal; font-style: italic}
TD.panel_title {  font-family: Tahoma, Arial, sans-serif; font-size: 20px; color: #707071;  text-decoration : none;  }
H1 {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
BODY.install, .install {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #707071;  font-weight:bold; }
.bold {  color: #529EE8; }
.alert {  color: #FC7126; }
a, a:visited { color: #000000; } 
a:hover, a.active { color: #FC7126; } 
A.install,A.install:visited, A.install:hover, A.install:active   {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #707071;  font-weight:bold; text-decoration : none; }

