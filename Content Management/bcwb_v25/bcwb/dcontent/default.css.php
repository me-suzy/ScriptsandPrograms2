<?PHP
/* 24/04/2004 - Hilay Selivansky - BiDi support*/
include ("../config.inc.php");
if($language=="ru" or $language=="en") $default_language=$language;
if(!$default_language) $default_language="ru";
if(! include($root_path_admin.'../lang/'.$default_language.'.inc.php') ) die("Can't include ".$root_path.'lang/'.$default_language.'.inc.php');
if (!$language_dir) $language_dir="LTR";

$style = "P, BODY, TD {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #333333; direction:'$language_dir'}";

print $style;
?>

a {  text-decoration:none; color: #322666; CURSOR: hand }
a:hover {  text-decoration:none; color: red }
a:link {  text-decoration:none;  color: #330066}
a:active {  text-decoration:none; color: #330066}


TD.txt  {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #333333; text-align: justify; }
.mainmenu {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #787581; padding-right: 5px; }
	a.mainmenu {  text-decoration:none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #787581; font-weight : bolder; }
	a.mainmenu:hover {  text-decoration:none; color: #050505; }
	a.mainmenu:link {  text-decoration:none; color: #787581; }
	a.mainmenu:active {  text-decoration:none; color: red; }

TD.copy { font-family:Verdana, Arial, tahoma; font-size:11px; font-weight:bolder; }

TD.submenu, a.submenu, a.submenu:link, a.submenu:active  {  text-decoration:none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px; color: #787581; font-weight : bolder;}
a.submenu:hover {  text-decoration:none; color: #050505; }

TD.track { text-decoration:none; color: #EEEEEE; font-weight : bold; font-size: 10px; }
a.track { text-decoration:none; color: #FFFFFF; font-weight : bold; font-size: 10px; }
a.track:link {  text-decoration:none;  color: #FFFFFF; }
a.track:hover {  text-decoration:none; color: #AAAAAA; }

H1 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 16px; color: #787581; }
H2 {  font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 14px; color: #787581; }

H2.install {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
BODY.install, .install {  font-family: Tahoma, Arial, Helvetica, sans-serif; font-size: 12px; color: #707071;  font-weight:bold; }
.enable {  color: #529EE8; }
.disable {  color: #FC7126; }
INPUT.install { font-size:12px;
		border:1px solid #707071;
		background-color: #eeeeee;
	}

