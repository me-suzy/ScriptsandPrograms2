<?php
session_start();
include "./func.php";
$dir="./_images" . $_SESSION["s_data"]["dir"];
GetDir($dirs,$files,$files_size);
$sort_opt=$_SESSION["s_data"]["sort"] . "sort_" . $_SESSION["s_data"]["sort2"];
unset($dirs);
@usort($files,$sort_opt);
$slideshow_files="";
for ($i=0; $i<SizeOf($files);$i++){
	if ($i!=0){
		$slideshow_files.=",";
	}
	$slideshow_files.=$dir.$files[$i]["name"];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
	<?php echo $content_type;?>
	<?php echo $content_language;?>
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta name="description" content="phpFotoAlbum" />
	<meta name="robots" content="ALL,FOLLOW" />
	<meta http-equiv="Cache-control" content="no-cache" />
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Expires" content="0" />
	<title>phpFotoAlbum - <?php echo $galver;?></title>
  </head>
<body>
SLIDESHOW IS ONLY EXPERIMENTAL...http://javascript.about.com/library/scripts/blflslideshow.htm<br />
<Script language="JavaScript">
document.write ('<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" WIDTH="'+(screen.width-25)+'" HEIGHT="'+(screen.height-100)+'" id="slideshow" ALIGN="">');
document.write ('<PARAM NAME=movie VALUE="image_show_slideshow.swf">');
document.write ('<PARAM NAME=quality VALUE=best>');
document.write ('<PARAM NAME=salign VALUE=LT>');
document.write ('<PARAM NAME=scale VALUE=noscale>');
document.write ('<PARAM NAME=bgcolor VALUE=#FFFFFF>');
document.write ('<PARAM NAME=FlashVars VALUE="border=0x000000&delay=<?php echo $_SESSION["s_data"]["slideshow_timer"]; ?>&images=<?php echo $slideshow_files; ?>">');
document.write ('<EMBED src="image_show_slideshow.swf" quality=best scale=noscale salign=LT bgcolor=#FFFFFF ALIGN="" NAME="slideshow" WIDTH="'+(screen.width-25)+'" HEIGHT="'+(screen.height-100)+'" FlashVars="border=0x000000&delay=<?php echo $_SESSION["s_data"]["slideshow_timer"]; ?>&images=<?php echo $slideshow_files; ?>" TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"> </EMBED>');
document.write ('</OBJECT>');
</Script>
</body></html>