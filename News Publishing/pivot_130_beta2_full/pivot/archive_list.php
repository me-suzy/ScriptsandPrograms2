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

/* example usage:

Whereever you wish to include a list of the archives, include the following
bit of code in one of your PHP files:

<?php

$archive_format= "<a href='%url%'>%st_monname% %st_year%</a><br />";
$archive_weblog="my_weblog";

include "pivot/archive_list.php";

?>

If you omit the $archive_format, it will use the formatting you've set in the
weblog's configuration. If you don't know the weblog's internal name (for the
$archive_weblog setting), you can look it up in Administration » File Mappings.

*/

// --------------------


// lamer protection
if (strpos($pivot_path,"ttp://")>0) { die('no');}
$scriptname = basename((isset($_SERVER['PATH_INFO'])) ? $_SERVER['PATH_INFO'] : $_SERVER['PHP_SELF']);
if ($scriptname=="archive_list.php") { die('no'); }
$checkvars = array_merge($_GET , $_POST, $_SERVER, $_COOKIE);

// convert encoding to UTF-8
i18n_array_to_utf8($checkvars, $dummy_variable);

if ( (isset($checkvars['pivot_url'])) || (isset($checkvars['log_url'])) || (isset($checkvars['pivot_path'])) ) {
	die('no');
}
// end lamer protection


chdir("pivot");
include "pv_core.php";

// suppress debug messages..
$Cfg['debug'] = 0;

if (isset($Weblogs[$archive_weblog])) {
	$Current_weblog = $archive_weblog;
} else {
	$Current_weblog = key($Weblogs);
}

if (isset($archive_format) && (strlen($archive_format)>1) ) {
	$Weblogs[$Current_weblog]['archive_linkfile'] = $archive_format;
}

echo snippet_archive_list();

?>
