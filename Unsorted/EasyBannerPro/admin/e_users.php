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


$s[SCRusers] = 'e_users.php'; $s[SCRdetail] = 'e_detail.php'; $s[sponsor] = 0; $s[sponsored] = '';
include("./common.php");
include("$s[phppath]/admin/f_users.php");

switch ($HTTP_GET_VARS[action]) {
case 'accounts_stats_home'	: accounts_stats_home();
case 'ads_show_form'		: ads_show_form();
case 'search_form'			: search_form();
case 'cheaters_main'		: cheaters_main();
case 'credits_add_all_form'	: credits_add_all_form();
case 'users_inactive_find'	: users_inactive_find($HTTP_GET_VARS);
// one size funkce se pouzivaji jen kdyz se ma rozdelit na vic stranek
case 'accounts_show'		: if ( ($HTTP_GET_VARS[perpage]) AND ($HTTP_GET_VARS[size]) ) accounts_show_one_size($HTTP_GET_VARS);
						 	  else accounts_show_all_sizes($HTTP_GET_VARS);
case 'month_show'			: if ($HTTP_GET_VARS[size]) month_show_one_size($HTTP_GET_VARS);
							  else month_show_all_sizes('');
case 'ads_show'				: if ($HTTP_GET_VARS[size]) ads_show_one_size($HTTP_GET_VARS);
				  			  else ads_show_all_sizes($HTTP_GET_VARS);
case 'impressions_added_all': impressions_added_all($HTTP_GET_VARS);
case 'impressions_added_wait_all': impressions_added_wait_all($HTTP_GET_VARS);
case 'impressions_added_spec_all': impressions_added_spec_all($HTTP_GET_VARS);
case 'lowratio'				: month_show_all_sizes($HTTP_GET_VARS);
case 'too_ip'				: too_ip($HTTP_GET_VARS[number]);
}

switch ($HTTP_POST_VARS[action]) {
case 'search_now'			: search_now($HTTP_POST_VARS);
}

#########################################################################
#########################################################################
#########################################################################

function accounts_stats_home() {
global $s;
check_session ('accounts');
include('./_head.txt');
echo $s[info];
echo iot('Accounts & Statistic');
accounts_show_form();
current_month_statistic_form();
if ($s[SCRusers]=='s_users.php') { echo $s[info_limit]; include('./_footer.txt'); exit; }
?>
<table border="0" width="500" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" width="400" cellspacing="1" cellpadding="2">
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="accounts_show">
<input type="hidden" name="what" value="noactive">
<tr><td align="center" nowrap><span class="text13blue"><b>Inactive accounts</b></span></td></tr>
<tr><td align="center" nowrap><span class="text13">Show accounts inactive longer than <input name="wait" size="5" maxlength="10" class="field1"> days</span>
<br><span class="text13">Ad size: &nbsp;&nbsp; 
<input type="radio" name="size" value="0" checked>All sizes &nbsp; 
<input type="radio" name="size" value="1">1 &nbsp; 
<input type="radio" name="size" value="2">2 &nbsp; <input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="center" nowrap><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form>
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="users_inactive_find">
<tr><td align="center" nowrap><br><span class="text13">
<select name="what" class="field1"><option value="find">Show</option><option value="delete">Delete</option></select>
 users who are inactive longer than <input name="days" size="5" maxlength="10" class="field1"> days</span></td></tr>
<tr><td align="center" nowrap><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table><br>
<?PHP
echo $s[info_limit]; include('./_footer.txt'); exit;
}

##################################################################################
##################################################################################
##################################################################################

function accounts_show_one_size($a) {
global $s;
check_session ('accounts');
if (!$a[wait]) $a[wait] = 14;
$w = get_accounts_where($a);

$q = dq("select count(*) from $s[pr]stats$a[size] where sponsor='$s[sponsor]' $w",1);
$pocet = mysql_fetch_row($q);
if (!$a[from]) $a[from] = 0; else $a[from] = $a[from] - 1;

include('./_head.txt');
echo accounts_rozcesti($pocet[0],$a);
accounts_table_head($a[size]);
$q = dq("select * from $s[pr]stats$a[size] where sponsor='$s[sponsor]' $w order by userid limit $a[from],$a[perpage]",1);
while ($zaznam = mysql_fetch_array($q))
{ if ( ($zaznam[last] == 0) AND ($zaznam[joined] > ($s[cas]-($a[wait]*86400))) AND ($a[what]=='noactive') ) continue;
  $p = show_account_row($zaznam);
  $i_m[$x]=$i_m[$x]+$p[0]; $i_w[$x]=$i_w[$x]+$p[1]; $i_rest[$x]=$i_rest[$x]+$p[2];
  if ($p[3]) $last = datum (0,$p[3]); else $last='Never yet';
}
?>
<tr>
<TD colspan=5 align="left"><span class="text13"><b>TOTAL</b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $i_m[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $i_w[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $i_rest[$x]; ?></b></span></TD>
<TD align="center" nowrap><span class="text13"><b><?PHP echo $last; ?></b></span></TD>
<TD>&nbsp;</TD>
</TR></table><br>
<?PHP
show_categories_table($a[size]);
include('./_footer.txt');
exit;
}

##############################################################################

function accounts_show_all_sizes($a) {
global $s;
check_session ('accounts');
if (!$a[wait]) $a[wait] = 14;
if (!$a[size]) { $x1=1; $x2=3; } else $x1=$x2=$a[size];
$w = get_accounts_where($a);
if ($a[ready_to_check]) $w = ' AND (accept != 1 OR approved != 1) AND (linka1!="" OR linka2!="" OR linka3!="") ';

include('./_head.txt');
if ($a[what]=='noactive') echo iot('Accounts Inactive Longer Than '.$a[wait].' Days');
for ($x=$x1;$x<=$x2;$x++)
{ $width = $s["w$x"]; $height = $s["h$x"];

  $q = dq("select * from $s[pr]stats$x where sponsor='$s[sponsor]' $w order by userid",1);
  accounts_table_head($x);
  while ($zaznam = mysql_fetch_assoc($q))
  { $p = show_account_row($zaznam);
    $i_m[$x]=$i_m[$x]+$p[0]; $i_w[$x]=$i_w[$x]+$p[1]; $i_rest[$x]=$i_rest[$x]+$p[2];
    if ($p[3] > $totallast) $totallast = $p[3];
  }
  ?>
  <tr>
  <TD colspan=5 align="left"><span class="text13"><b>TOTAL</b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $i_m[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $i_w[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $i_rest[$x]; ?></b></span></TD>
  <TD colspan=2>&nbsp;</TD>
  </TR>
  </table><br>
  <?PHP
  show_categories_table($x);
  $i_m[0]=$i_m[0]+$i_m[$x]; $i_w[0]=$i_w[0]+$i_w[$x]; $i_rest[0]=$i_rest[0]+$i_rest[$x];
}
if ($x1==$x2) { include('./_footer.txt'); exit; }

if ($totallast) $last = datum (1,$totallast); else $last='Never yet';
?>
<table border="0" width="98%" cellspacing="1" cellpadding="2" class="table1">
<tr>
<TD align="center" valign="top" nowrap colspan="4"><span class="text13blue"><b>Overall Statistic</b></span></TD>
</TR>
<tr>
<TD align="center" valign="top" nowrap><span class="text13">Impressions sent<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impressions received<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not used<br>impressions</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Last impression<br>by any user</span></TD>
</TR>
<tr>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $i_m[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $i_w[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $i_rest[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $last; ?></b></span></TD>
</TR>
</table>
<?PHP
include('./_footer.txt');
exit;
}

##################################################################################

function accounts_table_head($size) {
global $s;
$width = $s["w$size"]; $height = $s["h$size"];
?>
<table border="0" width="98%" cellspacing="1" cellpadding="2" class="table1">
<tr><td colspan="11" align="center"><span class="text13blue"><b>Ad size <?PHP echo "$size (width $width px, height $height px)"; ?></b></span></td></tr>
<tr>
<TD align="center" valign="top"><span class="text13">User<br>ID</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Accept.</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Enabl.<br>by<br>admin</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Enabl.<br>by<br>user</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Ad<br>avail.</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impr.<br>sent<br>by user</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impr.<br>received<br>by user</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not<br>used<br>impr.</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Last<br>impression<br>sent</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Advant.</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Cat.</span></TD>
</TR>
<?PHP
}

#############################################################################

function show_account_row($data) {
global $s;
if ($data[last]) $last = datum (0,$data[last]); else $last='Never yet';
if ($data[last] > $totallast) $totallast = $data[last];
$data[i_nu] = round($data[i_nu]);
if ($data[enable]) $enabled='<font color="green">Yes</font>'; else $enabled='<font color="red">No</font>';
if ($data[accept]) $accepted='<font color="green">Yes</font>'; else $accepted='<font color="red">No</font>';
if ($data[approved]) $approved='<font color="green">Yes</font>'; else $approved='<font color="red">No</font>';
if ( ($data[linka1]) OR ($data[linka2]) OR ($data[linka3]) ) $ad='<font color="green">Yes</font>'; else $ad='<font color="red">No</font>';
echo '<TR bgcolor="#FFFBE0" onMouseOut="style.backgroundColor=\'#FFFBE0\';status=\'\'" onMouseOver="style.backgroundColor=\'#FFF7C5\'">';
echo "<TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$data[number]\">$data[userid]</a></TD>
<TD align=\"center\"><span class=\"text13\">$accepted</span></TD>
<TD align=\"center\"><span class=\"text13\">$approved</span></TD>
<TD align=\"center\"><span class=\"text13\">$enabled</span></TD>
<TD align=\"center\"><span class=\"text13\">$ad</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_nu]</span></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$last</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[weight]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[category]&nbsp;</span></TD>
</TR>\n\n";
return array($data[i_m],$data[i_w],$data[i_nu],$totallast);
}

#############################################################################
#############################################################################
#############################################################################

function month_show_all_sizes($data) {
global $s;
check_session ('accounts');
if ($data[action]=='lowratio') $info='Accounts With Click Ratio Lower Than '.$data[lowratio].' % This Month
<br><span class="text13">Only accounts with at least 100 impressions sent this month are displayed';
else 
{ $info='Monthly Statistic<br><br>
  <span class="text13">It shows only accounts with at least one impression or click sent or received in the current month.</span>'; }
include('./_head.txt');
echo iot($info);
for ($x=1;$x<=3;$x++)
{ $q = dq("select * from $s[pr]months$x where sponsor='$s[sponsor]' AND m = '$s[month]' AND y = '$s[year]' order by userid",1);
  month_table_head($x);
  while ($zaznam = mysql_fetch_assoc($q))
  { if ( ($data[action]=='lowratio') AND (($zaznam[r_m]>=$data[lowratio]) OR ($zaznam[i_m]<100)) ) continue;
    $p = show_month_row($zaznam);
    $x_i_m[$x]=$x_i_m[$x]+$p[0]; $x_cl_m[$x]=$x_cl_m[$x]+$p[1]; $x_i_w[$x]=$x_i_w[$x]+$p[2]; $x_cl_w[$x]=$x_cl_w[$x]+$p[3];
  }
  ?>
  <tr><TD align="center"><span class="text13"><b>TOTAL</b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_i_m[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_cl_m[$x]; ?></b></span></TD>
  <TD>&nbsp;</TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_i_w[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_cl_w[$x]; ?></b></span></TD>
  <TD>&nbsp;</TD></TR></table></td></tr></table><br>
  <?PHP
  $x_i_m[0]=$x_i_m[0]+$x_i_m[$x]; $x_cl_m[0]=$x_cl_m[0]+$x_cl_m[$x]; $x_i_w[0]=$x_i_w[0]+$x_i_w[$x]; $x_cl_w[0]=$x_cl_w[0]+$x_cl_w[$x];
}
if ($data[action]=='lowratio') { include('./_footer.txt'); exit; }
?>
<table border="0" cellspacing="10" cellpadding="2" class="table1" width="600"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="2">
<tr><TD align="center" colspan="4"><span class="text13blue"><b>Overall Statistic</b></span></TD></TR>
<tr>
<TD align="center" valign="top" nowrap><span class="text13">Impressions sent<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks sent<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impressions received<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks received<br>by all users</span></TD>
</TR>
<tr>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_i_m[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_cl_m[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_i_w[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_cl_w[0]; ?></b></span></TD>
</TR></TABLE></td></tr></table>
<?PHP
include('./_footer.txt');
exit;
}

#############################################################################

function month_show_one_size($a) {
global $s;
check_session ('accounts');
include('./_head.txt');
echo iot('Monthly Statistic');
echo '<span class="text13">It shows only accounts with at least one impression or click sent or received in the current month.</span><br><br>';

$q = dq("select count(*) from $s[pr]months$a[size] where sponsor='$s[sponsor]'",1);
$pocet = mysql_fetch_row($q);
if (!$a[from]) $a[from] = 0; else $a[from] = $a[from] - 1;

echo month_rozcesti($pocet[0],$a);
if (!$a[perpage]) $a[perpage] = $pocet[0];
month_table_head($a[size]);
$q = dq("select * from $s[pr]months$a[size] where sponsor='$s[sponsor]' AND m = '$s[month]' AND y = '$s[year]' order by userid limit $a[from],$a[perpage]",0);
while ($zaznam = mysql_fetch_assoc($q))
{ $p = show_month_row($zaznam);
  $x_i_m[$x]=$x_i_m[$x]+$p[0]; $x_cl_m[$x]=$x_cl_m[$x]+$p[1]; $x_i_w[$x]=$x_i_w[$x]+$p[2]; $x_cl_w[$x]=$x_cl_w[$x]+$p[3];
}
?>
<tr><TD align="center"><span class="text13"><b>TOTAL</b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $x_i_m[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $x_cl_m[$x]; ?></b></span></TD>
<TD>&nbsp;</TD>
<TD align="center"><span class="text13"><b><?PHP echo $x_i_w[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $x_cl_w[$x]; ?></b></span></TD>
<TD>&nbsp;</TD></TR></table></td></tr></table><br>
<?PHP
include('./_footer.txt');
exit;
}

#############################################################################

function month_table_head($size) {
global $s;
$width = $s["w$size"]; $height = $s["h$size"];
?>
<table border="0" cellspacing="10" cellpadding="2" class="table1" width="600"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="2" width="98%"><tr>
<td align="center" colspan="7"><span class="text13blue"><b>Ad Size <?PHP echo "$size (width $width, height $height)"; ?></b></span></TD>
</tr><tr>
<TD align="center" valign="top"><span class="text13">UserID</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impressions<br>sent by user</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks<br>sent by user</span></TD>
<TD align="center" valign="top"><span class="text13">Ratio<br>(%)</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impressions<br>received</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks<br>received</span></TD>
<TD align="center" valign="top"><span class="text13">Ratio<br>(%)</span></TD></TR>
<?PHP
}

#############################################################################

function show_month_row($data) {
global $s;
echo '<TR bgcolor="#FFFBE0" onMouseOut="style.backgroundColor=\'#FFFBE0\';status=\'\'" onMouseOver="style.backgroundColor=\'#FFF7C5\'">';
echo "<TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$data[number]\">$data[userid]</a></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[cl_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[r_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[cl_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[r_w]</span></TD>
</TR>\n\n";
return array($data[i_m],$data[cl_m],$data[i_w],$data[cl_w]);
}

###########################################################################
###########################################################################
###########################################################################

function search_form() {
global $s;
include('./_head.txt');
echo $s[info];
echo iot('Search');
?>
<table border="0" width="500" cellspacing="1" cellpadding="2" class="table1">
<form method="POST" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="search_now">
<tr><td align="center" nowrap><br><span class="text13">
Word or phrase <input class="field1" name="phrase" size=30 maxlength=30><br><br>
In <select class="field1" name="where"><option value="userid">Username</option><option value="name">Name</option><option value="email">Email address</option><option value="siteurl">URL</option><option value="linkurl">Link URL</option></select>
<br><br>
<input type="submit" name="A1" value="Submit" class="button1"><br><br>
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

#########################################################################

function search_now($a) {
global $s;
check_session ('accounts');
if (!$a[phrase])
{ $s[info] = iot('Search phrase is missing. Please try again.'); search_form(); }
$a = replace_array_text($a);
if ( ($a[where]=='email') OR ($a[where]=='name') OR ($a[where]=='userid') OR ($a[where]=='siteurl') )
{ $q = dq("select * from $s[pr]members where $a[where] like '%$a[phrase]%' and sponsor='$s[sponsor]' order by userid",1);
  $num_rows = mysql_num_rows($q);
  if (!$num_rows)
  { $s[info] = iot('Phrase "'.$a[phrase].'" not found. Please try again.'); search_form(); }
  include('./_head.txt');
  echo iot('Users found: '.$num_rows);
  users_table_head();
  while ($result=mysql_fetch_array($q)) show_user_row($result);
  echo '</table></td></tr></table>';
  include('./_footer.txt'); exit;
}
if ($a[where]=='linkurl')
{ include('./_head.txt');
  for ($x=1;$x<=3;$x++)
  { $query = dq("select userid,number,url1,url2,url3 from $s[pr]link$x where url1 like '%$a[phrase]%' OR url2 like '%$a[phrase]%' OR url3 like '%$a[phrase]%' and sponsor='$s[sponsor]'",1);
    $num_rows = mysql_num_rows($query);
    if (!$num_rows) continue;
    $nasel = 1;
    echo "<br><span class=\"text13blue\"><b>Phrase '$a[phrase]' found in $num_rows accounts of size $x</b></span><br>";
    echo '<table border="0" width="500" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
    <table border="0" cellspacing="8" cellpadding="2"><TR>
    <TD align="center" valign="top" nowrap><span class="text13">User</span></TD>
    <TD align="center" valign="top"><span class="text13">URL</span></TD></TR>';
    while ($result=mysql_fetch_array($query))
    { echo "<TR>
      <TD align=\"center\" valign=\"top\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$result[number]\"><b>$result[userid]</b></a></TD>
      <TD align=\"left\" nowrap><span class=\"text13\">";
      if (eregi($a[phrase], $result[url1])) echo "URL 1: &nbsp;<a target\"_blank\" href=\"$result[url1]\">$result[url1]</a><br>\n";
      if (eregi($a[phrase], $result[url2])) echo "URL 2: &nbsp;<a target\"_blank\" href=\"$result[url2]\">$result[url2]</a><br>\n";
      if (eregi($a[phrase], $result[url3])) echo "URL 3: &nbsp;<a target\"_blank\" href=\"$result[url3]\">$result[url3]</a><br>\n";
      echo "</span></TD></TR>"; }
  echo "</table></td></tr></table>";
  }
}
if ($a[where]=='links')
{ include('./_head.txt');
  for ($x=1;$x<=3;$x++)
  { $query = dq("select userid,number,title1,title2,title3,text1,text2,text3 from $s[pr]link$x where title1 like '%$a[phrase]%' OR title2 like '%$a[phrase]%' OR title3 like '%$a[phrase]%' OR text1 like '%$a[phrase]%' OR text2 like '%$a[phrase]%' OR text3 like '%$a[phrase]%'",1);
    $num_rows = mysql_num_rows($query);
    if (!$num_rows) continue;
    $nasel = 1;
    echo "<br><span class=\"text13blue\"><b>Phrase '$a[phrase]' found in $num_rows accounts of size $x</b></span><br><br>";
    echo '<table border="0" width="550" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
    <table border="0" cellspacing="5" cellpadding="2">';
    while ($result=mysql_fetch_array($query))
    { echo "<TR>
      <TD align=\"center\" valign=\"top\"><span class=\"text13\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$result[number]\"><b>$result[userid]</b></a></span></TD>
      <TD align=\"left\"><span class=\"text13\">";
      if (eregi($a[phrase], $result[title1])) echo "<span class=\"text13blue\">Title 1:</span> $result[title1]<br>\n";
      if (eregi($a[phrase], $result[title2])) echo "<span class=\"text13blue\">Title 2:</span> $result[title2]<br>\n";
      if (eregi($a[phrase], $result[title3])) echo "<span class=\"text13blue\">Title 3:</span> $result[title3]<br>\n";
      if (eregi($a[phrase], $result[text1])) echo "<span class=\"text13blue\">Text 1:</span> $result[text1]<br>\n";
      if (eregi($a[phrase], $result[text2])) echo "<span class=\"text13blue\">Text 2:</span> $result[text2]<br>\n";
      if (eregi($a[phrase], $result[text3])) echo "<span class=\"text13blue\">Text 3:</span> $result[text3]<br>\n";
      echo "</span></TD></TR>"; }
  echo "</table></td></tr></table><br>";
  }
}
if ($nasel) { include('./_footer.txt'); exit; }
$s[info] = iot('Phrase "'.$a[phrase].'" not found. Please try again.');
search_form();
exit;
}

function show_user_row($data) {
global $s;
$joined = datum(0,$data[date]);
echo "<TR>
<TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$data[number]\">$data[userid]</a></TD>
<TD align=\"center\" nowrap><a target=\"_blank\" href=\"$data[siteurl]\">$data[siteurl]</a></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$data[name]</span></TD>
<TD align=\"center\"><a title=\"Click to view/edit details\" href=\"mailto:$data[email]\">$data[email]</a></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$joined</span></TD>
</TR>";
}

function users_table_head() {
?>
<table border="0" width="550" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="8" cellpadding="0"><TR>
<TD align="center" valign="top" nowrap><span class="text13">User</span></TD>
<TD align="center" valign="top"><span class="text13">URL</span></TD>
<TD align="center" valign="top"><span class="text13">Name</span></TD>
<TD align="center" valign="top"><span class="text13">Email</span></TD>
<TD align="center" valign="top"><span class="text13">Joined</span></TD>
</TR>
<?PHP
}

###########################################################################
###########################################################################
###########################################################################

function credits_add_all_form() {
global $s;
check_session ('users');
$q = dq("select * from $s[pr]wait_imp where user = '0'",0);
while ($r = mysql_fetch_row($q))
$currently .= $r[2].' impressions of size '.$r[1].', daily will be added '.$r[3].' of them.<br>';
if ($currently) $currently = '<span class="text13blue">Currently waiting impressions:<br>'.$currently.'</span>';
include('./_head.txt');
echo $s[info];
?>
<span class="text13blue"><b>Add free impressions to all accounts</b></span>
<br><br><table border="0" cellspacing="20" cellpadding="0" class="table1" width="550"><tr><td align="center">

<table border="0" cellspacing="0" cellpadding="2" width="100%">
<form METHOD="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="impressions_added_all">
<tr>
<td align="center" colspan="2" nowrap><span class="text13blue"><b>Common free impressions</b></span></td>
</tr>
<tr>
<td align="left" width="50%" nowrap><span class="text13">No. of impressions</span></td>
<td align="left" width="50%" nowrap><INPUT size="10" maxlength="10" name="add" class="field1"></td>
</tr>
<tr>
<td align="left"><span class="text13">Ad size</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="1" checked>1 &nbsp;
<input type="radio" name="size" value="2">2 &nbsp;
<input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="co" value="&nbsp;Add now&nbsp;" class="button1"></td>
</td></tr></form></table>
<br>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<form METHOD="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="impressions_added_spec_all">
<tr>
<td align="center" colspan="2"><span class="text13blue"><b>Special free impressions</b></span><br>
<span class="text10">Sample: Each account should get 10% of the impressions which the account sent yesterday. Enter yesterday's date to the "Base date" field and number "10" to the "Percentage" field</span></td>
</tr>
<tr>
<td align="left" width="50%" nowrap><span class="text13">Base date</span></td>
<td align="left" width="50%" nowrap><span class="text13"><INPUT size="10" maxlength="10" name="date" class="field1"><span class="text13"> mm/dd/yyyy</span></td>
</tr>
<tr>
<td align="left" width="50%" nowrap><span class="text13">Percentage</span></td>
<td align="left" width="50%" nowrap><INPUT size="10" maxlength="10" name="percent" class="field1"><span class="text13"></td>
</tr>
<tr>
<td align="left"><span class="text13">Ad size</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="1" checked>1 &nbsp;
<input type="radio" name="size" value="2">2 &nbsp;
<input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="co" value="&nbsp;Add now&nbsp;" class="button1"></td>
</td></tr></form></table>
<br>
<table border="0" cellspacing="0" cellpadding="2" width="100%">
<form METHOD="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="impressions_added_wait_all">
<tr>
<td align="center" colspan="2" nowrap><span class="text13blue"><b>Deferred free impressions</b></span></td>
</tr>
<tr><td align="center" colspan="2">
<span class="text10">The form below allows you to add a free impressions to all accounts but these impressions will not be available immediately but in steps. You set total number of free impressions for each account and how many of them should be used each day. Example: You add 10,000 impressions and set that each day should be used 500 of them. Then it will take total of 20 days until all the impressions will be used.<br>Note: The 'Daily Job' must be properly configured for this function.<br></span>
<?PHP echo $currently; ?>
</td></tr>
<tr>
<td align="left" width="50%" nowrap><span class="text13">No. of impressions</span></td>
<td align="left" width="50%" nowrap><INPUT size="10" maxlength="10" name="add" class="field1"></td>
</tr>
<tr>
<td align="left" width="50%" nowrap><span class="text13">Daily use</span></td>
<td align="left" width="50%" nowrap><INPUT size="10" maxlength="10" name="daily" class="field1"></td>
</tr>
<tr>
<td align="left"><span class="text13">Ad size</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="1" checked>1 &nbsp;
<input type="radio" name="size" value="2">2 &nbsp;
<input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="center" nowrap colspan="2">
<input type="submit" name="co" value="&nbsp;Add now&nbsp;" class="button1"></td>
</td></tr></form></table>
</td></tr></table>
<br>
<?PHP
include('./_footer.txt');
exit;
}

###########################################################################

function impressions_added_wait_all ($a) {
global $s;
check_session ('users');
dq("delete from $s[pr]wait_imp where size = '$a[size]' and user = '0'",1);
dq("insert into $s[pr]wait_imp values ('0','$a[size]','$a[add]','$a[daily]')",1);
$s[info]='<span class="text13blue"><b>Data has been saved.</b></span><br><br>';
credits_add_all_form();
exit;
}

###########################################################################

function impressions_added_spec_all ($a) {
global $s;
check_session ('users');
list($m,$d,$y)= split('/',$a[date]);
settype($m,integer); settype($d,integer);
//echo "select number,i_m from $s[pr]days$a[size] where m = '$m' and d = '$d' and y = '$y'";
$q = dq("select number,i_m from $s[pr]days$a[size] where m = '$m' and d = '$d' and y = '$y'",0);
while ($r = mysql_fetch_row($q))
{ set_time_limit(30);
  $add = $r[1]*($a[percent]/100); $total = $total + $add; //$accounts++;
  dq("update $s[pr]stats$a[size] set i_free = i_free + '$add', i_nu = i_nu + '$add' where number = '$r[0]'",1);
}
$s[info] = iot('Total of '.round($total,2).' free impressions have been added to '.$accounts.' accounts of size '.$a[size]);
credits_add_all_form();
exit;
}

###########################################################################

function impressions_added_all($a) {
global $s;
check_session ('users');
dq("update $s[pr]stats$a[size] set i_free = i_free + '$a[add]', i_nu = i_nu + '$a[add]' where sponsor='$s[sponsor]'",1);
$s[info] = iot($a[add].' free impressions have been added to all accounts of size '.$a[size]);
credits_add_all_form();
exit;
}

###########################################################################
###########################################################################
###########################################################################

function cheaters_main() {
global $s;
include('./_head.txt');
echo $s[info];
echo iot('Possible Cheaters');
?>
<table border="0" cellspacing="10" cellpadding="2" class="table1">
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<tr><td align="center" nowrap><span class="text13blue"><b>Please select which report you want to show</b></span></td></tr>
<tr><td align="left" nowrap><span class="text13">
<input type="radio" name="action" value="lowratio"> Accounts with ratio (impr./clicks) lower than <INPUT class="field1" maxLength=5 size=5 name="lowratio"> % this month<br><br>
<input type="radio" name="action" value="too_ip"> Users with attemp to send more than <INPUT class="field1" maxLength=5 size=5 name="number"> hits from the same IP<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;since the last daily reset<br>
</td></tr><tr><td align="center" nowrap>
<input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"><br>
</td></tr></form></table><br>
<?PHP
include('./_footer.txt');
exit;
}

##################################################################################

function too_ip($number) {
global $s;
check_session ('accounts');
if (!$number) cheaters_main();
$q = dq("select $s[pr]ip.number,$s[pr]ip.ip,$s[pr]ip.hits,$s[pr]members.userid 
from $s[pr]ip,$s[pr]members where $s[pr]ip.hits > $number AND $s[pr]ip.number=$s[pr]members.number 
order by $s[pr]members.userid,$s[pr]ip.hits desc",1);
$num_rows = mysql_num_rows($q);
if (!$num_rows) { $s[info] = iot('No records suit your criteria'); cheaters_main(); }

$lastreset = datum (1,$s[daily]);
$cas = datum (1,0);
include('./_head.txt');
echo '<span class="text13blue"><b>Members with attemp to send more than '.$number.' hits<br>from the same IP since the last daily reset</b></span><br>
<span class="text13">Last reset: '.$lastreset.', current time: '.$cas.'</span>
<br><span class="text10">You have allowed maximum of '.$s[count_ip].' incoming hits from one IP address to be counted for each member. It means that only the first '.$s[count_ip].' impressions is counted for each user from one IP. Impressions which exceed this limit are not counted in the statistic and are visible in this report only.</span><br><br>
<table border="0" width="550" cellspacing="10" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="8" cellpadding="0" width="400"><TR>
<TD align="center" nowrap><span class="text13">User</span></TD>
<TD align="center"><span class="text13">IP</span></TD>
<TD align="center" nowrap><span class="text13">Number of impressions<br>from this IP</span></TD>
</TR>';
while ($data=mysql_fetch_assoc($q))
{ echo "<TR>
  <TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$data[number]\">$data[userid]</a></TD>
  <TD align=\"center\" nowrap><span class=\"text13\">$data[ip]</span></TD>
  <TD align=\"center\" nowrap><span class=\"text13\">$data[hits]</span></TD></TR>";
}
echo '</table></td></tr></table>';
include('./_footer.txt');
exit;
}

###########################################################################

function users_inactive_find($data) {
global $s;
check_session ('users');
if (!$data[days]) $data[days] = 14; $waittime = $s[cas] - ($data[days]*86400);
$q1 = dq("select $s[pr]stats1.userid,$s[pr]stats1.number 
from $s[pr]stats1,$s[pr]stats2,$s[pr]stats3 where 
$s[pr]stats1.last<'$waittime' AND $s[pr]stats1.joined<'$waittime' AND 
$s[pr]stats2.last<'$waittime' AND $s[pr]stats2.joined<'$waittime' AND 
$s[pr]stats3.last<'$waittime' AND $s[pr]stats3.joined<'$waittime' AND 
$s[pr]stats1.number = $s[pr]stats2.number AND $s[pr]stats2.number = $s[pr]stats3.number 
AND $s[pr]stats1.sponsor='$s[sponsor]'",0);
if ($data[what]=='delete')
{ $s[no_stop] = 1;
  while ($r = mysql_fetch_assoc($q1)) { $l[] = $r[userid]; user_delete($r); }
  if ($l[0]) $s[info] = iot('These users have been deleted:<br>'.join(', ',$l));
  else $s[info] = iot('No users meet entered criteria');
  accounts_stats_home();
}
else
{ include('./_head.txt');
  echo iot('Users Inactive Longer Than '.$data[days].' Days');
  users_table_head();
  while ($r = mysql_fetch_row($q1))
  { $q = dq("select * from $s[pr]members where number = '$r[1]'",0);
    $result = mysql_fetch_assoc($q); show_user_row($result);
  }
}
exit;
}

##################################################################################
##################################################################################
##################################################################################

?>