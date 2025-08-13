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
 File: cp.templates.php
-----------------------------------------------------
 Purpose: The template management functions
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Templates {

    var $template_map   = array();
    
    // Reserved Template names
    var $reserved_names = array('act', 'trackback');
    
    // Reserved Global Variable names
    var $reserved_vars  = array(
    								'lang',
    								'charset',
    								'homepage',
    								'debug_mode',
    								'gzip_mode',
    								'version',
    								'elapsed_time',
    								'hits',
    								'total_queries',
    								'XID_HASH'
    							);
    							

    function Templates()
    {
        global $IN;

        switch($IN->GBL('M'))
        {
            case 'global_variables'      : $this->global_variables();
                break;
            case 'edit_global_var'       : $this->edit_global_variable();
                break;
            case 'update_global_var'     : $this->update_global_variable();
                break;
            case 'delete_global_var'     : $this->global_variable_delete_conf();
                break;
            case 'do_delete_global_var'  : $this->delete_global_variable();
                break;
            case 'new_tg_form'           : $this->edit_tempalte_group_form();
                break;
            case 'edit_tg_form'          : $this->edit_tempalte_group_form();
                break;
            case 'update_tg'             : $this->update_template_group();
                break;
            case 'edit_tg_order'         : $this->edit_template_group_order_form();
                break;
            case 'update_tg_order'       : $this->update_template_group_order();
                break;
            case 'tg_del_conf'           : $this->template_group_del_conf();
                break;
            case 'delete_tg'             : $this->template_group_delete();
                break;
            case 'new_templ_form'        : $this->new_template_form();
                break;
            case 'new_template'          : $this->create_new_template();
                break;
            case 'tmpl_del_conf'         : $this->template_del_conf();
                break;
            case 'delete_template'       : $this->delete_template();
                break;
            case 'edit_template'         : $this->edit_template();
                break;
            case 'update_template'       : $this->update_template();
                break;
            case 'template_prefs'        : $this->template_preferences_form();
                break;
            case 'update_template_prefs' : $this->update_template_preferences();
                break;
            case 'revision_history'      : $this->view_template_revision();
                break;
            case 'clear_revisions'       : $this->clear_revision_history();
                break;
            case 'export_tmpl'           : $this->export_templates_form();
                break;
            case 'export'                : $this->export_templates();
                break;
            default                      : $this->template_manager();
                break;
        }    
    }
    // END    



    // -----------------------------
    //  Verify access privileges
    // -----------------------------   

    function template_access_privs($data = '')
    {
    	global $SESS, $DB;
    	
    	// If the user is a Super Admin, return true
    	
		if ($SESS->userdata['group_id'] == 1)
		{
    		return TRUE;
		}    	
    
    	$template_id = '';
    	$group_id	 = '';    
    
    	if (is_array($data))
    	{
    		if (isset($data['template_id']))
    		{
    			$template_id = $data['template_id'];
    		}
    	
    		if (isset($data['group_id']))
    		{
    			$group_id = $data['group_id'];
    		}
    	}
    
    
        if ($group_id == '')
        {
        	if ($template_id == '')
        	{
        		return FALSE;
        	}
        	else
        	{
           		$query = $DB->query("SELECT group_id, template_name FROM exp_templates WHERE template_id = '$template_id'");
           		
           		$group_id = $query->row['group_id'];
            }
        }
                
                
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
			$access = FALSE;
			
			foreach ($SESS->userdata['assigned_template_groups'] as $key => $val)
			{
				if ($group_id == $key)
				{
					$access = TRUE;
					break;
				}
			}
		
			if ($access == FALSE)
			{
				return FALSE;
			}
        }
        else
        {
			if ($group_id != $SESS->userdata['tmpl_group_id'] )
			{
				return FALSE;
			}
        }        

		return TRUE;
    }
    // END


    // -----------------------------
    //  Template default page
    // -----------------------------   
    
    function template_manager()
    {  
        global $IN, $DSP, $DB, $PREFS, $FNS, $SESS, $LANG;
        
        $user_blog = FALSE;
        
        if ($SESS->userdata['tmpl_group_id'] != 0)
        {
            $user_blog = TRUE;
        }

        switch ($IN->GBL('MSG'))
        {
            case '01' : $message = $LANG->line('template_group_created');
                break;
            case '02' : $message = $LANG->line('template_group_updated');
                break;
            case '03' : $message = $LANG->line('template_group_deleted');
                break;
            case '04' : $message = $LANG->line('template_created');
                break;
            case '05' : $message = $LANG->line('template_deleted');
                break;
            default   : $message = "";
                break;
        }        
        
        $DSP->title  = $LANG->line('design');        
        $DSP->crumb  = $LANG->line('design');
        
        if ($user_blog === FALSE AND $DSP->allowed_group('can_admin_templates'))
        {
            $DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=new_tg_form', $LANG->line('create_new_template_group')));
        }
                
        $r  = $DSP->table('', '', '', '97%')
             .$DSP->tr()
             .$DSP->td('', '', '', '', 'top')
             .$DSP->heading($LANG->line('template_management'));
             
        if ($message != '')
        {
            $r .= $DSP->qdiv('success', $message);
        }

        $r .= $DSP->td_c()
             .$DSP->td('', '', '', '', 'top');
             
		$r .= $DSP->div('defaultRight');
		
        if ($DSP->allowed_group('can_admin_templates') || $user_blog !== FALSE)
        {
            $r .= $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=global_variables', '<b>'.$LANG->line('global_variables').'</b>');
        }
         
        if ($user_blog === FALSE AND $DSP->allowed_group('can_admin_templates'))
        {
            $r .= NBS.NBS.'|'.NBS.NBS.$DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=edit_tg_order', '<b>'.$LANG->line('edit_template_group_order').'</b>');
        }
        
        $r .= $DSP->div_c();
        
        $r .= $DSP->td_c()
             .$DSP->tr_c()
             .$DSP->table_c();
		 
		$qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
		$sitepath = $FNS->fetch_site_index(0, 0).$qs.'URL='.$FNS->fetch_site_index();
                
        if ( ! ereg("/$", $sitepath))
            $sitepath .= '/';
             
        $sql  = "SELECT group_id, group_name, is_site_default FROM exp_template_groups ";  
             
        if ($user_blog === TRUE)
        {
            $sql .= " WHERE group_id = '".$SESS->userdata['tmpl_group_id']."'";
        }
        else
        {
            $sql .= " WHERE is_user_blog = 'n'";
        }
        
        if ($SESS->userdata['group_id'] != 1)
        {
            foreach ($SESS->userdata['assigned_template_groups'] as $key => $val)
            {

                $sql .= " AND group_id = '$key' ";
            }
        }
                
        
        $sql .= " ORDER BY group_order";  
             
        $query = $DB->query($sql);

        foreach ($query->result as $row)
        {
            $template_group  = $row['group_name'];
            $is_site_default = $row['is_site_default'];
        
            $r .= $DSP->table('tableBorder', '7', '', '100%')
                 .$DSP->tr()
                 .$DSP->td('defaultPad', '37%', '', '', 'top');
                 
            $r .= $DSP->table('', '', '', '100%')
                 .$DSP->tr()
                 .$DSP->td('', '36%', '', '', 'top')
                 .$DSP->qdiv('rightPad', $DSP->heading($template_group))
                 .$DSP->td_c()
                 .$DSP->td('leftBorderPad', '55%', '', '', 'top')
                 .$DSP->qdiv('itemWrapper',  $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=new_templ_form'.AMP.'id='.$row['group_id'], $LANG->line('add_a_template')));
   
            if ($user_blog === FALSE AND $DSP->allowed_group('can_admin_templates'))
            {
                $r .= $DSP->qdiv('itemWrapper',  $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=edit_tg_form'.AMP.'id='.$row['group_id'], $LANG->line('edit_template_group')))
                     .$DSP->qdiv('itemWrapper',  $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=tg_del_conf'.AMP.'id='.$row['group_id'], $LANG->line('delete_template_group')));
            }
            
            // TEMPLATE EXPORT LINK
            // This has been temporarily disabled while we 
            // get to the bottom of the zip encoding problem.
			//  $r .= $DSP->qdiv('itemWrapper',  $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=export_tmpl'.AMP.'id='.$row['group_id'], $LANG->line('export_templates')));
                 
            $r .= $DSP->td_c()
                 .$DSP->tr_c()
                 .$DSP->table_c();

            $r .= $DSP->td_c()
                 .$DSP->td('', '63%', '', '', 'top');


        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
    
            $r .= $DSP->table('', '0', '', '100%')
                 .$DSP->tr()
                 .$DSP->table_qcell('tableHeadingBold', $LANG->line('template_name'), '32%')
                 .$DSP->table_qcell('tableHeadingBold', $LANG->line('hits'), '17%')
                 .$DSP->table_qcell('tableHeadingBold', $LANG->line('edit'), '17%')
                 .$DSP->table_qcell('tableHeadingBold', $LANG->line('prefs'), '17%')
                 .$DSP->table_qcell('tableHeadingBold', $LANG->line('delete'), '17%')
                 .$DSP->tr_c();
            
    
            $i = 0;
            
            $res = $DB->query("SELECT template_id, template_name, template_type, hits FROM exp_templates WHERE group_id = '".$row['group_id']."' ORDER BY template_name");
                
            foreach ($res->result as $val)
            {     
                $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
              
                $r .= $DSP->tr();
                
                $default = ($is_site_default == 'y' AND $val['template_name'] == 'index') ? $DSP->required() : '';
                
				$viewurl = $sitepath;

				if ($val['template_type'] == 'css')
				{
					$viewurl  = substr($viewurl, 0, -1);
					$viewurl .= $qs.$template_group.'/'.$val['template_name'].'/';
				}
				else
				{
					$viewurl .= $template_group.'/'.$val['template_name'].'/';
				}

                $r .= $DSP->table_qcell($style, $DSP->pagepop($viewurl, $default.$val['template_name']));
                
                $r .= $DSP->table_qcell($style, $val['hits']);

                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=edit_template'.AMP.'id='.$val['template_id'], $LANG->line('edit')));
                                  
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=template_prefs'.AMP.'id='.$val['template_id'], $LANG->line('prefs')));
                                                  
                                  
                $delete =  ($val['template_name'] == 'index') ? '--' : $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=tmpl_del_conf'.AMP.'id='.$val['template_id'], $LANG->line('delete'));

                $r .= $DSP->table_qcell($style, $delete)
                     .$DSP->tr_c();
            }
                          
                $r .= $DSP->table_c();


        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                
                $r .= $DSP->td_c()
                     .$DSP->tr_c()            
                     .$DSP->table_c();
                
                $r .= $DSP->qdiv('defaultPad', BR);
        }
        
        if ($user_blog === FALSE)
        {
            $r .= $DSP->required($LANG->line('default_site_page'));
        }
        
        $DSP->body = &$r;        
    }
    // END
    
    
  
    
    // ---------------------------------
    //  New/Edit Template Group Form
    // ---------------------------------

    function edit_tempalte_group_form()
    {
        global $DSP, $IN, $DB, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        $edit            = FALSE;
        $group_id        = '';
        $group_name      = '';
        $group_order     = '';
        $is_site_default = '';
        
                
        if ($group_id = $IN->GBL('id'))
        {
            $edit = TRUE;
            
            $query = $DB->query("SELECT group_id, group_name, is_site_default FROM exp_template_groups WHERE group_id = '$group_id'");
            
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }
        
        
        $title = ($edit == FALSE) ? $LANG->line('new_template_group_form') : $LANG->line('edit_template_group_form');
                
        // Build the output
        
        $DSP->title = &$title;
        $DSP->crumb = &$title;      
        
        $DSP->body = $DSP->form('C=templates'.AMP.'M=update_tg');
     
        if ($edit == TRUE)
            $DSP->body .= $DSP->input_hidden('group_id', $group_id);
        
        $DSP->body .= $DSP->heading($title);
                
        $DSP->body .=  $DSP->div('paddedWrapper')
                      .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('name_of_template_group', 'group_name').'</b>')
                      .$DSP->qdiv('itemWrapper', $LANG->line('template_group_instructions'))
                      .$DSP->qdiv('itemWrapper', $LANG->line('undersores_allowed'))
                      .$DSP->qdiv('itemWrapper', $DSP->input_text('group_name', $group_name, '20', '50', 'input', '240px'))
                      .$DSP->div_c();
              
                      
        $selected = ($is_site_default == 'y') ? 1 : '';
                      
        $DSP->body .= $DSP->qdiv('itemWrapper', $DSP->input_checkbox('is_site_default', 'y', $selected).NBS.NBS.$LANG->line('is_site_default').BR.BR); 
        
               
        if ($edit == FALSE)
            $DSP->body .= $DSP->input_submit($LANG->line('submit'));
        else
            $DSP->body .= $DSP->input_submit($LANG->line('update'));
    
        $DSP->body .= $DSP->form_c();
    }
    // END    
    
    
    
    // -------------------------------------
    //  Create/Update Template Group
    // -------------------------------------

    function update_template_group()
    {
        global $DSP, $IN, $DB, $FNS, $LANG;
        
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        
        if ( ! $group_name = $IN->GBL('group_name', 'POST'))
        {
            return $DSP->error_message($LANG->line('form_is_empty'));
        }
        
        
        if ( ! preg_match("#^[a-zA-Z0-9_\-/]+$#i", $group_name))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }
        
        if (in_array($group_name, $this->reserved_names))
        {
            return $DSP->error_message($LANG->line('reserved_name'));
        }
        
        $is_site_default = ($IN->GBL('is_site_default', 'POST') == 'y' ) ? 'y' : 'n';
              
        
        if ($is_site_default == 'y')
        {
            $DB->query("UPDATE exp_template_groups SET is_site_default = 'n' ");
        }
               
        
        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            $query = $DB->query("SELECT COUNT(*) AS count FROM exp_template_groups WHERE is_user_blog = 'n'");
            $group_order = $query->row['count'] +1;
        
            $DB->query(
                        $DB->insert_string(
                                             'exp_template_groups', 
                                              array(
                                                     'group_id'        => '', 
                                                     'group_name'      => $group_name,
                                                     'group_order'     => $group_order,
                                                     'is_site_default' => $is_site_default
                                                   )
                                           )      
                        );
                        
            $sql = $DB->insert_string(
                                       'exp_templates', 
                                        array(
                                               'template_id'   => '', 
                                               'group_id'      => $DB->insert_id,
                                               'template_name' => 'index'
                                             )
                                     );        
            $DB->query($sql);
            
            $message = '01';
        }
        else
        {
            $DB->query(
                        $DB->update_string(
                                            'exp_template_groups', 
                                             array('group_name' => $group_name, 'is_site_default' => $is_site_default), 
                                             array('group_id'   => $group_id)
                                          )
                      );              
       
            $message = '02';

        }
        
        $FNS->redirect(BASE.AMP.'C=templates'.AMP.'MSG='.$message);
        exit;     
    }
    // END    
    
    
    
    // -------------------------------
    //  Template Group Delete Confirm
    // -------------------------------   

    function template_group_del_conf()
    {
        global $DSP, $DB, $IN, $LANG;
        
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
    
        $DSP->title  = $LANG->line('template_group_del_conf');        
        $DSP->crumb  = $LANG->line('template_group_del_conf');        
    
        if ( ! $group_id = $IN->GBL('id'))
        {
            return false;
        }

        $query = $DB->query("SELECT group_name FROM exp_template_groups WHERE group_id = '$group_id'");
        
        $DSP->body = $DSP->form('C=templates'.AMP.'M=delete_tg')
                    .$DSP->input_hidden('group_id', $group_id)
                    .$DSP->heading($LANG->line('delete_template_group'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_this_group').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['group_name'].'</i>')
                    .$DSP->qdiv('alert', BR.'<b>'.$LANG->line('all_templates_will_be_nuked').'</b>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
    
    
    // -------------------------------
    //  Delete Template Group
    // -------------------------------   

    function template_group_delete()
    {
        global $DSP, $DB, $IN, $FNS, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            return false;
        }
        
        // We need to delete all the saved template data in the versioning table
        
        $sql = "DELETE FROM exp_revision_tracker WHERE ";
        
        $query = $DB->query("SELECT template_id FROM exp_templates WHERE group_id = '$group_id'");
        
        foreach ($query->result as $row)
        {
            $sql .= " item_id = '".$row['template_id']."' OR";
        }
                
        $sql = substr($sql, 0, -2);
                
        $DB->query($sql);
        
        $DB->query("DELETE FROM exp_templates WHERE group_id = '$group_id'");
        $DB->query("DELETE FROM exp_template_groups WHERE group_id = '$group_id'");
                
        $FNS->redirect(BASE.AMP.'C=templates'.AMP.'MSG=03');
        exit;     
    }
    // END    
    
    
    
    // -------------------------------
    //  Edit template group order
    // -------------------------------   

    function edit_template_group_order_form()
    {
        global $DSP, $DB, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
                
        $r  = $DSP->form('C=templates'.AMP.'M=update_tg_order');
        
        $r .= $DSP->heading($LANG->line('edit_group_order'));

		$r .= $DSP->table('tableBorder', '0', '', '30%')
			 .$DSP->tr()
			 .$DSP->td('tablePad', '37%', '', '', 'top');
	
        $r .= $DSP->table('', '0', '10', '100%').
			  $DSP->tr().
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('group_name')).
			  $DSP->table_qcell('tableHeadingBold', $LANG->line('order')).
			  $DSP->tr_c();
			

        $query = $DB->query("SELECT group_id, group_order, group_name FROM exp_template_groups WHERE is_user_blog = 'n'");
        
		$i = 0;

        foreach ($query->result as $row)
        {
			$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

            $r .= $DSP->tr()
                 .$DSP->table_qcell($style, '<b>'.$row['group_name'].'</b>')
                 .$DSP->table_qcell($style, $DSP->input_text($row['group_id'], $row['group_order'], '4', '3', 'input', '30px'))      
                 .$DSP->tr_c();
        }
        
        $r .= $DSP->table_c();

		$r .= $DSP->td_c()
             .$DSP->tr_c()
             .$DSP->table_c();

		$r .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit($LANG->line('update')));
                
        $r .= $DSP->form_c();

    
        $DSP->title  = $LANG->line('edit_group_order');        
        $DSP->crumb  = $LANG->line('edit_group_order');   

        $DSP->body  = &$r;
    }
    // END    




    // -------------------------------
    //  Update Template Group Order
    // -------------------------------   

    function update_template_group_order()
    {  
        global $DSP, $IN, $DB, $FNS, $LANG;
      
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        foreach ($_POST as $key => $val)
        {
            $DB->query("UPDATE exp_template_groups SET group_order = '$val' WHERE group_id = '$key'");    
        }

        $FNS->redirect(BASE.AMP.'C=templates');
        exit;
    }
    // END
  
  

  
    // -----------------------------
    //  New Template Form
    // -----------------------------   

    function new_template_form()
    {
        global $DSP, $IN, $FNS, $DB, $SESS, $LANG;
        
        if ( ! $group_id = $IN->GBL('id'))
        {
            return false;
        }
        
        if ( ! $this->template_access_privs(array('group_id' => $group_id)))
        {
        	return $DSP->no_access_message();
        }
        
        $user_blog = ($SESS->userdata['tmpl_group_id'] == 0) ? FALSE : TRUE;
                        
        // Build the output
        
        $DSP->title = $LANG->line('new_template_form');
        $DSP->crumb = $LANG->line('new_template_form');      
        
        $r  = $DSP->form('C=templates'.AMP.'M=new_template');
        $r .= $DSP->input_hidden('group_id', $group_id);
        
        $r .= $DSP->heading($LANG->line('new_template_form'));        
                
        $r .= $DSP->div('pad400')
             .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('name_of_template', 'template_name').'</b>')
             .$DSP->qdiv('itemWrapper', $LANG->line('template_group_instructions'))
             .$DSP->qdiv('itemWrapper', $LANG->line('undersores_allowed'))
             .$DSP->qdiv('', $DSP->input_text('template_name', '', '20', '50', 'input', '240px'))
             .$DSP->div_c();
                 
                 
        $r .= $DSP->div('pad400')
             .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('type_of_template', 'type_of_template').'</b>');
            
        $r .= $DSP->input_select_header('template_type');
        $r .= $DSP->input_select_option('webpage', $LANG->line('webpage'), 1);             
        $r .= $DSP->input_select_option('rss', $LANG->line('rss'), '');
        $r .= $DSP->input_select_footer();        
            
        $r .= $DSP->div_c();

                 
                 
        $r .= $DSP->qdiv('itemWrapper', '<b>'.$LANG->line('choose_default_data').'</b>');
        
        $r .= $DSP->qdiv('itemWrapper', $DSP->input_radio('data', 'none', 1).NBS.$LANG->line('blank_template'));
        
        $data = $FNS->create_directory_map(PATH_TMPL);
        
        if (count($data) > 0)
        {
            $r .= $DSP->div('itemWrapper')
                 .$DSP->input_radio('data', 'library').NBS.$LANG->line('template_from_library');
              
            $r .= BR.NBS.NBS.NBS.NBS.NBS.$DSP->input_select_header('library');
            
            $this->render_map_as_select_options($data);
    
            foreach ($this->template_map as $val)
            {
                $r .= $DSP->input_select_option($val, substr($val, 0, -4));
            }
            
            $r .= $DSP->input_select_footer()
                 .$DSP->div_c();
        }
                                  
        $r .= $DSP->div('itemWrapper')
             .$DSP->input_radio('data', 'template').NBS.$LANG->line('an_existing_template');
                
        $sql = "SELECT exp_template_groups.group_name, exp_templates.template_name, exp_templates.template_id
                FROM   exp_template_groups, exp_templates
                WHERE  exp_template_groups.group_id =  exp_templates.group_id";
                
         
        if ($user_blog == TRUE)
        {
            $sql .= " AND exp_template_groups.group_id = '".$SESS->userdata['tmpl_group_id']."'";
        }
        else
        {
            $sql .= " AND exp_template_groups.is_user_blog = 'n'";
        }
                
        $sql .= " ORDER BY exp_template_groups.group_order, exp_templates.template_name";         
                
                
        $query = $DB->query($sql);
                
        $r .= BR.NBS.NBS.NBS.NBS.NBS.$DSP->input_select_header('template');
                
        foreach ($query->result as $row)
        {
            $r .= $DSP->input_select_option($row['template_id'], $row['group_name'].'/'.$row['template_name']);
        }
        
        $r .= $DSP->input_select_footer()
             .$DSP->div_c();
        
                     
                      
        $r .= $DSP->qdiv('itemWrapper', BR.BR.$DSP->input_submit($LANG->line('submit')))
             .$DSP->form_c();

        $DSP->body = &$r;          
    }
    // END    
    
    
    
    
   
    // -------------------------------------------
    //  Create pull-down optios from dirctory map
    // -------------------------------------------

    function render_map_as_select_options($zarray, $array_name = '') 
    {	
        foreach ($zarray as $key => $val)
        {
            if ( is_array($val))
            {
                if ($array_name != "")
                    $key = $array_name.'/'.$key;
            
                $this->render_map_as_select_options($val, $key);
            }		
            else
            {
                if ($array_name <> "")
                    $val = $array_name.'/'.$val;
                    
               if (ereg(".tpl$", $val) || ereg(".css$", $val))
               {    
                    $this->template_map[] = $val;
               }
            }
        }
    }
    // END



    // -------------------------------
    //  Create new template
    // -------------------------------   
    
    function create_new_template()
    {
        global $DSP, $IN, $DB, $LOC, $FNS, $SESS, $LANG;
        

        if ( ! $template_name = $IN->GBL('template_name', 'POST'))
        {
            return $DSP->error_message($LANG->line('you_must_submit_a_name'));
        }
                 
        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
        	return $DSP->no_access_message();
        }
        
        if ( ! $this->template_access_privs(array('group_id' => $group_id)))
        {
        	return $DSP->no_access_message();
        }
        
        $user_blog = ($SESS->userdata['tmpl_group_id'] == 0) ? FALSE : TRUE;

        
        if ($user_blog == TRUE && $group_id != $SESS->userdata['tmpl_group_id'])
        {
        	return $DSP->no_access_message();
        }
             
        if ( ! preg_match("#^[a-zA-Z0-9_\-/\.]+$#i", $template_name))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }
        
        if (in_array($template_name, $this->reserved_names))
        {
            return $DSP->error_message($LANG->line('reserved_name'));
        }
        
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_templates WHERE group_id = '".$_POST['group_id']."' AND template_name = '".$DB->escape_str($_POST['template_name'])."'");
        
        if ($query->row['count'])
        {
            return $DSP->error_message($LANG->line('template_name_taken'));
        }
        
                
        $template_data = '';
        
        $template_type = $_POST['template_type'];
        
        if ($_POST['data'] == 'library')
        {
            if ($fp = @fopen(PATH_TMPL.$_POST['library'], 'r'))
            {
                $template_data = fread($fp, filesize(PATH_TMPL.$_POST['library']));

                fclose($fp);
            }        
        }
        elseif ($_POST['data'] == 'template')
        {
            $query = $DB->query("SELECT template_data, template_type FROM exp_templates WHERE template_id = '".$_POST['template']."'");
            
            $template_data = $query->row['template_data'];
            
            if ($template_type != $query->row['template_type'])
                $template_type = $query->row['template_type'];
        }    
                 
        $data = array(
                        'template_id'    => '',
                        'group_id'       => $_POST['group_id'],
                        'template_name'  => $_POST['template_name'],
                        'template_type'  => $template_type,
                        'template_data'  => $template_data
                     );


        $DB->query($DB->insert_string('exp_templates', $data));
        
        $FNS->redirect(BASE.AMP.'C=templates'.AMP.'MSG=04');
        exit;     
    }
    // END
        
    
    
    // -------------------------------
    //  Template Delete Confirm
    // -------------------------------   

    function template_del_conf()
    {
        global $DSP, $DB, $IN, $SESS, $LANG;
        
        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_id, template_name FROM exp_templates WHERE template_id = '$id'");
                
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
        else
        {
            if ($query->row['group_id'] != $SESS->userdata['tmpl_group_id'] )
            {
                return $DSP->no_access_message();
            }
        }
    
        $DSP->title  = $LANG->line('template_del_conf');        
        $DSP->crumb  = $LANG->line('template_del_conf');        
        
        
        $DSP->body = $DSP->form('C=templates'.AMP.'M=delete_template')
                    .$DSP->input_hidden('template_id', $id)
                    .$DSP->heading($LANG->line('delete_template'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_this_template').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['template_name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
    
    
    // -------------------------------
    //  Delete Template
    // -------------------------------   

    function delete_template()
    {
        global $DSP, $IN, $LANG, $FNS, $SESS, $DB;
        
        if ( ! $id = $IN->GBL('template_id', 'POST'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_id, template_name FROM exp_templates WHERE template_id = '$id'");
                
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
        else
        {
            if ($query->row['group_id'] != $SESS->userdata['tmpl_group_id'] )
            {
                return $DSP->no_access_message();
            }
        }        
        
        $DB->query("DELETE FROM exp_revision_tracker WHERE item_id = '$id' AND item_table = 'exp_templates' and item_field = 'template_data' ");
        
        $DB->query("DELETE FROM exp_templates WHERE template_id = '$id'");
        
        $FNS->redirect(BASE.AMP.'C=templates'.AMP.'MSG=05');
        exit;     
    }
    // END
    
    
    
    // -----------------------------
    //  Template Preferences Form
    // -----------------------------   
    
    function template_preferences_form($template_id = '')
    {
        global $IN, $DSP, $DB, $SESS, $LANG;
        
        $user_blog = FALSE;
        
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
        else
        {
            $user_blog = TRUE;
        }
                
        if ($template_id == '')
        {
            if ( ! $template_id = $IN->GBL('id'))
            {
                return false;
            }
            
            $message = '';
        }
        else
        {
            $message = $DSP->qdiv('success', $LANG->line('preferences_updated'));
        }
        
        // Fetch template preferences
        
        $query = $DB->query("SELECT template_name, template_type, group_id, allow_php, php_parse_location, no_auth_bounce, cache, refresh, hits FROM exp_templates WHERE template_id = '$template_id'");
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
        
    
        $DSP->title  = $LANG->line('template_preferences');        
        $DSP->crumb  = $LANG->line('template_preferences');      
    
        $r  = $DSP->form('C=templates'.AMP.'M=update_template_prefs')
             .$DSP->input_hidden('template_id', $template_id)
             .$DSP->input_hidden('group_id', $group_id)
             .$DSP->input_hidden('old_name', $template_name)
             .$DSP->heading($LANG->line('template_preferences'))
             .$message;
             
        $r .= $DSP->table('', '0', '', '100%')
             .$DSP->tr()
             .$DSP->td('rightBorderPad', '50%', '', '', 'top');                
        
        // The name of the index template can't be edited
        
        if ($template_name == 'index')
        {
            $r .= $DSP->qdiv('itemPadBotBorder', $DSP->heading($LANG->line('template_name').NBS.NBS.$template_name, 5));
        }
        else
        {
            $r .= $DSP->div('itemPadBotBorder')
                 .$DSP->heading($LANG->line('template_name', 'template_name'), 5)
                 .$DSP->qdiv('itemWrapper', $LANG->line('template_group_instructions'))
                 .$DSP->qdiv('itemWrapper', $LANG->line('undersores_allowed'))                 
                 .$DSP->input_text('template_name', $template_name, '20', '60', 'input', '250px')
                 .$DSP->div_c();
        }
        
        // Template type
        
        
        $r .= $DSP->div('itemPadBotBorder')
             .$DSP->heading($LANG->line('type_of_template', 'type_of_template'), 5);
            
        $r .= $DSP->input_select_header('template_type');
        
        $selected = ($template_type == 'webpage') ? 1 : '';
    
        $r .= $DSP->input_select_option('webpage', $LANG->line('webpage'), $selected);
        
        $selected = ($template_type == 'css') ? 1 : '';
    
        $r .= $DSP->input_select_option('css', $LANG->line('css_stylesheet'), $selected);
            
        $selected = ($template_type == 'rss') ? 1 : '';
    
        $r .= $DSP->input_select_option('rss', $LANG->line('rss'), $selected);

        $r .= $DSP->input_select_footer();        
            
        $r .= $DSP->div_c();


        // Cache settings
             
        $r .= $DSP->div('itemPadBotBorder')
             .$DSP->heading($LANG->line('enable_cache'), 5);
                                          
              $selected = ($cache == 'y') ? 1 : '';

        $r .= $LANG->line('yes').$DSP->nbs().$DSP->input_radio('cache', 'y', $selected).$DSP->nbs(3);
              
              $selected = ($cache == 'n') ? 1 : '';
              
        $r .= $LANG->line('no').$DSP->nbs().$DSP->input_radio('cache', 'n', $selected);


        // Refresh time
             
        $r .= $DSP->heading(BR.$LANG->line('cache_interval'), 5)
             .$DSP->qdiv('itemWrapper', $LANG->line('in_minutes'))
             .$DSP->input_text('refresh', $refresh, '8', '6', 'input', '50px')               
             .$DSP->div_c();
              
        // Only Super Admins can set this
        
        if ($SESS->userdata['group_id'] == 1)
        {                      
             // Allow PHP?

            $r .= $DSP->div('itemPadBotBorder')
                 .$DSP->heading($LANG->line('allow_php'), 5)
                 .$DSP->qdiv('alert', $LANG->line('php_security_warning'))
                 .$DSP->qdiv('itemWrapper', $LANG->line('consult_manual'));
                                              
                  $selected = ($allow_php == 'y') ? 1 : '';
    
            $r .= $LANG->line('yes').$DSP->nbs().$DSP->input_radio('allow_php', 'y', $selected).$DSP->nbs(3);
                  
                  $selected = ($allow_php == 'n') ? 1 : '';
                  
            $r .= $LANG->line('no').$DSP->nbs().$DSP->input_radio('allow_php', 'n', $selected);
                 
                 
            $r .= $DSP->heading(BR.$LANG->line('php_parse_location'), 5);
                                              
                  $selected = ($php_parse_location == 'i') ? 1 : '';
    
            $r .= $LANG->line('input').$DSP->nbs().$DSP->input_radio('php_parse_location', 'i', $selected).$DSP->nbs(2);
                  
                  $selected = ($php_parse_location == 'o') ? 1 : '';
                  
            $r .= $LANG->line('output').$DSP->nbs().$DSP->input_radio('php_parse_location', 'o', $selected)
                 .$DSP->div_c();
                 
                 
        }  
        
        
        
        // Hit counter
             
        $r .= $DSP->div('itemPadBotBorder')
             .$DSP->heading($LANG->line('hit_counter'), 5)
             .$DSP->input_text('hits', $hits, '10', '13', 'input', '90px')               
             .$DSP->div_c();

        
                   
        $r .= $DSP->td_c();
                
        $r .= $DSP->td('', '4%', '', '', 'top')
             .NBS
             .$DSP->td_c()
             .$DSP->td('', '46%', '', '', 'top');


        // Right side of page
        
        if ($user_blog == FALSE)
        {
        
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('restrict_to_group'), 5));

            $r .= $DSP->table('tableBorder', '0', '0', '100%').
                  $DSP->tr().
                  $DSP->td('tablePad'); 

            $r .= $DSP->table('', '0', '', '100%').
                  $DSP->tr().
                  $DSP->td('tableHeadingBold', '', '').
                  $LANG->line('member_group').
                  $DSP->td_c().
                  $DSP->td('tableHeadingBold', '', '').
                  $LANG->line('can_view_template').
                  $DSP->td_c().
                  $DSP->tr_c();
        
                $i = 0;
            
            $group = array();
            
            $result = $DB->query("SELECT member_group FROM exp_template_no_access WHERE template_id = '$template_id'");
            
            if ($result->num_rows != 0)
            {
                foreach($result->result as $row)
                {
                    $group[$row['member_group']] = TRUE;
                }
            }
            
            
            $query = $DB->query("SELECT group_id, group_title FROM exp_member_groups WHERE group_id != '1' ORDER BY group_title");
            
            foreach ($query->result as $row)
            {
                    $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
            
                    $r .= $DSP->tr().
                          $DSP->td($style, '50%').
                          $row['group_title'].
                          $DSP->td_c().
                          $DSP->td($style, '50%');
                          
                    $selected = ( ! isset($group[$row['group_id']])) ? 1 : '';
                        
                    $r .= $LANG->line('yes').NBS.
                          $DSP->input_radio('access_'.$row['group_id'], 'y', $selected).$DSP->nbs(3);
                       
                    $selected = (isset($group[$row['group_id']])) ? 1 : '';
                        
                    $r .= $LANG->line('no').NBS.
                          $DSP->input_radio('access_'.$row['group_id'], 'n', $selected).$DSP->nbs(3);
        
                    $r .= $DSP->td_c()
                         .$DSP->tr_c();
            }        
        
            $r .= $DSP->table_c(); 
    
            $r .= $DSP->td_c()   
                 .$DSP->tr_c()      
                 .$DSP->table_c();
            
            $r .= $DSP->heading(BR.$LANG->line('no_access_select_blurb'), 5);
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('no_access_instructions'));

            
            $sql = "SELECT exp_template_groups.group_name, exp_templates.template_name, exp_templates.template_id
                    FROM   exp_template_groups, exp_templates
                    WHERE  exp_template_groups.group_id =  exp_templates.group_id
                    ORDER BY exp_template_groups.group_order, exp_templates.template_name";         
                    
                    
            $query = $DB->query($sql);
                    
            $r .=  $DSP->div()
                  .$DSP->input_select_header('no_auth_bounce');
                    
            foreach ($query->result as $row)
            {
                $selected = ($row['template_id'] == $no_auth_bounce) ? 1 : '';
            
                $r .= $DSP->input_select_option($row['template_id'], $row['group_name'].'/'.$row['template_name'], $selected);
            }
            
            $r .= $DSP->input_select_footer();             
            $r .= $DSP->div_c(); 
          
      
        }
        else
        {
            $r .= NBS;
        }             
        
        
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();

        $r .= $DSP->qdiv('', $DSP->input_submit($LANG->line('submit')))
             .$DSP->form_c();
    
        $DSP->body = &$r;
    }
    // END
    
  
 
 
    // -------------------------------
    //  Update Template Preferences
    // -------------------------------   
    
    function update_template_preferences()
    {
        global $IN, $DSP, $DB, $SESS, $LANG;
            
        if ( ! $template_id = $IN->GBL('template_id', 'POST'))
        {
            return false;
        }
        
        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_id, template_name FROM exp_templates WHERE template_id = '$template_id'");
                
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
        else
        {
            if ($query->row['group_id'] != $SESS->userdata['tmpl_group_id'] )
            {
                return $DSP->no_access_message();
            }
        }        
        
                
        if ($template_name = $IN->GBL('template_name', 'POST'))
        {
            if ($template_name == '')
            {
                return $DSP->error_message($LANG->line('missing_name'));
            }
            
            if ( ! preg_match("#^[a-zA-Z0-9_\-/\.]+$#i", $template_name))
            {
                return $DSP->error_message($LANG->line('illegal_characters'));
            }
            
            if (in_array($template_name, $this->reserved_names))
            {
                return $DSP->error_message($LANG->line('reserved_name'));
            }
            
            // Is template name taken?
            
            if ($template_name != $_POST['old_name'])
            {
                $query = $DB->query("SELECT COUNT(*) AS count FROM exp_templates WHERE template_name='$template_name' AND group_id = '$group_id'");
                
                if ($query->row['count'] > 0)
                {
                    return $DSP->error_message($LANG->line('template_name_taken'));
                }
                
                $data['template_name'] = $template_name;
            }
        }
        
        
        $DB->query("DELETE FROM exp_template_no_access WHERE template_id = '$template_id'");
        
        $no_auth = FALSE;
                        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 7) == 'access_' AND $val == 'n')
            {
                $no_auth = TRUE;
                
                $DB->query("INSERT INTO exp_template_no_access (template_id, member_group) VALUES ('$template_id', '".substr($key, 7)."')");
            }
        }   
                
        $data['cache'] = $_POST['cache'];
        $data['refresh'] = ( ! is_numeric($_POST['refresh'])) ? '1' : $_POST['refresh'];
        $data['hits'] = ( ! is_numeric($_POST['hits'])) ? '0' : $_POST['hits'];
        $data['template_type'] = $_POST['template_type'];
        $data['php_parse_location'] = $_POST['php_parse_location'];
        $data['no_auth_bounce'] = ($no_auth == TRUE) ? $_POST['no_auth_bounce'] : '';

        if (isset($_POST['allow_php']) AND $_POST['allow_php'] == 'y' AND $SESS->userdata['group_id'] == 1)
        {
            $data['allow_php'] = 'y';
        }
        else
        {
            $data['allow_php'] = 'n';        
        }
        
        $DB->query($DB->update_string('exp_templates', $data, "template_id = '$template_id'"));
        
        return $this->template_preferences_form($template_id);
    }    
    // END
    
    
    
   
    // -------------------------------
    //  Edit Template
    // -------------------------------   

    function edit_template($template_id = '', $message = '')
    {
        global $DSP, $IN, $DB, $PREFS, $SESS, $FNS, $LOC, $LANG;
                
        if ($template_id == '')
        {
            if ( ! $template_id = $IN->GBL('id'))
            {
                return false;
            }
        }
        
        $user_blog = ($SESS->userdata['tmpl_group_id'] == 0) ? FALSE : TRUE;
        
        $query = $DB->query("SELECT group_id, template_name, template_data, template_notes, template_type FROM exp_templates WHERE template_id = '$template_id'");
        
        $group_id = $query->row['group_id'];
        $template_type = $query->row['template_type'];
        
        $result = $DB->query("SELECT group_name FROM exp_template_groups WHERE group_id = '".$group_id."'");
                                               
        $template_group  = $result->row['group_name']; 
                        
        if ( ! $this->template_access_privs(array('group_id' => $group_id)))
        {
        	return $DSP->no_access_message();
        }
        
        $template_data  = $query->row['template_data'];   
        $template_name  = $query->row['template_name']; 
        $template_notes = $query->row['template_notes']; 
             
		$qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
		$sitepath = $FNS->fetch_site_index(0, 0).$qs.'URL='.$FNS->fetch_site_index();
                     
        if ( ! ereg("/$", $sitepath))
            $sitepath .= '/';
        
        if ($template_type == 'css')
        {
        	$sitepath = substr($sitepath, 0, -1);
        	$sitepath .= $qs.'css='.$template_group.'/'.$template_name.'/';
        }
        else
        {
        	$sitepath .= $template_group.'/'.$template_name.'/';
    	}
    	
        $DSP->title  = $LANG->line('edit_template');        
        $DSP->crumb  = $LANG->line('edit_template');
        $DSP->rcrumb = $DSP->pagepop($sitepath, $LANG->line('view_rendered_template'));   
        
        ob_start();
        
        ?>     
        <script language="javascript" type="text/javascript"> 
        <!--
        
            function viewRevision()
            {	
                var id = document.forms.revisions.revision_history.value;
                
                if (id == "")
                {
                    return false;
                }
                else if (id == "clear")
                {
                    var items = document.forms.revisions.revision_history;
          
                    for (i = items.length -1; i >= 1; i--)
                    {
                        items.options[i] = null;
                    }
                    
                    document.forms.revisions.revision_history.options[0].selected = true;
                    
                    flipButtonText(1);
                    
                    window.open ("<?php echo BASE.'&C=templates&M=clear_revisions&id='.$template_id.'&Z=1'; ?>" ,"Revision", "width=500, height=260, location=0, menubar=0, resizable=0, scrollbars=0, status=0, titlebar=0, toolbar=0, screenX=60, left=60, screenY=60, top=60");

                    return false;                    
                }
                else
                {
                    window.open ("<?php echo BASE.'&C=templates&M=revision_history&Z=1'; ?>&id="+id ,"Revision", "status=0, titlebar=0, toolbar=0");

                    return false;
                }
                return false;
            }
            
            function flipButtonText(which)
            {	
                if (which == "clear")
                {
                    document.forms.revisions.submit.value = '<?php echo $LANG->line('clear'); ?>';
                }
                else
                {
                    document.forms.revisions.submit.value = '<?php echo $LANG->line('view'); ?>';
                }
            }
            
        //-->
        </script>        
    
        <?php
        
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
        
        $r  = $buffer;
        
         
        $r .= $DSP->form('', 'revisions')
             .$DSP->input_hidden('template_id', $template_id);
         
        $r .= $DSP->table('', '', '', '97%')
             .$DSP->tr()
             .$DSP->td()
             .$DSP->heading($LANG->line('template_name').NBS.NBS.$template_group.'/'.$template_name)
             .$DSP->td_c();
             
        $r .= $DSP->td()
             .$DSP->div('defaultRight');
             
        if ($user_blog == FALSE)
        {             
             $r .= "<select name='revision_history' class='select' onChange='flipButtonText(this.options[this.selectedIndex].value);'>"
                 .NL
                 .$DSP->input_select_option('', $LANG->line('revision_history'));
                 
            $query = $DB->query("SELECT tracker_id, item_date FROM exp_revision_tracker WHERE item_table = 'exp_templates' AND item_field = 'template_data' AND item_id = '$template_id' ORDER BY tracker_id DESC");
    
            if ($query->num_rows > 0)
            {             
                foreach ($query->result as $row)
                {
                    $r .= $DSP->input_select_option($row['tracker_id'], $LOC->set_human_time($row['item_date']));
                }  
                 
                $r .= $DSP->input_select_option('clear', $LANG->line('clear_revision_history'));  
            }
            
            $r .= $DSP->input_select_footer()
                 .$DSP->input_submit($LANG->line('view'), 'submit', "onclick='return viewRevision();'");
        }
        else
        {
            $r .= NBS; 
        }             
        $r .=  $DSP->div_c()
              .$DSP->td_c()
              .$DSP->tr_c()
              .$DSP->table_c()
              .$DSP->form_c();

        $r .= $message;
                
        $r .= $DSP->form('C=templates'.AMP.'M=update_template')
             .$DSP->input_hidden('template_id', $template_id);
      
        $r .= $DSP->div('itemWrapper')  
             .$DSP->input_textarea('template_data', $template_data, $SESS->userdata['template_size'], 'textarea', '100%')
             .$DSP->div_c();
             
              $selected = ($PREFS->ini('save_tmpl_revisions') == 'y') ? 1 : '';
              
        $r .= $DSP->table('', '', '6', '100%')
             .$DSP->tr()
             .$DSP->td('', '25%', '', '', 'top')
             .$DSP->qdiv('itemWrapper', $LANG->line('template_size').BR.$DSP->input_text('columns', $SESS->userdata['template_size'], '4', '2', 'input', '40px'));
             
        if ($user_blog == FALSE)
        {             
             $r .= $DSP->qdiv('itemWrapper', $LANG->line('save_history').NBS.NBS.$DSP->input_checkbox('save_history', 'y', $selected));
        }
             
        $r .= $DSP->input_submit($LANG->line('update'))
             .$DSP->td_c()
             .$DSP->td('', '75%')
             .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('template_notes').'</b>'.NBS.'-'.NBS.$LANG->line('template_notes_desc'))
             .$DSP->input_textarea('template_notes', $template_notes, '10', 'textarea', '100%')
             .$DSP->td_c()
             .$DSP->tr_c()
             .$DSP->table_c()
             .$DSP->form_c();
        
        
        $DSP->body = &$r;
    }
    // END    
 
    
    // -------------------------------   
    //  Update Template
    // -------------------------------   

    function update_template()
    {
        global $DSP, $IN, $DB, $LOC, $SESS, $FNS, $LANG;
    
        if ( ! $template_id = $IN->GBL('template_id', 'POST'))
        {
            return false;
        }
        
        if ( ! $this->template_access_privs(array('template_id' => $template_id)))
        {
        	return $DSP->no_access_message();
        }
   
                 
        $DB->query($DB->update_string('exp_templates', array('template_data' => $_POST['template_data'], 'template_notes' => $_POST['template_notes']), "template_id = '$template_id'")); 
    
        if ($IN->GBL('save_history', 'POST') == 'y')
        {
            $data = array(
                            'tracker_id' => '',
                            'item_id'    => $template_id,
                            'item_table' => 'exp_templates',
                            'item_field' => 'template_data',
                            'item_data'  => $_POST['template_data'],
                            'item_date'  => $LOC->now
                         );
    
    
            $DB->query($DB->insert_string('exp_revision_tracker', $data));
        }
        
        if (is_numeric($_POST['columns']))
        {  
            if ($SESS->userdata['template_size'] != $_POST['columns'])
            {
                $DB->query("UPDATE exp_members SET template_size = '".$_POST['columns']."' WHERE member_id = '".$SESS->userdata['member_id']."'");
           
                $SESS->userdata['template_size'] = $_POST['columns'];
            }
        }

        // Clear tag caching if we find the cache="yes" parameter in the template
        
        if (preg_match("#\s+cache=[\"']yes[\"']\s+#", stripslashes($_POST['template_data'])))
        {
            $FNS->clear_caching('tag');
        }
        
        // Clear cache files
        
        $FNS->clear_caching('all');
    
        $message = $DSP->qdiv('success', $LANG->line('template_updated'));
    
        return $this->edit_template($template_id, $message);
    }
    // END
    
    
    

    // -----------------------------
    //  View Revision History
    // -----------------------------   

    function view_template_revision()
    {
        global $DSP, $REGX, $IN, $DB, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
        
        $DSP->title  = $LANG->line('revision_history');        
        $DSP->crumb  = $LANG->line('revision_history');     
        
        $query = $DB->query("SELECT item_data FROM exp_revision_tracker WHERE tracker_id = '".$id."' ");
        
        $DSP->body = $DSP->input_textarea('template_data', $query->row['item_data'], 26, 'textarea', '100%');
    }
    // END


   
    // -----------------------------
    //  Clear Revision History
    // -----------------------------   

    function clear_revision_history()
    {
        global $DSP, $DB, $IN, $LANG;
    
        if ( ! $DSP->allowed_group('can_admin_templates'))
        {
            return $DSP->no_access_message();
        }
        
        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
    
        $DSP->title  = $LANG->line('revision_history');        
        $DSP->crumb  = $LANG->line('revision_history');        
        
        $query = $DB->query("DELETE FROM exp_revision_tracker WHERE item_id = '$id' AND item_table = 'exp_templates' AND item_field ='template_data'");
    
        $DSP->body = $DSP->qdiv('defaultCenter', BR.BR.'<b>'.$LANG->line('history_cleared').'</b>'.BR.BR.BR);
        
        $DSP->body .= $DSP->qdiv('defaultCenter', "<a href='javascript:window.close();'>".$LANG->line('close_window')."</a>".BR.BR.BR);
    
    }
    // END
   
   


    // -----------------------------
    //  Export template form
    // -----------------------------   
    
    function export_templates_form($group_id = '')
    {
        global $IN, $SESS, $DSP, $DB, $LANG;
        
        if ($group_id == '')
        {            
            if ( ! $group_id = $IN->GBL('id'))
            {
                return false;
            }
       }
                      
        if ($SESS->userdata['tmpl_group_id'] != 0)
        {
            $group_id = $SESS->userdata['tmpl_group_id'];
        }
        
        if ( ! $this->template_access_privs(array('group_id' => $group_id)))
        {
        	return $DSP->no_access_message();
        }       
        
        
        $LANG->fetch_language_file('admin');

        $sql  = "SELECT group_name FROM exp_template_groups WHERE group_id = '$group_id'";  
                          
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return false;
        }
                
        $r = $this->toggle_code();
        
        $r .= $DSP->heading($LANG->line('export_templates'));
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('choose_templates'));

        $r .= $DSP->form('C=templates'.AMP.'M=export'.AMP.'id='.$group_id, 'templates');

        $template_group  = $query->row['group_name'];
        
        $r .= $DSP->table('tableBorder', '0', '0', '40%').
              $DSP->tr().
              $DSP->td('tablePad'); 
    
        $r .= $DSP->table('', '0', '', '100%')
             .$DSP->tr()
             .$DSP->td('', '37%', '', '', 'top')
             .$DSP->heading(NBS.$template_group, 2)
             .$DSP->td_c()
             .$DSP->tr_c()
             .$DSP->tr()
             .$DSP->table_qcell('tableHeadingBold', $LANG->line('template_name'), '32%')
             .$DSP->table_qcell('tableHeadingBold', $DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.NBS.$LANG->line('select_all'), '17%')
             .$DSP->td_c()
             .$DSP->tr_c();

        $i = 0;
        
        $res = $DB->query("SELECT template_id, template_name, hits FROM exp_templates WHERE group_id = '$group_id' ORDER BY template_name");
            
        foreach ($res->result as $val)
        {     
            $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
          
            $r .= $DSP->tr();
            $r .= $DSP->table_qcell($style, $val['template_name']);

            $r .= $DSP->table_qcell($style, "<input type='checkbox' name=\"template[".$val['template_id']."]\" value='y' />")
                 .$DSP->tr_c();
        }
                
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();
        
        $r .= $DSP->qdiv('highlight', BR.$LANG->line('export_will_be_zip'));

        $r .= $DSP->qdiv('itemWrapper', $DSP->input_submit($LANG->line('export')));
             
        $r .= $DSP->form_c();
       
        $DSP->title =& $LANG->line('export_templates');        
        $DSP->crumb =& $LANG->line('export_templates');
        $DSP->body  =& $r; 
    }
    // END
    


    // -------------------------------------------
    //   JavaScript toggle code
    // -------------------------------------------    

    function toggle_code()
    {
        ob_start();
    
        ?>
        <script language="javascript" type="text/javascript"> 
        <!--
    
        function toggle(thebutton)
        {
            if (thebutton.checked) 
            {
               val = true;
            }
            else
            {
               val = false;
            }
                        
            var len = document.templates.elements.length;
        
            for (var i = 0; i < len; i++) 
            {
                var button = document.templates.elements[i];
                
                var name_array = button.name.split("["); 
                
                if (name_array[0] == "template") 
                {
                    button.checked = val;
                }
            }
            
            document.templates.toggleflag.checked = val;
        }
        
        //-->
        </script>
        <?php
    
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
        
        return $buffer;
    } 
    // END 

    
    // -----------------------------
    //  Export templates
    // -----------------------------   
    
    function export_templates()
    {
        global $IN, $SESS, $DSP, $DB, $LOC, $FNS, $LANG;
        
        
        if ( ! $group_id = $IN->GBL('id'))
        {
            return false;
        }
        
        // --------------------------------------
        // Is the user allowed to export?
        // --------------------------------------
                
        if ($SESS->userdata['tmpl_group_id'] != 0)
        {
            $group_id = $SESS->userdata['tmpl_group_id'];
        }
        
        if ( ! $this->template_access_privs(array('group_id' => $group_id)))
        {
        	return $DSP->no_access_message();
        }       

        // --------------------------------------
        // No templates?  Bounce them back
        // --------------------------------------

        if ( ! isset($_POST['template']))
        {
            return $this->export_templates_form($group_id);
        }


        // --------------------------------------
        // Is the selected compression supported?
        // --------------------------------------

        if ( ! @function_exists('gzcompress')) 
        {
            return $DSP->error_message($LANG->line('unsupported_compression'));
        }


        // --------------------------------------
        // Assign the name of the of the folder
        // --------------------------------------
        
        $query = $DB->query("SELECT group_name, is_site_default FROM exp_template_groups WHERE group_id = '$group_id'");
                        
        $directory = $query->row['group_name'].'_tmpl';
            
                    
        // --------------------------------------
        //  Fetch the template data and zip it
        // --------------------------------------

        require PATH_CP.'cp.utilities'.EXT;
        
        $zip = new Zipper;
        
        $temp_data = array();
        
        $zip->add_dir($directory.'/');

        foreach ($_POST['template'] as $key => $val)
        {
        	$query = $DB->query("SELECT template_data, template_name FROM exp_templates WHERE template_id = '$key'");

            $zip->add_file($query->row['template_data'], $directory.'/'.$query->row['template_name'].'.txt');
        }
        
        // -------------------------------------------
        //  Write out the headers
        // -------------------------------------------    
        
        ob_start();                
        
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE"))
        {
            header('Content-Type: application/x-zip');
            header('Content-Disposition: inline; filename="'.$directory.'.zip"');
			header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
        } 
        else 
        {
            header('Content-Type: application/x-zip');
            header('Content-Disposition: attachment; filename="'.$directory.'.zip"');
			header('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Pragma: no-cache');
        }
        
        echo $zip->output_zipfile();
                      
        $buffer = ob_get_contents();
        
        ob_end_clean(); 
        
        echo $buffer;
        
        exit;    
    }
    // END
    
    
    
    
    // -----------------------------
    //  Global Variables
    // -----------------------------   
    
    function global_variables($message = '')
    {
    	global $DSP, $DB, $LANG, $SESS;
    
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
    
    	$DSP->title = $LANG->line('global_variables');
    	$DSP->crumb = $LANG->line('global_variables');
		$DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=edit_global_var', $LANG->line('create_new_global_variable')));

    	$DSP->body  = $DSP->heading($LANG->line('global_variables'));
    	
    	$DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('global_variables_explanation'));
    	
    	if ($message != '')
    	{
    		$DSP->body .= $DSP->qdiv('success', $message);
    	}
    	
		$i = 0;
		
		$s1 = 'tableCellOne';
		$s2 = 'tableCellTwo';
		
		$id = ( ! defined('UB_BLOG_ID')) ? 0 : UB_BLOG_ID; 
		
		$query = $DB->query("SELECT variable_id, variable_name, variable_data FROM exp_global_variables WHERE user_blog_id = '$id' ");
		
		
		// -----------------------------
    	//  Table Header
		// -----------------------------   		

        $DSP->body .= $DSP->table('tableBorder', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->td('tablePad'); 

        $DSP->body .= $DSP->table('', '0', '0', '100%').
					  $DSP->tr().
					  $DSP->table_qcell('tableHeadingBold',
										($query->num_rows == 0) ? 
											array($LANG->line('global_variable_name')) : 
											array($LANG->line('global_variable_name'), 
												  $LANG->line('global_variable_syntax'),
												  $LANG->line('delete')
												 )
										).
					  $DSP->tr_c();
					  
		// -----------------------------
    	//  Table Rows
		// -----------------------------   		

        if ($query->num_rows == 0)
        {
			$DSP->body .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
											array(
													$DSP->qdiv('highlight', $LANG->line('no_global_variables'))
												  )
											);
        }
        else
        {
			foreach ($query->result as $row)
			{			
			
				$DSP->body .= $DSP->table_qrow( ($i++ % 2) ? $s1 : $s2, 
										array(
												$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=edit_global_var'.AMP.'id='.$row['variable_id'], $row['variable_name'])),
												$DSP->qspan('defaultBold', '{'.$row['variable_name'].'}'),
												$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=templates'.AMP.'M=delete_global_var'.AMP.'id='.$row['variable_id'], $LANG->line('delete'))),
											  )
										);
			}
		}	
        
        $DSP->body .= $DSP->table_c(); 

        $DSP->body .= $DSP->td_c()   
					 .$DSP->tr_c()      
					 .$DSP->table_c();  
    
    }
    // END
    
    


    // -----------------------------
    //  Create/Edit Global Variables
    // -----------------------------   
    
    function edit_global_variable()
    {
    	global $IN, $DSP, $DB, $LANG, $SESS;
    
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
    
    	$DSP->title = $LANG->line('global_variables');
    	$DSP->crumb = $LANG->line('global_variables');

    	$DSP->body  = $DSP->heading($LANG->line('global_variables'));
    	
        $DSP->body .= $DSP->form('C=templates'.AMP.'M=update_global_var');
     
     	$variable_name = '';
     	$variable_data = '';
     	
     	$id = $IN->GBL('id');
     	
		$ub_id = ( ! defined('UB_BLOG_ID')) ? 0 : UB_BLOG_ID; 
     
        if ($id != FALSE)
        {            
			$query = $DB->query("SELECT variable_name, variable_data, user_blog_id FROM exp_global_variables WHERE variable_id = '$id' ");
			
			if ($query->num_rows == 1)
			{
				if ($query->row['user_blog_id'] == $ub_id)
				{
            		$DSP->body .= $DSP->input_hidden('id', $id);
            		
					$variable_name = $query->row['variable_name'];
					$variable_data = $query->row['variable_data'];
				}
            }
        }
                
        $DSP->body .=  $DSP->div('paddedWrapper')
                      .$DSP->heading(BR.$LANG->line('variable_name', 'variable_name'), 5)
                      .$DSP->qdiv('itemWrapper',  $LANG->line('template_group_instructions'))
                      .$DSP->qdiv('itemWrapper',  $LANG->line('undersores_allowed'))
                      .$DSP->qdiv('itemWrapper', $DSP->input_text('variable_name', $variable_name, '20', '50', 'input', '240px'))
                      .$DSP->heading(BR.$LANG->line('variable_data'), 5)
             		  .$DSP->input_textarea('variable_data', $variable_data, '15', 'textarea', '100%')
                      .$DSP->div_c();
              
                                     
        if ($id == FALSE)
            $DSP->body .= $DSP->input_submit($LANG->line('submit'));
        else
            $DSP->body .= $DSP->input_submit($LANG->line('update'));
    
        $DSP->body .= $DSP->form_c();
    }
    // END
    




    // -----------------------------
    //  Insert/Update a Global Var
    // -----------------------------   
    
    function update_global_variable()
    {
    	global $IN, $DSP, $DB, $LANG, $SESS;
    
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
    

		if ($_POST['variable_name'] == '' || $_POST['variable_data'] == '')
		{
            return $DSP->error_message($LANG->line('all_fields_required'));
		}
        
        if ( ! preg_match("#^[a-zA-Z0-9_\-/]+$#i", $_POST['variable_name']))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }
        
        if (in_array($_POST['variable_name'], $this->reserved_vars))
        {
            return $DSP->error_message($LANG->line('reserved_name'));
        }
    	
     	
     	$id = $IN->GBL('id', 'POST');
     	
		$ub_id = ( ! defined('UB_BLOG_ID')) ? 0 : UB_BLOG_ID; 
     	
     
        if ($id != FALSE)
        {            
			$DB->query("UPDATE exp_global_variables SET variable_name = '".$DB->escape_str($_POST['variable_name'])."', variable_data = '".$DB->escape_str($_POST['variable_data'])."' WHERE variable_id = '$id' AND user_blog_id = '$ub_id'");
        
        	$msg = $LANG->line('global_var_updated');
        }
		else
		{
			$DB->query("INSERT INTO exp_global_variables (variable_id, variable_name, variable_data, user_blog_id) VALUES ('', '".$DB->escape_str($_POST['variable_name'])."',  '".$DB->escape_str($_POST['variable_data'])."', '$ub_id')");

        	$msg = $LANG->line('global_var_created');
		}

		return $this->global_variables($msg);
    }
    // END



    // -----------------------------
    //  Global Var Delete Conf
    // -----------------------------   

	function global_variable_delete_conf()
	{
        global $DSP, $DB, $IN, $LANG, $SESS;
        
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
    
        $DSP->title  = $LANG->line('delete_global_variable');        
        $DSP->crumb  = $LANG->line('delete_global_variable');        
    
        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
             	
		$ub_id = ( ! defined('UB_BLOG_ID')) ? 0 : UB_BLOG_ID; 
     
		$query = $DB->query("SELECT variable_name FROM exp_global_variables WHERE variable_id = '$id' AND user_blog_id = '$ub_id' ");
		
		if ($query->num_rows == 0)
		{
			return false;
		}
        
                
        $DSP->body = $DSP->form('C=templates'.AMP.'M=do_delete_global_var')
                    .$DSP->input_hidden('id', $id)
                    .$DSP->heading($LANG->line('delete_global_variable'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_this_variable').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['variable_name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
	}
	// END



    // -----------------------------
    //  Delete Global Variable
    // -----------------------------   

	function delete_global_variable()
	{
        global $DSP, $DB, $IN, $LANG, $SESS;
        
        if ($SESS->userdata['tmpl_group_id'] == 0)
        {
            if ( ! $DSP->allowed_group('can_admin_templates'))
            {
                return $DSP->no_access_message();
            }
        }
    
        if ( ! $id = $IN->GBL('id', 'POST'))
        {
            return false;
        }
             	
		$ub_id = ( ! defined('UB_BLOG_ID')) ? 0 : UB_BLOG_ID; 
     
		$query = $DB->query("SELECT count(*) AS count FROM exp_global_variables WHERE variable_id = '$id' AND user_blog_id = '$ub_id' ");
		
		if ($query->row['count'] == 0)
		{
			return false;
		}
        
		$DB->query("DELETE FROM exp_global_variables WHERE variable_id = '$id' AND user_blog_id = '$ub_id' ");


		return $this->global_variables($LANG->line('variable_deleted'));
	}
	// END


    
}
// END CLASS
?>