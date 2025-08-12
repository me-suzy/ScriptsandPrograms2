<?php 
/**
* Import Index File
*
* Plain page explaining import options
*     
* @package      admin
* @author       A Gianotto <snipe@snipe.net>
* @version 3.0
* @since 3.0
*/



$GALLERY_SECTION = "import";
$PAGE_TITLE = "Import Files";
include ("../../inc/config.php");
include ($cfg_admin_path."/lib/connect.php");
include ($cfg_admin_path."/lib/admin.functions.php");


include ("../layout/admin.header.php");  
?>

<h3>Snipe Gallery Image Import</h3>
<p>In addition to uploading images individually, you may choose to import files into snipe gallery.  You may zip up the images and upload the zip file using the <b><a href="zip.php">zip import tool</a></b>, or you may do a local import by uploading images into the specified directory and using the <b><a href="local.php">local import tool</a></b>.<p>


<?php
include ("../layout/admin.footer.php");   ?>	