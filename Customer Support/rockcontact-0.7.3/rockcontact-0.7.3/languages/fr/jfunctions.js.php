<?
/***************************************************************************
 *                            jfunction.js.php [French]
 *                            -------------------
 *   begin                : Saturday, Dec 04, 2004
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
  'POPUP_TITLE' => "Message",
  'POPUP_CLOSE' => "Fermer",
  'ALL_FIELDS_COMPLETED' => "Veuillez vous assurez que tous les champs ont été correctement complétés.",
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
