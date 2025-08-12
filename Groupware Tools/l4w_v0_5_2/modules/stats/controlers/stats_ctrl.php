<?php
	
  /**
    * $Id: stats_ctrl.php,v 1.5 2005/07/26 13:23:12 carsten Exp $
    *
    * Controler for users. See easy_framework for more information
    * about controlers and models.
    * @package stats
    */
    
  /**
    *
    * Users Controler Class
    * @package stats
    */
	class stats_script extends easy_controller {
		
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
                case "show_requests":
                    //$this->model->collect_basic_stats ($params);
					break;
			    case "show_workflow_history":
			    	$this->model->entry['type']->set ($_REQUEST['type']);
			    	$this->model->entry['id']->set ($_REQUEST['id']);
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