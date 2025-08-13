<?

/*
 * $Id: member_menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

$inbox_count = total_messages($userid, "inbox");
$saved_count = total_messages($userid, "saved");
$trash_count = total_messages($userid, "trash");
$image_status = image_status($userid);

$title = "Member Menu";

$content = <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr>
<td align="right" valign="top">
<a href="$base_url/index.php?$sn=$sid&amp;show=profile" target="_top">Edit Profile</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=upload" target="_top">Image ($image_status)</a>
<br />
<a href="$base_url/index.php?$sn=$sid&amp;show=messages" target="_top">Messages ($inbox_count/$saved_count/$trash_count)</a>
<br />
</td>
</tr>
</table>
EOF;

$final_output .= table($title, $content);

/*
 * $Id: member_menu.php,v 1.1.1.1 2002/08/31 14:25:16 destiney Exp $
 *
 */

?>