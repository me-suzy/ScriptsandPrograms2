<?
/***************************************************************************
 *                           visual_code_hidden.php
 *                            -------------------
 *   begin                : Thursday, Nov 25, 2004
 *   copyright            : (C) 2004 Network Rebusnet
 *   contact              : http://rockcontact.rebusnet.biz/contact/
 *
 *   $Id$
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

require_once("./includes/config.inc.php");
require_once("./includes/common.inc.php");

if( isset($_GET['id']) || isset($_POST['id']) ) {
  $id = ( isset($_POST['id']) ) ? $_POST['id'] : $_GET['id'];
} else {
  $id = '';
}

if ( ! is_md5($id) )
  return;

// Get file path
$imgpath = INSTALL_DIR . IMAGE_DIR ."/pix1.gif";

// send the right headers
header("Content-Type: image/gif");
header("Content-Length: " . filesize($imgpath));

// dump the picture and stop the script
echo file_get_contents($imgpath);

exit;

?>
