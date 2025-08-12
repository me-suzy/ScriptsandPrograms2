<?php
/**************************************
 * phpdbform_static_radiobox          *
 **************************************
 * Static RadioBox control            *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 06 - 13                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_static_radiobox extends phpdbform_field {
	// array of value, text
	var $options = array();

    function phpdbform_static_radiobox( &$form, $field, $title, $options )
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
		$this->cssclass = "fieldradiobox";
		$form->add( $this );
    }

	function get_string()
    {
        if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
        else $javascript="";
        if( !empty($this->title) ) $ret = $this->title."<br>";
		else $ret = "";
		reset( $this->options );
        while( $tok = each($this->options) )
        {
			$checked = ($tok[1][0] == $this->value)?"checked":"";
            $ret .= "<input type=\"radio\" class=\"{$this->cssclass}\" name=\"$this->key\" value=\"{$tok[1][0]}\" $checked $javascript  {$this->tag_extra}>{$tok[1][1]}<br>\n";
        }
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
