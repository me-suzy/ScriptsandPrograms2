<?php

  /**
    * $Id: fields_validations.inc.php,v 1.3 2005/07/28 05:58:09 carsten Exp $
    *
    * controls additional field validation 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package notes
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
    class tickets_fields_validations {
         
       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        0.4.0
        * @version      0.4.4
        */
        function tickets_fields_validations () {
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
		function contactGiven (&$params) {
			$data = $params->entry['contact']->data; // cannot use get here (get is calling this line actually!)
			if (!$data > 0) {
	            $this->entry['contact']->class = "alert";
				return translate ('ticket has to belong to contact')."<br>";
			}
			
			return '';
		}	
    }   

?>