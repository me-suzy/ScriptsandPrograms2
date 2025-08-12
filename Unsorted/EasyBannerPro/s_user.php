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
$s[sponsor] = 1; $s[SCRmember] = 's_user.php';

if ($HTTP_GET_VARS[action] == 'remind') remind('');
if ($HTTP_POST_VARS[action] == 'remind') remind($HTTP_POST_VARS);
if ((!$HTTP_POST_VARS) AND (!$HTTP_GET_VARS)) page_from_template('s_login.html',$s);

if ($HTTP_GET_VARS) $HTTP_POST_VARS = $HTTP_GET_VARS;
check_login($HTTP_POST_VARS);
if ($HTTP_GET_VARS[action] == 'banner_delete') banner_delete($HTTP_GET_VARS);
switch ($HTTP_POST_VARS[action]) {
case 'user_edit'			: user_edit($HTTP_POST_VARS[userid]);
case 'user_edited'			: user_edited($HTTP_POST_VARS);
case 'account_enable'		: account_enable($HTTP_POST_VARS);
case 'account_disable'		: account_disable($HTTP_POST_VARS);
case 'reset_ad_statistic'	: reset_ad_statistic($HTTP_POST_VARS);
case 'ad_edited'			: ad_edited($HTTP_POST_VARS);
case 'funds_home'			: funds_home();
case 'order_created'		: order_created($HTTP_POST_VARS);
case 'orders_show'			: orders_show($HTTP_POST_VARS);
case 'funds_refund'			: funds_refund($HTTP_POST_VARS[refund]);
case 'funds_use'			: funds_use($HTTP_POST_VARS);
}
if (eregi('statistic_day_by_day_',$HTTP_POST_VARS[action])) statistic_day_by_day($HTTP_POST_VARS);
if (eregi('statistic_month_by_month_',$HTTP_POST_VARS[action])) statistic_month_by_month($HTTP_POST_VARS);
if (eregi('statistic_',$HTTP_POST_VARS[action])) statistic_main($HTTP_POST_VARS);
if (eregi('ad_',$HTTP_POST_VARS[action])) ad_edit($HTTP_POST_VARS);
page_from_template('s_home.html',$s);


########################################################################
########################################################################
########################################################################

function funds_home() {
global $s,$m;
$q = dq("select * from $s[pr]s_packs order by price",1);
while ($data=mysql_fetch_assoc($q))
$s[packages] .= "<option value=\"$data[number]\">$data[descr]</option>\n";
$q = dq("select s_funds as balance from $s[pr]members where number = '$s[number]'",1);
$funds = mysql_fetch_assoc($q);
for ($x=1;$x<=3;$x++)
{ $q = dq("select c_nu as clicks$x, i_nu as imp$x from $s[pr]stats$x where number = '$s[number]'",1);
  $balance[$x] = mysql_fetch_assoc($q);
  $balance[$x]['imp'.$x] = round($balance[$x]['imp'.$x]);
}
$s = array_merge($s,$balance[1],$balance[2],$balance[3],$funds);
page_from_template('s_funds.html',$s);
}

########################################################################

function order_created($form) {
global $s,$m;
$q = dq("select * from $s[pr]s_packs where number = '$form[package]'",1);
$package = mysql_fetch_assoc($q);
dq("insert into $s[pr]s_orders values (NULL,'$s[number]','$package[number]','$package[descr]','$package[price]','$package[bonus]','$package[value]','$package[payhtml]','','$s[cas]')",1);
dq("update $s[pr]members set s_orders = s_orders + 1, s_last = '$s[cas]' where number = '$s[number]'",1);
$q = dq("select catname from $s[pr]categories where size = '$size' AND catid = '$form[category]'",1);
$x=mysql_fetch_row($q); $form[category]=$x[0];
$package[payhtml] = unreplace_once_html($package[payhtml]);
$s = array_merge ($package,$s);
$s[email] = $s[adminemail];
mail_from_template('email_admin_s_order.txt',$s);
page_from_template('s_ordered.html',$s);
}

########################################################################

function funds_use($form) {
global $s,$m;
$q = dq("select c_nu, i_nu from $s[pr]stats$form[size] where number = $s[number]",1);
$x = mysql_fetch_assoc($q);
if ($form[what]=='clicks')
{ $price = ($s['pr_clicks'.$form[size]]/100)*$form[number]; $w = 'clicks';
  if ($x[i_nu]>0) $problem[] = $m[im_only]; $form[what] = 'c';
}
elseif ($form[what]=='imp')
{ $price = ($s["pr_imp$form[size]"]/1000)*$form[number]; $w = 'impressions';
  if ($x[c_nu]>0) $problem[] = $m[cl_only]; $form[what] = 'i';
}
else problem($m[an_error]);
if (!$price) problem($m[banned]);
$q = dq("select s_funds from $s[pr]members where number = '$s[number]'",1);
$x = mysql_fetch_row($q);
if ($x[0]<$price) $problem[] = $m[malo]; 
if ($problem) { $s[info] = eot($m[errors],implode('<br>',$problem)); funds_home(); }
dq("update $s[pr]members set s_funds = s_funds - '$price' where number = $s[number]",1);
dq("update $s[pr]stats$form[size] set $form[what]_nu = $form[what]_nu + '$form[number]' where number = $s[number]",1);
$s[info] = iot($form[number].' '.$w.' '.$m[added].' '.$form[size]);
funds_home();
}

########################################################################

function funds_refund($refund) {
global $s,$m;
$size = ereg_replace("[clicks_|imp_]",'',$refund); $what = ereg_replace("_[123]",'',$refund);
if ($what=='clicks') $what1 = 'c_nu'; elseif ($what=='imp') $what1 = 'i_nu';
if ((!$size) OR (!$what)) problem($m[an_error]);
$q = dq("select $what1 from $s[pr]stats$size where number = '$s[number]'",1);
$x = mysql_fetch_row($q);
if ($what=='clicks') { $price = ($s["pr_clicks$size"]/100)*$x[0]; $what = 'c_nu'; }
if ($what=='imp') { $price = ($s["pr_imp$size"]/1000)*$x[0]; $what = 'i_nu'; }
dq("update $s[pr]stats$size set $what1 = '0' where number = '$s[number]'",1);
dq("update $s[pr]members set s_funds = s_funds + '$price' where number = '$s[number]'",1);
$s[info] = iot($m[refunded].' '.$price);
funds_home();
}

########################################################################

function orders_show($form) {
global $s,$m;
$q = dq("select * from $s[pr]s_orders where user = '$s[number]' order by order_time desc",1);
while ($orders = mysql_fetch_assoc($q))
{ if (!$orders[paylink]) $orders[status] = $m[i_paid];
  else { $orders[status] = $m[i_nopaid]; $orders[paylink] = unreplace_once_html($orders[paylink]); }
  $orders[date] = datum(0,$orders[time]);
  $form[orders] .= parse_part('s_order.txt',$orders);
}
page_from_template('s_orders.html',$form);
}

?>