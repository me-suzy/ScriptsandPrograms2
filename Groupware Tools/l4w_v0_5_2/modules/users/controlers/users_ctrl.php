<?php
	
  /**
    * $Id: users_ctrl.php,v 1.9 2005/07/24 09:20:33 carsten Exp $
    *
    * Controler for users. See easy_framework for more information
    * about controlers and models.
    * @package users
    */
    
  /**
    *
    * Users Controler Class
    * @package users
    */
	class users_script extends easy_controller {
		
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

                case "show_users":
                    $this->model->show_users ($params);
					break;
                case "add_user":
                    $result = $this->model->add_user ($params);
                    if ($result == "success")
                        $this->model->show_users ($params);
					break;
                case "delete_user":
                    $result = $this->model->delete_user ($params);
                    $this->model->show_users ($params);
					break;
                case "delete_user_view":
                    $this->model->user_summary ($params);
					break;
                case "update_user":
                    $result = $this->model->update_user ($params);
                    if ($result == "success")
                        $this->model->show_users ($params);
					break;
                case "update_users_groups":
                    $result = $this->model->update_users_groups ($params);
                    if ($result == "success")
                        $this->model->show_users ($params);
					break;
                case "view_user":
                    $this->model->view_user ($params);
					break;
                case "switch_to_user":
				    $result = $this->model->switchUser ($params);
				    if ($result != "success")
                        $this->model->show_users($params);
                    break;
                case "copy_from_dg":
				    $result = $this->model->copyFromDG ($params);
				    //$this->model->entry['command']->set('show_entries');
                    $this->model->show_users($params);
                	break;                   
			    case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command in ".__FILE__);
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
    } // end class

?>