<?php

   /**
    * $Id: docs_mdl.php,v 1.13 2005/08/04 19:56:32 carsten Exp $
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package      docs
    */
    
   /**
    * include fields validation rules
    */  
    include ('fields_validations.inc.php');
    
   /**
    * Documents Model
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
    */    
    class docs_model extends l4w_model {
         
        /**
          * int holding the id of an added document entry
          *
          * @access public
          * @var string
          */  
        var $inserted_doc_id = null;     // ID for user when adding was successfull
        
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'doc';     

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
        function docs_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("add_folder_view",        // assign entries (view)
                	   "add_folder",
                	   "add_doc",
                	   "create_doc",
                       "delete_entry",           // (action)
                       "edit_entry",
                	   "show_entries",           // list of all entries
                       "show_locked",            // list of all entries
                       "add_contact",            // add new contact (action)
                       "add_contact_view",       // add new contact (view)
                       "export_excel",           // export
                       "show_doc",               // 
                       "update_contact",         // (action)
                       "unset_current_view",     // unset current view in SESSION (unlock)
                       "help"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }

      /**
        * validates new or updated folder.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.4
        * @version      0.4.4
        */
        /*function folder_validation () {
            global $db_hdl, $logger;
            
            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            $query = "SELECT COUNT(*) FROM gacl_aro_groups WHERE id=".$group;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}
			// if parent exists, folder must have the same group
			if ($this->entry['parent']->get() > 0) {
                $metainfo = $this->get_metainfo('document', $this->entry['parent']->get());
                assert ($metainfo["grp"] == $this->entry['use_group']->get());    
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
        }*/
        
      /**
        * add new document.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2005
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.4
        * @version      0.5.2
        */
        function create_doc () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validate -----------------------------------------
            assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->validateModel();
            if (!$validation) { 
            	$this->error_msg .= translate ('validation of model failed');
            	return "failure"; 
            }
            
            // --- init ------------------------------------------------

            // --- Handle file --------------------------------------
            $uploaddir = UPLOAD_PATH; 
            
            $uploadfile = $uploaddir. $_FILES['fullpath']['name']; 
            if (!move_uploaded_file($_FILES['fullpath']['tmp_name'], $uploadfile)) { 
                $this->error_msg = translate ('uploading file failed');
                return "failure";
            } 

            // --- add entry ----------------------------------------            
            $doc_query = "INSERT INTO ".TABLE_PREFIX."docs (
                                is_dir,
                                parent,
                                name,
                                fullpath,
                                description)
                               VALUES (
                                '0',
                                '".$this->entry['parent']->get()."',
                                '".$this->entry['name']->get()."',
                                '".$uploadfile."',
                                '".$this->entry['description']->get()."'
                               )";
            $logger->log ($doc_query, 7);
            
            if (!$res = $this->ExecuteQuery ($doc_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['doc_id']->set ($inserted_id);
            
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
                            'doc',
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
                mysql_query ("DELETE FROM ".TABLE_PREFIX."docs WHERE doc_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_doc_id = $inserted_id;
            
			// --- fire event ---------------------------------------
            fireEvent ($this, 'doc', 'new document','system',$this->inserted_doc_id);
            
            return "success";
        }
               
      /**
        * add new folder.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.4
        * @version      0.4.7
        */
        function add_folder (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->folder_validation('doc');
            if ($validation != "success") return $validation; 
            
            // --- init ------------------------------------------------

            // --- add entry ----------------------------------------            
            $doc_query = "INSERT INTO ".TABLE_PREFIX."docs (
                                is_dir,
                                parent,
                                name,
                                fullpath,
                                description)
                               VALUES (
                                '1',
                                '".$this->entry['parent']->get()."',
                                '".$this->entry['name']->get()."',
                                '',
                                '".$this->entry['description']->get()."'
                               )";
            $logger->log ($doc_query, 7);
            if (!$res = $this->ExecuteQuery ($doc_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id();
            $this->entry['doc_id']->set ($inserted_id);
            
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
                            'doc',
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
                mysql_query ("DELETE FROM ".TABLE_PREFIX."docs WHERE doc_id='$inserted_id'");
                $this->error_msg = "Error inserting meta entry to database: ".mysql_error();
                return "failure";
            }    
			$this->inserted_doc_id = $inserted_id;
            
			// --- fire event ---------------------------------------
            fireEvent ($this, 'doc', 'new folder','system',$this->inserted_doc_id);
            
            return "success";
        }

      /**
        * deletes contact entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.4
        * @version      0.4.4
        */
        function delete_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_doc_values  = get_entries_for_primary_key (
                                       "docs", array ("doc_id" => $params['entry_id']));
            
            $old_meta_values = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'doc',
                                                          "object_id"   => $params['entry_id']));
            
            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            $is_dir = (bool)$old_doc_values['is_dir'];
            
            // --- validation ---------------------------------------
            if ($is_dir) {
                // there must not be any subfolders or sub-documents    
                $del_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."docs WHERE parent=".$params['entry_id'];
                if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                $cnt_row  = mysql_fetch_array($res);
                if ($cnt_row[0] > 0) {
                    $this->error_msg .= translate ('folder must be empty');
                    return "failure";    
                }    
            }    

            // --- fire event ---------------------------------------
            if ($is_dir)
                fireEvent ($this, 'doc', 'deleted folder', 'system', $params['entry_id']);
            else
                fireEvent ($this, 'doc', 'deleted document', 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."docs WHERE doc_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='doc' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- delete file --------------------------------------
            if (!$is_dir) {
                unlink ($old_doc_values['fullpath']);    
            }    
            
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";
            
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
        * @since        0.4.4
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
                                doc_id,
                                is_dir,
                                parent,
                                ".TABLE_PREFIX."docs.name,
                                fullpath,
                                category,
                                ".TABLE_PREFIX."docs.description,
                                ag.name AS group_name,
                                CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
                                mi.created,
                                mi.access_level,
                                mi.owner AS owner_id,
                                mi.grp   AS group_id, 
                                '#000000' AS color
                    FROM ".TABLE_PREFIX."docs 
                    LEFT JOIN ".TABLE_PREFIX."metainfo mi ON mi.object_id=".TABLE_PREFIX."docs.doc_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ag ON ag.id=mi.grp
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=mi.owner
					LEFT JOIN ".TABLE_PREFIX."group_details gd ON gd.id=ag.value
					WHERE 
						mi.object_type='doc' AND 
						gd.mandator_id=".$_SESSION['mandator']." AND 
                        parent=".$this->entry['parent']->get();
            }                
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.grp=".$_SESSION['use_my_group'];       
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
//die ($query);
            $this->dg = new datagrid (20, "docs", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            //$this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
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
                                ".TABLE_PREFIX."metainfo.grp,
                                ".TABLE_PREFIX."metainfo.owner,
                                ".TABLE_PREFIX."metainfo.created,
                                ".TABLE_PREFIX."metainfo.access_level
                    FROM ".TABLE_PREFIX."contacts 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."contacts.contact_id
                    LEFT JOIN ".TABLE_PREFIX."useronline ON ".TABLE_PREFIX."useronline.object_id=".TABLE_PREFIX."contacts.contact_id
                    WHERE ".TABLE_PREFIX."metainfo.object_type='contact' AND ".TABLE_PREFIX."useronline.object_type='contact'";

            return $this->show_entries ($params, $query);
        }
            
       /**
        * Shows document.
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.4.7
        * @version      0.4.7
        */
        function show_doc (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["doc_id"] > 0');
            
            $doc_values  = get_entries_for_primary_key (
                                       "docs", array ("doc_id" => $this->entry['doc_id']->get()));
            $meta_values = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => 'doc',
                                                          "object_id"   => $this->entry['doc_id']->get()));
            
            // --- sufficient rights ? ------------------------------
            if (!user_may_read ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }

            // --- set values ---------------------------------------
            foreach ($doc_values AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }
            
            return "success";
        }

        /**
        * updates filter.
        *
        * Updates filter settings for show_entries
        * 
        * @access       private
        * @param        paramas array holding request variables
        * @since        0.4.4
        * @version      0.4.4
        */
        function update_filter ($params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            if (isset ($params['my_group'])) {
                $_SESSION ["use_my_group"] = $params['my_group'];
            }    
            if (isset ($params['my_state'])) {
                $_SESSION ["use_my_state"] = $params['my_state'];
            }    
            if (isset ($params['my_owner'])) {
                $_SESSION ["use_my_owner"] = $params['my_owner'];
            }    
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

            $sql = "SELECT doc_id, name, parent 
                    FROM ".TABLE_PREFIX."docs
                    WHERE doc_id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['name'];
                $chain[$cnt]['id']     = $row['doc_id'];                
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
        * @since        0.4.4
        * @version      0.4.4
        */
        function get_folder_name ($folder_id = 0) {
            global $db_hdl, $logger;

            if ($folder_id == 0) return '';
            
            $sql = "SELECT name, is_dir
                    FROM ".TABLE_PREFIX."docs
                    WHERE doc_id='$folder_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            assert ('(bool)$row["is_dir"] == true');
            return $row['name'];
        }
        
    }   

?>