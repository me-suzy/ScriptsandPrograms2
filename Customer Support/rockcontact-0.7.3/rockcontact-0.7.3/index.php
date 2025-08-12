<?
/***************************************************************************
 *                                index.php
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
require_once("./includes/counter.inc.php");
require_once("./includes/mail.inc.php");
require_once("./includes/GenUID.class.php");

define('PARAM_PHASE',            'phase');
define('PARAM_PHASE_VALIDATION', '0');
define('PARAM_INTEGRITY',        'integrity');

increment_counter(COUNTER_PAGE_INDEX);

if( isset($_GET['lc']) || isset($_POST['lc']) ) {
  $lc = ( isset($_POST['lc']) ) ? $_POST['lc'] : $_GET['lc'];
} else {
  $lc = '';
}

// Set Language
$lc = get_page_language($lc);
require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/index.php"));

// Split form data in array
$contact_array = array();
$submission_array = array();
$control_array = array();
foreach ($_POST as $k => $v) {
  if ( is_start_with($k, SUBMISSION_FIELD_ID) )
    array_add($submission_array, $k , trim($v));
  else if ( is_start_with($k, CONTACT_FIELD_ID) )
    array_add($contact_array, $k , trim($v));
  else if ( is_start_with($k, CONTROL_FIELD_ID) )
    array_add($control_array, $k , trim($v));
}

// Normalize array
$contact_array = normalize_fields_submit($contact_array);
$submission_array = normalize_fields_submit($submission_array);
$control_array = normalize_fields_submit($control_array);

if ( isset($control_array[PARAM_PHASE]) && $control_array[PARAM_PHASE] == PARAM_PHASE_VALIDATION ){

  // Error integrity ?
  $str_integrity = $contact_array['visual_code_id'] . $submission_array['ticket_id'] . $control_array[PARAM_PHASE];
  $checksum_integrity = get_integrity_checksum($str_integrity);
  if ( $control_array[PARAM_INTEGRITY] != $checksum_integrity) {
    increment_counter(COUNTER_ERROR_INTEGRITY);
    die("Integrity error");
  }

  // Error bad ticket ?
  if ( is_ticket_id_used($submission_array['ticket_id']) ){
    $message_error = $msg['MSG_ERROR_TICKET_USED'];
    increment_counter(COUNTER_ERROR_VALIDATION_TICKET_USED);
  }

  // Error bad visual code enter ?
  if ( ! is_valid_visual_confirm ($contact_array['visual_code_id'], $contact_array['visual_code']) ){
    $message_error = $msg['MSG_ERROR_VISUAL_CONFIRMD'];
    increment_counter(COUNTER_ERROR_VALIDATION_VISUAL_CONFIRM);
  }

  // Error invalid email ?
  $email_valid_result = is_valid_email($contact_array['email']);
  if ( $email_valid_result > 0 ){
    if ( $email_valid_result == 1 ) {
      $message_error = $msg['MSG_ERROR_EMAIL_SYNTAX'];
    } else if ($email_valid_result == 2) {
      $message_error = $msg['MSG_ERROR_EMAIL_DNS'];
    }
    increment_counter(COUNTER_ERROR_VALIDATION_EMAIL);
  }

  // Error field empty ?
  if (! is_valid_require_field($_POST)){
    $message_error = $msg['MSG_ERROR_FIELD_EMPTY'];
    increment_counter(COUNTER_ERROR_VALIDATION_REQUIRE_FIELD);
  }

  if ( ! isset($message_error) || strlen($message_error) == 0){
    // Values is valid
    $id = insert_new_message($contact_array, $submission_array, $_SERVER);
    if (USE_EMAIL_CONFIRM) {
      send_email_confirm($id, $lc);
      header("HTTP/1.0 302 Temporary redirect");
      header("Location: ". WEB_SITE ."/need_confirm.php?lc=". $lc ."&id=". $id);
      exit;
    } else {
      // Direct to confirmation
      $url_confirm = get_email_confirmation_url($submission_array['ticket_id']);
      header("HTTP/1.0 302 Temporary redirect");
      header("Location: ". $url_confirm);
      exit;
    }
  }

}

// Create array, this array need VALUE transformation
$array_value = array ();
array_add($array_value, "first_name", (isset($contact_array['first_name']) ? $contact_array['first_name'] : "") );
array_add($array_value, "last_name", (isset($contact_array['last_name']) ? $contact_array['last_name'] : "") );
array_add($array_value, "email", (isset($contact_array['email']) ? $contact_array['email'] : "") );
array_add($array_value, "subject", (isset($submission_array['subject']) ? $submission_array['subject'] : "") );
array_add($array_value, "message", (isset($submission_array['message']) ? $submission_array['message'] : "") );

// Create array, this array NOT need transformation
$array_form_value = array ();
array_add($array_form_value, "PARAM_PHASE", PARAM_PHASE);
array_add($array_form_value, "PARAM_PHASE_VALIDATION", PARAM_PHASE_VALIDATION);
array_add($array_form_value, "PARAM_INTEGRITY", PARAM_INTEGRITY);
array_add($array_form_value, "REMOTE_ADDR", $_SERVER['REMOTE_ADDR']);
array_add($array_form_value, "HTTP_USER_AGENT", $_SERVER['HTTP_USER_AGENT']);

// Add visual code ID
if ( !isset($contact_array['visual_code_id']) || ! is_valid_visual_confirm_id($contact_array['visual_code_id'])){
  // Create new visual code in table TABLE_VISUAL_CONFIRM.
  $DBVisualConfirm = new DBVisualConfirm();
  $visual_id = $DBVisualConfirm->add();
  increment_counter(COUNTER_VISUAL_CONFIRM_CREATE);
}
else 
  $visual_id = $contact_array['visual_code_id'];
array_add($array_value, "visual_code_id", $visual_id);

if ( USE_VISUAL_CONFIRM ){
  array_add($array_value, "visual_code", (isset($contact_array['visual_code']) ? $contact_array['visual_code'] : "") );
  array_add($array_form_value, "CSS_VISUAL_CODE_VISIBILITY", "visible");
  array_add($array_form_value, "PAGE_VISUAL_RENDER", "visual_code.php");
} else {
  $code = get_visual_code_from_id($visual_id);
  array_add($array_value, "visual_code", $code);
  array_add($array_form_value, "CSS_VISUAL_CODE_VISIBILITY", "hidden");
  array_add($array_form_value, "PAGE_VISUAL_RENDER", "visual_code_hidden.php");
}

// Add ticket ID
if ( ! isset($submission_array['ticket_id']) ){
  $GenUID = new GenUID();
  $ticket_id = $GenUID->nextUID();
}
else 
  $ticket_id = $submission_array['ticket_id'];
array_add($array_value, "ticket_id", $ticket_id);

// Add integrity checksum
$integrity = get_integrity_checksum($visual_id . $ticket_id . PARAM_PHASE_VALIDATION);
array_add($array_value, "integrity", $integrity);

// Add Error Message
if( isset($message_error) && strlen($message_error) > 0 ){
  array_add($array_form_value, "MESSAGE_ERROR", $message_error);
  array_add($array_form_value, "CSS_ERROR_VISIBILITY", "visible");
} else {
  array_add($array_form_value, "MESSAGE_ERROR", "");
  array_add($array_form_value, "CSS_ERROR_VISIBILITY", "hidden");
}

// Final transformation 
$array_tmp = transform_to_value_array($array_value);
$array_form_value = array_merge($array_form_value, $array_tmp);
$array_form_value = array_merge($array_form_value, $msg);

// Create web page
$tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/index.tpl"));
$tpl = template_add_header_footer($tpl, $lc);
$tpl = template_transform($tpl, $array_form_value);
echo $tpl;

// Prune
$DBRockContact = new DBRockContact();
$DBRockContact->pruneVisualConfimCode();
if (USE_AUTO_PRUNE){
  $DBRockContact->pruneOldRecords();
}

?>
