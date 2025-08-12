<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

$i = 0;
$nav = "";

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

$comment_id = isset($_GET['c']) ? $_GET['c'] : 0;

tally_comment_view($comment_id, $_SERVER["REMOTE_ADDR"]);

$comment_subject = get_comment_subject($comment_id);

$comments_in_comment = get_comments_in_comment_count($comment_id);

$title = "View My Comment :: " . $comment_subject;

if($comments_in_comment  > $comments_replies_per_page){
	$nav_url = "$base_url/view_my_comment.php?c=$comment_id&";
	$nav = comment_nav_links($comments_in_comment, $comments_replies_per_page, $np, $ccp, $nav_url);

$nav = <<<EOF
<tr class="alt2">
	<td colspan="2" class="regular">$nav</td>
</tr>
EOF;

}

$sql = "
	select
		date_format($tb_comment_threads.timestamp, '$mysql_dates') as the_date,
		$tb_comments.user_id as user_id,
		$tb_users.username as username,
		$tb_comments.author_id as author_id,
		$tb_comments.subject as subject,
		$tb_comments.comment as comment,
		$tb_comments.id as id
	from
		$tb_comments
	left join
		$tb_comment_threads
	on
		$tb_comments.id = $tb_comment_threads.comment_id
	left join
		$tb_users
	on
		$tb_comments.user_id = $tb_users.id
	where
		$tb_comments.id = '$comment_id'
	or
		$tb_comments.pid = '$comment_id'
	order by
		$tb_comments.id
	limit
		$csr, $comments_replies_per_page
";

$query = mysql_query($sql) or die(mysql_error());

$comment_user_id = get_userid_from_comment_id($comment_id);

$un = get_username($comment_user_id);

$comment_user_name = <<<EOF
<span class="bold">$un</span>
EOF;

if(check_approved_image($comment_user_id)){
$comment_user_name = <<<EOF
<a class="bold" href="$base_url/?i=$comment_user_id">$un</a>
EOF;
}

$image_src = get_image($comment_user_id);

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $comment_user_name >> <a class="bold" href="$base_url/my_comments.php?id=$comment_user_id">My Comments</a> >> $comment_subject</td>
</tr>
<tr>
	<td class="regular">
EOF;

include("$include_path/vote_bar.php");
$content .= $vote_bar;

$content .= <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
	<td class="regular">$image_src</td>
</tr>
</table>
<table cellpadding="5" cellspacing="1" border="0">
<tr>
	<td>
EOF;

$i = $comment_user_id;
include("$include_path/profile_bar.php");
$content .= $profile_bar . "<br>";

$content .= <<<EOF
</td>
</tr>
</table>
	</td>
</tr>
<tr>
	<td class="bold" align="right">Comments For: $comment_user_name&nbsp;</td>
</tr>
</table>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
	<td>
EOF;

if(mysql_num_rows($query)){

$main_row = <<<EOF
	<tr>
		<td class="bold-rv" width="1%">Author</td>
		<td class="bold-rv">
		<table cellpadding="0" cellspacing="0" border="0" width="100%">
		<tr>
			<td class="bold-rv">Comment</td>
		</tr>
		</table>
		</td>
	</tr>
EOF;

$comments_table = <<<EOF
	<table cellpadding="5" cellspacing="1" border="0" width="100%">
	$nav
	$main_row
EOF;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt2" : "alt1";
$i++;

$comment_author_link = <<<EOF
<span class="smallbold">$ac</span>
EOF;

if($array["author_id"] > 0){
	$comment_starter = get_username($array["author_id"]);

$comment_author_link = <<<EOF
<span class="smallbold">$comment_starter</span>
EOF;

	if(check_approved_image($array["author_id"])){
$comment_author_link = <<<EOF
<a class="smallbold" href="$base_url/?i=$array[author_id]">$comment_starter</a>
EOF;
	}

}

$registered = get_user_signup_date($array["author_id"]);
$location = get_user_location($array["author_id"]);
$comments = get_user_comments_count($array["author_id"]);
$comment_subject = get_comment_subject($array["id"]);
$the_comment = nl2br(stripslashes($array["comment"]));

$comments_table .= <<<EOF
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap>$comment_author_link<br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Comments:<br>&nbsp;&nbsp;$comments<br><br></td>
	<td class="regular" valign="top"><br><span class="bold">$comment_subject</span><br><br>$the_comment<br><br></td>
</tr>
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap>$array[the_date]</td>
	<td class="smallregular" align="right">[ 
EOF;

$uid = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

if(comment_is_editable($array["id"], $uid)){

$comments_table .= <<<EOF
<a class="small" href="$base_url/edit_comment.php?c=$array[id]">Edit</a> | 
EOF;

}

$comments_table .= <<<EOF
<a class="small" href="$base_url/comment_reply.php?c=$comment_id">Reply</a> | <a class="small" href="$base_url/comment_reply.php?c=$comment_id&q=$array[id]">Quote</a> ]</td>
</tr>
EOF;

}

} else {

$comments_table = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td class="alt1" align="center"><br><br><span class="error">Sorry, no posts found..</span><br><br><br></td>
</tr>
EOF;

}

$comments_table .= <<<EOF
$main_row
$nav
</table>
EOF;

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