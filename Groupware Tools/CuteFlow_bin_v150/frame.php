<?php
session_start();
if ( (!isset($_SESSION['SESSION_CUTEFLOW_USERNAME'])) | (!isset($_SESSION['SESSION_CUTEFLOW_PASSWORD'])) )
{
	//--- no user logged in, so go to login-mask
	header("Location: index.php?language=$language");
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title></title>
	<link rel="stylesheet" href="pages/format.css" type="text/css">
</head>
<?php
	//--- insert language file
	if ($language == "")
	{
		$language = "de";		//--- default language is english (TODO)
	}
?>
	<frameset rows="25,*" framespacing="0" border="0" frameborder="0">
		<frame name="Header" src="pages/header.php?language=<?php echo $_REQUEST["language"];?>" marginwidth="0" marginheight="0" scrolling="no" frameborder="0" noresize>
		<frameset cols="180,*" frameborder="0" framespacing="0" border="0">
		    <frame name="frame_menu" src="pages/menu.php?language=<?php echo $_REQUEST["language"];?>" marginwidth="0" marginheight="0" scrolling="auto" frameborder="0" noresize>
	    	<frame name="frame_details" src="pages/empty.html" frameborder="0" scrolling="Auto" marginwidth="0" marginheight="0">
		</frameset>
	</frameset>
</html>