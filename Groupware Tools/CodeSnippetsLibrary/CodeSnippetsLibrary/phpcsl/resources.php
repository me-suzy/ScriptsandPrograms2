<?
/*
* Project:      PHP-CSL (PHP Code Snippet Library)
* Version:      0.9
* Date:         2005/02/09 (y/m/d)
* Author:       Stuart Cochrane
* URL:          http://www.php-csl.com
* Sourceforge:  http://www.sourceforge.net/projects/php-csl/
*
* Read: reame.txt, install.txt, license.txt
*
*
*
*
*
*/
?>

<br>
<h1>Sites worth a visit</h1>
<ul>
<?php
// feel free to add to this list
$res = array(
        "PopScript.com" =>
			array("Turnkey PHP Websites - Pre Built and Ready to Run." => "http://www.popscript.com"),
        "WebmasterStaff.com"  =>
			array("Get Your own PPC Search Engine or Complete Affiliate Network or Payment Processor." => "http://www.webmasterstaff.com"),
		);
		
foreach ($res as $t => $d) {
	foreach($d as $des => $url) {
		echo '<li><a target="_blank" href="'.$url.'">'.$t.'</a> - ('.$des.')</li><br><br>';
	}
}
?>
</ul>