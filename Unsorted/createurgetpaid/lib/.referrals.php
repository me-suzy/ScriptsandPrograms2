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
	
	class Referrals
	{
	
		function GetLevelData($membership = 0, $pos = 0)
		{
			GLOBAL $db;
			
			if($membership >= 1 && $db->Fetch("SELECT COUNT(id) FROM memberships WHERE id='$membership'", 16) == 1)
			{
				$referral_levels	= $db->Fetch("SELECT ref_levels FROM memberships WHERE id='$membership'", 16);
			}
			else
			{
				$referral_levels	= _REFERRAL_LEVELS;
			}
			
			$level_data	= explode("|", $referral_levels);
			
			return $level_data[$pos];
		}
		
		function GetAcceptedWithin()
		{
			return (_REFERRAL_WITHIN*24*60*60);
		}
		
		function ParentID($UID)
		{
			return $GLOBALS["db"]->Fetch("SELECT uid FROM refs WHERE rid='$UID'");
		}
		
		function GetRefID($R)
		{
			GLOBAL $db, $session;
			
			if(!is_numeric($R) || $R == 0)
			{
				$db->Query("SELECT id FROM users WHERE active='yes' AND premium>='1'");
				
				if($db->NumRows() >= 1)
				{
					$db->Query("SELECT id, premium FROM users WHERE active='yes' AND premium>='1'");
					
					$refs	= Array();
					
					while($row = $db->NextRow())
					{
						$weight	= $db->Fetch("SELECT weight FROM memberships WHERE id='" . $row["premium"] . "'", 2);
						
						for($i = 0; $i < $weight; $i++)
						{
							array_push($refs, $row["id"]);
						}
					}
					
					srand((float) microtime() * 1000000);
					shuffle($refs);
					
					return $refs[0];
				}
				else
					return "";
			}
			else
			{
				$db->Query("SELECT id FROM users WHERE id='$R'");
				
				if($db->NumRows() == 0)
					return $this->GetRefID(0);
				else
				{
					if($session->Get("track_r") != "set")
					{
						$db->Query("UPDATE users SET ref_hits=ref_hits+'1' WHERE id='$R'");
						
						$session->Set("track_r", "set");
					}
					
					return $R;
				}
			}
		}
		
		function MoveTier($UID)
		{
			GLOBAL $db;
			
			$par_id	= $this->ParentID($UID);
			
			if($par_id >= 1)
			{
				$ch_id	= Array();
				$i		= 0;
				
				$db->Query("SELECT rid FROM refs WHERE uid='$UID'", 11);
				
				while($id = $db->NextRow())
				{
					$ch_id[$i]	= $id["rid"];
					
					$i++;
				}
				
				for($i = 0; $i < count($ch_id); $i++)
				{
					$db->Query("UPDATE refs SET uid='$par_id' WHERE rid='" . $ch_id[$i] . "'", 11);
				}
			}
			else
			{
				$db->Query("DELETE FROM refs WHERE uid='$UID'", 11);
			}
		}
		
		function UpdateReferralStatus()
		{
			GLOBAL $db;
			
			$db->Query("SELECT id, rid FROM refs WHERE status='0'");
			$t	= time();
			
			while($row = $db->NextRow())
			{
				$userdata	= $db->Fetch("SELECT premium, email, clickthrus, ptc, paidsignups, leads_sales, games, credits, bonus, debits, referral_data, sessions, regdate FROM users WHERE id='" . $row["rid"] . "'", 2);
				$v			= $userdata["regdate"] + $this->GetAcceptedWithin();
				$tot		= 0;
				$var		= 0;
				
				if($t >= $v)
				{
					if(_REFERRAL_LOGGEDIN != 0)
					{
						$tot	+= 1;
						
						if(_REFERRAL_LOGGEDIN <= $userdata["sessions"])
							$var	+= 1;
					}
					
					if(_REFERRAL_EARNED != 0)
					{
						$tot	+= 1;
						
						$data			= unserialize($userdata["referral_data"]);
						
						$ref_earnings	= 0;
						
						for($i = 0; $i < $this->GetLevelData($userdata["premium"]); $i++)
						{
							$level			= $i + 1;
							$ref_earnings	+= $data["level_$level"];
						}
						
						$total_earnings	= $userdata["clickthrus"] + $userdata["ptc"] + $userdata["paidsignups"] + $userdata["leads_sales"] + $userdata["games"] + $userdata["credits"] + $userdata["bonus"] + $refearnings - $userdata["debits"];
						
						if(_REFERRAL_EARNED <= $total_earnings)
							$var	+= 1;
					}
					
					if($tot == $var)
					{
						$this->AddCreditsToUplines($row["rid"]);
						
						$db->Query("UPDATE refs SET status='1' WHERE id='" . $row["id"] . "'", 2);
					}
					else
						$db->Query("DELETE FROM refs WHERE id='" . $row["id"] . "'", 2);
				}
			}
		}
		
		function GetMaxLevels()
		{
			GLOBAL $db;
			
			$db->Query("SELECT id, ref_levels FROM memberships");
			
			$max	= 0;
			
			while($row = $db->NextRow())
			{
				$levels	= explode("|", $row["ref_levels"]);
				
				if($levels[0] > $max)
				{
					$max	= $levels[0];
					
					$mship	= $row["id"];
				}
			}
			
			$default	= explode("|", _REFERRAL_LEVELS);
			
			if($default[0] > $max)
			{
				$mship	= 0;
			}
			
			return $mship;
		}
		
		function AddCreditsToUplines($UID, $qcredits = 0, $credit_type = "cash")
		{
			GLOBAL $db;
			
			$pre	= $credit_type == "cash" ? "level_" : "plevel_";
			
			for($i = 1; $i - 1 < $this->GetLevelData($this->GetMaxLevels()); $i++)
			{
				$ups	= $this->GetUplinesOfUID($UID, $i, 2);
				
				if(is_array($ups))
				{
					foreach($ups AS $rid)
					{
						if($qcredits != 0)
							$credits		= ($this->GetLevelData($db->Fetch("SELECT premium FROM users WHERE id='$rid'"), $i) / 100) * $qcredits;
						
						$data			= unserialize($db->Fetch("SELECT referral_data FROM users WHERE id='$rid'"));
						
						$credit_var		= $qcredits == 0 && _REFERRAL_TYPE == "CREDITS" ? $this->GetLevelData($premium, $i) : $credits;
						
						$data[$pre.$i]	+= $credit_var;
						
						$db->Query("UPDATE users SET referral_data='" . serialize($data) . "' WHERE id='$rid'");
					}
				}
			}
		}
		
		function GetUplinesOfUID($UID, $ofLevel = 1, $status = 1, $currentLevel = 1)
		{
			GLOBAL $db;
			
			$qSelect	= $status == 2 ? "" : " AND status='$status'";
			
			$uplines	= Array();
			
			$db->Query("SELECT uid FROM refs WHERE rid='$UID'" . $qSelect, $currentLevel);
			
			while($data = $db->NextRow($currentLevel))
			{
				if($currentLevel != $ofLevel)
				{
					$ups = $this->GetUplinesOfUID($data["uid"], $ofLevel, $status, $currentLevel+1);
					
					for($i = 0; $i < count($ups); $i++)
						array_push($uplines, $ups[$i]);
				}
				else
					array_push($uplines, $data["uid"]);
			}
			return array_unique($uplines);
		}
		
		function GetNumReferrals($UID, $ofLevel = 1, $status = 1, $currentLevel = 1)
		{
			GLOBAL $db;
			
			$referrals	= Array();
			
			$db->Query("SELECT rid FROM refs WHERE uid='$UID' AND status='$status'", $currentLevel);
			
			while($data = $db->NextRow($currentLevel))
			{
				if($currentLevel != $ofLevel)
				{
					$refs = $this->GetNumReferrals($data["rid"], $ofLevel, $status, $currentLevel+1);
					
					for($i = 0; $i < count($refs); $i++)
						array_push($referrals, $refs[$i]);
				}
				else
					array_push($referrals, $data["rid"]);
			}
			return array_unique($referrals);
		}
		
	}
	
	$referrals	= new Referrals;

?>