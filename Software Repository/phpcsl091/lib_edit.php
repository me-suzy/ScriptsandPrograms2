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

echo '<h1>Rename a library</h1>';

if(isset($_POST['libs_edit'])) {
	// double check that the user wishes to edit the selected category.
	if(!isset($_POST['confirm'])) {
		$libs = $_POST['libs'];
		$new_lib = $_POST['new_lib'];
		
    	$reg = new regex;
		$reg->set_string($new_lib);
		$new_lib = $reg->get_string();
		
    	echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
        echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
        echo '<font color=red>Are you sure you want to rename \' '.$libs.' \' to \' '.$new_lib.' \'?</font></td></tr><tr align=center><td class="fr2">';
        echo '<input type=hidden name=confirm value=yes>';
		echo '<input type=hidden name=libs value='.$libs.'>';
		echo '<input type=hidden name=new_lib value='.$new_lib.'>';
		echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=No>&nbsp;&nbsp;';
		echo '<input type=submit class=submit name=libs_edit value=Yes>';
        echo '</td></tr></table></form>';
	} else {
		// has the user has confirmed the ammendment ??
		if($_POST['confirm'] == "yes") {
    		if(libs_edit($_POST['libs'], $_POST['new_lib'])) {
        		echo '</br><b>Library Edited !!</b></br>';
        		echo '<a href=index.php>click here to refresh and see changes.</a></br></div>';
                echo '<script language=javascript>
                document.location="index.php";
                </script>';
			} else {
        		echo 'some type of error happend.</br>';
        	}
		}
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
echo '</select></td></tr><tr><td class="fr2">';
echo '<b>Enter new library:</b>&nbsp;<input type=text maxlength=20 size=20 name=new_lib></td></tr>';
echo '<tr><td align="center" class="fr1">';
echo '<input type=button class=submit name=cancel OnClick="document.location=\'index.php\'" value=Cancel>&nbsp;&nbsp;';
echo '<input type=submit class=submit name=libs_edit value=Edit>';
echo '</td></tr></table></form>';

}
?>