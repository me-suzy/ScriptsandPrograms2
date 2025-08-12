<?php 
/**
* gallery_details.php
*
* This file displays the album/gallery details
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/

/**
*
* {@source }
*/

$GALLERY_SECTION = "gallery";
$PAGE_TITLE = "View Gallery";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");

include ("../layout/admin.header.php");

if ((empty($_REQUEST['page'])) || ($_REQUEST['page'] <= 0)){ 
	$page = 1; 
} else {
	$page = $_REQUEST['page'];
}

$limitvalue = $page*$cfg_per_page_limit-($cfg_per_page_limit);

// get the category details
if (!empty($_REQUEST['gallery_id'])) {
$sql = "select name, frame_style, watermark_txt, display_orderby, display_order  from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_frame_style, $this_watermark_txt, $this_display_orderby, $this_display_order) = mysql_fetch_row($get_catname);		
		}
	}


if (!empty($this_frame_style)) {
$sql = "select top_left_sm,  top_bg_sm,  top_right_sm,  left_bg_sm,  right_bg_sm,  bottom_left_sm,  bottom_bg_sm,  bottom_right_sm  from snipe_gallery_frames where frame_id='".$this_frame_style."'";
	if ($get_frames = mysql_query($sql)) {
		$valid_framestyle = mysql_num_rows($get_frames);
		if ($valid_framestyle > 0) {				
			list($frame_top_left_sm,  $frame_top_bg_sm,  $frame_top_right_sm,  $frame_left_bg_sm,  $frame_right_bg_sm,  $frame_bottom_left_sm,  $frame_bottom_bg_sm,  $frame_bottom_right_sm) = mysql_fetch_row($get_frames);		
		}
	}

}




echo "<h3>".$LANG_GEN_VIEW_IMAGES_IN_GALLERY.": ".stripslashes($this_catname)."</h3>";

/*
* If an image is to be deleted, get the filename of the fullsize and thumbnail
* images so that we can unlink them
*/
if ((!empty($_REQUEST['image_id'])) && ($_REQUEST['del']=="y")){
$sql = "select thumbname, filename from snipe_gallery_data where id='".$_REQUEST['image_id']."' AND cat_id='".$_REQUEST['gallery_id']."'";

	if ($get_del_images = mysql_query($sql)) {
		$valid_image_for_del = mysql_num_rows($get_del_images);
		if ($valid_image_for_del > 0) {				
			list($del_this_full, $del_this_thumb) = mysql_fetch_row($get_del_images);

			if (($cfg_use_cache==1) && (!empty($this_watermark_txt)) && (file_exists($cfg_cache_path)) && (is_writable($cfg_cache_path))) {
				if (file_exists($cfg_cache_path."/".$del_this_full)) {
					@unlink($cfg_cache_path."/".$del_this_full);				
				} 

			}

			if (!empty($del_this_full)) {
				if (file_exists($cfg_pics_path."/".$del_this_full)) {
					unlink($cfg_pics_path."/".$del_this_full);				
				} else {
					echo "<p class=\"errortxt\">".$LANG_ERR_FS_DEL_ERROR."</p>";
				}
			}

			if (!empty($del_this_thumb)) {
				if (file_exists($cfg_thumb_path."/".$del_this_thumb)) {
					unlink($cfg_thumb_path."/".$del_this_thumb);				
				} 
			}

			$sql = "delete from snipe_gallery_data where id='".$_REQUEST['image_id']."' AND cat_id='".$_REQUEST['gallery_id']."'";
			if (!$del_image = mysql_query($sql)) {
							
				echo "<p class=\"errortxt\">".$LANG_ERR_DEL_IMAGE_DB_ERROR."</p>";
				if ($cfg_debug_on==1) {
					echo "<p><b>mySQL said: </b>";
					echo mysql_error();
					echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";
				}
			}


		} 
	}

}



$sql = "select id, title, thumbname, added from snipe_gallery_data where cat_id='".$_REQUEST['gallery_id']."'";
		/*
		* user order by statement specified in database
		*/
		if (!empty($this_display_orderby)){
			$sql .= " order by ".$this_display_orderby." ";
		} else {
			$sql .= " order by id ";
		}


		if (!empty($this_display_order)) {
			$sql .= " ".$this_display_order." ";
		} else {
			if ($this_display_orderby!="rand()") {
				$sql .= " asc ";
			}
		}	

$sql .=" LIMIT $limitvalue, $cfg_per_page_limit";

$sqlcount = "select count(*) from snipe_gallery_data where cat_id='".$_REQUEST['gallery_id']."' ";
$print_query ="?gallery_id=".$_REQUEST['gallery_id']."&";

//echo $sql;

$sql_countresult = mysql_query($sqlcount);
list($totalrows) = mysql_fetch_row($sql_countresult);



		if ($get_file_list = mysql_query($sql)) {
			$num_images = mysql_num_rows($get_file_list);
			$on_page_count = 1;
				if ($num_images > 0 ) {
						if ($totalrows == 1 ) {
						echo "<p>".$LANG_GEN_IMAGE_IN_GALLERY.". ";	
						} else {
						echo "<p>There are <b>".$totalrows." images</b> posted.";	
						}
						echo $LANG_ADMIN_GALLERY_INTRO.' <a href="image.php?gallery_id='.$_REQUEST['gallery_id'].'&add=new">'.$LANG_GEN_CLICK_HERE.'</a>.</p>';

						$rowcolor = 0;
						if ($cfg_num_columns > $totalrows) {
							$table_width = ($totalrows * $cfg_thumb_width);
						} else {
							$table_width = ($cfg_num_columns * $cfg_thumb_width);
						}
					
						$cfg_max_img_width = $cfg_thumb_width;

						echo "<center>\n\n<!-- begin database output -->\n\n\n<table border=\"0\" bgcolor=\"#3B3B3B\" width=\"".$table_width."\" cellspacing=\"1\" cellpadding=\"3\">";
						if ($totalrows > $cfg_per_page_limit) {
							echo "<tr><td colspan=\"".$cfg_num_columns."\" align=\"right\" class=\"resultline-alt\">";
							make_user_page_nums($totalrows, $print_query, $_SERVER['PHP_SELF'], $cfg_per_page_limit, $page, $max_pages_to_show);
							echo "</td></tr>";
						}
						echo '<tr><td valign="top" align="center" class="resultline" width="'.$cfg_max_img_width.'">';
						echo '<center>';
						$col_position = 1;
						

							while (list($image_id, $image_title, $image_filename, $image_date_added) = mysql_fetch_row($get_file_list)) {
								$title_shortened = explode(" ", $image_title);
								$title_total_words = count($title_shortened);

								echo "<a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=".$image_id."\" class=\"gallerytitlelink\" title=\"".$image_title."\">\n";

									if (!empty($image_title)) {
										$x = 0;
										while ($x < $cfg_wordnumber_max) {
										echo "$title_shortened[$x] ";
										++$x;
										}
										
										if ($title_total_words > $cfg_wordnumber_max ) {
											echo "... ";
										}
									} else {
										echo "(".$LANG_GEN_NO_TITLE.")";
									}
									echo "</a>";		
								
								if ((file_exists($cfg_thumb_path."/".$image_filename)) && (!empty($image_filename))){ 
									$thumb_size = getimagesize($cfg_thumb_path."/".$image_filename);


									echo "\n<a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=$image_id\"><img src=\"".$cfg_thumb_url."/".$image_filename."?nocache=".date("U")."\" border=\"0\" $thumb_size[3] alt=\"".htmlspecialchars($image_title)."\"></a>\n";
								} else {
									echo "\n<a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=$image_id\"><img src=\"".$cfg_thumb_url."/nopic.gif\" border=\"0\" width=\"".$cfg_thumb_width."\" height=\"".$cfg_thumb_width."\"></a>\n";
								}
	
													
									
											
									$empty_cells = $num_images - $on_page_count;
									$cells_to_span = $cfg_num_columns - $col_position; 
									
									if ($col_position/$cfg_num_columns == 1){
										echo "<a href=\"javascript:decision('".$LANG_ADMIN_GALLERY_DEL_CONFIRM."','view.php?del=y&image_id=".$image_id."&gallery_id=".$_REQUEST['gallery_id']."&page=".$_REQUEST['page']."')\">:".$LANG_ADMIN_DEL.":</a></center>\n\n</td></tr>\n\n";										
										if ($num_images!=$on_page_count) {
										echo "<tr><td width=\"".$cfg_max_img_width."\" valign=\"top\"";
											echo ' align="center" class="resultline"><center>'."\n\n";
										$col_position = 1;
										} 


									
									} else {
										
										echo "<a href=\"javascript:decision('".$LANG_ADMIN_GALLERY_DEL_CONFIRM."','view.php?del=y&image_id=".$image_id."&gallery_id=".$_REQUEST['gallery_id']."&page=".$_REQUEST['page']."')\">:".$LANG_ADMIN_DEL.":</a></center></td>\n";		
										if (($empty_cells < $cfg_num_columns) && ($num_images == $on_page_count) && ($cfg_num_columns < $num_images)) {
											echo "\n<td valign=\"top\" align=\"center\" class=\"resultline\" colspan=\"$cells_to_span\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  ";

										} elseif  (($cfg_num_columns > $num_images) && ($col_position==$num_images)) {
											echo "\n";
											
										} else {
											echo "\n<td width=\"".$cfg_max_img_width."\" valign=\"top\"";
											echo ' align="center" class="resultline"><center>';
										
										}
										$col_position++;
									}

									$on_page_count++;
								
								
						}
					
					echo "</td></tr>";
					if ($totalrows > $cfg_per_page_limit) {
						echo "<tr><td colspan=\"".$cfg_num_columns."\" align=\"right\" class=\"resultline-alt\">";
						make_user_page_nums($totalrows, $print_query, $_SERVER['PHP_SELF'], $cfg_per_page_limit, $page, $max_pages_to_show);
						echo "</td></tr>";
					}
					echo "</table></center>\n<!-- end database output -->\n\n ";			

				
				} else {
					if ($totalrows < 1) {
						echo "<p>".$LANG_ADMIN_NO_IMAGES_IN_GAL." <b><a href=\"image.php?gallery_id=".$_REQUEST['gallery_id']."\">".$LANG_GEN_CLICK_HERE."</a></b>.  </p>";
					} else {
						if ($page > ($totalrows/$cfg_per_page_limit)) {
						echo "<p class=\"errortxt\">".$LANG_GEN_INVALID_PAGE_A."  <b><a href=\"view.php?gallery_id=".$_REQUEST['gallery_id']."&page=".($page -1)."\">".$LANG_GEN_CLICK_HERE_CAP."</a></b>".$LANG_GEN_INVALID_PAGE_B." <a href=\"view.php?gallery_id=".$_REQUEST['gallery_id']."\">".$LANG_GEN_RETURN_TO_GAL."</a>.  </p>";	
						}
					}
				}
				
				
		} else {
			echo "<p class=\"errortxt\">".$LANG_ERR_DB_ERROR."</p>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		}


} else {
	echo "<h3>".$LANG_ERR_ERROR."</h3>";
	echo $LANG_ERR_NO_GAL_ID;
}


include ("../layout/admin.footer.php"); ?>	
