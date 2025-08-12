<?php
/**
* admin.functions.php
*
* This file contains some of the more commonly used functions which help 
* snipe gallery to work.  Many of these functions are not exactly rocket-science,
* but they work well in automating some simple tasks.
*     
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
*/

/**
* Nicely displays php.ini settings
*
* This function simply checks for a value of "1" or "0"
* and then returns "On" or "Off".  This is used extensively in the  
* web-based installation file.
*
* {@source }
*/
function show_friendly_ini($value) {
	if (($value == 1) ||  ($value == "On")) {
		$show_value="On";
	} else {
		$show_value="Off";
	}

	return $show_value;
}


/**
* Formats mysql DATETIME field into human-readable display
*
* This function simply breaks apart the DATETIME value passed to the function
* and then formats it using date() and mktime() to display the date in the format:
* Jan 30, 1976 - 7:36 AM
*
* {@source }
*/
function make_datetime_pretty($date) { 
  $break = explode(" ", $date); 
  $datebreak = explode("-", $break[0]); 
  $time = explode(":", $break[1]); 
  $datetime = date("M j, Y - g:i A", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0])); 
  return $datetime;
} 

/**
* Formats mysql DATETIME field into human-readable display
*
* This function simply breaks apart the DATETIME value passed to the function
* and then formats it using date() and mktime() to display the date in the format:
* Jan 30, 1976
*
* {@source }
*/
function make_datetime_shortpretty($date) { 
  $break = explode(" ", $date); 
  $datebreak = explode("-", $break[0]); 
  $time = explode(":", $break[1]); 
  $datetime = date("M j, Y", mktime($time[0],$time[1],$time[2],$datebreak[1],$datebreak[2],$datebreak[0])); 
  return $datetime;
} 



/**
* Checks if the specified mySQL tables exist
*
* This function is used in the initial web-based install, and also
* in the Settings page of the admin interface.
*
* @param string $tablename mySQL table name
* @param string $db database name
*
* {@source }
*/
function TableExists($tablename, $db) {
   
   // Get a list of tables contained within the database.
   $result = mysql_list_tables($db);
   $rcount = mysql_num_rows($result);

   // Check each in list for a match.
   for ($i=0;$i<$rcount;$i++) {
       if (mysql_tablename($result, $i)==$tablename) return true;
   }
   return false;
} 


/**
* Gets the server's domain name
*
* This function is only used during the web-based installation
* to populate the domain name field.
*
* {@source }
*/
function getDomainName() { 
        //This function retrieves the base domain name as shown in $_SERVER["HTTP_HOST"]; 
        $domainName['full'] = $_SERVER["HTTP_HOST"]; 
       return $domainName['full']; 
} 


/**
* Generates a nested album/gallery list
*
* {@source }
*/
function get_cat_selectlist($current_cat_id, $count) {    
	static $option_results;      
	// if there is no current category id set, start off at the top level (zero)    
	if (!isset($current_cat_id)) {        
		$current_cat_id =0;    
	}        
	
	// increment the counter by 1    
	$count = $count+1;    
	
	// query the database for the sub-categories of whatever the parent category is    
	$sql = "SELECT id, name from snipe_gallery_cat where cat_parent = '$current_cat_id' order by name asc";     $get_options = mysql_query($sql);    
	$num_options = mysql_num_rows($get_options);       
	// our category is apparently valid, so go ahead...           
	if ($num_options > 0) {                      
		while (list($cat_id, $cat_name) = mysql_fetch_row($get_options)) {                     
			// if its not a top-level category, indent it to show that its a child category                    
			if ($current_cat_id!=0) {                       
				$indent_flag = "&nbsp;&nbsp;";                            
				for ($x=2; $x<=$count; $x++) {                                
					$indent_flag .= "--&gt;&nbsp;";                            
				}                     }                    
				$cat_name = $indent_flag.$cat_name;                                        $option_results[$cat_id] = $cat_name;                    
				// now call the function again, to recurse through the child categories                    
				get_cat_selectlist($cat_id, $count );                                        
				}                                                              
	}
return $option_results;
} 


/**
* Get the version of the GD Library
*
* {@source }
*/
function gd_version() { 
   static $gd_version_number = null; 
   if ($gd_version_number === null) { 
       /* Use output buffering to get results from phpinfo() 
       * without disturbing the page we're in.  Output 
       * buffering is "stackable" so we don't even have to 
       * worry about previous or encompassing buffering. 
	   */
       ob_start(); 
       phpinfo(8); 
       $module_info = ob_get_contents(); 
       ob_end_clean(); 
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i", 
               $module_info,$matches)) { 
           $gd_version_number = $matches[1]; 
       } else { 
           $gd_version_number = 0; 
       } 
   } 
   return $gd_version_number; 
} 


/**
* Crops the image according to the cropping tool
*
* $filename is the name of the file to be cropped
* $source_x is the original x coordinate
* $source_y is the original y coordinate
* $new_x is the new x coordinate
* $new_y is the new y coordinate
* $watermark is an integer (1 or 0) that tells the program
* whether or not the image will be watermarked.  This is important
* because if its a watermark, we need to check and see if the cache is
* being used and be sure we're cropping the correct image.
*
* {@source }
*/
function cropImage($filename, $source_x, $source_y, $new_x, $new_y,  $watermark) {
	global $cfg_thumb_path;
	global $cfg_pics_path;
	global $cfg_cache_path;
	global $cfg_thumb_width;
	global $cfg_use_cache;
	global $cfg_jpeg_qual;	
	global $cfg_debug_on;

	if ((isset($_REQUEST['croptype'])) && ($_REQUEST['croptype']=="full")) {
		if (($cfg_use_cache==1) && ($watermark==1)){
			$destination_path = $cfg_cache_path;
		} else {
			$destination_path = $cfg_pics_path;
		}
		
	} else {
		$destination_path = $cfg_thumb_path;
	}

	if ($source_x > $new_x) {
		$new_img_w = round(($source_x - $new_y) -2);
	} else {
		$new_img_w = round(($new_x - $source_x) -2);
	}
	
	if ($source_y > $new_y) {
		$new_img_h = round(($source_y - $new_y) -2);
	} else {
		$new_img_h = round(($new_y - $source_y) -2);
	}
	


	if (($cfg_thumb_width !=  $new_w) && ($_REQUEST['croptype']!="full")){
		$new_w_pixels = $cfg_thumb_width;
		$ratio = ($cfg_thumb_width/$new_img_w);			
		$new_h_pixels = round($ratio * $new_img_h);
		
	} else {
		$new_w_pixels = $new_img_w;
		$new_h_pixels = $new_img_h;
	}
	
	if ($watermark==1)  {
		if (file_exists($cfg_cache_path."/".$filename)) {
			$img_size = getimagesize($cfg_cache_path."/".$filename);
			$orig = $cfg_cache_path."/".$filename;

		} else {			
			$img_size = getimagesize($cfg_pics_path."/".$filename);
			$orig = $cfg_pics_path."/".$filename;
		}

	} else {
		$img_size = getimagesize($cfg_pics_path."/".$filename);
		$orig = $cfg_pics_path."/".$filename;
	}
	
	//if ($cfg_debug_on==1) {
	//	echo "<p><b>Details:</b><br>orig: ".$orig."<br>";
	//	echo "New: $new_img_w x $new_img_h <br>";
	//	echo "Old size: ".$img_size[0]." x ".$img_size[1]."<br>";
	//}

	if ($img_size[2]==2) {
		$img = imagecreatefromjpeg($orig);	
	} elseif ($img_size[2]==3) {
		$img = imagecreatefrompng($orig);
	}
	
	
	if (file_exists($orig)) {		
		
		//if  (imageistruecolor($img) == TRUE)  {
			$tmp_img = imagecreatetruecolor($new_w_pixels, $new_h_pixels);	
		//	echo  "truecolor<br>";
		//}  else {
		//	$tmp_img = imagecreate($new_w_pixels, $new_h_pixels);	
		//	echo  "not truecolor<br>";
		//}
		
		
		imagecopyresampled($tmp_img, $img, 0, 0, $source_x, $source_y, $new_w_pixels, $new_h_pixels, $new_img_w, $new_img_h);			
		
		if ($img_size[2]==2) {
			imagejpeg($tmp_img, $destination_path."/".$filename, $cfg_jpeg_qual);
		
		} elseif ($img_size[2]==3) {
			imagepng($tmp_img, $destination_path."/".$filename);
		}

		imagedestroy($tmp_img); 
		imagedestroy($img); 
		


	} else {
		echo "<p class=\"errortxt\">The fullsize image could not be located:</p><p>Looking for: ".$orig."</p>";

	}
	
  
} 


/**
* Adds the watermarked text to the image if the gallery type specifies
* text to watermark.  The font size, padding, and font name are
* configurable options in the config.php file
*
* {@source }
*/
function watermark_img($image_filename, $this_watermark_txt, $font_size) {

global $cfg_font_pos;
global $cfg_font_name;
global $cfg_font_path;
global $cfg_font_v_padding;
global $cfg_font_h_padding;
global $cfg_cache_path;
global $cfg_use_cache;
global $cfg_pics_path;


/*
* If the cache option is turned on, create a copy of the 
*/

	if (($cfg_use_cache==1) && (file_exists($cfg_cache_path."/".$image_filename)))  {
		$use_filename = $cfg_cache_path."/".$image_filename;
		$uploaded_img_size = getimagesize($cfg_cache_path."/".$image_filename);
	} else {
		$use_filename = $cfg_pics_path."/".$image_filename;
		$uploaded_img_size = getimagesize($cfg_pics_path."/".$image_filename);
	}

	$output_image = $cfg_pics_path."/".$image_filename;

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

		
		// The function ImageCreate() creates a PALETTE image.
		// The function ImageCreateFromJPEG() creates a TRUE COLOR image.

		

		if (!$image = imagecreatetruecolor($uploaded_img_size[0], $uploaded_img_size[1])) {
			$image = imagecreate($uploaded_img_size[0], $uploaded_img_size[1]);
		}

		if ($uploaded_img_size[2]==2) {
			$image = imagecreatefromjpeg($use_filename);
		} elseif ($uploaded_img_size[2]==3) {
			$image = imagecreatefrompng($use_filename);	
		}

		/*
		* in this case, the color is white, but you can replace the numbers with the RGB values
		* of any color you want
		*/
		$color = imagecolorallocate($image, 255,255,255);

		/*
		* make our drop shadow color
		*/
		$black = imagecolorallocate($image, 0,0,0);	

		ImageTTFText ($image, $font_size, 0, ($h_pos+2), ($v_pos+2), $black, $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

		/*
		* Now add the colored text "on top"
		*/
		ImageTTFText ($image, $font_size, 0, $h_pos, $v_pos, $color,  $cfg_font_path."/".$cfg_font_name,stripslashes($this_watermark_txt));

		if ($uploaded_img_size[2]==2) {
			imagejpeg($image,$output_image);
		} elseif ($uploaded_img_size[2]==3) {	
			imagepng($image,$output_image);
			
		} 
		
	imagedestroy($image); 
}


/**
* Adds the watermarked text to the image if the gallery type specifies
* text to watermark.  The font size, padding, and font name are
* configurable options in the config.php file
*
* {@source }
*/
function turn_img($image_filename, $direction, $cache) {

global $cfg_cache_path;
global $cfg_use_cache;
global $cfg_pics_path;

$degrees = 90;
if ($direction!="right") {
	$degrees = $degrees + 180;
}


/*
* If the cache option is turned on, create a copy of the 
*/

	if (($cfg_use_cache==1) && (file_exists($cfg_cache_path."/".$image_filename)))  {
		$use_filename = $cfg_cache_path."/".$image_filename;
		$uploaded_img_size = getimagesize($cfg_cache_path."/".$image_filename);
	} else {
		$use_filename = $cfg_pics_path."/".$image_filename;
		$uploaded_img_size = getimagesize($cfg_pics_path."/".$image_filename);
	}

	if (($cache==1) && ($cfg_use_cache==1)) {
		$output_image = $cfg_cache_path."/".$image_filename;
	} else {
		$output_image = $cfg_pics_path."/".$image_filename;
	}
			
		
		if (!$image = imagecreatetruecolor($uploaded_img_size[1], $uploaded_img_size[0])) {
			$image = imagecreate($uploaded_img_size[1], $uploaded_img_size[0]);
		}

		if ($uploaded_img_size[2]==2) {
			$image = imagecreatefromjpeg($use_filename);
		} elseif ($uploaded_img_size[2]==3) {
			$image = imagecreatefrompng($use_filename);	
		}

		

		if ($image = @imagerotate($image, $degrees, 0)) {

			if ($uploaded_img_size[2]==2) {
				imagejpeg($image,$output_image);
			} elseif ($uploaded_img_size[2]==3) {	
				imagepng($image,$output_image);
				
			} 
		} 
		
	imagedestroy($image); 
}


/**
* Makes the page numbers 
*
* This function makes page numbers in a Google-like manner
* where only a certain number of page numbers are printed on the 
* screen at any time.  This is helpful for very large galleries
* with a lot of images
*
* {@source }
*/
function make_user_page_nums($total_results, $print_query, $page_name, $results_per_page, $page, $max_pages_to_show) {

echo "Pages: ";

/* PREV LINK: print a Prev link, if the page number is not 1 */
if($page != 1) {
$pageprev = $page - 1;
echo "<a href=\"".$page_name.$print_query."page=".$pageprev."\">&#171;Prev</a> ";
}

/* get the total number of pages that are needed */

$showpages = round($max_pages_to_show/2);
$numofpages = $total_results/$results_per_page;
//echo $numofpages."-".$showpages;

if ($numofpages > $showpages ) { 
	$startpage = $page - $showpages ;
} else { 
	$startpage = 0; 
}

if ($startpage < 0){
$startpage = 0; 
}

if ($numofpages > $showpages ) {
	$endpage = $page + $showpages; 
} else { 
	$endpage = $showpages; 
}

if ($endpage > $numofpages){ 
	$endpage = $numofpages; 
}

/* loop through the page numbers and print them out */
for($i = $startpage; $i < $endpage; $i++) {

	/* if the page number in the loop is not the same as the page we're on, make it a link */
	$real_page = $i + 1;
	if ($real_page!=$page){
	echo " <a href=\"".$page_name.$print_query."page=".$real_page."\">".$real_page."</a> ";

	/* otherwise, if the loop page number is the same as the page we're on, do not make it a link, but rather just print it out */
	} else {
	echo "<b>".$real_page."</b>";
	}
}

	/* NEXT LINK -If the totalrows - $results_per_page * $page is > 0 (meaning there is a remainder), print the Next button. */
	if(($total_results-($results_per_page*$page)) > 0){
	$pagenext = $page + 1;
	echo " <a href=\"".$page_name.$print_query."page=".$pagenext."\">Next &#187;</a> ";
	}

}

?>