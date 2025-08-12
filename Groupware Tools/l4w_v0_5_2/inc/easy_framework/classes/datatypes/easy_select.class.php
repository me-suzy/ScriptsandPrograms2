<?php

/**
* Datatype to handle comboboxes (= select in html)
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

/**
* Datatype to handle comboboxes (= select in html)
*
* @version      $Id: easy_select.class.php,v 1.3 2005/08/06 06:57:08 carsten Exp $
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

class easy_select extends datatype {

    /**
     * size attribute of the select tag
     *
     * @access private
     * @var int 
     */
    var $size = 1;

    /**
     * multiple attribute of the select tag
     *
     * @access private
     * @var int 
     */
    var $multiple = false;

    /**
     * disabled attribute of the select tag
     *
     * @access private
     * @var int 
     */
    var $disabled = false;
    
    /**
     * default key when no option is given
     *
     * @access private
     * @var int 
     */
    var $defaultKey   = null;

    /**
     * key which should be selected 
     *
     * @access private
     * @var int 
     */
    var $selectedKey  = null;

    /**
     * When selection box has been filled via DB query, the query used 
     * is saved here
     *
     * @access private
     */
    var $query  = null;

    /**
     * Constructor
     *
     * @access public
     * @param  array   $options key => value pairs
     * @param  boolean $multiple selection may contain more than one element?
     */
    function easy_select ($options, $size = 1, $default = null, $selected = null) {

        // call parents constructor
    	parent::datatype(false);
    	
		if (!(is_null($options)))
    		assert (is_array($options));
		    		
		$this->size        = $size;
		$this->data        = $options;
		$this->default     = $default;
		$this->selectedKey = $selected;  
	}

    function setDefault ($default) {
        $this->default = $default;    
    }
        
    function get () {
        //$options =& parent::get();
        if (!parent::get())
        	return false;
        return $this->selectedKey;
    }

	/**
	 * @access public
     * @var mixed $value The data which should be assigned to the variable
     */
    function set ($value) {
        // 1.) Value is Null? 
        if (is_null($value) && !is_null($this->_mapNullTo))
            $value = $this->_mapNullTo;
		if (is_null($value) && $this->NULL_ALLOWED) {
		    $this->selectedKey = $value;
		    return;
		}
		assert ('!is_null($value)');

        // 2.) Value is Empty (i.e. empty string)?
        if ($value == '' && !is_null($this->_mapEmptyTo)) 
            $value = $this->_mapEmptyTo;
        if ($value == '' && $this->EMPTY_ALLOWED) {
		    $this->selectedKey = $value;
            return;
        }
        // 3.) Assign selected value
	    $this->selectedKey = $value;
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
		
        return true;
    }
        
   /*
    * Reads result set and fills options with key => value from $row[0] => $row[1]
    * 
    * @var string $name name of the input element
    */
    
    function fillFromResultSet (&$res, $query = null) {
        $this->query = str_replace(' ','#',$query);
        while ($row = mysql_fetch_array ($res)) {
            $this->data[$row[0]] = $row[1];    
        }    
    }
    
    function getQuery () {
        return str_replace('#',' ',$this->query);    
    }    
    
   /*
    * Render HTML for formular
    * 
    * @var string $name name of the input element
    */
    function getSelectHTML ($name, $tabindex = null, $accesskey = null) {
        $ret    = '';
        ($this->class     === null)  ? $classHTML     = '' : $classHTML     = ' class="'.$this->class.'"';
        ($this->style     === null)  ? $styleHTML     = '' : $styleHTML     = ' style="'.$this->style.'"';
        ($this->multiple  === false) ? $multiHTML     = '' : $multiHTML     = ' multiple';
        ($this->disabled  === false) ? $disabledHTML  = '' : $disabledHTML  = ' disabled';

        if ($tabindex === null)
            $tabHTML = ' tabindex="'.$this->tabindex.'"';
        else            
            $tabHTML = ' tabindex="'.$tabindex.'"';

        if ($accesskey    === null)
            $accesskey     = '';
        else 
            $accesskey = ' accesskey="'.$accesskey.'"';

        if ($this->size > 1) $name .= "[]";
        
        $ret .= '<select name="'.$name.'" size="'.$this->size.'" '.$classHTML.' '.$styleHTML.' '.$tabHTML.' '.$multiHTML.' '.$disabledHTML.' '.$accesskey.'>'; 
        foreach ($this->data AS $key => $value) {
            $sel = '';
            if ($this->selectedKey !== null) {
                if (is_array ($this->selectedKey)) {
                    if (in_array ($key,$this->selectedKey)) $sel = 'selected';           
                }
                else {
                    if ($this->selectedKey == $key) $sel = 'selected';    
                }
            }     
            else {
                if ($this->default == $key) $sel = 'selected';                        
            }
            $ret .= '<option value="'.$key.'" '.$sel.'>'.$value.'</option>';
        }
        $ret .= '</select>';
        
        return $ret;
    }    

}

?>
