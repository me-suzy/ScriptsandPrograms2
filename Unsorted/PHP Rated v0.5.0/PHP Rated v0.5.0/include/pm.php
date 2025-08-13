<?

/*
 * $Id: pm.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
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
	<td align="center" class="regular">Sorry there aren't any yet.</td>
</tr>
</table>
EOF;

}

$final_output .= table($title, $nav);

if(session_is_registered("userid"))
	$author_id = $userid;
else
	$author_id = 0;

$pm_sql = "
	select
		*
	from
		$tb_users
	where
		id = '$id'
";
$pm_query = sql_query($pm_sql);
$pm_array = sql_fetch_array($pm_query);

$content = "";

$title = "Leave " . $pm_array["username"] . " a Private Message";

if(isset($submit_message)){

if(strlen($message)<20){
	$pm_error = 1;
	$pm_html = "Your message must be at least 20 characters in length.";
}

if(strlen($message)>255){
	$pm_error = 1;
	$pm_html = "Your message must be less than 255 characters in length.";
}

if(strlen($subject)<5){
	$pm_subject_error = 1;
	$subject_html = "Your message subject must be at least 5 characters in length.";
}

if(strlen($subject)>50){
	$pm_subject_error = 1;
	$subject_html = "Your message subject must be less than 50 characters in length.";
}

if(!isset($pm_error)){

	$check_ip_sql = "
		select
			*
		from
			$tb_pms
		where
			user_id	=	'$user_id'
		order by
			timestamp desc
		limit
			0, 1
	";

	$check_ip_query = sql_query($check_ip_sql);
	$last_messenger_ip = sql_result($check_ip_query, "0", "author_ip");
	$last_messenger_id = sql_result($check_ip_query, "0", "author_id");

	if($last_messenger_ip != $REMOTE_ADDR && $user_id != $author_id){

		$is_sql = "
			insert into $tb_pms (
				id,
				user_id,
				subject,
				message,
				author_id,
				author_ip,
				pm_status
			) values (
				'',
				'$user_id',
				'$subject',
				'$message',
				'$author_id',
				'$REMOTE_ADDR',
				'inbox'
			)
		";

		if($is_query = sql_query($is_sql))
			$return_message = "Private Message Accepted, Thank You!";
		else
			$return_message = "Your Private Message was not recorded, a database error was encountered.";
	}

} else $error_message = "Private Message Submission Error";

if(isset($last_messenger_ip) and $last_messenger_ip == $REMOTE_ADDR)
	$return_message = "Your Private Message was not recorded.  Our records indicate you have<br />submitted a Private Message recently.  Come back later to comment again.";

if($user_id == $author_id)
	$return_message = "Your Private Message was not recorded.  You are not allowed to leave Private Messages for yourself.";

if(!isset($pm_error)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular" align="center"><br />$return_message<br /><br />
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

// Show message form
if(isset($pm_error) || !isset($submit_message)){

	$pm_sql = "
		select
			*
		from
			$tb_users
		where
			id = '$id'
	";
	$pm_query = sql_query($pm_sql);
	$pm_array = sql_fetch_array($pm_query);

$img_src = get_image($id);

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="regular">
EOF;

if(!isset($sing)) $sing = 0;

$content .= profile_bar($show, $sing, $s, $pm_array["id"]);

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
$content = "";

$title = "Private Message";

if(!isset($message)) $message = "";

$message = stripslashes($message);

if(!isset($subject)) $subject = "";

if(!session_is_registered("userid")){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td align="center"><br /><a name="pm">You must be logged in to leave a private message.</a><br /><br /><a href="$base_url/index.php?$sn=$sid&amp;show=signup">Click Here to Signup</a>.</td>
</tr>
</table>
EOF;
}

if(session_is_registered("userid")){
$content .= <<<EOF
<blockquote>
<br />
<form method="post" action="$base_url/index.php?$sn=$sid#pm">
<a name="pm" class="regular">Subject:</a>
<br />
<input type="text" name="subject" value="$subject" size="35" />
<br />
EOF;
}

if(isset($pm_subject_error)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$subject_html</td>
</tr>
</table>
EOF;
}

if(session_is_registered("userid")){
$content .= <<<EOF
<br />
<span class="regular">Message:</span>
<br /><textarea name="message" rows="7" cols="45">$message</textarea>
EOF;
}


if(isset($pm_error)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$error_message</td>
</tr>
</table>
EOF;
}

if(!isset($pm_html)) $pm_html = "";

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
	<td class="error">$pm_html</td>
</tr>
</table>
<br />
EOF;

if(session_is_registered("userid")){
$content .= <<<EOF
<input type="submit" name="submit_message" value=" Submit Private Message -> " />
<br />
<input type="hidden" name="author_id" value="$author_id" />
<input type="hidden" name="user_id" value="$pm_array[id]" />
<input type="hidden" name="show" value="pm" />
<input type="hidden" name="id" value="$id" />
<input type="hidden" name="cp" value="$cp">
<input type="hidden" name="pp" value="$pp">
<input type="hidden" name="sr" value="$sr">
<input type="hidden" name="s" value="$s">
EOF;

if(isset($sing) and $sing == 1){
$content .= <<<EOF
<input type="hidden" name="sing" value="$sing">
EOF;
}
}

$content .= <<<EOF
</form></blockquote><br />
EOF;

}

$final_output .= table($title, $content);

/*
 * $Id: pm.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>