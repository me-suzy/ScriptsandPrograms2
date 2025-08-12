<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	include "lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", _LANG_ORDER_TITLE);
	
	if(is_numeric($_GET["id"]) && $_GET["id"] != 0)
	{
		$db->Query("SELECT id FROM sales WHERE id='" . $_GET["id"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$db->Query("INSERT INTO sale_data (sid, uid, formdata, remote_addr, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $_GET["uid"] . "', '" . serialize($_POST) . "', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "');");
		
		$main->printText(_LANG_ORDER_TRACKED);
	}
	else
		header("Location: " . _SITE_URL . "/index.php?sid=" . $session->ID);

?>