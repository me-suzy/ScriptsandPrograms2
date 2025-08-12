<?php

  /**
    * $Id: header.inc.php,v 1.6 2005/07/27 13:00:53 carsten Exp $
    *
    * common header file
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	// Skin handling:
	$css_path = "../../".get_skin_css_path ();
	$img_path = "../../".get_skin_img_path ();

?>
<html>
<head>
	<title><?=$version_name?></title>
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf">
	<link rel='stylesheet'          type='text/css' href='<?=$css_path?>main.css'>
	<LINK REL="SHORTCUT ICON"       HREF="http://www.evandor.de/icon.ico">
	<meta http-equiv="expires"      content="0">
    <script type="text/javascript"  src="../../javascripts/extern/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
    <script type="text/javascript"  src="../common/functions.js"></script>
</head>

<body>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
