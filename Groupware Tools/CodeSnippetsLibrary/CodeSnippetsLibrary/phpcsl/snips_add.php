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



if(isset($_POST['snips_add_form'])) {
	if(!isset($_POST['cats'])) {
		echo "You should really have added a category before you try and add a snippet !!";
		die;
	}
	$ob = new SNIPS;
	
	echo '<script language="javascript">alert("'.$_POST['syntax'].'");</script>';
	
	if($ob->snips_create($_POST['cats'], $_POST['title'], $_POST['syntax'], $_POST['description'])) {
		
		$title = $_POST['title'];
		$reg = new regex;
		$reg->set_string($title);
		$new_title = $reg->get_string();
		
		echo '</br><b>Snippet Added !!</b></br>';
		echo '<a href=index.php?cat_select='.$_POST['cats'].'&show='.$new_title.'>click here to refresh and see changes.</a></br></div>';
		echo '<script language=javascript>
		document.location="index.php?cat_select='.$_POST['cats'].'&show='.$new_title.'";
		</script>';
	} else {
		echo 'some type of error happend, probably that a snippet with that name already exists</br>';
	}
}
echo '<h1>Add new Snippet</h1>';

echo '</br><form action='.$_SERVER["PHP_SELF"].'?'.$_SERVER["QUERY_STRING"].' method=post>';

$all_cats = cats_get();

if(count($all_cats) < 2) {
	$show_buttons="no";
	echo '<font color="red"><b>Please add a Category first!!</b></font><br>';
}

echo '<table width="600" border="0"><tr><td class="fr1">';
echo '<b>Select category:</b> &nbsp;<select name="cats">';
foreach($all_cats as $cat) { 
	if(strlen($cat) >1) {
		echo "<option value=$cat>$cat</option>\n";
	}
}

echo '</select></td></tr><tr><td class="fr2">';
echo '<b>Snippet title:</b>&nbsp;<input type=text maxlength=60 size=60 name=title></td></tr><tr><td class="fr1">';
echo '<b>Enter Syntax below:</b></br><textarea name=syntax rows=16 cols=60></textarea></td></tr><tr><td class="fr2">';
echo '<b>Enter Description below:</b></br><textarea name=description rows=10 cols=60></textarea></td></tr><tr align=center><td class="fr1" align="center">';

if(!isset($show_buttons)) {
	echo '<input type=button class=submit name=cancel OnClick="document.location=\'index.php\'" value=Cancel>&nbsp;&nbsp;';
	echo '<input type=submit class=submit name=snips_add_form value=Add>';
}
echo '</td></tr></table></form>';
?>
