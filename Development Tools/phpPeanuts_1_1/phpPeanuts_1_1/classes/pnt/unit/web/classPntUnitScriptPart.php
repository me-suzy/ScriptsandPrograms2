<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

	
/**
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */
class PntUnitScriptPart {

	/** page or part this part is a part of */
	var $whole;
	var $script;

	var $dir = '../classes/pnt/test/unit';
	var $rootDir = '../classes';
	var $fileFilter;
	var $fileFilterDelimiter = '|'; //TODO: find best string
	
	function PntUnitScriptPart(&$whole)
	{
		$this->whole =& $whole;
	}
	
	function printBody()
	{
		$this->initFileFilter();
		$this->initScript();

		include('skinScriptPart.php');
	}

    function getDir() 
    {
		if ( isSet($_REQUEST["Dir"]) ) 
			return $_REQUEST["Dir"];
			
		if ( isSet($this->dir) )
			return $this->dir; 
			
		return $this->rootDir;
    }
    
    function setDir($value)
    {
    	$this->dir = $value;
    }
    
    function setRootDir($value)
    {
    	$this->rootDir = $value;
    }
    
    function getScript() 
    {
    	return $this->script;
    }

	function initScript()
	{
		if ( isSet($_REQUEST["Script"]) )
			$this->script = $_REQUEST["Script"];

		if ( isSet($_REQUEST["!AddFiles"]) )
			$this->addDirFilesToScript();
	}

	function &getFileFilter() 
	{
		if (!$this->fileFilter)
			$this->setFileFilter( new PntPregFilterExpresson() );

		return $this->fileFilter;
	}
	
	function setFileFilter(&$aValue) {
		$this->fileFilter =& $aValue;
	}

	function initFileFilter()
	{
		$filter =& $this->getFileFilter();
		if ( isSet($_REQUEST["FileIncludePattern"]) )
			$filter->setIncludePattern($_REQUEST["FileIncludePattern"]);
		if ( isSet($_REQUEST["FileExcludePatterns"]) )
			$filter->setExcludePatterns(
				explode(
					$this->fileFilterDelimiter, 
					$_REQUEST["FileExcludePatterns"]
					)
				);
	}			


	function printFileIncludePattern() {
		$filter = $this->getFileFilter();
		print $filter->getIncludePattern();
	}

	function printFileExcludePatterns() {
		$filter = &$this->getFileFilter();
		print implode(
			$this->fileFilterDelimiter
			,$filter->getExcludePatterns()
			);
	}
	
	function addDirFilesToScript() 
	{
		if (!empty($this->script) )
			$this->script .= "\n";
			
		$this->script .= implode(
			"\n"
			, $this->getFiles(
				$this->getDir()
				,'.php'
				,array('.bak')
				)
			);
	}
    
    function getFiles($dir)
    {
        $files = array();
        if ($dp=opendir($dir)) {
            while (false!==($file=readdir($dp))) {
                $filename = $dir.'/'.$file;

                $filter = $this->getFileFilter(); 
                $match = $filter->evaluate($file);

                if (is_file($filename) && $match) {
                    $files[] = $filename;
                }
            }
            closedir($dp);
        }
        return $files;        
    }  
    
   	function getFileNamesFromScript() 
   	{
		// split on spaces, tabs and newlines
		$splitted = preg_split( "/[ \t\n\r]+/", $this->script );
		// trim and remove empty
		$names = array();
		forEach($splitted as $fileName) {
			$trimmed = trim($fileName);
			if ($trimmed) 
				$names[] = $trimmed;
		}
		return $names;
	}

	function printDirSelectOptions()
	{
		$dirs = array();
		$this->addDirsHoldingMatchingFiles($this->rootDir, $dirs);
		sort($dirs);
		$selected = $this->getDir();
		
		while ( list($key, $dir) = each($dirs) ) {
			print "<OPTION ";
			if ($dir == $selected) print "SELECTED ";
			print ">$dir</OPTION>\n";
		}
	}
	
	function addDirsHoldingMatchingFiles($dir, &$matching)
	{
		if(!is_dir($dir)) return;

		$filter =& $this->getFileFilter(); 
		$notYetAdded = true;
		$handle = opendir($dir);
		while (($entry = readdir($handle))  !== false) {
			if ($entry !== '.' && $entry !== '..') {
				$path = $dir ."/". $entry;
				if (is_dir($path)) {
	                $this->addDirsHoldingMatchingFiles($path, $matching);
				} elseif ($notYetAdded && is_file($path) && $filter->evaluate($path)) {
                	$matching[] = $dir;
                	$notYetAdded = false;
            	}
            }
		}		
        closedir($handle);
	}
	
	function protectionCheck($fileName, $protectionFilter)
	{
		if ($protectionFilter === null) return true;
		
		if (!isSet($this->alowedFiles)) {
			$this->alowedFiles = array();
			$this->addAllMatchingFilesAsKeys($this->rootDir, $this->alowedFiles, $protectionFilter);
		}

		return isSet($this->alowedFiles[$fileName]);
	}

	function addAllMatchingFilesAsKeys($dir, &$matching, &$filter)
	{
		if(!is_dir($dir)) return;

		$handle = opendir($dir);
		while (($entry = readdir($handle))  !== false) {
			if ($entry !== '.' && $entry !== '..') {
				$path = $dir ."/". $entry;
				if (is_dir($path)) {
	                $this->addAllMatchingFilesAsKeys($path, $matching, $filter);
				} elseif (is_file($path) && $filter->evaluate($path)) {
                	$matching[$path] = true;
            	}
            }
		}		
        closedir($handle);
	}

}
?>