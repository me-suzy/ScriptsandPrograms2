<?php

  //what=0-all time, 1-month, 2-week, 3-yesterday, 4-today, 5-year, 6-last week, 7-last month
  if(!strcmp($tint,'today')) $what=4;
  elseif(!strcmp($tint,'yesterday')) $what=3;
  elseif(!strcmp($tint,'week')) $what=2;
  elseif(!strcmp($tint,'lastweek')) $what=6;
  elseif(!strcmp($tint,'month')) $what=1;
  elseif(!strcmp($tint,'lastmonth')) $what=7;
  elseif(!strcmp($tint,'totalm')) $what=5;
  elseif(!strcmp($tint,'all')||!strcmp($tint,'total')) $what=0;

  //set sorting string
  if($sort['column']==1) $ordert='id ASC';
  elseif(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==2)) $ordert='v DESC';
  elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==2)) $ordert='hs DESC';
  elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==2)) $ordert='r DESC';
  elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==2)) $ordert='ht DESC';

  require './style/'.$conf->style.'/template/vpg_a.php';

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

  //get list of pages which contained in group
  $emptyid=0;
  if($page_id<221) {
      $mid=array();
      getpgs($page_id,$mid);
      if($err->flag) {$err->reason('vdb.php|grpg|\'getpgs\' function has failed');return;}
      $ids='';
      reset($mid);
      while($e=each($mid)) {
          if(empty($ids)) $ids.='('.$e[0];
          else $ids.=','.$e[0];
      }
      if(!empty($ids)) $ids.=','.$page_id.')';           //page_id - for get summary information about group
      else { $ids.='('.$page_id.')'; $emptyid=1; }
  }

  $resmainv=array();              //results matrix sorted by v/hs/ht/r
  $resmainhs=array();              //results matrix sorted by v/hs/ht/r
  $resmainr=array();              //results matrix sorted by v/hs/ht/r
  $resmainht=array();              //results matrix sorted by v/hs/ht/r
  $gpnames=array();              //names matrix sorted by names
  $urls=array();                 //matrix of pages URLs

  $request='LOCK TABLES aa_pages READ,aa_groups READ,aa_total READ,aa_days READ,aa_hours READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //get list of groups/pages
  if($page_id==221) $request='SELECT id,name FROM aa_groups WHERE added!=0 ORDER BY name ASC';
  else $request='SELECT id,name,url FROM aa_pages WHERE id IN '.$ids.' ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) {
      $gpnames[$row->id]=sprintf("%s%03d",$row->name,$row->id);
      if($page_id!=221) $urls[$row->id]=$row->url;
  }
  if($page_id!=221) $gpnames[$page_id]=sprintf("%s%03d",$name,$page_id);
  mysql_free_result($result);

  if($what==0 || $what==5) {        //for all time and any year
      if($what==0) {
          $rbeg=0;
          $rend=$conf->mnum;
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }
      elseif($what==5) {
          //begin month of year
          $byear=date('Y',$conf->btime);
          $bmonth=date('m',$conf->btime);
          $rend=($year-$byear)*12+(12-$bmonth);        //end month for select
          if($year==$byear) $rbeg=0;
          else $rbeg=$rend-11;                        //-1 for calculate increase
          $dateint=' ('.$byear.') ';
      }
      //get data from aa_total (group by id)
      if($page_id==221) $request='SELECT id,SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,SUM(hits-visitors) AS r FROM aa_total WHERE time>='.$rbeg.' AND time<='.$rend.' AND id>200 GROUP BY id  ORDER BY '.$ordert;
      else $request='SELECT id,SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,SUM(hits-visitors) AS r FROM aa_total WHERE time>='.$rbeg.' AND time<='.$rend.' AND id IN '.$ids.' GROUP BY id  ORDER BY '.$ordert;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($rowt=mysql_fetch_object($result)) {
          if($rowt->id) {
            $resmainv[$rowt->id]=$rowt->v;
            $resmainhs[$rowt->id]=$rowt->hs;
            $resmainht[$rowt->id]=$rowt->ht;
            $resmainr[$rowt->id]=$resmainht[$rowt->id]-$resmainv[$rowt->id];
          }
      }
      mysql_free_result($result);
      //get last records from aa_days
      if($page_id==221) $request='SELECT time,id,visitors_t AS v,hosts AS hs,hits AS ht,hits-visitors_t AS r FROM aa_days WHERE id>200 ORDER BY time DESC';
      else $request='SELECT time,id,visitors_t AS v,hosts AS hs,hits AS ht,hits-visitors_t AS r FROM aa_days WHERE id IN '.$ids.' ORDER BY time DESC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $p=array();    //massive of ids where was already processed
      while($rowd=mysql_fetch_object($result)) {
          if(isset($p[$rowd->id])) continue;
          $p[$rowd->id]=1;
          if($what==5) {
              $lyear=date('Y',$rowd->time*$conf->time1+$conf->btime);
              if($lyear!=$year) continue;
          }
          if(isset($resmainv[$rowd->id])) {
              $resmainv[$rowd->id]+=$rowd->v;
              $resmainhs[$rowd->id]+=$rowd->hs;
              $resmainht[$rowd->id]+=$rowd->ht;
              $resmainr[$rowd->id]=$resmainht[$rowd->id]-$resmainv[$rowd->id];
          }
          else {
              $resmainv[$rowd->id]=$rowd->v;
              $resmainhs[$rowd->id]=$rowd->hs;
              $resmainht[$rowd->id]=$rowd->ht;
              $resmainr[$rowd->id]=$resmainht[$rowd->id]-$resmainv[$rowd->id];
          }
      }
      mysql_free_result($result);
  }//if($what==0 && mysql_num_rows($result1)>0)
  else {
          //begin & end time for selecting of records
      if($what==4) {
         $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;     //number of begin hour today
         $rend=$conf->hnum+1;                                    //current hour+1
         $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime).') ';
         $table='aa_hours';
         $vis='aa_hours.visitors';
      }
      elseif($what==3) {
         $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;  //number of begin hour of yesterday
         $rend=$rbeg+24;                                         //number of begin hour of today
         $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime-7200).') ';
         $table='aa_hours';
         $vis='aa_hours.visitors';
      }
      elseif($what==2) {
          $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
          $rend=$conf->dnum+1;
          if($rbeg<0) $rbeg=0;
          $table='aa_days';
          $vis='aa_days.visitors_w';
          if($conf->btime>$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
          else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      }
      elseif($what==1) {
          $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
          $rend=$conf->dnum+1;
          if($rbeg<0) $rbeg=0;
          $table='aa_days';
          $vis='aa_days.visitors_m';
          if($conf->btime>$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
          else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
      }
      elseif($what==6) {
          $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
          $rend=$rbeg+7;
          if($rbeg<0) $rbeg=0;
          $table='aa_days';
          $vis='aa_days.visitors_w';
          if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
          else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
      }
      elseif($what==7) {
          $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
          $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
          if($rbeg<0) $rbeg=0;
          $table='aa_days';
          $vis='aa_days.visitors_m';
          if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
          else $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
      }
      elseif($what==0) {
         $rbeg=0;
         $rend=$conf->mnum+1;
         $table='aa_total';
         $vis='aa_total.visitors';
         $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }

      if($page_id==221) $request='SELECT id,SUM('.$vis.') AS v,SUM('.$table.'.hosts) AS hs,SUM('.$table.'.hits) AS ht, SUM('.$table.'.hits-'.$vis.') AS r FROM '.$table.' WHERE '.$table.'.time>='.$rbeg.' AND '.$table.'.time<'.$rend.' AND id>200 GROUP BY id ORDER BY '.$ordert;
      else $request='SELECT id,SUM('.$vis.') AS v,SUM('.$table.'.hosts) AS hs,SUM('.$table.'.hits) AS ht, SUM('.$table.'.hits-'.$vis.') AS r FROM '.$table.' WHERE '.$table.'.time>='.$rbeg.' AND '.$table.'.time<'.$rend.' AND id IN '.$ids.' GROUP BY id ORDER BY '.$ordert;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

      while($row=mysql_fetch_object($result)) {
          if($row->id) {
            $resmainv[$row->id]=$row->v;
            $resmainhs[$row->id]=$row->hs;
            $resmainht[$row->id]=$row->ht;
            $resmainr[$row->id]=$row->r;
          }
      }
      mysql_free_result($result);
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  reset($resmainv);
  while($k=key($resmainv)) {
      if($k==$page_id) { next($resmainv); continue; }
      $vmint=min($vmint,$resmainv[$k]);
      $hsmint=min($hsmint,$resmainhs[$k]);
      $htmint=min($htmint,$resmainht[$k]);
      $rmint=min($rmint,$resmainr[$k]);
      $vmaxt=max($vmaxt,$resmainv[$k]);
      $hsmaxt=max($hsmaxt,$resmainhs[$k]);
      $htmaxt=max($htmaxt,$resmainht[$k]);
      $rmaxt=max($rmaxt,$resmainr[$k]);
      $vsumt+=$resmainv[$k];
      $hssumt+=$resmainhs[$k];
      $htsumt+=$resmainht[$k];
      $rsumt+=$resmainr[$k];
      next($resmainv);
  }
  reset($gpnames);
  while($k=key($gpnames)) {
      if(!isset($resmainv[$k])) {
          $resmainv[$k]=0;
          $resmainhs[$k]=0;
          $resmainht[$k]=0;
          $resmainr[$k]=0;
          $vmint=0;                 //if exists page which do not have visitings
          $hsmint=0;
          $htmint=0;
          $rmint=0;
      }
      $nrect++;
      next($gpnames);
  }
  if(isset($gpnames[$page_id])) $nrect--;
  if($nrect) {
      $vavgt=sprintf("%.0f",$vsumt/$nrect);
      $hsavgt=sprintf("%.0f",$hssumt/$nrect);
      $htavgt=sprintf("%.0f",$htsumt/$nrect);
      $ravgt=sprintf("%.0f",$rsumt/$nrect);
  }
  if(isset($resmainv[$page_id])) {
      $vsumt=$resmainv[$page_id];
      $hssumt=$resmainhs[$page_id];
      $htsumt=$resmainht[$page_id];
      $rsumt=$resmainr[$page_id];
  }
  if(isset($resmainv['201']) && $page_id==221) {
      $vsumt=$resmainv['201'];
      $hssumt=$resmainhs['201'];
      $htsumt=$resmainht['201'];
      $rsumt=$resmainr['201'];
  }

  $maxlen=175;                                // length of column for graphic view
  $vmax=0;                               //max values
  $hsmax=0;
  $htmax=0;
  $rmax=0;
  if($emptyid==0) {
      $vmin=100000;                               //min values
      $hsmin=100000;
      $htmin=100000;
      $rmin=100000;
  }
  else {
      $vmin=0;                               //min values
      $hsmin=0;
      $htmin=0;
      $rmin=0;
      $vmint=0;
      $hsmint=0;
      $htmint=0;
      $rmint=0;
  }

  if(!$nrect) $nrect=1;

  $vs=0;                //page sum
  $hss=0;
  $hts=0;
  $rs=0;
  if($numstr>$nrect) $numstr=$nrect;               //nrect - the number of total pages in group/groups
  $nrec=$nrect-$begstr;                            //nrec - the number of frames pages in group/groups
  if($nrec>$numstr) $nrec=$numstr;

  $atmp=array();
  ksort($gpnames);
  if($sort['column']==1) { array_multisort($gpnames,SORT_ASC); }
  elseif(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==2)) { ksort($resmainv);  $atmp = array_merge($atmp,$resmainv); array_multisort($atmp,SORT_DESC,$gpnames,SORT_ASC); }
  elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==2)) { ksort($resmainhs); $atmp = array_merge($atmp,$resmainhs); array_multisort($atmp,SORT_DESC,$gpnames,SORT_ASC); }
  elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==2)) { ksort($resmainr); $atmp = array_merge($atmp,$resmainr); array_multisort($atmp,SORT_DESC,$gpnames,SORT_ASC); }
  elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==2)) { ksort($resmainht); $atmp = array_merge($atmp,$resmainht); array_multisort($atmp,SORT_DESC,$gpnames,SORT_ASC); }
  if(count($gpnames)==1) {
      reset($gpnames);
      $k=key($gpnames);
      $v=$gpnames[$k];
      $gpnames=array();
      $gpnames[0]=$v;
  }

  $vars['LISTLEN']=$nrect;
  $vars['REF']='summary';
  $vars['STAB']=1;
  if($page_id==221) {
      $vars['HEADER']=_SUMMARY.' / ';
      $vars['SHOWING']=_SHOWING.' '.$nrec.' '._ITEM_S;
      $vars['GRPG']=_GROUP;
      $vars['RANGE']=($begstr+1).' - '.($begstr+$nrec).' '._OUTOF.' '.$nrect;
      $vars['FPG']=_FORALLGRS;
  }
  else {
      $vars['HEADER']=_SUMMARY.' / ';
      $vars['GRPG']=_PAGE;
      if(count($gpnames)!=0 && $emptyid==0) {
          $vars['RANGE']=($begstr+1).' - '.($begstr+$nrec).' '._OUTOF.' '.$nrect;
          $vars['SHOWING']=_SHOWING.' '.$nrec.' '._ITEM_S;
      }
      else {
          $vars['LISTLEN']=0;
          $vars['RANGE']='0 - 0 '._OUTOF.' 0';
          $vars['SHOWING']=_SHOWING.' 0 '._ITEM_S;
      }
      $vars['FPG']=_FORGR.' \'<strong><i>'.$name.'</i></strong>\'';
  }
  $vars['RHEADER']=_VISGRPG;
  if($what==1) $vars['THEADER']=_MONTH.$dateint;
  elseif($what==2) $vars['THEADER']=_WEEK.$dateint;
  elseif($what==3) $vars['THEADER']=_YESTERDAY.$dateint;
  elseif($what==4) $vars['THEADER']=_TODAY.$dateint;
  elseif($what==5) $vars['THEADER']=_YEAR.$dateint;
  elseif($what==6) $vars['THEADER']=_LASTWEEK.$dateint;
  elseif($what==7) $vars['THEADER']=_LASTMONTH.$dateint;
  elseif($what==0) $vars['THEADER']=_ALLTIME.$dateint;

  if(!$what==5) $vars['INTERVAL']=$tint.'_'.$year;
  else  $vars['INTERVAL']=$tint;
  $vars['VISITORS']=_VISITORS;
  $vars['HOSTS']=_HOSTS;
  $vars['RELOADS']=_RELOADS;
  $vars['HITS']=_HITS;
  $vars['DETAIL']=_DETAILED;
  $vars['SORTBYN']=_SORTBYN;
  $vars['SORTBYV']=_SORTBYV;
  $vars['SORTBYHT']=_SORTBYHT;
  $vars['SORTBYHS']=_SORTBYHS;
  $vars['SORTBYR']=_SORTBYR;
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
  $rdid=$conf->ctime;
  $rdid=substr($rdid,2);
  srand((double)microtime() * 1000000);
  $rv=rand(1,42);
  $rdid=$rv.$rdid;
  $vars['PICTID']=$rdid;
  $rdnum=1;
  $request='DELETE FROM aa_rdata WHERE added<'.($conf->ctime-$conf->mrrdata*3600);
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',0,"'.($page_id.'|'.$vars['THEADER']).'","",'.$num.',0,0,0,0,0,0,0,0,0,0,0)';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $vars['MODULE']='graph';
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  $i=0;
  $power=count($gpnames);
  if($power!=0 && $emptyid==0) {
      for($j=0;$j<$power;$j++) {
            $k=(int)(substr($gpnames[$j],-3));
            if($k==$page_id) continue;
            $i++;
            if($i<$num) continue;
            if($i>$begstr+$numstr) break;
            $vars['NUM']=$num++;
            $vars['PGID']=$k;
            $fname=substr($gpnames[$j],0,-3);
            if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
            else $sname=$fname;
            $vars['GRPG']=$fname;
            $vars['GRPGSHORT']=$sname;
            $rd='';
            if($page_id!=221) { $vars['PGURL']=$urls[$k]; $rd=$urls[$k]; }
            $vars['VISITORS']=$resmainv[$k];
            $vars['HOSTS']=$resmainhs[$k];
            $vars['HITS']=$resmainht[$k];
            $vars['RELOADS']=$resmainr[$k];
            if($page_id==221) tparse($centerg,$vars);
            else tparse($centerp,$vars);
            //frames parameters
            $vmax=max($vmax,$resmainv[$k]);
            $hsmax=max($hsmax,$resmainhs[$k]);
            $htmax=max($htmax,$resmainht[$k]);
            $rmax=max($rmax,$resmainr[$k]);

            $minf=1;
            $vmin=min($vmin,$resmainv[$k]);
            $hsmin=min($hsmin,$resmainhs[$k]);
            $htmin=min($htmin,$resmainht[$k]);
            $rmin=min($rmin,$resmainr[$k]);
            $vs+=$resmainv[$k];
            $hss+=$resmainhs[$k];
            $hts+=$resmainht[$k];
            $rs+=$resmainr[$k];

            $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.','.$rdnum.',"'.$vars['GRPG'].'","'.$rd.'",0,'.($vsumt?sprintf("%.2f",$vars['VISITORS']/$vsumt*100):'0.00').','.$vars['VISITORS'].',0,'.($hssumt?sprintf("%.2f",$vars['HOSTS']/$hssumt*100):'0.00').','.$vars['HOSTS'].',0,'.($rsumt?sprintf("%.2f",$vars['RELOADS']/$rsumt*100):'0.00').','.$vars['RELOADS'].',0,'.($htsumt?sprintf("%.2f",$vars['HITS']/$htsumt*100):'0.00').','.$vars['HITS'].')';
            $result1=mysql_query($request,$conf->link);
            if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
            $rdnum++;
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }

  if(!$minf) {
      $vmin=0;                               //min values
      $hsmin=0;
      $htmin=0;
      $rmin=0;
  }

  if(!$nrec) $nrec=1;                          //to calculate frames average (in order to not check dividion by zero)
  if($numstr<$nrect) {
        $vars['VISITORS']='-';
        $vars['HOSTS']='-';
        $vars['HITS']='-';
        $vars['RELOADS']='-';
        tparse($delimiter,$vars);

        $vars['NAME']=_MINIMUM;
        $vars['VISITORS']=$vmin;
        $vars['HOSTS']=$hsmin;
        $vars['HITS']=$htmin;
        $vars['RELOADS']=$rmin;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $av=sprintf("%.0f",$vs/$nrec);
        $vars['VISITORS']=$av;
        $av=sprintf("%.0f",$hss/$nrec);
        $vars['HOSTS']=$av;
        $av=sprintf("%.0f",$hts/$nrec);
        $vars['HITS']=$av;
        $av=sprintf("%.0f",$rs/$nrec);
        $vars['RELOADS']=$av;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['VISITORS']=$vmax;
        $vars['HOSTS']=$hsmax;
        $vars['HITS']=$htmax;
        $vars['RELOADS']=$rmax;
        tparse($foot,$vars);
  }
  if($emptyid==0) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['VISITORS']=$vsumt;
  $vars['HOSTS']=$hssumt;
  $vars['HITS']=$htsumt;
  $vars['RELOADS']=$rsumt;
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['VISITORS']=$vmint;
  $vars['HOSTS']=$hsmint;
  $vars['HITS']=$htmint;
  $vars['RELOADS']=$rmint;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['VISITORS']=$vavgt;
  $vars['HOSTS']=$hsavgt;
  $vars['HITS']=$htavgt;
  $vars['RELOADS']=$ravgt;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['VISITORS']=$vmaxt;
  $vars['HOSTS']=$hsmaxt;
  $vars['HITS']=$htmaxt;
  $vars['RELOADS']=$rmaxt;
  tparse($foot,$vars);

  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

  require './style/'.$conf->style.'/template/vpg_d.php';

  //VISITORS
  $vars['STAB']=2;
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['HEADER']=_VISITORS.' / ';
  $vars['REF']='visitors';
  $vars['TOTAL']=_VISITORS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYV;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);
  $num=$begstr+1;
  $i=0;
  if($power!=0 && $emptyid==0) {
      for($j=0;$j<$power;$j++) {
            $k=(int)(substr($gpnames[$j],-3));
            if($k==$page_id) continue;
            $i++;
            if($i<$num) continue;
            if($i>$begstr+$numstr) break;
            $vars['NUM']=$num++;
            $fname=substr($gpnames[$j],0,-3);
            if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
            else $sname=$fname;
            $vars['GRPG']=$fname;
            $vars['GRPGSHORT']=$sname;
            if($page_id!=221) $vars['PGURL']=$urls[$k];
            $vars['PGID']=$k;
            $vars['TOTAL']=$resmainv[$k];
            if($vsumt)$vars['PER']=sprintf("%.2f",$resmainv[$k]/$vsumt*100);
            else $vars['PER']='0.00';
            if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$resmainv[$k]/$vmaxt);
            else $vars['GRAPHIC']=0;
            if($page_id==221) tparse($centerg,$vars);
            else tparse($centerp,$vars);
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }


  if($numstr<$nrect) {
        $vars['TOTAL']='-';
        $vars['PER']='-';
        tparse($delimiter,$vars);

        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$vmin;
        if($vsumt)$vars['PER']=sprintf("%.2f",$vmin/$vsumt*100);
        else $vars['PER']='0.00';
        if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$vmin/$vmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $av=sprintf("%.0f",$vs/$nrec);
        $vars['TOTAL']=$av;
        if($vsumt)$vars['PER']=sprintf("%.2f",$av/$vsumt*100);
        else $vars['PER']='0.00';
        if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$av/$vmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$vmax;
        if($vsumt)$vars['PER']=sprintf("%.2f",$vmax/$vsumt*100);
        else $vars['PER']='0.00';
        if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$vmax/$vmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
  }
  if($emptyid==0) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$vsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$vmint;
  if($vsumt)$vars['PER']=sprintf("%.2f",$vmint/$vsumt*100);
  else $vars['PER']='0.00';
  if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$vmint/$vmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$vavgt;
  if($vsumt)$vars['PER']=sprintf("%.2f",$vavgt/$vsumt*100);
  else $vars['PER']='0.00';
  if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$vavgt/$vmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$vmaxt;
  if($vsumt)$vars['PER']=sprintf("%.2f",$vmaxt/$vsumt*100);
  else $vars['PER']='0.00';
  if($vmaxt) $vars['GRAPHIC']=(int)($maxlen*$vmaxt/$vmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HOSTS
  $vars['STAB']=3;
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['HEADER']=_HOSTS.' / ';
  $vars['REF']='hosts';
  $vars['TOTAL']=_HOSTS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYHS;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);
  $num=$begstr+1;
  $i=0;
  if($power!=0 && $emptyid==0) {
      for($j=0;$j<$power;$j++) {
            $k=(int)(substr($gpnames[$j],-3));
            if($k==$page_id) continue;
            $i++;
            if($i<$num) continue;
            if($i>$begstr+$numstr) break;
            $vars['NUM']=$num++;
            $fname=substr($gpnames[$j],0,-3);
            if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
            else $sname=$fname;
            $vars['GRPG']=$fname;
            $vars['GRPGSHORT']=$sname;
            if($page_id!=221) $vars['PGURL']=$urls[$k];
            $vars['PGID']=$k;
            $vars['TOTAL']=$resmainhs[$k];
            if($hssumt)$vars['PER']=sprintf("%.2f",$resmainhs[$k]/$hssumt*100);
            else $vars['PER']='0.00';
            if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$resmainhs[$k]/$hsmaxt);
            else $vars['GRAPHIC']=0;
            if($page_id==221) tparse($centerg,$vars);
            else tparse($centerp,$vars);
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }

  if($numstr<$nrect) {
        $vars['TOTAL']='-';
        $vars['PER']='-';
        tparse($delimiter,$vars);

        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$hsmin;
        if($hssumt)$vars['PER']=sprintf("%.2f",$hsmin/$hssumt*100);
        else $vars['PER']='0.00';
        if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$hsmin/$hsmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $av=sprintf("%.0f",$hss/$nrec);
        $vars['TOTAL']=$av;
        if($hssumt)$vars['PER']=sprintf("%.2f",$av/$hssumt*100);
        else $vars['PER']='0.00';
        if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$av/$hsmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$hsmax;
        if($hssumt)$vars['PER']=sprintf("%.2f",$hsmax/$hssumt*100);
        else $vars['PER']='0.00';
        if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$hsmax/$hsmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
  }
  if($emptyid==0) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$hssumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$hsmint;
  if($hssumt)$vars['PER']=sprintf("%.2f",$hsmint/$hssumt*100);
  else $vars['PER']='0.00';
  if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$hsmint/$hsmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$hsavgt;
  if($hssumt)$vars['PER']=sprintf("%.2f",$hsavgt/$hssumt*100);
  else $vars['PER']='0.00';
  if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$hsavgt/$hsmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$hsmaxt;
  if($hssumt)$vars['PER']=sprintf("%.2f",$hsmaxt/$hssumt*100);
  else $vars['PER']='0.00';
  if($hsmaxt) $vars['GRAPHIC']=(int)($maxlen*$hsmaxt/$hsmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //RELOADS
  $vars['STAB']=4;
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['HEADER']=_RELOADS.' / ';
  $vars['REF']='reloads';
  $vars['TOTAL']=_RELOADS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYR;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);
  $num=$begstr+1;
  $i=0;
  if($power!=0 && $emptyid==0) {
      for($j=0;$j<$power;$j++) {
            $k=(int)(substr($gpnames[$j],-3));
            if($k==$page_id) continue;
            $i++;
            if($i<$num) continue;
            if($i>$begstr+$numstr) break;
            $vars['NUM']=$num++;
            $fname=substr($gpnames[$j],0,-3);
            if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
            else $sname=$fname;
            $vars['GRPG']=$fname;
            $vars['GRPGSHORT']=$sname;
            $vars['PGID']=$k;
            if($page_id!=221) $vars['PGURL']=$urls[$k];
            $vars['TOTAL']=$resmainr[$k];
            if($rsumt)$vars['PER']=sprintf("%.2f",$resmainr[$k]/$rsumt*100);
            else $vars['PER']='0.00';
            if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$resmainr[$k]/$rmaxt);
            else $vars['GRAPHIC']=0;
            if($page_id==221) tparse($centerg,$vars);
            else tparse($centerp,$vars);
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }

  if($numstr<$nrect) {
        $vars['TOTAL']='-';
        $vars['PER']='-';
        tparse($delimiter,$vars);

        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$rmin;
        if($rsumt)$vars['PER']=sprintf("%.2f",$rmin/$rsumt*100);
        else $vars['PER']='0.00';
        if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$rmin/$rmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $av=sprintf("%.0f",$rs/$nrec);
        $vars['TOTAL']=$av;
        if($rsumt)$vars['PER']=sprintf("%.2f",$av/$rsumt*100);
        else $vars['PER']='0.00';
        if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$av/$rmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$rmax;
        if($rsumt)$vars['PER']=sprintf("%.2f",$rmax/$rsumt*100);
        else $vars['PER']='0.00';
        if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$rmax/$rmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
  }
  if($emptyid==0) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$rsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$rmint;
  if($rsumt)$vars['PER']=sprintf("%.2f",$rmint/$rsumt*100);
  else $vars['PER']='0.00';
  if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$rmint/$rmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$ravgt;
  if($rsumt)$vars['PER']=sprintf("%.2f",$ravgt/$rsumt*100);
  else $vars['PER']='0.00';
  if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$ravgt/$rmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$rmaxt;
  if($rsumt)$vars['PER']=sprintf("%.2f",$rmaxt/$rsumt*100);
  else $vars['PER']='0.00';
  if($rmaxt) $vars['GRAPHIC']=(int)($maxlen*$rmaxt/$rmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HITS
  $vars['STAB']=5;
  if($page_id==221) $vars['GRPG']=_GROUP;
  else $vars['GRPG']=_PAGE;
  $vars['HEADER']=_HITS.' / ';
  $vars['REF']='hits';
  $vars['TOTAL']=_HITS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYHT;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);
  $num=$begstr+1;
  $i=0;
  if($power!=0 && $emptyid==0) {
      for($j=0;$j<$power;$j++) {
            $k=(int)(substr($gpnames[$j],-3));
            if($k==$page_id) continue;
            $i++;
            if($i<$num) continue;
            if($i>$begstr+$numstr) break;
            $vars['NUM']=$num++;
            $fname=substr($gpnames[$j],0,-3);
            if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
            else $sname=$fname;
            $vars['GRPG']=$fname;
            $vars['GRPGSHORT']=$sname;
            $vars['PGID']=$k;
            if($page_id!=221) $vars['PGURL']=$urls[$k];
            $vars['TOTAL']=$resmainht[$k];
            if($htsumt)$vars['PER']=sprintf("%.2f",$resmainht[$k]/$htsumt*100);
            else $vars['PER']='0.00';
            if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$resmainht[$k]/$htmaxt);
            else $vars['GRAPHIC']=0;
            if($page_id==221) tparse($centerg,$vars);
            else tparse($centerp,$vars);
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }

  if($numstr<$nrect) {
        $vars['TOTAL']='-';
        $vars['PER']='-';
        tparse($delimiter,$vars);

        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$htmin;
        if($htsumt)$vars['PER']=sprintf("%.2f",$htmin/$htsumt*100);
        else $vars['PER']='0.00';
        if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$htmin/$htmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $av=sprintf("%.0f",$hts/$nrec);
        $vars['TOTAL']=$av;
        if($htsumt)$vars['PER']=sprintf("%.2f",$av/$htsumt*100);
        else $vars['PER']='0.00';
        if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$av/$htmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$htmax;
        if($htsumt)$vars['PER']=sprintf("%.2f",$htmax/$htsumt*100);
        else $vars['PER']='0.00';
        if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$htmax/$htmaxt);
        else $vars['GRAPHIC']=0;
        tparse($foot,$vars);
  }
  if($emptyid==0) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$htsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$htmint;
  if($htsumt)$vars['PER']=sprintf("%.2f",$htmint/$htsumt*100);
  else $vars['PER']='0.00';
  if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$htmint/$htmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$htavgt;
  if($htsumt)$vars['PER']=sprintf("%.2f",$htavgt/$htsumt*100);
  else $vars['PER']='0.00';
  if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$htavgt/$htmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$htmaxt;
  if($htsumt)$vars['PER']=sprintf("%.2f",$htmaxt/$htsumt*100);
  else $vars['PER']='0.00';
  if($htmaxt) $vars['GRAPHIC']=(int)($maxlen*$htmaxt/$htmaxt);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);
  //total
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',252,"tot","",0,'.$nrect.','.$vsumt.',0,100.00,'.$hssumt.',0,100.00,'.$rsumt.',0,100.00,'.$htsumt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //minimum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',253,"min","",0,'.($vsumt?sprintf("%.2f",$vmint/$vsumt*100):'0.00').','.$vmint.',0,'.($hssumt?sprintf("%.2f",$hsmint/$hssumt*100):'0.00').','.$hsmint.',0,'.($rsumt?sprintf("%.2f",$rmint/$rsumt*100):'0.00').','.$rmint.',0,'.($htsumt?sprintf("%.2f",$htmint/$htsumt*100):'0.00').','.$htmint.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',254,"avg","",0,'.($vsumt?sprintf("%.2f",$vavgt/$vsumt*100):'0.00').','.$vavgt.',0,'.($hssumt?sprintf("%.2f",$hsavgt/$hssumt*100):'0.00').','.$hsavgt.',0,'.($rsumt?sprintf("%.2f",$ravgt/$rsumt*100):'0.00').','.$ravgt.',0,'.($htsumt?sprintf("%.2f",$htavgt/$htsumt*100):'0.00').','.$htavgt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',255,"max","",0,'.($vsumt?sprintf("%.2f",$vmaxt/$vsumt*100):'0.00').','.$vmaxt.',0,'.($hssumt?sprintf("%.2f",$hsmaxt/$hssumt*100):'0.00').','.$hsmaxt.',0,'.($rsumt?sprintf("%.2f",$rmaxt/$rsumt*100):'0.00').','.$rmaxt.',0,'.($htsumt?sprintf("%.2f",$htmaxt/$htsumt*100):'0.00').','.$htmaxt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

?>
