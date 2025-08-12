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
function ismaint($wpid)
{
	global $tableprefix, $db, $url_progsys, $path_progsys;
	if(!$db)
		return;
	$sql="select * from ".$tableprefix."_wparts where id='$wpid'";
	if(!$result=mysql_query($sql,$db))
		return;
	if(!$myrow=mysql_fetch_array($result))
		return;
	if($myrow["maint"]==1)
	{
		echo stripslashes($myrow["mainttxt"]);
		exit;
	}
}
?>
