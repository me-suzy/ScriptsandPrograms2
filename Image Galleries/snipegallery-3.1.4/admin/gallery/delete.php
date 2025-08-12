<?php 
/**
* Delete galleries and their images
*
* This file first checks to see if the gallery to be deleted has any images
* in it.  If it does, it asks the user to confirm that they really want to
* delete all of the images within the category.
*
* If they confirm, it will select the filenames of all the images
* in the category and delete the thumbnails, fullsize, and cache
* images (if cache is enabled) - then delete the gallery
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

$GALLERY_SECTION = "gallery";
$PAGE_TITLE = "Delete Gallery";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");

include ("../layout/admin.header.php");  

if (!empty($_REQUEST['gallery_id'])) {
	if ($thiscat_parent > 0) {

	$sql = "select name from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";
	if ($get_catname = mysql_query($sql)) {
		$valid_cat = mysql_num_rows($get_catname);
		if ($valid_cat > 0) {				
			list($this_catname) = mysql_fetch_row($get_catname);		
		

	
	
		if (($_REQUEST['del']=="y") && ($_POST['confirm']!="1")) {
		$sql = "select count(*) from snipe_gallery_data where cat_id ='".$_REQUEST['gallery_id']."'";
		$get_num_images = mysql_query($sql);
		list($images_in_cat) = mysql_fetch_row($get_num_images);


		$sql = "select cat_parent from snipe_gallery_cat where id ='".$_REQUEST['gallery_id']."'";
		$get_thiscat_parent = mysql_query($sql);
		list($thiscat_parent) = mysql_fetch_row($get_thiscat_parent);

		echo "<h3>Delete Gallery: ".stripslashes($this_catname)."</h3>";
			
			if ($images_in_cat > 0) {
				echo "<p class=\"errortxt\">WARNING!  This gallery has ".$images_in_cat." images in it.  Are you SURE you wish to permanently delete this gallery and all of the images contained within it?  (This action cannot be undone)</p>";

			} else {
				echo "<p>Are you sure you wish to delete this gallery?</p>";
			}

			if ($images_in_cat > 0) {

			} else {

			}
		
		?>
		<center><table>
		<tr>
			<form method="get" action="view.php">
			<td><input type="submit" value="Cancel" class="formbutton">
			<input type="hidden" name="gallery_id" value="<?php echo $_REQUEST['gallery_id']; ?>">
			</td>
			</form>
			<form method="post" action="delete.php">
			<td><input type="submit" value="Delete" class="formbutton">
			<input type="hidden" name="gallery_id" value="<?php echo $_REQUEST['gallery_id']; ?>">
			<input type="hidden" name="confirm" value="1">			
			</td>
			</form>
		</tr>
		</table></center><br><br><br><br><br>


	<?php
	} elseif (($_REQUEST['del']!="y") && ($_POST['confirm']=="1")) {

		$sql = "select id, filename, thumbname from snipe_gallery_data where cat_id ='".$_REQUEST['gallery_id']."'";
		$get_images = mysql_query($sql);
		$count = 0;
		while (list($del_id, $del_filename, $del_thumbname) = mysql_fetch_row($get_images)) {
			if (file_exists($cfg_pics_path."/".$del_filename)) {
				unlink($cfg_pics_path."/".$del_filename);
			}
			if (file_exists($$cfg_thumb_path."/".$del_thumbname)) {
				unlink($$cfg_thumb_path."/".$del_thumbname);
			}

			if (file_exists($cfg_cache_path."/".$del_filename)) {
				unlink($cfg_cache_path."/".$del_filename);
			}

			$sql = "delete from snipe_gallery_data where id='".$del_id."' ";
			$del_query = mysql_query($sql);
			$count++;
		}
		$sql = "delete from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."' ";
		if ($del_catquery = mysql_query($sql)) {
			echo "<h3>Gallery Deleted: ".stripslashes($this_catname)."</h3>";
			echo "<p>The gallery has been successfully deleted";
			
			if ($count > 0) {
				echo ", and ".$count." images have been deleted from the server";
			}
			echo ".</p>";

		} else {
			$err_message =   "<h3>Error Deleting Gallery</h3><span class=\"errortxt\">A database error has occured.</span>";
			if ($cfg_debug_on==1) {
				$err_message .=  "<p><b>mySQL said: </b>";
				$err_message .= mysql_error();
				$err_message .= "</p>\n\n<p><b>SQL query:</b> $sql </p>";

			}
		}
			

	}
	} else {
		echo "<h3>Invalid Gallery</h3>";
		echo "<p>Sorry, that gallery number is invalid.  Perhaps it has already been deleted?</p>";
	}
}
} else {
	echo "<p class=\"errortxt\">Sorry - this is an album and cannot be deleted this way. If you wish to delete a top level album that does not have any sub-galleries, you may go back to the <a href=\"index.php\">gallery listing page</a> and delete it from there.  If the album you wish to delete has galleries in it, you must delete the galleries first. </p>";
}
}
include ("../layout/admin.footer.php"); ?>	
