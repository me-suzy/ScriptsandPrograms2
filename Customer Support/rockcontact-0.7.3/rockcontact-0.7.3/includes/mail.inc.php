<?
/***************************************************************************
 *                               mail.inc.php
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
require_once("template.inc.php");
require_once("common.inc.php");
require_once("counter.inc.php");
require_once("SQLiteBackEnd.class.php");

/**
 * Get the primary key id in table TABLE_EMAIL_CONFIRM for $code.
 *
 * @param string $code The code
 * @return string The primary key for $code
 */
function get_id_for_email_confirm_code($code){
  $DBEmailConfirm = new DBEmailConfirm();
  $id = $DBEmailConfirm->findByCode($code);
  return $id;
}

/**
 * Build URL for confirm submission.
 *
 * @param string $id The primary key of submission
 * @return string The URL confirmation
 * @see WEB_SITE
 */
function get_email_confirmation_url($id){
  $DBEmailConfirm = new DBEmailConfirm();
  $DBEmailConfirm->findByID($id);
  $code = $DBEmailConfirm->code;
  $rv = WEB_SITE ."/confirm.php?code=". rawurlencode($code);
  return $rv;
}

/**
 * Check if email confirmation is already send.
 *
 * @param string $id The primary key in TABLE_EMAIL_CONFIRM
 * @return boolean TRUE if email is send, FALSE otherwise
 */
function is_email_confirm_send($id){
  $DBEmailConfirm = new DBEmailConfirm();
  $DBEmailConfirm->findByID($id);

  if (  $DBEmailConfirm->send == "TRUE" )
    return TRUE;
  else
    return FALSE;
}

/**
 * Check if email submission is already send.
 *
 * @param string $id The primary key in TABLE_SUBMISSION
 * @return boolean TRUE if email is send, FALSE otherwise
 */
function is_email_submission_send($id){
  $DBSubmission = new DBSubmission();
  $DBSubmission->findByID($id);
  if ( $DBSubmission->send == "TRUE" )
    return TRUE;
  else
    return FALSE;
}

/**
 * Build and send email to EMAIL_SEND_TO.
 *
 * @param string $id The primary key of submission
 * @see EMAIL_SEND_TO
 * @see EMAIL_FROM
 * @see COUNTER_EMAIL_SUBMISSION_SEND
 * @see DEFAULT_LANGUAGE
 * @see DATE_FORMAT
 * @see VALUE_FIELD_ID
 */
function send_email_submission($id) {

  if ( is_email_submission_send($id) )
    return;

  $array_values = array();
  array_add($array_values, 'TICKET_ID', $id);

  $DBContact = new DBContact();
  $DBContact->findByID($id);
  array_add($array_values, VALUE_FIELD_ID .'first_name', $DBContact->first_name);
  array_add($array_values, VALUE_FIELD_ID .'last_name', $DBContact->last_name);
  array_add($array_values, VALUE_FIELD_ID .'email', $DBContact->email);

  $DBSubmission = new DBSubmission();
  $DBSubmission->findByID($id);
  array_add($array_values, VALUE_FIELD_ID .'subject', $DBSubmission->subject);
  array_add($array_values, VALUE_FIELD_ID .'message', $DBSubmission->message);

  $DBLog = new DBLog();
  $DBLog->findByID($id);
  array_add($array_values, 'DATE_TICKET_OPEN', date(DATE_FORMAT, $DBLog->since));
  array_add($array_values, 'DATE_TICKET_CONFIRM', date(DATE_FORMAT, $DBLog->since_confirm));

  $tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR ."/". DEFAULT_LANGUAGE ."/email-submission.txt.tpl"));
  $msg = template_transform($tpl, $array_values);

  $is_accepted = send_mail(EMAIL_SEND_TO, $DBSubmission->subject, $msg, EMAIL_FROM);

  if ( $is_accepted ) {
    increment_counter(COUNTER_EMAIL_SUBMISSION_SEND);
    $DBSubmission->send = 'TRUE';
    $DBSubmission->save();
  }

}

/**
 * Build and send confirmation email.
 *
 * @param string $id The primary key of submission
 * @param string $lc The ISO639 language used for email.
 * @return boolean TRUE if email is send
 * @see EMAIL_FROM
 * @see COUNTER_EMAIL_CONFIRM_SEND
 * @see TICKET_VALID_SECONDE
 * @see DATE_FORMAT
 * @see VALUE_FIELD_ID
 */
function send_email_confirm($id, $lc) {

  if ( is_email_confirm_send($id) )
    return;

  $array_values = array();
  array_add($array_values, 'TICKET_ID', $id);
  array_add($array_values, 'TICKET_VALID_HOURS', round(TICKET_VALID_SECONDE / (60 * 60 )) );
  array_add($array_values, 'LINK_CONFIRM', get_email_confirmation_url($id));

  $DBContact = new DBContact();
  $DBContact->findByID($id);
  array_add($array_values, VALUE_FIELD_ID .'first_name', $DBContact->first_name);
  array_add($array_values, VALUE_FIELD_ID .'last_name', $DBContact->last_name);
  array_add($array_values, VALUE_FIELD_ID .'email', $DBContact->email);

  $DBSubmission = new DBSubmission();
  $DBSubmission->findByID($id);
  array_add($array_values, VALUE_FIELD_ID .'subject', $DBSubmission->subject);
  array_add($array_values, VALUE_FIELD_ID .'message', $DBSubmission->message);

  $DBLog = new DBLog();
  $DBLog->findByID($id);
  array_add($array_values, 'DATE_TICKET_OPEN', date(DATE_FORMAT, $DBLog->since));

  $tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR ."/". $lc ."/email-confirm.txt.tpl"));
  $msg = template_transform($tpl, $array_values);

  $is_accepted = send_mail($DBContact->email, $DBSubmission->subject, $msg, EMAIL_FROM);

  if ( $is_accepted ) {
    increment_counter(COUNTER_EMAIL_CONFIRM_SEND);
    $DBEmailConfirm = new DBEmailConfirm();
    $DBEmailConfirm->findByID($id);
    $DBEmailConfirm->send = 'TRUE';
    $DBEmailConfirm->save();
  }

}

/**
 * Send email.
 *
 * @param string $email
 * @param string $subject
 * @param string $msg
 * @param string $from
 * @return boolean TRUE if the mail was successfully accepted for delivery, FALSE otherwise.
 */
function send_mail($email, $subject, $msg, $from) {
  $headers = "MIME-Version: 1.0\n";
  $headers .= "Content-type: text/plain; charset=utf-8\n";
  $headers .= "From: $from <$from>\n";
  $rv = mail($email,$subject,$msg,$headers);
  return $rv;
}

/**
 * Update database table TABLE_EMAIL_CONFIRM on confirmation receive.
 *
 * @param string $id The primary key of submission
 */
function update_email_confirm_receive($id){
  $DBEmailConfirm = new DBEmailConfirm();
  $DBEmailConfirm->findByID($id);
  $DBEmailConfirm->confirmed = 'TRUE';
  $DBEmailConfirm->nb_confirm = $DBEmailConfirm->nb_confirm + 1;
  $DBEmailConfirm->save();
}

/**
 * Update database table TABLE_LOG on confirmation receive.
 *
 * @param string $id The primary key of submission
 * @param array $_SERVER Variables set by the web server related to the execution environment of the current script.
 */
function update_log_email_confirm_receive($id, $_SERVER){
  $DBLog = new DBLog();
  $DBLog->findByID($id);
  if ( $DBLog->since_confirm == NULL ) {
    $DBLog->since_confirm = time();
    $DBLog->ip_confirm = $_SERVER["REMOTE_ADDR"];
    $DBLog->agent_confirm = $_SERVER["HTTP_USER_AGENT"];
    $DBLog->referer_confirm = $_SERVER["HTTP_REFERER"];
    $DBLog->save();
    increment_counter(COUNTER_CONFIRM_RECEIVE);
  }
}

/**
 * Check if $email is valid email.
 *
 * @param string $email The email to check
   @return integer codes:
   Return codes:
   0: appears to be a valid email
   1: Not Valid didn't match pattern of a valid email
   2: Not valid no DNS records found
 */
function is_valid_email($email) {
  if ( strlen($email) <= 6 || ! strpos($email, '.') || ! strpos($email, '@') ) {
    return 1;
  }
  if ( function_exists('checkdnsrr') ) {
    # $grab[0] is the whole address
    # $grab[1] is the domain
    $grab = explode("@", $email);
    $domain = $grab[1];
    if ( checkdnsrr($domain) == FALSE ){
      return 2;
    }
  }
  # If it didn't return yet, it's invalid, even though it passed the preg.
  return 0;
}

?>
