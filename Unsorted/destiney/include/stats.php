<?php

$file = $cache_path . "/stats.htm";

if(@filemtime($file) < mktime(date("H")-1, abs(date("i")), abs(date("s")), date("m"), date("d"), date("Y"))){

	$yesterday = date("YmdHis",
		mktime(date("H"),date("i"),date("s"),date("m"),date("d")-1,date("Y")));
	$last_week = date("YmdHis",
		mktime(date("H"),date("i"),date("s"),date("m"),date("d")-7,date("Y")));
	$last_month = date("YmdHis",
		mktime(date("H"),date("i"),date("s"),date("m"),date("d")-31,date("Y")));

	$sql = "
		select
			sum(total_points) as count
		from
			$tb_users
	";
	$query = mysql_query($sql) or die(mysql_error());
	$pts_total_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			sum(rating) as count
		from
			$tb_ratings
		where
			timestamp > '$yesterday'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$pts_pd_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			sum(rating) as count
		from
			$tb_ratings
		where
			timestamp > '$last_week'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$pts_pw_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			sum(rating) as count
		from
			$tb_ratings
		where
			timestamp > '$last_month'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$pts_pm_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			sum(total_ratings) as count
		from
			$tb_users
	";
	$query = mysql_query($sql) or die(mysql_error());
	$ra_total_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_ratings
		where
			timestamp > '$yesterday'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$ra_pd_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_ratings
		where
			timestamp > '$last_week'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$ra_pw_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_ratings
		where
			timestamp > '$last_month'
		and
			rater_ip != '127.0.0.1'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$ra_pm_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count($tb_comments.id) as count
		from
			$tb_comments
	";
	$query = mysql_query($sql) or die(mysql_error());
	$cm_total_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count($tb_comment_threads.comment_id) as count
		from
			$tb_comment_threads
		where
			$tb_comment_threads.timestamp > '$yesterday'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$cm_pd_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count($tb_comment_threads.comment_id) as count
		from
			$tb_comment_threads
		where
			$tb_comment_threads.timestamp > '$last_week'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$cm_pw_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count($tb_comment_threads.comment_id) as count
		from
			$tb_comment_threads
		where
			$tb_comment_threads.timestamp > '$last_month'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$cm_pm_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_users
	";
	$query = mysql_query($sql) or die(mysql_error());
	$su_total_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			image_status = 'approved'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$sau_total_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			signup > '$yesterday'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$su_pd_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			signup > '$last_week'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$su_pw_count = number_format((int) mysql_result($query, 0, "count"));

	$sql = "
		select
			count(*) as count
		from
			$tb_users
		where
			signup > '$last_month'
	";
	$query = mysql_query($sql) or die(mysql_error());
	$su_pm_count = number_format((int) mysql_result($query, 0, "count"));

	$title = "Site Stats";

$points_today_link = $ratings_today_link = $comments_today_link = "Today";
$points_past_week_link = $ratings_past_week_link = $comments_past_week_link = $signups_past_week_link = "Past Week";
$points_past_month_link = $ratings_past_month_link = $comments_past_month_link = $signups_past_month_link = "Past Month";
$signups_today_link = "Signups Today";
$total_members_link = "Total Members";
$active_members_link = "Active Members";
$user_type_comments_link = "Total Comments";
$user_type_points_link = "Total Points";
$user_type_ratings_link = "Total Ratings";

if($show_graphs){

$points_today_link = <<<EOF
<a class="small" href="$base_url/stats/stats_points_day.php">Today</a>
EOF;
$points_past_week_link = <<<EOF
<a class="small" href="$base_url/stats/stats_points_week.php">Past Week</a>
EOF;
$points_past_month_link = <<<EOF
<a class="small" href="$base_url/stats/stats_points_month.php">Past Month</a>
EOF;
$ratings_today_link = <<<EOF
<a class="small" href="$base_url/stats/stats_ratings_day.php">Today</a>
EOF;
$ratings_past_week_link = <<<EOF
<a class="small" href="$base_url/stats/stats_ratings_week.php">Past Week</a>
EOF;
$ratings_past_month_link = <<<EOF
<a class="small" href="$base_url/stats/stats_ratings_month.php">Past Month</a>
EOF;
$comments_today_link = <<<EOF
<a class="small" href="$base_url/stats/stats_comments_day.php">Today</a>
EOF;
$comments_past_week_link = <<<EOF
<a class="small" href="$base_url/stats/stats_comments_week.php">Past Week</a>
EOF;
$comments_past_month_link = <<<EOF
<a class="small" href="$base_url/stats/stats_comments_month.php">Past Month</a>
EOF;
$signups_today_link = <<<EOF
<a class="small" href="$base_url/stats/stats_signups_day.php">Signups Today</a>
EOF;
$signups_past_week_link = <<<EOF
<a class="small" href="$base_url/stats/stats_signups_week.php">Past Week</a>
EOF;
$signups_past_month_link = <<<EOF
<a class="small" href="$base_url/stats/stats_signups_month.php">Past Month</a>
EOF;
$total_members_link = <<<EOF
<a class="small" href="$base_url/stats/stats_user_types.php">Total Members</a>
EOF;
$active_members_link = <<<EOF
<a class="small" href="$base_url/stats/stats_active_users.php">Active Members</a>
EOF;
$user_type_comments_link = <<<EOF
<a class="small" href="$base_url/stats/stats_user_type_comments.php">Total Comments</a>
EOF;
$user_type_points_link = <<<EOF
<a class="small" href="$base_url/stats/stats_user_type_points.php">Total Points</a>
EOF;
$user_type_ratings_link = <<<EOF
<a class="small" href="$base_url/stats/stats_user_type_ratings.php">Total Ratings</a>
EOF;

}

$site_stats = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr class="alt1">
	<td class="smallregular" nowrap="nowrap">$user_type_points_link:</td>
	<td align="right" class="smallregular">$pts_total_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$points_today_link:</td>
	<td align="right" class="smallregular">$pts_pd_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$points_past_week_link:</td>
	<td align="right" class="smallregular">$pts_pw_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$points_past_month_link:</td>
	<td align="right" class="smallregular">$pts_pm_count</td>
</tr>
<tr class="alt1">
	<td class="smallregular" nowrap="nowrap">$user_type_ratings_link:</td>
	<td align="right" class="smallregular">$ra_total_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$ratings_today_link:</td>
	<td align="right" class="smallregular">$ra_pd_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$ratings_past_week_link:</td>
	<td align="right" class="smallregular">$ra_pw_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$ratings_past_month_link:</td>
	<td align="right" class="smallregular">$ra_pm_count</td>
</tr>
<tr class="alt1">
	<td class="smallregular" nowrap="nowrap">$user_type_comments_link:</td>
	<td align="right" class="smallregular">$cm_total_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$comments_today_link:</td>
	<td align="right" class="smallregular">$cm_pd_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$comments_past_week_link:</td>
	<td align="right" class="smallregular">$cm_pw_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$comments_past_month_link:</td>
	<td align="right" class="smallregular">$cm_pm_count</td>
</tr>
<tr class="alt1">
	<td class="smallregular" nowrap="nowrap">$total_members_link:</td>
	<td align="right" class="smallregular">$su_total_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$active_members_link:</td>
	<td align="right" class="smallregular">$sau_total_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$signups_today_link:</td>
	<td align="right" class="smallregular">$su_pd_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$signups_past_week_link:</td>
	<td align="right" class="smallregular">$su_pw_count</td>
</tr>
<tr class="alt2">
	<td class="smallregular" nowrap="nowrap">$signups_past_month_link:</td>
	<td align="right" class="smallregular">$su_pm_count</td>
</tr>
</table>
EOF;

	$output = table($title, $site_stats);
	
	$output = eregi_replace("\n", "", $output);
	$output = eregi_replace("\t", "", $output);
	
	if($fp = fopen($file, 'w')) fwrite($fp, $output);
	fclose($fp);

} else {

	if($fp = fopen($file, 'r')){
		$output = fread($fp, filesize ($file));
		fclose ($fp);
	}

}

$final_output .= final_output($output);

?>