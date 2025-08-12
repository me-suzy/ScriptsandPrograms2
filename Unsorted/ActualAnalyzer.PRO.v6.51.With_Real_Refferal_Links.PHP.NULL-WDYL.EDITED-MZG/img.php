<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './count/cvis.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('img.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//visualisation
$cvis = & new cvis;
if($err->flag) {
  $err->reason('img.php||constructor of cvis class has failed');
  $err->log_out();
  $err->scr_pic();
  exit;
}

//get color and flag
if(isset($GLOBALS['img'])) $img=$GLOBALS['img'];
elseif(isset($HTTP_GET_VARS['img'])) $img=$HTTP_GET_VARS['img'];
else $img=2;
if(isset($GLOBALS['color'])) $dcolor=$GLOBALS['color'];
elseif(isset($HTTP_GET_VARS['color'])) $dcolor=$HTTP_GET_VARS['color'];
else $dcolor=0;
if(isset($GLOBALS['flag'])) $dflag=$GLOBALS['flag'];
elseif(isset($HTTP_GET_VARS['flag'])) $dflag=$HTTP_GET_VARS['flag'];
else $dflag=7;

//out button
if($img>100) {
  $cvis->out_digits($img,$dflag,123456789,12345,123,$dcolor);
  if($err->flag) {
    $err->reason('img.php||can not create button with digits');
    $err->log_out();
    $err->scr_pic();
    exit;
  }
}
else {
  $cvis->out_pic($img);
  if($err->flag) {
    $err->reason('img.php||can not create simple button');
    $err->log_out();
    $err->scr_pic();
    exit;
  }
}

?>
