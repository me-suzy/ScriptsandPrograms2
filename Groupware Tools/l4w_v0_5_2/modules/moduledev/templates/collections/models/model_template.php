<?php

   /**
    * Model for handling categories.
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */
        
   /**
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */    

    include ('fields_validations.inc.php');
    
   /**
    *
    * @author       ###author###
    * @copyright    ###copyright###
    * @package      ###package###
    */    
    class ###name###_model extends l4w_model {
        
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = '###name###';     

        /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function ###name###_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null, 
                array ("add_###name###",
                       "copy_from_dg",	      // copy columns from given datagrid
                	   "create_###name###",
                	   "add_ref_view",
                	   "add_folder",
                	   "create_folder",
                       "delete_entry",           // (action)
                       "delete_selected",
                       "del_ref",
                       "edit_###name###",
                       "edit_folder",
                	   "show_entries",           // list of all entries
                       "update_###name###", 
                       "update_folder",          
                       "unset_current_view",     // unset current view in SESSION (unlock)
                       "help"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }
        
      /**
        * add new folder.
        *
        * You need to have the "Add ###name###" permission to add a folder.
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function create_folder () {
            global $easy, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('###name###Manager','Add ###name###','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }

            // --- validate -----------------------------------------
            assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->folder_validation();
            if ($validation != "success") return $validation; 
            
            // --- additional validation ----------------------------
            // folders parent must be 0!
            if (!$this->entry['parent']->get() == 0) {
            	$this->error_msg .= translate ('only one level of entries allowed');
            	return "failure";	
            } 	

            
            // --- add entry ----------------------------------------            
            $query = "INSERT INTO ".TABLE_PREFIX."collections (
                                mandator,
                                parent,
                                is_dir,
                                name,
                                description)
                       VALUES (
                                '".$_SESSION['mandator']."',
                                '".$this->entry['parent']->get()."',
                                '1',
                                '".$this->entry['name']->get()."',
                                '".$this->entry['description']->get()."'
                               )";
            $logger->log ($query, 7);
            if (!$this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            $this->entry['collection_id']->set (mysql_insert_id());
                        
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'new folder', 'system', $this->entry['collection_id']->get());
                        
            return "success";
        }

        /**
        * adds new ###name###.
        *
        * The given ###name### name must not be empty and the current user must have the
        * appropriate rights. A corresponing event is fired once a ###name### has been added
        * successfully.
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function create_###name### () {
            global $easy, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('###name###Manager','Add ###name###','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }
            
            // --- validate -----------------------------------------
            $validation = $this->validateModel ();
            if ($validation != "success") return $validation; 
            
            // --- additional validation ----------------------------
            // categories parent must not be 0!
            if ($this->entry['parent']->get() == 0) {
            	$this->error_msg .= translate ('first level entry must be folder');
            	return "failure";	
            } 	            
            
            // --- add entry ----------------------------------------            
            $query = "INSERT INTO ".TABLE_PREFIX."collections (
                                name,
								parent,
								mandator,
                                description
                          )
                          VALUES (
                                '".$this->entry['name']->get()."',
								'".$this->entry['parent']->get()."',
								'".$_SESSION['mandator']."',
                                '".$this->entry['description']->get()."'
                               )";
            $logger->log ($query, 7);
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $this->entry['collection_id']->set (mysql_insert_id());
                        			
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'new ###name###', 'system', $this->entry['collection_id']->get());
            
            return "success";
        }
               
      /**
        * updates collection entry
        *
        * Uses intern contact validation. A ###name### or folder may be edited if the current
        * user has the right "edit ###name###". An event gets fired if a ###name### or folder is updated.
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function update_entry () {
            global $db_hdl, $easy, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('###name###Manager','Edit ###name###','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }

            // --- validate -----------------------------------------
            $validation = $this->validateModel();
            if ($validation != "success") return $validation; 
                                    
            // --- history, get_old values -----------------------------
            $old_entry_values   = get_entries_for_primary_key (
                                       "collections", array ("collection_id" => $this->entry['collection_id']->get()));
                                                                      
            // --- update entry ----------------------------------------
            $contact_query = "UPDATE ".TABLE_PREFIX."collections SET 
                                name          = '".$this->entry['name']->get()."',
                                description   = '".$this->entry['description']->get()."'
                              WHERE collection_id   = '".$this->entry['collection_id']->get()."'";

            if (!$this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- update component / categories relation --------------
            $del_query = "
            	DELETE FROM ".TABLE_PREFIX."###name###_component
            	WHERE ###name###_id=".$this->entry['collection_id']->get();
            if (!$this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            $keys = $this->entry['components']->selectedKey;
            foreach ($keys AS $key => $key) {           
    	        $query = "
        	    	INSERT INTO ".TABLE_PREFIX."###name###_component (component_id, ###name###_id) 
        	    	VALUES (
						$key,					
	        	    	".$this->entry['collection_id']->get()."
        	    	)              
            		";

	            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            }
            
            // --- update history -----------------------------------
            update_history ("collection",                             // identifier for history table
                            "collections",                         // table
                            $this->entry['collection_id']->get(),  // object_id
                            array ("collection_id" => $this->entry['collection_id']->get()), 
                            $old_entry_values);
                                                                    
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed ###name###', 'system', $this->entry['collection_id']->get());

            return "success";
        }

      /**
        * deletes entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function delete_entry (&$params) {
            global $db_hdl, $easy, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('###name###Manager','Delete ###name###','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }
            
            if (!$this->validateModel()) return "failure";
            
            // === find out if entry can be deleted =================
            
            // --- check for subentries -----------------------------
            $query = "
					SELECT * FROM ".TABLE_PREFIX."collections
					WHERE parent=".$this->entry['collection_id']->get();
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            if (mysql_num_rows($res) > 0) {
            	$this->error_msg .= translate ('subentries found');
                return "failure";
            } 	
            
            // --- check for referring entries ----------------------
            if (!isset($params['override'])) {
                $query = "
    					SELECT * FROM ".TABLE_PREFIX."refering
    					WHERE to_object_type='collection' AND 
    						  to_object_id=".$params['entry_id'];
                if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                if (mysql_num_rows($res) > 0) {
                
                	$this->error_msg .= translate ('entries with this ###name###');
    
    				$this->info_msg .= translate ('found entries').":<br>";	
    
    				while ($row = mysql_fetch_array($res)) {
    					switch ($row['from_object_type']) {
    						case 'contact':
    							$this->info_msg .= "<a href='../../modules/contacts/index.php?command=show_contact";
    							$this->info_msg .= "&contact_id=".$row['from_object_id']."'>";
    							$this->info_msg .= translate('contact').", #".$row['from_object_id']."<br>";								
    							break;
    					
    						default:
    							$this->info_msg .= $row['from_object_type'].", #".$row['from_object_id']."<br>";	
    							break;
    					}
    				}	
    				
    				$this->info_msg .= '<a href="index.php?command=delete_entry&entry_id='.$params['entry_id'].'&override">';
    				$this->info_msg .= translate('delete nevertheless')."</a><br>";
    				
                	return "failure";	
                }	
            }
                        
            // --- delete entry -------------------------------------
            $del_query = "
				DELETE FROM ".TABLE_PREFIX."collections 
				WHERE collection_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'deleted collection', 'system', $params['entry_id']);

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
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            //$db_hdl->debug=true;
            if (is_null($query)) {
                $query = "
                    SELECT
                        collection_id,
						".TABLE_PREFIX."collections.name,
						description,
						is_dir						
                    FROM ".TABLE_PREFIX."collections 
					WHERE parent=".$this->entry['parent']->get()." AND
					      mandator=".$_SESSION['mandator']."
					ORDER BY is_dir DESC
                    ";
            }                  

            $this->dg = new datagrid (20, "collections", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        * Shows single collection.
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function show_entry (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."collections
                WHERE collection_id=".$params['entry_id'];
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);

            // --- sufficient rights ? -----------------------------
            /*if (!user_may_read ($row['owner'],$row['grp'],$row['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }*/
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
                        
            // --- adjust some values -------------------------------
            /*$this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);*/
           
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
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function unset_view ($params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            mysql_query ("DELETE FROM useronline 
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
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function get_folder_name ($folder_id = 0) {
            global $db_hdl, $logger;

            if ($folder_id == 0) return '';
            
            $sql = "SELECT name
                    FROM ".TABLE_PREFIX."collections
                    WHERE collection_id='$folder_id' AND mandator=".$_SESSION['mandator'];
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            return $row['name'];
        }

       /**
        * standard validation of new or updated folder.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @param        boolean if strict, folder must have same group as parent folder
        * @return       string success on success, otherwise failure
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function folder_validation () {
            if (!$this->validateModel ()) return "failure";
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
        * @since        ###version_main###.###version_sub###.###version_detail###
        * @version      ###version_main###.###version_sub###.###version_detail###
        */
        function getParentChain ($start_id, $chain = null) {
            global $db_hdl, $logger;

            $sql = "SELECT collection_id, name, parent 
                    FROM ".TABLE_PREFIX."collections
                    WHERE collection_id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['name'];
                $chain[$cnt]['id']     = $row['collection_id'];                
                $chain += $this->getParentChain ($row['parent'], $chain);
            }	
            return $chain;
        }


    }   

?>