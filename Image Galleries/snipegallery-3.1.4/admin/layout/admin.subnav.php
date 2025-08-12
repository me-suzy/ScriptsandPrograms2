<?php
/**
* Admin Sub-nav 
*
* This file controls the sub-section navigation for each section.
* That is, it decides what to print out if you are in the gallery 
* section versus the settings section versus the FAQ section, etc.
* There is nothing you should need to change here.
*     
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*/

if (!empty($GALLERY_SECTION)) {
	
	if ($GALLERY_SECTION=="gallery") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n<td>";
		if (!empty($_REQUEST['gallery_id'])) {
			$sql = "select cat_parent from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
			$get_thiscat_parent = mysql_query($sql);
			list($thiscat_parent) = mysql_fetch_row($get_thiscat_parent);

			echo "&#187; <a href=\"".$cfg_admin_url."/gallery/gallery.php?gallery_id=".$_REQUEST['gallery_id']."\" class=\"subnav\">".$LANG_SUBNAV_EDIT_GALLERY."</a>";

			if ($thiscat_parent!=0) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/view.php?gallery_id=".$_REQUEST['gallery_id']."\" class=\"subnav\">".$LANG_SUBNAV_VIEW_IMAGES."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <b><a href=\"".$cfg_admin_url."/gallery/image.php?gallery_id=".$_REQUEST['gallery_id']."&add=new\" class=\"subnav\">".$LANG_SUBNAV_ADD_IMAGES."</a></b> ";
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/delete.php?gallery_id=".$_REQUEST['gallery_id']."&del=y\" class=\"subnav\">".$LANG_SUBNAV_DEL_GALLERY."</a> ";
			}
		} else {
			echo "&#187; <a href=\"".$cfg_admin_url."/gallery/gallery.php\" class=\"subnav\">".$LANG_SUBNAV_NEW_GALLERY."</a> ";
		}		
		
		echo "</td>\n</tr>\n</table>";
	} elseif ($GALLERY_SECTION=="image") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n<td>";
		if (!empty($_REQUEST['image_id'])) {
			echo "&#187; <a href=\"".$cfg_admin_url."/gallery/image.php?image_id=".$_REQUEST['image_id']."&gallery_id=".$_REQUEST['gallery_id']."\" class=\"subnav\">".$LANG_SUBNAV_EDIT_IMG."</a>";
			if ($cfg_enable_croptool==1) {
				echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/crop.php?gallery_id=".$_REQUEST['gallery_id']."&image_id=".$_REQUEST['image_id']."\" class=\"subnav\">".$LANG_SUBNAV_THUMBTOOL."</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/crop.php?croptype=full&gallery_id=".$_REQUEST['gallery_id']."&image_id=".$_REQUEST['image_id']."\" class=\"subnav\">".$LANG_SUBNAV_CROPTOOL."</a> ";
			}
		} 
		if (!empty($_REQUEST['gallery_id'])) {
			echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/gallery.php?gallery_id=".$_REQUEST['gallery_id']."\" class=\"subnav\">".$LANG_SUBNAV_EDIT_GALLERY."</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/gallery/view.php?gallery_id=".$_REQUEST['gallery_id'];
			if (!empty($_REQUEST['page'])) {
				echo "&page=".$_REQUEST['page'];
			}
			
			echo "\" class=\"subnav\">".$LANG_SUBNAV_VIEW_IMAGES."</a> ";

		}
		
		echo "</td>\n</tr>\n</table>";
	} elseif ($GALLERY_SECTION=="frames") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n<td>";
		echo "&#187; <a href=\"".$cfg_admin_url."/frames/frame.php\" class=\"subnav\">".$LANG_SUBNAV_NEW_FRAMES."</a> ";
		echo "</td>\n</tr>\n</table>";

	} elseif ($GALLERY_SECTION=="faq") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n";
		echo "<td>&#187; <a href=\"http://www.snipegallery.com/forums/\" target=\"_new\" class=\"subnav\">Snipe Gallery Forums</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"http://www.snipegallery.com/\" target=\"_new\" class=\"subnav\">Snipe Gallery Website</a></td>\n";
		echo "</tr>\n</table>";

	} elseif ($GALLERY_SECTION=="import") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n<td>";
		echo "&#187; <a href=\"".$cfg_admin_url."/import/zip.php\" class=\"subnav\">Zip File Import</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"".$cfg_admin_url."/import/local.php\" class=\"subnav\">Local Import</a> ";
		echo "</td>\n</tr>\n</table>";

	} elseif ($GALLERY_SECTION=="settings") {
		echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"100%\" class=\"subnav\">\n<tr>\n";
		echo "<td>&#187; <a href=\"http://www.snipegallery.com/forums/\" target=\"_new\" class=\"subnav\">Snipe Gallery Forums</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"http://www.snipegallery.com/\" target=\"_new\" class=\"subnav\">Snipe Gallery Website</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"http://www.php.net/\" target=\"_new\" class=\"subnav\">PHP.Net</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#187; <a href=\"http://www.mysql.com/\" target=\"_new\" class=\"subnav\">MySQL.Com</a></td>\n";
		echo "</tr>\n</table>";
	} 

	
}

?>