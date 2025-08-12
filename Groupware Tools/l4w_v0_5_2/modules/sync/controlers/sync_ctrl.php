<?php
	
  /**
    * $Id: sync_ctrl.php,v 1.2 2005/05/15 15:31:54 carsten Exp $
    *
    * controler for handling syncronization with other leads4web installations 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package sync
    */
        
   /**
    * Sync Controler
    *
    * controler for syncronization
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package contacts
    */    
	class sync_script extends easy_controller {

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
        * @since        0.4.6
        * @version      0.4.6
        */
        function handleModel() {
                        
			// === request vars ======================================
        	$params       = $this->get_params();
            $this->params =& $params;

            // === Initialization ====================================
			if (isset($params['command']))
				$this->model->command->set ($params['command']);

            $result = "success";

			// === Dispatch Part depending on command ================
			switch($this->model->command->get()){

                case "show_options": 
                    //$this->model->entry['command']->set('show_entries');
                    //$this->model->update_filter ($params);
                    //$result = $this->model->show_entries ($params);
					break;
			    case "syncronize":
			        $result = $this->model->syncronize ($params);
			        break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command");
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
				
    } // end class

?>