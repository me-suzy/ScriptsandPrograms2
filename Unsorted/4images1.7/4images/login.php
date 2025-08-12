<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: login.php                                            *
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

$main_template = 0;

$nozip = 1;
define('ROOT_PATH', './');
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');

$error = 0;
if ($user_info['user_level'] != GUEST || empty($HTTP_POST_VARS['user_name']) || empty($HTTP_POST_VARS['user_password'])) {
  if (!ereg("index.php", $url) && !ereg("login.php", $url) && !ereg("register.php", $url) && !ereg("member.php", $url)) {
    header("Location: ".$site_sess->url($url, "&"));
  }
  else {
    header("Location: ".$site_sess->url(ROOT_PATH."index.php", "&"));
  }
}
else {
  $user_name = trim($HTTP_POST_VARS['user_name']);
  $user_password = trim($HTTP_POST_VARS['user_password']);
  $auto_login = (isset($HTTP_POST_VARS['auto_login']) && $HTTP_POST_VARS['auto_login'] == 1) ? 1 : 0;

  if ($site_sess->login($user_name, $user_password, $auto_login)) {
    if (!ereg("index.php", $url) && !ereg("login.php", $url) && !ereg("register.php", $url) && !ereg("member.php", $url)) {
      header("Location: ".$site_sess->url($url, "&"));
    }
    else {
      header("Location: ".$site_sess->url(ROOT_PATH."index.php", "&"));
    }
  }
  else {
    $error = $lang['invalid_login'];
  }
}
if ($error) {
  $main_template = "error";
  include(ROOT_PATH.'includes/page_header.php');
  show_error_page($error);
}
?>