<?php
/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: multiselect.class.php,v 1.8 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class easy_multiselect {

	var $options_string = null;
	var $formular       = "form";
	var $pool           = "pool";
	var $choices        = "choices";
    var $pool_style     = '';
    var $choices_style  = '';
    	
	function easy_multiselect($options_string = null) {
		$this->options_string  = $options_string;
	}

	/*function preSelect (&$params) {
		if (isset($params[$this->name])) {	
			$this->checked = true;
			return 1;	
		}
		else {
			$this->checked = false;
			return 0;
		}
	}
	
	function setSelect ($checked) {
		assert ('is_bool ($this->checked)');
		$this->checked = $checked;	
	}*/
	
	function setFormularName ($name) {
	    $this->formular = $name;    
	}    
	
	function setLeftSelectName ($name) {
	    $this->pool = $name;    
	}  
	
	function setLeftSelectStyle ($style) {
	    $this->pool_style = $style;    
	}  
	
	function setRightSelectName ($name) {
	    $this->choices = $name;    
	}  
	
	function setRightSelectStyle ($style) {
	    $this->choices_style = $style;    
	}  

	function getJavascript () {
	    $ret = '
    	    <script language="JavaScript" type="text/javascript">
    
        	//function addChoices(from, to) {
    	    function addChoices (left2right) {
    	        if (left2right) {
        			from = document.getElementById ("'.$this->pool.'");
        			to   = document.getElementById ("'.$this->choices.'");
    	        }
    	        else {
    	        	to   = document.getElementById ("'.$this->pool.'");
        			from = document.getElementById ("'.$this->choices.'");
    	        }
    	        
    			var position = to.length;
            	for (i=from.options.length-1; i >= 0; i--) {
    	            poolentry     = from.options[i].value;
        	        name          = from.options[i].text;
                    if (from.options[i].selected) {
					    // add on the one side
					    to.options[position]     = null;
			            neuerEintrag             = new Option (name,poolentry,true, true);
        			    to.options[position]     = neuerEintrag;
            			// remove on the other side
					    from.options[i] = null;
            			position++;
            		}
        	   	}
            }
    
       		//function is_undefined(val) { return typeof(val) == "undefined"; }

    	</script>';
	    return $ret;    
	}    
	
	function toString () {
		//onClick="jsDelete(document.'.$this->formular.'.'.$this->choices.');"
        //onClick="addChoices(document.'.$this->formular.'.'.$this->pool.', document.'.$this->formular.'.'.$this->choices.'[]);"
		$ret  = '<table><tr><td>';
		$ret .= '<select name="'.$this->pool.'" id="'.$this->pool.'" size="4" style="'.$this->pool_style.'" multiple>'."\n";
        $ret .= $this->options_string."\n";    
        $ret .= '</select>'."\n";
        $ret .= '</td><td>
                 <input type="button" value="<<" name="delete"
                    onClick="addChoices(false);">
                 <br><br>
                 <input type="button" value=">>"
                    onClick="addChoices(true);"
					alt="add" title="add">&nbsp;
				</td><td>
				<select name="'.$this->choices.'[]" id="'.$this->choices.'" 
				        size="4" style="'.$this->choices_style.'" multiple>
                </select>';
		$ret .= "</td></tr></table>";
		return $ret;
	}
	
	
}
?>
