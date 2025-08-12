<?php
	
   /**
    * Controler for handling documents. This file contains the controler of the model-view-controller pattern used to 
    * implement the pics functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      pics
    */
        
   /**
    * This class implements the controler to call the models methods and modify the model itself.
    * pics.
    * pics are a basic type of information implemented in lead4web and provide a <b>headline</b>
    * and a <b>content field</b> only.<br>
    * Nevertheless, they can be <b>viewed, changed, organized</b> in different ways and even <b>synchronized</b>.
    * As a part of leads4web, pics are treated as <b>shareable pieces of information</b> which belong to extacly <b>one
    * group</b> and have certain <b>access rights</b>. When a pic gets <b>attached</b> to other pieces of information (like contacts or documents),
    * these access rights (and the group) are <b>inherited</b> from the parent.<br> 
    * A pic can belong to zero or more <b>collections</b> (which is basically a gathering of various pieces of information of any kind)
    * and can <b>reference</b> (or be referenced by) other pieces of information.<br>
    * pics can be organized in <b>folders</b>, but these folders do not pass their group or access rights to their content.  
    *
    * @version      $Id: pics_ctrl.php,v 1.2 2005/05/15 15:31:54 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      pics
    */    
	class pics_script extends easy_controller {

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

                case "add_folder":
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
			    /*case "add_pic_att":
                    $result = $this->model->add_pic ($params);
                    if ($result == "success") {
                        //$this->add_quicklink ($this->model);
	                    $this->model->entry['command']->set ('show_entries');                        
                        $this->model->show_entries ($params);
	                }
	                else
                        $this->model->entry['command']->set('add_pic_att');
					break;
                case "add_pic_att_view": 
                    $this->model->entry['ref_object_type']->set($_REQUEST['ref_object_type']);
                    $this->model->entry['ref_object_id']->set($_REQUEST['ref_object_id']);
                    $this->model->entry['ref_type']->set($_REQUEST['ref_type']);
                    $this->model->entry['command']->set('add_pic_att');
					break;*/
			    case "add_folder_view":
			        $this->model->entry['parent']->set($_REQUEST['parent']);
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
			    /*case "add_references":
                    $this->model->entry['command']->set('add_references');
                    $result = $this->model->add_references ($params);			    
                    $this->model->show_entries ($params);
                    break;*/
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
                case "edit_att_pic": 
                    $this->model->entry['command']->set('update_att_pic');
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
			    case "move":
			        $result = $this->model->moveEntries ($params);
			        $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
			        break;
			    case "move_view":
			        $this->model->getFolders ($params);
			        break;
                case "show_entries": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_entries ($params);
					break;
                case "show_locked": 
                    $this->model->entry['command']->set('show_entries');
                    $this->model->update_filter ($params);
                    $result = $this->model->show_locked ($params);
					break;
                case "update_att_pic":
                    $result = $this->model->update_entry ($params); 
                    if ($result == "success") {
                        //$params["entry_id"] = $_REQUEST['return_to'];
	                    list ($type, $id) = explode ("_", $_REQUEST['return_to']);
	                    $path = "http://".$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
	                    switch ($type) {
							case 'pic':
								$path .= "/../pics/index.php?command=edit_entry&entry_id=".$id;
								break;
							case 'todo':
								$path .= "/../todos/index.php?command=edit_entry&entry_id=".$id;
								break;
							case 'ticket':
								$path .= "/../tickets/index.php?command=edit_entry&entry_id=".$id;
								break;
							default:
								die ("unknown type in ".__FILE__." (".__LINE__.")");
								break;
						}
	                    header ("Location: ".$path);
	                    die();
	                    //$this->model->entry['command']->set ('update_entry');
                        //$this->model->show_entry ($params);
                    }
                    else {
	                    $this->model->entry['command']->set ('update_att_pic');
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
				    break;
				default:
					die ("unrecognized command");
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
       /**
        * Add quicklink
        *
        * Creates a quicklinkg for given entry
        * 
        * @access       private
        * @param        class current model
        * @since        0.4.4
        * @version      0.4.4
        */
		function add_quicklink (&$model) {

		    create_quicklink ('document', 
                              $model->entry['pic_id']->get(), 
                              $model->entry['name']->get(), 
                              'modules/pics/index.php?command=show_pic&pic_id='.
                              $model->entry['pic_id']->get());
        }
		
    } // end class

?>