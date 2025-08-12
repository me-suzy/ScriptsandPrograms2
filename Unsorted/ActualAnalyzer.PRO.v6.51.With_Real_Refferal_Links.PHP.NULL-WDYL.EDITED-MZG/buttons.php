<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';

//HTML code accumulator
$pagehtml='';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('buttons.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//window with HTML-code
$vars=array();
$vars['RF']=$rf;
top($vars);
buttons($vars);
if($err->flag) {
  $err->reason('buttons.php||can\'t output window with HTML-code');
  $err->log_out();
  $err->scr_out();
  exit;
}
bottom($vars);

//output HTML page
out();

exit;

//window with buttons
/*-------------------------------------------------------*/
function buttons(&$vars) {
  global $err,$conf;

  require './style/'.$conf->style.'/template/buttons.php';
  $vars['HEADER']=_BUTTONS;
  tparse($begin,$vars);

  //get available buttons
  $buts = array();
  $catalog=opendir('./style/'.$conf->style.'/image/buttons');
  while(($file=readdir($catalog))!=FALSE) {
    if($file!="."&&$file!="..") {
      if(preg_match("/^([0-9]+)\.gif$/i",$file,$matches)) $buts[]=$matches[1];
      elseif(preg_match("/^([0-9]+)\.png$/i",$file,$matches)) $buts[]=$matches[1];
    }
  }
  closedir($catalog);

  //sort
  $buts=array_unique($buts);
  natsort($buts);

  //out available buttons
  $current=0;
  reset($buts);
  while ($e=each($buts)) {
    $current++;
    $vars[$current]=$e[1];
    $col=0;
    if(($e[1]>50)&&($e[1]<100)) $col=16777215;
    if(($e[1]>150)&&($e[1]<200)) $col=16777215;
    $vars['I'.$current]='<img src="./img.php?img='.$e[1].'&color='.$col.'&flag=7">';
    if($current==5) {
      tparse($center,$vars);
      $current=0;
    }
  }

  //fill non-ended string
  if($current>0) {
    for($current++;$current<6;$current++) {
      $vars[$current]='&nbsp;';
      $vars['I'.$current]='&nbsp;';
    }
    tparse($center,$vars);
  }

  $vars['BACKTT']=_BACKTOTOP;
  tparse($end,$vars);
}

//top of page
/*-------------------------------------------------------*/
function top(&$vars) {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
  //globals variables
  $vars['SCROLL']='';
  $vars['ID']='';
  $vars['P2']='';
  $vars['OLDACT']='';
  $vars['LANG']=$conf->lang;
  $vars['STYLE']=$conf->style;
  $vars['SCRIPT']='code';
  $vars['VERSION']=_VERSION;
  $vars['VER']=$conf->version;
  $vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $vars['FAQ']=_FAQ;
  $vars['SUPPORT']=_SUPPORT;
  $vars['CHARSET']=_CHARSET;
  $vars['SERIES']=$conf->series;
  $vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._BUTTONS;
  $vars['UNAME']='';
  $vars['PASSW']='';
  $vars['SITE']=$conf->site;

  tparse($top,$vars);
}

//bottom of page
/*-------------------------------------------------------*/
function bottom(&$vars) {
  global $err,$conf;

  require './style/'.$conf->style.'/template/bottom.php';
  tparse($bottom,$vars);
}

?>
