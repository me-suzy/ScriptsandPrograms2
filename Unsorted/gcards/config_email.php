<?
require("inc/phpmailer/class.phpmailer.php");

class emailer extends phpmailer {
    // Set default variables for all new emails
    var $Mailer   = "mail";					// Choose "mail" "smtp" or "sendmail" - "mail" is default    
	var $PluginDir = "inc/phpmailer/";		// Path to phpmailer directory including trailing slash
	// SMTP Options - fill in if using smtp
	var $Host = "smtp.domain.com";		// example: "smtp1.example.com;smtp2.example.com" for multiple hosts
	var $Port = 25;							// SMTP port
	
	var $SMTPAuth = true;					// whether or not to use SMTP authorization
	var $Username = "user";	// SMTP username when SMTPAuth = true
	var $Password = "pass";				// SMTP password when SMTPAuth = true
}



?>
