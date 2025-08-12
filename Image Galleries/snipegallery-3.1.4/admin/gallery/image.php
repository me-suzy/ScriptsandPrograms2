<?php 
/**
* image.php.php
*
* Displays the image form and performs the add or update for images
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

$GALLERY_SECTION = "image";
$PAGE_TITLE = "Images";

include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ($cfg_admin_path."/lib/dropdown.functions.php");


/**
* Get the category details
*/
if ((!empty($_REQUEST['gallery_id'])) || (!empty($_REQUEST['form_gallery_id']))){
	if (!empty($_REQUEST['form_gallery_id'])) {
		$get_gall_id = $_REQUEST['form_gallery_id'];
	} else {
		$get_gall_id = $_REQUEST['gallery_id'];
	}

	$sql = "select name, cat_parent, default_thumbtype, watermark_txt from snipe_gallery_cat ";
	$sql .="where id='".$get_gall_id."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_cat_parent, $this_thumbtype, $this_watermark_txt) = mysql_fetch_row($get_catname);		
		}
	}
}

/**
* If this is a new image, we need to find out what whether or not is valid,
* what the file type is, and whether or not the category type require watermarking
*/


		/**
		* make sure an image was actually uploaded
		*/
		if (!empty($_FILES['form_image']['tmp_name']) && ($_FILES['form_image']['tmp_name']!= 'none'))  {
			$uploaded_img_size = getimagesize($_FILES['form_image']['tmp_name'], $info);

			/**
			* Make sure IPTC is enabled - and if it is, get the data from the uploaded file
			*/

			if ($cfg_use_iptc_meta==1) {
			$iptc = iptcparse($info["APP13"]);
				if (is_array($iptc)) { 

					if ($_POST['iptc_title_override']==1) {
						$form_image_title = $iptc["2#105"][0];
					}

					if ($_POST['iptc_caption_override']==1) {
						$form_details = $iptc["2#120"][0];
					}

					if ($_POST['iptc_author_override']==1) {
						$form_author = $iptc["2#080"][0];
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
					
					}


					if ($_POST['iptc_keywords_override']==1) {
						$iptc_keywords = $iptc["2#025"][0];

						$c = count ($iptc["2#025"]);
						if ($c > 0) {
							$form_keywords = "";
								for ($i=0; $i <$c; $i++)  {
									$form_keywords .= $iptc["2#025"][$i].' ';
								}
						}
					}

					if ($_POST['iptc_date_override']==1) {
						$iptc_showdate = strtotime($iptc["2#055"][0]);
						$date = date("Y-m-d", $iptc_showdate)."<br>";
					}
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
				$date = $_POST['form_year']."-".$_POST['form_month']."".$_POST['form_day'];
			}


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

			/*
			* if the image isn't valid, return an error and stop the process
			*/
			if ($img_filetype_err ==1) {
				$err_message =  "<h3>".$LANG_ERR_INVALID_FILETYPE_HEAD."</h3>\n\n<p>".$LANG_ERR_INVALID_FILETYPE_TXT."</p>";

			/* 
			* otherwise, go ahead...
			*/
			} else {

				if (!empty($_REQUEST['image_id'])) {
					$sql = "select filename,  thumbname  from snipe_gallery_data ";
					$sql .="where id='".$_REQUEST['image_id']."' ";

				} else {
					
					$sql = "insert into snipe_gallery_data (img_date, title, details, author, location, cat_id, keywords, publish, added) values (";
					$sql .="'".$date."', '".trim(addslashes($form_image_title))."', '".trim(addslashes($form_details))."', '".trim(addslashes($form_author))."', '".trim(addslashes($form_location))."', '".$_POST['form_gallery_id']."', '".trim(addslashes($form_keywords))."', '".$_POST['form_publish']."',  NOW())";
				}
			
				

				if ($add_image = mysql_query($sql)) {
					if (empty($_REQUEST['image_id'])) {
						$this_image_id = mysql_insert_id();
						$image_id = $this_image_id;

						/*
						* check to see if we should keep the original filename
						*/
						if ($cfg_orig_filenames==1) {
							$image_filename = $_FILES['form_image']['name'];
						} else {
							$image_filename = $this_image_id."_".date("U").$img_ext;
						}
					} else {
						list($edit_image_filename,  $edit_image_thumbname) = mysql_fetch_row($add_image);
						$image_filename = $edit_image_filename;
						$this_image_id = $_REQUEST['image_id'];
					}
	
					/*
					* move the uploaded file to where it belongs
					*/
					if (move_uploaded_file($_FILES['form_image']['tmp_name'], $cfg_pics_path."/".$image_filename)) {										

						$img_width = $uploaded_img_size[0];
						$img_height= $uploaded_img_size[1];
						
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
						
						/* 
						* if we have to cache the image, make a copy of it in the cache
						* directory specified in the config file
						*/
						if (($cfg_use_cache==1) && (file_exists($cfg_cache_path)) && (is_writable($cfg_cache_path))) {
							copy($cfg_pics_path."/".$image_filename, $cfg_cache_path."/".$image_filename);
							
						} 	
						
						$sql = "update snipe_gallery_data set filename='".$image_filename."' where id='".$this_image_id."'";
						$update_img = mysql_query($sql);						

						if ((isset($this_thumbtype)) && (!empty($this_thumbtype)) && ($this_thumbtype==2) && (($uploaded_img_size[2]==2) || ($uploaded_img_size[2]==3)))  {
							$print_message = "<h3>".$LANG_ADMIN_ADD_IMAGE.": ".$LANG_SEL_THUMB."</h3>";
							$show_crop_js = 1;
							if (!empty($this_watermark_txt)) {
								/**     
								* position of watermark text on image
								* 0 = top 
								* 1 = bottom 
								* 2 = middle left
								*/
								
								if ($cfg_font_pos == 0) {
									$h_pos = $cfg_font_h_padding;
									$v_pos = $cfg_font_v_padding;
								} elseif ($cfg_font_pos == 1) {
									$h_pos = $cfg_font_h_padding;
									$v_pos = round($uploaded_img_size[1] - $cfg_font_v_padding);
								} elseif ($cfg_font_pos == 2) {
									$h_pos = $cfg_font_h_padding;
									$v_pos = round($uploaded_img_size[1]/2);
								} else {
									$h_pos = $cfg_font_h_padding;
									$v_pos = $cfg_font_v_padding;
								}

								/*
								* If the cache option is turned on, create a copy of the 
								*/
								
								if ($cfg_use_cache==1) {
									$use_filename = $cfg_cache_path."/".$image_filename;
								} else {
									$use_filename = $cfg_pics_path."/".$image_filename;
								}

								if (!$image = imagecreatetruecolor($uploaded_img_size[0], $uploaded_img_size[1])) {										
									$image = imagecreate($uploaded_img_size[0], $uploaded_img_size[1]);
								}

								if ($uploaded_img_size[2]==2) {
									$image = imagecreatefromjpeg($use_filename);
								} elseif ($uploaded_img_size[2]==3) {
									$image = imagecreatefrompng($use_filename);	
								} 

								$color = imagecolorallocate($image, 255,255,255);
								$black = imagecolorallocate($image, 0,0,0);	

								ImageTTFText ($image, $cfg_font_size, 0, ($h_pos+2), ($v_pos+2), $black, $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

								/*
								* Now add the colored text "on top"
								*/
								ImageTTFText ($image, $cfg_font_size, 0, $h_pos, $v_pos, $color,  $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

								if ($uploaded_img_size[2]==2) {
									imagejpeg($image,$use_filename);
								} elseif ($uploaded_img_size[2]==3) {	
									imagepng($image,$use_filename);
									
								} 
								
							imagedestroy($image); 

							}
							header("Location: ".$cfg_admin_url."/gallery/crop.php?gallery_id=".$_REQUEST['form_gallery_id']."&image_id=".$this_image_id."&page=".$_REQUEST['page']);

						/*
						* if the gallery type specifies automatic thumbnailing
						*/
						} elseif ((isset($this_thumbtype)) && (!empty($this_thumbtype)) && ($this_thumbtype!=2) && (($uploaded_img_size[2]==2) || ($uploaded_img_size[2]==3)))  {


							$uploaded_img_size = getimagesize($cfg_pics_path."/".$image_filename);			
							$img_width = $uploaded_img_size[0];
							$img_height= $uploaded_img_size[1];
							$new_w = $cfg_thumb_width;
							$ratio = ($img_width / $new_w); 
							$new_h = round($img_height / $ratio);
							/*
							* if dynamic thumbnailing
							*/
							
							if ($uploaded_img_size[2]==2) {
								$src_img = imagecreatefromjpeg($cfg_pics_path."/".$image_filename);
							} elseif ($uploaded_img_size[2]==3) {
								$src_img = imagecreatefrompng($cfg_pics_path."/".$image_filename);
							}
							if (gd_version() >= 2) {
								$dst_img = imagecreatetruecolor($new_w,$new_h);								imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$img_width,$img_height);
							} else {
								$dst_img = imagecreate($new_w,$new_h);								imagecopyresized($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$img_width,$img_height);
							}
							

							$sql = "update snipe_gallery_data set thumbname='".$image_filename."' where id='".$this_image_id."'";
							if ($update_img = mysql_query($sql)) {	

								if ($uploaded_img_size[2]==2) {
									imagejpeg($dst_img, $cfg_thumb_path."/".$image_filename);
								} elseif ($uploaded_img_size[2]==3) {
									imagepng($dst_img, $cfg_thumb_path."/".$image_filename);
								}
								imagedestroy($dst_img);
							
								header("Location: ".$cfg_admin_url."/gallery/view.php?gallery_id=".$_REQUEST['form_gallery_id']."&image_id=".$this_image_id."&page=".$_REQUEST['page']); 
							} else {
								$thumb_db_error= "SQL: $sql <br>Mysql Said: ".mysql_error();
							}
						} else {							
							header("Location: ".$cfg_admin_url."/gallery/view.php?gallery_id=".$_REQUEST['form_gallery_id']."&image_id=".$this_image_id."&page=".$_REQUEST['page']."&bar"); 
						}
						
					} else{
						$err_message =   '<h3>'.$LANG_ERR_IMAGE_HEAD.'</h3><span class="errortxt">'.$LANG_ERR_IMAGE_TXT.'</span>';
					}

						
					

				 } 	else {
					$err_message =   "<h3>".$LANG_ERR_IMAGE_HEAD."</h3><span class=\"errortxt\">".$LANG_ERR_DB_ERROR."</span>";
					if ($cfg_debug_on==1) {
						$err_message .=  "<p><b>mySQL said: </b>";
						$err_message .= mysql_error();
						$err_message .= "</p>\n\n<p><b>SQL query:</b> $sql </p>";

					}
				 }
			}


		} else {
			$err_message = "<h3>".$LANG_ERR_NOIMAGE_HEAD."</h3>";
			$err_message .="<p>".$LANG_ERR_NOIMAGE_TXT."</p>";

		}
	
 


include ("../layout/admin.header.php");

if (!empty($thumb_db_error)) {
	echo "<p>ERROR: ".$thumb_db_error."</p>";
}

if (!empty($_REQUEST['image_id'])) {

	if ($_GET['rethumb']==1) {
		$sql = "select filename,  thumbname from snipe_gallery_data ";
		$sql .="where id='".$_REQUEST['image_id']."' ";
		$getthumbinfo = mysql_query($sql);

				list($image_filename, $image_thumbname) = mysql_fetch_row($getthumbinfo);
				
				$temp_size = getimagesize($cfg_pics_path."/".$image_filename);
					
					if (($cfg_use_cache==1) && (file_exists($cfg_cache_path."/".$image_filename))) {						
						if ($temp_size[2]==2) {
							$src_img = imagecreatefromjpeg($cfg_cache_path."/".$image_filename); 
						} else {
							$src_img = imagecreatefrompng($cfg_cache_path."/".$image_filename); 
						}
					} else {						
						if ($temp_size[2]==2) {
							$src_img = imagecreatefromjpeg($cfg_pics_path."/".$image_filename); 
						} else {
							$src_img = imagecreatefrompng($cfg_pics_path."/".$image_filename); 
						}
						
					}

					if (file_exists($cfg_pics_path."/".$image_filename)) {
						
						
						$img_width = $temp_size[0];
						$img_height = $temp_size[1];
						$new_w = $cfg_thumb_width;
						$ratio = ($img_width / $new_w); 
						$new_h = round($img_height / $ratio);
						
						$dst_img = imagecreatetruecolor($new_w,$new_h);									
						imagecopyresampled($dst_img,$src_img,0,0,0,0,$new_w,$new_h,$img_width,$img_height);	

						if ($temp_size[2]==2) {
							imagejpeg($dst_img, $cfg_thumb_path."/".$image_filename);
						} elseif ($temp_size[2]==3) {
							imagepng($dst_img, $cfg_thumb_path."/".$image_filename);
						}	
						$sql = "update snipe_gallery_data set thumbname='".$image_filename."' where id='".$_REQUEST['image_id']."'";
						$update_img = mysql_query($sql);	
						
					}
					
				} 


	if ($_POST['action']!="save") {
		$sql = "select filename,  thumbname,  img_date,  title,  ";
		$sql .="details,  author,  location,  cat_id,  keywords,  ";
		$sql .="publish,  added  from snipe_gallery_data ";
		$sql .="where id='".$_REQUEST['image_id']."' ";

		if ($show_image=mysql_query($sql)) {
			$valid_image = mysql_num_rows($show_image);
			if ($valid_image > 0) {
				
				echo "<h3>".stripslashes($this_catname)." - View Image Details</h3>";
				list($image_filename,  $image_thumbname,  $image_date,  $image_title,  $image_details,  $image_author,  $image_location,  $image_cat_id,  $image_keywords,  $image_publish,   $image_added) = mysql_fetch_row($show_image);
				$temp_size = @getimagesize($cfg_pics_path."/".$image_filename);
				
				list($image_year, $image_month, $image_day) = split("-",$image_date);
				$monthlist= MakeMonthDropMenu("form_month",$image_month);

				/* 
				* rotate the image if requested
				*/

				if (function_exists('imagerotate')) {
					if (($_REQUEST['rotate']=="left") || ($_REQUEST['rotate']=="right")) {

						$rotate_img = turn_img($image_filename, "right", 0);
						if ($cfg_use_cache==1) {
							$rotate_img_cache = turn_img($image_filename, "right", 1);
						}	
					}
				}

				
				$daylist= MakeDayDropMenu("form_day",$image_day);
				if ($image_year=="0000") {
					$image_year="";
				}
				include ($cfg_admin_path."/lib/forms/image_form.php");

			} else {
				echo "<h3>".stripslashes($this_catname)." - ".$LANG_ERR_INVALID_IMAGEID_HEAD."</h3><p class=\"errortxt\">".$LANG_ERR_INVALID_IMAGEID_TXT."</p>";
			}

			

		} else {
			echo "<h3>".stripslashes($this_catname)." - ".$LANG_ERR_ERROR."</h3><span class=\"errortxt\">".$LANG_ERR_DB_ERROR."</span>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		}

	} else {
		$date = $_POST['form_year']."-".$_POST['form_month']."".$_POST['form_day'];
		$sql = "update snipe_gallery_data set img_date='".$date."',  ";
		$sql .="title='".addslashes($_POST['form_image_title'])."',  ";
		$sql .="details='".addslashes($_POST['form_details'])."', ";
		$sql .="author='".addslashes($_POST['form_author'])."', ";
		$sql .="location='".addslashes($_POST['form_location'])."', ";
		$sql .="cat_id='".$_POST['form_gallery_id']."', ";
		$sql .=" keywords='".addslashes($_POST['form_keywords'])."', ";		
		$sql .="publish='".$_POST['form_publish']."' ";		
		$sql .="where id='".$_REQUEST['image_id']."' ";

		if ($show_image=mysql_query($sql)) {
			
		echo "<h3>".stripslashes($this_catname)." - ".$LANG_ADMIN_IMAGEEDIT_SAVED_HEAD."</h3>\n\n";		
		echo '<p>'.$LANG_ADMIN_IMAGEEDIT_SAVED_TXT.'</p>';
		} 	else {
			echo "<h3>".stripslashes($this_catname)." - ".$LANG_ERR_IMAGEEDIT_HEAD."</h3><span class=\"errortxt\">".$LANG_ERR_DB_ERROR."</span>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		 }
		
	}
	

	

} else {

	if (($this_cat_parent==0) && ($valid_cat > 0)) {
		echo "<h3>".$LANG_ADMIN_ADD_IMAGE."</h3>\n ";
		echo '<p>'.$LANG_ERR_IMG_TOPLVL.'</p>';


	} else {
		if ($valid_cat > 0) {	

		if ($_POST['action']=="new") {				
			if (!empty($err_message)) {			
				echo $err_message;					
			}
		} else {

			echo "<h3>Add New Image in &quot;".stripslashes($this_catname)."&quot;</h3>";
			echo "<p>Use the form below to add a new image to this gallery. ";
				if (($cfg_use_cache==1) && (!empty($this_watermark_txt))) {
					if (($cfg_use_cache==1) && (!empty($this_watermark_txt)) && (file_exists($cfg_cache_path)) && (is_writable($cfg_cache_path))) {
						echo " This gallery has watermarking enabled, so a copy of the original image will also be stored in the directory you have specified in the config.php file.";
					} else {
						echo " This gallery has watermarking enabled, however a problem has been detected in the cache directory, so an additional copy will not be stored.  Please check the settings for your cache directory specified in the config.php file.";
					}
				}
		
			echo "</p>";
			$monthlist= MakeMonthDropMenu("form_month","");
			$daylist= MakeDayDropMenu("form_day","");
			include ($cfg_admin_path."/lib/forms/image_form.php");
			
			
		}
		} else {
			echo "<h3>Invalid Gallery</h3>";
			echo "<p>This category is not valid.  Perhaps it has been deleted?  Please select a valid gallery from the <b><a href=\"index.php\">gallery listings page</a></b>.</p>";
		}
	}
}


include ("../layout/admin.footer.php"); ?>	
