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
 File: cp.publish_ad.php
-----------------------------------------------------
 Purpose: The publish administration functions
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class PublishAdmin {

	// Default "open" and "closed" status colors

	var $status_color_open   = '009933';
	var $status_color_closed = '990000';


    //-----------------------------------------------------------
    // Constructor
    //-----------------------------------------------------------
    // All it does it fetch the language file needed by the class
    //-----------------------------------------------------------

    function PublishAdmin()
    {
        global $LANG, $DSP;
            
        // Fetch language file
        
        $LANG->fetch_language_file('publish_ad');
    }
    // END



    //-----------------------------------------------------------
    // Weblog management page
    //-----------------------------------------------------------
    // This function displays the "weblog management" page
    // accessed via the "admin" tab
    //-----------------------------------------------------------

    function weblog_overview($message = '')
    {
        global $LANG, $DSP, $DB;  
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
                              

        // Fetch weblogs
        
        $query = $DB->query("SELECT weblog_id, blog_title FROM exp_weblogs WHERE is_user_blog = 'n' ORDER BY blog_title");
        
        if ($query->num_rows == 0)
        {
            return $DSP->set_return_data(
                                        $LANG->line('admin'), 
                                        
                                        
                                        $DSP->heading($LANG->line('weblog_management')).       
                                        $message.
                                        $DSP->qdiv('', $LANG->line('no_weblogs_exist').$DSP->br(2)).
                                        $DSP->qdiv('crumbLinksR', $DSP->anchor( BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=new_weblog', $LANG->line('create_new_weblog')))
                                      );  
        }     
                
        $r  = $DSP->heading($LANG->line('weblog_management'));
        
        $r .= stripslashes($message);
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%');
              
        $r .= $DSP->tr().
              $DSP->td('tableHeadingBold', '', '4').
              $LANG->line('weblog_name').
              $DSP->td_c().
              $DSP->tr_c();
        
        $i = 0;
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
          
            $r .= $DSP->tr();
            
            $r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $row['blog_title']).$DSP->nbs(5));
            
            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_prefs'.AMP.'weblog_id='.$row['weblog_id'], 
                                $LANG->line('edit_preferences')
                              ));
            
            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=group_prefs'.AMP.'weblog_id='.$row['weblog_id'], 
                                $LANG->line('edit_groups')
                              ));

            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=delete_conf'.AMP.'weblog_id='.$row['weblog_id'], 
                                $LANG->line('delete')
                              ));
                                                                          
            $r .= $DSP->tr_c();
        }
        
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        // Assign output data
        
        $DSP->title  = $LANG->line('weblog_management');
        $DSP->crumb  = $LANG->line('weblog_management');
        $DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=new_weblog', $LANG->line('create_new_weblog')));
        $DSP->body = &$r;                            
            
    }
    // END



    //--------------------------------------------------------------
    // "Create new weblog" form
    //--------------------------------------------------------------
    // This function displays the form used to create a new weblog
    //--------------------------------------------------------------

    function new_weblog_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
       
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
                          
        $r = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=create_blog');
        
        $r .= $DSP->heading($LANG->line('create_new_weblog'));
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '', '100%');
        
        // Weblog "short name" field
        
        $r .= $DSP->tr().
              $DSP->table_qcell('tableCellOne', $DSP->required().NBS.$DSP->qspan('defaultBold', $LANG->line('short_weblog_name', 'blog_name')).$DSP->nbs(2).'-'.$DSP->nbs(2).$LANG->line('single_word_no_spaces')).
              $DSP->table_qcell('tableCellOne', $DSP->input_text('blog_name', '', '20', '40', 'input', '260px')).
              $DSP->tr_c();
        
        // Weblog "full name" field
        
        $r .= $DSP->tr().
              $DSP->table_qcell('tableCellTwo', $DSP->required().NBS.$DSP->qspan('defaultBold', $LANG->line('full_weblog_name', 'blog_title'))).
              $DSP->table_qcell('tableCellTwo', $DSP->input_text('blog_title', '', '20', '100', 'input', '260px')).
              $DSP->tr_c();

        // Text: * Indicates required fields
          
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
        
        // Submit button
        
        $r .= $DSP->qdiv('', BR.$DSP->required(1).$DSP->br(2).$DSP->input_submit($LANG->line('submit')));
              
        $r .= $DSP->form_c();
        
        // Assign output data
        
        $DSP->title = &$LANG->line('create_new_weblog');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list', $LANG->line('weblog_management')).$DSP->crumb_item($LANG->line('new_weblog'));
        $DSP->body  = &$r;                
    }
    // END




    //-----------------------------------------------------------
    // Weblog preferences form
    //-----------------------------------------------------------
    // This function displays the form used to edit the various 
    // preferences for a given weblog
    //-----------------------------------------------------------

    function edit_blog_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG, $FNS;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
        
        // Set default values
        
        $i            = 0;
        $blog_name    = '';
        $blog_title   = '';
        $cat_group    = '';
        $status_group = '';
        
        
        // If we don't have the $weblog_id variable, bail out.
        
        if ( ! $weblog_id = $IN->GBL('weblog_id'))
        {
            return false;
        }
            
        $query = $DB->query("SELECT * FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
                        
        // Build the output
        
        $r = '';
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_preferences');
        $r .= $DSP->input_hidden('weblog_id', $weblog_id);

        $r .= $DSP->heading($LANG->line('weblog_prefs'));
        $r .= $DSP->heading($blog_title, 2);
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '', '100%');
        $r .= $DSP->tr();
        $r .= $DSP->td('tableHeadingLargeBold', '100%', '2');
        $r .= $LANG->line('weblog_base_setup');
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        
        
        //-------------------------
        // General settings
        //------------------------
        
        // Weblog "short name" field

        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;
        
        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->required().NBS.$DSP->qspan('defaultBold', $LANG->line('short_weblog_name', 'blog_name')).$DSP->nbs(2).'-'.$DSP->nbs(2).$LANG->line('single_word_no_spaces'), '50%').
              $DSP->table_qcell($style, $DSP->input_text('blog_name', $blog_name, '20', '40', 'input', '260px'), '50%').
              $DSP->tr_c();
        
        // Weblog "full name" field
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->required().NBS.$DSP->qspan('defaultBold', $LANG->line('full_weblog_name', 'blog_title')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('blog_title', $blog_title, '20', '100', 'input', '260px'), '50%').
              $DSP->tr_c();
              
              
        // Weblog URL field
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('blog_url', 'blog_url')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('blog_url', $blog_url, '50', '80', 'input', '100%'), '50%').
              $DSP->tr_c();

              
        // Weblog descriptions field
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('blog_description', 'blog_descriptions')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('blog_description', $blog_description, '50', '225', 'input', '100%'), '50%').
              $DSP->tr_c();
        
        
        // Weblog Language
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('blog_lang', 'blog_lang')), '50%').
              $DSP->table_qcell($style, $FNS->encoding_menu('languages', 'blog_lang', $blog_lang), '50%').
              $DSP->tr_c();
        
        // Weblog Encoding
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('blog_encoding', 'blog_encoding')), '50%').
              $DSP->table_qcell($style, $FNS->encoding_menu('charsets', 'blog_encoding', $blog_encoding), '50%').
              $DSP->tr_c().
              $DSP->table_c();
              
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
        
        $r .= $DSP->qdiv('', BR.BR);
        
        //---------------------------
        // Administrative settings
        //---------------------------        
        
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%');
        $r .= $DSP->tr();
        $r .= $DSP->td('tableHeadingLargeBold', '100%', '2');
        $r .= $LANG->line('default_settings');
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
                
        
        // Default status menu
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('default_status')), '50%');
              
        $r .= $DSP->td($style, '50%').
              $DSP->input_select_header('deft_status');
        
        $query = $DB->query("SELECT * FROM  exp_statuses WHERE group_id = '$status_group' order by status");
        
        if ($query->num_rows == 0)
        {
			$selected = ($deft_status == 'open') ? 1 : '';
				
			$r .= $DSP->input_select_option('open', $LANG->line('open'), $selected);
	
			$selected = ($deft_status == 'closed') ? 1 : '';
			
			$r .= $DSP->input_select_option('closed', $LANG->line('closed'), $selected);        
        }
        else
        {
            foreach ($query->result as $row)
            {
                $selected = ($deft_status == $row['status']) ? 1 : '';
                
				$status_name = ($row['status'] == 'open' OR $row['status'] == 'closed') ? $LANG->line($row['status']) : $row['status'];
                                    
                $r .= $DSP->input_select_option($row['status'], $status_name, $selected);
            }
        }
        
        $r .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
                

        // Default category menu
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('default_category')), '50%');
              
        $r .= $DSP->td($style, '50%').
              $DSP->input_select_header('deft_category');
        
        $selected = '';
            
        $r .= $DSP->input_select_option('', $LANG->line('none'), $selected);
        
        $query = $DB->query("SELECT cat_id, cat_name FROM  exp_categories WHERE group_id = '$cat_group' order by cat_name");
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $selected = ($deft_category == $row['cat_id']) ? 1 : '';
                                    
                $r .= $DSP->input_select_option($row['cat_id'], $row['cat_name'], $selected);
            }
        }
        
        $r .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
                


        // Enable comments
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('deft_comments')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($deft_comments == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('deft_comments', 'y', $selected).$DSP->nbs(3);

              $selected = ($deft_comments == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('deft_comments', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();



        // Enable trackback pings
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('deft_trackbacks')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($deft_trackbacks == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('deft_trackbacks', 'y', $selected).$DSP->nbs(3);

              $selected = ($deft_trackbacks == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('deft_trackbacks', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();
             
             
             
        // Add trackback RDF to your pages
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('enable_trackbacks')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($enable_trackbacks == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('enable_trackbacks', 'y', $selected).$DSP->nbs(3);

              $selected = ($enable_trackbacks == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('enable_trackbacks', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();
             
             
        // Max trackback hits per hour
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('trackback_max_hits', 'trackback_max_hits')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('trackback_max_hits', $trackback_max_hits, '15', '16', 'input', '80px'), '50%').
              $DSP->tr_c();
             
             
        // Default field for trackback
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('trackback_field')), '50%');
            
        $r .= $DSP->td($style, '50%');
              
        $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE group_id = '$field_group'");              
              
        if ($query->num_rows == 0)
        {
            $r .= '<b>'.$LANG->line('no_field_group_selected').'</b>';
        }
        else
        {
            $r .= $DSP->input_select_header('trackback_field');
        
            foreach ($query->result as $row)
            {
                $selected = ($trackback_field == $row['field_id']) ? 1 : '';
                    
                $r .= $DSP->input_select_option($row['field_id'], $row['field_label'], $selected);
            }
            
            $r .= $DSP->input_select_footer();
        } 
        
        $r .=  $DSP->td_c()
        	  .$DSP->tr_c();

                
        
        // Default field for search excerpt
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('search_excerpt')), '50%');
            
        $r .= $DSP->td($style, '50%');
              
        $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE field_search = 'y' AND group_id = '$field_group'");              

		$r .= $DSP->input_select_header('search_excerpt');
	
		foreach ($query->result as $row)
		{
			$selected = ($search_excerpt == $row['field_id']) ? 1 : '';
				
			$r .= $DSP->input_select_option($row['field_id'], $row['field_label'], $selected);
		}
		
		$r .= $DSP->input_select_footer();
        
                
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
                
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        $r .= $DSP->qdiv('', BR.BR);
        
        //---------------------------
        // Weblog posting settings
        //---------------------------        

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '', '100%');
        $r .= $DSP->tr();
        $r .= $DSP->td('tableHeadingLargeBold', '100%', '2');
        $r .= $LANG->line('weblog_settings');
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();

              
        // HTML formatting
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('weblog_html_formatting')), '50%');
              
        $r .= $DSP->td($style, '50%').
              $DSP->input_select_header('weblog_html_formatting');

        $selected = ($weblog_html_formatting == 'none') ? 1 : '';
            
        $r .= $DSP->input_select_option('none', $LANG->line('convert_to_entities'), $selected);

        $selected = ($weblog_html_formatting == 'safe') ? 1 : '';
        
        $r .= $DSP->input_select_option('safe', $LANG->line('allow_safe_html'), $selected);
                
        $selected = ($weblog_html_formatting == 'all') ? 1 : '';
        
        $r .= $DSP->input_select_option('all', $LANG->line('allow_all_html'), $selected);
                
        $r .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();


        // Allow IMG URLs?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('weblog_allow_img_urls')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($weblog_allow_img_urls == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('weblog_allow_img_urls', 'y', $selected).$DSP->nbs(3);

              $selected = ($weblog_allow_img_urls == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('weblog_allow_img_urls', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();


        // Auto link URLs?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('auto_link_urls')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($weblog_auto_link_urls == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('weblog_auto_link_urls', 'y', $selected).$DSP->nbs(3);

              $selected = ($weblog_auto_link_urls == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('weblog_auto_link_urls', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
        
        $r .= $DSP->qdiv('', BR.BR);
        
        //---------------------------
        // Comment posting settings
        //---------------------------        
     

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
        
        $r .= $DSP->table('', '0', '', '100%');
        $r .= $DSP->tr();
        $r .= $DSP->td('tableHeadingLargeBold', '100%', '2');
        $r .= $LANG->line('comment_prefs');
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();


        // Are comments enabled?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_system_enabled')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_system_enabled == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_system_enabled', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_system_enabled == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_system_enabled', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();



        // Require membership for comment posting?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_require_membership')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_require_membership == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_require_membership', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_require_membership == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_require_membership', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();


        // Require email address for comment posting?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_require_email')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_require_email == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_require_email', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_require_email == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_require_email', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();
             
             
        // Require comment moderation?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_moderate')).$DSP->qdiv('itemWrapper', $LANG->line('comment_moderate_exp')), '50%')
      
             .$DSP->td($style, '50%');
        
              $selected = ($comment_moderate == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_moderate', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_moderate == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_moderate', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();
             

        // Max characters in comments

        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;
        
        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_max_chars', 'comment_max_chars')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('comment_max_chars', $comment_max_chars, '10', '5', 'input', '50px'), '50%').
              $DSP->tr_c();
              
              
        // Comment Timelock

        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;
        
        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qdiv('defaultBold', $LANG->line('comment_timelock', 'comment_timelock')).$DSP->qdiv('itemWrapper', $LANG->line('comment_timelock_desc')), '50%').
              $DSP->table_qcell($style, $DSP->input_text('comment_timelock', $comment_timelock, '10', '5', 'input', '50px'), '50%').
              $DSP->tr_c();
              
              
        // Default comment text formatting
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_text_formatting')), '50%');
              
        $r .= $DSP->td($style, '50%').
              $DSP->input_select_header('comment_text_formatting');

        $selected = ($comment_text_formatting == 'none') ? 1 : '';
            
        $r .= $DSP->input_select_option('none', $LANG->line('none'), $selected);

        $selected = ($comment_text_formatting == 'xhtml') ? 1 : '';
        
        $r .= $DSP->input_select_option('xhtml', $LANG->line('xhtml'), $selected);
                
        $selected = ($comment_text_formatting == 'br') ? 1 : '';
        
        $r .= $DSP->input_select_option('br', $LANG->line('auto_br'), $selected);
                
        $r .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();

              
        // HTML formatting
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_html_formatting')), '50%');
              
        $r .= $DSP->td($style, '50%').
              $DSP->input_select_header('comment_html_formatting');

        $selected = ($comment_html_formatting == 'none') ? 1 : '';
            
        $r .= $DSP->input_select_option('none', $LANG->line('convert_to_entities'), $selected);

        $selected = ($comment_html_formatting == 'safe') ? 1 : '';
        
        $r .= $DSP->input_select_option('safe', $LANG->line('allow_safe_html'), $selected);
                
        $selected = ($comment_html_formatting == 'all') ? 1 : '';
        
        $r .= $DSP->input_select_option('all', $LANG->line('allow_all_html_not_recommended'), $selected);
                
        $r .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();


        // Allow IMG URLs?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_allow_img_urls')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_allow_img_urls == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_allow_img_urls', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_allow_img_urls == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_allow_img_urls', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();


        // Auto link URLs?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('auto_link_urls')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_auto_link_urls == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_auto_link_urls', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_auto_link_urls == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_auto_link_urls', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();


        // Comment notify?
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr()
             .$DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_notify')), '50%')
             .$DSP->td($style, '50%');
        
              $selected = ($comment_notify == 'y') ? 1 : '';
                
        $r .= $LANG->line('yes')
             .$DSP->input_radio('comment_notify', 'y', $selected).$DSP->nbs(3);

              $selected = ($comment_notify == 'n') ? 1 : '';

        $r .= $LANG->line('no')
             .$DSP->input_radio('comment_notify', 'n', $selected)
             .$DSP->td_c()
             .$DSP->tr_c();

        // Comment emails
        
        $style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo' ;

        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('comment_notify_emails', 'comment_notify_emails')).BR.$LANG->line('comment_notify_note'), '50%').
              $DSP->table_qcell($style, $DSP->input_text('comment_notify_emails', $comment_notify_emails, '50', '255', 'input', '100%'), '50%').
              $DSP->tr_c();

        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();
                   
        // BOTTOM SECTION OF PAGE
                

        // Text: * Indicates required fields
          
        $r .= $DSP->div().BR;

        $r .= $DSP->required(1).BR.BR;
    
        // "Submit" button

        $r .= $DSP->input_submit($LANG->line('update'));

        $r.= $DSP->div_c().$DSP->form_c();         
        
        
        $DSP->body = &$r;
        
        $DSP->title = $LANG->line('weblog_prefs');
        
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list', $LANG->line('weblog_management')).$DSP->crumb_item($LANG->line('edit_weblog'));
    }
    // END
   
  
  
  
  
  
  
  
    //-----------------------------------------------------------
    // Weblog group preferences form
    //-----------------------------------------------------------
    // This function displays the form used to edit the various 
    // preferences and group assignements for a given weblog
    //-----------------------------------------------------------

    function edit_group_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
        
        // Set default values
        
        $i            = 0;
        
        
        // If we don't have the $weblog_id variable, bail out.
        
        if ( ! $weblog_id = $IN->GBL('weblog_id'))
        {
            return false;
        }
            
        $query = $DB->query("SELECT * FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
                        
        // Build the output
        
        $DSP->body .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_preferences');
        $DSP->body .= $DSP->input_hidden('weblog_id', $weblog_id);
        $DSP->body .= $DSP->input_hidden('blog_name',  $blog_name);
        $DSP->body .= $DSP->input_hidden('blog_title', $blog_title);
        
        $DSP->body .= $DSP->heading($LANG->line('edit_group_prefs'));
        $DSP->body .= $DSP->heading($blog_title, 2);

        $DSP->body .= $DSP->table('tableBorder', '0', '0', '100%');
        $DSP->body .= $DSP->tr();
        $DSP->body .= $DSP->td('tablePad'); 
        
        $DSP->body .= $DSP->table('', '0', '', '100%');
        $DSP->body .= $DSP->tr();
        $DSP->body .= $DSP->table_qcell('tableHeadingBold', $LANG->line('preference'));
        $DSP->body .= $DSP->table_qcell('tableHeadingBold', $LANG->line('value'));
        $DSP->body .= $DSP->tr_c();
        
        
        // GROUP FIELDS
        
        $g = '';

        // Category group select list
        
        $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
           
        $query = $DB->query("SELECT group_id, group_name FROM exp_category_groups ORDER BY group_name");
        
        $g .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('category_group')));
        
        $g .= $DSP->td($style).
              $DSP->input_select_header('cat_group');
        
        $selected = '';

        $g .= $DSP->input_select_option('', 'None', $selected);
                 
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $selected = ($cat_group == $row['group_id']) ? 1 : '';
                                        
                $g .= $DSP->input_select_option($row['group_id'], $row['group_name'], $selected);
            }
        }
        
        $g .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();           
        
    
        // Status group select list
        
        $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
        
        $query = $DB->query("SELECT group_id, group_name FROM exp_status_groups ORDER BY group_name");
    
        $g .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('status_group')));
              
        $g .= $DSP->td($style).
              $DSP->input_select_header('status_group');
        
        $selected = '';

        $g .= $DSP->input_select_option('', 'None', $selected);
    
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $selected = ($status_group == $row['group_id']) ? 1 : '';
                                        
                $g .= $DSP->input_select_option($row['group_id'], $row['group_name'], $selected);
            }
        }

        $g .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
        
            
        // Field group select list
        
        $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
        
        $query = $DB->query("SELECT group_id, group_name FROM exp_field_groups ORDER BY group_name");
    
        $g .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_group')));
        
        $g .= $DSP->td($style).
              $DSP->input_select_header('field_group');
        
        $selected = '';

        $g .= $DSP->input_select_option('', 'None', $selected);
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $selected = ($field_group == $row['group_id']) ? 1 : '';
                                        
                $g .= $DSP->input_select_option($row['group_id'], $row['group_name'], $selected);
            }
        }

        $g .= $DSP->input_select_footer().
              $DSP->td_c().
              $DSP->tr_c();
              
                
        $DSP->body .= $g;
        
        // BOTTOM SECTION OF PAGE
                
        // Table end
        
        $DSP->body .= $DSP->table_c();
        
        $DSP->body .= $DSP->td_c()   
                     .$DSP->tr_c()      
                     .$DSP->table_c();      

        // Text: * Indicates required fields
          
        $DSP->body .= $DSP->div();
    
        // "Submit" button

        $DSP->body .= $DSP->input_submit($LANG->line('update'));

        $DSP->body .= $DSP->div_c().$DSP->form_c();         
        
        $DSP->title = $LANG->line('edit_weblog');
        
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list', $LANG->line('weblog_management')).$DSP->crumb_item($LANG->line('edit_weblog'));
    }
    // END
   
  
  
  
  
  
    
    //-----------------------------------------------------------
    // Weblog preference submission handler
    //-----------------------------------------------------------
    // This function receives the submitted weblog preferences
    // and stores them in the database.
    //-----------------------------------------------------------

    function update_weblog_prefs()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG, $FNS, $PREFS;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
        
        // If the $weblog_id variable is present we are editing an
        // existing weblog, otherwise we are creating a new one
        
        $edit = (isset($_POST['weblog_id'])) ? TRUE : FALSE;
        
        
        // Check for required fields

        $error = array();
        
        if ($_POST['blog_name'] == '')
        {
            $error[] = $LANG->line('no_weblog_name');
        }
          
        if ($_POST['blog_title'] == '')
        {
            $error[] = $LANG->line('no_weblog_title');
        }
        
        if (preg_match("/\W+/i", $_POST['blog_name']))
        {
            $error[] = $LANG->line('invalid_short_name');
        }
  
         if (count($error) > 0)
         {
            $msg = '';
            
            foreach($error as $val)
            {
                $msg .= $val.BR;  
            }
            
            return $DSP->error_message($msg);
         }   
  
  
        // Is the weblog name taken?
        
        $sql = "SELECT count(*) as count FROM exp_weblogs WHERE blog_name = '".$DB->escape_str($_POST['blog_name'])."'";
        
        if ($edit == TRUE)
        {
            $sql .= " AND weblog_id != '".$DB->escape_str($_POST['weblog_id'])."'";
        } 
        
        $query = $DB->query($sql);        
      
        if ($query->row['count'] > 0)
        {
            return $DSP->error_message($LANG->line('taken_weblog_name'));
        }
             
        // Construct the query based on whether we are updating or inserting
   
        if ($edit == FALSE)
        {  
            unset($_POST['weblog_id']);
            
            $_POST['blog_url']      = $FNS->fetch_site_index();
            $_POST['blog_lang']     = $PREFS->ini('xml_lang');
            $_POST['blog_encoding'] = $PREFS->ini('charset');            
            
            // Assign field group if there is only one
            
            $query = $DB->query("SELECT group_id FROM exp_field_groups");
            
            if ($query->num_rows == 1)
            {
                $_POST['field_group'] = $query->row['group_id'];
            }
            
            // Insert data
            
            $sql = $DB->insert_string('exp_weblogs', $_POST);  
            
            $DB->query($sql);
            
            $insert_id = $DB->insert_id;
            
            $success_msg = $LANG->line('weblog_created');
            
            $crumb = $DSP->crumb_item($LANG->line('new_weblog'));

            $LOG->log_action($success_msg.$DSP->nbs(2).$_POST['blog_title']);            
        }
        else
        {        
            $sql = $DB->update_string('exp_weblogs', $_POST, 'weblog_id='.$_POST['weblog_id']);  
            
            $DB->query($sql);

            $success_msg = $LANG->line('weblog_updated');
            
            $crumb = $DSP->crumb_item($LANG->line('update'));
        }

        
        $message = $DSP->qdiv('itemWrapper', $DSP->qspan('success', $success_msg).NBS.NBS.'<b>'.$_POST['blog_title'].'</b>');

        return $this->weblog_overview($message);
        
    }
    // END  
    
    
    
    //-----------------------------------------------------------
    // Delete weblog confirm
    //-----------------------------------------------------------
    // Warning message shown when you try to delete a weblog
    //-----------------------------------------------------------

    function delete_weblog_conf()
    {  
        global $DSP, $IN, $DB, $LANG;

        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }  

        if ( ! $weblog_id = $IN->GBL('weblog_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT blog_title FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
        
        $DSP->title = $LANG->line('delete_weblog');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list', $LANG->line('weblog_administration')).$DSP->crumb_item($LANG->line('delete_weblog'));

        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=delete'.AMP.'weblog_id='.$weblog_id)
                    .$DSP->input_hidden('weblog_id', $weblog_id)
                    .$DSP->heading($LANG->line('delete_weblog'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_weblog_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['blog_title'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    //-----------------------------------------------------------
    // Delete weblog
    //-----------------------------------------------------------
    // This function deletes a given weblog
    //-----------------------------------------------------------

    function delete_weblog()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
  
        if ( ! $weblog_id = $IN->GBL('weblog_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT blog_title FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
        
        $blog_title = $query->row['blog_title'];
        
        $LOG->log_action($LANG->line('weblog_deleted').NBS.NBS.$blog_title); 
        
        $query = $DB->query("SELECT entry_id from exp_weblog_titles WHERE weblog_id = '$weblog_id'");
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $DB->query("DELETE FROM exp_weblog_data WHERE entry_id = '".$row['entry_id']."'");              
            }
        }
        
        $DB->query("DELETE FROM exp_weblog_titles WHERE weblog_id = '$weblog_id'");  
                
        $DB->query("DELETE FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
                
        return $this->weblog_overview($DSP->qdiv('itemWrapper', $DSP->qspan('success', $LANG->line('weblog_deleted')).NBS.NBS.'<b>'.$blog_title.'</b>'));
    }
    // END    
   
   
   
   
   
   
//=====================================================================
//  CATEGORY ADMINISTRATION FUNCTIONS
//=====================================================================
   
   
   
    //-----------------------------------------------------------
    // Category overview page
    //-----------------------------------------------------------
    // This function displays the "categories" page, accessed
    // via the "admin" tab
    //-----------------------------------------------------------

    function category_overview($message = '')
    {
        global $LANG, $DSP, $SESS, $DB;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Fetch category groups
        
        $sql = "SELECT group_id, group_name FROM exp_category_groups WHERE exp_category_groups.is_user_blog = 'n' ORDER BY group_name";
                
        $query = $DB->query($sql);
              
        if ($query->num_rows == 0)
        {        
            return $DSP->set_return_data(
                                        $LANG->line('categories'), 
                                        $DSP->heading($LANG->line('categories')).       
                                        stripslashes($message).
                                        $DSP->qdiv('itemWrapper', $LANG->line('no_category_group_message')).
                                        $DSP->qdiv('itmeWrapper', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=cat_group_editor', $LANG->line('create_new_category_group'))),
                                        $LANG->line('categories')
                                      );  
        }     
              
        $r  = $DSP->heading($LANG->line('categories')).       
              stripslashes($message);
              
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '4').
              $LANG->line('category_group').
              $DSP->td_c().
              $DSP->tr_c();
        
        $i = 0;
        
        foreach($query->result as $row)
        {
        	// This is not efficient to put this query in the loop.
        	// Originally I did it with a join above, but there is a bug on OS X Server
        	// that I couldn't find a work-around for.  So... query in the loop it it.
        
        	$res = $DB->query("SELECT COUNT(*) AS count FROM exp_categories WHERE group_id = '".$row['group_id']."'");
        	$count = $res->row['count'];
        
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
            

            $r .= $DSP->tr().
                  $DSP->td($style, '35%').
                  $DSP->qspan('defaultBold', $row['group_name']).
                  $DSP->td_c();
            
            $r .= $DSP->table_qcell($style,
                  '('.$count.')'.$DSP->nbs(2).         
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=category_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('add_edit_categories')
                              ));
            

            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=cat_group_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('edit_group_name')
                              ));


            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=cat_group_del_conf'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('delete_group')
                              )).
                  $DSP->tr_c();
        }
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        $DSP->title  = $LANG->line('category_groups');
        $DSP->crumb  = $LANG->line('category_groups');
        $DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=cat_group_editor', $LANG->line('create_new_category_group')));
                
        $DSP->body = &$r;
    }
    // END
   
   
   
    //-----------------------------------------------------------
    // Category group form
    //-----------------------------------------------------------
    // This function shows the form used to define a new category
    // group or edit an existing one
    //-----------------------------------------------------------

    function edit_category_group_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Set default values
        
        $edit       = FALSE;
        $group_id   = '';
        $group_name = '';
        
        // If we have the group_id variable, it's an edit request, so fetch the category data
        
        if ($group_id = $IN->GBL('group_id'))
        {
            $edit = TRUE;
            
            $query = $DB->query("SELECT * FROM exp_category_groups WHERE group_id = '$group_id'");
            
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }    
        
        
        $title = ($edit == FALSE) ? $LANG->line('create_new_category_group') : $LANG->line('edit_category_group');
                
        // Build our output
        
        $r = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_cat_group');
     
        if ($edit == TRUE)
            $r .= $DSP->input_hidden('group_id', $group_id);
        
        
        $r .= $DSP->heading($title);
                
        $r .= $DSP->div('paddedWrapper').
              $LANG->line('name_of_category_group', 'group_name').
              BR.
              $DSP->input_text('group_name', $group_name, '20', '50', 'input', '260px').
              $DSP->div_c();
        
                
        if ($edit == FALSE)
            $r .= $DSP->input_submit($LANG->line('submit'));
        else
            $r .= $DSP->input_submit($LANG->line('update'));
    
        $r .= $DSP->form_c();

        $DSP->title = &$title;
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=categories', $LANG->line('category_groups')).$DSP->crumb_item($title);
        $DSP->body  = &$r;                

    }
    // END
   
   
   
    //-----------------------------------------------------------
    // Create/update category group
    //-----------------------------------------------------------
    // This function receives the submission from the group
    // form and stores it in the database
    //-----------------------------------------------------------

    function update_category_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // If the $group_id variable is present we are editing an
        // existing group, otherwise we are creating a new one
        
        $edit = (isset($_POST['group_id'])) ? TRUE : FALSE;
                
        if ($_POST['group_name'] == '')
        {
            return $this->edit_category_group_form();
        }
        
        
        if ( ! preg_match("#^[a-zA-Z0-9_\-/\s]+$#i", $_POST['group_name']))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }
  
        // Is the group name taken?
        
        $sql = "SELECT count(*) as count FROM exp_category_groups WHERE group_name = '".$DB->escape_str($_POST['group_name'])."'";
        
        if ($edit == TRUE)
        {
            $sql .= " AND group_id != '".$_POST['group_id']."'";
        } 
        
        $query = $DB->query($sql);        
      
        if ($query->row['count'] > 0)
        {
            return $DSP->error_message($LANG->line('taken_category_group_name'));
        }
     
        // Construct the query based on whether we are updating or inserting
   
        if ($edit == FALSE)
        {  
            unset($_POST['group_id']);

            $sql = $DB->insert_string('exp_category_groups', $_POST);  
            
            $success_msg = $LANG->line('category_group_created');
            
            $crumb = $DSP->crumb_item($LANG->line('new_weblog'));
            
            $LOG->log_action($LANG->line('category_group_created').$DSP->nbs(2).$_POST['group_name']); 

        }
        else
        {        
            $sql = $DB->update_string('exp_category_groups', $_POST, 'group_id='.$_POST['group_id']);  
            
            $success_msg = $LANG->line('category_group_updated');
            
            $crumb = $DSP->crumb_item($LANG->line('update'));
        }

        
        $DB->query($sql);
        
        $message = $DSP->div('success').'<b>'.$success_msg.$DSP->nbs(2).$_POST['group_name'].'</b>';

        if ($edit == FALSE)
        {            
            $query = $DB->query("SELECT weblog_id from exp_weblogs WHERE is_user_blog = 'n'");
            
            if ($query->num_rows > 0)
            {
                $message .= $DSP->br(2).$DSP->qspan('alert', $LANG->line('assign_group_to_weblog')).$DSP->nbs(2);
                
                if ($query->num_rows == 1)
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=group_prefs'.AMP.'weblog_id='.$query->row['weblog_id'];                
                }
                else
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list';
                }
                
                $message .= $DSP->anchor(BASE.AMP.$link, $LANG->line('click_to_assign_group'));
            }
        }
        
        $message .= $DSP->div_c();

        return $this->category_overview($message);
    }
    // END  
      
    
    //-----------------------------------------------------------
    // Delete category group confirm
    //-----------------------------------------------------------
    // Warning message if you try to delete a category group
    //-----------------------------------------------------------

    function delete_category_group_conf()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT group_name FROM exp_category_groups WHERE group_id = '$group_id'");
        
        $DSP->title = $LANG->line('delete_group');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=categories', $LANG->line('category_groups')).$DSP->crumb_item($LANG->line('delete_group'));


        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=delete_group'.AMP.'group_id='.$group_id)
                    .$DSP->input_hidden('group_id', $group_id)
                    .$DSP->heading($LANG->line('delete_group'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_cat_group_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['group_name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    //-----------------------------------------------------------
    // Delete categroy group
    //-----------------------------------------------------------
    // This function deletes the category group and all 
    // associated catetgories
    //-----------------------------------------------------------

    function delete_category_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;

        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_name FROM exp_category_groups WHERE group_id = '$group_id'");
        
        $name = $query->row['group_name'];
        
        $DB->query("DELETE FROM exp_category_groups WHERE group_id = '$group_id'");
        
        $DB->query("DELETE FROM exp_categories WHERE group_id = '$group_id'");
        
        $message = $DSP->qdiv('', $DSP->qspan('success', $LANG->line('category_group_deleted')).NBS.NBS.'<b>'.$name.'</b>'.BR.BR);
        
        $LOG->log_action($LANG->line('category_group_deleted').$DSP->nbs(2).$name);        

        return $this->category_overview($message);
    }
    // END    
    
    
    
  
    //-----------------------------------------------------------
    // Category management page
    //-----------------------------------------------------------
    // This function shows the list of current categories, as
    // well as the form used to submit a new category
    //-----------------------------------------------------------

    function category_manager($group_id = '')
    {  
        global $DSP, $IN, $DB, $LANG;
          
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ($group_id == '')
        {
            if ( ! $group_id = $IN->GBL('group_id'))
            {
                return false;
            }
        }

        
        $query = $DB->query("SELECT group_name FROM  exp_category_groups WHERE group_id = '$group_id'");
        
        $group_name = $query->row['group_name'];
        
            
        $r = $DSP->heading($group_name);
        
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('', '48%', '', '', 'top');

        $r .= $DSP->table('tableBorder', '0', '0', '95%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('category_group').':'.$DSP->nbs(2).'<b>'.$query->row['group_name'].'</b>').
              $DSP->tr_c();     
        
        $query = $DB->query("SELECT status_id, status FROM  exp_statuses WHERE group_id = '$group_id'");

        $r .= $DSP->tr().
              $DSP->td('tableCellOne', '', '', '', 'top');
      
      
        // Fetch the category tree  
        
        require PATH_CP.'cp.publish'.EXT;
        
        $PUB = new Publish;

        $PUB->category_tree('text', $group_id);

        if (count($PUB->categories) == 0)
        {
            $r .= $LANG->line('no_category_message');
        }
        else
        {    
            $r .= $DSP->div('leftPad');
                    
            foreach ($PUB->categories as $val)
            {
            	$prefix = (strlen($val['0']) == 1) ? NBS.NBS : NBS;
            
                $r .= $DSP->qdiv('category', $val['0'].$prefix.$val['1']);
            }
            
            $r .= $DSP->div_c();
        }
        
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();       
              
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        // Right side of page      
        
        $r .= $DSP->td_c().
              $DSP->td('rightCel', '52%', '', '', 'top');
              
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_category'.AMP.'group_id='.$group_id).
              $DSP->input_hidden('group_id', $group_id);
        
        $r .= $DSP->heading($LANG->line('add_new_category'), 5);
         
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_name', 'cat_name')).
              $DSP->input_text('cat_name', '', '40', '60', 'input', '320px').
              $DSP->div_c();
        
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_image', 'cat_image')).
              $DSP->qdiv('', $LANG->line('category_img_blurb')).
              $DSP->input_text('cat_image', '', '40', '120', 'input', '320px').
              $DSP->div_c();


        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_parent')).
              $DSP->input_select_header('parent_id').
              $DSP->input_select_option('0', $LANG->line('none'));

        $query = $DB->query("SELECT cat_id, cat_name FROM  exp_categories WHERE group_id = '$group_id' order by cat_name");
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $r .= $DSP->input_select_option($row['cat_id'], '('.$row['cat_id'].')'.NBS.NBS.$row['cat_name']);
            }
        }
        
        $r .= $DSP->input_select_footer().
              $DSP->div_c();

        $r .= $DSP->div().
              BR.
              $DSP->input_submit($LANG->line('submit')).
              $DSP->div_c().
              $DSP->form_c();
              
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();   
              
       // Assign output data       
              
        $DSP->title = $LANG->line('categories');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=categories', $LANG->line('category_groups')).$DSP->crumb_item($LANG->line('categories'));
        $DSP->body  = &$r;

    }
    // END  
    


    //-----------------------------------------------------------
    // Edit category form
    //-----------------------------------------------------------
    // This function displays an existing category in a form
    // so that it can be edited.
    //-----------------------------------------------------------

    function edit_category_form()
    {
        global $DSP, $IN, $DB, $REGX, $LANG;


        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $cat_id = $IN->GBL('cat_id'))
        {
            return false;
        }
    
 
        $query = $DB->query("SELECT cat_id, cat_name, cat_image, group_id, parent_id FROM  exp_categories WHERE cat_id = '$cat_id'");
        
        $group_id  = $query->row['group_id'];
        $cat_name  = $query->row['cat_name'];
        $cat_image = $query->row['cat_image'];
        $cat_id    = $query->row['cat_id'];
        $parent_id = $query->row['parent_id'];
    

        // Build our output
        
        $r  = $DSP->heading($LANG->line('edit_category'));
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_category').
              $DSP->input_hidden('cat_id', $cat_id).
              $DSP->input_hidden('group_id', $group_id);
         
         
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_name', 'cat_name')).
              $DSP->input_text('cat_name', $cat_name, '20', '60', 'input', '260px').
              $DSP->div_c();
              
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_image', 'cat_image')).
              $DSP->qdiv('', $LANG->line('category_img_blurb')).
              $DSP->input_text('cat_image', $cat_image, '40', '120', 'input', '320px').
              $DSP->div_c();

              
        
        $r .= $DSP->div('itemWrapper').
              $DSP->qdiv('defaultBold', BR.$LANG->line('category_parent')).
              $DSP->input_select_header('parent_id').     
              $DSP->input_select_option('0', $LANG->line('none'));
        
        $query = $DB->query("SELECT cat_id, cat_name FROM  exp_categories WHERE group_id = '$group_id' order by cat_name");
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $selected = ($row['cat_id'] == $parent_id) ? '1' : '';   
            
                $r .= $DSP->input_select_option($row['cat_id'], '('.$row['cat_id'].')'.NBS.NBS.$row['cat_name'], $selected);
            }
        }
        
        $r .= $DSP->input_select_footer().
              $DSP->div_c();
        
        $r .= $DSP->div('itemWrapper').
              BR.
              $DSP->input_submit($LANG->line('update')).
              $DSP->div_c();

        $r .= $DSP->form_c();

        $r .= $DSP->div('itemWrapper').
              BR.'<b>'.
              $DSP->anchor(
                            BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=del_category'.AMP.'cat_id='.$cat_id, 
                            $LANG->line('delete_category')
                          ).'</b>'.
              $DSP->div_c();
  
  
  
        $DSP->title = $LANG->line('edit_category');
        $DSP->crumb = $DSP->anchor( BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=categories', $LANG->line('category_groups')).
                      $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=category_editor'.AMP.'group_id='.$group_id, $LANG->line('categories'))).
                      $DSP->crumb_item($LANG->line('edit_category'));

        $DSP->body = &$r;                                  
    }
    // END
    
    

    //-----------------------------------------------------------
    // Category submission handler
    //-----------------------------------------------------------
    // This function receives the category information after
    // being submitted from the form (new or edit) and stores
    // the info in the database.
    //-----------------------------------------------------------

    function update_category()
    {
        global $DB, $DSP, $IN, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        $edit = ( ! $IN->GBL('cat_id', 'POST')) ? FALSE : TRUE;

        
        if ( ! $IN->GBL('cat_name', 'POST'))
        {
            return $this->category_manager($IN->GBL('group_id', 'POST'));
        }
        
		/*
		
		OLD CATEGORY TRAP
		
        if ( ! preg_match("#^[a-zA-Z0-9_\-/\s\.]+$#i", $IN->GBL('cat_name', 'POST')))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }
		*/
		
        
        if ($edit == FALSE)
        {
            $sql = $DB->insert_string('exp_categories', $_POST);            
        }
        else
        {
        
            if ($_POST['cat_id'] == $_POST['parent_id'])
            {
                $_POST['parent_id'] = 0;  
            }
        
        
            $sql = $DB->update_string(
                                        'exp_categories',
                                        
                                        array(
                                                'cat_name'  => $IN->GBL('cat_name', 'POST'),
                                                'cat_image' => $IN->GBL('cat_image', 'POST'),
                                                'parent_id' => $IN->GBL('parent_id', 'POST')
                                             ),
                                            
                                        array(
                                                'cat_id'    => $IN->GBL('cat_id', 'POST'),
                                                'group_id'  => $IN->GBL('group_id', 'POST')            
                                              )                
                                     );              
        }

        $DB->query($sql);

        return $this->category_manager($IN->GBL('group_id', 'POST'));
    }
    // END
    

    //-----------------------------------------------------------
    // Delete category
    //-----------------------------------------------------------
    // Deletes a cateogory and removes it from all weblog entries
    //-----------------------------------------------------------

    function delete_category()
    {  
        global $DSP, $IN, $DB;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $cat_id = $IN->GBL('cat_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT group_id FROM exp_categories WHERE cat_id = '$cat_id'");
        
        $group_id = $query->row['group_id'];
        
        $DB->query("DELETE FROM exp_category_posts WHERE cat_id = '$cat_id'");
        
        $DB->query("UPDATE exp_categories set parent_id = '0' WHERE parent_id = '$cat_id' AND group_id = '$group_id'");
        
        $DB->query("DELETE FROM exp_categories WHERE cat_id = '$cat_id' AND group_id = '$group_id'");
                
        $this->category_manager($group_id);
    }
    // END    
    

  

   
//=====================================================================
//  STATUS ADMINISTRATION FUNCTIONS
//=====================================================================

  
  
    //-----------------------------------------------------------
    // Status overview page
    //-----------------------------------------------------------
    // This function show the list of current status groups.
    // It is accessed by clicking "Custom entry statuses"
    // in the "admin" tab
    //-----------------------------------------------------------

    function status_overview($message = '')
    {
        global $LANG, $DSP, $DB;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Fetch category groups
        
        $sql = "SELECT exp_status_groups.group_id, exp_status_groups.group_name,
                COUNT(exp_statuses.group_id) as count 
                FROM exp_status_groups
                LEFT JOIN exp_statuses ON (exp_status_groups.group_id = exp_statuses.group_id)
                GROUP BY exp_status_groups.group_id
                ORDER BY exp_status_groups.group_name";        
        
        $query = $DB->query($sql);              
              
        if ($query->num_rows == 0)
        {

            return $DSP->set_return_data(
                                        $LANG->line('status_groups'), 
                                        
                                        $DSP->heading($LANG->line('status_groups')).       
                                        stripslashes($message).
                                        $DSP->qdiv('itemWrapper', $LANG->line('no_status_group_message')).
                                        $DSP->qdiv('itemWrapper',
                                        $DSP->anchor(
                                                        BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_group_editor', 
                                                        $LANG->line('create_new_status_group')
                                                     )),
                                        $LANG->line('status_groups')
                                      );  
        }     
       
       
       
        $r  = $DSP->heading($LANG->line('status_groups')).       
              stripslashes($message);

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
              
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '4').
              $LANG->line('status_group').
              $DSP->td_c().
              $DSP->tr_c();
        

        $i = 0;
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

            $r .= $DSP->tr();
            
            $r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $row['group_name']));
            

            $r .= $DSP->table_qcell($style, 
                  '('.$row['count'].')'.$DSP->nbs(2).          
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('add_edit_statuses')
                              ));

            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_group_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('edit_status_group_name')
                              ));


            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_group_del_conf'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('delete_status_group')
                              ));

            $r .= $DSP->tr_c();
        }
        
        $r .= $DSP->table_c();
    
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        $DSP->title  = $LANG->line('status_groups');
        $DSP->crumb  = $LANG->line('status_groups');
        $DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_group_editor', $LANG->line('create_new_status_group')));

        $DSP->body  = &$r;
    }
    // END
  
  

    //-----------------------------------------------------------
    // New/edit status group form
    //-----------------------------------------------------------
    // This function lets you create or edit a status group
    //-----------------------------------------------------------

    function edit_status_group_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
      
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Set default values
        
        $edit       = FALSE;
        $group_id   = '';
        $group_name = '';
        
        // If we have the group_id variable it's an edit request, so fetch the status data
        
        if ($group_id = $IN->GBL('group_id'))
        {
            $edit = TRUE;
            
            $query = $DB->query("SELECT * FROM exp_status_groups WHERE group_id = '$group_id'");
            
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }    
        
            
        if ($edit == FALSE)
            $title = $LANG->line('create_new_status_group');
        else
            $title = $LANG->line('edit_status_group');        
        
        // Build our output
        
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_status_group');
     
        if ($edit == TRUE)
            $r .= $DSP->input_hidden('group_id', $group_id);
            
        
        $r .= $DSP->heading($title);
                
        $r .= $DSP->div('paddedWrapper').
              $LANG->line('name_of_status_group', 'group_name').
              BR.
              $DSP->input_text('group_name', $group_name, '20', '50', 'input', '260px').
              $DSP->div_c();
        
                
        if ($edit == FALSE)
            $r .= $DSP->input_submit($LANG->line('submit'));
        else
            $r .= $DSP->input_submit($LANG->line('update'));
    
        $r .= $DSP->form_c();

        $DSP->title = &$title;
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).$DSP->crumb_item($title);
        $DSP->body  = &$r;                
    }
    // END


    //-----------------------------------------------------------
    // Status group submission handler
    //-----------------------------------------------------------
    // This function receives the submitted status group data
    // and puts it in the database
    //-----------------------------------------------------------

    function update_status_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // If the $group_id variable is present we are editing an
        // existing group, otherwise we are creating a new one
        
        $edit = (isset($_POST['group_id'])) ? TRUE : FALSE;
                
        if ($_POST['group_name'] == '')
        {
            return $this->edit_status_group_form();
        }
        
        if ( ! preg_match("#^[a-zA-Z0-9_\-/\s]+$#i", $_POST['group_name']))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }       
  
        // Is the group name taken?
        
        $sql = "SELECT count(*) as count FROM exp_status_groups WHERE group_name = '".$DB->escape_str($_POST['group_name'])."'";
        
        if ($edit == TRUE)
        {
            $sql .= " AND group_id != '".$_POST['group_id']."'";
        } 
        
        $query = $DB->query($sql);        
      
        if ($query->row['count'] > 0)
        {
            return $DSP->error_message($LANG->line('taken_status_group_name'));
        }
   
   
        // Construct the query based on whether we are updating or inserting
   
        if ($edit == FALSE)
        {  
            unset($_POST['group_id']);

            $DB->query($DB->insert_string('exp_status_groups', $_POST));  
            
            $group_id = $DB->insert_id;
            
			$DB->query("INSERT INTO exp_statuses (status_id, group_id, status, status_order, highlight) VALUES ('', '$group_id', 'open', '1', '$this->status_color_open')");
			$DB->query("INSERT INTO exp_statuses (status_id, group_id, status, status_order, highlight) VALUES ('', '$group_id', 'closed', '2', '$this->status_color_closed')");
            
            $success_msg = $LANG->line('status_group_created');
            
            $crumb = $DSP->crumb_item($LANG->line('new_status'));
            
            $LOG->log_action($LANG->line('status_group_created').$DSP->nbs(2).$_POST['group_name']);            
        }
        else
        {        
            $DB->query($DB->update_string('exp_status_groups', $_POST, 'group_id='.$_POST['group_id']));  
            
            $success_msg = $LANG->line('status_group_updated');
            
            $crumb = $DSP->crumb_item($LANG->line('update'));
        }
        
        
        $message = $DSP->qdiv('itemWrapper', $DSP->qspan('success', $success_msg).NBS.NBS.'<b>'.$_POST['group_name'].'</b>');

        if ($edit == FALSE)
        {            
            $query = $DB->query("SELECT weblog_id from exp_weblogs WHERE is_user_blog = 'n'");
            
            if ($query->num_rows > 0)
            {
                $message .= $DSP->div('itemWrapper').$DSP->span('alert').$LANG->line('assign_group_to_weblog').$DSP->span_c().$DSP->nbs(2);
                
                if ($query->num_rows == 1)
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=group_prefs'.AMP.'weblog_id='.$query->row['weblog_id'];                
                }
                else
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list';
                }
                
                $message .= $DSP->anchor(BASE.AMP.$link, $LANG->line('click_to_assign_group')).$DSP->div_c();
            }
        }
        
        return $this->status_overview($message);
    }
    // END  
      

  
    //-----------------------------------------------------------
    // Delete status group confirm
    //-----------------------------------------------------------
    // Warning message shown when you try to delete a status group
    //-----------------------------------------------------------

    function delete_status_group_conf()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT group_name FROM exp_status_groups WHERE group_id = '$group_id'");
        
        
        $DSP->title = $LANG->line('delete_group');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).$DSP->crumb_item($LANG->line('delete_group'));
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=delete_status_group'.AMP.'group_id='.$group_id)
                    .$DSP->input_hidden('group_id', $group_id)
                    .$DSP->heading($LANG->line('delete_group'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_status_group_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['group_name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    //-----------------------------------------------------------
    // Delete status group
    //-----------------------------------------------------------
    // This function nukes the status group and associated statuses
    //-----------------------------------------------------------

    function delete_status_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_name FROM exp_status_groups WHERE group_id = '$group_id'");

        $name = $query->row['group_name'];
        
        $DB->query("DELETE FROM exp_status_groups WHERE group_id = '$group_id'");
        
        $DB->query("DELETE FROM exp_statuses WHERE group_id = '$group_id'");
        
        $LOG->log_action($LANG->line('status_group_deleted').$DSP->nbs(2).$name);        
        
        $message = $DSP->qdiv('', $DSP->qspan('success', $LANG->line('status_group_deleted')).$DSP->nbs(2).'<b>'.$name.'</b>'.BR.BR);

        return $this->status_overview($message);
    }
    // END    
    
    
    
    //-----------------------------------------------------------
    // Status manager
    //-----------------------------------------------------------
    // This function lets you create/edit statuses
    //-----------------------------------------------------------

    function status_manager($group_id = '')
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ($group_id == '')
        {
            if ( ! $group_id = $IN->GBL('group_id'))
            {
                return false;
            }
        }
        
        $i = 0;
     
        $r = $DSP->heading($LANG->line('statuses'));

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('', '55%', '', '', 'top');
        
     
        $query = $DB->query("SELECT group_name FROM  exp_status_groups WHERE group_id = '$group_id'");
     
        $r .= $DSP->table('tableBorder', '0', '0', '95%').
              $DSP->tr().
              $DSP->td('tablePad'); 
     
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeading', '', '3').
              $DSP->qspan('defaultBold', $LANG->line('status_group').':').$DSP->nbs(2).$query->row['group_name'].
              $DSP->td_c().
              $DSP->tr_c();        

        $query = $DB->query("SELECT status_id, status FROM  exp_statuses WHERE group_id = '$group_id' ORDER BY status_order");
        
        $total = $query->num_rows + 1;
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

				$del = ($row['status'] != 'open' AND $row['status'] != 'closed') ? $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=del_status_conf'.AMP.'status_id='.$row['status_id'], $LANG->line('delete')) : '--';

				$status_name = ($row['status'] == 'open' OR $row['status'] == 'closed') ? $LANG->line($row['status']) : $row['status'];

                $r .= $DSP->tr().
                      $DSP->table_qcell($style, $DSP->qspan('defaultBold', $status_name)).
                      $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_status'.AMP.'status_id='.$row['status_id'], $LANG->line('edit'))).
                      $DSP->table_qcell($style, $del).
                      $DSP->tr_c();
            }
        }
        
        $r .= $DSP->table_c();        

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();   
             
        $r .= $DSP->qdiv('', BR.$DSP->qspan('defaultBold', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_status_order'.AMP.'group_id='.$group_id, $LANG->line('change_status_order'))));   
        
        $r .= $DSP->td_c().
              $DSP->td('rightCel', '45%', '', '', 'top');
        
        // Build the right side output
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_status'.AMP.'group_id='.$group_id).
              $DSP->input_hidden('group_id', $group_id);
        
        $r .= $DSP->qdiv('defaultBold', $LANG->line('create_new_status').$DSP->br(2));
        
        $r .= $DSP->qdiv('', $LANG->line('status_name', 'status').BR.$DSP->input_text('status', '', '30', '60', 'input', '260px'));
                
        $r .= $DSP->qdiv('', BR.$LANG->line('status_order', 'status_order').BR.$DSP->input_text('status_order', $total, '20', '3', 'input', '50px'));

        $r .= $DSP->qdiv('', BR.$LANG->line('highlight', 'highlight').BR.$DSP->input_text('highlight', '', '20', '30', 'input', '120px'));
        
        $r .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('submit')));

        $r .= $DSP->form_c();
              
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
        
  
        $DSP->title = $LANG->line('statuses');
  
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).$DSP->crumb_item($LANG->line('statuses'));

        $DSP->body  = &$r;  
    }
    // END  
    


    //-----------------------------------------------------------
    // Status submission handler
    //-----------------------------------------------------------
    // This function recieves the submitted status data and
    // inserts it in the database.
    //-----------------------------------------------------------

    function update_status()
    {
        global $DB, $DSP, $LANG, $IN;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        $edit = ( ! $IN->GBL('status_id', 'POST')) ? FALSE : TRUE;
        
        
        if ( ! $IN->GBL('status', 'POST'))
        {
            return $this->status_manager($IN->GBL('group_id', 'POST'));
        }
        
        if ( ! eregi( "^([-a-zA-Z0-9_\+ ])+$", $IN->GBL('status', 'POST')))
        {
            return $DSP->error_message($LANG->line('invalid_status_name'));
        }
        
		$sql = "SELECT count(*) AS count FROM exp_statuses WHERE status = '".$_POST['status']."' AND group_id = '".$_POST['group_id']."'";
        
        
        if ($edit == FALSE)
        {
        	$query = $DB->query($sql);
        
        	if ($query->row['count'] > 0)
        	{
				return $DSP->error_message($LANG->line('duplicate_status_name'));
        	}
        
            $sql = $DB->insert_string('exp_statuses', $_POST);     
            
        	$DB->query($sql);
        }
        else
        {          
        	$sql .= " AND status_id != '".$_POST['status_id']."'";
        	
        	$query = $DB->query($sql);
        
        	if ($query->row['count'] > 0)
        	{
				return $DSP->error_message($LANG->line('duplicate_status_name'));
        	}
        
            $sql = $DB->update_string(
                                        'exp_statuses', 
                                        
                                         array(
                                                'status'     => $IN->GBL('status', 'POST'),
                                                'highlight'  => $IN->GBL('highlight', 'POST')
                                               ), 
                                        
                                         array(
                                                'status_id'  => $IN->GBL('status_id', 'POST'),
                                                'group_id'   => $IN->GBL('group_id', 'POST')
                                              )
                                     );
        	$DB->query($sql);
        	
        	// If the status name has changed, we need to update weblog entries with the new stuatus.
        	
        	if ($_POST['old_status'] != $_POST['status'])
        	{
				$query = $DB->query("SELECT weblog_id FROM exp_weblogs WHERE status_group = '".$_POST['group_id']."'");
				
				if ($query->num_rows > 0)
				{
					foreach ($query->result as $row)
					{
						$DB->query("UPDATE exp_weblog_titles SET status = '".$DB->escape_str($_POST['status'])."' WHERE status = '".$DB->escape_str($_POST['old_status'])."' AND weblog_id = '".$row['weblog_id']."'");
					}
				}
			}                                    
        }


        return $this->status_manager($IN->GBL('group_id', 'POST'));
    }
    // END
   
   
   
    //-------------------------------------
    // Edit status form
    //-------------------------------------

    function edit_status_form()
    {
        global $DSP, $IN, $DB, $REGX, $LANG;

        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
    
        if ( ! $status_id = $IN->GBL('status_id'))
        {
            return false;
        }
    
        $query = $DB->query("SELECT * FROM  exp_statuses WHERE status_id = '$status_id'");
        
        $group_id  		= $query->row['group_id'];
        $status    		= $query->row['status'];
        $status_order	= $query->row['status_order'];
        $color     		= $query->row['highlight'];
        $status_id 		= $query->row['status_id'];

        // Build our output
        
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_status').
              $DSP->input_hidden('status_id', $status_id).
              $DSP->input_hidden('old_status',  $status).
              $DSP->input_hidden('group_id',  $group_id);
        
        $r .= $DSP->heading($LANG->line('edit_status'));
        		
		if ($status == 'open' OR $status == 'closed')
		{
			$r .= $DSP->input_hidden('status', $status);

        	$r .= $DSP->qdiv('itemWrapper', $LANG->line('status_name', 'status').BR.$DSP->qdiv('itemWrapper', $DSP->qspan('defaultBold', $LANG->line($status))));
		}
        else
        {
        	$r .= $DSP->qdiv('itemWrapper', $LANG->line('status_name', 'status').BR.$DSP->input_text('status', $status, '30', '60', 'input', '260px'));
        }
        
        $r .= $DSP->qdiv('', BR.$LANG->line('status_order', 'status_order').BR.$DSP->input_text('status_order', $status_order, '20', '3', 'input', '50px'));
          
        $r .= $DSP->qdiv('', BR.$LANG->line('highlight', 'highlight').BR.$DSP->input_text('highlight', $color, '30', '30', 'input', '120px'));
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('update')));
        
        $r .= $DSP->form_c();
        
        $DSP->title = $LANG->line('edit_status');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).$DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_editor'.AMP.'group_id='.$group_id, $LANG->line('statuses'))).$DSP->crumb_item($LANG->line('edit_status'));

        $DSP->body  = &$r;
    }
    // END
    
 
    // -------------------------------------------
    //   Delete status confirm
    // -------------------------------------------    

    function delete_status_confirm()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $status_id = $IN->GBL('status_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT status, group_id FROM exp_statuses WHERE status_id = '$status_id'");
        
        $DSP->title = $LANG->line('delete_status');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_editor'.AMP.'group_id='.$query->row['group_id'], $LANG->line('status_groups')).$DSP->crumb_item($LANG->line('delete_status'));
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=del_status'.AMP.'status_id='.$status_id)
                    .$DSP->heading($LANG->line('delete_status'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_status_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['status'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
 

    // -------------------------------------------
    //   Delete status
    // -------------------------------------------    

    function delete_status()
    {  
        global $DSP, $IN, $DB;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $status_id = $IN->GBL('status_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT status, group_id FROM exp_statuses WHERE status_id = '$status_id'");
        
        $group_id = $query->row['group_id'];
        $status   = $query->row['status'];
        
        $query = $DB->query("SELECT weblog_id FROM exp_weblogs WHERE status_group = '$group_id'");
        
        if ($query->num_rows > 0)
        {
        	$DB->query("UPDATE exp_weblog_titles SET status = 'closed' WHERE status = '$status' AND weblog_id = '".$query->row['weblog_id']."'");
        }

        if ($status != 'open' AND $status != 'closed')
        {    
        	$DB->query("DELETE FROM exp_statuses WHERE status_id = '$status_id' AND group_id = '$group_id'");
        }
        
        $this->status_manager($group_id);
    }
    // END    
    
    
    
    // -------------------------------------------
    //   Edit status order
    // -------------------------------------------    
    
	function edit_status_order()
	{    
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT status, status_id, status_order FROM  exp_statuses WHERE group_id = '$group_id' ORDER BY status_order");
        
        if ($query->num_rows == 0)
        {
            return false;
        }
                
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_status_order');
        $r .= $DSP->input_hidden('group_id', $group_id);
        
        $r .= $DSP->heading($LANG->line('change_status_order'));
        
        $r .= $DSP->table('tableBorder', '0', '0', '30%').
              $DSP->tr().
              $DSP->td('tablePad'); 
                
        $r .= $DSP->table('', '0', '10', '100%');
                
        foreach ($query->result as $row)
        {
        	$status_name = ($row['status'] == 'open' OR $row['status'] == 'closed') ? $LANG->line($row['status']) : $row['status'];
        
            $r .= $DSP->tr();
            $r .= $DSP->table_qcell('tableCellOne', $status_name);
            $r .= $DSP->table_qcell('tableCellOne', $DSP->input_text($row['status_id'], $row['status_order'], '4', '3', 'input', '30px'));      
            $r .= $DSP->tr_c();
        }
        
        $r .= $DSP->tr();
        $r .= $DSP->td('', '', '2');
        $r .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit($LANG->line('update')));
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();

        $r .= $DSP->form_c();

        $DSP->title = $LANG->line('change_status_order');
        
        
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).
        $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=status_editor'.AMP.'group_id='.$group_id, $LANG->line('statuses'))).$DSP->crumb_item($LANG->line('change_status_order'));
        

        $DSP->body  = &$r;
    
    }
    // END
    
    
    //---------------------------------------
    // Update status order
    //---------------------------------------

    function update_status_order()
    {  
        global $DSP, $IN, $DB, $LANG;

        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            return false;
        }
        
        unset($_POST['group_id']);
                
        foreach ($_POST as $key => $val)
        {
            $DB->query("UPDATE exp_statuses SET status_order = '$val' WHERE status_id = '$key'");    
        }
        
        return $this->status_manager($group_id);
    }
    // END
        
  
//=====================================================================
//  CUSTOM FIELD FUNCTIONS
//=====================================================================
 
 
 
  
    //-----------------------------------------------------------
    // Custom field overview page
    //-----------------------------------------------------------
    // This function show the "Custom weblog fields" page,
    // accessed via the "admin" tab
    //-----------------------------------------------------------

    function field_overview($message = '')
    {
        global $LANG, $DSP, $DB;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Fetch field groups
        
        $sql = "SELECT exp_field_groups.group_id, exp_field_groups.group_name,
                COUNT(exp_weblog_fields.group_id) as count 
                FROM exp_field_groups
                LEFT JOIN exp_weblog_fields ON (exp_field_groups.group_id = exp_weblog_fields.group_id)
                GROUP BY exp_field_groups.group_id
                ORDER BY exp_field_groups.group_name";        
        
        $query = $DB->query($sql);
              
        if ($query->num_rows == 0)
        {        
            return $DSP->set_return_data(
                                        $LANG->line('admin').$DSP->crumb_item($LANG->line('field_groups')), 
                                        
                                        $DSP->heading($LANG->line('field_groups')).
                                        stripslashes($message).
                                        $DSP->qdiv('itemWrapper', $LANG->line('no_field_group_message')).
                                        $DSP->qdiv('itmeWrapper',
                                        $DSP->anchor(
                                                        BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=field_group_editor', 
                                                        $LANG->line('create_new_field_group')
                                                     )),
                                      
                                        $LANG->line('field_groups')
                                      );  
        }     
              
        $r = $DSP->heading($LANG->line('field_groups')).
             stripslashes($message);              
              
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '4').
              $LANG->line('field_group').
              $DSP->td_c().
              $DSP->tr_c();
        
        $i = 0;  
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

            $r .= $DSP->tr().
                  $DSP->table_qcell($style, $DSP->qspan('defaultBold', $row['group_name']));
            
            $r .= $DSP->table_qcell($style,
                  '('.$row['count'].')'.$DSP->nbs(2).
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=field_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('add_edit_fields')
                               ));

            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=field_group_editor'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('edit_field_group_name')
                               ));

            $r .= $DSP->table_qcell($style,
                  $DSP->anchor(
                                BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=del_field_group_conf'.AMP.'group_id='.$row['group_id'], 
                                $LANG->line('delete_field_group')
                               ));

            $r .= $DSP->tr_c();
        }
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        $DSP->title  = $LANG->line('field_groups');    
        $DSP->crumb  = $LANG->line('field_groups');
        $DSP->rcrumb = $DSP->qdiv('crumbLinksR',
                       $DSP->anchor(
                                    BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=field_group_editor', 
                                    $LANG->line('create_new_field_group')
                                  ));
        $DSP->body = &$r;

    }
    // END
  


    //-----------------------------------------------------------
    // New/edit field group form
    //-----------------------------------------------------------
    // This function lets you create/edit a custom field group
    //-----------------------------------------------------------

    function edit_field_group_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // Set default values
        
        $edit       = FALSE;
        $group_id   = '';
        $group_name = '';
        
        // If we have the group_id variable it's an edit request, so fetch the field data
        
        if ($group_id = $IN->GBL('group_id'))
        {
            $edit = TRUE;
            
            $query = $DB->query("SELECT group_name, group_id FROM exp_field_groups WHERE group_id = '$group_id'");
            
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }   

        if ($edit == FALSE)
            $title = $LANG->line('new_field_group');
        else
            $title = $LANG->line('edit_field_group_name');
        
        // Build our output

        $r = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_field_group');
     
        if ($edit == TRUE)
            $r .= $DSP->input_hidden('group_id', $group_id);
            
        $r .= $DSP->heading($title);
            
        $r .= $DSP->div();
        $r .= $LANG->line('field_group_name', 'group_name');
        $r .= BR;
        $r .= $DSP->input_text('group_name', $group_name, '20', '50', 'input', '260px');
        $r .= $DSP->br(2);

        if ($edit == FALSE)
            $r .= $DSP->input_submit($LANG->line('submit'));
        else
            $r .= $DSP->input_submit($LANG->line('update'));
        
        $r .= $DSP->div_c();

        $r .= $DSP->form_c();

        $DSP->title = $title;
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=statuses', $LANG->line('status_groups')).$DSP->crumb_item($title);
        $DSP->body  = &$r;
    }
    // END
 
 
    //-----------------------------------------------------------
    // Field group submission handler
    //-----------------------------------------------------------
    // This function receives the submitted group data and puts
    // it in the database
    //-----------------------------------------------------------

    function update_field_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // If the $group_id variable is present we are editing an
        // existing group, otherwise we are creating a new one
        
        $edit = (isset($_POST['group_id'])) ? TRUE : FALSE;
        
        
        if ($_POST['group_name'] == '')
        {
            return $this->edit_field_group_form();
        }
        
        if ( ! preg_match("#^[a-zA-Z0-9_\-/\s]+$#i", $_POST['group_name']))
        {
            return $DSP->error_message($LANG->line('illegal_characters'));
        }              
  
        // Is the group name taken?
        
        $sql = "SELECT count(*) as count FROM exp_field_groups WHERE group_name = '".$DB->escape_str($_POST['group_name'])."'";
        
        if ($edit == TRUE)
        {
            $sql .= " AND group_id != '".$_POST['group_id']."'";
        } 
        
        $query = $DB->query($sql);        
      
        if ($query->row['count'] > 0)
        {
            return $DSP->error_message($LANG->line('taken_field_group_name'));
        }
   
        // Construct the query based on whether we are updating or inserting
   
        if ($edit == FALSE)
        {  
            unset($_POST['group_id']);

            $sql = $DB->insert_string('exp_field_groups', $_POST);  
            
            $success_msg = $LANG->line('field_group_created');
            
            $crumb = $DSP->crumb_item($LANG->line('new_field_group'));
            
            $LOG->log_action($LANG->line('field_group_created').$DSP->nbs(2).$_POST['group_name']);            
        }
        else
        {        
            $sql = $DB->update_string('exp_field_groups', $_POST, 'group_id='.$_POST['group_id']);  
            
            $success_msg = $LANG->line('field_group_updated');
            
            $crumb = $DSP->crumb_item($LANG->line('update'));
        }

        
        $DB->query($sql);
        
        $message = $DSP->div('success').$success_msg.$DSP->nbs(2).$_POST['group_name'];

        if ($edit == FALSE)
        {            
            $query = $DB->query("SELECT weblog_id from exp_weblogs WHERE is_user_blog = 'n'");
            
            if ($query->num_rows > 0)
            {
                $message .= $DSP->br(2).$DSP->span('alert').$LANG->line('assign_group_to_weblog').$DSP->span_c().$DSP->nbs(2);
                
                if ($query->num_rows == 1)
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=group_prefs'.AMP.'weblog_id='.$query->row['weblog_id'];                
                }
                else
                {
                    $link = 'C=admin'.AMP.'M=blog_admin'.AMP.'P=blog_list';
                }
                
                $message .= $DSP->anchor(BASE.AMP.$link, $LANG->line('click_to_assign_group'));
            }
        }
        
        $message .= $DSP->div_c();

        return $this->field_overview($message);
    }
    // END  
      
 
 
    
    //-----------------------------------------------------------
    // Delete field group confirm
    //-----------------------------------------------------------
    // Warning message if you try to delete a field group
    //-----------------------------------------------------------

    function delete_field_group_conf()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT group_name FROM exp_field_groups WHERE group_id = '$group_id'");
        
        
        $DSP->title = $LANG->line('delete_group');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=custom_fields', $LANG->line('field_groups')).$DSP->crumb_item($LANG->line('delete_group'));
        
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=delete_field_group'.AMP.'group_id='.$group_id)
                    .$DSP->input_hidden('group_id', $group_id)
                    .$DSP->heading($LANG->line('delete_field_group'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_field_group_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['group_name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    // -------------------------------------------
    //   Delete field group
    // -------------------------------------------    

    function delete_field_group()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
                
        $query = $DB->query("SELECT group_name FROM exp_field_groups WHERE group_id = '$group_id'");
        
        $name = $query->row['group_name'];
        
        $query = $DB->query("SELECT field_id FROM exp_weblog_fields WHERE group_id ='$group_id'");
                
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $sql[] = "alter table exp_weblog_data drop column field_id_".$row['field_id'];
                $sql[] = "alter table exp_weblog_data drop column field_ft_".$row['field_id'];
            }
            
            foreach ($sql as $q)
            {
                $DB->query($q);
            }
        }
        
        $DB->query("DELETE FROM exp_field_groups WHERE group_id = '$group_id'");
        
       	$DB->query("DELETE FROM exp_weblog_fields WHERE group_id = '$group_id'");
        
        $LOG->log_action($LANG->line('field_group_deleted').$DSP->nbs(2).$name);                
        
        $message = $DSP->qdiv('itemWrapper', $DSP->qspan('success', $LANG->line('field_group_deleted')).NBS.NBS.'<b>'.$name.'</b>');

        return $this->field_overview($message);
    }
    // END    
    
    
 
    //-----------------------------------------------------------
    // Field manager
    //-----------------------------------------------------------
    // This function show a list of current fields and the
    // form that allows you to create a new field.
    //-----------------------------------------------------------

    function field_manager($group_id = '', $msg = FALSE)
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
        
         $message = ($msg == TRUE) ? $DSP->qdiv('success', $LANG->line('preferences_updated')) : '';

        if ($group_id == '')
        {
            if ( ! $group_id = $IN->GBL('group_id'))
            {
                return false;
            }
        }
        
        // Fetch the name of the field group
        
        $query = $DB->query("SELECT group_name FROM  exp_field_groups WHERE group_id = '$group_id'");
                      
        $r  = $DSP->heading($LANG->line('custom_fields')).
             stripslashes($message);
              
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
     
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeading', '', '3').
              $LANG->line('field_group').':'.$DSP->nbs(2).$query->row['group_name'].
              $DSP->td_c().
              $DSP->tr_c();

        $query = $DB->query("SELECT field_id, field_order, field_label FROM  exp_weblog_fields WHERE group_id = '$group_id' ORDER BY field_order");
        
  
        if ($query->num_rows == 0)
        {
            $r .= $DSP->tr().
                  $DSP->td('tableCellTwo', '', '3').
                  '<b>'.$LANG->line('no_field_groups').'</br>'.
                  $DSP->td_c().
                  $DSP->tr_c();
        }  

        $i = 0;
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

                $r .= $DSP->tr();
                $r .= $DSP->table_qcell($style, $row['field_order'].$DSP->nbs(2).$DSP->qspan('defaultBold', $row['field_label']));
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_field'.AMP.'field_id='.$row['field_id'], $LANG->line('edit')));      
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=del_field_conf'.AMP.'field_id='.$row['field_id'], $LANG->line('delete')));      
                $r .= $DSP->tr_c();
            }
        }
        
        $r .= $DSP->table_c();

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      

        if ($query->num_rows > 0)
        {
            $r .= $DSP->qdiv('paddedWrapper', BR.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_field_order'.AMP.'group_id='.$group_id, $LANG->line('edit_field_order')));
        }
        
        $DSP->title = $LANG->line('custom_fields');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=custom_fields', $LANG->line('field_groups')).$DSP->crumb_item($LANG->line('custom_fields'));

        $DSP->rcrumb = $DSP->qdiv('crumbLinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_field'.AMP.'group_id='.$group_id, $LANG->line('create_new_custom_field')));
        
        $DSP->body  = &$r;  
    }
    // END  
    
  
 
    //-----------------------------------------------------------
    // Edit field form
    //-----------------------------------------------------------
    // This function lets you edit an existing custom field
    //-----------------------------------------------------------

    function edit_field_form()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

		$field_id = $IN->GBL('field_id');

        $type = ($field_id) ? 'edit' : 'new';
        
        $total_fields = '';
        
        if ($type == 'new')
        {
            $query = $DB->query("SELECT count(*) AS count FROM exp_weblog_fields");
            
            $total_fields = $query->row['count'] + 1;
        }
        
        $DB->fetch_fields = TRUE;
        
        $query = $DB->query("SELECT * FROM exp_weblog_fields WHERE field_id = '$field_id'");
        
        if ($query->num_rows == 0)
        {
            foreach ($query->fields as $f)
            {
                $$f = '';
            }
        }
        else
        {        
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }
        
        if ($group_id == '')
        {
			$group_id = $IN->GBL('group_id');
        }
                
        // Form declaration
        
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_weblog_fields');
        $r .= $DSP->input_hidden('group_id', $group_id);
        $r .= $DSP->input_hidden('field_id', $field_id);
        
        $title = ($type == 'edit') ? 'edit_field' : 'create_new_custom_field';
                
        $r .= $DSP->div('tableBorder');
        $r .= $DSP->div('tablepad');
        
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingLargeBold', '', '2').$LANG->line($title).$DSP->td_c().
              $DSP->tr_c();
              
        $i = 0;
            
        //---------------------------------
        // Field name
        //---------------------------------
        
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $DSP->required().NBS.$LANG->line('field_name', 'field_name')).$DSP->qdiv('itemWrapper', $LANG->line('field_name_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('field_name', $field_name, '20', '60', 'input', '260px'), '60%');
		$r .= $DSP->tr_c();		
                
        //---------------------------------
        // Field Label
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $DSP->required().NBS.$LANG->line('field_label', 'field_label')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('field_label', $field_label, '20', '60', 'input', '260px'), '60%');
		$r .= $DSP->tr_c();		
		
		
        //---------------------------------
        // Field order
        //---------------------------------
        
        if ($type == 'new')
            $field_order = $total_fields;
            
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_order', 'field_order')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('field_order', $field_order, '4', '3', 'input', '30px'), '60%');
		$r .= $DSP->tr_c();		


        //---------------------------------
        // Field type
        //---------------------------------

        $sel_1 = ''; $sel_2 = ''; $sel_3 = '';

        switch ($field_type)
        {
            case 'text'     : $sel_1 = 1;
                break;
            case 'textarea' : $sel_2 = 1;
                break;
            case 'select'   : $sel_3 = 1;
                break;
        }
        
		$typemenu  = $DSP->input_select_header('field_type')
					.$DSP->input_select_option('text', 		$LANG->line('text_input'),	$sel_1)
					.$DSP->input_select_option('textarea', 	$LANG->line('textarea'),  	$sel_2)
					.$DSP->input_select_option('select', 	$LANG->line('select_list'), $sel_3)
					.$DSP->input_select_footer();

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_type')), '40%');
		$r .= $DSP->table_qcell($style, $typemenu, '60%');
		$r .= $DSP->tr_c();	
		
        //---------------------------------
        // Field Formatting
        //---------------------------------
		
        $sel_1 = ''; $sel_2 = ''; $sel_3 = '';
        
        switch ($field_fmt)
        {
            case 'none'  : $sel_1 = 1;
                break;
            case 'br'    : $sel_2 = 1;
                break;
            case 'xhtml' : $sel_3 = 1;
                break;
            default		 : $sel_3 = 1;
                break;
        }
        
		$typemenu  = $DSP->input_select_header('field_fmt')
					.$DSP->input_select_option('none', 	$LANG->line('none'), 	$sel_1)
					.$DSP->input_select_option('br', 	$LANG->line('auto_br'), $sel_2)
					.$DSP->input_select_option('xhtml', $LANG->line('xhtml'), 	$sel_3)
					.$DSP->input_select_footer();
					
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';
		
		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_formatting')), '40%');
		$r .= $DSP->table_qcell($style, $typemenu, '60%');
		$r .= $DSP->tr_c();		
				
        //---------------------------------
        // Is field required?
        //---------------------------------
              
        if ($field_required == '') $field_required = 'n';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_required')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('field_required', 'y', ($field_required == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('field_required', 'n', ($field_required == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();		
             
        //---------------------------------
        // Is field searchable?
        //---------------------------------
        if ($field_search == '') $field_search = 'n';

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('is_field_searchable')), '40%');
		$r .= $DSP->table_qcell($style, $LANG->line('yes').$DSP->nbs().$DSP->input_radio('field_search', 'y', ($field_search == 'y') ? 1 : '').$DSP->nbs(3).$LANG->line('no').$DSP->nbs().$DSP->input_radio('field_search', 'n', ($field_search == 'n') ? 1 : ''), '60%');
		$r .= $DSP->tr_c();

        //---------------------------------
        // Field Max Length
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('field_max_length', 'field_maxl')).$DSP->qdiv('itemWrapper', $LANG->line('field_max_length_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('field_maxl', $field_maxl, '4', '3', 'input', '30px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Field Max Length
        //---------------------------------

		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style, $DSP->qspan('defaultBold', $LANG->line('textarea_rows', 'field_ta_rows')).$DSP->qdiv('itemWrapper', $LANG->line('textarea_rows_cont')), '40%');
		$r .= $DSP->table_qcell($style, $DSP->input_text('field_ta_rows', $field_ta_rows, '4', '3', 'input', '30px'), '60%');
		$r .= $DSP->tr_c();		

        //---------------------------------
        // Field list items
        //---------------------------------
            
		$style = ($i++ % 2) ? 'tableCellOne' : 'tableCellTwo';

		$r .= $DSP->tr();
		$r .= $DSP->table_qcell($style,  $DSP->qdiv('itemWrapper', $DSP->qspan('defaultBold', $LANG->line('field_list_items', 'field_list_items')))
										.$DSP->qdiv('itemWrapper', $LANG->line('field_list_instructions')), '40%', 'top');
		$r .= $DSP->table_qcell($style, $DSP->input_textarea('field_list_items', $field_list_items, 10, 'textarea', '400px'), '60%');
		$r .= $DSP->tr_c();		


                
		$r .= $DSP->table_c();
		$r .= $DSP->div_c();
		$r .= $DSP->div_c();
        
                 
                
        $r .= $DSP->div('itemWrapper');
		$r .= $DSP->required(1).BR.BR;
        
        if ($type == 'edit')        
            $r .= $DSP->input_submit($LANG->line('update'));
        else
            $r .= $DSP->input_submit($LANG->line('submit'));
              
        $r .= $DSP->div_c();
        
        
        $r .= $DSP->form_c();
        
        $DSP->title = $LANG->line('custom_fields');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=custom_fields', $LANG->line('field_groups')).$DSP->crumb_item($LANG->line('custom_fields'));
        $DSP->body  = &$r;

    }
    // END  
    
 
 
    //-------------------------------------------
    // Create/update custom fields
    //-------------------------------------------

    function update_weblog_fields()
    {  
        global $DSP, $IN, $DB, $REGX, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }        
        
        // If the $field_id variable has data we are editing an
        // existing group, otherwise we are creating a new one
        
        $edit = (! isset($_POST['field_id']) OR $_POST['field_id'] == '') ? FALSE : TRUE;
        
        // We need this as a variable as we'll unset the array index
       
        $group_id = $_POST['group_id'];
                
        // Check for required fields

        $error = array();
        
        if ($_POST['field_name'] == '')
        {
            $error[] = $LANG->line('no_field_name');
        }
        
        if ($_POST['field_label'] == '')
        {
            $error[] = $LANG->line('no_field_label');
        }
        
        // Does field name contain invalide characters?
        
        if ( ! eregi("^[a-zA-z0-9\_\-]+$", $_POST['field_name'])) 
        {
            $error[] = $LANG->line('invalid_characters');
        }
          
        // Is the field name taken?

        $sql = "SELECT count(*) as count FROM exp_weblog_fields WHERE field_name = '".$DB->escape_str($_POST['field_name'])."'";
        
        if ($edit == TRUE)
        {
            $sql .= " AND group_id != '$group_id'";
        } 
        
        $query = $DB->query($sql);        
      
        if ($query->row['count'] > 0)
        {
            $error[] = $LANG->line('duplicate_field_name');
        }

        // Are there errors to display?
        
        if (count($error) > 0)
        {
            $str = '';
            
            foreach ($error as $msg)
            {
                $str .= $msg.BR;
            }
            
            return $DSP->error_message($str);
        }
        
        if ($_POST['field_list_items'] != '')
        {
            $_POST['field_list_items'] = $REGX->convert_quotes($_POST['field_list_items']);
        }
             
        // Construct the query based on whether we are updating or inserting
   
        if ($edit === TRUE)
        {
            unset($_POST['group_id']);
                        
            $DB->query($DB->update_string('exp_weblog_fields', $_POST, 'field_id='.$_POST['field_id'].' AND group_id='.$group_id));  
        }
        else
        {
            if ($_POST['field_order'] == 0 || $_POST['field_order'] == '')
            {
                $query = $DB->query("SELECT count(*) AS count FROM exp_weblog_fields WHERE group_id = '$group_id'");
            
                $total = $query->row['count'] + 1;
            
                $_POST['field_order'] = $total; 
            }
                    
            $DB->query($DB->insert_string('exp_weblog_fields', $_POST));
            
            $insert_id = $DB->insert_id;
                        
            $DB->query("ALTER table exp_weblog_data add column field_id_".$insert_id." text NOT NULL");            
            
            $DB->query("ALTER table exp_weblog_data add column field_ft_".$insert_id." char(5) NOT NULL default 'xhtml'");        
        }

        return $this->field_manager($group_id, $edit);
    }
    // END 
 
 
 
    //-----------------------------------------------------------
    // Delete field confirm
    //-----------------------------------------------------------
    // Warning message if you try to delete a custom field
    //-----------------------------------------------------------

    function delete_field_conf()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $field_id = $IN->GBL('field_id'))
        {
            return false;
        }

        $query = $DB->query("SELECT field_label FROM exp_weblog_fields WHERE field_id = '$field_id'");
        
        $DSP->title = $LANG->line('delete_field');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=custom_fields', $LANG->line('field_groups')).$DSP->crumb_item($LANG->line('delete_field'));
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=delete_field'.AMP.'field_id='.$field_id)
                    .$DSP->input_hidden('field_id', $field_id)
                    .$DSP->heading($LANG->line('delete_field'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_field_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['field_label'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END    
    
   
   
    //-----------------------------------------------------------
    // Delete field
    //-----------------------------------------------------------
    // This function alters the "exp_weblog_data" table, dropping
    // the fields
    //-----------------------------------------------------------

    function delete_field()
    {  
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $field_id = $IN->GBL('field_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT group_id, field_label FROM exp_weblog_fields WHERE field_id = '$field_id'");
        
        $group_id = $query->row['group_id'];
        
        $field_label = $query->row['field_label'];

        
        $DB->query("ALTER table exp_weblog_data drop column field_id_".$field_id);
        
        $DB->query("ALTER table exp_weblog_data drop column field_ft_".$field_id);
        
        $DB->query("DELETE FROM exp_weblog_fields WHERE field_id = '$field_id'");
        
        $LOG->log_action($LANG->line('field_deleted').$DSP->nbs(2).$field_label);        

        return $this->field_manager($group_id);
    }
    // END    
 
 
 
 
    //-----------------------------------------------------------
    // Edit field order
    //-----------------------------------------------------------
    // This function shows the form that lets you change the 
    // order that fields appear in
    //-----------------------------------------------------------

    function edit_field_order_form()
    {  
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT field_label, field_name, field_order FROM exp_weblog_fields WHERE group_id = '$group_id' ORDER BY field_order");
        
        if ($query->num_rows == 0)
        {
            return false;
        }
                
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_field_order');
        $r .= $DSP->input_hidden('group_id', $group_id);
        
        $r .= $DSP->heading($LANG->line('edit_field_order'));
        
        $r .= $DSP->table('tableBorder', '0', '0', '30%').
              $DSP->tr().
              $DSP->td('tablePad'); 
                
        $r .= $DSP->table('', '0', '10', '100%');
                
        foreach ($query->result as $row)
        {
            $r .= $DSP->tr();
            $r .= $DSP->table_qcell('tableCellOne', $row['field_label'], $row['field_name']);
            $r .= $DSP->table_qcell('tableCellOne', $DSP->input_text($row['field_name'], $row['field_order'], '4', '3', 'input', '30px'));      
            $r .= $DSP->tr_c();
        }
        
        $r .= $DSP->tr();
        $r .= $DSP->td('', '', '2');
        $r .= $DSP->qdiv('itemWrapperTop', $DSP->input_submit($LANG->line('update')));
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();
        $r .= $DSP->td_c();
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();

        $r .= $DSP->form_c();

        $DSP->title = $LANG->line('edit_field_order');
        $DSP->crumb =
                    $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=custom_fields', $LANG->line('field_groups')).
                    $DSP->crumb_item($DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=field_editor'.AMP.'group_id='.$group_id, $LANG->line('custom_fields'))).
                    $DSP->crumb_item($LANG->line('edit_field_order'));

        $DSP->body  = &$r;
    }
    // END    
 
 
 
 
    //-----------------------------------------------------------
    // Update field order
    //-----------------------------------------------------------
    // This function receives the field order submission
    //-----------------------------------------------------------

    function update_field_order()
    {  
        global $DSP, $IN, $DB, $LANG;


        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $group_id = $IN->GBL('group_id', 'POST'))
        {
            return false;
        }
        
        unset($_POST['group_id']);
                
        foreach ($_POST as $key => $val)
        {
            $DB->query("UPDATE exp_weblog_fields SET field_order = '$val' WHERE field_name = '$key'");    
        }
        
        return $this->field_manager($group_id);
    }
    // END
 
    
    //-----------------------------------------------------------
    //  HTML Buttons
    //-----------------------------------------------------------
    // This function lets you edit the HTML buttons
    //-----------------------------------------------------------

    function html_buttons($message = '', $id = 0)
    { 
        global $IN, $DSP, $REGX, $LANG, $DB;
                
        if ($id == 0 AND ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }        
        
        if ($id != 0)
        {
            $r  = $DSP->qdiv('tableHeadingLargeBold', $LANG->line('html_buttons'));
        }
        else
        {
            $r  = $DSP->heading($LANG->line('default_html_buttons'));
        	$r .= $DSP->qdiv('itemWrapper', $LANG->line('define_html_buttons'));
        }
                

        $r .= stripslashes($message);
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=save_html_buttons').
              $DSP->body .= $DSP->input_hidden('member_id', "$id");

        $r .= $DSP->table(($id != 0) ? '' : 'tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td(($id != 0) ? '' : 'tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('tag_name')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('tag_open')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('tag_close')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('accesskey')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('tag_order')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('row')).
              $DSP->tr_c();
              
              
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_html_buttons WHERE member_id = '$id'");          

        $member_id = ($query->row['count'] == 0 AND ! isset($_GET['U'])) ? 0 : $id;
        
        $query = $DB->query("SELECT * FROM exp_html_buttons WHERE member_id = '$member_id' ORDER BY tag_row, tag_order");          
              
        $i = 0;
        
        if ($query->num_rows > 0)
        {     
            foreach ($query->result as $row)
            {      
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;                               
                
                $tag_row = $DSP->input_select_header('tag_row_'.$i);
                $selected = ($row['tag_row'] == '1') ? 1 : '';
                $tag_row .= $DSP->input_select_option('1', '1', $selected);
                $selected = ($row['tag_row'] == '2') ? 1 : '';
                $tag_row .= $DSP->input_select_option('2', '2', $selected);
                $tag_row .= $DSP->input_select_footer();
                
                $r .= $DSP->tr().
                      $DSP->table_qcell($style, $DSP->input_text('tag_name_'.$i,  $row['tag_name'], '20', '40', 'input', '100%'), '16%').
                      $DSP->table_qcell($style, $DSP->input_text('tag_open_'.$i,  $row['tag_open'], '20', '120', 'input', '100%'), '37%').
                      $DSP->table_qcell($style, $DSP->input_text('tag_close_'.$i, $row['tag_close'], '20', '120', 'input', '100%'), '37%').
                      $DSP->table_qcell($style, $DSP->input_text('accesskey_'.$i, $row['accesskey'], '2', '1', 'input', '30px'), '3%').
                      $DSP->table_qcell($style, $DSP->input_text('tag_order_'.$i, $row['tag_order'], '2', '2', 'input', '30px'), '3%').
                      $DSP->table_qcell($style, $tag_row, '4%').
                      $DSP->tr_c();
            }
        }   
            
              $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
            
              $tag_row  = $DSP->input_select_header('tag_row_'.$i);
              $tag_row .= $DSP->input_select_option('1', '1', '');
              $tag_row .= $DSP->input_select_option('2', '2', '');
              $tag_row .= $DSP->input_select_footer();
                  
        $r .= $DSP->tr().
              $DSP->table_qcell($style, $DSP->input_text('tag_name_'.$i, '', '20', '40', 'input', '100%'), '16%').
              $DSP->table_qcell($style, $DSP->input_text('tag_open_'.$i, '', '20', '120', 'input', '100%'), '37%').
              $DSP->table_qcell($style, $DSP->input_text('tag_close_'.$i,'', '20', '120', 'input', '100%'), '37%').
              $DSP->table_qcell($style, $DSP->input_text('accesskey_'.$i, '', '2', '1', 'input', '30px'), '3%').
              $DSP->table_qcell($style, $DSP->input_text('tag_order_'.$i, '', '2', '2', 'input', '30px'), '3%').
              $DSP->table_qcell($style, $tag_row, '4%').
              $DSP->tr_c();
              
        $r .= $DSP->table_c();
        
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
        
        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('htmlbutton_delete_instructions')));     
              
        $r .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('submit')))
             .$DSP->form_c();

        if ($id == 0)
        {
            $DSP->title = $LANG->line('default_html_buttons');
            $DSP->crumb = $LANG->line('default_html_buttons');
            $DSP->body  = &$r;    
        }
        else
        {
            return $r;
        }
    }
    // END  
    
    
      
    // -----------------------------------------
    //  Save HTML formatting buttons
    // -----------------------------------------
        
    function save_html_buttons()
    {
        global $IN, $FNS, $LANG, $DB, $DSP;
        
        $id = $IN->GBL('member_id', 'POST');
                
        if ($id == 0 AND ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }


        $data = array();
        
        foreach ($_POST as $key => $val)
        {
            if (strstr($key, 'tag_name_') AND $val != '')
            {
                $n = substr($key, 9);
                
                $data[] = array(
                                 'member_id' => $id,
                                 'tag_name'  => $_POST['tag_name_'.$n],
                                 'tag_open'  => $_POST['tag_open_'.$n],
                                 'tag_close' => $_POST['tag_close_'.$n],
                                 'accesskey' => $_POST['accesskey_'.$n],
                                 'tag_order' => $_POST['tag_order_'.$n],
                                 'tag_row'   => $_POST['tag_row_'.$n]
                                );
            }
        }


        $DB->query("DELETE FROM exp_html_buttons WHERE member_id = '$id'");

        foreach ($data as $val)
        {                       
            $DB->query($DB->insert_string('exp_html_buttons', $val));
        }
        
        $message = $DSP->qdiv('success', $LANG->line('preferences_updated'));

        if ($id == 0)
        {
            $this->html_buttons($message);
        }
        else
        {
            $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=htmlbuttons'.AMP.'id='.$id.AMP.'U=1');
            exit;    
        }
    }
    // END 




    //-----------------------------------------------------------
    //  Ping servers
    //-----------------------------------------------------------
    // This function lets you edit the ping servers
    //-----------------------------------------------------------

    function ping_servers($message = '', $id = '0')
    { 
        global $IN, $DSP, $REGX, $LANG, $DB;
        
        if ($id == 0 AND ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }        
        
        if ($id != 0)
        {
            $r  = $DSP->qdiv('tableHeadingLargeBold', $LANG->line('ping_servers'));
        }
        else
        {
            $r  = $DSP->heading($LANG->line('default_ping_servers'));
            
			$r .= $DSP->qdiv('itemWrapper', $LANG->line('define_ping_servers'));
        }
        
        $r .= stripslashes($message);
        
        $r .= $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=save_ping_servers').
              $DSP->body .= $DSP->input_hidden('member_id', "$id");
        
        $r .= $DSP->table(($id != 0) ? '' : 'tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td(($id != 0) ? '' : 'tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('server_name')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('server_url')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('port')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('protocol')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('is_default')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('server_order')).
              $DSP->tr_c();
              
              
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_ping_servers WHERE member_id = '$id'");
        
        $member_id = ($query->row['count'] == 0  AND ! isset($_GET['U'])) ? 0 : $id;
        
        $query = $DB->query("SELECT * FROM exp_ping_servers WHERE member_id = '$member_id' ORDER BY server_order");          
              
        $i = 0;
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {      
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;                               
                
                $protocol  = $DSP->input_select_header('ping_protocol_'.$i);            
                $protocol .= $DSP->input_select_option('xmlrpc', 'xmlrpc');
                $protocol .= $DSP->input_select_footer();
                
                $default = $DSP->input_select_header('is_default_'.$i);
                $selected = ($row['is_default'] == 'y') ? 1 : '';
                $default .= $DSP->input_select_option('y', $LANG->line('yes'), $selected);
                $selected = ($row['is_default'] == 'n') ? 1 : '';
                $default .= $DSP->input_select_option('n', $LANG->line('no'), $selected);
                $default .= $DSP->input_select_footer();
                
                $r .= $DSP->tr().
                      $DSP->table_qcell($style, $DSP->input_text('server_name_'.$i,  $row['server_name'], '20', '40', 'input', '100%'), '25%').
                      $DSP->table_qcell($style, $DSP->input_text('server_url_'.$i,   $row['server_url'], '20', '120', 'input', '100%'), '55%').
                      $DSP->table_qcell($style, $DSP->input_text('server_port_'.$i, $row['port'], '2', '4', 'input', '30px'), '5%').
                      $DSP->table_qcell($style, $protocol, '5%').
                      $DSP->table_qcell($style, $default, '5%').
                      $DSP->table_qcell($style, $DSP->input_text('server_order_'.$i, $row['server_order'], '2', '3', 'input', '30px'), '5%').
                      $DSP->tr_c();
            }
        }
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

            $protocol  = $DSP->input_select_header('ping_protocol_'.$i);            
            $protocol .= $DSP->input_select_option('xmlrpc', 'xmlrpc');
            $protocol .= $DSP->input_select_footer();
            
            $default = $DSP->input_select_header('is_default_'.$i);
            $default .= $DSP->input_select_option('y', $LANG->line('yes'));
            $default .= $DSP->input_select_option('n', $LANG->line('no'));
            $default .= $DSP->input_select_footer();

            $r .= $DSP->tr().
                  $DSP->table_qcell($style, $DSP->input_text('server_name_'.$i,  '', '20', '40', 'input', '100%'), '25%').
                  $DSP->table_qcell($style, $DSP->input_text('server_url_'.$i,  '', '20', '120', 'input', '100%'), '55%').
                  $DSP->table_qcell($style, $DSP->input_text('server_port_'.$i, '80', '2', '4', 'input', '30px'), '5%').
                  $DSP->table_qcell($style, $protocol, '5%').
                  $DSP->table_qcell($style, $default, '5%').
                  $DSP->table_qcell($style, $DSP->input_text('server_order_'.$i, '', '2', '3', 'input', '30px'), '5%').
                  $DSP->tr_c();
              
        $r .= $DSP->table_c();       

        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
              
        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('highlight', $LANG->line('pingserver_delete_instructions')));     
              
        $r .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('submit')))
             .$DSP->form_c();

        if ($id == 0)
        {
            $DSP->title = $LANG->line('default_ping_servers');
            $DSP->crumb = $LANG->line('default_ping_servers');
            $DSP->body  = &$r; 
        }
        else
        {
            return $r;
        }
    }
    // END  
    
    
      
    // -----------------------------------------
    //  Save ping servers
    // -----------------------------------------
        
    function save_ping_servers()
    {
        global $IN, $FNS, $LANG, $DB, $DSP;
        
        $id = $IN->GBL('member_id', 'POST');
        
        if ($id == 0 AND ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
                
        $data = array();
        
        foreach ($_POST as $key => $val)
        {
            if (strstr($key, 'server_name_') AND $val != '')
            {
                $n = substr($key, 12);
                
                $data[] = array(
                                 'member_id'     => $id,
                                 'server_name'   => $_POST['server_name_'.$n],
                                 'server_url'    => $_POST['server_url_'.$n],
                                 'port'          => $_POST['server_port_'.$n],
                                 'ping_protocol' => $_POST['ping_protocol_'.$n],
                                 'is_default'    => $_POST['is_default_'.$n],
                                 'server_order'  => $_POST['server_order_'.$n]
                                );
            }
        }


        $DB->query("DELETE FROM exp_ping_servers WHERE member_id = '$id'");

        foreach ($data as $val)
        {
            $DB->query($DB->insert_string('exp_ping_servers', $val));
        }
        
        $message = $DSP->qdiv('success', $LANG->line('preferences_updated'));
        
        
        if ($id == 0)
        {
            $this->ping_servers($message);
        }
        else
        {
            $FNS->redirect(BASE.AMP.'C=myaccount'.AMP.'M=pingservers'.AMP.'id='.$id.AMP.'U=1');
            exit;    
        }
    }
    // END 



    //-----------------------------------------------------------
    // File Upload Preferences Page
    //-----------------------------------------------------------

    function file_upload_preferences($update = '')
    {
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        
        $r = $DSP->heading($LANG->line('file_upload_preferences'));
        
        if ($update != '')
        {
            $r .= $DSP->qdiv('success', $LANG->line('preferences_updated'));
        }
     
        $r .= $DSP->table('tableBorder', '0', '0', '60%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableHeading', '', '3').
              $LANG->line('current_upload_prefs').
              $DSP->td_c().
              $DSP->tr_c();

        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE is_user_blog = 'n' ORDER BY name");
        
  
        if ($query->num_rows == 0)
        {
            $r .= $DSP->tr().
                  $DSP->td('tableCellTwo', '', '3').
                  '<b>'.$LANG->line('no_upload_prefs').'</b>'.
                  $DSP->td_c().
                  $DSP->tr_c();
        }  

        $i = 0;
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;

                $r .= $DSP->tr();
                $r .= $DSP->table_qcell($style, $i.$DSP->nbs(2).$DSP->qspan('defaultBold', $row['name']));
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_upload_pref'.AMP.'id='.$row['id'], $LANG->line('edit')));      
                $r .= $DSP->table_qcell($style, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=del_upload_pref_conf'.AMP.'id='.$row['id'], $LANG->line('delete')));      
                $r .= $DSP->tr_c();
            }
        }
        
        $r .= $DSP->table_c();
        
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      
                
        $DSP->title  = $LANG->line('file_upload_preferences');
        $DSP->crumb  = $LANG->line('file_upload_preferences');
        $DSP->rcrumb = $DSP->qdiv('crumblinksR', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_upload_pref', $LANG->line('create_new_upload_pref')));
        $DSP->body   = &$r;  
    }
    // END



    //--------------------------------------
    // New/Edit Upload Preferences form
    //--------------------------------------

    function edit_upload_preferences_form()
    {
        global $DSP, $IN, $DB, $LANG;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }
        
        $id = $IN->GBL('id');

        $type = ($id !== FALSE) ? 'edit' : 'new';
        
        $DB->fetch_fields = TRUE;
        
        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '$id' AND is_user_blog = 'n'");
        
        if ($query->num_rows == 0)
        {
        	if ($id != '')
            	return $DSP->no_access_message();
        
            foreach ($query->fields as $f)
            {
                $$f = '';
            }
        }
        else
        {        
            foreach ($query->row as $key => $val)
            {
                $$key = $val;
            }
        }
        
        // Form declaration
        
        $r  = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=update_upload_prefs');
        $r .= $DSP->input_hidden('id', $id);
        $r .= $DSP->input_hidden('cur_name', $name);

        
        
        if ($type == 'edit')        
            $r .= $DSP->heading($LANG->line('edit_file_upload_preferences'));
        else
            $r .= $DSP->heading($LANG->line('new_file_upload_preferences'));
                
        
        $r .= $DSP->table('', '0', '', '100%');
              
        $r .= $DSP->tr().
              $DSP->td('', '50%');
                      
        //---------------------------------
        // Name
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($DSP->required().NBS.$LANG->line('upload_pref_name', 'upload_pref_name'), 5)
             .$DSP->input_text('name', $name, '50', '50', 'input', '100%')
             .$DSP->div_c();

        //---------------------------------
        // Server path
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($DSP->required().NBS.$LANG->line('server_path', 'server_path'), 5)
             .$DSP->input_text('server_path', $server_path, '50', '100', 'input', '100%')
             .$DSP->div_c();


        //---------------------------------
        // URL
        //---------------------------------
        
        if ($url == '')
            $url = 'http://';
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($DSP->required().NBS.$LANG->line('url_to_upload_dir', 'url_to_upload_dir'), 5)
             .$DSP->input_text('url', $url, '50', '70', 'input', '100%')
             .$DSP->div_c();


        //---------------------------------
        // Allowed file types
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('allowed_types', 'allowed_types'), 5);
             
        $selected = ($allowed_types == 'img' || $allowed_types == '') ? 1 : '';
                     
        $r .= $DSP->qdiv('', $DSP->input_radio('allowed_types', 'img', $selected).NBS.$LANG->line('images_only'));
        
        $selected = ($allowed_types == 'all') ? 1 : '';
        
        $r .= $DSP->qdiv('', $DSP->input_radio('allowed_types', 'all', $selected).NBS.$LANG->line('all_filetypes'));
             
        $r .= $DSP->div_c();


        //---------------------------------
        // Max file size
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('max_size', 'max_size'), 5)
             .$DSP->input_text('max_size', $max_size, '15', '16', 'input', '90px')
             .$DSP->div_c();


        //---------------------------------
        // Max image height
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('max_height', 'max_height'), 5)
             .$DSP->input_text('max_height', $max_height, '10', '6', 'input', '60px')
             .$DSP->div_c();


        //---------------------------------
        // Max image width
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('max_width', 'max_width'), 5)
             .$DSP->input_text('max_width', $max_width, '10', '6', 'input', '60px')
             .$DSP->div_c();

        //---------------------------------
        // Image properties
        //---------------------------------
                
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('properties', 'properties'), 5)
             .$DSP->input_text('properties', $properties, '50', '120', 'input', '100%')
             .$DSP->div_c();

        //---------------------------------
        // Pre formatting
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('pre_format', 'pre_format'), 5)
             .$DSP->input_text('pre_format', $pre_format, '50', '120', 'input', '100%')
             .$DSP->div_c();

        //---------------------------------
        // Post formatting
        //---------------------------------
        
        $r .= $DSP->div('padBotBorder')
             .$DSP->heading($LANG->line('post_format', 'post_format'), 5)
             .$DSP->input_text('post_format', $post_format, '50', '120', 'input', '100%')
             .$DSP->div_c();
             
             
        $r .= $DSP->td_c()
             .$DSP->td('', '', '', '', 'top').BR;
        
        
        
        $r .= $DSP->heading($LANG->line('restrict_to_group'), 5).
              $DSP->qdiv('itemWrapper', $LANG->line('restrict_notes_1')).
              $DSP->qdiv('itemWrapper', $LANG->line('restrict_notes_2')).
              $DSP->qdiv('itemWrapper', $LANG->line('restrict_notes_3'));
              
        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 

        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('tableHeadingBold', '', '').
              $LANG->line('member_group').
              $DSP->td_c().
              $DSP->td('tableHeadingBold', '', '').
              $LANG->line('can_upload_files').
              $DSP->td_c().
              $DSP->tr_c();
    
        $i = 0;
        
        $group = array();
        
        $result = $DB->query("SELECT member_group FROM exp_upload_no_access WHERE upload_id = '$id'");
        
        if ($result->num_rows != 0)
        {
            foreach($result->result as $row)
            {
                $group[$row['member_group']] = TRUE;
            }
        }
        
        
        $query = $DB->query("SELECT group_id, group_title FROM exp_member_groups WHERE group_id != '1' AND group_id != '2' AND group_id != '4' ORDER BY group_title");
        
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
        
        $r .= $DSP->tr_c();
        $r .= $DSP->table_c();
             


        $r .= $DSP->div('itemWrapper')
             .$DSP->required(1).BR.BR;
        
        if ($type == 'edit')        
            $r .= $DSP->input_submit($LANG->line('update'));
        else
            $r .= $DSP->input_submit($LANG->line('submit'));
              
        $r .= $DSP->div_c();
        
        $r .= $DSP->form_c();
                
        $DSP->title = $LANG->line('create_new_upload_pref');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=upload_prefs', $LANG->line('file_upload_prefs')).$DSP->crumb_item($LANG->line('create_new_upload_pref'));
        $DSP->body  = &$r;
    }
    // END




    //------------------------------------
    // Update upload preferences
    //------------------------------------

    function update_upload_preferences()
    {
        global $DSP, $IN, $DB, $LANG, $FNS;
        
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        // If the $id variable is present we are editing an
        // existing field, otherwise we are creating a new one
        
        $edit = (isset($_POST['id']) AND $_POST['id'] != '') ? TRUE : FALSE;
                
        // Check for required fields

        $error = array();
        
        if ($_POST['name'] == '')
        {
            $error[] = $LANG->line('no_upload_dir_name');
        }
        
        if ($_POST['server_path'] == '')
        {
            $error[] = $LANG->line('no_upload_dir_path');
        }
        
        if ($_POST['url'] == '' OR $_POST['url'] == 'http://')
        {
            $error[] = $LANG->line('no_upload_dir_url');
        }
        
          
        // Is the name taken?

        $sql = "SELECT count(*) as count FROM exp_upload_prefs WHERE name = '".$DB->escape_str($_POST['name'])."'";
        
        $query = $DB->query($sql);        
      
        if (($edit == FALSE || ($edit == TRUE && $_POST['name'] != $_POST['cur_name'])) && $query->row['count'] > 0)
        {
            $error[] = $LANG->line('duplicate_dir_name');
        }
               
        
        // Are there errors to display?
        
        if (count($error) > 0)
        {
            $str = '';
            
            foreach ($error as $msg)
            {
                $str .= $msg.BR;
            }
            
            return $DSP->error_message($str);
        }

        $id = $IN->GBL('id', 'POST');
        
        unset($_POST['id']);
        unset($_POST['cur_name']);        

        $data = array();

        $DB->query("DELETE FROM exp_upload_no_access WHERE upload_id = '$id'");
        
        foreach ($_POST as $key => $val)
        {
            if (substr($key, 0, 7) == 'access_')
            {
                if ($val == 'n')
                    $DB->query("INSERT INTO exp_upload_no_access (upload_id, upload_loc, member_group) VALUES ('$id', 'cp', '".substr($key, 7)."')");
            }
            else
            {
                $data[$key] = $val;
            }
        }   

        // Construct the query based on whether we are updating or inserting
   
        if ($edit === TRUE)
        {        
            $DB->query($DB->update_string('exp_upload_prefs', $data, 'id='.$id));  
        }
        else
        {                    
            $DB->query($DB->insert_string('exp_upload_prefs', $data));            
        }
        
        // Clear database cache
        
        $FNS->clear_caching('db');

        return $this->file_upload_preferences(1);
    }
    // END




    //--------------------------------------
    // Upload preferences delete confirm
    //--------------------------------------

    function delete_upload_preferences_conf()
    {
        global $DSP, $IN, $DB, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }

        $query = $DB->query("SELECT name FROM exp_upload_prefs WHERE id = '$id'");
        
        $DSP->title = $LANG->line('delete_upload_preference');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=upload_prefs', $LANG->line('file_upload_prefs')).$DSP->crumb_item($LANG->line('delete_upload_preference'));
        
        $DSP->body = $DSP->form('C=admin'.AMP.'M=blog_admin'.AMP.'P=del_upload_pref'.AMP.'id='.$id)
                    .$DSP->input_hidden('id', $id)
                    .$DSP->heading($LANG->line('delete_upload_preference'))
                    .$DSP->qdiv('itemWrapper', '<b>'.$LANG->line('delete_upload_pref_confirmation').'</b>')
                    .$DSP->qdiv('itemWrapper', '<i>'.$query->row['name'].'</i>')
                    .$DSP->qdiv('alert', BR.$LANG->line('action_can_not_be_undone'))
                    .$DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('delete')))
                    .$DSP->form_c();
    }
    // END



    //--------------------------------------
    // Delete upload preferences
    //--------------------------------------

    function delete_upload_preferences()
    {
        global $DSP, $IN, $DB, $LOG, $LANG;
  
        if ( ! $DSP->allowed_group('can_admin_weblogs'))
        {
            return $DSP->no_access_message();
        }

        if ( ! $id = $IN->GBL('id'))
        {
            return false;
        }
        
        $DB->query("DELETE FROM exp_upload_no_access WHERE upload_id = '$id'");
        
        $query = $DB->query("SELECT name FROM exp_upload_prefs WHERE id = '$id'");
        
        $name = $query->row['name'];
        
        $DB->query("DELETE FROM exp_upload_prefs WHERE id = '$id'");
        
        $LOG->log_action($LANG->line('upload_pref_deleted').$DSP->nbs(2).$name);     
        
        // Clear database cache
        
        $FNS->clear_caching('db');

        return $this->file_upload_preferences();
    }
    // END

}
// END CLASS
?>