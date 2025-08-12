<?php
// admin functions
function versioninfo(){
	global $config;
	
	$result = @file("http://www.particlesoft.net/getlatest/particlelinks.txt");
	if (!$result){
		$txt = "Unable to get version information";
	} else {
		$version  = intval($result[0]);
		$tversion = intval($config["versionint"]);
		
		if ($version > $tversion){
			$txt = "There is a newer version of the script available";
		} elseif ($version == $tversion){
			$txt = "You are running the latest version";
		} else {
			$txt = "You appear to be running an unreleased version";
		}
	}
	
	return $txt;
}

function updateconfig(){
	global $db, $dbprefix, $usr;
	
	if ($usr->Access < 2){ return "Admin only!"; }
	
	$sql = "SELECT * FROM " . $dbprefix . "config WHERE config_help <> ''";
	$rec = $db->execute($sql);
	do {
	
		$sql  = "UPDATE " . $dbprefix . "config SET config_value = ";
		$sql .= "'" . dbSecure($_POST[$rec->fields["config_name"]]) . "' ";
		$sql .= " WHERE config_name = '" . dbSecure($rec->fields["config_name"]) . "'";
		$db->execute($sql);
	
	} while ($rec->loop());
	
	// and return
	return "Config updated successfully!";
}

function updatephrases(){
	global $db, $dbprefix, $usr;
	
	if ($usr->Access < 2){ return "Admin only!"; }
	
	$sql = "SELECT * FROM " . $dbprefix . "phrases";
	$rec = $db->execute($sql);
	do {
	
	if ($_POST[$rec->fields["phraseid"]] <> ""){
	
		$sql  = "UPDATE " . $dbprefix . "phrases SET phrase_value = ";
		$sql .= "'" . dbSecure($_POST[$rec->fields["phraseid"]]) . "' ";
		$sql .= " WHERE phraseid = " . $rec->fields["phraseid"];
		$db->execute($sql);
	
	}
	
	} while ($rec->loop());
	
	// and return
	return "Phrases updated successfully!";
}

function addphrase($phrase_name, $phrase_value){
	global $db, $dbprefix, $usr;
	
	if ($usr->Access < 2){ return "Admin only!"; }
	
	// standard validation
	if ($phrase_name == ""){ return "You did not enter a name for the phrase"; }
	if ($phrase_value == ""){ return "You did not enter the phrase itself"; }
	
	// check name isn't being used
	$sql = "SELECT * FROM " . $dbprefix . "phrases WHERE phrase_name = '" . dbSecure($phrase_name) . "'";
	$rec = $db->execute($sql);
	if ($rec->rows > 0){ return "A phrase with this name already exists"; }
	
	// ok, run the insert
	$sql  = "INSERT INTO " . $dbprefix . "phrases (phrase_name, phrase_value) VALUES (";
	$sql .= "'" . dbSecure($phrase_name) . "', ";
	$sql .= "'" . dbSecure($phrase_value) . "')";
	$db->execute($sql);
	
	// and return
	return "Phrase added successfully!";
}

function multilinephrase($phrase){
	global $db, $dbprefix;
	
	// check the phrase exists
	$sql = "SELECT * FROM " . $dbprefix . "phrases WHERE phrase_name = '" . dbSecure($phrase) . "'";
	$rec = $db->execute($sql);
	if ($rec->rows < 1){ return "The phrase could not be found"; }
	
	// run the update
	$sql  = "UPDATE " . $dbprefix . "phrases SET ";
	$sql .= "phrase_value = '" . addslashes($rec->fields["phrase_value"] . "\n") . "' ";
	$sql .= "WHERE phraseid = " . $rec->fields["phraseid"];
	$db->execute($sql);
	
	// and return
	return "Phrase made multi-line successfully!";
}

function flushwebsites(){
	global $db, $dbprefix, $usr;
	
	if ($usr->Access < 2){ return "Admin only!"; }
	
	$sql = "DELETE FROM " . $dbprefix . "queue";
	$db->execute($sql);
	
	return "Website queue flushed successfully!";
}

function flushtopics(){
	global $db, $dbprefix, $usr;
	
	if ($usr->Access < 2){ return "Admin only!"; }
	
	$sql = "DELETE FROM " . $dbprefix . "newtopics";
	$db->execute($sql);
	
	return "Topic queue flushed successfully!";
}

// for removing old archived templates
function clearhistory($days){
	global $db, $dbprefix;
	
	$days = (intval($days) * 86400);
	
	$old = (time() - $days);
	
	// clear them out
	$sql = "DELETE FROM " . $dbprefix . "skinhistory WHERE postdate < " . $old;
	$db->execute($sql);
	
	// and return
	return "Archives older than " . date("j F Y", $old) . " cleared!";
}
?>