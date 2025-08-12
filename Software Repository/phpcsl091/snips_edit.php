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

echo '<h1>Edit a Snippet</h1>';

if(isset($_POST['snips_edit_form'])) {
	// delete the original snippet first
	if(file_delete($_POST['cats'],$_POST['old_snip'])) {
	$ob = new SNIPS;
	if($ob->snips_create($_POST['cats'], $_POST['title'], $_POST['syntax'], $_POST['description'])) {
		
		$reg = new regex;
		$reg->set_string($_POST['title']);
		$title = $reg->get_string();
		
		echo '</br><b>Snippet Edited !!</b></br>';
		echo '<a href=index.php?cat_select='.$_POST['cats'].'&show='.$title.'>click here to refresh and see changes.</a></br></div>';
		echo '<script language=javascript>
		document.location="index.php?cat_select='.$_POST['cats'].'&show='.$title.'";
		</script>';
	} else {
		echo 'some type of error happend, probably that a snippet with that name already exists</br>';
	}
	}
} else {

// open the selected snippet
$file_s = $_GET['cats'].".".$_GET['snip'].$snippet_ext_s;
$file_d = $_GET['cats'].".".$_GET['snip'].$snippet_ext_d;
$syntax = file_open($file_s);
$description = file_open($file_d);
echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';
echo '<table width="600" border="0"><tr><td class="fr1">';
echo '<b>Edit title:</b>&nbsp;<input type=text maxlength=60 size=60 name=title value='.$_GET['snip'].'></td></tr><tr><td class="fr2">';

echo '<b>Edit Syntax below:</b></br><textarea name=syntax rows=16 cols=60>'.stripslashes(htmlspecialchars($syntax)).'</textarea></td></tr><tr><td class="fr1">';
echo '<b>Edit Description below:</b></br><textarea name=description rows=10 cols=60>'.stripslashes(htmlspecialchars($description)).'</textarea></td></tr><tr><td class="fr2" align=center>';

echo '<input type=hidden name=cats value='.$_GET['cats'].'>';
echo '<input type=hidden name=old_snip value='.$_GET['snip'].'>';
echo '<input type=button class=submit name=cancel OnClick="document.location=\''.$_SERVER['HTTP_REFERER'].'\'" value=Cancel>&nbsp;&nbsp;';
echo '<input type=submit class=submit name=snips_edit_form value=Edit>';
echo '</td></tr></table></form>';
}
?>