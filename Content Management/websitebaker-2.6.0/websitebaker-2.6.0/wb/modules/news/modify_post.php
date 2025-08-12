<?php

// $Id: modify_post.php 239 2005-11-22 11:50:41Z stefan $

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

// Get id
if(!isset($_GET['post_id']) OR !is_numeric($_GET['post_id'])) {
	header("Location: ".ADMIN_URL."/pages/index.php");
} else {
	$post_id = $_GET['post_id'];
}

// Include WB admin wrapper script
require(WB_PATH.'/modules/admin.php');

// Get header and footer
$query_content = $database->query("SELECT * FROM ".TABLE_PREFIX."mod_news_posts WHERE post_id = '$post_id'");
$fetch_content = $query_content->fetchRow();

if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
	function show_wysiwyg_editor($name,$id,$content,$width,$height) {
		echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
	}
} else {
	$id_list=array("short","long");
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
}

?>
<form name="modify" action="<?php echo WB_URL; ?>/modules/news/save_post.php" method="post" style="margin: 0;">

<input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
<input type="hidden" name="page_id" value="<?php echo $page_id; ?>">
<input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
<input type="hidden" name="link" value="<?php echo $fetch_content['link']; ?>">

<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
	<td width="80"><?php echo $TEXT['TITLE']; ?>:</td>
	<td width="100%">
		<input type="text" name="title" value="<?php echo (htmlspecialchars($fetch_content['title'])); ?>" style="width: 100%;" maxlength="255" />
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['GROUP']; ?>:</td>
	<td>
		<select name="group" style="width: 100%;">
			<option value="0"><?php echo $TEXT['NONE']; ?></option>
			<?php
			$query = $database->query("SELECT group_id,title FROM ".TABLE_PREFIX."mod_news_groups WHERE section_id = '$section_id' ORDER BY position ASC");
			if($query->numRows() > 0) {
				// Loop through groups
				while($group = $query->fetchRow()) {
					?>
					<option value="<?php echo $group['group_id']; ?>"<?php if($fetch_content['group_id'] == $group['group_id']) { echo ' selected'; } ?>><?php echo $group['title']; ?></option>
					<?php
				}
			}
			?>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['COMMENTING']; ?>:</td>
	<td>
		<select name="commenting" style="width: 100%;">
			<option value="none"><?php echo $TEXT['DISABLED']; ?></option>
			<option value="public" <?php if($fetch_content['commenting'] == 'public') { echo 'selected'; } ?>><?php echo $TEXT['PUBLIC']; ?></option>
			<option value="private" <?php if($fetch_content['commenting'] == 'private') { echo 'selected'; } ?>><?php echo $TEXT['PRIVATE']; ?></option>
		</select>
	</td>
</tr>
<tr>
	<td><?php echo $TEXT['ACTIVE']; ?>:</td>
	<td>
		<input type="radio" name="active" id="active_true" value="1" <?php if($fetch_content['active'] == 1) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('active_true').checked = true;">
		<?php echo $TEXT['YES']; ?>
		</a>
		&nbsp;
		<input type="radio" name="active" id="active_false" value="0" <?php if($fetch_content['active'] == 0) { echo ' checked'; } ?> />
		<a href="#" onclick="javascript: document.getElementById('active_false').checked = true;">
		<?php echo $TEXT['NO']; ?>
		</a>
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $TEXT['SHORT']; ?>:</td>
	<td>
	<?php
	show_wysiwyg_editor("short","short",$fetch_content['content_short'],"100%","135px");
	?>
	</td>
</tr>
<tr>
	<td valign="top"><?php echo $TEXT['LONG']; ?>:</td>
	<td>
	<?php
	show_wysiwyg_editor("long","long",$fetch_content['content_long'],"100%","300px");
	?>
	</td>
</tr>
</table>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td width="90">
		&nbsp;
	</td>
	<td align="left">
		<input name="save" type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 200px; margin-top: 5px;"></form>
	</td>
	<td align="right">
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = '<?php echo ADMIN_URL; ?>/pages/modify.php?page_id=<?php echo $page_id; ?>';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

<br />

<h2><?php echo $TEXT['MODIFY'].'/'.$TEXT['DELETE'].' '.$TEXT['COMMENT']; ?></h2>

<?php

// Loop through existing posts
$query_comments = $database->query("SELECT * FROM `".TABLE_PREFIX."mod_news_comments` WHERE section_id = '$section_id' AND post_id = '$post_id' ORDER BY commented_when DESC");
if($query_comments->numRows() > 0) {
	$row = 'a';
	?>
	<table cellpadding="2" cellspacing="0" border="0" width="100%">
	<?php
	while($comment = $query_comments->fetchRow()) {
		?>
		<tr class="row_<?php echo $row; ?>" height="20">
			<td width="20" style="padding-left: 5px;">
				<a href="<?php echo WB_URL; ?>/modules/news/modify_comment.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&comment_id=<?php echo $comment['comment_id']; ?>" title="<?php echo $TEXT['MODIFY']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/modify_16.png" border="0" alt="^" />
				</a>
			</td>	
			<td>
				<a href="<?php echo WB_URL; ?>/modules/news/modify_comment.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&comment_id=<?php echo $comment['comment_id']; ?>">
					<?php echo $comment['title']; ?>
				</a>
			</td>
			<td width="20">
				<a href="javascript: confirm_link('<?php echo $TEXT['ARE_YOU_SURE']; ?>', '<?php echo WB_URL; ?>/modules/news/delete_comment.php?page_id=<?php echo $page_id; ?>&section_id=<?php echo $section_id; ?>&post_id=<?php echo $post_id; ?>&comment_id=<?php echo $comment['comment_id']; ?>');" title="<?php echo $TEXT['DELETE']; ?>">
					<img src="<?php echo ADMIN_URL; ?>/images/delete_16.png" border="0" alt="X" />
				</a>
			</td>
		</tr>
		<?php
		// Alternate row color
		if($row == 'a') {
			$row = 'b';
		} else {
			$row = 'a';
		}
	}
	?>
	</table>
	<?php
} else {
	echo $TEXT['NONE_FOUND'];
}

?>



<?php

// Print admin footer
$admin->print_footer();

?>