<?php

$vote_bar = <<<EOF
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr>
<td class="regular">
<table cellpadding="1" cellspacing="0" border="0">
<tr><form method="post" action="$base_url/rate.php">
<input type="hidden" name="user_id" value="$i">
<input type="hidden" name="page" value="index">
<td class="regular">&nbsp;&nbsp;Awesome!</td>
<td class="regular">&nbsp;Fair&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td class="regular" align="right">Worst&nbsp;&nbsp;</td>
</tr>
<tr>
<td class="regular" colspan="3" align="center" valign="absmiddle" nowrap background="$base_url/images/bar.jpg">
EOF;

for($x=10; $x>-1; $x--){

$vote_bar .= <<<EOF
<input type="radio" name="submit_rating" value="$x" onclick="this.form.submit()">$x&nbsp;&nbsp;
EOF;

}

$vote_bar .= <<<EOF
</td>
</tr>
</table>
</td>
</tr>
</table>
EOF;

?>