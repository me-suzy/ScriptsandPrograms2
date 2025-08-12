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
$subcatnr=$params[0];
$$langvar=$params[1];
$layout=$params[2];
$altlinkmethod=1;
$list="subcategory";
$sql="select prog.progid, cat.catnr from ".$tableprefix."_subcategory subcat, ".$tableprefix."_category cat, ".$tableprefix."_programm prog where subcat.catnr=".$subcatnr." and prog.prognr=cat.programm and cat.catnr=subcat.category";
if(!$result = faqe_db_query($sql, $db))
	die("Could not connect to the database.");
if(!$myrow = faqe_db_fetch_array($result))
	die("Unable to retrieve data");
$prog=$myrow["progid"];
$catnr=$myrow["catnr"];
$act_script_url="faq.php";
include("./faq.php");
?>
