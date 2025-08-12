<?php
	
  /**
    * $Id: tree_ctrl.php,v 1.8 2005/07/20 13:13:52 carsten Exp $
    *
    * Controler for supersede. See easy_framework for more information
    * about controlers and models.
    * @package tree
    */
    
  /**
    *
    * Supersede Controler Class
    * @package tree
    */
	class tree_script extends easy_controller {
		
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

                case "add_entry":
                    $result = $this->model->add_entry($params); 
                    if ($result == "success") {
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_entry');
					break;
                case "delete_entry": 
                    //$this->model->entry['command']->set('update_contact');
                    $result = $this->model->delete_entry ($params);
                    $this->model->show_entries ($params);
					break;
			    case "edit_entry": 
                    $this->model->get_entry($params);
					break;
                case "tree": 
                    $this->model->entry['img_path']->set('../../img/eclipse/');
                    $result = $this->model->get_tree ($params);
					break;
                case "verticaltabs": 
                    $this->model->entry['img_path']->set('../../img/eclipse/');
                    $result = $this->model->get_verticaltabs ($params);
					break;
                case "order_down": 
                    $result = $this->model->order_down ($params);
                    $this->model->show_entries ($params);
					break;
                case "order_up": 
                    $result = $this->model->order_up ($params);
                    $this->model->show_entries ($params);
					break;
                case "show_entries": 
                    $result = $this->model->show_entries ($params);
					break;
                case "show_auth": 
                    $result = $this->model->show_auth ($params);
					break;
                case "show_tree": 
                    $this->model->entry['img_path']->set('../../img/eclipse/');
                    $result = $this->model->get_tree ($params);
					break;
                case "update_auth":
                    $result = $this->model->update_auth ($params); 
                    if ($result == "success")
                        $this->model->show_entries ($params);
	                else {
	                    $this->model->entry['command']->set ('update_auth');
				    }
				    break;
                case "update_entry":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success")
                        $this->model->show_entries ($params);
	                else {
	                    $this->model->entry['command']->set ('update_entry');
				    }
				    break;
				case "use_template":
				    $this->model->entry['parent_id']->set ($_REQUEST['parent_id']);
    				$result = $this->model->use_template ();
					$this->model->show_entries ($params);
					$result = "success";
					break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command");
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