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

echo '<h1>Delete a Snippet</h1>';

if(isset($_POST['snips_delete']) || isset($_GET['snips_delete'])) {
	if(!isset($_GET['snips_delete'])) {
		$snips = $_POST['snips'];
		$cats = $_POST['cats'];
	} else {
		$snips = $_GET['snips_delete'];
		$cats = $_GET['cats'];
	}
	// double check that the user wishes to delete the selected snippet.
	if(!isset($_POST['confirm'])) {
    	echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
        echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
        echo '<font color=red>Are you sure you want to delete \' '.$snips.' \' ?</font></td></tr><tr><td align=center class="fr2">';
        echo '<input type=hidden name=confirm value=yes>';
		echo '<input type=hidden name=snips value='.$snips.'>';
		echo '<input type=hidden name=cats value='.$cats.'>';
		echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=No>&nbsp;&nbsp;';
		echo '<input type=submit class=submit name=snips_delete value=Yes>';
        echo '</td></tr></table></form>';
	} else {
		// has the user has confirmed the deletion ??
		if($_POST['confirm'] == "yes") {
    		if(file_delete($_POST['cats'],$_POST['snips'])) {
        		echo '</br><b>Snippet Deleted !!</b></br>';
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
	echo "Oppps... you should not see this message, this page should not be accessed directly !";
}
?>