<?php

   	/*=====================================================================
	// $Id: header.inc.php,v 1.2 2005/03/11 12:36:00 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	// Skin handling:
	$css_path = get_skin_css_path ($user_id);
	$img_path = get_skin_img_path ($user_id);

?>
<!DOCTYPE HTML SYSTEM "http://www.evandor.de/HTML4evandor.dtd">
<html>
<head>
	<title><?=$version_name?></title>
	<link href="favicon.gif" rel="SHORTCUT ICON">
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf">
	<link rel='stylesheet'          type='text/css' href='<?=$css_path?>main.css'>
	<LINK REL="SHORTCUT ICON"       HREF="http://www.evandor.de/icon.ico">
	<meta http-equiv="expires"      content="0">
    <script type="text/javascript"  src="javascripts/extern/overlib/overlib.js"><!-- overLIB (c) Erik Bosrup --></script>
</head>

<body background='<?=$img_path?>background.gif'>
<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>
