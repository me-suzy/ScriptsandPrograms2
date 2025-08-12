<?php
//////////////////////////////////////////////////////////////
///  phpthumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://www.silisoftware.com         ///
//////////////////////////////////////////////////////////////
//                                                          //
//         This code is released under the GNU GPL:         //
//           http://www.gnu.org/copyleft/gpl.html           //
//                                                          //
//    +-----------------------------------------------+     //
//    | If you do use this code somewhere, send me    |     //
//    | an email and tell me how/where you used it.   |     //
//    |                                               |     //
//    | phpthumb() is free to use according to the    |     //
//    | terms of the GPL. Donations also gratefully   |     //
//    | accepted from happy users :)                  |     //
//    | Please see http://www.silisoftware.com        |     //
//    |                                               |     //
//    | If you like phpthumb(), please consider       |     //
//    | writing a review at HotScripts.com:           |     //
//    | http://www.hotscripts.com/Detailed/25654.html |     //
//    +-----------------------------------------------+     //
//                                                          //
//////////////////////////////////////////////////////////////
///                                                         //
// v1.2.6 - January 4, 2004                                 //
//   * Added patch to allow use of PHP older than 4.1.0     //
//     (without the superglobals arrays)                    //
//                                                          //
// v1.2.5 - December 26, 2003                               //
//   * Added configuration options for default output image //
//     format and max width/height                          //
//                                                          //
// v1.2.4 - December 20, 2003                               //
//   * Bugfix: temp directory for non-native GD support not //
//     always returning valid directory                     //
//   * Caching feature reintroduced (see configuration)     //
//                                                          //
// v1.2.3 - December 19, 2003                               //
//   * Added anti-hotlink code so the thumbnail script on   //
//     one domain cannot be used by another domain. The     //
//     list of allowed domains defaults to the current      //
//     domain but is configurable below as                  //
//     $config_nohotlink_valid_domains. The message, text   //
//     size, colors and whether to blank the image or not   //
//     are also configurable                                //
//   * Bugfix: URL image sources were not able to use the   //
//     non-GD GIF-reading functions                         //
//                                                          //
// v1.2.2 - December 17, 2003                               //
//   * Added option to use http:// URL as image source      //
//                                                          //
// v1.2.1 - December 11, 2003                               //
//   * Added option to get source data from a database      //
//     rather than a physical file                          //
//   * Bugfix: resize not proportional when wide image      //
//     limited more by max height than max width            //
//     Thanks mathias_strasser@gmx.net                      //
//   * Removed caching code                                 //
//                                                          //
// v1.2.0 - December 10, 2003                               //
//   * Added GIF support for versions of GD that do not     //
//     have built-in GIF support (v1.6.x) via the "GIF      //
//     Util" class by Fabien Ezber (www.yamasoft.com)       //
//     GD's built-in GIF-reading functions are faster, and  //
//     are present in PHP v4.3.0 or newer, but all versions //
//     of GD can display resized GIF thumbnails now.        //
//                                                          //
// v1.1.2 - October 26, 2003                                //
//   * check for source image existance to prevent text     //
//     error messages                                       //
//   * if GD not available, a GIF saying "no GD" is shown   //
//     instead of showing the original image                //
//                                                          //
// v1.1.1 - September 28, 2003                              //
//   * better resize code by sfisher10@cox.net              //
//                                                          //
// v1.1.0 - September 1, 2003                               //
//   * initial public release                               //
//   * thumbnails can now be larger than source image       //
//   * graphical error messages                             //
//                                                          //
// v1.0.0 - January 7, 2002                                 //
//   * initial private release                              //
//                                                         ///
//////////////////////////////////////////////////////////////
///                                                         //
// Description:                                             //
//                                                          //
// phpthumb() uses the GD library to create thumbnails from //
// images (GIF, PNG or JPEG) on the fly. The output size is //
// configurable (can be larger or smaller than the source), //
// and the source may be the entire image or only a portion //
// of the original image. True color and resampling is used //
// if GD v2.0+ is available, otherwise low-color and simple //
// resizing is used.                                        //
// Source image can be a physical file on the server or can //
// be retrieved from a database                             //
// GIFs are supported on all versions of GD (even if GD     //
// does not have native GIF support) thanks to Fabien       //
// Ezber's GIF Util class.                                  //
// AntiHotlinking feature prevents other people from using  //
// your server to resize their thumbnails.                  //
// Caching feature reduces server load.                     //
//                                                          //
//                                                          //
// Usage:                                                   //
//                                                          //
// Call phpthumb() just like you would a normal image.      //
// Examples: <IMG SRC="phpthumb.php?src=/image.jpg&w=100">  //
// <IMG SRC="phpthumb.php?src=http://example.com/foo.jpg">  //
// (see www.silisoftware.com for more usage examples)       //
//                                                          //
// To use a database rather than physical files, you will   //
// need to configure the $SQLquery variable as well as the  //
// username/password/database. Sample code is included for  //
// MySQL, but it should be easy to subtitute for another.   //
// The sample code uses 'src' as the primary key in query.  //
// For example, to show the image for productID #123:       //
//  <IMG SRC="phpthumb.php?src=123&w=100">                  //
//                                                          //
//                                                          //
// Parameters:                                              //
//   w  = max width of output thumbnail in pixels           //
//   h  = max height of output thumbnail in pixels          //
//   f  = output image format ('jpeg', 'png', or 'gif')     //
//   q  = JPEG compression (1=worst, 99=best, 75=default)   //
//   sx = left side of source rectangle (default = 0)       //
//   sy = top side of source rectangle (default = 0)        //
//   sw = width of source rectangle (default = fullwidth)   //
//   sh = height of source rectangle (default = fullheight) //
//                                                          //
// Notes:                                                   //
// * thumbnails will be scaled proportionately to fit in a  //
//   box of at most (width * height) pixels                 //
// * thumbnail caching for URL or database sources requires //
//   an absolute directory name for $config_cache_directory //
//   Physical file cached thumbnails will be recreated if   //
//   the source file changes, but remote/database files     //
//   cannot (modification time isn't readily available)     //
// * If you need a GUI interface for a user to specify crop //
//   settings you can investigate 12cropimage:              //
//   http://one-two.net/12cropimage                         //
//                                                         ///
//////////////////////////////////////////////////////////////

// this script relies on the superglobal arrays, fake it here for old PHP versions
if (phpversion() < '4.1.0') {
	$_SERVER  = $HTTP_SERVER_VARS;
	$_REQUEST = $_GET;
}

//////////////////////////////////////////////////////////////
// CONFIGURATION:

// * Default output configuration:
$config_output_format    = 'jpeg';                                 // default output format ('jpeg', 'png' or 'gif') - thumbnail will be output in this format (if available in your version of GD). This is always overridden by ?f=___ GETstring parameter
$config_output_maxwidth  = 0;                                      // default maximum thumbnail width.  If this is zero then default width  is the width  of the source image. This is always overridden by ?w=___ GETstring parameter
$config_output_maxheight = 0;                                      // default maximum thumbnail height. If this is zero then default height is the height of the source image. This is always overridden by ?h=___ GETstring parameter

// * Caching Configuration:
//$config_cache_directory = './cache/';                            // set the cache directory relative to the source image - must start with '.' (will not work to cache URL- or database-sourced images, use the absolute directory name below)
//$config_cache_directory = $_SERVER['DOCUMENT_ROOT'].'/cache/';   // set the cache directory to an absolute directory for all source images (must be used to cache URL- or database-sourced images)
$config_cache_directory = '';                                      // disable thumbnail caching
$config_cache_db = true;                                           // cache generated images in db

// * Anti-Hotlink Configuration:
$config_nohotlink_enabled       = false;                            // If false will allow thumbnailing from any source domain
$config_nohotlink_valid_domains = array(@$_SERVER['HTTP_HOST']);   // This is the list of domains for which thumbnails are allowed to be created. The default value of the current domain should be fine in most cases, but if neccesary you can add more domains in here, in the format 'www.example.com'
$config_nohotlink_erase_image   = true;                            // if true, thumbnail is covered up with $config_nohotlink_fill_color before text is applied
$config_nohotlink_fill_hexcolor = 'CCCCCC';                        // background color - usual HTML-style hex color notation
$config_nohotlink_text_hexcolor = 'FF0000';                        // text color       - usual HTML-style hex color notation
$config_nohotlink_text_message  = 'Hotlinking is not allowed!';    // Say whatever you want here
$config_nohotlink_text_fontsize = 3;                               // 1 is smallest, 5 is largest
//////////////////////////////////////////////////////////////


if (!function_exists('gd_info')) {
	// built into PHP v4.3.0+ (with bundled GD2 library)
	function gd_info() {
		ob_start();
		phpinfo();
		$phpinfo = ob_get_contents();
		ob_end_clean();

		// based on code by johnschaefer at gmx dot de
		// from PHP help on gd_info()
		$gd_info = array(
			'GD Version'         => '',
			'FreeType Support'   => false,
			'FreeType Support'   => false,
			'FreeType Linkage'   => '',
			'T1Lib Support'      => false,
			'GIF Read Support'   => false,
			'GIF Create Support' => false,
			'JPG Support'        => false,
			'PNG Support'        => false,
			'WBMP Support'       => false,
			'XBM Support'        => false
		);
		foreach(explode("\n", $phpinfo) as $line) {
			foreach ($gd_info as $key => $value) {
				if (strpos($line, $key) !== false) {
					$newvalue = trim(str_replace($key, '', strip_tags($line)));
					$gd_info[$key] = (($newvalue == 'enabled') ? true : $newvalue);
				}
			}
		}
		return $gd_info;
	}
}

// Set default output format based on what image types are available
$imagetypes = imagetypes();
$AvailableImageOutputFormats = array();
if ($imagetypes & IMG_GIF) {
	$thumbnailFormat               = 'gif';
	$AvailableImageOutputFormats[] = 'gif';
}
if ($imagetypes & IMG_PNG) {
	$thumbnailFormat               = 'png';
	$AvailableImageOutputFormats[] = 'png';
}
if ($imagetypes & IMG_JPG) {
	$thumbnailFormat               = 'jpeg';
	$AvailableImageOutputFormats[] = 'jpeg';
}
if (in_array($config_output_format, $AvailableImageOutputFormats)) {
	// set output format to config default if that format is available
	$thumbnailFormat = $config_output_format;
}
if (!empty($_REQUEST['f']) && (in_array($_REQUEST['f'], $AvailableImageOutputFormats))) {
	// override output format if $_REQUEST['f'] is set and that format is available
	$thumbnailFormat = $_REQUEST['f'];
}

// for JPEG images, quality 0 (worst) to 100 (best)
// quality < 25 is nasty, with not much size savings - not recommended
// problems with 100 - invalid JPEG?
$thumbnailQuality = max(1, min(99, (!empty($_REQUEST['q']) ? $_REQUEST['q'] : 75)));

if (!empty($_REQUEST['src']) || $OriginalImageData<>'') {
	// $OriginalImageData must contain the image data
	
	if (function_exists('ImageCreateFromString')) {
		if ($src = @ImageCreateFromString($OriginalImageData)) {
			// PHP's GD library before v1.5 can read (and write) GIFs
			// PHP's GD library after v2.0.(?) can read (not write) GIFs - bundled with PHP v4.3.0+
		}
		// PHP's GD library v1.6.x cannot read GIFs, so use "GIF Util" library by Fabien Ezber
		elseif(@include_once($includes_path . "/phpthumb.gif.php")){
			$bgColor = -1;
			$iIndex = 0;
			$gif = new CGIF();
			if ($gif->load_data_File($OriginalImageData, $iIndex)) {
				$src = @ImageCreateFromString($gif->getPng($bgColor));
			}
		}
		if ($src) {
			// all is good - source data read in and appears OK
			$width  = ImageSX($src);
			$height = ImageSY($src);
		}
		else {
			// cannot create image from string (unsupported image format)
			// simply output original (not resized/cropped) data and exit
			switch (substr($OriginalImageData, 0, 3)) {
				case 'GIF':
					header('Content-type: image/gif');
					break;
				case "\xFF\xD8\xFF":
					header('Content-type: image/jpeg');
					break;
				case "\x89".'PN':
					header('Content-type: image/png');
					break;
			}
			echo $OriginalImageData;
			exit;
		}
	}
	else { // GD functions not available

		// base64-encoded error images in GIF format
		$ERROR_NOGD = 'R0lGODlhIAAgALMAAAAAABQUFCQkJDY2NkZGRldXV2ZmZnJycoaGhpSUlKWlpbe3t8XFxdXV1eTk5P7+/iwAAAAAIAAgAAAE/vDJSau9WILtTAACUinDNijZtAHfCojS4W5H+qxD8xibIDE9h0OwWaRWDIljJSkUJYsN4bihMB8th3IToAKs1VtYM75cyV8sZ8vygtOE5yMKmGbO4jRdICQCjHdlZzwzNW4qZSQmKDaNjhUMBX4BBAlmMywFSRWEmAI6b5gAlhNxokGhooAIK5o/pi9vEw4Lfj4OLTAUpj6IabMtCwlSFw0DCKBoFqwAB04AjI54PyZ+yY3TD0ss2YcVmN/gvpcu4TOyFivWqYJlbAHPpOntvxNAACcmGHjZzAZqzSzcq5fNjxFmAFw9iFRunD1epU6tsIPmFCAJnWYE0FURk7wJDA0MTKpEzoWAAskiAAA7';
		header('Content-type: image/gif');
		echo base64_decode($ERROR_NOGD);
		exit;

	}
	$thumbnailSourceX      = (!empty($_REQUEST['sx']) ? $_REQUEST['sx'] : 0);
	$thumbnailSourceY      = (!empty($_REQUEST['sy']) ? $_REQUEST['sy'] : 0);
	$thumbnailSourceWidth  = (!empty($_REQUEST['sw']) ? $_REQUEST['sw'] : $width);
	$thumbnailSourceHeight = (!empty($_REQUEST['sh']) ? $_REQUEST['sh'] : $height);
	// limit source area to original image area
	$thumbnailSourceWidth  = min($thumbnailSourceWidth,  $width  - $thumbnailSourceX);
	$thumbnailSourceHeight = min($thumbnailSourceHeight, $height - $thumbnailSourceY);
	// default new width and height to source area
	$newwidth  = $thumbnailSourceWidth;
	$newheight = $thumbnailSourceHeight;
	if (($config_output_maxwidth > 0) && ($newwidth > $config_output_maxwidth)) {
		$maxwidth = $config_output_maxwidth;
		$newwidth = $maxwidth;
		$newheight = round($thumbnailSourceHeight * $newwidth / $thumbnailSourceWidth);
	}
	// if user sets width, save as max width
	// and compute new height based on source area aspect ratio
	if (!empty($_REQUEST['w'])) {
		$maxwidth = $_REQUEST['w'];
		$newwidth = $maxwidth;
		$newheight = round($thumbnailSourceHeight * $newwidth / $thumbnailSourceWidth);
	}
	// if user sets height, save as max height
	// if the max width has already been set and the new image is too tall,
	// compute new width based on source area aspect ratio
	// otherwise, use max height and compute new width
	if (!empty($_REQUEST['h']) || ($config_output_maxheight > 0)) {
		$maxheight = (!empty($_REQUEST['h']) ? $_REQUEST['h'] : $config_output_maxheight);
		if (isset($maxwidth)) {
			if ($newheight > $maxheight) {
				$newwidth = round($thumbnailSourceWidth * $maxheight / $thumbnailSourceHeight);
				$newheight = $maxheight;
			}
		}
		else {
			$newheight = $maxheight;
			$newwidth = round($thumbnailSourceWidth * $newheight / $thumbnailSourceHeight);
		}
	}
	if (gd_version() >= 2.0) {
		$im = ImageCreateTrueColor($newwidth, $newheight);
		//$im = ImageCreate($newwidth, $newheight);
		ImageCopyResampled($im, $src, 0, 0, $thumbnailSourceX, $thumbnailSourceY, $newwidth, $newheight, $thumbnailSourceWidth, $thumbnailSourceHeight);
	}
	else {
		$im = ImageCreate($newwidth, $newheight);
		ImageCopyResized($im, $src, 0, 0, $thumbnailSourceX, $thumbnailSourceY, $newwidth, $newheight, $thumbnailSourceWidth, $thumbnailSourceHeight);
	}

	////////////////////////////////////////////////////////////////
	// Optional anti-offsite hijacking of the thumbnail script
	if ($config_nohotlink_enabled && (substr($_REQUEST['src'], 0, strlen('http://')) == 'http://')) {
		$parsed_url = parse_url($_REQUEST['src']);
		if (!in_array(@$parsed_url['host'], $config_nohotlink_valid_domains)) {
			// This domain is not allowed
			$config_nohotlink_fill_color = ImageHexColorAllocate($im, $config_nohotlink_fill_hexcolor);
			$config_nohotlink_text_color = ImageHexColorAllocate($im, $config_nohotlink_text_hexcolor);

			$config_nohotlink_text_array = explode("\n", wordwrap($config_nohotlink_text_message, floor($newwidth / ImageFontWidth($config_nohotlink_text_fontsize)), "\n"));
			if ($config_nohotlink_erase_image) {
				ImageFilledRectangle($im, 0, 0, $newwidth, $newheight, $config_nohotlink_fill_color);
			}
			$rowcounter = 0;
			foreach ($config_nohotlink_text_array as $textline) {
				ImageString($im, $config_nohotlink_text_fontsize, 2, $rowcounter++ * ImageFontHeight($config_nohotlink_text_fontsize), $textline, $config_nohotlink_text_color);
			}
		}
	}
	////////////////////////////////////////////////////////////////

	ImageInterlace($im, 1);
	switch ($thumbnailFormat) {
		case 'jpeg':
			if ($cache_filename) {
				@ImageJPEG($im, $cache_filename, $thumbnailQuality);
			}
			header('Content-type: image/jpeg');
			ob_start();
				ImageJPEG($im, '', $thumbnailQuality);
				$scaledimage = ob_get_contents();
			ob_end_clean();
			echo $scaledimage;
			break;
		case 'png':
			if ($cache_filename) {
				@ImagePNG($im, $cache_filename);
			}
			header('Content-type: image/png');
			ob_start();
				ImagePNG($im);
				$scaledimage = ob_get_contents();
			ob_end_clean();
			echo $scaledimage;
			break;
		case 'gif':
			if ($cache_filename) {
				@ImageGIF($im, $cache_filename);
			}
			header('Content-type: image/gif');
			ob_start();
				ImageGIF($im);
				$scaledimage = ob_get_contents();
			ob_end_clean();
			echo $scaledimage;
			break;
	}
	ImageDestroy($im);
}
else {
	ErrorImage('Usage: '.$_SERVER['PHP_SELF'].'?src=/path/and/filename.jpg', 400, 50);
}

function gd_version() {
	$gd_info = gd_info();
	if (substr($gd_info['GD Version'], 0, strlen('bundled (')) == 'bundled (') {
		return (float) substr($gd_info['GD Version'], strlen('bundled ('), 3); // "2.0" (not "bundled (2.0.15 compatible)")
	}
	return (float) substr($gd_info['GD Version'], 0, 3); // "1.6" (not "1.6.2 or higher")
}

function ImageHexColorAllocate(&$img, $HexColorString) {
	$R = hexdec(substr($HexColorString, 0, 2));
	$G = hexdec(substr($HexColorString, 2, 2));
	$B = hexdec(substr($HexColorString, 4, 2));
	return ImageColorAllocate($img, $R, $G, $B);
}

function ErrorImage($text, $width=400, $height=100, $bgcolor='CCCCFF', $textcolor='FF0000') {
	$fontsize = 1;
	$LinesOfText = explode("\n", wordwrap($text, floor($width / ImageFontWidth($fontsize)), "\n", true));
	$height = max($height, count($LinesOfText) * ImageFontHeight($fontsize));
	if ($errorimg = ImageCreate($width, $height)) {
		$background_color = ImageHexColorAllocate($errorimg, $bgcolor);
		$text_color       = ImageHexColorAllocate($errorimg, $textcolor);
		ImageFilledRectangle($errorimg, 0, 0, $width, $height, $background_color);
		$lineYoffset = 0;
		foreach ($LinesOfText as $line) {
			ImageString($errorimg, $fontsize, 2, $lineYoffset, $line, $text_color);
			$lineYoffset += ImageFontHeight($fontsize);
		}
		if (function_exists('ImagePNG')) {
			header('Content-type: image/png');
			ImagePNG($errorimg);
		}
		elseif (function_exists('ImageGIF')) {
			header('Content-type: image/gif');
			ImageGIF($errorimg);
		}
		elseif (function_exists('ImageJPEG')) {
			header('Content-type: image/jpeg');
			ImageJPEG($errorimg);
		}
		else {
			echo $text;
		}
		ImageDestroy($errorimg);
	}
	else {
		echo $text;
	}
	ImageDestroy($errorimg);
	return true;
}

?>