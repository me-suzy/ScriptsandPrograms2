<?php
	
  /**
    * $Id: events_ctrl.php,v 1.5 2005/08/03 19:43:19 carsten Exp $
    *
    * Controler for users. See easy_framework for more information
    * about controlers and models.
    * @package files
    */
    
  /**
    *
    * Users Controler Class
    * @package events
    */
	class events_script extends easy_controller {
		
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

                case "show_events":
                    $result = $this->model->show_events ($params);
					break;
				case "register1":
				    $this->model->register1 ($params);
				    break;
				case "register2":
				    $result = $this->model->register2 ($params);
				    break;
				case "register3":
				    $result = $this->model->register3 ($params);
                    $this->model->show_events ($params);
				    break;
				case "unregister_event":
				    $result = $this->model->unregister ($params);
                    $this->model->show_events ($params);
				    break;
				case "edit_template":
				    //$this->model->entry['watchlist_id']->set ($_REQUEST['watchlist_id']);
				    //$this->model->entry['watchlist_id']->set_null_allowed (false);
				    $result = $this->model->getTemplate ();
				    if ($result != "success")
                        $this->model->show_events ($params);
				    break;				    
				case "update_template":
				    //$this->model->entry['content']->set ($_REQUEST['content']);
				    //$this->model->entry['watchlist_id']->set_null_allowed (false);
				    $result = $this->model->updateTemplate ();
				    if ($result == "success")
                        $this->model->show_events ($params);
				    break;				    
			    case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command (".$this->model->command->get().") in ".__FILE__);
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
    } // end class

?>