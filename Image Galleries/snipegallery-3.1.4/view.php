<?php 
/**
* view.php
*
* This file displays the gallery thumbnails for the user-view
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
include ("inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");

if (!empty($_REQUEST['gallery_id'])) {
	$sql = "select name, description, frame_style, watermark_txt, display_orderby, display_order  from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_cat_description, $this_frame_style, $this_watermark_txt, $this_display_orderby, $this_display_order) = mysql_fetch_row($get_catname);		
		}
	}
}

include ("layout/header.php");


if ((empty($_REQUEST['page'])) || ($_REQUEST['page'] <= 0)){ 
	$page = 1; 
} else {
	$page = $_REQUEST['page'];
}

$limitvalue = $page*$cfg_per_page_limit-($cfg_per_page_limit);
// get the category details
if (!empty($_REQUEST['gallery_id'])) {



if (!empty($this_frame_style)) {
$sql = "select top_left_sm,  top_bg_sm,  top_right_sm,  left_bg_sm,  right_bg_sm,  bottom_left_sm,  bottom_bg_sm,  bottom_right_sm  from snipe_gallery_frames where frame_id='".$this_frame_style."'";
	if ($get_frames = mysql_query($sql)) {
		$valid_framestyle = mysql_num_rows($get_frames);
		if ($valid_framestyle > 0) {				
			list($frame_top_left_sm,  $frame_top_bg_sm,  $frame_top_right_sm,  $frame_left_bg_sm,  $frame_right_bg_sm,  $frame_bottom_left_sm,  $frame_bottom_bg_sm,  $frame_bottom_right_sm) = mysql_fetch_row($get_frames);	
			
			if ((!empty($frame_top_left_sm)) && (file_exists($cfg_frames_path."/".$frame_top_left_sm))) {
				$frame_top_left_sm_size = getimagesize($cfg_frames_path."/".$frame_top_left_sm);
			} else {
				$frame_top_left_sm = "spacer.gif";
			}

			if ((!empty($frame_top_right_sm)) && (file_exists($cfg_frames_path."/".$frame_top_right_sm))) {
				$frame_top_right_sm_size = getimagesize($cfg_frames_path."/".$frame_top_right_sm);
			} else {
				$frame_top_right_sm = "spacer.gif";
			}

			if ((!empty($frame_bottom_left_sm)) && (file_exists($cfg_frames_path."/".$frame_bottom_left_sm))) {
				$frame_bottom_left_sm_size = getimagesize($cfg_frames_path."/".$frame_bottom_left_sm);
			} else {
				$frame_bottom_left_sm = "spacer.gif";
			}

			if ((!empty($frame_bottom_right_sm)) && (file_exists($cfg_frames_path."/".$frame_bottom_right_sm))) {
				$frame_bottom_right_sm_size = getimagesize($cfg_frames_path."/".$frame_bottom_right_sm);
			} else {
				$frame_bottom_right_sm = "spacer.gif";
			}

		}
	}

}




echo "<h3>View Images in: ".stripslashes($this_catname)."</h3>";



$sql = "select id, title, thumbname, added from snipe_gallery_data where cat_id='".$_REQUEST['gallery_id']."' AND publish=1";
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
		if ($this_display_order!="id") {
			$sql .= ", id asc ";
		}

$sql .=" LIMIT $limitvalue, $cfg_per_page_limit";

$sqlcount = "select count(*) from snipe_gallery_data where cat_id='".$_REQUEST['gallery_id']."'  AND publish=1 ";
$print_query ="?gallery_id=".$_REQUEST['gallery_id']."&";

//echo $sql;

$sql_countresult = mysql_query($sqlcount);
list($totalrows) = mysql_fetch_row($sql_countresult);



		if ($get_file_list = mysql_query($sql)) {
			$num_images = mysql_num_rows($get_file_list);
			$on_page_count = 1;
				if ($num_images > 0 ) {
					echo "<p>";
						if (($page==1) && (!empty($this_cat_description))) {
							echo stripslashes(nl2br($this_cat_description))."<br>";
						} 
						if ($totalrows == 1 ) {
							echo "There is <b>$totalrows image</b> posted.";	
						} else {
							echo "There are <b>$totalrows images</b> posted.  ";	
						}
						echo "Click on the thumbnails to view the fullsize images. </p>";

						$rowcolor = 0;
						if ($cfg_num_columns > $totalrows) {
							$table_width = ($totalrows * $cfg_thumb_width);
						} else {
							$table_width = ($cfg_num_columns * $cfg_thumb_width);
						}
					
						$cfg_max_img_width = $cfg_thumb_width;

						echo "<center>\n\n<!-- begin database output -->\n\n\n<table border=\"0\"  width=\"".$table_width."\" cellspacing=\"1\" cellpadding=\"3\">";
						if ($totalrows > $cfg_per_page_limit) {
							echo "<tr><td colspan=\"".$cfg_num_columns."\" align=\"right\" class=\"resultline-alt\">";
							make_user_page_nums($totalrows, $print_query, $_SERVER['PHP_SELF'], $cfg_per_page_limit, $page, $max_pages_to_show);
							echo "</td></tr>";
						}
						echo '<tr><td valign="top" align="center" width="'.$cfg_max_img_width.'">';
						echo '<center>';
						$col_position = 1;
						

							while (list($image_id, $image_title, $image_filename, $image_date_added) = mysql_fetch_row($get_file_list)) {

								echo "\n\n <!-- begin image record --> \n\n";
								$title_shortened = explode(" ", $image_title);
								$title_total_words = count($title_shortened);

								if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
								echo "\n\n".'<table border="0" cellspacing="0" cellpadding="0">'."\n";
								}

									
								
								if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
								echo '<tr><td '.$frame_top_left_sm_size[3].'>';
								}
								
								if ((file_exists($cfg_thumb_path."/".$image_filename)) && (!empty($image_filename))){ 
									$thumb_size = getimagesize($cfg_thumb_path."/".$image_filename);

									if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
										echo "\n<div class=\"img-shadow\">\n";
									} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
										
										echo "\n".'<img src="'.$cfg_frames_url.'/'.$frame_top_left_sm.'" border="0"></td>';
										echo '<td background="'.$cfg_frames_url.'/'.$frame_top_bg_sm.'"><img src="'.$cfg_app_url.'/images/spacer.gif" width="1" height="1" border="0"></td>';
										echo '<td '.$frame_top_right_sm_size[3].'><img src="'.$cfg_frames_url.'/'.$frame_top_right_sm.'" border="0"></td></tr>'."\n";
										echo '<tr><td background="'.$cfg_frames_url .'/'.$frame_left_bg_sm.'"></td><td>'."\n";
									}
									echo "<a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=$image_id\"><img src=\"".$cfg_thumb_url."/".$image_filename."\" border=\"0\" $thumb_size[3] alt=\"".htmlspecialchars($image_title)."\"></a>";

									if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
										echo "</div>\n";
									} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
										echo '<td background="'.$cfg_frames_url .'/'.$frame_right_bg_sm.'"></td></tr><tr>';
										echo '<td '.$frame_bottom_left_sm_size[3].'><img src="'.$cfg_frames_url .'/'.$frame_bottom_left_sm.'" border="0"></td><td background="'.$cfg_frames_url .'/'.$frame_bottom_bg_sm.'"></td><td><img src="'.$cfg_frames_url .'/'.$frame_bottom_right_sm.'" border="0"></td></tr>'."\n";
									}
								} else {
									if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
										echo "\n<div class=\"img-shadow\">";
									} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
										echo '<img src="'.$cfg_frames_url.'/'.$frame_top_left_sm.'" border="0"></td>';
										echo '<td background="'.$cfg_frames_url.'/'.$frame_top_bg_sm.'"><img src="'.$cfg_app_url.'/images/spacer.gif" width="1" height="1" border="0"></td>';
										echo '<td '.$frame_top_right_sm_size[3].'><img src="'.$cfg_frames_url.'/'.$frame_top_right_sm.'" border="0"></td></tr>'."\n";
										echo '<tr><td background="'.$cfg_frames_url .'/'.$frame_left_bg_sm.'"></td><td>';
									}
									echo "<a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=$image_id\"><img src=\"".$cfg_thumb_url."/nopic.gif\" border=\"0\" width=\"".$cfg_thumb_width."\" height=\"".$cfg_thumb_width."\"></a>";

									if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
										echo "</div>\n";
									} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
										echo '<td background="'.$cfg_frames_url .'/'.$frame_right_bg_sm.'"></td></tr><tr>'."\n";
										echo '<td '.$frame_bottom_left_sm_size[3].'><img src="'.$cfg_frames_url .'/'.$frame_bottom_left_sm.'" border="0"></td><td background="'.$cfg_frames_url .'/'.$frame_bottom_bg_sm.'"></td><td '.$frame_bottom_right_sm_size[3].'><img src="'.$cfg_frames_url .'/'.$frame_bottom_right_sm.'" border="0"></td></tr>'."\n\n";
									}
								}
								
										
									if (!empty($image_title)) {
										if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
											echo "<tr><td colspan=\"3\" align=\"center\">";
										}
										
										echo "\n\n<center><a href=\"image.php?page=$page&gallery_id=".$_REQUEST['gallery_id']."&image_id=".$image_id."\" class=\"gallerytitlelink\" title=\"".$image_title."\">";
										$x = 0;
										while ($x <= $cfg_wordnumber_max) {
											echo trim($title_shortened[$x]);
											if ($title_total_words > ($x+1)) {
												echo " ";

											}
										++$x;
										}
										
										if ($title_total_words > $cfg_wordnumber_max ) {
											echo "...";
										}
										echo "</a></center>";
										if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
											echo "</td></tr>";
										}
									} 
									
	
									if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
										echo "</table>\n\n";
									}
									
											
									$empty_cells = $num_images - $on_page_count;
									$cells_to_span = $cfg_num_columns - $col_position; 
									
									if ($col_position/$cfg_num_columns == 1){
										echo "</center>\n\n</td></tr>\n\n";										
										if ($num_images!=$on_page_count) {
										echo "<tr><td width=\"".$cfg_max_img_width."\" valign=\"top\"";
											echo ' align="center"><center>'."\n\n";
										$col_position = 1;
										} 


									
									} else {
										
										echo "</center></td>\n";		
										if (($empty_cells < $cfg_num_columns) && ($num_images == $on_page_count) && ($cfg_num_columns < $num_images)) {
											echo "\n<td valign=\"top\" align=\"center\" colspan=\"$cells_to_span\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  ";

										} elseif  (($cfg_num_columns > $num_images) && ($col_position==$num_images)) {
											echo "\n";
											
										} else {
											echo "\n<td width=\"".$cfg_max_img_width."\" valign=\"top\"";
											echo ' align="center"><center>';
										
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
						echo "<p>There are no images listed in this gallery yet.   </p>";
					} else {
						if ($page > ($totalrows/$cfg_per_page_limit)) {
						echo "<p class=\"errortxt\">You have reached a page number that appears to be invalid.  <b><a href=\"view.php?gallery_id=".$_REQUEST['gallery_id']."&page=".($page -1)."\">Click here</a></b> to try the previous page, or <a href=\"view.php?gallery_id=".$_REQUEST['gallery_id']."\">return to the gallery page</a>.  </p>";	
						}
					}
				}
				
				
		} else {
			echo "<p class=\"errortxt\">A database error has occured.</p>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		}


} else {
 echo "error - no gallery id";
}


include ("layout/footer.php"); ?>	
