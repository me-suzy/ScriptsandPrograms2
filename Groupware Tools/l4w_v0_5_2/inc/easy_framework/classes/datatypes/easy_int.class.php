<?php
/**
*
* Datatype for handling integer values
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
*
* Datatype for handling integer values
*
* @version      $Id: easy_int.class.php,v 1.9 2005/08/06 06:57:07 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/
class easy_integer extends datatype {

	/**
     * additional user functions for extended validation
     *
     * @access private
     * @var    object
     */
	var $min  = NULL;

	/**
     * additional user functions for extended validation
     *
     * @access private
     * @var    object
     */
	var $max  = NULL;

    /**
     * how many characters can the string representation of the integer hold? Used for setting maxlength in
     * inputHTML.
     *
     * @access private
     * @var int maxiumum length of the string, defaults to null
     */
    var $maxlength  = null;

    
    /**
     * constructor
     *
     * @param  integer $value Default value to assign to variable
     * @param  integer $range_min left boundary
     * @param  integer $range_max right boundary
     * @param  boolean setting strict to true means using assertions for validation
     * @access public
     */
    function easy_integer ($value, $range_min = null, $range_max = null, $strict = false) {
    	parent::datatype($strict);
    	
    	if (!(is_null($value))) {
			assert (is_integer($value));
        }
		$this->min = $range_min;
		$this->max = $range_max;
		$this->data = $value;
	}
	
  /**
    * validation of easy_integer datatype. Calls parents validation first.
    * If strict is set to true, a validation error is checked via an assertion (i.e.
    * can lead to termination of the script depending on how assertions are handled)
    *
    * Validations done:
    * is_numeric
    * is greater or equal left boundary (if provided)
    * is less or equal right boundary (if provided)
    * 
    * @return boolean true or false depending if validation succeeded
    */ 
   function validate () {
	
		if (!parent::validate()) return false;
		$validated = true;
		if ($this->strict) {
			assert ('is_numeric($this->data)');
			if ($this->min !== NULL) {
			    assert ('$this->min <= $this->data');
			}
			if ($this->max !== null) {
			    assert ('$this->max >= $this->data');
			}
		}	
		else {
			if ($this->min !== NULL) {
				if ($this->min > $this->data) $validated = false;
			}
			if ($this->max !== null) {
			    if ($this->max < $this->data) $validated = false;
			}
		}		
        return $validated;
	}

  /**
    * first, parents set function is called. Next, by casting to inter
    * it is ensured that the variable actually holds an integer.
    * 
    * @param object value to assign to datatype
    */ 
	function set ($value) {
		parent::set($value);
		$this->data = (int)$this->data;		
	}	
	
   /**
    * Render HTML for formular (type: input=text)
    * 
    * @param string $name name of the input element
    * @param integer $tabindex number of the tabindex to use. Default: handled by easy
    * @return string HTML string for an input element
    */
    function getInputHTML ($name, $tabindex = null) {
        $ret    = '';
        ($this->maxlength === null) ? $maxLengthHTML = '' : $maxLengthHTML = ' maxlength="'.$this->maxlength.'"';
        ($this->class     === null) ? $classHTML     = '' : $classHTML     = ' class="'.$this->class.'"';
        
        if ($tabindex === null)
            $tabHTML = ' tabindex="'.$this->tabindex.'"';
        else            
            $tabHTML = ' tabindex="'.$tabindex.'"';

        $ret .= '<input type=text name="'.$name.'" '; 
        $ret .= 'value="'.$this->get().'" ';
		$ret .= $classHTML.' '.$tabHTML.' '.$maxLengthHTML.'>';

        return $ret;
    }    

}

?>
