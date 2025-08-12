<?php

/*
//////////////////////////////////////////////////////////////
//															//
//		Vision Source v0.5 Beta								//
//		Created by Ben Maynard copyright 2005				//		
//		Email: volvorules@gmail.com							//
//		URL: http://www.visionsource.org					//
//		Created: 3rd March 2005								//
//															//
//----------------------------------------------------------//
//															//
//		Script: contact.php									//
//		written by: Ben Maynard								//
//															//
//////////////////////////////////////////////////////////////
*/

if ( ! defined( 'DIRECT' ) )
{	die("<h1>Access denied</h1>You are not allowed to access this file directly.");
}

//Start the class
class contact {

	var $output = "";
	var $html	= "";

	//--------------------------
	//	Set up all the pages
	//--------------------------
	
	function pages() {
		global $skin;
		
		$this->html = $skin->load('skin_contact');
		$skin->do_title("Contact Us");
		$do = !empty($_GET['do']) ? $_GET['do'] : FALSE;
		
			switch ($do)
			{
				case "main":
					$this->home();
				break;
				case "send":
					$this->sendmail();
				break;
				default:
					$this->home();
				break;
			}
			
		$skin->do_output("$this->output");
	}
	
	
	//------------------------------
	//	Lets do all the functions
	//------------------------------
	
 	function home()
	{
		$this->output .= $this->html->contact();
	}
	
	function sendmail()
	{
	  global $error, $cms;
	  
	  	$name 		= $_POST['name'];
		$email		= $_POST['email'];
		$message	= $_POST['message'];
		$ip			= $cms->member['ip'];
		
			if (!preg_match('/^([A-Z0-9\.\-_]+)@([A-Z0-9\.\-_]+)?([\.]{1})([A-Z]{2,6})$/i', $email))
			{
				$this->output .= $error->error('Please enter in a valid email address', $back = true);
				return;
			}
			
			if (empty($name))
			{
				$this->output .= $error->error('Please enter in your name.', $back = true);
				return;
			}
			
			if (empty($email))
			{
				$this->output .= $error->error('Please enter in an email address.', $back = true);
				return;
			}
			
			if (empty($message))
			{
				$this->output .= $error->error('Please enter in a message.', $back = true);
				return;
			}
			
			
		$headers = 'From: ' . $email . '' . "\r\n" .
  					'Reply-To: ' . $email . '' . "\r\n" .
   					'X-Mailer: PHP/' . phpversion();
			
		$email = "An email has been sent to you by {$name} via Vision Source Contact System.

Their name:	{$name}		
Their email: {$email}
Their message: 
" . wordwrap($message, 70) . "

Their IP: {$ip}

Regards,
Vision Source.";

		mail($info['email'], 'Email From Vision Source CMS', $email, $headers);
		
		$this->output .= $this->html->complete();

	}
	
}
?>