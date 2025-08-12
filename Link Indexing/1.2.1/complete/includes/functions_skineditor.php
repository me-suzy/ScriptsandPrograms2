<?php
// functions for that wacky skin editor
function saveas($skinid, $title, $tf = 1){
	global $db, $dbprefix;
	
	if ($title == ""){ return "You did not enter a title"; }
	
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The skinset could not be found"; }
	
	// now insert it into a thing
	$sql  = "INSERT INTO " . $dbprefix . "skinsets (title, imagesdir, css) VALUES (";
	$sql .= "'" . dbSecure($title) . "', ";
	$sql .= "'" . dbSecure($rec->fields["imagesdir"]) . "', ";
	$sql .= "'" . dbSecure($rec->fields["css"]) . "')";
	$db->execute($sql);
	$n = mysql_insert_id();
	
	// now create copy of the skinfiles
	if ($tf == 1){
		$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE skinid = " . dbSecure($skinid);
		$fil = $db->execute($sql);
		if ($fil->rows > 0){ do {
		
			$sql  = "INSERT INTO " . $dbprefix . "skinfiles (skinid, shortie, code) VALUES (";
			$sql .= dbSecure($n) . ", ";
			$sql .= "'" . dbSecure($fil->fields["shortie"]) . "', ";
			$sql .= "'" . dbSecure($fil->fields["code"]) . "')";
			$db->execute($sql);
		
		} while ($fil->loop()); }
	}
	
	// and return
	return "New skin created successfully. Use the menu above to switch to it.";
}

function editskin($skinid, $title, $imagesdir, $visible, $css){
	global $db, $dbprefix;
	
	// standard validation
	if ($title == ""){ return "You did not enter a title"; }
	
	// validate the skinset existance
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The skinset could not be found"; }
	
	// work out skin value
	if ($visible == "1"){
		$visible = 1;
	} else {
		$visible = 0;
	}
	
	// and let us update
	$sql  = "UPDATE " . $dbprefix . "skinsets SET ";
	$sql .= "title = '" . dbSecure($title) . "', ";
	$sql .= "imagesdir = '" . dbSecure($imagesdir) . "', ";
	$sql .= "visible = " . dbSecure($visible) . ", ";
	$sql .= "css = '" . dbSecure($css) . "' ";
	$sql .= "WHERE skinid = " . dbSecure($skinid);
	$db->execute($sql);
	
	// and return
	return "Skinset updated successfully";
}

function deleteskin($skinid, $confirm){
	global $db, $dbprefix;
	
	// check for confirmation
	if ($confirm <> 1){ return "You did not confirm the deletion"; }
	
	// validation thing
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The skinset could not be found"; }
	
	if ($skinid == 1){ return "Sorry, you can't delete skin with ID #1"; }
	
	// let's delete all the files
	$sql = "DELETE FROM " . $dbprefix . "skinfiles WHERE skinid = " . $rec->fields["skinid"];
	$db->execute($sql);
	
	// now lets delete all the historical entries
	$sql = "DELETE FROM " . $dbprefix . "skinhistory WHERE skinid = " . $rec->fields["skinid"];
	$db->execute($sql);
	
	// and delete the skinset
	$sql = "DELETE FROM " . $dbprefix . "skinsets WHERE skinid = " . $rec->fields["skinid"];
	$db->execute($sql);
	
	// and return successfully
	return "Skinset deleted successfully";
}

// create a new template
function newtemplate($skinid, $shortie){
	global $db, $dbprefix;
	
	// standard validation
	$skinid = intval($skinid);
	if ($shortie == ""){ return "You did not enter a name for the template"; }
	
	// validate the skinset
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The skinset could not be found"; }
	
	// and run the insert
	$sql  = "INSERT INTO " . $dbprefix . "skinfiles (skinid, shortie) VALUES (";
	$sql .= $rec->fields["skinid"] . ", '" . dbSecure($shortie) . "')";
	$db->execute($sql);
	
	// finally return
	return "Template created successfully";
}

function edittemplate($skinid, $shortie, $code){
	global $db, $dbprefix;
	
	// standard validation
	$fileid = intval($fileid);
	if ($shortie == ""){ return "No template name entered"; }
	
	// validate existance of template
	$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE skinid = " . dbSecure($skinid) . " AND shortie = '" . dbSecure($shortie) . "'";
	$rec = $db->execute($sql);
	if ($rec->rows < 1){
		// newly customised, insert entry
		$sql  = "INSERT INTO " . $dbprefix . "skinfiles (skinid, shortie, code) VALUES (";
		$sql .= $skinid . ", ";
		$sql .= "'" . dbSecure($shortie) . "', ";
		$sql .= "'" . dbSecure($code) . "')";
		$db->execute($sql);
		
	} else {
		// make an archive of the old one
		archivecode($skinid, $shortie, $code);
		
		// update the template
		$sql  = "UPDATE " . $dbprefix . "skinfiles SET ";
		$sql .= "code = '" . dbSecure($code) . "' ";
		$sql .= "WHERE fileid = " . $rec->fields["fileid"];
		$db->execute($sql);
	}
	
	// finally return
	return "Template edited successfully";
}

function deletetemplate($skinid, $fileid, $confirm = ""){
	global $db, $dbprefix;
	
	// standard validation
	$skinid = intval($skinid);
	
	// check user confirmed
	if ($confirm <> "delete"){
		return "You did not confirm the deletion";
	}
	
	// validate the skinset
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure($skinid);
	$skn = $db->execute($sql);
	if ($skn->rows < 1){ return "The skinset could not be found"; }
	
	// validate the template
	$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE skinid = " . dbSecure($skinid) . " AND shortie = '" . dbSecure($fileid) . "'";
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The template could not be found"; }
	
	// and delete the template
	$sql = "DELETE FROM " . $dbprefix . "skinfiles WHERE fileid = " . dbSecure($rec->fields["fileid"]);
	$db->execute($sql);
	
	// and return successful
	return "Template deleted successfully";
}

function reverttemplate($skinid, $shortie){
	global $db, $dbprefix;
	
	// validate the existance of the template
	$sql = "SELECT * FROM " . $dbprefix . "skinfiles WHERE skinid = " . dbSecure(intval($skinid)) . " AND shortie = '" . dbSecure($shortie) . "'";
	$fil = $db->execute($sql);
	if ($fil->rows < 1){ return "Template could not be found"; }
	
	// now archive a copy of it
	archivecode($skinid, $shortie, $fil->fields["code"]);
	
	// first, get the base to revert to
	$sql = "SELECT * FROM " . $dbprefix . "skinbase WHERE shortie = '" . dbSecure($shortie) . "'";
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "Unable to find base skin to revert to"; }
	
	// now run the update
	$sql  = "UPDATE " . $dbprefix . "skinfiles SET ";
	$sql .= "code = '" . dbSecure($rec->fields["code"]) . "' ";
	$sql .= "WHERE skinid = " . dbSecure(intval($skinid)) . " ";
	$sql .= "AND shortie = '" . dbSecure($shortie) . "'";
	$db->execute($sql);
	
	// and return
	return "Template reverted successfully!";
}

// for creating a historical entry
function archivecode($skinid, $fileid, $code){
	global $db, $dbprefix;
	
	// standard validation
	if ($fileid == ""){ return "No template name supplied"; }
	if ($code == ""){ return "I'm not archiving an empty template!"; }
	
	// validate the skin
	$sql = "SELECT * FROM " . $dbprefix . "skinsets WHERE skinid = " . dbSecure(intval($skinid));
	$skn = $db->execute($sql);
	if ($skn->rows < 1){ return "Skinset could not be found"; }
	
	// validate differences
	$sql = "SELECT * FROM " . $dbprefix . "skinhistory WHERE skinid = " . $skn->fields["skinid"] . " AND shortie = '" . dbSecure($fileid) . "' ORDER BY postdate DESC LIMIT 0, 1";
	$las = $db->execute($sql);
	if ($las->rows > 0){
		// there is a previous copy
		if (un($code) == $las->fields["code"]){
			// code is identical
			return "Not archiving, as code is identical to last save";
		}
	}
	
	// insert the code
	$sql  = "INSERT INTO " . $dbprefix . "skinhistory (postdate, skinid, shortie, code) VALUES (";
	$sql .= time() . ", ";
	$sql .= $skn->fields["skinid"] . ", ";
	$sql .= "'" . dbSecure($fileid) . "', ";
	$sql .= "'" . dbSecure($code) . "')";
	$db->execute($sql);
	
	// and return
	return "Archived copy created successfully!";
}
?>