<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

error_reporting  (E_ERROR | E_PARSE);
if (!$HTTP_POST_VARS) form($HTTP_SERVER_VARS[PATH_TRANSLATED]);

include('./admin/_head.txt');
echo '<LINK href="./styles.css" rel=StyleSheet>';

if (!$HTTP_POST_VARS[dbhost]) $chyby[] = 'Mysql database host is missing';
if (!$HTTP_POST_VARS[nodbpass])
{ if (!$HTTP_POST_VARS[dbusername]) $chyby[] = 'Mysql database username is missing';
  if (!$HTTP_POST_VARS[dbpassword]) $chyby[] = 'Password to mysql database is missing';
}
if (!$HTTP_POST_VARS[dbname]) $chyby[] = 'Missing name of your mysql database';
if (!eregi("^[a-z_]+$",$HTTP_POST_VARS[pr])) $chyby[] = 'Missing or wrong prefix of your tables';
if (!$HTTP_POST_VARS[phppath]) $chyby[] = 'Full path to your php folder is missing';
if ($chyby) chyba('<br>One or more errors found. Please go back and try again.<br><br>Errors:<br>'.implode('<br>',$chyby),1);

$phppath = ereg_replace ("[\]",'/',$HTTP_POST_VARS[phppath]);
$data = "<?PHP\n
\$s[dbhost] = '$HTTP_POST_VARS[dbhost]';
\$s[dbusername] = '$HTTP_POST_VARS[dbusername]';
\$s[dbpassword] = '$HTTP_POST_VARS[dbpassword]';
\$s[dbname] = '$HTTP_POST_VARS[dbname]';
\$s[nodbpass] = '$HTTP_POST_VARS[nodbpass]';
\$s[phppath] = '$phppath';
\$s[pr] = '$HTTP_POST_VARS[pr]';
\n?>";
create_write_file("$phppath/data/data.php",$data,1,0666,1);

$data = '<?PHP $s[daily]=1;$s[d]=100;$s[mini]=1;$s[lock]=1; ?>';
create_write_file("$phppath/data/time.php",$data,0,0666,1);

$data = "AuthName \"BANNED\"\nAuthType Basic\nAuthUserFile /dev/null\nAuthGroupFile /dev/null\n\nrequire valid-user\n\n";
create_write_file("$phppath/data/.htaccess",$data,0,0644,0);

include("$phppath/data/data.php");
$linkid = db_connect(); if (!$linkid) chyba($s[db_error],1);

$chyby = $uzbylo = 0;

$t[] = "$s[pr]b1";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  i1 int(11) NOT NULL default '0',
  c1 int(11) NOT NULL default '0',
  res1 int(11) NOT NULL default '0',
  i2 int(11) NOT NULL default '0',
  c2 int(11) NOT NULL default '0',
  res2 int(11) NOT NULL default '0',
  i3 int(11) NOT NULL default '0',
  c3 int(11) NOT NULL default '0',
  res3 int(11) NOT NULL default '0',
  INDEX number (number)
)";

$t[] = "$s[pr]b2";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  i1 int(11) NOT NULL default '0',
  c1 int(11) NOT NULL default '0',
  res1 int(11) NOT NULL default '0',
  i2 int(11) NOT NULL default '0',
  c2 int(11) NOT NULL default '0',
  res2 int(11) NOT NULL default '0',
  i3 int(11) NOT NULL default '0',
  c3 int(11) NOT NULL default '0',
  res3 int(11) NOT NULL default '0',
  INDEX number (number)
)";

$t[] = "$s[pr]b3";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  i1 int(11) NOT NULL default '0',
  c1 int(11) NOT NULL default '0',
  res1 int(11) NOT NULL default '0',
  i2 int(11) NOT NULL default '0',
  c2 int(11) NOT NULL default '0',
  res2 int(11) NOT NULL default '0',
  i3 int(11) NOT NULL default '0',
  c3 int(11) NOT NULL default '0',
  res3 int(11) NOT NULL default '0',
  INDEX number (number)
)";

$t[] = "$s[pr]blacklist";
$q[] = "(
  url varchar(100) NOT NULL default '',
  PRIMARY KEY  (url)
)";

$t[] = "$s[pr]categories";
$q[] = "(
  size tinyint(1) NOT NULL default '0',
  catid smallint(6) NOT NULL default '0',
  catname varchar(255) NOT NULL default ''
)";

$t[] = "$s[pr]day";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  size tinyint(1) NOT NULL default '0',
  cl_m mediumint(9) NOT NULL default '0',
  cl_w mediumint(9) NOT NULL default '0',
  m0 int(11) NOT NULL default '0',
  m1 int(11) NOT NULL default '0',
  m2 int(11) NOT NULL default '0',
  m3 int(11) NOT NULL default '0',
  m4 int(11) NOT NULL default '0',
  m5 int(11) NOT NULL default '0',
  m6 int(11) NOT NULL default '0',
  m7 int(11) NOT NULL default '0',
  m8 int(11) NOT NULL default '0',
  m9 int(11) NOT NULL default '0',
  m10 int(11) NOT NULL default '0',
  m11 int(11) NOT NULL default '0',
  m12 int(11) NOT NULL default '0',
  m13 int(11) NOT NULL default '0',
  m14 int(11) NOT NULL default '0',
  m15 int(11) NOT NULL default '0',
  m16 int(11) NOT NULL default '0',
  m17 int(11) NOT NULL default '0',
  m18 int(11) NOT NULL default '0',
  m19 int(11) NOT NULL default '0',
  m20 int(11) NOT NULL default '0',
  m21 int(11) NOT NULL default '0',
  m22 int(11) NOT NULL default '0',
  m23 int(11) NOT NULL default '0',
  w0 int(11) NOT NULL default '0',
  w1 int(11) NOT NULL default '0',
  w2 int(11) NOT NULL default '0',
  w3 int(11) NOT NULL default '0',
  w4 int(11) NOT NULL default '0',
  w5 int(11) NOT NULL default '0',
  w6 int(11) NOT NULL default '0',
  w7 int(11) NOT NULL default '0',
  w8 int(11) NOT NULL default '0',
  w9 int(11) NOT NULL default '0',
  w10 int(11) NOT NULL default '0',
  w11 int(11) NOT NULL default '0',
  w12 int(11) NOT NULL default '0',
  w13 int(11) NOT NULL default '0',
  w14 int(11) NOT NULL default '0',
  w15 int(11) NOT NULL default '0',
  w16 int(11) NOT NULL default '0',
  w17 int(11) NOT NULL default '0',
  w18 int(11) NOT NULL default '0',
  w19 int(11) NOT NULL default '0',
  w20 int(11) NOT NULL default '0',
  w21 int(11) NOT NULL default '0',
  w22 int(11) NOT NULL default '0',
  w23 int(11) NOT NULL default '0',
  INDEX number (number),
  INDEX size (size)
)";

$t[] = "$s[pr]days1";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  d tinyint(4) NOT NULL default '0',
  m tinyint(4) NOT NULL default '0',
  y smallint(6) NOT NULL default '0',
  i_m int(11) NOT NULL default '0',
  i_w int(11) NOT NULL default '0',
  cl_m mediumint(9) NOT NULL default '0',
  cl_w mediumint(9) NOT NULL default '0',
  INDEX number (number),
  INDEX d (d),
  INDEX m (m),
  INDEX y (y)
)";

$t[] = "$s[pr]days2";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  d tinyint(4) NOT NULL default '0',
  m tinyint(4) NOT NULL default '0',
  y smallint(6) NOT NULL default '0',
  i_m int(11) NOT NULL default '0',
  i_w int(11) NOT NULL default '0',
  cl_m mediumint(9) NOT NULL default '0',
  cl_w mediumint(9) NOT NULL default '0',
  INDEX number (number),
  INDEX d (d),
  INDEX m (m),
  INDEX y (y)
)";

$t[] = "$s[pr]days3";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  d tinyint(4) NOT NULL default '0',
  m tinyint(4) NOT NULL default '0',
  y smallint(6) NOT NULL default '0',
  i_m int(11) NOT NULL default '0',
  i_w int(11) NOT NULL default '0',
  cl_m mediumint(9) NOT NULL default '0',
  cl_w mediumint(9) NOT NULL default '0',
  INDEX number (number),
  INDEX d (d),
  INDEX m (m),
  INDEX y (y)
)";

$t[] = "$s[pr]def_ads";
$q[] = "(
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
  UNIQUE KEY number (number)
)";

$t[] = "$s[pr]ip";
$q[] = "(
  number int(11) NOT NULL default '0',
  ip char(16) NOT NULL default '',
  hits int(11) NOT NULL default '0'
)";

$t[] = "$s[pr]link1";
$q[] = "(
  number int(10) unsigned NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  cat smallint(6) default '0',
  url1 varchar(255) default NULL,
  banner1 varchar(255) default NULL,
  alt1 varchar(255) default NULL,
  raw1 text,
  ad_kind_1 varchar(15) NOT NULL default '',
  url2 varchar(255) default NULL,
  banner2 varchar(255) default NULL,
  alt2 varchar(255) default NULL,
  raw2 text,
  ad_kind_2 varchar(15) NOT NULL default '',
  url3 varchar(255) default NULL,
  banner3 varchar(255) default NULL,
  alt3 varchar(255) default NULL,
  raw3 text,
  ad_kind_3 varchar(15) NOT NULL default '',
  sponsor tinyint(1) NOT NULL default '0',
  def_number int(10) unsigned NOT NULL default '0'
)";

$t[] = "$s[pr]link2";
$q[] = "(
  number int(10) unsigned NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  cat smallint(6) default '0',
  url1 varchar(255) default NULL,
  banner1 varchar(255) default NULL,
  alt1 varchar(255) default NULL,
  raw1 text,
  ad_kind_1 varchar(15) NOT NULL default '',
  url2 varchar(255) default NULL,
  banner2 varchar(255) default NULL,
  alt2 varchar(255) default NULL,
  raw2 text,
  ad_kind_2 varchar(15) NOT NULL default '',
  url3 varchar(255) default NULL,
  banner3 varchar(255) default NULL,
  alt3 varchar(255) default NULL,
  raw3 text,
  ad_kind_3 varchar(15) NOT NULL default '',
  sponsor tinyint(1) NOT NULL default '0',
  def_number int(10) unsigned NOT NULL default '0'
)";

$t[] = "$s[pr]link3";
$q[] = "(
  number int(10) unsigned NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  cat smallint(6) default '0',
  url1 varchar(255) default NULL,
  banner1 varchar(255) default NULL,
  alt1 varchar(255) default NULL,
  raw1 text,
  ad_kind_1 varchar(15) NOT NULL default '',
  url2 varchar(255) default NULL,
  banner2 varchar(255) default NULL,
  alt2 varchar(255) default NULL,
  raw2 text,
  ad_kind_2 varchar(15) NOT NULL default '',
  url3 varchar(255) default NULL,
  banner3 varchar(255) default NULL,
  alt3 varchar(255) default NULL,
  raw3 text,
  ad_kind_3 varchar(15) NOT NULL default '',
  sponsor tinyint(1) NOT NULL default '0',
  def_number int(10) unsigned NOT NULL default '0'
)";

$t[] = "$s[pr]members";
$q[] = "(
  userid varchar(15) NOT NULL default '',
  userpass varchar(15) NOT NULL default '',
  email varchar(150) NOT NULL default '',
  siteurl varchar(150) default NULL,
  name varchar(100) default NULL,
  address varchar(255) default NULL,
  affiliate varchar(20) default NULL,
  date int(11) NOT NULL default '0',
  number int(11) NOT NULL auto_increment,
  accepted tinyint(1) NOT NULL default '0',
  sponsor tinyint(1) NOT NULL default '0',
  s_orders smallint(6) NOT NULL default '0',
  s_paid_ord smallint(6) NOT NULL default '0',
  s_last int(11) NOT NULL default '0',
  s_funds double(12,2) NOT NULL default '0.00',
  UNIQUE KEY number (number)
)";

$t[] = "$s[pr]moderators";
$q[] = "(
  number mediumint(9) NOT NULL auto_increment,
  username varchar(15) NOT NULL default '',
  password varchar(50) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  name varchar(50) default NULL,
  lastaccesss int(11) NOT NULL default '0',
  users tinyint(1) NOT NULL default '0',
  accounts tinyint(1) NOT NULL default '0',
  sponsors tinyint(1) NOT NULL default '0',
  s_accounts tinyint(1) NOT NULL default '0',
  backup tinyint(1) NOT NULL default '0',
  blacklist tinyint(1) NOT NULL default '0',
  email_u tinyint(1) NOT NULL default '0',
  reset tinyint(1) NOT NULL default '0',
  tmpl_msg tinyint(1) NOT NULL default '0',
  admins tinyint(1) NOT NULL default '0',
  config tinyint(1) NOT NULL default '0',
  x1 tinyint(1) NOT NULL default '0',
  x2 tinyint(1) NOT NULL default '0',
  x3 tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (number)
)";

$t[] = "$s[pr]months1";
$q[] = "(
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
  sponsor tinyint(1) NOT NULL default '0'
)";

$t[] = "$s[pr]months2";
$q[] = "(
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
  sponsor tinyint(1) NOT NULL default '0'
)";

$t[] = "$s[pr]months3";
$q[] = "(
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
  sponsor tinyint(1) NOT NULL default '0'
)";

$t[] = "$s[pr]ratios";
$q[] = "(
  size tinyint(1) NOT NULL default '0',
  min decimal(5,2) unsigned NOT NULL default '0.00',
  max decimal(5,2) unsigned NOT NULL default '0.00',
  ratio decimal(4,2) unsigned NOT NULL default '0.00'
)";

$t[] = "$s[pr]s_orders";
$q[] = "(
  number int(10) unsigned NOT NULL auto_increment,
  user int(11) NOT NULL default '0',
  pack mediumint(8) unsigned NOT NULL default '0',
  packname varchar(100) NOT NULL default '',
  price mediumint(8) unsigned NOT NULL default '0',
  bonus double(6,2) unsigned NOT NULL default '0.00',
  value double(9,2) unsigned NOT NULL default '0.00',
  paylink text,
  control varchar(255) default NULL,
  order_time int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (number)
)";

$t[] = "$s[pr]s_packs";
$q[] = "(
  number mediumint(9) NOT NULL auto_increment,
  descr varchar(150) NOT NULL default '',
  price mediumint(8) unsigned NOT NULL default '0',
  bonus double(6,2) unsigned NOT NULL default '0.00',
  value double(9,2) unsigned NOT NULL default '0.00',
  payhtml text NOT NULL,
  PRIMARY KEY  (number)
)";

$t[] = "$s[pr]stats1";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  accept tinyint(1) NOT NULL default '0',
  approved tinyint(1) NOT NULL default '0',
  enable tinyint(1) NOT NULL default '0',
  category smallint(6) NOT NULL default '0',
  c0 tinyint(1) NOT NULL default '0',
  c1 smallint(6) NOT NULL default '0',
  c2 smallint(6) NOT NULL default '0',
  c3 smallint(6) NOT NULL default '0',
  c4 smallint(6) NOT NULL default '0',
  c5 smallint(6) NOT NULL default '0',
  exratio decimal(4,2) NOT NULL default '0.00',
  no_slide tinyint(1) NOT NULL default '0',
  forclick tinyint(4) NOT NULL default '0',
  i_free int(10) unsigned NOT NULL default '0',
  c_free int(10) unsigned NOT NULL default '0',
  i_refer mediumint(8) unsigned NOT NULL default '0',
  i_w int(10) unsigned NOT NULL default '0',
  c_w int(10) unsigned NOT NULL default '0',
  i_m int(10) unsigned NOT NULL default '0',
  earned decimal(10,2) NOT NULL default '0.00',
  forclicks int(11) NOT NULL default '0',
  i_nu decimal(10,2) unsigned NOT NULL default '0.00',
  c_nu int(10) unsigned NOT NULL default '0',
  c_m int(10) unsigned NOT NULL default '0',
  i_move_in decimal(10,2) unsigned NOT NULL default '0.00',
  i_move_out int(10) unsigned NOT NULL default '0',
  last int(10) unsigned NOT NULL default '0',
  joined int(11) NOT NULL default '0',
  linka1 text,
  linkb1 text,
  linka2 text,
  linkb2 text,
  linka3 text,
  linkb3 text,
  weight tinyint(1) NOT NULL default '0',
  sponsor tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (number),
  INDEX accept (accept),
  INDEX approved (approved),
  INDEX enable (enable),
  INDEX category (category),
  INDEX c0 (c0),
  INDEX c1 (c1),
  INDEX c2 (c2),
  INDEX c3 (c3),
  INDEX c4 (c4),
  INDEX c5 (c5),
  INDEX i_free (i_free),
  INDEX c_free (c_free)
)";

$t[] = "$s[pr]stats2";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  accept tinyint(1) NOT NULL default '0',
  approved tinyint(1) NOT NULL default '0',
  enable tinyint(1) NOT NULL default '0',
  category smallint(6) NOT NULL default '0',
  c0 tinyint(1) NOT NULL default '0',
  c1 smallint(6) NOT NULL default '0',
  c2 smallint(6) NOT NULL default '0',
  c3 smallint(6) NOT NULL default '0',
  c4 smallint(6) NOT NULL default '0',
  c5 smallint(6) NOT NULL default '0',
  exratio decimal(4,2) NOT NULL default '0.00',
  no_slide tinyint(1) NOT NULL default '0',
  forclick tinyint(4) NOT NULL default '0',
  i_free int(10) unsigned NOT NULL default '0',
  c_free int(10) unsigned NOT NULL default '0',
  i_refer mediumint(8) unsigned NOT NULL default '0',
  i_w int(10) unsigned NOT NULL default '0',
  c_w int(10) unsigned NOT NULL default '0',
  i_m int(10) unsigned NOT NULL default '0',
  earned decimal(10,2) NOT NULL default '0.00',
  forclicks int(11) NOT NULL default '0',
  i_nu decimal(10,2) unsigned NOT NULL default '0.00',
  c_nu int(10) unsigned NOT NULL default '0',
  c_m int(10) unsigned NOT NULL default '0',
  i_move_in decimal(10,2) unsigned NOT NULL default '0.00',
  i_move_out int(10) unsigned NOT NULL default '0',
  last int(10) unsigned NOT NULL default '0',
  joined int(11) NOT NULL default '0',
  linka1 text,
  linkb1 text,
  linka2 text,
  linkb2 text,
  linka3 text,
  linkb3 text,
  weight tinyint(1) NOT NULL default '0',
  sponsor tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (number),
  INDEX accept (accept),
  INDEX approved (approved),
  INDEX enable (enable),
  INDEX category (category),
  INDEX c0 (c0),
  INDEX c1 (c1),
  INDEX c2 (c2),
  INDEX c3 (c3),
  INDEX c4 (c4),
  INDEX c5 (c5),
  INDEX i_free (i_free),
  INDEX c_free (c_free)
)";

$t[] = "$s[pr]stats3";
$q[] = "(
  number int(11) NOT NULL default '0',
  userid varchar(15) NOT NULL default '',
  accept tinyint(1) NOT NULL default '0',
  approved tinyint(1) NOT NULL default '0',
  enable tinyint(1) NOT NULL default '0',
  category smallint(6) NOT NULL default '0',
  c0 tinyint(1) NOT NULL default '0',
  c1 smallint(6) NOT NULL default '0',
  c2 smallint(6) NOT NULL default '0',
  c3 smallint(6) NOT NULL default '0',
  c4 smallint(6) NOT NULL default '0',
  c5 smallint(6) NOT NULL default '0',
  exratio decimal(4,2) NOT NULL default '0.00',
  no_slide tinyint(1) NOT NULL default '0',
  forclick tinyint(4) NOT NULL default '0',
  i_free int(10) unsigned NOT NULL default '0',
  c_free int(10) unsigned NOT NULL default '0',
  i_refer mediumint(8) unsigned NOT NULL default '0',
  i_w int(10) unsigned NOT NULL default '0',
  c_w int(10) unsigned NOT NULL default '0',
  i_m int(10) unsigned NOT NULL default '0',
  earned decimal(10,2) NOT NULL default '0.00',
  forclicks int(11) NOT NULL default '0',
  i_nu decimal(10,2) unsigned NOT NULL default '0.00',
  c_nu int(10) unsigned NOT NULL default '0',
  c_m int(10) unsigned NOT NULL default '0',
  i_move_in decimal(10,2) unsigned NOT NULL default '0.00',
  i_move_out int(10) unsigned NOT NULL default '0',
  last int(10) unsigned NOT NULL default '0',
  joined int(11) NOT NULL default '0',
  linka1 text,
  linkb1 text,
  linka2 text,
  linkb2 text,
  linka3 text,
  linkb3 text,
  weight tinyint(1) NOT NULL default '0',
  sponsor tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (number),
  INDEX accept (accept),
  INDEX approved (approved),
  INDEX enable (enable),
  INDEX category (category),
  INDEX c0 (c0),
  INDEX c1 (c1),
  INDEX c2 (c2),
  INDEX c3 (c3),
  INDEX c4 (c4),
  INDEX c5 (c5),
  INDEX i_free (i_free),
  INDEX c_free (c_free)
)";

$t[] = "$s[pr]wait_imp";
$q[] = "(
  user int(11) NOT NULL default '0',
  size tinyint(1) NOT NULL default '0',
  rest int(11) unsigned NOT NULL default '0',
  daily int(11) unsigned NOT NULL default '0'
)";

###########################################################################

for ($x=0;$x<=25;$x++)  // 0 az 25 = 26 tabulek (nemuze byt od 1 az)
{ if (mysql_query("DESCRIBE $t[$x]")) $uzbylo++;
  elseif ($q[$x])
  { $infnum = '';
    $r = mysql_query("CREATE TABLE $t[$x] $q[$x]");
    if (!$r) { $infnum = mysql_errno(); chyba(mysql_error(),0); $chyby++; }
    else hlaseni("Table $t[$x] created.\n");
  }
}

if (!$chyby)
{ if ($uzbylo)
  { if ($uzbylo<26)
    hlaseni('<b>Setup created some tables, some tables have been created in the past.</b>');
    elseif ($uzbylo==26)
    hlaseni ('<b>Setup did not created any tables, all necessary tables have been created in the past.</b>');
  }
  else hlaseni ('<b>Setup created all necessary tables.</b>');
}
else chyba ('<b>One or more errors found. Cannot continue.<br>Please make sure your database exists or ask yor server admin for help.</b>',1);

#####################################################################################

$q = mysql_query("select count(*) from $s[pr]moderators where username = 'admin1'");
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet)
{ $password = md5("admin1");
  $q = mysql_query ("INSERT INTO $s[pr]moderators VALUES (1, 'admin1', '$password', 'none@set.yet', '',0,1,1,1,1,1,1,1,1,1,1,1,0,0,0)");
  if (!$q) 
  { $x = mysql_error();
    chyba ("<b>Cannot insert administrator into the table $s[pr]moderators.<br>Mysql returned this error: $x.<br>Cannot continue.</b>",1);
  }
  else hlaseni ('<b>User "admin1" has been created.</b>');
}
what_now();

#####################################################################################
#####################################################################################
#####################################################################################

function what_now() {
?>
<br><table width=750 cellpadding=15 cellspacing=0 class="table1"><tr><td align="center">
<span class="text13blue">Easy Banner Pro has been successfully installed.<br>If all will work fine, delete setup.php from your server.</span>
<br><br><span class="text13">Now continue to your <a target="_blank" href="admin/index.php">admin directory</a>. Use username "admin1" and password "admin1".<br>
Inside the admin area click on link "Configuration" and fill in all required variables.</span><br>
</td></tr></table><br><br>
<?PHP
exit;
}

#####################################################################################
#####################################################################################
#####################################################################################

function form($pathtofolder) {
$pathtofolder = ereg_replace ("[\]", '/',$pathtofolder);
$pathtofolder = ereg_replace ('//', '/',$pathtofolder);
$pathtofolder = ereg_replace ('/setup.php','',$pathtofolder);
include('./admin/_head.txt');
echo '<LINK href="./styles.css" rel=StyleSheet>';
?>
<form method="POST" action="setup.php">
<table border="0" width="700" cellspacing="2" cellpadding="4" class="table1">
<tr>
<td colspan=2 align="center"><span class="text13blue"><b>Easy Banner Pro - Installation</b></span><br><span class="text13">Set up these variables<br>If you don't have a mysql database, ask your server admin to create one for you</span></td>
</tr>
<tr>
<td align="left"><span class="text13">Mysql database host</span><br><span class="text10">Try "localhost" if you are not sure</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbhost"></td>
</tr>
<tr>
<td align="left"><span class="text13">Mysql database username</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbusername"></td>
</tr>
<tr>
<td align="left"><span class="text13">Mysql database password</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbpassword"></td>
</tr>
<tr>
<td align="left"><span class="text13">Name of your mysql database</span></td>
<td align="left"><INPUT class="field1" maxLength=30 size=30 name="dbname"></td>
</tr>
<tr>
<td align="left"><span class="text13">Password is not needed</span><br><span class="text10">It should be unchecked on 99% servers!</span></td>
<td align="left"><input type="checkbox" name="nodbpass"></td></tr>
<tr>
<td align="left"><span class="text13">Prefix of all tables which Easy Banner Pro will create in the database. English letters only.<br><span class="text10">It is useful if you need install it more than one times and have only one database. Do not change it if you are not sure.</span></span></td>
<td align="left"><INPUT class="field1" maxLength=3 size=3 name="pr" value="eb_"></td>
</tr>
<tr>
<td align="left"><span class="text13">Full path to the folder where the scripts live. If you see any value in this field, it should be correct value on Unix or Linux servers so please don't change it if you are not 100% sure it is incorrect. On Windows you will need to replace all backlashes (\) with normal slashes (/). No trailing slash at all.</span></td>
<td align="left" nowrap><INPUT class="field1" maxLength=100 size=50 name="phppath" value="<?PHP echo $pathtofolder; ?>"><br><span class="text10">Sample for Linux:<br>/htdocs/sites/user/html/easybannerpro<br>Sample for Windows:<br>C:/somefolder/domain.com/easybannerfolder</span></td>
</tr>
<tr>
<td align="middle" width="100%" colSpan=2><INPUT type=submit value="Install" name=D1 class="button1"></TD>
</TR></TBODY></TABLE></FORM>
</center><br>
<?PHP
exit();
}

#####################################################################################
#####################################################################################
#####################################################################################

function chyba($text,$fatal) {
echo "<span class=\"text13blue\"><b>$text</b></span><br>";
if ($fatal) { echo "<span class=\"text13blue\"><b><br>Can't continue!</b></span><br>"; exit(); }
}

#####################################################################################

function hlaseni($text) {
echo '<span class="text13">'.$text.'</span><br>';
}

#####################################################################################
#####################################################################################
#####################################################################################

function create_write_file($file,$content,$owerwrite,$chmod,$fatal) {
if ( (!$owerwrite) AND (file_exists($file)) )
{ hlaseni ("File '$file' already exists. Skipping."); return 0; }
if (!$sb = @fopen($file,'w'))
{ chyba ("Unable to create file '$file'. Make sure this directory exists and it has 777 permission.",$fatal);
  return 0; }
$zapis = fwrite ($sb,$content); fclose($sb);
if (!$zapis)
{ chyba ("Cannot write to file '$file'. Make sure this directory exists and it has 777 permission.",$fatal);
  return 0; }
hlaseni ("Created file '$file'.");
if ($chmod) @chmod($file,$chmod);
}

#####################################################################################
#####################################################################################
#####################################################################################

function db_connect() {
global $s;
unset($s[db_error],$s[dben]);
if ($s[nodbpass]) $link_id = mysql_connect($s[dbhost], $s[dbusername]);
else $link_id = mysql_connect($s[dbhost],$s[dbusername],$s[dbpassword]);
if(!$link_id)
{ $s[db_error] = "Unable to connect to the host $s[dbhost]. Check database host, username, password."; $s[dben] = mysql_errno(); return 0; }
if ( (!$s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
if ( ($s[dbname]) && (!mysql_select_db($s[dbname])) )
{ $s[db_error] = mysql_errno().' '.mysql_error(); $s[dben] = mysql_errno(); return 0; }
return $link_id;
}

#####################################################################################
#####################################################################################
#####################################################################################


?>
