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

function ads_show_form() {
global $s;
include('./_head.txt');
echo $s[info];
echo iot('Ads');
?>
<table border="0" width="500" cellspacing="1" cellpadding="2" class="table1">
<tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="ads_show">
</td></tr>
<tr><td align="left" nowrap><span class="text13">Ad size: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="0" checked>All sizes &nbsp; 
<input type="radio" name="size" value="1">1 &nbsp; 
<input type="radio" name="size" value="2">2 &nbsp; <input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">Accepted by admin: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="accepted" value="0" checked>N/A &nbsp; 
<input type="radio" name="accepted" value="y">Yes &nbsp; <input type="radio" name="accepted" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">Enabled by admin: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="approve" value="0" checked>N/A &nbsp; 
<input type="radio" name="approve" value="y">Yes &nbsp; <input type="radio" name="approve" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">Enabled by user: </span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="enable" value="0" checked>N/A &nbsp; 
<input type="radio" name="enable" value="y">Yes &nbsp; <input type="radio" name="enable" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">1 or more ads ready:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="has_ad" value="0" checked>N/A &nbsp; 
<input type="radio" name="has_ad" value="y">Yes &nbsp; <input type="radio" name="has_ad" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">Per page * 
<td align="left" nowrap><span class="text13">
<input type="radio" name="perpage" value="0" checked>All &nbsp; 
<input type="radio" name="perpage" value="20">20 &nbsp; <input type="radio" name="perpage" value="50">50 &nbsp; 
<input type="radio" name="perpage" value="100">100 &nbsp; <input type="radio" name="perpage" value="200">200
</span></td></tr>
<tr><td align="center" colspan="2" nowrap><span class="text13">
<input type="submit" name="A1" value="&nbsp;Submit&nbsp;" class="button1">
</td></tr></form></table></td></tr></table><br>
<?PHP
echo $s[info_limit];
include('./_footer.txt');
exit;
}

#########################################################################
#########################################################################
#########################################################################

function ads_show_one_size($a) {
global $s;
if ($s[sponsor]) check_session ('s_accounts');
else check_session ('accounts');
$w = get_ads_where($a);
$q = dq("select count(*) from $s[pr]stats$a[size] where sponsor='$s[sponsor]' $w",1);
$pocet = mysql_fetch_row($q); $pocet = $pocet[0];
if (!$pocet) { $s[info] = iot('No accounts meet your criteria'); ads_show_form(); }

include('./_head.txt');
echo iot('Ads in Size '.$a[size].' (width '.$s["w$a[size]"].' px, height '.$s["h$a[size]"].' px)');

if ( ($a[perpage]) AND ($pocet>$a[perpage]) )
{ if (!$a[from]) $a[from] = 0; else $a[from] = $a[from] - 1;
  $od = $a[from]+1; $do = $a[from] + $a[perpage]; if ($do>$pocet) $do = $pocet;
  $rozcesti = "<span class=\"text13blue\"><b>Total of $pocet accounts found, showing accounts $od - $do</b></span>&nbsp;&nbsp;
  <form action=\"$s[thisscript]\" method=\"get\" name=\"form1\">
  <input type=\"hidden\" name=\"action\" value=\"ads_show\">
  <input type=\"hidden\" name=\"size\" value=\"$a[size]\">
  <input type=\"hidden\" name=\"perpage\" value=\"$a[perpage]\">
  <span class=\"text13\">Show accounts with begin of&nbsp;&nbsp;</span>
  <select class=\"field1\" name=\"from\"><option value=\"1\">1</option>";
  $y = ceil($pocet/$a[perpage]);  
  for ($x=1;$x<$y;$x++)
  { $od = $x*$a[perpage]+1;
    $rozcesti .= "<option value=\"$od\">$od</option>";}
  $rozcesti .= '</select>&nbsp;&nbsp;<input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></form>';
  $limit = "limit $a[from],$a[perpage]";
}
$s[size] = $a[size];
echo $rozcesti;
echo '<table border="0" width="80%" cellspacing="1" cellpadding="10" class="table1">';
$q = dq("select * from $s[pr]stats$a[size] where sponsor='$s[sponsor]' $w $limit",0);
while ($zaznam = mysql_fetch_assoc($q)) show_ad_rows($zaznam);
echo "</table></center><br>";
include('./_footer.txt');
exit;
}

#########################################################################

function ads_show_all_sizes($a) {
global $s;
if ($s[sponsor]) check_session ('s_accounts');
else check_session ('accounts');
$w = get_ads_where($a);
include('./_head.txt');
for ($x=1;$x<=3;$x++)
{ $s[size] = $x;
  echo iot('Ads in Size '.$x.' (width '.$s["w$x"].' px, height '.$s["h$x"].' px)');
  $q = dq("select * from $s[pr]stats$x where sponsor='$s[sponsor]' $w",0);
  if (!mysql_num_rows($q)) echo iot('No accounts meet your criteria');
  else
  { echo '<table border="0" width="80%" cellspacing="1" cellpadding="10" class="table1">';
    while ($zaznam = mysql_fetch_assoc($q)) show_ad_rows($zaznam);
    echo '</table></center><br>';
  }
}
include('./_footer.txt');
exit;
}

###########################################################################

function get_ads_where($a) {
// pomocna funkce
if ($a[accepted]=='y') $where[] = 'accept = 1';
elseif ($a[accepted]=='n') $where[] = 'accept != 1';
if ($a[approve]=='y') $where[] = 'approved = 1';
elseif ($a[approve]=='n') $where[] .= 'approved != 1';
if ($a[enable]=='y') $where[] = 'enable = 1';
elseif ($a[enable]=='n') $where[] = 'enable != 1';
if ($a[has_ad]=='y') $where[] = '(linka1!="" OR linka2!="" OR linka3!="")';
elseif ($a[has_ad]=='n') $where[] = '(linka1="" AND linka2="" AND linka3="")';
if ($where) $w = ' AND ' . implode(' AND ',$where);
return $w;
}

###########################################################################

function show_ad_rows($data) {
global $s;
$a[totalwidth] = $s["totalw$s[size]"]; $a[totalheight] = $s["totalh$s[size]"]; $a[size] = $s[size];
{ for ($x=1;$x<=3;$x++)
  { $ad = show_current_complete_ad($data[number],$s[size],$x);
    echo "<TR><TD align=\"center\"><span class=\"text13\">
    User <a title=\"Click to view/edit details\" href=\"$s[SCRdetail]?number=$data[number]\">$data[userid]</a> - ad #$x</span><br>
    <span class=\"text13\">$ad</span><br></td></tr>";
  }
}
}

#######################################################################
#######################################################################
#######################################################################

function show_categories_table($x) {
global $s;
if ($s["usecats$x"])
{ $q = dq("select catid,catname from $s[pr]categories where size='$x'",1);
  echo '<table border="0" width="300" cellspacing="1" cellpadding="2" class="table1">
  <TR><TD colspan="2" align="center"><span class="text13blue">Categories of ad size '.$x.'</span></td></tr>';
  while ($category = mysql_fetch_row($q))
  echo "<TR><TD align=\"center\"><span class=\"text13\">$category[0]</span></td>
  <TD align=\"center\"><span class=\"text13\">$category[1]</span></td></tr>\n";
  echo '</table><br>';
}
}

###########################################################################
###########################################################################
###########################################################################

function accounts_show_form() {
global $s;
for ($x=1;$x<=3;$x++)
{ $q = dq("select count(*) from $s[pr]stats$x where sponsor='$s[sponsor]' AND (accept != 1 OR approved != 1) AND (linka1!='' OR linka2!='' OR linka3!='')",0);
  $y = mysql_fetch_row($q); $check[$x] = $y[0];
}
?>
<table border="0" width="500" cellspacing="1" cellpadding="2" class="table1">
<tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<tr><td align="center" nowrap colspan="3"><span class="text13blue"><b>Accounts ready to check</b></span><br>
<span class="text10">It shows accounts which have one or more ads ready but are not enabled by admin</span><br>
</td></tr>
<tr>
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="accounts_show">
<input type="hidden" name="ready_to_check" value="1">
<input type="hidden" name="size" value="1">
<td align="right" nowrap><input type="submit" name="c" value="Size 1 (<?PHP echo $check[1] ?>)" class="button1"></td>
</form>
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="accounts_show">
<input type="hidden" name="ready_to_check" value="1">
<input type="hidden" name="size" value="2">
<td align="center" nowrap><input type="submit" name="c" value="Size 2 (<?PHP echo $check[2] ?>)" class="button1"></td>
</form>
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="accounts_show">
<input type="hidden" name="ready_to_check" value="1">
<input type="hidden" name="size" value="3">
<td align="left" nowrap><input type="submit" name="c" value="Size 3 (<?PHP echo $check[3] ?>)" class="button1"></td>
</form>
</tr></table>
<br>
<table border="0" cellspacing="1" cellpadding="2">
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="accounts_show">
<tr><td align="center" nowrap colspan="2"><span class="text13blue"><b>Show accounts, stats</b>
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Ad size:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="0" checked>All sizes &nbsp; 
<input type="radio" name="size" value="1">1 &nbsp; 
<input type="radio" name="size" value="2">2 &nbsp; <input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Accepted by admin:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="accepted" value="0" checked>N/A &nbsp; 
<input type="radio" name="accepted" value="y">Yes &nbsp; <input type="radio" name="accepted" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Enabled by admin:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="approve" value="0" checked>N/A &nbsp; 
<input type="radio" name="approve" value="y">Yes &nbsp; <input type="radio" name="approve" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Enabled by user:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="enable" value="0" checked>N/A &nbsp; 
<input type="radio" name="enable" value="y">Yes &nbsp; <input type="radio" name="enable" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Ad ready:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="has_ad" value="0" checked>N/A &nbsp; 
<input type="radio" name="has_ad" value="y">Yes &nbsp; <input type="radio" name="has_ad" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Advantaged:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="weight" value="0" checked>N/A &nbsp; 
<input type="radio" name="weight" value="y">Yes &nbsp; <input type="radio" name="weight" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Credits available:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="credits" value="0" checked>N/A &nbsp; 
<input type="radio" name="credits" value="y">Yes &nbsp; <input type="radio" name="credits" value="n">No
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">
Per page *</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="perpage" value="0" checked>All &nbsp; 
<input type="radio" name="perpage" value="20">20 &nbsp; <input type="radio" name="perpage" value="50">50 &nbsp; 
<input type="radio" name="perpage" value="100">100 &nbsp; <input type="radio" name="perpage" value="200">200
</td></tr>
<tr><td align="center" colspan="2">
<input type="submit" name="A1" value="Submit" class="button1">
</td></tr></form></table>
</td></tr></table><br>
<?PHP
}

###########################################################################

function current_month_statistic_form() {
global $s;
?>
<table border="0" width="500" cellspacing="1" cellpadding="2" class="table1"><tr><td align="center">
<table border="0" cellspacing="1" cellpadding="2">
<form method="get" action="<?PHP echo $s[SCRusers]; ?>">
<input type="hidden" name="action" value="month_show">
<tr><td align="center" nowrap colspan="2"><span class="text13blue"><b>Statistic of the current month</b><span></td></tr>
<tr>
<td align="left" nowrap><span class="text13">Ad size:</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="size" value="0" checked>All sizes &nbsp; <input type="radio" name="size" value="1">1 &nbsp; 
<input type="radio" name="size" value="2">2 &nbsp; <input type="radio" name="size" value="3">3
</span></td></tr>
<tr><td align="left" nowrap><span class="text13">Per page: *</span></td>
<td align="left" nowrap><span class="text13">
<input type="radio" name="perpage" value="0" checked>All &nbsp; <input type="radio" name="perpage" value="20">20 &nbsp; 
<input type="radio" name="perpage" value="50">50 &nbsp; <input type="radio" name="perpage" value="100">100 &nbsp;
<input type="radio" name="perpage" value="200">200
</span></td></tr>
<tr><td align="center" colspan="2">
<input type="submit" name="A1" value="Submit" class="button1">
</td></tr></form></table></td></tr></table><br>
<?PHP
}

###########################################################################
###########################################################################
###########################################################################

function accounts_rozcesti($total,$a) {
global $s;
if ($total<=$a[perpage]) return '';
$od = $a[from]+1; $do = $a[from] + $a[perpage]; if ($do>$total) $do = $total;
$ret = '<span class="text13blue"><b>Total of '.$total.' accounts found, showing accounts '."$od - $do".'</b></span>&nbsp;&nbsp;
<form method="get" action="'.$s[SCRusers].'" name="form1">
<input type="hidden" name="action" value="accounts_show">
<input type="hidden" name="what" value="'.$a[what].'">
<input type="hidden" name="size" value="'.$a[size].'">
<input type="hidden" name="accepted" value="'.$a[accepted].'">
<input type="hidden" name="approve" value="'.$a[approve].'">
<input type="hidden" name="has_ad" value="'.$a[has_ad].'">
<input type="hidden" name="enable" value="'.$a[enable].'">
<input type="hidden" name="weigth" value="'.$a[weight].'">
<input type="hidden" name="perpage" value="'.$a[perpage].'">
<span class="text13">Show accounts with begin of&nbsp;&nbsp;</span>
<select class="field1" name="from"><option value="1">1</option>';
$y = ceil($total/$a[perpage]);  
for ($x=1;$x<$y;$x++) { $od = $x*$a[perpage]+1; $ret .= '<option value="'.$od.'">'.$od.'</option>'; }
$ret .= '</select>&nbsp;&nbsp;<input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></form>';
return $ret;
}

###########################################################################

function month_rozcesti($total,$a) {
global $s;
if ( (!$a[perpage]) OR ($total<=$a[perpage]) ) return '';
$od = $a[from] + 1; $do = $a[from] + $a[perpage]; if ($do>$total) $do = $total;
$ret = '<span class="text13blue"><b>Total of '.$total.' accounts found, showing accounts '."$od - $do".'</b></span>&nbsp;&nbsp;
<form method="get" action="'.$s[SCRusers].'" name="form1">
<input type="hidden" name="action" value="month_show">
<input type="hidden" name="size" value="'.$a[size].'">
<input type="hidden" name="perpage" value="'.$a[perpage].'">
<span class="text13">Show accounts with begin of&nbsp;&nbsp;</span>
<select class="field1" name="from"><option value="1">1</option>';
$y = ceil($total/$a[perpage]);  
for ($x=1;$x<$y;$x++)
{ $od = $x*$a[perpage]+1; $ret .= '<option value="'.$od.'">'.$od.'</option>'; }
$ret .= '</select>&nbsp;&nbsp;<input type="submit" value="&nbsp;Submit&nbsp;" name="B1" class="button1"></form>';
return $ret;
}

###########################################################################

function get_accounts_where($a) {
global $s;
// pomocna funkce
if ($a[accepted]=='y') $where[] = 'accept = 1';
elseif ($a[accepted]=='n') $where[] = 'accept != 1';
if ($a[approve]=='y') $where[] = 'approved = 1';
elseif ($a[approve]=='n') $where[] = 'approved != 1';
if ($a[enable]=='y') $where[] = 'enable = 1';
elseif ($a[enable]=='n') $where[] = 'enable != 1';
if ($a[credits]=='y') $where[] = '(i_nu>=1 OR c_nu>=1)';
elseif ($a[credits]=='n') $where[] = '(i_nu<1 AND c_nu=0)';
if ($a[weight]=='y') $where[] = 'weight != 0';
elseif ($a[weight]=='n') $where[] = 'weight = 0';
if ($a[has_ad]=='y') $where[] = '(linka1!="" OR linka2!="" OR linka3!="")';
elseif ($a[has_ad]=='n') $where[] = '(linka1="" AND linka2="" AND linka3="")';
if ($a[what]=='noactive')
{ $limit = $s[cas] - $a[wait] * 86400;
  $where[] = "( ((last>0) AND (last<'$limit')) OR ((last=0) AND (joined<'$limit')) )";
}
if($where) $w = ' AND '.implode(' AND ',$where);
return $w;
}

###########################################################################
###########################################################################
###########################################################################

?>