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

	$tml->RegisterVar("TITLE", _LANG_CONF_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_CONF_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if($_SERVER["REQUEST_METHOD"] == "POST")
	{
		if(!$_POST["programid"] || !$_POST["confirmation"])
			exit($error->Report(_LANG_CONF_TITLE, _LANG_ERROR_FIELDEMPTY));
		
		$db->Query("SELECT id FROM paid_signups WHERE id='" . $_POST["programid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_CONF_TITLE, _LANG_CONF_PIDINVALID));
		
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $_POST["programid"] . "' AND uid='" . $user->Get("id") . "'");
		
		if($db->NumRows() == 1)
			exit($error->Report(_LANG_CONF_TITLE, _LANG_CONF_DOUBLE));
		
		$db->Query("INSERT INTO received_signups (uid, sid, confirmation, dateStamp) VALUES ('" . $user->Get("id") . "', '" . $_POST["programid"] . "', '" . $_POST["confirmation"] . "', '" . time() . "');");
		
		$max	= $db->Fetch("SELECT max FROM paid_signups WHERE id='" . $_POST["programid"] . "'");
		
		$db->Query("SELECT id FROM received_signups WHERE sid='" . $_POST["programid"] . "' AND NOT (checked='1' AND credited='no')");
		
		if($db->NumRows() == $max)
			$db->Query("UPDATE paid_signups SET active='no' WHERE id='" . $_POST["programid"] . "'");
		
		$main->printText(_LANG_CONF_CONFSENT);
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$tml->RegisterVar("NAME",	$user->Get("fname") . " " . $user->Get("sname"));
		$tml->RegisterVar("EMAIL",	$user->Get("email"));
		
		$tml->loadFromFile("pages/confirmation");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}
	
?>
