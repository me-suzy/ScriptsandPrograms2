<?

/*
 * $Id: comment.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";

if($s == "m") $sex = "Guys";
else $sex = "Girls";

$title = "View the " . $sex;

$total_users = 1;
if(!isset($sing) or $sing == 0) $total_users = get_total_users($s);

if($total_users > 0){

$nav_url = "$base_url/index.php?s=" . $s . "&amp;show=view&amp;";

$nav = nav_links($total_users, $pp, $np, $cp, $nav_url) . " " . $sex;

$nav = <<<EOF
<span class="regular">$nav</span>
EOF;

} else {

$nav = <<<EOF
<table cellpadding="15" cellspacing="0" border="0" width="100%">
<tr>
	<td align="center" class="regular">Sorry there are none.</td>
</tr>
</table>
EOF;

}

$final_output .= table($title, $nav);

if(session_is_registered("userid")) $commenter_id = $userid;
else $commenter_id = 0;

$com_sql = "
	select
		*
	from
		$tb_users
	where
		id = '$id'
";

$com_query = sql_query($com_sql);
$com_array = sql_fetch_array($com_query);

$content = "";

$title = "Leave " . $com_array["username"] . " a comment";

if(isset($submit_comment)){

if(strlen($comment)<20){
	$com_error = 1;
	$com_html = "Your comment must be at least 20 characters in length.";
}

if(strlen($comment)>255){
	$com_error = 1;
	$com_html = "Your comment must be less than 255 characters in length.";
}

if(!isset($com_error)){

	$check_ip_sql = "
		select
			*
		from
			$tb_comments
		where
			user_id = '$id'
		order by
			timestamp desc
		limit
			0, 1
	";

	$check_ip_query = sql_query($check_ip_sql);
	$last_commenter_ip = sql_result($check_ip_query, "0", "author_ip");
	$last_commenter_id = sql_result($check_ip_query, "0", "author_id");

	$same_ip = false;
	$same_commenter = false;
	
	if($last_commenter_ip == $REMOTE_ADDR) $same_ip = true;
	
	if($commenter_id == $id) $same_commenter = true;

	if($same_ip != true and $same_commenter != true){

		$is_sql = "
			insert into $tb_comments (
				id,
				user_id,
				comment,
				author_id,
				author_ip,
				status
			) values (
				'',
				'$id',
				'$comment',
				'$commenter_id',
				'$REMOTE_ADDR',
				'approved'
			)
		";

		if($is_query = sql_query($is_sql)){
	
			$message = "Comment Accepted, Thank You!";

			$ic_sql = "
				update
					$tb_users
				set
					total_comments = total_comments + 1
				where
					id = '$id'
			";

			$ic_query = sql_query($ic_sql);
		
		} else $message = "Your comment was not recorded.  Database error.";
	}

} else $error_message = "Comment Submission Error";

if(isset($same_ip) and $same_ip == true)
	$message = "Your comment was not recorded.  Our records indicate you have<br />submitted a commit recently.  Come back later to comment again.";

if(isset($same_commenter) and $same_commenter == true)
	$message = "Your comment was not recorded.  You are not allowed to comment about your own picture.";

if(!isset($message)) $message = "";

if(!isset($com_error)){

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" align="center"><br />$message<br /><br />
EOF;

if(isset($sing) and $sing > 0){

$content .= <<<EOF
<a href="$base_url/index.php?s=$s&amp;show=view&amp;$sn=$sid&amp;sing=$sing">Click Here to Continue</a>
EOF;
} else {

$content .= <<<EOF
<a href="$base_url/index.php?s=$s&amp;show=view&amp;$sn=$sid&amp;sr=$sr&amp;pp=$pp&amp;cp=$cp">Click Here to Continue</a>
EOF;
}

$content .= <<<EOF
<br /><br /></td>
</tr>
</table>
EOF;
}

} 

if(isset($com_error) || !isset($submit_comment)){

	$com_sql = "
		select
			*
		from
			$tb_users
		where
			id = '$id'
	";
	$com_query = sql_query($com_sql);
	$com_array = sql_fetch_array($com_query);

$img_src = get_image($id);

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">
EOF;

if(!isset($sing)) $sing = 0;

$content .= profile_bar($show, $sing, $s, $com_array["id"]);

$content .= <<<EOF
</td>
</tr>
</table>
<table cellpadding="1" cellspacing="0" border="0" bgcolor="black">
<tr>
<td bgcolor="black"><img src="$img_src" alt="" /></td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

$title = "Comment";

if(!isset($comment)) $comment = "";

$comment = stripslashes($comment);

$content = <<<EOF
<blockquote>
<br />
<form method="post" action="$base_url/index.php?$sn=$sid&amp;show=comment#comment">
<a name="comment" class="regular">Comment:</a>
<br />
<textarea name="comment" rows="7" cols="45">$comment</textarea>
EOF;


if(isset($com_error)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$error_message</td>
</tr>
</table>
EOF;
}

if(!isset($com_html)) $com_html = "";

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$com_html</td>
</tr>
</table>
<br />
<input type="submit" name="submit_comment" value=" Submit Comment -> " />
<br />
<input type="hidden" name="commenter_id" value="$commenter_id" />
<input type="hidden" name="id" value="$com_array[id]" />
<input type="hidden" name="cp" value="$cp">
<input type="hidden" name="pp" value="$pp">
<input type="hidden" name="sr" value="$sr">
<input type="hidden" name="s" value="$s">
EOF;

if($sing > 0){
$content .= <<<EOF
<input type="hidden" name="sing" value="$sing">
EOF;
}

$content .= <<<EOF
</form></blockquote><br />
EOF;
}

$final_output .= table($title, $content);

/*
 * $Id: comment.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>
