<?php

$Q="SELECT id FROM users WHERE id=1 LIMIT 1";
list($is_installed) = @mysql_fetch_row(mysql_query($Q));
if ($is_installed)
{
	header("Location: index.php?page=index&msg=".base64_encode("orange|Already instaled, thank you!"));
}

?>
