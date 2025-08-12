<?php

class config {

var $rf;
var $name;
var $url;
var $dbhost;
var $dbport;
var $dbase;
var $dbuser;
var $dbpass;
var $uname;
var $passw;
var $vapass;
var $tzone;
var $version;
var $lang;
var $style;
var $link;
var $ctime;
var $btime;
var $time1;
var $timeh;
var $htime;
var $hnum;
var $dtime;
var $dnum;
var $mnum;
var $mtime;
var $lmtime;
var $lpmtime;
var $nmtime;
var $wtime;
var $lwtime;
var $nwtime;
var $dformat;
var $tformat;
var $block;
var $tmas;
var $dmas;
var $tmasform;
var $dmasform;
var $tonline;
var $tonline;
var $services;
var $sreports;
var $sgrpgid;
var $stint;
var $semail;
var $folder;
var $amode;
var $amimg;
var $amcolor;
var $amstat;
var $mrrefb;
var $mrrefl;
var $mrfrmb;
var $mrfrml;
var $mrkeyb;
var $mrkeyl;
var $mrprvb;
var $mrprvl;
var $mrprxb;
var $mrprxl;
var $mrrawl;
var $mrhosts;
var $mrrdata;

function config($rf,$setupini=true) {
  global $err,$HTTP_POST_VARS,$HTTP_GET_VARS;

  $this->rf=$rf;                  //root folder
  $this->version=6.51;            //current version
  $this->name='ActualAnalyzer';   //Product name
  $this->series='Pro';            //current series
  $this->url='./';                //url of the analyzer folder
  $this->site='http://www.yourdomain.com/';   //home page
  $this->folder='pro';           //default folder
  $this->dbhost='localhost:3306'; //host of database
  $this->dbase='analyzer';        //database name
  $this->dbuser='admin';          //user name (for database)
  $this->dbpass='admin';          //password (for database)
  $this->uname='admin';           //user name (for login)
  $this->passw='admin';           //password (for login)
  $this->vapass=0;                //authentication for view area
  $this->amode='auto';            //page's connecting mode
  $this->amimg=2;                 //button by default
  $this->amcolor=0;               //color by default
  $this->amstat=1;                //statistics on button by defailt
  $this->tzone=0;                 //time zone
  $this->dformat=1;               //date format
  $this->tformat=1;               //time format
  $this->dltime=0;                //daylight time
  $this->lang='english';          //language
  $this->style='basic';           //style of templates
  $this->services=0;              //selected services
  $this->sreports=1;              //selected reports for services
  $this->sgrpgid=201;             //id of group/page for services
  $this->stint='yesterday';       //time interval for services
  $this->semail='';               //e-mail address for services
  $this->link=0;                  //link to current open database
  $this->tonline=180;             //online time in seconds
  $this->time1=86400;             //number of seconds in one day
  $this->timeh=3600;              //number of seconds in one hour
  $this->block=0;                 //for locks

  $this->mrrefb=50000;
  $this->mrrefl=300000;
  $this->mrfrmb=10000;
  $this->mrfrml=300000;
  $this->mrkeyb=10000;
  $this->mrkeyl=300000;
  $this->mrprvb=10000;
  $this->mrprvl=300000;
  $this->mrprxb=10000;
  $this->mrprxl=300000;
  $this->mrrawl=50000;
  $this->mrhosts=100000;
  $this->mrrdata=3;

  $this->tmas[1]='G:i:s';         //time formats
  $this->tmas[2]='H:i:s';
  $this->tmas[3]='g:i:s A';
  $this->tmas[4]='h:i:s A';
  $this->tmasform[1]='H:mm:ss';  //time formats descriptions
  $this->tmasform[2]='HH:mm:ss';
  $this->tmasform[3]='h:mm:ss tt';
  $this->tmasform[4]='hh:mm:ss tt';
  $this->dmas[1]='j F Y';         //date formats
  $this->dmas[2]='d F Y';
  $this->dmas[3]='d-M-y';
  $this->dmas[4]='d-M-Y';
  $this->dmas[5]='j-M-y';
  $this->dmas[6]='j-M-Y';
  $this->dmas[7]='m/d/y';
  $this->dmas[8]='m/d/Y';
  $this->dmas[9]='n/j/y';
  $this->dmas[10]='n/j/Y';
  $this->dmas[11]='y/m/d';
  $this->dmas[12]='Y/m/d';
  $this->dmas[13]='y/n/j';
  $this->dmas[14]='Y/n/j';
  $this->dmas[15]='Y-m-d';
  $this->dmas[16]='Y-n-j';
  $this->dmas[17]='d.m.y';
  $this->dmas[18]='d.m.Y';
  $this->dmas[19]='j.n.y';
  $this->dmas[20]='j.n.Y';
  $this->dmasform[1]='d MMMM yyyy';
  $this->dmasform[2]='dd MMMM yyyy';
  $this->dmasform[3]='dd-MMM-yy';
  $this->dmasform[4]='dd-MMM-yyyy';
  $this->dmasform[5]='d-MMM-yy';
  $this->dmasform[6]='d-MMM-yyyy';
  $this->dmasform[7]='mm/dd/yy';   //date formats descriptions
  $this->dmasform[8]='mm/dd/yyyy';
  $this->dmasform[9]='m/d/yy';
  $this->dmasform[10]='m/d/yyyy';
  $this->dmasform[11]='yy/mm/dd';
  $this->dmasform[12]='yyyy/mm/dd';
  $this->dmasform[13]='yy/m/d';
  $this->dmasform[14]='yyyy/m/d';
  $this->dmasform[15]='yyyy-mm-dd';
  $this->dmasform[16]='yyyy-m-d';
  $this->dmasform[17]='dd.mm.yy';
  $this->dmasform[18]='dd.mm.yyyy';
  $this->dmasform[19]='d.m.yy';
  $this->dmasform[20]='d.m.yyyy';

  //correct current time to current time zone
  $t=time();
  $tm=gmdate("n",$t);
  $td=gmdate("j",$t);
  $ty=gmdate("Y",$t);
  $th=gmdate("G",$t);
  $ti=gmdate("i",$t);
  $ts=gmdate("s",$t);
  $this->ctime=mktime($th,$ti,$ts,$tm,$td,$ty,$this->dltime);      //current GMT time
  $this->btime=$this->ctime;      //begin time for GMT

  //setup
  $sflag=false;
  if(!file_exists($this->rf.'cdata.php')&&($setupini)) {
    $sflag=true;
    $this->setup();
    if($err->flag) {$err->reason('config.php|config|\'setup\' function has failed');return;}
  }

  //load config
  if(file_exists($this->rf.'cdata.php')) {
    //read config
    read_file($this->rf.'cdata.php',$fdata);
    if($err->flag) {$err->reason('config.php|config|reading of config data has failed');return;}
    reset($fdata);
    next($fdata);
    while ($k=key($fdata)) {
      $tarray = preg_split("/\=/",$fdata[$k]);
      if(count($tarray)==2) {
        $tname=trim($tarray[0]);
        $tvalue=trim($tarray[1]);
        if(isset($this->$tname)) $this->$tname=$tvalue;
      }
      next($fdata);
    }
  }

  //correct time
  $this->ctime+=($this->tzone*$this->timeh);
  $db=getdate($this->btime);
  $dc=getdate($this->ctime);
  //hours
  $hbtime=mktime($db['hours'],0,0,$db['mon'],$db['mday'],$db['year'],0);
  //time of the current hour
  $this->htime=mktime($dc['hours'],0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  //number of current hour
  $this->hnum=($this->htime-$hbtime)/$this->timeh;
  //days
  $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year'],0);
  //time of the current day
  $this->dtime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  //number of current day
  $this->dnum=(int)sprintf("%d",($this->dtime-$hbtime)/$this->time1);
  //number of the current month
  $this->mnum=($dc['year']-$db['year'])*12+$dc['mon']-$db['mon'];
  //begin time of the current month
  $this->mtime=mktime(0,0,0,$dc['mon'],1,$dc['year'],0);
  //begin time of the last month
  $this->lmtime=mktime(0,0,0,$dc['mon']-1,1,$dc['year'],0);
  //begin time of the last month
  $this->lpmtime=mktime(0,0,0,$dc['mon']-2,1,$dc['year'],0);
  //begin time of the next month
  $this->nmtime=mktime(0,0,0,$dc['mon']+1,1,$dc['year'],0);
  //begin time of the current week
  if($dc['wday']==0) $num=6;
  else $num=$dc['wday']-1;
  $this->wtime=mktime(0,0,0,$dc['mon'],$dc['mday']-$num,$dc['year'],0);
  //begin time of the last week
  $this->lwtime=mktime(0,0,0,$dc['mon'],$dc['mday']-7-$num,$dc['year'],0);
  //begin time of the next week
  $this->nwtime=mktime(0,0,0,$dc['mon'],$dc['mday']+7-$num,$dc['year'],0);

  //dynamic setting of language
  if(isset($GLOBALS['language'])) $ilang=$GLOBALS['language'];
  elseif(isset($HTTP_GET_VARS['language'])) $ilang=$HTTP_GET_VARS['language'];
  elseif(isset($HTTP_POST_VARS['language'])) $ilang=$HTTP_POST_VARS['language'];
  else $ilang='';
  if(!empty($ilang)) $this->lang=$ilang;

  //dynamic setting of style
  if(isset($GLOBALS['style'])) $istyle=$GLOBALS['style'];
  elseif(isset($HTTP_GET_VARS['style'])) $istyle=$HTTP_GET_VARS['style'];
  elseif(isset($HTTP_POST_VARS['style'])) $istyle=$HTTP_POST_VARS['style'];
  else $istyle='';
  if(!empty($istyle)) $this->style=$istyle;

  if(!$sflag) {
    //language
    $lang=$this->rf.'lang/'.$this->lang.'.php';
    if(file_exists($lang)) require $lang;
    else require $this->rf.'lang/english.php';

    //style
    $style=$this->rf.'style/'.$this->style.'/style.php';
    if(file_exists($style)) require $style;
  }
}

//===================================================================
function setup() {
  global $err,$HTTP_POST_VARS,$HTTP_COOKIE_VARS,$HTTP_SERVER_VARS;

  require './common/config/setup.php';
}

//===================================================================
function saveconf() {
  global $err;

  $fdata[]='<?php die(\'Access restricted\');?>';
  $fdata[]='url='.$this->url;
  $fdata[]='uname='.$this->uname;
  $fdata[]='passw='.$this->passw;
  $fdata[]='vapass='.$this->vapass;
  $fdata[]='dbhost='.$this->dbhost;
  $fdata[]='dbase='.$this->dbase;
  $fdata[]='dbuser='.$this->dbuser;
  $fdata[]='dbpass='.$this->dbpass;
  $fdata[]='tzone='.$this->tzone;
  $fdata[]='btime='.$this->btime;
  $fdata[]='dformat='.$this->dformat;
  $fdata[]='tformat='.$this->tformat;
  $fdata[]='dltime='.$this->dltime;
  $fdata[]='lang='.$this->lang;
  $fdata[]='style='.$this->style;
  $fdata[]='services='.$this->services;
  $fdata[]='sreports='.$this->sreports;
  $fdata[]='sgrpgid='.$this->sgrpgid;
  $fdata[]='stint='.$this->stint;
  $fdata[]='semail='.$this->semail;
  $fdata[]='amode='.$this->amode;
  $fdata[]='amimg='.$this->amimg;
  $fdata[]='amcolor='.$this->amcolor;
  $fdata[]='amstat='.$this->amstat;
  save_file($this->rf.'cdata.php',$fdata);
}

//===================================================================
function db_diagnostics(&$test) {
  global $err;

  require './common/config/db_diag.php';
}

//===================================================================
function fs_diagnostics(&$test) {
  global $err;

  require './common/config/fs_diag.php';
}

//===================================================================
function crtables() {
  global $err;
  require './common/config/crtables.php';
}

}

?>
