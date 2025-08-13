<?
if (file_exists ("install.php")) {
	echo "Please remove install.php from server and try again.";
} else {

if (!$HTTP_POST_VARS["form_username"] || !$HTTP_POST_VARS["form_password"]) {
	header ("Location: $login_page");
}
session_start();
include ("inc.php");
$failed = 0;
$allow = "";
$welcome = "";
$isadmin = 0;
$user_name= preg_replace ($disallowed_symbols, "", $HTTP_POST_VARS["form_username"]);
$web_pass = preg_replace ($disallowed_symbols, "", $HTTP_POST_VARS["form_password"]);
if ($use_js_encode == 1) {
	include ("rsa.php");
	$pass_word = rsa_decrypt($web_pass, $HTTP_SESSION_VARS["key2"], $HTTP_SESSION_VARS["key1"]);
} else {
	$pass_word = $web_pass;
}
if (mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error")){
	mysql_select_db($db_name);
	$proxy_detected = 2;
	$sql_look = "SELECT proxy_ip FROM $proxy_table WHERE proxy_ip='$full_ip' OR proxy_ip='$user_ip'";
	$proxy_check = mysql_query($sql_look) or die ("Bad query");
	if (mysql_num_rows ($proxy_check) > 0) {
		$proxy_detected = 1;
	} elseif ($proxy_deny == 1) {
		foreach ($HTTP_SERVER_VARS as $name => $value) {
			// paranoid way - checking if HTTP_CONNECTION exixts and its value is keep-alive
			/*$connection_found = 2;
			if (preg_match ("~HTTP_CONNECTION~", $name)){
				$connection_found = 1;
				if (!eregi ("keep.alive", $value)) {
					$proxy_detected = 1;
				}
			}*/
			if (preg_match("~SPILL|VIA|PROXY|FORWARDED|CACHE~",$name)) {
				$proxy_detected = 1;
			}
		}
	}
	// paranoid way second step
	/*if ($connection_found == 2) {
			$proxy_detected = 1;
	}*/
	if ($proxy_detected == 2) {
		$sql_look = "SELECT * FROM $users_table WHERE username='$user_name'";
		$user_record = mysql_query ($sql_look) or die ("Bad query");
		$num_Rows = mysql_num_rows ($user_record);
		if ($num_Rows != 1) {
			$failed=1;
		} else {
			$user = mysql_fetch_assoc($user_record);
			if (md5 ($pass_word) != $user["password"]){
				$failed=1;
			} elseif ($user["admin"] == 1) {
				//welcome administrator
				$allow = $user_ip;
				$isadmin = 1;
				session_register ("allow");
				session_register ("isadmin");
				/*don't look here - sorry :)
				$admin_ip_str .= "allow from $user_ip";
				$acc_file_str = preg_replace("~#([a-z_]+)#~ie","$\\1",read_template("admin_htaccess.txt"));
				$acc_file =fopen("admin/.htaccess","w");
				fwrite($acc_file,$acc_file_str);
				fclose($acc_file); */
			} else {
				//welcome user
				$welcome = $user_ip;
				$user_id = $user["userid"];
				$real_name = $user["real_name"];
				$current_mail = $user["email"];
				$current_user = $user["username"];
				session_register ("welcome");
				session_register ("user_id");
				session_register ("real_name");
				session_register ("current_mail");
				session_register ("current_user");
				if ($c_time - $rotation_timeout < $user["LLT"] && $user_ip != $user["LLIP"]) {
					$rotation = $user["rotation"]+ 1;
					if ($rotation > 4) {
						$rotation_count = $user["rotation_count"]+ 1;
						$newpassword = rand_string(8);

						//sending mail
						$message = "Hello $real_name !\n\n";
						$message .= "Due to security alert your login information for $protected_url was changed\n\n";
						$message .= "Your username is: $current_user ";
						$message .= "\nYour new password is: $newpassword\n\n";
						$message .= "Reason: More then 3 $sessions_table from different IP-s using same login information during limited time period (".($rotation_timeout/60)." minutes).\n\n";
						$message .= "Please keep your password in safe place.\n";
						$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
						$headers = "From: Web Sites Protection System<$webmaster_mail>\n";
						$headers .= "X-Sender: <$webmaster_mail>\n"; 
						$headers .= "X-Mailer: PHP\n";
						$headers .= "X-Priority: 1\n";
						$headers .= "Return-Path: <$webmaster_mail>\n";  
						mail ($current_mail,"Your password was changed",$message,$headers);
						//end of sending mail

						$sql_log = "UPDATE $users_table SET LLT=$c_time,LLIP='$user_ip',rotation=0,rotation_count=$rotation_count,password= '".md5($newpassword )."' WHERE userid=$user_id";
					} else {
						$sql_log = "UPDATE $users_table SET LLT=$c_time,LLIP='$user_ip',rotation=$rotation WHERE userid=$user_id";
					}
				} else {
					$sql_log = "UPDATE $users_table SET LLT=$c_time,LLIP='$user_ip' WHERE userid=$user_id";
				}
				mysql_query ($sql_log) or die ("Bad query");
				$temp_user = strtolower(rand_string(10));
				$temp_pas = strtolower(rand_string(10));
				$temp_salt = strtolower(rand_string(2));
				$temp_pas_cr = crypt($temp_pas,$temp_salt);
				$sql_clear_expired_sess = "delete from $sessions_table where userid=$user_id or IP='$user_ip' or LTime<".($c_time-$temp_user_timeout);
				$sql_add_sess = "insert into $sessions_table (userid,IP,TPass,TUser,LTime) values ($user_id,'$user_ip','$temp_pas_cr','$temp_user',$c_time)";
				$sql_get_sess = "select IP,TPass,TUser from $sessions_table";
				mysql_query ($sql_clear_expired_sess) or die ("Bad query");
				mysql_query ($sql_add_sess) or die ("Bad query");
				$sess_list = mysql_query ($sql_get_sess) or die ("Bad query");
				while ($row = mysql_fetch_assoc($sess_list)) {
					$pas_str .= $row["TUser"].":".$row["TPass"]."\n";
					$ip_str .= "\nallow from ".$row["IP"]."";
				}
				$pas_file =fopen("$protected_path.hunter","w");
				fwrite($pas_file,$pas_str);
				fclose($pas_file);
				$acc_file_str = preg_replace("~#([a-z_]+)#~ie","$\\1",read_template("htaccess.txt"));
				$acc_file =fopen("$protected_path.htaccess","w");
				fwrite($acc_file,$acc_file_str);
				fclose($acc_file);
				$template = "welcome_member.htm";
			}
		}
	} else {
		$template = "proxy_deny.htm";
	}
	$reply_message = read_template($template);
	mysql_close();
}
if ($isadmin == 1) {
	header ("Location: admin.php");
}elseif ($failed == 1) {
	header ("Location: $login_page");
} else {
	echo preg_replace("~#([a-z_]+)#~ie","$\\1",$reply_message);
}

}
?>