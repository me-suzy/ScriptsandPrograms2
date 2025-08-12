<?PHP
if($_userlevel != 3){
    die("<center><span class=red>You need to be an admin to access this page!</span></center>");
}
	function edit_form($id, $firstname, $email, $username, $activated, $level, $sig, $title, $website, $aim, $msn, $yahoo, $icq, $warning, $skin, $avatar, $avatar_alt, $banned){

		if($activated == '0'){
			$activated_input = "<i>Unactivated </i><input type='radio' class='form' name='activated' value='0' checked=checked> - <input type='radio' class='form' name='activated' value='1'> <i>Activated</i>";
		} else {
			$activated_input = "<i>Unactivated </i><input type='radio' class='form' name='activated' value='0'> - <input type='radio' class='form' name='activated' value='1' checked=checked> <i>Activated</i>";
		}

		if($level == '1'){
			 $a_checked = " checked=checked";
		} else if($level == '2'){
			 $b_checked = " checked=checked";
		} else if($level == '3'){
			 $c_checked = " checked=checked";
		}
		$level_input = "<input type='radio' class='form' name='level' value='1'$a_checked> <i>Member</i><br><input type='radio' class='form' name='level' value='2'$b_checked> <i>Moderator</i><br><input type='radio' class='form' name='level' value='3'$c_checked> <i>Administrator</i>";



		if($warning == '0'){

			$warning_input = "<i>0</i> <input type='radio' class='form' name='warning' value='0' checked=checked> &nbsp;&nbsp;<i>1</i> <input type='radio' class='form' name='warning' value='1'> &nbsp;&nbsp;<i>2</i> <input type='radio' class='form' name='warning' value='2'> &nbsp;&nbsp;<i>3</i> <input type='radio' class='form' name='warning' value='3'>&nbsp;&nbsp;<i>4</i> <input type='radio' class='form' name='warning' value='4'>";

		} else if($warning == '1'){

			$warning_input = "<i>0</i> <input type='radio' class='form' name='warning' value='0'> &nbsp;&nbsp;<i>1</i> <input type='radio' class='form' name='warning' value='1' checked=checked> &nbsp;&nbsp;<i>2</i> <input type='radio' class='form' name='warning' value='2'> &nbsp;&nbsp;<i>3</i> <input type='radio' class='form' name='warning' value='3'>&nbsp;&nbsp;<i>4</i> <input type='radio' class='form' name='warning' value='4'>";

		} else if($warning == '2'){

			$warning_input = "<i>0</i> <input type='radio' class='form' name='warning' value='0'> &nbsp;&nbsp;<i>1</i> <input type='radio' class='form' name='warning' value='1'> &nbsp;&nbsp;<i>2</i> <input type='radio' class='form' name='warning' value='2' checked=checked> &nbsp;&nbsp;<i>3</i> <input type='radio' class='form' name='warning' value='3'>&nbsp;&nbsp;<i>4</i> <input type='radio' class='form' name='warning' value='4'>";

		} else if($warning == '3'){

			$warning_input = "<i>0</i> <input type='radio' class='form' name='warning' value='0'> &nbsp;&nbsp;<i>1</i> <input type='radio' class='form' name='warning' value='1'> &nbsp;&nbsp;<i>2</i> <input type='radio' class='form' name='warning' value='2'> &nbsp;&nbsp;<i>3</i> <input type='radio' class='form' name='warning' value='3' checked=checked>&nbsp;&nbsp;<i>4</i> <input type='radio' class='form' name='warning' value='4'>";

		} else if($warning == '4'){

			$warning_input = "<i>0</i> <input type='radio' class='form' name='warning' value='0'> &nbsp;&nbsp;<i>1</i> <input type='radio' class='form' name='warning' value='1'> &nbsp;&nbsp;<i>2</i> <input type='radio' class='form' name='warning' value='2'> &nbsp;&nbsp;<i>3</i> <input type='radio' class='form' name='warning' value='3'>&nbsp;&nbsp;<i>4</i> <input type='radio' class='form' name='warning' value='4' checked=checked>";

		}

		
		$templates = NULL;

		if ($handle = opendir('../templates/')) {

		   while (false !== ($file = readdir($handle))) {

			   if ($file != "." && $file != "..") {

				 if($file == $skin){
   
					$templates .= "<option value='$file' selected>$file</option>";

				} else {

					$templates .= "<option value='$file'>$file</option>";

				 }

			   }

		   }
		   closedir($handle);
		}

		if(!empty($emailok)){

			echo "<br>Emails did not match!";

		}

		if(!empty($passok)){

			echo "<br>Passwords didn't match";

		}

		echo "
			<table width=100% class=category_table cellpadding=0 cellspacing=0>
				<tr>
					<td class=table_1_header>			
						<img src=\"images/arrow_up.gif\"> <b>Edit $username's Information</b>			
					</td>
				</tr>
				<tr>
					<td>
						<center><form name=edit action=index.php?page=users&d=edit&id=$_GET[id]&step=2 method=post>
							<input type=hidden name=id value='$_GET[id]'>
					<table  width=100% border=0 cellpadding=0 cellspacing=0>
						<tr>
							<td valign=top class='row1'>
								<b>First Name: </b>
							</td>
							<td class='row1'>
								<input type=text name=first_name value='$firstname' class='textbox'>
							</td>
						</tr>
						<tr>
							<td  valign=top class='row1'>
								<b>Title: </b>
							</td>
							<td class='row1'>
								<input type='text' name='title' value=\"$title\" size='50' class='textbox'>
							</td>
						</tr>
						<tr>
							<td valign=top class='row2'>
								<b>New Password: </b> 
							</td>
							<td class='row2'>
								<input type='password' name='pass' size='50' class='textbox'>
							</td>
							</tr>
						<tr>
							<td valign=top class='row1'>
								<b>Email: </b> 
							</td>
								<td class='row1'>
									<input type='text' name='email' size='50' class='textbox' value='$email'>
								</td>
							</tr>

										<tr>
											<td valign=top class='row2'>
												<b>Aim: </b>
											</td>
											<td class='row2'>
												<input type='text' name='aim' value=\"$aim\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row1'>
												<b>Yahoo: </b>
											</td>
											<td class='row1'>
												<input type='text' name='yahoo' value=\"$yahoo\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>MSN: </b>
											</td>
											<td class='row2'>
												<input type='text' name='msn' value=\"$msn\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row1'>
												<b>ICQ: </b>
											</td>
											<td class='row1'>
												<input type='text' name='icq' value=\"$icq\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>Website: </b>
											</td>
											<td class='row2'>
												<input type='text' name='website_url' value=\"$website\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row1'>
												<b>Signature: </b>
											</td>
											<td class='row1'>
												<textarea name='sig' rows='15' cols='50'>$sig</textarea>
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>Avatar: </b>
											</td>
											<td class='row2'>
												<input type='text' name='avatar' value=\"$avatar\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>Avatar Title: </b>
											</td>
											<td class='row2'>
												<input type='text' name='avatar_alt' value=\"$avatar_alt\" size='50' class='textbox'>
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>Skin: </b>
											</td>
											<td class='row2'>
												<select name='skin'>
													$templates
												</select>
											</td>
										</tr>
										<tr>
											<td height=10 colspan=2 class=table_1_header><img src=\"images/arrow_up.gif\"> <b>Advanced User Settings</b></td>
										</tr>
										<tr>
											<td align=top class='row1'>
												<b>Activated: </b>
											</td>
											<td class='row1'>
												$activated_input
											</td>
										</tr>
										<tr>
											<td valign=top class='row2'>
												<b>User Level: </b><br>
											</td>
											<td class='row2'>
												$level_input
											</td>
										</tr>
										<tr>
											<td valign=top class='row1'>
												<b>User Warning: </b><br>												This feature is not yet working...
											</td>
											<td class='row1'>
												$warning_input
											</td>
										</tr>
										<tr>
											<td colspan=2 align=center class=table_bottom>
												<input type=submit name=submit value=\"Save > >\">
											</td>
										</tr>
									</table>
									</td>
								</tr>
							</table>";


	}
echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/users_title.gif\" border=0 alt=\"\"><p>";

if(($_GET['d'] == "edit") && (isset($_GET['id']))){

				$step = $_GET['step'];
				switch($step)
				{

					default:

						$get_user_info = mysql_query("SELECT * FROM users WHERE id='$_GET[id]'");
						$row = mysql_fetch_array($get_user_info);

						$firstname = $row['first_name'];
						$email = $row['email'];
						$username = $row['username'];
						$activated = $row['activated'];
						$level = $row['level'];
						$sig = $row['sig'];
						$title = $row['title'];
						$website = $row['website_url'];
						$aim = $row['aim'];
						$msn = $row['msn'];
						$yahoo = $row['yahoo'];
						$icq = $row['icq'];
						$warning = $row['warning'];
						$skin = $row['skin'];
						$avatar = $row['avatar'];
						$avatar_alt = $row['avatar_alt'];

						edit_form($id, $firstname, $email, $username, $activated, $level, $sig, $title, $website, $aim, $msn, $yahoo, $icq, $warning, $skin, $avatar, $avatar_alt);


					break;




					case '2':

						$id = $_POST['id'];
						$firstname = $_POST['first_name'];
						$email = $_POST['email'];
						$apass = $_POST['pass'];
						$activated = $_POST['activated'];
						$level = $_POST['level'];
						$sig = $_POST['sig'];
						$title = $_POST['title'];
						$website = $_POST['website_url'];
						$aim = $_POST['aim'];
						$msn = $_POST['msn'];
						$yahoo = $_POST['yahoo'];
						$icq = $_POST['icq'];
						$warning = $_POST['warning'];
						$skin = $_POST['skin'];
						$avatar = $_POST['avatar'];
						$avatar_alt = $_POST['avatar_alt'];


						$update_users = mysql_query("UPDATE users SET first_name='$firstname', email='$email', activated='$activated', level='$level', sig='$sig', title='$title', website_url='$website', aim='$aim', msn='$msn', yahoo='$yahoo', icq='$icq', warning='$warning', skin='$skin', avatar='$avatar', avatar_alt='$avatar_alt' WHERE id='$id'") or die(mysql_error());

						header("Location: index.php?page=users");

						
					break;

				}
} else if(($_GET['d'] == "delete") && (isset($_GET['id']))){
	$get_user_info = mysql_query("SELECT * FROM users WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_user_info);
	if($_GET['sure'] == "yes"){
		$delete_users = mysql_query("DELETE FROM users WHERE id='$_GET[id]'") or die(mysql_error());
		header("Location: index.php?page=users");
	} else {
		echo "				<table width=100% cellspacing=0 cellpadding=0 class=category_table>
								<tr>
									<td colspan=4>
										<table width=100% cellpadding=0 cellspacing=0 border=0>
											<tr>
												<td width=45>
													<img src=\"images/EKINboard_header_left.gif\"></td>
												<td width=100% class=table_1_header>
													<img src=\"images/arrow_up.gif\"> <b>Delete</b></td>
												<td align=right class=table_1_header>
													<img src=\"images/EKINboard_header_right.gif\"></td>
											</tr>
										</table>
									</td>
								</tr>
								<tr class=table_subheader>
									<td align=left class=table_subheader>	
										Are you sure you would like to delete <span class=hilight>$row[username]</span>'s account?
									</td>
								</tr>
								<tr>
									<td class=contentmain align=center>
										<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">
											<tr>
												<td class=\"redtable\" align=center width=100>
													<a href=\"index.php?page=users&d=delete&id=$_GET[id]&sure=yes\" class=link2>Yes</a>
												</td>
												<td width=20></td>
												<td class=\"bluetable\" align=center width=100>
													<a href=\"javascript:history.go(-1)\" class=link2>No</a>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>";
	}
} else if(($_GET['d'] == "ban") && (isset($_GET['id']))) {
	$get_user_info = mysql_query("SELECT * FROM users WHERE id='$_GET[id]'");
	$row = mysql_fetch_array($get_user_info);

	if($row[banned] == 1){
			$delete_users = mysql_query("UPDATE users SET banned='0', banned_reason='' WHERE id='$_GET[id]'") or die(mysql_error());
			header("Location: index.php?page=users");
	} else {
		if($_POST['reason'] != NULL){
			$delete_users = mysql_query("UPDATE users SET banned='1', banned_reason='$_POST[reason]' WHERE id='$_GET[id]'") or die(mysql_error());
			header("Location: index.php?page=users");
		} else {
			echo "				<table width=100% cellspacing=0 cellpadding=0 class=category_table>
									<tr>
										<td colspan=4>
											<table width=100% cellpadding=0 cellspacing=0 border=0>
												<tr>
													<td width=45>
														<img src=\"images/EKINboard_header_left.gif\"></td>
													<td width=100% class=table_1_header>
														<img src=\"images/arrow_up.gif\"> <b>Ban user</b></td>
													<td align=right class=table_1_header>
														<img src=\"images/EKINboard_header_right.gif\"></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr class=table_subheader>
										<td align=left class=table_subheader>	
											Please enter the reason why you are banning <span class=hilight>$row[username]</span>?
										</td>
									</tr>
									<tr>
										<td align=center>
											<form action=\"index.php?page=users&d=ban&id=$_GET[id]\" method=POST>
											<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\" width=100%>
												<tr>
													<td align=center>
														<textarea class='text' cols=80 rows=10 name='reason'></textarea>
													</td>
												</tr>
												<tr>
													<td align=center class=table_bottom> 
														<input type=submit value='Ban User!' name='submit'>
													</td>
												</tr>
											</table>
											</form>
										</td>
									</tr>
								</table>";
		}
}
} else {
$_s = $_GET[s];
if(isset($_POST[search])){
	$_s = $_POST[search];
}

$_sort = $_GET['sort'];

$display_num = 20;
$limitnum = 2;

$query_rows = "SELECT * FROM users WHERE username LIKE '$_s%'";
$result_rows = mysql_query ($query_rows);
$num_rows = mysql_num_rows ($result_rows);

if ($num_rows > $display_num) {
	$num_pages = ceil ($num_rows / $display_num);
} else {
	$num_pages = 1;
}
if (isset($_GET['p'])) {
	$page_page_num = $_GET['p'];
	$db_page_num = ($_GET['p'] - 1) * $display_num;
} else {
	$db_page_num = 0;
	$page_page_num = 1;
}

$page_num_str = NULL;

if ($num_pages > 1) {
echo "<table>
	<tr>
	<td class=\"padding\">
	<table class=\"pagetable_1\">
	<tr>
	<td>
	Pages: ($num_pages)
	</td>
	</tr>
	</table>
	</td>";

	$current_page = ($db_page_num / $display_num) + 1;

	for ($i = 1; $i <= $num_pages; $i++) {
		if ($i==$current_page) {
			echo "<td class=\"padding\">
				<table class=\"pagetable_2\">
				<tr>
				<td>
				<a href=\"index.php?page=users&p=$i\">$i</a>
				</td>
				</tr>
				</table>
				</td>";
		} else if(($i < $current_page - $limitnum) || ($i > $current_page + $limitnum)){
			if(($i < $current_page - $limitnum) && ($a == NULL)){
				echo "<td class=\"padding\">
				<table class=\"pagetable_1\">
				<tr>
				<td>
				<a href=\"index.php?page=users&p=1\">«</a>
				</td>
				</tr>
				</table>
				</td>";
				
				$a = 1;
			} else if(($i > $current_page + $limitnum) && ($b == NULL)){
				echo "<td class=\"padding\">
				<table class=\"pagetable_1\">
				<tr>
				<td>
				<a href=\"index.php?page=users&p=$num_pages\">»</a>
				</td>
				</tr>
				</table>
				</td>";
				$b = 1;
			}
		} else {
			echo "<td class=\"padding\">
				<table class=\"pagetable_1\">
				<tr>
				<td>
				<a href=\"index.php?page=users&p=$i\">$i</a>
				</td>
				</tr>
				</table>
				</td>";
		}
	}
}


if((strtolower($_sort) == "username") || (strtolower($_sort) == "level") || (strtolower($_sort) == "joined")){
	if(strtolower($_sort) == "username"){
		$_order = "ASC";
	} else if(strtolower($_sort) == "group"){
		$_sort = "level";
		$_order = "DESC";
	} else if(strtolower($_sort) == "joined"){
		$_sort = "signup_date";
		$_order = "ASC";
	}
} else {
	$_sort = "level";
	$_order = "DESC";
}

$alphabet = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
echo "<table><tr>";
foreach ($alphabet as $letter) {

	$s = strtoupper($_GET['s']);

	if(strtoupper($letter) == $s){
		$current_letter = 2;
	} else {
		$current_letter = 1;
	}
echo "<td class=\"padding\">
	<table class=\"pagetable_$current_letter\">
	<tr>
	<td>
	<a href=\"index.php?page=users&s=$letter\">$letter</a>
	</td>
	</tr>
	</table>
	</td>";
}
echo "<td class=\"padding\">
	<table>
	<tr>
	<td>
	( <a href=\"index.php?page=users\">View All</a> )
	</td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	<table width=100% height=10>
	<tr>
	<td>
	</td>
	</tr>
	</table>";
$evenmember_count = 0;
	echo "<form action=\"index.php?page=users\" method=\"post\">
	<table>
	<tr>
	<td>
			Search users:
	</td>
	<td>
			<input type=\"text\" class=\"text\" name=\"search\" size=\"20\" value=\"$_POST[search]\">
	</td>
	<td>
			<input type=\"submit\" value=\"Search\">
	</td>
	</tr>
	</table></form>";
if($num_rows != 0){
echo "<table width=100% height=10>
	<tr>
	<td>
	</td>
	</tr>
	</table>
	<table width=100% cellspacing=0 cellpadding=0 class=category_table>
	<tr class=table_1_header>
	<td colspan=8>
	<table width=100% cellpadding=0 cellspacing=0 border=0>
	<tr>
	<td width=100% class=table_1_header>
	<img src=\"images/arrow_up.gif\"> <b>Members</b>
	</td>
	<td align=right class=table_1_header>
	<img src=\"images/EKINboard_header_right.gif\"></td>
	</tr>
	</table>
	</td>
	</tr>
	<tr class=table_subheader>
	<td align=left class=table_subheader>
	<a href=\"memberlist.php?sort=username\" class=link2>Username</a>
	</td>
	<td width=100 align=center class=table_subheader>
	Level
	</td>
	<td width=150 align=center class=table_subheader>
	<a href=\"memberlist.php?sort=group\" class=link2>Group</a>
	</td>
	<td width=150 align=center class=table_subheader>
	<a href=\"memberlist.php?sort=joined\" class=link2>Joined</a>
	</td>
	<td width=50 align=center class=table_subheader>
	Posts
	</td>
	<td width=40 align=center class=table_subheader></td>
	<td width=40 align=center class=table_subheader></td>
	<td width=40 align=center class=table_subheader></td>
	</tr>";

	$r_result = mysql_query("select * from users WHERE username LIKE '$_s%' ORDER BY $_sort $_order LIMIT $db_page_num, $display_num");
	$r_count = mysql_num_rows($r_result);
	while($row=mysql_fetch_array($r_result)){

	$member_id = $row['id'];
	$member_name = $row['username'];
	$member_joined = $row['signup_date'];
	$member_joined = date("M jS, Y", strtotime($member_joined, "\n"));
	$member_www = $row['website_url'];
	$member_aim = $row['aim'];
	$member_msn = $row['msn'];
	$member_yahoo = $row['yahoo'];
	$member_icq = $row['icq'];
	$member_banned = $row['banned'];
	$member_group = $row['level'];
		$q1 = mysql_query("SELECT * FROM replies WHERE poster='$member_name'");
		$q2 = mysql_query("SELECT * FROM topics WHERE poster='$member_name'");
	$member_posts = mysql_num_rows($q1) + mysql_num_rows($q2);

	switch ($member_group) {
		case 0:
		   $member_group = "Test Account";
		   break;
		case 1:
		   $member_group = "Member";
   			break;
		case 2:
		   $member_group = "Moderator";
		   break;
		case 3:
		   $member_group = "Administrator";
		   break;
	}
	if($member_posts<50){
		$member_level = 1;
	} else if($member_posts>=50 && $member_posts<100){
		$member_level = 2;
	} else if($member_posts>=100 && $member_posts<250){
		$member_level = 3;
	} else if($member_posts>=250 && $member_posts<500){
        $member_level = 4;
    } else if($member_posts>=500){
        $member_level = 5;
    }

	if(!($evenmember_count % 2) == TRUE){
		$even_member = 1;
	} else {
		$even_member = 2;
	}

	$evenmember_count = $evenmember_count + 1;

	echo "<tr>
		<td class=\"row$even_member\"  onmouseover=\"this.className='row". $even_member ."_on';\" onmouseout=\"this.className='row$even_member';\" onclick=\"window.location.href='../profile.php?id=$member_id'\">
		<a href=\"../profile.php?id=$member_id\">$member_name</a>
		</td>
		<td align=center class=\"row$even_member\">
		<img src=\"../templates/default/images/level_$member_level.gif\">
		</td>
		<td align=center class=\"row2\">
		$member_group
		</td>
		<td align=center class=\"row$even_member\">
		$member_joined
		</td>
		<td align=center class=\"row$even_member\">
		$member_posts
		</td>
		<td class=\"row2\" align=center>
		<a href=\"index.php?page=users&d=edit&id=$member_id\"><img src=\"images/usr_edit_btn.gif\" border=0 alt='Edit User'></a></td>
		<td class=\"row2\" align=center>
		<a href=\"index.php?page=users&d=delete&id=$member_id\"><img src=\"images/usr_delete_btn.gif\" border=0  alt='Delete User'></a></td>";
if($member_banned == 1){
		echo "<td class=\"row2\" align=center>
		<a href=\"index.php?page=users&d=ban&id=$member_id\"><img src=\"images/usr_unban_btn.gif\" border=0 alt='Unban User'></a></td>";
} else {
		echo "<td class=\"row2\" align=center>
		<a href=\"index.php?page=users&d=ban&id=$member_id\"><img src=\"images/usr_ban_btn.gif\" border=0 alt='Ban User'></a></td>";
}
		echo "</tr>";
	}
echo "</table>";
} else {
	echo "<center><span class=\"error\">No users have been found!</span></center>";
}
}
?>