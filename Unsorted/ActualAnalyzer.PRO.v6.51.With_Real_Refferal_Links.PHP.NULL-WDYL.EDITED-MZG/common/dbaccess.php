<?php

class dbaccess
{

var $rf;      //root folder

function dbaccess($rf) {
  global $err,$conf;

  $this->rf=$rf;
}

/*-  visitings statistics  ---------------------------------------------------*/
function getVisitings($param) {
  global $err,$conf,$dbaccess;

  if($err->flag) return 'error';

  //database initialisation
  db_init();
  if($err->flag) {
    $err->reason('dbaccess.php|getVisitings|can\'t init database');
    $err->log_out();
    return 'error';
  }
  // $stat='visitors','hits','reloads','hosts'
  // $tint='today','yesterday','month','lastmonth','week','lastweek','total','all','totalm_year'
  //get statistics
  $request='LOCK TABLES aa_hours READ, aa_days READ, aa_total READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getVisitings|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

  $rez=array();
  reset($param);
  while($e=each($param)) {
      $tmp=split(',',$e[1]);
      $page_id=$tmp[0];
      $stat=$tmp[1];
      $tint=$tmp[2];
      $year=0;
      //reinterpret year interval
      if(strstr($tint,'totalm_')) {
        $arr=preg_split("/_/",$tint);
        $tint=$arr[0];
        $year=$arr[1];
      }
      //what=0-all time, 1-month, 2-week, 3-yesterday, 4-today, 5-year, 6-last week, 7-last month
      if(!strcmp($tint,'today')) $what=4;
      elseif(!strcmp($tint,'yesterday')) $what=3;
      elseif(!strcmp($tint,'week')) $what=2;
      elseif(!strcmp($tint,'lastweek')) $what=6;
      elseif(!strcmp($tint,'month')) $what=1;
      elseif(!strcmp($tint,'lastmonth')) $what=7;
      elseif(!strcmp($tint,'totalm')) $what=5;
      elseif(!strcmp($tint,'all')||!strcmp($tint,'total')) $what=0;

      $rez[$e[0]]=0;
      $resmainv=0;
      $resmainhs=0;
      $resmainr=0;
      $resmainht=0;

      if($what==0 || $what==5) {        //for all time and any year
          if($what==0) {
              $rbeg=0;
              $rend=$conf->mnum;
          }
          elseif($what==5) {
              //begin month of year
              $byear=date('Y',$conf->btime);
              $bmonth=date('m',$conf->btime);
              $rend=($year-$byear)*12+(12-$bmonth);        //end month for select
              if($year==$byear) $rbeg=0;
              else $rbeg=$rend-11;                        //-1 for calculate increase
          }
          //get data from aa_total (group by id)
          $request='SELECT id,SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,SUM(hits-visitors) AS r FROM aa_total WHERE time>='.$rbeg.' AND time<='.$rend.' AND id='.$page_id.' GROUP BY id';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('dbaccess.php|getVisitings|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
          while($rowt=mysql_fetch_object($result)) {
              if($rowt->id) {
                $resmainv=$rowt->v;
                $resmainhs=$rowt->hs;
                $resmainht=$rowt->ht;
                $resmainr=$resmainht-$resmainv;
              }
          }
          mysql_free_result($result);
          //get last records from aa_days
          $request='SELECT time,id,visitors_t AS v,hosts AS hs,hits AS ht,hits-visitors_t AS r FROM aa_days WHERE id='.$page_id.' ORDER BY time DESC';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('dbaccess.php|getVisitings|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
          $p=array();    //massive of ids where was already processed
          while($rowd=mysql_fetch_object($result)) {
              if(isset($p[$rowd->id])) continue;
              $p[$rowd->id]=1;
              if($what==5) {
                  $lyear=date('Y',$rowd->time*$conf->time1+$conf->btime);
                  if($lyear!=$year) continue;
              }
              $resmainv+=$rowd->v;
              $resmainhs+=$rowd->hs;
              $resmainht+=$rowd->ht;
              $resmainr=$resmainht-$resmainv;
          }
          mysql_free_result($result);
      }//if($what==0 && mysql_num_rows($result1)>0)
      else {
              //begin & end time for selecting of records
          if($what==4) {
             $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;     //number of begin hour today
             $rend=$conf->hnum+1;                                    //current hour+1
             $table='aa_hours';
             $vis='aa_hours.visitors';
          }
          elseif($what==3) {
             $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;  //number of begin hour of yesterday
             $rend=$rbeg+24;                                         //number of begin hour of today
             $table='aa_hours';
             $vis='aa_hours.visitors';
          }
          elseif($what==2) {
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
              $rend=$conf->dnum+1;
              if($rbeg<0) $rbeg=0;
              $table='aa_days';
              $vis='aa_days.visitors_w';
          }
          elseif($what==1) {
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
              $rend=$conf->dnum+1;
              if($rbeg<0) $rbeg=0;
              $table='aa_days';
              $vis='aa_days.visitors_m';
          }
          elseif($what==6) {
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
              $rend=$rbeg+7;
              if($rbeg<0) $rbeg=0;
              $table='aa_days';
              $vis='aa_days.visitors_w';
          }
          elseif($what==7) {
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
              $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
              if($rbeg<0) $rbeg=0;
              $table='aa_days';
              $vis='aa_days.visitors_m';
          }
          elseif($what==0) {
             $rbeg=0;
             $rend=$conf->mnum+1;
             $table='aa_total';
             $vis='aa_total.visitors';
          }

          $request='SELECT id,SUM('.$vis.') AS v,SUM('.$table.'.hosts) AS hs,SUM('.$table.'.hits) AS ht, SUM('.$table.'.hits-'.$vis.') AS r FROM '.$table.' WHERE '.$table.'.time>='.$rbeg.' AND '.$table.'.time<'.$rend.' AND id='.$page_id.' GROUP BY id';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('dbaccess.php|getVisitings|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

          while($row=mysql_fetch_object($result)) {
              if($row->id) {
                $resmainv=$row->v;
                $resmainhs=$row->hs;
                $resmainht=$row->ht;
                $resmainr=$row->r;
              }
          }
          mysql_free_result($result);
      }

      if(!strcmp($stat,'visitors')) $rez[$e[0]]=$resmainv;
      elseif(!strcmp($stat,'hosts')) $rez[$e[0]]=$resmainhs;
      elseif(!strcmp($stat,'reloads')) $rez[$e[0]]=$resmainr;
      elseif(!strcmp($stat,'hits')) $rez[$e[0]]=$resmainht;
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('dbaccess.php|getVisitings|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  //database closing
  db_close();
  if($err->flag) {
    $err->reason('dbaccess.php|getVisitings|can\'t close connection with database');
    $err->log_out();
    return 'error';
  }

  return $rez;
}
function getids() {
  global $err,$conf,$dbaccess;
  if($err->flag) return 'error';

  //database initialisation
  db_init();
  if($err->flag) {
    $err->reason('dbaccess.php|getids|can\'t init database');
    $err->log_out();
    return 'error';
  }
  echo '<html><head><title>Pages and groups</title></head><body><table border="1"><tr><td>ID</td><td>NAME</td><td>URL</td></tr>';
  $request='SELECT id,name,url FROM aa_pages WHERE added!=0 ORDER BY id ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getids|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  while($row=mysql_fetch_object($result)) {
      echo '<tr>';
      echo '<td>'.$row->id.'</td>';
      echo '<td>'.$row->name.'</td>';
      echo '<td>'.$row->url.'</td>';
      echo '</tr>';
  }
  mysql_free_result($result);
  $request='SELECT id,name FROM aa_groups WHERE added!=0 ORDER BY id ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getids|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  while($row=mysql_fetch_object($result)) {
      echo '<tr>';
      echo '<td>'.$row->id.'</td>';
      echo '<td>'.$row->name.'</td>';
      echo '<td>&nbsp</td>';
      echo '</tr>';
  }
  mysql_free_result($result);
  echo '</table></body></html>';
  //database closing
  db_close();
  if($err->flag) {
    $err->reason('dbaccess.php|getids|can\'t close connection with database');
    $err->log_out();
    return 'error';
  }
}
function getVisTim($param) {
  global $err,$conf,$dbaccess;

  if($err->flag) return 'error';
  //database initialisation
  db_init();
  if($err->flag) {
    $err->reason('dbaccess.php|getVisTim|can\'t init database');
    $err->log_out();
    return 'error';
  }
  // $stat='visitors','hits','reloads','hosts'
  // $tint='today','yesterday','month','lastmonth','week','lastweek','total','all','totalm_year'
  //get statistics
  $request='LOCK TABLES aa_hours READ, aa_days READ, aa_total READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

  $rez=array();
  $tmp=split(',',$param);
  $page_id=$tmp[0];
  $stat=$tmp[1];
  $tint=$tmp[2];
  $year=0;
  //reinterpret year interval
  if(strstr($tint,'totalm_')) {
    $arr=preg_split("/_/",$tint);
    $tint=$arr[0];
    $year=$arr[1];
  }
  //begin & end time of selecting of records
  if(!strcmp($tint,'today')) {
      $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;     //number of begin hour today
      $rend=$conf->hnum+1;                                    //current hour+1
      $rprev=$rbeg-24;
      $what=21;
  }
  elseif(!strcmp($tint,'yesterday')) {
      $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;  //number of begin hour of yesterday
      $rend=$rbeg+24;                                         //number of begin hour of today
      $rprev=$rbeg-24;
      $what=26;
  }
  elseif(!strcmp($tint,'week')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
      $rend=$conf->dnum+1;
      if($rbeg<0) $rbeg=0;
      $what=31;
  }
  elseif(!strcmp($tint,'lastweek')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
      $rend=$rbeg+7;
      if($rbeg<0) {
          $rbeg=0;
      }
      $what=31;
  }
  elseif(!strcmp($tint,'month')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
      $rend=$conf->dnum+1;
      if($rbeg<0) {
          $rbeg=0;
      }
      $rprev=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
      $what=36;
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
      $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
      $rprev=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lpmtime)/$conf->time1);
      $what=36;
      if($rbeg<0) {
          $rbeg=0;
      }
  }
  elseif(!strcmp($tint,'total')||!strcmp($tint,'all')) {
      $byear=date('Y',$conf->btime);
      $bmonth=date('m',$conf->btime);
      $eyear=date('Y',$conf->ctime);
      $bs=0;
      $em=$conf->mnum;
      $what=41;
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
  }
  $request='LOCK TABLES aa_hours READ, aa_days READ, aa_total READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

  //select from aa_hours
  if($what==21 || $what==26) {
      $request='SELECT time AS time,visitors AS v,hosts AS hs,hits AS ht,id FROM aa_hours WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
      //calculate previous values
      $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rprev.' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  }
  elseif($what==31 || $what==36) {
      //select from aa_days
      if($what==31) $request='SELECT time AS time,visitors_w AS v,hosts AS hs,hits AS ht,id FROM aa_days WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      else $request='SELECT time AS time,visitors_m AS v,hosts AS hs,hits AS ht,id FROM aa_days WHERE time>='.($rbeg-1).' AND time<'.$rend.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
      //calculate previous values
      if($what==31) $request='SELECT SUM(visitors_w) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.($rbeg-7).' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      else $request='SELECT SUM(visitors_m) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rprev.' AND time<'.$rbeg.' AND id='.$page_id.' ORDER BY time ASC';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  }
  elseif($what==41 || $what==46) {
      //select from aa_total
      $request='SELECT * FROM aa_total WHERE time>='.$bs.' AND time<='.$em.' AND id='.$page_id.' ORDER BY time ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
      //select last record from aa_days
      $request='SELECT * FROM aa_days WHERE id='.$page_id.' ORDER BY time DESC LIMIT 1';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}
  }
  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('dbaccess.php|getVisTim|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

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
  //matrixs of processing's results
  for($i=0;$i<$maxrec;$i++) {
      $v[$i]=0;
      $hs[$i]=0;
      $ht[$i]=0;
      $r[$i]=0;
  }//for($i=0;$i<$maxrec;$i++)
  //last time before this period for calculate of increase
  if($what==21 || $what==26 || $what==31 || $what==36) $inct=$rbeg-1;
  elseif($what==41) $inct=-1;
  elseif($what==46) $inct=$bm-1;
  if(mysql_num_rows($result)) {
      while($row=mysql_fetch_object($result)) {
          if($what==21 || $what==26 || $what==31 || $what==36) {
              if($row->time!=$inct) {     //result of last record of previous period
                  $i=$row->time-$rbeg;
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
          }//elseif($what==41)
          elseif($what==46) {
              if($row->time>=$bm) {
                  $i=$row->time-$bm;//+($bmonth-1);
                  $v[$i]+=$row->visitors;
                  $hs[$i]+=$row->hosts;
                  $ht[$i]+=$row->hits;
                  $r[$i]=($ht[$i]-$v[$i]);
              }
          }//elseif($what==46)
      }//while($row=mysql_fetch_object($result))
  }//if(mysql_num_rows($result))
  if($what==41 || $what==46) {
      if(mysql_num_rows($resultd)) {
          $row=mysql_fetch_object($resultd);
          if($what==41) $i=date('Y',$row->time*$conf->time1+$conf->btime)-$byear;
          elseif($what==46) {
              $lyear=date('Y',$row->time*$conf->time1+$conf->btime);
              $lmonth=date('m',$row->time*$conf->time1+$conf->btime);
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
          }//if($what==41 || ($what==46 && $lyear==$year))
          mysql_free_result($resultd);
      }//if(mysql_num_rows($resultd))
  }//if($what==41 || $what==46)
  for($i=0;$i<$maxrec;$i++) {
      if($what==21 || $what==26) {
          $dateint=date($conf->tmas[$conf->tformat],$i*3600+$conf->dtime).' - '.date($conf->tmas[$conf->tformat],($i+1)*3600+$conf->dtime);
          $t=$i*3600+$conf->dtime;
      }
      elseif($what==31 || $what==36) {
          $dateint=date('l, '.$conf->dmas[$conf->dformat],($rbeg+$i)*$conf->time1+$conf->btime);
          $t=($rbeg+$i)*$conf->time1+$conf->btime;
      }
      elseif($what==41) {
          $dateint=$byear+$i;
          $t=mktime(0,0,0,1,1,$byear+$i,0);
      }
      elseif($what==46) {
          $dateint=date('F',mktime(0,0,0,$bd+$i+1,1,$year,0));
          $t=mktime(0,0,0,$bd+$i+1,1,$year,0);
      }
      if(!strcmp($stat,'visitors')) $rez[$t]=''.$v[$i].','.$dateint;
      elseif(!strcmp($stat,'hosts')) $rez[$t]=''.$hs[$i].','.$dateint;
      elseif(!strcmp($stat,'reloads')) $rez[$t]=''.$r[$i].','.$dateint;
      elseif(!strcmp($stat,'hits')) $rez[$t]=''.$ht[$i].','.$dateint;
  }

  //database closing
  db_close();
  if($err->flag) {
    $err->reason('dbaccess.php|getVisTim|can\'t close connection with database');
    $err->log_out();
    return 'error';
  }

  return $rez;
}
function getExtRefPages($param) {
  global $err,$conf,$dbaccess;

  if($err->flag) return 'error';
  //database initialisation
  db_init();
  if($err->flag) {
    $err->reason('dbaccess.php|getExtRefPages|can\'t init database');
    $err->log_out();
    return 'error';
  }
  // $stat='visitors','hits','reloads','hosts'
  // $tint='today','yesterday','month','lastmonth','week','lastweek','total','all','totalm_year'

  $rez=array();
  $tmp=split(',',$param);
  $page_id=$tmp[0];
  $stat=$tmp[1];
  $tint=$tmp[2];
  $limit=$tmp[3];
  $year=0;
  //reinterpret year interval
  if(strstr($tint,'totalm_')) {
    $arr=preg_split("/_/",$tint);
    $tint=$arr[0];
    $year=$arr[1];
  }
  $dy=getdate($conf->dtime-40000);
  $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $dyear=$year;
  $year=$year-(int)(date('Y',$conf->btime))+1;
  if(!strcmp($tint,'today')) {
      $values='vt AS v,hst AS hs,(htt-vt) AS r,htt AS ht';
      $where=' AND (modify>='.$conf->dtime.' AND (vt!=0 OR hst!=0 OR htt!=0))';
  }
  elseif(!strcmp($tint,'yesterday')) {
      $values='IF(modify>='.$conf->dtime.',vy,vt) AS v,IF(modify>='.$conf->dtime.',hsy,hst) AS hs,IF(modify>='.$conf->dtime.',hty-vy,htt-vt) AS r,IF(modify>='.$conf->dtime.',hty,htt) AS ht';
      $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0)))';
  }
  elseif(!strcmp($tint,'week')) {
      $values='vw AS v,hsw AS hs,htw-vw AS r,htw AS ht';
      $where=' AND (modify>='.$conf->wtime.' AND (vw!=0 OR hsw!=0 OR htw!=0))';
  }
  elseif(!strcmp($tint,'lastweek')) {
      $values='IF(modify>='.$conf->wtime.',vlw,vw) AS v,IF(modify>='.$conf->wtime.',hslw,hsw) AS hs,IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw) AS r,IF(modify>='.$conf->wtime.',htlw,htw) AS ht';
      $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0)))';
  }
  elseif(!strcmp($tint,'month')) {
      $values='vm AS v,hsm AS hs,htm-vm AS r,htm AS ht';
      $where=' AND (modify>='.$conf->mtime.' AND (vm!=0 OR hsm!=0 OR htm!=0))';
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $values='IF(modify>='.$conf->mtime.',vlm,vm) AS v,IF(modify>='.$conf->mtime.',hslm,hsm) AS hs,IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm) AS r,IF(modify>='.$conf->mtime.',htlm,htm) AS ht';
      $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0)))';
  }
  elseif(!strcmp($tint,'totalm')) {
      $values='v'.$year.' AS v,hs'.$year.' AS hs,ht'.$year.'-v'.$year.' AS r,ht'.$year.' AS ht';
      $where=' AND (v'.$year.'!=0 OR hs'.$year.'!=0 OR ht'.$year.'!=0)';
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
      $where=' AND (('.$vvalues.')!=0 OR ('.$hsvalues.')!=0 OR ('.$htvalues.')!=0)';
  }
  if(!strcmp($stat,'visitors')) $ordert='v DESC,url ASC';
  elseif(!strcmp($stat,'hosts')) $ordert='hs DESC,url ASC';
  elseif(!strcmp($stat,'reloads')) $ordert='r DESC,url ASC';
  elseif(!strcmp($stat,'hits')) $ordert='ht DESC,url ASC';

  $request='SELECT url AS name,'.$values.' FROM aa_ref_total LEFT OUTER JOIN aa_ref_base ON aa_ref_total.refid=aa_ref_base.refid WHERE id='.$page_id.' AND aa_ref_base.flag=2'.$where.' ORDER BY '.$ordert.' LIMIT '.$limit;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getExtRefPages|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

  $t=0;
  while($row=mysql_fetch_object($result)) {
      if(strcmp($row->name,'undefined')) $fname='http://'.$row->name;
      else $fname=_DIRECT;
      if(!strcmp($stat,'visitors')) $rez[$t]=''.$row->v.','.$fname;
      elseif(!strcmp($stat,'hosts')) $rez[$t]=''.$row->hs.','.$fname;
      elseif(!strcmp($stat,'reloads')) $rez[$t]=''.$row->r.','.$fname;
      elseif(!strcmp($stat,'hits')) $rez[$t]=''.$row->ht.','.$fname;
      $t++;
  }
  mysql_free_result($result);
  //database closing
  db_close();
  if($err->flag) {
    $err->reason('dbaccess.php|getExtRefPages|can\'t close connection with database');
    $err->log_out();
    return 'error';
  }

  return $rez;
}
function getRefServ($param) {
  global $err,$conf,$dbaccess;

  if($err->flag) return 'error';
  //database initialisation
  db_init();
  if($err->flag) {
    $err->reason('dbaccess.php|getRefServ|can\'t init database');
    $err->log_out();
    return 'error';
  }
  // $stat='visitors','hits','reloads','hosts'
  // $tint='today','yesterday','month','lastmonth','week','lastweek','total','all','totalm_year'

  $rez=array();
  $tmp=split(',',$param);
  $page_id=$tmp[0];
  $stat=$tmp[1];
  $tint=$tmp[2];
  $limit=$tmp[3];
  $year=0;
  //reinterpret year interval
  if(strstr($tint,'totalm_')) {
    $arr=preg_split("/_/",$tint);
    $tint=$arr[0];
    $year=$arr[1];
  }

  $dy=getdate($conf->dtime-40000);
  $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $dyear=$year;
  $year=$year-(int)(date('Y',$conf->btime))+1;
  if(!strcmp($tint,'today')) {
      $svalues='SUM(vt) AS v,SUM(hst) AS hs,SUM(htt-vt) AS r,SUM(htt) AS ht';
      $where=' AND (modify>='.$conf->dtime.' AND (vt!=0 OR hst!=0 OR htt!=0))';
  }
  elseif(!strcmp($tint,'yesterday')) {
      $svalues='SUM(IF(modify>='.$conf->dtime.',vy,vt)) AS v,SUM(IF(modify>='.$conf->dtime.',hsy,hst)) AS hs,SUM(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS r,SUM(IF(modify>='.$conf->dtime.',hty,htt)) AS ht';
      $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0)))';
  }
  elseif(!strcmp($tint,'week')) {
      $svalues='SUM(vw) AS v,SUM(hsw) AS hs,SUM(htw-vw) AS r,SUM(htw) AS ht';
      $where=' AND (modify>='.$conf->wtime.' AND (vw!=0 OR hsw!=0 OR htw!=0))';
  }
  elseif(!strcmp($tint,'lastweek')) {
      $svalues='SUM(IF(modify>='.$conf->wtime.',vlw,vw)) AS v,SUM(IF(modify>='.$conf->wtime.',hslw,hsw)) AS hs,SUM(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS r,SUM(IF(modify>='.$conf->wtime.',htlw,htw)) AS ht';
      $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0)))';
  }
  elseif(!strcmp($tint,'month')) {
      $svalues='SUM(vm) AS v,SUM(hsm) AS hs,SUM(htm-vm) AS r,SUM(htm) AS ht';
      $where=' AND (modify>='.$conf->mtime.' AND (vm!=0 OR hsm!=0 OR htm!=0))';
  }
  elseif(!strcmp($tint,'lastmonth')) {
      $svalues='SUM(IF(modify>='.$conf->mtime.',vlm,vm)) AS v,SUM(IF(modify>='.$conf->mtime.',hslm,hsm)) AS hs,SUM(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS r,SUM(IF(modify>='.$conf->mtime.',htlm,htm)) AS ht';
      $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0)))';
  }
  elseif(!strcmp($tint,'totalm')) {
      $svalues='SUM(v'.$year.') AS v,SUM(hs'.$year.') AS hs,SUM(ht'.$year.'-v'.$year.') AS r,SUM(ht'.$year.') AS ht';
      $where=' AND (v'.$year.'!=0 OR hs'.$year.'!=0 OR ht'.$year.'!=0)';
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
      $svalues='SUM('.$vvalues.') AS v,SUM('.$hsvalues.') AS hs,SUM(('.$htvalues.')-('.$vvalues.')) AS r,SUM('.$htvalues.') AS ht';
      $where=' AND (('.$vvalues.')!=0 OR ('.$hsvalues.')!=0 OR ('.$htvalues.')!=0)';
  }
  if(!strcmp($stat,'visitors')) $ordert='v DESC,name ASC';
  elseif(!strcmp($stat,'hosts')) $ordert='hs DESC,name ASC';
  elseif(!strcmp($stat,'reloads')) $ordert='r DESC,name ASC';
  elseif(!strcmp($stat,'hits')) $ordert='ht DESC,name ASC';

  $request='SELECT domain AS name,'.$svalues.' FROM aa_ref_total LEFT OUTER JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE id='.$page_id.$where.' GROUP BY name ORDER BY '.$ordert.' LIMIT '.$limit;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('dbaccess.php|getRefServ|the request \''.$request.'\' has failed -- '.mysql_error());$err->log_out();return 'error';}

  $t=0;
  while($row=mysql_fetch_object($result)) {
      if(strcmp($row->name,'undefined')) $fname=$row->name;
      else $fname=_DIRECT;
      if(!strcmp($stat,'visitors')) $rez[$t]=''.$row->v.','.$fname;
      elseif(!strcmp($stat,'hosts')) $rez[$t]=''.$row->hs.','.$fname;
      elseif(!strcmp($stat,'reloads')) $rez[$t]=''.$row->r.','.$fname;
      elseif(!strcmp($stat,'hits')) $rez[$t]=''.$row->ht.','.$fname;
      $t++;
  }
  mysql_free_result($result);

  //database closing
  db_close();
  if($err->flag) {
    $err->reason('dbaccess.php|getRefServ|can\'t close connection with database');
    $err->log_out();
    return 'error';
  }

  return $rez;
}

}
?>
