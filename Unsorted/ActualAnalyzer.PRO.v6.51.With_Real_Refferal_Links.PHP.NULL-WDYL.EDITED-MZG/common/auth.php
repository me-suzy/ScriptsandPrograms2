<?php

class auth {

var $rf;

function auth($rf,$script,$title='',$pref='') {
  global $err,$conf,$HTTP_POST_VARS,$HTTP_COOKIE_VARS;
  $vars = array();

  $this->rf=$rf;

  //authentication need?
  if(!strcmp($script,'view')) {
    if($conf->{$pref.'vapass'}) return;
  }

  //get login info
  $uname='';
  if(isset($GLOBALS['uname'])) $uname=$GLOBALS['uname'];
  elseif(isset($HTTP_POST_VARS['uname'])) $uname=$HTTP_POST_VARS['uname'];
  if(empty($uname)) {
    if(isset($GLOBALS['unamef'])) $uname=$GLOBALS['unamef'];
    elseif(isset($HTTP_POST_VARS['unamef'])) $uname=$HTTP_POST_VARS['unamef'];
  }
  if(isset($GLOBALS['passw'])) $passw=$GLOBALS['passw'];
  elseif(isset($HTTP_POST_VARS['passw'])) $passw=$HTTP_POST_VARS['passw'];
  if(empty($passw)) {
    if(isset($GLOBALS['passwf'])) $passw=$GLOBALS['passwf'];
    elseif(isset($HTTP_POST_VARS['passwf'])) $passw=$HTTP_POST_VARS['passwf'];
  }
  if(isset($GLOBALS['aaauth'])) $remc=$GLOBALS['aaauth'];
  elseif(isset($HTTP_COOKIE_VARS['aaauth'])) $remc=$HTTP_COOKIE_VARS['aaauth'];
  else $remc='';

  //check login info
  if(strcmp($uname,$conf->uname)||strcmp($passw,$conf->passw)) {

    $vars['SCRIPT']=$script;
    if(empty($pref)) {
      $vars['SERIES']=$conf->series;
      $vars['SITE']=$conf->site;
      $vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
      $vars['TITLE']='ActualAnalyzer '.$conf->series.' - '.$title;
      $vars['VER']=$conf->version;
    }
    else {
      //for modules
      $vars['SERIES']=$conf->series.' / '.$conf->{$pref.'name'};
      $vars['SITE']=$conf->{$pref.'site'};
      $vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->{$pref.'site'}.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
      $vars['TITLE']='ActualAnalyzer '.$conf->series.' / '.$conf->{$pref.'name'}.' - '.$title;
      $vars['VER']=$conf->{$pref.'version'};
    }
    $this->top($vars);

    require $this->rf.'style/'.$conf->style.'/template/auth.php';
    $vars['HEADER']=_LOGIN;
    $vars['UNAMEDESC']=_UNAME;
    $vars['PASSWDESC']=_PASSWORD;
    $vars['LOGIN']=_LOGIN;
    $vars['REMEMBER']=_REMEMBER;

    //remember
    if(!empty($remc)) {
      $remarr=preg_split("/_/",$remc);
      $vars['UNAME']=$remarr[0];
      if(isset($remarr[1])) $vars['PASSW']=$remarr[1];
      $vars['RSTATUS']=' checked';
    }
    else {
      $vars['RSTATUS']='';
    }

    tparse($auth,$vars);

    $this->bottom($vars);

    //output HTML page
    out();

    exit;
  }
  else {
    //remember
    if(isset($GLOBALS['authpan'])||isset($HTTP_POST_VARS['authpan'])) {
      if(isset($GLOBALS['remlog'])||isset($HTTP_POST_VARS['remlog'])) $rem=$uname.'_'.$passw;
      else $rem='';

      //get path
      $path=$conf->url;
      $path=preg_replace("/^(http:\/\/)([^\/]+)/i",'',$path);
      SetCookie('aaauth',$rem,$conf->ctime+($conf->time1*3000),$path);
    }
  }
}

//top of page
/*-------------------------------------------------------*/
function top(&$vars) {
  global $err,$conf;

  require $this->rf.'style/'.$conf->style.'/template/top.php';
  //globals variables
  $vars['RF']=$this->rf;
  $vars['LANG']=$conf->lang;
  $vars['STYLE']=$conf->style;
  $vars['SCROLL']='';
  $vars['ID']='';
  $vars['P2']='';
  $vars['OLDACT']='';
  $vars['VERSION']=_VERSION;
  $vars['FAQ']=_FAQ;
  $vars['SUPPORT']=_SUPPORT;
  $vars['CHARSET']=_CHARSET;
  $vars['UNAME']='';
  $vars['PASSW']='';

  tparse($top,$vars);
}

//bottom of page
/*-------------------------------------------------------*/
function bottom(&$vars) {
  global $err,$conf;

  require $this->rf.'style/'.$conf->style.'/template/bottom.php';
  tparse($bottom,$vars);
}

}

?>
