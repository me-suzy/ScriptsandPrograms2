<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntDbObject', 'pnt/db');

/** Abstract superclass of objects that represent source code.
* It is called hypercode because method calls are represented by hyperlinks.
*  
* in order to fit into our CMS hcode classes should 
* support the necessary interface from Menu or Pagina.
* @package pnt/hcode
*/
class PntHCodeObject extends PntDbObject {

	var $titel = null;
	var $fsExists = null;
	var $parentKey = null;

	function PntHcodeObject($key='')
	{
		$this->PntDbObject();
		$this->setKey($key);
	}
	
	//static	
	function getFileSeparator()
	{
		return '/';
	}
	
	//static
	function getKeySeparator()
	{
		return '.';
	}

	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();
		$this->addFieldProp('titel', 'string', false, null, null, 1, 100);

		$this->addDerivedProp('tekst', 'string');
		$this->addDerivedProp('key', 'string', false, null, null, 1, 280);
		$this->addDerivedProp('filePath', 'string');
		$this->addDerivedProp('beschrijving', 'string');
		$this->addDerivedProp('kernwoorden', 'string');
		$this->addDerivedProp('objectId', 'string');

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}

	/** Returns the filePath for the supplied fileName. 
	* If no fileName is supplied, $this getFileName() us used.
	*/
	function getFilePath($fileName=null)
	{
		includeClass('HcodePackage', 'beheer');
		$parentFilePath = implode($this->getFileSeparator(), $this->getPidArray());
		if ($fileName === null) $fileName = $this->getFileName();
		if ($parentFilePath)
			$path= $parentFilePath . $this->getFileSeparator() . $fileName;
		else 
			$path= $fileName;
		return HcodePackage::getSourceRoot().$path;
	}
	
	function getFileName()
	{
		$this->subclassResponsability();
	}
	
	function getTitel()
	{
		if ($this->checkFsExists())
			return "$this->titel";
		else 
			return "[$this->titel]";
	}

	/** Return subpackages
	*/
	function &getChildren()
	{
		return array();
	}
	
	//implementation only feasable for HcodePackage and HcodeClass
	function getKey()
	{
		if ($this->packageKey)
			return $this->packageKey. $this->getKeySeparator(). $this->titel; 
		else
			return "$this->titel";
	}
	
	//implementation only feasable for HcodePackage and HcodeClass
	function setKey($value)
	{
		$sep = $this->getKeySeparator();
		$i = strRPos($value, $sep);
		if ($i !== false) {
			$this->packageKey = subStr($value, 0, $i);
			$this->titel = subStr($value, $i + 1);
		} else {
			$this->titel = "$value"; //workaround for reference anomalies
		}
	}

	function getPidArray()
	{
		if ($this->packageKey)
			return explode('.', $this->packageKey);
		else
			return array();
	}
	
	function getPackageKey()
	{
		return $this->packageKey;
	}
	
	function getParentKey()
	{
		return $this->parentKey;
	}
	
	function setParentKey($value)
	{
		$this->parentKey = $value;
	}
	
	function &getParent()
	{
		return $this->subclassResponsability();
	}
	
	function setParent(&$value)
	{
		if ($value)
			$this->setParentKey($value->getKey());
		else
			$this->setParentKey(null);
			
		$this->parent =& $value;
	}

	//should return html hypercode
	function getTekst()
	{
		return $this->getSource();
	}

	function &getWhole()
	{
		return $this->getParent();
	}

	function &getChildObjects()
	{
		return $this->getChildren();
	}

	function &getObject()
	{
		return $this;
	}

	function isAfbeelding()
	{
		return false;
	}
	
	function getObjectClass()
	{
		return getOriginalClassName(get_class($this));
	}
	
	function getObjectId()
	{
		return $this->getKey();
	}
	
	function getBeschrijving()
	{
		return $this->getObjectClass();
	}
	
	function getLabel()
	{
		return $this->getKey();
	}
	
	function isGroep()
	{
		return false;
	}
	
	function getKernwoorden()
	{
		//not yet implemented
		return '';
	}
	
	function getParentsArray()
	{
		$parent =& $this->getParent();
		if (!$parent) //package overrides this
			return array();
			
		$parentIds = $parent->getParentsArray();
		$parentIds[] = $this->getParentKey;
		return parentIds;
	}
	
	function isHoofdMenu()
	{
		return false;
	}
	
	function getFilePiece($filePath, $length, $startPos=0)
	{
//print "<BR>".$this->getKey()." getFileContents($filePath, $length, $startPos)";
		$handle = fopen ($filePath, "r");
		fseek($handle, $startPos);
	    $data = fread($handle, $length);
		
		fclose ($handle);
		return $data;
	}

}
?>