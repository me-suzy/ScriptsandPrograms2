<?php

   /**
    * Model template. 
    * 
    * This class provides the model functionality for handling emails. Accounts
    * can be managed (added, deleted) and emails get be imported from POP3 servers.
    * The basic idea is to divide emails into smaller chunks (which are in fact the
    * contained attachments and so on). The main mail gets a master_id of 0, the dependend
    * parts get the master_id of the main element.
    * The mails are stored in the file system and meta information (including the mail
    * header) is stored in the database.
    * Whenever possible, the downloaded stream is not changed at all. Formatting rules and
    * things like that should occur as late as possible (maybe even in the view).
    *
    */
            
   /**
    * You can modify the fields_validation rules to change the behaviour of how notes get validated. 
    */  
    include ('fields_validations.inc.php');
    
   /**
    *
    * @version      $Id: mail_mdl.php,v 1.40 2005/08/01 14:55:13 carsten Exp $
	* @author       Carsten Gräf	
    * @copyright    evandor media GmbH
    * @package      emails
    * 
    */    
    class mail_model extends l4w_model {
         
        /**
          * defines the kind of items handled in this model 
          *
          * @access public
          * @var string
          */  
        var $entry_type      = 'mail';     

		/**
          * imap connection (resource)
          *
          * @access private
          * @var string
          */  
        var $conn      = null;
        
        /**
          * progress in Percent
          *
          * @access private
          * @var string
          */  
        var $progress      = 0;
        
        /**
          * The parsed body of a mail 
          *
          * @access private
          * @var array
          */  
        var $body          = array(); 
        
        /**
          * internal counter
          *
          * @access private
          * @var int
          */  
        var $counter       = 1; 
        
        /**
          * variables holding information about success of getting mails
          *
          * @access private
          * @var string
          */  
        var $errors  = null;		// all errors which occured while fetching mail
        var $alerts  = null;		// all alerts which occured while fetching mail
        var $log     = null;		// general log about fetching all mails
        var $maillog = null;		// mail-specific log about fetching a single mail
        
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
        function mail_model ($smarty, $AuthoriseClass) {

            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_entries", null,
                array ("add_account_view",
                       "add_account",
                       "create_mail",
                       "send_mail",
                       "delete_from_trash",
                       "download_mail",
                       "move2trash",
                       "edit_account",
                       "get_mails",
                       "update_account",
                       "show_content",
                       "show_accounts",
                       "show_attachments",
                       "show_header",
                       "show_log",
                       "show_mail",
                       "show_mails",
                       "show_mails_for_contact",
                       "show_pic"
            ));
                                                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
            
            $this->errors   = '';
            $this->alerts   = '';
            $this->log      = '';
            $this->maillog  = '';
        }

      /**
        * 
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.5.2
        * @version      0.5.2
        */
        function sendmail () {
            
        	include ("../../extern/libmail/libmail.php");
        	
			/*$cont_res = mysql_query ("SELECT * FROM contacts WHERE contact_id='$id'", $db);
			$cont_row = mysql_fetch_array ($cont_res);
			if ($to == $cont_row['email']) $contact_id = $id;
			else						   $contact_id = 0;

			//mysql_select_db($db_user_name, $db);
			$res = mysql_query ("SELECT * FROM users WHERE id='$user_id'", $db);
			$row = mysql_fetch_array ($res);
			//mysql_select_db($db_name, $db);*/
		
			$add_headers  = "X-Mailer: leads4web by evandor media\n";
			//$add_headers .= "From: ".$row['email']; //." (".$row['vorname']." ".$row['nachname'].")\n";
			if ($this->entry['cc'] <> "")  $add_headers .= "Cc: ".$this->entry['cc']."\n";
			if ($this->entry['bcc'] <> "") $add_headers .= "Bcc: ".$this->entry['bcc']."\n";
			$size = 1 + round (strlen ($this->entry['message']->get()) / 1024);

			//$done = mail ($to, $subject, $message, $add_headers);
		
			$mail = new Mail();
			$mail->Subject($this->entry['subject']->get());
			$mail->From('graef@evandor.de'); //." (".$row['vorname']." ".$row['nachname']);
			$mail->To($this->entry['to']->get());
			if ($this->entry['cc']->get() <> "")  $mail->CC($this->entry['cc']->get());
			if ($this->entry['bcc']->get() <> "") $mail->BCC($this->entry['bcc']->get());
			$mail->Body($this->entry['message']->get());
			
			if (!$done = $mail->Send()) {
				
				return "failure";
			}
		
            return "success";
        }
      /**
        * 
        *
        * 
        * @access       public
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function account_validation () {
            
            // --- passwords differ? ------------------------------
            if ($this->entry['pass']->get() != $this->entry['pass2']->get()) {
                $this->error_msg .= translate ('passwords differ');
                return false;
            }

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
        * 
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.4.7
        * @version      0.4.7
        */
        function add_account ($params) {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            //assert ('$this->entry["parent"]->get() >= 0');
            $validation = $this->account_validation();
            if ($validation != "success") return $validation; 
            
            // --- add entry ----------------------------------------  
            $use_ssl = "0";
            if ((bool)$this->entry['use_ssl']->get())          
            	$use_ssl = "1";
            $active = "0";
            if ((bool)$this->entry['active']->get())          
            	$active = "1";
            		
            $query = "INSERT INTO ".TABLE_PREFIX."accounts (
                                owner,
								type,
                                host,
								port,
                                login,
                                pass,
								use_ssl,
								active,
                                default_folder)
                               VALUES (
                                '".$_SESSION['user_id']."',
                                '".$this->entry['type']->get()."',
                                '".$this->entry['host']->get()."',
								'".$this->entry['port']->get()."',
                                '".$this->entry['login']->get()."',
                                '".$this->entry['pass']->get()."',
								'$use_ssl',
								'$active',
                                1
                               )";
            $logger->log ($query, 7);
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            $this->entry['account_id']->set (mysql_insert_id());
			$this->inserted_account_id = mysql_insert_id();
            
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'new folder','system',$this->inserted_entry_id);
                        
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
        * @since        0
        * @version      0
        */
        function    update_account (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validate -----------------------------------------
            $validation = $this->account_validation();
            if ($validation != "success") return $validation; 
                                    
            // --- get old values -----------------------------------
            $old_entry_values   = get_entries_for_primary_key (
                                       "accounts", array ("id" => $this->entry['account_id']->get()));

            // --- sufficient rights ? ------------------------------
            if ($old_entry_values['owner'] != $_SESSION['user_id']) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
                                                          
            // --- update entry ----------------------------------------
            $use_ssl = "0";
            if ((bool)$this->entry['use_ssl']->get())          
            	$use_ssl = "1";
            $active = "0";
//echo $this->entry['active']->get();
            if ((bool)$this->entry['active']->get())          
            	$active = "1";
            	
            $update_query = "UPDATE ".TABLE_PREFIX."accounts SET 
                                host    = '".$this->entry['host']->get()."',
                                login   = '".$this->entry['login']->get()."',
                                port    = '".$this->entry['port']->get()."',
								type    = '".$this->entry['type']->get()."',
								use_ssl = '$use_ssl',
								active  = '$active'
							 WHERE id   = '".$this->entry['account_id']->get()."'";
//echo $update_query;
            if (!$res = $this->ExecuteQuery ($update_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";

            //if ($this->entry['pass']->get() != '') {
                $update_query = "UPDATE ".TABLE_PREFIX."accounts SET 
                                    pass      = '".$this->entry['pass']->get()."'
                                WHERE id      = '".$this->entry['account_id']->get()."'";

                if (!$res = $this->ExecuteQuery ($update_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            //}
                                                                
			// --- fire event ---------------------------------------
            //fireEvent ($this, $this->entry_type, 'changed '.$this->entry_type, 'system', $this->entry['memo_id']->get());

            //if (isset ($params['apply']) && $params['apply'] != '')
            //    return "apply";
                
            return "success";
        }

      /**
        * Show all accounts.
        *
        * A datagrid according to the current parameters is created. This datagrid can be sorted,
        * searched and so on. 
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.4.7
        * @version      0.4.7
        */
        function show_accounts (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            //$db_hdl->debug=true;
            if (is_null($query)) {
                $query = "
                    SELECT
                        id,
                        host,
						port,
						type,
						use_ssl,
						active,
                        login,
                        '******' AS pass,
                        default_folder
		            FROM ".TABLE_PREFIX."accounts
                    WHERE owner=".$_SESSION['user_id']."
                    ORDER BY host";
            }  

			$this->dg = new datagrid (20, "accounts", basename($_SERVER['SCRIPT_FILENAME']));
                        
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
        * Shows single entry.
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_account (&$params) {
            global $db_hdl, $logger, $PING_TIMER, $use_headline;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- validation ---------------------------------------
            assert ('$params["entry_id"] > 0');
            
            // get data for this contact
            $query ="
                SELECT * FROM ".TABLE_PREFIX."accounts
                WHERE id=".$params['entry_id']." AND owner=".$_SESSION['user_id'];

            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    
                                  
            // --- adjust some values -------------------------------
            $this->entry['account_id']->set ($row['id']);
            $this->entry['pass2']->set ($row['pass']);
            
            return "success";
        }


      /**
        * 
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       public
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_mails ($params) {
            global $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
		
            // --- test whether imap functions exist ----------------
        	if (!function_exists ("imap_errors")) {
        	    $this->error_msg .= translate ('imap not active');
        	    return "failure";
            } 

			// --- force output (I want it now) ---------------------
			echo "
				<html>
				<head>
				<title></title>
					<script language='javascript'>
						function set_percent (prozent, text, clear) {

							show_percent = '';
							if (prozent > 0)
								show_percent = prozent+' %';
				            opener.parent.configleiste.document.progress.progress_gif.width=prozent*1.7;
							opener.parent.configleiste.document.getElementById('percent_num').firstChild.nodeValue = show_percent;
							opener.parent.configleiste.document.getElementById('message').firstChild.nodeValue     = text;
							if (clear) {
								window.setTimeout ('set_percent (0, \"\", false)', 2000);
							}
						}
					</script>
				</head>
				<body bgcolor='#eeeeee'>
			";
			ob_implicit_flush ();
			$this->force_output();

			echo "<script>set_percent(1, '".translate('starting getting mails')."', false)</script>\n";

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- get mail for each account ------------------------ 
            $query    = "SELECT * FROM ".TABLE_PREFIX."accounts WHERE owner=".$_SESSION['user_id'];
            if (!$res = $this->ExecuteQuery ($query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            if (mysql_affected_rows() == 0) {
                $this->info_msg .= translate ('no account for user');
                return "success";    
            }    
            $cnt_accounts = mysql_affected_rows();
            
	        // --- mark older mails as old ----------------------------
            $mail_query = "UPDATE ".TABLE_PREFIX."emails SET new='0' WHERE owner=".$_SESSION['user_id'];
            if (!$this->ExecuteQuery ($mail_query, 'mysql_error', true, __FILE__, __LINE__)) return "failure";
            
            // --- iterate over accounts ------------------------------
            $i = 0;
            while ($account_row = mysql_fetch_array ($res)) {
            	if ((bool)$account_row['active']) {
	            	$this->progress = round(100 * ($i / $cnt_accounts)) + 1;
    	            echo "<script>set_percent($this->progress, '".translate('checking account').$account_row['host']."', false)</script>\n";
        	        $this->connect2account ($account_row, $cnt_accounts);    
            	}
            	else 
    	            echo "<script>set_percent($this->progress, '".translate('ignore account').$account_row['host']."', false)</script>\n";            	
            	$i++;
            }    
			
			// --- done message ---------------------------------------
			echo "<script>set_percent(100, '".translate('getting mails done')."', true)</script>\n";

			// --- add alerts and errors, if any ----------------------
			if ($imap_errors = imap_errors()) {
			//if (count($imap_errors) > 0) {
				foreach ($imap_errors AS $key => $val) 
    	        	$this->errors .= $val."\n";      
			}
			if ($imap_alerts = imap_alerts()) {
				foreach ($imap_alerts AS $key => $val) 
					$this->alerts .= $val."\n";      
			}
				
			// --- set protocol messages ------------------------------
			$this->entry['error']->set($this->errors);
			$this->entry['alerts']->set($this->alerts);
			$this->entry['log']->set($this->log);
            
            return "success";
        }
        
      /**
        * 
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       private
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.1
        */
        function connect2account ($account, $cnt_accounts) {
            global $logger;
            
            $Nmsgs          = 0;
            $existing_mails = array ();
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            //if ($this->conn = @imap_open ("{".$account['host'].":110/pop3/ssl/novalidate-cert}",  $account['login'], $account['pass'])) {
            $ip      = gethostbyname ($account['host']);
            $port    = $account['port'];
            $type    = $account['type'];
            $use_ssl = $account['use_ssl'];
            
            //{mail.evandor.de:110/pop3/ssl/novalidate-cert}INBOX
            $connect_string = "";
            switch ($type) {
				case "pop3":
					if ($use_ssl)
                        $connect_string = "{".$account['host'].":".$port."/pop3/ssl/validate-cert}INBOX";
				    else
			            $connect_string = "{".$account['host'].":".$port."/pop3/novalidate-cert}INBOX";
					break;
				case "imap":
		            $connect_string     = "{".$account['host'].":".$port."/pop3}INBOX";
					break;
				case "nntp":
		            $connect_string     = "{".$ip.":".$port."/pop3}INBOX";
					break;
				default:
					die ("unknown type in ".__FILE__);
					break;
			}

            $success = $this->conn = @imap_open ($connect_string,  $account['login'], $account['pass']);					
            
            if ($success) {
                $this->log .= translate ('opened mailbox')." ".$account['host']."\n";
            }
		    else {
                $this->errors .= translate ('failed to connect').": \n";
                $this->errors .= translate ('host')." ".$account['host'].", \n";
                $this->errors .= translate ('ip')." ".$ip.", ";
                $this->errors .= translate ('user')." ".$account['login']."\n";
                $this->errors .= translate ('Connection String:').": ";
		        $this->errors .= $connect_string."\n";
		        return;
		    }
		    
            $check = imap_check ($this->conn);
		    if (!$check) {
			    //echo "<script>set_percent($percent, '".get_from_texte ("Connection problems", $language)." ".$row_account['host']."')</script>\n";
                //logMsg (get_from_texte ("Connection problems", $language)." ".$row_account['host']);
                $this->errors .= translate ('connection problems')."\n";
                      
		        return;
		    }
		    
            $Nmsgs += $check->Nmsgs;

    		// skip, if there are no messages
    		if ($check->Nmsgs == 0) {
    		    $this->alerts .= translate ('no mails found');
		        $this->entry['goto_tab']->set (2); // info tab

		        return;
    		} 
    		else {
    			$this->log .= translate ('messages in mailbox').": ".$check->Nmsgs."\n";	
    		}	
    		
            // Which mails are already stored in my database?
    		$uid_local = array ();
	       	$uid_local_query = "SELECT unique_id FROM ".TABLE_PREFIX."emails WHERE owner=".$_SESSION['user_id'];
	       	if (!$uid_local_res = $this->ExecuteQuery ($uid_local_query, 'mysql_error', true, __FILE__, __LINE__)) {
	       	    $this->error_msg .= mysql_error();
	       	    return;
	       	}
    		while ($this_row = mysql_fetch_array ($uid_local_res)) {
    			$uid_local[] = $this_row[0];
    		}

            $overview = imap_fetch_overview($this->conn,"1:".$check->Nmsgs, FT_UID);
            //var_dump ($overview);
            
        	$this->get_mails_for_account (
        		$overview, 
        		$account['id'], 
        		$cnt_accounts,
        		$uid_local);
        
            if (imap_close ($this->conn)) {
                $this->log .= translate ('mailbox closed')." ".$account['host']."\n";            	
                $this->log .= "\n";            	
            } 	
			else {
                $this->errors .= translate ('failed closing connection')."\n";
                $this->errors .= translate ('call returned').": \n";
		    }
            
			$this->entry['account_id']->set($account['id']);
			//$this->entry['getlog']->set ($this->log);
        }

      /**
        * 
        *
        * If there are any problems, examine model->error_msg and model->info_msg.
        * 
        * @access       private
        * @param        array  holding request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_mails_for_account (
        	$overview, 
        	$account_id, 
        	$cnt_accounts,
        	$uid_local) {
        		
            global $logger;
            
            $remote_uids   = array ();
            $cnt           = 0;
            $start         = $this->progress;
            
            // --- iterate over mails in account --------------------
            for ($i=0; $i < count($overview); $i++) {
            	
            	$this->maillog = '';        // reset mail-specific log
            	$this->body    = array ();	// empty body
            	
            	$this->progress = $start + round((100.0 / $cnt_accounts) * ($i / count($overview)));
            	echo "<script>set_percent($this->progress, '".translate('checking mail').": ".$i."', false)</script>\n";
            	$this->force_output();
            	
	            // --- get Unique ID 
				$this->initialise_by_overview ($overview[$i], $account_id);
				$myUID         = $this->entry['myUID']->get();
				$remote_uids[] = $myUID;

				// Fetch only new mail:
				if (in_array ($myUID,$uid_local)) {
					continue;
				}
				
				$cnt++;
				$this->log .= translate ('adding mail').": ".substr ($this->entry['subject']->get(),0,50)."...\n";
				
				// --- eventually get mail --------------------------
				$header    = imap_fetchheader    ($this->conn, ($i+1));
				$structure = imap_fetchstructure ($this->conn, ($i+1));
            	$this->parse_stream ($this->conn, ($i+1), $header, $structure);
				$this->insert_into_db ($account_id, $header);
				$this->insert_into_filesystem ();
            }
        }
            
 		function insert_into_db ($account_id, $header) {

			for ($i=1; $i <= count( $this->body ); $i++) {

				switch ($this->body[$i]['bodytype']) {
					case TYPETEXT:
						break;
					case TYPEMULTIPART:
						break;
					case TYPEMESSAGE: 	  break;
					case TYPEAPPLICATION: break;
					case TYPEAUDIO: 	  break;
					case TYPEIMAGE: 	  break;
					case TYPEVIDEO: 	  break;
					case TYPEMODEL: 	  break;
					default: break;
				}

				if ($i==1) 
					$master_id = 0;
				//$size = $this->size;
							
				/*$checked_subject = $this->subject;
				if (strlen ($checked_subject) > 60) $checked_subject = substr ($checked_subject,0,57)."...";
				$checked_subject = mysql_escape_string($checked_subject);*/
				$attachment = "0";
				//echo count( $this->body )."###<br>";
				if (count( $this->body ) > 1) 
					$attachment = "1";

				if (!isset($this->body[$i]['filename'])) $this->body[$i]['filename'] = "";

				$folder     = $this->entry['folder']->get();
			
				$msg_nr     = $this->entry['msg_nr']->get();
				$myUID      = $this->entry['myUID']->get();
				$from       = $this->entry['from']->get();
				$to         = $this->entry['to']->get();
				$date       = $this->entry['date']->get();
				//$master_id  = 0;
				$size       = $this->entry['size']->get();
				$subject    = $this->entry['subject']->get();
				$subject    = mysql_escape_string($subject);
				//$attachment = "0";

				if ($i > 1) {
					$size=0;
					//$use_talk_id = 0;
				}
				
				$query = "INSERT INTO ".TABLE_PREFIX."emails
									(owner,       master_id,        grp,   
									 unique_id,   contact,	        folder,
									 sender,      recipient,	    senddate,
									 account,     size,             subject,	        
									 header,  	  
									 prim_body_type,
									 subtype,
									 msg_nr, 
									 attachment,  
								     new,        
									 log) 
						  VALUES
							('".$_SESSION['user_id']."', '$master_id', '0',         
							 '".$myUID."',               '0',          '$folder',
							 '".$from."',                '".$to."',    '".$date."',
							 '".$account_id."',          '$size', 	   '".mysql_escape_string($subject)."',
							 '".mysql_escape_string($header)."', 
							 '".$this->body[$i]['bodytype']."',
						     '".$this->body[$i]['subtype']."',
                 			 ".$msg_nr.",		 
							 '$attachment',
							 '1',
							 '".mysql_escape_string($this->maillog)."'
							)";

				
				$res	 = mysql_query ($query);
				if (mysql_error() != "") {
					echo $query;
					echo mysql_error();	
				}

				if ($i == 1)
					$master_id = mysql_insert_id();
				$this->body[$i]['new_id']   = mysql_insert_id();
			}
		}

		function insert_into_filesystem () {
		//EMAIL_PATH

			for ($i=1; $i <= count( $this->body ); $i++) {

				switch ($this->body[$i]['encoding']) {
					case ENC7BIT:     	  	 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					case ENC8BIT:   		 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					case ENCBINARY: 	  	 $this->body[$i]['emailtext'] = $this->body[$i]['content']	; break;
					case ENCBASE64: 		 $this->body[$i]['emailtext'] = imap_base64 ($this->body[$i]['content']); break;
					case ENCQUOTEDPRINTABLE: $this->body[$i]['emailtext'] = imap_qprint ($this->body[$i]['content']); break;
					case ENCOTHER:			 $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
					default: $this->body[$i]['emailtext'] = $this->body[$i]['content']; break;
				}

				switch ($this->body[$i]['bodytype']) {
					case TYPETEXT:
						if ($this->body[$i]['subtype'] == "HTML") 	    break;
						if ($this->is_html($this->body[$i]['content'])) break; // als plain deklariert, aber doch html?
						//$this->body[$i]['emailtext'] = $this->default_font.str_replace ("\n", "<br>\n", $this->body[$i]['emailtext'])."</font>";
						$this->body[$i]['emailtext'] = preg_replace ("'http:\/\/[^ ^\n]*'i", "<a href='\\0' target='new'><font color='#333366'>\\0</font></a>",  $this->body[$i]['emailtext']);
						$this->body[$i]['emailtext'] = preg_replace ("'(?<!http:\/\/)(www\.[^ ^\n^\( ^\)]*)'i", "<a href='http://\\1' target='new'><font color='#333366'>\\1</font></a>", $this->body[$i]['emailtext']);

						break;
					case TYPEMULTIPART:
						if (($this->body[$i]['subtype'] == "ALTERNATIVE") AND (!$this->is_html ($this->body[$i]['emailtext']))) {
							$this->body[$i]['emailtext'] = $this->default_font.str_replace ("\n", "<br>\n", $this->body[$i]['emailtext'])."</font>";
						}
						break;
					case TYPEMESSAGE: 	  break;
					case TYPEAPPLICATION: break;
					case TYPEAUDIO: 	  break;
					case TYPEIMAGE: 	  break;
					case TYPEVIDEO: 	  break;
					case TYPEMODEL: 	  break;
					default: $this->body[$i]['emailtext'] = $default_font."Unbekannter Typ</font>";
				}


				$folder     = $this->entry['folder']->get();
				
				$maildir = EMAIL_PATH."/".$_SESSION['user_id'];
				@mkdir ($maildir, 0700);
				$maildir = EMAIL_PATH."/".$_SESSION['user_id']."/".$folder;
				@mkdir ($maildir, 0700);
				$mailfile = $maildir."/".$this->body[$i]['new_id'];
				$fh = fopen ($mailfile, "w");
				fwrite ($fh, $this->body[$i]['emailtext']);
				fclose ($fh);

			}
		}

       /**
        * Show an mail entry.
        *
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_mail (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

			$query = "
				SELECT * FROM ".TABLE_PREFIX."emails 
				WHERE mail_id=".$this->entry['mail_id']->get()."
					AND owner=".$_SESSION['user_id']."
			";

			if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            $row = mysql_fetch_assoc ($res);

            // --- sufficient rights ? -----------------------------
            if ($_SESSION['user_id'] != $row['owner']) {
                $this->error_msg = translate ('no sufficient rights');
                return "failure";
            }
            
            foreach ($row AS $field => $value) {
                if (isset($this->entry[$field])) 
                    $this->entry[$field]->set($value);
            }    		
            
            // --- adjustments --------------------------------------
            $this->entry['from']->set ($row['sender']);
            $this->entry['to']->set ($row['recipient']);
            $this->entry['getlog']->set ($row['log']);
            
            //$this->entry['getlog']->set ($row['log']);

			return "success";
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
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_mails (&$params, $query = null) {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            if ($query === null) {
	            $query = "
	                SELECT
	                    mail_id,
						master_id,
						owner,
						grp,
						access_level,
						account,
						contact,
						unique_id,
						folder,
						sender,
						recipient,
						subject,
						senddate,
						size,
						attachment,
						beenread,
						new,
						filename,
						subtype
		            FROM ".TABLE_PREFIX."emails
	                WHERE 
						owner=".$_SESSION['user_id']." AND 
						master_id=0 AND
						deleted='0' AND
						folder=".$this->entry['folder']->get()."
				    ";
            }

			$this->dg = new datagrid (20, $this->entry_type."s", basename($_SERVER['SCRIPT_FILENAME']));
                        
            $this->dg->row_class_schema = array ();
            $this->dg->TRonMouseOver = "onmouseover=\"ColorOver(this,~line~,'#ffffCC');\"";
            $this->dg->TRonMouseOut  = "onmouseout =\"ColorOut (this,~line~,'#ffffff');\"";
            $this->dg->TRonDblClick  = "onDblClick =\"OpenElement (this);\"";
            
            // Default order
            if (!isset($params['order'])) 
            	$this->order=13;
            if (!isset($params['direction'])) 
            	$this->direction = "DESC";
            $this->dg->setOrder       ($this->order, $this->direction);
            $this->dg->setPage        ($this->pagenr);
            
            $this->dg->SetPagesize ($_SESSION['easy_datagrid']['entries_per_page']);

            $this->dg->datagrid_from_adodb_query ($query, $params, $db_hdl);
                        
            return "success";
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
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_mails_for_contact (&$params) {
            global $db_hdl, $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
  
  			$froms = explode ("|",$params['from_adresses']);
  			assert ('count($froms) > 0');
  			$from_condition = '';
  			foreach ($froms AS $key => $from) {
  				$from_condition .= "sender LIKE '%$from%' OR ";	
  			}
  			$from_condition = substr($from_condition,0,-4);
  			$query = "
				SELECT
                    mail_id,
					master_id,
					owner,
					grp,
					access_level,
					account,
					contact,
					unique_id,
					folder,
					sender,
					recipient,
					subject,
					senddate,
					size,
					attachment,
					beenread,
					new,
					filename,
					subtype
	            FROM ".TABLE_PREFIX."emails
                WHERE 
					owner=".$_SESSION['user_id']." AND 
					master_id=0 AND
					deleted='0' AND
					($from_condition)
			    ";
			$logger->log ($query, 7);
        	return $this->show_mails($params, $query);
        }
                    
       /**
        * 
        *
        * Asserts entry_id is given and contact exists. Returns "failure" if user does not have sufficient
        * rights to view this contact.  
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @since        0.5.0
        * @version      0.5.0
        */
        function show_content (&$params) {
            global $logger;

            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            $this->show_mail ($params);
            //die (var_dump ($this->entry));
            $mail_id = $this->entry['mail_id']->get();
            $folder  = $this->entry['folder']->get();
            
            $maildir = EMAIL_PATH."/".$_SESSION['user_id'];
            $mailfile = $maildir."/".$folder."/".$mail_id;
            $fh = fopen ($mailfile, "rb");
            $content = fread ($fh, filesize($mailfile));
            fclose ($fh);
            
            $this->entry['content']->set ($content);

			// --- update mail entry --------------------------------
			$query = "
						UPDATE ".TABLE_PREFIX."emails 
						SET beenread='1'
						WHERE mail_id=".$this->entry['mail_id']->get();
			if (!$this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) 
				return "failure";
            			            
            return "success";
        }

       /**
        * Show an mail entry.
        *
        *
        * @access       public
        * @param        array holds request variables
        * @param        string query, defaults to null. If not null, the given query is used to populate the datagrid
        * @return       string "success"
        * @since        0.5.0
        * @version      0.5.0
        */
        function get_attachments () {
            global $db_hdl, $logger;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

			$query = "
				SELECT * FROM ".TABLE_PREFIX."emails 
				WHERE master_id=".$this->entry['mail_id']->get()."
					AND owner=".$_SESSION['user_id']."
			";
			
			if (!$res = $this->ExecuteQuery ($query, 'mysql_error', __FILE__, __LINE__)) return "failure";
            assert ('mysql_num_rows($res) > 0');
            
            $this->entry['attachments']->set ($res);
            
			return "success";
        }

       /**
        * 
        *
        *
        * @access       public
        * @param        array holds requests parameters
        * @return       string "success" or "failure"
        * @todo         public funciton with "real" array of ids
        * @since        0.5.0
        * @version      0.5.0
        */
        function move2folder (&$params) {
            global $db_hdl, $logger, $PING_TIMER;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

            // --- find out ids of notes to delete ------------------
            $move_list = array ();

            foreach ($params AS $key => $value) {
            	//echo substr ($key, 0,5)."/".$this->entry_type."_<br>";
                if (substr ($key, 0,5) == $this->entry_type."_") {
                    $move_list[] = (int)substr ($key,5);    
                }    
            }    
              
            // --- init ---------------------------------------------
            $info_msg  = '';
            $error_msg = '';
            $success   = true;
          
            // --- move entries -----------------------------------
            foreach ($move_list AS $key => $move_id) {
				$wrapper = array ("entry_id" => $move_id, "move_to" => $params['target_folder']);
            	if ($params['target_folder'] == -1)  // delete, don't move!
            		$result = $this->delete_from_trash ($wrapper);
            	else 
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
        * moves entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.0
        */
        function move_entry (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- assertions ---------------------------------------
            assert ('(int)$params["entry_id"] > 0');
            assert ('(int)$params["move_to"] >= 0');
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);            
                                                                    
            // --- sufficient rights ? ------------------------------
            
            // --- entry can be moved? ------------------------------
                       
            // --- update master entry ------------------------------
            $update_query = "
				UPDATE ".TABLE_PREFIX."emails 
			    SET folder=".$params['move_to']." 
				WHERE mail_id='".$params['entry_id']."'";
			//echo $update_query;
            if (!$this->ExecuteQuery ($update_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                              
			// --- move master email file ---------------------------                              
			$maildir = EMAIL_PATH."/".$_SESSION['user_id'];
			@mkdir ($maildir, 0700);
			$maildir_to = EMAIL_PATH."/".$_SESSION['user_id']."/".$params['move_to'];
			@mkdir ($maildir_to, 0700);

			rename ($maildir."/1/".$params['entry_id'], $maildir_to."/".$params['entry_id']);
			                                    
			// --- update dependent entries -------------------------			                                    
            $select_query = "
				SELECT mail_id FROM ".TABLE_PREFIX."emails 
				WHERE 
					master_id='".$params['entry_id']."' AND
					owner='".$_SESSION['user_id']."'";
			//echo $select_query;
            if (!$res = $this->ExecuteQuery ($select_query, 'mysql_error', __FILE__, __LINE__)) return "failure";

			while ($row = mysql_fetch_array($res)) {
				$update_query = "
					UPDATE ".TABLE_PREFIX."emails 
				    SET folder=".$params['move_to']." 
					WHERE mail_id=".$row['mail_id'];
				//echo $update_query;
	            if (!$this->ExecuteQuery ($update_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
	                              
				// --- move dependend email file ------------------------                              
				rename ($maildir."/1/".$row['mail_id'], $maildir_to."/".$row['mail_id']);
			}		

            return "success";
        }            

      /**
        * deletes entry
        *
        * If there are any problems, examine model->error_msg and model->info_msg. 
        * 
        * @access       private
        * @param        array holds request variables
        * @return       string success on success, otherwise failure
        * @since        0.5.0
        * @version      0.5.0
        */
        function delete_from_trash (&$params) {
            global $db_hdl, $logger;
            
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);
            
            // --- assertions ---------------------------------------
            assert ('(int)$params["entry_id"] > 0');
            assert ('(int)$params["move_to"] == -1');
            
            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);            
                                                                    
            // --- "delete" master entry ------------------------------
            $update_query = "
				UPDATE ".TABLE_PREFIX."emails 
				SET deleted='1'
				WHERE mail_id='".$params['entry_id']."'";
            if (!$this->ExecuteQuery ($update_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
                              
			// --- delete master email file ---------------------------                              
			$maildir = EMAIL_PATH."/".$_SESSION['user_id'];
			unlink ($maildir."/0/".$params['entry_id']);
			                                    
			// --- delete dependent entries -------------------------			                                    
            $select_query = "
				SELECT mail_id FROM ".TABLE_PREFIX."emails 
				WHERE 
					master_id='".$params['entry_id']."' AND
					owner='".$_SESSION['user_id']."'";
			//echo $select_query;
            if (!$res = $this->ExecuteQuery ($select_query, 'mysql_error', __FILE__, __LINE__)) return "failure";

			while ($row = mysql_fetch_array($res)) {
				$del_query = "
					DELETE FROM ".TABLE_PREFIX."emails 
					WHERE mail_id=".$row['mail_id'];
				//echo $del_query;
	            if (!$this->ExecuteQuery ($del_query, 'mysql_error', __FILE__, __LINE__)) return "failure";
	                              
				// --- delete dependend email file ------------------------                              
				unlink ($maildir."/0/".$row['mail_id']);
				//rename ($maildir."/1/".$row['mail_id'], $maildir_to."/".$row['mail_id']);
			}		

            return "success";
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
        * 
        * @since        0.5.0
        * @version      0.5.0
        */
		function initialise_by_overview ($overview, $account_id) {
			$this->reset();
			
			// --- handle subject -----------------------------------
			if (isset($overview->subject)) {
			    $subject  = $this->My_imap_mime_header_decode($overview->subject);
			    $this->entry['subject']->set ($subject);
			}
			else {
			    $this->maillog .= "no subject given\n";
			    $this->entry['subject']->set ('<i>'.translate('no subject given').'</i>');  
			}    
			
			$this->entry['from']->set    (mysql_escape_string ($this->My_imap_mime_header_decode($overview->from)));
			
			// --- handle sent date ---------------------------------
			if (isset($overview->date)) {
			    $date  = $overview->date;
			    $stamp = strtotime ($date);
			    if ($stamp > 0)
    			    $this->entry['date']->set (date ("Y-m-d H:i", $stamp));
			    else {
			        $this->maillog .= date("Y.m.d H:i").' date conversion failed, set to 0'."\n";
                    $this->entry['date']->set (date ("Y-m-d H:i", 0));  
		        }
			}
			else {
			    $this->maillog .= date("Y.m.d H:i").' date unknown, set to 0'."\n";
			    $this->entry['date']->set (date ("Y-m-d H:i", 0));  
			}    
            
            // --- handle recipient ---------------------------------
            if (isset($overview->to)) {
			    $this->entry['to']->set      (mysql_escape_string ($this->My_imap_mime_header_decode($overview->to)));
			}
			else {
			    $this->maillog .= date("Y.m.d H:i").' unknown recipient'."\n";
			    $this->entry['to']->set ("unknown");  
			}    
            	
			$this->entry['size']->set     (ceil ($overview->size / 1024));
			//$this->entry['myUID']->set    ($overview->message_id);
			$this->entry['msg_nr']->set   ($overview->msgno);
			
			// according to RFC1939 the message id is no longer than 70 characters...
			// but some programs obviously don't care. 
			if (isset($overview->message_id)) {
    			$this->entry['myUID']->set    (substr ($overview->message_id, 0, 70));
    		}
			else {
			    $myUID = md5 ($overview->subject.date("His").$overview->from);
			    $this->maillog .= date("Y.m.d H:i").' unknown message id, set to '.$myUID."\n";
			    $this->entry['myUID']->set ($myUID);  
			}    
		}
		
		function reset () {
			$this->entry['header']->set   ('');
			$this->entry['structure']->set(null);
			$this->entry['body'] = array  ();
			$this->entry['subject']->set  ('');
			$this->entry['from']->set     ('');
			$this->entry['to']->set       ('');
			$this->entry['date']->set     ('');
			$this->entry['size']->set     (0);
			$this->entry['myUID']->set    ('');
			$this->entry['folder']->set   (1);
			$this->entry['msg_nr']->set   (0);
			//$this->entry['getlog']->set   ('');
		}
		
		function My_imap_mime_header_decode ($text) {
			$tmp     = imap_mime_header_decode($text);
			$retstr  = "";
			for($q=0;$q<count($tmp);$q++) {
				$retstr    .= $tmp[$q]->text;
			}
			return $retstr;
		}
		
		function parse_stream ($conn, $msgnr, $header, $structure) {

			// parse stream, set element counter to 1
			$this->counter = 1;
			$this->entry['imap_stream']->set ($conn);
			$this->entry['header']->set      ($header);
			$this->entry['structure']->set   ($structure);
			
			$this->maillog .= "Type: ".$structure->type."\n";
						
			if ($structure->type != TYPEMULTIPART) {
				
				$dummy = imap_fetchbody ($conn, $msgnr, 1);
				
				$this->body[$this->counter]['content']     = $dummy;
				$this->body[$this->counter]['bodytype']    = $structure->type;
				$this->body[$this->counter]['encoding']    = $structure->encoding;
				$this->body[$this->counter]['subtype']     = $structure->subtype;
				
				(isset($structure->description)) ?
					$this->body[$this->counter]['description'] = $structure->description :
					$this->body[$this->counter]['description'] = "";
				(isset($structure->disposition)) ?
					$this->body[$this->counter]['disposition'] = $structure->disposition :
					$this->body[$this->counter]['disposition'] = "";

				$this->body[$this->counter]['parameters']  = $structure->parameters;

				//$this->body[$this->counter]['dparameters'] = $this->structure->dparameters;
				(isset($structure->dparameters)) ?
					$this->body[$this->counter]['dparameters'] = $structure->dparameters :
					$this->body[$this->counter]['dparameters'] = "";

				$this->body[$this->counter]['parse_result'] = "";
				/*$body[$counter]['parse_result'] .= "bodytype:      ".$this->map_primary_body_type($this->body[$this->counter]['bodytype'])."<br>";
				$body[$counter]['parse_result'] .= "Encoding:      ".$this->map_encoding($this->body[$this->counter]['encoding'])."<br>";
				$body[$counter]['parse_result'] .= "subtype:       ".$this->body[$this->counter]['subtype']."<br>";
				$body[$counter]['parse_result'] .= "description:   ".$this->body[$this->counter]['description']."<br>";
				$body[$counter]['parse_result'] .= "disposition:   ".$this->body[$this->counter]['disposition']."<br>";
				$body[$counter]['parse_result'] .= "parameters:    ".$this->svar_dump($this->body[$this->counter]['parameters'])."<br>";
				$body[$counter]['parse_result'] .= "dparameters:   ".$this->svar_dump($this->body[$this->counter]['dparameters'])."<br>";
				*/
				$this->counter++;
			}
			else {
				//echo "Multipart mail";	
			}
			
			$this->parse_stream_rek ($structure, $msgnr, 0, 0);
		}
		
		function parse_stream_rek ($mimeobj, $msgnr, $depth, $section) {

			if (!isset ($mimeobj->parts)) {
				$this->maillog .= "parts: none\n";
				return;
			}
			
	        for($x = 0; $x < count($mimeobj->parts); $x++) {
	        	$this->maillog .= "Parsing Part: ".$x."\n";
				if($section == 0) 
					$nsection = $x + 1;
               	else if(($pos = strrpos($section, ".")) && $mimeobj->parts[0]->type != TYPEMULTIPART)
                       $nsection = substr($section, 0, $pos) . "." . ($x + 1);
               	else
                     $nsection = $section;

				if(isset($mimeobj->parts[$x]->parts) && count($mimeobj->parts[$x]->parts)) {
					if(!($mimeobj->parts[$x]->type == TYPEMESSAGE && $mimeobj->parts[$x]->parts[0]->type == TYPEMULTIPART))
                		$nsection .= ".0";
                	else
                		$nsection .= "";
            	}

            	$dummy = imap_fetchbody ($this->entry['imap_stream']->get(), $msgnr, $nsection);
            	
            	if (strlen($dummy) > 0) {
            		
					$this->body[$this->counter]['content']     = $dummy;
					$this->body[$this->counter]['bodytype']    = $mimeobj->parts[$x]->type;
					$this->body[$this->counter]['encoding']    = $mimeobj->parts[$x]->encoding;
					$this->body[$this->counter]['subtype']     = $mimeobj->parts[$x]->subtype;
					
					(isset($mimeobj->parts[$x]->description)) ?
						$this->body[$this->counter]['description'] = $mimeobj->parts[$x]->description :
						$this->body[$this->counter]['description'] = "";

					//$this->body[$this->counter]['disposition'] = $mimeobj->parts[$x]->disposition;
					(isset($mimeobj->parts[$x]->disposition)) ?
						$this->body[$this->counter]['disposition'] = $mimeobj->parts[$x]->disposition :
						$this->body[$this->counter]['disposition'] = "";
					$this->body[$this->counter]['parameters']  = $mimeobj->parts[$x]->parameters;
					//$this->body[$this->counter]['dparameters'] = $mimeobj->parts[$x]->dparameters;
					(isset($mimeobj->parts[$x]->dparameters)) ?
						$this->body[$this->counter]['dparameters'] = $mimeobj->parts[$x]->dparameters :
						$this->body[$this->counter]['dparameters'] = "";
					$this->body[$this->counter]['filename']    = $this->get_filename_from_parameters ($mimeobj->parts[$x]);
					if ($this->body[$this->counter]['filename'] == "")
						$this->body[$this->counter]['filename'] = "Multipart ".$x;

	            	/*$this->body[$this->counter]['parse_result'] = "";
					$this->body[$this->counter]['parse_result'] .= "bodytype:      ".$this->map_primary_body_type($this->body[$this->counter]['bodytype'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "Encoding:      ".$this->map_encoding($this->body[$this->counter]['encoding'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "subtype:       ".$this->body[$this->counter]['subtype']."<br>";
					$this->body[$this->counter]['parse_result'] .= "description:   ".$this->body[$this->counter]['description']."<br>";
					$this->body[$this->counter]['parse_result'] .= "disposition:   ".$this->body[$this->counter]['disposition']."<br>";
					$this->body[$this->counter]['parse_result'] .= "parameters:    ".$this->svar_dump($this->body[$this->counter]['parameters'])."<br>";
					$this->body[$this->counter]['parse_result'] .= "dparameters:   ".$this->svar_dump($this->body[$this->counter]['dparameters'])."<br>";
					*/
	            	$this->counter++;
				}
				$this->parse_stream_rek ($mimeobj->parts[$x], $msgnr, $depth + 1, $nsection);
			}
		}
		
		function get_filename_from_parameters ($mimeobj) {
			$ret_str = "";
			for ($i=0; $i < count ($mimeobj->parameters); $i++) {
				if ($mimeobj->parameters[$i]->attribute == "NAME")
					return $mimeobj->parameters[$i]->value;
			}
			if (!isset ($mimeobj->dparameters)) return "";
			for ($i=0; $i < count ($mimeobj->dparameters); $i++) {
				if ($mimeobj->parameters[$i]->attribute == "FILENAME")
					return $mimeobj->parameters[$i]->value;
			}
			return "";
		}
		
		function is_html ($text) {
			if (strlen ($this->escapeBadHTML($text)) < strlen ($text)) {
				if (strlen (str_replace("<html>","",$text)) < strlen ($text)) { // ist ein <html> - tag vorhanden?
					return true;
				}
			}
			return false;
		}
		
		function escapeBadHTML($str) {
  			$allowed = "br|b|i|p|u|a|http|mailto";
  			$str = preg_replace("/<((?!\/?($allowed)\b)[^>]*)>/xis", "", $str);
  			return $str;
		}
		
		// Workaround to force brower to flush
		function force_output() {
			for ($i=0; $i<100; $i++)
				echo "<!--<img border='img/shim.gif' height=1 width=1 border=0>-->\n";
		}
    }   

?>