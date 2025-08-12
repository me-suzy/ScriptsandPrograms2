<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
$enddate=mktime(0,0,0,$end_month,$end_day,$end_year);
$curdate=mktime(0,0,0,$curmonth,$curday+1,$curyear);
$adddate = date("Y-m-d H:i:00");
$added=0;
while($curdate<=$enddate)
{
	$entrydate=date("Y-m-d",$curdate);
	$sql = "insert into ".$tableprefix."_events (lang, date, text, heading, poster, headingicon,";
	$sql.= "category, added, posterid,exposter, dontemail, wap_nopublish, wap_short) values (";
	$sql.= "'".$curentry["lang"]."',";
	$sql.= "'$entrydate',";
	$sql.= "'".addslashes($curentry["text"])."',";
	$sql.= "'".addslashes($curentry["heading"])."',";
	$sql.= "'".$curentry["poster"]."',";
	$sql.= "'".$curentry["headingicon"]."',";
	$sql.= $curentry["category"].",";
	$sql.= "'$adddate',";
	$sql.= $curentry["posterid"].",";
	$sql.= $curentry["exposter"].",";
	$sql.= $curentry["dontemail"].",";
	$sql.= $curentry["wap_nopublish"].",";
	$sql.= "'".addslashes($curentry["wap_short"])."')";
	if(!$result = mysql_query($sql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	$neweventnr=mysql_insert_id($db);
	$attachsql = "select * from ".$tableprefix."_events_attachs where eventnr=$input_eventnr";
	if(!$attachresult = mysql_query($attachsql, $db))
	    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	while($attachrow=mysql_fetch_array($attachresult))
	{
		$tmpsql = "insert into ".$tableprefix."_events_attachs (eventnr, attachnr) values (";
		$tmpsql.= "$neweventnr, ".$attachrow["attachnr"].")";
		if(!$tmpresult = mysql_query($tmpsql, $db))
		    die("<tr class=\"errorrow\"><td>Unable to connect to database.".mysql_error());
	}
	$added++;
	$curdate+=24*60*60;
}
?>