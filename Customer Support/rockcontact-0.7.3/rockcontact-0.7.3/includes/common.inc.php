<?
/***************************************************************************
 *                                common.inc.php
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
require_once("counter.inc.php");

/**
 * Check if require field not empty.
 *
 * @param array $_POST The array with varables
 * @return boolean True if all require field not empty, FALSE otherwise
 * @see REQUIRE_FIELD_ID
 */
function is_valid_require_field($_POST){
  foreach ($_POST as $k => $v) {
  if ( strpos($k, REQUIRE_FIELD_ID) && strlen(trim($v)) == 0)
    return FALSE;
  }
  return TRUE;
}

/**
 * Get integrity checksum for string.
 *
 * @param string $str The string
 * @return string The MD5 checksum
 * @see INTEGRITY_SECRET
 */
function get_integrity_checksum($str){
  $rv = md5(INTEGRITY_SECRET . $str);
  return $rv;
}

/** TODO : Add Browser language detection **/
/**
 * Check if $lc is valid, if not valid return the default language.
 *
 * @param string $str The string
 * @return string The MD5 checksum
 * @see DEFAULT_LANGUAGE
 */
function get_page_language($lc){
  $lc = strtolower($lc);
  $array_language = explode(",", LANGUAGE_SUPPORTED);
  for($x = 0; $x < count($array_language); $x++){
    if(strcmp($array_language[$x],$lc) == 0)
      return $lc;
  }

  return DEFAULT_LANGUAGE;
}

/**
 * Verify if $md5 is valid MD5.
 *
 * @param string $md5 The MD5 string
 * @return boolean TRUE if $md5 is valid MD5, FALSE otherwise
 * @see COUNTER_INVALID_MD5
 */
function is_md5($md5){
  $char_valid = "abcdef0123456789";

  if ( strlen($md5) != 32 ){
    increment_counter(COUNTER_INVALID_MD5);
    return FALSE;
  }

  $md5 = strtolower($md5);
  for ( $x = 0; $x < strlen($md5); $x++){
    if ( strpos($char_valid, $md5[$x]) === FALSE){
      increment_counter(COUNTER_INVALID_MD5);
      return FALSE;
    }
  }

  return TRUE;
}

/**
 * Remove constants part string from keys. Deal with magic quote.
 *
 * @param array $array_field The array to normalize
 * @return array The array normalized
 * @see SUBMISSION_FIELD_ID
 * @see CONTACT_FIELD_ID
 * @see CONTROL_FIELD_ID
 * @see REQUIRE_FIELD_ID
 */
function normalize_fields_submit($array_fields){
  $rv = array();
  foreach ($array_fields as $k => $v) {
    $tmp_key = str_replace( SUBMISSION_FIELD_ID, "", $k);
    $tmp_key = str_replace( CONTACT_FIELD_ID, "", $tmp_key);
    $tmp_key = str_replace( CONTROL_FIELD_ID, "", $tmp_key);
    $tmp_key = str_replace( REQUIRE_FIELD_ID, "", $tmp_key);
    if (get_magic_quotes_gpc()){
      $v = stripslashes($v);
    }
    array_add($rv,$tmp_key,$v);
  }
  return $rv;
}

/**
 * Check if string $str start with $start.
 *
 * @param string $str The string to check
 * @param string $start The string to search
 * @return boolean TRUE if $str start with $start, FALSE otherwise
 */
function is_start_with($str, $start) {
  if (strncasecmp($str, $start, strlen($start)) == 0)
    return TRUE;
  else
    return FALSE;
}

/**
 * Add $key => $value to $array.
 *
 * @param array $array The array
 * @param string $key The $key to add
 * @param mixed $value The value to add
 * @return array The array with new value added
 */
function array_add(&$array, $key, $value){
  $temp = array($key => $value);
  $array = array_merge ($array, $temp);
}

/**
 * Verify if ticket $id is present in database.
 * Prevent using back button in browser for resubmit.
 *
 * @param string $id The id of ticket
 * @return boolean True if $id present in database
 */
function is_ticket_id_used($id){
  $DBSubmission = new DBSubmission();
  $r_id = $DBSubmission->findByID($id);
  if ( $r_id != NULL )
    return TRUE;
  else
    return FALSE;
}

/**
 * Perform all operations for insert in database the new message.
 *
 * @param array $contact_array Contact information
 * @param array $submission_array Message information
 * @param array $_SERVER HTTP Variables set by the web server related to the execution environment of the current script.
 * @return string The primary key of new record create in database
 */
function insert_new_message($contact_array, $submission_array, $_SERVER){
  $id = $submission_array['ticket_id'];

  if ( ! is_md5($id) )
    return;

  // Perform operations for insert new contact submit in table TABLE_CONTACT.
  $DBContact = new DBContact();
  $DBContact->add($id, $contact_array['first_name'], $contact_array['last_name'], $contact_array['email'], $contact_array['pref_lang']);

  // Perform operations for insert new message submit in table TABLE_SUBMISSION.
  $DBSubmission = new DBSubmission();
  $DBSubmission->add($id,$submission_array['subject'],$submission_array['message']);

  // Perform operations for insert new email confirmation in table TABLE_EMAIL_CONFIRM.
  $DBEmailConfirm = new DBEmailConfirm();
  $DBEmailConfirm->add($id);

  //insert new log
  $DBLog = new DBLog();
  $DBLog->add($id,$_SERVER["REMOTE_ADDR"],$_SERVER["HTTP_USER_AGENT"]);

  return $id;
}

/**
 * Check if code is valid with visual code in database.
 *
 * @param string $id The primary key of visual code in database
 * @param string $code The code for compart
 * @return boolean TRUE if visual code in DB is same that $code
 */
function is_valid_visual_confirm($id, $code){
  $DBVisualConfirm = new DBVisualConfirm();
  $DBVisualConfirm->findByID($id);
  if ( strcasecmp($DBVisualConfirm->code, $code) == 0)
    return TRUE;
  else
    return FALSE;
}

/**
 * Check if visual code exist in database, and is not invalidate by time.
 *
 * @param string $id The primary key of visual code in database
 * @return boolean TRUE if visual code is valid
 */
function is_valid_visual_confirm_id($id){
  $DBVisualConfirm = new DBVisualConfirm();
  $r_id = $DBVisualConfirm->findByID($id);
  if ( $r_id != NULL )
    return TRUE;
  else
    return FALSE;
}

/**
 * Fetch the visual code in table TABLE_VISUAL_CONFIRM.
 *
 * @param string $id The primary key of visual code
 * @return string The visual code
 * @see VISUAL_CONFIRM_NB_DIGIT
 */
function get_visual_code_from_id($id){
  $DBVisualConfirm = new DBVisualConfirm();
  $DBVisualConfirm->findByID($id);
  $rv = $DBVisualConfirm->code;
  if (strlen($rv) < VISUAL_CONFIRM_NB_DIGIT){
    return "Expired Code";
  }

  return $rv;
}

?>
