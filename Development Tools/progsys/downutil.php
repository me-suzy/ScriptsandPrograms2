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
function showdownloadlink($filenr, $displaytext, $displang)
{
	global $tableprefix, $db, $url_progsys, $path_progsys;
	include($path_progsys."/language/lang_".$displang.".php");
	if(!$db)
	{
		echo $displaytext;
		echo "&nbsp;<img src=\"".$url_progsys."/gfx/offline.gif\" border=\"0\" align=\"middle\" alt=\"".$l_tempoffline."\" title=\"".$l_tempoffline."\">";
	}
	$sql="select * from ".$tableprefix."_download_files where filenr='$filenr'";
	if(!$result=mysql_query($sql,$db))
		return;
	if(!$myrow=mysql_fetch_array($result))
		return;
	if($myrow["downloadenabled"]==1)
		echo "<a href=\"".$url_progsys."/download.php?filenr=".$filenr."&lang=".$displang."\">";
	echo $displaytext;
	if($myrow["downloadenabled"]==1)
		echo "</a>&nbsp;<img src=\"".$url_progsys."/gfx/online.gif\" border=\"0\" align=\"middle\" alt=\"".$l_online."\" title=\"".$l_online."\">";
	else
		echo "&nbsp;<img src=\"".$url_progsys."/gfx/offline.gif\" border=\"0\" align=\"middle\" alt=\"".$l_tempoffline."\" title=\"".$l_tempoffline."\">";
}
function isdownloadenabled($filenr)
{
	global $tableprefix, $db;
	if(!$db)
		return false;
	$sql="select * from ".$tableprefix."_download_files where filenr='$filenr'";
	if(!$result=mysql_query($sql,$db))
		return false;
	if(!$myrow=mysql_fetch_array($result))
		return false;
	if($myrow["downloadenabled"]==1)
		return true;
	return false;
}
?>
