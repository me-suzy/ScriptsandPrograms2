<?php 
/**
* Photo Frame Admin
*
* This file allows the admin to add, edit and delete
* the photo frames associated with each gallery.
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
$GALLERY_SECTION = "frames";
$PAGE_TITLE = "Photo Frames";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ("../layout/admin.header.php");  



if ((isset($_POST['action'])) && ($_POST['action']=="new")) {
	if ((isset($_POST['form_frame_name'])) && (!empty($_POST['form_frame_name']))) {
		
		/*
		* We make the insert into the database, so that we have 
		* an ID# to work from....
		*/
		$img_upload_err = 0;
		$img_filetype_err_names = "";
		$sql = "insert into snipe_gallery_frames (frame_name) values ('".trim(addslashes($_POST['form_frame_name']))."')";

		if ($add_frame = mysql_query($sql)) {
			$this_frame_id = mysql_insert_id();

			if (count($_FILES['form_frame_img']['name']) > 0) { 
			  // loop through the array
			  for ($i=0;$i<count($_FILES['form_frame_img']['name']);$i++) { 

				

				 if (!empty($_FILES['form_frame_img']['tmp_name'][$i]) && ($_FILES['form_frame_img']['tmp_name'][$i] != 'none'))  {

				$uploaded_img_size = getimagesize($_FILES['form_frame_img']['tmp_name'][$i]);
				
					if ($uploaded_img_size[2]==1) {
						$img_ext = ".gif";
						$img_filetype_err = 0;
						
						
					 } elseif ($uploaded_img_size[2]==2) {
						  $img_ext = ".jpg";
						  $img_filetype_err = 0;

					 } elseif ($uploaded_img_size[2]==3) {
						  $img_ext = ".png";
						  $img_filetype_err = 0;

					} else {
						$img_filetype_err = 1;
						$img_filetype_err_names .= "<li>".$_FILES['form_frame_img']['name'][$i];
					}
				
				
				if ($i < 8) {
					$new_imagename[$i] = $this_frame_id."_".$i."-th".$img_ext;
				} else {
					$new_imagename[$i] = $this_frame_id."_".$i."-full".$img_ext;
				}
					
					
				if (!move_uploaded_file($_FILES['form_frame_img']['tmp_name'][$i], $cfg_frames_path."/".$new_imagename[$i])) {
					$img_upload_err++;

				 } 		
					

				}	else {
					$empty_files++;
				}

			  } // end "for" loop

			  if (($image_upload_err < 1) && ($img_filetype_err < 1) && ($empty_files < 16)) {
					  $sql = "update snipe_gallery_frames set  ";
					  $sql .="top_left_sm='".$new_imagename[0]."', ";
					  $sql .="top_bg_sm='".$new_imagename[1]."',  ";
					  $sql .="top_right_sm='".$new_imagename[2]."', ";
					  $sql .="left_bg_sm='".$new_imagename[3]."',  ";
					  $sql .="right_bg_sm='".$new_imagename[4]."', ";
					  $sql .="bottom_left_sm='".$new_imagename[5]."', ";
					  $sql .="bottom_bg_sm='".$new_imagename[6]."',  ";
					  $sql .="bottom_right_sm='".$new_imagename[7]."',  ";
					  $sql .="top_left='".$new_imagename[8]."', ";
					  $sql .="top_bg='".$new_imagename[9]."',  ";
					  $sql .="top_right='".$new_imagename[10]."', ";
					  $sql .="left_bg='".$new_imagename[11]."',  ";
					  $sql .="right_bg='".$new_imagename[12]."', ";
					  $sql .="bottom_left='".$new_imagename[13]."', ";
					  $sql .="bottom_bg='".$new_imagename[14]."',  ";
					  $sql .="bottom_right='".$new_imagename[15]."'  ";
					  $sql .="where frame_id='".$this_frame_id."' ";
					  
					  if ($add_frame = mysql_query($sql)) {
						echo "<h3>New Photo Frame Set Saved</h3>\n";
						echo "<p>Your new frame set has been saved and can be seen below.</p> ";
						?>

						<center>
						<table>
						<tr>
							<td valign="top">
								<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[0]; ?>" border="0"></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[1]; ?>"><img src="<?php echo $cfg_admin_url; ?>/images/spacer.gif" width="1" height="1" border="0" alt=""></td>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[2]; ?>" border="0" alt=""></td>
								</tr>
								<tr>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[3]; ?>"></td>
									<td><img src="<?php echo $cfg_admin_url; ?>/images/spacer.gif" width="100" height="100" border="0" alt=""></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[4]; ?>"></td>
								</tr>
								<tr>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[5]; ?>" border="0" alt=""></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[6]; ?>"></td>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[7]; ?>" border="0" alt=""></td>
								</tr>
								</table>
							</td>
							<td valign="top">
							
								<table border="0" cellspacing="0" cellpadding="0">
								<tr>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[8]; ?>" border="0"></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[9]; ?>"><img src="../images/spacer.gif" width="1" height="1" border="0" alt=""></td>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[10]; ?>" border="0" alt=""></td>
								</tr>
								<tr>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[11]; ?>"></td>
									<td><img src="../images/spacer.gif" width="200" height="200" border="0" alt=""></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[12]; ?>"></td>
								</tr>
								<tr>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[13]; ?>" border="0" alt=""></td>
									<td background="<?php echo $cfg_frames_url."/".$new_imagename[14]; ?>"></td>
									<td><img src="<?php echo $cfg_frames_url."/".$new_imagename[15]; ?>" border="0" alt=""></td>
								</tr>
								</table>
						</td>
						</tr>
						</table></center>
						

						<?php


							

						} else {
							echo "<span class=\"errortxt\">A database error has occured.</span>";
								if ($cfg_debug_on==1) {
									echo "<p><b>mySQL said: </b>";
									echo mysql_error();
									echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

								}
						}
			} else {
				if ($image_upload_err > 0)  {
				echo "<h3>Error Creating Frameset</h3>\n\n<span class=\"errortxt\">There was an error uploading the new frame set.  Please be sure that the target directory exists and is writable by the server.  Your updates have not been saved.</span><br><br>\n\n\n\n";					
					$sql = "delete from snipe_gallery_frames where frame_id='".$this_frame_id."'";
					$del_frameset = mysql_query($sql);
				
				}
				if ($empty_files > 15)  {
				echo "<h3>Error Creating Frameset</h3>\n\n<span class=\"errortxt\">You have not included any images with your upload.  Please <a href=\"frame.php\">go back</a> and choose images to create your frameset.</span><br><br>\n\n\n\n";					
					$sql = "delete from snipe_gallery_frames where frame_id='".$this_frame_id."'";
					$del_frameset = mysql_query($sql);
				
				}
				if ($img_filetype_err > 0)  {
				echo "<h3>Error Creating Frameset</h3>\n\n<span class=\"errortxt\">Error: ".$img_filetype_err." image(s) are not an accepted file type.  Please be sure that your images are valid gif, jpg or png files.</span><br><br>".$img_filetype_err_names."";
				}
			}
			

			} // endif
		} else {
			echo "<span class=\"errortxt\">A database error has occured.</span>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}

		}



	}


} else {
	if (!empty($_REQUEST['frame_id'])) {
		echo "<h3>Edit Frame Set</h3>\n<p>Use the form below to edit your frame set.</p>";	
		$sql = "select * from snipe_gallery_frames where frame_id='".$_REQUEST['frame_id']."'";
		if ($get_frames = mysql_query($sql)) {
			$num_frames = mysql_num_rows($get_frames);
			if ($num_frames > 0) {
				$frame_imgs = mysql_fetch_array($get_frames);
				$frame_name = $frame_imgs['frame_name'];



			}
		} else {
			echo "<span class=\"errortxt\">A database error has occured.</span>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}

		}
	} else {
		echo "<h3>Create Frame Set</h3>\n<p>A photo frame set is a collection of images that will be used to create a graphical frame around the images within a gallery.  </p>";	
	}
			
	include ($cfg_admin_path."/lib/forms/frame_form.php");

}
include ("../layout/admin.footer.php"); ?>	
