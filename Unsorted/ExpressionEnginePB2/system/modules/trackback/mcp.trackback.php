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
 File: mcp.trackback.php
-----------------------------------------------------
 Purpose: Trackback class - CP
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Trackback_CP {

    var $version        = '1.0';
    var $tag            = "";
    var $insideitem     = false;
	var $tb_bad_urls    = array();
	var $tb_good_urls   = array();
    var $selected_urls  = array();

    // -------------------------
    //  Constructor
    // -------------------------
    
    function Trackback_CP()
    {
    }
    // END
      
 
    // ----------------------------------------
    //  Send Trackback
    // ----------------------------------------
    
	function send_trackback($tb_data)
	{
		global $REGX, $FNS;
		
		if ( ! is_array($tb_data))
		{ 
		    return false;
		}
		
        // ----------------------------------------
        //  Pre-process data
        // ----------------------------------------
		
		$required = array('entry_id', 'entry_link', 'entry_title', 'entry_content', 'trackback_url', 'weblog_name');
		
		foreach ($tb_data as $key => $val)
		{
		    if ( ! in_array($key, $required))
		    { 
		        return false;
		    }
		    
		    switch ($key)
		    {
		        case 'trackback_url' : $$key = $this->extract_trackback_urls($val);
		            break;
		        case 'entry_content' : $$key = $FNS->char_limiter($REGX->xml_convert(strip_tags(stripslashes($val))));
		            break;
		        default              : $$key = $REGX->xml_convert(strip_tags(stripslashes($val)));
		            break;
		    }
		}
		
        // ----------------------------------------
        //  Assign our data string
        // ----------------------------------------
        
		$data = "url=".rawurlencode($entry_link)."&title=".rawurlencode($entry_title)."&blog_name=".rawurlencode($weblog_name)."&excerpt=".rawurlencode($entry_content); 
        
                
        // ----------------------------------------
        //  Send trackbacks
        // ----------------------------------------
        
        if (count($trackback_url) > 0)
        {
            foreach ($trackback_url as $url)
            {
                if ( ! $this->previously_sent_trackbacks($entry_id, $url))
                {                
                    $this->process_trackback($url, $data);
                }
            }	
        }

        return array($this->tb_good_urls, $this->tb_bad_urls);
    }	
	// END
	
	

	
    // ----------------------------------------
    //  Extract trackback URL(s)
    // ----------------------------------------
      
    function extract_trackback_urls($urls)
    {           
		// Remove the pesky white space and replace with a comma.
		
		$urls = preg_replace("/\s*(\S+)\s*/", "\\1,", $urls);
		
		// If they use commas too, then get rid of the doubles.
		
		$urls = str_replace(",,", ",", $urls);
		
		// Remove any comma that might be at the end
		
		if (substr($urls, -1) == ",")
		{
			$urls = substr($urls, 0, -1);
		}
				
		// Break into an array via commas
		
		$urls = preg_split('/[,]/', $urls);
		
		// Removes duplicates.  Reduce user error...one of our mantras
		
        $urls = array_unique($urls);
        
        array_walk($urls, array($this, 'check_trackback_url_prefix')); 
        
        return $urls;
	}
	// END
		

    // ----------------------------------------
    //  Check URL prefix for http://
    // ----------------------------------------
    
    // Via callback in array_walk

    function check_trackback_url_prefix(&$url)
    {
        $url = trim($url);

        if (substr($url, 0, 4) != "http")
        {
            $url = "http://".$url;
        }
    }
    // END



    // ----------------------------------------
    //  Previously sent trackbacks
    // ----------------------------------------
		
    function previously_sent_trackbacks($entry_id, $url)
    {
        global $DB;
                                   
        $query = $DB->query("SELECT count(*) as count FROM exp_weblog_titles WHERE entry_id = '$entry_id' AND sent_trackbacks LIKE '%$url%'");   
    
        if ($query->row['count'] == 0)
            return false;
        else
            return true;
    }
	// END
	
	
	
	
    // ----------------------------------------
    //  Process Trackback
    // ----------------------------------------
    
	function process_trackback($url, $data)
	{
        $target = parse_url($url);
	
        // ----------------------------------------
        //  Can we open the socket?
        // ----------------------------------------
	          			                
        if ( ! $fp = @fsockopen($target['host'], 80))
        {
            $this->tb_bad_urls[] = $url;
            
            return;          
        }

        // ----------------------------------------
        //  Assign path
        // ----------------------------------------
        
        $ppath = ( ! isset($target['path'])) ? $url : $target['path'];
        
        $path = (isset($target['query']) && $target['query'] != "") ? $ppath.'?'.$target['query'] : $ppath;


        // ----------------------------------------
        //  Add ID to data string
        // ----------------------------------------

        if ($id = $this->find_remote_id($url))
        {
            $data = "tb_id=".$id."&".$data;
        }
                
        // ----------------------------------------
        //  Transfter data to remote server
        // ----------------------------------------

        fputs ($fp, "POST " . $path . " HTTP/1.1\r\n" ); 
        fputs ($fp, "Host: " . $target['host'] . "\r\n" ); 
        fputs ($fp, "Content-type: application/x-www-form-urlencoded\r\n" ); 
        fputs ($fp, "Content-length: " . strlen($data) . "\r\n" ); 
        fputs ($fp, "Connection: close\r\n\r\n" ); 
        fputs ($fp, $data);
   
        // ----------------------------------------
        //  Did we make a love connection?
        // ----------------------------------------
        
        $response = "";
        
        while(!feof($fp))
            $response .= fgets($fp, 128);
        
        @fclose($fp);
        
		if ( ! eregi("<error>0</error>", $response))
		{
            $this->tb_bad_urls[] = $url;             
		}
		
        $this->tb_good_urls[] = $url;
	}
	// END
			
	
	
	// ----------------------------------------
    //  Find Trackback URL's ID
    // ----------------------------------------
    
	function find_remote_id($url) {
		
		$tb_id = "";
		
        if (strstr($url, '?'))
		{
			$tb_array = explode('/', $url);
			$tb_end   = $tb_array[count($tb_array)-1];
			$tb_array = explode('=', $tb_end);
			$tb_id    = $tb_array[count($tb_array)-1];
		}
		else
		{
		    if (ereg("/$", $url))
		    {
		        $url = substr($url, 0, -1);
		    }
		
			$tb_array = explode('/', $url);
			$tb_id    = $tb_array[count($tb_array)-1];
		}	
		
		if ( ! preg_match ("/^([0-9]+)$/", $tb_id)) 
		{
		    return false;
		}
		else
		{
		    return $tb_id;
		}		
	}
	// END
	


    // ---------------------------------------
    //  Receive a trackback
    // ---------------------------------------

	function receive_trackback()
	{
	    global $REGX, $DB, $IN, $FNS, $LANG, $LOC, $PREFS, $STAT;
	    
        if ( ! isset($_GET['ACT_1']))
        {
            return $this->trackback_response(1);
        }
            
        if ( ! is_numeric($_GET['ACT_1']))
        {
            return $this->trackback_response(1);
        }
        
        $id = $_GET['ACT_1'];
                        
        // -----------------------------------
        //  Verify and pre-process post data
        // -----------------------------------

        $required_post_data = array('url', 'title', 'blog_name', 'excerpt');
            
        foreach ($required_post_data as $val)
        {
            if ( ! isset($_POST[$val]))
            {
                return $this->trackback_response(1);
            }
            
            $_POST[$val] = $REGX->xml_convert(strip_tags($_POST[$val]));
        }

        // ----------------------------
        //  Fetch preferences 
        // ----------------------------
        
        $sql = "SELECT exp_weblog_titles.title, 
                       exp_weblog_titles.url_title, 
                       exp_weblog_titles.allow_trackbacks, 
                       exp_weblog_titles.trackback_total, 
                       exp_weblog_titles.weblog_id,
                       exp_weblogs.blog_title,
                       exp_weblogs.blog_url,
                       exp_weblogs.comment_notify,
                       exp_weblogs.comment_notify_emails,
                       exp_weblogs.trackback_max_hits
                FROM   exp_weblog_titles, exp_weblogs
                WHERE  exp_weblog_titles.weblog_id = exp_weblogs.weblog_id
                AND    exp_weblog_titles.entry_id = '".$id."'";
                
		$query = $DB->query($sql);
		
		if ($query->num_rows == 0)
		{
            return $this->trackback_response(1);
		}
		
		foreach ($query->row as $key => $val)
		{
		    $$key = $val;
		}
		
        // ----------------------------
        //  Are pings allowed?
        // ----------------------------
		
		if ($allow_trackbacks == 'n')
		{
            return $this->trackback_response(1);
		}

        // ----------------------------
        //  Spam check
        // ----------------------------

        $last_hour = $LOC->now - 3600;

        $query = $DB->query("SELECT COUNT(*) as count FROM exp_trackbacks WHERE trackback_ip = '".$IN->IP."' AND trackback_date > '$last_hour'");

		if ($query->row['count'] >= $trackback_max_hits)
		{
			return $this->trackback_response(4);
		}
		
        // ----------------------------
        //  Check for previous pings
        // ----------------------------

        $query = $DB->query("SELECT COUNT(*) as count FROM exp_trackbacks WHERE trackback_url = '".$DB->escape_str($_POST['url'])."' AND entry_id = '".$DB->escape_str($id)."'");

		if ($query->row['count'] > 0)
		{
			return $this->trackback_response(2);
		}
		
		
        // ----------------------------------------
        //   Limit size of excerpt
        // ----------------------------------------
		
		$content = $FNS->char_limiter($_POST['excerpt']);		
       
        // ----------------------------------------
        //   Do we allow duplicate data?
        // ----------------------------------------

        if ($PREFS->ini('deny_duplicate_data') == 'y')
        {
			$query = $DB->query("SELECT count(*) AS count FROM exp_trackbacks WHERE content = '".$DB->escape_str($content)."' ");
		
			if ($query->row['count'] > 0)
			{					
				return $this->trackback_response(2);
			}
        }		

        // ----------------------------
        //  Insert the trackback
        // ----------------------------
        
        $data = array(
                        'entry_id'       => $id,
                        'weblog_id'		 => $weblog_id,
                        'title'          => $_POST['title'],
                        'content'        => $content,
                        'weblog_name'    => $_POST['blog_name'],
                        'trackback_url'  => $_POST['url'],
                        'trackback_date' => $LOC->now,
                        'trackback_ip'   => $IN->IP
                     );
        
        $DB->query($DB->insert_string('exp_trackbacks', $data));
        
        if ($DB->affected_rows == 0) 
        {
            return $this->trackback_response(3);
        }
                
        // ------------------------------------------------
        // Update trackback count and "recent trackback" date
        // ------------------------------------------------     
        
		$query = $DB->query("SELECT trackback_total FROM exp_weblog_titles WHERE entry_id = '$id'");

		$trackback_total = $query->row['trackback_total'] + 1;

		$DB->query("UPDATE exp_weblog_titles SET trackback_total = '$trackback_total', recent_trackback_date = '".$LOC->now."'  WHERE entry_id = '$id'");

        // ----------------------------------------
        // Update global stats
        // ----------------------------------------
        
        $STAT->update_trackback_stats($weblog_id);


        // ----------------------------
        //  Send notification
        // ----------------------------

        if ($comment_notify == 'y' AND $comment_notify_emails != '')
        {        
            // ----------------------------
            //  Build email message
            // ----------------------------
            
			$swap = array(
							'entry_title'			=> $title,
							'comment_url'			=> $FNS->remove_double_slashes($query->row['blog_url'].'/'.$url_title.'/'),
							'sending_weblog_name'	=> stripslashes($_POST['blog_name']),
							'sending_entry_title'	=> stripslashes($_POST['title']),
							'sending_weblog_url'	=> $_POST['url']
						 );
			
			$template = $FNS->fetch_email_template('admin_notify_trackback');
			
			$email_msg = $FNS->var_replace($swap, $template['data']);
            
            // ----------------------------
            //  Send email
            // ----------------------------
            
            require PATH_CORE.'core.email'.EXT;
                        
            $email = new EEmail;
            $email->wordwrap = true;
            $email->from($PREFS->ini('webmaster_email'));	
            $email->to($comment_notify_emails); 
            $email->subject($template['title']);	
            $email->message($REGX->entities_to_ascii($email_msg));		
            $email->Send();
        }
        

        // ----------------------------
        //  Return response
        // ----------------------------

        return $this->trackback_response(0);
    
    }    
    // END
        
    
	// ----------------------------------------
    //  Send Trackback Responses
    // ----------------------------------------

	function trackback_response($code=1)
	{
		if ($code == 0)
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n<response>\n<error>0</error>\n</response>";
		elseif ($code == 1)
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n<response>\n<error>1</error>\n<message>Incomplete Information</message>\n</response>";
		elseif ($code == 2)
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n<response>\n<error>1</error>\n<message>Trackback already received</message>\n</response>";
		elseif ($code == 3)
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n<response>\n<error>1</error>\n<message>Trackback unable to be accepted</message>\n</response>";
		elseif ($code == 4)
			echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?".">\n<response>\n<error>1</error>\n<message>Trackback hourly limit exceeded for this IP address</message>\n</response>";
	}
	// END
	
	
    // -----------------------
    //  XML Start Element
    // -----------------------

    function startElement($parser, $name, $attrs)
    {
        global $DSP, $REGX;
            
        if ($this->insideitem)
        {
            $this->tag = $name;
        }
        elseif ($name == "RDF:DESCRIPTION")
        {
            $url = $attrs['TRACKBACK:PING'];
            
            $title = $attrs['DC:TITLE'];
            
            $selected = (in_array($url, $this->selected_urls)) ? "checked=\"checked\"" : "";
            
            echo $DSP->qdiv('', "<input type=\"checkbox\" name=\"TB_AUTO_{$url}\" value=\"$url\" $selected />".NBS.NBS.$title);
            
            $this->insideitem = true;            
        }
    }
    // END
  
    
    // -----------------------
    //  XML End Element
    // -----------------------

    function endElement($parser, $name)
    {
        global $insideitem, $tag; 
        
        if ($name == "RDF:DESCRIPTION")
        {
            $this->insideitem = false;
        }
    }
    // END
    
    
    // -----------------------
    //  XML CDATA
    // -----------------------

    function characterData($parser, $data)
    {
        // Nothing between the tag.
    }
    

    // ----------------------------------------
    //  Module installer
    // ----------------------------------------

    function trackback_module_install()
    {
        global $DB;        
        
        $sql[] = "INSERT INTO exp_modules (module_id, module_name, module_version, has_cp_backend) VALUES ('', 'Trackback', '$this->version', 'n')";
        $sql[] = "INSERT INTO exp_actions (action_id, class, method) VALUES ('', 'Trackback_CP', 'receive_trackback')";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }
        
        return true;
    }
    // END
    
    
    
    // ----------------------------------------
    //  Module de-installer
    // ----------------------------------------

    function trackback_module_deinstall()
    {
        global $DB;    

        $query = $DB->query("SELECT module_id FROM exp_modules WHERE module_name = 'Trackback'"); 
                
        $sql[] = "DELETE FROM exp_module_member_groups WHERE module_id = '".$query->row['module_id']."'";        
        $sql[] = "DELETE FROM exp_modules WHERE module_name = 'Trackback'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Trackback'";
        $sql[] = "DELETE FROM exp_actions WHERE class = 'Trackback_CP'";

        foreach ($sql as $query)
        {
            $DB->query($query);
        }

        return true;
    }
    // END



}
// END CLASS
?>