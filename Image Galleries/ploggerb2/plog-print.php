<?php

include("plog-functions.php");
include("plog-globals.php");

$picture = get_picture_by_id($_GET['id']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<body onload="window.print();">
		<img src="<?=$picture["url"]?>" alt="<?=$picture["caption"]?>" />
	</body>
</html>
