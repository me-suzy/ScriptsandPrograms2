<?php
/*************************
  Copyright (c) 2004-2005 TinyWebGallery
  written by Michael Dempfle

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation; either version 2 of the License, or
  (at your option) any later version.
  ********************************************
  TWG version: 1.3b
  $Date: 2005/11/25 00:38 $
**********************************************/

@session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>TinyWebGallery</title>
</head>
<body>
<b>Internal tests of TinyWebGallery:</b><br/>
<?php

require "config.php";


// session check;

echo "gd version : " . gd_version() . "<br />";
checksession();
checkimagettftext();
checktwg_rot();
echo "<br/>Sessionprefix: " . $_SERVER['SERVER_NAME'] . $TWG_SESSION_PREFIX . "<br />";

echo "<br /><b>phpinfo():</b><p />";
phpinfo();


function checksession() {
  if(session_id()){
    echo "<br />Session is available. TWG can use the session and features like login, private galleries, options can be used<br />";
  } else {
      echo "<br />Session is not available. TWG can not use the session and features like login, private galleries, options can be used.<br />";
      echo "<br />This check is also done in TWG itself. Some features are then disabled by default!<br />";
      echo "Please contact your server administrator. Sometimes the tmp directory for php ist not configures properly!<br />;";
  }
}

function checkimagettftext() {
if (!function_exists("imagettftext")) {
echo "<br/>Function imagettftext does not exist - print_text should be set to false!<br/>";
} else {
  echo "<br/>Function imagettftext does exist - print_text should work fine.<br/>";
}
}

function checktwg_rot()
{
    global $cachedir;
    $image = $install_dir . "buttons/private.jpg";
	  $outputimage = $cachedir . "/_rotation_available.jpg";
    $outputimageerror = $cachedir . "/_rotation_not_available.jpg"; 
    // we check only once - if one to the ouputimages exists we don't do he check again
    // delete the _twg_rot_not_available.jpg and _twg_rot_available.jpg
    if (file_exists($outputimage)) {
        echo $outputimage . " does exist already - rotation is turned on. <br />Delete this file if you want a new test.<br>";
        return true;
    } else if (file_exists($outputimageerror)) {
        echo $outputimageerror . " does exist already - rotation is turned off. <br />Delete this file if you want a new test.<br>";
        return false;
    } else {
        if (!function_exists("imagerotate")) {
            echo '<br />The funktion imagerotate is not found with function_exists - the file ' . $outputimageerror . ' is created, which diables the rotation buttons.<br />';
            if (function_exists("imagecreatetruecolor")) {
               $dst = imagecreatetruecolor(50, 37);
               imagejpeg($dst, $outputimageerror, 50);
            } else {
               echo '<br />The funktion imagecreatetruecolor was not found. I think you don\'t have GDlib > 2.x.<br />';
            }
            return false;
        } else {
            echo '<br />imagerotate does exist - Checking if it is working...<br />';
            $oldsize = getImageSize($image);
            $src = imagecreatefromjpeg($image);
            $dst = imagecreatetruecolor(50, 37);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, 50, 37, 50, 37);
            $twg_rot = imagerotate($dst, 90, 0);
            echo 'Width of the new image: ' . ImageSX($twg_rot);
            echo '<br />If the width is 0, imagerotate does not work properly.<br/>';
            if (!imagejpeg($twg_rot, $outputimage, 50)) {
                imagejpeg($dst, $outputimageerror, 50);
                echo '-> Rotation is turned off.<br />';
                return false;
            } else {
                echo '-> Rotation is turned on.<br />';
                return true;
            } 
        } 
    } 
} 

function gd_version()
{
    static $gd_version_number = null;
    if ($gd_version_number === null) {
        // Use output buffering to get results from phpinfo()
        // without disturbing the page we're in.  Output
        // buffering is "stackable" so we don't even have to
        // worry about previous or encompassing buffering.
        ob_start();
        phpinfo(8);
        $module_info = ob_get_contents();
        ob_end_clean();
        if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
                $module_info, $matches)) {
            $gd_version_number = $matches[1];
        } else {
            $gd_version_number = 0;
        } 
    } 
    return $gd_version_number;
} 


?>
</body>
</html>