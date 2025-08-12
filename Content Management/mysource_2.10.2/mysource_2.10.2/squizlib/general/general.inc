<?  ##############################################
   ### SQUIZLIB ------------------------------###
  ##- General Include ---- PHP4 --------------##
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
## File: general/general.inc
## Desc: General misc everyday handy non-specific functions
## $Source: /home/cvsroot/squizlib/general/general.inc,v $
## $Revision: 2.48 $
## $Author: achadszinow $
## $Date: 2004/02/05 23:17:56 $
#######################################################################

#---------------------------------------------------------------------#
  
   #####################################################################
  # Strips the slashes from a variable if magic quotes is set for GPC
 # Handle normal variables and arrays 
#######################################################################

function gpc_stripslashes($var='') {
	if (get_magic_quotes_gpc()) {
		if (is_array($var)) {
			$var = stripslashes_array($var, true);
		} else {
			$var = stripslashes($var);
		}#end if
	}
	return $var;
}

  ########################################
 # Strips the slashes from an entire associative array
###############################################

function stripslashes_array(&$array, $strip_keys=false) {
	if(is_string($array)) return stripslashes($array);
	$keys_to_replace = Array();
	foreach($array as $key => $value) {
		if (is_string($value)) {
			$array[$key] = stripslashes($value);
		} elseif (is_array($value)) {
			stripslashes_array($array[$key], $strip_keys);
		}
		
		if ($strip_keys && $key != ($stripped_key = stripslashes($key))) {
			$keys_to_replace[$key] = $stripped_key;
		}
	}
	# now replace any of the keys that needed strip slashing
	foreach($keys_to_replace as $from => $to) {
		$array[$to]   = &$array[$from];
		unset($array[$from]);
	}
	return $array;
}


  ###################################################
 # Generates a random password from letters/digits
function random_password($length=8) {
	$consonants = 'bcdfghjklmnpqrstvwyz';
	$vowels     = 'aeiou';
	global $RANDOM_PASSWORD_SEED;
	while($length > 0) {
		$RANDOM_PASSWORD_SEED += $length;
		$seed = $RANDOM_PASSWORD_SEED + $length + ((int)(100000 * (double)microtime())) + time();
		srand($seed);
		$password .= $consonants[rand(0,strlen($consonants)-1)];
		if($length > 1) {
			srand($seed);
			$password .= $vowels[rand(0,strlen($vowels)-1)];
		}
		$length -= 2;
	}
	$RANDOM_PASSWORD_SEED++;
	return $password;
}

/**
* Returns a valid random login
*
* @param string $length The desired length. Defaults to 8 if not given else has a min of 2 if given
* @returns string
* @access public
*/
function random_username($length=8) {
	if ($length < 2) $length = 2;
	$letters = 'bcdfghjklmnpqrstvwyzaeiou0123456789';
	global $RANDOM_PASSWORD_SEED;
	$username == '';
	while($length > 0) {
		$RANDOM_PASSWORD_SEED += $length;
		$seed = $RANDOM_PASSWORD_SEED + $length + ((int)(100000 * (double)microtime())) + time();
		srand($seed);
		$username .= $letters[rand(0,strlen($letters)-1)];
		$length--;
	}
	$RANDOM_PASSWORD_SEED++;
	return $username;
}

  #################################################################################
 # Takes an int representing the number of bytes and returns a easy to read string
##########################################################################

function easy_filesize($size=0) {
	if ($size < 1500) {
		return "$size bytes";
	} elseif ($size < 1500000) {
		return sprintf("%.1f kb",$size/1024.0);
	} elseif ($size < 1500000000) {
		return sprintf("%.1f mb",($size/1024.0)/1024.0);
	} else {
		return sprintf("%.1f gb",(($size/1024.0)/1024.0)/1024.0);
	}
}

   #################################################################################
  # Takes a timestamp and returns a date/time string with detail varying
 # by how recent the date is
function easy_datetime($then='') {
	$now = time();
	if ($now - $then < 86400) { # A Day
		return ((date("H")>=date("H",$then))?"Today ":"Yesterday ") . date("h:ia",$then);
	} elseif ($now - $then < 604800) { # A week
		return date("D h:ia",$then);
	} else {
		return date("d M Y H:i",$then);
	}
}

   #################################################################################
  # Takes a timestamp and returns a date/time string with detail varying
 # by how recent the date is. The date/time is based on the locale.
function easy_locale_datetime($then='', $locale='c') {
	$now = time();
	$switch_back = false;
	if (trim($locale) != '' && strtolower($locale) != 'c') {
		setlocale(LC_TIME, array($locale, 'C'));
		$switch_back = true;
	}
	if ($now - $then < 86400) { # A Day
		$return_date = ((date("H")>=date("H",$then))?"Today ":"Yesterday ") . strftime("%I:%M%p",$then);
	} elseif ($now - $then < 604800) { # A week
		$return_date = strftime("%a %I:%M%p",$then);
	} else {
		$return_date = strftime("%d %b %Y %H:%M",$then);
	}
	if ($switch_back) setlocale(LC_TIME, 'C');
	return $return_date;
}

  ################################################################
 # Takes a timestamp and returns a human-friendly date/time string
function readable_datetime($then='') {
	return date("d M Y H:i",$then);
}


  ################################################################
 # Takes a timestamp and returns a human-friendly date/time string
 # based on the LOCALE passed in.
function readable_locale_datetime($then='', $locale='C') {
	$switch_back = false;
	if (trim($locale) != '' && strtolower($locale) != 'c') {
		setlocale(LC_TIME, array($locale, 'C'));
		$switch_back = true;
	}
	$return_date = strftime("%d %b %Y %H:%M",$then);
	if ($switch_back) setlocale(LC_TIME, 'C');
	return $return_date;
}

 ####################################################################
# Takes a string and looks for an integer at the end of it
# It tries to incrememnt this integer, if it can't find one, it appends "2"
# The option spacer only works if no int is already present - good for starting a trend
function increment_name($name='',$spacer='') {
	for($i = strlen($name) - 1; $i > 0; $i--) {
		if(!ereg("[^a-zA-Z0-9]",$name[$i])) break;
	} $trailing_whitespace = substr($name,$i+1);
	for($j = $i; $j > 0; $j--) {
		if(!ereg("[0-9]",$name[$j])) break;
	} $int = substr($name,$j+1,$i-$j);
	if(!$int) return substr($name,0,$j+1).$spacer.(2).$trailing_whitespace;
	return substr($name,0,$j+1).($int+1).$trailing_whitespace;
}

 ##############################################
# As above, but preserves a filename extension
function increment_filename($name='',$spacer='') {
	if(strpos($name,'.') !== false) {
		$ext = get_file_type($name);
		return increment_name(substr($name,0,-strlen($ext)-1),$spacer).'.'.$ext;
	} else return increment_name($name,$spacer="");
}


 ######################################################
# Takes two names of pages, categories, directories etc
# and decides whether they are similar enough to be the
# same name.. e.g "Bit & Pieces" = "bitspieces"
function fuzzy_name_compare($a='',$b='') {
	$a = ereg_replace("[^a-z0-9]+",'',ereg_replace("[^a-z0-9](and|or|of)[^a-z0-9]",'',strtolower($a)));
	$b = ereg_replace("[^a-z0-9]+",'',ereg_replace("[^a-z0-9](and|or|of)[^a-z0-9]",'',strtolower($b)));
	return $a==$b;
}

 ##############################################################
# Takes a two URLs and returns the relative HREF required to get
# you from the first URL to the second.
# The two given URLs MUST be absolute.
# protocol://domain/dir/dir2/file
function relative_href($from='',$to='') {
	$froms = split("/+",$from);
	$tos = split("/+",$to);
	$href = '';
	# Ensure the first element is the protocol
	if(!eregi("^[a-z]+\:$",$froms[0])) array_unshift($froms,"http:");
	if(!eregi("^[a-z]+\:$",$tos[0]  )) array_unshift($tos,"http:");
	# Different protocols or domains? ABSOLUTE HREF!
	if(strtolower($froms[0]) != strtolower($tos[0]) || strtolower($froms[1]) != strtolower($tos[1])) { 
		$tos[0] .= "/";
		$href = implode("/",$tos);
		return $href;
	}
	# Different first directories? Root path!
	if($froms[2] != $tos[2]) { 
		array_shift($tos); # Shift off protocol
		array_shift($tos); # Shift off domains
		return "/" . implode("/",$tos);
	}
	$split_point = 3; # Start from the second directory and find the place where the urls start to vary
	while($froms[$split_point] && $froms[$split_point] == $tos[$split_point]) $split_point++;
	for($i = count($froms); $i > $split_point; $i--) {
		$href .= "../";
	}
	#if(!$froms[$split_point]) $href .= $tos[$split_point-1]."/";
	# Forward to the destination
	for($i = $split_point - 1; $i < count($tos); $i++) {
		$href .= "{$tos[$i]}/";
	}
	$href = ereg_replace("/$","",$href); # Don't need one of these
	return $href;
}

 ############################################
# Converts a MySQL datetime to a timestamp
function mysql_to_timestamp($datetime='') {
	if (!list($d,$t) = explode(" ",$datetime)) $d = $datetime;
	list($y,$m,$d) = explode("-",$d);
	list($h,$i,$s) = explode(":",$t);
	return mktime($h,$i,$s,$m,$d,$y);
}


 ############################################
# Converts a MySQL datetime to a timestamp
function timestamp_to_mysql($time='') {
	return date('Y-m-d h:i:s',$time);
}


 ##################################################
# returns true if $array1 is identical to $array2
function equal_arrays($array1=array(),$array2=array()) {
	if(!(is_array($array1)||is_object($array1)) || !(is_array($array1)||is_object($array2))) {
		return $array1 == $array2;
	}
	reset($array1); reset($array2);
	while(1) {
		$s1 = each($array1); $s2 = each($array2);
		if($s1 === false &&  $s2 === false) return true;
		if($s1 === false xor $s2 === false) return false;
		list($k1, $v1) = $s1;
		list($k2, $v2) = $s2;
		if($k1 != $k2) return false;
		if(($t = gettype($v1)) != gettype($v2)) return false;
		switch($t) {
			case "array":
				if(!equal_arrays($v1,$v2)) return false;
				break;
			case "object":
				if(!get_class($v1) == get_class($v2)) return false;
				if(!equal_arrays($v1,$v2)) return false;
				break;
			default:
				if($v1 != $v2) return false;
			break;
		}
	}
	return true;
}


 ###################################################################################
# returns true if $array1 and $array2 have the same keys pointing to the same values
# Different from equal_arrays() in that the order of the keys in the arrays are not important
function equal_arrays_assoc($array1, $array2) {
	if(!(is_array($array1)||is_object($array1)) || !(is_array($array1)||is_object($array2))) {
		return $array1 == $array2;
	}

	if (count($array1) != count($array2)) return false;

	for(reset($array1); NULL !== ($k = key($array1)); next($array1)) {
		if (!array_key_exists($k, $array2)) return false;

		if(($t = gettype($array1[$k])) != gettype($array2[$k])) return false;

		switch($t) {
			case 'array':
				if(!equal_arrays_assoc($array1[$k], $array2[$k])) return false;
				break;
			case 'object':
				if(!get_class($array1[$k]) == get_class($array2[$k])) return false;
				if(!equal_arrays_assoc($array1[$k], $array2[$k])) return false;
				break;
			default:
				if($array1[$k] != $array2[$k]) return false;
		}#end switch
	}#end for
	return true;
}#end equal_arrays_assoc()



 ##################################################
# Returns 1, 0 or -1 depending on which array should
# be sorted "first"
function array_sort_compare($array1=array(),$array2=array()) {
	if(!(is_array($array1)||is_object($array1)) || !(is_array($array1)||is_object($array2))) {
		if((is_array($array1)||is_object($array1)) && !(is_array($array2)||is_object($array2))) return -1;
		if((is_array($array2)||is_object($array2)) && !(is_array($array1)||is_object($array1))) return 1;
		if(is_string($array1)||is_string($array2)) return strcmp($array1,$array2);
		if($array1 < $array2) return -1;
		if($array1 > $array2) return 1;
		return 0;
	}
	reset($array1); reset($array2);
	while(1) {
		$s1 = each($array1); $s2 = each($array2);
		if($s1 === false && $s2 === false) return  0;
		if($s1 === false && $s2 !== false) return  1;
		if($s1 !== false && $s2 === false) return -1;
		list($k1, $v1) = $s1;
		list($k2, $v2) = $s2;
		if(($r = array_sort_compare($v1,$v2)) != 0) {
			return $r;
		}
	}
	return 0;
}



 #############################################
#   remove all "" entries in a given array
function erase_blank_entries($array=array()) {
	if (!empty($array)) {
		foreach ($array as $key=>$value)  {
			if ($value == '')   {
				unset($array[$key]);
			}
		}
	}
	return $array;
}


 #################################################
# Similar to above, but works on what you sent it
function &array_clear_blanks(&$array) {
	if (!empty($array)) {
		foreach ($array as $key => $value) {
			if ($value == "")   {
				unset($array[$key]);
			}
		}
	}
	return $array;
}


 #####################################################
# Takes two plain arrays and compares their contents
# It returns two array - alist of elements missing from
# second array, and a list of those missing from the first
function array_compare(&$n,&$o) {
	if(!is_array($n)) $n = array();
	if(!is_array($o)) $o = array();
	$c = array_intersect($n,$o);
	return array(array_diff($n,$c),array_diff($o,$c));
}

   ##########################################################################
  # Merges 2 multi-dimensional arrays preserving the keys and returns
 # the merged array. If the same keys exist the values in array2 are used.
function multi_array_merge(&$array1, &$array2) {
	$array = $array1;
	if (!empty($array2)) {
		foreach($array2 as $key => $data) {
			if(is_array($data) && $array[$key]) {
				multi_array_merge($array[$key], $array2[$key]);
			} else {
				$array[$key]=$data;
			}
		}
	}
	return $array;
}

 #######################################################
# Searches an array for a value and if it finds it, 
# removes that element - only the first instance of it.
# Doesn't really work on associative arrays.
function array_remove_element($v='',&$a) {
	if (!is_array($a) || empty($a)) return;
	if(in_array($v,$a)) {
		array_splice($a,array_search($v,$a),1);
	}
}


 #######################################################
# Returns true if there are any common elements between
# two array, or false if there are none.
function array_any_common_elements(&$a1,&$a2) {
	if (!is_array($a1) || !is_array($a2)) return false;
	if (empty($a1) || empty($a2)) return false;
	reset($a1);
	while(list(,$v) = each($a1)) if(in_array($v,$a2)) return true;
	return false;
}

 #######################################################
# appends the second array to the first
# Doesn't really work on associative arrays.
function array_append(&$one, $two=array()) {
	if(is_array($one) && is_array($two)) {
		for(reset($two); NULL !== ($k = key($two)); next($two)) $one[] = $two[$k];
	}
}


 #################################################################################
# Takes a URL and returns a valid URL, or if one can't be made, a blank string
function valid_url($url='') {
	if (!ereg("^(http[s]?|ftp)\:\/\/",$url)) {
		if ($url) {
			return "http://" . $url;
		} else {
			return "";
		}
	} else {
		return $url;
	}
}


  #####################################################################
 # Looks for URLs and email addresses and adds link tags around them
#####################################################################

function hyperlink_urls($text='') {
	# Search through string and look for things, and keep a record
	# Emails
	$text = ereg_replace("([^t][^o][^\:])([a-zA-Z0-9\._-]+@[a-zA-Z0-9\._-]+\.[a-zA-Z]+)","\\1<a href=\"mailto:\\2\">\\2</a>",$text);
	# URLS (http & ftp)
	# Okay, what I think is happening here is it's looking for any string that starts with "http://" or "ftp://" and doesn't have the two characters = and " preceeding is (as you would in a manually definea A or IMG tag etc. It then checks that everything afterward conforms to generla URL standards.
	$text = ereg_replace("([^\=][^\"])((http[s]?|ftp)://[a-zA-Z0-9\._-]+\.[a-zA-Z]+\/?[a-zA-Z0-9\/\._\-\?\=\&]*)","\\1<a href=\"\\2\" target=new>\\2</a>",$text);
	return $text;
}

/**
* Function to return an array of files in a directory.
*
* @param string dir boolean fullpath
* dir is the directory you want to list files for. If you pass in a filename, it will take the directory of that file name and restrict the returned files to the filename you passed in.
* fullpath determines whether to return the full path to the file or just the filenames themselves
*
* @returns array
* @access public
*/
function list_files($dir='', $fullpath=false) {
	if (!$dir) return false;
	if (is_dir($dir)) {
		$restrict = false;
	} else {
		$restrict = basename(str_replace("*","",$dir));	# if we pass in *.inc, check for .inc
		$dir = dirname($dir);
	}
	$files = array();
	if ($handle = opendir($dir)) {
		while (($file = readdir($handle)) !== false) {
			if ($file == '.' || $file == '..') continue;
			if (is_dir($dir.'/'.$file)) {
				$files[] = array($file);
			}
			if (is_file($dir.'/'.$file)) {
				if ($restrict && (preg_match("%$restrict%", "$file"))) {
					$files[] = ($fullpath) ? $dir.'/'.$file : $file;
				}
				if (!$restrict) {
					$files[] = ($fullpath) ? $dir.'/'.$file : $file;
				}
			}
		}
		closedir($handle);
	}
	return $files;
}

  ####################################
 # Recursively creates a directory from the SYSTEM_ROOT
#####################################

function create_directory($path='') {
	if (!$path) return false;
	global $SYSTEM_ROOT;
	$branches = explode("/", str_replace($SYSTEM_ROOT, "", $path));
	$i = 0;
	$trunk = $SYSTEM_ROOT;
	$old_umask = umask(0);
	while(($trunk .= $branches[$i]."/") && $i++ < count($branches)) {
		if(is_dir($trunk)) continue;
		if (!mkdir(substr($trunk,0,-1),0775)) {
			report_error(__FILE__,__LINE__,"Unable to create directory: $trunk");
			clearstatcache();
			return false;
		}
	}
	umask($old_umask);
	clearstatcache();
	return true;
}

  
  #######################################################################
 # Recursively delete the contents of a directory without system calls #
#######################################################################

function delete_directory($path='') {
	if (!$path) return false;
	$path  = ereg_replace("/$","",$path);
	if (!is_dir($path)) {
		report_error(__FILE__,__LINE__,"$path is not a directory (for deletion).");
		clearstatcache();
		return 0;
	}
	if (clear_directory($path)) {
		if (rmdir($path)) {
			clearstatcache();
			return 1;
		} else {
			report_error(__FILE__,__LINE__,"Unable to delete dir: $path");
			clearstatcache();
			return 0;
		}
	}
	clearstatcache();
	return 0;
}


  ############################################################
 # Deletes all contents of a directory without system calls #
############################################################

function clear_directory($path='') {
	if (!$path) return false;
	$path  = ereg_replace("/$","",$path);
	if (!is_dir($path)) {
		report_error(__FILE__,__LINE__,"$path is not a directory (for clearing.");
		clearstatcache();
		return 0;
	}
	$dir = opendir($path);
	while(false !== ($filename = readdir($dir))) {
		if ($filename == "." || $filename == "..") continue;
		$filename = $path . "/" . $filename;
		if (is_dir($filename)) {
			delete_directory($filename);
		} else {
			if (!unlink($filename)) {
				report_error(__FILE__,__LINE__,"Unable to delete: $filename");
				clearstatcache();
				return 0;
			}
		}
	}
	closedir($dir);
	clearstatcache();
	return 1;
}



  #######################################################################
 # Recursively copies the contents of a directory without system calls #
function copy_directory($path='',$new_path='') {
	if (!$path || !$new_path) return false;
	$path     = ereg_replace("/$","",$path);
	$new_path = ereg_replace("/$","",$new_path);
	if (!is_dir($path)) {
		report_error(__FILE__,__LINE__,"$path is not a directory (for copying.)");
		clearstatcache();
		return 0;
	}
	if(file_exists($new_path)) {
		if(!is_dir($new_path)) {
			report_error(__FILE__,__LINE__,"$new_path is not a directory (for copying into).");
			clearstatcache();
			return 0;
		}
	} else {
		if(!create_directory($new_path)) {
			clearstatcache();
			return 0;
		}
	}
	# Copy the contents
	$dir = opendir($path);
	while(false !== ($filename = readdir($dir))) {
		if ($filename == "." || $filename == "..") continue;
		$full_filename = $path . "/" . $filename;
		if (is_dir($full_filename)) {
			if(!copy_directory($full_filename,"$new_path/$filename")) {
				clearstatcache();
				return 0;
			}
		} else {
			if (!copy($full_filename,"$new_path/$filename")) {
				report_error(__FILE__,__LINE__,"Unable to copy: $full_filename --> $new_path/$filename");
				clearstatcache();
				return 0;
			}
		}
	}
	closedir($dir);
	clearstatcache();
	return 1;
}


	/**
	* Takes a filename and returns the extension in lowercase
	*
	* @param string $filename A filename (with or without the path)
	* @returns string
	* @access  public
	*/
	function get_file_type($filename='') {
		if (!$filename) return false;
		return strtolower(substr( strrchr($filename,".") , 1)  );
	}

	/**
	* Takes a filename and returns true if it is considdered and image
	*
	* @param string $filename A filename (with or without the path)
	* @returns boolean
	* @access public
	*/
	function is_image($filename='') {
		switch(get_file_type($filename)) {
			case "jpg" :
			case "jpeg" :
			case "gif" :
			case "png" :
				return true;
			default :
				return false;
		} #end switch
	}#end is_image()

  ###################################################################
 # From the src ( without the extension ) returns the file name of 
# the image that exists
function get_image($src='') {

	if(!file_exists($src.".".($ext="gif"))){
		if(!file_exists($src.".".($ext="jpg"))){
			if(!file_exists($src.".".($ext="jpeg"))){
				if(!file_exists($src.".".($ext="png"))){
					return "";
				}
			}
		}
	}

	return $src.".".$ext;

}#end get_image()



 ######################################
# Checks an email address for validity
function valid_email($e='') {
	return ereg("^[_\.0-9a-zA-Z'-]+@([0-9a-zA-Z]([0-9A-Za-z-]*)+\.)+[a-zA-Z]{2,4}$",$e);
}


 ###########################
# Reads a file into a string
function file_to_string ($filename='') {
	if (!is_file($filename)) return false;
	if (!$f = fopen($filename,"r")) return false;
	$s = fread($f,filesize($filename));
	fclose($f);
	return $s;
}

function string_to_file ($string='', $filename='') {
	$tmp_filename = tempnam(dirname($filename), 'MyS');
	if (!$f = fopen($tmp_filename,"w")) {
		report_error(__FILE__,__LINE__,"Unable to open file for writing: " . $tmp_filename);
		return false;
	}
	$s = fputs($f, $string, strlen($string));
	fclose($f);
	$om = umask();
	umask(0);
	chmod($tmp_filename, 0664);
	rename($tmp_filename, $filename);
	umask($om);
	if (is_file($tmp_filename)) unlink($tmp_filename);
	return true;
}

 ################################################################
# Takes a filename and returns an alternative, possibly the same
# that is (more) url friendly.
function url_friendly_filename($filename='') {
	$filename = ereg_replace("[ \t\n\r]+","_",$filename); # Spaces! Grr!
	# http://www.w3.org/Addressing/URL/5_URI_BNF.html
	$filename = ereg_replace("[^a-zA-Z0-9\$\_\@\.\&\!\*\(\)\,\-]","",$filename);
	$filename = str_replace("\\","",$filename); # Backslashes! GRR!
	return $filename;
}

 ##########################################
# Returns "http://" or "https://" depends on
# the current protocol
function url_protocol($port='') {
	if(!$port) {
		$port = $_SERVER['SERVER_PORT'];
	}

	switch($port) {
	case 443:
		return "https";
	case 80: default:
		return "http";
	}
}


 #########################################################
# Converts an integer to a roman numeral
function int_to_roman($num=0) {
	$conv = array(10 => array('X', 'C', 'M'),
				  5  => array('V', 'L', 'D'),
				  1  => array('I', 'X', 'C'));
	$roman = '';
	
	$num = (int) $num;

	$digit  = (int) ($num / 1000);
	$num   -= $digit * 1000;
	$roman = str_repeat('M',$digit);

	for ($i = 2; $i >= 0; $i--) {
		$power = pow(10, $i);
		$digit = (int) ($num / $power);
		$num -= $digit * $power;

		if (($digit == 9) || ($digit == 4)) {
			$roman .= $conv[1][$i] . $conv[$digit+1][$i];
		} else {
			if ($digit >= 5) {
				$roman .= $conv[5][$i];
				$digit -= 5;
			}

			while ($digit > 0) {
				$roman .= $conv[1][$i];
				$digit--;
			}
		}
	}

	#if ($num > 0) {
	#	return('');
	#}

	return($roman);
}



 ##############################################################################
# Converts an integer to the corresponding letter string.. e.g 1=A, 26=Z, 27=AA
function int_to_letter_code($num=0) {
	$num = (int) $num;
	if($num<=0) return "";
	$ord = $num % 26;
	if(!$ord)$ord = 26;
	$l = chr(64 + $ord);
	if ($num > 26) {
		$l = int_to_letter_code($num/26) . $l;
	}
	return $l;
}

 ############################################################################
# Takes to version numbers returns 1 if the first one is greater, or 0, or -1
function version_no_compare($ver1='', $ver2='') {
	$v1s = explode(".",ereg_replace("[^0-9\.]","",$ver1));
	$v2s = explode(".",ereg_replace("[^0-9\.]","",$ver2));

	if (count($v1s) < count($v2s)) {
		for($i = count($v1s); $i < count($v2s); $i++) {
			$v1s[] = '0';
		}
	} else if (count($v2s) < count($v1s)) {
		for($i = count($v2s); $i < count($v1s); $i++) {
			$v2s[] = '0';
		}
	}

	for($i = 0, $total = count($v1s); $i < $total; $i++) {
		if((int) $v1s[$i] > (int) $v2s[$i]) return 1;
		if((int) $v1s[$i] < (int) $v2s[$i]) return -1;
	}
	return 0;

}

 ###########################################################################################
# returns the number of days, hours, minutes and seconds that relate to the number of seconds passed
function easy_time_total($secs=0, $full_format=false) {

	$secs = (int) $secs;

	$minute = 60;
	$hour   = 60 * $minute;
	$day    = 24 * $hour;

	$num_days    = 0;
	$num_hours   = 0;
	$num_minutes = 0;
	$num_seconds    = 0;

	# if there is more than one day left
	if ($secs >= $day) {
		$num_days = floor($secs/$day);
		# remove the number of seconds we have taken care of
		$secs -= ($day * $num_days);
	}#end if

	# if there is more than one hour left
	if ($secs >= $hour) {
		$num_hours = floor($secs/$hour);
		# remove the number of seconds we have taken care of
		$secs -= ($hour * $num_hours);
	}#end if

	# if there is more than one minute left
	if ($secs >= $minute) {
		$num_minutes = floor($secs/$minute);
		# remove the number of seconds we have taken care of
		$secs -= ($minute * $num_minutes);
	}#end if

	$num_seconds = $secs;


	$str = "";

	# if they want the full string version
	if ($full_format) {

		if ($num_days) {
			$str .= $num_days." day".(($num_days > 1) ? "s" : "");
		}

		if($num_hours) {
			# if there are no days don't need a joiner
			if (!$num_days) $joiner = "";
			# else if there aren't any minutes or seconds so join with " and "
			elseif (!$num_minutes && !$num_seconds) $joiner = " and ";
			# else there must be more following join with a comma
			else $joiner = ", ";

			$str .= $joiner.$num_hours." hour".(($num_hours > 1) ? "s" : "");
		}

		if($num_minutes) {
			# if there are no days or hours don't need a joiner
			if (!$num_days && !$num_hours) $joiner = "";
			# else if there aren't any seconds so join with " and "
			elseif (!$num_seconds) $joiner = " and ";
			# else there must be more following so join with a comma
			else $joiner = ", ";

			$str .= $joiner.$num_minutes." minute".(($num_minutes > 1) ? "s" : "");
		}

		if($num_seconds) {
			# if there are no days, hours or minutes don't need a joiner
			if (!$num_days && !$num_hours && !$num_minutes) $joiner = "";
			# else must be something preceding us so join with " and "
			else $joiner = " and ";

			$str .= $joiner.$num_seconds." second".(($num_seconds > 1) ? "s" : "");
		}

	# else they just want the normal "xx days xx:xx:xx xxxxx"
	} else {

		$time_type = "";

		if ($num_days) {
			$str .= $num_days." day".(($num_days > 1) ? "s" : "");
		}

		if($num_hours) {
			# if there are no days don't need a joiner
			if (!$num_days) $joiner = "";
			# else we must be following the days so if there minutes or seconds we have a space
			elseif ($num_minutes || $num_seconds) $joiner = " ";
			# else we must be following the days so but as there is no minutes or seconds we have " and "
			else $joiner = " and ";

			if (!$num_minutes && !$num_seconds) {
				$time_type = "hour".(($num_hours > 1) ? "s" : "");
			} else {
				$time_type .= "h";
			}

			$str .= $joiner.$num_hours;
		}

		if($num_minutes || ($num_hours && $num_seconds)) {

			# if there are no days or hours don't need a joiner
			if (!$num_days && !$num_hours) $joiner = "";
			# else if we are following hours, then have a colon
			elseif ($num_hours) $joiner = ":";
			# else we must be following the days so if there seconds we have a space
			elseif ($num_seconds) $joiner = " ";
			# else we must be following the days so but as there is no seconds we have " and "
			else $joiner = " and ";

			if (!$num_hours && !$num_seconds) {
				$time_type = "minute".(($num_minutes > 1) ? "s" : "");
				$str .= $joiner.$num_minutes;
			} else {
				$time_type .= $joiner."m";
				# if there are hours then zerofill to 2 places
				if ($num_hours) {
					$str .= $joiner.sprintf("%02d", $num_minutes);
				} else {
					$str .= $joiner.$num_minutes;
				}
			}
		}

		if($num_seconds) {
			# if there are no days or hours don't need a joiner
			if (!$num_days && !$num_hours && !$num_minutes) $joiner = "";
			# else if there hours the have a colon
			elseif ($num_hours || $num_minutes) $joiner = ":";
			# else we must be following days, so just have a space
			else $joiner = " and ";


			if (!$num_hours && !$num_minutes) {
				$time_type = "second".(($num_seconds > 1) ? "s" : "");
				$str .= $joiner.$num_seconds;
			} else {
				$time_type .= $joiner."s";
				$str .= $joiner.sprintf("%02d", $num_seconds);
			}
		}

		if ($time_type) {
			$str .= " ".((strstr($time_type, ":")) ? "(".trim($time_type).")" : trim($time_type));
		}

	}#end if

	return $str;

}#end easy_time_total()



  #####################################################################################
 # Takes a search string and a field to be matched to and returns the sql conditions 
function complex_search_sql_conditions($match_field='', $q='', $apostrophe_searchable=false) {
	if (!$q) return false;
	list($ors,$ands,$nots) = complex_search_sql_split_search($q, $apostrophe_searchable);
	for ($i = 0; $i < count($ors); $i++) {
		if ($orcon) $orcon .= " OR ";
		$orcon .= "$match_field LIKE '%". $ors[$i] ."%'";
	}
	for ($i = 0; $i < count($ands); $i++) {
		if ($andcon) $andcon .= " AND ";
		$andcon .= "$match_field LIKE '%". $ands[$i] ."%'";
	}
	for ($i = 0; $i < count($nots); $i++) {
		if ($notcon) $notcon .= " AND ";
		$notcon .= "$match_field NOT LIKE '%". $nots[$i] ."%'";
	}
	$conditions  = (($orcon) ? "(" . $orcon . ")" : "");
	$conditions .= (($andcon) ? (($conditions) ? " AND " : "") . "(" . $andcon . ")" : "");
	$conditions .= (($notcon) ? (($conditions) ? " AND " : "") . "(" . $notcon . ")" : "");
	$conditions = (($conditions) ? "(" . $conditions . ")" : "1=0");
	
	return $conditions;
}

  #####################################################################
 # Takes a search string and splits it into ORs ANDs and NOT strings 
function complex_search_sql_split_search($q='', $apostrophe_searchable=false) {
	$q    = gpc_stripslashes($q); # Stripslashes
	$q    = trim($q) . " ";   # Trim off any spaces
	if (!$apostrophe_searchable) {
		$q    = ereg_replace("'", "\"", $q);  # Allow for both quote types
	}
	$q    = preg_replace("[^\-\+\w\" ]","",$q); # Remove invalid characters. 
	$ands = array(); $ors = array(); $nots = array();
	$expr_start = 0;
	$logic      = "or";
	$end	= 0;
	for ($i = 0; $i < strlen($q); $i++) {
		if ($i == $expr_start) {
			if ($q[$i] == '+') {
				$logic = "and";
				$expr_start++;
				continue;
			}
			if ($q[$i] == '-') {
				$logic = "not";
				$expr_start++;
				continue;
			}
			if (substr($q, $i, 1) == "\"") { # Quoted string match.. skips to the end
				$i++;
				while (substr($q, $i, 1) != "\"" && $i < strlen($q)) $i++;
				$expr_start++;
				$end = 1;
			}
		}
		if ($q[$i] == "," || $q[$i] == " ") {
			$end = 1;
		}
		if ($end) {
			$expr = substr($q, $expr_start, $i - $expr_start);
			if ($apostrophe_searchable) {
				$expr = str_replace("'", "\'", $expr);
			}
			if ($logic == "and" && strlen($expr) >= 2) $ands[] = trim($expr);
			if ($logic == "or"  && strlen($expr) >= 2) $ors[]  = trim($expr);
			if ($logic == "not" && strlen($expr) >= 2) $nots[] = trim($expr);
			$expr_start = $i + 1;
			$logic      = "or";
			$end	= 0;
		}
	}
	return array($ors,$ands,$nots);
}

   #####################################################################################
  # Takes a field and a search string and returns an SQL expression that will
 # evaluate to a "score", taking into account the amount of words matched
#and their position in the text
# Note, I think this should be fixed so that 0 <= score <= 1 is always true. Also,
# score should ALWAYS be 0 if the $q wouldn't match.
function complex_search_sql_score($match_field='', $q='') {
	if (empty($q)) return false;
	list($ors,$ands,$nots) = complex_search_sql_split_search(strtolower($q));

	$length = "(1+LENGTH($match_field))";

	$order  = '((0';  # Decending order

	for ($i = 0; $i < count($ors); $i++) {
		$or = $ors[$i];
		# Phrases closer to the beginning have higher priority
		$importance = 0.9 + 0.1 * (count($ors) - $i) / count($ors);
		$order .= " + $importance * ((1 + (1.0 - (POSITION('$or' IN LOWER($match_field))/$length))) * ($match_field LIKE '%$or%'))";
	}
	for ($i = 0; $i < count($ands); $i++) { # Ands are twice as important. Aren't they?
		$and = $ands[$i];
		# Phrases closer to the beginning have higher priority
		$importance = 0.9 + 0.1 * (count($ands) - $i) / count($ands);
		$order .= " + 2 * $importance * ((1 + (1.0 - (POSITION('$and' IN LOWER($match_field))/$length))) * ($match_field LIKE '%$and%'))";
	}

	$order .= ')*('.complex_search_sql_conditions($match_field, $q).'))';

	return $order;
}

/**
* Comparison function that compares 2 arrays on the first element then if they are equal the second element. This function could probably be done better in time.
*
* @param   array $array1	The first array
* @param   array $array1	The second array
* @returns int
* @access  public
*/
function page_dupe_comparison($array1=array(), $array2=array()) {
	if (!is_array($array1) && !is_array($array2)) return 0;
	if (!is_array($array2)) return 1;
	if (!is_array($array1)) return -1;
	if ($array1[0] < $array2[0]) {
		return -1;
	} elseif ($array1[0] > $array2[0]) {
		return 1;
	} else {
		if ($array1[1] < $array2[1]) {
			return -1;
		} elseif ($array1[1] > $array2[1]) {
			return 1;
		} else {
			return 0;
		}
	}
}

/**
* Convert an ip address dotted format to an binary representation
* @param string $ip
* @return string
* @access public
*/
function ip_to_bin($ip='') {
	if(!preg_match("/[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+/",$ip)) return;
	$octets = explode('.',$ip);
	foreach($octets as $octet) {
		$binary .= str_pad(decbin($octet),8,'0',STR_PAD_LEFT);
	}
	return $binary;
}

/**
* Takes a string and escapes all special regular expression chars
* @param string $string - the string to escape
* @return string
* @access public
*/
function escape_regular_expression_chars($string='') {
	$patterns = array('/\\\/','/\^/','/\$/','/\./','/\[/','/\]/','/\|/','/\(/','/\)/','/\?/','/\*/','/\+/','/\{/','/\}/');
	$replacements = array('\\\\\\','\^','\\\$','\.','\[','\]','\|','\(','\)','\?','\*','\+','\{','\}');
	$string = preg_replace($patterns, $replacements, $string);
	return $string;
}
?>