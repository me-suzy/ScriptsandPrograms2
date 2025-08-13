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
 File: pi.auto_xhtml.php
-----------------------------------------------------
 Purpose: Text formatting plugin
=====================================================
*/


$plugin_info = array(
						'pi_name'			=> 'Auto XHTML Typography',
						'pi_version'		=> '1.0',
						'pi_author'			=> 'Rick Ellis',
						'pi_author_url'		=> '',
						'pi_description'	=> 'Replaces quotes, em-dashes and other characters with typographically correct entities.  It also replaces double line breaks with paragraph tags.',
						'pi_usage'			=> Auto_xhtml::usage()
					);



class Auto_xhtml {

    var $return_data;


    // ----------------------------------------
    //  Constructor
    // ----------------------------------------

    function Auto_xhtml()
    {    
        $this->return_data = '';

        $this->process_text();
    }
    // END
    

    // ----------------------------------------
    //  Plugin Usage
    // ----------------------------------------

	// This function describes how the plugin is used.
	//  Make sure and use output buffering
	
    function usage()
    {
    	ob_start(); 
    	?>
		In any template, use this tag pair to wrap items you want formated with XHTMl Typography.
	
		{exp:auto_xhtml}
		
			stuff...
		
		{/exp:auto_xhtml}
	
		<?php
        $buffer = ob_get_contents();
                
        ob_end_clean(); 
        
        return $buffer;
    }
    // END


    // ----------------------------------------
    //  Process Text
    // ----------------------------------------

    function process_text()
    {
        global $TMPL;
    
        $str =& $TMPL->tagdata;     
    
        // Define temporary markers
        $nl = '848Ff5f9a66a5ffb627cdbDce6';
        $dq = '5ffbFR627fTs3ks0097RHGH5w2';
        $sq = '5GEf899adfqekrFR62700WWde4';
        
            
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
            $str = " <p>\n".$str." ";
                    
        // Convert ampersands      
                    
        $str =& preg_replace("/&#(\d+);/", "AMP14TX903DVGHY4QW\\1;", $str);
        $str =& preg_replace("/&(\w+);/",  "AMP14TX903DVGHY4QW\\1;", $str);
        
        $str =& str_replace("&", "&amp;", $str);
        
        $str =& preg_replace("/AMP14TX903DVGHY4QW(\d+);/","&#\\1;", $str);
        $str =& preg_replace("/AMP14TX903DVGHY4QW(\w+);/","&\\1;",  $str);
        
        // Replace all newlines with a temporary marker
        
        $str =& preg_replace("/(\015\012)|(\015)|(\012)/",$nl, $str);
        
        // We don't want the auto typography feature to affect the quotes that appear inside tags
        // so we'll replace all single and double quotes within tags with temporary markers.
        
        preg_match_all("/\<.+?\>/si", $str, $out);
        
        for($i=0; $i < count($out['0']); $i++)
        {
            $temp[$i] = preg_replace("/\"/si", $dq, $out['0'][$i]);
            
            $temp[$i] = str_replace("'", $sq, $temp[$i]);
            
            $str =& str_replace($out['0'][$i], $temp[$i], $str);
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
                        "... "                  => "&#8230; ",
                    
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
            
        $this->return_data =& $str;
    
    }
    // END
      
}
// END CLASS
?>