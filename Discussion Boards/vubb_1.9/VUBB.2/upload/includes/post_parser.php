<?php
// main post parser
function main_post_parser()
{
	global $site_config;
	
	$_POST['body1'] = htmlspecialchars($_POST['body1']);
	
	// try to remove XSS Cross-site scripting issues.
	$_POST['body1'] = preg_replace("#javascript:#i", "java script:", $_POST['body1']);
	$_POST['body1'] = preg_replace("#alert#i", "&#097;lert", $_POST['body1']);
	$_POST['body1'] = preg_replace("#onmouseover#i", "&#111;nmouseover", $_POST['body1']);
	$_POST['body1'] = preg_replace("#onclick#i", "&#111;nclick", $_POST['body1']);
	$_POST['body1'] = preg_replace("#onload#i", "&#111;nload", $_POST['body1']);
	$_POST['body1'] = eregi_replace("#onsubmit#i", "&#111;nsubmit", $_POST['body1']);
		
	// if no magic addslashes
	if(!get_magic_quotes_gpc()) 
	{
		$_POST['body1'] = addslashes($_POST['body1']);
	}
}

function view_post($view)
{
	global $site_config;
	
	//urls	
	$chr_limit = '45';
	$add = '...';
	
	$view = preg_replace("!(http:/{2}[\w\.]{2,}[/\w\-\.\?\&\=\#]*)!e", "'<a href=\"\\1\" title=\"\\1\" target=\"_blank\">'.(strlen('\\1')>=$chr_limit ? substr('\\1',0,$chr_limit).'$add':'\\1').'</a>'", $view);
	
	// Smilies
	$grab_smilies = mysql_query("SELECT * FROM `smilies` ORDER BY `id`");
	while ($echo_smilies = mysql_fetch_array($grab_smilies))
	{
		$echo_smilies['code'] = htmlspecialchars($echo_smilies['code']);
		$view = str_replace($echo_smilies['code'],"<img src=\"{$site_config['site_url']}{$echo_smilies['image']}\" />",$view);
	}
	
	// Bold code
	$view = str_replace("[b]","<strong>",$view);
	$view = str_replace("[/b]","</strong>",$view);
		
	// Underline code
	$view = str_replace("[u]","<u>",$view);
	$view = str_replace("[/u]","</u>",$view);
		
	// Italic code
	$view = str_replace("[i]","<em>",$view);
	$view = str_replace("[/i]","</em>",$view);
		
	// New line code
	$view = str_replace("\n","<br />",$view);
	
	// images
	$view = str_replace('[img]', '<img border="0" src="', $view);
	$view = str_replace('[/img]', '" alt="user posted image">', $view);
	
	// quote
	$view = str_replace('[quote]', '<div class="quote">', $view);
	$view = str_replace('[/quote]', '</div>', $view);
	
	// code
	$view = str_replace('[code]', '<font class="code">', $view);
	$view = str_replace('[/code]', '</font>', $view);
	
	return $view;
}


// edit post parser
function edit_post_parser($edit)
{	
	global $site_config;
	
	/// To display correctly for editing!	
	// Replace new lines
	$edit = str_replace("<br />","\n",$edit);
		
	// Strip the slashes
	$edit = stripslashes($edit);
	
	return $edit;
}
?>