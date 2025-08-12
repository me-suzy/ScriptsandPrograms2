<?php

  $maxlen=170;        // length of column for graphic view
  if($page_id==221) $page_id=201;
  $lastrec=1000000;
  //begin & end time of selecting of records
  if(!strcmp($tint,'today')) {
      $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;     //number of begin hour today
      $rend=$conf->hnum+1;                                    //current hour+1
      $rprev=$rbeg-24;
      $what=21;
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime).') ';
      $header=_TODAY;
  }
  elseif(!strcmp($tint,'yesterday')) {
      $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;  //number of begin hour of yesterday
      $rend=$rbeg+24;                                         //number of begin hour of today
      $rprev=$rbeg-24;
      $what=26;
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->dtime-40000).') ';
      $header=_YESTERDAY;
  }
  elseif(!strcmp($tint,'week')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
      $rend=$conf->dnum+1;
      if($rbeg<0) {
          $rbeg=0;
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }
      else {
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }
      $what=31;
      $header=_WEEK;
  }
  elseif(!strcmp($tint,'lastweek')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
      $rend=$rbeg+7;
      if($rbeg<0) {
          $rbeg=0;
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-40000).') ';
      }
      else {
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-40000).') ';
      }
      $what=31;
      $header=_LASTWEEK;

  }
  elseif(!strcmp($tint,'month')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
      $rend=$conf->dnum+1;
      if($rbeg<0) {
          $rbeg=0;
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }
      else {
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
      }
      $rprev=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
      $what=36;
      $header=_MONTH;
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
      $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
      $rprev=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lpmtime)/$conf->time1);
      $what=36;
      if($rbeg<0) {
          $rbeg=0;
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-40000).') ';
      }
      else {
          $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-40000).') ';
      }
      $header=_LASTMONTH;
  }
  elseif(!strcmp($tint,'total')||!strcmp($tint,'all')) {
      $byear=date('Y',$conf->btime);
      $bmonth=date('m',$conf->btime);
      $eyear=date('Y',$conf->ctime);
      $bs=0;
      $em=$conf->mnum;
      $what=41;
      if($byear==$eyear) $dateint=' ('.$byear.') ';
      else $dateint=' ('.$byear.' - '.$eyear.') ';
      $header=_TOTAL;
  }
  elseif(!strcmp($tint,'totalm')) {
      //begin month of year
      $byear=date('Y',$conf->btime);
      $bmonth=date('m',$conf->btime);
      $em=($year-$byear)*12+(12-$bmonth); //end month for select
      if($year==$byear) { $bd=(int)(date('m',$conf->btime))-1; $bm=0; $bs=0; }
      else { $bm=$em-11; $bs=$bm-1; $bd=0; }        //-1 for calculate increase
      if($em>$conf->mnum) $em=$conf->mnum;
      if($em>=($conf->mnum-1))  $lastrec=($em-$bm);
      if($em==($conf->mnum-1))  $lastrec++;
      $rprev=$bm-12;
      $what=46;
      $dateint=' ('.$year.') ';
      $header=_YEAR;
  }
  require './style/'.$conf->style.'/template/vti_ti_a.php';

  $request='LOCK TABLES aa_hours READ, aa_days READ, aa_total READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //select from aa_hours
  if($what==21 || $what==26) {
      $request='SELECT time AS time,visitors AS v,hosts AS hs,hits AS ht,id FROM aa_hours WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //calculate previous values
      $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rprev.' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  elseif($what==31 || $what==36) {
      //select from aa_days
      if($what==31) $request='SELECT time AS time,visitors_w AS v,hosts AS hs,hits AS ht,id FROM aa_days WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      else $request='SELECT time AS time,visitors_m AS v,hosts AS hs,hits AS ht,id FROM aa_days WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //calculate previous values
      if($what==31) $request='SELECT SUM(visitors_w) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.($rbeg-7).' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      else $request='SELECT SUM(visitors_m) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rprev.' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  elseif($what==41 || $what==46) {
      //select from aa_total
      $request='SELECT * FROM aa_total WHERE time>='.$bs.' AND time<='.$em.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //select last record from aa_days
      $request='SELECT * FROM aa_days WHERE id='.$page_id.' ORDER BY time DESC LIMIT 1';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}

      if($what==46) {
          $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_total WHERE time>='.$rprev.' AND time<'.$bm.' AND id='.$page_id.' ORDER BY time ASC';
          $resultp=mysql_query($request,$conf->link);
          if(!$resultp) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if($what==21 || $what==26) $maxrec=24;
  elseif($what==31 || $what==36) {
      $maxrec=$rend-$rbeg;
      if(!strcmp($tint,'month')||!strcmp($tint,'week')) $lastrec=$maxrec-1;
  }
  elseif($what==41) $maxrec=$eyear-$byear+1;
  elseif($what==46) $maxrec=($em-$bm)+1;

  $v=array();
  $hs=array();
  $ht=array();
  $r=array();
  $ts=array();
  //matrixs of processing's results
  for($i=0;$i<$maxrec;$i++) {
      $ts[$i]=$i;
      $v[$i]=0;
      $hs[$i]=0;
      $ht[$i]=0;
      $r[$i]=0;
  }//for($i=0;$i<$maxrec;$i++)

  //total result of this period
  $vt=0;
  $hst=0;
  $htt=0;
  $rt=0;
  //result of last record before this period
  $vinc=0;
  $hsinc=0;
  $htinc=0;
  $rinc=0;
  $finc=0;
  //last time before this period for calculate of increase
  if($what==21 || $what==26 || $what==31 || $what==36) $inct=$rbeg-1;
  elseif($what==41) $inct=-1;
  elseif($what==46) $inct=$bm-1;
  //min values for this period
  $vmin=1000000;
  $hsmin=1000000;
  $htmin=1000000;
  $rmin=1000000;
  $minf=0;
  //max values for this period for calculate of length of columns of graphic view
  $vmax=0;
  $hsmax=0;
  $htmax=0;
  $rmax=0;
  //summary for previous period
  $vtp=0;
  $hstp=0;
  $http=0;
  $rtp=0;
  //max increase for this period
  $vmaxp=0;
  $hsmaxp=0;
  $htmaxp=0;
  $rmaxp=0;
  //min increase for this period
  $vminp=1000000;
  $hsminp=1000000;
  $htminp=1000000;
  $rminp=1000000;

  if(mysql_num_rows($result)) {
      while($row=mysql_fetch_object($result)) {
          if($what==21 || $what==26 || $what==31 || $what==36) {
              if($row->time==$inct) {     //result of last record of previous period
                  $vinc=$row->v;
                  $hsinc=$row->hs;
                  $htinc=$row->ht;
                  $rinc=($htinc-$vinc);
                  $finc=1;
              }//if($row->time==$inct)
              else {
                  $i=$row->time-$rbeg;
                  $vt+=$row->v;
                  $hst+=$row->hs;
                  $htt+=$row->ht;
                  $rt=($htt-$vt);
                  $v[$i]+=$row->v;
                  $hs[$i]+=$row->hs;
                  $ht[$i]+=$row->ht;
                  $r[$i]=($ht[$i]-$v[$i]);
              }
          }//if($what==21 || $what==26 || $what==31 || $what==36)
          elseif($what==41) {
              $i=(int)(($row->time+$bmonth-1)/12);
              $v[$i]+=$row->visitors;
              $hs[$i]+=$row->hosts;
              $ht[$i]+=$row->hits;
              $r[$i]+=($ht[$i]-$v[$i]);
              $vt+=$row->visitors;
              $hst+=$row->hosts;
              $htt+=$row->hits;
              $rt=($htt-$vt);
          }//elseif($what==41)
          elseif($what==46) {
              if($row->time==$inct) {
                  $vinc=$row->visitors;
                  $hsinc=$row->hosts;
                  $htinc=$row->hits;
                  $rinc=($htinc-$vinc);
                  $finc=1;
              }
              if($row->time>=$bm) {
                  $i=$row->time-$bm;//+($bmonth-1);
                  $v[$i]+=$row->visitors;
                  $hs[$i]+=$row->hosts;
                  $ht[$i]+=$row->hits;
                  $r[$i]=($ht[$i]-$v[$i]);
                  $vt+=$row->visitors;
                  $hst+=$row->hosts;
                  $htt+=$row->hits;
                  $rt=($htt-$vt);
              }
          }//elseif($what==46)
      }//while($row=mysql_fetch_object($result))
  }//if(mysql_num_rows($result))
  if(!$finc) {
      $vinc=0;
      $hsinc=0;
      $htinc=0;
      $rinc=0;
  }

  if($what==21 || $what==26 || $what==31 || $what==36) {
      if(mysql_num_rows($resultd)) {
          $row=mysql_fetch_object($resultd);
          if($row->nrec) {
              $vtp=$row->v;
              $hstp=$row->hs;
              $http=$row->ht;
              $rtp=$http-$vtp;
          }
      }
      mysql_free_result($resultd);
  }//($what==21 || $what==26 || $what==31 || $what==36)
  elseif($what==41 || $what==46) {
      if(mysql_num_rows($resultd)) {
          $row=mysql_fetch_object($resultd);
          if($what==41) $i=date('Y',$row->time*$conf->time1+$conf->btime)-$byear;
          elseif($what==46) {
              $lyear=date('Y',$row->time*$conf->time1+$conf->btime);
              $lmonth=date('m',$row->time*$conf->time1+$conf->btime);
//              $i=(int)(date('m',$row->time*$conf->time1+$conf->btime)-1);
              $db=getdate($conf->btime);
              $dc=getdate($row->time*$conf->time1+$conf->btime);
              $mnumtmp=($dc['year']-$db['year'])*12+$dc['mon']-$db['mon'];
              $i=$mnumtmp-$bm;
          }//elseif($what==46)
          if($what==41 || ($what==46 && $lyear==$year)) {
              $v[$i]+=$row->visitors_t;
              $hs[$i]+=$row->hosts;
              $ht[$i]+=$row->hits;
              $r[$i]=($ht[$i]-$v[$i]);
              $vt+=$row->visitors_t;
              $hst+=$row->hosts;
              $htt+=$row->hits;
              $rt=($htt-$vt);
          }//if($what==41 || ($what==46 && $lyear==$year))
          if($what==46) {
              if((($year-$lyear)==1)&&($lmonth==12)) {
                  $vinc+=$row->visitors_t;
                  $hsinc+=$row->hosts;
                  $htinc+=$row->hits;
                  $rinc=($htinc-$vinc);
              }//if((($year-$lyear)==1)&&($lmonth==12))
          }//if($what==46)
          mysql_free_result($resultd);
      }//if(mysql_num_rows($resultd))
      if($what==46) {
          if(mysql_num_rows($resultp)) {
              $row=mysql_fetch_object($resultp);
              if($row->nrec) {
                  $vtp=$row->v;
                  $hstp=$row->hs;
                  $http=$row->ht;
                  $rtp=$http-$vtp;
              }
          }
          mysql_free_result($resultp);
      }//$what==46)
  }//if($what==41 || $what==46)

  $fname=$name;
  if(strlen($fname)>_VS_PGSTITLINT) $sname=substr($fname,0,_VS_PGSTITLINT-3).'...';
  else $sname=$fname;
  if(count($v)) $vars['SHOWING']=_SHOWING.' '.$maxrec.' '._INTERVAL_S;
  else $vars['SHOWING']=_SHOWING.' 0 '._INTERVAL_S;
  if($page_id==221) $vars['FPG']=_FORALLGRS;
  elseif($page_id>200) $vars['FPG']=_FORGR." '<b><i>".$name."</i></b>'";
  else $vars['FPG']=_FORPG.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
  // input total table
  $vars['TINAME']=_TIMEINT;
  $vars['VISITORS']=_VISITORS;
  $vars['HOSTS']=_HOSTS;
  $vars['RELOADS']=_RELOADS;
  $vars['HITS']=_HITS;
  $vars['REF']='summary';
  $vars['STAB']=1;
  $vars['HEADER']=_SUMMARY.' / ';
  $vars['THEADER']=$header.$dateint;
  $vars['RHEADER']=_VISINT;
  $vars['DETAIL']=_DETAILED;
  $vars['SORTBYN']=_SORTBYT;
  $vars['SORTBYV']=_SORTBYV;
  $vars['SORTBYHT']=_SORTBYHT;
  $vars['SORTBYHS']=_SORTBYHS;
  $vars['SORTBYR']=_SORTBYR;
  $vars['SORTBYI']=_SORTBYI;
  $vars['LBEG']=_STARTOFLIST;
  $vars['LLSCR']=_PREVPG;
  $vars['LRSCR']=_NEXTPG;
  $vars['LEND']=_ENDOFLIST;
  $vars['BACKTT']=_BACKTOTOP;
  tparse($top,$vars);

  $rdid=$conf->ctime;
  $rdid=substr($rdid,2);
  srand((double)microtime() * 1000000);
  $rv=rand(1,42);
  $rdid=$rv.$rdid;
  $vars['PICTID']=$rdid;
  $rdnum=1;
  $request='DELETE FROM aa_rdata WHERE added<'.($conf->ctime-$conf->mrrdata*3600);
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',0,"'.($page_id.'|'.$vars['THEADER']).'","",1,0,0,0,0,0,0,0,0,0,0,0)';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $vars['MODULE']='graph';
  $vars['TITLE']=_GRAPH;
  $vars['ELEM']='graph';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  if(count($v)) {
      $vi[0]=$v[0]-$vinc;
      for($j=1;$j<$maxrec;$j++) $vi[$j]=$v[$j]-$v[$j-1];
      $hsi[0]=$hs[0]-$hsinc;
      for($j=1;$j<$maxrec;$j++) $hsi[$j]=$hs[$j]-$hs[$j-1];
      $ri[0]=$r[0]-$rinc;
      for($j=1;$j<$maxrec;$j++) $ri[$j]=$r[$j]-$r[$j-1];
      $hti[0]=$ht[0]-$htinc;
      for($j=1;$j<$maxrec;$j++) $hti[$j]=$ht[$j]-$ht[$j-1];
      $atmp=array();
      if(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==3)) { $atmp = array_merge($atmp,$v); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==3)) { $atmp = array_merge($atmp,$hs); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==3)) { $atmp = array_merge($atmp,$r); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==3)) { $atmp = array_merge($atmp,$ht); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==2&&$sort['column']==2)) { $atmp = array_merge($atmp,$vi); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==3&&$sort['column']==2)) { $atmp = array_merge($atmp,$hsi); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==4&&$sort['column']==2)) { $atmp = array_merge($atmp,$ri); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      elseif(($sort['table']==5&&$sort['column']==2)) { $atmp = array_merge($atmp,$hti); array_multisort($atmp,SORT_DESC,$ts,SORT_ASC);}
      for($j=0;$j<$maxrec;$j++) {
          $i=$ts[$j];
          if($what==21 || $what==26) $vars['PERIOD']=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          elseif($what==31 || $what==36) $vars['PERIOD']=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          elseif($what==41) $vars['PERIOD']=$byear+$i;
          elseif($what==46) $vars['PERIOD']=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $vars['VISITORS']=$v[$i];
          $vars['HOSTS']=$hs[$i];
          $vars['HITS']=$ht[$i];
          $vars['RELOADS']=$r[$i];
          if($what==41||$i>=$lastrec-1) {
              if($what==46) {
                  if($i==$lastrec-1) $vars['INTERVAL']='lastmonth';
                  elseif($i==$lastrec) $vars['INTERVAL']='month';
              }
              elseif($what==36||$what==31) {
                  if($i==$lastrec-1) $vars['INTERVAL']='yesterday';
                  elseif($i==$lastrec) $vars['INTERVAL']='today';
              }
              else $vars['INTERVAL']='totalm';
              tparse($icenter,$vars);
          }
          else tparse($center,$vars);
          $vmax=max($vmax,$v[$i]);
          $hsmax=max($hsmax,$hs[$i]);
          $htmax=max($htmax,$ht[$i]);
          $rmax=max($rmax,$r[$i]);
          $vmin=min($vmin,$v[$i]);
          $hsmin=min($hsmin,$hs[$i]);
          $htmin=min($htmin,$ht[$i]);
          $rmin=min($rmin,$r[$i]);
          $minf=1;

          $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.','.$rdnum.',"'.$vars['PERIOD'].'","",'.$vi[$i].','.($vt?sprintf("%.2f",$v[$i]/$vt*100.00):'0.00').','.$v[$i].','.$hsi[$i].','.($hst?sprintf("%.2f",$hs[$i]/$hst*100.00):'0.00').','.$hs[$i].','.$ri[$i].','.($rt?sprintf("%.2f",$r[$i]/$rt*100.00):'0.00').','.$r[$i].','.$hti[$i].','.($htt?sprintf("%.2f",$ht[$i]/$htt*100.00):'0.00').','.$ht[$i].')';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $rdnum++;
      }//for($i=0;$i<23;$i++)
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }

  if(!$minf) { $vmin=0; $hsmin=0; $htmin=0; $rmin=0; }
  $vars['PERIOD']=_SUMMARY.$dateint;
  $vars['VISITORS']=$vt;
  $vars['HOSTS']=$hst;
  $vars['HITS']=$htt;
  $vars['RELOADS']=$rt;
  tparse($delimiter,$vars);
  if(!$maxrec) $maxrec=1;
  $vars['NAME']=_MINIMUM;
  $vars['VISITORS']=$vmin;
  $vars['HOSTS']=$hsmin;
  $vars['HITS']=$htmin;
  $vars['RELOADS']=$rmin;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['VISITORS']=sprintf("%.0f",$vt/$maxrec);
  $vars['HOSTS']=sprintf("%.0f",$hst/$maxrec);
  $vars['HITS']=sprintf("%.0f",$htt/$maxrec);
  $vars['RELOADS']=sprintf("%.0f",$rt/$maxrec);
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['VISITORS']=$vmax;
  $vars['HOSTS']=$hsmax;
  $vars['HITS']=$htmax;
  $vars['RELOADS']=$rmax;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  require './style/'.$conf->style.'/template/vti_ti_d.php';

  //VISITORS
  $vars['STAB']=2;
  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_VISITORS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['HEADER']=_VISITORS.' / ';
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYV;
  $vars['REF']='visitors';
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_GRAPH;
  $vars['ELEM']='graph';
  tparse($button,$vars);
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);
  $suminc=0;
  $minf=0;
  if(count($v)) {
      for($j=0;$j<$maxrec;$j++) {
          $i=$ts[$j];
          if($what==21 || $what==26) $vars['PERIOD']=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          elseif($what==31 || $what==36) $vars['PERIOD']=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          elseif($what==41) $vars['PERIOD']=$byear+$i;
          elseif($what==46) $vars['PERIOD']=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $vars['INC']=$vi[$i];
          $vars['TOTAL']=$v[$i];
          if($vmax) $vars['GRAPHIC']=(int)($maxlen*$v[$i]/$vmax);
          else $vars['GRAPHIC']=0;
          if($what==41||$i>=$lastrec-1) {
              if($what==46) {
                  if($i==$lastrec-1) $vars['INTERVAL']='lastmonth';
                  elseif($i==$lastrec) $vars['INTERVAL']='month';
              }
              elseif($what==36||$what==31) {
                  if($i==$lastrec-1) $vars['INTERVAL']='yesterday';
                  elseif($i==$lastrec) $vars['INTERVAL']='today';
              }
              else $vars['INTERVAL']='totalm';
              tparse($icenter,$vars);
          }
          else tparse($center,$vars);
          $vmaxp=max($vmaxp,$vi[$i]);
          $vminp=min($vminp,$vi[$i]);
          $suminc+=$vi[$i];
          $minf=1;
      }//for($i=0;$i<$maxrec;$i++)
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) $vminp=0;

  $vars['PERIOD']=_SUMMARY.$dateint;
  $vars['INC']=$vt-$vtp;
  $vars['TOTAL']=$vt;
  tparse($delimiter,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['INC']=$vminp;
  $vars['TOTAL']=$vmin;
  if($vmax) $vars['GRAPHIC']=(int)($maxlen*$vmin/$vmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  if($maxrec>0) { $avgv=sprintf("%.0f",$vt/$maxrec); $avgpv=sprintf("%.0f",$suminc/$maxrec); if(!strcmp($avgpv,'-0')) $avgpv=0; }
  else { $avgv=0; $avgpv=0; }
  $vars['INC']=$avgpv;
  $vars['TOTAL']=$avgv;
  if($vmax) $vars['GRAPHIC']=(int)($maxlen*$avgv/$vmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['INC']=$vmaxp;
  $vars['TOTAL']=$vmax;
  if($vmax) $vars['GRAPHIC']=(int)($maxlen*$vmax/$vmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HOSTS
  $vars['STAB']=3;
  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_HOSTS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['HEADER']=_HOSTS.' / ';
  $vars['REF']='hosts';
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYHS;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_GRAPH;
  $vars['ELEM']='graph';
  tparse($button,$vars);
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  if(count($v)){
      $suminc=0;
      $minf=0;
      for($j=0;$j<$maxrec;$j++) {
          $i=$ts[$j];
          if($what==21 || $what==26) $vars['PERIOD']=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          elseif($what==31 || $what==36) $vars['PERIOD']=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          elseif($what==41) $vars['PERIOD']=$byear+$i;
          elseif($what==46) $vars['PERIOD']=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $vars['INC']=$hsi[$i];
          $vars['TOTAL']=$hs[$i];
          if($hsmax) $vars['GRAPHIC']=(int)($maxlen*$hs[$i]/$hsmax);
          else $vars['GRAPHIC']=0;
          if($what==41||$i>=$lastrec-1) {
              if($what==46) {
                  if($i==$lastrec-1) $vars['INTERVAL']='lastmonth';
                  elseif($i==$lastrec) $vars['INTERVAL']='month';
              }
              elseif($what==36||$what==31) {
                  if($i==$lastrec-1) $vars['INTERVAL']='yesterday';
                  elseif($i==$lastrec) $vars['INTERVAL']='today';
              }
              else $vars['INTERVAL']='totalm';
              tparse($icenter,$vars);
          }
          else tparse($center,$vars);
          $hsmaxp=max($hsmaxp,$hsi[$i]);
          $hsminp=min($hsminp,$hsi[$i]);
          $suminc+=$hsi[$i];
          $minf=1;
      }//for($i=0;$i<$maxrec;$i++)
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) $hsminp=0;

  $vars['PERIOD']=_SUMMARY.$dateint;
  $vars['INC']=$hst-$hstp;
  $vars['TOTAL']=$hst;
  tparse($delimiter,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['INC']=$hsminp;
  $vars['TOTAL']=$hsmin;
  if($hsmax) $vars['GRAPHIC']=(int)($maxlen*$hsmin/$hsmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  if($maxrec>0) { $avghs=sprintf("%.0f",$hst/$maxrec); $avgphs=sprintf("%.0f",$suminc/$maxrec); if(!strcmp($avgphs,'-0')) $avgphs=0; }
  else { $avghs=0; $avgphs=0; }
  $vars['INC']=$avgphs;
  $vars['TOTAL']=$avghs;
  if($hsmax) $vars['GRAPHIC']=(int)($maxlen*$avghs/$hsmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['INC']=$hsmaxp;
  $vars['TOTAL']=$hsmax;
  if($hsmax) $vars['GRAPHIC']=(int)($maxlen*$hsmax/$hsmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //RELOADS
  $vars['STAB']=4;
  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_RELOADS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['HEADER']=_RELOADS.' / ';
  $vars['REF']='reloads';
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYR;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_GRAPH;
  $vars['ELEM']='graph';
  tparse($button,$vars);
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  if(count($v)) {
      $suminc=0;
      $minf=0;
      for($j=0;$j<$maxrec;$j++) {
          $i=$ts[$j];
          if($what==21 || $what==26) $vars['PERIOD']=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          elseif($what==31 || $what==36) $vars['PERIOD']=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          elseif($what==41) $vars['PERIOD']=$byear+$i;
          elseif($what==46) $vars['PERIOD']=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $vars['INC']=$ri[$i];//$r[$i]-$rinc;
          $vars['TOTAL']=$r[$i];
          if($rmax) $vars['GRAPHIC']=(int)($maxlen*$r[$i]/$rmax);
          else $vars['GRAPHIC']=0;
          if($what==41||$i>=$lastrec-1) {
              if($what==46) {
                  if($i==$lastrec-1) $vars['INTERVAL']='lastmonth';
                  elseif($i==$lastrec) $vars['INTERVAL']='month';
              }
              elseif($what==36||$what==31) {
                  if($i==$lastrec-1) $vars['INTERVAL']='yesterday';
                  elseif($i==$lastrec) $vars['INTERVAL']='today';
              }
              else $vars['INTERVAL']='totalm';
              tparse($icenter,$vars);
          }
          else tparse($center,$vars);
          $rmaxp=max($rmaxp,$ri[$i]);
          $rminp=min($rminp,$ri[$i]);
          $suminc+=$ri[$i];
          $minf=1;
      }//for($i=0;$i<$maxrec;$i++)
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) $rminp=0;

  $vars['PERIOD']=_SUMMARY.$dateint;
  $vars['INC']=$rt-$rtp;
  $vars['TOTAL']=$rt;
  tparse($delimiter,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['INC']=$rminp;
  $vars['TOTAL']=$rmin;
  if($rmax) $vars['GRAPHIC']=(int)($maxlen*$rmin/$rmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  if($maxrec>0) { $avgr=sprintf("%.0f",$rt/$maxrec); $avgpr=sprintf("%.0f",$suminc/$maxrec); if(!strcmp($avgpr,'-0')) $avgpr=0; }
  else { $avgr=0; $avgpr=0; }
  $vars['INC']=$avgpr;
  $vars['TOTAL']=$avgr;
  if($rmax) $vars['GRAPHIC']=(int)($maxlen*$avgr/$rmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['INC']=$rmaxp;
  $vars['TOTAL']=$rmax;
  if($rmax) $vars['GRAPHIC']=(int)($maxlen*$rmax/$rmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HITS
  $vars['STAB']=5;
  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_HITS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['HEADER']=_HITS.' / ';
  $vars['REF']='hits';
  $vars['DETAIL']=_DETAILED;
  $vars['SORT']=_SORTBYHT;
  tparse($top,$vars);
  $vars['MODULE']='graph';
  $vars['TITLE']=_GRAPH;
  $vars['ELEM']='graph';
  tparse($button,$vars);
  $vars['TITLE']=_PIE;
  $vars['ELEM']='pie';
  tparse($button,$vars);
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  if(count($v)) {
      $suminc=0;
      $minf=0;
      for($j=0;$j<$maxrec;$j++) {
          $i=$ts[$j];
          if($what==21 || $what==26) $vars['PERIOD']=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          elseif($what==31 || $what==36) $vars['PERIOD']=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          elseif($what==41) $vars['PERIOD']=$byear+$i;
          elseif($what==46) $vars['PERIOD']=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $vars['INC']=$hti[$i];//$ht[$i]-$htinc;
          $vars['TOTAL']=$ht[$i];
          if($htmax) $vars['GRAPHIC']=(int)($maxlen*$ht[$i]/$htmax);
          else $vars['GRAPHIC']=0;
          if($what==41||$i>=$lastrec-1) {
              if($what==46) {
                  if($i==$lastrec-1) $vars['INTERVAL']='lastmonth';
                  elseif($i==$lastrec) $vars['INTERVAL']='month';
              }
              elseif($what==36||$what==31) {
                  if($i==$lastrec-1) $vars['INTERVAL']='yesterday';
                  elseif($i==$lastrec) $vars['INTERVAL']='today';
              }
              else $vars['INTERVAL']='totalm';
              tparse($icenter,$vars);
          }
          else tparse($center,$vars);
          $htmaxp=max($htmaxp,$hti[$i]);
          $htminp=min($htminp,$hti[$i]);
          $suminc+=$hti[$i];
          $minf=1;
      }//for($i=0;$i<$maxrec;$i++)
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) $htminp=0;

  $vars['PERIOD']=_SUMMARY.$dateint;
  $vars['INC']=$htt-$http;
  $vars['TOTAL']=$htt;
  tparse($delimiter,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['INC']=$htminp;
  $vars['TOTAL']=$htmin;
  if($htmax) $vars['GRAPHIC']=(int)($maxlen*$htmin/$htmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  if($maxrec>0) { $avght=sprintf("%.0f",$htt/$maxrec); $avgpht=sprintf("%.0f",$suminc/$maxrec); if(!strcmp($avgpht,'-0')) $avgpht=0; }
  else { $avght=0; $avgpht=0; }
  $vars['INC']=$avgpht;
  $vars['TOTAL']=$avght;
  if($htmax) $vars['GRAPHIC']=(int)($maxlen*$avght/$htmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['INC']=$htmaxp;
  $vars['TOTAL']=$htmax;
  if($htmax) $vars['GRAPHIC']=(int)($maxlen*$htmax/$htmax);
  else $vars['GRAPHIC']=0;
  tparse($foot,$vars);
  tparse($bottom,$vars);
  mysql_free_result($result);

  //total
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',252,"'.(_SUMMARY.$dateint).'","",'.($vt-$vtp).','.$maxrec.','.$vt.','.($hst-$hstp).',100.00,'.$hst.','.($rt-$rtp).',100.00,'.$rt.','.($htt-$http).',100.00,'.$htt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //minimum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',253,"min","",'.$vminp.','.($vt?sprintf("%.2f",$vmin/$vt*100.00):'0.00').','.$vmin.','.$hsminp.','.($hst?sprintf("%.2f",$hsmin/$hst*100.00):'0.00').','.$hsmin.','.$rminp.','.($rt?sprintf("%.2f",$rmin/$rt*100.00):'0.00').','.$rmin.','.$htminp.','.($htt?sprintf("%.2f",$htmin/$htt*100.00):'0.00').','.$htmin.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',254,"avg","",'.$avgpv.','.($vt?sprintf("%.2f",$avgv/$vt*100.00):'0.00').','.$avgv.','.$avgphs.','.($hst?sprintf("%.2f",$avghs/$hst*100.00):'0.00').','.$avghs.','.$avgpr.','.($rt?sprintf("%.2f",$avgr/$rt*100.00):'0.00').','.$avgr.','.$avgpht.','.($htt?sprintf("%.2f",$avght/$htt*100.00):'0.00').','.$avght.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',255,"max","",'.$vmaxp.','.($vt?sprintf("%.2f",$vmax/$vt*100.00):'0.00').','.$vmax.','.$hsmaxp.','.($hst?sprintf("%.2f",$hsmax/$hst*100.00):'0.00').','.$hsmax.','.$rmaxp.','.($rt?sprintf("%.2f",$rmax/$rt*100.00):'0.00').','.$rmax.','.$htmaxp.','.($htt?sprintf("%.2f",$htmax/$htt*100.00):'0.00').','.$htmax.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|vis_tim|the request \''.$request.'\' has failed -- '.mysql_error());return;}

?>
