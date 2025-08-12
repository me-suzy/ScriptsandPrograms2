<?php

  //get list of pages which contained in group
  $mid=array();
  getpgs($page_id,$mid);
  if($err->flag) {$err->reason('vdb.php|online_grpg|\'getpgs\' function has failed');return;}
  $idstmp='';
  reset($mid);
  while($e=each($mid)) {
      if(empty($idstmp)) $idstmp.='('.$e[0];
      else $idstmp.=','.$e[0];
  }
  if(!empty($idstmp)) $idstmp.=')';
  if(!empty($idstmp)) {
      $request='LOCK TABLES aa_raw READ,aa_pages READ';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|online_grpg|blocking of tables has failed -- '.mysql_error());return;}
      $request='SELECT name,url,aa_pages.id,COUNT(*) AS sum FROM aa_raw LEFT OUTER JOIN aa_pages ON aa_raw.id=aa_pages.id WHERE aa_pages.id IN'.$idstmp.' AND time>'.($tim-$conf->tonline).' AND time<='.$tim.' GROUP BY aa_raw.id,aa_raw.vid ORDER BY aa_pages.name ASC';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('vdb.php|online_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='UNLOCK TABLES';
      $resultu=mysql_query($request,$conf->link);
      if(!$resultu) {$err->reason('vdb.php|online_grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $val=array();
  $ids=array();
  $url=array();
  $tot=array();
  //total results
  $tot['sum']=0;
  $tot['avg']=0;
  $tot['max']=0;
  $tot['min']=1000000;
  $tot['nrec']=0;
  if(!empty($idstmp)) {
      while($row=mysql_fetch_object($result)) {
          if(!$row->sum) continue;
          if(!isset($val[$row->id])) $val[$row->id]=1;
          else $val[$row->id]++;
          $url[$row->id]=$row->url;
          $ids[$row->id]=$row->name;
          $tot['sum']++;
      }
      mysql_free_result($result);
  }
  reset($val);
  while($k=key($val)) {
      $tot['min']=min($tot['min'],$val[$k]);
      $tot['max']=max($tot['max'],$val[$k]);
      next($val);
  }
  $tot['nrec']=sizeof($val);
  if(!$tot['nrec']) $tot['min']=0;
  else $tot['avg']=(int)($tot['sum']/$tot['nrec']);
  if($sort['column']!=1) arsort($val);
  // length of column for graphic view
  $maxlen=175;

  require './style/'.$conf->style.'/template/vpr_on.php';
  $vars['STAB']=1;
  $vars['REF']='summary';
  $vars['HEADER']=_ONLINEBYPG;
  $vars['ITEM']=_PAGE;
  if($tot['nrec']) $vars['RANGE']='1 - '.$tot['nrec'].' '._OUTOF.' '.$tot['nrec'];
  else $vars['RANGE']='0 - 0 '._OUTOF.' '.$tot['nrec'];
  $vars['SHOWING']=_SHOWING.' '.$tot['nrec'].' '._VISITOR_S;
  $vars['ONTIME']=$tim;
  $vars['REFRESH']=_REFRESH ;
  $vars['FPG']=_FORGR.' \'<strong><i>'.$name.'</i></strong>\'';
  $vars['TOTAL']=_VISITORS;
  $vars['GRAPHIC']=_GRAPHIC;
  $vars['DETAIL']=_DETAILED;
  $vars['SORTBYN']=_SORTBYN;
  $vars['SORT']=_SORTBYV;
  $vars['SORTBYP']=_SORTBYP;
  tparse($top,$vars);

  if($tot['nrec']) {
      $num=1;
      reset($val);
      for($i=0;$i<$tot['nrec'];$i+=20) {
          $j=0;
          tparse($cpagestart,$vars);
          while($k=key($val)) {
              $vars['NUM']=$num++;
              $vars['PGID']=$k;
              $fname=$ids[$k];
              if(strlen($fname)>_VS_PGS) $sname=substr($fname,0,_VS_PGS-3).'...';
              else $sname=$fname;
              $vars['GRPG']=$fname;
              $vars['GRPGSHORT']=$sname;
              $vars['PGURL']=$url[$k];
              $vars['TOTAL']=$val[$k];
              $vars['PER']=sprintf('%.2f',$val[$k]/$tot['max']*100);
              $vars['GRAPHIC']=$maxlen*$val[$k]/$tot['max'];
              tparse($centerp,$vars);
              next($val);
              $j++;
              if($j==20) break;
          }
          tparse($cpageend,$vars);
      }
  }
  else { $vars['TEXT']=_NOVISITORS; tparse($empty,$vars); }

  if($tot['nrec']) $vars['NAME']=_TOTAL.' (1 - '.$tot['nrec'].')';
  else $vars['NAME']=_TOTAL.' (0 - 0)';
  $vars['TOTAL']=$tot['sum'];
  $vars['PER']='100.00';
  tparse($delimiter2,$vars);
  $vars['NAME']=_MINIMUM;
  $vars['TOTAL']=$tot['min'];
  $vars['PER']=$tot['nrec']?sprintf('%.2f',$tot['min']/$tot['max']*100):'0.00';
  $vars['GRAPHIC']=$tot['nrec']?$maxlen*$tot['min']/$tot['max']:'0';
  tparse($foot,$vars);
  $vars['NAME']=_AVERAGE;
  $vars['TOTAL']=$tot['avg'];
  $vars['PER']=$tot['nrec']?sprintf('%.2f',$tot['avg']/$tot['max']*100):'0.00';
  $vars['GRAPHIC']=$tot['nrec']?$maxlen*$tot['avg']/$tot['max']:'0';
  tparse($foot,$vars);
  $vars['NAME']=_MAXIMUM;
  $vars['TOTAL']=$tot['max'];
  $vars['PER']=$tot['nrec']?sprintf('%.2f',$tot['max']/$tot['max']*100):'0.00';
  $vars['GRAPHIC']=$tot['nrec']?$maxlen*$tot['max']/$tot['max']:'0';
  tparse($foot,$vars);
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

?>
