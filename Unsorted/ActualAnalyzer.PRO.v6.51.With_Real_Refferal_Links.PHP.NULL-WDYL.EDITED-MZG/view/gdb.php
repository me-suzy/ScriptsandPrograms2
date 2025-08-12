<?php

class gdb {

//===================================================================
function getnamegrpg($page_id,&$name,&$url) {
  global $conf,$err;

  if($page_id<201) { $table='aa_pages'; $u=',url'; }
  else { $table='aa_groups'; $u=''; }

  $request='SELECT name,id'.$u.' FROM '.$table.' WHERE id='.$page_id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('gdb.php|getnamegrpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) { mysql_free_result($result); $err->reason('vdb.php|getnamegrpg|the page with id='.$page_id.' is not found');return ''; }
  $row=mysql_fetch_object($result);
  mysql_free_result($result);
  $name=$row->name;
  if($page_id<201) $url=$row->url;
  else $url='';
}//function getnamegrpg
//===================================================================
function values(&$resarray,$rdid,$what) {
  global $conf,$err;

  $request='SELECT * FROM aa_rdata WHERE id='.$rdid.' ORDER BY num ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('gdb.php|values|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $tot=array();
  $tot['nrec']=0;
  $tot['name']='';
  $tot['vmin']=0;
  $tot['vmax']=0;
  $tot['vsum']=0;
  $tot['vavg']=0;
  $tot['vimin']=0;
  $tot['vimax']=0;
  $tot['visum']=0;
  $tot['viavg']=0;
  $tot['hsmin']=0;
  $tot['hsmax']=0;
  $tot['hssum']=0;
  $tot['hsavg']=0;
  $tot['hsimin']=0;
  $tot['hsimax']=0;
  $tot['hsisum']=0;
  $tot['hsiavg']=0;
  $tot['rmin']=0;
  $tot['rmax']=0;
  $tot['rsum']=0;
  $tot['ravg']=0;
  $tot['rimin']=0;
  $tot['rimax']=0;
  $tot['risum']=0;
  $tot['riavg']=0;
  $tot['htmin']=0;
  $tot['htmax']=0;
  $tot['htsum']=0;
  $tot['htavg']=0;
  $tot['htimin']=0;
  $tot['htimax']=0;
  $tot['htisum']=0;
  $tot['htiavg']=0;
  $i=1;
  while($row=mysql_fetch_object($result)) {
      if(!$row->num) { $resarray[0]=$row->name; $i=$row->vi; continue; }
      elseif($row->num==252) {
          $tot['nrec']=(int)($row->vp);
          $tot['name']=$row->name;
          $tot['vsum']=$row->v;
          $tot['hssum']=$row->hs;
          $tot['rsum']=$row->r;
          $tot['htsum']=$row->ht;
          $tot['visum']=$row->vi;
          $tot['hsisum']=$row->hsi;
          $tot['risum']=$row->ri;
          $tot['htisum']=$row->hti;
          continue;
      }
      elseif($row->num==253) {
          $tot['vmin']=$row->v;
          $tot['hsmin']=$row->hs;
          $tot['rmin']=$row->r;
          $tot['htmin']=$row->ht;
          $tot['vimin']=$row->vi;
          $tot['hsimin']=$row->hsi;
          $tot['rimin']=$row->ri;
          $tot['htimin']=$row->hti;
          continue;
      }
      elseif($row->num==254) {
          $tot['vavg']=$row->v;
          $tot['hsavg']=$row->hs;
          $tot['ravg']=$row->r;
          $tot['htavg']=$row->ht;
          $tot['viavg']=$row->vi;
          $tot['hsiavg']=$row->hsi;
          $tot['riavg']=$row->ri;
          $tot['htiavg']=$row->hti;
          continue;
      }
      elseif($row->num==255) {
          $tot['vmax']=$row->v;
          $tot['hsmax']=$row->hs;
          $tot['rmax']=$row->r;
          $tot['htmax']=$row->ht;
          $tot['vimax']=$row->vi;
          $tot['hsimax']=$row->hsi;
          $tot['rimax']=$row->ri;
          $tot['htimax']=$row->hti;
          continue;
      }
      $resarray[$i]='';
      if(!strcmp($what,'visitors')||!strcmp($what,'summary')) {
          if(empty($resarray[$i])) $resarray[$i].=$row->vi;
          else $resarray[$i].='|'.$row->vi;
          $resarray[$i].='|'.$row->v;
          $resarray[$i].='|'.$row->vp;
      }
      if(!strcmp($what,'hosts')||!strcmp($what,'summary')) {
          if(empty($resarray[$i])) $resarray[$i].=$row->hsi;
          else $resarray[$i].='|'.$row->hsi;
          $resarray[$i].='|'.$row->hs;
          $resarray[$i].='|'.$row->hsp;
      }
      if(!strcmp($what,'reloads')||!strcmp($what,'summary')) {
          if(empty($resarray[$i])) $resarray[$i].=$row->ri;
          else $resarray[$i].='|'.$row->ri;
          $resarray[$i].='|'.$row->r;
          $resarray[$i].='|'.$row->rp;
      }
      if(!strcmp($what,'hits')||!strcmp($what,'summary')) {
          if(empty($resarray[$i])) $resarray[$i].=$row->hti;
          else $resarray[$i].='|'.$row->hti;
          $resarray[$i].='|'.$row->ht;
          $resarray[$i].='|'.$row->htp;
      }
      if(!strcmp($what,'clicks')) {
          if(empty($resarray[$i])) $resarray[$i].=$row->hti;
          else $resarray[$i].='|'.$row->hti;
          $resarray[$i].='|'.$row->ht;
          $resarray[$i].='|'.$row->htp;
      }
      $resarray[$i].='|'.$row->name;
      $resarray[$i].='|'.$row->addpar;
      $i++;
  }
  if($tot['nrec']) {
      $resarray[0].='|'.$tot['nrec'];
      if(!strcmp($what,'visitors')) $resarray[0].='|'.$tot['vmin'].'|'.$tot['vmax'].'|'.$tot['vsum'].'|'.$tot['name'].'|'.$tot['visum'];
      elseif(!strcmp($what,'hosts')) $resarray[0].='|'.$tot['hsmin'].'|'.$tot['hsmax'].'|'.$tot['hssum'].'|'.$tot['name'].'|'.$tot['hsisum'];
      elseif(!strcmp($what,'reloads')) $resarray[0].='|'.$tot['rmin'].'|'.$tot['rmax'].'|'.$tot['rsum'].'|'.$tot['name'].'|'.$tot['risum'];
      elseif(!strcmp($what,'hits')) $resarray[0].='|'.$tot['htmin'].'|'.$tot['htmax'].'|'.$tot['htsum'].'|'.$tot['name'].'|'.$tot['htisum'];
      elseif(!strcmp($what,'clicks')) $resarray[0].='|'.$tot['htmin'].'|'.$tot['htmax'].'|'.$tot['htsum'].'|'.$tot['name'].'|'.$tot['htisum'];
      elseif(!strcmp($what,'summary')) {
          $resarray[0].='|'.$tot['vmin'].'|'.$tot['vmax'].'|'.$tot['vsum'].'|'.$tot['visum'];
          $resarray[0].='|'.$tot['hsmin'].'|'.$tot['hsmax'].'|'.$tot['hssum'].'|'.$tot['hsisum'];
          $resarray[0].='|'.$tot['rmin'].'|'.$tot['rmax'].'|'.$tot['rsum'].'|'.$tot['risum'];
          $resarray[0].='|'.$tot['htmin'].'|'.$tot['htmax'].'|'.$tot['htsum'].'|'.$tot['htisum'].'|'.$tot['name'];
      }
  }
  mysql_free_result($result);

}

}

?>
