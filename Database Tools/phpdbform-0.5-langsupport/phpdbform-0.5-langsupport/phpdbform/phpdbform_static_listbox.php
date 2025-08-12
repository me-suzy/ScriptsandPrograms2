<?php
/**************************************
 * phpdbform_static_listbox           *
 **************************************
 * Static ListBox control             *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 07                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_static_listbox extends phpdbform_field {
	// array of value, text
    var $options = array();

	// options can be an array or string
    function phpdbform_static_listbox( &$form, $field, $title, $options )
    {
		$this->phpdbform_field( $form, $field, $title );
        if( is_array($options) ) $this->options = $options;
		else {
			$tok = strtok ($options, ",");
			while( $tok )
			{
				$pos = strpos($tok, ";");
				if ($pos === false) {
					$this->options[] = array( $tok, $tok );
				} else {
					$this->options[] = array( substr($tok, 0, $pos), substr($tok, $pos + 1) );
				}
				$tok = strtok (",");
			}
		}
		$this->cssclass = "fieldlistbox";
		$form->add( $this );
    }

    function get_string()
    {
        if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
        else $javascript="";
        if( !empty($this->title) ) $ret = $this->title."<br>";
		else $ret = "";
        $ret .= "<select class=\"{$this->cssclass}\" name=\"$this->key\" $javascript {$this->tag_extra}>\n";
		reset( $this->options );
		while( $tok = each($this->options) ) {
			$selected = ($tok[1][0] == $this->value)?"selected":"";
			$ret .= "<option value=\"{$tok[1][0]}\" $selected>{$tok[1][1]}</option>\n";
        }
        $ret .= "</select>\n";
		return $ret;
    }

    function process()
    {
        if( isset( $_POST[$this->key] ) ) {
            $this->value = $_POST[$this->key];
            $this->delmagic();
        }
    }
}
?>
