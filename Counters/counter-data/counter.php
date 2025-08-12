<?php
/*********************************************************
			This Free Script was downloaded at			
			Free-php-Scripts.net (HelpPHP.net)			
	This script is produced under the LGPL license		
		Which is included with your download.			
	Not like you are going to read it, but it mostly	
	States that you are free to do whatever you want	
				With this script!						
*********************************************************/

//Connect to database
//include_once("connect.php");

//Site defines
//Set count type:
// 1 --> unique, only count each ip one time
// 2 --> All, count hits, ip not a problem

$type = 1;

//Function to get user ip (stolen from phpskills.com :)
function get_user_ip(){       
	$ipParts = explode(".", $_SERVER['REMOTE_ADDR']);
	if ($ipParts[0] == "165" && $ipParts[1] == "21") {    
    	if (getenv("HTTP_CLIENT_IP")) {
        	$ip = getenv("HTTP_CLIENT_IP");
        } elseif (getenv("HTTP_X_FORWARDED_FOR")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } elseif (getenv("REMOTE_ADDR")) {
            $ip = getenv("REMOTE_ADDR");
        }
    } else {
       return $_SERVER['REMOTE_ADDR'];
   	}
   	return $ip;
}

// Use this to check that the ip was already entered into the database (no entry is made)
$match = false;
$user_ip = get_user_ip();
$get_user = @mysql_query("SELECT * FROM ips WHERE `ip`='$user_ip'");

if(@mysql_num_rows($get_user) > 0){
	$match = true;
} else {
	$insert_ip = @mysql_query("INSERT  INTO ips (`ip`) VALUES ('$user_ip')");
}	

// Check Type Kind		
if($type == 2 || ($type == 1 && $match == false)){
	$get_last_hit = @mysql_fetch_array(@mysql_query("SELECT * FROM myhits WHERE `name`='frontpage'"));
	$get_last_hit[1]++;

	$update_count = @mysql_query("UPDATE myhits SET hits='$get_last_hit[1]' WHERE name='frontpage'");
} else {
	$get_last_hit = @mysql_fetch_array(@mysql_query("SELECT * FROM myhits WHERE `name`='frontpage'"));
}

// It is safe to change these information to your needs
if($type == 2){
	echo 'Page\'s Total hits: ' . $get_last_hit[1];
} else {
	echo 'This Page Has: ' . $get_last_hit[1] . ' Unique hits!';
}

echo "<!--
/*********************************************************
			This Free Script was downloaded at			
			Free-php-Scripts.net (HelpPHP.net)			
	This script is produced under the LGPL license		
		Which is included with your download.			
	Not like you are going to read it, but it mostly	
	States that you are free to do whatever you want	
				With this script!						
*********************************************************/
-->";
?>