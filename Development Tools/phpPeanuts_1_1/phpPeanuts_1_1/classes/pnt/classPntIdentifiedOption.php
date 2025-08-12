<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');

/** Abstract superclass for identiefied options.  
* @see http://www.phppeanuts.org/site/index_php/Menu/218
* @abstract
* @package pnt
*/
class PntIdentifiedOption extends PntObject {

	function PntIdentifiedOption($id=null, $label=null)
	{
		$this->PntObject();
		$this->id = $id;
		$this->label = $label;
	}

	/** @static 
	* @return String the name of the database table the instances are stored in
	* @abstract - override for each subclass
	*/
	function initPropertyDescriptors() {
		// only to be called once

		parent::initPropertyDescriptors();

		$this->addFieldProp('id', 'number', false,null,null,0,'6,0');
		$this->addFieldProp('label', 'string');
	}

	function getLabel()
	{
		if ($this->label)
			return $this->label;

		return $this->get('id');
	}	
	
	/** Returns the instances by Id
	* @static
	* @abstract
	* @return Array of instances
	function getInstances()
	{
		static $instances;
		if (!$instances) {
			//initialize instances here
		} else
			reset($instances);
			
		return $instances;
	}
	*/

}
?>