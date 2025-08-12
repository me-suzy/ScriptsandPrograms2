<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: global.php                                           *
 *        Copyright: (C) 2002 Jan Sorgalla                                *
 *            Email: jan@4homepages.de                                    *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.7                                                  *
 *                                                                        *
 *    Never released without support from: Nicky (http://www.nicky.net)   *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/
if (!defined('ROOT_PATH')) {
  die("Security violation");
}

error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_magic_quotes_runtime(0);
$start_time = microtime();

function addslashes_array($array) {
  foreach ($array as $key => $val) {
    $array[$key] = (is_array($val)) ? addslashes_array($val) : addslashes($val);
  }
  return $array;
}

if (get_magic_quotes_gpc() == 0) {
  $HTTP_GET_VARS = addslashes_array($HTTP_GET_VARS);
  $HTTP_POST_VARS = addslashes_array($HTTP_POST_VARS);
  $HTTP_COOKIE_VARS = addslashes_array($HTTP_COOKIE_VARS);
}

$cat_cache = array();
$cat_parent_cache = array();
$new_image_cache = array();
$session_info = array();
$user_info = array();
$user_access = array();
$config = array();
$lang = array();
$mime_type_match = array();
$additional_image_fields = array();
$additional_user_fields = array();
$additional_urls = array();
$global_info = array();
$auth_cat_sql = array();
unset($self_url);
unset($url);
unset($script_url);
unset($db_servertype);
@include(ROOT_PATH.'config.php');

if (!defined("4IMAGES_ACTIVE")) {
  header("Location: ".ROOT_PATH."install.php");
}

include(ROOT_PATH.'includes/constants.php');

if (isset($HTTP_GET_VARS['action']) || isset($HTTP_POST_VARS['action'])) {
  $action = (isset($HTTP_GET_VARS['action'])) ? stripslashes(trim($HTTP_GET_VARS['action'])) : stripslashes(trim($HTTP_POST_VARS['action']));
}
else {
  $action = "";
}

if (isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode'])) {
  $mode = (isset($HTTP_GET_VARS['mode'])) ? stripslashes(trim($HTTP_GET_VARS['mode'])) : stripslashes(trim($HTTP_POST_VARS['mode']));
}
else {
  $mode = "";
}

if (isset($HTTP_GET_VARS[URL_CAT_ID]) || isset($HTTP_POST_VARS[URL_CAT_ID])) {
  $cat_id = (isset($HTTP_GET_VARS[URL_CAT_ID])) ? intval($HTTP_GET_VARS[URL_CAT_ID]) : intval($HTTP_POST_VARS[URL_CAT_ID]);
}
else {
  $cat_id = 0;
}

if (isset($HTTP_GET_VARS[URL_IMAGE_ID]) || isset($HTTP_POST_VARS[URL_IMAGE_ID])) {
  $image_id = (isset($HTTP_GET_VARS[URL_IMAGE_ID])) ? intval($HTTP_GET_VARS[URL_IMAGE_ID]) : intval($HTTP_POST_VARS[URL_IMAGE_ID]);
}
else {
  $image_id = 0;
}

if (isset($HTTP_GET_VARS[URL_ID]) || isset($HTTP_POST_VARS[URL_ID])) {
  $id = (isset($HTTP_GET_VARS[URL_ID])) ? intval($HTTP_GET_VARS[URL_ID]) : intval($HTTP_POST_VARS[URL_ID]);
}
else {
  $id = 0;
}

if (isset($HTTP_GET_VARS[URL_PAGE]) || isset($HTTP_POST_VARS[URL_PAGE])) {
  $page = (isset($HTTP_GET_VARS[URL_PAGE])) ? intval($HTTP_GET_VARS[URL_PAGE]) : intval($HTTP_POST_VARS[URL_PAGE]);
  if (!$page) {
    $page = 1;
  }
}
else {
  $page = 1;
}

//-----------------------------------------------------
//--- Start DB ----------------------------------------
//-----------------------------------------------------
include(ROOT_PATH.'includes/db_'.strtolower($db_servertype).'.php');
$site_db = new Db($db_host, $db_user, $db_password, $db_name);

//-----------------------------------------------------
//--- Generate Setting --------------------------------
//-----------------------------------------------------
$sql = "SELECT setting_name, setting_value
        FROM ".SETTINGS_TABLE;
$result = $site_db->query($sql);
if (!$result) {
  echo $lang['no_settings'];
  exit;
}
while ($row = $site_db->fetch_array($result)) {
  $config[$row['setting_name']] = $row['setting_value'];
}
$site_db->free_result();

$config['allowed_mediatypes'] = str_replace(" ", "", $config['allowed_mediatypes']);
$config['allowed_mediatypes_array'] = explode(",", $config['allowed_mediatypes']);
$config['allowed_mediatypes_match'] = str_replace(",", "|", $config['allowed_mediatypes']);

$msg = "";
$clickstream = "";
define('MEDIA_PATH', ROOT_PATH.MEDIA_DIR);
define('THUMB_PATH', ROOT_PATH.THUMB_DIR);
define('MEDIA_TEMP_PATH', ROOT_PATH.MEDIA_TEMP_DIR);
define('THUMB_TEMP_PATH', ROOT_PATH.THUMB_TEMP_DIR);
define('TEMPLATE_PATH', ROOT_PATH.TEMPLATE_DIR."/".$config['template_dir']);
define('ICON_PATH', ROOT_PATH.TEMPLATE_DIR."/".$config['template_dir']."/icons");

//-----------------------------------------------------
//--- Useful Stuff ------------------------------------
//-----------------------------------------------------
if (empty($PHP_SELF)) {
  if (!empty($HTTP_SERVER_VARS['PATH_INFO'])) {
    $PHP_SELF = $HTTP_SERVER_VARS['PATH_INFO'];
  }
  elseif (!empty($HTTP_SERVER_VARS['PHP_SELF'])) {
    $PHP_SELF = $HTTP_SERVER_VARS["PHP_SELF"];
  }
  elseif (!empty($HTTP_ENV_VARS['PHP_SELF'])) {
    $PHP_SELF = $HTTP_ENV_VARS["PHP_SELF"];
  }
  else {
    $PHP_SELF = getenv("SCRIPT_NAME");
  }
}

$self_url = basename($PHP_SELF);
if (empty($self_url) || !preg_match("/\.php$/", $self_url)) {
  $self_url = "index.php";
}

//if (getenv("QUERY_STRING")) {
//  $self_url .= "?".getenv("QUERY_STRING");
//  $self_url = preg_replace(array("/([?|&])action=[^?|&]*/", "/([?|&])mode=[^?|&]*/", "/([?|&])phpinfo=[^?|&]*/", "/([?|&])printstats=[^?|&]*/", "/[?|&]".URL_ID."=[^?|&]*/", "/[?|&]l=[^?|&]*/", "/[&?]+$/"), array("", "", "", "", "", "", ""), $self_url);
//}
//else {
  if (preg_match("/details.php/", $self_url) && !preg_match("/[?|&]".URL_IMAGE_ID."=[^?|&]*/", $self_url) && $image_id) {
    $self_url .= "?".URL_IMAGE_ID."=".$image_id;
  }
  elseif (preg_match("/categories.php/", $self_url) && !preg_match("/[?|&]".URL_CAT_ID."=[^?|&]*/", $self_url)) {
    $self_url .= "?".URL_CAT_ID."=".$cat_id;
  }
  if (isset($show_result) && $show_result) {
    $self_url .= preg_match("/\?/", $self_url) ? "&amp;" : "?";
    $self_url .= "show_result=1";
  }
  if ($page && $page != 1) {
    $self_url .= preg_match("/\?/", $self_url) ? "&amp;" : "?";
    $self_url .= URL_PAGE."=".$page;
  }
//}

if (isset($HTTP_GET_VARS['url']) || isset($HTTP_POST_VARS['url'])) {
  $url = (isset($HTTP_GET_VARS['url'])) ? trim($HTTP_GET_VARS['url']) : trim($HTTP_POST_VARS['url']);
}
else {
  $url = "";
}
if (empty($url)) {
  $url = basename(getenv("HTTP_REFERER"));
}
else {
  if ($url == getenv("HTTP_REFERER")) {
    $url = "index.php";
  }
}
$url = preg_replace(array("/[?|&]action=[^?|&]*/", "/[?|&]mode=[^?|&]*/", "/[?|&]".URL_ID."=[^?|&]*/", "/[?|&]l=[^?|&]*/", "/[&?]+$/"), array("", "", "", "", ""), $url);
if ($url == $self_url || $url == "" || !preg_match("/\.php/", $url)) {
  $url = "index.php";
}

if (defined("SCRIPT_URL") && SCRIPT_URL != "") {
  $script_url = SCRIPT_URL;
}
else {
  $port = (!preg_match("/^(80|443)$/", getenv("SERVER_PORT"), $port_match)) ? ":".getenv("SERVER_PORT") : "";
  $script_url  = ($port_match[1] == 443) ? "https://" : "http://";
  $script_url .= (!empty($HTTP_SERVER_VARS['HTTP_HOST'])) ? $HTTP_SERVER_VARS['HTTP_HOST'] : getenv("SERVER_NAME");
  $script_url .= $port;
  $script_url .= (dirname($PHP_SELF) != "/") ? dirname($PHP_SELF) : "";
}

//-----------------------------------------------------
//--- Templates ---------------------------------------
//-----------------------------------------------------
include(ROOT_PATH.'includes/template.php');
$site_template = new Template(TEMPLATE_PATH);

$config['language_dir_default'] = $config['language_dir'];
if (isset($HTTP_GET_VARS['l']) || isset($HTTP_POST_VARS['l'])) {
  $l = (isset($HTTP_GET_VARS['l'])) ? trim($HTTP_GET_VARS['l']) : trim($HTTP_POST_VARS['l']);
  if (file_exists(ROOT_PATH.'lang/'.$l.'/main.php')) {
    $config['language_dir'] = $l;
  }
}

include(ROOT_PATH.'lang/'.$config['language_dir'].'/main.php');
include(ROOT_PATH."includes/db_field_definitions.php");
require(ROOT_PATH.'includes/auth.php');
include(ROOT_PATH.'includes/functions.php');

//-----------------------------------------------------
//--- GZip Compression --------------------------------
//-----------------------------------------------------
$do_gzip_compress = 0;
if ($config['gz_compress'] == 1 && !isset($nozip)) {
  if (get_php_version() >= 40004) {
    if (extension_loaded("zlib")) {
      ob_start("ob_gzhandler");
    }
  }
  elseif (get_php_version() > 40000) {
    if (eregi("gzip", $HTTP_SERVER_VARS["HTTP_ACCEPT_ENCODING"]) || eregi("x-gzip", $HTTP_SERVER_VARS["HTTP_ACCEPT_ENCODING"])) {
      if (extension_loaded("zlib")) {
        $do_gzip_compress = 1;
        ob_start();
        ob_implicit_flush(0);
      }
    }
  }
}

if (defined("GET_CACHES")) {
  $new_cutoff = time() - (60 * 60 * 24 * $config['new_cutoff']);
  $sql = "SELECT c.cat_id, c.cat_name, c.cat_description, c.cat_parent_id, c.cat_hits, c.cat_order, c.auth_viewcat, c.auth_viewimage, c.auth_download, c.auth_upload, c.auth_directupload, c.auth_vote, c.auth_sendpostcard, c.auth_readcomment, c.auth_postcomment, COUNT(i.image_id) AS new_images 
          FROM ".CATEGORIES_TABLE." c 
          LEFT JOIN ".IMAGES_TABLE." i ON (i.cat_id = c.cat_id AND i.image_date >= $new_cutoff AND i.image_active = 1) 
          GROUP BY c.cat_id 
          ORDER BY c.cat_order, c.cat_name ASC";
  $result = $site_db->query($sql);

  while ($row = $site_db->fetch_array($result)) {
    $cat_cache[$row['cat_id']] = $row;
    $cat_parent_cache[$row['cat_parent_id']][] = $row['cat_id'];
    $new_image_cache[$row['cat_id']] = $row['new_images'];
  }
  $site_db->free_result();

  $sql = "SELECT cat_id, COUNT(*) AS num_images 
          FROM ".IMAGES_TABLE." 
          WHERE image_active = 1 
          GROUP BY cat_id";
  $result = $site_db->query($sql);

  while ($row = $site_db->fetch_array($result)) {
    $cat_cache[$row['cat_id']]['num_images'] = $row['num_images'];
  }
  $site_db->free_result();
} //end if GET_CACHES
?>