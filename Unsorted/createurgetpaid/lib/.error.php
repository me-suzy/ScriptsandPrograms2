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
	
	class Error
	{
		
		function Fatal($indentifyer, $message, $sql = 0, $query = "", $error = "")
		{
			if($sql == 1 && _ERROR_EMAIL != "")
			{
				$GLOBALS["tml"]->RegisterVar("QUERY",	$query);
				$GLOBALS["tml"]->RegisterVar("ERROR",	$error);
				$GLOBALS["tml"]->RegisterVar("DATE",	date("l F d Y"));
				
				$GLOBALS["tml"]->loadFromFile("emails/error");
				$GLOBALS["tml"]->Parse(1);
				
				$GLOBALS["main"]->sendMail(_ERROR_EMAIL, "SQL database error", $GLOBALS["tml"]->GetParsedContent());
			}
			
			if($sql == 1)
				$GLOBALS["main"]->WriteToLog("mysql_errors", "SQL error in query \"$query\" - \"$error\"");
			
			exit(_ERROR_HANDLING == "HIDE" ? "A fatal error has occured, please try again later." : "<U><B>Fatal error from $indentifyer:</U><BR><BR>$message</B>");
		}
		
		function Warning($indentifyer, $message)
		{
			echo "<div style=\"position:absolute; top:10; left:10; width:350; z-index:1; padding:5px; border: #000000 2px solid; background-color:#FFFFFF;\"><b>Warning from $indentifyer: $message</b></div>\n";
		}
		
		function Report($indentifyer, $message, $layout = 0)
		{
			GLOBAL $tml;
			
			$tml->RegisterVar("INDENTIFYER", $indentifyer);
			$tml->RegisterVar("MESSAGE", $message);
			
			if($layout == 0)
			{
				$tml->loadFromFile("pages/header");
				$tml->Parse();
			}
			
			$tml->loadFromFile("pages/error");
			$tml->Parse();
			
			if($layout == 0)
			{
				$tml->loadFromFile("pages/footer");
				$tml->Parse();
			}
			
			$tml->Output();
		}
	}
	
	$error	= new Error;

?>