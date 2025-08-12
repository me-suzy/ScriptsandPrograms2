<?php

  /**
    * $Id: users_mdl.php,v 1.30 2005/07/31 09:10:02 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package users
    */
    
  /**
    *
    * Users Model Class
    * @package users
    */
    class users_model extends l4w_model {

        /**
          * int holding the id of an added user entry
          *
          * @access public
          * @var string
          */  
        var $inserted_user_id = null;     // ID for user when adding was successfull
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function users_model ($smarty, $AuthoriseClass) {

            // parents constructor
            parent::leads4web_model($smarty, $AuthoriseClass);
            
            // commands
            $this->command  = new easy_string  ("show_users", null,
                array ("show_users",           // list of all users
                       "add_user",             // action
                       "delete_user_view",     // view
                       "delete_user",          // action
                       "update_user",          // action
                       "update_users_groups",  // action
                       "view_user",            // view
                       "copy_from_dg",
                       "switch_to_user",
                       "help"
            ));
                 
            // models data                                           
            $this->entry['users_group']       = new easy_integer (null,0);
            $this->entry['use_user']          = new easy_integer (null,0);
            $this->entry['firstname']         = new easy_string  (null);
            $this->entry['lastname']          = new easy_string  (null);
            $this->entry['email']             = new easy_string  ('');
            $this->entry['login']             = new easy_string  (null);
            $this->entry['pass1']             = new easy_string  (null);
            $this->entry['pass2']             = new easy_string  (null);
            $this->entry['jabber_id']         = new easy_string  ('');
            $this->entry['jabber_pass']       = new easy_string  ('');

            $this->entry['navigation']        = new easy_select   (
                    array ("tree"         => translate ('tree',  null, true),
                           "verticaltabs" => translate ('verticaltabs', null, true)
                      ),  1, getNavigationStyle());
              
            $this->entry['mandators']         = new easy_string  (null);
            /*$this->entry['mandators']         = new easy_select   (
                    array ("Mr"  =>translate  ('Mr'),
                           "Mrs" => translate ('Mrs'),
                           "n/a" => translate ('n/a')
              ),  4, 'Mr');
			*/
            // adaptions 
            $this->entry['email']->set_empty_allowed (false);
                            
        }
       
      /**
        * Add User.
        *
        * Tries to add a new user to the database. If there are any problems, examine
        * model->error_msg and model->info_msg;
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function add_user ($params) {
            global $logger, $db_name, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Usermanager', 'Add User', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            if (!$this->validate_user_data ()) return "failure";
               
            // --- additional validation ----------------------------         
            // Login must not exist yet
            $exists_query = "
                SELECT COUNT(*) 
                FROM ".TABLE_PREFIX."users 
                WHERE login='".$this->entry['login']->get()."'";
            if (!$login_res = $this->ExecuteQuery ($exists_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $login_row = mysql_fetch_array ($login_res);
            if ($login_row[0] > 0) { 
                $this->error_msg = translate('login exists');
                return "failure";
            }
            // password too short?
            if (strlen($this->entry['pass1']->get()) < 3) {
                $this->error_msg = translate('password too short');
                return "failure";                
            }    

            // --- add entry ----------------------------------------
            // add user first:
        	$user_res_query = "
        	    INSERT INTO ".TABLE_PREFIX."users (
        	        login, 
        	        password, 
        	        grp, 
        	        firstname, 
        	        lastname, 
        	        email) 
        	    VALUES
        		    ('".$this->entry['login']->get()."',
                     '".md5($this->entry['pass1']->get())."',
                     ".$this->entry['users_group']->get().",
                     '".$this->entry['firstname']->get()."',
                     '".$this->entry['lastname']->get()."',
                     '".$this->entry['email']->get()."')";

            if (!$this->ExecuteQuery ($user_res_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $this->inserted_user_id = mysql_insert_id ();

            // Add to gacl
            $user_count_res = mysql_query ("SELECT COUNT(*) FROM ".TABLE_PREFIX."users");
            $user_count_row = mysql_fetch_array ($user_count_res);
            logDBError (__FILE__, __LINE__, mysql_error());
        
            $gacl_api->add_object ('Person', $this->entry['login']->get(), $this->inserted_user_id, 
                                          $user_count_row[0],
                                          false, 'aro');

            // find out group_id for group $use_group:
            if (!$gacl_api->add_group_object($this->entry['users_group']->get(), 'Person', $this->inserted_user_id, 'aro')) {
                $this->info_msg .= "adding object to group failed.";    
            }

    		// Eintrag in Tabelle user_details
	    	$query = "
				INSERT INTO ".TABLE_PREFIX."user_details 
					(user_id, skin, lang, created_by)
		    	VALUES ('".$this->inserted_user_id."','4','2',".$_SESSION['user_id'].")";
            $this->ExecuteQuery ($query, 'mysql_error', false, __FILE__, __LINE__);

			// Add mandator access
			if (isset ($params['mandators'])) {
				for ($i=0; $i < count ($params['mandators']); $i++) {
			    	$query = "
						INSERT INTO ".TABLE_PREFIX."user_mandator 
							(user_id, mandator_id)
		    			VALUES (".$this->inserted_user_id.",".$params['mandators'][$i].")";
            		$this->ExecuteQuery ($query, 'mysql_error', false, __FILE__, __LINE__);
				}
			}
			
            return "success";
        }

      /**
        * Updates the users default group and all groups (s)he is member of.
        *
        * Updates the users default group and all groups (s)he is member of
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function update_users_groups ($params) {
            global $logger, $gacl_api;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Usermanager', 'Edit User', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            // user must be member of default group if at least one group is selected
            $use_first = false;
            if (isset ($params['default_group'])) {
                $tmp = "member_".$params['default_group'];
                //$_REQUEST[$tmp] = "on";
                if ($params[$tmp] != "on")
                    $use_first = true;
            }
            else 
                $use_first = true;

            // --- update -------------------------------------------
            // update gacl
		
            $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');

            foreach ($formatted_groups AS $key => $value) {
            	// membership
                $tmp = "member_".$key;
                if (isset ($params[$tmp]) && $params[$tmp] == "on") { // add to group (if not already member)
                    if ($use_first) {
                        $params['default_group'] = $key;
                        $use_first = false;
                    }    
                    $logtext  = "Adding User #".$params['use_user']." (";
                    $logtext .= get_username_by_user_id ($params['use_user']).") to group ";
                    $logtext .= "#".$key." (".get_group_alias($key).")";
                    $logger->log ($logtext,4);
                    if (!$gacl_api->add_group_object (
                                    $key, 
                                    'Person', 
                                    $params['use_user'], 
                                    'ARO')) {
                        $logger->log ("phpgacl: Adding object failed.",1);                            
                    }
                }
                else { //remove from group (if member at all)
                    $logtext  = "Deleting User #".$params['use_user']." (";
                    $logtext .= get_username_by_user_id ($params['use_user']).") from group ";
                    $logtext .= "#".$key." (".get_group_alias($key).")";
                    $logger->log ($logtext,4);
                    if (!$gacl_api->del_group_object (
                                    $key, 
                                    'Person', 
                                    $params['use_user'], 
                                    'ARO')) {
                        $logger->log ("phpgacl: Deleting object failed.",1);                            
                    }
                }        
            }    
            // update table users
            $new_default_group = 0;
            if (isset($params['default_group'])) 
                $new_default_group = $params['default_group'];
                
            $query = "UPDATE ".TABLE_PREFIX."users SET grp=".$new_default_group." WHERE id=".$params['use_user'];
            $this->ExecuteQuery ($query, 'mysql_error', false, __FILE__, __LINE__);
            
            return "success";
        }

      /**
        * Update user
        *
        * Updates information about a user
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.5.1
        * @version      0.5.1
        */
        function setMandatorForUser ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            //var_dump ($params);
            
            // --- security -----------------------------------------
            if (($this->entry['use_user'] != $_SESSION['user_id']) &&
                (!$gacl_api->acl_check('Usermanager', 'Edit User', 'Person', $_SESSION['user_id']))) {
                die ("security check failed in ".__FILE__." ".__LINE__);    
            } 

           if ($params['setMandator']) {
	    		$query = "
					REPLACE INTO ".TABLE_PREFIX."user_mandator 
						(user_id, mandator_id)
	    			VALUES (".$this->entry['use_user']->get().",".$params['mandator_id'].")";
	    		//die ($query);
        		$this->ExecuteQuery ($query, 'mysql_error', false, __FILE__, __LINE__);
            }  
            else {
                $del_query = "
                    DELETE FROM ".TABLE_PREFIX."user_mandator 
            	    WHERE user_id=".$this->entry['use_user']->get()."
            	          AND
            	          mandator_id=".$params['mandator_id'];
		      	//echo $del_query;
                $this->ExecuteQuery ($del_query, 'mysql_error', false, __FILE__, __LINE__);
            }      
            
            return "success";    			
        }
             
       /**
        * Update user
        *
        * Updates information about a user
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function update_user ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            //var_dump ($params);
            
            // --- security -----------------------------------------
            if (($params['use_user'] != $_SESSION['user_id']) &&
                (!$gacl_api->acl_check('Usermanager', 'Edit User', 'Person', $_SESSION['user_id']))) {
                die ("security check failed in ".__FILE__." ".__LINE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            if (!$this->validate_user_data ()) return "failure";


            // Login may not exist yet
            $query = "
                SELECT COUNT(*) FROM ".TABLE_PREFIX."users 
                WHERE login='".$this->entry['login']->get()."' AND
                      id<>".$this->entry['use_user']->get();
            if (!$login_res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $login_row = mysql_fetch_array ($login_res);
            if ($login_row[0] > 0) { 
                $this->error_msg = translate('login exists');
                return "failure";
            }
            
            // --- update -------------------------------------------
            $query = "
                UPDATE ".TABLE_PREFIX."users 
                    SET login='".$this->entry['login']->get()."',
                        grp=".$this->entry['users_group']->get().",
                        firstname='".$this->entry['firstname']->get()."',
                        lastname='".$this->entry['lastname']->get()."',
                        email='".$this->entry['email']->get()."'
                WHERE id=".$this->entry['use_user']->get();
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            if (mysql_error() == '' && ($_SESSION['user_id'] == $this->entry['use_user']->get())) {
                // Update session
                $_SESSION['login'] = $_REQUEST['login'];
		    }
		    // --- update gacl --------------------------------------
		    $query = "
                UPDATE ".TABLE_PREFIX."gacl_aro 
                    SET name='".$this->entry['login']->get()."'
                WHERE value=".$this->entry['use_user']->get();
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure"; 
		    
		    if ($this->entry['pass1']->get() != '') {
                $query = "
                    UPDATE ".TABLE_PREFIX."users
                        SET password='".md5($this->entry['pass1']->get())."'
                    WHERE id=".$this->entry['use_user']->get();
                if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

                // Update session
                if ($_SESSION['user_id'] == $this->entry['use_user']->get())
                    $_SESSION['passwort'] = $this->entry['pass1']->get();
            }
            
            // --- Add mandator access --------------------------
			if (isset ($params['mandators'])) {
                if ($this->entry['use_user']->get() != $_SESSION['user_id']) {
    	            $del_query = "DELETE FROM ".TABLE_PREFIX."user_mandator 
    							  WHERE user_id=".$this->entry['use_user']->get();
    				$this->ExecuteQuery ($del_query, 'mysql_error', false, __FILE__, __LINE__);
    			
    				for ($i=0; $i < count ($params['mandators']); $i++) {
    		    		$query = "
    						INSERT INTO ".TABLE_PREFIX."user_mandator 
    							(user_id, mandator_id)
    		    			VALUES (".$this->entry['use_user']->get().",".$params['mandators'][$i].")";
    	        		$this->ExecuteQuery ($query, 'mysql_error', false, __FILE__, __LINE__);
    				}
                }
			}
            
            // --- jabber --------------------------------------------
            $query = "
                UPDATE ".TABLE_PREFIX."user_details SET 
                    jabber_id   = '".$this->entry['jabber_id']->get()."',
                    jabber_pass = '".$this->entry['jabber_pass']->get()."',
                    navigation  = '".$this->entry['navigation']->get()."'
                WHERE user_id=".$this->entry['use_user']->get();
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) {
                $this->info_msg .= "Adding jabber information failed";    
            }    
            
            $this->info_msg .= translate ('userinfo changed');
            if (isset ($_REQUEST['self']) && $_REQUEST['self'] == 'true')
                return "userinfo";
            return "success";
        }

      /**
        * Delete user
        *
        * Deletes a user. The caller needs the Usermanager->Delete User permission,
        * otherwise the script will terminate. A user cannot delete himself, and there
        * has to be a successor. If no successor is provided, the current user will
        * be the successor of the users entries.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function delete_user ($params) {
            global $db_hdl, $logger, $gacl_api;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Usermanager', 'Delete User', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

			// --- current user may delete "use_user"? --------------
            if (!$this->mayDelete ($_SESSION['user_id'], $this->entry['use_user']->get(), true)){
                return "failure";    
            } 

            $successor_user = $_SESSION['user_id'];
            $successor_grp  = get_main_group ($_SESSION['user_id']);
            if (isset ($params['successor'])) {
                $tmp            = explode ("|", $params['successor']);
                $successor_user = $tmp[0];
                $successor_grp  = $tmp[1];
            }    
                        
            // --- update metainfo  -------------------------------------
            $upd_query = "
                UPDATE ".TABLE_PREFIX."metainfo SET 
                    owner=$successor_user,
                    grp=$successor_grp
                WHERE owner=".$this->entry['use_user']->get()." 
            ";
            if (!$this->ExecuteQuery ($upd_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- delete from mailevents ---------------------------
            //$del_query = "DELETE FROM mailevents WHERE user_id=".$this->entry['use_user']->get();
            //if (!$this->ExecuteQuery ($del_query, 'mysql_error')) return "failure";

            // --- delete from quicklinks ---------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."quicklinks WHERE owner=".$this->entry['use_user']->get();
            if (!$this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- update users_deleted -----------------------------
            $sel_query = "
                SELECT * FROM ".TABLE_PREFIX."user_details a
                LEFT JOIN ".TABLE_PREFIX."users b ON a.user_id=b.id
                WHERE user_id=".$this->entry['use_user']->get(); 
            if (!$res = $this->ExecuteQuery ($sel_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $details_row = mysql_fetch_array ($res);

            $query = "
                INSERT INTO ".TABLE_PREFIX."users_deleted (
                    id, login, grp, 
                    salutation, firstname, lastname,
                    email, login_count, last_login,
                    created, created_by, deleted)
                VALUES (
                    ".$this->entry['use_user']->get().",'".$details_row['login']."',     ".$details_row['grp'].", 
                    '',                                 '".$details_row['firstname']."','".$details_row['lastname']."',
                    '".$details_row['email']."',        ".$details_row['login_count'].",'".$details_row['last_login']."',
                    '".$details_row['created']."',      '".$details_row['created_by']."',now()
                    )
            ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- delete user_details ------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."user_details WHERE user_id=".$this->entry['use_user']->get();
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- delete user --------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."users WHERE id=".$this->entry['use_user']->get();
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            // --- finally: delete aro ------------------------------
            $query = "SELECT id FROM ".TABLE_PREFIX."gacl_aro WHERE value=".$this->entry['use_user']->get();
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $row = mysql_fetch_array ($res);
            $done = $gacl_api->del_object ($row[0], 'aro', true);
            if (!$done) {
                $this->error_msg = "GACL: ".translate ('deleting user failed');
                return "failure";                
            }
            
            $this->info_msg = translate ('deleting user success');    
                        
            return "success";
        }

       /**
        * Show all entries.
        *
        * shows all users which belong to the current mandator. If the current
        * user is contained in the SUPERADMIN_ARRAY, all users are listed.
        * In any case, the user has to have the appropriate right (Show Usermanager)
        * to gain access to this information at all.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.5.1
        */
        function show_users ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $query = '
			    SELECT 
			        '.TABLE_PREFIX.'users.id,
			        section_value,
			        '.TABLE_PREFIX.'gacl_aro.value,
			        '.TABLE_PREFIX.'gacl_aro.name AS login, 
			        firstname,
			        lastname, 
			        email,
			        '.TABLE_PREFIX.'gacl_aro_groups.name
			    FROM '.TABLE_PREFIX.'gacl_aro 
                LEFT JOIN '.TABLE_PREFIX.'users ON '.TABLE_PREFIX.'users.id='.TABLE_PREFIX.'gacl_aro.value
			    LEFT JOIN '.TABLE_PREFIX.'gacl_aro_groups ON '.TABLE_PREFIX.'users.grp='.TABLE_PREFIX.'gacl_aro_groups.id
			    LEFT JOIN '.TABLE_PREFIX.'user_mandator um ON um.user_id='.TABLE_PREFIX.'users.id
				WHERE hidden=0 AND um.mandator_id='.$_SESSION['mandator'].'
			    ORDER BY section_value,order_value,'.TABLE_PREFIX.'gacl_aro.name
            ';
			
			// A superuser is allowed to see more than only the current mandator:
			if (is_superadmin()) {
				$query = '
				    SELECT 
				        '.TABLE_PREFIX.'users.id,
				        section_value,
				        '.TABLE_PREFIX.'gacl_aro.value,
				        '.TABLE_PREFIX.'gacl_aro.name AS login, 
				        firstname,
				        lastname, 
				        email,
				        '.TABLE_PREFIX.'gacl_aro_groups.name
				    FROM '.TABLE_PREFIX.'gacl_aro 
	                LEFT JOIN '.TABLE_PREFIX.'users ON '.TABLE_PREFIX.'users.id='.TABLE_PREFIX.'gacl_aro.value
				    LEFT JOIN '.TABLE_PREFIX.'gacl_aro_groups ON '.TABLE_PREFIX.'users.grp='.TABLE_PREFIX.'gacl_aro_groups.id
				    WHERE hidden=0
				    ORDER BY section_value,order_value,'.TABLE_PREFIX.'gacl_aro.name
	            ';
				}	

            $this->dg = new datagrid (20, "users", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        * Show contact.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function view_user ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- security -----------------------------------------
            
            if ($params['use_user'] != $_SESSION['user_id'] && 
                !$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." ".__LINE__);    
            } 

            assert ('$params["use_user"] > 0');
            
            // get data for this contact
            $contact_query ="
                SELECT * FROM ".TABLE_PREFIX."users
                LEFT JOIN ".TABLE_PREFIX."user_details ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."user_details.user_id
                WHERE ".TABLE_PREFIX."users.id=".$params['use_user'];
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);
                        
            $this->entry['firstname']->set ($row['firstname']);
            $this->entry['lastname']->set ($row['lastname']);
            $this->entry['email']->set ($row['email']);
            $this->entry['login']->set ($row['login']);
            $this->entry['jabber_id']->set ($row['jabber_id']);
            $this->entry['jabber_pass']->set ($row['jabber_pass']);

            return "success";
        }

      /**
        * Gets information about a user.
        *
        * This information is shown when the user is about to be deleted.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function user_summary ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Usermanager', 'Edit User', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            assert ('$params["use_user"] > 0');
            
            // get data for this user
            $contact_query ="
                SELECT * FROM ".TABLE_PREFIX."users
                LEFT JOIN ".TABLE_PREFIX."user_details ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."user_details.user_id
                WHERE ".TABLE_PREFIX."users.id=".$params['use_user'];
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);
                        
            $this->entry['users_group']->set ($row['grp']);
            $this->entry['firstname']->set ($row['firstname']);
            $this->entry['lastname']->set ($row['lastname']);

            return "success";
        }

     /**
        * switches user
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         validation and security
        * @since        0.5.1
        * @version      0.5.1
        */
        function switchUser (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //$validation = $this->note_validation();
            //if ($validation != "success") return $validation; 
                                    
            // --- sufficient rights ? ------------------------------
            if (!$GLOBALS['gacl_api']->acl_check('Usermanager', 'Switch User', 'Person', $_SESSION['user_id'])) {
			    $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            $_SESSION['user_id'] = $params['user_id'];
                                        
            return "success";
        }
 
        
      /**
        * validation
        *
        * This validation is checked if a user is added or updated.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function validate_user_data () {

            if (!((int)$this->entry['users_group']->get() > 10)) { // don't use root group
                $this->error_msg = translate('internal problem in '.__FILE__." ".__LINE__);
                return false;
            }

            if (!$this->entry['email']->validate()) { 
                $this->error_msg = translate('please provide a valid email address');
                return false;
            }
            if (strlen (trim($this->entry['firstname']->get())) < 2) {
                $this->error_msg = translate('please provide a valid first name');
                return false;                
            }    
            
            if (strlen (trim($this->entry['lastname']->get())) < 2) {
                $this->error_msg = translate('please provide a valid last name');
                return false;                
            }    

            if (strlen (trim($this->entry['login']->get())) < 3) {
                $this->error_msg = translate('please provide a valid login');
                return false;                
            }    
            
            if ($this->entry['pass1']->get() != $this->entry['pass2']->get()) {
                //echo $this->entry['pass1']->get()." / ".$this->entry['pass2']->get()."<br>";
                $this->error_msg = translate('passwords_differ');
                return false;                
            }    

            return true;
        }
    
       /**
        * is the user $who allowed to delete user $whom?
        *
        * use this function to decide, if the user $who is allowed to 
        * delete user $whom. 
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        int $who how wants to delete a user
        * @param        int $whom which user shall be deleted
        * @param        boolean $set_error_msg should an error message be added to $this->error_msg
        * @since        0.4.0
        * @version      0.5.1
        */
    	function mayDelete ($who, $whom, $set_error_msg = false) {

			$ret   = false;
    		$chain = array ();
			$this->getCreatedByChain ($who,$chain);
			
			// The "$gacl_api->acl_check('Usermanager', 'Delete User',..." call is 
			// kind of expansive; that's why it is not checked in here, but in the
			// "real" function call of delete_user
            
            // --- do not delete yourself! --------------------------
            if ($whom == $_SESSION['user_id']) {
            	if ($set_error_msg)
	                $this->error_msg = translate ('dont delete yourself');
                return false;    
            }  
            
            // --- do not delete superadmin! --------------------------
            if (is_superadmin($whom)) {
            	if ($set_error_msg)
	                $this->error_msg = translate ('dont delete superadmin!');
                return false;    
            }    
            
            
            // --- dont delete someone who is responsible for your "creation"
            if (in_array ($whom, $chain)) {
            	if ($set_error_msg)
	                $this->error_msg = translate ('dont delete your creator');
                return false;    
            }  
            
            return true; 
    	}
    			
    	function getCreatedByChain ($start, &$ret) {
    		
    		if ($start == 0) return $ret;
    		$query = "
				SELECT created_by FROM ".TABLE_PREFIX."user_details
 				WHERE user_id=$start
			";
			if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
			while ($row = mysql_fetch_array($res)) {
				$ret[] = $row[0];
			} 
			$this->getCreatedByChain($row[0], $ret);	
    	}	
    }   

?>