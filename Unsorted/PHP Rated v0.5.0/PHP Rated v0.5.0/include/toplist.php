<?

/*
 * $Id: toplist.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

switch($s){
	case "f" :
		$title = "Girls Toplist";
		break;
	default : 
		$title = "Guys Toplist";
}

if(!isset($sort)){$sort = "";}

switch ($sort){
	case "username_asc" : $order_by = "username"; break;
	case "username_desc" : $order_by = "username desc"; break;
	case "age_asc" : $order_by = "age"; break;
	case "age_desc" : $order_by = "age desc"; break;
	case "gender_asc" : $order_by = "sex"; break;
	case "gender_desc" : $order_by = "sex desc"; break;
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
		$tb_users.sex as sex,
		$tb_users.total_ratings as total_ratings,
		$tb_users.total_points as total_points,
		$tb_users.average_rating as average_rating,
		$tb_users.total_comments as total_comments
	from
		$tb_users
	where
		sex = '$s'
	and
		image_status = '1'
	order by
		$order_by
	limit
		0,50
";

$query = sql_query($sql);

$a=0; $b=1; $c=1;

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
<td class="bold">&nbsp;</td>
<td class="bold"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "username")
	$content .= "username_desc";
else
	$content .= "username_asc";

$content .= <<<EOF
"><b>Name</b></a></td>
<td class="bold" align="right"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "age")
	$content .= "age_desc";
else
	$content .= "age_asc";

$content .= <<<EOF
"><b>Age</b></a></td>
<td class="bold" align="right"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "total_comments desc")
	$content .= "total_comments_asc";
else
	$content .= "total_comments_desc";

$content .= <<<EOF
"><b>Comments</b></a></td>
<td class="bold" align="right"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "total_ratings desc")
	$content .= "total_ratings_asc";
else
	$content .= "total_ratings_desc";

$content .= <<<EOF
"><b>Times Rated</b></a></td>
<td class="bold" align="right"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "total_points desc")
	$content .= "total_points_asc";
else
	$content .= "total_points_desc";

$content .= <<<EOF
"><b>Total Points</b></a></td>
<td class="bold" align="right"><a href="$base_url/index.php?$sn=$sid&amp;show=tl&amp;s=$s&amp;sort=
EOF;

if($order_by == "average_rating desc")
	$content .= "avg_rating_asc";
else
	$content .= "avg_rating_desc";

$content .= <<<EOF
"><b>Average Rating</b></a></td>
</tr>
EOF;

if(sql_num_rows($query)>0){

while($array = sql_fetch_array($query)){

if($array["country"] == ""){$array["country"] = "None.gif";}
$short_entry = eregi_replace(".gif", "", $array["country"]);
$country = eregi_replace("_", " ", $short_entry);

$content .= <<<EOF
<tr>
<td class="regular" width="20"><img align="top" border="1" height="13" width="20" src="$base_url/images/flags/$array[country]" hspace="3" vspace="1" alt="$country" title="$country" /></td>
<td class="regular"><a href="$base_url/index.php?$sn=$sid&amp;i=$array[id]" target="_top">$array[username]</a></td>
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
<td class="regular" align="center" colspan="5"><br />There are none.</td>
</tr>
EOF;

}

$content .= <<<EOF
</table><br />
EOF;

$final_output .= table($title, $content);

/*
 * $Id: toplist.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>