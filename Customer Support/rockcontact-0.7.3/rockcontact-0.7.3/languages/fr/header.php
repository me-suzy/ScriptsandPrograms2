<?
/***************************************************************************
 *                            header.php [French]
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

$msg = array (
  'HEADER_TITLE' => "Rock Solid Contactez-Nous",
  'ENGLISH'      => "English",
  'FRENCH'       => "Français"
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
