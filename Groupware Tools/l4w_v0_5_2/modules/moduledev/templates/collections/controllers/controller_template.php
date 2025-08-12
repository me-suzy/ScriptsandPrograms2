<?php
	
   /**
    * Controller for handling ###name###.
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */
        
   /**
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */    
	class ###name###_script extends easy_controller {
        
      /**
        * Handles the parameters command and view.
        *
        * Dispatches according to current command
        * 
        * @access       public
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function handleModel() {

            // === Initialization ====================================
			if (isset($_REQUEST['command']))
				$this->model->command->set ($_REQUEST['command']);

			$this->model->order = new easy_integer (1,0);
			/*isset ($_REQUEST['order'])     ? 
				$this->model->order = $_REQUEST['order']    : 
				$this->model->order = 1;*/
			if (isset($_REQUEST['order']))
    			$this->model->order->set ($_REQUEST['order']);
			
			isset ($_REQUEST['direction']) ? 
				$this->model->direction =  $_REQUEST['direction'] : 
				$this->model->direction =  "";

			isset ($_REQUEST['pagenr']) ? 
				$this->model->pagenr =  $_REQUEST['pagenr'] : 
				$this->model->pagenr =  "";

			if (isset ($_REQUEST['entries_per_page']))  
				$_SESSION['easy_datagrid']['entries_per_page'] =  $_REQUEST['entries_per_page'];

            $result = "success";

			// === Dispatch Part depending on command ================
			switch($this->model->command->get()){

                case "add_###name###": 
                	(isset ($_REQUEST['parent'])) ? $use_folder = $_REQUEST['parent'] : $use_folder = 0;
			        $this->model->entry['parent']->set($use_folder);
                    $this->model->entry['command']->set('add_###name###');
					break;
                case "create_###name###": 
                    $result = $this->model->create_###name### ();
                    if ($result == "success") {
                        //$this->add_quicklink ($this->model);
                        $this->model->show_entries ($_REQUEST);
	                }
					break;
                case "edit_###name###": 
                    $this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $this->model->show_entry ($_REQUEST);
					break;
                case "update_###name###":
                	$this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $result = $this->model->update_entry (); 
                    if ($result == "success")
                        $this->model->show_entries ($_REQUEST);
				    break;
				case "delete_entry": 
				    $this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $result = $this->model->delete_entry ($_REQUEST);
                    $this->model->entry['parent']->set(0);
                    $this->model->show_entries ($_REQUEST);
					break;

			    case "add_folder":
			        (isset ($_REQUEST['parent'])) ? $use_folder = $_REQUEST['parent'] : $use_folder = 0;
			        $this->model->entry['parent']->set($use_folder);
                    $this->model->entry['command']->set('add_folder');
                    break;			        
                case "create_folder":
                    $result = $this->model->create_folder ($_REQUEST);
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        $this->model->show_entries ($_REQUEST);
	                }
	                else
                        $this->model->entry['command']->set('create_folder');
					break;
                case "edit_folder": 
                    $this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $this->model->show_entry ($_REQUEST);
					break;
                case "update_folder":
                	$this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $result = $this->model->update_entry (); 
                    if ($result == "success")
                        $this->model->show_entries ($_REQUEST);
				    break;
                
                case "show_entries": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($_REQUEST);
                    $result = $this->model->show_entries ($_REQUEST);
					break;

			    case "copy_from_dg":
				    $result = $this->model->copyFromDG ($_REQUEST);
				    $this->model->entry['command']->set('show_entries');
                    $this->model->show_entries($_REQUEST);
                	break;
                
               /*case "add_ref_view": 
                    $this->model->show_entries ($_REQUEST);
                    $this->model->entry['command']->set('add_references');
					break;*/
				case "unset_current_view":
                    $this->model->unset_view ($_REQUEST);
				    break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command (".$this->model->command->get().")");
			} // switch
			
			$this->setViewByTransition ($_REQUEST['command'], $result);	
					
		} // end handleModel        
				
    } // end class

?>