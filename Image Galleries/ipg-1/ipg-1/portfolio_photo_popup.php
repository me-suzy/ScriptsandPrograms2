<?php
/******************************************************************************
* IPG: Instant Photo Gallery                                                  *
* =========================================================================== *
* Software Version:             IPG 1.0                                       *
* Copyright 2005 by:            Verosky Media - Edward Verosky                *
* Support, News, Updates at:    http://www.instantphotogallery.com            *
*******************************************************************************
* This program is free software; you may redistribute it and/or modify it     *
* under the terms of the GNU General Public License as published by the Free  * 
* Software Foundation; either version 2 of the License, or (at your option)   *
* any later version.                                                          *                                                                             *
* This program is distributed WITHOUT ANY WARRANTIES; without even any        *
* implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.    *
*                                                                             *
* See www.gnu.org  for details of the GPL license.                            *
******************************************************************************/

include("./includes/config.php");
include("./includes/functions/fns_std.php");
include("./includes/functions/fns_db.php");
include('./includes/settings.php');

db_connect();

$image_id = isset($_POST['id'])?$_POST['id']:$_GET['id'];

count_click($image_id);

?>
<html>
<head>
<title>Photo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function closeWindow(){
  self.close();
}
function redisplayWindow(){
	self.focus();
}
</script>
<link rel="stylesheet" href="./CSS/main.css" type="text/css">
</head>
<center>
  <?php print '<br></div>'; ?>
  <img class="popup_image" src="<?php print urldecode($_GET['image'])?>"> <br/>
  <?php print '<div class="popup_title">Title: ' . stripslashes($_GET['title']) . '</div>'; ?>
  <br>
  <?php print '<div class="popup_caption">Caption: ' . stripslashes($_GET['caption']) . '</div>'; ?>
  <br>
  <a href="javascript: closeWindow()">Close Window</a> <br>
  <br>
</center>
</body>
</html>
