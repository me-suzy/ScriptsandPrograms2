<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################## //INSTALL - INSTALL\\ ################## \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

header('Expires: Mon, 5 Jul 1987 05:00:00 GMT'); 
header('Pragma: no-cache'); 

include("./../includes/functions_install.php");

iHeader("wtcBB Installation");

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

// connect to MySQL database
$link = mysql_connect($host,$db_username,$db_password) OR die(mysql_error().mail($db_email,"DATABASE ERROR","There has been a database error because: ".mysql_error()));

mysql_select_db($db_name,$link) OR die("Could not locate database.");

if($_GET['step'] == 1 OR empty($_GET)) {
	iTitle("Step 1 - Connection");
	print('<p>I have successfully connected to the database. If you encounter any problems throughout this installation process, you will need to completely empty your database of tables made by this process. You will now install <strong>wtcBB 1.1.6</strong>. Click next to proceed.</p>');
	iForm(2);
}

else if($_GET['step'] == 2) {
	iTitle("Step 2 - Creating Tables");

	print('<p>Creating tables...</p>');

	include("./install_mysql.php");

	foreach($mysql['create'] as $tableName => $value) {
		//print($tableName." ==== ".$value."<br /><br />");
		mysql_query($value) OR DIE($tableName." --- ".mysql_error());
	}

	print('<p>Tables created. Click next to proceed.</p>');
	iForm(3);
}

else if($_GET['step'] == 3) {
	iTitle("Step 3 - Populate Tables");

	print('<p>Populating tables...</p>');

	include("./install_mysql.php");

	foreach($mysql['insert'] as $tableName => $value) {
		if($tableName == "posts" OR $tableName == "threads" OR $tableName == "admin_permissions" OR $tableName == "user_info") continue;
		mysql_query($value) OR die('<p>'.$tableName.' --- '.mysql_error().'</p>');
	}

	print('<p>Tables were populated successfully. Click next to proceed.</p>');
	iForm(4);
}

else if($_GET['step'] == 4) {
	iTitle("Step 4 - Import Style Information & Templates");

	print('<p>Importing style information & templates... Please wait.</p>');

	include("./../includes/functions.php");
	include("./../includes/functions_xml.php");
	xml_import(file_get_contents("./install_style.xml"),true);

	print('<p>Style Information & Templates were imported successfully. Click next to proceed.</p>');
	iForm(5);
}

else if($_GET['step'] == 5) {
	include("./../includes/functions.php");

	if($_POST['install']['set_form']) {
		// uh oh!
		if(!$_POST['install']['username'] OR !$_POST['install']['password'] OR !$_POST['install']['details_boardname'] OR !$_POST['install']['details_boardurl']) {
			$theError = '<p>Make sure you fill out all required information.</p>';
		}

		else {
			$theUsername = htmlspecialchars(addslashes(trim($_POST['install']['username'])));
			$thePassword = htmlspecialchars(addslashes(trim($_POST['install']['password'])));

			// good to go!
			// run queries that we held off on in step 3...
			include("./install_mysql.php");
			foreach($mysql['insert'] as $tableName => $arr) {
				if($tableName == "posts" OR $tableName == "threads" OR $tableName == "admin_permissions" OR $tableName == "user_info") {
					mysql_query($arr) OR die('<p>'.$tableName.' --- '.mysql_error().'</p>');
				}
			}

			mysql_query($mysql['update']['user_info']) OR die('<p>'.$tableName.' --- '.mysql_error().'</p>');

			// update wtcbboptions
			mysql_query("UPDATE wtcBBoptions SET details_boardname = '".htmlspecialchars(addslashes(trim($_POST['install']['details_boardname'])))."' , details_boardurl = '".htmlspecialchars(addslashes(trim($_POST['install']['details_boardurl'])))."' , details_homepage = '".htmlspecialchars(addslashes(trim($_POST['install']['details_homepage'])))."' , details_homepageurl = '".htmlspecialchars(addslashes(trim($_POST['install']['details_homepageurl'])))."' , cookie_path = '".htmlspecialchars(addslashes(trim($_POST['install']['cookie_path'])))."' , cookie_domain = '".htmlspecialchars(addslashes(trim($_POST['install']['cookie_domain'])))."' , details_contact = '".htmlspecialchars(addslashes(trim($_POST['install']['details_contact'])))."'");

			// update forum last_reply_username
			mysql_query("UPDATE forums SET last_reply_username = '".$theUsername."' WHERE forumid = 2");
			
			iTitle("Installation Complete!");
			print('<p>You have successfully installed wtcBB 1.1.6</p>');
			print('<p><a href="./../index.php">Message Board</a> - <a href="./../admin/index.php">Administrator Control Panel</a></p>');
			iFooter();
			exit;
		}
	}

	iTitle("Step 5 - Provide Information");

	if(!empty($theError)) print($theError);

	$cookieDomain = preg_replace("|www.|","",$_SERVER['SERVER_NAME']);
	$combined = $_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
	$detailsBoardUrl = preg_replace("|/install/install.php|","",$combined);

	iTable("options","install","install_submit",1);

	iHeaderT("Installation Information <span class=\"small\">(* - Required Information)</span>",2);

	iText(1,"*Username","Enter your username that you desire here.","install","username");

	iText(2,"*Password","Enter your password that you desire here.","install","password");

	iText(1,"Contact Email","This email address is used when message board mail is sent.","install","details_contact","webmaster@".$cookieDomain);

	iText(2,"*Message Board Name","Enter the name of your message board here.","install","details_boardname");

	iText(1,"*Message Board URL","Enter the URL of your board here. Omit any trailing \"/\"","install","details_boardurl","http://".$detailsBoardUrl);

	iText(2,"Homepage Name","Enter the name of your homepage here.","install","details_homepage");

	iText(1,"Homepage URL","Enter the URL of your homepage here.","install","details_homepageurl","http://".$_SERVER['SERVER_NAME']);

	iText(2,"Cookie Path","If you are unsure as of what to put here, leave it as a \"/\"","install","cookie_path","/");

	iText(1,"Cookie Domain","Usually this is simply the domain of your web site. Note the preceeding dot.","install","cookie_domain",".".$cookieDomain,1);

	iFooterT(2,"install_submit");

	iTableEnd(1);
}

iFooter();

?>