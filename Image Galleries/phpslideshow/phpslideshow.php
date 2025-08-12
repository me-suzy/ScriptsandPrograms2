<?php
/* 
PHPSlideShow v0.9 written by Greg Lawler
from http://www.zinkwazi.com/scripts

v0.9.2 Aug 2005 - depricated $GLOBALS[] replaced with _GET
v0.9.1 Aug 2005 - bug fixed to allow use on PHP < 4.3
v0.9 June 2005 - major re-write, now template based
v0.6.2 august 2002 - minor upgrade, added javascript notes
v0.6.1 july 2002 - fixed special character bug.
v0.6 july 2002 - added lots of formatting options and a security patch
v0.5.1 march 2002 - minor bug fixes, reg exp fix...
v0.5 march 2002 - osx path fix, page headings for multi dir, cleaner...
v0.4 july 10 2001
v0.3.5 july 5 2001
v0.3.4 april 19 2001
v0.3.3 january 9 2001
v0.3.1 september 29 2000 - added support for image buttons
v0.3 september 12 2000 - added support for comments
v0.2 august 28 2000

PHPSlideshow is relesed under the GPL
See the license at http://www.gnu.org/licenses/gpl.txt
Feel free to use/modify this little script

IMPORTANT NOTE....
if you want to send me a token of appreciation, i like coffee so
anything from http://www.starbucks.com/card will be gladly accepted ;)
my address is:
attention: greg lawler
801 alston road, santa barbara, ca 93108 usa

INSTALLATION: See README.txt

enjoy :)
greg
*/

// NOTE: your phpslideshow.php script will work "out of the box" and 
// all layout and visual effects are controlled by the template.html file.
// you can fine tune your slideshow by editing some of these settings.

// number of images to display as thumbnails if a thumbnail directory exists
// (note that this will be rounded down to an odd number for symmetry.)
$thumb_row = 17;

// name of file containing optional page headings
$heading_info_file = "heading.txt";

// file containing optional image descriptions
$pic_info_file="pics.txt";

// thumbnail directory name (no slashes needed)
$thumbnail_dir = "thumbnails";

// language text for various areas...
$lang_back = "back";
$lang_next = "next";
$lang_of = "of";
$lang_stop_slideshow = "stop slideshow";
$lang_start_slideshow = "start slideshow";
$lang_img_hover = "click for next image...";
$lang_img_alt = "slideshow image";

// automated slideshow options
// remember that you need <META_REFRESH> in the <head> section of your html
// AND the <AUTO_SLIDESHOW_LINK> tag in your page.
// $delay is the number of seconds to pause between slides...
$delay = 2;

// sort images with newest or oldest on top. (this has no effect when pics.txt is used)
// $sort_images = "oldest"; 
$sort_images = "newest"; 

// set to true to display navigation icons instead of text...
$show_navigation_buttons = "false";
$back_button = "/i/lround.gif"; 
$next_button = "/i/rround.gif";

################################################################################
// grab the variables we want set for newer php version compatability
$phpslideshow = $_GET['phpslideshow'];
$directory = $_GET['directory'];
$currentPic = $_GET['currentPic'];
$browse = $_GET['browse'];
$auto = $_GET['auto'];

// check for platform dependent path info... (for windows and mac OSX)
$path = empty($HTTP_SERVER_VARS['PATH_INFO'])?
$HTTP_SERVER_VARS['PHP_SELF']:$HTTP_SERVER_VARS['PATH_INFO'];

// this only works on php > 4.3, replacing with file()
//if( file_exists( "template.html" ) ) $template = file_get_contents("template.html");
if( file_exists( "template.html" ) ) $template = implode("", file('template.html'));
else {
    echo "<b>ERROR:</b> Can't find the template.html file";
    exit;
}

// check that the user did not change the path...
if (preg_match(':(\.\.|^/|\:):', $directory)) {
	echo "<b>ERROR:</b> Your request contains an invalid path.<br>
    Your directory may not contain .. or : or start with a /<br>";
	exit;
}

if (empty($directory)) $directory = ".";
// if there is no $heading_info_file (see format above) set page heading here
if ( !file_exists("$directory/$heading_info_file")) {
	$header = "PHPSlideshow by Greg Lawler";
	$title = "$header";
}
else {
	$heading_info = file("$directory/$heading_info_file");
	$header = "$heading_info[0]";
	$title = $header;
}
$template = str_replace("<SHOW_TITLE>",$title,$template);

// image / text buttons
if ($show_navigation_buttons == "true") {
	$back_src = "<img src='$back_button' alt='back' class='nav_buttons' class='nav_buttons'>";
	$next_src = "<img src='$next_button' alt='next' class='nav_buttons' class='nav_buttons'>";
}
else {
	$back_src = "$lang_back";
	$next_src = "$lang_next";
}	

	if ( !file_exists("$directory/$pic_info_file")) {
        $dh = opendir( "$directory" );
        $pic_info = array();
        $time_info = array();
        while( $file = readdir( $dh ) ) {
								// look for these file types....
                if (preg_match('/(jpg|jpeg|gif|png)$/i',$file)) {
                        $time_info[] = filemtime("$directory/$file");
                        $pic_info[] = $file;
                }
        }
        if ( $sort_images == "oldest" ) $sortorder = SORT_ASC;
        elseif ( $sort_images == "newest" ) $sortorder = SORT_DESC;
        array_multisort($time_info, $sortorder, $pic_info, SORT_ASC, $time_info);
  }
  else $pic_info=file("$directory/$pic_info_file");

// begin messing with the array
$number_pics = count ($pic_info);
if (($currentPic > $number_pics)||($currentPic == $number_pics)||!$currentPic)
	$currentPic = '0';
$item = explode (";", rtrim($pic_info[$currentPic]));
$last = $number_pics - 1;
$next = $currentPic + 1;
if ($currentPic > 0 ) $back = $currentPic - 1;
else $currentPic = "0";


$blank = empty($item[1])?'&nbsp;':$item[1];

if ($currentPic > 0 ) $nav=$back;
else $nav=$last;
$nav = "<a href='$path?directory=$directory&currentPic=$nav'>$back_src</a>";
$current_show = "$path?directory=$directory";
$next_link = "<a href='$path?directory=$directory&currentPic=$next'>$next_src</a>";
$template = str_replace("<CURRENT_SHOW>",$current_show,$template);
$template = str_replace("<BACK>",$nav,$template);
$template = str_replace("<NEXT>",$next_link,$template);
$template = str_replace("<POSITION>","$next $lang_of $number_pics",$template);


// {{{ ------- EXIF stuff

//get comments from the EXIF data if available...
if(extension_loaded(exif)) {
	$curr_image = "$directory/$item[0]";
	$all_exif = @exif_read_data($curr_image,0,true);
	$exifhtml = $all_exif['COMPUTED'];
	$comment = $all_exif['COMMENT'][0];
    if (!empty($comment))  {
        $template = str_replace("<EXIF_COMMENT>",$comment,$template);
    }
}
// }}}

$image_title = "$item[1]";
$template = str_replace("<IMAGE_TITLE>",$image_title,$template);

// {{{ ------- my_circular($a_images, $currentPic, $thumb_row);

function my_circular($thumbnail_dir, &$template, $a_images, $currentPic, $thumb_row, $directory) {
global $path;
global $auto_url;

// get size of $a_images array...
$number_pics = count($a_images);
// do a little error checking...
if ($currentPic > $number_pics) $currentPic = 0;
if ($currentPic < 0) $currentPic = 0;
if ($thumb_row < 0) $thumb_row = 1;

// check if thumbnail row is greater than number of images...
if ($thumb_row > $number_pics) $thumb_row = $number_pics;

// split the thumbnail number and make it symmetrical...
$half = floor($thumb_row/2);

// show thumbnails
// left hand thumbs
if (($currentPic - $half) < 0) { // near the start...
    $underage = ($currentPic-1) - $half; 
    for ( $x=($number_pics-abs($underage+1)); $x<$number_pics; $x++) {
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
    for ( $x=0; $x<$currentPic  ; $x++ ) {
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
}
else {
    for ( $x=$currentPic-$half; $x < $currentPic; $x++ ) {
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
}

// show current (center) image thumbnail...
$item = explode (";", rtrim($a_images[$currentPic]));
$out .= "\n<img src='$directory/$thumbnail_dir/".rtrim($item[0])."' class='thumbnail_center'>";

// array for right side...
if (($currentPic + $half) >= $number_pics) { // near the end
    $overage = (($currentPic + $half) - $number_pics);
    for ( $x=$currentPic+1; $x < $number_pics; $x++) {
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
    for ( $x=0; $x<=abs($overage); $x++) {
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
}
else {
    for ( $x=$currentPic+1; $x<=$currentPic+$half; $x++ ) {  // right hand thumbs
        $next=$x;
        $item = explode (";", rtrim($a_images[$x]));
        $out .= "\n<a href='$path?directory=$directory$auto_url&currentPic=$next' class='thumbnail'><img src='$directory/$thumbnail_dir/".$item[0]."' class='thumbnail'></a>";
    }
}
        $template = str_replace("<THUMBNAIL_ROW>",$out,$template);
}
// }}}
// {{{ meta refresh stuff for auto slideshow...
if ($auto == "1") {
        $auto_url = "&auto=1";
        $meta_refresh = "<meta http-equiv='refresh' content='".$delay;
        $meta_refresh .= ";url=".$path."?directory=".$directory.$auto_url."&currentPic=".$next."'>";
        $template = str_replace("<META_REFRESH>",$meta_refresh,$template);
        $auto_slideshow = "<a href='$path?directory=$directory&currentPic=$currentPic'>$lang_stop_slideshow</a>\n";
        $template = str_replace("<AUTO_SLIDESHOW_LINK>",$auto_slideshow,$template);
}
else {
        $template = str_replace("<META_REFRESH>","",$template);
        $auto_slideshow = "<a href='$path?directory=$directory&auto=1&currentPic=$next'>$lang_start_slideshow</a>\n";
        $template = str_replace("<AUTO_SLIDESHOW_LINK>",$auto_slideshow,$template);
}
// }}}

$images = "<a href='$path?directory=$directory$auto_url&currentPic=$next'>";
$images .= "<img src='$directory/$item[0]' class='image' alt='$lang_img_alt' title='$lang_img_hover'></a>";
$template = str_replace("<IMAGE>",$images,$template);

if( file_exists( "$directory/$thumbnail_dir" ) ) { 
    my_circular($thumbnail_dir, $template, $pic_info, $currentPic, $thumb_row, $directory); 
}

$image_filename = "$item[0]";
$template = str_replace("<IMAGE_FILENAME>",$image_filename,$template);

echo $template;
?>
