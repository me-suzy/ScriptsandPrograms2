<?php
/*
  $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
  OSC-Affiliate

  Contribution based on:

  Enterprise Shopping Cart Software
  http://www.enterprisecart.com

  Copyright (c) 2004 Enterprise Shopping Cart Software.  Portions Copyright (c) 2001-2004 osCommerce: http://www.oscommerce.com

  Released under the GNU General Public License
*/

define('NAVBAR_TITLE_1', 'Entrar');
define('NAVBAR_TITLE_2', 'Affiliate Constraseña Olvidada');
define('HEADING_TITLE', 'He olvidado mi Contraseña!');
define('TEXT_NO_EMAIL_ADDRESS_FOUND', '<font color="#ff0000"><b>NOTA:</b></font> Ese E-Mail no figura en nuestros datos, intentelo de nuevo.');
define('EMAIL_PASSWORD_REMINDER_SUBJECT', STORE_NAME . ' - Nueva Contraseña');
define('EMAIL_PASSWORD_REMINDER_BODY', 'Ha solicitado una Nueva Contraseña desde ' . $REMOTE_ADDR . '.' . "\n\n" . 'Su nueva contraseña para \'' . STORE_NAME . '\' es:' . "\n\n" . '   %s' . "\n\n");
define('TEXT_PASSWORD_SENT', 'Se Ha Enviado Una Nueva Contraseña A Tu Email');
?>