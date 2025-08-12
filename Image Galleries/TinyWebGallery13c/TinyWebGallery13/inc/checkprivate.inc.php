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
// check if it is a private gallery
global $password_file;

$privategal = false;
$passwd = array();
if ($twg_album) {
    $privatefilename = $relativepath . $basedir . "/" . $twg_album . "/" . $password_file;
} else {
    $privatefilename = $relativepath . $basedir . "/" . $password_file;
} 
if (file_exists($privatefilename)) {
    $privategal = true;
    $dateipriv = fopen($privatefilename, "r");
    $passwd_line = trim(fgets($dateipriv, 500));

    $passwd = split(",", $passwd_line);
    fclose($dateipriv);
    if ($passwd_line == "") {
        $passwd = array($privatepasswort);
    } 
} 

?>