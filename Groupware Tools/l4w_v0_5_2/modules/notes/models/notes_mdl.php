<?php

   /**
    * Model for handling documents. This file contains the model of the model-view-controller pattern used to 
    * implement the notes functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
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
    * Adding a note to a not-writable parent folder results in an error.
    *
    * @version      $Id: notes_mdl.php,v 1.37 2005/08/01 14:55:13 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */    
    class notes_model extends l4w_model {
         
        /**
          * int holding the id of an added entry.
          * Whenever a new entry gets added, its id is passed to this variable.
          *
          * @access public
          * @var string
          */  
        var $inserted_entry_id = null;     

        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'note';     

       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @todo         think about getting rid of smarty and authClass as parameters
        * @since        0.4.4
        * @version      0.4.4
        */
        function notes_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("add_entry_view",
                       "add_note_att_view",
                       "add_folder_view",
                       "add_folder",
                	   "add_entry",
                	   "add_note_att",
                	   "add_ref_view",
                	   "clear_filter",
                       "delete_entry",           
                       "delete_selected",
                       "del_ref",
                       "edit_att_note",         
                       "edit_entry",
                       "edit_folder",
                       "export",
                       "export_view",
                       "help",
                       "move_view",
                       "move",
                       "search_notes",
                	   "show_entries",           
                       "show_locked",   
                       "update_att_note",         
                       "update_entry",           
                       "unset_current_view"   // unset current view in SESSION (unlock)
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        * validates new or updated note.
        *
        * Validates if assigned group exists and runs the validation rules defined in corresponding file fields_definition.inc.php.
        * The parent folder (if existent) may not have the same group or access rights.
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.4.5
        * @version      0.4.5
        */
        function note_validation () {
            global $logger;

            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            
            // --- attachments have group = 0 and don't need validation 
            if ($group == 0) 
            	return "success";
            
            // --- does current user really has access to group? (This can
            // --- happen when making a copy of another note!)
            $all_groups = get_all_groups ($_SESSION['user_id']);
            if (!in_array($group, $all_groups)) {
                $group = get_main_group($_SESSION['user_id']);
                $this->entry['use_group']->set ($group);
                $logger->log ("note_validation: Group was changed to ".$group, 7);
                $this->info_msg .= translate ('group was changed to')." ".get_group_alias($group);    
            }        
            
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$group;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $logger->log ("note_validation: Group ".$group." did not exist", 7);
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}

			$validate = $this->validateModel ();
            if (!$validate) return "failure"; 
            
            return "success";
        }
             
      /**
        * add new folder.
        *
        * The new entry gets validated via folder_validation and added to the memos table on success. In this case an
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
        function add_folder ($params) {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $this->entry['headline']->set_empty_allowed (false);
            assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->folder_validation($this->entry_type);
            if ($validation != "success") return $validation; 
            
            // --- add entry ----------------------------------------            
            $query = "INSERT INTO ".TABLE_PREFIX."memos (
                                is_dir,
                                parent,
                                headline,
                                content)
                               VALUES (
                                '1',
                                '".$this->entry['parent']->get()."',
                                '".$this->entry['headline']->get()."',
                                '".$this->entry['content']->get()."'
                               )";
            $logger->log ($query, 7);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['memo_id']->set ($inserted_id);
            
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
                            '".$this->entry_type."',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            ".$this->entry['use_group']->get().",
                            0,
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
            $logger->log ($meta_query, 7);
            mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."memos WHERE memo_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_entry_id = $inserted_id;
            
			// --- set default --------------------------------------
			set_defaults ($params);

			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'new folder','system',$this->inserted_entry_id);
            
            // --- show info when access different from parent folder
            if ($this->entry['parent']->get() > 0) {
                $parent_vals = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $this->entry['parent']->get()));
                if ($parent_vals['grp'] != $this->entry['use_group']->get())
                    $this->info_msg .= translate ('grp differs from parent')."<br>";
                if ($parent_vals['access_level'] != $this->entry['access']->get())
                    $this->info_msg .= translate ('access level differs from parent')."<br>";
            }    
            
            return "success";
        }
                
      /**
        * add new note.
        *
        * The new entry gets validated via note_validation and added to the memos table on success. In this case an
        * event is fired (new note). 
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.4.5
        * @version      0.4.7
        */
        function add_entry ($params) {
            global $db_hdl, $logger;  
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- sufficient rights? -------------------------------
            if (!$this->mayAddNote()) return "failure";

            // --- validate -----------------------------------------
            $this->entry['headline']->set_empty_allowed (false);
            $validation = $this->note_validation();
            if ($validation != "success") return $validation;
            
            // --- add entry ----------------------------------------            
            $add_query = "INSERT INTO ".TABLE_PREFIX."memos (
                                headline,
                                content,
                                is_dir,
                                priority,
                                starts,
                                due,
                                done,
                                followup,
                                parent		
                          )
                          VALUES (
                                '".$this->entry['headline']->getHTMLEscaped()."',
                                '".$this->entry['content']->getHTMLEscaped()."',
                                '0',
                                ".$this->entry['priority']->get().",
                                '".$this->entry['starts']->get("Y-m-d")."',
                                '".$this->entry['due']->get("Y-m-d")."',
                                '".$this->entry['done']->get()."',		
                                '".$this->entry['followup']->get("Y-m-d")."',
                                '".$this->entry['parent']->get()."'		
                               )";
            $logger->log ($add_query, 7);
            if (!$res = $this->ExecuteQuery ($add_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['memo_id']->set ($inserted_id);
            
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
                            '".$this->entry_type."',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            ".$this->entry['use_group']->get().",
							".getStateForNewObject($this->entry_type).",
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
            //echo $meta_query;

            $logger->log ($meta_query, 7);
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."memos WHERE memo_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_entry_id = $inserted_id;
            
			// --- set default --------------------------------------
			set_defaults ($params);
						
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'new '.$this->entry_type, 'system',$this->inserted_entry_id);
            
            return "success";
        }
               
      /**
        * updates note entry
        *
        * Uses intern notes validation and checks access rights. If the owner changes, it is checked
        * if the new owner has access to the (new) group. If not, the current user gets owner again.
        * Use Group equals 0 means the entry is an attachmente to another note (inheritance)
        * If there are any problems, examine
        * model->error_msg and model->info_msg;
        *
        * 27.02.2005:   Added notes as attachment
        * 01.03.2005:   Updating may mean deletion of attachments, too
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         check owner management
        * @todo         divide monster method
        * @since        0.4.5
        * @version      0.4.7
        */
        function    update_entry (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $this->entry['headline']->set_empty_allowed (false);
            $validation = $this->note_validation();
            if ($validation != "success") return $validation; 
                                
            
            // --- history, get_old values -----------------------------
            $old_entry_values   = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $this->entry['memo_id']->get()));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $this->entry['memo_id']->get()));

            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                                                          
            // --- update entry ----------------------------------------
            $contact_query = "UPDATE ".TABLE_PREFIX."memos SET 
                                headline          = '".$this->entry['headline']->getHTMLEscaped()."',
                                content           = '".$this->entry['content']->getHTMLEscaped()."',
                                priority          = ".$this->entry['priority']->get().",
                                due               = '".$this->entry['due']->get("Y-m-d")."',
                                starts            = '".$this->entry['starts']->get("Y-m-d")."',
                                followup          = '".$this->entry['followup']->get("Y-m-d")."',
                                done              = '".$this->entry['done']->get()."'
                              WHERE memo_id    = '".$this->entry['memo_id']->get()."'";

            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- add metainfo -------------------------------------
            $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                            grp          = ".$this->entry['use_group']->get().",
                            access_level = '".$this->entry['access']->get()."',
                            state        = ".$this->entry['state']->get().",
                            last_changer = ".$_SESSION['user_id'].",
                            last_change  = now()
                           WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['memo_id']->get()." 
                           ";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                
            // --- Does the owner still have access to the (new) group?
            if ($this->entry['use_group']->get() != 0) {
                $all_groups = get_all_groups ($this->entry['owner']->get());
                if (in_array ($this->entry['use_group']->get(), $all_groups)) {
                    // if so, the current user gets owner of the contact                
                    $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                    owner = ".$this->entry['owner']->get()."
                                    WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['memo_id']->get()." 
                                   ";
                    if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                }   
                else {
                    $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                    owner = ".$_SESSION['user_id']."
                                    WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['memo_id']->get()." 
                                   ";
                    if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                    $this->entry['owner']->set ($_SESSION['user_id']);
                    $this->info_msg = translate ('old user not member of new group');
    
                    return "failure";            	
                }	 
            }
            
            // --- update history -----------------------------------
            $ignore = array ();
            if ($this->entry_type == 'note') { 
                $ignore[] = "followup";                
                $ignore[] = "due";                
                $ignore[] = "priority";                
                $ignore[] = "starts";                
            }    
            update_history ($this->entry_type,                             // identifier for history table
                            "memos",                         // table
                            $this->entry['memo_id']->get(),  // object_id
                            array ("memo_id" => $this->entry['memo_id']->get()), 
                            $old_entry_values,
                            $ignore);
                            
            update_history ($this->entry_type,          
                            "metainfo",         
                            $this->entry['memo_id']->get(),
                            array ( "object_type" => $this->entry_type,
                                    "object_id"   => $this->entry['memo_id']->get()), 
                            $old_meta_values,
                            array ("last_changer", "last_change"));
                            
            // --- add attachment, if any ---------------------------------
            if (isset ($_REQUEST['attachment_content']) && ($_REQUEST['attachment_content'] != '')) {
                require_once ('../common/attachments.class.php');
                $ref_tab = new attachments_tab (
                    $this->entry['memo_id']->get(), // parent
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
                $external_refs = new external_references ($this->entry['memo_id']->get(),'note',false); 
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
                    $this->entry['memo_id']->get(),
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
            $this->addReferences ($this->entry_type, $this->entry['memo_id']->get(), $params, $this->entry['ref_desc']->get());
                            
            // --- unlock entry -------------------------------------
            $this->unlockEntry($this->entry_type, $this->entry['memo_id']->get());

            // --- set default --------------------------------------
			set_defaults ($params);
                            
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $this->entry['memo_id']->get());

            if (isset ($params['apply']) && $params['apply'] != '')
                return "apply";
                
            return "success";
        }

      /**
        * deletes entry
        *
        * Checks rights, fires event (deleted note), deletes any refering entries and attachments.
        * If there are any problems, examine model->error_msg and model->info_msg. 
        *
        * 17.3.05: Added parameter $parent_type
        * 
        * @access       public
        * @param        array   holds request variables
        * @param        boolean if set to true, the parents meta values (group and access rights) are
                                used to determine if the user has enough privileges to delete the entry. In
                                this case, the parameter "parent" must be set.
        * @param        boolean when use_inheritance is set to true, you have to pass the parents type
        * @return       string  success on success, otherwise failure
        * @since        0.4.5
        * @version      0.4.7
        * @todo         may not delete folders with children elements
        */
        function delete_entry ($params, $use_inheritance = false, $parent_type = NULL) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
			
            // --- history, get_old values -----------------------------
            $old_note_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['entry_id']));
            
            
            if ($use_inheritance) {
                $old_meta_values = get_entries_for_primary_key(
                                           "metainfo", array ("object_type" => $parent_type,
                                                              "object_id"   => $params['parent']));
            }
            else {          
                $old_meta_values = get_entries_for_primary_key(
                                           "metainfo", array ("object_type" => $this->entry_type,
                                                              "object_id"   => $params['entry_id']));
            }
                
            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'deleted '.$this->entry_type, 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."memos WHERE memo_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='".$this->entry_type."' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";

            // --- delete any refering entries in table refering ----
            $meta_query = "DELETE FROM ".TABLE_PREFIX."refering WHERE to_object_type='".$this->entry_type."' AND to_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $meta_query = "DELETE FROM ".TABLE_PREFIX."refering WHERE from_object_type='".$this->entry_type."' AND from_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            
            // --- delete any attachments ----------------------------
            $att_query = "SELECT memo_id FROM ".TABLE_PREFIX."memos WHERE parent='".$params['entry_id']."'";
            if (!$att_res = $this->ExecuteQuery($att_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            while ($att_row = mysql_fetch_array($att_res)) {
                $del_query = "DELETE FROM ".TABLE_PREFIX."memos WHERE memo_id='".$att_row['memo_id']."'";
                if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            
                // --- delete metainfo ----------------------------------
                $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='".$this->entry_type."' AND object_id='".$att_row['memo_id']."'";
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            }
                            
            // --- update any references in the sync table ----------
            $sync_query = "UPDATE ".TABLE_PREFIX."sync SET status='deleted locally' WHERE object_type='".$this->entry_type."' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($sync_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            
            return "success";
        }    
        
        
       /**
        * imports entry
        *
        * $params contains headline and content information, along with a product name (outlook
        * for example) and an syncronization identifier for the item (called sync_identifier), ("entry_id" in the case of outlook).
        * The entry gets added, if no corresponding item is found. If it is found and not marked as "locally deleted"
        * (in which case the method return "locally deleted"), the contents
        * of the entry are compared. If they are equal, the method returns "not changed". If not, the
        * method returns "changed". In any other (non-failure) case, the returned value is "imported".
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function import_entry ($params) {
            global $logger;
            
			$logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- find out if entry exists -------------------------
            $query = "SELECT * FROM ".TABLE_PREFIX."sync WHERE
                        user_id=".$_SESSION['user_id']." AND
                        sync_with='".$params['sync_with']."' AND
                        remote_identifier='".$params['identifier']."' AND
                        object_type='".$params['object_type']."'
                     ";
            if (!$sync_res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            $cnt   = mysql_num_rows($sync_res);
            if ($cnt == 0) { // entry does not yet exist, add it
                $params ["ref_object_type"] ='';
                $params ["ref_object_id"] ='';
                $params ["use_group"] = get_main_group($_SESSION['user_id']);
                $result = $this->add_entry($params);
                if (!$result == "success") 
                    return "result";
                // sync eintragen
                $query = "INSERT INTO ".TABLE_PREFIX."sync
                            (user_id, object_type, object_id, sync_with, remote_identifier, synced, timeoffset, status)
                          VALUES (
                            ".$_SESSION['user_id'].",
                            '".$this->entry_type."',
                            ".$this->inserted_entry_id.",
                            '".$params['sync_with']."',
                            '".$params['identifier']."',
                            now(),
                            0,
                            'imported'
                          )";
                if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                return "imported";
            }
            else { // entry does exists, compare
                $sync_row = mysql_fetch_array($sync_res);
                if ($sync_row['status'] == "deleted locally")
                    return "deleted locally";
                    
                $query    = "SELECT * FROM ".TABLE_PREFIX."memos WHERE memo_id=".$sync_row['object_id'];
                if (!$note_res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                $note_row      = mysql_fetch_array($note_res);
                if ($note_row['headline'] != $params['headline'] ||
                    $note_row['content']  != $params['content']) return "changed";
                return "not changed";
            }        
        }
        
       /**
        * syncronizes entry
        *
        * $params contains headline and content information, along with a product name (outlook
        * for example) and an syncronization identifier for the item (called sync_identifier), ("entry_id" in the case of outlook).
        * The entry gets added, if no corresponding item is found. If it is found, it gets updated if its
        * last_changed date is older than the last_changed + timeoffset param.
        * Added or updated entries are marked in the sync table to be ignored when updating the remote client.
        * 
        * deleted entries?
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function sync_entry ($params) {
            global $logger;

			$logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
			$logger->log (print_r ($params, true), 7);
            $logger->log (date ("d.m.Y H:i:s", $params['last_change']), 7);
            // try to import entry
            $result = $this->import_entry($params);
			$logger->log ($result, 7);
            
            switch ($result) {
                case "imported" :
                    return "imported";
                    break;
                case "not changed" :
                    return "not changed";
                    break;
                case "imported" :
                    return "imported";
                    break;
                case "deleted locally" :
                    return "deleted locally";
                    break;
                case "changed" :
                    // Find out if the last change (locally) is older than last syncronization
                    $query = "SELECT 
                                synced + 0 AS synced, 
                                timeoffset, 
                                status, 
                                object_id
                              FROM ".TABLE_PREFIX."sync WHERE
                                user_id=".$_SESSION['user_id']." AND
                                sync_with='".$params['sync_with']."' AND
                                remote_identifier='".$params['identifier']."' AND
                                object_type='".$params['object_type']."'
                             ";
                    if (!$sync_res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                    $sync_row      = mysql_fetch_array($sync_res);
                    
                    $query = "SELECT last_change + 0 AS last_change, ".TABLE_PREFIX."metainfo.state, ".TABLE_PREFIX."metainfo.owner FROM ".TABLE_PREFIX."memos
                              LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."memos.memo_id=".TABLE_PREFIX."metainfo.object_id
                              WHERE ".TABLE_PREFIX."metainfo.object_id=".$sync_row['object_id']." AND ".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."';
                             ";
                    if (!$meta_res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                    $meta_row      = mysql_fetch_array($meta_res);
                    
                    $update_entry  = false;
                    if ($meta_row['last_change'] == '' OR $meta_row['last_change'] == '0') { 
                        // never changed locally, => update
                        $update_entry = true;
                    }    
                    elseif ($meta_row['last_change'] <= $sync_row['synced']) {
                        // entry was not changed locally after last syncronization
                        $update_entry = true;    
                    }    
                    elseif (time($params['last_change']) <= $sync_row['synced']) {
                    	// entry was not changed on remote side
                        $update_entry = true;    
                    }
                    else {
                    	// entry was changed remote AND locally
                        return "conflict";                        
                    }    
                     
                    if ($update_entry) {   
                        $params['memo_id'] = $sync_row['object_id'];
                        $params['state']   = $meta_row['state'];
                        $params['owner']   = $meta_row['owner'];
                        $result = $this->update_entry($params);
                        
                        if ($result == "success") {
                            // update sync information
                            $query = "UPDATE ".TABLE_PREFIX."sync SET
                                        synced=now(),
                                        status='updated'
                                      WHERE object_type='".$this->entry_type."' AND
                                            object_id=".$sync_row['object_id'];
                            if (!$sync_res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) 
                                return "failure";
                        }    
                        
                        return $result;
                    }    
                    return "not last change".$meta_row['last_change'].")";
                    
                    break;
                default:
                    die ("unexpected result in ".__FILE__." ".__LINE__.": ".$result." (".$this->error_msg.")");
                    break;
            }    
        }
            
      /**
        * deletes a list of entries
        *
        * The list of entries to be deleted gets passed in the form note_XXX.
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @todo         add public function which takes "real" array with ids
        * @since        0.4.5
        * @version      0.4.5
        */
        function delete_entries (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $del_list = array ();
            foreach ($params AS $key => $value) {
                if (substr ($key, 0,5) == $this->entry_type."_") {
                    $del_list[] = (int)substr ($key,5);    
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
        * get list of parents names
        *
        * @access       public
        * @param        int id of node where to start
        * @return       array holding parents names and ids
        * @since        0.4.7
        * @version      0.4.7
        */
        function getParentChain ($start_id, $chain = null) {
            global $db_hdl, $logger;

            $sql = "SELECT memo_id, headline, parent 
                    FROM ".TABLE_PREFIX."memos
                    WHERE memo_id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', __FILE__, __LINE__)) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['headline'];
                $chain[$cnt]['id']     = $row['memo_id'];                
                $chain += $this->getParentChain ($row['parent'], $chain);
            }	
            return $chain;
        }
        
      /**
        * Show all entries.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. 
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.4.4
        * @version      0.4.4
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if (is_null($query)) { // set default query
                $query = $this->getSelectQuery();
            }  
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND mi.grp=".$_SESSION['use_my_group'];       
            
            if ($_SESSION['use_my_state'] > 0)
                $query .= " AND mi.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND mi.owner=".$_SESSION['use_my_owner'];       
            
            $query .= " AND ".$this->getWhereQuery ();    
			$query .= "ORDER BY is_dir DESC";

			$this->dg = new datagrid (20, $this->entry_type."s", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=2;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                       
            return "success";
        }
        
      /**
        * Search notes.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. 
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.1
        * @version      0.5.1
        */
        function search (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
        
            $search_for = $params['keyword'];
            
            $query = "
                SELECT
                    memo_id,
					headline,
					content,
					creator,
					CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
					ag.name AS grp,
					created,
					last_changer,
					last_change,
					access_level,  
                    is_dir,
                    mi.owner AS owner_id,
                    mi.grp   AS group_id,
                    '#000000'      AS color
	            FROM ".TABLE_PREFIX."memos 
                LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."memos.memo_id
                LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ag ON mi.grp=ag.id
                LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=mi.owner
				LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
				WHERE 
					gd.mandator_id=".$_SESSION['mandator']." AND 
					mi.object_type='".$this->entry_type."' AND
					(headline LIKE '%".$search_for."%' OR content LIKE '%".$search_for."%')";

			$this->show_entries($params, $query);
            
			$this->info_msg .= translate ('searched for')." ".$this->entry['keyword']->get();			

			return "success";
        }
                    
       /**
        * Shows single note.
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.4.5
        * @version      0.4.5
        */
        function show_entry (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."memos
                LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."memos.memo_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."' AND ".TABLE_PREFIX."memos.memo_id=".$params['entry_id'].")";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
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
            
            // --- group == 0 means: entry is attachement (inheritance!)
            /*if ($row['grp'] == 0) {
                $parents_values = get_entries_for_primary_key(
                                           "memos", array ("memo_id" => $row['parent']));

                $this->entry['parents_headline']->set($parents_values['headline']);    
            }  */  
            
                        
            // --- adjust some values -------------------------------
            $this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);

            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy($this->entry_type, $this->entry['memo_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
            }
            else {
                // --- lock contact when not readonly ---------------
                //$this->info_msg .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ($this->entry_type, $this->entry['memo_id']->get());
            }
           
            // --- if entry is a folder, adjust view ----------------
            if ((bool)$row['is_dir']) {
                return "redirect";    
            }    

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
            assert ('(int)$params["entry_id"] > 0');
            assert ('(int)$params["move_to"] >= 0');
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_note_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['entry_id']));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $params['entry_id']));
            
            
            // --- validation ---------------------------------------
            // target must be folder
            $target_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['move_to']));   
                                                                                            
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
            if ($params['entry_id'] == $params['move_to']) {
                $this->info_msg .= translate ('cannot move item to itself');
                return "failure";
            }    
                       
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $params['entry_id']);

            // --- update entry -------------------------------------
            $update_query = "UPDATE ".TABLE_PREFIX."memos SET parent=".$params['move_to']." WHERE memo_id='".$params['entry_id']."'";
            if (!$this->ExecuteQuery ($update_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                                    
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";
                 
            // --- update history -----------------------------------
            update_history ($this->entry_type,                             // identifier for history table
                            "memos",                         // table
                            $this->entry['memo_id']->get(),  // object_id
                            array ("memo_id" => $this->entry['memo_id']->get()), 
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
        * @since        0.4.7
        * @version      0.4.7
        */
        function moveEntries (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $move_list = array ();

            foreach ($params AS $key => $value) {
                if (substr ($key, 0,5) == $this->entry_type."_") {
                    $move_list[] = (int)substr ($key,5);    
                }    
            }    
                     
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
            
            // --- delete entries -----------------------------------
            foreach ($move_list AS $key => $move_id) {
                $wrapper = array ("entry_id" => $move_id, "move_to" => $params['move_to']);
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
        * unset view.
        *
        * private method to unset locking of contact
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string "success"
        * @todo         check if function is used at all. references contact!
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
        * get name for given folder id
        *
        * function asserts that given id actually exists and is a folder
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @since        0.4.4
        * @version      0.4.4
        */
        function get_folder_name ($folder_id = 0) {
            global $db_hdl, $logger;

            if ($folder_id == 0) return '';
            
            $sql = "SELECT headline, is_dir
                    FROM ".TABLE_PREFIX."memos
                    WHERE memo_id='$folder_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', __FILE__, __LINE__)) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            assert ('(bool)$row["is_dir"] == true');
            return $row['headline'];
        }

       /**
        * gets folders.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2005
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function getFolders (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $nodes = "
                var TREE_NODES = [";
            $nodes .= $this->add_nodes (0,20);    
            $nodes .= "    
                ];
            ";

            $this->entry['foldernodes']->set ($nodes);

            return "success";
        }
        
  	  /**
        * calculates javascript for tree
        *
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @since        0.4.7
        * @version      0.4.7
        * @todo         access rights in statement
        */
        function add_nodes ($root, $offset=0) {
            
            global $db_hdl, $logger;

            $js = "\n";
            for ($i=0; $i < $offset; $i++) $js .= " ";
            
            $query = "
                SELECT * 
                  FROM ".TABLE_PREFIX."memos 
                  LEFT JOIN ".TABLE_PREFIX."metainfo
                    ON ".TABLE_PREFIX."memos.memo_id=".TABLE_PREFIX."metainfo.object_id                
                 WHERE parent=$root AND
                       object_type='".$this->entry_type."' AND
                       is_dir != 0
                ORDER BY headline";
            //echo $query."<br>";
            
            $res   = mysql_query ($query);
            if (mysql_num_rows($res) == 0)
                return '';
                
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            while ($row = mysql_fetch_array ($res)) {
                $name   = $row['headline'];
                
                $js .= "['$name', 'javascript:setMoveTarget(\'".$name."\', ".$row['memo_id'].")', null, ";
                $js .= $this->add_nodes ($row['memo_id'], $offset+10);    
                $js .= "],\n";    
            }    
            
            return $js;
        }

       /**
        * export entries.
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.1
        * @version      0.5.1
        */
        function export ($params) {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $query   = $this->getSelectQuery(true);
            //$query  .= " ORDER BY parent_id, headline
            $res     = mysql_query ($query);
            $columns = mysql_num_fields($res);

            switch ($params['export_as']) {
            	case 'text':
                    header("Content-type: text/plain"); 
                    header("Content-Disposition: attachment; filename=export.txt"); 
                    $line    = 2;
                    while ($row = mysql_fetch_array ($res)) {
                        
                        // Headline and Info
                        echo $row['headline']."\n";
                        for ($i=0; $i < strlen ($row['headline']); $i++) echo "=";
                        echo "\n";
                        ($row['last_change'] != '') ? $date = $row['last_change'] : $date = $row['created'];
                        echo "[".$date."]\n\n";
                        
                        // Content
                        $content = $row['content'];
                        $tmp = html_entity_decode($content)."\n";
                        $tmp = str_replace ("&nbsp;", " ", $tmp);
                        $tmp = str_replace ("<br />", "", $tmp);
                        $tmp = str_replace ("<p>", "", $tmp);
                        $tmp = str_replace ("</p>", "", $tmp);
                        //$tmp = strip_tags ($tmp);
                        echo $tmp."\n";
                        
                        // References:
                        //if (user_may_read ($meta_values['owner'], $meta_values['grp'], $meta_values['access_level'])) { 
        
                        require_once ('../common/attachments.class.php');
                        $ref_tab = new attachments_tab (
                            $row['memo_id'],
                            'note', 
                            false); 
                        echo $ref_tab->getAsText ();
                        echo "\n";
                                
                        $line++;
                    }    
            		break;
            	case "rtf":
            		Header('Content-Type: application/rtf');
            		Header('Content-disposition: inline; filename=mydoc.rtf');
                    $tmpl = '{\rtf1\ansi\ansicpg1252\uc1\deff0\stshfdbch0\stshfloch0\stshfhich0\stshfbi0\deflang1031\deflangfe1031{\fonttbl{\f0\froman\fcharset0\fprq2{\*\panose 02020603050405020304}Times New Roman;}
{\f2\fmodern\fcharset0\fprq1{\*\panose 02070309020205020404}Courier New;}{\f77\froman\fcharset238\fprq2 Times New Roman CE;}{\f78\froman\fcharset204\fprq2 Times New Roman Cyr;}{\f80\froman\fcharset161\fprq2 Times New Roman Greek;}
{\f81\froman\fcharset162\fprq2 Times New Roman Tur;}{\f82\froman\fcharset177\fprq2 Times New Roman (Hebrew);}{\f83\froman\fcharset178\fprq2 Times New Roman (Arabic);}{\f84\froman\fcharset186\fprq2 Times New Roman Baltic;}
{\f85\froman\fcharset163\fprq2 Times New Roman (Vietnamese);}{\f97\fmodern\fcharset238\fprq1 Courier New CE;}{\f98\fmodern\fcharset204\fprq1 Courier New Cyr;}{\f100\fmodern\fcharset161\fprq1 Courier New Greek;}
{\f101\fmodern\fcharset162\fprq1 Courier New Tur;}{\f102\fmodern\fcharset177\fprq1 Courier New (Hebrew);}{\f103\fmodern\fcharset178\fprq1 Courier New (Arabic);}{\f104\fmodern\fcharset186\fprq1 Courier New Baltic;}
{\f105\fmodern\fcharset163\fprq1 Courier New (Vietnamese);}}{\colortbl;\red0\green0\blue0;\red0\green0\blue255;\red0\green255\blue255;\red0\green255\blue0;\red255\green0\blue255;\red255\green0\blue0;\red255\green255\blue0;\red255\green255\blue255;
\red0\green0\blue128;\red0\green128\blue128;\red0\green128\blue0;\red128\green0\blue128;\red128\green0\blue0;\red128\green128\blue0;\red128\green128\blue128;\red192\green192\blue192;}{\stylesheet{
\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs24\lang1031\langfe1031\cgrid\langnp1031\langfenp1031 \snext0 Normal;}{\*\cs10 \additive \ssemihidden Default Paragraph Font;}{\*
\ts11\tsrowd\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv 
\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs20\lang1024\langfe1024\cgrid\langnp1024\langfenp1024 \snext11 \ssemihidden Normal Table;}{\s15\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 
\f2\fs20\lang1031\langfe1031\cgrid\langnp1031\langfenp1031 \sbasedon0 \snext15 \styrsid6448827 Plain Text;}}{\*\rsidtbl \rsid16271\rsid1451326\rsid5599371\rsid5716158\rsid5919534\rsid6448827\rsid8013117\rsid11697437}
{\*\generator Microsoft Word 10.0.5522;}{\info{\author Carsten Graef}{\operator Metafinanz}{\creatim\yr2005\mo6\dy17\hr11\min59}{\revtim\yr2005\mo6\dy17\hr16\min56}{\version5}{\edmins0}{\nofpages1}{\nofwords5}{\nofchars37}{\*\company evandor}
{\nofcharsws41}{\vern16495}}\paperw11906\paperh16838\margl1152\margr1152\margt1417\margb1134 \deftab708\widowctrl\ftnbj\aenddoc\hyphhotz425\noxlattoyen\expshrtn\noultrlspc\dntblnsbdb\nospaceforul\hyphcaps0\formshade\horzdoc\dgmargin\dghspace180
\dgvspace180\dghorigin1152\dgvorigin1417\dghshow1\dgvshow1
\jexpand\viewkind1\viewscale100\pgbrdrhead\pgbrdrfoot\splytwnine\ftnlytwnine\htmautsp\nolnhtadjtbl\useltbaln\alntblind\lytcalctblwd\lyttblrtgr\lnbrkrule\nobrkwrptbl\snaptogridincell\allowfieldendsel\wrppunct\asianbrkrule\rsidroot5919534 \fet0\sectd 
\linex0\headery708\footery708\colsx708\endnhere\sectlinegrid360\sectdefaultcl\sectrsid6448827\sftnbj {\*\pnseclvl1\pnucrm\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl2\pnucltr\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl3
\pndec\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl4\pnlcltr\pnstart1\pnindent720\pnhang {\pntxta )}}{\*\pnseclvl5\pndec\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl6\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}
{\*\pnseclvl7\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl8\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl9\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}\pard\plain 
\s15\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid6448827 \f2\fs20\lang1031\langfe1031\cgrid\langnp1031\langfenp1031 {\insrsid11697437 ###header###}{\insrsid1451326 
\par }{\insrsid11697437 
\par ###content###
\par 
\par ###footer###}{\insrsid11697437 
\par }}';

                    $header = "\b test";
                    $tmpl   = str_replace ('###header###', $header, $tmpl);
                    echo $tmpl;            
                    die ();
            		break;
            	default:
            	    die ("unknown export_as parameter in ".__FILE__);
            		break;
            }
            
            die();
        }
        
 	  /**
        * clears filter
        *
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @todo         move to leads4web_model?
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
        *
        * 
        * @access       private
        * @todo         move to leads4web_model?
        * @since        0.5.1
        * @version      0.5.1
        */
        function getSelectQuery ($ignoreParent = false) {
        
            $query = "
                    SELECT
                        memo_id,
						headline,
						content,
						creator,
						CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
						ag.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,  
                        is_dir,
                        mi.owner AS owner_id,
                        mi.grp   AS group_id,
                        '#000000'      AS color
		            FROM ".TABLE_PREFIX."memos 
                    LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."memos.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ag ON mi.grp=ag.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=mi.owner
					LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
					WHERE 
						gd.mandator_id=".$_SESSION['mandator']." AND 
						mi.object_type='".$this->entry_type."'";
            
            if (!$ignoreParent) {
                $query .= " AND
                    	parent=".$this->entry['parent']->get();
            }
            return $query;
        }

 	  /**
        * which entries is the current user allowed to see?
        *
        * 
        * @access       private
        * @todo         move to leads4web_model?
        * @since        0.5.1
        * @version      0.5.1
        */
        function getWhereQuery () {
            $ret =  "(
                        	  mi.owner=".$_SESSION['user_id']." 
                			OR
		                      (".get_all_groups_or_statement ($_SESSION['user_id'])." AND mi.access_level LIKE '____r_____') 
			                OR
                     		   mi.access_level LIKE '_______r__'	
                        )
                    ";
            return $ret;
        }

        /**
        * is the current user allowed to add a note? 
        * 
        * @access       private
        * @since        0.5.1
        * @version      0.5.1
        */
        function mayAddNote () {
            $mayAdd = true;
            
            $parent = $this->entry['parent']->get();
            
            if ($parent > 0) {
                $parent_folder = get_entries_for_primary_key(
                                           "metainfo", array ("object_type" => $this->entry_type,
                                                              "object_id"   => $parent));
                if (!user_may_edit ($parent_folder['owner'], $parent_folder['grp'], $parent_folder['access_level'])) {
                    $this->info_msg .= translate ('parent folder not writable',null,true);    
                }    
            }    
            return $mayAdd;
        }

    }   

?>