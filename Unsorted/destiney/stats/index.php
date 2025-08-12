<?php
include("../admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!$show_graphs){
	header("Location: $base_url/");
}

$final_output .= <<<FO
</head>
<body bgcolor="$page_bg_color">
<table border="0" cellpadding="0" cellspacing="0" width="$total_width" align="center">
<tr>
	<td colspan="3" width="100%" valign="bottom">
	<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr>
		<td valign="middle" class="dc">$title_image</td>
		<td align="right" valign="bottom">
FO;

include("$include_path/logged_status.php");

$final_output .= <<<FO
		</td>
	</tr>
	</table>
	</td>
</tr>
<tr>
<td width="$left_col_width" valign="top">
FO;

include("$include_path/left.php");

$final_output .= <<<FO
</td>
<td width="$main_col_width" valign="top">
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

$faqs = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Site Stats</td>
</tr>
</table>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold"><br>
	&nbsp;Site Stats:
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="regular" bgcolor="black">
		<table cellpadding="5" cellspacing="1" border="0">
		<tr class="alt2">
			<td class="alt2">&nbsp;</td>
			<td class="bold">Points</td>
			<td class="bold">Ratings</td>
			<td class="bold">Comments</td>
			<td class="bold">Members</td>
		</tr>
		<tr class="alt1">
			<td class="alt2"><span class="bold">Today</span></td>
			<td class="regular"><a href="$base_url/stats/stats_points_day.php">Points Past Day</a></td>
			<td class="regular"><a href="$base_url/stats/stats_ratings_day.php">Ratings Past Day</a></td>
			<td class="regular"><a href="$base_url/stats/stats_comments_day.php">Comments Past Day</a></td>
			<td class="regular"><a href="$base_url/stats/stats_signups_day.php">Signups Past Day</a></td>
		</tr>
		<tr class="alt1">
			<td class="alt2"><span class="bold">Week</span></td>
			<td class="regular"><a href="$base_url/stats/stats_points_week.php">Point Past Week</a></td>
			<td class="regular"><a href="$base_url/stats/stats_ratings_week.php">Ratings Past Week</a></td>
			<td class="regular"><a href="$base_url/stats/stats_comments_week.php">Comments Past Week</a></td>
			<td class="regular"><a href="$base_url/stats/stats_signups_week.php">Signups Past Week</a></td>
		</tr>
		<tr class="alt1">
			<td class="alt2"><span class="bold">Month</span></td>
			<td class="regular"><a href="$base_url/stats/stats_points_month.php">Points Past Month</a></td>
			<td class="regular"><a href="$base_url/stats/stats_ratings_month.php">Ratings Past Month</a></td>
			<td class="regular"><a href="$base_url/stats/stats_comments_month.php">Comments Past Month</a></td>
			<td class="regular"><a href="$base_url/stats/stats_signups_month.php">Signups Past Month</a></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	<br><br>
	&nbsp;Member Stats:
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="regular" bgcolor="black">
		<table cellpadding="5" cellspacing="1" border="0">
		<tr class="alt2">
			<td class="alt2">&nbsp;</td>
			<td class="bold">Types</td>
			<td class="bold">Activity</td>
			<td class="bold">Ratings</td>
			<td class="bold">Points</td>
			<td class="bold">Comments</td>
		</tr>
		<tr class="alt1">
			<td class="alt2"><span class="bold">Members</span></td>
			<td class="regular"><a href="$base_url/stats/stats_user_types.php">Member Types</a></td>
			<td class="regular"><a href="$base_url/stats/stats_active_users.php">Member Activity</a></td>
			<td class="regular"><a href="$base_url/stats/stats_user_type_ratings.php">Member Ratings</a></td>
			<td class="regular"><a href="$base_url/stats/stats_user_type_points.php">Member Points</a></td>
			<td class="regular"><a href="$base_url/stats/stats_user_type_comments.php">Member Comments</a></td>
		</tr>
		</table>
		</td>
	</tr>
	</table>
	<br><br><br>
	</td>
</tr>
</table>
EOF;

$final_output .= table("Stats", $faqs);

$final_output .= <<<FO
</td>
</tr>
FO;

$final_output .= <<<FO
</table>
FO;

include("$include_path/copyright.php");

$final_output .= <<<FO
</td>
<td width="$right_col_width" valign="top">
FO;

include("$include_path/right.php");

$final_output .= <<<FO
</td>
</tr>
</table>
</body>
</html>
FO;

$final_output = final_output($final_output);

echo $final_output;

?>