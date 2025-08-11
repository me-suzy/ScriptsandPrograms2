<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/
include("config.inc.php")
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title>BCWB Gallery</title>
</head>
<body onclick="window.close(); return false;" leftmargin="0" topmargin="0" rightmargin="0" bottommargin="0" marginwidth="0" marginheight="0">
<?PHP 
	$imagesize = @GetImageSize ( preg_replace("/preview_image\.php\//", "", $root_path)."dcontent/".$filename );
	$filename = $http_path."dcontent/".$filename;

if($imagesize[2]==4)
	echo '
	<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
	 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
	 WIDTH="'.$imagesize[0].'" HEIGHT="'.$imagesize[1].'" ALIGN="">
	 <PARAM NAME=movie VALUE="'.$filename.'"> <PARAM NAME=quality VALUE=high><EMBED src="'.$filename.'" quality=high  WIDTH="'.$imagesize[0].'" HEIGHT="'.$imagesize[1].'" ALIGN=""
	 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
	</OBJECT>
		';
else
	echo '<a href=# onclick="window.close(); return false;"><img src="'.$filename."\" width=\"$imagesize[0]\" height=\"$imagesize[1]\"".' border="0" alt="'.$alt.'"></a>';
?><BR>
<script>
	top.window.resizeTo(<?PHP echo ($imagesize[0]+10).",".($imagesize[1]+50); ?>);
</script>
</body>
</html>
