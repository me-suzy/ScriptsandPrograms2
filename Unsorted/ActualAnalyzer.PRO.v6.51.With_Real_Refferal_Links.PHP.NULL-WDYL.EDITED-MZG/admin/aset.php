<?php

class aset {

var $act;                //current action
var $oldact;             //old action
var $extact;             //extension action
var $param;              //form parameter
var $id;                 //id of page/group
var $fun;                //current function
var $vars;               //variables for templates
var $rf;                 //root folder
var $ctrl;               //action selected

/*-------------------------------------------------------*/
function aset($rf) {
  global $err,$conf;

  //set default values
  $this->ctrl='';
  $this->rf=$rf;
  $this->act='pages';
  $this->oldact=$this->act;
  $this->extact=0;
  $this->param=0;
  $this->id=0;
  $this->newset=0;

  //get parameters
  $this->params();
  if($err->flag) {$err->reason('aset.php|aset|can\'t get parameters of admin form');return;}

  //reinterpret parameters
  $this->rparams();
  if($err->flag) {$err->reason('aset.php|aset|can\'t reinterpret parameters of admin form');return;}

  //globals variables
  $this->vars['RF']=$this->rf;
  $this->vars['SCROLL']='';
  $this->vars['ID']='';
  $this->vars['P2']='';
  $this->vars['OLDACT']=$this->act;
  $this->vars['LANG']=$conf->lang;
  $this->vars['STYLE']=$conf->style;
  $this->vars['VERSION']=_VERSION;
  $this->vars['VER']=$conf->version;
  $this->vars['UPDATE']=':&nbsp;&nbsp;<a href="'.$conf->site.'" target=_blank>'._CHECKUPDATE.'</a>&nbsp;&nbsp;';
  $this->vars['FAQ']=_FAQ;
  $this->vars['SUPPORT']=_SUPPORT;
  $this->vars['CHARSET']=_CHARSET;
  $this->vars['SERIES']=$conf->series;
  $this->vars['TITLE']='ActualAnalyzer '.$conf->series.' - '._ADMINAREA;
  $this->vars['UNAME']=$conf->uname;
  $this->vars['PASSW']=$conf->passw;
  $this->vars['SITE']=$conf->site;

  //call the need function
  eval("\$this->{$this->fun}();");
  if($err->flag) {$err->reason('aset.php|aset|\''.$this->fun.'\' function has failed');return;}
}

//get parameters
/*-------------------------------------------------------*/
function params() {
  global $err,$conf,$HTTP_POST_VARS;

  //get action
  if(isset($GLOBALS['act'])) $this->act=$GLOBALS['act'];
  elseif(isset($HTTP_POST_VARS['act'])) $this->act=$HTTP_POST_VARS['act'];

  //get old action
  if(isset($GLOBALS['oldact'])) $this->oldact=$GLOBALS['oldact'];
  elseif(isset($HTTP_POST_VARS['oldact'])) $this->oldact=$HTTP_POST_VARS['oldact'];

  //get extension of action
  if(isset($GLOBALS['extact_h'])) $this->extact=$GLOBALS['extact_h'];
  elseif(isset($HTTP_POST_VARS['extact_h'])) $this->extact=$HTTP_POST_VARS['extact_h'];

  //get param
  if(isset($GLOBALS['param_h'])) $this->param=$GLOBALS['param_h'];
  elseif(isset($HTTP_POST_VARS['param_h'])) $this->param=$HTTP_POST_VARS['param_h'];

  //get id
  if(isset($GLOBALS['id_h'])) $this->id=$GLOBALS['id_h'];
  elseif(isset($HTTP_POST_VARS['id_h'])) $this->id=$HTTP_POST_VARS['id_h'];

  //Ctrl selected
  if(isset($GLOBALS['set_x'])) $this->ctrl=$GLOBALS['set_x'];
  elseif(isset($HTTP_POST_VARS['set_x'])) $this->ctrl=$HTTP_POST_VARS['set_x'];
  else $this->ctrl='';
}

//reinterpret parameters
/*-------------------------------------------------------*/
function rparams() {
  global $err,$conf,$adb,$HTTP_POST_VARS;

  //set function
  $this->fun=$this->act;
  if(!empty($this->ctrl)) return;

  if(!empty($this->oldact)) {
    $this->act=$this->oldact;
    $this->fun=$this->act;
  }

  //reinterpret parameters
  if(!strcmp($this->extact,'set')) {
    if(!strcmp($this->act,'addpage')) {
      //get parameters of new page
      if(isset($GLOBALS['pname'])) $pname=$GLOBALS['pname'];
      elseif(isset($HTTP_POST_VARS['pname'])) $pname=$HTTP_POST_VARS['pname'];
      else $pname='';
      if(isset($GLOBALS['purl'])) $purl=$GLOBALS['purl'];
      elseif(isset($HTTP_POST_VARS['purl'])) $purl=$HTTP_POST_VARS['purl'];
      else $purl='';
      if(isset($GLOBALS['img'])) $img=$GLOBALS['img'];
      elseif(isset($HTTP_POST_VARS['img'])) $img=$HTTP_POST_VARS['img'];
      else $img=2;
      if(isset($GLOBALS['dcolor'])) $dcolor=$GLOBALS['dcolor'];
      elseif(isset($HTTP_POST_VARS['dcolor'])) $dcolor=$HTTP_POST_VARS['dcolor'];
      else $dcolor=0;
      if(preg_match("/^([0-9a-fA-F]+)/i",$dcolor,$matches)) {
        $dcolor=$matches[1];
        eval("\$dcolor=0x$dcolor;");
      }
      else $dcolor=0;
      if(isset($GLOBALS['dflag'])) $dflag=$GLOBALS['dflag'];
      elseif(isset($HTTP_POST_VARS['dflag'])) $dflag=$HTTP_POST_VARS['dflag'];
      else $dflag=1;
      if((isset($GLOBALS['defpg']))||(isset($HTTP_POST_VARS['defpg']))) $defpg=1;
      else $defpg=0;

      if(empty($pname)) {$this->fun='ierr';$this->param=_EMPTYNAME;return;}
      if(empty($purl)) {$this->fun='ierr';$this->param=_EMPTYURL;return;}

      //add page
      $adb->addpg($pname,$purl,$img,$dcolor,$dflag,$defpg);
      if($err->flag) {$err->reason('aset.php|rparams|adding of page has failed');return;}

      //reset parameters
      $this->act='pages';
      $this->fun=$this->act;
    }
    elseif(!strcmp($this->act,'addgroup')) {
      //get parameters of existing group
      if(isset($GLOBALS['gname'])) $gname=$GLOBALS['gname'];
      elseif(isset($HTTP_POST_VARS['gname'])) $gname=$HTTP_POST_VARS['gname'];
      else $gname='';

      if(empty($gname)) {$this->fun='ierr';$this->param=_EMPTYGNAME;return;}

      //add group
      $adb->updategr($gname);
      if($err->flag) {$err->reason('aset.php|rparams|can\'t add existing group');return;}

      //reset parameters
      $this->act='groups';
      $this->fun=$this->act;
    }
    elseif(!strcmp($this->act,'pages')) {
      //get parameters of existing page
      if(isset($GLOBALS['pname'])) $pname=$GLOBALS['pname'];
      elseif(isset($HTTP_POST_VARS['pname'])) $pname=$HTTP_POST_VARS['pname'];
      else $pname='';
      if(isset($GLOBALS['purl'])) $purl=$GLOBALS['purl'];
      elseif(isset($HTTP_POST_VARS['purl'])) $purl=$HTTP_POST_VARS['purl'];
      else $purl='';
      if(isset($GLOBALS['img'])) $img=$GLOBALS['img'];
      elseif(isset($HTTP_POST_VARS['img'])) $img=$HTTP_POST_VARS['img'];
      else $img=2;
      if(isset($GLOBALS['dcolor'])) $dcolor=$GLOBALS['dcolor'];
      elseif(isset($HTTP_POST_VARS['dcolor'])) $dcolor=$HTTP_POST_VARS['dcolor'];
      else $dcolor=0;
      if(preg_match("/^([0-9a-fA-F]+)/i",$dcolor,$matches)) {
        $dcolor=$matches[1];
        eval("\$dcolor=0x$dcolor;");
      }
      else $dcolor=0;
      if(isset($GLOBALS['dflag'])) $dflag=$GLOBALS['dflag'];
      elseif(isset($HTTP_POST_VARS['dflag'])) $dflag=$HTTP_POST_VARS['dflag'];
      else $dflag=1;
      if((isset($GLOBALS['defpg']))||(isset($HTTP_POST_VARS['defpg']))) $defpg=1;
      else $defpg=0;

      if(empty($pname)) {$this->fun='ierr';$this->param=_EMPTYNAME;return;}
      if(empty($purl)) {$this->fun='ierr';$this->param=_EMPTYURL;return;}

      //update existing page
      $adb->updatepg($this->id,$pname,$purl,$img,$dcolor,$dflag,$defpg);
      if($err->flag) {$err->reason('aset.php|rparams|updating of existing page has failed');return;}

      //reset parameters
      $this->act='pages';
      $this->fun=$this->act;
    }
    elseif(!strcmp($this->act,'groups')) {
      //get parameters of existing group
      if(isset($GLOBALS['gname'])) $gname=$GLOBALS['gname'];
      elseif(isset($HTTP_POST_VARS['gname'])) $gname=$HTTP_POST_VARS['gname'];
      else $gname='';

      if($this->id!=201) {
        //update existing group
        if(empty($gname)) {$this->fun='ierr';$this->param=_EMPTYGNAME;return;}
        $adb->updategr($gname,$this->id);
        if($err->flag) {$err->reason('aset.php|rparams|updating of existing group has failed');return;}
      }

      //reset parameters
      $this->act='groups';
      $this->fun=$this->act;
    }
    elseif(!strcmp($this->act,'settings')) {
      //get username
      if(isset($GLOBALS['unamef'])) $conf->uname=$GLOBALS['unamef'];
      elseif(isset($HTTP_POST_VARS['unamef'])) $conf->uname=$HTTP_POST_VARS['unamef'];
      else $conf->uname='admin';

      //get password
      if(isset($GLOBALS['passwf'])) $conf->passw=$GLOBALS['passwf'];
      elseif(isset($HTTP_POST_VARS['passwf'])) $conf->passw=$HTTP_POST_VARS['passwf'];
      else $conf->passw='admin';

      //get view area protect
      if((isset($GLOBALS['vpass']))||(isset($HTTP_POST_VARS['vpass']))) $conf->vapass=0;
      else $conf->vapass=1;

      //admin mode
      if(isset($GLOBALS['amode'])) $conf->amode=$GLOBALS['amode'];
      elseif(isset($HTTP_POST_VARS['amode'])) $conf->amode=$HTTP_POST_VARS['amode'];
      else $conf->amode='auto';

      //image id
      if(isset($GLOBALS['amimg'])) $conf->amimg=$GLOBALS['amimg'];
      elseif(isset($HTTP_POST_VARS['amimg'])) $conf->amimg=$HTTP_POST_VARS['amimg'];
      else $conf->amimg='2';

      //color of digits
      if(isset($GLOBALS['amcolor'])) $conf->amcolor=$GLOBALS['amcolor'];
      elseif(isset($HTTP_POST_VARS['amcolor'])) $conf->amcolor=$HTTP_POST_VARS['amcolor'];
      else $conf->amcolor='0';
      if(preg_match("/^([0-9a-fA-F]+)/i",$conf->amcolor,$matches)) {
        $conf->amcolor=$matches[1];
        eval("\$conf->amcolor=0x$conf->amcolor;");
      }
      else $conf->amcolor=0;

      //statistics on button
      if(isset($GLOBALS['amstat'])) $conf->amstat=$GLOBALS['amstat'];
      elseif(isset($HTTP_POST_VARS['amstat'])) $conf->amstat=$HTTP_POST_VARS['amstat'];
      else $conf->amstat='1';

      //get style
      $styleold=$conf->style;
      if(isset($GLOBALS['stylelist'])) $conf->style=$GLOBALS['stylelist'];
      elseif(isset($HTTP_POST_VARS['stylelist'])) $conf->style=$HTTP_POST_VARS['stylelist'];
      else $conf->style='basic';

      //get language
      $langold=$conf->lang;
      if(isset($GLOBALS['langlist'])) $conf->lang=$GLOBALS['langlist'];
      elseif(isset($HTTP_POST_VARS['langlist'])) $conf->lang=$HTTP_POST_VARS['langlist'];
      else $conf->lang='english';

      //get time format
      if(isset($GLOBALS['timelist'])) $conf->tformat=$GLOBALS['timelist'];
      elseif(isset($HTTP_POST_VARS['timelist'])) $conf->tformat=$HTTP_POST_VARS['timelist'];
      else $conf->tformat=1;

      //get date format
      if(isset($GLOBALS['datelist'])) $conf->dformat=$GLOBALS['datelist'];
      elseif(isset($HTTP_POST_VARS['datelist'])) $conf->dformat=$HTTP_POST_VARS['datelist'];
      else $conf->dformat=1;

      //get daylight time
      if((isset($GLOBALS['dltime']))||(isset($HTTP_POST_VARS['dltime']))) $conf->dltime=0;
      else $conf->dltime=1;

      //save new settings
      $conf->saveconf();
      if($err->flag) {$err->reason('aset.php|rparams|can\'t save new settings');return;}

      //new name of 201 group
      $adb->name201();
      if($err->flag) {$err->reason('aset.php|rparams|can\'t set new name of group \'201\'');return;}

      //relogin in are changing language or style
      if(strcmp($langold,$conf->lang)||strcmp($styleold,$conf->style)) {
        Header('Location: ./admin.php');
        exit;
      }
    }
    elseif(!strcmp($this->act,'services')) {
      //get service
      if(isset($GLOBALS['service'])) $conf->services = $GLOBALS['service'];
      elseif(isset($HTTP_POST_VARS['service'])) $conf->services = $HTTP_POST_VARS['service'];
      else $conf->services = 0;

      //get e-mail address
      if(isset($GLOBALS['semail'])) $conf->semail=$GLOBALS['semail'];
      elseif(isset($HTTP_POST_VARS['semail'])) $conf->semail=$HTTP_POST_VARS['semail'];
      else $conf->semail='';

      //get group/page ID
      if(isset($GLOBALS['sgrpgid'])) $conf->sgrpgid=$GLOBALS['sgrpgid'];
      elseif(isset($HTTP_POST_VARS['sgrpgid'])) $conf->sgrpgid=$HTTP_POST_VARS['sgrpgid'];
      else $conf->sgrpgid='201';

      //get time interval
      if(isset($GLOBALS['stint'])) $conf->stint=$GLOBALS['stint'];
      elseif(isset($HTTP_POST_VARS['stint'])) $conf->stint=$HTTP_POST_VARS['stint'];
      else $conf->stint='yesterday';

      //get perorts
      $conf->sreports=0;
      if((isset($GLOBALS['report1']))||(isset($HTTP_POST_VARS['report1']))) $conf->sreports |= 1;
      if((isset($GLOBALS['report2']))||(isset($HTTP_POST_VARS['report2']))) $conf->sreports |= 2;

      //save new settings
      $conf->saveconf();
      if($err->flag) {$err->reason('aset.php|rparams|can\'t save new settings for services');return;}
    }
  }
  elseif(!strcmp($this->extact,'edit')) {
    $this->fun='edit';
  }
  elseif(!strcmp($this->extact,'delete')) {
    $this->fun='delgrpg';
  }
  elseif(!strcmp($this->extact,'back')) {
    $this->act=$this->param;
    $this->fun=$this->act;
  }
  elseif(!strcmp($this->extact,'confirm')) {
    if(!strcmp($this->param,'delete')) {
      //delete page/group
      $adb->delpggr($this->id);
      if($err->flag) {$err->reason('aset.php|rparams|deleting of page/group has failed');return;}

      //reset parameters
      if($this->id>200) $this->act='groups';
      else $this->act='pages';
      $this->fun=$this->act;
    }
    elseif(!strcmp($this->param,'reset')) {
      //new begin time
      $conf->btime=$conf->ctime;

      //delete statictics from database
      $adb->resetstat();
      if($err->flag) {$err->reason('aset.php|rparams|deleting of statistics from database has failed');return;}

      //save new settings
      $conf->saveconf();
      if($err->flag) {$err->reason('aset.php|rparams|can\'t save new settings');return;}

      //reset parameters
      $this->act='pages';
      $this->fun=$this->act;
    }
  }
}

//pages settings
/*-------------------------------------------------------*/
function pages() {
  global $err,$conf,$adb;

  $this->top();

  $this->cpanel();

  $adb->pages($this->vars);
  if($err->flag) {$err->reason('aset.php|pages|can\'t output the table with pages');return;}

  $this->bottom();
}

//groups settings
/*-------------------------------------------------------*/
function groups() {
  global $err,$conf,$adb;

  $this->top();
  $this->cpanel();

  $adb->groups($this->vars);
  if($err->flag) {$err->reason('aset.php|pages|can\'t output the table with pages');return;}

  $this->bottom();
}

//add new page
/*-------------------------------------------------------*/
function addpage() {
  global $err,$conf,$adb,$HTTP_POST_VARS;

  $this->top();
  $this->cpanel();

  if(isset($GLOBALS['img'])) $img=$GLOBALS['img'];
  elseif(isset($HTTP_POST_VARS['img'])) $img=$HTTP_POST_VARS['img'];
  else $img=2;
  if(isset($GLOBALS['dcolor'])) $dcolor=$GLOBALS['dcolor'];
  elseif(isset($HTTP_POST_VARS['dcolor'])) $dcolor=$HTTP_POST_VARS['dcolor'];
  else $dcolor=0;
  if(preg_match("/^([0-9a-fA-F]+)/i",$dcolor,$matches)) {
    $dcolor=$matches[1];
    eval("\$dcolor=0x$dcolor;");
  }
  else $dcolor=0;
  if(isset($GLOBALS['dflag'])) $dflag=$GLOBALS['dflag'];
  elseif(isset($HTTP_POST_VARS['dflag'])) $dflag=$HTTP_POST_VARS['dflag'];
  else $dflag=1;
  if((isset($GLOBALS['defpg']))||(isset($HTTP_POST_VARS['defpg']))) $defpg=1;
  else $defpg=0;

  $adb->addpage($this->vars,$img,$dcolor,$dflag,$defpg);
  if($err->flag) {$err->reason('aset.php|addpage|adding of new page has failed');return;}

  $this->bottom();
}

//add new group
/*-------------------------------------------------------*/
function addgroup() {
  global $err,$conf,$adb;

  $this->top();
  $this->cpanel();

  $adb->addgr($this->vars);
  if($err->flag) {$err->reason('aset.php|addgroup|adding of new group has failed');return;}

  $this->bottom();
}

//edit page/group
/*-------------------------------------------------------*/
function edit() {
  global $err,$conf,$adb,$HTTP_POST_VARS;

  $this->vars['ID']=$this->id;

  $this->top();
  $this->cpanel();

  if($this->id>200) $adb->editgr($this->id,$this->vars);
  else {
    if(isset($GLOBALS['img'])) $img=$GLOBALS['img'];
    elseif(isset($HTTP_POST_VARS['img'])) $img=$HTTP_POST_VARS['img'];
    else $img=0;
    if(isset($GLOBALS['dcolor'])) $dcolor=$GLOBALS['dcolor'];
    elseif(isset($HTTP_POST_VARS['dcolor'])) $dcolor=$HTTP_POST_VARS['dcolor'];
    else $dcolor=0;
    if(preg_match("/^([0-9a-fA-F]+)/i",$dcolor,$matches)) {
      $dcolor=$matches[1];
      eval("\$dcolor=0x$dcolor;");
    }
    else $dcolor=0;
    if(isset($GLOBALS['dflag'])) $dflag=$GLOBALS['dflag'];
    elseif(isset($HTTP_POST_VARS['dflag'])) $dflag=$HTTP_POST_VARS['dflag'];
    else $dflag=1;
    if((isset($GLOBALS['defpg']))||(isset($HTTP_POST_VARS['defpg']))) $defpg=1;
    else $defpg=0;

    $adb->editpg($this->vars,$this->id,$img,$dcolor,$dflag,$defpg);
  }
  if($err->flag) {$err->reason('aset.php|edit|can\'t edit page/group');return;}

  $this->bottom();
}

//settings
/*-------------------------------------------------------*/
function settings() {
  global $err,$conf,$adb;

  $this->top();
  $this->cpanel();

  require './style/'.$conf->style.'/template/at_set.php';

  //security settings
  $this->vars['HEADER']=_SETTINGS;
  $this->vars['STEPS']=_STEP.' 1 '._OUTOF.' 1';
  $this->vars['SHEADER']=_SECURITYSET;
  $this->vars['NAMEDESC']=_UNAMEDESC;
  $this->vars['PASSWDESC']=_UPASSWDESC;
  tparse($top,$this->vars);

  $this->vars['VPDESC']=_AUTHDESC;
  if($conf->vapass) $this->vars['VPCHECK']='';
  else $this->vars['VPCHECK']='checked';
  tparse($sec_ext,$this->vars);

  $this->vars['THEADER']=_ADMINSET;
  tparse($sec_end,$this->vars);

  //list of modes
  $this->vars['LNAME']='amode';
  tparse($listbeg,$this->vars);
  $this->vars['VALUE']='auto';
  if(!strcmp($this->vars['VALUE'],$conf->amode)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_AUTOMODE;
  tparse($center,$this->vars);
  $this->vars['VALUE']='manual';
  if(!strcmp($this->vars['VALUE'],$conf->amode)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_MANUALMODE;
  tparse($center,$this->vars);
  $this->vars['LDESC']=_MODEDESC;
  tparse($listend,$this->vars);

  $this->vars['THEADER']=_PGSETFORAUTO;
  tparse($sec_end,$this->vars);

  //page settings by default for auto mode
  $this->vars['IMG']=$conf->amimg;
  $this->vars['DCOLOR']=$conf->amcolor;
  $this->vars['DFLAG']=$conf->amstat;
  tparse($psimg,$this->vars);

  $imglist=array();
  //list of available buttons
  $catalog=opendir('./style/'.$conf->style.'/image/buttons');
  while(($file=readdir($catalog))!=FALSE) {
    if($file!="."&&$file!="..") {
      if(preg_match("/^([0-9]+)\.png$/i",$file,$matches)) {
        $imglist[]=$matches[1];
      }
    }
  }
  closedir($catalog);

  natsort($imglist);

  reset($imglist);
  while($e=each($imglist)) {
    $this->vars['VALUE']=$e[1];
    if(!strcmp($this->vars['VALUE'],$conf->amimg)) $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    $this->vars['ITEM']=$e[1];
    tparse($center,$this->vars);
  }

  $this->vars['IMGDESC']=_IMGDESC;
  tparse($psimgend,$this->vars);

  if($conf->amimg>100) {
      $this->vars['DCOLOR']=sprintf('%06X',$conf->amcolor);
      $this->vars['DCOLORDESC']=_DCOLORDESC;
      tparse($pscolor,$this->vars);

      $this->vars['VALUE']='1';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG1;
      tparse($center,$this->vars);
      $this->vars['VALUE']='2';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG2;
      tparse($center,$this->vars);
      $this->vars['VALUE']='3';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG3;
      tparse($center,$this->vars);
      $this->vars['VALUE']='4';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG4;
      tparse($center,$this->vars);
      $this->vars['VALUE']='5';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG5;
      tparse($center,$this->vars);
      $this->vars['VALUE']='6';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG6;
      tparse($center,$this->vars);
      $this->vars['VALUE']='7';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG7;
      tparse($center,$this->vars);
      $this->vars['VALUE']='8';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG8;
      tparse($center,$this->vars);
      $this->vars['VALUE']='9';
      if(!strcmp($this->vars['VALUE'],$conf->amstat)) $this->vars['SELECTED']=' selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=_DFLAG9;
      tparse($center,$this->vars);

     $this->vars['DFLAGDESC']=_DFLAGDESC;
     tparse($psstatend,$this->vars);
  }

  $this->vars['THEADER']=_VIEWSET;
  tparse($sec_end,$this->vars);

  //list of languages
  $catalog=opendir('./lang');
  $this->vars['LNAME']='langlist';
  tparse($listbeg,$this->vars);

  while(($file=readdir($catalog))!=FALSE) {
    if($file!="."&&$file!="..") {
      $arr=preg_split("/\./",$file);
      $this->vars['VALUE']=$arr[0];
      if(!strcmp($arr[0],$conf->lang)) $this->vars['SELECTED']='selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=$arr[0];
      tparse($center,$this->vars);
    }
  }
  $this->vars['LDESC']=_LANGDESC;
  tparse($listend,$this->vars);
  closedir($catalog);

  //list of styles
  $catalog=opendir('./style');
  $this->vars['LNAME']='stylelist';
  tparse($listbeg,$this->vars);
  while(($file=readdir($catalog))!=FALSE) {
    if($file!="."&&$file!="..") {
      $this->vars['VALUE']=$file;
      if(!strcmp($file,$conf->style)) $this->vars['SELECTED']='selected';
      else $this->vars['SELECTED']='';
      $this->vars['ITEM']=$file;
      tparse($center,$this->vars);
    }
  }
  $this->vars['LDESC']=_STYLEDESC;
  tparse($listend,$this->vars);
  closedir($catalog);

  $this->vars['THEADER']=_TIMESET;
  tparse($middle,$this->vars);

  //list of time formats
  $this->vars['LNAME']='timelist';
  tparse($listbeg2,$this->vars);
  for($i=1;$i<=sizeof($conf->tmas);$i++) {
    $this->vars['VALUE']=$i;
    if($i==$conf->tformat) $this->vars['SELECTED']='selected';
    else $this->vars['SELECTED']='';
    $this->vars['ITEM']=$conf->tmasform[$i].' (example: '.date($conf->tmas[$i],$conf->ctime).')';
    tparse($center,$this->vars);
  }
  $this->vars['LDESC']=_TIMEDESC;
  tparse($listend2,$this->vars);

  //list of date formats
  $this->vars['LNAME']='datelist';
  tparse($listbeg2,$this->vars);
  for($i=1;$i<=sizeof($conf->dmas);$i++) {
    $this->vars['VALUE']=$i;
    if($i==$conf->dformat) $this->vars['SELECTED']='selected';
    else $this->vars['SELECTED']='';
    $this->vars['ITEM']=$conf->dmasform[$i].' (example: '.date($conf->dmas[$i],$conf->ctime).')';
    tparse($center,$this->vars);
  }
  $this->vars['LDESC']=_DATEFDESC;
  tparse($listend2,$this->vars);

  if($conf->dltime) $this->vars['DLCHECK']='';
  else $this->vars['DLCHECK']='checked';
  $this->vars['DLDESC']=_DLTIMEDESC;
  $this->vars['BACKTT']=_BACKTOTOP;
  $this->vars['SUBMIT']=_SUBMIT;
  tparse($bottom,$this->vars);

  $this->bottom();
}

//textarea with general HTML code
/*-------------------------------------------------------*/
function genhtml() {
  global $err,$conf;

  $this->top();
  $this->cpanel();

  require './style/'.$conf->style.'/template/codev.php';
  $this->vars['HEADER']=_GENHTML;
  $this->vars['DESC1']=_HTMLACT;
  tparse($begin,$this->vars);

  require './data/htmlgen.php';

  //output HTML-code
  $this->vars['TITLE']=_STATISTICS;
  $this->vars['VERSION']=$conf->version;
  $this->vars['URL']=$conf->url;
  $this->vars['COPY']=_COPYTOCLIP;
  tparse($code,$this->vars);

  tparse($end,$this->vars);

  $this->bottom();
}

//services
/*-------------------------------------------------------*/
function services() {
  global $err,$conf,$adb;

  $this->top();
  $this->cpanel();

  require './style/'.$conf->style.'/template/at_serv.php';

  $this->vars['HEADER']=_SERVICES;
  $this->vars['STEPS']=_STEP.' 1 '._OUTOF.' 1';
  $this->vars['SHEADER']=_SERVSET;
  tparse($top,$this->vars);

  //list of services
  $this->vars['VALUE']='0';
  if(!strcmp($this->vars['VALUE'],$conf->services)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_NONE;
  tparse($list,$this->vars);
  $this->vars['VALUE']='1';
  if(!strcmp($this->vars['VALUE'],$conf->services)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_STATONEMAIL;
  tparse($list,$this->vars);

  $this->vars['SDESC']=_SERVDESC;
  $this->vars['EMAIL']=$conf->semail;
  $this->vars['EMAILDESC']=_EMAILDESC;
  $this->vars['RHEADER']=_REPORTSET;
  tparse($toprep,$this->vars);

  //add all groups
  $groups=array();
  $groups[201]='All pages';
  $groups=$adb->getgrs();
  reset($groups);
  while ($k=key($groups)) {
    $this->vars['ITEM']=_GROUP.': '.$groups[$k];
    $this->vars['VALUE']=$k;
    if(!strcmp($conf->sgrpgid,$k))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($list,$this->vars);
    next($groups);
  }

  //add all pages
  $groups=$adb->getpages();
  reset($groups);
  while ($k=key($groups)) {
    $fname=$groups[$k];
    if(strlen($fname)>_VS_PGSLIST) $sname=substr($fname,0,_VS_PGSLIST-3).'...';
    else $sname=$fname;
    $this->vars['ITEM']=_PAGE.': '.$sname;
    $this->vars['VALUE']=$k;
    if(!strcmp($conf->sgrpgid,$k))   $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($list,$this->vars);
    next($groups);
  }

  $this->vars['GRPGDESC']=_GRPGDESC;
  tparse($centerrep,$this->vars);

  //list of time intervals
  $this->vars['VALUE']='yesterday';
  if(!strcmp($this->vars['VALUE'],$conf->stint)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_DAILY;
  tparse($list,$this->vars);
  $this->vars['VALUE']='lastweek';
  if(!strcmp($this->vars['VALUE'],$conf->stint)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_WEEKLY;
  tparse($list,$this->vars);
  $this->vars['VALUE']='lastmonth';
  if(!strcmp($this->vars['VALUE'],$conf->stint)) $this->vars['SELECTED']='selected';
  else $this->vars['SELECTED']='';
  $this->vars['ITEM']=_MONTHLY;
  tparse($list,$this->vars);

  $this->vars['TINTDESC']=_TINTDESC;
  $this->vars['RSHEADER']=_REPORTS;
  tparse($endrep,$this->vars);

  //list of reports
  $this->vars['RNAME']=_SUMMARY;
  $this->vars['ID']='1';
  if($conf->sreports & 0x1) $this->vars['VALUE']='checked';
  else $this->vars['VALUE']='';
  tparse($report,$this->vars);
  $this->vars['RNAME']=_REFSERVS;
  $this->vars['ID']='2';
  if($conf->sreports & 0x2) $this->vars['VALUE']='checked';
  else $this->vars['VALUE']='';
  tparse($report,$this->vars);

  $this->vars['DLDESC']=_DLTIMEDESC;
  $this->vars['BACKTT']=_BACKTOTOP;
  $this->vars['SUBMIT']=_SUBMIT;
  tparse($bottom,$this->vars);

  $this->bottom();
}

//delete page/group confirmation
/*-------------------------------------------------------*/
function delgrpg() {
  global $err,$conf,$adb;

  $this->top();

  $name='';$img=0;$uid=0;$url='';
  if($this->id<201) $adb->getparampg($this->id,$name,$img,$uid,$url);
  else $adb->getnamegrpg($this->id,$name);

  if($err->flag) {$err->reason('aset.php|delgrpg|can\'t get name of group/page');return;}

  require './style/'.$conf->style.'/template/at_cfm.php';

  $this->vars['HEADER']=_CONFIRMATION;
  if($this->id<201) {
    $fname=$name;
    if(strlen($fname)>_AS_PGDELCONF) $sname=substr($fname,0,_AS_PGDELCONF-3).'...';
    else $sname=$fname;
    $this->vars['CONFIRM']=_DELPGCONF;
    $this->vars['URL']=$url;
    $this->vars['PAGE']=$fname;
    $this->vars['PAGESHORT']=$sname;
  }
  else {
    $this->vars['CONFIRM']=_DELGRCONF;
    $this->vars['GROUP']=$name;
  }
  $this->vars['ACT']=$this->act;
  $this->vars['PARAM']='delete';
  $this->vars['PGID']=$this->id;
  $this->vars['BACK']=_BACK;
  $this->vars['NEXT']=_CONFIRM;
  if($this->id<201) tparse($topp,$this->vars);
  else tparse($topg,$this->vars);

  $this->bottom();
}

//reset confirmation
/*-------------------------------------------------------*/
function reset() {
  global $err,$conf;

  $this->top();

  require './style/'.$conf->style.'/template/at_cfm.php';

  $this->vars['HEADER']=_CONFIRMATION;
  $this->vars['CONFIRM']=_RESETCONF;
  $this->vars['ACT']='pages';
  $this->vars['PARAM']='reset';
  $this->vars['PGID']=$this->id;
  $this->vars['BACK']=_BACK;
  $this->vars['NEXT']=_CONFIRM;
  tparse($cmess,$this->vars);

  $this->bottom();
}

//top of page
/*-------------------------------------------------------*/
function top() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/top.php';
  $this->vars['SCRIPT']='admin';
  tparse($top,$this->vars);
}

//control panel
/*-------------------------------------------------------*/
function cpanel() {
  global $err,$conf,$adb;

  require './style/'.$conf->style.'/template/at_ctrl.php';

  $this->vars['NAME']=_SECTION;
  $this->vars['HEADER']=_CPANEL;
  $this->vars['CTIME']=_ISSUE.'&nbsp;&nbsp;';
  $this->vars['CTIME'].=date($conf->dmas[$conf->dformat],$conf->ctime).'&nbsp;&nbsp;'.date($conf->tmas[$conf->tformat],$conf->ctime).'&nbsp;&nbsp;';
  if($conf->tzone>0) $this->vars['CTIME'].='+'.$conf->tzone;
  elseif($conf->tzone<0) $this->vars['CTIME'].=$conf->tzone;
  $this->vars['CTIME'].=' GMT ';
  tparse($top,$this->vars);

  //view button
  $this->vars['FOLDER']='view.php?style='.$conf->style.'&language='.$conf->lang;
  $this->vars['MODULE']='aa';
  $this->vars['TITLE']=_VIEWAREA;
  tparse($button,$this->vars);

  //elog button
  $this->vars['FOLDER']='elog.php?style='.$conf->style.'&language='.$conf->lang;
  $this->vars['MODULE']='elog';
  $this->vars['TITLE']=_ERRSLOG;
  tparse($button,$this->vars);

  tparse($top2,$this->vars);

  //Sections list
  $this->vars['VALUE']='pages';
  $this->vars['NAME']=_PAGES;
  if(!strcmp($this->act,'pages'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='groups';
  $this->vars['NAME']=_GROUPS;
  if(!strcmp($this->act,'groups'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='addpage';
  $this->vars['NAME']=_CONNECTPG;
  if(!strcmp($this->act,'addpage'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='addgroup';
  $this->vars['NAME']=_CREATEGR;
  if(!strcmp($this->act,'addgroup'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='genhtml';
  $this->vars['NAME']=_GENHTML;
  if(!strcmp($this->act,'genhtml'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='services';
  $this->vars['NAME']=_SERVICES;
  if(!strcmp($this->act,'services'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='settings';
  $this->vars['NAME']=_SETTINGS;
  if(!strcmp($this->act,'settings'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);
  $this->vars['VALUE']='reset';
  $this->vars['NAME']=_RESET;
  if(!strcmp($this->act,'reset'))   $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($opt,$this->vars);

  $this->vars['SUBMIT']=_SELECT;
  tparse($bottom,$this->vars);
}

//bottom of page
/*-------------------------------------------------------*/
function bottom() {
  global $err,$conf;

  require './style/'.$conf->style.'/template/bottom.php';
  tparse($bottom,$this->vars);
}

//input error message   /-----------------------------------------------------//
function ierr() {
  global $err,$conf;

  $this->top();

  $this->vars['HEADER']=_ALERT;
  $this->vars['MESS']=$this->param;
  $this->vars['BACK']=_BACK;
  require './style/'.$conf->style.'/template/back.php';
  tparse($cmess,$this->vars);

  $this->bottom();
}

}

?>
