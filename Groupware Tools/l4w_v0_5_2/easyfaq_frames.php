<?php

   	/*=====================================================================
	// $Id: easyfaq_frames.php,v 1.3 2005/07/20 13:13:52 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	include_once ("config/config.inc.php");
	include_once ("connect_database.php");
	include_once ("inc/functions.inc.php");

	// This page gets include at the very beginning, so check here if user is logged in:
	security_check_core();

	// --- pagestats ------------------------------------------------
	set_page_stats(__FILE__);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
	<title><?=$version_name?></title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf, Stefan Jaeckel">
	<meta name="publisher"			content="evandor media GmbH">
	<LINK REL="SHORTCUT ICON" HREF="http://www.evandor.com/icon.ico">
</head>

    <frameset cols='201,*,0,0' border=0 id='contentframe'>
			<frame name='l4w_nav'      src='modules/tree/index.php?command=tree'     marginwidth=0 marginheight=0>
			<frame name='l4w_main'	   src='<?=START_PAGE?>'    marginwidth=0 marginheight=0>
			<frame name='executeframe' src='empty.php'     marginwidth=0 marginheight=0>
			<frame name='pingframe'    src='ping.php'      marginwidth=0 marginheight=0>
	</frameset>

</html>