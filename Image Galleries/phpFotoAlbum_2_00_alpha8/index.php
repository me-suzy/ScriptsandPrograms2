<?php
$galver="2.00 alpha8[2004-08-17]";
$footer_text="<div id=\"footer\">powered by <a href=\"http://php.soundboss.cz/index.php?lang=cz&co=phpfa2\">phpFotoAlbum</a> (".$galver.")</div>\n";
/*
/--------------------------\
|   PHP Foto Album 2.00    |
|     Ladislav Soukup      |
|    root@soundboss.cz     |
|                          |
| http://php.soundboss.cz/ |
|                          |
|        GNU / GPL         |
\--------------------------/
*/
session_start();
if (!session_is_registered("s_data")){
  session_register("s_data");
  $_SESSION["s_data"]["sort"]="name";
  $_SESSION["s_data"]["sort2"]="asc";
  $_SESSION["s_data"]["dir"]="/";
}
foreach ($_GET as $var=>$val){
	$_SESSION["s_data"][$var]=$val;
}
include "./func.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
	<?php echo $content_type;?>
	<?php echo $content_language;?>
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="description" content="phpFotoAlbum" />
	<meta name="robots" content="ALL,FOLLOW" />
	<meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<link rel="STYLESHEET" href="skin/<?php echo $_SESSION["s_data"]["skin"];?>/css.css" type="text/css" />
	<title>phpFotoAlbum - <?php echo $galver;?></title>
	<script src="js_code.js" type="text/javascript"></script>
  </head>
<?php
if (!empty($_SESSION["s_data"]["file"])){
	echo "<body onKeypress=\"keyCtrl();\">\n";
} else {
	echo "<body>\n";
}
if ($_SESSION["s_data"]["lang"]=="select"){
	// Nuceny vyber jazyka
	echo "<div id=\"main\">\n";
	echo "<br /><img src=\"skin/-shared-/logo.png\" width=\"120\" height=\"20\" alt=\"\" border=\"0\" /><br /><br />\n";
	@include "./lang/_langs.php";
	foreach ($lang as $id => $name) {
		echo "<a href=\"index.php?lang=".$id."\">".$name . "</a><br /><br />\n";
	}
	echo "</div><br />\n";
	echo $footer_text;
	die("</body></html>");
}
if (!empty($_SESSION["s_data"]["file"])){
	echo "<div id=\"menu2\">\n";
	include "./menu2.php";
} else {
	echo "<div id=\"menu\">\n";
	include "./menu.php";
}
?>
</div>
<hr class="hide" />
<div id="main">
<?php
if (!empty($_SESSION["s_data"]["page"])){
	@include "./" . $_SESSION["s_data"]["page"] . ".php";
} else if (!empty($_SESSION["s_data"]["file"])){
	@include "./image_show.php";
} else {
	@include "./image_list.php";
}
?>
</div>
<hr class="hide" />
<?php echo $footer_text; ?>
</body>
</html>