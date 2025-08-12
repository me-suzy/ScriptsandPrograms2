<?php

if(isset($_SESSION['userid'])){

$inbox_count = total_messages($_SESSION['userid'], "inbox");
$saved_count = total_messages($_SESSION['userid'], "saved");
$trash_count = total_messages($_SESSION['userid'], "trash");
$image_status = image_status($_SESSION['userid']);

$final_output .= <<<EOF
<table cellpadding="1" cellspacing="0" border="0" width="100%">
<tr>
	<td class="bold">[ <a class="bold" href="$base_url/profile.php" target="_top">My Profile</a> | <a class="bold" href="$base_url/upload.php" target="_top">My Image ($image_status)</a> | <a class="bold" href="$base_url/messages.php" target="_top">My Messages ($inbox_count/$saved_count/$trash_count)</a> ]</td>
	<td class="bold" align="right">Welcome $_SESSION[username]
EOF;

if(isset($_SESSION['sl']) && $_SESSION['sl']){
$final_output .= <<<EOF
, <a class="bold" href="$base_url/logout.php" target="_top">Logout</a>
EOF;
}

$final_output .= <<<EOF
&nbsp;</td>
</tr>
</table>
EOF;

} else {

$final_output .= <<<EOF
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><form method="post" action="$base_url/login.php">
	<td class="bold"><a class="bold" href="$base_url/signup.php">>>> Signup Now! <<<</a></td>
	<td class="bold" align="right">username: <input class="input" type="text" name="UN" size="10" value=""> password: <input class="input" type="password" name="PW" size="10" value=""> <input class="button" type="submit" name="login" value="Login">&nbsp;</td>
</form></tr>
</table>
EOF;

}

?>