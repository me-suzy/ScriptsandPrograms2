<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\
	// This script is copyrighted to CreateYourGetPaid©       \
	// Duplication, selling, or transferring of this script   \
	// is a violation of the copyright and purchase agreement.\
	// Alteration of this script in any way voids any         \
	// responsibility CreateYourGetPaid© has towards the      \
	// functioning of the script. Altering the script in an   \
	// attempt to unlock other functions of the program that  \
	// have not been purchased is a violation of the          \
	// purchase agreement and forbidden by CreateYourGetPaid© \
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\
	
	error_reporting(0);
	
	set_time_limit(0);
	
	chdir(getcwd());
	
	$GLOBALS["adminpage"] = "yes";
	
	include ".htconfig.php";
	
	if(_SITE_CRONTEST == "YES")
	{
		$main->sendMail(_SITE_EMAIL, "Cron-Test", "Started cronjob on: " . date("l F d Y h:i"));
	}
	
	if(_CRONJOBS == "YES")
	{
		$main->CronJobs();
	}
	
	if($_GET["output"] == "on")
	{
		$tml->Output();
	}

?>