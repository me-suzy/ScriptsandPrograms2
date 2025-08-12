<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function day_selector($fieldname, $defdate="", $enabled=true)
{
	if(!$defdate)
		$defdate=date("Y-m-d");
	list($year, $month, $day) = explode("-", $defdate);
	$selector="";
	$selector.="<select class=\"snewsdropdown\" name=\"$fieldname\"";
	if(!$enabled)
		$selector.=" disabled";
	$selector.=">\n";
	for($i=1;$i<32;$i++)
	{
		$selector.="<option value=\"".sprintf("%02d",$i)."\"";
		if($i==$day)
			$selector.=" selected";
		$selector.=">$i</option>\n";
	}
	$selector.="</select>\n";
	return $selector;
}
function month_selector($fieldname, $defdate="", $enabled=true)
{
	if(!$defdate)
		$defdate=date("Y-m-d");
	list($year, $month, $day) = explode("-", $defdate);
	$selector="";
	$selector.="<select class=\"snewsdropdown\" name=\"$fieldname\"";
	if(!$enabled)
		$selector.=" disabled";
	$selector.=">\n";
	for($i=1;$i<13;$i++)
	{
		$selector.="<option value=\"".sprintf("%02d",$i)."\"";
		if($i==$month)
			$selector.=" selected";
		$selector.=">$i</option>";
	}
	$selector.="</select>";
	return $selector;
}
function year_selector($fieldname, $defdate="", $enabled=true)
{
	global $yearrange;
	if(!$defdate)
		$defdate=date("Y-m-d");
	list($year, $month, $day) = explode("-", $defdate);
	$startyear=date("Y")-$yearrange;
	$endyear=date("Y")+$yearrange;
	$selector="";
	$selector.="<select class=\"snewsdropdown\" name=\"$fieldname\"";
	if(!$enabled)
		$selector.=" disabled";
	$selector.=">\n";
	for($i=$startyear;$i<$endyear+1;$i++)
	{
		$selector.="<option value=\"$i\"";
		if($i==$year)
			$selector.=" selected";
		$selector.=">$i</option>";
	}
	$selector.="</select>";
	return $selector;
}
?>