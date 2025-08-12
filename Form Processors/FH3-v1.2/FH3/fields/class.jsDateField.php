<?php

/**
 * class DateField
 *
 * Create a datefield with a jscalendar
 *
 * @author Teye Heimans *
 * @package FormHandler
 * @subpackage Fields
 */
class jsDateField extends DateField
{
    var $_isOk;  // boolean: is the mask containing all 3 fields (so that the js calendar can be used)

	/**
     * jsDateField::jsDateField()
     *
     * Constructor: create a new jsdatefield object
     *
     * @param object &$oForm: the form where the datefield is located on
     * @param string $sName: the name of the datefield
     * @return jsDateField
     * @author Teye Heimans
     */
	function jsDateField(&$oForm, $sName, $sDisplay = null )
	{
		// call the constructor of the datefield
		parent::DateField( $oForm, $sName, $sDisplay );

		// check if the mask contains all three fields..
		$str = $this->_getFieldsFromMask();

		// is the mask ok (can we use a js datefield ?
		$this->_isOk = ( strlen( $str ) == 3 && strpos($str, 'd') !== false && strpos($str, 'm') !== false && strpos($str, 'y') !== false );

		// when not OK, just return a datefield
		if( !$this->_isOk )
		{
		    return;
		}

		static $bSetJS = false;

    	// needed javascript included yet ?
        if(!$bSetJS)
        {
            $bSetJS = true;

            // add the needed javascript
            $oForm->_setJS(
              FH_FHTML_DIR."js/calendar_popup.js", true
            );
            $oForm->_setJS(
              "document.write(getCalendarStyles());\n".
              "function getDateString( fldYear, fldMonth, fldDay ) {\n".
              "    var frm = document.forms['".$oForm->_name."'];\n".
              "    var objY = frm.elements[fldYear];\n".
              "    var objM = frm.elements[fldMonth];\n".
              "    var objD = frm.elements[fldDay];\n".
              "    var y    = objY.options[objY.selectedIndex].value;\n".
              "    var m    = objM.options[objM.selectedIndex].value;\n".
              "    var d    = objD.options[objD.selectedIndex].value;\n".
              "    if (y=='' || m== '') { \n".
              "        return null;\n".
              "    }\n".
              "    if (d=='') { \n".
              "        d = 1;\n".
        	  "    }\n".
        	  "    return y+'-'+m+'-'+d;\n".
        	  "}\n"
            );
        }

        list( $iStart, $iEnd ) = $this->_getYearInterval();

        // add the javascript needed for the js calendar field
        $oForm->_setJS(
          "// create popup calendar\n".
          "if( document.getElementById('".$sName."_div') ) \n".
          "{\n".
		  "    var cal_".$sName." = new CalendarPopup('".$sName."_div');\n".
		  "   cal_".$sName.".showYearNavigation();\n".
		  "   cal_".$sName.".showYearNavigationInput();\n".
		  "   cal_".$sName.".setReturnFunction('set".$sName."Values');\n".
		  "   cal_".$sName.".addDisabledDates(null,'Dec 31, ".(date('Y')-$iStart-1)."');\n".
		  "   cal_".$sName.".addDisabledDates('Jan 1, ".(date('Y')+$iEnd+1)."',null);\n".
		  "   function set".$sName."Values(y,m,d) {\n".
		  "       document.forms['".$oForm->_name."'].elements['".$sName."_day'].value   = LZ(d);\n".
		  "       document.forms['".$oForm->_name."'].elements['".$sName."_month'].value = LZ(m);\n".
		  "       document.forms['".$oForm->_name."'].elements['".$sName."_year'].value  = y;\n".
		  //"    cal_".$sName.".setDate(
		  "   }\n".
		  "}\n"
		);
	}

	/**
     * DateField::getField()
     *
     * return the field
     *
     * @return string: the field
     * @author Teye Heimans
     * @access public
     */
	function getField()
	{
		$html = parent::getField();

		// when not OK, just return a datefield
		if( !$this->_isOk )
		{
		    return $html;
		}

		$html .=
		"<a href='javascript:;' ".
		"onclick=\"if( cal_".$this->_sName." ) cal_".$this->_sName.".showCalendar('anchor_".$this->_sName."', getDateString('".$this->_sName."_year', '".$this->_sName."_month', '".$this->_sName."_day')); return false;\" ".
		" name='anchor_".$this->_sName."' id='anchor_".$this->_sName."'>".
		"<img src='".FH_FHTML_DIR."images/calendar.gif' border='0' alt='Select Date' /></a>\n".
		"<div id='".$this->_sName."_div' ".
		" style='position:absolute;visibility:hidden;background-color:white;layer-background-color:white;'></div>\n";

 	    return $html;
	}
}
?>