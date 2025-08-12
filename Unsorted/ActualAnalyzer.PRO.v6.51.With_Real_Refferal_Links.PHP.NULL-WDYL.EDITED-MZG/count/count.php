<?php

/*------------------------------------------------------------------------*/
// Product: ActualAnalyzer
// Script: count.php
// Nullified : WTN-WDYL Team `2004
// Copyright: (c) 2002-2004 ActualScripts, Company. All rights reserved.
//
// YOU DON'T NEED TO EDIT ANYTHING IN THIS SCRIPT.
// SEE LICENSE AGREEMENT FOR MORE DETAILS
/*------------------------------------------------------------------------*/

class count {

function count() {
  global $err,$conf,$cdb,$cvis;

  //pages (hit)
  $pref='';        //url of page with analyzer
  $uid=$this->getpage($pref);
  if($err->flag) {$err->reason('count.php|count|can\'t get page');return;}

  //referrer
  $ref='';         //referrer
  $engine='';      //search engine
  $sphrase='';     //search phrase
  $swords = array();    //array of search words
  $this->getref($ref,$engine,$sphrase,$swords);

  //get visitor data
  $newses=true;  //new session flag
  $vid=0;    //unique visitor id
  $vtime=0;  //time of first visit in current session
  $this->ses($vid,$vtime,$ref,$pref,$newses);

  //information about visited groups/page
  $visarr = array();  //visited groups
  $prevsid = array();  //last visited groups+page
  $this->getprev($visarr,$prevsid,$newses);

  //information about current groups/page
  $img=2;  //image by default
  $pagesid=$cdb->getpages($uid,$pref,$img);
  if($err->flag) {$err->reason('count.php|count|can\'t get identifiers of the groups');return;}

  //IP (host)
  $ip='';
  $proxy='';
  $fcount=$this->getips($ip,$proxy);   //return filtering's state
  if($fcount) {
    $cdb->gethosts($ip,$pagesid);
    if($err->flag) {$err->reason('count.php|count|can\'t get hosts');return;}
  }

  //visitor
  $retime=array();   //time of first visit on page/groups
  $this->getvis($pagesid,$retime);
  if($err->flag) {$err->reason('count.php|count|can\'t get visitors');return;}
  if($fcount) {
    $cdb->updatevis($pagesid);
    if($err->flag) {$err->reason('count.php|count|can\'t save visitors');return;}
  }

  //proxy
  if((!empty($proxy))&&($fcount)) {
    $prxdomain=$this->getdomain($proxy);
    $cdb->proxy($pagesid,$proxy,$prxdomain);
    if($err->flag) {$err->reason('count.php|count|can\'t save proxy');return;}
  }

  //update referrer
  if($fcount) {
    $cdb->updateref($ref,$pagesid);
    if($err->flag) {$err->reason('count.php|count|can\'t save referrer');return;}
  }

  //search engines, phrases, keywords
  if((!empty($engine))&&($fcount)) {
    $cdb->search($pagesid,$engine,$sphrase,$swords);
    if($err->flag) {$err->reason('count.php|count|can\'t save referrer');return;}
  }

  //domain
  $domain=$this->getdomain($ip);
  //language
  $lang=$this->getlang();
  //country
  $country=$this->getcountry($domain,$ip,$lang);
  if($fcount) {
    $cdb->updatelc($pagesid,$lang,$country);
    if($err->flag) {$err->reason('count.php|count|can\'t save language and country');return;}
  }

  //provider
  $provider=$this->provider($domain,$ip);
  if((!empty($provider))&&($fcount)) {
    $cdb->providers($pagesid,$provider);
    if($err->flag) {$err->reason('count.php|count|can\'t save provider');return;}
  }

  //browser
  $browser=$this->getbrowser();
  //operating system
  $os=$this->getos();
  //screen resolution
  $screen=$this->getscreen();
  //color depth
  $cd=$this->getcd();
  //java
  $sjava=$this->getsjava();
  //cookie
  $scookie=$this->getscookie();
  //javascript
  $jscript=$this->getjscript();
  if($fcount) {
    $cdb->updatest($pagesid,$cd,$browser,$os,$jscript,$screen,$scookie,$sjava);
    if($err->flag) {$err->reason('count.php|count|can\'t save additional parameters');return;}
  }

  //point, ways, depth and time information
  if($fcount) {
    $this->pgaddinf($visarr,$prevsid,$pagesid,$vtime,$retime,$newses);
    if($err->flag) {$err->reason('count.php|count|can\'t save information about visited groups/page');return;}
  }

  //frames
  $frameurl=$this->frame();
  if((!empty($frameurl))&&($fcount)) {
    $cdb->frames($pagesid,$frameurl);
    if($err->flag) {$err->reason('count.php|count|can\'t save information about frame');return;}
  }

  //time zones
  $tzone=$this->zone();
  if((!empty($tzone))&&($fcount)) {
    $cdb->zones($pagesid,$tzone);
    if($err->flag) {$err->reason('count.php|count|can\'t save information about time zone');return;}
  }

  //raw log
  if($fcount) {
    $cdb->updateraw($vid,$domain);
    if($err->flag) {$err->reason('count.php|count|can\'t save information to raw log');return;}
  }

  //services
  if($conf->services) {
    $sdata=array();
    $cdb->servdata($sdata);
    if(!empty($sdata)) {
      //statistics on e-mail
      if($conf->services & 0x1){
        $this->sonemail($sdata);
        if($err->flag) {$err->reason('count.php|count|can\'t send statistics on e-mail');$err->log_out();$err->flag=false;}
      }
    }
  }

  //output picture with statistics
  if($img>100) {
    $flag=1;
    $d1=0;
    $d2=0;
    $d3=0;
    $color=0;
    $cdb->getstat($flag,$d1,$d2,$d3,$color);
    $cvis->out_digits($img,$flag,$d1,$d2,$d3,$color);
  }
  //output simple picture
  else $cvis->out_pic($img);
}

//get current page
function getpage(&$pref) {
  global $err,$conf,$HTTP_SERVER_VARS,$HTTP_GET_VARS;

  if(isset($GLOBALS['HTTP_REFERER'])) $page=$GLOBALS['HTTP_REFERER'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_REFERER'])) $page=$HTTP_SERVER_VARS['HTTP_REFERER'];
  else {
    if(isset($GLOBALS['anp'])) $page=$GLOBALS['anp'];
    elseif(isset($HTTP_GET_VARS['anp'])) $page=$HTTP_GET_VARS['anp'];
    else $page='';
    if(!strcmp($page,'null')) $page='';
  }
  if(empty($page)) {$err->reason('count.php|getpage|can\'t get URL of the page');return;}

  $page=preg_replace("/[\?|&|#|;].*$/i",'',$page);
  $page=preg_replace("/^(http:\/\/)(www\.)?/i",'',$page);
  $page=preg_replace("/(\/)*$/",'',$page);
  $pref=$page;
  if(preg_match("/^([^\/]+)/i",$page,$matches)) $pdomain=$matches[1];
  else $pdomain='';
  $pdomain=preg_replace("/(:\d+)*$/",'',$pdomain);

  $domain=$conf->url;
  if(preg_match("/^(http:\/\/)([^\/]+)/i",$domain,$matches)) $domain=$matches[2];
  elseif(isset($GLOBALS['SERVER_NAME'])) $domain=$GLOBALS['SERVER_NAME'];
  elseif(isset($HTTP_SERVER_VARS['SERVER_NAME'])) $domain=$HTTP_SERVER_VARS['SERVER_NAME'];
  elseif(isset($GLOBALS['HTTP_HOST'])) $domain=$GLOBALS['HTTP_HOST'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_HOST'])) $domain=$HTTP_SERVER_VARS['HTTP_HOST'];
  else {$err->reason('count.php|getpage|can\'t get current domain name');return;}
  $domain=preg_replace("/^(www\.)/i",'',$domain);
  $domain=preg_replace("/(:\d+)*$/",'',$domain);

  if(strcmp($pdomain,$domain)) {
    if(!file_exists('./data/aliases.php')) {$err->reason('count.php|getpage|unknown external page \''.$page.'\'');return;}
    require './data/aliases.php';

    //check aliases
    $falias=false;
    if(isset($alias)) {
      reset($alias);
      while($e=each($alias)) {
        if(!strcasecmp($pdomain,$e[1])) {
           $falias=true;
           break;
        }
      }
    }
    if(!$falias) {$err->reason('count.php|getpage|external page \''.$page.'\'  not in aliases list');return;}
  }

  if(isset($GLOBALS['anuid'])) $uid=$GLOBALS['anuid'];
  elseif(isset($HTTP_GET_VARS['anuid'])) $uid=$HTTP_GET_VARS['anuid'];
  else $uid=0;

  return $uid;
}

//get current IP
function getips(&$ip,&$proxy) {
  global $err,$conf,$HTTP_SERVER_VARS;

  if(isset($GLOBALS['REMOTE_ADDR'])) $ra=$GLOBALS['REMOTE_ADDR'];
  elseif(isset($HTTP_SERVER_VARS['REMOTE_ADDR'])) $ra=$HTTP_SERVER_VARS['REMOTE_ADDR'];
  else $ra='';

  if(isset($GLOBALS['HTTP_X_FORWARDED_FOR'])) {
    $ip=$GLOBALS['HTTP_X_FORWARDED_FOR'];
    $proxy=$ra;
  }
  elseif(isset($HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'])) {
    $ip=$HTTP_SERVER_VARS['HTTP_X_FORWARDED_FOR'];
    $proxy=$ra;
  }
  if(isset($GLOBALS['HTTP_CLIENT_IP'])) {
    $ip=$GLOBALS['HTTP_CLIENT_IP'];
  }
  elseif(isset($HTTP_SERVER_VARS['HTTP_CLIENT_IP'])) {
    $ip=$HTTP_SERVER_VARS['HTTP_CLIENT_IP'];
  }
  else $ip=$ra;

  if(!empty($ip)) {
    $iparr=preg_split("/,/",$ip);
    $ip=$iparr[0];
    $ip=ip2long($ip);
  }
  else $ip=0;

  if(!empty($proxy)) {
    $proxy=ip2long($proxy);
  }
  else $proxy=0;

  //filtering
  if(file_exists('./data/filters.php')) {
    require './data/filters.php';

    //filtering by IP
    if(isset($ip_filter)) {
      reset($ip_filter);
      while($e=each($ip_filter)) {
        if(strstr($e[1],'-')) {
          $range=preg_split("/-/",$e[1]);
          if(($ip >= ip2long($range[0]))&&($ip <= ip2long($range[1]))) return false;
        }
        elseif($ip == ip2long($e[1])) return false;
      }
    }

    //filtering by Proxy's IP
    if(isset($proxy_filter)) {
      reset($proxy_filter);
      while($e=each($proxy_filter)) {
        if(strstr($e[1],'-')) {
          $range=preg_split("/-/",$e[1]);
          if(($proxy >= ip2long($range[0]))&&($proxy <= ip2long($range[1]))) return false;
        }
        elseif($proxy == ip2long($e[1])) return false;
      }
    }
  }

  return true;
}

//get unique visitors
function getvis(&$pagesid,&$retime) {
  global $err,$conf,$HTTP_COOKIE_VARS;

  if(isset($GLOBALS['ant'])) $cot=$GLOBALS['ant'];
  elseif(isset($HTTP_COOKIE_VARS['ant'])) $cot=$HTTP_COOKIE_VARS['ant'];
  else $cot='';
  $cos=preg_split("/x/",$cot);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $anct[$id]=$cos[$c];
    }
  }

  if(isset($GLOBALS['anm'])) $com=$GLOBALS['anm'];
  elseif(isset($HTTP_COOKIE_VARS['anm'])) $com=$HTTP_COOKIE_VARS['anm'];
  else $com='';
  $cos=preg_split("/x/",$com);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      if($tim>$conf->mtime) $ancm[$id]=$cos[$c];
    }
  }

  if(isset($GLOBALS['anw'])) $cow=$GLOBALS['anw'];
  elseif(isset($HTTP_COOKIE_VARS['anw'])) $cow=$HTTP_COOKIE_VARS['anw'];
  else $cow='';
  $cos=preg_split("/x/",$cow);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      if($tim>$conf->wtime) $ancw[$id]=$cos[$c];
    }
  }

  if(isset($GLOBALS['an1'])) $co1=$GLOBALS['an1'];
  elseif(isset($HTTP_COOKIE_VARS['an1'])) $co1=$HTTP_COOKIE_VARS['an1'];
  else $co1='';
  $cos=preg_split("/x/",$co1);
  $max=sizeof($cos);
  for($c=0;$c<$max;$c++) {
    if(strlen($cos[$c])==10) {
      $id=substr($cos[$c],0,2);
      eval("\$id=0x$id;");
      $tim=substr($cos[$c],2,8);
      eval("\$tim=0x$tim;");
      $inter=$conf->ctime-$tim;
      if($inter<$conf->time1) $anc1[$id]=$cos[$c];
    }
  }

  //current time in HEX
  if($conf->ctime>0x7FFFFFFF) {
    $t1=$conf->ctime/16;
    $t2=$conf->ctime&0xF;
    $ctimestr=sprintf("%07X%01X",$t1,$t2);
  }
  else {
    $ctimestr=sprintf("%08X",$conf->ctime);
  }

  reset($pagesid);
  while ($k=key($pagesid)) {
    if(isset($anct[$k])) {
      $pagesid[$k].='|0';
      if(strlen($anct[$k])==10) {
        $tim=substr($anct[$k],2,8);
        eval("\$tim=0x$tim;");
        $retime[$k]=$tim;
      }
    }
    else {
      $pagesid[$k].='|1';
      $anct[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($ancm[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $ancm[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($ancw[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $ancw[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    if(isset($anc1[$k])) $pagesid[$k].='|0';
    else {
      $pagesid[$k].='|1';
      $anc1[$k]=sprintf("%02X%s",$k,$ctimestr);
    }

    next($pagesid);
  }

  $cot=join('x',$anct);
  $com=join('x',$ancm);
  $cow=join('x',$ancw);
  $co1=join('x',$anc1);

  //clear global cookie
  SetCookie('ant','',0,'/');
  SetCookie('anm','',0,'/');
  SetCookie('anw','',0,'/');
  SetCookie('an1','',0,'/');

  //get path
  $path=$conf->url;
  $path=preg_replace("/^(http:\/\/)([^\/]+)/i",'',$path);
  $path.='aa.php';
  //set cookie
  SetCookie('ant',$cot,$conf->ctime+($conf->time1*3000),$path);
  SetCookie('anm',$com,$conf->nmtime,$path);
  SetCookie('anw',$cow,$conf->nwtime,$path);
  SetCookie('an1',$co1,$conf->ctime+$conf->time1,$path);
}

//get current referrer
function getref(&$ref,&$sengine,&$sphrase,&$swords) {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anr'])) $refer=$GLOBALS['anr'];
  elseif(isset($HTTP_GET_VARS['anr'])) $refer=$HTTP_GET_VARS['anr'];
  else $refer='undefined';
  $refer=preg_replace("/^(http:\/\/)(www\.)?/i",'',$refer);
  $ref=$refer;
  $ref=preg_replace("/\/$/i",'',$ref);
  $ref=trim($ref);
  if(empty($ref)) $ref='undefined';
  //check referrer
  if(!preg_match("/(\.)+/i",$ref))  $ref='undefined';

  //search engine
  $sdomain='';
  if(preg_match("/^([^\/]+)/i",$ref,$matches)) $sdomain=$matches[1];
  if(empty($sdomain)) return;

  require './data/bases/ename.php';
  require './data/bases/engines.php';

  $sident='';
  $max=sizeof($engine);
  for($k=0;$k<$max;$k++) {
    $tarray = preg_split("/\|/",$engine[$k]);
    if(count($tarray)==2) {
      $engident=trim($tarray[0]);
      $tpl=trim($tarray[1]);
      if(preg_match("/$tpl/i",$sdomain)) {
        $sident=$engident;
        break;
      }
    }
  }
  if(empty($sident)) return;
  if(!isset($ename[$sident])) return;
  $sengine=$ename[$sident];

  //search phrase
  require './data/bases/ekeys.php';

  $refer=urldecode($refer);
  $tpl=$ekeys[$sident];
  if(preg_match("/[\/\?|&|#|;]$tpl([^\?|&|#|;]+)/i",$refer,$matches)) {
    $sphrase=$matches[sizeof($matches)-1];
    $sphrase=trim($sphrase);

    //search keywords
    $swords=preg_split("/[\s\+]+/",$sphrase,0,PREG_SPLIT_NO_EMPTY);   //delimiters: space symbols,+
    //delete garbage
    $max=sizeof($swords);
    for($k=0;$k<$max;$k++) {
      $swords[$k]=preg_replace("/^(\W)*/",'',$swords[$k]);
      $swords[$k]=preg_replace("/(\W)*$/",'',$swords[$k]);
      if(!preg_match("/(\w)+/",$swords[$k])) unset($swords[$k]);
    }
    if(sizeof($swords)==1) $sphrase='';
    elseif(sizeof($swords)==0) {
      $sphrase='';
      $swords=array();
    }
  }
}

//get current domain
function getdomain($ip) {
  global $err,$conf;

  $tip=long2ip($ip);
  $host=@gethostbyaddr($tip);
  $host=trim($host);
  $host=preg_replace("/^(www\.)/i",'',$host);
  return $host;
}

//get Language
function getlang() {
  global $err,$conf,$HTTP_SERVER_VARS;

  if(isset($GLOBALS['HTTP_ACCEPT_LANGUAGE'])) $lang=$GLOBALS['HTTP_ACCEPT_LANGUAGE'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'])) $lang=$HTTP_SERVER_VARS['HTTP_ACCEPT_LANGUAGE'];
  else $lang='undefined';
  $langs=preg_split("/,/",$lang);
  $lname=preg_split("/;/",$langs[0]);
  $lang=trim($lname[0]);
  if(empty($lang)) $lang='undefined';
  return $lang;
}

//get Country
function getcountry($host,$ip,$lang) {
  global $err,$conf;

  $tip=long2ip($ip);
  if((strcmp($host,$tip))&&(!empty($host))) {
    //get country through domain
    if(preg_match("/\.([a-zA-Z]+)$/",$host,$matches)) $country=trim($matches[1]);
    else $country='undefined';
    if(empty($country)) $country='undefined';
  }
  else {
    //get country through language setting
    $larr=preg_split("/-/",$lang);
    if(isset($larr[1])) $country=$larr[1];
    elseif(strcmp($larr[0],'undefined')) {

     require './data/bases/lantoc.php';

     $tmp=$larr[0];
     if(isset($lantoc[$tmp])) $country=$lantoc[$tmp];
     else $country='undefined';
    }
  }
  return $country;
}

//get Browser
function getbrowser() {
  global $err,$conf,$HTTP_SERVER_VARS;

  if(isset($GLOBALS['HTTP_USER_AGENT'])) $ua=$GLOBALS['HTTP_USER_AGENT'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) $ua=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
  else $ua='undefined';
  if(empty($ua)) $ua='undefined';

  require './data/bases/browos.php';

  $brow='';
  $max=sizeof($browtpl);
  for($k=0;$k<$max;$k++) {
    $tarray = preg_split("/\|/",$browtpl[$k]);
    if(count($tarray)==2) {
      $osname=trim($tarray[0]);
      $tpl=trim($tarray[1]);
      if(preg_match("/$tpl/i",$ua)) {$brow=$osname;break;}
    }
  }
  if(empty($brow)) $brow='undefined';
  return $brow;
}

//get Operating System
function getos() {
  global $err,$conf,$HTTP_SERVER_VARS;

  if(isset($GLOBALS['HTTP_USER_AGENT'])) $ua=$GLOBALS['HTTP_USER_AGENT'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_USER_AGENT'])) $ua=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
  else $ua='undefined';
  if(empty($ua)) $ua='undefined';

  require './data/bases/browos.php';

  $os='';
  $max=sizeof($ostpl);
  for($k=0;$k<$max;$k++) {
    $tarray = preg_split("/\|/",$ostpl[$k]);
    if(count($tarray)==2) {
      $osname=trim($tarray[0]);
      $tpl=trim($tarray[1]);
      if(preg_match("/$tpl/i",$ua)) {$os=$osname;break;}
    }
  }
  if(empty($os)) $os='undefined';
  return $os;
}

//get Screen Resolution
function getscreen() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anwt'])) $width=$GLOBALS['anwt'];
  elseif(isset($HTTP_GET_VARS['anwt'])) $width=$HTTP_GET_VARS['anwt'];
  else $width='undefined';
  $width=trim($width);
  if(empty($width)) $width='undefined';
  if(!strcmp($width,'null')) $width='undefined';
  if($width<1) $width='undefined';

  if(isset($GLOBALS['anh'])) $height=$GLOBALS['anh'];
  elseif(isset($HTTP_GET_VARS['anh'])) $height=$HTTP_GET_VARS['anh'];
  else $height='undefined';
  $height=trim($height);
  if(empty($height)) $height='undefined';
  if(!strcmp($height,'null')) $height='undefined';
  if($height<1) $height='undefined';

  if((!strcmp($width,'undefined'))||(!strcmp($height,'undefined'))) $screen='undefined';
  else $screen=sprintf("%05d",$width).'_'.$width.'*'.$height;
  return $screen;
}

//get Color Depth
function getcd() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['ancol'])) $cd=$GLOBALS['ancol'];
  elseif(isset($HTTP_GET_VARS['ancol'])) $cd=$HTTP_GET_VARS['ancol'];
  else $cd='undefined';
  $cd=trim($cd);
  if(empty($cd)) $cd='undefined';
  if(!strcmp($cd,'null')) $cd='undefined';
  if($cd<1) $cd='undefined';
  return $cd;
}

//get Java
function getsjava() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anj'])) $sjava=$GLOBALS['anj'];
  elseif(isset($HTTP_GET_VARS['anj'])) $sjava=$HTTP_GET_VARS['anj'];
  else $sjava='undefined';
  if(empty($sjava)) $sjava='undefined';
  if(!strcmp($sjava,'null')) $sjava='undefined';
  return $sjava;
}

//get Cookie
function getscookie() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anc'])) $scookie=$GLOBALS['anc'];
  elseif(isset($HTTP_GET_VARS['anc'])) $scookie=$HTTP_GET_VARS['anc'];
  else $scookie='undefined';
  if(empty($scookie)) $scookie='undefined';
  if(!strcmp($scookie,'null')) $scookie='undefined';
  return $scookie;
}


//get JavaScript
function getjscript() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anjs'])) $jscript=$GLOBALS['anjs'];
  elseif(isset($HTTP_GET_VARS['anjs'])) $jscript=$HTTP_GET_VARS['anjs'];
  else $jscript='undefined';
  if(empty($jscript)) $jscript='undefined';
  if(!strcmp($jscript,'null')) $jscript='undefined';
  return $jscript;
}

//visitor ID
function ses(&$vid,&$vtime,$ref,$pref,&$newses) {
  global $err,$conf,$HTTP_COOKIE_VARS;

  if(isset($GLOBALS['anses'])) $ses=$GLOBALS['anses'];
  elseif(isset($HTTP_COOKIE_VARS['anses'])) $ses=$HTTP_COOKIE_VARS['anses'];
  else $ses='';

  $ref=preg_replace("/(\/)*$/",'',$ref);

  if(!empty($ses)) {
    $sesarr = preg_split("/\|/",$ses);
    if(sizeof($sesarr)==4) {
      $vid=$sesarr[0];
      $vtime=$sesarr[1];
      $prevref=urldecode($sesarr[2]);
      $vref=urldecode($sesarr[3]);
    }
    else {
      $prevref='undefined';
      $vref='undefined';
    }
    //new session
    if(!strcmp($ref,$vref)) $newses=false;
    //reload + multiopen
    elseif(!strcmp($ref,$prevref)) $newses=false;
  }

  if($newses) $ses='';

  if(empty($ses)) {
    //create visitor ID
    $vid=$conf->ctime;
    $vid=substr($vid,2);
    srand((double)microtime() * 1000000);
    $rv=rand(1,42);
    $vid=$rv.$vid;
    //create visitor time
    $vtime=$conf->ctime;
    //create session data
    $prevref=urlencode($ref);
    $vref=urlencode($pref);
    $ses=$vid.'|'.$vtime.'|'.$prevref.'|'.$vref;
    //set session data

    SetCookie('anses',$ses);
  }
  else {
    //create session data
    $prevref=urlencode($ref);
    $vref=urlencode($pref);
    $ses=$vid.'|'.$vtime.'|'.$prevref.'|'.$vref;
    //set session data
    SetCookie('anses',$ses);
  }
}

//get array of visited page/groups
function getprev(&$visarr,&$prevsid,$newses) {
  global $err,$conf,$HTTP_COOKIE_VARS;

  //get visited groups
  if(isset($GLOBALS['anavis'])) $vis=$GLOBALS['anavis'];
  elseif(isset($HTTP_COOKIE_VARS['anavis'])) $vis=$HTTP_COOKIE_VARS['anavis'];
  else $vis='';
  if($newses) $vis='';
  if(!empty($vis)) {
    $varr=preg_split("/x/",$vis);
    $max=sizeof($varr);
    for($c=0;$c<$max;$c++) {
      $pgarr = preg_split("/z/",$varr[$c]);
      if(sizeof($pgarr)==10) {
        $pgid=$pgarr[0];
        $visarr[$pgid]=$varr[$c];
        $visarr[$pgid]=preg_replace("/z/",'|',$visarr[$pgid]);
      }
    }
  }

  //get last visited groups+page
  if(isset($GLOBALS['anprev'])) $prev=$GLOBALS['anprev'];
  elseif(isset($HTTP_COOKIE_VARS['anprev'])) $prev=$HTTP_COOKIE_VARS['anprev'];
  else $prev='';
  if($newses) $prev='';

  if(!empty($prev)) {
    $parr=preg_split("/x/",$prev);
    $max=sizeof($parr);
    for($c=0;$c<$max;$c++) {
      $pgarr = preg_split("/z/",$parr[$c]);
      if(sizeof($pgarr)==7) {
        $pgid=$pgarr[0];
        $prevsid[$pgid]=$parr[$c];
        $prevsid[$pgid]=preg_replace("/z/",'|',$prevsid[$pgid]);
      }
    }
  }
}

//additional information (points, ways, times, depthes)
function pgaddinf(&$visarr,&$prevsid,&$pagesid,$vtime,&$retime,$newses) {
  global $err,$conf,$cdb,$HTTP_COOKIE_VARS;

  //get dynamic information about session
  if(isset($GLOBALS['ancurr'])) $curr=$GLOBALS['ancurr'];
  elseif(isset($HTTP_COOKIE_VARS['ancurr'])) $curr=$HTTP_COOKIE_VARS['ancurr'];
  else $curr='';
  if($newses) $curr='';

  $tim=0;
  $num=0;
  $flags='';
  $carr=preg_split("/x/",$curr);
  if(sizeof($carr)==3) {
    $tim=$carr[0];
    $num=$carr[1];
    $flags=$carr[2];
  }

  //get current page
  $pageid=0;
  ksort($pagesid);
  reset($pagesid);
  while($k=key($pagesid)) {
    if($k<201) {
      $pageid=$k;
      break;
    }
    next($pagesid);
  }
  if($pageid==0) {$err->reason('count.php|pgaddinf|can\'t get id of current page');return;}

  //check visited state of current page ($vflags=1 - visited)
  $vflags=0;
  if(empty($flags)) $flags=str_repeat('0',50);
  $flarr=preg_split("//",$flags,0,PREG_SPLIT_NO_EMPTY);
  $fpos=(int)(($pageid-1)/4);
  $bitpos=(int)(($pageid-1)%4);
  $bitpos++;
  $mask=1;
  for($i=1;$i<$bitpos;$i++) $mask*=2;
  if(isset($flarr[$fpos])) {
    if($flarr[$fpos]&$mask) $vflags=1;
    else {
      $flarr[$fpos]=$flarr[$fpos]|$mask;
      $flags=join('',$flarr);
    }
  }

  if(empty($prevsid)) {
    //the first visit on site
    $cdb->points(1,$tim,$prevsid,$pagesid);
    if($err->flag) {$err->reason('count.php|pgaddinf|can\'t update information about first visit');return;}
  }
  else {
    if($num==1) {
      //secondary visit
      $cdb->points(2,$tim,$prevsid,$pagesid);
      if($err->flag) {$err->reason('count.php|pgaddinf|can\'t update information about secondary visit');return;}
    }
    elseif($num>1) {
      //next visits
      $cdb->points(3,$tim,$prevsid,$pagesid);
      if($err->flag) {$err->reason('count.php|pgaddinf|can\'t update information about next visits');return;}
    }
    else {$err->reason('count.php|pgaddinf|incorrect number of visited pages');return;}

    //information about ways
    $cdb->vector($prevsid,$pagesid);
    if($err->flag) {$err->reason('count.php|pgaddinf|can\'t update information about ways');return;}
  }

  //depth and time of the view for groups/page
  $cdb->depthtime($visarr,$prevsid,$pagesid,$retime,$num,$tim,$vflags);
  if($err->flag) {$err->reason('count.php|pgaddinf|can\'t update information about depth and time of the view');return;}

  //set cookie with dynamic information about session
  $num++;
  $curr=$conf->ctime.'x'.$num.'x'.$flags;
  SetCookie('ancurr',$curr);

  //set cookie with information about last visited groups+page
  $prevs=join('x',$pagesid);
  $prevs=preg_replace("/\|/",'z',$prevs);
  SetCookie('anprev',$prevs);

  //set cookie with information about visited groups
  $viss=join('x',$visarr);
  $viss=preg_replace("/\|/",'z',$viss);
  SetCookie('anavis',$viss);
}

//get URL of frame if exist
function frame() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anf'])) $flag=$GLOBALS['anf'];
  elseif(isset($HTTP_GET_VARS['anf'])) $flag=$HTTP_GET_VARS['anf'];
  else $flag=2;
  if($flag==2) return '';

  if(isset($GLOBALS['anfr'])) $fram=$GLOBALS['anfr'];
  elseif(isset($HTTP_GET_VARS['anfr'])) $fram=$HTTP_GET_VARS['anfr'];
  else $fram='undefined';

  $fram=preg_replace("/^(http:\/\/)(www\.)?/i",'',$fram);
  $fram=preg_replace("/[\?|&|#|;].*$/i",'',$fram);
  if(empty($fram)) $fram='undefined';
  //java script does not work properly
  if(!strcmp($fram,'null')) $fram='undefined';

  return $fram;
}

//get time zone
function zone() {
  global $err,$conf,$HTTP_GET_VARS;

  if(isset($GLOBALS['anlt'])) $tloc=$GLOBALS['anlt'];
  elseif(isset($HTTP_GET_VARS['anlt'])) $tloc=$HTTP_GET_VARS['anlt'];
  else return '';
  $tloc=trim($tloc);
  if(!strcmp($tloc,'null')) return '';
  if($tloc=='-1') return '';

  $tloc=($tloc/60)*(-1);
  $tloc=round($tloc);

  //incorrect time zones
  if($tloc<-12 || $tloc>13) return '';

  return $tloc+13;
}

//get provider
function provider($host,$ip) {
  global $err,$conf;

  $prov='';
  $tip=long2ip($ip);
  if((strcmp($host,$tip))&&(!empty($host))) {
    $tarr = preg_split("/\./",$host,0,PREG_SPLIT_NO_EMPTY);
    if(sizeof($tarr)>2) {
      $prov = $tarr[sizeof($tarr)-2];

      require'./data/bases/exthost.php';

      $max=sizeof($exthost);
      for($k=0;$k<$max;$k++) {
        if(!strcmp($exthost[$k],$prov)) {
          $prov=$tarr[sizeof($tarr)-3];
          break;
        }
      }

      if(!strcmp($tarr[sizeof($tarr)-2],'co')) {
        if((!strcmp($tarr[sizeof($tarr)-1],'jp'))||(!strcmp($tarr[sizeof($tarr)-1],'uk'))) {
          $prov=$tarr[sizeof($tarr)-3];
        }
      }
    }
    elseif(sizeof($tarr)>1) $prov = $tarr[sizeof($tarr)-2];
  }

  return $prov;
}

//statistics on e-mail service
function sonemail($sdata) {
  global $err,$conf;

  $message=$this->report($sdata);

  $to=$conf->semail;
  $domain=$conf->url;
  if(preg_match("/^(http:\/\/)([^\/]+)/i",$domain,$matches)) $domain=$matches[2];
  else $domain='unknown';
  $domain=preg_replace("/(:\d+)*$/",'',$domain);
  $from='"'.$domain.'" <analyzer@'.$domain.'>';

  if(!strcmp($conf->stint,'yesterday')) $subject=_DAILY.' '._REPORT;
  elseif(!strcmp($conf->stint,'lastweek')) $subject=_WEEKLY.' '._REPORT;
  elseif(!strcmp($conf->stint,'lastmonth')) $subject=_MONTHLY.' '._REPORT;
  else $subject=_REPORT;

  $rez=@mail($to, $subject, $message, "From: $from");
  if(!$rez) {$err->reason('count.php|sonemail|can\'t send report to e-mail '.$conf->semail);return;}
}

//statistics for services
function report(&$sdata) {
  global $err,$conf;

  $report='';
  require'./data/reports/top.php';

  if(!strcmp($conf->stint,'yesterday')) {
    $vars[1]=_DAILY;
    $dy=getdate($conf->dtime-40000);
    $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
    $vars[3]=date($conf->dmas[$conf->dformat],$ydtime);
  }
  elseif(!strcmp($conf->stint,'lastweek')) {
    $vars[1]=_WEEKLY;
    if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars[3]=date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200);
    else $vars[3]=date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200);
  }
  elseif(!strcmp($conf->stint,'lastmonth')) {
    $vars[1]=_MONTHLY;
    if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars[3]=date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200);
    else $vars[3]=date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200);
  }
  $vars[2]=_REPORT;
  $vars[4]=_STATISTICS;
  $grpgdata=preg_split("/\|/",$sdata[0]);
  $vars[6]=$grpgdata[0];
  if($conf->sgrpgid>200) $vars[5]=_FORGR;
  else {
    $vars[5]=_FORPG;
    $vars[7]=$grpgdata[1];
  }

  if($conf->sgrpgid>200) $this->rparse($report,$vars,$topgr);
  else $this->rparse($report,$vars,$toppg);

  //summary report
  if($conf->sreports & 0x1) {
    require'./data/reports/summary.php';

    if(isset($sdata['1_1'])) {

      $data=preg_split("/\|/",$sdata['1_1']);
      $vars[1]=_SUMMARY;
      $vars[2]=_VISITORS;
      $vars[3]=$data[0];
      $vars[4]=_HOSTS;
      $vars[5]=$data[1];
      $vars[6]=_RELOADS;
      $vars[7]=$data[2];
      $vars[8]=_HITS;
      $vars[9]=$data[3];

      $this->rparse($report,$vars,$center);
    }
  }

  //reffering servers report
  if($conf->sreports & 0x2) {
    require'./data/reports/refservs.php';

    if(isset($sdata['2_1'])) {

      $vars[10]=_REFSERVS;
      $this->rparse($report,$vars,$top);

      for($i=1;$i<10;$i++) {
        if(!isset($sdata['2_'.$i])) break;
        $data=preg_split("/\|/",$sdata['2_'.$i]);
        $vars[1]=$data[0];
        $vars[2]=_VISITORS;
        $vars[3]=$data[1];
        $vars[4]=_HOSTS;
        $vars[5]=$data[2];
        $vars[6]=_RELOADS;
        $vars[7]=$data[3];
        $vars[8]=_HITS;
        $vars[9]=$data[4];

        $this->rparse($report,$vars,$center);
      }
    }
  }

  return $report;
}

//parse report template
function rparse(&$report,&$vars,&$templ) {
  $report.= preg_replace("/%%([A-Z0-9]+)%%/e","\$vars['\\1']",$templ);
}

}

?>
