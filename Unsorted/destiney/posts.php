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

$thread_id = isset($_GET['t']) ? $_GET['t'] : 0;

tally_thread_view($thread_id, $_SERVER["REMOTE_ADDR"]);

$thread_name = get_thread_name($thread_id);

$posts_in_thread = get_posts_in_thread_count($thread_id);

$title = "Thread :: " . $thread_name;

if($posts_in_thread > $posts_per_page){
	$nav_url = "$base_url/posts.php?t=$thread_id&";
	$nav = comment_nav_links($posts_in_thread, $posts_per_page, $np, $ccp, $nav_url);

$nav = <<<EOF
<tr class="alt2">
	<td colspan="2" class="regular">$nav</td>
</tr>
EOF;

}

$sql = "
	select
		*,
		date_format(timestamp, '$mysql_dates') as the_date
	from
		$tb_posts
	where
		thread_id = '$thread_id'
	order by
		timestamp
	limit
		$csr, $posts_per_page
";

$query = mysql_query($sql) or die(mysql_error());

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$forum_id = get_forum_id_from_thread_id($thread_id);
$parent_forum = get_parent_forum_name($forum_id);
$sub_forum =  get_forum_name(" >> " , $forum_id);

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $forum_link$parent_forum$sub_forum</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
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
			<td class="bold-rv">Thread</td>
			<td class="bold-rv" align="right" nowrap><span class="smallregular">[ <a class="small" href="$base_url/new_thread.php?f=$forum_id">New Thread</a> | <a class="small" href="$base_url/post_reply.php?t=$thread_id">Post Reply</a> ]</span></td>
		</tr>
		</table>
		</td>
	</tr>
EOF;

$posts_table = <<<EOF
	<table cellpadding="5" cellspacing="1" border="0" width="100%">
	$nav
	$main_row
EOF;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt2" : "alt1";
$i++;

$username = get_username($array["userid"]);
$registered = get_user_signup_date($array["userid"]);
$location = get_user_location($array["userid"]);
$posts = get_user_posts_count($array["userid"]);
$the_post = nl2br($array["post"]);

$posts_table .= <<<EOF
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap><a class="smallbold" href="$base_url/?i=$array[userid]">$username</a><br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Posts:<br>&nbsp;&nbsp;$posts<br><br></td>
	<td class="regular" valign="top"><br><span class="bold">$array[subject]</span><br><br>$the_post<br><br></td>
</tr>
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap>$array[the_date]</td>
	<td class="smallregular" align="right">[ 
EOF;

if(post_is_editable($array["post_id"])){

$posts_table .= <<<EOF
<a class="small" href="$base_url/edit_post.php?p=$array[post_id]">Edit</a> | 
EOF;

}

$posts_table .= <<<EOF
<a class="small" href="$base_url/post_reply.php?t=$thread_id&p=$array[post_id]">Quote</a> ]</td>
</tr>
EOF;

}

} else {

$posts_table = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr>
	<td class="alt1" align="center"><br><br><span class="error">Sorry, no posts found..</span><br><br><br></td>
</tr>
EOF;

}

$posts_table .= <<<EOF
$main_row
$nav
</table>
EOF;

$content .= table_no_title($posts_table);

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