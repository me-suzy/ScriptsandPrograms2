<?php
// Database Connection Details
$server = 'localhost';
$database = '';
$db_user = '';
$db_pass = '';

// You do not need to edit anything below

$db = mysql_connect($server, $db_user, $db_pass);
mysql_select_db($database,$db);

	// Getting array for options
	$resultlink = mysql_query("SELECT * FROM `multiforums_settings`",$db);
	do
	{
		switch ($settings[v_name]) {
			case "mf_url":
				$mf_url = $settings["value"];
				break;
			case "mf_path":
				$mf_path = $settings["value"];
				break;
			case "email":
				$email = $settings["value"];
				break;
			case "password":
  				$password = $settings["value"];
				break;				
			case "noforum_error_url":
 				$noforum_error_url = $settings["value"];
				break;
			case "exist_error_url":
 				$exist_error_url = $settings["value"];
				break;
			case "offline_error_url":
				$offline_error_url = $settings["value"];
				break;				
			case "no_posts":
 				$no_posts = $settings["value"];
				break;
			case "no_admin_login":
 				$no_admin_login = $settings["value"];
				break;
			case "top_10":
 				$top_10 = $settings["value"];
				break;
			case "email_new":
				$email_new = $settings["value"];								
				break;
			case "auto_cache_time":
				$auto_cache_time = $settings["value"];
				break;
			case "copyright":
				$copyright = $settings["value"];
				break;
			case "reg_no":
				$reg_no = $settings["value"];
				break;
			case "master_offline":
				$master_offline = $settings["value"];
				break;
			case "last_cache":
				$last_cache = $settings["value"];
				break;																
			case "version":
				$version = $settings["value"];  
				break;
			case "latest_version":
				$latest_version = $settings["value"];
				break;																				
		}
	} while ($settings = mysql_fetch_array($resultlink)); 



?>
