<?php

// lamer protection
if (strpos($pivot_path,"ttp://")>0) { die('no'); }
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
if ($scriptname=="moblog_lib.php") { die('no'); }
if (!isset($_GET)) {
	$checkvars = array_merge($HTTP_GET_VARS , $HTTP_POST_VARS, $HTTP_SERVER_VARS, $HTTP_COOKIE_VARS);
} else {
	$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
}
if ( (isset($checkvars['basedir'])) || (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection

include_once($basedir.'/is_a.php');


if(!class_exists('pear')){
	require_once $basedir.'/pear.php';
}


require_once($basedir.'/socket.php');
include_once($basedir.'/pop3.php');
include_once($basedir.'/config.inc.php');
include_once($basedir.'/mimedecode.php');

define('__WEBLOG_ROOT', dirname(dirname(dirname(realpath(__FILE__)))));
define('__MOBLOG_ROOT', dirname(realpath(__FILE__)));


$pivot_path = __WEBLOG_ROOT."/pivot/";

include_once($pivot_path."pv_core.php");
include_once($pivot_path."pvlib.php");

$user= $moblog_cfg['pop_user'];
$pass= $moblog_cfg['pop_pass'];
$host= $moblog_cfg['pop_host'];
$port= ($moblog_cfg['pop_port']!='') ? $moblog_cfg['pop_port'] : "110";


$db = new db();



/**
 * Parse Email
 *
 */


function parse_email( $email ) {
	global $entry, $Cfg, $moblog_cfg, $filesdir;

	$params['include_bodies'] = TRUE;
	$params['decode_bodies']  = TRUE;
	$params['decode_headers'] = FALSE;


	$decode = new Mail_mimeDecode($email, "\r\n");
	$structure = $decode->decode($params);


	moblog_print("<h1>Headers</h1>");
	moblog_printr($structure->headers);


	moblog_print("Subject: ". $structure->headers['subject']);
	$entry['title'] = $structure->headers['subject'];


	// We check to see if we can figure out the name of the provider that sent the email.
	$entry['carrier'] = "all"; // default value..
	$fields = $structure->headers['from'].$structure->headers['x-return-path'].
				$structure->headers['x-mms-message-id'].$structure->headers['x-mailer'];
	foreach ($moblog_cfg['known_carriers'] as $temp_carrier) {
		if (strpos($fields, $temp_carrier)>0) {
			$entry['carrier'] = $temp_carrier;
		}
	}

	moblog_print("My carrier is: ".$entry['carrier']);

	if ($entry['carrier']=="all") {
		moblog_print("Fields: $fields");
	}

	$filesdir = __WEBLOG_ROOT."/".$Cfg['upload_path'];

	// get the replyaddress..
	if (isset($structure->headers['x-loop'])) {
		$replyaddress = "";
	} else {
		$replyto1 = $structure->headers['from'];
		$replyto2 = $structure->headers['return-path'];
		$replyaddress = (strlen($replyto2)<2) ? $replyto2 : $replyto1;
		//moblog_print("From: 1) $replyto1 - 2) $replyto2");
		//moblog_print("From: $replyaddress");
	}

	$entry['replyaddress'] = $replyaddress;

	// Parse silly encodings.. Only vodafone?
	// =?UTF-8?B?w6kgw6ggw68g4oKs?=
        // Comment by Hans Fr. Nordhaug:
        // FIXME - calling utf8_decode for e-mail encoded in iso-8859-1 is
        // plainly wrong. (See checks in parse_body).
	$entry['title'] = preg_replace("/=\?(.*)\?Q\?(.*)\?=/Ui", "\\2", $entry['title']);
	$entry['replyaddress'] = preg_replace("/=\?(.*)\?Q\?(.*)\?=/Ui", "\\2", $entry['replyaddress']);
	if (preg_match("/=\?(.*)\?B\?(.*)\?=/Ui", $entry['title'], $matches)) {
		$entry['title'] = str_replace($matches['0'], utf8_decode(base64_decode($matches[2])), $entry['title']);
	}
	if (preg_match("/=\?(.*)\?B\?(.*)\?=/Ui", $entry['replyaddress'], $matches)) {
		$entry['replyaddress'] = str_replace($matches['0'], utf8_decode(base64_decode($matches[2])), $entry['replyaddress']);
	}


	// for 'plain text' messages, parse the body.
	// parse_body($structure->body);
	parse_body($structure);

	// for mime mail, parse each part
	if ((isset($structure->parts)) && (is_array($structure->parts))) {

		foreach ($structure->parts as $part) {
			parse_parts($part);
		}

	}

}



function parse_parts($part) {
	global $entry, $filesdir;

	$temp_headers = array_merge( (array)$part->headers, (array)$part->ctype_parameters, (array)$part->d_parameters);

	moblog_print("<hr /><b>part: ".$part->ctype_primary ."</b>" );

	if (strtolower($part->ctype_primary) == "text") {

		parse_body($part);

	} else if (strtolower($part->ctype_primary) == "multipart") {

		foreach ($part->parts as $temp_part) {
			parse_parts($temp_part);
		}

	} else if ( (strtolower($part->ctype_primary) == "image")  ||
				(getextension($temp_headers['filename']) == "jpg") ||
				(getextension($temp_headers['filename']) == "jpeg") ||
				(getextension($temp_headers['filename']) == "png")  ) {

		parse_image($part);

	} else {

		parse_download($part);

	} // end if ($filename ... )


}





function compose_entry() {
	global $entry, $moblog_cfg, $db, $Pivot_Vars;

	if (strlen($entry['introduction'])>2) {

		// if so, save the new entry and generate files (if necessary)
		$entry['code']=">";
		$entry['date']= date('Y-m-d-H-i');

		if ( (!isset($entry['title'])) || ($entry['title']=="") ) {
			$entry['title'] = $moblog_cfg['title'];
		}
		if (!isset($entry['title'])) {
			$entry['status'] = $moblog_cfg['status'];
		}

		$entry['allow_comments'] = $moblog_cfg['allow_comments'];

		$entry['convert_lb'] = 0;

		$entry['user'] = $moblog_cfg['user'];


		//check for valid sender: $replyaddress must be in $moblog_cfg['allowed_senders']
		$allowed = false;
		foreach ($moblog_cfg['allowed_senders'] as $sender) {
			if ( (strlen($entry['replyaddress'])>2) && ( strpos(" ".$entry['replyaddress'], $sender) > 0 )) {
				$allowed = true;
			}
		}

		if ($allowed) {
			$entry['category'] = array ($moblog_cfg['category']);
			if(isset($entry['override_cat'])) {
				$entry['category'] = array ($entry['override_cat']);
			}
		} else {
			$entry['category'] = array ($moblog_cfg['spam_category']);
		}


		$entry = $db->set_entry($entry);
		$db->save_entry(true);

		serialize_uncache('ALL');

		make_filename($Pivot_Vars['piv_code'], $Pivot_Vars['piv_weblog'], 'message', $message);

		$msg = "Your entry has been posted! \n\n";
		$msg .= "title: ".$entry['title'];
		$msg .= "\nuser: ".$entry['user'];
		$msg .= "\ncat: ". implode("", $entry['category']);
		$msg .= "\nintroduction: ".$entry['introduction'];

		$msg_title = "[moblog] Success!";

	} else {

		$msg = "Not posted: Could not parse your entry\n\n";
		$msg .= "please report this to bob@mijnkopthee.nl , and refer to message #".date('mdHis');
		$msg_title = "[moblog] Not successful.";

	}


	// to wrap it up, send a confirmation by mail..
	$msg.= "\n\nprocessed: ". date("dS of F Y H:i:s")."\n";
	$add_header=sprintf("From: %s", $entry['replyaddress']."\n");
	$add_header=sprintf("x-loop: pivot-moblog\n");

	if (( $entry['replyaddress'] != "") && ($moblog_cfg['send_confirmation'])) {
		mail( $entry['replyaddress'], $msg_title, $msg, $add_header);
	}

	echo nl2br($msg);

	unset($db);

}




// ---- functions -----

function parse_body($part) {
	global $entry, $moblog_cfg;

	// Here we check the various 'skipcontent' rules, so we can easily skip mime parts we
	// don't need. (like gifs or ads that were added by the carrier)
	$temp_rules = array_merge( (array)$moblog_cfg['skipcontent']['all'], (array)$moblog_cfg['skipcontent'][$entry['carrier']] );
	$temp_headers = array_merge( (array)$part->headers, (array)$part->ctype_parameters, (array)$part->d_parameters);
	foreach ($temp_rules as $rule => $value) {
		if ((isset($temp_headers[$rule])) && ($temp_headers[$rule] == $value)) {
			moblog_print("We skip this part because rule '$rule' == '$value'");
			return "";
		}
	}


	moblog_print("Temp_headers:");
	moblog_printr($temp_headers);

	if (strtolower($temp_headers['content-transfer-encoding']) == "base64") {
		$part->body = base64_decode($part->body);
		moblog_print("un-base-64");
	}

	if (strtolower($temp_headers['content-transfer-encoding']) == "quoted-printable") {
		$part->body = quoted_printable_decode($part->body);
		moblog_print("un-quoted-printable");
	}


	if (is_string($part)) {
		// simple email body//
		$body = $part;
	} else {
		// multipart..
		$body = $part->body;
	}

	$body = preg_replace("/<style(.*)<\/style>/Usi", "", $body);
	$body = strip_tags($body, "<a><b><i><u><s>");

	moblog_print("Original body is: ". ($body));

        // Only convert boidy to UTF-8 if blog is running UTF-8 and the e-mail
        // isn't using UTF-8 as charset.
        if (strtolower(snippet_charset()) == "utf-8" &&
            strtolower($part->ctype_parameters['charset']) != "utf-8")
            $body = utf8_encode($body);

	//We try to find out where the line containing the title is at...
	if (preg_match("/^title:(.*)/mi", $body, $title)) {
		//And put the title var in the proper place
		$entry['title'] = trim($title[1]);
		echo "[title]";
	}

	//We repeat the same trick as above... for all vars wanted
	if (preg_match("/^user:(.*)/mi", $body, $user)) {
		$entry['user'] = trim($user[1]);
	}

	// in case ppl are lazy and use pass instead of password
	if (preg_match("/^pass:(.*)/mi", $body, $pass)) {
		$entry['pass'] = trim($pass[1]);
	} else if (preg_match("/^password:(.*)/mi", $body, $password)) {
		$entry['pass'] = trim($password[1]);
	}

	if (preg_match("/^publish:(.*)/mi", $body, $publish)) {
		if (trim($publish[1]) == "1") {
			$entry['status'] = 'publish';
		} else {
			$entry['status'] = 'hold';
		}
	}


	// in case ppl are lazy and use cat instead of category
	if (preg_match("/^cat:(.*)/mi", $body, $cat)) {
		$entry['category'] = trim($cat[1]);
	} else if (preg_match("/^category:(.*)/mi", $body, $category)) {
		$entry['category'] = trim($category[1]);
	}

	//We strip out all the lines we already used, and use what's left as the body
	@$body = str_replace ($title[0], "", $body);
	@$body = str_replace ($user[0], "", $body);
	@$body = str_replace ($pass[0], "", $body);
	@$body = str_replace ($password[0], "", $body);
	@$body = str_replace ($cat[0], "", $body);
	@$body = str_replace ($publish[0], "", $body);
	@$body = str_replace ($category[0], "", $body);
	@$body = str_replace ($pivot[0], "", $body);


	// strip off a standard .sig. Properly formed .sigs start with '-- ' on a new line.
	list($body, $sig) = explode("\n-- ", $body);

	$body = tidy(nl2br(trim(tidy($body))));

	moblog_print("Temp body is: $body");

	if (strlen($body)>strlen($entry['introduction'])) {
		$entry['introduction'] = $body;
	}
}



function parse_image($part) {
	global $entry, $moblog_cfg, $filesdir;

	// Here we check the various 'skipcontent' rules, so we can easily skip mime parts we
	// don't need. (like gifs or ads that were added by the carrier)
	$temp_rules = array_merge( (array)$moblog_cfg['skipcontent']['all'], (array)$moblog_cfg['skipcontent'][$entry['carrier']] );
	$temp_headers = array_merge( (array)$part->headers, (array)$part->ctype_parameters, (array)$part->d_parameters);
	foreach ($temp_rules as $rule => $value) {
		if (isset($temp_headers[$rule])) {
			if (is_array($value)) {
				if (in_array($temp_headers[$rule], $value)) {
					moblog_print("We skip this part because rule '$rule'");
					return "";
				}
			} else {
				if ($temp_headers[$rule] == $value) {
					moblog_print("We skip this part because rule '$rule' == '$value' ");
					return "";
				}
			}
		}
	}


	moblog_print("Temp_headers:");
	moblog_printr($temp_headers);


	if (isset($moblog_cfg['mime_cat'][ $part->ctype_primary ])) {
		$entry['override_cat'] = $part->ctype_primary;
	}

	// It's an image. We'll add all the images as an array to the entry..
	// get the original filename from the email..
	$filename = isset($part->ctype_parameters['name']) ? $part->ctype_parameters['name'] : $part->d_parameters['filename'];
	$filename = strtolower(safe_string($filename, false));

	$ext= getextension($filename);

	if ( ($filename !="") && ( ($ext=="jpg") || ($ext=="jpeg") ||  ($ext=="gif") ||  ($ext=="png") )) {

		$filename = safe_string($filename);
		$filename = str_replace(" ", "_", $filename);

		if ($ext=="jpeg") {
			$filename=str_replace(".jpeg", ".jpg", $filename);
			$ext = "jpg";
		}

		if (file_exists($filesdir.$filename)) {
			moblog_print("File $filename exists..");
			$filename = str_replace(".$ext", "", $filename);
			$filename = substr($filename, 0, 7)."_".date("Ymd-his").".".$ext;
		}


		$fp = fopen($filesdir.$filename, "wb");
		fwrite($fp, $part->body);
		fclose($fp);



		list ($mywidth, $myheight) = getimagesize($filesdir.$filename);

		if ( ($mywidth=="") && ($mywidth=="") ) {
			// Some mailers like pine, need content to get base64_decode'd
			$fp = fopen($filesdir.$filename, "wb");
			fwrite($fp, base64_decode($part->body));
			fclose($fp);
			list ($mywidth, $myheight) = getimagesize($filesdir.$filename);
		}

		if ( ($mywidth > $moblog_cfg['maxwidth']) || ($myheight > $moblog_cfg['maxheight'])) {

			$thumbfile = resize_image($filesdir.$filename, $maxwidth, $maxheight);

			if (strlen($entry['introduction'])>2) {
				$entry['introduction'] .="\n";
			}

			if (strlen($thumbfile)>2) {
				$entry['introduction'] .="\n[[popup:$filename:(thumbnail)::center:1]]\n";
			} else {
				$entry['introduction'] .="\n[[popup:$filename:".$moblog_cfg['click_for_image']."::center:1]]\n";
			}

		} else {

			$entry['introduction'] .= "\n[[image:$filename]]";

		}

	}

}



function parse_download($part) {
	global $entry, $filesdir;

	$temp_headers = array_merge( (array)$part->headers, (array)$part->ctype_parameters, (array)$part->d_parameters);

	moblog_print("Temp_headers:");
	moblog_printr($temp_headers);


	$filename = $temp_headers['filename'];
	$ext = getextension($filename);

	// Skip .smil files.
	if ($ext == "smil") {
		return;
	}

	$body = $part->body;

	// [[download:another.zip:icon:Download a zipfile:]]
	moblog_print("filename: ". $filename ." . $ext ");
	moblog_print("filesize: ". strlen($part->body));

	if (strlen($entry['introduction'])>2) {
			$entry['introduction'] .="\n&nbsp;\n";
	}

	/*
	if (strtolower($temp_headers['content-transfer-encoding']) == "base64") {
		$body = base64_decode($body);
		moblog_print("un-base-64");
	}
	*/

	$fp = fopen($filesdir.$filename, "wb");
	fwrite($fp, $body);
	fclose($fp);

	$entry['introduction'] .="[[download:$filename:icon:$filename:]]";


}


function tidy($text) {

	$text = str_replace("&nbsp;<br />", "", $text);
	$text = preg_replace("/([\n\r\t])+/is", "\n", $text);

	return ($text);
}



function resize_image($imagename) {
	global $local, $Cfg, $moblog_cfg;

	$ext = getextension($imagename);

	$thumbname = str_replace( $ext, "thumb.".$ext, $imagename);

	// echo "imagename = $imagename";

	$filename = ( $imagename );
	$thumbfilename = ( $thumbname );

	list($curwidth, $curheight) = getimagesize($filename);

	if ( ($curwidth>1201) || ($curheight>1201) || (!function_exists('ImageCreateFromJPEG')) ) {
		moblog_print("file to big to make thumbnail.");
		return "";
	}

	$factor = min( ($moblog_cfg['maxwidth'] / $curwidth) , ($moblog_cfg['maxheight'] / $curheight) );

	$dw		= $curwidth * $factor;
	$dh		= $curheight *  $factor;

	if ($ext == "jpg") { $src = ImageCreateFromJPEG($filename); }
	if ($ext == "png") { $src = ImageCreateFromPNG($filename); }

	if(function_exists('ImageCreateTrueColor')) {
		$dst = ImageCreateTrueColor($dw,$dh);
	} else {
		$dst = ImageCreate($dw,$dh);
	}

	ImageCopyResampled($dst,$src,0,0,0,0,$dw,$dh,$curwidth,$curheight);

	if($ext == "jpg") ImageJPEG($dst, $thumbfilename, $moblog_cfg['quality']);
	if($ext == "png") ImagePNG($dst, $thumbfilename, $moblog_cfg['quality']);

	ImageDestroy($dst);

	moblog_print("thumbfilename: $thumbfilename");

	return $thumbfilename;
}



function moblog_print($str) {
	global $moblog_cfg;

	if ($moblog_cfg['verbose']) {
		echo $str."<br />\n";
	}

}



function moblog_printr(&$var) {
	global $moblog_cfg;

	if ($moblog_cfg['verbose']) {
		echo "<pre>";
		print_r($var);
		echo "</pre>";
	}

}



if (!function_exists("ob_get_clean")) {
   function ob_get_clean() {
       $ob_contents = ob_get_contents();
       ob_end_clean();
       return $ob_contents;
   }
}

?>
