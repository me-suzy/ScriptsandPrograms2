<?PHP

if($_userlevel == 3){

echo "<a href=\"index.php\"><img src=\"images/home_lnk.gif\" border=0 alt=\"\"></a><img src=\"images/spacer.gif\" border=0 alt=\"\" width=5><img src=\"images/categories_title.gif\" border=0 alt=\"\"><p>";

echo "<div align=right><a href=index.php?page=categories&d=new><img src=images/new_cat.gif border=0></a></div>";

if($_GET[d] == "new"){

	if($_GET[step] == 2){

		$query_c = "INSERT INTO categories (name) VALUES ('$_POST[cname]')";

		$result = mysql_query($query_c);

			

		header("Location: index.php?page=categories");

	} else {

	echo "<center><table width=100% class=category_table cellpadding=0 cellspacing=0><tr><td class=table_1_header><b>Create a new category<b></td></tr><tr><td>";

		echo "<form action=index.php?page=categories&d=new&step=2 method=post name=form>";

		echo "<table border=0 cellpadding=0 cellspacing=0 width=100%>";

		echo "<tr><td align=center class=contentmain align=center>

		<table border=0 cellpadding=0 cellspacing=0><tr><td>Name:</td>

		<td class=contentmain><input type=text class=textbox name=cname value=\"$c_name\" size=50></td></tr></table>";

	    echo "</td></tr><tr><td colspan=2 align=center class=table_bottom><input type=submit value='Add'></td></tr>";

		echo "</table></center>";

	}

} else if(($_GET[d] == "edit") && (isset($_GET[id]))){

	if($_GET[step] == 2){

		$query_c = "UPDATE categories SET name='$_POST[cname]', level_limit='$_POST[level]' WHERE id='$_GET[id]'";

		$result = mysql_query($query_c);

			

		header("Location: index.php?page=categories");

	} else {

		$get_cat_info = mysql_query("SELECT * FROM categories WHERE id='$_GET[id]'");

		$row = mysql_fetch_array($get_cat_info);



echo "<center><table width=100% class=category_table cellpadding=0 cellspacing=0><tr><td class=table_1_header><b>Edit category<b></td></tr><tr><td>

	<form action=index.php?page=categories&d=edit&id=$_GET[id]&step=2 method=post name=form>

	<table border=0 cellpadding=0 cellspacing=0 width=100%>

	<tr><td align=center class=contentmain align=center>

	<table border=0 cellpadding=0 cellspacing=0><tr><td width=100>Name:</td>

	<td class=contentmain><input type=text class=textbox name=cname value=\"$row[name]\" size=50></td></tr>

	<tr><td>Accessable To:</td>

	<td class=contentmain>";

		if($row[level_limit] == '1'){

			echo "<input type='radio' class='form' name='level' value='1' checked=checked> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2'> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3'> <i>Administrators</i>";

		} else if($row[level_limit] == '2'){

			echo "<input type='radio' class='form' name='level' value='1'> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2' checked=checked> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3'> <i>Administrators</i>";

		} else if($row[level_limit] == '3'){

			echo "<input type='radio' class='form' name='level' value='1'> <i>Everyone</i><br><input type='radio' class='form' name='level' value='2'> <i>Moderators</i><br><input type='radio' class='form' name='level' value='3' checked=checked> <i>Administrators</i>";

		}

	echo "</td></tr>";

	echo "<tr><td align=right class=contentmain>Hidden:</td><td class=contentmain>";

		if($row[hidden] == '0'){

			echo "<input type='radio' class='form' name='hidden' value='0' checked=checked> <i>No</i><br><input type='radio' class='form' name='hidden' value='1'> <i>Yes</i>";

		} else if($row[hidden] == '1'){

			echo "<input type='radio' class='form' name='hidden' value='0' checked=checked> <i>No</i><br><input type='radio' class='form' name='hidden' value='1'> <i>Yes</i>";

		}

	echo "</td></tr></table>

	</td></tr><tr><td colspan=2 align=center class=table_bottom><input type=submit value='Save'></td></tr>

	</table></center>";

	}

} else if(($_GET[d] == "delete") && (isset($_GET[id]))){

	$get_cat_info = mysql_query("SELECT * FROM categories WHERE id='$_GET[id]'");

	$row = mysql_fetch_array($get_cat_info);

	if($_GET['sure'] == "yes"){

		$get_forum_info = mysql_query("SELECT * FROM forums WHERE cid='$_GET[id]'");

		while($f_row = mysql_fetch_array($get_forum_info)){

			$get_topic_info = mysql_query("SELECT * FROM topics WHERE fid='$f_row[id]'");

			while($t_row = mysql_fetch_array($get_topic_info)){

				$delete_replies = mysql_query("DELETE FROM replies WHERE tid='$t_row[id]'");

			}

			$delete_topics = mysql_query("DELETE FROM topics WHERE fid='$f_row[id]'");

		}

		$delete_forums = mysql_query("DELETE FROM forums WHERE cid='$row[id]'");

		$delete_category = mysql_query("DELETE FROM categories WHERE id='$row[id]'");



		header("Location: index.php?page=categories");

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

										Are you sure you would like to delete the category <span class=hilight>$row[name]</span> and everything in it?

									</td>

								</tr>

								<tr>

									<td class=contentmain align=center>

										<table cellpadding=\"3\" cellspacing=\"0\" border=\"0\">

											<tr>

												<td class=\"redtable\" align=center width=100>

													<a href=\"index.php?page=categories&d=delete&id=$_GET[id]&sure=yes\" class=link2>Yes</a>

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

$new_id_q = "SELECT * FROM categories WHERE id < '$id' ORDER BY id DESC LIMIT 1";

$new_id_r = mysql_query($new_id_q);

$f = mysql_fetch_assoc($new_id_r);

$new_id = $f['id'];

$up_1 = mysql_query("UPDATE categories SET id='0' WHERE id='$new_id'");

$f_up_1 = mysql_query("UPDATE forums SET cid='0' WHERE cid='$new_id'");

$up_2 = mysql_query("UPDATE categories SET id='$new_id' WHERE id='$id'");

$f_up_2 = mysql_query("UPDATE forums SET cid='$new_id' WHERE cid='$id'");

$up_3 = mysql_query("UPDATE categories SET id='$id' WHERE id='0'");

$f_up_3 = mysql_query("UPDATE forums SET cid='$id' WHERE cid='0'");



header ("Location: index.php?page=categories");



} else if(($_GET[d] == "movedown") && (isset($_GET[id]))){

$id = $_GET['id'];

$new_id_q = "SELECT * FROM categories WHERE id > '$id' ORDER BY id ASC LIMIT 1";

$new_id_r = mysql_query($new_id_q);

$f = mysql_fetch_assoc($new_id_r);

$new_id = $f['id'];

$down_1 = mysql_query("UPDATE categories SET id='0' WHERE id='$new_id'") or die (mysql_error());

$f_down_1 = mysql_query("UPDATE forums SET cid='0' WHERE cid='$new_id'") or die (mysql_error());

$down_2 = mysql_query("UPDATE categories SET id='$new_id' WHERE id='$id'") or die (mysql_error());

$f_down_2 = mysql_query("UPDATE forums SET cid='$new_id' WHERE cid='$id'") or die (mysql_error());

$down_3 = mysql_query("UPDATE categories SET id='$id' WHERE id='0'") or die (mysql_error());

$f_down_3 = mysql_query("UPDATE forums SET cid='$id' WHERE cid='0'") or die (mysql_error());



header ("Location: index.php?page=categories");



} else {

$evennumber_count = 0;

echo "<table width=100% cellspacing=0 cellpadding=0 class=category_table>

	<tr class=table_1_header>

	<td colspan=10>

	<table width=100% cellpadding=0 cellspacing=0 border=0>

	<tr>

	<td width=100% class=table_1_header>

	<img src=\"images/arrow_up.gif\"> <b>Categories</b>

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

	Name

	</td>

	<td width=75 align=center class=table_subheader>

	Topics

	</td>

	<td width=75 align=center class=table_subheader>

	Replies

	</td>

	<td width=100 align=center class=table_subheader>

	Accessable To

	</td>

	<td width=50 align=center class=table_subheader>

	Hidden

	</td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	<td width=40 align=center class=table_subheader></td>

	</tr>";



	$c_result = mysql_query("select * from categories ORDER BY id ASC");

	$c_count = mysql_num_rows($c_result);

	while($row=mysql_fetch_array($c_result)){



		$topic_count = 0;

		$reply_count = 0;

		$f_result = mysql_query("select * from forums WHERE cid='$row[id]'");

		while($f_row=mysql_fetch_array($f_result)){

			$topic_count_result = mysql_query("SELECT * FROM topics WHERE fid='$f_row[id]'");

			$t_count = mysql_num_rows($topic_count_result);

			$topic_count = $topic_count + $t_count;



			while($t_row=mysql_fetch_array($topic_count_result)){

				$reply_count_result = mysql_query("SELECT * FROM replies WHERE tid='$t_row[id]'");

				$r_count = mysql_num_rows($reply_count_result);

				$reply_count = $reply_count + $r_count;

			}

		}



	if($row[hidden] == 0){

		$hidden = "No";

	} else {

		$hidden = "Yes";

	}



	if($row[level_limit] == 1){

		$access = "Everyone";

	} else if($row[level_limit] == 2){

		$access = "Moderators";

	} else if($row[level_limit] == 3){

		$access = "Administrators";

	}

	

	if(!($even_number_count % 2) == TRUE){

		$even_number = 1;

	} else {

		$even_number = 2;

	}



	$even_number_count = $even_number_count + 1;



	echo "<tr>

		<td align=center>

		<img src=\"images/cat_img.gif\" border=0>

		</td>

		<td class=\"row$even_number\"  onmouseover=\"this.className='row". $even_number ."_on';\" onmouseout=\"this.className='row$even_number';\">

		<a href=\"index.php?page=forums&cid=$row[id]\">$row[name]</a>

		</td>

		<td align=center class=\"row$even_number\">

		$topic_count

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

		<a href=\"index.php?page=categories&d=edit&id=$row[id]\"><img src=\"images/cat_edit_btn.gif\" border=0></a></td>

		<td class=\"row2\" align=center>

		<a href=\"index.php?page=categories&d=delete&id=$row[id]\"><img src=\"images/cat_delete_btn.gif\" border=0></a></td>

		<td class=\"row2\" align=center>";

if($even_number_count != 1){

		echo "<a href=\"index.php?page=categories&d=moveup&id=$row[id]\"><img src=\"images/cat_up_btn.gif\" border=0></a>";

}

echo "</td><td class=\"row2\" align=center>";

if($even_number_count != $c_count){

		echo "<a href=\"index.php?page=categories&d=movedown&id=$row[id]\"><img src=\"images/cat_down_btn.gif\" border=0></a>";

}

		echo "</td></tr>";

	}

echo "</table>";

}

} else {

	echo "<center><span class=red>You need to be an admin to access this page!</span></center>";

}

?>