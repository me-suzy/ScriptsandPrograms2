<?php
// Yoursite.nu Plugboard 1.0
// Created by Linda - Please see http://www.yoursite.nu/mbforum.php?id=8 if you need help
// Do not redistribute this code
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<body style="margin:0px; font-family:sans-serif;">
<?php
$filename = 'install.php';
if (file_exists($filename)) {
   echo "<font color=red>You must delete install.php before using your plugboard</font>";
	exit;
} 
else {


include ("plug_settings.php");

mysql_connect ($conf['mysql_host'], $conf['mysql_user'], $conf['mysql_pass']); 
mysql_select_db($conf['mysql_db']);


// Add button if needed
if ($action =="plug") {
	$url = $_POST["url"];  
	$button = $_POST["button"];  
	$ip = $_POST["ip"];  

	if (!$url || !$button) {
	}
	else {

	// Check IP isn't banned
	if ($ipcheck == "true") {
	$sql_ipcheck = mysql_query("SELECT * FROM banned WHERE ip = '$ip'");
	$ipcheck = mysql_numrows ($sql_ipcheck);
	if ($ipcheck > 0) {
	echo "Your IP is banned from this plugboard";
	exit;
	}
	}

	// Check for malicious code - only allows a-z, 0-9 : / - _ ? and .
	function is_alphanumeric($test) {
	return (preg_match("/^[a-zA-Z0-9(\.)(\/)(\:)(\-)(\_)(\?)]+$/i", $test));
	}
	if ( !is_alphanumeric($url) ||  !is_alphanumeric($button)) {
	echo "Your url or button contains invalid characters";
	exit;
	}


	// Check Button and Site URL for http://
	$pre = "http://";

		if(preg_match("/^(http:\/\/)/i", $url)){
		}
		else{
			$url = "$pre$url";
		}

		if(preg_match("/^(http:\/\/)/i", $button)){
		}
		else{
			$button = "$pre$button";
		}




	// Check URL isn't already plugged		
	$sql_urlcheck = mysql_query("SELECT * FROM plugboard WHERE url = '$url'");
	$urlcheck = mysql_numrows ($sql_urlcheck);




	// Check URL isn't already plugged with(out) www.
	$www = "www.";
	if (preg_match("/$www/", "$url")) {
		$wwwurl = $url;
		$wwwurl = str_replace("http://www.","http://",$wwwurl); 
		$sql_wwwcheck = mysql_query("SELECT * FROM plugboard WHERE url = '$wwwurl'");
		$wwwcheck = mysql_numrows ($sql_wwwcheck);

	} else {

		$wwurl = $url;
		$wwurl = str_replace("http://","http://www.",$wwurl); 
		$sql_wwcheck = mysql_query("SELECT * FROM plugboard WHERE url = '$wwurl'");
		$wwcheck = mysql_numrows ($sql_wwcheck);
	}	
	



	// Check button isn't already plugged		
	$sql_butcheck = mysql_query("SELECT * FROM plugboard WHERE button = '$button'");
	$butcheck = mysql_numrows ($sql_butcheck);

	// display error if button or url has already been plugged
	if ($urlcheck > 0 || $butcheck > 0 || $wwwcheck > 0 || $wwcheck > 0) {
		echo "Your URL or Button is already on the plug board";
		exit;
	}



	// IP Check
	if ($ipcheck == "true") {
		$sql_ipcheck = mysql_query("SELECT * FROM plugboard ORDER BY plug_id DESC LIMIT 1");
		while($ipout = mysql_fetch_array($sql_ipcheck)) { 
		$latest_ip = $ipout[ip];
		if ($latest_ip == $ip) {
			echo "You cannot post twice in a row";
			exit;
		}
	}
}




	// Add Button
	mysql_query("INSERT INTO plugboard(button, url, ip) VALUES ('$button', '$url','$ip')");


}		
} 	

// Delete old plugs
$sql = mysql_query("SELECT * FROM plugboard ORDER BY plug_id DESC LIMIT 1");
$number = mysql_numrows ($sql);
if ($number == 0) {
}
else {

	while($out = mysql_fetch_array($sql)) { 
		$id = $out[plug_id];
		$nid = $id - $limit;
		mysql_query("DELETE from plugboard WHERE plug_id < '$nid'");
	}

}


// Display plugs
$sqlbuttons = mysql_query("SELECT * FROM plugboard ORDER BY plug_id DESC LIMIT $limit");
	while($outbuttons = mysql_fetch_array($sqlbuttons)) { 
		$url = $outbuttons[url];
		$button = $outbuttons[button];


	echo "<a href=\"$url\" target=\"_blank\"><img src=\"$button\" height=\"$button_height\" width=\"$button_width\" alt=\"$url\" border=\"0\"></a> ";
	}

}

?>
</body>
</html>