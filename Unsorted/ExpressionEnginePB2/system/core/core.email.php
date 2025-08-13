<?php
/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: core.email.php
-----------------------------------------------------
 Purpose: Send email
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class EEmail {

	//	Public variables.

	var	$protocol		= "mail";				// mail/sendmail/smtp
	var	$mailpath		= "/usr/sbin/sendmail";	// Sendmail path
	var	$smtp_host		= "";					// SMTP Server.  Example: mail.earthlink.net
	var	$smtp_user		= "";					// SMTP Username
	var	$smtp_pass		= "";					// SMTP Password
	var	$smtp_auth		= false;				// true/false.  Does SMTP require authentication?
	var	$smtp_port		= "25";					// SMTP Port
	var	$smtp_timeout	= 5;					// SMTP Timeout in seconds
	var	$debug			= false;				// true/false.  True displays messages, false does not
	var	$wordwrap		= false;				// true/false  Turns word-wrap on/off
	var	$wrapchars		= "76";					// Number of characters to wrap at.
	var	$mailtype		= "text";				// text/html  Defines email formatting
	var	$charset		= "UTF-8";				// Default char set: iso-8859-1 or us-ascii
	var	$encoding		= "8bit";				// Default bit depth (8bit = non-US char sets)
	var	$multipart		= "mixed";				// "mixed" (in the body) or "related" (separate)
	var	$validate		= false;				// true/false.  Enables email validation
	var	$priority		= "3";					// Default priority (1 - 5)
	var	$newline		= "\n";					// Default newline. "\r\n" or "\n" (Use "\r\n" to comply with RFC 822)
	var	$bcc_batch_mode	= false;				// true/false  Turns on/off Bcc batch feature
	var	$bcc_batch_tot	= 250;					// If bcc_batch_mode = true, sets max number of Bccs in each batch
	var $safe_mode		= FALSE;				// TRUE/FALSE - when servers are in safe mode they can't use the 5th parameter of mail()
	
	//-------------------------------------------------------------------------------------------	
	//	Private variables.  Do not modify

	var	$subject		= "";
	var	$body			= "";
	var	$finalbody		= "";
	var	$alt_boundary	= "";
	var	$atc_boundary	= "";
	var	$header_str		= "";
	var	$smtp_connect	= "";
	var	$useragent		= "";
	var	$recipients		= array();
	var	$cc_array		= array();
	var	$bcc_array		= array();
	var	$headers		= array();
	var	$attach_name	= array();
	var	$attach_type	= array();
	var	$attach_disp	= array();
	var	$protocols		= array('mail', 'sendmail', 'smtp');
	var	$base_charsets	= array('iso-8859-1', 'us-ascii');
	var	$bit_depths		= array('7bit', '8bit');
	var	$priorities		= array('1 (Highest)', '2 (High)', '3 (Normal)', '4 (Low)', '5 (Lowest)');
	
	// END VARIABLES ----------------------------------------------------------------------------
	


	//-------------------------------------
	//	Constructor
	//-------------------------------------
	
	function EEmail()
	{
		$this->useragent = APP_NAME.' '.APP_VER;
	
		$this->initialize();
		
		$this->set_config_values();		
	}	
	// END


	//-------------------------------------
	//	Set config values
	//-------------------------------------

	function set_config_values()
	{
		global $PREFS;
		
		$this->protocol = ( ! in_array( $PREFS->ini('mail_protocol'), $this->protocols)) ? 'mail' : $PREFS->ini('mail_protocol');
	
		$this->smtp_host = $PREFS->ini('smtp_server');
		$this->smtp_user = $PREFS->ini('smtp_username');
		$this->smtp_pass = $PREFS->ini('smtp_password');
		
		$this->safe_mode = ($PREFS->ini('safe_mode') == 'y') ? TRUE : FALSE;
		
        $this->smtp_auth = ( ! $this->smtp_user AND ! $this->smtp_pass) ? FALSE : TRUE;		
	}
	// END


	//-------------------------------------
	//	Initialize Variables
	//-------------------------------------

	function initialize()
	{
		$this->subject		= "";
		$this->body			= "";
		$this->finalbody	= "";
		$this->header_str	= "";
		$this->recipients	= array();
		$this->headers		= array();
		
		$this->add_header('User-Agent', $this->useragent);				
		$this->add_header('Date', $this->set_date());
	}
	// END


	//-------------------------------------
	//	From
	//-------------------------------------
	 
	function from($from, $name = '')
	{
		if (ereg( '\<(.*)\>', $from, $match))
			$from = $match['1'];

		if ($this->validate)
			$this->validate_email($this->str_to_array($from));			

		$this->add_header('From', $name.' <'.$from.'>');
		$this->add_header('Reply-To', $from);
		$this->add_header('Return-Path', '<'.$from.'>');
	}
	// END


	//-------------------------------------
	//	Recipients
	//-------------------------------------
		
	function to($to)
	{
		$to = $this->str_to_array($to);
		
		$to = $this->clean_email($to);
	
		if ($this->validate)
			$this->validate_email($to);
			
		if ($this->get_protocol() != 'mail')
			$this->add_header('To', implode(", ", $to));

		switch ($this->get_protocol())
		{
			case 'smtp'		: $this->recipients = $to;
			break;
			case 'sendmail'	: $this->recipients = implode(", ", $to);
			break;
			case 'mail'		: $this->recipients = implode(", ", $to);
			break;
		}	
	}
	// END


	//-------------------------------------
	//	Cc
	//-------------------------------------
	
	function cc($cc)
	{	
		$cc = $this->str_to_array($cc);
		
		$cc = $this->clean_email($cc);

		if ($this->validate)
			$this->validate_email($cc);

		$this->add_header('Cc', implode(", ", $cc));
		
		if ($this->get_protocol() == "smtp")
			$this->cc_array = $cc;
	}
	// END
	
	
	//-------------------------------------
	//	Bcc
	//-------------------------------------

	function bcc($bcc, $limit = '')
	{
		if ($limit != '' && is_numeric($limit))
		{
			$this->bcc_batch_mode = true;

			$this->bcc_batch_tot = $limit;
		}

		$bcc = $this->str_to_array($bcc);

		$bcc = $this->clean_email($bcc);
		
		if ($this->validate)
			$this->validate_email($bcc);

		if (($this->get_protocol() == "smtp") || ($this->bcc_batch_mode && count($bcc) > $this->bcc_batch_tot))
			$this->bcc_array = $bcc;
		else
			$this->add_header('Bcc', implode(", ", $bcc));
	}
	// END


	//-------------------------------------
	//	Message subject
	//-------------------------------------
	 
	function subject($subject)
	{
		$subject = preg_replace("/(\r\n)|(\r)|(\n)/", "", $subject);
		$subject = preg_replace("/(\t)/", " ", $subject);
		
		$this->add_header('Subject', trim($subject));		
	}
	// END


	//-------------------------------------
	//	Message body
	//-------------------------------------
	 
	function message($body)
	{
		$body = rtrim(str_replace("\r", "", $body));
	
		if ($this->wordwrap  AND  $this->mailtype != 'html')
			$this->body = $this->word_wrap($body);
		else
			$this->body = $body;
	}
	// END
	

	//-------------------------------------
	//	Add header item
	//-------------------------------------

	function add_header($header, $value)
	{
		$this->headers[$header] = $value;
	}
	// END


	//-------------------------------------------
	//	Convert sring into an array
	//-------------------------------------------

	function str_to_array($email)
	{
		if ( ! is_array($email))
		{	
			if (ereg(',$', $email))
				$email = substr($email, 0, -1);
			
			if (ereg('^,', $email))
				$email = substr($email, 1);	
					
			if (ereg(',', $email))
			{					
				$x = explode(',', $email);
				
				$email = array();
				
				for ($i = 0; $i < count($x); $i ++)
					$email[] = trim($x[$i]);
			}
			else
			{				
				$email = trim($email);
				
				settype($email, "array");
			}
		}
		return $email;
	}
	// END


	//-------------------------------------
	//	Set boundaries
	//-------------------------------------

	function set_boundaries()
	{
		$this->alt_boundary = "B_ALT_".uniqid(''); // mulipart/alternative
		$this->atc_boundary = "B_ATC_".uniqid(''); // attachment boundary
	}
	// END


	//-------------------------------------
	//	Set Message ID
	//-------------------------------------

	function set_message_id()
	{
		$from = $this->headers['From'];
		$from = str_replace(">", "", $from);
		$from = str_replace("<", "", $from);
	
        return  "<".uniqid('').strstr($from, '@').">";	        
	}
	// END


	//-------------------------------------
	//	Get Debug value
	//-------------------------------------

	function get_debug()
	{
		return $this->debug;
	}
	// END		

		
	//-------------------------------------
	//	Get protocol (mail/sendmail/smtp)
	//-------------------------------------

	function get_protocol($return = true)
	{
		$this->protocol = strtolower($this->protocol);
	
		$this->protocol = ( ! in_array($this->protocol, $this->protocols)) ? 'mail' : $this->protocol;
		
		if ($return == true) return $this->protocol;
	}
	// END
	

	//-------------------------------------
	//	Get mail encoding (7bit/8bit)
	//-------------------------------------

	function get_encoding($return = true)
	{		
		$this->encoding = ( ! in_array($this->encoding, $this->bit_depths)) ? '7bit' : $this->encoding;
		
		if ( ! in_array($this->charset, $this->base_charsets)) 
			$this->encoding = "8bit";
			
		if ($return == true) return $this->encoding;
	}
	// END
	
	
	//-----------------------------------------
	//	Get content type (text/html/attachment)
	//-----------------------------------------

	function get_content_type()
	{	
			if	($this->mailtype == 'html' &&  count($this->attach_name) == 0)
				return 'html';
	
		elseif	($this->mailtype == 'html' &&  count($this->attach_name)  > 0)
				return 'html-attach';				
				
		elseif	($this->mailtype == 'text' &&  count($this->attach_name)  > 0)
				return 'plain-attach';
				
		  else	return 'plain';
	}
	// END


	//-------------------------------------
	//	Set RFC 822 Date
	//-------------------------------------
	 
	function set_date()
	{
		$timezone = date("Z");
		
		$operator = (substr($timezone, 0, 1) == '-') ? '-' : '+';
		
		$timezone = abs($timezone);
			
		$timezone = ($timezone/3600) * 100 + ($timezone % 3600) /60;
		
		return sprintf("%s %s%04d", date("D, j M Y H:i:s"), $operator, $timezone);
	}
	// END


	//-------------------------------------
	//	Mime message
	//-------------------------------------

	function mime_message()
	{
		return "This is a multi-part message in MIME format.".$this->newline."Your email application may not support this format.";
	}
	// END


	//-------------------------------------
	//	Validate Email Address
	//-------------------------------------
	 
	function validate_email($email)
	{
		if ( ! is_array($email))
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message("The email validation method must be passed an array.");
		}

		for ($i=0; $i < count($email); $i++) 
		{
			if (!$this->valid_email($email[$i])) 
			{
				return ( ! $this->get_debug()) ? false :
						$this->error_message("Invalid Address: ". $email[$i]);
			}
		}	
	}
	// END
	
	
	//-------------------------------------
	// Email Validation
	//-------------------------------------

	function valid_email($address)
	{
		if ( ! ereg("^([-a-zA-Z0-9_\\.\\+])+@([-a-zA-Z0-9_\\.\\+]+\.)+[a-z]{2,6}$", $address))
			return false;
		else 
			return true;
	}
	// END


	//---------------------------------------------------------
	//	Clean Extended Email Address: Joe Smith <joe@smith.com>
	//---------------------------------------------------------
	 
	function clean_email($email)
	{
		if ( ! is_array($email))
		{
			if (ereg('\<(.*)\>', $email, $match))
           		return $match['1'];
           	else
           		return $email;
		}
			
		$clean_email = array();

		for ($i=0; $i < count($email); $i++) 
		{
			if (ereg('\<(.*)\>', $email[$i], $match))
           		$clean_email[] = $match['1'];
           	else
           		$clean_email[] = $email[$i];
		}
		
		return $clean_email;
	}
	// END
	
	
	//------------------------------------------------
	//	Strip HTML from message body
	//------------------------------------------------
	//	This function provides the raw message for use
	//	in plain-text headers of HTML-formatted emails

	function strip_html()
	{
		if (eregi( '\<body(.*)\</body\>', $this->body, $match))
		{
			$body = $match['1'];
		
			$body = substr($body, strpos($body, ">") + 1);
		}
		else
			$body = $this->body;
		
		$body = trim(strip_tags($body));

		$body = preg_replace( '#<!--(.*)--\>#', "", $body);
		
		$body = str_replace("\t", "", $body);
		
		for ($i = 20; $i >= 3; $i--)
		{
			$n = "";
			
			for ($x = 1; $x <= $i; $x ++)
				 $n .= "\n";
		
			$body = str_replace($n, "\n\n", $body);	
		}

		return $this->word_wrap($body, '76');
	}
	// END
	

	//-------------------------------------
	//	Word wrap
	//-------------------------------------
	 
	function word_wrap($str, $chars = '')
	{	
		if ($chars == '')
			$chars = ($this->wrapchars == "") ? "76" : $this->wrapchars;
		
		$lines = split("\n", $str);
		
		$output = "";

		while (list(, $thisline) = each($lines)) 
		{
			if (strlen($thisline) > $chars)
			{
				$line = "";
				
				$words = split(" ", $thisline);
				
				while(list(, $thisword) = each($words)) 
				{
					while((strlen($thisword)) > $chars) 
					{
						$cur_pos = 0;
						
						for($i=0; $i < $chars - 1; $i++)
						{
							$output .= $thisword[$i];
							$cur_pos++;
						}
						
						$output .= "\n";
						
						$thisword = substr($thisword, $cur_pos, (strlen($thisword) - $cur_pos));
					}
					
					if ((strlen($line) + strlen($thisword)) > $chars) 
					{
						$output .= $line."\n";
						
						$line = $thisword." ";
					} 
					else 
					{
						$line .= $thisword." ";
					}
				}
	
				$output .= $line."\n";
			} 
			else 
			{
				$output .= $thisline."\n";
			}
		}

		return $output;	
	}
	// END


	//-------------------------------------
	//	Assign file attachments
	//-------------------------------------
	
	function attach($filename, $disposition = 'attachment')
	{			
		$this->attach_name[] = $filename;
		
		$this->attach_type[] = $this->mime_types(next(explode('.', basename($filename))));
		
		$this->attach_disp[] = $disposition; // Can also be 'inline'  Not sure if it matters 
	}
	// END


	//-------------------------------------
	//	Build final headers
	//-------------------------------------

	function build_headers()
	{
		$this->add_header('X-Sender', $this->clean_email($this->headers['From']));
		$this->add_header('X-Mailer', $this->useragent);		
		$this->add_header('X-Priority', $this->priorities[$this->priority - 1]);
		$this->add_header('Message-ID', $this->set_message_id());		
		$this->add_header('Mime-Version', '1.0');
	}
	// END


	//-------------------------------------
	//	Write Headers as a string
	//-------------------------------------
	
	function write_header_string()
	{
		if ($this->protocol == 'mail')
		{		
			$this->subject = $this->headers['Subject'];
			
			unset($this->headers['Subject']);
		}	

		reset($this->headers);
		
		$this->header_str = "";
				
		foreach($this->headers as $key => $val) 
		{
			$this->header_str .= $key.": ".$val.$this->newline;
		}
		
		if ($this->get_protocol() == 'mail')
			$this->header_str = substr($this->header_str, 0, -1);	
	}
	// END


	//-------------------------------------
	//	Build Final Body and attachments
	//-------------------------------------

	function build_finalbody()
	{
		$this->set_boundaries();
		
		$this->write_header_string();
		
		$hdr = ($this->get_protocol() == 'mail') ? $this->newline : '';
			
		switch ($this->get_content_type())
		{
			case 'plain' :
							
				$hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: " . $this->get_encoding();
				
				if ($this->get_protocol() == 'mail')
				{
					$this->header_str .= $hdr;
					$this->finalbody = $this->body;
					
					return;
				}
				
				$hdr .= $this->newline . $this->newline . $this->body;
				
				$this->finalbody = $hdr;
						
				return;
			
			break;
			case 'html' :
							
				$hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->alt_boundary . "\"" . $this->newline;
				$hdr .= $this->mime_message() . $this->newline . $this->newline;
				$hdr .= "--" . $this->alt_boundary . $this->newline;
				
				$hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: " . $this->get_encoding() . $this->newline . $this->newline;
				$hdr .= $this->strip_html() . $this->newline . $this->newline . "--" . $this->alt_boundary . $this->newline;
			
				$hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: quoted/printable";
				
				if ($this->get_protocol() == 'mail')
				{
					$this->header_str .= $hdr;
					$this->finalbody = $this->body . $this->newline . $this->newline . "--" . $this->alt_boundary . "--";
					
					return;
				}
				
				$hdr .= $this->newline . $this->newline;
				$hdr .= $this->body . $this->newline . $this->newline . "--" . $this->alt_boundary . "--";

				$this->finalbody = $hdr;
				
				return;
		
			break;
			case 'plain-attach' :
	
				$hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->atc_boundary."\"" . $this->newline;
				$hdr .= $this->mime_message() . $this->newline . $this->newline;
				$hdr .= "--" . $this->atc_boundary . $this->newline;
	
				$hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: " . $this->get_encoding();
				
				if ($this->get_protocol() == 'mail')
				{
					$this->header_str .= $hdr;		
					
					$body  = $this->body . $this->newline . $this->newline;
				}
				
				$hdr .= $this->newline . $this->newline;
				$hdr .= $this->body . $this->newline . $this->newline;

			break;
			case 'html-attach' :
			
				$hdr .= "Content-Type: multipart/".$this->multipart."; boundary=\"" . $this->atc_boundary."\"" . $this->newline;
				$hdr .= $this->mime_message() . $this->newline . $this->newline;
				$hdr .= "--" . $this->atc_boundary . $this->newline;
	
				$hdr .= "Content-Type: multipart/alternative; boundary=\"" . $this->alt_boundary . "\"" . $this->newline .$this->newline;
				$hdr .= "--" . $this->alt_boundary . $this->newline;
				
				$hdr .= "Content-Type: text/plain; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: " . $this->get_encoding() . $this->newline . $this->newline;
				$hdr .= $this->strip_html() . $this->newline . $this->newline . "--" . $this->alt_boundary . $this->newline;
	
				$hdr .= "Content-Type: text/html; charset=" . $this->charset . $this->newline;
				$hdr .= "Content-Transfer-Encoding: quoted/printable";
				
				if ($this->get_protocol() == 'mail')
				{
					$this->header_str .= $hdr;	
					
					$body  = $this->body . $this->newline . $this->newline; 
					$body .= "--" . $this->alt_boundary . "--" . $this->newline . $this->newline;				
				}
				
				$hdr .= $this->newline . $this->newline;
				$hdr .= $this->body . $this->newline . $this->newline;
				$hdr .= "--" . $this->alt_boundary . "--" . $this->newline . $this->newline;

			break;
		}

		$attachment = array();

		$z = 0;
		
		for ($i=0; $i < count($this->attach_name); $i++)
		{
			$filename = $this->attach_name[$i];
			
			$basename = basename($filename);
			
			$ctype = $this->attach_type[$i];
						
			if (!file_exists($filename))
			{
				$this->error_message("Unable to locate this attachment: ".$filename); 
			}			

			$h  = "--".$this->atc_boundary.$this->newline;
			$h .= "Content-type: ".$ctype."; ";
			$h .= "name=\"".$basename."\"".$this->newline;
			$h .= "Content-Disposition: ".$this->attach_disp[$i].";".$this->newline;
			$h .= "Content-Transfer-Encoding: base64".$this->newline;

			$attachment[$z++] = $h;
			
			$file = filesize($filename) +1;
			
			$fp = fopen($filename, 'r');
			
			$attachment[$z++] = chunk_split(base64_encode(fread($fp, $file)));
				
			fclose($fp);
		}

		if ($this->get_protocol() == 'mail')
		{
			$this->finalbody = $body . implode($this->newline, $attachment).$this->newline."--".$this->atc_boundary."--";	
			
			return;
		}
		
		$this->finalbody = $hdr.implode($this->newline, $attachment).$this->newline."--".$this->atc_boundary."--";	
		
		return;	
	}
	// END
		

	//-------------------------------------
	//	Send Email
	//-------------------------------------

	function send()
	{	
		if (( ! isset($this->recipients) AND ! isset($this->headers['To']))  AND
			( ! isset($this->bcc_array) AND ! isset($this->headers['Bcc'])) AND
			( ! isset($this->headers['Cc'])))
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message("You must include a recipients: To, Cc, or Bcc");
		}

		$this->build_headers();

		if ($this->bcc_batch_mode  AND  count($this->bcc_array) > 0)
		{		
			if (count($this->bcc_array) > $this->bcc_batch_tot)
				return $this->batch_bcc_send();
		}
		
		$this->build_finalbody();
			
		if ( ! $this->mail_spool())
			return false;
		else
			return true;
	}
	// END
	
	
	//--------------------------------------------------
	//	Batch Bcc Send.  Sends groups of Bccs in batches
	//--------------------------------------------------

	function batch_bcc_send()
	{
		$float = $this->bcc_batch_tot -1;
		
		$flag = 0;
		
		$set = "";
		
		$chunk = array();		
		
		for ($i = 0; $i < count($this->bcc_array); $i++)
		{
			if (isset($this->bcc_array[$i]))
				$set .= ", ".$this->bcc_array[$i];
		
			if ($i == $float)
			{	
				$chunk[] = substr($set, 1);
				
				$float = $float + $this->bcc_batch_tot;
						
				$set = "";
			}
			
			if ($i == count($this->bcc_array)-1)
					$chunk[] = substr($set, 1);	
		}

		for ($i = 0; $i < count($chunk); $i++)
		{
			unset($this->headers['Bcc']);

			unset($bcc);

			$bcc = $this->str_to_array($chunk[$i]);

			$bcc = $this->clean_email($bcc);
	
			if ($this->protocol != 'smtp')
				$this->add_header('Bcc', implode(", ", $bcc));
			else
				$this->bcc_array = $bcc;
			
			$this->build_finalbody();

			$this->mail_spool();		
		}
	}
	// END


	//-------------------------------------
	//	Unwrap special elements
	//-------------------------------------

    function unwrap_specials()
    {
        $this->finalbody = preg_replace_callback("/\[unwrap\](.*?)\[\/unwrap\]/si", array($this, 'remove_nl_callback'), $this->finalbody);
    }
    // END


	//-------------------------------------
	//	Strip line-breaks via callback
	//-------------------------------------

    function remove_nl_callback($matches)
    {
        return preg_replace("/(\r\n)|(\r)|(\n)/", "", $matches['1']);    
    }
    // END


	//-------------------------------------
	//	Spool mail to the mail server
	//-------------------------------------

	function mail_spool()
	{
	    $this->unwrap_specials();

		switch ($this->get_protocol())
		{
			case 'mail'	:
			
					if ( ! $this->send_with_mail())
					{
						return ( ! $this->get_debug()) ? false :
								$this->error_message("Fatal error in: mail_with_mail()");
					}
			break;
			case 'sendmail'	: 
								
					if ( ! $this->send_with_sendmail())
					{
						return ( ! $this->get_debug()) ? false :
								$this->error_message("Fatal error in: send_with_sendmail()");
					}
			break;
			case 'smtp'	: 
								
					if ( ! $this->send_with_smtp())
					{
						return ( ! $this->get_debug()) ? false :
								$this->error_message("Fatal error in: send_with_smtp()");
					}
			break;

		}
			$this->good_message("Your message has been successfully sent using ".$this->get_protocol());
			
		return true;
	}
	// END
	

	//-------------------------------------
	//	Send using mail()
	//-------------------------------------

	function send_with_mail()
	{
		if ($this->safe_mode == TRUE)
		{
			if ( ! mail($this->recipients, $this->subject, $this->finalbody, $this->header_str))
				return false;
			else
				return true;		
		}
		else
		{
			if ( ! mail($this->recipients, $this->subject, $this->finalbody, $this->header_str, "-f".$this->clean_email($this->headers['From'])))
				return false;
			else
				return true;
		}
	}
	// END


	//-------------------------------------
	//	Send using Sendmail
	//-------------------------------------

	function send_with_sendmail()
	{
		$fp = @popen($this->mailpath . " -oi -f ".$this->clean_email($this->headers['From'])." -t", 'w');
		
		if( ! is_resource($fp))
		{								
			return ( ! $this->get_debug()) ? false :
					$this->error_message("Unable to open a socket to Sendmail. Please check settings.");
		}
		
		fputs($fp, $this->header_str);
		
		//fputs($fp, $this->newline);
		
		fputs($fp, $this->finalbody);
		
		pclose($fp) >> 8 & 0xFF;
		
		return true;
	}
	// END


	//-------------------------------------
	//	Send using SMTP
	//-------------------------------------

	function send_with_smtp()
	{	
	    if ($this->smtp_host == '')
	        return false;

		$this->smtp_connect();
		
		$this->smtp_authenticate();
		
		$this->send_command('from', $this->clean_email($this->headers['From']));

		foreach($this->recipients as $val)
			$this->send_command('to', $val);
			
		foreach($this->cc_array as $val)
			$this->send_command('to', $val);

		foreach($this->bcc_array as $val)
			$this->send_command('to', $val);
		
		$this->send_command('data');
		
		// $this->send_data($this->header_str . $this->newline . $this->finalbody);

		$this->send_data($this->header_str . $this->finalbody);
		
		$this->send_data('.');

		$reply = $this->get_data();
		
		$this->good_message($reply);			

		if (substr($reply, 0, 3) != '250')
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Failed to send SMTP email. Error: '.$reply);
		}

		$this->send_command('quit');
		
		return true;
	}
	// END
	

	//-------------------------------------
	//	SMTP Connect
	//-------------------------------------

	function smtp_connect()
	{
	
		$this->smtp_connect = fsockopen($this->smtp_host, 
										$this->smtp_port,
										$errno, 
										$errstr, 
										$this->smtp_timeout);

		if( ! is_resource($this->smtp_connect))
		{								
			return ( ! $this->get_debug()) ? false :
					$this->error_message("Unable to open SMTP socket. Error Number: ".$errno." Error Msg: ".$errstr);
		}

		$this->good_message($this->get_data());

		return $this->send_command('hello');
	}
	// END


	//-------------------------------------
	//	Send SMTP command
	//-------------------------------------

	function send_command($cmd, $data = '')
	{
		switch ($cmd)
		{
			case 'hello' :
		
					if ($this->smtp_auth || $this->get_encoding() == '8bit')
						$this->send_data('EHLO '.$this->get_hostname());
					else
						$this->send_data('HELO '.$this->get_hostname());
						
						$resp = 250;
			break;
			case 'from' :
			
						$this->send_data('MAIL FROM:<'.$data.'>');

						$resp = 250;
			break;
			case 'to'	:
			
						$this->send_data('RCPT To:<'.$data.'>');

						$resp = 250;			
			break;
			case 'data'	:
			
						$this->send_data('DATA');

						$resp = 354;			
			break;
			case 'quit'	:
		
						$this->send_data('QUIT');
						
						$resp = 221;
			break;
		}
		
		$reply = $this->get_data();	
		
		if ($this->get_debug())
			echo "<pre>".$cmd.": ".$reply."</pre>";

		if (substr($reply, 0, 3) != $resp)
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Failed to Send Command. Error: '.$reply);
		}
			
		if ($cmd == 'quit')
			fclose($this->smtp_connect);
	
		return true;
	}
	// END


	//-------------------------------------
	// SMTP Authenticate
	//-------------------------------------

	function smtp_authenticate()
	{	
		if ( ! $this->smtp_auth)
			return true;
			
		if ($this->smtp_user == ""  AND  $this->smtp_pass == "")
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Error: You must assign an SMTP username and password.');

		
		$this->send_data('AUTH LOGIN');

		$reply = $this->get_data();			

		if (substr($reply, 0, 3) != '334')
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Failed to send AUTH LOGIN command. Error: '.$reply);
		}

		$this->send_data(base64_encode($this->smtp_user));

		$reply = $this->get_data();			

		if (substr($reply, 0, 3) != '334')
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Failed to authenticate username. Error: '.$reply);
		}

		$this->send_data(base64_encode($this->smtp_pass));

		$reply = $this->get_data();			

		if (substr($reply, 0, 3) != '235')
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Failed to authenticate password. Error: '.$reply);
		}
	
		return true;
	}
	// END


	//-------------------------------------
	//	Send SMTP data
	//-------------------------------------

	function send_data($data)
	{
		if ( ! fwrite($this->smtp_connect, $data . $this->newline))
		{
			return ( ! $this->get_debug()) ? false :
					$this->error_message('Unable to send data: '.$data);
		}
		else
			return true;
	}
	// END


	//-------------------------------------
	//	Get SMTP data
	//-------------------------------------

	function get_data()
	{
        $data = "";
    
		while ($str = fgets($this->smtp_connect, 512)) 
		{            
			$data .= $str;
			
			if (substr($str, 3, 1) == " ")
				break; 	
    	}
    	
    	return $data;
	}
	// END


	//-------------------------------------
	//	Get Hostname
	//-------------------------------------
	
	function get_hostname()
	{
		if ($this->smtp_host != '')
			return $this->smtp_host;
	
		if (function_exists('getenv'))
		{
			$client_ip = getenv('HTTP_CLIENT_IP');
			$x_frd_for = getenv('HTTP_X_FORWARDED_FOR');
			$rmte_addr = getenv('REMOTE_ADDR');
		}
		else
		{
			$server	= (intval(str_replace(".", "", phpversion())) > '410') ? $GLOBALS['HTTP_SERVER_VARS'] : $_SERVER;

			$client_ip = $server['HTTP_CLIENT_IP'];
			$x_frd_for = $server['HTTP_X_FORWARDED_FOR'];
			$rmte_addr = $server['REMOTE_ADDR'];
		}
		
		
		if ($client_ip) 
		{
			$x_cip = explode(".", $client_ip);
			$x_rmt = explode(".", $rmte_addr);
			
			$ip = ($x_cip['0'] != $x_rmt['0']) ? implode(".", array_reverse($x_cip)) : $client_ip;
		}
		elseif ($x_frd_for) 
		{
			$ip = (strstr($x_frd_for, ",")) ? end(explode(",", $x_frd_for)) : $x_frd_for;
		}
		else
		{
			$ip = $rmte_addr;
		}
		
		return ($ip == '') ? $this->smtp_host : $ip;		
	}
	// END


	//-------------------------------------
	//	Error Message
	//-------------------------------------

	function error_message($msg)
	{					
		exit($msg);
	}
	// END
	
	
	//-------------------------------------
	//	Good Message
	//-------------------------------------

	function good_message($msg)
	{					
		if ($this->get_debug())	
			echo $msg."<br />";
	}
	// END

	//-------------------------------------
	//	Print Sent Message
	//-------------------------------------

	function print_message()
	{		
		echo
			"<pre>".
			$this->header_str."\n".
			$this->subject."\n".
			$this->finalbody.
			"</pre>";
	}
	// END	


	//-------------------------------------
	//	Mime Types
	//-------------------------------------
	
	function mime_types($ext = "")
	{
		$mimes = array(	'hqx'	=>	'application/mac-binhex40',
						'cpt'	=>	'application/mac-compactpro',
						'doc'	=>	'application/msword',
						'bin'	=>	'application/macbinary',
						'dms'	=>	'application/octet-stream',
						'lha'	=>	'application/octet-stream',
						'lzh'	=>	'application/octet-stream',
						'exe'	=>	'application/octet-stream',
						'class'	=>	'application/octet-stream',
						'psd'	=>	'application/octet-stream',
						'so'	=>	'application/octet-stream',
						'sea'	=>	'application/octet-stream',
						'dll'	=>	'application/octet-stream',
						'oda'	=>	'application/oda',
						'pdf'	=>	'application/pdf',
						'ai'	=>	'application/postscript',
						'eps'	=>	'application/postscript',
						'ps'	=>	'application/postscript',
						'smi'	=>	'application/smil',
						'smil'	=>	'application/smil',
						'mif'	=>	'application/vnd.mif',
						'xls'	=>	'application/vnd.ms-excel',
						'ppt'	=>	'application/vnd.ms-powerpoint',
						'wbxml'	=>	'application/vnd.wap.wbxml',
						'wmlc'	=>	'application/vnd.wap.wmlc',
						'dcr'	=>	'application/x-director',
						'dir'	=>	'application/x-director',
						'dxr'	=>	'application/x-director',
						'dvi'	=>	'application/x-dvi',
						'gtar'	=>	'application/x-gtar',
						'php'	=>	'application/x-httpd-php',
						'php4'	=>	'application/x-httpd-php',
						'php3'	=>	'application/x-httpd-php',
						'phtml'	=>	'application/x-httpd-php',
						'phps'	=>	'application/x-httpd-php-source',
						'js'	=>	'application/x-javascript',
						'swf'	=>	'application/x-shockwave-flash',
						'sit'	=>	'application/x-stuffit',
						'tar'	=>	'application/x-tar',
						'tgz'	=>	'application/x-tar',
						'xhtml'	=>	'application/xhtml+xml',
						'xht'	=>	'application/xhtml+xml',
						'zip'	=>	'application/zip',
						'mid'	=>	'audio/midi',
						'midi'	=>	'audio/midi',
						'mpga'	=>	'audio/mpeg',
						'mp2'	=>	'audio/mpeg',
						'mp3'	=>	'audio/mpeg',
						'aif'	=>	'audio/x-aiff',
						'aiff'	=>	'audio/x-aiff',
						'aifc'	=>	'audio/x-aiff',
						'ram'	=>	'audio/x-pn-realaudio',
						'rm'	=>	'audio/x-pn-realaudio',
						'rpm'	=>	'audio/x-pn-realaudio-plugin',
						'ra'	=>	'audio/x-realaudio',
						'rv'	=>	'video/vnd.rn-realvideo',
						'wav'	=>	'audio/x-wav',
						'bmp'	=>	'image/bmp',
						'gif'	=>	'image/gif',
						'jpeg'	=>	'image/jpeg',
						'jpg'	=>	'image/jpeg',
						'jpe'	=>	'image/jpeg',
						'png'	=>	'image/png',
						'tiff'	=>	'image/tiff',
						'tif'	=>	'image/tiff',
						'css'	=>	'text/css',
						'html'	=>	'text/html',
						'htm'	=>	'text/html',
						'shtml'	=>	'text/html',
						'txt'	=>	'text/plain',
						'text'	=>	'text/plain',
						'log'	=>	'text/plain',
						'rtx'	=>	'text/richtext',
						'rtf'	=>	'text/rtf',
						'xml'	=>	'text/xml',
						'xsl'	=>	'text/xml',
						'mpeg'	=>	'video/mpeg',
						'mpg'	=>	'video/mpeg',
						'mpe'	=>	'video/mpeg',
						'qt'	=>	'video/quicktime',
						'mov'	=>	'video/quicktime',
						'avi'	=>	'video/x-msvideo',
						'movie'	=>	'video/x-sgi-movie',
						'doc'	=>	'application/msword',
						'word'	=>	'application/msword',
						'xl'	=>	'application/excel',
						'eml'	=>	'message/rfc822'
					);

		return ( ! isset($mimes[strtolower($ext)])) ? "application/x-unknown-content-type" : $mimes[strtolower($ext)];
	}
	// END
}
// END CLASS
?>