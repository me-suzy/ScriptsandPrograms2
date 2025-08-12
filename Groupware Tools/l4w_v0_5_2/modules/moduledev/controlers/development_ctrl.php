<?php
	
   /**
    * Controler for handling documents. This file contains the controler of the model-view-controller pattern used to 
    * implement the notes functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */
        
   /**
    * This class implements the controler to call the models methods and modify the model itself.
    * notes.
    * Notes are a basic type of information implemented in lead4web and provide a <b>headline</b>
    * and a <b>content field</b> only.<br>
    * Nevertheless, they can be <b>viewed, changed, organized</b> in different ways and even <b>synchronized</b>.
    * As a part of leads4web, notes are treated as <b>shareable pieces of information</b> which belong to extacly <b>one
    * group</b> and have certain <b>access rights</b>. When a note gets <b>attached</b> to other pieces of information (like contacts or documents),
    * these access rights (and the group) are <b>inherited</b> from the parent.<br> 
    * A note can belong to zero or more <b>collections</b> (which is basically a gathering of various pieces of information of any kind)
    * and can <b>reference</b> (or be referenced by) other pieces of information.<br>
    * Notes can be organized in <b>folders</b>, but these folders do not pass their group or access rights to their content.  
    *
    * @version      $Id: development_ctrl.php,v 1.3 2005/07/14 06:01:22 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */    
	class development_script extends easy_controller {

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
			    case "add_module": 
                    //$this->model->entry['command']->set('add_entry');
                    //$this->model->entry['schema']->set ('leads4web');
					break;
			    case "create_module": 
                     $result = $this->model->create_module ($params);
	       		     break;
			    case "run_sql": 
                     $result = $this->model->run_sqls ($params);
	       		     break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command");
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
		
    } // end class

?>