<?php 
/**
* gallery.php
*
* Add or edit galleries and albums
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
$PAGE_TITLE = "Add/Edit Gallery";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ($cfg_admin_path."/lib/dropdown.functions.php");

include ("../layout/admin.header.php");  

if (!empty($_REQUEST['gallery_id'])) {

	if ($_POST['action']=="save") {

		  $sql = "update snipe_gallery_cat set  ";
		  $sql .="cat_parent='".$_POST['cat_parent']."', ";
		  $sql .="name='".addslashes($_POST['form_gallery_name'])."', ";
		  $sql .="description='".addslashes($_POST['form_cat_description'])."',  ";
		  $sql .="default_thumbtype='".$_POST['thumb_type']."', ";
		  $sql .="watermark_txt='".addslashes($_POST['form_watermark_txt'])."',  ";
		  $sql .="frame_style='".$_POST['form_frame_id']."', display_orderby='".$_POST['form_orderby']."', display_order='".$_POST['form_order']."' ";
		  $sql .="where id='".$_POST['gallery_id']."' ";

		  if ($update_gallery = mysql_query($sql)) {
			  if ($_POST['cat_parent']=="") {
				echo "<h3>Album Edits Saved</h3>";
			  } else {
				echo "<h3>Gallery Edits Saved</h3>";
			  }			  
			  echo "<p>Your edits to ".addslashes($_POST['form_gallery_name'])." have been saved! </p>";

		  } else {
			  if ($_POST['cat_parent']=="") {
				echo "<h3>Error Saving Album</h3>";
			  } else {
				echo "<h3>Error Saving Gallery</h3>";
			  }
			
			echo "<p class=\"errortxt\">A database error has occured while attempting to update your gallery.</p>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";
			}

		}

	} else {
		$sql ="select cat_parent,  name,  description,  imagefile,  default_thumbtype,  frame_style, watermark_txt, created_on, display_orderby, display_order from snipe_gallery_cat where id='".$_REQUEST['gallery_id']."'";

		


		if ($get_gallery= mysql_query($sql)){
			$valid_gallery= mysql_num_rows($get_gallery);
			if ($valid_gallery > 0) {
				list($gallery_cat_parent,  $gallery_name,  $gallery_description,  $gallery_imagefile,  $gallery_default_thumbtype,  $gallery_frame_style,  $gallery_watermark_txt, $gallery_created_on, $gallery_display_orderby, $gallery_display_order) = mysql_fetch_row($get_gallery);
				echo "<h3>Edit Album/Gallery: ".stripslashes($gallery_name)."</h3>\n<p>Use the form below to edit this album or gallery.</p>";
				$create_what = "Album/Gallery";

				$orderby_list = MakeOrderbyDropMenu("form_orderby",$gallery_display_orderby);
				$order_list = MakeOrderDropMenu("form_order",$gallery_display_order);
				include ($cfg_admin_path."/lib/forms/cat_form.php");				
				
			} else {
				echo "<h3>Invalid Gallery ID</h3>\n<p class=\"errortxt\">The gallery ID you have specified does not exist.  Perhaps it has already been deleted?</p>";
			}
		
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

	if ($_POST['action']=="new") {

		  $sql = "insert into snipe_gallery_cat (cat_parent,  name,  description,  default_thumbtype,  frame_style,  watermark_txt, display_orderby, display_order, created_on) values ( ";
		  $sql .="'".$_POST['cat_parent']."', ";
		  $sql .="'".addslashes($_POST['form_gallery_name'])."', ";
		  $sql .="'".addslashes($_POST['form_cat_description'])."',  ";
		  $sql .="'".$_POST['thumb_type']."', ";		  
		  $sql .="'".$_POST['form_frame_id']."', ";
		  $sql .="'".addslashes($_POST['form_watermark_txt'])."',  ";
		  $sql .="'".$_POST['form_orderby']."', ";
		  $sql .="'".$_POST['form_order']."', ";
		  $sql .="NOW()) ";
		 

		  if ($update_gallery = mysql_query($sql)) {
			  if ($_POST['cat_parent']=="") {
				echo "<h3>New Album Saved</h3>";
			  } else {
				echo "<h3>New Gallery Saved</h3>";
			  }			  
			  echo "<p>The album/gallery ".addslashes($_POST['form_gallery_name'])." has been saved! </p>";

		  } else {
			  if ($_POST['cat_parent']=="") {
				echo "<h3>Error Saving Album</h3>";
			  } else {
				echo "<h3>Error Saving Gallery</h3>";
			  }
			
			echo "<p class=\"errortxt\">A database error has occured while attempting to add your gallery.</p>";
			if ($cfg_debug_on==1) {
				echo "<p><b>mySQL said: </b>";
				echo mysql_error();
				echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";
			}

		}


	} else {
		echo "<h3>Create Album/Gallery</h3>\n<p>Use the form below to add a new album or gallery.</p>";
		$create_what = "Album/Gallery";
		$orderby_list = MakeOrderbyDropMenu("form_orderby","");
		$order_list = MakeOrderDropMenu("form_order","");
		include ($cfg_admin_path."/lib/forms/cat_form.php");
	}

}

include ("../layout/admin.footer.php"); ?>	
