<?php

   /**
    * Model for handling categories.
    *
    * This file contains the model of the model-view-controller pattern used to 
    * implement the business logic. A category is a kind of flag which can be attached to any entry in leads4web.
    * You can organize the categories by using folders.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling categories.
    * You can organize the categories by using folders.
    *
    * @version      $Id: collections_mdl.php,v 1.20 2005/08/01 14:55:13 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      categories
    */    
    class collections_model extends l4w_model {
        
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'category';     

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
        function collections_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null, 
                array ("add_category",
                       "copy_from_dg",	      // copy columns from given datagrid
                	   "create_category",
                	   "add_ref_view",
                	   "add_folder",
                	   "create_folder",
                       "delete_entry",           // (action)
                       "delete_selected",
                       "del_ref",
                       "edit_category",
                       "edit_folder",
                	   "show_entries",           // list of all entries
                       "update_category", 
                       "update_folder",          
                       "unset_current_view",     // unset current view in SESSION (unlock)
                       "help"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }

      /**
        * standard validation of category
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.5
        * @version      0.4.5
        */
        /*function category_validation () {
            if (!$this->validateModel ()) return "failure";
            return "success";
        }*/
        
      /**
        * add new folder.
        *
        * You need to have the "Add Category" permission to add a folder.
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.1
        * @version      0.5.2
        */
        function create_folder () {
            global $logger, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('CategoryManager','Add Category','Person',$_SESSION['user_id'])) {
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

			// --- update component / categories relation --------------
            if (!$this->updateComponentRelation()) return "failure";

			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'new folder', 'system', $this->entry['collection_id']->get());
                        
            return "success";
        }

        /**
        * adds new category.
        *
        * The given category name must not be empty and the current user must have the
        * appropriate rights. A corresponing event is fired once a category has been added
        * successfully.
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.5
        * @version      0.5.2
        */
        function create_category () {
            global $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('CategoryManager','Add Category','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }
            
            // --- validate -----------------------------------------
            $this->entry['name']->set_empty_allowed (false);
            $validate = $this->validateModel ();
            //echo "(".$validation.")";
            if (!$validate) return "failure"; 

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
            fireEvent ($this, $this->entry_type, 'new category', 'system', $this->entry['collection_id']->get());
            
            return "success";
        }
               
      /**
        * updates collection entry
        *
        * Uses intern contact validation. A category or folder may be edited if the current
        * user has the right "edit category". An event gets fired if a category or folder is updated.
        * If there are any problems, examine
        * model->error_msg and model->info_msg;
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.5
        * @version      0.5.2
        */
        function update_entry () {
            global $db_hdl, $logger, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('CategoryManager','Edit Category','Person',$_SESSION['user_id'])) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }

            // --- validate -----------------------------------------
            $this->entry['name']->set_empty_allowed (false);   	
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
            if (!$this->updateComponentRelation()) return "failure";
            
            // --- update history -----------------------------------
            update_history ("collection",                             // identifier for history table
                            "collections",                         // table
                            $this->entry['collection_id']->get(),  // object_id
                            array ("collection_id" => $this->entry['collection_id']->get()), 
                            $old_entry_values);
                                                                    
			// --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed category', 'system', $this->entry['collection_id']->get());

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
        * @since        0.5.1
        * @version      0.5.2
        */
        function delete_entry ($params) {
            global $db_hdl, $logger, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- permissions --------------------------------------
            if (!$gacl_api->acl_check('CategoryManager','Delete Category','Person',$_SESSION['user_id'])) {
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
    						  to_object_id=".$this->entry['collection_id']->get();
                if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                if (mysql_num_rows($res) > 0) {
                
                	$this->error_msg .= translate ('entries with this category');
    
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
    				
    				$this->info_msg .= '<a href="index.php?command=delete_entry&entry_id='.$this->entry['collection_id']->get().'&override">';
    				$this->info_msg .= translate('delete nevertheless')."</a><br>";
    				
                	return "failure";	
                }	
            }
                        
            // --- delete entry -------------------------------------
            $del_query = "
				DELETE FROM ".TABLE_PREFIX."collections 
				WHERE collection_id='".$this->entry['collection_id']->get()."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'deleted collection', 'system', $this->entry['collection_id']->get());

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
        * @since        0.4.5
        * @version      0.4.5
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
        * @since        0.4.4
        * @version      0.4.4
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
        * @since        0.4.4
        * @version      0.4.4
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
        * @since        0.5.1
        * @version      0.5.2
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
        * @since        0.5.2
        * @version      0.5.2
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

       /**
        * update list of related components
        *
        * 
        * @access       private
        * @param        int id of node where to start
        * @return       array holding parents names and ids
        * @since        0.5.2
        * @version      0.5.2
        */
		function updateComponentRelation () {
			global $logger;
			
			$del_query = "
            	DELETE FROM ".TABLE_PREFIX."category_component
            	WHERE category_id=".$this->entry['collection_id']->get();
            if (!$this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return false;

            $keys = $this->entry['components']->selectedKey;
            foreach ($keys AS $tmp =>$key) {           
    	        $query = "
        	    	INSERT INTO ".TABLE_PREFIX."category_component (component_id, category_id) 
        	    	VALUES (
						$key,					
	        	    	".$this->entry['collection_id']->get()."
        	    	)              
            		";
                $logger->log ($query, 4);
	            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return false;
            }
            
            return true;
            
		}
	}   

?>