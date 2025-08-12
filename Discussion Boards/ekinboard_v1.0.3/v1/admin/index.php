<?PHP

include '../config.php';

include '../updateonline.php';



$page_name = "Admin Panel";

updateonline('0','0','0', $_userid, $_username, $page_name);



$get_current_version = @file_get_contents("http://www.ekinboard.com/version.php");

if(!$get_current_version){
	$update_error = 1;

	$table_color = "red";
}

if($get_current_version != $_version){

	$need_update = 1;

	$table_color = "red";

} else {

	$need_update = 0;

	$table_color = "blue";

}

?>

<HTML>

	<HEAD>

		<TITLE>

			Administration Panel - (Powered by EKINboard)

		</TITLE>

		<link rel="stylesheet" type=text/css href=style.css>

	</HEAD>

	<BODY LEFTMARGIN=0 TOPMARGIN=5 MARGINWIDTH=0 MARGINHEIGHT=0>

		<?PHP

		if($_userlevel == 3){



			$total_users_result = mysql_query("SELECT * FROM users");

			$total_users = mysql_num_rows($total_users_result);



			$total_online_result = mysql_query("SELECT * FROM online WHERE guest='0'");

			$total_online = mysql_num_rows($total_online_result);



			$online_users_result = mysql_query("SELECT * FROM online WHERE isonline='1' AND guest='0'");

			$online_users = mysql_num_rows($online_users_result);



			$newest_user_result = mysql_query("select * from users ORDER BY id DESC LIMIT 1");

			$newest_user_row = mysql_fetch_array($newest_user_result);

			$newest_user_id = $newest_user_row['id'];

			$newest_user_name = $newest_user_row['username'];



			$categories_count_result = mysql_query("SELECT * FROM categories");

			$categories_count = mysql_num_rows($categories_count_result);



			$forum_count_result = mysql_query("SELECT * FROM forums");

			$forum_count = mysql_num_rows($forum_count_result);



			$topic_count_result = mysql_query("SELECT * FROM topics");

			$topic_count = mysql_num_rows($topic_count_result);



			$replies_count_result = mysql_query("SELECT * FROM replies");

			$replies_count = mysql_num_rows($replies_count_result);



			$ads_count_result = mysql_query("SELECT * FROM ads");

			$ads_count = mysql_num_rows($ads_count_result);

		?>

		<center>

		<table width="90%">

			<tr>

				<td colspan="2">

					<img src="images/main_top.gif" border=0 alt="">

				</td>

			</tr>

			<tr>

				<td width="205" valign=top>

					<table cellpadding="0" cellspacing="0" class="bluetable" width="200">

						<tr>

							<td class="bluetable_header">

								General Statistics

							</td>

						</tr>

						<tr>

							<td class="bluetable_content">

								<table cellpadding="0" cellspacing="0" width="100%">

									<tr>

										<td>

											<b>Total Members:</b>

										</td>

										<td width="60">

											<a href="index.php?page=users" class="link2"><?PHP echo $total_users; ?></a>

										</td>

									</tr>

									<tr>

										<td>

											<b>Users Online:</b>

										</td>

										<td width="60">

											<a href="index.php?page=online" class="link2"><?PHP echo $online_users; ?></a>

										</td>

									</tr>

									<tr>

										<td>

											<b>Users Online Today:</b>

										</td>

										<td width="60">

											<a href="index.php?page=online" class="link2"><?PHP echo $total_online; ?></a>

										</td>

									</tr>

									<tr>

										<td>

											<b>Newest Member:</b>

										</td>

										<td width="60">

											<a href="../profile.php?id=<?PHP echo $newest_user_id; ?>"><?PHP echo $newest_user_name; ?></a>

										</td>

									</tr>

								</table>

							</td>

						</tr>

					</table>

					<br>

					<table cellpadding="0" cellspacing="0" class="bluetable" width="200">

						<tr>

							<td class="bluetable_header">

								Board Statistics

							</td>

						</tr>

						<tr>

							<td class="bluetable_content">

								<table cellpadding="0" cellspacing="0" width="100%">

									<tr>

										<td>

											<b>Total Categories:</b>

										</td>

										<td width="60">

											<?PHP echo $categories_count; ?>

										</td>

									</tr>

									<tr>

										<td>

											<b>Total Forums:</b>

										</td>

										<td width="60">

											<?PHP echo $forum_count; ?>

										</td>

									</tr>

									<tr>

										<td>

											<b>Total Topics:</b>

										</td>

										<td width="60">

											<?PHP echo $topic_count; ?>

										</td>

									</tr>

									<tr>

										<td>

											<b>Total Replies:</b>

										</td>

										<td width="60">

											<?PHP echo $replies_count; ?>

										</td>

									</tr>

									<tr>

										<td>

											<b>Total Ads:</b>

										</td>

										<td width="60">

											<?PHP echo $ads_count; ?>

										</td>

									</tr>

								</table>

							</td>

						</tr>

					</table>
					<center><a href="../index.php"><img src="images/viewforum_btn.gif" alt="" border="0"></a></center>

				</td>

				<td valign="top" width="100%">

					<table class="<?PHP echo $table_color; ?>table" width="100%">

						<tr>

							<?PHP

							if($need_update == 1){

							?>

							<td class'"<?PHP echo $table_color; ?>table_content' width=50>

								<img src='images/update.gif' border=0 alt=''></td>

							<td class='<?PHP echo $table_color; ?>table_content'>

								Your <b>ekin</b>board needs to be updated!

							</td>

							<?PHP

							} else if($update_error == 1){

							?>

							<td class'"<?PHP echo $table_color; ?>table_content' width=50>

								<img src='images/update.gif' border=0 alt=''></td>

							<td class='<?PHP echo $table_color; ?>table_content'>

								There has been an error when trying to connect to the EKINboard website.<br>Please visit the website for any updates.

							</td>

							<?PHP

							} else {

							?>

							<td class'"<?PHP echo $table_color; ?>table_content' width=50>

								<img src='images/no_update.gif' border=0 alt=''></td>

							<td class='<?PHP echo $table_color; ?>table_content'>

								Your <b>ekin</b>board is completely up-to-date!

							</td>

							<?PHP

							}

							?>

						</tr>

					</table>

					<table width=90%>

						<tr>

							<td>

								Welcome to the EKINboard Administration Panel!<br><i>You are logged in as <a href="../profile.php?id=<?PHP echo $_userid; ?>" class="link2"><?PHP echo $_username; ?></a>.</i>

							</td>

						</tr>

					</table>

					<p>

					<?PHP

						switch($_GET['page']){

							case 'general':

								include ('general.php');

								break;

							case 'online':

								include ('online.php');

								break;

							case 'rules':

								include ('rules.php');

								break;

							case 'template':

								include ('skin.php');

								break;

							case 'categories':

								include ('categories.php');

								break;

							case 'forums':

								include ('forums.php');

								break;

							case 'moderators':

								include ('moderators.php');

								break;

							case 'users':

								include ('users.php');

								break;

							case 'ads':

								include ('ads.php');

								break;

							case 'wordfilter':

								include ('wordfilter.php');

								break;

							case 'about':

								include ('about.php');

								break;

							case 'help':

								include ('help.php');

								break;

							case 'rss':

								include ('rss.php');

								break;

							default:

								include ('home.php');

								break;

						}	

					?>

				</td>

			</tr>

			<tr>

				<td colspan="2" align=center>

					<a href="http://www.ekinboard.com" target="_blank">EKINboard</a> v<?PHP echo $_version; ?> © 2005 <a href="http://www.ekindesigns.com" target="_blank">EKINdesigns</a>

				</td>

			</tr>

		</table>

		</center>

		<?PHP

		} else {

			echo "<center><span class=error>You need to be an admin to access this page!</span></center>";

		}

		?>

	</body>

</html> 