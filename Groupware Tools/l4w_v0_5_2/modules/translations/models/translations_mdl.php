<?php

   /**
    * Model template. 
    *
    * @author       Carsten Gräf
    * @copyright    evandor media GmbH
    * @package      translations
    */
            
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    *
    * @version      $Id: translations_mdl.php,v 1.11 2005/08/01 14:55:14 carsten Exp $
    * @author       Carsten Gräf
    * @copyright    evandor media GmbH
    * @package      translations
    */    
    class translations_model extends l4w_model {
         
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
        var $entry_type      = 'translations';     

       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @todo         think about getting rid of smarty and authClass as parameters
        * @since        0
        * @version      0
        */
        function translations_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("main_view", null,
                array ("main_view",
                       "create_language_file",
                       "load_existing_language", 
                       "edit_language",
                       "edit_text",
                       "load_lang_view",
                       "new_lang_view1",
                       "generate_language",
                       "remove_language",
                       "set_text",
                       "test_language",
                       "update_language"
                ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0
        * @version      0
        */
        function generate_language ($params, $update = false) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
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
            // don't use existing language
            $query = "
                        SELECT COUNT(*) FROM ".TABLE_PREFIX."languages WHERE language='".strtolower($this->entry['language_name']->get())."'
                     ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            $row      = mysql_fetch_array($res);
            if ($row[0] > 0) {
                $this->error_msg .= translate ('language name exists')."\n";
                return "failure";    
            }    

            // --- alright, let's go --------------------------------
            $query = "
		          SELECT filename FROM ".TABLE_PREFIX."languages
		          WHERE lang_id=".$params['use_lang'];

            $offset = "../../";
            
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            $row      = mysql_fetch_array($res);
		    $template = parse_ini_file($offset."lang/lang_".$row['filename'].".txt");
            
		    // --- get language id to use ------------------------------
		    /*$sel_query    = "SELECT max(loaded_in_db)+1 FROM ".TABLE_PREFIX."languages";
		    if (!$sel_res = $this->ExecuteQuery ($sel_query, 'mysql_error')) return "failure";
		    $sel_row      = mysql_fetch_array($sel_res);*/
		    
		    if ($update) {
    		    // --- update language database ------------------------
                $query = "UPDATE ".TABLE_PREFIX."languages
                          SET loaded_in_db='1'
                          WHERE lang_id=".$params['use_lang'];
                $logger->log ($query, 7);
                if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
                $ref_lang_id = $params['use_lang'];
		    }
		    else {
    		    // --- insert into language database --------------------
                $query = "INSERT INTO ".TABLE_PREFIX."languages (
                                    language,
                                    aktiv,
                                    filename,
                                    loaded_in_db,
                                    path
                                  ) VALUES (
                                    '".strtolower($this->entry['language_name']->get())."',
                                    '0',
                                    '".strtolower($this->entry['language_name']->get())."',
                                    '1',
                                    '".$params['path']."'
                                   )";
                $logger->log ($query, 7);
                if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
                $ref_lang_id = mysql_insert_id();
		    }   
		     
            
            // --- add metainfo -------------------------------------
            foreach ($template AS $key => $value) {
                
                $lang_query = "
                    INSERT INTO ".TABLE_PREFIX."translations (lang_id, mykey, translation)
                    VALUES (
                        '".$ref_lang_id."','".mysql_escape_string($key)."', '".mysql_escape_string(htmlentities($value))."'
                    )
                ";
                mysql_query ($lang_query);
                $this->error_msg .= mysql_error();
            }
            
            return "success";
        }
             
      /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function load_language ($params) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validate all fields in entries -----------------
            assert ('$params["load_lang"] > 0');
            
            $use_params = array (
                            "use_lang" => $params['load_lang'],
                            "path"     => ""
                          );
                 
            return $this->generate_language($use_params, true);
        }

       /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0
        * @version      0
        */
        function update_language ($params) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- get max id from translations ---------------------
            $query = "SELECT max(id) FROM ".TABLE_PREFIX."translations";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            $row      = mysql_fetch_array ($res);
            $max_id   = $row[0];
                      
            // --- iterate parameters -------------------------------
            $updated = 0;
            for ($i=0; $i <= $max_id; $i++) {
                $ident = 'lang_'.$i;
                if (isset ($params[$ident])) {
                    $lang_query = "
                        UPDATE ".TABLE_PREFIX."translations 
                        SET 
                            translation='".mysql_escape_string(htmlentities($params[$ident]))."'
                        WHERE id=$i AND lang_id=".$this->entry['lang_id']->get()."
                    ";
                    mysql_query ($lang_query);
                    $this->error_msg .= mysql_error();
                    if (mysql_error() != "") {
                        echo $lang_query;
                        break;
                    }
                    $updated += mysql_affected_rows();
                }        
            }         
            
            $this->info_msg .= translate ('updated entries').": ".$updated."\n";
            
            return "success";
        }
             
      /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function create_language_file ($testing = false) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $lang_id = $this->entry['lang_id']->get();
            
            // --- get language info --------------------------------
            $query = "
                        SELECT * 
                        FROM ".TABLE_PREFIX."languages 
                        WHERE loaded_in_db!='0' AND 
                              lang_id=".$lang_id;
            
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            $row      = mysql_fetch_array ($res);
            assert ('$row["language"] != ""');
            
            $offset   = "../../";
            $filename = $offset."lang/lang_".$row['language'].".txt";
            $filetext = '';
            
            // --- testing only? -------------------------------------
            if ($testing) {
                $filename .= ".tmp";    
            }    
                       
            $query = "
                        SELECT * 
                        FROM ".TABLE_PREFIX."translations 
                        WHERE lang_id=$lang_id
                        ORDER BY mykey
                     ";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            while ($trans_row = mysql_fetch_array($res)) {
                $text = str_replace ("&amp;","&",$trans_row['translation']);
                $text = str_replace ("&gt;", ">", $text);
                $text = str_replace ("&lt;", "<", $text);
                $filetext .= $trans_row['mykey']." = \"".$text."\"\n";    
            }    
            
            // --- try to backup existing file ---
            $backup = $filename.".bak_".date("YmdHis");
            if ($fh = @fopen ($filename, "rb")) {
                if (!$fh2 = fopen ($backup, "wb")) {
                    $this->error_msg .= translate ('error opening file for backup: '.$backup);
                    return "failure";    
                }    
                $contents = fread($fh, filesize($filename));
                fwrite ($fh2, $contents);
                fclose ($fh);    
                fclose ($fh2);
                $this->info_msg .= translate ('created backup file').": ".$backup;
            }    
            
            //echo $filetext;
            if (!$fh = fopen ($filename, "wb")) {
                $this->error_msg .= translate ('opening file failed').": ".$filename;
                return "failure";
            }
            fwrite ($fh, $filetext);
            fclose ($fh);
            
            if ($testing) {
                // --- try to parse file ----------------------------
                if (!$done = parse_ini_file($filename)) {
                    $this->error_msg .= translate ('could not parse file').": ".$filename."<br>";
                    return "failure";    
                }    
                // --- cleanup --------------------------------------
                if (!unlink ($filename)) {
                    $this->info_msg .= translate ('delete failed for file').": ".$filename."<br>";    
                }    
                $this->info_msg .= translate ('language file ok')."<br>";
                return "success";    
            }    
            
            $query = "UPDATE ".TABLE_PREFIX."languages SET aktiv='1' WHERE lang_id='".$lang_id."'";
            if (!$this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            
            return "success";
        }
              
       /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function remove_language ($params) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            $clear_query = "DELETE FROM ".TABLE_PREFIX."translations WHERE lang_id=".$params['lang_id'];
            //$clear_query = "TRUNCATE ".TABLE_PREFIX."translations";
            mysql_query ($clear_query);
            $this->error_msg .= mysql_error();
            
            $upd_query = "UPDATE ".TABLE_PREFIX."languages SET loaded_in_db='0' WHERE lang_id=".$params['lang_id'];
            //$del_query   = "DELETE FROM ".TABLE_PREFIX."languages WHERE loaded_in_db!='0'";
            mysql_query ($upd_query);
            $this->error_msg .= mysql_error();
            
            return "success";
        }
             
      /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0
        * @version      0
        */
        function get_translations () {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "
                        SELECT * FROM ".TABLE_PREFIX."translations 
                        WHERE lang_id=".$this->entry['lang_id']->get()."
                        ORDER BY id
                     ";
            $res   = mysql_query($query);
            $this->error_msg .= mysql_error();
            
            $this->entry['translations']->set ($res);
            
            return "success";
        }
                             
      /**
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_loaded_language () {
            global $logger;
die ("depreciated");
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "SELECT * FROM ".TABLE_PREFIX."languages WHERE loaded_in_db!='0'";
            $res   = mysql_query($query);
            $this->error_msg .= mysql_error();
            $row   = mysql_fetch_array($res);
            
            $this->entry['language_name']->set ($row['language']);
                        
            return "success";
        }

      /**
        * updates entry
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success or apply on success, otherwise failure
        * @todo         check owner management
        * @todo         divide monster method
        * @since        0.5.0
        * @version      0.5.0
        */
        function set_text (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
			if ($this->entry['mykey']->get() == "false" ||
			    $this->entry['mykey']->get() == "true") {
				$this->error_msg = translate ("key must not be true or false");
				return "failure";    	
			}
            
            $query = "SELECT lang_id FROM ".TABLE_PREFIX."languages WHERE loaded_in_db != '0'";
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            
            while ($row = mysql_fetch_array($res)) {
            
                $tmp = "lang_".$row['lang_id'];
                if (isset ($tmp)) {
                    $replace_query = "REPLACE INTO ".TABLE_PREFIX."translations 
                                      SET translation='".mysql_escape_string(htmlentities($_REQUEST[$tmp]))."',
                                      mykey='".$this->entry['mykey']->get()."',
                                      lang_id=".$row['lang_id'];

                    if (!$this->ExecuteQuery ($replace_query, 'mysql_error')) return "failure";
                }
            }
            
            if (isset ($params['auto_generate_files']) && $params['auto_generate_files'] == "on")
            	return "generate";
            	 
            return "success";
            
        }
            
            

      /**
        * deletes entry
        * 
        * @access       public
        * @param        array   holds request variables
        * @param        boolean if set to true, the parents meta values (group and access rights) are
                                used to determine if the user has enough privileges to delete the entry. In
                                this case, the parameter "parent" must be set.
        * @param        boolean when use_inheritance is set to true, you have to pass the parents type
        * @return       string  success on success, otherwise failure
        * @since        0
        * @version      0
        * @todo         may not delete folders with children elements
        */
        function delete_entry ($params, $use_inheritance = false, $parent_type = NULL) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_translations_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['entry_id']));
            
            
            if ($use_inheritance) {
                $old_meta_values = get_entries_for_primary_key(
                                           "metainfo", array ("object_type" => $parent_type,
                                                              "object_id"   => $params['parent']));

            }
            else {          
                $old_meta_values = get_entries_for_primary_key(
                                           "metainfo", array ("object_type" => $this->entry_type,
                                                              "object_id"   => $params['entry_id']));
            }
                /*$old_meta_values = get_entries_for_primary_key(
                                            "metainfo", array ("object_type" => $this->entry_type,
                                                  "object_id"   => $params['entry_id']));*/
                
            // --- sufficient rights ? ------------------------------
            if (!user_may_delete ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'deleted '.$this->entry_type, 'system', $params['entry_id']);

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM memos WHERE memo_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error')) return "failure";
            
            // --- delete metainfo ----------------------------------
            $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='".$this->entry_type."' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error')) return "failure";
                        
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";

            // --- delete any refering entries in table refering ----
            $meta_query = "DELETE FROM refering WHERE to_object_type='".$this->entry_type."' AND to_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error')) return "failure";
            $meta_query = "DELETE FROM refering WHERE from_object_type='".$this->entry_type."' AND from_object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error')) return "failure";
            
            // --- delete any attachments ----------------------------
            $att_query = "SELECT memo_id FROM memos WHERE parent='".$params['entry_id']."'";
            if (!$att_res = $this->ExecuteQuery($att_query, 'mysql_error')) return "failure";
            while ($att_row = mysql_fetch_array($att_res)) {
                $del_query = "DELETE FROM memos WHERE memo_id='".$att_row['memo_id']."'";
                if (!$res = $this->ExecuteQuery ($del_query, 'mysql_error')) return "failure";
            
                // --- delete metainfo ----------------------------------
                $meta_query = "DELETE FROM ".TABLE_PREFIX."metainfo WHERE object_type='".$this->entry_type."' AND object_id='".$att_row['memo_id']."'";
                if (!$res = $this->ExecuteQuery ($meta_query, 'mysql_error')) return "failure";
            }
                            
            // --- update any references in the sync table ----------
            $sync_query = "UPDATE sync SET status='deleted locally' WHERE object_type='".$this->entry_type."' AND object_id='".$params['entry_id']."'";
            if (!$res = $this->ExecuteQuery ($sync_query, 'mysql_error')) return "failure";
            
            return "success";
        }    
        
        
      /**
        * deletes a list of entries
        *
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @todo         add public function which takes "real" array with ids
        * @since        0
        * @version      0
        */
        function delete_entries (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $del_list = array ();
            foreach ($params AS $key => $value) {
                if (substr ($key, 0,5) == $this->entry_type."_") {
                    $del_list[] = (int)substr ($key,5);    
                }    
            }    
            
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
            
            // --- delete entries -----------------------------------
            foreach ($del_list AS $key => $del_id) {
                $wrapper = array ("entry_id" => $del_id);
                $result  = $this->delete_entry($wrapper);    
                if ($result == "failure") {
                    if ($this->info_msg != "")
                        $info_msg  .= $this->info_msg."<br>";
                    if ($this->error_msg != "")
                        $error_msg .= $this->error_msg."<br>";    
                    $success    = false;
                }    
            }    
            
            // --- success ? ----------------------------------------
            if ($success) 
                return "success";
            
            $this->error_msg = $error_msg;
            $this->info_msg  = $info_msg;
            
            return "failure";
        }

      /**
        * get list of parents names
        *
        * @access       public
        * @param        int id of node where to start
        * @return       array holding parents names and ids
        * @since        0.4.7
        * @version      0.4.7
        */
        function getParentChain ($start_id, $chain = null) {
            global $db_hdl, $logger;

            $sql = "SELECT memo_id, headline, parent 
                    FROM memos
                    WHERE memo_id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error')) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['headline'];
                $chain[$cnt]['id']     = $row['memo_id'];                
                $chain += $this->getParentChain ($row['parent'], $chain);
            }	
            return $chain;
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
                $query = "
                    SELECT
                        memo_id,
						headline,
						content,
						creator,
						CONCAT(".TABLE_PREFIX."users.firstname,' ',".TABLE_PREFIX."users.lastname) AS owner,
						".TABLE_PREFIX."gacl_aro_groups.name AS grp,
						created,
						last_changer,
						last_change,
						access_level,  
                        is_dir,
                        ".TABLE_PREFIX."metainfo.owner AS owner_id,
                        ".TABLE_PREFIX."metainfo.grp   AS group_id,
                        '#000000'      AS color
		            FROM ".TABLE_PREFIX."memos 
                    LEFT JOIN ".TABLE_PREFIX."metainfo ON ".TABLE_PREFIX."metainfo.object_id=".TABLE_PREFIX."memos.memo_id
                    LEFT JOIN ".TABLE_PREFIX."gacl_aro_groups ON ".TABLE_PREFIX."metainfo.grp=".TABLE_PREFIX."gacl_aro_groups.id
                    LEFT JOIN ".TABLE_PREFIX."users ON ".TABLE_PREFIX."users.id=".TABLE_PREFIX."metainfo.owner
                    WHERE ".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."' AND
                    	  parent=".$this->entry['parent']->get();
            }  
            if ($_SESSION['use_my_group'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.grp=".$_SESSION['use_my_group'];       
            else {
                //$query .= " AND ".get_all_groups_or_statement ($_SESSION['user_id']);    
            }    
            
            if ($_SESSION['use_my_state'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.state=".$_SESSION['use_my_state'];       
            
            if ($_SESSION['use_my_owner'] > 0)
                $query .= " AND ".TABLE_PREFIX."metainfo.owner=".$_SESSION['use_my_owner'];       

            
            $query .= "
                        AND (
                        	  ".TABLE_PREFIX."metainfo.owner=".$_SESSION['user_id']." 
                			OR
		                      (".get_all_groups_or_statement ($_SESSION['user_id'])." AND ".TABLE_PREFIX."metainfo.access_level LIKE '____r_____') 
			                OR
                     		   ".TABLE_PREFIX."metainfo.access_level LIKE '_______r__'	
                        )
            ";    
			$query .= "ORDER BY is_dir DESC";

			$this->dg = new datagrid (20, $this->entry_type."s", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.4.5
        * @version      0.4.5
        */
        /*function get_text (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            
            return "success";
        }*/
            
       /**
        * Shows single entry.
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
        /*function show_entry (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM memos
                LEFT JOIN metainfo ON ".TABLE_PREFIX."metainfo.object_id=memos.memo_id
                WHERE (".TABLE_PREFIX."metainfo.object_type='".$this->entry_type."' AND memos.memo_id=".$params['entry_id'].")";

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error')) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);

            // --- sufficient rights ? -----------------------------
            if (!user_may_read ($row['owner'],$row['grp'],$row['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
            
            // --- group = 0 means: entry is attachement (inheritance!)
            if ($row['grp'] == 0) {
                $parents_values = get_entries_for_primary_key(
                                           "memos", array ("memo_id" => $row['parent']));

                $this->entry['parents_headline']->set($parents_values['headline']);    
            }    
            
                        
            // --- adjust some values -------------------------------
            $this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);

            // --- is locked ? --------------------------------------
            list ($lock_user, $lock_timestamp) = $this->lockedBy($this->entry_type, $this->entry['memo_id']->get());
            if ($lock_user > 0) {
                if ($lock_user != $_SESSION['user_id']) {
                    $this->error_msg .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
                    $this->entry['locked']->set (1); // 1 = true;
                }
                else
                    $this->info_msg  .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($lock_user);    
            }
            else {
                // --- lock contact -------------------------------------
                //$this->info_msg .= translate ($this->entry_type)." ".translate ('locked by')." ".get_username_by_user_id ($_SESSION['user_id']);
                $this->lockEntry ($this->entry_type, $this->entry['memo_id']->get());
            }
           
            // --- if entry is a folder, adjust view ----------------
            if ((bool)$row['is_dir']) {
                return "redirect";    
            }    

            return "success";
        }*/

      /**
        * moves entry
        *
        * asserts params entry id and move_to are given and greater or equal (entry_id) than zero. An entry cannot 
        * be moved onto itself, and the taret must be a folder. On success, an event (changed translations) is fired.
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function move_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- assertions ---------------------------------------
            assert ('(int)$params["entry_id"] > 0');
            assert ('(int)$params["move_to"] >= 0');
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- history, get_old values -----------------------------
            $old_translations_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['entry_id']));
            $old_meta_values    = get_entries_for_primary_key(
                                       "metainfo", array ("object_type" => $this->entry_type,
                                                          "object_id"   => $params['entry_id']));
            
            
            // --- validation ---------------------------------------
            // target must be folder
            $target_values = get_entries_for_primary_key (
                                       "memos", array ("memo_id" => $params['move_to']));   
                                                                                            
            if ($params['move_to'] > 0 && !(bool)$target_values['is_dir']) {
                $this->error_msg = translate ('move target must be a folder');
                return false;    
            }    
                                                        
            // --- sufficient rights ? ------------------------------
            if (!user_may_edit ($old_meta_values['owner'],$old_meta_values['grp'],$old_meta_values['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            // --- entry can be moved? ------------------------------
            if ($params['entry_id'] == $params['move_to']) {
                $this->info_msg .= translate ('cannot move item to itself');
                return "failure";
            }    
                       
            // --- fire event ---------------------------------------
            fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $params['entry_id']);

            // --- update entry -------------------------------------
            $update_query = "UPDATE memos SET parent=".$params['move_to']." WHERE memo_id='".$params['entry_id']."'";
            if (!$this->ExecuteQuery ($update_query, 'mysql_error')) return "failure";
                                    
            // --- delete quicklinks --------------------------------
            //$ql_query = "DELETE FROM quicklinks WHERE object_type='contact' AND object_id='".$params['entry_id']."'";
            //if (!$res = $this->ExecuteQuery ($ql_query, 'mysql_error')) return "failure";
                 
            // --- update history -----------------------------------
            update_history ($this->entry_type,                             // identifier for history table
                            "memos",                         // table
                            $this->entry['memo_id']->get(),  // object_id
                            array ("memo_id" => $this->entry['memo_id']->get()), 
                            $old_entry_values);
        
            return "success";
        }            
        
       /**
        * moves entries to other folder.
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @todo         public funciton with "real" array of ids
        * @since        0.4.7
        * @version      0.4.7
        */
        function moveEntries (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $move_list = array ();

            foreach ($params AS $key => $value) {
                if (substr ($key, 0,5) == $this->entry_type."_") {
                    $move_list[] = (int)substr ($key,5);    
                }    
            }    
                     
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
            
            // --- delete entries -----------------------------------
            foreach ($move_list AS $key => $move_id) {
                $wrapper = array ("entry_id" => $move_id, "move_to" => $params['move_to']);
                $result  = $this->move_entry($wrapper);    
                if ($result == "failure") {
                    if ($this->info_msg != "")
                        $info_msg  .= $this->info_msg."<br>";
                    if ($this->error_msg != "")
                        $error_msg .= $this->error_msg."<br>";    
                    $success    = false;
                }    
            }    
            
            // --- success ? ----------------------------------------
            if ($success) 
                return "success";
            
            $this->error_msg = $error_msg;
            $this->info_msg  = $info_msg;
            
            return "failure";
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
            
            $sql = "SELECT headline, is_dir
                    FROM memos
                    WHERE memo_id='$folder_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error')) return "";
            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_array($res);
            assert ('(bool)$row["is_dir"] == true');
            return $row['headline'];
        }

       /**
        * 
        * @access       public
        * @param        int    id of folder
        * @return       string name of folder
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_languages () {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $sql = "SELECT *
                    FROM ".TABLE_PREFIX."languages
                    WHERE aktiv != '0'
                   ";
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error')) return "";
            assert ('mysql_num_rows($res) > 0');

            $this->entry['languages']->set ($res);

            return "success";
        }


        
       /**
        * gets folders.
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2005
        * @param        array  holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function getFolders (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $nodes = "
                var TREE_NODES = [";
            $nodes .= $this->add_nodes (0,20);    
            $nodes .= "    
                ];
            ";

            $this->entry['foldernodes']->set ($nodes);

            return "success";
        }
            
 	  /**
        * clears filter
        *
        * 
        * @access       private
        * @param        int    id of folder
        * @return       string name of folder
        * @todo         move to leads4web_model?
        * @since        0.4.7
        * @version      0.4.7
        */
        function clear_filter () {
            global $db_hdl, $logger;
            
            parent::update_filter (array ("my_group" => '',
            							  "my_state" => '',
            							  "my_owner" => ''));
			//var_dump ($_SESSION['easy_datagrid']);
			$_SESSION['easy_datagrid'][$this->entry_type.'s'] = array ();
        }
        
       /**
        * returns a result set containing all installed languages (inactive or active)
        * 
        * @access       private
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_installed_languages () {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "SELECT * FROM ".TABLE_PREFIX."languages ORDER BY language";
            $res   = mysql_query($query);
            $this->error_msg .= mysql_error();
                        
            return $res;
        }

       /**
        * returns a result set containing all loaded languages 
        * 
        * @access       private
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_loaded_languages () {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            $query = "
                SELECT * FROM ".TABLE_PREFIX."languages 
                WHERE loaded_in_db!='0' ORDER BY language";
            $res   = mysql_query($query);
            $this->error_msg .= mysql_error();
                        
            return $res;
        }    
        
         /**
        *
        * get all loaded translations for given key 
        *
        * @access       private
        * @param        string text key
        * @return       result
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_texts ($text) {
            global $db_hdl, $logger;
            
            $sql = "
                    SELECT lang_id, language
                    FROM ".TABLE_PREFIX."languages
                    WHERE loaded_in_db != '0'
                    ORDER BY lang_id
                   ";
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error')) 
                return "";            

            $translations = array ();
            $cnt = 0;
            while ($row = mysql_fetch_array($res)) {
                $sql = "
                        SELECT *
                        FROM ".TABLE_PREFIX."translations
                        WHERE mykey='$text' AND lang_id=".$row['lang_id'];
                
                if (!$res2 = $this->ExecuteQuery ($sql, 'mysql_error')) return "";            
                $row2 = mysql_fetch_array ($res2);
                $translations[$cnt]['translation'] = $row2['translation'];
                $translations[$cnt]['lang_id']     = $row['lang_id'];
                $translations[$cnt]['language']    = $row['language'];
                $cnt++;
            }        
                
            $this->entry['mykey']->set ($text);

            return $translations;
        }  
    }   

?>