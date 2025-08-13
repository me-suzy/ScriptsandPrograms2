<?

/*
 * $Id: view.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";

$title = "View the " . sex($s);

$total_users = 1;
if(!isset($i))
	$total_users = get_total_users($s);

if($total_users > 0){

$nav_url = "$base_url/index.php?s=" . $s . "&amp;show=view&amp;";

$nav = nav_links($total_users, $pp, $np, $cp, $nav_url) . " " . sex($s);

$nav = <<<EOF
<span class="regular">$nav</span>
EOF;

} else {

$nav = <<<EOF
<table cellpadding="15" cellspacing="0" border="0" width="100%">
<tr>
	<td align="center" class="regular">There are none.</td>
</tr>
</table>
EOF;

}

$final_output .= table($title, $nav);

if(isset($change_order)){
	$order = $order_by;
	session_register("order");
}

$sql_order = convert_order_by($order);

$rate_sql = "
	select
		$tb_users.id as id,
		$tb_users.username as user_name,
		$tb_users.realname as realname,
		$tb_users.description as description,
		$tb_users.age as age,
		$tb_users.sex as sex,
		$tb_users.state as state,
		$tb_users.country as country,
		$tb_users.url as url,
		$tb_users.quote as quote,
		$tb_users.average_rating,
		$tb_users.total_points,
		$tb_users.total_ratings
	from
		$tb_users
	where
		sex = '$s'
	and
		image_status = '1'
	order by
		$sql_order
	limit
		$sr, $pp
";

$rate_query = sql_query($rate_sql);

if(sql_num_rows($rate_query)>0){

while($array = sql_fetch_array($rate_query)){

if($total_users>0){

$title = $array["user_name"];

$image_src = get_image($array["id"]);

$content = <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">
EOF;

if(!isset($i)) $i = 0;

$content .= profile_bar($show, $i, $s, $array["id"]);

$img_src = template("img_src");
eval("\$img_src = \"$img_src\";");

$direct_link = template("direct_link");
eval("\$direct_link = \"$direct_link\";");

$content .= <<<EOF
</td>
</tr>
</table>
$img_src
$direct_link
EOF;

$final_output .= table($title, $content);

$title = "About";

$short_entry = eregi_replace(".gif", "", $array["country"]);
$country = eregi_replace("_", " ", $short_entry);

$about_member = template("about_member");
eval("\$about_member = \"$about_member\";");
$content = $about_member;

$final_output .= table($title, $content);

$content = "";

$title = "Comments";

$gc_sql = "
	select
		*
	from
		$tb_comments
	where
		user_id = '$array[id]'
	order by
		timestamp desc
	limit
		0,10
";

$gc_query = sql_query($gc_sql);

if(sql_num_rows($gc_query)>0){

$content .= <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
EOF;

while($gc_array = sql_fetch_array($gc_query)){
		
if($gc_array["author_id"] != 0){
	
	$gcn_sql = "
		select
			id,
			username,
			sex
		from
			$tb_users
		where
			id = '$gc_array[author_id]'
	";

	$gcn_query = sql_query($gcn_sql);

	$name = sql_result($gcn_query, 0, "username");
	$sing_id = sql_result($gcn_query, 0, "id");
	$sing_sex = sql_result($gcn_query, 0, "sex");

} else $name = "an Anonymous Coward";

$year = substr($gc_array["timestamp"],0,4)+0;
$month = substr($gc_array["timestamp"],4,2)+0;
$date = substr($gc_array["timestamp"],6,2)+0;
$hour = substr($gc_array["timestamp"],8,2)+0;
$minutes = substr($gc_array["timestamp"],10,2)+0;
$seconds = substr($gc_array["timestamp"],12,2)+0;
		
$d = date("F j, Y", mktime($hour, $minutes, $seconds, $month, $date, $year));

if($gc_array["author_id"] != 0){

$check_sql = "
	select
		image_status
	from
		$tb_users
	where
		id = '$gc_array[author_id]'
";
$check_query = sql_query($check_sql);
$check_array = sql_fetch_array($check_query);
if($check_array["image_status"] == 1){
$user_link = <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;i=$sing_id">$name</a>
EOF;
} else {
$user_link = <<<EOF
$name
EOF;
}
} else $user_link = $name;

$comment = template("comment");
eval("\$comment = \"$comment\";");

$content .= <<<EOF
<tr>
<td width="100%">$comment</td>
</tr>
EOF;

}

$content .= "</table>";

} else $content .= "<p class=\"regular\">&nbsp;No comments yet...</p>";

$final_output .= table($title, $content);

}
}
}

/*
 * $Id: view.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>