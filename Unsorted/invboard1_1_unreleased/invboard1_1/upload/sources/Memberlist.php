<?php

/*
+--------------------------------------------------------------------------
|   Invision Board v1.1
|   ========================================
|   by Matthew Mecham
|   (c) 2001,2002 Invision Power Services
|   http://www.ibforums.com
|   ========================================
|   Web: http://www.ibforums.com
|   Email: phpboards@ibforums.com
|   Licence Info: phpib-licence@ibforums.com
+---------------------------------------------------------------------------
|
|   > Show all the members
|   > Module written by Matt Mecham
|   > Date started: 20th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/


$idx = new Memberlist;

class Memberlist {

    var $output     = "";
    var $page_title = "";
    var $nav        = array();
    var $html       = "";
    var $base_url   = "";

    var $first       = 0;
    var $max_results = 10;
    var $sort_key    = 'name';
    var $sort_order  = 'asc';
    var $filter      = 'ALL';
    
    var $mem_titles = array();
    var $mem_groups = array();
    
    
    function Memberlist() {
    	global $ibforums, $DB, $std, $print;
    	
    	
    	if ($ibforums->input['CODE'] == "") $ibforums->input['CODE'] = 'listall';
    	
    	//--------------------------------------------
    	// Require the HTML and language modules
    	//--------------------------------------------
    	
		$ibforums->lang = $std->load_words($ibforums->lang, 'lang_mlist', $ibforums->lang_id );
    	
    	$this->html = $std->load_template('skin_mlist');
    	
    	$this->base_url        = "{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}?s={$ibforums->session_id}";
    	
    	if ($ibforums->member['g_mem_info'] != 1)
 		{
 			$std->Error( array( 'LEVEL' => 1, 'MSG' => 'no_permission' ) );
    	}
    	
    	$see_groups = array();
    	
    	//--------------------------------------------
    	// Get the member groups, member titles stuff
    	//--------------------------------------------
    	
    	$DB->query("SELECT g_title, g_id, g_icon from ibf_groups WHERE g_hide_from_list <> 1 ORDER BY g_title");
    	
    	while ($row = $DB->fetch_row() )
    	{
    		$see_groups[] = $row['g_id'];
    		
    		$this->mem_groups[ $row['g_id'] ] = array( 'TITLE'  => $row['g_title'],
    												   'ICON'   => $row['g_icon'],
    											     );
    	}
    	
    	unset($row);
    	
    	$group_string = implode( ",", $see_groups );
    	
    	$DB->free_result();
    	
    	$DB->query("SELECT title, id, posts, pips from ibf_titles ORDER BY posts DESC");
    	
    	while ($row = $DB->fetch_row() )
    	{
    		$this->mem_titles[ $row['id'] ] = array( 'TITLE'   => $row['title'],
    												 'POSTS'   => $row['posts'],
    												 'PIPS'    => $row['pips'],
    												 
    											   );
    	}
    	
    	unset($row);
    	
    	$DB->free_result();
    	
    	$the_filter  = array( 'ALL' => $ibforums->lang['show_all'] );
    	
    	foreach($this->mem_groups as $id => $data)
    	{
    		if ($id == $ibforums->vars['guest_group'])
    		{
    			continue;
    		}
    		$the_filter[$id] = $data['TITLE'];
    	}
    	
    	//------------------------------------------
    	// Test for input
    	//------------------------------------------
    	
    	if (isset($ibforums->input['st']))          $this->first       = $ibforums->input['st'];
    	if (isset($ibforums->input['max_results'])) $this->max_results = $ibforums->input['max_results'];
    	if (isset($ibforums->input['sort_key']))    $this->sort_key    = $ibforums->input['sort_key'];
    	if (isset($ibforums->input['sort_order']))  $this->sort_order  = $ibforums->input['sort_order'];
    	if (isset($ibforums->input['filter']))      $this->filter      = $ibforums->input['filter'];
    	
    	//------------------------------------------
    	// Fix up the search box
    	//------------------------------------------
    	
    	$ibforums->input['name'] = trim(urldecode(stripslashes($ibforums->input['name'])));
    	
    	if ($ibforums->input['name'] == "")
    	{
    		$ibforums->input['name_box'] = 'all';
    	}
    	
    	//------------------------------------------
    	// Init some arrays
    	//------------------------------------------
    	
    	$the_sort_key = array( 'name'    => 'sort_by_name',
    						   'posts'   => 'sort_by_posts',
    						   'joined'  => 'sort_by_joined',
    						 );
    						 
    	$the_max_results = array( 10  => '10',
    							  20  => '20',
    							  30  => '30',
    							  40  => '40',
    							  50  => '50',
    						    );
    						    
    	$the_sort_order = array(  'desc' => 'descending_order',
    							  'asc'  => 'ascending_order',
    						   );
    						   
    	//------------------------------------------
    	// Start the form stuff
    	//------------------------------------------
    						   
    	$filter_html      = "<select name='filter' class='forminput'>\n";
    	$sort_key_html    = "<select name='sort_key' class='forminput'>\n";
    	$max_results_html = "<select name='max_results' class='forminput'>\n";
    	$sort_order_html  = "<select name='sort_order' class='forminput'>\n";
    	
    	foreach ($the_sort_order as $k => $v) {
			$sort_order_html .= $k == $this->sort_order ? "<option value='$k' selected>" . $ibforums->lang[ $the_sort_order[ $k ] ] . "\n"
											            : "<option value='$k'>"          . $ibforums->lang[ $the_sort_order[ $k ] ] . "\n";
		}
     	foreach ($the_filter as $k => $v) {
			$filter_html .= $k == $this->filter ? "<option value='$k' selected>"         . $the_filter[ $k ] . "\n"
											            : "<option value='$k'>"          . $the_filter[ $k ] . "\n";
		}   	
    	foreach ($the_sort_key as $k => $v) {
			$sort_key_html .= $k == $this->sort_key ? "<option value='$k' selected>"     . $ibforums->lang[ $the_sort_key[ $k ] ] . "\n"
											            : "<option value='$k'>"          . $ibforums->lang[ $the_sort_key[ $k ] ] . "\n";
		}    	
    	foreach ($the_max_results as $k => $v) {
			$max_results_html .= $k == $this->max_results ? "<option value='$k' selected>". $the_max_results[ $k ] . "\n"
											            : "<option value='$k'>"          . $the_max_results[ $k ] . "\n";
		}
		
		$ibforums->lang['sorting_text'] = preg_replace( "/<#FILTER#>/"      , $filter_html."</select>"     , $ibforums->lang['sorting_text'] );
    	$ibforums->lang['sorting_text'] = preg_replace( "/<#SORT_KEY#>/"    , $sort_key_html."</select>"   , $ibforums->lang['sorting_text'] );
    	$ibforums->lang['sorting_text'] = preg_replace( "/<#SORT_ORDER#>/"  , $sort_order_html."</select>" , $ibforums->lang['sorting_text'] );
    	$ibforums->lang['sorting_text'] = preg_replace( "/<#MAX_RESULTS#>/" , $max_results_html."</select>", $ibforums->lang['sorting_text'] );
    	
    	$error = 0;
    	
    	if (! isset($the_sort_key[ $this->sort_key ]) )       $error = 1;
    	if (! isset($the_sort_order[ $this->sort_order ]) )   $error = 1;
    	if (! isset($the_filter[ $this->filter ]) )           $error = 1;
    	if (! isset($the_max_results[ $this->max_results ]) ) $error = 1;
    	
    	if ($error == 1 )
    	{
    		$std->Error( array( LEVEL=> 5, MSG =>'incorrect_use') );
    	}
    	
    	//---------------------------------------------
    	// Find out how many members match our criteria
    	//---------------------------------------------
    	
    	$q_extra = "";
    	
    	if ($this->filter != 'ALL')
    	{
    		// Are we allowed to see this group?
    		
    		if ( ! preg_match( "/(^|,)".$this->filter."(,|$)/", $group_string ) )
    		{
    			$q_extra = " AND mgroup IN($group_string)";
    		}
    		else
    		{
    			$q_extra = " AND mgroup='".$this->filter."' ";
    		}
    	}
    	
    	if ($ibforums->input['name_box'] != 'all')
    	{
    		if ( $ibforums->input['name_box'] == 'begins' )
    		{
    			$q_extra .= " AND name LIKE '".$ibforums->input['name']."%'";
    		}
    		else
    		{
    			$q_extra .= " AND name LIKE '%".$ibforums->input['name']."%'";
    		}
    	}
    		
	    $DB->query("SELECT COUNT(id) as total_members FROM ibf_members WHERE id > 0".$q_extra);
		$max = $DB->fetch_row();
		
		$DB->free_result();
		
		$links = $std->build_pagelinks(  array( 'TOTAL_POSS'  => $max['total_members'],
												'PER_PAGE'    => $this->max_results,
												'CUR_ST_VAL'  => $this->first,
												'L_SINGLE'     => "",
												'L_MULTI'      => $ibforums->lang['pages'],
												'BASE_URL'     => $this->base_url."&act=Members&name=".urlencode($ibforums->input['name'])."&name_box={$ibforums->input['name_box']}&max_results={$this->max_results}&filter={$this->filter}&sort_order={$this->sort_order}&sort_key={$this->sort_key}"
											  )
									   );
									   
		$this->output = $this->html->start();
									   
		$this->output .= $this->html->Page_header( array( 'SHOW_PAGES' => $links) );  
		
		//-----------------------------
		// START THE LISTING
		//-----------------------------
		
		$DB->query("SELECT name, id, posts, joined, mgroup, email,title, hide_email, location, aim_name, icq_number "
		          ."FROM ibf_members WHERE id > 0".$q_extra." ORDER BY ".$this->sort_key." ".$this->sort_order." LIMIT ".$this->first.",".$this->max_results);
		
		while($member = $DB->fetch_row() )
		{
		
			$pips = 0;
			
			foreach($this->mem_titles as $k => $v)
			{
				if ($member['posts'] >= $v['POSTS']) {
					if (!$member['title'])
					{
						$member['title'] = $this->mem_titles[ $k ]['TITLE'];
					}
					$pips = $v['PIPS'];
					break;
				}
			}
			
			if ($this->mem_groups[ $member['mgroup'] ]['ICON'])
			{
				$member[MEMBER_PIPS_IMG] = "<img src='{$ibforums->vars[TEAM_ICON_URL]}/{$this->mem_groups[ $member['mgroup'] ][ICON]}' border='0'>";
			}
			else
			{
				if ($pips)
				{
					if (preg_match( "/^\d+$/", $pips ) )
					{
						for ($i = 1; $i <= $pips; ++$i)
						{
							$member['MEMBER_PIPS_IMG'] .= "<{A_STAR}>";
						}
					}
					else
					{
						$member['MEMBER_PIPS_IMG'] = "<img src='{$ibforums->vars[TEAM_ICON_URL]}/$pips' border='0'>";
					}
				}
			}
								   
			$member['MEMBER_JOINED'] = $std->get_date( $member['joined'], 'JOINED' );
			
			$member['MEMBER_GROUP']  = $this->mem_groups[ $member['mgroup'] ]['TITLE'];
			
			
			if (!$member['hide_email']) {
				$member['MEMBER_EMAIL'] = "<a href='{$this->base_url}&act=Mail&CODE=00&MID={$member['id']}'><{P_EMAIL}></a>&nbsp;";
			} else {
				$member['MEMBER_EMAIL'] = '&nbsp;';
			}
			
			if ($member['icq_number']) {
				$member['ICQNUMBER'] = "<a href=\"javascript:PopUp('{$this->base_url}&act=ICQ&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_ICQ}></a>&nbsp;";
			} else {
				$member['ICQNUMBER'] = '&nbsp;';
			}
			
			if ($member['aim_name']) {
				$member[AOLNAME] = "<a href=\"javascript:PopUp('{$this->base_url}&act=AOL&MID={$member['id']}','Pager','450','330','0','1','1','1')\"><{P_AOL}></a>&nbsp;";
			} else {
				$member['AOLNAME'] = '&nbsp;';
			}
			
			$member['password'] = "";
			
			$member['MEMBER_NAME'] = $member['name'];
			
			$member['MEMBER_POSTS'] = $member['posts'];
			
			$member['MEMBER_ID']    = $member['id'];
			
			$this->output .= $this->html->show_row($member);
			
		}
		
		$this->output .= $this->html->Page_end();
		
		$this->output .= $this->html->end( array( 'SHOW_PAGES' => $links) );
    	
    	$print->add_output("$this->output");
        $print->do_output( array( 'TITLE' => $ibforums->lang['page_title'], 'JS' => 0, NAV => array( $ibforums->lang['page_title'] ) ) );
    		
 	}
 	
 	
	
}

?>
