<?php

  /**
    * $Id: leads4web_model.php,v 1.27 2005/07/31 08:45:17 carsten Exp $
    *
    * l4w_model provides common attributes and some functionality for 
    * derived classes like locking.
    * 
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      common
    */
    
  /**
    * leads4web4 models superclass
    *
    * l4w_model provides common attributes and some functionality for 
    * derived classes like locking.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package common
    */
    class l4w_model extends easy_model {
         
        /**
          * holds the models data 
          *
          * @access private
          * @var array
          */
        var $entry = array();  

        /**
          * access via SetErrorMsg
          *
          * @access private
          * @var string
          */
        var $error_msg        = "";

        /**
          * access via SetInfoMsg
          *
          * @access private
          * @var string
          */
        var $info_msg         = "";             
                
        /**
          * default column to order by
          *
          * @access public
          * @var string
          */                
        var $order            = 1;
        
        /**
          * default sorting direction
          *
          * @access public
          * @var string
          */  
        var $direction        = "ASC";
        
        /**
          * default page number to display
          *
          * @access public
          * @var string
          */  
        var $pagenr           = "";

	     /**
	      * deprecated
	      *
	      * @access public
	      * @var class
	      */ 
        var $AuthoriseClass   = null;

 		/**
          * Array holding translations
          *
          * @access public
          * @var array
          */  
		var $LANG             = array ();

        /**
          * sql code which is executed during the lifetime of this script
          *
          * @access private
          * @var string
          */
        var $transact_sql_code = '';
		
        /**
          * model_id
          *
          * @access private
          * @var int
          */
        var $model_id = '';

       /**
        * Constructor.
        *
        * Defines the models attributes
        * Calls parents constructor. The two parameters are planed to 
        * be replaced by an array containing a various list of parameters.
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @since        0.4.2
        * @version      0.4.4
        */
        function leads4web_model (&$smarty, &$AuthoriseClass) {
            parent::easy_model($smarty); // call of parents constructor!
        }

       /**
        * Handle GUI parameter passing
        *
        * internal function to manage parameter passing
        * 
        * @access       private
        * @param        array holds request variables
        * @since        0.4.2
        * @version      0.5.2
        */
        function handleGUIElements ($params) {

            // params are added to model in easy_controller now,
            // therefore: dummy implementation
            return array ($this->entry, array());
            
            
            // --- internal gui for testing a.s.o. ------------------
            if ($this->smarty != null) {
                $this->addParams2Smarty ($params);
            }

            // --- model assignments --------------------------------
            return $this->addParams2Model ($this->entry, $params); //, $omit);
        }
    
      /**
        * Execute queries and handle errors
        *
        * private function which should be called to execute database
        * queries. In case of an error the execution can be stopped and
        * a message can be assigned to the models error_msg attribute.
        * 
        * 2.4.05:       Added line and file parameters
        *
        * @access       private
        * @param        string query to execute
        * @param        string message to show in case of error
        * @param        boolean should execution be stopped in case of error
        * @param        string  call from which file
        * @param        integer call in which line
        * @return       ressource database resource on success, false on failure
        * @since        0.4.2
        * @version      0.4.4
        */
        function ExecuteQuery ($query, $msg, $stop_execution = true, $file = '', $line = 0) {
            
            $res = mysql_query ($query);
            logDBError ($file, $line, mysql_error(), $query);

            if (mysql_error() != '') {
                if ($stop_execution) {
                    $this->error_msg = translate ($msg)." [".mysql_error()."]";
                    return false;
                }    
                else {
                    $this->info_msg = translate ($msg);                    
                }     
            }
    
           return $res;
        }

      /**
        * Execute queries and handle errors, to use with transactions
        *
        * @access       private
        * @param        string query to execute
        * @return       ressource void
        * @since        0.5.1
        * @version      0.5.1
        */
        function &ADODBExecuteQuery ($query) {
            global $db_hdl;
            
            $this->transact_sql_code .= $query . "\n\n";
            $res = $db_hdl->Execute ($query);
            return $res;
        }
        
      /**
        * End transaction and handle possioble errors in a common way
        *
        * @access       private
        * @return       boolean true on success, false on failure
        * @since        0.5.1
        * @version      0.5.1
        */
        function ADODBCompleteTrans () {
            global $db_hdl, $logger;
            
            if ($db_hdl->hasFailedTrans()) {
                $this->error_msg .= "mysql: transaction failed, please check logfile.";    
                $logger->log ("### transaction with error: start ##############", 1);
                $logger->log ($this->transact_sql_code, 1);
            }
            
            return $db_hdl->completeTrans();    
        }    


       /**
        * Method to calculate an identity hash for an array of strings
        *
        * The identity hash is used to get an estimate how unique an
        * entry is. It uses the first ten characters and weights them
        * accorinding to weight.
        * 
        * @access       private
        * @param        array array mapping strings to weights
        * @return       int   hash value
        * @since        0.4.3
        * @version      0.4.4
        */
        function getIdentityHash ($string_array) {
        	
        	$hash = 0;
        	
        	foreach ($string_array AS $string => $weight) {
        		// strip whitespaces
        		$chars = preg_replace("/(\r|\n|\s)/", "", strtoupper($string));
				//echo "String: ".$chars."<br>";
				$chars = substr ($chars, 0, 10);
				//echo "String: ".$chars."<br>";
			    $i          = 9; 
			    $position   = 0;
			    while ($i > 0) {
			   		if (isset ($chars[$position])) {
			   			$hash += $weight * ord($chars[$position]) * pow (2, $i);
			   			$i--;
			   			//echo "value = ".$hash."<br>";
			   		}
			   		else 
			   			break;	
			    }	 				
        	}
        	
        	return $hash;	   
        }      
        
      /**
        * get meta information about entry
        *
        * 
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @since        0.4.4
        * @version      0.4.4
        */
        function get_metainfo ($object_type, $object_id) {
            global $db_hdl, $logger;
            
            $sql = "SELECT owner, grp, state, created, access_level, last_change
                    FROM ".TABLE_PREFIX."metainfo
                    WHERE object_id='$object_id' AND object_type='$object_type'";

            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            return $row;
        }
        
       /**
        * serialize model
        *
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       array  array containing user_id and timestamp, (0,0) in case if the entry is not locked
        * @since        0.5.1
        * @version      0.5.1
        */
        function serialize ($params, $type, $name) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            $name = $this->entry[$name]->get();
            
            if ($name === null || $name == '') $name = translate ('unknown');
            $s = mysql_escape_string(serialize ($this));
            
            $sql = "INSERT INTO ".TABLE_PREFIX."serialized_models 
						(mandator_id, object_type, user_id, name, ts, model)
                    VALUES ('".$_SESSION['mandator']."','".$type."', ".$_SESSION['user_id'].", '".$name."', now(), '".$s."')";

            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) 
                return "failure";

			// set model id
			$this->model_id = mysql_insert_id();
			
            return "success";
        }    

       /**
        * unserialize model
        *
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       array  array containing user_id and timestamp, (0,0) in case if the entry is not locked
        * @since        0.5.1
        * @version      0.5.1
        */
        function unserialize ($params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            $sql = "SELECT model, save_as FROM ".TABLE_PREFIX."serialized_models 
					WHERE model_id=".$params['model_id'];
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__)) 
                return "failure";
            $row = mysql_fetch_array($res);
            $model_string = $row['model'];
            //echo $model_string;
          	$this = unserialize($model_string);

          	// --- delete element if of type (save_as) "clipboard" ---
          	if ($row['save_as'] == "clipboard") {
          		mysql_query ("DELETE FROM ".TABLE_PREFIX."serialized_models
	                          WHERE model_id=".$params['model_id']);	
          	}		
          	
            return "success";
        }    
        
       /**
        * get data for current model id from db
        *
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       array  array containing user_id and timestamp, (0,0) in case if the entry is not locked
        * @since        0.5.1
        * @version      0.5.1
        */
        function getModelData () {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

			$sql = "SELECT * FROM ".TABLE_PREFIX."serialized_models 
					WHERE model_id=".$this->model_id;
            $res = $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__);
			assert ('mysql_num_rows($res) == 1');
			$row = mysql_fetch_array($res);
			
            return $row;
        }
              
       /**
        * update model data
        *
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       array  array containing user_id and timestamp, (0,0) in case if the entry is not locked
        * @since        0.5.1
        * @version      0.5.1
        */
        function adjust_template ($params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

			$template_name = mysql_escape_string($params['template_name']);
			if (trim ($template_name) == '')
				$template_name = translate ('unknown');
			
			$sql = "UPDATE ".TABLE_PREFIX."serialized_models
					SET save_as='template',
						name='".$template_name."',
						grp='".$params['use_group']."'
					WHERE model_id=".$params['model_id'];
            $this->ExecuteQuery ($sql, 'mysql_error', true, __FILE__, __LINE__);
        }
                        
       /**
        * Returns user_id and timestamp if an entry is locked
        *
        * An entry can be locked, as someone has opened it for updating. If
        * it is locked, this function returns the user how has locked it, and
        * the timestamp of locking. 
        * If the entry is not locked, it returns array (0,0).
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       array  array containing user_id and timestamp, (0,0) in case if the entry is not locked
        * @since        0.4.3
        * @version      0.4.4
        */
        function lockedBy ($type, $id) {

            $query = "SELECT user_id, timestamp FROM ".TABLE_PREFIX."useronline 
                      WHERE object_type='$type' AND object_id=$id"; 
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            if (mysql_num_rows($res) == 0) 
                return array (0,0);
            $row = mysql_fetch_array($res);
            return array ($row['user_id'],$row['timestamp']);
            
        }    

       /**
        * Lock entry defined by type and id
        *
        * Use this function to lock an entry (defined by type and id).
        * If the entry is locked already, a warning is added to model->error_msg.
        *
        * 3.4.05        don't lock contact if user cannot save it (i.e. entry is readonly)
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @since        0.4.3
        * @version      0.4.7
        */
        function lockEntry ($type, $id) {
            //global $gacl;
            list ($locked_by, $ts) = $this->lockedBy($type, $id);
            if ($locked_by != 0) {
                $this->error_msg .= "Entry is locked (".__FILE__." ".__LINE__.")";
                return;    
            }    
            
            $lock = false;
            $vals = get_entries_for_primary_key (
                         "metainfo", 
                         array ("object_type" => $type,
                                "object_id"   => $id));
            
            if (user_may_delete ($vals['owner'], $vals['grp'], $vals['access_level']))
                $lock = true;

            if (user_may_edit ($vals['owner'], $vals['grp'], $vals['access_level']))
                $lock = true;
                             
            if (!$lock) return;
                   
            $query = "REPLACE INTO ".TABLE_PREFIX."useronline (timestamp, user_id, object_type, object_id) 
                      VALUES ('".time()."','".$_SESSION['user_id']."','$type','$id')"; 
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            if (!in_array (array ($type, $id), $_SESSION['current_views']))
                array_push ($_SESSION['current_views'], array ($type, $id));
        }    

        //    function user_may_edit ($owner, $grp, $level, $use_user = null) {

        
       /**
        * Unlock entry defined by type and id
        *
        * Use this function to unlock an entry
        * 
        * @access       private
        * @param        string type of the entry (contact, company...)
        * @param        int    object_id of entry
        * @return       int    number of affected rows
        * @since        0.4.3
        * @version      0.4.4
        */
        function unlockEntry ($type, $id) {

            $query = "DELETE FROM ".TABLE_PREFIX."useronline  
                      WHERE user_id='".$_SESSION['user_id']."' AND object_type='$type' AND object_id='$id'"; 
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            //var_dump ($_SESSION['current_views']);
            if (isset($_SESSION['current_views'])) {
                $key   = array_search(array ($type, $id), $_SESSION['current_views']);
                if (!is_null ($key) && $key !== false) {
                    unset ($_SESSION['current_views'][$key]);
                }
            }
                
            return mysql_affected_rows();
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
        * adds references.
        *
        * 
        * @access       private
        * @param        paramas array holding request variables
        * @since        0.4.5
        * @version      0.4.5
        */
        function addReference ($from_type, $from_id, $to_type, $to_id, $desc, $type = 1) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "REPLACE INTO ".TABLE_PREFIX."refering (
                         from_object_type,
                         from_object_id,
                         to_object_type,
                         to_object_id,
                         description,
                         ref_type)
                      VALUES (
                         '$from_type',
                         $from_id,
                         '$to_type',
                         ".$to_id.",
                         '$desc',
                         $type
                        )"; 
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
        }    
       /**
        * adds references.
        *
        * 
        * @access       private
        * @param        paramas array holding request variables
        * @todo         change name, as references can be deleted too
        * @since        0.4.5
        * @version      0.5.1
        */
        function addReferences ($from_type, $from_id, $params, $desc, $type = 1) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            if (isset ($params['new_references'])) {
            
                $new_refs = explode ("|",$params['new_references']);
                
                for ($i=0; $i < (-1 + count ($new_refs)); $i++) {
                    $tmp   = explode ('_', $new_refs[$i]);
                    $this->addReference($from_type, $from_id, $tmp[0], $tmp[1], $desc, $type);
                    
                }
            }   
            
            // delete first
			$query = "
					DELETE FROM ".TABLE_PREFIX."refering
					WHERE 
						from_object_type='$from_type' AND
						from_object_id=$from_id AND
						to_object_type='collection'
				";
			//echo $query;
			$res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            
            if (isset ($params['addto_collection'])) {

                $collections = $params['addto_collection'];
                
                for ($i=0; $i < (count ($collections)); $i++) {

                    $query = "INSERT INTO ".TABLE_PREFIX."refering (
                                 from_object_type,
                                 from_object_id,
                                 to_object_type,
                                 to_object_id,
                                description)
                              VALUES (
                                 '$from_type',
                                 $from_id,
                                 'collection',
                                 ".$collections[$i].",
                                 ''
                                )"; 
                    //die ($query);
                    $res   = mysql_query ($query);
                    logDBError (__FILE__, __LINE__, mysql_error(), $query);
                }
            }             
        }
        
        /**
        * deletes reference.
        *
        * 
        * @access       private
        * @param        paramas array holding request variables
        * @since        0.4.5
        * @version      0.4.5
        */
        function delReference ($params) {
            global $db_hdl, $logger;
                        die (__FILE__." ".__LINE__);
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            $meta_values    = get_entries_for_primary_key(
                                            "metainfo", 
                                            array ("object_type" => $params['from_object_type'],
                                                   "object_id"   => $params['from_object_id']));

            // --- sufficient rights ? ------------------------------
            if (user_may_edit ($meta_values['owner'],$meta_values['grp'],$meta_values['access_level'])) {
                $query = "DELETE FROM ".TABLE_PREFIX."refering 
                          WHERE 
                                from_object_type='".$params['from_object_type']."' AND
                                from_object_id=".$params['from_object_id']." AND
                                to_object_type='".$params['to_object_type']."' AND
                                to_object_id=".$params['to_object_id'];
                echo $query;
                $res   = mysql_query ($query);
                logDBError (__FILE__, __LINE__, mysql_error(), $query);
                if (mysql_error() != '') {
                    ?>
                        <script language="javascript">
                            alert ('reference could not be deleted');
                        </script>
                    <?php    
                }    
            }
        }

      /**
        * validates new or updated folder.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @param        boolean if strict, folder must have same group as parent folder
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function folder_validation ($type, $strict = false) {
            global $db_hdl, $logger;
            
            // --- validation ---------------------------------------
            $group = $this->entry['use_group']->get();
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."gacl_aro_groups WHERE id=".$group;
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

			$row   = mysql_fetch_array($res);
            if ($row[0] == 0) { // group must exist
                $this->error_msg = translate('internal errror group does not exist');
                return "failure";            
			}
			// if parent exists, folder must have the same group
			if ($strict && $this->entry['parent']->get() > 0) {
                $metainfo = $this->get_metainfo($type, $this->entry['parent']->get());
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
        }
        
      /**
        * gets id of parents folder, or null if not existent
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @param        boolean if strict, folder must have same group as parent folder
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function get_parent ($table, $column, $entry_id) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "SELECT parent FROM ".TABLE_PREFIX."$table WHERE $column=".$entry_id;
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            $row   = mysql_fetch_array($res);
            
            return $row[0];

        }
        
       /**
        * 
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         validation and security
        * @since        0.5.1
        * @version      0.5.2
        */
        function copyFromDG (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

			$query = "
					INSERT INTO ".TABLE_PREFIX."datagrids 
						(mandator_id, name, description, searchButtonCol)
					SELECT ".$_SESSION['mandator'].", name, 'created by l4w' AS description, searchButtonCol
					FROM ".TABLE_PREFIX."datagrids
					WHERE datagrid_id=".$params['copy_columns_from_dg']."
				";
			
			$myres  = mysql_query ($query); // AdoDB query did not work!
			$new_dg = mysql_insert_id();
			 
			$query  = "
					INSERT INTO ".TABLE_PREFIX."datagrid_columns 
						(datagrid_id, column_id,column_identifier,column_name, description,
						 width, visible, is_primary, order_nr, searchable)
					SELECT ".$new_dg.", column_id, column_identifier,column_name, description,
						 width, visible, is_primary, order_nr, searchable
					FROM ".TABLE_PREFIX."datagrid_columns 
					WHERE datagrid_id=".$params['copy_columns_from_dg']."
				";
			$myres  = mysql_query ($query);
			echo mysql_error();
			                    
            return "success";
        }
        
        
       /**
        * 
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         validation and security
        * @since        0.5.2
        * @version      0.5.2
        */
        function validateModel () {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validate all fields in entries -----------------
            $ok = true;
            reset($this->entry); 
			while (list($key, $val) = each($this->entry)) {
			    //echo $key.", "; 
			    //echo "(".var_dump($this->entry[$key]->data).") ";
            	$result = $this->entry[$key]->get();
            	//echo $result.", error: ";
            	$error  = $this->entry[$key]->error;
            	//echo $error."<br>";
            	if ($error != '') {
	            	$this->error_msg   .= translate ($key).": ".translate ($error)."<br>";	
	            	$this->entry[$key]->class = "alert";
           			$ok = false;
            	}	
            }
            
            return $ok;
        }
    
    }   

?>