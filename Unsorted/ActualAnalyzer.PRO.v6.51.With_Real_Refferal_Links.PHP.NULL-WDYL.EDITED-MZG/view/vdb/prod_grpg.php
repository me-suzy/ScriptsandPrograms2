<?php

  $dy=getdate($conf->dtime-40000);
  $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $dyear=$year;
  $year=$year-(int)(date('Y',$conf->btime))+1;
  if(!strcmp($tint,'today')) {
      $values='vt AS v,hst AS hs,(htt-vt) AS r,htt AS ht';
      $minvalues='MIN(vt) AS minv,MIN(hst) AS minhs,MIN(htt-vt) AS minr,MIN(htt) AS minht';
      $maxvalues='MAX(vt) AS maxv,MAX(hst) AS maxhs,MAX(htt-vt) AS maxr,MAX(htt) AS maxht';
      $sumvalues='SUM(vt) AS sumv,SUM(hst) AS sumhs,SUM(htt-vt) AS sumr,SUM(htt) AS sumht';
      $where=' AND (modify>='.$conf->dtime.' AND (vt!=0 OR hst!=0 OR htt!=0))';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime).') ';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'yesterday')) {
      $values='IF(modify>='.$conf->dtime.',vy,vt) AS v,IF(modify>='.$conf->dtime.',hsy,hst) AS hs,IF(modify>='.$conf->dtime.',hty-vy,htt-vt) AS r,IF(modify>='.$conf->dtime.',hty,htt) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->dtime.',vy,vt)) AS minv,MIN(IF(modify>='.$conf->dtime.',hsy,hst)) AS minhs,MIN(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS minr,MIN(IF(modify>='.$conf->dtime.',hty,htt)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->dtime.',vy,vt)) AS maxv,MAX(IF(modify>='.$conf->dtime.',hsy,hst)) AS maxhs,MAX(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS maxr,MAX(IF(modify>='.$conf->dtime.',hty,htt)) AS maxht';
      $sumvalues='SUM(IF(modify>='.$conf->dtime.',vy,vt)) AS sumv,SUM(IF(modify>='.$conf->dtime.',hsy,hst)) AS sumhs,SUM(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS sumr,SUM(IF(modify>='.$conf->dtime.',hty,htt)) AS sumht';
      $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0)))';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$ydtime).') ';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'week')) {
      $values='vw AS v,hsw AS hs,htw-vw AS r,htw AS ht';
      $minvalues='MIN(vw) AS minv,MIN(hsw) AS minhs,MIN(htw-vw) AS minr,MIN(htw) AS minht';
      $maxvalues='MAX(vw) AS maxv,MAX(hsw) AS maxhs,MAX(htw-vw) AS maxr,MAX(htw) AS maxht';
      $sumvalues='SUM(vw) AS sumv,SUM(hsw) AS sumhs,SUM(htw-vw) AS sumr,SUM(htw) AS sumht';
      $where=' AND (modify>='.$conf->wtime.' AND (vw!=0 OR hsw!=0 OR htw!=0))';
      if($conf->btime>$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'lastweek')) {
      $values='IF(modify>='.$conf->wtime.',vlw,vw) AS v,IF(modify>='.$conf->wtime.',hslw,hsw) AS hs,IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw) AS r,IF(modify>='.$conf->wtime.',htlw,htw) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->wtime.',vlw,vw)) AS minv,MIN(IF(modify>='.$conf->wtime.',hslw,hsw)) AS minhs,MIN(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS minr,MIN(IF(modify>='.$conf->wtime.',htlw,htw)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->wtime.',vlw,vw)) AS maxv,MAX(IF(modify>='.$conf->wtime.',hslw,hsw)) AS maxhs,MAX(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS maxr,MAX(IF(modify>='.$conf->wtime.',htlw,htw)) AS maxht';
      $sumvalues='SUM(IF(modify>='.$conf->wtime.',vlw,vw)) AS sumv,SUM(IF(modify>='.$conf->wtime.',hslw,hsw)) AS sumhs,SUM(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS sumr,SUM(IF(modify>='.$conf->wtime.',htlw,htw)) AS sumht';
      $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0)))';
      if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'month')) {
      $values='vm AS v,hsm AS hs,htm-vm AS r,htm AS ht';
      $minvalues='MIN(vm) AS minv,MIN(hsm) AS minhs,MIN(htm-vm) AS minr,MIN(htm) AS minht';
      $maxvalues='MAX(vm) AS maxv,MAX(hsm) AS maxhs,MAX(htm-vm) AS maxr,MAX(htm) AS maxht';
      $sumvalues='SUM(vm) AS sumv,SUM(hsm) AS sumhs,SUM(htm-vm) AS sumr,SUM(htm) AS sumht';
      $where=' AND (modify>='.$conf->mtime.' AND (vm!=0 OR hsm!=0 OR htm!=0))';
      if($conf->btime>$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $values='IF(modify>='.$conf->mtime.',vlm,vm) AS v,IF(modify>='.$conf->mtime.',hslm,hsm) AS hs,IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm) AS r,IF(modify>='.$conf->mtime.',htlm,htm) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->mtime.',vlm,vm)) AS minv,MIN(IF(modify>='.$conf->mtime.',hslm,hsm)) AS minhs,MIN(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS minr,MIN(IF(modify>='.$conf->mtime.',htlm,htm)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->mtime.',vlm,vm)) AS maxv,MAX(IF(modify>='.$conf->mtime.',hslm,hsm)) AS maxhs,MAX(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS maxr,MAX(IF(modify>='.$conf->mtime.',htlm,htm)) AS maxht';
      $sumvalues='SUM(IF(modify>='.$conf->mtime.',vlm,vm)) AS sumv,SUM(IF(modify>='.$conf->mtime.',hslm,hsm)) AS sumhs,SUM(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS sumr,SUM(IF(modify>='.$conf->mtime.',htlm,htm)) AS sumht';
      $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0)))';
      if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
      $vars['INTERVAL']=$tint;
  }
  elseif(!strcmp($tint,'totalm')) {
      $values='v'.$year.' AS v,hs'.$year.' AS hs,ht'.$year.'-v'.$year.' AS r,ht'.$year.' AS ht';
      $minvalues='MIN(v'.$year.') AS minv,MIN(hs'.$year.') AS minhs,MIN(ht'.$year.'-v'.$year.') AS minr,MIN(ht'.$year.') AS minht';
      $maxvalues='MAX(v'.$year.') AS maxv,MAX(hs'.$year.') AS maxhs,MAX(ht'.$year.'-v'.$year.') AS maxr,MAX(ht'.$year.') AS maxht';
      $sumvalues='SUM(v'.$year.') AS sumv,SUM(hs'.$year.') AS sumhs,SUM(ht'.$year.'-v'.$year.') AS sumr,SUM(ht'.$year.') AS sumht';
      $where=' AND (v'.$year.'!=0 OR hs'.$year.'!=0 OR ht'.$year.'!=0)';
      $dateint=' ('.$dyear.') ';
      $vars['INTERVAL']=$tint.'_'.$dyear;
  }
  elseif(!strcmp($tint,'all')||!strcmp($tint,'total')) {
      $vvalues='v1';
      $hsvalues='hs1';
      $htvalues='ht1';
      for($i=2;$i<=$lyear;$i++) {
          $vvalues.='+v'.$i;
          $hsvalues.='+hs'.$i;
          $htvalues.='+ht'.$i;
      }
      $values='('.$vvalues.') AS v,('.$hsvalues.') AS hs,(('.$htvalues.')-('.$vvalues.')) AS r,('.$htvalues.') AS ht';
      $minvalues='MIN('.$vvalues.') AS minv,MIN('.$hsvalues.') AS minhs,MIN(('.$htvalues.')-('.$vvalues.')) AS minr,MIN('.$htvalues.') AS minht';
      $maxvalues='MAX('.$vvalues.') AS maxv,MAX('.$hsvalues.') AS maxhs,MAX(('.$htvalues.')-('.$vvalues.')) AS maxr,MAX('.$htvalues.') AS maxht';
      $sumvalues='SUM('.$vvalues.') AS sumv,SUM('.$hsvalues.') AS sumhs,SUM(('.$htvalues.')-('.$vvalues.')) AS sumr,SUM('.$htvalues.') AS sumht';
      $where=' AND (('.$vvalues.')!=0 OR ('.$hsvalues.')!=0 OR ('.$htvalues.')!=0)';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      $vars['INTERVAL']=$tint;
  }
  //total results
  $tot=array();
  //sum by group/201 - percent
  $tot['vsum']=0;
  $tot['hssum']=0;
  $tot['htsum']=0;
  //average by pages/groups - percent
  $tot['vavg']=0;
  $tot['hsavg']=0;
  $tot['htavg']=0;
  //min by pages/groups - percent
  $tot['vmin']=1000000;
  $tot['hsmin']=1000000;
  $tot['htmin']=1000000;
  //max by pages/groups - percent
  $tot['vmax']=0;
  $tot['hsmax']=0;
  $tot['htmax']=0;
  //sum input by pages/groups - number
  $tot['ven']=0;
  $tot['hsen']=0;
  $tot['hten']=0;
  //sum output by pages/groups - number
  $tot['vex']=0;
  $tot['hsex']=0;
  $tot['htex']=0;
  //average input by pages/groups - number
  $tot['vaen']=0;
  $tot['hsaen']=0;
  $tot['htaen']=0;
  //average output by pages/groups - number
  $tot['vaex']=0;
  $tot['hsaex']=0;
  $tot['htaex']=0;
  //min input by pages/groups - number
  $tot['vnen']=1000000;
  $tot['hsnen']=1000000;
  $tot['htnen']=1000000;
  //min output by pages/groups - number
  $tot['vnex']=1000000;
  $tot['hsnex']=1000000;
  $tot['htnex']=1000000;
  //max input by pages/groups - number
  $tot['vxen']=0;
  $tot['hsxen']=0;
  $tot['htxen']=0;
  //max output by pages/groups - number
  $tot['vxex']=0;
  $tot['hsxex']=0;
  $tot['htxex']=0;
  $nrect=0;

  $request='LOCK TABLES aa_points READ,aa_vectors READ,aa_pages READ,aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|trans|blocking of tables has failed -- '.mysql_error());return;}
  if($page_id!=221) {
      //get list of pages which contained in group
      $mid=array();
      getpgs($page_id,$mid);
      if($err->flag) {$err->reason('vdb.php|prod_grpg|\'getpgs\' function has failed');return;}
      $ids='';
      reset($mid);
      while($e=each($mid)) {
          if(empty($ids)) $ids.='('.$e[0];
          else $ids.=','.$e[0];
      }
      if(!empty($ids)) $ids.=')';
      else $ids='(0)';
  }

  //ENTRY aa_vectors
  if($page_id<221) $request='SELECT '.$values.',aa_pages.id AS id,aa_pages.name AS name,aa_pages.url AS url FROM aa_vectors LEFT OUTER JOIN aa_pages ON aa_vectors.destid=aa_pages.id WHERE aa_pages.id IS NOT NULL AND aa_vectors.destid IN '.$ids.$where;
  else $request='SELECT '.$values.',aa_groups.id AS id,aa_groups.name AS name FROM aa_vectors LEFT OUTER JOIN aa_groups ON aa_vectors.destid=aa_groups.id WHERE aa_groups.id IS NOT NULL AND aa_vectors.destid>200'.$where;
  $resultenv=mysql_query($request,$conf->link);
  if(!$resultenv) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //EXIT aa_vectors
  if($page_id<221) $request='SELECT '.$values.',aa_pages.id AS id,aa_pages.name AS name,aa_pages.url AS url FROM aa_vectors LEFT OUTER JOIN aa_pages ON aa_vectors.sourid=aa_pages.id WHERE aa_pages.id IS NOT NULL AND aa_vectors.sourid IN '.$ids.$where;
  else $request='SELECT '.$values.',aa_groups.id AS id,aa_groups.name AS name FROM aa_vectors LEFT OUTER JOIN aa_groups ON aa_vectors.sourid=aa_groups.id WHERE aa_groups.id IS NOT NULL AND aa_vectors.sourid>200'.$where;
  $resultexv=mysql_query($request,$conf->link);
  if(!$resultexv) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //ENTRY/EXIT aa_points
  if($page_id<221) $request='SELECT flag,'.$values.',aa_pages.id AS id,aa_pages.name AS name,aa_pages.url AS url FROM aa_points LEFT OUTER JOIN aa_pages ON aa_points.id=aa_pages.id WHERE aa_pages.id IS NOT NULL AND aa_points.id IN '.$ids.' AND flag!=3'.$where;
  else $request='SELECT flag,'.$values.',aa_groups.id AS id,aa_groups.name AS name FROM aa_points LEFT OUTER JOIN aa_groups ON aa_points.id=aa_groups.id WHERE aa_groups.id IS NOT NULL AND aa_points.id>200 AND flag!=3'.$where;
  $resultp=mysql_query($request,$conf->link);
  if(!$resultp) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //ENTRY aa_vectors SUMMARY
  if($page_id<221) $request='SELECT '.$sumvalues.' FROM aa_vectors WHERE destid='.$page_id.$where;
  else $request='SELECT '.$sumvalues.' FROM aa_vectors WHERE destid=201'.$where;
  $resultens=mysql_query($request,$conf->link);
  if(!$resultens) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //EXIT aa_vectors SUMMARY
  if($page_id<221) $request='SELECT '.$sumvalues.' FROM aa_vectors WHERE sourid='.$page_id.$where;
  else $request='SELECT '.$sumvalues.' FROM aa_vectors WHERE sourid=201'.$where;
  $resultexs=mysql_query($request,$conf->link);
  if(!$resultexs) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //ENTRY/EXIT aa_points SUMMARY
  if($page_id<221) $request='SELECT flag,'.$sumvalues.' FROM aa_points WHERE id='.$page_id.$where.' AND flag!=3 GROUP BY flag';
  else $request='SELECT flag,'.$sumvalues.' FROM aa_points WHERE id=201'.$where.' AND flag!=3 GROUP BY flag';
  $resultps=mysql_query($request,$conf->link);
  if(!$resultps) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|prod_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $idslist=array();
  $sortmas=array();
  $val=array();
  if($page_id<221) { $bnum=1; $enum=201; }
  else { $bnum=201; $enum=221; }
  for($i=$bnum;$i<$enum;$i++) {
      $val[$i]['name']='';
      $val[$i]['env']=0;
      $val[$i]['enhs']=0;
      $val[$i]['enr']=0;
      $val[$i]['enht']=0;
      $val[$i]['exv']=0;
      $val[$i]['exhs']=0;
      $val[$i]['exr']=0;
      $val[$i]['exht']=0;
  }
  while($row=mysql_fetch_object($resultenv)) {
      $val[$row->id]['name']=$row->name;
      $val[$row->id]['env']+=$row->v; $val[$row->id]['enhs']+=$row->hs;
      $val[$row->id]['enr']+=$row->r; $val[$row->id]['enht']+=$row->ht;
      if($page_id<221) $val[$row->id]['url']=$row->url;
      if(!isset($idslist[$row->id])) $idslist[$row->id]=1;
  }
  mysql_free_result($resultenv);
  while($row=mysql_fetch_object($resultexv)) {
      $val[$row->id]['name']=$row->name;
      $val[$row->id]['exv']+=$row->v; $val[$row->id]['exhs']+=$row->hs;
      $val[$row->id]['exr']+=$row->r; $val[$row->id]['exht']+=$row->ht;
      if($page_id<221) $val[$row->id]['url']=$row->url;
      if(!isset($idslist[$row->id])) $idslist[$row->id]=1;
  }
  mysql_free_result($resultexv);
  while($row=mysql_fetch_object($resultp)) {
      $val[$row->id]['name']=$row->name;
      if($row->flag==1) {
          $val[$row->id]['env']+=$row->v; $val[$row->id]['enhs']+=$row->hs;
          $val[$row->id]['enr']+=$row->r; $val[$row->id]['enht']+=$row->ht;
      }
//      else {
//          $val[$row->id]['exv']+=$row->v; $val[$row->id]['exhs']+=$row->hs;
//          $val[$row->id]['exr']+=$row->r; $val[$row->id]['exht']+=$row->ht;
//      }
      if($page_id<221) $val[$row->id]['url']=$row->url;
      if(!isset($idslist[$row->id])) $idslist[$row->id]=1;
  }
  mysql_free_result($resultp);
  $nrect=sizeof($idslist);

  while($row=mysql_fetch_object($resultens)) {
      $tot['ven']+=$row->sumv; $tot['hsen']+=$row->sumhs; $tot['hten']+=$row->sumht;
  }
  mysql_free_result($resultens);
  while($row=mysql_fetch_object($resultexs)) {
      $tot['vex']+=$row->sumv; $tot['hsex']+=$row->sumhs; $tot['htex']+=$row->sumht;
  }
  mysql_free_result($resultexs);
  while($row=mysql_fetch_object($resultps)) {
      if($row->flag==1) {
          $tot['ven']+=$row->sumv; $tot['hsen']+=$row->sumhs; $tot['hten']+=$row->sumht;
      }
//      else {
//          $tot['vex']+=$row->sumv; $tot['hsex']+=$row->sumhs; $tot['htex']+=$row->sumht;
//      }
  }
  mysql_free_result($resultps);
  $tot['vsum']=$tot['ven']?sprintf('%.2f',$tot['vex']/$tot['ven']*100):sprintf('%.2f',$tot['vex']*100);
  $tot['hssum']=$tot['hsen']?sprintf('%.2f',$tot['hsex']/$tot['hsen']*100):sprintf('%.2f',$tot['hsex']*100);
  $tot['htsum']=$tot['hten']?sprintf('%.2f',$tot['htex']/$tot['hten']*100):sprintf('%.2f',$tot['htex']*100);

  $ids=array();
  reset($idslist);
  while($k=key($idslist)) {
      $val[$k]['pv']=$val[$k]['env']?sprintf('%.2f',$val[$k]['exv']/$val[$k]['env']*100):sprintf('%.2f',$val[$k]['exv']*100);
      $val[$k]['phs']=$val[$k]['enhs']?sprintf('%.2f',$val[$k]['exhs']/$val[$k]['enhs']*100):sprintf('%.2f',$val[$k]['exv']*100);
      $val[$k]['pht']=$val[$k]['enht']?sprintf('%.2f',$val[$k]['exht']/$val[$k]['enht']*100):sprintf('%.2f',$val[$k]['exv']*100);
      if(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==4)) $sortmas[]=$val[$k]['pv'];
      if(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==4)) $sortmas[]=$val[$k]['phs'];
      if(($sort['table']==1&&$sort['column']==4)||($sort['table']==5&&$sort['column']==4)) $sortmas[]=$val[$k]['pht'];
      if($sort['table']==2&&$sort['column']==2) $sortmas[]=$val[$k]['env'];
      if($sort['table']==3&&$sort['column']==2) $sortmas[]=$val[$k]['enhs'];
      if($sort['table']==5&&$sort['column']==2) $sortmas[]=$val[$k]['enht'];
      if($sort['table']==2&&$sort['column']==3) $sortmas[]=$val[$k]['exv'];
      if($sort['table']==3&&$sort['column']==3) $sortmas[]=$val[$k]['exhs'];
      if($sort['table']==5&&$sort['column']==3) $sortmas[]=$val[$k]['exht'];
      if($sort['column']==1) $sortmas[]=$val[$k]['name'];
      $ids[]=$k;
      next($idslist);
  }
  if($sort['column']==1) array_multisort($sortmas,SORT_ASC,$ids,SORT_ASC);
  else array_multisort($sortmas,SORT_DESC,$ids,SORT_ASC);

  // length of column for graphic view
  $maxlen=105;
  $loc=array();
  $loc['vsum']=0;
  $loc['hssum']=0;
  $loc['htsum']=0;
  $loc['vavg']=0;
  $loc['hsavg']=0;
  $loc['htavg']=0;
  $loc['vmin']=1000000;
  $loc['hsmin']=1000000;
  $loc['htmin']=1000000;
  $loc['vmax']=0;
  $loc['hsmax']=0;
  $loc['htmax']=0;
  $loc['ven']=0;
  $loc['hsen']=0;
  $loc['hten']=0;
  $loc['vex']=0;
  $loc['hsex']=0;
  $loc['htex']=0;
  $loc['vaen']=0;
  $loc['hsaen']=0;
  $loc['htaen']=0;
  $loc['vaex']=0;
  $loc['hsaex']=0;
  $loc['htaex']=0;
  //min input by pages/groups - number
  $loc['vnen']=1000000;
  $loc['hsnen']=1000000;
  $loc['htnen']=1000000;
  //min output by pages/groups - number
  $loc['vnex']=1000000;
  $loc['hsnex']=1000000;
  $loc['htnex']=1000000;
  //max input by pages/groups - number
  $loc['vxen']=0;
  $loc['hsxen']=0;
  $loc['htxen']=0;
  //max output by pages/groups - number
  $loc['vxex']=0;
  $loc['hsxex']=0;
  $loc['htxex']=0;
  if(!$nrect) {
      $loc['vmin']='0.00'; $loc['hsmin']='0.00'; $loc['htmin']='0.00';
      $loc['vnen']='0'; $loc['hsnen']='0'; $loc['htnen']='0';
      $loc['vnex']='0'; $loc['hsnex']='0'; $loc['htnex']='0';
      $tot['vmin']='0.00'; $tot['hsmin']='0.00'; $tot['htmin']='0.00';
      $tot['vnen']='0'; $tot['hsnen']='0'; $tot['htnen']='0';
      $tot['vnex']='0'; $tot['hsnex']='0'; $tot['htnex']='0';
  }

  if($numstr>$nrect) $numstr=$nrect;               //nrect - the number of total pages in group/groups
  $nrec=$nrect-$begstr;                            //nrec - the number of frames pages in group/groups
  if($nrec>$numstr) $nrec=$numstr;

  require './style/'.$conf->style.'/template/vpg_pr_a.php';
  $vars['LISTLEN']=$nrect;
  $vars['STAB']=1;
  $vars['REF']='summary';
  $vars['HEADER']=_SUMMARY.' / ';
  $vars['RHEADER']=_PRODOFGRPG;
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  if($nrect) $vars['RANGE']=($begstr+1).' - '.($begstr+$nrec).' '._OUTOF.' '.$nrect;
  else $vars['RANGE']='0 - 0 '._OUTOF.' '.$nrect;
  $vars['SHOWING']=_SHOWING.' '.$nrec.' '._ITEM_S;
  if($page_id==221) $vars['FPG']=_FORALLGRS;
  else $vars['FPG']=_FORGR.' \'<strong><i>'.$name.'</i></strong>\'';
  if(!strcmp($tint,'month')) $vars['THEADER']=_MONTH.$dateint;
  elseif(!strcmp($tint,'week')) $vars['THEADER']=_WEEK.$dateint;
  elseif(!strcmp($tint,'yesterday')) $vars['THEADER']=_YESTERDAY.$dateint;
  elseif(!strcmp($tint,'today')) $vars['THEADER']=_TODAY.$dateint;
  elseif(!strcmp($tint,'totalm')) $vars['THEADER']=_YEAR.$dateint;
  elseif(!strcmp($tint,'lastweek')) $vars['THEADER']=_LASTWEEK.$dateint;
  elseif(!strcmp($tint,'lastmonth')) $vars['THEADER']=_LASTMONTH.$dateint;
  elseif(!strcmp($tint,'all')||!strcmp($tint,'total')) $vars['THEADER']=_ALLTIME.$dateint;
  $vars['VISITORS']=_VISITORS;
  $vars['HOSTS']=_HOSTS;
  $vars['HITS']=_HITS;
  $vars['DETAIL']=_SELECT;
  $vars['SORTBYN']=_SORTBYN;
  $vars['SORTBYV']=_SORTBYV;
  $vars['SORTBYHT']=_SORTBYHT;
  $vars['SORTBYHS']=_SORTBYHS;
  $vars['SORTBYR']=_SORTBYR;
  $vars['SORTBYIN']=_SORTBYIN;
  $vars['SORTBYOUT']=_SORTBYOUT;
  $vars['SORTBYP']=_SORTBYP;
  $vars['LBEG']=_STARTOFLIST;
  $vars['LLSCR']=_PREVPG;
  $vars['LRSCR']=_NEXTPG;
  $vars['LEND']=_ENDOFLIST;
  $vars['LLLSCR']=_10PGSBACK;
  $vars['LRLSCR']=_10PGSFORWARD;
  tparse($top,$vars);

  $num=$begstr+1;
  $minf=0;
  $i=0;
  $cids=sizeof($ids);
  if($nrect) {
      for($l=0;$l<$cids;$l++) {
          $k=$ids[$l];
          $tot['vmax']=max($tot['vmax'],$val[$k]['pv']);
          $tot['hsmax']=max($tot['hsmax'],$val[$k]['phs']);
          $tot['htmax']=max($tot['htmax'],$val[$k]['pht']);
          $tot['vmin']=min($tot['vmin'],$val[$k]['pv']);
          $tot['hsmin']=min($tot['hsmin'],$val[$k]['phs']);
          $tot['htmin']=min($tot['htmin'],$val[$k]['pht']);
          $tot['vxen']=max($tot['vxen'],$val[$k]['env']);
          $tot['hsxen']=max($tot['hsxen'],$val[$k]['enhs']);
          $tot['htxen']=max($tot['htxen'],$val[$k]['enht']);
          $tot['vnen']=min($tot['vnen'],$val[$k]['env']);
          $tot['hsnen']=min($tot['hsnen'],$val[$k]['enhs']);
          $tot['htnen']=min($tot['htnen'],$val[$k]['enht']);
          $tot['vxex']=max($tot['vxex'],$val[$k]['exv']);
          $tot['hsxex']=max($tot['hsxex'],$val[$k]['exhs']);
          $tot['htxex']=max($tot['htxex'],$val[$k]['exht']);
          $tot['vnex']=min($tot['vnex'],$val[$k]['exv']);
          $tot['hsnex']=min($tot['hsnex'],$val[$k]['exhs']);
          $tot['htnex']=min($tot['htnex'],$val[$k]['exht']);
          $tot['vavg']+=$val[$k]['pv'];
          $tot['hsavg']+=$val[$k]['phs'];
          $tot['htavg']+=$val[$k]['pht'];
          $tot['vaen']+=$val[$k]['env'];
          $tot['hsaen']+=$val[$k]['enhs'];
          $tot['htaen']+=$val[$k]['enht'];
          $tot['vaex']+=$val[$k]['exv'];
          $tot['hsaex']+=$val[$k]['exhs'];
          $tot['htaex']+=$val[$k]['exht'];
          $i++;
          if($i<$num) { next($idslist); continue; }
          if($i>$begstr+$numstr) break;
          $vars['NUM']=$num++;
          $vars['PGID']=$k;
          $fname=$val[$k]['name'];
          if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
          else $sname=$fname;
          $vars['GRPG']=$fname;
          $vars['GRPGSHORT']=$sname;
          if($page_id<221) $vars['PGURL']=$val[$k]['url'];
          $vars['VISITORS']=$val[$k]['pv'];
          $vars['HOSTS']=$val[$k]['phs'];
          $vars['HITS']=$val[$k]['pht'];
          if($page_id<221) tparse($centerp,$vars);
          else tparse($centerg,$vars);
          //frames parameters
          $loc['vmax']=max($loc['vmax'],$val[$k]['pv']);
          $loc['hsmax']=max($loc['hsmax'],$val[$k]['phs']);
          $loc['htmax']=max($loc['htmax'],$val[$k]['pht']);
          $loc['vmin']=min($loc['vmin'],$val[$k]['pv']);
          $loc['hsmin']=min($loc['hsmin'],$val[$k]['phs']);
          $loc['htmin']=min($loc['htmin'],$val[$k]['pht']);
          $loc['vxen']=max($loc['vxen'],$val[$k]['env']);
          $loc['hsxen']=max($loc['hsxen'],$val[$k]['enhs']);
          $loc['htxen']=max($loc['htxen'],$val[$k]['enht']);
          $loc['vnen']=min($loc['vnen'],$val[$k]['env']);
          $loc['hsnen']=min($loc['hsnen'],$val[$k]['enhs']);
          $loc['htnen']=min($loc['htnen'],$val[$k]['enht']);
          $loc['vxex']=max($loc['vxex'],$val[$k]['exv']);
          $loc['hsxex']=max($loc['hsxex'],$val[$k]['exhs']);
          $loc['htxex']=max($loc['htxex'],$val[$k]['exht']);
          $loc['vnex']=min($loc['vnex'],$val[$k]['exv']);
          $loc['hsnex']=min($loc['hsnex'],$val[$k]['exhs']);
          $loc['htnex']=min($loc['htnex'],$val[$k]['exht']);
          $loc['vavg']+=$val[$k]['pv'];
          $loc['hsavg']+=$val[$k]['phs'];
          $loc['htavg']+=$val[$k]['pht'];
          $loc['vaen']+=$val[$k]['env'];
          $loc['hsaen']+=$val[$k]['enhs'];
          $loc['htaen']+=$val[$k]['enht'];
          $loc['vaex']+=$val[$k]['exv'];
          $loc['hsaex']+=$val[$k]['exhs'];
          $loc['htaex']+=$val[$k]['exht'];
          $loc['vsum']+=$val[$k]['pv'];
          $loc['hssum']+=$val[$k]['phs'];
          $loc['htsum']+=$val[$k]['pht'];
          $minf=1;
          next($idslist);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) {
      $loc['vmin']='0.00'; $loc['hsmin']='0.00'; $loc['htmin']='0.00';
      $loc['vnen']='0'; $loc['hsnen']='0'; $loc['htnen']='0';
      $loc['vnex']='0'; $loc['hsnex']='0'; $loc['htnex']='0';
  }
  if($nrec) {
      $loc['vavg']=sprintf("%.2f",$loc['vsum']/$nrec);
      $loc['hsavg']=sprintf("%.2f",$loc['hssum']/$nrec);
      $loc['htavg']=sprintf("%.2f",$loc['htsum']/$nrec);
      $loc['vaen']=(int)($loc['vaen']/$nrec);
      $loc['hsaen']=(int)($loc['hsaen']/$nrec);
      $loc['htaen']=(int)($loc['htaen']/$nrec);
      $loc['vaex']=(int)($loc['vaex']/$nrec);
      $loc['hsaex']=(int)($loc['hsaex']/$nrec);
      $loc['htaex']=(int)($loc['htaex']/$nrec);
  }
  else {
      $loc['vavg']='0.00'; $loc['hsavg']='0.00'; $loc['htavg']='0.00';
  }
  if($nrect) {
      $tot['vavg']=sprintf("%.2f",$tot['vavg']/$nrect);
      $tot['hsavg']=sprintf("%.2f",$tot['hsavg']/$nrect);
      $tot['htavg']=sprintf("%.2f",$tot['htavg']/$nrect);
      $tot['vaen']=(int)($tot['vaen']/$nrect);
      $tot['hsaen']=(int)($tot['hsaen']/$nrect);
      $tot['htaen']=(int)($tot['htaen']/$nrect);
      $tot['vaex']=(int)($tot['vaex']/$nrect);
      $tot['hsaex']=(int)($tot['hsaex']/$nrect);
      $tot['htaex']=(int)($tot['htaex']/$nrect);
  }
  else {
      $tot['vavg']='0.00'; $tot['hsavg']='0.00'; $tot['htavg']='0.00';
  }

  if($numstr<$nrect) {
      $vars['VISITORS']='-';
      $vars['HOSTS']='-';
      $vars['HITS']='-';
      tparse($delimiter,$vars);
      $vars['NAME']=_MINIMUM;
      $vars['VISITORS']=$loc['vmin'];
      $vars['HOSTS']=$loc['hsmin'];
      $vars['HITS']=$loc['htmin'];
      tparse($foot,$vars);
      $vars['NAME']=_AVERAGE;
      $vars['VISITORS']=$loc['vavg'];
      $vars['HOSTS']=$loc['hsavg'];
      $vars['HITS']=$loc['htavg'];
      tparse($foot,$vars);
      $vars['NAME']=_MAXIMUM;
      $vars['VISITORS']=$loc['vmax']?$loc['vmax']:'0.00';
      $vars['HOSTS']=$loc['hsmax']?$loc['hsmax']:'0.00';
      $vars['HITS']=$loc['htmax']?$loc['htmax']:'0.00';
      tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['VISITORS']=$tot['vsum'];
  $vars['HOSTS']=$tot['hssum'];
  $vars['HITS']=$tot['htsum'];
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['VISITORS']=$tot['vmin'];
  $vars['HOSTS']=$tot['hsmin'];
  $vars['HITS']=$tot['htmin'];
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['VISITORS']=$tot['vavg'];
  $vars['HOSTS']=$tot['hsavg'];
  $vars['HITS']=$tot['htavg'];
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['VISITORS']=$tot['vmax']?$tot['vmax']:'0.00';
  $vars['HOSTS']=$tot['hsmax']?$tot['hsmax']:'0.00';
  $vars['HITS']=$tot['htmax']?$tot['htmax']:'0.00';
  tparse($foot,$vars);
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

  require './style/'.$conf->style.'/template/vpg_pr_d.php';
  //VISITORS
  $vars['STAB']=2;
  $vars['HEADER']=_VISITORS.' / ';
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['REF']='visitors';
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['SORT']=_SORTBYV;
  $vars['IN']=_IN;
  $vars['OUT']=_OUT;
  $vars['PER']='%';
  tparse($top,$vars);
  $num=$begstr+1;
  $i=0;
  if($nrect) {
      for($l=0;$l<$cids;$l++) {
          $k=$ids[$l];
          $i++;
          if($i<$num) { next($idslist); continue; }
          if($i>$begstr+$numstr) break;
          $vars['NUM']=$num++;
          $vars['PGID']=$k;
          $fname=$val[$k]['name'];
          if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
          else $sname=$fname;
          $vars['GRPG']=$fname;
          $vars['GRPGSHORT']=$sname;
          if($page_id<221) $vars['PGURL']=$val[$k]['url'];
          $vars['IN']=$val[$k]['env'];
          $vars['OUT']=$val[$k]['exv'];
          $vars['PER']=$val[$k]['pv'];
          $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$val[$k]['pv']/$tot['vmax']):0;
          if($page_id<221) tparse($centerp,$vars);
          else tparse($centerg,$vars);
          next($idslist);
      }
  }
  else { $vars['TEXT']=_NORECORDS;  tparse($empty,$vars); }
  if($numstr<$nrect) {
      $vars['IN']='-';
      $vars['OUT']='-';
      $vars['PER']='-';
      tparse($delimiter,$vars);
      $vars['NAME']=_MINIMUM;
      $vars['IN']=$loc['vnen'];
      $vars['OUT']=$loc['vnex'];
      $vars['PER']=$loc['vmin'];
      $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$loc['vmin']/$tot['vmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_AVERAGE;
      $vars['IN']=$loc['vaen'];
      $vars['OUT']=$loc['vaex'];
      $vars['PER']=$loc['vavg'];
      $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$loc['vavg']/$tot['vmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_MAXIMUM;
      $vars['IN']=$loc['vxen'];
      $vars['OUT']=$loc['vxex'];
      $vars['PER']=$loc['vmax']?$loc['vmax']:'0.00';
      $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$loc['vmax']/$tot['vmax']):'0';
      tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['IN']=$tot['ven'];
  $vars['OUT']=$tot['vex'];
  $vars['PER']=$tot['vsum'];
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['IN']=$tot['vnen'];
  $vars['OUT']=$tot['vnex'];
  $vars['PER']=$tot['vmin'];
  $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$tot['vmin']/$tot['vmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['IN']=$tot['vaen'];
  $vars['OUT']=$tot['vaex'];
  $vars['PER']=$tot['vavg'];
  $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$tot['vavg']/$tot['vmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['IN']=$tot['vxen'];
  $vars['OUT']=$tot['vxex'];
  $vars['PER']=$tot['vmax']?$tot['vmax']:'0.00';
  $vars['GRAPHIC']=$tot['vmax']?(int)($maxlen*$tot['vmax']/$tot['vmax']):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HOSTS
  $vars['STAB']=3;
  $vars['HEADER']=_HOSTS.' / ';
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['REF']='hosts';
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['SORT']=_SORTBYHS;
  $vars['IN']=_IN;
  $vars['OUT']=_OUT;
  $vars['PER']='%';
  tparse($top,$vars);
  $num=$begstr+1;
  $i=0;
  if($nrect) {
      for($l=0;$l<$cids;$l++) {
          $k=$ids[$l];
          $i++;
          if($i<$num) { next($idslist); continue; }
          if($i>$begstr+$numstr) break;
          $vars['NUM']=$num++;
          $vars['PGID']=$k;
          $fname=$val[$k]['name'];
          if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
          else $sname=$fname;
          $vars['GRPG']=$fname;
          $vars['GRPGSHORT']=$sname;
          if($page_id<221) $vars['PGURL']=$val[$k]['url'];
          $vars['IN']=$val[$k]['enhs'];
          $vars['OUT']=$val[$k]['exhs'];
          $vars['PER']=$val[$k]['phs'];
          $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$val[$k]['phs']/$tot['hsmax']):0;
          if($page_id<221) tparse($centerp,$vars);
          else tparse($centerg,$vars);
          next($idslist);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
      $vars['IN']='-';
      $vars['OUT']='-';
      $vars['PER']='-';
      tparse($delimiter,$vars);
      $vars['NAME']=_MINIMUM;
      $vars['IN']=$loc['hsnen'];
      $vars['OUT']=$loc['hsnex'];
      $vars['PER']=$loc['hsmin'];
      $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$loc['hsmin']/$tot['hsmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_AVERAGE;
      $vars['IN']=$loc['hsaen'];
      $vars['OUT']=$loc['hsaex'];
      $vars['PER']=$loc['hsavg'];
      $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$loc['hsavg']/$tot['hsmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_MAXIMUM;
      $vars['IN']=$loc['hsxen'];
      $vars['OUT']=$loc['hsxex'];
      $vars['PER']=$loc['hsmax']?$loc['hsmax']:'0.00';
      $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$loc['hsmax']/$tot['hsmax']):'0';
      tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['IN']=$tot['hsen'];
  $vars['OUT']=$tot['hsex'];
  $vars['PER']=$tot['hssum'];
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['IN']=$tot['hsnen'];
  $vars['OUT']=$tot['hsnex'];
  $vars['PER']=$tot['hsmin'];
  $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$tot['hsmin']/$tot['hsmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['IN']=$tot['hsaen'];
  $vars['OUT']=$tot['hsaex'];
  $vars['PER']=$tot['hsavg'];
  $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$tot['hsavg']/$tot['hsmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['IN']=$tot['hsxen'];
  $vars['OUT']=$tot['hsxex'];
  $vars['PER']=$tot['hsmax']?$tot['hsmax']:'0.00';
  $vars['GRAPHIC']=$tot['hsmax']?(int)($maxlen*$tot['hsmax']/$tot['hsmax']):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HITS
  $vars['STAB']=5;
  $vars['HEADER']=_HITS.' / ';
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['REF']='hits';
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['SORT']=_SORTBYHT;
  $vars['IN']=_IN;
  $vars['OUT']=_OUT;
  $vars['PER']='%';
  tparse($top,$vars);
  $num=$begstr+1;
  $i=0;
  if($nrect) {
      for($l=0;$l<$cids;$l++) {
          $k=$ids[$l];
          $i++;
          if($i<$num) { next($idslist); continue; }
          if($i>$begstr+$numstr) break;
          $vars['NUM']=$num++;
          $vars['PGID']=$k;
          $fname=$val[$k]['name'];
          if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
          else $sname=$fname;
          $vars['GRPG']=$fname;
          $vars['GRPGSHORT']=$sname;
          if($page_id<221) $vars['PGURL']=$val[$k]['url'];
          $vars['IN']=$val[$k]['enht'];
          $vars['OUT']=$val[$k]['exht'];
          $vars['PER']=$val[$k]['pht'];
          $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$val[$k]['pht']/$tot['htmax']):0;
          if($page_id<221) tparse($centerp,$vars);
          else tparse($centerg,$vars);
          next($idslist);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
      $vars['IN']='-';
      $vars['OUT']='-';
      $vars['PER']='-';
      tparse($delimiter,$vars);
      $vars['NAME']=_MINIMUM;
      $vars['IN']=$loc['htnen'];
      $vars['OUT']=$loc['htnex'];
      $vars['PER']=$loc['htmin'];
      $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$loc['htmin']/$tot['htmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_AVERAGE;
      $vars['IN']=$loc['htaen'];
      $vars['OUT']=$loc['htaex'];
      $vars['PER']=$loc['htavg'];
      $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$loc['htavg']/$tot['htmax']):'0';
      tparse($foot,$vars);
      $vars['NAME']=_MAXIMUM;
      $vars['IN']=$loc['htxen'];
      $vars['OUT']=$loc['htxex'];
      $vars['PER']=$loc['htmax']?$loc['htmax']:'0.00';
      $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$loc['htmax']/$tot['htmax']):'0';
      tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['IN']=$tot['hten'];
  $vars['OUT']=$tot['htex'];
  $vars['PER']=$tot['htsum'];
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['IN']=$tot['htnen'];
  $vars['OUT']=$tot['htnex'];
  $vars['PER']=$tot['htmin'];
  $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$tot['htmin']/$tot['htmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['IN']=$tot['htaen'];
  $vars['OUT']=$tot['htaex'];
  $vars['PER']=$tot['htavg'];
  $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$tot['htavg']/$tot['htmax']):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['IN']=$tot['htxen'];
  $vars['OUT']=$tot['htxex'];
  $vars['PER']=$tot['htmax']?$tot['htmax']:'0.00';
  $vars['GRAPHIC']=$tot['htmax']?(int)($maxlen*$tot['htmax']/$tot['htmax']):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

?>
