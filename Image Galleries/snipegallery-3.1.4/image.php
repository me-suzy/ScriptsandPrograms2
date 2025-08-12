<?php 
/**
* image.php
*
* This file displays image details in the user's view (non-admin).
* It uses display_image.php for the formatting of the ac tual image display area
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

include ("inc/config.php");
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

	$sql = "select name, description, frame_style, watermark_txt, display_orderby, display_order  from snipe_gallery_cat where id='".$get_gall_id."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_cat_description, $this_frame_style, $this_watermark_txt, $this_display_orderby, $this_display_order) = mysql_fetch_row($get_catname);
			
		}
	}
}





if (!empty($this_frame_style)) {
$sql = "select top_left,  top_bg,  top_right,  left_bg,  right_bg,  bottom_left,  bottom_bg,  bottom_right  from snipe_gallery_frames where frame_id='".$this_frame_style."'";
	if ($get_frames = mysql_query($sql)) {
		$valid_framestyle = mysql_num_rows($get_frames);
		if ($valid_framestyle > 0) {				
			list($frame_top_left,  $frame_top_bg,  $frame_top_right,  $frame_left_bg,  $frame_right_bg,  $frame_bottom_left,  $frame_bottom_bg,  $frame_bottom_right) = mysql_fetch_row($get_frames);	
			
			if ((!empty($frame_top_left)) && (file_exists($cfg_frames_path."/".$frame_top_left))) {
				$frame_top_left_size = getimagesize($cfg_frames_path."/".$frame_top_left);
			} else {
				$frame_top_left = "spacer.gif";
			}

			if ((!empty($frame_top_right)) && (file_exists($cfg_frames_path."/".$frame_top_right))) {
				$frame_top_right_size = getimagesize($cfg_frames_path."/".$frame_top_right);
			} else {
				$frame_top_right = "spacer.gif";
			}

			if ((!empty($frame_bottom_left)) && (file_exists($cfg_frames_path."/".$frame_bottom_left))) {
				$frame_bottom_left_size = getimagesize($cfg_frames_path."/".$frame_bottom_left);
			} else {
				$frame_bottom_left = "spacer.gif";
			}

			if ((!empty($frame_bottom_right)) && (file_exists($cfg_frames_path."/".$frame_bottom_right))) {
				$frame_bottom_right_size = getimagesize($cfg_frames_path."/".$frame_bottom_right);
			} else {
				$frame_bottom_right = "spacer.gif";
			}

		}
	}

}

if (!empty($_REQUEST['image_id'])) {
	
		$sql = "select filename,  thumbname,  img_date,  title,  ";
		$sql .="details,  author,  location,  cat_id,  keywords,  ";
		$sql .="publish,  added  from snipe_gallery_data ";
		$sql .="where id='".$_REQUEST['image_id']."' AND publish=1 ";

		if ($show_image=mysql_query($sql)) {
			$valid_image = mysql_num_rows($show_image);
			if ($valid_image > 0) {
				
				list($image_filename,  $image_thumbname,  $image_date,  $image_title,  $image_details,  $image_author,  $image_location,  $image_cat_id,  $image_keywords,  $image_publish,   $image_added) = mysql_fetch_row($show_image);
				if (!empty($image_title)) {
					$PAGE_TITLE =  "View image: ".stripslashes($image_title);
				} else {
					
					$PAGE_TITLE = "Image in ".stripslashes($this_catname);
				}
				include ("layout/header.php");
				echo "<h3>".$PAGE_TITLE."</h3>";
				
				list($image_year, $image_month, $image_day) = split("-",$image_date);
				$monthlist= MakeMonthDropMenu("form_month",$image_month);
				$daylist= MakeDayDropMenu("form_day",$image_day);
				if ($image_year=="0000") {
					$image_year="";
				}
				include ($cfg_admin_path."/lib/display_image.php");

			} else {
				include ("layout/header.php");
				echo "<h3>".stripslashes($this_catname)." - Invalid Image</h3><p class=\"errortxt\">The image you have requested appears to be invalid.  Perhaps it has already been deleted?</p>";
			}

			

		} else {
			include ("layout/header.php");
			echo "<h3>".stripslashes($this_catname)." - Error Getting Image</h3><span class=\"errortxt\">A database error has occured.</span>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		}

	
}


include ("layout/footer.php"); ?>	
