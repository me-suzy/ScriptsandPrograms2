<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under 
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------


// don't access directly..
if(!defined('INPIVOT')){ exit('not in pivot'); }

// lamer protection
if (strpos($pivot_path,"ttp://")>0) {	die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);
if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection



// for now, we'll just use the xml / flat-file module..
if (file_exists(realpath($pivot_path). '/modules/module_db_xml.php')) {
	include_once( realpath($pivot_path). '/modules/module_db_xml.php');
}

class db {


// initialise the db
function db($loadindex=TRUE) {

	// Initialise the object for the lower-level functions
	$this->db_lowlevel = new db_lowlevel($loadindex);

}

// get a list of entries by date
function getlist($amount, $offset=0, $filteronuser="", $filteroncat="", $order=TRUE, $field="") {
	
	//debug("getlist - params: $amount, $offset, $filteronuser, $filteroncat");
	return $this->db_lowlevel->getlist($amount, $offset, $filteronuser, $filteroncat, $order, $field);

}

// get a list of entries by from the end of the list
function getlist_end($amount, $filteronuser="", $filteroncat="", $order=TRUE) {
	
	//debug("getlist - params: $amount, $offset, $filteronuser, $filteroncat");
	return $this->db_lowlevel->getlist_end($amount, $filteronuser, $filteroncat, $order);

}

function getlist_range($start_date, $stop_date, $filteronuser="", $filteroncat="", $order=TRUE) {

	//debug("getlist_range - params: $start_date, $stop_date, $filteronuser, $filteroncat");
	return $this->db_lowlevel->getlist_range($start_date, $stop_date, $filteronuser, $filteroncat, $order);

}


function getcodes_range($start_date, $stop_date, $filteronuser="", $filteroncat="", $order=TRUE) {

	//debug("getcodes_range - params: $start_date, $stop_date, $filteronuser, $filteroncat");
	return $this->db_lowlevel->getcodes_range($start_date, $stop_date, $filteronuser, $filteroncat, $order);

}


function get_archive_array($force=FALSE) {

	return $this->db_lowlevel->get_archive_array($force);

}


/*
// return a bit of HTML to insert a <select> with all available categories
function get_categories_select() {
	
	$output="";

	$this_cat=$this->entry['category'];

	if (isset($this->globals['categories'])) {
		$output="</td><td> <select name='f_catsing' style='height: 140px;' >";
		foreach ($this->globals["categories"] as $cat) {
			
			$cat= trim($cat);

			$sel = ($cat==$this_cat) ? " selected" : "";
			$output.="<option value='$cat'$sel>$cat</option>";
		}
		$output.="</select>pompom&nbsp;";
	}
	return $output;
}
*/


// return the number of categories
function get_categories_num() {
	$output="";

	if (isset($this->globals['categories'])) {
		return (count($this->globals['categories']));
	} else {
		return 0;
	}
}

// get the number of entries
function get_entries_count() {

	return $this->db_lowlevel->get_entries_count();

}


// get the code of the next entry
function get_next_code($num=1) {
	return $this->db_lowlevel->get_next_code($num);
}


// get the code of the previous entry
function get_previous_code($num=1) {
	return $this->db_lowlevel->get_previous_code($num);
}



// rebuild the index, if necessary
function generate_index() {

	if ($this->db_lowlevel->need_index()) {

		$this->db_lowlevel->generate_index();

	} else {

		echo "this database does not need an index.<br />";

	}

}

function unread_entry($code) {
	global $entriescache, $cachedcount, $loadcount;

	if (isset($entriescache[$code])) {
		unset($entriescache[$code]);
	}

}

function entry_exists($code) {

	return $this->db_lowlevel->entry_exists($code);

}


// This function retrieves a full entry as an associative array, and returns it.
function read_entry($code, $force=FALSE) {
	global $entriescache, $cachedcount, $loadcount;
	
	// Use this if you don't want to cache entries
	//$this->entry = $this->db_lowlevel->read_entry($code);
	
	// This is used for caching entries, so we don't have to acces disk so much.
	if (isset($entriescache[$code]) && ($entriescache[$code]['code'] == $code) && ($force==FALSE) ) {
		$cachedcount++;
		//debug("entry ".$code." from cache ($loadcount)");
		$this->entry = $entriescache[$code];
	} else {
		$loadcount++;
		$this->entry = $this->db_lowlevel->read_entry($code, $force);
		//debug("entry ".$code." was loaded ($loadcount)");
		$entriescache[$code] = $this->entry;
	}
	
	// cache no more than 200 entries, to keep memory from going insane..
	if (count($entriescache) > 200 ) {
		// we remove the first entry, assuming that is the one that is least likely to be needed again.
		$unsh = array_shift($entriescache);
		//debug("remove entry ".$unsh['code']." from cache ($loadcount)");
	} 
	

	return $this->entry;

}

function set_entry($entry) {
	global $entriescache, $serialize_cache, $loadcount;


	if (is_word_html($entry['introduction'])) {
		echo "<p>You pasted text directly from Microsoft Word. Some of the markup might be lost</p>";
		$entry['introduction'] = strip_word_html($entry['introduction']);
	}

	if (is_word_html($entry['body'])) {
		echo "<p>You pasted text directly from Microsoft Word. Some of the markup might be lost</p>";
		$entry['body'] = strip_word_html($entry['body']);
	}

	$entry['introduction'] = strip_scripting($entry['introduction']);

	$this->entry = $this->db_lowlevel->set_entry($entry);

	// also, change it in the entry cache
	//unset($entriescache); //[$entry['code']] = $entry;
	//unset($serialize_cache);

	//echo "<pre>";
	//print_r($this->db_lowlevel->entry_index);
	//echo "</pre>";

	return $this->entry;

}

function delete_entry() {
	global $entriescache, $cachedcount, $loadcount;

	if (isset($this->entry['code'])) {
		unset($entriescache[$this->entry['code']]);
	}
	
	serialize_uncache("ALL");
	
	$this->db_lowlevel->delete_entry();

}

function save_entry($update_index=TRUE) {
	
	return $this->db_lowlevel->save_entry($update_index);

}

function get_date($code) {

	return $this->db_lowlevel->get_date($code);

}

function disallow_write() {
	$this->db_lowlevel->disallow_write();
}


function allow_write() {
	$this->db_lowlevel->allow_write();
}

// end of class
}



?>
