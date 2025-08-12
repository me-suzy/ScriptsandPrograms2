<?php

//
// +----------------------------------------------------------------------+
// | Web Manager                                                          |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004 Protung Dragos (www.protung.ro)                   |
// +----------------------------------------------------------------------+
// +----------------------------------------------------------------------+
// |   filename             : index.php                                   |
// |   begin                : 20 07 2004                                  |
// |   copyright            : (C) 2004 Dragos Protung                     |
// |   email                : dragos@protung.ro                           |
// |   lastedit             : 20/08/2004 17:37                            |
// |                                                                      |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author: Protung Dragos <dragos@protung.ro>                           |
// +----------------------------------------------------------------------+
//

define('IN_PHPWC', true);

// include required files
require_once('includes/common.php');

if (PHPWC_NEW_VERSION_ALERT) {

	$data = check_new_version (PHPWC_VERSION);

	if ($data) {

		$new_version_alert  = ' [ New version ] \nVersion ' . $data[1] . ' is available for download.\n\n\n';
		$new_version_alert .= ' [ New this version ] \n' . $data[2] . '\n\n\n';
		$new_version_alert .= ' [ Download ]\n Do you want to download the new version now ?\n If you click cancel you can go later to http://protung.ro for the latest update !';
	}
}

?>


<script type="text/javascript"> 

	<!--

		msg = '<?=$new_version_alert?>';
		if (msg != '') {

			if (confirm(msg)) {
			
				parent.location.href = 'http://protung.ro';
			}
		}
		curent_panel = 'left';
		other_panel  = 'right';

	-->

</script>

<html>

<head>
<title>Web commander</title>
</head>

<frameset rows="45,*,50" framespacing="0" border="0" frameborder="0">
	<frame name="bar" scrolling="no" src="bar.php" marginwidth="0" marginheight="0" noresize>
	<frameset cols="50%,50%">
		<frame name="left" target="_self" scrolling="Yes" src="panel.php?panel=left"  marginwidth="0" marginheight="0">
		<frame name="right" target="_self" scrolling="Yes" src="panel.php?panel=right" marginwidth="0" marginheight="0">
	</frameset>
	<frame name="extra" scrolling="no" src="extra.php" marginwidth="0" marginheight="0" noresize>
</frameset>

<noframes>
	<body>
		<p>This page uses frames, but your browser doesn't support them. Get yourself a real browser</p>
	</body>
</noframes>

</html>