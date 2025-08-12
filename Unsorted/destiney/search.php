<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

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

$results = "";
$member_search = " >> Member Search";
if(isset($_GET['ss'])){
	$results = " >> Search Results";
$member_search = <<<EOF
 >> <a class="bold" href="$base_url/search.php?u=$_GET[u]&amp;g=$_GET[g]&amp;s=$_GET[s]&amp;c=$_GET[c]&amp;al=$_GET[al]&amp;ah=$_GET[ah]">Member Search</a>
EOF;
}

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a>$member_search$results</td>
</tr>
</table>
<table cellpadding="1" cellspacing="0" border="0" width="100%">
EOF;

if(isset($_GET['ss'])){

$nav_search_username = "";
if(isset($_GET['u']) && strlen($_GET['u'])){
	$nav_search_username = addslashes(trim($_GET['u']));
	$search_username = ".*" . $nav_search_username . ".*";
} else {
	$search_username = ".*";
}

$nav_search_low_age = 18;
if(isset($_GET['al']) && strlen($_GET['al'])){
	$nav_search_low_age = (int) $_GET['al'];
	$search_low_age = $nav_search_low_age;
}

$nav_search_high_age = 88;
if(isset($_GET['ah']) && strlen($_GET['ah'])){
	$nav_search_high_age = (int) $_GET['ah'];
	$search_high_age = $nav_search_high_age;
}

$nav_search_gender = "";
$gender_sql = "";
if(isset($_GET['g']) && strlen($_GET['g'])){
	switch($_GET['g']){
		case "m":
			$gender_sql = "and ( " . get_gender_types_sql("m") . ")";
			$nav_search_gender = "m";
			break;
		case "f":
			$gender_sql = "and ( " . get_gender_types_sql("f") . ")";
			$nav_search_gender = "f";
			break;
	}
}

$nav_search_state = "";
if(isset($_GET['s']) && strlen($_GET['s'])){
	$nav_search_state = $_GET['s'];
	$search_state = "%" . $nav_search_state . "%";
} else {
	$search_state = "%%";
}

$nav_search_country = "";
if(isset($_GET['c']) && strlen($_GET['c'])){
	$nav_search_country = $_GET['c'];
	$search_country = "%" . $nav_search_country . "%";
} else {
	$search_country = "%%";
}

$sort = isset($_GET['sort']) ? $_GET['sort'] : "";

$tc_sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		username REGEXP '$search_username'
	and
		age >= '$search_low_age'
	and
		age <= '$search_high_age'
	$gender_sql
	and
		state like '$search_state'
	and
		country like '$search_country'
	and
		image_status = 'approved'
";

$tc_query = mysql_query($tc_sql) or die(mysql_error());

$tc = (int) mysql_result($tc_query, 0, "count");

if($tc > 0){

$nav_url = "$base_url/search.php?u=" . $nav_search_username . "&amp;g=" . 
	$nav_search_gender . "&amp;s=" . $nav_search_state . "&amp;c=" . 
	$nav_search_country . "&amp;al=" . $nav_search_low_age . "&amp;ah=" . 
	$nav_search_high_age . "&amp;sort=" . $sort . "&amp;ss=1&amp;";

$nav = toplist_nav_links($tc, $search_users_per_page, $np, $ccp, $nav_url);

$nav = <<<EOF
<span class="regular">$nav</span>
EOF;

}

switch ($sort){
	case "username_asc" : $order_by = "username"; break;
	case "username_desc" : $order_by = "username desc"; break;
	case "age_asc" : $order_by = "age"; break;
	case "age_desc" : $order_by = "age desc"; break;
	case "user_type_asc" : $order_by = "user_type"; break;
	case "user_type_desc" : $order_by = "user_type desc"; break;
	case "total_ratings_asc" : $order_by = "total_ratings"; break;
	case "total_ratings_desc" : $order_by = "total_ratings desc"; break;
	case "total_comments_asc" : $order_by = "total_comments"; break;
	case "total_comments_desc" : $order_by = "total_comments desc"; break;
	case "total_points_asc" : $order_by = "total_points"; break;
	case "total_points_desc" : $order_by = "total_points desc"; break;
	case "avg_rating_asc" : $order_by = "average_rating"; break;
	case "avg_rating_desc" : $order_by = "average_rating desc"; break;
	default : $order_by = "average_rating desc"; break;
}

$sql = "
	select
		$tb_users.id as id,
		$tb_users.username as username,
		$tb_users.realname as realname,
		$tb_users.country as country,
		$tb_users.age as age,
		$tb_users.user_type as user_type,
		$tb_users.total_ratings as total_ratings,
		$tb_users.total_points as total_points,
		$tb_users.average_rating as average_rating,
		$tb_users.total_comments as total_comments
	from
		$tb_users
	where
		username REGEXP '$search_username'
	and
		age >= '$search_low_age'
	and
		age <= '$search_high_age'
	$gender_sql
	and
		state like '$search_state'
	and
		country like '$search_country'
	and
		image_status = 'approved'
	order by
		$order_by
	limit
		$csr, $search_users_per_page
";

$time_start = getmicrotime();

$query = mysql_query($sql) or die(mysql_error());

$time_end = getmicrotime();

$time = number_format($time_end - $time_start, 4);

$a=0; $b=1; $c=1;

$content .= <<<EOF
<tr>
<td class="bold" align="right">Search time: $time seconds&nbsp;
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td bgcolor="black">
<table cellpadding="3" cellspacing="1" border="0" width="100%">
EOF;

if(isset($nav) && strlen($nav)){
$content .= <<<EOF
<tr class="alt2">
	<td class="bold" colspan="7">$nav</td>
</tr>
EOF;
}

if(mysql_num_rows($query)>0){

$content .= <<<EOF
<tr class="alt1">
<td class="bold">&nbsp;</td>
<td class="bold"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "username")
	$content .= "username_desc";
else
	$content .= "username_asc";

$content .= <<<EOF
">Name</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "age")
	$content .= "age_desc";
else
	$content .= "age_asc";

$content .= <<<EOF
">Age</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "total_comments desc")
	$content .= "total_comments_asc";
else
	$content .= "total_comments_desc";

$content .= <<<EOF
">Comments</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "total_ratings desc")
	$content .= "total_ratings_asc";
else
	$content .= "total_ratings_desc";

$content .= <<<EOF
">Times Rated</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "total_points desc")
	$content .= "total_points_asc";
else
	$content .= "total_points_desc";

$content .= <<<EOF
">Total Points</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/search.php?u=$nav_search_username&amp;g=$nav_search_gender&amp;s=$nav_search_state&amp;c=$nav_search_country&amp;al=$nav_search_low_age&amp;ah=$nav_search_high_age&amp;ss=1&amp;sort=
EOF;

if($order_by == "average_rating desc")
	$content .= "avg_rating_asc";
else
	$content .= "avg_rating_desc";

$content .= <<<EOF
">Average</a></td>
</tr>
EOF;

$i = 0;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt1" : "alt2";
$i++;

if($array["country"] == ""){$array["country"] = "None.gif";}
$short_entry = eregi_replace(".gif", "", $array["country"]);
$country = eregi_replace("_", " ", $short_entry);

$content .= <<<EOF
<tr class="$alt">
<td class="regular" width="20"><img align="top" border="1" height="13" width="20" src="$base_url/images/flags/$array[country]" hspace="3" vspace="1" alt="$country" title="$country"></td>
<td class="regular"><a href="$base_url/?i=$array[id]" target="_top">$array[username]</a></td>
<td class="regular" align="right">$array[age]</td>
<td class="regular" align="right">$array[total_comments]</td>
<td class="regular" align="right">$array[total_ratings]</td>
<td class="regular" align="right">$array[total_points]</td>
<td class="regular" align="right">$array[average_rating]</td>
</tr>
EOF;
		
$a++; $b++;
}

} else {

$content .= <<<EOF
<tr class="alt1">
<td class="regular" align="center" colspan="7"><br><br><br>No results for your query.<br><br><br><br></td>
</tr>
EOF;

}

if(isset($nav) && strlen($nav)){
$content .= <<<EOF
<tr class="alt2">
	<td class="bold" colspan="7">$nav</td>
</tr>
EOF;
}

} else {

$states_list = get_states_list(isset($_GET['s']) ? $_GET['s'] : "");
$flags_list = getFlagList($base_path . "/images/flags", isset($_GET['c']) ? $_GET['c'] : "");

$low_age_options = get_age_options(isset($_GET['al']) ? $_GET['al'] : $low_age_limit);
$high_age_options = get_age_options(isset($_GET['ah']) ? $_GET['ah'] : $high_age_limit);

$username = isset($_GET['u']) ? $_GET['u'] : "";

$f_gender = (isset($_GET['g']) && $_GET['g'] == "f") ? " checked" : "";
$m_gender = (isset($_GET['g']) && $_GET['g'] == "m") ? " checked" : "";
$a_gender = (!isset($_GET['g']) || $_GET['g'] == "" || (!strlen($m_gender) && !strlen($f_gender))) ? " checked" : "";

$content .= <<<EOF
<tr>
<td>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr class="alt1">
	<td class="bold" align="center"><br>
	<table cellpadding="5" cellspacing="0" border="0">
	<form method="GET" action="$base_url/search.php">
	<tr>
		<td colspan="2" class="bold"><br><br>Search on any or all of the following fields:<br><br></td>
	</tr>
	<tr>
		<td class="bold" align="right">Username:</td>
		<td><input class="input" type="text" name="u" size="16" value="$username"></td>
	</tr>
	<tr>
		<td class="bold" align="right">Age:</td>
		<td class="regular">from <select class="input" name="al">$low_age_options</select> to <select class="input" name="ah">$high_age_options</select></td>
	</tr>
	<tr>
		<td class="bold" align="right">Gender:</td>
		<td class="regular"><input type="radio" name="g" value=""$a_gender> Any <input type="radio" name="g" value="f"$f_gender> Female <input type="radio" name="g" value="m"$m_gender> Male</td>
	</tr>
	<tr>
		<td class="bold" align="right">State/Province:</td>
		<td><select class="input" name="s"><option value="">Any State</option>$states_list</select></td>
	</tr>
	<tr>
		<td class="bold" align="right">Country:</td>
		<td><select class="input" name="c"><option value="">Any Country</option>$flags_list</select></td>
	</tr>
	<tr>
		<td colspan="2" align="right">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="2" align="right"><input class="button" type="submit" name="ss" value="Search ->"></td>
	</tr>
	</form>
	</table>
	<br><br>
	</td>
</tr>
EOF;

}

$content .= <<<EOF
</table>
</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

$final_output .= table("Member Search", $content);

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