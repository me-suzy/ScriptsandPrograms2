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


$s[SCRusers] = 's_users.php'; $s[SCRdetail] = 's_detail.php'; $s[sponsor] = 1; $s[sponsored] = 'sponsored';
include("./common.php");
include("$s[phppath]/admin/f_users.php");
check_session ('s_accounts');

switch ($HTTP_GET_VARS[action]) {
case 'accounts_stats_home'	: accounts_stats_home();
case 'ads_show_form'		: ads_show_form();
case 'users_orders'			: users_form();
case 'orders_show'			: orders_show($HTTP_GET_VARS);
case 'credits_add_all_form'	: credits_add_all_form();
case 'users_inactive_show'	: users_inactive_show($HTTP_GET_VARS);
// one size funkce se pouzivaji jen kdyz se ma rozdelit na vic stranek
case 'accounts_show'		: if ( ($HTTP_GET_VARS[perpage]) AND ($HTTP_GET_VARS[size]) ) accounts_show_one_size($HTTP_GET_VARS);
							  else accounts_show_all_sizes($HTTP_GET_VARS);
case 'month_show'			: if ($HTTP_GET_VARS[size]) month_show_one_size($HTTP_GET_VARS);
							  else month_show_all_sizes('');
case 'ads_show'				: if ($HTTP_GET_VARS[size]) ads_show_one_size($HTTP_GET_VARS);
					  		  else ads_show_all_sizes($HTTP_GET_VARS);
case 'impressions_added_all': impressions_added_all($HTTP_GET_VARS);
case 'clicks_added_all' 	: clicks_added_all($HTTP_GET_VARS);
case 'users_show'			: users_show($HTTP_GET_VARS);
}

#########################################################################
#########################################################################
#########################################################################

function accounts_stats_home() {
global $s;
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
<input type="hidden" name="action" value="users_inactive_show">
<tr><td align="center" nowrap><br><span class="text13">
<select name="what" class="field1"><option value="find">Show</option><option value="delete">Delete</option></select>
 users who are inactive longer than <input name="days" size="5" maxlength="10" class="field1"> days</span></td></tr>
<tr><td align="center" nowrap><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table><br>
<?PHP
echo $s[info_limit]; include('./_footer.txt'); exit;
}

##################################################################################

function accounts_show_one_size($a) {
global $s;
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
    // dalsi radek jiny pri N a S
    $i_w[$x]=$i_w[$x]+$p[0]; $i_rest[$x]=$i_rest[$x]+$p[1]; $cl_w[$x]=$cl_w[$x]+$p[2]; $cl_rest[$x]=$cl_rest[$x]+$p[3];
}
?>
<tr>
<TD colspan=5 align="center"><span class="text13"><b>TOTAL</b></span></TD>
<!-- verze pro normal
<TD align="center"><span class="text13"><b><?PHP echo $xzobr[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $xmy[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $xnep[$x]; ?></b></span></TD>
<TD colspan=2>&nbsp;</TD>-->
<!-- verze pro sponsora -->
<TD align="center"><span class="text13"><b><?PHP echo $i_w[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $i_rest[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $cl_w[$x]; ?></b></span></TD>
<TD align="center"><span class="text13"><b><?PHP echo $cl_rest[$x]; ?></b></span></TD>
</TR>
</table><br>
<?PHP
show_categories_table($a[size]);
include('./_footer.txt');
exit;
}

##############################################################################

function accounts_show_all_sizes($a) {
global $s;
if (!$a[wait]) $a[wait] = 14;
if (!$a[size]) { $x1=1; $x2=3; } else $x1=$x2=$a[size];

$w = get_accounts_where($a);

if ($a[ready_to_check])
$w = ' AND (accept != 1 OR approved != 1) AND (linka1!="" OR linka2!="" OR linka3!="") ';
include('./_head.txt');
if ($a[what]=='noactive') echo iot('Accounts Inactive Longer Than '.$a[wait].' Days');
for ($x=$x1;$x<=$x2;$x++)
{ $width = $s["w$x"]; $height = $s["h$x"];
  $q = dq("select * from $s[pr]stats$x where sponsor='$s[sponsor]' $w order by userid",1);
  //$total = mysql_num_rows($q); Total found $total accounts
  accounts_table_head($x);
  while ($zaznam = mysql_fetch_assoc($q))
  { $p = show_account_row($zaznam);
    // dalsi radek jiny pri N a S
    $i_w[$x]=$i_w[$x]+$p[0]; $i_rest[$x]=$i_rest[$x]+$p[1]; $cl_w[$x]=$cl_w[$x]+$p[2]; $cl_rest[$x]=$cl_rest[$x]+$p[3];
  }
  ?>
  <tr>
  <TD colspan=5 align="center"><span class="text13"><b>TOTAL</b></span></TD>
  <!-- verze pro normal
  <TD align="center"><span class="text13"><b><?PHP echo $xzobr[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $xmy[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $xnep[$x]; ?></b></span></TD>
  <TD colspan=2>&nbsp;</TD>-->
  <!-- verze pro sponsora -->
  <TD align="center"><span class="text13"><b><?PHP echo $i_w[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $i_rest[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $cl_w[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $cl_rest[$x]; ?></b></span></TD>
  </TR>
  </table><br>
  <?PHP
  show_categories_table($x);
  // dalsi radek jiny pr S a N
  $i_w[0]=$i_w[0]+$i_w[$x]; $i_rest[0]=$i_rest[0]+$i_rest[$x]; $cl_w[0]=$cl_w[0]+$cl_w[$x]; $cl_rest[0]=$cl_rest[0]+$cl_rest[$x];
} // konec for x=1 az 3
if ($x1==$x2) { include('./_footer.txt'); exit; }

if ($totallast) $last = datum (1,$totallast); else $last='Never yet';
?>
<table border="0" width="98%" cellspacing="1" cellpadding="2" class="table1">
<tr><TD align="center" valign="top" nowrap colspan="4"><span class="text13blue"><b>Overall Statistic</b></span></TD></TR>
<tr>
<!-- pro normal 
<TD align="center" valign="top" nowrap><span class="text13">Impressions sent<br>by all accounts above</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Impressions received<br>by all accounts above</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not used<br>impressions</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Last impression<br>by any member</span></TD>
-->
<!-- pro sponsora -->
<TD align="center" valign="top" nowrap><span class="text13">Impressions received<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not used<br>impressions</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks received<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not used<br>clicks</span></TD>
</TR>
<tr>
<!-- pro normal 
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $xzobr[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $xmy[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $xnep[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $last; ?></b></span></TD>
-->
<!-- pro sponsora -->
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $i_w[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $i_rest[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $cl_w[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $cl_rest[0]; ?></b></span></TD>
</TR>
</table>
<?PHP
include('./_footer.txt');
exit;
}

##################################################################################

function accounts_table_head($size) {
global $s;
// pro S + N az na vyjimky
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
<!-- dalsi radek jen pro normal -->
<!-- <TD align="center" valign="top" nowrap><span class="text13">Impr.<br>sent</span></TD> -->
<TD align="center" valign="top" nowrap><span class="text13">Impr.<br>received</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not<br>used<br>impr.</span></TD>
<!-- dalsi radek jen pro normal -->
<!-- <TD align="center" valign="top" nowrap><span class="text13">Last<br>impression<br>sent</span></TD> -->
<!-- dalsi dva radky jen pro sponsora -->
<TD align="center" valign="top" nowrap><span class="text13">Clicks<br>received</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Not<br>used<br>clicks</span></TD>
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
<!-- dalsi radek jen pro normal -->
<!--<TD align=\"center\"><span class=\"text13\">$data[i_m]</span></TD> -->
<TD align=\"center\"><span class=\"text13\">$data[i_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[i_nu]</span></TD>
<!-- dalsi radek jen pro normal -->
<!--<TD align=\"center\" nowrap><span class=\"text13\">$last</span></TD> -->
<!-- dalsi dva radky jen pro sponsora -->
<TD align=\"center\"><span class=\"text13\">$data[c_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[c_nu]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[weight]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[category]&nbsp;</span></TD>
</TR>\n\n";
// dalsi radek jiny pr N a S
return array($data[i_w],$data[i_nu],$data[c_w],$data[c_nu]);
}

#############################################################################
#############################################################################
#############################################################################

function month_show_all_sizes($data) {
global $s;
if ($data[action]=='lowratio') $info='Accounts With Click Ratio Lower Than '.$data[lowratio].' % This Month
<br><span class="text13blue">Only accounts with at least 100 impressions sent this month are displayed';
else $info='Monthly Statistic';
include('./_head.txt');
echo iot($info);
echo '<span class="text13">It shows only accounts with at least one impression or click sent or received in the current month.</span><br><br>';
for ($x=1;$x<=3;$x++)
{ $q = dq("select * from $s[pr]months$x where sponsor='$s[sponsor]' order by userid",0);
  month_table_head($x);
  while ($zaznam = mysql_fetch_array($q))
  { if ( ($data[action]=='lowratio') AND (($zaznam[r_m]>=$data[lowratio]) OR ($zaznam[i_m]<100)) ) continue;
    $p = show_month_row($zaznam);
    $x_i_m[$x]=$x_i_m[$x]+$p[0]; $x_cl_m[$x]=$x_cl_m[$x]+$p[1]; $x_i_w[$x]=$x_i_w[$x]+$p[2]; $x_cl_w[$x]=$x_cl_w[$x]+$p[3];
  }
  ?>
  <tr><TD align="center"><span class="text13"><b>TOTAL</b></span></TD>
<!--
  <TD align="center"><span class="text13"><b><?PHP echo $x_i_m[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_cl_m[$x]; ?></b></span></TD>
  <TD>&nbsp;</TD>-->
  <TD align="center"><span class="text13"><b><?PHP echo $x_i_w[$x]; ?></b></span></TD>
  <TD align="center"><span class="text13"><b><?PHP echo $x_cl_w[$x]; ?></b></span></TD>
  <TD>&nbsp;</TD></TR></table></td></tr></table><br>
  <?PHP
  $x_i_m[0]=$x_i_m[0]+$x_i_m[$x]; $x_cl_m[0]=$x_cl_m[0]+$x_cl_m[$x]; $x_i_w[0]=$x_i_w[0]+$x_i_w[$x]; $x_cl_w[0]=$x_cl_w[0]+$x_cl_w[$x];
}  // konec 1 az 3
if ($data[action]=='lowratio') { include('./_footer.txt'); exit; }

?>

<table border="0" width="600" cellspacing="1" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="2" width="400">
<tr><TD align="center" colspan="4"><span class="text13blue"><b>Overall Statistic</b></span></TD></TR>
<tr>
<!--
<TD align="center" valign="top" nowrap><span class="text13">Impressions sent<br>by all members</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks sent<br>by all members</span></TD>
-->
<TD align="center" valign="top" nowrap><span class="text13">Impressions received<br>by all users</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks received<br>by all users</span></TD>
</TR>
<tr>
<!--
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_i_m[0]; ?></b></span></TD>
<TD align="center" valign="top" nowrap><span class="text13blue"><b><?PHP echo $x_cl_m[0]; ?></b></span></TD>
-->
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
while ($zaznam = mysql_fetch_array($q))
{ $p = show_month_row($zaznam);
  $x_i_m[$x]=$x_i_m[$x]+$p[0]; $x_cl_m[$x]=$x_cl_m[$x]+$p[1]; $x_i_w[$x]=$x_i_w[$x]+$p[2]; $x_cl_w[$x]=$x_cl_w[$x]+$p[3];
}
?>
<tr><TD align="center"><span class="text13"><b>TOTAL</b></span></TD>
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
<!--
<TD align="center" valign="top" nowrap><span class="text13">Impressions<br>sent by user</span></TD>
<TD align="center" valign="top" nowrap><span class="text13">Clicks<br>sent by user</span></TD>
<TD align="center" valign="top"><span class="text13">Ratio<br>(%)</span></TD>-->
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
<!--
<TD align=\"center\"><span class=\"text13\">$data[i_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[cl_m]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[r_m]</span></TD>
-->
<TD align=\"center\"><span class=\"text13\">$data[i_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[cl_w]</span></TD>
<TD align=\"center\"><span class=\"text13\">$data[r_w]</span></TD>
</TR>\n\n";
return array($data[i_m],$data[cl_m],$data[i_w],$data[cl_w]);
}

#########################################################################
#########################################################################
#########################################################################

function credits_add_all_form() {
global $s;
check_session ('sponsors');
include('./_head.txt');
echo $s[info];
?>
<span class="text13blue"><b>Add free impressions or clicks to all sponsored accounts</b></span>
<br><br><table border="0" cellspacing="20" cellpadding="0" class="table1" width="500"><tr><td align="center">

<table border="0" cellspacing="0" cellpadding="2" width="400">
<form METHOD="get" action="<?PHP echo $s[SCRusers] ?>">
<input type="hidden" name="action" value="impressions_added_all">
<tr>
<td align="left" width="50%" nowrap><span class="text13">Add free impressions</span></td>
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
<table border="0" cellspacing="0" cellpadding="2" width="400">
<form METHOD="get" action="<?PHP echo $s[SCRusers] ?>">
<input type="hidden" name="action" value="clicks_added_all">
<tr>
<td align="left" width="50%" nowrap><span class="text13">Add free clicks</span></td>
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
</td></tr></table>
<br>
<?PHP
include('./_footer.txt');
exit;
}

#############################################################################

function impressions_added_all($a) {
global $s;
check_session ('sponsors');
dq("update $s[pr]stats$a[size] set i_free = i_free + '$a[add]', i_nu = i_nu + '$a[add]' where sponsor='$s[sponsor]'",1);
$s[info] = iot($a[add].' free impressions have been added to all sponsored accounts of size '.$a[size]);
credits_add_all_form();
exit;
}

#############################################################################

function clicks_added_all($a) {
global $s;
check_session ('sponsors');
dq("update $s[pr]stats$a[size] set c_free = c_free + '$a[add]', c_nu = c_nu + '$a[add]' where sponsor='$s[sponsor]'",1);
$s[info] = iot($a[add].' free clicks have been added to all sponsored accounts of size '.$a[size]);
credits_add_all_form();
exit;
}

#########################################################################
#########################################################################
#########################################################################

function users_form() {
global $s;
include('./_head.txt');
echo $s[info];
echo iot('Users & Orders');
?>
<table border="0" width="500" cellspacing="4" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<form method="get" action="<?PHP echo $s[SCRusers] ?>">
<input type="hidden" name="action" value="users_show">
<tr><td align="center" colspan="2" nowrap><span class="text13blue"><b>Search for users</b></span></td></tr>
<tr><td align="left" nowrap><span class="text13">Word or phrase</span></td>
<td align="left" nowrap><input name="phrase" size="50" maxlength="50" class="field1">
</td></tr>
<tr><td align="left" nowrap><span class="text13">Orders</span></td>
<td align="left" nowrap><select class="field1" name="orders">
<option value="0">Not depends if they have any order</option><option value="y">With at least one order</option><option value="paid">With at least one paid order</option><option value="n">Without orders</option>
</select></td></tr>
<tr><td align="left" colspan="2" nowrap><input type="checkbox" name="joined_in_last" value="1"> 
<span class="text13">Only users who joined in the last 
<input class="field1" type="text" name="joined_in_days" size="5" maxlength="10"> days</span>
</td></tr>
<tr><td align="left" nowrap><span class="text13">Accepted</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="accepted" value="0" checked>N/A &nbsp; 
<input type="radio" name="accepted" value="y">Yes &nbsp; 
<input type="radio" name="accepted" value="n">No</td></tr>
<tr><td align="left" nowrap><span class="text13">Per page</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="perpage" value="0" checked>All &nbsp; <input type="radio" name="perpage" value="20">20 &nbsp; 
<input type="radio" name="perpage" value="50">50 &nbsp; <input type="radio" name="perpage" value="100">100 &nbsp;
<input type="radio" name="perpage" value="200">200</td></tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="Submit" class="button1"></td></tr>
</form></table></td></tr></table>
<br>
<table border="0" width="500" cellspacing="4" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<form method="GET" action="<?PHP echo $s[SCRusers] ?>">
<input type="hidden" name="action" value="orders_show">
<tr><td align="center" nowrap colspan="2"><span class="text13blue"><b>Orders</b></span></td></tr>
<tr><td align="left" nowrap><span class="text13">Paid: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="which" value="0" checked>N/A &nbsp; 
<input type="radio" name="which" value="paid">Yes &nbsp; <input type="radio" name="which" value="nopaid">No
<tr><td align="left" nowrap><span class="text13">Per page: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="perpage" value="0" checked>All &nbsp; <input type="radio" name="perpage" value="20">20 &nbsp; 
<input type="radio" name="perpage" value="50">50 &nbsp; <input type="radio" name="perpage" value="100">100 &nbsp;
<input type="radio" name="perpage" value="200">200</td></tr>
<tr><td align="center" colspan="2"><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table>
<br>
<table border="0" width="500" cellspacing="4" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<form method="GET" action="<?PHP echo $s[SCRusers] ?>">
<input type="hidden" name="action" value="users_inactive_show">
<tr><td align="center" nowrap><span class="text13blue"><b>Inactive users</b></span></td></tr>
<tr><td align="center" nowrap><span class="text13">
<select name="what" class="field1"><option value="find">Show</option><option value="delete">Delete</option></select>
 users with no one new order in the last <input name="days" size="5" maxlength="10" class="field1"> days<br>
<input type="checkbox" name="withoutfunds"> Only those who don't have funds
</td></tr>
<tr><td align="center"><input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1"></td></tr>
</form></table></td></tr></table>
<?PHP
include('./_footer.txt');
exit;
}

##################################################################################

function users_show($a) {
global $s;
if ($a[phrase]) $where[] = "(userid like '%$a[phrase]%' OR email like '%$a[phrase]%' OR siteurl like '%$a[phrase]%' OR name like '%$a[phrase]%' OR address like '%$a[phrase]%')";
if (($a[joined_in_last]) AND ($a[joined_in_days]>0) )
{ $cas = $s[cas] - ($a[joined_in_days] * 86400); $where[] = "date > $cas"; }
if ($a[orders]=='y') $where[] = "s_orders > 0";
elseif ($a[orders]=='paid') $where[] = "s_paid_ord > 0";
elseif ($a[orders]=='n') $where[] = "s_orders = 0";
if ($a[accepted]=='y') $where[] = 'accepted = 1'; elseif ($a[accepted]=='n') $where[] = 'accepted = 0';
if ($where) $w = ' AND ' . implode(' AND ',$where);
$q = dq("select count(*) from $s[pr]members where sponsor='$s[sponsor]' $w",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { $s[info] = iot('No users meet your criteria found'); users_form(); }

if ($a[perpage])
{ if (!$a[from]) $a[from] = 0; else $a[from] = $a[from] - 1;
  $limit = "limit $a[from],$a[perpage]"; 
  if ($pocet > $a[perpage])
  { $rozcesti = '<form method="get" name="form">
    <input type="hidden" name="action" value="users_show">
    <input type="hidden" name="phrase" value="'.$a[phrase].'">
    <input type="hidden" name="orders" value="'.$a[orders].'">
    <input type="hidden" name="joined_in_last" value="'.$a[joined_in_last].'">
    <input type="hidden" name="joined_in_days" value="'.$a[joined_in_days].'">
    <input type="hidden" name="accepted" value="'.$a[accepted].'">
    <input type="hidden" name="perpage" value="'.$a[perpage].'">
    <span class="text13">Show users with begin of &nbsp;</span>
    <select class="field1" name="from"><option value="1">1</option>';
    $y = ceil($pocet/$a[perpage]);  
    for ($x=1;$x<$y;$x++)
    { $od = $x*$a[perpage]+1; $rozcesti .= "<option value=\"$od\">$od</option>"; }
    $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></form>';
    $od = $a[from] + 1; $do = $a[from] + $a[perpage]; if ($do > $pocet) $do = $pocet;
    if ($od==$do) $showing = ", showing user $od";
    else $showing = ", showing users $od - $do";
  }
}
$q = dq("select * from $s[pr]members where sponsor='$s[sponsor]' $w order by userid $limit",1);
include('./_head.txt');
echo iot('Users found: '.$pocet.' '.$showing).' '.$rozcesti;
sponsor_users_table_head();
while ($data=mysql_fetch_assoc($q)) show_sponsor_user_row($data);
echo '</table></TD></TR></TABLE>';
include('./_footer.txt');
exit;
}

##################################################################################

function orders_show($a) {
global $s;
if ($a[which]=='paid') $where[] = 'paylink = ""';
if ($a[which]=='nopaid') $where[] = 'not(paylink = "")';
if ($a[user]) $where[] = "user = '$a[user]'";
if ($where) $w = ' AND ' . implode(' AND ',$where);
$q = dq("select count(*) from $s[pr]s_orders where 1 $w",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { $s[info] = iot('No orders found'); users_form(); }
if ($a[perpage])
{ if (!$a[from]) $a[from] = 0; else $a[from] = $a[from] - 1;
  $limit = "limit $a[from],$a[perpage]"; 
  if ($pocet > $a[perpage])
  { $rozcesti = '<form method="GET" name="form">
    <input type="hidden" name="action" value="orders_show">
    <input type="hidden" name="which" value="'.$a[which].'">
    <input type="hidden" name="perpage" value="'.$a[perpage].'">
    <span class="text13">Show orders with begin of &nbsp;</span><select class="field1" name="from"><option value="1">1</option>';
    $y = ceil($pocet/$a[perpage]);  
    for ($x=1;$x<$y;$x++)
    { $od = $x*$a[perpage]+1; $rozcesti .= "<option value=\"$od\">$od</option>"; }
    $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></form>';
    $od = $a[from] + 1; $do = $a[from] + $a[perpage]; if ($do > $pocet) $do = $pocet;
    if ($od == $do) $showing = ", showing order $od";
    else $showing = ", showing orders $od - $do";
  }
}
$q = dq("select $s[pr]s_orders.number,$s[pr]members.number as userno,user,pack,price,paylink,order_time,userid,bonus,value,packname from
$s[pr]s_orders,$s[pr]members where $s[pr]s_orders.user=$s[pr]members.number $w order by order_time desc $limit",1);
$returnto = urlencode("$s[SCRusers]?action=orders_show&which=$a[which]&perpage=$a[perpage]");
include('./_head.txt');
echo iot('Orders found: '.$pocet.$showing).$rozcesti;
echo '<table border="0" width="600" cellspacing="8" cellpadding="2" class="table1"><TR><td align="center">
 <table border="0" width="580" cellspacing="3" cellpadding="2"><TR>
  <TD align="center" valign="top" nowrap><span class="text13">Order #</span></TD>
  <TD align="center" valign="top"><span class="text13">User</span></TD>
  <TD align="center" valign="top"><span class="text13">Pack</span></TD>
  <TD align="center" valign="top"><span class="text13">Price</span></TD>
  <TD align="center" valign="top"><span class="text13">Bonus</span></TD>
  <TD align="center" valign="top"><span class="text13">Value</span></TD>
  <TD align="center" valign="top"><span class="text13">Paid</span></TD>
  <TD align="center" valign="top"><span class="text13">Date</span></TD>
  </TR>';
while ($result=mysql_fetch_array($q))
{ $cas = datum(1,$result[order_time]); if ($result[paylink]) $paid = '<font color="red">No</font>'; else $paid = '<font color="green">Yes</font>';
  if (!$result[s_last]) $lastorder = ''; else $lastorder = datum(0,$result[s_last]);
  echo "<TR>
  <TD align=\"center\" nowrap><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?action=order_details&number=$result[number]&returnto=$returnto\">$result[number]</a></TD>
  <TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$result[userno]\">$result[userid]</a></TD>
  <TD align=\"center\"><a title=\"$result[packname]\" href=\"s_tools.php?action=package_edit&number=$result[pack]\">$result[pack]</span></TD>
  <TD align=\"center\"><span class=\"text13\">$result[price]</span></TD>
  <TD align=\"center\"><span class=\"text13\">$result[bonus]%</span></TD>
  <TD align=\"center\"><span class=\"text13\">$result[value]</span></TD>
  <TD align=\"center\" nowrap><span class=\"text13\">$paid</span></TD>
  <TD align=\"center\" nowrap><span class=\"text13\">$cas</span></TD>
  </TR>"; 
}
echo '</table></TD></TR></TABLE>';
include('./_footer.txt');
exit;
}

##################################################################################

function users_inactive_show($data) {
global $s;
if (!$data[days]) { $s[info] = iot('Number of days is missing'); users_form(); }
$waittime = $s[cas] - ($data[days]*86400);
if ($data[withoutfunds]) $x = 'AND s_funds<1';
$q = dq("select * from $s[pr]members where date < '$waittime' AND s_last < '$waittime' $x AND sponsor='$s[sponsor]'",0);
$number = mysql_num_rows($q);
if (!$number) { $s[info] = iot('No users meet entered criteria'); users_form(); }
if ($data[what]=='delete')
{ $s[no_stop] = 1;
  while ($r = mysql_fetch_assoc($q)) { $l[] = $r[userid]; user_delete($r); }
  if ($l[0]) $s[info] = iot('These users have been deleted:<br>'.join(', ',$l));
  users_form();
}
else
{ include('./_head.txt');
  echo iot('Users Without New Orders in the Last '.$data[days].' Days');
  sponsor_users_table_head();
  while ($r = mysql_fetch_assoc($q)) show_sponsor_user_row($r);
  echo '</table></td></tr></table>';
}
exit;
}

##########################################################################

function sponsor_users_table_head() {
?>
<table border="0" width="90%" cellspacing="8" cellpadding="2" class="table1"><TR><td align="center">
<table border="0" width="500" cellspacing="3" cellpadding="2"><TR>
<TD align="center" valign="top" nowrap><span class="text13">User ID</span></TD>
<TD align="center" valign="top"><span class="text13">Name</span></TD>
<TD align="center" valign="top"><span class="text13">Email</span></TD>
<TD align="center" valign="top"><span class="text13">Orders</span></TD>
<TD align="center"><span class="text13">Orders<br>paid</span></TD>
<TD align="center"><span class="text13">Last<br>order</span></TD>
<TD align="center"><span class="text13">Account<br>balance</span></TD>
<TD align="center" valign="top"><span class="text13">Joined</span></TD>
</TR>
<?PHP
}

##########################################################################

function show_sponsor_user_row($data) {
global $s;
//$data = strip_replace_array($data);
//$data = stripslashes($data);
$joined = datum(0,$data[date]);
if (!$data[s_last]) $lastorder = ''; else $lastorder = datum(0,$data[s_last]);
echo "<TR>
<TD align=\"center\"><a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?action=member_details&number=$data[number]\">$data[userid]</a></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$data[name]</span></TD>
<TD align=\"center\"><a href=\"mailto:$data[email]\">$data[email]</a></TD>
<TD align=\"center\"><a href=\"$s[SCRusers]?action=orders_show&which=all&user=$data[number]\">$data[s_orders]</a></TD>
<TD align=\"center\"><a href=\"$s[SCRusers]?action=orders_show&which=paid&user=$data[number]\">$data[s_paid_ord]</a></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$lastorder</span></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$data[s_funds]</span></TD>
<TD align=\"center\" nowrap><span class=\"text13\">$joined</span></TD></TR>";
}

#########################################################################
#########################################################################
#########################################################################

?>