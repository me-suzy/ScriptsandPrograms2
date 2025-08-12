<?php

/**
* Datatype for handling strings
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
* Use this datatype for (usual) strings. You can pass a list of values the string is allowed
* to take and decide whether to be "strict" or not.
*
* @version      $Id: easy_string.class.php,v 1.2 2005/08/06 06:57:07 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

class easy_string extends datatype {

    /**
     * which values is the variable allowed to take?
     *
     * @access private
     * @var array a list of values the variable is allowed to be assigned to
     */
    var $allowed_array  = null;

    /**
     * how many characters can the string hold? Used for setting maxlength in
     * inputHTML.
     *
     * @access private
     * @var int maxiumum length of the string, defaults to null
     */
    var $maxlength  = null;

    /**
     * Constructor
     *
     * @access public
     * @var string  $value the value to initialize the variable with
     * @var array   $allowed_array list of allowed values, can be null (to be ignored)
     * @var boolean $strict throw assertion when validation fails?
     */
    function easy_string ($value, $maxlength = null, $allowed_array = null, $strict = false) {

        // call parents constructor
    	parent::datatype($strict);
    	
		if (!(is_null($value)))
    		assert (is_string($value));
		
		if ($allowed_array != null) {
		    $this->allowed_array = $allowed_array;
		}	
		
		$this->maxlength = $maxlength;
		$this->data      = $value;
	}

    /**
     * Validation
     *
     * @access private
     * @var boolean validation successful or not. In case of strict validation, an assertion is thrown if not successful
     */
    function validate () {
		
		// call parents class validation first
    	if (!parent::validate()) return false;
		
		// suppose successful validation
		$validated = true;

		if ($this->strict && $this->allowed_array != null)
			assert ('in_array ($this->data, $this->allowed_array)');
		elseif ($this->allowed_array != null) { 
			if (!$validated = in_array ($this->data, $this->allowed_array)) {
				$this->error = 'VALUE NOT ALLOWED'; 
			}
		}
        return $validated;
    }

   /*
    * Append string to current data. 
    * If strict or not, $value must not be null and has to be a string
    * 
    * @var string value String to append to current value
    */
    function append ($value) {
        
		assert ('!is_null($value)');
		assert ('is_string ($value)');
	    $this->data .= $value; 
    }    
    
   /*
    * Render HTML for formular (type: input=text)
    * 
    * @var string $name name of the input element
    */
    function getInputHTML ($name, $tabindex = null, $accesskey = null) {
        
        $ret    = '';
        ($this->maxlength === null) ? $maxLengthHTML = '' : $maxLengthHTML = ' maxlength="'.$this->maxlength.'"';
        ($this->class     === null) ? $classHTML     = '' : $classHTML     = ' class="'.$this->class.'"';
        ($this->style     === null) ? $styleHTML     = '' : $styleHTML     = ' style="'.$this->style.'"';
        
        if ($accesskey    === null)
            $accesskey     = '';
        else 
            $accesskey = ' accesskey="'.$accesskey.'"';
        
        if ($tabindex === null)
            $tabHTML = ' tabindex="'.$this->tabindex.'"';
        else            
            $tabHTML = ' tabindex="'.$tabindex.'"';

        $ret .= '<input type=text name="'.$name.'" '; 
        $ret .= 'value="'.$this->get().'" ';
		$ret .= $classHTML.' '.$styleHTML.' '.$tabHTML.' '.$maxLengthHTML.' '.$accesskey.'>';

        return $ret;
    }    

   /*
    * Render HTML for formular (type: textarea)
    * 
    * @var string $name name of the input element
    */
    function getTextareaHTML ($name, $rows, $tabindex = null, $accesskey = null) {
        
        $ret    = '';
        ($this->class     === null) ? $classHTML     = '' : $classHTML     = ' class="'.$this->class.'"';
        ($this->style     === null) ? $styleHTML     = '' : $styleHTML     = ' style="'.$this->style.'"';
        
        if ($accesskey    === null) 
            $accesskey     = '';
        else 
            $accesskey = ' accesskey="'.$accesskey.'"';
        
        if ($rows === null) 
            $rows = '';
        else 
            $rows = ' rows="'.$rows.'"';

        if ($tabindex === null)
            $tabHTML = ' tabindex="'.$this->tabindex.'"';
        else            
            $tabHTML = ' tabindex="'.$tabindex.'"';

        $ret .= '<textarea name="'.$name.'" '.$rows.' '; 
		$ret .= $classHTML.' '.$styleHTML.' '.$tabHTML.' '.$accesskey.'>';
		$ret .= $this->get();
		$ret .= "</textarea>\n";

        return $ret;
    }    

}

?>
