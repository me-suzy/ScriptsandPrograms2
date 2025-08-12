<?php
	
   /**
    * Controler for handling documents. 
    *
    * @author       Carsten Gräf
    * @copyright    evandor media GmbH
    * @package      translations
    */
        
   /**
    *
    * @version      $Id: translations_ctrl.php,v 1.8 2005/06/04 18:15:26 carsten Exp $
    * @author       Carsten Gräf
    * @copyright    evandor media GmbH
    * @package      translations
    */    
	class translations_script extends easy_controller {

      /**
        * array holds current parameters
        *
        * @access public
        * @var array
        */  
        var $params            = null;
        
      /**
        * Handles the parameters command and view.
        *
        * Dispatches according to current command
        * 
        * @access       public
        * @since        0
        * @version      0
        */
        function handleModel() {
            //global $easy;
                        
			// === request vars ======================================
        	$params       = $this->get_params();
            $this->params =& $params;

            // === Initialization ====================================
			if (isset($params['command']))
				$this->model->command->set ($params['command']);

            $result = "success";

			// === Dispatch Part depending on command ================
			switch($this->model->command->get()){

                /*case "add_folder":
                    $result = $this->model->add_folder ($params);
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_folder');
					break;*/
				case "create_language_file":
    				$this->model->entry['lang_id']->set ($params['lang_id']);
                    $this->model->create_language_file ();                
                    break;				
                case "edit_language":
                    $this->model->entry['lang_id']->set ($params['lang_id']);
                    //$this->model->get_loaded_language();
                    $this->model->get_translations ();                
                    break;
                case "edit_text":
                    //$this->model->get_loaded_language();
                    $this->model->entry['mykey']->set($params['mykey']);
                    //$this->model->get_text ($params['mykey']);
                    break;
				case "generate_language":
    				$this->model->entry['lang_id']->set ($params['use_lang']);
                    $result = $this->model->generate_language ($params);
					break;
				case "load_existing_language":
				    $this->model->get_languages ();
				    $result = $this->model->load_language ($params);
				    break;
                case "load_lang_view":
                    $result = $this->model->get_languages ();
                    break;
				case "main_view":
					break;
				case "new_lang_view1":
    				$result = $this->model->get_languages ();
				    break;
                case "remove_language":
                    $result = $this->model->remove_language ($params);
					break;
				case "set_text":
                    //$this->model->get_loaded_language();	
                    //$this->model->get_text ($params['text']);			
				    $result = $this->model->set_text ($params);
				    if ($result == "generate") {
					    $this->model->entry['lang_id']->set ($_SESSION['language']);
	                    $result = $this->model->create_language_file ();  
				    }	
				    break;
                case "test_language":
                    $this->model->entry['lang_id']->set ($params['lang_id']);
                    $done = $this->model->create_language_file (true);                
                    //$this->model->test_language ();
                    break;
				case "update_language":
    				$this->model->entry['lang_id']->set ($params['lang_id']);
                    $result = $this->model->update_language ($params);
                    $this->model->get_translations ();                
					break;
				default:
					die ("unrecognized command (ctrl)");
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
    } // end class

?>