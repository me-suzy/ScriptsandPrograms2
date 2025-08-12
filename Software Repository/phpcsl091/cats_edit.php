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

echo '<h1>Rename a Category</h1>';

if(isset($_POST['cats_edit'])) {
	// double check that the user wishes to edit the selected category.
	if(!isset($_POST['confirm'])) {
		$cats = $_POST['cats'];
		$new_cat = $_POST['new_cat'];
		
    	$reg = new regex;
		$reg->set_string($new_cat);
		$new_cat = $reg->get_string();
		
    	echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
        echo '<table width="400" border="0"><tr><td class="fr1" align="center">';
        echo '<font color=red>Are you sure you want to rename \' '.$cats.' \' to \' '.$new_cat.' \'?</font></td></tr><tr align=center><td class="fr2">';
        echo '<input type=hidden name=confirm value=yes>';
		echo '<input type=hidden name=cats value='.$cats.'>';
		echo '<input type=hidden name=new_cat value='.$new_cat.'>';
		echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=No>&nbsp;&nbsp;';
		echo '<input type=submit class=submit name=cats_edit value=Yes>';
        echo '</td></tr></table></form>';
	} else {
		// has the user has confirmed the ammendment ??
		if($_POST['confirm'] == "yes") {
    		if(cats_edit($_POST['cats'], $_POST['new_cat'])) {
        		echo '</br><b>Category Edited !!</b></br>';
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
echo '<b>Select category:</b> &nbsp; <select name="cats">';
$all_cats = cats_get();
foreach($all_cats as $cat) { 
	if(strlen($cat) >1) {
		echo "<option value=$cat";
			if(isset($_GET['cats'])) {
				if($_GET['cats'] == $cat) {
					echo " selected ";
				}
			}
		echo ">$cat</option>\n";
	}
}
echo '</select></td></tr><tr><td class="fr2">';
echo '<b>Enter new title:</b>&nbsp;<input type=text maxlength=20 size=20 name=new_cat></td></tr>';
echo '<tr><td align="center" class="fr1">';
echo '<input type=button class=submit name=cancel OnClick="document.location=\'index.php\'" value=Cancel>&nbsp;&nbsp;';
echo '<input type=submit class=submit name=cats_edit value=Edit>';
echo '</td></tr></table></form>';

}
?>