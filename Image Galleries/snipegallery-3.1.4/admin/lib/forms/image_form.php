<?php 

/**
* image_form.php
*
* The form code for adding/editing an image
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/



?>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="myForm" enctype="multipart/form-data">

<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
<tr>
	<td colspan="2" class="resultline"><b>
	<?php 
	if ((isset($_REQUEST['image_id'])) && (!empty($_REQUEST['image_id']))) {
		echo "Edit Image ";
	} else {
		echo "Add New Image";
	}
	?>
</b></td>
</tr>
<?php if ((!isset($_REQUEST['image_id'])) && (empty($_REQUEST['image_id'])) && (empty($image_filename))) { ?>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[0]; ?>: </td>
	<td class="resultline-alt"><input name="form_image" type="file" size="50"></td>
</tr>

<?php } else { ?>
<tr>
	<td class="resultline-alt" colspan="2">
	<?php 
	if (file_exists($cfg_pics_path."/".$image_filename)) {
	$img_size = @getimagesize($cfg_pics_path."/".$image_filename, $info);
		echo '<center><img src="'.$cfg_pics_url."/".$image_filename.'?'.date("U").'" '.$img_size[3].'></center></td></tr><tr><td class="resultline-alt" valign="top">'.$LANG_IMG_FIELD[20].':</td><td class="resultline-alt">';
		echo "Type: ";
		// echo " mime: ".$img_size[mime];
		if ($img_size[2]==1) {
			echo "GIF (no thumbnailing)";
		} elseif ($img_size[2]==2) {
			echo "JPG";	
			if ($img_size[channels] ==3) {
				echo " (RGB)";
			} elseif ($img_size[channels]==4) {
				echo " (CMYK)";

			}
			
			//$exif = exif_read_data($cfg_pics_path."/".$image_filename, 0, true);
			//echo "<br />\n";
			//foreach ($exif as $key => $section) {
			//   foreach ($section as $name => $val) {
			//	   echo "$key.$name: $val<br />\n";
			//   }
			//}	


		} elseif ($img_size[2]==3) {
			echo "PNG";
		} elseif ($img_size[2]==4) {
			echo "SWF";
		}
		
		echo "<br>";
		echo $LANG_IMG_FIELD[19].": ".$img_size[0]." x ".$img_size[1]." pixels<br>";

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
		echo $LANG_IMG_FIELD[14].": ".$picweight."\n<br>";
		if ($cfg_use_cache==1) {
			if  (file_exists($cfg_cache_path."/".$image_filename)){
				echo "Cache Image: exists<br>";
			} else {
				echo "Cache Image: error - cache file missing \n<br>";
			}
		}
		echo $LANG_IMG_FIELD[15].": ".make_datetime_pretty($image_added)."\n";
		//if ((($img_size[2]==2) || ($img_size[2]==3)) && (function_exists('imagerotate'))) {
			//echo "<br>&#187; <b><a href=\"image.php?gallery_id=".$_REQUEST['gallery_id']."&image_id=".$_REQUEST['image_id']."&rotate=right&page=".$_REQUEST['page']."\">rotate image</a></b>";

		//}
		
		if (((($img_size[2]==2) || ($img_size[2]==3)) && ($cfg_minthumb_width < $img_size[0]) && ($cfg_minthumb_height < $img_size[1])) && ((isset($cfg_enable_croptool)) && ($cfg_enable_croptool==1))) {
			echo "<br>&#187; <b><a href=\"crop.php?gallery_id=".$_REQUEST['gallery_id']."&image_id=".$_REQUEST['image_id']."&croptype=full&page=".$_REQUEST['page']."\">".$LANG_IMG_FIELD[16]."</a></b>";
		}
		
		echo '<br>&#187; <b><a href="javascript:void(0);" onClick="expandcontent(\'uploadbox\')">'.$LANG_IMG_FIELD[22].'</a></b>';
		// echo "<br>Font size: ".imagefontwidth($cfg_font_path."".$cfg_font_name);
		echo '<span id="uploadbox" class="switchcontent"><input name="form_image" type="file" size="30"></span>';
		echo '</td></tr>';


		if ((count(iptcparse($info["APP13"])) > 1) && ($cfg_use_iptc_meta==1)){
		?>
		<tr>
			<td class="resultline-alt" valign="top">META data: </td> 
			<td class="resultline-alt"><span class="smadmin">
			<?php 			
			

			$iptc = iptcparse($info["APP13"]);
			if (is_array($iptc)) { 
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

				}
	 


				
		 ?>
			</span></td> 
		</tr> 
		<?php
		}
	} else {
		echo "<span class=\"smerrortxt\">can't find file: ".$cfg_pics_url."/".$image_filename."</span></td></tr>";
		?>
		<tr> 
			<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[0]; ?>: </td> 
			<td class="resultline-alt"><input name="form_image" type="file" size="50"></td> 
		</tr> 
	<?php
	}
	?>
	
	

<?php } ?>

<?php if ((isset($_REQUEST['image_id'])) && (!empty($_REQUEST['image_id']))) { ?>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[23]; ?>:</td>
	<td class="resultline-alt">
	<?php 
	/*
	* Check to see if there is a thumbnail named, and if there is, make sure
	* that it really exists
	*/
	echo '<table border="0" cellspacing="0" cellpadding="0"><tr><td valign="top">';
	if (!empty($image_thumbname)) {
		
		if (file_exists($cfg_thumb_path."/".$image_thumbname)) {
			$thumbimg_size = @getimagesize($cfg_thumb_path."/".$image_thumbname);
			echo '<img src="'.$cfg_thumb_url."/".$image_thumbname.'?'.date("U").'" '.$thumbimg_size[3].' hspace="3"></td><td valign="top">';

		} else {
			echo "<span class=\"smerrortxt\">can't find file: ".$cfg_thumb_url."/".$image_thumbname."</span>";
		}		

	} else {
		echo "none&nbsp;&nbsp;&nbsp;&nbsp;</td><td>";
	}

	/*
	* If the fullsized is a jpg or a png, present the option of re-cropping
	*/
	if (($img_size[2]==2) || ($img_size[2]==3)) {
			echo "<b>".$LANG_IMG_FIELD[17].":</b><br>&#187; <b><a href=\"image.php?gallery_id=".$_REQUEST['gallery_id']."&image_id=".$_REQUEST['image_id']."&rethumb=1&page=".$_REQUEST['page']."\">".$LANG_IMG_FIELD[18]."</a></b>";		

		if ((isset($cfg_enable_croptool)) && ($cfg_enable_croptool==1)) {
			echo "\n\n".'<br>&#187; <b><a href="crop.php?gallery_id='.$_REQUEST['gallery_id'].'&image_id='.$_REQUEST['image_id'].'&page='.$_REQUEST['page'].'">'.$LANG_IMG_FIELD[16].'</a></b><br>';

		}
	}
	//echo "\n\n".'&#187; <b><a href="javascript:void(0);" onClick="expandcontent(\'thumbbox\')">upload thumbnail</a></b>";
	echo "</td></tr></table>";

?>

<span id="thumbbox" class="switchcontent"><input name="form_thumb" type="file" size="30"></span>

</td>
</tr>
<?php } ?>
<tr>
	<td class="resultline-alt"><?php echo $LANG_IMG_FIELD[7]; ?>:</td>
	<td class="resultline-alt"><input type="text" name="form_image_title"<?php if ((isset($image_title)) && (!empty($image_title))) { echo " value=\"".stripslashes($image_title)."\""; }?> maxlength="200" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><b><?php echo $LANG_IMG_FIELD[8]; ?>:</b> </td>
	<td class="resultline-alt">
	<?php
	if (empty($_REQUEST['image_id'])) {
		$image_cat_id = $_REQUEST['gallery_id'];
	}
	$sql ="select id, name from snipe_gallery_cat where cat_parent='0' ";	
	$sql .=" order by name asc";
	$get_options = mysql_query($sql);    
	$num_options = mysql_num_rows($get_options);   
	echo '<select name="form_gallery_id">';

	// our category is apparently valid, so go ahead...           
	if ($num_options > 0) {  

		
		while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {
			$sql ="select id, name from snipe_gallery_cat where cat_parent='".$cat_id."' ";	
			$sql .=" order by name asc";
			$get_suboptions = mysql_query($sql);  
			while (list($subcat_id, $subcat_name) = mysql_fetch_row($get_suboptions)) {
				echo "<option value=\"".$subcat_id."\"";
				if ($image_cat_id==$subcat_id) {
					echo " selected=\"selected\"";
				}

				echo ">".stripslashes($cat_name).":: ".stripslashes($subcat_name)."</option>\n";
			}

		}		           
			                           
	} else {
		echo "<span class=\"smerrortxt\">No valid categories yet - <b><a href=\"gallery.php\">add one now</a></b>.</span>";
	}
	?>

	 </select>

	
	</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[3]; ?>: </td>
	<td class="resultline-alt">
	<textarea name="form_details" rows="4" cols="50"><?php if ((isset($image_details)) && (!empty($image_details))) { echo stripslashes($image_details); }?></textarea></td>
</tr>
<tr>
	<td class="resultline-alt"><?php echo $LANG_IMG_FIELD[5]; ?>:</td>
	<td class="resultline-alt"><?php echo $monthlist; ?> <?php echo $daylist; ?> <input type="text" name="form_year"<?php if ((isset($image_year)) && (!empty($image_year))) { echo " value=\"".stripslashes($image_year)."\""; }?> maxlength="4" size="6">
	</td>
</tr>
<tr>
	<td class="resultline-alt"><?php echo $LANG_IMG_FIELD[1]; ?>:</td>
	<td class="resultline-alt"><input type="text" name="form_author"<?php if ((isset($image_author)) && (!empty($image_author))) { echo " value=\"".stripslashes($image_author)."\""; }?> maxlength="100" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt"><?php echo $LANG_IMG_FIELD[2]; ?>:</td>
	<td class="resultline-alt"><input type="text" name="form_location"<?php if ((isset($image_location)) && (!empty($image_location))) { echo " value=\"".stripslashes($image_location)."\""; }?> maxlength="200" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt"><?php echo $LANG_IMG_FIELD[4]; ?>:</td>
	<td class="resultline-alt"><input type="text" name="form_keywords"<?php if ((isset($image_keywords)) && (!empty($image_keywords))) { echo " value=\"".stripslashes($image_keywords)."\""; }?> maxlength="250" size="30"></td>
</tr>

<?php 
if (($cfg_use_iptc_meta==1) && (empty($_REQUEST['image_id']))) {
?>

<tr>
	<td class="resultline-alt" valign="top">IPTC Meta Data: </td>
	<td class="resultline-alt">
	<input type="checkbox" name="iptc_title_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC headline as title<br>
	<input type="checkbox" name="iptc_caption_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC caption as description <br>
	<input type="checkbox" name="iptc_author_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC author as photographer <br> 
	<input type="checkbox" name="iptc_loc_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC location as location <br>
	<input type="checkbox" name="iptc_keywords_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC keywords as keywords <br>
	<input type="checkbox" name="iptc_date_override" value="1"<?php if ($cfg_iptc_meta_default==1) { echo ' checked="checked"'; } ?>>Use IPTC creation date as date<br>
	<span class="smadmin">(NOTE: IPTC values will override user-entered fields if selected)</span>
</td>
</tr>
<?php } ?>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[6]; ?>?: </td>
	<td class="resultline-alt"><input type="radio" name="form_publish" value="1"<?php if ((!isset($image_publish)) || ($image_publish==1)) { echo " checked=\"checked\""; } ?>><?php echo $LANG_IMG_FIELD[24]; ?><br>
	<input type="radio" name="form_publish" value="0"<?php if ((isset($image_publish)) && ($image_publish==0))  { echo " checked=\"checked\""; } ?>><?php echo $LANG_IMG_FIELD[25]; ?><br>
</td>
</tr>


<tr>
	<td colspan="2" class="resultline" align="right"><div align="right">
	<?php 
	if ((isset($_REQUEST['image_id'])) && (!empty($_REQUEST['image_id']))) { 
		
	?>
	<input type="submit" value="Save Edits" class="formbutton" onClick="this.disabled=true; this.value='Saving...'; this.form.submit();">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="gallery_id" value="<?php echo $_REQUEST['gallery_id']; ?>">
	<input type="hidden" name="image_id" value="<?php echo $_REQUEST['image_id']; ?>">
	<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>">

	<?php	
		} else {		

			if ((isset($first_album)) && ($first_album==1)) { 
				echo '<input type="hidden" name="gallery_id" value="'.$_REQUEST['gallery_id'].'">';
			}
			if ($this_thumbtype==2) {
				echo '<input type="submit" value="'.$LANG_IMG_FIELD[21] .'&#187;" class="formbutton" onClick="this.disabled=true; this.value=\''.$LANG_IMG_FIELD[13].'...\'; this.form.submit();">';
			} else {
				echo '<input type="submit" value="Save New" class="formbutton" onClick="this.disabled=true; this.value=\''.$LANG_IMG_FIELD[13].'...\'; this.form.submit();">';
			}		
		?>
		
		<input type="hidden" name="action" value="new">		
		<?php 
		
	}
	?>
	
	</div></td>
</tr>

</table>
</center>
<input type="hidden" name="MAX_FILE_SIZE" value="300000">
</form>