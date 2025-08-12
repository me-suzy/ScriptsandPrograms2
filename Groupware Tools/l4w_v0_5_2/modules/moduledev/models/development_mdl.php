<?php

   /**
    * Model for handling documents. This file contains the model of the model-view-controller pattern used to 
    * implement the notes functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * notes.
    * Notes are a basic type of information implemented in lead4web and provide a <b>headline</b>
    * and a <b>content field</b> only.<br>
    * Nevertheless, they can be <b>viewed, changed, organized</b> in different ways and even <b>synchronized</b>.
    * As a part of leads4web, notes are treated as <b>shareable pieces of information</b> which belong to extacly <b>one
    * group</b> and have certain <b>access rights</b>. When a note gets <b>attached</b> to other pieces of information (like contacts or documents),
    * these access rights (and the group) are <b>inherited</b> from the parent.<br> 
    * A note can belong to zero or more <b>collections</b> (which is basically a gathering of various pieces of information of any kind)
    * and can <b>reference</b> (or be referenced by) other pieces of information.<br>
    * Notes can be organized in <b>folders</b>, but these folders do not pass their group or access rights to their content.  
    *
    * There are extended models adding due-dates, priorites and so on to the basic notes model.
    * All this kind of information gets stored in a table called 'memos'.
    *
    * @version      $Id: development_mdl.php,v 1.7 2005/08/01 14:55:13 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      notes
    */    
    class development_model extends l4w_model {
         
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
        var $entry_type      = 'note';     

       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @todo         think about getting rid of smarty and authClass as parameters
        * @since        0.4.4
        * @version      0.4.4
        */
        function development_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array ("add_module",
                       "create_module",
                       "run_sql",
                       "unset_current_view"   // unset current view in SESSION (unlock)
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        * validates new or updated note.
        *
        * Validates if assigned group exists and runs the validation rules defined in corresponding file fields_definition.inc.php.
        * The parent folder (if existent) may not have the same group or access rights.
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @see          folder_validation
        * @since        0.4.5
        * @version      0.4.5
        */
        function module_validation () {
            //global $easy;
            
            // --- validation ---------------------------------------
            
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
        * .
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.4.7
        * @version      0.4.7
        */
        function get_dir_entries ($path, $recursive = false) {
            
            $entries = array ();
            
            if (is_dir($path)) { 
               if ($dh = opendir($path)) { 
                   while (($file = readdir($dh)) !== false) {
                        //$this->info_msg .= "Debug: ".$file."<br>";
                        if ($file == "." || $file == "..") 
                            continue; 
                        //if (is_dir($file)) { some bug prevents me from using this function... ;(
                        $is_file = false;
                        if ($fh = @fopen($path."/".$file, "rb")) {
                            $is_file = true;
                            fclose($fh);
                        }
                        if (!$is_file) {
                            if ($recursive)
                                $entries = array_merge($entries, $this->get_dir_entries($path."/".$file, $recursive));            
                        }                  
                        else {   
                            $this->info_msg .= "Found: ".$path."/".$file."<br>";
                            $entries[] = $path."/".$file;
                        }
                   } 
                   closedir($dh); 
               } 
            } 
            
            return $entries;
    
        }     
              
      /**
        * .
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.4.7
        * @version      0.4.7
        */
        function copyAndReplace ($from, $to, $tree_id = 0, $ordernr = 0) {
            global $db_name;
            
            if (!$content = @file_get_contents($from)) {
                $this->info_msg .= translate ('file maybe empty').": ".$from."<br>";
                return true;
            }
            (trim ($this->entry['schema']->get()) == '') ? $use_schema = $db_name : $use_schema = $this->entry['schema']->get();
            
            $content = str_replace ('###name###',           $this->entry['name']->get(),      $content); 
            $content = str_replace ('###author###',         $this->entry['author']->get(),    $content); 
            $content = str_replace ('###package###',        $this->entry['package']->get(),   $content); 
            $content = str_replace ('###copyright###',      $this->entry['copyright']->get(), $content); 
            $content = str_replace ('###id###',             $tree_id,      $content); 
            $content = str_replace ('###ordernr###',        $ordernr,      $content); 
            $content = str_replace ('###version_main###',   $this->entry['version_main']->get(),      $content); 
            $content = str_replace ('###version_sub###',    $this->entry['version_sub']->get(),      $content); 
            $content = str_replace ('###version_detail###', $this->entry['version_detail']->get(),      $content); 
            $content = str_replace ('###scheme###',         $use_schema,   $content); 
            $content = str_replace ('###table_prefix###',   TABLE_PREFIX,  $content); 
             
            if (!$fh = fopen ($to, "wb")) {
                $this->error_msg .= "Error with file ".$to."<br>";    
                return false;
            }
            fwrite ($fh, $content);
            fclose($fh);    
            
            return true;    
        }     

       /**
        * .
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.5.0
        * @version      0.5.2
        */
        function create_module ($params) {
            global $db_hdl, $logger;
                                    
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->module_validation();
            if ($validation != "success") return $validation;
            
            // --- init ---------------------------------------------
            $source_dir = "templates/".$this->entry['type']->get();
            $target_dir = "../".$this->entry['name']->get();
            //if ($this->entry['version']->get() != '')
            $target_dir .= "_".$this->entry['version_main']->get().".".
                               $this->entry['version_sub']->get().".".
                               $this->entry['version_detail']->get();

            //echo $target_dir;
            if (file_exists($target_dir)) {
                $this->error_msg .= translate ('target exists'). " ($target_dir)";    
                return "failure";
            }    
            if (!file_exists($source_dir)) {
                $this->error_msg .= translate ('source dir does not exist'). " ($source_dir)";    
                return "failure";
            }    
            
            // --- get first level files (not recursive) ------------
            $files = $this->get_dir_entries ($source_dir, false);
            if (!mkdir($target_dir, 0770)) {
                $this->error_msg .= translate ('creating directory failed')." ($target_dir)";
                return "failure";    
            }    
            foreach ($files AS $key => $file) {
                $this->info_msg .= "Copy $file to ".$target_dir."/".basename($file)."<br>";
                if (!$this->copyAndReplace($file, $target_dir."/".basename($file))) {
                    $this->error_msg .= translate ('creating file failed')." (".$target_dir."/".basename($file).")";
                    return "failure";    
                }   
            }    
            
            // --- get controler directory (not recursive) ----------
            $use_dir = $target_dir."/controllers";
            $files   = $this->get_dir_entries ($source_dir."/controllers", false);
            if (!mkdir($use_dir, 0770)) {
                $this->error_msg .= translate ('creating directory failed')." ($use_dir)";
                return "failure";    
            }    
            foreach ($files AS $key => $file) {
                $target_file = $use_dir."/".$this->entry['name']->get()."_ctrl.php";
                $this->info_msg .= "Copy $file to ".$target_file."<br>";
                if (!$this->copyAndReplace($file, $target_file)) {
                    $this->error_msg .= translate ('creating file failed')." ($target_file)";
                    return "failure";    
                }   
            }    
            
            // --- get model directory (not recursive) ----------
            $use_dir = $target_dir."/models";
            $files   = $this->get_dir_entries ($source_dir."/models", false);
            if (!mkdir($use_dir, 0770)) {
                $this->error_msg .= translate ('creating directory failed')." ($use_dir)";
                return "failure";    
            }    
            foreach ($files AS $key => $file) {
                if (basename($file) == "model_template.php")
                    $target_file = $use_dir."/".$this->entry['name']->get()."_mdl.php";
                else 
                    $target_file = $use_dir."/".basename ($file);
                if (!$this->copyAndReplace($file, $target_file)) {
                    $this->error_msg .= translate ('creating file failed')." ($target_file)";
                    return "failure";    
                }   
            }    

            // --- get views directory (not recursive) ----------
            $use_dir = $target_dir."/views";
            $files   = $this->get_dir_entries ($source_dir."/views", false);
            //var_dump($files);
            if (!mkdir($use_dir, 0770)) {
                $this->error_msg .= translate ('creating directory failed')." ($use_dir)";
                return "failure";    
            }    
            foreach ($files AS $key => $file) {
                if (basename($file) == "entry_template.tpl")
                    $target_file = $use_dir."/".$this->entry['name']->get().".tpl";
                else 
                    $target_file = $use_dir."/".basename ($file);
                if (!$this->copyAndReplace($file, $target_file)) {
                    $this->error_msg .= translate ('creating file failed')." ($target_file)";
                    return "failure";    
                }
            }    

            // --- sql ----------------------------------------------
            $use_dir = $target_dir."/db";
            $files   = $this->get_dir_entries ($source_dir."/db", false);
            if (!mkdir($use_dir, 0770)) {
                $this->error_msg .= translate ('creating directory failed')." ($use_dir)";
                return "failure";    
            }    
            $tree_query     = "SELECT MAX(id) FROM ".TABLE_PREFIX."tree";
            if (!$tree_res  = $this->ExecuteQuery ($tree_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $tree_row       = mysql_fetch_array ($tree_res);

            $tree2_query    = "SELECT MAX(order_nr) FROM ".TABLE_PREFIX."tree WHERE parent=1";
            if (!$tree2_res = $this->ExecuteQuery ($tree2_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $tree2_row      = mysql_fetch_array ($tree2_res);
            
            foreach ($files AS $key => $file) {
                $target_file = $use_dir."/".basename($file);
                if (!$this->copyAndReplace(
                        $file, 
                        $target_file, 
                        ($tree_row[0]+1), $tree2_row[0], 
                        $this->entry['version_main']->get().".".$this->entry['version_sub']->get().".".$this->entry['version_detail']->get())) {
                    $this->error_msg .= translate ('creating file failed')." ($target_file)";
                    return "failure";    
                }   
            }
            
            $sql  = @file_get_contents($use_dir."/create.sql");
            $sql .= "\n\n";
            $sql .= @file_get_contents($use_dir."/insert.sql");

            $this->entry['sql']->set ($sql);
            
            // --- add to components --------------------------------
            $comp_query     = "
                    INSERT INTO ".TABLE_PREFIX."components
                        (module_name, module_type, version_main, version_sub, version_detail, enabled) 
                    VALUES
                        ('".$this->entry['name']->get()."',          'extension', 
                        '".$this->entry['version_main']->get()."',
                        '".$this->entry['version_sub']->get()."',
                        '".$this->entry['version_detail']->get()."',
                        '1')
                ";
            if (!$this->ExecuteQuery ($comp_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            			
            	
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'new '.$this->entry_type, 'system',$this->inserted_entry_id);
            			
            return "success";
        }
               
       /**
        * .
        *
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @todo         add info message if group or access differs from parent
        * @todo         check ref_object_* handling (still used?)
        * @since        0.4.7
        * @version      0.4.7
        */
        function run_sqls ($params) {
            global $db_hdl, $logger;
                                    
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $stmts = explode (";", $this->entry['sql']->get());
    		$this->info_msg .= "<br>Running SQL: ";
    		foreach ($stmts AS $key => $stmt) {
    			if (trim($stmt) != '') {
    			    $stmt = str_replace ('\"', '"',  $stmt);
    			    $stmt = str_replace ("\\'", "'", $stmt);
    				$this->info_msg .= ".";
    				echo $stmt."<br>";
    				$res = mysql_query ($stmt);
    				echo mysql_error();
    			}			
    		}
    		//echo "<br>";	
            				
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'new '.$this->entry_type, 'system',$this->inserted_entry_id);
            			
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
        
    }   

?>