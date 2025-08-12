<?php
/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/
/***************************************************************************
 * Created by: Boesch IT-Consulting (info@boesch-it.de)
 * (c)2002-2005 Boesch IT-Consulting
 * *************************************************************************/
$path_progsys=dirname(__FILE__);
$do_db_die=false;
require($path_progsys.'/config.php');
$psys_db=$db;
function showpartnerlink($sitenr)
{
	global $tableprefix, $psys_db, $url_progsys, $path_progsys;
	if(!$psys_db)
		return;
	$sql="select * from ".$tableprefix."_partnersites where sitenr='$sitenr'";
	if(!$result=mysql_query($sql,$psys_db))
		return;
	if(!$myrow=mysql_fetch_array($result))
		return;
	if($myrow["disabled"]==0)
	{
		echo "<a href=\"".$url_progsys."/partner.php?sitenr=".$sitenr."\"";
		if($myrow["linktarget"])
			echo " target=\"".$myrow["linktarget"]."\"";
		echo ">";
	}
	echo "<img src=\"".$myrow["logourl"]."\" border=\"0\" alt=\"".$siteurl."\" align=\"middle\">";
	if($myrow["disabled"]==0)
		echo "</a>";
}
?>
