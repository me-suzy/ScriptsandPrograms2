<?php

  require './data/bases/tzones.php';
  if($page_id==221) $page_id=201;
  $bt=$conf->btime;
  $et=$conf->ctime;
  $pid='';
  $idrefs='';
  $idlangs='';
  $idcouns='';
  $idsts='';
  $idfrm='';
  $idprx='';
  $idprv='';
  $ideng='';
  $idkey='';
  $iddom='';
  $masref=array();
  $maslang=array();
  $mascoun=array();
  $masst=array();
  $masfrm=array();
  $masprx=array();
  $masprv=array();
  $maseng=array();
  $maskey=array();
  $masdom=array();
  $totalrec=0;
  $nrec=0;
  $aref=array();
  $alang=array();
  $acoun=array();
  $ast=array();
  $afrm=array();
  $aprx=array();
  $aprv=array();
  $aeng=array();
  $akey=array();
  $adom=array();
  $timerange='';
  $dc=getdate($conf->dtime-40000);
  $ytime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  if(!strcmp($tint,'today')) $timerange=' AND time>='.$conf->dtime;
  elseif(!strcmp($tint,'yesterday')) $timerange=' AND time>='.$ytime.' AND time<'.$conf->dtime;
  elseif(!strcmp($tint,'week')) $timerange=' AND time>='.$conf->wtime;
  elseif(!strcmp($tint,'lastweek')) $timerange=' AND time>='.$conf->lwtime.' AND time<'.$conf->wtime;
  elseif(!strcmp($tint,'month')) $timerange=' AND time>='.$conf->mtime;
  elseif(!strcmp($tint,'lastmonth')) $timerange=' AND time>='.$conf->lmtime.' AND time<'.$conf->mtime;
  elseif(!strcmp($tint,'totalm')) $timerange=' AND time>='.mktime(0,0,0,1,1,$year,0).' AND time<'.mktime(0,0,0,1,1,$year+1,0);
  elseif(!strcmp($tint,'online')) $timerange=' AND time>'.($year-$conf->tonline).' AND time<='.$year;
  $request='LOCK TABLES aa_raw READ,aa_raw_dom READ, aa_ref_base READ, aa_coun_base READ, aa_lang_base READ, aa_st_base READ, aa_pages READ, aa_groups READ, aa_frm_base READ, aa_prv_base READ, aa_prx_base READ, aa_eng_base READ, aa_key_base READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if($page_id>200) {
      $apages=array();
      $mid=array();
      getpgs($page_id,$mid);
      if($err->flag) {$err->reason('vdb.php|log|\'getpgs\' function has failed');return;}
      reset($mid);
      while($k=key($mid)) {
          if(empty($pid)) $pid=' IN ('.$k;
          else $pid.=','.$k;
          next($mid);
      }
      if(!empty($pid)) {
          $pid.=')';
          $request='SELECT id,name,url FROM aa_pages WHERE id'.$pid;
          $resultg=mysql_query($request,$conf->link);
          if(!$resultg) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($resultg)) {
              if(!isset($apages[$row->id])) {
                  $apages[$row->id]['name']=$row->name;
                  $apages[$row->id]['url']=$row->url;
              }
          }
          mysql_free_result($resultg);
      }
  }
  else {
      $pid='='.$page_id;
  }

  if(!empty($pid)) {
      $fstr='';
      $prvstr='';
      $prxstr='';
      $counstr='';
      $langstr='';
      $osstr='';
      $brstr='';
      $refstr='';
      $frmstr='';
      $engstr='';
      $keystr='';
      $coostr='';
      $jvstr='';
      $jsstr='';
      $resstr='';
      $colstr='';
      $hoststr='';
      $hitsstr='';
      $visstr='';
      $depthstr='';
      $frststr='';
      $lststr='';
      $frsdstr='';
      $lsdstr='';
      $zonestr='';
      if(sizeof($filter)) {
          if(isset($filter['f_host'])) {
              if(!strcasecmp($filter['f_host'],_NONE)) {
                  if($filtercl['f_host']==1) $hoststr=' AND aa_raw.host=0 AND aa_raw.domid=0';
                  elseif($filtercl['f_host']==2) $hoststr=' AND aa_raw.host!=0 AND aa_raw.domid!=0';
              }
              else {
                  $domain='';
                  $ip='';
                  if(preg_match("/\s*([^(\s]+)/",$filter['f_host'],$match)) {
                      if(preg_match("/^\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*$/",$match[1])) $ip=$match[1];
                      else $domain=$match[1];
                  }
                  if(preg_match("/\(\s*([^)\s]+)/",$filter['f_host'],$match)) {
                      if(preg_match("/^\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*$/",$match[1])) $ip=$match[1];
                      else $domain=$match[1];
                  }
                  if(!empty($ip)) {
                      $ip=ip2long($ip);
                      if($filtercl['f_host']==1) $hoststr=' AND aa_raw.host='.$ip;
                      elseif($filtercl['f_host']==2) $hoststr=' AND aa_raw.host!='.$ip;
                  }
                  if(!empty($domain)) {
                    $domid=-1;
                    $request='SELECT domid FROM aa_raw_dom WHERE domain="'.$domain.'"';
                    $result=mysql_query($request,$conf->link);
                    if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                    while($row=mysql_fetch_object($result)) $domid=$row->domid;
                    mysql_free_result($result);
                    if($filtercl['f_host']==1) $hoststr.=' AND aa_raw.domid='.$domid;
                    elseif($filtercl['f_host']==2) $hoststr.=' AND aa_raw.domid!='.$domid;
                  }
              }
          }
          if(isset($filter['f_proxy'])) {
              if(!strcasecmp($filter['f_proxy'],_NONE)) {
                  if($filtercl['f_proxy']==1) $prxstr=' AND aa_raw.prxid=0 AND aa_raw.prxip=0';
                  elseif($filtercl['f_proxy']==2) $prxstr=' AND aa_raw.prxid!=0 AND aa_raw.prxip!=0';
              }
              else {
                  $domain='';
                  $ip='';
                  if(preg_match("/\s*([^(\s]+)/",$filter['f_proxy'],$match)) {
                      if(preg_match("/^\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*$/",$match[1])) $ip=$match[1];
                      else $domain=$match[1];
                  }
                  if(preg_match("/\(\s*([^)\s]+)/",$filter['f_proxy'],$match)) {
                      if(preg_match("/^\s*\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\s*$/",$match[1])) $ip=$match[1];
                      else $domain=$match[1];
                  }
                  if(!empty($ip)) {
                      $ip=ip2long($ip);
                      if($filtercl['f_proxy']==1) $prxstr=' AND aa_raw.prxip='.$ip;
                      elseif($filtercl['f_proxy']==2) $prxstr=' AND aa_raw.prxip!='.$ip;
                  }
                  if(!empty($domain)) {
                    $prxid=-1;
                    $request='SELECT prxid FROM aa_prx_base WHERE name="'.$domain.'"';
                    $result=mysql_query($request,$conf->link);
                    if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                    while($row=mysql_fetch_object($result)) $prxid=$row->prxid;
                    mysql_free_result($result);
                    if($filtercl['f_proxy']==1) $prxstr.=' AND aa_raw.prxid='.$prxid;
                    elseif($filtercl['f_proxy']==2) $prxstr.=' AND aa_raw.prxid!='.$prxid;
                  }
              }
          }
          if(isset($filter['f_provider'])) {
              if(!strcasecmp($filter['f_provider'],_NONE)) {
                  if($filtercl['f_provider']==1) $prvstr=' AND aa_raw.prvid=0';
                  elseif($filtercl['f_provider']==2) $prvstr=' AND aa_raw.prvid!=0';
              }
              else {
                  $tmp=split("\(",$filter['f_provider']);
                  $prvid=-1;
                  $request='SELECT prvid FROM aa_prv_base WHERE name="'.trim($tmp[0]).'"';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  while($row=mysql_fetch_object($result)) $prvid=$row->prvid;
                  mysql_free_result($result);
                  if($filtercl['f_provider']==1) $prvstr=' AND aa_raw.prvid='.$prvid;
                  elseif($filtercl['f_provider']==2) $prvstr=' AND aa_raw.prvid!='.$prvid;
              }
          }
          if(isset($filter['f_country'])) {
              if(!strcasecmp($filter['f_country'],_NONE)) {
                  if($filtercl['f_country']==1) $counstr=' AND aa_raw.counid=0';
                  elseif($filtercl['f_country']==2) $counstr=' AND aa_raw.counid!=0';
              }
              else {
                   $counid=-1;
                   $request='SELECT counid FROM aa_coun_base WHERE lname="'.$filter['f_country'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $counid=$row->counid;
                   mysql_free_result($result);
                   if($filtercl['f_country']==1) $counstr=' AND aa_raw.counid='.$counid;
                   elseif($filtercl['f_country']==2) $counstr=' AND aa_raw.counid!='.$counid;
              }
          }
          if(isset($filter['f_tzone'])) {
              if(!$filter['f_tzone']) {
                  if($filtercl['f_tzone']==1) $zonestr=' AND aa_raw.zoneid=0';
                  elseif($filtercl['f_tzone']==2) $zonestr=' AND aa_raw.zoneid!=0';
              }
              else {
                  if($filtercl['f_tzone']==1) $zonestr=' AND aa_raw.zoneid='.$filter['f_tzone'];
                  elseif($filtercl['f_tzone']==2) $zonestr=' AND aa_raw.zoneid!='.$filter['f_tzone'];
              }
          }
          if(isset($filter['f_lang'])) {
              if(!strcasecmp($filter['f_lang'],_NONE)) {
                  if($filtercl['f_lang']==1) $langstr=' AND aa_raw.langid=0';
                  elseif($filtercl['f_lang']==2) $langstr=' AND aa_raw.langid!=0';
              }
              else {
                   $tmp=split("\(",$filter['f_lang']);
                   $langid=-1;
                   $request='SELECT langid FROM aa_lang_base WHERE lname="'.trim($tmp[0]).'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $langid=$row->langid;
                   mysql_free_result($result);
                   if($filtercl['f_lang']==1) $langstr=' AND aa_raw.langid='.$langid;
                   elseif($filtercl['f_lang']==2) $langstr=' AND aa_raw.langid!='.$langid;
              }
          }
          if(isset($filter['f_os'])) {
              if(!strcasecmp($filter['f_os'],_NONE)) {
                  if($filtercl['f_os']==1) $osstr=' AND aa_raw.osid=0';
                  elseif($filtercl['f_os']==2) $osstr=' AND aa_raw.osid!=0';
              }
              else {
                   $stid=-1;
                   $request='SELECT stid FROM aa_st_base WHERE stid>2000 AND stid<=3000 AND stname="'.$filter['f_os'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $stid=$row->stid;
                   mysql_free_result($result);
                   if($filtercl['f_os']==1) $osstr=' AND aa_raw.osid='.$stid;
                   elseif($filtercl['f_os']==2) $osstr=' AND aa_raw.osid!='.$stid;
              }
          }
          if(isset($filter['f_browser'])) {
              if(!strcasecmp($filter['f_browser'],_NONE)) {
                  if($filtercl['f_browser']==1) $brstr=' AND aa_raw.brid=0';
                  elseif($filtercl['f_browser']==2) $brstr=' AND aa_raw.brid!=0';
              }
              else {
                   $stid=-1;
                   $request='SELECT stid FROM aa_st_base WHERE stid>1000 AND stid<=2000 AND stname="'.$filter['f_browser'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $stid=$row->stid;
                   mysql_free_result($result);
                   if($filtercl['f_browser']==1) $brstr=' AND aa_raw.brid='.$stid;
                   elseif($filtercl['f_browser']==2) $brstr=' AND aa_raw.brid!='.$stid;
              }
          }
          if(isset($filter['f_refpg'])) {
              if(!strcasecmp($filter['f_refpg'],_NONE)) {
                  if($filtercl['f_refpg']==1) $refstr=' AND aa_raw.refid=0';
                  elseif($filtercl['f_refpg']==2) $refstr=' AND aa_raw.refid!=0';
              }
              else {
                   if(!strcmp($filter['f_refpg'],_DIRECT)) $filter['f_refpg']='undefined';
                   else $filter['f_refpg']=substr($filter['f_refpg'],7);
                   $refid=-1;
                   $request='SELECT refid FROM aa_ref_base WHERE url="'.$filter['f_refpg'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $refid=$row->refid;
                   mysql_free_result($result);
                   if($filtercl['f_refpg']==1) $refstr=' AND aa_raw.refid='.$refid;
                   elseif($filtercl['f_refpg']==2) $refstr=' AND aa_raw.refid!='.$refid;
              }
          }
          if(isset($filter['f_frame'])) {
              if(!strcasecmp($filter['f_frame'],_NONE)) {
                  if($filtercl['f_frame']==1) $frmstr=' AND aa_raw.frmid=0';
                  elseif($filtercl['f_frame']==2) $frmstr=' AND aa_raw.frmid!=0';
              }
              else {
                   if(!strcmp($filter['f_frame'],_DIRECT)) $filter['f_frame']='undefined';
                   else $filter['f_frame']=substr($filter['f_frame'],7);
                   $frmid=-1;
                   $request='SELECT frmid FROM aa_frm_base WHERE name="'.$filter['f_frame'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $frmid=$row->frmid;
                   mysql_free_result($result);
                   if($filtercl['f_frame']==1) $frmstr=' AND aa_raw.frmid='.$frmid;
                   elseif($filtercl['f_frame']==2) $frmstr=' AND aa_raw.frmid!='.$frmid;
              }
          }
          if(isset($filter['f_engine'])) {
              if(!strcasecmp($filter['f_engine'],_NONE)) {
                  if($filtercl['f_engine']==1) $engstr=' AND aa_raw.engid=0';
                  elseif($filtercl['f_engine']==2) $engstr=' AND aa_raw.engid!=0';
              }
              else {
                   $engid=-1;
                   $request='SELECT engid FROM aa_eng_base WHERE name="'.$filter['f_engine'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $engid=$row->engid;
                   mysql_free_result($result);
                   if($filtercl['f_engine']==1) $engstr=' AND aa_raw.engid='.$engid;
                   elseif($filtercl['f_engine']==2) $engstr=' AND aa_raw.engid!='.$engid;
              }
          }
          if(isset($filter['f_phrase'])) {
              if(!strcasecmp($filter['f_phrase'],_NONE)) {
                  if($filtercl['f_phrase']==1) $keystr=' AND aa_raw.keyid=0';
                  elseif($filtercl['f_phrase']==2) $keystr=' AND aa_raw.keyid!=0';
              }
              else {
                   $keyid=-1;
                   $request='SELECT keyid FROM aa_key_base WHERE name="'.preg_replace("/\"/",'\\\"',$filter['f_phrase']).'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $keyid=$row->keyid;
                   mysql_free_result($result);
                   if($filtercl['f_phrase']==1) $keystr=' AND aa_raw.keyid='.$keyid;
                   elseif($filtercl['f_phrase']==2) $keystr=' AND aa_raw.keyid!='.$keyid;
              }
          }
          if(isset($filter['f_visitor'])) {
              if($filtercl['f_visitor']==1) $visstr=' AND aa_raw.vid='.$filter['f_visitor'];
              elseif($filtercl['f_visitor']==2) $visstr=' AND aa_raw.vid!='.$filter['f_visitor'];
          }
          if(isset($filter['f_dview'])) {
              if($filtercl['f_dview']==1) $depthstr=' AND aa_raw.depth<'.$filter['f_dview'];
              elseif($filtercl['f_dview']==2) $depthstr=' AND aa_raw.depth='.$filter['f_dview'];
              elseif($filtercl['f_dview']==3) $depthstr=' AND aa_raw.depth>'.$filter['f_dview'];
          }
          if(isset($filter['f_hits'])) {
              if($filtercl['f_hits']==1) $hitsstr=' AND aa_raw.hits<'.$filter['f_hits'];
              elseif($filtercl['f_hits']==2) $hitsstr=' AND aa_raw.hits='.$filter['f_hits'];
              elseif($filtercl['f_hits']==3) $hitsstr=' AND aa_raw.hits>'.$filter['f_hits'];
          }
          if(isset($filter['f_cookie'])) {
              if($filtercl['f_cookie']==1) $coostr=' AND aa_raw.cookieid='.$filter['f_cookie'];
              elseif($filtercl['f_cookie']==2) $coostr=' AND aa_raw.cookieid!='.$filter['f_cookie'];
          }
          if(isset($filter['f_java'])) {
              if($filtercl['f_java']==1) $jvstr=' AND aa_raw.javaid='.$filter['f_java'];
              elseif($filtercl['f_java']==2) $jvstr=' AND aa_raw.javaid!='.$filter['f_java'];
          }
          if(isset($filter['f_jscript'])) {
              if(!strcasecmp($filter['f_jscript'],_NONE)) {
                  if($filtercl['f_jscript']==1) $jsstr=' AND aa_raw.jsid=0';
                  elseif($filtercl['f_jscript']==2) $jsstr=' AND aa_raw.jsid!=0';
              }
              else {
                   $stid=-1;
                   $request='SELECT stid FROM aa_st_base WHERE stid>3000 AND stid<=4000 AND stname="'.$filter['f_jscript'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $stid=$row->stid;
                   mysql_free_result($result);
                   if($filtercl['f_jscript']==1) $jsstr=' AND aa_raw.jsid='.$stid;
                   elseif($filtercl['f_jscript']==2) $jsstr=' AND aa_raw.jsid!='.$stid;
              }
          }
          if(isset($filter['f_screen'])) {
              if(!strcasecmp($filter['f_screen'],_NONE)) {
                  if($filtercl['f_screen']==1) $resstr=' AND aa_raw.resid=0';
                  elseif($filtercl['f_screen']==2) $resstr=' AND aa_raw.resid!=0';
              }
              else {
                   $stid=-1;
                   $request='SELECT stid FROM aa_st_base WHERE stid>4000 AND stid<=5000 AND stname LIKE "______'.$filter['f_screen'].'"';
                   $result=mysql_query($request,$conf->link);
                   if(!$result) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                   while($row=mysql_fetch_object($result)) $stid=$row->stid;
                   mysql_free_result($result);
                   if($filtercl['f_screen']==1) $resstr=' AND aa_raw.resid='.$stid;
                   elseif($filtercl['f_screen']==2) $resstr=' AND aa_raw.resid!='.$stid;
              }
          }
          if(isset($filter['f_cdepth'])) {
              if($filtercl['f_cdepth']==1) $colstr=' AND aa_raw.colid<'.$filter['f_cdepth'];
              elseif($filtercl['f_cdepth']==2) $colstr=' AND aa_raw.colid='.$filter['f_cdepth'];
              elseif($filtercl['f_cdepth']==3) $colstr=' AND aa_raw.colid>'.$filter['f_cdepth'];
          }
      }
      $fstr=$prvstr.$zonestr.$prxstr.$counstr.$langstr.$osstr.$brstr.$refstr.$frmstr.$engstr.$keystr.$coostr.$jvstr.$jsstr.$resstr.$colstr.$hoststr.$hitsstr.$depthstr.$frststr.$lststr.$frsdstr.$lsdstr.$visstr;
      $onnum='';
      if(!strcmp($tint,'online')) {
          $request='SELECT MAX(num) AS num FROM aa_raw WHERE id'.$pid.$timerange.$fstr.' GROUP BY id,vid';
          $resulton=mysql_query($request,$conf->link);
          if(!$resulton) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($resulton)) {
              if(empty($onnum)) $onnum=' AND aa_raw.num IN ('.$row->num;
              else $onnum.=','.$row->num;
          }
          if(!empty($onnum)) $onnum.=')';
          mysql_free_result($resulton);
      }
      if(!strcmp($sort,'f_time')) $request='SELECT * FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY time LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_host')) $request='SELECT * FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY host ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_proxy')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_prx_base ON aa_raw.prxid=aa_prx_base.prxid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_prx_base.name ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_provider')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_prv_base ON aa_raw.prvid=aa_prv_base.prvid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_prv_base.name ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_country')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_coun_base ON aa_raw.counid=aa_coun_base.counid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_coun_base.lname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_tzone')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY zoneid ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_lang')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_lang_base ON aa_raw.langid=aa_lang_base.langid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_lang_base.lname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_os')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_st_base ON aa_raw.osid=aa_st_base.stid WHERE id'.$pid.$timerange.$fstr.$onnum.' AND aa_st_base.stid>2000 AND aa_st_base.stid<=3000 ORDER BY aa_st_base.stname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_browser')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_st_base ON aa_raw.brid=aa_st_base.stid WHERE id'.$pid.$timerange.$fstr.$onnum.' AND aa_st_base.stid>1000 AND aa_st_base.stid<=2000 ORDER BY aa_st_base.stname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_refpg')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_ref_base ON aa_raw.refid=aa_ref_base.refid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_ref_base.url ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_frame')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_frm_base ON aa_raw.frmid=aa_frm_base.frmid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_frm_base.name ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_engine')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_eng_base ON aa_raw.engid=aa_eng_base.engid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_eng_base.name ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_phrase')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_key_base ON aa_raw.keyid=aa_key_base.keyid WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY aa_key_base.name ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_visitor')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY vid ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_first')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY frstime ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_last')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY lstime ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_dview')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY depth ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_hits')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY hits ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_cookie')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY cookieid ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_java')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY javaid ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_jscript')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_st_base ON aa_raw.jsid=aa_st_base.stid WHERE id'.$pid.$timerange.$fstr.$onnum.' AND aa_st_base.stid>3000 AND aa_st_base.stid<=4000 ORDER BY aa_st_base.stname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_screen')) $request='SELECT aa_raw.* FROM aa_raw LEFT OUTER JOIN aa_st_base ON aa_raw.resid=aa_st_base.stid WHERE id'.$pid.$timerange.$fstr.$onnum.' AND aa_st_base.stid>4000 AND aa_st_base.stid<=5000 ORDER BY aa_st_base.stname ASC,time ASC LIMIT '.$begstr.','.$numstr;
      elseif(!strcmp($sort,'f_cdepth')) $request='SELECT aa_raw.* FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum.' ORDER BY colid,time ASC LIMIT '.$begstr.','.$numstr;
      $resultlog=mysql_query($request,$conf->link);
      if(!$resultlog) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $nrec=mysql_num_rows($resultlog);

      $request='SELECT MIN(time),MAX(time),COUNT(*) FROM aa_raw WHERE id'.$pid.$timerange.$fstr.$onnum;
      $resultt=mysql_query($request,$conf->link);
      if(!$resultt) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($resultt)) { $row=mysql_fetch_row($resultt); $bt=$row[0]; $et=$row[1]; $totalrec=$row[2]; }
      mysql_free_result($resultt);
  }

  if(!empty($pid)) {
      while($row=mysql_fetch_object($resultlog)) {
//          if($row->refid&&!isset($masref[$row->refid])) {
//              if(empty($idrefs)) $idrefs='('.$row->refid;
//              else $idrefs.=','.$row->refid;
//              $masref[$row->refid]=1;
//          }
//          if($row->langid&&!isset($maslang[$row->langid])) {
//              if(empty($idlangs)) $idlangs='('.$row->langid;
//              else $idlangs.=','.$row->langid;
//              $maslang[$row->langid]=1;
//          }
//          if(($row->lcounid||$row->counid)&&!isset($mascoun[$row->counid])) {
//              if(empty($idcouns)) $idcouns='('.$row->lcounid.','.$row->counid;
//              else $idcouns.=','.$row->lcounid.','.$row->counid;
//              $mascoun[$row->counid]=1;
//          }
          if(empty($idrefs)) {
              $idrefs='('.$row->refid;
              $idlangs='('.$row->langid;
              $idcouns='('.$row->lcounid.','.$row->counid;
              $idsts='('.$row->brid.','.$row->osid.','.$row->resid.','.$row->jsid;
              $idfrm='('.$row->frmid;
              $idprx='('.$row->prxid;
              $idprv='('.$row->prvid;
              $ideng='('.$row->engid;
              $idkey='('.$row->keyid;
              $iddom='('.$row->domid;
          }
          else {
              $idrefs.=','.$row->refid;
              $idlangs.=','.$row->langid;
              $idcouns.=','.$row->lcounid.','.$row->counid;
              $idsts.=','.$row->brid.','.$row->osid.','.$row->resid.','.$row->jsid;
              $idfrm.=','.$row->frmid;
              $idprx.=','.$row->prxid;
              $idprv.=','.$row->prvid;
              $ideng.=','.$row->engid;
              $idkey.=','.$row->keyid;
              $iddom.=','.$row->domid;
          }
      }
  }
  if(!empty($idrefs)) {
      $request='SELECT * FROM aa_lang_base WHERE langid IN '.$idlangs.')';
      $resultl=mysql_query($request,$conf->link);
      if(!$resultl) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_st_base WHERE stid IN '.$idsts.')';
      $results=mysql_query($request,$conf->link);
      if(!$results) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_ref_base WHERE refid IN '.$idrefs.')';
      $resultr=mysql_query($request,$conf->link);
      if(!$resultr) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_frm_base WHERE frmid IN '.$idfrm.')';
      $resultf=mysql_query($request,$conf->link);
      if(!$resultf) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_prx_base WHERE prxid IN '.$idprx.')';
      $resultpx=mysql_query($request,$conf->link);
      if(!$resultpx) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_prv_base WHERE prvid IN '.$idprv.')';
      $resultpv=mysql_query($request,$conf->link);
      if(!$resultpv) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_eng_base WHERE engid IN '.$ideng.')';
      $resulten=mysql_query($request,$conf->link);
      if(!$resulten) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_raw_dom WHERE domid IN '.$iddom.')';
      $resultdm=mysql_query($request,$conf->link);
      if(!$resultdm) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT * FROM aa_key_base WHERE keyid IN '.$idkey.')';
      $resultk=mysql_query($request,$conf->link);
      if(!$resultk) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($resultl)) {
          if(!isset($alang[$row->langid])) {
              $alang[$row->langid]['sname']=$row->sname;
              if(!strcmp($row->lname,'undefined')) $alang[$row->langid]['lname']=_UNDEFINED;
              else $alang[$row->langid]['lname']=$row->lname;
          }
      }
      mysql_free_result($resultl);
      while($row=mysql_fetch_object($results)) {
          if(!isset($ast[$row->stid])) {
              $ast[$row->stid]['fname']=$row->fname;
              if(!strcmp($row->stname,'undefined')) $ast[$row->stid]['stname']=_UNDEFINED;
              else $ast[$row->stid]['stname']=$row->stname;
          }
      }
      mysql_free_result($results);
      while($row=mysql_fetch_object($resultr)) {
          if(!isset($aref[$row->refid])) {
              if(!strcmp($row->url,'undefined')) $aref[$row->refid]=$row->url;
              else $aref[$row->refid]='http://'.$row->url;
          }
      }
      mysql_free_result($resultr);
      while($row=mysql_fetch_object($resultf)) {
          if(!isset($afrm[$row->frmid])) {
              if(!strcmp($row->name,'undefined')) $afrm[$row->frmid]=$row->name;
              else $afrm[$row->frmid]='http://'.$row->name;
          }
      }
      mysql_free_result($resultf);
      while($row=mysql_fetch_object($resultpv)) {
          if(!isset($aprv[$row->prvid])) {
              if(!strcmp($row->name,'undefined')) { $aprv[$row->prvid]['name']=_UNDEFINED; $aprv[$row->prvid]['coun']=0; }
              else { $aprv[$row->prvid]['name']=$row->name; $aprv[$row->prvid]['coun']=$row->counid; }
          }
          if(empty($idcouns)) $idcouns='('.$row->counid;
          else $idcouns.=','.$row->counid;
      }
      mysql_free_result($resultpv);
      while($row=mysql_fetch_object($resultpx)) {
          if(empty($row->name)) continue;
          if(!isset($aprx[$row->prxid])) {
              if(!strcmp($row->name,'undefined')) $aprx[$row->prxid]=_UNDEFINED;
              else $aprx[$row->prxid]=$row->name;
          }
      }
      mysql_free_result($resultpx);
      while($row=mysql_fetch_object($resulten)) {
          if(!isset($aeng[$row->engid])) {
              if(!strcmp($row->name,'undefined')) $aeng[$row->engid]=_UNDEFINED;
              else $aeng[$row->engid]=$row->name;
          }
      }
      mysql_free_result($resulten);
      while($row=mysql_fetch_object($resultk)) {
          if(!isset($akey[$row->keyid])) {
              if(!strcmp($row->name,'undefined')) $akey[$row->keyid]=_UNDEFINED;
              else $akey[$row->keyid]=$row->name;
          }
      }
      mysql_free_result($resultk);
      while($row=mysql_fetch_object($resultdm)) {
          if(empty($row->domain)) continue;
          if(!isset($adom[$row->domid])) {
              if(!strcmp($row->domain,'undefined')) $adom[$row->domid]=_UNDEFINED;
              else $adom[$row->domid]=$row->domain;
          }
      }
      mysql_free_result($resultdm);
      if(!empty($idcouns)) {
          $request='SELECT * FROM aa_coun_base WHERE counid IN '.$idcouns.')';
          $resultc=mysql_query($request,$conf->link);
          if(!$resultc) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($resultc)) {
              if(!isset($acoun[$row->counid])) {
                  $acoun[$row->counid]['sname']=$row->sname;
                  if(!strcmp($row->lname,'undefined')) $acoun[$row->counid]['lname']=_UNDEFINED;
                  else $acoun[$row->counid]['lname']=$row->lname;
              }
          }
          mysql_free_result($resultc);
      }
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|log|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  require './style/'.$conf->style.'/template/vlog.php';
  $fname=$name;
  if(strlen($fname)>_VS_PGSTITLITEM) $sname=substr($fname,0,_VS_PGSTITLITEM-3).'...';
  else $sname=$fname;
  if($page_id>200) $vars['FPG']=_FORGR." '<b><i>".$name."</i></b>'";
  else $vars['FPG']=_FORPG.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
  if($nrec) $vars['RANGE']=($begstr+1).' - '.($begstr+$nrec).' '._OUTOF.' '.$totalrec;
  else $vars['RANGE']='0 - 0 '._OUTOF.' 0';
  if(!strcmp($tint,'online')) {
      $vars['HEADER']=_ONLINEDET;
      $vars['SHOWING']=_SHOWING.' '.$nrec.' '._VISITOR_S;
      $vars['ONTIME']=$year;
      $vars['REFRESH']=_REFRESH ;
      tparse($top_online,$vars);
  }
  else {
      $vars['HEADER']=_LOG.' / ';
      if(!strcmp($tint,'all')) $vars['THEADER']=_ALLTIME.' ('.date($conf->dmas[$conf->dformat],$bt).' - '.date($conf->dmas[$conf->dformat],$et).')';
      elseif(!strcmp($tint,'total')) $vars['THEADER']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$bt).' - '.date($conf->dmas[$conf->dformat],$et).')';
      elseif(!strcmp($tint,'today')) $vars['THEADER']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->dtime).')';
      elseif(!strcmp($tint,'yesterday')) $vars['THEADER']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$ytime).')';
      elseif(!strcmp($tint,'week')) $vars['THEADER']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->dtime).')';
      elseif(!strcmp($tint,'lastweek')) $vars['THEADER']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-40000).')';
      elseif(!strcmp($tint,'month')) $vars['THEADER']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->dtime).')';
      elseif(!strcmp($tint,'lastmonth')) $vars['THEADER']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-40000).')';
      elseif(!strcmp($tint,'totalm')) $vars['THEADER']=_YEAR.' ('.$year.')';
      $vars['SHOWING']=_SHOWING.' '.$nrec.' '._VISIT_S;
      $vars['REFRESH']=_REFRESH ;
      tparse($top_log,$vars);
  }

  $vars['FILTER']=_ADDVALTOF;
  $input=0;
  $num=$begstr;
  if(!empty($pid)) {
      if($nrec) {
          mysql_data_seek($resultlog,0);
          while($row=mysql_fetch_object($resultlog)) {
              $num++;
              $vars['NUM']=$num;
              $vars['DETAIL']=_SELECT;
              $vars['PAGEN']=_PAGE;
              if($page_id>200) {
                  $fname=$apages[$row->id]['name'];
                  if(strlen($fname)>_VS_LOGPG) $sname=substr($fname,0,_VS_LOGPG-3).'...';
                  else $sname=$fname;
                  $vars['PAGE']=$fname;
                  $vars['PAGESHORT']=$sname;
                  $vars['PREF']=$apages[$row->id]['url'];
              }
              else {
                  $fname=$name;
                  if(strlen($fname)>_VS_LOGPG) $sname=substr($fname,0,_VS_LOGPG-3).'...';
                  else $sname=$fname;
                  $vars['PAGE']=$fname;
                  $vars['PAGESHORT']=$sname;
                  $vars['PREF']=$url;
              }
              $vars['PGID']=$row->id;
              $vars['TIME']=date($conf->dmas[$conf->dformat],$row->time).'  '.date($conf->tmas[$conf->tformat],$row->time);
              if($conf->tzone>0) $vars['TIME'].='  +'.$conf->tzone;
              elseif($conf->tzone<0) $vars['TIME'].=$conf->tzone;
              $vars['TIME'].=' GMT ';
              tparse($prhead,$vars);

              $vars['NAME']=_HOST;
              $vars['VNAME']='f_host';
              $ip=long2ip($row->host);
              if(!$row->host) { $ip1=_NONE; $ip2=''; }
              else { $ip1=long2ip($row->host); $ip2=' ('.$ip.')'; }
              if(!isset($adom[$row->domid])) $vars['VALUE']=$ip1;
              else $vars['VALUE']=$adom[$row->domid].$ip2;
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_VISITORID;
              $vars['VNAME']='f_visitor';
              $vars['VALUE']=$row->vid;
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_PROXY;
              $vars['VNAME']='f_proxy';
              if(!$row->prxip) { $ip1=_NONE; $ip2=''; }
              else { $ip1=long2ip($row->prxip); $ip2=' ('.$ip.')'; }
              if(!isset($aprx[$row->prxid])) $vars['VALUE']=$ip1;
              else $vars['VALUE']=$aprx[$row->prxid].$ip2;
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_FIRSTDATE;
              $vars['VNAME']='f_';
              $vars['VALUE']=date($conf->dmas[$conf->dformat],$row->frstime);
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_PROVIDER;
              $vars['VNAME']='f_provider';
              if(!$row->prvid) $vars['VALUE']=_NONE;
              else {
                  if($aprv[$row->prvid]['coun']!=0) $vars['VALUE']=$aprv[$row->prvid]['name'].' ('.$acoun[$aprv[$row->prvid]['coun']]['lname'].')';
                  else $vars['VALUE']=$alang[$row->prvid]['name'];
              }
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_FIRSTTIME;
              $vars['VNAME']='f_';
              $vars['VALUE']=date($conf->tmas[$conf->tformat],$row->frstime);
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_COUNTRY;
              $vars['VNAME']='f_country';
              $vars['VALUE']=$acoun[$row->counid]['lname'];
              $vars['CAT']='flags';
              $vars['IMG']=$acoun[$row->counid]['sname'];
              if(!strcmp($acoun[$row->counid]['sname'],'unknown')) tparse($pr_l_txt,$vars);
              else tparse($pr_l_img,$vars);
              $vars['NAME']=_LASTDATE;
              $vars['VNAME']='f_';
              $vars['VALUE']=date($conf->dmas[$conf->dformat],$row->lstime);
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_TZONE;
              $vars['VNAME']='f_tzone';
              if(!$row->zoneid) $vars['VALUE']=_NONE;
              else $vars['VALUE']=$tzones[$row->zoneid];
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_LASTTIME;
              $vars['VNAME']='f_';
              $vars['VALUE']=date($conf->tmas[$conf->tformat],$row->lstime);
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_LANGUAGE;
              $vars['VNAME']='f_lang';
              if($row->lcounid!=0) $vars['VALUE']=$alang[$row->langid]['lname'].' ('.$acoun[$row->lcounid]['lname'].')';
              else $vars['VALUE']=$alang[$row->langid]['lname'];
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_DEPTHOFVIEW;
              $vars['VNAME']='f_dview';
              $vars['VALUE']=$row->depth;
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_OS;
              $vars['VNAME']='f_os';
              $vars['VALUE']=$ast[$row->osid]['stname'];
              $vars['CAT']='os';
              $vars['IMG']=$ast[$row->osid]['fname'];
              if(!strcmp($ast[$row->osid]['fname'],'unknown')) tparse($pr_l_txt,$vars);
              else tparse($pr_l_img,$vars);
              $vars['NAME']=_HITS;
              $vars['VNAME']='f_hits';
              $vars['VALUE']=$row->hits;
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_BROWSER;
              $vars['VNAME']='f_browser';
              $vars['VALUE']=$ast[$row->brid]['stname'];
              $vars['CAT']='browsers';
              $vars['IMG']=$ast[$row->brid]['fname'];
              if(!strcmp($ast[$row->osid]['fname'],'unknown')) tparse($pr_l_txt,$vars);
              else tparse($pr_l_img,$vars);
              $vars['NAME']=_COOKIE;
              $vars['VNAME']='f_cookie';
              if($row->cookieid==1) {
                  $vars['VALUE']=_ENABLED;
                  $vars['IMG']='enabled';
                  tparse($pr_r_img,$vars);
              }
              elseif($row->cookieid==2) {
                  $vars['VALUE']=_DISABLED;
                  $vars['IMG']='disabled';
                  tparse($pr_r_img,$vars);
              }
              else {
                  $vars['VALUE']=_UNDEFINED;
                  tparse($pr_r_txt,$vars);
              }
              $vars['NAME']=_REFPG;
              $vars['VNAME']='f_refpg';
              if(!strcmp($aref[$row->refid],'undefined')) {
                  $vars['VALUE']=_DIRECT;
                  tparse($pr_l_txt,$vars);
              }
              else {
                  if(strlen($aref[$row->refid])>_VS_LOGREF) $rshort=substr($aref[$row->refid],0,_VS_LOGREF-3).'...';
                  else $rshort=$aref[$row->refid];
                  $vars['REFERRER']=$aref[$row->refid];
                  $vars['REFSHORT']=$rshort;
                  tparse($pr_l_url,$vars);
              }
              $vars['NAME']=_JAVA;
              $vars['VNAME']='f_java';
              if($row->javaid==1) {
                  $vars['VALUE']=_ENABLED;
                  $vars['IMG']='enabled';
                  tparse($pr_r_img,$vars);
              }
              elseif($row->javaid==2) {
                  $vars['VALUE']=_DISABLED;
                  $vars['IMG']='disabled';
                  tparse($pr_r_img,$vars);
              }
              else {
                  $vars['VALUE']=_UNDEFINED;
                  tparse($pr_r_txt,$vars);
              }
              $vars['NAME']=_FRAMEADDR;
              $vars['VNAME']='f_frame';
              if(!$row->frmid) { $vars['VALUE']=_NONE; tparse($pr_l_txt,$vars); }
              else {
                  if(!strcmp($afrm[$row->frmid],'undefined')) {
                      $vars['VALUE']=_DIRECT;
                      tparse($pr_l_txt,$vars);
                  }
                  else {
                      if(strlen($afrm[$row->frmid])>_VS_LOGREF) $rshort=substr($afrm[$row->frmid],0,_VS_LOGREF-3).'...';
                      else $rshort=$afrm[$row->frmid];
                      $vars['REFERRER']=$afrm[$row->frmid];
                      $vars['REFSHORT']=$rshort;
                      tparse($pr_l_url,$vars);
                  }
              }
              $vars['NAME']=_JAVASCRIPT;
              $vars['VNAME']='f_jscript';
              $vars['VALUE']=$ast[$row->jsid]['stname'];
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_SENGINE;
              $vars['VNAME']='f_engine';
              if(!$row->engid) $vars['VALUE']=_NONE;
              else $vars['VALUE']=$aeng[$row->engid];
              tparse($pr_l_txt,$vars);
              $vars['NAME']=_SRESOLUTION;
              $vars['VNAME']='f_screen';
              $vars['VALUE']=$ast[$row->resid]['stname'];
              if(strcmp($vars['VALUE'],'undefined')) {
                $nm=preg_split("/_/",$vars['VALUE']);
                if(isset($nm[1])) $vars['VALUE']=$nm[1];
              }
              tparse($pr_r_txt,$vars);
              $vars['NAME']=_SPHRASE;
              $vars['VNAME']='f_phrase';
              if(!$row->keyid) $vars['VALUE']=_NONE;
              else $vars['VALUE']=$akey[$row->keyid];
              if(!$row->keyid) $vars['VALUE2']=_NONE;
              else $vars['VALUE2']=urlencode($akey[$row->keyid]);//preg_replace("/\"/",'\\\"',$akey[$row->keyid]);
              tparse($pr_l_txt2,$vars);
              $vars['NAME']=_COLORDEPTH;
              $vars['VNAME']='f_cdepth';
              if($row->colid<1000) $vars['VALUE']=$row->colid;
              else $vars['VALUE']=_UNDEFINED;
              tparse($pr_r_txt,$vars);
              tparse($prfoot,$vars);
              $input=1;
          }
      }
      mysql_free_result($resultlog);
  }
  if(!$input) {
      if(!strcmp($tint,'online')) $vars['TEXT']=_NOVISITORS;
      else $vars['TEXT']=_NORECORDS;
      tparse($empty,$vars);
  }
  if($nrec<$totalrec) {
      $vars['LISTLEN']=$totalrec;
      $vars['LBEG']=_STARTOFLIST;
      $vars['LLSCR']=_PREVPG;
      $vars['LRSCR']=_NEXTPG;
      $vars['LEND']=_ENDOFLIST;
      $vars['LLLSCR']=_10PGSBACK;
      $vars['LRLSCR']=_10PGSFORWARD;
      tparse($delimiter,$vars);
  }
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

?>
