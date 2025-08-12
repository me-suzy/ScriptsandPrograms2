<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['submit_thread']) && !isset($_POST['preview_thread']) && !isset($_GET['f'])){
	header("Location: $base_url/");
	exit();
}

$error = false;
$subject_msg = "";
$post_msg = "";
$thread_subject = "";
$thread_post = "";
$preview_content = "";

if(isset($_POST['submit_thread'])){

	if(!isset($_POST['f']) || !forum_exists($_POST['f'])){
		header("Location: $base_url/");
		exit();
	} else {
		$forum_id = $_POST['f'];
	}

	if(!strlen($_POST['thread_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$thread_subject = $_POST['thread_subject'];
	}

	if(strlen($_POST['thread_post'])){
			if(strlen($_POST['reply_post']) > 4096){
				$length = strlen($_POST['reply_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$thread_post = $_POST['thread_post'];
			}
	} else {
		$error = true;
		$post_msg = "<br>No message submitted.";
	}

	if(!$error){
		$insert_id = insert_new_thread($forum_id);
		insert_post_reply($insert_id, $thread_subject, $thread_post, $_SESSION['userid']);
		header("Location: $base_url/threads.php?f=$forum_id");
		exit();
	}
}

if(isset($_POST['preview_thread'])){
	
	if(!isset($_POST['f']) || !forum_exists($_POST['f'])){
		header("Location: $base_url/");
		exit();
	} else {
		$forum_id = $_POST['f'];
	}

	if(!strlen($_POST['thread_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$thread_subject = $_POST['thread_subject'];
	}

	if(strlen($_POST['thread_post'])){
			if(strlen($_POST['thread_post']) > 4096){
				$length = strlen($_POST['thread_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$thread_post = $_POST['thread_post'];
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
		$date = get_date();
		$preview_post = nl2br($thread_post);

$preview_html = <<<EOF
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td class="bold-rv">Author</td>
	<td class="bold-rv">Thread</td>
</tr>
<tr class="alt1">
	<td class="smallregular" valign="top" nowrap><a class="smallbold" href="$base_url/?i=$_SESSION[userid]">$username</a><br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Posts:<br>&nbsp;&nbsp;$posts<br><br></td>
	<td class="regular"><span class="bold">$thread_subject</span><br><br>$preview_post<br></td>
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

if(!isset($forum_id)){
	$forum_id = isset($_GET['f']) ? $_GET['f'] : 0;
}

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$parent_forum = get_parent_forum_name($forum_id);
$sub_forum =  get_forum_name_linked(" >> " , $forum_id);
$title = "Forum :: " . get_forum_name("", $forum_id);

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
EOF;

if(strlen($preview_content)){

$content .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Preview Thread:&nbsp;$preview_content</td>
</tr>
</table>
EOF;

}

$content .= <<<EOF
<br>
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td>
EOF;
	
$new_thread_table = <<<EOF
	<table cellpadding="5" cellspacing="1" border="0" width="100%">
	<tr><form method=post action="$base_url/new_thread.php">
	<input type="hidden" name="f" value="$forum_id">
		<td class="bold-rv" colspan="2">Post New Thread</td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="right">Logged in as:</td>
		<td class="regular"><a href="$base_url/?i=$_SESSION[userid]">$_SESSION[username]</a></td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right">Subject:</td>
		<td class="regular"><input class="input" type="text" name="thread_subject" size="67" value="$thread_subject"><span class="error">$subject_msg</span></td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right" valign="top"><br>Message:</td>
		<td class="regular"><textarea class="input" name="thread_post" rows="16" cols="70">$thread_post</textarea><span class="error">$post_msg</span></td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="center" colspan="2" nowrap><input class="button" type="submit" name="submit_thread" value="Submit Thread"> <input class="button" type="submit" name="preview_thread" value="Preview Thread"></td>
	</tr>
	</form>
	</table>
EOF;

$content .= table_no_title($new_thread_table);

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