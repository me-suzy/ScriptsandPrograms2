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

// if page being called using iframe we need to include the config files!!
if(!function_exists('file_exist')) {
	include 'config.php';
	include 'functions.php';
	echo '<link rel="stylesheet" href="'.$snippet_theme.'/phpcsl_iframe.css" type="text/css">';
}

	$d = $_GET['cat_select'].".".$_GET['show'].$snippet_ext_d;
	if(file_open($d)) {
		$d = file_open($d);
		$d = stripslashes($d);
		if(strlen($d) >0) {
			if(isset($show_half)) {
				echo substr($d, 0, 100)."...";
			} else {
				$src = nl2br(htmlentities($d));
			}
		} else {
			echo "<i>No description available...</i>";
		}
	} else {
		echo "No description available...";
	}
if(isset($src)) {
?>
<table>
<tr>
<td style="vertical-align: top; padding-right: 3pt;">&nbsp;</td>
<td style="text-align: left; vertical-align: top; padding-left: 3pt">
<?=$src?>
</td>
</tr>
</table>
<? } ?>