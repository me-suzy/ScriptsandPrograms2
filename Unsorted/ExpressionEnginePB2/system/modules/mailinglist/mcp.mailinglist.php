<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 - 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: mcp.mailinglist.php
-----------------------------------------------------
 Purpose: Basic Mailint List class - CP
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Mailinglist_CP {

    var $version = '1.0';


    // -------------------------
    //  Constructor
    // -------------------------
    
    function Mailinglist_CP( $switch = TRUE )
    {
        global $IN;
        
        if ($switch)
        {
            switch($IN->GBL('P'))
            {
                case 'view'			:  $this->view_mailing_list();
                    break;
                case 'del_confirm'	:  $this->delete_confirm();
                	break;
                case 'delete'		:  $this->delete_email_addresses();
                	break;
                case 'subscribe'	:  $this->subscribe_email_addresses();
                	break;
                default				:  $this->mailinglist_home();
                    break;
            }
        }
    }
    // END
    
    
    // -------------------------
    //  Mailinglist Home Page
    // -------------------------
    
    function mailinglist_home($message = '')
    {
        global $DSP, $DB, $LANG;
                        
        $DSP->title = $LANG->line('ml_mailinglist');
        $DSP->crumb = $LANG->line('ml_mailinglist');        
    
        $DSP->body  = $DSP->heading($LANG->line('ml_mailinglist'));   
        
        
        if ($message != '')
        {
			$DSP->body .= $DSP->qdiv('success', $message);
        }
    
        $query = $DB->query("SELECT count(*) AS count FROM exp_mailing_list");
    
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('ml_total_in_mailinglist').NBS.NBS.$query->row['count'], 5));
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->heading($DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=mailinglist'.AMP.'P=view', $LANG->line('ml_view_mailinglist')), 5));
				        
        $DSP->body .= $DSP->form('C=modules'.AMP.'M=mailinglist'.AMP.'P=view');
        
        $DSP->body .= $DSP->qdiv('', BR);
        
        $DSP->body .= $DSP->div('box450');
        
		$DSP->body .= $DSP->heading($LANG->line('ml_email_search') ,5);

		$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('ml_email_search_cont', 'email'));

		$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->input_text('email', '', '35', '100', 'input', '100%'));
        
        $DSP->body .= $DSP->input_submit($LANG->line('submit'));
        
        $DSP->body .= $DSP->div_c();
                        
        $DSP->body .= $DSP->form_c();   

        $DSP->body .= $DSP->qdiv('', BR);
        
        $DSP->body .= $DSP->form('C=modules'.AMP.'M=mailinglist'.AMP.'P=subscribe');
        
        $DSP->body .= $DSP->div('box450');
        
		$DSP->body .= $DSP->heading($LANG->line('ml_add_email_addresses') ,5);
		
		$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('ml_add_email_addresses_cont', 'addresses'));

        $DSP->body .= $DSP->qdiv('', $DSP->input_textarea('addresses', '', 10, 'textarea', '100%'));
        
        $DSP->body .= $DSP->input_submit($LANG->line('submit'));
        
        $DSP->body .= $DSP->div_c();
                        
        $DSP->body .= $DSP->form_c();   
    }
    // END
    
   
    // -------------------------
    //  Subscribe
    // -------------------------
    
    function subscribe_email_addresses()
    {
    	global $REGX, $DB, $FNS, $DSP, $LANG;
    	
    	if ($_POST['addresses'] == '')
    	{
    		return $this->mailinglist_home();	
    	}

		// ------------------------------
		//  Fetch existing addresses
		// ------------------------------
    	
    	$query = $DB->query("SELECT email FROM exp_mailing_list");
    	
    	$current = array();
    	
		if ($query->num_rows > 0)
		{
			foreach ($query->result as $row)
			{
				$current[$row['email']] = TRUE;	
			}
		} 
		
		// ------------------------------
		//  Clean up submitted addresses
		// ------------------------------
		
    	$email  = trim($_POST['addresses']);
    	$email  = preg_replace("/[,|\|]/", "", $email);
    	$email  = preg_replace("/[\r\n|\r|\n]/", " ", $email);
    	$email  = preg_replace("/\t+/", " ", $email);
    	$email  = preg_replace("/\s+/", " ", $email);
    	$emails = explode(" ", $email);
		
		// ------------------------------
		//  Insert new addresses
		// ------------------------------
		
		$good_email = 0;
		$dup_email	= 0;
		
    	$bad_email  = array();
		
		foreach($emails as $addr)
		{
			if (ereg('\<(.*)\>', $addr, $match))
				$addr = $match['1'];
		   
			if ( ! $REGX->valid_email($addr))
			{
				$bad_email[] = $addr;
				continue;
			}
			
			if (isset($current[$addr]))
			{
				$dup_email++;
		    	continue;
		    }
		    
			$DB->query("INSERT INTO exp_mailing_list (user_id, authcode, email) VALUES ('', '".$FNS->random('alpha', 10)."', '".$DB->escape_str($addr)."')");			
		
			$good_email++;
		}
    
    
    	if (count($bad_email) == 0)
    	{	
    		return $this->mailinglist_home($LANG->line('ml_emails_imported'));	
    	}
    	else
    	{
			$DSP->title = $LANG->line('ml_mailinglist');
			
			$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=mailinglist', $LANG->line('ml_mailinglist'));
		
			$DSP->body  = $DSP->heading($LANG->line('ml_mailinglist'));
						
			$DSP->body .= $DSP->qdiv('', BR);

			$DSP->body .= $DSP->heading($LANG->line('ml_total_emails_imported').NBS.NBS.$good_email, 5);
    	
			$DSP->body .= $DSP->heading($LANG->line('ml_total_duplicate_emails').NBS.NBS.$dup_email, 5);
    	
			$DSP->body .= $DSP->qdiv('', BR);

			$DSP->body .= $DSP->heading($LANG->line('ml_bad_email_heading'), 5);
			
			foreach ($bad_email as $val)
    		{
    			$DSP->body .= $DSP->qdiv('', $val);
    		}
    	}
    }
    // END
    
    
    
    
    // -------------------------
    //  View Mailinglist
    // -------------------------
    
    function view_mailing_list()
    {
        global $IN, $DSP, $DB, $LANG;
                
        $row_limit = 100;
        $paginate  = '';
        $row_count = 0;
                
        $DSP->title = $LANG->line('ml_mailinglist');
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=mailinglist', $LANG->line('ml_mailinglist'));
		$DSP->crumb .= $DSP->crumb_item($LANG->line('ml_view_mailinglist'));

        $DSP->body  = $DSP->heading($LANG->line('ml_mailinglist'));   
        
        
		$sql = "SELECT user_id, email FROM exp_mailing_list";
		
		$email = $IN->GBL('email', 'GP');		
              
		if ($email)               
        {
			$email = urldecode($email);
        
        	$sql .= " WHERE email LIKE '%".$email."%'";
        }
    
		$query = $DB->query($sql);    
		
		if ($query->num_rows == 0)
		{
			$DSP->body	.=	$DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('ml_no_results')));             
			
			return;
		}		
    
		 if ($query->num_rows > $row_limit)
		 { 
			$row_count = ( ! $IN->GBL('row')) ? 0 : $IN->GBL('row');
						
			$url = 'C=modules'.AMP.'M=mailinglist'.AMP.'P=view';
			
			if ($email)
			{
				$url .= AMP.'email='.urlencode($email);
			}
		 
			$paginate = $DSP->pager(  $url,
									  $query->num_rows, 
									  $row_limit,
									  $row_count,
									  'row'
									);
			 
			$sql .= " LIMIT ".$row_count.", ".$row_limit;
			
			$query = $DB->query($sql);    
    	}
    	
    
        $DSP->body	.=	$DSP->toggle();
                
        $DSP->body	.=	$DSP->form('C=modules'.AMP.'M=mailinglist'.AMP.'P=del_confirm', 'target');
    
        $DSP->body	.=	$DSP->table('tableBorder', '0', '0', '').
					  	$DSP->tr().
					  	$DSP->td('tablePad'); 

        $DSP->body	.=	$DSP->table('', '0', '0', '100%').
						$DSP->tr().
						$DSP->table_qcell('tableHeadingBold', 
											array(
													NBS,
													$LANG->line('ml_email_address'),
													$DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete').NBS.NBS
												 )
											).
						$DSP->tr_c();
		
		$row_count++;
		$i = 0;

		foreach ($query->result as $row)
		{				
			$DSP->body .= $DSP->table_qrow( ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo', 
											array(
													$row_count,
													$DSP->mailto($row['email']),
													$DSP->input_checkbox('toggle[]', $row['user_id'])
												  )
											);
			$row_count++;			
		}
		
		$DSP->body .= $DSP->table_qrow( ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo', 
										array(
												NBS,
												NBS,
												$DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.'<b>'.$LANG->line('delete').'</b>'.NBS.NBS
											  )
										);
			
        $DSP->body	.=	$DSP->table_c(); 

        $DSP->body	.=	$DSP->td_c().  
						$DSP->tr_c().     
						$DSP->table_c();
    
    
    	if ($paginate != '')
    	{
    		$DSP->body .= $DSP->qdiv('itemWrapper', $paginate);
    	}
    	
		$DSP->body	.=	$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')));             
        
        $DSP->body	.=	$DSP->form_c();
    }
    // END
    
  
    
    // -------------------------------------------
    //   Delete Confirm
    // -------------------------------------------    

    function delete_confirm()
    { 
        global $IN, $DSP, $LANG;
        
        
        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->view_mailing_list();
        }
        
        $DSP->title = $LANG->line('ml_mailinglist');
		$DSP->crumb  = $DSP->anchor(BASE.AMP.'C=modules'.AMP.'M=mailinglist', $LANG->line('ml_mailinglist'));
		$DSP->crumb .= $DSP->crumb_item($LANG->line('ml_view_mailinglist'));

        $DSP->body	.=	$DSP->form('C=modules'.AMP.'M=mailinglist'.AMP.'P=delete');
        
        $i = 0;
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
                $DSP->body	.=	$DSP->input_hidden('delete[]', $val);
                
                $i++;
            }        
        }
        
		$DSP->body .= $DSP->heading($LANG->line('ml_delete_confirm'));
		$DSP->body .= $DSP->qdiv('defaultBold', $LANG->line('ml_delete_question'));
		$DSP->body .= $DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'));
		$DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('delete')));
		$DSP->body .= $DSP->qdiv('alert',$DSP->div_c());
		$DSP->body .= $DSP->form_c();
    }
    // END   
    
    
    
    // -------------------------------------------
    //   Delete Email Addresses
    // -------------------------------------------    

    function delete_email_addresses()
    { 
        global $IN, $DSP, $LANG, $SESS, $DB;
        
        
        if ( ! $IN->GBL('delete', 'POST'))
        {
            return $this->view_mailing_list();
        }

        $ids = array();
                
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'delete') AND ! is_array($val))
            {
                $ids[] = "user_id = '".$val."'";
            }        
        }
        
        $IDS = implode(" OR ", $ids);
        
        $DB->query("DELETE FROM exp_mailing_list WHERE ".$IDS);
    
        $message = (count($ids) == 1) ? $LANG->line('ml_email_deleted') : $LANG->line('ml_emails_deleted');

        return $this->mailinglist_home($message);
    }
    // END    
     
        
    

    // -------------------------
    //  Module installer
    // -------------------------

    function mailinglist_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Mailinglist', '$this->version', 'y')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'insert_new_email')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'authorize_email')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Mailinglist', 'unsubscribe')";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    // -------------------------
    //  Module de-installer
    // -------------------------

    function mailinglist_module_deinstall()
    {
        global $DB;    

        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Mailinglist'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";        
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Mailinglist'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Mailinglist'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Mailinglist_CP'";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;
    }
    // END

}
// END CLASS
?>