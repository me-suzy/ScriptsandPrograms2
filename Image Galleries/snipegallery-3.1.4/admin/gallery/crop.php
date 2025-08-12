<?php 
/**
* crop.php
*
* This file handles the cropping for both fullsize and thumbnail
* images when using the cropping tool.  This file also calls up the watermark
* function if appropriate.  The actual crop tool interface is located in
* the /lib/croptool.php file
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
$PAGE_TITLE = "Crop Tool";


include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ($cfg_admin_path."/lib/dropdown.functions.php");

include ("../layout/admin.header.php");

// get the category details
if (!empty($_REQUEST['gallery_id'])) {
$sql = "select name, default_thumbtype, watermark_txt from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname, $this_thumbtype, $this_watermark_txt) = mysql_fetch_row($get_catname);		
		}
	}
}

if (!empty($_REQUEST['image_id'])) {	


	
	$sql = "select filename, cat_id from snipe_gallery_data where id='".$_REQUEST['image_id']."'";
	if ($get_filename = mysql_query($sql)) {
		$valid_image = mysql_num_rows($get_filename);
		if ($valid_image > 0) {				
			list($image_filename, $gallery_id) = mysql_fetch_row($get_filename);				

			if (($_REQUEST['crop']==1) && (isset($_REQUEST['sx']))  && (isset($_REQUEST['sy'])) && (isset($_REQUEST['ex'])) && (isset($_REQUEST['ey']))) {

				$uploaded_img_size = getimagesize($cfg_pics_path."/".$image_filename);

				if ((isset($this_thumbtype)) && (($uploaded_img_size[2]==2) || ($uploaded_img_size[2]==2)) && ($_REQUEST['croptype']=="full"))  {
					

					cropImage($image_filename, $_REQUEST['sx'], $_REQUEST['sy'],$_REQUEST['ex'],$_REQUEST['ey'], 0);				

					if (trim($this_watermark_txt)!="") {
						cropImage($image_filename, $_REQUEST['sx'], $_REQUEST['sy'],$_REQUEST['ex'],$_REQUEST['ey'], 1);

						$text_length = (strlen($this_watermark_txt) * 6);
						if (($uploaded_img_size[0] - $cfg_font_h_padding) > $text_length){
							watermark_img($image_filename, $this_watermark_txt, $cfg_font_size);				
						} 
						
					}								

				} else  {

					cropImage($image_filename, $_REQUEST['sx'], $_REQUEST['sy'],$_REQUEST['ex'],$_REQUEST['ey'],  1);
				}

					

				if ($_REQUEST['croptype']!="full") {					
					$sql = "update snipe_gallery_data set thumbname='".$image_filename."' where id='".$_REQUEST['image_id']."'";
				}
				

				if ($update_img = mysql_query($sql)) {
					if ($_REQUEST['croptype']=="full") {		
						echo "<h3>".$LANG_ADMIN_IMG_CROPPED."</h3>";
						echo "<p>".$LANG_ADMIN_IMG_CROPPED_TXT ."</p>";
						echo '<img src="'.$cfg_pics_url.'/'.$image_filename.'?'.date("U").'">';
					} else {
						echo "<h3>".$LANG_ADMIN_THUMB_ACCEPT."</h3>";
						echo "<p>".$LANG_ADMIN_THUMB_ACCEPT_TXT."</p>";
						$size = getimagesize($cfg_thumb_url.'/'.$image_filename);						
						echo '<img src="'.$cfg_thumb_url.'/'.$image_filename.'?'.date("U").'">';
					}
					
				} else {
					echo "error";
				}
		} else {
			if ($_REQUEST['croptype']=="full") {	
				echo "<h3>".$LANG_SUBNAV_EDIT_IMG.": ".$LANG_ADMIN_CROP_IMG."</h3>\n\n";
				echo "<p>".$LANG_ADMIN_CROP_TXT."</p>";
			} else {
				echo "<h3>".$LANG_SUBNAV_EDIT_IMG.": ".$LANG_ADMIN_SEL_THUMB."</h3>\n\n";
				echo "<p>".$LANG_ADMIN_SEL_THUMB_TXT."</p>";
				
			}
			
			$image_size = getimagesize($cfg_pics_path.'/'.$image_filename);
			$img_width = $image_size[0];
			$img_height= $image_size[1];
			$show_crop_js = 1;
			
			include ($cfg_admin_path."/lib/croptool.php");
		}
	} else {
		echo "<p>".$LANG_ERR_ERROR ." - ".$LANG_ERR_INVALID_IMAGEID_HEAD."</p>";
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
echo "<h3>".$LANG_ERR_NOIMAGE_HEAD."</h3>\n\n";
 echo "<p>".$LANG_ERR_NOIMAGE_CROP." </p>";
}


include ("../layout/admin.footer.php"); ?>	
