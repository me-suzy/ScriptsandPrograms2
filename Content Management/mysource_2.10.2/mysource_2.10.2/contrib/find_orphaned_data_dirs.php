<?php
	require_once(dirname(__FILE__).'/../web/init.php');
	$web =& get_web_system();
	$db =& $web->get_db();
	
	/* 
		PAGES
	*/
	$pageids_in_database = $db->single_column('select pageid from page order by pageid');
	$pageids_on_disk = array();

	$dirname = $DATA_PATH.'/restricted/page';
	$d = dir($dirname);
	while (false !== ($entry = $d->read())) {
		if ('.' == $entry or '..' == $entry) continue;
		// If a directory has only numbers in the name, its probablly one of ours
		if (is_dir("$dirname/$entry") && ! preg_match('/[^0-9]/', $entry)) {
			$pageids_on_disk[] = $entry;
		} else {
			echo "Found a stray file at $dirname/$entry\n";
		}
	}
	$d->close();

	$dirname = $DATA_PATH.'/unrestricted/page';
	$d = dir($dirname);
	while (false !== ($entry = $d->read())) {
		if ('.' == $entry or '..' == $entry) continue;
		// If a directory has only numbers in the name, its probablly one of ours
		if (is_dir("$dirname/$entry") && ! preg_match('/[^0-9]/', $entry)) {
			$pageids_on_disk[] = $entry;
		} else {
			echo "Found a stray file at $dirname/$entry\n";
		}
	}
	$d->close();

	$pages_on_disk_not_in_db = array_diff($pageids_on_disk, $pageids_in_database);
	asort($pages_on_disk_not_in_db);
	echo "Orphaned pageids: ".implode(', ', $pages_on_disk_not_in_db)."\n";


	/* 
		SITES
	*/
	$siteids_in_database = $db->single_column('select siteid from site order by siteid');
	$siteids_on_disk = array();

	$dirname = $DATA_PATH.'/restricted/site';
	$d = dir($dirname);
	while (false !== ($entry = $d->read())) {
		if ('.' == $entry or '..' == $entry or 'design' == $entry) continue;
		// If a directory has only numbers in the name, its probablly one of ours
		if (is_dir("$dirname/$entry") && ! preg_match('/[^0-9]/', $entry)) {
			$siteids_on_disk[] = $entry;
		} else {
			echo "Found a stray file at $dirname/$entry\n";
		}
	}
	$d->close();

	$dirname = $DATA_PATH.'/unrestricted/site';
	$d = dir($dirname);
	while (false !== ($entry = $d->read())) {
		if ('.' == $entry or '..' == $entry or 'design' == $entry) continue;
		// If a directory has only numbers in the name, its probablly one of ours
		if (is_dir("$dirname/$entry") && ! preg_match('/[^0-9]/', $entry)) {
			$siteids_on_disk[] = $entry;
		} else {
			echo "Found a stray file at $dirname/$entry\n";
		}
	}
	$d->close();

	$sites_on_disk_not_in_db = array_diff($siteids_on_disk, $siteids_in_database);
	asort($sites_on_disk_not_in_db);
	echo "Orphaned siteids: ".implode(', ', $sites_on_disk_not_in_db)."\n";


?>
