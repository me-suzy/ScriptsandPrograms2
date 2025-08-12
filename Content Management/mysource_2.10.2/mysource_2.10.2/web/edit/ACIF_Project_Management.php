<?php
    ##############################################
   ### MySource ------------------------------###
  ##- Include Files ------ PHP4 --------------##
 #-- Copyright Squiz.net ---------------------#
##############################################
## This file is subject to version 1.0 of the
## MySource License, that is bundled with
## this package in the file LICENSE, and is
## available at through the world-wide-web at
## http://mysource.squiz.net/
## If you did not receive a copy of the MySource
## license and are unable to obtain it through
## the world-wide-web, please contact us at
## mysource@squiz.net so we can mail you a copy
## immediately.
##
## File: web/edit/ACIF_Project_Management.php
## Desc: script to create pages from directories
## $Source: /home/cvsroot/mysource/web/edit/ACIF_Project_Management.php,v $
## $Revision: 2.14 $
## $Author: mmcintyre $
## $Date: 2004/01/26 22:43:04 $
#######################################################################



	#check to see if this script is being run from the command line
	if (php_sapi_name() != 'cli') {
		echo 'Access Denied. Script can only be run from the command line.<BR />';
		exit();
	}


	// @author: Marc McIntyre <mmcintyre@squiz.net>

	// execution time could take anywhere from 0.001 seconds to 14.1 Jurassic Eras 
	set_time_limit(0); 
	
	// could use anywhere between 100K to 1.4 million exabytes of memory
	ini_set('memory_limit', '-1');

	// include init.php to tap into mysource
	global $INCLUDE_PATH, $SQUIZLIB_PATH;

	require_once(dirname(dirname(__FILE__))."/init.php");
	require_once($INCLUDE_PATH."/webobject.inc");
	require_once($SQUIZLIB_PATH."/cache/file_cache.inc");
	require_once($INCLUDE_PATH."/page_template.inc");
	require_once($INCLUDE_PATH."/parameter_set.inc");
	require_once($SQUIZLIB_PATH."/bodycopy/bodycopy.inc");


	// MAIN //
	$proj_man = new ACIF_Project_Management($CONF_PATH, $DATA_PATH);
	$proj_man->getCurrentPagesAndFiles();					// get the current known pages and files
	$proj_man->getOptions();								// get the options \(siteid, design id, etc)
	$proj_man->dirLoop($proj_man->parameters['DIR']);		// looping
	$proj_man->writePageIndexCache();						// write the page index to cache

	fclose($proj_man->log_fp);
	

class ACIF_Project_Management { 


	// Constants
	var $IMB_CONTENT      = '';
	var $CAL_CONTENT      = '';
	var $CONF_PATH        = '';
	var $DATA_PATH        = '';
	var $A_STATUS         = Array('a', 'i', 'r', 'j', 'l', 'p');	// the available status's of directories

	var $TEMPLATE         = 'standard';						// template that we are using to create these pages
	var $RESTRICTION      = 'restricted';					// where in the data path the file are to exist
	var $TABLENAME        = 'file_builder';					// TABLENAME where details exist
	var $STATUS           = 'L';								// the status of the page when it is not archived
	var $STD_CONTENT_FILE = 'content.csv';					// the standard content file that has the pageid 
	var $CONF_FILE        = 'acif_filedesc.conf';			// file where file descriptions are stored

	var $db			      = null;							// db reference
	var $web			  = null;							// web reference
	var $site			  = null;							// site reference
	var $wc_template      = null;							// the content to get the WC content from
	var $fileid           = 0;								// the current fileid
	
	var $pages		      = Array();						// array of all the pages
	var $files            = Array();						// array of all the files
	var $ignore_dirs      = Array();						// directories to ignore
	var $is_parent        = true;							// used first loop or parent dir

	var $current_dir      = '';								// the current directory
	var $current_file     = '';								// the current file
	var $log_fp           = null;							// log filepointer
	var $prev_pageid      = 0;								// the previous pageid(used for ignore join)
	var $is_join          = false;
	var $join_cwd         ='';


	var $parameters = Array();


	/**
	* Constructor
	*/
	function ACIF_Project_Management($CONF_PATH, $DATA_PATH)
	{
		$this->CONF_PATH = $CONF_PATH;						// dir where file desc file is stored
		$this->DATA_PATH = $DATA_PATH;						// the path to the data directory
	
	}//end contructor


	/**
	* get the options from the web extension
	*
	* @return void
	* @access public
	*/
	function getOptions()
	{
		$web = &$this->getWeb();
		$obj = &$web->get_extension('ACIF_Project_Management');

		if (!file_exists("$obj->xtra_path/$obj->codename.pset")) {
			exit();
		}
		$pset = new Parameter_Set(get_class($obj),"$obj->xtra_path/$obj->codename.pset", $obj->parameters, $obj);
		
		$parameters = array();
		$parameters = $obj->parameters;

		foreach ($parameters as $p => $value) {
			$this->parameters[$p] = $value;	
		}

		if (!preg_match('/\/$/', $this->parameters['DIR'])) $this->parameters['DIR'] .= '/';
	
	}//end getOptions()


	/**
	* creates an error log
	*/
	function logError($error, $line)
	{
		if (!$this->log_fp) {
			$this->log_fp = fopen($this->parameters['ERROR_LOG'], 'a');
			if (!$this->log_fp) return false;
		}
		return fwrite($this->log_fp, $error."\t".$line."\n");
	
	}//end logError()


	/**
	* function to recurse through the directories
	*
	* @param string $dir		the directory to initialize the recurse
	* @param string $startdir	the startdir where the drill commensed
	*
	* @access public
	* @return bool
	*/
	function dirLoop($dir, $startdir = NULL) 
	{
		echo '.';
		chdir($dir); // change to the current directory
		if (!$startdir) {
			$startdir = $dir;
		}
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$cwd = getcwd(); // get the current working directory

				while (false !== ($file = readdir($dh))) {
					if ($file != ".." && $file != ".") {        // don't open the current or previous directory
						
						if (is_dir($file)) {

							// get the status of this directory and do whatever is needed based on the
							// status - archive, ignore, nothing etc
							// s_something  - we are dealing with something that has the status stripped of the front
							// something    - we are dealing with something that still has the status attached

							list($s_file, $s_cwd, $s_status) = $this->getDirStatus($file, $cwd); 
							$this->current_dir = $cwd;
							$this->current_file = $file;
							#$this->RESTRICTION = ($s_status == 'p' || $s_status == 'l') ? 'unrestricted' : 'restricted';

							
							if ((!$this->pageExists($s_file, $s_cwd)) && (!in_array($s_status, Array('i', 'r', 'j')))) {
								// dont create this page if it is ignored, or recursive ignore

								if ($this->is_join != $s_cwd) $this->is_join  = false;
								
								if  ($this->is_join) {
									if (preg_match('|'.$this->join_cwd.'/(.+)/(.+)|', $s_cwd.'/'.$s_file)) {
										$parentid = $this->getParentId($s_cwd.'/'.$s_file);
									} else {
										$parentid = $this->prev_pageid;
									}
								} else {
									$parentid = $this->getParentId($s_cwd.'/'.$s_file);
								}

								// create a new page
								$c_pageid = $this->createPage($s_file, $parentid, $s_cwd.'/'.$s_file, $s_status); 
								// check to see if we should be creating and more pages for this dir
								$this->checkDirType($s_cwd, $s_file, $c_pageid);

								$this->clearCache($c_pageid, 'page');
							
							} else {
								if ($s_status == 'j') {
									$this->prev_pageid = $this->getParentId($s_cwd.'/'.$s_file);
									$this->join_cwd = $s_cwd.'/'.$s_file;
									$this->is_join = true;
								}
							}


							if($s_status != 'r') { // dont go down here if it is force ignore
								$this->dirLoop($cwd.'/'.$file, $cwd);     // recursively open this directory 
							}
					
						} else {  // this is a file, file

							// s_something  - we are dealing with something that has the status stripped of the front
							// something    - we are dealing with something that still has the status attached
							// we only want to use the path with the status attached when we want to do something with
							// the directory structure

							list($s_cwd, $s_status) = $this->stripStatus($cwd, 'd'); //create striped status and file

							// current pageid for the directory this file exists
							$curr_pageid = $this->getPageId($s_cwd); 
							$this->RESTRICTION = $this->getPageRestriction($curr_pageid);

							// check to see if this file is the standard content file
							if ($file == $this->STD_CONTENT_FILE) {
							
								// make this page pull the content from the pageid in the file
								$pageid = $this->getPageIdFromFile($s_cwd.'/'.$file);
								if ($pageid) {
									$this->createPullContent($curr_pageid, $pageid);
									$this->clearCache($curr_pageid, 'page');
								}
							
							// else see if we have this already
							} elseif (!$this->fileExists($file, $s_cwd)) {
								$this->createFile($file, $cwd.'/'.$file, $curr_pageid); // create a new file entry
							} else { 
								 // if this does exist, make sure that we have the most recent one 
								if (!$this->checkFileChecksums($cwd.'/'.$file, $file, $curr_pageid)) {
									$this->copyFile($file, $cwd.'/'.$file, $curr_pageid);
									// clear the cache so that the file size changes appear in the summary
									$this->clearCache($this->files[$s_cwd.'/'.$file]['fileid'], 'file');
									$this->clearCache($curr_pageid, 'page');
								}
							} // end else
						} // end if

					} // end if
				} // end while
			} // end if
		}
		closedir($dh);
		chdir($startdir); // go back to the start dir
	
	}//end dirLoop()


	/**
	* returns true if the page exists or false otherwise
	*
	* @param string		$dir	the name of the directory
	* @param string		$cwd	the absolute parent directory where this directory exists
	*
	* @access public
	* @return bool
	*/
	function pageExists($dir = '', $cwd = '')
	{
		// strip the status off so we can still check if
		// it exists but the status has changed
				
		if ($this->pages[$cwd.'/'.$dir]) {
		 
			// create an entry to signify that this page
			// so that later on we can remove any pages from mysource
			// that nolonger exist
			
			$this->pages[$cwd.'/'.$dir]['exists'] = true;
			return true;
		} 
		return false;

	}//end pageExists()


	/**
	* changes a page's status to archive
	*
	* @param string $name the name of the directory
	* @param string $cwd the path to directory
	*
	* @access public
	* @return bool
	*/
	function archivePage($name, $cwd)
	{
		$site = &$this->getSite();
		$db = &$this->getDb();
		// we have to remove the underscore to get the pageid
		
		$pageid = $this->getPageId($cwd); 
		
		if (!$pageid) {
			// page doesn't exist yet so create it () ie. this is the first time run !?
			$parentid = $this->getParentId($cwd);
			$pageid = $this->createPage($name, $parentid ,$cwd , 'A');
			if (!$parentid) {
				$parentid = 0;
			}
			$db->insert('INSERT INTO '.$this->TABLENAME.' VALUES(0, '.$pageid.', '.$parentid.', "'.$name.'", "'.$cwd.'", "a")');
		} else {
			$db->update('UPDATE '.$this->TABLENAME.' SET status="a" WHERE pageid='.$pageid);
		}
	
		$page = &$site->get_page($pageid);
		
		// change the status
		if (!$page->add_status('A')) {
			$this->logError('could not change the status of the page "'.$pageid.'"', __LINE__);
			return false;
		}
		$this->clearCache($pageid, 'page');
		return true;

	}//end archivePage()
	

	/**
	* changes a page's status to live
	*
	* @param string $name the name of the directory
	* @param string $cwd the path to directory
	*
	* @access public
	* @return bool
	*/
	function unarchivePage($cwd)
	{
		$site   = &$this->getSite();
		$pageid = $this->getPageId($cwd); 
		$page   = &$site->get_page($pageid);
		
		if (!$page->add_status($this->STATUS)) {
			$this->logError('could not change status of pageid '.$pageid, __LINE__);
			return false;
		}
		
		$db = &$this->getDb();
		$db->update('UPDATE '.$this->TABLENAME.' set status="l" WHERE pageid='.$pageid); // make live
		$this->clearCache($pageid, 'page');
		return true;

	}//end unarchivePage()


	/**
	* gets a pageid from a file for use in pull content pages
	*
	* @param string $file the path to the file to retrieve the pageid
	*
	* @access public
	* @return integer pageid
	*/
	function getPageIdFromFile($file)
	{
		if (!$fp = @fopen($file, 'r')) {
			return false;
		}
		if (!$pageid = trim(@fread($fp, filesize($file)))) {
			// clean up
			fclose($fp);
			return false;
		}
		fclose($fp);
		return $pageid;
	
	}//end getPageIdFromFile()


	/**
	* changes a page to a pullcontent and assigns another page as the place 
	* where to pull content from 
	*
	* @param integer 	$pageid					the pageid of the page to become pullcontent
	* @param integer	$pull_content_pageid	the page where to pull content
	*
	* @access public
	* @return bool
	*/
	function createPullContent($pageid, $pull_content_pageid)
	{
		$site = &$this->getSite();
		if (!$page = &$site->get_page($pageid)) {
			return false;
		}
		// if we can't find the page, just return false
		$db = &$this->getDb();
		if (!$db->single_column('SELECT pageid FROM page WHERE pageid='.$pageid)) return false;
		$page->set_template('pullcontent', true);
		$page = &$page->get_template();
		$pull_content_page = &$site->get_page($pull_content_pageid);
		
		if (!$pull_content_page) {
			$this->logError("pull content page does not exist (".$pull_content_pageid.")", __LINE__);
			return false;
		}

		$page->set_content_pageid($pull_content_pageid);
		return true;

	}//end createPullContent()


	/**
	* function to get all the known pages in the system
	*
	* @access public
	* @return bool
	*/
	function getCurrentPagesAndFiles()
	{
		$db = &$this->getDB();
		$res = Array();

		// get all the pages that are currently known
		
		if (!$res = $db->associative_array('SELECT * FROM '.$this->TABLENAME)) {
			$this->logError('could not get the local page info', __LINE__);
			return false;
		}
		
		// create a store of pages

		foreach ($res as $r) {
			$path = stripslashes($r['path']);
			$this->pages[$path]['parent'] = $r['parentid'];
			$this->pages[$path]['pageid'] = $r['pageid'];
			$this->pages[$path]['name']   = stripslashes($r['name']);
			$this->pages[$path]['status'] = $r['status'];
		}

		if (!$res = $db->associative_array('SELECT * FROM '.$this->TABLENAME.'_files')) {
			$this->logError('could not get the local file info', __LINE__);
			return false;
		}
		foreach ($res as $r) {
			$path = stripslashes($r['path']);
			$this->files[$path]['fileid'] = $r['fileid'];
			$this->files[$path]['pageid'] = $r['pageid'];
			$this->files[$path]['name']   = stripslashes($r['name']);
		}

		return true;
	
	}//end getCurrentPagesAndFiles()


	/**
	* creates a new page in the mysource system
	*
	* @param string		$name		the name of the new page
	* @param integer	$parentid	the parentid of the page
	*
	* @access public
	* @return integer pageid
	*/
	function createPage($name = '', $parentid = '', $abs_path = '', $status, $template = '', $local_insert = true)
	{
		if (!$template) { // default to the global template 
			$template = $this->TEMPLATE;
		}

		$site = &$this->getSite();
		$db   = &$this->getDb();
		$info = Array();
	
		if (!$parentid) { 
			$parentid = $this->parameters['ROOT_PAGEID'];
			//$parentid = 0;
		}
		
		$page_status = (strtolower($status) == 'a') ? 'A' : $this->STATUS;
		$new_page =& new Page();

		$info = $new_page->create($name, $template, $site->id, $parentid, $page_status, false);
		
		$info[] = $new_page->id;
		// create a mysource page
		
		$scope = ($status == 'p') ? 1 : 0;


		// if this dir is under the root directory, store 0 as parentid
		if ($this->is_parent) { // this only happens once
			$db->update("UPDATE page SET designid=".$this->parameters['DESIGN'].", PUBLIC=".$scope." WHERE pageid=".$info[2]);
			$this->is_parent = false;
		} else {
			$new_page->set_public($scope);
		}

		// create an entry for this page
		if ($local_insert) {

			$this->pages[$abs_path]['name']   = $name;
			$this->pages[$abs_path]['parent'] = $parentid;
			$this->pages[$abs_path]['pageid'] = $info[2];
		
			$db->insert('INSERT INTO '.$this->TABLENAME.' VALUES(0, '.$info[2].', '.$parentid.', "'.addslashes($name).'", "'.addslashes($abs_path).'", "'.$status.'")');
		}

		// return the id of the new page
		return $info[2];

	}//end createPage()
	

	/**
	* strips the status from the front of the absolute directory or relative
	*
	* @param string		$path	the path to check
	* @param string		$type	the type to check
	*
	* @access public
	* @return array
	*/
	function stripStatus($path = '', $type)
	{
		$old_path = $path;
		switch ($type) {
			case 'd': // directory
				
				if (preg_match('/\/?(.)_.+$/', $path, $matches)) {
					$status = $matches[1];
					$path   = preg_replace("|/[a-zA-z0-9]_([^/]+)|", "/$1", $path);
				}
		
			break;
			case 'f': // non-absolute dir
				if (preg_match('|^(.)_(.+)$|', trim($path), $matches)) {
					$status = $matches[1];
					$path   = $matches[2];
				}
			
			break;
		}
		if (!$status) {
			$status = 'p';
		}
		if (!$path) {
			$path = $old_path;
		}
		return Array($path, $status);

	}//end stripStatus()


	/**
	* gets the status of a director from the first character on the name
	*
	* @param string		$name	the name of the directory
	* @param string		$path	the path to the directory
	*
	* @access public 
	* @return array
	*/
	function getDirStatus($name, $path)
	{
		$dir = $path.'/'.$name;
		list($dir, $status)  = $this->stripStatus($dir, 'd');
		list($path, $status) = $this->stripStatus($path, 'd');
		list($name, $status) = $this->stripStatus($name, 'f');
		$previous_status = $this->getPreviousDirStatus($dir);
		if ($status != $previous_status && $previous_status) {
			$this->changeExistingStatus($name, $dir, $status, $previous_status);
		}
		return Array($name, $path, $status);

	}//end getDirStatus()
	

	/**
	* changes the status of an  existsing page in the system
	*
	* @param string $name the name of the dir
	* @param string $dir the dir path the this dir
	* @param string $previous_status the previous status of the dir
	*
	* @access public
	* @return 
	*/
	function changeExistingStatus($name, $dir, $status, $previous_status)
	{
			switch ($status) {
				case 'l': // live
					
					switch($previous_status) {
						case 'a' :
							$this->unarchivePage($dir);
						break;
					}

				break;

				case 'p': // live / public
					
					switch($previous_status) {
						case 'a' :
							$this->unarchivePage($dir);
						break;
					}

				case 'a': // archived
					 $this->archivePage($name, $dir);
				break;
				case 'i': // ignored
				break;
				case 'r': // recursive ignore
				break;
				case 'j': // ignore join
				break;
			}

	}//end changeExistingStatus()


	/**
	* gets the previous status of a directorty
	*
	* @param string $cwd the directory to check
	*
	* @access public
	* @return string status
	*/
	function getPreviousDirStatus($cwd)
	{
		return (isset($this->pages[$cwd]['status']) ? $this->pages[$cwd]['status'] : false);

	}//end getPreviousDirStatus()


	/**
	* creates some pages if this dir is something special like a WC
	*
	* @param string $cwd the path to this dir
	* @param string $name the name of this directory
	* @param integer $parentid the parentid of this page
	*
	* @access public
	* @return bool 
	*/
	function checkDirType($cwd, $name, $parentid)
	{
		// if this directory is a WC (Working committee) then we want to create some extra pages
		if (preg_match('/(.+)_W([C|G])(\d+)\s+(.+)/i', $name, $matches)) {
			
			// create an Iteractive Message Board
			$board_name = $matches[1] .' W'.$matches[2].$matches[3] . ' - ' . $matches[4];
			$imbid = $this->createPage($board_name, $parentid, $cwd . '/' . $name, 'l', 'squiz_imb', false); 
			
			if ($this->parameters['WC_IMBID']) {
				$this->dupeIMB($this->parameters['WC_IMBID'], $imbid);
			}

			// create a calendar
			$cal_name = $matches[1] .' W'.$matches[2].$matches[3] . ' - ' . $matches[4]. ' Calendar';
			$calid = $this->createPage($cal_name, $parentid, $cwd . '/' . $name, 'l', 'calendar_2', false);

			if ($this->parameters['WC_CALID']) {
				$this->dupeCal($this->parameters['WC_CALID'], $calid);
			}

			// while we are here we will replace the content for this page
			$copy = $this->generatePageFromTemplate($cwd, $name);
			if (!$copy) return false;
			$page = &$this->getPage($parentid);
			$template = &$page->get_template();
			$template->parameters['bodycopy'] = serialize($copy);
			$template->parameters['title'] = '';
			$template->save_parameters();
		
			unset($page);
			unset($template);
		
			return true;
		}//end if

		return false;

	}//end checkDirType()


	/**
	* generates some content for each of the working committees based 
	* on the content in an assigned template
	* 
	* @param string $cwd the current working directory
	* @param string $name the name of the folder
	*
	* @access public
	* @return boolean
	*/
	function generatePageFromTemplate($cwd, $name)
	{
		if (!trim($this->parameters['WC_PAGEID'])) return false;
		if (is_null($this->wc_template)) {
			$page = &$this->getPage($this->parameters['WC_PAGEID']);
			if (!$page->id) {
				$this->logError('Working committee template does not exist('.$this->parameters['WC_PAGEID'].')', __LINE__);
				return false;
			}
			$template = $page->get_template();
			$bodycopy = new BodyCopy($template->parameters['bodycopy'], 'bodycopy');
			
			$this->wc_template = $bodycopy;
		}
		if (is_null($this->wc_template)) {
			$this->logError('could not get the working committee template', __LINE__);
			return false;
		}
		$keywords = $this->getKeywordReplacements($cwd, $name);
		$copy = $this->wc_template;
		$copy->add_replace_keywords($keywords);
		
		return $copy;

	}//end generatePageFromTemplate()


	/**
	* creates some replacements for keywords that exist in the template for working committee
	*
	* @param string $cwd the current working directory (status stripped)
	* @param string $name the name of the dir
	*
	* @access public
	* @return boolean
	*/
	function getKeywordReplacements($cwd, $name)
	{
		$keywords = Array();

		$root_dir = str_replace('/','\/', $this->parameters['DIR']);
		// get the reference panel
		# if (preg_match('|'.$root_dir.'([^/]+)/.+|', $cwd, $matches)) {
		# $keywords['rp_long'] = $matches[1];
		#}
		// get the working committee name and number
		if (preg_match('/(.+)_(W[C|G]\d+)\s+(.+)/i', $name, $matches)) {
			$keywords['wc_name']    = $matches[3];
			$keywords['wc_number']  = $matches[2];
			$keywords['rp_short']   = $matches[1];
			$keywords['wc_short']   = $matches[1].'_'.$matches[2];
			$wcnumber = $matches[2];

			if (!empty($wcnumber)) {
				$letter = $wcnumber{1};
				$keywords['group_type'] = 'Working '.((strtolower($letter) == 'c') ? 'Committee' : 'Group');
			}
		}
		
		// we will assume that the status of the terms of reference is 'live'
		$dir = '';

		foreach ($this->A_STATUS as $status) {
			if (is_dir($this->current_dir.'/'.$this->current_file.'/'.$status.'_Terms of Reference')) {
				$dir = $this->current_dir.'/'.$this->current_file.'/'.$status.'_Terms of Reference';
				break;
			}
		}

		// if there is no dir, check to make sure that the dir does not have a status in front of it (live)
		if (!$dir) {
			if (is_dir($this->current_dir.'/'.$this->current_file.'/Terms of Reference')) {
				$dir = $this->current_dir.'/'.$this->current_file.'/Terms of Reference';
			}
		}

		$keywords['tor'] = '';
		// get the terms of reference from the text file 
		if (is_dir($dir)) {
			$dh = opendir($dir);
			$latest_file = '';
			$latest_time = 0;

			while (false !== ($file = readdir($dh))) {
				if (!is_dir($file)) {
					$pathinfo = pathinfo($file);
					if ($pathinfo['extension'] != 'txt') continue;
					$info = stat($file);
					if (!$latest_time || strcmp($info['mtime'], $latest_time) < 0) {
						$latest_file = $file;
						$latest_time = $info['mtime'];
					}
				}
			} // end while
			$keywords['tor'] = nl2br(implode('', file($dir.'/'.$latest_file)));
		
		} // end 

		return $keywords;
	
	}//end getKeywordReplacements()
	
	/**
	* Makes a copy of an IMB
	*
	* @param integer $dupid the id of the page to dupe
	* @param integer $pageid the id of the page to copy to
	*
	* @return boolean
	* @access private
	*/
	function dupeIMB($dupeid, $pageid)
	{
		$db = &$this->getDb();
		if (!$this->IMB_CONTENT) {
			$sql = 'SELECT * FROM xtra_page_template_squiz_imb where pageid='.$dupeid;
			$res = $db->associative_row($sql);
			if (empty($res)) return false;
			$tmp = unserialize($res['parameters']);
			$this->IMB_CONTENT = addslashes(serialize($tmp));
		}

		$sql = 'UPDATE xtra_page_template_squiz_imb SET parameters=\''.$this->IMB_CONTENT.'\' WHERE pageid='.$pageid;
		if (!$db->insert($sql)) return false;

		$this->clearCache($pageid, 'page');
		return true;

	}//end dupeIMB


	
	/**
	* Makes a copy of a Calendar
	*
	* @param integer $dupid the id of the page to dupe
	* @param integer $pageid the id of the page to copy to
	*
	* @return boolean
	* @access private
	*/
	function dupeCal($dupeid, $pageid) 
	{
		$db = &$this->getDb();
		if (!$this->CAL_CONTENT) {
			$sql = 'SELECT * FROM xtra_page_template_calendar_2 where pageid='.$dupeid;
			$res = $db->associative_row($sql);
			if (empty($res)) return false;
			$tmp = unserialize($res['parameters']);
			$this->CAL_CONTENT = addslashes(serialize($tmp));
		}
		$sql = 'UPDATE xtra_page_template_calendar_2 SET parameters=\''.$this->CAL_CONTENT.'\' WHERE pageid='.$pageid;
		if (!$db->insert($sql)) return false;

		$this->clearCache($pageid, 'page');
		return true;

	}//end dupeCal()


	/**
	* checks to see if this file has been modified or not
	*
	* @param string		$abs_filename	the file to check (absolute)
	* @param string		$name			the name of the file
	* @param string		$pageid			the pageid where the file exists
	*
	* @access public
	* @return bool
	*/
	function checkFileChecksums($abs_filename, $name, $pageid) 
	{
		if ((filesize($abs_filename) == 0) && (filesize($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid.'/'.$name) == 0)) {
			return true;
		}
		return (md5_file($abs_filename) == md5_file($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid.'/'.$name));
	
	}//checkFileChecksums()
	

	/**
	* returns true if the file exists, false otherwise
	* 
	* @param string		$filename	the relative filename of the file
	* @param integer	$pageid		the pageid where the file is attached
	*
	* @access public
	* @return bool
	*/
	function fileExists($file, $cwd)
	{
		
		if ($this->files[$cwd.'/'.$file]['fileid']) {
			$this->files[$cwd.'/'.$file]['exists'] = 1;
			return true;
		}
		return false;

	}//end fileExists()


	/**
	* returns the pageid based on the directory
	*
	* @param string $cwd the directroy that the pageid is required
	* 
	* @access public
	* @return integer pageid
	*/
	function getPageId($cwd) 
	{
		if ($this->pages[$cwd]['pageid']) {
			return $this->pages[$cwd]['pageid'];
		}
		return false;

	}//end getPageId()


	/**
	* function to copy a file from where it exists into the mysource file system
	* 
	* @param string		$name		the name of the file
	* @param string		$abs_path	the absolute path to the file
	* @param integer	$pageid		the pageid to attach the file to
	*
	* @access public
	* @return bool
	*/
	function copyFile($name, $abs_path, $pageid)
	{
		if ($name == '') return false;
		if (!$pageid) return false;

		// delete the file if it already exists 
		if (file_exists($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid.'/'.$name)) {
			if(!unlink($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid.'/'.$name)) {
				$this->logError('could not remove file '.$name, __LINE__);
			}
		}
		if (!file_exists($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid)) {
			mkdir($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid);
			chmod($this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid, 0775);
		}
		
		// copy the file from where it is to where it should be in the mysource system
		if (!copy($abs_path, $this->DATA_PATH.'/'.$this->RESTRICTION.'/page/'.$pageid.'/'.$name)) {
			$this->logError('could not copy file '.$name, __LINE__);
			return false;
		}
		return true;

	}//end copyFile()
	

	/**
	* function to create a new file object and attach it to a page
	*
	* @param string		$name		the name of the file
	* @param string		$abs_path	the absolute path to the file
	* @param integer	$pageid		the pageid to attach the file to
	*
	* @access public
	* @return integer fileid
	*/
	function createFile($name, $abs_path, $pageid)
	{
		if (!$pageid) return false;
		

		// copy the file over
		if(!$this->copyFile($name, $abs_path, $pageid)) {
			return false;
		}

		$db = &$this->getDb();
		// get the next orderno for this file
		if (!$this->fileid) {
			$this->fileid = $db->single_element("SELECT max(orderno) FROM file WHERE pageid='".$pageid."'");
		}
		// increment the fileid
		$this->fileid++;

		$description = $this->getFileDescription($name);
		
		if (!$name || !$this->fileid || !$pageid) return false;


		// check to see if there is an entry for this file already
		
		$exists = $db->single_column('SELECT pageid from file where pageid='.$pageid.' and filename="'.addslashes($name).'"');

		if ($exists) {
			$this->logError('file already exists >'.$name, __LINE__);
			return false;
		}
		// create an entry in the database so the file exists in the mysource system
		$fileid = $db->insert("INSERT INTO file (pageid,filename,description,keywords,visible,log_hits,orderno) VALUES ('" . $pageid . "','".addslashes($name)."','". addslashes($description) ."','','Y','0','" . $this->fileid . "')");
		
		
		// store some things for future reference
		$this->files[$abs_path]['fileid'] = $fileid;
		$this->files[$abs_path]['name']   = $name;
		$this->files[$abs_path]['pageid'] = $pageid;

		list($s_path, $s_status) = $this->stripStatus($abs_path, 'd');

		if (!$fileid || !$pageid) {
			$this->logError('there was no fileid or pageid for >'.$name, __LINE__);
			return false;
		}

		$db->insert("INSERT INTO " . $this->TABLENAME . "_files VALUES(0, " . $fileid . ", " . $pageid . ", '" . addslashes($name) . "' , '" . addslashes($s_path) . "')");
		
		$this->clearCache($fileid, 'file');
		$this->clearCache($pageid, 'page');

		return $fileid;
	
	}//end createFile()
	

	/**
	* gets a description of the file from the conf file for the description in the file object
	*
	* @param string name the name of the file
	*
	* @access public
	* @return string description
	*/
	function getFileDescription($name)
	{
		$desc = file($this->CONF_PATH.'/'. $this->CONF_FILE);
		foreach ($desc as $d) {
			list($pat, $desc) = explode(',', $d);
			$pat  = trim($pat);
			$desc = trim($desc);
			if (preg_match("/$pat/", $name)) {
				return $desc;
			}
		}
		return '';

	}//end getFileDescription()


	/**
	* returns the the absolute parent directory of a directory and strips out the status of the directory
	*
	* @param string $dir the directory in which to find the parent
	*
	* @access public
	* @return string directory
	*/
	function getAbsParent($dir)
	{	
		preg_match('/(.+)\//', $dir, $matches);
		return $matches[1];

	}// getAbsParent()


	/**
	* returns the id of the parent directory
	*
	* @param string $cwd the directory which to find the parent of
	*
	* @access public
	* @return integer parentid
	*/
	function getParentId($cwd) 
	{
		// check to see if we have the parent of this page
		if (!$this->pages[$cwd]['parent']) { 
			$parent_abspath = $this->getAbsParent($cwd);
			$this->pages[$cwd]['parent'] = $this->pages[$parent_abspath]['pageid'];
		}
		return $this->pages[$cwd]['parent'];
		
	}//end getParentId()


	/**
	* function to clear the cache of a page
	*
	* @param integer id the id of the page
	*
	* @access public
	* @return mixed
	*/
	function clearCache($id = '', $type) 
	{
		if ($id) {
			global $CACHE;
			$msg = $CACHE->clear($id, $type);
		}
		return true;

	}//end clearCache()


	/**
	* returns db object reference
	*
	* @access public
	* @return &object db
	*/
	function &getDb() 
	{
		if (is_null($this->DB)) {
			$web = &$this->getWeb();
			$this->db = &$web->get_db();
		}
		return $this->db;

	}//end getDb() 


	/**
	* returns web object reference
	*
	* @access public
	* @return &object web
	*/
	function &getWeb()
	{
		if (is_null($this->web)) {
			$this->web = &get_web_system();
		}
		return $this->web;
	
	}//end getWeb()


	/**
	* returns site object reference
	*
	* @access public
	* @return &object site
	*/
	function &getSite()
	{	
		if (is_null($this->site)) {
			$web = &$this->getWeb();
			$this->site = &$web->get_site($this->parameters['SITEID']);
		}
		return $this->site;

	}//end getSite()


	/**
	* returns a reference to a page based on its id
	*
	* @param integer	$pageid		the id of the wanted page
	*
	* @access public
	* @return &object page 
	*/
	function &getPage($pageid)
	{
		$site = &$this->getSite();
		$page = &$site->get_page($pageid);
		if (!$page->id) return false;
		return $page;	
	
	}//end getPage()


	/**
	* writes the page index to cache
	*
	* @access public
	* @return booean
	*/
	function writePageIndexCache() 
	{
		$site = &$this->getSite();
		$site->write_page_index_to_cache();
		return true;

	}//end writePageIndexCache()


	/**
	* Returns the page restriction of a given pageid
	*
	* @param integer $pageid the pageid of the wanted page
	*
	* @access public
	* @return string
	*/
	function getPageRestriction($pageid='')
	{
		if (!$pageid) return false;
		$page = &$this->getPage($pageid);
		return ($page->effective_unrestricted()) ? 'unrestricted' : 'restricted';
	
	}//end getPageRestriction

}//end class ACIF_Project_Management
?>