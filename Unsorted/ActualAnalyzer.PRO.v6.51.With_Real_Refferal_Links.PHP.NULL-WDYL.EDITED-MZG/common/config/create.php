<?php

//===================================================================
function newtotal($link) {
  global $err;
  // Create the table aa_total.
$request=<<< NEWTOTAL
CREATE TABLE aa_total
(
 time TINYINT(1) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWTOTAL;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newtotal|the request \'create table aa_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newdays($link) {
  global $err;
  // Create the table aa_days.
$request=<<< NEWDAYS
CREATE TABLE aa_days
(
 time SMALLINT(2) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors_t INT(4) UNSIGNED NOT NULL,
 visitors_m INT(4) UNSIGNED NOT NULL,
 visitors_w INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWDAYS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newdays|the request \'create table aa_days\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newhours($link) {
  global $err;
  // Create the table aa_hours.
$request=<<< NEWHOURS
CREATE TABLE aa_hours
(
 time SMALLINT(2) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 visitors MEDIUMINT(3) UNSIGNED NOT NULL,
 hosts MEDIUMINT(3) UNSIGNED NOT NULL,
 hits MEDIUMINT(3) UNSIGNED NOT NULL,
 INDEX(time,id)
)
NEWHOURS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newhours|the request \'create table aa_hours\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newpg($link) {
  global $err;
  // Create the table aa_pages.
$request=<<< NEWPG
CREATE TABLE aa_pages
(
 id TINYINT(1) UNSIGNED NOT NULL,
 uid INT(4) UNSIGNED NOT NULL,
 name CHAR(255) NOT NULL,
 url CHAR(255) NOT NULL,
 imgid TINYINT(1) UNSIGNED NOT NULL,
 flags TINYINT(1) UNSIGNED NOT NULL,
 rgb MEDIUMINT(3) UNSIGNED NOT NULL,
 defpg TINYINT(1) UNSIGNED NOT NULL,
 defurl CHAR(255) NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 first_t INT(4) UNSIGNED NOT NULL,
 last_t INT(4) UNSIGNED NOT NULL,
 vmin INT(4) UNSIGNED NOT NULL,
 vmax INT(4) UNSIGNED NOT NULL,
 hsmin INT(4) UNSIGNED NOT NULL,
 hsmax INT(4) UNSIGNED NOT NULL,
 htmin INT(4) UNSIGNED NOT NULL,
 htmax INT(4) UNSIGNED NOT NULL,
 rmin INT(4) UNSIGNED NOT NULL,
 rmax INT(4) UNSIGNED NOT NULL,
 PRIMARY KEY(id,uid),
 INDEX(added)
)
NEWPG;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newpg|the request \'create table aa_pages\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newgr($link) {
  global $err;
  // Create the table aa_groups.
$request=<<< NEWGR
CREATE TABLE aa_groups
(
 id TINYINT(1) UNSIGNED NOT NULL PRIMARY KEY,
 flags1 INT(4) UNSIGNED NOT NULL,
 flags2 INT(4) UNSIGNED NOT NULL,
 flags3 INT(4) UNSIGNED NOT NULL,
 flags4 INT(4) UNSIGNED NOT NULL,
 flags5 INT(4) UNSIGNED NOT NULL,
 flags6 INT(4) UNSIGNED NOT NULL,
 flags7 INT(4) UNSIGNED NOT NULL,
 name CHAR(255) NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 first_t INT(4) UNSIGNED NOT NULL,
 last_t INT(4) UNSIGNED NOT NULL,
 vmin INT(4) UNSIGNED NOT NULL,
 vmax INT(4) UNSIGNED NOT NULL,
 hsmin INT(4) UNSIGNED NOT NULL,
 hsmax INT(4) UNSIGNED NOT NULL,
 htmin INT(4) UNSIGNED NOT NULL,
 htmax INT(4) UNSIGNED NOT NULL,
 rmin INT(4) UNSIGNED NOT NULL,
 rmax INT(4) UNSIGNED NOT NULL,
 INDEX(added)
)
NEWGR;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newgr|the request \'create table aa_groups\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newip($link) {
  global $err;
  // Create the table aa_hosts.
$request =<<< NEWIP
CREATE TABLE aa_hosts
(
 ip INT(4) NOT NULL PRIMARY KEY,
 flags1 INT(4) UNSIGNED NOT NULL,
 flags2 INT(4) UNSIGNED NOT NULL,
 flags3 INT(4) UNSIGNED NOT NULL,
 flags4 INT(4) UNSIGNED NOT NULL,
 flags5 INT(4) UNSIGNED NOT NULL,
 flags6 INT(4) UNSIGNED NOT NULL,
 flags7 INT(4) UNSIGNED NOT NULL
)
NEWIP;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newip|the request \'create table aa_hosts\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrb($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWRB
CREATE TABLE aa_ref_base
(
 refid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 flag TINYINT(1) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 url VARCHAR(255) NOT NULL,
 INDEX (url(40))
)
NEWRB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrb|the request \'create table aa_ref_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newdm($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWDM
CREATE TABLE aa_domains
(
 domid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 domain CHAR(60) NOT NULL,
 INDEX (domain(10))
)
NEWDM;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrb|the request \'create table aa_domains\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newtmp($link) {
  global $err;
  // Create the table aa_ref_base.
$request =<<< NEWTMP
CREATE TABLE aa_tmp
(
 refid SMALLINT(2) UNSIGNED NOT NULL,
 visitors INT(4) UNSIGNED NOT NULL,
 hosts INT(4) UNSIGNED NOT NULL,
 hits INT(4) UNSIGNED NOT NULL,
 reloads INT(4) UNSIGNED NOT NULL
)
NEWTMP;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newtmp|the request \'create table aa_tmp\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrt($link) {
  global $err;
  // Create the table aa_ref_total.
$request=<<< NEWRT
CREATE TABLE aa_ref_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 refid SMALLINT(2) UNSIGNED NOT NULL,
 domid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,refid)
)
NEWRT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrt|the request \'create table aa_ref_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newlb($link) {
  global $err;
  // Create the table aa_lang_base.
$request =<<< NEWLB
CREATE TABLE aa_lang_base
(
 langid TINYINT(1) UNSIGNED NOT NULL,
 sname CHAR(2) NOT NULL PRIMARY KEY,
 lname CHAR(100) NOT NULL,
 INDEX (langid)
)
NEWLB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newlb|the request \'create table aa_lang_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newcb($link) {
  global $err;
  // Create the table aa_coun_base.
$request =<<< NEWCB
CREATE TABLE aa_coun_base
(
 counid SMALLINT(2) UNSIGNED NOT NULL,
 sname CHAR(8) NOT NULL PRIMARY KEY,
 lname CHAR(100) NOT NULL,
 INDEX (counid)
)
NEWCB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newcb|the request \'create table aa_coun_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newlt($link) {
  global $err;
  // Create the table aa_lang_total.
$request =<<< NEWLT
CREATE TABLE aa_lang_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 langid TINYINT(1) UNSIGNED NOT NULL,
 counid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX (id,langid,counid)
)
NEWLT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newlt|the request \'create table aa_lang_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newct($link) {
  global $err;
  // Create the table aa_coun_total.
$request =<<< NEWCT
CREATE TABLE aa_coun_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 counid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX (id,counid)
)
NEWCT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newct|the request \'create table aa_coun_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newsb($link) {
  global $err;
  // Create the table aa_st_base.
$request =<<< NEWSB
CREATE TABLE aa_st_base
(
 stid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 fname CHAR(8) NOT NULL,
 stname CHAR(100) NOT NULL,
 INDEX (stname(20))
)
NEWSB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newsb|the request \'create table aa_st_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newst($link) {
  global $err;
  // Create the table aa_st_total.
$request =<<< NEWST
CREATE TABLE aa_st_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 stid SMALLINT(2) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX (id,stid)
)
NEWST;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newst|the request \'create table aa_st_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrawd($link) {
  global $err;
  // Create the table aa_raw_dom.
$request =<<< NEWRAWD
CREATE TABLE aa_raw_dom
(
 domid INT(4) UNSIGNED NOT NULL,
 domain CHAR(60) NOT NULL,
 count SMALLINT(2) NOT NULL,
 INDEX (domain(10),count)
)
NEWRAWD;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrawd|the request \'create table aa_raw_dom\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newraw($link) {
  global $err;
  // Create the table aa_raw.
$request =<<< NEWRAW
CREATE TABLE aa_raw
(
 num INT(4) UNSIGNED NOT NULL PRIMARY KEY,
 time INT(4) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 vid INT(4) UNSIGNED NOT NULL,
 host INT(4) NOT NULL,
 domid INT(4) UNSIGNED NOT NULL,
 refid SMALLINT(2) UNSIGNED NOT NULL,
 langid TINYINT(1) UNSIGNED NOT NULL,
 lcounid SMALLINT(2) UNSIGNED NOT NULL,
 counid SMALLINT(2) UNSIGNED NOT NULL,
 brid SMALLINT(2) UNSIGNED NOT NULL,
 osid SMALLINT(2) UNSIGNED NOT NULL,
 resid SMALLINT(2) UNSIGNED NOT NULL,
 colid SMALLINT(2) UNSIGNED NOT NULL,
 jsid SMALLINT(2) UNSIGNED NOT NULL,
 cookieid TINYINT(1) UNSIGNED NOT NULL,
 javaid TINYINT(1) UNSIGNED NOT NULL,
 frstime INT(4) UNSIGNED NOT NULL,
 lstime INT(4) UNSIGNED NOT NULL,
 engid SMALLINT(2) UNSIGNED NOT NULL,
 keyid SMALLINT(2) UNSIGNED NOT NULL,
 frmid SMALLINT(2) UNSIGNED NOT NULL,
 zoneid TINYINT(1) UNSIGNED NOT NULL,
 prvid SMALLINT(2) UNSIGNED NOT NULL,
 prxid SMALLINT(2) UNSIGNED NOT NULL,
 prxip INT(4) NOT NULL,
 depth TINYINT(1) UNSIGNED NOT NULL,
 hits SMALLINT(2) UNSIGNED NOT NULL,
 INDEX (id)
)
NEWRAW;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newraw|the request \'create table aa_raw\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newpoints($link) {
  global $err;
  // Create the table aa_points.
$request=<<<NEWPOINTS
CREATE TABLE aa_points
(
 flag TINYINT(1) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,flag)
)
NEWPOINTS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newpoints|the request \'create table aa_points\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newvectors($link) {
  global $err;
  // Create the table aa_vectors.
$request=<<<NEWVECTORS
CREATE TABLE aa_vectors
(
 sourid TINYINT(1) UNSIGNED NOT NULL,
 destid TINYINT(1) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(sourid,destid)
)
NEWVECTORS;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newpoints|the request \'create table aa_vectors\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newdepthes($link) {
  global $err;
  // Create the table  aa_depthes.
$request=<<<NEWDEPTHES
CREATE TABLE  aa_depthes
(
 id TINYINT(1) UNSIGNED NOT NULL,
 pages TINYINT(1) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,pages)
)
NEWDEPTHES;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newdepthes|the request \'create table aa_depthes\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newtimes($link) {
  global $err;
  // Create the table  aa_times.
$request=<<<NEWTIMES
CREATE TABLE  aa_times
(
 flag TINYINT(1) UNSIGNED NOT NULL,
 id TINYINT(1) UNSIGNED NOT NULL,
 rangeid TINYINT(1) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,rangeid)
)
NEWTIMES;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newtimes|the request \'create table aa_times\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newengb($link) {
  global $err;
  // Create the table aa_eng_base.
$request =<<< NEWENGB
CREATE TABLE aa_eng_base
(
 engid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 name VARCHAR(255) NOT NULL,
 INDEX(name(20))
)
NEWENGB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newengb|the request \'create table aa_eng_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newengt($link) {
  global $err;
  // Create the table  aa_eng_total.
$request=<<<NEWENGT
CREATE TABLE  aa_eng_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 engid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,engid)
)
NEWENGT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newengt|the request \'create table aa_eng_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newkeyb($link) {
  global $err;
  // Create the table aa_key_base.
$request =<<< NEWENGB
CREATE TABLE aa_key_base
(
 keyid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 flag TINYINT(1) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 name VARCHAR(255) NOT NULL,
 INDEX(name(20))
)
NEWENGB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newkeyb|the request \'create table aa_key_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newkeyt($link) {
  global $err;
  // Create the table  aa_key_total.
$request=<<<NEWKEYT
CREATE TABLE  aa_key_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 keyid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,keyid)
)
NEWKEYT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newkeyt|the request \'create table aa_key_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newfrmb($link) {
  global $err;
  // Create the table aa_frm_base.
$request =<<< NEWFRMB
CREATE TABLE aa_frm_base
(
 frmid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 name VARCHAR(255) NOT NULL,
 INDEX(name(40))
)
NEWFRMB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newfrmb|the request \'create table aa_frm_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newfrmt($link) {
  global $err;
  // Create the table  aa_frm_total.
$request=<<<NEWFRMT
CREATE TABLE  aa_frm_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 frmid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,frmid)
)
NEWFRMT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newfrmt|the request \'create table aa_frm_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newzones($link) {
  global $err;
  // Create the table  aa_zones.
$request=<<<NEWFRMT
CREATE TABLE  aa_zones
(
 id TINYINT(1) UNSIGNED NOT NULL,
 zoneid SMALLINT(2) UNSIGNED NOT NULL,
 modify INT(4) UNSIGNED NOT NULL,
 vt MEDIUMINT(3) UNSIGNED NOT NULL,
 hst MEDIUMINT(3) UNSIGNED NOT NULL,
 htt MEDIUMINT(3) UNSIGNED NOT NULL,
 vy MEDIUMINT(3) UNSIGNED NOT NULL,
 hsy MEDIUMINT(3) UNSIGNED NOT NULL,
 hty MEDIUMINT(3) UNSIGNED NOT NULL,
 vw MEDIUMINT(3) UNSIGNED NOT NULL,
 hsw MEDIUMINT(3) UNSIGNED NOT NULL,
 htw MEDIUMINT(3) UNSIGNED NOT NULL,
 vlw MEDIUMINT(3) UNSIGNED NOT NULL,
 hslw MEDIUMINT(3) UNSIGNED NOT NULL,
 htlw MEDIUMINT(3) UNSIGNED NOT NULL,
 vm INT(4) UNSIGNED NOT NULL,
 hsm INT(4) UNSIGNED NOT NULL,
 htm INT(4) UNSIGNED NOT NULL,
 vlm INT(4) UNSIGNED NOT NULL,
 hslm INT(4) UNSIGNED NOT NULL,
 htlm INT(4) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,zoneid)
)
NEWFRMT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newzones|the request \'create table aa_zones\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newprvb($link) {
  global $err;
  // Create the table aa_prv_base.
$request =<<< NEWPRVB
CREATE TABLE aa_prv_base
(
 prvid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 counid SMALLINT(2) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 name CHAR(30) NOT NULL,
 INDEX(name(10),counid)
)
NEWPRVB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newprvb|the request \'create table aa_prv_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newprvt($link) {
  global $err;
  // Create the table  aa_prv_total.
$request=<<<NEWPRVT
CREATE TABLE  aa_prv_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 prvid SMALLINT(2) UNSIGNED NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,prvid)
)
NEWPRVT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newprvt|the request \'create table aa_prv_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newprxb($link) {
  global $err;
  // Create the table aa_prx_base.
$request=<<<NEWPRXB
CREATE TABLE aa_prx_base
(
 prxid SMALLINT(2) UNSIGNED NOT NULL PRIMARY KEY,
 added INT(4) UNSIGNED NOT NULL,
 count INT(4) UNSIGNED NOT NULL,
 name CHAR(60) NOT NULL,
 INDEX(name(20))
)
NEWPRXB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newpxrb|the request \'create table aa_prx_base\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newprxt($link) {
  global $err;
  // Create the table  aa_prx_total.
$request=<<<NEWPRXT
CREATE TABLE  aa_prx_total
(
 id TINYINT(1) UNSIGNED NOT NULL,
 prxid SMALLINT(2) UNSIGNED NOT NULL,
 ip INT(4) NOT NULL,
 v1 INT(4) UNSIGNED NOT NULL,
 hs1 INT(4) UNSIGNED NOT NULL,
 ht1 INT(4) UNSIGNED NOT NULL,
 v2 INT(4) UNSIGNED NOT NULL,
 hs2 INT(4) UNSIGNED NOT NULL,
 ht2 INT(4) UNSIGNED NOT NULL,
 v3 INT(4) UNSIGNED NOT NULL,
 hs3 INT(4) UNSIGNED NOT NULL,
 ht3 INT(4) UNSIGNED NOT NULL,
 v4 INT(4) UNSIGNED NOT NULL,
 hs4 INT(4) UNSIGNED NOT NULL,
 ht4 INT(4) UNSIGNED NOT NULL,
 v5 INT(4) UNSIGNED NOT NULL,
 hs5 INT(4) UNSIGNED NOT NULL,
 ht5 INT(4) UNSIGNED NOT NULL,
 v6 INT(4) UNSIGNED NOT NULL,
 hs6 INT(4) UNSIGNED NOT NULL,
 ht6 INT(4) UNSIGNED NOT NULL,
 v7 INT(4) UNSIGNED NOT NULL,
 hs7 INT(4) UNSIGNED NOT NULL,
 ht7 INT(4) UNSIGNED NOT NULL,
 INDEX(id,prxid,ip)
)
NEWPRXT;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newprxt|the request \'create table aa_prx_total\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newrdata($link) {
  global $err;
  // Create the table  aa_rdata.
$request=<<<NEWRDATA
CREATE TABLE  aa_rdata
(
 id INT(4) UNSIGNED NOT NULL,
 added INT(4) UNSIGNED NOT NULL,
 num TINYINT(1) UNSIGNED NOT NULL,
 name CHAR(255) NOT NULL,
 addpar CHAR(255) NOT NULL,
 vi INT(4) NOT NULL,
 vp FLOAT(10,2) NOT NULL,
 v INT(4) UNSIGNED NOT NULL,
 hsi INT(4) NOT NULL,
 hsp FLOAT(10,2) NOT NULL,
 hs INT(4) UNSIGNED NOT NULL,
 ri INT(4) NOT NULL,
 rp FLOAT(10,2) NOT NULL,
 r INT(4) UNSIGNED NOT NULL,
 hti INT(4) NOT NULL,
 htp FLOAT(10,2) NOT NULL,
 ht INT(4) UNSIGNED NOT NULL,
 INDEX(added,id)
)
NEWRDATA;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newrdata|the request \'create table aa_rdata\' has failed -- '.mysql_error());return;}
}
//===================================================================
function newconfdb($link) {
  global $err;
  // Create the table  aa_confdb.
$request=<<<NEWCONFDB
CREATE TABLE  aa_confdb
(
 var CHAR(25) NOT NULL,
 val CHAR(255) NOT NULL
)
NEWCONFDB;
  $result=mysql_query($request,$link);
  if(!$result) {$err->reason('create.php|newconfdb|the request \'create table aa_confdb\' has failed -- '.mysql_error());return;}
}
//===================================================================

?>
