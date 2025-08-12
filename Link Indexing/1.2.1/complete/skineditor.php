<?php
require("includes/global.php");

// check user level
if ($usr->Access < 2){ die("You do not have access"); }

// check for actions
if ($_POST["do"] == "saveas"){
	$errormsg = saveas($_GET["skinid"], $_POST["saveas"], $_POST["savefiles"]);
} elseif ($_POST["do"] == "editskin"){
	$errormsg = editskin($_GET["skinid"], $_POST["title"], $_POST["imagesdir"], $_POST["visible"], $_POST["css"]);
} elseif ($_POST["do"] == "deleteskin"){
	$errormsg = deleteskin($_POST["skinid"], $_POST["confirm"]);
	if ($errormsg == "Skinset deleted successfully"){ $_GET["skinid"] = 1; }
} elseif ($_POST["do"] == "newtemplate"){
	$errormsg = newtemplate($_GET["skinid"], $_POST["new"]);
} elseif ($_POST["do"] == "Delete"){
	$errormsg = deletetemplate($_GET["skinid"], $_POST["fileid"], $_POST["confirm"]);
} elseif ($_POST["act"] == "Save File"){
	$errormsg = edittemplate($_POST["skinid"], $_POST["title"], $_POST["code"]);
} elseif ($_POST["act"] == "Revert"){
	$errormsg = reverttemplate($_POST["skinid"], $_POST["title"]);
} elseif ($_POST["act"] == "Cancel Edit"){
	Header("Location: skineditor.php?skinid=" . $_GET["skinid"]); die();
}

// work out which skin
if ($_GET["skinid"] <> ""){ $skinid = $_GET["skinid"]; } else { $skinid = 1; }
$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
$rec = $db->execute($sql);
if ($rec->rows < 1){ die("Unable to locate skin - check config"); }

// list of skins for jump menu
$sql = "SELECT * FROM " . $dbprefix . "skinsets ORDER BY title ASC";
$lis = $db->execute($sql);

// work out which page it is
if ($_REQUEST["do"] == "Edit File"){
	$pagesect = 1;
	$fileid = $_REQUEST["fileid"];
	
	$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE shortie = '" . dbSecure($fileid) . "' AND skinid = " . dbSecure($skinid);
	$fil = $db->execute($sql);
	if ($fil->rows < 1){
		
		$sql = "SELECT * FROM " . $dbprefix . "skinbase WHERE shortie = '" . dbSecure($fileid) . "'";
		$fil = $db->execute($sql);
		if ($fil->rows < 1){
			die("Unable to locate skin file");
		} else {
			$t_type = 1;
		}
	} else {
		// skin can be found, is it revertable?
		$sql = "SELECT * FROM " . $dbprefix . "skinbase WHERE shortie = '" . dbSecure($fileid) . "'";
		$rev = $db->execute($sql);
		if ($rev->rows > 0){
			$revertable = 1;
			
			if ($rev->fields["code"] == $fil->fields["code"]){
				$useless = 1;
			}
		}
	}
	
	// check for historical call
	$historyid = $_REQUEST["historyid"];
	if ($historyid <> ""){
		$sql = "SELECT * FROM " . $dbprefix . "skinhistory WHERE historyid = " . dbSecure(intval($historyid)) . " AND skinid = " . dbSecure(intval($skinid)) . " AND shortie = '" . dbSecure($fileid) . "'";
		$his = $db->execute($sql);
		if ($his->rows < 1){
			$errormsg = "Unable to locate archived copy";
		} else {
			$fil->fields["code"] = $his->fields["code"];
			$historical = 1;
			$revertable = 0;
		}
	}
	
} else {
	// work out which skin
	if ($rec->fields["visible"] == 1){
		$c1 = ' selected="selected"';
		$c2 = '';
	} else {
		$c1 = '';
		$c2 = ' selected="selected"';
	}
	
}

// get list of templates
$temlist = Array();

$sql = "SELECT shortie FROM " . $dbprefix . "skinbase ORDER BY shortie ASC";
$tem = $db->execute($sql);
if ($tem->rows > 0){ do {
	array_push($temlist, $tem->fields["shortie"]);
} while ($tem->loop()); }

$sql = "SELECT shortie FROM " . $dbprefix . "skinfiles WHERE skinid = " . dbSecure($rec->fields["skinid"]);
$tem = $db->execute($sql);
if ($tem->rows > 0){ do {
	
	$h = array_search($tem->fields["shortie"], $temlist);
	if (!($h === FALSE)){
		$temlist[$h] = "<strong>" . $tem->fields["shortie"] . "</strong>";
	} else {
		array_push($temlist, "<strong>" . $tem->fields["shortie"] . "</strong>");
	}
	
} while ($tem->loop()); }

// set up variables
$core = "skineditor.php?skinid=" . $rec->fields["skinid"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<title><?=$config["sitename"]?> Skin Editor</title>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<link rel="stylesheet" type="text/css" href="shared/popup.css" />
<script language="JavaScript" type="text/javascript" src="shared/functions.js"></script>
</head>
<body>
<div class="container">
	<div class="header">
		<div style="float: right;"><a href="javascript:window.close();">X</a></div>
		Skin Editor
	</div>
	
	<div class="main">
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
			<td><strong>Editing <?=$rec->fields["title"]?></strong></td>
			<td style="text-align: right;">
				<form action="skineditor.php" method="get" id="p" name="p">
					Change: 
					<select id="skinid" name="skinid" onChange="skinjump(document.forms.p.skinid.options[selectedIndex].value);">
						<option value="">Select...</option>
						<?php if ($lis->rows > 0){ do { ?>
						<option value="<?=$lis->fields["skinid"]?>"><?=$lis->fields["title"]?></option>
						<?php } while ($lis->loop()); } ?>
					</select>
					<input type="submit" value="GO" />
				</form>
			</td>
		</tr></table>
		<hr />
		
		<?php if ($errormsg <> ""){ echo($errormsg . "<hr />"); } ?>
		
		<?php if ($pagesect == 1){ // editing a template ?>
		
		<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr>
			<td><i>Template: <?=$fil->fields["shortie"]?></i></td>
			<td style="text-align: right;">
				<form action="skineditor.php" method="get" id="fi" name="fi">
					Change: 
					<select id="fileid" name="fileid" onChange="skinfilejump(<?=$skinid?>,document.forms.fi.fileid.options[selectedIndex].value);">
						<option value="">Select...</option>
						<?php
						foreach($temlist as $x){
						
						if ($x <> strip_tags($x)){
							$cssc = "font-weight: bold;";
							$x = strip_tags($x);
						} else {
							$cssc = "";
						}
						?>
						<option value="<?=$x?>" style="<?=$cssc?>"><?=$x?></option>
						<?php } ?>
					</select>
					<input type="hidden" id="skinid" name="skinid" value="<?=$skinid?>" />
					<input type="hidden" id="do" name="do" value="Edit File" />
					<input type="submit" value="GO" />
				</form>
			</td>
		</tr></table>
		<hr />
		
		<form action="<?=$core?>&do=Edit File" method="post">
			Title: <input type="text" size="30" id="title" name="title" value="<?=$fil->fields["shortie"]?>" /><br /><br />
			
			<?php if ($useless == 1 || $historical == 1){ ?>
			<div style="border: #FFFF00 3px solid; font-size: 80%; padding: 3px;">
			<?php if ($historical == 1){ ?>
			Note: You are viewing an archived copy of the template from <strong><?=date("j F Y H:i", $his->fields["postdate"])?></strong>, if you save the file then this old version will overwrite the new version. To return to the current version select current from the drop down at the bottom.
			<?php } else { ?>
			Note: Although this is a customised version of the template, it is identical to the base template. If you do not plan to customise it, it is recommended you delete it (from the skin overview page) so that it automatically updates when the base template is updated.
			<?php } ?>
			</div><br />
			<?php } ?>
			
			<?php if ($t_type == 1){
				$text = "Uncustomised Code";
				$cssc = "";
				$cssb = "";
			} else {
				$text = "Customised Code";
				$cssc = "background: #FFFF99;";
				$cssb = "background: #FFFF99;";
			}
			?>
			
			<span style="<?=$cssc?>"><?=$text?><br />
			<textarea cols="60" rows="15" id="code" name="code" style="<?=$cssb?>"><?=htmlspecialchars($fil->fields["code"])?></textarea><br />
			</span><br />
			
			<input type="hidden" id="fileid" name="fileid" value="<?=$fil->fields["shortie"]?>" />
			<input type="hidden" id="skinid" name="skinid" value="<?=$skinid?>" />
			<input type="hidden" id="do" name="do" value="Edit File" />
			<input type="submit" id="act" name="act" value="Save File" />
			<?php if ($revertable == 1){ ?>
			<input type="submit" id="act" name="act" value="Revert" />
			<?php } ?>
			<input type="submit" id="act" name="act" value="Cancel Edit" /><br />
			
			You can use cancel to return to the skinset overview. Use revert to change template to standard. However this will not mean it updates when the base template set is updated in future - to do that you must delete the template.
			
			<?php
			$sql = "SELECT * FROM " . $dbprefix . "skinhistory WHERE skinid = " . dbSecure(intval($skinid)) . " AND shortie = '" . dbSecure($fileid) . "' ORDER BY postdate DESC LIMIT 0, 100";
			$rev = $db->execute($sql);
			if ($rev->rows > 0){
			?>
			<br /><br />
			<form action="skineditor.php" method="get">
			<strong>Historical Versions</strong><br />
			<select id="historyid" name="historyid">
				<option value="">Current Version</option>
				<?php do { ?>
				<option value="<?=$rev->fields["historyid"]?>"><?=date("j F Y H:i", $rev->fields["postdate"])?></option>
				<?php } while ($rev->loop()); ?>
			</select>
			<input type="submit" value="View!" />
			</form>
			<?php } // end historical entries check ?>
		
		<input type="hidden" id="skinid" name="skinid" value="<?=$skinid?>" />
		<input type="hidden" id="fileid" name="fileid" value="<?=$fileid?>" />
		<input type="hidden" id="do" name="do" value="Edit File" />
		
		</form>
		
		<?php } else { // not editing a templage ?>
		
		[ Skin ID: <?=$skinid?> | <a href="<?=$config["topicpage"]?>&s=<?=$skinid?>" target="_blank">Preview</a> ]<br /><br />
		
		<form action="<?=$core?>" method="post">
			<strong>Edit Templates</strong><br />
			<select id="fileid" name="fileid">
				<?php
				foreach($temlist as $x){
				
				if ($x <> strip_tags($x)){
					$cssc = "font-weight: bold;";
					$x = strip_tags($x);
				} else {
					$cssc = "";
				}
				?>
				<option value="<?=$x?>" style="<?=$cssc?>"><?=$x?></option>
				<?php } ?>
			</select>
			<input type="submit" id="do" name="do" value="Edit File" />
			<input type="submit" id="do" name="do" value="Delete" />
			<input type="checkbox" id="confirm" name="confirm" value="delete" /> Click to confirm a delete
		</form><br />
		
		<form action="<?=$core?>" method="post">
			<input type="text" size="30" maxlength="50" id="new" name="new" />
			<input type="hidden" id="do" name="do" value="newtemplate" />
			<input type="submit" value="Create New" />
		</form><br />
		
		<form action="<?=$core?>" method="post">
		<strong>Save Skin As... (create new)</strong><br />
		<input type="text" size="30" id="saveas" name="saveas" maxlength="50" />
		<select id="savefiles" name="savefiles">
			<option value="1">Copy Templates</option>
			<option value="0">Leave Templates</option>
		</select>
		<input type="hidden" id="do" name="do" value="saveas" />
		<input type="submit" value="Save As" />
		</form><br />
		
		<form action="<?=$core?>" method="post">
		<strong>Edit Skin</strong><br />
		Title:<br />
		<input type="text" size="50" id="title" name="title" maxlength="50" value="<?=$rec->fields["title"]?>" /><br /><br />
		
		Images Directory:<br />
		<input type="text" size="50" id="imagesdir" name="imagesdir" maxlength="50" value="<?=$rec->fields["imagesdir"]?>" /><br /><br />
		
		User Selectable:<br />
		<select id="visible" name="visible">
			<option value="1"<?=$c1?>>Visible</option>
			<option value="0"<?=$c2?>>Invisible</option>
		</select><br /><br />
		
		CSS Code:<br />
		<textarea cols="60" rows="15" id="css" name="css"><?=$rec->fields["css"]?></textarea><br /><br />
		
		<input type="hidden" id="do" name="do" value="editskin" />
		<input type="submit" value="Update Skin!" />
		</form><br />
		
		<form action="<?=$core?>" method="post">
		<strong>Delete Skin</strong><br />
		<select id="confirm" name="confirm">
			<option value="0">Don't Delete</option>
			<option value="1">Delete It!</option>
		</select>
		<input type="hidden" id="skinid" name="skinid" value="<?=$_GET["skinid"]?>" />
		<input type="hidden" id="do" name="do" value="deleteskin" />
		<input type="submit" value="Delete" />
		</form>
		
		<?php } // end pagesect check ?>
		
	</div>
	
	<div class="footer">
		<a href="javascript:window.close();">Close Window</a>
	</div>
</div>
</body>
</html>