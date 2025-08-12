<?php

  $mrecinbase=$conf->mrkeyb;
  $mrecinlog=$conf->mrkeyl;
  $request='SELECT GET_LOCK("aa_key",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|search|\'aa_key\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  //ENGINES BASE
  //select engid from engine's base
  $request='SELECT engid FROM aa_eng_base WHERE name="'.$engine.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|search|\''.$engine.'\' engine was not found');return;}
  $row=mysql_fetch_object($result);
  $engid=$row->engid;
  mysql_free_result($result);
  //KEYWORDS BASE
  $sizekeywords=sizeof($keywords);         //for empty check(fastest)
  $numaddb=$sizekeywords;
  $restr=0;
  if(!empty($phrase)) $numaddb++;
  if(!empty($phrase)||$sizekeywords!=0) {
      //form string with keywords for request from base '("keyword",...)'
      $keyids='';
      reset($keywords);
      while($e=each($keywords)) {
          $k=$e[0];
          if(empty($keyids)) $keyids='(name IN ("'.trim(preg_replace("/\"/",'\\\"',$keywords[$k])).'"';
          else $keyids.=',"'.trim(preg_replace("/\"/",'\\\"',$keywords[$k])).'"';
      }//while($k=key($keywords))
      if(!empty($keyids)) $keyids.=') AND flag=1) OR ';
      //select keyid from keyword's base
      $request='SELECT keyid,name FROM aa_key_base WHERE '.$keyids.'(name="'.trim(preg_replace("/\"/",'\\\"',$phrase)).'" AND flag=2)';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $keyids=array();            //keyid's array ([1]=keyid,[2]=keyid,...)
      $keyidstmp=array();         //keywords existing in base ([keyid]=1,[keyid]=1,...)
      $keyidsstr='';              //string with keyid for request from log '(keyid,keyid,...)'
      $i=0;
      while($row=mysql_fetch_object($result)) {
          $keyids[$i++]=$row->keyid;
          $keyidstmp[$row->keyid]=$row->name;
          if(empty($keyidsstr)) $keyidsstr='('.$row->keyid;
          else $keyidsstr.=','.$row->keyid;
      }//while($row=mysql_fetch_object($result))
      mysql_free_result($result);
      // Add new keywords to base if it not exist in base
      $f=0;
      reset($keywords);
      while($e=each($keywords)) {
          $k=$e[0];
          $p=0;
          reset($keyidstmp);
          while($e=key($keyidstmp)) {
              if(!strcmp(trim($keyidstmp[$e]),trim($keywords[$k]))) { $p=1; break; }
              next($keyidstmp);
          }
          if(!$p) {
              if(!$restr) {
                  $delids=array();
                  restrict('key','aa_key_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
                  if($err->flag) {$err->reason('cdb.php|search|\'restrict\' function has failed');return;}
                  $restr=1;
              }
              //find hole in base
              $request='SELECT keyid FROM aa_key_base WHERE name="" ORDER BY keyid ASC LIMIT 1';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              if(mysql_num_rows($result)) {
                  $row=mysql_fetch_row($result);
                  mysql_free_result($result);
                  $keyid=$row[0];
                  $request='UPDATE aa_key_base SET name="'.trim(preg_replace("/\"/",'\\\"',$keywords[$k])).'",added='.$conf->ctime.',count=1,flag=1 WHERE keyid='.$keyid;
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $keyids[$i++]=$keyid;
                  $keyidstmp[$keyid]=$keywords[$k];
                  if(empty($keyidsstr)) $keyidsstr='('.$keyid;
                  else $keyidsstr.=','.$keyid;
                  $this->rawkeyid=$keyid;
              }
              else {
                  mysql_free_result($result);
                  //get last id in base
                  if(!$f) {
                      $request='SELECT MAX(keyid) AS lastid,COUNT(*) AS nrec FROM aa_key_base';
                      $result=mysql_query($request,$conf->link);
                      if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                      $lastid=1; $f=1;
                      while($row=mysql_fetch_object($result)) if($row->nrec) $lastid=$row->lastid+1;
                      mysql_free_result($result);
                  }//if(!$f)
                  $request='INSERT INTO aa_key_base (keyid,flag,added,count,name) VALUES ('.$lastid.',1,'.$conf->ctime.',1,"'.preg_replace("/\"/",'\\\"',$keywords[$k]).'")';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $keyids[$i++]=$lastid;
                  $keyidstmp[$lastid]=$keywords[$k];
                  if(empty($keyidsstr)) $keyidsstr='('.$lastid;
                  else $keyidsstr.=','.$lastid;
                  $this->rawkeyid=$lastid;
                  $lastid++;
              }
          }//if(!isset($keyidstmp[$keywords[$k]])) {
          else {
              $request='UPDATE aa_key_base SET count=count+1 WHERE keyid='.$e;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $this->rawkeyid=$e;
          }
      }//while($k=key($keywords))
      // Add new phrase to base if it not exist in base

      $p=0;
      reset($keyidstmp);
      while($e=key($keyidstmp)) {
          if(!strcmp(trim($keyidstmp[$e]),trim($phrase))) { $p=1; break; }
          next($keyidstmp);
      }
      if(!$p&&!empty($phrase)) {
          if(!$restr) {
              $delids=array();
              restrict('key','aa_key_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|search|\'restrict\' function has failed');return;}
              $restr=1;
          }
          //find hole in base
          $request='SELECT keyid FROM aa_key_base WHERE name="" ORDER BY keyid ASC LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {
              $row=mysql_fetch_row($result);
              mysql_free_result($result);
              $keyid=$row[0];
              $request='UPDATE aa_key_base SET name="'.trim(preg_replace("/\"/",'\\\"',$phrase)).'",added='.$conf->ctime.',count=1,flag=2 WHERE keyid='.$keyid;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $keyids[$i++]=$keyid;
              $keyidstmp[$keyid]=$phrase;
              if(empty($keyidsstr)) $keyidsstr='('.$keyid;
              else $keyidsstr.=','.$keyid;
              $this->rawkeyid=$keyid;
          }
          else {
              mysql_free_result($result);
              if(!$f) {
                  $request='SELECT MAX(keyid) AS lastid,COUNT(*) AS nrec FROM aa_key_base';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $lastid=1; $f=1;
                  while($row=mysql_fetch_object($result)) if($row->nrec) $lastid=$row->lastid+1;
                  mysql_free_result($result);
              }//if(!$f)
              $request='INSERT INTO aa_key_base (keyid,flag,added,count,name) VALUES ('.$lastid.',2,'.$conf->ctime.',1,"'.preg_replace("/\"/",'\\\"',$phrase).'")';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $keyids[$i++]=$lastid;
              $keyidstmp[$lastid]=$phrase;
              if(empty($keyidsstr)) $keyidsstr='('.$lastid;
              else $keyidsstr.=','.$lastid;
              $this->rawkeyid=$lastid;
          }
      }//if(!isset($keyidstmp[$phrase])&&!empty($phrase))
      elseif(!empty($phrase)) {
          $request='UPDATE aa_key_base SET count=count+1 WHERE keyid='.$e;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $this->rawkeyid=$e;
      }
      if(!empty($keyidsstr)) $keyidsstr.=')';
      $sizekeys=sizeof($keyids);
  }//if(!empty($phrase)||$sizekeywords!=0)

  //for raw log
  $this->rawengid=$engid;
//  $this->rawkeyid=0;

  $delids=array();
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // ENGINES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_eng_total WHERE id='.$k.' AND engid='.$engid;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          $request='INSERT INTO aa_eng_total (id,engid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$engid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_eng_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND engid='.$row->engid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      // KEYWORDS TOTAL
      if(!empty($phrase)||$sizekeywords!=0) {
            $keyidstmp=array();              //array where contain keyids which was already update
            // !!! SELECT for get modify !!!
            $request='SELECT * FROM aa_key_total WHERE id='.$k.' AND keyid IN '.$keyidsstr;
            $result1=mysql_query($request,$conf->link);
            if(!$result1) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
            //update existing records in log
            while($row=mysql_fetch_object($result1)) {
                //for raw log
//                $this->rawkeyid=max($this->rawkeyid,$row->keyid);
                $request='UPDATE aa_key_total SET modify='.$conf->ctime;
                $this->ndadd($request,$row,$pagesid[$k],1);
                $request.=' WHERE id='.$row->id.' AND keyid='.$row->keyid;
                $result=mysql_query($request,$conf->link);
                if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                $keyidstmp[$row->keyid]=1;
            }//while($row=mysql_fetch_object($result1))
            mysql_free_result($result1);
            //add new records to log
            for($i=0;$i<$sizekeys;$i++) {
                if(!isset($keyidstmp[$keyids[$i]])) {
                    if(!$restr) {
                        restrict('key','aa_key_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
                        if($err->flag) {$err->reason('cdb.php|search|\'restrict\' function has failed');return;}
                        $restr=1;
                    }
                    if(isset($delids[$keyids[$i]])) { $this->notraw=1; continue; }
                    //for raw log
                    $this->rawkeyid=max($this->rawkeyid,$keyids[$i]);
                    $request='INSERT INTO aa_key_total (id,keyid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$keyids[$i].','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
                    $result=mysql_query($request,$conf->link);
                    if(!$result) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                }//if(!isset($keyidstmp[$keyids[$i]]))
            }//for($i=0;$i<$sizekeys;$i++)
      }//if(!empty($phrase)||$sizekeywords!=0)
      next($pagesid);
  }//while($k=key($pagesid))

  $request='SELECT RELEASE_LOCK("aa_key")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|search|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);

?>
