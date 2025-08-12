<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
// wap settings
$sql = "select * from ".$tableprefix."_layout where lang='$act_lang'";
if(isset($layout))
	$sql.=" and id='$layout'";
else
	$sql.=" and deflayout=1";
if(!$result = mysql_query($sql, $db))
    die("Unable to connect to database.".mysql_error());
if(!$myrow=mysql_fetch_array($result))
{
	if(isset($layout))
		die("layout $layoutid not set up.");
	else		
		die("default layout not set up.");
}
$wap_options=$myrow["wap_options"];
$wap_title=$myrow["wap_title"];
$wap_description=$myrow["wap_description"];
$wap_copyright=$myrow["wap_copyright"];
$wap_auto['title']=$myrow["wap_auto_title"];
$wap_auto['short']=$myrow["wap_auto_short"];
$wap_maxentries=$myrow["wap_maxentries"];
$wap_ev_maxdays=$myrow["wap_ev_maxdays"];
$wap_ev_title=$myrow["wap_ev_title"];
$wap_ev_description=$myrow["wap_ev_description"];
$wap_ev_maxentries=$myrow["wap_ev_maxentries"];
$wap_an_maxdays=$myrow["wap_an_maxdays"];
$wap_an_maxentries=$myrow["wap_an_maxentries"];
$wap_an_title=$myrow["wap_an_title"];
$wap_an_description=$myrow["wap_an_description"];
$wap_evs_maxldays=$myrow["wap_evs_maxldays"];
$wap_evs_dayrange=$myrow["wap_evs_dayrange"];
$wap_catlist_epp=$myrow["wap_catlist_epp"];
$wap_evlist2_epp=$myrow["wap_evlist2_epp"];
$wap_cl_title=$myrow["wap_cl_title"];
$wap_cl_description=$myrow["wap_cl_description"];
$wap_cl_logo=$myrow["wap_cl_logo"];
?>