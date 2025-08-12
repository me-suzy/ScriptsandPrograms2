<?
class Debug 
{
	function Debug ($message, $className, $functionName, $messageType, $debugType = "USER")
	{
		global $DEBUG_ON,
			   $DEBUG_LOG_PATH,
			   $DEBUG_STYLE;
		
		if (!$DEBUG_ON)
			return;

		if (!isset ($DEBUG_LOG_PATH))
			$DEBUG_LOG_PATH = "./logs.txt";
			
		$currDate = getdate ();
		$currTime = "  ".$currDate ["hours"].":".$currDate ["minutes"].":".$currDate ["seconds"]." ";
		
		$message = $currTime." $messageType - [$className:$functionName ()] ".$message;

		if (isset ($DEBUG_STYLE) && $DEBUG_STYLE == "CMD")
			print $message."\n";
		else if (isset ($DEBUG_STYLE) && $DEBUG_STYLE == "WEB")
			print $message."<br>";

		error_log ($message."\n", 3, $DEBUG_LOG_PATH);
	
		if ($messageType == 'ERROR' && $debugType == 'SYS')
			exit ("The work of the system has been stopped. See ERROR_LOG_PATH for more information.");
	}
}
?>