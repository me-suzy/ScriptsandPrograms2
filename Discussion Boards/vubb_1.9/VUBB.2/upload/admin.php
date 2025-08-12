<?php
/*
Copyright 2005 VUBB
*/
// Start timer
$start_time = explode(' ', microtime());

// Start the session!
session_start();

// Get the settings
include('includes/settings.php');

// Get the settings
include('includes/header.php');

// Check for correct rank
if (!isset($_SESSION['user']) || !isset($_SESSION['pass']) || $user_info['group'] != '4' || $user_info['group'] == '') 
{	
	admin_error($lang['title']['no_access'], $lang['text']['no_access']);
}

echo "
<div align='center'>
<table width='90%' border='0' cellspacing='0' cellpadding='2'>
<tr>
<td colspan='2' class='head_block'>
" . $lang['title']['admin_area'] . "
</td>
</tr>
<tr> 
<td width=\"19%\" valign=\"top\" class=\"contentbox1\"> 
<a href=\"admin.php\">" . $lang['text']['admin_home'] . "</a><br />
<a href=\"index.php\">" . $lang['text']['back_to_forum'] . "</a><br /> 
<br />
<a href=\"admin.php?view=general\">" . $lang['text']['general'] . "</a><br />
<br />
<strong><em>" . $lang['text']['forum_controls'] . "</em></strong><br />
<a href='admin.php?view=addcats'>" . $lang['text']['add_cat'] . "</a><br />
<a href='admin.php?view=addforum'>" . $lang['text']['add_forum'] . "</a><br /> 
<a href='admin.php?view=managecf'>" . $lang['text']['manage'] . "</a><br />
<br /> 
<strong><em>" . $lang['text']['member_controls'] . "</em></strong><br /> 
<a href='admin.php?view=lock'>" . $lang['mix']['lock_member'] . "</a><br />
<a href='admin.php?view=unlock'>" . $lang['mix']['unlock_member'] . "</a><br />
<a href='admin.php?view=del'>" . $lang['text']['delete_member'] . "</a><br />
<a href='admin.php?view=group'>" . $lang['text']['group_changer'] . "</a><br /> 
<a href='admin.php?view=members'>" . $lang['text']['members_list'] . "</a><br /> 
<br /> 
<strong><em>" . $lang['text']['group_controls'] . "</em></strong><br />
<a href='admin.php?view=addgroups'>" . $lang['text']['create_group'] . "</a><br />
<a href='admin.php?view=deletegroup'>" . $lang['text']['delete_group'] . "</a><br />
<a href='admin.php?view=editgroups'>" . $lang['text']['edit_group'] . "</a><br />
<br />
<strong><em>" . $lang['text']['styles_controls'] . "</em></strong><br />
<a href='admin.php?view=styles'>" . $lang['text']['style_config'] . "</a><br />
</td>
<td width='81%' valign='top' class='contentbox1'>
";

// Start
if (!isset($_GET['view']) && !isset($_GET['action']))
{
	echo $lang['text']['admin_welcome'] . $user_info['user'];
}

// General
if (isset($_GET['view']) && $_GET['view'] == "general")
{
	echo "
	<form method='post' action='admin.php?action=general'>
	<strong>" . $lang['text']['forum_name'] . "</strong><br />
	<input type='text' name='fname' value='" . $site_config['site_name'] . "'><br />
	<strong>" . $lang['text']['forum_url'] . "</strong><br />
	<input type='text' name='furl' value='" . $site_config['site_url'] . "'><br />
	<strong>" . $lang['text']['forum_path'] . "</strong><br />
	<input type='text' name='fpath' value='" . $site_config['site_path'] . "'><br />
	<strong>" . $lang['text']['new_reg'] . "</strong><br />
	";
	
	if ($site_config['new_registrations'] == '1')
	{
		echo "<input type=\"checkbox\" name=\"new_reg\" value=\"checkbox\" checked>";
	}
	
	else
	{
		echo "<input type=\"checkbox\" name=\"new_reg\" value=\"checkbox\">";
	}
	
	echo "
	<br />
	<strong>" . $lang['text']['website_name'] . "</strong><br />
	<input type='text' name='sname' value='" . $site_config['website_name'] . "'><br />
	<strong>" . $lang['text']['website_url'] . "</strong><br />
	<input type='text' name='surl' value='" . $site_config['website_link'] . "'><br />
	<input type='submit' value='" . $lang['submit']['edit'] . "'>
	</form>
	";
}

// Edit Settings Fix.
if ($_GET['action'] == "general"){
if (!empty ($_POST['fname'])){
mysql_query("UPDATE `config` SET `value`='".$_POST['fname']."' WHERE name='site_name'");
}
if (!empty ($_POST['furl'])){
mysql_query("UPDATE `config` SET `value`='".$_POST['furl']."' WHERE name='site_url'");
}
if (!empty ($_POST['fpath'])){
mysql_query("UPDATE `config` SET `value`='".$_POST['fpath']."' WHERE name='site_path'");
}
if (!empty ($_POST['sname'])){
mysql_query("UPDATE `config` SET `value`='".$_POST['sname']."' WHERE name='website_name'");
}
if (!empty ($_POST['slink'])){
mysql_query("UPDATE `config` SET `value`='".$_POST['slink']."' WHERE name='website_link'");
}

echo $lang['text']['settingsupdated'];
}

if (isset($_GET['action']) && $_GET['action'] == "general")
{
	if (empty($_POST['fname']) || empty($_POST['furl']) || empty($_POST['fpath']))
	{
		admin_error($lang['title']['missing'], $lang['text']['missing']);
	} 
	
	else
	{
		if (isset($_POST['new_reg']))
		{
			$reg = '1';
		}
		
		else
		{
			$reg = '0';
		}
		
		// update forum name
		mysql_query("UPDATE `config` SET `value` = '" . $_POST['fname'] . "' WHERE `id` = '1'") or die(admin_error($lang['title']['error'],mysql_error()));
		// update forum url
		mysql_query("UPDATE `config` SET `value` = '" . $_POST['furl'] . "' WHERE `id` = '2'") or die(admin_error($lang['title']['error'],mysql_error()));
		// update forum path
		mysql_query("UPDATE `config` SET `value` = '" . $_POST['fpath'] . "' WHERE `id` = '3'") or die(admin_error($lang['title']['error'],mysql_error()));
		// update registrations on/off
		mysql_query("UPDATE `config` SET `value` = '" . $reg . "' WHERE `id` = '5'") or die(admin_error($lang['title']['error'],mysql_error()));
		// update website name
		mysql_query("UPDATE `config` SET `value` = '" . $_POST['sname'] . "' WHERE `id` = '8'") or die(admin_error($lang['title']['error'],mysql_error()));
		// update website url
		mysql_query("UPDATE `config` SET `value` = '" . $_POST['surl'] . "' WHERE `id` = '7'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		message('done','done');
	}
}

// Category Admin
if ($_GET['view'] == "addcats") 
{
	echo "
	<form method='post' action='admin.php?action=insertcats'>
	<strong>" . $lang['text']['cat_names'] . "</a><br />
	<textarea name='cats' cols='60' rows='5'></textarea><br />
	<input type='submit' value='" . $lang['submit']['add_cats'] . "'>
	</form>
	";
}

// Forum Admin
if ($_GET['view'] == "addforum") 
{
	// Count categorys
	$num_cats = mysql_num_rows(mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '1'")) or die(admin_error($lang['title']['error'],mysql_error()));
	
	if ($num_cats <= '0')
	{
		message($lang['title']['no_cats'], $lang['text']['no_cats']);
	}
	
	else
	{
		echo "
		<form method='post' action='admin.php?action=insertforum'>
		<strong>" . $lang['text']['forum_name'] . "</strong><br />
		<input type='text' name='forum'><br />
		<strong>" . $lang['text']['forum_desc'] . "</strong><br />
		<input type='text' name='description'><br />
		<strong>" . $lang['text']['forum_ilink'] . "</strong><br />
		<input type='checkbox' name='is_link' value='checkbox'><br />
		<strong>" . $lang['text']['forum_link'] . "</strong><br />
		<input type='text' name='link'><br />
		<table width='270' border='0' cellspacing='0' cellpadding='2'>
		<tr> 
		<td width='80' height='23' valign='top'><strong>" . $lang['text']['permissions'] . "</strong></td>
		<td width='190' valign='top'><div align='right'><strong>" . $lang['text']['can_view'] . "</strong> - <strong>" . $lang['text']['can_post'] . "</strong> - <strong>" . $lang['text']['can_reply'] . "</strong></div></td>
		</tr>
		";

		$select_groups = mysql_query("SELECT * FROM `groups`") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_groups = mysql_fetch_array($select_groups))
		{
			echo "
			<tr> 
			<td height='23' valign='top'>" . $get_groups['name'] . "</td>
			<td valign='top'><div align='right'> 
			<input type='checkbox' name='cview[" . $get_groups['id'] . "]'>
			- 
			<input type='checkbox' name='cpost[" . $get_groups['id'] . "]'>
			-
			<input type='checkbox' name='creply[" . $get_groups['id'] . "]'>
			</div></td>
			</tr>
			";
		}

		echo "
		</table>
		<strong>" . $lang['mix']['category'] . "</strong><br />
		<select name='cat'><br />
		";
		
		$find_cats = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '1'") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_cats = mysql_fetch_array($find_cats))
		{
			echo "<option value='" . $get_cats['id'] . "'>" . $get_cats['name'] . "</option>";
		}
		
		echo "
		</select><br />
		<input type='submit' value='" . $lang['submit']['add_forum'] . "'>
		</form>
		";
	}
}

if ($_GET['action'] == 'insertcats') 
{
	if (empty($_POST['cats'])) 
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		$catagorys = explode("\n", $_POST['cats']);
		
		foreach ($catagorys as $cat)
		{
			$cat = trim($cat);
			// Find the order
			$count_order = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `forums` WHERE `is_cat` = '1'")) or die(admin_error($lang['title']['error'],mysql_error()));
			if ($count_order['count'] <= '0' || $count_order['count'] == null)
			{
				$order = '1';
			}
			
			else
			{
				$order = $count_order['count'] + '1';
			}
			
			mysql_query("INSERT INTO `forums` SET `name` = '" . addslashes($cat) . "', `is_cat` = '1', `order` = '" . $order . "'") or die(admin_error($lang['title']['error'],mysql_error()));
			
			message($lang['mix']['category'], $lang['text']['category_added']);
		}
	}
}

if ($_GET['action'] == 'insertforum') 
{	
	if (!$_POST['forum']) 
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		if (isset($_POST['is_link']))
		{
			$link_check = '1';
		}
		
		else
		{
			$link_check = '0';
		}
		
		// Find the order
		$count_order = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `forums` WHERE `category` = '" . $_POST['cat'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
		if ($count_order['count'] <= '0' || $count_order['count'] == null)
		{
			$order = '1';
		}
		
		else
		{
			$order = $count_order['count'] + '1';
		}
		
		// forum info
		mysql_query("INSERT INTO `forums` SET `name` = '" . addslashes($_POST['forum']) . "', `is_cat` = '0', `category` = '" . $_POST['cat'] . "', `description` = '" . addslashes($_POST['description']) . "', `is_link` = '" . $link_check . "', `link` = '" . $_POST['link'] . "', `order` = '" . $order . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		$forum = mysql_fetch_array(mysql_query("SELECT `id` FROM `forums` ORDER BY `id` DESC LIMIT 1")) or die(admin_error($lang['title']['error'],mysql_error()));
		
		$cpost=$_POST['cpost'];
		$cview=$_POST['cview'];
		$creply=$_POST['creply'];
		
		// Get all IDs and sort them in an array

		$query="SELECT id FROM `groups`";
		$group_ids=mysql_query($query) or die(admin_error($lang['title']['error'],mysql_error()));
		$index=0;
		while ($group=mysql_fetch_assoc($group_ids))
		{
			$ids[$index]=$group['id'];
			$index++;
		}
		
		// Update permissions
		
		for ($ind=0; $ind<$index; $ind++)
		{
			if ($cpost[$ids[$ind]]) 
			{
				$cp = '1'; 
			}
			
			else 
			{
				$cp = '0';
			} 
			
			if ($creply[$ids[$ind]]) 
			{
				$cr = '1'; 
			}
			
			else 
			{
				$cr = '0';
			} 
			
			if ($cview[$ids[$ind]]) 
			{
				$cv = '1'; 
			}
			
			else 
			{
				$cv = '0';
			}
			
			$query="INSERT INTO `permissions` SET `forum` = '" . $forum['id'] . "', `cpost` = '" . $cp . "', `cview` = '" . $cv . "', `creply` = '" . $cr . "', `group` = '" . $ids[$ind] . "'";
			mysql_query($query) or die(admin_error($lang['title']['error'],mysql_error()));
		}
		
		message($lang['text']['forum'], $lang['text']['forum_added']);
	}
}

if ($_GET['view'] == 'managecf')
{	
	echo '
<strong>' . $lang['text']['manage'] . '</strong><br />
<br />';
		
	$find_cats = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '1'");
	while ($get_cats = mysql_fetch_array($find_cats))
	{
		echo "---<a href='admin.php?action=managecf&fid=" . $get_cats['id'] . "'>" . $get_cats['name'] . "</a><br />";
			
		$find_forums = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '0' AND `category` = '" . $get_cats['id'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_forums = mysql_fetch_array($find_forums))
		{
			echo "-<a href='admin.php?action=managecf&fid=" . $get_forums['id'] . "'>" . $get_forums['name'] . "</a><br />";
		}
			
		echo "<br />";	
	}
}

if ($_GET['action'] == 'managecf')
{
	$info = mysql_fetch_array(mysql_query("SELECT * FROM `forums` WHERE `id` = '" . $_GET['fid'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
		
	$info['name'] = stripslashes($info['name']);
	$info['description'] = stripslashes($info['description']);
		
	if ($info['is_cat'] == '1')
	{
		echo "
		<form method='post' action='admin.php?action=editcat&fid=" . $info['id'] . "'>
		" . $lang['text']['cat_name'] . "<br />
		<input type='text' name='category' value='" . $info['name'] . "'><br />
		<input type='submit' name='Submit' value='" . $lang['submit']['edit'] . "'>
		</form>
		<form method='post' action='admin.php?action=deletecat&cid=" . $info['id'] . "'>
		<input type='submit' name='Submit' value='" . $lang['text']['delete'] . "'>
		</form>
		";	
	}
		
	else
	{
		echo "
		<form method='post' action='admin.php?action=editforum&fid=" . $info['id'] . "'>
		<strong>" . $lang['text']['forum_name'] . "</strong><br />
		<input type='text' name='forum' value='" . $info['name'] . "'><br />
		<strong>" . $lang['text']['forum_desc'] . "</strong><br />
		<input type='text' name='description' value='" . $info['description'] . "'><br />
		<strong>" . $lang['text']['forum_ilink'] . "</strong><br />
		";
			
		if ($info['is_link'] == '1')
		{
			echo "
			<input type='checkbox' name='is_link' value='checkbox' checked><br />
			<strong>" . $lang['text']['forum_link'] . "</strong><br />
			<input type='text' name='link' value='" . $info['link'] . "'><br />
			";
		}
			
		else
		{
			echo "
			<input type='checkbox' name='is_link' value='checkbox'><br />
			<strong>" . $lang['text']['forum_link'] . "</strong><br />
			<input type='text' name='link'><br />
			";
		}
			
		echo "
		<table width='400' border='0' cellspacing='0' cellpadding='2'>
		<tr>
		<td width='150' height='23' valign='top'><strong>" . $lang['text']['permissions'] . "</strong></td>
		<td width='250' valign='top'><div align='right'><strong>" . $lang['text']['can_view'] . "</strong> - <strong>" . $lang['text']['can_post'] . "</strong> - <strong>" . $lang['text']['can_reply'] . "</strong></div></td>
		</tr>
		";
	
		$select_groups = mysql_query("SELECT * FROM `groups`") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_groups = mysql_fetch_array($select_groups))
		{
			echo "<tr><td height='23' valign='top'>" . $get_groups['name'] . "</td><td valign='top'><div align='right'>";
			$permissions = mysql_fetch_array(mysql_query("SELECT * FROM `permissions` WHERE `group` = '" . $get_groups['id'] . "' AND `forum` = '" . $_GET['fid'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
				
			if ($permissions['cview'] == '1')
			{
				echo "<input type='checkbox' name='cview[" . $get_groups['id'] . "]' checked>";
			}
				
			else
			{
				echo "<input type='checkbox' name='cview[" . $get_groups['id'] . "]'>";
			}
				
			echo " - ";
				
			if ($permissions['cpost'] == '1')
			{
				echo "<input type='checkbox' name='cpost[" . $get_groups['id'] . "]' checked>";
			}
				
			else
			{
					echo "<input type='checkbox' name='cpost[" . $get_groups['id'] . "]'>";
			}
			
			echo " - ";
			
			if ($permissions['creply'] == '1')
			{
				echo "<input type='checkbox' name='creply[" . $get_groups['id'] . "]' checked>";
			}
				
			else
			{
					echo "<input type='checkbox' name='creply[" . $get_groups['id'] . "]'>";
			}
	
			echo "</div></td></tr>";
		}
	
		echo "
		</table>
		</div>		
		<strong>" . $lang['mix']['category'] . "</strong><br />
		<select name='cat'><br />
		";
			
		$find_cats = mysql_query("SELECT * FROM `forums` WHERE `id` = '" . $info['category'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_cats = mysql_fetch_array($find_cats))
		{
			echo "<option value='" . $get_cats['id'] . "' selected>" . $get_cats['name'] . "</option>";
		}
			
		$find_cats = mysql_query("SELECT * FROM `forums` WHERE `is_cat` = '1' AND `id` != '" . $info['category'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		while ($get_cats = mysql_fetch_array($find_cats))
		{
			echo "<option value='" . $get_cats['id'] . "'>" . $get_cats['name'] . "</option>";
		}
			
		echo "
		</select><br />
		<input type='submit' name='Submit' value='" . $lang['submit']['edit'] . "'>
		</form>
		<form method='post' action='admin.php?action=deletef&fid=" . $info['id'] . "'>
		<input type='submit' name='Submit' value='" . $lang['text']['delete'] . "'>
		</form>
		";
	}
}

if ($_GET['action'] == 'editcat')
{
	if (empty($_POST['category']))
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		mysql_query("UPDATE `forums` SET `name` = '" . addslashes($_POST['category']) . "' WHERE `id` = '" . $_GET['fid'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		message($lang['title']['edited'], $lang['text']['category_edited']);
	}
}

if ($_GET['action'] == 'editforum')
{
	if (empty($_POST['forum']))
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		if (isset($_POST['is_link']))
		{
			$link_check = '1';
		}
			
		else
		{
			$link_check = '0';
		}
					
		mysql_query("UPDATE `forums` SET `name` = '" . addslashes($_POST['forum']) . "', `description` = '" . addslashes($_POST['description']) . "', `is_link` = '" . $link_check . "', `link` = '" . $_POST['link'] . "', `category` = '" .$_POST['cat']  . "' WHERE `id` = '" . $_GET['fid'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		$cpost=$_POST['cpost'];
		$cview=$_POST['cview'];
		$creply=$_POST['creply'];
		
		// Get all IDs and sort them in an array

		$query="SELECT id FROM `groups`";
		$group_ids=mysql_query($query) or die(admin_error($lang['title']['error'],mysql_error()));
		$index=0;
		while ($group=mysql_fetch_assoc($group_ids))
		{
			$ids[$index]=$group['id'];
			$index++;
		}
		
		// Update permissions
		
		for ($ind=0; $ind<$index; $ind++)
		{
			if ($cpost[$ids[$ind]]) 
			{
				$cp = '1'; 
			}
			
			else 
			{
				$cp = '0';
			} 
			
			if ($creply[$ids[$ind]]) 
			{
				$cr = '1'; 
			}
			
			else 
			{
				$cr = '0';
			} 
			
			if ($cview[$ids[$ind]]) 
			{
				$cv = '1'; 
			}
			
			else 
			{
				$cv = '0';
			}
			
			$query="UPDATE `permissions` SET `cpost` = '" . $cp . "', `cview` = '" . $cv . "', `creply` = '" . $cr . "' WHERE `forum` = '" . $_GET['fid'] . "' AND `group` = '" . $ids[$ind] . "'";
			mysql_query($query) or die(admin_error($lang['title']['error'],mysql_error()));
		}

		message($lang['title']['edited'], $lang['text']['forum_edited']);
	}
}

if (isset($_GET['action']) && $_GET['action'] == "deletecat")
{
	$count_forums = mysql_fetch_array(mysql_query("SELECT COUNT(*) AS `count` FROM `forums` WHERE `category` = '" . $_GET['cid'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
	
	if ($count_forums['count'] == '0')
	{
		mysql_query("DELETE FROM `forums` WHERE `id` = '" . $_GET['cid'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
	
		message($lang['title']['deleted'], $lang['text']['cat_deleted']);
	}
	
	else if ($count_forums['count'] > '0')
	{
		message($lang['title']['deleted_forums_first'], $lang['text']['deleted_forums_first']);
	}
}

if (isset($_GET['action']) && $_GET['action'] == "deletef")
{
	mysql_query("DELETE FROM `forums` WHERE `id` = '" . $_GET['fid'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
	
	message($lang['title']['deleted'], $lang['text']['forum_deleted']);
}

// Group Admin
if (isset($_GET['view']) && $_GET['view'] == "addgroups") 
{
	echo "
	<form method='post' action='admin.php?action=insertgroups'>
	" . $lang['text']['group_names'] . "<br />
	<textarea name='groups' cols='60' rows='5'></textarea><br />
	<input type='submit' value='" . $lang['submit']['add_groups'] . "'>
	</form>
	";
}

if (isset($_GET['view']) && $_GET['view'] == "editgroups") 
{
	if (!isset($_GET['g']))
	{
		$select_groups = mysql_query("SELECT * FROM `groups` WHERE `permanent` != '1'");
		while ($get_groups = mysql_fetch_array($select_groups))
		{
			$get_groups['name'] = stripslashes($get_groups['name']);
			echo "<a href='admin.php?view=editgroups&g=" . $get_groups['id'] . "'>" . $get_groups['name'] . "</a>";
		}
	}
	
	else
	{
		$group = mysql_fetch_array(mysql_query("SELECT * FROM `groups` WHERE `id` = '" . $_GET['g'] . "'"));
		$group['name'] = stripslashes($group['name']);
		
		echo "
		<form method='post' action='admin.php?action=editgroup&g=" . $group['id'] . "'>
		" . $lang['mix']['edit_group'] . "<br />
		<input type='text' name='group' value='" . $group['name'] . "'><br />
		<input type='submit' value='" . $lang['mix']['edit_group'] . "'>
		</form>
		";
	}
}

if (isset($_GET['view']) && $_GET['view'] == "deletegroup") 
{
	echo "<strong>" . $lang['text']['delete_group'] . "</strong><br /><br />";
	$select_groups = mysql_query("SELECT * FROM `groups` WHERE `permanent` != '1'") or die(admin_error($lang['title']['error'],mysql_error()));
	while ($get_groups = mysql_fetch_array($select_groups))
	{
		echo "<a href='admin.php?action=deletegroup&g=" . $get_groups['id'] . "'>" . $get_groups['name'] . "</a><br />";
	}
}

if ($_GET['action'] == "insertgroups") 
{
	if (empty($_POST['groups'])) 
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		// get each group on each new line		
		$exploded_group = explode("\n", $_POST['groups']);
		
		// for each group insert it into the database
		foreach ($exploded_group as $group)
		{
			$group = trim($group);
						
			mysql_query("INSERT INTO `groups` SET `name` = '" . addslashes($group) . "', `permanent` = '0'") or die(admin_error($lang['title']['error'],mysql_error()));
			
			message($lang['mix']['groups'], $lang['text']['group_added']);
		}
		
		// insert this new groups info into the permissions table for each forum
		$forum_ids=mysql_query("SELECT id FROM `forums` WHERE `is_cat` != '1'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		// set the index to start from
		$index=0;
		
		// loop the forum id's
		while ($forum=mysql_fetch_assoc($forum_ids))
		{
			$ids[$index]=$forum['id'];
			$index++;
		}
		
		// get group id
		$group = mysql_fetch_array(mysql_query("SELECT `id` FROM `groups` ORDER BY `id` DESC LIMIT 1")) or die(admin_error($lang['title']['error'],mysql_error()));
		
		for ($ind=0; $ind<$index; $ind++)
		{
			mysql_query("INSERT INTO `permissions` SET `forum` = '" . $ids[$ind] . "', `group` = '" . $group['id'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		}
	}
}

if (isset($_GET['action']) && $_GET['action'] == "editgroup")
{
	if (!isset($_POST['group']))
	{
		message($lang['title']['missing'], $lang['text']['missing']);
	}
	
	else
	{
		mysql_query("UPDATE `groups` SET `name` = '" . addslashes($_POST['group']) . "' WHERE `id` = '" . $_GET['g'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
	
		message($lang['mix']['groups'], $lang['text']['group_edited']);
	}
}

if (isset($_GET['action']) && $_GET['action'] == "deletegroup")
{
	mysql_query("DELETE FROM `groups` WHERE `id` = '" . $_GET['g'] . "'");
	
	message($lang['mix']['groups'], $lang['text']['group_deleted']);
}


// Styles Admin
if (isset($_GET['view']) && $_GET['view'] == "styles" && !isset($_POST['stylename']))
{
	$get = mysql_query("SELECT * FROM `config` WHERE `name` = 'template'") or die(admin_error($lang['title']['error'],mysql_error()));
	$display = mysql_fetch_array($get);
	echo 
'<strong>' . $lang["text"]["template_change"] . '</strong><br /><br />' .
$lang["text"]["current_template"] . ': ' . $display["value"] . '<br />
<br />Set New Style: <br />
<form action="admin.php?view=styles" method="post">
<input type="text" name="name" size="40" maxlength="256"><br />
<input type="submit" value="' . $lang["submit"]["update"] . '"></form>';
}

if (isset($_GET['view']) && $_GET['view'] == "styles" && isset($_POST['stylename']))
{
	// check to see if template/style chosen is a directory
	if (is_dir('./templates/'.$_POST['name'].'/'))
	{
		mysql_query("UPDATE `config` SET value = '" . $_POST['stylename'] . "' WHERE `id` = '4'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		echo $lang['text']['template_updated'];
	}
	
	else
	{
		admin_error($lang['title']['error'],$lang['text']['invalid_template']);
	}
} 

// Member Administration
// Lock someone
if (isset($_GET['view']) && $_GET['view'] == "lock")
{
	echo "
<form method='post' action='admin.php?view=lock&step=lock'>
" . $lang['text']['username'] . "<br />
<a href='admin.php?view=members'>" . $lang['text']['check_user_list'] . "</a><br />
<input type='text' name='locker'><br />
<input type='submit' value='" . $lang['mix']['lock_member'] . "'>
</form>";
	
	if ($_GET['step'] == "lock") 
	{
		if (empty($_POST['locker']))
		{
			message($lang['title']['missing'],$lang['text']['missing']);
		}
		
		else if ($_POST['locker2'] != "1") 
		{
			mysql_query("UPDATE `members` SET `locked` = '1' WHERE `user` = '" . $_POST['locker'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
			
			message($lang['title']['locked'],$lang['text']['you_locked'] . $_POST['locker']);
		}
	}
}

// Unlock someone
if ($_GET['view'] == "unlock") 
{
	echo "
	<form method='post' action='admin.php?view=unlock&step=unlock'> 
	<strong>" . $lang['text']['username'] . "</strong><br />
	<a href='admin.php?view=members'>" . $lang['text']['check_user_list'] . "</a><br />
	<input type='text' name='unlocker'><br />
	<input type='submit' value='" . $lang['mix']['unlock_member'] . "'>
	</form>
	";
	
	if ($_GET['step'] == "unlock") 
	{
		if (empty($_POST['unlocker']))
		{
			message($lang['title']['missing'],$lang['text']['missing']);
		}
		
		else if ($_POST['locker'] != "1") 
		{
			mysql_query("UPDATE `members` SET `locked` = '0' WHERE `id` = '" . $_POST['locker'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
			
			message($lang['title']['unlocked'], $lang['text']['you_unlocked'] . $_POST['locker']);
		}
	}
}

// Delete someone
if ($_GET['view'] == "del") 
{
	echo "
	<form method='post' action='admin.php?view=del&step=del'>
	<strong>" . $lang['text']['username'] . "</strong><br />
	<a href='admin.php?view=members'>" . $lang['text']['check_user_list'] . "</a><br />
	<input type='text' name='member'><br />
	<input type='submit' value='" . $lang['mix']['delete'] . "'>
	</form>";
	
	if ($_GET['step'] == "del") 
	{
		$info = mysql_fetch_array(mysql_query("SELECT * FROM `members` WHERE `user` = '" . $_POST['member'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
		if ($info['id'] != '1') 
		{
			$count = mysql_fetch_array(mysql_query("SELECT COUNT(id) AS `count` FROM `members` WHERE `user` = '" . $_POST['member'] . "'")) or die(admin_error($lang['title']['error'],mysql_error()));
			if ($count['count'] > '1')
			{
				message($lang['title']['error'],$lang['text']['more_than_1']);
			}
			
			else
			{
				mysql_query("DELETE FROM `members` WHERE `user` = '" . $_POST['member'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
				
				message($lang['title']['deleted'],$lang['text']['you_deleted'] . $_POST['member']);
			}
		} 
		
		else 
		{
			message($lang['title']['error'],$lang['text']['owner_delete']);
		}
	}
}

// Change users group
if ($_GET['view'] == "group") 
{
	echo "
	<form method='post' action='admin.php?view=group&step=change'>
	<strong>" . $lang['text']['username'] . "</strong><br />
	<input type='text' name='user'> <br />
	<a href='admin.php?view=members'>" . $lang['text']['check_user_list'] . "</a><br />
	<strong>" . $lang['mix']['groups'] . "</strong><br />
	<select name='group'>
	";
	
	$select_groups = mysql_query("SELECT * FROM `groups` WHERE `id` != '1'") or die(admin_error($lang['title']['error'],mysql_error()));
	while ($get_groups = mysql_fetch_array($select_groups))
	{
		echo "<option value='" . $get_groups['id'] . "'>" . $get_groups['name'] . "</option>";
	}
	
	echo "
	</select><br />
	<input type='submit' value='" . $lang['submit']['change_group'] . "'>
	</form>
	";
	
	if ($_GET['step'] == "change") 
	{
		mysql_query("UPDATE `members` SET `group` = '" . $_POST['group'] . "' WHERE `user` = '" . $_POST['user'] . "'") or die(admin_error($lang['title']['error'],mysql_error()));
		
		message($lang['title']['edited'],$lang['text']['group_changed']);
	}
}

// View usernames & ID
if ($_GET['view'] == "members") 
{
	$tsel = mysql_query("SELECT * FROM `members` WHERE `id` != '-1' ORDER BY `id`") or die(admin_error($lang['title']['error'],mysql_error()));
	while ($top = mysql_fetch_array($tsel)) 
	{
		echo $top['user'] . "(" . $top['id'] . ")" . " " . $top['email'] . " " . $top['ip'] . "<br /><br />";
	}
}

echo "
</td>
</tr>
</table>
</div>
<br />
";

include('includes/footer.php');

// End timer
$end_time = explode(' ', microtime());
$total_time = round($end_time[1] + $end_time[0] - $start_time[1] - $start_time[0], 3);

echo "<div align='center'>" . $total_time . "</div>";
?>
</body>
</html>