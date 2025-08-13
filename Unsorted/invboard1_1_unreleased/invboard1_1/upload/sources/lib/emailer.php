<?php

/*
+--------------------------------------------------------------------------
|   Invision Board v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 Invision Power Services
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Sending email module
|   > Module written by Matt Mecham
|   > Date started: 26th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
|
|   QUOTE OF THE MODULE: (Taken from "Shrek" (c) Dreamworks Pictures)
|   --------------------
|	DONKEY: We can stay up late, swap manly stories and in the morning,  
|           I'm making waffles!
|
+-------------------------------------------------------------------------- 
*/

// This module is fairly basic, more functionality is expected in future
// versions (such as MIME attachments, SMTP stuff, etc)


class emailer {


	var $from         = "";
	var $to           = "";
	var $subject      = "";
	var $message      = "";
	var $header       = "";
	var $footer       = "";
	var $template     = "";
	var $error        = "";
	var $parts        = array();
	var $bcc          = array();
	var $mail_headers = array();
	var $multipart    = "";
	var $boundry      = "";
	
	var $temp_dump = 0;
	
	function emailer() {
		global $ibforums;
		
		// Assign $from as the admin out email address, this can be
		// over-riden at any time.
		
		$this->from = $ibforums->vars['email_out'];
		
		$this->temp_dump = $ibforums->vars['fake_mail'];
		
		// Temporarily assign $header and $footer, this can be over-riden
		// also
		
		$this->header = $ibforums->vars['email_header'];
		$this->footer = $ibforums->vars['email_footer'];
		$this->boundry = "----=_NextPart_000_0022_01C1BD6C.D0C0F9F0";  //"b".md5(uniqid(time()));
		
	}
	
	function add_attachment($data = "", $name = "", $ctype='application/octet-stream')
	{
	
		$this->parts[] = array( 'ctype'  => $ctype,
								'data'   => $data,
								'encode' => 'base64',
								'name'   => $name
							  );
	}
	
	
	
	function build_headers()
	{
		global $ibforums;
		
		$this->mail_headers  = "From: \"".$ibforums->vars['board_name']."\" <".$this->from.">\n";
		
		if ( count( $this->bcc ) > 1 )
		{
			$this->mail_headers .= "Bcc: ".implode( "," , $this->bcc ) . "\n";
		}
		
		$this->mail_headers .= "Return-Path: ".$this->from."\n";
		$this->mail_headers .= "X-Priority: 3\n";
		$this->mail_headers .= "X-Mailer: IBForums PHP Mailer\n";
		
		if ( count ($this->parts) > 0 )
		{
		    
			$this->mail_headers .= "MIME-Version: 1.0\n";
			$this->mail_headers .= "Content-Type: multipart/mixed;\n\tboundary=\"".$this->boundry."\"\n\nThis is a MIME encoded message.\n\n--".$this->boundry;
			$this->mail_headers .= "\nContent-Type: text/plain;\n\tcharset=\"iso-8859-1\"\nContent-Transfer-Encoding: quoted-printable\n\n".$this->message."\n\n--".$this->boundry;
			$this->mail_headers .= $this->build_multipart();
			
			$this->message = "";
		}
	
	}
	
	function encode_attachment($part) {
		
		$msg = chunk_split(base64_encode($part['data']));
		
		return "Content-Type: ".$part['ctype']. ($part['name'] ? ";\n\tname =\"".$part['name']."\"" : "").
			  "\nContent-Transfer-Encoding: ".$part['encode']."\nContent-Disposition: attachment;\n\tfilename=\"".$part['name']."\"\n\n".$msg."\n";
		
	}
	
	function build_multipart() {
	
		$multipart = "";
		
		for ($i = sizeof($this->parts) - 1 ; $i >= 0 ; $i--)
		{
			$multipart .= "\n".$this->encode_attachment($this->parts[$i]) . "--".$this->boundry;
		}
		
		return $multipart . "--\n";
		
	}
	
	
	//+--------------------------------------------------------------------------
	// send_mail:
	// Physically sends the email
	//+--------------------------------------------------------------------------
	
	function send_mail()
	{
		global $ibforums;
		
		$this->to   = preg_replace( "/[ \t]+/" , " "  , $this->to );
		$this->from = preg_replace( "/[ \t]+/" , " "  , $this->from );
		
		$this->to   = preg_replace( "/,,/"     , ","  , $this->to );
		$this->from = preg_replace( "/,,/"     , ","  , $this->from );
		
		$this->to     = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->to  );
		$this->from   = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->from);
		
		$this->subject = $this->clean_message($this->subject);
		
		$this->build_headers();
		
		if ( ($this->from) and ($this->subject) )
		{
			$this->subject .= " ( From ".$ibforums->vars['board_name']." )";
			
			if ($this->temp_dump == 1)
			{
				$blah = $this->subject."\n------------\n".$this->mail_headers."\n\n".$this->message;
				
				$pathy = '/Library/WebServer/Documents/mail/'.date("F.Y.h:i.A").".txt"; // OS X rules!
				$fh = fopen ($pathy, 'w');
				fputs ($fh, $blah, strlen($blah) );
				fclose($fh);
			}
			else
			{
				if ( ! @mail( $this->to, $this->subject, $this->message, $this->mail_headers ) )
				{
					$this->fatal_error("Could not sent the email", "Failed at 'mail' command");
				}
			}
		}
		else
		{
			return FALSE;
		}
	}
	
	
	//+--------------------------------------------------------------------------
	// get_template:
	// Queries the database, and stores the template we wish to use in memory
	//+--------------------------------------------------------------------------

	function get_template($name="", $language="en") {
		global $ibforums, $IB, $DB;
		
		if ($name == "")
		{
			$this->error++;
			$this->fatal_error("A valid email template ID was not passed to the email library during template parsing", "");
		}
		
		
		if (! file_exists("./lang/$language/email_content.php") )
		{
			require "./lang/".$ibforums->vars['default_language']."/email_content.php";
		}
		else
		{
			require "./lang/$language/email_content.php";
		}
		
		if (! isset($EMAIL[ $name ]) )
		{
			$this->fatal_error("Could not find an email template with an ID of '$name'", "");
		}
		$this->template = $EMAIL['header'] . $EMAIL[ $name ] . $EMAIL['footer'];
	}
		
	//+--------------------------------------------------------------------------
	// build_message:
	// Swops template tags into the corresponding string held in $words array.
	// Also joins header and footer to message and cleans the message for sending
	//+--------------------------------------------------------------------------
		
	function build_message($words) {
		global $ibforums;
		
		if ($this->template == "") {
			$this->error++;
			$this->fatal_error("Could not build the email message, no template assigned", "Make sure a template is assigned first.");
		}
		
		$this->message = $this->template;
		
		// Add some default words
		
		$words['BOARD_ADDRESS'] = $ibforums->vars['board_url'] . '/index.' . $ibforums->vars['php_ext'];
		$words['WEB_ADDRESS']   = $ibforums->vars['home_url'];
		$words['BOARD_NAME']    = $ibforums->vars['board_name'];
		$words['SIGNATURE']     = $ibforums->vars['signature'];
		
		// Swop the words
		
		$this->message = preg_replace( "/<#(.+?)#>/e", "\$words[\\1]", $this->message );
		
		$this->message = $this->clean_message( $this->message );
		
	}

	
	//+--------------------------------------------------------------------------
	// clean_message: (Mainly used internally)
	// Ensures that \n and <br> are converted into CRLF (\r\n)
	// Also unconverts some iB_CODE.
	//+--------------------------------------------------------------------------
	
	function clean_message($message = "" ) {
	
		$message = preg_replace( "/^(\r|\n)+?(.*)$/", "\\2", $message );
	
		$message = preg_replace( "#<b>(.+?)</b>#" , "\\1", $message );
		$message = preg_replace( "#<i>(.+?)</i>#" , "\\1", $message );
		$message = preg_replace( "#<s>(.+?)</s>#" , "--\\1--", $message );
		$message = preg_replace( "#<u>(.+?)</u>#" , "-\\1-"  , $message );
		
		$message = preg_replace( "#<!--emo&(.+?)-->.+?<!--endemo-->#", "\\1" , $message );
		
		$message = preg_replace( "#<!--c1-->(.+?)<!--ec1-->#", "\n\n------------ CODE SAMPLE ----------\n"  , $message );
		$message = preg_replace( "#<!--c2-->(.+?)<!--ec2-->#", "\n-----------------------------------\n\n"  , $message );
		
		$message = preg_replace( "#<!--QuoteBegin-->(.+?)<!--QuoteEBegin-->#"                       , "\n\n------------ QUOTE ----------\n" , $message );
		$message = preg_replace( "#<!--QuoteBegin--(.+?)\+(.+?)-->(.+?)<!--QuoteEBegin-->#"         , "\n\n------------ QUOTE ----------\n" , $message );
		$message = preg_replace( "#<!--QuoteEnd-->(.+?)<!--QuoteEEnd-->#"                           , "\n-----------------------------\n\n" , $message );
		
		$message = preg_replace( "#<!--Flash (.+?)-->.+?<!--End Flash-->#e"                         , "(FLASH MOVIE)" , $message );
		$message = preg_replace( "#<img src=[\"'](\S+?)['\"].+?".">#"                                  , "(IMAGE: \\1)"   , $message );
		$message = preg_replace( "#<a href=[\"'](http|https|ftp|news)://(\S+?)['\"].+?".">(.+?)</a>#"  , "(URL: \\1)"     , $message );
		$message = preg_replace( "#<a href=[\"']mailto:(.+?)['\"]>(.+?)</a>#"                       , "(EMAIL: \\2)"   , $message );
		
		$message = preg_replace( "#<!--sql-->(.+?)<!--sql1-->(.+?)<!--sql2-->(.+?)<!--sql3-->#e"    , "\n\n--------------- SQL -----------\n\\2\n----------------\n\n", $message);
		$message = preg_replace( "#<!--html-->(.+?)<!--html1-->(.+?)<!--html2-->(.+?)<!--html3-->#e", "\n\n-------------- HTML -----------\n\\2\n----------------\n\n", $message);
		
		$message = preg_replace( "#<!--EDIT\|.+?\|.+?-->#" , "" , $message );
		
		$message = preg_replace( "#<.+?".">#" , "" , $message );
		//$message = str_replace( "\r"  , ""    , $message );
		//$message = str_replace( "\n\n", "\n"  , $message );
		
		$message = str_replace( "<br>" , "\n", $message );
		
		$message = str_replace( "&quot;", "\"", $message );
		$message = str_replace( "&#092;", "\\", $message );
		$message = str_replace( "&#036;", "\$", $message );
		$message = str_replace( "&#33;" , "!", $message );
		$message = str_replace( "&#39;" , "'", $message );
		$message = str_replace( "&lt;"  , "<", $message );
		$message = str_replace( "&gt;"  , ">", $message );
		$message = str_replace( "&amp;" , "&", $message );
		
		return $message;
	}
	
	function fatal_error($msg, $help="")
	{
		echo("<h1>Mail Error!</h1><br><b>$msg</b><br>$help");
		exit();
	}

}

?>