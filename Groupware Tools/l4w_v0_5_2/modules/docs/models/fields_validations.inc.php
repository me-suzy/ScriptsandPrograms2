<?php

  /**
    * $Id: fields_validations.inc.php,v 1.1 2004/12/28 16:59:11 carsten Exp $
    *
    * controls additional field validation 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
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
    class fields_validations {
         
       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
        function fields_validations () {
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