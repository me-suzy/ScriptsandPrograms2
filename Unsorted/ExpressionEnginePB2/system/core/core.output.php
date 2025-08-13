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
 File: core.output.php
-----------------------------------------------------
 Purpose: Display class.  All browser output is
 managed by this file.
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Output {

    // Private variables.  Do not alter

    var $out_type  = 'webpage';
    var $out_queue = '';


    // -------------------------------------------
    //    Build "output queue"
    // -------------------------------------------

    function build_queue($output)
    {
        $this->out_queue .= $output;
    }
    // END
 

    // -------------------------------------------
    //    Display the final browser output
    // -------------------------------------------

    function display_final_output($output = '')
    {
        global $PREFS, $TMPL, $BM, $DB, $SESS, $FNS;
        
        // -----------------------------------
        //  Fetch the output
        // -----------------------------------
                
        if ($output == '') 
            $output =& $this->out_queue;
            
        // -----------------------------------
        // Start output buffering
        // -----------------------------------

        ob_start();

        // -----------------------------------
        // Generate HTTP headers
        // -----------------------------------
        
        if ($PREFS->ini('send_headers') == 'y')
        {        
            @header("HTTP/1.0 200 OK");
            @header("HTTP/1.1 200 OK");
            @header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
            @header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
            @header("Cache-Control: no-store, no-cache, must-revalidate");
            @header("Cache-Control: post-check=0, pre-check=0", false);
            @header("Pragma: no-cache");
        }

        // -----------------------------------
        // Send CSS header
        // -----------------------------------

        if ($this->out_type == 'css')
        {
            @header("Content-type: text/css");
        }
        
        // -----------------------------------
        // Send RSS header
        // -----------------------------------
        
        if ($this->out_type == 'rss')
        { 
            @header("Content-Type: text/xml");            
        }
                    
        
        // -----------------------------------
        //  Fetch the buffered output
        // -----------------------------------
        
        echo $output;
                
        $buffer = ob_get_contents();
                
        ob_end_clean(); 

        
        // -----------------------------------
        //  Finalize template rendering
        // -----------------------------------
            
        if (REQ != 'CP')
        {             
            // -----------------------------------
            //  Add security hashes to forms
            // -----------------------------------
            
            // We do this here to keep the security hashes from being cached
            
            if ($PREFS->ini('secure_forms') == 'y')
            {
                if (preg_match_all("/({XID_HASH})/", $buffer, $matches))
                {
                    $db_reset = FALSE;
                    
                    // Disable DB caching if it's currently set
                    
                    if ($DB->enable_cache == TRUE)
                    {
                        $DB->enable_cache = FALSE;
                        
                        $db_reset = TRUE;
                    }
                
                    // Add security hashes
                    
                    foreach ($matches['1'] as $val)
                    {
                        $hash = $FNS->random('encrypt');
                        
                        $buffer = preg_replace("/{XID_HASH}/", $hash, $buffer, 1);
                        
                        $DB->query("INSERT INTO exp_security_hashes (date, hash) VALUES('".time()."', '".$hash."')");
                    }
                    
                    // Re-enable DB caching
                    
                    if ($db_reset == TRUE)
                    {
                        $DB->enable_cache = TRUE;                
                    }
                }
            }
            
            // -----------------------------------
            // Parse global template variables
            // -----------------------------------
            
            $buffer = str_replace(LD.'total_queries'.RD, $DB->q_count, $buffer);       
            $buffer = str_replace(LD.'hits'.RD, $TMPL->template_hits, $buffer);  
            
			// --------------------------------------------------
			//  {member_profile_link}
			// --------------------------------------------------
	
			if ($SESS->userdata['member_id'] != 0)
			{
				$name = ($SESS->userdata['screen_name'] == '') ? $SESS->userdata['username'] : $SESS->userdata['screen_name'];
				
				$path = "<a href='".$FNS->create_url('/member/'.$SESS->userdata['member_id'])."'>".$name."</a>";
				
				$buffer = str_replace(LD.'member_profile_link'.RD, $path, $buffer);
			}
			else
			{
				$buffer = str_replace(LD.'member_profile_link'.RD, '', $buffer);
			}
	
            
            // -----------------------------------
            // Parse {if logged_in} conditional
            // -----------------------------------
                
            if (preg_match_all("/".LD."if\s+logged_in\s*".RD."(.*?)".LD."\/if".RD."/is", $buffer, $matches))
            {
                foreach ($matches['1'] as $val)
                {
                    if ($SESS->userdata['member_id'] != 0)
                    {
                        $buffer = preg_replace("/".LD."if\s+logged_in\s*".RD.".*?".LD."\/if".RD."/is", $val, $buffer, 1);
                    }
                    else
                    {
                        $buffer = preg_replace("/".LD."if\s+logged_in\s*".RD.".*?".LD."\/if".RD."/is", '', $buffer, 1);
                    }
                }
            }
            
            // -----------------------------------
            // Parse {if logged_out} conditional
            // -----------------------------------
            
            if (preg_match_all("/".LD."if\s+logged_out\s*".RD."(.*?)".LD."\/if".RD."/is", $buffer, $matches))
            {
                foreach ($matches['1'] as $val)
                {
                    if ($SESS->userdata['member_id'] != 0)
                    {
                        $buffer = preg_replace("/".LD."if\s+logged_out\s*".RD.".*?".LD."\/if".RD."/is", '', $buffer, 1);
                    }
                    else
                    {
                        $buffer = preg_replace("/".LD."if\s+logged_out\s*".RD.".*?".LD."\/if".RD."/is", $val, $buffer, 1);
                    }
                }
            }
        }
        
        // -----------------------------------
        // Stop the benchmark
        // -----------------------------------
        
        $BM->mark('end');
        
        if (REQ == 'CP')
        {
            $buffer = str_replace($TMPL->l_delim.'cp:elapsed_time'.$TMPL->r_delim, $BM->elapsed('start', 'end'), $buffer);
        }
        else
        {
            $buffer = str_replace($TMPL->l_delim.'elapsed_time'.$TMPL->r_delim, $BM->elapsed('start', 'end'), $buffer);

            // --------------------------------------
            //  Remove bad variables
            // --------------------------------------
            
			// If 'debug' is turned off, we will remove any variables that didn't get parsed due to syntax errors.
	
			if ($PREFS->ini('debug') == 0)
			{
				$buffer =& preg_replace("/".LD.".*?".RD."/", '', $buffer);
			}
        }
        
        // -----------------------------------
        //  Compress the output
        // -----------------------------------
                
        // Note: Mozilla seems to have a problem with this. Investigate...
        
        if ($PREFS->ini('gzip_output') == 'y')
        {
            ob_start('ob_gzhandler');
        }        


        // -----------------------------------
        // Send it to the browser
        // -----------------------------------
        
        echo $buffer;
        
        
        // ---------------------------------------
        // Show queries if enabled for debugging
        // ---------------------------------------
        
        // For security reasons, we won't show the queries 
        // unless the current user is a logged-in Super Admin

        if ($DB->show_queries === TRUE)
        {
        	if ($SESS->userdata['group_id'] == 1)
        	{				
				$i = 1;
				
				$highlight = array('SELECT', 'FROM', 'WHERE', 'AND', 'LEFT JOIN', 'ORDER BY', 'LIMIT', 'INSERT', 'INTO', 'VALUES', 'UPDATE');
				
				foreach ($DB->queries as $val)
				{
					foreach ($highlight as $bold)
					{
						$val = str_replace($bold, '<b>'.$bold.'</b>', $val);	
					}
									
					echo "<div style=\"text-align: left; font-face: Sans-serif; font-size: 11px; margin: 12px; padding: 6px\"><hr size='1'>";
					echo "<h5>".$i.'</h5>';
					echo str_replace("\t", " ", $val);
					echo "</div>";
					
					$i++;
				}
			}
        }
    }
    // END
    


    // -------------------------------------------
    //    Display fatal error message
    // -------------------------------------------    
    
    function fatal_error($error_msg = '')
    {
        global $LANG, $REGX;
        
        $heading = (isset($LANG)) ? $LANG->line('error') : 'Error Message';
        
		$data = array(	'title' 	=> $heading,
						'heading'	=> $heading,
						'content'	=> '<p>'.$error_msg.'</p>'
					 );
										
		$this->show_message($data);
    }
    // END
    

    // -------------------------------------------
    //    System is off message
    // -------------------------------------------    
    
    function system_off_msg()
    {
        global $LANG, $DB, $PREFS;
        
		$query = $DB->query("SELECT template_data FROM exp_specialty_templates WHERE template_name = 'offline_template'");
		
		echo $query->row['template_data'];
		exit;                        
    }
    // END


    // ----------------------------------------
    //  Show message
    // ----------------------------------------
    
    // This function and the next enable us to show error
    // messages to users when needed.  For example, when
    // a form is submitted without the required info.
    
    // This is not used in the control panel, only with
    // publicly accessible pages.
     
    function show_message($data, $xhtml = TRUE)
    {
    	global $LANG, $DB;	
    	
    	$title		= ( ! isset($data['title']))	? ''  : $data['title'];
    	$heading	= ( ! isset($data['heading']))	? ''  : '<h1>'.$data['heading'].'</h1>';
    	$content	= ( ! isset($data['content']))	? ''  : $data['content'];
    	$redirect	= ( ! isset($data['redirect']))	? ''  : $data['redirect'];
    	$rate		= ( ! isset($data['rate']))		? '3' : $data['rate'];
    	$link		= ( ! isset($data['link']))		? ''  : $data['link'];
    	
    	$meta_refresh = ($redirect != '') ? "<meta http-equiv='refresh' content='".$rate."; url=".$redirect."'>" : '';
    	$refresh_msg  = ($redirect != '') ? "\n\n".str_replace("%x", $rate, $LANG->line('auto_redirection')) : '';
    	
    	if ($link != '')
    	{
    		$link = "<a href='".$link['0']."'>".$link['1']."</a>";
    	}
    	    	
    	$content = $content.$refresh_msg;
    	
    	if ($xhtml == TRUE)
    	{
			if ( ! class_exists('Typography'))
			{
				require PATH_CORE.'core.typography'.EXT;
			}
			
			$TYPE = new Typography;
	
			$content = $TYPE->parse_type(stripslashes($content), array('text_format' => 'xhtml'));
		}   	
    	    	
		$query = $DB->query("SELECT template_data FROM exp_specialty_templates WHERE template_name = 'message_template'");
		
		$template = $query->row['template_data'];
    	
		$template = str_replace('{title}', 			$title, 		$template);
		$template = str_replace('{meta_refresh}', 	$meta_refresh,	$template);
		$template = str_replace('{heading}',	 	$heading, 		$template);
		$template = str_replace('{content}', 		$content, 		$template);
		$template = str_replace('{link}', 			$link, 			$template);
        
        echo $template;
        exit;
    } 
    // END
    
  
    // ----------------------------------------
    //  Show user error
    // ----------------------------------------
 
    function show_user_error($type = 'submission', $errors, $heading = '')
    {
        global $LANG;
         
		if ($type != 'off')
		{      
			switch($type)
			{
				case 'submission' : $heading = $LANG->line('submission_error');
					break;
				case 'general'    : $heading = $LANG->line('general_error');
					break;
				default           : $heading = $LANG->line('submission_error');
					break;
			}
    	}
        
        $content  = '<ul>';
        
        if ( ! is_array($errors))
        {
			$content.= "<li>".$errors."</li>\n";
        }
		else
		{
			foreach ($errors as $val)
			{
				$content.= "<li>".$val."</li>\n";
			}
        }
        
        $content .= "</ul>";
        
        $data = array(	'title' 	=> $LANG->line('error'),
        				'heading'	=> $heading,
        				'content'	=> $content,
        				'redirect'	=> '',
        				'link'		=> array('JavaScript:history.go(-1)', $LANG->line('back'))
					 );
                
		$this->show_message($data, 0);
    } 
    // END

}
// END CLASS
?>