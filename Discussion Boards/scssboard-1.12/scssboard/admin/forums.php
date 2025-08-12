<?php
/*
** sCssBoard, an extremely fast and flexible CSS-based message board system
** Copyright (CC) 2005 Elton Muuga
**
** This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike License. 
** To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/2.0/ or send 
** a letter to Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
*/
?>

<?php
if ($current_user[users_level] < 3) {
	die("Insufficient access level.");
}

$categories_query = @mysql_query("select * from $_CON[prefix]categories order by category_order, category_id asc");

include("admin/navbar.php");
echo "
<br />
<table width='500' border='0' cellpadding='2' cellspacing='2' align='center'>";

//**********************************************************************
// Begin Create CATEGORY
//**********************************************************************

if ($_GET[createcat]) {

	if (!$_GET[go]) {

		echo "
<form action='?act=admin-forums&amp;createcat=yes&amp;go=yes' method='post'>
<tr>
	<td colspan='2' class='catheader' align='center' style='font-size:14px;'><strong>Create Category</strong></td>
</tr>
<tr>
	<td class='forum_stat_hd' align='center' width='250'>Category Name:</td>
	<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='cat_name'></td>
</tr>
<tr>
	<td class='forum_stat_hd' align='center' colspan='2'><input type='submit' name='submit' value='Create'></td>
</tr>";

	} else {

		$done = @mysql_query("insert into $_CON[prefix]categories(category_name) values('$_POST[cat_name]')");
		if ($done == 1) {
		echo "<center><b>Category ($_POST[cat_name]) created.</b></center><br /><br />";
		echo redirect("?act=admin-forums", 1);
		} else {
		echo "<center><b>Sorry, I was unable to create the category.</b></center><br /><br />";
		}
	}

//**********************************************************************
// Begin Edit CATEGORY
//**********************************************************************

} elseif ($_GET[editcat]) {

	if (!$_GET[go]) {

$category_2edit = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]categories where category_id = '$_GET[editcat]'"));
	
echo "
<form action='?act=admin-forums&amp;editcat=$_GET[editcat]&amp;go=yes' method='post'>
<tr>
	<td colspan='2' class='catheader' align='center' style='font-size:14px;'><strong>Edit Category</strong></td>
</tr>
<tr>
	<td class='forum_stat_hd' align='center' width='250'>Category Name:</td>
	<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='cat_name' value='$category_2edit[category_name]'></td>
</tr>
<tr>
	<td class='forum_stat_hd' align='center' colspan='2'><input type='submit' name='submit' value='Save'></td>
</tr>
</form>";

	} else {

		if ($_POST[cat_name] != "") {
			$done = @mysql_query("update $_CON[prefix]categories set category_name = '$_POST[cat_name]' where category_id = '$_GET[editcat]'");
			echo redirect("?act=admin-forums", 0);
		} else {
			echo "<center><b>Error: Category name was left blank.</b></center><br /><br />";
		}
	
	}
	
//**********************************************************************
// Begin Delete CATEGORY
//**********************************************************************

} elseif ($_GET[delcat]) {

	if (!$_GET[go]) {
		$category_2del = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]categories where category_id = '$_GET[delcat]'"));

		echo "<p align='center' style='font-weight:bold; '>Confirm deletion of category $_GET[delcat] ( $category_2del[category_name] ).</p><br />
		
		<span class='main_button'><a href='?act=admin-forums&amp;delcat=$_GET[delcat]&amp;go=yes'>OK</a></span> &nbsp; <span class='main_button'><a href='?act=admin-forums'>Cancel</a></span></center><br /><br />";
	} else {
		@mysql_query("delete from $_CON[prefix]categories where category_id = '$_GET[delcat]'");
		echo redirect("?act=admin-forums", 1);
	}


//**********************************************************************
// Begin Create Forum
//**********************************************************************

} elseif ($_GET[createforum]) {

	if (!$_POST[forum_name]) { //No forum name was sent here, so we need to fill stuff in

		echo "
			<form action='?act=admin-forums&amp;createforum=yes' method='post'>
				<tr>
					<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
						<strong>Create Forum</strong>
					</td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>Forum Name:</td>					
					<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='forum_name'></td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>Forum Description:</td>					
					<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='forum_description'></td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>Category:</td>
					<td class='forum_stat_hd' align='center' width='250'>";
						//Fetch all existing categories
						$categories = @mysql_query("select * from $_CON[prefix]categories");
						echo "<select name='forum_category'>";
						while($showcat = @mysql_fetch_array($categories)) {
							echo "<option value='$showcat[category_id]'>$showcat[category_name]</option>"; //Add as an option in dropdown
						}
						echo "</select>
					</td>
				</tr>
				<tr>
					<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
						<strong>Forum Permissions</strong>
					</td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>View Forum:</td>
					<td class='forum_stat_hd' align='center' width='250'>
						<select name='forums_p_read'>
							<option value='0' selected='selected'>0 (Everybody)</option>
							<option value='1'>1 (Members)</option>
							<option value='2'>2 (Moderators)</option>
							<option value='3'>3 (Administrators)</option>
							<option value='4'>4 (Owner)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>Create Topics:</td>
					<td class='forum_stat_hd' align='center' width='250'>
						<select name='forums_p_topic'>
							<option value='0'>0 (Everybody)</option>
							<option value='1' selected='selected'>1 (Members)</option>
							<option value='2'>2 (Moderators)</option>
							<option value='3'>3 (Administrators)</option>
							<option value='4'>4 (Owner)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' width='250'>Reply to Topics:</td>
					<td class='forum_stat_hd' align='center' width='250'>
						<select name='forums_p_reply'>
							<option value='0'>0 (Everybody)</option>
							<option value='1' selected='selected'>1 (Members)</option>
							<option value='2'>2 (Moderators)</option>
							<option value='3'>3 (Administrators)</option>
							<option value='4'>4 (Owner)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td class='forum_stat_hd' align='center' colspan='2'><input type='submit' name='submit' value='Create'></td>
				</tr>";

	} else { //A forum name was sent, so let's add it to the database!

			$done = @mysql_query("insert into $_CON[prefix]forums(forums_name,forums_category,forums_description,forums_p_read,forums_p_topic,forums_p_reply) values('$_POST[forum_name]','$_POST[forum_category]','$_POST[forum_description]',$_POST[forums_p_read],$_POST[forums_p_topic],$_POST[forums_p_reply])");
			echo "<center><b>A new forum titled <em><strong>$_POST[forum_name]</strong></em> was created.</b></center><br /><br />";
			echo redirect("?act=admin-forums", 1);
		
	}


//**********************************************************************
// Begin Edit Forum
//**********************************************************************

} elseif ($_GET[editforum]) {

	if (!$_POST[forums_name]) {
		$forum_2edit = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = '$_GET[editforum]'"));
	
		echo "
		<form action='?act=admin-forums&amp;editforum=$_GET[editforum]&amp;go=yes' method='post'>
		<tr>
			<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
				<strong>Edit Forum [ID $_GET[editforum]]</strong>
			</td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' width='250'>Forum Name:</td>
			<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='forums_name' value='$forum_2edit[forums_name]'></td>
		</tr>
			<tr>
			<td class='forum_stat_hd' align='center' width='250'>Forum Description:</td>
			<td class='forum_stat_hd' align='center' width='250'><input type='text' class='input' name='forums_desc' value='$forum_2edit[forums_description]'></td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' width='250'>Forum Category:</td>
			<td class='forum_stat_hd' align='center' width='250'>";

				$categories = @mysql_query("select * from $_CON[prefix]categories");
				echo "<select name='forums_category'>";
				while($showcat = @mysql_fetch_array($categories)) {
					echo "<option value='$showcat[category_id]'";
					if ($showcat[category_id] == $forum_2edit[forums_category]) {
						echo " selected='yes'";
					}
					echo ">$showcat[category_name]</option>";
				}
				echo "</select>
			</td>
		</tr>
		<tr>
			<td colspan='2' class='catheader' align='center' style='font-size:14px;'>
				<strong>Forum Permissions</strong>
			</td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' width='250'>View Forum:</td>
			<td class='forum_stat_hd' align='center' width='250'>
				<select name='forums_p_read'>
					<option value='0'"; 
					if($forum_2edit[forums_p_read] == 0) { echo " selected='yes'"; }
					echo ">0 (Everybody)</option>
					<option value='1'"; 
					if($forum_2edit[forums_p_read] == 1) { echo " selected='yes'"; }
					echo ">1 (Members)</option>
					<option value='2'"; 
					if($forum_2edit[forums_p_read] == 2) { echo " selected='yes'"; }
					echo ">2 (Moderators)</option>
					<option value='3'"; 
					if($forum_2edit[forums_p_read] == 3) { echo " selected='yes'"; }
					echo ">3 (Administrators)</option>
					<option value='4'"; 
					if($forum_2edit[forums_p_read] == 4) { echo " selected='yes'"; }
					echo ">4 (Owner)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' width='250'>Create Topics:</td>
			<td class='forum_stat_hd' align='center' width='250'>
				<select name='forums_p_topic'>
					<option value='0'"; 
					if($forum_2edit[forums_p_topic] == 0) { echo " selected='yes'"; }
					echo ">0 (Everybody)</option>
					<option value='1'"; 
					if($forum_2edit[forums_p_topic] == 1) { echo " selected='yes'"; }
					echo ">1 (Members)</option>
					<option value='2'"; 
					if($forum_2edit[forums_p_topic] == 2) { echo " selected='yes'"; }
					echo ">2 (Moderators)</option>
					<option value='3'"; 
					if($forum_2edit[forums_p_topic] == 3) { echo " selected='yes'"; }
					echo ">3 (Administrators)</option>
					<option value='4'"; 
					if($forum_2edit[forums_p_topic] == 4) { echo " selected='yes'"; }
					echo ">4 (Owner)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' width='250'>Reply to Topics:</td>
			<td class='forum_stat_hd' align='center' width='250'>
				<select name='forums_p_reply'>
					<option value='0'"; 
					if($forum_2edit[forums_p_reply] == 0) { echo " selected='yes'"; }
					echo ">0 (Everybody)</option>
					<option value='1'"; 
					if($forum_2edit[forums_p_reply] == 1) { echo " selected='yes'"; }
					echo ">1 (Members)</option>
					<option value='2'"; 
					if($forum_2edit[forums_p_reply] == 2) { echo " selected='yes'"; }
					echo ">2 (Moderators)</option>
					<option value='3'"; 
					if($forum_2edit[forums_p_reply] == 3) { echo " selected='yes'"; }
					echo ">3 (Administrators)</option>
					<option value='4'"; 
					if($forum_2edit[forums_p_reply] == 4) { echo " selected='yes'"; }
					echo ">4 (Owner)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class='forum_stat_hd' align='center' colspan='2'><input type='submit' name='submit' value='Save'></td>
		</tr>
		</form>";

	} else {

			$done = @mysql_query("update $_CON[prefix]forums set forums_name = '$_POST[forums_name]', forums_category = '$_POST[forums_category]', forums_description = '$_POST[forums_desc]', forums_p_read = '$_POST[forums_p_read]', forums_p_topic = '$_POST[forums_p_topic]', forums_p_reply = '$_POST[forums_p_reply]' where forums_id = '$_GET[editforum]'");
				echo "<center><b>Forum updated.</b></center><br /><br />";
				echo redirect("?act=admin-forums", 1);
	}

//**********************************************************************
// Begin Delete Forum
//**********************************************************************

} elseif ($_GET[delforum]) {

	if (!$go) {

		$forum_2del = @mysql_fetch_array(@mysql_query("select * from $_CON[prefix]forums where forums_id = '$_GET[delforum]'"));
		echo "<center><b>Are you sure you wish to delete <em>$forum_2del[forums_name]</em>?<br />
		All topics and posts currently in the forum will be deleted.</b><br /><br />
		<span class='main_button'><a href='?act=admin-forums&amp;delforum=$_GET[delforum]&amp;go=yes'>OK</a></span> &nbsp; 
		<span class='main_button'><a href='?act=admin-forums'>Cancel</a></span></center><br /><br />";

	} else {
		
		@mysql_query("delete from $_CON[prefix]posts where posts_forum = '$_GET[delforum]'");
		@mysql_query("delete from $_CON[prefix]forums where forums_id = '$_GET[delforum]'");
		echo "<center><b>Forum [ ID $_GET[delforum] ] deleted.</b></center><br /><br />";
		echo redirect("?act=admin-forums", 1);
	}

//**********************************************************************
// Begin Forum Reorder
//**********************************************************************

} elseif ($_GET[reorder]) {

	$cat_order = $_POST[cat_order];
	$forum_order = $_POST[forum_order];

	foreach($cat_order as $c_id => $c_order) {

		@mysql_query("update $_CON[prefix]categories set category_order = '$c_order' where category_id = '$c_id'");
	
	}

	foreach($forum_order as $f_id => $f_order) {

		@mysql_query("update $_CON[prefix]forums set forums_order = '$f_order' where forums_id = '$f_id'");
	
	}

	echo redirect("?act=admin-forums");


//**********************************************************************
// Begin Forum Listing
//**********************************************************************

} else {

	echo "<tr>
		<td colspan='3' class='catheader' align='center' style='font-size:14px;'><strong>Categories/Forums</strong></td>
	</tr>
	<tr>
		<td class='forum_stat_hd' align='center' width='25'>	Order	</td>
		<td class='forum_stat_hd' align='center' width='400'>	Name	</td>
		<td class='forum_stat_hd' align='center' width='100'>	Config	</td>
	</tr>";
	
	echo "<form action='index.php?act=admin-forums&amp;reorder=1' method='post' name='reorderForm'>";

	while ($cat_show = @mysql_fetch_array($categories_query)) {

		$prelim_forums_query = mysql_query("select forums_id from $_CON[prefix]forums where forums_category = '$cat_show[category_id]'");

			echo "
			<tr>
				<td class='catheader' align='center'>	<input type='text' name='cat_order[$cat_show[category_id]]' class='input' style='width:25px; text-align:center;' value='$cat_show[category_order]' />			</td>
				<td class='catheader'>
					$cat_show[category_name]
				</td>
				<td class='catheader' style='font-size:12px;' align='center'>
					<a href='?act=admin-forums&amp;editcat=$cat_show[category_id]'>Edit</a>";
					if (@mysql_num_rows($prelim_forums_query) == 0) {
						echo " | <a href='?act=admin-forums&amp;delcat=$cat_show[category_id]'>Del</a>";
					}
				echo "</td>
			</tr>";

		$forums_query = @mysql_query("select * from $_CON[prefix]forums where forums_category = '$cat_show[category_id]' order by forums_order asc");

	while ($forum_show = @mysql_fetch_array($forums_query)) {
		echo "
		<tr>
			<td class='forum_name' align='center'>	<input type='text' name='forum_order[$forum_show[forums_id]]' class='input' style='width:25px; text-align:center;' value='$forum_show[forums_order]' />			</td>
			<td class='forum_name'>					$forum_show[forums_name]		</td>
			<td class='forum_name' align='center'>
				<a href='?act=admin-forums&amp;editforum=$forum_show[forums_id]'>Edit</a> | 
				<a href='?act=admin-forums&amp;delforum=$forum_show[forums_id]'>Del</a>
			</td>
		</tr>";
	}
	}

	echo "<tr>
		<td colspan='3' class='forum_stat_hd' align='center' style='padding:10px;'>
			<span class='main_button'><strong><a href='javascript:document.reorderForm.submit();'>Reorder Forums/Categories</a></strong></span>
		</td>
	</tr>";
	echo "<tr>
		<td colspan='3' class='forum_stat_hd' align='center' style='padding:10px;'>
			<span class='main_button'><strong><a href='?act=admin-forums&amp;createcat=yes'>Create New Category</a></strong></span> &nbsp; <span class='main_button'><strong><a href='?act=admin-forums&amp;createforum=yes'>Create New Forum</a></strong></span>
		</td>
	</tr>";
	echo "</form>";
}

echo "</table>";
?>