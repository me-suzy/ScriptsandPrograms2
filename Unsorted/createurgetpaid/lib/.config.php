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

	class Config
	{
		function ChangeKeyCase($array)
		{
			if(is_array($array))
			{
				foreach($array as $key => $value)
				{
					$new_array[strtoupper($key)] = $value;
				}
				
				return $new_array;
			}
			else
				return $array;
		}
		
		function Load()
		{
			GLOBAL $db, $error;
			
			$db->Query("SELECT * FROM config");
			
			if($db->NumRows() != 1)
				exit($error->Fatal(__FILE__, "There is no configuration entry in the database."));
			
			$config	= $db->Fetch("SELECT * FROM config");
			$config	= $this->ChangeKeyCase($config);
			
			foreach($config AS $name => $value)
			{
				if(!is_numeric($name))
				{
					define("_$name", $value);
				}
			}
		}
		
		function Save($settings)
		{
			GLOBAL $db;
			
			if($settings["options_earned"] != "on")
				$settings["referral_earned"]	= 0;
			
			if($settings["options_loggedin"] != "on")
				$settings["referral_loggedin"]	= 0;
			
			if($settings["ap_passphrase"])
				$settings["ap_passphrase"]	= base64_encode($settings["ap_passphrase"]);
			
			foreach($settings AS $name => $value)
			{
				if($name != "PHPSESSID" && $name != "sid")
				{
					if($qSet)
						$qSet .= ", ";
					
					$qSet	.= "$name = '$value'";
				}
			}
			
			$db->Query("UPDATE config SET $qSet");
			
			return true;
		}
	
	}
	
	$cfg	= new Config;

?>