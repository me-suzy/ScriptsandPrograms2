<?php

    /*=====================================================================
	// $Id: online_iframe.php,v 1.4 2005/06/11 08:26:11 carsten Exp $
    // copyright evandor media Gmbh 2003
	//=====================================================================*/

	include ("inc/pre_include_standard.inc.php");
	$css_path = get_skin_css_path ($user_id);
	$img_path = get_skin_img_path ($user_id);

    if (!isset($add_module_path_offset)) $add_module_path_offset = "";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title></title>
<link rel='stylesheet' type='text/css' href='<?=$css_path?>bottom.css'>

<!-- ruft sich alle drei Minuten selbst auf, um die Anzeige zu aktualisieren -->
<meta http-equiv="Refresh" content="180; url=online_iframe.php">
</head>
<body marginwidth=0 marginheight=0 topmargin=0 leftmargin=0>
<?php

	// Counter updaten:
	$res = mysql_query ("SELECT COUNT(*) FROM ".TABLE_PREFIX."useronline WHERE user_id='$user_id'");
	$row = mysql_fetch_array ($res);
	logDBError (__FILE__, __LINE__, mysql_error());
	if ($row[0] < 1) {
		$insert_query = "INSERT INTO ".TABLE_PREFIX."useronline (timestamp, user_id) VALUES ('".time()."', '$user_id')";
		mysql_query ($insert_query);
	    logDBError (__FILE__, __LINE__, mysql_error(), $insert_query);
	}
	// Auslesen:
	$res = mysql_query ("SELECT user_id FROM ".TABLE_PREFIX."useronline");
    logDBError (__FILE__, __LINE__, mysql_error());
	$count = 0;
	$users_logged_in = "";
	while ($row = mysql_fetch_array ($res)) {
		$count++;
		$user_res = mysql_query ("SELECT login FROM ".TABLE_PREFIX."users WHERE id='".$row['user_id']."'");
		$user_row = mysql_fetch_array ($user_res);
		logDBError (__FILE__, __LINE__, mysql_error());
		$users_logged_in .= $count.": ".$user_row['login']."\n";
	}
?>
	<table border=0 cellpadding=0 cellspacing=0 width='110%'>
		<tr>
			<td valign="middle" align='left' height=20 class=leiste><?php
	echo "<a class='link_no_frame' href='#' title='$users_logged_in'>".$count."</a>";
	?>
	</td></tr></table>
<?php

?>
</body>
</html>