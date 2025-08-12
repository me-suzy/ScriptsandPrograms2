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

	class Banners
	{
	
		function RegisterBannerVars()
		{
			GLOBAL $db, $tml;
			
			$ID		= $db->GetRandomRecord("SELECT id FROM ads WHERE active='yes'");
			
			if(is_numeric($ID) && $ID >= 1)
			{
				$db->Query("UPDATE ads SET views=views+1 WHERE id='$ID'");
				
				$data	= $db->Fetch("SELECT path, alt, views, type, quantity, jscode, b_type FROM ads WHERE id='$ID'");
				
				if($data["type"] == "views")
				{
					if($data["views"] == $data["quantity"])
						$db->Query("UPDATE ads SET active='no' WHERE id='$ID'");
				}
			}
			
			if($data["b_type"] == "img")
				return "<a href='" . _SITE_URL . "/banners.php?id=$ID' target='_blank'><img src='" . $data["path"] . "' alt='" . $data["alt"] . "' border='0'></a>";
			else
				return $data["jscode"];
		}
		
		function GetURL($ID)
		{
			GLOBAL $db;
			
			$db->Query("UPDATE ads SET clicks=clicks+1 WHERE id='$ID'");
			
			$data	= $db->Fetch("SELECT url, clicks, type, quantity FROM ads WHERE id='$ID'");
			
			if($data["type"] == "clicks")
			{
				if($data["clicks"] == $data["quantity"])
					$db->Query("UPDATE ads SET active='no' WHERE id='$ID'");
			}
			
			return $data["url"];
		}
	
	}
	
	$banners	= new Banners;

?>