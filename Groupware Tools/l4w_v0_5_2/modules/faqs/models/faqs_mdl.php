<?php

   /**
    * Model for handling documents. This file contains the model of the model-view-controller pattern used to 
    * implement the faqs functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      faqs
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how faqs get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * faqs.
    * faqs are a basic type of information implemented in lead4web and provide a <b>headline</b>
    * and a <b>content field</b> only.<br>
    * Nevertheless, they can be <b>viewed, changed, organized</b> in different ways and even <b>synchronized</b>.
    * As a part of leads4web, faqs are treated as <b>shareable pieces of information</b> which belong to extacly <b>one
    * group</b> and have certain <b>access rights</b>. When a note gets <b>attached</b> to other pieces of information (like contacts or documents),
    * these access rights (and the group) are <b>inherited</b> from the parent.<br> 
    * A note can belong to zero or more <b>collections</b> (which is basically a gathering of various pieces of information of any kind)
    * and can <b>reference</b> (or be referenced by) other pieces of information.<br>
    * faqs can be organized in <b>folders</b>, but these folders do not pass their group or access rights to their content.  
    *
    * There are extended models adding due-dates, priorites and so on to the basic faqs model.
    * All this kind of information gets stored in a table called 'memos'.
    *
    * @version      $Id: faqs_mdl.php,v 1.8 2005/07/31 09:01:06 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      faqs
    */    
    class faqs_model extends notes_model {
         
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'faq';     
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @faq         think about getting rid of smarty and authClass as parameters
        * @since        0.4.7
        * @version      0.4.7
        */
        function faqs_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            $this->command  = new easy_string  ("show_entries", null,
                array ("add_entry_view",
                       "add_note_att_view",
                       "add_folder_view",
                       "add_folder",
                	   "add_entry",
                	   "add_note_att",
                	   "add_ref_view",
                	   "browse",
                	   "clear_filter",
                       "delete_entry",           
                       "delete_selected",
                       "del_ref",
                       "edit_att_note",         
                       "edit_entry",
                       "edit_folder",
                       "help",
                       "list",
                       "move_view",
                       "move",
                       "search_view",
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
        * validates new or updated faq.
        *
        * Validates if assigned group exists and runs the validation rules defined in corresponding file fields_definition.inc.php.
        * The parent folder (if existent) may not have the same group or access rights.
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.4.7
        * @version      0.4.7
        */
        function faq_validation ($params) {
            global $logger;
            
            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            
            // --- attachments have group = 0 and don't need validation 
            if ($group == 0) return "success";
            
            // --- does current user really has access to group? (This can
            // --- happen when making a copy of another note!)
            //$all_groups = get_all_groups ($_SESSION['user_id']);
            $all_groups = get_all_groups ($params['owner']); // guest can add entries if owner is set to 2
            if (!in_array($group, $all_groups)) {
                $group = get_main_group($params['owner']);
                $this->entry['use_group']->set ($group);
                $this->info_msg .= translate ('group was changed to')." ".get_group_alias($group);    
            }        
            
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$group;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "guestview";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "guestview";            
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
            if (!$ok) return "guestview";

            return "success";
        }       

       /**
        * add new note.
        *
        * The new entry gets validated via note_validation and added to the memos table on success. In this case an
        * event is fired (new faq). 
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
            $validation = $this->faq_validation($params);
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
                            ".$params['owner'].",
                            ".$params['owner'].",
                            ".$this->entry['use_group']->get().",
							".getStateForNewObject($this->entry_type).",
                            now(),
                            '".$this->entry['access']->get()."'
                           )";
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
            
            if (strtoupper($_SESSION['login']) == "GUEST")
                return "thanks";    
            
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
         
            die ("not implemented");   
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

            die ("not implemented");   
        }
                    
      /**
        * Show all entries.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. This method is based on the parents (notes_model) method show_entries.
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.4.7
        * @version      0.4.7
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
                        memo_id,
						headline,
						content,
						creator,
                        followup,
                        ordernr,
						CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
						".TABLE_PREFIX."gacl_aro_groups.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,  
                        is_dir,
                        ".TABLE_PREFIX."metainfo.owner AS owner_id,
                        ".TABLE_PREFIX."metainfo.grp   AS group_id,
                        done,
                        color,
                        ".TABLE_PREFIX."metainfo.state
		            FROM ".TABLE_PREFIX."memos 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."memos.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=".TABLE_PREFIX."gacl_aro_groups.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."metainfo.owner
                    LEFT JOIN ".TABLE_PREFIX."priorities ON ".TABLE_PREFIX."memos.priority=".TABLE_PREFIX."priorities.prio_id
                    WHERE ".TABLE_PREFIX."metainfo.object_type='faq' AND
                    	  parent=".$this->entry['parent']->get();
            }
            
            if (!isset($params['order'])) $params['order'] = 1;
            
            return parent::show_entries($params, $query);
              
        }
        
      /**
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.0
        * @version      0.5.0
        */
       function search (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            isset ($params['search']) ? $search = $params['search'] : $search = '';

            $query = "
                    SELECT
                        memo_id,
						headline,
						content,
						creator,
                        followup,
                        ordernr,
						last_change,
						access_level,  
                        is_dir,
                        ".TABLE_PREFIX."metainfo.state,
                        ".TABLE_PREFIX."metainfo.owner,
                        ".TABLE_PREFIX."metainfo.grp
		            FROM ".TABLE_PREFIX."memos m
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=m.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=".TABLE_PREFIX."gacl_aro_groups.id
                    WHERE ".TABLE_PREFIX."metainfo.object_type='faq' AND
                    (
                        headline LIKE '%".$search."%'	OR
                        content  LIKE '%".$search."%'
                    )
                    ";

            $query .= "
                        AND (
                        	  ".TABLE_PREFIX."metainfo.owner=".$_SESSION['user_id']." 
                			OR
		                      (".get_all_groups_or_statement ($_SESSION['user_id'])." AND ".TABLE_PREFIX."metainfo.access_level LIKE '____r_____') 
			                OR
                     		   ".TABLE_PREFIX."metainfo.access_level LIKE '_______r__'	
                        )
            ";    
			$query .= "ORDER BY is_dir DESC, ordernr ASC";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $this->entry['res'] = $res;
            
            $this->entry['hits']->set (mysql_affected_rows());
            return "success";  
        }

            
      /**
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.0
        * @version      0.5.0
        */
       function browse (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            isset ($params['parent']) ? $parent = $params['parent'] : $parent = 0;

            $query = "
                    SELECT
                        memo_id,
						headline,
						content,
						creator,
                        followup,
						last_change,
						access_level,  
                        is_dir,
                        ".TABLE_PREFIX."metainfo.state,
                        ".TABLE_PREFIX."metainfo.owner,
                        ".TABLE_PREFIX."metainfo.grp
		            FROM ".TABLE_PREFIX."memos m
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=m.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=".TABLE_PREFIX."gacl_aro_groups.id
                    WHERE ".TABLE_PREFIX."metainfo.object_type='faq' AND
                          m.parent=".$parent."
                    ";

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

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $this->entry['res'] = $res;
            
            $this->entry['hits']->set (mysql_affected_rows());
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
        /*function show_entry (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM memos
                LEFT JOIN metainfo ON metainfo.object_id=memos.memo_id
                WHERE (metainfo.object_type='note' AND memos.memo_id=".$params['entry_id'].")";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
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
            list ($lock_user, $lock_timestamp) = $this->lockedBy('note', $this->entry['memo_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ('note')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ('note')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
            }
            else {
                // --- lock contact -------------------------------------
                $this->info_msg .= translate ('note')." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ('note', $this->entry['memo_id']->get());
            }
           
            return "success";
        }*/
        
                    
 	  /**
        * clears filter
        *
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @faq         move to leads4web_model?
        * @since        0.4.7
        * @version      0.4.7
        */
       function clear_filter () {
            global $db_hdl, $logger;
            
            parent::update_filter (array ("my_group" => '',
            							  "my_state" => '',
            							  "my_owner" => ''));
			//var_dump ($_SESSION['easy_datagrid']);
			$_SESSION['easy_datagrid']['faqs'] = array ();
        }
    }   

?>