<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './admin/adb.php';


//HTML code accumulator
$pagehtml='';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('code.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//database
$adb = & new adb;
if($err->flag) {
  $err->reason('admin.php||constructor of adb class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//window with HTML-code
$vars=array();
$vars['RF']=$rf;
top($vars);
text($vars);
if($err->flag) {
  $err->reason('code.php||can\'t output window with HTML-code');
  $err->log_out();
  $err->scr_out();
  exit;
}
bottom($vars);

//output HTML page
out();

exit;

//textarea with HTML code
/*-------------------------------------------------------*/
function text(&$vars) {
  global $err,$conf,$adb,$HTTP_GET_VARS;

  db_init();
  if($err->flag) {$err->reason('code.php|text|initialization of database has failed');return;}

  //get UID of page
  if(isset($GLOBALS['pid'])) $pid=$GLOBALS['pid'];
  elseif(isset($HTTP_GET_VARS['pid'])) $pid=$HTTP_GET_VARS['pid'];
  else {$err->reason('code.php|text|can\'t get pid of page');return;}

  $adb->getparampg($pid,$name,$img,$uid,$url);
  if($err->flag) {$err->reason('code.php|text|can\'t get information about page');return;}

  db_close();
  if($err->flag) {$err->reason('code.php|text|closing of connection with database has failed');return;}

  require './style/'.$conf->style.'/template/codev.php';

  $fname=$name;
  if(strlen($fname)>_CS_HTML) $sname=substr($fname,0,_CS_HTML-3).'...';
  else $sname=$fname;
  $vars['HEADER']=_INDHTML.' '._FORPG.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
  $vars['DESC1']=_HTMLACT;
  tparse($begin,$vars);

  require './data/htmlcode.php';

  //output HTML-code
  $vars['TITLE']=_STATISTICS;
  $vars['VERSION']=$conf->version;
  $vars['URL']=$conf->url;
  $vars['UID']=$uid;
  if($img==1) {
    //transparent pixel
    $vars['WIDTH']=1;
    $vars['HEIGHT']=1;
  }
  else {
    //logo 88x31
    $vars['WIDTH']=88;
    $vars['HEIGHT']=31;
  }
  $vars['COPY']=_COPYTOCLIP;
  tparse($code,$vars);

  tparse($end,$vars);
}

//top of page
/*-------------------------------------------------------*/
function top(&$vars) {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
  //globals variables
  $vars['LANG']=$conf->lang;
  $vars['STYLE']=$conf->style;
  $vars['SCROLL']='';
  $vars['ID']='';
  $vars['P2']='';
  $vars['OLDACT']='';
  $vars['SCRIPT']='code';
  $vars['VERSION']=_VERSION;
  $vars['VER']=$conf->version;
  $vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $vars['FAQ']=_FAQ;
  $vars['SUPPORT']=_SUPPORT;
  $vars['CHARSET']=_CHARSET;
  $vars['SERIES']=$conf->series;
  $vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._INDHTML;
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
