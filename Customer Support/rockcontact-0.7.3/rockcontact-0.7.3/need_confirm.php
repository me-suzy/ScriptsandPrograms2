<?
/***************************************************************************
 *                             need_confirm.php
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
require_once("./includes/SQLiteBackEnd.class.php");

increment_counter(COUNTER_PAGE_NEED_CONFIRM);

if( isset($_GET['lc']) || isset($_POST['lc']) ) {
  $lc = ( isset($_POST['lc']) ) ? $_POST['lc'] : $_GET['lc'];
} else {
  $lc = '';
}

if( isset($_GET['id']) || isset($_POST['id']) ) {
  $id = ( isset($_POST['id']) ) ? $_POST['id'] : $_GET['id'];
} else {
  $id = '';
}

if ( ! is_md5($id) )
  return;

$lc = get_page_language($lc);
require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/need_confirm.php"));

$DBLog = new DBLog();
$DBLog->findByID($id);

if( strlen($DBLog->since) == 0 || time() > ($DBLog->since + TICKET_VALID_SECONDE) ){
  increment_counter(COUNTER_ERROR_TICKET_EXPIRE);
  die ("Ticket expired");
}

$DBContact = new DBContact();
$DBContact->findByID($id);

$array_value = array();
$array_value = array_merge($array_value, $msg);
array_add($array_value, "TICKET_ID", $id);
array_add($array_value, "TICKET_VALID_HOURS", round(TICKET_VALID_SECONDE / (60 * 60 )));
array_add($array_value, VALUE_FIELD_ID ."email", $DBContact->email);
array_add($array_value, "REMOTE_ADDR", $_SERVER['REMOTE_ADDR']);
array_add($array_value, "HTTP_USER_AGENT", $_SERVER['HTTP_USER_AGENT']);

array_add($array_value, "DATE_TICKET_OPEN", date(DATE_FORMAT, $DBLog->since));

$tpl = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/need_confirm.tpl"));
$tpl = template_add_header_footer($tpl, $lc);
$tpl = template_transform($tpl, $array_value);

echo $tpl;

?>
