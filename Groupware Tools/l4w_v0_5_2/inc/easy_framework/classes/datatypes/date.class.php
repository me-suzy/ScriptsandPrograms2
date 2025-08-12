<?php

/**
     * Class String (Easy)
     *
     * Class to handle Srrings
     * @package EasyFramework
     */
     
     /**
	   * Class String (Easy)
	   *
	   * Class to handle Srrings
	   * @package EasyFramework
	  */

class easy_date extends datatype {

	var $format         = DATE_FORMAT;
	var $original       = '';
	var $format_success = true;

    // numeric!
    function easy_date ($value = null) {

    	parent::datatype(false);
		if (!(is_null($value))) {
    		assert (is_numeric($value));
    		//$this->data = strtotime($value);
	        $this->data = $value;
	    }
	}
	
	function setFormat ($format) {
	    assert ('is_string($format)');    
	    $this->format = $format;
	}    
    
    function validate () {
		
    	if (!parent::validate()) return false;
		
		$validated = true;
		
        return $validated;
    }
    
    function set ($value) { // value is string like '30.5.2005' or something like that

        $this->original = $value;

        if ($this->NULL_ALLOWED && is_null ($value)) {
            $this->data = null;
            return;    
        }    

        if ($this->EMPTY_ALLOWED && ($value == "0000-00-00" || $value == "")) {
            $this->data = "";
            return;    
        }    

        // try to guess format first (database date string look like 2005-12-30)
        $guess_success = false;
        $tmp   = @explode ("-", $value);
        $day   = @$tmp[2];
        $month = @$tmp[1];
        $year  = @$tmp[0];
        //echo $value;
        //echo "(".$month."-".$day."-".$year.")";
        if (@checkdate($month, $day, $year)) {
            $guess_success = true;    
        }    

        if (!$guess_success) {
            switch (DATE_FORMAT) {
                case 'd.m.Y':
                    $tmp   = explode (".", $value);
                    $day   = @$tmp[0];
                    $month = @$tmp[1];
                    $year  = @$tmp[2];
                    break;
                case 'Y-m-d':    
                    $tmp   = explode ("-", $value);
                    $day   = @$tmp[2];
                    $month = @$tmp[1];
                    $year  = @$tmp[0];
                    break;
                case 'Ymd':    
                    $day   = substr ($value,0,4);
                    $month = substr ($value,4,2);
                    $year  = substr ($value,6,2);
                    break;
                case 'm/d/Y':    
                    $tmp   = explode ("/", $value);
                    $day   = @$tmp[1];
                    $month = @$tmp[0];
                    $year  = @$tmp[2];
                    break;
                default: 
                    assert ('strlen("DATE_FORMAT NOT SUPPORTED") == 0');
                    break;
            }
        }
                
        if (!@checkdate ($month, $day, $year)) {
            $this->format_success = false;
            $this->error          = 'DATE NOT VALID';
        }
            
        $val   = mktime (1,1,1,$month, $day, $year);
            
        // set as unix timestamp
        parent::set ($val);                
    }    
    
    function get ($format = null) {

        if (!$this->format_success) // echo "*";
            return $this->original;
            
        $val = parent::get ();
        if (is_null ($format))
            $format = DATE_FORMAT;
        if ($this->EMPTY_ALLOWED && $this->data == "")
            return '';
            
        return date ($format, $val);
    }    

   /*
    * Render HTML for formular (type: input=text)
    * 
    * @var string $name name of the input element
    */
    function getInputHTML ($name, $tabindex = null, $accesskey = null) {
        
        $ret    = '';
        ($this->class     === null) ? $classHTML     = '' : $classHTML     = ' class="'.$this->class.'"';
        
        if ($accesskey    === null)
            $accesskey     = '';
        else 
            $accesskey = ' accesskey="'.$accesskey.'"';
        
        if ($tabindex === null)
            $tabHTML = ' tabindex="'.$this->tabindex.'"';
        else            
            $tabHTML = ' tabindex="'.$tabindex.'"';

        $ret .= '<input type=text size=10 maxsize=10 name="'.$name.'" '; 
        $ret .= 'onClick="javascript:show_calendar(\''.$name.'\',\''.time().'\');" readonly ';
        $ret .= 'value="'.$this->get().'" ';
		$ret .= $classHTML.' '.$tabHTML.' '.$accesskey.'>';

        return $ret;
    }    
	
	
}

?>
