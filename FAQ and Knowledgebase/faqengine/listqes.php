<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($id))
	die("Calling error: wrong paramcount");
$params=explode("|",$id);
if(count($params)<3)
	die("Calling error: wrong paramcount");
$prog=$params[0];
$$langvar=$params[1];
$layout=$params[2];
$altlinkmethod=1;
$list="questions";
$act_script_url="faq.php";
include("./faq.php");
?>
