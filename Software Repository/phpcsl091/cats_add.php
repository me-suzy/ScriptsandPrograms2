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
echo '<h1>Add new Category</h1>';

if(isset($_POST['add_snip_form'])) {
	if(cats_add($_POST['new_cat'])) {
		echo '<br><div align=center><b>Category Added !!</b></br>';
		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
		echo '<script language=javascript>
		document.location="index.php";
		</script>';
	} else {
		echo 'some type of error happend, probably that a category with that name already exists</br>';
	}
}

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
echo '<b>Category title:</b>&nbsp;<input type=text name=new_cat></td></tr><tr><td align="center" class="fr2">';
echo '<input type=submit class=submit name=add_snip_form value=submit></td></tr></table>';
echo '<input type=hidden name=op value='.$_GET['op'].'>';
echo '</table>';
?>