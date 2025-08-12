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

function user_details_table($number) {
global $s;
if ($s[sponsor]) check_session ('s_accounts');
else check_session ('accounts');
// stejne pro normal a sponsor - jenom url jen pro normal (nemusi se menit nic, je tam podminka)
$q = dq("select * from $s[pr]members where number = '$number'",0);
$user = mysql_fetch_assoc($q);
$user[date] = datum(0,$user[date]);
$user[affiliate] = str_replace('___','',$user[affiliate]);
?>
<table border="0" cellspacing="2" cellpadding="4" class="table1" width="500"><tr><td align="center">
<table border="0" cellspacing="0" cellpadding="2" width="400">
<tr><td colspan=2 align="center"><span class="text13blue"><b>Details</b></span></td></tr>
<?PHP
if ($s[SCRdetail]=='e_detail.php')  // jenom pro ne-sponsora
{ echo '<tr>
  <td align="left" nowrap><span class="text13">URL</span></td>
  <td align="left" nowrap><span class="text13"><a target="_blank" href="'.$user[siteurl].'">'.$user[siteurl].'</a></span></td>
  </tr>';
}
?>
<tr>
<td align="left" nowrap><span class="text13">Name</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $user[name]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Address</span></td>
<td align="left"><span class="text13"><?PHP echo $user[address]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Email</span></td>
<td align="left" nowrap><span class="text13"><a href="mailto:<?PHP echo "$user[email]\">$user[email]"; ?></a></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Password</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $user[userpass]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Date joined</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $user[date]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Referred by&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo "<a title=\"Click to view/edit details\" href=\"e_detail.php?username=$user[affiliate]\">$user[affiliate]"; ?></a>&nbsp;</span></td>
</tr>
<form METHOD="post" action="<?PHP echo $s[SCRdetail] ?>">
<input type="hidden" name="number" value="<?PHP echo $number; ?>">
<input type="hidden" name="action" value="user_edit">
<tr><td align="center" colspan="2"><input type="submit" name="co" value="Edit the details above" class="button1">
</td></tr></form>
</table></td></tr></table><br>
<?PHP
}

###########################################################################

function user_delete_table($number) {
global $s;
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
?>
<table border="0" cellspacing="2" cellpadding="4" class="table1" width="500"><tr><td align="center">
<table border="0" cellspacing="0" cellpadding="2" width="400"><tr><td align="center">
<span class="text13blue"><b>Delete this user</b></span>
<form METHOD="post" action="<?PHP echo $s[thisscript] ?>">
<input type="hidden" name="number" value="<?PHP echo $number; ?>">
<input type="hidden" name="action" value="user_delete">
<span class="text13">And send rejection email: </span>
<select class="field1" name="send_email"><option value="0">None</option>
<?PHP 
$dr = opendir("$s[phppath]/data/templates"); rewinddir($dr);
while ($q = readdir($dr))
{ if (eregi("^email_r_.*\.txt$",$q) AND (is_file("$s[phppath]/data/templates/$q")))
  $pole[] = $q; }
foreach ($pole as $k => $v) $list .= "<option value=\"$v\">$v</option>\n";
closedir ($dr); echo $list;
?>
</select><br><br>
<input type="submit" name="co" value="Delete now" class="button1"></td></form>
</tr></table>
<span class="text10"><b>Note:</b><br>
In the select list above are displayed all files saved in the 'templates' directory with names which begin to 'email_r_' and end to '.txt'. Feel free to edit/add/remove any of these files.<br></span>
</td></tr></table><br>
<?PHP
}

#############################################################################
#############################################################################
#############################################################################

function user_edit($number) {
global $s;
// stejne pro normal a sponsor - jenom url jen pro normal (nemusi se menit nic, je tam podminka)
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
$q = dq("select * from $s[pr]members where number = '$number'",0);
$zaznam = mysql_fetch_assoc($q);
include('./_head.txt');
echo $s[info];
?>
<span class="text13blue"><b>Edit User Details</span></b><br><br>
<table border="0" cellspacing="2" cellpadding="4" class="table1">
<form METHOD="post" action="<?PHP echo $s[SCRdetail] ?>">
<input type="hidden" name="number" value="<?PHP echo $number; ?>">
<input type="hidden" name="action" value="user_edited">
<tr>
<td align="left" nowrap><span class="text13">Username</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $zaznam[userid]; ?></span></td>
</tr>
<?PHP
if ($s[SCRdetail]=='e_detail.php')  // jenom pro ne-sponsora
{ echo '<tr>
  <td align="left" nowrap><span class="text13">URL</span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="100" name="url" value="'.$zaznam[siteurl].'"></td>
  </tr>';
}
?>
<tr>
<td align="left" nowrap><span class="text13">Name</span></td>
<td align="left" nowrap><INPUT class="field1" size="60" maxlength="100" name="name" value="<?PHP echo $zaznam[name]; ?>"></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Address</span></td>
<td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="address" value="<?PHP echo $zaznam[address]; ?>"></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Email</span></td>
<td align="left" nowrap><INPUT class="field1" size="60" maxlength="100" name="email" value="<?PHP echo $zaznam[email]; ?>"></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Password</span></td>
<td align="left" nowrap><INPUT class="field1" size="15" maxlength="15" name="userpass" value="<?PHP echo $zaznam[userpass]; ?>"></td>
</tr>
<tr>
<td align="center" colspan=2><input type="submit" name="co" value="&nbsp;Save&nbsp;" class="button1"></td>
</tr></form></table>
<?PHP
include('./_footer.txt');
exit;
}

#############################################################################

function user_edited($a) {
global $s;
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
if ( (!$a[name]) OR (!$a[email]) OR (!$a[userpass]) ) 
{ $s[info] = iot('Missing field(s). Please try again.'); user_edit($a[number]); exit; }
$a = replace_array_text($a);
dq("update $s[pr]members set siteurl='$a[url]',name='$a[name]',address='$a[address]',email='$a[email]',userpass='$a[userpass]' where number = '$a[number]'",1);
$s[info] = iot('User details have been updated');
user_details($a[number]);
}

#############################################################################
#############################################################################
#############################################################################

function ad_edit_table($link,$stats,$size) {
// stejne pro normal a sponsor - jenom categories jen pro normal (nemusi se menit nic, je tam podminka)
// $link, $stats - radek z tabulky link a stats (assoc pole), $size - 1, 2 nebo 3
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
global $s;
$ad = get_three_ads($link[number],$size,$stats,$link,1);
if ($s["usecats$size"])
{ $q = dq("select catid,catname from $s[pr]categories where size = '$size'",1);
  while ($x=mysql_fetch_array($q)) $c[$x[0]]=$x[1];
  $category = '<SELECT class="field1" name="category">';
  $categories = '<SELECT class="field1" name="categories[]" size=5 multiple>';
  if ($stats[c0]) $selected = ' selected'; else $selected = '';
  $categories .= '<option value="a"'.$selected.'>All</option>';

  foreach($c as $k => $v)
  { if ($link[cat]==$k) $selected=' selected'; else $selected='';
    $category .= "<option value=\"$k\"$selected>$v</option>\n";
	if ($stats[c0]) $selected='';
	elseif (($stats[c1]==$k) OR ($stats[c2]==$k) OR ($stats[c3]==$k) OR ($stats[c4]==$k) OR ($stats[c5]==$k)) $selected=' selected';
	else $selected='';
	$categories .= "<option value=\"$k\"$selected>$v</option>\n";
  }
  $categories .= '</select>'; $category .= '</select>';
}
else $category = $categories = '<span class="text13">NA</span>';
?>
<table border="0" cellspacing="10" cellpadding="0" class="table1" width="580"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="4">
<form METHOD="post" action="<?PHP echo $s[SCRdetail] ?>">
<input type="hidden" name="number" value="<?PHP echo $link[number]; ?>">
<input type="hidden" name="size" value="<?PHP echo $size; ?>">
<input type="hidden" name="action" value="ad_edited">
<tr>
<td align="left" nowrap><span class="text13">Advantage</span></td>
<td align="left" nowrap><?PHP echo $stats[advantage]; ?></td>
</tr>
<tr><td align="center" colspan="2"><span class="text10">If an account has an advantage, links of this account have priority - other links are not displayed until this account has any credits available.<br>Higher number = higher priority</span></td></tr>
<tr>
<td align="left" nowrap><span class="text13">Category</span></td>
<td align="left" nowrap><?PHP echo $category; ?></td>
</tr>
<?PHP
if ($s[SCRdetail]=='e_detail.php')
{ echo '<tr>
  <td align="left" nowrap><span class="text13">Categories displayed<br>on pages of this account<br>(Select All or<br>max of 5 categories)</span></td>
  <td align="left" nowrap>'.$categories.'</td></tr>';
}
for ($x=1;$x<=3;$x++)
{ ?>
  <tr>
  <td align="center" nowrap colspan="2"><span class="text13blue"><b>Ad #<?PHP echo $x ?></b></span></td>
  <tr>
  <td align="center" nowrap colspan="2"><span class="text13"><?PHP echo $ad[$x]; ?></span></td>
  </tr>
  </tr>
  <tr>
  <td align="left" nowrap colspan="2"><span class="text13"><input type="radio" name="ad_kind_<?PHP echo $x ?>" value="picture"<?PHP if ($link["ad_kind_$x"]=='picture') echo ' checked' ?>> Use this ad - picture</span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">URL</span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="url<?PHP echo $x ?>" value="<?PHP echo $link["url$x"] ?>"></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Banner</span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="banner<?PHP echo $x ?>" value="<?PHP echo $link["banner$x"] ?>"></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Alt tag</span></td>
  <td align="left" nowrap><INPUT class="field1" size="60" maxlength="255" name="alt<?PHP echo $x ?>" value="<?PHP echo $link["alt$x"] ?>"></td>
  </tr>
  <tr>
  <td align="left" nowrap colspan="2"><span class="text13"><input type="radio" name="ad_kind_<?PHP echo $x ?>" value="raw_html"<?PHP if ($link["ad_kind_$x"]=='raw_html') echo ' checked' ?>> Use this ad - raw HTML code</span></td>
  </tr>
  <tr>
  <td align="left" valign="top" colspan="2"><textarea class="field1" name="raw<?PHP echo $x ?>" rows="15" cols="88"><?PHP echo $link["raw$x"] ?></textarea></td>
  </tr>
<?PHP
}
?>
<tr>
<td align="center" nowrap colspan="2">
<input type="submit" name="co" value="Save" class="button1"></td>
</td></tr></form></table></td></tr></table>
<br>
<?PHP
}

###########################################################################

function ad_edited($in) {
global $s;
// stejne pro normal a sponsor
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');

if (!$in[categories]) $in[categories][0]='a';
if (!$s["usecats$in[size]"]) $in[category] = $c0 = 1;

for ($x=1;$x<=3;$x++) $raw[$x] = replace_once_html($in["raw$x"]);
$in = replace_array_text($in);

dq("update $s[pr]link$in[size] set cat='$in[category]',
  url1='$in[url1]',banner1='$in[banner1]',alt1='$in[alt1]',raw1='$raw[1]',ad_kind_1='$in[ad_kind_1]',
  url2='$in[url2]',banner2='$in[banner2]',alt2='$in[alt2]',raw2='$raw[2]',ad_kind_2='$in[ad_kind_2]',
  url3='$in[url3]',banner3='$in[banner3]',alt3='$in[alt3]',raw3='$raw[3]',ad_kind_3='$in[ad_kind_3]' 
  where number = '$in[number]'",1);
for ($x=1;$x<=3;$x++)
{ $y = get_complete_html($in[size],$in[number],$x,$in["banner$x"],$in["url$x"],$in["alt$x"],$raw[$x],$in["ad_kind_$x"]);
  $ad_a[$x] = $y[0]; $ad_b[$x] = $y[1]; $ok = $ok + $y[2];
}
if ($in[categories])
{ if ($in[categories][0]=='a') $c0 = 1;
  else { $c0 = 0; $c1=$in[categories][0]; $c2=$in[categories][1]; $c3=$in[categories][2]; $c4=$in[categories][3]; $c5=$in[categories][4]; }
}
if (!$ok) $x = ',approved = 0'; else $x = '';
dq("update $s[pr]stats$in[size] set 
linka1='$ad_a[1]', linka2='$ad_a[2]',linka3='$ad_a[3]',linkb1='$ad_b[1]',linkb2='$ad_b[2]',linkb3='$ad_b[3]',
category='$in[category]',c0='$c0',c1='$c1',c2='$c2',c3='$c3',c4='$c4',c5='$c5',weight = '$in[weight]' $x 
where number = '$in[number]'",1);
$s[info] = iot('Data has been saved');
account_edit($in);
exit;
}

#############################################################################
#############################################################################
#############################################################################

function account_enable($a) {
global $s;
// stejne pro B a T
// stejne pro normal a sponsor
if ($s[sponsor]) check_session ('s_accounts');
else check_session ('accounts');
$q = dq("select linka1,linka2,linka3 from $s[pr]stats$a[size] where number = '$a[number]'",1);
$x = mysql_fetch_row($q);
if ( (!$x[0]) AND (!$x[1]) AND (!$x[2]) )
{ $s[info] = iot('Account '.$a[size].' has no one ad configured. It can\'t be enabled.');
  user_details($a[number]); exit; }
dq("update $s[pr]stats$a[size] set approved = 1 where number = '$a[number]'",1);
$s[info] = iot('Account '.$a[size].' has been enabled');
user_details($a[number]);
}

###########################################################################

function account_disable($a) {
global $s;
// stejne pro B a T
// stejne pro normal a sponsor
if ($s[sponsor]) check_session ('s_accounts');
else check_session ('accounts');
dq("update $s[pr]stats$a[size] set approved = 0 where number = '$a[number]'",1);
$s[info] = iot('Account '.$a[size].' has been disabled');
user_details($a[number]);
}

###########################################################################
###########################################################################
###########################################################################

function user_accept($a) {
global $s;
// stejne pro B a T
// stejne pro normal a sponsor
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
$q = dq("select * from $s[pr]members where number = '$a[number]'",0);
$x = mysql_fetch_assoc($q);
if ($s[SCRdetail]=='e_detail.php')
{ $x[memberfile] = "$s[phpurl]/member.php"; mail_from_template('email_u_join.txt',$x); }
else
{ $x[memberfile] = "$s[phpurl]/s_user.php"; mail_from_template('email_s_join.txt',$x); }
dq("update $s[pr]members set accepted = 1 where number = '$a[number]'",1);
for ($x=1;$x<=3;$x++) dq("update $s[pr]stats$x set accept = 1 where number = '$a[number]'",1);
$s[info] = iot('User has been accepted');
user_details($a[number]);
exit;
}

##################################################################################
##################################################################################
##################################################################################

function get_three_ads($number,$size,$stats,$link,$return) {
global $s;
// number - cislo clena, size - 1, 2 nebo 3, 
$data[number] = $number; $data[size] = $size; $data[totalheight] = $s["totalh$size"]; $data[totalwidth] = $s["totalw$size"];
for ($x=1;$x<=3;$x++)
{ $data[link] = $x;
  if (!$stats["linka$x"]) $ad[$x] = '<b>#'.$x.'</b><br>Ad not set';
  else
  { if ($return) $ad[$x] = show_current_complete_ad($number,$size,$x);
    else $ad[$x] = '<b>#'.$x.'</b><br>URL: <a target="ooooo" href="'.$link["url$x"].'">'.$link["url$x"].'</a><br>'.show_current_complete_ad($number,$size,$x);
  }
}
if ($return) return $ad;
echo '<span class="text13blue"><b>Ads</b></span><br><br><span class="text13">'.$ad[1].'<br>'.$ad[2].'<br>'.$ad[3].'<br></span>';
}

###########################################################################

function list_category_categories($size,$stats) {
global $s;
// vrati kategorii clena a kategorie zobrazovane na jeho stranach
if ($s["usecats$size"])
{ $q = dq("select catname,catid from $s[pr]categories where size = '$size'",0);
  while ($x = mysql_fetch_row($q)) 
  { if ($x[1] == $stats[category]) $category = $x[0];
    for ($y=1;$y<=5;$y++) { if ($x[1]==$stats["c$y"]) $categories .= $x[0].'<br>'; }
  }
  if ($stats[c0]) $categories = 'All categories<br>';
}
return array($category,$categories);
}

###########################################################################
###########################################################################
###########################################################################

function statistic_table_month($number,$size) {
global $s;
$a = '<td align="center" width="20"><span class="text10">'; $b = '</span></td>'."\n";

$tr[m] = '<td align="left" nowrap><span class="text10">Month'.$b;
$tr[i_m] = '<td align="left" nowrap><span class="text10">Impr. sent by user'.$b;
$tr[cl_m] = '<td align="left" nowrap><span class="text10">Clicks sent by user'.$b;
$tr[r_m] = '<td align="left" nowrap><span class="text10">Ratio'.$b;
$tr[i_w] = '<td align="left" nowrap><span class="text10">Impr. received by user'.$b;
$tr[cl_w] = '<td align="left" nowrap><span class="text10">Clicks received by user'.$b;
$tr[r_w] = '<td align="left" nowrap><span class="text10">Ratio'.$b;

$q = dq("select * from $s[pr]months$size where number = '$number' AND y = '$s[year]'",0);
while ($month = mysql_fetch_assoc($q))
{ $n = $month[m]; $x[$n][m] .= $a.$month[m].$b;
  $x[$n][i_m] .= $a.$month[i_m].$b; $x[$n][cl_m] .= $a.$month[cl_m].$b; $x[$n][r_m] .= $a.$month[r_m].$b;
  $x[$n][i_w] .= $a.$month[i_w].$b; $x[$n][cl_w] .= $a.$month[cl_w].$b; $x[$n][r_w] .= $a.$month[r_w].$b;
}
for ($n=1;$n<=12;$n++)
{ if (!$x[$n])
  { $x[$n][m] .= $a.$n.$b;
    $x[$n][i_m] .= $a.'0'.$b; $x[$n][cl_m] .= $a.'0'.$b; $x[$n][r_m] .= $a.'0'.$b;
    $x[$n][i_w] .= $a.'0'.$b; $x[$n][cl_w] .= $a.'0'.$b; $x[$n][r_w] .= $a.'0'.$b;
  }
  $tr[m] .= $x[$n][m];
  $tr[i_m] .= $x[$n][i_m]; $tr[cl_m] .= $x[$n][cl_m]; $tr[r_m] .= $x[$n][r_m]; 
  $tr[i_w] .= $x[$n][i_w]; $tr[cl_w] .= $x[$n][cl_w]; $tr[r_w] .= $x[$n][r_w]; 
}
if ($s[sponsor]) unset($tr[i_m],$tr[cl_m],$tr[r_m]);
$n = 0;
foreach ($tr as $k => $v)
{ $n++;
  if ($n%2) $tr[$k] = '<tr bgcolor="#FFFBEE">'.$v; else $tr[$k] = '<tr>'.$v;
}
$tr[m] .= '</tr>';
$tr[i_m] .= '</tr>'; $tr[cl_m] .= '</tr>'; $tr[r_m] .= '</tr>';
$tr[i_w] .= '</tr>'; $tr[cl_w] .= '</tr>'; $tr[r_w] .= '</tr>';
return iot('Months of Year '.$s[year]).'<table border="0" width="100%" cellspacing="0" cellpadding="2">'.$tr[m].$tr[i_m].$tr[cl_m].$tr[r_m].$tr[i_w].$tr[cl_w].$tr[r_w].'</table><br>';
}

#############################################################################
#############################################################################
#############################################################################

function aff_credits_confer($data) {
global $s;
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
$q = dq("select affiliate from $s[pr]members where number = '$data[number]'",1);
$r = mysql_fetch_row($q);
if (strstr($r[0],'___'))
{ $r[0] = str_replace('___','',$r[0]);
  dq("update $s[pr]members set affiliate='$r[0]' where number = '$data[number]'",1);
  dq("update $s[pr]stats$s[whereref] SET i_free=i_free+'$s[forref]',i_refer=i_refer+'$s[forref]',i_nu=i_nu+'$s[forref]' WHERE userid = '$r[0]'",1);
  $s[info] = iot('User '.$r[0].' got free credits');
}
user_details($data[number]);
}

#################################################################################

function aff_credits_reject($data) {
global $s;
if ($s[sponsor]) check_session ('sponsors');
else check_session ('users');
$q = dq("select affiliate from $s[pr]members where number = '$data[number]'",1);
$r = mysql_fetch_row($q);
$r[0] = str_replace('___','',$r[0]);
dq("update $s[pr]members set affiliate='' where number = '$data[number]'",1);
user_details($data[number]);
}

###########################################################################
###########################################################################
###########################################################################

function free_credits_info($user) {
global $s;
if (!strstr($user[affiliate],'___')) return '';
$user[affiliate] = str_replace('___','',$user[affiliate]);
$a = "<br><br><span class=\"text13\">This user has been referred by user $user[affiliate]. The referring user should get $s[forref] free impressions.<br>Confer the free credits?
<br><a href=\"$s[SCRdetail]?action=aff_credits_confer&number=$user[number]&affiliate=$user[affiliate]\">Yes, confer the free credits now</a>
&nbsp;&nbsp;&nbsp;<a href=\"$s[SCRdetail]?action=aff_credits_reject&number=$user[number]\">No, forget that and delete the request</a>";
return $a;
}

###########################################################################

function accept_user_info($number) {
global $s;
$q = dq("select accept from $s[pr]stats1 where number = '$number'",1);
$r = mysql_fetch_row($q);
if (!$r[0]) 
{ $a = '<form action="'.$s[SCRdetail].'" method="post">This user is not accepted<br>
  <input type="hidden" name="action" value="user_accept">
  <input type="hidden" name="number" value="'.$number.'">
  <input type="submit" name="co" value="Accept now" class="button1"></form>';
}
return $a;
}

###########################################################################

function enable_disable_info_button($number,$size,$stats) {
global $s;
// pouzite v user_details
if ($stats[approved]) 
{ $a[approved] = '<font color="green">Enabled by admin</font>&nbsp;&nbsp;&nbsp;';
  $a[button] = '<input type="hidden" name="action" value="account_disable">
                <input type="submit" name="co" value="Disable this account" class="button1">';
}
else
{ $a[approved] = '<font color="red">Disabled by admin</font>&nbsp;&nbsp;&nbsp;';
  $a[button] = '<input type="hidden" name="action" value="account_enable">
                <input type="submit" name="co" value="Enable this account" class="button1">';
}
$a[button] = '<form METHOD="post" action="'.$s[SCRdetail].'"><td align="center">
<input type="hidden" name="number" value="'.$number.'"><input type="hidden" name="size" value="'.$size.'">'
.$a[button].'</td></form>';
if ($stats[enable]) $a[enabled] = '<font color="green">Enabled by user</font>'; else $a[enabled] = '<font color="red">Disabled by user</font>';
return $a;
}

###########################################################################
###########################################################################
###########################################################################

?>