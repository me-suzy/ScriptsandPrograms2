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
|   > Multi function library
|   > Module written by Matt Mecham
|   > Date started: 14th February 2002
|
|	> Module Version Number: 1.0.0
+--------------------------------------------------------------------------
*/



class FUNC {

	var $time_formats = array();
	var $time_options = array();
	var $offset       = "";
	var $offset_set   = 0;

	// Set up some standards to save CPU later
	
	function FUNC() {
		global $INFO;
		
		$this->time_options = array( 'JOINED' => $INFO['clock_joined'],
									 'SHORT'  => $INFO['clock_short'],
									 'LONG'   => $INFO['clock_long']
								   );
		
	}
	
	/*-------------------------------------------------------------------------*/
	//
	// Load a template file from DB or from PHP file
	//
	/*-------------------------------------------------------------------------*/
	
	function load_template( $name, $id='' )
	{
		global $ibforums, $DB, $root_path;
		
		$tags      = 1;
		
		if ($ibforums->vars['safe_mode_skins'] == 0)
		{
			// Simply require and return
			
			require $root_path."Skin/".$ibforums->skin_id."/$name.php";
			return new $name();
		}
		else
		{
			// We're using safe mode skins, yippee
			// Load the data from the DB
			
			$DB->query("SELECT func_name, func_data, section_content FROM ibf_skin_templates WHERE set_id='".$ibforums->skin_rid."' AND group_name='$name'");
			
			if ( ! $DB->get_num_rows() )
			{
				fatal_error("Could not fetch the templates from the database. Template $name, ID {$ibforums->skin_rid}");
			}
			else
			{
				$new_class = "class $name {\n";
				
				while( $row = $DB->fetch_row() )
				{
					if ($tags == 1)
					{
						$comment = "<!--TEMPLATE: $name -- Template Part: ".$row['func_name']."-->\n";
					}
					
					$new_class .= 'function '.$row['func_name'].'('.$row['func_data'].") {\n";
					$new_class .= "global \$ibforums;\n";
					$new_class .= 'return <<<EOF'."\n".$comment.$row['section_content']."\nEOF;\n}\n";
				}
				
				$new_class .= "}\n";
				
				eval($new_class);
				
				return new $name();
			}
		}
	}
		
		
	/*-------------------------------------------------------------------------*/
	//
	// Creates a profile link if member is a reg. member, else just show name
	//
	/*-------------------------------------------------------------------------*/
	
	function make_profile_link($name, $id="")
	{
		global $ibforums;
		
		if ($id > 0)
		{
			return "<a href='{$ibforums->base_url}&act=Profile&MID=$id'>$name</a>";
		}
		else
		{
			return $name;
		}
	}
	
	/*-------------------------------------------------------------------------*/
	//
	// Redirect using HTTP commands, not a page meta tag.
	//
	/*-------------------------------------------------------------------------*/
	
	function boink_it($url)
	{
		global $ibforums;
		
		if ($ibforums->vars['header_redirect'] == 'refresh')
		{
			@header("Refresh: 0;url=".$url);
		}
		else if ($ibforums->vars['header_redirect'] == 'html')
		{
			@flush();
			echo("<html><head><meta http-equiv='refresh' content='0; url=$url'></head><body></body></html>");
			exit();
		}
		else
		{
			@header("Location: ".$url);
		}
		exit();
	}
	
	/*-------------------------------------------------------------------------*/
	//
	// Create a random 8 character password
	//
	/*-------------------------------------------------------------------------*/
	
	function make_password()
	{
		$pass = "";
		$chars = array(
			"1","2","3","4","5","6","7","8","9","0",
			"a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
			"k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
			"u","U","v","V","w","W","x","X","y","Y","z","Z");
	
		$count = count($chars) - 1;
	
		srand((double)microtime()*1000000);

		for($i = 0; $i < 8; $i++)
		{
			$pass .= $chars[rand(0, $count)];
		}
	
		return($pass);
	}
	
	/*-------------------------------------------------------------------------*/
	//
	// Generate the appropriate folder icon for a forum
	//
	/*-------------------------------------------------------------------------*/
	
	function forum_new_posts($forum_data, $sub=0) {
        global $ibforums, $std;
        
        $rtime = $ibforums->input['last_visit'];
        
        $fid   = $forum_data['fid'] == "" ? $forum_data['id'] : $forum_data['fid'];
        
        if ( $ftime = $std->my_getcookie('fread_'.$fid ) )
        {
        	$rtime = $ftime > $rtime ? $ftime : $rtime;
        }
        
        if ($sub == 0)
        {
			if ( ! $forum_data['status'] )
			{
				return "<{C_LOCKED}>";
			}
			
			$sub_cat_img = '';
        }
        else
        {
        	$sub_cat_img = '_CAT';
        }
        
        if ($forum_data['password'] and $sub == 0)
        {
            return $forum_data['last_post'] > $rtime ? "<{C_ON_RES}>"
                                                     : "<{C_OFF_RES}>";
        }
        
        return $forum_data['last_post']  > $rtime ? "<{C_ON".$sub_cat_img."}>"
                                                  : "<{C_OFF".$sub_cat_img."}>";
    }
    
	/*-------------------------------------------------------------------------*/
	//
	// Generate the appropriate folder icon for a topic
	//
	/*-------------------------------------------------------------------------*/
	
	function folder_icon($topic, $dot="", $last_time=-1) {
		global $ibforums;
		
		$last_time = $last_time > $ibforums->input['last_visit'] ? $last_time : $ibforums->input['last_visit'];
		
		if ($dot != "")
		{
			$dot = "_DOT";
		}
		
		if ($topic['state'] == 'closed')
		{
			return "<{B_LOCKED}>";
		}
		
		if ($topic['poll_state'])
		{
		
			if ( ! $ibforums->member['id'] )
			{
				return "<{B_POLL".$dot."}>";
			}
			
			if ($topic['last_post'] > $topic['last_vote'])
			{
				$topic['last_vote'] = $topic['last_post'];
			}
			
			if ($last_time  && ($topic['last_vote'] > $last_time ))
			{
				return "<{B_POLL".$dot."}>";
			}
			if ($last_time  && ($topic['last_vote'] < $last_time ))
			{
				return "<{B_POLL_NN".$dot."}>";
			}
			
			return "<{B_POLL}>";
		}
		
		
		if ($topic['state'] == 'moved' or $topic['state'] == 'link')
		{
			return "<{B_MOVED}>";
		}
		
		if ( ! $ibforums->member['id'] )
		{
			return "<{B_NORM".$dot."}>";
		}
		
		if (($topic['posts'] + 1 >= $ibforums->vars['hot_topic']) and ( (isset($last_time) )  && ($topic['last_post'] <= $last_time )))
		{
			return "<{B_HOT_NN".$dot."}>";
		}
		if ($topic['posts'] + 1 >= $ibforums->vars['hot_topic'])
		{
			return "<{B_HOT".$dot."}>";
		}
		if ($last_time  && ($topic['last_post'] > $last_time))
		{
			return "<{B_NEW".$dot."}>";
		}
		
		return "<{B_NORM".$dot."}>";
		
	}
	
	/*-------------------------------------------------------------------------*/
    // text_tidy:
    // Takes raw text from the DB and makes it all nice and pretty - which also
    // parses un-HTML'd characters. Use this with caution!         
    /*-------------------------------------------------------------------------*/
    
    function text_tidy($txt = "") {
    
    	$trans = get_html_translation_table(HTML_ENTITIES);
    	$trans = array_flip($trans);
    	
    	$txt = strtr( $txt, $trans );
    	
    	$txt = preg_replace( "/\s{2}/" , "&nbsp; "      , $txt );
    	$txt = preg_replace( "/\r/"    , "\n"           , $txt );
    	$txt = preg_replace( "/\t/"    , "&nbsp;&nbsp;" , $txt );
    	//$txt = preg_replace( "/\\n/"   , "&#92;n"       , $txt );
    	
    	return $txt;
    	
    }

	/*-------------------------------------------------------------------------*/
    // compile_db_string:
    // Takes an array of keys and values and formats them into a string the DB
    // can use.
    // $array = ( 'THIS' => 'this', 'THAT' => 'that' );
    // will be returned as THIS, THAT  'this', 'that'                
    /*-------------------------------------------------------------------------*/
    
    function compile_db_string($data) {
    
    	$field_names  = "";
		$field_values = "";
		
		foreach ($data as $k => $v) {
			$v = preg_replace( "/'/", "\\'", $v );
			$field_names  .= "$k,";
			$field_values .= "'$v',";
		}
		
		$field_names  = preg_replace( "/,$/" , "" , $field_names  );
		$field_values = preg_replace( "/,$/" , "" , $field_values );
		
		return array( 'FIELD_NAMES'  => $field_names,
					  'FIELD_VALUES' => $field_values,
					);
	}



    /*-------------------------------------------------------------------------*/
    // Build up page span links                
    /*-------------------------------------------------------------------------*/
    
	function build_pagelinks($data) {

		$work = array();
	
		$work['pages']        = 1;
		
		if ( ($data['TOTAL_POSS'] % $data['PER_PAGE']) == 0 ) {
			$work['pages'] = $data['TOTAL_POSS'] / $data['PER_PAGE'];
		} else {
			$number = ($data['TOTAL_POSS'] / $data['PER_PAGE']);
			$work['pages'] = ceil( $number);
		}
		
		
		$work['total_page']   = $work['pages'];
		$work['current_page'] = $data['CUR_ST_VAL'] > 0 ? ($data['CUR_ST_VAL'] / $data['PER_PAGE']) + 1 : 1;
	
		if ($work['pages'] > 1) {
			$work['first_page'] = "{$data['L_MULTI']} ({$work['pages']}) <a href='{$data['BASE_URL']}&st=0'>&lt;</a>";
			for( $i = 0; $i <= $work['pages'] - 1; ++$i ) {
				$RealNo = $i * $data['PER_PAGE'];
				$PageNo = $i+1;
				if ($RealNo == $data['CUR_ST_VAL']) {
					$work['page_span'] .= "&nbsp;<b>[{$PageNo}]</b>";
				} else {
					if ($PageNo < ($work['current_page'] - 5) and ($work['current_page'] >= 6))  {
						$work['st_dots'] = '&nbsp;...';
						continue;
					}
					$work['page_span'] .= "&nbsp;<a href='{$data['BASE_URL']}&st={$RealNo}'>{$PageNo}</a>";
					if ($PageNo >= ($work['current_page'] + 5)) {
						$work['end_dots'] = '...&nbsp;';
						break;
					}
				}
			}
			$work['last_page'] = "<a href='{$data['BASE_URL']}&st=".($work['pages']-1) * $data['PER_PAGE']."'>&gt;</a>";
			$work['return']    = $work['first_page'].$work['st_dots'].$work['page_span'].'&nbsp;'.$work['end_dots'].$work['last_page'];
		} else {
			$work['return']    = $data['L_SINGLE'];
		}
	
		return $work['return'];
	}
    
    
    
    /*-------------------------------------------------------------------------*/
    // Build the forum jump menu               
    /*-------------------------------------------------------------------------*/ 
    
	function build_forum_jump($html=1, $override=0) {
		global $INFO, $DB, $ibforums;
		// $html = 0 means don't return the select html stuff
		// $html = 1 means return the jump menu with select and option stuff
		
		$last_cat_id = -1;
		
		$DB->query("SELECT f.id as forum_id, f.parent_id, f.subwrap, f.sub_can_post, f.name as forum_name, f.position, f.read_perms, c.id as cat_id, c.name
				    FROM ibf_forums f
				     LEFT JOIN ibf_categories c ON (c.id=f.category)
				    ORDER BY c.position, f.position");
		
		
		if ($html == 1) {
		
			$the_html = "<form onSubmit=\"if(document.jumpmenu.f.value == -1){return false;}\" action='{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}?s={$ibforums->session_id}&act=SF' method='GET' name='jumpmenu'>"
			           ."<input type='hidden' name='act' value='SF'>\n<input type='hidden' name='s' value='{$ibforums->session_id}'>"
			           ."<select name='f' onChange=\"if(this.options[this.selectedIndex].value != -1){ document.jumpmenu.submit() }\" class='forminput'>"
			           ."<option value='-1'>#Forum Jump#"
			           ."<option value='-1'>------------";
		}
		
		$forum_keys = array();
		$cat_keys   = array();
		$children   = array();
		$subs       = array();
			
		while ( $i = $DB->fetch_row() )
		{
			$selected = '';
		
			if ($html == 1 or $override == 1)
			{
				if ($ibforums->input['f'] and $ibforums->input['f'] == $i['forum_id'])
				{
					$selected = ' selected';
				}
			}
			
			if ($i['subwrap'] == 1 and $i['sub_can_post'] != 1)
			{
				$forum_keys[ $i['cat_id'] ][$i['forum_id']] = "<option value=\"{$i['forum_id']}\"".$selected.">&nbsp;&nbsp;- {$i['forum_name']}</option>\n";
			}
			else
			{
				if ($i['read_perms'] == '*')
				{
					if ($i['parent_id'] > 0)
					{
						$children[ $i['parent_id'] ][] = "<option value=\"{$i['forum_id']}\"".$selected.">&nbsp;&nbsp;---- {$i['forum_name']}</option>\n";
					}
					else
					{
						$forum_keys[ $i['cat_id'] ][$i['forum_id']] = "<option value=\"{$i['forum_id']}\"".$selected.">&nbsp;&nbsp;- {$i['forum_name']}</option>\n";
					}
				}
				else if (preg_match( "/(^|,)".$ibforums->member[mgroup]."(,|$)/", $i['read_perms']) )
				{
					if ($i['parent_id'] > 0)
					{
						$children[ $i['parent_id'] ][] = "<option value=\"{$i['forum_id']}\"".$selected.">&nbsp;&nbsp;---- {$i['forum_name']}</option>\n";
					}
					else
					{
						$forum_keys[ $i['cat_id'] ][$i['forum_id']] = "<option value=\"{$i['forum_id']}\"".$selected.">&nbsp;&nbsp;- {$i['forum_name']}</option>\n";
					}
				}
				else
				{
					continue;
				}
			}
			
			if ($last_cat_id != $i['cat_id'])
			{
				
				// Make sure cats with hidden forums are not shown in forum jump
				
				$cat_keys[ $i['cat_id'] ] = "<option value='-1'>{$i['name']}</option>\n";
							              
				$last_cat_id = $i['cat_id'];
				
			}
		}
		
		foreach($cat_keys as $cat_id => $cat_text)
		{
			if ( is_array( $forum_keys[$cat_id] ) && count( $forum_keys[$cat_id] ) > 0 )
			{
				$the_html .= $cat_text;
				
				foreach($forum_keys[$cat_id] as $idx => $forum_text)
				{
					$the_html .= $forum_text;
					
					if (count($children[$idx]) > 0)
					{
						$the_html .= $t;
						
						foreach($children[$idx] as $ii => $tt)
						{
							$the_html .= $tt;
						}
					}
				}
			}
		}
			
		
		if ($html == 1)
		{
			$the_html .= "</select>&nbsp;<input type='submit' value='{$ibforums->lang['jmp_go']}' class='forminput'></form>";
		}
		
		return $the_html;
		
	}
	
	function clean_email($email = "") {

    	$email = preg_replace( "#[\n\r\*\'\"<>&\%\!\(\)\{\}\[\]\?\\/]#", "", $email );
    	
    	if ( preg_match( "/^.+\@(\[?)[a-zA-Z0-9\-\.]+\.([a-zA-Z]{2,4}|[0-9]{1,4})(\]?)$/", $email) )
    	{
    		return $email;
    	}
    	else
    	{
    		return FALSE;
    	}
	}
    
    
    /*-------------------------------------------------------------------------*/
    // SKIN, sort out the skin stuff                 
    /*-------------------------------------------------------------------------*/
    
    function load_skin() {
    	global $ibforums, $INFO, $DB;
    	
    	$id       = -1;
    	$skin_set = 0;
    	
    	//------------------------------------------------
    	// Do we have a skin for a particular forum?
    	//------------------------------------------------
    	
    	if ($ibforums->input['f'] and $ibforums->input['act'] != 'UserCP')
    	{
    		if ( $ibforums->vars[ 'forum_skin_'.$ibforums->input['f'] ] != "" )
    		{
    			$id = $ibforums->vars[ 'forum_skin_'.$ibforums->input['f'] ];
    			
    			$skin_set = 1;
    		}
    	}
    	
    	//------------------------------------------------
    	// Are we allowing user chooseable skins?
    	//------------------------------------------------
    	
    	$extra = "";
    	
    	if ($skin_set != 1 and $ibforums->vars['allow_skins'] == 1)
    	{
    		if (isset($ibforums->input['skinid']))
    		{
    			$id    = $ibforums->input['skinid'];
    			$extra = " AND s.hidden=0";
    			$skin_set = 1;
    		}
    		else if ( $ibforums->member['skin'] != "" and intval($ibforums->member['skin']) >= 0 )
    		{
    			$id = $ibforums->member['skin'];
    			
    			if ($id == 'Default') $id = -1;
    			
    			$skin_set = 1;
    		}
    		
    	}
    	
    	//------------------------------------------------
    	// Load the info from the database.
    	//------------------------------------------------
    	
    	if ( $id >= 0 and $skin_set == 1)
    	{
    		$DB->query("SELECT s.*, t.template, c.css_text
    					FROM ibf_skins s
    					  LEFT JOIN ibf_templates t ON (t.tmid=s.tmpl_id)
    					  LEFT JOIN ibf_css c ON (c.cssid=s.css_id)
    	           	   WHERE s.sid=$id".$extra);
    	           	   
    	    // Didn't get a row?
    	    
    	    if (! $DB->get_num_rows() )
    	    {
    	    	// Update this members profile
    	    	
    	    	if ( $ibforums->member['id'] )
    	    	{
    	    		$DB->query("UPDATE ibf_members SET skin='-1' WHERE id='".$ibforums->member['id']."'");
    	    	}
    	    	
    	    		$DB->query("SELECT s.*, t.template, c.css_text
    							FROM ibf_skins s
    					  		 LEFT JOIN ibf_templates t ON (t.tmid=s.tmpl_id)
    					 		 LEFT JOIN ibf_css c ON (s.css_id=c.cssid)
    	           	   		    WHERE s.default_set=1");
    	    }
    	    
    	}
    	else
    	{
    		$DB->query("SELECT s.*, t.template, c.css_text
    					FROM ibf_skins s
    					  LEFT JOIN ibf_templates t ON (t.tmid=s.tmpl_id)
    					  LEFT JOIN ibf_css c ON (s.css_id=c.cssid)
    	           	   WHERE s.default_set=1");
    	}
    	
    	if ( ! $row = $DB->fetch_row() )
    	{
    		echo("Could not query the skin information!");
    		exit();
    	}
    	
    	return $row;
    	
    }
    
    /*-------------------------------------------------------------------------*/
    // Require, parse and return an array containing the language stuff                 
    /*-------------------------------------------------------------------------*/ 
    
    function load_words($current_lang_array, $area, $lang_type) {
    
        require "./lang/".$lang_type."/".$area.".php";
        
        foreach ($lang as $k => $v)
        {
        	$current_lang_array[$k] = stripslashes($v);
        }
        
        unset($lang);
        
        return $current_lang_array;

    }

    
    /*-------------------------------------------------------------------------*/
    // Return a date or '--' if the date is undef.
    // We use the rather nice gmdate function in PHP to synchronise our times
    // with GMT. This gives us the following choices:
    //
    // If the user has specified a time offset, we use that. If they haven't set
    // a time zone, we use the default board time offset (which should automagically
    // be adjusted to match gmdate.             
    /*-------------------------------------------------------------------------*/    
    
    function get_date($date, $method) {
        global $ibforums;
        
        if (!$date)
        {
            return '--';
        }
        
        if (empty($method))
        {
        	$method = 'LONG';
        }
        
        if ($this->offset_set == 0)
        {
        	// Save redoing this code for each call, only do once per page load
        	
			$this->offset = (($ibforums->member['time_offset'] != "") ? $ibforums->member['time_offset'] : $ibforums->vars['time_offset']) * 3600;
			
			if ($ibforums->vars['time_adjust'] != "" and $ibforums->vars['time_adjust'] != 0)
			{
				$this->offset += ($ibforums->vars['time_adjust'] * 60);
			}
			
			if ($ibforums->member['dst_in_use'])
			{
				$this->offset += 3600;
			}
			
			$this->offset_set = 1;
        }
        
        
        return gmdate($this->time_options[$method], ($date + $this->offset) );
    }
    
    /*-------------------------------------------------------------------------*/
    // Sets a cookie, abstract layer allows us to do some checking, etc                
    /*-------------------------------------------------------------------------*/    
    
    function my_setcookie($name, $value = "", $sticky = 1) {
        global $INFO;
        
        $exipres = "";
        
        if ($sticky == 1)
        {
        	$expires = time() + 60*60*24*365;
        }

        $INFO['cookie_domain'] = $INFO['cookie_domain'] == "" ? ""  : $INFO['cookie_domain'];
        $INFO['cookie_path']   = $INFO['cookie_path']   == "" ? "/" : $INFO['cookie_path'];
        
        $name = $INFO['cookie_id'].$name;
      
        @setcookie($name, urlencode($value), $expires, $INFO['cookie_path'], $INFO['cookie_domain']);
    }
    
    /*-------------------------------------------------------------------------*/
    // Cookies, cookies everywhere and not a byte to eat.                
    /*-------------------------------------------------------------------------*/  
    
    function my_getcookie($name)
    {
    	global $INFO, $HTTP_COOKIE_VARS;
    	
    	if (isset($HTTP_COOKIE_VARS[$INFO['cookie_id'].$name]))
    	{
    		return urldecode($HTTP_COOKIE_VARS[$INFO['cookie_id'].$name]);
    	}
    	else
    	{
    		return FALSE;
    	}
    	
    }
    
    /*-------------------------------------------------------------------------*/
    // Makes incoming info "safe"              
    /*-------------------------------------------------------------------------*/
    
    function parse_incoming()
    {
    	global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_CLIENT_IP, $REQUEST_METHOD, $REMOTE_ADDR, $HTTP_PROXY_USER, $HTTP_X_FORWARDED_FOR;
    	$return = array();
    	
		if( is_array($HTTP_GET_VARS) )
		{
			while( list($k, $v) = each($HTTP_GET_VARS) )
			{
				//$k = $this->clean_key($k);
				if( is_array($HTTP_GET_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_GET_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		
		// Overwrite GET data with post data
		
		if( is_array($HTTP_POST_VARS) )
		{
			while( list($k, $v) = each($HTTP_POST_VARS) )
			{
				//$k = $this->clean_key($k);
				if ( is_array($HTTP_POST_VARS[$k]) )
				{
					while( list($k2, $v2) = each($HTTP_POST_VARS[$k]) )
					{
						$return[$k][ $this->clean_key($k2) ] = $this->clean_value($v2);
					}
				}
				else
				{
					$return[$k] = $this->clean_value($v);
				}
			}
		}
		
		// Sort out the accessing IP
		
		$return['IP_ADDRESS'] = $this->select_var( array( 
														  1 => $HTTP_X_FORWARDED_FOR,
														  2 => $HTTP_PROXY_USER,
														  3 => $REMOTE_ADDR,
														  4 => $_SERVER['REMOTE_ADDR']
														)
												 );
												 
		// Make sure we take a valid IP address
		
		$return['IP_ADDRESS'] = preg_replace( "/^([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})\.([0-9]{1,3})/", "\\1.\\2.\\3.\\4", $return['IP_ADDRESS'] );
		
		$return['request_method'] = strtolower($REQUEST_METHOD);
		
		
		return $return;
	}
	
    /*-------------------------------------------------------------------------*/
    // Key Cleaner - ensures no funny business with form elements             
    /*-------------------------------------------------------------------------*/
    
    function clean_key($key) {
    
    	if ($key == "")
    	{
    		return "";
    	}
    	$key = preg_replace( "/\.\./"           , ""  , $key );
    	$key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
    	$key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
    	return $key;
    }
    
    function clean_value($val) {
    
    	if ($val == "")
    	{
    		return "";
    	}
    	$val = preg_replace( "/&/"         , "&amp;"         , $val );
    	$val = preg_replace( "/<!--/"      , "&#60;&#33;--"  , $val );
    	$val = preg_replace( "/-->/"       , "--&#62;"       , $val );
    	$val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
    	$val = preg_replace( "/>/"         , "&gt;"          , $val );
    	$val = preg_replace( "/</"         , "&lt;"          , $val );
    	$val = preg_replace( "/\"/"        , "&quot;"        , $val );
    	$val = preg_replace( "/\|/"        , "&#124;"        , $val );
    	$val = preg_replace( "/\n/"        , "<br>"          , $val ); // Convert literal newlines
    	$val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
    	$val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
    	$val = preg_replace( "/!/"         , "&#33;"         , $val );
    	$val = preg_replace( "/'/"         , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
    	$val = stripslashes($val);                                     // Swop PHP added backslashes
    	$val = preg_replace( "/\\\/"       , "&#092;"        , $val ); // Swop user inputted backslashes
    	return $val;
    }
    
    
    function is_number($number="")
    {
    
    	if ($number == "") return -1;
    	
    	if ( preg_match( "/^([0-9]+)$/", $number ) )
    	{
    		return $number;
    	}
    	else
    	{
    		return "";
    	}
    }
    
    /*-------------------------------------------------------------------------*/
    // MEMBER FUNCTIONS             
    /*-------------------------------------------------------------------------*/
    
    
    function set_up_guest($name='Guest') {
    	global $INFO;
    
    	return array( 'name'     => $name,
    				  'id'       => 0,
    				  'password' => "",
    				  'email'    => "",
    				  'title'    => "Unregistered",
    				  'mgroup'    => $INFO['guest_group'],
    				  'view_sigs' => $INFO['guests_sig'],
    				  'view_img'  => $INFO['guests_img'],
    				  'view_avs'  => $INFO['guests_ava'],
    				);
    }
    
    /*-------------------------------------------------------------------------*/
    // GET USER AVATAR         
    /*-------------------------------------------------------------------------*/
    
    function get_avatar($member_avatar="", $member_view_avatars=0, $avatar_dims="x") {
    	global $ibforums;
    	
    	if (!$member_avatar or $member_view_avatars == 0 or !$ibforums->vars['avatars_on'])
    	{
    		return "";
    	}
    	
    	if (preg_match ( "/^noavatar/", $member_avatar ))
    	{
    		return "";
    	}
    	
    	if ( (preg_match ( "/\.swf/", $member_avatar)) and ($ibforums->vars['allow_flash'] != 1) )
    	{
    		return "";
    	}
    	
    	$davatar_dims    = explode( "x", $ibforums->vars['avatar_dims'] );
    	$default_a_dims  = explode( "x", $ibforums->vars['avatar_def'] );
    	
    	
		 // Have we enabled URL / Upload avatars?
	 
		 $this_dims = explode( "x", $avatar_dims );
		 if (!$this_dims[0]) $this_dims[0] = $davatar_dims[0];
		 if (!$this_dims[1]) $this_dims[1] = $davatar_dims[1];
			 
		 if ( preg_match( "/^http:\/\//", $member_avatar ) )
		 {
			 // Ok, it's a URL..
			 
			 if (preg_match ( "/\.swf/", $member_avatar))
			 {
				 return "<OBJECT CLASSID=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" WIDTH={$this_dims[0]} HEIGHT={$this_dims[1]}><PARAM NAME=MOVIE VALUE={$member_avatar}><PARAM NAME=PLAY VALUE=TRUE><PARAM NAME=LOOP VALUE=TRUE><PARAM NAME=QUALITY VALUE=HIGH><EMBED SRC={$member_avatar} WIDTH={$this_dims[0]} HEIGHT={$this_dims[1]} PLAY=TRUE LOOP=TRUE QUALITY=HIGH></EMBED></OBJECT>";
			 }
			 else
			 {
				 return "<img src='{$member_avatar}' border='0' width='{$this_dims[0]}' height='{$this_dims[1]}'>";
			 }
			 
			 // Not a URL? Is it an uploaded avatar?
		 }
		 else if ( ($ibforums->vars['avup_size_max'] > 1) and ( preg_match( "/^upload:av-(?:\d+)\.(?:\S+)/", $member_avatar ) ) )
		 {
			 
			 $member_avatar = preg_replace( "/^upload:/", "", $member_avatar );
			 
			 return "<img src='{$ibforums->vars['upload_url']}/$member_avatar' border='0' width='{$this_dims[0]}' height='{$this_dims[1]}'>";
		 }
		 // No, it's not a URL or an upload, must be a normal avatar then
    	 else if ($member_avatar != "")
    	 {
    	 	// Do we have an avatar still ?
    	 	
    	 	return "<img src='{$ibforums->vars['AVATARS_URL']}/{$member_avatar}' border='0' width='{$default_a_dims[0]}' height='{$default_a_dims[1]}'>";
    	 }
    	 else
    	 {
    	 	// No, ok - return blank
    	 	return "";
    	 }
    }
 
 
 
 
    /*-------------------------------------------------------------------------*/
    // ERROR FUNCTIONS             
    /*-------------------------------------------------------------------------*/
    
    function Error($error) {
    	global $DB, $ibforums, $root_path, $skin_universal, $QUERY_STRING;
    	
    	
    	if ( $error['MSG'] == 'server_too_busy' or $error['MSG'] == 'you_are_banned')
    	{
    		
    		$DB->query("SELECT s.*, t.template FROM ibf_templates t, ibf_skins s ".
    	           	   "WHERE s.default_set=1 AND t.tmid=s.tmpl_id");
    	           	   
    	    $ibforums->skin = $DB->fetch_row();
    	           	   
    		require $root_path."Skin/s".$ibforums->skin['set_id']."/skin_global.php";
    		
    		$ibforums->session_id = $this->my_getcookie('session_id');

			$skin_universal = new skin_global();
			
			$ibforums->base_url   = $ibforums->vars['board_url'].'/index.'.$ibforums->vars['php_ext'].'?s='.$ibforums->session_id;
			$ibforums->vars['img_url']   = 'style_images/' . $ibforums->skin['img_id'];

		}

    	$ibforums->lang = $this->load_words($ibforums->lang, "lang_error", $ibforums->lang_id);
    	
    	list($em_1, $em_2) = explode( '@', $ibforums->vars['email_in'] );
    	
    	$msg = $ibforums->lang[ $error['MSG'] ];
    	
    	if ($error['EXTRA'])
    	{
    		$msg = preg_replace( "/<#EXTRA#>/", $error['EXTRA'], $msg );
    	}
    	
    	$html = $skin_universal->Error( $msg, $em_1, $em_2);
    	
    	// If we're a guest, show the log in box..
    	
    	if ($ibforums->member['id'] == "" and $error['MSG'] != 'server_too_busy')
    	{
    		$html = preg_replace( "/<!-- IBF\.LOG_IN_TABLE -->/e", "\$skin_universal->error_log_in(\$QUERY_STRING)", $html);
    	}
    	
    	$print = new display();
    	
    	$print->add_output($html);
    		
    	$print->do_output( array(
    								OVERRIDE   => 1,
    								TITLE      => $ibforums->lang['error_title'],
    							 )
    					  );
    }
    
    function board_offline()
    {
    	global $DB, $ibforums, $root_path, $skin_universal;
    	
    	$ibforums->lang = $this->load_words($ibforums->lang, "lang_error", $ibforums->lang_id);
    	
    	$msg = preg_replace( "/\n/", "<br>", stripslashes($ibforums->vars['offline_msg']) );
    	
    	$html = $skin_universal->board_offline( $msg );
    	
    	$print = new display();
    	
    	$print->add_output($html);
    		
    	$print->do_output( array(
    								OVERRIDE   => 1,
    								TITLE      => $ibforums->lang['offline_title'],
    							 )
    					  );
    }
    								
    /*-------------------------------------------------------------------------*/
    // Variable chooser             
    /*-------------------------------------------------------------------------*/
    
    function select_var($array) {
    	
    	if ( !is_array($array) ) return -1;
    	
    	ksort($array);
    	
    	
    	$chosen = -1;  // Ensure that we return zero if nothing else is available
    	
    	foreach ($array as $k => $v)
    	{
    		if (isset($v))
    		{
    			$chosen = $v;
    			break;
    		}
    	}
    	
    	return $chosen;
    }
      
    
} // end class


//######################################################
// Our "print" class
//######################################################


class display {

    var $to_print = "";
    
    //-------------------------------------------
    // Appends the parsed HTML to our class var
    //-------------------------------------------
    
    function add_output($to_add) {
        $this->to_print .= $to_add;
        //return 'true' on success
        return true;
    }
    
    //-------------------------------------------
    // Parses all the information and prints it.
    //-------------------------------------------
    
    function do_output($output_array) {
        global $DB, $Debug, $skin_universal, $ibforums;
        
        $TAGS = $DB->query("SELECT macro_value, macro_replace FROM ibf_macro WHERE macro_set='{$ibforums->skin['macro_id']}'");
        
        $ex_time     = sprintf( "%.4f",$Debug->endTimer() );
        
        $query_cnt   = $DB->get_query_cnt();
        
        if ($DB->obj['debug'])
        {
        	flush();
        	print "<html><head><title>mySQL Debugger</title><body bgcolor='white'><style type='text/css'> TABLE, TD, TR, BODY { font-family: verdana,arial, sans-serif;color:black;font-size:11px }</style>";
        	print $ibforums->debug_html;
        	print "</body></html>";
        	exit();
        }
        
        $input   = "";
        $queries = "";
        $sload   = "";
        
        $gzip_status = $ibforums->vars['disable_gzip'] == 1 ? $ibforums->lang['gzip_off'] : $ibforums->lang['gzip_on'];
        
        if ($ibforums->server_load > 0)
        {
        	$sload = '&nbsp; [ Server Load: '.$ibforums->server_load.' ]';
        }
        
        //+----------------------------------------------
        
        if ($ibforums->vars['debug_level'] > 0)
        {
        
			$stats = "<br><table width='<{tbl_width}>' cellpadding='4' align='center' cellspacing='0' id='row1'>
					   <tr>
						 <td align='center'>[ Script Execution time: $ex_time ] &nbsp; [ $query_cnt queries used ] &nbsp; [ $gzip_status ] $sload</td>
					   </tr>
					  </table>";
        }
        		  
       //+----------------------------------------------
        		  
       if ($ibforums->vars['debug_level'] >= 2)
       {
       		$stats .= "<br><table width='<{tbl_width}>' align='center' cellpadding='0' cellspacing='1' bgcolor='<{tbl_border}>'>
       					<tr>
       					 <td>
       					  <table width='100%' align='center' cellpadding='4' cellspacing='1'>
       					<tr>
       					  <td colspan='2' class='titlemedium' align='center'>FORM and GET Input</td>
       					</tr>";
        
			while( list($k, $v) = each($ibforums->input) )
			{
				$stats .= "<tr><td width='20%' class='row1'>$k</td><td width='80%' class='row1'>$v</td></tr>";
			}
			
			$stats .= "</table></td></tr></table>";
        
        }
        
        //+----------------------------------------------
        
        if ($ibforums->vars['debug_level'] >= 3)
        {
        	$stats .= "<br><table width='<{tbl_width}>' align='center' cellpadding='0' cellspacing='1' bgcolor='<{tbl_border}>'>
       					<tr>
       					 <td>
       					  <table width='100%' align='center' cellpadding='4' cellspacing='1'>
       					<tr>
       					  <td colspan='2' class='titlemedium' align='center'>Queries Used</td>
       					</tr>";
       					
        	foreach($DB->obj['cached_queries'] as $q)
        	{
        		$q = preg_replace( "/^SELECT/i" , "<font style='color:red;font-weight:bold'>SELECT</font>"   , $q );
        		$q = preg_replace( "/^UPDATE/i" , "<font style='color:blue;font-weight:bold'>UPDATE</font>"  , $q );
        		$q = preg_replace( "/^DELETE/i" , "<font style='color:orange;font-weight:bold'>DELETE</font>", $q );
        		$q = preg_replace( "/^INSERT/i" , "<font style='color:green;font-weight:bold'>INSERT</font>" , $q );
        		$q = str_replace( "LEFT JOIN", "<font style='color:red;font-weight:bold'>LEFT JOIN</font>" , $q );
        		
        		$q = preg_replace( "/(".$ibforums->vars['sql_tbl_prefix'].")(\S+?)([\s\.,]|$)/", "<font style='color:purple;font-weight:bold'>\\1\\2</font>\\3", $q );
        		
        		$stats .= "<tr><td class='row1'>$q</td></tr>";
        	}
        	
        	$stats .= "</table></td></tr></table>";
        }

        
        /********************************************************/
        // NAVIGATION
        
        $nav  = $skin_universal->start_nav();
        
        $nav .= "<a href='{$ibforums->vars['board_url']}/index.{$ibforums->vars['php_ext']}?s={$ibforums->session_id}'>{$ibforums->vars['board_name']}</a>";
        
        if ( empty($output_array['OVERRIDE']) )
        {
			if (is_array( $output_array['NAV'] ) )
			{
				foreach ($output_array['NAV'] as $n)
				{
					if ($n)
					{
						$nav .= "<{F_NAV_SEP}>" . $n;
					}
				}
			}
        }
        
        $nav .= $skin_universal->end_nav();
        
        
     
        /********************************************************/
        // CSS
        
		$css = "\n<style type='text/css'>\n".$ibforums->skin['css_text']."\n</style>";
		
		// Yes, I realise that this is silly and easy to remove the copyright, but
		// as it's not concealed source, there's no point having a 1337 fancy hashing
		// algorithm if all you have to do is delete a few lines, so..
		// However, be warned: If you remove the copyright and you have not purchased
		// copyright removal, you WILL be spotted and your licence to use Invision Board
		// will be terminated, requiring you to remove your board immediately.
		// So, have a nice day.
		
        $copyright = "<!-- Copyright Information -->\n\n<p><table width='80%' align='center' cellpadding='3' cellspacing='0'><tr><td align='center' valign='middle'>$b_copy<br>Powered by <a href=\"http://www.invisionboard.com\" class=\"copyright\" target='_blank'>Invision Board</a> {$ibforums->version} &copy; 2002 &nbsp;<a href='http://www.invisionpower.com' target='_blank'>Invision PS</a></td></tr></table><p>";
        
        if ($ibforums->vars['ips_cp_purchase'])
        {
        	$copyright = "";
        }
        
        // Awww, cmon, don't be mean! Literally thousands of hours have gone into
        // coding Invision Board and all we ask in return is one measly little line
        // at the bottom. That's fair isn't it?
        // No? Hmmm...
        // Have you seen how much it costs to remove the copyright from UBB? o_O
                       
        /********************************************************/
        // Build the board header
        
        $this_header  = $skin_universal->BoardHeader();
        
        // Build the members bar

        if ($ibforums->member['id'] == 0)
        {
        	$output_array['MEMBER_BAR'] = $skin_universal->Guest_bar();
        }
        else
        {
        	if (!$ibforums->member['g_use_pm'])
        	{
        		$output_array['MEMBER_BAR'] = $skin_universal->Member_no_usepm_bar();
        	}
        	else
        	{
				$pm_js = "";
				
				if ( ($ibforums->member['g_max_messages'] != "") and ($ibforums->member['msg_total'] >= $ibforums->member['g_max_messages']) )
				{
					$msg_data['TEXT'] = $ibforums->lang['msg_full'];
				}
				else
				{
					$ibforums->member['new_msg'] = $ibforums->member['new_msg'] == "" ? 0 : $ibforums->member['new_msg'];
				
					$msg_data['TEXT'] = sprintf( $ibforums->lang['msg_new'], $ibforums->member['new_msg']);
				}
				
				// Do we have a pop up to show?
				
				if ($ibforums->member['show_popup'])
				{
					$DB->query("UPDATE ibf_members SET show_popup='0' WHERE id='{$ibforums->member['id']}'");
					$pm_js = $skin_universal->PM_popup();
				}
				
				if ( ($ibforums->member['is_mod']) or ($ibforums->member['g_is_supmod'] == 1) )
				{
					$mod_link = $skin_universal->mod_link();
				}
      	
				$admin_link = $ibforums->member['g_access_cp'] ? $skin_universal->admin_link() : '';
			
				$output_array['MEMBER_BAR'] = $pm_js . $skin_universal->Member_bar($msg_data, $admin_link, $mod_link);
 			}
 			
 		}
 		
 		if ($ibforums->vars['board_offline'] == 1)
 		{
 			$output_array['TITLE'] = $ibforums->lang['warn_offline']." ".$output_array['TITLE'];
 		}
        
        // Get the template
        
        $ibforums->skin['template'] = str_replace( "<% CSS %>"            , $css                     , $ibforums->skin['template']);
		$ibforums->skin['template'] = str_replace( "<% JAVASCRIPT %>"     , ""                       , $ibforums->skin['template']);
        $ibforums->skin['template'] = str_replace( "<% TITLE %>"          , $output_array['TITLE']   , $ibforums->skin['template']);
        $ibforums->skin['template'] = str_replace( "<% BOARD %>"          , $this->to_print          , $ibforums->skin['template']);
        $ibforums->skin['template'] = str_replace( "<% STATS %>"          , $stats                   , $ibforums->skin['template']);
        $ibforums->skin['template'] = str_replace( "<% GENERATOR %>"      , ""                       , $ibforums->skin['template']);
		$ibforums->skin['template'] = str_replace( "<% COPYRIGHT %>"      , $copyright               , $ibforums->skin['template']);
		$ibforums->skin['template'] = str_replace( "<% BOARD HEADER %>"   , $this_header             , $ibforums->skin['template']);
		$ibforums->skin['template'] = str_replace( "<% NAVIGATION %>"     , $nav                     , $ibforums->skin['template']);
		
		if ( empty($output_array['OVERRIDE']) )
		{
      	    $ibforums->skin['template'] = str_replace( "<% MEMBER BAR %>"     , $output_array['MEMBER_BAR'], $ibforums->skin['template']);
        }
        else
        {
      	    $ibforums->skin['template'] = str_replace( "<% MEMBER BAR %>"     , "<br>"                     , $ibforums->skin['template']);
      	}
      	
      	//+--------------------------------------------
      	//| Get the macros and replace them
      	//+--------------------------------------------
      	
      	while ( $row = $DB->fetch_row($TAGS) )
      	{
			if ($row['macro_value'] != "")
			{
				$ibforums->skin['template'] = str_replace( "<{".$row['macro_value']."}>", $row['macro_replace'], $ibforums->skin['template'] );
			}
		}
		
		$ibforums->skin['template'] = str_replace( "<#IMG_DIR#>", $ibforums->skin['img_dir'], $ibforums->skin['template'] );
		
		if ($ibforums->vars['ipshosting_credit'])
		{
			$ibforums->skin['template'] = str_replace( "<!--IBF.BANNER-->", $skin_universal->ibf_banner(), $ibforums->skin['template'] );
		}
		
		// Close this DB connection
		
		$DB->close_db();
		
		// Start GZIP compression
        
        if ($ibforums->vars['disable_gzip'] != 1)
        {
        	ob_start('ob_gzhandler');
        }
        
        $this->do_headers();
		
        print $ibforums->skin['template'];
        
        exit;
    }
    
    //-------------------------------------------
    // print the headers
    //-------------------------------------------
        
    function do_headers() {
    	global $ibforums;
    	
    	if ($ibforums->vars['print_headers'])
    	{
			@header("HTTP/1.0 200 OK");
			@header("HTTP/1.1 200 OK");
			@header("Content-type: text/html");
			
			if ($ibforums->vars['nocache'])
			{
				@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				@header("Cache-Control: no-cache, must-revalidate");
				@header("Pragma: no-cache");
			}
        }
    }
    
    //-------------------------------------------
    // print a pure redirect screen
    //-------------------------------------------
    
    
    function redirect_screen($text="", $url="") {
    	global $ibforums, $skin_universal, $DB;
    	
    	if ($ibforums->input['debug'])
        {
        	flush();
        	exit();
        }
        
    	$url = $start . "?s={$ibforums->session_id}&".$url;
    	
    	$ibforums->lang['stand_by'] = stripslashes($ibforums->lang['stand_by']);
    	
    	$css = "\n<style>\n<!--\n".str_replace( "<#IMG_DIR#>", $ibforums->skin['img_dir'], $ibforums->skin['css_text'] )."\n//-->\n</style>";
    	
    	$htm = $skin_universal->Redirect($text, $url, $css);
    	
    	$TAGS = $DB->query("SELECT macro_value, macro_replace FROM ibf_macro WHERE macro_set='{$ibforums->skin['macro_id']}'");
    	
    	while ( $row = $DB->fetch_row($TAGS) )
      	{
			if ($row['macro_value'] != "")
			{
				$htm = str_replace( "<{".$row['macro_value']."}>", $row['macro_replace'], $htm );
			}
		}
		
		$html = str_replace( "<#IMG_DIR#>", $ibforums->skin['img_dir'], $htm );
    	
    	// Close this DB connection
		
		$DB->close_db();
		
		// Start GZIP compression
        
        if ($ibforums->vars['disable_gzip'] != 1)
        {
        	ob_start ('ob_gzhandler');
        }
        
        $this->do_headers();
        
    	echo ($htm);
    	exit;
    }
    
    //-------------------------------------------
    // print a minimalist screen suitable for small
    // pop up windows
    //-------------------------------------------
    
    function pop_up_window($title = 'Invision Board', $text = "" ) {
    	global $ibforums, $DB;
    	
    	$css = "\n<style>\n<!--\n".str_replace( "<#IMG_DIR#>", $ibforums->skin['img_dir'], $ibforums->skin['css_text'] )."\n//-->\n</style>";
		
    	$html = "<html>
    	           <head>
    	              <title>$title</title>
    	              $css
    	           </head>
    	           <body topmargin='0' leftmargin='0' rightmargin='0' marginwidth='0' marginheight='0' alink='#000000' vlink='#000000'>
    	           $text
    	           </body>
    	         </html>
    	        ";
    	        
    	$TAGS = $DB->query("SELECT macro_value, macro_replace FROM ibf_macro WHERE macro_set='{$ibforums->skin['macro_id']}'");
    	
    	while ( $row = $DB->fetch_row($TAGS) )
      	{
			if ($row['macro_value'] != "")
			{
				$html = str_replace( "<{".$row['macro_value']."}>", $row['macro_replace'], $html );
			}
		}
		
		$html = str_replace( "<#IMG_DIR#>", $ibforums->skin['img_dir'], $html );
    	
    	$DB->close_db();
    	  
    	if ($ibforums->vars['disable_gzip'] != 1)
        {
        	ob_start ('ob_gzhandler');
        }
        
        $this->do_headers();
        
    	echo ($html);
    	exit;
    } 
    
    
    
} // END class
    



//######################################################
// Our "session" class
//######################################################


class session {

    var $ip_address = 0;
    var $user_agent = "";
    var $time_now   = 0;
    var $session_id = 0;
    var $session_dead_id = 0;
    var $session_user_id = 0;
    var $session_user_pass = "";
    var $last_click        = 0;
    var $location          = "";
    var $member            = array();

    // No need for a constructor
    
    function authorise() {
        global $DB, $INFO, $ibforums, $std, $HTTP_USER_AGENT;
        
        //-------------------------------------------------
        // Before we go any lets check the load settings..
        //-------------------------------------------------
        
        if ($ibforums->vars['load_limit'] > 0)
        {
        	if ( file_exists('/proc/loadavg') )
        	{
        		if ( $fh = @fopen( '/proc/loadavg', 'r' ) )
        		{
        			$data = @fread( $fh, 6 );
        			@fclose( $fh );
        			
        			$load_avg = explode( " ", $data );
        			
        			$ibforums->server_load = trim($load_avg[0]);
        			
        			if ($ibforums->server_load > $ibforums->vars['load_limit'])
        			{
        				$std->Error( array( 'LEVEL' => 1, 'MSG' => 'server_too_busy' ) );
        			}
        		}
        	}
        }
        
        //--------------------------------------------
		// Are they banned?
		//--------------------------------------------
		
		if ($ibforums->vars['ban_ip'])
		{
			$ips = explode( "|", $ibforums->vars['ban_ip'] );
			
			foreach ($ips as $ip)
			{
				$ip = preg_replace( "/\*/", '.*' , $ip );
				if (preg_match( "/$ip/", $ibforums->input['IP_ADDRESS'] ))
				{
					$std->Error( array( LEVEL => 1, MSG => 'you_are_banned' ) );
				}
			}
		}
        
        //--------------------------------------------
        
        $this->member = array( 'id' => 0, 'password' => "", 'name' => "", 'mgroup' => $INFO['guest_group'] );
        
        //-------------------------------------------------
        // If we are accessing the registration functions,
        // lets not confuse things.
        //-------------------------------------------------
        
        // We don't want to check if we're registering and we don't want to start
        // any new headers if we're simply viewing an attachment..
        
        if ( $ibforums->input['act'] == 'Reg' or $ibforums->input['act'] == 'Attach' )
        {
        	return $this->member;
        }
        
        $this->ip_address = $ibforums->input['IP_ADDRESS'];
        $this->user_agent = substr($HTTP_USER_AGENT,0,50);
        $this->time_now   = time();
        
        $cookie = array();
        $cookie['session_id']   = $std->my_getcookie('session_id');
        $cookie['member_id']    = $std->my_getcookie('member_id');
        $cookie['pass_hash']    = $std->my_getcookie('pass_hash');
        
       
        if (! empty($cookie['session_id']) )
        {
        	$this->get_session($cookie['session_id']);
        }
        elseif (! empty($ibforums->input['s']) )
        {
        	$this->get_session($ibforums->input['s']);
        }
        else
        {
        	$this->session_id = 0;
        }
        
        //-------------------------------------------------
        // Finalise the incoming data..
        //-------------------------------------------------
        
        $ibforums->input['Privacy'] = $std->select_var( array( 
															   1 => $ibforums->input['Privacy'],
															   2 => $std->my_getcookie('anonlogin')
												      )      );
												      
		//-------------------------------------------------								  
		// Do we have a valid session ID?
		//-------------------------------------------------
		
		if ( ($this->session_id != 0) and ( ! empty($this->session_id) ) )
		{
			// We've checked the IP addy and browser, so we can assume that this is
			// a valid session.
			
			if ( ($this->session_user_id != 0) and ( ! empty($this->session_user_id) ) )
			{
				// It's a member session, so load the member.
				
				$this->load_member($this->session_user_id);
				
				// Did we get a member?
				
				if ( (! $this->member['id']) or ($this->member['id'] == 0) )
				{
					$this->unload_member();
					$this->update_guest_session();
				}
				else
				{
					$this->update_member_session();
				}
			}
			else
			{
				$this->update_guest_session();
			}
		
		}
		else
		{
			// We didn't have a session, or the session didn't validate
			
			// Do we have cookies stored?
			
			if ($cookie['member_id'] != "" and $cookie['pass_hash'] != "")
			{
				$this->load_member($cookie['member_id']);
				
				if ( (! $this->member['id']) or ($this->member['id'] == 0) )
				{
					$this->unload_member();
					$this->create_guest_session();
				}
				else
				{
					if ($this->member['password'] == $cookie['pass_hash'])
					{
						$this->create_member_session();
					}
					else
					{
						$this->unload_member();
						$this->create_guest_session();
					}
				}
			}
			else
			{
				$this->create_guest_session();
			}
		}
		
        //-------------------------------------------------
        // Set up a guest if we get here and we don't have a member ID
        //-------------------------------------------------
        
        if (! $this->member['id'])
        {
        	$this->member = $std->set_up_guest();
        	$DB->query("SELECT * from ibf_groups WHERE g_id='".$INFO['guest_group']."'");
        	$group = $DB->fetch_row();
        
			foreach ($group as $k => $v)
			{
				$this->member[ $k ] = $v;
			}
		
		}
		
        //------------------------------------------------
        // Synchronise the last visit and activity times if
        // we have some in the member profile
        //-------------------------------------------------
        
        if ($this->member['id'])
        {
        	if ( ! $ibforums->input['last_activity'] )
        	{
				if ($this->member['last_activity'])
				{
					$ibforums->input['last_activity'] = $this->member['last_activity'];
				}
				else
				{
					$ibforums->input['last_activity'] = $this->time_now;
				}
        	}
        	//------------
        	
        	if ( ! $ibforums->input['last_visit'] )
        	{
				if ($this->member['last_visit'])
				{
					$ibforums->input['last_visit'] = $this->member['last_visit'];
				}
				else
				{
					$ibforums->input['last_visit'] = $this->time_now;
				}
        	}
        
			//-------------------------------------------------
			// If there hasn't been a cookie update in 2 hours,
			// we assume that they've gone and come back
			//-------------------------------------------------
			
			if (!$this->member['last_visit'])
			{
				// No last visit set, do so now!
				
				$DB->query("UPDATE ibf_members SET last_visit='".$this->time_now."', last_activity='".$this->time_now."' WHERE id='".$this->member['id']."'");
				
			}
			else if ( (time() - $ibforums->input['last_activity']) > 300 )
			{
				// If the last click was longer than 5 mins ago and this is a member
				// Update their profile.
				
				$DB->query("UPDATE ibf_members SET last_activity='".$this->time_now."' WHERE id='".$this->member['id']."'");
				
			}
		
		}
		
		//-------------------------------------------------
        // Set a session ID cookie
        //-------------------------------------------------
        
        $std->my_setcookie("session_id", $this->session_id, -1);
        
        return $this->member;
        
    }
    
    //+-------------------------------------------------
	// Attempt to load a member
	//+-------------------------------------------------
	
    function load_member($member_id=0)
    {
    	global $DB, $std, $ibforums;
    	
     	if ($member_id != 0)
        {
            				  
            $DB->query("SELECT mod.mid as is_mod, m.id, m.name, m.mgroup, m.password, m.email, m.allow_post, m.view_sigs, m.view_avs, m.view_pop, m.view_img, m.auto_track,
                              m.mod_posts, m.language, m.skin, m.new_msg, m.show_popup, m.msg_total, m.time_offset, m.posts, m.joined, m.last_post,
            				  m.last_visit, m.last_activity, m.dst_in_use, m.view_prefs, g.*
            				  FROM ibf_members m
            				    LEFT JOIN ibf_groups g ON (g.g_id=m.mgroup)
            				    LEFT JOIN ibf_moderators mod ON (mod.member_id=m.id OR mod.group_id=m.mgroup )
            				  WHERE m.id='$member_id'");
            
            if ( $DB->get_num_rows() )
            {
            	$this->member = $DB->fetch_row();
            }
            
            //-------------------------------------------------
            // Unless they have a member id, log 'em in as a guest
            //-------------------------------------------------
            
            if ( ($this->member['id'] == 0) or (empty($this->member['id'])) )
            {
				$this->unload_member();
            }
		}
		
		unset($member_id);
	}
	
	//+-------------------------------------------------
	// Remove the users cookies
	//+-------------------------------------------------
	
	function unload_member()
	{
		global $DB, $std, $ibforums;
		
		// Boink the cookies
		
		$std->my_setcookie( "member_id" , "0", -1  );
		$std->my_setcookie( "pass_hash" , "0", -1  );
		
		$this->member['id']       = 0;
		$this->member['name']     = "";
		$this->member['password'] = "";
		
	}
    
    //-------------------------------------------
    // Updates a current session.
    //-------------------------------------------
    
    function update_member_session() {
        global $DB, $ibforums;
        
        // Make sure we have a session id.
        
        if ( (empty($this->session_id)) or ($this->session_id == 0) )
        {
        	$this->create_member_session();
        	return;
        }
        
        if (empty($this->member['id']))
        {
        	$this->unload_member();
        	$this->create_guest_session();
        	return;
        }
        
        $query = "UPDATE ibf_sessions SET " .
			     "member_name='" .$this->member['name']     ."', ".
			     "member_id='"   .$this->member['id']       ."', ".
				 "member_group='".$this->member['mgroup']   ."', ";
        
        // Append the rest of the query
        $query .= "login_type='".$ibforums->input['Privacy']."', running_time='".$this->time_now."', in_forum='".$ibforums->input['f']."', in_topic='".$ibforums->input['t']."', location='".$ibforums->input['act'].",".$ibforums->input['p'].",".$ibforums->input['CODE']."' ";
        $query .= "WHERE id='".$this->session_id."'";
        
        // Update the database
        
        $DB->query($query);
    }        
    
    //--------------------------------------------------------------------
    
    function update_guest_session() {
        global $DB, $ibforums, $INFO;
        
        // Make sure we have a session id.
        
        if ( (empty($this->session_id)) or ($this->session_id == 0) )
        {
        	$this->create_guest_session();
        	return;
        }
        
        $query  = "UPDATE ibf_sessions SET member_name='',member_id='0',member_group='".$INFO['guest_group']."'";
        $query .= ",login_type='0', running_time='".$this->time_now."', in_forum='".$ibforums->input['f']."', in_topic='".$ibforums->input['t']."', location='".$ibforums->input['act'].",".$ibforums->input['p'].",".$ibforums->input['CODE']."' ";
        $query .= "WHERE id='".$this->session_id."'";
        
        // Update the database
        
        $DB->query($query);
    } 
                    
    
    //-------------------------------------------
    // Get a session based on the current session ID
    //-------------------------------------------
    
    function get_session($session_id="") {
        global $DB, $INFO, $std;
        
        $result = array();
        
        $query = "";
        
        $session_id = preg_replace("/([^a-zA-Z0-9])/", "", $session_id);
        
        if ( !empty($session_id) )
        {
        
			if ($INFO['match_browser'] == 1)
			{
				$query = " AND browser='".$this->user_agent."'";
			}
				
			$DB->query("SELECT id, member_id, running_time, location FROM ibf_sessions WHERE id='".$session_id."' and ip_address='".$this->ip_address."'".$query);
			
			if ($DB->get_num_rows() != 1)
			{
				// Either there is no session, or we have more than one session..
				
				$this->session_dead_id   = $session_id;
				$this->session_id        = 0;
        		$this->session_user_id   = 0;
        		return;
			}
			else
			{
				$result = $DB->fetch_row();
				
				if ($result['id'] == "")
				{
					$this->session_dead_id   = $session_id;
					$this->session_id        = 0;
					$this->session_user_id   = 0;
					unset($result);
					return;
				}
				else
				{
					$this->session_id        = $result['id'];
					$this->session_user_id   = $result['member_id'];
					$this->last_click        = $result['running_time'];
        			$this->location          = $result['location'];
        			unset($result);
					return;
				}
			}
		}
    }
    
    //-------------------------------------------
    // Creates a member session.
    //-------------------------------------------
    
    function create_member_session() {
        global $DB, $INFO, $std, $ibforums;
        
        if ($this->member['id'])
        {
        	//---------------------------------
        	// Remove the defunct sessions
        	//---------------------------------
        	
			$INFO['session_expiration'] = $INFO['session_expiration'] ? (time() - $INFO['session_expiration']) : (time() - 3600);
			
			$DB->query( "DELETE FROM ibf_sessions WHERE running_time < {$INFO['session_expiration']} or member_id='".$this->member['id']."'");
			
			$this->session_id  = md5( uniqid(microtime()) );
			
			//---------------------------------
        	// Insert the new session
        	//---------------------------------
        	
			$DB->query("INSERT INTO ibf_sessions (id, member_name, member_id, ip_address, browser, running_time, location, login_type, member_group) ".
					   "VALUES ('".$this->session_id."', '".$this->member['name']."', '".$this->member['id']."', '".$this->ip_address."', '".$this->user_agent."', '".$this->time_now."', ".
					   "',,', '".$ibforums->input['Privacy']."', ".$this->member['mgroup'].")");
					   
			// If this is a member, update their last visit times, etc.
			
			if (time() - $this->member['last_activity'] > 300)
			{
				//---------------------------------
				// Reset the topics read cookie..
				//---------------------------------
				
				$std->my_setcookie('topicsread', '');
				
				$DB->query("UPDATE ibf_members SET last_visit=last_activity, last_activity='".$this->time_now."' WHERE id='".$this->member['id']."'");
				
				//---------------------------------
				// Fix up the last visit/activity times.
				//---------------------------------
				
				$ibforums->input['last_visit']    = $this->member['last_activity'];
				$ibforums->input['last_activity'] = $this->time_now;
			}
		}
		else
		{
			$this->create_guest_session();
		}
    }
    
    //--------------------------------------------------------------------
    
    function create_guest_session() {
        global $DB, $INFO, $std, $ibforums;
        
		//---------------------------------
		// Remove the defunct sessions
		//---------------------------------
		
		if ( ($this->session_dead_id != 0) and ( ! empty($this->session_dead_id) ) )
		{
			$extra = " or id='".$this->session_dead_id."'";
		}
		else
		{
			$extra = "";
		}
		
		$INFO['session_expiration'] = $INFO['session_expiration'] ? (time() - $INFO['session_expiration']) : (time() - 3600);
		
		$DB->query( "DELETE FROM ibf_sessions WHERE running_time < {$INFO['session_expiration']} or ip_address='".$this->ip_address."'".$extra);
		
		$this->session_id  = md5( uniqid(microtime()) );
		
		//---------------------------------
		// Insert the new session
		//---------------------------------
		
		$DB->query("INSERT INTO ibf_sessions (id, member_name, member_id, ip_address, browser, running_time, location, login_type, member_group) ".
				   "VALUES ('".$this->session_id."', '', '0', '".$this->ip_address."', '".$this->user_agent."', '".$this->time_now."', ".
				   "',,', '0', ".$INFO['guest_group'].")");
					   
    }
    
    //--------------------------------------------------------------------
    
        
}




?>