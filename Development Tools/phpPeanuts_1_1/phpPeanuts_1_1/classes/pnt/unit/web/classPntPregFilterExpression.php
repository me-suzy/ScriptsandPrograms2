<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0


includeClass('PntFilterExpression', 'pnt/unit/web');

/**
 * This expression tells wheather a supplied string fulfills its requirements.
 * The requirements are an include pattern string
 * and an array of exlude pattern strings
 * 
 * this classes expects preg patterns
 *
 *
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */

class PntPregFilterExpresson extends PntFilterExpression {
	
    /**
    * Initialize during construction. Meant to be overriden by subclasses
    *
    * @access private
    */
	function _initialize () {
		$this->setIncludePattern('.php');
		$this->setExcludePatterns(Array('.bak', '.bk'));
	}

    /**
    * Match the pattern String for the String. 
    *
    * @param String aPattern
    * @param String aString
    * @result boolean 
    * @access public
    */
	function match($aPattern, $aString) {
		
		return preg_match(
			"~$aPattern~"
			, $aString
			);
	}
}

?>