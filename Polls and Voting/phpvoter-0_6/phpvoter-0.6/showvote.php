<?php
// We need some common functions
require("includes/functions.inc.php");

// Print the active question if the user hasn't already answered it.
if (!hasalreadyvoted()) {
	print letsvote("active", $config['votescript']);
} else {
	$linkvote = linktoactivevote();
	if ($linkvote != "") {
		print $lang['alreadyvoted'];
		print " <a href=\"$linkvote\">{$lang['viewresults']}</a>\n";
	} else {
		print $lang['no_votes_in_db'];
	}
}

if ($debug) {
	print $strError;
}

?>
