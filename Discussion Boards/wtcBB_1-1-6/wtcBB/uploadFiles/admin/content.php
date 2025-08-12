<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################# //ADMIN PANEL CONTENT\\ ################# \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// define a few variables
$fileAction = "Index";

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

// if phpinfo show it..
if($_GET['do'] == "phpinfo") {
	phpinfo();
	exit;
}

// do header
admin_header("wtcBB Admin Panel - Main - Quick Stuff");

construct_title("Quick Stuff");

// do version check.. but only if we can establish a connection
if(@fopen("http://www.webtrickscentral.com/webtrickscentralBB/versionCheck.js","r")) {
	// this script is just the variable for the current version
	print('<script type="text/javascript" src="http://www.webtrickscentral.com/webtrickscentralBB/versionCheck.js"></script>');

	// 'nowVersion' is most up to date version
	?>
	<script type="text/javascript">
	// this message board's version
	currVersion = "<?php print($bboptions['version_num']); ?>";

	if(nowVersion > currVersion) {
		document.writeln('<p style="width: 90%; margin-left: auto; margin-right: auto; color: #bb0000; font-weight: bold;">You are using an out of date version. The newest version is ' + nowVersion + '. You can download the latest version <a href="http://www.webtrickscentral.com/download.php?do=wtcBB" target="_blank">here</a>.</p>');
	}

	if(imperativeMessage != null) {
		document.writeln('<p style="width: 90%; margin-left: auto; margin-right: auto; color: #bb0000; font-weight: bold;">' + imperativeMessage + '</p>');
	}

	</script>
	<?php
}

print('<p style="width: 90%; margin-left: auto; margin-right: auto;">Welcome to the WebTricksCentral Bulletin Board Administrator Control Panel! This is the place where you can control <em>every</em> aspect of your message board. Enable or disable things, add your own custom bulletin board code, change the colors, edit users, edit HTML and CSS templates, and much more!</p>');

construct_table("options","quick","quick_submit");

construct_header("Quick Stuff",2);

	print("\t<tr>\n");
		print("\t\t<td class=\"desc1\"><strong>User Search</strong></td>\n");
		print("\t\t<td class=\"input1\">\n");
		print("\t\t\t<form action=\"user.php\" method=\"get\" style=\"margin: 0;\"><input type=\"hidden\" name=\"search_user[set_form]\" value=\"1\" /><input type=\"hidden\" name=\"do\" value=\"search\" /><input type=\"text\" class=\"text\" style=\"width: 50%;\" name=\"search_user[username]\" id=\"theUsername\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form> \n\n<script type=\"text/javascript\">\n\tobj = document.getElementById('theUsername'); obj.focus();\n</script>\n\n</td>\n");

		print("\n\n</tr><tr>\n\n");

		print("\t\t<td class=\"desc2\"><strong>PHP Function Search</strong></td>\n");
		print("\t\t<td class=\"input2\">\n");
		print("\t\t\t<form action=\"http://www.php.net/manual-lookup.php\" method=\"get\" style=\"margin: 0;\"><input type=\"text\" class=\"text\" style=\"width: 50%;\" name=\"function\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form>\n\n</td>\n");

		print("\n\n</tr><tr>\n\n");

		print("\t\t<td class=\"desc1_bottom\"><strong>MySQL Language Search</strong></td>\n");
		print("\t\t<td class=\"input1_bottom\">\n");
		print("\t\t\t<form action=\"http://www.mysql.com/doc/manual.php\" method=\"get\" style=\"margin: 0;\"><input type=\"hidden\" name=\"depth\" value=\"2\" /><input type=\"text\" name=\"search_query\" class=\"text\" style=\"width: 50%;\" /> &nbsp;&nbsp; <input type=\"submit\" value=\"Find\" style=\"margin-bottom: 4px;\" ".$submitbg."></form>\n\n</td>\n");
	print("\t</tr>\n");

print("\t<tr><td class=\"footer\" colspan=\"2\">&nbsp;</td></tr>\n");

construct_table_END();


print("\n\n<br /><br />\n\n");


construct_table("options","stats","stats_submit");

construct_header("Quick Stats",4);

	$currTime = mktime(0,0,0,date("m"),date("d"),date("Y"));

	// do quick stats
	$newusersjoined = query("SELECT COUNT(*) AS count FROM user_info WHERE date_joined >= '".$currTime."' AND username != 'Guest'",1);
	$uniqueusers = query("SELECT COUNT(*) AS count FROM user_info WHERE lastactivity >= '".$currTime."' AND username != 'Guest'",1);
	$totalUsers2 = query("SELECT COUNT(*) AS count FROM user_info WHERE username != 'Guest'",1);
	$totalPosts = query("SELECT COUNT(*) AS count FROM posts WHERE deleted = 0 OR deleted IS NULL",1);
	$newPosts = query("SELECT COUNT(*) AS count FROM posts WHERE date_posted >= '".$currTime."'",1);
	$totalThreads = query("SELECT COUNT(*) AS count FROM threads WHERE (deleted_thread = 0 OR deleted_thread IS NULL) AND (moved = 0 OR moved IS NULL)",1);
	$newThreads = query("SELECT COUNT(*) AS count FROM threads WHERE date_made >= '".$currTime."'",1);
	$mysqlVersion = query("SELECT VERSION() AS version",1);
	$attachmentSize = query("SELECT SUM(size) AS count FROM attachments",1);
	$usersOnline = query("SELECT COUNT(*) AS members FROM sessions WHERE userid != 0",1);
	$guestCount = query("SELECT COUNT(*) AS guests FROM sessions WHERE userid = 0",1);
	$totalUsers = $usersOnline['members'] + $guestCount['guests'];

	$attachmentSize['count'] /= 1000000;

	//$serverLoad = exec("uptime 2>&1");

	//$serverLoad = split("load average: ",$serverLoad);
	$serverLoad = '';

	print("\t<tr>\n");
		print("\t\t<td><strong>PHP Version: </strong></td><td>".phpversion()."</td>\n");
		print("\t\t<td><strong>MySQL Version: </strong></td><td>".$mysqlVersion['version']."</td>\n");
	print("\t</tr>\n");

	print("\t<tr>\n");
		print("\t\t<td><strong>Users Joined Today: </strong></td><td>".$newusersjoined['count']."</td>\n");
		print("\t\t<td><strong>Unique Users Visited Today: </strong></td><td>".$uniqueusers['count']."</td>\n");
	print("\t</tr>\n");

	print("\t<tr>\n");
		print("\t\t<td><strong>Total Attachment Filesize: </strong></td><td>".$attachmentSize['count']." MB</td>\n");
		print("\t\t<td><strong>Total Users: </strong></td><td>".$totalUsers2['count']."</td>\n");
	print("\t</tr>\n");

	print("\t<tr>\n");
		print("\t\t<td><strong>Total Threads: </strong></td><td>".$totalThreads['count']."</td>\n");
		print("\t\t<td><strong>Total Threads Today: </strong></td><td>".$newThreads['count']."</td>\n");
	print("\t</tr>\n");

	print("\t<tr>\n");
		print("\t\t<td><strong>Total Posts: </strong></td><td>".$totalPosts['count']."</td>\n");
		print("\t\t<td><strong>Total Posts Today: </strong></td><td>".$newPosts['count']."</td>\n");
	print("\t</tr>\n");

	print("\t<tr>\n");
		print("\t\t<td><strong>Server Load Averages: </strong></td><td>".$serverLoad[1]."</td>\n");
		print("\t\t<td><strong>Total Online: </strong></td><td>".$totalUsers." (".$usersOnline['members']." Members; ".$guestCount['guests']." Guests)</td>\n");
	print("\t</tr>\n");

construct_table_END();


print("\n\n<br /><br />\n\n");

print("<div style=\"width: 90%; margin-left: auto; margin-right: auto;\">\n\n");
	print("<strong>Credits:</strong>\n");
	print("<p style=\"margin-top: 0;\">All graphics and coding were developed by <strong>Andrew Gallant (Handle: jamslam)</strong>.</p>");
	print("<p style=\"margin-top: 0;\">I would like to thank <strong>Justin Shreve (Handle: Scyth)</strong> for helping me with features, bug testing, and putting up with me. And any other future work he might do to help wtcBB.</p>");
print("\n</div>\n");

// do footer
admin_footer();

?>