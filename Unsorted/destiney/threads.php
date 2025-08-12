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
FO;

$forum_id = isset($_GET['f']) ? $_GET['f'] : 0;
$parent_forum = get_parent_forum_name($forum_id);
$sub_forum =  get_forum_name(" >> " , $forum_id);
$title = "Forum :: " . get_forum_name("", $forum_id);

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$sql = "
	select
		*
	from
		$tb_threads
	where
		forum_id = '$forum_id'
	order by
		timestamp desc
";

$query = mysql_query($sql) or die(mysql_error());

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $forum_link$parent_forum$sub_forum</td>
</tr>
</table>
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="smallregular" align="right">[ <a class="small" href="$base_url/new_thread.php?f=$forum_id">New Thread</a> ]&nbsp;</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>
EOF;

if(mysql_num_rows($query)){

$forum_table = <<<EOF
	<table cellpadding="5" cellspacing="1" border="0" width="100%">
	<tr>
		<td class="bold-rv">Thread</td>
		<td class="bold-rv" align="center" width="1%">Starter</td>
		<td class="bold-rv" align="center" width="1%">Replies</td>
		<td class="bold-rv" align="center" width="1%">Views</td>
		<td class="bold-rv" align="center" width="1%" nowrap>Last Post</td>
	</tr>
EOF;

$i = 0;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt1" : "alt2";
$i++;

$thread_name = get_thread_name($array["thread_id"]);
$thread_starter = get_thread_starter($array["thread_id"]);
$thread_starter_id = get_thread_starter_id($thread_starter);
$replies_count = get_thread_replies_count($array["thread_id"]);
$views_count = get_thread_views_count($array["thread_id"]);
$last_post = get_last_post_for_thread_id($array["thread_id"]);

$forum_table .= <<<EOF
<tr class="$alt">
	<td class="regular"><a href="$base_url/posts.php?t=$array[thread_id]">$thread_name</a></td>
	<td class="regular" align="center"><a href="$base_url/?i=$thread_starter_id">$thread_starter</a></td>
	<td class="regular" align="center">$replies_count</td>
	<td class="regular" align="center">$views_count</td>
	<td class="smallregular" align="right" nowrap>$last_post</td>
</tr>
EOF;

}
} else {

$forum_table = <<<EOF
<table cellpadding="5" cellspacing="1" border="0" width="100%">
<tr class="alt1">
	<td class="regular" align="center"><br><br><br>Sorry, no threads in this forum yet..<br><br><br><br></td>
</tr>
EOF;

}

$forum_table .= <<<EOF
</table>
EOF;

$content .= table_no_title($forum_table);

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