<?

// timeout period in secs, increase the last number if desired
ini_set("max_execution_time", 3600);

////////******////////
////////******////////

// image settings for thumbnails
$width      = 200; 			// max width
$quality    = 90; 			// image quality of thumbnail
$height     = 150;			// height
$heightalso = 1; 			// height overrides width (if an image is tall, width will be smaller)
                 			// set to 1, height is more important; set to 0, width is more important
$include_mode = false;		// set if you are including this into another page by some means
							// if so, make sure the script can see $_GET okay (usually can with include())

$display_mode = "page"; 	// 'link' = direct image link,
							// 'page' = show image in script generated page
						
// colors, borders
$use_own_colors = true; 	// set to use own coloring or ignore (use css from another page)
$link_color = "#4455dd";
$hover_color = "#111565";	// will be deprecated; will have independent css file

// pictures per page
$row = 4;					// images per row
$perrow = 3;				// how many rows
$border = 2;				// border size, 0 if no border

// page options
$title = "True's Image Gallery";
$footer = "1";				// show footer on the main page
$ascending = "1";			// main index listing ascending or descending?

// where to print the page number and photo count text
$print_pages_on_top = "0";
$print_pages_on_bottom = "1";

// gallery options
$gal_sort = "1";			// turn off to do filesystem sorting
$gal_footer = "1";			// be nice if you left this on ;)
$gal_ascending = "1";		// ascending or descending...case of filenames matters

// per-gallery comment
$comment = "1";				// allow creator to have comments in galleries
$commentfile = "comment.txt";
$commentprefix = "<font size=\"1\"> &nbsp;</font><br><b>Gallery Comments from the Author:</b><br>";

// variable for "target=" when clicking on an image, empty/commented sets to "_new"
$imagetarget = "_top";

// hitcounter [NOT YET IMPLEMENTED]
$hitcounter = "1";

// show image details, html args are currently "$fn $res_w $res_h $image_size"
$showdetails = "1";
@$showdetails_html = "<br>".$fn."<br><font size='-2'>".$res_w."x".$res_h." / ".$image_size."</font>";

// create image detail information while creating thumbnails (keep OFF for now)
$showdetails_createinfo = "0";

// bytes - MB (factors of 1000) or MiB (factors of 1024) [NOT YET IMPLEMENTED]
$mib = "1";					// KiB or MiB counting (/1024), off sets to /1000
$bytetype = "k";			// b = bytes, k = kilobytes, m = megabytes, will round k/m to 2 decimal spots
$thousands = ",";
$decimal = ".";

// show count of images in each dir
$mainpage_showcount = 1;

?>