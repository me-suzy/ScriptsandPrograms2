<?
/***************************************************************************
 *                         need_confirm.php [French]
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
  'PAGE_TITLE'     => "Succès",
  'MSG_EMAIL_SEND' => "Un courriel à été envoyer à {VALUE_email}",
  'MSG'            => "Suivez les instructions dans le courriel pour confirmation.",
  'TICKET'         => "Ticket",
  'TICKET_OPEN'    => "Ticket Ouvert",
  'TICKET_VALID'   => "Ticket valide pour",
  'HOURS'          => "{TICKET_VALID_HOURS} heures",
  'RETURN_TO_HOME' => "Retour à la page d'accueil"
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
