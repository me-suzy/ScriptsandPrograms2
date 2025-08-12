<?php 
/**
* User Album/Gallery Display Listing
*
* This file displays all of the albums/galleries
* that the admin has created
* 
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*
*/
$GALLERY_SECTION = "gallery";
$PAGE_TITLE = "Galleries";
include ("inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");
include ("layout/header.php");  



	/*
	* get the album list from the database
	*/
	$sql = "select id, name, created_on from snipe_gallery_cat where cat_parent='0'";
	if ($get_galleries = mysql_query($sql)) {
		$num_galleries = mysql_num_rows($get_galleries);
		if ($num_galleries > 0) {
			echo "<h3>Current Albums</h3>\n\n<p>Below are the image albums you have created in Snipe Gallery. To the images within a gallery, simply click on the gallery name in the list below.</p>";
			if (!empty($del_message)) {
				echo "<p class=\"errortxt\">".$del_message."</p>\n\n";
			}
			echo "<table cellspacing=\"0\" cellpadding=\"3\" width=\"90%\">";
			while (list($gallery_id, $gallery_name, $gallery_created_on) = mysql_fetch_row($get_galleries)) { 
				echo "\n\n<!-- Begin Album/Gallery block -->\n\n\n";
				echo "<tr>\n<td class=\"resultline\"><b>Album: </b></td><td class=\"resultline\"><b>";
				if (!empty($gallery_name)) {
					echo stripslashes($gallery_name);
				} else {
					echo "-- untitled --";
				}
				
				echo "</b> - Created on: ".make_datetime_pretty($gallery_created_on)."</td></tr>";
				

				/*
				* get the galleries from each album
				*/
				$sql = "select id, name from snipe_gallery_cat where cat_parent='".$gallery_id."'";
				if ($get_subgalleries = mysql_query($sql)) {
					$num_subgalleries = mysql_num_rows($get_subgalleries);
					$total_images_in_cat = 0;
					if ($num_subgalleries > 0) {
						$subgallery_count = 1;
						echo "<tr><td class=\"resultline-alt\">Galleries:</td><td class=\"resultline-alt\">";
						while (list($subgallery_id, $subgallery_name) = mysql_fetch_row($get_subgalleries)) {
							echo "<a href=\"view.php?gallery_id=".$subgallery_id."\">".stripslashes($subgallery_name);
							$sql = "select count(*) from snipe_gallery_data where cat_id='".$subgallery_id."' AND publish='1'";

							if ($count_images = mysql_query($sql)) {
								list($images_in_cat) = mysql_fetch_row($count_images);
								echo " (".$images_in_cat.")";
								$total_images_in_cat = $total_images_in_cat + $images_in_cat;
							}
							echo "</a>";
							if ($subgallery_count < $num_subgalleries) {
								echo ", ";
							}
							$subgallery_count++;
						}
						echo "</td></tr>\n";
					} else {
						echo "<tr><td class=\"resultline-alt\">&nbsp;</td><td class=\"resultline-alt\">-- no galleries --</td></tr>\n";
					}
				} else {
					echo "<tr><td class=\"resultline-light\">&nbsp;</td><td class=\"resultline-light\">-- error retreiving galleries --</td></tr>\n";
				}	
				
				echo "<tr><td class=\"resultline-light\"><img src=\"".$cfg_app_url."/images/spacer.gif\" width=\"1\" height=\"5\" border=\"0\"></td><td class=\"resultline-light\"><img src=\"".$cfg_app_url."/images/spacer.gif\" width=\"1\" height=\"5\" border=\"0\"></td></tr>\n";
				
				
				echo "\n\n<!-- End Album/Gallery block -->\n\n\n";
				
			}
			echo "</table>";

		} else {
			/*
			*  If there are no galleries or albums to be found, let the user know.
			*/
			echo "<h3>Create Album</h3>\n<p>No albums or galleries have been created yet.  In order to add images, you must create at least one album with a gallery. If you are the gallery owner, please go to the administration panel and create at least one album and gallery. </p>";
		}

	} else {
		/*
		* If there was a database error, print out some details if debugging is turned on.
		*/
		echo "<span class=\"errortxt\">A database error has occured.</span>";
		if ($cfg_debug_on==1) {
			echo "<p><b>mySQL said: </b>";
			echo mysql_error();
			echo "</p>\n\n<p><b>SQL query:</b> $sql </p>";

		}

	}


include ("layout/footer.php"); ?>	
