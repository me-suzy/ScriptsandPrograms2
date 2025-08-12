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
      $sumvalues='vt AS sumv,hst AS sumhs,(htt-vt) AS sumr,htt AS sumht';
      $where=' AND (modify>='.$conf->dtime.' AND (vt!=0 OR hst!=0 OR htt!=0)) ';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime).') ';
  }
  elseif(!strcmp($tint,'yesterday')) {
      $values='IF(modify>='.$conf->dtime.',vy,vt) AS v,IF(modify>='.$conf->dtime.',hsy,hst) AS hs,IF(modify>='.$conf->dtime.',hty-vy,htt-vt) AS r,IF(modify>='.$conf->dtime.',hty,htt) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->dtime.',vy,vt)) AS minv,MIN(IF(modify>='.$conf->dtime.',hsy,hst)) AS minhs,MIN(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS minr,MIN(IF(modify>='.$conf->dtime.',hty,htt)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->dtime.',vy,vt)) AS maxv,MAX(IF(modify>='.$conf->dtime.',hsy,hst)) AS maxhs,MAX(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS maxr,MAX(IF(modify>='.$conf->dtime.',hty,htt)) AS maxht';
      $sumvalues='IF(modify>='.$conf->dtime.',vy,vt) AS sumv,IF(modify>='.$conf->dtime.',hsy,hst) AS sumhs,IF(modify>='.$conf->dtime.',hty-vy,htt-vt) AS sumr,IF(modify>='.$conf->dtime.',hty,htt) AS sumht';
      $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0))) ';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$ydtime).') ';
  }
  elseif(!strcmp($tint,'week')) {
      $values='vw AS v,hsw AS hs,htw-vw AS r,htw AS ht';
      $minvalues='MIN(vw) AS minv,MIN(hsw) AS minhs,MIN(htw-vw) AS minr,MIN(htw) AS minht';
      $maxvalues='MAX(vw) AS maxv,MAX(hsw) AS maxhs,MAX(htw-vw) AS maxr,MAX(htw) AS maxht';
      $sumvalues='vw AS sumv,hsw AS sumhs,(htw-vw) AS sumr,htw AS sumht';
      $where=' AND (modify>='.$conf->wtime.' AND (vw!=0 OR hsw!=0 OR htw!=0)) ';
      if($conf->btime>$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  }
  elseif(!strcmp($tint,'lastweek')) {
      $values='IF(modify>='.$conf->wtime.',vlw,vw) AS v,IF(modify>='.$conf->wtime.',hslw,hsw) AS hs,IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw) AS r,IF(modify>='.$conf->wtime.',htlw,htw) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->wtime.',vlw,vw)) AS minv,MIN(IF(modify>='.$conf->wtime.',hslw,hsw)) AS minhs,MIN(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS minr,MIN(IF(modify>='.$conf->wtime.',htlw,htw)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->wtime.',vlw,vw)) AS maxv,MAX(IF(modify>='.$conf->wtime.',hslw,hsw)) AS maxhs,MAX(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS maxr,MAX(IF(modify>='.$conf->wtime.',htlw,htw)) AS maxht';
      $sumvalues='IF(modify>='.$conf->wtime.',vlw,vw) AS sumv,IF(modify>='.$conf->wtime.',hslw,hsw) AS sumhs,IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw) AS sumr,IF(modify>='.$conf->wtime.',htlw,htw) AS sumht';
      $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0))) ';
      if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  }
  elseif(!strcmp($tint,'month')) {
      $values='vm AS v,hsm AS hs,htm-vm AS r,htm AS ht';
      $minvalues='MIN(vm) AS minv,MIN(hsm) AS minhs,MIN(htm-vm) AS minr,MIN(htm) AS minht';
      $maxvalues='MAX(vm) AS maxv,MAX(hsm) AS maxhs,MAX(htm-vm) AS maxr,MAX(htm) AS maxht';
      $sumvalues='vm AS sumv,hsm AS sumhs,(htm-vm) AS sumr,htm AS sumht';
      $where=' AND (modify>='.$conf->mtime.' AND (vm!=0 OR hsm!=0 OR htm!=0)) ';
      if($conf->btime>$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $values='IF(modify>='.$conf->mtime.',vlm,vm) AS v,IF(modify>='.$conf->mtime.',hslm,hsm) AS hs,IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm) AS r,IF(modify>='.$conf->mtime.',htlm,htm) AS ht';
      $minvalues='MIN(IF(modify>='.$conf->mtime.',vlm,vm)) AS minv,MIN(IF(modify>='.$conf->mtime.',hslm,hsm)) AS minhs,MIN(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS minr,MIN(IF(modify>='.$conf->mtime.',htlm,htm)) AS minht';
      $maxvalues='MAX(IF(modify>='.$conf->mtime.',vlm,vm)) AS maxv,MAX(IF(modify>='.$conf->mtime.',hslm,hsm)) AS maxhs,MAX(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS maxr,MAX(IF(modify>='.$conf->mtime.',htlm,htm)) AS maxht';
      $sumvalues='IF(modify>='.$conf->mtime.',vlm,vm) AS sumv,IF(modify>='.$conf->mtime.',hslm,hsm) AS sumhs,IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm) AS sumr,IF(modify>='.$conf->mtime.',htlm,htm) AS sumht';
      $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0))) ';
      if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
      else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  }
  elseif(!strcmp($tint,'totalm')) {
      $values='v'.$year.' AS v,hs'.$year.' AS hs,ht'.$year.'-v'.$year.' AS r,ht'.$year.' AS ht';
      $minvalues='MIN(v'.$year.') AS minv,MIN(hs'.$year.') AS minhs,MIN(ht'.$year.'-v'.$year.') AS minr,MIN(ht'.$year.') AS minht';
      $maxvalues='MAX(v'.$year.') AS maxv,MAX(hs'.$year.') AS maxhs,MAX(ht'.$year.'-v'.$year.') AS maxr,MAX(ht'.$year.') AS maxht';
      $sumvalues='v'.$year.' AS sumv,hs'.$year.' AS sumhs,(ht'.$year.'-v'.$year.') AS sumr,ht'.$year.' AS sumht';
      $where=' AND (v'.$year.'!=0 OR hs'.$year.'!=0 OR ht'.$year.'!=0) ';
      $dateint=' ('.$dyear.') ';
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
      $sumvalues='('.$vvalues.') AS sumv,('.$hsvalues.') AS sumhs,(('.$htvalues.')-('.$vvalues.')) AS sumr,('.$htvalues.') AS sumht';
      $where=' AND (('.$vvalues.')!=0 OR ('.$hsvalues.')!=0 OR ('.$htvalues.')!=0) ';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
  }
  if($page_id!=221) {
      if($sort['column']==1) $ordert='aa_pages.name ASC';
      elseif(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==2)) $ordert='v DESC,aa_pages.name ASC';
      elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==2)) $ordert='hs DESC,aa_pages.name ASC';
      elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==2)) $ordert='r DESC,aa_pages.name ASC';
      elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==2)) $ordert='ht DESC,aa_pages.name ASC';
  }
  else {
      if($sort['column']==1) $ordert='aa_groups.name ASC';
      elseif(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==2)) $ordert='v DESC,aa_groups.name ASC';
      elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==2)) $ordert='hs DESC,aa_groups.name ASC';
      elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==2)) $ordert='r DESC,aa_groups.name ASC';
      elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==2)) $ordert='ht DESC,aa_groups.name ASC';
  }
  //total results
  $vsumt=0;
  $hssumt=0;
  $htsumt=0;
  $rsumt=0;
  $vavgt=0;
  $hsavgt=0;
  $htavgt=0;
  $ravgt=0;
  $nrect=0;
  $vmint=1000000;
  $hsmint=1000000;
  $htmint=1000000;
  $rmint=1000000;
  $vmaxt=0;
  $hsmaxt=0;
  $htmaxt=0;
  $rmaxt=0;

  $request='LOCK TABLES aa_points READ,aa_pages READ,aa_groups READ,aa_vectors READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|ways|blocking of tables has failed -- '.mysql_error());return;}
  if($page_id!=221) {
      //get list of pages which contained in group
      $emptyid=0;
      $mid=array();
      getpgs($page_id,$mid);
      if($err->flag) {$err->reason('vdb.php|ways|\'getpgs\' function has failed');return;}
      $ids='';
      reset($mid);
      while($e=each($mid)) {
          if(empty($ids)) $ids.='('.$e[0];
          else $ids.=','.$e[0];
      }
      if(!empty($ids)) $ids.=')';
      else $ids='(0)';
  }
  $pages=array();
  $request='SELECT id,name,url FROM aa_pages WHERE id IN'.$ids;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|ways|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) {
      $pages[$row->id]['name']=$row->name;
      $pages[$row->id]['url']=$row->url;
  }
  mysql_free_result($result);
  //get entry/exit list
  $request='SELECT flag,id,'.$values.' FROM aa_points WHERE id IN'.$ids.$where.' ORDER BY flag ASC,ht DESC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|ways|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $encount=0;
  $excount=0;
  $enp=array();
  $exp=array();
  $esg=array();
  $ways=array();
  $enav=0;
  $exav=0;
  while($row=mysql_fetch_object($result)) {
      if($row->flag==1) {
          $encount++;
          $enav+=$row->ht;
      }
      if($row->flag==2) {
          $excount++;
          $exav+=$row->ht;
      }
      if($row->flag==3) {
          $enav-=$row->ht;
          $exav-=$row->ht;
          $esg[$row->id]['v']=$row->v;
          $esg[$row->id]['hs']=$row->hs;
          $esg[$row->id]['r']=$row->r;
          $esg[$row->id]['ht']=$row->ht;
      }
  }
  $enav=$encount?$enav/$encount:0;
  $exav=$excount?$exav/$excount:0;
  $encount=0;
  $excount=0;
  if(mysql_num_rows($result)) mysql_data_seek($result,0);
  while($row=mysql_fetch_object($result)) {
      if($encount==10&&$excount==10) break;
      $v=$row->v;
      $hs=$row->hs;
      $r=$row->r;
      $ht=$row->ht;
      if(isset($esg[$row->id])) {
          $v-=$esg[$row->id]['v'];
          $hs-=$esg[$row->id]['hs'];
          $r-=$esg[$row->id]['r'];
          $ht-=$esg[$row->id]['ht'];
      }
      if($row->flag==1&&$encount<10&&$ht>=$enav) {
          $encount++;
          $enp[$row->id]['v']=$v;
          $enp[$row->id]['hs']=$hs;
          $enp[$row->id]['r']=$r;
          $enp[$row->id]['ht']=$ht;
      }
      if($row->flag==2&&$excount<10&&$ht>=$exav) {
          $excount++;
          $exp[$row->id]['v']=$v;
          $exp[$row->id]['hs']=$hs;
          $exp[$row->id]['r']=$r;
          $exp[$row->id]['ht']=$ht;
      }
  }
  mysql_free_result($result);
  $p=0;
  while($k=key($enp)) {
      $destid[0]=$k;
      $checkloop=array();
      //prev state
      $way=array();
      $way[0]['way']=$k;
      $way[0]['v']=$enp[$k]['v'];
      $way[0]['hs']=$enp[$k]['hs'];
      $way[0]['r']=$enp[$k]['r'];
      $way[0]['ht']=$enp[$k]['ht'];
      $loop=' NOT IN ('.$destid[0];
      $repeat=0;
      $checkloop[$destid[0]]=1;
      for($i=0;$i<20;$i++) {
          $loopl=$loop;
          for($j=0;$j<10;$j++) {
              $request='SELECT destid,'.$maxvalues.',COUNT(*) AS nrec FROM aa_vectors WHERE destid'.$loopl.') AND destid IN'.$ids.' AND sourid='.$destid[$i].$where.' GROUP BY destid ORDER BY maxht DESC';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('vdb.php|ways|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $repeat=0;
              $row=mysql_fetch_object($result);
              mysql_free_result($result);
              if($row->nrec) break;//for($j=0;$j<10;$j++)
              $loopl.=','.$destid[$i];
              $i--;
              if($i<0) { $j=10; break; }
          }//for($j=0;$j<10;$j++)
          if($j==10) break;//for($i=0;$i<20;$i++)
          if(isset($checkloop[$row->destid])) {//exclude repeated vectors
              $repeat=1;
              $i--;
              if($i<0) { break; }
              $loop.=','.$row->destid;
          }//if(isset($checkloop[$destid[$i]]))
          else {
              $checkloop[$row->destid]=1;
              $destid[$i+1]=$row->destid;
              $loop=' NOT IN ('.$row->destid.','.$destid[$i];
          }//else
          if(!$repeat) {
              $way[$i+1]['way']=$row->destid;
              $way[$i+1]['v']=$row->maxv;
              $way[$i+1]['hs']=$row->maxhs;
              $way[$i+1]['r']=$row->maxr;
              $way[$i+1]['ht']=$row->maxht;
              if(isset($exp[$row->destid])&&$k!=$destid[$i]&&$k!=$destid[$i-1]&&$k!=$row->destid) {
                  break;//for($i=0;$i<20;$i++)
              }
          }//if(!$repeat)

      }//for($i=0;$i<20;$i++)
      if(sizeof($way)>1) {
          $ways[$p]=array();
          $ways[$p]=array_merge($ways[$p],$way);
          $p++;
      }//if(sizeof($way)>1)
      next($enp);
  }//while($k=key($enp))

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|ways|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  require './style/'.$conf->style.'/template/vpg_ws.php';
  $vars['REF']='summary';
  $vars['RHEADER']=_MPWAYS;
  $vars['FPG']=_FORGR.' \'<strong><i>'.$name.'</i></strong>\'';
  if(!strcmp($tint,'month')) $vars['THEADER']=_MONTH.$dateint;
  elseif(!strcmp($tint,'week')) $vars['THEADER']=_WEEK.$dateint;
  elseif(!strcmp($tint,'yesterday')) $vars['THEADER']=_YESTERDAY.$dateint;
  elseif(!strcmp($tint,'today')) $vars['THEADER']=_TODAY.$dateint;
  elseif(!strcmp($tint,'totalm')) $vars['THEADER']=_YEAR.$dateint;
  elseif(!strcmp($tint,'lastweek')) $vars['THEADER']=_LASTWEEK.$dateint;
  elseif(!strcmp($tint,'lastmonth')) $vars['THEADER']=_LASTMONTH.$dateint;
  elseif(!strcmp($tint,'total')) $vars['THEADER']=_TOTAL.$dateint;
  elseif(!strcmp($tint,'all')) $vars['THEADER']=_ALLTIME.$dateint;
  $vars['INTERVAL']='all';
  $vars['BACKTT']=_BACKTOTOP;
  $nway=1;
  if(sizeof($ways)) {
      for($i=0;$i<sizeof($ways);$i++) {
          $vars['HEADER']=_WAY.' '.$nway.' / ';
          $nway++;
          $vars['GRPG']=_PAGE;
          if(sizeof($ways[$i])) $vars['RANGE']='1 - '.(sizeof($ways[$i])).' '._OUTOF.' '.sizeof($ways[$i]);
          else $vars['RANGE']='0 - 0 '._OUTOF.' '.sizeof($ways[$i]);
          $vars['SHOWING']=_SHOWING.' '.sizeof($ways[$i]).' '._ITEM_S;
          $vars['VISITORS']=_VISITORS;
          $vars['HOSTS']=_HOSTS;
          $vars['RELOADS']=_RELOADS;
          $vars['HITS']=_HITS;
          $vars['DETAIL']=_DETAILED;
          tparse($top,$vars);
          $num=1;
          for($j=0;$j<sizeof($ways[$i]);$j++) {
              $vars['NUM']=$num++;
              $vars['PGID']=$ways[$i][$j]['way'];
              $fname=$pages[$ways[$i][$j]['way']]['name'];
              if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
              else $sname=$fname;
              $vars['GRPG']=$fname;
              $vars['GRPGSHORT']=$sname;
              $vars['PGURL']=$pages[$ways[$i][$j]['way']]['url'];
              $vars['VISITORS']=$ways[$i][$j]['v'];
              $vars['HOSTS']=$ways[$i][$j]['hs'];
              $vars['HITS']=$ways[$i][$j]['ht'];
              $vars['RELOADS']=$ways[$i][$j]['r'];
              tparse($centerp,$vars);
          }//for($j=sizeof($ways[$i])-1;$j>=0;$j--)
          tparse($bottom,$vars);
      }//for($i=0;$i<sizeof($ways);$i++)
  }//if(sizeof($ways))
  else {
          $vars['HEADER']=_WAY.' 1'.' / ';
          $nway++;
          $vars['GRPG']=_PAGE;
          $vars['RANGE']='0 - 0 '._OUTOF.' 0';
          $vars['SHOWING']=_SHOWING.' 0 '._ITEM_S;
          $vars['VISITORS']=_VISITORS;
          $vars['HOSTS']=_HOSTS;
          $vars['RELOADS']=_RELOADS;
          $vars['HITS']=_HITS;
          $vars['DETAIL']=_DETAILED;
          tparse($top,$vars);
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
          tparse($bottom,$vars);
  }

?>
