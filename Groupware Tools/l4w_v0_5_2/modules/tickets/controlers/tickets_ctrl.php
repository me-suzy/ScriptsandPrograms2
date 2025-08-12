<?php
	
  /**
    * $Id: tickets_ctrl.php,v 1.16 2005/07/28 14:49:57 carsten Exp $
    *
    * controler for handling tickets
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package tickets
    */
        
   /**
    * Tickets Controler
    *
    * controler for handling tickets
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package tickets
    */    
	class tickets_script extends easy_controller {

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
        * @since        0.4.4
        * @version      0.4.4
        */
        function handleModel() {
            //global $easy;
                        
			// === request vars ======================================
        	$params       = $this->get_params();
            $this->params =& $params;

            // === Initialization ====================================
			if (isset($_REQUEST['command']))
				$this->model->command->set ($_REQUEST['command']);

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
                        //$this->add_quicklink ($this->model);
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_folder');
					break;
			    case "add_folder_view": 
			    	isset($_REQUEST['parent']) ? $parent = $_REQUEST['parent'] : $parent = 0;
			    	$this->model->entry['parent']->set($parent);
                    $this->model->entry['command']->set('add_folder');
					break;
                case "add_ticket":
                	//$this->model->entry['contact']->set ($_REQUEST['contact']);
                    $result = $this->model->add_ticket ();
                    if ($result == "success") {
                        $this->model->show_entries ($params);
	                }
	                //else
                    //    $this->model->entry['command']->set('add_ticket');
					break;
			    case "add_note_att":
                    $result = $this->model->add_entry ($params);
                    if ($result == "success") {
                        //$this->add_quicklink ($this->model);
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_note_att');
					break;
                case "add_note_att_view": 
                    //if (isset($_REQUEST['ref_object_type'])) 
                        $this->model->entry['ref_object_type']->set($_REQUEST['ref_object_type']);
                    //if (isset($_REQUEST['ref_object_id'])) 
                        $this->model->entry['ref_object_id']->set($_REQUEST['ref_object_id']);
                    //if (isset ($_REQUEST['ref_type']))
                        $this->model->entry['ref_type']->set($_REQUEST['ref_type']);
                    $this->model->entry['command']->set('add_note_att');
					break;
			    case "add_ticket_view": 
    			    isset ($_REQUEST['parent']) ? $parent_id = $_REQUEST['parent'] : $parent_id = 0;
    			    $this->model->entry['parent']->set($parent_id);
                    if (isset ($_REQUEST['contact'])) 
                        $this->model->entry['contact']->set($_REQUEST['contact']);
    			    $this->model->entry['command']->set('add_ticket');
					break;
                case "add_ref_view": 
                    $this->model->show_entries ($params);
                    $this->model->entry['command']->set('add_references');
					break;
			    /*case "add_references":
                    $this->model->entry['command']->set('add_references');
                    $result = $this->model->add_references ($params);			    
                    $this->model->show_entries ($params);
                    break;*/
				case "delete_entry": 
                    $result = $this->model->delete_entry ($params);
                    $this->model->show_entries ($params);
					break;
				case "delete_selected": 
                    $result = $this->model->delete_entries ($params);
                    $this->model->show_entries ($params);
					break;
			    case "del_ref":
			        $this->model->delReference ($params);
			        break;
                case "edit_att_entry": 
                    $this->model->entry['command']->set('update_entry');
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
                case "edit_folder": 
                    $this->model->entry['command']->set('update_entry');
                    $this->model->show_entry ($params);
					break;
				case "move":
			        $result = $this->model->moveEntries ($params);
                    $this->model->show_entries ($params);
			        break;
			    case "move_view":
			        $this->model->getFolders ($params);
			        break;	
			    case "adjust_template":
			    	$this->model->adjust_template ($params);
			    	$this->model->show_entries ($params);
			    	break;
			    case "save_as_template":
			        $this->model->serialize($params, $this->model->entry_type, "theme");
                    $this->model->show_entries ($params);
			        break;
			    case "serialize":
			        $this->model->serialize($params, $this->model->entry_type, "theme");
                    $this->model->show_entries ($params);
			        break;
                case "show_entries": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($_REQUEST);
                    $result = $this->model->show_entries ($params);
					break;
                case "show_locked": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_locked ($params);
					break;
                case "update_entry":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success")
                        $this->model->show_entries ($params);
	                else {
	                    $this->model->entry['command']->set ('update_entry');
	                    $this->model->entry['contact']->set ($params['contact']);	
	                    $this->model->entry['parent']->set  (0);	                    				
				    }
				    break;
				case "unserialize":
				    // get original query for contacts (as in fields_definition.inc.php)
			        $query = $this->model->entry['contact']->getQuery ();
			        $this->model->unserialize($params);
			        // override entry['contact']
			        $res   = mysql_query ($query);
                    $this->model->entry['contact']->fillFromResultSet ($res, $query);
                    // override due date
                    $this->model->entry['due'] = new easy_date (time() + (60*60*24*7));      
                    $this->model->entry['command']->set ("add_ticket");
			        break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;
				case "clear_filter":
					$this->model->clear_filter ($params);
                    $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
				    break;
				case "copy_from_dg":
				    $result = $this->model->copyFromDG ($_REQUEST);
				    $this->model->entry['command']->set('show_entries');
                    //$this->model->update_filter ($_REQUEST);
                    $result = $this->model->show_entries ($params);
                	break;									    
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command (".$this->model->command->get().")");
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
        * @since        0.4.4
        * @version      0.4.4
        */
		function add_quicklink (&$model) {

		    create_quicklink ('document', 
                              $model->entry['note_id']->get(), 
                              $model->entry['name']->get(), 
                              'modules/tickets/index.php?command=show_note&note_id='.
                              $model->entry['note_id']->get());
        }
		
    } // end class

?>