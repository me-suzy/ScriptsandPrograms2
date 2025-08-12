<?php
	
  /**
    * $Id: groups_ctrl.php,v 1.5 2005/06/10 10:07:54 carsten Exp $
    *
    * Controler for supersede. See easy_framework for more information
    * about controlers and models.
    * @package files
    */
    
  /**
    *
    * Supersede Controler Class
    * @package groups
    */
	class groups_script extends easy_controller {
		
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

                case "show_groups":
                    $this->model->show_groups($params);
					break;
                case "show_hierarchy":
                    (isset($params['parent'])) ? $parent = $params['parent'] : $parent = 0;
                    $this->model->entry['parent']->set ($parent);
                    $this->model->show_hierarchy($params);
					break;
                case "add_group":
                    $result = $this->model->add_group ($params);
                    if ($result == "success")
                        $this->model->show_groups ($params);
					break;
                case "delete_group_view":
                    $this->model->group_summary ($params);
					break;
                case "delete_group":
                    $result = $this->model->delete_group ($params);
                    $this->model->show_groups ($params);
					break;
                case "update_group":
                    $result = $this->model->update_group ($params);
                    if ($result == "success")
                        $this->model->show_groups ($params);
					break;
                case "view_group":
                    $this->model->view_group ($params);
					break;
				default:
					die ("unrecognized command in ".__FILE__);
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
    } // end class

?>