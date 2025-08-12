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


include('./functions.php');
include_once("$s[phppath]/data/messages.php");
if (!$s[nocron]) include_once("$s[phppath]/rebuild_f.php");
include('./data/time.php');

check_mini_job($s[cas]);

$month = month_number(0); $year = year_number(0);

for ($x=1;$x<=3;$x++)
{ $q = dq("select sum(i_m),sum(c_m) from $s[pr]stats$x",0);
  $r = mysql_fetch_row($q);
  $a["i_$x"] = $r[0]; $a[i] = $a[i] + $a["i_$x"];
  $a["c_$x"] = $r[1]; $a[c] = $a[c] + $a["c_$x"];
}

for ($x=1;$x<=3;$x++)
{ $q = dq("select sum(i_m),sum(cl_m) from $s[pr]days$x where m = '$month' AND y = '$year'",0);
  $r = mysql_fetch_row($q); 
  if ($r[0]) $a["m_i_$x"] = $r[0]; else $a["m_i_$x"] = 0; if ($a["m_i_$x"]>$a["total$x"]) $a["month$x"] = $a["total$x"];
  $a[m_i] = $a[m_i] + $a["m_i_$x"];
  if ($r[1]) $a["m_c_$x"] = $r[1]; else $a["m_c_$x"] = 0; //if ($a["m_c_$x"]>$a["total$x"]) $a["month$x"] = $a["total$x"];
  $a[m_c] = $a[m_c] + $a["m_c_$x"];
}
$q = dq("select count(*) from $s[pr]members",0);
$r = mysql_fetch_row($q); $a[members] = $r[0];

$a[hourly_graph] = statistic_hours_all();
$x = statistic_day_by_day_all();
$a[month_impressions] = $x[table1]; $a[month_clicks] = $x[table2]; $a[current_month] = $x[current_month];
page_from_template('info.html',$a);


###############################################################################
###############################################################################
###############################################################################

function statistic_hours_all() {
global $s,$m;
$a[size] = $in[size];
$l[0] = '<td align="center" valign="bottom" nowrap>';
$l[1] = '<td align="center" nowrap class="tdw" width="18">';
$r = '</td>'."\n";
$q = dq("select sum(m0),sum(m1),sum(m2),sum(m3),sum(m4),sum(m5),sum(m6),sum(m7),sum(m8),sum(m9),sum(m10),sum(m11),sum(m12),sum(m13),sum(m14),sum(m15),sum(m16),sum(m17),sum(m18),sum(m19),sum(m20),sum(m21),sum(m22),sum(m23) from $s[pr]day",1);
$radek[1] = '<td align="left" class="tdh"><span class="text10">'.$m[hour].'</span></td>';
$radek[2] = '<td align="left" class="tdh"><span class="text10">'.$m[impressions].'</span></td>';
$data = mysql_fetch_row($q);
for ($x=0;$x<=23;$x++)
{ if ($data[$x]) $mo[$x] = $data[$x]; else $mo[$x] = 0;
  $radek[1] .= '<td align="center" class="tdh"><span class="text10">'.$x.$r;
  $radek[2] .= $l[1].$mo[$x].$r;
  if ($big1<$mo[$x]) $big1 = $mo[$x];
}
$a[table1] = '<table class="table_graph" cellpadding="1" cellspacing="0" border="0"><tr><td>&nbsp;</td>';
$pomer1 = $big1/100;
for ($x=0;$x<=23;$x++)
{ if ($pomer1) { $w[$x] = ceil($w[$x]/$pomer1); $mo[$x] = ceil($mo[$x]/$pomer1); }
  if (!$w[$x]) $w[$x] = 1; if (!$mo[$x]) $mo[$x] = 1;
  $a[table1] .= '<td align="center" valign="bottom" width="21">';
  $a[table1] .= '<img src="2.jpg" height="'.$mo[$x].'" width="10">';
}
$a[table1] .= '</tr>';
$x = 0;
foreach ($radek as $k => $v) $a[table1] .= "<tr>$v</tr>";
$a[hourly_graph] = $a[table1].'</table>';
return $a[hourly_graph];
}

###############################################################################
###############################################################################
###############################################################################

function statistic_day_by_day_all() { 
global $s,$m;
//today_to_days_one_user($in);
$in[year] = year_number(0); $in[month] = month_number(0);
$a[current_month] = $m['m'.$in[month]].' '.$in[year];
$kk = mktime(0,0,0,$in[month],15,$in[year]); $dni = date('t',$kk); 

for ($x=1;$x<=3;$x++)
{ $q = dq("select d,sum(i_m) as i,sum(cl_m) as c from $s[pr]days$x where m = '$in[month]' AND y = '$in[year]' group by d",1);
  while ($data = mysql_fetch_assoc($q))
  { $a['day'.$data[d]][i] = $a['day'.$data[d]][i] + $data[i];
    $a['day'.$data[d]][c] = $a['day'.$data[d]][c] + $data[c];
  }
}

if ($s[graph_vert])
{ $l[0] = '<td align="center" valign="bottom" nowrap>';
  $l[1] = '<td align="center" nowrap class="tdw">';
  $l[2] = '<td align="center" nowrap class="tdw">';
  $r = '</td>'."\n";
  for ($x=1;$x<=$dni;$x++)
  { if ($a["day$x"][i]) $i[$x] = $a["day$x"][i]; else $i[$x] = 0;
    if ($a["day$x"][c]) $c[$x] = $a["day$x"][c]; else $c[$x] = 0;
    $radek1[$x] = $radek2[$x] = '<tr><td align="center" class="tdh" nowrap><span class="text10">'.$x.'</span></td>';
    $radek1[$x] .= $l[1].$i[$x].'</span></td>';
    if (!$s[sponsor]) $radek2[$x] .= $l[1].$c[$x].'</span></td>';
    $radek2[$x] .= $l[1].'</span></td>';
    if ($big1<$i[$x]) $big1 = $i[$x]; if ($big2<$c[$x]) $big2 = $c[$x];
  }
  $a[table1] = $a[table2] = '<table border="0" cellpadding="2" cellspacing="0">
  <tr><td align="center" class="tdh"><span class="text10">'.$m[day].'</span></td>';
  $a[table1] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[impressions].'</span></td>';
  $a[table2] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$m[clicks].'</span></td>';
  $pomer1 = $big1/100; $pomer2 = $big2/100;
  for ($x=1;$x<=$dni;$x++)
  { if ($pomer1) $i[$x] = ceil($i[$x]/$pomer1);
    if ($pomer2) $c[$x] = ceil($c[$x]/$pomer2);
    if (!$i[$x]) $i[$x] = 1; if (!$c[$x]) $c[$x] = 1;
    $a[table1] .= $radek1[$x].'<td align="left" valign="bottom">';
    $a[table1] .= '<img src="3.jpg" width="'.$i[$x].'" height="10"></td></tr>'."\n";
    $a[table2] .= $radek2[$x].'<td align="left" valign="bottom">';
    $a[table2] .= '<img src="3.jpg" width="'.$c[$x].'" height="10"></td></tr>'."\n";
  }
  $a[table1] .= '</table>'; $a[table2] .= '</table>';
}
else
{ $l[0] = '<td align="center" valign="bottom" nowrap>';
  $l[1] = '<td align="center" nowrap class="tdw" width="18">';
  $r = '</td>'."\n";
  $radek[1] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[day].'</span></td>';
  $radek[2] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[impressions].'</span></td>';
  $radek[4] = '<td align="left" class="tdh" nowrap><span class="text10">'.$m[clicks].'</span></td>';
  for ($x=1;$x<=$dni;$x++)
  { if ($a["day$x"][i]) $i[$x] = $a["day$x"][i]; else $i[$x] = 0;
    if ($a["day$x"][c]) $c[$x] = $a["day$x"][c]; else $c[$x] = 0;
    $radek[1] .= '<td align="center" class="tdh" nowrap><span class="text10">'.$x.'</span></td>';
    $radek[2] .= $l[1].$i[$x].$r;
    $radek[4] .= $l[1].$c[$x].$r;
    if ($big1<$i[$x]) $big1 = $i[$x]; if ($big2<$c[$x]) $big2 = $c[$x];
  }
  $a[table1] = $a[table2] = '<table bordre="0" cellpadding="1" cellspacing="0"><tr><td>&nbsp;</td>';
  $pomer1 = $big1/100; $pomer2 = $big2/100;
  for ($x=1;$x<=$dni;$x++)
  { if ($pomer1) $i[$x] = ceil($i[$x]/$pomer1); if ($pomer2) $c[$x] = ceil($c[$x]/$pomer2);
    if (!$i[$x]) $i[$x] = 1; if (!$c[$x]) $c[$x] = 1;
    $a[table1] .= '<td align="center" valign="bottom" nowrap>';
    $a[table1] .= '<img src="1.jpg" height="'.$i[$x].'" width="10"></td>'."\n";
    $a[table2] .= '<td align="center" valign="bottom" nowrap>';
    $a[table2] .= '<img src="1.jpg" height="'.$c[$x].'" width="10"></td>'."\n";
  }
  $a[table1] .= '</tr>'; $a[table2] .= '</tr>';

  foreach ($radek as $k => $v)
  { if ($k==1) { $a[table1] .= "<tr>$v</tr>"; $a[table2] .= "<tr>$v</tr>"; }
    elseif ($k<=3) $a[table1] .= "<tr>$v</tr>";
    else $a[table2] .= "<tr>$v</tr>";
  }

  $a[table1] .= '</table>'; $a[table2] .= '</table>';
}
$a = array_merge($in,$a);
return $a;
}

###############################################################################
###############################################################################
###############################################################################

?>