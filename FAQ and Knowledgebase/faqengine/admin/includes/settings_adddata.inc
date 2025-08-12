<?php
/***************************************************************************
 * (c)2001-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
if(isset($mods))
{
	while(list($null, $mod) = each($_POST["mods"]))
	{
		$mod_query = "INSERT INTO ".$tableprefix."_failed_notify (usernr) VALUES ('$mod')";
		if(!faqe_db_query($mod_query, $db))
			die("<tr bgcolor=\"#cccccc\"><td>Unable to update the database.");
	}
}
if(isset($rem_mods))
{
	while(list($null, $mod) = each($_POST["rem_mods"]))
	{
		$rem_query = "DELETE FROM ".$tableprefix."_failed_notify WHERE usernr = '$mod'";
		if(!faqe_db_query($rem_query,$db))
			die("<tr class=\"errorrow\"><td>Unable to update the database.");
	}
}
?>