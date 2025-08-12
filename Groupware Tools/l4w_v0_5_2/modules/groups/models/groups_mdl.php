<?php

  /**
    * $Id: groups_mdl.php,v 1.16 2005/07/31 09:01:06 carsten Exp $
    *
    * Model for groups. See easy_framework for more information
    * about controlers and models.
    * @package groups
    */
    
  /**
    *
    * Groups Model Class
    * @package groups
    */
    class groups_model extends l4w_model {
         
        /**
          * int holding the id of an added group entry
          *
          * @access public
          * @var string
          */  
        var $inserted_group_id = null;
        
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
        * @since        0.1.0
        * @version      0.1.0
        */
        function groups_model (&$smarty, &$AuthoriseClass) {

            parent::leads4web_model($smarty, $AuthoriseClass); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_users", null,
                array ("show_groups",          // list of all groups
                       "add_group",            // action
                       "delete_group_view",    // view
                       "delete_group",         // action
                       "update_group",         // action
                       "view_group",            // view
                       "show_hierarchy"
            ));
            
            $this->entry['groups_html']       = new easy_string  (null);
            $this->entry['name']              = new easy_string  (null);
            $this->entry['description']       = new easy_string  (null);
            $this->entry['parent']            = new easy_integer (null,0);
            $this->entry['group_id']          = new easy_integer (null,0);
            $this->entry['users_in_group']    = new easy_integer (null,0);
        
            $this->entry['group_id']->set_null_allowed (true);
        }
       
       /**
        * Add Group.
        *
        * Tries to add a new group to the database. If there are any problems, examine
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
        function add_group () {
            global $db_hdl, $logger, $db_name, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Add Group', 'Person', $_SESSION['user_id'])) {
                $msg = "security check failed in ".__FILE__." (".__LINE__.")";
                $logger->log ($msg, 4);
                die ($msg);    
            }
            
            // --- basic validation ---------------------------------
            if (!$this->validateModel ()) {
                $logger->log ("validation of model failed", 4);
                return "failure";
            }
            
            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            // --- validate -----------------------------------------
             if (trim ($this->entry['name']->get()) == '')  { // name must not be empty
                $this->error_msg = translate('name must not be empty');
                $logger->log ('name was empty', 4);
                return "failure";
            }   
            
            // parent id must exist
            $parent_query    = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$this->entry['parent']->get();
            if (!$parent_res = $this->ExecuteQuery ($parent_query, 'mysql_error', true, __FILE__, __LINE__)) {
                $logger->log ('parent id did not exist', 4);
                return "failure";
            }
            $parent_row      = mysql_fetch_array ($parent_res);
            
            if (!$parent_row[0] > 0) { 
                $logger->log ('parent row was not greater 0', 4);
                $this->error_msg = translate('internal problem in '.__FILE__." ".__LINE__);
                return "failure";
            }
            
            // warning if name already exists, no error
            $name_query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE name='".$this->entry['name']->get()."'";
            if (!$name_res = $this->ExecuteQuery ($name_query, 'mysql_error', true, __FILE__, __LINE__)) {
                return "failure";
            }
            $name_row = mysql_fetch_array ($name_res);
            
            if ($name_row[0] > 0) { 
                $logger->log ('name exists', 4);
                $this->info_msg = translate('warning name exists');
            }
                  
            // --- add entry ----------------------------------------
            // Add to group_details first
	    	$query = "
	    	    INSERT INTO ".TABLE_PREFIX."group_details (description, mandator_id)
		    	VALUES ('".$this->entry['description']->get()."', ".$_SESSION['mandator'].")";
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $inserted_id = mysql_insert_id ();
            
            $new_group_id = $gacl_api->add_group (
                $inserted_id,
                $this->entry['name']->get(), 
                $this->entry['parent']->get(), 
                'ARO');            
            
            $this->inserted_group_id = $new_group_id;
                                     
            return "success";
        }

       /**
        * Update Group.
        *
        * Update Group
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function update_group (&$params) {
            global $db_hdl, $logger, $db_name, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Edit Group', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
             if (trim ($this->entry['name']->get()) == '')  { // name must not be empty
                $this->error_msg = translate('name must not be empty');
                return "failure";
            }   
                        
            // warning if name already exists, no error
            $name_query = "
                SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups 
                WHERE name='".$this->entry['name']->get()."' AND
                      id<>".$this->entry['group_id']->get()."";
            if (!$name_res = $this->ExecuteQuery ($name_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $name_row = mysql_fetch_array ($name_res);
            
            if ($name_row[0] > 0) { 
                $this->info_msg = translate('warning name exists');
            }
                  
            // --- update entry ----------------------------------------
	    	// update group_details
	    	$query = "
	    	    UPDATE ".TABLE_PREFIX."group_details SET 
		    	description = '".$this->entry['description']->get()."'
		    	WHERE id=".get_value_for_gacl_aro_group($this->entry['group_id']->get())."
		    	";
		    
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
             
            // update gacl_aro_groups
            $query = "
	    	    UPDATE ".TABLE_PREFIX."gacl_aro_groups SET 
		    	name = '".$this->entry['name']->get()."'
		    	WHERE id=".$this->entry['group_id']->get()."
		    	";
            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            return "success";
        }
        

       /**
        * Delete Group.
        *
        * Delete Group
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function delete_group (&$params) {
            global $db_hdl, $logger, $gacl_api;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Delete Group', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            $query = '
    			SELECT		a.id, a.name, a.value, count(b.aro_id) AS count
                FROM		'.TABLE_PREFIX.'gacl_aro_groups a
                LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
                WHERE       id = '.$params['group_id'].'
                GROUP BY	a.id,a.name,a.value';
            if (!$grp_res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
	    	$row     = mysql_fetch_array ($grp_res);
	    	
	    	assert ('$row["count"] == 0');
	    	assert ('!$this->has_subgroups ($params["group_id"])');
            
            // --- delete entry -------------------------------------
            // gacl
            if (!$gacl_api->del_group ($params['group_id'], FALSE)) {
                $this->error_msg = "PHPgacl: deleting group failed.";
                return "failure";   
            } 
            
            // insert into groups_deleted
            $insert_into = "
                INSERT INTO ".TABLE_PREFIX."groups_deleted (id, description, deleted) 
	            SELECT id, description, now() 
                FROM ".TABLE_PREFIX."group_details
                WHERE ".TABLE_PREFIX."group_details.id=".$row['value'];
            $logger->log ($insert_into, 7);
            $res = $this->ExecuteQuery ($insert_into, 'mysql_error', true, __FILE__, __LINE__);
            
            // delete group_details
            $del_query = "
                DELETE FROM ".TABLE_PREFIX."group_details 
                WHERE id='".$row['value']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                        
            return "success";
        }

       /**
        * Show all entries.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function show_groups (&$params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Show Groupmanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

/*            $query = '
    			SELECT		a.id, a.name, a.value, count(b.aro_id)
    			FROM		'.TABLE_PREFIX.'gacl_aro_groups a
    			LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
    			LEFT JOIN   '.TABLE_PREFIX.'group_details gd ON a.value=gd.id
				WHERE gd.mandator_id='.$_SESSION["mandator"].'
				GROUP BY	a.id,a.name,a.value';
*/
			// A superuser is allowed to see more than only the current mandator:
			//if (is_superadmin()) {
                $query = '
        			SELECT		a.id, a.name, a.value, count(b.aro_id), gd.mandator_id
        			FROM		'.TABLE_PREFIX.'gacl_aro_groups a
        			LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
        			LEFT JOIN   '.TABLE_PREFIX.'group_details gd ON a.value=gd.id
    				GROUP BY	a.id,a.name,a.value';
			//}

			if (!$grp_res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
		    $group_data = array();
		
			while($row = mysql_fetch_array ($grp_res)) {
				$group_data[$row[0]] = array(
					'name'     => $row[1],
					'value'    => $row[2],
					'count'    => $row[3],
					'mandator' => $row[4]
				);
			}

			$groups_html  = "<table border=0>\n";
            $groups_html .= "<th>".translate('group')."</th>\n";
            $groups_html .= "<th>".translate('mandator')."</th>\n";
            $groups_html .= "<th># ".translate('members')."</th>\n";
            $groups_html .= "<th colspan=3>".translate('action')."</th>\n";

            $formatted_groups = $gacl_api->format_groups($gacl_api->sort_groups('aro'), 'leads4web');

			// get root group
            //$root_groups[0]    = $gacl_api->get_root_group_id();
			// get any (mandator) root group
			$root_groups = array ();
			$roots_query = "SELECT DISTINCT group_root FROM ".TABLE_PREFIX."mandator";
			if (!$roots_res = $this->ExecuteQuery ($roots_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
			while ($roots_row = mysql_fetch_array($roots_res))
				$root_groups[] = $roots_row[0];

            foreach ($formatted_groups AS $key => $value) {
            	// show only if mandator fits
            	$mand_query = '
    				SELECT	 mandator_id, a.value
    				FROM     '.TABLE_PREFIX.'group_details gd
					JOIN     '.TABLE_PREFIX.'gacl_aro_groups a ON gd.id=a.value
					WHERE    a.id='.$key.'
				';

	            if (!$mand_res = $this->ExecuteQuery ($mand_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
				$mand_row = mysql_fetch_array($mand_res);
				if (!is_superadmin()) {
    				if ($mand_row['mandator_id'] != $_SESSION['mandator']) continue;
				}
							
                $groups_html .= "<tr>";
                $groups_html .= "<td><b>$value</b></td>";
                $groups_html .= "<td><b>".getMandatorName($group_data[$key]['mandator'])."</b></td>";
                $groups_html .= "<td align=right>".$group_data[$key]['count']."</td>";
                $groups_html .= "<td><a href='index.php?command=view_group&parent=$key'>[Add Group here]</a></td>";

                if (!in_array ($mand_row['value'], $root_groups)) {
                    $groups_html .= "<td><a href='index.php?command=view_group&group_id=$key'>[Edit Group]</a></td>";
                    $groups_html .= "<td><a href='index.php?command=delete_group_view&group_id=$key'>Delete</a></td>";
                }
                elseif (is_superadmin()) {
                    $groups_html .= "<td><a href='index.php?command=view_group&group_id=$key'>[Edit Group]</a></td>";
                    $groups_html .= "<td><a href='index.php?command=delete_group_view&group_id=$key'>Delete</a></td>";                    
                }    
                else
                    $groups_html .= "<td colspan=2>&nbsp;</td>";
                $groups_html .= "</tr>\n";
            }    
            $groups_html .= "</table>\n";
            
            $this->entry['groups_html']->set ($groups_html);
            return "success";
        }
        
       /**
        * Show all entries (hierachical view).
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.5.1
        * @version      0.5.1
        */
        function show_hierarchy (&$params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Show Groupmanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $query = '
    			SELECT		a.id, a.name, a.value, count(b.aro_id) AS cnt, gd.mandator_id, gd.parent_id
    			FROM		'.TABLE_PREFIX.'gacl_aro_groups a
    			LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
    			LEFT JOIN   '.TABLE_PREFIX.'group_details gd ON a.value=gd.id
				WHERE gd.parent_id='.$this->entry['parent']->get().' AND
                      gd.mandator_id='.$_SESSION['mandator'].'
				GROUP BY	a.id,a.name,a.value';
			
			$this->dg = new datagrid (20, "groups", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        * Show group.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function view_group (&$params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Show Groupmanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // get data for this group if use_group is set
            if ($this->entry['group_id']->get() > 0) {
                $group_query ="
                    select b.parent_id, name, description from ".TABLE_PREFIX."gacl_aro_groups a
                    LEFT join ".TABLE_PREFIX."group_details b on a.value=b.id
                    WHERE a.id=".$this->entry['group_id']->get()."
                ";
                //echo $group_query;
                if (!$res = $this->ExecuteQuery ($group_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
                assert ('mysql_num_rows($res) > 0');
                $row = mysql_fetch_assoc ($res);
            
                $this->entry['name']->set ($row['name']);
                $this->entry['parent']->set ($row['parent_id']);
                $this->entry['description']->set ($row['description']);
            }
            
            return "success";
        }
        
      /**
        * Gets summary for group (usually when the group is about to be deleted)
        * 
        * @access       private
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function group_summary (&$params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Groupmanager', 'Delete Group', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__." (".__LINE__.")");    
            }

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            assert ('$params["group_id"] > 0');
            
            $query = '
    			SELECT		a.id, a.name, a.value, count(b.aro_id) AS count
                FROM		'.TABLE_PREFIX.'gacl_aro_groups a
                LEFT JOIN	'.TABLE_PREFIX.'gacl_groups_aro_map b ON b.group_id=a.id
                WHERE       id = '.$params['group_id'].'
                GROUP BY	a.id,a.name,a.value';
            if (!$grp_res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
	    	$row     = mysql_fetch_array ($grp_res);
            $this->entry['users_in_group']->set($row['count']);            
                       
            return "success";
        }
        
      /**
        * Checks for subgroups of given group
        * 
        * @access       private
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function has_subgroups ($group_id) {
            global $db_hdl, $logger, $gacl_api;
        
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE parent_id=".$group_id; 
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $row   = mysql_fetch_array ($res);
            if ($row[0] > 0)
                return true;            
            return false;
        }    
        
     
    }   

?>