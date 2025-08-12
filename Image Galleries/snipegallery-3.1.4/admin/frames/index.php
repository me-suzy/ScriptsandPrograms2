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

/*
* Check to see if we're deleting a frame set.  If we are, we need to
* first get the filenames from the database so that we may remove them
* from the server.  Then we delete the frame set record, and then 
* we update any gallery sets that are using it.
*/
if ((!empty($_REQUEST['frame_id'])) && ($_REQUEST['del']=="y")) {
	$sql = "select * from snipe_gallery_frames where frame_id='".$_REQUEST['frame_id']."'";
	if ($get_frame_images = mysql_query($sql)) {
		$num_frames_for_del = mysql_num_rows($get_frame_images);
		if ($num_frames_for_del > 0) {

			$frame_imgs = mysql_fetch_array($get_frame_images);
			$del_file_err = 0;

			if (!empty($frame_imgs['top_left_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_left_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_left_sm']);
				} else {
					$del_file_err = 1;
				}
			}
			if (!empty($frame_imgs['top_bg_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_bg_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_bg_sm']);
				} else {
					$del_file_err = 1;
				}
			}

			if (!empty($frame_imgs['top_right_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_right_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_right_sm']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['left_bg_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['left_bg_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['left_bg_sm']);
				} else {
					$del_file_err = 1;
				}
			}
			if (!empty($frame_imgs['right_bg_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['right_bg_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['right_bg_sm']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_left_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_left_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_left_sm']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_bg_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_bg_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_bg_sm']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_right_sm'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_right_sm'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_right_sm']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['top_left'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_left'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_left']);
				} else {
					$del_file_err = 1;
				}
			}
			if (!empty($frame_imgs['top_bg'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_bg'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_bg']);
				} else {
					$del_file_err = 1;
				}
			}

			if (!empty($frame_imgs['top_right'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['top_right'])) {
					unlink($cfg_frames_path."/".$frame_imgs['top_right']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['left_bg'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['left_bg'])) {
					unlink($cfg_frames_path."/".$frame_imgs['left_bg']);
				} else {
					$del_file_err = 1;
				}
			}
			if (!empty($frame_imgs['right_bg'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['right_bg'])) {
					unlink($cfg_frames_path."/".$frame_imgs['right_bg']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_left'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_left'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_left']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_bg'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_bg'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_bg']);
				} else {
					$del_file_err = 1;
				} 
			}
			if (!empty($frame_imgs['bottom_right'])) {
				if (file_exists($cfg_frames_path."/".$frame_imgs['bottom_right'])) {
					unlink($cfg_frames_path."/".$frame_imgs['bottom_right']);
				} else {
					$del_file_err = 1;
				} 
			}
			
			if ($del_file_err == 1) {
				$del_status = "One or more image files could not be deleted from the server.  Perhaps they were already deleted?";
			}

			$sql = "delete from snipe_gallery_frames where frame_id='".$_REQUEST['frame_id']."'";
			if (!$delete_frameset = mysql_query($sql)) {
				$del_status = "Unable to delete frame set - database error";
				if ($cfg_debug_on==1) {
					$del_status .="<p><b>mySQL said: </b>";
					$del_status .= mysql_error();
					$del_status .="</p>\n\n<p><b>SQL query:</b> $sql </p>";

				}
			
			} else {
				$sql = "update snipe_gallery_cat set frame_style='' where frame_style='".$_REQUEST['frame_id']."'";
				if (!$reset_frameset = mysql_query($sql)) {
					$del_status = "Unable to update galleries - database error";
					if ($cfg_debug_on==1) {
						$del_status .="<p><b>mySQL said: </b>";
						$del_status .= mysql_error();
						$del_status .="</p>\n\n<p><b>SQL query:</b> $sql </p>";
					}

				} else {
					$del_status = "Your frame set was deleted and the files were removed from the server.";
				}
			}

		} else {
			$del_status = "Unable to delete frame set - perhaps it was already deleted? ";
		}

	}



}

	$sql = "select frame_id, frame_name from snipe_gallery_frames order by frame_name asc";
	if ($get_frames = mysql_query($sql)) {
		$num_frames = mysql_num_rows($get_frames);
		if ($num_frames > 0) {
			echo "<h3>Current Photo Frame Sets</h3>\n\n<p>Below are the photo frames you have created in Snipe Gallery. Click on the photo frame set name to preview or edit the set.</p>";
			if (!empty($del_status)) {
				echo "<p class=\"errortxt\">".$del_status."</p>";
			}
			echo "<table cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"#808080\">";
			while (list($frame_id, $frame_name) = mysql_fetch_row($get_frames)) { 
				
				/*
				*	Let's find out how many gallerys (if any) are using this frame
				*/
				$sql = "select count(*) from snipe_gallery_cat where frame_style='".$frame_id."'";
				$get_framed_galleries = mysql_query($sql);
				list($total_framed) = mysql_fetch_row($get_framed_galleries);


				echo "\n\n<!-- Begin Frame block -->\n\n\n";
				echo "<tr>\n<td class=\"resultline\"><b><a href=\"frame.php?frame_id=".$frame_id."\" title=\"".stripslashes($frame_name)."\" class=\"gallery\">".stripslashes($frame_name)."</a></b> (used in ".$total_framed;
				
				if (($total_framed > 1) || ($total_framed == 0)) {
					echo " galleries";
				} else {
					echo " gallery";
				}
				echo ")</td>";
				echo "<td class=\"resultline\">";
				if ($total_framed > 0) {
					echo "<a href=\"".$_SERVER['PHP_SELF']."?frame_id=".$frame_id."&del=y\" onclick=\"return confirm('Are you sure you want to delete the frame set \'".addslashes($frame_name)."\'?   There are currently ".$total_framed." galleries using this frame set.  This action cannot be undone.')\"><img src=\"".$cfg_admin_url."/images/icons/trash.gif\" border=\"0\">";
				} else {
					echo "<a href=\"".$_SERVER['PHP_SELF']."?frame_id=".$frame_id."&del=y\" onclick=\"return confirm('Are you sure you want to delete the frame set \'".addslashes($frame_name)."\'?  This action cannot be undone.')\"><img src=\"".$cfg_admin_url."/images/icons/trash.gif\" border=\"0\">";
				}			
				
				echo "</td>\n</tr>\n";				
				echo "\n\n<!-- End Frame block -->\n\n\n";
				
			}
			echo "</table>";

		} else {
			echo "<h3>Create Frame Set</h3>\n<p>A photo frame set is a collection of images that will be used to create a graphical frame around the images within a gallery.  <b>No frame sets have been created yet.</b>   </p>";			
			include ($cfg_admin_path."/lib/forms/frame_form.php");

		}

	} else {
		echo "<span class=\"errortxt\">A database error has occured.</span>";
		if ($cfg_debug_on==1) {
			echo "<p><b>mySQL said: </b>";
			echo mysql_error();
			echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

		}

	}


include ("../layout/admin.footer.php"); ?>	
