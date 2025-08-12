<?php

   	/*=====================================================================
	// $Id: header_left.inc.php,v 1.1 2004/11/04 08:30:47 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	// Skin handling:
	$css_path = get_skin_css_path ($user_id);
	$img_path = get_skin_img_path ($user_id);

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