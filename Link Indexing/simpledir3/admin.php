<?php

# SimpleDir 3.0
# Copyright 2003-2004 Tara, http://gurukitty.com/star. All rights reserved.
# Released June 19, 2004

# SimpleDir 3.0 is linkware and can be used or modified as long all notes on the SimpleDir 3.0 files remain intact, unaltered, and a link is placed on all pages used by the SimpleDir 3.0 script to http://gurukitty.com/star so others can find out about this script as well. You may modify this script to suit your wishes, but please follow my requests and do not distribute it.

# All I ask of you is the above and to not sell or distribute SimpleDir 3.0 without my permission.
# All risks of using SimpleDir 3.0 are the user's responsibility, not the creator of the script.
# For further information and updates, visit the SimpleDir 3.0 site at http://gurukitty.com/star.
# Thank you for downloading SimpleDir 3.0.

/*************INCLUDES*************/
require("config.php");
require("common.php");

/*************VARIABLES*************/
$do = $_REQUEST['do'];
$sql = $_REQUEST['sql'];
$action = $_REQUEST['action'];
$queryid = $_REQUEST['id'];
$text = $_REQUEST['text'];

/*************LOGIN*************/
if (!isset($PHP_AUTH_USER) || !isset($PHP_AUTH_PW))
  displayLogin(); 
else
{ 
  // Escape password && username
  $PHP_AUTH_USER = addslashes($PHP_AUTH_USER); 
  $PHP_AUTH_PW = md5($PHP_AUTH_PW);

  // Check username && password with config options
  if (($PHP_AUTH_USER == $adminname) && ($PHP_AUTH_PW == $dirpass))
    $logged = 'Y';
  else
    $logged = 'N';

  if ($logged == 'N')
    displayLogin(); 
}

require("first.inc.php");       // Include header

/******************************************************************************/
/******************************************************************************/
/*                        ?DO=**** SCREENS                                    */
/* After the main admin screen, they are in alphabetical order.								*/
/******************************************************************************/
/******************************************************************************/

/*************MAIN ADMIN SCREEN*************/
if(($do == "main") || ($query == "")) { ?>
  <p align="center"><u>Welcome!</u></p>
  <p>Welcome <b><?=$adminname?></b> to your SimpleDir administrative control panel! If this is your first visit please refer to the Misc. section to the left, particularly "Edit Options" and "Change Password" and edit the variables there before doing anything else. After that, feel free to add as many links and categories as you wish.</p>
  <?php
  $pendnum = snippetStatus(0);     // pending links query
  if($pendnum > 0)
    $apprlink = ' (<a href="admin.php?do=apprlink">View</a>)'; ?>
  <p><b>Pending links:</b> <?=$pendnum?><?=$apprlink?><br>
  <b>Approved Links:</b> <?=snippetStatus(1)?><br>
  <b>Total Links:</b> <?=snippetTotal('L')?><br>
  <b>Total Categories:</b> <?=snippetTotal('C')?></p>
  <?php
}

if($do == "addcat") { ?>
		
	<p align="center"><u>Add A Category</u></p>
		
	<form method="post" action="admin.php?sql=newcat">
	<p><b>Category Name:</b><br><input type="text" name="catname" size="25"></p>
	<p><b>Category Description:</b><br><textarea name="catDesc" rows="6" cols="40"></textarea></p>
	<p><input type="submit" value="Add"></p>
		
	<?php
}

if($do == "addlink") { ?>

	<p align="center"><u>Add A Link</u></p>

	<form method="post" action="admin.php?sql=newlinkcp">
	<input type="hidden" name="linkstatus" value="1">
	<p><b>Owner Name:</b><br><input type="text" name="ownername" size="30"></p>
	<p><b>Owner E-mail:</b><br><input type="text" name="owneremail" size="30"></p>
	<p><b>Link Name:</b><br><input type="text" name="linkname" size="30"></p>
	<p><b>Link URL:</b><br><input type="text" name="linkurl" size="30" value="http://"></p>
	<p><b>Category:</b><br><?php printCats('relCatID'); ?></p>
	<p><b>Link Description:</b><br><textarea name="linkdesc" rows="6" cols="40"></textarea></p>
	<p><b>Link Notes:</b><br><textarea name="linknotes" rows="6" cols="40"></textarea></p>
	<p><input type="submit" name="submit" value="Add"></p>

	<?php
}

if($do == "apprlink") {
	echo "<p align=\"center\"><u>Approve A Link</u></p>";
	$select = mysql_query ("SELECT linkname, linkID FROM $tblinks WHERE linkstatus = '0' ORDER BY linkID ASC");
	if(($num_rows = mysql_num_rows($select)) == 0){
		echo "<p>There are no pending links.</p>";
	} else {
		echo "<p>";
		do {
			if($row['linkname'] != ""){
				$linkID = $row['linkID'];
				$linkname = stripslashes($row['linkname']); ?>
				[<a href="admin.php?action=apprlinks&id=<?=$linkID?>"><?=$linkID?></a>] - <?=$linkname?><BR>
			<?php } 
		}
		while($row = mysql_fetch_array($select));
		echo "</p><p>To approve all of the above links: <a href=\"admin.php?sql=massappr\">Mass Approve</a>.</p>";
	}
	include("footer.inc.php");
}

if($do == "delcat") {
	changecats("delcats");
}

if($do == "editcat") {
	changeCats("editcats");
}

if($do == "editcfg") { ?>
  <p align="center"><u>Edit Configuration Options</u></p>
  <form method="post" action="admin.php?sql=updatecfg">
  <input type="hidden" name="id" value="1">

  <p><b>Directory's Name:</b><BR><i>The name of your site.</i><BR>
  <input type="text" name="sitename" size="40" value="<?=$sitename?>"></p>

  <p><b>Directory's URL:</b><BR><i>The URL to the root directory of your site. Be sure to leave the trailing slash (/) off the end. ex: http://www.yourdomain.com/dir</i><BR>
  <input type="text" name="siteurl" size="40" value="<?=$siteurl?>"></p>

  <p><b>Ability for Visitors to Add:</b><BR><i>Would you like your visitors to have the ability to add links to your site? Y or N</i><BR>
  <input type="text" name="visitoradd" size="40" value="<?=$visitoradd?>"></p>

  <p><b>Pending Status:</b><BR><i>Would you like links to receive pending status and wait for you to approve them? Y or N</i><BR>
  <input type="text" name="pend" size="40" value="<?=$pend?>"></p>

  <p><b>Allow Descriptions:</b><BR><i>Would you like the links in your site to have brief descriptions? Y or N</i><BR>
  <input type="text" name="allowdesc" size="40" value="<?=$allowdesc?>"></p>

  <p><b>Number of links per page:</b><BR><i>How many links would you like per page? Enter a number only. ex: 10, 25, 40. DO NOT set this to 0 (it will then be reset to the default - 25). If you don't want to use this feature, simply set this to a number higher than the quantity of links in any category.</i><br>
  <input type="text" name="numperpage" size="40" value="<?=$numperpage?>"></p>

  <p><b>E-mail Notification:</b><BR><i>This turns on or off all e-mail notification. SimpleDir will send an e-mail out to the owners of newly approved links and to you when there is a new link added. Y or N</i><BR>
  <input type="text" name="emailnotify" size="40" value="<?=$emailnotify?>"></p>

  <p><b>Your E-mail Address:</b><BR><i>Will be displayed on all e-mails sent by SimpleDir.</i><br>
  <input type="text" name="adminemail" size="40" value="<?=$adminemail?>"></p>

  <p><b>Use File Manager:</b><br><i>Would you like to use the built-in online file manager that comes with SimpleDir? Y or N.</i><br>
  <input type="text" name="usemanager" size="40" value="<?=$usemanager?>"></p>

  <p><b>Absolute Path:</b><br><i>The absolute path to the directory that contains the files that you would like to edit through the file manager. No trailing slash!</i><br>
  <input type="text" name="sitepath" size="40" value="<?=$sitepath?>"></p>

  <p><b>Category Selection Style:</b><br><i>Would you like the list of categories to be in the style of radio buttons or a drop-down menu? (R = radio buttons, D = drop-down menu)</i><br>
  <input type="text" name="catselect" size="40" value="<?=$catselect?>"></p>

  <p><input type="submit" name="submit" value="Submit"></p>
  </form>
  <?php
}

if($do == "editlogin") { ?>
  <p align="center"><u>Change Login Info</u></p>
  <form method="post" action="admin.php?sql=updatelogin">

  <p>Please note that after changing this information, you will have to re-login.</p>

  <p><b>Username:</b><BR><i>The name you would like the script to call you. This will also be your username.</i><BR>
  <input type="text" name="adminname" size="25" value="<?=$adminname?>"></p>

  <p><b>Old password:</b><br><i>Please enter your old password below for verification.</i><br>
  <input type="password" name="oldpass" size="25"></p>

  <p><b>New password:</b><br><i>Please enter your desired new password below.</i><br>
  <input type="password" name="newpass" size="25"></p>

  <p><input type="submit" name="submit" value="Submit"></p>
  </form>
  <?php
}

if($do == "files") {
	echo "<p align=\"center\"><u>File Manager</u></p>";
	if($usemanager == "Y") {
		$root = $sitepath;
		if(!isset($newpath)) {
			$filepath = $root;
		}
		if((!isset($action)) || ($action == "main")) {
			echo "<p>SimpleDir's File Manager has been enabled. Please note that (1) any files that you would like to edit through this file manager must be CHMODed to 766 or 666, (2) only non-image files will be displayed below, and (3) the numbers beside the filenames are its permissions.</p>";
			if($handle = opendir($filepath)) {
				// print title
				echo "<p>Directory: $filepath</p>";
				// set $file to the appropriate variable
				while(false !== ($file = readdir($handle))) {
					if(is_dir($file)) {
						$dirs[] = $file;
					}
					elseif(is_file($file)) {
						$files[] = $file;
					}
				}
				// close connection
				closedir($handle);
				// print directories
				if(is_array($dirs)) {
					sort($dirs);
					foreach($dirs as $dir) {
					}
				}
				// print files
				if(is_array($files)) {
					sort($files);
					foreach($files as $file) {
						if(is_file($file)) {
							// set extension to variable
							$fileparts = pathinfo($file);
							$ext = $fileparts['extension'];
							// get permissions
							$decperms = fileperms($file);
							$octalperms = sprintf("%o",$decperms);
							$perms = substr($octalperms,3);
							if( ($ext != "gif") && ($ext != "jpg") && ($ext != "png") && ($ext != "bmp") ) {
								echo "<a href=\"admin.php?do=files&action=edit&text=".$file."\"><img src=\"file.gif\" border=\"0\"> ".$file."</a> ".$perms."<br>";
							}
						}
					}
				}
			}
		}
		elseif(isset($action)) {
			if($action == "edit") {
				$thefile = $filepath.'/'.$text;
				$filename = basename($thefile);
				$dir = dirname($thefile);
				if((is_file($thefile)) && (is_readable($thefile)) && (is_writable($thefile))) { ?>
					<p>Currently editing: <b><?=$filename?></b> in <?=$dir?></p>
					<form action="admin.php?do=files&action=save&text=<?=$text?>" method="post">
					<p><textarea rows="20" cols="92" name="myfile"><?php
					$fileh = fopen($thefile, "r");
					$read = fread($fileh, filesize($thefile));
					$contents = htmlspecialchars($read);
					fclose($fileh);
					echo $contents; ?>
					</textarea></p>
					<p><input type="submit" name="save" value="Save"></p>
					</form>					
					<?php
				}
				else { ?>
					<p>File <b><?=$filename?></b> in <?=$dir?> is unable to be edited. This may be because:</p>
					<ul><li>The file does not exist.<li>The path is incorrect.<li>The file has not been CHMODed properly. It must be CHMODed to 666 or 766.</ul>
					<?php
				}
			}
			if($action == "save") {
				// set necessary variables
				$thefile = $filepath.'/'.$text;
				$filename = basename($thefile);
				$dir = dirname($thefile);
				// write to file, if writable
				if(is_writable($thefile)) {
					$myfile = stripslashes($_POST['myfile']);
					$fileh = fopen($thefile, "w");
					fwrite($fileh, $myfile);
					fclose($fileh);
					// print success message
					echo "<p>File <b>".$filename."</b> in ".$dir." has been saved.</p><p><a href=\"admin.php?do=files&action=edit&text=".$text."\">Edit this file again</a><br><a href=\"admin.php?do=files\">Return to the listing</a></p>";
				}
				else {
					echo "<p>File <b>".$filename."</b> in ".$dir." is not writable. Please make sure that it is CHMODed to 766 or 666.</p><p><a href=\"admin.php?do=files\">Return to the listing</a></p>";
				}
			}
		}
	}
	elseif($usemanager == "N") {
		echo "<p>SimpleDir's File Manager has been disabled. To enable it, <a href=\"admin.php?do=editcfg\">edit the options</a>.</p>";
	}
}

if($do == "listall") { ?>
	<p align="center"><u>List All Links</u></p>
	<p>To edit a link, either click on it's id below or <a href="admin.php?do=searchform">search</a> for the link you wish to edit if you have a large directory.</p>
	<?php		
	$select = mysql_query ("SELECT linkname, linkID FROM $tblinks ORDER BY linkID ASC");
	if(($num_rows = mysql_num_rows($select)) == 0){
		echo "<p>There are no links.</p>";
	} else {
		echo "<p>";
		do {
			if($row['linkname'] != ""){
				$linkID = $row['linkID'];
				$linkname = stripslashes($row['linkname']);
				admin_listlinks();
			} 
		}
		while($row = mysql_fetch_array($select));
		echo "</p>";
		admin_legend();
	}
}

if($do == "searchform") { ?>
	<p align="center"><u>Search Links</u></p>
	<p>Enter in as many of the fields below as you would like, click Search, and follow the instructions on the next page or you may view <a href="admin.php?do=listall">all links</a> available to edit.</p>
	<form action="admin.php?sql=searchlinks" method="post">
	<p><b>Link ID:</b><br><input type="text" name="linkID" size="25"></p>
	<p><b>Category:</b><br>
	<?
	$select = mysql_query ("SELECT catname, catID FROM $tbcats ORDER BY catname");
	if(($num_rows = mysql_num_rows($select)) == 0){
		echo "There are no categories.";
	} else {
		do {
			if($row['catname'] != ""){
				$catname = stripslashes($row['catname']); ?>
				<input type="radio" name="relCatID" value="<?=$row["catID"]?>"> <?=$catname?> 
			<?php }
		}
		while($row = mysql_fetch_array($select));
	}
	?></p>
	<p><b>Link Name:</b><br><input type="text" name="linkname" size="25"></p>
	<p><b>Link URL:</b><br><input type="text" name="linkurl" size="25"></p>
	<p><b>Link Status:</b><br><input type="radio" name="linkstatus" value="0"> Pending <input type="radio" name="linkstatus" value="1"> Approved</p>
	<p><b>Owner Name:</b><br><input type="text" name="ownername" size="25"></p>
	<p><b>Owner E-mail:</b><br><input type="text" name="owneremail" size="25"></p>
	<input type="Submit" value="Search">
	</form>
	<?php
}

if($do == "sendemail") {
	$to = $_POST['to'];
	if($to == "all") {
		$select = mysql_query('SELECT DISTINCT owneremail FROM '.$tblinks); 
		if(($num_rows = mysql_num_rows($select)) == 0){
			$echomsgtype = "0";
			$echomsg = "Error! There are no e-mail addresses in <b>$tblinks</b>.";
		} else {
			$echomsgtype = "1";
			do {
				if($row['owneremail'] != "") {
					$recipient = $row['owneremail'];
					$subject = $_POST['subject'];
					$mailheaders = "From: $adminemail\n";
					$mailheaders .= "Reply-To: $adminemail\n\n";
					$msg = $_POST['msgbody'];
					mail($recipient, $subject, $msg, $mailheaders);
				}
			}
			while($row = mysql_fetch_array($select));
		}
	}
	else {
		$query = mysql_query("SELECT owneremail FROM $tblinks WHERE linkID = '$to'");
		$link = mysql_fetch_array($query);
		$recipient = $link['owneremail'];
		$echomsgtype = "1";
		// start email code
		$subject = $_POST['subject'];
		$mailheaders = "From: $adminemail\n";
		$mailheaders .= "Reply-To: $adminemail\n";
		$msg = $_POST['msgbody'];
		mail($recipient, $subject, $msg, $mailheaders);
	};
	if($echomsgtype == "1") {
		$echomsg = "Success! The e-mails have been sent.";
	}
	echo "<p>$echomsg</p>";
}

if($do == "tplslist") { ?>
	<p align="center"><u>List All Templates</u></p>
	<p>Below is a list of all of the templates that you can edit.</p>
	<p><a href="admin.php?action=edittpls&id=headfoot">Header/Footer Templates</a><br>
	<a href="admin.php?action=edittpls&id=cats">Category Related Templates</a><br>
	<a href="admin.php?action=edittpls&id=links">Links Listing Related Templates</a><br>
	<a href="admin.php?action=edittpls&id=add">Add A Link Related Templates</a><br>
	<a href="admin.php?action=edittpls&id=modify">Modify A Link Related Templates</a></p>
	<?php
}

if($do == "uninstall") { ?>
	<p align="center"><u>Uninstall SimpleDir <?=$sdversion?></u></p>
	<p>If you are sure that you would like to erase all data entered by this script into your MySQL databases, click on the link below. Please note that this function <b>cannot</b> be reversed. It is a good idea to back this data up before clicking on the link below.</p>
	<p align=center><a href="admin.php?sql=dropall">Uninstall Completely</a></p>
	<p>However, if you would like to mass delete all links or categories, then click on the corresponding link below.</p>
	<p align=center><a href="admin.php?sql=droplinks">Mass Delete Links</a> | <a href="admin.php?sql=dropcats">Mass Delete Categories</a></p>
	<?php
}

/******************************************************************************/
/******************************************************************************/
/*                        ?SQL=**** SCREENS                                   */
/* They are in alphabetical order.																						*/
/******************************************************************************/
/******************************************************************************/

if($sql == "approvelink") {
	// set action and linkID to shorter variables
	$action = $_POST['action'];
	$linkID = $_POST['linkID'];
	if($action == "approve") {
		// set form values to variables
		$relCatID = $_POST['relCatID'];
		$linkname = addslashes($_POST['linkname']);
		$linkurl = $_POST['linkurl'];
		$linkdesc = addslashes($_POST['linkdesc']);
		$linkstatus = $_POST['linkstatus'];
		$ownername = addslashes($_POST['ownername']);
		$owneremail = $_POST['owneremail'];
		$linknotes = addslashes($_POST['linknotes']);
		// if email notification is turned on
		if($emailnotify == "Y") {
			// select category name
			$select = mysql_query("SELECT catname FROM $tbcats WHERE catID = '$relCatID'");
			$row = mysql_fetch_array($select);
			$catname = $row['catname'];
			// begin message to site owner
			$recipient = "$owneremail";
			$subject = "$sitename: You have been approved!";
			$mailheaders = "From: $adminemail\n";
			$mailheaders .= "Reply-To: $adminemail\n\n";
			$msg = "Hi $ownername!\n\nYou have been approved for $sitename with the following details:\n\nName: $ownername\nE-mail address: $owneremail\nLink ID: $linkID\nLink name: $linkname\nLink URL: $linkurl\nLink description: $linkdesc\nCategory: $catname\n\nThank you for submitting your site to $sitename!\n\n$adminname\n$siteurl/";
			mail($recipient, $subject, $msg, $mailheaders);
			// end message to site owner
			// update info in $tblinks
			$query = "UPDATE $tblinks SET relCatID = '$relCatID', linkname = '$linkname', linkurl = '$linkurl', linkdesc = '$linkdesc', linkstatus = '$linkstatus', ownername = '$ownername', owneremail = '$owneremail', linknotes = '$linknotes' WHERE linkID = '$linkID'";
			mysql_query($query) or die(mysql_error());
		}
		// if email notification is turned off
		elseif($emailnotify == "N") {
			// update info in $tblinks
			$query = "UPDATE $tblinks SET relCatID = '$relCatID', linkname = '$linkname', linkurl = '$linkurl', linkdesc = '$linkdesc', linkstatus = '$linkstatus', ownername = '$ownername', owneremail = '$owneremail', linknotes = '$linknotes' WHERE linkID = '$linkID'";
			mysql_query($query) or die(mysql_error());
		}
		// load success message
		echo "<p>Success! The link has been approved.<br><a href=\"admin.php?do=apprlink\">Approve more links</a></p>";
	}
	if($action == "delete") {
		$query = "DELETE from $tblinks WHERE linkID = '$linkID'";
		mysql_query($query);
		echo "<p>Success! The link has been deleted.<br><a href=\"admin.php?do=apprlink\">Approve more links</a></p>";
	}
}

if($sql == "dropall") {
	$query = "DROP TABLE $tbconfig, $tblinks, $tbcats";
	mysql_query($query);
	echo "<p>Success! All data has been erased from the MySQL database.</p>";
}

if($sql == "dropcats") {
	mysql_query("DROP TABLE $tbcats");
	mysql_query("CREATE TABLE $tbcats (catID int(6) NOT NULL auto_increment, catname text NOT NULL, KEY catID (catID))") or die(mysql_error());
	mysql_query("INSERT INTO $tbcats VALUES ('', 'Other')") or die(mysql_error());
	echo "<p>Success! All categories have been erased from the MySQL database.</p>";
}

if($sql == "droplinks") {
	mysql_query("DROP TABLE $tblinks");
	mysql_query("CREATE TABLE $tblinks (linkID int(6) NOT NULL auto_increment, relCatID int(6) NOT NULL, linkname text NOT NULL, linkurl text NOT NULL, linkstatus int(6) NOT NULL, linkdesc text NOT NULL, ownername text NOT NULL, owneremail text NOT NULL, linknotes text NOT NULL, KEY linkID (linkID))") or die(mysql_error());
	echo "<p>Success! All links have been erased from the MySQL database.</p>";
}


if($sql == "massappr") {
	$query = "UPDATE $tblinks SET linkstatus = '1' WHERE linkstatus = '0'";
	mysql_query($query) or die(mysql_error());
	echo "<p>Success! All pending links have been approved.</p>";
}

if($sql == "newcat") {
	// set to variable
	$catname = addslashes($_POST['catname']);
	$catDesc = addslashes($_POST['catDesc']);
	// query $tbcats
	mysql_query("INSERT INTO $tbcats VALUES ('', '$catname', '$catDesc')") or die(mysql_error());
	echo "<p>Success! The category has been added.</p>";
}

if($sql == "newlinkcp") {
	// set variables
	$relCatID = $_POST['relCatID'];
	$linkname = addslashes($_POST['linkname']);
	$linkurl = $_POST['linkurl'];
	$linkstatus = $_POST['linkstatus'];
	$linkdesc = addslashes($_POST['linkdesc']);
	$ownerename = addslashes($_POST['ownername']);
	$owneremail = $_POST['owneremail'];
	$linknotes = addslashes($_POST['linknotes']);
	// query $tblinks
	$query = "INSERT INTO $tblinks VALUES ('','$relCatID','$linkname', '$linkurl', '$linkstatus', '$linkdesc', '$ownername', '$owneremail', '$linknotes')";
	mysql_query($query);
	echo "<p>Success! The link has been added.</p>";
}

if($sql == "removecat") {
	$query = "DELETE from $tbcats WHERE catID = '{$_POST['catID']}'";
	mysql_query($query);
	echo "<p>Success! The category has been deleted.</p>";
}

if($sql == "removelink") {
	$query = "DELETE from $tblinks WHERE linkID = '{$_POST['linkID']}'";
	mysql_query($query);
	echo "<p>Success! The link has been deleted.</p>";
}

if($sql == "searchlinks") {
	// set form values as new variables
	$linkID = $_POST['linkID'];
	$relCatID = $_POST['relCatID'];
	$linkname = addslashes($_POST['linkname']);
	$linkurl = $_POST['linkurl'];
	$linkstatus = $_POST['linkstatus'];
	$ownername = addslashes($_POST['ownername']);
	$owneremail = $_POST['owneremail'];
	// set blank form values as wildcards
	if ($linkID == "") {$linkID = '%';}
	if ($relCatID == "") {$relCatID = '%';}
	if ($linkname == "") {$linkname = '%';}
	if ($linkurl == "") {$linkurl = '%';}
	if ($linkstatus == "") {$linkstatus = '%';}
	if ($ownername == "") {$ownername = '%';}
	if ($owneremail == "") {$owneremail = '%';}
	// select correct records
	$query = "SELECT * FROM $tblinks WHERE linkID LIKE '$linkID' AND relCatID LIKE '$relCatID' AND linkname LIKE '$linkname' AND linkurl LIKE '$linkurl' AND linkstatus LIKE '$linkstatus' AND ownername LIKE '$ownername' AND owneremail LIKE '$owneremail' ORDER BY linkID ASC";
	$result = mysql_query($query);
	echo "<p align=\"center\"><u>Search Results</u></p>";
	if ($row = mysql_fetch_array($result)) { ?>
		<p>To edit a link, either click on it's id below, start a new <a href="admin.php?do=searchform">search</a>, or <a href="admin.php?do=listall">view all links</a> available to edit.</p>
		<p><?php
		do { 
			// insert display results code here
			$linkID = $row['linkID'];
			$linkname = stripslashes($row['linkname']);
			admin_listlinks();
		} while($row = mysql_fetch_array($result));
		echo "</p>";
		admin_legend();
	} else {
		echo "<p>No records were found with those values. Please <a href=\"admin.php?do=searchform\">try again</a>.</p>";
	}
}

if($sql == "updatecat") {
	// set variables
	$catname = addslashes($_POST['catname']);
	$catDesc = addslashes($_POST['catDesc']);
	$catID = $_POST['catID'];
	// query $tbcats
	$query = "UPDATE $tbcats SET catname = '$catname', catDesc = '$catDesc' WHERE catID = '$catID'";
	mysql_query($query);
	echo "<p>Success! The category's data has been updated.</p>";
}

if($sql == "updatecfg")
{
  // set form values to variables
  $newsitename = addslashes($_POST['sitename']);
  $newsiteurl = $_POST['siteurl'];
  $newvisitoradd = $_POST['visitoradd'];
  $newpend = $_POST['pend'];
  $newallowdesc = $_POST['allowdesc'];
  $newnumperpage = $_POST['numperpage'];
  $newemailnotify = $_POST['emailnotify'];
  $newadminemail = $_POST['adminemail'];
  $newusemanager = $_POST['usemanager'];
  $newsitepath = $_POST['sitepath'];
  $newcatselect = $_POST['catselect'];
  // query table $tbconfig
  $query = "UPDATE $tbconfig SET sitename = '$newsitename', siteurl = '$newsiteurl', visitoradd = '$newvisitoradd', pend = '$newpend', allowdesc = '$newallowdesc', numperpage = '$newnumperpage', emailnotify = '$newemailnotify', adminemail = '$newadminemail', usemanager = '$newusemanager', sitepath = '$newsitepath', catselect = '$newcatselect' WHERE id = '1'";
  mysql_query($query);
  echo "<p>Success! The configuration options have been updated.</p>";
}

if($sql == "updatelink") {
	// set form values as new variables
	$linkID = $_POST['linkID'];
	$relCatID = $_POST['relCatID'];
	$linkname = addslashes($_POST['linkname']);
	$linkurl = $_POST['linkurl'];
	$linkdesc = addslashes($_POST['linkdesc']);
	$linkstatus = $_POST['linkstatus'];
	$ownername = addslashes($_POST['ownername']);
	$owneremail = $_POST['owneremail'];
	$linknotes = addslashes($_POST['linknotes']);
	// query $tblinks
	$query = "UPDATE $tblinks SET relCatID = '$relCatID', linkname = '$linkname', linkurl = '$linkurl', linkdesc = '$linkdesc', linkstatus = '$linkstatus', ownername = '$ownername', owneremail = '$owneremail', linknotes = '$linknotes' WHERE linkID = '$linkID'";
	mysql_query($query) or die(mysql_error());
	echo "<p>Success! The link's info has been updated.</p>";
}

if($sql == "updatelogin")
{
  // set form values to new variables
  $newadminname = addslashes($_POST['adminname']);
  $oldpass = $_POST['oldpass'];
  $newpass = $_POST['newpass'];
  $md5oldpass = md5($oldpass);
  $md5newpass = md5($newpass);
  // if oldpass and the password in $tbconfig don't match
  if($md5oldpass != $dirpass)
    $msg1 = "Invalid old password.<br>";
  // these if statements make changing a password more secure
  if($dirpass == $md5newpass)
  $msg2 = "New password is the same as the old password.<br>";
  if($oldpass == "")
  {
    if($msg1 != "")
      $msg1 = "";
    $msg3 = "You must enter your old password.<br>";
  }
  if($newpass == "")
    $msg4 = "You must enter a new password.<br>";
  elseif($md5oldpass == $dirpass)
  {
    $query = "UPDATE $tbconfig SET adminname = '$newadminname', dirpass = '$md5newpass' WHERE id = '1'";
    mysql_query($query);
    if($msg1 == "" && $msg2 == "" && $msg3 == "" && $msg4 == "")
      $msg5 = 'Success! Your login information has been updated. You must <a href="admin.php">re-login</a> with your new password.';
  }
  // outputs the error message(s) (if any) or the success message
  if($msg5 == "")
  {
    $msg0 = "<b>Error</b><br>";
    $msg6 = '</p><p>Please <a href="admin.php?do=editlogin">try again</a>.';
  }
  echo '<p>'.$msg0.$msg1.$msg2.$msg3.$msg4.$msg5.$msg6.'</p>';
}

/******************************************************************************/
/******************************************************************************/
/*                        ?ACTION=**** SCREENS                                */
/* They are in alphabetical order.																						*/
/******************************************************************************/
/******************************************************************************/

if($action == "apprlinks") {
	$query = mysql_query("SELECT * FROM $tblinks WHERE linkID = '$queryid'");
	if ($row = mysql_fetch_array($query)) {
		do { 
			$ownername = stripslashes($row['ownername']);
			$linkname = stripslashes($row['linkname']);
			$linkdesc = stripslashes($row['linkdesc']);
			$linknotes = stripslashes($row['linknotes']);
			?>
		
			<p align="center"><u>Approve A Link</u></p>
			<form method="post" action="admin.php?sql=approvelink">
			<input type="hidden" name="linkID" value="<?=$row["linkID"]?>">
			<input type="hidden" name="linkstatus" value="1">
			<p><b>Link ID:</b> <?=$row["linkID"]?></p>
			<p><b>Owner Name:</b><br><input type="text" name="ownername" size="30" value="<?=$ownername?>"></p>
			<p><b>Owner E-mail:</b><br><input type="text" name="owneremail" size="30" value="<?=$row["owneremail"]?>"> <a href="admin.php?action=email&id=<?=$row['linkID']?>">E-mail</a></p>
			<p><b>Link Name:</b><br><input type="text" name="linkname" size="30" value="<?=$linkname?>"></p>
			<p><b>Link URL:</b><br><input type="text" name="linkurl" size="30" value="<?=$row["linkurl"]?>"> <a href="<?=$row["linkurl"]?>" target=_blank>Visit</a></p>
			<p><b>Category ID<a href="#catIDs">*</a>:</b><br><input type="text" name="relCatID" size="30" value="<?=$row["relCatID"]?>"></p>
			<p><b>Link Description:</b><br><textarea name="linkdesc" rows="6" cols="40"><?=$linkdesc?></textarea></p>
			<p><b>Link Notes:</b><br><textarea name="linknotes" rows="6" cols="40"><?=$linknotes?></textarea></p>
			<p><input type="radio" name="action" value="approve" style="border: 0px;" checked> Approve <input type="radio" name="action" value="delete" style="border: 0px;"> Delete</p>

			<p><input type="submit" name="submit" value="Submit"></p>
			</form>

			<a name="catIDs"></a>
			<p><b>*Category IDs:</b><br>
			<?php
			$select = mysql_query ("SELECT catname, catID FROM $tbcats ORDER BY catID");
			if(($num_rows = mysql_num_rows($select)) == 0){
				echo "There are no categories.";
			} else {
				do {
					if($row['catname'] != ""){
						$catname = stripslashes($row['catname']); ?>
						<?=$row["catID"]?> - <?=$catname?><BR>
					<?php }
				}
				while($row = mysql_fetch_array($select));
			}
			echo "</p>";
		} while($row = mysql_fetch_array($query));
	}
	else {
		echo "<p>Please go back to <a href=\"admin.php?do=apprlink\">Approve A Link</a> and try again.</p>";
	}
}

if($action == "delcats") {
	$query = mysql_query("SELECT catname FROM $tbcats WHERE catID = '$queryid'");
	if ($row = mysql_fetch_array($query)) {
		do { 
			$catname = stripslashes($row['catname']);	?>

			<p align="center"><u>Delete A Category</u></p>

			<form method="post" action="admin.php?sql=removecat">
			<input type="hidden" name="catID" value="<?=$queryid?>">
			<p>Are you sure that you would like to delete category <b><?=$catname?></b>?</p>
			<p><input type="submit" name="submit" value="Yes"></p>
			</form>

			<?php
		} while($row = mysql_fetch_array($query));
	}
	else {
		echo "<p>Please go back to <a href=\"admin.php?delcat\">Delete A Category</a> and try again.</p>";
	}
}

if($action == "dellinks") {
	$query = mysql_query("SELECT * FROM $tblinks WHERE linkID = '$queryid'");
	if ($row = mysql_fetch_array($query)) {
		do { 
			$linkname = stripslashes($row['linkname']);
			?>

			<p align="center"><u>Delete A Link</u></p>

			<form method="post" action="admin.php?sql=removelink">
			<input type="hidden" name="linkID" value="<?=$row["linkID"]?>">
			<p>Are you sure that you would like to delete link <b><?=$linkname?></b>?</p>
			<p><input type="submit" name="submit" value="Yes"></p>
			</form>

			<?
		} while($row = mysql_fetch_array($query));
	}
	else {
		echo "<p>Please go back to <a href=\"admin.php?do=dellink\">Delete A Link</a> and try again.</p>";
	}
}

if($action == "editcats") {
	$query = mysql_query("SELECT * FROM $tbcats WHERE catID = '$queryid'");
	if ($cat = mysql_fetch_array($query)) {
		do { 
			$catname = stripslashes($cat['catname']);
			$catDesc = stripslashes($cat['catDesc']);
			$parentCatID = $cat['parentCatID'];	?>
		
			<p align="center"><u>Edit A Category</u></p>
			<form method="post" action="admin.php?sql=updatecat">
			<input type="hidden" name="catID" value="<?=$queryid?>">
			<p><b>Category Name:</b><BR><input type="text" name="catname" value="<?=$catname?>"></p>
			<p><b>Category Description:</b><br><textarea name="catDesc" rows="6" cols="40"><?=$catDesc?></textarea></p>
			<p><input type="submit" name="submit" value="Submit"></p>
			</form>
			<?php	
		} while($cat = mysql_fetch_array($query));
	}
	else {
		echo "<p>Please go back to <a href=\"admin.php?do=editcat\">Edit A Category</a> and try again.</p>";
	}
}

if($action == "editlinks") {
	$query = mysql_query("SELECT * FROM $tblinks WHERE linkID = '$queryid'");
	if ($row = mysql_fetch_array($query)) {
		do { 
			$ownername = stripslashes($row['ownername']);
			$linkname = stripslashes($row['linkname']);
			$linkdesc = stripslashes($row['linkdesc']);
			$linknotes = stripslashes($row['linknotes']);
			?>
		
			<p align="center"><u>Edit A Link</u></p>
			<form method="post" action="admin.php?sql=updatelink">
			<input type="hidden" name="linkID" value="<?=$row["linkID"]?>">
			<p><b>Link ID:</b> <?=$row["linkID"]?></p>
			<p><b>Link Status:</b> <i>0 = pending, 1 = approved</i><br><input type="text" name="linkstatus" size="30" value="<?=$row["linkstatus"]?>">
			<p><b>Owner Name:</b><br><input type="text" name="ownername" size="30" value="<?=$ownername?>"></p>
			<p><b>Owner E-mail:</b><br><input type="text" name="owneremail" size="30" value="<?=$row["owneremail"]?>"> <a href="admin.php?action=email&id=<?=$row['linkID']?>">E-mail</a></p>
			<p><b>Link Name:</b><br><input type="text" name="linkname" size="30" value="<?=$linkname?>"></p>
			<p><b>Link URL:</b><br><input type="text" name="linkurl" size="30" value="<?=$row["linkurl"]?>"> <a href="<?=$row["linkurl"]?>" target=_blank>Visit</a></p>
			<p><b>Category ID<a href="#catIDs">*</a>:</b><br><input type="text" name="relCatID" size="30" value="<?=$row["relCatID"]?>"></p>
			<p><b>Link Description:</b><br><textarea name="linkdesc" rows="6" cols="40"><?=$linkdesc?></textarea></p>
			<p><b>Link Notes:</b><br><textarea name="linknotes" rows="6" cols="40"><?=$linknotes?></textarea></p>

			<p><input type="submit" name="submit" value="Submit"></p>
			</form>

			<a name="catIDs"></a>
			<p><b>*Category IDs:</b><br>
			<?php
			$select = mysql_query ("SELECT catname, catID FROM $tbcats ORDER BY catID");
			if(($num_rows = mysql_num_rows($select)) == 0){
				echo "There are no categories.";
			} else {
				do {
					if($row['catname'] != ""){?>
					<?=$row["catID"]?> - <?=$row["catname"]?><BR>
					<?php }
				}
				while($row = mysql_fetch_array($select));
			}
			echo "</p>";
		} while($row = mysql_fetch_array($query));
	}
	else {
		echo "<p>Please go back to <a href=\"admin.php?do=editlink\">Edit A Link</a> and try again.</p>";
	}
}

if ($action == "edittpls") {
	if ($queryid == "") { ?>
		<p>Please go back to <a href="admin.php?do=tplslist">the templates list</a> and select a template to edit.</p>
		<?php
	}
	if ($queryid == "add")
	{ ?>
		<p align="center"><u>Add A Link Related Templates</u></p>
		<form action="admin.php?action=edittpls&id=save" method="post">
		<input type="hidden" name="tplid" value="add">
		
		<p><b>Top of the page:</b><br>
		The contents of this template will be directly above the Category list on the Add A Link form.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="add_top"><?php editTPLS("add_top.tpl"); ?></textarea></p>
		
		<p><b>Bottom of the page:</b><br>
		The contents of this template will be directly below the Category list on the Add A Link form.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="add_bottom"><?php editTPLS("add_bottom.tpl"); ?></textarea></p>
		
		<p><b>Add A Link Disabled:</b><br>
		This template will be displayed when a visitor accesses add.php while the option for visitors to add links has been disabled.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="add_disable"><?php editTPLS("add_disable.tpl"); ?></textarea></p>
		
		<p><b>New Link Success:</b><br>
		This template will be displayed after a visitor's link has been successfully added to the database.<br>
		<a href="docs_tplvars.html#success_link" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="link_success"><?php editTPLS("link_success.tpl"); ?></textarea></p>
		
		<p><input type="submit" name="save" value="Save"></p>
		</form>
		<?php
	}
	if ($queryid == "cats")
	{ ?>
		<p align="center"><u>Category Related Templates</u></p>
		<form action="admin.php?action=edittpls&id=save" method="post">
		<input type="hidden" name="tplid" value="cats">
		
		<p><b>Top of the page:</b><br>
		The contents of this template will be above the category listing.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="10" cols="92" name="cats_header"><?php editTPLS("cats_header.tpl"); ?></textarea></p>
		
		<p><b>Category listings:</b><br>
		The templates below are displayed on listing.php, dependent on how many links are in a category.<br>
		<a href="docs_tplvars.html#cats" target="_blank">Variables you can use</a></p>
		
		<p><i>One link:</i><br>
		<textarea rows="3" cols="92" name="cats_1link"><?php editTPLS("cats_1link.tpl"); ?></textarea></p>
		
		<p><i>Multiple links:</i><br>
		<textarea rows="3" cols="92" name="cats_multlinks"><?php editTPLS("cats_multlinks.tpl"); ?></textarea></p>
		
		<p><b>Bottom of the page:</b><br>
		The contents of this template will be below the category listing.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="10" cols="92" name="cats_footer"><?php editTPLS("cats_footer.tpl"); ?></textarea></p>
		
		<p><input type="submit" name="save" value="Save"></p>
		</form>
		<?php
	}
	if ($queryid == "headfoot") { ?>
		<p align="center"><u>Header/Footer Templates</u></p>
		<form action="admin.php?action=edittpls&id=save" method="post">
		<input type="hidden" name="tplid" value="headfoot">
		
		<p><b>Header template:</b><br>
		This template is intended to contain code that will go on the top of every page. It can be included by putting {include file='header.tpl'} in any of the other templates.<br>
		<a href="docs_tplvars.html#headfoot" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="header"><?php editTPLS("header.tpl"); ?></textarea></p>
		
		<p><b>Footer template:</b><br>
		This template is intended to contain code that will go on the bottom of every page. It can be included by putting {include file='footer.tpl'} in any of the other templates.<br>
		<a href="docs_tplvars.html#headfoot" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="footer"><?php editTPLS("footer.tpl"); ?></textarea></p>
		
		<p><input type="submit" name="save" value="Save"></p>
		</form>					
		<?php
	}
	if ($queryid == "links")
	{ ?>
		<p align="center"><u>Links Listing Related Templates</u></p>
		<form action="admin.php?action=edittpls&id=save" method="post">
		<input type="hidden" name="tplid" value="links">
		
		<p><b>Top of the page:</b><br>
		The contents of this template will be directly above the links listing.<br>
		<a href="docs_tplvars.html#links" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="10" cols="92" name="links_header"><?php editTPLS("links_header.tpl"); ?></textarea></p>
		
		<p><b>Link listings:</b><br>
		The templates below are displayed on listing.php, dependent on whether or not a link contains a description (or if descriptions are disabled).<br>
		<a href="docs_topvars.html#links" target="_blank">Variables you can use</a></p>
		
		<p><i>No description:</i><br>
		<textarea rows="3" cols="92" name="links_nodesc"><?php editTPLS("links_nodesc.tpl"); ?></textarea></p>
		
		<p><i>Description:</i><br>
		<textarea rows="3" cols="92" name="links_desc"><?php editTPLS("links_desc.tpl"); ?></textarea></p>
		
		<p><b>Bottom of the page:</b><br>
		The contents of this template will be directly below the links listing.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="10" cols="92" name="links_footer"><?php editTPLS("links_footer.tpl"); ?></textarea></p>
		
		<p><input type="submit" name="save" value="Save"></p>
		</form>
		<?php
	}
	if ($queryid == "modify")
	{ ?>
		<p align="center"><u>Modify A Link Related Templates</u></p>
		<form action="admin.php?action=edittpls&id=save" method="post">
		<input type="hidden" name="tplid" value="modify">
		
		<p><b>Top of the page:</b><br>
		The contents of this template will be directly above the Old Category list on the Modify A Link form.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="modifyA"><?php editTPLS("modifyA.tpl"); ?></textarea></p>
		
		<p><b>Middle of the page:</b><br>
		The contents of this template will be between the Old Category list and the New Category list on the Modify A Link form.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="modifyB"><?php editTPLS("modifyB.tpl"); ?></textarea></p>
		
		<p><b>Bottom of the page:</b><br>
		The contents of this template will be directly below the New Category list on the Modify A Link form.<br>
		<a href="docs_tplvars.html#general" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="modifyC"><?php editTPLS("modifyC.tpl"); ?></textarea></p>
		
		<p><b>Success message:</b><br>
		This template will be displayed after a visitor's updated information has been successfully e-mailed to you.<br>
		<a href="docs_tplvars.html#success_modify" target="_blank">Variables you can use</a><br><br>
		
		<textarea rows="20" cols="92" name="modify_success"><?php editTPLS("modify_success.tpl"); ?></textarea></p>
		
		<p><input type="submit" name="save" value="Save"></p>
		</form>
		<?php
	}
	if ($queryid == "save")
	{		
		// Set $filename, save files
		if ($_POST['tplid'] == "add")
		{
			saveTPLS("add_top.tpl",$_POST['add_top'],'Y','N');
			saveTPLS("add_bottom.tpl",$_POST['add_bottom'],'N','N');
			saveTPLS("add_disable.tpl",$_POST['add_disable'],'N','N');
			saveTPLS("link_success.tpl",$_POST['link_success'],'N','Y');
		}
		if ($_POST['tplid'] == "cats")
		{
			saveTPLS("cats_header.tpl",$_POST['cats_header'],'Y','N');
			saveTPLS("cats_1link.tpl",$_POST['cats_1link'],'N','N');
			saveTPLS("cats_multlinks.tpl",$_POST['cats_multlinks'],'N','N');
			saveTPLS("cats_footer.tpl",$_POST['cats_footer'],'N','Y');
		}
		if ($_POST['tplid'] == "links")
		{
			saveTPLS("links_header.tpl",$_POST['links_header'],'Y','N');
			saveTPLS("links_nodesc.tpl",$_POST['links_nodesc'],'N','N');
			saveTPLS("links_desc.tpl",$_POST['links_desc'],'N','N');
			saveTPLS("links_footer.tpl",$_POST['links_footer'],'N','Y');
		}
		if ($_POST['tplid'] == "headfoot")
		{
			saveTPLS("header.tpl",$_POST['header'],'Y','N');
			saveTPLS("footer.tpl",$_POST['footer'],'N','Y');
		}
		if ($_POST['tplid'] == "modify")
		{
			saveTPLS("modifyA.tpl",$_POST['modifyA'],'Y','N');
			saveTPLS("modifyB.tpl",$_POST['modifyB'],'N','N');
			saveTPLS("modifyC.tpl",$_POST['modifyC'],'N','N');
			saveTPLS("modify_success.tpl",$_POST['modify_success'],'N','Y');
		}
	}
}

if($action == "email") { ?>
	<p align="center"><u>Send An E-mail</u></p>

	<p>To send an e-mail to all link owners in <?=$sitename?>, write only <i>all</i> in the Recipient field. If you would like to send an e-mail to only one link owner, then put their link ID in the Recipient field.</p>

	<p>Note: If there are a lot of e-mail addresses stored in table <b><?=$tblinks?></b>, the next page may take a little while to load. Be patient and do not close this browser window until a Success message appears.</p>

	<form method="post" action="admin.php?do=sendemail">
	<p><b>Recipient:</b><br><input type="text" name="to" size="30" value="<?=$queryid?>"></p>
	<p><b>Subject:</b><br><input type="text" name="subject" size="30" value="<?=$sitename?>: Code Alert"></p>
	<p><b>Message body:</b><br><textarea name="msgbody" rows="12" cols="75">Hi,

This is just a short note to request that you place a code on your site, linking back to <?=$sitename?>. As it is a requirement to do so, I will not add your site to <?=$sitename?> until you place a link back somewhere on your site.

<?=$adminname?>

<?=$siteurl?>/</textarea></p>
	<p><input type="submit" name="submit" value="E-mail"></p>
	</form>
	<?php
}

require("footer.inc.php");		// Include footer

?>