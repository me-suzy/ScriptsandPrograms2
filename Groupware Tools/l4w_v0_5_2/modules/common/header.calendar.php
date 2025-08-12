<?php

  /**
    * $Id: header.calendar.php,v 1.1 2005/07/28 05:58:08 carsten Exp $
    *
    * common header file
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	// Skin handling:
	$css_path = "../".get_skin_css_path ();
	$img_path = "../".get_skin_img_path ();

?>
<html>
<head>
	<title><?=$version_name?></title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf">
	<link rel='stylesheet'          type='text/css' href='<?=$css_path?>calendar.css'>
	<LINK REL="SHORTCUT ICON"       HREF="http://www.evandor.de/icon.ico">
	<meta http-equiv="expires"      content="0">
</head>

<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
