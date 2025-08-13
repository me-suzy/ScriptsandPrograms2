<?

/*
 * $Id: view_msg.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$content = "";
$title = "Private Messages";

if(session_is_registered("userid")){

if(isset($save_msg)){
	$save = move_message($msg_id,"saved");
	if($save == true) $msg_return = "Message saved.";
}

if(isset($delete_msg)){
	$delete = move_message($msg_id,"trash");
	if($delete == true) $msg_return = "Message deleted.";
}

if(isset($undelete_msg)){
	$undelete = move_message($msg_id,"saved");
	if($undelete == true) $msg_return = "Message saved.";
}

if(isset($msg_return)){
$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="bold" align="center">$msg_return</td>
</tr>
</table>
EOF;
}

$sql = "
	select
		$tb_users.username as author_name,
		$tb_users.sex as gender,
		$tb_pms.author_id as author_id,
		$tb_pms.id as pm_id,
		$tb_pms.subject as subject,
		$tb_pms.message as message,
		$tb_pms.timestamp as timestamp
	from
		$tb_pms
	left join
		$tb_users
	on
		$tb_users.id = $tb_pms.author_id
	where
		$tb_pms.id = '$msg_id'
	group by
		$tb_pms.pm_id
";

$query = sql_query($sql);

$array = sql_fetch_array($query);

$pretty_time = pretty_time($array["timestamp"]);

$folder_table = folder_table($userid);

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular" colspan="5" align="right">$folder_table</td>
</tr>
<tr>
<td class="regular">
<span class="bold">From:</span> <a href="$base_url/index.php?$sn=$sid&amp;show=view&amp;sing=$array[author_id]&amp;s=$array[gender]">$array[author_name]</a><br / >
<span class="bold">Subject:</span> $array[subject]<br />
<span class="bold">Recieved:</span> $pretty_time
</td>
</tr>
<tr>
<td colspan="3" class="regular"><blockquote>$array[message]</blockquote></td>
</tr>
</table>
EOF;

if(!isset($msg_return)){
	$msg_bar = mv_msg_bar($folder, $array["pm_id"]);
	$content .= $msg_bar;
}

}

if(!session_is_registered("userid")){
$content .= <<<EOF
<br />
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular" align="center">You must login to view messages.</td>
</tr>
</table>
<br />
EOF;
}

$final_output .= table($title, $content);

/*
 * $Id: view_msg.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>