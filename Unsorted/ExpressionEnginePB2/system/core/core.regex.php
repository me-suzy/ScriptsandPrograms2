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
 File: core.regex.php
-----------------------------------------------------
 Purpose: Regular expression library.
=====================================================
*/

if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Regex {


    //-------------------------------------
    //  Validate Email Address
    //-------------------------------------

    function valid_email($address)
    {
        if ( ! eregi("^([-a-zA-Z0-9_\.\+])+@([-a-zA-Z0-9_\.\+]+\.)+[a-z]{2,6}$", $address))
        {
			if ( ! preg_match("/^[^@\s]+@([-_\.a-z0-9]+\.)+[a-z]{2,6}$/ix", $address))
			{
				return false;
			}
        }
            return true;
    }
    // END
    


    //-------------------------------------
    //  Validate IP Address
    //-------------------------------------

    function valid_ip($ip)
    {
		if ( ! preg_match( "/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}/", $ip))
		{
			return false;
		}

		return true;
    }
    // END


    //-------------------------------------
    //  Prep URL
    //-------------------------------------

    function prep_url($str = '')
    {
    	if ($str == 'http://')
    	{
    		return '';
    	}
    
    	if ($str != '')
    	{
			if ( ! eregi("^http://", $str))
			{
				$str = 'http://'.$str;
			}
		}
		
		return $str;
    }
    // END


    //-------------------------------------
    //  Decode query string entities
    //-------------------------------------    

    function decode_qstr($str)
    {
        $str = str_replace('&#46;', '.', $str);
        $str = str_replace('&#63;', '?', $str);
        $str = str_replace('&amp;', '&', $str);
        
        return $str;
    }
    // END


    //--------------------------------------------
    // Format HTML so it appears correct in forms
    //--------------------------------------------    

    function form_prep($str = '', $strip = 0)
    {
        if ($str == '')
        {
            return '';
        }
    
        if ($strip != 0)
        {
            $str = stripslashes($str);
        }
    
        $str = htmlspecialchars($str);    
        $str = str_replace("'", "&#39;", $str);
        
        return $str;
    }
    // END


    //-----------------------------------------
    // Convert PHP tags to entities
    //-----------------------------------------    

    function encode_php_tags($str)
    {        
		$str = preg_replace("/<\?php/i", 	'&lt;?php',	$str);
		$str = preg_replace("/<\?/i", 		'&lt;?', 	$str);
		$str = preg_replace("/\?>/i", 		'?&gt;', 	$str);
		
		// <?php  Fixes BBEdit syntax coloring bug
		
		return $str;
	}
	// END


    //-------------------------------------
    //  Convert EE Tags
    //-------------------------------------

	function encode_ee_tags($str)
	{
		if ($str != '')
		{
			$str = preg_replace("/\{exp:(.+?)\}/", "&#123;exp:\\1&#125;", $str);
			$str = preg_replace("/\{\/exp:(.+?)\}/", "&#123;/exp:\\1&#125;", $str);
		}
		
		return $str;
	}
	// END


    //----------------------------------------------
    //  Convert single and double quotes to entites
    //----------------------------------------------

    function convert_quotes($str)
    {    
        $ents = array(  
                        "\'"    =>    "&#39;",
                        "\""    =>    "&quot;",
                      );
    
        foreach ($ents as $k => $v)
        {
            $str = str_replace($k, $v, $str);
        }

        return $str;
    }
    // END



    //-------------------------------------
    // Convert reserved XML characters
    //-------------------------------------    

    function xml_convert($str)
    {
        $temp = '848ff8if9a6fb627faccdbcce6';
        
        $str = preg_replace("/&#(\d+);/", "$temp\\1;", $str);
        $str = preg_replace("/&(\w+);/",  "$temp\\1;", $str);
        
        $ents = array(
                        "&"   =>    "&amp;",
                        "<"   =>    "&lt;",
                        ">"   =>    "&gt;",
                        "\""  =>    "&quot;",
                        "'"   =>    "&apos;",
                        "-"   =>    "&#45;"
                    );
    
        foreach ($ents as $k => $v)
        {
            $str = preg_replace("/".$k."/", $v, $str);
        }
            
        $str = preg_replace("/$temp(\d+);/","&#\\1;",$str);
        $str = preg_replace("/$temp(\w+);/","&\\1;", $str);
            
        return stripslashes($str);
    }    
    // END



    //-------------------------------------------------
    // Convert character entities back to ASCII
    //-------------------------------------------------

    function entities_to_ascii($str)
    {
        $ents = array(
                        '&amp;'  =>   '&',
                        '&lt;'   =>   '<',
                        '&gt;'   =>   '>',
                        '&apos;' =>   "'",
                        '&quot;' =>   '"',
                        '&#45;'  =>   '-'
                    );
    
    
        foreach ($ents as $k => $v)
        {
            $str = preg_replace("/".$k."/", $v, $str);
        }
        
        $trans = get_html_translation_table(HTML_ENTITIES);
        $trans = array_flip($trans);
        
        $ret = strtr($str, $trans);

        return preg_replace('/\&#(\d+)\;/me',"chr('\\1')",$ret);
    }
    // END
    

    //-------------------------------------------------
    //  Trim slashes "/" from front and back of string
    //-------------------------------------------------

    function trim_slashes($str)
    {
        if (ereg("^/", $str))
        {
            $str = substr($str, 1);
        }
        
        if (ereg("^&#47;", $str))
        {
            $str = substr($str, 5);
        }
       
        if (ereg("/$", $str))
        {
            $str = substr($str, 0, -1);
        }
        
        if (ereg("&#47;$", $str))
        {
            $str = substr($str, 0, -5);
        }

        return $str;
    }
    // END


    //-------------------------------------------------
    //  Removes double commas from string
    //-------------------------------------------------

    function remove_extra_commas($str)
    {
		$str = str_replace(",,", ",", $str);
    
        if (ereg("^,", $str))
        {
            $str = substr($str, 1);
        }
       
        if (ereg(",$", $str))
        {
            $str = substr($str, 0, -1);
        }
        
        return $str;
    }
    // END
    
    
    //-------------------------------------------------
    //  Strip quotes
    //-------------------------------------------------

    function strip_quotes($str)
    {
    	$str = str_replace("\"", "", $str);
    	$str = str_replace("'",  "", $str);
    	
    	return $str;
    }
    // END


    
    //-------------------------------------------------
    //  XSS hacking stuff
    //-------------------------------------------------

	function xss_clean($str)
	{
    	// These are naughty things that we don't want users to submit

		$str = preg_replace("/<.*?script(.*?)\>/i",		"&lt;script\\1&gt;",	$str);
		$str = preg_replace("/<.*?object(.*?)\>/i",		"&lt;object\\1&gt;",	$str);
		$str = preg_replace("/<.*?applet(.*?)\>/i",		"&lt;applet\\1&gt;",	$str);
		$str = preg_replace("/<.*?embed(.*?)\>/i",		"&lt;embed\\1&gt;",		$str);
		$str = preg_replace("/<.*?plaintext(.*?)\>/i",	"&lt;plaintext\\1&gt;",	$str);
		$str = preg_replace("/<.*?iframe(.*?)\>/i",		"&lt;iframe\\1&gt;",	$str);
		$str = preg_replace("/<.*?style(.*?)\>/i",		"&lt;style\\1&gt;",		$str);
		$str = preg_replace("/<.*?form(.*?)\>/i",		"&lt;form\\1&gt;",		$str);
        
        $bad = array(
						"<!--"			=>	"&lt;!--",
						"-->"			=>	"--&gt;",
						"plaintext>"	=>	"plaintext&gt;",
						"script>"		=>	"script&gt;",
						"%3c"			=>	"&lt;",		// <
						"%253c"			=>	"&lt;",		// <
						"%0e"			=>	"&gt;",		// >
						"%28"			=>	"&#40;",	// (  
						"%29"			=>	"&#41;",	// ) 
						"%2528"			=>	"&#40;"		// (
        			);
        			
        foreach ($bad as $key => $val)
        {
			$str = str_replace($key, $val, $str);   
        }

		return $str;
     }           
	// END



    //-------------------------------------------------
    //  Create URL Title
    //-------------------------------------------------

	function create_url_title($str)
	{
		global $PREFS;
		
		$str = strip_tags($str);
		
		// Use dash as separator		

		if ($PREFS->ini('word_separator') == 'dash')
		{
			$trans = array(
							"\&\#\d+?\;"                        => '',
							"\&\S+?\;"                          => '',
							"['\"\?\.\!*\$\#@%;:,\_=\(\)\[\]]"  => '',
							"\s+"                               => '-',
							"\/"                                => '-',
							"-+"                                => '-',
							"\&"                                => '',
							"-$"                                => ''
						   );
		}
		else // Use underscore as separator
		{
			$trans = array(
							"\&\#\d+?\;"                        => '',
							"\&\S+?\;"                          => '',
							"['\"\?\.\!*\$\#@%;:,\-=\(\)\[\]]"  => '',
							"\s+"                               => '_',
							"\/"                                => '_',
							"_+"                                => '_',
							"\&"                                => '',
							"_$"                                => ''
						   );
		}
					   
		foreach ($trans as $key => $val)
		{
			$str = preg_replace("#".$key."#", $val, $str);
		}             

		$str = trim(stripslashes(strtolower($str)));

		return $str;
	}
	// END

}
// END CLASS
?>