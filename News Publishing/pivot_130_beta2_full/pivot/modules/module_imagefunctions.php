<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }


$images_dir = "../". $Cfg['upload_path'];

$host		= $Pivot_Vars['HTTP_HOST'];						// :global pref: host
$self		= $Pivot_Vars['PHP_SELF'];						// :global pref: current script
	// :global pref: maximum width and height
$mw			= (isset($Cfg['upload_thumb_width'])) ? $Cfg['upload_thumb_width'] : 200;										
$mh			= (isset($Cfg['upload_thumb_height'])) ? $Cfg['upload_thumb_height'] : 200;								
$qual		= 70;											// :global pref: quality jpeg image
$local		= true;											// :global pref: local gd or remote gd

// ------------- GD test, from the singapore test file --------------- 
// http://cvs.sourceforge.net/viewcvs.py/singapore/singapore/test.php

//get phpinfo data
ob_start();
phpinfo(8);
$phpinfo = ob_get_contents();
ob_end_clean();

//find gd version
$phpinfo = strip_tags($phpinfo);
$phpinfo = stristr($phpinfo,"gd version");
$phpinfo = stristr($phpinfo,"version");

if(!$phpinfo) {
	// GD not found. You may be able to use ImageMagick instead");
	$local = FALSE;	
} else {
	//extract text version and number version
	$gd_version_text = substr($phpinfo,0,strpos($phpinfo,"\n"));
	$gd_version_number = substr($gd_version_text,0,strpos($gd_version_text,"."));
	$gd_version_number = substr($gd_version_number, strlen($gd_version_number)-1);
	if($gd_version_number=="1") {
		$local = FALSE;	
		//echo ("This version of GD produces very poor quality thumbnails and use of ImageMagick is greatly preferred");
	} elseif($gd_version_number=="2") {
		$local = TRUE;	
		// echo ("To take advantage of GD2's higher quality thumbnails change the <code>thumbnail_software</code> option in singapore.ini to \"gd2\"");
	}
}

/*
// a quick hack, to revert to remote cropping, if imagecreatefromjpeg does not exist..
if ( (!function_exists('imagecreatefromjpeg')) || (!function_exists('imagecreatetruecolor')) ) {
	$local = FALSE;
} */


function gd_crop($thumb) {

	global $host, $img, $mw, $mh, $qual, $local, $Cfg, $Paths;

	$sx		= $thumb->x;
	$sy		= $thumb->y;
	$sw		= $thumb->w;
	$sh		= $thumb->h;

	$scalew = $sw / $mw;
	$scaleh = $sh / $mh;

	$factor = max($scalew,$scaleh);

	$dx		= 0;
	$dy		= 0;
	$dw		= $thumb->w/$factor;
	$dh		= $thumb->h/$factor;

	print_style();

	printf("<div id='editor'><h1>Thumbnail: <b>'%s'</b></h1>\n",$img->name);

	if( $img->ext == 'gif' ) {  
		echo "<small style='color:red;'>(When using GIF files, there is a significant chance that you can't use Pivot to make thumbnails. If you have problems with making thumbnails, we suggest using PNG or JPG files.)</small><br /><br />\n";  
	}

	$filename = fixpath( dirname(dirname($Paths['pivot_url'])) . "/" . $Cfg['upload_path'] .  $img->name);
	$thumbfilename = fixpath( dirname(dirname($Paths['pivot_url'])) . "/" . $Cfg['upload_path'] .  $thumb->name);

	if($local) {
 
		$ext = strtolower($img->ext);
		if($ext == "jpeg") { $ext="jpg"; }
		
		if($ext == "jpg") $src = imagecreatefromjpeg($img->name);
		if($ext == "png") $src = imagecreatefrompng($img->name);
		if($ext == "gif") $src = imagecreatefromgif($img->name);
		

		if(function_exists('imagecreatetruecolor')) {
			$dst = imagecreatetruecolor($dw, $dh);
			$tmp_img = imagecreatetruecolor($sw, $sh);
		} else {
			$dst = imagecreate($dw, $dh);
		}


		if (function_exists('imagecopyresampled')) {
			// GD 2.0 has a bug that ignores the 'source_x' and 'source_y'..
			// to compensate, we use a temp image..
			imagecopy ($tmp_img, $src,0,0,$sx,$sy, $sw,$sh);
			imagecopyresampled($dst,$tmp_img,0,0,0,0,$dw,$dh,$sw,$sh);
		} else {
			imagecopyresized($dst,$src,0,0,$sx,$sy,$dw,$dh,$sw,$sh);
		} 

		if($ext == "jpg") {
			ImageJPEG($dst, $thumb->name, $qual);
		}

		if($ext == "png") {
			ImagePNG($dst, $thumb->name, $qual);
		}

		ImageDestroy($dst);

	} else {

		
		$remotefile = sprintf("%s%s", $host, urlencode($filename));

		$remote = sprintf("http://www.mijnkopthee.nl/remote/crop.php?img=http://%s&dx=%s&dy=%s&sx=%s&sy=%s&dw=%s&dh=%s&sw=%s&sh=%s&ext=%s", $remotefile,$dx,$dy,$sx,$sy,$dw,$dh,$sw,$sh,$img->ext);

		//debug("remote: $remote");

		if (@$fp = fopen($remote,"rb")) {
			$handle = fopen($thumb->name,"wb");
			while (!feof($fp)) {
  			  fwrite($handle,fread($fp, 8192) );
			} 
			fclose($handle);
			fclose($fp);
		} else { 
			echo "could not make thumbnail<br />";
			echo $remote;
		} 

	}
	
	
	srand ((double) microtime() * 1000000);
	$rand = rand(10000, 99999);
	printf("<img src='%s?%s'><br />\n",$thumbfilename, $rand);
	//printf("<h2>Saved as <b>%s</b></h2>\n",$thumb->name);
	print_module_footer();
}



function resize_image($imagename, $maxwidth, $maxheight) {
	global $local, $base_url, $Cfg, $host, $Pivot_Vars, $qual;

	$ext = getextension($imagename);
	if($ext == "jpeg") { $ext="jpg"; }
	
	$thumbname = make_thumbname(basename($imagename));

	$filepath = fixpath( dirname($base_url) . "/" . $Cfg['upload_path'] .  $imagename);
	$filename = ("../".$Cfg['upload_path'].$imagename);
	$thumbfilename = ( "../" . $Cfg['upload_path'] .  $thumbname);

	list($curwidth, $curheight) = getimagesize($filename);
	
	$factor = min( ($maxwidth / $curwidth) , ($maxheight / $curheight) );
	
	$sx		= 0;
	$sy		= 0;
	$sw		= $curwidth;
	$sh		= $curheight;

	$dx		= 0;
	$dy		= 0;
	$dw		= $curwidth * $factor;
	$dh		= $curheight *  $factor;

	if($local) {

		if ($ext == "jpg") { $src = ImageCreateFromJPEG($filename); }
		if ($ext == "png") { $src = ImageCreateFromPNG($filename); }
		
		if(function_exists('ImageCreateTrueColor')) {
			$dst = ImageCreateTrueColor($dw,$dh);
		} else {
			$dst = ImageCreate($dw,$dh);
		}

		ImageCopyResampled($dst,$src,$dx,$dy,$sx,$sy,$dw,$dh,$sw,$sh);

		if($ext == "jpg") ImageJPEG($dst,$thumbfilename,$qual);
		if($ext == "png") ImagePNG($dst,$thumbfilename,$qual);

		ImageDestroy($dst);

	} else {


		$remotefile = sprintf("%s%s", $Pivot_Vars['HTTP_HOST'], urlencode($filepath));

		$remote = sprintf("http://www.mijnkopthee.nl/remote/crop.php?img=http://%s&dx=%s&dy=%s&sx=%s&sy=%s&dw=%s&dh=%s&sw=%s&sh=%s&ext=%s", $remotefile,$dx,$dy,$sx,$sy,$dw,$dh,$sw,$sh,$ext);

		echo("$remote<br /><br />");


		if (@$fp = fopen($remote,"rb")) {
			$handle = fopen($thumbfilename,"wb");
			fwrite($handle,fread($fp,filesize($filename)));
			fclose($handle);
			fclose($fp);
		} else { 
			echo "could not make thumbnail<br />";
			echo $remote;
			$thumbfilename="";
		} 

	}

	return $thumbfilename;
}




function auto_thumbnail($imagename) {
	global $Cfg, $qual, $local;

	// if $local is false, we don't automatically make a thumbnail..
	if ($local == FALSE) {
		return FALSE;
	}

	$ext = getextension($imagename);
	if($ext == "jpeg") { $ext="jpg"; }
	
	$thumbname = make_thumbname(basename($imagename));
	$filename = ("../".$Cfg['upload_path'].$imagename);
	$thumbfilename = ( "../" . $Cfg['upload_path'] .  $thumbname);

	$width = $Cfg['upload_thumb_width'];
	$height = $Cfg['upload_thumb_height'];

	// echo "file: $filename<br>";
	// echo "thumb: $thumbfilename<br>";

	if ($ext == "jpg") { $src = ImageCreateFromJPEG($filename); }
	if ($ext == "png") { $src = ImageCreateFromPNG($filename); }
	
	list($curwidth, $curheight) = getimagesize($filename);

	//echo "width x height: $curwidth, $curheight<br />";
	
	$scale = min ( ($curheight / $height), ($curwidth / $width) );

	//echo "scale: $scale<br />";
	//echo "qual: $qual<br />";
	
	if(function_exists('ImageCreateTrueColor')) {
		$dst = ImageCreateTrueColor($width,$height);
	} else {
		$dst = ImageCreate($width,$height);
	}
	
	$startx = ( ($width / 2) - ($curwidth / 2 / $scale) );
	$endx = ( ( $width / 2) + ($curwidth / 2 / $scale)  ) - $startx;
	$starty = ( ($height / 2) - (($curheight / 2) / $scale) );
	$endy = ( ($height / 2) + (($curheight / 2) / $scale)) - $starty;
	
	//echo "Start en stop: $starty tot $endy<br />";
	
	ImageCopyResampled($dst, $src, $startx, $starty, 0, 0, $endx, $endy, $curwidth, $curheight);
	
	if($ext == "jpg") {
		ImageJPEG($dst,$thumbfilename,$qual);
	}

	if($ext == "png") {
		ImagePNG($dst,$thumbfilename,$qual);
	}
	
	ImageDestroy($src);
	ImageDestroy($dst);
	
	return TRUE;
}





function make_thumbname($filename) {

		if(preg_match("/([a-zA-Z]+)$/i",$filename,$match)) {
			$ext = $match[0];			
			$thumbname = preg_replace("/(\.)(.*)$/i",".thumb.".$match[0],$filename);
		} else {
			$thumbname = $filename.".thumb";
		}

		return $thumbname;

}


?>