<?PHP

include('./admin/_head.txt');
echo '<LINK href="./styles.css" rel=StyleSheet>';

error_reporting  (E_ERROR | E_PARSE);
include_once('./data/data.php');
$linkid = db_connect(); if (!$linkid) problem($s[db_error]);
if (ini_get("magic_quotes_sybase")) ini_set("magic_quotes_sybase",0);
if (!get_magic_quotes_gpc()) ini_set("magic_quotes_gpc",1);
if (!get_magic_quotes_runtime()) set_magic_quotes_runtime(1);

dq("insert into $s[pr]months1 values(1,1,1,1,1,1,1,1,1,1,1)",0);
$q = dq("select userid from $s[pr]months1 where number = '1' AND userid = '1'",0);
$r = mysql_fetch_row($q);
dq("delete from $s[pr]months1 where number = '1' AND userid = '1'",0);
if (!$r[0]) update_to_28(); // pokud $r[0] existuje tak má 2.8

echo eot('UPDATE SUCCESSFUL','Now remove \'update.php\' from your server.<br>Make sure to read Manual of what to do now.');
exit;






function update_to_28() {
global $s;
set_time_limit(300);
dq("ALTER TABLE $s[pr]categories CHANGE catname catname varchar(255) NOT NULL default ''",1);
dq("ALTER TABLE $s[pr]day ADD KEY number (number), ADD INDEX size (size)",1);
dq("ALTER TABLE $s[pr]members 
 CHANGE email email varchar(150) NOT NULL default '', 
 CHANGE siteurl siteurl varchar(150) default NULL, 
 CHANGE name name varchar(100) default NULL, 
 CHANGE affiliate affiliate varchar(20) default NULL, 
 ADD address VARCHAR(255) default NULL AFTER name, 
 ADD accepted tinyint(1) NOT NULL default '0' AFTER number, 
 ADD s_funds double(12,2) NOT NULL default '0.00' AFTER s_last",1);
dq("ALTER TABLE $s[pr]s_orders 
 CHANGE number number int(10) unsigned NOT NULL auto_increment, 
 CHANGE pack pack mediumint(8) unsigned NOT NULL default '0', 
 CHANGE price price mediumint(8) unsigned NOT NULL default '0', 
 CHANGE time order_time int(10) unsigned NOT NULL default '0', 
 ADD packname varchar(100) NOT NULL default '' AFTER pack,
 ADD bonus double(6,2) unsigned NOT NULL default '0.00' AFTER price,
 ADD value double(9,2) unsigned NOT NULL default '0.00' AFTER bonus,
 ADD paylink text AFTER value, 
 ADD control varchar(255) default NULL AFTER paylink,
 DROP size, DROP imp, DROP clicks, DROP paid",1);

for ($x=1;$x<=3;$x++)
{ dq("ALTER TABLE $s[pr]b$x ADD INDEX number (number)",1);
  dq("ALTER TABLE $s[pr]days$x DROP w, ADD KEY number (number), ADD INDEX d (d), ADD INDEX m (m), ADD INDEX y (y)",1);
  dq("ALTER TABLE $s[pr]link$x 
   CHANGE number number int(10) unsigned NOT NULL default '0',
   CHANGE cat cat smallint(6) default '0',
   CHANGE url1 url1 varchar(255) default NULL, CHANGE banner1 banner1 varchar(255) default NULL, CHANGE alt1 alt1 varchar(255) default NULL, 
   CHANGE url2 url2 varchar(255) default NULL, CHANGE banner2 banner2 varchar(255) default NULL, CHANGE alt2 alt2 varchar(255) default NULL, 
   CHANGE url3 url3 varchar(255) default NULL, CHANGE banner3 banner3 varchar(255) default NULL, CHANGE alt3 alt3 varchar(255) default NULL,
   ADD raw1 text AFTER alt1, ADD raw2 text AFTER alt2, ADD raw3 text AFTER alt3, 
   ADD ad_kind_1 varchar(15) NOT NULL default '' after raw1, ADD ad_kind_2 varchar(15) NOT NULL default '' after raw2, ADD ad_kind_3 varchar(15) NOT NULL default '' after raw3, 
   DROP flash1, DROP flash2, DROP flash3, 
   ADD def_number int(10) unsigned NOT NULL default '0' AFTER sponsor, 
   DROP PRIMARY KEY",1);
  dq("UPDATE $s[pr]link$x set ad_kind_1 = 'picture', ad_kind_2 = 'picture', ad_kind_3 = 'picture'",1);
  dq("ALTER TABLE $s[pr]stats$x 
   CHANGE free_imp i_free int(10) unsigned NOT NULL default '0', 
   CHANGE free_clicks c_free int(10) unsigned NOT NULL default '0',
   CHANGE refcredit i_refer mediumint(8) unsigned NOT NULL default '0',
   CHANGE weshow i_w int(10) unsigned NOT NULL default '0',
   CHANGE clicks c_w int(10) unsigned NOT NULL default '0',
   CHANGE meshows i_m int(10) unsigned NOT NULL default '0',
   CHANGE nu_imp i_nu decimal(10,2) unsigned NOT NULL default '0.00',
   CHANGE nu_clicks c_nu int(10) unsigned NOT NULL default '0',
   CHANGE klikunej c_m int(10) unsigned NOT NULL default '0',
   CHANGE time last int(10) unsigned NOT NULL default '0',
   CHANGE c1 c1 smallint(6) NOT NULL default '0',
   CHANGE c2 c2 smallint(6) NOT NULL default '0',
   CHANGE c3 c3 smallint(6) NOT NULL default '0',
   CHANGE c4 c4 smallint(6) NOT NULL default '0',
   CHANGE c5 c5 smallint(6) NOT NULL default '0',
   ADD no_slide tinyint(1) NOT NULL default '0' AFTER exratio, 
   ADD i_move_in decimal(10,2) unsigned NOT NULL default '0.00' AFTER c_m, 
   ADD i_move_out int(10) unsigned NOT NULL default '0' AFTER i_move_in, 
   DROP pu_imp, DROP pu_clicks, 
   ADD INDEX accept (accept), ADD INDEX approved (approved), ADD INDEX enable (enable), ADD INDEX category (category), ADD INDEX c0 (c0), ADD INDEX c1 (c1), ADD INDEX c2 (c2), ADD INDEX c3 (c3), ADD INDEX c4 (c4), ADD INDEX c5 (c5), ADD INDEX i_free (i_free), ADD INDEX c_free (c_free)",
   1);
  dq("DROP TABLE $s[pr]week$x",1); 
  dq("CREATE TABLE $s[pr]months$x (
   number int(11) NOT NULL default '0',
   userid varchar(15) NOT NULL default '',
   m tinyint(4) NOT NULL default '0',
   y smallint(6) NOT NULL default '0',
   i_m int(11) NOT NULL default '0',
   cl_m int(11) NOT NULL default '0',
   r_m decimal(4,2) NOT NULL default '0.00',
   i_w int(11) NOT NULL default '0',
   cl_w int(11) NOT NULL default '0',
   r_w decimal(4,2) NOT NULL default '0.00',
   sponsor tinyint(1) NOT NULL default '0')",1);

}

dq("DROP TABLE $s[pr]s_packs",1); dq("DROP TABLE $s[pr]def_ban",1);

dq("CREATE TABLE $s[pr]s_packs (
  number mediumint(9) NOT NULL auto_increment,
  descr varchar(150) NOT NULL default '',
  price mediumint(8) unsigned NOT NULL default '0',
  bonus double(6,2) unsigned NOT NULL default '0.00',
  value double(9,2) unsigned NOT NULL default '0.00',
  payhtml text NOT NULL,
  PRIMARY KEY  (number))",1);
dq("CREATE TABLE $s[pr]def_ads (
  number int(11) NOT NULL auto_increment,
  size tinyint(1) NOT NULL default '0',
  description varchar(100) NOT NULL default '',
  enable tinyint(1) NOT NULL default '0',
  c0 tinyint(1) NOT NULL default '0',
  c1 smallint(6) NOT NULL default '0',
  c2 smallint(6) NOT NULL default '0',
  c3 smallint(6) NOT NULL default '0',
  c4 smallint(6) NOT NULL default '0',
  c5 smallint(6) NOT NULL default '0',
  ad1 text,
  ad2 text,
  ad3 text,
  UNIQUE KEY number (number))",1);
dq("CREATE TABLE $s[pr]ratios (
  size tinyint(1) NOT NULL default '0',
  min decimal(5,2) unsigned NOT NULL default '0.00',
  max decimal(5,2) unsigned NOT NULL default '0.00',
  ratio decimal(4,2) unsigned NOT NULL default '0.00')",1);

// zkontrolovat!
$q = dq("select number,accept from $s[pr]stats1",1);
while ($a = mysql_fetch_row($q))
{ set_time_limit(30);
  dq("update $s[pr]members set accepted = '$a[1]' where number = '$a[0]'");
}

hlaseni ('Update to version 2.8 successful');
}








function chyba($text,$fatal) {
echo '<span class="text13blue"><b>'.$text.'</b></span><br>';
if ($fatal) { echo '<span class="text13blue"><b><br>Can\'t continue!</b></span><br>'; exit(); }
}


function hlaseni($text) {
echo '<span class="text13">'.$text.'</span><br>';
}

###############################################################################
###############################################################################
###############################################################################

function db_connect() {
global $s,$m;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "$m[dbconnecterror] $s[dbhost]."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
return $link_id;
}

###############################################################################

function dq($query,$check) {
global $s;
$q = mysql_query($query);
if ( ($check) AND (!$q) ) problem(mysql_error());
return $q;
}

###############################################################################
###############################################################################
###############################################################################

function iot($info) {
return '<span class="text13blue"><b>'.$info.'</b></span><br><br>';
}

###############################################################################

function eot($line1,$line2) {
$a = '<span class="text13blue"><b>'.$line1.'</b></span>';
if ($line2) $a .= '<br><span class="text13">'.$line2.'</span>';
$a .= '<br><br>';
return $a;
}

?>