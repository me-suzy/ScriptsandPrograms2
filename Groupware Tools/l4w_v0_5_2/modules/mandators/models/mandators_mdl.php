<?php

   /**
    * Model for handling mandators. This file contains the model of the model-view-controller pattern used to 
    * implement the mandators functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      mandators
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * the mandators in leads4web.
    *
    * @version      $Id: mandators_mdl.php,v 1.17 2005/08/04 15:48:30 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      mandators
    */    
    class mandators_model extends l4w_model {
         
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
        var $entry_type      = 'mandators';     

       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @todo         think about getting rid of smarty and authClass as parameters
        * @since        0.5.1
        * @version      0.5.1
        */
        function mandators_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("show_entries",           
                       "add_mandator",        // view to add new mandator
                       "edit_mandator",
                       "edit_users",
                       "update_mandator",
                       "updateMandatorUsers",
                       "delete_mandator",
                       //"edit_datagrid",
                       //"edit_datagrid_column",
                       "create_mandator",     // method to add new mandator
                       "switch_to_mandator",
                       "copy_from_dg",	      // copy columns from given datagrid
                       "unset_current_view"   // unset current view in SESSION (unlock)
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
        * @see          folder_validation
        * @since        0.5.1
        * @version      0.5.1
        */
        function entry_validation () {
            
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
        * create new mandator.
        *
        * The new entry gets validated via entry_validation. 
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         
        * @since        0.5.1
        * @version      0.5.1
        */
        function create_entry ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Mandatormanager', 'Add Mandator', 'Person', $_SESSION['user_id'])) {
                $logger->log ('Security validation in '.__CLASS__.'::'.__FUNCTION__, 1);
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            //$db_hdl->debug = true;

            // --- validate -----------------------------------------
            $validation = $this->entry_validation();
            if ($validation != "success") 
                return $validation;

            // --- start ADODB transaction --------------------------
            $db_hdl->StartTrans();    
            
            // --- add basic navigation tree to tree table ----------
            $tree_root_query = "SELECT 100+max(id) FROM ".TABLE_PREFIX."tree";
            $res = $this->ADODBExecuteQuery($tree_root_query);
            $tree_root = $res->fields[0];
            
            // Add root (tree)
            $tree_query = "
                INSERT INTO ".TABLE_PREFIX."tree (id, parent, name, link, frame, img, sign)
                VALUES (".($tree_root+1).", $tree_root, '".$this->entry['name']->get()."', '','','','')
                ";
            $this->ADODBExecuteQuery($tree_query);

            // Add administration subtree
            $tree_query = "
                INSERT INTO ".TABLE_PREFIX."tree (id, parent, name, link, frame, img, sign, subtree_identifier)
                VALUES (".($tree_root+2).", ".($tree_root+1).", 'administration', '','','admin.gif','', '~~rights~~')
                ";
            $this->ADODBExecuteQuery($tree_query);
            
            // === and now: the root for the groups... =================
            // each group belongs to a mandator, that's why the mandator_id is listed
            // in groups_details, not in table mandator
            require_once ("../groups/models/groups_mdl.php");
    		//require_once ("../emails/controlers/mail_ctrl.php");
		    $null_val    = null;
		    /*$group_params = array ('command'     => 'add_group',
		                           'parent'      => 10,
		                           'name'        => $this->entry['name']->get()."RootGroup", 
		                           'description' => 'generated at '.date("d.m.Y H:i:s"));*/
                        		                           
    	    $groups_model = new groups_model ($null_val, $null_val);
    	    
            $groups_model->command->set    ('add_group');
            $groups_model->entry['parent']->set     (10);
            $groups_model->entry['name']->set       ($this->entry['name']->get()."RootGroup");
            $groups_model->entry['description']->set('generated at '.date("d.m.Y H:i:s"));

    	    $add_group_rc = $groups_model->add_group();
            if ($add_group_rc != "success") {
                $this->error_msg .= "failure adding root group";
                $db_hdl->FailTrans(); // Rollback!
                return "failure";    
            }    
            
            // --- add entry ----------------------------------------            
            $add_query = "INSERT INTO ".TABLE_PREFIX."mandator (
                                name,
                                tree_root,
                                description,
                                acl_inc_php	
                          )
                          VALUES (
                                '".$this->entry['name']->get()."',
                                ".$tree_root.",
                                '".$this->entry['description']->get()."',
                                '".$this->entry['acl_inc_php']->get()."'
                               )";            
            $this->ADODBExecuteQuery ($add_query);
            
            $inserted_id = $db_hdl->Insert_ID();
            $this->entry['mandator_id']->set ($inserted_id);
            
            // --- add at least the current user to this mandant ----
            $add_user_query = "
                INSERT INTO ".TABLE_PREFIX."user_mandator (user_id, mandator_id)
                VALUES (
                    ".$_SESSION['user_id'].", $inserted_id
                    )
                ";
            $this->ADODBExecuteQuery ($add_user_query);
                       
            // --- add current user to the root group of new mandator ---
            if (!$gacl_api->add_group_object (
                                    $groups_model->inserted_group_id, 
                                    'Person', 
                                    $_SESSION['user_id'], 
                                    'ARO')) {
            	$this->info_msg = "Adding ".$membership_row['grp']." failed<br>";
			}	
             
            // --- copy datagrids from mandator 1
            // don't copy datagrids here. Datagrids are copied when actually entering 
            // the model (function copyFromDG)
            /*$add_dg_query = "
		            INSERT INTO ".TABLE_PREFIX."datagrids (name, description, mandator_id, searchButtonCol) 
					SELECT name, description, $inserted_id as mandator_id, searchButtonCol from ".TABLE_PREFIX."datagrids
					WHERE mandator_id=1
				";            
			$res = $this->ADODBExecuteQuery ($add_dg_query);*/
			
            // --- copy access_levels from mandator 1
            $add_dg_query = "
		            INSERT INTO ".TABLE_PREFIX."access_options (mandator, identifier, name, icon) 
					SELECT $inserted_id as mandator, identifier, name, icon from ".TABLE_PREFIX."access_options
					WHERE mandator=1
				";            
			$res = $this->ADODBExecuteQuery ($add_dg_query);

            // --- copy priorities from mandator 1
            $add_dg_query = "
		            INSERT INTO ".TABLE_PREFIX."priorities (name, description, translate, order_nr, color, mandator) 
					SELECT name, description, translate, order_nr, color, $inserted_id as mandator
					FROM ".TABLE_PREFIX."priorities
					WHERE mandator=1
				";            
			$res = $this->ADODBExecuteQuery ($add_dg_query);

            if (!$this->ADODBCompleteTrans()) {
                // remove generated group!
                $del_group_params = array ("group_id" => $groups_model->inserted_group_id);
                $groups_model->delete_group($del_group_params);    
                $logger->log ("deleting group ".$groups_model->inserted_group_id,1);
                return "failure";    
            }
            
            // get value of new group: // have to do this outside of transaction
            $val_query   = "SELECT value FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$groups_model->inserted_group_id;
            $val_res     = $this->ADODBExecuteQuery($val_query);
            $groups_root = $val_res->fields[0];

            // update mandator in group details
            $upd_query   = "
                UPDATE ".TABLE_PREFIX."group_details 
                SET mandator_id=$inserted_id
                WHERE id=$groups_root"; 
            $this->ADODBExecuteQuery ($upd_query);

            return "success";
        }
               
      /**
        * updates mandator
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         check owner management
        * @todo         divide monster method
        * @since        0.4.5
        * @version      0.4.7
        */
        function updateMandator (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //$validation = $this->note_validation();
            //if ($validation != "success") return $validation; 
                                    
            // --- sufficient rights ? ------------------------------
            /*if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }*/
                                                          
            // --- update entry ----------------------------------------
            $query = "UPDATE ".TABLE_PREFIX."mandator SET 
                                name        = '".$this->entry['name']->getHTMLEscaped()."',
                                acl_inc_php = '".$this->entry['acl_inc_php']->get()."'
                              WHERE mandator_id = '".$this->entry['mandator_id']->get()."'";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                                        
            return "success";
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
        * @since        0.5.1
        * @version      0.5.1
        */
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if (is_null($query)) { // set default query
                $query = "
                    SELECT
                        mandator_id,
                        name,
                        tree_root,
                        description,
                        acl_inc_php
                    FROM ".TABLE_PREFIX."mandator"; 
            }  
            
			//$query .= "ORDER BY is_dir DESC";

			$this->dg = new datagrid (20, $this->entry_type."s", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement ('edit_mandator', 'entry_id', this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=2;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                       
            return "success";
        }

       /**
        * get data for entry.
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.1
        * @version      0.5.1
        */
        function getMandator (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."mandator
                WHERE mandator_id=".$params['entry_id'];

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
            //$this->entry['use_group']->set ($row['grp']);
            
            return "success";

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
        * @since        0.5.1
        * @version      0.5.1
        */
        function edit_datagrid (&$params, $query = null) {
            global $gacl_api;
            

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Mandatormanager', 'Edit Datagrid', 'Person', $_SESSION['user_id'])) {
                $logger->log ('Security validation in '.__CLASS__.'::'.__FUNCTION__, 1);
                die ("security check failed in ".__FILE__);    
            } 
            
            return $this->show_entries($params);
        }

       /**
        * get Users.
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
        function getUsers (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            require_once('../../modules/users/models/users_mdl.php');
            
            $dummy = null;
            
            $user_model = new users_model($dummy, $dummy);
            
            $users_params = array ();
            $result = $user_model->show_users($users_params);
            $this->dg =& $user_model->dg;

            return $result;
        }
        
       /**
        * update Users belonging to current mandator.
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
        function updateMandatorUsers (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            //var_dump($params);

            require_once('../../modules/users/models/users_mdl.php');
            
            $dummy = null;
            $user_model = new users_model($dummy, $dummy);
            $users_params = array ("mandator_id" => $params['mandator_id']);
            
            assert ('isset ($params["primaryKeys"])');
            
            $keys = explode ("|", $params['primaryKeys']);
            foreach ($keys AS $tmp =>$key) {
                if ($key > 0) {
                    $user_model->entry['use_user']->set ($key);
                    $users_params['setMandator'] = false;
                    $dummy  = 'member_'.$key;
                    //echo $dummy."<br>";
                    if (isset ($params[$dummy]) && $params[$dummy] == "on")
                        $users_params['setMandator'] = true;
                    $result = $user_model->setMandatorForUser($users_params);
                }        
            }    
            
            return "success";
        }

      /**
        * switches mandator for current user
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         validation and security
        * @since        0.5.1
        * @version      0.5.1
        */
        function switchMandator (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //$validation = $this->note_validation();
            //if ($validation != "success") return $validation; 
                                    
            // --- sufficient rights ? ------------------------------
			if (!$GLOBALS['gacl_api']->acl_check('Mandatormanager', 'Switch Mandator', 'Person', $_SESSION['user_id'])) {
			    $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }

                                                          
            $_SESSION['mandator'] = $params['entry_id'];
                                        
            return "success";
        }

       /**
        * delete mandator.
        *
        * The new entry gets validated via entry_validation. 
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         
        * @since        0.5.1
        * @version      0.5.1
        */
        function deleteMandator ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- security -----------------------------------------
            assert ('$this->mayDeleteMandator ($_SESSION["user_id"])'); 
            
            $mandator_to_delete = $params['entry_id'];
            
            // --- don't delete main (default) mandator -------------
            if ($mandator_to_delete == 1) {
                $this->info_msg .= translate ('dont delete default mandator');
                return "failure";    
            }    
            
            // --- don't delete if there are assigned users ---------
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."user_mandator WHERE mandator_id=".$mandator_to_delete;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $row   = mysql_fetch_array($res);
            if ($row[0] > 0) {
                $this->info_msg .= translate ('there are users assigned to this mandator');
                return "failure";                    
            }    
            
            // --- don't delete if there are assigned groups --------
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."group_details WHERE mandator_id=".$mandator_to_delete;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $row   = mysql_fetch_array($res);
            if ($row[0] > 0) {
                $this->info_msg .= translate ('there are groups assigned to this mandator');
                return "failure";                    
            }    

            // === alright, let's try it ============================
            $db_hdl->StartTrans();
            
            // --- delete priorities --------------------------------
            $query = "DELETE FROM ".TABLE_PREFIX."priorities WHERE mandator_id=".$mandator_to_delete;
            $this->ADODBExecuteQuery($query);
            
            // --- delete skins -------------------------------------
            $query = "DELETE FROM ".TABLE_PREFIX."skins WHERE mandator_id=".$mandator_to_delete;
            $this->ADODBExecuteQuery($query);
            
            // --- delete states ------------------------------------
            $query = "DELETE FROM ".TABLE_PREFIX."states WHERE mandator_id=".$mandator_to_delete;
            $this->ADODBExecuteQuery($query);
            
            // --- delete access options ----------------------------
            $query = "DELETE FROM ".TABLE_PREFIX."access_options WHERE mandator_id=".$mandator_to_delete;
            $this->ADODBExecuteQuery($query);

            // --- delete mandator ------------------------------------
            $query = "DELETE FROM ".TABLE_PREFIX."mandator WHERE mandator_id=".$mandator_to_delete;
            $this->ADODBExecuteQuery($query);
            
            if (!$this->ADODBCompleteTrans()) {
                $logger->log ("transaction failed in ".__FILE__,1);
                return "failure";    
            }

            
            return "success";
        }
        
        // === Helper Functions =====================================
        
       /**
        * may user $who delete mandator $whom.
        *
        * 
        * @access       private
        * @param        array  holding request variables
        * @return       string true if user $who is allowed to delete mandator $whom
        * @since        0.5.1
        * @version      0.5.1
        */
        function mayDeleteMandator ($who) {
            global $gacl_api;
            
            if (!$gacl_api->acl_check('Mandatormanager', 'Delete Mandator', 'Person', $who)) {
                $logger->log ('Security validation in '.__CLASS__.'::'.__FUNCTION__, 1);
                return false;
            } 
            
            return true;
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
                    
    }   

?>