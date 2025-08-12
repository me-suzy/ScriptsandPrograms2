<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ##################### //FAQ FUNCTIONS\\ ################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// not too many here
// mainly just caching FAQ entities
function cacheFAQEntities() {
	$entities = query("SELECT * FROM faq ORDER BY parent ASC");

	// if rows, loop
	if(mysql_num_rows($entities)) {
		while($faq = mysql_fetch_array($entities)) {
			$faqinfo[$faq['parent']][$faq['display_order']][$faq['faqid']] = $faq;
		}

		// sort
		foreach($faqinfo as $parent => $value) {
			// sort by display order
			ksort($faqinfo[$parent]);
		}

		return $faqinfo;
	}

	return false;
}

// loop through FAQ entities
function recurseEntities($start = -1,$dropDownNav = false) {
	global $faqinfo, $faqbits, $colors;

	// make sure is array first
	if(!is_array($faqinfo["$start"])) {
		return;
	}

	foreach($faqinfo["$start"] as $displayOrder => $arr2) {
		foreach($arr2 as $faqid => $arr) {
			$arr['message'] = nl2br($arr['message']);

			// if category get template
			if($arr['is_category'] == 1) {
				if(!$dropDownNav) {
					eval("\$faqbits .= \"".getTemplate("faq_category")."\";");
				} else {
					eval("\$faqbits .= \"".getTemplate("faq_select_category")."\";");
				}
			}

			else {
				if(!$dropDownNav) {
					eval("\$faqbits .= \"".getTemplate("faq_item")."\";");
				} else {
					eval("\$faqbits .= \"".getTemplate("faq_select_item")."\";");
				}
			}

			recurseEntities($faqid, $dropDownNav);

			if($arr['is_category'] == 1 AND !$dropDownNav) {
				eval("\$faqbits .= \"".getTemplate("faq_divider")."\";");
			}
		}
	}

	return $faqbits;
}

?>