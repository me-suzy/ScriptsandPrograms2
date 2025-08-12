<?php

	/*=====================================================================
	// $Id: change_helpmode.php,v 1.1 2005/04/16 06:36:18 carsten Exp $
    // copyright evandor media Gmbh 2004
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");

	// --- GET / POST -----------------------------------------------
	
	// permissions
	
	// set
	if ($_REQUEST['active'] == "true")
	    $_SESSION['helpmode'] = "true";
	else {
	    $_SESSION['helpmode'] = "false";
    }
	
?>
<html>
<head>
	&nbsp;
</head>
<body>
</body>
</html>