<?php
	
  /**
    * $Id: workflow_ctrl.php,v 1.9 2005/07/26 13:23:12 carsten Exp $
    *
    * Controler for supersede. See easy_framework for more information
    * about controlers and models.
    * @package workflow
    */
    
  /**
    *
    * Supersede Controler Class
    * @package workflow
    */
	class workflow_script extends easy_controller {
		
        var $params            = null;
        
      /**
        * Handles the parameters command and view.
        *
        * Order, direction, pagenr and entries_per_page are
        * used to manage the datagrid to display the tables with
        * the databases entries.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
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

                case "add_status":
                    $this->model->entry['type']->disabled=true;
                    if (isset($params['reference']))
                        $this->model->entry['type']->setDefault($params['reference']); 
					break;
                case "add_transition":
                    $this->model->entry['reference']->set($_REQUEST['reference']);
                    $this->model->entry['type']->disabled=true;
                    if (isset($params['reference']))
                        $this->model->entry['type']->setDefault($params['reference']); 
					$query = "
				            SELECT status, name FROM ".TABLE_PREFIX."states s
				            WHERE s.mandator=".$_SESSION['mandator']." AND
				                  s.reference='".$this->model->entry['reference']->get()."' 
				        ";
				    $res   = mysql_query ($query);
				    $this->model->entry['state_new']->fillFromResultSet ($res, $query);
					break;
                case "create_status": 
                    $result = $this->model->createStatus ($params);
                    if ($result == "success") {
                        $params['reference'] = $this->model->entry['type']->get();
                        $this->model->show_states ($params);                        
                    }    
					break;
                case "create_transition": 
                    $result = $this->model->createTransition ($params);
                    if ($result == "success") {
                        $params['reference'] = $this->model->entry['type']->get();
                        $this->model->show_transitions ($params);                        
                    }    
					break;
                case "delete_transition":
                    //$this->model->entry['type']->set($_REQUEST['type']);
                    $this->model->entry['reference']->set($_REQUEST['type']);
                    $this->model->entry['usr']->set($_REQUEST['usr']);
                    $this->model->entry['grp']->set($_REQUEST['grp']);
					$result = $this->model->deleteTransition ();
                    $this->model->show_transitions ($params);
					break;						
                case "edit_status":
                    $this->model->entry['type']->disabled=true;
                    if (isset($params['reference']))
                        $this->model->entry['type']->setDefault($params['reference']); 
                    $this->model->getStatus($params);
                    break;
                case "update_status":
                    $this->model->entry['type']->disabled=true;
                    $result = $this->model->updateStatus();
                    $this->model->show_states ($params);
                    break;
                case "delete_status":
                    $this->model->entry['reference']->set($_REQUEST['reference']);
                    $this->model->entry['state']->set($_REQUEST['state']);
					$result = $this->model->deleteStatus ();
                    $this->model->show_states ($params);
					break;						
                case "show_references": 
                    $result = $this->model->show_references ($params);
					break;
                case "show_states": 
                    $result = $this->model->show_states ($params);
					break;
                case "show_transitions": 
                    $result = $this->model->show_transitions ($params);
					break;
                case "copy_from_dg":
				    $result = $this->model->copyFromDG ($params);
				    //$this->model->entry['command']->set('show_entries');
                    $this->model->show_references($params);
                	break;                   
                case "set_startpoint":
                    $this->model->entry['reference']->set($_REQUEST['reference']);
                    $this->model->entry['state']->set($_REQUEST['state']);
					$result = $this->model->setStartpoint();
					break;						
                case "set_endpoint":
                    $this->model->entry['reference']->set($_REQUEST['reference']);
                    $this->model->entry['state']->set($_REQUEST['state']);
                    $this->model->entry['selected']->set($_REQUEST['selected']);
					$result = $this->model->setEndpoint();
					break;						
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command: ".$this->model->command->get());
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
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