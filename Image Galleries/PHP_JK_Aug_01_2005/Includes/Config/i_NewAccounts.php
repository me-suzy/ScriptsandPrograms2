<?php 

	//************************************************************************************
	// This include goes in the /PHPJK/Admin/NewAccounts/index.php page
	//************************************************************************************
	
	// This is the email that is sent to the admin whenever a new account is created
	//	(if that option is turned on). This email is sent before an account is
	//	authenticated.
	// 		the 1: is replaced with the users login
	//		the 2: is replaced with the users password
	//		the \n should appear as a line break in the email
	$CONF_NewAccount = "A new user has created an account.\n\n";
	$CONF_NewAccount .= "Login: 1:\nPassword: 2:";
	
	
	// This is the email sent to users when they sign up for a new account
	//	if authentication is turned OFF
	// 		the 1: is replaced with the users login
	//		the 2: is replaced with the users password
	//		the \n should appear as a line break in the email
	$CONF_NewUser = "Thank you for signing up! Please contact us if you have any questions.\n\n";
	$CONF_NewUser .= "Your login is: 1:\nYour password is: 2:";
	
	
	// This is the email sent to users when they sign up for a new account
	//	if authentication is turned ON
	// 		the 1: is replaced with the users login
	//		the 2: is replaced with the current domain name (eg: "www.phpjk.com")
	//		the 3: is replaced with their authentication id number
	//		the \n should appear as a line break in the email
	$CONF_NewUserAuth = "Dear 1:,\n\n";
	$CONF_NewUserAuth .= "Thank you for registering. Before we can activate your account one last step must be taken to complete your registration!\n\n";
	$CONF_NewUserAuth .= "Please note - you must complete this last step to become a registered member. ";
	$CONF_NewUserAuth .= "You will only need to click on the link once, and your account will be updated.\n\n";
	$CONF_NewUserAuth .= "To complete your registration, click on the link below: \n";
	$CONF_NewUserAuth .= "2:/UserArea/Authenticate.php?id=3: \n\n";
	$CONF_NewUserAuth .= "<a href=\"2:/UserArea/Authenticate.php?id=3:\">AOL Users click Here to be Activated</a>\n\n";
	$CONF_NewUserAuth .= "**** Does The Above Link Not Work? ****\n";
	$CONF_NewUserAuth .= "If the above link does not work, please use your Web browser to go to:\n";
	$CONF_NewUserAuth .= "2:/UserArea/Authenticate.php\n\n";
	$CONF_NewUserAuth .= "Please be sure not to add extra spaces. You will need to type in your username and activation ";
	$CONF_NewUserAuth .= "number on the page that appears when you click on, or copy the above link into your browser.\n\n";
	$CONF_NewUserAuth .= "Your Username is: 1:\n";
	$CONF_NewUserAuth .= "Your Activation ID is: 3:\n\n";
	$CONF_NewUserAuth .= "If you are still having problems signing up please contact a member of our support staff.\n\n";
	$CONF_NewUserAuth .= "Thanks very much";
?>