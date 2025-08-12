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

if(isset($_GET['op'])) {
?>
<br><br>
<h1>Coding Credits</h1>
<ul>
	<li>Author: Stuart Cochrane</li>
	<li>Date: 2005-02-09</li>
	<li>URL: <a href="http://www.php-csl.com">www.php-csl.com</a></li>
	<li>License: GPL</li>
</ul>
<br>
<h1>Other Credits</h1>
<ul>
	<li>Thanks to Sourceforge.net.</li>
	<li>Thanks to all bug reporters over the years.</li>
	<li>Thanks to the thousands of PHP-CSL users worldwide.</li>
</ul>

<?
} else {
	echo "Oppps... you should not see this message, this page should not be accessed directly !";
}
?>