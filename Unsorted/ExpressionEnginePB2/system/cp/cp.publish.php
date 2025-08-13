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
 File: cp.publish.php
-----------------------------------------------------
 Purpose: The main weblog class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Publish {

    var $categories     = array();
    var $cat_parents	= array();

    //------------------------
    // Request handler
    //------------------------
  
    function request_handler()
    {
        global $IN, $DSP, $LANG, $FNS;
                        
        switch ($IN->GBL('M'))
        {
            case 'new_entry'        : ( ! $IN->GBL('preview', 'POST')) ? $this->submit_new_entry() : $this->new_entry_form('preview');            
                break;
            case 'entry_form'       : $this->new_entry_form();
                break;
            case 'edit_entry'       : $this->new_entry_form('edit');
                break;
            case 'view_entry'       : $this->view_entry();
                break;
            case 'view_entries'     : $this->edit_entries();
                break;
            case 'delete_conf'      : $this->delete_entries_confirm();
                break;
            case 'delete_entries'   : $this->delete_entries();
                break;
            case 'view_comments'    : $this->view_comments();
                break;
            case 'edit_comment'     : $this->edit_comment_form();
                break;
            case 'change_status'     : $this->change_comment_status();
                break;                
            case 'update_comment'   : $this->update_comment();
                break;
            case 'del_comment_conf' : $this->delete_comment_confirm();
                break;
            case 'del_comment'      : $this->delete_comment();
                break;
            case 'view_pings'       : $this->view_previous_pings();
                break;
            case 'file_upload_form' : $this->file_upload_form();
                break;
            case 'upload_file'      : $this->upload_file();
                break;
            case 'file_browser'     : $this->file_browser();
                break;
            case 'replace_file'     : $this->replace_file();
                break;
            case 'image_options'	: $this->image_options_form();
            	break;
            case 'create_thumb'		: $this->create_thumb();
            	break;
            case 'emoticons'        : $this->emoticons();
                break;
            default  :
                        
                    if ($IN->GBL('C') == 'publish')
                    {
						if ($IN->GBL('BK'))
						{
							return $this->new_entry_form();
						}
                    
                        $assigned_weblogs = $FNS->fetch_assigned_weblogs();
                                            
                        if (count($assigned_weblogs) == 0)
                        {
                            return $DSP->no_access_message($LANG->line('unauthorized_for_any_blogs'));
                        }
                        else
                        {
                            if (count($assigned_weblogs) == 1)
                            {
                                return $this->new_entry_form();
                            }
                            else
                            {
                                return $this->weblog_select_list();
                            }
                        }
                    }
                    else
                    {
                       return $this->edit_entries();
                    }        
             break;
        }
    }
    // END



    //-----------------------------------------------------------
    // Weblog selection menu
    //-----------------------------------------------------------
    // This function shows a list of available weblogs.
    // This list will be displayed when a user clicks the
    // "publish" or "edit" link when more than one weblog exist.
    //-----------------------------------------------------------

    function weblog_select_list()
    {
        global $IN, $DSP, $DB, $LANG, $FNS, $SESS;
        
                
        if ($IN->GBL('C') == 'publish')
        {
            $blurb  = $LANG->line('select_blog_to_post_in');
            $title  = $LANG->line('publish');
            $action = 'C=publish'.AMP.'M=entry_form';
        }
        else
        {
            $blurb  = $LANG->line('select_blog_to_edit');
            $title  = $LANG->line('edit');
            $action = 'C=edit'.AMP.'M=view_entries';
        }
    
        //-------------------------------------------------
        // Fetch the blogs the user is allowed to post in
        //-------------------------------------------------
                
        if ($SESS->userdata['group_id'] != 1) 
        { 
            $allowed_blogs = $FNS->fetch_assigned_weblogs();
            
            // If there aren't any blogs assigned to the user, bail out
            
            if (count($allowed_blogs) == 0)
            {
                return $DSP->no_access_message($LANG->line('unauthorized_for_any_blogs'));
            }
        
            // Build query
            
            $sql = "SELECT weblog_id, blog_title FROM exp_weblogs WHERE ";
            
            $sql .= " (";
        
            foreach ($allowed_blogs as $val)
            {
                $sql .= " weblog_id = '".$val."' OR"; 
            }
            
            $sql = substr($sql, 0, -2).')';
                    
            $sql .= " ORDER BY blog_title";
       }
       else
       {
            $sql = "SELECT weblog_id, blog_title FROM exp_weblogs WHERE is_user_blog = 'n' ORDER BY blog_title";
       }

        $links = array();
        
        $query = $DB->query($sql);
        
        foreach ($query->result as $row)
        {
            $links[] = $DSP->qdiv('', $DSP->anchor(BASE.AMP.$action.AMP.'weblog_id='.$row['weblog_id'], $row['blog_title']));
        }
        
        // If there are no allowed blogs, show a message
        
        if (count($links) < 1)
        {
            return $DSP->no_access_message($LANG->line('unauthorized_for_any_blogs'));
        }
                
        
        $DSP->body = $DSP->heading($blurb);
        
        $DSP->body .= $DSP->div('box280');
                      
        foreach ($links as $val)
        {
            $DSP->body .= $DSP->qdiv('itemWrapper', $val);     
        }  
        
        $DSP->body .= $DSP->div_c();
                        
        $DSP->title = &$title;
        $DSP->crumb = &$title;
                
    }
    // END    



    //-----------------------------------------------------------
    // Weblog "new entry" form
    //-----------------------------------------------------------
    // This function displays the form used to submit, edit, or
    // preview new weblog entries with.  
    //-----------------------------------------------------------

    function new_entry_form($which = 'new', $alt_preview = '')
    {
        global $DSP, $LANG, $LOC, $DB, $IN, $REGX, $FNS, $SESS;
        
        $title            = '';
        $url_title        = '';
        $status           = '';
        $expiration_date  = '';
        $entry_date       = '';
        $sticky           = '';
        $allow_trackbacks = '';
        $trackback_urls   = '';
        $field_data       = '';
        $allow_comments   = '';
        $preview_text     = '';
        $catlist          = '';
        $author_id        = '';
        $tb_url           = '';
        $bookmarklet      = FALSE;
      
      
        //------------------------------------------------------------------
        // We need to first determine which weblog to post the entry into.
        //------------------------------------------------------------------

        $assigned_weblogs = $FNS->fetch_assigned_weblogs();

        if ( ! $weblog_id = $IN->GBL('weblog_id', 'GP'))
        {
            // Does the user have their own blog?
            
            if ($SESS->userdata['weblog_id'] != 0)
            {
                $weblog_id = $SESS->userdata['weblog_id'];
            }
            elseif (sizeof($assigned_weblogs) == 1)
            {
            		$weblog_id = $assigned_weblogs['0'];
            }
            else
            {
                $query = $DB->query("SELECT weblog_id from exp_weblogs WHERE is_user_blog = 'n'");
      
                if ($query->num_rows == 1)
                {
                    $weblog_id = $query->row['weblog_id'];
                }
                else
                {
                    return false;
                }
            }
        }
        
        // ----------------------------------------------
        //  Security check
        // ---------------------------------------------
                
        if ( ! in_array($weblog_id, $assigned_weblogs))
        {
            return $DSP->no_access_message($LANG->line('unauthorized_for_this_blog'));
        }
            
        // ----------------------------------------------
        //  Fetch weblog preferences
        // ---------------------------------------------

        $query = $DB->query("SELECT * FROM  exp_weblogs WHERE weblog_id = '$weblog_id'");        
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('no_weblog_exits'));
        }

        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
         
        // --------------------------------------------------------------------       
        // The $which variable determines what the page should show:
        //  If $which = 'new' we'll show a blank "new entry" page
        //  If $which = "preview", the user has clicked the "preview" button.
        //  If $which = "edit", we are editing an already existing entry.
        // --------------------------------------------------------------------              
                
        if ($which == 'edit')
        {
            if ( ! $entry_id = $IN->GBL('entry_id', 'GET'))
            {
                return false;
            }
            
            // Fetch the weblog data
        
            $sql = "SELECT exp_weblog_titles.*, exp_weblog_data.*
                    FROM   exp_weblog_titles, exp_weblog_data
                    WHERE  exp_weblog_titles.entry_id = '$entry_id'
                    AND    exp_weblog_titles.weblog_id = '$weblog_id'
                    AND    exp_weblog_titles.entry_id = exp_weblog_data.entry_id"; 
        
            $result = $DB->query($sql);
            
            if ($result->num_rows == 0)
            {
                return $DSP->error_message($LANG->line('no_weblog_exits'));
            }
            
            if ($result->row['author_id'] != $SESS->userdata['member_id'])
            {    
                if ( ! $DSP->allowed_group('can_edit_other_entries'))
                {
                    return $DSP->no_access_message();
                }
            }            
        
            foreach ($result->row as $key => $val)
            {
                $$key = $val;
            }
        }
        
        // ---------------------------------------------
        //  Assign page title based on type of request
        // ---------------------------------------------
        
        switch ($which)
        {
            case 'edit'		:  $DSP->title = $LANG->line('edit_entry');
                break;
            case 'preview'	:  $DSP->title = $LANG->line('preview');
                break;
            default			:  $DSP->title = $LANG->line('new_entry');
                break;        
        }

        
        // ----------------------------------------------
        //  Assign breadcrumb
        // ---------------------------------------------
        
        $DSP->crumb = $DSP->title.$DSP->crumb_item('<b>'.$blog_title.'</b>');
        
        // We'll focus the title field upon load
        
        $DSP->body_props = " onLoad=\"document.forms[0].title.focus();\"";

        // ----------------------------------------------
        //  Are we using the bookmarklet?
        // ---------------------------------------------
        
        if ($IN->GBL('BK', 'GP'))
        {
            $bookmarklet = TRUE;
            
            $tb_url = $IN->GBL('tb_url', 'GP');
        }
        
        // ----------------------------------------------
        //  Start building the page output
        // ---------------------------------------------
        
        $r = '';
        
        // ----------------------------------------------
        //  Form header and hidden fields  
        // ---------------------------------------------
        
        $BK = ($bookmarklet == TRUE) ? AMP.'BK=1'.AMP.'Z=1' : '';
                    
        if ($IN->GBL('C') == 'publish')
        {
            $r .= $DSP->form('C=publish'.AMP.'M=new_entry'.$BK, 'entryform');
        }
        else
        {
            $r .= $DSP->form('C=edit'.AMP.'M=new_entry'.$BK, 'entryform');
        }
        
        $r .= $DSP->input_hidden('weblog_id', $weblog_id); 
        
        if ($IN->GBL('entry_id', 'POST'))
        {
            $entry_id = $IN->GBL('entry_id', 'POST');
        }
            
        if (isset($entry_id))
        {
            $r .= $DSP->input_hidden('entry_id', $entry_id); 
        }
        
        if ($bookmarklet == TRUE)
        {
            $r .= $DSP->input_hidden('tb_url', $tb_url); 
        } 
        
            
        // ----------------------------------------------
        //  Are we previewing an entry?
        // ---------------------------------------------
        
        if ($which == 'preview')
        {
            // ----------------------------------------
            //  Instantiate Typography class
            // ----------------------------------------        
          
            if ( ! class_exists('Typography'))
            {
                require PATH_CORE.'core.typography'.EXT;
            }
            
            $TYPE = new Typography;
                
        
            $r .= "<div id='previewWrapper'>".
                  $DSP->div('preview');
                  
			if ($alt_preview == '')
            {
            	$r .= $DSP->heading(stripslashes($IN->GBL('title', 'POST')));
            }
			else
            {
            	$r .= $DSP->heading($LANG->line('error'));
            }
            
            // We need to grab each global array index and do a little formatting
            
            foreach($_POST as $key => $val)
            {            
                // Gather categories.  Since you can select as many categories as you want
                // they are submitted as an array.  The $_POST['category'] index
                // contains a sub-array as the value, therefore we need to loop through 
                // it and assign discrete variables.
                
                if (is_array($val))
                {
                    foreach($val as $k => $v)
                    {
                    	$_POST[$k] = $v;
                    }
                    
                    unset($_POST[$key]);
                }
                else
                {
                	if ($alt_preview == '')
                	{
						if (strstr($key, 'field_id'))
						{
							$expl = explode('field_id_', $key);
							
							// Pass the entry data to the typography class
													
							$txt_fmt = ( ! isset($_POST['field_ft_'.$expl['1']])) ? 'xhtml' : $_POST['field_ft_'.$expl['1']];
						
							$r .= $TYPE->parse_type( stripslashes($val), 
													 array(
																'text_format'   => $txt_fmt,
																'html_format'   => $weblog_html_formatting,
																'auto_links'    => $weblog_allow_img_urls,
																'allow_img_url' => $weblog_auto_link_urls
														   )
													);
						} 
					}
                    
                    $val = stripslashes($val);
                
                    $_POST[$key] = $val;
                }
                        
               $$key = $val;
            }
            
			if ($alt_preview != '')
			{
				$r .= $DSP->qdiv('highlight', $alt_preview);
			}                    
	
            $r .= BR.BR.
                  $DSP->div_c().
                  $DSP->div_c();
        }
        // END PREVIEW
                
                
        // ----------------------------------------------
        //  Left side of page
        // ---------------------------------------------
        
        $r .= $DSP->table('', '0', '10', '100%').
              $DSP->tr().
              $DSP->td('tableBorder', '76%', '', '', 'top');
        
        // "title" input Field
        
        if ($IN->GBL('title', 'GET'))
        {
            $title = $this->bm_qstr_decode($IN->GBL('title', 'GET'));
        }
        
        $r .= $DSP->qdiv('itemWrapper', $DSP->qdiv('itemTitle', $DSP->required().NBS.$LANG->line('title', 'title')).$DSP->input_text('title', $title, '20', '100', 'input', '99%'));
              
        // "URL title" input Field
        
        $r .= $DSP->qdiv('itemWrapper',
                          $DSP->qspan('itemTitle', $LANG->line('url_title', 'url_title')).
                          $DSP->input_text('url_title', $url_title, '20', '75', 'input', '99%').
                          $DSP->br(2)
                         );
               
        //--------------------------------
        // HTML formatting buttons
        //--------------------------------
                
        $r .= $this->html_formatting_buttons( ($which == 'edit') ? $author_id : '', $field_group);
          
        //--------------------------------
        // Custom Fields
        //--------------------------------

        // The following chunk of code gathers all the custom 
        // entry fields and displays them based on their type.
        
        $query = $DB->query("SELECT * FROM  exp_weblog_fields WHERE group_id = '$field_group' ORDER BY field_order");
                
        foreach ($query->result as $row)
        {
            switch ($which)
            {
                case 'preview' : 
                        $field_data = ( ! isset( $_POST['field_id_'.$row['field_id']] )) ?  '' : $_POST['field_id_'.$row['field_id']];
                        $field_fmt  = ( ! isset( $_POST['field_ft_'.$row['field_id']] )) ? $row['field_fmt'] : $_POST['field_ft_'.$row['field_id']];
                    break;
                case 'edit'    :
                        $field_data = ( ! isset( $result->row['field_id_'.$row['field_id']] )) ? '' : $result->row['field_id_'.$row['field_id']];
                        $field_fmt  = ( ! isset( $result->row['field_ft_'.$row['field_id']] )) ? $row['field_fmt'] : $result->row['field_ft_'.$row['field_id']];
                    break;
                default        :
                
                        $tb_url   = ( ! isset($_GET['tb_url'])) ? '' : $_GET['tb_url'];
                        $tb_field = ( ! isset($_GET['field_id_'.$row['field_id']])) ? '' : $_GET['field_id_'.$row['field_id']];
                        
                        $field_data = ( ! isset( $_GET['field_id_'.$row['field_id']] )) ? '' :  $this->bm_qstr_decode($tb_url."\n\n".$tb_field);
                        $field_fmt  = $row['field_fmt'];
                    break;
            }
                    
            $required  = ($row['field_required'] == 'n') ? '' : $DSP->required().NBS;     
            
            //--------------------------------
            // Textarea fieled types
            //--------------------------------
        
            if ($row['field_type'] == 'textarea')
            {               
                $rows = ( ! isset($row['field_ta_rows'])) ? '10' : $row['field_ta_rows'];
            
                $r .= $DSP->div('itemWrapper').
                      $DSP->div('itemTitle').$required.'<label for="field_id_'.$row['field_id'].'">'.$row['field_label'].'</label>'.$DSP->div_c().
                      $DSP->input_textarea('field_id_'.$row['field_id'], $field_data, $rows, 'textarea', '99%', "onclick='setFieldName(this.name)'").
                      $this->text_formatting_buttons($row['field_id'], $field_fmt).
                      $DSP->div_c();
            }
            else
            {        
                //--------------------------------
                // Text input field types
                //--------------------------------
                
                if ($row['field_type'] == 'text')
                {   
                    $r .= $DSP->div('itemWrapper').
                          $DSP->div('itemTitle').$required.'<label for="field_id_'.$row['field_id'].'">'.$row['field_label'].'</label>'.$DSP->div_c().
                          $DSP->input_text('field_id_'.$row['field_id'], $field_data, '50', $row['field_maxl'], 'input', '99%', "onclick='setFieldName(this.name)'").
                      	  $this->text_formatting_buttons($row['field_id'], $field_fmt).
                          $DSP->div_c();       
                }            

                //--------------------------------
                // Drop-down lists
                //--------------------------------
                
                elseif ($row['field_type'] == 'select')
                {     
                    $r .= $DSP->div('itemWrapper').
                          $DSP->div('itemTitle').$required.$row['field_label'].$DSP->div_c().
                          $DSP->input_select_header('field_id_'.$row['field_id']);
                                    
                    foreach (explode("\n", trim($row['field_list_items'])) as $v)
                    {                    
                        $v = trim($v);
                    
                        $selected = ($v == $field_data) ? 1 : '';
                                            
                        $r .= $DSP->input_select_option($v, $v, $selected);
                    }
                    
                    $r .= $DSP->input_select_footer().
                          $this->text_formatting_buttons($row['field_id'], $field_fmt).
                          $DSP->div_c();
                }
            }
        }

        
        //--------------------------------
        // Trackback Auto-discovery
        //--------------------------------
        
        $tb = '';
        $auto = FALSE;
     
        if ($bookmarklet == TRUE)
        { 
            $selected_urls = array();
        
            if ($which == 'preview')
            {
                foreach ($_POST as $key => $val)
                {
                    if (ereg('^TB_AUTO_', $key))
                    {
                        $selected_urls[] = $val;
                    }
                }
            }
            
            require PATH_MOD.'trackback/mcp.trackback'.EXT;
            
            $xml_parser = xml_parser_create();
            $rss_parser =& new Trackback_CP(); 
            $rss_parser->selected_urls = $selected_urls;
            
            xml_set_object($xml_parser, $rss_parser); 
            xml_set_element_handler($xml_parser, "startElement", "endElement");
            xml_set_character_data_handler($xml_parser, "characterData");
            
            if ($fp = @fopen($tb_url, 'rb'))
            {
                $tb_data = "";
                          
                while ($data = fread($fp, 4096))
                {                            
                    if (preg_match_all("/<rdf:RDF.*?>(.*?)<\/rdf:RDF>/si", $data, $matches)) // <?php
                    { 
                        $tb_data .= implode("\n", $matches['0']);	
                    }
                }
                                
                $tb_data = preg_replace_callback("/(dc:title\=)(.*?)(dc:identifier)/si", array($this, "convert_tb_title_entities"), $tb_data);
                
                
                if ($tb_data != '') 
                {                
                    $auto = TRUE;
                    
                    $tb .= $DSP->qdiv('itemWrapper', $LANG->line('select_entries_to_ping').BR);
                    
                    ob_start();
                    
                    xml_parse($xml_parser, '<xml>'.$tb_data.'</xml>', feof($fp));
                    fclose($fp);
                    xml_parser_free($xml_parser);
                                                        
                    $tb .= ob_get_contents();
                            
                    ob_end_clean(); 
                }
            }    
        }
                    
        
        //--------------------------------
        // Trackback submission form
        //--------------------------------

        $r .= $DSP->div('itemWrapper').
              $DSP->qspan('itemTitle', $LANG->line('ping_urls', 'trackback_urls'));
              
        // If we're editing we'll show the "previous pings" link 

        if ($which == 'edit')
        {
            $r .= $DSP->nbs(2).
                  $DSP->qspan('bold',
                  $DSP->anchorpop(
                                    BASE.AMP.'C=publish'.AMP.'M=view_pings'.AMP.'entry_id='.$entry_id.AMP.'Z=1', 
                                    $LANG->line('view_previous_pings')
                                  )
                              );
        }
              
        $r .=  BR
              .$DSP->input_textarea('trackback_urls', $trackback_urls, 4, 'textarea', '99%')
              .$DSP->div_c();
        
        
        if ($bookmarklet == TRUE)
        {
            $r .= $DSP->div('itemWrapper')
                 .$DSP->span('itemTitle')
                 .$LANG->line('auto_discovery', 'trackback_urls')
                 .$DSP->span_c()
                 .BR
                 .$tb
                 .$DSP->div_c();
        }   
              
        $r .= $DSP->td_c();


        // ----------------------------------------------
        //  Right side of the page
        // ---------------------------------------------
        
        $r .= $DSP->td('', '24%', '', '', 'top');
        
        //--------------------------------
        // Submit/Preview buttons        
        //--------------------------------
        
        $r .= $DSP->div('publishBorder').
              $DSP->div('publishPad').
              $DSP->input_submit($LANG->line('preview'), 'preview').
              $DSP->nbs(2).NL;
        
        if ($IN->GBL('C') == 'publish')
        {
            $r .= $DSP->input_submit($LANG->line('submit'), 'submit');
        }
        else
        {
            $r .= $DSP->input_submit($LANG->line('update'), 'submit');
        }
        
        $r .= $DSP->br(2).$DSP->required(1).
              $DSP->div_c().
              $DSP->div_c();
      
        //--------------------------------
        // Status pull-down menu
        //--------------------------------
        
        $r .= $DSP->div('publishBorder').
              $DSP->div('publishPad').
              $DSP->qdiv('itemTitle', $LANG->line('entry_status')).
              $DSP->input_select_header('status');
        
        if ($status == '') 
            $status = $deft_status;
        
        $query = $DB->query("SELECT * FROM  exp_statuses WHERE group_id = '$status_group' order by status_order");
        
		if ($query->num_rows == 0)
		{
			$selected = ($status == 'open') ? 1 : '';
				
			$r .= $DSP->input_select_option('open', $LANG->line('open'), $selected);
	
			$selected = ($status == 'closed') ? 1 : '';
			
			$r .= $DSP->input_select_option('closed', $LANG->line('closed'), $selected);
		}
        else
        {
            foreach ($query->result as $row)
            {
                $selected = ($status == $row['status']) ? 1 : '';
                                    
				$status_name = ($row['status'] == 'open' OR $row['status'] == 'closed') ? $LANG->line($row['status']) : $row['status'];
                                    
                $r .= $DSP->input_select_option($REGX->form_prep($row['status']), $REGX->form_prep($status_name), $selected);
            }
        }
        
        $r .= $DSP->input_select_footer().
              $DSP->div_c().
              $DSP->div_c();
        
        //--------------------------------
        // Author pull-down menu
        //--------------------------------
                
        $r .= $DSP->div('publishBorder').
              $DSP->div('publishPad').
              $DSP->qdiv('itemTitle', $LANG->line('author')).
              $DSP->input_select_header('author_id');
        
        // First we'll assign the default author.
        
        if ($author_id == '')
            $author_id = $SESS->userdata['member_id'];
                            
            $query = $DB->query("SELECT username, screen_name FROM exp_members WHERE member_id = '$author_id'");
            
            $author = ($query->row['screen_name'] == '') ? $query->row['username'] : $query->row['screen_name'];
        
            $r .= $DSP->input_select_option($author_id, $author);

        // Next we'll gather all the authors that are allowed to be in this list
                      
        $ss = "SELECT exp_members.member_id, exp_members.group_id, exp_members.username, exp_members.screen_name, exp_members.weblog_id,
               exp_member_groups.*
               FROM exp_members, exp_member_groups
               WHERE exp_members.member_id != '$author_id' 
               AND exp_members.in_authorlist = 'y' 
               AND exp_members.group_id = exp_member_groups.group_id
               ORDER BY screen_name asc, username asc";                         
                                                
        $query = $DB->query($ss);
        
        if ($query->num_rows > 0)
        {            
            foreach ($query->result as $row)
            {
                // Is this a "user blog"?  If so, we'll only allow
                // multiple authors if they are assigned to this particular blog
            
                if ($SESS->userdata['weblog_id'] != 0)
                {
                    if ($row['weblog_id'] == $weblog_id)
                    {                    
                        $author = ($row['screen_name'] == '') ? $row['username'] : $row['screen_name'];
                    
                        $selected = ($author_id == $row['member_id']) ? 1 : '';
                                            
                        $r .= $DSP->input_select_option($row['member_id'], $author, $selected);
                    }                
                }
                else
                {
                    // Can the current user assign the entry to a different author?
                    
                    if ($DSP->allowed_group('can_assign_post_authors'))
                    {
                        // If it's not a user blog we'll confirm that the user is 
                        // assigned to a member group that allows posting in this weblog
                        
                        if (isset($SESS->userdata['assigned_weblogs'][$weblog_id]))
                        {
                            $author = ($row['screen_name'] == '') ? $row['username'] : $row['screen_name'];
                        
                            $selected = ($author_id == $row['member_id']) ? 1 : '';
                                                
                            $r .= $DSP->input_select_option($row['member_id'], $author, $selected);
                        }
                    }
                }
            }
        }
            
        $r .= $DSP->input_select_footer().
              $DSP->div_c().
              $DSP->div_c();
        
        //--------------------------------
        // Date field
        //--------------------------------

        $r .= $DSP->div('publishBorder').
              $DSP->div('publishPad').
              $DSP->qspan('itemTitle', $LANG->line('date', 'entry_date')).BR;

            if ($which != 'preview')
            {
                $entry_date = $LOC->set_human_time($entry_date);
            }
            

        $r .= $DSP->input_text('entry_date', $entry_date, '20', '23', 'input', '160px');           
        
        //--------------------------------
        // Expiration date field
        //--------------------------------
        
            if ($which != 'preview')
            {
                $expiration_date = ($expiration_date == 0) ? '' : $LOC->set_human_time($expiration_date);
            }
                              
        $r .= $DSP->div('itemTitle').
              $LANG->line('expiration_date', 'expiration_date').BR.
              $DSP->input_text('expiration_date', $expiration_date, '20', '23', 'input', '160px').BR.
              $DSP->div_c().
              $DSP->div_c().
              $DSP->div_c();
                
        // END date fields     
      
        $r .= $DSP->div('publishBorder');
        
        $r .= $DSP->qdiv('publishPad', $DSP->qdiv('itemTitle', $LANG->line('options')));
        
        //--------------------------------
        // "Sticky" checkbox
        //--------------------------------
        
        $r .= $DSP->qdiv('publishPad', $DSP->input_checkbox('sticky', 'y', $sticky).' '.$LANG->line('sticky'));
        
        //--------------------------------
        // "Allow comments" checkbox
        //--------------------------------
        
        if ($allow_comments == '' AND  $which == 'new')
            $allow_comments = $deft_comments;
        
        $r .= $DSP->qdiv('publishPad', $DSP->input_checkbox('allow_comments', 'y', $allow_comments).' '.$LANG->line('allow_comments'));
       
        //--------------------------------
        // "Allow Trackback" checkbox
        //--------------------------------
        
        if ($allow_trackbacks == '' AND  $which == 'new')
            $allow_trackbacks = $deft_trackbacks;
        
        $r .= $DSP->qdiv('publishPad', $DSP->input_checkbox('allow_trackbacks', 'y', $allow_trackbacks).' '.$LANG->line('allow_trackbacks'));
  
        $r .= $DSP->div_c();

        //--------------------------------
        // Ping servers
        //--------------------------------
        
        $r .= $this->fetch_ping_servers( ($which == 'edit') ? $author_id : '', isset($entry_id) ? $entry_id : '');
         
         
        //--------------------------------
        // Category multi-select field    
        //--------------------------------
        
        if ($which == 'edit')
        {
            $sql = "SELECT exp_categories.cat_name, exp_category_posts.*
                    FROM   exp_categories, exp_category_posts
                    WHERE  exp_categories.group_id = '$cat_group'
                    AND    exp_category_posts.entry_id = '$entry_id'
                    AND    exp_categories.cat_id = exp_category_posts.cat_id"; 
        
            $query = $DB->query($sql);
                        
            foreach ($query->result as $row)
            {            
                $catlist[$row['cat_id']] = $row['cat_id'];  
            }
        }
        
        $this->category_tree('select', $cat_group, $which, $deft_category, $catlist);
    
        $r .= $DSP->div('publishBorderNB').
        	  $DSP->div('publishPad').
              $DSP->qdiv('itemTitle', $LANG->line('categories'));
        
        if (count($this->categories) == 0)
        {  
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('no_categories'));
        }
        else
        {                   
            foreach ($this->categories as $val)
            {
                $r .= $val;
            }
        }
        
        $r .= $DSP->div_c();
        $r .= $DSP->div_c();
        
        // END Table
                      
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
              
        $r .= $DSP->form_c();
              
        // Assign the page we've just built to the output handler
              
        $DSP->body = &$r;
        
        // Don't show the line below the breadcrumb
        $DSP->crumbline = FALSE;        
    }
    // END
    
    

    //-------------------------------------
    //  Convert quotes in trackback titles
    //-------------------------------------    
 
    // This function converts any quotes found in RDF titles
    // to entities.  This is used in the trackback auto-discovery feature
    // to prevent a bug that happens if weblog entry titles contain quotes
    
    function convert_tb_title_entities($matches)
    {
        $matches['2'] = trim($matches['2']);
        $matches['2'] = preg_replace("/^\"/", '', $matches['2']); 
        $matches['2'] = preg_replace("/\"$/", '', $matches['2']);
        $matches['2'] = str_replace("\"", "&quot;", $matches['2']);
        
        return $matches['1']."\"".$matches['2']."\"\n".$matches['3'];
    }
    // END



    //-------------------------------------
    //  Bookmarklet query string decode
    //-------------------------------------    

    function bm_qstr_decode($str)
    {
        global $REGX;
    
        $str = str_replace("%20",    " ",       $str); 
        $str = str_replace("%uFFA5", "&#8226;", $str); 
        $str = str_replace("%uFFCA", " ",       $str); 
        $str = str_replace("%uFFC1", "-",       $str);
        $str = str_replace("%uFFC9", "...",     $str); 
        $str = str_replace("%uFFD0", "-",       $str); 
        $str = str_replace("%uFFD1", "-",       $str);
        $str = str_replace("%uFFD2", "\"",      $str); 
        $str = str_replace("%uFFD3", "\"",      $str); 
        $str = str_replace("%uFFD4", "\'",      $str); 
        $str = str_replace("%uFFD5", "\'",      $str);
        
        $str =  preg_replace("/\%u([0-9A-F]{4,4})/e","'&#'.base_convert('\\1',16,10).';'", $str);
        
        $str = stripslashes(urldecode($str)); 
                
        return $str;
    }
    // END

   
	function fetch_category_parents($cat_array = '')
	{
		global $DB;
		
		if (count($cat_array) == 0)
		{
			return;
		}

		$sql = "SELECT parent_id FROM exp_categories WHERE (";
		
		foreach($cat_array as $val)
		{
			$sql .= " cat_id = '$val' OR ";
		}
		
		$sql = substr($sql, 0, -3).")";
		
		$query = $DB->query($sql);
				
		if ($query->num_rows == 0)
		{
			return;
		}
		
		$temp = array();

		foreach ($query->result as $row)
		{
			if ($row['parent_id'] != 0)
			{
				$this->cat_parents[] = $row['parent_id'];
				
				$temp[] = $row['parent_id'];
			}
		}
	
		$this->fetch_category_parents($temp);
	}   
   
   
   
   
    //---------------------------------------------------------------
    // Weblog entry submission handler
    //---------------------------------------------------------------
    // This function receives a new or edited weblog entry and
    // stores it in the database.  It also sends trackbacks and pings
    //---------------------------------------------------------------

    function submit_new_entry()
    {
        global $IN, $LANG, $FNS, $LOC, $DSP, $DB, $SESS, $STAT, $REGX;
                
        $tb_errors          = FALSE;
        $ping_errors        = FALSE;
        
        
        if ( ! $weblog_id = $IN->GBL('weblog_id', 'POST'))
        {
            return false;
        }
        
        //-----------------------------
        //  Does entry ID exist?
        //-----------------------------
        
        $entry_id = ( ! $IN->GBL('entry_id', 'POST')) ? '' : $IN->GBL('entry_id', 'POST');
        
        
        //-----------------------------
        //  Fetch Weblog Data
        //-----------------------------
        
        // We need this info in order to send Trackbacks and to ping
        
		$query = $DB->query("SELECT blog_title, blog_url, trackback_field FROM exp_weblogs WHERE weblog_id = '".$weblog_id."'");
		
		$blog_title 		= $query->row['blog_title'];
		$blog_url			= $query->row['blog_url'];
		$trackback_field	= $query->row['trackback_field'];
		
		        
        
        //-----------------------------
        //  Error trapping
        //-----------------------------
                        
        $error = array();
        
        // Fetch language file
        
        $LANG->fetch_language_file('publish_ad');
        
        //---------------------------------
        // No entry title? Assign error.
        //---------------------------------
        
        if ( ! $title = strip_tags($IN->GBL('title', 'POST')))
        {
            $error[] = $LANG->line('missing_title');
        }
            
        //---------------------------------------------
        // No date? Assign error.
        //---------------------------------------------
            
        if ( ! $IN->GBL('entry_date', 'POST'))
        {
            $error[] = $LANG->line('missing_date');
        }
        
        //---------------------------------------------
        // Convert the date to a Unix timestamp
        //---------------------------------------------
        
        $entry_date = $LOC->convert_human_date_to_gmt($IN->GBL('entry_date', 'POST'));
                     
        if ( ! is_numeric($entry_date)) 
        { 
            $error[] = $LANG->line('invalid_date_formatting');
        }

        //---------------------------------------------
        // Convert expiration date to a Unix timestamp
        //---------------------------------------------
        
        if ( ! $IN->GBL('expiration_date', 'POST'))
        {
            $expiration_date = 0;
        }
        else
        {
            $expiration_date = $LOC->convert_human_date_to_gmt($IN->GBL('expiration_date', 'POST'));

            if ( ! is_numeric($expiration_date)) 
            { 
            	$error[] = $LANG->line('invalid_date_formatting');
            }
        }
        
        //--------------------------------------
        // Are all requred fields filled out?
        //--------------------------------------
        
         $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE field_required = 'y'");
         
         if ($query->num_rows > 0)
         {
            foreach ($query->result as $row)
            {
                if (isset($_POST['field_id_'.$row['field_id']]) AND $_POST['field_id_'.$row['field_id']] == '') 
                {
                    $error[] = $LANG->line('custom_field_empty').NBS.$row['field_label'];
                }           
            }
         }
         
        //---------------------------------
        // Fetch xml-rpc ping server IDs
        //---------------------------------
                       
        $ping_servers = array();
        
        foreach ($_POST as $key => $val)
        {
            if (strstr($key, 'ping_'))
            {
                $ping_servers[] = substr($key, 5);
                
                unset($_POST[$key]);
            } 
        }

        //-------------------------------------
        // Pre-process Trackback data
        //-------------------------------------
           
        // If the weblog submission was via the bookmarklet we need to fetch the trackback URLs
        
        $tb_auto_urls = '';
        
        if ($IN->GBL('BK', 'GP'))
        {        
            foreach ($_POST as $key => $val)
            {
                if (ereg('^TB_AUTO_', $key))
                {
                    $tb_auto_urls .= $val.NL;
                }
            }
        }
        
        // Join the manually submitted trackbacks with the auto-disovered ones
        
        $trackback_urls = $IN->GBL('trackback_urls', 'POST');
        
        if ($tb_auto_urls != '')
        {
            $trackback_urls .= NL.$tb_auto_urls;
        }
        
        //--------------------------------------
        // Is weblog data present?
        //--------------------------------------
        
        // In order to send pings or trackbacks, the weblog needs a title and URL
        
        if ($trackback_urls != '' || count($ping_servers) > 0)
        {
            if ($blog_title == '' || $blog_url == '')
            {
                $error[] = $LANG->line('missing_weblog_data_for_pings');
            }            
        }
        
        
        //--------------------------------------
        // Is the title unique?
        //--------------------------------------
        
        if ($title != '')
        {			
            //---------------------------------
            // Do we have a URL title?
            //---------------------------------
            
            // If not, create one from the title
            
            $url_title = $IN->GBL('url_title', 'POST');
            
            if ( ! $url_title)
            {
                $url_title = $title;
            }
            
            // Kill all the extraneous characters.  
            // We want the URL title to pure alpha text
            
            $url_title = $REGX->create_url_title($url_title);
            
         
            //---------------------------------
            // Is URL title unique?
            //---------------------------------
             
            $sql = "SELECT count(*) AS count FROM exp_weblog_titles WHERE url_title = '".$DB->escape_str($url_title)."' AND weblog_id = '$weblog_id'";
        
            if ($entry_id != '')
            {
                $sql .= " AND entry_id != '$entry_id'";
            }
        
             $query = $DB->query($sql);
             
             if ($query->row['count'] > 0)
             {
                $error[] = $LANG->line('url_title_not_unique');
             }
        }
        
         
        //---------------------------------
        // Do we have an error to display?
        //---------------------------------

         if (count($error) > 0)
         {
            $msg = '';
            
            foreach($error as $val)
            {
                $msg .= $val.'<br />';  
            }
                       
            return $this->new_entry_form('preview', $msg);
         }   
            
            
        
        //---------------------------------
        // Fetch catagories
        //---------------------------------

        // We do it first so we can destroy the category index from 
        // the $_POST array since we use a separate table to store categories in
                        
        if (isset($_POST['category']) AND is_array($_POST['category']))
        {
        	foreach ($_POST['category'] as $cat_id)
        	{
				$this->cat_parents[] = $cat_id;
        	}

            $this->fetch_category_parents($_POST['category']);            
        }
        unset($_POST['category']);


        //---------------------------------
        // Fetch previously sent trackbacks
        //---------------------------------
        
        // If we are editing an existing entry, fetch the previously sent trackbacks
        // and add the new trackback URLs to them
        
        $sent_trackbacks = '';
        
        if ($trackback_urls != '' AND $entry_id != '')
        {
            $sent_trackbacks = trim($trackback_urls)."\n";
            
            $query = $DB->query("SELECT sent_trackbacks FROM exp_weblog_titles WHERE entry_id = '$entry_id'");
        
            if ($query->num_rows > 0)
            {
                $sent_trackbacks = $query->row['sent_trackbacks'];
            }
        }           
            
        //---------------------------------
        // Set "mode" cookie
        //---------------------------------

        // We do it now so we can destry it from the POST array
        
        if (isset($_POST['mode']))
        {    
            $FNS->set_cookie('mode' , $_POST['mode'], 60*60*24*182);       

            unset($_POST['mode']);
        }
        
        
        //---------------------------------
        // Build our query string
        //---------------------------------
        
        $data = array(  
                        'entry_id'          => '',
                        'weblog_id'         => $weblog_id,
                        'author_id'         => ( ! $IN->GBL('author_id', 'POST')) ? $SESS->userdata['member_id']: $IN->GBL('author_id', 'POST'),
                        'ip_address'        => $IN->IP,
                        'title'             => $title,
                        'url_title'         => $url_title,
                        'entry_date'        => $entry_date,
                        'year'              => gmdate('Y', $entry_date),
                        'month'             => gmdate('m', $entry_date),
                        'day'               => gmdate('d', $entry_date),
                        'expiration_date'   => $expiration_date,
                        'sticky'            => ($IN->GBL('sticky', 'POST') == 'y') ? 'y' : 'n',
                        'status'            => $IN->GBL('status', 'POST'),
                        'allow_comments'    => ($IN->GBL('allow_comments', 'POST') == 'y') ? 'y' : 'n',
                        'allow_trackbacks'  => ($IN->GBL('allow_trackbacks', 'POST') == 'y') ? 'y' : 'n'
                     );
        
        
        //---------------------------------
        // Insert the entry
        //---------------------------------
        
        if ($entry_id == '')
        {  
            $sql = $DB->insert_string('exp_weblog_titles', $data);
                
            $DB->query($sql); 
            
            $entry_id = $DB->insert_id;  
            
            //------------------------------------
            // Insert the custom field data
            //------------------------------------
            
            $cust_fields = array('entry_id' => $entry_id, 'weblog_id' => $weblog_id);
            
            foreach ($_POST as $key => $val)
            {
                if (strstr($key, 'field'))
                {
                	$cust_fields[$key] = $val;
                }        
            }
            
            if (count($cust_fields) > 0)
            {
                // Submit the custom fields
                                   
                $DB->query($DB->insert_string('exp_weblog_data', $cust_fields));
            }
            
            //------------------------------------
            // Update member stats
            //------------------------------------
            
            if ($data['author_id'] == $SESS->userdata['member_id'])
            {
                $total_entries = $SESS->userdata['total_entries'] +1;
            }
            else
            {
                $query = $DB->query("SELECT total_entries FROM exp_members WHERE member_id = '".$data['author_id']."'");

                $total_entries = $query->row['total_entries'] + 1;
            }
                                    
            $DB->query("UPDATE exp_members set total_entries = '$total_entries', last_entry_date = '".$LOC->now."' WHERE member_id = '".$data['author_id']."'");
                         
            //-------------------------------------
            // Set page title and success message
            //-------------------------------------
                            
            $type = 'new';
            
            $page_title = 'entry_has_been_added';
                        
            $message = $LANG->line($page_title);
        }        
        else
        {
            //---------------------------------
            // Update an existing entry
            //---------------------------------
     
            // First we need to see if the author of the entry has changed.
       
            $query = $DB->query("SELECT author_id FROM exp_weblog_titles WHERE entry_id = '$entry_id'");
            
            $old_author = $query->row['author_id'];
            
            if ($old_author != $data['author_id'])
            {
                // Decremenet the counter on the old author
            
                $query = $DB->query("SELECT total_entries FROM exp_members WHERE member_id = '$old_author'");

                $total_entries = $query->row['total_entries'] - 1;
            
                $DB->query("UPDATE exp_members set total_entries = '$total_entries' WHERE member_id = '$old_author'");
                      
                // Increment the counter on the new author
            
                $query = $DB->query("SELECT total_entries FROM exp_members WHERE member_id = '".$data['author_id']."'");

                $total_entries = $query->row['total_entries'] + 1;
            
                $DB->query("UPDATE exp_members set total_entries = '$total_entries' WHERE member_id = '".$data['author_id']."'");
            }
        
            //------------------------------------
            // Update the entry
            //------------------------------------        
        
            unset($data['entry_id']);
        
            $DB->query($DB->update_string('exp_weblog_titles', $data, "entry_id = '$entry_id'"));   
        
            //------------------------------------
            // Update the custom fields
            //------------------------------------        
            
            $cust_fields = array();
            
            foreach ($_POST as $key => $val)
            {
                if (strstr($key, 'field'))
                {
					$cust_fields[$key] = $val;
                }        
            }
            
            if (count($cust_fields) > 0)
            {
                // Update the custom fields
           
                $DB->query($DB->update_string('exp_weblog_data', $cust_fields, "entry_id = '$entry_id'"));   
            }
            
            //------------------------------------
            // Delete categories
            //------------------------------------        
                        
            // We will resubmit all categories next
                        
            $DB->query("DELETE FROM exp_category_posts WHERE entry_id = '$entry_id'");
            
            //------------------------------------
            // Set page title and success message
            //------------------------------------        
            
            $type = 'update';
            
            $page_title = 'entry_has_been_updated';
            
            $message = $LANG->line($page_title);
        }
        
        //---------------------------------
        // Insert categories
        //---------------------------------
        
        if ($this->cat_parents  > 0)
        { 
        	$this->cat_parents = array_unique($this->cat_parents);

        	sort($this->cat_parents);
        	
            foreach($this->cat_parents  as $val)
            {
                $DB->query("INSERT INTO exp_category_posts (entry_id, cat_id) VALUES ('$entry_id', '$val')");
            }
        }
        
        // ----------------------------------------
        // Update global stats
        // ----------------------------------------
        
	        $STAT->update_weblog_stats($weblog_id);

        //---------------------------------
        // Send trackbacks
        //---------------------------------
        
        $tb_body = ( ! isset($_POST['field_id_'.$trackback_field])) ? '' : $_POST['field_id_'.$trackback_field];
        
        if ($trackback_urls != '' AND $tb_body != '' AND $data['status'] != 'closed' AND $data['entry_date'] < ($LOC->now + 90))
        {
            $entry_link = $blog_url;
            
            if ( ! ereg("/$", $entry_link)) 
            {
                $entry_link .= '/';
            }
            
            $entry_link.= $url_title.'/';
            
            $tb_data = array(   'entry_id'      => $entry_id,
                                'entry_link'    => $FNS->remove_double_slashes($entry_link),
                                'entry_title'   => $title,
                                'entry_content' => $tb_body,
                                'weblog_name'   => $blog_title,
                                'trackback_url' => $trackback_urls
                            );
                                
            require PATH_MOD.'trackback/mcp.trackback'.EXT;
            
            $TB = new Trackback_CP;
                
            $tb_res = $TB->send_trackback($tb_data);
            
            //---------------------------------------
            // Update the "sent_trackbacks" field
            //---------------------------------------
            
            // Fetch the URLs that were sent successfully and update the DB
            
            if (count($tb_res['0']) > 0)
            {
                foreach ($tb_res['0'] as $val)
                {
                    $sent_trackbacks .= $val."\n";
                }
                
                $DB->query("UPDATE exp_weblog_titles SET sent_trackbacks = '$sent_trackbacks' WHERE entry_id = '$entry_id'");
            }
            
            if (count($tb_res['1']) > 0)
            {
                $tb_errors = TRUE;
            }                    
        }
        
        //---------------------------------
        // Send xml-rpc pings
        //---------------------------------
        
        $ping_message = '';

        if (count($ping_servers) > 0)
        {
        	$ping_result = $this->send_pings($ping_servers, $blog_title, $blog_url);
        	
        	if (count($ping_result) > 0)
        	{
        		$ping_errors = TRUE;
        		        		
                $ping_message .= $DSP->qdiv('highlight', BR.'<b>'.$LANG->line('xmlrpc_ping_errors').'</b>');
                
                foreach ($ping_result as $val)
                {
                    $ping_message .= $DSP->qdiv('', $val);
                }
        	}
        	
            //---------------------------------
            // Save ping button state
            //---------------------------------
            
            $DB->query("DELETE FROM exp_entry_ping_status WHERE entry_id = '$entry_id'");
        
			foreach ($ping_servers as $val)
			{
				$DB->query("INSERT INTO exp_entry_ping_status (entry_id, ping_id) VALUES ('$entry_id', '$val')");
			}  	
        }
        

        //---------------------------------------
        // Show ping erors if there are any
        //---------------------------------------
        
        if ($tb_errors == TRUE || $ping_errors == TRUE)
        {       
			$r  = $DSP->qdiv('success', $LANG->line($page_title).BR.BR);
         
            if (isset($tb_res['1']) AND count($tb_res['1']) > 0)
            {
                $r .= $DSP->qdiv('highlight', '<b>'.$LANG->line('trackback_url_errors').'</b>');
                
                foreach ($tb_res['1'] as $val)
                {
                    $r .= $DSP->qdiv('', $val);
                }
            } 
                        
            $r .= $ping_message;
            
			$r .= $DSP->qdiv('', BR.$DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=view_entry'.AMP.'weblog_id='.$IN->GBL('weblog_id', 'POST').AMP.'entry_id='.$entry_id, $LANG->line('click_to_view_your_entry')));
		
			return $DSP->set_return_data($LANG->line('publish'),$r);				   
        }
        
        //---------------------------------
        // Redirect to ths "success" page
        //---------------------------------
                                          
        $FNS->redirect(BASE.AMP.'C=edit'.AMP.'M=view_entry'.AMP.'weblog_id='.$IN->GBL('weblog_id', 'POST').AMP.'entry_id='.$entry_id.AMP.'U='.$type);
        exit;        
    }
    // END
    
    
    
	//---------------------------------
	// Send Pings
	//---------------------------------
    
    function send_pings($ping_servers, $blog_title, $blog_url)
    {
    	global $DB, $PREFS;
    	
    	$sql = "SELECT server_name, server_url, port FROM exp_ping_servers WHERE";
    	
    	foreach ($ping_servers as $id)
    	{
    		$sql .= " id = '$id' OR";    	
    	}
    	
    	$sql = substr($sql, 0, -2);
    	
		$query = $DB->query($sql);
    	
    	if ($query->num_rows == 0)
    	{
			return FALSE;    	
    	}
    	
		if ( ! class_exists('XML_RPC'))
		{
			require PATH_CORE.'core.xmlrpc'.EXT;
		}
		
		$XRPC = new XML_RPC;
		
		$result = array();
    	
		foreach ($query->result as $row)
		{
			if ( ! $XRPC->weblogs_com_ping($row['server_url'], $row['port'], $blog_title, $blog_url))
			{
				$result[] = $row['server_name'];
			}
		}		
		
		return $result;
    }
    // END
    
    
    
    //-----------------------------------------------------------
    // Category tree
    //-----------------------------------------------------------
    // This function (and the next) create a higherarchy tree
    // of categories.  There are two versions of the tree. The
    // "text" version is a list of links allowing the categories
    // to be edited.  The "form" version is displayed in a 
    // multi-select form on the new entry page.
    //-----------------------------------------------------------

    function category_tree($type = 'text', $group_id = '', $action = '', $default = '', $selected = '')
    {  
        global $DSP, $IN, $REGX, $DB;
  
        // Fetch category group ID number
      
        if ($group_id == '')
        {        
            if ( ! $group_id = $IN->GBL('group_id'))
            {
                return false;
            }
        }
        
        // If we are using the category list on the "new entry" page
        // and the person is returning to the edit page after previewing,
        // we need to gather the selected categories so we can highlight
        // them in the form.
        
        if ($action == 'preview')
        {
            $catarray = array();
        
            foreach ($_POST as $key => $val)
            {                
                if (strstr($key, 'category'))
                {
                    $catarray[$val] = $val;
                }            
            }
        }

        if ($action == 'edit')
        {
            $catarray = array();
            
            if (is_array($selected))
            {
                foreach ($selected as $key => $val)
                {
                    $catarray[$val] = $val;
                }
            }
        }
            
        // Fetch category groups
        
        $query = $DB->query("SELECT cat_name, cat_id, parent_id
                             FROM exp_categories 
                             WHERE group_id = '$group_id' 
                             ORDER BY parent_id, cat_name");
              
        if ($query->num_rows == 0)
        {
            return false;
        }     
        
        // Assign the query result to a multi-dimensional array
                    
        foreach($query->result as $row)
        {        
            $cat_array[$row['cat_id']]  = array($row['parent_id'], $row['cat_name']);
        }
        
         
        // If we are showing the "multi-select" version of the category list
        // we'll write the form header

        if ($type == 'select')
        {
            $size = count($cat_array) + 1;
        
            $this->categories[] = $DSP->input_select_header('category[]', 1, $size);
        }
        
        // Build our output...
        
        $sel = '';

        foreach($cat_array as $key => $val) 
        {
            if (0 == $val['0']) 
            {
                if ($action == 'new')
                {
                    $sel = ($default == $key) ? '1' : '';   
                }
                else
                {
                    $sel = (isset($catarray[$key])) ? '1' : '';   
                }
                                
                if ($type == 'select')
                    $this->categories[] = $DSP->input_select_option($key, $val['1'], $sel);
                else
                    $this->categories[] = array($key, $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_category'.AMP.'cat_id='.$key, '<b>'.$val['1']."</b><br />\n"));
                
                
                $this->category_subtree($key, $cat_array, $depth=1, $type, $action, $default, $selected);
            }
        }
        
        if ($type == 'select')
            $this->categories[] = $DSP->input_select_footer();
    }
    // END  
    
    
    
    
    //-----------------------------------------------------------
    // Category sub-tree
    //-----------------------------------------------------------
    // This function works with the preceeding one to show a
    // hierarchical display of categories
    //-----------------------------------------------------------
        
    function category_subtree($cat_id, $cat_array, $depth, $type, $action, $default = '', $selected = '')
    {
        global $DSP, $IN, $DB, $REGX, $LANG;

        $spcr = "&nbsp;";
        
        
        // Just as in the function above, we'll figure out which items are selected.
        
        if ($action == 'preview')
        {
            $catarray = array();
        
            foreach ($_POST as $key => $val)
            {
                if (strstr($key, 'category'))
                {
                    $catarray[$val] = $val;
                }            
            }
        }
        
        if ($action == 'edit')
        {
            $catarray = array();
            
            if (is_array($selected))
            {
                foreach ($selected as $key => $val)
                {
                    $catarray[$val] = $val;
                }
            }
        }
                
        $indent = $spcr.$spcr.$spcr.'|_'.$spcr;
    
        if ($depth == 1)	
        {
            $depth = 2;
        }
        else 
        {	                            
            $indent = str_repeat($spcr, $depth).$indent;
            
            $depth = $depth + 4;
        }
        
        $sel = '';
            
        foreach ($cat_array as $key => $val) 
        {
            if ($cat_id == $val['0']) 
            {
                $pre = ($depth > 2) ? "&nbsp;" : '';
                
                if ($action == 'new')
                {
                    $sel = ($default == $key) ? '1' : '';   
                }
                else
                {
                    $sel = (isset($catarray[$key])) ? '1' : '';   
                }
                
                if ($type == 'select')
                    $this->categories[] = $DSP->input_select_option($key, $pre.$indent.$spcr.$val['1'], $sel);
                else
                    $this->categories[] =  array($key, $pre.$indent.$spcr.$DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=blog_admin'.AMP.'P=edit_category'.AMP.'cat_id='.$key, '<b>'.$val['1'])."</b><br />\n");
                                
                $this->category_subtree($key, $cat_array, $depth, $type, $action, $default, $selected);
            }
        }
    }
    // END        
        
    

    //---------------------------------------------------------------
    // Text formatting buttons
    //---------------------------------------------------------------
    // This function displays radio buttons used to select
    // between xhtml, auto <br /> and "none" on the new entry page 
    //---------------------------------------------------------------

    function text_formatting_buttons($id, $default = 'xhtml')
    {
        global $DSP, $LANG;
        
		if ($default == '')
			$default = 'xhtml';
			                  
        $def1 = ($default == 'br')    ? 1 : 0;
        $def2 = ($default == 'xhtml') ? 1 : 0;
        $def3 = ($default == 'none')  ? 1 : 0;
        
        return
                $DSP->div('xhtmlWrapper').
                '<b>'.$LANG->line('newline_format').'</b>'.$DSP->nbs(2). 
                
                $DSP->input_radio('field_ft_'.$id, 'br', $def1).
                htmlspecialchars('<br />').NBS.$DSP->nbs(3).
                
                $DSP->input_radio('field_ft_'.$id, 'xhtml', $def2).
                'xhtml'.NBS.$DSP->nbs(3).

                $DSP->input_radio('field_ft_'.$id, 'none', $def3).
                $LANG->line('none').NBS.$DSP->nbs(3).

                $DSP->div_c();
    }
    // END
                
        
    //---------------------------------------------------------------
    // Fetch ping servers
    //---------------------------------------------------------------
    // This function displays the ping server checkboxes
    //---------------------------------------------------------------
        
    function fetch_ping_servers($member_id = '', $entry_id = '')
    {
        global $LANG, $DB, $SESS, $DSP;
        
        
        $sent_pings = array();
        
        if ($entry_id != '')
        {
        	$query = $DB->query("SELECT ping_id FROM exp_entry_ping_status WHERE entry_id = '$entry_id'");
        	
			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$sent_pings[$row['ping_id']] = TRUE;
				}
			}
        }
        
        if ($member_id == '')
        {        
            $member_id = $SESS->userdata['member_id'];
        }

        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_ping_servers WHERE member_id = '".$SESS->userdata['member_id']."'");
        
        $member_id = ($query->row['count'] == 0) ? 0 : $SESS->userdata['member_id'];
              
        $query = $DB->query("SELECT id, server_name, is_default FROM exp_ping_servers WHERE member_id = '$member_id' ORDER BY server_order");

        if ($query->num_rows == 0)
        {
            return false;
        }
        
        $r  = $DSP->div('publishBorder');
        
        $r .= $DSP->qdiv('publishPad', $DSP->qdiv('itemTitle', $LANG->line('ping_sites')));
        
        foreach($query->result as $row)
        {
            if (isset($_POST['preview']))
            {
                $selected = (isset($_POST['ping_'.$row['id']]) AND $row['is_default'] == 'y') ? 1 : '';
            }
            else
            {
            	if ($entry_id != '')
            	{
					$selected = (isset($sent_pings[$row['id']])) ? 1 : '';
            	}
            	else
            	{
					$selected = ($row['is_default'] == 'y') ? 1 : '';
				}
            }
            
            $r .= $DSP->qdiv('publishPad', $DSP->input_checkbox('ping_'.$row['id'], 'y', $selected).' '.$row['server_name']);
        }

        $r .= $DSP->div_c();
        
        return $r;
    }        
    // END
    
    
       
        
    //---------------------------------------------------------------
    // HTML formatting buttons
    //---------------------------------------------------------------
    // This function and the next display the HTML formatting buttons
    //---------------------------------------------------------------
    
    function default_buttons($close = true)
    {
        global $DSP, $LANG;

            $buttons = array(
                              'link'      => array("javascript:promptTag(\"link\");", ''),
                              'email'     => array("javascript:promptTag(\"email\");", ''),
                              'image'     => array("javascript:promptTag(\"image\");", ''),
                              'close_all' => array("javascript:closeall();", "")
                            );
               
               
             if ( ! $close) 
             {
                unset($buttons['close_all']);
             } 
               
             $r = '';
             $i = 0;
                
            foreach ($buttons as $k => $v)
            {                    
                if ($i == 0 AND $close == false)
                {
                    $r .= $DSP->td('htmlButtonOutterL');
                }
                else
                {
                    $r .= $DSP->td('htmlButtonOutter');
                }
                
                $i++;
            
                $r .= 
                      $DSP->div('htmlButtonInner').              
                      $DSP->div('htmlButtonA').
                      $DSP->anchor($v['0'], $LANG->line($k), $v['1']).
                      $DSP->div_c().
                      $DSP->div_c().
                      $DSP->td_c();
            }
    
        return $r;
    }
    
    //---------------------------------------------------------------
    // HTML formatting buttons
    //---------------------------------------------------------------
    // This function and the above display the HTML formatting buttons
    //---------------------------------------------------------------

    function html_formatting_buttons($member_id = '', $field_group)
    {
        global $DSP, $IN, $SESS, $DB, $LANG;
        
        if ($member_id == '')
        {        
            $member_id = $SESS->userdata['member_id'];
        }
        
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_html_buttons WHERE member_id = '$member_id'");
        
        $member_id = ($query->row['count'] == 0) ? 0 : $SESS->userdata['member_id'];
        
        $query_one = $DB->query("SELECT * FROM exp_html_buttons WHERE member_id = '$member_id' AND tag_row = '1' ORDER BY tag_order");
        $query_two = $DB->query("SELECT * FROM exp_html_buttons WHERE member_id = '$member_id' AND  tag_row = '2' ORDER BY tag_order");
                 
        if ($query_one->num_rows == 0  AND $query_two->num_rows == 0)
        {
            return false;
        }                 
                          
        $data  = array();

        if ($query_one->num_rows > 0)
        {
            $data[] = $query_one->result;
        }
        
        if ($query_two->num_rows > 0)
        {
            $data[] = $query_two->result;
        }
                     
        $r = 
            $DSP->div('buttonInsert').
            $DSP->div('defaultSmall');
              
        if (count($data) > 0)
        {
            if ( ! $mode = $IN->GBL('mode', 'POST'))
            {
                if ( ! $mode = $IN->GBL('mode', 'COOKIE'))
                {
                    $mode = '';
                }
            }
        
            if ($mode == 'guided')
            {
                $guided = "checked='checked'";
                $normal = "";
            }
            else
            {
                $normal = "checked='checked'";
                $guided = "";
            }
        
        
            $r .=               
                  '<b>'.$LANG->line('button_mode').'</b>'.$DSP->nbs(3).
                  $LANG->line('guided').NBS.
                  "<input type='radio' name='mode' value='guided' onclick='setmode(this.value)' $guided/>".
                  $DSP->nbs(2).
                  $LANG->line('normal').NBS.
                  "<input type='radio' name='mode' value='normal' onclick='setmode(this.value)' $normal/>".
                  $DSP->nbs(8);
        }
            $r .= 
                  $DSP->anchorpop(BASE.AMP.'C=publish'.AMP.'M=file_upload_form'.AMP.'field_group='.$field_group.AMP.'Z=1', '<b>'.$LANG->line('file_upload').'</b>', '400', '540').
                  $DSP->nbs(10).
                  $DSP->anchorpop(BASE.AMP.'C=publish'.AMP.'M=emoticons'.AMP.'field_group='.$field_group.AMP.'Z=1', '<b>'.$LANG->line('emoticons').'</b>', '540', '550').
                  $DSP->br(2).
                  $DSP->div_c();
              
        $jsvars = array();
              
        if (count($data) == 0)
        {
            $r  .= $DSP->table('buttonMargin', '0', '', '').
                   $DSP->tr().
                   $this->default_buttons(0).
                   $DSP->tr_c().
                   $DSP->table_c();
        }
        else
        {
            $rows = (count($data) == 1) ? 1 : 2;
            
            $n = 0;
            $i = 0;

            foreach ($data as $groups)
            {                 
                $r  .= $DSP->table('buttonMargin', '0', '', '').
                       $DSP->tr();
                
                $edge = false;
                
                foreach ($groups as $row)
                {
                    $accesskey = ($row['accesskey'] != '') ? "accesskey=\"".trim($row['accesskey'])."\" " : "";
                                                                               
                    if ($row['tag_close'])
                    {                        
                        $jsfunc = $accesskey."onclick='taginsert(this, \"".htmlspecialchars(addslashes($row['tag_open']))."\", \"".htmlspecialchars(addslashes($row['tag_close']))."\")'";
                    }
                    else
                    {                
                        $jsfunc = $accesskey."onclick='singleinsert(\"".htmlspecialchars(addslashes($row['tag_open']))."\")'";
                    }
                    
                    $jsvars[] = 'button_'.$i;
                    
                    if ($edge == false)
                    {
                        $r .= $DSP->td('htmlButtonOutterL');
                    }
                    else
                    {



                        $r .= $DSP->td('htmlButtonOutter');
                        
                        $edge = true;
                    }
                          
                          
                    $r .= $DSP->div('htmlButtonInner').              
                          "<div class='htmlButtonA' id='button_".$i."'>".
                          $DSP->anchor('javascript:nullo()', htmlspecialchars(trim($row['tag_name'])), " name='button_{$i}' $jsfunc").                          
                          $DSP->div_c().
                          $DSP->div_c().
                          $DSP->td_c();       
                          
                          $i++;   
                          
                          $edge = true;
                }    
                    
                if ($rows == 1 || ($rows == 2 AND $n == 0))
                {
                    $r .= $this->default_buttons();                
                }
                          
                $r .=              
                      $DSP->tr_c().
                      $DSP->table_c();
                                        
                $n ++;       
            }
        }
        
        $r .= $DSP->div_c();
 
                             
        ob_start();
        
        ?>     
        
        <script language="javascript" type="text/javascript"> 
        <!--


        var selField  = false;
        var selMode   = "normal";
        
        var no_cursor     = "<?php echo $LANG->line('html_buttons_no_cursor'); ?>";
        var url_text      = "<?php echo $LANG->line('html_buttons_url_text'); ?>";
        var webpage_text  = "<?php echo $LANG->line('html_buttons_webpage_text'); ?>";
        var title_text	  = "<?php echo $LANG->line('html_buttons_title_text'); ?>";
        var image_text    = "<?php echo $LANG->line('html_buttons_image_text'); ?>";
        var email_text    = "<?php echo $LANG->line('html_buttons_email_text'); ?>";
        var email_title   = "<?php echo $LANG->line('html_buttons_email_title'); ?>";
        var enter_text    = "<?php echo $LANG->line('html_buttons_enter_text'); ?>";
        
        <?php
            
            echo "\n";
        
            foreach ($jsvars as $val)
            {
                echo "var $val = 0;\n";
            }
            
        ?>
        
        tagarray  = new Array();
        usedarray = new Array();
        
        function nullo()
        {
            return;
        }
                
        //  State change
        
        function styleswap(link)
        {
            if (document.getElementById(link).className == 'htmlButtonA')
            {
                document.getElementById(link).className = 'htmlButtonB';
            }
            else
            {
                document.getElementById(link).className = 'htmlButtonA';
            }
        
        }
        
        //  Set button mode
        
        function setmode(which)
        {	
            if (which == 'guided')
                selMode = 'guided';
            else
                selMode = 'normal';
        }
        
        //  Dynamically set the textarea name
        
        function setFieldName(which)
        {
            if (which != selField)
            {
                selField = which;
                
                clear_state();
                        
                tagarray  = new Array();
                usedarray = new Array();
            }
        }
        
        // Clear state
        
        function clear_state()
        {
            if (usedarray[0])
            {
                while (usedarray[0])
                {
                    clearState = arraypop(usedarray);
                    
                    eval(clearState + " = 0");
                    
                    document.getElementById(clearState).className = 'htmlButtonA';
                }
            }	
        }
        
        // Array size
        
        function getarraysize(thearray)
        {
            for (i = 0; i < thearray.length; i++)
            {
                if ((thearray[i] == "undefined") || (thearray[i] == "") || (thearray[i] == null))
                {
                    return i;
                }
            }
            
            return thearray.length;
        }        
        
        // Array push
        
        function arraypush(thearray, value)
        {
            thearray[getarraysize(thearray)] = value;
        }
        
        // Array pop
        
        function arraypop(thearray)
        {
            thearraysize = getarraysize(thearray);
            retval = thearray[thearraysize - 1];
            delete thearray[thearraysize - 1];
            return retval;
        }
        
        // Insert single tag
        
        function singleinsert(tagOpen)
        {
            if ( ! selField)
            {
                alert(no_cursor);
                
                return false;
            }
                
            eval("document.entryform." + selField + ".value += tagOpen");	
            
            curField = eval('document.entryform.' + selField);
            
            curField.blur();
        
            curField.focus();	
        }
        
        
        // Insert tag
        
        function taginsert(item, tagOpen, tagClose)
        {
        
            // Determine which tag we are dealing with
        
            var which = eval('item.name');
            
            if ( ! selField)
            {
                alert(no_cursor);
                        
                return false;
            }
            
            if (selMode == 'guided')
            {
                data = prompt(enter_text, "");
                
                if ((data != null) && (data != ""))
                {
                    result =  tagOpen + data + tagClose;
                    
                    eval("document.entryform." + selField + ".value += result");			
                }
                
                curField = eval('document.entryform.' + selField);
                
                curField.blur();
            
                curField.focus();	
                
                return;		
            }
        
        
            // Is this a Windows user?  
                
            var theSelection = false;    
            
            if ((navigator.appName == "Microsoft Internet Explorer") &&
                (navigator.appVersion.indexOf("Win") != -1))
            {
                theSelection = document.selection.createRange().text; 
            }
            
            // If so, add tags around selection
        
            if (theSelection) 
            {		
                var tags = tagOpen;
                var tage = tagClose; 
            
                document.selection.createRange().text = tags + theSelection + tage;
                
                curField = eval('document.entryform.' + selField);
                
                curField.blur();
        
                curField.focus();
                        
                theSelection = '';
                
                return;
            }
            
                
            // Add single open tags
            
            if (eval(which) == 0)
            {
                var result = tagOpen;
                
                eval("document.entryform." + selField + ".value += result");			
                        
                eval(which + " = 1");
                
                arraypush(tagarray, tagClose);
                arraypush(usedarray, which);
                
                styleswap(which);
            }
            else
            {
                // Close tags
            
                n = 0;
                
                for (i = 0 ; i < tagarray.length; i++ )
                {
                    if (tagarray[i] == tagClose)
                    {
                        n = i;
                        
						while (tagarray[n])
						{
							closeTag = arraypop(tagarray);
										
							eval("document.entryform." + selField + ".value += closeTag");			
						}
						
						while (usedarray[n])
						{
							clearState = arraypop(usedarray);
							
							eval(clearState + " = 0");
							
							document.getElementById(clearState).className = 'htmlButtonA';
						}
                    }
                }
            }
            
            curField = eval('document.entryform.' + selField);
            
            curField.blur();
        
            curField.focus();	
        }
        
        // Prompted tags
        
        function promptTag(which)
        {
            if ( ! selField)
            {
                alert(no_cursor);
                
                return;
            }
        
            if ( ! which)
                return;
        
            if (which == "link")
            {
                var URL = prompt(url_text, "http://");
                        
                if ( ! URL || URL == 'http://' || URL == null)
                    return; 
                
                var Name = prompt(webpage_text, "");
                
                var Title = prompt(title_text, "");
                
                if (Title == "")
                {
                	var Title = Name;
                }
            
                var Link = '<a href="' + URL + '" title="' + Title + '">' + Name + '<'+'/a>';
            }
            
            
            if (which == "email")
            {
                var Email = prompt(email_text,"");
                
                if ( ! Email || Email == null)
                    return; 
                    
                var Title = prompt(email_title, "");
            
                if (Title == "")
                    Title = Email;
            
                var Link = '<a href="mailto:' + Email + '">' + Title + '<'+'/a>';                
            }
        
            if (which == "image")
            {
                var URL   = prompt(image_text, "http://");
                
                if ( ! URL || URL == null)
                    return; 
            
                var Link = '<img src="' + URL + '" />';
            }
        
            eval("document.entryform." + selField + ".value += Link");			
        
            curField = eval('document.entryform.' + selField);
            
            curField.blur();
        
            curField.focus();
        }
        
        // Close all tags
        
        function closeall()
        {	
            if (tagarray[0])
            {
                while (tagarray[0])
                {
                    closeTag = arraypop(tagarray);
                                
                    eval("document.entryform." + selField + ".value += closeTag");			
                }
            }
            
            clear_state();	
                
            curField = eval('document.entryform.' + selField);
            
            curField.focus();
        }
        
        //-->
        </script>
        
        <?php

    $javascript = ob_get_contents();
    
    ob_end_clean();

    return $javascript.$r;

    }
    // END
        
   
   

    //---------------------------------------------------------------
    // View previous pings
    //---------------------------------------------------------------
    // This function lets you look at trackback pings that you sent previously
    //---------------------------------------------------------------

    function view_previous_pings()
    {
        global $IN, $DSP, $LANG, $DB;
           
        if ( ! $entry_id = $IN->GBL('entry_id', 'GP'))
        {
            return false;
        }
        
        $query = $DB->query("SELECT sent_trackbacks FROM  exp_weblog_titles WHERE entry_id = '$entry_id'");        
        
        if ($query->num_rows == 0)
        {
            return false;
        }


        $DSP->title = $LANG->line('view_previous_pings');
        $DSP->crump = $LANG->line('view_previous_pings');
        
        $DSP->body  = $DSP->div('fieldWrapper').
                      $DSP->div('bold').
                      $LANG->line('previiously_pinged_urls').
                      $DSP->div_c().
                      $DSP->input_textarea('trackback_urls', $query->row['sent_trackbacks'], 12, 'textarea', '99%').
                      $DSP->div_c();        
    }   
    // END
   
   
   
    
   
   
//=====================================================================
//  "EDIT" PAGE FUNCTIONS
//=====================================================================
    
       
   
    //-----------------------------------------------------------
    // Edit weblogs page
    //-----------------------------------------------------------
    // This function is called when the EDIT tab is clicked
    //-----------------------------------------------------------

    function edit_entries($weblog_id = '', $message = '')
    {
        global $IN, $LANG, $DSP, $FNS, $LOC, $DB, $SESS;


        // Results per page - make this an option
        
        $perpage = 50;       

        
        // Security check
        
        if ( ! $DSP->allowed_group('can_access_edit'))
        {
            return $DSP->no_access_message();
        }
        
        
        //-----------------------------------------------------------
        // Fetch weblog ID numbers assigned to the current user
        //-----------------------------------------------------------
                
        if ( ! $DSP->allowed_group('can_edit_other_entries'))
        {
            $allowed_blogs = $FNS->fetch_assigned_weblogs();
            
            // If there aren't any blogs assigned to the user, bail out
            
            if (count($allowed_blogs) == 0)
            {
                return $DSP->no_access_message();
            }
        }
        
        //------------------------------
        // Fetch Color Library
        //------------------------------
        
        // We use this to assist with our status colors
        
        if (file_exists(PATH.'lib/colors'.EXT))
        {
        	include (PATH.'lib/colors'.EXT);
        }
        else
        {	
        	$colors = '';
        }
                
        // We need to determine which weblog to show entries from.
        // if the weblog_id global doesn't exist we'll show all weblogs
        // combined
                
        if ($weblog_id == '')
        {
            $weblog_id = $IN->GBL('weblog_id', 'GP');
        }
        
        if ($weblog_id == 'null' OR $weblog_id === FALSE)
        {
            $weblog_id = '';
        }
        
        
        $cat_id = $IN->GBL('cat_id', 'GP');
        $status = $IN->GBL('status', 'GP');      
        $order  = $IN->GBL('order', 'GP');
        $date_range = $IN->GBL('date_range', 'GP');

        // How many blogs do we have?  This will determine a number of things later

        if ( ! $DSP->allowed_group('can_edit_other_entries') || $SESS->userdata['weblog_id'] != 0)
        {
            $total_blogs = count($allowed_blogs);
        }
        else
        {
            $query = $DB->query("SELECT count(*) as count FROM exp_weblogs WHERE is_user_blog = 'n'");
    
            $total_blogs = $query->row['count'];
        }
              
        // Begin building the page output
        
        $r = $DSP->heading($LANG->line('edit_weblog_entries'));
        
        // Do we have a message to show?
        // Note: a message is displayed on this page after editing or submitting a new entry
        
        if ($message != '')
        {
            $r .= $message;
        }     
        
        // Declare the "filtering" form
        
        $r .= $DSP->form(BASE.AMP.'C=edit'.AMP.'M=view_entries', 'filterform', 'post');
        
        // If we have more than one weblog we'll write the JavaScript menu switching code       
        
        if ($total_blogs > 1)
        {      
            $r  .= $this->filtering_menus();
        }
        
        // Table start
                
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->td('itemWrapper', '', '7').NL;
        
        // If we have more than one blog we'll add the "onchange" method to
        // the form so that it'll automatically switch categories and statuses
        
        if ($total_blogs > 1)
        {       
            $r .= "<select name='weblog_id' class='select' onchange='changemenu(this.selectedIndex);'>\n";
        }
        else
        {
            $r .= "<select name='weblog_id' class='select'>\n";
        }
        
        
        // Design note:  Becuase the JavaScript code dynamically switches the information inside the
        // pull-down menus we can't show any particular menu in a "selected" state unless there is only
        // one weblog.  Remember that each weblog is fully independent, so it can have its own 
        // categories, statuses, etc. 
        
        // Weblog selection pull-down menu
        
        $r .= $DSP->input_select_option('null', $LANG->line('weblog')).
              $DSP->input_select_option('null',  $LANG->line('all'));
        
        // Fetch the names of all weblogs and write each one in an <option> field
        
        $sql = "SELECT blog_title, weblog_id FROM exp_weblogs";
        
        // If the user is restricted to specific blogs, add that to the query
        
        if ( ! $DSP->allowed_group('can_edit_other_entries') || $SESS->userdata['weblog_id'] != 0)
        {
            $sql .= " WHERE (";
        
            foreach ($allowed_blogs as $val)
            {
                $sql .= " weblog_id = '".$val."' OR"; 
            }
            
            $sql = substr($sql, 0, -2).')';
        }
        else
        {
            $sql .= " WHERE is_user_blog = 'n'";
        }

        $sql .= " order by blog_title";    
                
        $query = $DB->query($sql);
                
        if ($query->num_rows == 1)
        {
            $weblog_id = $query->row['weblog_id'];
        }
        
        $selected = '';
        
        foreach ($query->result as $row)
        {
            if ($total_blogs == 1)
            {               
                $selected = ($weblog_id == $row['weblog_id']) ? 1 : '';          
            }      
        
            $r .= $DSP->input_select_option($row['weblog_id'], $row['blog_title'], $selected);
        }        

        $r .= $DSP->input_select_footer().
              $DSP->nbs(2);
        
        
        // Category pull-down menu
        
        $r .= $DSP->input_select_header('cat_id').
              $DSP->input_select_option('', $LANG->line('category')).
              $DSP->input_select_option('', $LANG->line('all'));

        if ($total_blogs == 1)
        {               
            $query = $DB->query("SELECT cat_id, cat_name FROM exp_categories WHERE group_id = '$weblog_id' ORDER BY cat_name");
                                
            if ($query->num_rows > 0)
            {
                foreach ($query->result as $row)
                {
                    $selected = ($cat_id == $row['cat_id']) ? 1 : '';          

                    $r .= $DSP->input_select_option($row['cat_id'], $row['cat_name'], $selected);
                }
            }
        }

        $r .= $DSP->input_select_footer().
              $DSP->nbs(2);
        
        
        // Status pull-down menu
        
        $sel_1 = '';
        $sel_2 = '';
        
        if ($total_blogs == 1)
        {               
              $sel_1 = ($status == 'open')   ? 1 : '';          
              $sel_2 = ($status == 'closed') ? 1 : '';          
        }        
        
        $r .= $DSP->input_select_header('status').
              $DSP->input_select_option('', $LANG->line('status')).
              $DSP->input_select_option('', $LANG->line('all'));
        
        if ($total_blogs == 1)
        {               
            $query = $DB->query("SELECT status FROM exp_statuses WHERE group_id = '$weblog_id' ORDER BY status_order");                            
                            
            if ($query->num_rows > 0)
            {
                foreach ($query->result as $row)
                {
                    $selected = ($status == $row['status']) ? 1 : '';   
                   
					$status_name = ($row['status'] == 'closed' OR $row['status'] == 'open') ?  $LANG->line($row['status']) : $row['status'];
                
                    $r .= $DSP->input_select_option($row['status'], $status_name, $selected);
                }
            }
        }        
        
        $r .= $DSP->input_select_footer().
              $DSP->nbs(2);
        
        // Date range pull-down menu
        
        $sel_1 = ''; $sel_2 = ''; $sel_3 = ''; $sel_4 = ''; $sel_5 = ''; 
        
        if ($total_blogs == 1)
        {               
              $sel_1 = ($date_range == '1')   ? 1 : '';          
              $sel_2 = ($date_range == '7')   ? 1 : '';          
              $sel_3 = ($date_range == '31')  ? 1 : '';          
              $sel_4 = ($date_range == '182') ? 1 : '';          
              $sel_5 = ($date_range == '365') ? 1 : '';          
        }
        
        $r .= $DSP->input_select_header('date_range').
              $DSP->input_select_option('', $LANG->line('date_range')).
              $DSP->input_select_option('1', $LANG->line('today'), $sel_1).
              $DSP->input_select_option('7', $LANG->line('past_week'), $sel_2).
              $DSP->input_select_option('31', $LANG->line('past_month'), $sel_3).
              $DSP->input_select_option('182', $LANG->line('past_six_months'), $sel_4).
              $DSP->input_select_option('365', $LANG->line('past_year'), $sel_5).
              $DSP->input_select_option('', $LANG->line('any_date')).
              $DSP->input_select_footer().
              $DSP->nbs(2);
        
        
        // Display order pull-down menu
        
        $sel_1 = ''; $sel_2 = ''; $sel_3 = ''; 

        if ($total_blogs == 1)
        {               
              $sel_1 = ($order == 'desc')  ? 1 : '';          
              $sel_2 = ($order == 'asc')   ? 1 : '';          
              $sel_3 = ($order == 'alpha') ? 1 : '';          
        }
        
        $r .= $DSP->input_select_header('order').
              $DSP->input_select_option('desc', $LANG->line('order'), $sel_1).
              $DSP->input_select_option('asc', $LANG->line('ascending'), $sel_2).
              $DSP->input_select_option('desc', $LANG->line('descending'), $sel_1).
              $DSP->input_select_option('alpha', $LANG->line('alpha'), $sel_3).
              $DSP->input_select_footer().
              $DSP->nbs(2);
        
        
        // Submit button and form close

        $r .= $DSP->input_submit($LANG->line('submit'), 'submit');
              
        $r .= $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
        
        $r .= $DSP->form_c();
        
        // Build the SQL query as well as the query string for the paginate links
        
        
        $pageurl = BASE.AMP.'C=edit'.AMP.'M=view_entries';
        
        $sql = "SELECT DISTINCT 
                       exp_weblog_titles.weblog_id, 
                       exp_weblog_titles.entry_id,         
                       exp_weblog_titles.title, 
                       exp_weblog_titles.status, 
                       exp_weblog_titles.entry_date, 
                       exp_weblog_titles.comment_total, 
                       exp_weblog_titles.trackback_total, 
                       exp_members.username,
                       exp_members.screen_name
                FROM   exp_weblog_titles, exp_members, exp_weblogs";
                
        if ($cat_id)
        {
            $sql .= ", exp_category_posts ";             
        }
        
        
        // Limit to weblogs assigned to user
        
        if ( ! $DSP->allowed_group('can_edit_other_entries') || $SESS->userdata['weblog_id'] != 0)
        {
            $sql .= " WHERE (";
        
            foreach ($allowed_blogs as $val)
            {
                $sql .= " exp_weblog_titles.weblog_id = '".$val."' OR"; 
            }
            
            $sql = substr($sql, 0, -2).')';
        }
        else
        {
            $sql .= " WHERE is_user_blog = 'n'";
        }
                        
        $sql .= " AND exp_weblog_titles.author_id = exp_members.member_id"; 
        
        
		if ( ! $DSP->allowed_group('can_view_other_entries'))
		{
        	$sql .= " AND exp_weblog_titles.author_id = ".$SESS->userdata['member_id'] ; 
		}
        
        if ($weblog_id)
        {
            $sql .= " AND exp_weblog_titles.weblog_id = $weblog_id";
        }
        
        if ($date_range)
        {
            $pageurl .= AMP.'date_range='.$date_range;
        
            $date_range = time() - ($date_range * 60 * 60 * 24);
                    
            $sql .= " AND exp_weblog_titles.entry_date > $date_range";
        }
             
        if ($cat_id)
        {
            $pageurl .= AMP.'cat_id='.$cat_id;
        
            $sql .= " AND exp_category_posts.cat_id = '$cat_id'     
                      AND exp_category_posts.entry_id = exp_weblog_titles.entry_id ";    
        }
                
        if ($status)
        {
            $pageurl .= AMP.'status='.$status;
        
            $sql .= " AND exp_weblog_titles.status = '$status'";        
        }
        
        $sql .= " ORDER BY ";        
        
        if ($order)
        {
            $pageurl .= AMP.'order='.$order;
        
            switch ($order)
            {
                case 'asc'   : $sql .= "entry_date asc";
                    break;
                case 'desc'  : $sql .= "entry_date desc";
                    break;
                case 'alpha' : $sql .= "title asc";
                    break;
                default      : $sql .= "entry_date desc";
            }
        }
        else
        {
            $sql .= "entry_date desc";
        }
      
        // Run the query the first time.
        // We need to run this query twice. The first time without the LIMIT
        // clause so that we can determine the total number of available results
        // and pass this number to the paginate class. Then we'll add the LIMIT
        // clause the SQL statement and run it again and use that to render
        // the page output.  Obviously doing this twice isn't ideal but I don't
        // know of any other way to accomplish this.
    
        $query = $DB->query($sql);  
        
        // No result?  Show the "no results" message
        
        $total_count = $query->num_rows;
        
        if ($total_count == 0)
        {            
            $r .= $DSP->qdiv('', BR.$LANG->line('no_entries_matching_that_criteria'));
        
            return $DSP->set_return_data(
                                            $LANG->line('edit').$DSP->crumb_item($LANG->line('edit_weblog_entries')), 
                                            $r,
                                            $LANG->line('edit_weblog_entries')
                                        );    
        }
                
        // Get the current row number and add the LIMIT clause to the SQL query
        
        if ( ! $rownum = $IN->GBL('rownum', 'GP'))
        {        
            $rownum = 0;
        }
                        
        $sql .= " LIMIT ".$rownum.", ".$perpage;        
        
        
        // Run the query              
    
        $query = $DB->query($sql);  
        


		$sql = "SELECT weblog_id, blog_name FROM exp_weblogs ";
				
		if (USER_BLOG !== FALSE)
		{
			$sql .= " WHERE exp_weblogs.weblog_id = '".UB_BLOG_ID."'";
		}
		else
		{
			$sql .= " WHERE exp_weblogs.is_user_blog = 'n'";
		}
		        
        $w_array = array();
        
        $result = $DB->query($sql);

        if ($result->num_rows > 0)
        {            
            foreach ($result->result as $rez)
            {
                $w_array[$rez['weblog_id']] = $rez['blog_name'];
            }
        }
        
        
        // We need to also grab the status highlight colors and the name of the weblog
        
        $cql = "SELECT exp_weblogs.weblog_id, exp_weblogs.blog_name, exp_statuses.status, exp_statuses.highlight
                 FROM  exp_weblogs, exp_statuses, exp_status_groups
                 WHERE exp_status_groups.group_id = exp_weblogs.status_group
                 AND   exp_statuses.highlight != ''";
                 
        
        // Limit to weblogs assigned to user
        
        if ( ! $DSP->allowed_group('can_edit_other_entries') || $SESS->userdata['weblog_id'] != 0)
        {
            $sql .= " AND (";
        
            foreach ($allowed_blogs as $val)
            {
                $sql .= " exp_weblogs.weblog_id = '".$val."' OR"; 
            }
            
            $sql = substr($sql, 0, -2).')';
        }
        else
        {
            $cql .= " AND is_user_blog = 'n'";     
        }
        
        
        $result = $DB->query($cql);
        
        $c_array = array();

        if ($result->num_rows > 0)
        {            
            foreach ($result->result as $rez)
            {            
                $c_array[$rez['weblog_id'].'_'.$rez['status']] = str_replace('#', '', $rez['highlight']);
            }
        }

		// "select all" checkbox

        $r .= $DSP->toggle();
        
        // Build the item headings  
        
        // Declare the "delete" form
        
        $r .= $DSP->form('C=edit'.AMP.'M=delete_conf', 'target');
       
              
        // Table start

        $r .= $DSP->table('tableBorder', '0', '0', '100%').
              $DSP->tr().
              $DSP->td('tablePad'); 
                
        $r .= $DSP->table('', '0', '', '100%').
              $DSP->tr().
              $DSP->table_qcell('tableHeadingBold', $LANG->line('title')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('edit')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('comments')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('author')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('date')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('weblog')).
              $DSP->table_qcell('tableHeadingBold', $LANG->line('status')).
              $DSP->table_qcell('tableHeadingBold', $DSP->input_checkbox('toggleflag', '', '', "onclick=\"toggle(this);\"").NBS.$LANG->line('delete')).
              $DSP->tr_c();
       
        // Loop through the query result and write each table row 
                       
        $i = 0;
        
        foreach($query->result as $row)
        {
            $style = ($i % 2) ? 'tableCellOne' : 'tableCellTwo'; $i++;
                      
            $r .= $DSP->tr();
            
            // Weblog entry title (view entry)
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->anchor(
                                                  BASE.AMP.'C=edit'.AMP.'M=view_entry'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], 
                                                  '<b>'.$row['title'].'</b>'
                                                )
                                    );
            // Edit entry
            
            $r .= $DSP->table_qcell($style, 
                                    $DSP->anchor(
                                                  BASE.AMP.'C=edit'.AMP.'M=edit_entry'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], 
                                                  $LANG->line('edit')
                                                )
                                    );             
            // Comment count
            
            $res = $DB->query("SELECT COUNT(*) AS count FROM exp_comments WHERE entry_id = '".$row['entry_id']."'");$DB->q_count--;
            $r .= $DSP->table_qcell($style, 
                                    '('.($res->row['count']+$row['trackback_total']).')'.$DSP->nbs(2).
                                    $DSP->anchor(
                                                   BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'weblog_id='.$row['weblog_id'].AMP.'entry_id='.$row['entry_id'], 
                                                   $LANG->line('view')
                                                 )
                                    );
            // Username
            
            $name = ($row['screen_name'] != '') ? $row['screen_name'] : $row['username'];
            
            $r .= $DSP->table_qcell($style, $name);
                  
            // Date

            $r .= $DSP->td($style).$LOC->set_human_time($row['entry_date']).$DSP->td_c();
            
            // Weblog

            $r .= $DSP->table_qcell($style, $w_array[$row['weblog_id']]);

            // Status
            
            $r .= $DSP->td($style);
            
            $status_name = ($row['status'] == 'open' OR $row['status'] == 'closed') ? $LANG->line($row['status']) : $row['status'];

			if (isset($c_array[$weblog_id.'_'.$row['status']]) AND $c_array[$weblog_id.'_'.$row['status']] != '')
			{
				$color = $c_array[$weblog_id.'_'.$row['status']];
				
				$prefix = (is_array($colors) AND ! array_key_exists(strtolower($color), $colors)) ? '#' : '';
			
				$r .= "<div style='color:".$prefix.$color.";'>".$status_name.'</div>';
			
			}
			else
				$r .= $status_name;


                
            $r .= $DSP->td_c();
            
            // Delete checkbox
            
            $r .= $DSP->table_qcell($style, $DSP->input_checkbox('toggle[]', $row['entry_id']));
                  
            $r .= $DSP->tr_c();
            
        } // End foreach
        
        $r .= $DSP->table_c();
                
        $r .= $DSP->td_c()   
             .$DSP->tr_c()      
             .$DSP->table_c();      


        $r .= $DSP->table('', '0', '', '98%');
        $r .= $DSP->tr().
              $DSP->td();
        
        // Pass the relevant data to the paginate class
        
        $r .=  $DSP->div('crumblinks').
               $DSP->pager(
                            $pageurl,
                            $total_count,
                            $perpage,
                            $rownum,
                            'rownum'
                          ).
              $DSP->div_c().
              $DSP->td_c().
              $DSP->td('defaultRight');
        
        // Delete button
        
        $r .= $DSP->input_submit($LANG->line('delete')).
              $DSP->td_c().
              $DSP->tr_c().
              $DSP->table_c();
              
        
        $r .= $DSP->form_c();
        

        // Set output data        
        
        $DSP->title = $LANG->line('edit').$DSP->crumb_item($LANG->line('edit_weblog_entries'));
        $DSP->crumb = $LANG->line('edit_weblog_entries');
        $DSP->body  = &$r;                                 
    }
    // END
 

 
 
    //-----------------------------------------------------------
    // JavaScript filtering code
    //-----------------------------------------------------------
    // This function writes some JavaScript functions that
    // are used to switch the various pull-down menus in the
    // EDIT page
    //-----------------------------------------------------------

    function filtering_menus()
    { 
        global $DSP, $LANG, $SESS, $FNS, $DB;
     
        // In order to build our filtering options we need to gather 
        // all the weblogs, categories and custom statuses
        
        $blog_array   = array();
        $cat_array    = array();
        $status_array = array();
        
		$allowed_blogs = $FNS->fetch_assigned_weblogs();

		if (count($allowed_blogs) > 0)
		{
			// Fetch weblog titles
			
			$sql = "SELECT blog_title, weblog_id, cat_group, status_group FROM exp_weblogs";
					
			if ( ! $DSP->allowed_group('can_edit_other_entries') || $SESS->userdata['weblog_id'] != 0)
			{
				$sql .= " WHERE (";
			
				foreach ($allowed_blogs as $val)
				{
					$sql .= " weblog_id = '".$val."' OR"; 
				}
				
				$sql = substr($sql, 0, -2).')';
			}
			else
			{
				$sql .= " WHERE is_user_blog = 'n'";
			}
			
			$sql .= " ORDER BY blog_title";
			
			$query = $DB->query($sql);
					
			foreach ($query->result as $row)
			{
				$blog_array[$row['weblog_id']] = array($row['blog_title'], $row['cat_group'], $row['status_group']);
			}        
        }
        
        $sql = "SELECT exp_categories.group_id, exp_categories.cat_id, exp_categories.cat_name 
                FROM exp_categories, exp_category_groups
                WHERE exp_category_groups.group_id = exp_categories.group_id";
        
        if ($SESS->userdata['weblog_id'] != 0)
        {
            $sql .= " AND exp_categories.group_id = '".$query->row['cat_id']."'";
        }
        else
        {
            $sql .= " AND exp_category_groups.is_user_blog = 'n'";
        }
        
        $sql .= " ORDER BY cat_name";
        
        $query = $DB->query($sql);
            
        foreach ($query->result as $row)
        {
            $cat_array[] = array($row['group_id'], $row['cat_id'], $row['cat_name']);
        }
             
            
        $query = $DB->query("SELECT group_id, status FROM exp_statuses ORDER BY status_order");
            
        foreach ($query->result as $row)
        {
            $status_array[]  = array($row['group_id'], $row['status']);
        }
        
        // Build the JavaScript needed for the dynamic pull-down menus
        // We'll use output buffering since we'll need to return it
        // and we break in and out of php
        
        ob_start();
                
?>

<script language="JavaScript">
<!--

var firstcategory = 1;
var firststatus = 1;

function changemenu(index)
{ 

  var categories = new Array();
  var statuses   = new Array();
  
  var i = firstcategory;
  var j = firststatus;
  
  var blogs = document.filterform.weblog_id.options[index].value;
  
    with(document.filterform.cat_id)
    {
        if (blogs == "null")
        {    
            categories[i] = new Option("<?php echo $LANG->line('all'); ?>", ""); i++;
    
            statuses[j] = new Option("<?php echo $LANG->line('all'); ?>", ""); j++;
            statuses[j] = new Option("<?php echo $LANG->line('open'); ?>", "open"); j++;
            statuses[j] = new Option("<?php echo $LANG->line('closed'); ?>", "closed"); j++;
        }
        
       <?php
                        
        foreach ($blog_array as $key => $val)
        {
        
        ?>
        
        if (blogs == "<?php echo $key ?>")
        {
            categories[i] = new Option("<?php echo $LANG->line('all'); ?>", ""); i++; <?php echo "\n";
         
            if (count($cat_array) > 0)
            {
                foreach ($cat_array as $k => $v)
                {
                    if ($v['0'] == $val['1'])
                    {
                    
            // Note: this kludgy indentation is so that the JavaScript will look nice when it's renedered on the page        
            ?>
            categories[i] = new Option("<?php echo $v['2'];?>", "<?php echo $v['1'];?>"); i++; <?php echo "\n";
                    }
                }
            }
              
            ?>
            
            statuses[j] = new Option("<?php echo $LANG->line('all'); ?>", ""); j++;
            <?php
    
            if (count($status_array) > 0)
            {
                foreach ($status_array as $k => $v)
                {
                    if ($v['0'] == $val['2'])
                    {
                    
					$status_name = ($v['1'] == 'closed' OR $v['1'] == 'open') ?  $LANG->line($v['1']) : $v['1'];
            ?> 
            statuses[j] = new Option("<?php echo $status_name; ?>", "<?php echo $v['1']; ?>"); j++; <?php
                    }
                }
            }                    
             
            ?> 

        } // END if blogs
            
        <?php
         
        } // END OUTER FOREACH
         
        ?> 
        with (document.filterform.cat_id)
        {
            for (i = length-1; i >= firstcategory; i--)
                options[i] = null;
            
            for (i = firstcategory; i < categories.length; i++)
                options[i] = categories[i];
            
            options[0].selected = true;
        }
        
        with (document.filterform.status)
        {
            for (i = length-1; i >= firststatus; i--)
                options[i] = null;
            
            for (i = firststatus;i < statuses.length; i++)
                options[i] = statuses[i];
            
            options[0].selected = true;
        }
    }
}

//--></script>
        
<?php
                
        $javascript = ob_get_contents();
        
        ob_end_clean();
        
        return $javascript;
     
    }
    // END 
 
 
 
    //-----------------------------------------------------------
    // View weblog entry
    //-----------------------------------------------------------
    // This function displays an individual weblog entry 
    //-----------------------------------------------------------

    function view_entry()
    {
        global $DSP, $LANG, $FNS, $DB, $IN, $REGX, $SESS;


        if ( ! $entry_id = $IN->GBL('entry_id', 'GET'))
        {
            return false;
        }

        if ( ! $weblog_id = $IN->GBL('weblog_id', 'GET'))
        {
            return false;
        }
        
        // ----------------------------------------
        //  Instantiate Typography class
        // ----------------------------------------        
      
        if ( ! class_exists('Typography'))
        {
            require PATH_CORE.'core.typography'.EXT;
        }
        
        $TYPE = new Typography;
        
        $query = $DB->query("SELECT weblog_html_formatting, weblog_allow_img_urls, weblog_auto_link_urls from exp_weblogs WHERE weblog_id = '$weblog_id'");

        foreach ($query->row as $key => $val)
        {        
            $$key = $val;
        }
        
        $message = '';
        
        if ($U = $IN->GBL('U'))
        {
            $message = ($U == 'new') ? $DSP->qdiv('success', $LANG->line('entry_has_been_added').BR.BR) : $DSP->qdiv('success', $LANG->line('entry_has_been_updated').BR.BR);
        }
                
        $query = $DB->query("SELECT field_group FROM  exp_weblogs WHERE weblog_id = '$weblog_id'");        
        
        if ($query->num_rows == 0)
        {
            return false;
        }
        
        $field_group = $query->row['field_group'];
        
    
        $query = $DB->query("SELECT field_id  FROM exp_weblog_fields WHERE group_id = '$field_group' AND field_type != 'select' ORDER BY field_order");
        
        $fields = array();
        
        foreach ($query->result as $row)
        {
            $fields['field_id_'.$row['field_id']] = 1;
        }        
            
    
        $sql = "SELECT exp_weblog_titles.*, exp_weblog_data.*
                FROM   exp_weblog_titles, exp_weblog_data
                WHERE  exp_weblog_titles.entry_id = '$entry_id'
                AND    exp_weblog_titles.entry_id = exp_weblog_data.entry_id"; 
    
        $result = $DB->query($sql);
            
        if ($result->row['author_id'] != $SESS->userdata['member_id'])
        {    
            if ( ! $DSP->allowed_group('can_view_other_entries'))
            {
                return $DSP->no_access_message();
            }
        }  

		$r = $DSP->div('preview');
		
		$r .= $message;

        if ($result->num_rows > 0)
        {			
			$r .= '<h3>'.stripslashes($result->row['title'])."</h3>".$DSP->nl(2);
					
			foreach ($result->row as $key => $val)
			{
				if (isset($fields[$key]))
				{             
					if (strstr($key, 'field_id'))
					{
						$expl = explode('field_id_', $key);
					
						$r .= $TYPE->parse_type( stripslashes($val), 
												 array(
															'text_format'   => $result->row['field_ft_'.$expl['1']],
															'html_format'   => $weblog_html_formatting,
															'auto_links'    => $weblog_auto_link_urls,
															'allow_img_url' => $weblog_allow_img_urls,
													   )
												);
					}                     
				}            
			} 
		}  
        
        $r .= NL.NL.
              $DSP->qdiv('itemWrapper', $DSP->qdiv('defaultBold', $DSP->anchor(
                            BASE.AMP.'C=edit'.AMP.'M=edit_entry'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id, 
                            $LANG->line('edit_this_entry')
                          ))).
        
            $DSP->div_c();
                
                                
            $DSP->set_return_data( 
                                    $LANG->line('view_entry'),
                                    $r, 
                                    $LANG->line('view_entry')
                                  );    

    }
    // END

 
 
 
    //-----------------------------------------------------------
    //  Delete Entries (confirm)
    //-----------------------------------------------------------
    // Warning message if you try to delete an entry
    //-----------------------------------------------------------

    function delete_entries_confirm()
    { 
        global $IN, $DSP, $LANG;
        
        if ( ! $DSP->allowed_group('can_delete_self_entries') AND
             ! $DSP->allowed_group('can_delete_all_entries'))
        {
            return $DSP->no_access_message();
        }
                
        if ( ! $IN->GBL('toggle', 'POST'))
        {
            return $this->edit_entries();
        }

        $r  = $DSP->form('C=edit'.AMP.'M=delete_entries');
        
        $i = 0;
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'toggle') AND ! is_array($val))
            {
                $r .= $DSP->input_hidden('delete[]', $val);
                
                $i++;
            }        
        }
        
        $r .= $DSP->heading($LANG->line('delete_confirm'));
        $r .= $DSP->div();
        
        if ($i == 1)
            $r .= '<b>'.$LANG->line('delete_entry_confirm').'</b>';
        else
            $r .= '<b>'.$LANG->line('delete_entries_confirm').'</b>';
            
        $r .= $DSP->br(2).
              $DSP->qdiv('alert', $LANG->line('action_can_not_be_undone')).
              $DSP->br().
              $DSP->input_submit($LANG->line('delete')).
              $DSP->div_c().
              $DSP->form_c();

        $DSP->title = $LANG->line('delete_confirm');
        $DSP->crumb = $LANG->line('delete_confirm');         
        $DSP->body  = &$r;
    }
    // END   
    
    
    
    //-----------------------------------------------------------
    //  Delete Entries
    //-----------------------------------------------------------
    // Kill the specified entries
    //-----------------------------------------------------------

    function delete_entries()
    { 
        global $IN, $DSP, $LANG, $SESS, $DB, $FNS, $STAT;
        
        if ( ! $DSP->allowed_group('can_delete_self_entries') AND
             ! $DSP->allowed_group('can_delete_all_entries'))
        {
            return $DSP->no_access_message();
        }
                
        if ( ! $IN->GBL('delete', 'POST'))
        {
            return $this->edit_entries();
        }
        
        $sql = 'SELECT author_id, entry_id FROM exp_weblog_titles WHERE (';
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'delete') AND ! is_array($val))
            {                    
                $sql .= " entry_id = '$val' OR ";
            }        
        }

        $sql = substr($sql, 0, -3).')';
        
        $query = $DB->query($sql);
        
        $authors = array();
        
        foreach ($query->result as $row)
        {
            if ($row['author_id'] == $SESS->userdata['member_id'])
            {
                if ( ! $DSP->allowed_group('can_delete_self_entries'))
                {             
                    return $DSP->no_access_message($LANG->line('unauthorized_to_delete_self'));
                }
            }
            else
            {
                if ( ! $DSP->allowed_group('can_delete_all_entries'))
                {             
                    return $DSP->no_access_message($LANG->line('unauthorized_to_delete_others'));
                }
            }
            
            $authors[$row['entry_id']] = $row['author_id'];
        }
        
        
        foreach ($_POST as $key => $val)
        {        
            if (strstr($key, 'delete') AND ! is_array($val))
            {
            	if ( ! is_numeric($val))
            		continue;
            
                $query = $DB->query("SELECT weblog_id FROM exp_weblog_titles WHERE entry_id = '$val'");
            
                $weblog_id = $query->row['weblog_id'];                
            
                $DB->query("DELETE FROM exp_weblog_titles WHERE entry_id = '$val'");
                $DB->query("DELETE FROM exp_weblog_data WHERE entry_id = '$val'");
                $DB->query("DELETE FROM exp_category_posts WHERE entry_id = '$val'");
                $DB->query("DELETE FROM exp_trackbacks WHERE entry_id = '$val'");

                $query = $DB->query("SELECT total_entries FROM exp_members WHERE member_id = '".$authors[$val]."'");

				$tot = $query->row['total_entries'];
				
				if ($tot > 0)
					$tot -= 1;

                $DB->query("UPDATE exp_members set total_entries = '".$tot."' WHERE member_id = '".$authors[$val]."'");                

                $query = $DB->query("SELECT count(*) AS count FROM exp_comments WHERE status = 'o' AND entry_id = '$val' AND author_id = '".$authors[$val]."'");

                if ($query->row['count'] > 0)
                {
                    $count = $query->row['count'];
                
                    $query = $DB->query("SELECT total_comments FROM exp_members WHERE member_id = '".$authors[$val]."'");

                    $DB->query("UPDATE exp_members set total_comments = '".($query->row['total_comments'] - $count)."' WHERE member_id = '".$authors[$val]."'");                
                }

                $DB->query("DELETE FROM exp_comments WHERE entry_id = '$val'");
                
                // Update statistics
                
                $STAT->update_weblog_stats($weblog_id);
                $STAT->update_comment_stats($weblog_id);
                $STAT->update_trackback_stats($weblog_id);
            }        
        }
                
        
        // ----------------------------------------
        // Return success message
        // ----------------------------------------

        $message = $DSP->div('success').$LANG->line('entries_deleted').$DSP->div_c();

        return $this->edit_entries('', $message);
    }
    // END    
     
     

    //-----------------------------------------------------------
    // File upload form
    //-----------------------------------------------------------

    function file_upload_form()
    {
        global $IN, $DSP, $LANG, $SESS, $DB;
        
        $LANG->fetch_language_file('filebrowser');
                
        $DSP->title = $LANG->line('file_upload');
        
        $DSP->body = $DSP->heading(BR.$LANG->line('file_upload'));
        
        if ($SESS->userdata['group_id'] == 1)
        {
            $sql = "SELECT id, name FROM exp_upload_prefs ORDER BY name";
        }
        else
        {
            
            $sql = "SELECT exp_upload_prefs.id, exp_upload_prefs.name 
                    FROM exp_upload_prefs
                    LEFT JOIN exp_upload_no_access ON exp_upload_prefs.id = exp_upload_no_access.upload_id
                    WHERE exp_upload_no_access.upload_id IS NOT NULL
                    AND   exp_upload_no_access.upload_loc = 'cp'
                    AND   exp_upload_no_access.member_group != '".$SESS->userdata['group_id']."'
                    ORDER BY name";       
        }   
        
        $query = $DB->query($sql);

        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message();
        }        
        

        $DSP->body .= "<form method=\"post\" action=\"".BASE.AMP.'C=publish'.AMP.'M=upload_file'.AMP.'Z=1'."\" enctype=\"multipart/form-data\">\n";
        
        $DSP->body .= $DSP->input_hidden('field_group', $IN->GBL('field_group', 'GET'));

        $DSP->body .= $DSP->qdiv('', BR."<input type=\"file\" name=\"userfile\" size=\"20\" />".BR.BR);
        
        $DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('select_destination_dir'));
        
        $DSP->body .= $DSP->input_select_header('destination');
                                
        foreach ($query->result as $row)
        {
            $DSP->body .= $DSP->input_select_option($row['id'], $row['name']);
        }
        
		$DSP->body .= $DSP->input_select_footer();
                      

        $DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('upload')).$DSP->br(2));

        $DSP->body .= $DSP->form_c();
        
        // -------------------------------
        // File Browser
        // -------------------------------
        
        $DSP->body .= '<hr noshade="noshade" size="1px" width="90%" align="left">';
        
        $DSP->body .= $DSP->heading($LANG->line('file_browser'));
        $DSP->body .= "<form method=\"post\" action=\"".BASE.AMP.'C=publish'.AMP.'M=file_browser'.AMP.'Z=1'."\" enctype=\"multipart/form-data\">\n";
        
        $DSP->body .= $DSP->input_hidden('field_group', $IN->GBL('field_group', 'GET'));
        
        $DSP->body .= $DSP->qdiv('itemWrapper', $LANG->line('select_destination_dir'));
        
        $DSP->body .= $DSP->input_select_header('directory');
                                
        foreach ($query->result as $row)
        {
            $DSP->body .= $DSP->input_select_option($row['id'], $row['name']);
        }
        
		$DSP->body .= $DSP->input_select_footer();
                      

        $DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('view')));

        $DSP->body .= $DSP->form_c();
        $DSP->body .= $DSP->qdiv('itemWrapper', BR.'<div align="center"><a href="JavaScript:window.close();"><b>'.$LANG->line('close_window').'</b></a></div>');
        
        // ---------------------------
        // End File Browser
        // ---------------------------
    }
    // END



    //----------------------------------
    // Upload File
    //----------------------------------

    function upload_file()
    {
        global $IN, $DSP, $DB, $LANG, $SESS;  
        
        $id = $IN->GBL('destination', 'POST');
        $field_group = $IN->GBL('field_group', 'POST');
                
        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($id)."'");
        
        if ($query->num_rows == 0)
        {
            return;
        }
        
        if ($SESS->userdata['group_id'] != 1)
        {
            $safety = $DB->query("SELECT count(*) AS count FROM exp_upload_no_access WHERE upload_id = '".$query->row['id']."' AND upload_loc = 'cp' AND member_group = '".$SESS->userdata['group_id']."'");
        
            if ($safety->row['count'] != 0)
            {
                return $DSP->no_access_message();
            }
        }
            
        require PATH_CORE.'core.upload'.EXT;
        
        $UP = new Upload();
       
        $UP->set_upload_path($query->row['server_path']);
        $UP->set_max_width($query->row['max_width']);
        $UP->set_max_height($query->row['max_height']);
        $UP->set_max_filesize($query->row['max_size']);
        $UP->set_allowed_types(($SESS->userdata['group_id'] == 1) ? 'all' : $query->row['allowed_types']);
                        
        $res = $UP->upload_file();
        
		global $UL; $UL = $UP;
                
        if ($res == 'exists')
        {        
            $this->file_exists_warning();
        }
        elseif ($res == 'success')
        {                
			$this->finalize_uploaded_file(
											array(
													'id'			=> $id,
													'field_group'	=> $field_group,
													'file_name'		=> $UP->file_name,
													'is_image'		=> $UP->is_image,
													'step'			=> 1
												)			
										);			
        }
    }
    // END
    
    
	//----------------------------------
    // File Browser Insert
    //----------------------------------

    function file_inserting()
    {    
    	if (! class_exists('File_Browser'))
        { 
        	require PATH_CP.'cp.filebrowser'.EXT;
        }
        
        $FP = new File_Browser();
        return $FP->file_inserting();

	}
	// END
	
	

	//----------------------------------
    // File Browser
    //----------------------------------

    function file_browser()
    {
        global $IN, $DSP, $DB, $LANG, $SESS;
        
        $LANG->fetch_language_file('filebrowser');
        
        $id = $IN->GBL('directory', 'POST');
        $field_group = $IN->GBL('field_group', 'POST');
        
        $DSP->title = $LANG->line('file_browser');        
        $DSP->body = $DSP->heading(BR.$LANG->line('file_browser'));
                
        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($id)."'");
        
        if ($query->num_rows == 0)
        {
            return;
        }
        
        if ($SESS->userdata['group_id'] != 1)
        {
            $safety = $DB->query("SELECT count(*) AS count FROM exp_upload_no_access WHERE upload_id = '".$query->row['id']."' AND upload_loc = 'cp' AND member_group = '".$SESS->userdata['group_id']."'");
        
            if ($safety->row['count'] != 0)
            {
                return $DSP->no_access_message();
            }
        }
           
        if (! class_exists('File_Browser'))
        { 
        	require PATH_CP.'cp.filebrowser'.EXT;
        }
        
        $FP = new File_Browser();
       
        $FP->set_upload_path($query->row['server_path']);
        $directory_url 	= $query->row['url'];
        $pre_format 	= $query->row['pre_format'];
        $post_format 	= $query->row['post_format'];
        $properties 	= ($query->row['properties']  != '') ? " ".$query->row['properties'] : "";
        $FP->create_filelist();
        
        if (sizeof($FP->filelist) == 0)
        {
        	$DSP->error_message($LANG->line('fp_no_files'));
        }
        
        $r = <<<EOT

<script language="JavaScript">
<!--

var item=new Array();
var width=new Array();
var height=new Array();

EOT;
		$i = 0;
		foreach ($FP->filelist as $file_info)
    	{
            if ($file_info['type'] == 'image')
            {
            	$r .= "item[$i] = '".$file_info['name']."';\n";
            	$r .= "width[$i] = ".$file_info['width'].";\n";
            	$r .= "height[$i] = ".$file_info['height'].";\n";
            	$i++;
            }
        }
	
	$r .= <<<EOT

function showimage()
{

	var loc_w = 350;
	var loc_h = 0;
	for (var i=0; i < document.browser.elements['file[]'].length; i++)
	{
		if (document.browser.elements['file[]'].options[i].selected == true)
		{
			for (var t=0; t < item.length; t++)
			{
				if (item[t] == document.browser.elements['file[]'].options[i].value)
				{
					var loc = '{$directory_url}'+document.browser.elements['file[]'].options[i].value;
					window.open(loc,'Image'+t,'width='+width[t]+',height='+height[t]+',screenX='+loc_w+',screenY='+loc_h+',top='+loc_h+',left='+loc_w+',toolbar=0,status=0,scrollbars=0,location=0,menubar=1,resizable=1');
					loc_w = loc_w + width[t];
					loc_h = loc_h + 100;
				}
			}
		}
	}
return false;
}

function fileplacer()
{
	var done = 'n';
	var file = '';
	var insert = '';
	var field_value = 'field_id_1';
	var pre_format  = '{$pre_format}';
	var post_format = '{$post_format}';
	var properties  = '{$properties}';
	
	for (var i=0; i < document.browser.field.length; i++)
	{
		if (document.browser.field.options[i].selected == true)
		{
			field_value = document.browser.field.options[i].value;
		}
	}
	
	for (var i=0; i < document.browser.elements['file[]'].length; i++)
	{
		if (document.browser.elements['file[]'].options[i].selected == true)
		{
			done = 'n';
			file = document.browser.elements['file[]'].options[i].value;
			
			for (var t=0; t < item.length; t++)
			{
				if (item[t] == document.browser.elements['file[]'].options[i].value)
				{
					file = '<img src="{filedir_{$id}}' + file + '"'+ properties + ' width="'+width[t]+'" height="'+height[t]+'" />';
					var input = pre_format + file + post_format
					opener.document.entryform.elements[field_value].value += input+' ';
					done = 'y';
          		}
			}
			
			if (done == 'n')
			{
				file = '<a href="{filedir_{$id}}' + file + '">'+file+'</a>';
				var input = pre_format + file + post_format
				opener.document.entryform.elements[field_value].value += input;
			}
		}
	}
return false;
}


//-->
</script>

EOT;
        
        $r .= "<form method=\"post\" name='browser' action=\"".BASE.AMP.'C=publish'.AMP.'M=file_browser'."\" enctype=\"multipart/form-data\">\n";
        
        $r .= $DSP->input_hidden('directory', $id);
        
        $r .= $DSP->qdiv('itemTitle', $LANG->line('fb_select_files'));
        
        $r .= $DSP->div('itemWrapper').$DSP->input_select_header('file[]','y',10);
        
        foreach ($FP->filelist as $file_info)
        {
        	$display_name = (isset($file_info['type']) && $file_info['type'] == 'image') ? $file_info['name'].NBS.NBS.NBS : $file_info['name'].'*'.NBS.NBS.NBS;
            $r .= $DSP->input_select_option($file_info['name'], $display_name);
        }
        
		$r .= $DSP->input_select_footer().$DSP->div_c();
		
		
        $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE group_id = '".$field_group."' ORDER BY field_order");
               
        $r .= $DSP->div('itemWrapper').$DSP->qdiv('itemTitle', $LANG->line('fb_select_field'));
               
        $r .= $DSP->input_select_header('field');        
        
        foreach ($query->result as $row)
        {        
            $r .= $DSP->qdiv('', $DSP->input_select_option('field_id_'.$row['field_id'], $row['field_label']));
        }
        
        $r .= $DSP->input_select_footer().$DSP->div_c();
        
        $view_text = (sizeof($FP->filelist) > 1) ? $LANG->line('fb_view_images') : $LANG->line('fb_view_image');
        $insert_text = (sizeof($FP->filelist) > 1) ? $LANG->line('fb_insert_files') : $LANG->line('fb_insert_file');
		
		$r .= $DSP->qdiv('', BR.$DSP->input_submit($view_text,'submit',' onclick="return showimage()";')
			  .NBS.NBS
			  .$DSP->input_submit($insert_text, 'submit',' onclick="return fileplacer();"'));

        $r .= $DSP->form_c();
        
        $r .= $DSP->qdiv('itemWrapper',BR.$LANG->line('fb_non_images'));
        $r .= $DSP->qdiv('itemWrapper', BR.'<div align="center"><a href="JavaScript:window.close();"><b>'.$LANG->line('close_window').'</b></a></div>');
        
		
		$DSP->body = &$r;	
    }
    // END



    //-----------------------------------------------------------
    //  File Exists Warning message
    //-----------------------------------------------------------

    function file_exists_warning()
    {
        global $IN, $DSP, $LANG, $UL;
                
        $field_group = $IN->GBL('field_group', 'POST');
        
        $original_file	= (isset($_FILES['userfile']['name'])) ? $_FILES['userfile']['name'] : $_POST['original_file'];
        $file_name		= (isset($_POST['file_name'])) ? $_POST['file_name'] : $_FILES['userfile']['name'];
        $destination	= (isset($_POST['id'])) ? $_POST['id'] : $_POST['destination'];
        $is_image		= (isset($_POST['is_image'])) ? $_POST['is_image'] : $UL->is_image;
        $width			= (isset($_POST['width'])) ? $_POST['width'] : $UL->width;
        $height			= (isset($_POST['height'])) ? $_POST['height'] : $UL->height;
        $imgtype		= (isset($_POST['imgtype'])) ? $_POST['imgtype'] : $UL->imgtype;


        $DSP->title = $LANG->line('file_upload');

        $DSP->body  = $DSP->heading(BR.$LANG->line('warning'));
        
        $DSP->body .= $DSP->qdiv('highlight', $LANG->line('file_exists').BR.BR);
        $DSP->body .= $DSP->qdiv('', $LANG->line('overwrite_instructions'));

        $DSP->body .= $DSP->form('C=publish'.AMP.'M=replace_file'.AMP.'Z=1');

        $DSP->body .= $DSP->input_text('file_name', $file_name, '40', '100', 'input', '200px');
        
        $DSP->body .= $DSP->input_hidden('original_file', $original_file);
        $DSP->body .= $DSP->input_hidden('temp_file_name', $file_name);
        $DSP->body .= $DSP->input_hidden('field_group', $field_group);
        $DSP->body .= $DSP->input_hidden('is_image', $is_image);
        $DSP->body .= $DSP->input_hidden('width', $width);
        $DSP->body .= $DSP->input_hidden('height', $height);
        $DSP->body .= $DSP->input_hidden('imgtype', $imgtype);
        $DSP->body .= $DSP->input_hidden('id', $destination);

        $DSP->body .= $DSP->qdiv('', BR.$DSP->input_submit($LANG->line('submit')));

        $DSP->body .= $DSP->form_c();
        
        $DSP->body .= $DSP->qdiv('', $DSP->br(4));
    }
    // END



    //-----------------------------------
    //  Overwrite file
    //-----------------------------------

    function replace_file()
    {
        global $IN, $DSP, $LANG, $DB; 
        
        $id          	= $IN->GBL('id', 'POST'); 
        $file_name   	= $IN->GBL('file_name', 'POST');  
        $temp_file_name	= $IN->GBL('temp_file_name', 'POST');  
        $is_image    	= $IN->GBL('is_image', 'POST');
        $field_group 	= $IN->GBL('field_group', 'POST');
                
        require PATH_CORE.'core.upload'.EXT;
        
        $UP = new Upload();
        
		$query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($id)."'");
		$UP->set_upload_path($query->row['server_path']);
        
        if ($temp_file_name != $file_name)
        {
			if (file_exists($query->row['server_path'].$file_name))
			{
				return	$this->file_exists_warning();
        	}
        }
                
        if ($UP->file_overwrite() === TRUE)
        {
			$this->finalize_uploaded_file(
											array(
													'id'			=> $id,
													'field_group'	=> $field_group,
													'file_name'		=> $file_name,
													'is_image'		=> $is_image,
													'step'			=> 1
												)			
										);			
        }
        else
        {
            return $DSP->error_message($LANG->line('file_upload_error'));        
        }
    }

    // END
    


    //-----------------------------------------------------------
    //  Image options form
    //-----------------------------------------------------------

    function image_options_form()
    {
        global $IN, $DSP, $LANG, $DB, $UL;
                
        $id				= (isset($_POST['id'])) ? $_POST['id'] : $_POST['destination'];
        $file_name		= (isset($_POST['file_name'])) ? $_POST['file_name'] : $_FILES['userfile']['name'];
        $is_image		= (isset($_POST['is_image'])) ? $_POST['is_image'] : $UL->is_image;
        $width			= (isset($_POST['width'])) ? $_POST['width'] : $UL->width;
        $height			= (isset($_POST['height'])) ? $_POST['height'] : $UL->height;
        $imgtype		= (isset($_POST['imgtype'])) ? $_POST['imgtype'] : $UL->imgtype;  // 2 = jpg  3 = png
        $field_group 	= $IN->GBL('field_group', 'POST');
        
        
        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($id)."'");
        $max_w = ($query->row['max_width'] == '')  ? '300'  : $query->row['max_width'];
        $max_h = ($query->row['max_height'] == '') ? '250' : $query->row['max_height'];
        
        
        $DSP->title = $LANG->line('file_upload');        
                                     
        ob_start();
        ?>
		<script language="JavaScript">
		
		function changeDimUnits(f, side)
		{
			var unit = (side == "w")? f.width_unit : f.height_unit;
			var orig = (side == "w")? f.width_orig : f.height_orig;
			var curr = (side == "w")? f.width : f.height;
			
			curr.value = (unit.options[unit.selectedIndex].value == "pixels") ? Math.round(orig.value * curr.value / 100.0) : Math.round((curr.value / orig.value) * 100.0);
			
			return;
		}
		
		function changeDimValue(f, side)
		{
			var max 	= (side == "h") ? <?php echo $max_w; ?>	: <?php echo $max_h; ?>;
			var unit	= (side == "w") ? f.width_unit	: f.height_unit;
			var orig	= (side == "w") ? f.width_orig	: f.height_orig;
			var curr	= (side == "w") ? f.width 		: f.height;
			var t_unit	= (side == "h") ? f.width_unit	: f.height_unit;
			var t_orig	= (side == "h") ? f.width_orig	: f.height_orig;
			var t_curr	= (side == "h") ? f.width		: f.height;
			
			var ratio	= (unit.options[unit.selectedIndex].value == "pixels") ? curr.value/orig.value : curr.value/100;
			
			var res = (t_unit.value == "pixels") ? Math.floor(ratio * t_orig.value) : Math.round(ratio * 100);
			
			if (res > max)
			{
				if (f.constrain.checked)
					t_curr.value = t_orig.value;
				
				curr.value	 = (unit.options[unit.selectedIndex].value == "pixels") ? 
								Math.min(curr.value, orig.value) : curr.value = Math.min(curr.value, 100);
			}
			else
			{
				if (f.constrain.checked)
					t_curr.value = res;
			}
						
			return;
		}
								
		</script>
		<?php
		
        $DSP->body .= ob_get_contents();
                
        ob_end_clean(); 
        
        
        $DSP->body .= $DSP->heading(BR.$LANG->line('resize_image'));
        
        $DSP->body .= $DSP->qdiv('', $LANG->line('thumb_instructions'));
                        
        $DSP->body .= $DSP->form('C=publish'.AMP.'M=create_thumb'.AMP.'Z=1', 'fileOptions');
        $DSP->body .= $DSP->input_hidden('field_group', $field_group);
        $DSP->body .= $DSP->input_hidden('is_image', $is_image);
        $DSP->body .= $DSP->input_hidden('imgtype', $imgtype);
        $DSP->body .= $DSP->input_hidden('file_name', $file_name);
        $DSP->body .= $DSP->input_hidden('id', $id);
		$DSP->body .= $DSP->input_hidden('width_orig',  $width);
		$DSP->body .= $DSP->input_hidden('height_orig', $height);
		
		$DSP->body .= BR."<fieldset class='thumb' name=\"thumb_settings\" id=\"thumb_settings\" >";
					
		$DSP->body .= "<legend>".$LANG->line('thumb_settings')."</legend>";
		
		$DSP->body .= $DSP->div('thumbPad');

		$DSP->body .= $DSP->table('', '6', '0', '');
		  
		$DSP->body .= $DSP->table_qrow( 'none', 
								array(
										NBS.$LANG->line('width'),
										$DSP->input_text('width', $width, '4', '4', 'input', '40px', " onchange=\"changeDimValue(this.form, 'w');\" "),
  
										"<select name='width_unit' class='select' onchange=\"changeDimUnits(this.form, 'w')\"  >".
										$DSP->input_select_option('pixels', $LANG->line('pixels'), 1).
										$DSP->input_select_option('percent',$LANG->line('percent')).
										$DSP->input_select_footer()
									  )
								);
			  
		
		$DSP->body .= $DSP->table_qrow( 'none', 
								array(
										$LANG->line('height'),
										$DSP->input_text('height', $height, '4', '4', 'input', '40px', " onchange=\"changeDimValue(this.form, 'h');\" "),
									  
										"<select name='height_unit' class='select' onchange=\"changeDimUnits(this.form, 'h')\"  >".
										$DSP->input_select_option('pixels', $LANG->line('pixels'), 1).
										$DSP->input_select_option('percent', $LANG->line('percent')).
										$DSP->input_select_footer()
									  )
								);		
		
		$DSP->body .= $DSP->tr();
		$DSP->body .= $DSP->td('none', '', '3');
		
		$DSP->body .= $DSP->qdiv('itemWrapper', $DSP->input_checkbox('constrain', '1', 1).NBS.$LANG->line('constrain_proportions'));
		
		$DSP->body .= $DSP->qdiv('', BR.$DSP->input_radio('source', 'copy', 1).NBS.$LANG->line('create_thumb_copy'));
		$DSP->body .= $DSP->qdiv('', $DSP->input_radio('source', 'orig').NBS.$LANG->line('resize_original'));
		
		$DSP->body .= $DSP->td_c();
		$DSP->body .= $DSP->tr_c();
		$DSP->body .= $DSP->table_c();
				
		$DSP->body .= $DSP->div_c();
		
		$DSP->body .= "</fieldset>";	
		
        $DSP->body .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('resize_image')));
				        
        $DSP->body .= $DSP->qdiv('itemWrapper', BR.'<div align="center"><a href="JavaScript:window.close();"><b>'.$LANG->line('close_window').'</b></a></div>');

        $DSP->body .= $DSP->form_c();        
    }
    // END



    //-----------------------------------
    //  Create image thumbnail
    //-----------------------------------

    function create_thumb()
    {
        global $IN, $DSP, $LANG, $PREFS, $LANG, $DB; 
        
        
        foreach ($_POST as $key => $val)
        {
        	$$key = $val;
        }
        
        if ($width == $width_orig AND $height == $height_orig)
        {
			return $DSP->error_message($LANG->line('image_size_not_different'));
        }
                
        if ($width != $width_orig OR $height_orig != $height)
        {
			$query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($id)."'");
	
			$props = array(
							'path'		=> $query->row['server_path'],
							'filename'	=> $file_name,
							'type'		=> $imgtype,
							'quality'	=> '90',
							's_width'	=> $width_orig,
							't_width'	=> $width,
							's_height'	=> $height_orig,
							't_height'	=> $height
							);        
					
			require PATH_CORE.'core.upload'.EXT;
			
			$UP = new Upload();
			
			if ($source == 'orig')
			{
				$UP->thumb_prefix = '';
			}
			else
			{				
				$name = substr($file_name, 0, strpos($file_name, "."));
				$ext  = substr($file_name, strpos($file_name, "."));
								
				$file_name = $name.$UP->thumb_prefix.$ext; 
			}
			
			$protocol = 'image_resize_'.$PREFS->ini('image_resize_protocol');
			
			if (ereg("gd2$", $protocol))
			{
				$protocol = substr($protocol, 0, -1);
			}
			
			if ( ! $UP->$protocol($props))
			{
				return;
			}
		}
		
		$this->finalize_uploaded_file(
										array(
												'id'			=> $id,
												'field_group'	=> $field_group,
												'file_name'		=> $file_name,
												'is_image'		=> 1,
												'step'			=> 2,
												'source'		=> $source
											)			
									);			
	}
	// END



    //---------------------------------------
    //  Finalize Uploaded File
    //---------------------------------------

    function finalize_uploaded_file($data)
    {
        global $IN, $DSP, $LANG, $PREFS, $DB;
        
        // Fetch upload preferences
                
        $query = $DB->query("SELECT * FROM exp_upload_prefs WHERE id = '".$DB->escape_str($data['id'])."'");  
       
        $properties = ($query->row['properties']  != '') ? " ".$query->row['properties'] : "";
                                
        $props = $query->row['pre_format'];
                        
        if ($data['is_image'] == 1)
        {
            $props .= "<img src=\"{filedir_".$data['id']."}".$data['file_name']."\"".$properties;
        
            if (function_exists('getimagesize')) 
            {
                $imgdim = @getimagesize($query->row['server_path'].$data['file_name']);
                
                if (is_array($imgdim)) 
                {
                    $props .= " width=\"".$imgdim['0']."\" height=\"".$imgdim['1']."\"";
                }
            }
            
            $props .= " />";
        }
        else
        {
            $props .= "<a href=\"{filedir_".$data['id']."}".$data['file_name']."\">".$data['file_name']."</a>";
        }
        
        $props .= $query->row['post_format'];
        
        
        $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE group_id = '".$data['field_group']."' ORDER BY field_order");
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('no_entry_fields'));        
        }
                
        ob_start();
        
        ?>     
        
        <script language="javascript" type="text/javascript"> 
        <!--

        function fileplacer() 
        {
            var file = '<?php echo $props; ?>';
        
        <?php
        
        $n = 0;
        
        foreach ($query->result as $row)
        {
        ?>
            if (document.upload.which[<?php echo $n; ?>].checked) 
            {
                opener.document.entryform.field_id_<?php echo $row['field_id']; ?>.value += file;
            }
        <?php
            
            $n++;
         }
         ?>
        }
        
        //-->
        </script>
        
        <?php

        $javascript = ob_get_contents();
        
        ob_end_clean();
        
        
        $DSP->title =& $LANG->line('file_upload');
        
        $DSP->body = $javascript;
        
        
        if ($data['step'] == 1)
        {
			$DSP->body .= $DSP->qdiv('success', BR.$LANG->line('you_have_uploaded'));
			$DSP->body .= $DSP->qdiv('itemWrapper', '<b>'.$data['file_name'].'</b>');
			$DSP->body .= $DSP->qdiv('', BR.$LANG->line('choose_a_destination').BR.BR);
        }
        else
        {
        	if (isset($data['source']) AND $data['source'] == 'copy')
        		$DSP->body .= $DSP->qdiv('success', BR.$LANG->line('thumbnail_created'));
        	else
        		$DSP->body .= $DSP->qdiv('success', BR.$LANG->line('image_resized'));
			
			$DSP->body .= $DSP->qdiv('', BR.$LANG->line('choose_a_destination_for_thumb').BR.BR);
        }
        	
		if ($data['step'] == 1 AND $data['is_image'] == 1 AND $PREFS->ini('enable_image_resizing') == 'y')
		{
			$DSP->body .= "<form name='upload' method='post' action='".BASE.AMP.'C=publish'.AMP.'M=image_options'.AMP.'Z=1'."' >";  

			global $UL;
						
			$width		= (isset($_POST['width'])) ? $_POST['width'] : $UL->width;
			$height		= (isset($_POST['height'])) ? $_POST['height'] : $UL->height;
			$imgtype	= (isset($_POST['imgtype'])) ? $_POST['imgtype'] : $UL->imgtype;  // 2 = jpg  3 = png
			
			$DSP->body .= $DSP->input_hidden('id', $data['id']);
			$DSP->body .= $DSP->input_hidden('field_group', $data['field_group']);
			$DSP->body .= $DSP->input_hidden('is_image', $data['is_image']);
			$DSP->body .= $DSP->input_hidden('file_name', $data['file_name']);
			$DSP->body .= $DSP->input_hidden('width', $width);
			$DSP->body .= $DSP->input_hidden('height', $height);
			$DSP->body .= $DSP->input_hidden('imgtype', $imgtype);
		}
		else
		{
			$DSP->body .= "<form name='upload' method='post' action='JavaScript:window.close()' >";  
		}
	        
        
        $i = 1;
        
        foreach ($query->result as $row)
        {
            $selected = ($i == 1) ? 1 : 0;
        
            $DSP->body .= $DSP->qdiv('', $DSP->input_radio('which', 'field_id_'.$row['field_id'],  $selected).NBS.$row['field_label']);
        
            $i++;
        }
        
		$DSP->body .= $DSP->qdiv('', $DSP->input_radio('which', 'null').NBS.$LANG->line('do_not_place_file'));
        
        $DSP->body .= $DSP->qdiv('', BR."<input type='submit'  value='".$LANG->line('submit')."' onclick='fileplacer();' class='submit' />");
        $DSP->body .= $DSP->form_c();
    }
    // END
  
  
  
    //-----------------------------------------------------------
    //  Emoticons
    //-----------------------------------------------------------

    function emoticons()
    {
        global $IN, $DSP, $LANG, $PREFS, $DB;
        
        
        if ( ! $field_group = $IN->GBL('field_group', 'GET'))
        {
            return;
        }
        
        
        if ( ! is_file(PATH_MOD.'emoticon/emoticons'.EXT))
        {
            return $DSP->error_message($LANG->line('no_emoticons'));        
        }
        else
        {
            require PATH_MOD.'emoticon/emoticons'.EXT;
        }
        
        if ( ! is_array($smileys))
        {
            return;
        }
        
        
        $path = $PREFS->ini('emoticon_path', 1);
        
        $query = $DB->query("SELECT field_id, field_label FROM exp_weblog_fields WHERE group_id = '".$field_group."' ORDER BY field_order");
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('no_entry_fields'));        
        }
                
        ob_start();
        
        ?>     
        
        <script language="javascript" type="text/javascript"> 
        <!--

        function add_smiley(smiley)
        {
            var  form = document.forms[0];  
        <?php
        
        $n = 0;
        
        foreach ($query->result as $row)
        {
        ?>

            if (form.which[<?php echo $n; ?>].checked) 
            {
                opener.document.entryform.field_id_<?php echo $row['field_id']; ?>.value += " " + smiley + " ";
                window.close();
                opener.window.document.entryform.field_id_<?php echo $row['field_id']; ?>.focus();
            }
        <?php
            
            $n++;
         }
         ?>
        }
        
        //-->
        </script>
        
        <?php

        $javascript = ob_get_contents();
        
        ob_end_clean();
        
        
        $r = $javascript;
        
        $r .= $DSP->heading($LANG->line('emoticons'));
        
        $r .= $DSP->qdiv('', BR.$LANG->line('choose_a_destination_for_emoticon').BR.BR);
        
        $r .= "<form name='upload' method='post' action='' >";        
        
        $i = 1;
        
        foreach ($query->result as $row)
        {
            $selected = ($i == 1) ? 1 : 0;
        
            $r .= $DSP->qdiv('', $DSP->input_radio('which', 'field_id_'.$row['field_id'],  $selected).NBS.$row['field_label']);
        
            $i++;
        }
        
        $r .= $DSP->qdiv('', BR.$LANG->line('click_emoticon').BR.BR);
        
        
        $r .= $DSP->table('', '0', '10', '100%');
        
        $i = 1;
        
        foreach ($smileys as $key => $val)
        {
            if ($i == 1)
            {
                $r .= "<tr>\n";                
            }
            
            $r .= "<td><a href=\"#\" onClick=\"return add_smiley('".$key."');\"><img src=\"".$path.$smileys[$key]['0']."\" width=\"".$smileys[$key]['1']."\" height=\"".$smileys[$key]['2']."\" alt=\"".$smileys[$key]['3']."\" border=\"0\" /></a></td>\n";

            if ($i == 8)
            {
                $r .= "</tr>\n";                
                
                $i = 1;
            }
            else
            {
                $i++;
            }      
        }
        
        $r = rtrim($r);
                
        if (substr($r, -5) != "</tr>")
        {
            $r .= "</tr>\n";
        }
        
        $r .= $DSP->table_c();
        
        $r .= "</form>";
        
        $DSP->body  =& $r;   
        $DSP->title =& $LANG->line('file_upload');
    }
    // END
  
  


    //---------------------------------------
    // View comments and trackback
    //---------------------------------------

    function view_comments($weblog_id = '', $entry_id = '', $message = '')
    {
        global $IN, $DSP, $SESS, $DB, $DSP, $FNS, $LANG, $LOC, $PREFS;
    
		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';
    
        //---------------------------------------
        // Assign page header and breadcrumb
        //---------------------------------------
        
        $DSP->title = $LANG->line('comments');
        $DSP->crumb = $LANG->line('comments');
        
        $r = $DSP->heading($LANG->line('comments'));


        $validate = ($IN->GBL('validate', 'GET') == 1) ? TRUE : FALSE;
        
    	if ($validate)
    	{    	
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			}     

			$sql = "SELECT exp_comments.*
					FROM exp_comments, exp_weblogs
					WHERE exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
			
			$sql .= "ORDER BY comment_date";
			
			$query = $DB->query($sql);
						
			if ($query->num_rows == 0)
			{
				if ($IN->GBL('U', 'GET') == 1)
				{
					$r .= $DSP->qdiv('success',$LANG->line('status_changed'));
				}
				else
				{
					$r .= $DSP->qdiv('', $LANG->line('no_comments_or_trackbacks'));
				}
        		return $DSP->body = $r;
			}
			        						
			$comment_text_formatting = 'xhtml';
			$comment_html_formatting = 'safe';
			$comment_allow_img_urls  = 'n';
			$comment_auto_link_urls	 = 'y';
    	}
    	else
    	{
			if ($entry_id == '')
			{
				if ( ! $entry_id = $IN->GBL('entry_id', 'GET'))
				{
					return false;
				}
			}
			
			if ($weblog_id == '')
			{
				if ( ! $weblog_id = $IN->GBL('weblog_id', 'GET'))
				{
					return false;
				}
			}
			
			if (USER_BLOG !== FALSE)
			{        
				if ($weblog_id != UB_BLOG_ID)
				{
					return false;
				}
			}						
			
			//---------------------------------------
			// Fetch Author ID and verify privs
			//---------------------------------------
		
			$query = $DB->query("SELECT author_id FROM exp_weblog_titles WHERE entry_id = '$entry_id'");
			
			if ($query->num_rows == 0)
			{
				return $DSP->error_message($LANG->line('no_weblog_exits'));
			}
			
			if ($query->row['author_id'] != $SESS->userdata['member_id'])
			{    
				if ( ! $DSP->allowed_group('can_view_other_comments'))
				{
					return $DSP->no_access_message();
				}
			}
			
			//---------------------------------------
			// Fetch comment display preferences
			//---------------------------------------
		
			$query = $DB->query("SELECT comment_text_formatting, 
										comment_html_formatting,
										comment_allow_img_urls,
										comment_auto_link_urls
										FROM exp_weblogs 
										WHERE weblog_id = '$weblog_id'");
			
			
			if ($query->num_rows == 0)
			{
				return $DSP->error_message($LANG->line('no_weblog_exits'));
			}
			
			foreach ($query->row as $key => $val)
			{
				$$key = $val;
			}
			   
	   
			//---------------------------------------
			// Fetch comments and trackbacks
			//---------------------------------------
			   
			$sql = "SELECT exp_comments.*,  exp_trackbacks.*
					FROM exp_temp_union AS entry
					LEFT JOIN exp_comments   ON (entry.num = '0' AND exp_comments.entry_id   = '$entry_id')
					LEFT JOIN exp_trackbacks ON (entry.num = '1' AND exp_trackbacks.entry_id = '$entry_id')
					WHERE entry.num < 2
					ORDER BY COALESCE(exp_comments.comment_date, exp_trackbacks.trackback_date)"; 
					
			$query = $DB->query($sql);
		}       
                      
        $r .= $message;

        $comment_flag = FALSE;
        
        //---------------------------------------
        // Instantiate the Typography class
        //---------------------------------------

        if ( ! class_exists('Typography'))
        {
            require PATH_CORE.'core.typography'.EXT;
        }
        
        $TYPE = new Typography;
        
        
        $val = ($validate) ? AMP.'validate=1' : '';
        
        
        //-------------------------------
        // Show comments
        //-------------------------------
   
        foreach ($query->result as $row)
        {
	
			if ($validate) $row['trackback_id'] = NULL;
        
            //-------------------------------
            // Show Comments
            //-------------------------------
            
            if ($row['comment_id'] !== NULL)
            {
                $comment_flag = TRUE;
            
                $r .= $DSP->div('comments');     
                    
                    
                $r .= $DSP->table('', '6', '0', '100%').
                      $DSP->tr().
                      $DSP->td('cmtRightBorder', '30%', '', '', 'top');
            
        
        
                $r .=  $DSP->table('', '0', '0', '100%')
                      .$DSP->tr()
                      .$DSP->td('', '56px', '', '', 'top')
                      .$DSP->qdiv('cmtCredits', $LANG->line('posted_by'))
                      .$DSP->td_c()
                      .$DSP->td('', '', '', '', 'top')
                      .$DSP->qdiv('cmtCreditVal', $row['name'])
                      .$DSP->td_c();
                
                if ($row['location'] != '')
                {
                    $r .= $DSP->tr_c()
                         .$DSP->tr()
                         .$DSP->td('', '56px', '', '', 'top')
                         .$DSP->qdiv('cmtCredits', $LANG->line('located_in'))
                         .$DSP->td_c()
                         .$DSP->td('', '', '', '', 'top')
                         .$DSP->qdiv('cmtCreditVal', $row['location'])
                         .$DSP->td_c();
                }
                
                if ($row['email'] != '')
                {
                    $r .= $DSP->tr_c()
                         .$DSP->tr()
                         .$DSP->td('', '56px', '', '', 'top')
                         .$DSP->qdiv('cmtCredits', $LANG->line('comment_email'))
                         .$DSP->td_c()
                         .$DSP->td('', '', '', '', 'top')
                         .$DSP->qdiv('cmtCreditVal', $DSP->mailto($row['email'], $row['email']))
                         .$DSP->td_c();
                }

                if ($row['url'] != '')
                {
                    $r .= $DSP->tr_c()
                         .$DSP->tr()
                         .$DSP->td('', '56px', '', '', 'top')
                         .$DSP->qdiv('cmtCredits', $LANG->line('comment_url'))
                         .$DSP->td_c()
                         .$DSP->td('', '', '', '', 'top')
                         .$DSP->qdiv('cmtCreditVal', $DSP->anchor($FNS->fetch_site_index().$qm.'URL='.$row['url'], $row['url']))
                         .$DSP->td_c();
                }

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_date'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', $LOC->set_human_time($row['comment_date']))
                     .$DSP->td_c();

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_ip'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', $row['ip_address'])
                     .$DSP->td_c();
                     
                     
                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('status').':')
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', ($row['status'] == 'o') ? $LANG->line('open') : $DSP->qspan('highlight', $LANG->line('closed')))
                     .$DSP->td_c();
                     
                 if ($row['status'] == 'o')
                 {
                 	$status = 'close';
                 	$status_label = $LANG->line('close');
                 }
                 else
                 {
                 	$status = 'open';    
                 	$status_label = $LANG->line('open');
                 }
                     
                 $status_change = $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=change_status'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id.AMP.'comment_id='.$row['comment_id'].AMP.'status='.$status.$val, $status_label);

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_action'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qspan('cmtCreditVal', $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=edit_comment'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id.AMP.'comment_id='.$row['comment_id'].$val, $LANG->line('edit')).NBS.NBS.'|'.NBS.NBS.$status_change.NBS.NBS.'|'.NBS.NBS.$DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=del_comment_conf'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id.AMP.'comment_id='.$row['comment_id'].$val, $LANG->line('delete')))
                     .$DSP->td_c()
                     .$DSP->tr_c()
                     .$DSP->table_c();
                
                $r .= $DSP->td_c().
                      $DSP->td('cmtLeftPad', '70%', '', '', 'top');
                
                $r .= $TYPE->parse_type( $row['comment'], 
                                               array(
                                                        'text_format'   => $comment_text_formatting,
                                                        'html_format'   => $comment_html_formatting,
                                                        'auto_links'    => $comment_auto_link_urls,
                                                        'allow_img_url' => $comment_allow_img_urls
                                                    )
                                            );                        
                $r .= $DSP->td_c().
                      $DSP->tr_c().
                      $DSP->table_c();
                      
                $r .= $DSP->div_c();                
            }            
        
            //-------------------------------
            // Show Trackbacks
            //-------------------------------
            
            if ($row['trackback_id'] !== NULL)
            {   
                $comment_flag = TRUE;
                     
                $r .= $DSP->div('comments');     
                    
                $r .= $DSP->table('', '6', '0', '100%').
                      $DSP->tr().
                      $DSP->td('cmtRightBorder', '240px', '', '', 'top');        
        
                $r .=  $DSP->table('', '0', '0', '240px')
                      .$DSP->tr()
                      .$DSP->td('', '56px', '', '', 'top')
                      .$DSP->qdiv('cmtCredits', $LANG->line('weblog_name'))
                      .$DSP->td_c()
                      .$DSP->td('', '', '', '', 'top')
                      .$DSP->qdiv('cmtCreditVal', $row['weblog_name'])
                      .$DSP->td_c();

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_url'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', $DSP->anchor($FNS->fetch_site_index().$qm.'URL='.$row['trackback_url'], $row['trackback_url']))
                     .$DSP->td_c();

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_date'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', $LOC->set_human_time($row['trackback_date']))
                     .$DSP->td_c();

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_ip'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qdiv('cmtCreditVal', $row['trackback_ip'])
                     .$DSP->td_c();

                $r .= $DSP->tr_c()
                     .$DSP->tr()
                     .$DSP->td('', '56px', '', '', 'top')
                     .$DSP->qdiv('cmtCredits', $LANG->line('comment_action'))
                     .$DSP->td_c()
                     .$DSP->td('', '', '', '', 'top')
                     .$DSP->qspan('cmtCreditVal', $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=del_comment_conf'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id.AMP.'trackback_id='.$row['trackback_id'], $LANG->line('delete_trackback')))
                     .$DSP->td_c()
                     .$DSP->tr_c()
                     .$DSP->table_c();
                
                
                $r .= $DSP->td_c().
                      $DSP->td('cmtLeftPad', '', '', '', 'top');
                      
                      
                $r .= $DSP->heading($row['title'], 5);
                
                $r .= $TYPE->parse_type( $row['content'], 
                                               array(
                                                        'text_format'   => $comment_text_formatting,
                                                        'html_format'   => $comment_html_formatting,
                                                        'auto_links'    => $comment_auto_link_urls,
                                                        'allow_img_url' => $comment_allow_img_urls
                                                    )
                                            );                        
                $r .= $DSP->td_c().
                      $DSP->tr_c().
                      $DSP->table_c();
                      
                      
                $r .= $DSP->div_c();
            }
        
        }
        // END FOREACH
        
        // No comment message
        
        if ($comment_flag == FALSE)
        {
            $r .= $DSP->qdiv('', $LANG->line('no_comments_or_trackbacks'));
        }        
                
        $DSP->body = $r;
    }
    // END







    //-----------------------------------------
    // Edit comments form
    //-----------------------------------------

    function edit_comment_form()
    {
        global $IN, $DB, $DSP, $LANG, $SESS;

        $comment_id = $IN->GBL('comment_id');
        $weblog_id  = $IN->GBL('weblog_id');
        $entry_id   = $IN->GBL('entry_id');
        
        
        if ($comment_id == FALSE)
        {
            return $DSP->no_access_message();
        }        
        
        $validate = 0;
        if ($IN->GBL('validate') == 1)
        {
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			} 
			
			$sql = "SELECT exp_comments.*
					FROM exp_comments, exp_weblogs
					WHERE comment_id = '$comment_id'
					AND exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
															
			$query = $DB->query($sql);			
			
        	$validate = 1;
        }
        else
        {
			if ( ! $DSP->allowed_group('can_edit_all_comments'))
			{
				if ( ! $DSP->allowed_group('can_edit_own_comments'))
				{     
					return $DSP->no_access_message();
				}
				else
				{
					$sql = "SELECT exp_weblog_titles.author_id 
							FROM   exp_weblog_titles, exp_comments
							WHERE  exp_weblog_titles.entry_id = exp_comments.entry_id
							AND    exp_comments.comment_id = '$comment_id'";
	
					$query = $DB->query($sql);
					
					if ($query->row['author_id'] != $SESS->userdata['member_id'])
					{
						return $DSP->no_access_message();
					}
				}
			}
			
        	$query = $DB->query("SELECT * FROM exp_comments WHERE comment_id = '$comment_id'");
		}        
        
        if ($query->num_rows == 0)
        {
        	return false;
        }
        
        foreach ($query->row as $key => $val)
        {
        	$$key = $val;
        }
        
        
        $r  = $DSP->form('C=edit'.AMP.'M=update_comment');
        $r .= $DSP->input_hidden('comment_id', $comment_id);
        $r .= $DSP->input_hidden('author_id',  $author_id);
        $r .= $DSP->input_hidden('weblog_id',  $weblog_id);
        $r .= $DSP->input_hidden('entry_id',   $entry_id);
        $r .= $DSP->input_hidden('validate',   $validate);
                
        $r .= $DSP->heading($LANG->line('edit_comment'));
        
        if ($author_id == 0)
        {
			$r .= $DSP->itemgroup(
									$DSP->required().NBS.$LANG->line('name', 'name'),
									$DSP->input_text('name', $name, '40', '100', 'input', '300px')
								  );
												
			$r .= $DSP->itemgroup(
									$DSP->required().NBS.$LANG->line('email', 'email'),
									$DSP->input_text('email', $email, '35', '100', 'input', '300px')
								  );
		 
	
			$r .= $DSP->itemgroup(
									$LANG->line('url', 'url'),
									$DSP->input_text('url', $url, '40', '100', 'input', '300px')
								  );
								  
			$r .= $DSP->itemgroup(
									$LANG->line('location', 'location'),
									$DSP->input_text('location', $location, '40', '100', 'input', '300px')
								  );
         }   
         
			$r .= $DSP->itemgroup(
									$LANG->line('comment', 'comment'),
									$DSP->input_textarea('comment', $comment, '14', 'textarea', '99%')
								  );
        
        // Submit button   
        
        $r .= $DSP->itemgroup( '',
                                $DSP->required(1).$DSP->br(2).$DSP->input_submit($LANG->line('submit'))
                              );
        $r .= $DSP->form_c();

        $DSP->title = $LANG->line('edit_comment');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id, $LANG->line('comments')).$DSP->crumb_item($LANG->line('edit_comment'));
        $DSP->body  = &$r;
    }
    // END
    
    
    


    //-----------------------------------------
    // Update comment
    //-----------------------------------------

    function update_comment()
    {
        global $IN, $DSP, $DB, $LANG, $REGX, $SESS, $FNS;
    
        $comment_id = $IN->GBL('comment_id', 'POST');
        $author_id  = $IN->GBL('author_id', 'POST');
        $weblog_id  = $IN->GBL('weblog_id', 'POST');
        $entry_id   = $IN->GBL('entry_id', 'POST');                        

        if ($comment_id == FALSE)
        {
            return $DSP->no_access_message();
        }    

        if ($author_id === FALSE)
        {
            return $DSP->no_access_message();
        }    
        
        if ($IN->GBL('validate') == 1)
        {
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			}    
			
			$sql = "SELECT COUNT(*) AS count 
					FROM exp_comments, exp_weblogs
					WHERE comment_id = '$comment_id'
					AND exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
															
			$query = $DB->query($sql);			
	
			if ($query->row['count'] == 0)
			{
				return $DSP->no_access_message();
			}			
        }
        else
        {        
			if ( ! $DSP->allowed_group('can_edit_all_comments'))
			{
				if ( ! $DSP->allowed_group('can_edit_own_comments'))
				{     
					return $DSP->no_access_message();
				}
				else
				{
					$sql = "SELECT exp_weblog_titles.author_id 
							FROM   exp_weblog_titles, exp_comments
							WHERE  exp_weblog_titles.entry_id = exp_comments.entry_id
							AND    exp_comments.comment_id = '$comment_id'";
	
					$query = $DB->query($sql);
					
					if ($query->row['author_id'] != $SESS->userdata['member_id'])
					{
						return $DSP->no_access_message();
					}
				}
			}
        }
        
        //---------------------------------------
        // Fetch comment display preferences
        //---------------------------------------
    
        $query = $DB->query("SELECT exp_weblogs.comment_require_email
                                    FROM exp_weblogs, exp_comments
                                    WHERE exp_comments.weblog_id = exp_weblogs.weblog_id
                                    AND exp_comments.comment_id = '$comment_id'");
        
        
        if ($query->num_rows == 0)
        {
            return $DSP->error_message($LANG->line('no_weblog_exits'));
        }
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }


        // -------------------------------------
        //  Error checks
        // -------------------------------------
		
		$error = array();

		if ($author_id == 0)
		{
			// Fetch language file
			
			$LANG->fetch_language_file('myaccount');
			
            if ($comment_require_email == 'y')
            {
				// -------------------------------------
				//  Is email missing?
				// -------------------------------------
				
				if ($_POST['email'] == '')
				{
					$error[] = $LANG->line('missing_email');
				}
				
				// -------------------------------------
				//  Is email valid?
				// -------------------------------------
				
				if ( ! $REGX->valid_email($_POST['email']))
				{
					$error[] = $LANG->line('invalid_email_address');
				}
				
				
				// -------------------------------------
				//  Is email banned?
				// -------------------------------------
				
				if ($SESS->ban_check('email', $_POST['email']))
				{
					$error[] = $LANG->line('banned_email');
				}
			}
		}

		// -------------------------------------
		//  Is comment missing?
		// -------------------------------------
		
		if ($_POST['comment'] == '')
		{
			$error[] = $LANG->line('missing_comment');
		}

        
        // -------------------------------------
        //  Display error is there are any
        // -------------------------------------

         if (count($error) > 0)
         {
            $msg = '';
            
            foreach($error as $val)
            {
                $msg .= $val.'<br />';  
            }
            
            return $DSP->error_message($msg);
         }

		// Build query
		
		if ($author_id == 0)
		{
			$data = array(
							'name'		=> $_POST['name'],	
							'email'		=> $_POST['email'],	
							'url'		=> $_POST['url'],	
							'location'	=> $_POST['location'],	
							'comment'	=> $_POST['comment']	
						 );
		}
		else
		{
		
			$data = array(
							'comment'	=> $_POST['comment']	
						 );
		}

		$sql = $DB->update_string('exp_comments', $data, "comment_id = '$comment_id'");
			
		$DB->query($sql); 
		
		        
		if ($IN->GBL('validate', 'POST') == 1)
		{
			$FNS->redirect(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'validate=1');
			exit;
		}		

        $message = $DSP->qdiv('success', $LANG->line('comment_updated'));

        $this->view_comments($weblog_id, $entry_id, $message);
	}
	// END


    //-----------------------------------------
    // Delete comment/trackback confirmation
    //-----------------------------------------

    function delete_comment_confirm()
    {
        global $IN, $DSP, $DB, $LANG, $SESS;
       
        $comment_id   = $IN->GBL('comment_id');
        $trackback_id = $IN->GBL('trackback_id');
        $weblog_id    = $IN->GBL('weblog_id');
        $entry_id     = $IN->GBL('entry_id');
        
        
        if ($trackback_id == FALSE AND $comment_id == FALSE)
        {
            return $DSP->no_access_message();
        }     
        
        if ($IN->GBL('validate') == 1)
        {
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			}    
						
			$sql = "SELECT COUNT(*) AS count 
					FROM exp_comments, exp_weblogs
					WHERE comment_id = '$comment_id'
					AND exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
						
			$query = $DB->query($sql);			
	
			if ($query->row['count'] == 0)
			{
				return $DSP->no_access_message();
			}			
        }
		else
		{
			if ( ! $DSP->allowed_group('can_delete_all_comments'))
			{
				if ( ! $DSP->allowed_group('can_delete_own_comments'))
				{     
					return $DSP->no_access_message();
				}
				else
				{
					if ($comment_id != FALSE)
					{
						$sql = "SELECT exp_weblog_titles.author_id 
								FROM   exp_weblog_titles, exp_comments
								WHERE  exp_weblog_titles.entry_id = exp_comments.entry_id
								AND    exp_comments.comment_id = '$comment_id'";
					}
					else
					{
						$sql = "SELECT exp_weblog_titles.author_id 
								FROM   exp_weblog_titles, exp_trackbacks
								WHERE  exp_weblog_titles.entry_id = exp_trackbacks.entry_id
								AND    exp_trackbacks.trackback_id = '$trackback_id'";
					}
					
					$query = $DB->query($sql);
					
					if ($query->row['author_id'] != $SESS->userdata['member_id'])
					{
						return $DSP->no_access_message();
					}
				}
			}
   		}     
   
        $r  = $DSP->form('C=edit'.AMP.'M=del_comment');
        
        if ($comment_id != FALSE)
        {
            $r .= $DSP->input_hidden('comment_id', $comment_id);
        }
        else
        {
            $r .= $DSP->input_hidden('trackback_id', $trackback_id);
        }
        
        $validate = ($IN->GBL('validate') == 1) ? 1 : 0;
        
		$r .= $DSP->input_hidden('validate', $validate);        
        
                
        $r .= $DSP->heading($LANG->line('delete_confirm'));
        $r .= $DSP->div();
        
        if ($comment_id != FALSE)
        {
            $r .= '<b>'.$LANG->line('delete_comment_confirm').'</b>';
        }
        else
        {
            $r .= '<b>'.$LANG->line('delete_trackback_confirm').'</b>';
        }
        
        $r .= $DSP->br(2).
              $DSP->qdiv('alert', $LANG->line('action_can_not_be_undone')).
              $DSP->br().
              $DSP->input_submit($LANG->line('delete')).
              $DSP->div_c().
              $DSP->form_c();

        $DSP->title = $LANG->line('delete_confirm');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'weblog_id='.$weblog_id.AMP.'entry_id='.$entry_id, $LANG->line('comments')).$DSP->crumb_item($LANG->line('edit_comment'));
        $DSP->body  = &$r;
    }
    // END


    //-----------------------------------------
    // Change Comment Status
    //-----------------------------------------

    function change_comment_status()
    {
        global $IN, $DSP, $DB, $LANG, $PREFS, $REGX, $FNS, $SESS, $STAT;
        	
        $comment_id = $IN->GBL('comment_id', 'GET');
        $weblog_id	= $IN->GBL('weblog_id', 'GET');
        $entry_id	= $IN->GBL('entry_id', 'GET');
        
        if ($comment_id == FALSE)
        {
            return $DSP->no_access_message();
        }
        	
        if ($IN->GBL('validate') == 1)
        {
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			}  
			
			$sql = "SELECT exp_comments.entry_id, exp_comments.weblog_id
					FROM exp_comments, exp_weblogs
					WHERE comment_id = '$comment_id'
					AND exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
						
			$query = $DB->query($sql);			
	
			if ($query->num_rows == 0)
			{
				return $DSP->no_access_message();
			}	
			
			$entry_id  = $query->row['entry_id'];
			$weblog_id = $query->row['weblog_id'];
        }
        else
        {
			if ( ! $DSP->allowed_group('can_edit_all_comments'))
			{     
				return $DSP->no_access_message();
			}
        }
        
        // Change status
        
        $status = (isset($_GET['status']) AND $_GET['status'] == 'close') ? 'c' : 'o';
        
        $DB->query("UPDATE exp_comments SET status = '$status' WHERE comment_id = '$comment_id'");
        
        // Fetch date and autor of most recent coment
        
        $query = $DB->query("SELECT comment_date, author_id FROM exp_comments WHERE status = 'o' AND entry_id = '$entry_id' ORDER BY comment_date desc LIMIT 1");
        
        $member_id = $query->row['author_id'];
        $comment_date = ($query->num_rows == 0) ? 0 : $query->row['comment_date'];
        
        // Selete total number of comments
        
        $query = $DB->query("SELECT COUNT(*) AS count FROM exp_comments WHERE status = 'o' AND entry_id = '$entry_id' ");
        
        $comment_total = $query->row['count'];
        
        // Update weblog stats
        
		$DB->query("UPDATE exp_weblog_titles SET comment_total = '$comment_total', recent_comment_date = '$comment_date' WHERE entry_id = '$entry_id'");
        
        // Update member stats
        
        if ($member_id != 0)
        {
			$query = $DB->query("SELECT total_comments FROM exp_members WHERE member_id = '$member_id'");
			
			$total = $query->row['total_comments'];
	
			if ($status == 'c')
			{
				$query = $DB->query("SELECT comment_date FROM exp_comments WHERE status = 'o' AND author_id = '$member_id' ORDER BY comment_date desc LIMIT 1");
        		$comment_date = ($query->num_rows == 0) ? 0 : $query->row['comment_date'];
        		$total--;
			}
			else
			{
				$total++;
			}
	
			$sql = "UPDATE exp_members SET total_comments = '$total', last_comment_date = '$comment_date' WHERE member_id = '$member_id'";
		
			$DB->query($sql);                
        }
        
        // Update global stats
        
		$STAT->update_comment_stats($weblog_id);
		
		// ----------------------------------------
		//  Send email notification
		// ----------------------------------------
		
		if ($status == 'o')
		{
			$query = $DB->query("SELECT DISTINCT email FROM exp_comments WHERE status = 'o' AND entry_id = '$entry_id' AND notify = 'y'");
			
			$recipients = array();
			
			if ($query->num_rows > 0)
			{
				foreach ($query->result as $row)
				{
					$recipients[] = $row['email'];   
				}
			}
	
			$email_msg = '';
					
			if (count($recipients) > 0)
			{
				$qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
	
				$action_id  = $FNS->fetch_action_id('Comment_CP', 'delete_comment_notification');
				
				$query = $DB->query("SELECT title FROM exp_weblog_titles WHERE entry_id = '$entry_id'");
				$entry_title = $query->row['title'];
				
				$query = $DB->query("SELECT blog_title, blog_url FROM exp_weblogs WHERE weblog_id = '$weblog_id'");
			
				$swap = array(
								'weblog_name'				=> $query->row['blog_title'],
								'entry_title'				=> $entry_title,
								'site_name'					=> $PREFS->ini('site_name'),
								'site_url'					=> $PREFS->ini('site_url'),
								'comment_url'				=> $FNS->remove_double_slashes($query->row['blog_url'].'/'.$entry_id.'/'),
								'notification_removal_url'	=> $FNS->fetch_site_index(0, 0).$qs.'ACT='.$action_id.'&id='.$comment_id
							 );
				
				$template = $FNS->fetch_email_template('comment_notification');
				
				$email_msg = $FNS->var_replace($swap, $template['data']);
	
				// ----------------------------
				//  Send email
				// ----------------------------
				
				if ( ! class_exists('EEmail'))
				{
					require PATH_CORE.'core.email'.EXT;
				}
				
				$email = new EEmail;
				$email->wordwrap = true;
				
				foreach ($recipients as $val)
				{
					$email->initialize();
					$email->from($PREFS->ini('webmaster_email'));	
					$email->to($val); 
					$email->subject($template['title']);	
					$email->message($REGX->entities_to_ascii($email_msg));		
					$email->Send();
				}            
			}
		
		}	
		
		if ($IN->GBL('validate') == 1)
		{
			$FNS->redirect(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'validate=1'.AMP.'U=1');
			exit;
		}		
		        
        $this->view_comments($weblog_id, $entry_id, $DSP->qdiv('success',$LANG->line('status_changed')));
    }
    // END


    //-----------------------------------------
    // Delete comment/trackback
    //-----------------------------------------

    function delete_comment()
    {
        global $IN, $DSP, $DB, $LANG, $SESS, $FNS, $STAT;
       
        $comment_id   = $IN->GBL('comment_id', 'POST');
        $trackback_id = $IN->GBL('trackback_id', 'POST');
        
        if ($trackback_id == FALSE AND $comment_id == FALSE)
        {
            return $DSP->no_access_message();
        }
        

        if ($comment_id != FALSE)
        {
            $sql = "SELECT exp_weblog_titles.author_id, exp_weblog_titles.entry_id, exp_weblog_titles.weblog_id, exp_weblog_titles.comment_total
                    FROM   exp_weblog_titles, exp_comments
                    WHERE  exp_weblog_titles.entry_id = exp_comments.entry_id
                    AND    exp_comments.comment_id = '$comment_id'";
        }
        else
        {
            $sql = "SELECT exp_weblog_titles.author_id, exp_trackbacks.entry_id, exp_weblog_titles.weblog_id, exp_weblog_titles.trackback_total
                    FROM   exp_weblog_titles, exp_trackbacks
                    WHERE  exp_weblog_titles.entry_id = exp_trackbacks.entry_id
                    AND    exp_trackbacks.trackback_id = '$trackback_id'";
        }
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
            return $DSP->no_access_message();
        }
        
        $entry_id  = $query->row['entry_id'];
        $author_id = $query->row['author_id'];
        $weblog_id = $query->row['weblog_id'];
        
        		
        if ($IN->GBL('validate') == 1)
        {
			if ( ! $DSP->allowed_group('can_moderate_comments'))
			{
				return $DSP->no_access_message();
			}   
			
			$sql = "SELECT COUNT(*) AS count 
					FROM exp_comments, exp_weblogs
					WHERE comment_id = '$comment_id'
					AND exp_comments.status = 'c' ";
						
			$sql .= (USER_BLOG !== FALSE) ? "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' " : "AND exp_weblogs.is_user_blog = 'n' ";
						
			$query = $DB->query($sql);			
	
			if ($query->row['count'] == 0)
			{
				return $DSP->no_access_message();
			}						
        }
		else
		{
			if ( ! $DSP->allowed_group('can_delete_all_comments'))
			{
				if ( ! $DSP->allowed_group('can_delete_own_comments'))
				{     
					return $DSP->no_access_message();
				}
				else
				{
					if ($author_id != $SESS->userdata['member_id'])
					{
						return $DSP->no_access_message();
					}
				}
			}
       }
       
        if ($comment_id != FALSE)
        {
            $DB->query("DELETE FROM exp_comments WHERE comment_id = '$comment_id'");      
            
            $query = $DB->query("SELECT comment_date FROM exp_comments WHERE status = 'o' AND entry_id = '$entry_id' ORDER BY comment_date desc limit 1");
            
            $comment_date  = ($query->num_rows == 0) ? 0 : $query->row['comment_date'];

            $query = $DB->query("SELECT comment_total FROM exp_weblog_titles WHERE weblog_id = '$weblog_id'");            
            
            $DB->query("UPDATE exp_weblog_titles SET comment_total = '".($query->row['comment_total'] - 1)."', recent_comment_date = '$comment_date' WHERE entry_id = '$entry_id'");      
            
        	$STAT->update_comment_stats($weblog_id);
        	
            $msg = $LANG->line('comment_deleted');            
        }
        else
        {
            $DB->query("DELETE FROM exp_trackbacks WHERE trackback_id = '$trackback_id'");   
            
            $query = $DB->query("SELECT trackback_date FROM exp_trackbacks WHERE entry_id = '$entry_id' ORDER BY trackback_date desc limit 1");
            
            $trackback_date = ($query->num_rows == 0) ? 0 : $query->row['trackback_date'];
            
            $query = $DB->query("SELECT trackback_total FROM exp_weblog_titles WHERE weblog_id = '$weblog_id'");            
            
            $DB->query("UPDATE exp_weblog_titles SET trackback_total = '".($query->row['trackback_total'] - 1)."', recent_trackback_date = '$trackback_date' WHERE entry_id = '$entry_id'");      

			$STAT->update_trackback_stats($weblog_id);
			
           	$msg = $LANG->line('trackback_deleted');
        }
        
        
		if ($IN->GBL('validate', 'POST') == 1)
		{
			$FNS->redirect(BASE.AMP.'C=edit'.AMP.'M=view_comments'.AMP.'validate=1');
			exit;
		}		        

        $message = $DSP->qdiv('success', $msg);

        $this->view_comments($weblog_id, $entry_id, $message);
    }
    // END



}
// END CLASS
?>