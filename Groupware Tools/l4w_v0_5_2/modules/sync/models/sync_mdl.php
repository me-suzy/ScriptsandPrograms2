<?php

   /**
    * $Id: sync_mdl.php,v 1.5 2005/07/31 09:10:02 carsten Exp $
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package sync
    */
    
   /**
    * include fields validation rules
    */  
    include ('fields_validations.inc.php');
    
   /**
    * Documents Model
    *
    * model for handling documents
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2004
    * @package docs
    */    
    class sync_model extends l4w_model {
         
        /**
          * int holding the id of an added document entry
          *
          * @access public
          * @var string
          */  
        var $inserted_note_id = null;     // ID for user when adding was successfull
        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @since        0.4.4
        * @version      0.4.4
        */
        function sync_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array ("show_options",       
                       "syncronize",
                       "help"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
                            
        }

      /**
        * syncronization handler.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function syncronize ($params) {
            global $logger;
            
            //var_dump ($params);
            
            if (isset ($params['use_notes']) && $params['use_notes'] == "on") {
                $result = $this->syncronize_notes ($params);    
                if ($result != "success") return $result;
            }    
            
            return "success";
        }
        
      /**
        * syncronization handler for notes.
        *
        * If there are any problems, examine model->error_msg and model->info_msg;
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.6
        * @version      0.4.6
        */
        function syncronize_notes ($params) {
            global $logger;
            
            //var_dump ($params);
            
            // get all notes the current user is owner of
            $query = "
                SELECT 
                	memo_id,
                	headline,
                	content,
                	created,
                	last_change,
                	access_level
                FROM memos 
                LEFT JOIN metainfo ON ".TABLE_PREFIX."metainfo.object_id=memos.memo_id
                WHERE ".TABLE_PREFIX."metainfo.object_type='note' AND owner=".$_SESSION['user_id'];
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // new webservice client
            $client = new soapclient($this->entry['remote']->get()."webservices/notes.php");

            // syncronize notes using webservice
            while ($row = mysql_fetch_array($res)) {
                // Call the SOAP method
                $result = $client->call('updateNoteOnServer', 
                    array('login'       => $this->entry['use_user']->get(),
                          'md5passwd'   => $this->entry['use_pass']->get(),
                          'headline'    => $row['headline'],
                          'content'     => $row['content'],
                          'sync_with'   => $row['leads4web'],
                          'identifier'  => $row['memo_id'],
                          'last_change' => $row['last_change'],
                          0
                    ));
                
                // Display the result
                print_r($result);
            }    
            
            return "success";
        }
    }   

?>