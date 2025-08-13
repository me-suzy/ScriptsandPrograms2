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
 File: core.input.php
-----------------------------------------------------
 Purpose: This class fetches all input data from
 the super-global arrays (GET, POST, SERVER, COOKIE).
=====================================================
*/


if ( ! defined('EXT'))
{
    exit('Invalid file request');
}


class Input {

    var $AGENT    	= '';       // The current user's browser data
    var $IP       	= '';       // The current user's IP address
    var $SID      	= '';       // Session ID extracted from the URI segments
    var $URI      	= '';       // The full URI query string: /weblog/comments/124/    
    var $QSTR     	= '';       // Only the query segment of the URI: 124
    var $SEGS     	= array();  // The segments of the query string in an array
	var $trim_input	= TRUE;
   
   // These are reserved words that have special meaning when they are the first
   // segment of a URI string.  Template groups can not be named any of these words
      
    var $reserved = array('css', 'trackback');
    
    //-----------------------------------
    // Constructor
    //-----------------------------------

    function Input()
    {
    }
    // END



    //-----------------------------------
    // Fetch incomming GET/POST/IP data
    //-----------------------------------

    function fetch_input_data()
    {
        global $PREFS, $REGX;
        
        
        //-----------------------------------
        // Fetch and pre-process GET data
        //-----------------------------------
                
        if (is_array($_GET) AND count($_GET) > 0)
        {
            foreach($_GET as $key => $val)
            {            
                $_GET[$this->clean_input_keys($key)] = $this->clean_input_data($val);
            }    
        }
        
        //-----------------------------------
        // Fetch and pre-process POST data
        //-----------------------------------
        
        if (is_array($_POST) AND count($_POST) > 0)
        {
            foreach($_POST as $key => $val)
            {                
                if (is_array($val))
                {  
                   // Added this to deal with multi-select lists, as these are sent as a multi-dimensional array

                    foreach($val as $k => $v)
                    {
                        $_POST[$key.'_'.$k] = $this->clean_input_data($v);
                    }
                }
                else
                    $_POST[$this->clean_input_keys($key)] = $this->clean_input_data($val);
            }            
        }

        //-----------------------------------
        // Fetch and pre-process COOKIE data
        //-----------------------------------
        
        if (is_array($_COOKIE) AND count($_COOKIE) > 0)
        {
            foreach($_COOKIE as $key => $val)
            {            
                $_COOKIE[$this->clean_input_keys($key)] = $this->clean_input_data($val);
            }    
        }


        //-----------------------------------
        // Fetch the IP address
        //-----------------------------------
        
        $CIP = (isset($_SERVER['HTTP_CLIENT_IP']) AND $_SERVER['HTTP_CLIENT_IP'] != "") ? $_SERVER['HTTP_CLIENT_IP'] : FALSE;
        $RIP = (isset($_SERVER['REMOTE_ADDR']) AND $_SERVER['REMOTE_ADDR'] != "") ? $_SERVER['REMOTE_ADDR'] : FALSE;
        $FIP = (isset($_SERVER['HTTP_X_FORWARDED_FOR']) AND $_SERVER['HTTP_X_FORWARDED_FOR'] != "") ? $_SERVER['HTTP_X_FORWARDED_FOR'] : FALSE;
                    
		if ($CIP && $RIP)	$this->IP = $CIP;	
		elseif ($RIP)		$this->IP = $RIP;
		elseif ($CIP)		$this->IP = $CIP;
		elseif ($FIP)		$this->IP = $FIP;
		
		if (strstr($this->IP, ','))
		{
			$this->IP = end(explode(',', $this->IP));
		}
		
		if ( ! $REGX->valid_ip($this->IP))
		{
			$this->IP = '0.0.0.0';
		}
		
		unset($CIP);
		unset($RIP);
		unset($FIP);
    }
    // END
    
    

    //--------------------------------------
    //  Parse URI segments
    //--------------------------------------

    function parse_uri($uri = '')
    {
        global $REGX;
    
        if ($uri != '')
        {
            $uri = $REGX->trim_slashes($uri);  
                          
            //--------------------------------------
            //  Does URI contain a session ID?
            //--------------------------------------
            
            // If so, trim it off and rebuild the URI
                            
            if (substr($uri, 0, 2) == 'S=')
            {
                $ex = explode('/', $uri);
            
                $this->SID = substr($ex['0'], 2);
                
                $uri = '';
                
                if (count($ex) > 1)
                {
                    for ($i = 1; $i < count($ex); $i++)
                    {
                        $uri .= $ex[$i].'/';
                    }
                    
                    $uri = substr($uri, 0, -1);
                }
            }
            
            
            if ($uri != '')
            {
                $x = 0;
                
                $ex = explode("/", $uri);
                
                //---------------------------------------
                // Is the first URI segment reserved?
                //---------------------------------------
                
                // Reserved segments are treated as Action requests so we'll
                // assign them as $_GET variables. We do this becuase these
                // reserved words are actually Action requests that don't come to 
                // us as normal GET/POST requests.
                            
                if (in_array($ex['0'], $this->reserved))
                {
                    $_GET['ACT'] = $ex['0'];
                    
                    for ($i = 1; $i < count($ex); $i++)
                    {                        
                        $_GET['ACT_'.$i] = $ex[$i];
                    }
                    
                    $x = 1;
                }

                //---------------------------------------
                // Parse URI segments
                //---------------------------------------
                
                $n = 1;
                
                $uri = '';

                for ($i = $x; $i < count($ex); $i++)
                {
                    $this->SEGS[$n] = $ex[$i];
                    
                    $uri .= $ex[$i].'/';
                    
                    $n++;
                }
                
                $uri = substr($uri, 0, -1);
                
                // Does the URI contain the css request?
                // If so, assign it as a GET variable.
                // This only happens when the "force query string"
                // preference is set.
                
                if (substr($uri, 0, 4) == 'css=')
                {
                    $_GET['css'] = substr($uri, 4);
                }         
                
                // Reassign the full URI
                
                $this->URI = '/'.$uri.'/';
            }            
        }
    }
    // END
    


    //-----------------------------------------
    // Clean global input data
    //-----------------------------------------    

    function clean_input_data($str)
    {
        $str = preg_replace("/(\015\012)|(\015)|(\012)/", "\n", $str);        

        if ($this->trim_input == TRUE)
        {
        	$str = str_replace("\t", '', $str);
        	$str = trim($str);
        }
        
        if ( ! get_magic_quotes_gpc())
        {
            $str = addslashes($str);
        }
        
        return $str;
    }
    // END



    //-------------------------------------
    //  Clean global input keys
    //-------------------------------------    

    function clean_input_keys($str)
    {
        if ( ! get_magic_quotes_gpc())
        {
            $str = addslashes($str);
        }
        
        return $str;
    }
    // END
    
    
    //--------------------------------------------------
    //  Fetch a URI segment
    //--------------------------------------------------

    function fetch_uri_segment($n = '')
    {    
        return ( ! isset($this->SEGS[$n])) ? FALSE : $this->SEGS[$n];
    }
    // END    
    
   
    //--------------------------------------------------
    //  Retrieve Get/Post/Server/Cookie variables
    //--------------------------------------------------

    function GBL($which, $type = 'GET')
    {
        global $PREFS;
    
        $allowed_types = array('GP', 'GET', 'POST', 'SERVER', 'COOKIE');
        
        if ( ! in_array($type, $allowed_types))
            return false;            
         
        switch($type)
        {
            case 'GP'    : 
                            if ( ! isset($_POST[$which]) )
                            {
                                if ( ! isset($_GET[$which]) )
                                {
                                    return FALSE;                                
                                }
                                else
                                    return $_GET[$which];
                            }
                            else
                                return $_POST[$which];
                break;
            case 'GET'    : return ( ! isset($_GET[$which]) )    ? FALSE : $_GET[$which];    
                break;
            case 'POST'   : return ( ! isset($_POST[$which]) )   ? FALSE : $_POST[$which];
                break;
            case 'SERVER' : return ( ! isset($_SERVER[$which]) ) ? FALSE : $_SERVER[$which];         
                break;    
            case 'COOKIE' : 
                    
                    $prefix = ( ! $PREFS->ini('cookie_prefix')) ? 'exp_' : $PREFS->ini('cookie_prefix').'_';
                    
                    return ( ! isset($_COOKIE[$prefix.$which]) ) ? FALSE : stripslashes($_COOKIE[$prefix.$which]);
                    
                break;    
        }
    }
    // END        
}
// END CLASS
?>