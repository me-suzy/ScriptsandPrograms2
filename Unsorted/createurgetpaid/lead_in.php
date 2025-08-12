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
	
	$tml->RegisterVar("TITLE", _LANG_SUBMISSION_TITLE);
	
	if($_GET["confirm"] != "")
	{
		$db->Query("SELECT id FROM lead_data WHERE active='" . $_GET["confirm"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_NOTCONFIRMED));
		
		$data_id	= $db->Fetch("SELECT lid FROM lead_data WHERE active='" . $_GET["confirm"] . "'");
		$conf_mail	= $db->Fetch("SELECT conf_mail FROM leads WHERE id='" . $data_id . "'");
		
		if($conf_mail == "no")
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_NOTCONFIRMED));
		
		$db->Query("UPDATE lead_data SET active='yes' WHERE active='" . $_GET["confirm"] . "'");
		
		$main->printText(_LANG_SUBMISSION_CONFIRMED);
	}
	elseif(is_numeric($_GET["id"]) && $_GET["id"] != 0)
	{
		$db->Query("SELECT id FROM leads WHERE id='" . $_GET["id"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$conf_mail	= $db->Fetch("SELECT conf_mail FROM leads WHERE id='" . $_GET["id"] . "'");
		
		if(!$_POST["name"])
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_NONAME));
		
		if(!$_POST["email"])
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_NOEMAIL));
		
		if(_LEAD_CHECKEMAIL == "YES")
		{
			$db->Query("SELECT formdata FROM lead_data WHERE lid='" . $_GET["id"] . "'");
			
			while($loopdata = $db->NextRow())
			{
				$formdata	= unserialize($loopdata["formdata"]);
				
				if($_POST["email"] == $formdata["email"])
					exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_DOUBLEMAIL));
			}
		}
		
		if(_LEAD_CHECKIP == "YES")
		{
			$db->Query("SELECT remote_addr FROM lead_data WHERE lid='" . $_GET["id"] . "'");
			
			while($loopdata = $db->NextRow())
			{
				if($_SERVER["REMOTE_ADDR"] == $loopdata["remote_addr"])
					exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_SUBMISSION_DOUBLEIP));
			}
		}
		
		if($conf_mail == "yes")
		{
			$chars	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
			mt_srand ((double) microtime() * 1000000);
			
			for($get = strlen($chars); $i != 5; ++$i)
				$active	.= $chars[mt_rand(0, $get)];
		}
		else
			$active	= "yes";
		
		$db->Query("INSERT INTO lead_data (lid, uid, formdata, active, remote_addr, dateStamp) VALUES ('" . $_GET["id"] . "', '" . $_GET["uid"] . "', '" . serialize($_POST) . "', '$active', '" . $_SERVER["REMOTE_ADDR"] . "', '" . time() . "');");
		
		if($conf_mail == "yes")
		{
			$tml->RegisterVar("NAME",	$_POST["name"]);
			$tml->RegisterVar("ACTIVE",	$active);
			
			$tml->loadFromFile("emails/submission");
			$tml->Parse(1);
			
			$main->sendMail($_POST["email"], _LANG_SUBMISSION_TITLE, $tml->GetParsedContent());
			
			$main->printText(_LANG_SUBMISSION_MAILSENT);
		}
		else
			$main->printText(_LANG_SUBMISSION_SAVED);
	}
	else
		header("Location: " . _SITE_URL . "/index.php?sid=" . $session->ID);

?>