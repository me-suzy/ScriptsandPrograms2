<?php
/**
 * class DateField
 *
 * Create a datefield
 *
 * @author Teye Heimans
 * @package FormHandler
 * @subpackage Fields
 */

class DateField extends Field
{
    var $_sDisplay;  // string: how to display the fields (d-m-y) or other
    var $_oDay;      // SelectField: object of the day selectfield
    var $_oMonth;    // SelectField: object of the month selectfield
    var $_oYear;     // SelectField: object of the year selectfield
    var $_sInterval; // string: interval of the year
    var $_bRequired; // boolean: if the field is required or if we have to give the option to leave this field empty

    /**
     * DateField::DateField()
     *
     * Constructor: create a new datefield object
     *
     * @param object &$oForm: the form where the datefield is located on
     * @param string $sName: the name of the datefield
     * @return DateField
     * @access public
     * @author Teye Heimans
     */
    function DateField(&$oForm, $sName, $sDisplay = null )
    {
        // set the default date display
        if( !is_null( $sDisplay ) )
        {
            $this->setDisplay( $sDisplay );
        }
        // no display set... use the default
        else
        {
            $this->setDisplay( FH_DATEFIELD_DEFAULT_DISPLAY );
        }

        // set the default interval
        $this->setInterval( FH_DATEFIELD_DEFAULT_DATE_INTERVAL );

        // set if the field is required
        $this->setRequired( FH_DATEFIELD_DEFAULT_REQUIRED );

        // the day, month and year fields
        $this->_oDay   =& new SelectField($oForm, $sName.'_day');
        $this->_oMonth =& new SelectField($oForm, $sName.'_month');
        $this->_oYear  =& new SelectField($oForm, $sName.'_year');

        parent::Field( $oForm, $sName );
    }

    /**
     * DateField::setRequired()
     *
     * Set if the datefield is required or if we have to give the user
     * the option to select empty value
     *
     * @param boolean $bStatus: the status
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function setRequired( $bStatus )
    {
        $this->_bRequired = $bStatus;
    }

    /**
     * DateField::setDisplay()
     *
     * Set the display of the fields
     * (use d,m and y for positioning, like "d-m-y", or "y/m/d" )
     *
     * @param string $sDisplay: how we have to display the datefield (day-month-year combination)
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function setDisplay( $sDisplay )
    {
        $this->_sDisplay = strtolower( $sDisplay );
    }

    /**
     * DateField::setInterval()
     *
     * Set the year range of the years
     * The interval between the current year and the years to start/stop.
     * Default the years are beginning at 90 yeas from the current. It is also possible to have years in the future.
     * This is done like this: "90:10" (10 years in the future).
     *
     * @param string/int $sInterval: the interval we should use
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function setInterval( $sInterval )
    {
        $this->_sInterval = $sInterval;
    }

    /**
     * DateField::setExtra()
     *
     * Set some extra tag information of the fields
     *
     * @param string $sExtra: The extra information to inglude with the html tag
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function setExtra( $sExtra )
    {
    	$this->_oDay->setExtra   ( $sExtra );
    	$this->_oMonth->setExtra ( $sExtra );
    	$this->_oYear->setExtra  ( $sExtra );
    }

    /**
     * DateField::getValue()
     *
     * return the value of the field (in d-m-Y format!)
     *
     * @return string: the current value of the field
     * @access public
     * @author Teye Heimans
     */
    function getValue()
    {
        $d = $this->_oDay->getValue();
        $m = $this->_oMonth->getValue();
        $y = $this->_oYear->getValue();

        return $this->_fillMask( $d, $m, $y);
    }

    /**
     * DateField::getAsArray()
     *
     * Get the date value as an array: array(y,m,d)
     *
     * @return array
     * @access public
     * @author Teye Heimans
     * @since 25/11/2005
     */
    function getAsArray()
    {
        $d = $this->_oDay->getValue();
        $m = $this->_oMonth->getValue();
        $y = $this->_oYear->getValue();

        return array( $y, $m, $d );
    }

    /**
     * DateField::isValid()
     *
     * Check if the date is valid (eg not 31-02-2003)
     *
     * @return boolean: true if the field is correct, false if not
     * @access public
     * @author Teye Heimans
     */
    function isValid()
    {
    	// the result has been requested before..
    	if( isset($this->_isValid))
    	{
    		return $this->_isValid;
    	}

    	// first of al check if the date is right when a valid date is submitted
    	// (but only when all fields are displayed (d m and y in the display string!)
    	if( strpos( $this->_sDisplay, 'd') !== false &&
    	    strpos( $this->_sDisplay, 'm') !== false &&
    	    strpos( $this->_sDisplay, 'y') !== false &&
    	    ($this->_oDay->getValue() != '00' && $this->_oDay->getValue() != "") &&
    	    ($this->_oMonth->getValue() != '00' && $this->_oMonth->getValue() != "") &&
    	    ($this->_oYear->getValue() != '0000' && $this->_oYear->getValue() != "") &&
            !checkdate(
              $this->_oMonth->getValue(),
              $this->_oDay->getValue(),
              $this->_oYear->getValue()
            ))
        {
        	$this->_sError = $this->_oForm->_text( 13 );
            $this->_isValid = false;
            return $this->_isValid;
        }

        // if validator given, check the value with the validator
    	if(isset($this->_sValidator) && !empty($this->_sValidator))
    	{
    		$this->_isValid = parent::isValid();
    	}
    	// no validator is given.. value is always valid
    	else
    	{
    		$this->_isValid = true;
    	}

    	return $this->_isValid;
    }

    /**
     * DateField::getField()
     *
     * return the field
     *
     * @return string: the field
     * @access public
     * @author Teye Heimans
     */
    function getField()
    {
        // set the date when:
        // - the field is empty
    	// - its not an edit form,
    	// - the form is not posted
    	// - the field is required
    	// - there is no value set...
    	if( !$this->_oForm->isPosted() && !$this->_oForm->edit && $this->getValue() == $this->_fillMask() && $this->_bRequired )
    	{
    		// set the current date if wanted
    		if( FH_DATEFIELD_SET_CUR_DATE )
    		{
    			$this->setValue( date('d-m-Y') );
    		}
    		// we dont have to set the current date.. just set the current year..
    		else
    		{
    			$this->setValue( date('01-01-Y') );
    		}
    	}

    	// get the year interval
    	list( $iStart, $iEnd ) = $this->_getYearInterval();

        // get the days, months and years
        $aDays = array();
        if(!$this->_bRequired)
        {
            $aDays['00'] = '';
        }
        for($i = 1; $i <= 31; $i++)
        {
            $aDays[sprintf('%02d', $i)] = sprintf('%02d', $i);
        }

        // set the months in the field
        $aMonths = array(
          '01' => $this->_oForm->_text( 1 ),
          '02' => $this->_oForm->_text( 2 ),
          '03' => $this->_oForm->_text( 3 ),
          '04' => $this->_oForm->_text( 4 ),
          '05' => $this->_oForm->_text( 5 ),
          '06' => $this->_oForm->_text( 6 ),
          '07' => $this->_oForm->_text( 7 ),
          '08' => $this->_oForm->_text( 8 ),
          '09' => $this->_oForm->_text( 9 ),
          '10' => $this->_oForm->_text( 10 ),
          '11' => $this->_oForm->_text( 11 ),
          '12' => $this->_oForm->_text( 12 )
        );
        if(!$this->_bRequired)
        {
            $aMonths['00'] = '';
            ksort($aMonths);
        }

        // set the years
        $aYears = array();
        if(!$this->_bRequired)
        {
            $aYears['0000'] = '';
        }

        // years, from high to low
        for($i = date('Y') + intval($iEnd); $i > date('Y') - intval($iStart); $i-- )
        {
            $aYears[$i] = $i;
        }

        //for($i = date('Y') - intval($iStart); $i <= date('Y') + intval($iEnd); $i++) {
        //    $aYears[$i] = $i;
        //}

        // set the options in the selectfields
        $this->_oDay   -> setOptions ( $aDays );
        $this->_oMonth -> setOptions ( $aMonths );
        $this->_oYear  -> setOptions ( $aYears );


        // replace the values by the fields..
        return $this->_fillMask(
          ' '.$this->_oDay->getField().' ',
          ' '.$this->_oMonth->getField().' ',
          ' '.$this->_oYear->getField().' '
        ) .
        (isset($this->_sExtraAfter) ? $this->_sExtraAfter :'');
    }

    /**
     * DateField::setValue()
     *
     * Set the value of the field. The value can be 4 things:
     * - "d-m-Y" like 02-04-2004
     * - "Y-m-d" like 2003-12-24
     * - Unix timestamp like 1104421612
     * - Mask style. If you gave a mask like d/m/y, this is valid: 02/12/2005
     *
     * @param string $sValue: the time to set the current value
     * @return void
     * @access public
     * @author Teye Heimans
     */
    function setValue( $sValue )
    {
    	// remove the time part if the date is coming from a datetime field
    	$aMatch = array();
    	if( preg_match('/^([0-9]{4}-[0-9]{2}-[0-9]{2}) [0-9]{2}:[0-9]{2}:[0-9]{2}$/', $sValue, $aMatch) )
    	{
    		$sValue = $aMatch[1];
    	}

    	// replace the d, m and y values
    	$regex = $this->_fillMask( '%%2%%', '%%2%%', '%%4%%' );

       	// next, escape dangerous characters for the regex
    	$metachar = array( '\\',   '/',  '^',  '$',  '.',  '[',  ']',  '|',  '(',  ')',  '?',  '*',  '+',  '{',  '}' );
    	$escape   = array( '\\\\', '\/', '\^', '\$', '\.', '\[', '\]', '\|', '\(', '\)', '\?', '\*', '\+', '\{', '\}' );
    	$regex    = str_replace( $metachar, $escape, $regex );

    	// now add the (\d+) for matching the day, month and year values
    	$regex = str_replace('%%2%%', '(\d+{,2})', $regex );
    	$regex = str_replace('%%4%%', '(\d+{,4})', $regex );
    	$regex = '/'.$regex.'/';

    	// now find the results
    	$match = array();
    	if( preg_match($regex, $sValue, $match ) )
    	{
    	    // get the fields from the mask
    	    $str = $this->_getFieldsFromMask();

    	    // get the length of the buffer (containing the dmy order)
    	    $len = strlen( $str );

    	    // save the results in the vars $d, $m and $y
    	    for( $i = 0; $i < $len; $i++ )
    	    {
    	        $c  = $str{$i};
    	        $$c = $match[$i+1];
    	    }
    	}
    	// the given value does not match the mask... is it dd-mm-yyyy style ?
    	elseif( preg_match( '/^(\d{2})-(\d{2})-(\d{4})$/', $sValue, $match ) )
    	{
    	    $d = $match[1];
    	    $m = $match[2];
    	    $y = $match[3];
    	}
    	// is the given value in yyyy-mm-dd style ?
    	elseif( preg_match( '/^(\d{4})-(\d{2})-(\d{2})$/', $sValue, $match ) )
    	{
    	    $d = $match[3];
    	    $m = $match[2];
    	    $y = $match[1];
    	}
    	// is the given value a timestamp ?
    	elseif( strlen( $sValue ) >= 8 && Validator::IsDigit($sValue) )
    	{
    	    $d = date('d', $sValue );
    	    $m = date('m', $sValue );
    	    $y = date('y', $sValue );
    	}

    	// save the dates for the fields
        $this->_oDay->setValue  ( isset($d) ? $d : '0' );
        $this->_oMonth->setValue( isset($m) ? $m : '0' );
        $this->_oYear->setValue ( isset($y) ? $y : '0' );
    }


    /**
     * DateField::_getFieldsFromMask()
     *
     * Get the fields from the mask.
     * For example: "select the \da\y: d" will result in "d".
     * "y/m/d" will result in "ymd"
     *
     * @param string $mask: The mask where we should get the fields from
     * @return string
     * @access private
     * @author Teye Heimans
     */
    function _getFieldsFromMask( $mask = null)
    {
        // when no mask is given, use the default mask
        if( is_null( $mask ) )
        {
            $mask = $this->_sDisplay;
        }

        // buffer
	    $str = '';
	    $len = strlen( $mask );

	    // walk each character in the mask
	    for( $i = 0; $i < $len; $i++ )
	    {
	        // get the character
	        $c = $mask{ $i };

	        // day, month or year ?
    	    if( $c == 'd' || $c == 'm' || $c == 'y' )
    	    {
	           // not the first char ?
	           if( $i != 0 )
	           {
	               // was the char not escaped?
	               if( $mask{ $i - 1 } != '\\' )
	               {
	                   $str .= $c;
	               }
	           }
	           // the first char
	           else
	           {
	               // just add it to the buffer
	               $str .= $c;
	           }
    	    }
	    }

	    return $str;
    }

    /**
     * DateField::_fillMask()
     *
     * Return the mask filled with the given values
     *
     * @param string $d: The replacement for the "d"
     * @param string $m: The replacement for the "d"
     * @param string $y: The replacement for the "d"
     * @return string
     * @access private
     * @author Teye Heimans
     */
    function _fillMask( $d = '', $m = '', $y = '', $mask = null )
    {
        // when no mask is given, use the default mask
        if( is_null( $mask ) )
        {
            $mask = $this->_sDisplay;
        }

        // make sure that the fields are not replacing other fields characters
        // and that escaped chars are possible, like "the \da\y is: d"
        $len = strlen( $mask );
        $str = '';
        for( $i = 0; $i < $len; $i++ )
        {
            $c = $mask{$i};

            // field char ?
            if( $c == 'd' || $c == 'm' || $c == 'y' )
            {
                // first char ?
                if( $i == 0 )
                {
                    $str .= '%__'.$c.'__%';
                }
                else
                {
                    // check if the char is escaped.
                    if( $mask{$i - 1} == '\\' )
                    {
                        // the char is escaped, display the char without slash
                        $str = substr($str, 0, -1).$c;
                    }
                    // the char is not escaped
                    else
                    {
                        $str .= '%__'.$c.'__%';
                    }
                }
            }
            else
            {
                $str .= $c;
            }
        }

        // replace the values by the new values
        return str_replace(
          array('%__d__%','%__m__%','%__y__%'),
          array( $d, $m, $y ),
          $str
        );
    }

    /**
     * DateField::_getYearInterval()
     *
     * Get the year interval
     *
     * @return array
     * @access private
     * @author Teye Heimans
     */
    function _getYearInterval ()
    {
    	$sInterval = $this->_sInterval;

        // get the year interval for the dates in the field
        if( strpos($sInterval, ':') )
        {
             list( $iStart, $iEnd ) = explode( ':', $sInterval, 2 );
        }
        // no splitter found, just change the start interval
        elseif( is_string($sInterval) || is_integer($sInterval) && !empty($sInterval) )
        {
            $iStart = $sInterval;
            $iEnd = 0;
        }
        // no interval given.. use the default
        else
        {
            $iStart = 90;
            $iEnd = 0;
        }

        return array( $iStart, $iEnd );
    }
}

?>