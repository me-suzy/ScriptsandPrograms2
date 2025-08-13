<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2004 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: cp.mt_import.php
-----------------------------------------------------
 Purpose: Movable Type Import Utility
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class MT_Import {

    var $m_batch    = 1000;
    var $b_batch    = 100;


    // -------------------------------------------
    //  Constructor
    // -------------------------------------------    
    
    function MT_Import() 
    {
        global $IN, $DSP, $LANG, $SESS, $PREFS;
        
        // You have to be a Super Admin to access this page
        
        if ($SESS->userdata['group_id'] != 1)
        {
            return $DSP->no_access_message();
        }
        
        
        // Fetch the language file
                
        $LANG->fetch_language_file('mt_import');
        
    
        switch($IN->GBL('F'))
        {
            case 'check'                : $this->check_file();
                break;
            case 'perform_import'                : $this->perform_import();
                break;
            default                         : $this->mt_import_main_page();
                break;
        }
    }
    // END
    
    
    
    // -------------------------------------------
    //  Movable Type Import Main Page
    // -------------------------------------------    
    
    function mt_import_main_page($msg = '') 
    {
        global $IN, $DSP, $LANG, $PREFS;
         
        $DSP->title = $LANG->line('mt_import_utility');
        
        if ($DSP->crumb == '')
        {
            $DSP->crumb = $LANG->line('mt_import_utility');
        }
        
        $r  = $DSP->heading($LANG->line('mt_import_utility'));
    
        if ( ! $PREFS->ini('mt_import_file'))
        {
            $r .= $DSP->qdiv('itemWrapper', $DSP->heading($LANG->line('mt_import_welcome'), 5));
            
            $r .= $DSP->qdiv('itemWrapper', $DSP->qspan('highlight', $LANG->line('mt_import_disclaimer').BR.BR));
        }
            
        if ( ! $PREFS->ini('mt_import_file'))
        {
            $r .= $DSP->qdiv('box450', $this->mt_file_form($msg));
        }
        else
        {            
            $r .= $this->import_grid();
        }    
    
        $DSP->body = &$r;
    } 
    // END
        
    
    // -------------------------------------------
    //  MT File form
    // -------------------------------------------    
    
    function mt_file_form($message = '')
    {
        global $DSP, $IN, $LANG, $DB, $PREFS;
        
            $r  = $DSP->heading($LANG->line('import_info'));
            $r .= $DSP->qdiv('itemWrapper', $LANG->line('file_blurb'));
    
            $mt_file   = ( ! $IN->GBL('mt_file', 'POST'))  ? '' : $_POST['mt_file'];
            
            // Find weblogs
            $query = $DB->query("SELECT weblog_id, blog_title, status_group, cat_group FROM exp_weblogs ORDER BY blog_title");
            $w = '';
            $first_stati_group = '';
            $first_cat_group = '';
            $status_js = '<script language="JavaScript">
<!--

function changestatus(index)
{ 
var j=0;
var menuitem=new Array();
var which=document.mt_form.weblog_id.options[index].value;
with (document.mt_form.status)
{
';
            $category_js = '<script language="JavaScript">
<!--

function changecategory(index)
{ 
var j=0;
var menuitem=new Array();
var which=document.mt_form.weblog_id.options[index].value;
with (document.mt_form.status)
{
';

            foreach ($query->result as $row)
            {
                $w .= $DSP->input_select_option($row['weblog_id'], $row['blog_title']);
                $status_js .= "if (which == '".$row['weblog_id']."'){\n";
                $category_js .= "if (which == '".$row['weblog_id']."'){\n";
                
                $results = $DB->query("SELECT status 
                                                    FROM exp_statuses
                                                    WHERE group_id = '".$DB->escape_str($row['status_group'])."'
                                                    ORDER BY status");
                                                    
                 if ($results->num_rows > 0)
                 {        
                      foreach ($results->result as $cat_row)
                      {
                           $selected = ($cat_row['status'] == 'open') ? 'y' : '';
                           $first_stati_group .= $DSP->input_select_option($cat_row['status'], $cat_row['status'], $selected);
                           $status_js .= "menuitem[j]=new Option('".$cat_row['status']."','".$cat_row['status']."'); j++;\n";
                      }
                      
                      if ( ! isset($first_stati))
                      {
                           $first_stati = $first_stati_group;
                      }
                      
                 }
                 else
                 {
                      $status_js .= "menuitem[j]=new Option('No Statuses for Weblog','closed'); j++;\n";
                 }
                 
                 $status_js .= "} \n";
                 
                 $category_js .= "menuitem[j]=new Option('".$LANG->line('auto_create')."','auto'); j++;\n";
                 $first_cat_group .= $DSP->input_select_option('auto', $LANG->line('auto_create'));
                 
                 $widgets = $DB->query("SELECT cat_id, cat_name
                                                    FROM exp_categories
                                                    WHERE group_id = '".$DB->escape_str($row['cat_group'])."'
                                                    ORDER BY cat_name");
                 
                 if ($widgets->num_rows > 0)
                 {
                      foreach ($widgets->result as $cat_row)
                      {
                           //$selected = ($cat_row['status'] == 'open') ? 'y' : '';
                           $first_cat_group .= $DSP->input_select_option($cat_row['cat_id'], $cat_row['cat_name']);
                           $category_js .= "menuitem[j]=new Option('".$cat_row['cat_name']."','".$cat_row['cat_id']."'); j++;\n";
                      }
                      
                      if ( ! isset($first_categories))
                      {
                           $first_categories = $first_cat_group;
                      }
                 }
                
                $category_js .= "} \n";
            }
            
            $status_js .= 'with (document.mt_form.status)
{
	for (i=length-1; i >=0; i--)
	{
		options[i]=null;
	}
		
		for (i=0; i< menuitem.length; i++)
		{
			options[i]=menuitem[i];
			if (menuitem[i].value == "open")
			{
			     options[i].selected=true;
			}
		}
}}}

//-->
</script>';

            $category_js .= 'with (document.mt_form.category)
{
	for (i=length-1; i >=0; i--)
	{
		options[i]=null;
	}
		
		for (i=0; i< menuitem.length; i++)
		{
			options[i]=menuitem[i];
			if (menuitem[i].value == "open")
			{
			     options[i].selected=true;
			}
		}
}}}
//-->
</script>';
            

            $r .= $message;
            
            $r .= $status_js.$category_js;

            $r .= $DSP->form('C=admin'.AMP.'M=utilities'.AMP.'P=mt_import'.AMP.'F=check','mt_form');
            
            $r .= $DSP->div('itemWrapper').BR.
                  $DSP->qdiv('itemTitle', $LANG->line('file_info')).
                  $DSP->qdiv('itemWrapper', $LANG->line('file_blurb2')).
                  $DSP->input_text('mt_file', $mt_file, '40', '70', 'input', '300px').
                  $DSP->div_c();
                  
                  
            // weblog pull-down menu
            
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('weblog_select'));
             
             $r .= $DSP->qdiv('itemWrapper', $LANG->line('field_blurb'));
            
            // Had to write this out since function did not allow addition of JS
            $r .= "\n".'<select name="weblog_id" class="select" onChange="changestatus(this.selectedIndex);changecategory(this.selectedIndex);">'."\n";
            
            $r .= $w;
    
            $r .= $DSP->input_select_footer();
            
            $r .= $DSP->div_c();
            
             // member pull-down menu
            
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('admin_select'));
            
            $r .= $DSP->input_select_header('member_id');
            
            $query =$DB->query("SELECT exp_members.member_id, exp_members.screen_name, exp_members.username 
                                             FROM exp_members, exp_member_groups
                                             WHERE exp_member_groups.group_id = exp_members.group_id
                                             AND ((exp_member_groups.can_access_cp = 'y'
                                             AND exp_member_groups.can_access_publish = 'y')
                                             OR (exp_members.group_id = '1'))");
    
            foreach ($query->result as $row)
            {
                 if ($row['screen_name'] == '')
                 {
                      $row['screen_name'] = $row['username'];
                 }
                $r .= $DSP->input_select_option($row['member_id'], $row['screen_name']);
            }
    
            $r .= $DSP->input_select_footer();
            
            $r .= $DSP->div_c();
            
            
             // status pull-down menu
            
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('status_select'));
            
            $r .= $DSP->input_select_header('status');
            
            if ( isset($first_stati))
            {
                 $r .= $first_stati;
            }
    
            $r .= $DSP->input_select_footer();
            
            $r .= $DSP->div_c();
            
            // category pull-down menu
            $r .= $DSP->div('itemWrapper').
                  $DSP->qdiv('itemTitle', $LANG->line('category_select'));
            
             $r .= $DSP->input_select_header('category');
            
            if ( isset($first_categories))
            {
                 $r .= $first_categories;
            }
    
            $r .= $DSP->input_select_footer();
            
            $r .= $DSP->div_c();
                  
            $r .= $DSP->qdiv('itemWrapper', BR.$DSP->input_submit($LANG->line('submit_info'), 'submit'));
        
            $r .= $DSP->form_c();

        return $r;
    }    
    // END    
    
    
    
   // -------------------------------------------
    //  Check File and Save Location in Config File
    // -------------------------------------------    
    
    function check_file()
    {
        global $DSP, $IN, $FNS, $LANG;
        
        // Check for required fields
        
        if ( ! isset($_POST['mt_file']) || $_POST['mt_file'] == '' || $_POST['weblog_id'] == '' || $_POST['member_id'] == '' || $_POST['status'] == '')
        {
            return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('empty_field_warning')));
        }
        
        $realpath = realpath($_POST['mt_file']);
        
        if ( ! file_exists($realpath))
        {
        	return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('invalid_path')));
        }
        
        $lines = file($realpath);
        $data = implode('', $lines);
        
        if (! strpos($data,'--------'))
        {
        	return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('invalid_file')));
        }
     
     
     	// Write the new data to the config file
        
        $pm_config = array(
                            'mt_file_location'               => $realpath,
                            'mt_weblog_selection'        => $_POST['weblog_id'],
                            'mt_admin_selection'         => $_POST['member_id'],
                            'mt_category_selection'     => $_POST['category'],
                            'mt_status_selection'         => $_POST['status']);
                          
        Admin::append_config_file($pm_config);
        
        
        // -----------------------------------------
        //  Redirect to main import page
        // -----------------------------------------

        $FNS->redirect(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=mt_import'.AMP.'F=perform_import');
        exit;     
    }
    // END    
    
        
    // -------------------------------------------
    //  Import File and Find category info
    // -------------------------------------------    
    
    function perform_import()
    {
        global $DSP, $IN, $FNS, $LANG, $PREFS, $DB, $IN, $LOC, $REGX;


        $realpath = realpath($PREFS->ini('mt_file_location'));   
        $weblog_id = $PREFS->ini('mt_weblog_selection');        
        $member_id = $PREFS->ini('mt_admin_selection');        
        $status = $PREFS->ini('mt_status_selection');   
        $category = $PREFS->ini('mt_category_selection');        
        
        // --------------------------------------
        //  FETCH WEBLOG FIELDS
        // --------------------------------------
        
        $query = $DB->query("SELECT field_group FROM exp_weblogs WHERE weblog_id = '{$weblog_id}'");        
        
        if ($query->num_rows == 0)
        {
            return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('no_fields_assigned')));
        }

        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }

        $query = $DB->query("SELECT field_id, field_name 
                                           FROM exp_weblog_fields 
                                           WHERE group_id = '{$field_group}' 
                                           AND field_type = 'textarea' 
                                           ORDER BY field_order");
        
        $fields = array();
        
        foreach ($query->result as $row)
        {
            $fields['field_id_'.$row['field_id']] = $row['field_name'];
        }

        if (count($fields) == 0)
        {
            return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('no_textarea_fields')));
        }
        
        
        // --------------------------------------
        //  MT IMPORT STUFF
        // --------------------------------------
        
        $lines = file($realpath);
        $data = implode('', $lines);
        
        if ( ! strpos($data,'--------'))
        {
        	return $this->mt_import_main_page($DSP->qdiv('alert', BR.$LANG->line('invalid_file')));
        }
        
        // All tabs into spaces.
        $data = preg_replace("/(\t)/", ' ', $data);
        
        // Make all line breaks into one type of identifiable line break marker.
        $LB = '9serLBR3ngti';
        $data = preg_replace("/(\r\n|\n|\r)/", $LB, $data);
        
        // Break it up by entries.
        $entries = explode('--------'.$LB, $data);
        
        // Our various data arrays
        $titles = array();
        $dates = array();
        $body = array();
        $extended = array();
        $summary = array();
        $keywords = array();
        
        $comments = array();
        $trackbacks = array();
        
        $allow_comments = array();
        $allow_pings = array();
        $convert_breaks = array();
        
        $primary_categories = array();
        $categories              = array();
        
        $id = 0;
        foreach($entries as $entry)
        {
        	if (trim($entry) == '')
        	{
        		continue;
        	}
        	
        	$sections = explode("-----",$entry);
        	
        	// We expect at least two sections
        	if ( ! isset($sections['1']))
        	{
        	     continue;
        	}
        	
        	// -----------------------------------
        	// Grab entry data and put into arrays 
        	// -----------------------------------
        	
        	$first_section = explode($LB,$sections['0']);
        	$allow_comments[$id] = 1;
        	$allow_pings[$id] = 0;
        	$convert_breaks[$id] = 0;
        	
        	for ($i=0; $i < sizeof($first_section); $i++)
        	{
        	      if (trim($first_section[$i]) == '')
        	      {
        	          continue;
        	      }
        	
        	      $parts = explode(':',$first_section[$i]);
        	      if (sizeof($parts) < 2)
        	      {
        	          continue;
        	      }
        	      
        	      // TITLE
        	      if (strstr($parts['0'],'TITLE') !== false)
        	      {
        	           $titles[$id] = trim(str_replace('TITLE:','',$first_section[$i]));
                      }
                      
                      // DATE - keep in format, change later
                      if (strstr($parts['0'],'DATE') !== false)
                      {
                           $dates[$id] = trim(str_replace('DATE:','',$first_section[$i]));
                      }
                      
                      // META DATA
                      if (strstr($parts['0'],'ALLOW COMMENTS') !== false)
                      {
                           $allow_comments[$id] = trim(str_replace('ALLOW COMMENTS:','',$first_section[$i]));
                      }
                      if (strstr($parts['0'],'ALLOW PINGS') !== false)
                      {
                           $allow_pings[$id] = trim(str_replace('ALLOW PINGS:','',$first_section[$i]));
                      }
                      if (strstr($parts['0'],'CONVERT BREAKS') !== false)
                      {
                           $convert_breaks[$id] = trim(str_replace('CONVERT BREAKS:', '',$first_section[$i]));
                      }
                      
                      // PRIMARY CATEGORY
                      if (strstr($parts['0'],'PRIMARY CATEGORY') !== false)
        	      {
        	           $primary_categories[$id] = trim(str_replace('PRIMARY CATEGORY:','',$first_section[$i]));
                      }
                      
                      // CATEGORY 
                      elseif (strstr($parts['0'],'CATEGORY') !== false)
        	      {
        	           // Catch for people who make primary and category equal to each other.
        	          if (isset($primary_categories[$id]) && trim($parts['1']) == $primary_categories[$id])
        	          {
        	               continue;
        	          }
        	          
        	          // No primary category, but multiple secondary categories then first secondary is primary...
        	          if ( ! isset($primary_categories[$id]) && sizeof ($categories[$id]) > 1)
        	          {
        	               $primary_categories[$id] = $categories[$id]['0'];
        	               $categories[$id]['0'] = trim(str_replace('CATEGORY:','',$first_section[$i]));
        	          }
        	          else
        	          {
        	               $categories[$id][] = trim(str_replace('CATEGORY:','',$first_section[$i]));
        	          }
                      }
                }
                // End section 1
                
                // More MT logic:
                // If no primary category and there is a single category, then category becomes primary category
                if ( ! isset($primary_categories[$id]) && isset($categories[$id]) && sizeof ($categories[$id]) == 1)
                {
                     $primary_categories[$id] = $categories[$id]['0'];
                     unset($categories[$id]);
                }
                
                
                // Data Check
                if ( ! isset($dates[$id]) || ! isset($titles[$id]) || str_replace($LB, '', trim($titles[$id])) == '' || str_replace($LB, '', trim($dates[$id])) == '')
                {
                     continue;
                }
                      
                // Go through the rest of the sections
                
                for ($i=1; $i < sizeof ($sections); $i++)
                {
                     
                      // EXTENDED BODY
                      preg_match("/EXTENDED BODY:(.*)/", $sections[$i], $meta_info);
                      if (isset($meta_info['1']))
                      {
                           $extended[$id] = trim($meta_info['1']);
                           continue;
                      }
                      
                      // BODY
                      preg_match("/BODY:(.*)/", $sections[$i], $meta_info);
                      if (isset($meta_info['1']))
                      {
                           $body[$id] = trim($meta_info['1']);
                           continue;
                      }
                      
                      // EXCERPT
                      preg_match("/EXCERPT:(.*)/", $sections[$i], $meta_info);
                      if (isset($meta_info['1']))
                      {
                           $summary[$id] = trim($meta_info['1']);
                           continue;
                      }
                      
                      // KEYWORDS
                      preg_match("/KEYWORDS:(.*)/", $sections[$i], $meta_info);
                      if (isset($meta_info['1']))
                      {
                           $keywords[$id] = trim($meta_info['1']);
                           continue;
                      }
                      
                      // COMMENTS
                      preg_match("/COMMENT:(.*)/", $sections[$i], $meta_info);
        	      if (isset($meta_info['1']))
        	      {
        		     if ( ! isset($c)) $c = 0;
        		     $cparts = explode($LB, $meta_info['1']);
        		     
        		     foreach($cparts as $cpart)
        		     {
        		          if (strstr($cpart,'AUTHOR:') !== false)
                                  {
                                       $comments[$id][$c]['author'] = trim(str_replace('AUTHOR:','',$cpart));
                                       $meta_info['1'] = str_replace($cpart.$LB,'',$meta_info['1']);
                                  }
                                  elseif (strstr($cpart,'DATE:') !== false)
                                  {
                                       $comments[$id][$c]['date'] = trim(str_replace('DATE:','',$cpart));
                                       $meta_info['1'] = str_replace($cpart.$LB,'',$meta_info['1']);
                                  }
                                  elseif (strstr($cpart,'EMAIL:') !== false)
                                  {
                                       $comments[$id][$c]['email'] = trim(str_replace('EMAIL:','',$cpart));
                                       $meta_info['1'] = str_replace($cpart.$LB,'',$meta_info['1']);
                                  }
                                  elseif (strstr($cpart,'URL:') !== false)
                                  {
                                       $comments[$id][$c]['url'] = trim(str_replace('URL:','',$cpart));
                                       $meta_info['1'] = str_replace($cpart.$LB,'',$meta_info['1']);
                                  }
                                  elseif (strstr($cpart,'IP:') !== false)
                                  {
                                       $comments[$id][$c]['ip'] = trim(str_replace('IP:','',$cpart));
                                       $meta_info['1'] = str_replace($cpart.$LB,'',$meta_info['1']);
                                  }
        		     }
        		      // Required
        		     if ( ! isset($comments[$id][$c]['author']) || ! isset($comments[$id][$c]['date']))
        		     {
        		         unset($comments[$id][$c]);
        		         continue;
        		     }
        		     
        		     // Clean up comment body
        		     $meta_info['1'] = str_replace('COMMENT:'.$LB, '', $meta_info['1']);
        		     while(substr($meta_info['1'],0,strlen($LB)) == $LB)
        		     {
        		          $meta_info['1'] = substr($meta_info['1'], strlen($LB));
        		     }
        		     
        		     while(substr($meta_info['1'],-strlen($LB)) == $LB)
        		     {
        		          $meta_info['1'] = substr($meta_info['1'], 0, -strlen($LB));
        		     }
        		     
        		     // Store comment body
        		     $comments[$id][$c]['body'] = trim($meta_info['1']);
        		     
        		     $c++; // C++, get it? Ha!
        		     continue;
        	     }
        	     
        	     
        	     
        	     // TRACKBACKS
        	     preg_match("/PING:(.*)/", $sections[$i], $meta_info);
        	     if (isset($meta_info['1']))
        	     {
                          if ( ! isset($t)) $t = 0;
                          $tparts = explode($LB, $meta_info['1']);
        		     
        		  foreach($tparts as $tpart)
                          {
                               if (strstr($tpart,'TITLE:') !== false)
                               {
                                    $trackbacks[$id][$t]['title'] = trim(str_replace('TITLE:','',$tpart));
                                    $meta_info['1'] = str_replace($tpart,'',$meta_info['1']);
                               }
                               elseif (strstr($tpart,'DATE:') !== false)
                               {
                                    $trackbacks[$id][$t]['date'] = trim(str_replace('DATE:','',$tpart));
                                    $meta_info['1'] = str_replace($tpart,'',$meta_info['1']);
                               }
                               elseif (strstr($tpart,'URL:') !== false)
                               {
                                    $trackbacks[$id][$t]['url'] = trim(str_replace('URL:','',$tpart));
                                    $meta_info['1'] = str_replace($tpart,'',$meta_info['1']);
                               }
                               elseif (strstr($tpart,'IP:') !== false)
                               {
                                    $trackbacks[$id][$t]['ip'] = trim(str_replace('IP:','',$tpart));
                                    $meta_info['1'] = str_replace($tpart,'',$meta_info['1']);
                               }
                               elseif (strstr($tpart,'BLOG NAME:') !== false)
                               {
                                    $trackbacks[$id][$t]['blog_name'] = trim(str_replace('BLOG NAME','',$tpart));
                                    $meta_info['1'] = str_replace($tpart,'',$meta_info['1']);
                               }
        		      
        		}
        		
        		// Required fields is four
        		// Only IP is not required.
        		if (sizeof($trackbacks[$id][$t]) < 4 && isset($trackbacks[$id][$t]['ip']))
        	       {
        	            unset($trackbacks[$id][$t]);
                            continue;
                       }
                       // Clean up Trackback body
                       $meta_info['1'] = str_replace('PING:'.$LB, '', $meta_info['1']);
                       while(substr($meta_info['1'],0,strlen($LB)) == $LB)
                       {
                            $meta_info['1'] = substr($meta_info['1'], strlen($LB));
                       }
                       
                       while(substr($meta_info['1'],-strlen($LB)) == $LB)
                       {
                            $meta_info['1'] = substr($meta_info['1'], 0, -strlen($LB));
                       }
                       
                       // Store trackback body
                       $trackbacks[$id][$t]['body'] = trim($meta_info['1']);
                       
        		$t++;
        	     } 
                }  
                // End of all sections
                
                // Data Check
                if ( ! isset($body[$id]) || str_replace($LB, '', trim($body[$id]) == ''))
                {
                     continue;
                }
        	
        	$id++;
        	$c = 0;
        	$t = 0;
        }
        
        if ($category == 'auto')
        {
        	$cleaned_primary_categories = array_unique($primary_categories);
        	$cleaned_categories = array();
        
        	foreach ($categories as $eid => $cat_array)
        	{
        		foreach ($cat_array as $cat)
        		{
        			if ( ! in_array($cat,$cleaned_categories))
        			{
        			     $cleaned_categories[$eid] = $cat;
        			}
        		}
        	}
        
        
        	// Find category group for this weblog
        	$query =$DB->query("SELECT cat_group FROM exp_weblogs
			                         WHERE weblog_id = '{$weblog_id}'");
	
		$weblog_cat_id = $query->row['cat_group'];
	
		// Category ID Arrays
		$primary_cat_ids = array();
		$regular_cat_ids = array();
        
		// Check for these primary categories.  If not there, create
		if (sizeof($cleaned_primary_categories) > 0)
		{
			foreach($cleaned_primary_categories as $key => $prim)
			{
			    $name = $IN->clean_input_data($prim);
			    if ($name == '')
			    {
			         continue;
			    }
			    
			    $query =$DB->query("SELECT cat_id
			                                     FROM exp_categories
			                                     WHERE exp_categories.cat_name = '".$DB->escape_str($name)."'
			                                     AND exp_categories.parent_id = '0'
			                                     AND exp_categories.group_id = '{$weblog_cat_id}'
			                                     ");
			                                     
			    if ($query->num_rows == 0)
			    {	
			    	// Create primary category
			    	$insert_array = array('group_id'  => $weblog_cat_id,
			    	                                 'cat_name' => $name,
			    	                                 'cat_image' => '',
			    	                                 'parent_id'   => '0'
			    	                                 );
			    	
			    	$sql = $DB->insert_string('exp_categories', $insert_array);   
			    	$DB->query($sql);       
			    	$primary_cat_ids[$name] = $DB->insert_id;
			    }
			    else
			    {
			         $primary_cat_ids[$name] = $query->row['cat_id'];
			         unset($cleaned_primary_categories[$key]);
			    }
			}
		}
		
		
		// Check for these categories.  If not there, create.
		if (sizeof($cleaned_categories) > 0)
		{
			foreach($cleaned_categories as $key => $cat)
			{
			    $name = $IN->clean_input_data($cat);
			    if ($name == '')
			    {
			         continue;
			    }
			
			    $query =$DB->query("SELECT cat_id 
			                                     FROM exp_categories
			                                     WHERE exp_categories.cat_name = '".$DB->escape_str($cat)."'
			                                     AND exp_categories.group_id = '{$weblog_cat_id}'
			                                     ");
			                                     
			    if ($query->num_rows == 0)
			    {
			    	// Find primary category for this category (if any)
			    	if (isset($primary_categories[$key]) && $primary_categories[$key] != $cat)
			    	{
			    		$parent = $IN->clean_input_data($primary_categories[$key]);
			    		$results = $DB->query("SELECT cat_id 
			                                                   FROM exp_categories
			                                                   WHERE exp_categories.cat_name = '".$DB->escape_str($parent)."'
			                                                   AND exp_categories.parent_id = '0'
			                                                   AND exp_categories.group_id = '{$weblog_cat_id}'");
			                                                 
			                  $pid = ($results->num_rows > 0) ? $results->row['cat_id'] : '0';
			    	} 
			    	else
			    	{
			    		$pid = 0;
			    	}
			    
			    	// Create category
			    	$insert_array = array('group_id'  => $weblog_cat_id,
			    	                                 'cat_name' => $name,
			    	                                 'cat_image' => '',
			    	                                 'parent_id'   => $pid
			    	                                 );
			    	
			    	$sql = $DB->insert_string('exp_categories', $insert_array);   
			    	$DB->query($sql);  
			    	$regular_cat_ids[$name] = $DB->insert_id;
			    }
			    else
			    {
			         $regular_cat_ids[$name] = $query->row['cat_id'];
			         unset($cleaned_categories[$key]);
			    }
			}
		}
	}	
		
	// --------------------------------------
	// Data Arrays
	// --------------------------------------
	
        
        // Get our member's IP address
        $result = $DB->query("SELECT member_id, ip_address FROM exp_members WHERE pmember_id = '{$member_id}'");
        $ip_address = $result->row['ip_address'];
        $total = $id;
        $comments_entered = 0;
        $trackbacks_entered = 0;
        
        
	for ($id=0; $id < $total; $id++)
	{
		// Function to create MT Export Date format to gmt
		$entry_date = $this->convert_mt_date_to_gmt($dates[$id]);
		
		$url_title = strtolower(strip_tags($titles[$id]));
		$url_title = $REGX->create_url_title($titles[$id]);
		
		$comments_allowed = ($allow_comments[$id] ==  1) ? 'y' : 'n';
		$trackbacks_allowed = ($allow_pings[$id] ==  1) ? 'y' : 'n';
		$breaks_converted = ($convert_breaks[$id] ==  1 || $convert_breaks[$id] == '__default__') ? 'br' : 'none';
              
              // Discover recent comment date
              if (isset($comments[$id]) && sizeof($comments[$id]) > 0)
              {
                  $recent_comment_date = time();
                  for($c=0; $c < sizeof($comments[$id]); $c++)
                  {
                       $date = $this->convert_mt_date_to_gmt(stripslashes($comments[$id][$c]['date']));
                       if ($date < $recent_comment_date)
                       {
                            $recent_comment_date = $date;
                       }
                  }
              }
              else
              {
                   $recent_comment_date = 0;
              }
              
              // Discover recent trackback date
              if (isset($trackbacks[$id]) && sizeof($trackbacks[$id]) > 0)
              {
                  $recent_trackback_date = time();
                  for($t=0; $t < sizeof($trackbacks[$id]); $t++)
                  {
                       $date = $this->convert_mt_date_to_gmt(stripslashes($trackbacks[$id][$t]['date']));
                       if ($date < $recent_trackback_date)
                       {
                            $recent_trackback_date = $date;
                       }
                  }
              }
              else
              {
                   $recent_trackback_date = 0;
              }
              
              $comment_total = ( ! isset($comments[$id])) ? 0 : sizeof($comments[$id]);
              $trackback_total = ( ! isset($trackbacks[$id])) ? 0 : sizeof($trackbacks[$id]);
	    
	     $data = array(
                                'entry_id'                         => '',
                                'weblog_id'                       => $weblog_id,
                                'author_id'                        => $member_id,
                                'ip_address'                      => $ip_address,
                                'title'                                => $titles[$id],
                                'url_title'                          => $url_title,
                                'status'                            => $status,
                                'allow_comments'             => $comments_allowed,
                                'allow_trackbacks'             => $trackbacks_allowed,
                                'entry_date'                     => $entry_date, // Converted to GMT above
                                'year'                               => date("Y", $entry_date), // Already converted to GMT
                                'month'                            => date("m", $entry_date), // so we just need to use date()
                                'day'                                => date("d", $entry_date), // for year, month, and day, I think.
                                'expiration_date'              => 0,
                                'recent_comment_date'   => $recent_comment_date,
                                'recent_trackback_date'   => $recent_trackback_date,
                                'comment_total'              => $comment_total,
                                'trackback_total'              => $trackback_total
                              );
                              
              $sql = $DB->insert_string('exp_weblog_titles', $data);  
              $DB->query($sql); 
              $entry_id = $DB->insert_id;
              
              //------------------------------------
              // Insert the custom field data
              //------------------------------------
                
              $cust_fields = array('entry_id' => $entry_id, 'weblog_id' => $weblog_id);
          
              // Key = field name in database table
              // Val = field_name
              // This should match the array against the field_name 
              // and insert into the correct field in the weblog data table
              
              foreach ($fields as $key => $val)
              {
                   if ( isset(${$val}[$id]))
                   {
                        $field_data = str_replace($LB,"\n", ${$val}[$id]);
                        
                        // Make sure the field data is not just some line breaks
                        if ($field_data != "\n" && $field_data != "\n\n" && $field_data != "\n\n\n")
                        {
                             $cust_fields[$key] = $field_data;
                             $key2 = str_replace('field_id_', 'field_ft_', $key);
                             $cust_fields[$key2] = $breaks_converted;
                        }
                   }
              }
              
              $DB->query($DB->insert_string('exp_weblog_data', $cust_fields));	
              
             if ($category == 'auto')
             { 
                   //------------------------------------
                   // Insert primary categories
                   //------------------------------------
              
                   if ( isset($primary_categories[$id]))
                   {
                        if (isset($primary_cat_ids[$prim]))
                        {
                             $cat_id = $primary_cat_ids[$prim];
		             $DB->query("INSERT INTO exp_category_posts (entry_id, cat_id) VALUES ('{$entry_id}', '{$cat_id}')");
                        }
                   }
                   
                   //------------------------------------
                   // Insert categories
                   //------------------------------------
              
                   if ( isset($categories[$id]) && sizeof($categories[$id]) > 0)
                   {
                        foreach($categories[$id] as $cat)
                        {
                             if ( ! isset($regular_cat_ids[$cat]))
                             {
                                  continue; // this should never happen, but just in case...
                             }
		        
		             $cat_id = $regular_cat_ids[$cat];
		             $DB->query("INSERT INTO exp_category_posts (entry_id, cat_id) VALUES ('{$entry_id}', '{$cat_id}')");
                        }
                   }
              }
              else
              {
                   $DB->query("INSERT INTO exp_category_posts (entry_id, cat_id) VALUES ('{$entry_id}', '{$category}')");
              }
              
              //------------------------------------
              // Insert the comment data
              //------------------------------------
              
              if ( isset($comments[$id]) && sizeof($comments[$id]) > 0)
              {
                   // $comments[$id][$c]['body'], ['ip'], ['author'], ['url'], ['email'], ['date']
                   
                   for($c=0; $c < sizeof($comments[$id]); $c++)
                   {
                        $com_name = ( ! isset($comments[$id][$c]['author'])) ? 'Anonymous' : stripslashes($comments[$id][$c]['author']); 
                        $com_email = ( ! isset($comments[$id][$c]['email'])) ? '' : stripslashes($comments[$id][$c]['email']); 
                        $com_url = ( ! isset($comments[$id][$c]['url'])) ? '' : stripslashes($comments[$id][$c]['url']); 
                        $com_ip = ( ! isset($comments[$id][$c]['ip'])) ? '' : stripslashes($comments[$id][$c]['ip']); 
                        $com_body = str_replace($LB, "\n", stripslashes($comments[$id][$c]['body'])); 
                   
                        $com_date = $this->convert_mt_date_to_gmt($comments[$id][$c]['date']);
                        
                        $data = array(
                                             'weblog_id'           => $weblog_id,
                                             'entry_id'             => $entry_id,
                                             'author_id'           => 0,
                                             'name'                 => $com_name,
                                             'email'                 => $com_email,
                                             'url'                     => $com_url,
                                             'location'             => '',
                                             'comment'           => $com_body,
                                             'comment_date'  => $com_date,
                                             'ip_address'         => $com_ip,
                                             'notify'                => 'n'
                                          );
                                                          
                        $DB->query($DB->insert_string('exp_comments', $data));
                        $comments_entered++;
                  }
              }
              
               //------------------------------------
              // Insert the trackback data
              //------------------------------------
              
              if ( isset($trackbacks[$id]) && sizeof($trackbacks[$id]) > 0)
              {
                   // $trackbacks[$id][$t]['title'], ['date'], ['url'], ['ip'],['blog_name'], ['body']
                   
                   for($t=0; $t < sizeof($trackbacks[$id]); $t++)
                   {
                        $ping_title = stripslashes($trackbacks[$id][$t]['title']); 
                        $blog_name = stripslashes($trackbacks[$id][$t]['blog_name']); 
                        $ping_url = stripslashes($trackbacks[$id][$t]['url']); 
                        $ping_ip = ( ! isset($trackbacks[$id][$t]['ip'])) ? '' : stripslashes($trackbacks[$id][$t]['ip']); 
                        $ping_body = str_replace($LB, "\n", stripslashes($trackbacks[$id][$t]['body'])); 
                        $ping_date = $this->convert_mt_date_to_gmt($trackbacks[$id][$t]['date']);
                        
                        $data = array(
                                             'weblog_id'            => $weblog_id,
                                             'entry_id'              => $entry_id,
                                             'title'                     => $ping_title,
                                             'content'               => $ping_body,
                                             'weblog_name'      => $blog_name,
                                             'trackback_url'      => $ping_url,
                                             'trackback_date'   => $ping_date,
                                             'trackback_ip'          => $ping_ip
                                          );
                                                          
                        $DB->query($DB->insert_string('exp_trackbacks', $data));
                        $trackbacks_entered++;
                  }
              }
	}
	// END of importing entries 
	
	// Clear out the config information.
	$this->clear_config_prefs();
	
	// Display Success Message...
	
	if ($category == 'auto')
	{
		$categories_entered = sizeof($cleaned_primary_categories) + sizeof($cleaned_categories);
	}
	
	$DSP->title = $LANG->line('mt_import_utility');
        $DSP->crumb = $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=mt_import', $LANG->line('mt_import_utility')).$DSP->crumb_item($LANG->line('import_complete'));
        
        $r = $DSP->heading($LANG->line('import_complete'));
        
        $r .= $DSP->qdiv('success', $LANG->line('you_are_done_importing'));
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_entries').NBS.$id);
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_comments').NBS.$comments_entered);
        
        $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_weblog_trackbacks').NBS.$trackbacks_entered);
        
        if (isset($categories_entered) && $categories_entered > 0)
        {
             $r .= $DSP->qdiv('itemWrapper', $LANG->line('total_categories_entered').NBS.$categories_entered);
        }
        
        $r .= $DSP->qdiv('itemWrapper', BR.$DSP->qdiv('highlight', $LANG->line('more_importing_info')));
        $r .= $DSP->qdiv('itemWrapper', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=mt_import', $LANG->line('return_to_import')));
        
        $r .= $DSP->heading($LANG->line('recalculate_statistics'), 2);
        $r .= $DSP->qdiv('itemWrapper', $DSP->anchor(BASE.AMP.'C=admin'.AMP.'M=utilities'.AMP.'P=recount_stats', $LANG->line('click_to_reset_statistics')));
        
         
        $DSP->body = &$r;
        
    }
    // END    
    
    

     // ------------------------------------------- 
     // Converts the human-readable date used in the MT export format
     // -------------------------------------------
     
    function convert_mt_date_to_gmt($datestr = '')
    {
        global $LANG, $LOC;
    
        if ($datestr == '')
            return false;
                    
            $datestr = trim($datestr);
            $datestr = str_replace('/','-',$datestr);
            $datestr = preg_replace("/\040+/", "\040", $datestr);

            if ( ! ereg("^[0-9]{1,2}\-[0-9]{1,2}\-[0-9]{2,4}\040[0-9]{1,2}:[0-9]{1,2}.*$", $datestr))
            {
                return $LANG->line('invalid_date_formatting');
            }

            $split = preg_split("/\040/", $datestr);

            $ex = explode("-", $split['0']);            
            
            $month = (strlen($ex['0']) == 1) ? '0'.$ex['0']  : $ex['0'];
            $day   = (strlen($ex['1']) == 1) ? '0'.$ex['1']  : $ex['1'];
            $year  = (strlen($ex['2']) == 2) ? '20'.$ex['2'] : $ex['2'];

            $ex = explode(":", $split['1']); 
            
            $hour = (strlen($ex['0']) == 1) ? '0'.$ex['0'] : $ex['0'];
            $min  = (strlen($ex['1']) == 1) ? '0'.$ex['1'] : $ex['1'];

            if (isset($ex['2']) AND ereg("[0-9]{1,2}", $ex['2']))
            {
                $sec  = (strlen($ex['2']) == 1) ? '0'.$ex['2'] : $ex['2'];
            }
            else
            {
                $sec = date('s');
            }
            
            if (isset($split['2']))
            {
                $ampm = strtolower($split['2']);
                
                if (substr($ampm, 0, 1) == 'p' AND $hour < 12)
                    $hour = $hour + 12;
                    
                if (substr($ampm, 0, 1) == 'a' AND $hour == 12)
                    $hour =  '00';
                    
                if (strlen($hour) == 1)
                    $hour = '0'.$hour;
            }

        if ($year < 1902 || $year > 2037)            
        {
            return $LANG->line('date_outside_of_range');
        }
                
        $time = $LOC->set_gmt(mktime($hour, $min, $sec, $month, $day, $year));

        // Offset the time by one hour if the user is submitting a date
        // in the future or past so that it is no longer in the same
        // Daylight saving time.
        
        if (date("I", $LOC->now))
        { 
            if ( ! date("I", $time))
            {
               $time -= 3600;            
            }
        }
        else
        {
            if (date("I", $time))
            {
                $time += 3600;           
            }
        }

        $time += $LOC->set_localized_offset();

        return $time;      
    }
    // END

    
    
 
    // -------------------------------------------
    //  clear config data
    // -------------------------------------------    
 
 	function clear_config_prefs()
 	{
 		global $DSP, $LANG;
 	
		require 'config'.EXT;
		
		$newdata = array();
	 
		// -----------------------------------------
		//	Write config backup file
		// -----------------------------------------
				
		$old  = "<?php\n\n";
		$old .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($conf as $key => $val)
		{
			if ( ! ereg("^mt_", $key))
			{
				$newdata[$key] = $val;
			}
		
			$old .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$old .= '?'.'>';
		
		if ($fp = @fopen('config_bak'.EXT, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $old);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}		
				
		// -----------------------------------------
		//	Write config file as a string
		// -----------------------------------------
		
		$new  = "<?php\n\n";
		$new .= "if ( ! defined('EXT')){\nexit('Invalid file request');\n}\n\n";
	 
		foreach ($newdata as $key => $val)
		{
			$new .= "\$conf['".$key."'] = \"".addslashes($val)."\";\n";
		} 
		
		$new .= '?'.'>';
		
		// -----------------------------------------
		//	Write config file
		// -----------------------------------------

		if ($fp = @fopen('config'.EXT, 'wb'))
		{
			flock($fp, LOCK_EX);
			
			fwrite($fp, $new);
			
			flock($fp, LOCK_UN);
			
			fclose($fp);
		}
 	} 
  	// END
}
// END CLASS
?>