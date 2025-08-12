<?PHP
error_reporting(E_ERROR);

include ("queries.php");
?>

<HTML>

<HEAD>

<TITLE>EKINboard - Update</TITLE>

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

<center><form action=update.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><img src="images/install_top_1.gif"></td></tr>

<tr><td colspan=2><span class=hilight>UPDATE EKINboard tables<Br><br></span></td></tr>

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
					foreach($update as $key){
						@mysql_query($key);
					}

					if (is_writable('../db_info.php')) {
					
						$contents = file_get_contents('../db_info.php');

						$cont = fopen('../db_info.php','w+');

						$contents = str_replace("<{db_host}>", $db_host, $contents);

						$contents = str_replace("<{db_user}>", $db_user, $contents);

						$contents = str_replace("<{db_pass}>", $db_pass, $contents);

						$contents = str_replace("<{db_name}>", $db_name, $contents);

						fwrite($cont,$contents);
						fclose($cont);

						$config_edited = TRUE;
					}

					echo "<center>

						<table width=400 cellspacing=\"0\"><tr><td>

						<img src=\"images/install_top_2.gif\">

						</td></tr></table><p><table width=400 cellspacing=\"0\" class=\"bluetable\"><tr><td class=\"redtable_content\">

							- The database tables have been updated";

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

<center><form action=update.php method=post>

<table border=0 cellpadding=0 cellspacing=0 width=400>

<tr><td colspan=2 height=10></td></tr>

<tr><td colspan=2><img src="images/install_top_1.gif"></td></tr>

<tr><td colspan=2><span class=hilight>UPDATE EKINboard tables<Br><br></span></td></tr>

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

}



?>