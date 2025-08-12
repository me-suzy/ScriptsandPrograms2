<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
if(!isset($$langvar) || !$$langvar)
	$act_lang=$default_lang;
else
	$act_lang=$$langvar;
include_once('language/lang_'.$act_lang.'.php');
require_once('./includes/get_settings.inc');
require_once('./includes/block_leacher.inc');
if($heading)
	$pageheading=$heading;
else
	$pageheading=$l_news;
if($allowcomments==0)
	die("$l_functiondisabled");
if(isset($mode))
{
	if($mode=="new")
	{
		$page="comment";
		include_once("./includes/head.inc");
		include_once("./includes/com_new.inc");
	}
	if($mode=="add")
	{
		include_once("./includes/com_add.inc");
	}
	if($mode=="display")
	{
		include_once("./includes/head.inc");
		include_once("./includes/com_display.inc");
	}
}
echo "</td></tr></table></td></tr></table></div>";
include_once("./includes/footer.inc");
?>
