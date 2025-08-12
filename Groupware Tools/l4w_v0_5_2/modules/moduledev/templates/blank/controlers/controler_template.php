<?php
	
   /**
    * Controler for handling documents. 
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */
        
   /**
    *
    * @version      $Id: controler_template.php,v 1.2 2005/05/15 15:31:54 carsten Exp $
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */    
	class ###name###_script extends easy_controller {

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
        * @since        ###version###
        * @version      ###version###
        */
        function handleModel() {
            //global $easy;
                        
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

                case "add_folder":
                    $result = $this->model->add_folder ($params);
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_folder');
					break;
                case "add_entry":
                    $result = $this->model->add_entry ($params);
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');                        
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_entry');
					break;
			    case "add_folder_view":
			        $this->model->entry['parent']->set($_REQUEST['parent']);
                    $this->model->entry['command']->set('add_folder');
                    break;
			    case "add_entry_view": 
			        isset ($_REQUEST['parent']) ? $parent_id = $_REQUEST['parent'] : $parent_id = 0;
			    	$this->model->entry['parent']->set($parent_id);
                    $this->model->entry['command']->set('add_entry');
					break;
                case "add_ref_view": // read as "add as reference view" 
                    $this->model->update_filter ($params);
                    $this->model->show_entries ($params);
                    $this->model->entry['command']->set('add_ref_view');
					break;
				case "delete_entry": 
                    $result = $this->model->delete_entry ($params);
                    $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
					break;
				case "delete_selected": 
                    $this->model->entry['command']->set ('show_entries');
                    $result = $this->model->delete_entries ($params);
                    $this->model->show_entries ($params);
					break;
			    case "del_ref":
			        $this->model->delReference ($params);
			        break;
                case "edit_att_note": 
                    $this->model->entry['command']->set('update_att_note');
                    $this->model->show_entry ($params);
					break;
                case "edit_entry": 
                    $this->model->entry['command']->set('update_entry');
                    $result = $this->model->show_entry ($params);
					break;
                case "edit_folder": 
                    $this->model->entry['command']->set('update_entry');
                    $this->model->show_entry ($params);
					break;
			    case "move":
			        $result = $this->model->moveEntries ($params);
			        $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
			        break;
			    case "move_view":
			        $this->model->getFolders ($params);
			        break;
                case "show_entries": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_entries ($params);
					break;
                case "show_locked": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_locked ($params);
					break;
                case "update_entry":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        $this->model->show_entries ($params);
                    }    
	                else { // apply
	                    $this->model->entry['command']->set ('update_entry');
				    }
				    break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;
				case "clear_filter":
					$this->model->clear_filter ($params);
                    $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
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