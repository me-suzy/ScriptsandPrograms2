<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['submit_message']) && !isset($_GET['i'])){
	header("Location: $base_url/");
	exit();
}

$user_id = isset($_GET['i']) ? $_GET['i'] : 0;

$i = 0;
$error = false;
$subject_msg = "";
$post_msg = "";
$reply_subject = "";
$reply_post = "";
$preview_content = "";
$quoted = "";
$resubject = "";

if(isset($_POST['submit_message'])){

	if(!isset($_POST['i'])){
		header("Location: $base_url/");
		exit();
	} else {
		$user_id = $_POST['i'];
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
		insert_private_message($user_id, $reply_subject, $reply_post, $_SESSION['userid']);
		header("Location: $base_url/?i=$user_id");
		exit();
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

$title = "Private Message";

$content = <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">&nbsp;<a class="bold" href="$base_url/">$site_title</a> >> Private Message</td>
</tr>
</table>
EOF;

$comment_user_name = get_username($user_id);

if(check_approved_image($user_id)){
$comment_user_name = <<<EOF
<a class="bold" href="$base_url/?i=$user_id">$comment_user_name</a>
EOF;
}

$content .= <<<EOF
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold" align="right">Leave Private Message For: $comment_user_name&nbsp;</td>
</tr>
<tr>
	<td class="regular">
EOF;

$comment_reply_table = <<<EOF
	<table cellpadding="3" cellspacing="1" border="0" width="100%">
	<tr><form method="post" action="$base_url/message.php">
	<input type="hidden" name="i" value="$user_id">
		<td class="bold-rv" colspan="2">&nbsp;Leave Private Message</td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="right">Logged in as:</td>
		<td class="regular"><a href="$base_url/?i=$_SESSION[userid]">$_SESSION[username]</a></td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right">Subject:</td>
		<td class="regular"><input class="input" type="text" name="reply_subject" size="67" value="$reply_subject"><span class="error">$subject_msg</span></td>
	</tr>
	<tr class="alt1">
		<td class="regular" align="right" valign="top"><br>Message:</td>
		<td class="regular"><textarea class="input" name="reply_post" rows="16" cols="70" wrap="virtual">$reply_post</textarea><span class="error">$post_msg</span></td>
	</tr>
	<tr class="alt2">
		<td class="regular" align="center" colspan="2" nowrap><input class="button" type="submit" name="submit_message" value="Leave Private Message"></td>
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