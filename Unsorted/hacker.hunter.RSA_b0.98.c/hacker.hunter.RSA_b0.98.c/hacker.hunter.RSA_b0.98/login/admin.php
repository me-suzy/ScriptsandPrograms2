<?
include ("inc.php");
session_start();
/*Parse session and post data*/
if ($logout) {
		session_destroy();
		header ("Location: ".$login_page);
}
if (session_is_registered("allow")){
		if ($HTTP_SESSION_VARS["allow"] == $user_ip){
				//sort order
				if (session_is_registered("sort_order")) {
						if ($rew == 1 && $sort_order == "DESC") {
								$sort_order = "ASC";
						} elseif ($rew == 1 && $sort_order=="ASC") {
								$sort_order = "DESC";
						}
				}else{
						$sort_order= "ASC";
						session_register ("sort_order");
				}
				unset ($rew);
				//sort field
				if (session_is_registered("sort")) {
						if ($order == 1){
								$sort ="username";
						} elseif ($order == 2){
								$sort ="real_name";
						} elseif ($order == 3){
								$sort ="email";
						} elseif ($order == 4){
								$sort ="rotation_count";
						} elseif ($order == 5){
								$sort ="LLIP";
						} elseif ($order == 6){
								$sort ="admin";
						}elseif ($order == "id") {
								$sort ="userid";
						}
				}else{
						$sort= "userid";
						session_register ("sort");
				}
				unset ($order);
				//start row
				if (!session_is_registered("start_row")) {
						$start_row = 0;
						session_register ("start_row");
				}
				if ($forw==1){
						$start_row += 10;
				}
				if ($back==1 && $start_row >9){
						$start_row -= 10;
				}

				// read input users array
				function read_post($array){
						if (is_array($array)) {
							unset($str);
							foreach ($array as $cur_user => $value) {
								if ($value==1) {
									$str .= "$cur_user ";
								}
							}
							if ($str) {
								return str_replace(" ",",",trim($str));
							}
						}
				}
				$admins = read_post($admin);
				$del_users = read_post($del_user);
				$del_admins = read_post($del_admin);
				
				//function for mailing passwords
				function send_mail ($target_email, $target_name, $updated_username, $updated_password) {
					global $protected_url;
					$message = "Hello $target_name !\n\n";
					$message .= "Your login information for $protected_url was changed by administrator\n\n";
					$message .= "Your new username is: $updated_username\n";
					$message .= "Your new password is: $updated_password\n\n";
					$message .= "Please keep your password in safe place.\n\n";
					$message .= "--== Thank You for chosing web sites protection system ==--\n\n";
					$headers = "From: Web Sites Protection System<$webmaster_mail>\n";
					$headers .= "X-Sender: <$webmaster_mail>\n"; 
					$headers .= "X-Mailer: PHP\n";
					$headers .= "X-Priority: 1\n";
					$headers .= "Return-Path: <$webmaster_mail>\n";
					mail ($target_email,"Your password was changed",$message,$headers);
				}
				
				//update sql generator
				if (is_array($update)) {
					foreach ($update as $user_to_update => $ok) {
						if ($ok==1) {
							if (strlen ($new_password[$user_to_update]) > 2) {
								$last_sql .= "UPDATE $users_table SET username='".$new_username[$user_to_update]."',real_name='".$new_real_name[$user_to_update]."',email='".$new_email[$user_to_update]."',password='".md5($new_password[$user_to_update])."' WHERE userid=$user_to_update\n";
								if ($send_mail[$user_to_update] == 1) {
									send_mail ($new_email[$user_to_update], $new_real_name[$user_to_update], $new_username[$user_to_update], $new_password[$user_to_update]);
								}
							} else {
								$last_sql .= "UPDATE $users_table SET username='".$new_username[$user_to_update]."',real_name='".$new_real_name[$user_to_update]."',email='".$new_email[$user_to_update]."' WHERE userid=$user_to_update\n";
							}
						}
					}
				}

				/*End of parse session and post data*/
				if (mysql_connect($db_host, $db_user, $db_password) or die ("DB connection error")){
						mysql_select_db($db_name);
						//add new user
						if ($form_username && $form_email && $form_password) {
								$checking = "SELECT * FROM $users_table WHERE username='$form_username'";
								$isexist =mysql_query ($checking) or die ("Bad query");
								if (mysql_num_rows ($isexist) < 1) {
										$last_sql .= "INSERT INTO $users_table (username,password,email,real_name,dDate) VALUES ('$form_username','".md5($form_password)."','$form_email','$form_name',".time().")\n";
								} else {
										$last_sql_echo .= "<b>User $form_username exixts.</b><br>";
								}
						}
						//add admin flag
						if ($admins) {
								$last_sql .= "UPDATE $users_table SET admin=1 WHERE userid IN ($admins)\n";
						}
						//remove admin flag
						if ($del_admins) {
								$last_sql .= "UPDATE $users_table SET admin=0 WHERE userid IN ($del_admins)\n";
						}
						//remove user
						if ($del_users) {
								$last_sql .= "DELETE FROM $users_table WHERE userid IN ($del_users)\n";
						}
						if (strlen ($last_sql) > 1) {
							$dump_array = explode("\n", trim($last_sql));
							foreach ($dump_array as $value) {
								$each_sql =trim ($value);
								$last_sql_echo .= $each_sql.";<br>";
								mysql_query ($each_sql) or die ("Bad query");
							}
						}
						$sql_count = "SELECT COUNT(*) as allusers FROM $users_table";
						$users_count = mysql_query ($sql_count) or die ("Bad query");
						$count = mysql_fetch_array($users_count);
						$users_number = $count["allusers"];
						if (strlen ($form_search)>0) {
							$sql_users = "SELECT * FROM $users_table WHERE username like '%$form_search%' OR email like '%$form_search%' OR real_name like '%$form_search%'";
						} else {
							$sql_users = "SELECT * FROM $users_table ORDER BY $sort $sort_order LIMIT $start_row, 10";
						}
						$users_list = mysql_query ($sql_users) or die ("Bad query");
						$last_sql_echo .= $sql_users.";<br>";
						
						?>

						<html><head>
						<title>Hacker Hinter. Admin Panel.</title>
						<link rel="stylesheet" href="admin.css">
						<script language="JavaScript">
						<!--
						function popup(what) {
								window.open(what + ".php",'admin','width=400,height=560,left=20,top=20,status=yes,scrollbars=yes');
						}
						//-->
						</script>
						</head>
						<body>
						<form method="post" action="admin.php">
						<table width="99%" border="1" cellspacing="0" cellpadding="4" align="center">
						<?$page = 1+($start_row/10);
						$pages = (int)(($users_number/10)+0.9);
						echo "<tr><td>";
						if ($start_row >9) {
								echo "<a href=\"admin.php?back=1\">&lt;&lt; Back</a>";
						} else {
								echo "&lt;&lt; Back";
						}
						echo "</td><td colspan=\"2\" align=\"center\"><a href=\"admin.php?rew=1\">Change sort order</a></td><td colspan=\"4\" align=\"center\">Page <b>$page</b> from <b>$pages</b> $sort_order sorting by $sort.</td><td colspan=\"2\" align=\"right\">";
						if ($start_row < $users_number-10) {
								echo "<a href=\"admin.php?forw=1\">Forward &gt;&gt;</a>";
						} else {
								echo "Forward &gt;&gt;";
						}
						echo "</td></tr>";
						echo "<tr align=\"center\"><td><a href=\"admin.php?order=id\">User ID</a></td>";
						echo "<td><a href=\"admin.php?order=1\">Username</a></td>";
						echo "<td><a href=\"admin.php?order=2\">Name</a></td>";
						echo "<td><a href=\"admin.php?order=3\">E-Mail</a></td>";
						echo "<td>Password</td>";
						echo "<td><a href=\"admin.php?order=4\">Rotated</a></td>";
						echo "<td><a href=\"admin.php?order=5\">Start date</a></td>";
						echo "<td><a href=\"admin.php?order=6\">Is Admin</a></td>";
						echo "<td><b>Delete</b></td>";
						while ($row = mysql_fetch_assoc($users_list)) {
								echo "<tr align=\"center\"><td><b>".$row["userid"]."</b> <input type=\"checkbox\" name=\"update[".$row["userid"]."]\" value=\"1\"></td><td><input type=\"text\" class=\"frm\" name=\"new_username[".$row["userid"]."]\" value=\"".$row["username"]."\" size=\"10\"></td> <td><input type=\"text\" class=\"frm\" name=\"new_real_name[".$row["userid"]."]\" value=\"".$row["real_name"]."\" size=\"12\"></td><td><input type=\"text\" class=\"frm\" name=\"new_email[".$row["userid"]."]\" value=\"Demo page\" size=\"15\"></td><td><input type=\"text\" class=\"frm\" name=\"new_password[".$row["userid"]."]\" size=\"10\"></td><td><input type=\"checkbox\" name=\"send_mail[".$row["userid"]."]\" value=\"1\"> <b>".$row["rotation_count"]."</b> times</td><td>".date ("M j  H:i",$row["dDate"])."&nbsp;</td><td>";
								if ($row["admin"] == 1) {
										echo "<font color=\"#000088\"><b>Yes</b></font><td><input type=\"checkbox\" name=\"del_admin[".$row["userid"]."]\" value=\"1\"></td></tr>";
								} else {
												echo "<input type=\"checkbox\" name=\"admin[".$row["userid"]."]\" value=\"1\"></td><td><input type=\"checkbox\" name=\"del_user[".$row["userid"]."]\" value=\"1\"></td></tr>";
								}
						}?>
				<tr align="center"><td>&nbsp;</td>
				<td height="50">Username:<br>
				<input class="frm" type="text" name="form_username" size="10">
				</td><td height="50">Name:<br>
				<input class="frm" type="text" name="form_name" size="12"></td>
				<td height="50">E-mail:<br>
				<input class="frm" type="text" name="form_email" size="15">
				</td><td height="50">Password:<br>
				<input class="frm" type="text" name="form_password" size="10"> </td>
				<td height="50" colspan="4" align="left"> <b>Manually add user</b><br>
				</td></tr>
				<tr><td colspan="5" align="right"><input name="form_search" class="frm" type="text" size="15"></td><td colspan="4"><b>Search for user</b></td></tr>
				<tr><td align="center" colspan="9" height="50"><input class="frm" type="submit" value="Submit Data"></td></tr>
				<tr><td colspan="9"><a href="javascript:popup('proxy')">Edit proxy lists</a></td></tr>
				<?include ("timer.php");
					echo "<tr><td colspan=\"9\"><b>Last query:</b><br><div class=\"hgl\">".$last_sql_echo."</div><b>".$s_time."</b></td></tr>";?>
				</tr><tr><td colspan="9"><a href="admin.php?logout=yes">Logout<a></td></tr>
				<tr><td colspan="9">Check UserID box on rows that must be changed.
				<br>If user is admin, delete box just removes admin flag.
				<br>Check Rotated box to mail changed password to user.
				<br>Blank password field will leave password without changes.
				<br>"Rotated" is number of password changes forced by system after hacking attempt suspicion.</td>
				<tr><td align="center" colspan="9"><b>Web sites protection system &copy; Polar Lights Labs 1994-2002.</b></td>
				</tr></table></form></body></html><?
				mysql_close();
				}
		} else {
				session_unregister ("allow");
				header ("Location: ".$login_page);
		}
} else {
		header ("Location: ".$login_page);
}
?>
