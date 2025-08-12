<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
require_once('./config.php');
require_once('./functions.php');
if(!isset($id))
	die("Calling error: wrong paramcount");
$params=explode("|",$id);
if(count($params)<4)
	die("Calling error: wrong paramcount");
$question=$params[0];
$$langvar=$params[1];
$layout=$params[2];
$backurl=$params[3];
$backurl=str_replace("§","|",$backurl);
$altlinkmethod=1;
$mode="read";
$sql="select * from ".$tableprefix."_questions qes, ".$tableprefix."_programm prog where qes.questionnr=".$question." and prog.prognr=qes.prognr";
if(!$result = faqe_db_query($sql, $db))
	db_die("Could not connect to the database.");
if(!$myrow = faqe_db_fetch_array($result))
	die("Unable to retrieve data");
$prog=$myrow["progid"];
$act_script_url="question.php";
include("./question.php");
?>
