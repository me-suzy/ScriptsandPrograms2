<?php
	
   /**
    * Controler for handling mandators. This file contains the controler of the model-view-controller pattern used to 
    * implement the mandators functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      mandators
    */
        
   /**
    * This class implements the controler to call the models methods and modify the model itself.
    *
    * Mandators are a basic level of access to leads4web. When you log in, you have to decide which mandator
    * you want to use and you must have the appropriate rights to gain access to this mandator. 
    *    
    *
    * @version      $Id: mandators_ctrl.php,v 1.7 2005/07/20 07:18:41 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      mandators
    */    
	class mandators_script extends easy_controller {

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
        * @since        0.5.1
        * @version      0.5.1
        */
        function handleModel() {
                        
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

			    case "add_mandator":
			        break;
                case "create_mandator":
                    $this->model->entry['name']->set_empty_allowed (false);
                    $result = $this->model->create_entry ($params);
                    if ($result == "success") {
	                    //$this->model->entry['command']->set ('show_entries');                        
                        $this->model->show_entries ($params);
	                }
					break;
				case "delete_entry":
				    $result = $this->model->deleteMandator($params);
                    $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
				    break;
			    /*case "add_entry_view": 
			        isset ($_REQUEST['parent']) ? $parent_id = $_REQUEST['parent'] : $parent_id = 0;
			    	$this->model->entry['parent']->set($parent_id);
                    $this->model->entry['command']->set('add_entry');
					break;*/
                case "show_entries": 
                    $this->model->entry['command']->set('show_entries');
                    $result = $this->model->show_entries ($params);
					break;
				case "edit_mandator":
				    $this->model->getMandator($params);
				    break;
				case "edit_users":
				    $this->model->entry['mandator_id']->set($params['mandator_id']);
				    $this->model->getUsers($params);
				    break;
				case "update_mandator":
                    $this->model->entry['name']->set_empty_allowed (false);
				    $result = $this->model->updateMandator($params);
				    if ($result == "success") { 
    				    $this->model->entry['command']->set('show_entries');
                        $this->model->show_entries($params);
				    }
				    break;
				case "updateMandatorUsers":
				    $result = $this->model->updateMandatorUsers ($params);
				    if ($result == "success") { 
    				    $this->model->entry['command']->set('show_entries');
                        $this->model->show_entries($params);
				    }				    
				    break;
                case "switch_to_mandator":
				    $result = $this->model->switchMandator ($params);
				    if ($result == "success") { 
    				    $this->model->entry['command']->set('show_entries');
                        $this->model->show_entries($params);
				    }				    
                    break;
                case "copy_from_dg":
				    $result = $this->model->copyFromDG ($params);
				    $this->model->entry['command']->set('show_entries');
                    $this->model->show_entries($params);
                	break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command ".__FILE__);
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
		
    } // end class

?>