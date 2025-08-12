<?php
/*
 * Created on 05.04.2005
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
     function fireEvent (&$model, $reference, $event, $type, $object_id) {
        global $logger;
        
		$logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

        // get event id 
        $query = "SELECT event_id, default_action 
                  FROM ".TABLE_PREFIX."events 
                  WHERE object_type='$reference' AND
                        event='$event' AND
                        event_type='$type'";

        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);

        if (mysql_num_rows($res) != 1) {
            $logger->log ($query, 4);
            //$model->error_msg = 
            //    "Internal event error in ".__FILE__." ".__LINE__." Ref.: ".$reference.", Event: ".$event.", Type:".$type." #:".mysql_num_rows($res);
            //$model->info_msg .= translate ('event fired the first time');
            $query = "
                INSERT INTO ".TABLE_PREFIX."events
                    (object_type, event, description, added_by, added_date, event_type)
                VALUES (
                    '$reference',
                    '$event',
                    'added as event was missing',
                    ".$_SESSION['user_id'].",
                    now(),
                    'system'                
                )
                ";
            //echo __FILE__;
            //echo $query;
            //die ();                
            $res = mysql_query ($query);
	        logDBError (__FILE__, __LINE__, mysql_error(), $query);
            if (mysql_error() != '')
                $this->info_msg .= translate ('event was not added');
            else 
                fireEvent ($model, $reference, $event, $type, $object_id);
            return;
        }         
        $row = mysql_fetch_array($res);
    
    	// check Watchlist for registered users    
        $cWResult = checkWatchList ($row['event_id'], $reference, $object_id, $model);   
    
    	// check default action
    	if ($row['default_action'] > 0) {
    		// perform default Action
    		$rc  = performAction (
    			$row['default_action'], // the action to perform (see table actions)
    			$reference, 			// what kind of object
    			$object_id, 			// object ID
    			$row['event_id'], 	    // which event 
    			null,					// no watcher given
                $model);
    	    return $rc;
    	}	
    	
    	return $cWResult;
    }    
    
    function checkWatchList ($event_id, $reference, $object_id, &$model) {

        // get watchers 
        $query = "SELECT * 
                  FROM ".TABLE_PREFIX."eventwatcher 
                  WHERE event_id='$event_id'";
        $res = mysql_query ($query);
        //echo $query;
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$error = null;
		
        while ($row = mysql_fetch_array($res)) {
            // check if the watcher has access to object
            $meta_query = "SELECT * 
                           FROM ".TABLE_PREFIX."metainfo 
                           WHERE  object_type='$reference' AND
                                  object_id='$object_id'";
            //echo "<br>-------------<br>".$meta_query."<br>";
            $meta_res = mysql_query ($meta_query);
	       	logDBError (__FILE__, __LINE__, mysql_error(), $meta_query);
            $meta_row = mysql_fetch_array($meta_res);
	       	if (!user_may_read($meta_row['owner'], $meta_row['grp'], $meta_row['access_level'], $row['watcher']))
	       	   continue;
	       	//echo "hier1<br>";
	       	// check if watch is not restricted
	       	if ($row['restrict_to_user'] > 0 && $row['restrict_to_user'] != $meta_row['owner'])
	       	   continue;              
	       	//echo "hier2<br>";
	       	if ($row['restrict_to_grp'] > 0 && $row['restrict_to_grp'] != $meta_row['grp'])
	       	   continue; 
            //echo "hier3<br>";
	       	// ok, so perform action
	       	$rc  = performAction ($row['perform_action'], $reference, $object_id, $event_id, $row['watcher'],$model);
	       	//echo $row['perform_action'];
	       	//var_dump ($rc);
        	if (!is_null($rc)) {
        		$error .= $rc;
        	}
        }  
        if (!is_null($error))	
        	return $error;

        return null;  
    }    
    
    function performAction ($action, $reference, $object_id, $event_id, $watcher, &$model) {
        global $easy, $logger;
        
        // get action 
        $query = "SELECT * 
                  FROM ".TABLE_PREFIX."actions 
                  WHERE action_id=".$action;
        //echo "-".$query;
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row = mysql_fetch_array($res);
        $logger->log ("Eventmanager: Calling function ".$row['user_function'],7);
        return call_user_func($row['user_function'], array ($reference, $object_id, $event_id, $watcher, &$model));
    }        
    
    function getTextFromTemplate ($reference, $event_id, $name) {

        // get event from event_id
        $query = "SELECT event FROM ".TABLE_PREFIX."events 
                  WHERE event_id=".$event_id;
        echo $query;
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
        $row = mysql_fetch_array($res);
        
        require_once ('../../modules/events/models/events_mdl.php');
        $events_model  = new events_model (null, null);
        //$events_model->entry['pathoffset']->set($pathoffset);
        
        list ($subpath, $path, $name) = $events_model->getNameAndPath ($reference, $name, $row['event']);

        if (!$fh = fopen ($path.$name, "rb"))
            return translate ('could not open')." ".$path.$name;
          
        $content = fread ($fh, filesize ($path.$name));
        fclose ($fh);
        
        // replacement of variables
        $link = "links not implemented yet";
		$content = substitute ($content, array (
									'###entry_type###' => translate ($reference),
									'###date###'       => date("d.m.Y H:i"),
									'###user###'       => get_username_by_user_id($_SESSION['user_id']),
									'###link2entry###' => $link
								));
        
        return $content;
    }    
    
    function add_news ($params, $alt_text = null) {

        list ($reference, $object_id, $event, $watcher, $model) = $params;    

        // --- get info about event ---------------------------------
        $query = "
            SELECT * FROM ".TABLE_PREFIX."events WHERE event_id=$event
            ";
        
        $res      = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row      = mysql_fetch_array ($res);
        $showlink = true;
        
        switch ($reference) {
            case "contact":
                $link  = "../../modules/contacts/index.php?command=show_contact";
                $link .= "&contact_id=$object_id";
                // !!! not good
                if ($event == 10) $showlink = false;
                break;
            default:
                $link = '#';    
        }
          
        $newstext = getTextFromTemplate ($reference, $event, 'news');
            
        /*$newstext  = translate ($reference).": ".$row['description']."<br>";
        if ($showlink)
            $newstext .= "<a href='$link'>".translate ('view')."</a>";
        
        if (!is_null($alt_text)) {
        	$newstext = $alt_text;	
        }*/	
        $query = "INSERT INTO ".TABLE_PREFIX."news (
                    creator, owner, headline, news, sentto
                  )    
                  VALUES (
                    0,
                    $watcher,
                    '".$row['description']."',
                    '".mysql_escape_string ($newstext)."',
                    $watcher
                  )";
        $res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
    	return null;
    }    
    
    function checkItemsToRemind ($params, $alt_text = null) {
		global $logger;
		
        list ($reference, $object_id, $event, $watcher, $model) = $params;    

        // --- get info about event ---------------------------------
        $query = "
            SELECT * FROM ".TABLE_PREFIX."events WHERE event_id=$event
            ";
        
        $res      = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row      = mysql_fetch_array ($res);
        $showlink = true;
        
        /*switch ($reference) {
            case "contact":
                $link  = "../../modules/contacts/index.php?command=show_contact";
                $link .= "&contact_id=$object_id";
                // !!! not good
                if ($event == 10) $showlink = false;
                break;
            default:
                $link = '#';    
        }*/
        echo $reference."/".$event;
        $remindertext = getTextFromTemplate ($reference, $event, 'reminders');

    	$logger->log ("Sending reminder to ".get_useremail_by_user_id($watcher), 4);
		
		$done = doSendEmail (
			get_useremail_by_user_id($watcher),
			get_useremail_by_user_id($_SESSION['user_id']),
			translate ('reminder'),
			$remindertext,
			$remindertext);
		
		if (!$done) {
	    	$logger->log ("Sending mail to ".get_useremail_by_user_id($watcher)." failed", 1);
			// sending mail did not succeed, so give the user a hint about this
			// id 38 adds "sending mail failed" news
			$failedtext  = translate ("sending mail failed").": <br><br>";
			$failedtext .= "[".date ("d.m.Y H:i:s")."]<br><br>"; 
			$failedtext .= $remindertext;
			add_news (array ('email', null, '38', $watcher,$model), $failedtext);
			$model->entry['error_msg'] = $failedtext;
			return $failedtext;
		}            
    }
    
    function send2jabber ($params) {
        
        list ($reference, $object_id, $event, $watcher, $model) = $params;    
        
        // --- get info about event ---------------------------------
        $query = "
            SELECT * FROM ".TABLE_PREFIX."events WHERE event_id=$event
            ";
        $res      = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$row      = mysql_fetch_array ($res);
        $showlink = false;
        
        /*switch ($reference) {
            case "contact":
                $link  = "../../modules/contacts/index.php?command=show_contact";
                $link .= "&contact_id=$object_id";
                // !!! not good
                if ($event == 10) $showlink = false;
                break;
            default:
                $link = '#';    
        }*/
            
        $newstext  = "by user ".get_username_by_user_id($_SESSION['user_id']); //." (title: ".$row['headline'].")";
        //if ($showlink)
        //    $newstext .= "<a href='$link'>".translate ('view')."</a>";
        
        $error_handling = error_reporting(E_ERROR);
             
        $query = "SELECT jabber_id, jabber_pass 
				  FROM ".TABLE_PREFIX."user_details
				  WHERE user_id=".$watcher;
        $jab_res = mysql_query ($query);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);
		$jabber_row = mysql_fetch_array($jab_res); 
		     
        require_once('../../extern/jabber/lib/class.jabber.php');

        $JABBER = new Jabber;
        $JABBER->server         = JABBER_SERVER;
        $JABBER->port           = JABBER_PORT;
        $JABBER->username       = $jabber_row['jabber_id'];
        $JABBER->password       = $jabber_row['jabber_pass'];
        $JABBER->resource       = 'leads4web4';
        
        $JABBER->Connect() or die('Couldn\'t connect!');
        $JABBER->SendAuth() or die('Couldn\'t authenticate!');
        
        //session_start();
        /*$query   = "SELECT jabber_id FROM ".TABLE_PREFIX."user_details WHERE user_id=".$watcher;
        $jab_res = mysql_query ($query);
        $jab_row = mysql_fetch_array($jab_res);
		logDBError (__FILE__, __LINE__, mysql_error(), $query);*/
        
        $success   = $JABBER->SendMessage($jab_row['jabber_id'], 'normal', NULL,
                            array(
                             	   "subject" => $row['description'],
                                   "body"    => mysql_escape_string($newstext),
                             	   "thread"  => session_id(),
                                  )
                            );    
        $JABBER->Disconnect();
        error_reporting($error_handling );
        //echo "<pre>".mysql_escape_string($newstext)."</pre>";
    	return null;
    } 

   /**
    *  An entry has been assigned to a new carer. Email a notification to
    *  the new owner of the entry.
    */
 	function entryAssignedEvent (&$params) {
        global $easy, $logger;
        
        list ($reference, $object_id, $event, $watcher, $model) = $params;   
        
        // find out to whom the email should be sent to:
        $vals = get_entries_for_primary_key (
        			"metainfo",
        			array ("object_type" => $reference,
        			       "object_id"   => $object_id)	
        		);
        $watcher = $vals['owner'];
        $logger->log ("Sending mail to ".get_useremail_by_user_id($watcher), 4);
        $params[4]->error_msg .= sendmail (array ($reference, $object_id, $event, $watcher, $model));
 	}
 	
    function sendmail ($params) {
    	global $easy, $logger;
    	
    	//var_dump ($params);
    	list ($reference, $object_id, $event, $watcher, $model) = $params;    
    	
    	$tpl_query = "SELECT template, subject FROM ".TABLE_PREFIX."events WHERE event_id=".$event;
    	//echo $tpl_query;
    	$tpl_res   = mysql_query ($tpl_query);
    	$tpl_row   = mysql_fetch_array($tpl_res);

		$subject   = translate ($tpl_row['subject']);    	
		
    	if ($tpl_row['template'] != '') {
			$file_txt  = "../../templates/mail/english/".$tpl_row['template'].".txt";
			$file_html = "../../templates/mail/english/".$tpl_row['template'].".html";
			if (!$body_txt = file_get_contents($file_txt)) {
	    		$body_txt = "Error reading template file for event ".$event."<br><br>";			
			}	
			else {
				
				$body_html = '';
				if (!$body_html = @file_get_contents($file_html)) {
		    		$body_html = $body_txt;	
				}	
				$link  = "http://".$_SERVER['HTTP_HOST'];
				$link .= dirname($_SERVER['PHP_SELF']);
				$link .= "/index.php?command=edit_entry&entry_id=".$object_id;
				
				switch ($reference) {
            		case "contact":
						$headline  = "to be done";   
						//$link     .= "entry_id=".$;             		
	            default:
		            $headline = "to be specified";  
                	//$link    .= '';    
        		}

				$body_txt  = substitute ($body_txt, array (
											'###entry_type###' => translate ($reference),
											'###date###'       => date("d.m.Y H:i"),
											'###user###'       => get_username_by_user_id($_SESSION['user_id']),
											'###headline###'   => $headline,
											'###openlink###'   => $link
										));
				
				$body_html = substitute ($body_html, array (
											'###entry_type###' => translate ($reference),
											'###date###'       => date("d.m.Y H:i"),
											'###user###'       => get_username_by_user_id($_SESSION['user_id']),
											'###headline###'   => $headline,
											'###openlink###'   => $link
										));

			}	
    	}
    	else {
    		$body_txt  = "No template file found for event ".$event."<br><br>";
    		$body_html = "No template file found for event ".$event."<br><br>";
    	}
    	
    	$logger->log ("Sending mail to ".get_useremail_by_user_id($watcher), 4);
		$done = doSendEmail (
			get_useremail_by_user_id($watcher),
			get_useremail_by_user_id($_SESSION['user_id']),
			$subject,
			$body_html,
			$body_txt);
		
		if (!$done) {
	    	$logger->log ("Sending mail to ".get_useremail_by_user_id($watcher)." failed", 1);
			// sending mail did not succeed, so give the user a hint about this
			// id 38 adds "sending mail failed" news
			$failedtext  = translate ("sending mail failed").": <br><br>";
			$failedtext .= "[".date ("d.m.Y H:i:s")."]<br><br>"; 
			$failedtext .= $body_txt;
			add_news (array ('email', null, '38', $watcher,$model), $failedtext);
			$model->entry['error_msg'] = $failedtext;
			return $failedtext;
		}	

        return '';
    }
    
    function doSendEmail ($to, $cc, $subject, $body_html, $body_txt) {
    	
    	if (USE_PHP_MAIL_FUNCTION) {
			require_once ("../../extern/libmail/libmail.php");
			$m= new Mail;
			$m->From    (SMTP_FROM);
		    //$m->To      (get_useremail_by_user_id($watcher));
			$m->To      ($to);
			if (!is_null ($cc) && strlen($cc) > 0)
			    $m->Cc ($cc);
			$m->Subject ($subject);
			$m->Body($body_html);	// set the body
	     	//$m->Priority($prio) ;	// set the priority to Low
		    $done = $m->Send("text/html");	// send the mail
		}
		else {
		    require("../../extern/phpmailer/class.phpmailer.php");
			$mail = new PHPMailer();
			$mail->IsSMTP(); // telling the class to use SMTP
			$mail->IsHTML(true);
			$mail->Host = SMTP_HOST;
			$mail->From = SMTP_FROM;
			$mail->AddAddress($to);
			if (!is_null ($cc) && strlen($cc) > 0)
			    $mail->AddCc ($cc);
			//$mail->AddCustomHeader($add_headers);
			$mail->Subject  = $subject;
			$mail->Body     = $body_html;
			$mail->AltBody  = $body_txt;			
			$mail->WordWrap = 50;
			$done = $mail->Send();
		}
		return $done;
    }
?>
