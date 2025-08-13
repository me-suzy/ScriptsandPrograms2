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
 File: core.functions.php
-----------------------------------------------------
 Purpose: Shared system functions.
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Functions {  
   
    var $seed = false; // Whether we've seeded our rand() function.  We only seed once per script execution
   
     
   
    // ----------------------------------------
    //  Fetch base site index
    // ----------------------------------------
 
    function fetch_site_index($add_slash = 0, $sess_id = 1)
    {
        global $PREFS, $TMPL, $SESS;
        
        $url = $PREFS->ini('site_url', 1);
                
        if (USER_BLOG !== FALSE)
        {
            $url .= USER_BLOG.'/';
        }
        
        $url .= $PREFS->ini('site_index');
        
        if ($PREFS->ini('force_query_string') == 'y')
        {
        	$url .= '?';
        }        
        
        if ($sess_id == 1 AND $PREFS->ini('user_session_type') != 'c' AND $TMPL->template_type == 'webpage')
        {
            if ($SESS->userdata['session_id'] != '')
            {
                $url .= "/S=".$SESS->userdata['session_id']."/";
            }
        }
        
        if ($add_slash == 1)
        {
            if ( ! ereg("/$", $url))
            {
                $url .= "/";
            }
        }
                
        return $url;
    } 
    // END
        

    // ----------------------------------------
    //  Create a custom URL
    // ----------------------------------------
    
    // The input to this function is parsed and added to the
    // full site URL to create a full URL/URI
    
    function create_url($segment, $trailing_slash = true, $sess_id = 1)
    {
        global $PREFS, $REGX;
        
        // Since this function can be used via a callback
        // we'll fetch the segiment if it's an array
        
        if (is_array($segment))
        {
            $segment = $segment['1'];
        }
        
        $segment = preg_replace("#[\'|\"]#", '', $segment);
        
        $segment = preg_replace("/(.+?)(&#47;|\/)index(.*?)/", "\\1\\3", $segment);
        
        // --------------------------
        //  Specials
        // --------------------------
        
        // These are exceptions to the normal path rules
        
        if ($segment == 'SITE_INDEX')
        {
            return $this->fetch_site_index();
        }
        
        if ($segment == 'LOGOUT')
        {
            $qs = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';        

            return $this->fetch_site_index(0, 0).$qs.'ACT='.$this->fetch_action_id('Member', 'member_logout');
        }
                
        // END Specials
                                
        $base = $this->fetch_site_index(0, $sess_id);
        
        $segment = $REGX->trim_slashes($segment);        
        
        $base .= '/'.$segment;
        
        if ( ! ereg("/$", $base) AND $trailing_slash == true)
        {
            $base .= '/';
        }
        
        if (ereg("index\/$", $base))
        {
            $base = substr($base, 0, -6);
        }
                        
        return $this->remove_double_slashes($base);
    }
    // END



    // ----------------------------------------
    //  Link builder
    // ----------------------------------------
    
    function linker($name, $path = '', $segment = '')
    {
        global $PREFS, $REGX;
        
        $base = $this->fetch_site_index();
        
        if ($path != 'SITE_INDEX' AND $path != '')
        {
            $path = $REGX->trim_slashes($path);        
        
            $base .= '/'.$path;
        }
        
        if ($segment != '')
        {
            $base .= '/'.$segment.'/';
        }
        
        if ( ! ereg("/$", $base))
        {
            $base .= '/';
        }
        
        $link = "<a href=\"".$base."\">".$name."</a>";
        
        return $this->remove_double_slashes($link);
    }
    // END 


    // ----------------------------------------
    //  Fetch site index with URI query string
    // ----------------------------------------
 
    function fetch_current_uri()
    { 
        global $IN;
           
        $url = $this->fetch_site_index().$IN->URI;
        
        $url = $this->remove_double_slashes($url);
        
        return $url;
    } 
    // END
    
    
    //-----------------------------------------
    //  Remove duplicate slashes from URL
    //-----------------------------------------
    
    // With all the URL/URI parsing/building, there is the potential
    // to end up with double slashes.  This is a clean-up function.

    function remove_double_slashes($str)
    {
        $str = str_replace("http://", "HTTP:SS", $str);
        $str = preg_replace("#/+#", "/", $str);
        $str = str_replace("HTTP:SS", "http://", $str);
    
        return $str;
    }
    // END

    
    // ----------------------------------------
    //  Remove session ID from string
    // ----------------------------------------
    
    // This function is used mainly by the Input class to strip
    // session IDs if they are used in public pages.
 
    function remove_session_id($str)
    {
        $str = preg_replace("#S=.+?/#", "", $str);
           
        return $str;
    } 
    // END


    //-----------------------------------------
    //  Extract path info
    //----------------------------------------- 
    
    // We use this to extract the template group/template name
    // from path variables, like {some_var path="weblog/index"}

    function extract_path($str)
    {
        global $REGX;
            
        if (preg_match("#=(.*)#", $str, $match))
        {        	
        	$path = preg_replace("#[\'|\"]#", "", $match['1']);     	
        
        	$path = $REGX->trim_slashes($path);
        	        	
        	if (eregi("index/$", $path))
        	{
        		$path = str_replace('/index', '', $path);
        	}
        	if (eregi("index$", $path))
        	{
        		$path = str_replace('/index', '', $path);
        	}
        
            return $path;
        }
        else
        {
            return 'SITE_INDEX';
        }
    }
    // END

        
    // ----------------------------------------
    //  Replace variables
    // ----------------------------------------
	
	function var_replace($data, $str)
	{
		if ( ! is_array($data))
		{
			return false;
		}
	
		foreach ($data as $key => $val)
		{
			$str = str_replace('{'.$key.'}', $val, $str);
		}
	
		return $str;
	}
	// END


    // ----------------------------------------
    //  Redirect
    // ----------------------------------------
    
    function redirect($location)
    {    
        global $PREFS;
                
        $location = str_replace('&amp;', '&', $location);
                
        switch($PREFS->ini('redirect_method'))
        {
            case 'refresh' : header("Refresh:0;url=$location");
                break;
            default        : header("location:$location");
                break;
        }
        
        exit;
    }
    // END


    // ----------------------------------------
    //  Bounce
    // ----------------------------------------
    
    function bounce($location = '')
    {
        if ($location == '')
            $location = BASE;
            
        $this->redirect($location);
        exit;
    }
    // END
    
    

    // -------------------------------------------------
    //   Convert a string into a SHA1 encrypted hash
    // -------------------------------------------------
    
    function hash($str)
    {    
        if ( ! function_exists('sha1'))
        {
            if ( ! function_exists('mhash'))
            {
				if ( ! class_exists('SHA'))
				{
					require PATH_CORE.'core.sha1'.EXT;    
				}
            
                $SH = new SHA;

                return $SH->encode_hash($str);            
            }
            else
            {
                return bin2hex(mhash(MHASH_SHA1, $str));
            }
        }
        else
        {
            return sha1($str);
        }
    }
    // END    



    // -------------------------------------------------
    //   Random number/password generator
    // -------------------------------------------------
    
    function random($type = 'encrypt', $len = 8)
    {
        if ($this->seed == FALSE)
        {
            if (phpversion() >= 4.2)
                mt_srand();
            else
                mt_srand(hexdec(substr(md5(microtime()), -8)) & 0x7fffffff);
            
            $this->seed = TRUE;
        }
                        
        switch($type)
        {
            case 'basic'	: return mt_rand();  
              break;
            case 'alpha'	:
            case 'numeric'	:
            
					$pool = ($type == 'alpha') ? "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" : "0123456789";

                    $str = '';
                
                    for ($i=0; $i < $len; $i++) 
                    {    
                        $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1); 
                    }
                    return $str;      
              break;
            case 'md5'		: return md5(uniqid(mt_rand())); 
              break; 
            case 'encrypt'	: return $this->hash(uniqid(mt_rand())); 
              break; 
        }        
    }
    // END
 
 
    
    // ----------------------------------------
    //  Form declaration
    // ----------------------------------------
    
    // This function is used by modules when they need to create forms

    function form_declaration($hidden_fields = '', $action = '', $name = '', $secure = TRUE)
    {
        global $PREFS;
        
        if ( ! is_array($hidden_fields))
        {
			$hidden_fields = array();        
        }
            
        if ($action == '')
        {
            $action = $this->fetch_site_index();
        }
        
        if (ereg("\?$", $action))
        {
        	$action = substr($action, 0, -1);
        }
        
        
        if ($name != '')
            $name = "name='{$name}' id='{$name}' ";
        
        $form  = "<form {$name}method=\"post\" action=\"".$action."\">\n";

        $form .= "<div>\n";
        
        if ($secure == TRUE)
        {
			if ($PREFS->ini('secure_forms') == 'y')
			{
				if ( ! isset($hidden_fields['XID']))
				{
					$hidden_fields = array_merge(array('XID' => '{XID_HASH}'), $hidden_fields);
				}
				elseif ($hidden_fields['XID'] == '')
				{
					$hidden_fields['XID']  = '{XID_HASH}';
				}
			}
		}
		
        foreach ($hidden_fields as $key => $val)
        {
            $form .= "<input type=\"hidden\" name=\"$key\" value=\"$val\" />\n";
        }

        $form .= "</div>\n\n";
        
        return $form;
    }
    // END
    
    
    
    // ----------------------------------------
    //  Form backtrack
    // ----------------------------------------
    
    // This function lets us return a user to a previously
    // visited page after submitting a form.  The page
    // is determined by the offset that the admin
    // places in each form
    
    function form_backtrack($offset = '')
    {
        global $SESS;
        
		$ret = $this->fetch_site_index();
		
		if ($offset != '')
		{
            if (isset($SESS->tracker[$offset]))
            {
                if ($SESS->tracker[$offset] != 'index')
                {
                    return $this->fetch_site_index().$SESS->tracker[$offset];
                }
            }
		}
		
		if (isset($_POST['RET']))
		{
			if (ereg("^-", $_POST['RET']))
			{
				$return = str_replace("-", "", $_POST['RET']);
				
				if (isset($SESS->tracker[$return]))
				{
					if ($SESS->tracker[$return] != 'index')
					{
						$ret = $this->fetch_site_index().$SESS->tracker[$return];
					}
				}
			}
			else
			{
				$ret = $_POST['RET'];
			}
		} 
		
        return $this->remove_double_slashes($ret);
    }
    // END


    // ----------------------------------------
    //    eval() 
    // ----------------------------------------
    
    // Evaluates a string as PHP
    
    function evaluate($str)
    {    
		return eval('?>'.$str.'<?php ');
		
		// ?><?php // BBEdit syntax coloring bug fix
    }
    // END



    // ----------------------------------------
    //  Delete spam prevention hashes
    // ----------------------------------------
     
    function clear_spam_hashes()
    {
        global $PREFS, $DB;
     
        if ($PREFS->ini('secure_forms') == 'y')
        {        
			$DB->query("DELETE FROM exp_security_hashes WHERE date < UNIX_TIMESTAMP()-7200");
        }    
    }
    // END



    // ----------------------------------------
    //  Set Cookie
    // ----------------------------------------
    
    function set_cookie($name = '', $value = '', $expire = '')
    {    
        global $PREFS;
        
        if ($expire == '' || ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        elseif ($expire != 0)
        {
            $expire = time() + $expire;
        }
            
        $prefix = ( ! $PREFS->ini('cookie_prefix')) ? 'exp_' : $PREFS->ini('cookie_prefix').'_';
        $path   = ( ! $PREFS->ini('cookie_path'))   ? '/'    : $PREFS->ini('cookie_path');
        $domain = ( ! $PREFS->ini('cookie_domain')) ? ''     : $PREFS->ini('cookie_domain');
        
        $value = stripslashes($value);
                    
        setcookie($prefix.$name, $value, $expire, $path, $domain, 0);
    }
    // END



    // ----------------------------------------
    //  Character limiter
    // ----------------------------------------
    
    function char_limiter($str, $num = 500)
    {
        if (strlen($str) < $num) 
        {
            return $str;
        }
        
        $str = str_replace("\n", " ", $str);        
        
        $str = preg_replace("/\s+/", " ", $str);

		if (strlen($str) <= $num)
		{
			return $str;
		}
		$str = trim($str);
		                                
        $out = "";
		        
        foreach (explode(" ", trim($str)) as $val)
        {
			$out .= $val;			
						                    	
        	if (strlen($out) >= $num)
        	{
        		return $out.'&#8230;'; 
        	}
        	
        	$out .= ' ';
        }
    }
    // END



    // ----------------------------------------
    //  Word limiter
    // ----------------------------------------
    
    function word_limiter($str, $num = 100)
    {
        if (strlen($str) < $num) 
        {
            return $str;
        }
        
        $str = str_replace("\n", " ", $str);        
        
        $str = preg_replace("/\s+/", " ", $str);
        
        $word = explode(" ", $str);
        
		if (count($word) <= $num)
		{
			return $str;
		}
                
        $str = "";
                 
        for ($i = 0; $i < $num + 1; $i++) 
        {
            $str .= $word[$i]." ";
        }

        return trim($str).'&#8230;'; 
    }
    // END


    // ----------------------------------------
    //  Encode email from template callback
    // ----------------------------------------

	function encode_email($str)
	{
		$email = (is_array($str)) ? trim($str['1']) : trim($str);
		
		$title = '';
		$email = str_replace("\"", "", $email);
		$email = str_replace("\'", "", $email);
		
		if ($p = strpos($email, "title="))
		{
			$title = substr($email, $p + 6);
			$email = trim(substr($email, 0, $p));
		}
	
		if ( ! class_exists('Typography'))
		{
			require PATH_CORE.'core.typography'.EXT;
		}
		
		return Typography::encode_email($email, $title, TRUE);
	}
	// END



    // ----------------------------------------
    //  Fetch Email Template
    // ----------------------------------------
	
	function fetch_email_template($name)
	{
		global $IN, $DB, $SESS, $PREFS;

		$query = $DB->query("SELECT template_name, data_title, template_data, enable_template FROM exp_specialty_templates WHERE template_name = '$name'");

		if ($query->row['enable_template'] == 'y')
		{
			return array('title' => $query->row['data_title'], 'data' => $query->row['template_data']);
		}
		
        if ($SESS->userdata['language'] != '')
        {
            $user_lang = $SESS->userdata['language'];
        }
        else
        {
        	if ($IN->GBL('language', 'COOKIE'))
        	{
                $user_lang = $IN->GBL('language', 'COOKIE');
        	}
        	elseif ($PREFS->ini('deft_lang') != '')
            {
                $user_lang = $PREFS->ini('deft_lang');
            }
            else
            {
                $user_lang = 'english';
            }
        }

		if ( function_exists($name))
		{
			$title = $name.'_title';
		
			return array('title' => $title(), 'data' => $name());
		}
		else
		{
			if ( ! @include(PATH_LANG.$user_lang.'/email_data'.EXT))
			{
				return array('title' => $query->row['data_title'], 'data' => $query->row['template_data']);
			}
					
			return array('title' => $query->row['data_title'], 'data' => $query->row['template_data']);
		}
	}
	// END
	

    // -----------------------------------------
    //  Create character encoding menu
    // -----------------------------------------
        
    function encoding_menu($which, $name, $selected = '')
    {
        global $DSP;       

		$files = array('languages', 'charsets');
		
		if ( ! in_array($which, $files))
		{
			return false;
		}
		
        
        $file = PATH.'lib/'.$which.EXT;    
			
		if ( ! file_exists($file)) 
		{
			return false;
		}   

		include($file);
        
		$r = $DSP->input_select_header($name);
		
		foreach ($$which as $key => $val)
		{
			if ($which == 'languages')
			{
				$r .= $DSP->input_select_option($val, $key, ($selected == $val) ? 1 : '');
			}
			else
			{
				$r .= $DSP->input_select_option($val, $val, ($selected == $val) ? 1 : '');
			}
		}
		
		$r .= $DSP->input_select_footer();
		
		return $r;
	}
	// END



    // -----------------------------
    //  Create Directory Map
    // -----------------------------   

    function create_directory_map($source_dir)
    {
        if ( ! isset($filedata))
            $filedata = array();
        
        if ($fp = @opendir($source_dir))
        { 
            while (false !== ($file = readdir($fp)))
            {
                if (is_dir($source_dir.$file) && $file !== '.' && $file !== '..') 
                {       
                    $temp_array = array();
                     
                    $temp_array = $this->create_directory_map($source_dir.$file."/");   
                    
                    $filedata[$file] = $temp_array;
                }
                elseif (substr($file, 0, 1) != ".")
                {
                    $filedata[] = $file;
                }
            }         
            return $filedata;        
        } 
    }
    // END

 

    // -----------------------------------------
    //  Fetch names of installed language packs
    // -----------------------------------------
        
    function language_pack_names($default)
    {
        global $PREFS;
            
        $source_dir = PATH_LANG;
    
        $filelist = array();
    
        if ($fp = @opendir($source_dir)) 
        { 
            while (false !== ($file = readdir($fp))) 
            { 
                $filelist[count($filelist)] = $file;
            } 
        } 
    
        closedir($fp); 
        
        sort($filelist);

        $r  = "<div class='default'>";
		$r .= "<select name='deft_lang' class='select'>\n";
            
        for ($i =0; $i < sizeof($filelist); $i++) 
        {
            if ( ! eregi(".php$",  $filelist[$i]) AND 
                 ! eregi(".html$",  $filelist[$i]) AND
                 ! eregi(".DS_Store",  $filelist[$i]) AND
                 ! eregi("\.",  $filelist[$i])
               )
                {
                    $selected = ($filelist[$i] == $default) ? " selected='selected'" : '';
                    
					$r .= "<option value='{$filelist[$i]}'{$selected}>".ucfirst($filelist[$i])."</option>\n";
                }
        }        

        $r .= "</select>";
        $r .= "</div>";

        return $r;
    }
    // END    
    
    
    
    // -----------------------------------------
    //  Delete cache files
    // -----------------------------------------
        
    function clear_caching($which, $sub_dir = '')
    {
        global $IN, $DB;
    
        $actions = array('page', 'tag', 'db', 'all');
        
        if ( ! in_array($which, $actions))
            return;
            
        if ($sub_dir != '')
        {
            $sub_dir = '/'.md5($sub_dir).'/';
        }
                        
        switch ($which)
        {
            case 'page' : $this->delete_directory(PATH_CACHE.'page_cache'.$sub_dir);
                break;
            case 'db'   : $this->delete_directory(PATH_CACHE.'db_cache'.$sub_dir);
                break;
            case 'tag'  : $this->delete_directory(PATH_CACHE.'tag_cache'.$sub_dir);
                break;
            case 'all'  : 
                          $this->delete_directory(PATH_CACHE.'page_cache'.$sub_dir);
                          $this->delete_directory(PATH_CACHE.'db_cache'.$sub_dir);
                          $this->delete_directory(PATH_CACHE.'tag_cache'.$sub_dir);
                break;
        }            
    }
    // END
    
    
       
    // -----------------------------------------
    //  Delete Direcories
    // -----------------------------------------

    function delete_directory($path, $del_root = FALSE)
    {
        $current_dir = @opendir($path);
        
        while($filename = @readdir($current_dir))
        {        
            if (@is_dir($path.'/'.$filename) and ($filename != "." and $filename != ".."))
            {
                $this->delete_directory($path.'/'.$filename, TRUE);
            }
            elseif($filename != "." and $filename != "..")
            {
                @unlink($path.'/'.$filename);
            }
        }
        
        @closedir($current_dir);
        
        if ($del_root == TRUE)
        {
            @rmdir($path);
        }
    }
    // END
 
 
 
    // -----------------------------------------
    //  Delete Expired Files
    // -----------------------------------------
    
    // We use this to delete old query cache files

    function delete_expired_files($path = '', $del_root = FALSE)
    {
    	global $PREFS;
    	
    	if ($path == '')
    		return;
    		
    	$expiration = $PREFS->ini('db_cache_refresh');
    	
    	if ($expiration == '' OR ! is_numeric($expiration))
    	{
			$expiration = 60*60*24*30;  // Set expiration to 30 days
    	}
    	
    	$now = time();

        $current_dir = @opendir($path);
        
        while($filename = @readdir($current_dir))
        {        
            if (@is_dir($path.'/'.$filename) and ($filename != "." and $filename != ".."))
            {
            	if ((filemtime($path.'/'.$filename.'/.') + $expiration) < $now)
            	{
					$this->delete_directory($path.'/'.$filename, TRUE);
				}
            }
            elseif ($filename != "." and $filename != "..")
            { 
            	if ((filemtime($path.'/'.$filename) + $expiration) < $now)
            	{
					@unlink($path.'/'.$filename);
				}
            }
        }
        
        @closedir($current_dir);
        
        if ($del_root == TRUE)
        {
            @rmdir($path);
        }
    }
    // END




    // -----------------------------------------
    //  Fetch allowed weblogs
    // -----------------------------------------
    
    // This function fetches the ID numbers of the
    // weblogs assigned to the currently logged in user.

    function fetch_assigned_weblogs()
    {
        global $SESS, $DB;
    
        $allowed_blogs = array();
        
        // If the 'weblog_id' index is not zero, it means the
        // current user has been assigned a specifc blog
        
        if ($SESS->userdata['weblog_id'] != 0)
        {
            $allowed_blogs[] = $SESS->userdata['weblog_id'];
        }
        else
        {
            if ($SESS->userdata['group_id'] == 1)
            {
				$query = $DB->query("SELECT weblog_id FROM exp_weblogs WHERE is_user_blog = 'n'");
				
				foreach ($query->result as $row)
				{
                    $allowed_blogs[] = $row['weblog_id'];
            	}
            }
            else
            {
                foreach ($SESS->userdata['assigned_weblogs'] as $key => $val)
                {
                    $allowed_blogs[] = $key;
                }
            }
        }
        
        return $allowed_blogs;
    }
    // END

 
    // -----------------------------------------
    //  Fetch allowed template group
    // -----------------------------------------
    
    // This function fetches the ID number of the
    // template assigned to the currently logged in user.

    function fetch_assigned_template_group()
    {
        global $SESS;
    
        $allowed_tg = 0;
                
        if ($SESS->userdata['tmpl_group_id'] != 0)
        {
            $allowed_tg = $SESS->userdata['tmpl_group_id'];
        }
        
        return $allowed_tg;
    }
    // END
 
 
 
    // ----------------------------------------------
    //  Log Referrer data
    // ----------------------------------------------    
  
    function log_referrer()
    {  
        global $IN, $PREFS, $DB, $LOC, $REGX;
        
        if ($PREFS->ini('log_referrers') == 'n')
        {
            return;
        }
        
        if ( ! isset($_SERVER['HTTP_REFERER']))
        {
            return;
        }
        
        $site_url 	=& $PREFS->ini('site_url');
        $ref 		= ( ! isset($_SERVER['HTTP_REFERER'])) 		? '' : $REGX->xss_clean($_SERVER['HTTP_REFERER']);
        $agent 		= ( ! isset($_SERVER['HTTP_USER_AGENT'])) 	? '' : $REGX->xss_clean($_SERVER['HTTP_USER_AGENT']);
                
        if ($ref != '' && ! ereg("^$site_url", $ref))
        {         	
        	// --------------------------------
        	// Check against Blacklist
        	// --------------------------------
        	
        	if (in_array($DB->prefix.'blacklisted', $DB->fetch_tables()))
        	{
        		$query = $DB->query("SELECT * FROM exp_blacklisted");
        		
        		foreach($query->result as $row)
        		{
        			$values = explode('|',$row['blacklisted_value']);
        			
        			if (sizeof($values) == 0)
        			{
        				continue;
        			}
        			
        			switch($row['blacklisted_type'])
        			{
        				case 'url':
        					foreach($values as $bad_url)
        					{
        						if (strpos($ref, $bad_url) !== false)
        						{
        							return FALSE;
        						}        					
        					}
        				break;
        				case 'agent':
        					if ($agent == '')
        					{
        						continue;
        					}
        					
        					foreach($values as $bad_agent)
        					{
        						if (strpos($agent, $bad_agent) !== false)
        						{
        							return FALSE;
        						}        					
        					}        				
        				break;
        				case 'ip':
        					foreach($values as $bad_ip)
        					{
        						if (strpos($IN->IP, $bad_ip) !== false)
        						{
        							return FALSE;
        						}        					
        					}        					
        				break;
        			}
        		}
        	}        	
        	
        	// --------------------------------
        	// INSERT into database
        	// --------------------------------  
        	
			$ref_to = $this->fetch_current_uri();
			
			$insert_data = array (  'ref_id'  	=>  '',
									'ref_from' 	=> $ref,
									'ref_to'  	=> $ref_to,
									'user_blog'	=> USER_BLOG,
									'ref_ip'   	=> $IN->IP,
									'ref_date'	=> $LOC->now,
									'ref_agent'	=> $agent
									);
	
			$DB->query($DB->insert_string('exp_referrers', $insert_data));
        }
    }
	// END    
    
        
    // ----------------------------------------------
    //  Fetch Action ID
    // ----------------------------------------------    
  
    function fetch_action_id($class, $method)
    {  
        global $DB;
        
        if ($class == '' || $method == '')
        {
            return false;
        }

        $query = $DB->query("SELECT action_id FROM exp_actions WHERE class= '$class' AND method = '$method'");
                
        if ($query->num_rows == 0)
        {
            return '';
        }
        
        return $query->row['action_id'];
    }
    // END  
        
    

    //---------------------------------------------------------------    
    //  SQL "AND" or "OR" string for conditional tag parameters
    //---------------------------------------------------------------

    // This function lets us build a specific type of query
    // needed when tags have conditional parameters:
    //
    // {exp:some_tag  param="value1|value2|value3"}
    //
    // Or the parameter can contain "not":
    //
    // {exp:some_tag  param="not value1|value2|value3"}
    //
    // This function explodes the pipes and constructs a series of AND
    // conditions or OR conditions
    
    // We should probably put this in the DB class but it's not
    // something that is typically used

    function sql_andor_string($str, $field, $prefix = '')
    {
    	global $DB;
    
        if ($str == "" || $field == "")
        {
            return '';
        }
            
        $sql = '';
        
        if ($prefix != '')
            $prefix .= '.';
    
        if (preg_match("/\|/", $str))
        {
            $ex = explode("|", $str);
        
            if (count($ex) > 0)
            {                
                if (ereg("^not ", $ex['0']))
                {
                    $ex['0'] = substr($ex['0'], 3);
                    
                    for ($i = 0; $i < count($ex); $i++)
                    {
                        $ex[$i] = trim($ex[$i]);
                        
                        if ($ex[$i] != "")
                        {
                            $sql .= "AND $prefix"."$field != '".$DB->escape_str($ex[$i])."' "; 
                        }   
                    }                    
                }
                else
                {
                    $sql .= "AND (";
                
                    for ($i = 0; $i < count($ex); $i++)
                    {
                        $ex[$i] = trim($ex[$i]);
                        
                        if ($ex[$i] != "")
                        {
                            $sql .= $prefix.$field." = '".$DB->escape_str($ex[$i])."' OR ";
                        }  
                    }
                    
                    $sql = substr($sql, 0, - 3);
                    
                    $sql .= ")";
                }             
            }
        }
        else
        {   
            if (ereg("^not ", $str))
            {
                $str = trim(substr($str, 3));
                
               $sql .= "AND ".$prefix.$field." != '".$DB->escape_str($str)."'";
            }
            else
            {
               $sql .= "AND ".$prefix.$field." = '".$DB->escape_str($str)."'";
            }
        }

        return $sql;        
    }
    // END

}
// END CLASS
?>