<?

/*
 * $Id: view_comments.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";

$title = "View Comments";

$tc_sql = "
	select
		count(*) as count
	from
		$tb_comments
";

$tc_query = sql_query($tc_sql);
$tc_array = sql_fetch_array($tc_query);
$tc = $tc_array["count"];

if($tc > 0){

$nav_url = "$base_url/index.php?show=vc&amp;";

$nav = nav_links($tc, $comments_per_page, $np, $cp, $nav_url);

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

$title = "Comments";

$gc_sql = "
	select
		*
	from
		$tb_comments
	order by
		timestamp desc
	limit
		$sr, $comments_per_page
";

$gc_query = sql_query($gc_sql);

if(sql_num_rows($gc_query)>0){

$content .= <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
EOF;

while($gc_array = sql_fetch_array($gc_query)){

$year = substr($gc_array["timestamp"],0,4)+0;
$month = substr($gc_array["timestamp"],4,2)+0;
$date = substr($gc_array["timestamp"],6,2)+0;
$hour = substr($gc_array["timestamp"],8,2)+0;
$minutes = substr($gc_array["timestamp"],10,2)+0;
$seconds = substr($gc_array["timestamp"],12,2)+0;
		
$d = date("F j, Y", mktime($hour, $minutes, $seconds, $month, $date, $year));

if($gc_array["author_id"] > 0){

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

		$author = true;
		$sing_id = $gc_array["author_id"];
		$sing_sex = get_user_sex($gc_array["author_id"]);

	} else {

		$author = false;
	}

	$name = get_user_name($gc_array["author_id"]);

} else {

	$author = false;
	$name = "an Anonymous Coward";
}

if($gc_array["user_id"] > 0){

	$check_sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$gc_array[user_id]'
	";
	$check_query = sql_query($check_sql);
	$check_array = sql_fetch_array($check_query);

	if($check_array["image_status"] == 1){
		
		$user = true;
		$user_sing_id = $gc_array["user_id"];
		$user_sing_sex = get_user_sex($gc_array["user_id"]);
	
	} else {
		
		$user = false;
	}
	
	$user_name = get_user_name($gc_array["user_id"]);

} else {

	$user = false;
	$user_name = "an Anonymous Coward";
}

if($author){

$user_link = <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;i=$sing_id">$name</a>
EOF;

} else {

	$user_link = $name;
}

if($user){

$member_link = <<<EOF
<a href="$base_url/index.php?$sn=$sid&amp;i=$user_sing_id">$user_name</a>
EOF;

} else $member_link = $user_name;

$comment_list = template("comment_list");
eval("\$comment_list = \"$comment_list\";");

$content .= <<<EOF
<tr>
<td width="100%">$comment_list</td>
</tr>
EOF;

}

$content .= "</table>";

} else $content .= "<p class=\"regular\">&nbsp;No comments yet...</p>";

$final_output .= table($title, $content);

/*
 * $Id: view_comments.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>