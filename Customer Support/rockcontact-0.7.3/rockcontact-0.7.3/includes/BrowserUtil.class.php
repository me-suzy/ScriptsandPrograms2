<?
/***************************************************************************
 *                           BrowserUtil.class.php
 *                            -------------------
 *   begin                : Tuesday, Jan 11, 2005
 *   copyright            : (C) 2005 Network Rebusnet
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

class BrowserUtil {

  function sendNoCacheHeader(){
    // Date in the past
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    // always modified
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    // HTTP/1.1
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    // HTTP/1.0
    header("Pragma: no-cache");
  }

  function sendGDImage($img, $httpAccept){
    $httpAccept = strtolower($httpAccept);
    if (function_exists("imageJPEG") && (strstr($httpAccept,"jpeg") !== FALSE) ) {
      header("Content-type: image/jpeg");
      imageJPEG($img,"",80);
      return "JPEG";
    } else if( function_exists("imagePNG") && (strstr($httpAccept,"png") !== FALSE) ){
      header("Content-type: image/png");
      imagePNG($img);
      return "PNG";
    } else if (function_exists("imageGIF") && (strstr($httpAccept,"gif") !== FALSE)) {
      header("Content-type: image/gif");
      imageGIF($img);
      return "GIF";
    } else {
      // No graphic library linked
      return NULL;
    }
  }

}
