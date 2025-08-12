<?php

/*

 Website Baker Project <http://www.websitebaker.org/>
 Copyright (C) 2004-2005, Ryan Djurovich

 Website Baker is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Website Baker is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Website Baker; if not, write to the Free Software
 Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

// Include config file
require('../../config.php');

// Make sure people are allowed to access this page
if(MANAGE_SECTIONS != 'enabled') {
	header('Location: '.ADMIN_URL.'/pages/index.php');
}

// Get page id
if(!isset($_GET['page_id']) OR !is_numeric($_GET['page_id'])) {
	header("Location: index.php");
} else {
	$page_id = $_GET['page_id'];
}

// Create new admin object
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages_modify');

// Check if we are supposed to add or delete a section
if(isset($_GET['section_id']) AND is_numeric($_GET['section_id'])) {
	// Get more information about this section
	$section_id = $_GET['section_id'];
	$query_section = $database->query("SELECT module FROM ".TABLE_PREFIX."sections WHERE section_id = '$section_id'");
	if($query_section->numRows() == 0) {
		$admin->print_error('Section not found');
	}
	$section = $query_section->fetchRow();
	// Include the modules delete file if it exists
	if(file_exists(WB_PATH.'/modules/'.$section['module'].'/delete.php')) {
		require(WB_PATH.'/modules/'.$section['module'].'/delete.php');
	}
	$database->query("DELETE FROM ".TABLE_PREFIX."sections WHERE section_id = '$section_id' LIMIT 1");
	if($database->is_error()) {
		$admin->print_error($database->get_error());
	} else {
		require(WB_PATH.'/framework/class.order.php');
		$order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
		$order->clean($page_id);
		$admin->print_success($TEXT['SUCCESS'], ADMIN_URL.'/pages/sections.php?page_id='.$page_id);
		$admin->print_footer();
		exit();
	}
} elseif(isset($_POST['module']) AND $_POST['module'] != '') {
	// Get section info
	$module = $_POST['module'];
	// Include the ordering class
	require(WB_PATH.'/framework/class.order.php');
	// Get new order
	$order = new order(TABLE_PREFIX.'sections', 'position', 'section_id', 'page_id');
	$position = $order->get_new($page_id);	
	// Insert module into DB
	$database->query("INSERT INTO ".TABLE_PREFIX."sections (page_id,module,position,block) VALUES ('$page_id','$module','$position','1')");
	// Get the section id
	$section_id = $database->get_one("SELECT LAST_INSERT_ID()");	
	// Include the selected modules add file if it exists
	if(file_exists(WB_PATH.'/modules/'.$module.'/add.php')) {
		require(WB_PATH.'/modules/'.$module.'/add.php');
	}	
}

// Get perms
$database = new database();
$results = $database->query("SELECT admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'");
$results_array = $results->fetchRow();
$old_admin_groups = explode(',', $results_array['admin_groups']);
$old_admin_users = explode(',', $results_array['admin_users']);
if(!is_numeric(array_search($admin->get_group_id(), $old_admin_groups)) AND !is_numeric(array_search($admin->get_user_id(), $old_admin_users))) {
	$admin->print_error($MESSAGE['PAGES']['INSUFFICIENT_PERMISSIONS']);
}

// Get page details
$database = new database();
$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE page_id = '$page_id'";
$results = $database->query($query);
if($database->is_error()) {
	$admin->print_header();
	$admin->print_error($database->get_error());
}
if($results->numRows() == 0) {
	$admin->print_header();
	$admin->print_error($MESSAGE['PAGES']['NOT_FOUND']);
}
$results_array = $results->fetchRow();

// Set module permissions
$module_permissions = $_SESSION['MODULE_PERMISSIONS'];

// Unset block var
unset($block);
// Include template info file (if it exists)
if($results_array['template'] != '') {
	$template_location = WB_PATH.'/templates/'.$results_array['template'].'/info.php';
} else {
	$template_location = WB_PATH.'/templates/'.DEFAULT_TEMPLATE.'/info.php';
}
if(file_exists($template_location)) {
	require($template_location);
}
// Check if $menu is set
if(!isset($block[1]) OR $block[1] == '') {
	// Make our own menu list
	$block[1] = $TEXT['MAIN'];
}

?>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%" height="50" style="margin-bottom: 10px;">
<tr style="background-color: #F0F0F0;">
	<td valign="middle" align="left">
		<h2><?php echo $HEADING['MANAGE_SECTIONS']; ?></h2>
	</td>
	<td align="right">
		<?php echo $TEXT['CURRENT_PAGE']; ?>: 
		<b><?php echo ($results_array['page_title']); ?></b>
		-
		<a href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>"><?php echo $HEADING['MODIFY_PAGE']; ?></a>
		-
		<a href="<?php echo ADMIN_URL; ?>/pages/settings.php?page_id=<?php echo $page_id; ?>"><?php echo $TEXT['CHANGE_SETTINGS']; ?></a>
	</td>
</tr>
</table>

<?php
$query_sections = $database->query("SELECT section_id,module,position,block FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' ORDER BY position ASC");
if($query_sections->numRows() > 0) {
?>
<form name="section_properties" action="<?php echo ADMIN_URL; ?>/pages/sections_save.php?page_id=<?php echo $page_id; ?>" method="post">

<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td><?php echo $TEXT['TYPE']; ?>:</td>
	<?php if(SECTION_BLOCKS) { ?>
	<td style="display: {DISPLAY_BLOCK}"><?php echo $TEXT['BLOCK']; ?>:</td>
	<?php } ?>
	<td colspan="3" width="60"><?php echo $TEXT['ACTIONS']; ?>:</td>
</tr>
<?php
	$num_sections = $query_sections->numRows();
	while($section = $query_sections->fetchRow()) {
		// Get the modules real name
		$module_path = WB_PATH.'/modules/'.$section['module'].'/info.php';
		if(file_exists($module_path)) {
			require($module_path);
			if(!isset($module_function)) { $module_function = 'unknown'; }
			if(!is_numeric(array_search($section['module'], $module_permissions)) AND $module_function == 'page') {
			?>
			<tr>
				<td style="width: 250px;"><a href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>#<?php echo $section['section_id']; ?>"><?php echo $module_name; ?></a></td>
				<?php if(SECTION_BLOCKS) { ?>
				<td>
					<select name="block<?php echo $section['section_id']; ?>" style="width: 150px;">
						<?php
						foreach($block AS $number => $name) {
							?>
							<option value="<?php echo $number; ?>"<?php if($number == $section['block']) { echo ' selected'; } ?>><?php echo $name; ?></option>
							<?php
						}
						?>
					</select>
				</td>
				<?php } ?>
				<td width="20">
					<?php if($section['position'] != 1) { ?>
					<a href="<?php echo ADMIN_URL; ?>/pages/move_up.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section['section_id']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" alt="^" border="0" />
					</a>
					<?php } ?>
				</td>
				<td width="20">
					<?php if($section['position'] != $num_sections) { ?>
					<a href="<?php echo ADMIN_URL; ?>/pages/move_down.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section['section_id']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" alt="v" border="0" />
					</a>
					<?php } ?>
				</td>
				<td width="20">
					<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo ADMIN_URL; ?>/pages/sections.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section['section_id']; ?>');">
						<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" alt="Del" border="0" />
					</a>
				</td>
			</tr>
			<?php
			}
			if(isset($module_function)) { unset($module_function); } // Unset module type
		}
	}
	?>
	<tr>
		<td>&nbsp;</td>
		<?php if(SECTION_BLOCKS) { ?>
		<td><input type="submit" name="save" value="<?php echo $TEXT['SAVE']; ?>" style="width: 150px;" /></td>
		<?php } ?>
		<td colspan="3" width="60">&nbsp;</td>
	</tr>
	</table>

</form>

<?php
}

// Work-out if we should show the "Add Section" form
$query_sections = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'menu_link'");
if($query_sections->numRows() == 0) {
	?>
	<h2><?php echo $TEXT['ADD_SECTION']; ?></h2>
	
	<form name="add" action="<?php echo ADMIN_URL; ?>/pages/sections.php?page_id=<?php echo $page_id; ?>" method="post">
	
	<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
	<tr>
		<td>
			<select name="module" style="width: 100%;">
			<?php
			// Insert module list
			$result = $database->query("SELECT * FROM ".TABLE_PREFIX."addons WHERE type = 'module' AND function = 'page' AND directory != 'menu_link'");
			if($result->numRows() > 0) {
				while($module = $result->fetchRow()) {
					// Check if user is allowed to use this module
					if(!is_numeric(array_search($module['directory'], $module_permissions))) {
						?>
						<option value="<?php echo $module['directory']; ?>"<?php if($module['directory'] == 'wysiwyg') { echo 'selected'; } ?>><?php echo $module['name']; ?></option>
						<?php
					}
				}
			}
			?>
			</select>
		</td>
		<td width="100">
			<input type="submit" name="submit" value="<?php echo $TEXT['ADD']; ?>" style="width: 100px" />
		</td>
	</tr>
	</table>
	
	</form>
	<?php
}

// Print admin footer
$admin->print_footer();

?>