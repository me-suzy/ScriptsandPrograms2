<?php

  /**
    * $Id: frame.php,v 1.2 2004/12/18 09:20:32 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package contacts
    */
die ("deprecated");
	include ("../../config/config.inc.php");
	include ("../../connect_database.php");
	include ("../../inc/functions.inc.php");

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

<frameset rows='*,200' border=0>
    <frame name='l4w_content'    src='test.php'     marginwidth=0 marginheight=0>
    <frame name='l4w_pingframe'  src='contact_ping.php?object_type=contact' marginwidth=0 marginheight=0 noresize>
</frameset>

</html>