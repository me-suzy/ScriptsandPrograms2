<?php
	
   /**
    * Controller for handling categories.
    *
    * This file contains the controler of the model-view-controller pattern used to 
    * implement the categories' functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */
        
   /**
    * This class implements the controller to call the models methods and modify the model itself.
    * Categories can be attached to any entry in leads4web as a kind of "flag"
    *
    * @version      $Id: collections_ctrl.php,v 1.9 2005/07/20 07:18:41 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */    
	class collections_script extends easy_controller {
        
      /**
        * Handles the parameters command and view.
        *
        * Dispatches according to current command
        * 
        * @access       public
        * @since        0.4.4
        * @version      0.5.2
        */
        function handleModel() {

        	// === Initialization ====================================
			if (isset($_REQUEST['command']))
				$this->model->command->set ($_REQUEST['command']);

			$this->model->order = new easy_integer (1,0);	// todo: Find a priciple for handling orders, directions,... 
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

                case "add_category": 
                	(isset ($_REQUEST['parent'])) ? $use_folder = $_REQUEST['parent'] : $use_folder = 0;
			        $this->model->entry['parent']->set($use_folder);
                    $this->model->entry['command']->set('add_category');
					break;
                case "create_category": 
                	//$this->model->entry['name']->set_empty_allowed (false);
                    $result = $this->model->create_category ();
                    if ($result == "success") {
                        //$this->add_quicklink ($this->model);
                        $this->model->show_entries ($_REQUEST);
	                }
					break;
                case "edit_category": 
                    $this->model->entry['collection_id']->set($_REQUEST['entry_id']);
                    $this->model->show_entry ($_REQUEST);
					break;
                case "update_category":
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
                	$this->model->entry['name']->set_empty_allowed (false);
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