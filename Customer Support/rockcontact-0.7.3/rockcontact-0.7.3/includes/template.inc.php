<?
/***************************************************************************
 *                             template.inc.php
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
require_once("common.inc.php");

/**
 * Transform $template, replace all occurrence of array $arguments
 *
 * @param string $template The template
 * @param array $arguments The arguments
 * @return string The template transformed.
 */
function template_transform($template, $arguments) {
  global $cfg, $lc;

  array_add($arguments, "LC", $lc);
  array_add($arguments, "NOW_DATE", date(DATE_FORMAT, time()));
  array_add($arguments, "NOW_YEAR", date("Y", time()));
  array_add($arguments, "IMAGE_WEB_DIR", get_custom_images_dir());
  $arguments = array_merge($arguments, $cfg);

  // First for message
  foreach ($arguments as $k => $v) {
    if ($v == null) $v = "";
    $template = str_replace("{".$k."}", $v, $template);
  }

  // Second for variable in message
  foreach ($arguments as $k => $v) {
    if ($v == null) $v = "";
    $template = str_replace("{".$k."}", $v, $template);
  }

  return $template;
}

/**
 * Add "VALUE_FIELD_ID" on between of all values in array.
 *
 * @param array $arr The array
 * @return array The array modified.
 * @see VALUE_FIELD_ID
 */
function transform_to_value_array($arr){
  $keys = array_keys($arr);
  $rv = array();
  for($x = 0; $x < count($keys); $x++){
    array_add( $rv, VALUE_FIELD_ID . $keys[$x], htmlentities($arr[$keys[$x]], ENT_QUOTES, 'UTF-8'));
  }
  return $rv;
}

/**
 * Get images directory.
 *
 * @return string Return custom images directory if exist or default images directory.
 * @see IMAGE_DIR
 * @see CUSTOM_DIR
 */
function get_custom_images_dir(){
  $custom_dir = INSTALL_DIR . IMAGE_DIR . CUSTOM_DIR;
  if ( file_exists($custom_dir) )
    return IMAGE_DIR . CUSTOM_DIR;
  else
    return IMAGE_DIR;
}

/**
 * Get custom filename.
 *
 * @return string Return custom filename if exist or default filename.
 * @see CUSTOM_DIR
 */
function get_custom_filename($filename){
  $real_filename = realpath($filename);
  $dir = dirname($real_filename);
  $file = basename($real_filename);
  $custom_template = $dir . CUSTOM_DIR ."/". $file;
  if ( file_exists($custom_template) )
    return $custom_template;
  else
    return $filename;
}

/**
 * Add header and footer in $template with language $lc.
 *
 * @param string $template The template page
 * @param string $lc The ISO639 code for language used
 * @return string Return $template with header and footer added.
 */
function template_add_header_footer($template, $lc) {
  $rv = template_add_header($template,$lc);
  $rv = template_add_footer($rv,$lc);
  return $rv;
}

/**
 * Add header in $template with language $lc.
 *
 * @param string $template The template page
 * @param string $lc The ISO639 code for language used
 * @return string Return $template with header added.
 */
function template_add_header($template, $lc) {
  require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/header.php"));
  $header = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/header.tpl") );
  $trans = template_transform($header, $msg);
  $rv = str_replace("{PAGE_HEADER}", $trans, $template);
  return $rv;
}

/**
 * Add footer in $template with language $lc.
 *
 * @param string $template The template page
 * @param string $lc The ISO639 code for language used
 * @return string Return $template with footer added.
 */
function template_add_footer($template, $lc) {
  require_once(get_custom_filename(LANGUAGE_DIR. "/". $lc ."/footer.php"));
  $footer = file_get_contents(get_custom_filename(TEMPLATE_DIR. "/footer.tpl"));
  $trans = template_transform($footer, $msg);
  $rv = str_replace("{PAGE_FOOTER}", $trans, $template);
  return $rv;
}

?>
