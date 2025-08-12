<?php
include("./admin/config.php");
include("$include_path/common.php");
include("$include_path/$table_file");

if(!isset($_POST['submit_comment'])){
	if(isset($_GET['id'])){
		$id = $_GET['id'];
	} else {
		header("Location: $base_url/comments.php");
		exit();
	}
}

$commenter_id = isset($_SESSION['userid']) ? $_SESSION['userid'] : 0;

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
<table cellpadding="0" cellspacing="0" border="0" width="100%" align="center">
FO;

$final_output .= <<<FO
<tr>
<td align="left" valign="top">
FO;

$content = "";

$title = "Reply to comment";

if(isset($_POST['submit_comment'])){

	$new_comment = ereg_replace("<([^>]+)>", "", $_POST['comment']);
	$new_comment = addslashes($new_comment);

	if(!strlen($new_comment)){
		$com_error = 1;
		$com_html = "Forget something? You failed to submit a reply.";
	}

	if(strlen($new_comment) > 10240){
		$com_error = 1;
		$com_html = "Your reply must be less than 10K in length.";
	}

	if(!isset($com_error)){

		$check_ip_sql = "
			select
				*
			from
				$tb_comments
			where
				id = '$_POST[id]'
		";

		$check_ip_query = mysql_query($check_ip_sql) or die(mysql_error());
		$last_commenter_ip = @mysql_result($check_ip_query, 0, "author_ip");
		$last_commenter_id = @mysql_result($check_ip_query, 0, "author_id");
		$user_id = @mysql_result($check_ip_query, 0, "user_id");

		$same_ip = false;
		$same_commenter = false;
		
		if($last_commenter_ip == $_SERVER['REMOTE_ADDR']) $same_ip = true;
		
		if($last_commenter_id > 0){
			if($commenter_id == $last_commenter_id) $same_commenter = true;
		}

			$is_sql = "
				insert into $tb_comments (
					id,
					pid,
					user_id,
					comment,
					author_id,
					author_ip,
					status
				) values (
					'',
					'$_POST[id]',
					'$user_id',
					'$new_comment',
					'$commenter_id',
					'$_SERVER[REMOTE_ADDR]',
					'approved'
				)
			";

			$is_query = mysql_query($is_sql) or die(mysql_error());
		
			$message = "<br><br><br>Comment Accepted, Thank You!<br>";

			$ic_sql = "
				update
					$tb_users
				set
					total_comments = total_comments + 1
				where
					id = '$user_id'
			";

			$ic_query = mysql_query($ic_sql) or die(mysql_error());

	} else $error_message = "Comment Submission Error";

	$message = isset($message) ? $message : "";

	if(!isset($com_error)){

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" align="center"><br>$message<br><br><a href="$base_url/comments.php">Click Here to Continue</a><br><br><br></td>
</tr>
</table>
EOF;

	}
} 

if(isset($com_error) || !isset($_POST['submit_comment'])){

$new_comment = isset($_POST['comment']) ? $_POST['comment'] : "";
$id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = isset($_POST['id']) ? $_POST['id'] : $id;

$title = "Reply To Comment";

$gc_sql = "
	select
		id,
		pid,
		user_id,
		author_id,
		comment,
		DATE_FORMAT(timestamp, '%M %e, %Y') as comment_date
	from
		$tb_comments
	where
		id = '$id'
	order by
		timestamp desc
";
$gc_query = mysql_query($gc_sql) or die(mysql_error());

if(mysql_num_rows($gc_query)){

$content .= <<<EOF
<table cellpadding="2" cellspacing="0" border="0" width="100%">
EOF;

$gc_array = mysql_fetch_array($gc_query);

$user_id = $gc_array["user_id"];
$author_id = $gc_array["author_id"];

$comment = nl2br(wordwrap(stripslashes($gc_array["comment"]), 88, "\n", 1));

$comment_date = $gc_array["comment_date"];

$user_name = get_username($user_id);
$author_name = get_username($author_id);

$author_link = $author_name;
$user_link = $user_name;

if($author_id > 0){

	$check_sql = "
		select
			image_status
		from
			$tb_users
		where
			id = '$author_id'
	";
	$check_query = mysql_query($check_sql) or die(mysql_error());

	if(mysql_result($check_query, 0, "image_status") == "approved"){

$author_link = <<<EOF
<a href="$base_url/?i=$author_id">$author_name</a>
EOF;
	
	}
}

$check_sql = "
	select
		image_status
	from
		$tb_users
	where
		id = '$user_id'
";
$check_query = mysql_query($check_sql) or die(mysql_error());

if(mysql_result($check_query, 0, "image_status") == "approved"){

$user_link = <<<EOF
<a href="$base_url/?i=$user_id">$user_name</a>
EOF;

}

$content .= <<<EOF
<tr>
	<td width="100%"><br>
	<table cellpadding="0" cellspacing="2" border="0" width="100%">
	<tr>
		<td class="regular" colspan="2">On $gc_array[comment_date], $author_link left a comment for $user_link:</td>
	</tr>
	<tr>
		<td width="1%">&nbsp;</td>
		<td class="regular" width="99%">$comment</td>
	</tr>
	</table>
	</td>
</tr>
EOF;

}

$content .= "</table><br>";

$content .= <<<EOF
<table cellpadding="10" cellspacing="0" border="0" width="100%">
<tr><form method="post" action="$base_url/reply_com.php">
	<td class="regular"><a name="comment" class="regular">Reply:</a>
	<br>
	<textarea class="input" name="comment" rows="8" cols="72">$new_comment</textarea><br>
	<span class="smallregular">10K Max&nbsp;&nbsp;-&nbsp;&nbsp;All HTML will be removed.&nbsp;&nbsp;-&nbsp;&nbsp;IP Logged: $_SERVER[REMOTE_ADDR]</span>
EOF;

$com_html = isset($com_html) ? $com_html : "";

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$com_html</td>
</tr>
</table>
<br>
<input class="button" type="submit" name="submit_comment" value=" Submit Comment Reply -> ">
<br>
<input type="hidden" name="commenter_id" value="$commenter_id">
<input type="hidden" name="id" value="$id">
EOF;

$content .= <<<EOF
</form><br></td>
</tr>
</table>
EOF;
}

$final_output .= table($title, $content);

$final_output .= <<<FO
</td>
</tr>
FO;

$final_output .= <<<FO
</table>
FO;

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