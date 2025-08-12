<?php

ob_start();
include_once("sysheader.inc");

// destroy all data associated with current session:
global $g_BizSystem;
$g_BizSystem->GetSessionContext()->Destroy();
	
// Redirect:
header("Location: login.php");
exit;
?>