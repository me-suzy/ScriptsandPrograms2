<?
/***************************************************************************
 *                              visual_code.php
 *                            -------------------
 *   begin                : Sunday, Nov 21, 2004
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
require_once("./includes/counter.inc.php");
require_once("./includes/template.inc.php");
require_once("./includes/BrowserUtil.class.php");
require_once("./includes/Graphic.class.php");
require_once("./includes/SQLiteBackEnd.class.php");

if( isset($_GET['id']) || isset($_POST['id']) ) {
  $id = ( isset($_POST['id']) ) ? $_POST['id'] : $_GET['id'];
} else {
  $id = '';
}

if ( ! is_md5($id) )
  return;

$DBVisualConfirm = new DBVisualConfirm();
$r_id = $DBVisualConfirm->findByID($id);
if ( $r_id == NULL ) {
  $imgpath = INSTALL_DIR . get_custom_images_dir() . "/error-code.gif";
  header("Content-type: image/gif");
  header("Content-Length: " . filesize($imgpath));
  echo file_get_contents($imgpath);
  exit;
}

$browserutil= new BrowserUtil();
$browserutil->sendNoCacheHeader();

$graphic= new Graphic();
$img = $graphic->renderVisualConfim($DBVisualConfirm->code,
                                    VISUAL_BACKGROUND_COLOR,
                                    VISUAL_FONT_COLOR,
                                    VISUAL_RAND_MASK_INTENSITY,
                                    VISUAL_FONT_HEIGHT);
 
$r_type = $browserutil->sendGDImage($img, $_SERVER['HTTP_ACCEPT']);
imageDestroy($img);

if ( $r_type != NULL )
  increment_counter(COUNTER_VISUAL_CONFIRM_RENDER);

?>
