<?php
	
  /**
    * $Id: news_ctrl.php,v 1.3 2005/05/15 15:31:54 carsten Exp $
    *
    * Controler for supersede. See easy_framework for more information
    * about controlers and models.
    * @package news
    */
    
  /**
    *
    * Supersede Controler Class
    * @package news
    */
	class news_script extends easy_controller {
		
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

                /*case "add_contact":
                    $result = $this->model->add_contact ($params); 
                    if ($result == "success") {
                        $this->add_quicklink ($this->model);
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_contact');
					break;
                case "add_contact_view": 
                    $this->model->entry['command']->set('add_contact');
					break;
                case "assign_view" :
                
                	break;
				case "delete_entry": 
                    //$this->model->entry['command']->set('update_contact');
                    $result = $this->model->delete_entry ($params);
                    $this->model->show_entries ($params);
					break;
                case "export_excel": 
                    $this->model->export_excel ($params);
                    $result = $this->model->show_entries ($params);
					break;
                case "show_contact": 
                    $this->model->entry['command']->set('update_contact');
                    $result = $this->model->show_contact ($params);
                    $this->add_quicklink ($this->model);
					break;*/
                case "show_all_news": 
                    $this->model->get_all_news ($params);
                    //$result = $this->model->show_entries ($params);
					break;
                case "show_current_news": 
                    $this->model->get_current_news ($params);
                    //$result = $this->model->show_entries ($params);
					break;
                /*case "show_locked": 
                case "update_contact":
                    $result = $this->model->update_contact ($params); 
                    if ($result == "success")
                        $this->model->show_entries ($params);
	                else {
	                    $this->model->entry['command']->set ('update_contact');
	                    //$this->model->entry['contact_id']->set ($params['contact_id']);					
				    }
				    break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;*/
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