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
 File: mod.comment.php
-----------------------------------------------------
 Purpose: Commenting class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Comment {

	// Maximum number of comments.  This is a safety valve
	// in case the user doesn't specify a maximum

	var $limit		= 600;
	

    // ----------------------------------------
    //  Comment Entries
    // ----------------------------------------

    function entries()
    {
        global $IN, $DB, $TMPL, $LOC, $REGX, $FNS;
        
		// --------------------------------------
		//  No Query String?  Why bother...
		// --------------------------------------
                                
        if ($IN->QSTR == '')
        {
            return false;
        }
        
        // Base variables
        
        $return 		= '';
        $current_page	= '';
        $qstring		= $IN->QSTR;
        $uristr			= $IN->URI;
        $switch 		= array();
        
        // Pagination variables
        
    	$paginate			= FALSE;
    	$paginate_data		= '';
    	$pagination_links	= '';
    	$page_next			= '';
    	$page_previous		= '';
		$current_page		= 0;
		$t_current_page		= '';
		$total_pages		= 1;
		
                
		// --------------------------------------
		//  Parse page number
		// --------------------------------------
		
		// We need to strip the page number from the URL for two reasons:
		// 1. So we can create pagination links
		// 2. So it won't confuse the query with an improper proper ID
		
		if (preg_match("#/P(\d+)#", $qstring, $match))
		{
			$current_page = $match['1'];	
			
			$uristr  = $FNS->remove_double_slashes(str_replace($match['0'], '', $uristr));
			$qstring = $FNS->remove_double_slashes(str_replace($match['0'], '', $qstring));
		}
                
        $entry_id = trim($qstring);
         
		// If there is a slash in the entry ID we'll kill everything after it.
 		
 		$entry_id = preg_replace("#/.+?#", "", $entry_id);
         
        // ----------------------------------------
        // Do we have a vaild weblog and ID number?
        // ----------------------------------------
        
		$timestamp = ($TMPL->cache_timestamp != '') ? $LOC->set_gmt($TMPL->cache_timestamp) : $LOC->now;
                
        $sql = "SELECT entry_id FROM exp_weblog_titles, exp_weblogs 
        		WHERE exp_weblog_titles.weblog_id = exp_weblogs.weblog_id 
        		AND (expiration_date = 0 || expiration_date > ".$timestamp.") 
        		AND status != 'closed' AND ";
        
		$sql .= ( ! is_numeric($entry_id)) ? " url_title = '".$entry_id."' " : " entry_id = '$entry_id' ";
		
		if (USER_BLOG === FALSE) 
		{
			$sql .= " AND exp_weblogs.is_user_blog = 'n' ";
			
            if ($blog_name = $TMPL->fetch_param('weblog'))
            {
                $sql .= $FNS->sql_andor_string($blog_name, 'blog_name', 'exp_weblogs');
            }
		}
		else
		{
			$sql .= " AND exp_weblogs.weblog_id = '".UB_BLOG_ID."' ";		
		}
                
        $query = $DB->query($sql);
        
        // Bad ID?  See ya!
        
        if ($query->num_rows == 0)
        {
        	return false;
        }
        unset($sql);
        
        // We'll reassign the entry ID so it's the true numeric ID
                        
		$entry_id = $query->row['entry_id'];
		
		
		// ----------------------------------------
		//  Fetch custom member field IDs
		// ----------------------------------------
	
		$query = $DB->query("SELECT m_field_id, m_field_name FROM exp_member_fields");
				
		$mfields = array();
		
		if ($query->num_rows > 0)
		{
			foreach ($query->result as $row)
			{        		
				$mfields[$row['m_field_name']] = $row['m_field_id'];
			}
		}
		
        // ---------------------------------
        //  Build master query
        // ---------------------------------
        
		$limit = ( ! $TMPL->fetch_param('limit')) ? $this->limit : $TMPL->fetch_param('limit');
        $sort  = ( ! $TMPL->fetch_param('sort'))  ? 'desc' : $TMPL->fetch_param('sort');
        
        // Hat tip to Yoshi (http://psychodaisy.com/) for letting me steal
        // this query from his plugin
		        
		$f_sql = "SELECT  exp_trackbacks.*,
					exp_comments.*,
					exp_members.location, exp_members.interests, exp_members.aol_im, exp_members.yahoo_im, exp_members.msn_im, exp_members.icq, exp_members.group_id, exp_members.member_id,
					exp_member_data.*,
					exp_weblogs.comment_text_formatting, exp_weblogs.comment_html_formatting, exp_weblogs.comment_allow_img_urls, exp_weblogs.comment_auto_link_urls ";
		
		$p_sql = "SELECT COUNT(*) AS count ";	
				
		$sql = "FROM exp_temp_union AS D
				LEFT JOIN exp_trackbacks ON (D.num=0)
				LEFT JOIN exp_comments ON (D.num=1)
				LEFT JOIN exp_weblogs ON (exp_comments.weblog_id = exp_weblogs.weblog_id OR exp_trackbacks.weblog_id=exp_weblogs.weblog_id)
				LEFT JOIN exp_members ON exp_members.member_id = exp_comments.author_id 
				LEFT JOIN exp_member_data ON exp_member_data.member_id = exp_members.member_id
				WHERE D.num < 2 
				AND IFNULL(exp_trackbacks.entry_id, exp_comments.entry_id) ='$entry_id'
				AND IFNULL(exp_comments.status, exp_trackbacks.trackback_id)  !='c' 
				ORDER BY COALESCE(exp_comments.comment_date, exp_trackbacks.trackback_date) {$sort} ";
								        
        // ---------------------------------
        //  Do we have pagination data?
        // ---------------------------------	
        		
		if (preg_match("/".LD."paginate".RD."(.+?)".LD.SLASH."paginate".RD."/s", $TMPL->tagdata, $match))
		{
			$paginate		= TRUE;
			$paginate_data	= $match['1'];
		
			$TMPL->tagdata = preg_replace("/".LD."paginate".RD.".+?".LD.SLASH."paginate".RD."/s", "", $TMPL->tagdata);
			
			$query = $DB->query($p_sql.$sql);
			
			$total_rows = $query->row['count'];
			
			$current_page = ($current_page == '' || ($limit > 1 AND $current_page == 1)) ? 0 : $current_page;
			
			if ($current_page > $total_rows)
			{
				$current_page = 0;
			}
						
			$t_current_page = floor(($current_page / $limit) + 1);
			$total_pages	= intval(floor($total_rows / $limit));
			
			if ($total_rows % $limit) 
				$total_pages++;
			
			if ($total_rows > $limit)
			{
				if ( ! class_exists('Paginate'))
				{
					require PATH_CORE.'core.paginate'.EXT;
				}
				
				$PGR = new Paginate();

				$basepath = $FNS->create_url($uristr, 1, 0);
				
				$first_url = (ereg("\.php/$", $basepath)) ? substr($basepath, 0, -1) : $basepath;
				
				$PGR->first_url 	= $first_url;
				$PGR->path			= $basepath;
				$PGR->prefix		= 'P';
				$PGR->total_count 	= $total_rows;
				$PGR->per_page		= $limit;
				$PGR->cur_page		= $current_page;
				
				$pagination_links = $PGR->show_links();
				
				if ((($total_pages * $limit) - $limit) > $current_page)
				{
					$page_next = $basepath.'P'.($current_page + $limit).'/';
				}
				
				if (($current_page - $limit ) >= 0) 
				{						
					$page_previous = $basepath.'P'.($current_page - $limit).'/';
				}
			}
			else
			{
				$current_page = '';
			}
		}
		
        // -----------------------------------
        //  Finalize master query and run it
        // -----------------------------------	
                
		$sql .= ($current_page == '') ? " LIMIT ".$limit : " LIMIT ".$current_page.', '.$limit;
                
        $query = $DB->query($f_sql.$sql);
        
        if ($query->num_rows == 0)
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
                
        $TYPE = new Typography(0); 
        
        // ----------------------------------------
        //  Set trackback flag
        // ---------------------------------------- 
        
        // Depending on whether the {if trackbacks} conditional
        // is present we'll set whether we need to show trackbacks
        
        $show_trackbacks = (preg_match("/".LD."if\s+trackbacks".RD.".+?".LD.SLASH."if".RD."/s", $TMPL->tagdata)) ? TRUE : FALSE;
                
        // ----------------------------------------
        //  Start the processing loop
        // ----------------------------------------        
        
        foreach ($query->result as $row)
        {
            // Skip the iteration if we aren't supposed to show trackbacks on any given cycle
        
        	if ($show_trackbacks == FALSE AND $row['trackback_id'] != '')
        		continue;
        	        
            $tagdata =& $TMPL->tagdata;     
            
            // ----------------------------------------
            //   Parse conditional pairs
            // ----------------------------------------

            foreach ($TMPL->var_cond as $val)
            {                
                // ----------------------------------------
                //   {if comments} - for  combined C/TB
                // ----------------------------------------

				if (preg_match("/^if\s+comments.*/i", $val['0']))
                {                
                    $rep = ( ! isset($row['comment_id']) || $row['comment_id'] == '' || $row['comment_id'] == 0) ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
                }
                
                // ----------------------------------------
                //   {if trackbacks} - for  combined C/TB
                // ----------------------------------------

				if (preg_match("/^if\s+trackbacks.*/i", $val['0']))
                {   
                    $rep = ( ! isset($row['trackback_id']) || $row['trackback_id'] == '' || $row['trackback_id'] == 0) ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
                }
                  
                // ----------------------------------------
                //   Scripted conditionals
                // ----------------------------------------
                            
                $cond = preg_replace("/^if/", "", $val['0']);
                
                // Since we allow the following shorthand condition: {if username}
                // but it's not legal PHP, we'll correct it by adding:  != ''
                
                if ( ! ereg("\|", $cond))
                {                    
                    if ( ! preg_match("/(\!=|==|<|>|<=|>=|<>)/s", $cond))
                    {
                        $cond .= " != ''";
                    }
                }
								
				if ( isset($row[$val['3']]))
				{  
					$cond =& str_replace($val['3'], "\$row['".$val['3']."']", $cond);
					  
					$cond =& str_replace("\|", "|", $cond);
							 
					eval("\$result = ".$cond.";");
										
					if ($result)
					{
						$tagdata =& str_replace($val['1'], $val['2'], $tagdata);                 
					}
					else
					{
						$tagdata =& str_replace($val['1'], '', $tagdata);                 
					}   
				}
				elseif (isset($mfields[$val['3']]))
				{
					//  Parse conditions in custom member fields
				
					if (isset($row['m_field_id_'.$mfields[$val['3']]]))
					{
						$v = $row['m_field_id_'.$mfields[$val['3']]];
									 
						$cond =& str_replace($val['3'], "\$v", $cond);
						
						$cond =& str_replace("\|", "|", $cond);
								 
						eval("\$result = ".$cond.";");
											
						if ($result)
						{
							$tagdata =& str_replace($val['1'], $val['2'], $tagdata);                 
						}
						else
						{ 
							$tagdata =& str_replace($val['1'], '', $tagdata);                 
						}   
					}
				}                        
            
				// ----------------------------------------
				//   {if LOGGED_IN}
				// ----------------------------------------
			
				if (preg_match("/^if\s+LOGGED_IN.*/i", $val['0']))
				{
					$rep = ($SESS->userdata['member_id'] == 0) ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata); 
				}
				
				// ----------------------------------------
				//   {if NOT_LOGGED_IN}
				// ----------------------------------------
	
				if (preg_match("/^if\s+NOT_LOGGED_IN.*/i", $val['0']))
				{
					$rep = ($SESS->userdata['member_id'] != 0) ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
				}
                        
                // ----------------------------------------
                //   {if allow_comments}
                // ----------------------------------------

				if (preg_match("/^if\s+allow_comments.*/i", $val['0']))
                {                
                    $rep = ($row['allow_comments'] == 'n') ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
                }
                
                // ----------------------------------------
                //   {if allow_trackbacks}
                // ----------------------------------------

				if (preg_match("/^if\s+allow_trackbacks.*/i", $val['0']))
                {   
                    $rep = ($row['allow_trackbacks'] == 'n') ? '' : $val['2'];
					
					$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
                }
            }
            // END CONDITIONAL PAIRS
            
                        
     
            // ----------------------------------------
            //   Parse "single" variables
            // ----------------------------------------

            foreach ($TMPL->var_single as $key => $val)
            { 
            
				// ----------------------------------------
				//  parse {switch} variable
				// ----------------------------------------
				
				if (ereg("^switch", $key))
				{
					$sparam =& $TMPL->assign_parameters($key);
					
					$sw = '';

					if (isset($sparam['switch']))
					{
						$sopt = explode("|", $sparam['switch']);
						
						if (count($sopt) == 2)
						{
							if (isset($switch[$sparam['switch']]) AND $switch[$sparam['switch']] == $sopt['0'])
							{
								$switch[$sparam['switch']] = $sopt['1'];
								
								$sw = $sopt['1'];									
							}
							else
							{
								$switch[$sparam['switch']] = $sopt['0'];
								
								$sw = $sopt['0'];									
							}
						}
					}
					
					$tagdata =& $TMPL->swap_var_single($key, $sw, $tagdata);
				}
              
            
            
                // ----------------------------------------
                //  parse permalink
                // ----------------------------------------
                
                if (ereg("^permalink", $key))
                {                     
                        $tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $FNS->create_url($uristr.'#'.$row['comment_id'], 0, 0), 
                                                            $tagdata
                                                         );
                }
            
                // ----------------------------------------
                //  parse comment date
                // ----------------------------------------
                
                if (ereg("^comment_date", $key))
                {                     
                        $tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $row['comment_date']), 
                                                            $tagdata
                                                         );
                }
                
                // ----------------------------------------
                //  parse trackback date
                // ----------------------------------------
                
                if (ereg("^trackback_date", $key))
                {
                        $tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $row['trackback_date']), 
                                                            $tagdata
                                                          );
                }
                
                // ----------------------------------------
                //  parse "last edit" date
                // ----------------------------------------
                
                if (ereg("^edit_date", $key))
                {                     
                        $tagdata =& $TMPL->swap_var_single(
                                                            $key, 
                                                            $LOC->decode_date($val, $row['edit_date']), 
                                                            $tagdata
                                                         );
                }
                
                
                // Prep the URL
                
                $row['url'] = $REGX->prep_url($row['url']);
            

                // ----------------------------------------
                //  {url_or_email}
                // ----------------------------------------
                
                if ($key == "url_or_email")
                {
                    $tagdata =& $TMPL->swap_var_single($val, ($row['url'] != '') ? $row['url'] : $TYPE->encode_email($row['email'], '', 0), $tagdata);
                }


                // ----------------------------------------
                //  {url_or_email_as_author}
                // ----------------------------------------
                
                if ($key == "url_or_email_as_author")
                {                    
                    if ($row['url'] != '')
                    {
                        $tagdata =& $TMPL->swap_var_single($val, "<a href=\"".$row['url']."\">".$row['name']."</a>", $tagdata);
                    }
                    else
                    {
                        $tagdata =& $TMPL->swap_var_single($val, $TYPE->encode_email($row['email'], $row['name']), $tagdata);
                    }
                }
                
                // ----------------------------------------
                //  {url_or_email_as_link}
                // ----------------------------------------
                
                if ($key == "url_or_email_as_link")
                {                    
                    if ($row['url'] != '')
                    {
                        $tagdata =& $TMPL->swap_var_single($val, "<a href=\"".$row['url']."\">".$row['url']."</a>", $tagdata);
                    }
                    else
                    {                        
                        $tagdata =& $TMPL->swap_var_single($val, $TYPE->encode_email($row['email']), $tagdata);
                    }
                }
               
                // ----------------------------------------
                //  parse comment field
                // ----------------------------------------
                
                if ($key == 'comment')
                {
                    $comment =& $TYPE->parse_type( $row['comment'], 
                                                   array(
                                                            'text_format'   => $row['comment_text_formatting'],
                                                            'html_format'   => $row['comment_html_formatting'],
                                                            'auto_links'    => $row['comment_auto_link_urls'],
                                                            'allow_img_url' => $row['comment_allow_img_urls']
                                                        )
                                                );
                
                    $tagdata =& $TMPL->swap_var_single($key, $comment, $tagdata);                
                }

                // ----------------------------------------
                //  parse basic fields
                // ----------------------------------------
                 
                if (isset($row[$val]))
                {                    
                    $tagdata =& $TMPL->swap_var_single($val, $row[$val], $tagdata);
                }
                
                // ----------------------------------------
                //  parse custom member fields
                // ----------------------------------------
                                
                if ( isset( $mfields[$val] ) AND isset( $row['m_field_id_'.$mfields[$val]] ) )
                {
                    $tagdata =& $TMPL->swap_var_single(
                                                        $val, 
                                                        $row['m_field_id_'.$mfields[$val]], 
                                                        $tagdata
                                                      );
                }
                
                    
            }        
        
            $return .= "<a name=\"".$row['comment_id']."\"></a>\n".$tagdata;
        }
        
		// ----------------------------------------
		//  Clean up left over variables
		// ----------------------------------------
		
		// Since comments do not necessarily require registration, and since
		// you are allowed to put member variables in comments, we need to kill
		// left-over unparsed junk
		
		$return = preg_replace("/".LD."if.*?".RD.".+?".LD.SLASH."if".RD."/s", '', $return);
		$return = preg_replace("/".LD.".+?".RD."/", '', $return);

		// ----------------------------------------
		//  Add pagination to result
		// ----------------------------------------

        if ($paginate == TRUE)
        {
        	$paginate_data = str_replace(LD.'current_page'.RD, 		$t_current_page, 	$paginate_data);
        	$paginate_data = str_replace(LD.'total_pages'.RD,		$total_pages,  		$paginate_data);
        	$paginate_data = str_replace(LD.'pagination_links'.RD,	$pagination_links,	$paginate_data);
        	
        	if (preg_match("/".LD."if previous_page".RD."(.+?)".LD.SLASH."if".RD."/s", $paginate_data, $match))
        	{
        		if ($page_previous == '')
        		{
        			 $paginate_data = preg_replace("/".LD."if previous_page".RD.".+?".LD.SLASH."if".RD."/s", '', $paginate_data);
        		}
        		else
        		{
					$match['1'] = str_replace(LD.'path'.RD, $page_previous, $match['1']);
				
					$paginate_data = str_replace($match['0'],	$match['1'], $paginate_data);
				}
        	}
        	
        	if (preg_match("/".LD."if next_page".RD."(.+?)".LD.SLASH."if".RD."/s", $paginate_data, $match))
        	{
        		if ($page_next == '')
        		{
        			 $paginate_data = preg_replace("/".LD."if next_page".RD.".+?".LD.SLASH."if".RD."/s", '', $paginate_data);
        		}
        		else
        		{
					$match['1'] = str_replace(LD.'path'.RD, $page_next, $match['1']);
				
					$paginate_data = str_replace($match['0'],	$match['1'], $paginate_data);
				}
        	}
        
			$position = ( ! $TMPL->fetch_param('paginate')) ? '' : $TMPL->fetch_param('paginate');
			
			switch ($position)
			{
				case "top"	: $return  = $paginate_data.$return;
					break;
				case "both"	: $return  = $paginate_data.$return.$paginate_data;
					break;
				default		: $return .= $paginate_data;
					break;
			}
        }	
        
        return $return;
    }
    // END



    // ----------------------------------------
    //  Comment Submission Form
    // ----------------------------------------

    function form()
    {
        global $IN, $FNS, $PREFS, $SESS, $TMPL, $DB, $REGX;
        
        $qstring = $IN->QSTR;
                
		// --------------------------------------
		//  Remove page number
		// --------------------------------------
		
		if (preg_match("#/P\d+#", $qstring, $match))
		{			
			$qstring = $FNS->remove_double_slashes(str_replace($match['0'], '', $qstring));
		}
		        
 		$entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] : $qstring;
 		
		// If there is a slash in the entry ID we'll kill everything after it.
 		
 		$entry_id = preg_replace("#/.+?#", "", $entry_id);
 		
        // ----------------------------------------
        //   Are comments allowed?
        // ----------------------------------------        
        
        $sql = "SELECT entry_id, allow_comments FROM exp_weblog_titles ";
                
        if ( ! is_numeric($entry_id))
        {
            $sql .= " WHERE url_title = '".$DB->escape_str($entry_id)."' ";
        }
        else
        {
			$sql .= " WHERE entry_id = '$entry_id' ";
		}
        
        $query = $DB->query($sql);

        if ($query->num_rows == 0)
        {
            return false;
        }
        
        if ($query->row['allow_comments'] == 'n')
        {
            return false;
        }
        
   
        $tagdata = $TMPL->tagdata; 
        
    
        // ----------------------------------------
        //   Parse conditional pairs
        // ----------------------------------------

        foreach ($TMPL->var_cond as $val)
        {                
			// ----------------------------------------
			//   {if LOGGED_IN}
			// ----------------------------------------
		
            if (preg_match("/^if\s+LOGGED_IN.*/i", $val['0']))
            {
				$rep = ($SESS->userdata['member_id'] == 0) ? '' : $val['2'];
				
				$tagdata =& str_replace($val['1'], $rep, $tagdata); 
			}
			
			// ----------------------------------------
			//   {if NOT_LOGGED_IN}
			// ----------------------------------------

            if (preg_match("/^if\s+NOT_LOGGED_IN.*/i", $val['0']))
            {
				$rep = ($SESS->userdata['member_id'] != 0) ? '' : $val['2'];
				
				$tagdata =& str_replace($val['1'], $rep, $tagdata);                 
			}
        }
        // END CONDITIONALS
             
                
        foreach ($TMPL->var_single as $key => $val)
        {              
            // ----------------------------------------
            //  parse {name}
            // ----------------------------------------
            
            if ($key == 'name')
            {
                $name = ($SESS->userdata['screen_name'] != '') ? $SESS->userdata['screen_name'] : $SESS->userdata['username'];
            
                $name = ( ! isset($_POST['name'])) ? $name : $_POST['name'];
            
                $tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($name), $tagdata);
            }
                    
            // ----------------------------------------
            //  parse {email}
            // ----------------------------------------
            
            if ($key == 'email')
            {
                $email = ( ! isset($_POST['email'])) ? $SESS->userdata['email'] : $_POST['email'];
              
                $tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($email), $tagdata);
            }

            // ----------------------------------------
            //  parse {url}
            // ----------------------------------------
            
            if ($key == 'url')
            {
                $url = ( ! isset($_POST['url'])) ? $SESS->userdata['url'] : $_POST['url'];
                
                if ($url == '')
                    $url = 'http://';

                $tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($url), $tagdata);
            }

            // ----------------------------------------
            //  parse {location}
            // ----------------------------------------
            
            if ($key == 'location')
            { 
                $location = ( ! isset($_POST['location'])) ? $SESS->userdata['location'] : $_POST['location'];

                $tagdata =& $TMPL->swap_var_single($key, $REGX->form_prep($location), $tagdata);
            }
          
            // ----------------------------------------
            //  parse {comment}
            // ----------------------------------------
            
            if ($key == 'comment')
            {
                $comment = ( ! isset($_POST['comment'])) ? '' : $_POST['comment'];
            
                $tagdata =& $TMPL->swap_var_single($key, $comment, $tagdata);
            }
            
            // ----------------------------------------
            //  parse {save_info}
            // ----------------------------------------
            
            if ($key == 'save_info')
            {
                $save_info = ( ! isset($_POST['save_info'])) ? '' : $_POST['save_info'];
                       
                $notify = ( ! isset($SESS->userdata['notify_by_default'])) ? $IN->GBL('save_info', 'COOKIE') : $SESS->userdata['notify_by_default'];
                        
                $checked   = ( ! isset($_POST['PRV'])) ? $notify : $save_info;
            
                $tagdata =& $TMPL->swap_var_single($key, ($checked == 'yes') ? "checked=\"checked\"" : '', $tagdata);
            }
            
            // ----------------------------------------
            //  parse {notify_me}
            // ----------------------------------------
            
            if ($key == 'notify_me')
            {
            	$checked = '';
            
                if (isset($SESS->userdata['notify_by_default']) AND $SESS->userdata['notify_by_default'] == 'y')
                {
                	$checked = 'yes';
                }
                
                if ($IN->GBL('notify_me', 'COOKIE'))
                {
                	$checked = $IN->GBL('notify_me', 'COOKIE');
                }
                
                if (isset($_POST['notify_me']))
                {
                	$checked = $_POST['notify_me'];
                }
            
                $tagdata =& $TMPL->swap_var_single($key, ($checked == 'yes') ? "checked=\"checked\"" : '', $tagdata);
            }
        }
        
        // ----------------------------------------
        //  Create form
        // ----------------------------------------
                
        $RET = (isset($_POST['RET'])) ? $_POST['RET'] : $FNS->fetch_current_uri();
        
        $PRV = (isset($_POST['PRV'])) ? $_POST['PRV'] : $REGX->trim_slashes($TMPL->fetch_param('preview'));
        
        $XID = ( ! isset($_POST['XID'])) ? '' : $_POST['XID'];
               
        $hidden_fields = array(
                                'ACT'      => $FNS->fetch_action_id('Comment', 'insert_new_comment'),
                                'RET'      => $RET,
                                'URI'      => ($IN->URI == '') ? 'index' : $IN->URI,
                                'PRV'      => $PRV,
                                'XID'      => $XID,
                                'entry_id' => $query->row['entry_id']
                              );            
                             
        $res  = $FNS->form_declaration($hidden_fields, '', 'comment_form');
        
        $res .= stripslashes($tagdata);
        
        $res .= "</form>"; 

        return $res;
    }
    // END




    // ----------------------------------------
    //  Preview
    // ----------------------------------------

    function preview()
    {
        global $IN, $TMPL, $DB;
        
        $entry_id = (isset($_POST['entry_id'])) ? $_POST['entry_id'] : $IN->QSTR;
        
        if ( ! is_numeric($entry_id))
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
        $TYPE->encode_email = FALSE;               
        
        $sql = "SELECT exp_weblogs.comment_text_formatting, exp_weblogs.comment_html_formatting, exp_weblogs.comment_allow_img_urls, exp_weblogs.comment_auto_link_urls
                FROM   exp_weblogs, exp_comments
                WHERE  exp_comments.weblog_id = exp_weblogs.weblog_id 
                AND    exp_comments.entry_id = '$entry_id'";        
        
        $query = $DB->query($sql);
        
        if ($query->num_rows == '')
        {
            $formatting = 'none';
        }
        else
        {
            $formatting = $query->row['comment_text_formatting'];
        }
        
        $comment = stripslashes($IN->GBL('comment', 'POST'));
                
        $tagdata = $TMPL->tagdata; 
        
        foreach ($TMPL->var_single as $key => $val)
        {              
            // ----------------------------------------
            //  parse comment field
            // ----------------------------------------
            
            if ($key == 'comment')
            { 
                $data =& $TYPE->parse_type( $comment, 
                                             array(
                                                    'text_format'   => $query->row['comment_text_formatting'],
                                                    'html_format'   => $query->row['comment_html_formatting'],
                                                    'auto_links'    => $query->row['comment_auto_link_urls'],
                                                    'allow_img_url' => $query->row['comment_allow_img_urls']
                                                   )
                                            );

                $tagdata =& $TMPL->swap_var_single($key, $data, $tagdata);                
            }
        }
        
        return $tagdata;
    }
    // END



    // ----------------------------------------
    //  Preview handler
    // ----------------------------------------

    function preview_handler()
    {
        global $IN, $OUT, $LANG, $OUT;
        
        if ($IN->GBL('PRV', 'POST') == '')
        {
            $error[] = $LANG->line('cmt_no_preview_template_specified');
            
            return $OUT->show_user_error('general', $error);        
        }
        
        require PATH_CORE.'core.template'.EXT;
        
        $T = new Template();
                
        global $TMPL;
               $TMPL = $T;
        
		$preview = ( ! $IN->GBL('PRV', 'POST')) ? '' : $IN->GBL('PRV', 'POST');

        if ( ! ereg("/", $preview))
        	$preview = '';
        else
        {
			$ex = explode("/", $preview);

			if (count($ex) != 2)
			{
				$preview = '';
			}
        }
        	        	
        if ($preview == '')
        {
        	$group = 'weblog';
        	$templ = 'preview';
        }
		else
		{
        	$group = $ex['0'];
        	$templ = $ex['1'];
		}        
            
        $TMPL->run_template_engine($group, $templ);
    }
    // END




    // ----------------------------------------
    //  Insert new comment
    // ----------------------------------------

    function insert_new_comment()
    {
        global $IN, $SESS, $PREFS, $DB, $FNS, $OUT, $LANG, $REGX, $LOC, $STAT;
    
        $default = array('name', 'email', 'url', 'comment', 'location');
        
        foreach ($default as $val)
        {
			if ( ! isset($_POST[$val]))
			{
				$_POST[$val] = '';
			}
        }        
                
        // If the comment is empty, bounce them back
        
        if ($_POST['comment'] == '')
        {
            $FNS->redirect($_POST['RET']);
        }
               
        // ----------------------------------------
        // Fetch the comment language pack
        // ----------------------------------------
        
        $LANG->fetch_language_file('comment');
        
                
        // ----------------------------------------
        // Is the user banned?
        // ----------------------------------------
        
        if ($SESS->userdata['is_banned'] == TRUE)
        {
            $error[] = $LANG->line('not_authorized');
            
            return $OUT->show_user_error('general', $error);
        }
                
        // ----------------------------------------
        //  Can the user post comments?
        // ----------------------------------------
        
        if ($SESS->userdata['can_post_comments'] == 'n')
        {
            $error[] = $LANG->line('cmt_no_authorized_for_comments');
            
            return $OUT->show_user_error('general', $error);
        }
         
        // ----------------------------------------
        // Is this a preview request?
        // ----------------------------------------
        
        if (isset($_POST['preview']))
        {            
            return $this->preview_handler();
        }
        
        // ----------------------------------------
        // Fetch weblog preferences
        // ----------------------------------------
        
        $sql = "SELECT exp_weblog_titles.title, 
                       exp_weblog_titles.url_title,
                       exp_weblog_titles.weblog_id,
                       exp_weblog_titles.comment_total,
                       exp_weblog_titles.allow_comments,
                       exp_weblogs.blog_title,
                       exp_weblogs.blog_url,
                       exp_weblogs.comment_system_enabled,
                       exp_weblogs.comment_max_chars,
                       exp_weblogs.comment_timelock,
                       exp_weblogs.comment_require_membership,
                       exp_weblogs.comment_moderate,
                       exp_weblogs.comment_require_email,
                       exp_weblogs.comment_notify,
                       exp_weblogs.comment_notify_emails
                FROM   exp_weblog_titles, exp_weblogs
                WHERE  exp_weblog_titles.weblog_id = exp_weblogs.weblog_id
                AND    exp_weblog_titles.entry_id = '".$DB->escape_str($_POST['entry_id'])."'";
                
        $query = $DB->query($sql);        
        
        unset($sql);
                
        if ($query->num_rows == 0)
        {
            return false;
        }

        // ----------------------------------------
        //   Are comments allowed?
        // ----------------------------------------

        if ($query->row['allow_comments'] == 'n' || $query->row['comment_system_enabled'] == 'n')
        {
            $error[] = $LANG->line('cmt_comments_not_allowed');
            
            return $OUT->show_user_error('submission', $error);
        }
                
        // ----------------------------------------
        //   Is there a comment timelock?
        // ----------------------------------------

        if ($query->row['comment_timelock'] != '' || $query->row['comment_timelock'] > 0)
        {
			if ($SESS->userdata['group_id'] != 1)        
			{
				$time = $LOC->now - $query->row['comment_timelock'];
			
				$result = $DB->query("SELECT count(*) AS count FROM exp_comments WHERE comment_date > '$time' AND ip_address = '$IN->IP' ");
			
				if ($result->row['count'] > 0)
				{
					$error[] = str_replace("%s", $query->row['comment_timelock'], $LANG->line('cmt_comments_timelock'));
					
					return $OUT->show_user_error('submission', $error);
				}
			}
        }
        
        // ----------------------------------------
        //   Do we allow dupllicate data?
        // ----------------------------------------

        if ($PREFS->ini('deny_duplicate_data') == 'y')
        {
			if ($SESS->userdata['group_id'] != 1)        
			{			
				$result = $DB->query("SELECT count(*) AS count FROM exp_comments WHERE comment = '".$DB->escape_str($_POST['comment'])."' ");
			
				if ($result->row['count'] > 0)
				{					
					return $OUT->show_user_error('submission', $LANG->line('cmt_duplicate_comment_warning'));
				}
			}
        }
                
        
        $entry_title        = $query->row['title'];
        $url_title	        = $query->row['url_title'];
        $blog_title         = $query->row['blog_title'];
        $weblog_id          = $query->row['weblog_id'];
        $blog_url	        = $query->row['blog_url'];
        $comment_total      = $query->row['comment_total'] + 1;
        $require_membership = $query->row['comment_require_membership'];
        $comment_moderate	= ($SESS->userdata['group_id'] != 1) ? $query->row['comment_moderate'] : 'n';
        $comment_notify		= $query->row['comment_notify'];
        $notify_address		= $query->row['comment_notify_emails'];
        
        
        // ----------------------------------------
        //  Start error trapping
        // ----------------------------------------        
        
        $error = array();
        
        if ($SESS->userdata['member_id'] != 0)        
        {
            // If the user is logged in we'll reassign the POST variables with the user data
            
             $_POST['name']     = ($SESS->userdata['screen_name'] != '') ? $SESS->userdata['screen_name'] : $SESS->userdata['username'];
             $_POST['email']    =  $SESS->userdata['email'];
             $_POST['url']      =  $SESS->userdata['url'];
             $_POST['location'] =  $SESS->userdata['location'];
        }
        
        
        // ----------------------------------------
        //  Is membership is required to post...
        // ----------------------------------------
        
        if ($require_membership == 'y')
        {        
            // Not logged in
        
            if ($SESS->userdata['member_id'] == 0)
            {
                $error[] = $LANG->line('cmt_must_be_member');
                
                return $OUT->show_user_error('submission', $error);
            }
            
            // Membership is pending
            
            if ($SESS->userdata['member_id'] == 3)
            {
                $error[] = $LANG->line('cmt_account_not_active');
                
                return $OUT->show_user_error('general', $error);
            }
                        
        }
        else
        {                              
            // ----------------------------------------
            //  Missing name?
            // ----------------------------------------
            
            if ($_POST['name'] == '')
            {
                $error[] = $LANG->line('cmt_missing_name');
            }
            
			// -------------------------------------
			//  Is name banned?
			// -------------------------------------
		
			if ($SESS->ban_check('screen_name', $_POST['name']))
			{
                $error[] = $LANG->line('cmt_name_not_allowed');
			}
            
            // ----------------------------------------
            //  Missing or invalid email address
            // ----------------------------------------
    
            if ($query->row['comment_require_email'] == 'y')
            {
                if ($_POST['email'] == '')
                {
                    $error[] = $LANG->line('cmt_missing_email');
                }
                elseif ( ! $REGX->valid_email($_POST['email']))
                {
                    $error[] = $LANG->line('cmt_invalid_email');
                }
            }
        }
        
		// -------------------------------------
		//  Is email banned?
		// -------------------------------------
		
		if ($_POST['email'] != '')
		{
			if ($SESS->ban_check('email', $_POST['email']))
			{
				$error[] = $LANG->line('cmt_banned_email');
			}
		}	
        
        // ----------------------------------------
        //  Is comment too big?
        // ----------------------------------------
        
        if ($query->row['comment_max_chars'] != '' AND $query->row['comment_max_chars'] != 0)
        {        
            if (strlen($_POST['comment']) > $query->row['comment_max_chars'])
            {
                $str = str_replace("%n", strlen($_POST['comment']), $LANG->line('cmt_too_large'));
                
                $str = str_replace("%x", $query->row['comment_max_chars'], $str);
            
                $error[] = $str;
            }
        }
        
        // ----------------------------------------
        //  Do we have errors to display?
        // ----------------------------------------
                
        if (count($error) > 0)
        {
           return $OUT->show_user_error('submission', $error);
        }
        
        // ----------------------------------------
        //  Fetch email notification addresses
        // ----------------------------------------
        
        $query = $DB->query("SELECT DISTINCT email FROM exp_comments WHERE status = 'o' AND entry_id = '".$DB->escape_str($_POST['entry_id'])."' AND notify = 'y'");
        
        $recipients = array();
        
        if ($query->num_rows > 0)
        {
            foreach ($query->result as $row)
            {
                $recipients[] = $row['email'];   
            }
        }
        
        
        // ----------------------------------------
        //  Build the data array
        // ----------------------------------------
        
        $notify = ($IN->GBL('notify_me', 'POST')) ? 'y' : 'n';
        
        $data = array(
                        'weblog_id'     => $weblog_id,
                        'entry_id'      => $_POST['entry_id'],
                        'author_id'     => $SESS->userdata['member_id'],
                        'name'          => $REGX->xss_clean($_POST['name']),
                        'email'         => $_POST['email'],
                        'url'           => $REGX->xss_clean($REGX->prep_url($_POST['url'])),
                        'location'      => $REGX->xss_clean($_POST['location']),
                        'comment'       => $REGX->xss_clean($_POST['comment']),
                        'comment_date'  => $LOC->now,
                        'ip_address'    => $IN->IP,
                        'notify'        => $notify,
                        'status'		=> ($comment_moderate == 'y') ? 'c' : 'o'
                     );

      
        // ----------------------------------------
        //  Submit data into DB
        // ----------------------------------------
      
        if ($PREFS->ini('secure_forms') == 'y')
        {
            $query = $DB->query("SELECT COUNT(*) AS count FROM exp_security_hashes WHERE hash='".$DB->escape_str($_POST['XID'])."' AND date > UNIX_TIMESTAMP()-7200");
        
            if ($query->row['count'] > 0)
            {
                $sql = $DB->insert_string('exp_comments', $data);

                $DB->query($sql);
                
                $comment_id = $DB->insert_id;
                                
                $DB->query("DELETE FROM exp_security_hashes WHERE (hash='".$DB->escape_str($_POST['XID'])."' OR date < UNIX_TIMESTAMP()-7200)");
            }
            else
            {
                $FNS->redirect(stripslashes($_POST['RET']));
            }
        }
        else
        {
            $sql = $DB->insert_string('exp_comments', $data);
        
            $DB->query($sql);
            
            $comment_id = $DB->insert_id;
        }
        
        if ($comment_moderate == 'n')
        {       
			// ------------------------------------------------
			// Update comment total and "recent comment" date
			// ------------------------------------------------
			
			$DB->query("UPDATE exp_weblog_titles SET comment_total = '$comment_total', recent_comment_date = '".$LOC->now."' WHERE entry_id = '".$_POST['entry_id']."'");
		 
			// ----------------------------------------
			// Update member comment total and date
			// ----------------------------------------
			
			if ($SESS->userdata['member_id'] != 0)
			{
				$query = $DB->query("SELECT total_comments FROM exp_members WHERE member_id = '".$SESS->userdata['member_id']."'");
	
				$DB->query("UPDATE exp_members SET total_comments = '".($query->row['total_comments'] + 1)."', last_comment_date = '".$LOC->now."' WHERE member_id = '".$SESS->userdata['member_id']."'");                
			}
			
			// ----------------------------------------
			// Update global stats
			// ----------------------------------------
			
			$STAT->update_comment_stats($weblog_id);
        }
        
        // ----------------------------
        //  Send admin notification
        // ----------------------------

        if ($comment_notify == 'y' AND $notify_address != '')
        { 
			$swap = array(
							'weblog_name'	=> $blog_title,
							'entry_title'	=> $entry_title,
							'comment_url'	=> $FNS->remove_double_slashes($blog_url.'/'.$url_title.'/')
			
						 );
			
			$template = $FNS->fetch_email_template('admin_notify_comment');
			
			$email_msg = $FNS->var_replace($swap, $template['data']);
                   
			// We don't want to send an admin notification if the person
			// leaving the comment is an admin in the notification list
			
			if (eregi("^".$_POST['email'], $notify_address))
			{
				$notify_address = str_replace($_POST['email'], "", $notify_address);
			
				$notify_address = str_replace(",,", ",", $notify_address);
			}
                        
            // ----------------------------
            //  Send email
            // ----------------------------
            
            if ( ! class_exists('EEmail'))
            {
				require PATH_CORE.'core.email'.EXT;
            }
                 
            $email = new EEmail;
            $email->wordwrap = true;
            $email->from($PREFS->ini('webmaster_email'));	
            $email->to($notify_address); 
            $email->subject($template['title']);	
            $email->message($REGX->entities_to_ascii($email_msg));		
            $email->Send();
        }
        

        // ----------------------------------------
        //  Send user notifications
        // ----------------------------------------
 
		if ($comment_moderate == 'n')
        {       
			$email_msg = '';
					
			if (count($recipients) > 0)
			{
				$qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        
	
				$action_id  = $FNS->fetch_action_id('Comment_CP', 'delete_comment_notification');
			
				$swap = array(
								'weblog_name'				=> $blog_title,
								'entry_title'				=> $entry_title,
								'site_name'					=> $PREFS->ini('site_name'),
								'site_url'					=> $PREFS->ini('site_url'),
								'comment_url'				=> $FNS->remove_session_id($_POST['RET']),
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
					// We don't notify the person currently commenting.  That would be silly.
					
					if ($val != $_POST['email'])
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
		}
       
        // ----------------------------------------
        //  Clear cache files
        // ----------------------------------------
        
        $FNS->clear_caching('all', $_POST['URI']);
        
        // ----------------------------------------
        //  Set cookies
        // ----------------------------------------

        if ($IN->GBL('save_info', 'POST'))
        {        
            $FNS->set_cookie('save_info',   'yes',              60*60*24*365);
            $FNS->set_cookie('my_name',     $_POST['name'],     60*60*24*365);
            $FNS->set_cookie('my_email',    $_POST['email'],    60*60*24*365);
            $FNS->set_cookie('my_url',      $_POST['url'],      60*60*24*365);
            $FNS->set_cookie('my_location', $_POST['location'], 60*60*24*365);
            
            // Notification cookies
            
            if ($notify == 'y')
            {        
                $FNS->set_cookie('notify_me', 'yes', 60*60*24*365);
            }
            else
            {
                $FNS->set_cookie('notify_me', 'no', 60*60*24*365);
            }
        }
        else
        {
            $FNS->set_cookie('save_info',   'no', 60*60*24*365);
            $FNS->set_cookie('my_name',     '');
            $FNS->set_cookie('my_email',    '');
            $FNS->set_cookie('my_url',      '');
            $FNS->set_cookie('my_location', '');
            $FNS->set_cookie('notify_me', 'no', 60*60*24*365);
        }

        // -------------------------------------------
        //  Bounce user back to the comment page
        // -------------------------------------------
        
        if ($comment_moderate == 'y')
        {
			$data = array(	'title' 	=> $LANG->line('cmt_comment_accepted'),
							'heading'	=> $LANG->line('thank_you'),
							'content'	=> $LANG->line('cmt_will_be_reviewed'),
							'rate'		=> 6,							
							'redirect'	=> $_POST['RET'],							
							'link'		=> array($_POST['RET'], $LANG->line('cmt_return_to_comments'))
						 );
					
			$OUT->show_message($data);
		}
		else
		{
        	$FNS->redirect($_POST['RET']);
    	}
    }
    // END

}
// END CLASS
?>