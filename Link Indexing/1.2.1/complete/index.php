<?php
require("includes/global.php");

// begin building breadcrumbs
$bread = '<a href="' . $config["topicpage"] . '">' . $phrase["top"] . '</a>';
$rlink = "";

// check for editor actions
if ($usr->Access > 0){
	if ($_GET["queueid"] <> ""){
		$errormsg = considersite($_GET["queueid"], $_GET["act"]);
	} elseif ($_GET["newtopicid"] <> ""){
		$errormsg = considertopic($_GET["newtopicid"], $_GET["nt_act"]);
	}
	
	// check for editor mode
	if ($_GET["mode"] == "editor"){
		$editormode = 1;
	}
}

// check for regular actions
if ($_POST["do"] == "suggesturl"){
	$errormsg = suggestsite($_POST["topicid"], $_POST["website"], $_POST["url"], $_POST["description"], $_POST["email"]);
} elseif ($_POST["do"] == "suggesttopic"){
	$errormsg = suggesttopic($_POST["topicid"], $_POST["topic_title"], $_POST["email"], $_POST["description"]);
}

if ($_GET["topic"] <> ""){
	// browsing a topic
	$parent = 0;
	$topic  = $_GET["topic"];
	$cursor = explode("/", $topic);
	array_pop($cursor);
	
	// loop through path to get records
	foreach ($cursor as $t){
		$sql = "SELECT * FROM " . $dbprefix . "topics WHERE parent = " . dbSecure($parent) . " AND title = '" . dbSecure(d1($t)) . "'";
		$top = $db->execute($sql);
		if ($top->rows < 1){ notfound(); }
		$parent = $top->fields["topicid"];
		
		// build recordset
		$rlink .= d2($top->fields["title"]) . "/";
		$bread .= ' ' . $config["breadcrumb"] . ' <a href="' . $config["topicpage"] . $rlink . '">' . $top->fields["title"] . '</a>';
	}
	
	// now get sub-topics recordset
	$vis = ($usr->Access > 0) ? 0 : 1;
	$sql = "SELECT * FROM " . $dbprefix . "topics WHERE parent = " . $top->fields["topicid"] . " AND visible >= " . $vis . " ORDER BY title ASC";
	$rec = $db->execute($sql);
	
	// and links recordset
	$sql = "SELECT * FROM " . $dbprefix . "links WHERE topicid = " . $top->fields["topicid"] . " ORDER BY priority DESC, website ASC";
	$web = $db->execute($sql);
	
	// put topic id in place
	$topicid = $top->fields["topicid"];
	
} else {
	
	// parent topics
	$sql = "SELECT * FROM " . $dbprefix . "topics WHERE parent = 0 AND visible = 1 ORDER BY title ASC";
	$rec = $db->execute($sql);
	
	// put topic id in place
	$topicid = 0;
	
}

// sub-topics half way point
if ($rec->rows > 0){
	$half = ceil($rec->rows / 2);
	$row  = 0;
}

// work out page title
if ($top){
	$pagetitle = $config["sitename"] . " - " . $top->fields["title"];
} else {
	$pagetitle = $config["sitename"];
}

// build a core URL
$root = $config["virtualpath"];
$core = $root . "index.php?topic=" . $_GET["topic"];

// page header
include("includes/page_header.php");
?>

<?php if ($errormsg <> ""){ ?>
<?=$errormsg?>
<hr />
<?php } ?>

<?php if ($rec->rows > 0){ ?>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
	<tr valign="top">
		<td style="width: 50%;">
			<ul>
				<?php
				do {
				$cssclass = ($rec->fields["visible"] == 1) ? '' : ' class="invisible"';
				$marker   = ($rec->fields["visible"] == 1) ? '' : '*';
				?>
				<li<?=$cssclass?>><a href="<?=$config["topicpage"] . $topic . d2($rec->fields["title"]) . "/"?>"><?=$rec->fields["title"]?></a><?=$marker?><?php if ($rec->fields["description"] <> ""){ echo(" - " . $rec->fields["description"]); } ?></li>
				<?php $row++; $rec->loop(); } while ($row < $half); ?>
			</ul>
		</td>
		<td style="width: 50%;">
			<?php if ($rec->rows < 2){ echo("&nbsp;"); } else { ?>
			<ul>
				<?php
				do {
				$cssclass = ($rec->fields["visible"] == 1) ? '' : ' class="invisible"';
				$marker   = ($rec->fields["visible"] == 1) ? '' : '*';
				?>
				<li<?=$cssclass?>><a href="<?=$config["topicpage"] . $topic . d2($rec->fields["title"]) . "/"?>"><?=$rec->fields["title"]?></a><?=$marker?><?php if ($rec->fields["description"] <> ""){ echo(" - " . $rec->fields["description"]); } ?></li>
				<?php } while ($rec->loop()); ?>
			</ul>
			<?php } ?>
		</td>
	</tr>
</table>
<?php } ?>

<?php if ($rec->rows > 0 && $web->rows > 0){ echo("<hr />"); } ?>

<?php if ($web->rows > 0){ ?>
<ul>
	<?php do { ?>
	<li><a href="<?=$web->fields["url"]?>" target="<?=$config["linktarget"]?>"><?=$web->fields["website"]?></a> - <?=$web->fields["description"]?></li>
	<?php } while ($web->loop()); ?>
</ul>
<?php } ?>

<?php
if ($config["showstats"] == "true" && $_GET["topic"] == ""){

$sql = "SELECT topicid FROM " . $dbprefix . "topics";
$sta = $db->execute($sql);
$st1 = number_format($sta->rows);
$sta->clear();

$sql = "SELECT linkid FROM " . $dbprefix . "links";
$sta = $db->execute($sql);
$st2 = number_format($sta->rows);
$sta->clear();

$sql = "SELECT queueid FROM " . $dbprefix . "queue";
$sta = $db->execute($sql);
$st3 = number_format($sta->rows);
$sta->clear();
?>
<hr />

<center>
	[ <?=$phrase["total_topics"]?>: <?=$st1?> | <?=$phrase["total_links"]?>: <?=$st2?> | <?=$phrase["total_queue"]?>: <?=$st3?> ]
</center>
<?php } // end stats check ?>

<?php if ($usr->Access > 0){ ?>
<hr />
<strong>Editor Tools</strong>

<ul>
	<li>Topic [ ID <?=$topicid?> ]</li>
	<ul>
		<li><a href="<?=$root?>moderate.php?page=addtopic&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('addtopic', '<?=$root?>', '<?=$topicid?>'); return false;">Add Sub-Topic</a></li>
		<li><a href="<?=$root?>moderate.php?page=edittopic&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('edittopic', '<?=$root?>', '<?=$topicid?>'); return false;">Edit Topic</a></li>
		<li><a href="<?=$root?>moderate.php?page=deletetopic&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('deletetopic', '<?=$root?>', '<?=$topicid?>'); return false;">Delete Topic</a></li>
		<li><a href="<?=$root?>moderate.php?page=visibility&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('visibility', '<?=$root?>', '<?=$topicid?>'); return false;">Toggle Visibility</a></li>
	</ul>
	
	<?php if ($topicid > 0){ ?>
	<li>Websites</li>
	<ul>
		<li><a href="<?=$root?>moderate.php?page=addlink&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('addlink', '<?=$root?>', '<?=$topicid?>'); return false;">Add Link</a></li>
		<li><a href="<?=$root?>moderate.php?page=managelinks&amp;topicid=<?=$topicid?>" target="_blank" onClick="openwin('managelinks', '<?=$root?>', '<?=$topicid?>'); return false;">Manage Links</a></li>
	</ul>
	<?php } ?>
	
	<li>Pages</li>
	<ul>
		<li><a href="<?=$root?>admin.php">Admin Page</a></li>
		<li><a href="<?=$root?>admin.php?do=signout&amp;from=<?=$_SERVER["REQUEST_URI"]?>">Sign Out</a></li>
	</ul>
</ul>

<strong>Invisible Topics</strong>
<ul>
	<li>Invisible topics are marked with a *</li>
</ul>

<?php
$sql = "SELECT * FROM " . $dbprefix . "queue WHERE topicid = " . dbSecure($topicid) . " ORDER BY postdate ASC";
$que = $db->execute($sql);
if ($que->rows > 0){
?>
<strong>User Submissions</strong>
<ul>
	<?php do { ?>
	<li>[ <a href="<?=$core?>&amp;act=accept&amp;queueid=<?=$que->fields["queueid"]?>">Accept</a> | <a href="<?=$core?>&act=reject&amp;queueid=<?=$que->fields["queueid"]?>">Reject</a> | <a href="<?=$root?>moderate.php?page=editqueue&amp;topicid=<?=$que->fields["queueid"]?>" target="_blank" onClick="openwin('editqueue', '<?=$root?>', '<?=$que->fields["queueid"]?>'); return false;">Edit</a> ] <a href="<?=$que->fields["url"]?>" target="<?=$config["linktarget"]?>"><?=$que->fields["website"]?></a> - <?=$que->fields["description"]?></li>
	<?php } while ($que->loop()); ?>
</ul>
<?php } ?>

<?php
$sql = "SELECT * FROM " . $dbprefix . "newtopics WHERE topicid = " . dbSecure($topicid) . " ORDER BY postdate ASC";
$tqu = $db->execute($sql);
if ($tqu->rows > 0){
?>
<strong>Topic Submissions</strong>
<ul>
	<?php do { ?>
	<li>[ <a href="<?=$core?>&amp;nt_act=accept&amp;newtopicid=<?=$tqu->fields["newtopicid"]?>">Accept</a> | <a href="<?=$core?>&nt_act=reject&amp;newtopicid=<?=$tqu->fields["newtopicid"]?>">Reject</a> | <a href="<?=$root?>moderate.php?page=editnewtopic&amp;topicid=<?=$tqu->fields["newtopicid"]?>" target="_blank" onClick="openwin('editnewtopic', '<?=$root?>', '<?=$tqu->fields["newtopicid"]?>'); return false;">Edit</a> ] <strong><?=$tqu->fields["title"]?></strong><?php if ($tqu->fields["description"] <> ""){ echo(" - " . $tqu->fields["description"]); } ?></li>
	<?php } while ($tqu->loop()); ?>
</ul>
<?php } ?>

<?php } elseif ($config["usersubmissions"] == "true" && $topicid > 0){ ?>
<hr />
<?php if ($_GET["mode"] == "suggest"){ ?>
<form action="<?=$core?>&amp;mode=suggest" method="post" id="suggest" name="suggest">

<?php if ($config["showsubmissionrules"] == "true" && $phrase["submit_rules"] <> ""){ ?>
<table cellpadding="4" cellspacing="1" border="0" class="rules">
	<tr>
		<th>Submission Rules</th>
	</tr>
	<tr>
		<td><?=Encode($phrase["submit_rules"])?></td>
	</tr>
</table><br />
<?php } // global rules ?>

<?php if ($top->fields["rules"] <> ""){ ?>
<table cellpadding="4" cellspacing="1" border="0" class="rules">
	<tr>
		<th>Topic Rules</th>
	</tr>
	<tr>
		<td><?=Encode($top->fields["rules"])?></td>
	</tr>
</table><br />
<?php } // topic rules ?>

<table cellpadding="0" cellspacing="5" border="0">
	<tr>
		<td><label for="website"><?=$phrase["sitename"]?></label>:</td>
		<td><input type="text" size="30" maxlength="255" id="website" name="website" /></td>
	</tr>
	<tr>
		<td><label for="url"><?=$phrase["url"]?></label>:</td>
		<td><input type="text" size="30" maxlength="255" id="url" name="url" value="http://" /></td>
	</tr>
	<tr>
		<td><label for="description"><?=$phrase["description"]?></label>:</td>
		<td><textarea id="description" name="description" cols="40" rows="4"></textarea></td>
	</tr>
	<tr>
		<td><label for="email"><?=$phrase["emailaddress"]?></label>:</td>
		<td><input type="text" size="30" maxlength="255" id="email" name="email" /></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<input type="submit" value="<?=$phrase["suggesturl"]?>!" />
			<input type="hidden" id="topicid" name="topicid" value="<?=$topicid?>" />
			<input type="hidden" id="do" name="do" value="suggesturl" />
		</td>
	</tr>
</table>
</form>

<?php } elseif ($_GET["mode"] == "suggesttopic"){ ?>
<form action="<?=$core?>&amp;mode=suggesttopic" method="post" id="suggest" name="suggest">
<table cellpadding="0" cellspacing="5" border="0">
	<tr>
		<td><label for="topic_title"><?=$phrase["topic"]?></label>:</td>
		<td><input type="text" size="30" maxlength="255" id="topic_title" name="topic_title" /></td>
	</tr>
	<tr>
		<td><label for="description"><?=$phrase["description"]?></label>:</td>
		<td><textarea id="description" name="description" cols="40" rows="4"></textarea></td>
	</tr>
	<tr>
		<td><label for="email"><?=$phrase["emailaddress"]?></label>:</td>
		<td><input type="text" size="30" maxlength="255" id="email" name="email" /></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align: center;">
			<input type="submit" value="<?=$phrase["suggesttopic"]?>!" />
			<input type="hidden" id="topicid" name="topicid" value="<?=$topicid?>" />
			<input type="hidden" id="do" name="do" value="suggesttopic" />
		</td>
	</tr>
</table>

<?php } else { ?>
<ul>
	<li><a href="<?=$core?>&amp;mode=suggest#suggest"><?=$phrase["suggesturl"]?></a></li>
	<?php if ($config["topicsubmissions"] == "true"){ ?>
	<li><a href="<?=$core?>&amp;mode=suggesttopic#suggesttopic"><?=$phrase["suggesttopic"]?></a></li>
	<?php } ?>
</ul>
<?php } ?>
<?php } // end editor or not check ?>

<?php
include("includes/page_footer.php");
?>