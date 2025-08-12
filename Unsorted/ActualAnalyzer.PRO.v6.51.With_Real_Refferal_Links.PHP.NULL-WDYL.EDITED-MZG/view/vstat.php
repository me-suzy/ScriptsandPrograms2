<?php

class vstat {

var $id;                 //id of page(group)
var $name;               //name of page(group)
var $url;                //url of page
var $act;                //action
var $oldact;             //previous action
var $extact;             //extension of action
var $tint;               //time interval
var $param;              //form parameter
var $param2;             //form parameter 2
var $bpos;               //begin position in list
var $frame;              //count of the lines in current frame of the list
var $listlen;            //lenght of the list
var $vars;               //variables for template
var $rf;                 //root folder

function vstat($rf) {
  global $err,$conf,$vdb;

  //default values
  $this->rf=$rf;
  $this->act='vis_grpg';
  $this->oldact='';
  $this->extact='all';
  $this->tint='total';
  $this->param='';
  $this->param2='';
  $this->frame=20;
  $this->bpos=0;
  $this->listlen=$this->bpos+$this->frame;
  $this->id=221;
  $this->name=_ALL;
  $this->url='';
  $this->vars=array();

  $this->params();
  if($err->flag) {$err->reason('vstat.php|vstat|can\'t get parameters of view form');return;}

  //globals variables
  $this->vars['RF']=$this->rf;
  $this->vars['PREF']='';
  $this->vars['ID']='';
  $this->vars['P2']='';
  $this->vars['OLDACT']=$this->act;
  $this->vars['LISTCUR']=$this->bpos;
  $this->vars['LANG']=$conf->lang;
  $this->vars['STYLE']=$conf->style;
  $this->vars['SCRIPT']='view';
  $this->vars['VERSION']=_VERSION;
  $this->vars['VER']=$conf->version;
  $this->vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $this->vars['FAQ']=_FAQ;
  $this->vars['SUPPORT']=_SUPPORT;
  $this->vars['CHARSET']=_CHARSET;
  $this->vars['SERIES']=$conf->series;
  $this->vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._VIEWAREA;
  $this->vars['UNAME']=$conf->uname;
  $this->vars['PASSW']=$conf->passw;
  $this->vars['SITE']=$conf->site;
  $this->vars['BACKTT']=_BACKTOTOP;

  //call the need function
  if(method_exists($this,$this->act)) {
    eval("\$this->{$this->act}();");
    if($err->flag) {$err->reason('vstat.php|vstat|\''.$this->act.'\' function has failed');return;}
  }
  else {
    $this->vars['SCROLL']='';
    $this->top($this->vars);
    $this->cpanel();

    require $this->rf.'style/'.$conf->style.'/template/vmess.php';

    $this->vars['HEADER']=_INFORMATION;
    $this->vars['MESSAGE']=_RUNAVAIL;
    tparse($top,$this->vars);

    $this->bottom($this->vars);
  }
}

//get parameters
/*-------------------------------------------------------*/
function params() {
  global $err,$conf,$vdb,$HTTP_POST_VARS;

  //get id
  $sflag=0;
  if(isset($GLOBALS['id_h'])) {if(!empty($GLOBALS['id_h'])) {$this->id=$GLOBALS['id_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['id_h'])) {if(!empty($HTTP_POST_VARS['id_h'])) {$this->id=$HTTP_POST_VARS['id_h'];$sflag=1;}}
  if(!$sflag) {
    if(isset($GLOBALS['grpg'])) $this->id=$GLOBALS['grpg'];
    elseif(isset($HTTP_POST_VARS['grpg'])) $this->id=$HTTP_POST_VARS['grpg'];
  }

  //get action
  $sflag=0;
  if(isset($GLOBALS['act_h'])) {if(!empty($GLOBALS['act_h'])) {$this->act=$GLOBALS['act_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['act_h'])) {if(!empty($HTTP_POST_VARS['act_h'])) {$this->act=$HTTP_POST_VARS['act_h'];$sflag=1;}}
  if(!$sflag) {
    if(isset($GLOBALS['act'])) $this->act=$GLOBALS['act'];
    elseif(isset($HTTP_POST_VARS['act'])) $this->act=$HTTP_POST_VARS['act'];
  }
  //set frame =10 records for online and log reports for Pro edition
  if($conf->version>5) {
    if((!strcmp($this->act,'onlinedet'))||(!strcmp($this->act,'log'))) $this->frame=10;
  }

  //get old action
  if(isset($GLOBALS['oldact'])) $this->oldact=$GLOBALS['oldact'];
  elseif(isset($HTTP_POST_VARS['oldact'])) $this->oldact=$HTTP_POST_VARS['oldact'];
  else $this->oldact='';

  //get extension of action
  $sflag=0;
  if(isset($GLOBALS['extact_h'])) {if(!empty($GLOBALS['extact_h'])) {$this->extact=$GLOBALS['extact_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['extact_h'])) {if(!empty($HTTP_POST_VARS['extact_h'])) {$this->extact=$HTTP_POST_VARS['extact_h'];$sflag=1;}}
  if(!$sflag) {
    if(isset($GLOBALS['extact'])) $this->extact=$GLOBALS['extact'];
    elseif(isset($HTTP_POST_VARS['extact'])) $this->extact=$HTTP_POST_VARS['extact'];
  }

  //get time interval
  $sflag=0;
  if(isset($GLOBALS['tint_h'])) {if(!empty($GLOBALS['tint_h'])) {$this->tint=$GLOBALS['tint_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['tint_h'])) {if(!empty($HTTP_POST_VARS['tint_h'])) {$this->tint=$HTTP_POST_VARS['tint_h'];$sflag=1;}}
  if(!$sflag) {
    if(isset($GLOBALS['tint'])) $this->tint=$GLOBALS['tint'];
    elseif(isset($HTTP_POST_VARS['tint'])) $this->tint=$HTTP_POST_VARS['tint'];
  }

  //get param value
  $sflag=0;
  if(isset($GLOBALS['param_h'])) {if(!empty($GLOBALS['param_h'])) {$this->param=$GLOBALS['param_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['param_h'])) {if(!empty($HTTP_POST_VARS['param_h'])) {$this->param=$HTTP_POST_VARS['param_h'];$sflag=1;}}
  if(!$sflag) $this->param='';

  //reinterpret year interval
  if(strstr($this->tint,'totalm_')) {
    $arr=preg_split("/_/",$this->tint);
    $this->tint=$arr[0];
    $this->param=$arr[1];
  }

  //get param2 value
  $sflag=0;
  if(isset($GLOBALS['param2_h'])) {if(!empty($GLOBALS['param2_h'])) {$this->param2=$GLOBALS['param2_h'];$sflag=1;}}
  elseif(isset($HTTP_POST_VARS['param2_h'])) {if(!empty($HTTP_POST_VARS['param2_h'])) {$this->param2=$HTTP_POST_VARS['param2_h'];$sflag=1;}}
  if(!$sflag) $this->param2='';

  //get current list position
  if(isset($GLOBALS['listcur'])) $this->bpos=$GLOBALS['listcur'];
  elseif(isset($HTTP_POST_VARS['listcur'])) $this->bpos=$HTTP_POST_VARS['listcur'];
  $oldbpos=$this->bpos;

  //get list lenght
  if(isset($GLOBALS['listlen'])) $this->listlen=$GLOBALS['listlen'];
  elseif(isset($HTTP_POST_VARS['listlen'])) $this->listlen=$HTTP_POST_VARS['listlen'];

  //scrolling of the list
  if(isset($GLOBALS['listp'])) $listp=$GLOBALS['listp'];
  elseif(isset($HTTP_POST_VARS['listp'])) $listp=$HTTP_POST_VARS['listp'];
  else $listp='';

  //direction of scrolling
  $direction='';
  $eact='';
  if(!empty($listp)) {
    $tarr=preg_split("/=/",$listp);
    $direction=$tarr[0];
    $eact=$tarr[1];
  }

  //set new position
  if(!strcmp($direction,'lbeg')) $this->bpos=0;
  elseif(!strcmp($direction,'lllscr')) {
    $this->bpos=$this->bpos-(10*$this->frame);
    if($this->bpos<0) $this->bpos=0;
  }
  elseif(!strcmp($direction,'llscr')) {
    $this->bpos=$this->bpos-$this->frame;
    if($this->bpos<0) $this->bpos=0;
  }
  elseif(!strcmp($direction,'lrscr')) {
    $this->bpos=$this->bpos+$this->frame;
    if($this->bpos>=$this->listlen) {
      $m=(int)($this->listlen/$this->frame);
      $n=$this->listlen-($m*$this->frame);
      if($n==0) $n=$this->frame;
      $this->bpos=$this->listlen-$n;
    }
  }
  elseif(!strcmp($direction,'lrlscr')) {
    $this->bpos=$this->bpos+(10*$this->frame);
    if($this->bpos>=$this->listlen) {
      $m=(int)($this->listlen/$this->frame);
      $n=$this->listlen-($m*$this->frame);
      if($n==0) $n=$this->frame;
      $this->bpos=$this->listlen-$n;
    }
  }
  elseif(!strcmp($direction,'lend')) {
      $m=(int)($this->listlen/$this->frame);
      $n=$this->listlen-($m*$this->frame);
      if($n==0) $n=$this->frame;
      $this->bpos=$this->listlen-$n;
  }
  else $this->bpos=0;

  if($oldbpos!=$this->bpos) {
    if(!empty($eact)) $this->extact=$eact;
    else $this->extact='summary';
  }
}

//top of page
/*-------------------------------------------------------*/
function top() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
  tparse($top,$this->vars);
}

//bottom of page
/*-------------------------------------------------------*/
function bottom() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/bottom.php';
  tparse($bottom,$this->vars);
}

//control panel
/*-------------------------------------------------------*/
function cpanel() {
  global $err,$conf,$vdb;

  require './style/'.$conf->style.'/template/vctrl.php';

  $tabn=0;  //active position of Tab
  $tiflag=2;  //accessible time intervals   0-none 1-total,year 2-all without ALL 3-all
  $gpflag=0;  //accessible groups/pages    0-page,group 1-group 2-ALL,group 3-all
  $dofr='';  //description of report
  $reps=array();

  //Visitings
  $t=0;
  $tn=1;
  $reps[$t][0]='vis_grpg|'._VISITINGS;
  if(!strcmp($this->act,'vis_grpg')) {$gpflag=2;$tabn=0;$reps[$t][$tn]='|';$dofr=_VISGRPG_D;}
  else $reps[$t][$tn]='vis_grpg'.'|';
  $reps[$t][$tn].=_VISGRPG;
  $tn++;
  if(!strcmp($this->act,'vis_int')) {$tiflag=3;$tabn=0;$reps[$t][$tn]='|';$dofr=_VISINT_D;}
  else $reps[$t][$tn]='vis_int'.'|';
  $reps[$t][$tn].=_VISINT;
  $tn++;
  if(!strcmp($this->act,'onlinegrpg')) {$gpflag=1;$tiflag=0;$tabn=0;$reps[$t][$tn]='|';$dofr=_ONLINEBYPG_D;}
  else $reps[$t][$tn]='onlinegrpg'.'|';
  $reps[$t][$tn].=_ONLINEBYPG;
  $tn++;
  if(!strcmp($this->act,'onlinedet')) {$tiflag=0;$tabn=0;$reps[$t][$tn]='|';$dofr=_ONLINEDET_D;}
  else $reps[$t][$tn]='onlinedet'.'|';
  $reps[$t][$tn].=_ONLINEDET;
  $tn++;
  if(!strcmp($this->act,'log')) {$tabn=0;$reps[$t][$tn]='|';$dofr=_LOG_D;}
  else $reps[$t][$tn]='log'.'|';
  $reps[$t][$tn].=_LOG;

  //Referrals
  $t++;
  $tn=1;
  $reps[$t][0]='refserv|'._REFERRALS;
  if(!strcmp($this->act,'refserv')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_REFSERVS_D;}
  else $reps[$t][$tn]='refserv'.'|';
  $reps[$t][$tn].=_REFSERVS;
  $tn++;
  if(!strcmp($this->act,'allrefpg')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_ALLREFPGS_D;}
  else $reps[$t][$tn]='allrefpg'.'|';
  $reps[$t][$tn].=_ALLREFPGS;
  $tn++;
  if(!strcmp($this->act,'intrefpg')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_INTREFPGS_D;}
  else $reps[$t][$tn]='intrefpg'.'|';
  $reps[$t][$tn].=_INTREFPGS;
  $tn++;
  if(!strcmp($this->act,'extrefpg')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_EXTREFPGS_D;}
  else $reps[$t][$tn]='extrefpg'.'|';
  $reps[$t][$tn].=_EXTREFPGS;
  $tn++;
  if(!strcmp($this->act,'engines')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_SENGINES_D;}
  else $reps[$t][$tn]='engines'.'|';
  $reps[$t][$tn].=_SENGINES;
  $tn++;
  if(!strcmp($this->act,'swords')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_SWORDS_D;}
  else $reps[$t][$tn]='swords'.'|';
  $reps[$t][$tn].=_SWORDS;
  $tn++;
  if(!strcmp($this->act,'sphrases')) {$tabn=1;$reps[$t][$tn]='|';$dofr=_SPHRASES_D;}
  else $reps[$t][$tn]='sphrases'.'|';
  $reps[$t][$tn].=_SPHRASES;

  //Visitor info
  $t++;
  $tn=1;
  $reps[$t][0]='browsers|'._VINFO;
  if(!strcmp($this->act,'browsers')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_BROWSERS_D;}
  else $reps[$t][$tn]='browsers'.'|';
  $reps[$t][$tn].=_BROWSERS;
  $tn++;
  if(!strcmp($this->act,'oss')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_OSS_D;}
  else $reps[$t][$tn]='oss'.'|';
  $reps[$t][$tn].=_OSS;
  $tn++;
  if(!strcmp($this->act,'screen')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_SRESOLUTIONS_D;}
  else $reps[$t][$tn]='screen'.'|';
  $reps[$t][$tn].=_SRESOLUTIONS;
  $tn++;
  if(!strcmp($this->act,'colord')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_COLORDEPTH_D;}
  else $reps[$t][$tn]='colord'.'|';
  $reps[$t][$tn].=_COLORDEPTH;
  $tn++;
  if(!strcmp($this->act,'jscript')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_JAVASCRIPT_D;}
  else $reps[$t][$tn]='jscript'.'|';
  $reps[$t][$tn].=_JAVASCRIPT;
  $tn++;
  if(!strcmp($this->act,'java')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_JAVA_D;}
  else $reps[$t][$tn]='java'.'|';
  $reps[$t][$tn].=_JAVA;
  $tn++;
  if(!strcmp($this->act,'cookie')) {$gpflag=1;$tiflag=1;$tabn=2;$reps[$t][$tn]='|';$dofr=_COOKIE_D;}
  else $reps[$t][$tn]='cookie'.'|';
  $reps[$t][$tn].=_COOKIE;

  //Geography
  $t++;
  $tn=1;
  $reps[$t][0]='countries|'._GEOGRAPHY;
  if(!strcmp($this->act,'countries')) {$tabn=3;$reps[$t][$tn]='|';$dofr=_COUNTRIES_D;}
  else $reps[$t][$tn]='countries'.'|';
  $reps[$t][$tn].=_COUNTRIES;
  $tn++;
  if(!strcmp($this->act,'languages')) {$gpflag=1;$tabn=3;$reps[$t][$tn]='|';$dofr=_LANGUAGES_D;}
  else $reps[$t][$tn]='languages'.'|';
  $reps[$t][$tn].=_LANGUAGES;
  $tn++;
  if(!strcmp($this->act,'tzones')) {$gpflag=1;$tabn=3;$reps[$t][$tn]='|';$dofr=_TZONES_D;}
  else $reps[$t][$tn]='tzones'.'|';
  $reps[$t][$tn].=_TZONES;
  $tn++;
  if(!strcmp($this->act,'providers')) {$gpflag=1;$tiflag=1;$tabn=3;$reps[$t][$tn]='|';$dofr=_PROVIDERS_D;}
  else $reps[$t][$tn]='providers'.'|';
  $reps[$t][$tn].=_PROVIDERS;
  $tn++;
  if(!strcmp($this->act,'proxy')) {$gpflag=1;$tiflag=1;$tabn=3;$reps[$t][$tn]='|';$dofr=_PROXYS_D;}
  else $reps[$t][$tn]='proxy'.'|';
  $reps[$t][$tn].=_PROXYS;

  //Page info
  $t++;
  $tn=1;
  $reps[$t][0]='entry|'._PGINFO;
  if(!strcmp($this->act,'entry')) {$gpflag=3;$tiflag=3;$tabn=4;$reps[$t][$tn]='|';$dofr=_ENTRYGRPG_D;}
  else $reps[$t][$tn]='entry'.'|';
  $reps[$t][$tn].=_ENTRYGRPG;
  $tn++;
  if(!strcmp($this->act,'exits')) {$gpflag=3;$tiflag=3;$tabn=4;$reps[$t][$tn]='|';$dofr=_EXITGRPG_D;}
  else $reps[$t][$tn]='exits'.'|';
  $reps[$t][$tn].=_EXITGRPG;
  $tn++;
  if(!strcmp($this->act,'single')) {$gpflag=3;$tiflag=3;$tabn=4;$reps[$t][$tn]='|';$dofr=_SINGLE_D;}
  else $reps[$t][$tn]='single'.'|';
  $reps[$t][$tn].=_SINGLE;
  $tn++;
  if(!strcmp($this->act,'prod')) {$gpflag=3;$tiflag=3;$tabn=4;$reps[$t][$tn]='|';$dofr=_PRODOFGRPG_D;}
  else $reps[$t][$tn]='prod'.'|';
  $reps[$t][$tn].=_PRODOFGRPG;
  $tn++;
  if(!strcmp($this->act,'timeonpg')) {$tabn=4;$reps[$t][$tn]='|';$dofr=_TIMEONGRPG_D;}
  else $reps[$t][$tn]='timeonpg'.'|';
  $reps[$t][$tn].=_TIMEONGRPG;
  $tn++;
  if(!strcmp($this->act,'rets')) {$tabn=4;$reps[$t][$tn]='|';$dofr=_RETBACK_D;}
  else $reps[$t][$tn]='rets'.'|';
  $reps[$t][$tn].=_RETBACK;
  $tn++;
  if(!strcmp($this->act,'frames')) {$tabn=4;$reps[$t][$tn]='|';$dofr=_PGINFRAMES_D;}
  else $reps[$t][$tn]='frames'.'|';
  $reps[$t][$tn].=_PGINFRAMES;

  //Traffic
  $t++;
  $tn=1;
  $reps[$t][0]='transto|'._TRAFFIC;
  if(!strcmp($this->act,'transto')) {$tabn=5;$reps[$t][$tn]='|';$dofr=_TRANSTO_D;}
  else $reps[$t][$tn]='transto'.'|';
  $reps[$t][$tn].=_TRANSTO;
  $tn++;
  if(!strcmp($this->act,'transfrom')) {$tabn=5;$reps[$t][$tn]='|';$dofr=_TRANSFROM_D;}
  else $reps[$t][$tn]='transfrom'.'|';
  $reps[$t][$tn].=_TRANSFROM;
  $tn++;
  if(!strcmp($this->act,'ways')) {$gpflag=1;$tabn=5;$reps[$t][$tn]='|';$dofr=_MPWAYS_D;}
  else $reps[$t][$tn]='ways'.'|';
  $reps[$t][$tn].=_MPWAYS;
  $tn++;
  if(!strcmp($this->act,'viewd')) {$gpflag=1;$tabn=5;$reps[$t][$tn]='|';$dofr=_DEPTHOFVIEW_D;}
  else $reps[$t][$tn]='viewd'.'|';
  $reps[$t][$tn].=_DEPTHOFVIEW;

  $this->vars['NAME']=_FOR;
  $this->vars['HEADER']=_CPANEL;
  if($tabn==0) $this->vars['TIMG']='tabal';
  else $this->vars['TIMG']='tabpl';
  $this->vars['ACT']=$this->act;
  tparse($top,$this->vars);

  //modules buttons
  if(!empty($conf->aa_mod)) {
    $mods=preg_split("/\|/",$conf->aa_mod);
    for($i=0;$i<sizeof($mods);$i++) {
      if(!strcmp($mods[$i],'aat_')) {
        $lf='./modules/tracker/';
        loadmod($mods[$i],'./',$lf);

        $this->vars['FOLDER']=$lf.'view.php?style='.$conf->style.'&language='.$conf->lang;
        $this->vars['MODULE']='aat';
        $this->vars['TITLE']=$conf->{$mods[$i].'name'};
        tparse($button,$this->vars);
      }
    }
  }

  //admin button
  $this->vars['FOLDER']='admin.php?style='.$conf->style.'&language='.$conf->lang;
  $this->vars['MODULE']='admin';
  $this->vars['TITLE']=_ADMINAREA;
  tparse($button,$this->vars);

  tparse($top2,$this->vars);

  //Tabs
  for($i=0;$i<=$t;$i++) {
    $tarr=preg_split("/\|/",$reps[$i][0]);
    $this->vars['ACT']=$tarr[0];
    $this->vars['TNAME']=$tarr[1];

    if($i==$tabn) {
      $this->vars['TBGIMG']='tabtopa';
      $this->vars['TCIMG']='tabacr';
    }
    elseif(($i+1)==$tabn) {
      $this->vars['TBGIMG']='tabtopp';
      $this->vars['TCIMG']='tabacl';
    }
    else {
      $this->vars['TBGIMG']='tabtopp';
      $this->vars['TCIMG']='tabpc';
    }

    if($i==$tabn) tparse($tabelema,$this->vars);
    else tparse($tabelem,$this->vars);
    if($i<$t) tparse($tabdel,$this->vars);
  }

  if($tabn==$t) $this->vars['TIMG']='tabar';
  else $this->vars['TIMG']='tabpr';
  tparse($actlist,$this->vars);

  //List of reports
  $i=1;
  for(;$i<sizeof($reps[$tabn]);$i++) {
    $tarr=preg_split("/\|/",$reps[$tabn][$i]);
    $tact=$tarr[0];
    $this->vars['ACT']=$tact;

    if(empty($tact)) $tact=$this->act;
    if(method_exists($this,$tact)) $this->vars['IMG']='ren';
    else $this->vars['IMG']='rdis';
    $this->vars['NAME']=$tarr[1];

    if(empty($this->vars['ACT'])) tparse($listelema,$this->vars);
    else tparse($listelem,$this->vars);
  }
  for(;$i<=8;$i++) tparse($listeleme,$this->vars);

  //Description
  $this->vars['DESC']=$dofr;
  tparse($rdesc,$this->vars);

  //Calendar
  $this->vars['CDATE']=_ISSUE.'&nbsp;&nbsp;'.date($conf->dmas[$conf->dformat],$conf->ctime);
  $this->vars['CTIME']=date($conf->tmas[$conf->tformat],$conf->ctime).'&nbsp;&nbsp;';
  if($conf->tzone>0) $this->vars['CTIME'].='+'.$conf->tzone;
  elseif($conf->tzone<0) $this->vars['CTIME'].=$conf->tzone;
  $this->vars['CTIME'].=' GMT';
  tparse($calbeg,$this->vars);

  //begin time of first week of month
  $dc=getdate($conf->mtime);
  if($dc['wday']==0) $num=6;
  else $num=$dc['wday']-1;
  $fwts=mktime(0,0,0,$dc['mon'],$dc['mday']-$num,$dc['year'],0);
  //begin time of week before first week of month
  $lfwts=mktime(0,0,0,$dc['mon'],$dc['mday']-$num-7,$dc['year'],0);

  //begin time for calendar
  $wcount=0;
  $ptrs=0;
  $ts1=strftime("%W",$lfwts);
  $ts=strftime("%W",$conf->lwtime);
  if($ts==$ts1) {$bts=$lfwts;$wcount=1;$ptrs=1;}
  else $bts=$fwts;

  //count of weeks for calendar
  $ts1=strftime("%W",$fwts);
  $ts2=strftime("%W",$conf->nmtime);
  if($ts1>$ts2) $wcount+=$ts2+1;
  else $wcount+=$ts2-$ts1+1;

  //number of string with last week
  if($ptrs==0) {
    if($ts>$ts2) $ptrs=1;
    else $ptrs=$wcount-($ts2-$ts);
  }

  //weeks pointers
  for($d=0;$d<=$wcount;$d++) {
    if($d==$ptrs&&($tiflag==2||$tiflag==3)) {
      $this->vars['INTERVAL']='lastweek';
      $this->vars['PERIOD']=_LASTWEEK;
      tparse($clpointer,$this->vars);
    }
    elseif(($d==($ptrs+1))&&($tiflag==2||$tiflag==3)) {
      $this->vars['INTERVAL']='week';
      $this->vars['PERIOD']=_WEEK;
      tparse($clpointer,$this->vars);
    }
    else tparse($clempty,$this->vars);
  }

  $cdc=getdate($bts);
  $dcc=getdate($conf->ctime);
  //days of week
  tparse($cdays,$this->vars);
  for($d=0;$d<7;$d++) {
    $cts=mktime(0,0,0,$cdc['mon'],$cdc['mday']+$d,$cdc['year'],0);
    $tdc=getdate($cts);
    $dname=$tdc['weekday'];
    $this->vars['NAME']=substr($dname,0,2);
    tparse($cday,$this->vars);
  }

  //begin and end time of selection
  $bdcc=getdate($conf->btime);
  $bcts=mktime(0,0,0,$bdcc['mon'],$bdcc['mday'],$bdcc['year'],0);
  $selbt=0;
  $selet=0;
  if(!strcmp($this->tint,'all')||!strcmp($this->tint,'total')) {
    $selbt=$bts;
    $selet=mktime(0,0,0,$dcc['mon'],$dcc['mday']+1,$dcc['year'],0);
    if($bcts>$bts) $selbt=$bcts;
  }
  elseif(!strcmp($this->tint,'today')) {
    $selbt=$conf->dtime;
    $selet=mktime(0,0,0,$dcc['mon'],$dcc['mday']+1,$dcc['year'],0);
  }
  elseif(!strcmp($this->tint,'yesterday')) {
    $selbt=mktime(0,0,0,$dcc['mon'],$dcc['mday']-1,$dcc['year'],0);
    $selet=$conf->dtime;
  }
  elseif(!strcmp($this->tint,'week')) {
    $selbt=$conf->wtime;
    $selet=mktime(0,0,0,$dcc['mon'],$dcc['mday']+1,$dcc['year'],0);
    if($bcts>$selbt) $selbt=$bcts;
  }
  elseif(!strcmp($this->tint,'lastweek')) {
    $selbt=$conf->lwtime;
    $selet=$conf->wtime;
    if(($selbt<$bcts)&&($bcts<$selet)) $selbt=$bcts;
  }
  elseif(!strcmp($this->tint,'month')) {
    $selbt=$conf->mtime;
    $selet=mktime(0,0,0,$dcc['mon'],$dcc['mday']+1,$dcc['year'],0);
    if($bcts>$selbt) $selbt=$bcts;
  }
  elseif(!strcmp($this->tint,'lastmonth')) {
    $selbt=$bts;
    $selet=$conf->mtime;
    if(($selbt<$bcts)&&($bcts<$selet)) $selbt=$bcts;
  }
  elseif(!strcmp($this->tint,'totalm')) {
    $selbt=mktime(0,0,0,1,1,$this->param,0);
    $selet=mktime(0,0,0,1,1,$this->param+1,0);
    if($selet>$bts) {
      if($selbt>$bts) $selet=mktime(0,0,0,$dcc['mon'],$dcc['mday']+1,$dcc['year'],0);
      else $selbt=$bts;
    if($bcts>$selbt) $selbt=$bcts;
    }
  }

  //create calendar
  $selflag=false;
  $yday=mktime(0,0,0,$dcc['mon'],$dcc['mday']-1,$dcc['year'],0);
  $cday=strftime("%d",$conf->ctime);
  $cmonth=strftime("%m",$conf->ctime);
  for($d=0;$d<$wcount;$d++) {
    tparse($cdigdl,$this->vars);
    for($n=0;$n<7;$n++) {
      $cts=mktime(0,0,0,$cdc['mon'],$cdc['mday']+$d*7+$n,$cdc['year'],0);
      if($cts==$selbt) $selflag=true;
      elseif($cts==$selet) $selflag=false;
      if($selflag) $this->vars['SELD']=' class=sel';
      else $this->vars['SELD']='';
      $tdc=getdate($cts);
      //yesterday
      if($yday==$cts&&($tiflag==2||$tiflag==3)) {
        $this->vars['INTERVAL']='yesterday';
        $this->vars['PERIOD']=_YESTERDAY;
        if($tdc['mon']!=$cmonth) {
          $this->vars['NUM']=$tdc['mday'];
          tparse($cdigpa,$this->vars);
        }
        else {
          $this->vars['NUM']=$tdc['mday'];
          tparse($cdiga,$this->vars);
        }
      }
      elseif($tdc['mon']!=$cmonth) {
        $this->vars['NUM']=$tdc['mday'];
        tparse($cdigp,$this->vars);
      }
      else {
        //today
        if($tdc['mday']==$cday&&($tiflag==2||$tiflag==3)) {
          $this->vars['NUM']=$tdc['mday'];
          $this->vars['INTERVAL']='today';
          $this->vars['PERIOD']=_TODAY;
          tparse($cdiga,$this->vars);
        }
        else {
          $this->vars['NUM']=$tdc['mday'];
          tparse($cdig,$this->vars);
        }
      }
    }
  }
  tparse($cafter,$this->vars);
  for($d=$wcount;$d<7;$d++) {
    tparse($caftere,$this->vars);
  }

  //list of time intervals
  if($tiflag!=0) {
    $this->vars['NAME']=_TIMEINT;
    tparse($tlist,$this->vars);
  }
  else tparse($emplist,$this->vars);

  if($tiflag==3) {
    $this->vars['VALUE']='all';
    $this->vars['NAME']=_ALL;
    if(!strcmp($this->tint,'all'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
  }
  if($tiflag==2||$tiflag==3) {
    $this->vars['VALUE']='today';
    $this->vars['NAME']=_TODAY;
    if(!strcmp($this->tint,'today'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
    $this->vars['VALUE']='yesterday';
    $this->vars['NAME']=_YESTERDAY;
    if(!strcmp($this->tint,'yesterday'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
    $this->vars['VALUE']='week';
    $this->vars['NAME']=_WEEK;
    if(!strcmp($this->tint,'week'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
    $this->vars['VALUE']='lastweek';
    $this->vars['NAME']=_LASTWEEK;
    if(!strcmp($this->tint,'lastweek'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
    $this->vars['VALUE']='month';
    $this->vars['NAME']=_MONTH;
    if(!strcmp($this->tint,'month'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
    $this->vars['VALUE']='lastmonth';
    $this->vars['NAME']=_LASTMONTH;
    if(!strcmp($this->tint,'lastmonth'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
  }
  if($tiflag==1||$tiflag==2||$tiflag==3) {
    for($i=date("Y",$conf->btime);$i<=date("Y",$conf->ctime);$i++) {
      $this->vars['VALUE']='totalm_'.$i;
      $this->vars['NAME']=_YEAR.' '.$i;
      $this->vars['SELECTED']='';
      if(!strcmp($this->tint,'totalm')) {
        if($i==$this->param) $this->vars['SELECTED']=' selected';
      }
      tparse($opt,$this->vars);
    }
    $this->vars['VALUE']='total';
    $this->vars['NAME']=_TOTAL;
    if(!strcmp($this->tint,'total'))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($opt,$this->vars);
  }

  if($tiflag!=0) tparse($etlist,$this->vars);

  //list of groups/pages
  $this->vars['NAME']=_FOR;
  tparse($grpglist,$this->vars);

  //All groups
  if($gpflag==2||$gpflag==3) {
    $this->vars['VALUE']=221;
    if(!strcmp($this->id,221))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    $this->vars['NAME']=_ALLGRS;
    tparse($opt,$this->vars);
  }

  //Groups
  if($gpflag==0||$gpflag==1||$gpflag==2||$gpflag==3) {
    $groups=$vdb->getgrs();
    reset($groups);
    while ($k=key($groups)) {
      $fname=$groups[$k];
      if(strlen($fname)>_VS_PGSLIST) $sname=substr($fname,0,_VS_PGSLIST-3).'...';
      else $sname=$fname;
      $this->vars['NAME']=_GROUP.': '.$sname;
      $this->vars['VALUE']=$k;
      if(!strcmp($this->id,$k))   $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      tparse($opt,$this->vars);
      next($groups);
    }
  }

  //Pages
  if($gpflag==0||$gpflag==3) {
    $groups=$vdb->getpages();
    reset($groups);
    while ($k=key($groups)) {
      $fname=$groups[$k];
      if(strlen($fname)>_VS_PGSLIST) $sname=substr($fname,0,_VS_PGSLIST-3).'...';
      else $sname=$fname;
      $this->vars['NAME']=_PAGE.': '.$sname;
      $this->vars['VALUE']=$k;
      if(!strcmp($this->id,$k))   $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      tparse($opt,$this->vars);
      next($groups);
    }
  }

  tparse($bottom,$this->vars);
}

//filter panel
/*-------------------------------------------------------*/
function fpanel($sort,&$filter,&$filter_cl) {
  global $err,$conf,$vdb;

  require './view/vstat/fpanel.php';
}

//Visitings by time intervals
/*-------------------------------------------------------*/
function vis_int() {
  global $err,$vdb,$conf;

  //if all groups are selected -> all pages
  if($this->id==221) $this->id=201;

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|vis_int|can\'t get name of the group/page with id='.$this->id);return;}

  if(!strcmp($this->tint,'all')) {
    //report by all time intervals
    $vdb->vis_all($this->id,$this->vars,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|vis_int|can\'t create report by time intervals for all time intervals for id='.$this->id);return;}
  }
  else {
    //report by selected time interval
    $year=0;
    if(!strcmp($this->tint,'totalm')) $year=$this->param;
    $vdb->vis_tim($this->id,$this->vars,$this->name,$this->url,$sort,$this->tint,$year);
    if($err->flag) {$err->reason('vstat.php|vis_int|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}
  }

  $this->bottom($this->vars);
}

//Visitings by groups/pages
/*-------------------------------------------------------*/
function vis_grpg() {
  global $err,$vdb,$conf;

  //if page is selected -> all groups
  if($this->id<201) {
    if(!strcmp($this->oldact,'vis_grpg')) {
      $this->act='vis_int';
      $this->vis_int();
      return;
    }
    else $this->id=221;
  }

  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  if($this->id!=221) {
    $vdb->getnamegrpg($this->id,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|entry|can\'t get name of the group/page with id='.$this->id);return;}
  }

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->vis_grpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|vis_grpg|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//referring servers report
/*-------------------------------------------------------*/
function refserv() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|refserv|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->ref($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year,0);
  if($err->flag) {$err->reason('vstat.php|refserv|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//all referring pages report
/*-------------------------------------------------------*/
function allrefpg() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|allrefpg|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->ref($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year,1);
  if($err->flag) {$err->reason('vstat.php|allrefpg|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//internal referring pages report
/*-------------------------------------------------------*/
function intrefpg() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|intrefpg|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->ref($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year,2);
  if($err->flag) {$err->reason('vstat.php|intrefpg|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//external referring pages report
/*-------------------------------------------------------*/
function extrefpg() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|extrefpg|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->ref($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year,3);
  if($err->flag) {$err->reason('vstat.php|extrefpg|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//On-line users by groups/pages
/*-------------------------------------------------------*/
function onlinegrpg() {
  global $err,$vdb,$conf;

  //if page is selected -> all pages
  if($this->id<201) $this->id=201;

  $ontime=$conf->ctime;
  if(strstr($this->param2,'online_')) {
    $arr=preg_split("/_/",$this->param2);
    if(isset($arr[1])) $ontime=$arr[1];
  }
  $this->vars['P2']='online_'.$ontime;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"summary\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|onlinedet|can\'t get name of the group/page with id='.$this->id);return;}

  $vdb->online_grpg($this->id,$this->vars,$this->name,$sort,$ontime);
  if($err->flag) {$err->reason('vstat.php|onlinedet|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Detailed report about on-line users
/*-------------------------------------------------------*/
function onlinedet() {
  global $err,$vdb,$conf;

  $ontime=$conf->ctime;
  if(strstr($this->param2,'online_')) {
    $arr=preg_split("/_/",$this->param2);
    if(isset($arr[1])) $ontime=$arr[1];
  }
  $this->vars['P2']='online_'.$ontime;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;

  $sort='';
  $filter=array();
  $filter_cl=array();
  $this->filterby($sort,$filter,$filter_cl);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"summary\")'";}

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|onlinedet|can\'t get name of the group/page with id='.$this->id);return;}

  $this->top($this->vars);
  $this->cpanel();
  $this->fpanel($sort,$filter,$filter_cl);

  $vdb->log($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$filter,$filter_cl,'online',$ontime);
  if($err->flag) {$err->reason('vstat.php|onlinedet|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}


//countries reports
/*-------------------------------------------------------*/
function countries() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|countries|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->countr($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|countries|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//languages reports
/*-------------------------------------------------------*/
function languages() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|languages|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->lang($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|languages|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//browsers reports
/*-------------------------------------------------------*/
function browsers() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|browsers|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->brow($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|browsers|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//operating systems reports
/*-------------------------------------------------------*/
function oss() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|oss|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->os($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|oss|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//screen resolutions reports
/*-------------------------------------------------------*/
function screen() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|screen|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->scr($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|screen|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//color depths reports
/*-------------------------------------------------------*/
function colord() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|colord|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->colord($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|colord|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//JavaScript reports
/*-------------------------------------------------------*/
function jscript() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|jscript|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->jscript($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|jscript|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Java reports
/*-------------------------------------------------------*/
function java() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|java|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->java($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|java|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Cookie reports
/*-------------------------------------------------------*/
function cookie() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|cookie|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->cookie($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|cookie|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Entry pages
/*-------------------------------------------------------*/
function entry() {
  global $err,$vdb,$conf;

  //if page is selected - time interval=all
  if($this->id<201) $this->tint='all';
  //if all groups and all time intervals are selected - time interval=total
  if(($this->id==221)&&(!strcmp($this->tint,'all'))) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  if($this->id!=221) {
    $vdb->getnamegrpg($this->id,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|entry|can\'t get name of the group/page with id='.$this->id);return;}
  }

  if(!strcmp($this->tint,'all')) {
    //report by time intervals
    $vdb->entry_tim($this->id,$this->vars,$this->name,$this->url,$sort);
    if($err->flag) {$err->reason('vstat.php|entry|can\'t create report by time intervals for all time intervals for id='.$this->id);return;}
  }
  else {
    //report by groups/pages
    $year=0;
    if(!strcmp($this->tint,'totalm')) $year=$this->param;
    $vdb->entry_grpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
    if($err->flag) {$err->reason('vstat.php|entry|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}
  }

  $this->bottom($this->vars);
}

//Exit pages
/*-------------------------------------------------------*/
function exits() {
  global $err,$vdb,$conf;

  //if page is selected - time interval=all
  if($this->id<201) $this->tint='all';
  //if all groups and all time intervals are selected - time interval=total
  if(($this->id==221)&&(!strcmp($this->tint,'all'))) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  if($this->id!=221) {
    $vdb->getnamegrpg($this->id,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|exits|can\'t get name of the group/page with id='.$this->id);return;}
  }

  if(!strcmp($this->tint,'all')) {
    //report by time intervals
    $vdb->exit_tim($this->id,$this->vars,$this->name,$this->url,$sort);
    if($err->flag) {$err->reason('vstat.php|exits|can\'t create report by time intervals for all time intervals for id='.$this->id);return;}
  }
  else {
    //report by groups/pages
    $year=0;
    if(!strcmp($this->tint,'totalm')) $year=$this->param;
    $vdb->exit_grpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
    if($err->flag) {$err->reason('vstat.php|exits|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}
  }

  $this->bottom($this->vars);
}

//Single visits
/*-------------------------------------------------------*/
function single() {
  global $err,$vdb,$conf;

  //if page is selected - time interval=all
  if($this->id<201) $this->tint='all';
  //if all groups and all time intervals are selected - time interval=total
  if(($this->id==221)&&(!strcmp($this->tint,'all'))) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  if($this->id!=221) {
    $vdb->getnamegrpg($this->id,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|single|can\'t get name of the group/page with id='.$this->id);return;}
  }

  if(!strcmp($this->tint,'all')) {
    //report by time intervals
    $vdb->single_tim($this->id,$this->vars,$this->name,$this->url,$sort);
    if($err->flag) {$err->reason('vstat.php|single|can\'t create report by time intervals for all time intervals for id='.$this->id);return;}
  }
  else {
    //report by groups/pages
    $year=0;
    if(!strcmp($this->tint,'totalm')) $year=$this->param;
    $vdb->single_grpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
    if($err->flag) {$err->reason('vstat.php|single|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}
  }

  $this->bottom($this->vars);
}

//Time on group/page
/*-------------------------------------------------------*/
function timeonpg() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|timeonpg|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->timeonpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|timeonpg|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Returnings back
/*-------------------------------------------------------*/
function rets() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|rets|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->rets($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|rets|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Depth of viewing
/*-------------------------------------------------------*/
function viewd() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|viewd|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->viewd($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|viewd|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Search engines
/*-------------------------------------------------------*/
function engines() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|engines|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->engines($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|engines|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Search words
/*-------------------------------------------------------*/
function swords() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|swords|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->swords($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|swords|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Search phrases
/*-------------------------------------------------------*/
function sphrases() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|sphrases|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->sphrases($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|sphrases|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Page in frames
/*-------------------------------------------------------*/
function frames() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|frames|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->frames($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|frames|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Time zones
/*-------------------------------------------------------*/
function tzones() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|tzones|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->zones($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|tzones|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Providers
/*-------------------------------------------------------*/
function providers() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|providers|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->providers($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|providers|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Ways
/*-------------------------------------------------------*/
function ways() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //if all time intervals are selected - time interval=total
  if((!strcmp($this->tint,'all'))) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  $this->vars['SCROLL']='';

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|ways|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->ways($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|ways|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Productivity
/*-------------------------------------------------------*/
function prod() {
  global $err,$vdb,$conf;

  //if page is selected - time interval=all
  if($this->id<201) $this->tint='all';
  //if all groups and all time intervals are selected - time interval=total
  if(($this->id==221)&&(!strcmp($this->tint,'all'))) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $this->name=_ALL;
  if($this->id!=221) {
    $vdb->getnamegrpg($this->id,$this->name,$this->url);
    if($err->flag) {$err->reason('vstat.php|prod|can\'t get name of the group/page with id='.$this->id);return;}
  }

  if(!strcmp($this->tint,'all')) {
    //report by time intervals
    $vdb->prod_tim($this->id,$this->vars,$this->name,$this->url,$sort);
    if($err->flag) {$err->reason('vstat.php|prod|can\'t create report by time intervals for all time intervals for id='.$this->id);return;}
  }
  else {
    //report by groups/pages
    $year=0;
    if(!strcmp($this->tint,'totalm')) $year=$this->param;
    $vdb->prod_grpg($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
    if($err->flag) {$err->reason('vstat.php|prod|can\'t create report by groups/pages for time interval='.$this->tint.' for id='.$this->id);return;}
  }

  $this->bottom($this->vars);
}

//Transitions to group/page
/*-------------------------------------------------------*/
function transto() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|transto|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->transto($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|transto|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Transitions from group/page
/*-------------------------------------------------------*/
function transfrom() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|transfrom|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->transfrom($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|transfrom|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Proxy servers
/*-------------------------------------------------------*/
function proxy() {
  global $err,$vdb,$conf;

  //information: only for groups
  if(($this->id<201)||($this->id==221)) $this->id=201;
  //information: only total and yearly
  if(strcmp($this->tint,'total')&&strcmp($this->tint,'totalm')) $this->tint='total';

  $sort=array();
  $this->sortby($sort);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"$this->extact\")'";}

  $this->top($this->vars);
  $this->cpanel();

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|proxy|can\'t get name of the group/page with id='.$this->id);return;}

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->proxy($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|proxy|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//Log of visitings
/*-------------------------------------------------------*/
function log() {
  global $err,$vdb,$conf;

  //if all groups are selected - group=all pages
  if($this->id==221) $this->id=201;
  //if all time intervals are selected - time interval=total
  if(!strcmp($this->tint,'all')) $this->tint='total';

  $sort='';
  $filter=array();
  $filter_cl=array();
  $this->filterby($sort,$filter,$filter_cl);

  if(!strcmp($this->extact,'all')) {$this->vars['SCROLL']='';}
  else {$this->vars['SCROLL']="onload='GoRef(\"summary\")'";}

  //get name of page/group by id
  $vdb->getnamegrpg($this->id,$this->name,$this->url);
  if($err->flag) {$err->reason('vstat.php|log|can\'t get name of the group/page with id='.$this->id);return;}

  $this->top($this->vars);
  $this->cpanel();
  $this->fpanel($sort,$filter,$filter_cl);

  $year=0;
  if(!strcmp($this->tint,'totalm')) $year=$this->param;
  $vdb->log($this->id,$this->vars,$this->bpos,$this->frame,$this->name,$this->url,$sort,$filter,$filter_cl,$this->tint,$year);
  if($err->flag) {$err->reason('vstat.php|log|can\'t create report for time interval='.$this->tint.' for id='.$this->id);return;}

  $this->bottom($this->vars);
}

//create sort array
/*-------------------------------------------------------*/
function sortby(&$sort) {
  global $err,$vdb,$conf,$HTTP_POST_VARS;

  //default sort order
  $sort['table']=1;
  $sort['column']=1;

  $tmp='';
  if(isset($GLOBALS['tab_sort'])) $tmp=$GLOBALS['tab_sort'];
  elseif(isset($HTTP_POST_VARS['tab_sort'])) $tmp=$HTTP_POST_VARS['tab_sort'];

  //if sort buttons exists
  if(!empty($tmp)) {
    //get current sort order
    $i=1;
    for(;$i<6;$i++) {
      $j=1;
      for(;$j<6;$j++) {
        if(isset($GLOBALS[$i.'_'.$j.'_x'])||isset($HTTP_POST_VARS[$i.'_'.$j.'_x'])) {
          $sort['table']=$i;
          $sort['column']=$j;

          //set extended action
          if($i==1) $this->extact='summary';
          elseif($i==2) $this->extact='visitors';
          elseif($i==3) $this->extact='hosts';
          elseif($i==4) $this->extact='reloads';
          elseif($i==5) $this->extact='hits';

          break;
        }
      }
      if($j!=6) break;
    }

    if(($i==6)&&(!strcmp($this->act,$this->oldact))) {
      $tmparr = preg_split("/_/",$tmp);
      if(sizeof($tmparr)==2) {
        $sort['table']=$tmparr[0];
        $sort['column']=$tmparr[1];
      }
    }
  }

  //reset sort order for reports without sort
  if(!strcmp($this->extact,'all')) {
    $sort['table']=1;
    $sort['column']=1;
  }

  $this->vars['TABSORT']=$sort['table'].'_'.$sort['column'];
}

//create filter array
/*-------------------------------------------------------*/
function filterby(&$sort,&$filter,&$filter_cl) {
  global $err,$vdb,$conf,$HTTP_POST_VARS;

  require './view/vstat/filterby.php';
}

}

?>
