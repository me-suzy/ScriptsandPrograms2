<?
include ("inc.php");
session_start();
/*Parse session and post data*/
if (session_is_registered("welcome")){
		if ($HTTP_SESSION_VARS["welcome"] == $HTTP_SERVER_VARS["REMOTE_ADDR"]){
			$code = strtolower(rand_string(12));
			$message = "Hello $real_name !\n\n";
			$message .= "You requested change of login information for $protected_url\n\n";
			$headers = "From: Web Sites Protection System<$webmaster_mail>\n";
			$headers .= "X-Sender: <$webmaster_mail>\n"; 
			$headers .= "X-Mailer: PHP\n";
			$headers .= "X-Priority: 1\n";
			$headers .= "Return-Path: <$webmaster_mail>\n";  
			if ($action == "email") {
				$e_mail = preg_replace ($mail_disallowed_symbols, "", $HTTP_GET_VARS["new_email"]);
				if (!preg_match ("/^\w+((\-|\.)\w+)*@\w+((\-|\.)\w+)*\.[A-z0-9]{2,}$/", $e_mail)) {
					$template = "something_wrong.htm";
				} else {
					$message .= "If you really want to change your e-mail please confirm it and open this link in browser.\n\n";
					$message .= "http://".$this_server.$auth_web_path."activate.php?code=$code\n\n";
					$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
					mail ($e_mail,"Conformation request",$message,$headers);
					$template = "change.htm";
					$sql = "INSERT INTO $changes_table (id,code,dDate,store,email) VALUES ($user_id,'$code',".time().",'$e_mail','$current_mail')";
				}
			}elseif ($action == "password"){
				$message .= "If you really want to change your password please confirm it and open this link in browser.\n\n";
				$message .= "http://".$this_server.$auth_web_path."activate.php?code=$code\n\n";
				$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
				mail ($current_mail,"Conformation request",$message,$headers);
				$template = "change.htm";
				$sql = "INSERT INTO $changes_table (id,code,dDate,store,email) VALUES ($user_id,'$code',".time().",'password','$current_mail')";
			}
			if (mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error")){
				mysql_select_db($db_name);
				mysql_query ("DELETE FROM $changes_table WHERE id=$user_id") or die ("Bad query");
				mysql_query ($sql) or die ("Bad query");
				mysql_close();
			}
			echo preg_replace("~#([a-z_]+)#~ie","$\\1",read_template($template));
		} else {
				session_unregister ("welcome");
				header ("Location: $login_page");
		}
} else {
		header ("Location: $login_page");
}
?>
