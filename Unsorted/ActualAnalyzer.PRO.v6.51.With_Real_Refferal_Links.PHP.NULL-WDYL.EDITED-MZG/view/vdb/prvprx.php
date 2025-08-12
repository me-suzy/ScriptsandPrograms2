<?php

  if($page_id==221) $page_id=201;
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $dyear=$year;
  $year=$year-(int)(date('Y',$conf->btime))+1;
  if(!strcmp($tint,'totalm')) {
      $values='v'.$year.' AS v,hs'.$year.' AS hs,ht'.$year.'-v'.$year.' AS r,ht'.$year.' AS ht';
      $minvalues='MIN(v'.$year.') AS minv,MIN(hs'.$year.') AS minhs,MIN(ht'.$year.'-v'.$year.') AS minr,MIN(ht'.$year.') AS minht';
      $maxvalues='MAX(v'.$year.') AS maxv,MAX(hs'.$year.') AS maxhs,MAX(ht'.$year.'-v'.$year.') AS maxr,MAX(ht'.$year.') AS maxht';
      $sumvalues='SUM(v'.$year.') AS sumv,SUM(hs'.$year.') AS sumhs,SUM(ht'.$year.'-v'.$year.') AS sumr,SUM(ht'.$year.') AS sumht';
      $where=' AND (v'.$year.'!=0 OR hs'.$year.'!=0 OR ht'.$year.'!=0)';
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
      $sumvalues='SUM('.$vvalues.') AS sumv,SUM('.$hsvalues.') AS sumhs,SUM(('.$htvalues.')-('.$vvalues.')) AS sumr,SUM('.$htvalues.') AS sumht';
      $where=' AND (('.$vvalues.')!=0 OR ('.$hsvalues.')!=0 OR ('.$htvalues.')!=0)';
      $dateint=' ('.date($conf->dmas[$conf->dformat],$conf->btime).' - '.date($conf->dmas[$conf->dformat],$conf->ctime).') ';
  }
  if($sort['column']==1) $ordert='name ASC';
  elseif(($sort['table']==1&&$sort['column']==2)||($sort['table']==2&&$sort['column']==2)) $ordert='v DESC,name ASC';
  elseif(($sort['table']==1&&$sort['column']==3)||($sort['table']==3&&$sort['column']==2)) $ordert='hs DESC,name ASC';
  elseif(($sort['table']==1&&$sort['column']==4)||($sort['table']==4&&$sort['column']==2)) $ordert='r DESC,name ASC';
  elseif(($sort['table']==1&&$sort['column']==5)||($sort['table']==5&&$sort['column']==2)) $ordert='ht DESC,name ASC';
  if($flag==2) $ordert.=',ip ASC';
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

  if($flag==1) $request='LOCK TABLES aa_prv_base READ,aa_prv_total READ,aa_coun_base READ';
  else $request='LOCK TABLES aa_prx_base READ,aa_prx_total READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|prvprx|blocking of tables has failed -- '.mysql_error());return;}

  if($flag==1) $request='SELECT name,counid,'.$values.' FROM aa_prv_total LEFT OUTER JOIN aa_prv_base ON aa_prv_total.prvid=aa_prv_base.prvid WHERE id='.$page_id.$where.' ORDER BY '.$ordert.' LIMIT '.$begstr.','.$numstr;
  else $request='SELECT ip,name,'.$values.' FROM aa_prx_total LEFT OUTER JOIN aa_prx_base ON aa_prx_total.prxid=aa_prx_base.prxid WHERE id='.$page_id.$where.' ORDER BY '.$ordert.' LIMIT '.$begstr.','.$numstr;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if($flag==1) {
      $mascoun=array();
      $idcm=array();
      $idc='';
      while($row=mysql_fetch_object($result)) {
          if(isset($idcm[$row->counid])) continue;
          if(empty($idc)) $idc='('.$row->counid;
          else $idc.=','.$row->counid;
          $idcm[$row->counid]=1;
      }
      if(!empty($idc)) {
          $idc.=')';
          $request='SELECT counid,sname,lname FROM aa_coun_base WHERE counid IN '.$idc;
          $resultc=mysql_query($request,$conf->link);
          if(!$resultc) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($resultc)) {
              if(!isset($mascoun[$row->counid])) {
                  $mascoun[$row->counid]['sname']=$row->sname;
                  $mascoun[$row->counid]['lname']=$row->lname;
              }
          }
          mysql_free_result($resultc);
      }
  }

  if($flag==1) $request='SELECT '.$minvalues.','.$maxvalues.','.$sumvalues.',COUNT(*) AS nrect FROM aa_prv_total WHERE id='.$page_id.$where;
  else $request='SELECT '.$minvalues.','.$maxvalues.','.$sumvalues.',COUNT(*) AS nrect FROM aa_prx_total WHERE id='.$page_id.$where;
  $resultt=mysql_query($request,$conf->link);
  if(!$resultt) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  while($row=mysql_fetch_object($resultt)) {
      $nrect=$row->nrect;
      if($nrect) {
          $vsumt=$row->sumv;
          $hssumt=$row->sumhs;
          $htsumt=$row->sumht;
          $rsumt=$row->sumr;
          $vmint=$row->minv;
          $hsmint=$row->minhs;
          $htmint=$row->minht;
          $rmint=$row->minr;
          $vmaxt=$row->maxv;
          $hsmaxt=$row->maxhs;
          $htmaxt=$row->maxht;
          $rmaxt=$row->maxr;
          $vavgt=sprintf("%.0f",$vsumt/$nrect);
          $hsavgt=sprintf("%.0f",$hssumt/$nrect);
          $htavgt=sprintf("%.0f",$htsumt/$nrect);
          $ravgt=sprintf("%.0f",$rsumt/$nrect);
      }
      else {
          $vmint=0;
          $hsmint=0;
          $htmint=0;
          $rmint=0;
      }
  }
  mysql_free_result($resultt);

  // length of column for graphic view
  $maxlen=175;
  // max values
  $vmax=0;
  $hsmax=0;
  $htmax=0;
  $rmax=0;
  $vmin=100000;                               //min values
  $hsmin=100000;
  $htmin=100000;
  $rmin=100000;
  $vs=0;                //page sum
  $hss=0;
  $hts=0;
  $rs=0;
  if($numstr>$nrect) $numstr=$nrect;               //nrect - the number of total pages in group/groups
  $nrec=$nrect-$begstr;                            //nrec - the number of frames pages in group/groups
  if($nrec>$numstr) $nrec=$numstr;

  require './style/'.$conf->style.'/template/vpr_a.php';
  $vars['LISTLEN']=$nrect;
  $vars['STAB']=1;
  $vars['REF']='summary';
  $vars['HEADER']=_SUMMARY.' / ';
  if($flag==1) { $vars['ITEM']=_PROVIDER; $vars['RHEADER']=_PROVIDERS; }
  else { $vars['ITEM']=_PROXY; $vars['RHEADER']=_PROXYS; }
  if($nrect) $vars['RANGE']=($begstr+1).' - '.($begstr+$nrec).' '._OUTOF.' '.$nrect;
  else $vars['RANGE']='0 - 0 '._OUTOF.' '.$nrect;
  $vars['SHOWING']=_SHOWING.' '.$nrec.' '._ITEM_S;
  $vars['FPG']=_FORGR.' \'<strong><i>'.$name.'</i></strong>\''; $vars['GRPG']=_GROUP;
  if(!strcmp($tint,'totalm')) $vars['THEADER']=_YEAR.$dateint;
  elseif(!strcmp($tint,'all')||!strcmp($tint,'total')) $vars['THEADER']=_ALLTIME.$dateint;
  $vars['DETAIL']=_DETAILED;
  $vars['VISITORS']=_VISITORS;
  $vars['HOSTS']=_HOSTS;
  $vars['RELOADS']=_RELOADS;
  $vars['HITS']=_HITS;
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
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',0,"'.($page_id.'|'.$vars['THEADER']).'","",'.$num.',0,0,0,0,0,0,0,0,0,0,0)';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $vars['MODULE']='graph';
  $vars['TITLE']=_BAR;
  $vars['ELEM']='bar';
  tparse($button,$vars);
  tparse($etop,$vars);

  if(mysql_num_rows($result)) {
      mysql_data_seek($result,0);
      while($row=mysql_fetch_object($result)) {
          $vars['NUM']=$num++;
          $vars['VISITORS']=$row->v;
          $vars['HOSTS']=$row->hs;
          $vars['HITS']=$row->ht;
          $vars['RELOADS']=$row->r;
          $nm=$row->name;
          if($flag==1) { if(strcmp($mascoun[$row->counid]['lname'],'undefined')) $nm.=' ('.$mascoun[$row->counid]['lname'].')'; }
          else {
              if(empty($nm)) $nm=long2ip($row->ip);
              else $nm.=' ('.long2ip($row->ip).')';
          }
          $vars['NAME']=$nm;
          tparse($center,$vars);
          //frames parameters
          $vmax=max($vmax,$row->v);
          $hsmax=max($hsmax,$row->hs);
          $htmax=max($htmax,$row->ht);
          $rmax=max($rmax,$row->r);
          $vmin=min($vmin,$row->v);
          $hsmin=min($hsmin,$row->hs);
          $htmin=min($htmin,$row->ht);
          $rmin=min($rmin,$row->r);
          $minf=1;
          $vs+=$row->v;
          $hss+=$row->hs;
          $hts+=$row->ht;
          $rs+=$row->r;

          $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.','.$rdnum.',"'.$vars['NAME'].'","",0,'.($vsumt?sprintf("%.2f",$row->v/$vsumt*100):'0.00').','.$row->v.',0,'.($hssumt?sprintf("%.2f",$row->hs/$hssumt*100):'0.00').','.$row->hs.',0,'.($rsumt?sprintf("%.2f",$row->r/$rsumt*100):'0.00').','.$row->r.',0,'.($htsumt?sprintf("%.2f",$row->ht/$htsumt*100):'0.00').','.$row->ht.')';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $rdnum++;
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if(!$minf) { $vmin=0; $hsmin=0; $htmin=0; $rmin=0; }
  if($nrec) {
      $av=sprintf("%.0f",$vs/$nrec);
      $ahs=sprintf("%.0f",$hss/$nrec);
      $aht=sprintf("%.0f",$hts/$nrec);
      $ar=sprintf("%.0f",$rs/$nrec);
  }
  else { $av=0; $ahs=0; $aht=0; $ar=0; }
  if($numstr<$nrect) {
        $vars['VISITORS']=$vs;
        $vars['HOSTS']=$hss;
        $vars['HITS']=$hts;
        $vars['RELOADS']=$rs;
        tparse($delimiter,$vars);
        $vars['NAME']=_MINIMUM;
        $vars['VISITORS']=$vmin;
        $vars['HOSTS']=$hsmin;
        $vars['HITS']=$htmin;
        $vars['RELOADS']=$rmin;
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $vars['VISITORS']=$av;
        $vars['HOSTS']=$ahs;
        $vars['HITS']=$aht;
        $vars['RELOADS']=$ar;
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['VISITORS']=$vmax;
        $vars['HOSTS']=$hsmax;
        $vars['HITS']=$htmax;
        $vars['RELOADS']=$rmax;
        tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
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

  require './style/'.$conf->style.'/template/vpr_d.php';
  //VISITORS
  $vars['STAB']=2;
  $vars['HEADER']=_VISITORS.' / ';
  $vars['REF']='visitors';
  $vars['TOTAL']=_VISITORS;
  $vars['GRAPHIC']=_GRAPHIC;
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
  $minf=0;
  if(mysql_num_rows($result)) {
      mysql_data_seek($result,0);
      while($row=mysql_fetch_object($result)) {
          $vars['NUM']=$num++;
          $vars['TOTAL']=$row->v;
          $vars['PER']=$vsumt?sprintf("%.2f",$row->v/$vsumt*100):'0.00';
          $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$row->v/$vmaxt):0;
          $nm=$row->name;
          if($flag==1) { if(strcmp($mascoun[$row->counid]['lname'],'undefined')) $nm.=' ('.$mascoun[$row->counid]['lname'].')'; }
          else {
              if(empty($nm)) $nm=long2ip($row->ip);
              else $nm.=' ('.long2ip($row->ip).')';
          }
          $vars['NAME']=$nm;
          tparse($center,$vars);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
        $vars['TOTAL']=$vs;
        $vars['PER']=$vsumt?sprintf("%.2f",$vs/$vsumt*100):'0.00';
        tparse($delimiter,$vars);
        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$vmin;
        $vars['PER']=$vsumt?sprintf("%.2f",$vmin/$vsumt*100):'0.00';
        $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$vmin/$vmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $vars['TOTAL']=$av;
        $vars['PER']=$vsumt?sprintf("%.2f",$av/$vsumt*100):'0.00';
        $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$av/$vmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$vmax;
        $vars['PER']=$vsumt?sprintf("%.2f",$vmax/$vsumt*100):'0.00';
        $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$vmax/$vmaxt):'0';
        tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$vsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$vmint;
  $vars['PER']=$vsumt?sprintf("%.2f",$vmint/$vsumt*100):'0.00';
  $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$vmint/$vmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$vavgt;
  $vars['PER']=$vsumt?sprintf("%.2f",$vavgt/$vsumt*100):'0.00';
  $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$vavgt/$vmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$vmaxt;
  $vars['PER']=$vsumt?sprintf("%.2f",$vmaxt/$vsumt*100):'0.00';
  $vars['GRAPHIC']=$vmaxt?(int)($maxlen*$vmaxt/$vmaxt):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HOSTS
  $vars['STAB']=3;
  $vars['HEADER']=_HOSTS.' / ';
  $vars['REF']='hosts';
  $vars['TOTAL']=_HOSTS;
  $vars['GRAPHIC']=_GRAPHIC;
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
  $minf=0;
  if(mysql_num_rows($result)) {
      mysql_data_seek($result,0);
      while($row=mysql_fetch_object($result)) {
          $vars['NUM']=$num++;
          $vars['TOTAL']=$row->hs;
          $vars['PER']=$hssumt?sprintf("%.2f",$row->hs/$hssumt*100):'0.00';
          $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$row->hs/$hsmaxt):'0';
          $nm=$row->name;
          if($flag==1) { if(strcmp($mascoun[$row->counid]['lname'],'undefined')) $nm.=' ('.$mascoun[$row->counid]['lname'].')'; }
          else {
              if(empty($nm)) $nm=long2ip($row->ip);
              else $nm.=' ('.long2ip($row->ip).')';
          }
          $vars['NAME']=$nm;
          tparse($center,$vars);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
        $vars['TOTAL']=$hss;
        $vars['PER']=$hssumt?sprintf("%.2f",$hss/$hssumt*100):'0.00';
        tparse($delimiter,$vars);
        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$hsmin;
        $vars['PER']=$hssumt?sprintf("%.2f",$hsmin/$hssumt*100):'0.00';
        $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$hsmin/$hsmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $vars['TOTAL']=$ahs;
        $vars['PER']=$hssumt?sprintf("%.2f",$ahs/$hssumt*100):'0.00';
        $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$ahs/$hsmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$hsmax;
        $vars['PER']=$hssumt?sprintf("%.2f",$hsmax/$hssumt*100):'0.00';
        $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$hsmax/$hsmaxt):'0';
        tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$hssumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$hsmint;
  $vars['PER']=$hssumt?sprintf("%.2f",$hsmint/$hssumt*100):'0.00';
  $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$hsmint/$hsmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$hsavgt;
  $vars['PER']=$hssumt?sprintf("%.2f",$hsavgt/$hssumt*100):'0.00';
  $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$hsavgt/$hsmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$hsmaxt;
  $vars['PER']=$hssumt?sprintf("%.2f",$hsmaxt/$hssumt*100):'0.00';
  $vars['GRAPHIC']=$hsmaxt?(int)($maxlen*$hsmaxt/$hsmaxt):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //RELOADS
  $vars['STAB']=4;
  $vars['HEADER']=_RELOADS.' / ';
  $vars['REF']='reloads';
  $vars['TOTAL']=_RELOADS;
  $vars['GRAPHIC']=_GRAPHIC;
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
  $minf=0;
  if(mysql_num_rows($result)) {
      mysql_data_seek($result,0);
      while($row=mysql_fetch_object($result)) {
          $vars['NUM']=$num++;
          $vars['TOTAL']=$row->r;
          $vars['PER']=$rsumt?sprintf("%.2f",$row->r/$rsumt*100):'0.00';
          $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$row->r/$rmaxt):'0';
          $nm=$row->name;
          if($flag==1) { if(strcmp($mascoun[$row->counid]['lname'],'undefined')) $nm.=' ('.$mascoun[$row->counid]['lname'].')'; }
          else {
              if(empty($nm)) $nm=long2ip($row->ip);
              else $nm.=' ('.long2ip($row->ip).')';
          }
          $vars['NAME']=$nm;
          tparse($center,$vars);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
        $vars['TOTAL']=$rs;
        $vars['PER']=$rsumt?sprintf("%.2f",$rs/$rsumt*100):'0.00';
        tparse($delimiter,$vars);
        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$rmin;
        $vars['PER']=$rsumt?sprintf("%.2f",$rmin/$rsumt*100):'0.00';
        $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$rmin/$rmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $vars['TOTAL']=$ar;
        $vars['PER']=$rsumt?sprintf("%.2f",$ar/$rsumt*100):'0.00';
        $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$ar/$rmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$rmax;
        $vars['PER']=$rsumt?sprintf("%.2f",$rmax/$rsumt*100):'0.00';
        $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$rmax/$rmaxt):'0';
        tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$rsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$rmint;
  $vars['PER']=$rsumt?sprintf("%.2f",$rmint/$rsumt*100):'0.00';
  $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$rmint/$rmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$ravgt;
  $vars['PER']=$rsumt?sprintf("%.2f",$ravgt/$rsumt*100):'0.00';
  $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$ravgt/$rmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$rmaxt;
  $vars['PER']=$rsumt?sprintf("%.2f",$rmaxt/$rsumt*100):'0.00';
  $vars['GRAPHIC']=$rmaxt?(int)($maxlen*$rmaxt/$rmaxt):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);

  //HITS
  $vars['STAB']=5;
  $vars['HEADER']=_HITS.' / ';
  $vars['REF']='hits';
  $vars['TOTAL']=_HITS;
  $vars['GRAPHIC']=_GRAPHIC;
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
  $minf=0;
  if(mysql_num_rows($result)) {
      mysql_data_seek($result,0);
      while($row=mysql_fetch_object($result)) {
          $vars['NUM']=$num++;
          $vars['TOTAL']=$row->ht;
          $vars['PER']=$htsumt?sprintf("%.2f",$row->ht/$htsumt*100):'0.00';
          $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$row->ht/$htmaxt):'0';
          $nm=$row->name;
          if($flag==1) { if(strcmp($mascoun[$row->counid]['lname'],'undefined')) $nm.=' ('.$mascoun[$row->counid]['lname'].')'; }
          else {
              if(empty($nm)) $nm=long2ip($row->ip);
              else $nm.=' ('.long2ip($row->ip).')';
          }
          $vars['NAME']=$nm;
          tparse($center,$vars);
      }
  }
  else { $vars['TEXT']=_NORECORDS; tparse($empty,$vars); }
  if($numstr<$nrect) {
        $vars['TOTAL']=$hts;
        $vars['PER']=$htsumt?sprintf("%.2f",$hts/$htsumt*100):'0.00';
        tparse($delimiter,$vars);
        $vars['NAME']=_MINIMUM;
        $vars['TOTAL']=$htmin;
        $vars['PER']=$htsumt?sprintf("%.2f",$htmin/$htsumt*100):'0.00';
        $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$htmin/$htmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_AVERAGE;
        $vars['TOTAL']=$aht;
        $vars['PER']=$htsumt?sprintf("%.2f",$aht/$htsumt*100):'0.00';
        $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$aht/$htmaxt):'0';
        tparse($foot,$vars);
        $vars['NAME']=_MAXIMUM;
        $vars['TOTAL']=$htmax;
        $vars['PER']=$htsumt?sprintf("%.2f",$htmax/$htsumt*100):'0.00';
        $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$htmax/$htmaxt):'0';
        tparse($foot,$vars);
  }
  if($nrect) $vars['NAME']=_TOTAL.' (1 - '.$nrect.')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$htsumt;
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$htmint;
  $vars['PER']=$htsumt?sprintf("%.2f",$htmint/$htsumt*100):'0.00';
  $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$htmint/$htmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$htavgt;
  $vars['PER']=$htsumt?sprintf("%.2f",$htavgt/$htsumt*100):'0.00';
  $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$htavgt/$htmaxt):'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$htmaxt;
  $vars['PER']=$htsumt?sprintf("%.2f",$htmaxt/$htsumt*100):'0.00';
  $vars['GRAPHIC']=$htmaxt?(int)($maxlen*$htmaxt/$htmaxt):'0';
  tparse($foot,$vars);
  tparse($bottom,$vars);
  mysql_free_result($result);
  //total
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',252,"tot","",0,'.$nrect.','.$vsumt.',0,100.00,'.$hssumt.',0,100.00,'.$rsumt.',0,100.00,'.$htsumt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //minimum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',253,"min","",0,'.($vsumt?sprintf("%.2f",$vmint/$vsumt*100):'0.00').','.$vmint.',0,'.($hssumt?sprintf("%.2f",$hsmint/$hssumt*100):'0.00').','.$hsmint.',0,'.($rsumt?sprintf("%.2f",$rmint/$rsumt*100):'0.00').','.$rmint.',0,'.($htsumt?sprintf("%.2f",$htmint/$htsumt*100):'0.00').','.$htmint.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',254,"avg","",0,'.($vsumt?sprintf("%.2f",$vavgt/$vsumt*100):'0.00').','.$vavgt.',0,'.($hssumt?sprintf("%.2f",$hsavgt/$hssumt*100):'0.00').','.$hsavgt.',0,'.($rsumt?sprintf("%.2f",$ravgt/$rsumt*100):'0.00').','.$ravgt.',0,'.($htsumt?sprintf("%.2f",$htavgt/$htsumt*100):'0.00').','.$htavgt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //maximum
  $request='INSERT INTO aa_rdata (id,added,num,name,addpar,vi,vp,v,hsi,hsp,hs,ri,rp,r,hti,htp,ht) VALUES ('.$rdid.','.$conf->ctime.',255,"max","",0,'.($vsumt?sprintf("%.2f",$vmaxt/$vsumt*100):'0.00').','.$vmaxt.',0,'.($hssumt?sprintf("%.2f",$hsmaxt/$hssumt*100):'0.00').','.$hsmaxt.',0,'.($rsumt?sprintf("%.2f",$rmaxt/$rsumt*100):'0.00').','.$rmaxt.',0,'.($htsumt?sprintf("%.2f",$htmaxt/$htsumt*100):'0.00').','.$htmaxt.')';
  $result1=mysql_query($request,$conf->link);
  if(!$result1) {$err->reason('vdb.php|prvprx|the request \''.$request.'\' has failed -- '.mysql_error());return;}

?>
