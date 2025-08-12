<?
/**
* Klasse zum Verwalten von emails
*
* Diese Klasse dient dazu, gespeicherte Emails zu verwalten. Die ver-
* wendete Datenbankstruktur:
*
* (...)
*
* @access	public
* @author	Carsten Gräf
* @copyright	Gräf, 23.8.2001
* @version	0.3 beta
* @package	mail
*/

	class l4w_mail {

		var $imap_stream;
		var $header;
		var $structure;
		var $default_font;
		var $body;
		var $db;
		var $subject;
		var $from;
		var $to;
		var $date;
		var $size;
		var $myUID;

		var $folder;

		var $user_id;
		var $group;

		var $counter = 1;

		function init ($db, $default_font) {
			$this->db           = $db;
			$this->default_font = $default_font;
			$this->folder       = 1;
		}

		function initialise_by_overview ($overview, $account_id) {
			$this->reset();
			$this->subject = mysql_escape_string ($this->My_imap_mime_header_decode($overview->subject));
			$this->from    = mysql_escape_string ($this->My_imap_mime_header_decode($overview->from));
			$this->to      = mysql_escape_string ($this->My_imap_mime_header_decode($overview->to));
			$date          = $overview->date;
			$stamp         = strtotime ($date);
			//echo $stamp."<br>";
            if ($stamp > 0)
				$this->date    = date ("Y-m-d H:i", $stamp);
    		else
            	$this->date    = date ("Y-m-d H:i", 0);
			$this->size    = ceil ($overview->size / 1024);
			$this->myUID   = md5 ($account_id.$this->subject.$this->from.$this->date.$this->size);

			return $this->myUID;
		}

		function parse_stream_rek ($mimeobj, $msgnr, $depth, $section) {

			if (!isset ($mimeobj->parts)) return;

	        for($x = 0; $x < count($mimeobj->parts); $x++) {
				if($section == 0) $nsection = $x + 1;
               	else if(($pos = strrpos($section, ".")) && $mimeobj->parts[0]->type != TYPEMULTIPART)
                       $nsection = substr($section, 0, $pos) . "." . ($x + 1);
               	else
                     $nsection = $section;

				if(isset($mimeobj->parts[$x]->parts) && count($mimeobj->parts[$x]->parts)) {
					if(!($mimeobj->parts[$x]->type == TYPEMESSAGE && $mimeobj->parts[$x]->parts[0]->type == TYPEMULTIPART))
                		$nsection .= ".0";
                	else
                		$nsection .= "";
            	}

            	$dummy = imap_fetchbody ($this->imap_stream, $msgnr, $nsection);
            	if (strlen($dummy) > 0) {
					$this->body[$this->counter]['content']     = $dummy;
					$this->body[$this->counter]['bodytype']    = $mimeobj->parts[$x]->type;
					$this->body[$this->counter]['encoding']    = $mimeobj->parts[$x]->encoding;
					$this->body[$this->counter]['subtype']     = $mimeobj->parts[$x]->subtype;
					(isset($mimeobj->parts[$x]->description)) ?
						$this->body[$this->counter]['description'] = $mimeobj->parts[$x]->description :
						$this->body[$this->counter]['description'] = "";

					//$this->body[$this->counter]['disposition'] = $mimeobj->parts[$x]->disposition;
					(isset($mimeobj->parts[$x]->disposition)) ?
						$this->body[$this->counter]['disposition'] = $mimeobj->parts[$x]->disposition :
						$this->body[$this->counter]['disposition'] = "";
					$this->body[$this->counter]['parameters']  = $mimeobj->parts[$x]->parameters;
					//$this->body[$this->counter]['dparameters'] = $mimeobj->parts[$x]->dparameters;
					(isset($mimeobj->parts[$x]->dparameters)) ?
						$this->body[$this->counter]['dparameters'] = $mimeobj->parts[$x]->dparameters :
						$this->body[$this->counter]['dparameters'] = "";
					$this->body[$this->counter]['filename']    = $this->get_filename_from_parameters ($mimeobj->parts[$x]);
					if ($this->body[$this->counter]['filename'] == "")
						$this->body[$this->counter]['filename'] = "Multipart ".$x;

	            	$this->body[$this->counter]['parse_result'] = "";
					$this->body[$this->counter]['parse_result'] .= "bodytype:      ".$this->map_primary_body_type($this->body[$this->counter]['bodytype'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "Encoding:      ".$this->map_encoding($this->body[$this->counter]['encoding'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "subtype:       ".$this->body[$this->counter]['subtype']."<br>";
					$this->body[$this->counter]['parse_result'] .= "description:   ".$this->body[$this->counter]['description']."<br>";
					$this->body[$this->counter]['parse_result'] .= "disposition:   ".$this->body[$this->counter]['disposition']."<br>";
					$this->body[$this->counter]['parse_result'] .= "parameters:    ".$this->svar_dump($this->body[$this->counter]['parameters'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "dparameters:   ".$this->svar_dump($this->body[$this->counter]['dparameters'])."<br>";

	            	$this->counter++;
				}
				$this->parse_stream_rek ($mimeobj->parts[$x], $msgnr, $depth + 1, $nsection);
			}
		}

		function parse_stream ($conn, $msgnr, $header, $structure) {

			$this->imap_stream = $conn;
			$this->header      = $header;
			$this->structure   = $structure;
			$this->counter     = 1;
			//echo $this->structure->type."+++".TYPEMULTIPART;
			if ($this->structure->type <> TYPEMULTIPART) {
				$dummy = imap_fetchbody ($this->imap_stream, $msgnr, 1);
				$this->body[$this->counter]['content']     = $dummy;
				$this->body[$this->counter]['bodytype']    = $this->structure->type;
				$this->body[$this->counter]['encoding']    = $this->structure->encoding;
				$this->body[$this->counter]['subtype']     = $this->structure->subtype;
				(isset($this->structure->description)) ?
					$this->body[$this->counter]['description'] = $this->structure->description :
					$this->body[$this->counter]['description'] = "";
				(isset($this->structure->disposition)) ?
					$this->body[$this->counter]['disposition'] = $this->structure->disposition :
					$this->body[$this->counter]['disposition'] = "";

				$this->body[$this->counter]['parameters']  = $this->structure->parameters;

				//$this->body[$this->counter]['dparameters'] = $this->structure->dparameters;
				(isset($this->structure->dparameters)) ?
					$this->body[$this->counter]['dparameters'] = $this->structure->dparameters :
					$this->body[$this->counter]['dparameters'] = "";

				$this->body[$this->counter]['parse_result'] = "";
				$this->body[$this->counter]['parse_result'] .= "bodytype:      ".$this->map_primary_body_type($this->body[$this->counter]['bodytype'])."<br>";
				$this->body[$this->counter]['parse_result'] .= "Encoding:      ".$this->map_encoding($this->body[$this->counter]['encoding'])."<br>";
				$this->body[$this->counter]['parse_result'] .= "subtype:       ".$this->body[$this->counter]['subtype']."<br>";
				$this->body[$this->counter]['parse_result'] .= "description:   ".$this->body[$this->counter]['description']."<br>";
				$this->body[$this->counter]['parse_result'] .= "disposition:   ".$this->body[$this->counter]['disposition']."<br>";
				$this->body[$this->counter]['parse_result'] .= "parameters:    ".$this->svar_dump($this->body[$this->counter]['parameters'])."<br>";
				$this->body[$this->counter]['parse_result'] .= "dparameters:   ".$this->svar_dump($this->body[$this->counter]['dparameters'])."<br>";

				$this->counter++;
			}


			$this->parse_stream_rek ($this->structure, $msgnr, 0,0);
		}

		function parse_stream_and_set_body ($conn, $msgnr, $header, $structure, $body) {

			$this->imap_stream = $conn;
			$this->header      = $header;
			$this->structure   = $structure;
			$this->counter     = 1;

			$this->body[$this->counter]['content']     = $body;
			$this->body[$this->counter]['bodytype']    = $this->structure->type;
			$this->body[$this->counter]['encoding']    = $this->structure->encoding;
			$this->body[$this->counter]['subtype']     = $this->structure->subtype;
			$this->body[$this->counter]['description'] = $this->structure->description;
			$this->body[$this->counter]['disposition'] = $this->structure->disposition;
			$this->body[$this->counter]['parameters']  = $this->structure->parameters;
			$this->body[$this->counter]['dparameters'] = $this->structure->dparameters;

			$this->body[$this->counter]['parse_result'] = "";
			$this->body[$this->counter]['parse_result'] .= "bodytype:      ".$this->map_primary_body_type($this->body[$this->counter]['bodytype'])."<br>";
			$this->body[$this->counter]['parse_result'] .= "Encoding:      ".$this->map_encoding($this->body[$this->counter]['encoding'])."<br>";
			$this->body[$this->counter]['parse_result'] .= "subtype:       ".$this->body[$this->counter]['subtype']."<br>";
			$this->body[$this->counter]['parse_result'] .= "description:   ".$this->body[$this->counter]['description']."<br>";
			$this->body[$this->counter]['parse_result'] .= "disposition:   ".$this->body[$this->counter]['disposition']."<br>";
			$this->body[$this->counter]['parse_result'] .= "parameters:    ".$this->svar_dump($this->body[$this->counter]['parameters'])."<br>";
			$this->body[$this->counter]['parse_result'] .= "dparameters:   ".$this->svar_dump($this->body[$this->counter]['dparameters'])."<br>";

			$this->counter++;
		}

		function insert_into_db ($account_id, $use_folder, $use_talk_id) {

			$this->folder = $use_folder;

			for ($i=1; $i <= count( $this->body ); $i++) {

				switch ($this->body[$i]['bodytype']) {
					case TYPETEXT:
						break;
					case TYPEMULTIPART:
						break;
					case TYPEMESSAGE: 	  break;
					case TYPEAPPLICATION: break;
					case TYPEAUDIO: 	  break;
					case TYPEIMAGE: 	  break;
					case TYPEVIDEO: 	  break;
					case TYPEMODEL: 	  break;
					default: break;
				}

				if ($i==1) $master_id = 0;
				$size = $this->size;
				if ($i > 1) {
					$size=0;
					$use_talk_id = 0;
				}
				$checked_subject = $this->subject;
				if (strlen ($checked_subject) > 60) $checked_subject = substr ($checked_subject,0,57)."...";
				$checked_subject = mysql_escape_string($checked_subject);
				$attachment = "false";
				if (count( $this->body ) > 1) $attachment = "true";

				if (!isset($this->body[$i]['filename'])) $this->body[$i]['filename'] = "";

				$query = "INSERT INTO emails
									(owner,       master_id, grp,   group_read,
									 unique_id,   contact_id,	    folder,
									 von,         an,	            datum,
									 betreff,	  header,           body,
									 groesse,     added_to_talks,   new,
									 konto, prim_body_type, parse_result,
									 filename, attachment, subtype) VALUES
									('".$this->user_id."', '$master_id', '".$this->group."',          'false',
									 '".$this->myUID."',   '0',               '$this->folder',
									 '".$this->from."',    '".$this->to."',   '".$this->date."',
									 '".$checked_subject."', '".mysql_escape_string($this->header)."', 		null,
									 '".$size."',    '".$use_talk_id."',			  	'true',
									 '$account_id','".$this->body[$i]['bodytype']."',
									 '".mysql_escape_string($this->body[$i]['parse_result'])."',
									 '".$this->body[$i]['filename']."', '$attachment',
									 '".$this->body[$i]['subtype']."')";

				$res	 = mysql_query ($query);
				/*if (mysql_error () <> "") {
					echo "LINE: ".__LINE__."<br><br>";
					echo mysql_error();
					echo "<br><br>".$query;
				}*/
				logDBError (__FILE__, __LINE__, mysql_error());
				if (mysql_error() != "")
					logMsg ($query);

				if ($i == 1)
					$master_id = mysql_insert_id();
				$this->body[$i]['new_id']   = mysql_insert_id();
				// Update talks...
				if ($use_talk_id > 0) {
					mysql_query ("UPDATE talks SET mail_id='".mysql_insert_id()."' WHERE talk_id='$use_talk_id'");
                	logDBError (__FILE__, __LINE__, mysql_error());
				}
			}
		}

		function insert_into_filesystem () {
			global $system_path_to_email_folder;

			for ($i=1; $i <= count( $this->body ); $i++) {

				switch ($this->body[$i]['encoding']) {
					case ENC7BIT:     	  	 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					case ENC8BIT:   		 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					case ENCBINARY: 	  	 $this->body[$i]['emailtext'] = $this->body[$i]['content']	; break;
					case ENCBASE64: 		 $this->body[$i]['emailtext'] = imap_base64 ($this->body[$i]['content']); break;
					case ENCQUOTEDPRINTABLE: $this->body[$i]['emailtext'] = imap_qprint ($this->body[$i]['content']); break;
					case ENCOTHER:			 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					default: $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
				}

				switch ($this->body[$i]['bodytype']) {
					case TYPETEXT:
						if ($this->body[$i]['subtype'] == "HTML") 	    break;
						if ($this->is_html($this->body[$i]['content'])) break; // als plain deklariert, aber doch html?
						//$this->body[$i]['emailtext'] = $this->default_font.str_replace ("\n", "<br>\n", $this->body[$i]['emailtext'])."</font>";
						$this->body[$i]['emailtext'] = preg_replace ("'http:\/\/[^ ^\n]*'i", "<a href='\\0' target='new'><font color='#333366'>\\0</font></a>",  $this->body[$i]['emailtext']);
						$this->body[$i]['emailtext'] = preg_replace ("'(?<!http:\/\/)(www\.[^ ^\n^\( ^\)]*)'i", "<a href='http://\\1' target='new'><font color='#333366'>\\1</font></a>", $this->body[$i]['emailtext']);

						break;
					case TYPEMULTIPART:
						if (($this->body[$i]['subtype'] == "ALTERNATIVE") AND (!$this->is_html ($this->body[$i]['emailtext']))) {
							$this->body[$i]['emailtext'] = $this->default_font.str_replace ("\n", "<br>\n", $this->body[$i]['emailtext'])."</font>";
						}
						break;
					case TYPEMESSAGE: 	  break;
					case TYPEAPPLICATION: break;
					case TYPEAUDIO: 	  break;
					case TYPEIMAGE: 	  break;
					case TYPEVIDEO: 	  break;
					case TYPEMODEL: 	  break;
					default: $this->body[$i]['emailtext'] = $default_font."Unbekannter Typ</font>";
				}



				$maildir = $system_path_to_email_folder."/".$this->user_id;
				@mkdir ($maildir, 0700);
				$maildir = $system_path_to_email_folder."/".$this->user_id."/".$this->folder;
				@mkdir ($maildir, 0700);
				$mailfile = $maildir."/".$this->body[$i]['new_id'];
				$fh = fopen ($mailfile, "w");
				fwrite ($fh, $this->body[$i]['emailtext']);
				fclose ($fh);
				
				/* Spamassassin Test */
				if (defined ('SPAMASSASSIN_PATH') &&
				    strlen (SPAMASSASSIN_PATH) > 0 &&
				    $_SESSION['user_id'] == 2) {
				    echo "<!--Testing for spamassasin-->";    
				    //$command = "echo $mailfile | ".SPAMASSASSIN_PATH." -D rulesrun=255 >> $mailfile";
				    $command = "echo $mailfile | ".SPAMASSASSIN_PATH." >> $mailfile";
				    //echo $command;
				    exec ($command);
                }
			}
		}

		function set_user ($user_id) {
			$this->user_id = $user_id;
		}

		function set_group ($group) {
			$this->group = $group;
		}

		function get_From () {
			return $this->from;
		}

		function get_Subject() {
			return $this->subject;
		}

		function map_primary_body_type ($id) {
			$ret_str = "";
			switch ($id) {
				case TYPETEXT:     	  	$ret_str = "Text"; break;
				case TYPEMULTIPART:   	$ret_str = "Multipart"; break;
				case TYPEMESSAGE: 	  	$ret_str = "Message"; break;
				case TYPEAPPLICATION: 	$ret_str = "Application"; break;
				case TYPEAUDIO:			$ret_str = "Audio"; break;
				case TYPEIMAGE: 	    $ret_str = "Image"; break;
				case TYPEVIDEO: 	   	$ret_str = "Video"; break;
				case TYPEMODEL: 	    $ret_str = "Model"; break;
				default: $ret_str = "Unbekannt";
			}
			return $ret_str;
		}

		function map_encoding ($id) {
			$ret_str = "";
			switch ($id) {
				case ENC7BIT:     	  	 $ret_str = "7bit"; break;
				case ENC8BIT:   		 $ret_str = "8bit"; break;
				case ENCBINARY: 	  	 $ret_str = "binary"; break;
				case ENCBASE64: 		 $ret_str = "base64"; break;
				case ENCQUOTEDPRINTABLE: $ret_str = "quoted printable"; break;
				case ENCOTHER:			 $ret_str = "andere"; break;
				default: $ret_str = "Unbekannt";
			}
			return $ret_str;
		}

		function reset () {
			$this->imap_stream = null;
			$this->header  = "";
			$this->structure = null;
			$this->body = array ();
			$this->subject = "";
			$this->from    = "";
			$this->to      = "";
			$this->date    = "";
			$this->size    = 0;
			$this->myUID   = "";
			$this->folder  = 1;

		}



		function My_imap_mime_header_decode ($text) {
			$tmp     = imap_mime_header_decode($text);
			$retstr  = "";
			for($q=0;$q<count($tmp);$q++) {
				$retstr    .= $tmp[$q]->text;
			}
			return $retstr;
		}

		function insert_sub_into_filesystem () {
			global $system_path_to_email_folder;

			for ($i=1; $i <= count( $this->body ); $i++) {


				$maildir = $system_path_to_email_folder."/".$this->user_id;
				@mkdir ($maildir, 0700);
				$maildir = $system_path_to_email_folder."/".$this->user_id."/".$this->folder;
				@mkdir ($maildir, 0700);
				$mailfile = $maildir."/".$this->body[$i]['new_id'];

				$fh = fopen ($mailfile, "w");
				fwrite ($fh, $this->body[$i]['content']);
				fclose ($fh);
			}
		}
		function svar_dump($data) {
			ob_start();
 			var_dump($data);
 			$ret_val = ob_get_contents();
			ob_end_clean();
 			return $ret_val;
		}

		function escapeBadHTML($str) {
  			$allowed = "br|b|i|p|u|a|http|mailto";
  			$str = preg_replace("/<((?!\/?($allowed)\b)[^>]*)>/xis", "", $str);
  			return $str;
		}

		function is_html ($text) {
			if (strlen ($this->escapeBadHTML($text)) < strlen ($text)) {
				if (strlen (str_replace("<html>","",$text)) < strlen ($text)) { // ist ein <html> - tag vorhanden?
					return true;
				}
			}
			return false;
		}

		function get_filename_from_parameters ($mimeobj) {
			$ret_str = "";
			for ($i=0; $i < count ($mimeobj->parameters); $i++) {
				if ($mimeobj->parameters[$i]->attribute == "NAME")
					return $mimeobj->parameters[$i]->value;
			}
			if (!isset ($mimeobj->dparameters)) return "";
			for ($i=0; $i < count ($mimeobj->dparameters); $i++) {
				if ($mimeobj->parameters[$i]->attribute == "FILENAME")
					return $mimeobj->parameters[$i]->value;
			}
			return "";
		}


}



?>