<?php

  /**
    * $Id: workflow_mdl.php,v 1.15 2005/08/04 15:48:30 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package workflow
    */
    
  /**
    *
    * Supersede Model Class
    * @package workflow
    */
    class workflow_model extends l4w_model {
         
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
        function workflow_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array ("add_status",
                       "add_transition",
                       "create_status",
                       "create_transition",
                       "delete_transition",
                       "delete_status",
                       "edit_status",
                       "update_status",
                       "show_references",           // list of all entries
                       "show_states",
                       "show_transitions",
                       "copy_from_dg",
                       "set_startpoint",
                       "set_endpoint",
                       "help"
            ));
                                                                        
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');

        }

       /**
        * create status
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @since        0.5.1
        * @version      0.5.1
        */
        function createStatus (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- sufficient rights? -------------------------------
            if (!$this->mayAddStatus()) return "failure";
            
            // --- validation ---------------------------------------

            // --- get new state ------------------------------------
            $query = "
                SELECT MAX(status)+1 AS new_state FROM ".TABLE_PREFIX."states 
                WHERE 
                    mandator=".$_SESSION['mandator']." AND
                    reference='".$this->entry['type']->get()."'
                    ";
            if (!$res = $this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) return "failure";
            if (mysql_num_rows($res) > 0 ) {
                $row = mysql_fetch_array($res);
                $use_state = $row['new_state'];
            }
            else
                $use_state = 0;
                    
            // --- query --------------------------------------------
            $query = "
                INSERT INTO ".TABLE_PREFIX."states 
					(mandator, reference, status, name, color, description)
                VALUES (
                    ".$_SESSION['mandator'].",
                    '".$this->entry['type']->get()."',             
                    '".$use_state."',             
                    '".$this->entry['name']->get()."',             
                    '".$this->entry['color']->get()."',
                    '".$this->entry['description']->get()."'
                    )";
            if (!$this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) return "failure";

            // --- add default transition from new state back to itself ---
            $query = "
                INSERT INTO ".TABLE_PREFIX."transitions 
					(mandator, reference, grp, user, state_old, state_new, name)
                VALUES (
                    ".$_SESSION['mandator'].",
                    '".$this->entry['type']->get()."',             
                    0,
                    0,
                    ".$use_state.",             
                    ".$use_state.",             
                    'added by system when adding new state'
                    )";
            if (!$this->ExecuteQuery ($query, 'problem adding default transition', __FILE__, __LINE__)) return "failure";
            
            
            return "success";
        }
             
       /**
        * create status
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @todo         check request variable state_new
        * @since        0.5.2
        * @version      0.5.2
        */
        function createTransition (&$params) {
            global $db_hdl, $logger;
//print_r ($this->entry['state_new']);
//die();                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- sufficient rights? -------------------------------
            if (!$this->mayAddTransition()) return "failure";
            
            // --- validation ---------------------------------------
                    
            // --- query --------------------------------------------
            $grp_keys = $this->entry['grp']->get();
            foreach ($grp_keys AS $tmp => $key) {
                $query = "
                    INSERT INTO ".TABLE_PREFIX."transitions 
                        (mandator, reference, grp, user, state_old, state_new, name)
                    VALUES (
                        ".$_SESSION['mandator'].",
                        '".$this->entry['reference']->get()."',             
                        '".$key."',             
                        '0',             
                        '".$this->entry['state']->get()."',
                        '".$_REQUEST['state_new']."',
                        '".$this->entry['name']->get()."'
                        )";
                //die ($query);
                if (!$this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) return "failure";
            }
            
            return "success";
        }
        
       /**
        * delete transition
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @since        0.5.2
        * @version      0.5.2
        */
        function deleteStatus () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- sufficient rights? -------------------------------
            //if (!$this->mayAddTransition()) return "failure";
            
            // --- validation ---------------------------------------
            $query = "
                SELECT object_id FROM ".TABLE_PREFIX."metainfo mi
                LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups aro_groups ON aro_groups.id=mi.grp
                LEFT JOIN ".TABLE_PREFIX."group_details d ON d.id=aro_groups.value
                WHERE d.mandator_id=".$_SESSION['mandator']." AND
                      mi.state=".$this->entry['state']->get()."
            ";       
//echo $query;                    
            if (!$res = $this->ExecuteQuery ($query, 'entries exist', __FILE__, __LINE__)) 
                return "failure";

            if (mysql_num_rows($res) > 0) {
                $this->error_msg .= translate ('entries with given state exist');                
                return "failure";
            }     

            // --- query --------------------------------------------
            // delete all transitions pointing to or from the deleted state
            $query = "
                DELETE FROM ".TABLE_PREFIX."transitions 
                WHERE mandator=".$_SESSION['mandator']." AND
                      reference='".$this->entry['reference']->get()."' AND
                      (
                        state_old=".$this->entry['state']->get()." OR
                        state_new=".$this->entry['state']->get()."
                      )
            ";
            //echo $query;
            
            if (!$this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) 
                return "failure";
            
            // delete all transitions pointing to or from the deleted state
            $query = "
                DELETE FROM ".TABLE_PREFIX."states 
                WHERE mandator=".$_SESSION['mandator']." AND
                      reference='".$this->entry['reference']->get()."' AND
                      status=".$this->entry['state']->get()."
            ";
            //echo $query;
            
            if (!$this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) 
                return "failure";

            return "failure";
        }
                      
       /**
        * delete transition
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @since        0.5.2
        * @version      0.5.2
        */
        function deleteTransition () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- sufficient rights? -------------------------------
            //if (!$this->mayAddTransition()) return "failure";
            
            // --- validation ---------------------------------------
                    
            // --- query --------------------------------------------
            $query = "
                DELETE FROM ".TABLE_PREFIX."transitions 
                WHERE mandator=".$_SESSION['mandator']." AND
                      reference='".$this->entry['reference']->get()."' AND
                      grp='".$this->entry['grp']->get()."' AND
                      user='".$this->entry['usr']->get()."' AND
                      state_old=".$this->entry['state']->get()." AND
                      state_new=".$this->entry['state_new']->get()."
            ";

            if (!$this->ExecuteQuery ($query, 'entry exists', __FILE__, __LINE__)) 
                return "failure";
            
            return "failure";
        }
        

       /**
        * get data for single entry.
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.1
        * @version      0.5.1
        */
        function getStatus (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('isset($params["reference"])');
            assert ('isset($params["state"])');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."states
                WHERE 
                    reference='".$params['reference']."' AND 
                    status=".$params['state']." AND
                    mandator=".$_SESSION['mandator']."
                    ";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
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
        * update status
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.2
        * @version      0.5.2
        */
        function updateStatus () {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- sufficient rights ? -----------------------------
            /*if (!user_may_read ($row['owner'],$row['grp'],$row['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }*/

            // --- validation ---------------------------------------
            $query ="
                UPDATE ".TABLE_PREFIX."states
                SET 
                    name='".$this->entry['name']->get()."',
                    description='".$this->entry['description']->get()."',
                    color='".$this->entry['color']->get()."'                    
                WHERE 
                    reference='".$this->entry['reference']->get()."' AND 
                    status=".$this->entry['state']->get()." AND
                    mandator=".$_SESSION['mandator']."
            ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            //assert ('mysql_num_rows($res) > 0');
            //$row = mysql_fetch_assoc ($res);

            
                        
            return "success";
        }
        
        
       /**
        * Show distinct references.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.4.3
        * @version      0.4.3
        */
        function show_references (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validation ---------------------------------------
            
            //$db_hdl->debug=true;
            $query = "SELECT DISTINCT reference
                      FROM ".TABLE_PREFIX."states 
                      WHERE mandator=".$_SESSION['mandator']."
                      ORDER BY reference
                     ";

            $this->dg = new datagrid (20, "reference", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=1;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
        }
                
       /**
        * Show states for reference.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.4.3
        * @version      0.4.3
        */
        function show_states (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validation ---------------------------------------
            
            //$db_hdl->debug=true;
            $query = "SELECT reference, name, status, startpoint, endpoint
                      FROM ".TABLE_PREFIX."states 
                      WHERE 
                        reference='".$this->entry['reference']->get()."' AND
                        mandator=".$_SESSION['mandator']."
                      ORDER BY name
                     ";
            //echo $query;
            $this->dg = new datagrid (20, "states", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=1;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
        }

       /**
        * Show transitions for state.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.4.3
        * @version      0.4.3
        */
        function show_transitions (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validation ---------------------------------------
            
            //$db_hdl->debug=true;
            $query = "SELECT 
                            t.reference, 
                            g.name AS group_name, 
                            CONCAT(u.firstname,' ',u.lastname) AS user_name, 
                            t.grp, 
                            user, 
                            s.name AS newstate, 
                            t.name, 
                            isdefault,
                            t.state_new
                      FROM ".TABLE_PREFIX."transitions t
                      LEFT JOIN ".TABLE_PREFIX."states s ON s.status=t.state_new AND s.reference='".$this->entry['reference']->get()."'
                      LEFT JOIN ".TABLE_PREFIX."users u ON u.id=t.user
                      LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups g ON t.grp=g.id 
                      WHERE t.reference='".$this->entry['reference']->get()."'
                        AND state_old='".$this->entry['state']->get()."'
                        AND s.mandator=".$_SESSION['mandator']."
                        AND t.mandator=".$_SESSION['mandator']."
                      ORDER BY t.name
                     ";
            //echo $query;
            $this->dg = new datagrid (20, "states", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=1;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
        }

       /**
        * 
        * @access       public
        * @since        0.5.2
        * @version      0.5.2
        */
        function setStartpoint () {
            global $gacl_api;

            $query = "
            	UPDATE ".TABLE_PREFIX."states
            	SET startpoint='0'
            	WHERE reference='".$this->entry['reference']->get()."' AND
	            	mandator=".$_SESSION['mandator']."
            	";
            //echo $query;
            //if (
            $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__); //) 

            $query = "
            	UPDATE ".TABLE_PREFIX."states
            	SET startpoint='1'
            	WHERE reference='".$this->entry['reference']->get()."' AND
            	      status=".$this->entry['state']->get()." AND
            	      mandator=".$_SESSION['mandator']."
            	";
            //echo $query;
            //if (
            $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__); //) 
            //	return "failure";
            
            return "success";
        }

       /**
        * 
        * @access       public
        * @since        0.5.2
        * @version      0.5.2
        */
        function setEndpoint () {
            global $gacl_api;

            $query = "
            	UPDATE ".TABLE_PREFIX."states
            	SET endpoint='".$this->entry['selected']->get()."'
            	WHERE reference='".$this->entry['reference']->get()."' AND
            	      status=".$this->entry['state']->get()." AND
            	      mandator=".$_SESSION['mandator']."
            	";
            echo $query;
            //if (
            $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__); //) 
            
            return "success";
        }

       /**
        * is the current user allowed to add a status? 
        * 
        * @access       private
        * @since        0.5.1
        * @version      0.5.1
        */
        function mayAddStatus () {
            global $gacl_api;

            if (!$gacl_api->acl_check('Workflowmanager', 'Add Workflow', 'Person', $_SESSION['user_id'])) {
                $this->info_msg .= translate ('not allowed to add workflow',null,true);    
                return false;
            }
            
            return true;
        }
    
       /**
        * is the current user allowed to add a transition? 
        * 
        * @access       private
        * @since        0.5.2
        * @version      0.5.2
        */
        function mayAddTransition () {
            
            return $this->mayAddStatus();
        }

    }   

?>