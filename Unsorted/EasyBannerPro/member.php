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
$s[sponsor] = 0; $s[SCRmember] = 'member.php';

if ($HTTP_GET_VARS[action] == 'remind') remind('');
if ($HTTP_POST_VARS[action] == 'remind') remind($HTTP_POST_VARS);
if ((!$HTTP_POST_VARS) AND (!$HTTP_GET_VARS)) page_from_template('u_login.html',$s);

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
case 'impressions_move'		: impressions_move();
case 'impressions_moved'	: impressions_moved($HTTP_POST_VARS);
}

if (eregi('statistic_day_by_day_',$HTTP_POST_VARS[action])) statistic_day_by_day($HTTP_POST_VARS);
if (eregi('statistic_month_by_month_',$HTTP_POST_VARS[action])) statistic_month_by_month($HTTP_POST_VARS);
if (eregi('statistic_',$HTTP_POST_VARS[action])) statistic_main($HTTP_POST_VARS);
if (eregi('ad_',$HTTP_POST_VARS[action])) ad_edit($HTTP_POST_VARS);
if (eregi('html_show_',$HTTP_POST_VARS[action])) html_show($HTTP_POST_VARS);
if ($s[allow_move]) $s[move_impressions] = parse_part('u_move_impressions.txt',$s);
page_from_template('u_home.html',$s);


########################################################################
########################################################################
########################################################################

function impressions_move() {
global $s;
for ($x=1;$x<=3;$x++)
{ $q = dq("select i_nu from $s[pr]stats$x where number = '$s[number]'",1);
  $y = mysql_fetch_row($q); if ($y[0]) $s["available$x"] = floor($y[0]); else $s["available$x"] = 0;
}
page_from_template('u_move_impressions.html',$s);
exit;
}

########################################################################

function impressions_moved($data) {
global $s,$m;
if (!$s[allow_move]) page_from_template('u_home.html',$s);
$q = dq("select i_nu from $s[pr]stats$data[size] where number = '$s[number]'",1);
$x = mysql_fetch_row($q);
if ($data[move]>$x[0]) $data[move] = $x[0]; $data[move] = floor($data[move]);
if (!$data[move]) impressions_move();
$moved = round(($data[move]/$s['move'.$data[size]])*$s['move'.$data[to_size]],2);
dq("update $s[pr]stats$data[size] set i_nu = i_nu - '$data[move]', i_move_out = i_move_out + '$data[move]' where number = '$s[number]'",1);
dq("update $s[pr]stats$data[to_size] set i_nu = i_nu + '$moved', i_move_in = i_move_in + '$moved' where number = '$s[number]'",1);
$s[info] = iot($data[move].' '.$m[i_imp_taken].' '.$data[size].', '.$moved.' '.$m[i_imp_moved].' '.$data[to_size]);
impressions_move();
exit;
}

########################################################################
########################################################################
########################################################################

function html_show($form) {
global $s;
// stejne pro B a T
$size = $form[size] = eregi_replace('html_show_','',$form[action]);
if ($s["logoleft$size"])
{ $form[width] = $s["w$size"] + $s["logow$size"]; $form[height] = $s["h$size"]; }
else
{ $form[width] = $s["w$size"]; $form[height] = $s["h$size"] + $s["logoh$size"]; }
$form[workfile_iframe]="$s[phpurl]/work.php?n=$s[number]&size=$size";
$form[workfile_java]="$s[phpurl]/work.php?n=$s[number]&size=$size&j=1&code=";
$form[html] = htmlspecialchars(parse_part('html.txt',$form));
page_from_template('u_html.html',$form);
}

########################################################################
########################################################################
########################################################################

?>