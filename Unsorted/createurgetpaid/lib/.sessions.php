<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any	      \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the	      \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	Class Session
	{
		var $ID		= NULL;
		
		var $Data	= Array();
		
		function Session($id)
		{
			GLOBAL $db;
			
			$count	= $db->Fetch("SELECT COUNT(id) FROM sessions WHERE id='$id'");
			
			if($count == 1)
			{
				$this->ID	= $this->Load($id);
			}
			else
			{
				$this->ID	= $this->Create();
			}
			
			$this->Load($this->ID);
		}
		
		function Load($id)
		{
			GLOBAL $db;
			
			$this->Data	= unserialize($db->Fetch("SELECT data FROM sessions WHERE id='$id'"));
			
			return $id;
		}
		
		function GenerateID()
		{
			$charset	= "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
			$hash		= "";
			
			mt_srand((double) microtime() * 1000000);
			
			for($i = 0; $i < _SITE_SESSIONLENGTH; $i++)
			{
				$hash	.= $charset[mt_rand(0, strlen($charset) - 1)];
			}
			
			return md5($hash);
		}
		
		function Create()
		{
			GLOBAL $db;
			
			$id	= $this->GenerateID();
			
			$db->Query("INSERT INTO sessions (id, lastUpdate, data) VALUES ('$id', '" . time() . "', '');");
			
			return $id;
		}
		
		function Get($name)
		{
			return $this->Data[$name];
		}
		
		function Set($name, $value)
		{
			$this->Data[$name]	= $value;
		}
		
		function Save()
		{
			GLOBAL $db;
			
			$db->Query("UPDATE sessions SET data='" . serialize($this->Data) . "' WHERE id='" . $this->ID . "'");
		}
		
		function GC($maxLifeTime = 1440)
		{
			GLOBAL $db;
			
			$db->Query("DELETE FROM sessions WHERE lastUpdate<" . (time() - $maxLifeTime));
			
			return true;
		}
	}
	
	$session	= new Session($_GET["sid"]);

?>