<?php 
/**
* cat_form.php
*
* This file contains the code for the album/gallery form
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
?>
<script language="javaScript">
function validateForm() {
  var okSoFar=true //-- Changes to false when bad field found.
	var wm = "The following fields are required to \n\rcontinue this action:\n\r\n";
	var noerror = 1;
  //-- Check the field, reject if blank.


  if (document.myForm.form_gallery_name.value=="") {
    okSoFar=false
	wm += "* <?php echo $create_what; ?> name\r\n";
    document.myForm.form_gallery_name.focus()
  }


	if (okSoFar==false) {
	alert(wm);
	return (false);
	}
  
}
</script>

<form method="post" action="gallery.php" name="myForm" onSubmit="return validateForm()">

<center>
<table border="0" cellspacing="1" cellpadding="3" bgcolor="#999999">
<tr>
	<td colspan="2" class="resultline"><b>
	<?php 
	if ($gallery_cat_parent==0) {
		echo "Edit Top-Level Album ";
	} else {
		if ((isset($_REQUEST['cat_id'])) && (!empty($_REQUEST['cat_id']))) {
			echo "Edit Gallery ";
		} else {
			echo "Create New Gallery";
		}
	}
	?>
</b></td>
</tr>
<tr>
	<td class="resultline-alt"><b><?php echo $create_what; ?> Name: </b></td>
	<td class="resultline-alt"><input type="text" name="form_gallery_name"<?php if ((isset($gallery_name)) && (!empty($gallery_name))) { echo " value=\"".stripslashes($gallery_name)."\""; }?> maxlength="200" size="30"></td>
</tr>
<tr>
	<td class="resultline-alt" valign="top"><b>Create In:</b> </td>
	<td class="resultline-alt">
	
	<?php

	if ((isset($gallery_cat_parent)) && ($gallery_cat_parent==0)) {
		echo '-- Top Level (Album) -- ';
		echo '<input type="hidden" name="cat_parent" value="0">';
	} else {
		$sql ="select id, name from snipe_gallery_cat where cat_parent='0' ";
		if (!empty($_REQUEST['gallery_id'])) {
			$sql .=" AND id!='".$_REQUEST['gallery_id']."'";
		}	
		$sql .=" order by name asc";
		$get_options = mysql_query($sql);    
		$num_options = mysql_num_rows($get_options);   
		echo '<select name="cat_parent"><option value="">-- Top Level (Album) -- </option>';

		// our category is apparently valid, so go ahead...           
		if ($num_options > 0) {  
			
			while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) { 
				echo "<option value=\"".$cat_id."\"";
				if (($gallery_cat_parent==$cat_id) || ($_GET['add_cat_parent']==$cat_id)){
					echo " selected=\"selected\"";
				}
				echo ">".stripslashes($cat_name)."</option>\n";

			}
			echo ' </select>';
						   
										   
		}
	}
	?>

	

	
	</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Description: </td>
	<td class="resultline-alt">
	<textarea name="form_cat_description" rows="4" cols="50"><?php if ((isset($gallery_description)) && (!empty($gallery_description))) { echo stripslashes($gallery_description); }?></textarea></td>
</tr>
<?php if (($gallery_cat_parent!=0) || (!empty($add_cat_parent))) { ?>
<tr>
	<td class="resultline-alt" valign="top">Thumbnailing: </td>
	<td class="resultline-alt"><input type="radio" name="thumb_type" value="1"<?php if ((empty($gallery_default_thumbtype)) || ($gallery_default_thumbtype==1)) { echo " checked=\"checked\""; } ?>>Thumbnail entire image<br>
	<?php if ((isset($cfg_enable_croptool)) && ($cfg_enable_croptool==1)) { ?>
		<input type="radio" name="thumb_type" value="2"<?php if ($gallery_default_thumbtype==2) { echo " checked=\"checked\""; } ?>>Create thumbnails from Snipe Gallery cropping tool<br>
	<?php } ?>
	<!-- <input type="radio" name="thumb_type" value="3"<?php if ($gallery_default_thumbtype==3) { echo " checked=\"checked\""; } ?>>Upload your own thumbnails -->
	
	</td>
</tr>
<?php if ((isset($cfg_enable_watermark)) && ($cfg_enable_watermark==1)) { ?>
<tr>
	<td class="resultline-alt" valign="top">Watermark Text: </td>
	<td class="resultline-alt">
	<?php if ($gd_info_array["FreeType Support"]== true) { ?>
	<input type="text" name="form_watermark_txt"<?php if ((isset($gallery_watermark_txt)) && (!empty($gallery_watermark_txt))) { echo " value=\"".stripslashes($gallery_watermark_txt)."\""; }?> maxlength="200" size="30">
	<?php } else { ?>
		<span class="smerrortxt">Freetype does not appear to be installed.</span>
	<?php } ?>
	
	</td>
</tr>
<?php } ?>
<tr>
	<td class="resultline-alt" valign="top">Photo Frames:</td>
	<td class="resultline-alt">
	<?php
	$sql ="select frame_id, frame_name from snipe_gallery_frames order by frame_name asc";
	if ($get_frames= mysql_query($sql)){
		$num_frames = mysql_num_rows($get_frames);
		if ($num_frames > 0) {
			echo "<select name=\"form_frame_id\">\n";
			echo "<option value=\"\">- none - </option>\n";
			if ($cfg_use_dropshadow ==1) {
				echo "<option value=\"\">no frame - use dropshadow</option>\n";
			}
				while (list($frame_id, $frame_name) = mysql_fetch_row($get_frames)) { 
					echo "<option value=\"".$frame_id."\"";
					if ((!empty($gallery_frame_style)) && ($gallery_frame_style==$frame_id)) {
						echo " selected=\"selected\"";
					}
					echo ">".stripslashes($frame_name)."</option>\n";

				}
				
			echo "</select>\n\n";
		} else {
			echo 'none available [<a href="'.$cfg_admin_url.'/frames/">create now</a>]';
		}
	
	} else {
		echo "<span class=\"errortxt\">A database error has occured.</span>";
		if ($cfg_debug_on==1) {
			echo "<p><b>mySQL said: </b>";
			echo mysql_error();
			echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

		}

	}

	?>
	
	</td>
</tr>
<tr>
	<td class="resultline-alt" valign="top">Display Order:</td>
	<td class="resultline-alt">
	<?php echo $orderby_list;	?> <?php echo $order_list;	?>
	
	</td>
</tr>
<?php } ?>
<tr>
	<td colspan="2" class="resultline" align="right"><div align="right">
	<?php 
	if ((isset($_REQUEST['gallery_id'])) && (!empty($_REQUEST['gallery_id']))) { 
		
	?>
	<input type="submit" value="Save Edits" class="formbutton">
	<input type="hidden" name="action" value="save">
	<input type="hidden" name="gallery_id" value="<?php echo $_REQUEST['gallery_id']; ?>">

	<?php	
		} else {
		if ((isset($first_album)) && ($first_album==1)) { 
			echo '<input type="hidden" name="cat_parent" value="0">';
		}
		
	?>
		<input type="submit" value="Create Album" class="formbutton">		
		<input type="hidden" name="action" value="new">
		<?php 
		
	}
	?>
	
	</div></td>
</tr>
</table>
</center>
</form>