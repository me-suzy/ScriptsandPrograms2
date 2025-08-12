<?php

   /**
    * $Id: tickets_mdl.php,v 1.32 2005/08/04 15:48:30 carsten Exp $
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package tickets
    */
    
   /**
    * include fields validation rules
    */  
    require_once ('fields_validations.inc.php');
    
   /**
    * Documents Model
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
    */    
    class tickets_model extends l4w_model {
         
        /**
          * int holding the id of an added document entry
          *
          * @access public
          * @var string
          */  
        var $inserted_entry_id = null;     // ID for user when adding was successfull
        
         /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'ticket';   
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @since        0.4.4
        * @version      0.4.4
        */
        function tickets_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array ("add_folder",
                       "add_folder_view",
                       "add_ticket_view",
                       "add_note_att_view",
                	   "add_ticket",
                	   "add_note_att",
                	   "add_ref_view",
                       "clear_filter",
					   "delete_entry",           // (action)
                       "delete_selected",
                       "del_ref",
                       "edit_att_entry",         // edit attachment entry
                       "edit_entry",
                       "edit_folder",
                       "move",
                       "move_view",

                       "save_as_template",		 // (un)serialization
                       "serialize",
                       "unserialize",         
                       "adjust_template",
                       "copy_from_dg",
                	   "show_entries",           // list of all entries
                       "show_locked",            // list of all entries
                       "update_entry",  
                       "unset_current_view",     // unset current view in SESSION (unlock)
                       "help"
            ));
            $this->command->strict = true;
                                                              
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }

      /**
        * validates new or updated ticket. The 'due'-date must be ok, there has to be
        * a contact given
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function ticket_validation () {
            global $logger;
            
            $validated = true;
			// --- validate date ------------------------------------
            $due = $this->entry['due'];
            if (!$due->EMPTY_ALLOWED && $due->get() != '') {
                if (!checkdate($due->get("m"), $due->get("d"), $due->get("Y"))) {
                    $this->error_msg .= translate ('date format error')."<br>";    
                    $validated = false;
                }
            }    
            
			// --- validate contact is set --------------------------
			// now in fields validations
			
			if (!$validate = $this->validateModel ()) {
				//die (".");
				//$this->error_msg .= translate ('validation of model failed');
				return "failure";	
			}	

            return "success";
        }

      /**
        * add new folder.
        *
        * The new entry gets validated via folder_validation and added to the ticket table on success. In this case an
        * event is fired (new folder). If the group or the access rights differ from the parents folder, a corresponing
        * info message is set.
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function add_folder (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->folder_validation('ticket');
            if ($validation != "success") return $validation; 
            
            // --- add entry ----------------------------------------            
            $query = "INSERT INTO ".TABLE_PREFIX."tickets (
                                is_dir,
                                parent,
                                theme,
                                content)
                               VALUES (
                                '1',
                                '".$this->entry['parent']->get()."',
                                '".$this->entry['theme']->getHTMLEscaped()."',
                                '".$this->entry['ticket']->getHTMLEscaped()."'
                               )";
            $logger->log ($query, 7);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['ticket_id']->set ($inserted_id);

            // --- add metainfo -------------------------------------
            $meta_query = "INSERT INTO ".TABLE_PREFIX."metainfo (
                            object_type,
                            object_id,
                            creator,
                            owner,
                            grp,
                            state,
                            created,
                            access_level)
                           VALUES (
                            'ticket',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            ".$this->entry['use_group']->get().",
                            0,
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
            $logger->log ($meta_query, 7);
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."tickets WHERE ticket_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_entry_id = $inserted_id;
            
			// --- fire event ---------------------------------------
            fireEvent ($this, 'ticket', 'new folder','system',$this->inserted_entry_id);
            
            // --- show info when access different from parent folder
            if ($this->entry['parent']->get() > 0) {
                $parent_vals = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'ticket',
                                                          "object_id"   => $this->entry['parent']->get()));
                if ($parent_vals['grp'] != $this->entry['use_group']->get())
                    $this->info_msg .= translate ('grp differs from parent')."<br>";
                if ($parent_vals['access_level'] != $this->entry['access']->get())
                    $this->info_msg .= translate ('access level differs from parent')."<br>";
            }    
            
            return "success";
        }
        
      /**
        * adds a new ticket.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.5.2
        */
        function add_ticket () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->ticket_validation();
            if ($validation != "success") return $validation;
            
            // --- inheritance from contact -------------------------
            $ref_meta_info = get_entries_for_primary_key(
                                   "metainfo", array ("object_type" => 'contact',
                                                      "object_id"   => $this->entry['contact']->get())); //$params['contact']));
            // overwrite grp, access with values from referer
            $this->entry['use_group']->set ($ref_meta_info['grp']);
            $this->entry['access']->set    ($ref_meta_info['access_level']);

            // --- add entry ----------------------------------------            
            $query = "INSERT INTO ".TABLE_PREFIX."tickets (
                                theme,
                                content,
                                due,
                                priority,
                                parent,
                                contact_id                               
                          )
                          VALUES (
                                '".$this->entry['theme']->getHTMLEscaped()."',
                                '".$this->entry['ticket']->getHTMLEscaped()."',
                                '".$this->entry['due']->get("Y-m-d")."',
                                '".$this->entry['priority']->get()."',
                                '".$this->entry['parent']->get()."',
                                ".$this->entry['contact']->get()."
                               )";
            $logger->log ($query, 7);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['ticket_id']->set ($inserted_id);
            
            // --- add metainfo -------------------------------------
            $meta_query = "INSERT INTO ".TABLE_PREFIX."metainfo (
                            object_type,
                            object_id,
                            creator,
                            owner,
                            grp,
                            state,
                            created,
                            access_level)
                           VALUES (
                            'ticket',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            ".$this->entry['use_group']->get().",
							".getStateForNewObject($this->entry_type).",
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
            $logger->log ($meta_query, 7);
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."tickets WHERE ticket_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_ticket_id = $inserted_id;
            
			// --- set default --------------------------------------
			set_defaults ();
						
			// --- fire event ---------------------------------------
            fireEvent ($this, 'ticket', 'new ticket','system',$this->inserted_ticket_id);
            
            // --- add references if any ----------------------------
			/*if ($params['ref_object_type'] != '' && $params['ref_object_id'] > 0) {
                $this->addReference(
                            $params['ref_object_type'],
                            $params['ref_object_id'], 
                            'note', 
                            $this->inserted_ticket_id, 
                            '', 
                            2);    
                return "close";                            
			} */  
			
            return "success";
        }
               
      /**
        * updates note entry
        *
        * Uses intern ticket validation. If there are any problems, examine
        * model->error_msg and model->info_msg;
        * 
        * 17.2.2005: added external links
        *
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.7
        */
        function update_entry (&$params) {
           
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validate -----------------------------------------
            $validation = $this->ticket_validation();
            if ($validation != "success") return $validation; 
                        
            // --- init ---------------------------------------------
            //var_dump ($this->entry);
            
            // --- history, get_old values -----------------------------
            $old_entry_values   = get_entries_for_primary_key (
                                       "tickets", array ("ticket_id" => $this->entry['ticket_id']->get()));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'ticket',
                                                          "object_id"   => $this->entry['ticket_id']->get()));

            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                                                          
            // --- update entry ----------------------------------------
            $query = "UPDATE ".TABLE_PREFIX."tickets SET 
                                theme          = '".$this->entry['theme']->getHTMLEscaped()."',
                                content        = '".$this->entry['ticket']->getHTMLEscaped()."',
                                due            = '".$this->entry['due']->get("Y-m-d")."',
                                priority       = '".$this->entry['priority']->get()."',
                                contact_id     = ".$this->entry['contact']->get()."
                              WHERE ticket_id  = '".$this->entry['ticket_id']->get()."'";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- add metainfo -------------------------------------
            $contact_query = "
                SELECT grp, access_level FROM ".TABLE_PREFIX."metainfo
                WHERE object_type='contact' AND object_id=".$this->entry['contact']->get()."
                    ";
            if (!$contact_res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $contact_row = mysql_fetch_array($contact_res);
            $use_group = $contact_row['grp'];
            
            $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                            grp          = ".$use_group.",
                            access_level = '".$contact_row['access_level']."',
                            state        = ".$this->entry['state']->get().",
                            last_changer = ".$_SESSION['user_id'].",
                            last_change  = now()
                           WHERE object_type='ticket' AND object_id=".$this->entry['ticket_id']->get()." 
                           ";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                
            // --- Does the (new) owner still have access to the (new) group?
            $all_groups = get_all_groups ($this->entry['owner']->get());
            if (in_array ($use_group, $all_groups)) {
                // if so, the current user gets owner of the contact                
                $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                owner = ".$this->entry['owner']->get()."
                                WHERE object_type='ticket' AND object_id=".$this->entry['ticket_id']->get()." 
                               ";
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                
                // fire event for assigned ticket if owner was changed
                if (mysql_affected_rows() > 0) {
	                $event_error = fireEvent ($this, 'ticket', 'assigned', 'system', $this->entry['ticket_id']->get());
					if (!is_null($event_error)) {
						$this->info_msg .= translate ($event_error);
        			}
                }
            }   
            else {
                $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                owner = ".$_SESSION['user_id']."
                                WHERE object_type='ticket' AND object_id=".$this->entry['ticket_id']->get()." 
                               ";
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                $this->entry['owner']->set ($_SESSION['user_id']);
                $this->info_msg = translate ('old user not member of new group');

                return "failure";            	
            }	 
            
            // --- identity hash ------------------------------------

            // --- update history -----------------------------------
            update_history ("ticket",                             // identifier for history table
                            "tickets",                         // table
                            $this->entry['ticket_id']->get(),  // object_id
                            array ("ticket_id" => $this->entry['ticket_id']->get()), 
                            $old_entry_values);
            update_history ("ticket",          
                            "metainfo",         
                            $this->entry['ticket_id']->get(),
                            array ( "object_type" => 'ticket',
                                    "object_id"   => $this->entry['ticket_id']->get()), 
                            $old_meta_values,
                            array ("last_changer", "last_change"));
                            
            // --- add attachment, if any ---------------------------------
            if (isset ($_REQUEST['attachment_content']) && ($_REQUEST['attachment_content'] != '')) {
                require_once ('../common/attachments.class.php');
                $ref_tab = new attachments_tab (
                    $this->entry['ticket_id']->get(), // serves as perent
                    $this->entry_type,
                    false); 
                $ref_tab->setTabNr   (2);
                $ref_tab->setImgPath ('');
                
                $done = $ref_tab->addAttachment ($this->entry, 'note', $_REQUEST);
                if ($done != "success") 
                    return "failure";
            }
            
            // --- add external links, if any -----------------------   
            if (isset ($_REQUEST['external_link_path']) && ($_REQUEST['external_link_path'] != '')) {
                require_once ('../common/external_references.class.php');
                $external_refs = new external_references ($this->entry['ticket_id']->get(),'note',false); 
                $external_refs->setTabNr   (2);
                $external_refs->setImgPath ('');
                
                $done = $external_refs->addExternalLink ($this->entry, $this->entry_type, $_REQUEST);
                if ($done != "success") 
                    return "failure";
            }
            
            // --- and delete attachments, if delete_attachments given ---
            if (isset ($params['delete_attachments']) && $params['delete_attachments'] != '') {
                require_once ('../common/attachments.class.php');
                $ref_tab = new attachments_tab (
                    $this->entry['ticket_id']->get(),
                    $this->entry_type, 
                    false); 
                $ref_tab->setTabNr   (2);
                $ref_tab->setImgPath ('');
                $ref_tab->setModel   ($this);
                
                $done = $ref_tab->deleteAttachments ($params);
                if ($done != "success") {
                    $this->error_msg .= "internal error deleting attachment, ".__FILE__." (".__LINE__.")";
                    return "failure";
                }    
            }
            
            // --- add references -----------------------------------
            $this->addReferences ('ticket', $this->entry['ticket_id']->get(), $params, $this->entry['ref_desc']->get());
                            
            // --- unlock entry -------------------------------------
            $this->unlockEntry('ticket', $this->entry['ticket_id']->get());

            // --- set default --------------------------------------
			set_defaults ($params);
                            
			// --- fire event ---------------------------------------
            //fireEvent ($this, 'ticket', 'changed ticket', 'system', $this->entry['ticket_id']->get());

			// --- fire event ---------------------------------------
            $event_error = fireEvent ($this, 'ticket', 'changed ticket','system',$this->entry['ticket_id']->get());
			if (!is_null($event_error)) {
				$this->info_msg .= translate ($event_error);
        	}					            

            if (isset ($params['apply']) && $params['apply'] != '')
                return "apply";
                
            return "success";
        }
        
      /**
        * moves entry
        *
        * asserts params entry id and move_to are given and greater or equal (entry_id) than zero. An entry cannot 
        * be moved onto itself, and the taret must be a folder. On success, an event (changed note) is fired.
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function move_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- assertions ---------------------------------------
            assert ('(int)$params["ticket_id"] > 0');
            assert ('(int)$params["move_to"] >= 0');
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_note_values = get_entries_for_primary_key (
                                       "tickets", array ("ticket_id" => $params['ticket_id']));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $params['ticket_id']));
            
            
            // --- validation ---------------------------------------
            // target must be folder
            $target_values = get_entries_for_primary_key (
                                       "tickets", array ("ticket_id" => $params['move_to']));   
                                                                                            
            if ($params['move_to'] > 0 && !(bool)$target_values['is_dir']) {
                $this->error_msg = translate ('move target must be a folder');
                return false;    
            }    
                                                        
            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            // --- entry can be moved? ------------------------------
            if ($params['ticket_id'] == $params['move_to']) {
                $this->info_msg .= translate ('cannot move item to itself');
                return "failure";
            }    
                       
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $params['ticket_id']);

            // --- update entry -------------------------------------
            $update_query = "UPDATE ".TABLE_PREFIX."tickets SET parent=".$params['move_to']." WHERE ticket_id='".$params['ticket_id']."'";
            if (!$this->ExecuteQuery ($update_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                                    
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";
                 
            // --- update history -----------------------------------
            update_history ($this->entry_type,                             // identifier for history table
                            "tickets",                         // table
                            $this->entry['ticket_id']->get(),  // object_id
                            array ("ticket_id" => $this->entry['ticket_id']->get()), 
                            $old_entry_values);
        
            return "success";
        }            
        
/**
        * moves entries to other folder.
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @todo         public funciton with "real" array of ids
        * @todo         move to leads4web_model?
        * @since        0.4.7
        * @version      0.4.7
        */
        function moveEntries (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $move_list = array ();

            foreach ($params AS $key => $value) {
                if (substr ($key, 0,7) == $this->entry_type."_") {
                    $move_list[] = (int)substr ($key,7);    
                }    
            }    
                     
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
            
            // --- delete entries -----------------------------------
            foreach ($move_list AS $key => $move_id) {
                $wrapper = array ("ticket_id" => $move_id, "move_to" => $params['move_to']);
                $result  = $this->move_entry($wrapper);    
                if ($result == "failure") {
                    if ($this->info_msg != "")
                        $info_msg  .= $this->info_msg."<br>";
                    if ($this->error_msg != "")
                        $error_msg .= $this->error_msg."<br>";    
                    $success    = false;
                }    
            }    
            
            // --- success ? ----------------------------------------
            if ($success) 
                return "success";
            
            $this->error_msg = $error_msg;
            $this->info_msg  = $info_msg;
            
            return "failure";
        }
        
      /**
        * deletes entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function delete_entry (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_note_values = get_entries_for_primary_key (
                                       "tickets", array ("ticket_id" => $params['entry_id']));
            
            $old_meta_values = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'ticket',
                                                          "object_id"   => $params['entry_id']));

            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                        
            // --- fire event ---------------------------------------
            fireEvent ($this, 'ticket', 'deleted ticket', 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."tickets WHERE ticket_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='ticket' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            // --- delete any refering entries in table refering ----
            $meta_query = "DELETE FROM ".TABLE_PREFIX."refering WHERE to_object_type='ticket' AND to_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $meta_query = "DELETE FROM ".TABLE_PREFIX."refering WHERE from_object_type='ticket' AND from_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- update any references in the sync table ----------
            //$sync_query = "UPDATE sync SET status='deleted locally' WHERE object_type='note' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($sync_query, 'mysql_error')) return "failure";
            
            return "success";
        }    
            
      /**
        * deletes a list of entries
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function delete_entries (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of tickets to delete ------------------
            $del_list = array ();
            foreach ($params AS $key => $value) {
                if (substr ($key, 0,7) == "ticket_") {
                    $del_list[] = (int)substr ($key,7);    
                }    
            }    
            
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
            
            // --- delete entries -----------------------------------
            foreach ($del_list AS $key => $del_id) {
                $wrapper = array ("entry_id" => $del_id);
                $result  = $this->delete_entry($wrapper);    
                if ($result == "failure") {
                    if ($this->info_msg != "")
                        $info_msg  .= $this->info_msg."<br>";
                    if ($this->error_msg != "")
                        $error_msg .= $this->error_msg."<br>";    
                    $success    = false;
                }    
            }    
            
            // --- success ? ----------------------------------------
            if ($success) 
                return "success";
            
            $this->error_msg = $error_msg;
            $this->info_msg  = $info_msg;
            
            return "failure";
        }

      /**
        * Show all entries.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. In the end, the query gets serialized and saved, for further use like
        * exporting.
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.4.6
        * @version      0.4.6
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            //$db_hdl->debug=true;
            if (is_null($query)) {
                $query = "
                    SELECT
                        ticket_id,
						theme,
						content,
						creator,
						followup,
						CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
						ag.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,   
                        is_dir,
                        mi.owner AS owner_id,
                        mi.grp   AS group_id,
						done,
                        color,
                        mi.state
                    FROM ".TABLE_PREFIX."tickets 
                    LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."tickets.ticket_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ag ON mi.grp=ag.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=mi.owner
                    LEFT JOIN ".TABLE_PREFIX."priorities p ON ".TABLE_PREFIX."tickets.priority=p.prio_id
					LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
					WHERE mi.object_type='ticket' AND
					      p.mandator=".$_SESSION['mandator']." AND
                    	  parent=".$this->entry['parent']->get()." AND
                    	  gd.mandator_id=".$_SESSION['mandator'];
            }       
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND mi.grp=".$_SESSION['use_my_group'];       
            else {
                //$query .= " AND ".get_all_groups_or_statement ($_SESSION['user_id']);    
            }    
            
            if ($_SESSION['use_my_state'] != '')
                $query .= " AND mi.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND mi.owner=".$_SESSION['use_my_owner'];       

            if (isset ($params['filter_contact_id']) && $params['filter_contact_id'] > 0)
                $query .= " AND ".TABLE_PREFIX."tickets.contact_id=".$params['filter_contact_id'];       
                
            $query .= "
                        AND (
                        	  mi.owner=".$_SESSION['user_id']." 
                			OR
		                      (".get_all_groups_or_statement ($_SESSION['user_id'])." AND mi.access_level LIKE '____r_____') 
			                OR
                     		   mi.access_level LIKE '_______r__'	
                        )
            ";    
			$query .= "ORDER BY is_dir DESC";

            $this->dg = new datagrid (20, "tickets", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=1;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                     
            // --- serialize query for further use (i.e. export) ----
            //$this->serializeQuery ($this->dg);
   
            return "success";
        }
        
       /**
        * Shows single ticket.
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this ticket.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.4.6
        * @version      0.4.6
        */
        function show_entry (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."tickets
                LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."tickets.ticket_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='ticket' AND ".TABLE_PREFIX."tickets.ticket_id=".$params['entry_id'].")";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);

            // --- sufficient rights ? -----------------------------
            if (!user_may_read ($row['owner'],$row['grp'],$row['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            foreach ($row AS $field => $value) {
                    if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
                        
            // --- adjust some values -------------------------------
            //$due    = explode ('-', $row['due']);
            //$due_ts = mktime (1,1,1,$due[1], $due[2], $due[0]);
            //$due    = date (DATE_FORMAT, $due_ts);
             
            //$this->entry['use_group']->set ($row['grp']);
            //$this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);
            $this->entry['contact']->set   ($row['contact_id']);
            $this->entry['ticket']->set    ($row['content']);

            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy('ticket', $this->entry['ticket_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ('ticket')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ('ticket')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
            }
            else {
                // --- lock contact -------------------------------------
                //$this->info_msg .= translate ('ticket')." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ('ticket', $this->entry['ticket_id']->get());
            }
           
            return "success";
        }
        
      /**
        * unset view.
        *
        * private method to unset locking of contact
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string "success"
        * @since        0.4.4
        * @version      0.4.4
        */
        function unset_view ($params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            mysql_query ("DELETE FROM ".TABLE_PREFIX."useronline 
	                     WHERE user_id='".$_SESSION['user_id']."'
						 AND object_type='contact' 
                         AND object_id=".$params['contact_id']);
        	logDBError (__FILE__, __LINE__, mysql_error());
            array_push ($_SESSION['current_views'], array ('contact', $params['contact_id']));
            
            return "success";
        }

	   /**
        * clears filter
        *
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @todo         move to leads4web_model?
        * @todo         check same (?) functionality of js in filter.inc.php
        * @since        0.4.7
        * @version      0.4.7
        */
        function clear_filter () {
            global $db_hdl, $logger;
            
            parent::update_filter (array ("my_group" => '',
            							  "my_state" => '',
            							  "my_owner" => ''));
			//var_dump ($_SESSION['easy_datagrid']);
			$_SESSION['easy_datagrid'][$this->entry_type.'s'] = array ();
        }            
        
      /**
        * get list of parents names
        *
        * 
        * 
        * @access       private
        * @param        int id of node where to start
        * @return       array holding parents names and ids
        * @since        0.4.4
        * @version      0.4.4
        */
        function getParentChain ($start_id, $chain = null) {
            global $db_hdl, $logger;

            $sql = "SELECT ticket_id, theme, parent 
                    FROM ".TABLE_PREFIX."tickets
                    WHERE ticket_id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['theme'];
                $chain[$cnt]['id']     = $row['ticket_id'];                
                $chain += $this->getParentChain ($row['parent'], $chain);
            }	
            return $chain;
        }
        
       /**
        * get name for given folder id
        *
        * function asserts that given id actually exists and is a folder
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @since        0.4.7
        * @version      0.4.7
        */
        function get_folder_name ($folder_id = 0) {
            global $db_hdl, $logger;

            if ($folder_id == 0) return '';
            
            $sql = "SELECT theme, is_dir
                    FROM ".TABLE_PREFIX."tickets
                    WHERE ticket_id='$folder_id'";
            echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            assert ('(bool)$row["is_dir"] == true');
            return $row['theme'];
        }

        
    }   

?>