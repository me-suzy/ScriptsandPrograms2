<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

require_once('../classes/pnt/unit/classPntTestNotifier.php');
//now generalFunctions has been included too
includeClass('PntPregFilterExpression', 'pnt/unit/web');

/**
 * The html based user interface outputs test results as they occur.
 * It therefore will still output what happens before a fatal error occurs.
 * This is especially usefull if the cause of the error was already 
 * reported by an assertion.
 *
 * To produce the actual user interface this class is instatiated 
 * and methods are called by a view script during the output of the html
 * 
 * In order to find the TestCase classes they either need to follow 
 * a naming convention like phpPeanuts and PEAR, 
 * (see function _getClassNameFromRelativeFilePath)
 * or the included file should return the names
 * of the testcase classes in the file (like example MathTest.php ),
 * separated by spaces
 *
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */

class PntUnitPage {

	/** PntUnitScriptPart $scriptPart Lets the user build a testcase include script */
	var $scriptPart;
	/** string $format printf format for test event row */
	var $format = "<TR VALIGN='TOP' class=pntUnit%s><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD><TD>%s</TD></TR>";
	/** string $rootDir Subfolders of this folder are searched for testcase files
	* also used by @see getPearClassName */
	var $rootDir; 
	/** if set, only filenames equal to the ones the protectionfilter matches for are alowed */
	var $protectionFilter;

	function PntUnitPage() {
		$this->initialize();
	}

	function initialize() {
	}
	
	function setDir($dir)
	{
		$part =& $this->getScriptPart();
		$part->setDir($dir);
	}
	
	function setRootDir($dir)
	{
		$this->rootDir = $dir;
		$part =& $this->getScriptPart();
		$part->setRootDir($dir);
	}
	
	function &getFileFilter() 
	{
		$part =& $this->getScriptPart();
		return $part->getFileFilter();
	}
	
	function initForHandleRequest() 
	{
//var_dump($this);
	}
	
	function handleRequest() 
	{
		$this->initForHandleRequest();
		include('skinPntUnit.php');
	}
    
    function &getScriptPart()
    {
    	if (!$this->scriptPart) {
	    	includeClass('PntUnitScriptPart', 'pnt/unit/web');
	    	$this->scriptPart =& new PntUnitScriptPart($this);
		}
   		return $this->scriptPart;
    }
    		
    function printScriptPart()
    {
    	$part =& $this->getScriptPart();
    	$part->printBody();
    }
    
    function &getTestRunPart()
    {
    	if (!isSet($this->testRunPart)) {
	    	includeClass('PntUnitTestRunPart', 'pnt/unit/web');
	    	$this->testRunPart =& new PntUnitTestRunPart(
	    		$this,
	    		$this->format);
		}
   		return $this->testRunPart;
    }
	
	function printTestRunPart() 
	{
    	$part =& $this->getTestRunPart();
   			if (isSet($_REQUEST["!RunScript"]) )
				$toRun =& $this->getTestClassAndFileNames();
			else
				$toRun = null;
    	$part->setClassAndFileNames($toRun);
    	$part->printBody();
	}

	function getTestClassAndFileNames()
	{
		$scriptPart = $this->getScriptPart();
		$fileNames = $scriptPart->getFileNamesFromScript();
		if (empty($fileNames)) return;
		
		$classAndFileNames = array();
		forEach($fileNames as $fileName) {
			
			if (!$scriptPart->protectionCheck($fileName, $this->protectionFilter)) {
				print "Access denied: $fileName";
				return array();
			}
			// may be included before
			if (isSet($includeResults[$fileName]) ) {
				$includeResult = $includeResults[$fileName];
			} else {
				$includeResult = include($fileName);
				$includeResults[$fileName] = $includeResult;
			}

			//make array of class names			
			if ( is_string($includeResult) )
				$fileClassNames = explode(' ', $includeResult);
			else
				$fileClassNames = array($this->_getClassNameFromRelativeFilePath($fileName));
				
			forEach($fileClassNames as $className) {
				$classAndFileNames[] = array($className, $fileName);
			}
		}
		return $classAndFileNames;		
	}

    function _getClassNameFromRelativeFilePath($filePath) 
    {
    	// override this for different class naming convention
    	return $this->getPeanutsTestClassName($filePath);
    }
    
    function getPeanutsTestClassName($filePath) 
    {
    	return subStr(basename($filePath, '.php'), 4);
	}	
    
    function getPearClassName($filePath) {
    	
		$className = str_replace('/','_',substr(str_replace($this->rootDir,'',$filePath),0));
        $className = basename($className,'.php');   // remove php-extension
		return $className;
	}
	
}
?>