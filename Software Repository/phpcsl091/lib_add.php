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
echo '<h1>Add new library</h1>';

if(isset($_POST['add_lib_form'])) {
	lib_add($_POST['new_lib']);
	if(file_exist($_POST['new_lib'])) {
		echo '<br><div align=center><b>Library Added !!</b></br>';
		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
		echo '<script language=javascript>
		document.location="index.php";
		</script>';
	} else {
		echo "ERROR: please try again in a few momments.";
	}
	
}

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
echo '<b>Library title:</b>&nbsp;<input type=text name=new_lib></td></tr><tr><td align="center" class="fr2">';
echo '<input type=submit class=submit name=add_lib_form value=submit></td></tr></table>';
echo '<input type=hidden name=op value='.$_GET['op'].'>';
echo '</table>';
?>