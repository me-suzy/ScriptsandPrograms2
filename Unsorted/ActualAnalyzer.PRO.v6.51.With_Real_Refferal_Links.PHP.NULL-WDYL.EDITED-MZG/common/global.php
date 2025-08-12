<?php

//read array from file
/*-------------------------------------------------------*/
function read_file($fname,&$fdata) {
  global $err;

  if(!file_exists($fname)) {$err->reason('global.php|read_file|the file '.$fname.' not found');return;}
  $file=fopen($fname,'r');
  if(!$file) {$err->reason('global.php|read_file|can\'t open the file '.$fname);return;}
  flock($file,LOCK_SH);
  while($str=fgets($file,1000)) $fdata[]=trim($str);
  flock($file,LOCK_UN);
  fclose($file);
}

//save array to file
/*-------------------------------------------------------*/
function save_file($fname,&$fdata) {
  global $err;

  $file=fopen($fname,'w');
  if(!$file) {$err->reason('global.php|save_file|can\'t create the file '.$fname);return;}
  flock($file,LOCK_EX);
  $total=sizeof($fdata);
  for ($c=0; $c<$total; $c++) {
    if(!fwrite($file,$fdata[$c]."\n")) {$err->reason('global.php|save_file|can\'t write into the file '.$fname);return;}
  }
  flock($file,LOCK_UN);
  fclose($file);
}

//attach array to file
/*-------------------------------------------------------*/
function attach($fname,&$fdata) {
  global $err;

  $file=fopen($fname,'a');
  if(!$file) {$err->reason('global.php|attach|can\'t open the file '.$fname);return;}
  flock($file,LOCK_EX);
  $total=sizeof($fdata);
  for ($c=0; $c<$total; $c++) {
    if(!fwrite($file,$fdata[$c]."\n")) {$err->reason('global.php|attach|can\'t attach to the file '.$fname);return;}
  }
  flock($file,LOCK_UN);
  fclose($file);
}

//init database
/*-------------------------------------------------------*/
function db_init () {
  global $err,$conf;

  $conf->link=@mysql_connect($conf->dbhost,$conf->dbuser,$conf->dbpass);
  if(!$conf->link) {$err->reason('global.php|db_init|connection with mysql server has failed');return;}
  $rez=mysql_select_db($conf->dbase);
  if(!$rez) {$err->reason('global.php|db_init|the request \'use '.$conf->dbase.'\' has failed -- '.mysql_error());return;}
}

//close database
/*-------------------------------------------------------*/
function db_close () {
  global $err,$conf;

  if($conf->link) {
    $rez=mysql_close($conf->link);
    if(!$rez) {$err->reason('global.php|db_close|disconnection with mysql server has failed');return;}
  }
}

//parse teplate
/*-------------------------------------------------------*/
function tparse(&$templ,&$vars) {
  global $pagehtml;

  $pagehtml.= preg_replace("/%%([A-Z0-9]+)%%/e","\$vars['\\1']",$templ);
}

//output HTML code of the page
/*-------------------------------------------------------*/
function out() {
  global $pagehtml;

  echo($pagehtml);
}

//===================================================================
function getpgs($id,&$mid) {                  // Get pages from group with id=$id
  global $err,$conf;

  $request='SELECT flags1,flags2,flags3,flags4,flags5,flags6,flags7 FROM aa_groups WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('global.php|getpgs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $mid=array();
  if(!mysql_num_rows($result)) { mysql_free_result($result); return; }
  $row=mysql_fetch_row($result);
  mysql_free_result($result);
  //cycle by flags
  for($i=0;$i<7;$i++) {
      $ind=31;
      $tmp=(float)$row[$i];
      while($tmp) {
          if($tmp%2) $mid[$ind+$i*32]=1;
          $tmp=(int)($tmp/2);
          $ind--;
      }
  }

}
//===================================================================
function restrict($item,$tbl,$maxrec,$numadd,&$module,&$delids) {
  global $err,$conf;
  //Restrict size of sheaf 'base-log'
  //$item - 'ref','key','frm','prx','prv'
  //$tbl - checked table from sheaf 'base-log' for rotation: 'aa_ref_base','aa_ref_log','aa_key_base','aa_key_log', etc.
  //$maxrec - max records in table $tbl
  //$numadd - the number of added records to table $tbl
  //$module - module: 'main','tracker', etc.

  //get the number of records in table
  if(!strcmp($tbl,'aa_ref_base')) $request='SELECT COUNT(*) AS nrec FROM '.$tbl.' WHERE url!=""';
  elseif(!strcmp(substr($tbl,-4),'base')) $request='SELECT COUNT(*) AS nrec FROM '.$tbl.' WHERE name!=""';
  else $request='SELECT COUNT(*) AS nrec FROM '.$tbl;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_object($result);
  mysql_free_result($result);
  $recintbl=$row->nrec;
  //get the number of deleted records from base
  $numdel=0;
  if(($recintbl+$numadd)>$maxrec) $numdel=$recintbl-$maxrec+$numadd;
  if($numdel<=0) return;
  $numdel=30;       //delete 30 items
  //get dead ids from base
  $endtime=$conf->ctime+1;
  $request='SELECT '.$item.'id AS delid,(('.$endtime.'-added)/count) AS speed FROM aa_'.$item.'_base WHERE added!=0 ORDER BY speed DESC LIMIT '.$numdel;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $ids='';
  while($row=mysql_fetch_object($result)) {
      if(empty($ids)) $ids=' IN('.$row->delid;
      else $ids.=','.$row->delid;
      $delids[$row->delid]=1;
  }//while($row=mysql_fetch_object($result))
  if(!empty($ids)) $ids.=')';
  mysql_free_result($result);
  if(!empty($ids)) {
      //delete deleted ids from log (not for ref)
      if(strcmp($item,'ref')) {
          $request='DELETE FROM aa_'.$item.'_total WHERE '.$item.'id'.$ids;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          //module's log processing
          if(isset($module['aat_'])) {
              $request='DELETE FROM aat_'.$item.'_total WHERE '.$item.'id'.$ids;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
      }
      if(isset($module['aat_'])) $request='LOCK TABLES aa_raw WRITE,aa_raw_dom WRITE,aat_raw WRITE,aat_raw_dom WRITE';
      else $request='LOCK TABLES aa_raw WRITE,aa_raw_dom WRITE';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //delete deleted ids from raw-log
      $request='SELECT domid,COUNT(*) AS count FROM aa_raw WHERE '.$item.'id'.$ids.' GROUP BY domid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aa_raw WHERE '.$item.'id'.$ids;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          $request='DELETE FROM aa_raw_dom WHERE domid='.$row->domid.' AND count<='.$row->count;
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_affected_rows()) {
              $request='UPDATE aa_raw_dom SET count=count-'.$row->count.' WHERE domid='.$row->domid;
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
      }
      mysql_free_result($result);
      //module's raw-log processing
      if(isset($module['aat_'])) {
          $request='SELECT domid,COUNT(*) AS count FROM aat_raw WHERE '.$item.'id'.$ids.' GROUP BY domid';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $request='DELETE FROM aat_raw WHERE '.$item.'id'.$ids;
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) {
              $request='DELETE FROM aat_raw_dom WHERE domid='.$row->domid.' AND count<='.$row->count;
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              if(!mysql_affected_rows()) {
                  $request='UPDATE aat_raw_dom SET count=count-'.$row->count.' WHERE domid='.$row->domid;
                  $result1=mysql_query($request,$conf->link);
                  if(!$result1) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              }
          }
          mysql_free_result($result);
      }
      $request='UNLOCK TABLES';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  if(!strcmp($item,'ref')) {
      //ONLY for ref - processing domain
      //get dead domain's ids
      $request='SELECT domid AS delid FROM aa_ref_total WHERE id<221 AND refid'.$ids.' GROUP BY domid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $idds='';
      $iddm=array();
      while($row=mysql_fetch_object($result)) {
          if(empty($idds)) $idds=' IN('.$row->delid;
          else $idds.=','.$row->delid;
          $iddm[$row->delid]=1;
      }//while($row=mysql_fetch_object($result))
      if(!empty($idds)) $idds.=')';
      mysql_free_result($result);
      //delete deleted ids from log (not for ref)
      if(!empty($ids)) {
          $request='DELETE FROM aa_'.$item.'_total WHERE '.$item.'id'.$ids;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          //module's log processing
          if(isset($module['aat_'])) {
              $request='DELETE FROM aat_'.$item.'_total WHERE '.$item.'id'.$ids;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
      }//if(!empty($ids))
      //check dead domains on delete
      $request='SELECT domid FROM aa_ref_total WHERE domid'.$idds.' GROUP BY domid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //unset from deleted domains where used in log
      while($row=mysql_fetch_object($result)) unset($iddm[$row->domid]);
      mysql_free_result($result);
      if(isset($module['aat_'])) {
          //check dead domains on delete
          $request='SELECT domid FROM aat_ref_total WHERE domid'.$idds.' GROUP BY domid';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          //unset from deleted domains where used in log
          while($row=mysql_fetch_object($result)) unset($iddm[$row->domid]);
          mysql_free_result($result);
      }
      //form deleted string from domains
      $idds='';
      while($k=key($iddm)) {
          if(empty($idds)) $idds=' IN('.$k;
          else $idds.=','.$k;
          next($iddm);
      }//while($k=key($iddm))
      if(!empty($idds)) $idds.=')';
      //delete deleted ids from domain base (set added=0)
      if(!empty($idds)) {
          $request='UPDATE aa_domains SET domain="" WHERE domid'.$idds;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!empty($idds))
      //delete deleted ids from base (set added=0)
      if(!empty($ids)) {
          $request='UPDATE aa_ref_base SET added=0,url="",count=0 WHERE refid'.$ids;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!empty($ids))
  }//if(!strcmp($item,'ref'))
  else {
      //delete deleted ids from base (set added=0)
      if(!empty($ids)) {
          $request='UPDATE aa_'.$item.'_base SET added=0,name="",count=0 WHERE '.$item.'id'.$ids;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('global.php|restrict|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!empty($ids))
  }//else

}

//load module's variables   /-------------------------------------------------//
function loadmod($pref,$rf,$lf) {
  global $err,$conf,$configdb;

  //load local classes
  require $lf.'common/lconfig.php';

  //local config
  $lconf = & new lconfig($rf,$lf);
  if($err->flag) {$err->reason('global.php|loadmod|constructor of lconfig class has failed');return;}
}

//convert time to string   /--------------------------------------------------//
function timtostr($tim) {

  $th=floor($tim/3600);
  $tim-=$th*3600;
  $tm=floor($tim/60);
  $tim-=$tm*60;
  $ts=$tim;

  return sprintf("%02.0d:%02.0d:%02.0d",$th,$tm,$ts);
}

//convert string to time   /--------------------------------------------------//
function strtotim($tstr) {

  $tarr=preg_split("/:/",$tstr);
  $tim=0;
  $a=1;
  for($i=sizeof($tarr);$i>0;$i--) {
    $loc=$tarr[$i-1];
    if($a>60) {
      if($loc>23) $loc=23;
      elseif($loc<0) $loc=0;
    }
    else {
      if($loc>59) $loc=59;
      elseif($loc<0) $loc=0;
    }

    $tim+=$loc*$a;
    $a*=60;
    if($a>3600) break;
  }

  return $tim;
}

?>
