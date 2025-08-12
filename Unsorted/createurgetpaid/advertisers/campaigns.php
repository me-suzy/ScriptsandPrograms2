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

	include "../lib/.htconfig.php";
	
	$tml->RegisterVar("TITLE", _LANG_ADVERTISERS_TITLE);
	
	if(!$user->IsAdvertiser())
		exit($error->Report(_LANG_ADVERTISERS_TITLE, _LANG_ERROR_NOADVERTISER));
	
	$tml->RegisterVar("EMAIL", $user->Get("email"));
	
	$tml->loadFromFile("pages/header");
	$tml->Parse();
	
	if($_GET["action"] == "adstats")
	{
		$db->Query("SELECT * FROM ads WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$row["url"]			= substr($row["url"], 0, 45);
			@$row["clickratio"]	= round(($row["clicks"] / $row["views"] * 100), 3);
			
			$tml->RegisterLoop("AdStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/adstats");
		$tml->Parse();
	}
	elseif($_GET["action"] == "clickstats")
	{
		$db->Query("SELECT * FROM paid_clicks WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			@$row["ratio"]	= round(($row["clicks"] / $row["sent"] * 100), 3);
			
			$tml->RegisterLoop("ClickStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/paidclickstats");
		$tml->Parse();
	}
	elseif($_GET["action"] == "emailstats")
	{
		$db->Query("SELECT * FROM paid_emails WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			@$row["ratio"]	= round(($row["clicks"] / $row["sent"] * 100), 3);
			
			$tml->RegisterLoop("EmailStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/paidemailstats");
		$tml->Parse();
	}
	elseif($_GET["action"] == "signupstats")
	{
		$db->Query("SELECT * FROM paid_signups WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$db->Query("SELECT id FROM received_signups WHERE sid='" . $row["id"] . "'", 2);
			
			$row["current"]	= $db->NumRows();
			$row["status"]	= @round(($db->NumRows() / $row["max"]) * 100, 2);
			
			$tml->RegisterLoop("SignupStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/paidsignupstats");
		$tml->Parse();
	}
	elseif($_GET["action"] == "leadstats")
	{
		$db->Query("SELECT * FROM leads WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$db->Query("SELECT id FROM lead_data WHERE lid='" . $row["id"] . "' AND status='unchecked'", 2);
			
			$row["unchecked"]	= $db->NumRows(2);
			
			$db->Query("SELECT id FROM lead_data WHERE lid='" . $row["id"] . "' AND status='checked'", 2);
			
			$row["checked"]		= $db->NumRows(2);
			
			$tml->RegisterLoop("LeadStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/leadstats");
		$tml->Parse();
	}
	elseif($_GET["action"] == "salestats")
	{
		$db->Query("SELECT * FROM sales WHERE aid='" . $user->Get("id") . "'");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$db->Query("SELECT id FROM sale_data WHERE sid='" . $row["id"] . "' AND status='unchecked'", 2);
			
			$row["unchecked"]	= $db->NumRows(2);
			
			$db->Query("SELECT id FROM sale_data WHERE sid='" . $row["id"] . "' AND status='checked'", 2);
			
			$row["checked"]		= $db->NumRows(2);
			
			$tml->RegisterLoop("SaleStats", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/advertisers/salestats");
		$tml->Parse();
	}
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>