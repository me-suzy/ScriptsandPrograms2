<?PHP
#############################################
#	Project:	HumanCheck 2.1
#	file:		humancheck_config.php
#	company:	SmiledSoft.com (SmiledSoft.com)
#	author:		Yuriy Horobey (yuriy@horobey.com)
#	purpose: 	cheks if code from visitor matches 
#				code on the picture and shows a message
#	date: 24.08.2004
#
#############################################

	error_reporting(85);//serious error only

	require(dirname(__FILE__)."/humancheck_config.php");
	//lets get session id $sid
	//it comes from hidden field in the form
	//usally forms have method "post"
	$sid=trim($HTTP_POST_VARS["sid"]);
	//but maybe this one has get?
	if(!$sid)	$sid=trim($HTTP_GET_VARS["sid"]);

	session_id($sid);
	session_start();

	$noautomationcode = $HTTP_SESSION_VARS["noautomationcode"];

	$code = trim($HTTP_POST_VARS["code"]);
	if($code != $noautomationcode){
		//codes do not match.
		// Inform your visitor that he has entered wrong code
		//please make sure that execution will stop here, use die() or exit functions

		die("Dear visitor you have entered wrong code. <br> Blah blah try again.<br>Or you are the evil script trying to access my server<br><h1>Access Denied</h1>");
	}else{
	
		//Codes match! that means that your visitor is human and so has access 
		//to the following part of the page
	
	
	}
?>


<html>
<body>
<p><strong>Code is OK you are allowd to see my page. </strong><br>
  From here you can redirect visitor, or allow him to download something or insert 
  registration data to the database. etc.</p>
</body>
</html>