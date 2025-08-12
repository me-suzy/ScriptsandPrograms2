<?php

$image_src = get_image($i);

$user_image = <<<EOF
<table cellpadding="5" cellspacing="5" border="0">
<tr>
	<td class="regular">$image_src</td>
</tr>
</table>
EOF;

?>