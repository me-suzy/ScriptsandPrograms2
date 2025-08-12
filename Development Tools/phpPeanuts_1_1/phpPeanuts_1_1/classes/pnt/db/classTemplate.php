<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntDbObject', 'pnt/db');

class PntDbObjectTemplate extends PntDbObject {

	function PntDbObjectTemplate($id=null)
	{
		$this->PntDbObject($id);
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function getTableName() 
	{
		return 'testdbobjects';
	}
	
	/** Returns the classFolder
	* @static
	* @return String 
	*/
	function getClassDir() 
	{
		return 'pnt/db';
	}
	
	
	function initPropertyDescriptors() {
		parent::initPropertyDescriptors();
		
		//$this->addFieldProp($name, $type, $readOnly=false, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null, $persistent=true) 
		//$this->addDerivedProp/addMultiValueProp($name, $type, $readOnly=true, $minValue=null, $maxValue=null, $minLength=0, $maxLength=null, $classDir=null) 
	}


	
}
?>