<?php
  /**
    * $Id: external_references.class.php,v 1.4 2005/04/03 06:30:10 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package stats
    */
    
  /**
    *
    * Users Model Class
    * @package stats
    */
    class external_references {

        var $tab_nr                     = null;
        var $locked                     = null;
        var $img_path                   = null;
        var $currentObjectID            = null;
        var $currentObjectType          = null;
        
       /**
        * Constructor.
        *
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @package      easy_framework
        * @since        0.4.6
        * @version      0.4.6
        */
        function external_references ($id, $type, $locked) { 
            
            $this->locked            = $locked;
            $this->currentObjectID   = $id;
            $this->currentObjectType = $type;
        }
        
        function setImgPath ($path) {
            $this->img_path = $path;    
        }    
                
        function setTabNr ($nr) {
            $this->tab_nr = $nr;    
        }    
        
        function addExternalLink ($entry, $entry_type, $request) {
            global $easy;
            
            $path = urldecode($request['external_link_path']);
            $path = str_replace ('\\','/',$path);
            $path = str_replace ('//','/',$path);
            ($request['external_link_name'] != '') ? $desc = $request['external_link_name'] : $desc = $path;
            $seq_query = "SELECT (MAX(to_object_id)+1), 1 FROM ".TABLE_PREFIX."refering WHERE ref_type=3";
            if (!$seq_res = $this->ExecuteQuery ($seq_query, 'mysql_error')) 
                return "failure";
            $seq_row   = mysql_fetch_array($seq_res);
            ($seq_row[0] > 0) ? $sequence = $seq_row[0] : $sequence = 1;
            
            $ref_query = "INSERT INTO ".TABLE_PREFIX."refering (
                            from_object_type,
                            from_object_id,
                            to_object_type,
                            to_object_id,
                            ref_type,
                            description,
                            ref_scheme,
                            ref_path)
                          VALUES (
                            '".$entry_type."',
                            ".$this->currentObjectID.",
                            'external',
                            ".$sequence.",
                            3,
                            '$desc',
                            ".$request['scheme'].",
                            '$path'
                          )";
            if (!$this->ExecuteQuery ($ref_query, 'mysql_error')) 
                return "failure";
                    
            return "success";                        
        }    
        
        
        /**
        * Execute queries and handle errors
        *
        * private function which should be called to execute database
        * queries. In case of an error the execution can be stopped and
        * a message can be assigned to the models error_msg attribute.
        * Same function as in leads4web_model
        * 
        * @access       private
        * @param        string query to execute
        * @param        string message to show in case of error
        * @param        boolean should execution be stopped in case of error
        * @return       ressource database resource on success, false on failure
        * @since        0.4.7
        * @version      0.4.7
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