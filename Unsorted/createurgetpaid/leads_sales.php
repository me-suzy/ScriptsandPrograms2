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

	$tml->RegisterVar("TITLE", _LANG_LEADSSALES_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if(is_numeric($_GET["lid"]) || is_numeric($_GET["aid"]))
	{
		if(is_numeric($_GET["lid"]))
		{
			$get	= "lid";
			$type	= "lid";
			$pre	= "lead";
		}
		else
		{
			$get	= "aid";
			$type	= "sid";
			$pre	= "sale";
		}
		
		$db->Query("SELECT id FROM ${pre}s WHERE id='" . $_GET[$get] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_LEADSSALES_TITLE, _LANG_ERROR_ERROROCCURED));
		
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$data	= $db->Fetch("SELECT id, name, description, description2, html, url, max, type, c_type, credits FROM ${pre}s WHERE id='" . $_GET[$get] . "'");
		
		$tml->RegisterVar("C_TYPE",				$data["c_type"] == "points" ? _LANG_STATS_POINTS : _LANG_STATS_CASH);
		$tml->RegisterVar("MAX_SUBMISSIONS",	$data["max"]);
		$tml->RegisterVar("DESCRIPTION",		$data["description"]);
		$tml->RegisterVar("DESCRIPTION2",		$data["description2"]);
		$tml->RegisterVar("CREDITS",			number_format($data["credits"], 2));
		$tml->RegisterVar("HTML",				htmlentities($data["html"]));
		$tml->RegisterVar("NAME",				$data["name"]);
		$tml->RegisterVar("UID",				$user->Get("id"));
		$tml->RegisterVar("ID",					$data["id"]);
		
		if($data["type"] == "form")
			$tml->RegisterVar("TYPE", 1);
		else
		{
			$data["url"]	= eregi_replace("#EMAIL", $user->Get("email"), $data["url"]);
			$data["url"]	= eregi_replace("#UID", $user->Get("id"), $data["url"]);
			
			$tml->RegisterVar("URL",	$data["url"]);
			$tml->RegisterVar("TYPE",	2);
		}
		
		$db->Query("SELECT id FROM ${pre}_data WHERE ${type}='" . $_GET[$get] . "'");
		
		$tml->RegisterVar("CURRENT_SUBMISSIONS", $db->NumRows());
		
		$db->Query("SELECT id FROM ${pre}_data WHERE ${type}='" . $_GET[$get] . "' AND uid='" . $user->Get("id") . "'");
		
		$tml->RegisterVar("USER_SUBMISSIONS", $db->NumRows());
		
		$tml->loadFromFile("pages/${pre}");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id, name, description FROM leads WHERE active='yes'");
		
		$i	= 1;
		
		while($data = $db->NextRow())
		{
			$data["description"]	= $data["description"];
			
			$tml->RegisterLoop("Leads", $i, $data);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT_LEADS", $i == 1 ? 0 : $i - 1);
		
		$db->Query("SELECT id, name, description FROM sales WHERE active='yes'");
		
		$i	= 1;
		
		while($data = $db->NextRow())
		{
			$data["description"]	= $data["description"];
			
			$tml->RegisterLoop("Sales", $i, $data);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT_SALES", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/leadsale");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
	}
	
	$tml->Output();

?>