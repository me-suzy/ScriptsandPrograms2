<?php

  /**
    * $Id: news_mdl.php,v 1.9 2005/07/31 09:01:06 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package news
    */
    

    //include ('fields_validations.inc.php');
    
    /**
    *
    * Supersede Model Class
    * @package news
    */
    class news_model extends l4w_model {
         
        /**
          * int holding the id of an added user entry
          *
          * @access public
          * @var string
          */  
        var $inserted_contact_id = null;     // ID for user when adding was successfull
        
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
        function news_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array (
                	   "show_current_news",           // current news for a user
                       "show_all_news",
                       "help"
            ));
                                                            
            //include ('fields_definition.inc.php');
                            
        }

      /**
        * validates new or updated entry.
        *
        * If there are any problems, examine
        * model->error_msg and model->info_msg;
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      leads4web
        * @since        0.4.0
        * @version      0.4.3
        */
        /*function contact_validation () {
            global $db_hdl, $logger;
            
            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            $query = "SELECT COUNT(*) FROM gacl_aro_groups WHERE id=".$group;
            //$res   = mysql_query($query);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}
            if (($this->entry['salutation']->get() == "Mr" || $this->entry['salutation']->get() == "Mrs") &&
                    $this->entry['salutation_letter']->get() == translate('dear mr dear mrs')) {
                $this->error_msg = translate('salutation_letter_not_changed');
                return "failure";
            }
            if ($this->entry['birthday']->get() == "dd.mm.yyyy")
                $this->entry['birthday']->set ('');
                
            if (trim ($this->entry['birthday']->get()) != '') {     
                $birthday       = explode (".",$this->entry['birthday']->get());
                if (!checkdate ((int)$birthday[1],(int)$birthday[0],(int)$birthday[2])) {            
                    $this->error_msg = translate('no valid birthday');
                    return "failure";
                }
                if (mktime() < adodb_mktime (0,0,0,(int)$birthday[1],(int)$birthday[0],(int)$birthday[2])) { 
                    $this->error_msg = translate('birthday in future');
                    return "failure";
                }
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
        } */   
       

       /**
        * get all unread news for the current user.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function get_all_news (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $query = "SELECT  *
                FROM ".TABLE_PREFIX."news"; 
                //WHERE metainfo.object_type='contact'";

            $this->dg = new datagrid (20, "news", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        * get all unread news for the current user.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function get_current_news (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- mark news as read? -------------------------------
            if (isset ($params['checked_news'])) {
                $query = "UPDATE news SET beenread='1' WHERE news_id=".$params['checked_news'];
                mysql_query ($query);
                logDBError (__FILE__, __LINE__, mysql_error(), $query);
            }    
                        
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
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            //$db_hdl->debug=true;
            if (is_null($query)) {
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
                    FROM contacts 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=contacts.contact_id
                    WHERE ".TABLE_PREFIX."metainfo.object_type='contact'";
            }                
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.grp=".$_SESSION['use_my_group'];       
            else {
                $query .= " AND ".get_all_groups_or_statement ($_SESSION['user_id']);    
            }    
            
            if ($_SESSION['use_my_state'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.owner=".$_SESSION['use_my_owner'];       

            $query .= " AND (".TABLE_PREFIX."metainfo.owner=".$_SESSION['user_id']." OR
                                 (".TABLE_PREFIX."metainfo.access_level LIKE '____r_____') OR
                                 (".TABLE_PREFIX."metainfo.access_level LIKE '_______r__')
                            ) ";    

            $this->dg = new datagrid (20, "contacts", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
            $this->serializeQuery ($this->dg);
   
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
        function show_contact (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            assert ('$params["contact_id"] > 0');
            
            // get data for this contact
            $contact_query ="
                SELECT * FROM contacts
                LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=contacts.contact_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='contact' AND contacts.contact_id=".$params['contact_id'].")";
            //$res = mysql_query ($contact_query);
            if (!$res = $this->ExecuteQuery ($contact_query, 'mysql_error')) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
                        
            // --- adjust some values -------------------------------
            if ($row['birthday'] == "0000-00-00")
                $this->entry['birthday']->set('');
            else {
                $tmp = explode ("-", $row['birthday']);
                $this->entry['birthday']->set($tmp[2].".".$tmp[1].".".$tmp[0]);
            }
            $this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);
            
            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy('contact', $this->entry['contact_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    //$this->info_msg .= " ".time() - $lock_timestamp;
            }
            else {
                // --- lock contact -------------------------------------
                $this->info_msg .= translate ('contact')." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ('contact', $this->entry['contact_id']->get());
            }
            
            return "success";
        }        
    }   

?>