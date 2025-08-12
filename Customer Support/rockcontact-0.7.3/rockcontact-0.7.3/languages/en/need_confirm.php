<?
/***************************************************************************
 *                         need_confirm.php [English]
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
  'TEXT_DIRECTION' => "ltr",
  'PAGE_TITLE'     => "Success",
  'MSG_EMAIL_SEND' => "E-Mail have been send to {VALUE_email}",
  'MSG'            => "Read your mail and follow the instruction.",
  'TICKET'         => "Ticket",
  'TICKET_OPEN'    => "Ticket Open",
  'TICKET_VALID'   => "Ticket valid for",
  'HOURS'          => "{TICKET_VALID_HOURS} hours",
  'RETURN_TO_HOME' => "Return to home"
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
