<?php
/*
+---------------------------------------------------------------+
|       Download Sentinel++ 
|        /mplus.php
|
|	Version: >>v2.1.0<<
|
|        ©Kevin Lynn 2005
|        URL: http://scripts.ihostwebservices.com
|        EMAIL: scripts at ihostwebservices dot com
|
|        Released under the terms and conditions of the
|        GNU General Public License (http://gnu.org).
+---------------------------------------------------------------+
*/

/* grab the config file data */
if(file_exists('/home/example/ds_files/scripts/ds_config.php')) {include_once('/home/example/ds_files/scripts/ds_config.php');}
else {echo 'Config file missing, please re-install.';exit;}

if(DS_CTOKENON ==1 || DS_STOKENON ==1) 
{
	session_start();
}

/* use a browser cookie to disable link sharing between browsers */
if(DS_CTOKENON == 1) 
{
	$ctoken = md5(uniqid(rand(), true));
	setcookie('ctoken', $ctoken, time()+DS_ACTIVEDL);
	define('CTOKEN', $ctoken);
}

/* set up session token to prevent offsite robots from leeching. */
if(DS_STOKENON == 1) 
{
	session_start();
	$stoken = md5(uniqid(rand(), true));
	$_SESSION['stoken'] = $stoken;
	define('STOKEN', $stoken);
}

/* parse the html file or the cache if there is one*/
$data = @file_get_contents('mplus.html');
if(!$data) {echo 'mplus.html file missing, please re-install.';exit;}

define('DIR_LINK', extract_string($data, '<dirlink>', '</dirlink>'));
define('FILE_LINK', extract_string($data, '<filelink>', '</filelink>'));
define('TOKEN', make_token());
$preloop = extract_string($data, '<preloop>', '</preloop>');
$postloop = extract_string($data, '<postloop>', '</postloop>');
$head = before('<preloop>', $data);
$foot = after('</postloop>',$data);

/* build the content */
$list = makeTree(DS_FILEPATH);
$list = array_reverse($list);
$content = makeContent($list);
$display = buildDisplay($content);

/* display the content */
echo $head;
echo $preloop;
echo $display;
echo $postloop;
echo $foot;


function makeContent($list)
{
	$cnt = '';
	$desc = '';
	$athr = '';
	@include_once (DS_DATADIR.'ds_count.php');
	@include_once (DS_DATADIR.'ds_desc.php');
	@include_once (DS_DATADIR.'ds_author.php');
	
	foreach($list as $fileandpath)
	{
		$size = get_file_size(filesize($fileandpath));
		$mdate = date('d\-M\-Y\ ga ', filemtime($fileandpath)); // the file modified date, formatted.
		$filter = after(DS_FILEPATH.'/', $fileandpath); // get rid of the leading user directory info and focus on everything under ds_files
		$splitdirs = explode('/',$filter); // directories
		$_file = end($splitdirs); // the file
		$downloads = 0;
		$description = '';
		$author = '';	
	
		// find the download count for this file 
		foreach($cnt as $kfile=>$vcount)
		{
			if($kfile == $fileandpath)
			{
				$downloads = $vcount;
				break;
			}
			else
			{
				$downloads = 0;
			}
		}
		
		// find the description for this file 
		foreach($desc as $kfile=>$vdesc)
		{
			if($kfile == $fileandpath)
			{
				$description = $vdesc;
				break;
			}
			else
			{
				$description = '';
			}
		}
	
		// find the author for this file 
		foreach($athr as $kfile=>$vauthor)
		{
			if($kfile == $fileandpath)
			{
				$author = $vauthor;
				break;
			}
			else
			{
				$author = '';
			}
		}
	
		$incr = 0; // need this added to the file name value to make every file name unique, so you can have the same file name in different directories
		foreach($splitdirs as $dirlist_key=>$dirlist_value)
		{
			$incr++;
			if($dirlist_value == $_file) 
			{
				$store_dir_and_position[$dirlist_value.':'.$incr.':'.$filter] = 'file:'.$dirlist_key.':'.$description.':'.$author.':'.$downloads.':'.$size.':'.$mdate;
			}
			else
			{
				$store_dir_and_position[$dirlist_value.':'.$incr] = 'dir:'.$dirlist_key.':::::'.$mdate;
			}
		}
	}

	return $store_dir_and_position;

}

function buildDisplay($store_dir_and_position)
{
	$what_am_i_and_where_am_i = 'dir:0'; // intialize value
	foreach ($store_dir_and_position as $skey=>$svalue)
	{
		$skey_filename = explode(':', $skey);
		$skey = $skey_filename[0]; // take off the extra number we added in loop above
		$filepathlink =  $skey_filename[2];
		
		$endstring = '';
		$startstring = '';
		$where_was_i = $what_am_i_and_where_am_i[1];
		
		$what_am_i_and_where_am_i = explode(':',$svalue); 
		$description = $what_am_i_and_where_am_i[2];
		$author = $what_am_i_and_where_am_i[3];
		$downloads = $what_am_i_and_where_am_i[4];
		$size = $what_am_i_and_where_am_i[5];
		$mdate = $what_am_i_and_where_am_i[6];
		
		if(DS_UNQTKN == 1)
		{
			$token = make_token();
		}
		else
		{
			$token = TOKEN;
		}
		
		// close previous one based on the difference between the levels. $where_am_i minus $what_am_i_and_where_am_i[1]
		$qtyOfCloses = $where_was_i - $what_am_i_and_where_am_i[1];
		
		if($qtyOfCloses > 0 )
		{
			for($q=0; $q<$qtyOfCloses; $q++)
			{
				$endstring .= '</ul></li>';
			}
		}
		else
		{
			$endstring .= '';
		}
		
		if($what_am_i_and_where_am_i [0] == 'dir')
		{
			$replace = str_replace ('<dirname />', $skey, DIR_LINK);
			$startstring = '<li>'.$replace.'<ul>';
		}
		else
		{
			$replace = str_replace ('<filename />', $skey, FILE_LINK);
			$replace = str_replace ('<filepathlink />', $filepathlink, $replace);
			$replace = str_replace ('<token />', $token, $replace);
			$replace = str_replace ('<filesize />', $size, $replace);
			$replace = str_replace ('<downloads />', $downloads, $replace);
			$replace = str_replace ('<date />', $mdate, $replace);
			$replace = str_replace ('<author />', $author, $replace);
			$replace = str_replace ('<description />',$description,$replace);
			
			
			$startstring = '<li>'.$replace.'</li>';
		}
		
		$display .= $endstring.$startstring;	
	}


	for($q=0; $q<$what_am_i_and_where_am_i[1]; $q++)
	{
		$endstring .= '</ul></li>';
	}
	
	$display .= $endstring;
	return $display;
}
	
function extract_string($str, $start, $end) 
{
	$str_low = strtolower($str);
	if (strpos($str_low, $start) !== false && strpos($str_low, $end) !== false) {
		$pos1 = strpos($str_low, $start) + strlen($start);
		$pos2 = strpos($str_low, $end) - $pos1;
		return substr($str, $pos1, $pos2);
	}
} 

function before ($this, $inthat)
{
	return substr($inthat, 0, strpos($inthat, $this));
}
 
function after ($this, $inthat)
{
	if (!is_bool(strpos($inthat, $this)))
	return substr($inthat, strpos($inthat,$this)+strlen($this));
}

function get_file_size($size) 
{
	$units = array(' B', ' KB', ' MB', ' GB', ' TB');
	for ($i = 0; $size > 1024; $i++) { $size /= 1024; }
	return round($size, 2).$units[$i];
}

function make_token() 
{
	$currhour = date('Ymd');
	$token['time'] = time();
	$token['hash'] = sha1(DS_FTOKEN.$currhour);
	if(DS_CTOKENON ==1) $token['ctoken'] = CTOKEN;
	if(DS_STOKENON ==1) $token['stoken'] = STOKEN;
	$passtoken = base64_encode(serialize($token));
	return $passtoken;
}

function makeTree($path)
{ 
	$list=array();
	$handle=opendir($path);
	while($a=readdir($handle)) {
		if(!preg_match('/^\./',$a)) {
			$full_path = $path.'/'.$a;
			if(is_file($full_path)) { $list[]=$full_path; }
			if(is_dir($full_path)) {
				$recursive=makeTree($full_path);
				for($n=0; $n<count($recursive); $n++) {
					$list[]=$recursive[$n];
				}
			}
		}
	}
	closedir($handle);
	return $list;
}
?>