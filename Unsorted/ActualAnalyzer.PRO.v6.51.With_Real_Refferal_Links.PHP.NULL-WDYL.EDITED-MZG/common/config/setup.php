<?php

  //setup
  $vars= array();

  //get language
  if(isset($GLOBALS['langlist'])) $this->lang=$GLOBALS['langlist'];
  elseif(isset($HTTP_POST_VARS['langlist'])) $this->lang=$HTTP_POST_VARS['langlist'];

  //language
  $lang=$this->rf.'lang/'.$this->lang.'.php';
  if(file_exists($lang)) require $lang;
  else require $this->rf.'lang/english.php';

  //style
  $style=$this->rf.'style/'.$this->style.'/style.php';
  if(file_exists($style)) require $style;

  //step
  if(isset($GLOBALS['extact_h'])) $step=$GLOBALS['extact_h'];
  elseif(isset($HTTP_POST_VARS['extact_h'])) $step=$HTTP_POST_VARS['extact_h'];
  else $step='';

  //top
  require $this->rf.'style/'.$this->style.'/template/top.php';
  //globals variables
  $vars['RF']=$this->rf;
  $vars['SCROLL']='';
  $vars['ID']='';
  $vars['P2']='';
  $vars['SCRIPT']='admin';
  $vars['LANG']=$this->lang;
  $vars['STYLE']=$this->style;
  $vars['VERSION']=_VERSION;
  $vars['VER']=$this->version;

  //check last version
  $flag=false;
  if(isset($GLOBALS['anver'])) {$flag=true;$ver=$GLOBALS['anver'];}
  elseif(isset($HTTP_COOKIE_VARS['anver'])) {$flag=true;$ver=$HTTP_COOKIE_VARS['anver'];}
  else {
    $checkver='Nullified by WTN-WDYL'.$this->version;
    $update='';
  }
  if($flag) {
    if($this->version<$ver) $update='&nbsp;&nbsp;<a style="color:#FF4000" href="'.$this->site.'" target=_blank>'._LASTVER.' '.$ver.'</a>&nbsp;&nbsp;';
    else $update='';
  }
  $vars['UPDATE']=$update;

  $vars['FAQ']=_FAQ;
  $vars['OLDACT']='';
  $vars['SUPPORT']=_SUPPORT;
  $vars['CHARSET']=_CHARSET;
  $vars['SERIES']=$this->series;
  $vars['TITLE']='ActualAnalyzer '.$this->series.' - '._ADMINAREA;
  $vars['UNAME']=$this->uname;
  $vars['PASSW']=$this->passw;
  $vars['SITE']=$this->site;
  tparse($top,$vars);

  //step3
  if(!strcmp($step,'step2')) {
    if(isset($GLOBALS['fldurl'])) $this->url=$GLOBALS['fldurl'];
    elseif(isset($HTTP_POST_VARS['fldurl'])) $this->url=$HTTP_POST_VARS['fldurl'];
    if(isset($GLOBALS['fldhost'])) $this->dbhost=$GLOBALS['fldhost'];
    elseif(isset($HTTP_POST_VARS['fldhost'])) $this->dbhost=$HTTP_POST_VARS['fldhost'];
    if(isset($GLOBALS['fldbase'])) $this->dbase=$GLOBALS['fldbase'];
    elseif(isset($HTTP_POST_VARS['fldbase'])) $this->dbase=$HTTP_POST_VARS['fldbase'];
    if(isset($GLOBALS['flduser'])) $this->dbuser=$GLOBALS['flduser'];
    elseif(isset($HTTP_POST_VARS['flduser'])) $this->dbuser=$HTTP_POST_VARS['flduser'];
    if(isset($GLOBALS['fldpass'])) $this->dbpass=$GLOBALS['fldpass'];
    elseif(isset($HTTP_POST_VARS['fldpass'])) $this->dbpass=$HTTP_POST_VARS['fldpass'];
    if(isset($GLOBALS['fldtzone'])) $this->tzone=$GLOBALS['fldtzone'];
    elseif(isset($HTTP_POST_VARS['fldtzone'])) $this->tzone=$HTTP_POST_VARS['fldtzone'];

    $this->btime+=($this->tzone*$this->timeh);

    $this->fs_diagnostics($results);
    if($err->flag) {$err->reason('config.php|setup|diagnostics of file system has failed');$err->log_out();}
    $this->db_diagnostics($results);
    if($err->flag) {$err->reason('config.php|setup|diagnostics of database has failed');$err->log_out();}

    require $this->rf.'/style/'.$this->style.'/template/set3.php';
    $vars['LANG']=$this->lang;
    $vars['HEADER']=_SETUP;
    $vars['STEPS']=_STEP.' 3 '._OUTOF.' 3';
    $vars['FSHEADER']=_FSTESTRES;
    tparse($top,$vars);

    //test of results
    $vars['OK']=_OK;
    $vars['FAIL']=_FAIL;
    $vars['SKIP']=_SKIP;
    $flag=0;
    //create file test result
    $vars['TNAME']=_TCRFILE;
    if(!strcmp($results[1],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[1],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //write to file test result
    $vars['TNAME']=_WRTOFILE;
    if(!strcmp($results[2],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[2],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //open file test result
    $vars['TNAME']=_OPENFILE;
    if(!strcmp($results[3],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[3],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //read from file test result
    $vars['TNAME']=_REFROMFILE;
    if(!strcmp($results[4],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[4],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //delete file test result
    $vars['TNAME']=_DELFILE;
    if(!strcmp($results[5],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[5],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}

    $vars['DBHEADER']=_DBTESTRES;
    tparse($center,$vars);

    //connect to MySQL server test result
    $vars['TNAME']=_CONNECTTODB;
    if(!strcmp($results[11],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[11],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //select/create database test result
    $vars['TNAME']=_DBSELCR;
    if(!strcmp($results[12],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[12],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //create table test result
    $vars['TNAME']=_DBCRTABLE;
    if(!strcmp($results[13],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[13],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //lock permissions
    $vars['TNAME']=_DBLOCKTABLE;
    if(!strcmp($results[19],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[19],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //insert records into table test result
    $vars['TNAME']=_DBINSREC;
    if(!strcmp($results[14],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[14],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //update records in table test result
    $vars['TNAME']=_DBUPDREC;
    if(!strcmp($results[15],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[15],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //select records from table test result
    $vars['TNAME']=_DBSELREC;
    if(!strcmp($results[16],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[16],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //delete records from table test result
    $vars['TNAME']=_DBDELREC;
    if(!strcmp($results[17],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[17],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}
    //delete table test result
    $vars['TNAME']=_DBDELTAB;
    if(!strcmp($results[18],'Ok')) tparse($c_ok,$vars);
    elseif(!strcmp($results[18],'Fail')) {$flag=1;tparse($c_fail,$vars);}
    else {$flag=1;tparse($c_skip,$vars);}

    if($flag) {
      $vars['WAY']='back';
      $vars['NEXT']=_BACK;
      $vars['STEP']='step1';
    }
    else {
      $vars['WAY']='go';
      $vars['NEXT']=_NEXT;
      $vars['STEP']='step3';
      //create tables
      $this->crtables();
      if($err->flag) {$err->reason('config.php|setup|creating of tables has failed|');return;}
      //save setting
      $this->saveconf();
      if($err->flag) {$err->reason('config.php|setup|can\'t save settings|');return;}
    }
    tparse($bottom,$vars);
  }
  //step2
  elseif(!strcmp($step,'step1')) {

    $rurl='http://';
    if(isset($GLOBALS['HTTP_HOST'])) $rurl.=$GLOBALS['HTTP_HOST'];
    elseif(isset($HTTP_SERVER_VARS['HTTP_HOST'])) $rurl.=$HTTP_SERVER_VARS['HTTP_HOST'];
    elseif(isset($GLOBALS['SERVER_NAME'])) $rurl.=$GLOBALS['SERVER_NAME'];
    elseif(isset($HTTP_SERVER_VARS['SERVER_NAME'])) $rurl.=$HTTP_SERVER_VARS['SERVER_NAME'];
    else $rurl='';
    if(isset($GLOBALS['REQUEST_URI'])) $rurl.=$GLOBALS['REQUEST_URI'];
    elseif(isset($HTTP_SERVER_VARS['REQUEST_URI'])) $rurl.=$HTTP_SERVER_VARS['REQUEST_URI'];
    elseif(isset($GLOBALS['PATH_INFO'])) $rurl.=$GLOBALS['PATH_INFO'];
    elseif(isset($HTTP_SERVER_VARS['PATH_INFO'])) $rurl.=$HTTP_SERVER_VARS['PATH_INFO'];
    $urlarr=preg_split("/\//",$rurl);
    $max=sizeof($urlarr);
    $urlarr[$max-1]='';
    $rurl=join('/',$urlarr);

    require $this->rf.'style/'.$this->style.'/template/set2.php';
    //default parameters
    $vars['LANG']=$this->lang;
    $vars['HEADER']=_SETUP;
    $vars['STEPS']=_STEP.' 2 '._OUTOF.' 3';
    $vars['SHEADER']=_SCROPTS;
    $vars['FLDURL']=$rurl;
    $vars['URLDESC']=_URLDESC1.$this->folder._URLDESC2;
    $vars['FOLDER']=$this->folder;
    $vars['DHEADER']=_DBOPTIONS;
    $vars['FLDHOST']=$this->dbhost;
    $vars['HOSTDESC']=_DBHOSTDESC;
    $vars['FLDBASE']=$this->dbase;
    $vars['BASEDESC']=_DBDESC;
    $vars['FLDUSER']=$this->dbuser;
    $vars['USERDESC']=_DBUSERDESC;
    $vars['FLDPASS']=$this->dbpass;
    $vars['PASSDESC']=_DBPASSDESC;
    $vars['THEADER']=_TOPTIONS;
    $vars['TZONEDESC']=_TZONEDESC;
    $vars['BACK']=_BACK;
    $vars['NEXT']=_NEXT;
    tparse($set,$vars);
  }
  //step1
  else {
    require $this->rf.'style/'.$this->style.'/template/set1.php';
    $vars['HEADER']=_SETUP;
    $vars['STEPS']=_STEP.' 1 '._OUTOF.' 3';
    $vars['THEADER']=_LANGSET;
    tparse($top,$vars);

    //list of languages
    $catalog=opendir($this->rf.'lang');
    while(($file=readdir($catalog))!=FALSE) {
      if($file!="."&&$file!="..") {
        $arr=preg_split("/\./",$file);
        $vars['VALUE']=$arr[0];
        if(!strcmp($arr[0],$this->lang)) $vars['SELECTED']='selected';
        else $vars['SELECTED']='';
        $vars['ITEM']=$arr[0].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        tparse($center,$vars);
      }
    }

    $vars['LANGDESC']=_LANGDESC;
    $vars['NEXT']=_NEXT;
    tparse($bottom,$vars);
  }

  //bottom
  require $this->rf.'style/'.$this->style.'/template/bottom.php';
  tparse($bottom,$vars);

  //output HTML page
  out();

  exit;

?>
