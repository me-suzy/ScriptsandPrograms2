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


include("./common.php");
switch ($HTTP_GET_VARS[action]) {
case 'left_frame'		: left_frame();
case 'home'				: home();
case 'statistic'		: statistic();
}

##############################################################################
##############################################################################
##############################################################################

function left_frame() {
global $s,$HTTP_SESSION_VARS;
$q = dq("select * from $s[pr]moderators where username='$HTTP_SESSION_VARS[admuser]'",1);
$user = mysql_fetch_assoc($q);
$left = '<a target="right" href="'; $right = '</a><br>';
include('./_head.txt');
echo '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td align="center" valign="top">
<table border=0 width=95% cellspacing=2 cellpadding=0><TR><TD align="left" nowrap>
<span class="text13blue"><b>
Members<br>';
if ($user[accounts]) echo $left.'e_users.php?action=accounts_stats_home">Accounts/stats'.$right;
if ($user[accounts]) echo $left.'e_users.php?action=ads_show_form">Ads'.$right;
if ($user[accounts]) echo $left.'e_users.php?action=search_form">Search'.$right;
if ($user[accounts]) echo $left.'e_users.php?action=cheaters_main">Cheaters?'.$right;
if ($user[users]) echo $left.'e_users.php?action=credits_add_all_form">Add credits'.$right;
if ($user[config]) echo $left.'e_tools.php?action=ratios_home">Sliding ratios'.$right;
echo '<br>Sponsors<br>';
if ($user[s_accounts]) echo $left.'s_users.php?action=accounts_stats_home">Accounts/stats'.$right;
if ($user[s_accounts]) echo $left.'s_users.php?action=ads_show_form">Ads'.$right;
if ($user[sponsors]) echo $left.'s_users.php?action=users_orders">Users/orders'.$right;
if ($user[sponsors]) echo $left.'s_tools.php?action=packages_home">Packages'.$right;
if ($user[sponsors]) echo $left.'s_users.php?action=credits_add_all_form">Add credits'.$right;
echo '<br>Tools<br>';
echo $left.'main.php?action=statistic">Info & Statistic'.$right;
echo $left.'t_ads.php?action=fix_ads_home">Fix ads'.$right;
if ($user[reset]) echo $left.'t_admin.php?action=reset_rebuild_home">Reset/rebuild'.$right;
if ($user[backup]) echo $left.'t_admin.php?action=backup_home">Database'.$right;
if ($user[blacklist]) echo $left.'t_config.php?action=blacklist">Blacklist'.$right;
if ($user[email_u]) echo $left.'t_admin.php?action=email_members">Email members'.$right;
echo '<br>System<br>';
if ($user[tmpl_msg]) echo $left.'t_ads.php?action=default_ads_home">Default ads'.$right;
if ($user[tmpl_msg]) echo $left.'t_config.php?action=messages_edit">Messages'.$right;
if ($user[admins]) echo $left.'t_admin.php?action=moderators_home">Moderators'.$right;
if ($user[config]) echo $left.'configuration.php">Configuration'.$right;
if ($user[tmpl_msg]) echo $left.'t_config.php?action=templates_home">Templates'.$right;
if ($user[config]) echo $left.'t_admin.php?action=uninstall">Uninstall'.$right;
echo '<br><a target="_top" href="login.php?action=log_off">Log off'.$right.
'</td></tr></table></td></tr></table></center></span>
</span>';
exit;
}

##############################################################################

function home() {
global $s;
include('./_head.txt');
?>
<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td width=750 align="center" valign="top">
<br><img src="logo.gif" border=0><br><br><br><br><br>
<span class="text13blue"><b>Welcome to the Easy Banner Pro Admin Area</b></span><br><br>
<span class="text13">Please select a function from the menu on the left</span>
</td></tr></table>
</center>
<?PHP
exit;
}

##############################################################################

function statistic() {
global $s;
include('./_head.txt');

$a = '<td align="center" width="50"><span class="text13">'; $b = '</span></td>'."\n";
$q = dq("select count(*) from $s[pr]members where sponsor = '0'",1);
$x = mysql_fetch_row($q); $total[members] = $x[0];
$q = dq("select count(*) from $s[pr]members where sponsor = '1'",1);
$x = mysql_fetch_row($q); $total[sponsors] = $x[0];
$info = iot('Info').'<table border="0" width="400" cellspacing="0" cellpadding="2" class="table1">'.'
<tr><td align="left" nowrap><span class="text13">You use version'.$b.$a.'2.8'.$b.'</tr>
<tr><td align="left" nowrap><span class="text13">Exchange users'.$b.$a.$total[members].$b.'</tr>
<tr><td align="left" nowrap><span class="text13">Paid users (sponsors)'.$b.$a.$total[sponsors].$b.'</tr>
</table><br>';
echo $info;

for ($size=1;$size<=3;$size++)
{ $q = dq("select sum(i_m) as i_m,sum(c_m) as cl_m,sum(i_w) as i_w,sum(c_w) as cl_w from $s[pr]stats$size",1);
  $total[$size] = mysql_fetch_assoc($q);
  $total[i_m] = $total[i_m] + $total[$size][i_m]; $total[cl_m] = $total[cl_m] + $total[$size][cl_m];
  $total[i_w] = $total[i_w] + $total[$size][i_w]; $total[cl_w] = $total[cl_w] + $total[$size][cl_w];
  $tr[head] .= $a.$size.$b; 
  $tr[i_m] .= $a.$total[$size][i_m].$b; $tr[cl_m] .= $a.$total[$size][cl_m].$b; 
  $tr[i_w] .= $a.$total[$size][i_w].$b; $tr[cl_w] .= $a.$total[$size][cl_w].$b; 
}
$tr[head] = '<tr><td align="left" nowrap><span class="text13">Ad size'.$b.$a.'All'.$b.$tr[head].'</tr>';
$tr[i_m] = '<tr><td align="left" nowrap><span class="text13">Impr. sent by users'.$b.$a.$total[i_m].$b.$tr[i_m].'</tr>';
$tr[cl_m] = '<tr><td align="left" nowrap><span class="text13">Clicks sent by users'.$b.$a.$total[cl_m].$b.$tr[cl_m].'</tr>';
$tr[i_w] = '<tr><td align="left" nowrap><span class="text13">Impr. received by users'.$b.$a.$total[i_w].$b.$tr[i_w].'</tr>';
$tr[cl_w] = '<tr><td align="left" nowrap><span class="text13">Clicks received by users'.$b.$a.$total[cl_w].$b.$tr[cl_w].'</tr>';

$statistic = iot('Statistic').'<table border="0" width="400" cellspacing="0" cellpadding="2" class="table1">'.$tr[head].$tr[i_m].$tr[cl_m].$tr[i_w].$tr[cl_w].'</table><br>';
echo $statistic;

echo iot('Months of Year '.$s[year]);
$a = '<td align="center" width="30"><span class="text10">'; $b = '</span></td>'."\n";
for ($size=1;$size<=3;$size++)
{ unset($x,$tr);
  $q = dq("select m,sum(i_m) as i_m,sum(cl_m) as cl_m,sum(i_w) as i_w,sum(cl_w) as cl_w from $s[pr]months$size where y = '$s[year]' group by m",1);
  while ($month = mysql_fetch_assoc($q))
  { //echo "$month[m] - $month[cl_w]<br>";
	$n = $month[m]; $x[$n][m] .= $month[m];
    $x[$n][i_m] .= $month[i_m]; $x[$n][cl_m] .= $month[cl_m];
    $x[$n][i_w] .= $month[i_w]; $x[$n][cl_w] .= $month[cl_w];
    $all[$n][i_m] = $all[$n][i_m] + $month[i_m]; $all[$n][cl_m] = $all[$n][cl_m] + $month[cl_m];
    $all[$n][i_w] = $all[$n][i_w] + $month[i_w]; $all[$n][cl_w] = $all[$n][cl_w] + $month[cl_w];
  }
  for ($n=1;$n<=12;$n++)
  { if (!$x[$n])
    { $x[$n][i_m] .= 0; $x[$n][cl_m] .= 0;
      $x[$n][i_w] .= 0; $x[$n][cl_w] .= 0; 
    }
    $total['i_m'.$size] = $total['i_m'.$size] + $x[$n][i_m]; $total['cl_m'.$size] = $total['cl_m'.$size] + $x[$n][cl_m];
    $total['i_w'.$size] = $total['i_w'.$size] + $x[$n][i_w]; $total['cl_w'.$size] = $total['cl_w'.$size] + $x[$n][cl_w];
    $tr[m] .= $a.$n.$b;
    $tr[i_m] .= $a.$x[$n][i_m].$b; $tr[cl_m] .= $a.$x[$n][cl_m].$b;
    $tr[i_w] .= $a.$x[$n][i_w].$b; $tr[cl_w] .= $a.$x[$n][cl_w].$b;
  }
  $n = 0;
  $tr[m] = '<td align="left" nowrap><span class="text10">Month'.$b.$a.All.$b.$tr[m].'</tr>';
  $tr[i_m] = '<td align="left" nowrap><span class="text10">Impr. sent by users'.$b.$a.$total['i_m'.$size].$b.$tr[i_m].'</tr>';
  $tr[cl_m] = '<td align="left" nowrap><span class="text10">Clicks sent by users'.$b.$a.$total['cl_m'.$size].$b.$tr[cl_m].'</tr>';
  $tr[i_w] = '<td align="left" nowrap><span class="text10">Impr. received by users'.$b.$a.$total['i_w'.$size].$b.$tr[i_w].'</tr>';
  $tr[cl_w] = '<td align="left" nowrap><span class="text10">Clicks received by users'.$b.$a.$total['cl_w'.$size].$b.$tr[cl_w].'</tr>';
  foreach ($tr as $k => $v)
  { $n++;
    if ($n%2) $tr[$k] = '<tr bgcolor="#FFFBEE">'.$v; else $tr[$k] = '<tr>'.$v;
  }
  $table[months] .= iot('Size '.$size).'<table border="0" width="600" cellspacing="0" cellpadding="2" class="table1">'.$tr[m].$tr[i_m].$tr[cl_m].$tr[i_w].$tr[cl_w].'</table><br>';
}
unset($tr,$total);
for ($n=1;$n<=12;$n++)
{ if (!$all[$n][i_m]) $all[$n][i_m] = 0; if (!$all[$n][cl_m]) $all[$n][cl_m] = 0;
  if (!$all[$n][i_w]) $all[$n][i_w] = 0; if (!$all[$n][cl_w]) $all[$n][cl_w] = 0;
  $tr[m] .= $a.$n.$b;
  $tr[i_m] .= $a.$all[$n][i_m].$b; $tr[cl_m] .= $a.$all[$n][cl_m].$b;
  $tr[i_w] .= $a.$all[$n][i_w].$b; $tr[cl_w] .= $a.$all[$n][cl_w].$b;
  $total[i_m] = $total[i_m] + $all[$n][i_m]; $total[cl_m] = $total[cl_m] + $all[$n][cl_m];
  $total[i_w] = $total[i_w] + $all[$n][i_w]; $total[cl_w] = $total[cl_w] + $all[$n][cl_w];
}
$tr[m] = '<td align="left" nowrap><span class="text10">Month'.$b.$a.All.$b.$tr[m].'</tr>';
$tr[i_m] = '<td align="left" nowrap><span class="text10">Impr. sent by users'.$b.$a.$total[i_m].$b.$tr[i_m].'</tr>';
$tr[cl_m] = '<td align="left" nowrap><span class="text10">Clicks sent by users'.$b.$a.$total[cl_m].$b.$tr[cl_m].'</tr>';
$tr[i_w] = '<td align="left" nowrap><span class="text10">Impr. received by users'.$b.$a.$total[i_w].$b.$tr[i_w].'</tr>';
$tr[cl_w] = '<td align="left" nowrap><span class="text10">Clicks received by users'.$b.$a.$total[cl_w].$b.$tr[cl_w].'</tr>';
foreach ($tr as $k => $v)
{ $n++;
  if ($n%2) $tr[$k] = '<tr bgcolor="#FFFBEE">'.$v; else $tr[$k] = '<tr>'.$v;
}
$table[months_all] = iot('All sizes').'<table border="0" width="600" cellspacing="0" cellpadding="2" class="table1">'.$tr[m].$tr[i_m].$tr[cl_m].$tr[i_w].$tr[cl_w].'</table><br>';
echo $table[months_all].$table[months];

echo '<span class="text10"><b>Notes</b><br><br>
If you have limited the number of impressions which can sent by each user from one IP, this statistic contains only those impressions which does not exceed this limit. Impressions over this limit are not counted.<br><br>
The overall statistic and statistic for months come from different tables. It means that if you have reseted some statistic or ran the Daily Job unregularly, these data don\'t must be matching. In this case, the overall data are more reliable.<br><br>
The statistics for months may be delayed up to 5 hours.<br></span><br>';
include('./_footer.txt');
exit;
}

##############################################################################
##############################################################################
##############################################################################

?>