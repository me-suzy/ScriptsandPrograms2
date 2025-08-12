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
	
	class User
	{
	
		var $data = Array();
	
		function User($ID = "")
		{
			if($ID)
				$this->LoadByID($ID);
			else
				$this->LoadFromCookie();
		}
		
		function LoadByID($ID)
		{
			GLOBAL $db;
			
			if($ID)
			{
				$db->Query("UPDATE users SET lastactive='" . time() . "', sentmail='no' WHERE id='$ID'");
				
				$db->Query("SELECT * FROM users WHERE id='$ID'");
				
				$this->data	= $db->NextRow();
			}
		}
		
		function GetCheckString($ID)
		{
			return md5("sTr1nG" . $ID . _DB_PASS . ($ID+328));
		}
		
		function LoadFromCookie()
		{
			GLOBAL $session;
			
			if($this->IsLoggedIn())
			{
				$this->LoadByID($_COOKIE["uid"] >= 1 ? $_COOKIE["uid"] : $session->Get("uid"));
			}
		}
		
		function IsEmail($email)
		{
			$GLOBALS["db"]->Query("SELECT id FROM users WHERE email='" . addslashes($email) . "'");
			
			return $GLOBALS["db"]->NumRows() == 0 ? false : true;
		}
		
		function IsPassword($email, $password)
		{
			$GLOBALS["db"]->Query("SELECT id FROM users WHERE email='" . addslashes($email) . "' AND password='" . addslashes($password) . "'");
			
			return $GLOBALS["db"]->NumRows() == 1 ? true : false;
		}
		
		function IsLoggedIn()
		{
			GLOBAL $session;
			
			$remote_addr	= $_COOKIE["rad"] != "" ? $_COOKIE["rad"] : $session->Get("rad");
			$checkString	= $_COOKIE["chk"] != "" ? $_COOKIE["chk"] : $session->Get("chk");
			$UID			= $_COOKIE["uid"] >= 1 ? $_COOKIE["uid"] : $session->Get("uid");
			
			return ($checkString == $this->GetCheckString($UID) && $remote_addr == $_SERVER["REMOTE_ADDR"]);
		}
		
		function Login($email, $password, $mode = 1)
		{
			GLOBAL $db, $session;
			
			$UID	= $db->Fetch("SELECT id FROM users WHERE email='" . addslashes($email) . "' AND password='" . addslashes($password) . "'");
			
			$db->Query("UPDATE users SET lastlogin='" . time() . "' WHERE id='$UID'");
			$db->Query("UPDATE users SET sessions=sessions+'1' WHERE id='$UID'");
			
			$db->Query("REPLACE INTO login_logs SET uid='$UID', browser='" . trim($_SERVER["HTTP_USER_AGENT"]) . "', remote_addr='" . $_SERVER["REMOTE_ADDR"] . "', dateStamp='" . time() . "'");
			
			$expire	= $mode == 1 ? time() + _MEMBER_LOGINCOOKIE : 0;
			
			setcookie("rad",		$_SERVER["REMOTE_ADDR"], $expire);
			setcookie("chk",		$this->GetCheckString($UID), $expire);
			setcookie("uid",		$UID, $expire);
			
			$session->Set("rad",	$_SERVER["REMOTE_ADDR"]);
			$session->Set("chk",	$this->GetCheckString($UID));
			$session->Set("uid",	$UID);
			
			$session->Save();
			
			return $UID >= 1;
		}
		
		function Logoff()
		{
			GLOBAL $session;
			
			setcookie("rad",		"");
			setcookie("chk",		"");
			setcookie("uid",		"");
			
			$session->Set("rad",	"");
			$session->Set("chk",	"");
			$session->Set("uid",	"");
			
			$session->Save();
		}
		
		function Add($email, $password)
		{
			$GLOBALS["db"]->Query("INSERT INTO users (email, password) VALUES ('" . addslashes($email) . "', '" . addslashes($password) . "')");
			
			return $GLOBALS["db"]->LastInsertID();
		}
		
		function Remove($UID)
		{
			GLOBAL $db;
			
			$db->Query("DELETE FROM actions WHERE uid='$UID'", 11);
			$db->Query("DELETE FROM users WHERE id='$UID'", 11);
			$db->Query("DELETE FROM refs WHERE rid='$UID'", 11);
			$db->Query("DELETE FROM sent_clicks WHERE uid='$UID'", 11);
			$db->Query("DELETE FROM sent_emails WHERE uid='$UID'", 11);
			
			if(_REFERRAL_MOVETIER == "YES")
				$GLOBALS["referrals"]->MoveTier($UID);
			else
				$db->Query("DELETE FROM refs WHERE uid='$UID'", 11);
			
			return true;
		}
		
		function Get($name)
		{
			return $GLOBALS["main"]->Trim($this->data[$name]);
		}
		
		function Set($name, $value)
		{
			$this->data[$name] = $value;
		}
		
		function Save()
		{
			foreach($this->data as $key => $value)
			{
				if(!is_numeric($key))
				{
					if($qSet)
						$qSet .= ", ";
					
					$qSet	.= "$key = '" . addslashes($value) . "'";
				}
			}
			
			$GLOBALS["db"]->Query("UPDATE users SET $qSet WHERE id='" . $this->data["id"] . "'");
		}
		
		function NumMembers($a = 0)
		{
			GLOBAL $db;
			
			$db->Query("SELECT id FROM users" . ($a == 1 ? "" : " WHERE active='yes' AND vacation='0'"));
			
			return $db->NumRows();
		}
		
		function NumPayments()
		{
			GLOBAL $db;
			
			$db->Query("SELECT id FROM payments WHERE paid='yes'");
			
			return $db->NumRows();
		}
		
		function IsAdvertiser()
		{
			return $this->Get("advertiser") == "yes" ? true : false;
		}
		
		function IsOperator()
		{
			return $this->Get("operator") == "yes" && $this->IsLoggedIn() ? true : false;
		}
		
		function IsActive($email)
		{
			$active	= $GLOBALS["db"]->Fetch("SELECT active FROM users WHERE email='" . addslashes($email) . "'");
			
			return $active == "yes" ? true : false;
		}
		
		function SendActivationEmail($email, $hash)
		{
			GLOBAL $tml;
			
			$tml->RegisterVar("HASH", $hash);
			
			$tml->loadFromFile("emails/activation");
			$tml->Parse(1);
			
			return $GLOBALS["main"]->sendMail($email, _LANG_SIGNUP_ACTIVATION, $tml->GetParsedContent());
		}
		
		function SendSignupEmail($email)
		{
			GLOBAL $tml;
			
			$userdata	= $GLOBALS["db"]->Fetch("SELECT id, password FROM users WHERE email='" . addslashes($email) . "'");
			
			$tml->RegisterVar("UID",		$userdata["id"]);
			$tml->RegisterVar("PASSWORD",	$userdata["password"]);
			$tml->RegisterVar("EMAIL",		$email);
			$tml->RegisterVar("DATE",		date("l F d Y"));
			
			$tml->loadFromFile("emails/signup");
			$tml->Parse(1);
			
			return $GLOBALS["main"]->sendMail($email, _LANG_SIGNUP_WELCOME, $tml->GetParsedContent());
		}
		
		function ResendPassword($email)
		{
			GLOBAL $tml;
			
			$password	= $GLOBALS["db"]->Fetch("SELECT password FROM users WHERE email='" . addslashes($email) . "'");
			
			$tml->RegisterVar("PASSWORD",	$password);
			$tml->RegisterVar("EMAIL",		$email);
			$tml->RegisterVar("DATE",		date("l F d Y"));
			
			$tml->loadFromFile("emails/password");
			$tml->Parse(1);
			
			return $GLOBALS["main"]->sendMail($email, _LANG_MEMBERS_PASSWORD, $tml->GetParsedContent());
		}
		
		function Add2Actions($UID, $AID, $type, $credits, $c_type = "unknown")
		{
			GLOBAL $db;
			
			if(_MEMBER_LATESTACT >= 1)
			{
				$db->Query("SELECT id FROM actions WHERE uid='$UID'", 8);
				
				if($db->NumRows(8) >= _MEMBER_LATESTACT)
				{
					$ID	= $db->Fetch("SELECT id FROM actions WHERE uid='$UID' ORDER BY id LIMIT 1", 8);
					
					$db->Query("DELETE FROM actions WHERE id='$ID'", 8);
				}
			}
			
			$db->Query("INSERT INTO actions (uid, aid, type, c_type, credits, dateStamp) VALUES ('$UID', '$AID', '$type', '$c_type', '$credits', '" . time() . "');", 8);
		}
		
		function VerifyData($POST, $SERV)
		{
			$blocklist	= $GLOBALS["db"]->Fetch("SELECT email, remote_addr, payment_account FROM blocklist");
			
			foreach($blocklist AS $field => $value)
			{
				if(is_numeric($field))
					continue;
				
				$value	= explode("|", $value);
				
				foreach($value AS $valueid => $valuename)
				{
					if(!strcasecmp("remote_addr", $field))
					{
						$field		= strtoupper($field);
						
						$fetchfrom	= $SERV;
					}
					else
						$fetchfrom	= $POST;
					
					if($fetchfrom[$field] == $valuename && $fetchfrom[$field] != "")
						return false;
				}
			}
			
			return true;
		}
		
		function ClickQueue($UID)
		{
			GLOBAL $db;
			
			$db->Query("SELECT id, cid, queue FROM sent_queue");
			
			while($queue = $db->NextRow())
			{
				$db->Query("INSERT INTO sent_clicks (cid, uid, dateStamp) VALUES ('" . $queue["cid"] . "', '$UID', '" . time() . "');", 2);
				
				$db->Query("UPDATE paid_clicks SET sent=sent+'1' WHERE id='" . $queue["cid"] . "'", 2);
				
				if($queue["queue"] == 1)
				{
					$db->Query("DELETE FROM sent_queue WHERE id='" . $queue["id"] . "'", 2);
				}
				else
					$db->Query("UPDATE sent_queue SET queue=queue-'1' WHERE id='" . $queue["id"] . "'", 2);
			}
		}
	}
	
	$user	= new User;

?>