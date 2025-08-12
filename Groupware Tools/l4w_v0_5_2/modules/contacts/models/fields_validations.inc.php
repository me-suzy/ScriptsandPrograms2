<?php

  /**
    * $Id: fields_validations.inc.php,v 1.4 2005/06/28 07:21:56 carsten Exp $
    *
    * controls additional field validation 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */
    
   /**
    * Additional field validation
    *
    * Additional validations (apart from core validation) can be defined here. 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */    
    class contacts_fields_validations {
         
      /**
        * holds the models data 
        *
        * @access private
        * @var array
        */
       //var $entry = array();  

       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
        function contacts_fields_validations () {
        }

       /**
        * User defined validation.
        *
        * minLength returns corresponding message if the lastname field is shorter than 3 letters
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
		function minLength (&$params) {
			$data = $params->entry['lastname']->data; // cannot use get here (get is calling this line actually!)
			if (strlen ($data) < 3) { 
				return "LASTNAME TOO SHORT";
			}
			return '';
		}	
    }   

?>