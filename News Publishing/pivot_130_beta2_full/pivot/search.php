<?php

// ---------------------------------------------------------------------------
//
// PIVOT - LICENSE:
//
// This file is part of Pivot. Pivot and all its parts are licensed under
// the GPL version 2. see: http://www.pivotlog.net/help/help_about_gpl.php
// for more information.
//
// ---------------------------------------------------------------------------

// First line defense.
if (file_exists(dirname(__FILE__)."/first_defense.php")) {
	include_once(dirname(__FILE__)."/first_defense.php");
	block_refererspam();
	block_postedspam();
}

define('LIVEPAGE', TRUE);

include_once("pv_core.php");
include_once("modules/module_search.php");

$starttime = getmicrotime();

// 2004/11/01 =*=*= JM - log searches
// 2004/11/24 =*=*= JM - corrections thanks to jim
// log_search();

function log_search() {
    global $Pivot_Vars,$Paths;

    // is there anything to save?
    if( '' != $Pivot_Vars['search'] ) {
        // set path
        $log_path =  $Paths['pivot_path'].'logs/' ;

        // is there an old to load?
        if( file_exists( $log_path.'log_search.php' )) {
            // file exists - load if writable
            if( is_writable( $log_path.'log_search.php' )) {
                $log_search_array = load_serialize( $log_path.'log_search.php' );
                $log_exists = TRUE;
            }
        }
        // just in case
        if( !is_array( $log_search_array )) { $log_search_array = array(); }
        // add to the log
        $log_search_array[$Pivot_Vars['search']]++;

        if(( isset( $log_exists ))&&( TRUE==$log_exists )) {
            // easy route - now serialize and save
            save_serialize( 'logs/log_search.php',$log_search_array );
        } else {
            // else attempt to make it - suppress errors
            @makedir( $log_path,0700 );
            @touch( $log_path.'log_search.php' );
            @chmod( $log_path.'log_search.php',0777 );
            // final check
            if( is_writable( $log_path.'log_search.php' )) {
                save_serialize( 'logs/log_search.php',$log_search_array );
            }
        }
    }
}
// END


$db = new db;

$result = "<h2>". lang('weblog_text', 'search_title'). "</h2>\n\n" ;
$result .= search_result();
$result .= "<!-- Search took ".timetaken() . " seconds -->";

unset($db->entry);

// Select the first weblog, and get the template for the archive page..
reset ($Weblogs);
$Current_weblog = (key($Weblogs));

// the search template for the current weblog
if (isset($Weblogs[$Current_weblog]['extra_template']) && ($Weblogs[$Current_weblog]['extra_template']!="") ) {
	$template_html = load_template($Weblogs[$Current_weblog]['extra_template']);
} else {
	$template_html = load_template($Weblogs[$Current_weblog]['archive_template']);
}


// match and replace the [[weblog]] tags for the search output
if (preg_match_all('/\[\[(sub)?weblog:(.*)?(:[0-9]*)?\]\]/siU', $template_html, $match)) {

	if (count($match[1])==1) {
		$template_html = str_replace($match[0][0], $result, $template_html);
	} else {
		$template_html = preg_replace("/\[\[(sub)?weblog:standard(:[0-9]*)?\]\]/siU", $result, $template_html);

		foreach ($match[0] as $name) {
			$template_html = str_replace($name, "", $template_html);
		}
	}

}



if (!($template_html)) {
	ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
} else {
	$output=$template_html;
	$output=parse_step4($output);
}

echo ($output);
flush();


?>
