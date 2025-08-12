<?php

   /**
    * Model for handling documents. This file contains the model of the model-view-controller pattern used to 
    * implement the todos functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      todos
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how todos get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * todos.
    * todos are a basic type of information implemented in lead4web and provide a <b>headline</b>
    * and a <b>content field</b> only.<br>
    * Nevertheless, they can be <b>viewed, changed, organized</b> in different ways and even <b>synchronized</b>.
    * As a part of leads4web, todos are treated as <b>shareable pieces of information</b> which belong to extacly <b>one
    * group</b> and have certain <b>access rights</b>. When a note gets <b>attached</b> to other pieces of information (like contacts or documents),
    * these access rights (and the group) are <b>inherited</b> from the parent.<br> 
    * A note can belong to zero or more <b>collections</b> (which is basically a gathering of various pieces of information of any kind)
    * and can <b>reference</b> (or be referenced by) other pieces of information.<br>
    * todos can be organized in <b>folders</b>, but these folders do not pass their group or access rights to their content.  
    *
    * There are extended models adding due-dates, priorites and so on to the basic todos model.
    * All this kind of information gets stored in a table called 'memos'.
    *
    * @version      $Id: todos_mdl.php,v 1.11 2005/07/31 09:10:02 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      todos
    */    
    class todos_model extends notes_model {
         
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'todo';     
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @todo         think about getting rid of smarty and authClass as parameters
        * @since        0.4.7
        * @version      0.4.7
        */
        function todos_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            $this->command  = new easy_string  ("show_entries", null,
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
                       "unset_current_view"   // unset current view in SESSION (unlock)
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        * validates new or updated todo.
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
        function todo_validation () {
            global $logger;
            
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
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

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
		            FROM ".TABLE_PREFIX."memos 
                    LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."memos.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ag ON mi.grp=ag.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=mi.owner
                    LEFT JOIN ".TABLE_PREFIX."priorities ON ".TABLE_PREFIX."memos.priority=".TABLE_PREFIX."priorities.prio_id
					LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
		            WHERE mi.object_type='todo' AND
						  gd.mandator_id=".$_SESSION['mandator']." AND 
                    	  parent=".$this->entry['parent']->get();
            }
            
            return parent::show_entries($params, $query);
              
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
        * @see          show_entries
        * @since        0.4.5
        * @version      0.4.5
        */
        /*function show_locked (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "
                   SELECT
                        memo_id,
						headline,
						content,
						creator,
						followup,
						CONCAT(users.firstname,' ',users.lastname) AS owner,
						gacl_aro_groups.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,
						is_dir,
                        metainfo.owner AS owner_id,
                        metainfo.grp   AS group_id,
                        done,
                        color
                    FROM memos 
                    LEFT JOIN metainfo ON metainfo.object_id=memos.memo_id
                    LEFT JOIN gacl_aro_groups ON metainfo.grp=gacl_aro_groups.id
                    LEFT JOIN users ON users.id=metainfo.owner
                    LEFT JOIN useronline ON useronline.object_id=memos.memo_id
                    LEFT JOIN priorities ON memos.priority=priorities.prio_id
                    WHERE metainfo.object_type='todo' AND
                          useronline.object_type='todo' AND
                    	  parent=".$this->entry['parent']->get();

            return $this->show_entries ($params, $query);
        }*/
            
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
			$_SESSION['easy_datagrid']['todos'] = array ();
        }
    }   

?>