<?php



/**
 * Dynamic wrapper for main_show_bbclone_hits()
 *
 * @return string
 */
function snippet_show_bbclone_hits() {
	global $Paths;

	if (defined('LIVEPAGE')){

		// for pages like 'live entries' and 'dynamic archives'..
		return main_show_bbclone_hits();

	} else{

		// For genreated pages like the frontpage and archives.
		$file = __FILE__;

		$output = "<"."?php\n";
		$output .= "	include_once('$file');\n";
		$output .= "	echo main_show_bbclone_hits();\n";
		$output .= "?".">\n";

	}

	// return $output to the parser..
	return $output;
}

/**
 * This snippet shows a global overview of your bbclone stats.
 *
 * @return string
 */
function main_show_bbclone_hits() {
	global $Paths;

	// Include the language strings:
	if (function_exists('LoadLabels')) {
		LoadLabels( $Paths['extensions_path'] . "bbclone_tools/bbclone_lang.php" );
	}

	include(dirname(dirname(dirname(__FILE__)))."/bbclone/var/access.php");

	$totalvisits   = $access["stat"]["totalvisits"];
	$totalcount    = $access["stat"]["totalcount"];
	$visitorsmonth = $access["time"]["month"][date("n")-1];
	$visitorstoday = $access["time"]["wday"][date("w")];
	$wday          = $access["time"]["wday"];

	for($week = 0; list(,$wdays) = each($wday); $week += $wdays);

	$output = sprintf("<p class=\"bbclone-stats\">\n%s: <strong>%s</strong><br />\n", lang('bbclone','total_visits'), $totalvisits);
	$output .= sprintf("%s: <strong>%s</strong><br />\n", lang('bbclone','unique_visits'), $totalcount);
	$output .= sprintf("%s: <strong>%s</strong><br />\n", lang('bbclone','this_month'), $visitorsmonth);
	$output .= sprintf("%s: <strong>%s</strong><br />\n", lang('bbclone','this_week'), $week);
	$output .= sprintf("%s: <strong>%s</strong>\n</p>\n", lang('bbclone','today'), $visitorstoday);

	unset($access);

	return $output;


}


?>