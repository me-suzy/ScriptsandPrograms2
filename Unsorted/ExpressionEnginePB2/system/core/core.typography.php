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
 File: core.typography.php
-----------------------------------------------------
 Purpose: Typographic rendering class
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}



class Typography {

    var $text_format    = 'xhtml';  // xhtml, br, none, or lite
    var $html_format    = 'safe';   // safe, all, none
    var $auto_links     = 'y'; 
    var $allow_img_url  = 'n';
    var $parse_images   = false;
    var $encode_email   = true;
    var $use_span_tags  = true;
    var $popup_links    = true;
    var $smileys        = false;
    var $emoticon_path  = '';
    var $site_index		= '';
    var $word_censor    = false;
    var $censored_words = array();
    var $file_paths     = array();
    var $text_fmt_types	= array('xhtml', 'br', 'none', 'lite');
    var $html_fmt_types	= array('safe', 'all', 'none');
    var $yes_no_syntax	= array('y', 'n');
    
    
    //-------------------------------------
    //  Allowed tags
    //-------------------------------------
    
    // Note: The decoding array is associative, allowing more precise mapping
           
    var $safe_encode = array('b', 'i', 'u', 'em', 'strike', 'strong', 'pre', 'code', 'blockquote');
    
    var $safe_decode = array(
                                'b'             => 'b', 
                                'i'             => 'i',
                                'u'             => 'u', 
                                'em'            => 'em', 
                                'strike'        => 'strike', 
                                'strong'        => 'strong', 
                                'pre'           => 'pre', 
                                'code'          => 'pre', 
                                'blockquote'    => 'blockquote',
                                'quote'         => 'blockquote'
                             );
    


    //-------------------------------------
    //  Constructor
    //-------------------------------------

    function Typography($parse_images = true)
    {
        global $PREFS, $FNS;
        
        if (REQ == 'CP')
        {
			$this->site_index = $FNS->fetch_site_index();        
        }
        
        if ($parse_images == TRUE)
        {
            $this->fetch_file_paths();
        }
        
        $this->parse_images = $parse_images;
            
        //-------------------------------------
        //  Fetch emoticon prefs
        //-------------------------------------
        
        if ($PREFS->ini('enable_emoticons') == 'y')
        {
            if (is_file(PATH_MOD.'emoticon/emoticons'.EXT))
            {
                require PATH_MOD.'emoticon/emoticons'.EXT;
                
                if (is_array($smileys))
                {
                    $this->smileys = $smileys;
                    
                    $this->emoticon_path = $PREFS->ini('emoticon_path', 1);
                }
            }
        }

        //-------------------------------------
        //  Fetch word censoring prefs
        //-------------------------------------
        
        if ($PREFS->ini('enable_censoring') == 'y')
        {
            if ($PREFS->ini('censored_words') != '')
            {
                $words = preg_replace("/\s+/", "", trim($PREFS->ini('censored_words')));
                
				$words = str_replace('||', '|', $words);
        
                if (substr($words, -1) == "|")
                {
                    $words = substr($words, 0, -1);
                }
                        
                $this->censored_words = explode("|", $words);
                
                if (count($this->censored_words) > 0)
                {
                    $this->word_censor = TRUE;
                }
            }
        }
    }
    // END
    

    // ----------------------------------------
    //  Fetch file upload paths
    // ----------------------------------------

    function fetch_file_paths()
    {
        global $DB;
        
        $query = $DB->query("SELECT id, url FROM exp_upload_prefs");
        
        if ($query->num_rows == 0)
        {
            return;
        }
                
        foreach ($query->result as $row)
        {            
            $this->file_paths[$row['id']] = $row['url'];
        }
    }
    // END


    // ----------------------------------------
    //  Parse file paths
    // ----------------------------------------

    function parse_file_paths($str)
    {
        global $DB;
        
        if ($this->parse_images == FALSE)
        {
            return $str;
        }
        
        if (count($this->file_paths) == 0)
        {
            return $str;
        }

        foreach ($this->file_paths as $key => $val)
        {
            $str = str_replace("{filedir_".$key."}", $val, $str);
        }

        return $str;
    }
    // END


    //-------------------------------------
    //  Typographic parser
    //-------------------------------------
    
    // Note: The processing order is very important in this function so don't change it!
    
    function parse_type($str, $prefs = '')
    {
    	global $REGX;
    	     
        if ($str == '')
        {
            return;    
        }
        
        //-------------------------------------
        //  Encode PHP tags
        //-------------------------------------    
        
        // Before we do anything else, we'll convert PHP tags into character entities.
        // This is so that PHP submitted in weblog entries, comments, etc. won't get parsed.
        // Since you can enable templates to parse PHP, it would open up a security
        // hole to leave PHP submitted in entries and comments intact.
        
		$str = $REGX->encode_php_tags($str);

        //-------------------------------------
        //  Encode EE tags
        //-------------------------------------    
		
		// Next, we need to encode EE tags contained in entries so that they don't get parsed
				
		$str = $REGX->encode_ee_tags($str);    
    
        //-------------------------------------
        //  Set up our preferences
        //-------------------------------------    
        
        if (is_array($prefs))
        {
            if (isset($prefs['text_format']) AND in_array($prefs['text_format'], $this->text_fmt_types))
            {
                $this->text_format =& $prefs['text_format'];
            }
        
            if (isset($prefs['html_format']) AND in_array($prefs['html_format'], $this->html_fmt_types))
            {
                $this->html_format =& $prefs['html_format'];
            }
        
            if (isset($prefs['auto_links']) AND in_array($prefs['auto_links'], $this->yes_no_syntax))
            {
                $this->auto_links =& $prefs['auto_links'];
            }

            if (isset($prefs['allow_img_url'])  AND in_array($prefs['allow_img_url'], $this->yes_no_syntax))
            {
            	$this->allow_img_url =& $prefs['allow_img_url'];
            }
        }
        
        //-------------------------------------
        //  Fix emoticon bug
        //------------------------------------- 
        
        $str = str_replace('>:-(', ':angry:', $str);
        $str = str_replace('>:(',  ':mad:',   $str);
        
        //-------------------------------------
        //  Strip IMG tags if not allowed
        //------------------------------------- 

        if ($this->allow_img_url == 'n')
        {
            $str = $this->strip_images($str);
        }

        //-------------------------------------
        //  Format HTML
        //-------------------------------------  
    
        $str = $this->format_html($str);

        //-------------------------------------
        //  Auto-link URLs and email addresses
        //------------------------------------- 
                
        if ($this->auto_links == 'y' AND $this->html_format != 'none')
        {
            $str = $this->auto_linker($str);
        }

        //-------------------------------------
        //  Decode pMcode
        //-------------------------------------  
    
        $str = $this->decode_pmcode($str);


        //-------------------------------------
        //  Auto XHTML Typography
        //-------------------------------------    

        switch ($this->text_format)
        {
            case 'xhtml' : $str = $this->xhtml_typography($str);
                break;
            case 'lite'  : $str = $this->light_xhtml_typography($str);  // Used with weblog entry titles
                break;
            case 'br'    : $str = $this->nl2br_except_pre($str);
                break;
        }


        //-------------------------------------
        //  Parse file paths (in images)
        //------------------------------------- 

        $str = $this->parse_file_paths($str);
        
        
        //-------------------------------------
        //  Parse emoticons
        //------------------------------------- 

        $str = $this->emoticon_replace($str);
        
        
        //-------------------------------------
        //  Parse censored words
        //------------------------------------- 

        $str = $this->filter_censored_words($str);
        
        
        //------------------------------------------
        //  Decode and spam-protect email addresses
        //------------------------------------------ 
        
        $str = $this->decode_emails($str);
        

        return $str;
    }
    // END


    //-------------------------------------
    //  Format HTML
    //-------------------------------------

    function format_html(&$str)
    {
    	global $REGX;
    
        $html_options = array('all', 'safe', 'none');
    
        if ( ! in_array($this->html_format, $html_options))
        {
            $this->html_format = 'safe';
        }    
    
        if ($this->html_format == 'all')
        {
            return $str;
        }

        if ($this->html_format == 'none')
        {
            return $this->encode_tags($str);
        }
    
        //-------------------------------------
        //  Permit only safe HTML
        //-------------------------------------
        
        $str = $REGX->xss_clean($str);
        
        // We strip any JavaScript event handlers from image links or anchors
        // This prevents cross-site scripting hacks.
        
     	$js = array(   
						'onBlur',
						'onChange',
						'onClick',
						'onFocus',
						'onLoad',
						'onMouseOver',
						'onmouseup',
						'onmousedown',
						'onSelect',
						'onSubmit',
						'onUnload',
						'onkeypress',
						'onkeydown',
						'onkeyup',
						'onresize'
					);
        
        
		foreach ($js as $val)
		{
			$str = preg_replace("/<img src\s*=(.+?)".$val."\s*\=.+?\>/i", "<img src=\\1 />", $str);
			$str = preg_replace("/<a href\s*=(.+?)".$val."\s*\=.+?\>/i", "<a href=\\1>", $str);			
		}        
        
        // Turn <br /> tags into newlines
        
		$str = preg_replace("#<br>|<br />#i", "\n", $str);
		
		// Strip paragraph tags
		
		$str = preg_replace("#<p>|</p>#i",    "",   $str);

        // Convert allowed HTML to pMcode
        
        foreach($this->safe_encode as $val)
        {
            $str = preg_replace("#<".$val.">(.+?)</".$val.">#si", "[$val]\\1[/$val]", $str);
        }

        // Convert anchors to pMcode
        // We do this to prevent allowed HTML from getting converted in the next step
        
        $str = preg_replace("#<a\s+href=[\"'](\S+?)[\"'](.*?)\>(.*?)</a>#si", "[url=\"\\1\"\\2]\\3[/url]", $str);

        // Convert image tags pMcode

		$str = str_replace("/>", ">", $str);
        
        $str = preg_replace("#<img\s+src=\s*[\"'](.+?)[\"'](.*?)\s*\>#si", "[img]\\1\\2[/img]", $str);

        $str =& preg_replace( "#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)\.(jpg|jpeg|gif|png)#i", "[img]http\\4://\\5\\6.\\7[/img]", $str);

        return $this->encode_tags($str);
    }
    // END



    //-------------------------------------
    //  Auto link URLs and email addresses
    //-------------------------------------

    function auto_linker(&$str)
    {
    	global $PREFS;
    	  
        $str .=' ';
        
        // We don't want any links that appear in the control panel (in weblog entries, comments, etc.)
        // to point directly at URLs.  Why?  Becuase the control panel URL will end up in people's referrer logs, 
        // This would be a bad thing.  So, we'll point all links to the "bounce server"
                
		$qm = ($PREFS->ini('force_query_string') == 'y') ? '' : '?';

        $bounce = (REQ == 'CP') ? $this->site_index.$qm.'URL=' : '';
        
        $pop = ($this->popup_links == TRUE) ? " target=\"_blank\" " : "";
    
        // Clear period from the end of URLs
        $str =& preg_replace( "#(^|\s|\()((http://|https://|www\.)\w+[^\s\)]+)\.([\s\)])#i", "\\1\\2\\4", $str);
        
        // Auto link URL
        $str =& preg_replace( "#(^|\s|\()((http(s?)://)|(www\.))(\w+[^\s\)\<]+)#i", "\\1<a href=\"".$bounce."http\\4://\\5\\6\"$pop>http\\4://\\5\\6</a>", $str);
        
        // Clear period from the end of emails
        $str =& preg_replace("#(^|\s|\()([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)\.([\s\)])#i","\\1\\2@\\3.\\4\\5",$str);
        
        // Auto link email
        $str =& preg_replace("/(^|\s|\()([a-zA-Z0-9_\.\-]+)@([a-zA-Z0-9\-]+)\.([a-zA-Z0-9\-\.]*)/i", "\\1[email]\\2@\\3.\\4[/email]", $str);
                
        return substr($str, 0, -1);
    }
    // END



    //-------------------------------------
    //  Decode pMcode
    //-------------------------------------

    function decode_pmcode(&$str)
    {
        //-------------------------------------
        //  Decode pMcode array map
        //-------------------------------------
        
        foreach($this->safe_decode as $key => $val)
        {
            $str = preg_replace("#\[".$key."\](.+?)\[/".$key."\]#si", "<$val>\\1</$val>", $str);
        }
        
        
        //-------------------------------------
        //  Decode color tags
        //-------------------------------------
        
        if ($this->use_span_tags == TRUE)
        {
            $str = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "<span style=\"color:\\1;\">\\2</span>",$str);
        }    
        else
        {
            $str = preg_replace("/\[color=(.*?)\](.*?)\[\/color\]/si", "<font color=\"\\1\">\\2</font>", $str);
        }
        
        //-------------------------------------
        //  Decode size tags
        //-------------------------------------

        if ($this->use_span_tags == TRUE)
        {
            $str = preg_replace_callback("/\[size=(.*?)\](.*?)\[\/size\]/si", array($this, "font_matrix"),$str);
        }    
        else
        {
            $str = preg_replace("/\[size=(.*?)\](.*?)\[\/size\]/si", "<font color=\"\\1\">\\2</font>", $str);
        }


        //-------------------------------------
        // Add http:// to URLs that lack it
        //-------------------------------------

		$str = preg_replace("/\[url=www\.(.*?)\]/i", "[url=http://www.\\1]", $str);
		$str = preg_replace("/\[url=\"www\.(.*?)\]/i", "[url=\"http://www.\\1]", $str);
		$str = preg_replace("/\[url]www\.(.*?)\[\/url]/i", "[url]http://www.\\1[/url]", $str);
		
        //-------------------------------------
        // Decode URLs with quotes
        //-------------------------------------
      
        // [url="http://www.somesite.com"]somesite[/url]

        $str = preg_replace("/\[url=\"(.*?)\](.*?)\[\/url\]/i", "<a href=\"\\1>\\2</a>", $str);


        //-------------------------------------
        // Decode URLs without quotes
        //-------------------------------------
      
        // [url=http://www.somesite.com]somesite[/url]

        $str = preg_replace_callback("/\[url=(.*?)\](.*?)\[\/url\]/i", array($this, "create_link"), $str);

        // [url]www.somesite[/url]

        $str = preg_replace_callback("/\[url](.*?)\[\/url\]/i", array($this, "create_link"), $str);


        //-------------------------------------
        // Image tags
        //-------------------------------------

        // [img] and [/img]
        
        if ($this->allow_img_url == 'y')
        {
            $str = preg_replace("/\[img\](.*?)\[\/img\]/i", "<img src=\\1 />", $str);
        }
        else
        {
            $str = preg_replace("/\[img\](.*?)\[\/img\]/i", "\\1", $str);
        }
        
        // Add quotes back to image tag if missing
        
        if ( ! preg_match("/\<img src=[\"|\'].+?[\"|\'].*?\>/i", $str))
        {
			$str = preg_replace("/<img src=(.+?)\s+(.*?)\/\>/i", "<img src=\"\\1\" \\2/>", $str);
        }
        
        //-------------------------------------
        // Style tags
        //-------------------------------------
        
        // [style=class_name]stuff..[/style]  
    
        $str = preg_replace("/\[style=(.*?)\](.*?)\[\/style\]/si", "<span class=\"\\1\">\\2</span>", $str);    


        return $str;
    }
    // END
    
    
    
    //-------------------------------------
    //  Format URL via callback
    //-------------------------------------

    function create_link($matches)
    {            
        $url =& $matches['1'];
    
        if ( ! stristr($url, "http://")) 
        {
            $url = "http://".$url;
        }
        
        if (isset($matches['2']))
        {
            return "<a href=\"".$url."\">".$matches['2']."</a>";
        }
        else
        {
            return "<a href=\"".$url."\">".$matches['1']."</a>";
        }
    }
    // END
    

    
    //-----------------------------------------
    // Decode and spam protect email addresses
    //-----------------------------------------

    function decode_emails($str)
    {                    
        // [email=your@yoursite]email[/email]

        $str = preg_replace_callback("/\[email=(.*?)\](.*?)\[\/email\]/i", array($this, "create_mailto"),$str);
        
        // [email]joe@xyz.com[/email]

        $str = preg_replace_callback("/\[email\](.*?)\[\/email\]/i", array($this, "create_mailto"),$str);
        
        return $str;
    }
    // END
    

    //-------------------------------------
    //  Format Email via callback
    //-------------------------------------

    function create_mailto($matches)
    {   
        $title = ( ! isset($matches['2'])) ? $matches['1'] : $matches['2'];
    
        if ($this->encode_email == TRUE)
        {
            return $this->encode_email($matches['1'], $title, TRUE);
        }
        else
        {
            return "<a href=\"mailto:".$matches['1']."\">".$title."</a>";        
        }
    }
    // END
    

    //----------------------------------------
    //  Font sizing matrix via callback
    //----------------------------------------

    function font_matrix($matches)
    {
        switch($matches['1'])
        {
            case 1  : $size = '9px';
                break;
            case 2  : $size = '11px';
                break;
            case 3  : $size = '14px';
                break;
            case 4  : $size = '16px';
                break;
            case 5  : $size = '18px';
                break;
            case 6  : $size = '20px';
                break;
            default : $size = '11px';
                break;
        }
    
        return "<span style=\"font-size:".$size.";\">".$matches['2']."</span>";
    }
    // END

    
    
    //-------------------------------------
    //  Encode tags
    //-------------------------------------
    
    function encode_tags($str) 
    {  
        $str = str_replace("<", "&lt;", $str);
        $str = str_replace(">", "&gt;", $str);
    
        return $str;
    }
    // END



    //-------------------------------------
    //  Strip IMG tags
    //-------------------------------------

    function strip_images(&$str)
    {    
        $str =& preg_replace("#<img\s+.*?src\s*=\s*[\"'](.+?)[\"'].*?\>#", "\\1", $str);
        $str =& preg_replace("#<img\s+.*?src\s*=\s*(.+?)\s*\>#", "\\1", $str);
                
        return $str;
    }
    // END



    //-------------------------------------
    //  Emoticon replacement
    //-------------------------------------

    function emoticon_replace($str)
    {
        if ($this->smileys == FALSE)
        {
            return $str;
        }        
        
        foreach ($this->smileys as $key => $val)
        {        
            $str = str_replace($key, "<img src=\"".$this->emoticon_path.$this->smileys[$key]['0']."\" width=\"".$this->smileys[$key]['1']."\" height=\"".$this->smileys[$key]['2']."\" alt=\"".$this->smileys[$key]['3']."\" border=\"0\" />", $str);
        }
        
        return $str;
    }
    // END



    //-------------------------------------
    //  Word censor
    //-------------------------------------

    function filter_censored_words($str)
    {
        if ($this->word_censor == FALSE)
        {
            return $str;    
        }
    
        foreach ($this->censored_words as $badword)
        {        
            $str = preg_replace("/([^A-Za-z])".$badword."([^A-Za-z])/si","\\1####\\2", $str);
        }
        
        return $str;
    }
    // END



    //-------------------------------------
    //  NL to <br /> - Except within <pre>
    //-------------------------------------
    
    function nl2br_except_pre($str)
    {
        $ex = explode("pre>",$str);
        $ct = count($ex);
        
        $newstr = "";
        
        for ($i = 0; $i < $ct; $i++)
        {
            if (($i % 2) == 0)
                $newstr .= nl2br($ex[$i]);
            else 
                $newstr .= $ex[$i];
            
            if ($ct - 1 != $i) 
                $newstr .= "pre>";
        }
        
        return $newstr;
    }
    // END


    //-------------------------------------
    //  Convert ampersands to entities
    //-------------------------------------

    function convert_ampersands(&$str)
    {
        $str =& preg_replace("/&#(\d+);/", "AMP14TX903DVGHY4QW\\1;", $str);
        $str =& preg_replace("/&(\w+);/",  "AMP14TX903DVGHY4QW\\1;", $str);
        
        $str =& str_replace("&", "&amp;", $str);
        
        $str =& preg_replace("/AMP14TX903DVGHY4QW(\d+);/","&#\\1;", $str);
        $str =& preg_replace("/AMP14TX903DVGHY4QW(\w+);/","&\\1;",  $str);
        
        return $str;
        
    }
    // END


    //-------------------------------------------
    //  Auto XHTML Typography - light version
    //-------------------------------------------    
    
    // We use this for weblog entry titles.  It allows us to 
    // format only the various characters without adding <p> tags
    
    function light_xhtml_typography($str)
    {    
        $str = ' '.$str.' ';
            
        $table = array(
        
                        "\"' "                  => "&#8221;&#8217; ",
                        "\"'"                   => "&#8220;&#8216;",
                        
                        " \""                   => " &#8220;",
                        " '"                    => " &#8216;",
                        "'"                     => "&#8217;",
                        
                        "&#8216;\""             => "&#8216;&#8220;",
                    
                        "\" "                   => "&#8221; ",
                        "\"."                   => "&#8221;.",
                        "\","                   => "&#8221;,",
                        "\";"                   => "&#8221;;",
                        "\":"                   => "&#8221;:",
                        "\"!"                   => "&#8221;!",
                        "\"?"                   => "&#8221;?",
                        
                        " -- "                  => "&#8212;",
                        "... "                  => "&#8230; ",
                    
                        ".  "                   => ".&nbsp; ",
                        "?  "                   => "?&nbsp; ",
                        "!  "                   => "!&nbsp; ",
                        ":  "                   => ":&nbsp; ",
        );
        
        foreach ($table as $key => $val)
        {
            $str =& str_replace($key, $val, $str);
        }
        
        return substr(substr($str, 0, -1), 1);;
    }
    // END



    //-------------------------------------
    //  Auto XHTML Typography
    //-------------------------------------    
    
    function xhtml_typography($str)
    {    
        if ($str == '')
            return;    
    
        // Define temporary markers
        $nl = 'N848Ff5f9a66a5ffb627cdbDce6N';
        $dq = 'D5ffbFR627fTs3ks0097RHGH5w2D';
        $sq = 'S5GEf899adfqekrFR62700WWde4S';
        $el = 'E57Uhr5IImB03YQwe3X4X50kryuE';
        $pt = 'Y573Bdd7I4DWddQwe3X48dkwiueH';
        
            
        // We don't want to add an opening <p> tag if the first
        // thing in an entry is a <blockquote>, <pre>, <code>, etc.	
            
        if (substr($str, 0, 5) == "<bloc"  ||
            substr($str, 0, 4) == "<pre"   ||
            substr($str, 0, 5) == "<code"  ||
            substr($str, 0, 4) == "<div"   ||
            substr($str, 0, 2) == "<h"     ||
            substr($str, 0, 3) == "<ol"    ||
            substr($str, 0, 3) == "<ul")
        {
            $str = " ".$str." ";
        }
        else
            $str = " <p>".$str." ";
                    
        $str =& $this->convert_ampersands($str);   
        
        // Convert elipsis to a temporary marker
        $str = preg_replace("/(\w)\.\.\.(\s)/", "\\1$el\\2", $str);     
        
        // Convert 
        $str = preg_replace("/(\S+)\"(\S+?)\"/", "\\1$dq\\2$dq", $str);     
        
        // Replace all newlines with a temporary marker
        
        $str =& preg_replace("/(\015\012)|(\015)|(\012)/",$nl, $str);
        
        // We don't want the auto typography feature to affect the quotes that appear inside tags
        // so we'll replace all single and double quotes within tags with temporary markers.
        
        if (preg_match_all("/\<.+?\>/si", $str, $out))
        {
			for($i=0; $i < count($out['0']); $i++)
			{
				$temp[$i] = preg_replace("/\"/si", $dq, $out['0'][$i]);
				
				$temp[$i] = str_replace("'", $sq, $temp[$i]);
				
				$str =& str_replace($out['0'][$i], $temp[$i], $str);
			}
		}
        
        // We also need to prevent curly quotes from appearing within PHP code examples.
        // Since we turn PHP tags into entities by default, we'll run the above 
        // code again, only looking for PHP tag entities
        
        if (preg_match_all("/&lt;\?.+?\?&gt;/si", $str, $out))
        {
			for($i=0; $i < count($out['0']); $i++)
			{
				$temp[$i] = preg_replace("/\"/si", $dq, $out['0'][$i]);
				
				$temp[$i] = str_replace("'", $sq, $temp[$i]);
				
				$str =& str_replace($out['0'][$i], $temp[$i], $str);
			}
		}        
        
        // Finally we'll replace data between <pre> tags with a temporary marker
        
        $pretags = array();
        
        if (preg_match_all("/\<pre\>.+?\<\/pre\>/si", $str, $out))
        {
			for($i=0; $i < count($out['0']); $i++)
			{
				$pretags[$i] = $out['0'][$i];
				
				$str = preg_replace("/\<pre\>.+?\<\/pre\>/si", $i.$pt, $str, 1);
			}        
        }
           
           
           
        // Define our translation table
            
        $table = array(
        
                        "\"' "                  => "&#8221;&#8217; ",
                        "\"'"                   => "&#8220;&#8216;",
                        
                        " \""                   => " &#8220;",
                        " '"                    => " &#8216;",
                        "'"                     => "&#8217;",
                        
                        $nl."\""                =>  $nl."&#8220;",
                        $nl."&#8217;"           =>  $nl."&#8216;",
                        "&#8216;\""             => "&#8216;&#8220;",
                    
                        "\" "                   => "&#8221; ",
                        "\"$nl"                 => "&#8221;".$nl,
                        "\"."                   => "&#8221;.",
                        "\","                   => "&#8221;,",
                        "\";"                   => "&#8221;;",
                        "\":"                   => "&#8221;:",
                        "\"!"                   => "&#8221;!",
                        "\"?"                   => "&#8221;?",
                        
                        " -- "                  => "&#8212;",
                        $el                     => "&#8230;",
                    
                        ".  "                   => ".&nbsp; ",
                        "?  "                   => "?&nbsp; ",
                        "!  "                   => "!&nbsp; ",
                        ":  "                   => ":&nbsp; ",
                        
                        "$nl$nl<blockquote>"    => "\n</p>\n<blockquote>",
                        "</blockquote>$nl$nl"   => "</blockquote>\n<p>\n",
                        
                        "$nl$nl<ul>"            => "\n</p>\n<ul>",
                        "</ul>$nl$nl"           => "</ul>\n<p>\n",
                        
                        "$nl$nl<ol>"            => "\n</p>\n<ol>",
                        "</ol>$nl$nl"           => "</ol>\n<p>\n",
                        
                        "$nl$nl<code>"          => "\n</p>\n<code>",
                        "</code>$nl$nl"         => "</code>\n<p>\n",
                        
                        "$nl$nl<pre>"           => "\n</p>\n<pre>",
                        "</pre>$nl$nl"          => "</pre>\n<p>\n",
                            
                        "$nl$nl"                => "\n</p>\n<p>\n",
                        
                        "</blockquote>$nl"      => "</blockquote>\n",
                        
                        "$nl<li>"               => "\n<li>",
                        "$nl</ol>"              => "\n</ol>",
                        "$nl</ul>"              => "\n</ul>"
        );
        
        foreach ($table as $key => $val)
        {
            $str =& str_replace($key, $val, $str);
        }
        
        // Replace <pre> tags back in string
        
        $i = 0;
        foreach ($pretags as $key => $val)
        {
        	$str = str_replace($i.$pt, $pretags[$i], $str);
        	$i++;
        }
        
        $str = str_replace($el, "&#8230;", $str);
        
        
        // Convert newlines to <br /> tags except within <pre> tags
        
        $newstr = '';
        $ex = explode("pre>", $str);
        $ct = count($ex);
        
        for ($i = 0; $i < $ct; $i++)
        {
            if (($i % 2) == 0)
            {
                $newstr .= str_replace($nl, "\n<br />\n", $ex[$i]);
            }
            else
            { 
                $newstr .= $ex[$i];
            }
        
            if ($ct -1 != $i) 
            {
                $newstr .= "pre>";
            }
        }
        
        $str =& str_replace($nl, "\n", $newstr);
        
        // Clean up the spaces we added at the beginning
        
        $str =& substr($str,1);
        $str =& substr($str, 0, -1);
        
        if (substr($str, -4) == "</p>")
            $str =& substr($str, 0, -4);  
        
        // Add the closing </p> tag at the end of the entry
        
        if (substr($str, -6) != "quote>" AND
            substr($str, -4) != "pre>"   AND
            substr($str, -5) != "code>"  AND
            substr($str, -4) != "div>"   AND
            substr($str, -3) != "ol>"    AND
            substr($str, -3) != "ul>")
        {
            $str .= "\n</p>";
        }
            
        // Clean up stray paragraph tags.
            
        $newstr = '';
        
        $copy = explode("</p>", $str);
        
        for ($i = 0;  $i < count($copy); $i++)
        {
            if (stristr($copy[$i], "<p>"))
            {
                $newstr .= $copy[$i]."</p>";
            }
            else
                $newstr .= $copy[$i];
        }
        
        $str =& $newstr;
            
        // Add quotes back to tags
            
        $str =& str_replace($dq, "\"", $str);
        $str =& str_replace($sq, "'", $str);
        
        if (substr($str, 0, 3) == "<p>")
        {
            $str = "<p>\n".substr($str, 3);
        }
            
        return $str."\n";
    }
    // END


    //-------------------------------------
    //  Encode Email Address
    //-------------------------------------

    function encode_email($email, $title = '', $anchor = TRUE)
    {
    
        if ($title == "")
            $title = $email;
        
        $bit = array();
        
        if ($anchor == TRUE)
        { 
            $bit[] = '<'; $bit[] = 'a '; $bit[] = 'h'; $bit[] = 'r'; $bit[] = 'e'; $bit[] = 'f'; $bit[] = '='; $bit[] = '\"'; $bit[] = 'm'; $bit[] = 'a'; $bit[] = 'i'; $bit[] = 'l';  $bit[] = 't'; $bit[] = 'o'; $bit[] = ':';
        }
        
        for ($i = 0; $i < strlen($email); $i++)
        {
            $bit[] .= " ".ord(substr($email, $i, 1));
        }
        
        if ($anchor == TRUE)
        {        
            $bit[] = '\"'; $bit[] = '>';
            
            for ($i = 0; $i < strlen($title); $i++)
            {
                $bit[] .= " ".ord(substr($title, $i, 1));
            }
            
            $bit[] = '<'; $bit[] = '/'; $bit[] = 'a'; $bit[] = '>';
       }
        
        $bit = array_reverse($bit);
            
        ob_start();
        
?>
<script type="text/javascript">
//<![CDATA[
var l=new Array();
<?php
    
    $i = 0;
    
    foreach ($bit as $val)
    {
?>l[<?php echo $i++; ?>]='<?php echo $val; ?>';<?php
    }
?>

for (var i = l.length-1; i >= 0; i--){ 
if (l[i].substring(0, 1) == ' ') document.write("&#"+unescape(l[i].substring(1))+";"); 
else document.write(unescape(l[i]));
}
//]]>
</script>
<?php
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
    
        return $buffer;        
    }
    // END


}
// END CLASS
?>