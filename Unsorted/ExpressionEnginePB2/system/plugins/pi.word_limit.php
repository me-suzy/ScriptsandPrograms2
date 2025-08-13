<?php

/*
=====================================================
 ExpressionEngine - by pMachine
-----------------------------------------------------
 Nullified by GTT
-----------------------------------------------------
 Copyright (c) 2003 pMachine, Inc.
=====================================================
 THIS IS COPYRIGHTED SOFTWARE
 PLEASE READ THE LICENSE AGREEMENT
=====================================================
 File: pi.word_limit.php
-----------------------------------------------------
 Purpose: Word limiting plugin
=====================================================

*/


$plugin_info = array(
						'pi_name'			=> 'Word Limiter',
						'pi_version'		=> '1.0',
						'pi_author'			=> 'Rick Ellis',
						'pi_author_url'		=> '',
						'pi_description'	=> 'Permits you to limit the number of words in some text',
						'pi_usage'			=> Word_limit::usage()
					);


class Word_limit {

    var $return_data;

    
    // ----------------------------------------
    //  Word Limiter
    // ----------------------------------------

    function Word_limit()
    {
        global $TMPL, $FNS;
                        
		$total = ( ! $TMPL->fetch_param('total')) ? '500' :  $TMPL->fetch_param('total');
		
		if ( ! is_numeric($total))
			$total = 500;
                
 		$this->return_data = $FNS->word_limiter($TMPL->tagdata, $total);
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
Wrap anything you want to be processed between the tag pairs.

{exp:word_limit total="100"}

text you want processed

{/exp:word_limit}

Note:  The "total" parameter lets you specify the number of words.

<?php
$buffer = ob_get_contents();
	
ob_end_clean(); 

return $buffer;
}
// END


}
// END CLASS
?>