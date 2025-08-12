<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: image_utils.php                                      *
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

function init_convert_options() {
  global $config, $lang;

  $convert_options = array(
    "convert_error" => 0,
    "convert_tool" => $config['convert_tool'],
    "convert_path" => ereg_replace("\/$", "", $config['convert_tool_path'])
  );
  switch($config['convert_tool']) {
  case "im":
    $convert_options['convert_path'] = check_executable($convert_options['convert_path']);
    $convert_options['convert_path'] = preg_replace("/".check_executable("mogrify")."$/i", check_executable("convert"), $convert_options['convert_path']);
    if (!@is_executable($convert_options['convert_path'])) {
      $convert_options['convert_error'] = "<b class=\"marktext\">".$lang['im_error']."</b><br />\n".$lang['check_module_settings'];
    }
    break;
  case "gd":
    if (!extension_loaded("gd")) {
      $convert_options['convert_error'] = "<b class=\"marktext\">".$lang['gd_error']."</b><br />\n".$lang['check_module_settings'];
    }
    break;
  case "netpbm":
    if (!@is_executable($convert_options['convert_path']."/".check_executable("pnmscale"))) {
      $convert_options['convert_error'] = "<b class=\"marktext\">".$lang['netpbm_error']."</b><br />\n".$lang['check_module_settings'];
    }
    break;
  default:
    $convert_options['convert_error'] = "<b class=\"marktext\">".$lang['no_convert_module']."</b><br />\n".$lang['check_module_settings'];
  }
  return $convert_options;
}

function resize_image_gd($src, $dest, $quality, $width, $height, $image_info) {
  global $convert_options;

  $types = array(1 => "gif", 2 => "jpeg", 3 => "png");
  if (defined('CONVERT_IS_GD2') && CONVERT_IS_GD2 == 1) {
    $thumb = imagecreatetruecolor($width, $height);
  }
  else {
    $thumb = imagecreate($width, $height);
  }
  $image_create_handle = "imagecreatefrom".$types[$image_info[2]];
  if ($image = $image_create_handle($src)) {
    if (defined('CONVERT_IS_GD2') && CONVERT_IS_GD2 == 1) {
      imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));
    }
    else {
      imagecopyresized($thumb, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));
    }
    $image_handle = "image".$types[$image_info[2]];
    $image_handle($thumb, $dest, $quality);
    imagedestroy($image);
    imagedestroy($thumb);
  }
  return (file_exists($dest)) ? 1 : 0;
}

function resize_image_im($src, $dest, $quality, $width, $height, $image_info) {
  global $convert_options;

  $command = $convert_options['convert_path']." -quality ".$quality." -antialias -sample $width"."x"."$height \"$src\" \"$dest\"";
  system($command);
  return (file_exists($dest)) ? 1 : 0;
}

function resize_image_netpbm($src, $dest, $quality, $width, $height, $image_info) {
  global $convert_options;

  $convert_path = $convert_options['convert_path'];
  $types = array(1 => "gif", 2 => "jpeg", 3 => "png");
  $target = ($width > $height) ? $width : $height;
  $command = $convert_path."/".check_executable($types[$image_info[2]]."topnm")." ".$src." | ".$convert_path."/".check_executable("pnmscale")." --quiet -xysize ".$target." ".$target." | ";
  if ($image_info[2] == 1) {
    $command .= $convert_path."/".check_executable("ppmquant")." 256 | " . $convert_path."/".check_executable("ppmtogif")." > ".$dest;
  }
  elseif ($image_info[2] == 3) {
    $command .= $convert_path."/".check_executable("pnmtopng")." > ".$dest;
  }
  else {
    $jpeg_exec = (file_exists($convert_path."/".check_executable("pnmtojpeg"))) ? check_executable("pnmtojpeg") : check_executable("ppmtojpeg");
    $command .= $convert_path."/".$jpeg_exec." --quality=".$quality." > ".$dest;
  }
  system($command);
  return (file_exists($dest)) ? 1 : 0;
}

function get_width_height($dimension, $width, $height, $resize_type = 1) {
  if ($resize_type == 2) {
    $new_width = $dimension;
    $new_height = floor(($dimension/$width) * $height);
  }
  elseif ($resize_type == 3) {
    $new_width = floor(($dimension/$height) * $width);
    $new_height = $dimension;
  }
  else {
    $ratio = $width / $height;
    if ($ratio > 1) {
      $new_width = $dimension;
      $new_height = floor(($dimension/$width) * $height);
    }
    else {
      $new_width = floor(($dimension/$height) * $width);
      $new_height = $dimension;
    }
  }
  return array("width" => $new_width, "height" => $new_height);
}

function create_thumbnail($src, $dest, $quality, $dimension, $resize_type) {
  global $convert_options;

  if (file_exists($dest)) {
    @unlink($dest);
  }
  $image_info = (defined("IN_CP")) ? getimagesize($src) : @getimagesize($src);
  if (!$image_info) {
    return false;
  }
  $width_height = get_width_height($dimension, $image_info[0], $image_info[1], $resize_type);
  $resize_handle = "resize_image_".$convert_options['convert_tool'];
  if ($resize_handle($src, $dest, $quality, $width_height['width'], $width_height['height'], $image_info)) {
    @chmod($dest, CHMOD_FILES);
    return true;
  }
  else {
    return false;
  }
}

function resize_image($file, $quality, $dimension, $resize_type = 1) {
  global $convert_options;
  $image_info = (defined("IN_CP")) ? getimagesize($file) : @getimagesize($file);
  if (!$image_info) {
    return false;
  }
  $file_bak = $file.".bak";
  if (!rename($file, $file_bak)) {
    return false;
  }
  $width_height = get_width_height($dimension, $image_info[0], $image_info[1], $resize_type);
  $resize_handle = "resize_image_".$convert_options['convert_tool'];
  if ($resize_handle($file_bak, $file, $quality, $width_height['width'], $width_height['height'], $image_info)) {
    @chmod($file, CHMOD_FILES);
    @unlink($file_bak);
    return true;
  }
  else {
    rename($file_bak, $file);
    return false;
  }
}
?>