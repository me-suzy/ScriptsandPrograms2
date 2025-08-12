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
$l_menus =
array(
	array(
		array("entry"=>$l_mainmenu,"url"=>"index.php?lang=$lang#top","level"=>0),
		array("entry"=>$l_logout,"url"=>"logout.php?lang=$lang","level"=>0)
	),
	array(
		array("entry"=>$l_progs,"url"=>"index.php?lang=$lang#progs","level"=>1),
		array("entry"=>$l_oslist,"url"=>"os.php?lang=$lang","level"=>1),
		array("entry"=>$l_editprogs,"url"=>"program.php?lang=$lang","level"=>1),
		array("entry"=>$l_changelog,"url"=>"changelog.php?lang=$lang","level"=>1),
		array("entry"=>$l_bugtracking,"url"=>"bugtraq.php?lang=$lang","level"=>1),
		array("entry"=>$l_todo,"url"=>"todo.php?lang=$lang","level"=>1),
		array("entry"=>$l_todostats,"url"=>"todostats.php?lang=$lang","level"=>1),
		array("entry"=>$l_featurerequests,"url"=>"requests.php?lang=$lang","level"=>1),
		array("entry"=>$l_requeststats,"url"=>"requeststats.php?lang=$lang","level"=>1),
		array("entry"=>$l_references,"url"=>"reference.php?lang=$lang","level"=>1),
		array("entry"=>$l_mirrorserver,"url"=>"mirrorserver.php?lang=$lang","level"=>2),
		array("entry"=>$l_downloadfiles,"url"=>"download_files.php?lang=$lang","level"=>2),
		array("entry"=>$l_downloadstats,"url"=>"downloadstats.php?lang=$lang","level"=>2)
	),
	array(
		array("entry"=>$l_newsletter,"url"=>"index.php?lang=$lang#news","level"=>2),
		array("entry"=>$l_sendnewsletter,"url"=>"newsletter.php?lang=$lang","level"=>2),
		array("entry"=>$l_subscribers,"url"=>"subscribers.php?lang=$lang","level"=>2)
	),
	array(
		array("entry"=>$l_partnersites,"url"=>"index.php?lang=$lang#partner","level"=>2),
		array("entry"=>$l_managepartnersites,"url"=>"partnersites.php?lang=$lang","level"=>2),
		array("entry"=>$l_partnersiteclicks,"url"=>"partnerclicks.php?lang=$lang","level"=>2)
	),
	array(
		array("entry"=>$l_screenshots,"url"=>"index.php?lang=$lang#screenshots","level"=>2),
		array("entry"=>$l_directories,"url"=>"screenshotdirs.php?lang=$lang","level"=>2),
		array("entry"=>$l_managescreenshots,"url"=>"screenshots.php?lang=$lang","level"=>2)
	),
	array(
		array("entry"=>$l_user,"url"=>"index.php?lang=$lang#users","level"=>1),
		array("entry"=>$l_editadmins,"url"=>"users.php?lang=$lang","level"=>1),
		array("entry"=>$l_failed_logins,"url"=>"loginfailures.php?lang=$lang","level"=>3),
		array("entry"=>$l_ipbanlist,"url"=>"banlist.php?lang=$lang","level"=>2),
		array("entry"=>$l_freemailerlist,"url"=>"freemailer.php?lang=$lang","level"=>2)
	),
	array(
		array("entry"=>$l_layout,"url"=>"index.php?lang=$lang#layout","level"=>1),
		array("entry"=>$l_editsettings,"url"=>"settings.php?lang=$lang","level"=>3),
		array("entry"=>$l_texts,"url"=>"texts.php?lang=$lang","level"=>1),
		array("entry"=>$l_allowedreferers,"url"=>"allowedrefs.php?lang=$lang","level"=>1),
		array("entry"=>$l_forbiddenreferers,"url"=>"forbiddenrefs.php?lang=$lang","level"=>1)
	),
	array(
		array("entry"=>$l_administration,"url"=>"index.php?lang=$lang#admin","level"=>3),
		array("entry"=>$l_hostcache,"url"=>"hostcache.php?lang=$lang","level"=>3),
		array("entry"=>$l_cleansession,"url"=>"sessions.php?lang=$lang","level"=>3),
		array("entry"=>$l_shutdownsys,"url"=>"shutdown.php?lang=$lang","level"=>3),
		array("entry"=>$l_dbbackup,"url"=>"backup.php?lang=$lang","level"=>3)
	)
);
?>