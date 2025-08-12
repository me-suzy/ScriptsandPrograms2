<?php
	
   /**
    * Controler for handling documents. This file contains the controler of the model-view-controller pattern used to 
    * implement the notes functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      templates
    */
        
   /**
    *
    * @version      $Id: templates_ctrl.php,v 1.1 2005/07/05 10:45:02 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      templates
    */    
	class templates_script extends easy_controller {

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

                /*case "add_folder":
                    $result = $this->model->add_folder ($params);
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        //$this->add_quicklink ($this->model);
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_folder');
					break;
                case "add_entry":
                    $result = $this->model->add_entry ($params);
                    if ($result == "success") {
                        //$this->add_quicklink ($this->model);
	                    $this->model->entry['command']->set ('show_entries');                        
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_entry');
					break;
			    case "add_folder_view":
			        (isset ($_REQUEST['parent'])) ? $use_folder = $_REQUEST['parent'] : $use_folder = 0;
			        $this->model->entry['parent']->set($use_folder);
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
                    $return_to = '';
                    if (isset($_REQUEST['return_to']))
                    	$return_to = $_REQUEST['return_to'];
                    $this->model->entry['return_to']->set($return_to);
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
			    case "export":
			        $result = $this->model->export ($params);
			        break;
			    case "export_view":
			        break;
			    case "move":
			        $result = $this->model->moveEntries ($params);
			        $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
			        break;
			    case "move_view":
			        $this->model->getFolders ($params);
			        break;
			    case "search_notes":
			        $this->model->entry['keyword']->set($params['keyword']);
			        $this->model->entry['command']->set('show_entries');
			        $this->model->search ($params);
			        break;*/
                case "show_entries": 
                    //  $this->model->entry['command']->set('show_entries');
                    //$this->model->update_filter ($params);
                    if (isset ($_REQUEST['type']))
                        $this->model->entry['type']->set($_REQUEST['type']);
                    $this->model->show_entries ($params);
					break;
                /*case "show_locked": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_locked ($params);
					break;
                case "update_att_note":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success") {
                        //$params["entry_id"] = $_REQUEST['return_to'];
                        var_dump ($_REQUEST);
	                    list ($type, $id) = explode ("_", $_REQUEST['return_to']);
	                    $path = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	                    switch ($type) {
							case 'note':
								$path .= "/../notes/index.php?command=edit_entry&entry_id=".$id;
								break;
							case 'contact':
								$path .= "/../contacts/index.php?command=show_contact&contact_id=".$id;
								break;
							case 'todo':
								$path .= "/../todos/index.php?command=edit_entry&entry_id=".$id;
								break;
							case 'ticket':
								$path .= "/../tickets/index.php?command=edit_entry&entry_id=".$id;
								break;
							default:
								die ("unknown type ($type) in ".__FILE__." (".__LINE__.")");
								break;
						}
	                    header ("Location: ".$path);
	                    die();
	                    //$this->model->entry['command']->set ('update_entry');
                        //$this->model->show_entry ($params);
                    }
                    else {
	                    $this->model->entry['command']->set ('update_att_note');
				    }
				    break;
                case "update_entry":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success") {
	                    $this->model->entry['command']->set ('show_entries');
                        $this->model->show_entries ($params);
                    }    
	                else { // apply
	                    $this->model->entry['command']->set ('update_entry');
	                    //$this->model->entry['parent']->set (0);
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
				    break;*/
				default:
					die ("unrecognized command: ".$this->model->command->get());
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
		
    } // end class

?>