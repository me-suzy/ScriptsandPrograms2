<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
	<title></title>
</head>
<?php
	include ("../config/config.inc.php");
	
	if ($SHOW_POSITION_IN_MAIL == true)
	{
?>	
		<frameset cols="180,*" frameborder="1" framespacing="0" border="1">
			<frame name="FRAME_POSITION" src="mail_content_position.php?cpid=<?php echo $_REQUEST["cpid"];?>&language=<?php echo $_REQUEST["language"];?>" marginwidth="0" marginheight="0" scrolling="auto" frameborder="1">
		    <frame name="FRAME_VALUES" src="mail_content_values.php?cpid=<?php echo $_REQUEST["cpid"];?>&language=<?php echo $_REQUEST["language"];?>" frameborder="0" scrolling="Auto" marginwidth="0" marginheight="0">
		</frameset
<?php 
	}
	else
	{
?>	
		<frameset cols="*" frameborder="0" framespacing="0" border="0">
			<frame name="FRAME_VALUES" src="mail_content_values.php?cpid=<?php echo $_REQUEST["cpid"];?>&language=<?php echo $_REQUEST["language"];?>" frameborder="0" scrolling="Auto" marginwidth="0" marginheight="0">
		</frameset
<?php 
	}
?>
</html>