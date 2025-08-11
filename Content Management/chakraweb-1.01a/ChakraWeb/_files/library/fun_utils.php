<?php
// ----------------------------------------------------------------------
// ModName: fun_utils.php
// Purpose: General Porpuses Functions
// Author:  Mitra Dwi Wiyono (mdwiyono@quick4all.com)
// ----------------------------------------------------------------------

if (!defined('LOADED_AS_LIBRARY')) 
    die ("You can't access [fun_utils.php] file directly...");

function GetOsInfo()
{
	global $os_name;
	global $os_type;

	$os_name = php_uname();

	if (substr($os_name, 0, 7) == "Windows") 
	{
		$os_type = OS_WINDOWS;
	}
	else
	{
		//just a simple solutions
		$os_type = OS_UNIX;
	}
}

function PrintArray($arr, $title='')
{
	echo "<pre>\r\n$title = ";
	print_r($arr);
	echo "\r\n</pre>";
}

function PrintLine($str, $title='')
{
	if (!empty($title))
		echo "$title = ";
	echo "$str<br>\r\n";
}

function IsUrlExist($url)
{
	$url = ereg_replace('http://', '', $url);
	list($domain, $file) = explode('/', $url, 2);

	$fid = fsockopen($domain,80);
	fputs($fid,"GET /$file HTTP/1.0\r\nHost: $domain\r\n\r\n");
	$buffer = fgets($fid, 1024);
	fclose($fid);
	
	if (ereg("HTTP/1.1 200 OK", $buffer)) 
		return true;
	else 
		return false;
}


//Sample:
//VersionCheck('4.0.6');  // if version not ok the script stopped and displays an errormsg

function VersionCheck($vercheck)
{
	$minver = str_replace(".","", $vercheck);
	$curver = str_replace(".","", phpversion());
	if($curver >= $minver)
	{
		return true;
	} 
	else 
	{
		SystemFatalError('VersionCheck', "SORRY! PHP version ".$vercheck." or greater is required. Your PHP-Version is : ".phpversion());
	}
}


//open the file, but validate and check the existence of the file if appropriate
function LocalFileOpen($file_name, $mode, $check=true)
{
	global $os_type;
	$fh = 0;

	$file_name = ProperLocalFileName($file_name);
	if ($check)
	{
		if (is_file($file_name))
			$fh = @fopen($file_name, $mode);
	}
	else
		$fh = @fopen($file_name, $mode);

	return $fh;
}

function ProperLocalFileName($file_name)
{
	global $os_type;

	if ($os_type == OS_WINDOWS)
		$file_name = str_replace('/', '\\', $file_name);
	else
		$file_name = str_replace('\\', '/', $file_name);

	return $file_name;
}

function ProperLocalPathName($path)
{
	$sep = GetLocalPathSeparator();
	if ($path[strlen($path)-1] != $sep) 
		$path .= $sep;

	return $path;
}

function ProperUrlPathName($path)
{
	if ($path[strlen($path)-1] != '/') 
		$path .= '/';

	return $path;
}

function GetLocalPathSeparator()
{
	global $os_type;
	global $gPathSep;

	if (!isset($gPathSep))
	{
		if (!isset($os_type))
			GetOsInfo();

		if ($os_type == OS_WINDOWS)
			$gPathSep = '\\';
		else
			$gPathSep = '/';
	}
	return $gPathSep;
}

function IsLocalReferer()
{
    global $HTTP_SERVER_VARS;

    if (empty($HTTP_SERVER_VARS['HTTP_HOST'])) {
        $server = getenv('HTTP_HOST');
    } else {
        $server = $HTTP_SERVER_VARS['HTTP_HOST'];
    }

    if (empty($HTTP_SERVER_VARS['HTTP_REFERER'])) {
        $referer = getenv('HTTP_REFERER');
    } else {
        $referer = $HTTP_SERVER_VARS['HTTP_REFERER'];
    }

    if (empty($referer) || preg_match("!^http://$server/!", $referer)) {
        return true;
    } else {
        return false;
    }
}


function GetBaseURL()
{
    global $HTTP_SERVER_VARS;

    if (empty($HTTP_SERVER_VARS['HTTP_HOST'])) {
        $server = getenv('HTTP_HOST');
    } else {
        $server = $HTTP_SERVER_VARS['HTTP_HOST'];
    }
    // IIS sets HTTPS=off
    if (isset($HTTP_SERVER_VARS['HTTPS']) && $HTTP_SERVER_VARS['HTTPS'] != 'off') {
        $proto = 'https://';
    } else {
        $proto = 'http://';
    }

    $path = GetBaseURI();

    return "$proto$server$path/";
}

function GetBaseURI()
{
    global $HTTP_SERVER_VARS;

    // Get the name of this URI

    // Start of with REQUEST_URI
    if (isset($HTTP_SERVER_VARS['REQUEST_URI'])) {
        $path = $HTTP_SERVER_VARS['REQUEST_URI'];
    } else {
        $path = getenv('REQUEST_URI');
    }
    if ((empty($path)) ||
        (substr($path, -1, 1) == '/')) {
        // REQUEST_URI was empty or pointed to a path
        // Try looking at PATH_INFO
        $path = getenv('PATH_INFO');
        if (empty($path)) {
            // No luck there either
            // Try SCRIPT_NAME
            if (isset($HTTP_SERVER_VARS['SCRIPT_NAME'])) {
                $path = $HTTP_SERVER_VARS['SCRIPT_NAME'];
            } else {
                $path = getenv('SCRIPT_NAME');
            }
        }
    }

    $path = preg_replace('/[#\?].*/', '', $path);
    $path = dirname($path);

    if (preg_match('!^[/\\\]*$!', $path)) {
        $path = '';
    }

    return $path;
}

function HtmlCleanAll($str)
{
    return ereg_replace('<([^>]|\n)*>', '', $str);
}

function HtmlClean($str)
{
    $params = array(
					'|</?\s*SCRIPT.*?>|si' => '',
                    '|</?\s*FRAME.*?>|si' => '',
                    '|</?\s*OBJECT.*?>|si' => '',
                    '|</?\s*META.*?>|si' => '',
                    '|</?\s*APPLET.*?>|si' => '',
                    '|</?\s*LINK.*?>|si' => '',
                    '|</?\s*IFRAME.*?>|si' => '',
					);

    return PRegReplace($params, $str);	
}


function RequestGetValue($var_name, $default=false, $clean=CLEAN_NO)
{
	global $PhpMagicQuote;

	if (isset($_REQUEST[$var_name]))
	{
        $out = $_REQUEST[$var_name];

        if (is_string($out))
        {
            if ($PhpMagicQuote)
                $out = stripslashes($out);
            $out = trim($out);
        }

		if ($clean == CLEAN_SAVE)
			$out = 	HtmlClean($out);
		else if ($clean == CLEAN_ALL)
			$out = 	HtmlCleanAll($out);
	}
	else
		$out = $default;

	return $out;
}

function RequestGetValueForce($var_name, $clean=false)
{
	if (isset($_REQUEST[$var_name]))
	{
		if ($clean)
			$out = 	RequestValueClean($_REQUEST[$var_name]);
		else
			$out = 	$_REQUEST[$var_name];
	}
	else
		SystemFatalError('RequestGetValueForce',"The parameter '$var_name' must be exist");

	return $out;
}

//read local file and save it to the string
function ReadLocalFile($file_name, &$errmsg, $check=true)
{
	$file_name = ProperLocalFileName($file_name);
	$fh = LocalFileOpen($file_name, 'r', $check);
	if ($fh != 0)
	{
		$out = @fread($fh, filesize($file_name));
		@fclose($fh); 
	}
	else
	{
		$errmsg = 'Unable to open file '.$file_name;
		$out = "";
	}

	return $out;
}

//write string to local file
function WriteLocalFile($file_name, $content)
{
    $bresult = false;

	$file_name = ProperLocalFileName($file_name);
	$fh = LocalFileOpen($file_name, 'wb', false);
	if ($fh != 0)
	{
        $bresult = @fwrite($fh, $content);
		@fclose($fh); 
	}
	return $bresult;
}


//read local file and do find replace
//parameter is an two domension array of (var, value). Format of var is {var}
function LoadContentFile($file_name, $params)
{
	$content = ReadLocalFile($file_name, $errmsg, true);
	if (empty($content))
		$content = $errmsg;
	else
		$content = ContentParseVar($content, $params);

	return $content;
}

//params is an two domension array of (var, value). Format of var is {var}
function ContentParseVar($content, $params)
{
	if (!empty($content))
	{
		//if (!is_array($params))
		//	$params = SystemGetParams();

		reset ($params);
		foreach ($params as $var => $value) 
		{
			$content = str_replace('{'.$var.'}', $value, $content);
		} 
	}

	return $content;
}


function SearchQueryBuilder($fldname, $query, &$query_list)
{
	global $db;

	if (!isset($query_list))
		$query_list = array();
	reset($query_list);

	$sign = '+';
	$worlds = array();

	$query = trim($query);
	if (strlen($query) == 0)
		return $out;

	$i = 0;
	$max = strlen($query);
 	while($i < $max)
	{
		$ch = substr($query, $i, 1);
		switch ($ch)
		{
		case '"':
			//find the end part of "
			$item = '';
			$ch = '$';
			while(($i < $max) && ($ch != '"'))
			{
				$i++;
				$ch = substr($query, $i, 1);
				if ($ch != '"')
					$item = $item.$ch;
			}
			$worlds[] = array($sign, $item);
			$count++;
			$i++;
			break;
		case ' ':
			$sign = '+';
			$i++;
			break;
		case '-':
			$sign = '-';
			$i++;
			break;
		default:
			//find the end part of the world
			$item = $ch;
			while(($i < $max) && ($ch != ' '))
			{
				$i++;
				$ch = substr($query, $i, 1);
				if ($ch != ' ')
					$item = $item.$ch;
			}
			$worlds[] = array($sign, $item);
			break;
		}
	}

	//build the query
	$out = '';
	$first_time = true;
	reset ($worlds);
	while (list(, $arr) = each($worlds)) 
	{
		list($sign, $item) = $arr;
		$query_list[] = $item;
   		//print "Item: [$sign]$item<br>\n";
		
		$item = $db->qstr('%'.$item.'%');
		$clause = '('.$fldname.' like '.$item.')';
		if ($first_time)
		{
			$first_time = false;
			if ($sign == '-')
				$out = '( not '.$clause.' )';
			else
				$out = $clause;
		}
		else
		{
			if ($sign == '-')
				$out = $out.' and '.'( not '.$clause.' )';
			else
				$out = $out.' and '.$clause;
		}	
	}
	//print "<br>\n";
	
	return $out;
}


function RandValue($min, $max)
{
	static $nSeed = 0;

	if ($nSeed == 0)
	{
   		list($usec, $sec) = explode(' ', microtime());
   		$nSeed = (float) $sec + ((float) $usec * 100000);
	}
	srand($nSeed);
	
	return rand($min, $max); 
}

function RandStrNumber($format, $min, $max)
{
	return sprintf($format, RandValue($min, $max));
}

function UniqueFileName($path, $prefix, $ext)
{
	$sep = GetLocalPathSeparator();
	if ($path[strlen($path)-1] != $sep) 
		$path .= $sep;
	
	$bLoop = true;
	while ($bLoop)
	{
		$filename = $path.$prefix.RandStrNumber('%06d').'.'.$ext;
		$bLoop = is_file($filename);
	}
	return $filename;
}

function GetMimeArray()
{
	static $arr_mime = array(
			'image/jpeg' => 'jpg',
			'image/gif' => 'gif',
			'image/fif' => 'fif',
			'image/png' => 'png',
			'image/png' => 'png',
			'image/png' => 'png',
			'image/tiff' => 'tif',
			'audio/basic' => 'au',
			'audio/midi' => 'mid',
			'audio/mpeg' => 'mp3',
			'audio/x-wav' => 'wav',
			'application/zip' => 'zip',
			'application/pdf' => 'pdf',
			'application/postscript' => 'ps',
			'application/rtf' => 'rtf',
			'application/msword' => 'doc',
			'text/html' => 'htm',
			'text/plain' => 'txt',
			'video/mpeg' => 'mpg',
			);

	return $arr_mime;
}

function GetFileExtFromMime($mime)
{
	$arr_mime = GetMimeArray();
	$mime = trim(strtolower($mime));
	return $arr_mime[$mime];
}

function GetMime($file_name)
{
	$arr_mime = GetMimeArray();
    $file_ext = GetFileExt($file_name);

    foreach($arr_mime as $mime => $ext)
    {
        if ($file_ext == $ext)
            return $mime;
    }

    return 'text/plain';
}

function IsMimeImage($mime)
{
	$mime = trim(strtolower($mime));
	if (strpos($mime, 'image/') === false)
		return false;
	return true;
}

function GetFileExt($file_name)
{
    if (preg_match('/(.*)\.(.*)$/i', $file_name, $match))
    {
        //PrintArray($match);
        return strtolower($match[2]);
    }

    return '';
}

function LinkFromArray($arlinks, $prefix, $suffix, $sep=' - ')
{
	$out = '';

	reset ($arlinks);
	foreach ($arlinks as $link) 
	{
		list($url, $title) = $link;
		$out .= $prefix.'<a href="'.$url.'">'.$title.'</a>'.$suffix.$sep;
	} 

	return substr($out, 0, strlen($out) - strlen($sep));
}

function UnorderListFromArray($arlist)
{
	$out = "<ul>\n";

	reset ($arlist);
	foreach ($arlist as $line) 
	{
		$out .= '<li>'.$line."\n";
	} 
	$out .= "</ul>\n";

	return $out;
}


function ListGetValue($list, $key, $default=false)
{
	if (isset($list[$key]))
		$out = 	$list[$key];
	else
		$out = $default;

	return $out;
}

function BlockPositionAsOption($pos, $bAddBlank=false)
{
	global $gBlockPositionList;
	
	$out = '';
	if ($bAddBlank)
		$out .= "<option $selected value=\"\"></option>\r\n";

	foreach($gBlockPositionList as $key => $note)
	{
		if ($key == $pos)
			$selected = 'selected';
		else
			$selected = '';
		$out .= "<option $selected value=\"$key\">$note</option>\r\n";
	}

	return $out;

}


function HRef($url, $title, $target='')
{
    if (empty($target))
    	return '<a href="'.$url.'">'.$title.'</a>';
    else
    	return '<a href="'.$url.'" target="'.$target.'">'.$title.'</a>';
}

function HRefCheat($url, $url_cheat, $title)
{
	return '<a onmouseover="self.status=\''.$url_cheat.'\'; return true" onmouseout="self.status=\'\'; return true" href="'.$url.'">'.$title.'</a>';
}

function IsValidLangID($lid)
{
	global $gValidLanguageList;
	return in_array($lid, $gValidLanguageList);
}

function LocalFileParsed($filename)
{
	$content = ContentParse(ReadLocalFile(SystemGetContentLocalPath().$filename, $errmsg, true));
	return $content;
}

/**
 * clean user input, modified from PostNuke
 * Gets a _REQUEST variable, cleaning it up to try to ensure that
 * hack attacks don't work
 * @param var name of variable to get
 * @param ...
 * @returns string/array
 * @return prepared variable if only one variable passed
 * in, otherwise an array of prepared variables
 */
function VarCleanFromInput()
{
    $search = array('|</?\s*SCRIPT.*?>|si',
                    '|</?\s*FRAME.*?>|si',
                    '|</?\s*OBJECT.*?>|si',
                    '|</?\s*META.*?>|si',
                    '|</?\s*APPLET.*?>|si',
                    '|</?\s*LINK.*?>|si',
                    '|</?\s*IFRAME.*?>|si',
                    '|STYLE\s*=\s*"[^"]*"|si');

    $replace = array('');

    $resarray = array();
    foreach (func_get_args() as $var) 
	{
        // Get var
		if (!isset($_REQUEST[$var]))
		{
            array_push($resarray, NULL);
            continue;
        }
		
		$ourvar = $_REQUEST[$var];
		if (empty($ourvar))
		{
            array_push($resarray, NULL);
            continue;
        }

        // Clean var
        if (get_magic_quotes_gpc()) {
            StrStripSlashes($ourvar);
        }
        
		$ourvar = preg_replace($search, $replace, $ourvar);

        // Add to result array
        array_push($resarray, $ourvar);
    }

    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}


function VarPrepForStore()
{
	global $db;

    $resarray = array();

    foreach (func_get_args() as $ourvar) 
	{
        array_push($resarray, $db->qstr($ourvar));
    }

    // Return vars
    if (func_num_args() == 1) {
        return $resarray[0];
    } else {
        return $resarray;
    }
}

function GetStrImageSize($string)
{
	$size = array('w' => 0, 'h' => 0);

	if (strlen($string) > 0)
	{
		$img = @ImageCreateFromString($string);
		if ($img)
		{
			$size['w'] = @ImageSX($img);
			$size['h'] = @ImageSY($img);
			@ImageDestroy($img);
		}
	}

	return $size;
}

function IncrementHits($table, $id)
{
	global $db;

	$sql = "update $table set item_hits=item_hits+1 where item_id=$id";
	//PrintLine($sql, 'SQL');

	return $db->Execute($sql);
}

function UpdateRating($table, $id, $rating)
{
	global $db;

	$sql = 'select item_rating, item_votes from '.$table.' where item_id = '.$id;
	//PrintLine($sql, 'SQL');

	$rs = $db->Execute($sql);
	if ($rs === false) 
		DbFatalError('UpdateRating', 'Unable to get item information');

	if (!$rs->EOF)
	{
		//PrintLine($rating, 'rating');

		$item_rating = $rs->fields[0];
		$item_votes  = $rs->fields[1];
		$item_rating = (float)($item_rating*$item_votes+$rating)/($item_votes+1);
		$item_votes++;
		
		$sql = "update $table set item_rating=$item_rating, item_votes=$item_votes where item_id=$id";
		//PrintLine($sql, 'SQL');

		return $db->Execute($sql);
	}
	return false;
}

function GetIconImage($icon)
{
	return '<img src="'.SystemGetImagePath().'icons/'.$icon.'" border="0" align="absmiddle">';
}

function GetRatingImage($rating, $btitle=true)
{
	$out = '';
	if ($btitle)
		$out .= _ITEM_RATING.':';

	$out .= '<img src="'.SystemGetImagePath().'stars/';

	if ($rating < 0.249)
		$out .= '0.gif';
	else if ($rating < 0.749)
		$out .= 'half.gif';
	else if ($rating < 1.249)
		$out .= '1.gif';
	else if ($rating < 1.749)
		$out .= '1half.gif';
	else if ($rating < 2.249)
		$out .= '2.gif';
	else if ($rating < 2.749)
		$out .= '2half.gif';
	else if ($rating < 3.249)
		$out .= '3.gif';
	else if ($rating < 3.749)
		$out .= '3half.gif';
	else if ($rating < 4.249)
		$out .= '4.gif';
	else if ($rating < 4.749)
		$out .= '4half.gif';
	else
		$out .= '5.gif';

	$out .= '" border="0" align="absmiddle">';
	return $out;
}

function TimeZoneOffsetAsOption($tz)
{
	$theList = array (
				0 => '(GMT -12:00 hours) Eniwetok, Kwajalein',
				1 => '(GMT -11:00 hours) Midway Island, Samoa',
				2 => '(GMT -10:00 hours) Hawaii',
				3 => '(GMT -9:00 hours) Alaska',
				4 => '(GMT -8:00 hours) Pacific Time (US &amp; Canada)',
				5 => '(GMT -7:00 hours) Mountain Time (US &amp; Canada)',
				6 => '(GMT -6:00 hours) Central Time (US &amp; Canada), Mexico City',
				7 => '(GMT -5:00 hours) Eastern Time (US &amp; Canada), Bogota, Lima, Quito',
				8 => '(GMT -4:00 hours) Atlantic Time (Canada), Caracas, La Paz',
				8.5 => '(GMT -3:30 hours) Newfoundland',
				9 => '(GMT -3:00 hours) Brazil, Buenos Aires, Georgetown',
				10 => '(GMT -2:00 hours) Mid-Atlantic',
				11 => '(GMT -1:00 hours) Azores, Cape Verde Islands',
				12 => '(GMT) Western Europe Time, London, Lisbon, Casablanca, Monrovia',
				13 => '(GMT +1:00 hours) CET(Central Europe Time), Brussels, Copenhagen, Madrid, Paris',
				14 => '(GMT +2:00 hours) EET(Eastern Europe Time), Kaliningrad, South Africa',
				15 => '(GMT +3:00 hours) Baghdad, Kuwait, Riyadh, Moscow, St. Petersburg',
				15.5 => '(GMT +3:30 hours) Tehran',
				16 => '(GMT +4:00 hours) Abu Dhabi, Muscat, Baku, Tbilisi',
				16.5 => '(GMT +4:30 hours) Kabul',
				17 => '(GMT +5:00 hours) Ekaterinburg, Islamabad, Karachi, Tashkent',
				17.5 => '(GMT +5:30 hours) Bombay, Calcutta, Madras, New Delhi',
				18 => '(GMT +6:00 hours) Almaty, Dhaka, Colombo',
				19 => '(GMT +7:00 hours) Bangkok, Hanoi, Jakarta',
				20 => '(GMT +8:00 hours) Beijing, Perth, Singapore, Hong Kong, Chongqing, Urumqi, Taipei',
				21 => '(GMT +9:00 hours) Tokyo, Seoul, Osaka, Sapporo, Yakutsk',
				21.5 => '(GMT +9:30 hours) Adelaide, Darwin',
				22 => '(GMT +10:00 hours) EAST(East Australian Standard)',
				23 => '(GMT +11:00 hours) Magadan, Solomon Islands, New Caledonia',
				24 => '(GMT +12:00 hours) Auckland, Wellington, Fiji, Kamchatka, Marshall Island',
			);

	$out = '';
	foreach($theList as $key => $note)
	{
		if ($key == $tz)
			$selected = 'selected';
		else
			$selected = '';
		$out .= "<option $selected value=\"$key\">$note</option>\r\n";
	}

	return $out;

}

function AvatarAsOption($avatar)
{
	$path = SystemGetAvatarLocalPath(); 
	$path = substr($path, 0, strlen($path)-1);

	$out = '';
	$flist = @opendir($path) ; 
	while($fn = @readdir($flist)) 
	{
		if ($fn != '.' && $fn != '..')
		{
			if ($fn == $avatar)
				$selected = 'selected';
			else
				$selected = '';
	
			$out .= "<option $selected value=\"$fn\">$fn</option>\r\n";
		}
	}
	@closedir($flist); 

	//PrintLine($path, 'path');
	//print $out;

	return $out;
}

function CheckImageUpload($fld_name, &$errmsg, $NoFileOK)
{
	$errno 	= $_FILES[$fld_name][error]; 		// error

	if ($errno != 0) //UPLOAD_ERR_OK)
	{
		switch ($errno)
		{
		case 1: //UPLOAD_ERR_INI_SIZE:
			$errmsg = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
			break;
		case 2: //UPLOAD_ERR_FORM_SIZE:
			$errmsg = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the html form';
			break;
		case 3: //UPLOAD_ERR_PARTIAL:
			$errmsg = 'The uploaded file was only partially uploaded';
			break;
		case 4: //UPLOAD_ERR_NO_FILE:
			if ($NoFileOK)
				return true;

			$errmsg = 'No file was uploaded';
			break;
		default:
			$errmsg = 'Unknown error code '.$errno;
			break;
		}

		return false;
	}

	$bc_mime = $_FILES[$fld_name][type];     	// mime type 
	if (IsMimeImage($bc_mime))
		return true;
	
	$errmsg = 'Yang anda upload bukan gambar bung!';
	return false;
}

function CreateRandomPassword()
{
	return SimpleRandString(7);
}


// Generates a random string with the specified length
// Chars are chosen from the provided [optional] list
//
function SimpleRandString($length=16, $list="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ")
{
	mt_srand((double)microtime()*1000000);
	$newstring="";
	if($length>0){
	while(strlen($newstring)<$length)
	{
		$newstring.=$list[mt_rand(0, strlen($list)-1)];
	}
	}
	return $newstring;
}


//@param $ts is a timestamp string in YYYYMMDDHHNNSS format
//@return a timestamp string in YYYY-MM-DD HH:NN:SS format
function FormatMySqlTime($ts)
{
	$out =  substr($ts, 0, 4).'-'.substr($ts, 4, 2).'-'.substr($ts, 6, 2).' ';
	$out .= substr($ts, 8, 2).':'.substr($ts, 10, 2).':'.substr($ts, 12, 2);
	
	return $out;
}

function IsUrlValid($url)
{
    return preg_match("/^([http|ftp|mailto|news]:\/\/)?([^\/]+)/i", $url);
}

function IsHttpUrlValid($url)
{
    return preg_match("/^(http:\/\/)?([^\/]+)/i", $url);
}


function IsNameValid($uname)
{
    return preg_match('/^[a-zA-Z0-9 _.]{3,}$/', $uname);
}

function IsUPValid($uname)
{
    return preg_match('/^[a-zA-Z0-9_]{3,}$/', $uname);
}

function IsEmailValid($email)
{
    return preg_match('/^[a-z0-9_-]+(\.[a-z0-9_-]+)*@[a-z0-9_-]+(\.[a-z0-9_-]+)+$/', $email);
}

function ComboBoxFromArray($ar, $name, $selvalue='')
{
    $out = "<select size=\"1\" name=\"$name\" class=\"inputbox\">\n";

    foreach($ar as $value => $label)
    {
        $out .= "<option ";

        if ($selvalue == $value)
            $out .= "selected ";

        $out .= "value=\"$value\">$label</option>\n";
    }
    $out .= "</select>\n";

    return $out;
}

function ComboBoxFromArray1($ar, $name, $selvalue='')
{
    $out = "<select size=\"1\" name=\"$name\" class=\"inputbox\">\n";

    foreach($ar as $value)
    {
        $out .= "<option ";

        if ($selvalue == $value)
            $out .= "selected ";

        $out .= "value=\"$value\">$value</option>\n";
    }
    $out .= "</select>\n";

    return $out;
}

function CheckBox($name, $value, $bchecked)
{
    if ($bchecked)
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\" checked>";
    else
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\">";
}



function EncloseParagraph($str)
{
    if ((strpos($str, '<p>') === false) && (strpos($str, '<P>') === false))
    {
        $str = '<p>'.$str.'</p>';
    }
    return $str;
}


function SmileyParse($str)
{
    $arSmiley = SmileyGetArray();

    foreach($arSmiley as $code => $smiley)
    {
        $str = str_replace($code, $smiley[1], $str);
    }

    return $str;
}

function SmileyGetArray()
{
    global $garSmiley;

    if (isset($garSmiley))
        return $garSmiley;

    $garSmiley = array (
        ':grin:'    => array('Very Happy', '<img src="/images/smiley/biggrin.gif" border=0>'),
        ':-D'       => array('Very Happy', '<img src="/images/smiley/biggrin.gif" border=0>'),
        ':D'        => array('Very Happy', '<img src="/images/smiley/biggrin.gif" border=0>'),

        ':smile:'   => array('Smile', '<img src="/images/smiley/smile.gif" border=0>'),
        ':-)'       => array('Smile', '<img src="/images/smiley/smile.gif" border=0>'),
        ':)'        => array('Smile', '<img src="/images/smiley/smile.gif" border=0>'),

        ':!:'       => array('Exclamation', '<img src="/images/smiley/exclaim.gif" border=0>'),
        ':?:'       => array('Question', '<img src="/images/smiley/question.gif" border=0>'),
        ':idea:'    => array('Idea', '<img src="/images/smiley/idea.gif" border=0>'),
        ':arrow:'   => array('Arrow', '<img src="/images/smiley/arrow.gif" border=0>'),

        ':sad:'     => array('Sad', '<img src="/images/smiley/sad.gif" border=0>'),
        ':-('       => array('Sad', '<img src="/images/smiley/sad.gif" border=0>'),
        ':('        => array('Sad', '<img src="/images/smiley/sad.gif" border=0>'),

        ':oops:'    => array('Embarassed', '<img src="/images/smiley/redface.gif" border=0>'),
        ':cry:'     => array('Crying or Very sad', '<img src="/images/smiley/cry.gif" border=0>'),

        ':shock:'   => array('Shocked', '<img src="/images/smiley/shock.gif" border=0>'),
        ':eek:'     => array('Surprised', '<img src="/images/smiley/surprised.gif" border=0>'),
        ':-o'       => array('Surprised', '<img src="/images/smiley/surprised.gif" border=0>'),
        ':o'        => array('Surprised', '<img src="/images/smiley/surprised.gif" border=0>'),

        ':???:'     => array('Confused', '<img src="/images/smiley/confused.gif" border=0>'),
        ':-?'       => array('Confused', '<img src="/images/smiley/confused.gif" border=0>'),
        ':?'        => array('Confused', '<img src="/images/smiley/confused.gif" border=0>'),

        ':cool:'    => array('Cool', '<img src="/images/smiley/cool.gif" border=0>'),
        '8-)'       => array('Cool', '<img src="/images/smiley/cool.gif" border=0>'),
        '8)'        => array('Cool', '<img src="/images/smiley/cool.gif" border=0>'),

        ':lol:'     => array('Laughing', '<img src="/images/smiley/lol.gif" border=0>'),

        ':mad:'     => array('Mad', '<img src="/images/smiley/mad.gif" border=0>'),
        ':-x'       => array('Mad', '<img src="/images/smiley/mad.gif" border=0>'),
        ':x'        => array('Mad', '<img src="/images/smiley/mad.gif" border=0>'),

        ':razz:'    => array('Razz', '<img src="/images/smiley/razz.gif" border=0>'),
        ':-P'       => array('Razz', '<img src="/images/smiley/razz.gif" border=0>'),
        ':P'        => array('Razz', '<img src="/images/smiley/razz.gif" border=0>'),

        ':evil:'    => array('Evil or Very Mad', '<img src="/images/smiley/evil.gif" border=0>'),
        ':twisted:' => array('Twisted Evil', '<img src="/images/smiley/twisted.gif" border=0>'),

        ':roll:'    => array('Rolling Eyes', '<img src="/images/smiley/rolleyes.gif" border=0>'),

        ':wink:'    => array('Wink', '<img src="/images/smiley/wink.gif" border=0>'),
        ';)'        => array('Wink', '<img src="/images/smiley/wink.gif" border=0>'),
        ';-)'       => array('Wink', '<img src="/images/smiley/wink.gif" border=0>'),

        ':neutral:' => array('Neutral', '<img src="/images/smiley/neutral.gif" border=0>'),
        ':-|'       => array('Neutral', '<img src="/images/smiley/neutral.gif" border=0>'),
        ':|'        => array('Neutral', '<img src="/images/smiley/neutral.gif" border=0>'),

        ':mrgreen:' => array('Mr. Green', '<img src="/images/smiley/mrgreen.gif" border=0>'),
    );

    return $garSmiley;
}

function SetDynamicContent()
{
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");               // Date in the past
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");  // always modified
    header("Cache-Control: no-store, no-cache, must-revalidate");   // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");                                     // HTTP/1.0 
}


function FixKeywords($params)
{
    $params = strtolower($params);
    $params = str_replace(', ', ',', $params);
    $params = str_replace(', ', ',', $params);

    $ar = explode(',', $params);
    $kws = array_unique($ar);

    return implode(', ', $kws);
}

function InitCountryList()
{
    global $gCountryList;

    if (!isset($gCountryList))
    {
    $gCountryList = array (
    0 => _FLD_SELECT_COUNTRY,
    1 => 'Afghanistan',
    2 => 'Albania',
    3 => 'Algeria',
    4 => 'Andorra',
    5 => 'Angola',
    6 => 'Antigua and Barbuda',
    7 => 'Argentina',
    8 => 'Armenia',
    9 => 'Aruba',
    10 => 'Australia',
    11 => 'Austria',
    12 => 'Azerbaijan',
    13 => 'Bahamas',
    14 => 'Bahrain',
    15 => 'Bangladesh',
    16 => 'Barbados',
    17 => 'Belarus',
    18 => 'Belgium',
    19 => 'Belize',
    20 => 'Benin',
    21 => 'Bermuda',
    22 => 'Bhutan',
    23 => 'Bolivia',
    24 => 'Bosnia',
    25 => 'Botswana',
    26 => 'Brazil',
    27 => 'Brunei',
    28 => 'Bulgaria',
    29 => 'Burkina Faso',
    30 => 'Burma',
    31 => 'Burundi',
    32 => 'Cambodia',
    33 => 'Cameroon',
    34 => 'Canada',
    35 => 'Cape Verde',
    36 => 'Cayman Islands',
    37 => 'Central Afr. Rep.',
    38 => 'Chad',
    39 => 'Chile',
    40 => 'China',
    41 => 'Colombia',
    42 => 'Comoros',
    43 => 'Congo',
    44 => 'Costa Rica',
    45 => 'Croatia',
    46 => 'Cuba',
    47 => 'Cyprus',
    48 => 'Czech Republic',
    49 => 'Denmark',
    50 => 'Djibouti',
    51 => 'Dom. Republic',
    52 => 'Dominica',
    53 => 'Ecuador',
    54 => 'Egypt',
    55 => 'El Salvador',
    56 => 'Equatorial Guinea',
    57 => 'Eritrea',
    58 => 'Estonia',
    59 => 'Ethiopia',
    60 => 'Fiji',
    61 => 'Finland',
    62 => 'France',
    63 => 'Gabon',
    64 => 'Gambia',
    65 => 'Georgia',
    66 => 'Germany',
    67 => 'Ghana',
    68 => 'Gibraltar',
    69 => 'Greece',
    70 => 'Grenada',
    71 => 'Guadeloupe',
    72 => 'Guatemala',
    73 => 'Guinea',
    74 => 'Guinea-Bissau',
    75 => 'Guyana',
    76 => 'Haiti',
    77 => 'Honduras',
    78 => 'Hong Kong',
    79 => 'Hungary',
    80 => 'Iceland',
    81 => 'India',
    82 => 'Indonesia',
    83 => 'Iran',
    84 => 'Iraq',
    85 => 'Ireland',
    86 => 'Israel',
    87 => 'Italy',
    88 => 'Ivory Coast',
    89 => 'Jamaica',
    90 => 'Japan',
    91 => 'Jersey',
    92 => 'Jordan',
    93 => 'Kazakhstan',
    94 => 'Kenya',
    95 => 'Kuwait',
    96 => 'Laos',
    97 => 'Latvia',
    98 => 'Lebanon',
    99 => 'Lesotho',
    100 => 'Liberia',
    101 => 'Libya',
    102 => 'Liechtenstein',
    103 => 'Lithuania',
    104 => 'Luxembourg',
    105 => 'Macau',
    106 => 'Macedonia',
    107 => 'Madagascar',
    108 => 'Malawi',
    109 => 'Malaysia',
    110 => 'Maldives',
    111 => 'Mali',
    112 => 'Malta',
    113 => 'Martinique',
    114 => 'Mauritania',
    115 => 'Mauritius',
    116 => 'Mexico',
    117 => 'Moldova',
    118 => 'Monaco',
    119 => 'Mongolia',
    120 => 'Morocco',
    121 => 'Mozambique',
    122 => 'Namibia',
    123 => 'Nepal',
    124 => 'Netherlands',
    125 => 'Netherlands Antilles',
    126 => 'New Zealand',
    127 => 'Nicaragua',
    128 => 'Niger',
    129 => 'Nigeria',
    130 => 'North Korea',
    131 => 'Norway',
    132 => 'Oman',
    133 => 'Pakistan',
    134 => 'Panama',
    135 => 'Paraguay',
    136 => 'Peru',
    137 => 'Philippines',
    138 => 'Poland',
    139 => 'Portugal',
    140 => 'Qatar',
    141 => 'Romania',
    142 => 'Russia',
    143 => 'Rwanda',
    144 => 'San Marino',
    145 => 'Saudi Arabia',
    146 => 'Senegal',
    147 => 'Seychelles',
    148 => 'Sierra Leone',
    149 => 'Singapore',
    150 => 'Slovakia',
    151 => 'Slovenia',
    152 => 'Somalia',
    153 => 'South Africa',
    154 => 'South Korea',
    155 => 'Spain',
    156 => 'Sri Lanka',
    157 => 'Sudan',
    158 => 'Suriname',
    159 => 'Sweden',
    160 => 'Switzerland',
    161 => 'Syria',
    162 => 'Taiwan',
    163 => 'Tajikistan',
    164 => 'Tanzania',
    165 => 'Thailand',
    166 => 'Togo',
    167 => 'Trinidad and Tobago',
    168 => 'Tunisia',
    169 => 'Turkey',
    170 => 'Turkmenistan',
    171 => 'Turks and Caicos Islands',
    172 => 'U.A.E.',
    173 => 'Uganda',
    174 => 'Ukraine',
    175 => 'United Kingdom',
    176 => 'United States',
    178 => 'Uruguay',
    179 => 'Uzbekistan',
    180 => 'Venezuela',
    181 => 'Vietnam',
    182 => 'Yemen',
    183 => 'Yugoslavia',
    184 => 'Zambia',
    185 => 'Zimbabwe',
    );
    }
}

function CountryComboBox($fldname, $selvalue='')
{
    global $gCountryList;

    InitCountryList();

    return ComboBoxFromArray($gCountryList, $fldname, $selvalue);
}

    
// ----------------------------------------------------------------------
//END
?>
