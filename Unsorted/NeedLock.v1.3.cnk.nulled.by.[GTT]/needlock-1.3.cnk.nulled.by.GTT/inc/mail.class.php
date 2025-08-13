<?php

/*

  $$Index - mail.class.php

  +------------------------------------------+

  @ mail functions
  
  +------------------------------------------+
*/

class mailer {


 var $from         = "";
 var $to           = "";
 var $subject      = "";
 var $message      = "";
 var $header       = "";
 var $footer       = "";
 var $template     = "";
 var $error        = "";
 var $parts        = array();
 var $cc           = array(); 
 var $bcc          = array();
 var $mail_headers = array();
 var $multipart    = "";
 var $boundry      = "";
 var $charset      = "koi8-r";

 var $smtp_fp      = FALSE;
 var $smtp_msg     = "";
 var $smtp_port    = "";
 var $smtp_host    = "localhost";
 var $smtp_user    = "";
 var $smtp_pass    = "";
 var $smtp_code    = "";
	
 var $temp_dump  = 0;
 var $mail_method  = 'mail';

/*
  @ init class, set default mail_method ( optional )
*/
function mailer($method='') {
   $this->mail_method = $method == '' ? "mail" : $this->mail_method;
   $this->boundry = "----=_NextPart_000_0022_01C1BD6C.D0C0F9F0";
}
 
/*
  @ set mail_method
*/
function setMailMethod($method) {
   $this->mail_method = $method;
}

/*
  @ set all params for smtp mail send
*/
function setSmtpParams($port="",$host="",$user,$pass) {
   $this->smtp_port   = $port == '' ? $port : 25;
   $this->smtp_host   = $host == '' ? $host : 'localhost';
   $this->smtp_user   = $user;
   $this->smtp_pass   = $pass;
}
 
/*
  @ set from 
*/
function setFrom($from) {
   $this->from = $from;
}
 
/*
  @ set to
*/
function setTo($to) {
   $this->to = $to;
}
 
/*
  @ set Cc
  @ param $cc is array
*/
function setCc($cc) {
   $this->cc = $cc;
}
 
/*
  @ set Bcc
  @ param $bcc is array
*/
function setBcc($bcc) {
   $this->bcc = $bcc;
}
 
/*
  @ set subject
*/
function setSubj($subj) {
   $this->subject = $subj;
}
 
/*
  @ set charset
*/
function setCharset($charset) {
   $this->charset = $charset;
}
 
/*
  @ compile and set message
  @ param $templateName is a message template
*/
function setMessage($templateName,$dataArray) {
   global $needsecure,$DIRS,$std,$INFO;
   
   $fh = @fopen("{$DIRS['MAIL_TPLS']}{$templateName}.mtpl","r");
   while ( $line = @fgets($fh) ) {
     $message .= preg_replace("#\[tag\](.+?)\[/tag\]#ies","\$dataArray['\\1']",$line);
   }                          
   @fclose($fh);   
   
   $this->message = $message;
}	

function send_mail() {
		
    $this->to   = preg_replace( "/[ \t]+/" , " "  , $this->to );
    $this->from = preg_replace( "/[ \t]+/" , " "  , $this->from );
		
    $this->to   = preg_replace( "/,,/"     , ","  , $this->to );
    $this->from = preg_replace( "/,,/"     , ","  , $this->from );
		
    $this->to     = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->to  );
    $this->from   = preg_replace( "#\#\[\]'\"\(\):;/\$!£%\^&\*\{\}#" , "", $this->from);
		
    $this->subject = $this->clean($this->subject);
		
    $this->build_headers();
		
		
   if ( ($this->to) and ($this->from) and ($this->subject) ) {
      
      if ($this->temp_dump == 1) {
	
	$blah = $this->subject."\n------------\n".$this->mail_headers."\n\n".$this->message;
	$pathy = '/tmp/'.date("F.Y.h:i.A").".txt";
	$fh = fopen ($pathy, 'w');
	fputs ($fh, $blah, strlen($blah) );
	fclose($fh);
      
      } else {
	
	if ($this->mail_method != 'smtp') {
	    $check = @mail( $this->to, $this->subject, $this->message, $this->mail_headers );	
		if ( $check ) {
		   return TRUE;
		} else {
		   return FALSE;
		}
	} else {
		$this->smtp_send_mail();
        }
      }
   } else {
     return FALSE;
   }
   
}

/*
  @
  @   SMTP methods
  @
*/
	
/*
  @ get_line()
  @
  @ Reads a line from the socket and returns
  @ CODE and message from SMTP server
  @
*/
function smtp_get_line() {
   
   $this->smtp_msg = "";
		
   while ( $line = fgets( $this->smtp_fp, 515 ) ) {
	$this->smtp_msg .= $line;
			
	if ( substr($line, 3, 1) == " " ) {
	   break;
	}
   }
}
	
/*
  @ send_cmd()
  @
  @ Sends a command to the SMTP server
  @ Returns TRUE if response, FALSE if not
  @
*/
function smtp_send_cmd($cmd) {
		
   $this->smtp_msg  = "";
   $this->smtp_code = "";
		
   fputs( $this->smtp_fp, $cmd."\r\n" );
		
   $this->smtp_get_line();
		
   $this->smtp_code = substr( $this->smtp_msg, 0, 3 );
		
   return $this->smtp_code == "" ? FALSE : TRUE;
}

/*
  @ error()
*/
function smtp_error($err = "") {
   $this->fatal_error( "SMTP protocol failure!</b><br>Host: ".$this->smtp_host."<br>Return Code: ".$this->smtp_code."<br>Return Msg: ".$this->smtp_msg."<br>Error: $err", "Check your SMTP settings" );
}
	
/*
  @ crlf_encode()
  @
  @ RFC 788 specifies line endings in
  @ \r\n format with no periods on a 
  @ new line
*/
	
function smtp_crlf_encode($data) {
   $data .= "\n";
   $data  = str_replace( "\n", "\r\n", str_replace( "\r", "", $data ) );
   $data  = str_replace( "\n.\r\n" , "\n. \r\n", $data );
		
   return $data;
}
	
/*
  @ send our smtp mail
*/	
function smtp_send_mail() {
		
   $this->smtp_fp = fsockopen( $this->smtp_host, intval($this->smtp_port), $errno, $errstr, 30 );
		
   if ( ! $this->smtp_fp ) {
      $this->smtp_error("Could not open a socket to the SMTP server");
   }
		
   $this->smtp_get_line();
		
   $this->smtp_code = substr( $this->smtp_msg, 0, 3 );
		
   if ( $this->smtp_code == 220 ) {
       $data = $this->smtp_crlf_encode( $this->mail_headers."\n" . $this->message);
			
        $this->smtp_send_cmd("HELO ".$this->smtp_host);
			
	if ( $this->smtp_code != 250 ) {
            $this->smtp_error("HELO");
	}
			
	if ($this->smtp_user and $this->smtp_pass) {
	   $this->smtp_send_cmd("AUTH LOGIN");
				
   	  if ( $this->smtp_code == 334 ) {
	     $this->smtp_send_cmd( base64_encode($this->smtp_user) );
					
	        if ( $this->smtp_code != 334  ) {
	           $this->smtp_error("Username not accepted from the server");
	        }
					
	        $this->smtp_send_cmd( base64_encode($this->smtp_pass) );
					
	        if ( $this->smtp_code != 235 ) {
	           $this->smtp_error("Password not accepted from the server");
	        }
	  } else {
	      $this->smtp_error("This server does not support authorisation");
	    }
	}
			
	$this->smtp_send_cmd("MAIL FROM:".$this->from);
			
	if ( $this->smtp_code != 250 ) {
	   $this->smtp_error();
	}
			
	$to_arry = array( $this->to );
			
	if ( count( $this->cc ) > 0 ) {
	   foreach ($this->cc as $cc) {
		if ($cc != "") {
		   $to_arry[] = $cc;
		}
	   }
	}
	
	if ( count( $this->bcc ) > 0 ) {
	   foreach ($this->bcc as $bcc) {
		if ($bcc != "") {
		   $to_arry[] = $bcc;
		}
	   }
	}
			
	foreach( $to_arry as $to_email ) {
		$this->smtp_send_cmd("RCPT TO:".$to_email);
				
		if ( $this->smtp_code != 250 ) {
		   $this->smtp_error();
		   break;
		}
	}
			
	/*
	  @ SEND MAIL!
	*/
			
	$this->smtp_send_cmd("DATA");
			
	if ( $this->smtp_code == 354 ) {
	   fputs( $this->smtp_fp, $data."\r\n" );
	} else {
	   $this->smtp_error("Error on write to SMTP server");
	}
			
	$this->smtp_send_cmd(".");
			
	if ( $this->smtp_code != 250 ) {
	   $this->smtp_error();
	}
			
	$this->smtp_send_cmd("quit");
			
	if ( $this->smtp_code != 221 ) {
	   $this->smtp_error();
	}
			
	@fclose( $this->smtp_fp );
    
   } else {
       $this->smtp_error();
     }
}
 
//+-------------------------------------+//
//                                       //
//   Common functions for module use     //
//                                       // 
//+-------------------------------------+//

/*
  @ load letter template
*/
function loadTemplate($name) {
    
}
 
/*
  @ set parts ( attachments )
*/
function add_attachment($data = "", $name = "", $ctype='application/octet-stream') {
    $this->parts[] = array( 'ctype'  => $ctype,
			    'data'   => $data,
			    'encode' => 'base64',
			    'name'   => $name
			  );
}

function build_headers() {
   	
   $this->mail_headers  = "From: {$this->from}\n";
		
   if ( $this->mail_method != 'smtp' ) {
        if ( count( $this->cc ) > 1 ) {
	   $this->mail_headers .= "Cc: ".implode( "," , $this->cc ) . "\n";
	}
	if ( count( $this->bcc ) > 1 ) {
	   $this->mail_headers .= "Bcc: ".implode( "," , $this->bcc ) . "\n";
	}
    } else {
	if ( $this->to ) {
	   $this->mail_headers .= "To: ".$this->to."\n";
	}
        $this->mail_headers .= "Subject: ".$this->subject."\n";
      }
		
   $this->mail_headers .= "Return-Path: ".$this->from."\n";
   $this->mail_headers .= "X-Priority: 3\n";
   $this->mail_headers .= "X-Mailer: PHP Mailer\n";
			
   if ( count ($this->parts) > 0 ) {
    
	$this->mail_headers .= "MIME-Version: 1.0\n";
	$this->mail_headers .= "Content-Type: multipart/mixed;\n\tboundary=\"".$this->boundry."\"\n\nThis is a MIME encoded message.\n\n--".$this->boundry;
	$this->mail_headers .= "\nContent-Type: text/plain;\n\tcharset=\"".$this->charset."\"\nContent-Transfer-Encoding: quoted-printable\n\n".$this->message."\n\n--".$this->boundry;
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
		
   for ($i = sizeof($this->parts) - 1 ; $i >= 0 ; $i--) {
	$multipart .= "\n".$this->encode_attachment($this->parts[$i]) . "--".$this->boundry;
   }
		
   return $multipart . "--\n";
		
}
	
/*
  @ clean_message
  @ Ensures that \n and <br> are converted into CRLF (\r\n)
*/
function clean($message = "" ) {
	
   $message = preg_replace( "/^(\r|\n)+?(.*)$/", "\\2", $message );
   $message = preg_replace( "#<.+?".">#" , "" , $message );
        
// $message = str_replace( "\r"  , ""    , $message );
// $message = str_replace( "\n\n", "\n"  , $message );
   $message = str_replace( "<br>" , "\n", $message );
		
   $message = str_replace( "&quot;", "\"", $message );
   $message = str_replace( "&#092;", "\\", $message );
   $message = str_replace( "&#036;", "\$", $message );
   $message = str_replace( "&#33;" , "!", $message );
   $message = str_replace( "&#39;" , "'", $message );
   $message = str_replace( "&lt;"  , "<", $message );
   $message = str_replace( "&gt;"  , ">", $message );
   $message = str_replace( "&#124;", '|', $message );
   $message = str_replace( "&amp;" , "&", $message );
		
   return $message;
}

/*
  @ Shows fatal error
*/	
function fatal_error($msg, $help="") {
   echo("<h1>Mail Error!</h1><br><b>$msg</b><br>$help");
   exit();
}
	

}


?>
