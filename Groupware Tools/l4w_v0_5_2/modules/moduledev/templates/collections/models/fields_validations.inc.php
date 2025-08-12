<?php

   /**
    * Validation Rules for the model's fields.
    *
   /**
    * Fields used for model
    * 
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */
    
   /**
    * Additional field validation
    * The rules defined here are called automatically when defined in fields_definition.inc.php 
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */    

    class ###name###_fields_validations {
         
       /**
        * Constructor.
        *
        * empty
        * 
        * @access       public
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function ###name###_fields_validations () {
        }

       /**
        * User defined validation.
        *
        * example function
        * minLength returns corresponding message if the lastname field is shorter than 3 letters
        * 
        * @access       public
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
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