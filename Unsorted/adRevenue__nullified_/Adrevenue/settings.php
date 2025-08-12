<?
#- System paths
$P[home]      = "/home/www";         					//Path to web home directory
$P[html]      = $P[home]."/public_html";				//Path to web root	
$P[templates] = $P[html]."/templates";					//Path to templates directory
$P[includes]  = $P[html];						//Home to includes
$P[url]	      = "http://www.mydomain.com/";			        //Where this site is located

#- Database Settings
$DBLIB[engine] = "mysql";						// Choose "mysql" or "pgsql"
$DBLIB[host]   = "localhost";						// Hostname of database server
$DBLIB[port]   = "";							// Enter the port for postgresql or mysql (blank for mysql)
$DBLIB[database] = "adrevenue";						// Database name
$DBLIB[user]   = "root";						// DB Login name
$DBLIB[password]= "";							// DB Password
$DBLIB[persistent] = 1;							// Use a persistent DB connection

#- Other Settings
$S[dateformat] = "M j, Y g:ia";
$S[adlimit] = 8;							// Limit of ads to show at once
$S[border] = "#CCCCCC";							// Default AD Border Color
$S[bgcolor] = "#FFFFCC";						// Default AD Background Color
$S[cpc] = .05;								// Minimum CPC
$S[adtype] = "page";							// Either "page" or "keyword" based
$S[dupclicks] = 360;							// Ignore duplicate clicks within # seconds
$S[freemoney] = 10.00;							// Free money to give when someone signs up.
$S[org] = "W3matter.com";						// Your Website's name

#- Paypal Settings
$S[paypal_email] = "you@playpalemail.com";					// Your Paypal e-mail account
$S[paypal_transaction_name] = "Advertising Account Deposit";		// A Description of the transaction
$S[paypal_default] = 25.00;						// Default Deposit amount
$S[paypal_success] = "http://www.mydomain.com/ad.php"; 	// Page to go to when payment is successfull
$S[paypal_cancel] = "http://www.mydomain.com/ad.php";  	// Page to go to when payment is cancelled
$S[paypal_ipn] = "http://www.mydomain.com/xyzinstant.php";	// Paypal Instant Notification URL

#- Include some files
include_once($P[includes]."/xtpl.php");					// a template library
include_once($P[includes]."/lib.php");					// a function library
include_once($P[includes]."/display.php");				// ad display library
include_once($P[includes]."/reports.php");				// reports library

session_name("adrevenue");
session_start();
if(!$sess || !$user)
{
	session_register("sess","user");
}

?>
