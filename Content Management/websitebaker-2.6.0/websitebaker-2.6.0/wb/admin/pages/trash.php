<?php

// $Id: trash.php 116 2005-09-16 21:20:22Z stefan $

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

require('../../config.php');
require_once(WB_PATH.'/framework/class.admin.php');
$admin = new admin('Pages', 'pages');

?>
<script type="text/javascript" language="javascript">
function toggle_viewers() {
	if(document.add.visibility.value == 'private') {
		document.getElementById('private_viewers').style.display = 'block';
		document.getElementById('registered_viewers').style.display = 'none';
	} else if(document.add.visibility.value == 'registered') {
		document.getElementById('private_viewers').style.display = 'none';
		document.getElementById('registered_viewers').style.display = 'block';
	} else {
		document.getElementById('private_viewers').style.display = 'none';
		document.getElementById('registered_viewers').style.display = 'none';
	}
}
function toggle_visibility(id){
	if(document.getElementById(id).style.display == "block") {
		document.getElementById(id).style.display = "none";
	} else {
		document.getElementById(id).style.display = "block";
	}
}
var plus = new Image;
plus.src = "<?php echo ADMIN_URL; ?>/images/plus_16.png";
var minus = new Image;
minus.src = "<?php echo ADMIN_URL; ?>/images/minus_16.png";
function toggle_plus_minus(id) {
	var img_src = document.images['plus_minus_' + id].src;
	if(img_src == plus.src) {
		document.images['plus_minus_' + id].src = minus.src;
	} else {
		document.images['plus_minus_' + id].src = plus.src;
	}
}
</script>

<style type="text/css">
.pages_list img {
	display: block;
}
ul, li {
	list-style: none;
	margin: 0;
	padding: 0;
}
.page_list {
	display: none;
}
</style>

<noscript>
	<style type="text/css">
	.page_list {
		display: block;
	}
	</style>
</noscript>
<?php

function make_list($parent, $editable_pages) {
	// Get objects and vars from outside this function
	global $admin, $template, $database, $TEXT, $MESSAGE;
	?>
	<ul id="p<?php echo $parent; ?>" <?php if($parent != 0) { echo 'class="page_list"'; } ?>>
	<?php	
	// Get page list from database
	$database = new database();
	$query = "SELECT * FROM ".TABLE_PREFIX."pages WHERE parent = '$parent' AND visibility = 'deleted' ORDER BY position ASC";
	$get_pages = $database->query($query);
	
	// Insert values into main page list
	if($get_pages->numRows() > 0)	{
		while($page = $get_pages->fetchRow()) {
			// Get user perms
			$admin_groups = explode(',', str_replace('_', '', $page['admin_groups']));
			$admin_users = explode(',', str_replace('_', '', $page['admin_users']));
			if(is_numeric(array_search($admin->get_group_id(), $admin_groups)) OR is_numeric(array_search($admin->get_user_id(), $admin_users))) {
				if($page['visibility'] == 'deleted') {
					$can_modify = true;
					$editable_pages = $editable_pages+1;
				} else {
					$can_modify = false;
				}
			} else {
				$can_modify = false;
			}
						
			// Work out if we should show a plus or not
			$get_page_subs = $database->query("SELECT page_id,admin_groups,admin_users FROM ".TABLE_PREFIX."pages WHERE parent = '".$page['page_id']."'");
			if($get_page_subs->numRows() > 0) {
				$display_plus = true;
			} else {
				$display_plus = false;
			}
			
			// Work out how many pages there are for this parent
			$num_pages = $get_pages->numRows();
			?>
			
			<li id="p<?php echo $page['parent']; ?>" style="padding: 2px 0px 2px 0px;">
			<table width="720" cellpadding="1" cellspacing="0" border="0" style="background-color: #F0F0F0;">
			<tr>
				<td width="20" style="padding-left: <?php echo $page['level']*20; ?>px;">
					<?php
					if($display_plus == true) {
					?>
					<a href="javascript: toggle_visibility('p<?php echo $page['page_id']; ?>');" title="<?php echo $TEXT['EXPAND'].'/'.$TEXT['COLLAPSE']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/plus_16.png" onclick="toggle_plus_minus('<?php echo $page['page_id']; ?>');" name="plus_minus_<?php echo $page['page_id']; ?>" border="0" alt="+" />
					</a>
					<?php
					}
					?>
				</td>
				<?php if($admin->get_permission('pages_modify') == true AND $can_modify == true AND $page['visibility'] != 'heading') { ?>
				<td>
					<a href="<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>"><?php echo ($page['page_title']); ?></a>
				</td>
				<?php } else { ?>
				<td>
					<?php
					if($page['visibility'] != 'heading') {
						echo ($page['page_title']);
					} else {
						echo '<b>'.($page['page_title']).'</b>';
					}
					?>
				</td>
				<?php } ?>
				<td align="left" width="232">
					<font color="#999999"><?php echo $page['menu_title']; ?></font>
				</td>
				<td align="right" valign="middle" width="30" style="padding-right: 20px;">
				<?php if($page['visibility'] == 'public') { ?>
					<img src="<?php echo ADMIN_URL; ?>/images/visible_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['PUBLIC']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'private') { ?>
					<img src="<?php echo ADMIN_URL; ?>/images/private_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['PRIVATE']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'registered') { ?>
					<img src="<?php echo ADMIN_URL; ?>/images/keys_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['REGISTERED']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'none') { ?>
					<img src="<?php echo ADMIN_URL; ?>/images/hidden_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['NONE']; ?>" border="0" />
				<?php } elseif($page['visibility'] == 'deleted') { ?>
					<img src="<?php echo ADMIN_URL; ?>/images/deleted_16.png" alt="<?php echo $TEXT['VISIBILITY']; ?>: <?php echo $TEXT['DELETED']; ?>" border="0" />
				<?php } ?>
				</td>
				<td width="20">
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/settings.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['SETTINGS']; ?>">
							<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="<?php echo $TEXT['SETTINGS']; ?>" />
						</a>
						<?php } ?>
					<?php } else { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/restore.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['RESTORE']; ?>">
							<img src="<?php echo ADMIN_URL; ?>/images/restore_16.png" border="0" alt="<?php echo $TEXT['RESTORE']; ?>" />
						</a>
					<?php } ?>
				</td>
				<td width="20">
				<?php if($page['position'] != 1) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_up.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_UP']; ?>">
							<img src="<?php echo ADMIN_URL; ?>/images/up_16.png" border="0" alt="^" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td width="20">
				<?php if($page['position'] != $num_pages) { ?>
					<?php if($page['visibility'] != 'deleted') { ?>
						<?php if($admin->get_permission('pages_settings') == true AND $can_modify == true) { ?>
						<a href="<?php echo ADMIN_URL; ?>/pages/move_down.php?page_id=<?php echo $page['page_id']; ?>" title="<?php echo $TEXT['MOVE_DOWN']; ?>">
							<img src="<?php echo ADMIN_URL; ?>/images/down_16.png" border="0" alt="v" />
						</a>
						<?php } ?>
					<?php } ?>
				<?php } ?>
				</td>
				<td width="20">
					<?php if($admin->get_permission('pages_delete') == true AND $can_modify == true) { ?>
					<a href="javascript: confirm_link('<?php echo $MESSAGE['PAGES']['DELETE_CONFIRM']; ?>?', '<?php echo ADMIN_URL; ?>/pages/delete.php?page_id=<?php echo $page['page_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
						<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
					</a>
					<?php } ?>
				</td>
			</tr>
			</table>
			</li>
							
			<?php
			// Get subs
			make_list($page['page_id'], $editable_pages);
		}

	}
	?>
	</ul>
	<?php
	return $editable_pages;
}

// Generate pages list
if($admin->get_permission('pages_view') == true) {
	?>
	<table cellpadding="0" cellspacing="0" width="100%" border="0">
	<tr>
		<td>
			<h2><?php echo $HEADING['DELETED_PAGES']; ?></h2>
		</td>
		<td align="right">
				<a href="<?php echo ADMIN_URL; ?>/pages/empty_trash.php">
				<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" alt="<?php echo $TEXT['PAGE_TRASH']; ?>" border="0" />
				<?php echo $TEXT['EMPTY_TRASH']; ?></a>
		</td>
	</tr>
	</table>
	<div class="pages_list">
	<table cellpadding="1" cellspacing="0" width="720" border="0">
	<tr>
		<td width="20">
			&nbsp;
		</td>
		<td>
			<?php echo $TEXT['PAGE_TITLE']; ?>:
		</td>
		<td width="198" align="left">
			<?php echo $TEXT['MENU_TITLE']; ?>:
		</td>
		<td width="80" align="center">
			<?php echo $TEXT['VISIBILITY']; ?>:
		</td>
		<td width="90" align="center">
			<?php echo $TEXT['ACTIONS']; ?>:
		</td>		
	</tr>
	</table>
	<?php
	$editable_pages = make_list(0, 0);
	?>
	</div>
	<div class="empty_list">
		<?php echo $TEXT['NONE_FOUND']; ?>
	</div>
	<?php
} else {
	$editable_pages = 0;
}

// Figure out if the no pages found message should be shown or not
if($editable_pages == 0) {
	?>
	<style type="text/css">
	.pages_list {
		display: none;
	}
	</style>
	<?php
} else {
	?>
	<style type="text/css">
	.empty_list {
		display: none;
	}
	</style>
	<?php
}

?>
<br />< <a href="<?php echo ADMIN_URL; ?>/pages/index.php"><?php echo $MESSAGE['PAGES']['RETURN_TO_PAGES']; ?></a>
<?php

// Print admin 
$admin->print_footer();

?>