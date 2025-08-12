<?
/***************************************************************************
 *                              counter.inc.php
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

require_once("config.inc.php");
require_once("SQLiteBackEnd.class.php");

define('COUNTER_PAGE_INDEX',                      "index.php");
define('COUNTER_PAGE_NEED_CONFIRM',               "need_confirm.php");
define('COUNTER_PAGE_CONFIRM',                    "confirm.php");
define('COUNTER_PAGE_JFUNCTIONS',                 "jfunctions.js.php");
define('COUNTER_VISUAL_CONFIRM_CREATE',           "visual_confirm_create");
define('COUNTER_VISUAL_CONFIRM_RENDER',           "visual_confirm_render");
define('COUNTER_EMAIL_CONFIRM_SEND',              "email_confirm_send");
define('COUNTER_CONFIRM_RECEIVE',                 "confirm_receive");
define('COUNTER_EMAIL_SUBMISSION_SEND',           "email_submission_send");
define('COUNTER_ERROR_VALIDATION_REQUIRE_FIELD',  "error_validation_require_field");
define('COUNTER_ERROR_VALIDATION_EMAIL',          "error_validation_email");
define('COUNTER_ERROR_VALIDATION_VISUAL_CONFIRM', "error_validation_visual_confirm");
define('COUNTER_ERROR_VALIDATION_TICKET_USED',    "error_validation_ticket_used");
define('COUNTER_ERROR_INTEGRITY',                 "error_integrity");
define('COUNTER_ERROR_TICKET_EXPIRE',             "error_ticket_expire");
define('COUNTER_INVALID_MD5',                     "invalid_md5");
define('COUNTER_PRUNE_RUN',                       "prune_run");
define('COUNTER_PRUNE_RECORD',                    "prune_record");

/**
 * Increment counter in table TABLE_COUNTER.
 *
 * @param string $key primary key in database
 */
function increment_counter($key){
  $DBCounter = new DBCounter();
  $DBCounter->findByID($key);
  $DBCounter->value = $DBCounter->value + 1;
  $DBCounter->last = time();
  $DBCounter->save();
}

?>
