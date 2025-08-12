<?php


// textual markers for search engines
$GLOBALS['engines']['google'] = "[Go] ";
$GLOBALS['engines']['alltheweb'] = "[Al] ";
$GLOBALS['engines']['vivisimo'] = "[Viv] ";
$GLOBALS['engines']['vinden'] = "[Vin] ";
$GLOBALS['engines']['altavista'] = "[Av] ";
$GLOBALS['engines']['aol'] = "[Ao] ";
$GLOBALS['engines']['lycos'] = "[Ly] ";
$GLOBALS['engines']['msn'] = "[Ms] ";
$GLOBALS['engines']['mysearch'] = "[My] ";
$GLOBALS['engines']['yahoo'] = "[Y] ";




/**
 * Dynamic wrapper for main_show_bbclone_referers()
 *
 * @return string
 */
function snippet_show_bbclone_referers() {
	global $Paths;

	if (defined('LIVEPAGE')){

		// for pages like 'live entries' and 'dynamic archives'..
		return main_show_bbclone_referers();

	} else{

		// For genreated pages like the frontpage and archives.
		$file = __FILE__;

		$output = "<"."?php\n";
		$output .= "	include_once('$file');\n";
		$output .= "	echo main_show_bbclone_referers();\n";
		$output .= "?".">\n";

	}

	// return $output to the parser..
	return $output;
}

/**
 * This snippet allows for commentform texarea's to be resizeable on the fly
 *
 * @return string
 */
function main_show_bbclone_referers() {
	global $Paths, $engines;

	// Include the language strings:
	if (function_exists('LoadLabels')) {
		LoadLabels( $Paths['extensions_path'] . "snippets/bbclone_lang.php" );
	}

	include(dirname(dirname(dirname(__FILE__)))."/bbclone/var/last.php");

	$output = "<p class=\"bbclone-referers\">\n";

	$last['traffic'] = array_reverse($last['traffic']);

	foreach($last['traffic'] as $line) {

		// skip 'unknown'..
		if ($line['referer']=="unknown") { continue; }

		// Get the search engine, if any was used
		if ( ($line['search']!="") && ($line['search']!="-") ){

			$line['searchengine'] = "[S] ";

			foreach($engines as $engine => $name) {
				if (strpos($line['referer'],$engine.".")>0) { $line['searchengine'] = $name;}
			}

			$title = $line['searchengine']." ".$line['search'];

		} else {
			$title = referer_disptitle($line['referer']);
		}

		$output .= sprintf("%s ", date("H:i",$line['time']));
		$output .= sprintf("<a href=\"%s\">%s</a>", $line['referer'], trimtext($title,20));
		$output .= "<br />\n";

		$count++;

		if ($count>15) { break; }

	}

	$output .= "<p>\n";

	unset($last);

	return $output;


}



/**
 * Formats an url for display. Index files, http://
 * prefixes and 'www.' are ignored.
 *
 * @param string $text
 * @return string
 */
function referer_disptitle($text) {
	global $titles;

	if (strpos($text, "?")) { $text=substr($text,0, strpos($text, "?") ); }
	$text=str_replace("/index.php", "", $text);
	$text=str_replace("/index.html", "", $text);
	$text=str_replace("/index.htm", "", $text);
	$text=str_replace("/index.shtml", "", $text);
	if ((strlen($text) - strrpos($text, "/")) == 1 ) { $text=substr($text,0,(strlen($text)-1)); }

	$text = str_replace('http://', '', $text);
	$text = str_replace('www.', '', $text);

	$text = stripslashes(htmlspecialchars($text));
	return $text;

}

?>