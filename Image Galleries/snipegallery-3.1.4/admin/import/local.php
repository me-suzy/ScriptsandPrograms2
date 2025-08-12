<?php 
/**
* Import Local File
*
* Allows importing from specific upload directory
*     
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.1.1
* @since 3.1.1
*/



$GALLERY_SECTION = "import";
$PAGE_TITLE = "Import Local Files";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");


include ("../layout/admin.header.php");  

// get the category details
if (!empty($_REQUEST['gallery_id'])) {
$sql = "select name, frame_style, watermark_txt, display_orderby, display_order  from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_frame_style, $this_watermark_txt, $this_display_orderby, $this_display_order) = mysql_fetch_row($get_catname);		
		}
	}

}

if ($_POST['action']!="upload") {
?>
<h3><?php echo $PAGE_TITLE ; ?></h3>


<?php 
if (($cfg_use_cache==1) && (!file_exists($cfg_cache_path))){
			echo '<p class="errortxt">ERROR: The cache is enabled in your config.php file, but that directory ('.$cfg_cache_path.') does not appear to exist.</p>';
} elseif (($cfg_use_cache==1) && (file_exists($cfg_cache_path)) && (!is_writable($cfg_cache_path))){
			echo '<p class="errortxt">ERROR: The cache is enabled in your config.php file, and the directory ('.$cfg_cache_path.') exists, but it does not appear to be writable. Please check the permissions on this directory before proceeding.</p>';
} elseif (($cfg_use_cache==1) && (file_exists($cfg_cache_path)) && (!is_writable($cfg_cache_path))){
			echo '<p class="errortxt">ERROR: The cache is enabled in your config.php file, and the directory ('.$cfg_cache_path.') exists, but it does not appear to be writable. Please check the permissions on this directory before proceeding.</p>';
} elseif (!file_exists($cfg_local_import_dir)){
			echo '<p class="errortxt">ERROR: The cache is enabled in your config.php file, and the directory ('.$cfg_local_import_dir.') exists, but it does not appear to be writable. Please check the permissions on this directory before proceeding.</p>';


} else {
	if (file_exists($cfg_local_import_dir)){
		$dir = opendir($cfg_local_import_dir);
		$filecount = 0;
		while ($file = readdir($dir)) {
		 if ($file == '.' || $file == '..') continue;
		 $filedata = explode(".", $file);
		 $filpartcount = count($filedata);
		 $ext_key = ($filpartcount - 1);
		 if (($filedata[$ext_key]=="gif") || ($filedata[$ext_key]=="jpg") || ($filedata[$ext_key]=="jpeg") || ($filedata[$ext_key]=="png") || ($filedata[$ext_key]=="GIF")  || ($filedata[$ext_key]=="JPG")  || ($filedata[$ext_key]=="JPEG")  || ($filedata[$ext_key]=="PNG") ){
			 $filecount++;
		}
		}

	}

	if ($filecount < 1) {
		echo '<p class="errortxt">ERROR: The local import directory ('.$cfg_local_import_dir.') does not contain any valid image files.</p>';

	} else {

?>

<p>The local import tool allows you to import images that have been FTPed to the webserver.  (This can be helpful in cases where there are large numbers of images to be imported.  The information in the additional fields will be applied to all images.  All additional fields are optional.</p>
<form method="post" action="local.php">
<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
<tr>
	<td colspan="2" class="resultline"><b>Local File Import Settings</b></td>
</tr>

<tr>
	<td class="resultline-alt" valign="top"><b><?php echo $LANG_ADMIN_IMPORT_2; ?>:</b> </td>
	<td class="resultline-alt">
	
	<?php
	
		$sql ="select id, name from snipe_gallery_cat where cat_parent='0' order by name asc";
		$get_options = mysql_query($sql);    
		$num_options = mysql_num_rows($get_options);   
		echo '<select name="gallery_id">';

		// our category is apparently valid, so go ahead...           
		if ($num_options > 0) {  			
			while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) { 

				$sql ="select id, name from snipe_gallery_cat where cat_parent='".$cat_id."' order by name asc";
				$get_suboptions = mysql_query($sql);    
				$num_suboptions = mysql_num_rows($get_suboptions);
				if ($num_suboptions > 0) {
					while (list($subcat_id, $subcat_name) = mysql_fetch_row($get_suboptions)) { 
				
					echo "<option value=\"".$subcat_id."\">".stripslashes($cat_name).":: ".stripslashes($subcat_name)."</option>\n";
					}
				}

			}
			echo ' </select>';
						   
										   
		}
	?>	
	</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Import Directory: </td>
	<td class="resultline-alt"><?php echo $cfg_local_import_dir; ?> (<?php echo $filecount; ?> files)</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[1]; ?>: </td>
	<td class="resultline-alt"><input type="text" name="form_author" maxlength="100" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[2]; ?>: </td>
	<td class="resultline-alt"><input type="text" name="form_location" maxlength="255" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[3]; ?>: </td>
	<td class="resultline-alt"><textarea name="form_details" rows="4" cols="50"></textarea></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><?php echo $LANG_IMG_FIELD[4]; ?>: </td>
	<td class="resultline-alt"><input type="text" name="form_keywords" maxlength="255" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top" colspan="2">
	<input type="checkbox" name="make_thumbs" value="1" checked="checked"><?php echo $LANG_IMG_FIELD[10]; ?>?
	</td>
</tr>

<tr>
	<td class="resultline-alt" valign="top" colspan="2">
	<input type="checkbox" name="keep_filedate" value="1"><?php echo $LANG_IMG_FIELD[11]; ?>
	</td>
</tr>

<?php 
if ($cfg_use_iptc_meta==1) {
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
	<td class="resultline-alt" valign="top" colspan="2">
	<input type="checkbox" name="form_publish" value="1" value="1" checked="checked">Publish?
	</td>
</tr>

<tr>
	<td colspan="2" class="resultline" align="right"><div align="right">
	<input type="submit" value="<?php echo $LANG_IMG_FIELD[12]; ?>" class="formbutton" onClick="this.disabled=true; this.value='<?php echo $LANG_IMG_FIELD[13]; ?>...'; this.form.submit();">	
	<input type="hidden" name="action" value="upload">
	
	</div></td>
</tr>
</table>
</center>
</form>

<?php
}
}
} elseif ($_POST['action']=="upload") {

		$dir = opendir($cfg_local_import_dir);
			while ($file = readdir($dir)) {
				if ($file == '.' || $file == '..') continue;
				$time = filemtime($cfg_local_import_dir."/".$file);		
				$total_files++;
				if ($uploaded_img_size = getimagesize($cfg_local_import_dir."/".$file, $info)) {
					$img_width = $uploaded_img_size[0];
					$img_height= $uploaded_img_size[1];
					

					/**
					* Find out the file type
					*/				
					if ($uploaded_img_size[2]==1) {
						$img_ext = ".gif";
						$img_filetype_err = 0;						
						
					} elseif ($uploaded_img_size[2]==2) {
						  $img_ext = ".jpg";
						  $img_filetype_err = 0;

					} elseif ($uploaded_img_size[2]==3) {
						  $img_ext = ".png";
						  $img_filetype_err = 0;

					} elseif ($uploaded_img_size[2]==4) {
						  $img_ext = ".swf";
						  $img_filetype_err = 0;

					} else {
						$img_filetype_err = 1;		
						
					}							

					
					

					/**
					* Make sure IPTC is enabled - and if it is, get the data from the uploaded file
					*/

					if ($cfg_use_iptc_meta==1) {
					$iptc = iptcparse($info["APP13"]);
						if (is_array($iptc)) { 
							$form_location='';

							if ($_POST['iptc_title_override']==1) {
								$form_image_title = $iptc["2#105"][0];
							} else {
								$form_image_title = $_POST['form_image_title'];
							}

							if ($_POST['iptc_caption_override']==1) {
								$form_details = $iptc["2#120"][0];
							} else {
								$form_details = $_POST['form_details'];
							}

							if ($_POST['iptc_author_override']==1) {
								$form_author = $iptc["2#080"][0];
							} else {
								$form_author = $_POST['form_author'];
							}

							if ($_POST['iptc_loc_override']==1) {
								if (!empty($iptc["2#090"][0])) {
									$form_location .=  $iptc["2#090"][0];
									if (!empty($iptc["2#095"][0])) {
										$form_location .=", ";
									}
								}
								if (!empty($iptc["2#095"][0])) {
									$form_location .= $iptc["2#095"][0]." ";
								}
								if (!empty($iptc_country)) {
									$form_location .= $iptc["2#101"][0];
								}
							
							} else {
								$form_location = $_POST['form_location'];
							}


							if ($_POST['iptc_keywords_override']==1) {
								$iptc_keywords = $iptc["2#025"][0];

								$c = count ($iptc["2#025"]);
								if ($c > 0) {
									$form_keywords = "";
										for ($i=0; $i <$c; $i++)  {
											$form_keywords .= $iptc["2#025"][$i].' ';
										}
								} else {
									$form_keywords = $_POST['form_keywords'];
								}
							}

							if ($_POST['iptc_date_override']==1) {
								$iptc_showdate = strtotime($iptc["2#055"][0]);
								$img_time = date("Y-m-d", $iptc_showdate);
							}
						} else {
							$form_image_title = $_POST['form_image_title'];
							$form_author = $_POST['form_author'];
							$form_location = $_POST['form_location'];
							$form_keywords = $_POST['form_keywords'];
							$form_details = $_POST['form_details'];
						}


					/**
					* If IPTC isn't enabled, assign the form fields to the variable names
					*/
					} else {
						$form_image_title = $_POST['form_image_title'];
						$form_author = $_POST['form_author'];
						$form_location = $_POST['form_location'];
						$form_keywords = $_POST['form_keywords'];
						$form_details = $_POST['form_details'];
						
						if ($_POST['keep_filedate']==1) {
							$img_time = date("Y-m-d",$time);
						} else {
							$img_time = "";
						}
					}

						$sql = "insert into snipe_gallery_data (img_date, title, details, author, location, cat_id, keywords, publish, added) values (";
						$sql .="'".$img_time."', '".trim(addslashes($form_image_title))."', '".trim(addslashes($form_details))."', '".trim(addslashes($form_author))."', '".trim(addslashes($form_location))."', '".$_POST['gallery_id']."', '".trim(addslashes($form_keywords))."', '".$_POST['form_publish']."',  NOW())";
						
						
						if ($add_image = mysql_query($sql)) {
							$image_count++;
							$this_image_id = mysql_insert_id();
							$image_id = $this_image_id;
							/*
							* check to see if we should keep the original filename
							*/
							if ($cfg_orig_filenames==1) {
								$image_filename = $file;
							} else {
								$image_filename = $this_image_id."_".date("U").$img_ext;
							}
							
							$sql = "update snipe_gallery_data set filename='".$image_filename."' where id='".$this_image_id."'";
							$update_img = mysql_query($sql);		
							copy($cfg_local_import_dir."/".$file, $cfg_pics_path."/".$image_filename);

							/*
							*	If caching is enabled, copy the new file into the cache
							*/
							if (($cfg_use_cache==1) && (is_writable($cfg_cache_path))) {
								copy($cfg_local_import_dir."/".$file, $cfg_cache_path."/".$image_filename);
							}

								/*
								* if this image can be resized, figure out what we need to do
								*/
								if (($uploaded_img_size[2]==2) || ($uploaded_img_size[2]==3)) {

									/* 
									* if this is a landscape style image
									*/
									if ($img_width > $img_height) {

										/*
										* if there is a ceiling on the max upload width, resize the image down
										* to the specified size
										*/
										if (($cfg_use_fullsize_ceil==1) && ($img_width > $cfg_max_fullsize_width)) {
											$new_fw = $cfg_max_fullsize_width;
											$fratio = ($img_width / $new_fw); 
											$new_fh = round($img_height / $fratio);	
											$continue_resize = 1;
										}

									} else {
										if (($cfg_use_fullsize_hceil==1) && ($img_height > $cfg_max_fullsize_height)) {

											/*
											* if there is a ceiling on the max upload height, resize the image down
											* to the specified size
											*/
											if (($cfg_use_fullsize_hceil==1) && ($img_height > $cfg_max_fullsize_height)) {
												$new_fh = $cfg_max_fullsize_height;										
												$fratio = ($img_height / $new_fh); 
												$new_fw = round($img_width / $fratio);	
												$continue_resize = 1;
											}

										}

									}								
										

										if ($continue_resize == 1) {
											$fsrc_img = imagecreatefromjpeg($cfg_pics_path."/".$image_filename); 
											if (gd_version() >= 2) {
												$fdst_img = imagecreatetruecolor($new_fw,$new_fh);							imagecopyresampled($fdst_img,$fsrc_img,0,0,0,0,$new_fw,$new_fh,$img_width,$img_height);
											} else {
												$fdst_img = imagecreate($new_fw,$new_fh);								imagecopyresized($fdst_img,$fsrc_img,0,0,0,0,$new_fw,$new_fh,$img_width,$img_height);
											}
											
											
											if ($uploaded_img_size[2]==2) {
												imagejpeg($fdst_img, $cfg_pics_path."/".$image_filename);
											} elseif ($uploaded_img_size[2]==3) {
												imagepng($fdst_img, $cfg_pics_path."/".$image_filename);
											}
											imagedestroy($fdst_img);
										}
		 
								}

							if ((($uploaded_img_size[2]==2) || ($uploaded_img_size[2]==3)) && ($image_count <= $cfg_max_import_munge) && ($_POST['make_thumbs']==1))  {
									$uploaded_img_size = getimagesize($cfg_pics_path."/".$image_filename);
									$img_width = $uploaded_img_size[0];
									$img_height= $uploaded_img_size[1];

									
					
									$new_w = $cfg_thumb_width;
									$ratio = ($img_width / $new_w); 
									$new_h = round($img_height / $ratio);
									/*
									* if dynamic thumbnailing
									*/
									$src_img = imagecreatefromjpeg($cfg_pics_path."/".$image_filename); 
									$dst_img = imagecreatetruecolor($new_w,$new_h);									
									imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$img_width, $img_height);

									$sql = "update snipe_gallery_data set thumbname='".$image_filename."' where id='".$this_image_id."'";
									$update_img = mysql_query($sql);	

									if ($uploaded_img_size[2]==2) {
										imagejpeg($dst_img, $cfg_thumb_path."/".$image_filename);
									} elseif ($uploaded_img_size[2]==3) {
										imagepng($dst_img, $cfg_thumb_path."/".$image_filename);
									}
							}

							/* 
							* if we have to cache the image, make a copy of it in the cache
							* directory specified in the config file
							*/
							if (($cfg_use_cache==1) && (!empty($this_watermark_txt)) && (file_exists($cfg_cache_path)) && (is_writable($cfg_cache_path))) {
								copy($cfg_pics_path."/".$image_filename, $cfg_cache_path."/".$image_filename);
								
							} 		
							unlink($cfg_local_import_dir."/".$file);

						}
						
						$filelist .= "<li>".$file;
						$numfiles++;
					}	
				}

				if ($numfiles < 1) {							 						
								
					echo "<h3>No valid images found</h3>";
					echo '<p>ERROR - no valid gif, jpg or png images detected.</p>';
				} else {
					echo "<h3>Files Imported</h3>";
					echo "<p>Success - ".$numfiles." images were imported.</p>";
					echo $filelist;
					echo "<br><br><b><a href=\"../gallery/view.php?gallery_id=".$_POST['gallery_id']."\">".$LANG_SUBNAV_VIEW_IMAGES."</a></b><br><br><b>More Info:</b><br>";
				
				
				}
				

			
		}



include ("../layout/admin.footer.php");   ?>	