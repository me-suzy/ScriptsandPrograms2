<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntHcodeObject', 'pnt/hcode');

/** Instances correspond to methods that reside in files.
* Database is used for caching and searching.
* Instances can be loaded from the database, or
* created from a key. If they are loaded from a 
* database, the persistent fields will all be initialized.
* The source property is not persistent, its content
* is allways retrieved from file.
* If created from a key, only the fields whose values
* are present in the key are initialized, right away, the others are 
* initialized when needed.
*
* In any case, if an array of instances is retrieved from a propery, 
* only instances whose parent really exists on the filessystem
* should be returned. If its parent does no longer exist, 
* it is deleted and so is the method instance.
*
* If source is retrieved, * the instance and its parent must 
* be validated and if it is outdated, 
* they must be updated, and the database too. 
* If the database is updated, call records are updated too. 
*
* in order to fit into our CMS hcode classes should 
* support the necessary interface from Menu or Page.
* @package pnt/hcode
*/
class PntHcodeMethod extends PntHcodeObject {

	var $parent;
	var $sourcePos = 0;
	var $sourceLength = 0;

	//static
	function getInstance($key)
	{
		return new HcodeMethod($key);
	}

	//static
	function getTableName()
	{
		return 'hcodemethod';
	}

	//static
	function getKeySeparator()
	{
		return '::';
	}

	//static - override if required
	function &getUiColumnPaths() {
		return array (
			'name' => 'titel'
			, 'class' => 'parent.titel'
			, 'classFolder' => 'parent.packageFilePath'
		);
	}

	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();
		$this->addFieldProp('parentId', 'number', false, 0, null, 0, '6,0');
		$this->addFieldProp('sourcePos', 'number', false, 0, 999999, 0, '6,0');
		$this->addFieldProp('sourceLength', 'number', false, 0, 64000, 0, '5,0');
		$this->addFieldProp('sourceHash', 'string', false, null, null, 32, 32);
		
		$this->addFieldProp('parentKey', 'string', false, null, null, null, 180, null, false);
		$this->addDerivedProp('parent', 'HcodeClass');
		
		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}
	
	function getKey()
	{
		$pKey = $this->getParentKey();
		if ($pKey)
			return $pKey. $this->getKeySeparator(). $this->titel; 
		else
			return $this->titel;
	}

	function setKey($value)
	{
		$sep = $this->getKeySeparator();
		$i = strPos($value, $sep);
		$this->parentKey = subStr($value, 0, $i);
		$this->titel = subStr($value, $i+ strLen($sep));
	}

	function getBeschrijving()
	{
		return "method source";
	}

	function getFilePath()
	{
		$cls = $this->getParent();
		if ($cls)
			return $cls->getFilePath();
		else
			return null;
	}

	function getFileName()
	{
		$cls = $this->getParent();
		if ($cls)
			return $cls->getFileName();
		else
			return null;
	}
	
	function &getParent()
	{
		if ($this->parent)
			return $this->parent;
			
		includeClass('HcodeClass', 'beheer');
		
		if ($this->parentKey) { //initialized from key
			$this->parent =& new HcodeClass($this->parentKey);
		} else { //initialized from data
			$clsDes =& PntClassDescriptor::getInstance('HcodeClass');
			$retrieved =& $clsDes->getPeanutsWith('id', $this->parentId);
			if (count($retrieved))
				$this->parent =& $retrieved[key($retrieved)];
			else 
				$this->parent = null;
		}
		
		return $this->parent;
	}
	
	function setParent(&$value)
	{
		
		if ($value) 
			$this->parentId = $value->get('id');
		else
			$this->parentId = null;
			
		parent::setParent($value);
	}

	function getParentKey()
	{
		if ($this->parentKey) 
			return $this->parentKey;
		
		//initialized from db
		if (!$this->id)
			return null;
		$parent =& $this->getParent();
		if (!$parent) return null;
		
		return $parent->getKey();
	}
	
	function getMenu1() 
	{
		$cls = $this->getParent();
		if ($cls)
			return $cls->getMenu1();
		else
			return null;
	}
	
	//returns ascii text including method comment
	function getSource()
	{
		if ($this->source) return $this->source;
		
		if (!$this->sourceLength) 
			$this->ensureUpdated();
		if ($this->source) return $this->source;
		
		if (!$this->checkFsExists())
			return "DELETED method: ". $this->getKey();

		$this->source = $this->getFilePiece(
			$this->getFilePath()
			, $this->sourceLength
			, $this->sourcePos);
		return $this->source;
	}

	/** Make sure this is up to date as well as its 
	* data in the database. 
	* @result boolean Wheather this was already up to date
	*/
	function ensureUpdated()
	{
		if (!$this->checkFsExists()) 
			return true; //checkFsExists has already deleted everything

		//parent has changed an updated all its methods data too
		return $this->initFromSource();
	}
	
	//! sourcePos must be set seperately
	function setSource(&$source)
	{
		$this->sourceLength = strLen($source);
		$this->sourceHash = md5($source);
		$this->source =& $source; 
		$this->parseSource();
	}

	function getTekst()
	{
		if (!$this->sourceElements)
			$this->parseSource();
//printDebug($this->sourceElements);
		// $this->sourceElements is only splitted on method calls.
		// make hyperlink of method name
		$pattern = '/function[\s]+(\S+)\(/';
		$els1 = preg_split($pattern, $this->sourceElements[0], -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
		if ($els1[0] != $this->getTitel() && $els1[0] != '&'.$this->getTitel())
			$tekst = array_shift($els1);
		else
			$tekst = '';
		
		$tekst .= "function ";
		if (subStr($els1[0], 0, 1) == '&')
			$tekst .= '&';
		$tekst .= "[~methodSearch:";
		$tekst .= $this->getTitel();
		$tekst .= "]($els1[1]";
		
		for ($i=1; $i<count($this->sourceElements); $i += 2) {
			$call = $this->sourceElements[$i];
			$tekst .= "->[~methodSearch:$call](";
			$tekst .= $this->sourceElements[$i+1];
		}
		return $tekst;
	}
	
	function checkFsExists()
	{
		$parent =& $this->getParent();
		if ($parent)
			return $parent->checkFsExists();
		else 
			return false;
	}

	/** Only called by parent when method is new or has changed
	*/
	function saveCalls()
	{
		$this->parseSource();
		$callIndex = array();
		$qh =& $this->newQueryHandler();
		$qh->runQuery("DELETE FROM hcodecall WHERE methodId = $this->id");
		
		for ($i=1; $i<count($this->sourceElements); $i += 2) {
			$call = $this->sourceElements[$i];
			if (!isSet($callIndex[$call]) ) {
				$callIndex[$call] = $i;
				$qh->runQuery("INSERT INTO hcodecall SET 
					methodId = $this->id,
					name = '$call'");
//print "<BR>\n$qh->query";
			}
		}

	}
	
	/** Initialize this from the parent. 
	* Make sure this is up to date as well as its 
	* data in the database. 
	* @result boolean Wheather this was already up to date
	*/
	function initFromSource()
	{
		$cls =& $this->getParent();
		if (!$cls) {
			$this->source = 'Method without Class: '. $this->getKey();
			return false;
		}
			
		$methods =& $cls->getMethods();
		$mth =& $methods[$this->titel];
		if (!$mth) {
			$this->source = 'DELETED Method: '. $this->getKey();
			return false;
		}
		if ($this->sourceHash == $mth->sourceHash)
			return true;
			
		//$mth has already updated database 
		$this->id = $mth->id;
		$this->parentId = $mth->parentId;
		$this->sourcePos = $mth->sourcePos;
		$this->sourceLength = $mth->sourceLength;
		$this->sourceHash = $mth->sourceHash;
		$this->source =& $mth->source; 
		return false;
	}

	//only called by parent to initialize new HcodeMethods
	function initFromParent(&$parent, $mthName, $sourcePos, &$source)
	{
		$this->setParent($parent);
		$this->titel = $mthName;
		$this->sourcePos = $sourcePos;
		$this->setSource($source);
		$this->fsExists = true;
	}		
	
	/** Build parstree from source. Current version only splits off the method calls
	*/
	function parseSource()
	{
		$this->sourceElements = preg_split('/\->(\w+)\(/', $this->getSource(), -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);
	}

}
?>