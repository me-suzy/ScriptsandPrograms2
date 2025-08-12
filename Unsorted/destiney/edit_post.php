<?php
include("./admin/config.php");
include("$include_path/common.php");

check_user_login();

include("$include_path/$table_file");
include("$include_path/doc_head.php");
include("$include_path/styles.php");

if(!isset($_POST['submit_edit_post']) && !isset($_POST['preview_edit_post']) && !isset($_GET['p'])){
	header("Location: $base_url/");
	exit();
}

$error = false;
$too_old_error = false;
$subject_msg = "";
$post_msg = "";
$edit_subject = "";
$edit_post = "";
$preview_content = "";

if(isset($_POST['submit_edit_post'])){

	if(!isset($_POST['p']) || !post_exists($_POST['p'])){
		header("Location: $base_url/");
		exit();
	} else {
		$post_id = $_POST['p'];
	}

	if(!strlen($_POST['edit_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$edit_subject = $_POST['edit_subject'];
	}

	if(strlen($_POST['edit_post'])){
			if(strlen($_POST['edit_post']) > 4096){
				$length = strlen($_POST['edit_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$edit_post = $_POST['edit_post'];
			}
	} else {
		$error = true;
		$post_msg = "<br>No message submitted.";
	}

	if(!$error){
		update_post($post_id, $edit_subject, $edit_post);
		$thread_id = get_thread_id_from_post_id($post_id);
		header("Location: $base_url/posts.php?t=$thread_id");
		exit();
	}
}

if(isset($_POST['preview_edit_post'])){
	
	if(!isset($_POST['p']) || !post_exists($_POST['p'])){
		header("Location: $base_url/");
		exit();
	} else {
		$post_id = $_POST['p'];
	}

	if(!strlen($_POST['edit_subject'])){
		$error = true;
		$subject_msg = "<br>No subject submitted.";
	} else {
		$edit_subject = $_POST['edit_subject'];
	}

	if(strlen($_POST['edit_post'])){
			if(strlen($_POST['edit_post']) > 4096){
				$length = strlen($_POST['edit_post']) - 4096;
				$error = true;
				$post_msg = "<br>4K or less. " . $length . " bytes too large.";
			} else {
				$edit_post = $_POST['edit_post'];
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
		$the_post = nl2br($edit_post);
		$date = get_date();

$preview_html = <<<EOF
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr>
	<td class="bold-rv">Author</td>
	<td class="bold-rv">Thread</td>
</tr>
<tr class="alt1">
	<td class="smallregular" valign="top" nowrap><a class="smallbold" href="$base_url/?i=$_SESSION[userid]">$username</a><br><br>Registered:<br>&nbsp;&nbsp;$registered<br>Location:<br>&nbsp;&nbsp;$location<br>Posts:<br>&nbsp;&nbsp;$posts<br><br></td>
	<td class="regular"><span class="bold">$edit_subject</span><br><br>$the_post<br><br></td>
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

if(!isset($post_id)){
	$post_id = isset($_GET['p']) ? $_GET['p'] : 0;
}

if(post_exists($post_id) && post_is_editable($post_id)){
	$post = get_post_post($post_id);
	$subject = get_post_subject($post_id);
} else {
	$too_old_error = true;
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

$thread_id = get_thread_id_from_post_id($post_id);
$forum_id = get_forum_id_from_thread_id($thread_id);

$forum_link = <<<EOF
<a class="bold" href="$base_url/forums.php">Forums</a>
EOF;

$parent_forum = get_parent_forum_name($forum_id);
$sub_forum =  get_forum_name_linked(" >> " , $forum_id);
$title = "Forum :: " . get_forum_name("", $forum_id);

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
	<td class="bold" align="right">Preview Post Edit:&nbsp;$preview_content</td>
</tr>
</table>
<br>
EOF;

}

$content .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td>
EOF;

if($too_old_error){

$post_reply_table = <<<EOF
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr class="alt2">
	<td class="regular" align="center"><br><br><br>Post is no longer editable.<br><br><br><br></td>
</tr>
</table>
EOF;

} else {

$post_reply_table = <<<EOF
<table cellpadding="3" cellspacing="1" border="0" width="100%">
<tr><form method=post action="$base_url/edit_post.php">
<input type="hidden" name="p" value="$post_id">
	<td class="bold-rv" colspan="2">Edit Post</td>
</tr>
<tr class="alt2">
	<td class="regular" align="right">Logged in as:</td>
	<td class="regular"><a href="$base_url/?i=$_SESSION[userid]">$_SESSION[username]</a></td>
</tr>
<tr class="alt1">
	<td class="regular" align="right">Subject:</td>
	<td class="regular"><input class="input" type="text" name="edit_subject" size="67" value="$subject"></td>
</tr>
<tr class="alt1">
	<td class="regular" align="right" valign="top"><br>Message:</td>
	<td class="regular"><textarea class="input" name="edit_post" rows="16" cols="70">$post</textarea></td>
</tr>
<tr class="alt2">
	<td class="regular" align="center" colspan="2" nowrap><input class="button" type="submit" name="submit_edit_post" value="Edit Post"> <input class="button" type="submit" name="preview_edit_post" value="Preview Edit"></td>
</tr>
</form>
</table>
EOF;

}

$content .= table_no_title($post_reply_table);

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