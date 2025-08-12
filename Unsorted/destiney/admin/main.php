<?php
include("./config.php");
include("$include_path/common.php");

check_login();

if(!isset($_SESSION['cq']) || !$_SESSION['cq']){ 
	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			image_status = 'queued'
	";
	$query = mysql_query($sql) or die(mysql_error());
	if(mysql_result($query, 0, "count")){
		header("Location: $base_url/admin/new_images.php");
		exit();
	}
	$_SESSION['cq'] = true;
}

include("$include_path/$table_file");

$yesterday = date("YmdHis",
	mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")));
$last_week = date("YmdHis",
	mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
$last_month = date("YmdHis",
	mktime(date("H"),date("i"),date("s"),date("m"),date("d")-31,date("Y")));

$sql = "
	select
		sum(rating) as count
	from
		$tb_ratings
	where
		timestamp > '$yesterday'
";
$query = mysql_query($sql) or die(mysql_error());
$pts_pd_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		sum(rating) as count
	from
		$tb_ratings
	where
		timestamp > '$last_week'
";
$query = mysql_query($sql) or die(mysql_error());
$pts_pw_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		sum(rating) as count
	from
		$tb_ratings
	where
		timestamp > '$last_month'
";
$query = mysql_query($sql) or die(mysql_error());
$pts_pm_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_ratings
	where
		timestamp > '$yesterday'
";
$query = mysql_query($sql) or die(mysql_error());
$ra_pd_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_ratings
	where
		timestamp > '$last_week'
";
$query = mysql_query($sql) or die(mysql_error());
$ra_pw_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_ratings
	where
		timestamp > '$last_month'
";
$query = mysql_query($sql) or die(mysql_error());
$ra_pm_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count($tb_comment_threads.comment_id) as count
	from
		$tb_comment_threads
	where
		$tb_comment_threads.timestamp > '$yesterday'
";
$query = mysql_query($sql) or die(mysql_error());
$cm_pd_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count($tb_comment_threads.comment_id) as count
	from
		$tb_comment_threads
	where
		$tb_comment_threads.timestamp > '$last_week'
";
$query = mysql_query($sql) or die(mysql_error());
$cm_pw_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count($tb_comment_threads.comment_id) as count
	from
		$tb_comment_threads
	where
		$tb_comment_threads.timestamp > '$last_month'
";
$query = mysql_query($sql) or die(mysql_error());
$cm_pm_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		signup > '$yesterday'
";
$query = mysql_query($sql) or die(mysql_error());
$su_pd_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		signup > '$last_week'
";
$query = mysql_query($sql) or die(mysql_error());
$su_pw_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		signup > '$last_month'
";
$query = mysql_query($sql) or die(mysql_error());
$su_pm_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from 
		$tb_users
";
$query = mysql_query($sql) or die(mysql_error());
$total_user_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		image_status = 'approved'
";
$query = mysql_query($sql) or die(mysql_error());
$active_user_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		image_status = 'queued'
";
$query = mysql_query($sql) or die(mysql_error());
$pending_user_count = (int) mysql_result($query, 0, "count");

$sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		image_status = 'disabled'
";
$query = mysql_query($sql) or die(mysql_error());
$inactive_user_count = (int) mysql_result($query, 0, "count");

$final_output = <<<EOF
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title></title>
EOF;

include("$include_path/styles.php");

$final_output .= <<<EOF
</head>
<body bgcolor="$page_bg_color">
EOF;

$title = "Site Stats";

$table = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="300">
<tr>
<td>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr bgcolor="#eeeeee">
<td class="bold" colspan="2">User Info:</td>
</tr>
<tr>
<td class="regular"><a href="$base_url/admin/new_images.php" target="main">Pending Users</a>:</td>
<td align="right" class="regular">$pending_user_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Active Users:</td>
<td align="right" class="regular">$active_user_count</td>
</tr>
<tr>
<td class="regular">Inactive Users:</td>
<td align="right" class="regular">$inactive_user_count</td>
</tr>
<tr bgcolor="#eeeeee">
<td class="regular">Total Users:</td>
<td align="right" class="regular">$total_user_count</td>
</tr>
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

$main_table = small_table($title, $table);

$final_output .= <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td valign="top">$main_table</td>
</tr>
</table>
</body>
</html>
EOF;

echo $final_output;

?>