<?php
/**************************************
 * phpdbform_password                 *
 **************************************
 * Password control                   *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 07                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_password extends phpdbform_field {

    function phpdbform_password( &$form, $field, $title, $size, $maxlength )
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
        return $title."<input type=password class=\"{$this->cssclass}\" name=\"{$this->key}\" size={$this->size} $maxlength value=\"".htmlspecialchars($this->value)."\" $javascript {$this->tag_extra}><br>\n";
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
