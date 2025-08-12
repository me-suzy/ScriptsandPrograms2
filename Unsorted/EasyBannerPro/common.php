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
include_once('./data/data.php');
$s[cas] = time() + $s[timeplus];
include_once("$s[phppath]/data/messages.php");
$linkid = db_connect(); if (!$linkid) problem($s[db_error]);
if (ini_get("magic_quotes_sybase")) ini_set("magic_quotes_sybase",0);
if (!get_magic_quotes_gpc()) ini_set("magic_quotes_gpc",1);
if (!get_magic_quotes_runtime()) set_magic_quotes_runtime(1);
if ($s[nocron]) { include_once('./rebuild_f.php'); check_if_job(); }

###############################################################################
###############################################################################
###############################################################################

function replace_once_html($x) {
// vhodne na html pred vlozenim do databaze, po vytazeni se ale musi vratit ' a \
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$x)));
//$x = stripslashes($x);
return ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',ereg_replace("'",'&#039;',$x)));
}

###############################################################################

function unreplace_once_html($x) {
// na html (kompletni ad a raw) po vytazeni z databaze
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]",'',$x));
return ereg_replace('&#92;','\\\\',ereg_replace('&#039;',"'",$x));
}

###############################################################################

function replace_once_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$x)));
//$x = stripslashes($x);
return ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',htmlspecialchars($x,ENT_QUOTES)));
}

###############################################################################

function replace_array_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
foreach ($x as $k => $v)
{ if (is_array($v)) continue;
  $v = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$v)));
  //$v = stripslashes($v);
  $x[$k] = ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',htmlspecialchars($v,ENT_QUOTES)));
}
return $x;
}

###############################################################################

function sponsors_in_array() {
global $s;
// vrati seznam cisel sponsoru v poli - potrebuju to na vlozeni do tabulky months...
$q = dq("select number from $s[pr]members where sponsor = '1'",1);
while ($x=mysql_fetch_row($q)) $sponsors[] = $x[0];
if (!$sponsors) $sponsors = array('');
return $sponsors;
}

###############################################################################
###############################################################################
###############################################################################

function day_number($x) {
// vraci cislo aktualniho dne, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('j',$x);
}

###############################################################################

function month_number($x) {
// vraci cislo aktualniho mesice, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('n',$x);
}

###############################################################################

function year_number($x) {
// vraci cislo aktualniho roku, musi se mu poslat $s[cas] ( tj. time()+$s[timeplus] )
global $s;
if (!$x) $x = $s[cas];
return date('Y',$x);
}

###############################################################################
###############################################################################
###############################################################################

function problem($error) {
global $s;
include_once('./functions.php');
$pole[errortext] = $error;
page_from_template('error.html',$pole);
exit;
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

function user_delete($data) {
global $s;
$q = dq("select userid,email from $s[pr]members where number = '$data[number]'",1);
$userid = mysql_fetch_assoc($q);
//if ($data[send_email]) mail_from_template($data[send_email],$userid);
// dalsich 6 radku jen pro banner
$direc = opendir("$s[phppath]/userbanners");
while ($file = readdir($direc))
{ if (eregi("^$userid[userid]\-.*", $file)) unlink ("$s[phppath]/userbanners/$file");
  if (eregi("^[0-9]+\-$userid[userid]\-.*", $file)) unlink ("$s[phppath]/userbanners/$file");
}
closedir($direc);
dq("delete from $s[pr]members where number = '$data[number]'",1);
for ($x=1;$x<=3;$x++)
{ dq("delete from $s[pr]link$x where number = '$data[number]'",1);
  dq("delete from $s[pr]stats$x where number = '$data[number]'",1);
  dq("delete from $s[pr]b$x where number = '$data[number]'",1);
  dq("delete from $s[pr]days$x where number = '$data[number]'",1);
  dq("delete from $s[pr]months$x where number = '$data[number]'",1);
}
dq("delete from $s[pr]wait_imp where user = '$data[number]'",1);
dq("delete from $s[pr]day where number = '$data[number]'",1);
dq("delete from $s[pr]ip where number = '$data[number]'",1);
dq("delete from $s[pr]s_orders where user = '$data[number]'",1);
if (!$s[no_stop])
{ include('./_head.txt'); echo iot('Selected user has been deleted'); include('./_footer.txt'); exit; }
}

###############################################################################
###############################################################################
###############################################################################

?>