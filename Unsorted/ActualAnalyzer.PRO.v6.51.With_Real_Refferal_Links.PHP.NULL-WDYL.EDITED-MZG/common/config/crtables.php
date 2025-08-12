<?php

  $this->link=@mysql_connect($this->dbhost,$this->dbuser,$this->dbpass);
  if(!$this->link) {$err->reason('config.php|crtables|connection with mysql server has failed');return;}
  $rez=mysql_select_db($this->dbase);
  if(!$rez) {$err->reason('config.php|crtables|the request \'use '.$this->dbase.'\' has failed -- '.mysql_error());return;}

  $request='SHOW TABLES LIKE "aa_%"';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $tables=array();
  while($row=mysql_fetch_row($result)) $tables[$row[0]]=0;
  mysql_free_result($result);

  require './common/config/create.php';

  // LITE
  if(!isset($tables['aa_groups'])) {
      newgr($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newgr\' function has failed');return;}
  }
  if(!isset($tables['aa_pages'])) {
      newpg($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newpg\' function has failed');return;}
  }
  if(!isset($tables['aa_hosts'])) {
      newip($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newip\' function has failed');return;}
  }
  if(!isset($tables['aa_hours'])) {
      newhours($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newhours\' function has failed');return;}
  }
  if(!isset($tables['aa_days'])) {
      newdays($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newdays\' function has failed');return;}
  }
  if(!isset($tables['aa_total'])) {
      newtotal($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newtotal\' function has failed');return;}
  }
  if(!isset($tables['aa_ref_base'])) {
      newrb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newrb\' function has failed');return;}
  }
  if(!isset($tables['aa_domains'])) {
      newdm($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newdm\' function has failed');return;}
  }
  if(!isset($tables['aa_ref_total'])) {
      newrt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newrt\' function has failed');return;}
  }
  if(!isset($tables['aa_tmp'])) {
      newtmp($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newtmp\' function has failed');return;}
  }
  // STANDARD
  if(!isset($tables['aa_lang_base'])) {
      newlb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newlb\' function has failed');return;}
  }
  if(!isset($tables['aa_coun_base'])) {
      newcb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newcb\' function has failed');return;}
  }
  if(!isset($tables['aa_lang_total'])) {
      newlt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newlt\' function has failed');return;}
  }
  if(!isset($tables['aa_coun_total'])) {
      newct($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newct\' function has failed');return;}
  }
  if(!isset($tables['aa_st_base'])) {
      newsb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newsb\' function has failed');return;}
  }
  if(!isset($tables['aa_st_total'])) {
      newst($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newst\' function has failed');return;}
  }
  if(!isset($tables['aa_raw_dom'])) {
      newrawd($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newrawd\' function has failed');return;}
  }
  if(!isset($tables['aa_raw'])) {
      newraw($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newraw\' function has failed');return;}
  }
  // PRO
  if(!isset($tables['aa_points'])) {
      newpoints($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newpoints\' function has failed');return;}
  }
  if(!isset($tables['aa_vectors'])) {
      newvectors($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newvectors\' function has failed');return;}
  }
  if(!isset($tables['aa_depthes'])) {
      newdepthes($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newdepthes\' function has failed');return;}
  }
  if(!isset($tables['aa_times'])) {
      newtimes($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newtimes\' function has failed');return;}
  }
  if(!isset($tables['aa_eng_base'])) {
      newengb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newengb\' function has failed');return;}
  }
  if(!isset($tables['aa_eng_total'])) {
      newengt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newengt\' function has failed');return;}
  }
  if(!isset($tables['aa_key_base'])) {
      newkeyb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newkeyb\' function has failed');return;}
  }
  if(!isset($tables['aa_key_total'])) {
      newkeyt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newkeyt\' function has failed');return;}
  }
  if(!isset($tables['aa_frm_base'])) {
      newfrmb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newfrmb\' function has failed');return;}
  }
  if(!isset($tables['aa_frm_total'])) {
      newfrmt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newfrmt\' function has failed');return;}
  }
  if(!isset($tables['aa_zones'])) {
      newzones($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newzones\' function has failed');return;}
  }
  if(!isset($tables['aa_prv_base'])) {
      newprvb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newprvb\' function has failed');return;}
  }
  if(!isset($tables['aa_prv_total'])) {
      newprvt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newprvt\' function has failed');return;}
  }
  if(!isset($tables['aa_prx_base'])) {
      newprxb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newprxb\' function has failed');return;}
  }
  if(!isset($tables['aa_prx_total'])) {
      newprxt($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newprxt\' function has failed');return;}
  }
  if(!isset($tables['aa_rdata'])) {
      newrdata($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newrdata\' function has failed');return;}
  }
  if(!isset($tables['aa_confdb'])) {
      newconfdb($this->link);
      if($err->flag) {$err->reason('config.php|crtables|\'newconfdb\' function has failed');return;}
  }

  $request='LOCK TABLES aa_groups WRITE, aa_lang_base WRITE, aa_coun_base WRITE, aa_st_base WRITE,aa_points WRITE,aa_eng_base WRITE';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  $request='SELECT * FROM aa_groups WHERE id=201';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if(!mysql_num_rows($result)) {
      mysql_free_result($result);
      $request='INSERT INTO aa_groups (id,flags1,flags2,flags3,flags4,flags5,flags6,flags7,name,added,vmin,vmax,hsmin,hsmax,htmin,htmax,rmin,rmax,first_t,last_t) VALUES (201,0,0,0,0,0,0,0,"'._ALLPGS.'",'.$this->ctime.',0,0,0,0,0,0,0,0,0,0)';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  else mysql_free_result($result);

  require($this->rf.'data/bases/language.php');
  require($this->rf.'data/bases/country.php');
  require($this->rf.'data/bases/os.php');
  require($this->rf.'data/bases/browser.php');
  require($this->rf.'data/bases/ename.php');

  // Loading of languages base
  $request='DELETE FROM aa_lang_base WHERE 1=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  reset($lang);
  $num=0;
  while($k=key($lang)) {
      $num++;
      $request='INSERT INTO aa_lang_base (langid,sname,lname) VALUES ('.$num.',"'.$k.'","'.$lang[$k].'")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      next($lang);
  }
  $request='INSERT INTO aa_lang_base (langid,sname,lname) VALUES (255,"--","undefined")';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  // Loading of countries base
  $request='DELETE FROM aa_coun_base WHERE 1=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  reset($country);
  $num=0;
  while($k=key($country)) {
      $num++;
      $request='INSERT INTO aa_coun_base (counid,sname,lname) VALUES ('.$num.',"'.$k.'","'.$country[$k].'")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      next($country);
  }
  $request='INSERT INTO aa_coun_base (counid,sname,lname) VALUES (1000,"unknown","undefined")';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  // Loading of OS/browsers base
  $request='DELETE FROM aa_st_base WHERE stid>1000 AND stid<=3000';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  reset($browser);
  $num=1000;
  while($k=key($browser)) {
      $num++;
      $request='INSERT INTO aa_st_base (stid,fname,stname) VALUES ('.$num.',"'.$k.'","'.$browser[$k].'")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      next($browser);
  }
  $request='INSERT INTO aa_st_base (stid,fname,stname) VALUES (2000,"unknown","undefined")';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  reset($os);
  $num=2000;
  while($k=key($os)) {
      $num++;
      $request='INSERT INTO aa_st_base (stid,fname,stname) VALUES ('.$num.',"'.$k.'","'.$os[$k].'")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      next($os);
  }
  $request='INSERT INTO aa_st_base (stid,fname,stname) VALUES (3000,"unknown","undefined")';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  // Loading of 'undefined' color, JavaScript and resolution to aa_st_base
  $request='SELECT stid FROM aa_st_base WHERE stid IN (1000,4000,5000)';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $st=array();
  while($row=mysql_fetch_object($result)) $st[$row->stid]=1;
  mysql_free_result($result);

  if(!isset($st[1000])) {
      $request='INSERT INTO aa_st_base (stid,stname) VALUES (1000,"undefined")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  if(!isset($st[4000])) {
      $request='INSERT INTO aa_st_base (stid,stname) VALUES (4000,"undefined")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }
  if(!isset($st[5000])) {
      $request='INSERT INTO aa_st_base (stid,stname) VALUES (5000,"undefined")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  }

  $request='DELETE FROM aa_points WHERE 1=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  for($i=1;$i<4;$i++) {
      for($j=1;$j<221;$j++) {
          $request='INSERT INTO aa_points (flag,id) VALUES ('.$i.','.$j.')';
          $result=mysql_query($request,$this->link);
          if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      }
  }
  $request='DELETE FROM aa_eng_base WHERE 1=1';
  $result=mysql_query($request,$this->link);
  if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
  $i=0;
  reset($ename);
  while($k=key($ename)) {
      $i++;
      $request='INSERT INTO aa_eng_base (engid,name) VALUES ('.$i.',"'.$ename[$k].'")';
      $result=mysql_query($request,$this->link);
      if(!$result) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}
      next($ename);
  }

  $request='UNLOCK TABLES';
  $resultu=mysql_query($request,$this->link);
  if(!$resultu) {$err->reason('config.php|crtables|the request \''.$request.'\' has failed -- '.mysql_error());return;}

  if($this->link) {
    $rez=mysql_close($this->link);
    if(!$rez) {$err->reason('config.php|crtables|disconnect with mysql server has failed');return;}
  }

?>
