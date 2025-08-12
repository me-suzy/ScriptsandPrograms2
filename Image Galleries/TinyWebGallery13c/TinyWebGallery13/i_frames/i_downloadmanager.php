<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/

require "../config.php";
include "../inc/mysession.inc.php";
include "../inc/filefunctions.inc.php";
include "i_parserequest.inc.php";

if (isset($_GET['twg_xmlhttp'])) {
    $_SESSION["twg_download"]  = $_GET['twg_xmlhttp'];
		return "";
}

if (isset($_SESSION["twg_download"])) { // we know what to do !
  $twg_download = $_SESSION["twg_download"];
} else {
  $twg_download = false;
}

require "../language/language_" . $default_language . ".php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
 "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>TinyWebGallery</title>
<meta name="author" content="mid" />
<link rel="stylesheet" type="text/css" href="iframe.css" />
<script type="text/javaScript">reload = false;</script>
<script type="text/javaScript" src="../js/twg_image.js"></script>
<script type="text/javaScript" src="../js/twg_xhconn.js"></script>

<script type="text/javaScript">
function saveStatus(wert) {
	if (document.getElementById('storeses').checked==true ) { 
		var myConn = new XHConn();
		if (!myConn) alert("XMLHTTP not available. Try a newer/better browser.");
		var fnWhenDone = function (oXML) { };
		myConn.connect("<?php echo $_SERVER['PHP_SELF'];  ?>?twg_xmlhttp=" + wert, fnWhenDone);
	}
}
</script>
</head>
<body>
 <form action="<?php print $_SERVER['PHP_SELF']; ?>" method="get">
<table summary=''  style="width: 100%; height:100%" cellpadding='0' cellspacing='0'><tr><td class="closebutton"> 
<img name="imageField" alt='' onClick="closeiframe()" align="right" src="../buttons/close.gif" width="12" height="12" border="0" />
</td></tr><tr><td> 
<?php

    
    if ($open_download_in_new_window) {
										  $open = "window.open(' ";
										  $end = "');";
										} else {
										  $target = "parent.location.href='";
										  $end = "'";
										}
										
		            if ($enable_direct_download) {
												 $link= "../" . $basedir . '/' . rawurlencode($twg_album) . '/' . rawurlencode ($image) ;	
												// $download1 = sprintf("<a id='adefaultslide' %s href='%s/%s/%s'>", $target, $basedir, $twg_album, urldecode($image));
										} else {
										    $link= "../image.php?twg_album=" . $album_enc . "&amp;twg_show=" . $image_enc; 
										     $linkjs = "../image.php?twg_album=" . $album_enc . "&twg_show=" . $image_enc; 
										} 

if ($twg_download) {
 if ($twg_download != "all") {
 echo '<script type="text/javaScript">';
 echo 'closeiframe();' . $open . $linkjs . $end;
 echo '</script>';
 }
}

    echo $lang_dl_as_zip1;
    
    echo '
    <center><table summary=""><tr><td colspan=2 class="centertable">
 <p>
		    <input type="button" onClick="saveStatus(\'single\');window.setTimeout(\'closeiframe()\',1000);' . $open . $link . $end . '"   name="Submit" value="'. $lang_dl_as_zip2 . '" />';
		    
		    $filename = '../' . $basedir . '/' . $twg_album . '/' . str_replace("/", "_", $twg_album) . '.txt';
		    if (file_exists($filename)) {
		       $linkzip = getFileContent( $filename ,  "error reading link - check your filesettings !");
		    } else {
		       $linkzip =  '../' . $basedir . '/' . rawurlencode($twg_album) . '/' . rawurlencode (str_replace("/", "_", $twg_album)) . '.zip';
		    }
		    
		    echo '  ';
		    echo '<input type="button" target="_parent" onClick="saveStatus(\'all\');self.location.href=\'' . $linkzip . '\';window.setTimeout(\'closeiframe()\',1000);" value="' . $lang_dl_as_zip3 . '" />
		  </p>
		    </td></tr><tr><td>'; 
		    if ($xml_http) { 
		    echo '<input type="checkbox" name="checkbox" id="storeses" value="checkbox" /></td><td>' . $lang_dl_as_zip4;
		    }
        echo '</td></tr></table>';
?>
  </center>
</td></tr></table>  
</form>
<?php include "i_bottom.inc.php"; ?>
</body>
</html>