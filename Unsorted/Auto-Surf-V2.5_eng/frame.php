<?php
require('./prepend.inc.php');
?>
<html>
<head>
<title><? echo "$seitenname"; ?> Surfbar</title>
</head>
<frameset rows="100,*" frameborder="NO" border="0" framespacing="0">
<frame name="top" scrolling="NO" noresize src="frame_top.php?userid=<?php echo $userid; ?>" >
<frame name="bottom" src="frame_bottom.html">
</frameset>
</html>