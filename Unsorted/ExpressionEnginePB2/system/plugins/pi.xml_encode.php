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
 File: pi.text.php
-----------------------------------------------------
 Purpose: This class contains a number of useful text
 manipulation functions
=====================================================




*/



$plugin_info = array(
						'pi_name'			=> 'XML Encode',
						'pi_version'		=> '1.0',
						'pi_author'			=> 'Rick Ellis',
						'pi_author_url'		=> '',
						'pi_description'	=> 'XML Encoding plugin.',
						'pi_usage'			=> Xml_encode::usage()
					);



class Xml_encode {

    var $return_data;
    
    
    // ----------------------------------------
    //  XML Encoding function
    // ----------------------------------------

    function Xml_encode()
    {
        global $TMPL;
        
        $this->return_data = '';
        
        $str =& $TMPL->tagdata;
                
        $str =& strip_tags($str); 
            
        $ents = array(
                        "&"   =>    "&amp;",
                        "<"   =>    "&lt;",
                        ">"   =>    "&gt;",
                        "\""  =>    "&quot;",
                        "'"   =>    "&apos;"
                    );
    
        foreach ($ents as $k => $v)
        {
            $str =& preg_replace("/".$k."/", $v, $str);
        }
                       
	$this->return_data = trim($str);
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
This plugin converts reserved XML characters to entities.  It is used in the RSS templates.

To use this plugin, wrap anything you want to be processed by it between these tag pairs:

{exp:xml_encode}

text you want processed

{/exp:xml_encode}
<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
// END
}
// END CLASS
?>