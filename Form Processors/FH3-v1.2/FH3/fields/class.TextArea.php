<?php

/**
 * class TextArea
 *
 * Create a textarea
 *
 * @author Teye Heimans
 * @package FormHandler
 * @subpackage Fields
 */
class TextArea extends Field {

    var $_iCols;   // int: number of colums which the textarea should get
    var $_iRows;   // int: number of rows which the textarea should get

    /**
     * TextArea::TextArea()
     *
     * Constructor: create a new textarea
     *
     * @param object &$oForm: The form where this field is located on
     * @param string $sName: The name of the field
     * @return TextArea
     * @author Teye Heimans
     * @access public
     */
    function TextArea( &$oform, $sName )
    {
        // call the constructor of the Field class
        parent::Field( $oform, $sName );

        $this->setCols( 40 );
        $this->setRows( 7 );
    }

    /**
     * TextArea::setCols()
     *
     * Set the number of cols of the textarea
     *
     * @param integer $iCols: the number of cols
     * @return void
     * @author Teye Heimans
     * @access public
     */
    function setCols( $iCols )
    {
        $this->_iCols = $iCols;
    }

    /**
     * TextArea::setRows()
     *
     * Set the number of rows of the textarea
     *
     * @param integer $iRows: the number of rows
     * @return void
     * @author Teye Heimans
     * @access public
     */
    function setRows( $iRows )
    {
        $this->_iRows = $iRows;
    }

    /**
     * TextArea::getField()
     *
     * Return the HTML of the field
     *
     * @return string: the html of the field
     * @author Teye Heimans
     * @access public
     */
    function getField()
    {
        return sprintf(
          '<textarea name="%s" id="%1$s" cols="%d" rows="%d"%s>%s</textarea>%s',
          $this->_sName,
          $this->_iCols,
          $this->_iRows,
          (isset($this->_iTabIndex) ? ' tabindex="'.$this->_iTabIndex.'" ' : '').
          (isset($this->_sExtra) ? ' '.$this->_sExtra :''),
          (isset($this->_mValue) ? htmlspecialchars($this->_mValue) : ''),
          (isset($this->_sExtraAfter) ? $this->_sExtraAfter :'')
        );
    }
}

?>