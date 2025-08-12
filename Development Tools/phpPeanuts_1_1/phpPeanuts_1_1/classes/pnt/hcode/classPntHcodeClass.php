<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntHcodeObject', 'pnt/hcode');

/** Instances correspond to classes that reside in files.
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
* only instances that really exist on the filessystem
* should be returned. 
* If an instances existance is validated and it no longer exists, 
* it is deleted from the database and all methods are too.
* If source, parent, package or methods are retrieved,
* the instance must be validated and if it is outdated, 
* it must be updated, and the database too. 
* If the database is updated, methods are updated but 
* subclasses are not. Parents are only updated as
* far as necessary to prevent cycles in recursive parent retrieval.
*
* in order to fit into our CMS hcode classes  
* support the necessary interface from Menu or Page.
* @package pnt/hcode
*/
class PntHcodeClass extends PntHcodeObject {

	var $parentId = 0;
	var $scope = '';

	//static Create instance from a key
	function getInstance($key)
	{
		return new HcodeClass($key);
	}
	
	//static
	function getTableName()
	{
		return 'hcodeclass';
	}

	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();

		$this->addFieldProp('parentId', 'number', false, 0, null, 0, '6,0');
		$this->addFieldProp('packageKey', 'string', false, null, null, 0, 80);
		$this->addFieldProp('scope', 'string', false, null, null, 0, 1000);
		$this->addFieldProp('sourceLength', 'number', false, 1, 64000, 1, '5,0');
		$this->addFieldProp('versiondt', 'timestamp', false, null, null, null, null);
	
		$this->addFieldProp('parentKey', 'string', false, null, null, null, 180, null, false);
		
		$this->addDerivedProp('source', 'string');
		$this->addDerivedProp('package', 'HcodePackage');
		$this->addDerivedProp('methods', 'HcodeMethod');
		$this->addDerivedProp('parent', 'HcodeClass');
		$this->addDerivedProp('children', 'HcodeClass');
		$this->addDerivedProp('packageFilePath', 'string');

		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}

	function getBeschrijving()
	{
		return "class source";
	}

	function getFileName()
	{
		$fileName = "test$this->titel.php";
		if ( is_file($this->getFilePath($fileName)) )
			return $fileName;
		else
			return "class$this->titel.php";
	}
	
	function &getMethods()
	{
		$this->ensureUpdated();
		
		$this->dbEnsureMethodsInitialized();
		return $this->methods;
	}
	
	/** Get method by name, case insensitive */
	function &getMethodNamed($methodName)
	{
		$name = strToLower($methodName);
		$methods =& $this->getMethods();
		reset($methods);
		while ( list($key) = each($methods) ) {
//print "$key ";
			if (strToLower($key) == $name)
				return $methods[$key];
		}
		return null;
	}
	
	function dbEnsureMethodsInitialized()
	{
		if (isSet($this->methods)) return ;
		
		//load methods from database, build associative array by titel
		$this->methods = array();
		includeClass('HcodeMethod', 'beheer');
		$clsDes =& PntClassDescriptor::getInstance('HcodeMethod');
		$retrieved =& $clsDes->getPeanutsWith('parentId', $this->id);
		while (list($key) = each($retrieved))
			$this->methods[$retrieved[$key]->getTitel()] =& $retrieved[$key];
			
		ksort($this->methods); //case sensitive sort
	}
	
	function &getPackage()
	{
		includeClass('HcodePackage', 'beheer');
		return new HcodePackage($this->packageKey);
	}
	
	function getPackageFilePath()
	{
		return str_replace('.', '/', $this->packageKey);
	}

	
	function &getWhole()
	{
		return $this->getPackage();
	}

	function getMenu1() 
	{
		return $this->getPackage();
	}
	
	function getSource()
	{
		if (!$this->checkFsExists())
			return "DELETED class: ". $this->getKey();
		$this->ensureUpdated();
		if (!$this->source)
			$this->source = $this->getFilePiece(
				$this->getFilePath()
				, $this->sourceLength);
			
		return $this->source;
	}
	
	function setSource($value)
	{
		$this->source = $value;
		$this->sourceLength = strLen($value);
	}

	function getTekst()
	{
		$parentKey = $this->getParentKey();
		$sep = $this->getKeySeparator();
		$i = strRPos($parentKey, $sep);
		if ($i !== false) {
			$parentTitel = subStr($parentKey, $i + 1);
		} else {
			$parentTitel = "$parentKey"; //workaround for reference anomalies
		}
		$link = "[~class:$parentKey|$parentTitel]";
		$tekst = str_replace($parentTitel, $link, $this->getSource());
		return $tekst;
	}

	function getParentKey()
	{
		if ($this->parentKey)
			return $this->parentKey;

		if (!$this->ensureUpdated())
			return $this->parentKey;
		$parent = $this->getParent();
		if ($parent)
			return $parent->getKey();
		
		return null;
	}
	
	//called from initParentKey called from initFromFile called from ensureUpdated
	function setParentKey($value)
	{
		$this->parentKey = $value;
		
		$parent =& $this->getParent();
		$parent->loadData();
		if (!$parent->get('id'))
			$parent->ensureUpdated(); //recurse into parent
		
		$this->scope = null;
		$this->getScope();                          // initializes scope so scope
		$this->parentId = $this->parent->get('id'); //and parentId  can be saved to the database
	}
	
	/** @result superClass
	*/
	function &getParent()
	{
		if (isSet($this->parent))
			return $this->parent;
			
		includeClass('HcodeClass', 'beheer');
		
		if ($this->parentKey) { //initialized from source
			$this->parent =& new HcodeClass($this->parentKey);
		} else { //initialized from key or data
			if (!$this->id)
				$this->loadData();
			if (!$this->id)
				$this->ensureUpdated(); //only if this is not in the database
			$clsDes =& PntClassDescriptor::getInstance('HcodeClass');
			$retrieved =& $clsDes->getPeanutsWith('id', $this->parentId);
			if (count($retrieved))
				$this->parent =& $retrieved[key($retrieved)];
			else 
				$this->parent = null;
		}

		return $this->parent;
	}

	function getScope()
	{
		if ($this->scope)
			return $this->scope;
		
		$parent =& $this->getParent();
		
		if ($parent) {
			$pScope = $parent->getScope();
			$this->scope = $pScope 
				? $pScope.$parent->getTitel().'|' 
				: '|'.$parent->getTitel().'|' ;
		} else {
			$this->scope = '';
		}
				
		return $this->scope;
	}

	/** @result subclasses
	*/
	function &getChildren()
	{
		if ($this->children)
			return $this->children;
		
		if (!$this->checkFsExists()) 
			return array();
			
		//load from database
		$clsDes =& PntClassDescriptor::getInstance('HcodeClass');
		$this->children =& $clsDes->getPeanutsWith('parentId', $this->id);
		return $this->children;
	}

	/** Check existance. If this no longer exists, 
	* it is deleted from the database and all methods are too.
	* @result boolean 
	*/
	function checkFsExists()
	{
		if ($this->fsExists !== null)
			return $this->fsExists;
	
		$this->fsExists = is_file(realpath($this->getFilePath()));
		if (!$this->fsExists) {
			$this->delete();
		}
		return $this->fsExists;
	}
	
	function getFileModifiedTime()
	{
		if (!$this->checkFsExists())
			return null;
			
		includeClass('ValueValidator');
		$unixTimestamp = filemtime($this->getFilePath());
		return date(ValueValidator::getInternalTimestampFormat(), $unixTimestamp);
	}
	
	/** delete from database with all methods
	*/
	function delete()
	{
		if (!$this->id)
			$this->loadData();
		if ($this->id) {
			$this->dbEnsureMethodsInitialized();
			$this->deleteMethods();
			parent::delete();
		}
	}
	
	//PREREQUISITE:  loadData done, $this->methods initialized
	function deleteMethods($sender='')
	{
		reset($this->methods);
		while (list($key) = each($this->methods)) {
			if ( is_ofType($this->methods[$key],'PntHcodeMethod') )
				$this->methods[$key]->delete();
			else {
				//old debugging code, should never run
				print "\n<BR>key: ". $this->getKey();
				print "\n<BR>sender: $sender";
				printDebug($this->methods[$key]);
				return printDebug($this->methods);
			}
		}
	}
	
	function loadData()
	{
		$clsDes =& $this->getClassDescriptor();
		$qh =& $clsDes->getSelectQueryHandler();

		$fieldMap =& $clsDes->getFieldMap();
		$map =& $qh->prefixColumnNames($fieldMap, $this->getTableName());

		$qh->where_equals($map['titel'], $this->titel);
		$qh->query .= ' AND '. $map['packageKey']. " = '$this->packageKey'";

		$qh->_runQuery();
		if ($qh->getError()) 
			trigger_error($qh->getError(), E_USER_ERROR);
		
		if ($qh->getRowCount() == 0) 
			return;
			
		$row=mysql_fetch_assoc($qh->result);
		$this->initFromData($row, $fieldMap);
	}

	/** Make sure this is up to date as well as its 
	* data in the database. 
	* @result boolean Wheather this was already up to date
	*/
	function ensureUpdated()
	{
		if (!$this->checkFsExists()) 
			return false; //checkFsExists has already deleted everything

		if (!$this->id)
			$this->loadData();
		if (isSet($this->versiondt) && $this->getFileModifiedTime() == $this->versiondt) 
			return true;

		$this->initFromSource();
		return false;
	}

	//PREREQUISITE: loadData done
	function initFromSource()
	{
//print "<BR>initializing class from source: ".$this->getKey()."<BR>";		
		includeClass('HcodeMethod', 'beheer');
		$this->dbEnsureMethodsInitialized();
		$this->versiondt = $this->getFileModifiedTime();
		$this->fileText = $this->getFileContents($this->getFilePath());

		$classDefEnd = $this->sourceStrPos($this->fileText, '{');
		$mthStart = $this->sourceStrPos($this->fileText, 'function ', $classDefEnd);
		if ($mthStart === false) { // no methods
			$this->setSource($this->fileText); 
		} else {
			//find end of var declarations or class def
			$lastStatementEnd = $this->sourceStrRPos($this->fileText, ';', $mthStart);
			if ($lastStatementEnd === false && $classDefEnd === false) 
				$prevMthEnd = $mthStart;
			else 
				$prevMthEnd = max($classDefEnd, $lastStatementEnd);
			//skip eventual comment on same line
			$prevMthEnd = $this->sourceStrPos($this->fileText, "\n", $prevMthEnd);
	
			$this->setSource(substr($this->fileText, 0, $prevMthEnd + 1));
		}

		$this->initParentKey($classDefEnd);
		$this->save();

		if ($mthStart === false) 
			$this->deleteMethods();
		else
			$this->initMethodsFromSource($mthStart, $prevMthEnd);
	}
	
	function initMethodsFromSource($mthStart, $prevMthEnd)
	{
		$methodsFromFile = array();
		$continue = true;
		while ($continue) {
			$mthNameEnd = $this->sourceStrPos($this->fileText, '(', $mthStart);
			if ($mthNameEnd === false) 
				return; //bad method declaration
			$mthName = substr($this->fileText, $mthStart + 8, $mthNameEnd - $mthStart - 8);
			$mthName = trim($mthName);
			if (subStr($mthName, 0, 1) == '&')
				$mthName = subStr($mthName, 1);

			//find next mthStart and mthEnd 
			$mthStart = $this->sourceStrPos($this->fileText, 'function', $mthNameEnd);
			if ($mthStart === false) {
				$mthEnd = strLen($this->fileText) -1;
				$continue = false;
			} else {
				$mthEnd = $this->sourceStrRPos($this->fileText, '}', $mthStart);
				if ($mthEnd <= $prevMthEnd) //only happens if method contains func'tion
					$mthEnd = $mthStart - 1; 
			}

			$mth =& new HcodeMethod();
			$mth->initFromParent($this, $mthName, $prevMthEnd + 1, 
				substr($this->fileText, $prevMthEnd + 1, $mthEnd - $prevMthEnd ) );
			if (!isset($methodsFromFile[$mthName])) {
				$methodsFromFile[$mthName] =& $mth;
				if (isset($this->methods[$mthName]) ) { //method already in db
					$mth->id = $this->methods[$mthName]->id;
					$mth->save();
					if ($this->methods[$mthName]->sourceHash != $mth->sourceHash) {
						$mth->saveCalls();
					}
					unset($this->methods[$mthName]);
				} else { //new method
					$mth->save();
					$mth->saveCalls();
				}
			} //else: method redefinition: ignore

			$prevMthEnd = $mthEnd;
		}
		//methods that no longer exist remain in $this->methods 
		$this->deleteMethods('initMethodsFromSource');
		
		ksort($methodsFromFile);
		$this->methods =& $methodsFromFile;
	}
	
	function initParentKey($classDefEnd)
	{	
//print "initParentId($classDefEnd)";
		$source = subStr($this->source, 0, $classDefEnd + 1);
		$superStart = $this->sourceStrPos($source, 'extends');
		$superNameLenght = $classDefEnd - $superStart - 7;
//print " $superStart, $classDefEnd, $superNameLenght";
		if ($superStart === false 
				|| $classDefEnd === false
				|| $superNameLenght <= 0
			) return;
					
		$superName = subStr($source, $superStart + 7, $superNameLenght);
		$superName = trim($superName);
//print("<BR>'$superName'");

		$source = str_replace('"', "'", $source);
		$superNameStart = $this->sourceStrPos($source, "'$superName'");
		$classDefStart = $this->sourceStrRPos($source, 'class', $superStart);
		if ($superNameStart === false || $superNameStart > $classDefStart)
			return;  //superclass as include parameter not found

		$includeStart = $includeEnd = 0;
		while ($includeEnd < $superNameStart && $includeStart !== false) {
			$includeStart = $this->sourceStrPos($source, 'includeClass', $includeEnd);
			$includeEnd = $this->sourceStrPos($source, ';', $includeStart);

			if ($superNameStart > $includeStart && $includeEnd > $superNameStart) {
				$paramSep = $this->sourceStrPos($source, ',', $superNameStart);
				if ($paramSep === false || $paramSep > $includeEnd) 
					return $this->setParentKey($superName);
				$litStart = strPos($source, "'", $paramSep + 1);
				if ($litStart === false || $litStart > $includeEnd) 
					return $this->setParentKey($superName);
				$litEnd = strPos($source, "'", $litStart + 1);
				if ($litEnd === false || $litEnd > $includeEnd) 
					return; //error

				$packageDir = subStr($source, $litStart + 1, $litEnd - $litStart - 1);
				$this->setParentKey(
					str_replace('/', '.', $packageDir)
					. '.'. $superName);
//print "<BR>parentKey: $this->parentKey";
				return;
			}
		}
	}

	/** Finds a String in source, skipping html, comments, and literal strings
	* Currently ignores the skipping and only finds sinlge chars when backwards
	*/
	function sourceStrPos(&$haystack, $needle, $pos=0)
	{
		//HACK: ignore the skipping
		return strPos($haystack, $needle, $pos);
	}
	
	function sourceStrRPos(&$haystack, $needle, $pos=null)
	{
		$needleLen = strLen($needle);
		while ($pos >= 0) {
			if (subStr($haystack, $pos, $needleLen) == $needle)
				return $pos;
			$pos--;
		}
		return false;
	}

	//should be moved to generalFuctions	
	function getFileContents($filePath)
	{
		$handle = fopen ($filePath, "r");
		$contents = "";
		do {
		    $data = fread($handle, 8192);
		    if (strlen($data) == 0) {
		        break;
		    }
		    $contents .= $data;
		} while(true);
		fclose ($handle);
		return $contents;
	}

}
?>