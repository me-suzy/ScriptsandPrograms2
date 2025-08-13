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
 File: cp.specialty_tmp.php
-----------------------------------------------------
 Purpose: Special Purpose Templates
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Specialty_Templates {

	var $ignore = array('offline_template', 'message_template');
	

	// ---------------------------------
	//	Constructor
	// ---------------------------------

	function Specialty_Templates()
	{
		global $LANG;
		
	    $LANG->fetch_language_file('specialty_tmp');
	}
	// END



	// ---------------------------------
	//	Offline template
	// ---------------------------------
		
	function offline_template($message = '')
	{
		global $DSP, $DB, $REGX, $LANG, $PREFS;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
				
		$DSP->title = $LANG->line('offline_template');						 
		$DSP->crumb = $LANG->line('offline_template');	
		
		$DSP->body = $DSP->heading($LANG->line('offline_template'));
		
		$DSP->body .= $DSP->qdiv('', $LANG->line('offline_template_desc'));
		
		if ($message != '')
		{
			$DSP->body .= $DSP->qdiv('success', $message);
		}
		
		$query = $DB->query("SELECT template_data FROM exp_specialty_templates WHERE template_name = 'offline_template'");
		
        $DSP->body .= $DSP->form('C=admin'.AMP.'M=sp_templ'.AMP.'P=update_offline_template');
      
        $DSP->body .= $DSP->div('itemWrapper')  
					 .$DSP->input_textarea('template_data', $query->row['template_data'], '25', 'textarea', '100%')
					 .$DSP->div_c();
					 
		$DSP->body .= $DSP->input_submit($LANG->line('update'))
             		 .$DSP->form_c();
	}
	// END	
	
	
	// ---------------------------------
	//	Update Offline Template
	// ---------------------------------
		
	function update_offline_template()
	{
		global $DB, $DSP, $LANG;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! isset($_POST['template_data']))
        {
        	return FALSE;
        }
	
		$DB->query("UPDATE exp_specialty_templates SET template_data = '".$DB->escape_str($_POST['template_data'])."' WHERE template_name = 'offline_template'");
	
		$this->offline_template($LANG->line('template_updated'));
	}
	// END
	
	

	// ---------------------------------
	//	User Messages Template
	// ---------------------------------
		
	function user_messages_template($message = '')
	{
		global $DSP, $DB, $REGX, $LANG, $PREFS;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
				
		$DSP->title = $LANG->line('user_messages_template');						 
		$DSP->crumb = $LANG->line('user_messages_template');	
		
		$DSP->body = $DSP->heading($LANG->line('user_messages_template'));
		
		$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('user_messages_template_desc'));
		$DSP->body .= $DSP->qdiv('itemWrapper', 
												$DSP->qspan('highlight', $LANG->line('user_messages_template_warning')).
												$DSP->qspan('default', '{title} {meta_refresh} {heading} {content} {link}')
								);
		
		if ($message != '')
		{
			$DSP->body .= $DSP->qdiv('success', $message);
		}
		
		$query = $DB->query("SELECT template_data FROM exp_specialty_templates WHERE template_name = 'message_template'");
		
        $DSP->body .= $DSP->form('C=admin'.AMP.'M=sp_templ'.AMP.'P=update_user_messages_tmpl');
      
        $DSP->body .= $DSP->div('itemWrapper')  
					 .$DSP->input_textarea('template_data', $query->row['template_data'], '25', 'textarea', '100%')
					 .$DSP->div_c();
					 
		$DSP->body .= $DSP->input_submit($LANG->line('update'))
             		 .$DSP->form_c();
	}
	// END	
	
	
	// ---------------------------------
	//	Update Offline Template
	// ---------------------------------
		
	function update_user_messages_template()
	{
		global $DB, $DSP, $LANG;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! isset($_POST['template_data']))
        {
        	return FALSE;
        }
	
		$DB->query("UPDATE exp_specialty_templates SET template_data = '".$DB->escape_str($_POST['template_data'])."' WHERE template_name = 'message_template'");
	
		$this->user_messages_template($LANG->line('template_updated'));
	}
	// END
	
  
    // ---------------------------------
    //  Member notification templates
    // ---------------------------------     
    
    function mbr_notification_tmpl($message = '')
    {
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_members'))
        {
            return $DSP->no_access_message();
        }
        
        $DSP->title  = $LANG->line('email_notification_template');
        $DSP->crumb  = $LANG->line('email_notification_template');
             
        $r = $DSP->heading($LANG->line('email_notification_template'));
        
        if ($message != '')
        {
        	$r .= $DSP->qdiv('success', $LANG->line('template_updated'));
        }
     
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '2').
              $LANG->line('templates').
              $DSP->td_c().
              $DSP->tr_c();
              
        $str = '';
        
        foreach ($this->ignore as $val)
        {
        	$str .= " template_name != '".$val."' AND";
        }
        
        $str = substr($str, 0, -3);
              
		$sql = "SELECT * 
				FROM  exp_specialty_templates 
				WHERE ".$str."
				ORDER BY template_name";


        $query = $DB->query($sql);
        
        $i = 0;
        
		foreach ($query->result as $row)
		{
			$templates[$LANG->line($row['template_name'])] = $row['template_id'];
		}
		
		ksort($templates);
		
		foreach ($templates as $key => $val)
		{
			$style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
	
			$r .= $DSP->tr();
			$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $key), '40%');
			$r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=sp_templ'.AMP.'P=edit_notification_tmpl'.AMP.'id='.$val, $LANG->line('edit')), '30%');      
			$r .= $DSP->tr_c();
		}	
		
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                
        $DSP->body   = &$r;  
    }
    // END
    
    
    // ----------------------------------
    //  Edit Email Notification Template
    // ----------------------------------
    
    function edit_notification_tmpl()
    {  
		global $IN, $DSP, $DB, $REGX, $LANG, $PREFS;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! $id = $IN->GBL('id'))
        {
        	return;
        }
				
		$query = $DB->query("SELECT template_name, data_title, template_data, enable_template FROM exp_specialty_templates WHERE template_id = '$id'");
		
		if ($query->num_rows == 0)
		{
			return;
		}
		
		// Available Variables for each template
		
		$vars = array(
						'admin_notify_reg'						=> array('name', 'site_name', 'control_panel_url'),
						'admin_notify_comment'					=> array('weblog_name', 'entry_title', 'comment_url'),
						'admin_notify_trackback'				=> array('entry_title', 'comment_url', 'sending_weblog_name', 'sending_entry_title', 'sending_weblog_url'),
						'mbr_activation_instructions'			=> array('activation_url', 'site_name', 'site_url'),
						'forgot_password_instructions'			=> array('name', 'reset_url', 'site_name', 'site_url'),
						'reset_password_notification'			=> array('name', 'username', 'password', 'site_name', 'site_url'),
						'validated_member_notify'				=> array('name', 'site_name', 'site_url'),
						'mailinglist_activation_instructions'	=> array('activation_url', 'site_name', 'site_url'),
						'comment_notification'					=> array('weblog_name', 'entry_title', 'comment_url', 'notification_removal_url', 'site_name', 'site_url')
					);
		
		$vstr = '';
		
		if (isset($vars[$query->row['template_name']]))
		{
			foreach ($vars[$query->row['template_name']] as $val)
			{
				$vstr .= '{'.$val.'} ';
			}
			
			$vstr = $DSP->qdiv('itemWrapper', $LANG->line('available_variables').NBS.NBS.$vstr);
		}
				
		$DSP->title = $LANG->line('email_notification_template');						 
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=sp_templ'.AMP.'P=mbr_notification_tmpl', $LANG->line('email_notification_template')).$DSP->crumb_item($LANG->line($query->row['template_name']));

		$DSP->body = $DSP->heading($LANG->line($query->row['template_name']));
		
		$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line($query->row['template_name'].'_desc'));
		
		$DSP->body .= $vstr;
		
        $DSP->body .= $DSP->form('C=admin'.AMP.'M=sp_templ'.AMP.'P=update_notification_tmpl');
        $DSP->body .= $DSP->input_hidden('id', $id);
        
        $DSP->body .= $DSP->div('itemWrapper')
        			 .$DSP->heading($LANG->line('email_title'), 5)
                     .$DSP->input_text('data_title', $query->row['data_title'], '50', '80', 'input', '400px')
					 .$DSP->div_c();
      
        $DSP->body .= $DSP->div('itemWrapper')
                	 .$DSP->heading($LANG->line('email_message'), 5) 
					 .$DSP->input_textarea('template_data', $query->row['template_data'], '17', 'textarea', '100%')
					 .$DSP->div_c();
					 
		$DSP->body .= $DSP->heading(BR.$LANG->line('use_this_template'), 5);
		
		$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('use_this_template_exp'));
					 
		$selected = ($query->row['enable_template'] == 'y') ? 1 : '';
		
		$DSP->body .= $LANG->line('yes').NBS.$DSP->input_radio('enable_template', 'y', $selected).$DSP->nbs(3);
			 
		$selected = ($query->row['enable_template'] == 'n') ? 1 : '';
		
		$DSP->body .= $LANG->line('no').NBS.$DSP->input_radio('enable_template', 'n', $selected).$DSP->nbs(3);
					 
		$DSP->body .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('update')))
             		 .$DSP->form_c();
	}
	// ENd    
    
    
    // ----------------------------------
    //  Update Notification Templates
    // ----------------------------------  
    
    function update_notification_tmpl()
    {
		global $DB, $DSP, $LANG;
	
        if ( ! $DSP->allowed_group('can_admin_preferences'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! isset($_POST['template_data'])  ||  ! isset($_POST['id']))
        {
        	return FALSE;
        }
	
		$DB->query("UPDATE exp_specialty_templates SET data_title = '".$DB->escape_str($_POST['data_title'])."', template_data = '".$DB->escape_str($_POST['template_data'])."', enable_template = '".$DB->escape_str($_POST['enable_template'])."' WHERE template_id = '".$_POST['id']."'");
    
    	$this->mbr_notification_tmpl(1);
    }
    // END
        
}
// END CLASS
?>