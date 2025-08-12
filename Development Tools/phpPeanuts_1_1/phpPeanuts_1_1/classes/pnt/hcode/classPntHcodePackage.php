<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntHcodeObject', 'pnt/hcode');

/** Objects of this class represent classFolders. 
* in order to fit into our CMS hcode classes should 
* support the necessary interface from Menu or Pagina.
* @package pnt/hcode
*/
class PntHCodePackage extends PntHcodeObject {

	var $fileNames;
	var $dirNames;
	var $packageKey = null;
	
	//static
	function getInstance($key)
	{
		return new HcodePackage($key);
	}
	
	//static
	// must be empty or have slash at the end
	function getSourceRoot() {
		return '../classes/';
	}

	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();
		$this->addFieldProp('parentKey', 'string', false, null, null, 0, 80, false);

		$this->addDerivedProp('id', 'string');
		$this->addDerivedProp('parent', 'HcodePackage');
		$this->addDerivedProp('children', 'HcodePackage');
		$this->addDerivedProp('classes', 'HcodeClass');

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}

	function getBeschrijving()
	{
		return "classFolder";
	}
	
	function getFileName()
	{
		return $this->titel;
	}
	
	/** Return subpackages
	*/
	function &getChildren()
	{
		if ($this->dirNames === null)
			$this->initFromSource();
		//as dirNames are sorted by asort the keys of the result will
		//probably differ from the keys of the corresponding dirNames
		$result = array();
		$sep = $this->getKeySeparator();
		reset($this->dirNames);
		while (list($key, $dirName) = each($this->dirNames)) {
			if ($this->getKey())
				$childKey = $this->getKey(). $sep. $dirName;
			else
				$childKey = $dirName;
			$child =& new HcodePackage($childKey);
			$child->fsExists = true;
			$result[$dirName] =& $child;
		}
		return $result;
	}
	
	function &getClasses()
	{
		if ($this->fileNames === null)
			$this->initFromSource();

		//as fileNames are sorted by asort the keys of the result will
		//probably differ from the keys of the corresponding fileNames
		includeClass('HcodeClass', 'beheer');
		$result = array();
		reset($this->fileNames);
		while (list($key, $fileName) = each($this->fileNames)) {
			$classNamePos = (substr($fileName, 0, 4) == 'test') ? 4 : 5;
			$className = substr($fileName, $classNamePos, strLen($fileName) - $classNamePos - 4);
			$classKey = $this->getKey(). '.'. $className;
			$class =& new HcodeClass($classKey);
			$class->fsExists = true;
			$result[$className] =& $class;
		}
		return $result;
	}
	
	function &getParent()
	{
		$pkey = $this->getParentKey();
		if ($pkey)
			return new HcodePackage($pkey);
		else
			return null;
	}
	
	function getParentKey()
	{
		return $this->packageKey;
	}
	
	function setParentKey($value)
	{
		$this->packageKey = $value;
	}
	
	function getId()
	{
		return $this->getKey();
	}

	function isGroep()
	{
		return true;
	}

	function &getMenu1()
	{
		if ($this->getKey())
			return $this;
		else 
			return new Menu(Menu::getHcodePackagesMenuId());
	}
	
	function getParentsArray()
	{
		includeClass('Menu', 'beheer');
		
		$packagesMenu =& new Menu(Menu::getHcodePackagesMenuId());
		$menuIds = array_reverse($packagesMenu->getParentsArray());
		$menuIds[] = Menu::getHcodePackagesMenuId();

		$pidArr =& $this->getPidArray();
		while (list($key, $title) = each($pidArr)) {
			$menuIds[] = implode('.', array_slice($pidArr, 0, $key + 1));
		}

		return array_reverse($menuIds);
	}
	
	/** maybe we can show a comment from a file in the package dir, 
	* or the directory list with file details
	*/
	function getTekst()
	{
		if ($this->checkFsExists())
			return "package ". $this->getId();
		else 
			return "DELETED package ". $this->getId();
	}

	function checkFsExists()
	{
		if ($this->fsExists !== null)
			return $this->fsExists;
	
		$this->fsExists = is_dir(realpath($this->getFilePath()));
		return $this->fsExists;
	}
	
	function initFromSource()
	{
		$base = realpath($this->getFilePath());
	    $this->dirNames = array();
	    $this->fileNames = array();
	    if($this->checkFsExists()) {
	       $dh = opendir($base);
	       while ($dh !== false && false !== ($entry = readdir($dh))) {
	       		$path = $base ."/". $entry;
	       		
	           	if (!in_array($entry, array('.', '..', 'CVS'))) {
	           		$starter = substr($entry, 0, 4);
		           	if (is_dir($path)) {
		                $this->dirNames[] = $entry;
		            } elseif(is_file($path) && ($starter == 'clas' || $starter == 'test') ) {
		                $this->fileNames[] = $entry;
		            }
		    	}
	        }
	        closedir($dh);
	        asort($this->dirNames);
	        asort($this->fileNames);
	    }
	 }
}
?>