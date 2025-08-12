<?php

// May need this if you've got a big tree
// set_time_limit(120);

// The meat
include 'CSitemapDirectory.php';

// Start processing at the document root
$sm = new CSitemapDirectory($GLOBALS['DOCUMENT_ROOT']);

// Now we just have to pick a SitemapSaver...
//
// CSitemapEchoSaver() 
// input:	none
// output:	HTML list to standard output (browser)
//
// CSitemapFileSaverPHPArray($path)
// input:	filename to save array to
// output:	plain old PHP array, path as key, title as value
//
// OR... write your own (see CSitemapSaver.php)

$sm->save(new CSitemapEchoSaver());
//$sm->save(new CSitemapFileSaverPHPArray('sitemap.php'));

?>