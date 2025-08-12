<?php 

include("../../../config.php");
// ================================================
// tinymce PHP WYSIWYG editor control
// ================================================
// Configuration file
// ================================================
// Developed: j-cons.com, mail@j-cons.com
// Copyright: j-cons (c)2004 All rights reserved.
// ------------------------------------------------
//                                   www.j-cons.com
// ================================================
// v.1.0, 2004-10-04
// ================================================

// directory where tinymce files are located
$imagedirectory = $imagedir;
define("IMAGE_DIR", $basedir.$imagedirectory);
define("IMAGE_URL", "$imageurl");

$tinyMCE_dir = '__jscripts/tinymce/';  // not used??? FDx
// calculation of base document root (useful if safe_mode is on or server does not give the appropriate info)
$length = strlen($tinyMCE_dir);
$p_root = $_SERVER['PATH_TRANSLATED'];
$r_root = DirName($p_root);
$root = substr($r_root,0,-$length);
if (!ereg('/$',$root))
  $root = $root.'/';
else
  $root = $root;

  
$root = $basedir.'/';
$tinyMCE_root = $root.$tinyMCE_dir;
  
// base url for images - must match with base document root
//$tinyMCE_base_url = 'http://yoursite.com/';
$tinyMCE_base_url = $websiteurl;
/*
if (!ereg('/$', $_SERVER['DOCUMENT_ROOT']))
  $tinyMCE_root = $_SERVER['DOCUMENT_ROOT'].$tinyMCE_dir; // not used??? FDx
else
  $tinyMCE_root = $_SERVER['DOCUMENT_ROOT'].substr($tinyMCE_dir,1,strlen($tinyMCE_dir)-1);  
*/

// image library related config

// allowed extentions for uploaded image files
$tinyMCE_valid_imgs = array('gif', 'jpg', 'jpeg', 'png', 'swf');

// allow upload in image library
$tinyMCE_upload_allowed = true;
?>