<?
/***************************************************************************
 *                            index.php [French]
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
  'PAGE_TITLE'                => "Rock Solid Contactez-Nous",
  'META_KEYWORDS'             => "Rock,Solid,Contact,Contactez-Nous",
  'META_DESCRIPTION'          => "Rock Solid Contactez-Nous Système",
  'FIRST_NAME'                => "Prénom",
  'LAST_NAME'                 => "Nom",
  'SECTION_CONTACT'           => "Contact",
  'SECTION_SUBMISSION'        => "Message",
  'EMAIL'                     => "Courriel",
  'SUBJECT'                   => "Sujet",
  'MESSAGE'                   => "Message",
  'SECTION_VISUAL_CONFIRM'    => "Code de confirmation",
  'CODE'                      => "Entrez le code de confirmation",
  'BUTTON_SUBMIT'             => "Envoyer",
  'MSG_ERROR_FIELD_EMPTY'     => "Veuillez vous assurez que tous les champs ont été correctement complétés.",
  'MSG_ERROR_EMAIL_SYNTAX'    => "L'adresse de courriel n'a  pas une syntaxe valide.",
  'MSG_ERROR_EMAIL_DNS'       => "L'adresse de courriel n'a pas de nom DNS valide.",
  'MSG_ERROR_VISUAL_CONFIRMD' => "Confirmation visuel invalide.",
  'MSG_ERROR_TICKET_USED'     => "Ce Ticket est déjà utiliser, créé un nouveau ticket."
);

foreach ($msg as $k => $v) {
  $msg[$k] = htmlentities($v, ENT_QUOTES);
}

?>
