<?

/*
 * $Id: main.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

include("./config.php");
include("$include_path/functions.php");
include("$include_path/session.php");

if(!session_is_registered("admin"))
	header("Location: index.php");

include("$include_path/$table_file");
include("$include_path/common.php");

$yesterday = date("YmdHis",
mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")));
$last_week = date("YmdHis",
mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
$last_month = date("YmdHis",
mktime(date("H"),date("i"),date("s"),date("m"),date("d")-31,date("Y")));
$pts_pd_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $yesterday"), 0, "count") + 0;
$pts_pw_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $last_week"), 0, "count") + 0;
$pts_pm_count = sql_result(sql_query("select sum(rating) as count from $tb_ratings where timestamp > $last_month"), 0, "count") + 0;
$ra_pd_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $yesterday"), 0, "count") + 0;
$ra_pw_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $last_week"), 0, "count") + 0;
$ra_pm_count = sql_result(sql_query("select count(*) as count from $tb_ratings where timestamp > $last_month"), 0, "count") + 0;
$cm_pd_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $yesterday"), 0, "count") + 0;
$cm_pw_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $last_week"), 0, "count") + 0;
$cm_pm_count = sql_result(sql_query("select count(*) as count from $tb_comments where timestamp > $last_month"), 0, "count") + 0;
$su_pd_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $yesterday"), 0, "count") + 0;
$su_pw_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $last_week"), 0, "count") + 0;
$su_pm_count = sql_result(sql_query("select count(*) as count from $tb_users where signup > $last_month"), 0, "count") + 0;
$total_user_count = sql_result(sql_query("select count(*) as count from $tb_users"), 0, "count") + 0;
$active_user_count = sql_result(sql_query("select count(*) as count from $tb_users where image_status = '1'"), 0, "count") + 0;
$pending_user_count = sql_result(sql_query("select count(*) as count from $tb_users where image_status = '0'"), 0, "count") + 0;
$inactive_user_count = sql_result(sql_query("select count(*) as count from $tb_users where image_status = '-1'"), 0, "count") + 0;

$styles = template("styles");
eval("\$styles = \"$styles\";");

$content = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
$styles
<script language="javascript">if(top.location == self.location){top.location.href='index.php';}</script>
</head>
<body bgcolor="$page_bg_color">
EOF;

$title = "Site Stats";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="bold" colspan="2">Past 24 Hours:</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Points Today:</td>
<td align="right" class="regular">$pts_pd_count</td>
</tr>
<tr>
<td class="regular">Ratings Today:</td>
<td align="right" class="regular">$ra_pd_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Comments Today:</td>
<td align="right" class="regular">$cm_pd_count</td>
</tr>
<tr>
<td class="regular">Signups Today:</td>
<td align="right" class="regular">$su_pd_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="bold" colspan="2">Past Week:</td>
</tr>
<tr>
<td class="regular">Points this Week:</td>
<td align="right" class="regular">$pts_pw_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Ratings this Week:</td>
<td align="right" class="regular">$ra_pw_count</td>
</tr>
<tr>
<td class="regular">Comments this Week:</td>
<td align="right" class="regular">$cm_pw_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Signups this Week:</td>
<td align="right" class="regular">$su_pw_count</td>
</tr>
<tr>
<td class="bold" colspan="2">Past Month:</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Points this Month:</td>
<td align="right" class="regular">$pts_pm_count</td>
</tr>
<tr>
<td class="regular">Ratings this Month:</td>
<td align="right" class="regular">$ra_pm_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Comments this Month:</td>
<td align="right" class="regular">$cm_pm_count</td>
</tr>
<tr>
<td class="regular">Signups this Month:</td>
<td align="right" class="regular">$su_pm_count</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

$table_left = small_table($title, $table);

$title = "User Info";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr bgcolor="#eeeeee">
<td class="regular"><a href="$base_url/admin/new_images.php?$sn=$sid" target="main">Pending Users</a>:</td>
<td align="right" class="regular">$pending_user_count</td>
</tr>
<tr>
<td class="regular">Active Users:</td>
<td align="right" class="regular">$active_user_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Inactive Users:</td>
<td align="right" class="regular">$inactive_user_count</td>
</tr>
<tr>
<td class="regular">Total Users:</td>
<td align="right" class="regular">$total_user_count</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

$table_right = small_table($title, $table);

$vers = get_version();

$upgrade_link = "";

if($vers > $version){
$upgrade_link = <<<EOF
<a href="http://destiney.com/prated/?show=members" target="_blank">Upgrade</a>&nbsp;&nbsp;
EOF;
}

$title = "pRated Version";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr bgcolor="#eeeeee">
<td class="regular">Current version:</td><td align="right" class="regular">$upgrade_link$vers</td>
</tr>
<tr>
<td class="regular">Your version:</td><td align="right" class="regular">$version</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

$table_right2 = small_table($title, $table);

$content .= <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">$table_left</td>
	<td valign="top">$table_right $table_right2</td>
</tr>
</table>
</body>
</html>
EOF;

echo $content;

/*
 * $Id: main.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>