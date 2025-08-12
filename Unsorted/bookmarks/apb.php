<?

//####################################################################
// Active PHP Bookmarks - lbstone.com/apb/
//
// Filename:    apb.php
// Authors:     L. Brandon Stone (lbstone.com)
//              Nathanial P. Hendler (retards.org)
//
// 2003-03-11   Added security check. [LBS]
// 2002-02-07   Rearranged the order of things, added some
//              additional comments. [LBS]
// 2001-09-04   Starting on version 1.0 [NPH] [LBS]
//
//####################################################################

//////////////////////////////////////////////////////////////////////
// Security check.
//////////////////////////////////////////////////////////////////////

if ($HTTP_COOKIE_VARS["DOCUMENT_ROOT"] ||
    $HTTP_POST_VARS["DOCUMENT_ROOT"] ||
    $HTTP_GET_VARS["DOCUMENT_ROOT"])
{ exit(); }

//////////////////////////////////////////////////////////////////////
// Database configuration.
//////////////////////////////////////////////////////////////////////

// Change these vars so that you can connect to your database.
$APB_SETTINGS['apb_host']     = "";
$APB_SETTINGS['apb_database'] = "";
$APB_SETTINGS['apb_username'] = "";
$APB_SETTINGS['apb_password'] = "";

// If you would like to set your database variables from an external
// file, link to that from here.
//include("/var/www/apb_db_config.php");

//////////////////////////////////////////////////////////////////////
// Paths and URLs.
//////////////////////////////////////////////////////////////////////

// Change the apb_dir_name, if you want the program to run somewhere other
// than the "bookmarks", directory.  By default this value is 'bookmarks/'.
// If you want to run APB from 'http://www.yoursite.com/bm/', just change
// this value to 'bm/'.
$APB_SETTINGS['apb_dir_name'] = 'bookmarks/';

// There is usually no reason to change these.
$APB_SETTINGS['apb_url']   = 'http://' . $HTTP_HOST . '/' . $APB_SETTINGS['apb_dir_name'];
$APB_SETTINGS['home_url']  = $APB_SETTINGS['apb_url'];
$APB_SETTINGS['apb_path']  = $DOCUMENT_ROOT . '/' . $APB_SETTINGS['apb_dir_name'];
$APB_SETTINGS['log_path']  = $APB_SETTINGS['apb_path'] . 'apb.log';
$APB_SETTINGS['view_group_path'] = $APB_SETTINGS['apb_url'] . "view_group.php";
$APB_SETTINGS['daily_browsing_public'] = 0;

//////////////////////////////////////////////////////////////////////
// Global settings.
//////////////////////////////////////////////////////////////////////

// Change these at your own risk.  (These will be documented after
// they've been fully tested.)
$APB_SETTINGS['template']  = 'default';
$APB_SETTINGS['auth_type'] = 'cookie';  // 'httpd' or 'cookie'
$APB_SETTINGS['limit']     = 5;
$APB_SETTINGS['debug']     = 0;

//////////////////////////////////////////////////////////////////////
// Load the program libraries.
//////////////////////////////////////////////////////////////////////

include_once($APB_SETTINGS['apb_path']."apb_common.php");

?>