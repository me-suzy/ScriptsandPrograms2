<?php
include("./admin/config.php");
include("$include_path/common.php");

if(isset($_GET['i']) && $_GET['i'] > 0){
	$i = $_GET['i'];
} else {
	header("Location: $base_url/");
	exit();
}

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

$nav="";

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
FO;

$title = "Comments";

$tc_sql = "
	select
		count(*) as count
	from
		$tb_comments
	where
		pid = 0
	and 
		user_id = '$i'
";

$tc_query = mysql_query($tc_sql) or die(mysql_error());
$tc = (int) mysql_result($tc_query, 0, "count");

if($tc > 0){

$nav_url = "$base_url/my_comments.php?i=$i&";

$nav = comment_nav_links($tc, $comments_per_user_page, $np, $ccp, $nav_url);

$nav = <<<EOF
<span class="regular">$nav</span>
EOF;

}

$sql = "
	select
		$tb_comments.id as id,
		$tb_comments.user_id as user_id,
		$tb_comments.subject as subject,
		$tb_comments.comment as comment,
		$tb_comments.author_id as author_id,
		count($tb_comment_views.comment_id) as comment_views,
		$tb_users.username as author_name,
		$tb_users.image_status as image_status
	from
		$tb_comments
	left join
		$tb_comment_threads
	on
		$tb_comments.id = $tb_comment_threads.comment_id
	left join
		$tb_comment_views
	on
		$tb_comments.id = $tb_comment_views.comment_id
	left join
		$tb_users
	on
		$tb_comments.author_id = $tb_users.id
	where
		$tb_comments.pid = 0
	and 
		$tb_comments.user_id = '$i'
	group by
		$tb_comments.id
	order by
		$tb_comment_threads.timestamp desc
	limit
		$csr, $comments_per_user_page
";

$query = mysql_query($sql) or die(mysql_error());

$user_name = get_username($i);

if(check_approved_image($i)){
$user_name = <<<EOF
<a class="bold" href="$base_url/?i=$i">$user_name</a>
EOF;
}

$image_src = get_image($i);

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $user_name >> My Comments</td>
</tr>
</table>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
	<td>
EOF;

include("$include_path/vote_bar.php");
$content .= $vote_bar;

$content .= <<<EOF
<table cellpadding="5" cellspacing="1" border="0">
<tr>
	<td class="regular">$image_src</td>
</tr>
</table>
<table cellpadding="5" cellspacing="1" border="0">
<tr>
	<td>
EOF;

include("$include_path/profile_bar.php");
$content .= $profile_bar . "<br>";

$content .= <<<EOF
</td>
</tr>
</table>
EOF;

if(mysql_num_rows($query)){

$comments_table = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr class="alt2">
	<td class="regular" colspan="5">$nav</td>
</tr>
<tr>
	<td class="bold-rv">Comment Subject</td>
	<td class="bold-rv" align="center" width="1%">Replies</td>
	<td class="bold-rv" align="center" width="1%">Views</td>
	<td class="bold-rv" align="center" width="1%" nowrap>Starter</td>
</tr>
EOF;

$i = 0;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt1" : "alt2";
$i++;

$comment_name = get_comment_subject($array["id"]);

$comment_starter_id = get_comment_starter_id($array["id"]);

$comment_starter_link = $ac;

if($comment_starter_id > 0){
	$comment_starter = get_username($comment_starter_id);

	$comment_starter_link = $comment_starter;

	if(check_approved_image($comment_starter_id)){
$comment_starter_link = <<<EOF
<a href="$base_url/?i=$comment_starter_id">$comment_starter</a>
EOF;
	}

}

$comment_replies_count = get_comment_replies_count($array["id"]);
$comment_views_count = get_comment_views_count($array["id"]);

$comments_table .= <<<EOF
<tr class="$alt">
	<td class="regular"><a href="$base_url/view_my_comment.php?c=$array[id]">$comment_name</a></td>
	<td class="regular" align="center">$comment_replies_count</td>
	<td class="regular" align="center">$comment_views_count</td>
	<td class="regular" align="center" nowrap>$comment_starter_link</td>
</tr>
EOF;

}

$comments_table .= <<<EOF
	<tr>
		<td class="bold-rv">Comment Subject</td>
		<td class="bold-rv" align="center" width="1%">Replies</td>
		<td class="bold-rv" align="center" width="1%">Views</td>
		<td class="bold-rv" align="center" width="1%" nowrap>Starter</td>
	</tr>
	<tr class="alt2">
		<td class="regular" colspan="5">$nav</td>
	</tr>
</table>
EOF;

} else {

$comments_table = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr class="alt1">
	<td class="regular" align="center" colspan="4"><br><br>No comments yet.<br><br><br></td>
</tr>
</table>
EOF;

}

$content .= table_no_title($comments_table);

$content .= <<<EOF
</td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

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