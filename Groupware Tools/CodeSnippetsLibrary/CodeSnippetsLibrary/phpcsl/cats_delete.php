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

echo '<h1>Delete a Category</h1>';

if(isset($_POST['cats_delete']) || isset($_GET['cats_delete'])) {
	// double check that the user wishes to delete the selected category.
	if(!isset($_POST['confirm'])) {
		if(!isset($_POST['cats'])) {
			$cats = $_GET['cats_delete'];
		} else {
			$cats = $_POST['cats'];
		}
    	echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
        echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
        echo '<font color=red>Are you sure you want to delete \' '.$cats.' \' ?</font></td></tr><tr><td align="center" class="fr2">';
        echo '<input type=hidden name=confirm value=yes>';
		echo '<input type=hidden name=cats value='.$cats.'>';
		echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=NO>&nbsp;&nbsp;';
		echo '<input type=submit class=submit name=cats_delete value=Yes>';
        echo '</td></tr></table></form>';
	} else {
		// has the user has confirmed the deletion ??
		if($_POST['confirm'] == "yes") {
    		if(cats_delete($_POST['cats'])) {
        		echo '</br><b>Category Deleted !!</b></br>';
        		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
                echo '<script language=javascript>document.location="index.php";</script>';
			} else {
        	   echo 'some type of error happend.</br>';
        	}
		}
	}
	
	
} else {

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
echo 'Select category: &nbsp;<select name="cats">';
$all_cats = cats_get();
foreach($all_cats as $cat) { 
	if(strlen($cat) >1) {
		echo "<option value=$cat>$cat</option>\n";
	}
}
echo '</select></td></tr><tr><td align=center class="fr2">';
echo '<input type=submit class=submit name=cats_delete value=submit>';
echo '</td></tr></table></form>';

}
?>