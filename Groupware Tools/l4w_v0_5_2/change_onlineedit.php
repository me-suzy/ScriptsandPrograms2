<?php

	/*=====================================================================
	// $Id: change_onlineedit.php,v 1.1 2005/04/15 11:06:51 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");

	// --- GET / POST -----------------------------------------------
	
	// permissions
	
	// set
	if ($_REQUEST['active'] == "true")
	    $_SESSION['onlineedit'] = "true";
	else {
	    $_SESSION['onlineedit'] = "false";
    }
	
?>
<html>
<head>
	&nbsp;
</head>
<body>
</body>
</html>