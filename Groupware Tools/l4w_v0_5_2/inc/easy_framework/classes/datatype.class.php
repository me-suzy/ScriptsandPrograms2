<?php

/**
* Superclass for all implemented Datatypes
*
* datatypes are used to hold the models data. Using datatypes ensures
* validation of user-input. Every field of the model should be defined
* in one of the different datatypes easy offers (like easy_string, easy_integer, ...)
* Have a look in the examples section to find out how datatypes are supposed
* to work.
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
* Superclass for all implemented Datatypes
*
* Use datatypes to hold the models data. For example, in the constructor of 
* a model, add something like 
*
* $CustomerId = new easy_integer (null, 0);
*
* This ensures the variable is initiated with "null" and has to be greater or
* equal to zero. Whenever trying to access the variable (via the $CustomerId->get()
* method), the variables data gets validated and an error (or info message) is
* thrown in case of a failed validation.
*
*
* @version      $Id: datatype.class.php,v 1.11 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
* @todo         think about history management
*/

class datatype {
	
	/**
     * contains the data of the variable. Should be access only via getter and setter
     * methods. Defaults to NULL.
     *
     * @access private
     * @var object
     */
	var $data           = NULL;

	/**
     * if strict is set to true, assertions are used to 
     * validate the data. This may result in stopping the execution
     * of the script.
     *
     * As a rule of thumb, set strict to true if you are dealing with
     * variables the user is not supposed to change, and set it to false
     * when dealing with user input.
     *
     * @var boolean
     */
	var $strict           = false;

	/**
     * error if validation fails
     *
     * @var     integer
     * @todo    nr or msg or both?
     */
	var $error            = 0;

	/**
     * css class for rendering datatype
     *
     * @var string
     */
	var $class = null;

	/**
     * css style for rendering datatype
     *
     * @var string
     */
	var $style = null;

	/**
     * additional user functions for extended validation
     *
     * @access private
     * @var    object
     */
	var $rules            = null;

	/**
     * variable can be null (i.e. !isset ($var))
     *
     * @var boolean
     * @access private
     */
    var $NULL_ALLOWED   = true;

    /**
     * variable can be empty (i.e. ($var == ''))
     *
     * @var boolean
     * @access private
     */
	var $EMPTY_ALLOWED  = true;

	/**
     * if data == null, map data automatically to this value
     *
     * @var object
     * @access private
     */
	var $_mapNullTo     = null;

	/**
     * if data == '', map data automatically to this value
     *
     * @var object
     * @access private
     */
	var $_mapEmptyTo    = null;
		
	/**
     * tabindex in formular
     *
     * @var string
     * @access private
     */
	var $tabindex = 0;

	/**
     * Constructor of datatype
     *
     * As a rule of thumb, set strict to true if you are dealing with
     * variables the user is not supposed to change, and set it to false
     * when dealing with user input. 
     *
     * This constructor is called by the datatypes derived from this class
     * when initiating a new datatype.
     *
     * @var boolean use_strict true means using assertions
     */
    function datatype ($use_strict = false) {
    	global $easy_tabindex;
    	
    	$this->strict   = $use_strict;
    	$this->tabindex = $easy_tabindex;
    	$easy_tabindex++;
    }

	/**
     * basic datatype validation. Subclasses extend this validation rules
     * but should call this superclass implementation. This superclass basically
     * checks for emptyness and / or "nullness" of the checked data.
     *
     * @return boolean validation success or failure of validation
     */
	function validate () {

        // assume validation succeeds
		$basic_validation = true;
		
		// if datatype is null and null is not allowed, set error
		if (!$this->NULL_ALLOWED  && is_null($this->data)) {
			$this->error = 'DATATYPE IS NULL'; 
			$basic_validation = false;
		}
		
		// if datatype is empty and empty is not allowed, set error
        if (!$this->EMPTY_ALLOWED && $this->data === "") {
			$this->error = 'DATATYPE IS EMPTY'; 
    		$basic_validation = false;
        }

        // if strict, then die if validation did not succeed
        if ($this->strict)
        	assert ('$basic_validation');
		else { // else return false
			if (!$basic_validation) return false;
		}
		
		// passed, so return success of validation 
        return true;
	}	

	/**
     * Don't access this->value directly, use this setter function. It automatically
     * performs some basic checks (validations) on the given value and maps empty strings
     * or null - values to defauls given in _mapNullTo and _mapEmptyTo, resp.
     *
     * @var mixed $value The data which should be assigned to the variable
     */
    function set ($value) {

    	// 1.) Value is Null? 
        if (is_null($value) && !is_null($this->_mapNullTo))
            $value = $this->_mapNullTo;
		if (is_null($value) && $this->NULL_ALLOWED) {
		    $this->data = $value;
		    return;
		}
		assert ('!is_null($value)');

        // 2.) Value is Empty (i.e. empty string)?
        if ($value == '' && !is_null($this->_mapEmptyTo)) 
            $value = $this->_mapEmptyTo;
        if ($value == '' && $this->EMPTY_ALLOWED) {
		    $this->data = $value;
            return;
        }
        // 3.) Assign value
	    $this->data = $value;    	
    }

	/**
     * Calling get results in validating the data assigned to the variable. If strict
     * is set to true, a validation failure throws an assertion. The additional rules
     * which have been added via add_rule are called and this->error is set.
     * 
     * @todo   think about additional rules and behavior in case of problem.
     * @return mixed return the data assigned to this variable
     */
    function get () {

		// data is null and null is allowed: return null
        if (is_null($this->data) && $this->NULL_ALLOWED) 
            return null;
		
		// now assert data is not null
		assert ('!is_null($this->data)');
		
		$validated = true;
		
		if ($this->strict)
			assert ('$this->validate()');
		else
			$validated = $this->validate();

		if (!$validated) 
		    return false;
		
		// validate user functions - only set error msg, but keep value
		$error = $this->validate_additional_rules();	
		if ($error != '') {
			$this->error = $error; 
		}	
				
		return $this->data;
    }
    
	/**
     * Wrapper for get, calls htmlspecialchars.
     * 
     * @return mixed return the data assigned to this variable after put into htmlspecialchars
     */
    function getHTMLEscaped () {
        return htmlspecialchars ($this->get(), ENT_QUOTES);    
    }    
    
	/**
     * Wrapper for get, removes escape sequences.
     * 
     * @return mixed return the data assigned to this variable after escape seqences have been stripped off
     */
    function getUnescaped () {
        $tmp = $this->get();
        $tmp = str_replace ('\"', '"', $tmp);
        $tmp = str_replace ("\'", "'", $tmp);
        return $tmp;    
    }    

	/**
     * Setter for NULL_ALLOWED.
     * 
     * @var boolean $allow set NULL_ALLOWED to true
     */
    function set_null_allowed ($allow) {
        $this->NULL_ALLOWED = $allow;
    }

	/**
     * Map NULL to any value?
     * 
     * @var mixed $value maps NULL value to given variable
     */
    function mapNullTo ($value) {
        $this->_mapNullTo = $value;    
    }

	/**
     * Setter for EMPTY_ALLOWED.
     * Sets NULL_ALLOWED to false if empty is not allowed
     * 
     * @var boolean $allow set EMPTY_ALLOWED to true
     */
    function set_empty_allowed ($allow) {
        $this->EMPTY_ALLOWED = $allow;
        if (!$allow) 
            $this->NULL_ALLOWED = false;
    }

	/**
     * Map "empty" value to any value?
     * 
     * @var mixed $value maps "empty" value to given variable
     */
    function mapEmptyTo ($value) {
        $this->_mapEmptyTo = $value;    
    }   

	/**
     * Adds rule to list of rules to apply when getting back the data for
     * a variable using get();
     * 
     * @param string $user_function the name of the function to call
     * @param array  $params array of parameters to pass to the function 
     */
    function add_rule ($user_function, $params) {
    	$cnt = count ($this->rules);
   		$this->rules[$cnt]['name']   = $user_function; 	
   		$this->rules[$cnt]['params'] = $params; 	
    } 	

    /**
	 * Run additional checks on data
	 *
     * additional rules for validation can be added via add_rule. These rules
     * will not be validated automatically, you have to call this function. All
     * rules will be called; returning an empty string means the validation was
     * ok, in any other case it means that the returned string contains an error
     * message. If strict equals true, the error message has to be empty, if it is
     * false, the error message is returned.
     * If there are several rules, the first which fails returns its error message.
     *
     * @return string error message. Empty string means that there was no error.
     */
    function validate_additional_rules () {
		
		$i          = 0;
		while ($i < count ($this->rules)) {
			//echo "calling ".$this->rules[$i]['name']."<br>";
			$error_msg = call_user_func($this->rules[$i]['name'], $this->rules[$i]['params']);			
			if ($this->strict) {
				assert ('$error_msg == ""');
			}	
			else {
				if ($error_msg != '') { //return $error_msg;		
					return $error_msg;
				}	
			}	
			$i++;	
		}	
		return '';
	}	
}

?>
