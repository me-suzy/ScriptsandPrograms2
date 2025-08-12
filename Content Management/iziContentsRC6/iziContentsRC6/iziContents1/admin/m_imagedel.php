<?php

/***************************************************************************

 m_imagedel.php
 ---------------
 copyright : (C) 2005 - The iziContents Development Team

 iziContents version : 1.0
 fileversion : 1.0.1
 change date : 23 - 04 - 2005
 ***************************************************************************/

/***************************************************************************
 The iziContents Development Team offers no warranties on this script.
 The owner/licensee of the script is solely responsible for any problems
 caused by installation of the script or use of the script.

 All copyright notices regarding iziContents and ezContents must remain intact on the scripts and in the HTML for the scripts.

 For more info on iziContents,
 visit http://www.izicontents.com*/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the License which can be found within the
 *   zipped package. Under the licence of GPL/GNU.
 *
 ***************************************************************************/

$GLOBALS["rootdp"] = '../';

$GLOBALS["form"] = 'images';
$validaccess = VerifyAdminLogin();


if ($GLOBALS["candelete"] == False)
{
   Header("Location: ".BuildLink('adminlogin.php'));
}
else
{
   DeleteImage();
   Header("Location: ".BuildLink('m_images.php')."&page=".$_GET["page"]."&subdir=".$_GET["subdir"]);
}


function DeleteImage()
{
   global $_GET;

   $savedir = getcwd();
   chdir($GLOBALS["rootdp"].$GLOBALS["image_home"].$_GET["subdir"]);
   $ffile = $_GET["Image"];
   if (file_exists("$ffile"))
   {
      unlink ("$ffile");
   };
   chdir($savedir);
} // function DeleteImage()

?>
