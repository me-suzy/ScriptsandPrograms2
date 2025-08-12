<?php
require("includes/global.php");

// check user level
if ($usr->Access < 1){ die("You do not have access"); }
$tint = intval($_GET["topicid"]);

// check for actions
if ($_POST["do"] == "addtopic"){
	$errormsg = addtopic($_GET["topicid"], $_POST["title"], $_POST["visible"], $_POST["keywords"], $_POST["description"], $_POST["rules"]);
} elseif ($_POST["do"] == "edittopic"){
	$errormsg = edittopic($_GET["topicid"], $_POST["title"], $_POST["parent"], $_POST["keywords"], $_POST["description"], $_POST["rules"]);
} elseif ($_POST["do"] == "deletetopic"){
	$errormsg = deletetopic($_GET["topicid"]);
} elseif ($_POST["do"] == "addlink"){
	$errormsg = addlink($_GET["topicid"], $_POST["title"], $_POST["description"], $_POST["url"], $_POST["priority"]);
} elseif ($_POST["do"] == "editlink"){
	$errormsg = editlink($_GET["topicid"], $_POST["title"], $_POST["description"], $_POST["url"], $_POST["priority"], $_POST["topicid"]);
} elseif ($_POST["do"] == "editqueue"){
	$errormsg = editqueue($_GET["topicid"], $_POST["title"], $_POST["description"], $_POST["url"], $_POST["email"], $_POST["topicid"]);
} elseif ($_POST["do"] == "editnewtopic"){
	$errormsg = editnewtopic($_GET["topicid"], $_POST["title"], $_POST["email"], $_POST["topicid"], $_POST["description"]);
}

// work out the page
if ($_GET["page"] == "visibility"){
	$errormsg = visible($_GET["topicid"]);
} elseif ($_GET["page"] == "addtopic"){
	$pagesect = 1;
} elseif ($_GET["page"] == "edittopic"){
	$sql = "SELECT * FROM " . $dbprefix . "topics WHERE topicid = " . dbSecure($tint);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ die("Topic could not be found"); }
	
	$pagesect = 2;
} elseif ($_GET["page"] == "deletetopic"){
	$pagesect = 3;
} elseif ($_GET["page"] == "addlink"){
	$pagesect = 4;
} elseif ($_GET["page"] == "managelinks"){
	$pagesect = 5;
	
	if ($_GET["delete"] <> ""){
		$errormsg = deletelink($_GET["delete"]);
	}
	
	$sql = "SELECT * FROM " . $dbprefix . "links WHERE topicid = " . dbSecure($tint) . " ORDER BY priority DESC, website ASC";
	$rec = $db->execute($sql);
	
	$core = "moderate.php?page=managelinks&topicid=" . $tint . "&";
} elseif ($_GET["page"] == "editlink"){
	$sql = "SELECT * FROM " . $dbprefix . "links WHERE linkid = " . dbSecure($tint);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ die("Link could not be found"); }
	
	$pagesect = 6;
	
} elseif ($_GET["page"] == "editqueue"){
	$sql = "SELECT * FROM " . $dbprefix . "queue WHERE queueid = " . dbSecure($tint);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ die("Link could not be found"); }
	
	$pagesect = 7;
} elseif ($_GET["page"] == "editnewtopic"){
	$sql = "SELECT * FROM " . $dbprefix . "newtopics WHERE newtopicid = " . dbSecure($tint);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ die("Topic could not be found"); }
	
	$pagesect = 8;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?=$config["sitename"]?> Moderation</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" type="text/css" href="shared/popup.css" />
<script language="JavaScript" type="text/javascript" src="shared/functions.js"></script>
</head>
<body>
<div class="container">
	<div class="header">
		<div style="float: right;"><a href="javascript:window.close();">X</a></div>
		Moderator Panel
	</div>
	
	<div class="main">
		<?php if ($errormsg <> ""){ ?>
		<div><?=$errormsg?></div>
		<?php } ?>
		
		<?php if ($pagesect == 1){ ?>
		<form action="moderate.php?page=addtopic&topicid=<?=$_GET["topicid"]?>" method="post">
		<strong>Add Topic [ Title ]</strong>
		
		<table cellpadding="0" cellspacing="3" border="0">
		<tr>
			<td>Title:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" /></td>
		</tr>
		<tr>
			<td>Keywords:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="keywords" name="keywords" /></td>
		</tr>
		<tr>
			<td>Visibility:</td>
			<td style="text-align: right;">
				<select id="visible" name="visible">
					<option value="1">Visible</option>
					<option value="0">Hidden</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2">Description:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="description" name="description" rows="5" cols="40"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">Rules:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="rules" name="rules" rows="5" cols="40"></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="hidden" id="do" name="do" value="addtopic" />
				<input type="submit" value="Create Topic!" />
			</td>
		</tr>
		</table>
		</form>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } elseif ($pagesect == 2){ ?>
		<form action="moderate.php?page=edittopic&topicid=<?=$_GET["topicid"]?>" method="post">
		<strong>Edit Topic</strong><br /><br />
		
		<table cellpadding="0" cellspacing="3" border="0">
		<tr>
			<td>Title:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" value="<?=$rec->fields["title"]?>"</td>
		</tr>
		<tr>
			<td>Keywords:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="keywords" name="keywords" value="<?=$rec->fields["keywords"]?>" /></td>
		</tr>
		<tr>
			<td colspan="2">Parent Topic:</td>
		<tr>
		<?php if ($config["topicdropdown"] == "true"){ ?>
			<td colspan="2">
				<select id="parent" name="parent">
					<option value="0">No parent</option>
					<?=fetchoptions($rec->fields["parent"], $rec->fields["topicid"])?>
				</select>
			</td>
		<?php } else { ?>
			<td colspan="2">
				<input type="text" size="11" maxlength="11" id="parent" name="parent" value="<?=$rec->fields["parent"]?>" />
			</td>
		<?php } ?>
		</tr>
		<tr>
			<td colspan="2">Description:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="description" name="description" rows="5" cols="40"><?=htmlspecialchars($rec->fields["description"])?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2">Rules:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="rules" name="rules" rows="5" cols="40"><?=htmlspecialchars($rec->fields["rules"])?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="hidden" id="do" name="do" value="edittopic" />
				<input type="submit" value="Edit Topic!" />
			</td>
		</tr>
		</table>
		</form>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } elseif ($pagesect == 3 && $errormsg == ""){ ?>
		<form action="moderate.php?page=deletetopic&topicid=<?=$_GET["topicid"]?>" method="post">
		Are you sure you want to delete this topic? Deleting a topic deletes all the links on it, all the sub-topics and all the links on those topics as well. That could be a lot links!<br /><br />
		
		<input type="hidden" id="do" name="do" value="deletetopic" />
		<input type="submit" value="Yes, delete it!" />
		</form>
		
		<?php } elseif ($pagesect == 4){ ?>
		<form action="moderate.php?page=addlink&topicid=<?=$_GET["topicid"]?>" method="post">
		<table cellpadding="0" cellspacing="3" border="0">
		<tr>
			<td>Title:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" /></td>
		</tr>
		<tr>
			<td>URL:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="url" name="url" value="http://" /></td>
		<tr>
			<td>Order:</td>
			<td style="text-align: right;"><input type="text" size="11" maxlength="11" id="priority" name="priority" value="0" /></td>
		</tr>
		<tr>
			<td colspan="2">Description:</td>
		</tr>
		<tr>
			<td colspan="2"><textarea cols="40" rows="5" id="description" name="description"></textarea></td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="hidden" id="do" name="do" value="addlink" />
				<input type="submit" value="Add Link!" />
			</td>
		</tr>
		</table>
		</form>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } elseif ($pagesect == 5){ ?>
		<table width="100%" cellpadding="0" cellspacing="3" border="0">
			<?php if ($rec->rows > 0){ do { ?>
			<tr>
				<td><?=$rec->fields["website"]?></td>
				<td style="text-align: right;"><a href="moderate.php?page=editlink&topicid=<?=$rec->fields["linkid"]?>" target="_blank" onClick="openwin('editlink', '<?=$root?>', '<?=$rec->fields["linkid"]?>'); return false;">Edit</a></td>
				<td style="text-align: right;"><a href="<?=$core?>delete=<?=$rec->fields["linkid"]?>">Delete</a></td>
			</tr>
			<?php } while ($rec->loop()); } ?>
		</table>
		
		<?php } elseif ($pagesect == 6){ ?>
		<form action="moderate.php?page=editlink&topicid=<?=$_GET["topicid"]?>" method="post">
		<table cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td>Title:</td>
				<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" value="<?=$rec->fields["website"]?>" /></td>
			</tr>
			<tr>
				<td>URL:</td>
				<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="url" name="url" value="<?=$rec->fields["url"]?>" /></td>
			</tr>
			<tr>
				<td>Order:</td>
				<td style="text-align: right;"><input type="text" size="11" maxlength="11" id="priority" name="priority" value="<?=$rec->fields["priority"]?>" /></td>
			</tr>
			<tr>
				<td colspan="2">Topic:</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if ($config["topicdropdown"] == "true"){ ?>
					<select id="topicid" name="topicid">
						<?=fetchoptions($rec->fields["topicid"], 0)?>
					</select>
					<?php } else { ?>
					<input type="text" size="11" maxlength="11" id="topicid" name="topicid" value="<?=$rec->fields["topicid"]?>" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">Description:</td>
			</tr>
			<tr>
				<td colspan="2"><textarea cols="40" rows="4" id="description" name="description"><?=htmlspecialchars($rec->fields["description"])?></textarea></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;"><input type="submit" value="Submit Link Changes!" /></td>
			</tr>
		</table>
		
		<input type="hidden" id="do" name="do" value="editlink" />
		</form>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } elseif ($pagesect == 7){ ?>
		<form action="moderate.php?page=editqueue&topicid=<?=$_GET["topicid"]?>" method="post">
		<table cellpadding="0" cellspacing="3" border="0">
			<tr>
				<td>Title:</td>
				<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" value="<?=$rec->fields["website"]?>" /></td>
			</tr>
			<tr>
				<td>URL:</td>
				<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="url" name="url" value="<?=$rec->fields["url"]?>" /></td>
			</tr>
			<tr>
				<td>E-Mail:</td>
				<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="email" name="email" value="<?=$rec->fields["email"]?>" /></td>
			</tr>
			<tr>
				<td>IP Address:</td>
				<td style="text-align: right;"><strong><?=$rec->fields["ip"]?></strong></td>
			</tr>
			<tr>
				<td colspan="2">Topic:</td>
			</tr>
			<tr>
				<td colspan="2">
					<?php if ($config["topicdropdown"] == "true"){ ?>
					<select id="topicid" name="topicid">
						<?=fetchoptions($rec->fields["topicid"], 0)?>
					</select>
					<?php } else { ?>
					<input type="text" size="11" maxlength="11" id="topicid" name="topicid" value="<?=$rec->fields["topicid"]?>" />
					<?php } ?>
				</td>
			</tr>
			<tr>
				<td colspan="2">Description:</td>
			</tr>
			<tr>
				<td colspan="2"><textarea cols="40" rows="5" id="description" name="description"><?=htmlspecialchars($rec->fields["description"])?></textarea></td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center;">
					<input type="hidden" id="do" name="do" value="editqueue" />
					<input type="submit" value="Submit Changes!" />
				</td>
			</tr>
		</table>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } elseif ($pagesect == 8){ ?>
		<form action="moderate.php?page=editnewtopic&topicid=<?=$_GET["topicid"]?>" method="post">
		<table cellpadding="0" cellspacing="3" border="0">
		<tr>
			<td>Title:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="title" name="title" value="<?=$rec->fields["title"]?>"</td>
		</tr>
		<tr>
			<td>E-Mail:</td>
			<td style="text-align: right;"><input type="text" size="30" maxlength="255" id="email" name="email" value="<?=$rec->fields["email"]?>"</td>
		</tr>
		<tr>
			<td>IP Address:</td>
			<td style="text-align: right;"><strong><?=$rec->fields["ip"]?></strong></td>
		</tr>
		<tr>
			<td colspan="2">Parent Topic:</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php if ($config["topicdropdown"] == "true"){ ?>
				<select id="topicid" name="topicid">
					<option value="0">No parent</option>
					<?=fetchoptions($rec->fields["topicid"], 0)?>
				</select>
				<?php } else { ?>
				<input type="text" size="11" maxlength="11" id="topicid" name="topicid" value="<?=$rec->fields["topicid"]?>" />
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">Description:</td>
		</tr>
		<tr>
			<td colspan="2">
				<textarea id="description" name="description" rows="5" cols="40"><?=htmlspecialchars($rec->fields["description"])?></textarea>
			</td>
		</tr>
		<tr>
			<td colspan="2" style="text-align: center;">
				<input type="hidden" id="do" name="do" value="editnewtopic" />
				<input type="submit" value="Edit Topic!" />
			</td>
		</tr>
		</table>
		</form>
		
		<script language="JavaScript" type="text/javascript">
		window.onload = document.getElementById('title').focus();
		</script>
		
		<?php } ?>
	</div>
	
	<div class="footer">
		<a href="javascript:window.close();">Close Window</a>
	</div>
</div>
</body>
</html>