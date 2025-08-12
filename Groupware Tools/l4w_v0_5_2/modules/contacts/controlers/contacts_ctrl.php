<?php
	
  /**
    * $Id: contacts_ctrl.php,v 1.11 2005/06/26 16:48:25 carsten Exp $
    *
    * controler for handling contacts 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */
        
   /**
    * Contacts Controler
    *
    * controler for handling contacts 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */    
	class contacts_script extends easy_controller {

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
        * @since        0.4.0
        * @version      0.4.4
        */
        function handleModel() {
            global $easy;
                        
			// === request vars ======================================
        	$params       = $this->get_params();
            $this->params =& $params;

            // === Initialization ====================================
			if (isset($params['command']))
				$this->model->command->set ($params['command']);

			isset ($params['order'])     ? 
				$this->model->order = $params['order']    : 
				$this->model->order = 1;
			
			isset ($params['direction']) ? 
				$this->model->direction =  $params['direction'] : 
				$this->model->direction =  "";

			isset ($params['pagenr']) ? 
				$this->model->pagenr =  $params['pagenr'] : 
				$this->model->pagenr =  "";

			if (isset ($params['entries_per_page']))  
				$_SESSION['easy_datagrid']['entries_per_page'] =  $params['entries_per_page'];

            $result = "success";

			// === Dispatch Part depending on command ================
			switch($this->model->command->get()){

                case "add_contact":
                    $result = $this->model->add_contact ($params); 
                    if ($result == "success") {
                        $this->add_quicklink ($this->model);
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_contact');
					break;
                case "add_contact_view": 
                    $this->model->entry['command']->set('add_contact');
                    if (isset ($_REQUEST['email']))
                    	$this->model->entry['email']->set($_REQUEST['email']);
                    if (isset ($_REQUEST['lastname']))
                    	$this->model->entry['lastname']->set($_REQUEST['lastname']);
					break;
                case "assign_view" :
                
                	break;
				case "delete_entry": 
                    //$this->model->entry['command']->set('update_contact');
                    $result = $this->model->delete_entry ($params);
                    $this->model->show_entries ($params);
					break;
                case "export_excel": 
                    $this->model->export_excel ($params);
                    $result = $this->model->show_entries ($params);
					break;
                case "show_contact": 
                    $this->model->entry['command']->set('update_contact');
                    $result = $this->model->show_contact ($params);
                    if ($result == "failure") 
                        $this->model->show_entries ($params);
                    else
                        $this->add_quicklink ($this->model);
					break;
                case "show_entries": 
                    $this->model->update_filter ($params);
                    $this->model->entry['command']->set('show_entries');
                    $result = $this->model->show_entries ($params);
					break;
                /*case "show_locked": 
                    $this->model->update_filter ($params);
                    $result = $this->model->show_locked ($params);
					break;*/
                case "update_contact":
                    $result = $this->model->update_contact ($params); 
                    if ($result == "success")
                        $this->model->show_entries ($params);
                    elseif ($result == "apply") {
                    	$this->model->entry['command']->set('update_contact');
                        $this->model->show_contact ($params);
                    }
	                else {
	                    $this->model->entry['command']->set ('update_contact');
	                    //$this->model->entry['contact_id']->set ($params['contact_id']);					
				    }
				    break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command: ".$this->model->command->get());
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
       /**
        * Add quicklink
        *
        * Creates a quicklinkg for given entry
        * 
        * @access       private
        * @param        class current model
        * @since        0.4.0
        * @version      0.4.4
        */
		function add_quicklink (&$model) {

		    create_quicklink ('contact', 
                              $model->entry['contact_id']->get(), 
                              $model->entry['firstname']->get()." ".
                              $model->entry['lastname']->get(), 
                              'modules/contacts/index.php?command=show_contact&contact_id='.
                              $model->entry['contact_id']->get());
        }
		
    } // end class

?>