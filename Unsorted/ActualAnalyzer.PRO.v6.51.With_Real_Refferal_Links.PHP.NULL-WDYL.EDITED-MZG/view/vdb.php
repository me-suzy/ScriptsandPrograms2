<?php

class vdb {

//===================================================================
function getgrs() {
  global $conf,$err;

  $request='LOCK TABLES aa_groups READ';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $mas=array();
  $request='SELECT name,id FROM aa_groups WHERE id=201';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) { mysql_free_result($result); return $mas; }
  $row=mysql_fetch_object($result);
  $mas['201']=$row->name;
  mysql_free_result($result);

  $request='SELECT id,name,added FROM aa_groups WHERE added!=0 AND id!=201 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$conf->link);
  if(!$resultu) {$err->reason('vdb.php|getgrs|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  return $mas;
}//function getgrs
//===================================================================
function getpages() {
  global $conf,$err;

  $mas=array();
  $request='SELECT id,name,added FROM aa_pages WHERE added!=0 ORDER BY name ASC';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getpages|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  while($row=mysql_fetch_object($result)) $mas[$row->id]=$row->name;
  mysql_free_result($result);

  return $mas;
}//function getpages
//===================================================================
function getnamegrpg($page_id,&$name,&$url) {
  global $conf,$err;

  if($page_id<201) { $table='aa_pages'; $u=',url'; }
  else { $table='aa_groups'; $u=''; }

  $request='SELECT name,id'.$u.' FROM '.$table.' WHERE id='.$page_id;
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('vdb.php|getnamegrpg|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) { mysql_free_result($result); $err->reason('vdb.php|getnamegrpg|the page with id='.$page_id.' is not found');return ''; }
  $row=mysql_fetch_object($result);
  mysql_free_result($result);
  $name=$row->name;
  if($page_id<201) $url=$row->url;
  else $url='';
}//function getnamegrpg
//===================================================================
function vis_all($page_id,&$vars,$name,$url) {
  global $err,$conf;
  require './view/vdb/vis_all.php';
}
//===================================================================
function vis_tim($page_id,&$vars,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/vis_tim.php';
}
//===================================================================
function ref($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/ref.php';
}
//===================================================================
function vis_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) { //page_id=221-all by groups,other-group by pages
  global $err,$conf;
  require './view/vdb/vis_grpg.php';
}
//===================================================================
function standard($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/standard.php';
}
//===================================================================
function log($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$filter,$filtercl,$tint,$year) {
  global $err,$conf;
  require './view/vdb/log.php';
}
//===================================================================
function points_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/points_grpg.php';
}
//===================================================================
function points_tim($page_id,&$vars,$name,$url,$flag) {
  global $err,$conf;
  require './view/vdb/points_tim.php';
}
//===================================================================
function timedepth($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/timedepth.php';
}
//===================================================================
function engkey($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/engkey.php';
}
//===================================================================
function zones($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/zones.php';
}
//===================================================================
function frames($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/frames.php';
}
//===================================================================
function prvprx($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/prvprx.php';
}
//===================================================================
function trans($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,$flag) {
  global $err,$conf;
  require './view/vdb/trans.php';
}
//===================================================================
function prod_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/prod_grpg.php';
}
//===================================================================
function prod_tim($page_id,&$vars,$name,$url) {
  global $err,$conf;
  require './view/vdb/prod_tim.php';
}
//===================================================================
function online_grpg($page_id,&$vars,$name,$sort,$tim) {
  global $err,$conf;
  require './view/vdb/online_grpg.php';
}
//===================================================================
function ways($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err,$conf;
  require './view/vdb/ways.php';
}
//===================================================================
//STANDARD
function lang($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,0);
  if($err->flag) {$err->reason('vdb.php|lang_all|\'standard\' function has failed');return;}
}
function countr($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|countr|\'standard\' function has failed');return;}
}
function colord($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|colord|\'standard\' function has failed');return;}
}
function brow($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,3);
  if($err->flag) {$err->reason('vdb.php|brow|\'standard\' function has failed');return;}
}
function os($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,4);
  if($err->flag) {$err->reason('vdb.php|os|\'standard\' function has failed');return;}
}
function jscript($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,5);
  if($err->flag) {$err->reason('vdb.php|jscript|\'standard\' function has failed');return;}
}
function scr($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,6);
  if($err->flag) {$err->reason('vdb.php|scr|\'standard\' function has failed');return;}
}
function cookie($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,7);
  if($err->flag) {$err->reason('vdb.php|cookie|\'standard\' function has failed');return;}
}
function java($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->standard($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,8);
  if($err->flag) {$err->reason('vdb.php|java|\'standard\' function has failed');return;}
}
//PRO
function entry_tim($page_id,&$vars,$name,$url) {
  global $err;
  $this->points_tim($page_id,$vars,$name,$url,1);
  if($err->flag) {$err->reason('vdb.php|entry_tim|\'points_tim\' function has failed');return;}
}
function entry_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->points_grpg($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|entry_grpg|\'points_grpg\' function has failed');return;}
}
function exit_tim($page_id,&$vars,$name,$url) {
  global $err;
  $this->points_tim($page_id,$vars,$name,$url,2);
  if($err->flag) {$err->reason('vdb.php|exit_tim|\'points_tim\' function has failed');return;}
}
function exit_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->points_grpg($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|exit_grpg|\'points_grpg\' function has failed');return;}
}
function single_tim($page_id,&$vars,$name,$url) {
  global $err;
  $this->points_tim($page_id,$vars,$name,$url,3);
  if($err->flag) {$err->reason('vdb.php|single_tim|\'points_tim\' function has failed');return;}
}
function single_grpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->points_grpg($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,3);
  if($err->flag) {$err->reason('vdb.php|single_grpg|\'points_grpg\' function has failed');return;}
}
function timeonpg($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->timedepth($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|timeonpg|\'points\' function has failed');return;}
}
function rets($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->timedepth($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|rets|\'points\' function has failed');return;}
}
function viewd($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->timedepth($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,3);
  if($err->flag) {$err->reason('vdb.php|depth|\'points\' function has failed');return;}
}
function engines($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->engkey($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|engines|\'engkey\' function has failed');return;}
}
function swords($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->engkey($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|swords|\'engkey\' function has failed');return;}
}
function sphrases($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->engkey($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,3);
  if($err->flag) {$err->reason('vdb.php|sphrases|\'engkey\' function has failed');return;}
}
function transto($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->trans($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|transto|\'trans\' function has failed');return;}
}
function transfrom($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->trans($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|transfrom|\'trans\' function has failed');return;}
}
function providers($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->prvprx($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,1);
  if($err->flag) {$err->reason('vdb.php|providers|\'prvprx\' function has failed');return;}
}
function proxy($page_id,&$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year) {
  global $err;
  $this->prvprx($page_id,$vars,$begstr,$numstr,$name,$url,$sort,$tint,$year,2);
  if($err->flag) {$err->reason('vdb.php|proxy|\'prvprx\' function has failed');return;}
}

}

?>
