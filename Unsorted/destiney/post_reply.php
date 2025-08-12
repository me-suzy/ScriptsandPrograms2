<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['submit_post']) && !isset($_POST['preview_post']) && !isset($_GET['t'])){
	header("Location: $base_url/");
	exit();
}

$i = 0;
$error = false;
$subject_msg = "";
$post_msg = "";
$reply_subject = "";
$reply_post = "";
$preview_content = "";
$nav = "";
$quoted = "";
$resubject = "";

if(isset($_POST['submit_post'])){

	if(!isset($_POST['t']) || !thread_exists($_POST['t'])){
		header("Location: $base_url/");
		exit();
	} else {
		$thread_id = $_POST['t'];
	}

	if(!strlen($_POST['reply_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$reply_subject = $_POST['reply_subject'];
	}

	if(strlen($_POST['reply_post'])){
			if(strlen($_POST['reply_post']) > 4096){
				$length = strlen($_POST['reply_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$reply_post = $_POST['reply_post'];
			}
	} else {
		$error = true;
		$post_msg = "<br>No message submitted.";
	}

	if(!$error){
		insert_post_reply($thread_id, $reply_subject, $reply_post, $_SESSION['userid']);
		header("Location: $base_url/posts.php?t=$_POST[t]");
		exit();
	}
}

if(isset($_POST['preview_post'])){
	
	if(!isset($_POST['t']) || !thread_exists($_POST['t'])){
		header("Location: $base_url/");
		exit();
	} else {
		$thread_id = $_POST['t'];
	}

	if(!strlen($_POST['reply_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$reply_subject = $_POST['reply_subject'];
	}

	if(strlen($_POST['reply_post'])){
			if(strlen($_POST['reply_post']) > 4096){
				$length = strlen($_POST['reply_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$reply_post = $_POST['reply_post'];
			}
	} else {
		$error = true;
		$post_msg = "<br>No message submitted.";
	}

	if(!$error){

		$username = get_username($_SESSION['userid']);
		$registered = get_user_signup_date($_SESSION['userid']);
		$location = get_user_location($_SESSION['userid']);
		$posts = get_user_posts_count($_SESSION['userid']);
		$the_post = nl2br($reply_post);
		$date = get_date();

$preview_html = <<<EOF
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td class="bold-rv">Author</td>
	<td class="bold-rv">Thread</td>
</tr>
<tr class="alt1">
	<td class="smallregular" valign="top" nowrap><a class="smallbold" href="$base_url/?i=$_SESSION[userid]">$username</a><br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Posts:<br>&nbsp;&nbsp;$posts<br><br></td>
	<td class="regular"><span class="bold">$reply_subject</span><br><br>$the_post<br><br></td>
</tr>
<tr class="alt2">
	<td class="smallregular" valign="top" nowrap>$date</td>
	<td class="smallregular" align="right">&nbsp;</td>
</tr>
</table>
EOF;

$preview_content = table_no_title($preview_html);

	}
}

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

if(!isset($thread_id)){
	$thread_id = isset($_GET['t']) ? $_GET['t'] : 0;
}

$posts_in_thread = get_posts_in_thread_count($thread_id);
$forum_id = get_forum_id_from_thread_id($thread_id);

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$parent_forum = get_parent_forum_name($forum_id);
$forum_name = get_forum_name("", $forum_id);
$sub_forum =  get_forum_name_linked(" >> " , $forum_id);
$title = "Forum :: " . $forum_name;

if($posts_in_thread > $posts_per_page){
	$nav_url = "$base_url/posts.php?t=$thread_id&";
	$nav = comment_nav_links($posts_in_thread, $posts_per_page, $np, $ccp, $nav_url);

$nav = <<<EOF
<tr class="alt2">
	<td colspan="2" class="regular">$nav</td>
</tr>
EOF;

}

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> $forum_link$parent_forum$sub_forum >> Post Reply</td>
</tr>
</table>
EOF;

if(strlen($preview_content)){

$content .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Preview Post:&nbsp;$preview_content</td>
</tr>
</table>
EOF;

}

$content .= <<<EOF
<br>
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">
EOF;

if(isset($_GET['p']) && post_exists($_GET['p'])){
	$quote = get_post_post($_GET['p']);
	$resubject = "RE: " . get_post_subject($_GET['p']);
	$quote = wordwrap($quote, 64, "\r\n");
	$quoted = "\r\n\r\n> ";
	$quoted .= eregi_replace("\n", "\n> ", $quote);
	$quoted .= "\r\n";
}

$post_reply_table = <<<EOF
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr><form method="post" action="$base_url/post_reply.php">
<input type="hidden" name="t" value="$thread_id">
	<td class="bold-rv" colspan="2">&nbsp;Post Reply</td>
</tr>
<tr class="alt2">
	<td class="regular" align="right">Logged in as:</td>
	<td class="regular"><a href="$base_url/?i=$_SESSION[userid]">$_SESSION[username]</a></td>
</tr>
<tr class="alt1">
	<td class="regular" align="right">Subject:</td>
	<td class="regular"><input class="input" type="text" name="reply_subject" size="67" value="$reply_subject$resubject"><span class="error">$subject_msg</span></td>
</tr>
<tr class="alt1">
	<td class="regular" align="right" valign="top"><br>Message:</td>
	<td class="regular"><textarea class="input" name="reply_post" rows="16" cols="70" wrap="virtual">$reply_post$quoted</textarea><span class="error">$post_msg</span></td>
</tr>
<tr class="alt2">
	<td class="regular" align="center" colspan="2" nowrap><input class="button" type="submit" name="submit_post" value="Post Reply"> <input class="button" type="submit" name="preview_post" value="Preview Post"></td>
</form></tr>
</table>
EOF;

$content .= table_no_title($post_reply_table);

$content .= <<<EOF
<br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Thread (Reversed):&nbsp;</td>
</tr>
</table>
EOF;

$sql = "
	select
		*,
		date_format(timestamp, '$mysql_dates') as the_date
	from
		$tb_posts
	where
		thread_id = '$thread_id'
	order by
		timestamp desc
	limit
		$csr, $posts_per_page
";

$query = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($query)){

$posts_table = <<<EOF
	<table cellpadding="4" cellspacing="1" border="0" width="100%">
	$nav
	<tr>
		<td class="bold-rv">Author</td>
		<td class="bold-rv">Thread</td>
	</tr>
EOF;

while($array = mysql_fetch_array($query)){

$alt = $i % 2 ? "alt1" : "alt2";
$i++;

$username = get_username($array["userid"]);
$registered = get_user_signup_date($array["userid"]);
$location = get_user_location($array["userid"]);
$posts = get_user_posts_count($array["userid"]);
$the_post = nl2br($array["post"]);

$posts_table .= <<<EOF
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap><a class="smallbold" href="$base_url/?i=$array[userid]">$username</a><br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Posts:<br>&nbsp;&nbsp;$posts<br><br></td>
	<td class="regular"><span class="bold">$array[subject]</span><br><br>$the_post<br><br></td>
</tr>
<tr class="$alt">
	<td class="smallregular" valign="top" nowrap>$array[the_date]</td>
	<td class="smallregular" align="right">&nbsp;</td>
</tr>
EOF;

}

$posts_table .= <<<EOF
$nav
</table>
EOF;

$content .= table_no_title($posts_table);

}

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

echo $final_output;

?>