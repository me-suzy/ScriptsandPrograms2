<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");

$user_type_id = 0;

if(isset($_GET['ut'])){
	$user_type_id = $_GET['ut'];
} else {	
	header("Location: $base_url/");
	exit();
}

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

$user_type = get_user_type($user_type_id);

$sort = isset($_GET['sort']) ? $_GET['sort'] : "";

$tc_sql = "
	select
		count(*) as count
	from
		$tb_users
	where
		user_type = '$user_type_id'
	and
		image_status = 'approved'
";

$tc_query = mysql_query($tc_sql) or die(mysql_error());
$tc = (int) mysql_result($tc_query, 0, "count");

if($tc > 0){

$nav_url = "$base_url/toplist.php?ut=$user_type_id&amp;sort=$sort&amp;";

$nav = toplist_nav_links($tc, $toplists_users_per_page, $np, $ccp, $nav_url);

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
		user_type = '$user_type_id'
	and
		image_status = 'approved'
	order by
		$order_by
	limit
		$csr, $toplists_users_per_page
";

$query = mysql_query($sql) or die(mysql_error());

$a=0; $b=1; $c=1;

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $user_type Toplist</td>
</tr>
</table>
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
<td>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td bgcolor="black">
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr class="alt2">
	<td class="bold" colspan="7">$nav</td>
</tr>
<tr class="alt1">
<td class="bold">&nbsp;</td>
<td class="bold"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "username")
	$content .= "username_desc";
else
	$content .= "username_asc";

$content .= <<<EOF
">Name</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "age")
	$content .= "age_desc";
else
	$content .= "age_asc";

$content .= <<<EOF
">Age</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "total_comments desc")
	$content .= "total_comments_asc";
else
	$content .= "total_comments_desc";

$content .= <<<EOF
">Comments</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "total_ratings desc")
	$content .= "total_ratings_asc";
else
	$content .= "total_ratings_desc";

$content .= <<<EOF
">Times Rated</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "total_points desc")
	$content .= "total_points_asc";
else
	$content .= "total_points_desc";

$content .= <<<EOF
">Total Points</a></td>
<td class="bold" align="right"><a class="bold" href="$base_url/toplist.php?ut=$user_type_id&amp;sort=
EOF;

if($order_by == "average_rating desc")
	$content .= "avg_rating_asc";
else
	$content .= "avg_rating_desc";

$content .= <<<EOF
">Average</a></td>
</tr>
EOF;

if(mysql_num_rows($query)>0){

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
<tr>
<td class="regular" align="center" colspan="5"><br>There are none.</td>
</tr>
EOF;

}

$content .= <<<EOF
<tr class="alt2">
	<td class="bold" colspan="7">$nav</td>
</tr>
</table>
</td>
</tr>
</table></td>
</tr>
</table>
EOF;

$final_output .= table($user_type . " Toplist", $content);

$final_output .= <<<FO
</td>
</tr>
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