<?php

   /**
    * Validation Rules for the model's fields.
    *
    * This file contains the rules the model's fields have to follow so that the 
    * model can be validated (see function minLength as an example).
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */
    
   /**
    * Additional field validation
    * The rules defined here are called automatically when defined in fields_definition.inc.php 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      categories
    */    
    class categories_fields_validations {
         
       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
        function categories_fields_validations () {
        }

       /**
        * User defined validation.
        *
        * example function
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