<?php 
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle - based on Golden Hill Free Web 
  JavaScript Bandwidth Test

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3c
  $Date: 2005/11/15 09:02 $
**********************************************/
?><html><head>
<noscript>
<meta http-equiv="refresh" content="0; URL=<?php echo $_SERVER['PHP_SELF']; ?>?twg_highbandwidth=true">
</noscript>
<title>TinyWebGallery Bandwidth Test</title>
<script type="text/javascript">
connectionSpeed = 0;
function drawCSImageTag( fileLocation, fileSize, imgTagProperties ) {
	start = (new Date()).getTime();
	loc = fileLocation + '?t=' + escape(start);
		// Append the Start time to the image url to ensure the image is not in disk cache.
	document.write('<div style="visibility:hidden; position:absolute; z-index=3;"><img src="' + loc + '" ' + imgTagProperties + ' onload="connectionSpeed=computeConnectionSpeed(' + start + ',' + fileSize + ');"><\/div>');
	return;
}

function computeConnectionSpeed( start, fileSize ) {
	// This function returns the speed in kbps of the user's connection,
	// based upon the loading of a single image.  It is called via onload
	// by the image drawn by drawCSImageTag() and is not meant to be called
	// in any other way.  You shouldn't ever need to call it explicitly.

	end = (new Date()).getTime();
	speed = (Math.floor((((fileSize * 8) / ((end - start) / 1000)) / 1024) * 10) / 10);
	
	
	newurl = "<?php echo $_SERVER['PHP_SELF'];

?>" + location.search;
	if (newurl == "<?php echo $_SERVER['PHP_SELF'];

?>") {
	   newurl += "?";
	} else {
	   newurl += "&";
	}
	if (speed) {
		if (speed < <?php echo $bandwidth_limit;

?>) {
	   window.location = newurl + "twg_lowbandwidth=true"; 
	   return; 
	   }
	}
     window.location = newurl + "twg_highbandwidth=true"; 
}
</script><script type="text/javascript">

</script><meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<?php echo $install_dir ?>style.css" >
</head><body><script type="text/javascript">
<!--
drawCSImageTag( '<?php echo $install_dir ?>buttons/speed.jpg',                        // Image filename
                15000,                                  	  // Image size
                'border=1 height=200 alt="test"');   // <img> tag attributes
//--></script>
<br>
<center>
<?php
echo "<span class='twg_speedtest'>";
if ($_SERVER["HTTP_ACCEPT_LANGUAGE"] <> '') {
    $lang_browser = substr($HTTP_ACCEPT_LANGUAGE, 0, 2);
    if ($lang_browser == "de") {
        echo "TinyWebGallery testet die Verbindungsgeschwindigkeit ...<br/>Bitte überprüfen Sie den Parameter install_dir in der config.php, wenn die Galerie nicht startet.";
    } else {
        echo "TinyWebGallery is testing your connection speed ...<br/>If this message does not disapper please check the install_dir in the config.php!";
    } 
} 
echo "</span>";
echo "<br/>&nbsp;<br/>";
echo "<noscript>";
echo "<span style='color:#000000;' class='twg_speedtest'>";
if ($_SERVER["HTTP_ACCEPT_LANGUAGE"] <> '') {
    $lang_browser = substr($HTTP_ACCEPT_LANGUAGE, 0, 2);
    if ($lang_browser == "de") {
       echo "Auf ihrem Rechner ist Javascript deaktiviert. TWG kann auch so benutzt werden, jedoch sind viele Features nicht verfügbar. <br/>&nbsp;<br/>Da Sie diesen Text angezeigt bekommen, wurden Sie nicht automatisch weitergeleitet ;)<br /> Bitte klicken Sie <a href='" . $_SERVER['PHP_SELF'] . "?twg_highbandwidth=true'><u><b>hier</b></u></a> um die  um zur  Version für schnelle Verbindungen zu gelangen.<br/>Wenn Sie ISDN oder ein Modem haben, klicken Sie bitte <a href='" . $_SERVER['PHP_SELF'] . "?twg_lowbandwidth=true'><u><b>hier</b></u></a>.<br/>&nbsp;<br/>Um diese Nachricht nicht wieder zu erhalten, hängen Sie bitte ?twg_highbandwidth=true an ihre URL an.";
    } else {
       echo "Javascript is not enabled on your system. You can still use TWG but not all features are available. <br/>&nbsp;<br/>Becasue you can see this text you where not transfered automatically to the gallery ;)<br /> Please click <a href='" . $_SERVER['PHP_SELF'] . "?twg_highbandwidth=true'><u><b>here</b></u></a> to get to TWG with high bandwidth settings.<br/>If you have a slow connection (ISDN oder modem), please click <a href='" . $_SERVER['PHP_SELF'] . "?twg_lowbandwidth=true'><u><b>here</b></u></a>.<br/>&nbsp;<br/>If you don't want to getthis message anymore please add ?twg_highbandwidth=true (or ?twg_lowbandwidth=true for the slow connection settings as default) to your URL.";
    }
}
echo "</span>";
echo "</noscript>";
?>

</center>
</body></html>