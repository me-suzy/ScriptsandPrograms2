<?php

$total_comments = get_user_comments_count($i);

$profile_bar = <<<EOF
<table cellpadding="0" cellspacing="0" border="0">
<tr>
<td class="bold"><a class="bold" href="$base_url/my_comments.php?i=$i">My Comments($total_comments)</a>&nbsp;&nbsp;</td>
<td class="bold"><a class="bold" href="$base_url/comment.php?i=$i">Leave Comment</a>&nbsp;&nbsp;</td>
<td class="bold"><a class="bold" href="$base_url/message.php?i=$i">Private Message</a>&nbsp;&nbsp;</td>
<td class="bold"><a class="bold" href="$base_url/send_to_friend.php?i=$i">Send to Friend</a>&nbsp;&nbsp;</td>
<td class="bold"><a class="bold" href="mailto:$owner_email?Subject=Image Report ID: $i">Report Image</a>&nbsp;</td>
</tr>
</table>
EOF;

?>