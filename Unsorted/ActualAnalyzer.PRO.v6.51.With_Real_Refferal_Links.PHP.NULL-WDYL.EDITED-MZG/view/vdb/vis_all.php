<?php

  require './style/'.$conf->style.'/template/vti_a.php';

  if($page_id==221) $page_id=201;
  //begin hour of today
  $hbday=$conf->hnum-($conf->htime-$conf->dtime)/3600;

  $request='LOCK TABLES aa_hours READ, aa_days READ, aa_total READ, aa_pages READ, aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //select min and max from aa_pages/aa_groups for "Total" interval
  if($page_id<=200) {
      $request='SELECT vmin,vmax,htmin,htmax,hsmin,hsmax,rmin,rmax,id FROM aa_pages WHERE id='.$page_id;
      $resulte=mysql_query($request,$conf->link);
      if(!$resulte) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else {
      $request='SELECT vmin,vmax,htmin,htmax,hsmin,hsmax,rmin,rmax,id FROM aa_groups WHERE id='.$page_id;
      $resulte=mysql_query($request,$conf->link);
      if(!$resulte) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $rowe=mysql_fetch_object($resulte);
  mysql_free_result($resulte);
  //select from aa_total
  $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs, SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_total WHERE id='.$page_id;
  $resultt=mysql_query($request,$conf->link);
  if(!$resultt) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //select from aa_days
  //last record for total
  $request='SELECT visitors_t,hosts,hits,id,time FROM aa_days WHERE id='.$page_id.' ORDER BY time DESC LIMIT 1';
  $resultl=mysql_query($request,$conf->link);
  if(!$resultl) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //4-week, 3-last week, 5 - prev last week, 2-month, 1-last month, 0-prev last month
  $begw=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
  $beglw=$begw-7;
  $beglpw=$beglw-7;
  $begm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
  $beglm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
  $beglpm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lpmtime)/$conf->time1);
  if($begw<0) $begw=0;
  if($beglw<0) $beglw=0;
  if($beglpw<0) $beglpw=0;
  if($begm<0) $begm=0;
  if($beglm<0) $beglm=0;
  if($beglpm<0) $beglpm=0;

  $request='SELECT IF(time>='.($begw).',4,IF(time>='.($beglw).'&&time<'.($begw).',3,IF(time>='.($beglpw).'&&time<'.($beglw).',5,10))) AS tm,SUM(visitors_w) AS v7,SUM(hosts) AS hs,SUM(hits) AS ht,MIN(visitors_w) AS mn7,MIN(hosts) AS mnhs,MIN(hits) AS mnht,MIN(hits-visitors_w) AS mnr7,MAX(visitors_w) AS mx7,MAX(hosts) AS mxhs,MAX(hits) AS mxht,MAX(hits-visitors_w) AS mxr7,COUNT(*) AS nrec FROM aa_days WHERE id='.$page_id.' GROUP BY tm ORDER BY time DESC';
  $resultdw=mysql_query($request,$conf->link);
  if(!$resultdw) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='SELECT IF(time>='.($begm).',2,IF(time>='.($beglm).'&&time<'.($begm).',1,IF(time>='.($beglpm).'&&time<'.($beglm).',0,10))) AS tm,SUM(visitors_m) AS v30,SUM(hosts) AS hs,SUM(hits) AS ht,MIN(visitors_m) AS mn30,MIN(hosts) AS mnhs,MIN(hits) AS mnht,MIN(hits-visitors_m) AS mnr30,MAX(visitors_m) AS mx30,MAX(hosts) AS mxhs,MAX(hits) AS mxht,MAX(hits-visitors_m) AS mxr30,COUNT(*) AS nrec FROM aa_days WHERE id='.$page_id.' GROUP BY tm ORDER BY time DESC';
  $resultdm=mysql_query($request,$conf->link);
  if(!$resultdm) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //select from aa_hours
  $request='SELECT IF(time>='.($hbday).',3,IF(time>='.($hbday-24).'&&time<'.($hbday).',2,IF(time>='.($hbday-36).'&&time<'.($hbday-24).',1,0)))AS tm,SUM(visitors) AS v,SUM(hosts) AS hs, SUM(hits) AS ht FROM aa_hours WHERE id='.$page_id.' GROUP BY tm ORDER BY tm DESC';
  $resulth=mysql_query($request,$conf->link);
  if(!$resulth) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|vis_all|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //TOTAL
  $vt=array(0,1000000,0);    //total-0,min-1,max-2
  $hst=array(0,1000000,0);
  $htt=array(0,1000000,0);
  $rt=array(0,1000000,0);
  $mint=0;
  //aa_total processing
  if(mysql_num_rows($resultt)) {
      $row=mysql_fetch_object($resultt);
      if($row->nrec) {
              $vt[0]+=$row->v;
              $hst[0]+=$row->hs;
              $htt[0]+=$row->ht;
              $rt[0]+=($htt[0]-$vt[0]);

              $vt[1]=$rowe->vmin;
              $hst[1]=$rowe->hsmin;
              $htt[1]=$rowe->htmin;
              $rt[1]=$rowe->rmin;

              $vt[2]=$rowe->vmax;
              $hst[2]=$rowe->hsmax;
              $htt[2]=$rowe->htmax;
              $rt[2]=$rowe->rmax;
              $mint=1;
      }
  }//if(mysql_num_rows($resultt))
  mysql_free_result($resultt);
  //last record from aa_days
  if(mysql_num_rows($resultl)) {
      $row=mysql_fetch_object($resultl);
      $vt[0]+=$row->visitors_t;
      $hst[0]+=$row->hosts;
      $htt[0]+=$row->hits;
      $rt[0]=($htt[0]-$vt[0]);

      if($conf->dnum-$row->time<=1) {
          $vt[1]=min($vt[1],$row->visitors_t);
          $hst[1]=min($hst[1],$row->hosts);
          $htt[1]=min($htt[1],$row->hits);
          $rt[1]=min($rt[1],($row->hits-$row->visitors_t));
      }
      else {
          $vt[1]=0;
          $hst[1]=0;
          $htt[1]=0;
          $rt[1]=0;
      }

      $vt[2]=max($vt[2],$row->visitors_t);
      $hst[2]=max($hst[2],$row->hosts);
      $htt[2]=max($htt[2],$row->hits);
      $rt[2]=max($rt[2],($row->hits-$row->visitors_t));
      $mint=1;
  }//if(mysql_num_rows($resultl))
  if(!$mint) {
      $vt[1]=0;
      $hst[1]=0;
      $htt[1]=0;
      $rt[1]=0;
  }
  mysql_free_result($resultl);

  // 30 DAYS
  //process aa_days
  //current month and week
  $v30=array(0,0,1000000,0);        //total-0,previous30-1,min-2,max-3
  $hs30=array(0,0,1000000,0);
  $ht30=array(0,0,1000000,0);
  $r30=array(0,0,1000000,0);

  $v7=array(0,0,1000000,0);        //total-0,previous7-1,min-2,max-3
  $hs7=array(0,0,1000000,0);
  $ht7=array(0,0,1000000,0);
  $r7=array(0,0,1000000,0);
  //last month and week
  $v30l=array(0,0,1000000,0);        //total-0,previous30-1,min-2,max-3
  $hs30l=array(0,0,1000000,0);
  $ht30l=array(0,0,1000000,0);
  $r30l=array(0,0,1000000,0);

  $v7l=array(0,0,1000000,0);        //total-0,previous7-1,min-2,max-3
  $hs7l=array(0,0,1000000,0);
  $ht7l=array(0,0,1000000,0);
  $r7l=array(0,0,1000000,0);

  $mn30=0;
  $mn30l=0;
  $mn7=0;
  $mn7l=0;
  //4-week, 3-last week, 5 - prev last week, 5+4+3+2-month, 1-last month, 0-prev last month
  if(mysql_num_rows($resultdw)) {
      while($row=mysql_fetch_object($resultdw)) {
          if($row->tm==4) {
              //last 7
              $v7[0]+=$row->v7;
              $hs7[0]+=$row->hs;
              $ht7[0]+=$row->ht;
              $r7[0]=($ht7[0]-$v7[0]);

              if($conf->dnum-$begw==$row->nrec+1) {
                  $v7[2]=$row->mn7;
                  $hs7[2]=$row->mnhs;
                  $ht7[2]=$row->mnht;
                  $r7[2]=$row->mnr7;
              }
              else {
                  $v7[2]=0;
                  $hs7[2]=0;
                  $ht7[2]=0;
                  $r7[2]=0;
              }

              $v7[3]=$row->mx7;
              $hs7[3]=$row->mxhs;
              $ht7[3]=$row->mxht;
              $r7[3]=$row->mxr7;
              $mn7=1;
          }
          elseif($row->tm==3) {
              $v7l[0]+=$row->v7;
              $hs7l[0]+=$row->hs;
              $ht7l[0]+=$row->ht;
              $r7l[0]=($ht7l[0]-$v7l[0]);

              if($row->nrec==7) {
                  $v7l[2]=$row->mn7;
                  $hs7l[2]=$row->mnhs;
                  $ht7l[2]=$row->mnht;
                  $r7l[2]=$row->mnr7;
              }
              else {
                  $v7l[2]=0;
                  $hs7l[2]=0;
                  $ht7l[2]=0;
                  $r7l[2]=0;
              }

              $v7l[3]=$row->mx7;
              $hs7l[3]=$row->mxhs;
              $ht7l[3]=$row->mxht;
              $r7l[3]=$row->mxr7;
              //prev 7
              $v7[1]+=$row->v7;
              $hs7[1]+=$row->hs;
              $ht7[1]+=$row->ht;
              $r7[1]=($ht7[1]-$v7[1]);
              $mn7l=1;
          }
          elseif($row->tm==5) {
              //prev last week
              $v7l[1]+=$row->v7;
              $hs7l[1]+=$row->hs;
              $ht7l[1]+=$row->ht;
              $r7l[1]=($ht7l[1]-$v7l[1]);
          }
      }//while($row=mysql_fetch_object($resultd))
  }//if(mysql_num_rows($resultd))
  mysql_free_result($resultdw);
  if(mysql_num_rows($resultdm)) {
      while($row=mysql_fetch_object($resultdm)) {
          if($row->tm==2) {
              //last 30
              $v30[0]+=$row->v30;
              $hs30[0]+=$row->hs;
              $ht30[0]+=$row->ht;
              $r30[0]=($ht30[0]-$v30[0]);

              if($conf->dnum-$begm==$row->nrec+1) {
                            $v30[2]=min($v30[2],$row->mn30);
                            $hs30[2]=min($hs30[2],$row->mnhs);
                            $ht30[2]=min($ht30[2],$row->mnht);
                            $r30[2]=min($r30[2],$row->mnr30);
              }
              else {
                            $v30[2]=0;
                            $hs30[2]=0;
                            $ht30[2]=0;
                            $r30[2]=0;
              }
              $v30[3]=max($v30[3],$row->mx30);
              $hs30[3]=max($hs30[3],$row->mxhs);
              $ht30[3]=max($ht30[3],$row->mxht);
              $r30[3]=max($r30[3],$row->mxr30);
              $mn30=1;
          }
          elseif($row->tm==1) {
              //prev 30
              $v30[1]+=$row->v30;
              $hs30[1]+=$row->hs;
              $ht30[1]+=$row->ht;
              $r30[1]=($ht30[1]-$v30[1]);

              $v30l[0]+=$row->v30;
              $hs30l[0]+=$row->hs;
              $ht30l[0]+=$row->ht;
              $r30l[0]=($ht30l[0]-$v30l[0]);

              if($begm-$beglm==$row->nrec+1) {
                            $v30l[2]=min($v30l[2],$row->mn30);
                            $hs30l[2]=min($hs30l[2],$row->mnhs);
                            $ht30l[2]=min($ht30l[2],$row->mnht);
                            $r30l[2]=min($r30l[2],$row->mnr30);
              }
              else {
                            $v30l[2]=0;
                            $hs30l[2]=0;
                            $ht30l[2]=0;
                            $r30l[2]=0;
              }

              $v30l[3]=max($v30l[3],$row->mx30);
              $hs30l[3]=max($hs30l[3],$row->mxhs);
              $ht30l[3]=max($ht30l[3],$row->mxht);
              $r30l[3]=max($r30l[3],$row->mxr30);
              $mn30l=1;
          }
          elseif($row->tm==0) {
              //prev 30
              $v30l[1]+=$row->v30;
              $hs30l[1]+=$row->hs;
              $ht30l[1]+=$row->ht;
              $r30l[1]=($ht30l[1]-$v30l[1]);
          }
      }//while($row=mysql_fetch_object($resultd))
  }//if(mysql_num_rows($resultd))
  mysql_free_result($resultdm);

  if(!$mn30) {
      $v30[2]=0;
      $hs30[2]=0;
      $ht30[2]=0;
      $r30[2]=0;
  }
  if(!$mn7) {
      $v7[2]=0;
      $hs7[2]=0;
      $ht7[2]=0;
      $r7[2]=0;
  }
  if(!$mn30l) {
      $v30l[2]=0;
      $hs30l[2]=0;
      $ht30l[2]=0;
      $r30l[2]=0;
  }
  if(!$mn7l) {
      $v7l[2]=0;
      $hs7l[2]=0;
      $ht7l[2]=0;
      $r7l[2]=0;
  }

  //YESTERDAY AND TODAY
  //process aa_hours
  $vy=array(0,0);        //total-0,previous-1
  $hsy=array(0,0);
  $hty=array(0,0);
  $ry=array(0,0);

  $vtd=0;        //total-0
  $hstd=0;
  $httd=0;
  $rtd=0;
  if(mysql_num_rows($resulth)) {
      while($row=mysql_fetch_object($resulth)) {
          if($row->tm==3) {
              //today
              $vtd+=$row->v;
              $hstd+=$row->hs;
              $httd+=$row->ht;
              $rtd=($httd-$vtd);
          }
          elseif($row->tm==2) {
              //yesterday
              $vy[0]+=$row->v;
              $hsy[0]+=$row->hs;
              $hty[0]+=$row->ht;
              $ry[0]=($hty[0]-$vy[0]);
          }
          elseif($row->tm>=1) {
              //last yesterday
              $vy[1]+=$row->v;
              $hsy[1]+=$row->hs;
              $hty[1]+=$row->ht;
              $ry[1]=($hty[1]-$vy[1]);
          }
      }//while($row=mysql_fetch_object($resulth))
  }//if(mysql_num_rows($resulth))
  mysql_free_result($resulth);

  // for calculating of average and periods
  if($conf->dnum>29) { $ndays30=30; $dtime30=$conf->mtime; }
  else { $ndays30=$conf->dnum+1; $dtime30=$conf->mtime; }
  if($conf->dnum>6) { $ndays7=7; $dtime7=$conf->wtime; }
  else { $ndays7=$conf->dnum+1; $dtime7=$conf->wtime; }
  if($hbday>23) $nhoursy=24;
  else $nhoursy=$hbday+1;
  if(($conf->hnum-$hbday)>=23) $nhourst=24;
  else $nhourst=$conf->hnum-$hbday+1;
  if($conf->dtime) $dtimey=$conf->dtime-$conf->time1;
  else $dtimey=$conf->btime;

  $fname=$name;
  if(strlen($fname)>_VS_PGSTITLINT) $sname=substr($fname,0,_VS_PGSTITLINT-3).'...';
  else $sname=$fname;
  $vars['HEADER']=_SUMMARY.' / ';
  $vars['THEADER']=_ALLTIME.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['RHEADER']=_VISINT;
  $vars['SHOWING']=_SHOWING.' 7 '._INTERVAL_S;
  if($page_id==221) $vars['FPG']=_FORALLGRS;
  elseif($page_id>200) $vars['FPG']=_FORGR." '<b><i>".$name."</i></b>'";
  else $vars['FPG']=_FORPG.' \'<b><i><a href="'.$url.'" title="'.$fname.'" target=_blank><code>'.$sname."</code></a></i></b>'";
  $vars['BACKTT']=_BACKTOTOP;
  $vars['INTERVAL']=_TIMEINT;
  $vars['VISITORS']=_VISITORS;
  $vars['HOSTS']=_HOSTS;
  $vars['RELOADS']=_RELOADS;
  $vars['HITS']=_HITS;
  $vars['REF']='summary';
  $vars['DETAIL']=_DETAILED;
  tparse($top,$vars);

  //write total results to $vars
  $vars['INTERVAL']='total';
  $vars['PERIOD']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['VISITORS']=$vt[0];
  $vars['HOSTS']=$hst[0];
  $vars['RELOADS']=$rt[0];
  $vars['HITS']=$htt[0];
  // output total-string
  tparse($center,$vars);
  //write results 30 to $vars
  $vars['INTERVAL']='lastmonth';
  if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  else $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  $vars['VISITORS']=$v30l[0];
  $vars['HOSTS']=$hs30l[0];
  $vars['RELOADS']=$r30l[0];
  $vars['HITS']=$ht30l[0];
  // output 30-string
  tparse($center,$vars);
  //write results month to $vars
  $vars['INTERVAL']='month';
  if($conf->btime>$conf->mtime) $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['VISITORS']=$v30[0];
  $vars['HOSTS']=$hs30[0];
  $vars['RELOADS']=$r30[0];
  $vars['HITS']=$ht30[0];
  // output 30-string
  tparse($center,$vars);
  //write results last week to $vars
  $vars['INTERVAL']='lastweek';
  if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  else $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  $vars['VISITORS']=$v7l[0];
  $vars['HOSTS']=$hs7l[0];
  $vars['RELOADS']=$r7l[0];
  $vars['HITS']=$ht7l[0];
  // output 7-string
  tparse($center,$vars);
  //write results week to $vars
  $vars['INTERVAL']='week';
  if($conf->btime>$conf->wtime) $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['VISITORS']=$v7[0];
  $vars['HOSTS']=$hs7[0];
  $vars['RELOADS']=$r7[0];
  $vars['HITS']=$ht7[0];
  // output 7-string
  tparse($center,$vars);
  //write yesterday results to $vars
  $vars['INTERVAL']='yesterday';
  $vars['PERIOD']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$dtimey).')';
  $vars['VISITORS']=$vy[0];
  $vars['HOSTS']=$hsy[0];
  $vars['RELOADS']=$ry[0];
  $vars['HITS']=$hty[0];
  // output yesterday-string
  tparse($center,$vars);
  //write today results to $vars
  $vars['INTERVAL']='today';
  $vars['PERIOD']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['VISITORS']=$vtd;
  $vars['HOSTS']=$hstd;
  $vars['RELOADS']=$rtd;
  $vars['HITS']=$httd;
  // output today-string
  tparse($center,$vars);

  tparse($bottom,$vars);

  require './style/'.$conf->style.'/template/vti_d.php';

  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_VISITORS;
  $vars['MIN']=_MINIMUM;
  $vars['AVERAGE']=_AVERAGE;
  $vars['MAX']=_MAXIMUM;

  // VISITORS
  $vars['HEADER']=_VISITORS.' / ';
  $vars['REF']='visitors';
  $vars['DETAIL']=_DETAILED;
  tparse($top,$vars);

  $vars['INTERVAL']='total';
  $vars['PERIOD']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']='-';
  $vars['TOTAL']=$vt[0];
  $vars['MIN']=$vt[1];
  $vars['AVERAGE']=sprintf("%.0f",$vt[0]/($conf->dnum+1));
  $vars['MAX']=$vt[2];
  tparse($center,$vars);

  $vars['INTERVAL']='lastmonth';
  if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  else $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  $vars['INC']=($v30l[0]-$v30l[1]);
  $vars['TOTAL']=$v30l[0];
  $vars['MIN']=$v30l[2];
  $vars['AVERAGE']=sprintf("%.0f",$v30l[0]/date($conf->dmas[$conf->dformat],$conf->mtime-7200));
  $vars['MAX']=$v30l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='month';
  if($conf->btime>$conf->mtime) $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($v30[0]-$v30[1]);
  $vars['TOTAL']=$v30[0];
  $vars['MIN']=$v30[2];
  $vars['AVERAGE']=sprintf("%.0f",$v30[0]/($conf->dnum-$begm+1));
  $vars['MAX']=$v30[3];
  tparse($center,$vars);

  $vars['INTERVAL']='lastweek';
  if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  else $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  $vars['INC']=($v7l[0]-$v7l[1]);
  $vars['TOTAL']=$v7l[0];
  $vars['MIN']=$v7l[2];
  $vars['AVERAGE']=sprintf("%.0f",$v7l[0]/7);
  $vars['MAX']=$v7l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='week';
  if($conf->btime>$conf->wtime) $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($v7[0]-$v7[1]);
  $vars['TOTAL']=$v7[0];
  $vars['MIN']=$v7[2];
  $vars['AVERAGE']=sprintf("%.0f",$v7[0]/($conf->dnum-$begw+1));
  $vars['MAX']=$v7[3];
  tparse($center,$vars);

  $vars['INTERVAL']='yesterday';
  $vars['PERIOD']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$dtimey).')';
  $vars['INC']=($vy[0]-$vy[1]);
  $vars['TOTAL']=$vy[0];
  $vars['MIN']='-';//$vy[1];
  $vars['AVERAGE']='-';//sprintf("%.0f",$vy[0]/$nhoursy);
  $vars['MAX']='-';//$vy[2];
  tparse($center,$vars);

  $vars['INTERVAL']='today';
  $vars['PERIOD']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($vtd-$vy[0]);
  $vars['TOTAL']=$vtd;
  $vars['MIN']='-';//$vt[1];
  $vars['AVERAGE']='-';//(int)($vtd/$nhourst);
  $vars['MAX']='-';//$vt[2];
  tparse($center,$vars);

  tparse($bottom,$vars);

  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_HOSTS;
  $vars['MIN']=_MINIMUM;
  $vars['AVERAGE']=_AVERAGE;
  $vars['MAX']=_MAXIMUM;

  // HOSTS
  $vars['HEADER']=_HOSTS.' / ';
  $vars['REF']='hosts';
  $vars['DETAIL']=_DETAILED;
  tparse($top,$vars);

  $vars['INTERVAL']='total';
  $vars['PERIOD']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']='-';
  $vars['TOTAL']=$hst[0];
  $vars['MIN']=$hst[1];
  $vars['AVERAGE']=sprintf("%.0f",$hst[0]/($conf->dnum+1));
  $vars['MAX']=$hst[2];
  tparse($center,$vars);

  $vars['INTERVAL']='lastmonth';
  if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  else $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  $vars['INC']=($hs30l[0]-$hs30l[1]);
  $vars['TOTAL']=$hs30l[0];
  $vars['MIN']=$hs30l[2];
  $vars['AVERAGE']=sprintf("%.0f",$hs30l[0]/date($conf->dmas[$conf->dformat],$conf->mtime-7200));
  $vars['MAX']=$hs30l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='month';
  if($conf->btime>$conf->mtime) $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($hs30[0]-$hs30[1]);
  $vars['TOTAL']=$hs30[0];
  $vars['MIN']=$hs30[2];
  $vars['AVERAGE']=sprintf("%.0f",$hs30[0]/($conf->dnum-$begm+1));
  $vars['MAX']=$hs30[3];
  tparse($center,$vars);

  $vars['INTERVAL']='lastweek';
  if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  else $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  $vars['INC']=($hs7l[0]-$hs7l[1]);
  $vars['TOTAL']=$hs7l[0];
  $vars['MIN']=$hs7l[2];
  $vars['AVERAGE']=sprintf("%.0f",$hs7l[0]/7);
  $vars['MAX']=$hs7l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='week';
  if($conf->btime>$conf->wtime) $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($hs7[0]-$hs7[1]);
  $vars['TOTAL']=$hs7[0];
  $vars['MIN']=$hs7[2];
  $vars['AVERAGE']=sprintf("%.0f",$hs7[0]/($conf->dnum-$begw+1));
  $vars['MAX']=$hs7[3];
  tparse($center,$vars);

  $vars['INTERVAL']='yesterday';
  $vars['PERIOD']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$dtimey).')';
  $vars['INC']=($hsy[0]-$hsy[1]);
  $vars['TOTAL']=$hsy[0];
  $vars['MIN']='-';//$hsy[1];
  $vars['AVERAGE']='-';//(int)($hsy[0]/$nhoursy);
  $vars['MAX']='-';//$hsy[2];
  tparse($center,$vars);

  $vars['INTERVAL']='today';
  $vars['PERIOD']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($hstd-$hsy[0]);
  $vars['TOTAL']=$hstd;
  $vars['MIN']='-';//$hst[1];
  $vars['AVERAGE']='-';//(int)($hstd/$nhourst);
  $vars['MAX']='-';//$hst[2];
  tparse($center,$vars);

  tparse($bottom,$vars);

  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_RELOADS;
  $vars['MIN']=_MINIMUM;
  $vars['AVERAGE']=_AVERAGE;
  $vars['MAX']=_MAXIMUM;

  // RELOADS
  $vars['HEADER']=_RELOADS.' / ';
  $vars['REF']='reloads';
  $vars['DETAIL']=_DETAILED;
  tparse($top,$vars);

  $vars['INTERVAL']='total';
  $vars['PERIOD']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']='-';
  $vars['TOTAL']=$rt[0];
  $vars['MIN']=$rt[1];
  $vars['AVERAGE']=sprintf("%.0f",$rt[0]/($conf->dnum+1));
  $vars['MAX']=$rt[2];
  tparse($center,$vars);

  $vars['INTERVAL']='lastmonth';
  if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  else $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  $vars['INC']=($r30l[0]-$r30l[1]);
  $vars['TOTAL']=$r30l[0];
  $vars['MIN']=$r30l[2];
  $vars['AVERAGE']=sprintf("%.0f",$r30l[0]/date($conf->dmas[$conf->dformat],$conf->mtime-7200));
  $vars['MAX']=$r30l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='month';
  if($conf->btime>$conf->mtime) $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($r30[0]-$r30[1]);
  $vars['TOTAL']=$r30[0];
  $vars['MIN']=$r30[2];
  $vars['AVERAGE']=sprintf("%.0f",$r30[0]/($conf->dnum-$begm+1));
  $vars['MAX']=$r30[3];
  tparse($center,$vars);

  $vars['INTERVAL']='lastweek';
  if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  else $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  $vars['INC']=($r7l[0]-$r7l[1]);
  $vars['TOTAL']=$r7l[0];
  $vars['MIN']=$r7l[2];
  $vars['AVERAGE']=sprintf("%.0f",$r7l[0]/7);
  $vars['MAX']=$r7l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='week';
  if($conf->btime>$conf->wtime) $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($r7[0]-$r7[1]);
  $vars['TOTAL']=$r7[0];
  $vars['MIN']=$r7[2];
  $vars['AVERAGE']=sprintf("%.0f",$r7[0]/($conf->dnum-$begw+1));
  $vars['MAX']=$r7[3];
  tparse($center,$vars);

  $vars['INTERVAL']='yesterday';
  $vars['PERIOD']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$dtimey).')';
  $vars['INC']=($ry[0]-$ry[1]);
  $vars['TOTAL']=$ry[0];
  $vars['MIN']='-';//$ry[1];
  $vars['AVERAGE']='-';//(int)($ry[0]/$nhoursy);
  $vars['MAX']='-';//$ry[2];
  tparse($center,$vars);

  $vars['INTERVAL']='today';
  $vars['PERIOD']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($rtd-$ry[0]);
  $vars['TOTAL']=$rtd;
  $vars['MIN']='-';//$rt[1];
  $vars['AVERAGE']='-';//(int)($rtd/$nhourst);
  $vars['MAX']='-';//$rt[2];
  tparse($center,$vars);

  tparse($bottom,$vars);

  $vars['INTERVAL']=_TIMEINT;
  $vars['INC']=_INCREASE;
  $vars['TOTAL']=_HITS;
  $vars['MIN']=_MINIMUM;
  $vars['AVERAGE']=_AVERAGE;
  $vars['MAX']=_MAXIMUM;

  // HITS
  $vars['HEADER']=_HITS.' / ';
  $vars['REF']='hits';
  $vars['DETAIL']=_DETAILED;
  tparse($top,$vars);

  $vars['INTERVAL']='total';
  $vars['PERIOD']=_TOTAL.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']='-';
  $vars['TOTAL']=$htt[0];
  $vars['MIN']=$htt[1];
  $vars['AVERAGE']=sprintf("%.0f",$htt[0]/($conf->dnum+1));
  $vars['MAX']=$htt[2];
  tparse($center,$vars);

  $vars['INTERVAL']='lastmonth';
  if($conf->btime>=$conf->lmtime&&$conf->btime<$conf->mtime) $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  else $vars['PERIOD']=_LASTMONTH.' ('.date($conf->dmas[$conf->dformat],$conf->lmtime).' - '.date($conf->dmas[$conf->dformat],$conf->mtime-7200).')';
  $vars['INC']=($ht30l[0]-$ht30l[1]);
  $vars['TOTAL']=$ht30l[0];
  $vars['MIN']=$ht30l[2];
  $vars['AVERAGE']=sprintf("%.0f",$ht30l[0]/date($conf->dmas[$conf->dformat],$conf->mtime-7200));
  $vars['MAX']=$ht30l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='month';
  if($conf->btime>$conf->mtime) $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_MONTH.' ('.date($conf->dmas[$conf->dformat],$conf->mtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($ht30[0]-$ht30[1]);
  $vars['TOTAL']=$ht30[0];
  $vars['MIN']=$ht30[2];
  $vars['AVERAGE']=sprintf("%.0f",$ht30[0]/($conf->dnum-$begm+1));
  $vars['MAX']=$ht30[3];
  tparse($center,$vars);

  $vars['INTERVAL']='lastweek';
  if($conf->btime>=$conf->lwtime&&$conf->btime<$conf->wtime) $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  else $vars['PERIOD']=_LASTWEEK.' ('.date($conf->dmas[$conf->dformat],$conf->lwtime).' - '.date($conf->dmas[$conf->dformat],$conf->wtime-7200).')';
  $vars['INC']=($ht7l[0]-$ht7l[1]);
  $vars['TOTAL']=$ht7l[0];
  $vars['MIN']=$ht7l[2];
  $vars['AVERAGE']=sprintf("%.0f",$ht7l[0]/7);
  $vars['MAX']=$ht7l[3];
  tparse($center,$vars);

  $vars['INTERVAL']='week';
  if($conf->btime>$conf->wtime) $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  else $vars['PERIOD']=_WEEK.' ('.date($conf->dmas[$conf->dformat],$conf->wtime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($ht7[0]-$ht7[1]);
  $vars['TOTAL']=$ht7[0];
  $vars['MIN']=$ht7[2];
  $vars['AVERAGE']=sprintf("%.0f",$ht7[0]/($conf->dnum-$begw+1));
  $vars['MAX']=$ht7[3];
  tparse($center,$vars);

  $vars['INTERVAL']='yesterday';
  $vars['PERIOD']=_YESTERDAY.' ('.date($conf->dmas[$conf->dformat],$dtimey).')';
  $vars['INC']=($hty[0]-$hty[1]);
  $vars['TOTAL']=$hty[0];
  $vars['MIN']='-';//$hty[1];
  $vars['AVERAGE']='-';//(int)($hty[0]/$nhoursy);
  $vars['MAX']='-';//$hty[2];
  tparse($center,$vars);

  $vars['INTERVAL']='today';
  $vars['PERIOD']=_TODAY.' ('.date($conf->dmas[$conf->dformat],$conf->ctime).')';
  $vars['INC']=($httd-$hty[0]);
  $vars['TOTAL']=$httd;
  $vars['MIN']='-';//$htt[1];
  $vars['AVERAGE']='-';//(int)($httd/$nhourst);
  $vars['MAX']='-';//$htt[2];
  tparse($center,$vars);

  tparse($bottom,$vars);

?>
