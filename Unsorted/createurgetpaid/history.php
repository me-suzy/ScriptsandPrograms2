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

	$tml->RegisterVar("TITLE", _LANG_HISTORY_TITLE);

	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_HISTORY_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	$tml->loadFromFile("pages/header");
	$tml->Parse();
	
	$start	= (isset($_GET["start"])) ? intval($_GET["start"]) : 0;
	
	$db->Query("SELECT id, aid, uid, type, c_type, credits, dateStamp FROM actions WHERE uid='" . $user->Get("id") . "' ORDER BY id DESC LIMIT $start, " . _MEMBER_LATESTACTPP);
	
	$i	= 1;
	
	while($row = $db->NextRow())
	{
		$pts	= 0;
		$tp		= 0;
		
		if($row["type"] == "emails")
		{
			$type	= _LANG_STATS_STATMAILS;
			$table	= "paid_emails";
			$field	= "subject";
		}
		elseif($row["type"] == "clicks")
		{
			$type	= _LANG_STATS_STATCLICKS;
			$table	= "paid_clicks";
			$field	= "title";
		}
		elseif($row["type"] == "signup")
		{
			$type	= _LANG_STATS_STATSIGNUP;
			$table	= "paid_signups";
			$field	= "title";
		}
		elseif($row["type"] == "lead")
		{
			$type	= _LANG_STATS_STATLEAD;
			$table	= "leads";
			$field	= "name";
		}
		elseif($row["type"] == "sale")
		{
			$type	= _LANG_STATS_STATSALE;
			$table	= "sales";
			$field	= "name";
		}
		elseif($row["type"] == "credits")
		{
			$type	= _LANG_STATS_STATCREDITS;
		}
		elseif($row["type"] == "debits")
		{
			$type	= _LANG_STATS_STATDEBITS;
		}
		elseif($row["type"] == "refund")
		{
			$type	= _LANG_STATS_STATREFUND;
		}
		elseif($row["type"] == "points")
		{
			$type	= _LANG_STATS_STATPOINTS;
			$pts	= 1;
		}
		elseif($row["type"] == "dpoints")
		{
			$type	= _LANG_STATS_STATDPOINTS;
			$pts	= 1;
		}
		elseif($row["type"] == "transfer_to")
		{
			$type	= _LANG_STATS_STATTRANSFERTO;
			$tp		= 1;
			
			if($row["c_type"] == "points")
				$pts = 1;
		}
		elseif($row["type"] == "transfer_from")
		{
			$type	= _LANG_STATS_STATTRANSFERFROM;
			$tp		= 1;
			
			if($row["c_type"] == "points")
				$pts = 1;
		}
		elseif($row["type"] == "bubble_to")
			$type	= _LANG_STATS_STATBUBBLETO;
		elseif($row["type"] == "bubble_from")
			$type	= _LANG_STATS_STATBUBBLEFROM;
		elseif($row["type"] == "ht_won")
			$type	= _LANG_STATS_STATHTWON;
		elseif($row["type"] == "ht_lost")
			$type	= _LANG_STATS_STATHTLOST;
		elseif($row["type"] == "scratch_won")
			$type	= _LANG_STATS_STATSCRATCHWON;
		elseif($row["type"] == "scratch_paid")
			$type	= _LANG_STATS_STATSCRATCHPAID;
		elseif($row["type"] == "deposit")
			$type	= _LANG_STATS_STATDEPOSIT;
		elseif($row["type"] == "payout")
			$type	= _LANG_STATS_STATPAYOUT;
		else
			$type	= _LANG_STATS_STATUNKNOWN;
		
		if($table != "" && $row["aid"] != 0)
		{
			$db->Query("SELECT id FROM ${table} WHERE id='" . $row["aid"] . "'", 2);
			
			if($db->NumRows(2) == 1)
			{
				$data	= $db->Fetch("SELECT $field, c_type FROM $table WHERE id='" . $row["aid"] . "'", 2);
				
				$row["action"]	= $type . $data[$field];
			}
		}
		elseif($tp == 1)
		{
			$email	= $db->Fetch("SELECT email FROM users WHERE id='" . $row["aid"] . "'", 2);
			
			$row["action"]	= $type . $email;
		}
		else
			$row["action"]	= $type;
		
		$row["date"]	= date(_SITE_DATESTAMP . " h:i", $row["dateStamp"]);
		$row["credits"]	= number_format($row["credits"], $pts == 1 || $data["c_type"] == "points" ? 2 : 4, ".", "");
		
		$row["c_type"]	= $data["c_type"] == "points" || $pts == 1 ? _LANG_STATS_POINTS : _LANG_STATS_CASH;
		
		$tml->RegisterLoop("Actions", $i, $row);
		
		$i++;
	}
	
	$db->Query("SELECT id FROM actions WHERE uid='" . $user->Get("id") . "'");
	
	$tml->RegisterVar("NAV",	$main->GeneratePages(_SITE_URL . "/history.php?sid=" . $session->ID, $db->NumRows(), _MEMBER_LATESTACTPP, $start));
	
	$tml->RegisterVar("COUNT",	$i == 1 ? 0 : $i - 1);
	
	$tml->loadFromFile("pages/history");
	$tml->Parse();
	
	$tml->loadFromFile("pages/footer");
	$tml->Parse();
	
	$tml->Output();

?>