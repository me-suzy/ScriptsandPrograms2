<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// #### //INSTALL - UPGRADE to 1.0.0 RELEASE CANDIDATE 1\\ ### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

header('Expires: Mon, 5 Jul 1987 05:00:00 GMT'); 
header('Pragma: no-cache'); 

include("./../includes/functions_install.php");

iHeader("wtcBB Upgrade to wtcBB 1.0.0 Release Candidate 1");

// yikes, no config file??
if(!file_exists("./../includes/config.php")) {
	iTitle("Error!");
	print('<p>Sorry, I could not locate the <strong>config.php</strong> file in the <strong>includes</strong> directory.</p>');
	iFooter();
	exit;
}

// chmod problems?
if(!($images = @fopen("./../images/index2.html","w+")) OR !($smilies = @fopen("./../images/smilies/index2.html","w+")) OR !($postIcons = @fopen("./../images/post_icons/index2.html","w+")) OR !($avatars = @fopen("./../avatars/index2.html","w+")) OR !($attachments = @fopen("./../attachments/index2.html","w+")) OR !($export = @fopen("./../export/index2.html","w+"))) {
	iTitle("Error!");
	print('<p>Sorry, one or more of the directories that should have read and write access do not. Please refer to the <strong>installation.html</strong> file that was included in your downloaded zip file.</p>');
	iFooter();
	exit;
}

// delete index2.html's...
@unlink("./../images/index2.html"); @unlink("./../images/smilies/index2.html");
@unlink("./../images/post_icons/index2.html"); @unlink("./../avatars/index2.html");
@unlink("./../attachments/index2.html"); @unlink("./../export/index2.html");

include("./../includes/config.php");
include("./../includes/functions.php");

// uh oh!
if($bboptions['version_text'] != "wtcBB 1.0.0 BETA 3" AND $_GET['step'] < 4) {
	iTitle("Error!");
	print('<p>Sorry, this is an upgrade from wtcBB 1.0.0 BETA 3 to wtcBB 1.0.0 Release Candidate 1.</p>');
	iFooter();
	exit;
}

if($_GET['step'] == 1 OR empty($_GET)) {
	iTitle("Step 1 - Connection");
	print('<p>I have succssfully connected to the database. This process will upgrade your current version, wtcBB 1.0.0 BETA 3 to wtcBB 1.0.0 Release Candidate 1. It is <strong>strongly</strong> recommended that before you proceed with this process, that you have a backup of your database, as if you encounter any problems throughout this upgrade process, you will need to reinstate the old database, and repeat this upgrade process. Click next to proceed.</p>');
	iForm(2,"upgrade_1-0-0_RC_1.php");
}

else if($_GET['step'] == 2) {
	iTitle("Step 2 - Update Style Information &amp; Templates");

	print('<p>Updating style inforamtion &amp; templates... Please wait.</p>');

	include("./../includes/functions_xml.php");
	xml_import(file_get_contents("./install_style.xml"),true,true);

	print('<p>Style Information &amp; Templates were updated successfully. Click next to proceed.</p>');
	iForm(3,"upgrade_1-0-0_RC_1.php");
}

else if($_GET['step'] == 3) {
	iTitle("Step 3 - Update Version Number");

	print('<p>Updating version number...</p>');

	include("./install_mysql.php");
	query($mysql['update_1-0-0_RC_1']['update']['wtcBBoptions']);

	print('<p>Updated version number successfully.</p>');
	iForm(4,"upgrade_1-0-0_RC_1.php");
}

else if($_GET['step'] == 4) {
	iTitle("Upgrade Complete!");
	print('<p>You have successfully upgraded your wtcBB 1.0.0 BETA 3 message board to <strong>wtcBB 1.0.0 Release Candidate 1</strong>.</p>');
	print('<p><a href="./../index.php">Message Board</a> - <a href="./../admin/index.php">Administrator Control Panel</a></p>');
}

iFooter();

?>