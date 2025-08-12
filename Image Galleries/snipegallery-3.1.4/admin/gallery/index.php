<?php 
/**
* Admin Album/Gallery Display Listing
*
* This file displays all of the albums/galleries
* that the user has created
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
$GALLERY_SECTION = "gallery";
$PAGE_TITLE = "Galleries";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ("../layout/admin.header.php");  

if (!empty($_REQUEST['del_gallery_id'])) {
	$sql = "delete from snipe_gallery_cat where id='".$_REQUEST['del_gallery_id']."' OR cat_parent='".$_REQUEST['del_gallery_id']."'";
	if ($del_cat = mysql_query($sql)) {
		$del_message = "Album/galleries successfully deleted.";
	} else {
		$del_message = "Error deleting category.  ";		
		if ($cfg_debug_on==1) {
			$del_message .="<br><br>mySQL said: </b>";
			$del_message .= mysql_error();
			$del_message .="<b>SQL query:</b> $sql </p>";

		}
	}
}


	// get the category list
	$sql = "select id, name, created_on from snipe_gallery_cat where cat_parent='0'";
	if ($get_galleries = mysql_query($sql)) {
		$num_galleries = mysql_num_rows($get_galleries);
		if ($num_galleries > 0) {
			echo "<h3>".$LANG_ADMIN_CURRENT_ALBUM."</h3>\n\n<p>".$LANG_ADMIN_ALBUM_TXT."</p>";
			if (!empty($del_message)) {
				echo "<p class=\"errortxt\">".$del_message."</p>\n\n";
			}
			echo "<table cellspacing=\"0\" cellpadding=\"3\" width=\"90%\">";
			while (list($gallery_id, $gallery_name, $gallery_created_on) = mysql_fetch_row($get_galleries)) { 
				echo "\n\n<!-- Begin Album/Gallery block -->\n\n\n";
				echo "<tr>\n<td class=\"resultline\"><b>".$LANG_GEN_ALBUM.": </b></td><td class=\"resultline\"><b><a href=\"gallery.php?gallery_id=".$gallery_id."\" title=\"$gallery_name\" class=\"gallery\">";
				if (!empty($gallery_name)) {
					echo stripslashes($gallery_name);
				} else {
					echo "-- ".$LANG_GEN_UNTITLED." --";
				}
				
				echo "</a></b> - ".$LANG_GEN_CREATED_ON.": ".make_datetime_pretty($gallery_created_on)."</td></tr>";
				

				$sql = "select id, name from snipe_gallery_cat where cat_parent='".$gallery_id."'";
				if ($get_subgalleries = mysql_query($sql)) {
					$num_subgalleries = mysql_num_rows($get_subgalleries);
					$total_images_in_cat = 0;
					if ($num_subgalleries > 0) {
						$subgallery_count = 1;
						echo "<tr><td class=\"resultline-alt\">Galleries:</td><td class=\"resultline-alt\">";
						while (list($subgallery_id, $subgallery_name) = mysql_fetch_row($get_subgalleries)) {
							echo "<a href=\"view.php?gallery_id=".$subgallery_id."\">".stripslashes($subgallery_name);
							$sql = "select count(*) from snipe_gallery_data where cat_id='".$subgallery_id."'";

							if ($count_images = mysql_query($sql)) {
								list($images_in_cat) = mysql_fetch_row($count_images);
								echo " (".$images_in_cat.")";
								$total_images_in_cat = $total_images_in_cat + $images_in_cat;
							}
							echo "</a>";
							if ($subgallery_count < $num_subgalleries) {
								echo ", ";
							}
							$subgallery_count++;
						}
						echo "</td></tr>\n";
					} else {
						echo "<tr><td class=\"resultline-alt\">&nbsp;</td><td class=\"resultline-alt\">-- ".$LANG_GEN_NO_GALLERIES." --</td></tr>\n";
					}
				} else {
					echo "<tr><td class=\"resultline-light\">&nbsp;</td><td class=\"resultline-light\">-- ".$LANG_ERR_GALLERIES." --</td></tr>\n";
				}	
				echo "<tr><td class=\"resultline-light\"><img src=\"".$cfg_admin_url."/images/spacer.gif\" width=\"1\" height=\"5\" border=\"0\"></td>\n<td class=\"resultline-rlight\" align=\"right\">";
				
				echo "\n\n<!-- Album admin icons -->\n\n";
				echo "<table cellspacing=\"0\" cellpadding=\"1\"><tr>";
				echo "<td><a href=\"gallery.php?cat_parent=".$gallery_id."\"><img src=\"".$cfg_admin_url."/images/icons/icon_folder_new.gif\" width=\"16\" height=\"16\" border=\"0\" alt=\"".$LANG_ADMIN_ADD_GALLERY."\"></a></td>";
				echo "<td class=\"smadmin\"><a href=\"gallery.php?add_cat_parent=".$gallery_id."\" class=\"smadminlink\">".$LANG_ADMIN_ADD_GALLERY."</a></td>";
				echo "<td class=\"smadmin\"><img src=\"".$cfg_admin_url."/images/spacer.gif\" width=\"15\" height=\"1\" border=\"0\" alt=\"\"></td>";

				if ($num_subgalleries > 0) {
					echo "<td class=\"smadmin\"><img src=\"".$cfg_admin_url."/images/icons/trash-off.gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"".$LANG_ADMIN_DEL_GALLERY."\"></td>";
					echo "<td class=\"smadmin\">".$LANG_ADMIN_DEL_GALLERY."</td>";
				} else {
					echo "<td class=\"smadmin\"><a href=\"index.php?del_gallery_id=".$gallery_id."\"><img src=\"".$cfg_admin_url."/images/icons/trash.gif\" width=\"15\" height=\"15\" border=\"0\" alt=\"\"></a></td>";
					echo "<td class=\"smadmin\"><a href=\"index.php?del_gallery_id=".$gallery_id."\" class=\"smadminlink\">".$LANG_ADMIN_DEL_GALLERY."</a></td>";
				}
				echo "</tr></table>";			
				
				echo "\n\n<!-- Album admin icons -->\n\n";
				echo "</td></tr>\n";
				echo "<tr><td class=\"resultline-light\"><img src=\"".$cfg_admin_url."/images/spacer.gif\" width=\"1\" height=\"5\" border=\"0\"></td><td class=\"resultline-light\"><img src=\"".$cfg_admin_url."/images/spacer.gif\" width=\"1\" height=\"5\" border=\"0\"></td></tr>\n";
				
				
				echo "\n\n<!-- End Album/Gallery block -->\n\n\n";
				
			}
			echo "</table>";

		} else {
			echo "<h3>".$LANG_ADMIN_CREATE_ALBUM."</h3>\n<p>".$LANG_ADMIN_NO_ALBUMS."  </p>";
			$first_album = 1;
			$create_what = "Album";
			include ($cfg_admin_path."/lib/forms/cat_form.php");

		}

	} else {
		echo "<span class=\"errortxt\">".$LANG_ERR_DB_ERROR."</span>";
		if ($cfg_debug_on==1) {
			echo "<p><b>mySQL said: </b>";
			echo mysql_error();
			echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

		}

	}


include ("../layout/admin.footer.php"); ?>	
