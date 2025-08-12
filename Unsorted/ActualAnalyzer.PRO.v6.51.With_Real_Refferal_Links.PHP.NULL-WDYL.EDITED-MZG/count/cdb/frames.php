<?php

  $mrecinbase=$conf->mrfrmb;
  $mrecinlog=$conf->mrfrml;
  $restr=0;
  $request='SELECT GET_LOCK("aa_frm",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|frames|\'aa_frm\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  //FRAMES BASE
  //select frmid from frames's base
  $request='SELECT frmid FROM aa_frm_base WHERE name="'.$url.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $delids=array();
      restrict('frm','aa_frm_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
      if($err->flag) {$err->reason('cdb.php|frames|\'restrict\' function has failed');return;}
      $restr=1;
      $request='SELECT frmid FROM aa_frm_base WHERE name="" ORDER BY frmid ASC LIMIT 1';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          $row=mysql_fetch_row($result);
          mysql_free_result($result);
          $frmid=$row[0];
          $request='UPDATE aa_frm_base SET name="'.$url.'",added='.$conf->ctime.',count=1 WHERE frmid='.$frmid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {
          mysql_free_result($result);
          $request='SELECT MAX(frmid) AS lastid,COUNT(*) AS nrec FROM aa_frm_base';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $frmid=1;
          $row=mysql_fetch_object($result);
          if($row->nrec) $frmid=$row->lastid+1;
          mysql_free_result($result);
          $request='INSERT INTO aa_frm_base (frmid,added,count,name) VALUES ('.$frmid.','.$conf->ctime.',1,"'.$url.'")';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }
  else {
      $row=mysql_fetch_object($result);
      $frmid=$row->frmid;
      mysql_free_result($result);
      $request='UPDATE aa_frm_base SET count=count+1 WHERE frmid='.$frmid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  //for raw log
  $this->rawfrmid=$frmid;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // FRAMES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_frm_total WHERE id='.$k.' AND frmid='.$frmid;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          if(!$restr) {
              $delids=array();
              restrict('frm','aa_frm_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|frames|\'restrict\' function has failed');return;}
              $restr=1;
              if(isset($delids[$frmid])) { $this->notraw=1; break; }
          }
          $request='INSERT INTO aa_frm_total (id,frmid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$frmid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_frm_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND frmid='.$row->frmid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      next($pagesid);
  }//while($k=key($pagesid))
  $request='SELECT RELEASE_LOCK("aa_frm")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|frames|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);

?>
