<?php
/**************************************
 * phpdbform_textbox                  *
 **************************************
 * Textbox control                    *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 06                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_textbox extends phpdbform_field {

    function phpdbform_textbox( &$form, $field, $title, $size=0, $maxlength=0 )
    {
		$this->phpdbform_field( $form, $field, $title );
		if( $form->hasdblink )
		{
			if( $size == 0 ) $size = $form->dbfields[$field]["maxlength"];
			if( $maxlength == 0 ) $maxlength = $form->dbfields[$field]["maxlength"];
		}
        $this->size = $size;
        $this->maxlength = $maxlength;
		$this->cssclass = "fieldtextbox";
		$form->add( $this );
    }

	function get_string()
	{
        if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
        else $javascript="";
        if( !empty($this->title) ) $title = $this->title."<br>";
		else $title = "";
        if( $this->maxlength > 0 ) $maxlength = "maxlength={$this->maxlength}";
        else $maxlength = "";
        return $title."<input type=text class=\"{$this->cssclass}\" name=\"{$this->key}\" size={$this->size} $maxlength value=\"".htmlspecialchars($this->value)."\" $javascript {$this->tag_extra}>\n";
	}

    function process()
    {
		if( !$this->process ) return;
        if( isset( $_POST[$this->key] ) )
		{
            $this->value = $_POST[$this->key];
            $this->delmagic();
        }
    }
}
