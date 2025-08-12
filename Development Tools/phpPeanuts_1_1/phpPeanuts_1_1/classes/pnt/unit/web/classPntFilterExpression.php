<?php
// Copyright (c) MetaClass, 2003, 2004
// Licensed under the Academic Free License version 2.0



/**
 * This expression tells wheather a supplied string fulfills its requirements.
 * The requirements are an include pattern string
 * and an array of exlude pattern strings
 * 
 * subclasses decide how the patterns are interpreted, 
 * for example like preg, ereg, windows-style (*.php) 
 * or whatever you see fit.
 *
 *
 * @package pnt/unit/web
 * @author  Henk Verhoeven, MetaClass <henk@phpPeanuts.org>
 */

class PntFilterExpression  {
	
	/**
    *	String includePattern the pattern that 
    *      specifies which strings to include
    */
	var $includePattern = '';

	/**
    *	Array excludePatterns the patterns (Strings) that 
    *      specify which strings to exclude
    */
	var $excludePatterns;
	
    /**
    * Constructor calling initialize during construction. 
    *
    * @access public
    */
    function PntFilterExpression()  {
		$this->_initialize();
	}

    /**
    * Initialize during construction. Meant to be overriden by subclasses
    *
    * @access private
    */
	function _initialize () {
		//default is do nothing
	}
	
    /**
    * Property getter, see field doc
    *
    * @result String 
    * @access public
    */
    function getIncludePattern() {
    	return $this->includePattern;
    }

    /**
    * Property Setter, see field doc
    *
    * @param String 
    * @access public
    */
    function setIncludePattern($aValue) {
    	$this->includePattern = $aValue;
    }

    /**
    * Property getter, see field doc
    *
    * @result Array of String 
    * @access public
    */
    function getExcludePatterns() {
    	return $this->excludePatterns;
    }

    /**
    * Property Setter, see field doc
    *
    * @param Array of String 
    * @access public
    */
    function setExcludePatterns($aValue) {
    	$this->excludePatterns = $aValue;
    }

    /**
    * Evaluate the expression for the argument String. 
    * Return wheather the argument fulfills the requirements
    *
    * @param Array of String 
    * @result boolean 
    * @access public
    */
	function evaluate($aString) {

    	if ( $this->getIncludePattern() 
    			&& !$this->match($this->getIncludePattern(),$aString)
    		) {
    			return false;
   		}
		if ( is_array($this->getExcludePatterns()) ) {
			foreach ($this->getExcludePatterns() as $aPattern) {
				if ( $this->match($aPattern,$aString) ) {
					return false;
				}
			}
		}
		return true;
	}

    /**
    * Match the pattern String for the String. 
    *
    * @param String aPattern
    * @param String aString
    * @result boolean 
    * @access public
    * @abstract
    */
	function match($aPattern, $aString) {
		
		trigger_error('Should have been implemented by subclass', E_USER_WARNING );
		return true;
	}

}

?>