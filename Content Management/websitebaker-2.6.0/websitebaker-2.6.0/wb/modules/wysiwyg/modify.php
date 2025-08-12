<?php

// $Id: modify.php 239 2005-11-22 11:50:41Z stefan $

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

if(!defined('WB_PATH')) exit('Direct access to this file is not allowed');

// Get page content
$query = "SELECT content FROM ".TABLE_PREFIX."mod_wysiwyg WHERE section_id = '$section_id'";
$get_content = $database->query($query);
$content = $get_content->fetchRow();
$content = (htmlspecialchars($content['content']));

if(!isset($wysiwyg_editor_loaded)) {
	$wysiwyg_editor_loaded=true;

	if (!defined('WYSIWYG_EDITOR') OR WYSIWYG_EDITOR=="none" OR !file_exists(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php')) {
		function show_wysiwyg_editor($name,$id,$content,$width,$height) {
			echo '<textarea name="'.$name.'" id="'.$id.'" style="width: '.$width.'; height: '.$height.';">'.$content.'</textarea>';
		}
	} else {
		$id_list=array();
		$query_wysiwyg = $database->query("SELECT section_id FROM ".TABLE_PREFIX."sections WHERE page_id = '$page_id' AND module = 'wysiwyg'");
		if($query_wysiwyg->numRows() > 0) {
			while($wysiwyg_section = $query_wysiwyg->fetchRow()) {
				$entry='content'.$wysiwyg_section['section_id'];
				array_push($id_list,$entry);
			}
			require(WB_PATH.'/modules/'.WYSIWYG_EDITOR.'/include.php');
		}
	}
}

?>

<form name="wysiwyg<?php echo $section_id; ?>" action="<?php echo WB_URL; ?>/modules/wysiwyg/save.php" method="post">

<input type="hidden" name="page_id" value="<?php echo $page_id; ?>" />
<input type="hidden" name="section_id" value="<?php echo $section_id; ?>" />

<?php
show_wysiwyg_editor('content'.$section_id,'content'.$section_id,$content,'725px','350px');
?>

<table cellpadding="0" cellspacing="0" border="0" width="100%" style="padding-bottom: 10px;">
<tr>
	<td align="left">
		<input type="submit" value="<?php echo $TEXT['SAVE']; ?>" style="width: 100px; margin-top: 5px;" />
	</td>
	<td align="right">
		</form>
		<input type="button" value="<?php echo $TEXT['CANCEL']; ?>" onclick="javascript: window.location = 'index.php';" style="width: 100px; margin-top: 5px;" />
	</td>
</tr>
</table>

</form>

<br />