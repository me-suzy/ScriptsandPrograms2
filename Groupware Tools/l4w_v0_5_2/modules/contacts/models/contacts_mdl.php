<?php

   /**
    * $Id: contacts_mdl.php,v 1.43 2005/08/01 14:55:13 carsten Exp $
    *
    * model for handling contacts 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */
    
    /**
      * include fields validation rules
      */  
    include ('fields_validations.inc.php');
    
   /**
    * Contacts Model
    *
    * model for handling contacts 
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package contacts
    */    
    class contacts_model extends l4w_model {
         
        /**
          * int holding the id of an added user entry
          *
          * @access public
          * @var string
          */  
        var $inserted_contact_id = null;     // ID for user when adding was successfull
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @since        0.4.0
        * @version      0.4.4
        */
        function contacts_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("assign_view",            // assign entries (view)
                	   "show_entries",           // list of all entries
                       "show_locked",            // list of all entries
                       "add_contact",            // add new contact (action)
                       "add_contact_view",       // add new contact (view)
                       "export_excel",           // export
                       "show_contact",           // (view)
                       "update_contact",         // (action)
                       "delete_entry",           // (action)
                       "unset_current_view",     // unset current view in SESSION (unlock)
                       "help"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }

      /**
        * validates new or updated entry.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.0
        * @version      0.4.4
        */
        function contact_validation () {
            global $db_hdl, $logger;
            
            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$group;
            //$res   = mysql_query($query);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}
            /*if (($this->entry['salutation']->get() == "Mr" || $this->entry['salutation']->get() == "Mrs") &&
                    $this->entry['salutation_letter']->get() == translate('dear mr dear mrs')) {
                $this->error_msg = translate('salutation_letter_not_changed');
                return "failure";
            }*/
            if ($this->entry['birthday']->get() == "dd.mm.yyyy")
                $this->entry['birthday']->set ('');
                
            if (trim ($this->entry['birthday']->get()) != '') {     
                $birthday       = explode (".",$this->entry['birthday']->get());
                if (!checkdate ((int)$birthday[1],(int)$birthday[0],(int)$birthday[2])) {            
                    $this->error_msg = translate('no valid birthday');
                    return "failure";
                }
                if (mktime() < adodb_mktime (0,0,0,(int)$birthday[1],(int)$birthday[0],(int)$birthday[2])) { 
                    $this->error_msg = translate('birthday in future');
                    return "failure";
                }
            }

            // --- validate all fields in entries -----------------
            $ok = true;
            reset($this->entry); 
			while (list($key, $val) = each($this->entry)) { 
            	$result = $this->entry[$key]->get();
            	$error  = $this->entry[$key]->error;
            	if ($error != '') {
	            	$this->error_msg   .= translate ($key).": ".translate ($error)."<br>";	
	            	$this->entry[$key]->class = "alert";
           			$ok = false;
            	}	
            }	
            if (!$ok) return "failure";

            return "success";
        }    
       
      /**
        * add new contact.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * This function calls contact_validation, inserts new entry into contacts
        * and to table metainfo. If the entry cannot be inserted to table contacts, the
        * function returns. If there is a problem with the table metainfo, the contact
        * entry is deleted before returning.
        * At last, an identity hash is calculated and set, and an event (contact, new, system)
        * is fired.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.0
        * @version      0.4.3
        */
        function add_contact (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->contact_validation();
            if ($validation != "success") return $validation; 
            
            // --- init ------------------------------------------------
            $tmp = $this->entry['birthday']->get();
            $birthday = '';
            if ($tmp != '') {
                $tmp = explode (".", $tmp);
                $birthday = $tmp[2]."-".$tmp[1]."-".$tmp[0];    
            }    

            // --- add entry ----------------------------------------            
            $contact_query = "INSERT INTO ".TABLE_PREFIX."contacts (
                                salutation,
                                salutation_letter,
                                title,
                                firstname,
                                lastname,
                                email,
                                company,
                                department,
                                function,
                                phone_private1, phone_private2,
                                phone_company1, phone_company2,
                                mobile_phone,
            					fax,
                                street,
                                zipcode,
                                city,
                                country,
                                homepage,
                                birthday,
                                freetext1,
                                freetext2,
                                freetext3, 
                                remark)
                               VALUES (
                                '".$this->entry['salutation']->get()."',
                                '".$this->entry['salutation_letter']->get()."',
                                '".$this->entry['title']->get()."',
                                '".$this->entry['firstname']->get()."',
                                '".$this->entry['lastname']->get()."',
                                '".$this->entry['email']->get()."',
                                '".$this->entry['company']->get()."',
                                '".$this->entry['department']->get()."',
                                '".$this->entry['function']->get()."',
                                '".$this->entry['phone_private1']->get()."', '".$this->entry['phone_private2']->get()."',
                                '".$this->entry['phone_company1']->get()."', '".$this->entry['phone_company2']->get()."',
                                '".$this->entry['mobile_phone']->get()."',
            					'".$this->entry['fax']->get()."',
                                '".$this->entry['street']->get()."',
                                '".$this->entry['zipcode']->get()."',
                                '".$this->entry['city']->get()."',
                                ".$this->entry['country']->get().",
                                '".$this->entry['homepage']->get()."',
                                '$birthday',
                                '".$this->entry['freetext1']->get()."',
                                '".$this->entry['freetext2']->get()."',
                                '".$this->entry['freetext3']->get()."',
                                '".$this->entry['remark']->get()."'
                               )";
            $logger->log ($contact_query, 7);
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['contact_id']->set ($inserted_id);
            
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
                            'contact',
                            $inserted_id,
                            ".$_SESSION['user_id'].",
                            ".$_SESSION['user_id'].",
                            ".$this->entry['use_group']->get().",
                            ".getStateForNewObject('contact').",
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
            $logger->log ($meta_query, 7);
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."contacts WHERE contact_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_contact_id = $inserted_id;

            // --- add alternative emails ---------------------------
            if ($this->checkAdditionalEmails () != "success") return "failure";

			// --- identity hash ------------------------------------
            $this->setIdentityHash ($this->inserted_contact_id);
            
            // --- set default --------------------------------------
			set_defaults ($params);
						
			// --- fire event ---------------------------------------
            $event_error = fireEvent ($this, 'contact', 'new', 'system', $this->inserted_contact_id);
			if (!is_null($event_error) && $event_error != '') {
				$this->info_msg .= "Event Error: ".translate ($event_error);
        	}					            
            return "success";
        }

      /**
        * updates contact entry
        *
        * Uses intern contact validation. If there are any problems, examine
        * model->error_msg and model->info_msg;
        *
        * At first, the given parameters are validated via contact_validation.
        * The old values are saved temporarily to add the changed fields to the history
        * at the end. Then the table contacts gets updated (return on failure). After that
        * the tabe metainfo is updated. (return on failure). If the (maybe new) owner of
        * the contact has no access to the (maybe new) group, the ownership is set to
        * the current user and an info message is set. Then the identity hash is recalculated,
        * the history is updated, memos are added (if any), the entry gets unlocked and
        * an event is fired (contact, changed, system).
        *
        * 19.12.2004:   added check for sufficient rights 
        * 09.02.2005:   added heredity constraints for tickets
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.0
        * @version      0.4.6
        */
        function update_contact (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->contact_validation();
            if ($validation != "success") return $validation; 
                        
            // --- init ---------------------------------------------
            $tmp = $this->entry['birthday']->get();
            $birthday = '';
            if ($tmp != '') {
                $tmp = explode (".", $tmp);
                $birthday = $tmp[2]."-".$tmp[1]."-".$tmp[0];    
            }    
            
            // --- history, get_old values -----------------------------
            $old_contact_values = get_entries_for_primary_key (
                                       "contacts", array ("contact_id" => $this->entry['contact_id']->get()));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'contact',
                                                          "object_id"   => $this->entry['contact_id']->get()));
            $old_cat_values     = get_entries_for_key(
                                       "refering", array ("from_object_type" => 'contact',
                                                          "from_object_id"   => $this->entry['contact_id']->get(),
                                                          "to_object_type"   => 'collection'),
                                       "to_object_id");

            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                                                          
            // --- update entry ----------------------------------------
            $contact_query = "UPDATE ".TABLE_PREFIX."contacts SET 
                                salutation        = '".$this->entry['salutation']->get()."',
                                salutation_letter = '".$this->entry['salutation_letter']->get()."',
                                title             = '".$this->entry['title']->get()."',
                                firstname         = '".$this->entry['firstname']->get()."',
                                lastname          = '".$this->entry['lastname']->get()."',
                                email             = '".$this->entry['email']->get()."',
                                company           = '".$this->entry['company']->get()."',
                                department        = '".$this->entry['department']->get()."',
                                function          = '".$this->entry['function']->get()."',
                                phone_private1    = '".$this->entry['phone_private1']->get()."', 
                                phone_private2    = '".$this->entry['phone_private2']->get()."',
                                phone_company1    = '".$this->entry['phone_company1']->get()."', 
                                phone_company2    = '".$this->entry['phone_company2']->get()."',
            					mobile_phone      = '".$this->entry['mobile_phone']->get()."',
                                fax               = '".$this->entry['fax']->get()."',
                                street            = '".$this->entry['street']->get()."',
                                zipcode           = '".$this->entry['zipcode']->get()."',
                                city              = '".$this->entry['city']->get()."',
                                country           = ".$this->entry['country']->get().",
                                homepage          = '".$this->entry['homepage']->get()."',
                                birthday          = '$birthday', 
                                freetext1         = '".$this->entry['freetext1']->get()."',
                                freetext2         = '".$this->entry['freetext2']->get()."',
                                freetext3         = '".$this->entry['freetext3']->get()."',
                                remark            = '".$this->entry['remark']->get()."'
                              WHERE contact_id    = '".$this->entry['contact_id']->get()."'";
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- add metainfo -------------------------------------
            $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                            grp          = ".$this->entry['use_group']->get().",
                            state        = ".$this->entry['state']->get().",
                            last_changer = ".$_SESSION['user_id'].",
                            last_change  = now()
                           WHERE object_type='contact' AND object_id=".$this->entry['contact_id']->get()." 
                           ";
            if (!$this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

			// --- info when changing entry to state -1 (i.e. undefined) ---
			if ($this->entry['state']->get() == -1 && $old_meta_values['state'] != -1) {
				$this->info_msg .= translate ('state was changed to undefined');	
			}
				
			// --- change access level only if allowed to delete entry ---
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
	            $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
    	                        access_level = '".$this->entry['access']->get()."'
        	                   WHERE object_type='contact' AND object_id=".$this->entry['contact_id']->get()." 
            	               ";
            	if (!$this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            }
                
            // --- add alternative emails ---------------------------
            if ($this->checkAdditionalEmails () != "success") return "failure";
                            
            // --- consider heredity --------------------------------
            $tickets_query = "SELECT ticket_id FROM ".TABLE_PREFIX."tickets WHERE contact_id=".$this->entry['contact_id']->get();
            if (!$tickets_res = $this->ExecuteQuery ($tickets_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            while ($tickets_row = mysql_fetch_array($tickets_res)) {
                $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                grp          = ".$this->entry['use_group']->get().",
                                access_level = '".$this->entry['access']->get()."'
                               WHERE object_type='ticket' AND object_id=".$tickets_row['ticket_id']." 
                               ";
                if (!$this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";                
            }    
            
            // --- Does the (new?) owner still have access to the (new) group?
            $all_groups = get_all_groups ($this->entry['owner']->get());
            if (in_array ($this->entry['use_group']->get(), $all_groups)) {
                // if so, the current user gets owner of the contact                
                $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                owner = ".$this->entry['owner']->get()."
                                WHERE object_type='contact' AND object_id=".$this->entry['contact_id']->get()." 
                               ";
                //$logger->log ($meta_query, 7);
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                //$res = mysql_query ($meta_query);
                
                // fire event if owner was changed
                if (mysql_affected_rows() > 0) {
	                $event_error = fireEvent ($this, 'contact', 'assigned', 'system', $this->entry['contact_id']->get());
					if (!is_null($event_error)) {
						$this->info_msg .= translate ($event_error);
        			}
                }
            }   
            else {
                $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                owner = ".$_SESSION['user_id']."
                                WHERE object_type='contact' AND object_id=".$this->entry['contact_id']->get()." 
                               ";
                //$logger->log ($meta_query, 7);
                //$res = mysql_query ($meta_query);
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                $this->entry['owner']->set ($_SESSION['user_id']);
                $this->error_msg = translate ('new carer is not a member of entries group');

                return "failure";            	
            }	 
                 
            // --- identity hash ------------------------------------
            $this->setIdentityHash ($this->entry['contact_id']->get());
            								
            if ($this->error_msg != "")
                return "failure";

            // --- add references -----------------------------------
            $this->addReferences ('contact', $this->entry['contact_id']->get(), $params, $this->entry['ref_desc']->get());

            // --- update history -----------------------------------
            update_history ("contact",                          // identifier for history table
                            "contacts",                         // table
                            $this->entry['contact_id']->get(),  // object_id
                            array ("contact_id" => $this->entry['contact_id']->get()), 
                            $old_contact_values,
                            array ("identity_hash"));           // dont log identity hash
            update_history ("contact",          
                            "metainfo",         
                            $this->entry['contact_id']->get(),
                            array ( "object_type" => 'contact',
                                    "object_id"   => $this->entry['contact_id']->get()), 
                            $old_meta_values,
                            array('last_change','last_changer'));
            update_history_array
            			   ("contact",          
                            "refering",         
                            $this->entry['contact_id']->get(),
                            array ( "from_object_type" => 'contact',
                                    "from_object_id"   => $this->entry['contact_id']->get(),
                                    "to_object_type"   => 'collection'), 
                            $old_cat_values,
                            'to_object_id',
                            array("from_object_type", "from_object_id", "to_object_type","ref_type","ref_scheme"));
			
			// --- add attachment, if any ---------------------------------
            if (isset ($_REQUEST['attachment_content']) && ($_REQUEST['attachment_content'] != '')) {
                require_once ('../common/attachments.class.php');
                $ref_tab = new attachments_tab (
                    $this->entry['contact_id']->get(), // parent
                    'contact', 
                    false); 
                //$ref_tab->setTabNr   (2);
                //$ref_tab->setImgPath ('');
                
                $done = $ref_tab->addAttachment ('contact', 'note', $_REQUEST);
                if ($done != "success") 
                    return "failure";
            }

			// --- add external links, if any -----------------------   
            if (isset ($_REQUEST['external_link_path']) && ($_REQUEST['external_link_path'] != '')) {
                require_once ('../common/external_references.class.php');
                $external_refs = new external_references ($this->entry['contact_id']->get(),'contact',false); 
                //$external_refs->setTabNr   (2);
                //$external_refs->setImgPath ('');
                
                $done = $external_refs->addExternalLink ($this->entry, 'contact', $_REQUEST);
                if ($done != "success") 
                    return "failure";
            }
            
            // --- and delete attachments, if delete_attachments given ---
            if (isset ($params['delete_attachments']) && $params['delete_attachments'] != '') {
                require_once ('../common/attachments.class.php');
                $att_tab = new attachments_tab (
                    $this->entry['contact_id']->get(),
                    'contact', 
                    false); 
                //$ref_tab->setTabNr   (2);
                //$ref_tab->setImgPath ('');
                $att_tab->setModel   ($this);
                
                $done = $att_tab->deleteAttachments ($params);
                if ($done != "success") {
                    $this->error_msg .= "internal error deleting attachment, ".__FILE__." (".__LINE__.")";
                    return "failure";
                }    
            }
            
                                                     
            // --- unlock entry -------------------------------------
            $this->unlockEntry('contact', $this->entry['contact_id']->get());
                                        
			// --- fire event ---------------------------------------
            fireEvent ($this, 'contact', 'changed', 'system', $this->entry['contact_id']->get());

            if (isset ($params['apply']) && $params['apply'] != '')
                return "apply";
                
            return "success";
        }

      /**
        * deletes contact entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. First it is
        * checked if the current user has sufficient rights to delete this entry. Then an event
        * is fired (contact, deleted, system). Then the contacts table entry is deleted, afterwards
        * the metainfo entry. Finally, any quicklinks referring to the entry are deleted.
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.0
        * @version      0.4.4
        */
        function delete_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_contact_values = get_entries_for_primary_key (
                                       "contacts", array ("contact_id" => $params['entry_id']));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'contact',
                                                          "object_id"   => $params['entry_id']));
            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }

            // --- fire event ---------------------------------------
            fireEvent ($this, 'contact', 'deleted', 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."contacts WHERE contact_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete quicklinks --------------------------------
            $ql_query = "DELETE FROM ".TABLE_PREFIX."quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- update history -----------------------------------
            update_history ("contact",                          // identifier for history table
                            "contacts",                         // table
                            $params['entry_id'],  // object_id
                            array ("contact_id" => $params['entry_id']), 
                            $old_contact_values,
                            array ("identity_hash"));           // dont log identity hash
            update_history ("contact",          
                            "metainfo",         
                            $params['entry_id'],
                            array ( "object_type" => 'contact',
                                    "object_id"   => $params['entry_id']), 
                            $old_meta_values);
            
            return "success";
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
        * @since        0.4.0
        * @version      0.4.4
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            //$db_hdl->debug=true;
            if (is_null($query)) {
                $query = "SELECT 
								contact_id,
                                salutation,
                                salutation_letter,
                                title,
                                firstname,
                                lastname,
                                street,
                                zipcode,
                                city,
                                email,
                                company,
                                department,
                                function,
                                phone_private1, phone_private2,
                                phone_company1, phone_company2,
                                fax,
                                country,
                                homepage,
                                birthday, 
                                further_emails,
                                category,
                                remark,
                                mi.grp,
                                mi.owner,
                                mi.created,
                                mi.access_level
                    FROM ".TABLE_PREFIX."contacts 
                    LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."contacts.contact_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups  ag ON ag.id=mi.grp
					LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
					WHERE 
						gd.mandator_id=".$_SESSION['mandator']." AND 
						mi.object_type='contact'";
            }     
                   
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND mi.grp=".$_SESSION['use_my_group'];       
            else {
                $query .= " AND ".get_all_groups_or_statement ($_SESSION['user_id']);    
            }    
            
            if ($_SESSION['use_my_state'] > 0)
                $query .= " AND mi.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND mi.owner=".$_SESSION['use_my_owner'];       

            $query .= " AND (mi.owner=".$_SESSION['user_id']." OR
                                 (mi.access_level LIKE '____r_____') OR
                                 (mi.access_level LIKE '_______r__')
                            ) ";    
//echo $query;
            $this->dg = new datagrid (20, "contacts", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
                     
            // --- serialize query for further use (i.e. export) ----
            $this->serializeQuery ($this->dg);
   
            return "success";
        }
        
       /**
        * Show locked entries.
        *
        * Calls <i>show_entries</i> with another, adopted query. Only the entries which are currently locked
        * are shown.
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success"
        * @since        0.1.0
        * @version      0.1.0
        */
        function show_locked (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            //$db_hdl->debug=true;
            $query = "SELECT    contact_id,
                                salutation,
                                salutation_letter,
                                title,
                                firstname,
                                lastname,
                                street,
                                zipcode,
                                city,
                                email,
                                company,
                                department,
                                function,
                                phone_private1, phone_private2,
                                phone_company1, phone_company2,
                                fax,
                                country,
                                homepage,
                                birthday, 
                                further_emails,
                                category,
                                remark,
                                metainfo.grp,
                                metainfo.owner,
                                metainfo.created,
                                metainfo.access_level
                    FROM ".TABLE_PREFIX."contacts 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=contacts.contact_id
                    LEFT JOIN ".TABLE_PREFIX."useronline ON useronline.object_id=contacts.contact_id
                    WHERE metainfo.object_type='contact' AND useronline.object_type='contact'";

            return $this->show_entries ($params, $query);
        }
            
       /**
        * Shows single contact.
        *
        * Asserts contact_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact. Checks if contact is locked already (warning) or locks contact itself. 
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.1.0
        * @version      0.1.0
        */
        function show_contact (&$params) {
            global $db_hdl, $logger, $PING_TIMER;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- fill country field -------------------------------
            //$query = "SELECT id, country FROM ".TABLE_PREFIX."countries";
            //$res   = mysql_fetch_array ($query);
            //$this->entry['country']->fillFromResultSet ($res);

            // --- validation ---------------------------------------
            assert ('$params["contact_id"] > 0');
            
            // get data for this contact
            $contact_query ="
                SELECT * FROM ".TABLE_PREFIX."contacts
                LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."contacts.contact_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='contact' AND ".TABLE_PREFIX."contacts.contact_id=".$params['contact_id'].")";
            //$res = mysql_query ($contact_query);
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
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
            if ($row['birthday'] == "0000-00-00")
                $this->entry['birthday']->set('');
            else {
                $tmp = explode ("-", $row['birthday']);
                $this->entry['birthday']->set($tmp[2].".".$tmp[1].".".$tmp[0]);
            }
            $this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);
            
            $res = mysql_query ("SELECT email,email FROM ".TABLE_PREFIX."alt_email_addresses WHERE contact_id=".$params['contact_id']);
            $this->entry['further_emails']->fillFromResultSet($res);
            
            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy('contact', $this->entry['contact_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    //$this->info_msg .= " ".time() - $lock_timestamp;
            }
            else {
                // --- lock contact -------------------------------------
                $this->info_msg .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ('contact', $this->entry['contact_id']->get());
            }
            
            return "success";
        }

      /**
        * export to excel
        *
        * accoring to the query string created by show_entries the contacts get exported to excel.
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string "success"
        * @since        0.4.3
        * @version      0.4.4
        */
        function export_excel ($params) {
        	global $logger;
        	
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $old_error_level = error_reporting();
            error_reporting(E_ERROR);
            
            include_once "../../extern/Spreadsheet_Excel_Writer/Writer.php";

            $xls =& new Spreadsheet_Excel_Writer();
            $xls->send("contacts.xls");
            $sheet =& $xls->addWorksheet('Export of contacts '.date("d.m.Y"));

            $headline =& $xls->addFormat();
            $headline->setBold();
            $headline->setColor("white");
            $headline->setBgColor("blue");
            
            // get last query 
            $serial_query    = "SELECT db_query_serialized FROM ".TABLE_PREFIX."user_details WHERE user_id=".$_SESSION['user_id'];
            if (!$serial_res = $this->ExecuteQuery ($serial_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            $serial_row      = mysql_fetch_array($serial_res);
            $excel_res = mysql_query (unserialize($serial_row['db_query_serialized']));

            //$sheet->write(0,0, unserialize($serial_row['db_query_serialized']));
            $columns = mysql_num_fields($excel_res);
            for ($i=0; $i < $columns; $i++) {
                $sheet->write(0,$i, translate (mysql_field_name($excel_res, $i)), $headline);
            }    
             
            $line    = 2;
            while ($row = mysql_fetch_array ($excel_res)) {
                for ($i=0; $i < $columns; $i++) {
                    $sheet->write($line,$i, addslashes($row[$i]));
                }    
                $line++;
            }    
            
            $xls->close();

            error_reporting($old_error_level);
            
            return "success";
        }

        
      /**
        * check (add or delete) additional emails.
        *
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string "success"
        * @since        0.5.1
        * @version      0.5.1
        */
        function checkAdditionalEmails () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            if ($this->entry['further_emails']->get() != '') {
            	$adresses = explode ("|", $this->entry['further_emails']->get());
				// delete old entries first
				$query = "DELETE FROM ".TABLE_PREFIX."alt_email_addresses 
						  WHERE contact_id=".$this->entry['contact_id']->get();
				if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            	foreach ($adresses AS $key => $adress) {
            		if (trim($adress) != '') {
		            	$query = "
							INSERT INTO ".TABLE_PREFIX."alt_email_addresses (contact_id, email)
							VALUES (".$this->entry['contact_id']->get().", '".$adress."')
            		               ";
            		    //$logger->log ($query, 1);
           				if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            		}
            	}
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
        * @since        0.4.0
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
        * identity hash determines how close a contact is to another.
        *
        * two contacts with the same identity hash are considered equal.
        * 
        * @access       private
        * @param        int contact_id
        * @since        0.4.0
        * @version      0.4.3
        */
        function setIdentityHash ($contact_id) {
        	global $logger;
        	
        	// --- find out identity hash ---------------------------
            $hash = $this->getIdentityHash (array (
            									$this->entry['firstname']->get()    => 5,
            									$this->entry['lastname']->get()     => 10,
            									$this->entry['email']->get()        => 10,
            									$this->entry['mobile_phone']->get() => 10,
            									$this->entry['street']->get()       => 5,
            									$this->entry['zipcode']->get()      => 5,
            									$this->entry['city']->get()         => 2
            								));
			mysql_query ("UPDATE ".TABLE_PREFIX."contacts SET identity_hash=$hash WHERE contact_id=".$contact_id);
            $logger->log (mysql_error(),  1);
        }

      /**
        * serializeQuery
        *
        * The query calculated by the datagrid $dg gets serialized for further use (excel export)
        * 
        * @access       private
        * @param        class datagrid
        * @return       string "failure" on failure
        * @since        0.4.0
        * @version      0.4.3
        */
        function serializeQuery (&$dg) {
            
            $serial = serialize ($dg->query);
	       	$serial = addslashes ($serial);
		    $serial_query = "
			     UPDATE ".TABLE_PREFIX."user_details
			     SET db_query_serialized='".$serial."'
			     WHERE user_id='".$_SESSION['user_id']."'
		    ";
            if (!$this->ExecuteQuery ($serial_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
        }
    }   

?>