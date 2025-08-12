<?php
	
   /**
    * Controler for handling datagrids. This file contains the controler of the model-view-controller pattern used to 
    * implement the mandators functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      datagrids
    */
        
   /**
    * This class implements the controler to call the models methods and modify the model itself.
    *    
    *
    * @version      $Id: datagrids_ctrl.php,v 1.2 2005/07/13 13:59:24 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      datagrids
    */    
	class datagrids_script extends easy_controller {

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
        * @since        0.5.1
        * @version      0.5.1
        */
        function handleModel() {
                        
            // === Initialization ====================================
			if (isset($_REQUEST['command']))
				$this->model->command->set ($_REQUEST['command']);

            $result = "success";

			// === Dispatch Part depending on command ================
			switch($this->model->command->get()){

                case "edit_datagrid": 
                    //$this->model->entry['command']->set('edit_datagrid');
                    $this->model->entry['datagrid']->set ($_REQUEST['datagrid']);
                    $result = $this->model->edit_datagrid ();
					break;
				case "edit_column":
                    $this->model->entry['datagrid_id']->set ($_REQUEST['datagrid_id']);
                    $this->model->entry['column_id']->set ($_REQUEST['column_id']);
                    $this->model->getDatagridColumn ();
					break;
                case "update_datagrid":
                    $result = $this->model->updateDatagrid (); 
                	if (isset ($_REQUEST['datagrid']))
	                    $this->model->entry['datagrid']->set ($_REQUEST['datagrid']);
	                //$result = $this->model->edit_datagrid ();
				    break;
				case "unset_current_view":
                    $this->model->unset_view ($params);
				    break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command (".$this->model->command->get().") in ".__FILE__);
			} // switch
			
			$this->setViewByTransition ($_REQUEST['command'], $result);	
					
		} // end handleModel        
		
		
    } // end class

?>