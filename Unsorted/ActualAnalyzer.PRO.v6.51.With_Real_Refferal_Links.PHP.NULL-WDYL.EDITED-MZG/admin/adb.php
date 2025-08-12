<?php

class adb {

//===================================================================
function getgrs() {
  global $conf,$err;

  $request='LOCK TABLES aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $mas=array();
  $request='SELECT name,id FROM aa_groups WHERE id=201';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) return $mas;
  $row=mysql_fetch_object($result);
  $mas['201']=$row->name;
  mysql_free_result($result);

  $request='SELECT id,name,added FROM aa_groups WHERE added!=0 AND id!=201 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  return $mas;
}//function getgrs
//===================================================================
function getpages() {
  global $conf,$err;

  $request='LOCK TABLES aa_pages READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $mas=array();
  $request='SELECT id,name,added FROM aa_pages WHERE added!=0 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  return $mas;
}//function getpages
//===================================================================
function getparampg($page_id,&$name,&$imgid,&$uid,&$url) {
  global $conf,$err;

  $request='SELECT name,imgid,uid,url FROM aa_pages WHERE id='.$page_id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getparampg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $err->reason('adb.php|getparampg|the page with id='.$page_id.' is not found');
      $name='';
      $imgid='';
      $uid='';
      $url='';
  }
  else {
      $row=mysql_fetch_object($result);
      mysql_free_result($result);
      $name=$row->name;
      $imgid=$row->imgid;
      $uid=$row->uid;
      $url=$row->url;
  }
}
//===================================================================
function getnamegrpg($page_id,&$name) {
  global $conf,$err;

  if($page_id<201) $table='aa_pages';
  else $table='aa_groups';

  $request='SELECT name,id FROM '.$table.' WHERE id='.$page_id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|getnamegrpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $err->reason('adb.php|getnamegrpg|the page with id='.$page_id.' is not found');
      $name='';
  }
  else {
      $row=mysql_fetch_object($result);
      mysql_free_result($result);
      $name=$row->name;
  }

}
//===================================================================
function grpg(&$vars,$what) {                //display list of pages(0) or groups(1)
  global $err,$conf;

  if($what==1) {
      require './style/'.$conf->style.'/template/at_grs.php';
      $table='aa_groups';
  }
  else {
      require './style/'.$conf->style.'/template/at_pgs.php';
      $table='aa_pages';
  }

  $request='LOCK TABLES '.$table.' READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive the name of main group and/or list of groups/pages (flags for calculate of amoung of pages)
  if($what==1) {
      $request='SELECT name,flags1,flags2,flags3,flags4,flags5,flags6,flags7 FROM '.$table.' WHERE id=201 ORDER BY name ASC';
      $result201=mysql_query($request,$conf->link);
      if(!$result201) {$err->reason('adb.php|grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT id,name,flags1,flags2,flags3,flags4,flags5,flags6,flags7 FROM '.$table.' WHERE added!=0 AND id!=201 ORDER BY name ASC';
  }
  else $request='SELECT id,name,url,uid FROM '.$table.' WHERE added!=0 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|grpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //display top
  $nrect=mysql_num_rows($result);
  if($what==1) {
      $nrect++;
      $vars['HEADER']=_INFABOUTGR;
      $vars['PAGE']=_GROUP;
  }
  else {
      $vars['HEADER']=_INFABOUTPGS;
      $vars['PAGE']=_PAGE;
  }
  if($nrect) $vars['RANGE']='1 - '.$nrect.' '._OUTOF.' '.$nrect;
  else $vars['RANGE']='0 - 0 '._OUTOF.' 0';
  $vars['SHOWING']=_SHOWING.' '.$nrect.' '._ITEM_S;
  $vars['ACTION']=_ACTION;
  tparse($top,$vars);

  //display center
  $num=1;
  if($what==1) {                //groups
      //display main group with the quantity of pages in it
      $row=mysql_fetch_row($result201);
      $vars['NUM']=$num;
      $vars['PAGE']=$row[0];
      $cnt=0;
      for($i=1;$i<8;$i++) {
          $tmp=(float)$row[$i];
          while($tmp) {
              if($tmp%2) $cnt++;
              $tmp=intval($tmp/2);
          }
      }
      $vars['PAGESCOUNT']='- '.$cnt.' '._PAGE_S;
      $vars['HTML']=_INDHTML;
      $vars['EDIT']=_EDIT;
      $vars['DELETE']=_DELETE;
      tparse($center201,$vars);
      //display list of the other groups with the quantity of pages in its
      $num++;
      mysql_free_result($result201);
      while($row=mysql_fetch_row($result)) {
          $vars['NUM']=$num;
          $vars['PAGE']=$row[1];
          $vars['PGID']=$row[0];
          $cnt=0;
          for($i=2;$i<9;$i++) {
                    $tmp=(float)$row[$i];
                    while($tmp) {
                        if($tmp%2) $cnt++;
                        $tmp=(int)($tmp/2);
                    }
          }
          $vars['PAGESCOUNT']='- '.$cnt.' '._PAGE_S;
          $vars['HTML']=_INDHTML;
          $vars['EDIT']=_EDIT;
          $vars['DELETE']=_DELETE;
          tparse($center,$vars);
          $num++;
      }
  }
  else {                //pages
      //display list of pages
      if($nrect) {                //if do not empty list of pages
          while($row=mysql_fetch_object($result)) {
              $vars['NUM']=$num;
              $fname=$row->name;
              if(strlen($fname)>_AS_PGS) $sname=substr($fname,0,_AS_PGS-3).'...';
              else $sname=$fname;
              $vars['PAGE']=$fname;
              $vars['PAGESHORT']=$sname;
              $vars['URL']=$row->url;
              $vars['PGID']=$row->id;
              $vars['HTML']=_INDHTML;
              $vars['EDIT']=_EDIT;
              $vars['DELETE']=_DISCONNECT;
              tparse($center,$vars);
              $num++;
          }
      }
      else {                                     //if empty list of pages
              $vars['TEXT']=_NORECORDS;
              tparse($empty,$vars);
      }
  }
  mysql_free_result($result);
  //display bottom
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

}
//===================================================================
function editpg(&$vars,$id,$imgid,$color,$dflag,$defpg) {                //display form for add(0) or edit(id) page
  global $err,$conf;

  $request='LOCK TABLES aa_pages READ,aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|editpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if($id!=0) {
      //receive name and url of edited page
      $request='SELECT name,url,imgid,flags,rgb,defpg FROM aa_pages WHERE id='.$id;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|editpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {
          $row = mysql_fetch_object($result);
          $rname=$row->name;
          $rurl=$row->url;
          $rimgid=$row->imgid;
          $rflag=$row->flags;
          $rrgb=$row->rgb;
          $rdefpg=$row->defpg;
          mysql_free_result($result);
      }
      else { mysql_free_result($result); $rname=''; $rurl=''; $rimgid=0; $rflags=0; $rrgb=0; $rdefpg=0; }
  }

  //calculate numbers of field and bit for id
  $fieldn=(int)($id/32)+1;
  $bit=$id%32;
  if($bit) { $bit--; $flag=1073741824>>$bit; }
  else $flag=2147483648;
  $field='flags'.$fieldn;
  if($id==0) $flag=0;
  //receive list of groups, if page belong to group then FieldChecked=1, else 0
  $request='SELECT id,name,flags1,flags2,flags3,flags4,flags5,flags6,flags7,IF('.$field.'&'.$flag.',1,0) AS checked FROM aa_groups WHERE id!=201 AND added!=0 ORDER BY name ASC';
  $resultg=mysql_query($request,$conf->link);
  if(!$resultg) {$err->reason('adb.php|editpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive main group data (flags - for calculate quantity of pages in it)
  $request='SELECT name,flags1,flags2,flags3,flags4,flags5,flags6,flags7 FROM aa_groups WHERE id=201';
  $result201=mysql_query($request,$conf->link);
  if(!$result201) {$err->reason('adb.php|editpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|editpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  require './style/'.$conf->style.'/template/at_pg.php';

  //display top
  if($id==0) $vars['HEADER']=_CONNECTPG;
  else $vars['HEADER']=_EDITPG;
  $vars['STEPS']=_STEP.' 1 '._OUTOF.' 1';
  $vars['THEADER']=_BUTTONSET;

  $vars['GHEADER']=_PAGEINGRS;
  $vars['PNAME']='';
  $vars['NAMEDESC']=_PGNAMEDESC;
  $vars['PURL']='';
  $vars['URLDESC']=_PGURLDESC." <i>\"http://www.mydomain.com/index.html\"</i>).";
  $vars['DEFPGDESC']=_DEFPGDESC;
  $vars['IMG']=$imgid;
  $vars['DCOLOR']=$color;
  $vars['DFLAG']=$dflag;
  if($defpg) $vars['DEFPGCHECK']='checked';
  else $vars['DEFPGCHECK']='';
  if($id!=0) {        //information about edited page
      $vars['PNAME']=$rname;
      $vars['PURL']=$rurl;
      if(!$imgid) {
          $vars['IMG']=$rimgid;
          $imgid=$rimgid;
          $vars['DCOLOR']=$rrgb;
          $color=$rrgb;
          $vars['DFLAG']=$rflag;
          $dflag=$rflag;
          if($rdefpg) $vars['DEFPGCHECK']='checked';
          else $vars['DEFPGCHECK']='';
          $defpg=$rdefpg;
      }
      $vars['OPERATE']='FormIdExt(admin,"'.$id.'","edit");';
  }
  else {
    if(!$imgid) $vars['IMG']=2;
    $vars['OPERATE']='';
  }

  tparse($top_button,$vars);

  $imglist=array();
  //list of available buttons
  $catalog=opendir('./style/'.$conf->style.'/image/buttons');
  while(($file=readdir($catalog))!=FALSE) {
    if($file!="."&&$file!="..") {
      if(preg_match("/^([0-9]+)\.png$/i",$file,$matches)) {
        $imglist[]=$matches[1];
      }
    }
  }
  closedir($catalog);

  natsort($imglist);

  reset($imglist);
  while($e=each($imglist)) {
    $vars['VAL']=$e[1];
    if(!strcmp($vars['VAL'],$vars['IMG'])) $vars['VAL'].=' selected';
    $vars['NAME']=$e[1];
    tparse($top_list,$vars);
  }

  $vars['IMGDESC']=_IMGDESC;
  tparse($top_bend,$vars);

  if($imgid>100) {
      $vars['DCOLOR']=sprintf('%06X',$color);
      $vars['DCOLORDESC']=_DCOLORDESC;
      tparse($top_dstart,$vars);

      $vars['VAL']='1';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG1;
      tparse($top_list,$vars);
      $vars['VAL']='2';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG2;
      tparse($top_list,$vars);
      $vars['VAL']='3';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG3;
      tparse($top_list,$vars);
      $vars['VAL']='4';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG4;
      tparse($top_list,$vars);
      $vars['VAL']='5';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG5;
      tparse($top_list,$vars);
      $vars['VAL']='6';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG6;
      tparse($top_list,$vars);
      $vars['VAL']='7';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG7;
      tparse($top_list,$vars);
      $vars['VAL']='8';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG8;
      tparse($top_list,$vars);
      $vars['VAL']='9';
      if(!strcmp($vars['VAL'],$dflag)) $vars['VAL'].=' selected';
      $vars['NAME']=_DFLAG9;
      tparse($top_list,$vars);
      $vars['DFLAGDESC']=_DFLAGDESC;
      tparse($top_dend,$vars);
  }
  $vars['THEADER']=_PGSET;
  $vars['GHEADER']=_PAGEINGRS;
  $vars['PNAME']='';
  $vars['NAMEDESC']=_PGNAMEDESC;
  $vars['PURL']='';
  $vars['URLDESC']=_PGURLDESC." <i>\"http://www.mydomain.com/index.html\"</i>).";
  $vars['IMG']=$imgid;
  if($id!=0) {        //information about edited page
      $vars['PNAME']=$rname;
      $vars['PURL']=$rurl;
      if(!$imgid) $vars['IMG']=$rimgid;
      $vars['OPERATE']='FormIdExt(admin,"'.$id.'","edit");';
  }
  else {
    if(!$imgid) $vars['IMG']=2;
    $vars['OPERATE']='';
  }
  tparse($top_page,$vars);

  //display information about main group
  if(mysql_num_rows($result201)) {
      $row=mysql_fetch_row($result201);
      $vars['GID']=201;
      $vars['GNAME']=$row[0];
      $cnt=0;
      for($i=1;$i<8;$i++) {
          $tmp=(float)$row[$i];
          while($tmp) {
              if($tmp%2) $cnt++;
              $tmp=(int)($tmp/2);
          }
      }
      $vars['PAGESCOUNT']='- '.$cnt.' '._PAGE_S;
      tparse($center201,$vars);
  }

  //display list of other groups
  while($row = mysql_fetch_row($resultg)) {
      $vars['GID']=$row[0];
      $vars['GNAME']=$row[1];
      if($row[9]) $vars['VALUE']='checked';
      else $vars['VALUE']='';
      $cnt=0;
      for($i=2;$i<9;$i++) {
          $tmp=(float)$row[$i];
          while($tmp) {
              if($tmp%2) $cnt++;
              $tmp=(int)($tmp/2);
          }
      }
      $vars['PAGESCOUNT']='- '.$cnt.' '._PAGE_S;
      tparse($center,$vars);
  }
  //display bottom
  $vars['SUBMIT']=_SUBMIT;
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);
  mysql_free_result($resultg);
  mysql_free_result($result201);

}
//===================================================================
function updatepg($id,$name,$url,$imgid,$color,$flag,$defpg) {        //update base after edit or add page
                                           //if id==0 then add new page else edit exists page
  global $err,$conf,$HTTP_POST_VARS;

  $defurl=$url;
  if(preg_match("/[^\/]+(\/[^\/]+)$/i",$defurl)) {
    $defurl=preg_replace("/\/[^\/]+$/i",'',$defurl);
  }
  else $defurl=preg_replace("/\/$/i",'',$defurl);
  $request='LOCK TABLES aa_pages WRITE,aa_groups WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if($id==0) {                //add new page
          $lastuid=1;
          $request='SELECT MAX(uid) AS lastuid,COUNT(*) AS nrec FROM aa_pages';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          while($row=mysql_fetch_object($result)) { if($row->nrec) $lastuid=$row->lastuid+1; }
          mysql_free_result($result);
            //receive first free id (where added=0)
          $request='SELECT id FROM aa_pages WHERE added=0 ORDER BY id ASC LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {        //if exists free id then place page here (UPDATE)
              $row = mysql_fetch_object($result);
              mysql_free_result($result);
              $id=$row->id;
              $request='UPDATE aa_pages SET name="'.$name.'",url="'.$url.'",defurl="'.$defurl.'",defpg='.$defpg.',uid='.$lastuid.',imgid='.$imgid.',flags='.$flag.',rgb='.$color.',added='.$conf->ctime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,htmin=1000000,rmin=1000000,vmax=0,hsmax=0,htmax=0,rmax=0 WHERE id='.$id;
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
          else {                        //if do not exists free id (INSERT)
                //receive last id (where max)
              mysql_free_result($result);
              $request='SELECT id FROM aa_pages ORDER BY id DESC LIMIT 1';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
              if(mysql_num_rows($result)) {        //if exists last id then id=max+1
                  $row = mysql_fetch_object($result);
                  mysql_free_result($result);
                  $id=$row->id+1;
                   //check page limit
                  if($id>200) {$err->reason('adb.php|updatepg|adding of new page has failed(limit=200)');return;}
              }
              else {                                //if do not exists last id (clear table)
                  mysql_free_result($result);
                  $id=1;
              }
               //insert page into table
              $request='INSERT INTO aa_pages (id,uid,name,url,imgid,flags,rgb,defurl,defpg,added,first_t,last_t,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax) VALUES ('.$id.','.$lastuid.',"'.$name.'","'.$url.'",'.$imgid.','.$flag.','.$color.',"'.$defurl.'",'.$defpg.','.$conf->ctime.',0,0,1000000,0,1000000,0,1000000,0,1000000,0)';
              $result=mysql_query($request,$conf->link);
              if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
  }
  else {                //update existing page (only for edit)
          $request='UPDATE aa_pages SET name="'.$name.'",url="'.$url.'",defurl="'.$defurl.'",defpg='.$defpg.',imgid='.$imgid.',flags='.$flag.',rgb='.$color.' WHERE id='.$id;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  if($defpg) {
      // Reset default page for others pages
      $request='UPDATE aa_pages SET defpg=0 WHERE id!='.$id.' AND defurl="'.$defurl.'"';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  //receive list of groups
  $request='SELECT id FROM aa_groups WHERE added!=0 AND id!=201';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $idon='(201';                //list of groups where contain this page
  $idoff='';             //list of groups where do not contain this page
  while($row=mysql_fetch_object($result)) {
      $idg=$row->id;
      if(isset($GLOBALS['group'.$idg])||isset($HTTP_POST_VARS['group'.$idg])) {
          if(empty($idon)) $idon='('.$idg;
          else $idon.=','.$idg;
      }
      else {
          if(empty($idoff)) $idoff='('.$idg;
          else $idoff.=','.$idg;
      }
  }
  if(!empty($idon)) $idon.=')';
  if(!empty($idoff)) $idoff.=')';
  mysql_free_result($result);

  //calculate numbers of field and bit for id
  $fieldn=(int)($id/32)+1;
  $bit=(int)($id%32);
  if($bit) { $bit--; $flag=1073741824>>$bit; }
  else $flag=2147483648;
  $flagoff=4294967295-$flag;

  if(!empty($idon)) {                //add page in groups
      $request='UPDATE aa_groups SET flags'.$fieldn.'=flags'.$fieldn.'|'.$flag.' WHERE id IN'.$idon;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  if(!empty($idoff)) {           //delete page from groups
      $request='UPDATE aa_groups SET flags'.$fieldn.'=flags'.$fieldn.'&'.$flagoff.' WHERE id IN'.$idoff;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|updatepg|the request \''.$request.'\' has failed -- '.mysql_error());return;}

}
//===================================================================
function editgr($id,&$vars) {                //display form for add or edit group
  global $err,$conf;

  require './style/'.$conf->style.'/template/at_gr.php';

  $request='LOCK TABLES aa_pages READ,aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|editgr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive all list of pages
  $request='SELECT id,name,url,added FROM aa_pages WHERE added!=0 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|editgr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive list of pages for edited group
  $mid=array();
  if($id!=0) {
      getpgs($id,$mid);
      if($err->flag){$err->reason('adb.php|editgr|\'getpgs\' function has failed');return;}
      //receive name of edited group
      $request='SELECT name,id FROM aa_groups WHERE id='.$id;
      $resultn=mysql_query($request,$conf->link);
      if(!$resultn) {$err->reason('adb.php|editgr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $row[0]='';
      $row=mysql_fetch_row($resultn);
      mysql_free_result($resultn);
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|editgr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //display top
  if($id!=0) {
      $vars['HEADER']=_EDITGR;
      $vars['GNAME']=$row[0];
  }
  else {
      $vars['HEADER']=_CREATEGR;
      $vars['GNAME']='';
  }
  $vars['STEPS']=_STEP.' 1 '._OUTOF.' 1';
  $vars['THEADER']=_GRSET;
  $vars['NAMEDESC']=_GRNAMEDESC;
  $vars['PHEADER']=_PGSINGR;
  if($id==201) tparse($top201,$vars);
  else tparse($top,$vars);

  //display center
  if(mysql_num_rows($result)) {
      while($row=mysql_fetch_object($result)) {
          $fname=$row->name;
          if(strlen($fname)>_AS_GR) $sname=substr($fname,0,_AS_GR-3).'...';
          else $sname=$fname;
          $vars['PID']=$row->id;
          $vars['URL']=$row->url;
          $vars['PNAME']=$fname;
          $vars['PNAMESHORT']=$sname;
          if(isset($mid[$row->id])) $vars['VALUE']='checked';
          else $vars['VALUE']='';
          if($id==201) tparse($center201,$vars);
          else tparse($center,$vars);
      }
  }
  else {
          $vars['TEXT']=_NORECORDS;
          tparse($empty,$vars);
  }
  mysql_free_result($result);

  //display bottom
  $vars['SUBMIT']=_SUBMIT;
  $vars['BACKTT']=_BACKTOTOP;
  tparse($bottom,$vars);

}
//===================================================================
function updategr($name,$groupid=0) {                //update exists record or add new record to group's base
  global $err,$conf,$HTTP_POST_VARS;

  $request='LOCK TABLES aa_groups WRITE,aa_pages READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive all list of pages id
  $request='SELECT id,added FROM aa_pages WHERE added!=0';
  $resultp=mysql_query($request,$conf->link);
  if(!$resultp) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //receive information about edited group
  $request='SELECT * FROM aa_groups WHERE id='.$groupid;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //form old values in $row
  if(mysql_num_rows($result)) $row = mysql_fetch_row($result);
  else for($i=0;$i<10;$i++) $row[$i]=0;
  mysql_free_result($result);

  //form values of flags for group (add/delete page to/from group)
  while($rowp=mysql_fetch_object($resultp)) {
      $id=$rowp->id;
      $fieldn=(int)($id/32)+1;
      $bit=(int)($id%32);
      if($bit) { $flag=1073741824>>($bit-1); }
      else $flag=2147483648;
      if(isset($GLOBALS['page'.$id])||isset($HTTP_POST_VARS['page'.$id])) {
          if($flag==2147483648 || (float)$row[$fieldn]>2147483647) {
              $rlast=$row[$fieldn]%2;
              $flast=$flag%2;
              $row[$fieldn]=(int)($row[$fieldn]/2);
              $flag=(int)($flag/2);
              $row[$fieldn]|=$flag;
              $row[$fieldn]*=2;
              if($rlast||$flast) $row[$fieldn]+=1;
          }
          else $row[$fieldn]|=$flag;
      }
      else {
          $tmp=(float)$row[$fieldn];//-=$flag;//$row[$fieldn]&=(~$flag);
          for(;$bit<31;$bit++) $tmp=(int)($tmp/2);
          if($tmp%2) $row[$fieldn]-=$flag;
      }
  }
  mysql_free_result($resultp);

  if($row[0]) {                 //if isset group
      //form query string that contain changed flags and its values
      $query='';
      $res=0;
      for($i=1;$i<8;$i++) {
          if(empty($query)) $query='flags'.$i.'='.$row[$i];
          else $query.=',flags'.$i.'='.$row[$i];
          $res+=$row[$i];
      }
      $request='UPDATE aa_groups SET name="'.$name.'",'.$query.' WHERE id='.$groupid;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }//if($row[0])
  else {                 //if do not isset group
      //receive first free id (where added=0)
      $request='SELECT id,added FROM aa_groups WHERE added=0 ORDER BY id ASC LIMIT 1';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      if(mysql_num_rows($result)) {                //if exists free id then update it
          $row1 = mysql_fetch_object($result);
          mysql_free_result($result);
          $groupid=$row1->id;
          //form query string that contain changed flags and its values
          $query='';
          $res=0;
          for($i=1;$i<8;$i++) {
              if(empty($query)) $query='flags'.$i.'='.$row[$i];
              else $query.=',flags'.$i.'='.$row[$i];
              $res+=$row[$i];
          }
          $request='UPDATE aa_groups SET added='.$conf->ctime.',name="'.$name.'",first_t=0,last_t=0,vmin=1000000,vmax=0,hsmin=1000000,hsmax=0,htmin=1000000,htmax=0,rmin=1000000,rmax=0,'.$query.' WHERE id='.$groupid;
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
      else {                                        //if do not exists free id
          mysql_free_result($result);
          //receive last id (max id)
          $request='SELECT id FROM aa_groups ORDER BY id DESC LIMIT 1';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(mysql_num_rows($result)) {                //if exists max id then id=max+1
              $row1 = mysql_fetch_object($result);
              $groupid=$row1->id+1;
              //check limit of quantity groups
              if($groupid>220) {$err->reason('adb.php|updategr|adding of new group has failed(limit=220)|');return;}
          }
          else {                                        //if do not exists max id (clear table)
              $groupid=202;                        //201 - main group id
          }
          mysql_free_result($result);
          //form query string that contain changed flags and its values
          $query='';
          $res=0;
          for($i=1;$i<8;$i++) { $query.=$row[$i].','; $res+=$row[$i]; }
          $request='INSERT INTO aa_groups (id,flags1,flags2,flags3,flags4,flags5,flags6,flags7,name,added,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax,first_t,last_t) VALUES ('.$groupid.','.$query.'"'.$name.'",'.$conf->ctime.',1000000,0,1000000,0,1000000,0,1000000,0,0,0)';
          $result=mysql_query($request,$conf->link);
          if(!$result) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('adb.php|updategr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function delpggr($id) {                //delete page or group from base
  global $err,$conf;

  $request='SELECT GET_LOCK("aa_ref",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|delpggr|\'ref\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_ref_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_key",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|delpggr|\'key\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_key_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_frm",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|delpggr|\'frm\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_frm_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_prv",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|delpggr|\'prv\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_prv_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_prx",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|delpggr|\'prx\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_prx_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT RELEASE_LOCK("aa_prx")';
  $reslock=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);

  $request='LOCK TABLES aa_pages WRITE,aa_groups WRITE,aa_hours WRITE,aa_days WRITE,aa_total WRITE,aa_hosts WRITE,aa_lang_total WRITE,aa_coun_total WRITE,aa_st_total WRITE,aa_raw_dom WRITE,aa_raw WRITE,aa_points WRITE,aa_vectors WRITE,aa_depthes WRITE,aa_times WRITE,aa_eng_total WRITE,aa_zones WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if($id<=200) $table='aa_pages';
  else $table='aa_groups';
  //deleted page/group is marked as added=0 (speed optimization when add new page/group )
  $request='UPDATE '.$table.' SET added=0 WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //delete page/group from the other tables
  $request='DELETE FROM aa_hours WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_days WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_lang_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_coun_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_st_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_times WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_eng_total WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_zones WHERE id='.$id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  //delete page/group from the aa_hosts table (set corresponding bit in flags)
  $fieldn=(int)($id/32)+1;
  $bit=(int)($id%32);
  if($bit) { $bit--; $flag=1073741824>>$bit; }
  else $flag=2147483648;
  $flag=4294967295-$flag;                //inversion of flag
  $request='UPDATE aa_hosts SET flags'.$fieldn.'=flags'.$fieldn.'&'.$flag;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  //delete page from the aa_groups table (set corresponding bit in flags)
  if($id<=200) {
      $request='UPDATE aa_groups SET flags'.$fieldn.'=flags'.$fieldn.'&'.$flag;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='UPDATE aa_points SET modify=0,vt=0,vy=0,vw=0,vlw=0,vm=0,vlm=0,v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,hst=0,hsy=0,hsw=0,hslw=0,hsm=0,hslm=0,hs1=0,hs2=0,hs3=0,hs4=0,hs5=0,hs6=0,hs7=0,htt=0,hty=0,htw=0,htlw=0,htm=0,htlm=0,ht1=0,ht2=0,ht3=0,ht4=0,ht5=0,ht6=0,ht7=0 WHERE id='.$id;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aa_vectors WHERE sourid='.$id.' OR destid='.$id;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='SELECT domid,COUNT(*) AS count FROM aa_raw WHERE id='.$id.' GROUP BY domid';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aa_raw WHERE id='.$id;
      $result1=mysql_query($request,$conf->link);
      if(!$result1) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      while($row=mysql_fetch_object($result)) {
          $request='DELETE FROM aa_raw_dom WHERE domid='.$row->domid.' AND count<='.$row->count;
          $resultl=mysql_query($request,$conf->link);
          if(!$resultl) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          if(!mysql_affected_rows()) {
              $request='UPDATE aa_raw_dom SET count=count-'.$row->count.' WHERE domid='.$row->domid;
              $resultl=mysql_query($request,$conf->link);
              if(!$resultl) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
          }
      }
      mysql_free_result($result);
  }
  else {
      $request='DELETE FROM aa_depthes WHERE id='.$id;
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $request='UNLOCK TABLES';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|delpggr|the request \''.$request.'\' has failed -- '.mysql_error());return;}
}
//===================================================================
function resetstat() {                //reset statistics
  global $err,$conf;

  $request='SELECT GET_LOCK("aa_locka",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'adb\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);

  $request='DELETE FROM aa_raw';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_raw_dom';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $module=array();
  if(isset($conf->aa_mod)) {
      $tmp=split('\|',$conf->aa_mod);
      for($i=0;$i<sizeof($tmp);$i++) $module[$tmp[$i]]=1;
  }
  if(isset($module['aat_'])) {
      $request='DELETE FROM aat_hours';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_days';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_lang_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_coun_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_st_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_raw';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_raw_dom';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_dephit';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_times';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='DELETE FROM aat_zones';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='LOCK TABLES aat_groups WRITE,aat_pages WRITE';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='UPDATE aat_groups SET added='.$conf->btime.',first_t=0,last_t=0,htmin=1000000,htmax=0 WHERE added!=0';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='UPDATE aat_pages SET added='.$conf->btime.',first_t=0,last_t=0,htmin=1000000,htmax=0 WHERE added!=0';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      $request='UNLOCK TABLES';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  //reset statistics from all tables of analyzer
  $request='DELETE FROM aa_hours';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_days';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_hosts';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_lang_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_coun_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_st_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_vectors';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_depthes';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_times';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_zones';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_rdata';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='LOCK TABLES aa_groups WRITE,aa_pages WRITE,aa_points WRITE';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='UPDATE aa_groups SET added='.$conf->btime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,rmin=1000000,htmin=1000000,vmax=0,hsmax=0,rmax=0,htmax=0 WHERE added!=0';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='UPDATE aa_pages SET added='.$conf->btime.',first_t=0,last_t=0,vmin=1000000,hsmin=1000000,rmin=1000000,htmin=1000000,vmax=0,hsmax=0,rmax=0,htmax=0 WHERE added!=0';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='UPDATE aa_points SET modify=0,vt=0,vy=0,vw=0,vlw=0,vm=0,vlm=0,v1=0,v2=0,v3=0,v4=0,v5=0,v6=0,v7=0,hst=0,hsy=0,hsw=0,hslw=0,hsm=0,hslm=0,hs1=0,hs2=0,hs3=0,hs4=0,hs5=0,hs6=0,hs7=0,htt=0,hty=0,htw=0,htlw=0,htm=0,htlm=0,ht1=0,ht2=0,ht3=0,ht4=0,ht5=0,ht6=0,ht7=0';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='UNLOCK TABLES';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='SELECT GET_LOCK("aa_ref",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'ref\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  if(isset($module['aat_'])) {
      $request='DELETE FROM aat_ref_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $request='DELETE FROM aa_ref_base';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_domains';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_ref_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_tmp';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_key",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'key\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  if(isset($module['aat_'])) {
      $request='DELETE FROM aat_key_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  $request='DELETE FROM aa_eng_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_key_base';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_key_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_frm",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'frm\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_frm_base';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_frm_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_prv",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'prv\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_prv_base';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_prv_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT GET_LOCK("aa_prx",10)';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $row=mysql_fetch_row($result);
  if(!$row[0]) {$err->reason('adb.php|resetstat|\'prx\' is busy -- '.mysql_error());return;}
  mysql_free_result($result);
  $request='DELETE FROM aa_prx_base';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='DELETE FROM aa_prx_total';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $request='SELECT RELEASE_LOCK("aa_prx")';
  $reslock=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  mysql_free_result($reslock);
}
//===================================================================
function name201() {                //change name of main group
  global $err,$conf;

  $request='UPDATE aa_groups SET name="'._ALLPGS.'" WHERE id=201';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('adb.php|resetstat|the request \''.$request.'\' has failed -- '.mysql_error());return;}

}
//===================================================================
function pages(&$vars) {                //display pages list
  global $err;
  $this->grpg($vars,0);
  if($err->flag){$err->reason('adb.php|pages|\'grpg\' function has failed');return;}
}
//===================================================================
function groups(&$vars) {        //display groups list
  global $err;
  $this->grpg($vars,1);
  if($err->flag){$err->reason('adb.php|groups|\'grpg\' function has failed');return;}
}
//===================================================================
function addpage(&$vars,$imgid,$color,$flag,$defpg) {        //display form for add page
  global $err;
  $this->editpg($vars,0,$imgid,$color,$flag,$defpg);
  if($err->flag){$err->reason('adb.php|addpage|\'editpg\' function has failed');return;}
}
//===================================================================
function addpg($name,$url,$imgid,$color,$flag,$defpg) {        //add new page to base
  global $err;
  $this->updatepg(0,$name,$url,$imgid,$color,$flag,$defpg);
  if($err->flag){$err->reason('adb.php|addpg|\'updatepg\' function has failed');return;}
}
//===================================================================
function addgr(&$vars) {                //display form for add group
  global $err;
  $this->editgr(0,$vars);
  if($err->flag){$err->reason('adb.php|addgr|\'editgr\' function has failed');return;}
}
//===================================================================
}

?>
