<?

/*
 * $Id: messages.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

if(!isset($folder)) $folder = "inbox";

$content = "";
$title = "Private Messages";

if(session_is_registered("userid")){

if(isset($empty_trash)){
	$return = empty_trash($userid);
	if($return == true) $msg_return = "Trash has been emptied.";
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

$content = get_messages($userid, $folder);

if($folder == "trash" && !isset($empty_trash)){

$total_trash = total_messages($userid, "trash");

if($total_trash > 0){

$trash_bar = empty_trash_bar();

$content .= <<<EOF
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular" align="center">$trash_bar</td>
</tr>
</table>
EOF;
}

}

$content .= "<br />";

}

if(!session_is_registered("userid")){
$content = <<<EOF
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
 * $Id: messages.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>