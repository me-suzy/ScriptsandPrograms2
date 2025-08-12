<?php 
/**
* search.php
*
* This performs the boolean search functions.  
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0.3
* @since 3.0.3
*
*/

/**
*
* {@source }
*/

$GALLERY_SECTION = "search";
$PAGE_TITLE = "Search";

include ("inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ("layout/header.php");



if ((empty($_REQUEST['page'])) || ($_REQUEST['page'] <= 0)){ 
	$page = 1; 
} else {
	$page = $_REQUEST['page'];
}

$limitvalue = $page*$cfg_per_page_limit-($cfg_per_page_limit);

if (!empty($_REQUEST['keyword'])) {


if (($_REQUEST['search_type']=="and") || ($_REQUEST['search_type']=="or")) {
	$search_type = $_REQUEST['search_type'];
} else {
	$search_type = "and";
}

/**
* Prevent sql injection - eliminate any non alpha, numeric, or "-" characters from the input
*/
//$keyword = trim(preg_replace('/[^ a-z0-9-]+/i', '', $_REQUEST['keyword']));
//$search_cat = trim(preg_replace('/[^ a-z0-9-]+/i', '', $_REQUEST['search_cat']));

$keyword = trim(mysql_real_escape_string($_REQUEST['keyword']));
$search_cat = trim(mysql_real_escape_string($_REQUEST['search_cat']));

if ($_REQUEST['search_cat']!="") {
	$sql = "select name from snipe_gallery_cat where id='".$search_cat."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname) = mysql_fetch_row($get_catname);		
		} else {

			$this_catname="INVALID CATEGORY";
		}
	}
	 
}

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
$sql .=" order by added desc LIMIT $limitvalue, $cfg_per_page_limit";

/**
* Count the total number of results (for pagination)
*/
$sqlcount = "select count(*) from snipe_gallery_data where  publish='1' AND ";
$sqlcount = $sqlcount.$add_sql;

$print_query ="?keyword=".urlencode($_REQUEST['keyword'])."&txtonly=".$_REQUEST['txtonly']."&search_type=".$_REQUEST['search_type']."&search_cat=".$search_cat."&";

$sql_countresult = mysql_query($sqlcount);
list($totalrows) = mysql_fetch_row($sql_countresult);

if ($search = mysql_query($sql)) {
	$num_results = mysql_num_rows($search);
	if ($num_results > 0) {
		echo "<h3>Search Results for  &quot;".stripslashes($_REQUEST['keyword'])."&quot;</h3>";
		
			if ($totalrows ==1) {
				echo "<p>There is one match to your search. ";
			} else {
				echo "<p>There are ".$totalrows." matches to your search";
				if ($_REQUEST['search_cat']!="") {
					echo "in the <b>".stripslashes($this_catname)."</b> category";
				}
				echo ". To view the result details, click on the listing title - or <a href=\"search.php\">perform another search</a>.</p>";
			}


			/**
			* If they have specified the text only version
			*/
			if ($_REQUEST['txtonly']==1) {
				$rowcolor = 0;
				echo "<center>\n\n<!-- begin database output -->\n\n\n<table border=\"0\"  width=\"90%\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#CCCCCC\">";
				if ($totalrows > $cfg_per_page_limit) {
				echo "<tr><td colspan=\"5\" align=\"right\" class=\"resultline-light\">";
					make_user_page_nums($totalrows, $print_query, $_SERVER['PHP_SELF'], $cfg_per_page_limit, $page, $max_pages_to_show);
					echo "</td></tr>";
				}

					echo "<tr><td><b>Title</b></td><td><b>File size</b></td><td><b>Appears In</b></td><td><b>Date</b></td><td><b>Added</b></td></tr>";

					while (list($image_id, $image_filename,  $image_thumbname,  $image_date,  $image_title,  $image_details,  $image_author,  $image_location,  $image_cat_id,   $image_added) = mysql_fetch_row($search)) {

						$rowcolor++;
						$report_class = ($rowcolor % 2) ? 'resultline-alt' : 'resultline-light';


					/**
					* Highlight search words
					*/
						if (count($keyword) > 0) { 
							  // loop through the array
							  for ($i=0;$i<count($keyword);$i++) { 
									$image_title =  eregi_replace($keyword[$i], "<span class=\"highlighttxt\">\\0</span>", $image_title);
									
							  }
						} 

					if ($image_title=="") {
						$image_title = "(No title)";
					} 

					$picweight=filesize($cfg_pics_path."/".$image_filename);
					if ($picweight >= 1073741824) { 
						$picweight = round($picweight / 1073741824 * 100) / 100 . "g"; 
					} elseif ($picweight >= 1048576) { 
						$picweight = round($picweight / 1048576 * 100) / 100 . "m"; 
					} elseif ($picweight >= 1024) { 
						$picweight = round($picweight / 1024 * 100) / 100 . "k"; 
					} else { 
						$picweight = $picweight . "b"; 
					} 

					echo "<tr><td align=\"left\" class=\"".$report_class."\"><a href=\"image.php?image_id=".$image_id."&search_type=".$search_type."&keyword=".urlencode($_REQUEST['keyword'])."&search_cat=".$search_cat."\">$image_title</a></td><td align=\"left\" class=\"".$report_class."\">".$picweight."</td>";
					echo "<td class=\"".$report_class."\">";

						$sql = "select name, cat_parent from snipe_gallery_cat where id='".$image_cat_id."'";
						if ($get_img_catname = mysql_query($sql)) {
							$is_valid_cat = mysql_num_rows($get_img_catname);
							if ($is_valid_cat > 0) {				
								list($img_catname, $img_catname_parent) = mysql_fetch_row($get_img_catname);
								
								
							}
						}
						if ($img_catname_parent > 0) {
						$sql = "select name from snipe_gallery_cat where id='".$img_catname_parent."'";
							if ($get_img_catname_parent = mysql_query($sql)) {
								$is_valid_cat_parent = mysql_num_rows($get_img_catname_parent);
								if ($is_valid_cat_parent > 0) {				
									list($img_catname_parent) = mysql_fetch_row($get_img_catname_parent);
									
									
								}
							}
						}
						echo "<a href=\"view.php?gallery_id=".$image_cat_id."\">".$img_catname_parent.":: ".$img_catname."</a>";
					echo "</td>";
					echo "<td class=\"".$report_class."\">";
					if ($image_date!="0000-00-00") {
						echo make_datetime_shortpretty($image_date);
					} else {
						echo "&nbsp;";
					}	
					
					echo "</td><td class=\"".$report_class."\">".make_datetime_shortpretty($image_added)."</td></tr>";
					if ($totalrows > $cfg_per_page_limit) {
					
				}

				}

				if ($totalrows > $cfg_per_page_limit) {
				echo "<tr><td colspan=\"5\" align=\"right\" class=\"resultline-light\">";
					make_user_page_nums($totalrows, $print_query, $_SERVER['PHP_SELF'], $cfg_per_page_limit, $page, $max_pages_to_show);
					echo "</td></tr>";
				}
				echo "</table>";
	
		/**
		* If they have not selected text only, build the image display
		*/
		} else {

				
				if ($cfg_num_columns > $totalrows) {
					$table_width = ($num_results * $cfg_thumb_width);
					$cfg_num_columns = $num_results;
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
				

					while (list($image_id, $image_filename,  $image_thumbname,  $image_date,  $image_title,  $image_details,  $image_author,  $image_location,  $image_cat_id,  $image_keywords,  $image_publish,   $image_added) = mysql_fetch_row($search)) {

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
							} 
							echo "<a href=\"image.php?page=$page&search_type=".$search_type."&image_id=$image_id&keyword=".urlencode($_REQUEST['keyword'])."&search_cat=".$search_cat."\"><img src=\"".$cfg_thumb_url."/".$image_filename."\" border=\"0\" $thumb_size[3] alt=\"".htmlspecialchars($image_title)."\"></a>";

							if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
								echo "</div>\n";
							} 
						} else {
							if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
								echo "\n<div class=\"img-shadow\">";
							} 
							echo "<a href=\"image.php?page=$page&search_type=".$search_type."&image_id=$image_id&keyword=".urlencode($_REQUEST['keyword'])."&search_cat=".$search_cat."\"><img src=\"".$cfg_thumb_url."/nopic.gif\" border=\"0\" width=\"".$cfg_thumb_width."\" height=\"".$cfg_thumb_width."\"></a>";

							if ((($this_frame_style=="0") || ($this_frame_style=="")) && ($cfg_use_dropshadow==1)) {
								echo "</div>\n";
							}
						}
						
								
							if (!empty($image_title)) {
								if ((!empty($this_frame_style)) && ($valid_framestyle > 0)) {
									echo "<tr><td colspan=\"3\" align=\"center\">";
								}
								
								echo "\n\n<center><a href=\"image.php?page=$page&gallery_id=".$image_cat_id."&image_id=".$image_id."&keyword=".urlencode($_REQUEST['keyword'])."&search_cat=".$search_cat."\" class=\"gallerytitlelink\" title=\"".$image_title."\">";
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
	}

/**
* No matches found - print out a search form
*/
} else {
		echo "<h3>No matches</h3>\n";
		echo "<p>There are no matches  for  &quot;".stripslashes($_REQUEST['keyword'])."&quot;";
		if ($_REQUEST['search_cat']!="") {
			echo "in the <b>".stripslashes($this_catname)."</b> category.";
		}
		echo "</p>";
		include ($cfg_admin_path."/lib/forms/searchform.php");
}

/**
* If there was an error, display a friendly message
*/
} else {
	echo "<h3>Error Performing Search</h3><span class=\"errortxt\">A database error has occured.</h3>";
	if ($cfg_debug_on==1) {
		echo "<p><b>mySQL said: </b>";
		echo mysql_error();
		echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

	}
}

/**
* No keywords specified, so print the plain search page
*/
} else {
	echo "<h3>Search</h3>";
	include ($cfg_admin_path."/lib/forms/searchform.php");

}


include ("layout/footer.php"); ?>	
