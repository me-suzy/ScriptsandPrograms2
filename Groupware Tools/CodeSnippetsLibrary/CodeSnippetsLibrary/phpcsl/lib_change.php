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

echo '<h1>Change active library</h1>';

if(isset($_POST['libs_change'])) {
    		if(libs_change($_POST['libs'])) {
        		echo '</br><b>Library Changed !!</b></br>';
        		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
                echo '<script language=javascript>
                document.location="index.php";
                </script>';
			} else {
        		echo 'some type of error happend.</br>';
        	}
	 
} else {

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="400" border="0"><tr><td class="fr1">';
echo '<b>Select library:</b> &nbsp; <select name="libs">';
$all_libs = libs_get();
foreach($all_libs as $lib) { 
	if(strlen($lib) >1) {
		echo "<option value=$lib";
			if(isset($_GET['libs'])) {
				if($_GET['libs'] == $lib) {
					echo " selected ";
				}
			}
		echo ">$lib</option>\n";
	}
}
echo '</select></td></tr>';
echo '<tr><td align="center" class="fr2">';
echo '<input type=button class=submit name=cancel OnClick="document.location=\'index.php\'" value=Cancel>&nbsp;&nbsp;';
echo '<input type=submit class=submit name=libs_change value=Change>';
echo '</td></tr></table></form>';

}
?>