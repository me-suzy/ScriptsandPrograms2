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
 File: mod.rss.php
-----------------------------------------------------
 Purpose: RSS generating class
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Rss {

    // -------------------------------------
    //  RSS feed
    // -------------------------------------
    
    // This function fetches the weblog metadata used in
    // the channel section of RSS feeds
    
    // Note: The item elements are generated using the weblog class

    function feed()
    {
        global $DB, $LOC, $LANG, $TMPL, $FNS, $OUT;
        
        if (USER_BLOG !== FALSE)
        {
            $weblog = USER_BLOG;
        }
        else
        {
            if ( ! $weblog = $TMPL->fetch_param('weblog'))
            {
            	$LANG->fetch_language_file('rss');
                $OUT->fatal_error($LANG->line('rss_invalid_weblog'));
                exit;
            }
        }        
        
        $sql = "SELECT 	exp_weblogs.weblog_id, exp_weblogs.blog_title, exp_weblogs.blog_url, exp_weblogs.blog_lang, exp_weblogs.blog_encoding, exp_weblogs.blog_description,
                       	exp_weblog_titles.entry_date, exp_weblog_titles.edit_date,
                      	exp_members.email, exp_members.username, exp_members.screen_name, exp_members.url
                FROM	exp_weblogs, exp_weblog_titles, exp_members
                WHERE	exp_weblogs.weblog_id = exp_weblog_titles.weblog_id
                AND		exp_weblog_titles.author_id = exp_members.member_id
				AND		exp_weblog_titles.entry_date <  {$LOC->now} 
 				AND 	(exp_weblog_titles.expiration_date = 0 || exp_weblog_titles.expiration_date > {$LOC->now}) ";
                
        if (USER_BLOG !== FALSE)
        {        
            $sql .= "AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' ";
        }
        else
        {
            $sql .= "AND exp_weblogs.is_user_blog = 'n' ";
        
			$xql = "SELECT weblog_id FROM exp_weblogs WHERE ";
		
			$str = $FNS->sql_andor_string($weblog, 'blog_name');
			
			if (substr($str, 0, 3) == 'AND')
				$str = substr($str, 3);
			
			$xql .= $str;            
				
			$query = $DB->query($xql);
			
			if ($query->num_rows == 0)
			{
            	$LANG->fetch_language_file('rss');
                $OUT->fatal_error($LANG->line('rss_invalid_weblog'));
                exit;
			}
			
			if ($query->num_rows == 1)
			{
				$sql .= "AND exp_weblog_titles.weblog_id = '".$query->row['weblog_id']."' ";
			}
			else
			{
				$sql .= "AND (";
				
				foreach ($query->result as $row)
				{
					$sql .= "exp_weblog_titles.weblog_id = '".$row['weblog_id']."' OR ";
				}
				
				$sql = substr($sql, 0, - 3);
				
				$sql .= ") ";
			}
        }
                
        // ----------------------------------------------
        // Add status declaration
        // ----------------------------------------------
        
		$sql .= "AND exp_weblog_titles.status != 'closed' ";
        
        if ($status = $TMPL->fetch_param('status'))
        {
        	$status = str_replace('Open',   'open',   $status);
        	$status = str_replace('Closed', 'closed', $status);
        
            $sql .= $FNS->sql_andor_string($status, 'exp_weblog_titles.status');
        }
        else
        {
            $sql .= "AND exp_weblog_titles.status = 'open' ";
        }
                
        // ----------------------------------------------
        // Limit to (or exclude) specific users
        // ----------------------------------------------
        
        if ($username = $TMPL->fetch_param('username'))
        {
            // Shows entries ONLY for currently logged in user
        
            if ($username == 'CURRENT_USER')
            {
                $sql .=  "AND exp_members.member_id = '".$SESS->userdata['member_id']."' ";
            }
            elseif ($username == 'NOT_CURRENT_USER')
            {
                $sql .=  "AND exp_members.member_id != '".$SESS->userdata['member_id']."' ";
            }
            else
            {                
                $sql .= $FNS->sql_andor_string($username, 'exp_members.username');
            }
        }
                  
        $sql .= " ORDER BY exp_weblog_titles.entry_date desc LIMIT 1";
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == 0)
        {
			$LANG->fetch_language_file('rss');
			$OUT->fatal_error($LANG->line('rss_invalid_weblog'));
			exit;
        }
        
        foreach ($query->row as $key => $val)
        {
            $$key = $val;
        }
        

        foreach ($TMPL->var_single as $key => $val)
        {    
        
            // ----------------------------------------
            //  {weblog_id}
            // ----------------------------------------
            
            if (ereg("weblog_id", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $weblog_id, 
                                                            $TMPL->tagdata
                                                        );
            }


            // ----------------------------------------
            //  {encoding}
            // ----------------------------------------
            
            if (ereg("encoding", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $blog_encoding, 
                                                            $TMPL->tagdata
                                                        );
            }


            // ----------------------------------------
            //  {weblog_language}
            // ----------------------------------------
            
            if (ereg("weblog_language", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $blog_lang, 
                                                            $TMPL->tagdata
                                                        );
            }


            // ----------------------------------------
            //  {weblog_description}
            // ----------------------------------------
            
            if (ereg("weblog_description", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $blog_description, 
                                                            $TMPL->tagdata
                                                        );
            }
            
            // ----------------------------------------
            //  {weblog_url}
            // ----------------------------------------
            
            if ($key == 'weblog_url')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $blog_url, 
                                                            $TMPL->tagdata
                                                        );
            }

            // ----------------------------------------
            //  {weblog_name}
            // ----------------------------------------
            
            if ($key == 'weblog_name')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $blog_title, 
                                                            $TMPL->tagdata
                                                        );
            }


            // ----------------------------------------
            //  {email}
            // ----------------------------------------
            
            if ($key == 'email')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $email, 
                                                            $TMPL->tagdata
                                                        );
            }
            
            
            // ----------------------------------------
            //  {url}
            // ----------------------------------------
            
            if ($key == 'url')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $url, 
                                                            $TMPL->tagdata
                                                        );
            }

            // ----------------------------------------
            //  {date}
            // ----------------------------------------
            
            if (ereg("^date", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $entry_date), 
                                                            $TMPL->tagdata
                                                        );
            }
            
            // ----------------------------------------
            //  {edit_date}
            // ----------------------------------------
            
            if (ereg("^edit_date", $key))
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $LOC->timestamp_to_gmt($edit_date)), 
                                                            $TMPL->tagdata
                                                        );
            }
            
            // ----------------------------------------
            //  {author}
            // ----------------------------------------
            
            if ($key == 'author')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single($val, ($screen_name != '') ? $screen_name : $username, $TMPL->tagdata);
            }
            
            // ----------------------------------------
            //  {version}
            // ----------------------------------------
            
            if ($key == 'version')
            {                     
                $TMPL->tagdata =& $TMPL->swap_var_single($val, APP_VER, $TMPL->tagdata);
            }
                        
			// ----------------------------------------
			//  {trimmed_url} - used by Atom feeds
			// ----------------------------------------
			
			if ($key == "trimmed_url")
			{
				$blog_url = (isset($blog_url) AND $blog_url != '') ? $blog_url : '';
			
				$blog_url = str_replace('http://', '', $blog_url);
				$blog_url = str_replace('www.', '', $blog_url);
				$blog_url = current(explode("/", $blog_url));
			
				$TMPL->tagdata =& $TMPL->swap_var_single($val, $blog_url, $TMPL->tagdata);
			}			
        }

        return trim($TMPL->tagdata);
    }
    // END
    
}
// END CLASS
?>