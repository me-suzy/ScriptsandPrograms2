<?
/***************************************************************************
 *                            index.php [English]
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
  'TEXT_DIRECTION'            => "ltr",
  'PAGE_TITLE'                => "Rock Solid Contact US",
  'META_KEYWORDS'             => "Rock,Solid,Contact,Contact US",
  'META_DESCRIPTION'          => "Rock Solid Contact US System",
  'FIRST_NAME'                => "First Name",
  'LAST_NAME'                 => "Last Name",
  'SECTION_CONTACT'           => "Contact",
  'SECTION_SUBMISSION'        => "Message",
  'EMAIL'                     => "E-Mail",
  'SUBJECT'                   => "Subject",
  'MESSAGE'                   => "Message",
  'SECTION_VISUAL_CONFIRM'    => "Confirmation code",
  'CODE'                      => "Enter the confirmation code",
  'BUTTON_SUBMIT'             => "Send",
  'MSG_ERROR_FIELD_EMPTY'     => "Please make sure all fields was properly completed.",
  'MSG_ERROR_EMAIL_SYNTAX'    => "E-mail field didn't match pattern of a valid email.",
  'MSG_ERROR_EMAIL_DNS'       => "E-mail field no valid DNS records found.",
  'MSG_ERROR_VISUAL_CONFIRMD' => "Visual code not valid.",
  'MSG_ERROR_TICKET_USED'     => "Ticket already used, create new ticket."
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
