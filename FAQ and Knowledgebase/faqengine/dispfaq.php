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
$faqnr=$params[0];
$$langvar=$params[1];
$layout=$params[2];
$altlinkmethod=1;
$display="faq";
$sql="select * from ".$tableprefix."_data where faqnr=".$faqnr;
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow = faqe_db_fetch_array($result))
	die("Unable to retrieve data");
$catnr=$myrow["category"];
$sql="select * from ".$tableprefix."_category cat, ".$tableprefix."_programm prog where cat.catnr=".$catnr." and prog.prognr=cat.programm";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow = faqe_db_fetch_array($result))
	die("Unable to retrieve data");
$prog=$myrow["progid"];
$act_script_url="faq.php";
include("./faq.php");
?>
