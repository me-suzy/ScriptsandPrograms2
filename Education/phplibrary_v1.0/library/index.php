<?php
//////////////////////////////////////////////////////////////////////
//             PHP Item Library - Sebastian Flippence               //
//                   http://www.sebflipper.com                      //
//                        Main Script File                          //
//                                                                  //
//                     Last Updated: 29/03/03                       //
//////////////////////////////////////////////////////////////////////


// Beginning PHP
//////////////////////////////////////
// Program Version 
$library_ver = "v1.0";
$library_ver_date = "09/07/2003";

$setup = false;
// Loading configuration file for the rest of the modules use
// dirname(__FILE__) is a command use to get the location of the script (eg C:\Program Files\EasyPHP\www or /user/bin/hostname/etc)
if(is_file(dirname(__FILE__)."/library.config.inc.php"))
	{ 
	// If is does then load it into the script
	require dirname(__FILE__)."/library.config.inc.php";
	}

if ($db==false) {
		$setup = true;
		$library_name = "Library Setup";
		$user_id = 1;
}

// Checking lastest version
$library_current_ver="";
flush();
$fp=@fopen("http://www.ffhut.co.uk/~sebflipper/phplibrary_version.php?url=http://$SERVER_NAME$PHP_SELF", "r");
if($fp){
	$library_current_ver = fgets($fp, 1024);
	fclose($fp);
}
if($library_current_ver==false) {
	$library_current_ver = "Error Connecting";
}

// Logout
if ($module=="logout")
	{
	header ("Location: $PHP_SELF?cookie_set=true");
	}

// Cookie area (used to set a login cookie and delete it when they logout)
if ($cookie_set==true)
	{
	if ($login==true)
		{
		// Making sure the form is not blank
		if ($username==true && $password==true)
			{
			// Setting login cookie 
			// Encripting the password with MD5
			$password = md5($password);

			setcookie ("PHPLibrary[username]", $username);
			setcookie ("PHPLibrary[password]", $password);
			header ("Location: $PHP_SELF?module=home");
			exit;
			}
			else
			{
			// If they haven't filled out the login form show error
		    echo "<html><body><script language=javascript1.1>alert('Please fill out the required fields'); javascript:history.back();</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
			exit;
			}
		}
		else
		{
		// Else loging out (deletes the cookie)
		setcookie ("PHPLibrary[username]");
		setcookie ("PHPLibrary[password]");
		setcookie ("PHPLibrary[items]");
		header ("Location: $PHP_SELF");
		exit;
		}
	}

// Checking and Adding Module
// Setting the page to login, if they have not set a module to go to
if ($module==false) { 
	if ($PHPLibrary[username]==true && $PHPLibrary[password]==true) {
		$module = "home";
	}
	else
	{
		if ($header_footer!="no") {
			// Adding the header file
			include "header.inc.php";
		}
		if ($setup==true) {
			require dirname(__FILE__)."/modules/setup.php";
		}
		else
		{
			require dirname(__FILE__)."/modules/login.php";
		}
	}
}
// Making sure they are not trying to view a module without a cookie
else
{
	if ($PHPLibrary[username]==false || $PHPLibrary[password]==false)
		{
		if ($header_footer!="no") {
			// Adding the header file
			include "header.inc.php";
		}
		require dirname(__FILE__)."/modules/login.php";
		}
}


// Checking for a cookie
if ($PHPLibrary[username]==true && $PHPLibrary[password]==true)
	{
	
	$cookie_username = $PHPLibrary[username];
	$cookie_password = $PHPLibrary[password];
	// Checking the cookie's user name and password againsts the real one in the database
	$authenticate_mysql = mysql_query("SELECT `id`,`username`,`password`,`name` FROM `$mysql_pre$mysql_admin` WHERE `username` = '$cookie_username' AND `password` = '$cookie_password' LIMIT 0, 1",$db);
	//
	$authenticate_results = mysql_fetch_array($authenticate_mysql);
	
	$real_username = $authenticate_results["username"];
	$real_password = $authenticate_results["password"];
	// Just here I have used echo commands to output the some of the variables 
	
	if ($PHPLibrary[username]=="$real_username" && $PHPLibrary[password]=="$real_password")
		{
		// Cookie Login is correct
		// Displaying admin centre
		
		// Getting the user id for use the accounts module
		$user_id = $authenticate_results["id"];
		$user_full_name = $authenticate_results["name"]; 
			
		if ($header_footer!="no") {
			// Adding the header file
			include "header.inc.php";
		}

		// Adding Module
		// Checking that the file exists
		if(is_file("modules/".$module.".php"))
			{ 
			// If is does then load it into the script
			require dirname(__FILE__)."/modules/".$module.".php";
			}
			else
			{ 
			// If is doesn't then display an error message
			// The \ next to the " stops the echo command from ending
			echo("<a href=\"javascript:history.back()\">Back</a><br><br>\n<b>ERROR: Cannot find Module!</b>\n");
			}
				
		}
		else
		{
		// Cookie Login is incorrect
		echo "<html><body><script language=javascript1.1>alert('Invalid Username or Password'); window.location='$PHP_SELF?cookie_set=true';</script><noscript>Your browser doesn't support JavaScript 1.1 or it's turned off in your browsers preferences.</noscript></body></html>";
		exit;
		}
	}
	
if ($header_footer!="no") {
	// Adding the footer file
	include "footer.inc.php";
}

// End of PHP!
?>