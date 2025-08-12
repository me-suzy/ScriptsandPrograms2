<?PHP

ob_start(); 

session_start();

if (is_writable('../db_info.php')) {
	$edit_config = TRUE;

	$contents = file_get_contents('../db_info.php');
}

include ("queries.php");

?>

<HTML>

<HEAD>

<TITLE>EKINboard - Install</TITLE>

<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">

<link rel=stylesheet type=text/css href=../templates/default/style.css>

</HEAD>

<BODY LEFTMARGIN=0 TOPMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

<?PHP

if(isset($_POST['tables'])){

	$db_host = $_POST['db_host'];

	$db_user = $_POST['db_user'];

	$db_pass = $_POST['db_pass'];

	$db_name = $_POST['db_name'];





	if((!$db_host) || (!$db_user) || (!$db_name)){

		if(!$db_host){

			$db_host = 'localhost';

			$host_error = 1;

			$host_error_message = "Host address was automatically changed to 'localhost.'";

		}

		if(!$db_user){

			$user_error = 1;

			$user_error_message = "Your username is missing!";

		}

		if(!$db_name){

			$name_error = 1;

			$name_error_message = "Please enter your database name!";

		}

?>

<center><form action=index.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><img src="images/install_top_1.gif"></td></tr>

<tr><td colspan=2><span class=hilight>Create EKINboard tables<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $host_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $host_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Host (Usually localhost): </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_host value="<?PHP echo $db_host; ?>">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $user_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $user_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Username: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_user value="<?PHP echo $db_user; ?>">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $pass_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $pass_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Password: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=textbox name=db_pass value="<?PHP echo $db_pass; ?>">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $name_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $name_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Name: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_name value="<?PHP echo $db_name; ?>">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td align=right><input type=submit name=tables value="Continue >>"></td></tr>

</table></center>

<?PHP

	} else {

		$connect = mysql_connect($db_host,$db_user,$db_pass) or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");

		if ($connect) {

			$db = mysql_select_db($db_name,$connect) or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");

			if ($db) {

					foreach($query as $key){

						@mysql_query($key);

					}

					$forum_location = str_replace("/install/index.php", '', "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

					@mysql_query("INSERT INTO settings (name, value) VALUES ('main_location', '$forum_location')") or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");



?>

<center>

<table width=400 cellspacing="0"><tr><td>

<img src="images/install_top_2.gif">

</td></tr></table><p>

<table width=400 cellspacing="0" class="bluetable"><tr><td class="redtable_content">

Congradulations!  Your database has been updated.<p>Now you need to create a default administration account.

</td></tr></table><p>

<form action=index.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><span class=hilight>Create Administration Account<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $host_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $host_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											E-Mail Address: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=admin_email value="<?PHP echo $email; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $user_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $user_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Username: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=admin_username value="" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $pass_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $pass_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Password: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=text name=admin_password value="" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $pass_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $pass_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Password (confirm): </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=text name=admin_confirm value="" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td colspan=2><span class=hilight>Forum Settings<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $forum_organization_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $forum_organization_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Forum Organization: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=forum_organization value="<?PHP echo $forum_organization; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $forum_email_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $forum_email_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Forum Email: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=forum_email value="" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<input type=hidden name=admin_db_host value="<?PHP echo $db_host; ?>">

<input type=hidden name=admin_db_user value="<?PHP echo $db_user; ?>">

<input type=hidden name=admin_db_pass value="<?PHP echo $db_pass; ?>">

<input type=hidden name=admin_db_name value="<?PHP echo $db_name; ?>">

<tr><td align=right><input type=submit name=admin value="Continue >>"></td></tr>

</table></center>

<?PHP



				mysql_close();

			}

		}

	}

} else if(isset($_POST['admin'])){

	$admin_email = $_POST['admin_email'];

	$admin_username = $_POST['admin_username'];

	$admin_password = $_POST['admin_password'];

	$admin_confirm = $_POST['admin_confirm'];



	$admin_db_host = $_POST['admin_db_host'];

	$admin_db_user = $_POST['admin_db_user'];

	$admin_db_pass = $_POST['admin_db_pass'];

	$admin_db_name = $_POST['admin_db_name'];


	$forum_organization = $_POST['forum_organization'];

	$forum_email = $_POST['forum_email'];
	

	if((!$admin_email) || (!$admin_username) || (!$admin_password) || (!$admin_confirm) || ($admin_password != $admin_confirm) || (!$forum_organization) || (!$forum_email)){

		if(!$admin_email){

			$email_error = 1;

			$email_error_message = "You need to enter an email address!";

		}

		if(!$admin_username){

			$user_error = 1;

			$user_error_message = "Please choose a username.";

		}

		if((!$admin_password) || (!$admin_confirm)){

			if(!$admin_password){

				$pass_error = 1;

				$pass_error_message = "Please choose a password.";

			}

			if(!$admin_confirm){

				$conf_error = 1;

				$conf_error_message = "Please re-type your password.";

			}

		} else if($admin_password != $admin_confirm){

				$conf_error = 1;

				$conf_error_message = "Your passwords no not match!";

		}

		if(!$forum_organization){

			$forum_organization_error = 1;

			$forum_organization_error_message = "You need to enter an organization for your forum!";

		}

		if(!$forum_email){

			$forum_email_error = 1;

			$forum_email_error_message = "You need to enter a forum email address";

		}

		?>

<center><form action=index.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><img src="images/install_top_2.gif"></td></tr>

<tr><td colspan=2><span class=hilight>Create Administration Account<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $email_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $email_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											E-Mail Address: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=admin_email value="<?PHP echo $admin_email; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $user_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $user_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Username: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=admin_username value="<?PHP echo $admin_username; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $pass_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $pass_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Password: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=text name=admin_password value="<?PHP echo $admin_password; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $conf_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $conf_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Password (confirm): </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=text name=admin_confirm value="<?PHP echo $admin_confirm; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td colspan=2><span class=hilight>Forum Settings<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $forum_organization_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $forum_organization_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Forum Organization: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=forum_organization value="<?PHP echo $forum_organization; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>

<tr><td height=5>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $forum_email_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $forum_email_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Forum Email: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=text name=forum_email value="<?PHP echo $forum_email; ?>" size=20>

										</td>

									</tr>

								</table>

</td></tr>
<input type=hidden name=admin_db_host value="<?PHP echo $admin_db_host; ?>">

<input type=hidden name=admin_db_user value="<?PHP echo $admin_db_user; ?>">

<input type=hidden name=admin_db_pass value="<?PHP echo $admin_db_pass; ?>">

<input type=hidden name=admin_db_name value="<?PHP echo $admin_db_name; ?>">

<tr><td align=right><input type=submit name=admin value="Continue >>"></td></tr>

</table></center>

<?PHP

	} else {

			$connect = @mysql_connect($admin_db_host,$admin_db_user,$admin_db_pass);

			if ($connect) {

				$db = @mysql_select_db($admin_db_name,$connect);

				if ($db) {
					if($edit_config == TRUE){
						$cont = fopen('../db_info.php','w+');

						$contents = str_replace("<{db_host}>", $admin_db_host, $contents);

						$contents = str_replace("<{db_user}>", $admin_db_user, $contents);

						$contents = str_replace("<{db_pass}>", $admin_db_pass, $contents);

						$contents = str_replace("<{db_name}>", $admin_db_name, $contents);

						fwrite($cont,$contents);
						fclose($cont);

						$config_edited = TRUE;
					}


					$admin_password = md5($admin_password);

					$result = MYSQL_QUERY("INSERT INTO users (email,username,password,signup_date,activated,level)

						VALUES ('$admin_email', '$admin_username', '$admin_password', now(), '1', '3')") or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");

					$result = MYSQL_QUERY("INSERT INTO settings (name, value)

						VALUES ('main_email', '$forum_email')") or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");

					$result = MYSQL_QUERY("INSERT INTO settings (name, value)

						VALUES ('organization', '$forum_organization')") or die("

					<center>

						<table width=400 cellspacing=0 class=redtable>

							<tr><td class=redtable_header><b>Error</b></td></tr>

							<tr><td class=redtable_content>

						Error returned:<br>

						<i>". mysql_error() ."</i><p>

						Please press the back button and try again!

					</center>");

					$submitted_title = "Welcome to EKINboard!";
					$submitted_message = "Everyone from EKINboard would like to thank you for downloading our message board!\r\n\r\nWe have spent a great ammount of time working on this software for you to enjoy, FREE!\r\nIf you need any help at all please visit ekinboard's website.\r\n\r\nEKINboard Team";

					$t_date = date("Y-m-d");

					$t_time = date("H:i:s a");

					$t_datetime = $t_date ." ". $t_time;

	

					$result=MYSQL_QUERY("INSERT INTO topics (fid,title,description,message,poster,date,last_post,datesort)".

					"VALUES ('1', '$submitted_title', '', '$submitted_message', '$admin_username', '$t_date', '$t_date', '$t_datetime')"); 


					echo "<center>

						<table width=400 cellspacing=\"0\"><tr><td>

						<img src=\"images/install_top_2.gif\">

						</td></tr></table><p>

						<table width=400 cellspacing=\"0\" class=\"bluetable\"><tr><td class=\"redtable_content\">

						Dear User,<br>

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;We at EKINboard would like to thank you for installing EKINboard on your website.  We hope that it is everything that you have expected and more!<br>

						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;If you have any questions at all, please do not hesitate to ask.<br>

						<b>EKINboard Staff</b>

						</td></tr></table><p><table width=400 cellspacing=\"0\" class=\"bluetable\"><tr><td class=\"redtable_content\">

							- The database tables have been created<br>

							- The administrators account has been made";



						if($config_edited == TRUE){

							echo "<br>- Your config file has been configured";

						}

						echo "</td></tr></table><p><table width=400 cellspacing=\"0\" class=\"redtable\"><tr><td class=\"redtable_content\">

						Please delete the install directory for security purposes!

						</td></tr></table><p></center>";

				} else {

				echo "<center><span class=red>Could not connect to the database!</span></center>";

			}

			} else {

				echo "<center><span class=red>Could not connect!</span></center>";

			}

	}

} else {

?>

<center><form action=index.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><img src="images/install_top_1.gif"></td></tr>

<tr><td colspan=2><span class=hilight>Create EKINboard tables<Br><br></span></td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $host_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $host_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Host (Usually localhost): </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_host value="localhost">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $user_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $user_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Username: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_user value="">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $pass_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $pass_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Password: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=password class=textbox name=db_pass value="">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td>

								<table cellspacing="0" class="legend_<?PHP echo $name_error; ?>" width=100%>

									<tr>

										<td valign=middle class=redtable_content colspan=2>

											<span class="error"><?PHP echo $name_error_message; ?></span>

										</td>

									</tr>

									<tr>

										<td valign=middle width=50% class=redtable_content>

											Database Name: </td>

										<td valign=middle width=50% class=redtable_content>

											<input type=text class=textbox name=db_name value="">

										</td>

									</tr>

								</table>

</td></tr>

<tr><td align=right><input type=submit name=tables value="Continue >>"></td></tr>

</table></center>

<?PHP

}



?>