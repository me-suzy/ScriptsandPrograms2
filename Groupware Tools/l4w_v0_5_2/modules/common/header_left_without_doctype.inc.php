<?php

  /**
    * $Id: header_left_without_doctype.inc.php,v 1.3 2004/12/28 16:58:19 carsten Exp $
    *
    * header without doctype declaration. When showing the tree in the left hand 
    * navigation, there are problems when the declaration is included.
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */

	// Skin handling:
	$css_path = "../../".get_skin_css_path ();
	$img_path = "../../".get_skin_img_path ();

?>
<head>
	<title><?=$version_name;?></title>
	<link REL="SHORTCUT ICON"		HREF="http://www.evandor.com/icon.ico">
	<meta http-equiv="content-type" content="text/html;charset=iso-8859-1">
	<meta name="copyright"			content="evandor media GmbH">
	<meta name="author" 			content="Carsten Gräf">
	<meta name="publisher"			content="evandor media GmbH">
	<meta http-equiv="expires" content="0">
	<link rel='stylesheet' type='text/css' href='<?=$css_path?>left.css'>
</head>

<body background='<?=$img_path?>background_left.jpg' topmargin="0" leftmargin="0" marginheight="0"  marginwidth="0">