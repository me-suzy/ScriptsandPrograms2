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
 File: mod.search.php
-----------------------------------------------------
 Purpose: Search class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Search {

	var	$min_length		= 3;	// Minimum length of search keywords
	var	$cache_expire	= 24;	// How many hours should we keep search caches?
	var	$keywords		= "";
	var	$text_format	= 'xhtml';	// Excerpt text formatting
	var	$html_format	= 'all';	// Excerpt html formatting
	var	$auto_links		= 'y';		// Excerpt auto-linking: y/n
	var	$allow_img_url	= 'n';		// Excerpt - allow images:  y/n
	var	$blog_array 	= array();
	var	$cat_array  	= array();


    // ----------------------------------------
    //  Perform Search
    // ----------------------------------------
	
	function do_search()
	{
		global $IN, $LANG, $DB, $SESS, $OUT, $FNS, $REGX;
		
        // ----------------------------------------
        // Fetch the search language file
        // ----------------------------------------
        
        $LANG->fetch_language_file('search');
        
        // ----------------------------------------
        //  Do we have a search results page?
        // ----------------------------------------
        
        // The search results template is specified as a parameter in the search form tag.
        // If the parameter is missing we'll issue an error since we don't know where to 
        // show the results
        
        if ( ! isset($_POST['RP']) OR $_POST['RP'] == '')
        {
            return $OUT->show_user_error('general', array($LANG->line('search_path_error')));
        }
		
        // ----------------------------------------
        //  Is the current user allowed to search?
        // ----------------------------------------

        if ($SESS->userdata['can_search'] == 'n' AND $SESS->userdata['group_id'] != 1)
        {            
            return $OUT->show_user_error('general', array($LANG->line('search_not_allowed')));
        }
		
        // ----------------------------------------
        //  Flood control
        // ----------------------------------------
        
        if ($SESS->userdata['search_flood_control'] > 0 AND $SESS->userdata['group_id'] != 1)
		{
			$cutoff = time() - $SESS->userdata['search_flood_control'];

			$query = $DB->query("SELECT search_id FROM exp_search WHERE search_date > '$cutoff' AND (member_id='".$SESS->userdata['member_id']."' OR ip_address='".$IN->IP."')");
					
			$text = str_replace("%x", $SESS->userdata['search_flood_control'], $LANG->line('search_time_not_expired'));
				
			if ($query->num_rows > 0)
			{
            	return $OUT->show_user_error('general', array($text));
			}
		}
		
        // ----------------------------------------
        //  Did the user submit any keywords?
        // ----------------------------------------
        
        // We only require a keyword if the member name field is blank
        
        if ( ! isset($_POST['member_name']) OR $_POST['member_name'] == '')
        {        
			if ( ! isset($_POST['keywords']) OR $_POST['keywords'] == "")
			{            
				return $OUT->show_user_error('general', array($LANG->line('search_no_keywords')));
			}
		}
		
		// ----------------------------------------
		//  Strip extraneous junk from keywords
		// ----------------------------------------

		if ($_POST['keywords'] != "")		
		{
			$this->keywords = $this->keyword_clean($DB->escape_str($_POST['keywords']));
			
			// ----------------------------------------
			//  Is the search term long enough?
			// ----------------------------------------
	
			if (strlen($this->keywords) < $this->min_length)
			{
				$text = $LANG->line('search_min_length');
				
				$text = str_replace("%x", $this->min_length, $text);
							
				return $OUT->show_user_error('general', array($text));
			}
		}
		
        // ----------------------------------------
        //  Build and run query
        // ----------------------------------------

        $sql = $this->build_standard_query();
               
        $query = $DB->query($sql);
                		
        // ----------------------------------------
        //  No query results?
        // ----------------------------------------
		
		if ($query->num_rows == 0)
		{	
            return $OUT->show_user_error('off', array($LANG->line('search_no_result')), $LANG->line('search_result_heading'));
		}
		
        // ----------------------------------------
        //  If we have a result, cache it
        // ----------------------------------------
		
		$hash = $FNS->random('md5');
		
		$sql = str_replace("\\", "\\\\", $sql);
		
		// This fixes a bug that occurs when a different table prefix is used
        
        $sql = str_replace('exp_', 'MDBMPREFIX', $sql);
				
		$data = array(
						'search_id'		=> $hash,
						'search_date'	=> time(),
						'member_id'		=> $SESS->userdata['member_id'],
						'ip_address'	=> $IN->IP,
						'total_results'	=> $query->num_rows,
						'per_page'		=> (isset($_POST['RES']) AND is_numeric($_POST['RES']) AND $_POST['RES'] < 999 ) ? $_POST['RES'] : 50,
						'query'			=> addslashes(serialize($sql)),
						'result_page'	=> $_POST['RP']
						);
		
		$DB->query($DB->insert_string('exp_search', $data));
					
        // ----------------------------------------
        //  Redirect to search results page
        // ----------------------------------------
					
		$path = $FNS->remove_double_slashes($FNS->create_url($REGX->trim_slashes($_POST['RP'])).$hash.'/');
		
		return $FNS->redirect($path);
	}
	// END
	
	
	
	
	// ---------------------------------------
	//  Create the search query
	// ---------------------------------------

	function build_standard_query()
	{
		global $DB, $LOC, $FNS;
		
        $blog_array		= array();
        		
		// ---------------------------------------
        //  Fetch the weblog_id numbers
		// ---------------------------------------
			
        // If $_POST['weblog'] exists we know the request is coming from the 
        // simple search form. If so we need to fetch the ID number of those weblogs
        

        if (isset($_POST['weblog_id']) AND is_array($_POST['weblog_id']))
        {
			$blog_array = $_POST['weblog_id'];
        }
		else
		{
			$sql = "SELECT weblog_id FROM exp_weblogs WHERE ";        
									
			if (USER_BLOG !== FALSE)
			{
				// If it's a "user blog" we limit to only their assigned blog
			
				$sql .= "weblog_id = '".UB_BLOG_ID."' ";
			}
			else
			{
				$sql .= "is_user_blog = 'n' ";
				
				if (isset($_POST['weblog']) AND $_POST['weblog'] != '')
				{
					$sql .= $FNS->sql_andor_string($_POST['weblog'], 'blog_name');
						
					if (substr($sql, 0, 3) == 'AND')
						$sql = substr($sql, 3);	
				}
			}
							
			$query = $DB->query($sql);
					
			foreach ($query->result as $row)
			{
				$blog_array[] = $row['weblog_id'];
			}        
		}
						
        // ----------------------------------------------
        //  Fetch the weblog_id numbers (from Advanced search)
        // ----------------------------------------------
        
        // We do this up-front since we use this same sub-query in two places

		$id_query = '';
                        
        if (count($blog_array) > 0)
        {                
        	foreach ($blog_array as $val)
        	{
        		if ($val != 'null' AND $val != '')
        		{
        			$id_query .= " exp_weblog_titles.weblog_id = '".$DB->escape_str($val)."' OR";
        		}
        	} 
        	        	
        	if ($id_query != '')
        	{
        		$id_query = substr($id_query, 0, -2);
        	
        		$id_query = ' AND ('.$id_query.') ';
        	}
        }

		// ---------------------------------------
		//  Fetch the searchable field names
		// ---------------------------------------
				
		$fields = array();
				
		$xql = "SELECT DISTINCT(field_group) FROM exp_weblogs WHERE ";
		
		if (USER_BLOG !== FALSE)
		{        
			$xql .= "weblog_id = '".UB_BLOG_ID."' ";
		}
		else
		{
			$xql .= "is_user_blog = 'n' ";
		}
		
		if ($id_query != '')
		{
			$xql .= $id_query.' ';
			
			$xql = str_replace('exp_weblog_titles.', '', $xql);
		}
					
		$query = $DB->query($xql);
		
		if ($query->num_rows > 0)
		{
			$fql = "SELECT field_id FROM exp_weblog_fields WHERE field_search = 'y' AND (";
		
			foreach ($query->result as $row)
			{
				$fql .= " group_id = '".$row['field_group']."' OR";	
			}
			
			$fql = substr($fql, 0, -2).')';  
							
			$query = $DB->query($fql);
						
			foreach ($query->result as $row)
			{
				$fields[] = $row['field_id'];
			}
		}
        		
		// ---------------------------------------
		//  Build the main query
		// ---------------------------------------
	
        $sql = "SELECT  DISTINCT(exp_weblog_titles.url_title), 
        				exp_weblog_titles.title, 
        				exp_weblog_titles.entry_date,
        				exp_weblog_titles.recent_comment_date,
        				exp_weblog_titles.comment_total,
        				exp_weblogs.blog_title,";
        				
        foreach ($fields as $val)
        {
			$sql .= "exp_weblog_data.field_id_".$val.",";
        }
       	
       	$sql .= "exp_weblogs.blog_title, exp_weblogs.blog_url, exp_weblogs.search_excerpt,
       			 exp_members.member_id, exp_members.screen_name
				FROM exp_weblog_titles
				LEFT JOIN exp_weblogs ON exp_weblog_titles.weblog_id = exp_weblogs.weblog_id 
				LEFT JOIN exp_weblog_data ON exp_weblog_titles.entry_id = exp_weblog_data.entry_id 
				LEFT JOIN exp_members ON exp_members.member_id = exp_weblog_titles.author_id 
				LEFT JOIN exp_comments ON exp_weblog_titles.entry_id = exp_comments.entry_id				
				LEFT JOIN exp_category_posts ON exp_weblog_titles.entry_id = exp_category_posts.entry_id
				LEFT JOIN exp_categories ON exp_category_posts.cat_id = exp_categories.cat_id
				WHERE ";
				
        // ----------------------------------------------
        // Is this a user blog?
        // ----------------------------------------------

        if (USER_BLOG !== FALSE)
        {        
            $sql .= "exp_weblogs.weblog_id = '".UB_BLOG_ID."' ";
        }
        else
        {
            $sql .= "exp_weblogs.is_user_blog = 'n' ";
        }
        
        // ----------------------------------------------
        // We only select entries that have not expired 
        // ----------------------------------------------
        
        $sql .= "AND exp_weblog_titles.entry_date < ".$LOC->now." ";
                   
        $sql .= "AND (exp_weblog_titles.expiration_date = 0 || exp_weblog_titles.expiration_date > ".$LOC->now.") ";
        
        
        // ----------------------------------------------
        // Add status declaration to the query
        // ----------------------------------------------
        
		$sql .= "AND exp_weblog_titles.status != 'closed' ";
        
        $status = (isset($_POST['status']) AND $_POST['status'] != '') ? $_POST['status'] : '';
        
        if ($status != '')
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
        // Set Date filtering
        // ----------------------------------------------
        
        if (isset($_POST['date']) AND $_POST['date'] != 0)
        {
        	$cutoff = $LOC->now - (60*60*24*$_POST['date']);
        	
        	if (isset($_POST['date_order']) AND $_POST['date_order'] == 'older')
        	{
				$sql .= "AND exp_weblog_titles.entry_date < ".$cutoff." ";
        	}
        	else
        	{
				$sql .= "AND exp_weblog_titles.entry_date > ".$cutoff." ";
        	}
        
        }
        
        // ----------------------------------------------
        //  Add keyword to the query
        // ----------------------------------------------
                
        $sql .= " AND (exp_weblog_titles.title LIKE '%".$DB->escape_str($this->keywords)."%' ";
        
		if (isset($_POST['search_in']) AND ($_POST['search_in'] == 'entries' OR $_POST['search_in'] == 'everywhere'))
		{
			foreach ($fields as $val)
			{
				$sql .= " OR (exp_weblog_data.field_id_".$val." LIKE '%".$DB->escape_str($this->keywords)."%') ";
			}
		}
		
		if (isset($_POST['search_in']) AND $_POST['search_in'] == 'everywhere')
		{
			$sql .= " OR (exp_comments.comment LIKE '%".$DB->escape_str($this->keywords)."%') ";
		}
       	
        $sql .= ")";
        
        // ----------------------------------------------
        //  Limit query to a specific member
        // ----------------------------------------------
        
        if (isset($_POST['member_name']) AND $_POST['member_name'] != '')
        {
        	if (isset($_POST['exact_match']) AND $_POST['exact_match'] == 'y')
        	{
        		$sql .= " AND exp_members.screen_name = '".$DB->escape_str($_POST['member_name'])."' ";
        	}
        	else
        	{
        		$sql .= " AND exp_members.screen_name LIKE '%".$DB->escape_str($_POST['member_name'])."%' ";
        	}
        }
        
        // ----------------------------------------------
        //  Limit query to a specific weblog
        // ----------------------------------------------
                
        if (count($blog_array) > 0)
        {        
			$sql .= $id_query;
        }
        
        // ----------------------------------------------
        //  Limit query to a specific category
        // ----------------------------------------------
                
        if (isset($_POST['cat_id']) AND is_array($_POST['cat_id']))
        {        
        	$temp = '';
        
        	foreach ($_POST['cat_id'] as $val)
        	{
        		if ($val != 'all' AND $val != '')
        		{
        			$temp .= " exp_categories.cat_id = '".$DB->escape_str($val)."' OR";
        		}
        	} 
        	
        	if ($temp != '')
        	{
        		$temp = substr($temp, 0, -2);
        	
        		$sql .= ' AND ('.$temp.') ';
        	}
        }
	
        // ----------------------------------------------
        //  Set sort order
        // ----------------------------------------------
	
		$order_by = ( ! isset($_POST['order_by'])) ? 'date' : $_POST['order_by'];
	
		switch ($order_by)
		{
			case 'most_comments'	:	$sql .= " ORDER BY comment_total ";
				break;
			case 'recent_comment'	:	$sql .= " ORDER BY recent_comment_date ";
				break;
			case 'title'			:	$sql .= " ORDER BY title ";
				break;
			default					:	$sql .= " ORDER BY entry_date ";
				break;
		}
	
		$order = ( ! isset($_POST['sort_order'])) ? 'desc' : $_POST['sort_order'];
		
		if ($order != 'asc' AND $order != 'desc')
			$order = 'desc';
			
		$sql .= " ".$order;
		
		return $sql;
	}
	// END




    // ----------------------------------------
    //  Total search results
    // ----------------------------------------
	
	function total_results()
	{
		global $IN, $DB, $TMPL, $LANG, $FNS, $OUT, $LOC;
        
        // ----------------------------------------
        // Check search ID number
        // ----------------------------------------
        
        // If the QSTR variable is less than 32 characters long we
        // don't have a valid search ID number
        
        if (strlen($IN->QSTR) < 32)
        {
			return '';
        }        
                        
        // ----------------------------------------
        // Fetch ID number and page number
        // ----------------------------------------
        
		$search_id = substr($IN->QSTR, 0, 32);

        // ----------------------------------------
        //  Fetch the cached search query
        // ----------------------------------------        
			        
		$query = $DB->query("SELECT total_results FROM exp_search WHERE search_id = '".$DB->escape_str($search_id)."'");

		if ($query->num_rows == 1)
		{
			return $query->row['total_results'];
		}
		else
		{
			return 0;
		}
	}
	// END


    // ----------------------------------------
    //  Show search results
    // ----------------------------------------
	
	function search_results()
	{
		global $IN, $DB, $TMPL, $LANG, $FNS, $OUT, $LOC;

        // ----------------------------------------
        // Fetch the search language file
        // ----------------------------------------
        
        $LANG->fetch_language_file('search');
        
        // ----------------------------------------
        // Check search ID number
        // ----------------------------------------
        
        // If the QSTR variable is less than 32 characters long we
        // don't have a valid search ID number
        
        if (strlen($IN->QSTR) < 32)
        {
            return $OUT->show_user_error('off', array($LANG->line('search_no_result')), $LANG->line('search_result_heading'));        
        }        
                
        // ----------------------------------------
        // Clear old search results
        // ----------------------------------------

		$expire = time() - ($this->cache_expire * 3600);
		
		$DB->query("DELETE FROM exp_search WHERE search_date < '$expire'");
        
        
        // ----------------------------------------
        // Fetch ID number and page number
        // ----------------------------------------
        
        // We cleverly disguise the page number in the ID hash string
                
        $cur_page = 0;
        
        if (strlen($IN->QSTR) == 32)
        {
        	$search_id = $IN->QSTR;
        }
        else
        {
        	$search_id = substr($IN->QSTR, 0, 32);
        	$cur_page  = substr($IN->QSTR, 32);
        }

        // ----------------------------------------
        //  Fetch the cached search query
        // ----------------------------------------        
			        
		$query = $DB->query("SELECT * FROM exp_search WHERE search_id = '".$DB->escape_str($search_id)."'");
        
		if ($query->num_rows == 0)
		{
            return $OUT->show_user_error('off', array($LANG->line('search_no_result')), $LANG->line('search_result_heading'));        
		}
		
        $sql = stripslashes(unserialize($query->row['query']));
        $sql = str_replace('MDBMPREFIX', 'exp_', $sql);
                
        $per_page = $query->row['per_page'];
        $res_page = $query->row['result_page'];
        
        // ----------------------------------------
        //  Run the search query
        // ----------------------------------------   
                
        $query = $DB->query($sql);
        
		if ($query->num_rows == 0)
		{
            return $OUT->show_user_error('off', array($LANG->line('search_no_result')), $LANG->line('search_result_heading'));        
		}
        
        // ----------------------------------------
        //  Calculate total number of pages
        // ----------------------------------------
			
		$current_page =  ($cur_page / $per_page) + 1;
			
        $total_pages = intval($query->num_rows / $per_page);
        
        if ($query->num_rows % $per_page) 
        {
            $total_pages++;
        }		
        
		$page_count = $LANG->line('page').' '.$current_page.' '.$LANG->line('of').' '.$total_pages;
		
		// -----------------------------
    	//  Do we need pagination?
		// -----------------------------  
		
		// If so, we'll add the LIMIT clause to the SQL statement and run the query again
				
		$pager = ''; 		
		
		if ($query->num_rows > $per_page)
		{ 											
			if ( ! class_exists('Paginate'))
			{
				require PATH_CORE.'core.paginate'.EXT;
			}

			$PGR = new Paginate();
						
			$PGR->path			= $FNS->create_url($res_page.'/'.$search_id, 0, 0);
			$PGR->total_count 	= $query->num_rows;
			$PGR->per_page		= $per_page;
			$PGR->cur_page		= $cur_page;
			
			$pager = $PGR->show_links();			
			 
			$sql .= " LIMIT ".$cur_page.", ".$per_page;
			
			$query = $DB->query($sql);    
		}
		
		// -----------------------------
    	//  Fetch dates
		// -----------------------------  
		
		// We'll grab all date variables from the template
		// we do this to avoid cycling this in the result loop
		
		$dates = array();
		
		if (preg_match_all("/".LD."(entry|recent_comment)_date\s+format=(.*?)".RD."/s", $TMPL->tagdata, $matches))
		{ 
        	for ($j = 0; $j < count($matches['0']); $j++)
        	{
				$fmt = str_replace("\"", "", $matches['2'][$j]);
				$fmt = str_replace("'",  "", $fmt);
        	
				$dates[] = array($matches['0'][$j], $fmt);
        	}
		}
		
		// -----------------------------
    	//  Fetch member path variable
		// -----------------------------  
		
		// We do it here in case it's used in multiple places.
		
		$m_paths = array();
		
		if (preg_match_all("/".LD."member_path(\s*=.*?)".RD."/s", $TMPL->tagdata, $matches))
		{ 
        	for ($j = 0; $j < count($matches['0']); $j++)
        	{        	
				$m_paths[] = array($matches['0'][$j], $FNS->extract_path($matches['1'][$j]));
        	}
		}
		
		
		// -----------------------------
    	//  Fetch switch param
		// -----------------------------  
		
		$switch1 = '';
		$switch2 = '';
		
		if ($switch = $TMPL->fetch_param('switch'))
		{
			if (ereg("\|", $switch))
			{
				$x = explode("|", $switch);
				
				$switch1 = $x['0'];
				$switch2 = $x['1'];
			}
			else
			{
				$switch1 = $switch;
			}
		}		
		
		
		// ----------------------------------------
		//  Instantiate Typography class
		// ----------------------------------------        
	  
		if ( ! class_exists('Typography'))
		{
			require PATH_CORE.'core.typography'.EXT;
		}
            
		$TYPE = new Typography;
		
		// -----------------------------
    	//  Result Loop
		// -----------------------------  
		
		$result = '';
		$i = 0;
        
        foreach ($query->result as $row)
        {
			$temp = $TMPL->tagdata;
			
			$switch = ($i++ % 2) ? $switch1 : $switch2;

			$temp = str_replace(LD.'switch'.RD,			$switch, 				$temp);
			$temp = str_replace(LD.'title'.RD,			$row['title'], 			$temp);
			$temp = str_replace(LD.'author'.RD,			$row['screen_name'],	$temp);
			$temp = str_replace(LD.'comment_total'.RD,	$row['comment_total'],	$temp);
			$temp = str_replace(LD.'weblog'.RD,			$row['blog_title'],		$temp);
			
			if (isset($row['field_id_'.$row['search_excerpt']]))
			{				
				$excerpt = strip_tags($row['field_id_'.$row['search_excerpt']]);
				$excerpt = preg_replace("/(\015\012)|(\015)|(\012)/", " ", $excerpt);        
				$excerpt = $FNS->word_limiter($excerpt, 50);
			
				$excerpt = $TYPE->parse_type($excerpt, array(
																'text_format'   => 'xhtml',
																'html_format'   => 'safe',
																'auto_links'    => 'y',
																'allow_img_url' => 'n'
														    ));
														    
														    
			
				$temp = str_replace(LD.'excerpt'.RD, $excerpt, $temp);
			}
			else
			{
				$temp = str_replace(LD.'excerpt'.RD, '', $temp);
			}
			
			// Parse member_path
			
			if (count($m_paths) > 0)
			{
				foreach ($m_paths as $val)
				{					
					$temp = preg_replace("/".$val['0']."/", $FNS->create_url($val['1'].'/'.$row['member_id']), $temp);
				}
			}
			
			// Parse permalink path
			
			if (ereg("/index/$", $row['blog_url']))
				$row['blog_url'] = substr($row['blog_url'], 0, -6);
						
			$path = $FNS->remove_double_slashes($row['blog_url'].'/'.$row['url_title'].'/');
			$temp = str_replace(LD.'path'.RD, $path, $temp);
			
			// Parse dates
			
			if (count($dates) > 0)
			{
				foreach ($dates as $val)
				{
					$date = (ereg("recent_comment_date", $val['0'])) ? $row['recent_comment_date'] : $row['entry_date'];
					
					$date = ($date == 0 || $date == "") ? '' : $LOC->decode_date($val['1'], $date);
					
					$temp = preg_replace("/".$val['0']."/", $date, $temp);
				}
			}
						
			$result .= $temp;
        }
        
        
        $TMPL->tagdata = $result;
        
		// ----------------------------------------
		//   Parse variables
		// ----------------------------------------
		
		$swap = array(
						'lang:total_search_results'	=>	$LANG->line('search_total_results'),
						'lang:search_engine'		=>	$LANG->line('search_engine'),
						'lang:search_results'		=>	$LANG->line('search_results'),
						'lang:search'				=>	$LANG->line('search'),
						'lang:title'				=>	$LANG->line('search_title'),
						'lang:weblog'				=>	$LANG->line('search_weblog'),
						'lang:excerpt'				=>	$LANG->line('search_excerpt'),
						'lang:author'				=>	$LANG->line('search_author'),
						'lang:date'					=>	$LANG->line('search_date'),
						'lang:total_comments'		=>	$LANG->line('search_total_comments'),
						'lang:recent_comments'		=>	$LANG->line('search_recent_comment_date')
					);
	
		$TMPL->template = $FNS->var_replace($swap, $TMPL->template);

		// ----------------------------------------
		//   Add Pagination
		// ----------------------------------------

		if ($pager == '')
		{
			$TMPL->template = preg_replace("/".LD."if paginate".RD.".*?".LD."&#47;if".RD."/s", '', $TMPL->template);
		}
		else
		{
			$TMPL->template = preg_replace("/".LD."if paginate".RD."(.*?)".LD."&#47;if".RD."/s", "\\1", $TMPL->template);
		}

		$TMPL->template = str_replace(LD.'paginate'.RD, $pager, $TMPL->template);
		$TMPL->template = str_replace(LD.'page_count'.RD, $page_count, $TMPL->template);

        return stripslashes($TMPL->tagdata);
	}
	// END




    // ----------------------------------------
    //  Simple Search Form
    // ----------------------------------------

    function simple_form()
    {
        global $IN, $FNS, $PREFS, $TMPL, $DB, $LANG;
        
        // ----------------------------------------
        //  Create form
        // ----------------------------------------
        
        $result_page = ( ! $TMPL->fetch_param('result_page')) ? 'search/results' : $TMPL->fetch_param('result_page');
               
        $hidden_fields = array(
                                'ACT'		=> $FNS->fetch_action_id('Search', 'do_search'),
                                'XID'		=> '',
                                'RP'		=> $result_page,
                                'RES'		=> $TMPL->fetch_param('results'),
                                'status'	=> $TMPL->fetch_param('status'),
                                'weblog'	=> $TMPL->fetch_param('weblog'),
                                'search_in'	=> $TMPL->fetch_param('search_in')
                              );            
                             
        $res  = $FNS->form_declaration($hidden_fields, '', 'searchform');
                
        $res .= stripslashes($TMPL->tagdata);
        
        $res .= "</form>"; 

        return $res;
	}
	// END



    // ----------------------------------------
    //  Advanced Search Form
    // ----------------------------------------

    function advanced_form()
    {
        global $IN, $FNS, $PREFS, $TMPL, $DB, $LANG;
        
        
        $LANG->fetch_language_file('search');
        
		// ----------------------------------------
		//  Fetch weblogs and categories
		// ----------------------------------------
        
        // First we need to grab the name/ID number of all weblogs and categories
		
		$sql = "SELECT blog_title, weblog_id, cat_group FROM exp_weblogs WHERE ";
								
        if (USER_BLOG !== FALSE)
        {
            // If it's a "user blog" we limit to only their assigned blog
        
            $sql .= "exp_weblogs.weblog_id = '".UB_BLOG_ID."' ";
        }
        else
        {
            $sql .= "exp_weblogs.is_user_blog = 'n' ";
        
            if ($weblog = $TMPL->fetch_param('weblog'))
            {
                $xql = "SELECT weblog_id FROM exp_weblogs WHERE ";
            
                $str = $FNS->sql_andor_string($weblog, 'blog_name');
                
                if (substr($str, 0, 3) == 'AND')
                    $str = substr($str, 3);
                
                $xql .= $str;            
                    
                $query = $DB->query($xql);
                
                if ($query->num_rows > 0)
                {
                    if ($query->num_rows == 1)
                    {
                        $sql .= "AND weblog_id = '".$query->row['weblog_id']."' ";
                    }
                    else
                    {
                        $sql .= "AND (";
                        
                        foreach ($query->result as $row)
                        {
                            $sql .= "weblog_id = '".$row['weblog_id']."' OR ";
                        }
                        
                        $sql = substr($sql, 0, - 3);
                        
                        $sql .= ") ";
                    }
                }
            }
        }
                  
		$sql .= " ORDER BY blog_title";
		
		$query = $DB->query($sql);
				
		foreach ($query->result as $row)
		{
			$this->blog_array[$row['weblog_id']] = array($row['blog_title'], $row['cat_group']);
		}        
	
        $sql = "SELECT exp_categories.group_id, exp_categories.cat_id, exp_categories.cat_name 
                FROM exp_categories, exp_category_groups
                WHERE exp_category_groups.group_id = exp_categories.group_id
                AND exp_category_groups.is_user_blog = 'n'
                ORDER BY cat_name";
        
        $query = $DB->query($sql);
        
        if ($query->result > 0)
        {
			foreach ($query->result as $row)
			{
				$this->cat_array[] = array($row['group_id'], $row['cat_id'], $row['cat_name']);
			}
		}					
                
		// ----------------------------------------
		//  Build select list
		// ----------------------------------------
        
        $weblog_names = "<option value=\"null\" selected=\"selected\">".$LANG->line('search_any_weblog')."</option>\n";
         
		foreach ($this->blog_array as $key => $val)
		{
			$weblog_names .= "<option value=\"".$key."\">".$val['0']."</option>\n";
		}
                
   
        $tagdata = $TMPL->tagdata; 
        
		// ----------------------------------------
		//   Parse variables
		// ----------------------------------------
		
		$swap = array(
						'lang:search_engine'				=>	$LANG->line('search_engine'),
						'lang:search'						=>	$LANG->line('search'),
						'lang:search_by_keyword'			=>	$LANG->line('search_by_keyword'),
						'lang:search_in_titles'				=>	$LANG->line('search_in_titles'),
						'lang:search_in_entries'			=>	$LANG->line('search_entries'),
						'lang:search_everywhere'			=>	$LANG->line('search_everywhere'),
						'lang:search_by_member_name'		=>	$LANG->line('search_by_member_name'),
						'lang:exact_name_match'				=>	$LANG->line('search_exact_name_match'),
						'lang:also_search_comments'			=>	$LANG->line('search_also_search_comments'),
						'lang:any_date'						=>	$LANG->line('search_any_date'),
						'lang:today_and'					=>	$LANG->line('search_today_and'),
						'lang:this_week_and'				=>	$LANG->line('search_this_week_and'),
						'lang:one_month_ago_and'			=>	$LANG->line('search_one_month_ago_and'),
						'lang:three_months_ago_and'			=>	$LANG->line('search_three_months_ago_and'),
						'lang:six_months_ago_and'			=>	$LANG->line('search_six_months_ago_and'),
						'lang:one_year_ago_and'				=>	$LANG->line('search_one_year_ago_and'),
						'lang:weblogs'						=>	$LANG->line('search_weblogs'),
						'lang:categories'					=>	$LANG->line('search_categories'),
						'lang:newer'						=>	$LANG->line('search_newer'),
						'lang:older'						=>	$LANG->line('search_older'),
						'lang:sort_results_by'				=>	$LANG->line('search_sort_results_by'),
						'lang:date'							=>	$LANG->line('search_date'),
						'lang:title'						=>	$LANG->line('search_title'),
						'lang:most_comments'				=>	$LANG->line('search_most_comments'),
						'lang:recent_comment'				=>	$LANG->line('search_recent_comment'),
						'lang:descending'					=>	$LANG->line('search_descending'),
						'lang:ascending'					=>	$LANG->line('search_ascending'),
						'lang:search_entries_from'			=>	$LANG->line('search_entries_from'),
						'lang:any_category'					=>	$LANG->line('search_any_category'),
						'weblog_names' 						=>	$weblog_names
					);
	
		
		$tagdata = $FNS->var_replace($swap, $tagdata);
		
		$TMPL->template = $FNS->var_replace($swap, $TMPL->template);
        
        // ----------------------------------------
        //  Create form
        // ----------------------------------------
                
        $result_page = ( ! $TMPL->fetch_param('result_page')) ? 'search/results' : $TMPL->fetch_param('result_page');
               
        $hidden_fields = array(
                                'ACT'		=> $FNS->fetch_action_id('Search', 'do_search'),
                                'XID'		=> '',
                                'RP'		=> $result_page,
                                'RES'		=> $TMPL->fetch_param('results'),
                                'status'	=> $TMPL->fetch_param('status'),
                                'search_in'	=> $TMPL->fetch_param('search_in')
                              );            
                             
        $res  = $FNS->form_declaration($hidden_fields, '', 'searchform');
        
        $res .= $this->search_js_switcher();
        
        $res .= stripslashes($tagdata);
        
        $res .= "</form>"; 

        return $res;
    }
    // END



    // ----------------------------------------
    //  JavaScript weblog/category switch code
    // ----------------------------------------

	function search_js_switcher()
	{
		global $LANG;
		        		
		ob_start();
?>
<script language="JavaScript">
<!--

var firstcategory = 1;
var firststatus = 1;

function changemenu(index)
{ 

	var categories = new Array();
	
	var i = firstcategory;
	var j = firststatus;
	
	var blogs = document.searchform.elements['weblog_id[]'].options[index].value;
	
	var reset = 0;

	for (var g = 0; g < document.searchform.elements['weblog_id[]'].options.length; g++)
	{
		if (document.searchform.elements['weblog_id[]'].options[g].value != 'null' && 
			document.searchform.elements['weblog_id[]'].options[g].selected == true)
		{
			reset++;
		}
	} 
  
	with (document.searchform.elements['cat_id[]'])
	{	<?php
						
		foreach ($this->blog_array as $key => $val)
		{
		
		?>
		
		if (blogs == "<?php echo $key ?>")
		{	<?php echo "\n";
			if (count($this->cat_array) > 0)
			{
				foreach ($this->cat_array as $k => $v)
				{
					if ($v['0'] == $val['1'])
					{
						?>
			categories[i] = new Option("<?php echo $v['2'];?>", "<?php echo $v['1'];?>"); i++; <?php echo "\n";
					}
				}
			}
			  
			?>

		} // END if blogs
			
		<?php
		 
		} // END OUTER FOREACH
		 
		?> 
								
		if (reset > 1)
		{
			 categories = new Array();
		}

		with (document.searchform.elements['cat_id[]'])
		{
			for (i = length-1; i >= firstcategory; i--)
				options[i] = null;
			
			for (i = firstcategory; i < categories.length; i++)
				options[i] = categories[i];
			
			options[0].selected = true;
		}
		
	}
}

//--></script>
	
		<?php
	
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
	
	
		return $buffer;
	}
	// END



	// ----------------------------------------
	//  Clean Keywords
	// ----------------------------------------
	
	function keyword_clean($str)
	{
		$str =& strtolower($str);
		$str =& strip_tags($str);
	
		// Remove periods unless they are within a word
	
		$str =& preg_replace("#\.*(\s|$)#", " ", $str);
	
		$chars = array(
						","		=> " ",
						";"		=> " ",
						"\""	=> " ",
						"("		=> " ",
						")"		=> " ",
						"-"		=> " ",
						"+"		=> " ",
						"!"		=> " ",
						"?"		=> " ",
						"["		=> " ",
						"]"		=> " ",
						"$"		=> " ",
						"@"		=> " ",
						"&"		=> " ",
						"^"		=> " ",
						"~"		=> " ",
						"<"		=> " ",
						">"		=> " ",
						"*"		=> " ",
						"|"		=> " ",
						"\n"	=> " ",
						"\t"	=> " "
					  );
		
				
		foreach ($chars as $key => $val)
		{
			$str =& str_replace($key, $val, $str);
		}

		$str =& preg_replace("(\s+)", " ", $str);
		
		return trim($str);
	}
	// END

}
// END CLASS
?>