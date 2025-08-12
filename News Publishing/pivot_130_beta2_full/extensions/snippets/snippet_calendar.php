<?php

/**
 * Pcalendar is the standard calendar that prints the current or a previous
 * month, and shows the entries that were published during this period.
 *
 * Depending on the parameter that is used in the templates, it includes 
 * the link to the css file, writes the calendar HTML or both. Ideally you 
 * should use [[pcalendar:style]] somewhere in the <head> section of your
 * template, and [[pcalendar]] in the <body>, where you would like it to
 * be. 
 *
 * [[calendar:style]]  includes a link to the css file
 * [[calendar:no_css]] writes the calendar, and no css
 * [[calendar]]        write the css, if it wasn't written yet, and 
 *                     then write the calendar.
 *
 * @author Pivot Dev-team 
 * @version 1.0 
 *
 */



/**
 * This is the function that is called from within the parser. 
 *
 * @param string $function
 * @return string HTML
 */
function snippet_calendar($function) {
	global $calendar_style;
		
	switch($function){

		case "style":
			$calendar_style = true;	
			$output = calendar_style();
			break;
			
		case "no_css":
			$output = calendar_main();
			break;
			
		default: 
			if (!$calendar_style) {
				$output = calendar_style();
			} else {
				$output = "";
			}
			$output = calendar_main();
			
	}
	
	return $output;
	
}




/**
 * Include a bit of code, that pulls in the pcalendar.php script.
 * either at runtime, or as a bit of code for generated pages.
 * JM - 2004/10/03, Bob - 2005/01/20
 *
 * @return string HTML
 */ 

function calendar_main() {
	global $Paths, $Current_weblog, $set_output_paths;

	if (defined('LIVEPAGE')) {

		// If it's a Live entry or live archive page: include it.
		
		$old_current_weblog = $Current_weblog;

		// Buffer the output..
		ob_start();
		include_once($Paths['extensions_path']. "calendar/calendar.php");
		$output = ob_get_contents();
		ob_end_clean();

		$Current_weblog = $old_current_weblog;

	} else {

		// For generated pages, we output a bit of code that will
		// include the pcalendar.php when the page is viewed.
		
		$output .= '<'.'?php '."\n";
		if(!isset($set_output_paths) || ($set_output_paths==FALSE)) {
			$set_output_paths=TRUE;
			$output .= sprintf("DEFINE('INWEBLOG', TRUE);\n ");
			$output .=sprintf("\$log_url='%s';\n ", $Paths['log_url'] );
			$output .=sprintf("\$pivot_url='%s';\n ", $Paths['pivot_url'] );
			$output .= sprintf("\$pivot_path='%s';\n ", $Paths['pivot_path']);
			$output .= sprintf("\$weblog='%s';\n ", $Current_weblog);
		}
		$output .= 'include_once( "'.$Paths['extensions_path'].'calendar/calendar.php" );';
		$output .= "\n ?".'>';
	}

	return $output;

}





/**
 * Include the Calender Styles
 * JM - 2004/10/03, Bob - 2005/01/20
 *
 * @return string HTML
 */ 
function calendar_style() {
	global $Paths;

	$output  = '<link rel="stylesheet" type="text/css" href="'.$Paths['extensions_url'].'calendar/calendar.css" />'."\n" ;	

	return $output;
}



?>