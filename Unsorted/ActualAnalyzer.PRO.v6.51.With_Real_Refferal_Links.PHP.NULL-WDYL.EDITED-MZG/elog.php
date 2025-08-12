<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/auth.php';

//HTML code accumulator
$pagehtml='';

//config
$conf = & new config($rf,false);
if($err->flag) {
  $err->reason('elog.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'elog',_ERRSLOG);
if($err->flag) {
  $err->reason('elog.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//get errors
$errs = array();
get_log($errs);
if($err->flag) {
  $err->reason('elog.php||can\'t get log of errors');
  $err->log_out();
  $err->scr_out();
  exit;
}

//create HTML page
out_log($rf);

//output HTML page
out();

//get log of the errors
/*-------------------------------------------------------*/
function get_log() {
  global $errs,$HTTP_POST_VARS;

  $file='errors.php';
  $fileold='errsold.php';
  if(!file_exists($file)) return;

  $extact='';
  if(isset($GLOBALS['extact_h'])) $extact=$GLOBALS['extact_h'];
  elseif(isset($HTTP_POST_VARS['extact_h'])) $extact=$HTTP_POST_VARS['extact_h'];
  if(!strcmp($extact,'clear')) {
    $rez=unlink('./'.$file);
    if(!$rez) {$err->reason('elog.php|get_log|can\'t delete file '.$file);return;}
    if(!file_exists($fileold)) return;
    $rez=unlink('./'.$fileold);
    if(!$rez) {$err->reason('elog.php|get_log|can\'t delete file '.$fileold);return;}
    return;
  }

  $errscur=file($file);
  unset($errscur[0]);

  if(file_exists($fileold)) {
    $errsold=file($fileold);
    unset($errsold[0]);
  }
  else $errsold=array();

  $errs=array_merge($errsold,$errscur);
}

//output errors to screen
/*-------------------------------------------------------*/
function out_log($rf) {
  global $errs,$conf,$HTTP_POST_VARS;

  $frame=20;
  $emax=sizeof($errs);

  if($emax==0) {
    $ebeg=0;
    $lbeg=0;
    $nrec=0;
  }
  else {
    //get position of current page
    if(isset($GLOBALS['listcur'])) $ebeg=$GLOBALS['listcur'];
    elseif(isset($HTTP_POST_VARS['listcur'])) $ebeg=$HTTP_POST_VARS['listcur'];
    else $ebeg=$emax-1;

    //scrolling of the list
    if(isset($GLOBALS['lbeg_x'])||isset($HTTP_POST_VARS['lbeg_x'])) $ebeg=$emax-1;
    elseif(isset($GLOBALS['lllscr_x'])||isset($HTTP_POST_VARS['lllscr_x'])) {
      $ebeg=$ebeg+(10*$frame);
      if($ebeg>=$emax) $ebeg=$emax-1;
    }
    elseif(isset($GLOBALS['llscr_x'])||isset($HTTP_POST_VARS['llscr_x'])) {
      $ebeg=$ebeg+$frame;
      if($ebeg>=$emax) $ebeg=$emax-1;
    }
    elseif(isset($GLOBALS['lrscr_x'])||isset($HTTP_POST_VARS['lrscr_x'])) {
      if(($ebeg-$frame)>=0) $ebeg=$ebeg-$frame;
    }
    elseif(isset($GLOBALS['lrlscr_x'])||isset($HTTP_POST_VARS['lrlscr_x'])) {
      if(($ebeg-(10*$frame))>=0) $ebeg=$ebeg-(10*$frame);
      else $ebeg=(int)($emax-1)%$frame;
    }
    elseif(isset($GLOBALS['lend_x'])||isset($HTTP_POST_VARS['lend_x'])) {
      $ebeg=(int)($emax-1)%$frame;
    }

    $lbeg=$ebeg-$frame+1;
    if($lbeg<0) $lbeg=0;
    $nrec=$ebeg-$lbeg+1;
  }

  //globals variables
  $vars = array();
  $vars['RF']=$rf;
  $vars['SCROLL']='';
  $vars['ID']='';
  $vars['P2']='';
  $vars['OLDACT']='';
  $vars['LANG']=$conf->lang;
  $vars['STYLE']=$conf->style;
  $vars['SCRIPT']='elog';
  $vars['VERSION']=_VERSION;
  $vars['VER']=$conf->version;
  $vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $vars['FAQ']=_FAQ;
  $vars['SUPPORT']=_SUPPORT;
  $vars['CHARSET']=_CHARSET;
  $vars['SERIES']=$conf->series;
  $vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._ERRSLOG;
  $vars['UNAME']=$conf->uname;
  $vars['PASSW']=$conf->passw;
  $vars['SITE']=$conf->site;
  top($vars);

  require './style/'.$conf->style.'/template/et_errs.php';
  $vars['HEADER']=_ERRSINF;
  $vars['SHOWING']=_SHOWING.' '.$nrec.' '._ITEM_S;
  $vars['RANGE']=($emax-$ebeg).' - '.($emax-$lbeg).' '._OUTOF.' ';
  if($emax==0) $vars['RANGE'].='0';
  else $vars['RANGE'].=$emax;
  $vars['CLEAR']=_CLEARLOG;
  $vars['REFRESH']=_REFRESH;
  $vars['LEVEL']=_LEVEL;
  $vars['FILE']=_FILE;
  $vars['FUNCTION']=_FUNCTION;
  $vars['DESCRIPTION']=_DESCRIPTION;
  tparse($top,$vars);

  if($emax==0) {
    $vars['TEXT']=_NORECORDS;
    tparse($empty,$vars);
  }
  else {
    $line=0;
    $e=$ebeg;
    for(;$e>=0;$e--) {
      $line++;
      if($line>$frame) break;
      //array with error data
      $errarr = preg_split("/\|/",$errs[$e]);

      //time of error
      $vars['LEVEL']=_LEVEL;
      $vars['FILE']=_FILE;
      $vars['FUNCTION']=_FUNCTION;
      $vars['DESCRIPTION']=_DESCRIPTION;
      $vars['TIME']=$errarr[0];
      tparse($header,$vars);

      //errors on levels
      $max=sizeof($errarr);
      $level=1;
      $ti=$max-1;
      while(1) {
        $i=$ti;
        for(;$i>0;$i--) if(preg_match("/^(\w+:)*\w+\.php$/i",$errarr[$i])) break;
        if($i>0) {
          $vars['FILE']=$errarr[$i];
          $vars['FUNCT']=$errarr[$i+1];
          $vars['DESC']='';
          for($c=$i+2;$c<=$ti;$c++) $vars['DESC'].=$errarr[$c];
          $vars['LEVEL']=$level;
          $level++;
          tparse($center,$vars);
          $ti=$i-1;
        }
        else break;
      }
    }
    if(($emax-1)>$frame) {
      $vars['LISTCUR']=$ebeg;
      $vars['LBEG']=_STARTOFLIST;
      $vars['LLSCR']=_PREVPG;
      $vars['LRSCR']=_NEXTPG;
      $vars['LEND']=_ENDOFLIST;
      $vars['LLLSCR']=_10PGSBACK;
      $vars['LRLSCR']=_10PGSFORWARD;
      tparse($delimiter,$vars);
    }
  }

  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

  bottom($vars);
}

//top of page
/*-------------------------------------------------------*/
function top(&$vars) {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
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
