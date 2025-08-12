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
if (ini_get("magic_quotes_sybase")) ini_set("magic_quotes_sybase",0);
if (!get_magic_quotes_gpc()) ini_set("magic_quotes_gpc",1);
if (!get_magic_quotes_runtime()) set_magic_quotes_runtime(1);
session_start();
include('../data/data.php');
include('../data/time.php');
include_once('../rebuild_f.php');
$linkid = db_connect(); if (!$linkid) problem($s[db_error]);
$s[cas] = time()+$s[timeplus];
if (!$s[no_mini_job]) check_mini_job($s[cas]);
if ($s[nocron]) check_if_job();

$s[info_limit] = '<span class="text13">* If you want to see a limited number of members on the same page (it\'s useful for bigger exchanges with 100\'s members) you will need to select one exchange size only (1, 2 or 3). If you want to see all exchange sizes on the same page, you cannot use this feature.<br></span>';
$s[month] = month_number(0); $s[year] = year_number(0);

##################################################################################
##################################################################################
##################################################################################

function iot($info) {
return '<span class="text13blue"><b>'.$info.'</b></span><br><br>';
}

##################################################################################

function eot($line1,$line2) {
$a = '<span class="text13blue"><b>'.$line1.'</b></span>';
if ($line2) $a .= '<br><span class="text13">'.$line2.'</span>';
$a .= '<br><br>';
return $a;
}

##################################################################################
##################################################################################
##################################################################################

function replace_once_html($x) {
// vhodne na html pred vlozenim do databaze, po vytazeni se ale musi vratit ' a \
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$x)));
return ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',ereg_replace("'",'&#039;',$x)));
}

##################################################################################

function unreplace_once_html($x) {
// na html (kompletni ad a raw) po vytazeni z databaze
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]",'',$x));
return ereg_replace('&#92;','\\',ereg_replace('&#039;',"'",$x));
}

##################################################################################

function replace_once_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
$x = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$x)));
return ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',htmlspecialchars($x,ENT_QUOTES)));
}

#######################################################################

function replace_array_text($x) {
// premeni < > ' " \
// vhodne na jakykoliv text pred vlozenim do databaze, ne na html
if (!$x) return $x;
foreach ($x as $k => $v)
{ if (is_array($v)) continue;
  $v = ereg_replace("''","'",ereg_replace("[\]'","'",ereg_replace('[\]"','"',$v)));
  $x[$k] = ereg_replace('&amp;','&',ereg_replace("[\]",'&#92;',htmlspecialchars($v,ENT_QUOTES)));
}
return $x;
}

##################################################################################
##################################################################################
##################################################################################

function mail_from_template($template,$value) {
global $s;
$template = $s[phppath].'/data/templates/'.$template;
if (!is_array($value)) $value = array();
$fd = fopen($template,'r') or problem("Cannot read template $template");
while ($line = fgets($fd,4096)) $emailtext .= $line;
fclose($fd);
eregi("Subject: +([^\n\r]+)",$emailtext,$regs); $sub = $regs[1];
$emailtext = eregi_replace("Subject: +([^\n\r]+)[\r\n]+",'',$emailtext);
while (list($k,$v) = each($value)) $emailtext = str_replace("#%$k%#",$v,$emailtext);
reset ($value);
$emailtext = eregi_replace("#%[a-z0-9_]*%#",'',$emailtext);
$emailtext = unhtmlentities($emailtext); $sub = unhtmlentities($sub);
$emailtext = stripslashes($emailtext);
//echo "To: $value[email]<br>From: $s[adminemail];<br>Sub: $sub<br>$emailtext<br><br><br>";
mail($value[email], $sub, $emailtext, "From: $s[adminemail];");
}

##################################################################################

function unhtmlentities($string) {
$string = eregi_replace('&#039;',"'",$string);
$trans_tbl = get_html_translation_table(HTML_ENTITIES);
$trans_tbl = array_flip($trans_tbl);
return strtr($string,$trans_tbl);
}

##################################################################################
##################################################################################
##################################################################################

function get_complete_html($size,$number,$b,$banner,$url,$alt,$raw,$ad_kind) {
global $s;
$data = array('size'=>$size,'number'=>$number,'b'=>$b,'banner'=>$banner,'url'=>$url,'alt'=>$alt,'ad_kind'=>$ad_kind);
$data[w] = $s["w$size"]; $data[h] = $s["h$size"];
if ($ad_kind=='picture')
{ $link_url = "&size=$data[size]&to=$data[number]&b=$data[b]&url=".urlencode(ereg_replace('&amp;','&',$data[url]));
  if (eregi(".*swf$",$data[banner]))
  { $data[click_tag] = $s[phpurl].urlencode('/link.php?from=').'<_>'.urlencode($link_url).'&window='.$s[target];
    $html1 = parse_part('flash.txt',$data);
  }
  elseif (($data[banner]) AND ($data[url]))
  { $html1 = "<a target=\"$s[target]\" href=\"$s[phpurl]/link.php?from=";
    $html2 = $link_url."\"><img alt=\"$data[alt]\" border=0 src=\"$data[banner]\" width=\"$data[w]\" height=\"$data[h]\"></a>";
  }
}
elseif ($ad_kind=='raw_html')
{ preg_match_all("(http://[a-z0-9./_|+=%?&\-]+)",$raw,$x);
  foreach ($x[0] as $k => $v)
  $raw = str_replace($v,"$s[phpurl]/link.php?from=<_>&size=$data[size]&to=$data[number]&b=$data[b]&url=".urlencode(ereg_replace('&amp;','&',$v)),$raw);
  $html1 = $raw; $html2 = '';
}
if ($html1) $ok = 1;
$html1 = stripslashes($html1); $html2 = stripslashes($html2);
return array($html1,$html2,$ok);
}

###############################################################################

function parse_part($template,$value) {
global $s,$m;
$template = $s[phppath].'/data/templates/'.$template;
if (!is_array($value)) $value = array();
$value[adminemail] = $s[adminemail];
$fh = fopen($template,'r') or problem("$m[errorreadtmpl] $template");
while (!feof($fh)) $line .= fgets($fh,4096);
fclose ($fh);
while( list ($key, $val) = each ($value)) $line = str_replace("#%$key%#",$val,$line);
reset ($value);
$line = eregi_replace("#%[a-z0-9_]*%#",'',$line);
$line = StripSlashes($line);
return $line;
}

##################################################################################
##################################################################################
##################################################################################

function show_current_complete_ad($user,$size,$banner) {
global $s,$m;
$q = dq("select linka$banner,linkb$banner from $s[pr]stats$size where number = '$user'",1);
$a = mysql_fetch_row($q);
if ($a[0]) return unreplace_once_html(str_replace('link.php?','link.php?nc=1&',$a[0]).$a[1]);
else return 'No ad set';
}

##################################################################################
##################################################################################
##################################################################################

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

##################################################################################

function dq($query,$check) {
global $s;
$q = mysql_query($query);
if (($check) AND (!$q)) problem(mysql_error());
return $q;
}

##################################################################################
##################################################################################
##################################################################################

function check_session($action) {
global $s,$HTTP_SESSION_VARS;
$data = $HTTP_SESSION_VARS;
$q = dq("select count(*) from $s[pr]moderators where username='$data[admuser]' and number = '$data[number]' and $action = 1",1);
$data = mysql_fetch_row($q);
if (!$data[0]) problem('You don\'t have permission for this action.');
}

##################################################################################
##################################################################################
##################################################################################

function problem($error) {
include('./_head.txt');
echo iot('<font color="FF0000" size=3>ERROR</font><br><br>'.$error);
include('./_footer.txt'); exit; }
function d88fc6edf21ea464d35ff76288b84103($a) {
$s = 1; // tady se musi menit cislo skriptu
$r = file("http://www.phpwebscripts.com/scripts/check.php?s_=$s&u=$a[p_user]&p=$a[p_pass]&d=$a[p_domain]&url=$a[phpurl]");
if ($r[0]) return $r[0];
return false;
}

##################################################################################
##################################################################################
##################################################################################

function sponsors_in_array() {
global $s;
// vrati seznam cisel sponsoru v poli - potrebuju to na vlozeni do tabulky months...
$q = dq("select number from $s[pr]members where sponsor = '1'",0);
while ($x=mysql_fetch_row($q)) $sponsors[] = $x[0];
return $sponsors;
}

##################################################################################
##################################################################################
##################################################################################

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

##################################################################################
##################################################################################
##################################################################################

function datum($plustime,$cas) {
global $s;
if (!$cas) $cas=$s[cas];// else $cas = $cas + $s[timeplus];
if ($s[ustime]) { if ($plustime) $x = date ("Y-m-d, g:i a",$cas);
                         else $x = date ("Y-m-d",$cas); }
else { if ($plustime) $x = date ("j/n/Y, G:i",$cas);
       else $x = date ("j/n/Y",$cas); }
return $x;
}

##################################################################################
##################################################################################
##################################################################################

function user_delete($data) {
global $s;
$q = dq("select userid,email from $s[pr]members where number = '$data[number]'",1);
$userid = mysql_fetch_assoc($q);
if ($data[send_email]) mail_from_template($data[send_email],$userid);
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

##################################################################################
##################################################################################
##################################################################################

?>