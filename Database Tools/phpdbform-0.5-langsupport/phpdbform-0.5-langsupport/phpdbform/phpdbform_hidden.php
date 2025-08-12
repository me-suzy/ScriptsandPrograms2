<?php
/**************************************
 * phpdbform_hidden                   *
 **************************************
 * Hidden control                     *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 11                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_hidden extends phpdbform_field {
    
    function phpdbform_hidden( &$form, $field )
    {
		$this->phpdbform_field( $form, $field, "" );
		$form->add( $this );
    }

    function get_string()
    {
        return "<input type=hidden name=\"$this->key\" value=\"".htmlspecialchars($this->value)."\">\n";
    }

    function process()
    {
        if( isset( $_POST[$this->key] ) ) {
            $this->value = $_POST[$this->key];
            $this->delmagic();
        }
    }
}
