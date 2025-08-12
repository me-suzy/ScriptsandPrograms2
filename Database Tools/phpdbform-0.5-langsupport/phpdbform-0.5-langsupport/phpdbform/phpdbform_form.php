<?php
/**************************************
 * phpform                            *
 **************************************
 * Base class for forms               *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 06                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpform {
    var $name;
    var $action;
    var $fields;
	var $noproc;
	var $hasdblink = false;

    function phpform( $name, $action = "" )
    {
        $this->fields = array();
        $this->name = $name;
        if( $action == "" ) $action = basename($_SERVER["PHP_SELF"]);
        $this->action = $action;
    }

	function add( &$field )
	{
		$this->fields[$field->field] = $field;
	}

    function draw_submit( $button_text )
    {
		// don't name this button submit, or the form.submit() won't work
        print "<input type=\"submit\" name=\"submit_button\" class=\"fieldbutton\" value=\"$button_text\">\n";
    }

    function draw_header()
    {
		print "<form method=\"post\" name=\"{$this->name}\" action=\"{$this->action}\" enctype=\"multipart/form-data\">\n<input type=\"hidden\" name=\"{$this->name}_phpform_sent\" value=\"1\">\n";
	}

    function draw_footer()
    {
        print "</form>\n";
    }

    function draw()
    {
        $this->draw_header();
        reset($this->fields);
        while( $field = each($this->fields) )
        {
//            print $field[1]."<br>";
            $field[1]->draw();
            echo "<br>";
        }
        print "<br>";
		// By Iko (2004-10-17): Language support: _LANGSUBMIT
        $this->draw_submit( _LANGSUBMIT );
		$this->draw_footer();
    }

    function process()
    {
        if( !isset( $_POST["{$this->name}_phpform_sent"] ) ) return false;
		$this->noproc = false;
        reset($this->fields);
        while( $field = each($this->fields) )
        {
            $this->fields[$field[1]->field]->process();
        }
		if( $this->noproc ) return false;
        return true;
    }

    function clear()
    {
        reset($this->fields);
        while( $field = each($this->fields) )
        {
            $this->fields[$field[1]->field]->value = "";
        }
    }
}
?>
