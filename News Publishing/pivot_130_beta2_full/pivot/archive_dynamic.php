<?php

// First line defense.
if (file_exists(dirname(__FILE__)."/first_defense.php")) {
	include_once(dirname(__FILE__)."/first_defense.php");
	block_refererspam();
}

// this file will enable non-crufty urls for archives and entries
$maxlength = 35;


include_once("pv_core.php");

function make_archive_list($ye, $mo, $da, $title) {
	global $HTTP_SERVER_VARS, $Weblogs, $Current_weblog, $db, $pivot_url, $pivot_path, $entry, $temp_entry, $pivotdir, $maxlength, $filtercat, $Paths;

	reset ($Weblogs);
	$Current_weblog = (key($Weblogs));

	$db = new db();
	// added by sam
	foreach ($Weblogs[$Current_weblog]['sub_weblog'] as $subweblog) {
		foreach ($subweblog['categories'] as $cat) {
			$current_cats[] = $cat;
		}
	}


	if ( ($da>0) && ($da<32) && ($mo>0) && ($mo<13) ) {
		// get a list of the entries for the current day
		list($start_date, $stop_date) = getdaterange(sprintf("%02d-%02d-%02d-00-00",$ye,$mo, $da), 'day' );
	} else if ( ($mo>0) && ($mo<13) )  {
		// get a list of the entries for the current month
		list($start_date, $stop_date) = getdaterange(sprintf("%02d-%02d-01-00-00",$ye,$mo), 'month' );
	} else  {
		// get a list of the entries for the current year
		list($start_date, $stop_date) = getdaterange(sprintf("%02d-%02d-01-00-00",$ye,$mo), 'year' );
	}


	$list_entries = $db->getlist_range($start_date, $stop_date,"","", FALSE);


	if ($title != "") {

		$title = str_replace("http://", "", $title);
		$title = str_replace("http", "", $title);

		// we try to see if we can match a given name to a title..
		foreach ($list_entries as $entry) {
			$entry['title'] = str_replace("http://", "", $entry['title']);
			//echo "is: ".safe_string($title, TRUE) ." ?= ". safe_string($entry['title'], TRUE) ."<br />\n";
			if ( safe_string($title, TRUE) == safe_string($entry['title'], TRUE) ) {
				$HTTP_POST_VARS['id'] = $entry['code'];
				$_POST['id'] = $entry['code'];
				include ('entry.php');
				die();
			}
		}

		// next, we see if the start is the same..
		foreach ($list_entries as $entry) {
			$entry['title'] = str_replace("http://", "", $entry['title']);
			if (substr($title,0,19) == substr(safe_string($entry['title'], TRUE),0,19)) {
				$HTTP_POST_VARS['id'] = $entry['code'];
				$_POST['id'] = $entry['code'];
				include ('entry.php');
				die();
			}
		}

		// next, we see if the start is the same..
		foreach ($list_entries as $entry) {
			$entry['title'] = str_replace("http://", "", $entry['title']);
			if (substr($title,0,7) == substr(safe_string($entry['title'], TRUE),0,7)) {
				$HTTP_POST_VARS['id'] = $entry['code'];
				$_POST['id'] = $entry['code'];
				include ('entry.php');
				die();
			}
		}
	}


	// We continue with the selected entries, and display the bunch

	$arc_list = "";
	foreach ($list_entries as $list_entry) {
		// added by sam
		$valid_entry_cats = array_intersect($list_entry['category'], $current_cats);
		if ( $valid_entry_cats &&  (!isset($filtercat) || (@in_array($filtercat, $list_entry['category'] )) )) {
			// if we don't filter, or we filter on the entry's category..
			if ($list_entry['status'] == 'publish') {
				//if the entry is published
				$diffdate = format_date( $list_entry['date'], $Weblogs[$Current_weblog]['diffdate_format']);
				$date = format_date( $list_entry['date'], $Weblogs[$Current_weblog]['entrydate_format']);
				$temp_entry = $list_entry;
				$link = make_filelink($list_entry['code'], "", "");
				if ($diffdate != $lastdiffdate) {
					$arc_list.= "<br />".$diffdate."<br />\n";
					$lastdiffdate = $diffdate;
				}
				$arc_list.= sprintf("%s <a href='%s' title=\"(%s, %s comments)\">%s</a><br />\n",
				$date, $link, implode(", ",$valid_entry_cats),$list_entry['commcount'], trimtext($list_entry['title'],$maxlength) );
			}
		}
	}

	unset($temp_entry);

	// the search template for the current weblog
	if (isset($Weblogs[$Current_weblog]['extra_template']) && ($Weblogs[$Current_weblog]['extra_template']!="") ) {
		$template_html = load_template($Weblogs[$Current_weblog]['extra_template']);
	} else {
		$template_html = load_template($Weblogs[$Current_weblog]['archive_template']);
	}


	// match and replace the [[weblog]] tags for the archive output
	if (preg_match_all('/\[\[(sub)?weblog:(.*)?(:[0-9]*)?\]\]/siU', $template_html, $match)) {

		if (count($match[1])==1) {
			$template_html = str_replace($match[0][0], $arc_list, $template_html);
		} else {
			$template_html = preg_replace("/\[\[(sub)?weblog:standard(:[0-9]*)?\]\]/siU", $arc_list, $template_html);

			foreach ($match[0] as $name) {
				$template_html = str_replace($name, "", $template_html);
			}
		}

	}

	$filename = $Weblogs[$Current_weblog]['archive_path'] . make_archive_name();

	if (!($template_html)) {
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {
		$output=$template_html;
		$output=parse_step4($output);
	}

	echo ($output);
	flush();


}






function make_default() {
	global $HTTP_SERVER_VARS, $Weblogs, $Current_weblog, $db, $pivot_url, $pivot_path, $entry, $temp_entry, $pivotdir;

	reset ($Weblogs);
	$Current_weblog = (key($Weblogs));

	$db = new db();

	$arc_list = "";

	if (file_exists($pivot_dir."db/ser-archive_overview_cat.php") && file_exists($pivot_dir."db/ser-archive_overview_cat.php")) {

		$arc_array_cat = load_serialize($pivot_dir."db/ser-archive_overview_cat.php", TRUE);
		$arc_array_mon = load_serialize($pivot_dir."db/ser-archive_overview_mon.php", TRUE);

		// if over three three days old.
		if ((mktime() - filemtime($pivot_dir."db/ser-archive_overview_cat.php")) >  259200) {
			unlink ($pivot_dir."db/ser-archive_overview_cat.php");
			unlink ($pivot_dir."db/ser-archive_overview_mon.php");
		}

	} else {

		$list_entries = $db->getlist_range("1970-01-01-00-00", "2020-12-31-23-59","","", FALSE);

		// iterate through all of the entries, building the arrays for both the
		// per-month and per-category lists..
		foreach ($list_entries as $list_entry) {
			$date = format_date( $list_entry['date'], $Weblogs[$Current_weblog]['fulldate_format']);
			$temp_entry = $list_entry;
			$link = make_filelink($list_entry['code']);
			list($ye, $mo) = explode("-", $list_entry['date']);
			if (isset($list_entry['category'])) {
				foreach ($list_entry['category'] as $cat) {
					$arc_array_cat[$cat][$ye] = 1;
				}
			}
			$arc_array_mon[$ye][$mo] = 1;
		}

		save_serialize($pivot_dir."db/ser-archive_overview_cat.php", $arc_array_cat, FALSE);
		save_serialize($pivot_dir."db/ser-archive_overview_mon.php", $arc_array_mon, FALSE);
	}

	foreach ($Weblogs[$Current_weblog]['sub_weblog'] as $subweblog) {
		foreach ($subweblog['categories'] as $cat) {
			$current_cats[] = $cat;
		}
	}

	// build the per-month list
	foreach ($arc_array_mon as $ye => $months) {
		$arc_list .= "<p><b>$ye:</b><br />\n";

		ksort ($months);

		$temp_arr = array();
		foreach ($months as $mo => $dummy) {
			$temp_arr[] = sprintf("<a href=\"%s/%s/\">%s</a>\n", $ye, $mo, lang('months',-1+$mo));
		}

		$arc_list .= implode(", ", $temp_arr). "<br /></p>\n";

	}



	// build the per-category list
	ksort($arc_array_cat);
	if (count($arc_array_cat) > 1) {

		foreach ($arc_array_cat as $cat => $year) {
			if (in_array($cat, $current_cats)) {
				$arc_list .= "<p><b>$cat:</b>\n";

				ksort ($year);

				$temp_arr = array();
				foreach ($year as $ye => $dummy) {
					$temp_arr[] = sprintf("<a href=\"%s/%s/\">%s</a>\n", $cat, $ye, $ye);
				}
				$arc_list .= implode(", ", $temp_arr). "</p>\n";
			}
		}
	}



	// the search template for the current weblog
	if (isset($Weblogs[$Current_weblog]['extra_template']) && ($Weblogs[$Current_weblog]['extra_template']!="") ) {
		$template_html = load_template($Weblogs[$Current_weblog]['extra_template']);
	} else {
		$template_html = load_template($Weblogs[$Current_weblog]['archive_template']);
	}

	if (preg_match_all('/\[\[(sub)?weblog:(.*)?\]\]/siU', $template_html, $match)) {


		if (count($match[1])==1) {
			$template_html = str_replace("[[weblog:".$match[1][0]."]]", $arc_list, $template_html);
			$template_html = str_replace("[[subweblog:".$match[1][0]."]]", $arc_list, $template_html);
			$template_html = str_replace("[[weblog:".$match[2][0]."]]", $arc_list, $template_html);
			$template_html = str_replace("[[subweblog:".$match[2][0]."]]", $arc_list, $template_html);
		} else {
			$template_html = str_replace("[[weblog:standard]]", $arc_list, $template_html);
			$template_html = str_replace("[[subweblog:standard]]", $arc_list, $template_html);
			foreach ($match[1] as $name) {
				$template_html = str_replace("[[weblog:".$name."]]", "", $template_html);
				$template_html = str_replace("[[subweblog:".$name."]]", "", $template_html);
			}
		}
	}



	$filename = $Weblogs[$Current_weblog]['archive_path'] . make_archive_name();

	if (!($template_html)) {
		ErrorOut("Could not load template file: <i>$template</i> [does not exist]");
	} else {
		$output=$template_html;
		$output=parse_step4($output);
	}

	echo ($output);
	flush();

}


// --------
// 'main'..
// --------

//split the input.
if (strpos($_GET['p'],"-")==4) {
	list($ye, $mo, $da) = explode("-", $_GET['p']);
	list($da, $name) = explode("/", $da);
} else {
	list($ye, $mo, $da, $name, $extra) = explode("/", $_GET['p']);
}



// check to see if we want to filter on category.
if (in_array($ye, explode("|", $Cfg['cats'])))  {

	// if the second 'directory' is a category, we use that to filter whatever comes after..

	$filtercat = $ye;

	// we need to shift the year, month and day;
	$ye = $mo;
	$mo = $da;
	$da = $name;

}


// show the appropriate pages:
if (isset($ye) && isset($mo) && isset($da) && is_numeric($ye) && is_numeric($mo) && is_numeric($da)) {

	//echo "1";
	make_archive_list($ye, $mo, $da, $name);

} else if (isset($ye) && isset($mo) && is_numeric($ye) && is_numeric($mo)) {

	//echo "2";
	make_archive_list($ye, $mo, "", $da);

} else if (isset($ye) && is_numeric($ye)) {

	//echo "3";
	make_archive_list($ye, "", "", $mo);

} else {
	// just show the default page:
	//echo "4";
	make_default();
}

?>
