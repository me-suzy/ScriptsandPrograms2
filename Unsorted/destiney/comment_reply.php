<?php
include("./admin/config.php");
include("$include_path/common.php");

if(!$allow_anonymous_comments){
	check_user_login();
}

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['submit_comment_reply']) && !isset($_POST['preview_comment_reply']) && !isset($_GET['c'])){
	header("Location: $base_url/");
	exit();
}

$comment_id = isset($_GET['c']) ? $_GET['c'] : 0;

$i = 0;
$error = false;
$subject_msg = "";
$post_msg = "";
$reply_subject = "";
$reply_post = "";
$preview_content = "";
$quoted = "";
$resubject = "";

if(isset($_POST['submit_comment_reply'])){

	if(!isset($_POST['c']) || !comment_exists($_POST['c'])){
		header("Location: $base_url/");
		exit();
	} else {
		$comment_id = $_POST['c'];
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
		insert_comment_reply($comment_id, $reply_subject, $reply_post, $_SESSION['userid']);
		header("Location: $base_url/view_comment.php?c=$comment_id");
		exit();
	}
}

if(isset($_POST['preview_comment_reply'])){
	
	if(!isset($_POST['c']) || !comment_exists($_POST['c'])){
		header("Location: $base_url/");
		exit();
	} else {
		$comment_id = $_POST['c'];
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

		$commenter_username = isset($_SESSION['username']) && strlen($_SESSION['username']) ? $_SESSION['username'] : $ac;

$commenter_link = <<<EOF
<span class="smallbold">$commenter_username</span>
EOF;

		if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0){

$commenter_link = <<<EOF
<a class="smallbold" href="$base_url/?i=$_SESSION[userid]">$commenter_username</a>
EOF;

		}

		$registered = get_user_signup_date($_SESSION['userid']);
		$location = get_user_location($_SESSION['userid']);
		$comments = get_author_comments_count($_SESSION['userid']);
		$the_comment = nl2br($reply_post);
		$date = get_date();

$preview_html = <<<EOF
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td class="bold-rv" width="1%">Author</td>
	<td class="bold-rv" width="99%">Comment</td>
</tr>
<tr class="alt1">
	<td class="smallregular" valign="top" nowrap>$commenter_link<br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Comments:<br>&nbsp;&nbsp;$comments<br><br></td>
	<td class="regular"><span class="bold">$reply_subject</span><br><br>$the_comment<br><br></td>
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

$title = "Post Comment Reply";

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> <a class="bold" href="$base_url/comments.php">Comments</a> >> Post Comment Reply</td>
</tr>
</table>
EOF;

if(strlen($preview_content)){

$content .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Preview Comment Post:&nbsp;$preview_content</td>
</tr>
</table>
EOF;

}

$user_id = get_userid_from_comment_id($comment_id);
$comment_user_name = get_username($user_id);

if(check_approved_image($user_id)){
$comment_user_name = <<<EOF
<a class="bold" href="$base_url/?i=$user_id">$comment_user_name</a>
EOF;
}

$content .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Post Comment Reply For: $comment_user_name&nbsp;</td>
</tr>
<tr>
	<td class="regular">
EOF;

if(isset($_GET['q']) && comment_exists($_GET['q'])){
	$quote = get_comment($_GET['q']);
	$resubject = "RE: " . get_comment_subject($_GET['q']);
	$quote = wordwrap($quote, 64, "\r\n");
	$quoted = "\r\n\r\n> ";
	$quoted .= eregi_replace("\n", "\n> ", $quote);
	$quoted .= "\r\n";
}

$commenter_username = isset($_SESSION['username']) && strlen($_SESSION['username']) ? $_SESSION['username'] : $ac;
$commenter_link = $commenter_username;

if(isset($_SESSION['userid']) && $_SESSION['userid'] > 0){
$commenter_link = <<<EOF
<a href="$base_url/?i=$_SESSION[userid]">$commenter_username</a>
EOF;
}

$comment_reply_table = <<<EOF
	<table cellpadding="3" cellspacing="1" border="0" width="100%">
	<tr><form method="post" action="$base_url/comment_reply.php">
	<input type="hidden" name="c" value="$comment_id">
		<td class="bold-rv" colspan="2">&nbsp;Post Comment Reply</td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="right">Logged in as:</td>
		<td class="regular">&nbsp;$commenter_link</td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right">Subject:</td>
		<td class="regular"><input class="input" type="text" name="reply_subject" size="67" value="$reply_subject$resubject"><span class="error">$subject_msg</span></td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right" valign="top"><br>Comment:</td>
		<td class="regular"><textarea class="input" name="reply_post" rows="16" cols="70" wrap="virtual">$reply_post$quoted</textarea><span class="error">$post_msg</span></td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="center" colspan="2" nowrap><input class="button" type="submit" name="submit_comment_reply" value="Post Comment Reply"> <input class="button" type="submit" name="preview_comment_reply" value="Preview Post"></td>
	</form></tr>
	</table>
EOF;

$content .= table_no_title($comment_reply_table);

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