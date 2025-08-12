<?PHP

if($_userlevel == 3){

echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/moderators_title.gif\" border=0 alt=\"\"><p>";



if(($_GET[fid] != "") || ($_POST[fid] != "")){

if(isset($_POST[fid])){

	$_GET[fid] = $_POST[fid];

}



if(($_GET[d] == "add") && (!empty($_GET[uid])) && (!empty($_GET[fid]))){



			$forum_mod_sql = mysql_query("SELECT * FROM users WHERE id='$_GET[uid]'");

			$mods_row = mysql_fetch_array($forum_mod_sql);

				$mod_forums = $mods_row['forum_mod'];

					if(empty($mod_forums)){

						$forum_insert = $_GET[fid];

					} else {

						$forum_insert = $mod_forums . "," . $_GET[fid];

					}

			$forum_mod_sql = mysql_query("UPDATE users SET forum_mod='$forum_insert' WHERE id='$_GET[uid]'");

			header("Location: index.php?page=moderators&fid=$_GET[fid]");

} else if(($_GET[d] == "delete") && (!empty($_GET[uid])) && (!empty($_GET[fid]))){

			$forum_mod_sql = mysql_query("SELECT * FROM users WHERE id='$_GET[uid]'");

			$mods_row = mysql_fetch_array($forum_mod_sql);

			$mod_forums = $mods_row['forum_mod'];

			$mod_forums = explode(",", $mod_forums);

			$new_mod_list = NULL;

			foreach($mod_forums as $forum_id){

				if($forum_id !== $_GET[fid]){

					$new_mod_list .= $forum_id . ",";

				}

			}



			$forum_mod_sql = mysql_query("UPDATE users SET forum_mod='$new_mod_forums' WHERE id='$_GET[uid]'");



			header("Location: index.php?page=moderators&fid=$_GET[fid]");

}

		$f_result = mysql_query("SELECT * FROM forums WHERE id='$_POST[fid]'");

		$rr = mysql_fetch_array($f_result);



echo "<table width=100% cellspacing=0 cellpadding=0 class=category_table>

	<tr class=table_1_header>

	<td colspan=5>

	<table width=100% cellpadding=0 cellspacing=0 border=0>

	<tr>

	<td width=100% class=table_1_header>

	<img src=\"images/arrow_up.gif\"> <b>Manage Moderators [ $rr[name] ]</b>

	</td>

	<td align=right class=table_1_header>

	<img src=\"images/EKINboard_header_right.gif\"></td>

	</tr>

	</table>

	</td>

	</tr>

	<tr class=table_subheader>

	<td align=left class=table_subheader width=60>

	</td>

	<td align=left class=table_subheader>

	Username

	</td>

	<td width=150 align=center class=table_subheader>

	Group

	</td>

	<td width=75 align=center class=table_subheader>

	Posts

	</td>

	<td width=40 align=center class=table_subheader></td>

	</tr>";

$even_number_count = 0;

			$current_mods = '';



			$forum_mod_sql = mysql_query("SELECT * FROM users ORDER BY level DESC");

			

			while($mods_row = mysql_fetch_array($forum_mod_sql)){

				$mod_username = $mods_row['username'];



				$mod_userid = $mods_row['id'];



				$mod_userlevel = $mods_row['level'];



				$mod_forums = $mods_row['forum_mod'];



				$mod_forums = explode(",", $mod_forums);

		

				$q1 = mysql_query("SELECT * FROM replies WHERE poster='$mod_username'");

				$q2 = mysql_query("SELECT * FROM topics WHERE poster='$mod_username'");

			$member_posts = mysql_num_rows($q1) + mysql_num_rows($q2);



				if($mod_userlevel == 1){

					foreach($mod_forums as $fm_id){

						if($fm_id == $_GET[fid]){

							if($mod_userlevel == 1){

								$group = "Forum Moderator";

							} else if($mod_userlevel == 2){

								$group = "Global Moderator";

							} else if($mod_userlevel == 3){

								$group = "Administrator";

							}

							if(!($even_number_count % 2) == TRUE){

								$even_number = 1;

							} else {

								$even_number = 2;

							}

							$even_number_count = $even_number_count + 1;

							$forum_number_count = $forum_number_count + 1;

							echo "<tr>

								<td align=center>

								<img src=\"images/mod_img.gif\" border=0>

								</td>

								<td class=\"row$even_number\"  onmouseover=\"this.className='row". $even_number ."_on';\" onmouseout=\"this.className='row$even_number';\">

								<a href=\"../profile.php?id=$mod_userid\">$mod_username</a>

								</td>

								<td align=center class=\"row$even_number\">

								$group

								</td>

								<td align=center class=\"row$even_number\">

								$member_posts

								</td>

								<td class=\"row2\" align=center><a href=index.php?page=moderators&fid=$_GET[fid]&d=delete&uid=$mod_userid><img src=\"images/delete_btn.gif\" border=0></a></td>

								</tr>";

						}

					}

				}

			}

if($even_number_count == 0){

echo "<tr><td colspan=7 class=contentmain align=center><table class=redtable width=95%>

		<tr><td class=redtable_header><b>Notice</b></td></tr>

		<tr><td class=redtable_content>There are no moderators set to this forum.</td><tr></table>

		</td></tr>";

}

echo "</table><table width=100% height=10>

	<tr>

	<td>

	</td>

	</tr>

	</table>";



	echo "<form action=\"index.php?page=moderators&fid=$_GET[fid]\" method=\"post\">

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

if(isset($_POST[search])){

echo "<table width=100% cellspacing=0 cellpadding=0 class=category_table>

	<tr class=table_1_header>

	<td colspan=6>

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

	<td align=left class=table_subheader width=60>

	</td>

	<td align=left class=table_subheader>

	<a href=\"memberlist.php?sort=username\" class=link2>Username</a>

	</td>

	<td width=100 align=center class=table_subheader>

	Level

	</td>

	<td width=150 align=center class=table_subheader>

	<a href=\"memberlist.php?sort=joined\" class=link2>Joined</a>

	</td>

	<td width=50 align=center class=table_subheader>

	Posts

	</td>

	<td width=40 align=center class=table_subheader></td>

	</tr>";



	$r_result = mysql_query("select * from users WHERE username LIKE '$_POST[search]%' AND level='1'");

	$r_count = mysql_num_rows($r_result);

	while($row=mysql_fetch_array($r_result)){

				$mod_forums = $row['forum_mod'];



				$mod_forums = explode(",", $mod_forums);



					foreach($mod_forums as $fm_id){

						if($fm_id == $_GET[fid]){

							$already_mod = TRUE;

						}

					}

		if($already_mod != TRUE){

		$member_id = $row['id'];

		$member_name = $row['username'];

		$member_joined = $row['signup_date'];

		$member_joined = date("M jS, Y", strtotime($member_joined, "\n"));

		$member_www = $row['website_url'];

		$member_aim = $row['aim'];

		$member_msn = $row['msn'];

		$member_yahoo = $row['yahoo'];

		$member_icq = $row['icq'];

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

			<td align=center>

			<img src=\"images/users_img.gif\" border=0>

			</td>

			<td class=\"row$even_member\"  onmouseover=\"this.className='row". $even_member ."_on';\" onmouseout=\"this.className='row$even_member';\" onclick=\"window.location.href='../profile.php?id=$member_id'\">

			<a href=\"../profile.php?id=$member_id\">$member_name</a>

			</td>

			<td align=center class=\"row$even_member\">

			<img src=\"../templates/default/images/level_$member_level.gif\">

			</td>

			<td align=center class=\"row$even_member\">

			$member_joined

			</td>

			<td align=center class=\"row$even_member\">

			$member_posts

			</td>

			<td class=\"row2\" align=center><a href=index.php?page=moderators&fid=$_GET[fid]&d=add&uid=$member_id><img src=\"images/add_btn.gif\" border=0></a></td>

			</tr>";

		}

	}

if($evenmember_count == 0){

echo "<tr><td colspan=7 class=contentmain align=center><table class=redtable width=95%>

		<tr><td class=redtable_header><b>Notice</b></td></tr>

		<tr><td class=redtable_content>Could not find any regular users under the keyword <b>$_POST[search]</b></td><tr></table>

		</td></tr>";

}

echo "</table>";

}

} else {

echo "<center><table width=100% class=category_table cellpadding=0 cellspacing=0><tr><td class=table_1_header><b>Select Forum<b></td></tr><tr><td align=center>";

	echo "<center><form action=\"index.php?page=moderators\" method=POST name=form>";

	echo "<table border=0 cellpadding=0 cellspacing=0>";

	echo "<tr><td align=right class=contentmain>Forum:</td><td class=contentmain>

	<select name=fid class=textbox value=2><option value=''>Choose Forum..</option>";

	$c_result = mysql_query("select * from categories ORDER By id ASC");

	while($r = mysql_fetch_array($c_result)){

		$get_c_id = $r['id'];

		$get_c_name = $r['name'];



		$selected = NULL;



		if($get_c_id == $row[cid]){

			$selected = " SELECTED";

		}



		echo "<optgroup label=\"$get_c_name\">";



		$f_result = mysql_query("select * from forums WHERE cid='$get_c_id' AND id!='$_GET[id]' ORDER By id ASC");

		while($rr = mysql_fetch_array($f_result)){

			$get_f_id = $rr['id'];

			$get_f_name = $rr['name'];

	

			$selected = NULL;

	

			if(($get_f_id == $row[cid]) && ($subforum==1)){

				$selected = " SELECTED";

			}

	

			echo "<option value=$get_f_id". "$selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$get_f_name</option>";

		}

	}



	echo "</select></td></tr>";

	echo "</table></td></tr>";

	echo "<tr><td colspan=3 align=center class=table_bottom><input type=submit value='Continue > >'></td></tr></table>";

}

} else {

	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";

}

?>