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
 File: core.style.php
-----------------------------------------------------
 Purpose: This class fetches the requested stylesheet.
 It also caches it in case there are multiple stylesheet
 requests on a single page
=====================================================
*/


class Style {

	var $style_cache = array();


    function Style()
    {
        global $DB, $PREFS; 
       
    	$stylesheet = $_GET['css'];
    
        if ( $stylesheet == '' ||
             ! ereg("/", $stylesheet) ||
			   preg_match("#^(http:\/\/|www\.)#i", $stylesheet)
            )
            exit;
                        
		if ( ! isset($style_cache[$stylesheet]))
		{
			$ex =  explode("/", $stylesheet);
			
			if (count($ex) != 2)
				exit;
			
			$sql = "SELECT	exp_templates.template_data 
					FROM   exp_templates, exp_template_groups 
					WHERE  exp_templates.group_id = exp_template_groups.group_id
					AND    exp_templates.template_name = '".$DB->escape_str($ex['1'])."'
					AND    exp_template_groups.group_name = '".$DB->escape_str($ex['0'])."'";
	
			$query = $DB->query($sql);
	
			if ($query->num_rows == 0)
				exit;
				
			$style_cache[$stylesheet] = $query->row['template_data'];
		}

			
		if ($PREFS->ini('send_headers') == 'y')
		{        
			@header("HTTP/1.0 200 OK");
			@header("HTTP/1.1 200 OK");
			@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
			@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
			@header("Cache-Control: no-store, no-cache, must-revalidate");
			@header("Cache-Control: post-check=0, pre-check=0", false);
			@header("Pragma: no-cache");
		}
			@header("Content-type: text/css");

	
		echo $style_cache[$stylesheet];		
        exit;        
    }
    // END
}
// END CLASS
?>