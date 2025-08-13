<?
include ("inc.php");
$cod = preg_replace ($disallowed_symbols, "", $HTTP_GET_VARS["code"]);
if (mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error")){
	mysql_select_db($db_name);
	$try = mysql_query ("SELECT * FROM $changes_table WHERE code='$cod'") or die ("Bad query");
	$num_Rows = mysql_num_rows ($try);
	if ($num_Rows != 1) {
		$template = "something_wrong.htm";
	} else {
		$requested = mysql_fetch_assoc($try);
		$stored =$requested["store"];
		$id = $requested["id"];
		$current_mail = $requested["email"];
		if ($stored == "password") {
			$new_pas = rand_string(8);
			$sql_update = "Update $users_table set password= '".md5($new_pas)."' WHERE userid=$id";
			$message = "Hello !\n\n";
			$message .= "Your login information for $protected_url was changed\n\n";
			$message .= "Your new password is: $new_pas\n\n";
			$message .= "Please keep your password in safe place.\n";
			$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
			$headers = "From: Web Sites Protection System<$webmaster_mail>\n";
			$headers .= "X-Sender: <$webmaster_mail>\n"; 
			$headers .= "X-Mailer: PHP\n";
			$headers .= "X-Priority: 1\n";
			$headers .= "Return-Path: <$webmaster_mail>\n";  
			mail ($current_mail,"Your password was changed",$message,$headers);
			$template = "password_changed.htm";
		} else {
			session_start();
			session_unregister ("current_mail");
			$current_mail = $stored;
			session_register ("current_mail");
			$sql_update = "Update $users_table set email= '$stored' WHERE userid=$id";
			$template = "email_changed.htm";
		}
		mysql_query ($sql_update) or die ("Bad query");
		mysql_query ("DELETE FROM $changes_table WHERE code='$cod'") or die ("Bad query");
		mysql_close();
	}
} else {
	$template = "something_wrong.htm";
}
echo preg_replace("~#([a-z_]+)#~ie","$\\1",read_template($template));
?>
