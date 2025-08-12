<?php
/**************************************
 * phpdbform_date                     *
 **************************************
 * Textbox control with support for   *
 * dates conversion to/from sql       *
 *                                    *
 * Paulo Assis <paulo@phpdbform.com>  *
 * 2001 - 02 - 06                     *
 **************************************/

require_once("phpdbform/phpdbform_field.php");

class phpdbform_date extends phpdbform_field {
    var $dateformat;

    function phpdbform_date( &$form, $field, $title, $dateformat )
    {
		$this->phpdbform_field( $form, $field, $title );
        if( $dateformat != "fmtUS" && $dateformat != "fmtEUR" && $dateformat != "fmtSQL" )
            $dateformat = "fmtSQL";
        $this->dateformat = $dateformat;
        // giving some extra space at the control
        $this->size = 12;
        $this->maxlength = 10;
		$this->cssclass = "fieldtextbox";
		$form->add( $this );
    }

    function get_string()
    {
        $tDate = "";
        if( strlen($this->value) == 10 )
        {
            if( $this->dateformat == "fmtUS" )
            {
                $tDate = substr( $this->value, 5, 2 ) . "/"
                        .substr( $this->value, 8, 2 ) . "/"
                        .substr( $this->value, 0, 4 );
            } else if( $this->dateformat == "fmtEUR" )
            {
                $tDate = substr( $this->value, 8, 2 ) . "/"
                        .substr( $this->value, 5, 2 ) . "/"
                        .substr( $this->value, 0, 4 );
            } else $tDate = $this->value;
        }
        if( strlen($this->onblur) ) $javascript = "onblur=\"{$this->onblur}\"";
        else $javascript="";
        if( !empty($this->title) ) $ret = $this->title."<br>";
		else $ret = "";
        return $ret."<input type=text class=\"{$this->cssclass}\" name=\"$this->key\" size=$this->size maxlength=$this->maxlength value=\"".htmlspecialchars($tDate)."\" $javascript {$this->tag_extra}>\n";
    }

    function process()
    {
        $tDate = "";
        if( isset( $_POST[$this->key] ) )
            $tDate = $_POST[$this->key];
        if( strlen($tDate) == 10 )
        {
            if( $this->dateformat == "fmtUS" )
            {
                $this->value = substr( $tDate, 6, 4 ) . "-"
                              .substr( $tDate, 0, 2 ) . "-"
                              .substr( $tDate, 3, 2 );
            } else if( $this->dateformat == "fmtEUR" )
            {
                $this->value = substr( $tDate, 6, 4 ) . "-"
                              .substr( $tDate, 3, 2 ) . "-" 
                              .substr( $tDate, 0, 2 );
            } else $this->value = $tDate;
        }
    }
}
