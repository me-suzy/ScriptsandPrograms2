<?php
	
   /**
    * Controler for handling documents. 
    *
    * @author       
    * @copyright    
    * @package      
    */
        
   /**
    *
    * @version      $Id: mail_ctrl.php,v 1.14 2005/07/14 07:32:17 carsten Exp $
    * @author       
    * @copyright    
    * @package      
    */    
	class mail_script extends easy_controller {

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
        * @since        0
        * @version      0
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

                case "add_account_view":
                    $this->model->entry['command']->set ('add_account');
                    break;
                case "create_mail":
                	break;
                case "send_mail":
	                $result = $this->model->sendmail ();
                	break;
                case "add_account":
                    $result = $this->model->add_account ($params);
                    if ($result != "success")
                        $this->model->entry['command']->set('add_account');
                    else 
                        $this->model->show_accounts ($params);
					break;
				case "delete_from_trash":
					$params['target_folder'] = -1; // -1 means "delete"
					$this->model->move2folder ($params);
					$this->model->entry['command']->set('show_mails');
                    $this->model->entry['folder']->set ($params['folder']);
                    $this->model->show_mails ($params);
					break;
				case "download_mail":
					$result = $this->model->download_mail ($params);
                    //if ($result == "success")
                    //    $this->model->entry['command']->set('add_account');
					break;
                case "edit_account":
                    $this->model->entry['command']->set ('update_account');
                    $this->model->show_account ($params);
                    break;
				case "get_mails":
				    $this->model->get_mails ($params);
				    break;
				case "show_accounts":
				    $this->model->show_accounts ($params);
				    break;
				case "show_attachments": 
                    $this->model->entry['command']->set('show_attachments');
                    $result = $this->model->show_mail ($params);
                    $result = $this->model->get_attachments ();
					break;
				case "show_content": 
                    //$this->model->entry['command']->set('show_mail');
                    //$this->model->update_filter ($params);
                    $result = $this->model->show_content ($params);
					break;
				case "show_header": 
                    $this->model->entry['command']->set('show_header');
                    $result = $this->model->show_mail ($params);
					break;
				case "show_log": 
                    //$this->model->entry['command']->set('show_header');
                    $result = $this->model->show_mail ($params);
					break;
				case "show_mail": 
                    $this->model->entry['command']->set('show_mail');
                    //$this->model->update_filter ($params);
                    $result = $this->model->show_mail ($params);
					break;
				case "show_mails": 
                    $this->model->entry['command']->set('show_mails');
                    $this->model->entry['folder']->set ($params['folder']);
                    //$this->model->update_filter ($params);
                    $result = $this->model->show_mails ($params);
					break;
				case "show_mails_for_contact":
                    $this->model->entry['command']->set('show_mails_for_contact');
                    $result = $this->model->show_mails_for_contact ($params);
					break;
				case "show_pic": 
                    //$this->model->entry['command']->set('show_mail');
                    //$this->model->update_filter ($params);
                    $result = $this->model->show_content ($params);
					//var_dump ($this->model->entry);
					break;
				case "clear_filter":
					$this->model->clear_filter ($params);
                    $this->model->entry['command']->set ('show_entries');
                    $this->model->show_entries ($params);
				    break;
				case "move2trash":
					$params['target_folder'] = 0; // folder trash
					$this->model->move2folder ($params);
					
					$this->model->entry['command']->set('show_mails');
                    $this->model->entry['folder']->set ($params['folder']);
                    $this->model->show_mails ($params);
					break;
                case "update_account":
                    $result = $this->model->update_account ($params);
                    if ($result != "success")
                        $this->model->entry['command']->set('edit_account');
                    else
                        $this->model->show_accounts ($params);
                    break;
				case "help":
				    $result = $_REQUEST['about'];
				    break;
				default:
					die ("unrecognized command (ctrl)");
			} // switch
			
			$this->setViewByTransition ($params['command'], $result);	
					
		} // end handleModel        
		
    } // end class

?>