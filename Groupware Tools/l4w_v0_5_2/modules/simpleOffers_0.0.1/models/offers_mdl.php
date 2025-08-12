<?php

   /**
    * Model for handling documents. This file contains the model of the model-view-controller pattern used to 
    * implement the offers functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      offers
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how offers get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * offers.
    *
    * @version      $Id: offers_mdl.php,v 1.4 2005/08/01 14:55:13 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      offers
    */    
    class offers_model extends l4w_model {
         
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
        var $entry_type      = 'offer';     

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
        function offers_model ($smarty, $AuthoriseClass) {

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
                       "help",
                       "move_view",
                       "move",
                	   "show_entries",           
                       "show_locked",   
                       "update_att_note",         
                       "update_entry",        
                       "upload_pics",   
                       "unset_current_view"   // unset current view in SESSION (unlock)
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        * validates new or updated offer.
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
        function offer_validation () {
            //global $logger;

            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            
            // --- attachments have group = 0 and don't need validation 
            if ($group == 0) return "success";
            
            // --- does current user really has access to group? (This can
            // --- happen when making a copy of another note!)
            $all_groups = get_all_groups ($_SESSION['user_id']);
            if (!in_array($group, $all_groups)) {
                $group = get_main_group($_SESSION['user_id']);
                $this->entry['use_group']->set ($group);
                $this->info_msg .= translate ('group was changed to')." ".get_group_alias($group);    
            }        
            
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$group;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}

            // --- validate all fields in entries -----------------
            $ok = true;
            reset($this->entry); 
			while (list($key, $val) = each($this->entry)) { 
            	$result = $this->entry[$key]->get(); // important for validation!
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
        * add new offer.
        *
        * The new entry gets validated via offer_validation and added to the memos table on success. 
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.5.0
        * @version      0.5.0
        */
        function add_entry ($params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->offer_validation();
            if ($validation != "success") return $validation;
            
            // --- add entry ----------------------------------------            
            $add_query = "INSERT INTO ".TABLE_PREFIX."offers (
                                headline,
                                content,
                                description,
                                details,
                                is_dir,
                                starts,
                                due,
                                done,
                                followup,
                                parent		
                          )
                          VALUES (
                                '".$this->entry['headline']->getHTMLEscaped()."',
                                '".$this->entry['content']->getHTMLEscaped()."',
                                '".$this->entry['description']->getHTMLEscaped()."',
                                '".$this->entry['details']->getHTMLEscaped()."',
                                '0',
                                '".$this->entry['starts']->get("Y-m-d")."',
                                '".$this->entry['due']->get("Y-m-d")."',
                                '".$this->entry['done']->get()."',		
                                '".$this->entry['followup']->get("Y-m-d")."',
                                '".$this->entry['parent']->get()."'		
                               )";
            $logger->log ($add_query, 7);
            if (!$res = $this->ExecuteQuery ($add_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['offer_id']->set ($inserted_id);
            
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
            $res = mysql_query ($meta_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                mysql_query ("DELETE FROM ".TABLE_PREFIX."offers WHERE offer_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_entry_id = $inserted_id;
            
			// --- set default --------------------------------------
			set_defaults ($params);
						
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'new '.$this->entry_type, 'system',$this->inserted_entry_id);
            
            // --- are there pics to upload? ------------------------
            $pics = false;
            if (substr_count ($this->entry['content']->getHTMLEscaped(), "file:///") > 0 ||
                substr_count ($this->entry['details']->getHTMLEscaped(), "file:///") > 0) {
                return "add_pics";       
            }
                        
            return "success";
        }
               
      /**
        * updates offers entry
        *
        *
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         check owner management
        * @todo         divide monster method
        * @since        0.5.0
        * @version      0.5.0
        */
        function    update_entry (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->offer_validation();
            if ($validation != "success") return $validation; 
                                    
            // --- history, get_old values -----------------------------
            /*$old_entry_values   = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $this->entry['offer_id']->get()));*/
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $this->entry['offer_id']->get()));
            
            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                                                          
            // --- update entry ----------------------------------------
            $contact_query = "UPDATE ".TABLE_PREFIX."offers SET 
                                headline          = '".$this->entry['headline']->getHTMLEscaped()."',
                                content           = '".$this->entry['content']->getHTMLEscaped()."',
                                description       = '".$this->entry['description']->getHTMLEscaped()."',
                                details           = '".$this->entry['details']->getHTMLEscaped()."',
                                due               = '".$this->entry['due']->get("Y-m-d")."',
                                starts            = '".$this->entry['starts']->get("Y-m-d")."',
                                followup          = '".$this->entry['followup']->get("Y-m-d")."',
                                done              = '".$this->entry['done']->get()."'
                              WHERE offer_id      = '".$this->entry['offer_id']->get()."'";

            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- add metainfo -------------------------------------
            $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                            grp          = ".$this->entry['use_group']->get().",
                            access_level = '".$this->entry['access']->get()."',
                            state        = ".$this->entry['state']->get().",
                            last_changer = ".$_SESSION['user_id'].",
                            last_change  = now()
                           WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['offer_id']->get()." 
                           ";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                
            // --- Does the owner still have access to the (new) group?
            if ($this->entry['use_group']->get() != 0) {
                $all_groups = get_all_groups ($this->entry['owner']->get());
                if (in_array ($this->entry['use_group']->get(), $all_groups)) {
                    // if so, the current user gets owner of the contact                
                    $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                    owner = ".$this->entry['owner']->get()."
                                    WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['offer_id']->get()." 
                                   ";
                    if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                }   
                else {
                    $meta_query = "UPDATE ".TABLE_PREFIX."metainfo SET
                                    owner = ".$_SESSION['user_id']."
                                    WHERE object_type='".$this->entry_type."' AND object_id=".$this->entry['offer_id']->get()." 
                                   ";
                    if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                    $this->entry['owner']->set ($_SESSION['user_id']);
                    $this->info_msg = translate ('old user not member of new group');
    
                    return "failure";            	
                }	 
            }
            
            // --- unlock entry -------------------------------------
            $this->unlockEntry($this->entry_type, $this->entry['offer_id']->get());

            // --- set default --------------------------------------
			set_defaults ($params);
                            
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $this->entry['offer_id']->get());

            if (isset ($params['apply']) && $params['apply'] != '')
                return "apply";
                
            return "success";
        }

      /**
        * upload pics.
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.5.0
        * @version      0.5.0
        */
        function upload_pics ($params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //$validation = $this->offer_validation();
            //if ($validation != "success") return $validation;
            
            $uploaddir = 'pics/'; 
            $files = $_FILES['userfile'];
            
            foreach ($files['name'] as $key=>$name) {
                
                $uploadfile = $uploaddir.$name; 

                print "<pre>"; 
                if (move_uploaded_file($_FILES['userfile']['tmp_name'][$key], $uploadfile)) { 
                   print "File is valid, and was successfully uploaded. "; 
                   print "Here's some more debugging info:\n"; 
                   print_r($_FILES); 
                } else { 
                   print "Possible file upload attack!  Here's some debugging info:\n"; 
                   print_r($_FILES); 
                } 
                print "</pre>"; 

            }
            
            //var_dump ($this->entry['content']->get());
                        
            return "success";
        }

      /**
        * deletes entry
        *
        * 
        * @access       public
        * @param        array   holds request variables
        * @param        boolean if set to true, the parents meta values (group and access rights) are
                                used to determine if the user has enough privileges to delete the entry. In
                                this case, the parameter "parent" must be set.
        * @param        boolean when use_inheritance is set to true, you have to pass the parents type
        * @return       string  success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.0
        * @todo         may not delete folders with children elements
        */
        function delete_entry ($params, $use_inheritance = false, $parent_type = NULL) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_meta_values = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $params['entry_id']));
                
            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            // --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'deleted '.$this->entry_type, 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."offers WHERE offer_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='".$this->entry_type."' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
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
        * deletes a list of entries
        *
        * The list of entries to be deleted gets passed in the form note_XXX.
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @todo         add public function which takes "real" array with ids
        * @since        0.5.0
        * @version      0.5.0
        */
        function delete_entries (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $use_length = strlen ($this->entry_type)+1; 
            $del_list   = array ();
            foreach ($params AS $key => $value) {
                if (substr ($key, 0,$use_length) == $this->entry_type."_") {
                    $del_list[] = (int)substr ($key,$use_length);    
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
                $chain[$cnt]['id']     = $row['offer_id'];                
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
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            //$db_hdl->debug=true;
            if (is_null($query)) {
                $query = "
                    SELECT
                        offer_id,
						headline,
						content,
						creator,
						CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
						".TABLE_PREFIX."gacl_aro_groups.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,  
                        is_dir,
                        ".TABLE_PREFIX."metainfo.owner AS owner_id,
                        ".TABLE_PREFIX."metainfo.grp   AS group_id,
                        '#000000'      AS color
		            FROM ".TABLE_PREFIX."offers 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."offers.offer_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=".TABLE_PREFIX."gacl_aro_groups.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."metainfo.owner
                    WHERE ".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."' AND
                    	  parent=".$this->entry['parent']->get();
            }  
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.grp=".$_SESSION['use_my_group'];       
            else {
                //$query .= " AND ".get_all_groups_or_statement ($_SESSION['user_id']);    
            }    
            
            if ($_SESSION['use_my_state'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.owner=".$_SESSION['use_my_owner'];       

            
            $query .= "
                        AND (
                        	  ".TABLE_PREFIX."metainfo.owner=".$_SESSION['user_id']." 
                			OR
		                      (".get_all_groups_or_statement ($_SESSION['user_id'])." AND ".TABLE_PREFIX."metainfo.access_level LIKE '____r_____') 
			                OR
                     		   ".TABLE_PREFIX."metainfo.access_level LIKE '_______r__'	
                        )
            ";    
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
                     
            // --- serialize query for further use (i.e. export) ----
            //$this->serializeQuery ($this->dg);
   
            return "success";
        }
                    
       /**
        * Shows single offer.
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.0
        * @version      0.5.0
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
                SELECT * FROM ".TABLE_PREFIX."offers
                LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."offers.offer_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."' AND ".TABLE_PREFIX."offers.offer_id=".$params['entry_id'].")";

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
            
            // --- group = 0 means: entry is attachement (inheritance!)
            if ($row['grp'] == 0) {
                $parents_values = get_entries_for_primary_key(
                                           "memos", array ("memo_id" => $row['parent']));

                $this->entry['parents_headline']->set($parents_values['headline']);    
            }    
            
                        
            // --- adjust some values -------------------------------
            $this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);

            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy($this->entry_type, $this->entry['offer_id']->get());
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
                $this->lockEntry ($this->entry_type, $this->entry['offer_id']->get());
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
                            $this->entry['offer_id']->get(),  // object_id
                            array ("memo_id" => $this->entry['offer_id']->get()), 
                            $old_entry_values);
        
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
        
    }   

?>