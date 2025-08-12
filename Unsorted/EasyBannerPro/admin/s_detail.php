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


$s[SCRusers] = 's_users.php'; $s[SCRdetail] = 's_detail.php'; $s[sponsor] = 0;
include("./common.php");
include("$s[phppath]/admin/f_detail.php");

switch ($HTTP_POST_VARS[action]) {
case 'user_accept'			: user_accept($HTTP_POST_VARS);
case 'user_edit'			: user_edit($HTTP_POST_VARS[number]);
case 'user_edited'			: user_edited($HTTP_POST_VARS);
case 'user_delete'			: user_delete($HTTP_POST_VARS);
case 'ad_edited'			: ad_edited($HTTP_POST_VARS);
case 'account_enable'		: account_enable($HTTP_POST_VARS);
case 'account_disable'		: account_disable($HTTP_POST_VARS);
case 'save_numbers'			: save_numbers($HTTP_POST_VARS);
case 'order_delete'			: order_delete($HTTP_POST_VARS);
case 'order_paid'			: order_paid($HTTP_POST_VARS[number]);
}
switch ($HTTP_GET_VARS[action]) {
case 'order_details'		: order_details($HTTP_GET_VARS);
case 'account_edit'			: account_edit($HTTP_GET_VARS);
case 'aff_credits_confer'	: aff_credits_confer($HTTP_GET_VARS);
case 'aff_credits_reject'	: aff_credits_reject($HTTP_GET_VARS);
}
user_details($HTTP_GET_VARS[number]);


##########################################################################
##########################################################################
##########################################################################

function user_details($number) {
global $s;
check_session ('sponsors');
$q = dq("select * from $s[pr]members where number = '$number'",1);
$user = mysql_fetch_assoc($q);
$free_cred_info = free_credits_info($user);
$accept = accept_user_info($number);

include('./_head.txt');
echo $s[info];
echo iot('User '.$user[userid].$accept.$free_cred_info);

user_details_table($number);
user_delete_table($number);
$user[s_last] = datum(1,$user[s_last]);

?>
<table border="0" cellspacing="2" cellpadding="4" class="table1" width="500"><tr><td align="center">
<table border="0" cellspacing="0" cellpadding="2" width="300">
<tr>
<td colspan=2 align="center">
<span class="text13blue"><b>Orders</b></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">No. of orders</span></td>
<td align="left" nowrap><a href="s_users.php?action=orders_show&which=all&user=<?PHP echo $number.'">'.$user[s_orders] ?></a></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Paid orders</span></td>
<td align="left" nowrap><a href="s_users.php?action=orders_show&which=paid&user=<?PHP echo $number.'">'.$user[s_paid_ord] ?></a></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Funds balance</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $user[s_funds]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Last order</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $user[s_last]; ?></span></td>
</tr>
</tr></table></td></tr></table><br>
<?PHP

for ($x=1;$x<=3;$x++)
{ $s[use1] = 1; if (!$s["use$x"]) continue;
  $q = dq("select * from $s[pr]stats$x where number = '$number'",1);
  $stats = mysql_fetch_assoc($q);
  $q = dq("select * from $s[pr]link$x where number = '$number'",1);
  $link = mysql_fetch_assoc($q);

  $en_dis = enable_disable_info_button($number,$x,$stats);

  if (!$stats[weight]) $advantaged = 'No'; else $advantaged = $stats[weight];
  if ($stats[last]) $lastimpress = datum(1,$stats[last]); else $lastimpress = 'Never yet'; 
  $width = $s["w$x"]; $height = $s["h$x"];

  $y = list_category_categories($x,$stats); $category = $y[0]; $categories = $y[1];

  ?>
  <table border="0" width="500" cellspacing="2" cellpadding="4" class="table1"><tr><td align="center">
  <table border="0" width="480" cellspacing="0" cellpadding="2"><tr>
  <td colspan=2 align="center">
  <span class="text13blue"><b>Size <?PHP echo "$x (width $width px, height $height px)</span><br><span class=\"text13\">$en_dis[approved] $en_dis[enabled]"; ?></span></TD>
  </tr>
  <tr><td colspan=2 align="center">
  <table border=0 cellpadding=10 cellspacing=0 width="300"><tr>
  <?PHP echo $en_dis[button] ?>
  <form METHOD="get" action="<?PHP echo $s[SCRdetail] ?>">
  <input type="hidden" name="number" value="<?PHP echo $number; ?>">
  <input type="hidden" name="size" value="<?PHP echo $x; ?>">
  <input type="hidden" name="action" value="account_edit">
  <td align="center"><input type="submit" name="co" value="Edit this account" class="button1"></td></form>
  </tr></table>
  </td></tr>
  <tr>
  <td align="left" nowrap><span class="text13">Advantaged</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $advantaged; ?></a></span></td>
  </tr>
  <tr><td align="center" colspan="2" nowrap><span class="text10">If an account is advantaged, links of this account have priority.<br>Higher number = higher priority</span></td></tr>
  <tr>
  <td align="left" nowrap><span class="text13">Category</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $category; ?></span></span></td>
  </tr>

  <tr>
  <td align="center" nowrap colspan="2"><span class="text13blue"><b>Statistic</b></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Clicks left</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[c_nu]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions left</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_nu]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Impressions received</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_w]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Free impressions</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[i_free]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Clicks received</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[c_w]; ?></a></span></td>
  </tr>
  <tr>
  <td align="left" nowrap><span class="text13">Free clicks</span></td>
  <td align="left" nowrap><span class="text13"><?PHP echo $stats[c_free]; ?></a></span></td>
  </tr>
  <tr><td colspan=2 align="center">
  <?PHP
  echo statistic_table_month($number,$x);
  get_three_ads($number,$x,$stats,$link,0);
  echo '</td></tr></table></td></tr></table><br>';
}
include('./_footer.txt');
exit;
}

#############################################################################
#############################################################################
#############################################################################

function account_edit($a) {
global $s;
check_session ('sponsors');
$q = dq("select * from $s[pr]link$a[size] where number = '$a[number]'",1);
$link = mysql_fetch_assoc($q);
foreach ($link as $k => $v) $link[$k] = stripslashes($v);
for ($x=1;$x<=3;$x++) $link["raw$x"] = htmlspecialchars(unreplace_once_html($link["raw$x"]));

$q = dq("select * from $s[pr]stats$a[size] where number = '$a[number]'",1);
$stats = mysql_fetch_assoc($q);
$stats[i_nu] = round($stats[i_nu]);

if ($stats[approved]) $i = '<font color="green">Enabled by admin</font>&nbsp;&nbsp;&nbsp;'; else $i = '<font color="red">Disabled by admin</font>&nbsp;&nbsp;&nbsp;';
if ($stats[enable]) $i .= '<font color="green">Enabled by user</font>'; else $i .= '<font color="red">Disabled by user</font>';

$stats[advantage] = '<SELECT class="field1" name="weight"><option value="0">No</option>';
for ($x=1;$x<=5;$x++)
{ if ($stats[weight]==$x) $selected=' selected'; else $selected='';
  $stats[advantage] .= "<option value=\"$x\"$selected>$x</option>\n";
}
$stats[advantage] .= '</select>';


include('./_head.txt');
echo $s[info];
echo iot('User '.$link[userid].' - Account '.$a[size].' </b>(width '.$s["w$a[size]"].'px, height '.$s["h$a[size]"].'px)</span><br><span class="text13">'.$i.'</span>');
?>
<table border="0" cellspacing="10" cellpadding="0" class="table1" width="580"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="4">
<form METHOD="post" action="s_detail.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="size" value="<?PHP echo $a[size]; ?>">
<input type="hidden" name="action" value="save_numbers">
<tr>
<td align="left" nowrap><span class="text13">Impressions balance</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[i_nu]; ?></span></td>
<td align="left"><span class="text13">&nbsp;</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Impressions received</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[i_w]; ?></span></td>
<td align="left"><span class="text13">&nbsp;</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Free impressions</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[i_free]; ?></span></td>
<td align="left"><span class="text13">&nbsp;Add/take <INPUT class="field1" size="5" maxlength="10" name="add_free"> *</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Clicks balance</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[c_nu]; ?></span></td>
<td align="left"><span class="text13">&nbsp;</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Clicks received</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[c_w]; ?></span></td>
<td align="left"><span class="text13">&nbsp;</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Free clicks</span></td>
<td align="left"><span class="text13"><?PHP echo $stats[c_free]; ?></span></td>
<td align="left"><span class="text13">&nbsp;Add/take <INPUT class="field1" size="5" maxlength="10" name="add_free_cl"> *</span></td>
</tr>
<tr><td align="center" nowrap colspan="3">
<input type="submit" name="co" value="&nbsp;Save&nbsp;" class="button1"><br><br>
<span class="text10">* Enter 100 to add 100 impressions or clicks to this account<br>or -100 to take 100 impressions or clicks from this account</span></td>
</td></tr></form></table></td></tr></table><br>
<?PHP
ad_edit_table($link,$stats,$a[size]);
echo "<a href=\"$s[SCRdetail]?number=$a[number]\">Back to previous screen</a><br><br>";
include('./_footer.txt');
exit;
}

###########################################################################

function save_numbers($a) {
global $s;
check_session ('sponsors');
dq("update $s[pr]stats$a[size] set 
i_free = i_free + '$a[add_free]', i_nu = i_nu + '$a[add_free]', c_free = c_free + '$a[add_free_cl]', c_nu = c_nu + '$a[add_free_cl]'
where number = '$a[number]'",1);
$s[info] = iot('Data has been saved');
account_edit($a);
}

##########################################################################
##########################################################################
##########################################################################

function order_delete($a) {
global $s;
check_session ('sponsors');
$q = dq("select user from $s[pr]s_orders where number = '$a[number]'",1);
$data = mysql_fetch_array($q);
if (!$data[user]) problem("Selected order does not exist");

dq("delete from $s[pr]s_orders where number = '$a[number]'",1);
$q = dq("select count(*) from $s[pr]s_orders where user = '$data[user]'",1);
$orders = mysql_fetch_row($q); $orders = $orders[0];
$q = dq("select count(*) from $s[pr]s_orders where user = '$data[user]' and paylink = ''",1);
$paid = mysql_fetch_row($q); $paid = $paid[0];
dq("update $s[pr]members set s_orders = '$orders', s_paid_ord = '$paid' where number = '$data[user]' and sponsor = '1'",1);

include('./_head.txt');
echo '<br><br><span class="text13blue"><b>Order has been deleted</b></span><br><br><br>
<a href="'.$a[returnto].'">Back to previous page</a>';
include('./_footer.txt');
exit;
}

#########################################################################

function order_paid($number) {
global $s;
check_session ('sponsors');
$q = dq("select * from $s[pr]s_orders where number = '$number'",1);
$data = mysql_fetch_assoc($q);
if (!$data[user]) problem('Selected order does not exist');

dq("update $s[pr]s_orders set paylink = '' where number = '$number'",1);
$q = dq("select count(*) from $s[pr]s_orders where user = '$data[user]'",1);
$orders = mysql_fetch_row($q); $orders = $orders[0];
$q = dq("select count(*) from $s[pr]s_orders where user = '$data[user]' and paylink = ''",1);
$paid = mysql_fetch_row($q); $paid = $paid[0];
dq("update $s[pr]members set s_orders = '$orders', s_paid_ord = '$paid', s_funds = s_funds + '$data[value]' where number = '$data[user]' and sponsor = '1'",1);
$s[info] = iot('Selected order has been marked as paid');
$a[number] = $number;
order_details($a);
}

##########################################################################

function order_details($a) {
global $s;
check_session ('sponsors');
$q = dq("select * from $s[pr]s_orders where number = '$a[number]'",1);
$data = mysql_fetch_array($q);
$q = dq("select descr from $s[pr]s_packs where number = '$data[pack]'",1);
$pack = mysql_fetch_row($q); $pack = $pack[0];
$q = dq("select userid from $s[pr]members where number = '$data[user]'",1);
$user = mysql_fetch_row($q); $user = $user[0];

$data[order_time] = datum(1,$data[order_time]);
if (!$data[paylink]) $paid = '<font color="green">Yes</font>'; else $paid = '<font color="red">No</font>';
$w = $s["w$data[size]"]; $h = $s["h$data[size]"];
include('./_head.txt');
echo $s[info];
echo iot('Order #'.$data[number]);
?>
<table border="0" cellspacing="2" cellpadding="4" class="table1" width="500"><tr><td align="center">
<table border="0" cellspacing="2" cellpadding="4">
<tr>
<td align="left" nowrap><span class="text13">User</span></td>
<td align="left" nowrap><a title="Click to view/edit user details" href="s_detail.php?number=<?PHP echo "$data[user]\">$user"; ?></a></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Pack ordered</span></td>
<td align="left" nowrap><a title="Click to view/edit user details" href="s_tools.php?action=package_edit&number=<?PHP echo "$data[pack]\">$pack"; ?></a></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Price</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $data[price]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Bonus</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $data[bonus]; ?>%</span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Total value</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $data[value]; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Paid</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $paid; ?></span></td>
</tr>
<tr>
<td align="left" nowrap><span class="text13">Ordered</span></td>
<td align="left" nowrap><span class="text13"><?PHP echo $data[order_time]; ?></span></td>
</tr>
<tr><td colspan="2" align="center">
<table border="0" cellpadding="10" cellspacing="0"><tr>
<form METHOD="post" action="s_detail.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="action" value="order_delete">
<input type="hidden" name="returnto" value="<?PHP echo $a[returnto]; ?>">
<td align="center"><input type="submit" name="co" value="Delete this order" class="button1"></td></form>
<?PHP if ($data[paylink]) { ?>
<form METHOD="post" action="s_detail.php">
<input type="hidden" name="number" value="<?PHP echo $a[number]; ?>">
<input type="hidden" name="action" value="order_paid">
<td align="center"><input type="submit" name="co" value="Mark this order as paid" class="button1"></td></form>
<?PHP 
$info = '<span class="text10">If you mark an order as paid, the purchased funds will be added to account of the the user and he/she get the chance to use these funds for clicks or impressions.</span>';
}
echo '</tr></table></td></tr></table>'.$info.'</td></tr></table>';
include('./_footer.txt');
exit();
}

##########################################################################
##########################################################################
##########################################################################

?>