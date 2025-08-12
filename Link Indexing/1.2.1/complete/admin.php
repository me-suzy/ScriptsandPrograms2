<?php
require("includes/global.php");

// check for actions
if ($_POST["username"] <> ""){
	$errormsg = $usr->signin($_POST["username"], $_POST["password"], $_POST["from"]);
	$usr->Auth(0);
} elseif ($_GET["do"] == "signout"){
	$usr->signout();
	
	if ($_GET["from"] <> ""){
		Header("Location: " . $_GET["from"]); die();
	} else {
		$usr->Auth(0);
	}
	
} elseif ($_POST["do"] == "updateconfig"){
	$errormsg = updateconfig();
} elseif ($_POST["do"] == "updatephrases"){
	$errormsg = updatephrases();
} elseif ($_POST["do"] == "addphrase"){
	$errormsg = addphrase($_POST["phrase_name"], $_POST["phrase_value"]);
} elseif ($_POST["do"] == "multiline"){
	$errormsg = multilinephrase($_POST["phrase"]);
} elseif ($_POST["do"] == "exportlanguage"){
	$errormsg = export_language($_POST["title"]);
} elseif ($_POST["do"] == "importlanguage"){
	$errormsg = import_language($_FILES["xmlfile"]);
} elseif ($_POST["do"] == "flushqueue"){
	$errormsg = flushwebsites();
} elseif ($_POST["do"] == "flushtopics"){
	$errormsg = flushtopics();
} elseif ($_POST["do"] == "clearhistory"){
	$errormsg = clearhistory($_POST["days"]);
} elseif ($_POST["do"] == "updateprofile"){
	$errormsg = $usr->profile();
}

// work out what page it is
if ($_GET["page"] == "config"){
	$pagesect = 1;
	$usr->Auth(2);
} elseif ($_GET["page"] == "phrases"){
	$pagesect = 2;
	$usr->Auth(2);
} elseif ($_GET["page"] == "maintenance"){
	$pagesect = 3;
	$usr->Auth(2);
} elseif ($_GET["page"] == "myprofile"){
	$pagesect = 4;
	
	$sql = "SELECT * FROM " . $dbprefix . "users WHERE userid = " . dbSecure(intval($_SESSION["userid"]));
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ die("Unable to locate your account"); }
}

// setup the variables
$bread = '<a href="' . $config["topicpage"] . '">Top</a> ' . $config["breadcrumb"] . ' <a href="admin.php">Admin Panel</a>';
$pagetitle = $config["sitename"] . " Admin";

// page header
include("includes/page_header.php");
?>

<?php if ($errormsg <> ""){ ?>
<?=$errormsg?>
<hr />
<?php } ?>

<?php if ($usr->Access < 1){ ?>
<form action="admin.php" method="post" class="login">
	Username:<br />
	<input type="text" size="30" maxlength="50" id="username" name="username" value="<?=$_POST["username"]?>" /><br /><br />
	
	Password:<br />
	<input type="password" size="30" maxlength="50" id="password" name="password" /><br /><br />
	
	<input type="hidden" id="from" name="from" value="<?=$_GET["from"]?>" />
	<input type="submit" value="Login!" />
</form>

<?php } else { ?>
<table width="100%" cellpadding="5" cellspacing="0" border="0">
<tr valign="top">
<td class="adminmenu" style="width: 150px;">

	<strong>Navigation</strong><br />
	<a href="admin.php">Admin Overview</a><br />
	<a href="admin.php?page=myprofile">My Profile</a><br />
	<a href="admin.php?do=signout">Sign Out</a>
	
	<?php if ($usr->Access > 1){ ?>
	<br /><br />
	<strong>Admin Pages</strong><br />
	<a href="admin.php?page=config">Edit Config</a><br />
	<a href="admin.php?page=phrases">Edit Phrases</a><br />
	<a href="admin.php?page=maintenance">Maintenance</a><br />
	<a href="skineditor.php" target="_blank" onClick="skineditor(); return false;">Launch skin editor</a>
	<?php } ?>

</td>
<?php if ($config["stretchedmenu"] == "true"){ ?>
</tr><tr>
<?php } ?>
<td>
<?php if ($pagesect == 1){ ?>

<form action="admin.php?page=config" method="post">
<table width="500" cellpadding="0" cellspacing="3" border="0">
	<tr style="background: #EEEEEE;">
		<th colspan="2">Edit Config</th>
	</tr>
	<?php $sql = "SELECT * FROM " . $dbprefix . "config WHERE config_help <> '' ORDER BY config_name ASC"; $cng = $db->execute($sql); do { ?>
	<tr>
		<td>
			<strong><?=$cng->fields["config_name"]?></strong><br />
			<span class="smalltext"><?=$cng->fields["config_help"]?></span>
		</td>
		<td>
			<input type="text" size="30" maxlength="255" id="<?=$cng->fields["config_name"]?>" name="<?=$cng->fields["config_name"]?>" value="<?=htmlspecialchars($cng->fields["config_value"])?>" />
		</td>
	</tr>
	<?php } while ($cng->loop()); ?>
	<tr>
		<th colspan="2">
			<input type="hidden" id="do" name="do" value="updateconfig" />
			<input type="submit" value="Update The Config!" style="font-size: 125%;" />
		</th>
	</tr>
</table>
</form>

<?php } elseif ($pagesect == 2){ ?>

<form action="admin.php?page=phrases" method="post">
<table width="500" cellpadding="0" cellspacing="3" border="0">
	<tr style="background: #EEEEEE;">
		<th colspan="2">Edit Phrases</th>
	</tr>
	<?php $sql = "SELECT * FROM " . $dbprefix . "phrases ORDER BY phrase_name ASC"; $phr = $db->execute($sql); if ($phr->rows > 0){ do { ?>
	<tr>
		<td>
			<strong><?=$phr->fields["phrase_name"]?></strong>
		</td>
		<td>
			<?php if (strstr($phr->fields["phrase_value"], "\n")){ ?>
			<textarea cols="40" rows="4" id="<?=$phr->fields["phraseid"]?>" name="<?=$phr->fields["phraseid"]?>"><?=htmlspecialchars($phr->fields["phrase_value"])?></textarea>
			<?php } else { ?>
			<input type="text" size="50" maxlength="255" id="<?=$phr->fields["phraseid"]?>" name="<?=$phr->fields["phraseid"]?>" value="<?=htmlspecialchars($phr->fields["phrase_value"])?>" />
			<?php } ?>
		</td>
	</tr>
	<?php } while ($phr->loop()); } ?>
	<tr>
		<th colspan="2">
			<input type="hidden" id="do" name="do" value="updatephrases" />
			<input type="submit" value="Update Phrases!" style="font-size: 125%;" />
		</th>
	</tr>
</table>

</form>

<hr />

<form action="admin.php?page=phrases" method="post">
<strong>Add New Phrase</strong><br /><br />

Phrase Name:<br />
<input type="text" size="30" maxlength="255" id="phrase_name" name="phrase_name" /><br /><br />

Phrase Value:<br />
<textarea cols="30" id="phrase_value" name="phrase_value"></textarea><br /><br />

<input type="hidden" id="do" name="do" value="addphrase" />
<input type="submit" value="Add Phrase!" />
</form>

<hr />

<form action="admin.php?page=phrases" method="post">
<strong>Make Phrase Multi-line</strong><br />
Whether a phrase gets a multi-line box or not is determined by whether it has more than one line or not. But obviously if it doesn't, is just had a text input box so you can't add a new line. That is what this tool is for. To undo this simply remove any line breaks.<br /><br />

<select id="phrase" name="phrase">
	<?php if ($phr->rows > 0){ $phr->start(); do { ?>
	<option><?=$phr->fields["phrase_name"]?></option>
	<?php } while ($phr->loop()); } ?>
</select>
<input type="hidden" id="do" name="do" value="multiline" />
<input type="submit" value="Make Multi-line!" />
</form>

<hr />

<form action="admin.php?page=phrases" method="post">
<strong>Export Phrases</strong><br />
Export the phrases as an XML language pack. Give it a name like English or Klingon and click export. This is useful if you have translated a new language and want to send it to us (which is much appreciated :).<br /><br />

<input type="text" size="20" maxlength="50" id="title" name="title" />
<input type="hidden" id="do" name="do" value="exportlanguage" />
<input type="submit" value="Export!" />
</form>

<hr />

<form action="admin.php?page=phrases" method="post" enctype="multipart/form-data">
<strong>Import Phrases</strong><br />
Caution: this will overwrite the existing phrases!<br /><br />

<input type="file" size="20" id="xmlfile" name="xmlfile" />
<input type="hidden" id="do" name="do" value="importlanguage" />
<input type="submit" value="Import!" />
</form>

<?php } elseif ($pagesect == 3){ ?>

<strong>Maintenance</strong><br />
This page contains tasks you may want to run every now and then to keep things running smoothly.<br /><br />

<form action="admin.php?page=maintenance" method="post">
<strong>Flush website queue</strong><br />
This will delete ALL user suggested websites that have not yet been moderated from the queue.<br />
<input type="hidden" id="do" name="do" value="flushqueue" />
<input type="submit" value="Flush Queue!" />
</form><br />

<form action="admin.php?page=maintenance" method="post">
<strong>Flush topics queue</strong><br />
This will delete ALL user suggested topics that have not yet been moderated from the queue.<br />
<input type="hidden" id="do" name="do" value="flushtopics" />
<input type="submit" value="Flush Topics!" />
</form><br />

<form action="admin.php?page=maintenance" method="post">
<strong>Clear template history</strong><br />
This will delete all the archived copies of templates that were created before a set amount of days (specified in the box). You don't need to do this as they take up very little space but you may want to. Note: 1 day will cover the past 24 hours, not just today. You can clear them all by setting it to 0.<br />
<input type="text" size="4" maxlength="4" id="days" name="days" value="90" />
<input type="hidden" id="do" name="do" value="clearhistory" />
<input type="submit" value="Clear History!" />
</form>

<?php } elseif ($pagesect == 4){ ?>
<strong>My Profile</strong><br />
Here you can update your user account details. In order to make any changes you must enter your existing password. If you don't want to change your password, leave the other boxes blank.<br /><br />

<form action="admin.php?page=myprofile" method="post">
<table cellpadding="0" cellspacing="3" border="0">
	<tr>
		<td>Current Password:</td>
		<td><input type="password" size="30" maxlength="50" id="pass1" name="pass1" /></td>
	</tr>
	<tr>
		<td>New Password:</td>
		<td><input type="password" size="30" maxlength="50" id="pass2" name="pass2" /></td>
	</tr>
	<tr>
		<td>Confirm New:</td>
		<td><input type="password" size="30" maxlength="50" id="pass3" name="pass3" /></td>
	</tr>
	<tr>
		<td>Username:</td>
		<td><input type="text" size="30" maxlength="50" id="usern" name="usern" value="<?=$rec->fields["username"]?>" /></td>
	</tr>
	<tr>
		<td>E-Mail:</td>
		<td><input type="text" size="30" maxlength="50" id="email" name="email" value="<?=$rec->fields["email"]?>" /></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<input type="hidden" id="do" name="do" value="updateprofile" />
			<input type="submit" value="Update Profile!" />
		</td>
	</tr>
</table>
</form>

<?php } else { // else default page ?>
Hi <?=$_SESSION["username"]?>! [ <a href="admin.php?do=signout">Sign Out</a> ]

<?php
$sql = "SELECT DISTINCT topicid FROM " . $dbprefix . "queue ORDER BY postdate ASC";
$ned = $db->execute($sql);
if ($ned->rows > 0){
?>
<hr /><strong>Topics in need of moderation</strong>
<ul>
	<?php do { ?>
	<li><?=fetchtopic($ned->fields["topicid"])?></li>
	<?php } while ($ned->loop()); ?>
</ul>
<?php } // end categories check ?>

<?php
$sql = "SELECT DISTINCT topicid FROM " . $dbprefix . "newtopics ORDER BY postdate ASC";
$ned = $db->execute($sql);
if ($ned->rows > 0){
?>
<hr /><strong>Topics with sub-topic suggestions</strong>
<ul>
	<?php do { ?>
	<li><?=fetchtopic($ned->fields["topicid"])?></li>
	<?php } while ($ned->loop()); ?>
</ul>
<?php } // end topics moderation check ?>

<hr />

Version information:<br />
<i><?=versioninfo()?></i>

<?php } // end page check ?>
</td>
</tr>
</table>

<?php } ?>

<?php
include("includes/page_footer.php");
?>