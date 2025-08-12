<?
/***************************************************************************
 *                                confirm.php
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

require_once("./includes/config.inc.php");
require_once("./includes/template.inc.php");
require_once("./includes/common.inc.php");
require_once("./includes/mail.inc.php");
require_once("./includes/counter.inc.php");
require_once("./includes/SQLiteBackEnd.class.php");

increment_counter(COUNTER_PAGE_CONFIRM);

if( isset($_GET['code']) || isset($_POST['code']) ) {
  $code = ( isset($_POST['code']) ) ? $_POST['code'] : $_GET['code'];
} else {
  $code = '';
}

if ( ! is_md5($code) )
  return;

$id = get_id_for_email_confirm_code($code);

$DBLog = new DBLog();
$DBLog->findByID($id);
if ( time() > ($DBLog->since + TICKET_VALID_SECONDE) ){
  increment_counter(COUNTER_ERROR_TICKET_EXPIRE);
  die ("Ticket expired");
}

update_email_confirm_receive($id);
update_log_email_confirm_receive($id, $_SERVER);
send_email_submission($id);

$array_value = array();
array_add($array_value, "TICKET_ID", $id);
array_add($array_value, "REMOTE_ADDR", $_SERVER['REMOTE_ADDR']);
array_add($array_value, "HTTP_USER_AGENT", $_SERVER['HTTP_USER_AGENT']);

// Read change in DBLog after update_log_email_confirm_receive.
$DBLog = new DBLog();
$DBLog->findByID($id);
array_add($array_value, "DATE_TICKET_OPEN", date(DATE_FORMAT, $DBLog->since));
array_add($array_value, "DATE_TICKET_CONFIRM", date(DATE_FORMAT, $DBLog->since_confirm));

$DBContact = new DBContact();
$DBContact->findByID($id);

$lc = $DBContact->pref_lang;
require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/confirm.php"));
$array_value = array_merge($array_value, $msg);

$tmp_array = array();
array_add($tmp_array, "first_name", $DBContact->first_name);
array_add($tmp_array, "last_name", $DBContact->last_name);
array_add($tmp_array, "email", $DBContact->email);
array_add($tmp_array, "pref_lang", $DBContact->pref_lang);
$result_array_value = transform_to_value_array($tmp_array);
$array_value = array_merge($array_value, $result_array_value);

$DBSubmission = new DBSubmission();
$DBSubmission->findByID($id);

$tmp_array = array();
array_add($tmp_array, "subject", $DBSubmission->subject);
array_add($tmp_array, "message", $DBSubmission->message);
$result_array_value = transform_to_value_array($tmp_array);
$array_value = array_merge($array_value, $result_array_value);

$tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/confirm.tpl"));
$tpl = template_add_header_footer($tpl, $lc);
$tpl = template_transform($tpl, $array_value);

echo $tpl;

?>
