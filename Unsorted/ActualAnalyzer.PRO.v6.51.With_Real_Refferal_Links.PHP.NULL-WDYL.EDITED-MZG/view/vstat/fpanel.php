<?php

  require './style/'.$conf->style.'/template/vfilter.php';

  $this->vars['HEADER']=_FILTERPAN.' / ';
  if(!strcmp($this->act,'onlinedet')) $this->vars['RHEADER']=_ONLINEDET;
  elseif(!strcmp($this->act,'log')) $this->vars['RHEADER']=_LOG;
  else $this->vars['RHEADER']='';
  $this->vars['DESC']=_SORTBY;
  $this->vars['CLEAR']=_CLEARFILTER;
  $this->vars['CHEADER']=_FILTERING.' ';
  if($this->id==221) $this->vars['CHEADER'].=_FORALLGRS;
  elseif($this->id>200) $this->vars['CHEADER'].=_FORGR." '<b><i>".$this->name."</i></b>'";
  else {
    $fname=$this->name;
    if(strlen($fname)>_VS_PGTITLFILTR) $sname=substr($fname,0,_VS_PGTITLFILTR-3).'...';
    else $sname=$fname;
    $this->vars['CHEADER'].=_FORPG.' \'<b><i><a href="'.$this->url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
  }
  tparse($topstart,$this->vars);

  //list of sorted parameters
  $this->vars['VALUE']='f_time';
  $this->vars['ITEM']=_TIMEOFVIS;
  if(!strcmp($sort,'f_time')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_host';
  $this->vars['ITEM']=_HOST;
  if(!strcmp($sort,'f_host')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_proxy';
  $this->vars['ITEM']=_PROXY;
  if(!strcmp($sort,'f_proxy')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_provider';
  $this->vars['ITEM']=_PROVIDER;
  if(!strcmp($sort,'f_provider')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_country';
  $this->vars['ITEM']=_COUNTRY;
  if(!strcmp($sort,'f_country')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_tzone';
  $this->vars['ITEM']=_TZONE;
  if(!strcmp($sort,'f_tzone')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_lang';
  $this->vars['ITEM']=_LANGUAGE;
  if(!strcmp($sort,'f_lang')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_os';
  $this->vars['ITEM']=_OS;
  if(!strcmp($sort,'f_os')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_browser';
  $this->vars['ITEM']=_BROWSER;
  if(!strcmp($sort,'f_browser')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_refpg';
  $this->vars['ITEM']=_REFPG;
  if(!strcmp($sort,'f_refpg')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_frame';
  $this->vars['ITEM']=_FRAMEADDR;
  if(!strcmp($sort,'f_frame')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_engine';
  $this->vars['ITEM']=_SENGINE;
  if(!strcmp($sort,'f_engine')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_phrase';
  $this->vars['ITEM']=_SPHRASE;
  if(!strcmp($sort,'f_phrase')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_visitor';
  $this->vars['ITEM']=_VISITORID;
  if(!strcmp($sort,'f_visitor')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_dview';
  $this->vars['ITEM']=_DEPTHOFVIEW;
  if(!strcmp($sort,'f_dview')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_hits';
  $this->vars['ITEM']=_HITS;
  if(!strcmp($sort,'f_hits')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_cookie';
  $this->vars['ITEM']=_COOKIE;
  if(!strcmp($sort,'f_cookie')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_java';
  $this->vars['ITEM']=_JAVA;
  if(!strcmp($sort,'f_java')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_jscript';
  $this->vars['ITEM']=_JAVASCRIPT;
  if(!strcmp($sort,'f_jscript')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_screen';
  $this->vars['ITEM']=_SRESOLUTION;
  if(!strcmp($sort,'f_screen')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']='f_cdepth';
  $this->vars['ITEM']=_COLORDEPTH;
  if(!strcmp($sort,'f_cdepth')) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);

  $this->vars['PARAM']=_PARAM;
  $this->vars['VALUE']=_VALUE;
  $this->vars['CONDIT']=_CONDIT;
  tparse($topend,$this->vars);

  //host
  $this->vars['DESC']=_HOST;
  $this->vars['NAME']='f_host';
  if(isset($filter['f_host'])) {
    $this->vars['VALUE']=$filter['f_host'];
    if($filter_cl['f_host']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_host']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //proxy
  $this->vars['DESC']=_PROXY;
  $this->vars['NAME']='f_proxy';
  if(isset($filter['f_proxy'])) {
    $this->vars['VALUE']=$filter['f_proxy'];
    if($filter_cl['f_proxy']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_proxy']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //provider
  $this->vars['DESC']=_PROVIDER;
  $this->vars['NAME']='f_provider';
  if(isset($filter['f_provider'])) {
    $this->vars['VALUE']=$filter['f_provider'];
    if($filter_cl['f_provider']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_provider']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //country
  $this->vars['DESC']=_COUNTRY;
  $this->vars['NAME']='f_country';
  if(isset($filter['f_country'])) {
    $this->vars['VALUE']=$filter['f_country'];
    if($filter_cl['f_country']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_country']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //time zone
  $this->vars['DESC']=_TZONE;
  $this->vars['NAME']='f_tzone';
  tparse($cliststart,$this->vars);

  $ftzone='';
  if(isset($filter['f_tzone'])) $ftzone=$filter['f_tzone'];

  $this->vars['VALUE']='';
  $this->vars['ITEM']='';
  if(empty($ftzone)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);

  //list of time zones
  require './data/bases/tzones.php';

  reset($tzones);
  while ($k=key($tzones)) {
    $this->vars['VALUE']=$k;
    $this->vars['ITEM']=$tzones[$k];
    if(!strcmp($k,$ftzone)) $this->vars['SELECTED']=' selected';
    else $this->vars['SELECTED']='';
    tparse($listc,$this->vars);
    next($tzones);
  }

  if(isset($filter['f_tzone'])) {
    if($filter_cl['f_tzone']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_tzone']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($clistend,$this->vars);
  //language
  $this->vars['DESC']=_LANGUAGE;
  $this->vars['NAME']='f_lang';
  if(isset($filter['f_lang'])) {
    $this->vars['VALUE']=$filter['f_lang'];
    if($filter_cl['f_lang']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_lang']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //operating system
  $this->vars['DESC']=_OS;
  $this->vars['NAME']='f_os';
  if(isset($filter['f_os'])) {
    $this->vars['VALUE']=$filter['f_os'];
    if($filter_cl['f_os']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_os']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //browser
  $this->vars['DESC']=_BROWSER;
  $this->vars['NAME']='f_browser';
  if(isset($filter['f_browser'])) {
    $this->vars['VALUE']=$filter['f_browser'];
    if($filter_cl['f_browser']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_browser']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //referring page
  $this->vars['DESC']=_REFPG;
  $this->vars['NAME']='f_refpg';
  if(isset($filter['f_refpg'])) {
    $this->vars['VALUE']=$filter['f_refpg'];
    if($filter_cl['f_refpg']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_refpg']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //frame address
  $this->vars['DESC']=_FRAMEADDR;
  $this->vars['NAME']='f_frame';
  if(isset($filter['f_frame'])) {
    $this->vars['VALUE']=$filter['f_frame'];
    if($filter_cl['f_frame']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_frame']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //search engine
  $this->vars['DESC']=_SENGINE;
  $this->vars['NAME']='f_engine';
  if(isset($filter['f_engine'])) {
    $this->vars['VALUE']=$filter['f_engine'];
    if($filter_cl['f_engine']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_engine']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //search phrase
  $this->vars['DESC']=_SPHRASE;
  $this->vars['NAME']='f_phrase';
  if(isset($filter['f_phrase'])) {
    $this->vars['VALUE']=$filter['f_phrase'];
    if($filter_cl['f_phrase']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_phrase']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //visitor id
  $this->vars['DESC']=_VISITORID;
  $this->vars['NAME']='f_visitor';
  if(isset($filter['f_visitor'])) {
    $this->vars['VALUE']=$filter['f_visitor'];
    if($filter_cl['f_visitor']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_visitor']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //depth of viewing
  $this->vars['DESC']=_DEPTHOFVIEW;
  $this->vars['NAME']='f_dview';
  if(isset($filter['f_dview'])) {
    $this->vars['VALUE']=$filter['f_dview'];
    if($filter_cl['f_dview']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_dview']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
    if($filter_cl['f_dview']==3) $this->vars['STATE3']='checked';
    else $this->vars['STATE3']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
    $this->vars['STATE3']='';
  }
  $this->vars['DESC1']=_LESS;
  $this->vars['DESC2']=_EQUAL;
  $this->vars['DESC3']=_MORE;
  tparse($cdigit,$this->vars);
  //hits
  $this->vars['DESC']=_HITS;
  $this->vars['NAME']='f_hits';
  if(isset($filter['f_hits'])) {
    $this->vars['VALUE']=$filter['f_hits'];
    if($filter_cl['f_hits']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_hits']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
    if($filter_cl['f_hits']==3) $this->vars['STATE3']='checked';
    else $this->vars['STATE3']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
    $this->vars['STATE3']='';
  }
  $this->vars['DESC1']=_LESS;
  $this->vars['DESC2']=_EQUAL;
  $this->vars['DESC3']=_MORE;
  tparse($cdigit,$this->vars);
  //cookie
  $this->vars['DESC']=_COOKIE;
  $this->vars['NAME']='f_cookie';
  tparse($cliststart,$this->vars);

  $fcookie='';
  if(isset($filter['f_cookie'])) $fcookie=$filter['f_cookie'];

  $this->vars['VALUE']='';
  $this->vars['ITEM']='';
  if(empty($fcookie)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=1;
  $this->vars['ITEM']=_ENABLED;
  if(!strcmp($fcookie,1)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=2;
  $this->vars['ITEM']=_DISABLED;
  if(!strcmp($fcookie,2)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=3;
  $this->vars['ITEM']=_UNDEFINED;
  if(!strcmp($fcookie,3)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);

  if(isset($filter['f_cookie'])) {
    if($filter_cl['f_cookie']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_cookie']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($clistend,$this->vars);

  //java
  $this->vars['DESC']=_JAVA;
  $this->vars['NAME']='f_java';
  tparse($cliststart,$this->vars);

  $fjava='';
  if(isset($filter['f_java'])) $fjava=$filter['f_java'];

  $this->vars['VALUE']='';
  $this->vars['ITEM']='';
  if(empty($fjava)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=1;
  $this->vars['ITEM']=_ENABLED;
  if(!strcmp($fjava,1)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=2;
  $this->vars['ITEM']=_DISABLED;
  if(!strcmp($fjava,2)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);
  $this->vars['VALUE']=3;
  $this->vars['ITEM']=_UNDEFINED;
  if(!strcmp($fjava,3)) $this->vars['SELECTED']=' selected';
  else $this->vars['SELECTED']='';
  tparse($listc,$this->vars);

  if(isset($filter['f_java'])) {
    if($filter_cl['f_java']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_java']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($clistend,$this->vars);
  //javascript
  $this->vars['DESC']=_JAVASCRIPT;
  $this->vars['NAME']='f_jscript';
  if(isset($filter['f_jscript'])) {
    $this->vars['VALUE']=$filter['f_jscript'];
    if($filter_cl['f_jscript']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_jscript']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //screen resolution
  $this->vars['DESC']=_SRESOLUTION;
  $this->vars['NAME']='f_screen';
  if(isset($filter['f_screen'])) {
    $this->vars['VALUE']=$filter['f_screen'];
    if($filter_cl['f_screen']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_screen']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
  }
  $this->vars['DESC1']=_EQUAL;
  $this->vars['DESC2']=_NOTEQUAL;
  tparse($ctext,$this->vars);
  //color depth
  $this->vars['DESC']=_COLORDEPTH;
  $this->vars['NAME']='f_cdepth';
  if(isset($filter['f_cdepth'])) {
    if(!strcmp($filter['f_cdepth'],'1000')) $this->vars['VALUE']=_UNDEFINED;
    else $this->vars['VALUE']=$filter['f_cdepth'];
    if($filter_cl['f_cdepth']==1) $this->vars['STATE1']='checked';
    else $this->vars['STATE1']='';
    if($filter_cl['f_cdepth']==2) $this->vars['STATE2']='checked';
    else $this->vars['STATE2']='';
    if($filter_cl['f_cdepth']==3) $this->vars['STATE3']='checked';
    else $this->vars['STATE3']='';
  }
  else {
    $this->vars['VALUE']='';
    $this->vars['STATE1']='checked';
    $this->vars['STATE2']='';
    $this->vars['STATE3']='';
  }
  $this->vars['DESC1']=_LESS;
  $this->vars['DESC2']=_EQUAL;
  $this->vars['DESC3']=_MORE;
  tparse($cdigit,$this->vars);

  $this->vars['SUBMIT']=_SUBMIT;
  $this->vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$this->vars);

?>
