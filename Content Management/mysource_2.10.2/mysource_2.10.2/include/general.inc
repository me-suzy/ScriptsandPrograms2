<?  ##############################################
   ### MySource ------------------------------###
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
## File: include/general.inc
## Desc: General misc everyday handy non-specific functions
## $Source: /home/cvsroot/mysource/include/general.inc,v $
## $Revision: 2.27.2.3 $
## $Author: tbarrett $
## $Date: 2004/05/13 01:41:41 $
#######################################################################

#---------------------------------------------------------------------#


/**
* Function to print an error message 
*
* If we are viewing the frontend, the error is hidden in
* the source for the page and an email is sent to the
* adminstrator
*
* @param string file	The file the error occured in
* @param string line	The line the error occured on
* @param string message The error message you want displayed
*
* @returns void
* @access public
*/
function report_error($file='', $line='', $message='') {
	# Strip out the file path begining
	global $SYSTEM_ROOT, $IN_BACKEND;
	$file = str_replace($SYSTEM_ROOT, '', $file);
	$message = str_replace($SYSTEM_ROOT, '', $message);
	if (!$IN_BACKEND) {
		global $REPORTING_ERROR;

		if ($REPORTING_ERROR) return;
		$REPORTING_ERROR = true;

		$url = $_SERVER['HTTP_HOST'].str_replace('index.php', '', $_SERVER['PHP_SELF']);
		$date = date('F j, Y, g:i a');
		$msg = "MYSOURCE ERROR:\nDATE-TIME: $date\nURL: $url\nFILE: $file     LINE: $line\n$message\nEND ERROR";
		$msg .= "\nGet Contents:  " . array_contents($_GET) . "\n";
		$msg .= "\nPost Contents: " . array_contents($_POST) . "\n";
		echo "\n\n<!-- $msg -->\n\n";

		global $INCLUDE_PATH, $CACHE_PATH;
		include_once("$INCLUDE_PATH/config.inc");
		$conf = &get_config('MySource');
		$to_email = $conf->errors_email;
		if ($to_email == '') {
			# so we will use the mysource web master email
			$to_email = $config->webmaster_email;
		}
		if (trim($to_email) != '') {
			$md5 = md5($message);
			// We are probablly going to send an email
			// Have we already sent one recently about this ?
			$cachefile = "$CACHE_PATH/error_$md5";
			$count = 1;
			if (file_exists($cachefile)) {
				// We have sent a file, how recent was it ?
				$last_modified = filemtime($cachefile);
				$now = date("U");
				// The file has been modified in the last ERR_CUTOFF_TIME seconds, increment the counter
				if (($now - $last_modified) <= ERR_CUTOFF_TIME) {
					$fp = fopen($cachefile, "r");
					if ($fp) {
						$count = fgets($fp, 1024);
						fclose($fp);
						$count = (int) $count + 1; // Make sure the count is really a number
					}
				}
			}

			// Write the count back out to the file
			$fp = fopen($cachefile, "w+");
			if ($fp) {
				fwrite($fp, "$count", strlen("$count"));
				fclose($fp);
			}

			// If the count is one of the ones we email for, send the email
			// What the if conditon should do is send an email for every power of 2 up to ERR_MAX_GROUP_SIZE
			// Then once it hits ERR_MAX_GROUP_SIZE send an email every ERR_MAX_GROUP_SIZE messages
			if ((strpos(sprintf("%b", $count), "0") === false) || ($count % ERR_MAX_GROUP_SIZE == ERR_MAX_GROUP_SIZE - 1)) {
				mail($to_email, 'MySource Error', $msg . ' has been generated ' . $count .' times within the last '.ERR_CUTOFF_TIME.' seconds', "From: errors@".$_SERVER['HTTP_HOST']);
				// Cleanup any errors which havn't been sent in 10 minutes
				$d = dir($CACHE_PATH);
				while (false !== ($entry = $d->read())) {
					// If the file is an error file
					if (substr($entry, 0, 6) == 'error_') {
						// If it hasn't been modified in ERR_CUTOFF_TIME * 2 seconds
						if (($now - filemtime("$CACHE_PATH/$entry")) > ERR_CUTOFF_TIME * 2) {
							unlink("$CACHE_PATH/$entry");
						}
					}

				}
				$d->close();
			}
		}
		$REPORTING_ERROR = false;
		return;
	}

	?>
	</script></table></table></table></table><br>
	<table bgcolor=#ffffff cellspacing=0 border=1 bordercolor=#ff0000>
		<tr>
			<td bgcolor=#993333>
				<font face=arial color=white size=-1>M<small>Y</small>S<small>OURCE</small> E<small>RROR</small>
			</td>
		</tr>
		<tr>
			<td>
				<table bgcolor=#dddddd cellpadding=2 cellspacing=0 border=0>
					<tr>
						<td><font face=arial color=red size=-1><b>File:</b></td>
						<td><font face=courier size=-1><?=$file?></td>
						<td align=right><font face=arial color=red size=-1><b>Line: </b><font face=courier color=black size=-1><?=$line?></td>
					</tr>
					<tr>
						<td valign=top><font face=arial color=red size=-1><b>Msg:</b></td>
						<td colspan=2 bgcolor=#eeeeee><font face=courier size=-1><?=$message?></td>
					</tr>
				</table>
			</td>
		</tr>
	</table><br>
	<?
}


 ############################################################
# Pipes a file through, hopefully with the correct mime/type
function send_cacheable_file($path='', $alt_filename='', $alt_mimetype='', $allow_relative_backpaths = false) {
	if(!$allow_relative_backpaths) { # This can be set to true, developer MUST ensure no user-inputted data is used in the pathname
		$path = ereg_replace("^\.\.\/|\/\.\.\/",'',$path);
	}
	if(!file_exists($path)) {
		report_error(__FILE__,__LINE__, "Unable to send file, file doesn't exist: $path");
		return;
	}
	$file_types_config = &get_config("File_Types");
	$file = substr(strrchr($path,"/"),1);
	if ($alt_mimetype) {
		$type = $alt_mimetype;
	} elseif (!$type = &$file_types_config->mime_types[get_file_type($file)]) {
		$type = "text/plain";
	}
	$system_config = &get_system_config();
	$expires = (($system_config->max_idle_time)?$system_config->max_idle_time:86400); # Give 'em a day
	header('Cache-Control:');
	header('Pragma: cache');
	header('Expires: '. gmdate('D, d M Y H:i:s',time()+$expires) . ' GMT');
	header('Content-Type: ' . $type);
	header('Content-Disposition: inline; filename='.(($alt_filename)?$alt_filename:$file).';');
	header('Last-Modified: ' . gmdate('D, d M Y H:i:s',filemtime($path)) . ' GMT');
	header('Content-Length: '.filesize($path));
	readfile($path);
}


 ###############################################
# Allows external code to check whether the user
# has turned the jukebox on or off
# Requires the session to be started
function mysource_jukebox() {
	$session = &get_mysource_session();
	if($_REQUEST['mysource_action'] == 'jukebox') {
		if($session->get_var('jukebox')) {
			$session->unset_var('jukebox');
		} else {
			$session->set_var('jukebox',$mysource_action);
		}
	}
	return ($session->get_var('jukebox') == 'jukebox');
}

 ########################################################
# Returns some mysource data for debugging purposes.
# standard is to start name with mysource_ and serialized values
# with mysource_serialized.
function &get_mysource_debug() {
	global $XTRAS;
	$system_config = &get_system_config();
	$web_system = &get_web_system();
	$mysource_debug = array('mysource_version'=>MYSOURCE_VERSION,
		'mysource_name'=>MYSOURCE_NAME,
		'mysource_long_name'=>MYSOURCE_LONG_NAME,
		'mysource_url'=>MYSOURCE_URL,
		'mysource_required_php_version'=>MYSOURCE_REQUIRED_PHP_VERSION,
		'mysource_php_version'=>phpversion(),
		'mysource_php_mysql_support'=>((function_exists(mysql))?'True':'False'),
		'mysource_required_mysql_version'=>MYSOURCE_REQUIRED_MYSQL_VERSION,
		'mysource_mysql_version'=>$web_system->db->server_version(),
		'mysource_serialized_xtras'=>serialize($XTRAS->register),
		'mysource_serialized_system_config'=>serialize($system_config)
	);
	return $mysource_debug;
}

function print_headers($headers) {
	foreach($headers as $name => $value) {
		# Validate header name and value here ( see http rfc)
		header("$name: $value");
	}
}

 ##################################################################
# Returns the prefix for making a HREF to the web directory
$WEB_URL  = '';
$WEB_HREF = '';
function web_href($src='', $start_query_string=false) {
	global $IN_BACKEND;
	if($GLOBALS['ABSOLUTE']) {
		global $WEB_URL;
		$result = &$WEB_URL;
	} else {
		global $WEB_HREF;
		$result = &$WEB_HREF;
	}
	if (!$result) {
		if ($IN_BACKEND) {
			# We make an assumption here that the edit directory is 
			# always going to be one directory above the web directory
			if($GLOBALS['ABSOLUTE']) {
				$result = url_protocol().'://'.$_SERVER['HTTP_HOST'].dirname(dirname($_SERVER['PHP_SELF']));
			} else {
				$result = dirname(dirname($_SERVER['PHP_SELF']));
			}
		} else {
			$web = &get_web_system();
			$site = &$web->get_site();

			# if the web system can't give us the site, get the first site which has a URL
 			if(!$site->id) {
 				$db = &$web->get_db();
 				$site = &$web->get_site($db->single_element("SELECT siteid FROM site_url"));
 			}

			# if no urls, just get the first site there is, which may cause problems
			if(!$site->id) {
				$db = &$web->get_db();
				$site = &$web->get_site($db->single_element("SELECT siteid FROM site"));
			}
			if($site->id) {
				if($GLOBALS['ABSOLUTE']) {
					$result = $site->get_url();
				} else {
					$result = $site->get_href();
				}
			} else {
				if($GLOBALS['ABSOLUTE']) {
					$result = url_protocol().'://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
				} else {
					$result = dirname($_SERVER['PHP_SELF']);
				}
			}
		}#end if IN_BACKEND
		$result.= ((substr($result, -1) != "/") ? "/" : "");
	}#end if

	if ($start_query_string) {
		# if there is a question mark then append an '&' otherwise append a '?'
		# also if the last char is a slash don't need to add another one
		$href = $result;
		$href .= (strchr($result, "?")) ? "&" : ((substr($result, -1) == "/") ? "" : "/")."?";
	} else {
		$href = &$result;
	}#end if
	return $href.$src;

}#end web_href()

 ####################################################
# Returns the prefix for making a HREF to a lib file
$LIB_URL  = '';
$LIB_HREF = '';
function lib_href($filename='') {
	if($GLOBALS['ABSOLUTE']) {
		global $LIB_URL;
		$result = &$LIB_URL;
	} else {
		global $LIB_HREF;
		$result = &$LIB_HREF;
	}
	if (!$result) $result = web_href("__lib/");
	return $result.$filename;
}#end lib_href()

 #########################################################
# Returns the prefix for making a HREF to a squizlib file
$SQUIZLIB_URL  = '';
$SQUIZLIB_HREF = '';
function squizlib_href($squizlib_dir='', $filename='') {
	if($GLOBALS['ABSOLUTE']) {
		global $SQUIZLIB_URL;
		$result = &$SQUIZLIB_URL;
	} else {
		global $SQUIZLIB_HREF;
		$result = &$SQUIZLIB_HREF;
	}
	if (!$result) $result = web_href("__squizlib/");
	return $result."$squizlib_dir/$filename";
}#end squizlib_href()

 #################################################################
# Returns the prefix for making a HREF to the data directory file
$DATA_URL  = '';
$DATA_HREF = '';
function data_href($filename='', $web_href='') {
	if ($web_href) {
		return $web_href.((substr($web_href, -1) != '/') ? '/' : '').'__data/'.$filename;
	} else {
		if($GLOBALS['ABSOLUTE']) {
			global $DATA_URL;
			if (!$DATA_URL) $DATA_URL = web_href('__data/','',true);
			return $DATA_URL.$filename;
		} else {
			global $DATA_HREF;
			if (!$DATA_HREF) $DATA_HREF = web_href('__data/');
			return $DATA_HREF.$filename;
		}
	}
} #end data_href()

/**
* restricts or unrestricts a directory in the data path
*
* @param boolean unrestricted Whether the directory is public or restricted access
* @param string dir	The directory you want to restrict
*
* @returns boolean
* @access public
*/
function restrict_data_path($unrestricted=false, $dir='') {

	$from_dir = get_data_path(!$unrestricted, $dir);
	$to_dir   = get_data_path($unrestricted,  $dir);

	# if the from dir doesn't exist, but the to dir does, then assume that we don't need to do anything
	if (!is_dir($from_dir) && is_dir($to_dir)) {
		return true;
	}

	if (!is_dir($from_dir) && !is_dir($to_dir)) {
		# None exist? Create the to_dir
		if (create_directory($to_dir)) return true;
		report_error(__FILE__, __LINE__, "Unable to create '$to_dir'");
		return false;
	}

	if (is_dir($from_dir) && is_dir($to_dir)) {
		report_error(__FILE__, __LINE__, "Unable to move '$from_dir' to '$to_dir', both directories already exist.");
		return false;
	}

	if (is_dir($from_dir)) {
		# make sure that the parent of the to directory exists, other wise the rename will fail
		create_directory(dirname($to_dir));
		# try and rename from_dir to to_dir
		if (rename($from_dir, $to_dir)) {
			return true;
		} else {
			report_error(__FILE__, __LINE__, "Unable to move '$from_dir' to '$to_dir', rename failed");
			return false;
		}#end if
	}
} #end restrict_data_path()


/**
* Function to return a data path for a particular directory.
*
* @param boolean unrestricted Whether the directory is public or restricted access
* @param string dir	The directory you want the path to
*
* @returns string
* @access public
*/
function get_data_path($unrestricted=false, $dir='') {
	global $DATA_PATH;
	return $DATA_PATH.'/'.(($unrestricted) ? "un" : "").'restricted/'.$dir;
}#end get_data_path()

/**
* Function to return a static data path for a particular directory.
*
* @param string dir	The directory you want the path to
*
* @returns string
* @access public
*/
function get_static_data_path($dir='') {
	global $DATA_PATH;
	return $DATA_PATH.'/static/'.$dir;
}#end get_static_data_path()


/**
* Function to return the FTP data path
*
* @returns string
* @access public
*/
function get_ftp_data_path() {
	global $DATA_PATH;
	return $DATA_PATH.'/ftp';
}

/**
* Prints the status popup window.
*
* This is a nice little progress bar window you can call from your code when you need to do a heap 
* of processing that would otherwise time out. It also has the added benefit of letting the user
* know how much processing has been done. 
*
*<br>See country of origin and extract keywords wizards for examples. 
*
* @param int		$percent		Percentage (as a number) of processing that is complete.
* @param string		$process_url	URL to submit to 
* @param boolean	$start			Flag to indicate the start of processing (and the progress bar)
* @param boolean	$finish			Flag to indicate finishing the processing (and the progress bar)
* @param string		$title			Title that appears in the status bar of the popup window
* @param string		$status_message	Message to display underneath the progress bar (e.g. 1 of n pages complete)
* @returns string 
* @access public
*/ 
function status_popup($percent=0,$process_url='',$start=0,$finish=0,$colour='',$title='',$status_message='') {
	if ($percent > 100) $percent = 100;
	$lib_href = lib_href();
	if($start && $finish) {
		$s = "<script>popup = window.open('$process_url','status','toolbar=no,location=no,menubar=no,resizable=no,width=400,height=100,nominimize,nomaximize,norestore,scrollbars=no'); popup.document.write(\"<html><head><title>$title</title><script language=\\\"Javascript\\\">window.opener.location='$process_url';window.close();<\\/script></head><body bgcolor=#e0e0e0><table width=100%><tr><td><span style=\\\"font-size: 11px; color: #000000; font-family: verdana; font-weight: bold;\\\">100% completed</span></td></tr><tr bgcolor=$colour><td width=100% bgcolor=\\\"$colour\\\">&nbsp;</td></tr><tr><td align=right><span style=\\\"font-size: 10px; color: #000000; font-family: verdana; font-weight: bold;\\\">$status_message</span></td></tr></table></body></html>\");popup.focus();</script>";
	} elseif($start) {
		$s = "<script>popup = window.open('$process_url','status','toolbar=no,location=no,menubar=no,resizable=no,width=400,height=100,nominimize,nomaximize,norestore,scrollbars=no'); popup.focus();</script>";
	} elseif($finish) {
		$s = "<html><head><title>$title</title>".
			js_include($lib_href.'js/general.js').
			js_include($lib_href.'js/edit.js').
			js_include(squizlib_href('js','general.js')).
			js_include(squizlib_href('js','debug.js')).
			"<script language=\"Javascript\">".(($process_url) ? "window.opener.location='$process_url';" : '')."window.close();</script></head><body bgcolor=\"#e0e0e0\"><table width=\"100%\"><tr><td><span style=\"font-size: 11px; color: #000000; font-family: verdana; font-weight: bold;\">100% completed</span></td></tr><tr bgcolor=\"#ffffff\"><td width=\"100%\" bgcolor=\"$colour\">&nbsp;</td></tr><tr><td align=\"right\"><span style=\"font-size: 10px; color: #000000; font-family: verdana; font-weight: bold;\">$status_message</span></td></tr></table></body></html>";
	} elseif ($percent == 100) {
		$s = "<html><head><title>$title</title>".
			js_include($lib_href.'js/general.js').
			js_include($lib_href.'js/edit.js').
			js_include(squizlib_href('js','general.js')).
			js_include(squizlib_href('js','debug.js')).
			"</head><body bgcolor=\"#e0e0e0\"><table width=\"100%\"><tr><td><span style=\"font-size: 11px; color: #000000; font-family: verdana; font-weight: bold;\">$percent% completed</span></td></tr><tr bgcolor=\"#ffffff\"><td width=\"$percent%\" bgcolor=\"$colour\">&nbsp;</td></tr><tr><td align=\"right\"><span style=\"font-size: 10px; color: #000000; font-family: verdana; font-weight: bold;\">$status_message</span></td></tr></table><script type=\"text/javascript\" language=\"javascript\">function move() { window.location='$process_url'; }</script><script type=\"text/javascript\" language=\"javascript\">window.load = setTimeout( \"move()\", 600 );</script></body></html>";
	} else {
		$s = "<html><head><title>$title</title>".
			js_include($lib_href.'js/general.js').
			js_include($lib_href.'js/edit.js').
			js_include(squizlib_href('js','general.js')).
			js_include(squizlib_href('js','debug.js')).
			"</head><body bgcolor=\"#e0e0e0\"><table width=\"100%\"><tr><td colspan=\"2\"><span style=\"font-size: 11px; color: #000000; font-family: verdana; font-weight: bold;\">$percent% completed</span></td></tr><tr bgcolor=\"#ffffff\"><td width=\"$percent%\" bgcolor=\"$colour\">&nbsp;</td><td>&nbsp;</td></tr><tr><td align=\"right\" colspan=\"2\"><span style=\"font-size: 10px; color: #000000; font-family: verdana; font-weight: bold;\">$status_message</span></td></tr></table><script type=\"text/javascript\" language=\"javascript\">function move() { window.location='$process_url'; }</script><script type=\"text/javascript\" language=\"javascript\">window.load = setTimeout( \"move()\", 600 );</script></body></html>";
	}
	return $s;
}

/**
* Sets the global ABSOLUTE variable 
*
* @param boolean	$abs			Flag to indicate whether MySource should generate relative or absolute urls
* @returns boolean
* @access public
*/ 
function absolute_urls($abs) {
	if(gettype($abs) == 'boolean') $GLOBALS['ABSOLUTE'] = $abs;
	return $GLOBALS['ABSOLUTE'];
}
?>