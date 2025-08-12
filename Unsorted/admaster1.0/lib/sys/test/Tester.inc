<?
class Tester 
{
	function Tester ($message, $className, $functionName)
	{
		global $TEST_LOG_PATH,
			   $TEST_STYLE;
		
		if (!isset ($TEST_LOG_PATH))
			$TEST_LOG_PATH = "test_log.txt";

		$currDate = getdate ();
		$currTime = "  ".$currDate ["hours"].":".$currDate ["minutes"].":".$currDate ["seconds"]." ";
		
		if (isset ($TEST_STYLE) && $TEST_STYLE == "CMD")
			print $currTime." - [$className:$functionName ()] ".$message."\n";
		else if (isset ($TEST_STYLE) && $TEST_STYLE == "WEB")
			print $currTime." - [$className:$functionName ()] ".$message."<br>";

		error_log ($currTime." - [$className:$functionName ()] ".$message."\n", 3, $TEST_LOG_PATH);
	}
}
?>