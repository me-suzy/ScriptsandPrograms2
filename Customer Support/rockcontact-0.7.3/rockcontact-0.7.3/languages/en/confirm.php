<?
/***************************************************************************
 *                           confirm.php [English]
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
  'PAGE_TITLE'     => "Confirmation Successful",
  'SUBJECT'        => "Subject",
  'MESSAGE'        => "Message",
  'MSG_SUCCESS'    => "Confirmation Successful, the following message is transmit to authority.",
  'TICKET'         => "Ticket",
  'TICKET_OPEN'    => "Ticket Open",
  'TICKET_CONFIRM' => "Ticket Confirmed",
  'EMAIL'          => "E-Mail",
  'FIRST_NAME'     => "First Name",
  'LAST_NAME'      => "Last Name",
  'RETURN_TO_HOME' => "Return to home"
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
