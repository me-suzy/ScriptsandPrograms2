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

$title = "Forums";

$forum_parent = isset($_GET['p']) ? $_GET['p'] : 0;

$title = "Forums" . get_forum_name(" :: ", $forum_parent);

$sql = "
	select
		*
	from
		$tb_forums
	where
";

if($forum_parent){

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$sub_forum = get_forum_name(" >> ", $forum_parent);

$sql .= "
		forum_id = '$forum_parent'
";

} else {

$sub_forum = "";

$forum_link = <<<EOF
Forums
EOF;

$sql .= "
		forum_pid = '$forum_parent'
";

}

$sql .= "
	order by
		order_by
";

$query = mysql_query($sql) or die(mysql_error());

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $forum_link$sub_forum</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td>
	<table cellpadding="0" cellspacing="1" border="0" width="100%">
	<tr>
		<td bgcolor="black">
		<table cellpadding="5" cellspacing="1" border="0" width="100%">
		<tr>
			<td class="bold-rv">&nbsp;</td>
			<td class="bold-rv">Forum</td>
			<td class="bold-rv" width="47" align="center">Posts</td>
			<td class="bold-rv" width="47" align="center">Threads</td>
			<td class="bold-rv" align="center" nowrap>Last Post</td>
		</tr>
EOF;

if(mysql_num_rows($query)){

while($array = mysql_fetch_array($query)){

$content .= <<<EOF
<tr class="alt2">
	<td class="regular" colspan="5"><a class="bold" href="$base_url/forums.php
EOF;

$content .= $forum_parent ? "" : "?p=" . $array["forum_id"];

$content .= <<<EOF
">$array[forum]</a><br><span class="smallregular">$array[description]</span></td>
</tr>
EOF;

$ssql = "
	select
		*
	from
		$tb_forums
	where
		forum_pid = '$array[forum_id]'
	order by
		order_by
";

$squery = mysql_query($ssql) or die(mysql_error());

if(mysql_num_rows($squery)){

while($sarray = mysql_fetch_array($squery)){

$posts = get_posts_count($sarray['forum_id']);
$threads = get_threads_count($sarray['forum_id']);
$last_post = get_last_post($sarray['forum_id']);

$content .= <<<EOF
<tr class="alt1">
	<td class="regular">&nbsp;</td>
	<td class="regular"><a href="$base_url/threads.php?f=$sarray[forum_id]">$sarray[forum]</a><br><span class="smallregular">$sarray[description]</span></td>
	<td class="regular" align="center">$posts</td>
	<td class="regular" align="center">$threads</td>
	<td class="smallregular" align="right" nowrap>$last_post</td>
</tr>
EOF;

}
}
}
}

$content .= <<<EOF
</table>
</td>
</tr>
</table></td>
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