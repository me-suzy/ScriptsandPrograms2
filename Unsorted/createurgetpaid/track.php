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
	
	$tml->RegisterVar("TITLE", _LANG_TRACKER_TITLE);
	
	$db->Query("SELECT id FROM users WHERE id='" . $_GET["uid"] . "'");
	
	$count_user	= $db->NumRows();
	
	if($_GET["t"] == "paidmail")
		$db->Query("SELECT id FROM paid_emails WHERE id='" . $_GET["pid"] . "'");
	
	if($_GET["t"] == "paidclick")
		$db->Query("SELECT id FROM paid_clicks WHERE id='" . $_GET["pid"] . "'");
	
	$count_mail	= $db->NumRows();
	
	if(($_GET["t"] != "paidmail" && $_GET["t"] != "paidclick") || $count_user <= 0 || $count_mail != 1)
		exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_WRONGLINK, $_GET["a"] == "credit" ? 1 : 0));
	
	$db->Query("UPDATE users SET lastactive='" . time() . "', lastlogin='" . time() . "', sentmail='no' WHERE id='" . $_GET["uid"] . "'");
	
	if($_GET["t"] == "paidmail")
		$db->Query("SELECT id FROM sent_emails WHERE id='" . $_GET["id"] . "' AND uid='" . $_GET["uid"] . "' AND mid='" . $_GET["pid"] . "' AND status='unread'");
	
	if($_GET["t"] == "paidclick")
		$db->Query("SELECT id FROM sent_clicks WHERE id='" . $_GET["id"] . "' AND uid='" . $_GET["uid"] . "' AND cid='" . $_GET["pid"] . "' AND status!='locked'");
	
	$count_sent	= $db->NumRows();
	
	if($count_sent != 1 && $_GET["a"] == "credit")
		exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_GOTCREDITED, 1));
	
	if($_GET["t"] == "paidmail")
	{
		$type		= "emails";
		$field		= "clickthrus";
	}
	elseif($_GET["t"] == "paidclick")
	{
		$type		= "clicks";
		$field		= "ptc";
		
		$sdata		= $db->Fetch("SELECT onClickthru, status, dateStamp FROM sent_" . $type . " WHERE id='" . $_GET["id"] . "'", 2);
	}
	else
		exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_WRONGLINK, 1));
	
	$data		= $db->Fetch("SELECT id, url, timer, c_type, credits, ref_earnings, active FROM paid_" . $type . " WHERE id='" . $_GET["pid"] . "'", 2);
	
	if($data["active"] == "no")
		exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_WRONGLINK, 1));
	
	$field	= $data["c_type"] == "points" ? "points" : $field;
	
	if($_GET["a"] == "credit")
	{
		if($_GET["p"] == "read")
		{
			if(((time() - $data["timer"]) < $session->Get("timer" . $_GET["id"]) - (($data["timer"] * _MEMBER_TCRATIO) / 100)) || ($session->Get("nocheat" . $_GET["id"]) != "ok") && _MEMBER_TCENABLE == "YES")
			{
				$db->Query("DELETE FROM sent_" . $type . " WHERE id='" . $_GET["id"] . "'");
				
				$db->Query("SELECT id FROM cheaters WHERE uid='" . $_GET["uid"] . "'");
				
				if($db->NumRows() == 0)
				{
					$db->Query("SELECT id FROM cheaters");
					
					if($db->NumRows() == 25)
					{
						$db->Query("DELETE FROM cheaters WHERE id='" . $db->Fetch("SELECT id FROM cheaters ORDER BY dateStamp ASC LIMIT 1") . "'");
					}
					
					$userdata	= $db->Fetch("SELECT email, payment_account FROM users WHERE id='" . $_GET["uid"] . "'");
					
					$db->Query("INSERT INTO cheaters (uid, email, payment_account, remote_addr, count, dateStamp) VALUES ('" . $_GET["uid"] . "', '" . $userdata["email"] . "', '" . $userdata["payment_account"] . "', '" . $_SERVER["REMOTE_ADDR"] . "', '1', '" . time() . "');");
				}
				else
					$db->Query("UPDATE cheaters SET count=count+'1' WHERE uid='" . $_GET["uid"] . "'");
				
				if(_MEMBER_TCDELETE == "YES")
				{
					$user->Remove($_GET["uid"]);
					$user->Logout();
					
					exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_DELETED, 1));
				}
				
				if(_MEMBER_TCINACTIVATE == "YES")
				{
					$db->Query("UPDATE users SET active='no' WHERE id='" . $_GET["uid"] . "'");
					$user->Logout();
					
					exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_DEACTIVATED, 1));
				}
				
				if(_MEMBER_TCDEBIT >= 0.01)
				{
					$db->Query("UPDATE users SET debits=debits+'" . _MEMBER_TCDEBIT . "' WHERE id='" . $_GET["uid"] . "'");
					
					exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_DEBITED, 1));
				}
				
				exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_CHEAT, 1));
			}
			
			$db->Query("UPDATE users SET " . $field . "=" . $field . "+'" . $data["credits"] . "' WHERE id='" . $_GET["uid"] . "'");
			$db->Query("UPDATE paid_" . $type . " SET clicks=clicks+1 WHERE id='" . $_GET["pid"] . "'");
			
			if($sdata["onClickthru"] == 1 && $type == "clicks")
			{
				$db->Query("DELETE FROM sent_" . $type . " WHERE cid='" . $_GET["pid"] . "' AND dateStamp='" . $sdata["dateStamp"] . "'");
			}
			elseif($sdata["onClickthru"] > 1 && $type == "clicks")
			{
				$db->Query("UPDATE sent_" . $type . " SET onClickthru='" . ($sdata["onClickthru"] - 1) . "' WHERE cid='" . $_GET["pid"] . "' AND dateStamp='" . $sdata["dateStamp"] . "'");
				
				if($sdata["status"] != "normal")
				{
					$db->Query("UPDATE sent_" . $type . " SET status='locked', clickStamp='" . (time() + (60*60*24)) . "' WHERE id='" . $_GET["id"] . "'");
				}
				else
				{
					$db->Query("DELETE FROM sent_" . $type . " WHERE id='" . $_GET["id"] . "'");
				}
			}
			else
			{
				if($_GET["t"] == "paidmail")
				{
					$db->Query("UPDATE sent_" . $type . " SET status='read' WHERE id='" . $_GET["id"] . "'");
				}
				else
				{
					$db->Query("DELETE FROM sent_" . $type . " WHERE id='" . $_GET["id"] . "'");
				}
			}
			
			if(_REFERRAL_TYPE == "PERCENTAGE" && $data["ref_earnings"] == "yes")
			{
				$referrals->AddCreditsToUplines($_GET["uid"], $data["credits"], $data["c_type"]);
			}
			
			$user->Add2Actions($_GET["uid"], $data["id"], $type, $data["credits"]);
			
			if(_MEMBER_TCENABLE == "YES")
			{
				$session->Set("timer"	. $_GET["id"],	"");
				$session->Set("nocheat"	. $_GET["id"],	"");
			}
			
			$tml->loadFromFile("crediting/received");
		}
		else
		{
			if(($session->Get("trackwait") + $data["timer"]) > time() && (_MEMBER_TRACKWAIT == "YES"))
				exit($error->Report(_LANG_TRACKER_TITLE, _LANG_TRACKER_PLEASEWAIT, 1, 1));
			
			if(_MEMBER_TRACKWAIT == "YES")
				$session->Set("trackwait",	time());
			
			if(_MEMBER_TCENABLE == "YES")
			{
				$session->Set("timer"	. $_GET["id"],	time());
				$session->Set("nocheat"	. $_GET["id"],	"ok");
			}
			
			$tml->RegisterVar("ID",			$_GET["id"]);
			$tml->RegisterVar("PID",		$_GET["pid"]);
			$tml->RegisterVar("UID",		$_GET["uid"]);
			$tml->RegisterVar("TYPE",		$_GET["t"]);
			$tml->RegisterVar("REFRESH",	$data["timer"]);
			
			$tml->loadFromFile("crediting/waiting");
		}
	}
	else
	{
		$tml->RegisterVar("TYPE",	$_GET["t"]);
		$tml->RegisterVar("ID",		$_GET["id"]);
		$tml->RegisterVar("URL",	$data["url"]);
		$tml->RegisterVar("PID",	$_GET["pid"]);
        $tml->RegisterVar("UID",	$_GET["uid"]);
		
		$tml->loadFromFile("crediting/frameset");
	}
	
	$tml->Parse();
	$tml->Output();

?>