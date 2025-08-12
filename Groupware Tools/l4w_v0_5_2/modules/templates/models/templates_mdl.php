<?php

   /**
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      templates
    */
    
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    *
    * @version      $Id: templates_mdl.php,v 1.2 2005/07/31 08:45:16 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      templates
    */    
    class templates_model extends l4w_model {
         
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
        var $entry_type      = 'template';     

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
        function templates_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("show_entries"           
                                  
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
        }

      /**
        * Show entries.
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
        function show_entries (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if (is_null($query)) { // set default query
                $query = "
                    SELECT model_id, name, ts, object_type FROM ".TABLE_PREFIX."serialized_models mi
                    WHERE object_type='".$this->entry['type']->get()."'
                        AND 
			    		    (user_id=".$_SESSION['user_id']."
				    		OR
					    	(".get_all_groups_or_statement ($_SESSION['user_id']).")
						)
						AND mandator_id=".$_SESSION['mandator']."
						AND save_as='template'
                    ORDER BY name
                ";
            }  

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
                       
            return "success";
        }

    }   

?>