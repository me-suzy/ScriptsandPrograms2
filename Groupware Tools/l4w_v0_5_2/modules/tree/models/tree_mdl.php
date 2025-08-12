<?php

  /**
    * $Id: tree_mdl.php,v 1.19 2005/08/01 14:55:14 carsten Exp $
    *
    * Model for supersede. See easy_framework for more information
    * about controlers and models.
    * @package tree
    */
    
  /**
    *
    * Supersede Model Class
    * @package tree
    */
    class tree_model extends easy_model {
         
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
          * Container
          *
          * @access public
          * @var array
          */
        var $files            = null;

        /**
          * Container
          *
          * @access public
          * @var array
          */
        var $replace_array    = null;

        var $order            = 1;
        var $direction        = "ASC";
        var $pagenr           = "";
        var $entries_per_page = null;
        var $dg               = null;
        var $AuthoriseClass   = null;
        var $preAddFolderHook = null;

        var $LANG             = array (); //Array holding translations
        
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
        function tree_model (&$smarty, &$AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", 
                array ("show_entries",           // list of all entries
                       "add_entry",              // add new contact (action)
                       "navigation",
                       "verticaltabs",
                       "show_tree",              // (view)
                       "update_contact",         // (action)
                       "delete_entry",            // (action)
                       "edit_entry",             // view
                       "order_up",
                       "order_down",
                       "update_entry",
                       "update_auth",
                       "show_auth",
                       "use_template",
                       "help"
            ));
                  
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');                                          
                
        }
        
	  /**
        * 
        *
        * 
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @package      easy_framework
        * @since        0.4.3
        * @version      0.5.2
        */       
        function add_entry () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            //list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            if (!((int)$this->entry['parent_id']->get() >= 0)) {
                $this->error_msg = 'internal error in '.__FILE__." ".__LINE__." (".$this->entry['parent_id']->get().")";
                return "failure";
            }
            if (trim($this->entry['name']->get()) == '') {
                $this->error_msg = translate ('no name given');
                return "failure";
            }
            
            // --- get max order_nr ---------------------------------
            $query = "
				SELECT max(order_nr) 
				FROM ".TABLE_PREFIX."tree 
				WHERE parent=".$this->entry['parent_id']->get();
            $res   = mysql_query ($query);
            $row   = mysql_fetch_array ($res);

            // --- add entry ----------------------------------------
            $query = "INSERT INTO ".TABLE_PREFIX."tree (
                        parent,
                        name,
                        link,
                        frame,
                        img,
                        order_nr,
                        subtree_identifier,
                        sign,
                        translate,
                        enabled,
                        authorize
                      )
                      VALUES (
                        '".$this->entry['parent_id']->get()."',
                        '".$this->entry['name']->get()."',
                        '".$this->entry['link']->get()."',
                        '".$this->entry['frame']->get()."',
                        '".$this->entry['img']->get()."',
                        '".($row[0]+1)."',
                        '".$this->entry['subtree_identifier']->get()."',
                        '".$this->entry['sign']->get()."',
                        '".$this->entry['translate']->get()."',
                        '".$this->entry['enabled']->get()."',
                        '".$this->entry['authorize']->get()."'
                      )";

            $logger->log ($query, 7);
            $res = mysql_query ($query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                $this->error_msg = "Error inserting entry to database: ".mysql_error();
                return "failure";
            }    
            $inserted_id = mysql_insert_id();
            $this->entry['id']->set ($inserted_id);
            
            // --- add to history -----------------------------------
            $old_array = array ();                              // entry didn't exist before
            update_history ("tree",                             // identifier for history table
                            "tree",                             // table
                            $this->entry['id']->get(),          // object_id
                            array ("id" => $this->entry['id']->get()), 
                            $old_array); 
             
            return "success";
        }

       /**
        * 
        *
        * 
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @package      easy_framework
        * @since        0.5.2
        * @version      0.5.2
        */       
        function use_template () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

			assert ('$_REQUEST["templates"] > 0');            
            
			$query = "
			    INSERT INTO ".TABLE_PREFIX."tree (parent, name, link, frame, img, sign, order_nr, subtree_identifier,
			    		enabled, authorize, protected, visible_for_guest)
				SELECT ".$this->entry['parent_id']->get()." AS parent, name,link,frame,img, sign, order_nr, subtree_identifier, 
						enabled, authorize, protected, visible_for_guest
				FROM ".TABLE_PREFIX."tree 
				WHERE id=".$_REQUEST["templates"]."
				";
			
				if (!$this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
			
			return "success";
        }
        
        function update_entry (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //$validation = $this->contact_validation();
            //if ($validation != "success") return $validation; 
            
            // --- init ------------------------------------------------
            
            // --- history, get_old values -----------------------------
            $old_entry_values = get_entries_for_primary_key (
                                       "tree", array ("id" => $this->entry['id']->get()));

            // --- update entry ----------------------------------------
            $query = "UPDATE ".TABLE_PREFIX."tree SET 
                                name      = '".$this->entry['name']->get()."',
                                link      = '".$this->entry['link']->get()."',
                                frame     = '".$this->entry['frame']->get()."',
                                img       = '".$this->entry['img']->get()."',
                                sign      = '".$this->entry['sign']->get()."',
                                subtree_identifier= '".$this->entry['subtree_identifier']->get()."',
                                translate = '".$this->entry['translate']->get()."',
                                enabled   = '".$this->entry['enabled']->get()."',
                                authorize = '".$this->entry['authorize']->get()."'
                              WHERE id    = '".$this->entry['id']->get()."'";
            
            $logger->log ($query, 7);
            $res = mysql_query ($query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                $this->error_msg = "Error inserting entry to database: ".mysql_error();
            }    

            // --- update history -----------------------------------
            update_history ("tree",                             // identifier for history table
                            "tree",                             // table
                            $this->entry['id']->get(),          // object_id
                            array ("id" => $this->entry['id']->get()), 
                            $old_entry_values); 
                                       
            return "success";
        }

        function delete_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- validation ---------------------------------------
            // entry must not be parent of other entry
            $query = "SELECT COUNT(*) FROM ".TABLE_PREFIX."tree WHERE parent=".$params['id'];
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            $row   = mysql_fetch_array ($res);
            if ($row[0] > 0) {
                $logger->log (mysql_error(),  1);
                $this->info_msg = translate ('delete children first');
                return "failure";                
            }    

            // --- history, get_old values -----------------------------
            $old_entry_values = get_entries_for_primary_key (
                                       "tree", array ("id" => $params['id']));

            // --- delete entry -------------------------------------
            $del_query = "DELETE FROM ".TABLE_PREFIX."tree WHERE id='".$params['id']."'";
            $logger->log ($del_query, 7);
            $res = mysql_query ($del_query);
            if (mysql_error() != '') {
                $logger->log (mysql_error(),  1);
                $this->error_msg = "Error deleting entry from database: ".mysql_error();
                return "failure";
            }    
            
            // --- update history -----------------------------------
            update_history ("tree",                             // identifier for history table
                            "tree",                             // table
                            $this->entry['id']->get(),          // object_id
                            array ("id" => $this->entry['id']->get()), 
                            $old_entry_values); 
                        
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
        function show_entries (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validation ---------------------------------------
            if ($this->entry['parent_id']->get() == null)
                $this->entry['parent_id']->set(getMandatorTreeRoot());
            
            //$db_hdl->debug=true;
            $query = "SELECT id, name, link, img, sign, translate, enabled, authorize, order_nr, protected 
                      FROM ".TABLE_PREFIX."tree 
                      WHERE parent=".$this->entry['parent_id']->get()."
                     ";

            $this->dg = new datagrid (20, "tree", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffCC');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffff');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=9;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
        }
        
        function show_auth (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- validation ---------------------------------------
            //if ($this->entry['parent_id']->get() == null)
            //    $this->entry['parent_id']->set(0);
            
            //$db_hdl->debug=true;
            $query = "SELECT 
                            id, login, firstname, lastname, email, tree_id 
                      FROM ".TABLE_PREFIX."users a
                      LEFT JOIN authorize b on a.id=b.user_id
                     ";

            $this->dg = new datagrid (20, "auth", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffff');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffCC');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) $this->order=4;
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
        }


        function order_down (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- update order_nr ----------------------------------
            $query = "SELECT id, order_nr 
                      FROM ".TABLE_PREFIX."tree 
                      WHERE parent=".$this->entry['parent_id']->get()."
                      ORDER BY order_nr ASC
                     ";
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            
            $i     = 0;
            $hit   = false;
            while ($row = mysql_fetch_array ($res)) {
                if ($row['id'] == $this->entry['id']->get()) {
                    $hit = true;
                    $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".($i+1)." WHERE id=".$row['id'];   
                }    
                else {
                    if ($hit) {
                        $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".($i-1)." WHERE id=".$row['id'];   
                        $hit = false;
                    }
                    else    
                        $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".$i." WHERE id=".$row['id'];   
                }    
                
                mysql_query ($query);
                logDBError (__FILE__, __LINE__, mysql_error(), $query);
                $i++;
            }    
                        
            return "success";
        }

        function order_up (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
            
            // --- update order_nr ----------------------------------
            $query = "SELECT id, order_nr 
                      FROM ".TABLE_PREFIX."tree 
                      WHERE parent=".$this->entry['parent_id']->get()."
                      ORDER BY order_nr DESC
                     ";
            $res   = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            
            $i     = 0;
            $hit   = false;
            while ($row = mysql_fetch_array ($res)) {
                if ($row['id'] == $this->entry['id']->get()) {
                    $hit = true;
                    $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".($i-1)." WHERE id=".$row['id'];   
                }    
                else {
                    if ($hit) {
                        $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".($i+1)." WHERE id=".$row['id'];   
                        $hit = false;
                    }
                    else    
                        $query = "UPDATE ".TABLE_PREFIX."tree SET order_nr=".$i." WHERE id=".$row['id'];   
                }    
                
                mysql_query ($query);
                logDBError (__FILE__, __LINE__, mysql_error(), $query);
                $i--;
            }    
                        
            return "success";
        }
        
       /**
        * Show tree.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function get_tree (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
			$root_id = getMandatorTreeRoot();
            $nodes = "
                var TREE_NODES = [";
            $nodes .= $this->add_nodes ($root_id,20);    
            $nodes .= "    
                ];
            ";

            $this->entry['js_treenodes']->set ($nodes);

            return "success";
        }

       /**
        * Show "tree" with vertical tabs.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @since        0.5.2
        * @version      0.5.2
        */
        function get_verticaltabs(&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);
			$root_id = getMandatorTreeRoot();
            $nodes = "
            <table border=0 width='100%'>
                ";
            $nodes .= $this->add_tabs (1+$root_id,1);    
            $nodes .= "   
            	<tr><td>&nbsp;</td></tr> 
                </table>
            ";

            $this->entry['tabs_treenodes']->set ($nodes);

            return "success";
        }

       /**
        * Get entry data.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function get_entry (&$params) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if (!$this->entry['id']->get() > 0) {
                $this->entry['command']->set('add_entry');
                return "success";
            }
            else
                $this->entry['command']->set('update_entry');
            
            // get data for this entry if existent
            $query ="
                SELECT * FROM ".TABLE_PREFIX."tree
                WHERE id=".$this->entry['id']->get();
                
            $res = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            $row = mysql_fetch_assoc ($res);
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
            
            // --- adjust some values -------------------------------
            $this->entry['parent_id']->set ($row['parent']);
                        
            return "success";
        }
        
        function add_nodes ($root, $offset=0) {
            
            global $db_hdl, $logger;

            $js = "\n";
            for ($i=0; $i < $offset; $i++) $js .= " ";
            
            $query = "
                SELECT * 
                FROM ".TABLE_PREFIX."tree 
                WHERE 
                    parent=$root AND 
                    enabled!='0'
                ORDER BY order_nr";
                
            if (strtolower($_SESSION['login']) == "guest") {
                $query = "
                    SELECT * 
                    FROM ".TABLE_PREFIX."tree 
                    WHERE 
                        parent=$root AND 
                        enabled!='0' AND
                        visible_for_guest!='0'
                    ORDER BY order_nr";                
            }        
            //echo $query."<br>";
            
            $res   = mysql_query ($query);
            if (mysql_num_rows($res) == 0)
                return '';
                
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            while ($row = mysql_fetch_array ($res)) {
                $name   = $row['name'];
                if ((boolean)$row['translate'])
                    $name = translate($name, null, true);
                if ($root == 0)
                    $name = "<b>$name</b>";    
                if (trim ($row['sign']) != '') {
                    $name = $row['sign']." ".$name;    
                }
                if (trim ($row['img']) != '') {
                    $img_path = $this->entry['img_path']->get();
                    $name = '<img src="'.$img_path.$row['img'].'" align="top" border=0>&nbsp;'.$name;    
                }
                
                ($row['link']  != '') ? $link  = "'".$row['link']."'"  : $link  = 'null';
                ($row['frame'] != '') ? $frame = "'".$row['frame']."'" : $frame = 'null';
                
                $add = true;
                if ($row['subtree_identifier'] == '~~emails~~' && !module_enabled('emails'))
                    $add = false;
                
                if ($add) {
                    $js .= "['$name', $link, $frame, ".$row['subtree_identifier'];
                    $js .= $this->add_nodes ($row['id'], $offset+10);    
                    $js .= "],\n";    
                }
            }    
            
            return $js;
            
            
            
        }    

        function add_tabs ($root, $offset=0) {
            global $db_hdl, $logger, $img_path;

            $js = "\n";
            //for ($i=0; $i < $offset; $i++) $js .= " ";
            
            $query = "
                SELECT * 
                FROM ".TABLE_PREFIX."tree 
                WHERE 
                    parent=$root AND 
                    enabled!='0'
                ORDER BY order_nr";
                
            if (strtolower($_SESSION['login']) == "guest") {
                $query = "
                    SELECT * 
                    FROM ".TABLE_PREFIX."tree 
                    WHERE 
                        parent=$root AND 
                        enabled!='0' AND
                        visible_for_guest!='0'
                    ORDER BY order_nr";                
            }        
            //echo $query."<br>";
            
            $res   = mysql_query ($query);
            if (mysql_num_rows($res) == 0)
                return '';
                
            logDBError (__FILE__, __LINE__, mysql_error(), $query);
            while ($row = mysql_fetch_array ($res)) {
                $name   = $row['name'];
                if ((boolean)$row['translate'])
                    $name = translate($name, null, true);
                if ($root == 0)
                    $name = "<b>$name</b>";    
                if (trim ($row['sign']) != '') {
                    $name = $row['sign']." ".$name;    
                }
                if (trim ($row['img']) != '') {
                    $img_path = $this->entry['img_path']->get();
                    $name = '<img src="'.$img_path.$row['img'].'" align="top" border=0 alt="">&nbsp;'.$name;    
                }
                
                ($row['link']  != '') ? $link  = "'".$row['link']."'"  : $link  = 'null';
                ($row['frame'] != '') ? $frame = "'".$row['frame']."'" : $frame = 'null';

                $add = true;
                if ($row['subtree_identifier'] == '~~emails~~' && !module_enabled('emails'))
                    $add = false;
                
                if ($add) {
                    //$js .= "['$name', $link, $frame, ".$row['subtree_identifier'];
					$link = str_replace("&", "&amp;", $link);	

                    if ($offset == 1) {
	                    $js .= "<tr><td class='verticaltabs' onClick='toggleDisplay(\"tab_span_".$row['id']."\");'>";
	                    $link = str_replace("&", "&amp;", $link);	
	                    if ($link == "null") 
		                    $js .= "<a href='#' class='verticaltabs'>".$name."</a></td></tr>"; //".$row['subtree_identifier']."<br>";
						else 
		                    $js .= "<a href=$link target=$frame class='verticaltabs'>".$name."</a></td></tr>"; //".$row['subtree_identifier']."<br>";						
		                
	                    //$js .= $name."</td></tr>";
	                    $js .= "<tr><td><img src='../../img/shim.gif' alt='pic not found' height=5 border=0></td></tr></table>\n";
		                
		                $subnodes = $this->add_tabs ($row['id'], $offset+1);
		                
		                if ($row['subtree_identifier'] != '' || $subnodes != '') {
		                    $js .= "\n<DIV id='tab_span_".$row['id']."' style='display:none'>\n";
		                    $js .= "<table width='100%'>\n";
			                if ($row['subtree_identifier'] != '')
		    	                $js .= "<tr><td>".$row['subtree_identifier']."</td></tr>";     
    	        	        $js .= $subnodes;     
    	        	        $js .= "<tr><td><img src='../../img/shim.gif' alt='pic not found' height=5 border=0></td></tr>";
    	            	    $js .= "</table>\n";
        	            	$js .= "</DIV>\n"; 
		                }
		                
        	            $js .= "<table width='100%'>\n";       
                    }
                    else {
	                    $js .= "<tr><td>";
	                    for ($i=0; $i < 2+2*$offset; $i++) $js .= "&nbsp;";
	                    if ($link != "null") 	
		                    $js .= "<a href=$link target=$frame>".$name."</a></td></tr>";
						else 
							$js .= $name."</td></tr>";
						if ($row['subtree_identifier'] != '')
		                    $js .= $row['subtree_identifier'];                    	
    	                $js .= $this->add_tabs ($row['id'], $offset+1);    
        	            $js .= "\n";    
                    }
                }
            }    
            
            return $js;
        }
                
        function getParentChain ($start_id, $chain = null) {
            global $db_hdl, $logger;

            $sql = "SELECT id, name, parent 
                    FROM ".TABLE_PREFIX."tree
                    WHERE id='$start_id'";
            //echo $sql;
            if (!$res = $this->ExecuteQuery ($sql, 'mysql_error')) return "failure";
            ($chain == null) ? $cnt = 0 : $cnt = count($chain);
            while ($row = mysql_fetch_array($res)) {
           		$chain[$cnt]['name']   = $row['name'];
                $chain[$cnt]['id']     = $row['id'];                
                $chain += $this->getParentChain ($row['parent'], $chain);
            }	
            return $chain;
        }
        
        function handleGUIElements ($params) {
            
            // --- internal gui for testing a.s.o. ------------------
            /*if ($this->smarty != null) {
                $this->addParams2Smarty ($params);
            }*/

            // --- model assignments --------------------------------
            return $this->addParams2Model ($this->entry, $params); //, $omit);
        }
    
        /**
        * Execute queries and handle errors
        *
        * Execute queries and handle errors
        * 
        * @access       private
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        paramas array holding request variables
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function ExecuteQuery ($query, $msg, $stop_execution = true) {
            
            $res = mysql_query ($query);
            logDBError (__FILE__, __LINE__, mysql_error(), $query);

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

    }   

?>