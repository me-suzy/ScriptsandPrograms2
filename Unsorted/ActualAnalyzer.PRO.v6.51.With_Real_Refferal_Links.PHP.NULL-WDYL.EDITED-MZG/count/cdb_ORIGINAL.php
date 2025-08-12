<?php

class cdb {

var $rawid;
var $rawvid;
var $rawhost;
var $rawrefid;
var $rawlangid;
var $rawlcounid;
var $rawcounid;
var $rawbrid;
var $rawosid;
var $rawresid;
var $rawcolid;
var $rawjsid;
var $rawcookieid;
var $rawjavaid;
var $rawfrstime;
var $rawengid;
var $rawkeyid;
var $rawfrmid;
var $rawzoneid;
var $rawprvid;
var $rawprxid;
var $rawprxip;
var $rawlstime;
var $rawdepth;
var $rawhits;
var $pgtoday;
var $pgtotal;
var $pgonline;
var $pgflag;
var $pgrgb;
var $pgimg;
var $pgcid;
var $rsd;
var $rsw;
var $rsm;
var $rsmas;
var $module;
var $notraw;
var $pgcount;

//===================================================================
function getpages($uid,&$defurl,&$imgid) {        //receive list of page id and groups id containing this page
  global $err,$conf;

  $this->rawid=0;
  $this->rawvid=0;
  $this->rawhost=0;
  $this->rawrefid=0;
  $this->rawlangid=0;
  $this->rawlcounid=0;
  $this->rawcounid=0;
  $this->rawbrid=0;
  $this->rawosid=0;
  $this->rawresid=0;
  $this->rawcolid=0;
  $this->rawjsid=0;
  $this->rawcookieid=0;
  $this->rawjavaid=0;
  $this->rawfrstime=$conf->ctime;
  $this->rawengid=0;
  $this->rawkeyid=0;
  $this->rawfrmid=0;
  $this->rawzoneid=0;
  $this->rawprvid=0;
  $this->rawprxid=0;
  $this->rawprxip=0;
  $this->rawlstime=$conf->ctime;
  $this->rawdepth=1;
  $this->rawhits=1;
  $this->pgtoday=0;
  $this->pgtotal=0;
  $this->pgonline=0;
  $this->pgcount=0;
  $this->pgflag=0;
  $this->pgrgb=0;
  $this->pgimg=0;
  $this->rsm=0;
  $this->rsw=0;
  $this->rsd=0;
  $this->rsmas=array();
  $this->notraw=0;

  $this->module=array();
  if(isset($conf->aa_mod)) {
      $tmp=split('\|',$conf->aa_mod);
      for($i=0;$i<sizeof($tmp);$i++) $this->module[$tmp[$i]]=1;
  }
  $request='SELECT GET_LOCK("aa_lockc",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|getpages|\'cdb\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  //get time of last record
  $request='SELECT MAX(time) AS lt FROM aa_days';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_object($result);
  $numr=mysql_num_rows($result);
  mysql_free_result($result);

  $drop=0;
  //if time of last not today then delete all records from aa_hosts
  if($numr) {
      if($row->lt<$conf->dnum) {
          $request='DELETE FROM aa_hosts';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $this->rsd=1;
          $rbegw=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
          $rbegm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
          if($row->lt<$rbegw) $this->rsw=1;
          if($row->lt<$rbegm) $this->rsm=1;
      }
  }

  $request='LOCK TABLES aa_pages WRITE, aa_groups WRITE, aa_hosts WRITE, aa_hours WRITE, aa_days WRITE, aa_total WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='SELECT RELEASE_LOCK("aa_lockc")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);

  //search page id with uid
  if($uid) $request='SELECT id,uid,added,imgid,flags,rgb FROM aa_pages WHERE uid='.$uid.' AND added!=0';
  else $request='SELECT id,uid,added,imgid,flags,rgb FROM aa_pages WHERE ((url="http://'.$defurl.'" OR url="http://www.'.$defurl.'") OR ((defurl="http://'.$defurl.'" OR defurl="http://www.'.$defurl.'")  AND defpg=1)) AND added!=0';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {  //if page do not exists
      mysql_free_result($result);
      if(strcmp($conf->amode,'auto')||$uid) {
          if($uid) $err->reason('cdb.php|getpages|the page with uid '.$uid.' was not found');
          else $err->reason('cdb.php|getpages|the default page with url "'.$defurl.'" was not found');
          return;
      }
      //if automatically adding of new page
      $fadd=0;
      //get page's folder
      $deffol=$defurl;
      if(preg_match("/[^\/]+(\/[^\/]+)$/i",$deffol)) $deffol=preg_replace("/\/[^\/]+$/i",'',$deffol);
      else $deffol=preg_replace("/\/$/i",'',$deffol);
      if(preg_match("/(index|default)\.(\w)+$/i",$defurl,$matches)) {
          //if 'index' or 'default' page then if exists page with URL=page's folder then edit this record
          $request='SELECT id,uid,added,imgid,flags,rgb FROM aa_pages WHERE (url="http://'.$deffol.'" OR url="http://www.'.$deffol.'") AND added!=0';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {
              $row=mysql_fetch_object($result);
              mysql_data_seek($result,0);
              $request='UPDATE aa_pages SET url="http://'.$defurl.'",defurl="http://'.$deffol.'",defpg=1 WHERE id='.$row->id;
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          else { mysql_free_result($result); $fadd=1; }
      }
      elseif(!preg_match("/\/[^\/]+\.[^\/]+$/i",$defurl,$matches)) { //may be default folder
          $request='SELECT id,uid,added,imgid,flags,rgb FROM aa_pages WHERE (url REGEXP "^http://'.$defurl.'/index." OR url REGEXP "^http://www.'.$defurl.'/index." OR url REGEXP "^http://'.$defurl.'/default." OR url REGEXP "^http://www.'.$defurl.'/default.") AND added!=0 LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {
              $row=mysql_fetch_object($result);
              mysql_data_seek($result,0);
              $request='UPDATE aa_pages SET defpg=1 WHERE id='.$row->id;
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          else { mysql_free_result($result); $fadd=1; }
      }
      //page
      else { $fadd=1; }
      //else add new page with given URL
      if($fadd) {
          $lastuid=1;
          $request='SELECT MAX(uid) AS lastuid,COUNT(*) AS nrec FROM aa_pages';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) { if($row->nrec) $lastuid=$row->lastuid+1; }
          mysql_free_result($result);
            //receive first free id (where added=0)
          $request='SELECT id FROM aa_pages WHERE added=0 ORDER BY id ASC LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {        //if exists free id then place page here (UPDATE)
              $row = mysql_fetch_object($result);
              mysql_free_result($result);
              $id=$row->id;
              $request='UPDATE aa_pages SET name="http://'.$defurl.'",url="http://'.$defurl.'",defurl="http://'.$deffol.'",defpg=0,uid='.$lastuid.',imgid='.$conf->amimg.',flags='.$conf->amstat.',rgb='.$conf->amcolor.',added='.$conf->ctime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,htmin=1000000,rmin=1000000,vmax=0,hsmax=0,htmax=0,rmax=0 WHERE id='.$id;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          else {                        //if do not exists free id (INSERT)
                //receive last id (where max)
              mysql_free_result($result);
              $request='SELECT id FROM aa_pages ORDER BY id DESC LIMIT 1';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              if(mysql_num_rows($result)) {        //if exists last id then id=max+1
                  $row = mysql_fetch_object($result);
                  mysql_free_result($result);
                  $id=$row->id+1;
                   //check page limit
                  if($id>200) {$err->reason('cdb.php|getpages|adding of new page has failed(limit=200)');return;}
              }
              else {                                //if do not exists last id (clear table)
                  mysql_free_result($result);
                  $id=1;
              }
               //insert page into table
              $request='INSERT INTO aa_pages (id,uid,name,url,imgid,flags,rgb,defurl,defpg,added,first_t,last_t,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax) VALUES ('.$id.','.$lastuid.',"http://'.$defurl.'","http://'.$defurl.'",'.$conf->amimg.','.$conf->amstat.','.$conf->amcolor.',"http://'.$deffol.'",0,'.$conf->ctime.',0,0,1000000,0,1000000,0,1000000,0,1000000,0)';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          //add this page to 201 group
          //calculate numbers of field and bit for id
          $fieldn=(int)($id/32)+1;
          $bit=(int)($id%32);
          if($bit) { $bit--; $flag=1073741824>>$bit; }
          else $flag=2147483648;
          $request='UPDATE aa_groups SET flags'.$fieldn.'=flags'.$fieldn.'|'.$flag.' WHERE id=201';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          //get data by this page
          $request='SELECT id,uid,added,imgid,flags,rgb FROM aa_pages WHERE uid='.$lastuid.' AND added!=0';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }

  $row = mysql_fetch_object($result);
  //for raw-log
  $this->rawid=$row->id;
  $this->pgflag=$row->flags;
  $this->pgrgb=$row->rgb;
  $this->pgimg=$row->imgid;
  $this->pgcid=$row->id;
  //result's massive
  $pages[$row->id]=$row->id.'|1';        //key=pages/groups id, value=1
  $imgid=$row->imgid;
  mysql_free_result($result);
  //template for search page by flags in aa_groups
  $id=$row->id;
  $fieldn=(int)($id/32)+1;
  $bit=$id%32;
  if($bit) { $bit--; $flag=1073741824>>$bit; }
  else $flag=2147483648;
  $field='flags'.$fieldn;
  //search groups id that contain this page
  $request='SELECT * FROM aa_groups WHERE added!=0 AND '.$field.'&'.$flag;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //add groups in result
  while($row = mysql_fetch_object($result)) $pages[$row->id]=$row->id.'|1';
  mysql_free_result($result);

  return $pages;
}
//===================================================================
function gethosts($ip,&$pagesid) {                //update/add IP and flags of pages/groups
  global $err,$conf;

  $mrecinlog=$conf->mrhosts;
  //for raw-log
  $this->rawhost=$ip;
  if(!$ip) {
      reset($pagesid);
      while($e=each($pagesid)) $pagesid[$e[0]].='|1';
      return;
  }
  //search record with IP and receive flags
  $request='SELECT flags1,flags2,flags3,flags4,flags5,flags6,flags7,ip FROM aa_hosts WHERE ip='.$ip;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  $numr=mysql_num_rows($result);
  mysql_free_result($result);

  // Templates for update/insert flags in aa_hosts
  $mflag=array();        //key=number of flags, value=flags
  reset($pagesid);
  while($e=each($pagesid)) {
      $id=$e[0];
      $fieldn=(int)($id/32)+1;
      $bit=(int)($id%32);
      if($bit) { $bit--; $flag=1073741824>>$bit; }
      else $flag=2147483648;
      if(!isset($mflag[$fieldn])) $mflag[$fieldn]=$flag;
      if($flag==2147483648 || $mflag[$fieldn]>2147483647) {
          $rlast=$mflag[$fieldn]%2;
          $flast=$flag%2;
          $mflag[$fieldn]=(int)($mflag[$fieldn]/2);
          $flag=(int)($flag/2);
          $mflag[$fieldn]|=$flag;
          $mflag[$fieldn]*=2;
          if($rlast||$flast) $mflag[$fieldn]+=1;
      }
      else $mflag[$fieldn]|=$flag;

      //returned results
      if(!$numr) $pagesid[$e[0]].='|1';
      else {
          if($row[$fieldn-1]&$flag) $pagesid[$e[0]].='|0';        //if this host was already today
          else $pagesid[$e[0]].='|1';                            //if was'nt this host today
      }
  }

  if(!$numr) {                //if this IP was already today
      $request='SELECT COUNT(*) AS nrec FROM aa_hosts';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $row=mysql_fetch_object($result);
      mysql_free_result($result);
      if($row->nrec>$mrecinlog) return;
      //generate query for insert flags
      $k='';
      $v='';
      reset($mflag);
      while($e=each($mflag)) {
          $k.=',flags'.$e[0];
          $v.=','.$e[1];
      }
      $request='INSERT INTO aa_hosts (ip'.$k.') VALUES ('.$ip.$v.')';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else {
      //generate query for update flags
      $k='';
      reset($mflag);
      while($e=each($mflag)) {
          if(empty($k))$k='flags'.$e[0].'=flags'.$e[0].'|'.$e[1];
          else $k.=',flags'.$e[0].'=flags'.$e[0].'|'.$e[1];
      }
      $request='UPDATE aa_hosts SET '.$k.' WHERE ip='.$ip;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|gethosts|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

}
//===================================================================
function updatevis(&$pagesid) {                //update aa_hours,aa_days,aa_total,aa_pages and aa_groups
                                               //increment visitors,hosts,hits and calculate min,max,first and last time
  global $err,$conf;

  $resd=array();
  reset($pagesid);
  while($e=each($pagesid)) {
      $mas=split("\|",$e[1]);
      $tmpht=$mas[1];
      $tmphs=$mas[2];
      $tmpvt=$mas[3];
      $tmpv30=$mas[4];
      $tmpv7=$mas[5];
      $tmpv=$mas[6];
      // aa_days - select last records
      $request='SELECT id,time,visitors_t,visitors_m,visitors_w,hosts,hits FROM aa_days WHERE id='.$e[0].' ORDER BY time DESC LIMIT 1';
      $resultd=mysql_query($request,$conf->link);
      if(!$resultd) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($resultd)) {
          $row=mysql_fetch_object($resultd);
          $resd[$e[0]]['time']=$row->time;
          $resd[$e[0]]['visitors_t']=$row->visitors_t;
          $resd[$e[0]]['visitors_m']=$row->visitors_m;
          $resd[$e[0]]['visitors_w']=$row->visitors_w;
          $resd[$e[0]]['hosts']=$row->hosts;
          $resd[$e[0]]['hits']=$row->hits;
      }
      mysql_free_result($resultd);

      // Update AA_HOURS
      $request='UPDATE aa_hours SET visitors=visitors+'.$tmpv.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->hnum.' AND id='.$e[0];
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
         $request='INSERT INTO aa_hours (time,id,visitors,hosts,hits) VALUES ('.$conf->hnum.','.$e[0].','.$tmpv.','.$tmphs.','.$tmpht.')';
         $result=mysql_query($request,$conf->link);
         if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }

      // Update AA_DAYS
      $up=0;
      if(isset($resd[$e[0]])) {
         if($resd[$e[0]]['time']==$conf->dnum) {
             $up=1;
             $request='UPDATE aa_days SET visitors_t=visitors_t+'.$tmpvt.',visitors_m=visitors_m+'.$tmpv30.',visitors_w=visitors_w+'.$tmpv7.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->dnum.' AND id='.$e[0];
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
         }
         elseif($resd[$e[0]]['time']>$conf->dnum) {
                $request='SELECT * FROM aa_days WHERE time='.$conf->dnum.' AND id='.$e[0];
                $result1=mysql_query($request,$conf->link);
                if(!$result1) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                if(mysql_num_rows($result1)) {
                        $up=1;
                        $request='UPDATE aa_days SET visitors_t=visitors_t+'.$tmpvt.',visitors_m=visitors_m+'.$tmpv30.',visitors_w=visitors_w+'.$tmpv7.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$conf->dnum.' AND id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                        $db=getdate($conf->btime);
                        $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year'],0);
                        $dlast=$conf->dnum*86400+$hbtime;// begin time of last record(day)
                        $dl=getdate($dlast);
                        $mnuml=($dl['year']-$db['year'])*12+$dl['mon']-$db['mon'];// month number of last record(day)
                        $request='UPDATE aa_total SET visitors=visitors+'.$tmpvt.',hosts=hosts+'.$tmphs.',hits=hits+'.$tmpht.' WHERE time='.$mnuml.' AND id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                        $row=mysql_fetch_object($result1);
                        $resd[$e[0]]['time']=$row->time;
                        $resd[$e[0]]['visitors_t']=$row->visitors_t+$tmpvt;
                        $resd[$e[0]]['visitors_m']=$row->visitors_m+$tmpv30;
                        $resd[$e[0]]['visitors_w']=$row->visitors_w+$tmpv7;
                        $resd[$e[0]]['hosts']=$row->hosts+$tmphs;
                        $resd[$e[0]]['hits']=$row->hits+$tmpht;
                        if($e[0]<=200) $table='aa_pages';
                        else $table='aa_groups';
                        $mv=$resd[$e[0]]['visitors_t'];
                        $mhs=$resd[$e[0]]['hosts'];
                        $mht=$resd[$e[0]]['hits'];
                        $mr=$resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t'];
                        $request='UPDATE '.$table.' SET vmin=IF(vmin>'.$mv.','.$mv.',vmin),vmax=IF(vmax<'.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['visitors_t'].',vmax),hsmin=IF(hsmin>'.$mhs.','.$mhs.',hsmin),hsmax=IF(hsmax<'.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hosts'].',hsmax),htmin=IF(htmin>'.$mht.','.$mht.',htmin),htmax=IF(htmax<'.$resd[$e[0]]['hits'].','.$resd[$e[0]]['hits'].',htmax),rmin=IF(rmin>'.$mr.','.$mr.',rmin),rmax=IF(rmax<'.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).','.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).',rmax) WHERE id='.$e[0];
                        $result=mysql_query($request,$conf->link);
                        if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}

                }
                else {
                        $resd[$e[0]]['time']=$conf->dnum;
                        $resd[$e[0]]['visitors_t']=$tmpvt;
                        $resd[$e[0]]['visitors_m']=$tmpv30;
                        $resd[$e[0]]['visitors_w']=$tmpv7;
                        $resd[$e[0]]['hosts']=$tmphs;
                        $resd[$e[0]]['hits']=$tmpht;
                }
                mysql_free_result($result1);
         }
      }//if(mysql_num_rows($resultd))
      if(!isset($resd[$e[0]])||!$up) {        // Add new record into aa_days and aa_total
         $request='INSERT INTO aa_days (time,id,visitors_t,visitors_m,visitors_w,hosts,hits) VALUES ('.$conf->dnum.','.$e[0].','.$tmpvt.','.$tmpv30.','.$tmpv7.','.$tmphs.','.$tmpht.')';
         $result=mysql_query($request,$conf->link);
         if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}

         if(isset($resd[$e[0]])&&!$up) {
             // Update AA_TOTAL if new record was added in AA_DAYS (Last record is included in AA_TOTAL)
             // bmonth of last record by time
             $db=getdate($conf->btime);
             $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year'],0);
             $dlast=$resd[$e[0]]['time']*86400+$hbtime;// begin time of last record(day)
             $dl=getdate($dlast);
             $mnuml=($dl['year']-$db['year'])*12+$dl['mon']-$db['mon'];// month number of last record(day)
             // AA_TOTAL
             $request='UPDATE aa_total SET visitors=visitors+'.$resd[$e[0]]['visitors_t'].',hosts=hosts+'.$resd[$e[0]]['hosts'].',hits=hits+'.$resd[$e[0]]['hits'].' WHERE time='.$mnuml.' AND id='.$e[0];
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
             if(!mysql_affected_rows()) {
                 $request='INSERT INTO aa_total (time,id,visitors,hosts,hits) VALUES ('.$mnuml.','.$e[0].','.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hits'].')';
                 $result=mysql_query($request,$conf->link);
                 if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
             }
          }//if(mysql_num_rows($resultd)&&!$up)
      }//if(!mysql_num_rows($resultd)||!$up)

      //update min and max in pages or groups AND first/last time
      if($e[0]<=200) $table='aa_pages';
      else $table='aa_groups';
      //where insert new record into aa_days (record from aa_days -> aa_total)//update existing record in aa_total
      if(isset($resd[$e[0]])&&!$up) {
          $mv=$resd[$e[0]]['visitors_t'];
          $mhs=$resd[$e[0]]['hosts'];
          $mht=$resd[$e[0]]['hits'];
          $mr=$resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t'];
          if(($conf->dnum-$resd[$e[0]]['time'])>1) {        //if day was passed
              $mv=0;
              $mhs=0;
              $mht=0;
              $mr=0;
          }
          $request='UPDATE '.$table.' SET last_t='.$conf->ctime.',vmin=IF(vmin>'.$mv.','.$mv.',vmin),vmax=IF(vmax<'.$resd[$e[0]]['visitors_t'].','.$resd[$e[0]]['visitors_t'].',vmax),hsmin=IF(hsmin>'.$mhs.','.$mhs.',hsmin),hsmax=IF(hsmax<'.$resd[$e[0]]['hosts'].','.$resd[$e[0]]['hosts'].',hsmax),htmin=IF(htmin>'.$mht.','.$mht.',htmin),htmax=IF(htmax<'.$resd[$e[0]]['hits'].','.$resd[$e[0]]['hits'].',htmax),rmin=IF(rmin>'.$mr.','.$mr.',rmin),rmax=IF(rmax<'.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).','.($resd[$e[0]]['hits']-$resd[$e[0]]['visitors_t']).',rmax) WHERE id='.$e[0];
      }
      else $request='UPDATE '.$table.' SET last_t='.$conf->ctime.',first_t=IF(first_t,first_t,'.$conf->ctime.') WHERE id='.$e[0];
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }//while($e=each($pagesid))

  //if imgid>100
  //  if flag==1 v total
  //  if flag==2 hs total
  //  if flag==3 ht total
  //  if flag==4 v total,today
  //  if flag==5 hs total,today
  //  if flag==6 ht total,today
  //  if flag==7 v total,today,online
  //  if flag==8 hs total,today, v online
  //  if flag==9 ht total,today, v online
  $this->pgcount=1;
  if($this->pgimg>100) {
      //total result
      $request='SELECT visitors_t AS v,hosts AS hs,hits AS ht FROM aa_days WHERE time='.$conf->dnum.' AND id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal=$row->v;
          elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal=$row->hs;
          elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal=$row->ht;
      }
      mysql_free_result($result);
      $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_total WHERE id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($row->nrec) {
              if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal+=$row->v;
              elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal+=$row->hs;
              elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal+=$row->ht;
          }
      }
      mysql_free_result($result);
      //today result
      if($this->pgflag>3) {
          $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;
          $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rbeg.' AND id='.$this->pgcid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) {
              if($row->nrec) {
                  if($this->pgflag==4||$this->pgflag==7) $this->pgtoday=$row->v;
                  elseif($this->pgflag==5||$this->pgflag==8) $this->pgtoday=$row->hs;
                  elseif($this->pgflag==6||$this->pgflag==9) $this->pgtoday=$row->ht;
              }
          }
          mysql_free_result($result);
      }
  }
  // Get statistics for services
  if($conf->services&&$this->rsd) {
      if($conf->sgrpgid<201) $request='SELECT name,url FROM aa_pages WHERE id='.$conf->sgrpgid;
      else $request='SELECT name FROM aa_groups WHERE id='.$conf->sgrpgid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          $this->rsmas[0]=$row->name.'|';
          if($conf->sgrpgid<201) $this->rsmas[0].=$row->url;
      }
      mysql_free_result($result);
      if($conf->sreports&1) {
          if($this->rsd&&!strcmp($conf->stint,'yesterday')) {             // Get statistics for yesterday
              $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;
              $rend=$rbeg+24;
              $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              while($row=mysql_fetch_object($result)) {
                  if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                  else $this->rsmas['1_1']='0|0|0|0';
              }
              mysql_free_result($result);
          }
          elseif($this->rsw&&!strcmp($conf->stint,'lastweek')) {            // Get statistics for last week
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
              $rend=$rbeg+7;
              $request='SELECT SUM(visitors_w) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              while($row=mysql_fetch_object($result)) {
                  if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                  else $this->rsmas['1_1']='0|0|0|0';
              }
              mysql_free_result($result);
          }
          elseif($this->rsm&&!strcmp($conf->stint,'lastmonth')) {           // Get statistics for last month
              $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
              $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
              $request='SELECT SUM(visitors_m) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              while($row=mysql_fetch_object($result)) {
                  if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                  else $this->rsmas['1_1']='0|0|0|0';
              }
              mysql_free_result($result);
          }
      }
  }
  // Delete records where time>72
  //begin hour of (yesterday-24)
  if($conf->hnum>71) {
      $hbday=$conf->hnum-($conf->htime-$conf->dtime)/3600-48;
      $request='DELETE FROM aa_hours WHERE time<'.$hbday;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }//if($conf->hnum>71)

  // Delete records where time>93
  if($conf->dnum>92) {
      $hbday=$conf->dnum-93;
      $request='DELETE FROM aa_days WHERE time<='.$hbday;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $request='UNLOCK TABLES';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updatevis|the request \''.$request.'\' has failed -- '.mysql_error());return;}

}
//===================================================================
function updateref($url,&$pagesid) {
  global $err,$conf,$HTTP_SERVER_VARS;

  $mrecinbase=$conf->mrrefb;
  $mrecinlog=$conf->mrrefl;
  $restr=0;
  //ref domain
  $pdomain='';
  $page=preg_replace("/[\?|&|#].*$/i",'',$url);
  if(preg_match("/([^\/]+)/i",$page,$matches)) $pdomain=$matches[1];
  $pdomain=preg_replace("/(:\d+)*$/",'',$pdomain);
  //our domain
  $domain=$conf->url;
  if(preg_match("/^(http:\/\/)([^\/]+)/i",$domain,$matches)) $domain=$matches[2];
  elseif(isset($GLOBALS['SERVER_NAME'])) $domain=$GLOBALS['SERVER_NAME'];
  elseif(isset($HTTP_SERVER_VARS['SERVER_NAME'])) $domain=$HTTP_SERVER_VARS['SERVER_NAME'];
  elseif(isset($GLOBALS['HTTP_HOST'])) $domain=$GLOBALS['HTTP_HOST'];
  elseif(isset($HTTP_SERVER_VARS['HTTP_HOST'])) $domain=$HTTP_SERVER_VARS['HTTP_HOST'];
  else {$err->reason('cdb.php|updateref|can\'t get current domain name');return;}
  $domain=preg_replace("/^(www\.)/i",'',$domain);
  $domain=preg_replace("/(:\d+)*$/",'',$domain);
  if(!strcmp($pdomain,$domain)) $cd=1;                //internal
  else {
      $cd=0;                                        //external
      if(file_exists('./data/aliases.php')) {
        require './data/aliases.php';
        //check aliases
        if(isset($alias)) {
          reset($alias);
          while($e=each($alias)) {
            if(!strcasecmp($pdomain,$e[1])) {
                $cd=1;
                break;
            }
          }
        }
      }
  }

  $request='SELECT GET_LOCK("aa_ref",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|updateref|\'aa_ref\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  // Check and add to aa_ref_base the new referrer and get referrer_id.
  $request='SELECT refid,url FROM aa_ref_base WHERE url="'.$url.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {                //referrer is not exists
       mysql_free_result($result);
       $delids=array();
       restrict('ref','aa_ref_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
       if($err->flag) {$err->reason('cdb.php|updateref|\'restrict\' function has failed');return;}
       $restr=1;
       $request='SELECT refid FROM aa_ref_base WHERE url="" ORDER BY refid ASC LIMIT 1';
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       if(mysql_num_rows($result)) {
           $row=mysql_fetch_row($result);
           mysql_free_result($result);
           $refid=$row[0];
           if($cd) $request='UPDATE aa_ref_base SET url="'.$url.'",added='.$conf->ctime.',count=1,flag=1 WHERE refid='.$refid;
           else $request='UPDATE aa_ref_base SET url="'.$url.'",added='.$conf->ctime.',count=1,flag=2 WHERE refid='.$refid;
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
       else {
           mysql_free_result($result);
           $request='SELECT refid FROM aa_ref_base ORDER BY refid DESC LIMIT 1';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
           //search max id
           if(mysql_num_rows($result)) {
               $row=mysql_fetch_row($result);
               mysql_free_result($result);
               if($row[0]==65535){$err->reason('cdb.php|updateref|the limit of referrers (65535 referrers) is achieved');return;}
               $refid=$row[0]+1;
           }
           else {
               mysql_free_result($result);
               $refid=1;
           }
           if($cd) $request='INSERT INTO aa_ref_base (refid,flag,added,count,url) VALUES ('.$refid.',1,'.$conf->ctime.',1,"'.$url.'")';
           else $request='INSERT INTO aa_ref_base (refid,flag,added,count,url) VALUES ('.$refid.',2,'.$conf->ctime.',1,"'.$url.'")';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
   }
   else {                                        //referrer is exists
       $row=mysql_fetch_row($result);
       $refid=$row[0];
       mysql_free_result($result);
       $request='UPDATE aa_ref_base SET count=count+1 WHERE refid='.$refid;
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
   }

  // Check and add to aa_domains the new domain and get domain_id.
  $request='SELECT domid,domain FROM aa_domains WHERE domain="'.$pdomain.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {             //referrer is not exists
       mysql_free_result($result);
       $request='SELECT domid FROM aa_domains WHERE domain="" ORDER BY domid ASC LIMIT 1';
       $result=mysql_query($request,$conf->link);
       if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       if(mysql_num_rows($result)) {
           $row=mysql_fetch_row($result);
           mysql_free_result($result);
           $domid=$row[0];
           $request='UPDATE aa_domains SET domain="'.$pdomain.'" WHERE domid='.$domid;
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
       else {
           mysql_free_result($result);
           $request='SELECT domid FROM aa_domains ORDER BY domid DESC LIMIT 1';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
           //search max id
           if(mysql_num_rows($result)) {
               $row=mysql_fetch_row($result);
               mysql_free_result($result);
               if($row[0]==65535){$err->reason('cdb.php|updateref|the limit of domains (65535 domains) is achieved');return;}
               $domid=$row[0]+1;
           }
           else {
               mysql_free_result($result);
               $domid=1;
           }
           $request='INSERT INTO aa_domains (domid,domain) VALUES ('.$domid.',"'.$pdomain.'")';
           $result=mysql_query($request,$conf->link);
           if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
       }
   }
   else {                                    //referrer is exists
       $row=mysql_fetch_row($result);
       $domid=$row[0];
       mysql_free_result($result);
   }
  //for raw-log
  $this->rawrefid=$refid;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // FRAMES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_ref_total WHERE id='.$k.' AND refid='.$refid;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          if(!$restr) {
              $delids=array();
              restrict('ref','aa_ref_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|updateref|\'restrict\' function has failed');return;}
              $restr=1;
              if(isset($delids[$refid])) { $this->notraw=1; break; }
          }
          $request='INSERT INTO aa_ref_total (id,refid,domid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$refid.','.$domid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_ref_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND refid='.$row->refid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      next($pagesid);
  }//while($k=key($pagesid))

  // Get statistics for services
  $this->pgcount=1;
  if($conf->services&&$this->rsd) {
      if($conf->sreports&2) {
          if($this->rsd&&!strcmp($conf->stint,'yesterday')) {             // Get statistics for yesterday
              $dy=getdate($conf->dtime-40000);
              $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
              $svalues='SUM(IF(modify>='.$conf->dtime.',vy,vt)) AS v,SUM(IF(modify>='.$conf->dtime.',hsy,hst)) AS hs,SUM(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS r,SUM(IF(modify>='.$conf->dtime.',hty,htt)) AS ht';
              $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0)))';
              $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $i=1;
              while($row=mysql_fetch_object($result)) {
                  $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                  $i++;
              }
              mysql_free_result($result);
          }
          elseif($this->rsw&&!strcmp($conf->stint,'lastweek')) {            // Get statistics for last week
              $svalues='SUM(IF(modify>='.$conf->wtime.',vlw,vw)) AS v,SUM(IF(modify>='.$conf->wtime.',hslw,hsw)) AS hs,SUM(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS r,SUM(IF(modify>='.$conf->wtime.',htlw,htw)) AS ht';
              $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0)))';
              $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $i=1;
              while($row=mysql_fetch_object($result)) {
                  $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                  $i++;
              }
              mysql_free_result($result);
          }
          elseif($this->rsm&&!strcmp($conf->stint,'lastmonth')) {           // Get statistics for last month
              $svalues='SUM(IF(modify>='.$conf->mtime.',vlm,vm)) AS v,SUM(IF(modify>='.$conf->mtime.',hslm,hsm)) AS hs,SUM(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS r,SUM(IF(modify>='.$conf->mtime.',htlm,htm)) AS ht';
              $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0)))';
              $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $i=1;
              while($row=mysql_fetch_object($result)) {
                  $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                  $i++;
              }
              mysql_free_result($result);
          }
      }
  }

  $request='SELECT RELEASE_LOCK("aa_ref")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|updateref|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);
}
//===================================================================
function updatelc(&$pagesid,$language,$country) {                    // update languages and countries logs
  global $err,$conf;

  $langid=255;
  $lc=0;
  $counid=1000;
  $language=strtolower($language);
  $country=strtolower($country);
  // parse language from format: language-country
  $langtmp=split('-',$language);
  if(!isset($langtmp[0])) $langtmp[0]='undefined';         //language
  elseif(empty($langtmp[0])) $langtmp[0]='undefined';
  if(!isset($langtmp[1])) $langtmp[1]='undefined';         //country
  elseif(empty($langtmp[1])) $langtmp[1]='undefined';

  $year=(int)(date('y',$conf->ctime));

  $request='LOCK TABLES aa_lang_total WRITE, aa_coun_total WRITE, aa_lang_base READ, aa_coun_base READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if(strcmp($langtmp[0],'undefined')) {
      $request='SELECT langid FROM aa_lang_base WHERE sname="'.$langtmp[0].'"';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
           $row=mysql_fetch_row($result);
           $langid=$row[0];
      }
      mysql_free_result($result);
  }
  if(strcmp($country,'undefined')||strcmp($langtmp[1],'undefined')) {
      $request='SELECT counid,sname FROM aa_coun_base WHERE sname="'.$country.'" OR sname="'.$langtmp[1].'"';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
           while($row=mysql_fetch_object($result)) {
               if(!strcmp($row->sname,$langtmp[1])) { if($row->counid!=1000) $lc=$row->counid; }
               if(!strcmp($row->sname,$country)) $counid=$row->counid;
           }
      }
      mysql_free_result($result);
  }

  //for raw-log
  $this->rawlangid=$langid;
  $this->rawlcounid=$lc;
  $this->rawcounid=$counid;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      if($k<201) { next($pagesid); continue; }
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // LANGUAGES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_lang_total WHERE id='.$k.' AND langid='.$langid.' AND counid='.$lc;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          $request='INSERT INTO aa_lang_total (id,langid,counid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$langid.','.$lc.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_lang_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND langid='.$langid.' AND counid='.$lc;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      // COUNTRIES TOTAL
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_coun_total WHERE id='.$k.' AND counid='.$counid;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          $request='INSERT INTO aa_coun_total (id,counid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$counid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_coun_total SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND counid='.$counid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      next($pagesid);
  }//while($k=key($pagesid))
  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updatelc|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function updatest(&$pagesid,$color,$browser,$os,$js,$res,$cookie,$java) {          // update standard log
  global $err,$conf;

  //color id
  if(strcmp($color,'undefined')) $colid=$color;
  else $colid=1000;
  //cookie id
  if(!strcmp($cookie,'undefined')) $cookieid=5003;
  elseif($cookie==2) $cookieid=5002;
  else $cookieid=5001;
  //java id
  if(!strcmp($java,'undefined')) $javaid=6003;
  elseif($java==2) $javaid=6002;
  else $javaid=6001;
  //browser id
  $query='';
  if(strcmp($browser,'undefined')) $query.='(fname="'.$browser.'" AND stid>1000 AND stid<2000)';
  $brid=2000;
  //OS id
  if(strcmp($os,'undefined')) {
      if(empty($query)) $query.='(fname="'.$os.'" AND stid>2000 AND stid<3000)';
      else $query.=' OR (fname="'.$os.'" AND stid>2000 AND stid<3000)';;
  }
  $osid=3000;
  //JavaScript id
  if(strcmp($js,'undefined')) {
      $jsid=0;
      if(empty($query)) $query.='(stname="'.$js.'" AND stid>3000 AND stid<4000)';
      else $query.=' OR (stname="'.$js.'" AND stid>3000 AND stid<4000)';
  }
  else $jsid=4000;
  //resolution id
  if(strcmp($res,'undefined')) {
      $resid=0;
      if(empty($query)) $query.='(stname="'.$res.'" AND stid>4000 AND stid<5000)';
      else $query.=' OR (stname="'.$res.'" AND stid>4000 AND stid<5000)';
  }
  else $resid=5000;
  $year=(int)(date('y',$conf->ctime));

  $request='LOCK TABLES aa_st_base WRITE, aa_st_total WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!empty($query)) {
      $request='SELECT stid FROM aa_st_base WHERE '.$query;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          while($row=mysql_fetch_object($result)) {
              if($row->stid>1000 && $row->stid<=2000) $brid=$row->stid;
              elseif($row->stid>2000 && $row->stid<=3000) $osid=$row->stid;
              elseif($row->stid>3000 && $row->stid<=4000) $jsid=$row->stid;
              elseif($row->stid>4000 && $row->stid<=5000) $resid=$row->stid;
          }//while($row=mysql_fetch_object($result))
      }//if(mysql_num_rows($result))
      mysql_free_result($result);
      $query='';
      $num=0;
      if(!$jsid){
          $num++;
          $query.='IF(stid>3000&&stid<4000,1,';
      }//if(!$jsid)
      if(!$resid){
          $num++;
          $query.='IF(stid>4000&&stid<5000,2,';
      }//if(!$resid)
      if($num) {
          $query.='0';
          for($i=0;$i<$num;$i++) $query.=')';
          $request='SELECT '.$query.' AS item,MAX(stid) AS maxid FROM aa_st_base WHERE stid>1000 AND stid<5000 GROUP BY item';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {
              while($row=mysql_fetch_object($result)) {
                  if($row->item==1) {
                      $jsid=$row->maxid+1;
                      $request='INSERT INTO aa_st_base (stid,stname) VALUES ('.$jsid.',"'.$js.'")';
                      $result1=mysql_query($request,$conf->link);
                      if(!$result1) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  }//if($row->item==1)
                  elseif($row->item==2) {
                      $resid=$row->maxid+1;
                      $request='INSERT INTO aa_st_base (stid,stname) VALUES ('.$resid.',"'.$res.'")';
                      $result1=mysql_query($request,$conf->link);
                      if(!$result1) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  }//elseif($row->item==2)
              }//while($row=mysql_fetch_object($result))
          }//if(mysql_num_rows($result))
          mysql_free_result($result);
          if(!$jsid){
              $jsid=3001;
              $request='INSERT INTO aa_st_base (stid,stname) VALUES ('.$jsid.',"'.$js.'")';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//if(!$jsid)
          if(!$resid){
              $resid=4001;
              $request='INSERT INTO aa_st_base (stid,stname) VALUES ('.$resid.',"'.$res.'")';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//if(!$resid)
      }//if($num)
  }//if(!empty($query))
  //for raw-log
  $this->rawbrid=$brid;
  $this->rawosid=$osid;
  $this->rawjsid=$jsid;
  $this->rawresid=$resid;
  $this->rawcolid=$colid;
  $this->rawcookieid=$cookieid-5000;
  $this->rawjavaid=$javaid-6000;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      if($k<201) { next($pagesid); continue; }
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // Update or add new color depth in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$colid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$colid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add new browser in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$brid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$brid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add new OS in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$osid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$osid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add new JavaScript in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$jsid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$jsid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add new resolution in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$resid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$resid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add cookie in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$cookieid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$cookieid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      // Update or add java in aa_st_total
      $request='UPDATE aa_st_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND stid='.$javaid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows()) {
          $request='INSERT INTO aa_st_total (id,stid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$javaid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      next($pagesid);
  }//while($k=key($pagesid))
  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updatest|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function getrangeid($tm,&$retval) { //1-aa_times, 2-aa_returns

  $size=sizeof($retval);
  for($i=1;$i<$size;$i++)
      if($tm<$retval[$i]) return $i;
  return 18;
}
//===================================================================
function ndadd(&$request,&$row,$stat,$num) {
  global $conf;

  $mas=split("\|",$stat);
  $cht=$mas[$num]; $chs=$mas[$num+1]; $cvt=$mas[$num+2]; $cv30=$mas[$num+3]; $cv7=$mas[$num+4]; $cv=$mas[$num+5];
  $db=getdate($conf->btime);
  if(!$row->modify) $dc=getdate($conf->btime);
  else $dc=getdate($row->modify);
  $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year'],0);
  $ldtime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  $ldnum=(int)sprintf("%d",($ldtime-$hbtime)/$conf->time1);
  $begw=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
  $beglw=$begw-7;
  $begm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
  $beglm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  // YEAR
  $request.=',v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht;
  // DAY
  if($conf->dnum!=$ldnum) {
      $request.=',vt='.$cv.',hst='.$chs.',htt='.$cht;
      if($conf->dnum-$ldnum==1) $request.=',vy='.$row->vt.',hsy='.$row->hst.',hty='.$row->htt;
      else $request.=',vy=0,hsy=0,hty=0';
  }
  else $request.=',vt=vt+'.$cv.',hst=hst+'.$chs.',htt=htt+'.$cht;
  // WEEK
  if($ldnum<$begw) {
      $request.=',vw='.$cv7.',hsw='.$chs.',htw='.$cht;
      if($ldnum>=$beglw) $request.=',vlw='.$row->vw.',hslw='.$row->hsw.',htlw='.$row->htw;
      else $request.=',vlw=0,hslw=0,htlw=0';
  }
  else $request.=',vw=vw+'.$cv7.',hsw=hsw+'.$chs.',htw=htw+'.$cht;
  // MONTH
  if($ldnum<$begm) {
      $request.=',vm='.$cv30.',hsm='.$chs.',htm='.$cht;
      if($ldnum>=$beglm) $request.=',vlm='.$row->vm.',hslm='.$row->hsm.',htlm='.$row->htm;
      else $request.=',vlm=0,hslm=0,htlm=0';
  }
  else $request.=',vm='.$row->vm.'+'.$cv30.',hsm='.$row->hsm.'+'.$chs.',htm='.$row->htm.'+'.$cht;
}
//===================================================================
function delndadd(&$request,&$row,$time,$param,$prev,$curr,$prevstat,$pnum,$currstat,$cnum) {
  global $conf;
  //$param - value from MySQL result ($row->rangeid ...) which switch prev/curr
  //$prevval - $param value from which decrement
  //$currval - $param value to which increment
  //$row - MySQL result
  //$prevstat - data which decrement
  //$currstat - data which increment
  //$time - last visit time
  $db=getdate($conf->btime);
  if(!$row->modify) $dc=getdate($conf->btime);
  else $dc=getdate($row->modify);
  $hbtime=mktime(0,0,0,$db['mon'],$db['mday'],$db['year'],0);
  $ldtime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  $ldnum=(int)sprintf("%d",($ldtime-$hbtime)/$conf->time1);
  $dc=getdate($time);
  $ldtime=mktime(0,0,0,$dc['mon'],$dc['mday'],$dc['year'],0);
  $tdnum=(int)sprintf("%d",($ldtime-$hbtime)/$conf->time1);
  $begw=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->wtime)/$conf->time1);
  $beglw=$begw-7;
  $begm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
  $beglm=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $tyear=(int)(date('y',$time))-(int)(date('y',$conf->btime))+1;
  $pht=0;$phs=0;$pvt=0;$pv30=0;$pv7=0;$pv=0;
  $cht=0;$chs=0;$cvt=0;$cv30=0;$cv7=0;$cv=0;
  if(!empty($prevstat)) {
      $mas=split("\|",$prevstat);
      $pht=$mas[$pnum]; $phs=$mas[$pnum+1]; $pvt=$mas[$pnum+2]; $pv30=$mas[$pnum+3]; $pv7=$mas[$pnum+4]; $pv=$mas[$pnum+5];
  }
  if(!empty($currstat)) {
      $mas=split("\|",$currstat);
      $cht=$mas[$cnum]; $chs=$mas[$cnum+1]; $cvt=$mas[$cnum+2]; $cv30=$mas[$cnum+3]; $cv7=$mas[$cnum+4]; $cv=$mas[$cnum+5];
  }
  // DAY
  if($conf->dnum!=$ldnum) {
      if($param==$prev) {
          if($conf->dnum-$tdnum==1) {$row->vy=$row->vt-$pv;$row->hsy=$row->hst-$phs;$row->hty=$row->htt-$pht;}
          else {$row->vy=0;$row->hsy=0;$row->hty=0;}
      }
      else {
          if($conf->dnum-$ldnum==1) {$row->vy=$row->vt;$row->hsy=$row->hst;$row->hty=$row->htt;}
          else {$row->vy=0;$row->hsy=0;$row->hty=0;}
      }
      $row->vt=$cv;$row->hst=$chs;$row->htt=$cht;
  }
  else {
      if($param==$prev) {
          if($conf->dnum-$tdnum==1) {$row->vy=$row->vy-$pv;$row->hsy=$row->hsy-$phs;$row->hty=$row->hty-$pht;}
          elseif($conf->dnum==$tdnum) {$row->vt=$row->vt-$pv;$row->hst=$row->hst-$phs;$row->htt=$row->htt-$pht;}
      }
      if($param==$curr) {$row->vt=$row->vt+$cv;$row->hst=$row->hst+$chs;$row->htt=$row->htt+$cht;}
  }
  $request.=',vt='.$row->vt.',hst='.$row->hst.',htt='.$row->htt;
  $request.=',vy='.$row->vy.',hsy='.$row->hsy.',hty='.$row->hty;
  // WEEK
  if($ldnum<$begw) {
      if($param==$prev) {
          if($tdnum>=$beglw) {$row->vlw=$row->vw-$pv7;$row->hslw=$row->hsw-$phs;$row->htlw=$row->htw-$pht;}
          else {$row->vlw=0;$row->hslw=0;$row->htlw=0;}
      }
      else {
          if($ldnum>=$beglw) {$row->vlw=$row->vw;$row->hslw=$row->hsw;$row->htlw=$row->htw;}
          else {$row->vlw=0;$row->hslw=0;$row->htlw=0;}
      }
      $row->vw=$cv7;$row->hsw=$chs;$row->htw=$cht;
  }
  else {
      if($param==$prev) {
          if($tdnum>=$beglw && $tdnum<$begw) {$row->vlw=$row->vlw-$pv7;$row->hslw=$row->hslw-$phs;$row->htlw=$row->htlw-$pht;}
          elseif($tdnum>=$begw) {$row->vw=$row->vw-$pv7;$row->hsw=$row->hsw-$phs;$row->htw=$row->htw-$pht;}
      }
      if($param==$curr) {$row->vw=$row->vw+$cv7;$row->hsw=$row->hsw+$chs;$row->htw=$row->htw+$cht;}
  }
  $request.=',vw='.$row->vw.',hsw='.$row->hsw.',htw='.$row->htw;
  $request.=',vlw='.$row->vlw.',hslw='.$row->hslw.',htlw='.$row->htlw;
  // MONTH
  if($ldnum<$begm) {
      if($param==$prev) {
          if($tdnum>=$beglm) {$row->vlm=$row->vm-$pv30;$row->hslm=$row->hsm-$phs;$row->htlm=$row->htm-$pht;}
          else {$row->vlm=0;$row->hslm=0;$row->htlm=0;}
      }
      else {
          if($ldnum>=$beglm) {$row->vlm=$row->vm;$row->hslm=$row->hsm;$row->htlm=$row->htm;}
          else {$row->vlm=0;$row->hslm=0;$row->htlm=0;}
      }
      $row->vm=$cv30;$row->hsm=$chs;$row->htm=$cht;
  }
  else {
      if($param==$prev) {
          if($tdnum>=$beglm && $tdnum<$begm) {$row->vlm=$row->vlm-$pv30;$row->hslm=$row->hslm-$phs;$row->htlm=$row->htlm-$pht;}
          elseif($tdnum>=$begm) {$row->vm=$row->vm-$pv30;$row->hsm=$row->hsm-$phs;$row->htm=$row->htm-$pht;}
      }
      if($param==$curr) {$row->vm=$row->vm+$cv30;$row->hsm=$row->hsm+$chs;$row->htm=$row->htm+$cht;}
  }
  $request.=',vm='.$row->vm.',hsm='.$row->hsm.',htm='.$row->htm;
  $request.=',vlm='.$row->vlm.',hslm='.$row->hslm.',htlm='.$row->htlm;
  // YEAR
  if($param==$prev) {
      eval("\$row->v$tyear=\$row->v$tyear-\$pvt;");
      eval("\$row->hs$tyear=\$row->hs$tyear-\$phs;");
      eval("\$row->ht$tyear=\$row->ht$tyear-\$pht;");
  }
  if($param==$curr) {
      eval("\$row->v$lyear=\$row->v$lyear+\$cvt;");
      eval("\$row->hs$lyear=\$row->hs$lyear+\$chs;");
      eval("\$row->ht$lyear=\$row->ht$lyear+\$cht;");
  }
  if($lyear!=$tyear && $param==$prev) eval("\$request.=',v$tyear='.\$row->v$tyear.',hs$tyear='.\$row->hs$tyear.',ht$tyear='.\$row->ht$tyear;");
  eval("\$request.=',v$lyear='.\$row->v$lyear.',hs$lyear='.\$row->hs$lyear.',ht$lyear='.\$row->ht$lyear;");
}
//===================================================================
function points($flag,$time,&$previd,&$currid) {          // update aa_points
  global $err,$conf;
  // flag=1:    add $currstat to exit, entry, single TO $curid;
  // flag=2:    add $currstat to exit TO $currid; delete $prevstat from exit, single FROM $previd;
  // flag=3:    add $currstat to exit TO $currid; delete $prevstat from exit FROM $previd;
  $request='LOCK TABLES aa_points WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if($flag>1) {
      reset($previd);
      while($k=key($previd)) {
          if($flag==2) $request='SELECT * FROM aa_points WHERE (id='.$k.' AND flag=3) OR (id='.$k.' AND flag=2)';
          elseif($flag==3) $request='SELECT * FROM aa_points WHERE id='.$k.' AND flag=2';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result1)) {
              $request='UPDATE aa_points SET modify='.$conf->ctime;
              $this->delndadd($request,$row,$time,$row->id,$k,0,$previd[$k],1,'',0);
              $request.=' WHERE id='.$row->id.' AND flag='.$row->flag;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
          next($previd);
      }
  }
  reset($currid);
  while($k=key($currid)) {
      if($flag==1) $request='SELECT * FROM aa_points WHERE id='.$k;
      else $request='SELECT * FROM aa_points WHERE id='.$k.' AND flag=2';
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result1)) {
          $request='UPDATE aa_points SET modify='.$conf->ctime;
          $this->ndadd($request,$row,$currid[$k],1);
          $request.=' WHERE id='.$row->id.' AND flag='.$row->flag;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//while($row=mysql_fetch_object($result1))
      mysql_free_result($result1);
      next($currid);
  }
  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|points|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function vector(&$msourid,&$mdestid) {          // update aa_vectors
  global $err,$conf;

  $request='LOCK TABLES aa_vectors WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|vector|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  reset($msourid);
  while($sourid=key($msourid)) {
      reset($mdestid);
      while($destid=key($mdestid)) {
          if(($sourid<201&&$destid>200)||($sourid>200&&$destid<201)) { next($mdestid); continue;}
          // !!! SELECT for get modify !!!
          $request='SELECT * FROM aa_vectors WHERE sourid='.$sourid.' AND destid='.$destid;
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('cdb.php|vector|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_num_rows($result1)) {
              mysql_free_result($result1);
              $mas=split("\|",$mdestid[$destid]);
              $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
              $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
              $request='INSERT INTO aa_vectors (sourid,destid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$sourid.','.$destid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|vector|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          else {
              while($row=mysql_fetch_object($result1)) {
                 $request='UPDATE aa_vectors SET modify='.$conf->ctime;
                 $this->ndadd($request,$row,$mdestid[$destid],1);
                 $request.=' WHERE sourid='.$row->sourid.' AND destid='.$row->destid;
                 $result=mysql_query($request,$conf->link);
                 if(!$result) {$err->reason('cdb.php|vector|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              }//while($row=mysql_fetch_object($result1))
              mysql_free_result($result1);
          }//else
          next($mdestid);
      }
      next($msourid);
  }
  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|vector|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function depthtime(&$ppagegid,&$previd,&$currid,&$rtimes,$quantity,$prevtime,$vflag) {          // update aa_depthes
  global $err,$conf;
  //$previd['id']='id|hits|hosts|vt|v30|v7|v';   - "ïðåäûäóùèé ìàññèâ"
  //$currid['id']='id|hits|hosts|vt|v30|v7|v';   - "òåêóùèé ìàññèâ"
  //$ppagegid['id']='id|time|tlen|num|hits|hosts|vt|v30|v7|v';   - "ïîñåùåííûå ãðóïïû"
  //$vflags=1/0;
  //$rtimes['id']=time;
  $this->rawlstime=$prevtime?$prevtime:$conf->ctime;
  $this->rawhits=$quantity+1;
  if(isset($ppagegid[201])) {
      $mas=split("\|",$ppagegid[201]);
      if($vflag==1) $this->rawdepth=$mas[3];
      else $this->rawdepth=$mas[3]+1;
  }
  require './data/bases/retval.php';
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  $request='LOCK TABLES aa_depthes WRITE,aa_times WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //TIME SECTION - aa_times
  //if quantity!=0
  //  cycle previd
  //    for page: update/insert new record to aa_times (previd)hits|hosts|vt|v30|v7|v under rangeid=(ctime-prevtime)
  //    if quantity==1
  //      only for group: add/update to ppagegid |id|ctime|(ctime-prevtime)|0|(previd)hits|hosts|vt|v30|v7|v
  //    else quantity>1
  //      if isset(ppagegid[id])
  //        decrement aa_times under rangeid=tlen --(ppagegid)hits|hosts|vt|v30|v7|v with check of day/week/month with time from ppagegid
  //        go to new day/week/month with time from ppagegid
  //        increment aa_times under rangeid=tlen+(ctime-prevtime) ++(ppagegid)hits|hosts|vt|v30|v7|v
  //        modify ppagegid set |time|tlen|=|ctime|tlen+(ctime-prevtime)|
  //      else
  //        only for group: update/insert new record to aa_times (previd)hits|hosts|vt|v30|v7|v under rangeid=(ctime-prevtime)
  //        only for group: add to ppagegid |id|ctime|(ctime-prevtime)|0|(previd)hits|hosts|vt|v30|v7|v
  if($quantity!=0) {
      reset($previd);
      while($k=key($previd)) {
          $mas=split('\|',$previd[$k]);
          $pid=$mas[0]; $pht=$mas[1]; $phs=$mas[2]; $pvt=$mas[3]; $pv30=$mas[4]; $pv7=$mas[5]; $pv=$mas[6];
          $rangeid=$this->getrangeid($conf->ctime-$prevtime,$retval);
          // Page processing
          if($k<201) {
              // !!! SELECT for get modify !!!
              $request='SELECT * FROM aa_times WHERE id='.$k.' AND rangeid='.$rangeid.' AND flag=1';
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              if(!mysql_num_rows($result1)) {
                  mysql_free_result($result1);
                  $request='INSERT INTO aa_times (flag,id,rangeid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES (1,'.$k.','.$rangeid.','.$conf->ctime.','.$pv.','.$phs.','.$pht.','.$pv7.','.$phs.','.$pht.','.$pv30.','.$phs.','.$pht.','.$pvt.','.$phs.','.$pht.')';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              }
              else {
                  while($row=mysql_fetch_object($result1)) {
                     $request='UPDATE aa_times SET modify='.$conf->ctime;
                     $this->ndadd($request,$row,$previd[$k],1);
                     $request.=' WHERE id='.$row->id.' AND rangeid='.$row->rangeid.' AND flag=1';
                     $result=mysql_query($request,$conf->link);
                     if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  }//while($row=mysql_fetch_object($result1))
                  mysql_free_result($result1);
              }//else
              next($previd);
              continue;
          }//if($k<201)
          // Group processing
          if($quantity==1) {
              if(isset($ppagegid[$k])) {
                  $mas=split('\|',$ppagegid[$k]);
                  $pgid=$mas[0]; $pgtm=$mas[1]; $pgtl=$mas[2]; $pgqn=$mas[3]; $pght=$mas[4]; $pghs=$mas[5]; $pgvt=$mas[6]; $pgv30=$mas[7]; $pgv7=$mas[8]; $pgv=$mas[9];
                  $ppagegid[$k]=''.$k.'|'.$conf->ctime.'|'.($conf->ctime-$prevtime).'|'.$pgqn.'|'.$pght.'|'.$pghs.'|'.$pgvt.'|'.$pgv30.'|'.$pgv7.'|'.$pgv;
              }
              else $ppagegid[$k]=''.$k.'|'.$conf->ctime.'|'.($conf->ctime-$prevtime).'|0|'.$pht.'|'.$phs.'|'.$pvt.'|'.$pv30.'|'.$pv7.'|'.$pv;
              $prangeid=0;
              $crangeid=$this->getrangeid($conf->ctime-$prevtime,$retval);
          }
          else {
              if(isset($ppagegid[$k])) {
                  $mas=split('\|',$ppagegid[$k]);
                  $pgid=$mas[0]; $pgtm=$mas[1]; $pgtl=$mas[2]; $pgqn=$mas[3]; $pght=$mas[4]; $pghs=$mas[5]; $pgvt=$mas[6]; $pgv30=$mas[7]; $pgv7=$mas[8]; $pgv=$mas[9];
                  $prangeid=$this->getrangeid($pgtl,$retval);
                  $crangeid=$this->getrangeid($pgtl+($conf->ctime-$prevtime),$retval);
                  $ppagegid[$k]=''.$pgid.'|'.$conf->ctime.'|'.($pgtl+($conf->ctime-$prevtime)).'|'.$pgqn.'|'.$pght.'|'.$pghs.'|'.$pgvt.'|'.$pgv30.'|'.$pgv7.'|'.$pgv;
              }
              else {
                  $pght=$pht; $pghs=$phs; $pgvt=$pvt; $pgv30=$pv30; $pgv7=$pv7; $pgv=$pv;
                  $prangeid=0;
                  $crangeid=$this->getrangeid($conf->ctime-$prevtime,$retval);
                  $ppagegid[$k]=''.$pid.'|'.$conf->ctime.'|'.($conf->ctime-$prevtime).'|0|'.$pht.'|'.$phs.'|'.$pvt.'|'.$pv30.'|'.$pv7.'|'.$pv;
              }
          }
          if($crangeid!=$prangeid) {
              $request='SELECT * FROM aa_times WHERE id='.$k.' AND (rangeid='.$prangeid.' OR rangeid='.$crangeid.') AND flag=1';
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              $uc=0;
              while($row=mysql_fetch_object($result1)) {
                  $request='UPDATE aa_times SET modify='.$conf->ctime;
                  if($row->rangeid==$prangeid) $this->delndadd($request,$row,$pgtm,$row->rangeid,$prangeid,0,$ppagegid[$k],4,'',0);
                  elseif($row->rangeid==$crangeid) $this->ndadd($request,$row,$ppagegid[$k],4);
                  $request.=' WHERE id='.$row->id.' AND rangeid='.$row->rangeid.' AND flag=1';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  if($row->rangeid==$crangeid) $uc=1;
              }//while($row=mysql_fetch_object($result1))
              mysql_free_result($result1);
              if(!$uc) {
                  $request='INSERT INTO aa_times (flag,id,rangeid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES (1,'.$k.','.$crangeid.','.$conf->ctime.','.$pgv.','.$pghs.','.$pght.','.$pgv7.','.$pghs.','.$pght.','.$pgv30.','.$pghs.','.$pght.','.$pgvt.','.$pghs.','.$pght.')';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              }//if(!$uc)
          }
          next($previd);
      }//while($k=key($previd))
  }//if($quantity!=0)
  // RETURN and DEPTH
  //cycle currid
  //  RETURN
  //  if isset(rtimes[currid])
  //    update/insert record to aa_times under flag=2 and rangeid=ctime-rtimes[currid] (curr)hits|hosts|vt|v30|v7|v
  //  DEPTH
  //  if vflags>1
  //    if currid>200
  //      if isset(ppagegid[currid])
  //        decrement aa_depthes under pages=(ppagegid)quantity --(ppagegid)hits|hosts|vt|v30|v7|v with check of day/week/month with time from ppagegid
  //        go to new day/week/month with time from ppagegid
  //        increment aa_depthes under pages=(ppagegid)quantity+1 ++(ppagegid)hits|hosts|vt|v30|v7|v
  //        modify ppagegid set |time|tlen|quantity|=|ctime|tlen|quantity+1|
  //      else
  //        update/insert record to ppagegid under pages=1 (curr)hits|hosts|vt|v30|v7|v
  //        add new record to ppagegid[]=currid|ctime|0|1|(curr)hits|hosts|vt|v30|v7|v
  reset($currid);
  while($k=key($currid)) {
      $mas=split("\|",$currid[$k]);
      $cid=$mas[0]; $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      //RETURN
      if(isset($rtimes[$k])) {
          //for raw log
          if($k<201) $this->rawfrstime=$rtimes[$k]>0?$rtimes[$k]:$conf->ctime;
          // !!! SELECT for get modify !!!
          $rangeid=$this->getrangeid($conf->ctime-$rtimes[$k],$retval);
          $request='SELECT * FROM aa_times WHERE id='.$k.' AND rangeid='.$rangeid.' AND flag=2';
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_num_rows($result1)) {
              mysql_free_result($result1);
              $request='INSERT INTO aa_times (flag,id,rangeid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES (2,'.$k.','.$rangeid.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//if(!mysql_num_rows($result1))
          else {
              while($row=mysql_fetch_object($result1)) {
                 $request='UPDATE aa_times SET modify='.$conf->ctime;
                 $this->ndadd($request,$row,$currid[$k],1);
                 $request.=' WHERE id='.$row->id.' AND rangeid='.$row->rangeid.' AND flag=2';
                 $result=mysql_query($request,$conf->link);
                 if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              }//while($row=mysql_fetch_object($result1))
              mysql_free_result($result1);
          }//else
      }//if(isset($rtimes[$k]))
      // DEPTH: ONLY FOR GROUP AND $vflag==0
      if($vflag==1||$k<201) { next($currid); continue; }
      if(isset($ppagegid[$k])) {
          $mas=split("\|",$ppagegid[$k]);
          $pgid=$mas[0]; $pgtm=$mas[1]; $pgtl=$mas[2]; $pgqn=$mas[3]; $pght=$mas[4]; $pghs=$mas[5]; $pgvt=$mas[6]; $pgv30=$mas[7]; $pgv7=$mas[8]; $pgv=$mas[9];
          $prev=$pgqn;
          $curr=$pgqn+1;
          $ppagegid[$k]=''.$pgid.'|'.$pgtm.'|'.$pgtl.'|'.($pgqn+1).'|'.$pght.'|'.$pghs.'|'.$pgvt.'|'.$pgv30.'|'.$pgv7.'|'.$pgv;
          //for raw log
      }//if(isset($ppagegid[$k]))
      else {
          $prev=0;
          $curr=1;
          $pght=$cht; $pghs=$chs; $pgvt=$cvt; $pgv30=$cv30; $pgv7=$cv7; $pgv=$cv;
          $ppagegid[$k]=''.$k.'|'.$conf->ctime.'|0|1|'.$cht.'|'.$chs.'|'.$cvt.'|'.$cv30.'|'.$cv7.'|'.$cv;
      }//else
      if($curr!=$prev) {
            // !!! SELECT for get modify !!!
            $request='SELECT * FROM aa_depthes WHERE id='.$k.' AND (pages='.$prev.' OR pages='.$curr.')';
            $result1=mysql_query($request,$conf->link);
            if(!$result1) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
            $uc=0;
                while($row=mysql_fetch_object($result1)) {
                    $request='UPDATE aa_depthes SET modify='.$conf->ctime;
                    if($row->pages==$prev) $this->delndadd($request,$row,$pgtm,$row->pages,$prev,0,$ppagegid[$k],4,'',0);
                    elseif($row->pages==$curr) $this->ndadd($request,$row,$ppagegid[$k],4);
                    $request.=' WHERE id='.$row->id.' AND pages='.$row->pages;
                    $result=mysql_query($request,$conf->link);
                    if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                    if($row->pages==$curr) $uc=1;
                }//while($row=mysql_fetch_object($result1))
                mysql_free_result($result1);
            if(!$uc) {
                $request='INSERT INTO aa_depthes (id,pages,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$curr.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
                $result=mysql_query($request,$conf->link);
                if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
            }//if(!$uc)
      }
      next($currid);
  }//while($k=key($currid))
  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|depthtime|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function search(&$pagesid,$engine,$phrase,&$keywords) {
  global $err,$conf;
  require './count/cdb/search.php';
}
//===================================================================
function frames(&$pagesid,$url) {
  global $err,$conf;
  require './count/cdb/frames.php';
}
//===================================================================
function zones(&$pagesid,$zone) {
  global $err,$conf;
  //for raw log
  $this->rawzoneid=$zone;
  // ONLY FOR GROUP
  $request='LOCK TABLES aa_zones WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|zones|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  reset($pagesid);
  while($k=key($pagesid)) {
      if($k<201) { next($pagesid); continue; }
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      // !!! SELECT for get modify !!!
      $request='SELECT * FROM aa_zones WHERE id='.$k.' AND zoneid='.$zone;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|zones|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_num_rows($result1)) {
          mysql_free_result($result1);
          $request='INSERT INTO aa_zones (id,zoneid,modify,vt,hst,htt,vw,hsw,htw,vm,hsm,htm,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$zone.','.$conf->ctime.','.$cv.','.$chs.','.$cht.','.$cv7.','.$chs.','.$cht.','.$cv30.','.$chs.','.$cht.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|zones|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_num_rows($result1))
      else {
          while($row=mysql_fetch_object($result1)) {
             $request='UPDATE aa_zones SET modify='.$conf->ctime;
             $this->ndadd($request,$row,$pagesid[$k],1);
             $request.=' WHERE id='.$row->id.' AND zoneid='.$row->zoneid;
             $result=mysql_query($request,$conf->link);
             if(!$result) {$err->reason('cdb.php|zones|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }//while($row=mysql_fetch_object($result1))
          mysql_free_result($result1);
      }//else
      next($pagesid);
  }//while($k=key($pagesid))
  $request='UNLOCK TABLES';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|zones|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function providers(&$pagesid,$provider) {
  global $err,$conf;

  $mrecinbase=$conf->mrprvb;
  $mrecinlog=$conf->mrprvl;
  $restr=0;
  $request='SELECT GET_LOCK("aa_prv",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|providers|\'aa_prv\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  //PROVIDERS BASE
  //select prvid from provider's base
  $request='SELECT prvid FROM aa_prv_base WHERE name="'.$provider.'" AND counid='.$this->rawcounid;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $delids=array();
      restrict('prv','aa_prv_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
      if($err->flag) {$err->reason('cdb.php|providers|\'restrict\' function has failed');return;}
      $restr=1;
      $request='SELECT prvid FROM aa_prv_base WHERE name="" ORDER BY prvid ASC LIMIT 1';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          $row=mysql_fetch_row($result);
          mysql_free_result($result);
          $prvid=$row[0];
          $request='UPDATE aa_prv_base SET name="'.$url.'",added='.$conf->ctime.',count=1 WHERE prvid='.$prvid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {
          mysql_free_result($result);
          $request='SELECT MAX(prvid) AS lastid,COUNT(*) AS nrec FROM aa_prv_base';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $prvid=1;
          $row=mysql_fetch_object($result);
          if($row->nrec) $prvid=$row->lastid+1;
          mysql_free_result($result);
          $request='INSERT INTO aa_prv_base (prvid,added,count,name,counid) VALUES ('.$prvid.','.$conf->ctime.',1,"'.$provider.'",'.$this->rawcounid.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }
  else {
      $row=mysql_fetch_object($result);
      $prvid=$row->prvid;
      mysql_free_result($result);
      $request='UPDATE aa_prv_base SET count=count+1 WHERE prvid='.$prvid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  //for raw log
  $this->rawprvid=$prvid;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  //PROVIDERS TOTAL
  reset($pagesid);
  while($k=key($pagesid)) {
      if($k<201) { next($pagesid); continue; }
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      $request='UPDATE aa_prv_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND prvid='.$prvid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows($conf->link)) {
          if(!$restr) {
              $delids=array();
              restrict('prv','aa_prv_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|providers|\'restrict\' function has failed');return;}
              $restr=1;
              if(isset($delids[$prvid])) { $this->notraw=1; break; }
          }
          $request='INSERT INTO aa_prv_total (id,prvid,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$prvid.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      next($pagesid);
  }//while($k=key($pagesid))
  $request='SELECT RELEASE_LOCK("aa_prv")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);
}
//===================================================================
function proxy(&$pagesid,$ip,$host) {
  global $err,$conf;

  $mrecinbase=$conf->mrprxb;
  $mrecinlog=$conf->mrprxl;
  $restr=0;
  if(!strcmp(trim(long2ip($ip)),trim($host))) $host='';
  $request='SELECT GET_LOCK("aa_prx",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('cdb.php|proxy|\'aa_prx\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  //PROXY BASE
  $request='SELECT prxid FROM aa_prx_base WHERE name="'.$host.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $delids=array();
      restrict('prx','aa_prx_base',$mrecinbase,sizeof($pagesid),$this->module,$delids);
      if($err->flag) {$err->reason('cdb.php|proxy|\'restrict\' function has failed');return;}
      $restr=1;
      $request='SELECT prxid FROM aa_prx_base WHERE name="" ORDER BY prxid ASC LIMIT 1';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          $row=mysql_fetch_row($result);
          mysql_free_result($result);
          $prxid=$row[0];
          $request='UPDATE aa_prx_base SET name="'.$host.'",added='.$conf->ctime.',count=1 WHERE prxid='.$prxid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {
          mysql_free_result($result);
          $request='SELECT MAX(prxid) AS lastid,COUNT(*) AS nrec FROM aa_prx_base';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $prxid=1;
          $row=mysql_fetch_object($result);
          if($row->nrec) $prxid=$row->lastid+1;
          mysql_free_result($result);
          $request='INSERT INTO aa_prx_base (prxid,added,count,name) VALUES ('.$prxid.','.$conf->ctime.',1,"'.$host.'")';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }
  else {
      $row=mysql_fetch_object($result);
      $prxid=$row->prxid;
      mysql_free_result($result);
      $request='UPDATE aa_prx_base SET count=count+1 WHERE prxid='.$prxid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|providers|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  //for raw log
  $this->rawprxid=$prxid;
  $this->rawprxip=$ip;

  $lyear=(int)(date('y',$conf->ctime))-(int)(date('y',$conf->btime))+1;
  //PROXY TOTAL
  reset($pagesid);
  while($k=key($pagesid)) {
      if($k<201) { next($pagesid); continue; }
      $mas=split("\|",$pagesid[$k]);
      $cht=$mas[1]; $chs=$mas[2]; $cvt=$mas[3]; $cv30=$mas[4]; $cv7=$mas[5]; $cv=$mas[6];
      $request='UPDATE aa_prx_total SET v'.$lyear.'=v'.$lyear.'+'.$cvt.',hs'.$lyear.'=hs'.$lyear.'+'.$chs.',ht'.$lyear.'=ht'.$lyear.'+'.$cht.' WHERE id='.$k.' AND prxid='.$prxid.' AND ip='.$ip;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(!mysql_affected_rows($conf->link)) {
          if(!$restr) {
              $delids=array();
              restrict('prx','aa_prx_total',$mrecinlog,sizeof($pagesid),$this->module,$delids);
              if($err->flag) {$err->reason('cdb.php|proxy|\'restrict\' function has failed');return;}
              $restr=1;
              if(isset($delids[$prxid])) { $this->notraw=1; break; }
          }
          $request='INSERT INTO aa_prx_total (id,prxid,ip,v'.$lyear.',hs'.$lyear.',ht'.$lyear.') VALUES ('.$k.','.$prxid.','.$ip.','.$cvt.','.$chs.','.$cht.')';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }//if(!mysql_affected_rows($result))
      next($pagesid);
  }//while($k=key($pagesid))
  $request='SELECT RELEASE_LOCK("aa_prx")';
  $reslock=mysql_query($request,$conf->link);
  if(!$reslock) {$err->reason('cdb.php|proxy|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);
}
//===================================================================
function updateraw($vid,$domain) {          // update raw log
  global $err,$conf;

  $maxraw=$conf->mrrawl;
  if(!strcmp(trim(long2ip($this->rawhost)),$domain)) $domain='';
  $request='LOCK TABLES aa_raw_dom WRITE,aa_raw WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  // ADD RECORD TO LOG
  $request='SELECT MAX(num) FROM aa_raw';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(mysql_num_rows($result)) {
      $row=mysql_fetch_row($result);
      $num=$row[0]+1;
  }
  else $num=1;
  mysql_free_result($result);
  if($num>$maxraw) {
      $request='SELECT domid,COUNT(*) AS count FROM aa_raw WHERE num<='.($num-$maxraw).' GROUP BY domid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aa_raw WHERE num<='.($num-$maxraw);
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          $request='DELETE FROM aa_raw_dom WHERE domid='.$row->domid.' AND count<='.$row->count;
          $result1=mysql_query($request,$conf->link);
          if(!$result1) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_affected_rows()) {
              $request='UPDATE aa_raw_dom SET count=count-'.$row->count.' WHERE domid='.$row->domid;
              $result1=mysql_query($request,$conf->link);
              if(!$result1) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
      }
      mysql_free_result($result);
  }
  if($this->notraw) {
      $request="UNLOCK TABLES";
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      return;
  }
  // DOMAIN BASE - get domain's id
  $request='SELECT domid FROM aa_raw_dom WHERE domain="'.$domain.'"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $domid=1;
      $request='SELECT MAX(domid) AS domid,COUNT(*) AS nrec FROM aa_raw_dom';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $row=mysql_fetch_object($result);
      if($row->nrec) $domid=$row->domid+1;
      mysql_free_result($result);
      $request='INSERT INTO aa_raw_dom (domid,domain,count) VALUES ('.$domid.',"'.$domain.'",1)';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else {
      $row=mysql_fetch_object($result);
      mysql_free_result($result);
      $domid=$row->domid;
      $request='UPDATE aa_raw_dom SET count=count+1 WHERE domid='.$domid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $request='INSERT INTO aa_raw (num,time,id,vid,host,domid,refid,langid,lcounid,counid,brid,osid,resid,colid,jsid,cookieid,javaid,frstime,lstime,engid,keyid,frmid,zoneid,prvid,prxid,prxip,depth,hits) VALUES ('.$num.','.$conf->ctime.','.$this->rawid.','.$vid.','.$this->rawhost.','.$domid.','.$this->rawrefid.','.$this->rawlangid.','.$this->rawlcounid.','.$this->rawcounid.','.$this->rawbrid.','.$this->rawosid.','.$this->rawresid.','.$this->rawcolid.','.$this->rawjsid.','.$this->rawcookieid.','.$this->rawjavaid.','.$this->rawfrstime.','.$this->rawlstime.','.$this->rawengid.','.$this->rawkeyid.','.$this->rawfrmid.','.$this->rawzoneid.','.$this->rawprvid.','.$this->rawprxid.','.$this->rawprxip.','.$this->rawdepth.','.$this->rawhits.')';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $this->pgcount=1;
  if($this->pgimg>100&&$this->pgflag>6) {
      $request='SELECT vid FROM aa_raw WHERE time>='.($conf->ctime-$conf->tonline).' AND id='.$this->pgcid.' GROUP BY vid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $this->pgonline=mysql_num_rows($result);
      mysql_free_result($result);
  }

  $request="UNLOCK TABLES";
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('cdb.php|updateraw|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function getstat(&$flag,&$tot,&$tod,&$onl,&$color) {
  global $err,$conf;

  if($this->pgcount==0&&$this->pgimg>100) {
      $request='LOCK TABLES aa_hours WRITE, aa_days WRITE, aa_total WRITE, aa_raw WRITE';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      //total result
      $request='SELECT visitors_t AS v,hosts AS hs,hits AS ht FROM aa_days WHERE time='.$conf->dnum.' AND id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal=$row->v;
          elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal=$row->hs;
          elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal=$row->ht;
      }
      mysql_free_result($result);
      $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_total WHERE id='.$this->pgcid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          if($row->nrec) {
              if($this->pgflag==1||$this->pgflag==4||$this->pgflag==7) $this->pgtotal+=$row->v;
              elseif($this->pgflag==2||$this->pgflag==5||$this->pgflag==8) $this->pgtotal+=$row->hs;
              elseif($this->pgflag==3||$this->pgflag==6||$this->pgflag==9) $this->pgtotal+=$row->ht;
          }
      }
      mysql_free_result($result);

      //today result
      if($this->pgflag>3) {
          $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600;
          $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rbeg.' AND id='.$this->pgcid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) {
              if($row->nrec) {
                  if($this->pgflag==4||$this->pgflag==7) $this->pgtoday=$row->v;
                  elseif($this->pgflag==5||$this->pgflag==8) $this->pgtoday=$row->hs;
                  elseif($this->pgflag==6||$this->pgflag==9) $this->pgtoday=$row->ht;
              }
          }
          mysql_free_result($result);
      }
      if($this->pgflag>6) {
          $request='SELECT vid FROM aa_raw WHERE time>='.($conf->ctime-$conf->tonline).' AND id='.$this->pgcid.' GROUP BY vid';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $this->pgonline=mysql_num_rows($result);
          mysql_free_result($result);
      }
      $request="UNLOCK TABLES";
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('cdb.php|getstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $flag=$this->pgflag;
  $tot=$this->pgtotal;
  $tod=$this->pgtoday;
  $onl=$this->pgonline;
  $color=$this->pgrgb;
}
//===================================================================
function servdata(&$res) {
  global $err,$conf;

  if($this->pgcount==0) {
      // Get statistics for services
      if($conf->services&&$this->rsd) {
          $request='LOCK TABLES aa_groups WRITE, aa_pages WRITE, aa_hours WRITE, aa_days WRITE';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if($conf->sgrpgid<201) $request='SELECT name,url FROM aa_pages WHERE id='.$conf->sgrpgid;
          else $request='SELECT name FROM aa_groups WHERE id='.$conf->sgrpgid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) {
              $this->rsmas[0]=$row->name.'|';
              if($conf->sgrpgid<201) $this->rsmas[0].=$row->url;
          }
          mysql_free_result($result);
          if($conf->sreports&1) {
              if($this->rsd&&!strcmp($conf->stint,'yesterday')) {             // Get statistics for yesterday
                  $rbeg=$conf->hnum-($conf->htime-$conf->dtime)/3600-24;
                  $rend=$rbeg+24;
                  $request='SELECT SUM(visitors) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_hours WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  while($row=mysql_fetch_object($result)) {
                      if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                      else $this->rsmas['1_1']='0|0|0|0';
                  }
                  mysql_free_result($result);
              }
              elseif($this->rsw&&!strcmp($conf->stint,'lastweek')) {            // Get statistics for last week
                  $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lwtime)/$conf->time1);
                  $rend=$rbeg+7;
                  $request='SELECT SUM(visitors_w) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  while($row=mysql_fetch_object($result)) {
                      if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                      else $this->rsmas['1_1']='0|0|0|0';
                  }
                  mysql_free_result($result);
              }
              elseif($this->rsm&&!strcmp($conf->stint,'lastmonth')) {           // Get statistics for last month
                  $rbeg=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->lmtime)/$conf->time1);
                  $rend=$conf->dnum-sprintf("%.0f",($conf->dtime-$conf->mtime)/$conf->time1);
                  $request='SELECT SUM(visitors_m) AS v,SUM(hosts) AS hs,SUM(hits) AS ht,COUNT(*) AS nrec FROM aa_days WHERE time>='.$rbeg.' AND time<'.$rend.' AND id='.$conf->sgrpgid;
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  while($row=mysql_fetch_object($result)) {
                      if($row->nrec) $this->rsmas['1_1']=$row->v.'|'.$row->hs.'|'.($row->ht-$row->v).'|'.$row->ht;
                      else $this->rsmas['1_1']='0|0|0|0';
                  }
                  mysql_free_result($result);
              }
          }
          $request="UNLOCK TABLES";
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      if($conf->services&&$this->rsd) {
          $request='SELECT GET_LOCK("aa_ref",10)';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_num_rows($result)) {$err->reason('cdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          $row=mysql_fetch_row($result);
          if(!$row[0]) {$err->reason('cdb.php|servdata|\'aa_ref\' is busy -- '.mysql_error());return;}
          mysql_free_result($result);
          if($conf->sreports&2) {
              if($this->rsd&&!strcmp($conf->stint,'yesterday')) {             // Get statistics for yesterday
                  $dy=getdate($conf->dtime-40000);
                  $ydtime=mktime(0,0,0,$dy['mon'],$dy['mday'],$dy['year'],0);
                  $svalues='SUM(IF(modify>='.$conf->dtime.',vy,vt)) AS v,SUM(IF(modify>='.$conf->dtime.',hsy,hst)) AS hs,SUM(IF(modify>='.$conf->dtime.',hty-vy,htt-vt)) AS r,SUM(IF(modify>='.$conf->dtime.',hty,htt)) AS ht';
                  $where=' AND ((modify>='.$conf->dtime.' AND (vy!=0 OR hsy!=0 OR hty!=0)) OR ((modify>='.$ydtime.' AND modify<'.$conf->dtime.') AND (vt!=0 OR hst!=0 OR htt!=0)))';
                  $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $i=1;
                  while($row=mysql_fetch_object($result)) {
                      $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                      $i++;
                  }
                  mysql_free_result($result);
              }
              elseif($this->rsw&&!strcmp($conf->stint,'lastweek')) {            // Get statistics for last week
                  $svalues='SUM(IF(modify>='.$conf->wtime.',vlw,vw)) AS v,SUM(IF(modify>='.$conf->wtime.',hslw,hsw)) AS hs,SUM(IF(modify>='.$conf->wtime.',htlw-vlw,htw-vw)) AS r,SUM(IF(modify>='.$conf->wtime.',htlw,htw)) AS ht';
                  $where=' AND ((modify>='.$conf->wtime.' AND (vlw!=0 OR hslw!=0 OR htlw!=0)) OR ((modify>='.$conf->lwtime.' AND modify<'.$conf->wtime.') AND (vw!=0 OR hsw!=0 OR htw!=0)))';
                  $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $i=1;
                  while($row=mysql_fetch_object($result)) {
                      $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                      $i++;
                  }
                  mysql_free_result($result);
              }
              elseif($this->rsm&&!strcmp($conf->stint,'lastmonth')) {           // Get statistics for last month
                  $svalues='SUM(IF(modify>='.$conf->mtime.',vlm,vm)) AS v,SUM(IF(modify>='.$conf->mtime.',hslm,hsm)) AS hs,SUM(IF(modify>='.$conf->mtime.',htlm-vlm,htm-vm)) AS r,SUM(IF(modify>='.$conf->mtime.',htlm,htm)) AS ht';
                  $where=' AND ((modify>='.$conf->mtime.' AND (vlm!=0 OR hslm!=0 OR htlm!=0)) OR ((modify>='.$conf->lmtime.' AND modify<'.$conf->mtime.') AND (vm!=0 OR hsm!=0 OR htm!=0)))';
                  $request='SELECT aa_domains.domain AS name,'.$svalues.' FROM aa_ref_total LEFT JOIN aa_domains ON aa_ref_total.domid=aa_domains.domid WHERE aa_ref_total.id='.$conf->sgrpgid.$where.' GROUP BY aa_ref_total.domid ORDER BY ht DESC,aa_domains.domain ASC LIMIT 3';
                  $result=mysql_query($request,$conf->link);
                  if(!$result) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
                  $i=1;
                  while($row=mysql_fetch_object($result)) {
                      $this->rsmas['2_'.$i]=$row->name.'|'.$row->v.'|'.$row->hs.'|'.$row->r.'|'.$row->ht;
                      $i++;
                  }
                  mysql_free_result($result);
              }
          }
          $request='SELECT RELEASE_LOCK("aa_ref")';
          $reslock=mysql_query($request,$conf->link);
          if(!$reslock) {$err->reason('cdb.php|servdata|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          mysql_free_result($reslock);
      }
  }
  if(sizeof($this->rsmas)<2) $res=array();
  else $res=array_merge($res,$this->rsmas);
}
//===================================================================

}

?>
