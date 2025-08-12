<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


/** This class allows to be extended to support simple graphics calculations
* like vector addition, multiplication
* A Point is a value object, i.e. it should not be modified. 
* Instead, a new point will be created by calculating functions.
* @package pnt/graphics
*/
class PntPoint {
	
	var $x;
	var $y;
	
	function PntPoint($x, $y)
	{
		$this->x = $x;
		$this->y = $y;
	}
	
}