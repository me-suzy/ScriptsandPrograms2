<?  ##############################################
   ### SquizLib ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: mime_mail/mime_mail.inc
## Desc: A class that manages mime mails.
## $Source: /home/cvsroot/squizlib/mime_mail/mime_mail.inc,v $
## $Revision: 1.16 $
## $Author: bvial $
## $Date: 2003/10/27 00:24:49 $
#######################################################################
# Requires object from squizlib
global $SQUIZLIB_PATH;
include_once("$SQUIZLIB_PATH/object/object.inc");
#---------------------------------------------------------------------#

 class Mime_Mail extends Object {
	
	# Public vars
	var $to='';
	var $from='';
	var $subject='';
	var $bounce_address='';
	var $headers = '';
	var $text_alternative = ''; # Text which will be displayed if a UA can not read the attachement
	
	# Private vars
	var $BASE64		= 'base64';
	var $BIT7		= '7bit';
	var $NOSUBJECT	= '(No Subject)';
	var $WARNING	= 'This is a MIME encoded message';
	var $OCTET		= 'application/octet-stream';
	var $TEXT		= 'text/plain';
	var $HTML		= 'text/html';
	var $JPEG		= 'image/jpg';
	var $GIF		= 'image/gif';
	var $CRLF		= "\n";
	var $CHARSET	= 'us-ascii';
	var $INLINE		= 'inline';
	var $ATTACH		= 'attachment';

	var $mimeparts = array(); # An array of all different parts in the email

	 ############
	# Constuctor
	function Mime_Mail($from='', $to='', $subject='', $headers = '', $text_alternative) {
		$this->to = $to;
		$this->from = $from;
		$this->subject = $subject;
		if (is_array($headers)) {
			if (sizeof($headers)>1) 
				$headers=join($this->CRLF, $headers);
			else
				$headers=$headers[0];
		}
		if ($from) {
			$headers = preg_replace("!(from:\ ?.+?[\r\n]?\b)!i", '', $headers);
		}

		/*
			This gets the bounce header so php can send the "from" properly.
			It might be either
			(a) easier to pass in - I probably won't be able to find all references to mime_mail or
			(b) easier to get from the array of headers - but i'd have to do the check if headers isn't an array anyway.
		*/
		if (preg_match("/return-path/i",$headers)) {
			preg_match("/return-path:[\s]+(.*)/i",$headers,$return_path);
			if ($return_path[1]) {
				$this->set_bounce_address(trim($return_path[1]));
			} else {
				$this->set_bounce_address($from);
			}
		} else {
			$this->set_bounce_address($from);
		}

		$this->headers = chop($headers);
		$this->text_alternative = $text_alternative;
		$this->attach($text_alternative,"","text/plain","7bit");
		return;
	}

	 ######################
	# Changes the to field
	function set_to($new_to='') {
		if($new_to != $this->to) {
			$this->to = $new_to;
			unset($this->email);
		}
	}
	 ######################
	# Changes the from field
	function set_from($new_from='') {
		if($new_from != $this->from) {
			$this->from = $new_from;
			unset($this->email);
		}
	}
	 ######################
	# Changes the subject field
	function set_subject($new_subject='') {
		if($new_subject != $this->subject) {
			$this->subject = $new_subject;
			unset($this->email);
		}
	}

	function set_bounce_address($email='') {
		if ($email != $this->bounce_address) {
			if (preg_match("/</", $email)) {
				preg_match("/<([^>]*)>/",$email,$return_email);
				$bounce_email = $return_email[1];
			} else {
				$bounce_email = $email;
			}
			$this->bounce_address = $bounce_email;
			unset($this->email);
		}
	}

	  ###################################################
	 # Given a path to a file, attach it to the email
	function fattach($path='', $description='', $contenttype='', $encoding='', $disp='') {
		
		# Set the content type and encoding to defaults
		if(!$contenttype) $contenttype = $this->OCTET;
		if(!$encoding) $encoding = $this->BASE64;
		if(!$disp) $disp = "attachment; filename=\"".basename($path)."\"";

		# Does the file actually exist
		if (!file_exists($path)) {
			return "File does not exist";
		}
		
		# Lets read in the file
		$fp = fopen($path, "rb");	# (b)inary for Win compatability
		if (!$fp) {
			return "Could not open file: ".$path;
		}
		$contenttype .= "; name=".basename($path);
		$data = fread($fp, filesize($path));
		return $this->attach($data, 
				$description, 
				$contenttype,
				$encoding,
				$disp);	
	}


	 ###########################################
	# Lets attache something to the email
	function attach($data='', $description='', $contenttype='', $encoding='', $disp='') {
		# Set the content type and encoding to defaults
		if(!$contenttype) $contenttype = $this->OCTET;
		if(!$encoding) $encoding = $this->BASE64;
		
		if (empty($data)) {
			return "No data to be attached";
		}

		if ($encoding == $this->BIT7) {
			$emsg = $data;
		}
		elseif ($encoding == $this->BASE64) {
			$emsg = base64_encode($data);
			# Split the data so that it is RFC complient
			$emsg = chunk_split($emsg,76,$this->CRLF);
		}
		
		# Check if content-type is text/plain and if charset is not specified append default CHARSET
		if (preg_match("!^".$this->TEXT."!i", $contenttype) && !preg_match("!;charset=!i", $contenttype)) $contenttype .= ";charset=".$this->CHARSET ;

		$msg = sprintf("Content-Type: %sContent-Transfer-Encoding: %s%s%s%s",$contenttype.$this->CRLF,$encoding.$this->CRLF,(($description)?"Content-Description: $description".$this->CRLF:""),($disp?"Content-Disposition: $disp".$this->CRLF:""),$this->CRLF.$emsg.$this->CRLF);
		$this->mimeparts[] = $msg;
		return sizeof($this->mimeparts);
	}

	 ####################################################
	# Build the mail message in MIME format
	function build_message() {
		$msg = "";
		$boundary = 'PM'.chr(rand(65, 91)).'------'.md5(uniqid(rand()));	# Boundary marker
		$nparts = sizeof($this->mimeparts);

		# There is more than one part, hence set the MIME header to mulipart/mixed or if the text alternative is set and there are attachments then set it to multipart/alternative
		if (is_array($this->mimeparts) && ($nparts > 0)) {
			$c_ver = "MIME-Version: 1.0".$this->CRLF;
			if(!empty($this->text_alternative) && $nparts > 1) {
				$c_type = 'Content-Type: multipart/alternative;'.$this->CRLF."\tboundary=\"$boundary\"".$this->CRLF;
			} else {
				$c_type = 'Content-Type: multipart/mixed;'.$this->CRLF."\tboundary=\"$boundary\"".$this->CRLF;
			}
			$c_enc = "Content-Transfer-Encoding: ".$this->BIT7.$this->CRLF;
			$c_desc = (($c_desc)?"Content-Description: $c_desc".$this->CRLF:"");

			# Now create the MIME parts of the email
			for ($i=0 ; $i < $nparts; $i++) {
				if (!empty($this->mimeparts[$i])) 
					$msg .= '--'.$boundary.$this->CRLF.$this->mimeparts[$i].$this->CRLF;
			}
			$msg .= '--'.$boundary.'--'.$this->CRLF;
			$msg = $c_ver.$c_type.$c_enc.$c_desc.$warning.$msg;
		}
		return $msg;
	}

	 ##################################################################
	# Lets generate the mail message
	# This returns the addtional headers to pass to the mail function
	function gen_email($force=false) {
		if (!empty($this->email) && !$force) return $this->email ;
		$email = "";
		if (empty($this->subject)) $this->subject = $this->NOSUBJECT;
		if (!empty($this->from)) $email .= 'From: '.$this->from.$this->CRLF;
		if (!empty($this->headers)) $email .= $this->headers.$this->CRLF;
		$email .= $this->build_message();
		$this->email = $email;
		return $this->email;
	}

	 ############################################
	# Lets just print out the mail message
	function print_mail($force=false) {
		$email =  $this->gen_email($force);
		if (!empty($this->to)) $email = 'To: '.$this->to.$this->CRLF.$email;
		if (!empty($this->subject)) $email = 'Subject: '.$this->subject.$this->CRLF.$email;
		echo $email;
	}

	 #######################################################
	# Send the mail out on the local mailer
	function send_mail($force=false) {
		$email = $this->gen_email($force);
		if (empty($this->to)) {
			return "To Address not specified";
		}
		if (!$this->bounce_address) $this->bounce_address = $_SERVER['SERVER_ADMIN'];
		mail($this->to, $this->subject,"", $email, "-f$this->bounce_address");
		return true;
	}
} 
?>
