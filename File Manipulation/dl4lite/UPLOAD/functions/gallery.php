<?php

/*********************************************************
 * Name: gallery.php
 * Author: Dave Conley
 * Contact: realworld@blazefans.com
 * Description: Functions for thumbnail gallery management
 * Version: 4.00
 * Last edited: 16th March, 2004
 *********************************************************/

class gallery
{
	var $html;
	
	function gallery()
	{
		global $rwdInfo, $OUTPUT, $DB;
		$this->html = $OUTPUT->load_template("skin_gallery");
		if ( !$rwdInfo->imgs_saved )
		{
			$DB->query("SELECT * FROM dl_images");
			if ($myrow = $DB->fetch_row($result))
			{
				do
				{
					// Add image to cache
					$rwdInfo->image_cache[$myrow["dlid"]] = $myrow;
				} while ($myrow = $DB->fetch_row($result));
			}
			$rwdInfo->imgs_saved = 1;
		}
	}

	// Removes all thumbnails from download
	function remove_thumbs($id = 0)
	{
		global $DB, $rwdInfo;
		if ( $id == 0 )
			return;
		$result = $DB->query("SELECT * FROM dl_images WHERE dlid=$id");
		if ($rows = $DB->fetch_row($result))
		{
			do
			{
				$file = $rwdInfo->path."/downloads/".$rows["realName"];
				if(is_file($file))
					unlink($file);
			} while ($rows = $DB->fetch_row($result));
		}
	}

	function removeThumb($thispage, $return)
	{
		global $DB, $IN, $rwdInfo, $sid, $std, $act;
		if ($IN["confirm"])
		{
			$result = $DB->query("SELECT * FROM dl_images WHERE id=$IN[imgid]");
			$rows = $DB->fetch_row($result);
			if ( is_file( $rwdInfo->path."/downloads/".$rows["realName"] ) )
				unlink( $rwdInfo->path."/downloads/".$rows["realName"] );
			$result = $DB->query("DELETE FROM dl_images WHERE id=$IN[imgid]");
			$std->info (GETLANG("deldl")."<p>"."<a href='$return'>".GETLANG("continue")."</a>");
		}
		else if ($IN["cancel"])
		{
			$std->info (GETLANG("delcancel")."<p>"."<a href='$return'>".GETLANG("continue")."</a>");
		}
		else
		{
			$std->warning (GETLANG("warn_imgdel")."<p>"
							."<form method='post' action='".$thispage."'>"
							."<input type='hidden' name='imgid' value='".$_GET["imgid"]."'>"
							."<input type='Submit' name='confirm' value='".GETLANG("yes")."'>"
							."<input type='Submit' name='cancel' value='".GETLANG("no")."'> </form>");

		}
	}

	function getFirstThumb($id, $owner)
	{
		global $DB, $IN, $CONFIG, $rwdInfo, $sid;
		if ( !$CONFIG["display_thumbs"] )
		    return "";
		
		$firsttime = true;
		$imageArray = $rwdInfo->image_cache[$id];
		
		if ( empty($imageArray) )
			return "";
		
		$imgVal = $imageArray;
		
		// Roughly Calculate thumbnail dimensions in case a thumbnail doesnt exist
		$size = explode( "x", $imgVal["size"] );
		$width = $size[0];
		$height = $size[1];
		if ( $width > $height )
		{
		    if ( $width > $CONFIG["thumbWidth"] )
			$dims = "width=$CONFIG[thumbWidth]";
		    else
			$dims = "width=$width";
		}
		else
		{
		    if ( $height > $CONFIG["thumbHeight"] )
			$dims = "height=$CONFIG[thumbHeight]";
		    else
			$dims = "height=$height";
		}
		
		$imgid = $imgVal["id"];
		
		$thisthumb = array();
				
		// Main thumb data
		$thisthumb['imageurl'] = "{$rwdInfo->url}/downloads/{$imgVal['realName']}";			
		// If thumbnail exists then use that otherwise show a resized version of the full image
		if (is_file($rwdInfo->path."/downloads/t_".$imgVal["realName"]))
			$thisthumb['thumburl'] = "{$rwdInfo->url}/downloads/t_{$imgVal['realName']}";
		else
		{
			$thisthumb['thumburl'] = "{$rwdInfo->url}/downloads/{$imgVal['realName']}";
			$thisthumb['dims'] = $dims;
		}
		$thisthumb['cellwidth'] = "##WIDTH##";
		$data = $this->html->thumb_plain($thisthumb);
		
		return $data;
	}
}

?>