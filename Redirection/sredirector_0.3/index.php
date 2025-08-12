<?
/*
Seraph Redirector
Author: Ryan Ong <Snobord787@msn.com>
Copyright (c): 2003 Ryan Ong, all rights reserved
Version: 0.3
Site: sredirector.sourceforge.net
Updated: 10/30/03
 * This Script is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License (GPL)
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * You should have received a copy of the GNU General Public License (GPL)
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
if(!is_file(".htaccess")){
	chmod (getcwd(), 0755);
	if (!$handle = fopen(".htaccess", 'w')) { 
		print "Could not Create .htaccess<br />Create a file called .htaccess with \"ErrorDocument 404 ".$_SERVER['PHP_SELF']."\" in it."; 
		exit; 
	} 

	if (!fwrite($handle, "ErrorDocument 404 ".$_SERVER['PHP_SELF'])) { 
		print "Cannot write to .htaccess.<br /> Please edit or create a file called .htaccess with \"ErrorDocument 404 ".$_SERVER['PHP_SELF']."\" in it."; 
		exit; 
	} 
    
	print "Success, wrote .htaccess reload page to test."; 
	fclose($handle); 
	exit;
}
require "config.php";

$path = $_SERVER["QUERY_STRING"];

/*-----------------------------------------------------------
www.php.net/fsockopen
This will use Fsockopen to load if not it will use fopen
-----------------------------------------------------------*/
$relayerror = 0;
if($relay && function_exists("fsockopen")){
	/*------------------------
	Define Protocals and Ports
	--------------------------*/
	if($protocal = "http://"){
		$port = "80";
		$protocal = "";
	}elseif($protocal = "ftp://")
	$port = "21";
	elseif($protocal = "https://" || $protocal = "ssl://"){
		$port = "443";
		$protocal = "ssl://";
	}
	
	/*-------------------------
	Get headers sent
	--------------------------*/
	$headers_sent = apache_request_headers();
	
	/*-------------------------
	Fetch and Build Post String
	Taken From Snoopy(http://snoopy.sourceforge.com)
	--------------------------*/
	if($headers_sent["Content-type"]=="application/x-www-form-urlencoded"){
		reset($_POST);
		while(list($key,$val) = each($_POST)) {
			if (is_array($val) || is_object($val)) {
				while (list($cur_key, $cur_val) = each($val)) {
					$postdata .= urlencode($key)."[]=".urlencode($cur_val)."&";
				}
			} else
			$postdata .= urlencode($key)."=".urlencode($val)."&";
		}
	}elseif(preg_match("/multipart\/form-data/i",$headers_sent["Content-type"])){
		$boundary = explode(";",$headers_sent["Content-type"]);
		$boundary = "$".substr($headers_sent["Content-type"],1).";";
		eval($boundary);
		reset($_POST);
		while(list($key,$val) = each($_POST)) {
			if (is_array($val) || is_object($val)) {
				while (list($cur_key, $cur_val) = each($val)) {
					$postdata .= "--".$boundary."\r\n";
					$postdata .= "Content-Disposition: form-data; name=\"$key\[\]\"\r\n\r\n";
					$postdata .= "$cur_val\r\n";
				}
			} else {
				$postdata .= "--".$boundary."\r\n";
				$postdata .= "Content-Disposition: form-data; name=\"$key\"\r\n\r\n";
				$postdata .= "$val\r\n";
			}
		}
		
		reset($_FILES);
		while (list($field_name, $file_names) = each($_FILES)) {
			settype($file_names, "array");
			while (list(, $file_name) = each($file_names)) {
				if (!is_readable($_FILES[$field_name]['tmp_name'])) continue;
				
				$fp = fopen($_FILES[$field_name]['tmp_name'], "r");
				$file_content = fread($fp, $_FILES[$field_name]['size']);
				fclose($fp);
				$base_name = $_FILES[$field_name]['name'];
				
				$postdata .= "--".$boundary."\r\n";
				$postdata .= "Content-Disposition: form-data; name=\"$field_name\"; filename=\"$base_name\"\r\n\r\n";
				$postdata .= "$file_content\r\n";
			}
		}
		$postdata .= "--".$boundary."--\r\n";
	}
	
	/*-------------------------
	Open Connection
	--------------------------*/
	$fp = @fsockopen($protocal.$host, $port, $errno, $errstr, $timeout);
	if(!$fp){
		//error tell us
		$relayerror=1;
		$relayerrortext = "fsockopen ERROR: $errstr ($errno)";
	}else{
		/*-------------------------
		Build headers sent
		--------------------------*/
		$request="";
		foreach($headers_sent as $k => $v){
			$request.=$k." ".$v."\r\n";
		}
		/*-------------------------
		Insert Break between header
		and body
		--------------------------*/
		$request .="\r\n";
		
		/*-------------------------
		Build Post Data
		--------------------------*/
		$request .=$postdata;
		
		/*-------------------------
		Send All built headers
		--------------------------*/
		fwrite($fp, $request);
		
		/*-------------------------
		Get Reply Data(headers and html)
		--------------------------*/
		// Get Headers by line
		$headers = Array();
		while(!feof($fp)) {
			$header = trim(fgets($fp, 128));
			if($header == "")
			break;
			$headers[] = $header;
		}
		
		// Get all data
		$data="";
		while(!feof($fp))
		$data .= fread($fp, 4096);
		
		/*-------------------------
		Close Connection nothing
		else is needed.
		--------------------------*/
		fclose($fp);
		
		/*-------------------------
		Print headers and content
		--------------------------*/
		// Printing Headers
		foreach($headers as $v){
			header($v);
		}
		
		// Printing Content
		echo $data;
	}
}
/*-------------------------
check if allow_url_fopen is true,
and if fsockopen failed
--------------------------*/
if($relay && ini_get('allow_url_fopen') && $relayerror){
	// Opens connection
	$handle = @fopen ($protocal.$url.$path, "r");
	if(!$handle){
		// fetch data
		$content="";
		do {
			$data = @fread($handle, 8192);
			if (strlen($data) == 0) {
				break;
			}
			$content.= $data;
		} while(true);
		fclose ($handle);
		echo $content."<!-- $relayerrortext -->";
		$relayerror=0;
	}else
	$relayerror=1;
}else
$relayerrortext.="\nfopen ERROR: php.ini setting allow_url_fopen is set to false";	


/*-----------------------------------------------------------
This will use framesets
-----------------------------------------------------------*/

if($frameset || $relayerror){
	echo <<<FRAME
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
<title>$title</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/javascript">
<!--
function redirect(url)
	{ location.href = url.options[url.selectedIndex].value; }
//-->
</script>
</head>
<frameset frameborder="NO" border="0" framespacing="0">
  <frame src="http://$protocal$host$path">
</frameset>
<noframes><body onload="redirect($protocal$host$path);">
You need Frames :P
</body></noframes>
</html>
<!-- $relayerrortext -->
FRAME;
}

/*-----------------------------------------------------------
This will redirect using headers
-----------------------------------------------------------*/
if($redirect){
	// Uses header to Redirect
	@header("Refresh: ".$time.";url=".$protocal.$url.$path);
	// Print Redirection if Time is greater than 0.
	if($time > 0){
		print template();
	}
}
?>