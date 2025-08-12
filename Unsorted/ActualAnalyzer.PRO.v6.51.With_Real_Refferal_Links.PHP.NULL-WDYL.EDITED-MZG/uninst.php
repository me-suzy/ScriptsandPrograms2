<?php

//root folder
$rf='./';

require './common/error.php';
require './common/global.php';
require './common/config.php';
require './common/auth.php';

//errors
$err = & new error($rf);

//config
$conf = & new config($rf);
if($err->flag) {
  $err->reason('uninst.php||constructor of config class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//authentication
$login = & new auth($rf,'uninst',_UNINSTALL);
if($err->flag) {
  $err->reason('uninst.php||constructor of auth class has failed');
  $err->log_out();
  $err->scr_out();
  exit;
}

//delete all tables
deltables();
if($err->flag) {
  $err->reason('uninst.php||can\'t remove tables from database');
  $err->log_out();
  $err->scr_out();
  exit;
}

//delete all files
delfiles();
if($err->flag) {
  $err->reason('uninst.php||can\'t remove files');
  $err->log_out();
  $err->scr_out();
  exit;
}

echo 'uninst completed successfully, now you can remove all scripts of the ActualAnalyzer.';
exit;

//=================================================================== FOR uninst
function deltables() {
  global $err,$conf;

  $conf->link=@mysql_connect($conf->dbhost,$conf->dbuser,$conf->dbpass);
  if(!$conf->link) {$err->reason('uninst.php|deltables|connection with mysql server has failed');return;}
  $rez=mysql_select_db($conf->dbase);
  if(!$rez) {$err->reason('uninst.php|deltables|the request \'use '.$conf->dbase.'\' has failed -- '.mysql_error());return;}


  $request='SHOW TABLES LIKE "aa_%"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('uninst.php|deltables|the request \'show tables\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) return;

  $tables=array();
  while($row=mysql_fetch_row($result)) $tables[$row[0]]=0;
  mysql_free_result($result);
  if(isset($tables['aa_groups'])) {
      $request='DROP TABLE aa_groups';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_groups\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_pages'])) {
      $request='DROP TABLE aa_pages';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_pages\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_hosts'])) {
      $request='DROP TABLE aa_hosts';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_hosts\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_hours'])) {
      $request='DROP TABLE aa_hours';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_hours\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_days'])) {
      $request='DROP TABLE aa_days';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_days\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_total'])) {
      $request='DROP TABLE aa_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_ref_base'])) {
      $request='DROP TABLE aa_ref_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_ref_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_ref_total'])) {
      $request='DROP TABLE aa_ref_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_ref_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_domains'])) {
      $request='DROP TABLE aa_domains';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_domains\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_tmp'])) {
      $request='DROP TABLE aa_tmp';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_tmp\' has failed -- '.mysql_error());return;}
  }

  if(isset($tables['aa_lang_base'])) {
      $request='DROP TABLE aa_lang_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_lang_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_lang_total'])) {
      $request='DROP TABLE aa_lang_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_lang_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_coun_base'])) {
      $request='DROP TABLE aa_coun_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_coun_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_coun_total'])) {
      $request='DROP TABLE aa_coun_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_coun_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_st_base'])) {
      $request='DROP TABLE aa_st_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_st_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_st_total'])) {
      $request='DROP TABLE aa_st_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_st_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_raw_dom'])) {
      $request='DROP TABLE aa_raw_dom';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_raw_dom\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_raw'])) {
      $request='DROP TABLE aa_raw';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_raw\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_points'])) {
      $request='DROP TABLE aa_points';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_points\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_vectors'])) {
      $request='DROP TABLE aa_vectors';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_vectors\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_depthes'])) {
      $request='DROP TABLE aa_depthes';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_depthes\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_times'])) {
      $request='DROP TABLE aa_times';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_times\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_eng_base'])) {
      $request='DROP TABLE aa_eng_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_eng_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_eng_total'])) {
      $request='DROP TABLE aa_eng_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_eng_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_key_base'])) {
      $request='DROP TABLE aa_key_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_key_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_key_total'])) {
      $request='DROP TABLE aa_key_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_key_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_frm_base'])) {
      $request='DROP TABLE aa_frm_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_frm_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_frm_total'])) {
      $request='DROP TABLE aa_frm_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_frm_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_zones'])) {
      $request='DROP TABLE aa_zones';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_zones\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_prv_base'])) {
      $request='DROP TABLE aa_prv_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_prv_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_prv_total'])) {
      $request='DROP TABLE aa_prv_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_prv_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_prx_base'])) {
      $request='DROP TABLE aa_prx_base';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_prv_base\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_prx_total'])) {
      $request='DROP TABLE aa_prx_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_prv_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_rdata'])) {
      $request='DROP TABLE aa_rdata';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_rdata\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aa_confdb'])) {
      $request='DROP TABLE aa_confdb';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aa_confdb\' has failed -- '.mysql_error());return;}
  }

  // Click Tracker
  $request='SHOW TABLES LIKE "aat_%"';
  $result=mysql_query($request,$conf->link);
  if(!$result) {$err->reason('uninst.php|deltables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  if(!mysql_num_rows($result)) return;

  $tables=array();
  while($row=mysql_fetch_row($result)) $tables[$row[0]]=0;
  mysql_free_result($result);
  if(isset($tables['aat_groups'])) {
      $request='DROP TABLE aat_groups';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_groups\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_pages'])) {
      $request='DROP TABLE aat_pages';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_pages\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_hosts'])) {
      $request='DROP TABLE aat_hosts';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_hosts\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_hours'])) {
      $request='DROP TABLE aat_hours';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_hours\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_days'])) {
      $request='DROP TABLE aat_days';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_days\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_total'])) {
      $request='DROP TABLE aat_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_ref_total'])) {
      $request='DROP TABLE aat_ref_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_ref_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_lang_total'])) {
      $request='DROP TABLE aat_lang_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_lang_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_coun_total'])) {
      $request='DROP TABLE aat_coun_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_coun_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_st_total'])) {
      $request='DROP TABLE aat_st_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_st_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_raw_dom'])) {
      $request='DROP TABLE aat_raw_dom';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_raw_dom\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_raw'])) {
      $request='DROP TABLE aat_raw';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_raw\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_dephit'])) {
      $request='DROP TABLE aat_dephit';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_dephit\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_times'])) {
      $request='DROP TABLE aat_times';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_times\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_eng_total'])) {
      $request='DROP TABLE aat_eng_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_eng_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_key_total'])) {
      $request='DROP TABLE aat_key_total';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_key_total\' has failed -- '.mysql_error());return;}
  }
  if(isset($tables['aat_zones'])) {
      $request='DROP TABLE aat_zones';
      $result=mysql_query($request,$conf->link);
      if(!$result) {$err->reason('uninst.php|deltables|the request \'drop table aat_zones\' has failed -- '.mysql_error());return;}
  }

  if($conf->link) {
    $rez=mysql_close($conf->link);
    if(!$rez) {$err->reason('uninst.php|deltables|closing of connection with mysql server has failed');return;}
  }
}

//delete files
/*----------------------------------------------------------*/
function delfiles() {
  global $err;

  if(file_exists('./cdata.php')) {
    $rez=unlink('./cdata.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file cdata.php');return;}
  }

  if(file_exists('./errsold.php')) {
    $rez=unlink('./errsold.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file errsold.php');return;}
  }

  if(file_exists('./errors.php')) {
    $rez=unlink('./errors.php');
    if(!$rez) {$err->reason('uninst.php|delfiles|can\'t delete file errors.php');return;}
  }
}

?>
