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

echo '<h1>Delete a library</h1>';

if(isset($_POST['libs_delete']) || isset($_GET['libs_delete'])) {
	// double check that the user wishes to delete the selected category.
	if(!isset($_POST['confirm'])) {
		if(!isset($_POST['libs'])) {
			$cats = $_GET['libs_delete'];
		} else {
			$cats = $_POST['libs'];
		}
    	echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
        echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
        echo '<font color=red>Are you sure you want to delete \' '.$libs.' \' ?</font></td></tr><tr><td align="center" class="fr2">';
        echo '<input type=hidden name=confirm value=yes>';
		echo '<input type=hidden name=libs value='.$libs.'>';
		echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=NO>&nbsp;&nbsp;';
		echo '<input type=submit class=submit name=libs_delete value=Yes>';
        echo '</td></tr></table></form>';
	} else {
		// has the user has confirmed the deletion ??
		if($_POST['confirm'] == "yes") {
		
    		libs_delete($_POST['libs']);
			// need to check the default library!!
			if("/".$_POST['libs'] == $snippet_library) {
				new_default();
			}

	        		echo '</br><b>Library Deleted !!</b></br>';
	        		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
	                echo '<script language=javascript>
	                document.location="index.php";
	                </script>';
		}
	}
	
} else {

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
echo 'Select category: &nbsp;<select name="libs">';
$all_libs = libs_get();
foreach($all_libs as $lib) { 
	if(strlen($lib) >1) {
		echo "<option value=$lib>$lib</option>\n";
	}
}
echo '</select></td></tr><tr><td align=center class="fr2">';
echo '<input type=submit class=submit name=libs_delete value=submit>';
echo '</td></tr></table></form>';

}
?>