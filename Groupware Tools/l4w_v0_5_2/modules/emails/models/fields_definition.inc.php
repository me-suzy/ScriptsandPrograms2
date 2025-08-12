<?php

  /**
    * $Id: fields_definition.inc.php,v 1.14 2005/07/14 07:32:17 carsten Exp $
    *
    * controls additional field definitions
    *
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      email
    */

	$this->entry['command']      = new easy_string  ('');      // holding the next command to execute

	// --- accounts -------------------------------------------------
	
	$this->entry['account_id']   = new easy_integer  (null, 1);      

	$this->entry['host']         = new easy_string  ('');      
    $this->entry['host']->set_empty_allowed (false);
    $this->entry['host']->class  = "focus";

	$this->entry['login']        = new easy_string  ('');     
    $this->entry['login']->set_empty_allowed (false);
    $this->entry['login']->class = "focus";
    
    $this->entry['pass']         = new easy_string  ('');      
    $this->entry['pass2']        = new easy_string  ('');      

    $this->entry['port']         = new easy_integer (110);      

	$this->entry['type']         = new easy_select   (
        array ("pop3"  => 'POP3',
               "imap"  => 'IMAP',
               "nntp"  => 'NNTP'
              ),  1, 'pop3');
              
    //$this->entry['salutation']->class        = "formular_small";
	$this->entry['use_ssl']      = new easy_string  ('0');   	
	$this->entry['active']       = new easy_string  ('0');   	

    // --- output ---------------------------------------------------
    $this->entry['log']          = new easy_string ('');
    $this->entry['alerts']       = new easy_string ('');
    $this->entry['error']        = new easy_string ('');
    
    $this->entry['goto_tab']     = new easy_integer (1,1,10);
    
    // --- single mail ----------------------------------------------
    
    $this->entry['mail_id']      = new easy_integer (0,0);
    $this->entry['imap_stream']  = new easy_resource (null);
	$this->entry['header']       = new easy_string ('');
	$this->entry['structure']    = new easy_string (null);
	//$this->entry['body']         = array ();
	$this->entry['subject']      = new easy_string  ('');
	$this->entry['subtype']      = new easy_string  ('');
	
	$this->entry['from']         = new easy_string  ('');
	$this->entry['to']           = new easy_string  ('');
	$this->entry['cc']           = new easy_string  ('');
	$this->entry['bcc']          = new easy_string  ('');
	$this->entry['message']      = new easy_string  ('');
	
	$this->entry['date']         = new easy_string  ('');
	$this->entry['size']         = new easy_integer (0,0);
	$this->entry['myUID']        = new easy_string  ('');
	$this->entry['folder']       = new easy_integer (1);
	$this->entry['getlog']       = new easy_string  ('');
	$this->entry['msg_nr']       = new easy_integer (0);

	$this->entry['attachment']   = new easy_string  ('0');    // true, false
	$this->entry['attachments']  = new easy_resource (null);  // mysql resource
	
	$this->entry['content']      = new easy_string  ('');
	//$this->entry['counter']      = new easy_integer (0,0);
?>