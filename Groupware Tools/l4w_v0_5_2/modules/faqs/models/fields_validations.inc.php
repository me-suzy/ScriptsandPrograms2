<?php

  /**
    * $Id: fields_validations.inc.php,v 1.1 2005/03/29 15:35:34 carsten Exp $
    *
    * controls additional field validation 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      notes
    */
    
   /**
    * Additional field validation
    *
    * Additional validations (apart from core validation) can be defined here. 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
    */    
    class todos_validations {
         
       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
        function todos_validations () {
        }

       /**
        * User defined validation.
        *
        * Check date
        * 
        * @access       public
        * @since        0.4.7
        * @version      0.4.7
        */
		function getDateParts ($dateStr) {	
		    	
		    $day   = null;
		    $month = null;
		    $year  = null;	
		    	
			switch (DATE_FORMAT) {
                case 'd.m.Y':
                    $tmp   = explode (".", $dateStr);
                    $day   = @$tmp[0];
                    $month = @$tmp[1];
                    $year  = @$tmp[2];
                    break;
                case 'Y-m-d':    
                    $tmp   = explode ("-", $dateStr);
                    $day   = @$tmp[2];
                    $month = @$tmp[1];
                    $year  = @$tmp[0];
                    break;
                case 'Ymd':    
                    $day   = substr ($dateStr,0,4);
                    $month = substr ($dateStr,4,2);
                    $year  = substr ($dateStr,6,2);
                    break;
                case 'm/d/Y':    
                    $tmp   = explode ("/", $dateStr);
                    $day   = @$tmp[1];
                    $month = @$tmp[0];
                    $year  = @$tmp[2];
                    break;
                default: 
                    assert ('strlen("DATE_FORMAT NOT SUPPORTED") == 0');
                    break;
            }

            return array ($day, $month, $year);
        }
        
       /**
        * User defined validation.
        *
        * Check date
        * 
        * @access       public
        * @since        0.4.7
        * @version      0.4.7
        */
		function checkDueDate (&$params) {			
			if (!isset ($_REQUEST['due'])) 
			    return '';
			
			$value = $_REQUEST['due'];
			if ($value == '') return '';
			
            list ($day, $month, $year) = call_user_func (array ('todos_validations', 'getDateParts'), $value);

			if (!@checkdate($month, $day, $year)) {
				return "DATE NOT VALID";
			}
			return '';
		}	

       /**
        * User defined validation.
        *
        * Check date
        * 
        * @access       public
        * @since        0.4.7
        * @version      0.4.7
        */
		function checkStartsDate (&$params) {			
			if (!isset ($_REQUEST['starts'])) 
			    return '';
			
			$value = $_REQUEST['starts'];
			if ($value == '') return '';
			
			list ($day, $month, $year) = call_user_func (array ('todos_validations', 'getDateParts'), $value);
            if (!@checkdate($month, $day, $year)) {
				return "DATE NOT VALID";
			}
			return '';
		}	
    }   

?>