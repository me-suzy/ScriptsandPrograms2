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

	$tml->RegisterVar("TITLE", _LANG_TICKETS_TITLE);
	
	if(!$user->IsLoggedIn())
		exit($error->Report(_LANG_TICKETS_TITLE, _LANG_ERROR_NOTLOGGEDIN));
	
	if($_GET["action"] == "new")
	{
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!$_POST["subject"] || !$_POST["text"])
				exit($error->Report(_LANG_TICKETS_TITLE, _LANG_ERROR_FIELDEMPTY));
			
			$db->Query("INSERT INTO s_tickets (uid, cid, subject, text, email, urgency, dateStamp) VALUES ('" . $user->Get("id") . "', '" . $_POST["cid"] . "', '" . $_POST["subject"] . "', '" . $_POST["text"] . "', '" . ($_POST["email"] ? "YES" : "NO") . "', '" . $_POST["urgency"] . "', '" . time() . "');");
			
			$main->printText(_LANG_TICKETS_POSTED);
		}
		else
		{
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$db->Query("SELECT id, category FROM s_cats ORDER BY category ASC");
			
			$i	= 1;
			
			while($row = $db->NextRow())
			{
				$tml->RegisterLoop("Categories", $i, $row);
				
				$i++;
			}
			
			$tml->RegisterVar("FNAME", $user->Get("fname"));
			$tml->RegisterVar("SNAME", $user->Get("sname"));
			$tml->RegisterVar("EMAIL", $user->Get("email"));
			
			$tml->loadFromFile("pages/tickets_new");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	elseif($_GET["action"] == "respond")
	{
		$db->Query("SELECT id FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_TICKETS_TITLE, _LANG_TICKETS_WRONGID));
		
		if($_SERVER["REQUEST_METHOD"] == "POST")
		{
			if(!$_POST["message"])
				exit($error->Report(_LANG_TICKETS_TITLE, _LANG_ERROR_FIELDEMPTY));
			
			$db->Query("INSERT INTO s_posts (tid, message, dateStamp) VALUES ('" . $_GET["tid"] . "', '" . $_POST["message"] . "', '" . time() . "');");
			
			$status	= $db->Fetch("SELECT status FROM s_tickets WHERE id='" . $_GET["tid"] . "'");
			
			if($status == "closed")
				$db->Query("UPDATE s_tickets SET status='open' WHERE id='" . $_GET["tid"] . "'");
			
			$main->printText(_LANG_TICKETS_RESPONDED);
		}
		else
		{
			$tml->loadFromFile("pages/header");
			$tml->Parse();
			
			$tml->RegisterVar("TID", $_GET["tid"]);
			
			$tml->loadFromFile("pages/tickets_respond");
			$tml->Parse();
			
			$tml->loadFromFile("pages/footer");
			$tml->Parse();
			
			$tml->Output();
		}
	}
	elseif($_GET["action"] == "view")
	{
		$db->Query("SELECT id FROM s_tickets WHERE id='" . $_GET["tid"] . "' AND uid='" . $user->Get("id") . "'");
		
		if($db->NumRows() == 0)
			exit($error->Report(_LANG_TICKETS_TITLE, _LANG_TICKETS_WRONGID));
		
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$ticketdata	= $db->Fetch("SELECT id, cid, subject, text, urgency, status, dateStamp FROM s_tickets WHERE id='" . $_GET["tid"] . "' AND uid='" . $user->Get("id") . "'");
		
		$urgency	= $main->Urgency($ticketdata["urgency"]);
		
		$tml->RegisterVar("CATEGORY",	$db->Fetch("SELECT category FROM s_cats WHERE id='" . $ticketdata["cid"] . "'"));
		$tml->RegisterVar("DATE",		date(_SITE_DATESTAMP, $ticketdata["dateStamp"]));
		$tml->RegisterVar("TIME",		date("h:i:s", $ticketdata["dateStamp"]));
		$tml->RegisterVar("RESOLVED",	$ticketdata["resolved"] == 1 ? "yes" : "no");
		$tml->RegisterVar("SUBJECT",	$ticketdata["subject"]);
		$tml->RegisterVar("STATUS",		$ticketdata["status"]);
		$tml->RegisterVar("PROBLEM",	nl2br(htmlentities($ticketdata["text"])));
		$tml->RegisterVar("PROBLEM",	nl2br(htmlentities($ticketdata["text"])));
		$tml->RegisterVar("RESOLUTION",	$ticketdata["resolution"] == "" ? "-" : nl2br(htmlentities($ticketdata["resolution"])));
		$tml->RegisterVar("COLOR",		$urgency["color"]);
		$tml->RegisterVar("TICKET",		$ticketdata["id"]);
		
		$tml->RegisterVar("SIGNUP",		date(_SITE_DATESTAMP, $user->Get("regdate")));
		$tml->RegisterVar("FNAME",		$user->Get("fname"));
		$tml->RegisterVar("SNAME",		$user->Get("sname"));
		$tml->RegisterVar("EMAIL",		$user->Get("email"));
		
		$db->Query("SELECT message, type, dateStamp FROM s_posts WHERE tid='" . $_GET["tid"] . "' ORDER BY dateStamp ASC");
		
		$i	= 1;
		
		while($row = $db->NextRow())
		{
			$row["date"]	= date(_SITE_DATESTAMP, $row["dateStamp"]);
			$row["time"]	= date("h:i:s", $row["dateStamp"]);
			$row["message"]	= nl2br(htmlentities($row["message"]));
			$row["from"]	= $row["type"] == "from" ? $user->Get("fname") . " " . $user->Get("sname") : "Admin";
			
			$tml->RegisterLoop("Messages", $i, $row);
			
			$i++;
		}
		
		$tml->RegisterVar("COUNT", $i == 1 ? 0 : $i - 1);
		
		$tml->loadFromFile("pages/tickets_view");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}
	else
	{
		$tml->loadFromFile("pages/header");
		$tml->Parse();
		
		$db->Query("SELECT id, subject, text, urgency, status, dateStamp FROM s_tickets WHERE uid='" . $user->Get("id") . "' ORDER BY dateStamp DESC");
		
		$i	= 1;
		$j	= 1;
		$k	= 1;
		
		while($row = $db->NextRow())
		{
			$uData			= $main->Urgency($row["urgency"]);
			
			$row["date"]	= date(_SITE_DATESTAMP, $row["dateStamp"]);
			$row["time"]	= date("h:i:s", $row["dateStamp"]);
			$row["color"]	= $uData["color"];
			$row["urgency"]	= $uData["urgency"];
			
			if($row["status"] == "open")
			{
				$tml->RegisterLoop("OTickets", $i, $row);
				
				$i++;
			}
			elseif($row["status"] == "pending")
			{
				$tml->RegisterLoop("PTickets", $k, $row);
				
				$k++;
			}
			else
			{
				$tml->RegisterLoop("CTickets", $j, $row);
				
				$j++;
			}
		}
		
		$tml->RegisterVar("OPEN",		$i == 1 ? 0 : $j - 1);
		$tml->RegisterVar("PENDING",	$k == 1 ? 0 : $k - 1);
		$tml->RegisterVar("CLOSED",		$j == 1 ? 0 : $j - 1);
		
		$tml->loadFromFile("pages/tickets");
		$tml->Parse();
		
		$tml->loadFromFile("pages/footer");
		$tml->Parse();
		
		$tml->Output();
	}
	
?>