<?php

  /**
    * $Id: events_mdl.php,v 1.11 2005/08/03 19:43:19 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package users
    */
    
  /**
    *
    * Users Model Class
    * @package events
    */
    class events_model extends l4w_model {

        /**
          * int holding the id of an added user entry
          *
          * @access public
          * @var string
          */  
        var $inserted_event_id = null;     // ID for user when adding was successfull
        
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
        function events_model ($smarty, $AuthoriseClass) {

            // parents constructor
            parent::leads4web_model($smarty, $AuthoriseClass);
            
            // commands
            $this->command  = new easy_string  ("show_users", null,
                array ("show_events",          // list of all users
                       "register1",
                       "register2",
                       "register3",
                       "unregister_event",
                       "edit_template",
                       "update_template",
                       "help"
            ));
            $this->command->strict = true;
                 
            // models data                                           
            $this->entry['users_group']       = new easy_integer (null,0);
            $this->entry['use_user']          = new easy_integer ((int)$_SESSION['user_id'],0);
            $this->entry['reference']         = new easy_string  (null);
            $this->entry['restrict_to_user']  = new easy_integer (null,0);
            $this->entry['restrict_to_grp']   = new easy_integer (null,0);
            $this->entry['action']            = new easy_integer (null,0);            
            $this->entry['watchlist_id']      = new easy_integer (null,0);            
            $this->entry['event_id']          = new easy_integer (null,0);            
            $this->entry['action_id']         = new easy_integer (null,0);            
            $this->entry['name']              = new easy_string  (null);            
            $this->entry['path']              = new easy_string  (null);            
            $this->entry['content']           = new easy_string  (null);            
            $this->entry['pathoffset']        = new easy_string  ('../../');
        }
       
      /**
        * Delete event
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
        function unregister ($params) {
            global $db_hdl, $logger, $gacl_api;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Delete User', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            assert ('$this->entry["watchlist_id"]->get() > 0');

            // --- delete from watchlist ----------------------------
            $del_query = "
                DELETE FROM ".TABLE_PREFIX."eventwatcher 
                WHERE watchlist_id=".$this->entry['watchlist_id']->get();
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
        function show_events ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            if (!$gacl_api->acl_check('Eventmanager', 'Show Eventmanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $query = '
			    SELECT 
                    watchlist_id,
                    w.event_id,
                    object_type,
                    event,
                    CONCAT(u.firstname," ",u.lastname) AS restrict_to_user,
                    g.name AS restrict_to_grp,
                    perform_action,
                   	e.description,
                   	added_by,
                   	added_date,
                   	event_type,
                   	action_id,
                   	a.name,
                   	user_function,
                   	a.description AS action_desc		    
                FROM '.TABLE_PREFIX.'eventwatcher w
                LEFT JOIN '.TABLE_PREFIX.'events e ON w.event_id=e.event_id
                LEFT JOIN '.TABLE_PREFIX.'actions a ON w.perform_action=action_id  
                LEFT JOIN '.TABLE_PREFIX.'users u ON u.id=w.restrict_to_user
                LEFT JOIN '.TABLE_PREFIX.'gacl_aro_groups g ON g.id=w.restrict_to_grp 
                WHERE watcher='.$this->entry['use_user']->get();
            //$db_hdl->debug=true;

            $this->dg = new datagrid (20, "users", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
                        
            return "success";
        }
        
       /**
        * register1.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function register1 ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
                        
            return "success";
        }

       /**
        * register2.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function register2 ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
                        
            return "success";
        }

       /**
        * register3.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function register3 ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
                        
            // --- validation ---------------------------------------
            if (!isset ($params['event'])) {
                $this->error_msg = translate ('no event given'); 
                return "failure";
            }    
                                       
            // --- add entry ----------------------------------------
            foreach ($params['event'] AS $key => $myevent) {
            	$query = "
            	    INSERT INTO ".TABLE_PREFIX."eventwatcher (
                        watcher, event_id, restrict_to_user, restrict_to_grp, perform_action
                    ) 
        	        VALUES
        		        (".$this->entry['use_user']->get().",
                         $myevent,
                         '".$this->entry['restrict_to_user']->get()."',
                         '".$this->entry['restrict_to_grp']->get()."',
                         ".$this->entry['action']->get().")";
                //die ($query);
                if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            }
            
            return "success";
        }

       /**
        * getTemplate
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.5.2
        * @version      0.5.2
        */
        function getTemplate () {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- find out templates name and path -----------------
            $watchlist_id = $this->entry['watchlist_id']->get();
            $event_id     = $this->entry['event_id']->get();
            $action_id    = $this->entry['action_id']->get();
            
            if ($watchlist_id > 0)
                $this->getFileInfo ($watchlist_id, true);
            else { // event_id given?
                $query = "
                    SELECT e.object_type, e.event, a.name from ".TABLE_PREFIX."events e
                    left join actions a ON a.action_id = $action_id
                    WHERE e.event_id=".$event_id;
                $this->getFileInfo (0, true, $query);                
            }    
                                    
            return "success";
        }

       /**
        * updateTemplate
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.5.2
        * @version      0.5.2
        */
        function updateTemplate () {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
            //    die ("security check failed in ".__FILE__);    
            //} 

            // --- find out templates name and path -----------------
            $watchlist_id = $this->entry['watchlist_id']->get();
            $event_id     = $this->entry['event_id']->get();
            $action_id    = $this->entry['action_id']->get();
            
            if ($watchlist_id > 0)
                $this->getFileInfo ($watchlist_id, false);
            else { // event_id given?
                $query = "
                    SELECT e.object_type, e.event, a.name from ".TABLE_PREFIX."events e
                    left join actions a ON a.action_id = $action_id
                    WHERE e.event_id=".$event_id;
                $this->getFileInfo (0, false, $query);                
            }    

            $path    = $this->entry['path']->get();
            $name    = $this->entry['name']->get();
            $content = $this->entry['content']->get();

            // --- write new content --------------------------------
            if (!$fh = fopen ($path.$name, "wb")) {
                $this->error_msg .= translate ('could not open file')." ".$path.$name;
                return "failure";    
            }
            fwrite ($fh, $content);
            fclose ($fh);
                                    
            return "success";
        }   
        
       /**
        * updateTemplate
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.5.2
        * @version      0.5.2
        */
        function getFileInfo ($watchlist_id, $get_content, $query = null) {

            if (is_null ($query)) {
                $query = "
                    SELECT e.object_type, e.event, a.name from ".TABLE_PREFIX."eventwatcher w
                    left join events e ON w.event_id = e.event_id
                    left join actions a ON w.perform_action = a.action_id
                    where w.watchlist_id=$watchlist_id
                ";
            }
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            assert ('mysql_num_rows ($res) == 1');
            $row     = mysql_fetch_array ($res);
            
            list ($subpath, $path, $name) = $this->getNameAndPath ($row['object_type'], $row['name'], $row['event']);
            
            // --- if template does not exist, create it ------------
            if (!file_exists ($path.$name)) {
                if (!file_exists ($subpath)) {
                    if (!mkdir ($subpath)) {
                        $this->error_msg .= translate ('could not create dir')." ".$subpath;
                        return "failure";                                                    
                    }                            
                }    
                if (!file_exists ($path)) {
                    if (!mkdir ($path)) {
                        $this->error_msg .= translate ('could not create dir')." ".$path;
                        return "failure";                                                    
                    }        
                }    
                if (!$fh = fopen ($path.$name, "w")) {
                    $this->error_msg .= translate ('could not create file')." ".$path.$name;
                    return "failure";    
                }
                $content = $name;
                fwrite ($fh, $content);
                fclose ($fh);
            }                
            else {
                if (!$fh = fopen ($path.$name, "rb")) {
                    $this->error_msg .= translate ('could not open file')." ".$path.$name;
                    return "failure";    
                }
                if ($get_content) {
                    $content = fread ($fh, filesize ($path.$name));    
                }
            }    
            
            $this->entry['path']->set    ($path);
            $this->entry['name']->set    ($name);
            if ($get_content) {
                $this->entry['content']->set ($content);
            }
        }   

        function getNameAndPath ($type, $name, $event) {

            $query = "
                SELECT language from ".TABLE_PREFIX."languages l
                where l.lang_id=".$_SESSION['language']."
            ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $row      = mysql_fetch_array ($res);
            $language = $row['language'];

            //list ($subpath, $path, $name) = $this->getNameAndPath ($row['object_type'], $row['name'], $row['event']);
            $subpath = $this->entry['pathoffset']->get()."templates/".$name."/";
            $path    = $subpath.$type."/";
            $name    = str_replace (" ", "_", trim($event))."_".$language.".tpl";
   
            return array ($subpath, $path, $name);
        }    
          
        function getAutoEventTable () {

            // security ???
            
            $html = '<table border=0>';
            
            $query = "
                SELECT * FROM ".TABLE_PREFIX."events e
                where default_action > 0 
                ORDER BY object_type, event
            ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            while ($row      = mysql_fetch_array ($res)) {
                $html .= '<tr>';
                
                $html .= '<td>'.$row['object_type'].'</td>';
                $html .= '<td>'.$row['event'].'</td>';
                $html .= '<td>'.$row['description'].'</td>';
                $html .= '<td><a href="index.php?command=edit_template&event_id='.$row['event_id'];
                $html .= '&action_id='.$row['default_action'].'">';
                $html .= translate ('edit template').'</a></td>';

                $html .= '</tr>';
            }    
            $html .= '</table>';
            
            return $html;
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
    
    }   

?>