<?php

class cvis {

function out_pic($img) {
  global $err,$conf;

  Header("Expires: Mon, 26 Jul 1980 05:00:00 GMT");
  Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  Header("Pragma: no-cache"); // HTTP/1.0
  Header("Content-type: image/gif");

  $this->locate($img);
}

function out_digits($img,$flag,$d1,$d2,$d3,$color) {
  global $err,$conf;

  Header("Expires: Mon, 26 Jul 1980 05:00:00 GMT");
  Header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
  Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  Header("Pragma: no-cache"); // HTTP/1.0

  if(function_exists('ImageTypes')) {
    if(ImageTypes() & IMG_PNG) {
      Header("Content-type: image/png");
      $im = imagecreatefrompng('./style/'.$conf->style.'/image/buttons/'.$img.'.png');
    }
    elseif(ImageTypes() & IMG_GIF) {
      Header("Content-type: image/gif");
      $im = imagecreatefromgif('./style/'.$conf->style.'/image/buttons/'.$img.'.gif');
    }
    else {$err->reason('cvis.php|out_digits|can\'t found supported graphic format');return;}

    $c3=$color & 0xFF;
    $color/=0x100;
    $c2=$color & 0xFF;
    $color/=0x100;
    $c1=$color & 0xFF;
    $col = ImageColorAllocate($im, $c1, $c2, $c3);

    if(($flag==1)||($flag==2)||($flag==3)) {
      $px = (imagesx($im)-3-5*strlen($d1));
      ImageString($im,1,$px,3,$d1,$col);
    }
    elseif(($flag==4)||($flag==5)||($flag==6)) {
      $px = (imagesx($im)-3-5*strlen($d1));
      ImageString($im,1,$px,3,$d1,$col);
      $px = (imagesx($im)-3-5*strlen($d2));
      ImageString($im,1,$px,11,$d2,$col);
    }
    elseif(($flag==7)||($flag==8)||($flag==9)) {
      $px = (imagesx($im)-3-5*strlen($d1));
      ImageString($im,1,$px,3,$d1,$col);
      $px = (imagesx($im)-3-5*strlen($d2));
      ImageString($im,1,$px,11,$d2,$col);
      $px = (imagesx($im)-3-5*strlen($d3));
      ImageString($im,1,$px,19,$d3,$col);
    }

    ImagePNG($im);
    ImageDestroy($im);
    exit;
  }

  $err->reason('cvis.php|out_digits|function ImageTypes does not exist');
  $err->log_out();

  Header("Content-type: image/gif");
  $this->locate($img);
}

function locate($img) {
  global $err,$conf,$HTTP_SERVER_VARS;

  if(isset($GLOBALS['SERVER_SOFTWARE'])) $software=$GLOBALS['SERVER_SOFTWARE'];
  elseif(isset($HTTP_SERVER_VARS['SERVER_SOFTWARE'])) $software=$HTTP_SERVER_VARS['SERVER_SOFTWARE'];
  else $software='';

  //IIS break down all headers after relocation
  if(preg_match("/iis/i",$software)) {
    if(function_exists('ImageTypes')) {
      if(ImageTypes() & IMG_PNG) {
        $im = imagecreatefrompng('./style/'.$conf->style.'/image/buttons/'.$img.'.png');
        ImagePNG($im);
        ImageDestroy($im);
      }
      elseif(ImageTypes() & IMG_GIF) {
        $im = imagecreatefromgif('./style/'.$conf->style.'/image/buttons/'.$img.'.gif');
        ImageGIF($im);
        ImageDestroy($im);
      }
      else @readfile('./style/'.$conf->style.'/image/buttons/'.$img.'.gif');
    }
    else @readfile('./style/'.$conf->style.'/image/buttons/'.$img.'.gif');
  }
  else {
    Header('Location: ./style/'.$conf->style.'/image/buttons/'.$img.'.gif');
  }

  exit;
}

}

?>
