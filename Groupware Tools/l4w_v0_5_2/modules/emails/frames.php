<?php

   	/*=====================================================================
	// $Id: frames.php,v 1.3 2005/05/02 11:59:07 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/
	
	$folder = $_REQUEST['folder'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN" "http://www.w3.org/TR/html4/frameset.dtd">
<html>
<head>
</head>

    <frameset rows='50%,50%' border=0 id='mailsframe'>
		<frame name='mails' src='index.php?command=show_mails&folder=<?=$folder?>' marginwidth=0 marginheight=0>
		<frame name='mail'  src='views/nomail.tpl'      marginwidth=0 marginheight=0>
	</frameset>

</html>