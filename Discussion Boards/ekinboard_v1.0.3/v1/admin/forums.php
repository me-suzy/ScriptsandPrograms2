<?PHP

if($_userlevel == 3){

echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/forums_title.gif\" border=0 alt=\"\"><p>";

echo "<div align=right><a href=index.php?page=forums&d=new><img src=images/new_forum.gif border=0></a></div>";

if($_GET[d] == "new"){

	if($_GET[step] == 2){

		if(eregi("subforum_", $_POST[c_id])){

			$_POST[c_id] = str_replace("subforum_", "", $_POST[c_id]);

			$subforum = 1;

		} else {

			$subforum = 0;

		}



		$query_c = "INSERT INTO `forums` ( `id` , `cid` , `subforum` , `name` , `description` , `hidden` , `protected` , `password` , `news` , `restricted_level` ) VALUES ('', '$_POST[c_id]', '$subforum', '$_POST[fname]', '$_POST[fdescription]', '$_POST[hidden]', '0', '', '$_POST[news]', '$_POST[level]')";

		$result = mysql_query($query_c);

			

		header("Location: index.php?page=forums");

	} else {

echo "<center><table width=100% class=category_table cellpadding=0 cellspacing=0><tr><td class=table_1_header><b>New forum<b></td></tr><tr><td>";

	echo "<center><form action=index.php?page=forums&d=new&step=2 method=post name=form>";

	echo "<table border=0 cellpadding=0 cellspacing=0 width='100%'>";

	echo "<tr><td align=right class=contentmain>Category:</td><td class=contentmain>

	<select name=c_id class=textbox value=2>";

	$c_result = mysql_query("select * from categories ORDER By id ASC");

	while($r = mysql_fetch_array($c_result)){

		$get_c_id = $r['id'];

		$get_c_name = $r['name'];



		$selected = NULL;



		if($get_c_id == $row[cid]){

			$selected = " SELECTED";

		}



		echo "<option value=$get_c_id". "$selected>$get_c_name</option>";



		$f_result = mysql_query("select * from forums WHERE cid='$get_c_id' ORDER By id ASC");

		while($rr = mysql_fetch_array($f_result)){

			$get_f_id = $rr['id'];

			$get_f_name = $rr['name'];

	

			$selected = NULL;

	

			if(($get_f_id == $row[cid]) && ($subforum==1)){

				$selected = " SELECTED";

			}

	

			echo "<option value=subforum_$get_f_id". "$selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$get_f_name</option>";

		}

	}



	echo "</select></td></tr>";

	echo "<tr><td align=right class=contentmain>Name:</td><td class=contentmain><input type=text class=textbox name=fname value=\"$row[name]\" size=50></td></tr>";

	echo "<tr><td align=right class=contentmain>Description:</td><td class=contentmain><input type=text class=textbox name=fdescription value=\"$row[description]\" size=50></td></tr>";

	echo "<tr><td align=right class=contentmain>Accessable To:</td>

	<td class=contentmain>

	<input type='radio' class='form' name='level' value='1' checked=checked> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2'> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3'> <i>Administrators</i>";

	echo "</td></tr>";

	echo "<tr><td align=right class=contentmain>Hidden:</td>

	<td class=contentmain>";

			echo "<input type='radio' class='form' name='hidden' value='0' checked=checked> <i>No</i><br><input type='radio' class='form' name='hidden' value='1'> <i>Yes</i>";

	echo "</td></tr>";

	echo "<tr><td colspan=3 align=center class=table_bottom><input type=submit value='Add'></td></tr>";

	echo "</table></center></table></center>";

	}

} else if(($_GET[d] == "edit") && (isset($_GET[id]))){

	if($_GET[step] == 2){



		if(eregi("subforum_", $_POST[c_id])){

			$_POST[c_id] = str_replace("subforum_", "", $_POST[c_id]);

			$subforum = 1;

		} else {

			$subforum = 0;

		}

		if($_POST[news] == 1){
			$query_d = "UPDATE forums SET news='0' WHERE news='1'";
			$result = mysql_query($query_d);
			$query_e = "UPDATE forums SET news='1' WHERE id='$_GET[id]'";
			$result = mysql_query($query_e);
		}

		$query_c = "UPDATE forums SET cid='$_POST[c_id]', name='$_POST[fname]', description='$_POST[fdescription]', subforum='$subforum', restricted_level='$_POST[level]', hidden='$_POST[hidden]' WHERE id='$_GET[id]'";

		$result = mysql_query($query_c);

			

		header("Location: index.php?page=forums");

	} else {

		$get_cat_info = mysql_query("SELECT * FROM forums WHERE id='$_GET[id]'");

		$row = mysql_fetch_array($get_cat_info);



		$subforum = $row[subforum]; 



echo "<center><table width=100% class=category_table cellpadding=0 cellspacing=0><tr><td class=table_1_header><b>Edit forum<b></td></tr><tr><td>";

	echo "<center><form action=index.php?page=forums&d=edit&id=$_GET[id]&step=2 method=post name=form>";

	echo "<table border=0 cellpadding=0 cellspacing=0 width='100%'>";

	echo "<tr><td align=right class=contentmain>Category:</td><td class=contentmain>

	<select name=c_id class=textbox value=2>";

	$c_result = mysql_query("select * from categories ORDER By id ASC");

	while($r = mysql_fetch_array($c_result)){

		$get_c_id = $r['id'];

		$get_c_name = $r['name'];



		$selected = NULL;



		if($get_c_id == $row[cid]){

			$selected = " SELECTED";

		}



		echo "<option value=$get_c_id". "$selected>$get_c_name</option>";



		$f_result = mysql_query("select * from forums WHERE cid='$get_c_id' AND id!='$_GET[id]' ORDER By id ASC");

		while($rr = mysql_fetch_array($f_result)){

			$get_f_id = $rr['id'];

			$get_f_name = $rr['name'];

	

			$selected = NULL;

	

			if(($get_f_id == $row[cid]) && ($subforum==1)){

				$selected = " SELECTED";

			}

	

			echo "<option value=subforum_$get_f_id". "$selected>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$get_f_name</option>";

		}

	}



	echo "</select></td></tr>";

	echo "<tr><td align=right class=contentmain>Name:</td><td class=contentmain><input type=text class=textbox name=fname value=\"$row[name]\" size=50></td></tr>";

	echo "<tr><td align=right class=contentmain>Description:</td><td class=contentmain><input type=text class=textbox name=fdescription value=\"$row[description]\" size=50></td></tr>";
	echo "<tr><td align=right class=contentmain>News:</td>

	<td class=contentmain>";

		if($row[news] == 0){

			echo "<input type='radio' class='form' name='news' value='0' checked=checked> <i>No</i><br><input type='radio' class='form' name='news' value='1'> <i>Yes</i>";

		} else if($row[news] == 1){

			echo "<input type='radio' class='form' name='news' value='0'> <i>No</i><br><input type='radio' class='form' name='news' value='1' checked=checked> <i>Yes</i>";

		}

	echo "</td></tr>";
	echo "<tr><td align=right class=contentmain>Accessable To:</td>

	<td class=contentmain>";

		if($row[restricted_level] == '1'){

			echo "<input type='radio' class='form' name='level' value='1' checked=checked> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2'> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3'> <i>Administrators</i>";

		} else if($row[restricted_level] == '2'){

			echo "<input type='radio' class='form' name='level' value='1'> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2' checked=checked> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3'> <i>Administrators</i>";

		} else if($row[restricted_level] == '3'){

			echo "<input type='radio' class='form' name='level' value='1'> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2'> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3' checked=checked> <i>Administrators</i>";

		}

	echo "</td></tr>";

	echo "<tr><td align=right class=contentmain>Hidden:</td>

	<td class=contentmain>";

		if($row[hidden] == '0'){

			echo "<input type='radio' class='form' name='hidden' value='0' checked=checked> <i>No</i><br><input type='radio' class='form' name='hidden' value='1'> <i>Yes</i>";

		} else if($row[hidden] == '1'){

			echo "<input type='radio' class='form' name='hidden' value='0'> <i>No</i><br><input type='radio' class='form' name='hidden' value='1' checked=checked> <i>Yes</i>";

		}

	echo "</td></tr>";

	echo "<tr><td colspan=3 align=center class=table_bottom><input type=submit value='Save'></td></tr>";

	echo "</table></center></table></center>";

	}

} else if(($_GET[d] == "delete") && (isset($_GET[id]))){

	$get_forums_info = mysql_query("SELECT * FROM forums WHERE id='$_GET[id]'");

	$row = mysql_fetch_array($get_forums_info);

	if($_GET['sure'] == "yes"){

		$get_topic_info = mysql_query("SELECT * FROM topics WHERE fid='$_GET[id]'");

		while($f_row = mysql_fetch_array($get_topic_info)){



			$delete_replies = mysql_query("DELETE FROM replies WHERE tid='$f_row[id]'");

		}

		$delete_forums = mysql_query("DELETE FROM topics WHERE cid='$row[id]'");

		$delete_category = mysql_query("DELETE FROM forums WHERE id='$row[id]'");



		header("Location: index.php?page=forums");

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

										Are you sure you would like to delete the forum <span class=hilight>$row[name]</span> and everything in it?

									</td>

								</tr>

								<tr>

									<td class=contentmain align=center>

										<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">

											<tr>

												<td class=\"redtable\" align=center width=100>

													<a href=\"index.php?page=forums&d=delete&id=$_GET[id]&sure=yes\" class=link2>Yes</a>

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

} else if(($_GET[d] == "moveup") && (isset($_GET[id]))){

$id = $_GET['id'];

$new_id_q_d = "SELECT * FROM forums WHERE id='$id' AND subforum='0'";

$new_id_r_d = mysql_query($new_id_q_d);

$d = mysql_fetch_assoc($new_id_r_d);



$new_id_q = "SELECT * FROM forums WHERE id < '$id' AND cid='$d[cid]' AND subforum='0' ORDER BY id DESC LIMIT 1";

$new_id_r = mysql_query($new_id_q);

$f = mysql_fetch_assoc($new_id_r);

$new_id = $f['id'];

$up_1 = mysql_query("UPDATE forums SET id='0' WHERE id='$new_id' AND subforum='0'");

$f_up_1 = mysql_query("UPDATE topics SET fid='0' WHERE fid='$new_id' AND subforum='0'");

$up_2 = mysql_query("UPDATE forums SET id='$new_id' WHERE id='$id' AND subforum='0'");

$f_up_2 = mysql_query("UPDATE topics SET fid='$new_id' WHERE fid='$id' AND subforum='0'");

$up_3 = mysql_query("UPDATE forums SET id='$id' WHERE id='0' AND subforum='0'");

$f_up_3 = mysql_query("UPDATE topics SET fid='$id' WHERE fid='0' AND subforum='0'");



$up_1 = mysql_query("UPDATE forums SET cid='0' WHERE cid='$new_id' AND subforum='1'");

$f_up_1 = mysql_query("UPDATE topics SET fid='0' WHERE fid='$new_id' AND subforum='1'");

$up_2 = mysql_query("UPDATE forums SET cid='$new_id' WHERE cid='$id' AND subforum='1'");

$f_up_2 = mysql_query("UPDATE topics SET fid='$new_id' WHERE fid='$id' AND subforum='1'");

$up_3 = mysql_query("UPDATE forums SET cid='$id' WHERE cid='0' AND subforum='1'");

$f_up_3 = mysql_query("UPDATE topics SET fid='$id' WHERE fid='0' AND subforum='1'");



header ("Location: index.php?page=forums");



} else if(($_GET[d] == "movedown") && (isset($_GET[id]))){

$id = $_GET['id'];

$new_id_q_d = "SELECT * FROM forums WHERE id='$id' AND subforum='0'";

$new_id_r_d = mysql_query($new_id_q_d);

$d = mysql_fetch_assoc($new_id_r_d);



$new_id_q = "SELECT * FROM forums WHERE id > '$id' AND cid='$d[cid]' AND subforum='0' ORDER BY id ASC LIMIT 1";

$new_id_r = mysql_query($new_id_q);

$f = mysql_fetch_assoc($new_id_r);

$new_id = $f['id'];

$down_1 = mysql_query("UPDATE forums SET id='0' WHERE id='$new_id'") or die (mysql_error());

$f_down_1 = mysql_query("UPDATE topics SET fid='0' WHERE fid='$new_id'") or die (mysql_error());

$down_2 = mysql_query("UPDATE forums SET id='$new_id' WHERE id='$id'") or die (mysql_error());

$f_down_2 = mysql_query("UPDATE topics SET fid='$new_id' WHERE fid='$id'") or die (mysql_error());

$down_3 = mysql_query("UPDATE forums SET id='$id' WHERE id='0'") or die (mysql_error());

$f_down_3 = mysql_query("UPDATE topics SET fid='$id' WHERE fid='0'") or die (mysql_error());



$up_1 = mysql_query("UPDATE forums SET cid='0' WHERE cid='$new_id' AND subforum='1'");

$f_up_1 = mysql_query("UPDATE topics SET fid='0' WHERE fid='$new_id' AND subforum='1'");

$up_2 = mysql_query("UPDATE forums SET cid='$new_id' WHERE cid='$id' AND subforum='1'");

$f_up_2 = mysql_query("UPDATE topics SET fid='$new_id' WHERE fid='$id' AND subforum='1'");

$up_3 = mysql_query("UPDATE forums SET cid='$id' WHERE cid='0' AND subforum='1'");

$f_up_3 = mysql_query("UPDATE topics SET fid='$id' WHERE fid='0' AND subforum='1'");



header ("Location: index.php?page=forums");



} else {

$select_cats = mysql_query("SELECT * FROM categories ORDER BY id");

echo "<table width=100%><tr><td align=left>";

while ($r = mysql_fetch_assoc($select_cats)){

echo "<table width=100% cellspacing=0 cellpadding=0 class=category_table>

	<tr class=table_1_header>

	<td colspan=12>

	<table width=100% cellpadding=0 cellspacing=0 border=0>

	<tr>

	<td width=100% class=table_1_header>

	<img src=\"images/arrow_up.gif\"> <b>$r[name]</b>

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

	<td align=left class=table_subheader colspan=2>

	Name

	</td>

	<td width=75 align=center class=table_subheader>

	Topics

	</td>

	<td width=75 align=center class=table_subheader>

	Replies

	</td>

	<td width=100 align=center class=table_subheader>

	Reply Access

	</td>

	<td width=50 align=center class=table_subheader>

	Hidden

	</td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	</tr>";

$forum_number_count = 0;

$even_number_count = 0;

$select_fors = mysql_query("SELECT * FROM forums WHERE cid='$r[id]' AND subforum='0' ORDER BY id");

$forum_count = mysql_num_rows($select_fors);

while ($rr = mysql_fetch_assoc($select_fors)){


	if($rr[news] == 1){
		$news = "_news";
	} else {
		$news = NULL;
	}


	if($rr[hidden] == 0){

		$hidden = "No";

	} else {

		$hidden = "Yes";

	}



	if($rr[restricted_level] == 1){

		$access = "Everyone";

	} else if($rr[restricted_level] == 2){

		$access = "Moderators";

	} else if($rr[restricted_level] == 3){

		$access = "Administrators";

	}



		$f_result = mysql_query("select * from topics WHERE fid='$rr[id]'");

		while($t_row=mysql_fetch_array($f_result)){

			$replies_count_result = mysql_query("SELECT * FROM replies WHERE tid='$t_row[id]'");

			$r_count = mysql_num_rows($replies_count_result);

			$reply_count = $reply_count + $r_count;

		}



	$topic_count_result = mysql_query("SELECT * FROM topics WHERE fid='$rr[id]'");

	$t_count = mysql_num_rows($topic_count_result);



	if(!($even_number_count % 2) == TRUE){

		$even_number = 1;

	} else {

		$even_number = 2;

	}



	$even_number_count = $even_number_count + 1;

	$forum_number_count = $forum_number_count + 1;

	echo "<tr>

		<td align=center>

		<img src=\"images/forums". $news ."_img.gif\" border=0>

		</td>

		<td colspan=2 class=\"row$even_number\"  onmouseover=\"this.className='row". $even_number ."_on';\" onmouseout=\"this.className='row$even_number';\">

		<a href=\"index.php?page=forums&cid=$rr[id]\">$rr[name]</a>

		</td>

		<td align=center class=\"row$even_number\">

		$t_count

		</td>

		<td align=center class=\"row$even_number\">

		$reply_count

		</td>

		<td align=center class=\"row$even_number\">

		$access

		</td>

		<td align=center class=\"row$even_number\">

		$hidden

		</td>

		<td class=\"row2\" align=center>

		<a href=\"index.php?page=forums&d=edit&id=$rr[id]\"><img src=\"images/forums_edit_btn.gif\" border=0></a></td>

		<td class=\"row2\" align=center>

		<a href=\"index.php?page=forums&d=delete&id=$rr[id]\"><img src=\"images/forums_delete_btn.gif\" border=0></a></td>

		<td class=\"row2\" align=center>";

if($forum_number_count != 1){

		echo "<a href=\"index.php?page=forums&d=moveup&id=$rr[id]\"><img src=\"images/cat_up_btn.gif\" border=0></a>";

}

echo "</td><td class=\"row2\" align=center>";

if($forum_number_count != $forum_count){

		echo "<a href=\"index.php?page=forums&d=movedown&id=$rr[id]\"><img src=\"images/cat_down_btn.gif\" border=0></a>";

}

		echo "</td>

		<td class=\"row2\" align=center>

		<a href=\"index.php?page=moderators&fid=$rr[id]\"><img src=\"images/mods_btn.gif\" border=0></a></td>

		</tr>";

	$reply_count = 0;

	$subforum_number_count = 0;

	$select_subfors = mysql_query("SELECT * FROM forums WHERE cid='$rr[id]' AND subforum='1' ORDER BY id");

	$subforum_count = mysql_num_rows($select_subfors);

	while ($rrr = mysql_fetch_assoc($select_subfors)){



	if($rrr[hidden] == 0){

		$hidden = "No";

	} else {

		$hidden = "Yes";

	}



	if($rrr[restricted_level] == 1){

		$access = "Everyone";

	} else if($rrr[restricted_level] == 2){

		$access = "Moderators";

	} else if($rrr[restricted_level] == 3){

		$access = "Administrators";

	}

		$f_result = mysql_query("select * from topics WHERE fid='$rrr[id]'");

		while($t_row=mysql_fetch_array($f_result)){

			$replies_count_result = mysql_query("SELECT * FROM replies WHERE tid='$t_row[id]'");

			$r_count = mysql_num_rows($replies_count_result);

			$reply_count = $reply_count + $r_count;

		}



	$topic_count_result = mysql_query("SELECT * FROM topics WHERE fid='$rrr[id]'");

	$t_count = mysql_num_rows($topic_count_result);



		if(!($even_number_count % 2) == TRUE){

			$even_number = 1;

		} else {

			$even_number = 2;

		}

	

		$even_number_count = $even_number_count + 1;

		$subforum_number_count = $subforum_number_count + 1;

		echo "<tr>

			<td align=center>

			</td>

			<td align=center width=60>

			<img src=\"images/subforums_img.gif\" border=0>

			</td>

			<td class=\"row$even_number\"  onmouseover=\"this.className='row". $even_number ."_on';\" onmouseout=\"this.className='row$even_number';\">

			<a href=\"index.php?page=forums&cid=$rrr[id]\">$rrr[name]</a>

			</td>

			<td align=center class=\"row$even_number\">

			$t_count

			</td>

			<td align=center class=\"row$even_number\">

			$reply_count

			</td>

			<td align=center class=\"row$even_number\">

			$access

			</td>

			<td align=center class=\"row$even_number\">

			$hidden

			</td>

			<td class=\"row2\" align=center>

			<a href=\"index.php?page=forums&d=edit&id=$rrr[id]\"><img src=\"images/forums_edit_btn.gif\" border=0></a></td>

			<td class=\"row2\" align=center>

			<a href=\"index.php?page=forums&d=delete&id=$rrr[id]\"><img src=\"images/forums_delete_btn.gif\" border=0></a></td>

			<td class=\"row2\" align=center>";

		if($subforum_number_count != 1){

			echo "<a href=\"index.php?page=forums&d=moveup&id=$rrr[id]\"><img src=\"images/cat_up_btn.gif\" border=0></a>";

		}

		echo "</td><td class=\"row2\" align=center>";

		if($subforum_number_count != $subforum_count){

			echo "<a href=\"index.php?page=forums&d=movedown&id=$rrr[id]\"><img src=\"images/cat_down_btn.gif\" border=0></a>";

		}

			echo "</td>

			<td class=\"row2\" align=center>

			<a href=\"index.php?page=moderators&fid=$rr[id]\"><img src=\"images/mods_btn.gif\" border=0></a></td>

			</tr>";

	}

}



echo "</table><br><br>";

}

echo "</tr></td></table>";

}

} else {

	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";

}

?>

</BODY></HTML>