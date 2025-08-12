<?php
	ob_start();	//must turn on buffering so that every page buffers. This is because there are functions that write cookies that can be
				// called after other text has already been output (such as the DOMAIN_OneTimePopup() function)
				// DB_CloseDomains() flushes the output
	require(dirname(__FILE__)."/../Configurations/PHPJK_Config.php");
	require("class.phpmailer.php");
	require("i_Globals.php");
	require("i_Colors.php");
	require("i_Domains.php");
	require("i_Accounts.php");
	require("i_Structure.php");
	Require("i_Administration.php");
	Require("i_Upload.php");
	Require("i_GlfAx.php");

	if ( ( $sOS == "" ) && ( strpos($_SERVER["SCRIPT_NAME"], "PHPJK_Installation") === False ) ) {
		echo "Please run the installation script before accessing the gallery.";
		exit;
	}
?>
