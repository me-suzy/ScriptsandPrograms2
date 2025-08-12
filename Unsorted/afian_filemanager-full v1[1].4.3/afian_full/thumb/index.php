<?php

require_once("../config.php");
require_once("$config[root_dir]/functions/functions.php");
require_once("$config[root_dir]/functions/compatibility.php");

$img = safeFilename($img);
$dir = stripslashes(safepath($dir));

//set path
$base_dir = $config[base_dir];
if ($dir) {
	$base_dir = $base_dir . $dir;
}




function thumb_getsize($thumb_width,$thumb_height,$original_width,$original_height,$border=0) {
  $min_width = $thumb_width - $border - $border;
  $min_height = $thumb_height - $border - $border;
  $div_width = $original_width / $min_width;
  $div_height = $original_height / $min_height;
  if ($div_width >= $div_height) {
    $res_width = $min_width;
        $res_height = round($original_height / $div_width);
        $res_left = $border;
        $res_top = round(($min_height / 2) - ($res_height / 2) + $border);
  } else {
    $res_height = $min_height;
        $res_width = round($original_width / $div_height);
        $res_top = $border;
        $res_left = round(($min_width / 2) - ($res_width / 2) + $border);
  }
  $result = array($res_left,$res_top,$res_width,$res_height);
  return $result;
}
function thumb_create($original_img,$img_border,$rect,$original_width,$original_height,$border_width,$border_height) {
  $thumbnail = imagecreate($border_width,$border_height);
  imagecopyresized($thumbnail,$img_border,0,0,0,0,$border_width,$border_height,$border_width,$border_height);
  imagecopyresized($thumbnail,$original_img,$rect[0],$rect[1],0,0,$rect[2],$rect[3],$original_width,$original_height);
  return $thumbnail;
}
function thumb_print($dir,$file_thumb,$file_border,$s,$f,$mdir) {

  if ($f or (!(file_exists($mdir.$file_thumb)))) {
    list($width,$height,$pictype) = getimagesize($dir.$file_thumb);
        switch ($pictype) {
          case 1 : $img = imagecreatefromgif($dir.$file_thumb); break;
          case 2 : $img = imagecreatefromjpeg($dir.$file_thumb); break;
          case 3 : $img = imagecreatefrompng($dir.$file_thumb); break;
        }
    list($b_width,$b_height,$b_pictype) = getimagesize($file_border);
        switch ($b_pictype) {
          case 1 : $imgborder = imagecreatefromgif($file_border); break;
          case 2 : $imgborder = imagecreatefromjpeg($file_border); break;
          case 3 : $imgborder = imagecreatefrompng($file_border); break;
        }
    if (($img) && ($imgborder)) {
      $rect = thumb_getsize($b_width,$b_height,$width,$height,$GLOBALS["thumbsborder"]);
      $thumbnail = thumb_create($img,$imgborder,$rect,$width,$height,$b_width,$b_height);
          if ($s) {
            switch ($pictype) {
              case 1 : imagegif($thumbnail,$mdir.$file_thumb); break;
              case 2 : imagejpeg($thumbnail,$mdir.$file_thumb); break;
              case 3 : imagepng($thumbnail,$mdir.$file_thumb); break;
            }
          }
          switch ($pictype) {
            case 1 : imagegif($thumbnail); break;
            case 2 : imagejpeg($thumbnail); break;
            case 3 : imagepng($thumbnail); break;
          }
        }
  } else {
    list(,,$pictype) = getimagesize($mdir.$file_thumb);
        switch ($pictype) {
          case 1 : $img = imagecreatefromgif($mdir.$file_thumb); break;
          case 2 : $img = imagecreatefromjpeg($mdir.$file_thumb); break;
          case 3 : $img = imagecreatefrompng($mdir.$file_thumb); break;
        }
        switch ($pictype) {
          case 1 : imagegif($img); break;
          case 2 : imagejpeg($img); break;
          case 3 : imagepng($img); break;
        }
  }
}


function main($dir,$pic,$dia,$s,$f,$mdir) {
     thumb_print($dir,$pic,$dia,$s,$f,$mdir);
}

$img_dir = "$base_dir/";
$borderpic="$config[root_dir]/thumb/thumb.png";

main($img_dir,$img,$borderpic,$save,$f,$thumbs_dir);
?> 