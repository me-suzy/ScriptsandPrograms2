<?php

   /**
    * Model for handling datagrids. This file contains the model of the model-view-controller pattern used to 
    * implement the mandators functionality.
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      datagrids
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    * This class implements the model and the business functionality for handling
    * the mandators in leads4web.
    *
    * @version      $Id: datagrids_mdl.php,v 1.8 2005/08/01 14:55:13 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      mandators
    */    
    class datagrids_model extends l4w_model {
         
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
        function datagrids_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("edit_datagrid",
                       "edit_column",
                       "update_datagrid",
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
        /*function entry_validation () {
            
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
        }*/
                             

    
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
        function show_entries ($query = null) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if (is_null($query)) { // set default query
                $query = "
                    SELECT
                        datagrid_id,
                        column_id,
                    	column_identifier,
                    	column_name,
                    	description,
                    	width,
                    	visible,
                    	is_primary,
                    	order_nr,
                    	searchable,
                    	sortable
                    FROM ".TABLE_PREFIX."datagrid_columns
                    WHERE datagrid_id=".$this->entry['datagrid_id']->get()."
                    ORDER BY order_nr
				"; 
            }  
            //echo $query;
			//$query .= "ORDER BY is_dir DESC";

			$this->dg = new datagrid (20, "datagrid_columns", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=2;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $params["datagrid"] = $this->entry['datagrid']->get();
            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                       
            return "success";
        }
                    

      /**
        * Show datagrid.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. 
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.1
        * @version      0.5.2
        */
        function edit_datagrid ($query = null) {
            global $gacl_api, $logger;
            
            // --- set values according to datagrid name ------------
            $this->setBasicModelValues();
			
            // --- security -----------------------------------------
            //if (!$gacl_api->acl_check($this->entry['section']->get(), 'Edit Datagrid', 'Person', $_SESSION['user_id'])) {
            if (!is_superadmin()) {
                $logger->log ('Security validation in '.__CLASS__.'::'.__FUNCTION__, 1);
                die ("security check failed in ".__FILE__);    
            } 
            
            return $this->show_entries();
        }
                     
      /**
        * Show datagrid Column.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. 
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.2
        * @version      0.5.2
        */
		function getDatagridColumn () {
            $query = "
            	SELECT 
            		column_identifier,
            		column_name,
            		description,
            		width,
            		visible,
            		is_primary,
            		order_nr,
            		searchable,
            		sortable
            	FROM ".TABLE_PREFIX."datagrid_columns
            	WHERE datagrid_id=".$this->entry['datagrid_id']->get()." AND
            		  column_id='".$this->entry['column_id']->get()."' 
            ";
            //echo $query;
			if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
			
			// --- sufficient rights ? -----------------------------
            /*if (!user_may_read ($row['owner'],$row['grp'],$row['access_level'])) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }*/

            assert ('mysql_num_rows($res) == 1');
            $row = mysql_fetch_assoc ($res);

            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
                       
            //var_dump ($this->entry);
            //die(); 
            // --- adjust some values -------------------------------
            /*$this->entry['use_group']->set ($row['grp']);
            $this->entry['access']->set    ($row['access_level']);
            $this->entry['state']->set     ($row['state']);
            $this->entry['owner']->set     ($row['owner']);*/
           
            return "success";

		}		

      /**
        * updates column entry
        *
        * If there are any problems, examine
        * model->error_msg and model->info_msg;
        * 
        * @access       public
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.2
        * @version      0.5.2
        */
        function updateDatagrid () {
            global $db_hdl, $logger, $gacl_api;

            //var_dump ($_REQUEST);
            //die();
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- set values according to datagrid name ------------
            $this->setBasicModelValues ();

            // --- permissions --------------------------------------
            //if (!$gacl_api->acl_check($this->entry['section']->get(),'Edit Datagrid','Person',$_SESSION['user_id'])) {
            if (!is_superadmin()) {
                $this->error_msg .= "permission error in ".__FILE__;
                return "failure";
            }

            // --- validate -----------------------------------------
            //if (!$this->validateModel ()) return "failure";
                                                                                                          
            // --- update entries -----------------------------------
            $col_ids = explode ("|", $_REQUEST['col_ids']);
            foreach ($col_ids AS $key => $col_id) {
            	if ($col_id > 0) {
            		$column_name = $_REQUEST['column_name_'.$col_id];
            		$description = $_REQUEST['description_'.$col_id];
            		$width       = $_REQUEST['width_'.$col_id];
            		$visible     = $_REQUEST['visible_'.$col_id];
            		//echo $visible."<br>";
            		($visible == "on") ? $visible = '1' : $visible = '0';
            		
		            $query = "UPDATE ".TABLE_PREFIX."datagrid_columns SET 
    		                      column_name = '$column_name',
    		                      description = '$description',
    		                      width       = '$width',
								  visible     = '$visible'
        		              WHERE datagrid_id = ".$this->entry['datagrid_id']->get()." AND
            		                column_id   = ".$col_id."
                		      ";
                	//echo $query;
		            if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            	}
		    }
		                                                                                           
            return "success";
        }

      /**
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string "success"
        * @todo         check if function is used at all. references contact!
        * @since        0.5.2
        * @version      0.5.2
        */
        function setBasicModelValues() {
        
        	if ($this->entry['datagrid_id']->get() > 0)
	            $query = "
    	        	SELECT datagrid_id, aco_section, name
        	    	FROM ".TABLE_PREFIX."datagrids
            		WHERE datagrid_id=".$this->entry['datagrid_id']->get()."
            	";        	
        	else
	            $query = "
    	        	SELECT datagrid_id, aco_section, name 
        	    	FROM ".TABLE_PREFIX."datagrids
            		WHERE mandator_id=".$_SESSION['mandator']." AND
            			  name='".$this->entry['datagrid']->get()."' 
            	";

	        if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
			$row = mysql_fetch_array($res);
			$this->entry['datagrid_id']->set ($row['datagrid_id']);
			$this->entry['datagrid']->set    ($row['name']);
			$this->entry['section']->set     ($row['aco_section']);
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