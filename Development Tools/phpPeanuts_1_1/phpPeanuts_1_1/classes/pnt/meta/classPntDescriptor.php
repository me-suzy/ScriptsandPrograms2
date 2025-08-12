<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0

includeClass('PntObject', 'pnt');

/** Abstract superclass of meta objects. 
* @package pnt/meta
*/
class PntDescriptor extends PntObject {

	var $name;
	var $label;

	function getName() {
		return $this->name;
	}
	
	function setName($aString) {
		$this->name = $aString;
	}

	function getLabel() {
		if (!isSet($this->label))
			return $this->getName();
			
		return $this->label;
	}
	
	function setLabel($aString) {
		//the label of a class can be changed, for example when the site uses a differen language
		$this->label = $aString;
	}

}
?>