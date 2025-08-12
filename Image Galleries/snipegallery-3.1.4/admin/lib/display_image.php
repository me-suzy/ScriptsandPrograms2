<?php 

/**
* display_image.php
*
* This file contains the code for the image display used in the user view
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.1.1
* @since 3.0
*
*/



/*
* Calculate the next and previous links
*/

if ((empty($_REQUEST['page'])) || ($_REQUEST['page'] <= 0)){ 
	$page = 1; 
} else {
	$page = $_REQUEST['page'];
}

$limitvalue = $page*$cfg_per_page_limit-($cfg_per_page_limit);

if (!empty($_REQUEST['keyword'])) { 
/**
* Explode the keywords to get an array of distinct words
*/
$keyword = explode(" ",$keyword);

$sql = "select id, filename,  thumbname,  img_date,  title,  ";
$sql .="details,  author,  location,  cat_id, added  from snipe_gallery_data where  publish='1' AND ";
$add_sql = "";
if (count($keyword) > 0) { 
		/**
		* loop through all of the keywords to make them into seperate queries
		*/
		for ($i=0;$i<count($keyword);$i++) { 
            $add_sql .=" ((details LIKE '%".$keyword[$i]."%' OR keywords LIKE '%".$keyword[$i]."%' OR title LIKE '%".$keyword[$i]."%'  OR location LIKE '%".$keyword[$i]."%')  OR \n"; 
			$add_sql .=" (details='".$keyword[$i]."' OR keywords='".$keyword[$i]."' OR title='".$keyword[$i]."'  OR location='".$keyword[$i]."'))  \n"; 
			$x = $i +1;
			if (count($keyword) > $x) { 
				$add_sql .=$search_type ." ";
			}
      }
} 

/**
* If they have specified to search within a specific category, include that in the query
*/
if ($_REQUEST['search_cat']!="") {
	$add_sql .=" AND cat_id='".$search_cat."' \n";
}
$sql = $sql.$add_sql;
$sql .=" order by added desc ";

} else {


$sql = "select id from snipe_gallery_data where cat_id='".$_REQUEST['gallery_id']."' AND publish=1";
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

}

if ($get_file_list = mysql_query($sql)) {
	$num_total = mysql_num_rows($get_file_list);
	$count = 1;
	$pos_array = array();
	while (list($page_img_id) = mysql_fetch_row($get_file_list)) {
		$pos_array[$count]= $page_img_id;	

		if ($page_img_id==$_REQUEST['image_id']){
			$prev_count = ($count - 1);
			$next_count = ($count + 1);
			
		}
		$count++;	
	}

	if ($prev_count >= 0) {
		$prev_id = $pos_array[$prev_count];
	}
	if ($next_count <= $num_total) {
		$next_id = $pos_array[$next_count];				
	}
	
} 

?>


<center>
<table border="0" cellspacing="1" cellpadding="3">
<tr>
	<td align="center">


	<?php 

	if (file_exists($cfg_pics_path."/".$image_filename)) {
	$img_size = @getimagesize($cfg_pics_path."/".$image_filename, $info);
	$sub_table_width = ($frame_top_left_size[0] + $frame_top_right_size[0] + $img_size[0]);

	if ($sub_table_width < 300) {
		$sub_table_width = 400;
	} else {
		$sub_table_width = $sub_table_width;
	}

		echo '<center>';
		?>
		<?php if (($cfg_nextprev_links=="top") || ($cfg_nextprev_links=="both")) { ?>
		<table border="0" cellspacing="0" cellpadding="2" width="<?php echo $sub_table_width; ?>">
		<tr>
			<td align="left" class="resultline" width="50%">
				<?php
				if (!empty($prev_id)) {
					if (!empty($_REQUEST['keyword'])) { 
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&keyword='.urlencode($_REQUEST['keyword']).'&image_id='.$prev_id.'&search_cat='.($_REQUEST['search_cat']).'&search_type='.$search_type.'">&#171; Prev</a></b>';
					} else {
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&gallery_id='.$_REQUEST['gallery_id'].'&image_id='.$prev_id.'">&#171; Prev</a></b>';
					}
				}
				?>&nbsp;
			</td>
			<td align="right" class="resultline" width="50%">
			<?php
				if (!empty($next_id)) {
					if (!empty($_REQUEST['keyword'])) { 
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&keyword='.urlencode($_REQUEST['keyword']).'&image_id='.$next_id.'&search_cat='.($_REQUEST['search_cat']).'&search_type='.$search_type.'">Next &#187;</a></b>';
					} else {
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&gallery_id='.$_REQUEST['gallery_id'].'&image_id='.$next_id.'">Next &#187;</a></b>';
					}
				}
				?>&nbsp;
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<?php if (!empty($_REQUEST['keyword'])) { ?>
				<b><a href="search.php?keyword=<?php echo urlencode($_REQUEST['keyword']); ?>&page=<?php echo $page; ?>&search_cat=<?php echo ($_REQUEST['search_cat']); ?>&search_type=<?php echo $search_type; ?>">Back to Search Results for &quot;<?php echo urldecode(stripslashes($_REQUEST['keyword'])); ?>&quot;  - Page <?php echo $page; ?></a></b>
			<?php } else { ?>
				<b><a href="view.php?gallery_id=<?php echo $_REQUEST['gallery_id']; ?>&page=<?php echo $page; ?>">Back to &quot;<?php echo stripslashes($this_catname); ?>&quot; Index - Page <?php echo $page; ?></a></b>
				<?php } ?>
			</td>
		</tr>
		</table>
		<?php } 

		
		if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
			echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td>';
			echo "\n<div class=\"img-shadow\" align=\"center\">";
		} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
			echo '<table border="0" cellspacing="0" cellpadding="0">';
			echo '<tr><td '.$frame_top_left_size[3].'>';
			echo '<img src="'.$cfg_frames_url.'/'.$frame_top_left.'" border="0"></td>';
			echo '<td background="'.$cfg_frames_url.'/'.$frame_top_bg.'"><img src="'.$cfg_app_url.'/images/spacer.gif" width="1" height="1" border="0"></td>';
			echo '<td '.$frame_top_right_size[3].'><img src="'.$cfg_frames_url.'/'.$frame_top_right.'" border="0"></td></tr>'."\n";
			echo '<tr><td background="'.$cfg_frames_url .'/'.$frame_left_bg.'"></td><td>';
		}

		echo '<img src="'.$cfg_pics_url."/".$image_filename.'?'.date("U").'" '.$img_size[3].' alt="'.htmlspecialchars(stripslashes($image_title)).'">';
		if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
			echo "</div>\n";
			echo '</td></tr></table>';
		} elseif ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
			echo '<td background="'.$cfg_frames_url .'/'.$frame_right_bg.'"></td></tr><tr>';
			echo '<td '.$frame_bottom_left_size[3].'><img src="'.$cfg_frames_url .'/'.$frame_bottom_left.'" border="0"></td><td background="'.$cfg_frames_url .'/'.$frame_bottom_bg.'"></td><td><img src="'.$cfg_frames_url .'/'.$frame_bottom_right.'" border="0"></td></tr>'."\n</table>";
		}
		echo '</center></td></tr>';
	} else {
		echo "<span class=\"smerrortxt\">can't find file: ".$cfg_pics_url."/".$image_filename."</span></td></tr>";
	}


	?>
	
<tr>
	<td valign="top" align="left">
	<table border="0" width="<?php echo $sub_table_width; ?>">
	<tr>
		<td>
		<table border="0">
		<?php if ((isset($image_details)) && (!empty($image_details))) { ?>
		<tr>
			<td valign="top"><b>Description:</b> </td>
		<td>
		<?php echo nl2br(stripslashes($image_details));?></td>
		</tr>
		<?php } ?>

		<?php if ((isset($image_date)) && (!empty($image_date)) && ($image_date!="0000-00-00")) { ?>	
		<tr>
			<td><b>Photo Date:</b></td>
			<td><?php echo make_datetime_shortpretty($image_date); ?>
			</td>
		</tr>
		<?php } ?>

		<?php if ((isset($image_author)) && (!empty($image_author))) { ?>	
		<tr>
			<td><b>Photographer:</b></td>
			<td><?php echo stripslashes($image_author); ?></td>
		</tr>
		<?php } ?>


		<?php if ((isset($image_location)) && (!empty($image_location))) { ?>	
		<tr>
			<td><b>Location:</b></td>
			<td><?php echo stripslashes($image_location); ?></td>
		</tr>
		
		<?php } ?>

		<?php if ((isset($image_keywords)) && (!empty($image_keywords)) && ($cfg_display_keywords==1)) { ?>	
		<tr>
			<td><b>Keywords:</b></td>
			<td>
			
			<?php 
			if ($cfg_search_keywords==1) {

					if (!empty($image_keywords)) { 
						/**
						* Explode the keywords to get an array of distinct words
						*/
						$keyword = explode(" ",$image_keywords);

						
						if (count($keyword) > 0) { 
								/**
								* loop through all of the keywords to make them into seperate queries
								*/
								for ($i=0;$i<count($keyword);$i++) { 
									$searchsql = "select count(*) from snipe_gallery_data where  publish='1' AND id!=".$_REQUEST['image_id']." AND ";
									$searchsql .=" ((details LIKE '%".$keyword[$i]."%' OR keywords LIKE '%".$keyword[$i]."%' OR title LIKE '%".$keyword[$i]."%'  OR location LIKE '%".$keyword[$i]."%')  OR \n"; 
									$searchsql .=" (details='".$keyword[$i]."' OR keywords='".$keyword[$i]."' OR title='".$keyword[$i]."'  OR location='".$keyword[$i]."'))  \n"; 
									$x = $i +1;
									

									if ($search_query = mysql_query($searchsql)) {
									list($search_match_rows) = mysql_fetch_row($search_query);
										if ($search_match_rows > 0) {
											echo " <a href=\"search.php?keyword=".stripslashes($keyword[$i])."\">".stripslashes($keyword[$i])."</a>";
										} else {
											echo " ".stripslashes($keyword[$i]);
										}
										if (count($keyword) > $x) { 
											echo ",";
										}
									} else {
										if ($cfg_debug_on==1) {
											echo "<p>DB Error: <b>mySQL said: </b>";
											echo mysql_error();
											echo "</p>\n\n<p><b>SQL query:</b> $searchsql </p>";

										}
									}
							} 
						
						}
					} else {
						echo "none";
					}
			} else {
				echo stripslashes($image_keywords);
			}
			
			?></td>
		</tr>	
		
		<?php if ((!isset($_REQUEST['gallery_id'])) && (empty($_REQUEST['gallery_id']))) { ?>	
		<tr>
			<td><b>In Gallery:</b></td>
			<td><?php
			$sql = "select name from snipe_gallery_cat where id='".$image_cat_id."'";
			if ($get_catname = mysql_query($sql)) {
				list($image_catname) = mysql_fetch_row($get_catname);
				if ($image_cat_id!="") {
					echo "<a href=\"view.php?gallery_id=".$image_cat_id."\">".stripslashes($image_catname)."</a>";
				}
			} else {
				if ($cfg_debug_on==1) {
				echo "<p>DB Error: <b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $searchsql </p>";

				}
			}
			?></td>
		</tr>

		<?php } ?>
		<?php } ?>


		

		<?php if ($cfg_iptc_user_view==1) { 
		$iptc = iptcparse($info["APP13"]);
			if (is_array($iptc)) { 
		?>
		<tr>
			<td valign="top">META data:</td>		
			<td>
			<?php
			
					$iptc_caption = $iptc["2#120"][0]; 					
					$iptc_creation_date = $iptc["2#055"][0]; 
					$iptc_photog = $iptc["2#080"][0]; 
					$iptc_credit_byline_title = $iptc["2#085"][0]; 
					$iptc_city = $iptc["2#090"][0]; 
					$iptc_state = $iptc["2#095"][0]; 
					$iptc_country = $iptc["2#101"][0]; 
					$iptc_otr = $iptc["2#103"][0]; 
					$iptc_headline = $iptc["2#105"][0]; 
					$iptc_source = $iptc["2#110"][0]; 
					$iptc_photo_source = $iptc["2#115"][0]; 
					$iptc_caption = $iptc["2#120"][0];   
					$iptc_keywords = $iptc["2#025"][0];
					//echo $iptc_keywords;

					if (!empty($iptc_headline)) {
						echo "Headline: ".$iptc_headline ."<br>";
					}
					if (!empty($iptc_creation_date)) {
						$iptc_showdate = strtotime($iptc_creation_date);
						echo "Created On: ".date("F j, Y", $iptc_showdate)."<br>";
					}

					if (!empty($iptc_caption)) {
						echo "Caption: ".$iptc_caption ."<br>";
					}
					if (!empty($iptc_photog)) {
						echo "Photographer: ".$iptc_photog;
						if (!empty($iptc_credit_byline_title)) {
							echo " (".$iptc_credit_byline_title.")";
						}
						echo "<br />";
					}
					
					if ((!empty($iptc_city)) || (!empty($iptc_state)) || (!empty($iptc_country))) {
						echo "Location: ";
							if (!empty($iptc_city)) {
								echo $iptc_city;
								if (!empty($iptc_state)) {
									echo ", ";
								}
							}
							if (!empty($iptc_state)) {
								echo $iptc_state." ";
							}
							if (!empty($iptc_country)) {
								echo $iptc_country;
							}
						echo "<br />";
					}

					$c = count ($iptc["2#025"]);
					if ($c > 0) {
						echo "Keywords: ";
					   for ($i=0; $i <$c; $i++) 
					   {
						   echo $iptc["2#025"][$i].' ';
					   }
					}

				
				?>
			
			
			
			</td>
		</tr>
		<?php 
			}
		} ?>
		</table>
	</td>
	</tr>
	</table>

</td>
</tr>
	</table>


<?php if (($cfg_nextprev_links=="bottom") || ($cfg_nextprev_links=="both") || ($cfg_nextprev_links!="top")) { ?>
		<table border="0" cellspacing="0" cellpadding="2" width="<?php echo $sub_table_width; ?>">
		<tr>
			<td align="left" class="resultline" width="50%">
				<?php
				if (!empty($prev_id)) {
					if (!empty($_REQUEST['keyword'])) { 
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&keyword='.urlencode($_REQUEST['keyword']).'&image_id='.$prev_id.'&search_cat='.($_REQUEST['search_cat']).'&search_type='.$search_type.'">&#171; Prev</a></b>';
					} else {
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&gallery_id='.$_REQUEST['gallery_id'].'&image_id='.$prev_id.'">&#171; Prev</a></b>';
					}
				}
				?>&nbsp;
			</td>
			<td align="right" class="resultline" width="50%">
			<?php
				if (!empty($next_id)) {
					if (!empty($_REQUEST['keyword'])) { 
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&keyword='.urlencode($_REQUEST['keyword']).'&image_id='.$next_id.'&search_cat='.($_REQUEST['search_cat']).'&search_type='.$search_type.'">Next &#187;</a></b>';
					} else {
						echo '<b><a href="image.php?page='.$_REQUEST['page'].'&gallery_id='.$_REQUEST['gallery_id'].'&image_id='.$next_id.'">Next &#187;</a></b>';
					}
				}
				?>&nbsp;
			</td>
		</tr>
		<tr>
			<td align="center" colspan="2">
			<?php if (!empty($_REQUEST['keyword'])) { ?>
				<b><a href="search.php?keyword=<?php echo urlencode($_REQUEST['keyword']); ?>&page=<?php echo $page; ?>&search_cat=<?php echo ($_REQUEST['search_cat']); ?>&search_type=<?php echo $search_type; ?>">Back to Search Results for &quot;<?php echo urldecode(stripslashes($_REQUEST['keyword'])); ?>&quot;  - Page <?php echo $page; ?></a></b>
			<?php } else { ?>
				<b><a href="view.php?gallery_id=<?php echo $_REQUEST['gallery_id']; ?>&page=<?php echo $page; ?>">Back to &quot;<?php echo stripslashes($this_catname); ?>&quot; Index - Page <?php echo $page; ?></a></b>
				<?php } ?>
			</td>
		</tr>
		</table>
<?php } ?>
</center>

