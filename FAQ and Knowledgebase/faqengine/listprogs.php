<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(isset($id))
{
	$params=explode("|",$id);
	if(count($params)<2)
		die("Calling error: wrong paramcount");
	$$langvar=$params[0];
	$layout=$params[1];
}
$altlinkmethod=1;
$act_script_url="faq.php";
$list="progs";
include("./faq.php");
?>
