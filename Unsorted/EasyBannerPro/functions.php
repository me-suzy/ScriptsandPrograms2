<?php

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


include_once('./common.php');
$s[adminfile] = $s[phpurl].'/admin/index.php';

###############################################################################
###############################################################################
###############################################################################

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
###############################################################################
###############################################################################

function show_current_banner($banner,$size) {
global $s,$m;
$s[banner_exists] = 1;
$data[w] = $w = $s["w$size"]; $data[h] = $h = $s["h$size"];
if (eregi(".swf$",$banner))
{ $data[banner] = $banner; return parse_part('flash.txt',$data); }
if ($banner) return "<img width=\"$w\" height=\"$h\" src=\"$banner\">";
$s[banner_exists] = 0; return $m[i_noneyet];
}

###############################################################################

function show_current_complete_ad($size,$banner) {
global $s,$m;
$q = dq("select linka$banner,linkb$banner from $s[pr]stats$size where userid = '$s[userid]'",1);
$a = mysql_fetch_row($q);
if ($a[0]) return unreplace_once_html(str_replace('link.php?','link.php?nc=1&',$a[0]).$a[1]);
else return $m[i_noneyet];
}

###############################################################################
###############################################################################
###############################################################################

function page_from_template($template,$value) {
global $s,$m;
//echo $value[current_ad_1];exit;
$template = $s[phppath].'/data/templates/'.$template;
if (!is_array($value)) $value = array();
$value[adminemail] = $s[adminemail];
$fh = @fopen($template,'r') or problem("$m[errorreadtmpl] $template");
while(!feof($fh)) $line .= fgets($fh, 4096);
fclose($fh);
while( list ($key, $val) = each ($value)) $line = str_replace("#%$key%#",$val,$line);
reset ($value);
$line = eregi_replace("#%[a-z0-9_]*%#",'',$line);
$line = StripSlashes($line);
include('./data/templates/_head.txt');
echo $line;
include('./data/templates/_footer.txt');
//echo base64_decode('PCEtLSBUaGlzIHN5c3RlbSBwb3dlcmVkIGJ5IFRleHQgRXhjaGFuZ2UgUHJvIGZyb20gaHR0cDovL3d3dy5waHB3ZWJzY3JpcHRzLmNvbS8gLS0+');
exit;
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

###############################################################################

function mail_from_template($template,$value) {
global $s,$m;
$template = $s[phppath].'/data/templates/'.$template;
if (!is_array($value)) $value = array();
$fd = @fopen($template,'r') or problem("$m[errorreadtmpl] $template");
while ($line = fgets($fd,4096)) $emailtext .= $line;
fclose($fd);
eregi("Subject: +([^\n\r]+)",$emailtext,$regs); $sub = $regs[1];
$emailtext = eregi_replace("Subject: +([^\n\r]+)[\r\n]+",'',$emailtext);
while (list($k,$v) = each($value)) $emailtext = str_replace("#%$k%#",$v,$emailtext);
reset ($value);
$emailtext = eregi_replace("#%[a-z0-9_]*%#",'',$emailtext);
$emailtext = unhtmlentities($emailtext); $sub = unhtmlentities($sub);
$emailtext = stripslashes($emailtext);
if ( ($s[adminemail1]) AND ($value[email]==$s[adminemail]) )
{ mail($s[adminemail1],$sub,$emailtext,"From: $s[adminemail]");
  //echo "To: $s[adminemail1]<br>From: $s[adminemail]<br>Sub: $sub<br>$emailtext<br><br><br>";
}
//echo "To: $value[email]<br>From: $s[adminemail]<br>Sub: $sub<br>$emailtext<br><br><br>";
mail($value[email],$sub,$emailtext,"From: $s[adminemail]");
}

###############################################################################

function unhtmlentities($string) {
//pouzite v mail_from_template
$string = eregi_replace('&#039;',"'",$string);
$trans_tbl = get_html_translation_table(HTML_ENTITIES);
$trans_tbl = array_flip($trans_tbl);
return strtr($string,$trans_tbl);
}

###############################################################################
###############################################################################
###############################################################################

function datum ($plustime,$cas) {
global $s;
if (!$cas) $cas = $s[cas];
if ($s[ustime]) { if ($plustime) $x = date ("Y-m-d, g:i a",$cas); else $x = date ("Y-m-d",$cas); }
else { if ($plustime) $x = date ("j/n/Y, G:i",$cas); else $x = date ("j/n/Y",$cas); }
return $x;
}

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

###############################################################################

function check_blacklist($domain) {
global $s,$m;
$mojeurl = parse_url($domain); $domena = $mojeurl[host];
$mojepole = explode(".",$domena); $pocetprvku = count($mojepole);
$url = $mojepole[$pocetprvku-2].'.'.$mojepole[$pocetprvku-1];
$q = dq("select count(*) from $s[pr]blacklist where url like '$url'",1);
$data = mysql_fetch_row($q);
if ($data[0]) return "$m[blackdomain1] $url $m[blackdomain2]";
// kdyby bylo treba neco.co.uk
$url = $mojepole[$pocetprvku-3].'.'.$url;
$q = dq("select count(*) from $s[pr]blacklist where url like '$url'",1);
$data = mysql_fetch_row($q);
if ($data[0]) return "$m[blackdomain1] $url $m[blackdomain2]";
}

###############################################################################

function check_email($email) {
if (eregi("^[a-z0-9_.=+-]+@[a-z0-9.-]+\.[a-z]{2,6}$",$email)) return 1;
return 0;
}

###############################################################################

function check_url($url) {
if (eregi("^http://[a-z0-9./_|+=%?&\-]+$",$url)) return 1;
return 0;
}

###############################################################################
###############################################################################
###############################################################################

function check_login($in) {
global $s,$m;
// stejne pro normal + sponsor (musi byt nahore definovany $s[sponsor] 0 nebo 1
// stejne pro B a T
$q = dq("select userpass,userid,number from $s[pr]members where userid = '$in[userid]' AND sponsor = '$s[sponsor]'",1);
$data = mysql_fetch_assoc($q);
if (!$data[number])
{ $in[info] = iot($m[usernexists]);
  if ($s[sponsor]) page_from_template('s_login.html',$in);
  else page_from_template('u_login.html',$in);
}
if (trim($data[userpass]) != trim($in[userpass]))
{ $in[info] = iot($m[wrongpass]);
  if ($s[sponsor]) page_from_template('s_login.html',$in);
  else page_from_template('u_login.html',$in);
}
$s = array_merge($s,$data);
}

###############################################################################
###############################################################################
###############################################################################
















###############################################################################
###############################################################################
#########################          STATISTIC          #########################
###############################################################################
###############################################################################

function statistic_main($in) {
global $s,$m;
// stejne pro normal + sponsor az na nazev sablony
// stejne pro B a T
if (!$in[size]) $in[size] = eregi_replace('statistic_','',$in[action]);
$ad_stats = statistic_part_ad($in); $stats = statistic_part_current($in); $hours = statistic_part_hours($in);
$data = array_merge ($in,$ad_stats,$stats,$hours);
$data[height] = $s['h'.$in[size]]; $data[width] = $s['w'.$in[size]];
include('./data/time.php');
if ($data[last]) $data[lasttime] = datum(1,$data[last]);
else $data[lasttime] = $m[i_never];
$data[time] = datum(1,0);
if ($s[sponsor]) page_from_template('s_statistic.html',$data);
else page_from_template('u_statistic.html',$data);
}

########################################################################

function statistic_part_current($in) {
global $s;
// stejne pro normal + sponsor
// stejne pro B a T
$x = $in[size];
$q = dq("select size,rest,daily from $s[pr]wait_imp where user = '$s[number]'",1);
while ($r = mysql_fetch_row($q)) { $wait_imp[w_rest] = $r[1]; $wait_imp[w_daily] = $r[2]; }
if (!$wait_imp[w_rest]) $wait_imp[w_rest] = 0;
if (!$wait_imp[w_daily]) $wait_imp[w_daily] = 0;
$q = dq("select * from $s[pr]stats$x where number = '$s[number]'",1);
$data[stats] = mysql_fetch_array($q);
$data[stats][ratio] = $data[stats][exratio] * 100;
if ($data[stats][i_w]) $data[stats][r_w]=100*($data[stats][c_w]/$data[stats][i_w]); else $data[stats][r_w] = 0;
$data[stats][r_w] = round ($data[stats][r_w],2);
if ($data[stats][i_m]) $data[stats][r_m]=100*($data[stats][c_m]/$data[stats][i_m]); else $data[stats][r_m] = 0;
$data[stats][r_m] = round ($data[stats][r_m],2);
if ($data[stats][last]) $data[stats][time] = datum (1,$data[stats][last]);
$data[stats][total_imp] = $data[stats][i_nu] + $data[stats][i_w];
$data[stats][total_clicks] = $data[stats][c_nu] + $data[stats][c_w];
if ($s[sponsor]) $data[stats][i_nu] = round($data[stats][i_nu]);
reset ($data); while (list($key,$value)=each($data)) $$key=$value;
$data = array_merge($stats,$reset,$wait_imp);
return $data;
}

########################################################################

function statistic_part_hours($in) {
global $s,$m;
// stejne pro normal + sponsor (musi byt nahore definovany $s[sponsor] 0 nebo 1
// stejne pro B a T
$a[size] = $in[size];
$l[0] = '<td align="center" valign="bottom" nowrap>';
$l[1] = '<td align="center" nowrap class="tdw" width="18">';
$r = '</td>'."\n";
$q = dq("select * from $s[pr]day where userid = '$s[userid]' AND size = '$in[size]'",1);
$radek[1] = '<td align="left" class="tdh"><span class="text10">'.$m[hour].'</span></td>';
if (!$s[sponsor]) $radek[2] = '<td align="left" class="tdh"><span class="text10">'.$m[your_p].'</span></td>';
$radek[3] = '<td align="left" class="tdh"><span class="text10">'.$m[your_ad].'</span></td>';
$data = mysql_fetch_array($q);
for ($x=0;$x<=23;$x++)
{ if ($data["m$x"]) $mo[$x] = $data["m$x"]; else $mo[$x] = 0;
  if ($data["w$x"]) $w[$x] = $data["w$x"]; else $w[$x] = 0;
  $radek[1] .= '<td align="center" class="tdh"><span class="text10">'.$x.$r;
  if (!$s[sponsor]) $radek[2] .= $l[1].$mo[$x].$r;
  $radek[3] .= $l[1].$w[$x].$r;
  if ($big1<$w[$x]) $big1 = $w[$x]; if ($big1<$mo[$x]) $big1 = $mo[$x];
}
$a[table1] = '<table class="table_graph" cellpadding="1" cellspacing="0" border="0"><tr><td>&nbsp;</td>';
$pomer1 = $big1/100;
for ($x=0;$x<=23;$x++)
{ if ($pomer1) { $w[$x] = ceil($w[$x]/$pomer1); $mo[$x] = ceil($mo[$x]/$pomer1); }
  if (!$w[$x]) $w[$x] = 1; if (!$mo[$x]) $mo[$x] = 1;
  $a[table1] .= '<td align="center" valign="bottom" width="21">';
  if (!$s[sponsor]) $a[table1] .= '<img src="1.jpg" height="'.$mo[$x].'" width="10">';
  $a[table1] .= '<img src="2.jpg" height="'.$w[$x].'" width="10"></td>'."\n";
}
$a[table1] .= '</tr>';
$x = 0;
foreach ($radek as $k => $v) $a[table1] .= "<tr>$v</tr>";
$a[hourly_graph] = $a[table1].'</table>';
return $a;
}

########################################################################

function statistic_part_ad($in) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T az na zobrazeni reklamy (banner nebo iframe)
$x = $in[size];
$q = dq("select i1 as ad_i1,c1 as ad_c1,res1 as ad_res1,i2 as ad_i2,c2 as ad_c2,res2 as ad_res2,i3 as ad_i3,c3 as ad_c3,res3 as ad_res3 from $s[pr]b$x where userid = '$s[userid]'",1);
$data = mysql_fetch_array($q);
// zobrazeni ads
$q = dq("select linka1,linka2,linka3 from $s[pr]stats$x where userid = '$s[userid]'",1);
$ad = mysql_fetch_assoc($q);
for ($x=1;$x<=3;$x++) $data["ban$x"] = show_current_complete_ad($in[size],$x);
for ($x=1;$x<=3;$x++)
{ if ($data["ad_i$x"]) $data["ad_ratio$x"]=100*($data["ad_c$x"]/$data["ad_i$x"]); else $data["ad_ratio$x"] = 0;
  $data["ad_ratio$x"] = round ($data["ad_ratio$x"],2);
  if ($data["ad_res$x"]) $data["ad_res$x"] = datum (1,$data["ad_res$x"]); else $data["ad_res$x"] = $m[i_never];
}
return $data;
}

########################################################################

function reset_ad_statistic($in) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T
dq("update $s[pr]b$in[size] set i$in[ad] = 0, c$in[ad] = 0, res$in[ad] = '$s[cas]' where userid = '$s[userid]'",1);
statistic_main($in);
}

########################################################################
########################################################################
########################################################################

function statistic_day_by_day($in) { 
global $s,$m;
// stejne pro normal + sponsor (musi byt nahore definovany $s[sponsor] 0 nebo 1
// stejne pro B a T
$in[size] = eregi_replace('statistic_day_by_day_','',$in[action]);
today_to_days_one_user($in);
if (!$in[year]) $in[year] = date('Y',$s[cas]);
if (!$in[month]) $in[month] = date('n',$s[cas]);
$a[month] = $m['m'.$in[month]].' '.$in[year];
if ($in[month]==1) { $a[prev_m]=12; $a[prev_y]=$in[year]-1; }
else { $a[prev_m] = $in[month]-1; $a[prev_y]=$in[year]; }
if ($in[month]==12) { $a[next_m]=1; $a[next_y]=$in[year]+1; }
else { $a[next_m] = $in[month]+1; $a[next_y]=$in[year]; }
$kk = mktime(0,0,0,$in[month],15,$in[year]); $dni = date("t",$kk); 
$q = dq("select * from $s[pr]days$in[size] where userid = '$in[userid]' AND m = '$in[month]' AND y = '$in[year]'",1);
while ($data = mysql_fetch_array($q)) $a['day'.$data[d]] = $data;

if ($s[graph_vert])
{ $l[0] = '<td align="center" valign="bottom" nowrap>';
  $l[1] = '<td align="center" nowrap class="tdw">';
  $l[2] = '<td align="center" nowrap class="tdw">';
  $r = '</td>'."\n";
  for ($x=1;$x<=$dni;$x++)
  { if ($a["day$x"][i_m]) $i_m[$x] = $a["day$x"][i_m]; else $i_m[$x] = 0;
    if ($a["day$x"][i_w]) $i_w[$x] = $a["day$x"][i_w]; else $i_w[$x] = 0;
    if ($a["day$x"][cl_m]) $cl_m[$x] = $a["day$x"][cl_m]; else $cl_m[$x] = 0;
    if ($a["day$x"][cl_w]) $cl_w[$x] = $a["day$x"][cl_w]; else $cl_w[$x] = 0;
    $radek1[$x] = $radek2[$x] = '<tr><td align="center" class="tdh" nowrap><span class="text10">'.$x.'</span></td>';
    if (!$s[sponsor]) $radek1[$x] .= $l[1].$i_m[$x].'</span></td>';
    $radek1[$x] .= $l[1].$i_w[$x].'</span></td>';
    if (!$s[sponsor]) $radek2[$x] .= $l[1].$cl_m[$x].'</span></td>';
    $radek2[$x] .= $l[1].$cl_w[$x].'</span></td>';
    if ($big1<$i_w[$x]) $big1 = $i_w[$x]; if ($big1<$i_m[$x]) $big1 = $i_m[$x];
    if ($big2<$cl_w[$x]) $big2 = $cl_w[$x]; if ($big2<$cl_m[$x]) $big2 = $cl_m[$x];
  }
  $a[table1] = $a[table2] = '<table border="0" cellpadding="2" cellspacing="0">
  <tr><td align="center" class="tdh"><span class="text10">'.$m[day].'</span></td>';
  if (!$s[sponsor]) $a[table1] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[your_p].'</span></td>';
  if (!$s[sponsor]) $a[table2] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[your_p].'</span></td>';
  $a[table1] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[your_ad].'</span></td></tr>';
  $a[table2] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[your_ad].'</span></td></tr>';
  $pomer1 = $big1/100; $pomer2 = $big2/100;
  for ($x=1;$x<=$dni;$x++)
  { if ($pomer1) { $i_w[$x] = ceil($i_w[$x]/$pomer1); $i_m[$x] = ceil($i_m[$x]/$pomer1); }
    if ($pomer2) { $cl_w[$x] = ceil($cl_w[$x]/$pomer2); $cl_m[$x] = ceil($cl_m[$x]/$pomer2); }
    if (!$i_w[$x]) $i_w[$x] = 1; if (!$i_m[$x]) $i_m[$x] = 1;
    if (!$cl_w[$x]) $cl_w[$x] = 1; if (!$cl_m[$x]) $cl_m[$x] = 1;
    $a[table1] .= $radek1[$x].'<td align="left" valign="bottom">';
    if (!$s[sponsor]) $a[table1] .= '<img src="3.jpg" width="'.$i_m[$x].'" height="10"><br>';
    $a[table1] .= '<img src="4.jpg" width="'.$i_w[$x].'" height="10"></td></tr>'."\n";
    $a[table2] .= $radek2[$x].'<td align="left" valign="bottom">';
    if (!$s[sponsor]) $a[table2] .= '<img src="3.jpg" width="'.$cl_m[$x].'" height="10"><br>';
    $a[table2] .= '<img src="4.jpg" width="'.$cl_w[$x].'" height="10"></td></tr>'."\n";
  }
  $a[table1] .= '</table>'; $a[table2] .= '</table>';
}
else
{ $l[0] = '<td align="center" valign="bottom" nowrap>';
  $l[1] = '<td align="center" nowrap class="tdw" width="18">';
  $r = '</td>'."\n";
  $radek[1] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[day].'</span></td>';
  $radek[2] = $radek[4] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[your_p].'</span></td>';
  $radek[3] = $radek[5] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[your_ad].'</span></td>';
  for ($x=1;$x<=$dni;$x++)
  { if ($a["day$x"][i_m]) $i_m[$x] = $a["day$x"][i_m]; else $i_m[$x] = 0;
    if ($a["day$x"][i_w]) $i_w[$x] = $a["day$x"][i_w]; else $i_w[$x] = 0;
    if ($a["day$x"][cl_m]) $cl_m[$x] = $a["day$x"][cl_m]; else $cl_m[$x] = 0;
    if ($a["day$x"][cl_w]) $cl_w[$x] = $a["day$x"][cl_w]; else $cl_w[$x] = 0;
    $radek[1] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$x.'</span></td>';
    $radek[2] .= $l[1].$i_m[$x].$r;
    $radek[3] .= $l[1].$i_w[$x].$r;
    $radek[4] .= $l[1].$cl_m[$x].$r;
    $radek[5] .= $l[1].$cl_w[$x].$r;
    if ($big1<$i_w[$x]) $big1 = $i_w[$x]; if ($big1<$i_m[$x]) $big1 = $i_m[$x];
    if ($big2<$cl_w[$x]) $big2 = $cl_w[$x]; if ($big2<$cl_m[$x]) $big2 = $cl_m[$x];
  }
  $a[table1] = $a[table2] = '<table bordre="0" cellpadding="1" cellspacing="0"><tr><td>&nbsp;</td>';
  $pomer1 = $big1/100; $pomer2 = $big2/100;
  for ($x=1;$x<=$dni;$x++)
  { if ($pomer1) { $i_w[$x] = ceil($i_w[$x]/$pomer1); $i_m[$x] = ceil($i_m[$x]/$pomer1); }
    if ($pomer2) { $cl_w[$x] = ceil($cl_w[$x]/$pomer2); $cl_m[$x] = ceil($cl_m[$x]/$pomer2); }
    if (!$i_w[$x]) $i_w[$x] = 1; if (!$i_m[$x]) $i_m[$x] = 1;
    if (!$cl_w[$x]) $cl_w[$x] = 1; if (!$cl_m[$x]) $cl_m[$x] = 1;
    $a[table1] .= '<td align="center" valign="bottom" nowrap>';
    if (!$s[sponsor]) $a[table1] .= '<img src="1.jpg" height="'.$i_m[$x].'" width="10">';
    $a[table1] .= '<img src="2.jpg" height="'.$i_w[$x].'" width="10"></td>'."\n";
    $a[table2] .= '<td align="center" valign="bottom" nowrap>';
    if (!$s[sponsor]) $a[table2] .= '<img src="1.jpg" height="'.$cl_m[$x].'" width="10">';
    $a[table2] .= '<img src="2.jpg" height="'.$cl_w[$x].'" width="10"></td>'."\n";
  }
  $a[table1] .= '</tr>'; $a[table2] .= '</tr>';
  if ($s[sponsor]) { $radek[2] = $radek[3]; $radek[4] = $radek[5]; unset($radek[3],$radek[5]); }

  foreach ($radek as $k => $v)
  { if ($k==1) { $a[table1] .= "<tr>$v</tr>"; $a[table2] .= "<tr>$v</tr>"; }
    elseif ($k<=3) $a[table1] .= "<tr>$v</tr>";
    else $a[table2] .= "<tr>$v</tr>";
  }

  $a[table1] .= '</table>'; $a[table2] .= '</table>';
}
$a = array_merge($in,$a);
$a[height] = $s['h'.$in[size]]; $a[width] = $s['w'.$in[size]];
if ($s[sponsor]) page_from_template('s_statistic_day_by_day.html',$a);
else page_from_template('u_statistic_day_by_day.html',$a);
exit;
}

###############################################################################

function today_to_days_one_user($data) {
// vezme dnesni data a prida je do tabulky days - pouzit kdyz se user diva na mesicni stats
global $s;
list($d,$mo,$y) = split('-',date('j-n-Y',$s[cas]));
dq("delete from $s[pr]days$data[size] where d = '$d' AND m = '$mo' AND y = '$y' AND number = '$s[number]'",1);
$q = dq("select number,userid,size,cl_m,cl_w,m0+m1+m2+m3+m4+m5+m6+m7+m8+m9+m10+m11+m12+m13+m14+m15+m16+m17+m18+m19+m20+m21+m22+m23,w0+w1+w2+w3+w4+w5+w6+w7+w8+w9+w10+w11+w12+w13+w14+w15+w16+w17+w18+w19+w20+w21+w22+w23 from $s[pr]day where userid = '$data[userid]' AND size = '$data[size]'",1);
$r = mysql_fetch_row($q);
if ( ($r[3]) OR ($r[4]) OR ($r[5]) OR ($r[6]) )
dq("insert into $s[pr]days$r[2] values ('$r[0]','$r[1]','$d','$mo','$y','$r[5]','$r[6]','$r[3]','$r[4]')",0);
}

###############################################################################
###############################################################################
###############################################################################

function statistic_month_by_month($in) { 
global $s,$m;
// stejne pro normal + sponsor (musi byt nahore definovany $s[sponsor] 0 nebo 1
// stejne pro B a T
$l[0] = '<td align="center" valign="bottom" width="40" nowrap>';
$l[1] = '<td align="center" width="40" nowrap class="tdw">';
$l[2] = '<td align="center" width="40" nowrap class="tdw">';
$r = '</td>'."\n";
$in[size] = eregi_replace('statistic_month_by_month_','',$in[action]);
days_to_months_one_user($in); 
if (!$in[year]) $in[year] = year_number(0);
if (!$in[month]) $in[month] = month_number(0);
$a[prev_y] = $in[year]-1; $a[next_y] = $in[year]+1;

$tr[m] = '<tr><td align="left" class="tdh"><span class="text10">'.$m[month].'</span></td>';
$tr[i_m] = $tr[cl_m] = '<tr><td align="left" nowrap class="tdh"><span class="text10">'.$m[your_p].'</span></td>';
$tr[i_w] = $tr[cl_w] = '<tr><td align="left" nowrap class="tdh"><span class="text10">'.$m[your_ad].'</span></td>';

$q = dq("select * from $s[pr]months$in[size] where number = '$s[number]' AND y = '$in[year]'",1);
while ($month = mysql_fetch_assoc($q))
{ $n = $month[m]; $x[$n][m] .= $month[m];
  $x[$n][i_m] .= $month[i_m]; $x[$n][cl_m] .= $month[cl_m];
  $x[$n][i_w] .= $month[i_w]; $x[$n][cl_w] .= $month[cl_w];
}
for ($n=1;$n<=12;$n++)
{ if (!$x[$n])
  { $x[$n][m] = $n;
    $x[$n][i_m] = 0; $x[$n][cl_m] = 0; $x[$n][i_w] = 0; $x[$n][cl_w] = 0;
  }
  $tr[m] .= '<td align="center" class="tdh"><span class="text10">'.$x[$n][m].'</span></td>';
  $tr[i_m] .= $l[1].$x[$n][i_m].$r; $tr[cl_m] .= $l[1].$x[$n][cl_m].$r;
  $tr[i_w] .= $l[2].$x[$n][i_w].$r; $tr[cl_w] .= $l[2].$x[$n][cl_w].$r;
  if ($big1<$x[$n][i_w]) $big1 = $x[$n][i_w]; if ($big1<$x[$n][i_m]) $big1 = $x[$n][i_m];
  if ($big2<$x[$n][cl_w]) $big2 = $x[$n][cl_w]; if ($big2<$x[$n][cl_m]) $big2 = $x[$n][cl_m];
}
if ($s[sponsor]) unset($tr[i_m],$tr[cl_m]);

$tr[g_i] = '<tr><td></td>'; $tr[g_cl] = '<tr><td></td>';
$pomer1 = $big1/100; $pomer2 = $big2/100;
for ($n=1;$n<=12;$n++)
{ if ($pomer1) { $i_w = ceil($x[$n][i_w]/$pomer1); $i_m = ceil($x[$n][i_m]/$pomer1); }
  if ($pomer2) { $cl_w = ceil($x[$n][cl_w]/$pomer2); $cl_m = ceil($x[$n][cl_m]/$pomer2); }
  if (!$i_w) $i_w = 1; if (!$i_m) $i_m = 1; if (!$cl_w) $cl_w = 1; if (!$cl_m) $cl_m = 1;
  $tr[g_i] .= $l[0]; $tr[g_cl] .= $l[0];
  if (!$s[sponsor]) $tr[g_i] .= '<img src="1.jpg" height="'.$i_m.'" width="18">';
  $tr[g_i] .= '<img src="2.jpg" height="'.$i_w.'" width="18">'.$r."\n";
  if (!$s[sponsor]) $tr[g_cl] .= '<img src="1.jpg" height="'.$cl_m.'" width="18">';
  $tr[g_cl] .= '<img src="2.jpg" height="'.$cl_w.'" width="18"></td>'.$r."\n";
}

$tr[m] .= '</tr>'; $tr[i_m] .= '</tr>'; $tr[cl_m] .= '</tr>'; $tr[i_w] .= '</tr>'; $tr[cl_w] .= '</tr>';
$tr[g_i] .= '</tr>'; $tr[g_cl] .= '</tr>'; 

$a[table1] = '<table border="0" width="600" cellspacing="0" cellpadding="2">'.$tr[g_i].$tr[m].$tr[i_m].$tr[i_w].'</table><br>';
$a[table2] = '<table border="0" width="600" cellspacing="0" cellpadding="2">'.$tr[g_cl].$tr[m].$tr[cl_m].$tr[cl_w].'</table><br>';

$a = array_merge($in,$a);
$a[height] = $s['h'.$in[size]]; $a[width] = $s['w'.$in[size]];
if ($s[sponsor]) page_from_template('s_statistic_month_by_month.html',$a);
else page_from_template('u_statistic_month_by_month.html',$a);
exit;
}

###############################################################################

function days_to_months_one_user($data) {
// vezme data z tohoto mesice a da je do tabulky months$size - pouzit kdyz se user diva na stats
global $s;
today_to_days_one_user($data);
$sponsors = sponsors_in_array(); if (in_array($r[0],$sponsors)) $sponsor = 1; else $sponsor = 0;
$month = month_number(0); $year = year_number(0);
$q = dq("select sum(i_m),sum(i_w),sum(cl_m),sum(cl_w) from $s[pr]days$data[size] where m = '$month' AND y = '$year' AND number = '$s[number]'",1);
$r = mysql_fetch_row($q);
if ($r[1]) $r_w = round(100*($r[3]/$r[1]),2); else $r_w = 0;  // $r_w, $r_m - ratia
if ($r[0]) $r_m = round(100*($r[2]/$r[0]),2); else $r_m = 0;
dq("delete from $s[pr]months$data[size] where m = '$month' AND y = '$year' AND number = '$s[number]'",1);
dq("insert into $s[pr]months$data[size] values('$s[number]','$s[userid]','$month','$year','$r[0]','$r[2]','$r_m','$r[1]','$r[3]','$r_w','$sponsor')",1);
}

###############################################################################
###############################################################################
#########################        STATISTIC END         ########################
###############################################################################
###############################################################################












###############################################################################
###############################################################################
#########################            USER              ########################
###############################################################################
###############################################################################

function user_joined($in) {
global $s,$m,$HTTP_COOKIE_VARS;
foreach ($in as $k => $v) $in[$k] = trim($v);
if ( (!$in[userid]) OR (!$in[userpass]) OR (!$in[name]) OR (!$in[email]) ) $problem[] = $m[missingfield];
if (!eregi("^[a-z0-9]{5,15}$",$in[userid])) $problem[] = $m[wrongusername];
if (!eregi("^[a-z0-9]{5,15}$",$in[userpass])) $problem[] = $m[wrongpassword];
if (strlen ($in[name]) > 100) $problem[] = $m[longname];
if (!check_email($in[email])) $problem[] = $m[wrongemail];
elseif (strlen ($in[email]) > 100) $problem[] = $m[longemail];
if (!$s[sponsor])
{ if (!$in[siteurl]) $problem[] = $m[missingfield];
  elseif (!check_url($in[siteurl])) $problem[] = $m[wrongurl];
  elseif (strlen ($in[siteurl]) > 100) $problem[] = $m[longurl];
  elseif ($a=check_blacklist($in[siteurl])) $problem[] = $a;
}
$in = replace_array_text($in);
if ($problem)
{ $in[info] = eot($m[errors],implode('<br>',$problem));
  if ($s[sponsor]) page_from_template('s_join.html',$in); else page_from_template('u_join.html',$in); exit;
}

// member exists
$q = dq("select count(*) from $s[pr]members where userid = '$in[userid]'",1);
$data = mysql_fetch_row($q);
if ($data[0]>0)
{ $in[info] = iot($m[usernameused]); 
  if ($s[sponsor]) page_from_template('s_join.html',$in); else page_from_template('u_join.html',$in); exit;
}

if ( ($s[aff_manually]) AND ($HTTP_COOKIE_VARS[EB_affiliate]) ) $o = '___'.$HTTP_COOKIE_VARS[EB_affiliate]; else $o = $HTTP_COOKIE_VARS[EB_affiliate];
dq("insert into $s[pr]members values('$in[userid]','$in[userpass]','$in[email]','$in[siteurl]','$in[name]','','$o','$s[cas]',NULL,'$s[a_accept]','$s[sponsor]','0','0','0','0')",1);
$number = mysql_insert_id();
if ($s[sponsor]) $advantage = $s[def_adv_s]; else $advantage = $s[def_adv];
for ($x=1;$x<=3;$x++)
{ if (!$s[sponsor]) { $ratio = $s["ratio$x"]; $forclick = $s["forclick$x"]; $freecredit = $s["freecredit$x"]; }
  dq("insert into $s[pr]stats$x values('$number','$in[userid]','$s[a_accept]','0','1','0','','','','','','','$ratio','0','$forclick','$freecredit','0','0','0','0','0','0','0','$freecredit','0','0','0','0','0','$s[cas]','','','','','','','$advantage','$s[sponsor]')",1);
  dq("insert into $s[pr]link$x values('$number','$in[userid]','','','','','','','','','','','','','','','','','$s[sponsor]',0)",1);
  dq("insert into $s[pr]b$x values('$number','$in[userid]','0','0','0','0','0','0','0','0','0')",1);
  dq("insert into $s[pr]day (number,userid,size) values('$number','$in[userid]','$x')",1);
}
if ((!$s[aff_manually]) AND ($HTTP_COOKIE_VARS[EB_affiliate]))
dq("UPDATE $s[pr]stats$s[whereref] SET i_free=i_free+$s[forref],i_refer=i_refer+$s[forref],i_nu=i_nu+$s[forref] WHERE userid = '$HTTP_COOKIE_VARS[EB_affiliate]'",0);

if ($s[a_accept])
{ if ($s[sponsor])
  { $in[memberfile] = "$s[phpurl]/s_user.php"; mail_from_template('email_s_join.txt',$in); }
  else
  { $in[memberfile] = "$s[phpurl]/member.php"; mail_from_template('email_u_join.txt',$in); }
}
if ($s[inew])
{ $in[adminfile] = $s[adminfile]; $in[memberemail] = $in[email]; $in[email] = $s[adminemail];
  if (!$s[sponsor]) mail_from_template('email_admin_u.txt',$in);
  else mail_from_template('email_admin_s.txt',$in);
}
$q = dq("select * from $s[pr]members where userid = '$in[userid]' AND userpass = '$in[userpass]'",1);
$data = mysql_fetch_assoc($q);
$data[memberfile] = $in[memberfile];
if ($s[sponsor]) { $data[memberfile] = "$s[phpurl]/s_user.php"; page_from_template ('s_joined.html',$data); }
else { $data[memberfile] = "$s[phpurl]/member.php"; page_from_template ('u_joined.html',$data); }
}

###############################################################################
###############################################################################
###############################################################################

function user_edit($userid) {
global $s;
// stejne pro normal + sponsor
// stejne pro B a T
$q = dq("select * from $s[pr]members where userid = '$s[userid]'",1);
$data = mysql_fetch_assoc($q);
$data[info] = $s[info];
if ($s[sponsor]) page_from_template('s_edit.html',$data);
else page_from_template('u_edit.html',$data);
}

###############################################################################

function user_edited($in) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T
if ( (!$in[name]) OR (!$in[newpass]) OR (!$in[email]) ) $problem[] = $m[missingfield];
if (!eregi("^[a-z0-9]{5,15}$",$in[newpass])) $problem[] = $m[wrongpassword];
if (strlen ($in[name]) > 100) $problem[] = $m[longname];
if (strlen ($in[email]) > 100) $problem[] = $m[longemail];
if (!check_email($in[email])) $problem[] = $m[wrongemail];
if (!$s[sponsor])
{ if (!check_url($in[siteurl])) $problem[] = $m[wrongurl];
  if (strlen ($in[siteurl]) > 100) $problem[] = $m[longurl];
  if ($a=check_blacklist($in[siteurl])) $problem[] = $a;
}
$in = replace_array_text($in);
if ($problem)
{ $in[info] = eot($m[errors],implode('<br>',$problem));
  if ($s[sponsor]) page_from_template('s_edit.html',$in);
  else page_from_template('u_edit.html',$in);
}

dq("update $s[pr]members set userpass='$in[newpass]',name='$in[name]',email='$in[email]',siteurl='$in[siteurl]' where userid = '$s[userid]'",1);
$q = dq("select email,siteurl,name,userid,userpass from $s[pr]members where userid = '$s[userid]' AND userpass = '$in[newpass]'",1);
$in = mysql_fetch_assoc($q);

if ($s[ichange])
{ $in[adminfile] = $s[adminfile]; 
  $in[memberemail] = $in[email]; $in[from] = $in[email] = $s[adminemail];
  if ($s[sponsor]) mail_from_template("email_admin_s_change.txt",$in);
  else mail_from_template("email_admin_u_change.txt",$in);
}
$s[info] = iot($m[i_saved]);
user_edit($in[userid]);
exit;
}

###############################################################################
###############################################################################
###############################################################################

function remind($in) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T
if (!$in[email])
{ if ($s[sponsor]) page_from_template('s_remind.html',$in);
  else page_from_template('u_remind.html',$in); }
$q = dq("select userid,userpass from $s[pr]members where email = '$in[email]'",1);
$data = mysql_fetch_array($q);
if (!$data[0])
{ $in[info] = iot($m[reminderror]);
  if ($s[sponsor]) page_from_template('s_remind.html',$in);
  else page_from_template('u_remind.html',$in); }
$data[email] = $in[email]; $data[loginurl] = "$s[phpurl]/$s[SCRmember]";
mail_from_template('email_remind.txt',$data);
$s[info] = iot($m[remind]);
if ($s[sponsor]) page_from_template('s_login.html',$s);
else page_from_template('u_login.html',$s);
}

###############################################################################
###############################################################################
#########################           USER END           ########################
###############################################################################
###############################################################################






















###############################################################################
###############################################################################
#########################           AD EDIT            ########################
###############################################################################
###############################################################################

function ad_edit($in) {
global $s,$m;
// stejne pro normal + sponsor
if ($in[size]) $size = $in[size]; else $size = str_replace('ad_','',$in[action]);

$q = dq("select * from $s[pr]link$size where userid = '$in[userid]'",1);
$link = mysql_fetch_assoc($q);
for ($x=1;$x<=3;$x++) $link["raw$x"] = htmlspecialchars(unreplace_once_html($link["raw$x"]));

$q = dq("select * from $s[pr]stats$size where userid = '$in[userid]'",1);
$stats = mysql_fetch_assoc($q);

if ($s["usecats$size"])
{ $q = dq("select catid,catname from $s[pr]categories where size = '$size'",1);
  while ($cats=mysql_fetch_row($q)) $categories[$cats[0]]=$cats[1];
  $link[category] = '<SELECT class="field1" name="category">';
  $link[czct] = '<SELECT class="field1" name="categories[]" size=5 multiple>';
  if ($stats[c0]) $selected=' selected'; else $selected='';
  $link[czct] .= "<option value=\"a\"$selected>$m[any]</option>\n";
  foreach ($categories as $k => $v)
  { if ($link[cat]==$k) $selected=' selected'; else $selected='';
    $link[category] .= "<option value=\"$k\"$selected>$v</option>\n";
	if ($stats[c0]) $selected='';
	elseif (($stats[c1]==$k) OR ($stats[c2]==$k) OR ($stats[c3]==$k) OR ($stats[c4]==$k) OR ($stats[c5]==$k)) $selected=' selected';
	else $selected='';
	$link[czct] .= "<option value=\"$k\"$selected>$v</option>\n";
  }
  $link[czct] .= '</select>'; $link[category] .= '</select>';
}
else $link[category] = $link[czct] = '<span class="text13">'.$m[na].'</span>';

if ($stats[approved]) $link[approved] = $m[i_approved]; else $link[approved] = $m[i_noapproved];
if (!$s[enablebyuser]) $link[enablebutton] = $link[ienabled] = '';
else
{ $link[enablebutton] = '<form METHOD="post" action="'.$s[SCRmember].'"><input type="hidden" name="userid" value="'.$s[userid].'">
  <input type="hidden" name="userpass" value="'.$s[userpass].'"><input type="hidden" name="size" value="'.$size.'">';
  if ($stats[enable]) 
  { $link[ienabled] = $m[i_isenabled];
    $link[enablebutton] .= '<input type="hidden" name="action" value="account_disable"><input type="submit" name="D1" value="'.$m[butdisable].'" class="button1">';
  }
  else
  { $link[ienabled] = $m[i_isdisabled];
    $link[enablebutton] .= '<input type="hidden" name="action" value="account_enable"><input type="submit" name="D1" value="'.$m[butenable].'" class="button1">';
  }
  $link[enablebutton] .= '</form>';
}
$link[size]=$size; $link[w] = $s["w$size"]; $link[h] = $s["h$size"];
for ($x=1;$x<=3;$x++)
{ $link[link] = $x; $link["current_ad_$x"] = show_current_complete_ad($size,$x);
  if ($link["ad_kind_$x"]=='picture') $link["ad_kind_picture_$x"] = ' checked';
  elseif ($link["ad_kind_$x"]=='raw_html') $link["ad_kind_raw_html_$x"] = ' checked';
  if ($s[uploadban])
  { $link["current_banner_$x"] = show_current_banner($link["banner$x"],$size);
    if ($s[banner_exists]) $link["current_banner_$x"] .= "<br>
    <a href=\"$s[SCRmember]?action=banner_delete&size=$size&banner=$x&userid=$s[userid]&userpass=$s[userpass]\">$m[delete_banner]</a>";
  }
}
$link[userid] = $s[userid]; $link[userpass] = $s[userpass]; $link[info] = $s[info];
if ($s[sponsor])
{ if (($s[raw]) AND ($s[uploadban])) page_from_template('s_ad_edit_upload_raw.html',$link);
  elseif ($s[raw]) page_from_template('s_ad_edit_raw.html',$link);
  elseif ($s[uploadban]) page_from_template('s_ad_edit_upload.html',$link);
  else page_from_template('s_ad_edit.html',$link);
}
else
{ if (($s[raw]) AND ($s[uploadban])) page_from_template('u_ad_edit_upload_raw.html',$link);
  elseif ($s[raw]) page_from_template('u_ad_edit_raw.html',$link);
  elseif ($s[uploadban]) page_from_template('u_ad_edit_upload.html',$link);
  else page_from_template('u_ad_edit.html',$link);
}
exit;
}

############################################################################

function ad_edited($in) {
global $s,$m,$HTTP_POST_FILES;
// stejne pro normal + sponsor az na nazev sablony
$size = $in[size];
if ($s[uploadban])
{ $q = dq("select banner1,banner2,banner3 from $s[pr]link$size where number = '$s[number]'",1);
  $old_banners = mysql_fetch_assoc($q);
  for ($x=1;$x<=3;$x++)
  { if (!$in["banner$x"] = banner_upload($HTTP_POST_FILES["banner_uploaded_$x"],$size,$x))
    $in["banner$x"] = $old_banners["banner$x"];
  }
}

if ( ($s["usecats$size"]) AND (!$in[category]) ) $problem[] = $m[misscategory];
elseif (!$s["usecats$size"]) $in[category] = 1;

for ($x=1;$x<=3;$x++)
{ if (!$s[raw]) $in["ad_kind_$x"] = 'picture';
  if ( (($in["ad_kind_$x"]) != 'picture') AND (($in["ad_kind_$x"]) != 'raw_html') ) $in["ad_kind_$x"] = '';
  if ($a=check_blacklist($in["url$x"])) $problem[] = $a;
  if (trim($in["url$x"]))
  { if (!(eregi("^http://..*",$in["url$x"]))) $problem[] = "$m[wrong_url] $x.";
    $y = htmlspecialchars($in["url$x"]);
    if ($in["url$x"]!=$y) $problem[] = "$m[wrong_url] $x.";
  }
  $banner_format = 0;
  if (trim($in["banner$x"]))
  { if (($s[flash]) AND (eregi(".*swf$",$in["banner$x"]))) $banner_format = 1;
    elseif ( (eregi(".*gif$",$in["banner$x"])) OR (eregi(".*jpg$",$in["banner$x"])) OR (eregi(".*jpeg$",$in["banner$x"])) OR (eregi(".*png$",$in["banner$x"])) ) $banner_format = 1;
    if (!$banner_format) $problem[] = "$m[wrong_banner] $x.";
  }
}
if ($problem) problem (eot($m[errors],implode('<br>',$problem)));

for ($x=1;$x<=3;$x++) $raw[$x] = replace_once_html($in["raw$x"]);
$in = replace_array_text($in);

dq("update $s[pr]link$size set 
  url1='$in[url1]',banner1='$in[banner1]',alt1='$in[alt1]',raw1='$raw[1]',ad_kind_1='$in[ad_kind_1]',
  url2='$in[url2]',banner2='$in[banner2]',alt2='$in[alt2]',raw2='$raw[2]',ad_kind_2='$in[ad_kind_2]',
  url3='$in[url3]',banner3='$in[banner3]',alt3='$in[alt3]',raw3='$raw[3]',ad_kind_3='$in[ad_kind_3]',
  cat='$in[category]' where number = '$s[number]'",1);
for ($x=1;$x<=3;$x++)
{ $y = get_complete_html($size,$s[number],$x,$in["banner$x"],$in["url$x"],$in["alt$x"],$raw[$x],$in["ad_kind_$x"]);
  $in["htmla$x"] = $y[0]; $in["htmlb$x"] = $y[1];
}
if ((!$in[categories]) OR ($in[categories][0]=='a')) $c0 = 1;
else { $c0 = 0; $c1=$in[categories][0]; $c2=$in[categories][1]; $c3=$in[categories][2]; $c4=$in[categories][3]; $c5=$in[categories][4]; }

if ((!$in[htmla1]) AND (!$in[htmla2]) AND (!$in[htmla3])) $autoapr = 0; elseif ($s[sponsor]) $autoapr = $s[s_adautoapr]; else $autoapr = $s[adautoapr];
dq("update $s[pr]stats$size set linka1='$in[htmla1]',linka2='$in[htmla2]',linka3='$in[htmla3]',linkb1='$in[htmlb1]',linkb2='$in[htmlb2]',linkb3='$in[htmlb3]',
  category='$in[category]',c0='$c0',c1='$c1',c2='$c2',c3='$c3',c4='$c4',c5='$c5',approved='$autoapr' where number = '$s[number]'",1);

if ($s[iad])
{ $q = dq("select catname from $s[pr]categories where size = '$size' AND catid = '$in[category]'",1);
  $x = mysql_fetch_row($q); $in[category] = $x[0];
  $in[adminfile] = $s[adminfile]; $in[email] = $s[adminemail];
  if ($s[sponsor]) mail_from_template('email_admin_s_ad.txt',$in);
  else mail_from_template('email_admin_u_ad.txt',$in);
}
$s[info] = iot($m[i_saved]);
ad_edit($in);
}

########################################################################

function banner_upload($file,$size,$banner) {
global $s,$m;

if (!$file[name]) return NULL;
if (!is_uploaded_file($file[tmp_name])) problem ($m[uploaderror]);
if (file_exists("$s[phppath]/userbanners/temp-$s[userid]"))
unlink ("$s[phppath]/userbanners/temp-$s[userid]");
move_uploaded_file($file[tmp_name],"$s[phppath]/userbanners/temp-$s[userid]");
if (($s[flash]) AND (strstr($file[name],'.swf'))) $obrazek = 'swf';
else
{ $bannersize = getimagesize("$s[phppath]/userbanners/temp-$s[userid]");
  if ($bannersize[2]==1) $obrazek = 'gif'; if ($bannersize[2]==2) $obrazek = 'jpg'; if ($bannersize[2]==3) $obrazek = 'png';
}
if (!$obrazek) { unlink("$s[phppath]/userbanners/temp-$s[userid]"); problem($m[wrongimgfrm]); }
if ((filesize("$s[phppath]/userbanners/temp-$s[userid]")) > $s["bannermax$size"])
{ unlink("$s[phppath]/userbanners/temp-$s[userid]"); problem ($m[bannersize1].' '.$s["bannermax$size"]." $m[bytes]. $m[bannersize2] $file[size] $m[bytes]."); }

$direc = opendir("$s[phppath]/userbanners");
while ($file = readdir($direc))
{ if (eregi("^$s[userid]-$size-$banner..*",$file)) unlink ("$s[phppath]/userbanners/$file");
  if (eregi("^[0-9]+-$s[userid]-$size-$banner..*",$file)) unlink ("$s[phppath]/userbanners/$file");
}
closedir($direc);

$cas = $s[cas];
rename ("$s[phppath]/userbanners/temp-$s[userid]","$s[phppath]/userbanners/$cas-$s[userid]-$size-$banner.$obrazek");
chmod ("$s[phppath]/userbanners/$cas-$s[userid]-$size-$banner.$obrazek",0644);
return "$s[phpurl]/userbanners/$cas-$s[userid]-$size-$banner.$obrazek";
}

###############################################################################

function banner_delete($in) {
global $s,$m;
$direc = opendir("$s[phppath]/userbanners");
while ($file = readdir($direc))
{ if (eregi("^[0-9]+-$s[userid]-$in[size]-$in[banner]..*",$file)) unlink ("$s[phppath]/userbanners/$file"); }
closedir($direc);
dq("update $s[pr]stats$in[size] set linka$in[banner]='',linkb$in[banner]='' where userid = '$in[userid]'",1);
$q = dq("select linka1,linka2,linka3 from $s[pr]stats$in[size] where userid = '$in[userid]'",1);
$ad = mysql_fetch_row($q);
if (($ad[0]) OR ($ad[1]) OR ($ad[2]))
{ if ($s[sponsor]) $approved = $s[s_adautoapr]; else $approved = $s[adautoapr]; }
else $approved = 0; 
dq("update $s[pr]stats$in[size] set approved = '$approved' where userid = '$in[userid]'",1);
dq("update $s[pr]link$in[size] set banner$in[banner] = '' where userid = '$in[userid]'",1);
$s[info] = iot($m[i_b_deleted]);
ad_edit($in);
}

###############################################################################

function account_enable($a) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T
dq("update $s[pr]stats$a[size] set enable = 1 where userid = '$s[userid]'",1);
$s[info]=iot($m[i_enabled]);
ad_edit($a);
}

###############################################################################

function account_disable($a) {
global $s,$m;
// stejne pro normal + sponsor
// stejne pro B a T
dq("update $s[pr]stats$a[size] set enable = 0 where userid = '$s[userid]'",1);
$s[info]=iot($m[i_disabled]);
ad_edit($a);
}

###############################################################################
###############################################################################
#########################         AD EDIT END          ########################
###############################################################################
###############################################################################



?>