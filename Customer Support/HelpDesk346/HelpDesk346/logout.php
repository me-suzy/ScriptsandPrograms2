<?
	//Revised on May 29, 2005
	//Revised by Jason Farrell
	//Revision Number 3
	
	session_start();
	session_destroy();
	header("location: index.php");
exit;
?>